<?php session_start(); ?>
<?php ob_start(); ?>

<?php include ("header.php") ?>


<?php
$ewCurSec = 0; // Initialise

// User levels
define("ewAllowAdd", 1, true);
define("ewAllowDelete", 2, true);
define("ewAllowEdit", 4, true);
define("ewAllowView", 8, true);
define("ewAllowList", 8, true);
define("ewAllowReport", 8, true);
define("ewAllowSearch", 8, true);																														
define("ewAllowAdmin", 16, true);						
?>
<?php
if (@$_SESSION["php_project_esf_status"] <> "login") {
	echo "No ha ingresado su clave de acceso.";
	exit();
}
?>
<?php

// Initialize common variables
$x_credito_comment_id = Null;
$x_credito_id = Null;
$x_comentario_int = Null;
$x_comentario_ext = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php


$x_credito_id = $_SESSION["php_project_esf_status_UserName"];
if(empty($x_credito_id)){
	$x_credito_id = @$_POST["x_credito_id"];
	if(empty($x_credito_id)){
		echo "No se locaizaron los comentarios del credito.";
		exit();
	}
}

// Get action
$sAction = @$_POST["a_add"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I"; // Display blank record
}
else
{

	// Get fields from form
	$x_credito_comment_id = @$_POST["x_credito_comment_id"];
//	$x_credito_id = @$_POST["x_credito_id"];
	$x_comentario_int = @$_POST["x_comentario_int"];
	$x_comentario_ext = @$_POST["x_comentario_ext"];
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			#echo "<p align='center'>Los comentarios han sido enviados.</p>";
				$_SESSION["ewmsg"] = "LOS COMENTARIOS FUERON ENVIADOS AL OFICIAL DE CUMPLIMIENTO, GRACIAS!";
				?>
				<br /><br /><center><p><span class="phpmaker" style="color: Red;"><?php echo $_SESSION["ewmsg"]; ?></span></p></center>
                <?php
				die();
		}
		break;
}
?>

<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<script type="text/javascript">
<!--
function EW_checkMyForm(EW_this) {
return true;
}
<?php

if (@$_SESSION["ewmsg"] <> "") {

?>

<p><span class="phpmaker" style="color: Red;"><?php echo $_SESSION["ewmsg"]; ?></span></p>
<?php }?>

//-->
</script>
<p align="center"><span class="phpmaker">Enviar E-mail al Oficial de Cumplimiento para notificar operaciones internas preocupantes<br>
  <br>
    <p>
<form name="credito_commentadd" id="credito_commentadd" action="" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_add" value="A">
<input type="hidden" name="x_credito_id" value="<?php echo $x_credito_id; ?>">
<table width="530" align="center" >
	<tr>
		<td colspan="2" class="ewTableAltRow"><span><b>Usuario:</b></span>
		  <?php  echo $_SESSION["php_project_esf_status_UserName"];?>
         

        </span></td>
    </tr>
	<tr>
	  <td width="332" class="ewTableHeader">Contenido del mail</td>
      
	</tr>
	<tr>
		<td class="ewTableAltRow" align="center">
		  <textarea name="x_comentario_int" cols="170" rows="20" id="x_comentario_int"><?php echo @$x_comentario_int; ?></textarea>
		</td>
	  </tr>
</table>
<p align="center">
<input type="submit" name="Action" value="Enviar E-mail">
</p>
</form>

<?php



// Close recordset and connection

phpmkr_free_result($rs);

phpmkr_db_close($conn);

?>

<?php if ($sExport <> "word" && $sExport <> "excel") { ?>

<?php include ("footer.php") ?>

<?php } ?>
<?php

//-------------------------------------------------------------------------------
// Function LoadData
// - Load Data based on Key Value sKey
// - Variables setup: field variables

function LoadData($sKey,$conn)
{
	$sKeyWrk = "" . addslashes($sKey) . "";
	$sSql = "SELECT * FROM `credito_comment`";
	$sSql .= " WHERE `credito_comment_id` = " . $sKeyWrk;
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sGroupBy <> "") {
		$sSql .= " GROUP BY " . $sGroupBy;
	}
	if ($sHaving <> "") {
		$sSql .= " HAVING " . $sHaving;
	}
	if ($sOrderBy <> "") {
		$sSql .= " ORDER BY " . $sOrderBy;
	}
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
	if (phpmkr_num_rows($rs) == 0) {
		$LoadData = false;
	}else{
		$LoadData = true;
		$row = phpmkr_fetch_array($rs);

		// Get the field contents
		$GLOBALS["x_credito_comment_id"] = $row["credito_comment_id"];
		$GLOBALS["x_credito_id"] = $row["credito_id"];
		$GLOBALS["x_comentario_int"] = $row["comentario_int"];
		$GLOBALS["x_comentario_ext"] = $row["comentario_ext"];
	}
	phpmkr_free_result($rs);
	return $LoadData;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function AddData
// - Add Data
// - Variables used: field variables

function AddData($conn)
{

	/*// Field credito_id
	$theValue = ($GLOBALS["x_credito_id"] != "") ? intval($GLOBALS["x_credito_id"]) : "NULL";
	$fieldList["`credito_id`"] = $theValue;

	// Field comentario_int
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_comentario_int"]) : $GLOBALS["x_comentario_int"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`comentario_int`"] = $theValue;

	// Field comentario_ext
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_comentario_ext"]) : $GLOBALS["x_comentario_ext"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`comentario_ext`"] = $theValue;

	// insert into database
	$strsql = "INSERT INTO `credito_comment` (";
	$strsql .= implode(",", array_keys($fieldList));
	$strsql .= ") VALUES (";
	$strsql .= implode(",", array_values($fieldList));
	$strsql .= ")";
	phpmkr_query($strsql, $conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $strsql);*/
	
	
	// se registra la alerta
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_comentario_int"]) : $GLOBALS["x_comentario_int"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$x_mensaje = $theValue;
				
						$para  = 'oficialdecumplimiento@financieracrea.com'; // atencion a la coma
						// subject
						$titulo = 'E-MAIL ENVIADO POR '.$_SESSION["php_project_esf_status_UserName"]."";						
						$cabeceras = 'From: zortiz@createc.mx';									
						$mensajeMail = $x_mensaje."\n \n * Este mensaje ha sido enviado de forma automatica, por favor no lo responda. \n \n";			 
						// Mail it						
						mail($para, $titulo, $mensajeMail, $cabeceras);
						
					
						
	return true;
}
?>
