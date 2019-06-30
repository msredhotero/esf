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
	echo "No ha ingresado su clave de acceso.";
	exit();
}
?>
<?php

// Initialize common variables
$x_credito_comment_id = Null;
$x_recibo_qr_id = Null;
$x_comentario_int = Null;
$x_comentario_ext = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php


$x_recibo_qr_id = @$_GET["key"];
if(empty($x_recibo_qr_id)){
	$x_recibo_qr_id = @$_POST["x_recibo_qr_id"];
	if(empty($x_recibo_qr_id)){
		echo "No se locaizaron los comentarios del recibo QR.";
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
//	$x_recibo_qr_id = @$_POST["x_recibo_qr_id"];
	$x_comentario_int = @$_POST["x_comentario_int"];
	$x_comentario_ext = @$_POST["x_comentario_ext"];
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($sKey,$conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No Record Found for Key = " . $sKey;
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_credito_commentlist.php");
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			echo "<p align='center'>Los comentarios han sido registrados.</p>";
			echo "
			<script type='text/javascript'>
			<!--
				window.opener.document.frm_visor_qr.submit();
			//-->
			</script>";
		}
		break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Financiera CRECE</title>
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
return true;
}

//-->
</script>
<p align="center"><span class="phpmaker">Comentarios de Responsable Sucursal<br>
  <br>
<a href="javascript: window.close();">Cerrar ventana</a></span></p>
<form name="credito_commentadd" id="credito_commentadd" action="php_recibo_qr_commentadd.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_add" value="A">
<input type="hidden" name="x_recibo_qr_id" value="<?php echo $x_recibo_qr_id; ?>">
<table width="530" align="center" >
	<tr>
		<td colspan="2" class="ewTableAltRow"><span><b>Recibo QR folio:</b></span>
		  <?php if (!(!is_null($x_recibo_qr_id)) || ($x_recibo_qr_id == "")) { $x_recibo_qr_id = 0;} // Set default value ?>
          <?php
$sSqlWrk = "SELECT folio FROM recibo_qr where recibo_qr_id = $x_recibo_qr_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
echo "- ". $datawrk["folio"]." -";
@phpmkr_free_result($rswrk);
?>
        </span></td>
    </tr>
	<tr>
	  <td width="332" class="ewTableHeader">Bitacora Responsable Sucursal</td>
     
	</tr>
	<tr>
		<td class="ewTableAltRow" align="center">
		  <textarea name="x_comentario_int" cols="50" rows="10" id="x_comentario_int"><?php echo @$x_comentario_int; ?></textarea>
		</td>
	 
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
		$GLOBALS["x_recibo_qr_id"] = $row["credito_id"];
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
	global $x_recibo_qr_id;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_comentario_int"]) : $GLOBALS["x_comentario_int"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";


	$sqlComente = "UPDATE recibo_qr SET bitacora = \"$theValue\" WHERE recibo_qr_id =$x_recibo_qr_id ";
	
	$rsComent = phpmkr_query($sqlComente,$conn) or die("Error al insertar el comentario". phpmkr_error()."sql :". $sqlComente);
	
	// Field credito_id
	$theValue = ($GLOBALS["x_recibo_qr_id"] != "") ? intval($GLOBALS["x_recibo_qr_id"]) : "NULL";
	$fieldList["`credito_id`"] = $theValue;

	// Field comentario_int
	
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
	//phpmkr_query($strsql, $conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $strsql);
	
	if($rsComent ){
	return true;
	}
}
?>
