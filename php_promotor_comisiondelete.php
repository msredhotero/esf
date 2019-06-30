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
?>
<?php

// Initialize common variables
$x_promotor_comision_id = Null; 
$ox_promotor_comision_id = Null;
$x_promotor_id = Null; 
$ox_promotor_id = Null;
$x_solicitud_id = Null; 
$ox_solicitud_id = Null;
$x_fecha_registro = Null; 
$ox_fecha_registro = Null;
$x_comision = Null; 
$ox_comision = Null;
$x_comision_importe = Null; 
$ox_comision_importe = Null;
$x_referencia = Null; 
$ox_referencia = Null;
$x_promotor_comision_status_id = Null; 
$ox_promotor_comision_status_id = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
$arRecKey = Null;

// Load Key Parameters
$sKey = "";
$bSingleDelete = true;
$x_promotor_comision_id = @$_GET["promotor_comision_id"];
if (!empty($x_promotor_comision_id)) {
	if ($sKey <> "") { $sKey .= ","; }
	$sKey .= $x_promotor_comision_id;
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
	header("Location: php_promotor_comisionlist.php");
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
	$sDbWhere .= "`promotor_comision_id`=" . $sRecKey . " AND ";
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
			header("Location: php_promotor_comisionlist.php");
			exit();
		}
		break;
	case "D": // Delete
		if (DeleteData($sDbWhere,$conn)) {
			$_SESSION["ewmsg"] = "Delete Successful";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_promotor_comisionlist.php");
			exit();
		}
		break;
}
?>
<?php include ("header.php") ?>
<p><span class="phpmaker">Delete from TABLE: Comisiones<br><br><a href="php_promotor_comisionlist.php">Back to List</a></span></p>
<form action="php_promotor_comisiondelete.php" method="post">
<p>
<input type="hidden" name="a_delete" value="D">
<?php $sKey = (get_magic_quotes_gpc()) ? stripslashes($sKey) : $sKey; ?>
<input type="hidden" name="key_d" value="<?php echo htmlspecialchars($sKey); ?>">
<table class="ewTable">
	<tr class="ewTableHeader">
		<td valign="top"><span>No</span></td>
		<td valign="top"><span>Promotor</span></td>
		<td valign="top"><span>Folio</span></td>
		<td valign="top"><span>Fecha de registro</span></td>
		<td valign="top"><span>Comision</span></td>
		<td valign="top"><span>Importe</span></td>
		<td valign="top"><span>Referencia</span></td>
		<td valign="top"><span>Status</span></td>
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
	$x_promotor_comision_id = $sRecKey;
	if (LoadData($conn)) {
?>
	<tr<?php echo $sItemRowClass;?>>
		<td><span>
<?php echo $x_promotor_comision_id; ?>
</span></td>
		<td><span>
<?php
if ((!is_null($x_promotor_id)) && ($x_promotor_id <> "")) {
	$sSqlWrk = "SELECT `nombre_completo` FROM `promotor`";
	$sTmp = $x_promotor_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `promotor_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["nombre_completo"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_promotor_id = $x_promotor_id; // Backup Original Value
$x_promotor_id = $sTmp;
?>
<?php echo $x_promotor_id; ?>
<?php $x_promotor_id = $ox_promotor_id; // Restore Original Value ?>
</span></td>
		<td><span>
<?php
if ((!is_null($x_solicitud_id)) && ($x_solicitud_id <> "")) {
	$sSqlWrk = "SELECT `folio` FROM `solicitud`";
	$sTmp = $x_solicitud_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `solicitud_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["folio"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_solicitud_id = $x_solicitud_id; // Backup Original Value
$x_solicitud_id = $sTmp;
?>
<?php echo $x_solicitud_id; ?>
<?php $x_solicitud_id = $ox_solicitud_id; // Restore Original Value ?>
</span></td>
		<td><span>
<?php echo FormatDateTime($x_fecha_registro,7); ?>
</span></td>
		<td><span>
<?php echo (is_numeric($x_comision)) ? FormatNumber($x_comision,2,0,0,-2) : $x_comision; ?>
</span></td>
		<td><span>
<?php echo (is_numeric($x_comision_importe)) ? FormatNumber($x_comision_importe,2,0,-2,-2) : $x_comision_importe; ?>
</span></td>
		<td><span>
<?php echo $x_referencia; ?>
</span></td>
		<td><span>
<?php
if ((!is_null($x_promotor_comision_status_id)) && ($x_promotor_comision_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `promotor_comision_status`";
	$sTmp = $x_promotor_comision_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `promotor_comision_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_promotor_comision_status_id = $x_promotor_comision_status_id; // Backup Original Value
$x_promotor_comision_status_id = $sTmp;
?>
<?php echo $x_promotor_comision_status_id; ?>
<?php $x_promotor_comision_status_id = $ox_promotor_comision_status_id; // Restore Original Value ?>
</span></td>
	</tr>
<?php
	}
	$i += 1;
}
?>
</table>
<p>
<input type="submit" name="Action" value="CONFIRM DELETE">
</form>
<?php include ("footer.php") ?>
<?php

//-------------------------------------------------------------------------------
// Function LoadData
// - Load Data based on Key Value sKey
// - Variables setup: field variables

function LoadData($conn)
{
	global $x_promotor_comision_id;
	$sSql = "SELECT * FROM `promotor_comision`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_promotor_comision_id) : $x_promotor_comision_id;
	$sWhere .= "(`promotor_comision_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_promotor_comision_id"] = $row["promotor_comision_id"];
		$GLOBALS["x_promotor_id"] = $row["promotor_id"];
		$GLOBALS["x_solicitud_id"] = $row["solicitud_id"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
		$GLOBALS["x_comision"] = $row["comision"];
		$GLOBALS["x_comision_importe"] = $row["comision_importe"];
		$GLOBALS["x_referencia"] = $row["referencia"];
		$GLOBALS["x_promotor_comision_status_id"] = $row["promotor_comision_status_id"];
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
	global $x_promotor_comision_id;
	$sSql = "SELECT * FROM `promotor_comision`";
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
	global $x_promotor_comision_id;
	$sSql = "Delete FROM `promotor_comision`";
	$sSql .= " WHERE " . $sqlKey;
	phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	return true;
}
?>
