<?php session_start(); ?>
<?php ob_start(); ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0
?>
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
	header("Location:  login.php");
	exit();
}
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php include("php_busca_iniciales_usuario.php");?>

<?php
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
// Initialize common variables
$x_credito_comment_id = Null;
$x_credito_id = Null;
$x_comentario_int = Null;
$x_comentario_ext = Null;

$sSqlWrk = "SELECT nombre FROM usuario WHERE usuario_id = ".$_SESSION["php_project_esf_status_UserID"];

$rswrku = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrku);
$x_nombre = $datawrk["nombre"];
@phpmkr_free_result($rswrku);


$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
$currdate = ConvertDateToMysqlFormat($currdate);
$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];		

?>
<?php

$x_credito_id = @$_GET["key"];
if(empty($x_credito_id)){
	$x_credito_id = @$_POST["x_credito_id"];
	if(empty($x_credito_id)){
		echo "No se locaizaron los comentarios del credito.";
		exit();
	}
}

// Get action
$sAction = @$_POST["a_edit"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";	// Display with input box
} else {

		$x_hoy = date("Y-m-d");
		$hoy = date("H:i:s"); 
		$x_usuaio_registrado = registraUsuario($_SESSION["php_project_esf_status_UserID"],$conn);
	// Get fields from form
	$x_credito_comment_id = @$_POST["x_credito_comment_id"];
//	$x_credito_id = @$_POST["x_credito_id"];
	if($_POST["x_comentario_int2"] != ""){		
		$x_comentario_int = @$_POST["x_com_int_dum"]."\n\n"."$x_usuaio_registrado $x_hoy  $hoy \n".@$_POST["x_comentario_int2"];
		
	}else{
		$x_comentario_int = @$_POST["x_com_int_dum"];
	}
	if($_POST["x_comentario_ext2"] != ""){
		$x_comentario_ext = @$_POST["x_com_ext_dum"]."\n\n"."$x_usuaio_registrado  $x_hoy $hoy \n".@$_POST["x_comentario_ext2"];
		 
		
	}else{
		$x_comentario_ext = @$_POST["x_com_ext_dum"];
	}
	
	$x_comentario_int = str_replace("'","-", $x_comentario_int);
	$x_comentario_ext = str_replace("'","-", $x_comentario_ext);
	$x_comentario_int = str_replace('"',"-", $x_comentario_int);
	$x_comentario_ext = str_replace('"',"-", $x_comentario_ext);
}


switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($x_credito_id,$conn)) { // Load Record based on key
			echo "No se localizaron los comentarios.";
			exit();
		}
		break;
	case "U": // Update
		if (EditData($x_credito_id,$conn)) { // Update Record based on key
			echo "<p align='center'>Los comentarios han sido registrados.</p>";
		}
		break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>CREA Technologies</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">td img {display: block;}</style>
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#FFFFFF">
<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator

//-->
</script>
<script type="text/javascript">
<!--
function EW_checkMyForm(EW_this) {
if (EW_this.x_credito_id && !EW_hasValue(EW_this.x_credito_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_credito_id, "SELECT", "Please enter required field - Credito Num"))
		return false;
}
return true;
}

//-->
</script>
<p align="center"><span class="phpmaker">Comentarios<br>
<br>
  <a href="javascript: window.close();">Cerrar ventana</a></span></p>
<form name="credito_commentedit" id="credito_commentedit" action="php_credito_commentedit.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<input type="hidden" name="x_credito_id" value="<?php echo $x_credito_id; ?>">
<input type="hidden" name="x_com_int_dum" value="<?php echo $x_comentario_int; ?>">
<input type="hidden" name="x_com_ext_dum" value="<?php echo $x_comentario_ext; ?>">

<table width="530" align="center" >
	<tr>
		<td colspan="2" class="ewTableAltRow"><span><b>Credito Num:</b></span>
		  <?php if (!(!is_null($x_credito_id)) || ($x_credito_id == "")) { $x_credito_id = 0;} // Set default value ?>
          <?php
$sSqlWrk = "SELECT credito_num FROM credito where credito_id = $x_credito_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
echo $datawrk["credito_num"];
@phpmkr_free_result($rswrk);
?>
        </span></td>
    </tr>
	<tr>
	  <td width="332" class="ewTableHeader">Comentario Interno</td>
      <td width="456" class="ewTableHeader">Comentario Externo</td>
	</tr>
	<tr>
		<td class="ewTableAltRow" align="center">
		  <textarea name="x_comentario_int" cols="40" rows="10" id="x_comentario_int"><?php echo @$x_comentario_int_aux; ?></textarea>
		</td>
	  <td class="ewTableAltRow" align="center"><textarea cols="40" rows="10" id="x_comentario_ext" name="x_comentario_ext"><?php echo @$x_comentario_ext_aux; ?></textarea></td>
	</tr>
	<tr>
	  <td class="ewTableAltRow" align="center">&nbsp;</td>
	  <td class="ewTableAltRow" align="center">&nbsp;</td>
    </tr>
	<tr>
	  <td class="ewTableAltRow" align="center">Agregar comentario</td>
	  <td class="ewTableAltRow" align="center">Agregar comentario</td>
    </tr>
	<tr>
	  <td class="ewTableAltRow" align="center"><textarea name="x_comentario_int2" cols="40" rows="10" id="x_comentario_int2"></textarea></td>
	  <td class="ewTableAltRow" align="center"><textarea cols="40" rows="10" id="x_comentario_ext2" name="x_comentario_ext2"></textarea></td>
    </tr>
</table>
<p align="center">
<input type="submit" name="Action" value="Guardar Comentarios">
</p>
</form>
</body>
</html>
<?php
phpmkr_db_close($conn);
?>
<?php

//-------------------------------------------------------------------------------
// Function LoadData
// - Load Data based on Key Value sKey
// - Variables setup: field variables

function LoadData($sKey,$conn)
{
	$sKeyWrk = "" . addslashes($sKey) . "";
	$sSql = "SELECT * FROM `credito_comment`";
	$sSql .= " WHERE `credito_id` = " . $sKeyWrk;
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
		//$GLOBALS["x_comentario_int"] = $row["comentario_int"];
		//$GLOBALS["x_comentario_ext"] = $row["comentario_ext"];
		
		$x_comentario_int = substr($row["comentario_int"],-10000) ;
		$x_comentario_ext = substr($row["comentario_ext"],-10000);
		$GLOBALS["x_comentario_int_aux"] = $x_comentario_int;
		$GLOBALS["x_comentario_ext_aux"] = $x_comentario_ext;
		
		
		
	}
	phpmkr_free_result($rs);
	return $LoadData;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function EditData
// - Edit Data based on Key Value sKey
// - Variables used: field variables

function EditData($sKey,$conn)
{

	// Open record
		
		$x_usuaio_registrado .= " ".$x_hoy ." ".$hoy." \n";
		$sKeyWrk = "" . addslashes($sKey) . "";
		$theValue = ($GLOBALS["x_credito_id"] != "") ? intval($GLOBALS["x_credito_id"]) : "NULL";		
		$fieldList["`credito_id`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_comentario_int"]) : $x_usuaio_registrado.$GLOBALS["x_comentario_int"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`comentario_int`"] = $theValue;		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_comentario_ext"]) : $GLOBALS["x_comentario_ext"];		
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`comentario_ext`"] = $theValue;
		
				// update
		//$sSql = "UPDATE `credito_comment` SET  ";
		/*foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}*/
		//$sSql .= " WHERE `credito_id` =". $sKeyWrk;
		
		$x_hoy = date("Y-m-d");
		$hoy = date("H:i:s"); 
		$x_usuaio_registrado = registraUsuario($_SESSION["php_project_esf_status_UserID"],$conn);
		
			if($_POST["x_comentario_int2"] != ""){		
		$x_comentario_int_p = "\n\n"."$x_usuaio_registrado $x_hoy  $hoy \n".@$_POST["x_comentario_int2"];
		
	}else{
		$x_comentario_int_p = "";
	}
	if($_POST["x_comentario_ext2"] != ""){
		$x_comentario_ext_p = "\n\n"."$x_usuaio_registrado  $x_hoy $hoy \n".@$_POST["x_comentario_ext2"];
		 
		
	}else{
		$x_comentario_ext_p = "";
	}
	
	$x_comentario_int = str_replace("'","-", $x_comentario_int);
	$x_comentario_ext = str_replace("'","-", $x_comentario_ext);
	$x_comentario_int = str_replace('"',"-", $x_comentario_int);
	$x_comentario_ext = str_replace('"',"-", $x_comentario_ext);
		
		
		
		$sSql = "UPDATE `credito_comment` SET comentario_int = CONCAT(credito_comment.comentario_int, \"".$x_comentario_int_p."\") ";
		$sSql .= " , comentario_ext =  CONCAT(credito_comment.comentario_ext, \"".$x_comentario_ext_p."\")  "; 
		$sSql .= " WHERE `credito_id` =". $sKeyWrk;
		phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);		
		//echo $sSql."<br>";	
		$EditData = true; // Update Successful
	return $EditData;
}
?>
