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
<?php

// Initialize common variables
$x_fotografia_id = Null;
$x_solicitud_id = Null;
$x_fecha_registro = Null;
$x_titulo = Null;
$x_descripcion = Null;
$x_foto = Null;
$fs_x_foto = 0;
$fn_x_foto = "";
$ct_x_foto = "";
$w_x_foto = 0;
$h_x_foto = 0;
$a_x_foto = "";
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

$x_solicitud_id = @$_POST["x_solicitud_id"];

// Load Key Parameters
$sKey = @$_GET["key"];
if (($sKey == "") || ((is_null($sKey)))) {
	$sKey = @$_POST["key_d"];
}
$sDbWhere = "";
if (!is_array($sKey)) {
	$arRecKey = split(",",$sKey);
}else {
	$arRecKey = $sKey;
}

// Multiple delete records
if (count($arRecKey) <= 0) {
	ob_end_clean();
	header("Location: php_fotografialist.php");
	exit();
}
foreach ($arRecKey as $sRecKey)
{
	$sRecKey = (get_magic_quotes_gpc()) ? $sRecKey : addslashes($sRecKey);

	// Remove spaces
	$sRecKey = trim($sRecKey);

	// Build the SQL
	$sDbWhere .= "(`fotografia_id`=" . $sRecKey . ") OR ";
}
if (substr($sDbWhere, -4) == " OR ") { $sDbWhere = substr($sDbWhere, 0, strlen($sDbWhere)-4); }

// Get action
$sAction = @$_POST["a_delete"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";	// Display with input box
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Display
		if (LoadRecordCount($sDbWhere,$conn) <= 0) {
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_fotografialist.php?x_solicitud_id=$x_solicitud_id");
			exit();
		}
		break;
	case "D": // Delete
		if (DeleteData($sDbWhere,$conn)) {
			$ewmsg = (get_magic_quotes_gpc()) ? stripslashes(implode(",", array_values($sKey))) : implode(",", array_values($sKey));
			$_SESSION["ewmsg"] = "los datos han sido eliminados.";
			phpmkr_db_close($conn);
			ob_end_clean();
			echo "
			<script type=\"text/javascript\">
			<!--
			window.opener.document.listado.submit();		
			window.close(); 	
			//-->
			</script>";
			exit();
		}
		break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>e - SF >  FINANCIERA CRECE - FOTOs </title>
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
<SCRIPT TYPE="text/javascript">
<!--
window.focus();
//-->
</SCRIPT>
</head>
<body>
<p><span class="phpmaker">Eliminando Fotografias<br><br>
<a href="javascript: window.close();">Cerrar ventana</a></span></p>
<form action="php_fotografiadelete.php" method="post">
<p>
<input type="hidden" name="a_delete" value="D">
<?php
	foreach ($arRecKey as $sid) {
	$sid = (get_magic_quotes_gpc()) ? stripslashes($sid) : $sid;
?>
		<input type="hidden" name="key_d[]" value="<?php echo  htmlspecialchars($sid); ?>">
<?php
	}
?>
<table class="ewTable">
	<tr class="ewTableHeader">
		<td valign="top"><span>No</span></td>
		<td valign="top"><span>Titulo</span></td>
		<td valign="top"><span>Descripcion</span></td>
		<td valign="top"><span>Foto</span></td>
	</tr>
<?php
$nRecCount = 0;
foreach ($arRecKey as $sRecKey) {
	$sRecKey = trim($sRecKey);
	$sRecKey = (get_magic_quotes_gpc()) ? stripslashes($sRecKey) : $sRecKey;
	$nRecCount = $nRecCount + 1;

	// Set row color
	$sItemRowClass = " class=\"ewTableRow\"";

	// Display alternate color for rows
	if ($nRecCount % 2 <> 0) {
		$sItemRowClass = " class=\"ewTableAltRow\"";
	}
	if (LoadData($sRecKey,$conn)) {
?>
	<tr<?php echo $sItemRowClass;?>>
		<td><span>
<?php echo $x_fotografia_id; ?>
</span></td>
		<td><span>
<?php echo $x_titulo; ?>
</span></td>
		<td><span>
<?php echo $x_descripcion; ?>
</span></td>
		<td><span>
<?php if ((!is_null($x_foto)) &&  $x_foto <> "") { ?>
<a href="<?php echo ewUploadPath(0) . $x_foto ?>"><?php echo $x_foto; ?></a>
<?php } ?>
</span></td>
	</tr>
<?php
	}
}
?>
</table>
<p>
<input type="hidden" name="x_solicitud_id" value="<?php echo $x_solicitud_id; ?>">
<input type="submit" name="Action" value="CONFIRME">
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
	$sSql = "SELECT * FROM `fotografia`";
	$sSql .= " WHERE `fotografia_id` = " . $sKeyWrk;
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
		$GLOBALS["x_fotografia_id"] = $row["fotografia_id"];
		$GLOBALS["x_solicitud_id"] = $row["solicitud_id"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
		$GLOBALS["x_titulo"] = $row["titulo"];
		$GLOBALS["x_descripcion"] = $row["descripcion"];
		$GLOBALS["x_foto"] = $row["foto"];
	}
	phpmkr_free_result($rs);
	return $LoadData;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function LoadRecordCount
// - Load Record Count based on input sql criteria sqlKey

function LoadRecordCount($sqlKey,$conn)
{
	$sSql = "SELECT * FROM `fotografia`";
	$sSql .= " WHERE " . $sqlKey;
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
	return phpmkr_num_rows($rs);
	phpmkr_free_result($rs);
}
?>
<?php

//-------------------------------------------------------------------------------
// Function DeleteData
// - Delete Records based on input sql criteria sqlKey

function DeleteData($sqlKey,$conn)
{
	$sSql = "Delete FROM `fotografia`";
	$sSql .= " WHERE " . $sqlKey;
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
	phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
	return true;
}
?>
