<?php session_start(); ?>
<?php ob_start(); ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Cache-Control: private");
header("Pragma: no-cache"); // HTTP/1.0 
?>
<?php
$ewCurSec = 0; // Initialise

// User levels
define("ewAllowadd", 1, true);
define("ewAllowdelete", 2, true);
define("ewAllowedit", 4, true);
define("ewAllowview", 8, true);
define("ewAllowlist", 8, true);
define("ewAllowreport", 8, true);
define("ewAllowsearch", 8, true);																														
define("ewAllowadmin", 16, true);						
?>
<?php
if (@$_SESSION["php_project_esf_status"] <> "login") {
	header("Location:  login.php");
	exit();
}
$x_solicitud_id = @$_GET["key"];
if (($x_solicitud_id == "") || ((is_null($x_solicitud_id)))) {
	$x_solicitud_id = @$_POST["x_solicitud_id"];
	if (($x_solicitud_id == "") || ((is_null($x_solicitud_id)))) {	
		echo "Solicitud no localizada.";
		exit();
	}
}

?>
<?php

// Initialize common variables
$x_solicitud_inciso_id = Null; 
$ox_solicitud_inciso_id = Null;
$x_fecha_registro = Null; 
$ox_fecha_registro = Null;
$x_cliente_id = Null; 
$ox_cliente_id = Null;
$x_ingreso_id = Null; 
$ox_ingreso_id = Null;
$x_garantia_id = Null; 
$ox_garantia_id = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
$arRecKey = Null;

// Load Key Parameters
$sKey = "";
$bSingleDelete = true;
$x_solicitud_inciso_id = @$_GET["solicitud_inciso_id"];
if (!empty($x_solicitud_inciso_id)) {
	if ($sKey <> "") { $sKey .= ","; }
	$sKey .= $x_solicitud_inciso_id;
}else{
	$bSingleDelete = false;
}
if (!$bSingleDelete) {
	$sKey = @$_POST["key_d"];
}
if (!is_array($sKey)) {
	if (strlen($sKey) > 0) {	
		$arRecKey = split(",", $sKey);
	}
}else {
	$sKey = implode(",", $sKey);
	$arRecKey = split(",", $sKey);
}
if (count($arRecKey) <= 0) {
	ob_end_clean();
	header("Location: php_solicitud_incisolist.php");
	exit();
}
$sKey = implode(",", $arRecKey);
$i = 0;
$sDbWhere = "";
while ($i < count($arRecKey)){
	$sDbWhere .= "(";

	// Remove spaces
	$sRecKey = trim($arRecKey[$i+0]);
	$sRecKey = (!get_magic_quotes_gpc()) ? addslashes($sRecKey) : $sRecKey ;

	// Build the SQL
	$sDbWhere .= "`solicitud_inciso_id`=" . $sRecKey . " AND ";
	if (substr($sDbWhere, -5) == " AND ") { $sDbWhere = substr($sDbWhere, 0, strlen($sDbWhere)-5) . ") OR "; }
	$i += 1;
}
if (substr($sDbWhere, -4) == " OR ") { $sDbWhere = substr($sDbWhere, 0 , strlen($sDbWhere)-4); }

// Get action
$sAction = @$_POST["a_delete"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";		// Display with input box
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Display
		if (LoadRecordCount($sDbWhere,$conn) <= 0) {
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_solicitud_incisolist.php?key=$x_solicitud_id");
			exit();
		}
		break;
	case "D": // Delete
		if (DeleteData($sDbWhere,$conn)) {
			$_SESSION["ewmsg"] = "El Asociado ha sido eliminado.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_solicitud_incisolist.php?key=$x_solicitud_id");
			exit();
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
<style type="text/css">
<!--
.style1 {
	color: #CC0000;
	font-weight: bold;
}
-->
</style>
</head>
<body bgcolor="#FFFFFF">
<p><span class="phpmaker"><a href="php_solicitud_incisolist.php?key=<?php echo $x_solicitud_id; ?>">Regresar a la Lista de Asociados </a></span></p>
<form action="php_solicitud_incisodelete.php" method="post">
<p>
<input type="hidden" name="a_delete" value="D">
<?php $sKey = (get_magic_quotes_gpc()) ? stripslashes($sKey) : $sKey; ?>
<input type="hidden" name="key_d" value="<?php echo htmlspecialchars($sKey); ?>">
<input type="hidden" name="x_solicitud_id" value="<?php echo $x_solicitud_id; ?>">
<table class="ewTable_small">
	<tr class="ewTableHeader">
		<td width="96" valign="top"><span>No</span></td>
		<td width="112" valign="top"><span>Fecha de registro</span></td>
		<td width="576" valign="top"><span>Socio</span></td>
	</tr>
<?php
$nRecCount = 0;
$i = 0;
while ($i < count($arRecKey)) {
	$nRecCount++;

	// Set row color
	$sItemRowClass = " class=\"ewTableRow\"";

	// Display alternate color for rows
	if ($nRecCount % 2 <> 0) {
		$sItemRowClass = " class=\"ewTableAltRow\"";
	}
	$sRecKey = trim($arRecKey[$i+0]);
	$sRecKey = (get_magic_quotes_gpc()) ? stripslashes($sRecKey) : $sRecKey;
	$x_solicitud_inciso_id = $sRecKey;
	if (LoadData($conn)) {
?>
	<tr<?php echo $sItemRowClass;?>>
		<td><span>
<?php echo $x_solicitud_inciso_id; ?>
</span></td>
		<td><span>
<?php echo FormatDateTime($x_fecha_registro,7); ?>
</span></td>
		<td><span>
<?php
if ((!is_null($x_cliente_id)) && ($x_cliente_id <> "")) {
	$sSqlWrk = "SELECT `nombre_completo` FROM `cliente`";
	$sTmp = $x_cliente_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `cliente_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["nombre_completo"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_cliente_id = $x_cliente_id; // Backup Original Value
$x_cliente_id = $sTmp;
?>
<?php echo $x_cliente_id; ?>
<?php $x_cliente_id = $ox_cliente_id; // Restore Original Value ?>
</span></td>
	</tr>
<?php
	}
	$i += 1;
}
?>
</table>
<p class="style1">IMPORTANTE: ¡ TODOS LOS DATOS DEL ASOCIADO SERAN ELIMINADOS Y NO PODRAN SER RECUPERADOS!
<p>
  <input type="submit" name="Action" value="CONFIRME">
</form>
<?php include ("footer.php") ?>
<?php

//-------------------------------------------------------------------------------
// Function LoadData
// - Load Data based on Key Value sKey
// - Variables setup: field variables

function LoadData($conn)
{
	global $x_solicitud_inciso_id;
	$sSql = "SELECT * FROM `solicitud_inciso`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_solicitud_inciso_id) : $x_solicitud_inciso_id;
	$sWhere .= "(`solicitud_inciso_id` = " . addslashes($sTmp) . ")";
	$sSql .= " WHERE " . $sWhere;
	if ($sGroupBy <> "") {
		$sSql .= " GROUP BY " . $sGroupBy;
	}
	if ($sHaving <> "") {
		$sSql .= " HAVING " . $sHaving;
	}
	if ($sOrderBy <> "") {
		$sSql .= " ORDER BY " . $sOrderBy;
	}
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	if (phpmkr_num_rows($rs) == 0) {
		$bLoadData = false;
	}else{
		$bLoadData = true;
		$row = phpmkr_fetch_array($rs);

		// Get the field contents
		$GLOBALS["x_solicitud_inciso_id"] = $row["solicitud_inciso_id"];
		$GLOBALS["x_solicitud_id"] = $row["solicitud_id"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
		$GLOBALS["x_cliente_id"] = $row["cliente_id"];
		$GLOBALS["x_ingreso_id"] = $row["ingreso_id"];
		$GLOBALS["x_garantia_id"] = $row["garantia_id"];
	}
	phpmkr_free_result($rs);
	return $bLoadData;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function LoadRecordCount
// - Load Record Count based on input sql criteria sqlKey

function LoadRecordCount($sqlKey,$conn)
{
	global $x_solicitud_inciso_id;
	$sSql = "SELECT * FROM `solicitud_inciso`";
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
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
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
	global $x_solicitud_inciso_id;


	$sSqlWrk = "SELECT * FROM solicitud_inciso ";
	$sSqlWrk .= " WHERE " . $sqlKey;	
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_solicitud_inciso_id = $datawrk["solicitud_inciso_id"];	
		$x_cliente_id = $datawrk["cliente_id"];
		$x_ingreso_id = $datawrk["ingreso_id"];		
		$x_garantia_id = $datawrk["garantia_id"];				
		
		$sSql = "Delete FROM direccion where cliente_id = ".$x_cliente_id;
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);

		$sSql = "Delete FROM cliente where cliente_id = ".$x_cliente_id;
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);

		$sSql = "Delete FROM ingreso where ingreso_id = ".$x_ingreso_id;
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);

		$sSql = "Delete FROM garantia where garantia_id = ".$x_garantia_id;
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);

		$sSqlWrk2 = "SELECT referencia_id FROM inciso_referencia ";
		$sSqlWrk2 .= " WHERE solicitud_inciso_id = " . $x_solicitud_inciso_id;	
		$rswrk2 = phpmkr_query($sSqlWrk2,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		while ($datawrk2 = phpmkr_fetch_array($rswrk2)) {
			$x_referencia_id = $datawrk2["referencia_id"];						
			$sSql = "Delete FROM referencia where referencia_id = ".$x_referencia_id;
			phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		}
		@phpmkr_free_result($rswrk2);
		$sSql = "Delete FROM inciso_referencia where solicitud_inciso_id = " . $x_solicitud_inciso_id;	
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		
	}
	@phpmkr_free_result($rswrk);

	
	$sSql = "Delete FROM `solicitud_inciso`";
	$sSql .= " WHERE " . $sqlKey;
	phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	return true;
}
?>
