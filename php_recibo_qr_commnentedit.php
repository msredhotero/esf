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

<?php
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
// Initialize common variables
$x_credito_comment_id = Null;
$x_recibo_qr_id = Null;
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

$x_recibo_qr_id = @$_GET["key"];
if(empty($x_recibo_qr_id)){
	$x_recibo_qr_id = @$_POST["x_recibo_qr_id"];
	if(empty($x_recibo_qr_id)){
		echo "No se locaizaron los comentarios del credito.";
		exit();
	}
}

// Get action
$sAction = @$_POST["a_edit"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";	// Display with input box
} else {

	$x_recibo_qr_id = $_POST["x_recibo_qr_id"];
//	$x_recibo_qr_id = @$_POST["x_recibo_qr_id"];
	if($_POST["x_comentario_int2"] != ""){
		
		$x_comentario_int = @$_POST["x_com_int_dum"]."\n\n"."$x_nombre - (".FormatDateTime($currdate,7)." - $currtime) \n".@$_POST["x_comentario_int2"];
	}else{
		$x_comentario_int = @$_POST["x_com_int_dum"];
	}
	
}


switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($x_recibo_qr_id,$conn)) { // Load Record based on key
			echo "No se localizaron los comentarios.";
			exit();
		}
		break;
	case "U": // Update
		if (EditData($x_recibo_qr_id,$conn)) { // Update Record based on key
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
if (EW_this.x_recibo_qr_id && !EW_hasValue(EW_this.x_recibo_qr_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_recibo_qr_id, "SELECT", "Please enter required field - Credito Num"))
		return false;
}
return true;
}

//-->
</script>
<p align="center"><span class="phpmaker">Comentarios<br>
<br>
  <a href="javascript: window.close();">Cerrar ventana</a></span></p>
<form name="credito_commentedit" id="credito_commentedit" action="" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<input type="hidden" name="x_recibo_qr_id" value="<?php echo $x_recibo_qr_id; ?>">
<input type="hidden" name="x_com_int_dum" value="<?php echo $x_comentario_int; ?>">
<input type="hidden" name="x_com_ext_dum" value="<?php echo $x_comentario_ext; ?>">
<table width="530" align="center" >
	<tr>
		<td class="ewTableAltRow"><span><b>Recibo QR folio:</b></span>
		  <?php if (!(!is_null($x_recibo_qr_id)) || ($x_recibo_qr_id == "")) { $x_recibo_qr_id = 0;} // Set default value ?>
          <?php
$sSqlWrk = "SELECT folio FROM recibo_qr where recibo_qr_id = $x_recibo_qr_id";$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
echo "- ".$datawrk["folio"]." -";
@phpmkr_free_result($rswrk);
?>
        </span></td>
    </tr>
	<tr>
	  <td class="ewTableHeader">Comentario Responsable Sucursal</td>
    </tr>
	<tr>
		<td align="center" class="ewTableAltRow">
		  <textarea name="x_comentario_int" cols="50" rows="10" id="x_comentario_int"><?php echo @$x_comentario_int; ?></textarea>
		</td>
    </tr>
</table><?php if(($_SESSION["php_project_esf_status_UserRolID"] == 12)){?>
<p align="center">
<input type="submit" name="Action" value="Guardar Comentarios">
</p><?php }?>
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
	$sSql = "SELECT * FROM `recibo_qr`";
	$sSql .= " WHERE `recibo_qr_id` = " . $sKeyWrk;
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
//	echo $sSql."<br>";
//	die();
	if (phpmkr_num_rows($rs) == 0) {
		$LoadData = false;
	}else{
		$LoadData = true;
		$row = phpmkr_fetch_array($rs);

		// Get the field contents
		//$GLOBALS["x_credito_comment_id"] = $row["credito_comment_id"];
		$GLOBALS["x_recibo_qr_id"] = $row["recibo_qr_id"];
		$GLOBALS["x_comentario_int"] = $row["bitacora"];
		
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
		$sKeyWrk = "" . addslashes($sKey) . "";
		$GLOBALS["x_comentario_int"] = $_POST["x_comentario_int"];
		global $x_recibo_qr_id;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_comentario_int"]) : $GLOBALS["x_comentario_int"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";


		$sqlComente = "UPDATE recibo_qr SET bitacora = \"$theValue\" WHERE recibo_qr_id =$x_recibo_qr_id ";
		$rsComent = phpmkr_query($sqlComente,$conn) or die("Error al insertar el comentario". phpmkr_error()."sql :". $sqlComente);

		
		$EditData = true; // Update Successful

	return $EditData;
}
?>
