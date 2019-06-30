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
$x_fondeo_credito_id = Null; 
$ox_fondeo_credito_id = Null;
$x_fondeo_credito_tipo_id = Null; 
$ox_fondeo_credito_tipo_id = Null;
$x_solicitud_id = Null; 
$ox_solicitud_id = Null;
$x_fondeo_credito_status_id = Null; 
$ox_fondeo_credito_status_id = Null;
$x_fecha_otrogamiento = Null; 
$ox_fecha_otrogamiento = Null;
$x_importe = Null; 
$ox_importe = Null;
$x_tasa = Null; 
$ox_tasa = Null;
$x_plazo = Null; 
$ox_plazo = Null;
$x_fecha_vencimiento = Null; 
$ox_fecha_vencimiento = Null;
$x_tasa_moratoria = Null; 
$ox_tasa_moratoria = Null;
$x_medio_pago_id = Null; 
$ox_medio_pago_id = Null;
$x_referencia_pago = Null; 
$ox_referencia_pago = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
$arRecKey = Null;

// Load Key Parameters
$sKey = "";
$bSingleDelete = true;
$x_fondeo_credito_id = @$_GET["credito_id"];
if (!empty($x_fondeo_credito_id)) {
	if ($sKey <> "") { $sKey .= ","; }
	$sKey .= $x_fondeo_credito_id;
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
	header("Location: php_fondeo_creditolist.php");
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
	$sDbWhere .= "`credito_id`=" . $sRecKey . " AND ";
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
			header("Location: php_fondeo_creditolist.php");
			exit();
		}
		break;
	case "D": // Delete
		if (DeleteData($sDbWhere,$conn)) {
			$_SESSION["ewmsg"] = "Delete Successful";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_fondeo_creditolist.php");
			exit();
		}
		break;
}
?>
<?php include ("header.php") ?>
<p><span class="phpmaker">fondos - CREDITOS<br><br>
  <a href="php_fondeo_creditolist.php">Regresar al listado</a></span></p>
<form action="php_fondeo_creditodelete.php" method="post">
<p>
<input type="hidden" name="a_delete" value="D">
<?php $sKey = (get_magic_quotes_gpc()) ? stripslashes($sKey) : $sKey; ?>
<input type="hidden" name="key_d" value="<?php echo htmlspecialchars($sKey); ?>">
<table class="ewTable">
	<tr class="ewTableHeader">
		<td valign="top"><span>No</span></td>
		<td valign="top"><span>Tipo</span></td>
		<td valign="top"><span>Solicitud</span></td>
		<td valign="top"><span>Status</span></td>
		<td valign="top"><span>Fecha de otrogamiento</span></td>
		<td valign="top"><span>Importe</span></td>
		<td valign="top"><span>Tasa</span></td>
		<td valign="top"><span>Plazo (meses)</span></td>
		<td valign="top"><span>Fecha de vencimiento</span></td>
		<td valign="top"><span>Tasa moratoria</span></td>
		<td valign="top"><span>Medio de pago</span></td>
		<td valign="top"><span>Referencia de pago</span></td>
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
	$x_fondeo_credito_id = $sRecKey;
	if (LoadData($conn)) {
?>
	<tr<?php echo $sItemRowClass;?>>
		<td><span>
<?php echo $x_fondeo_credito_id; ?>
</span></td>
		<td><span>
<?php
if ((!is_null($x_fondeo_credito_tipo_id)) && ($x_fondeo_credito_tipo_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `credito_tipo`";
	$sTmp = $x_fondeo_credito_tipo_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `credito_tipo_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_fondeo_credito_tipo_id = $x_fondeo_credito_tipo_id; // Backup Original Value
$x_fondeo_credito_tipo_id = $sTmp;
?>
<?php echo $x_fondeo_credito_tipo_id; ?>
<?php $x_fondeo_credito_tipo_id = $ox_fondeo_credito_tipo_id; // Restore Original Value ?>
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
<?php
if ((!is_null($x_fondeo_credito_status_id)) && ($x_fondeo_credito_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `credito_status`";
	$sTmp = $x_fondeo_credito_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `credito_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_fondeo_credito_status_id = $x_fondeo_credito_status_id; // Backup Original Value
$x_fondeo_credito_status_id = $sTmp;
?>
<?php echo $x_fondeo_credito_status_id; ?>
<?php $x_fondeo_credito_status_id = $ox_fondeo_credito_status_id; // Restore Original Value ?>
</span></td>
		<td><span>
<?php echo FormatDateTime($x_fecha_otrogamiento,7); ?>
</span></td>
		<td><span>
<?php echo (is_numeric($x_importe)) ? FormatNumber($x_importe,0,0,0,-2) : $x_importe; ?>
</span></td>
		<td><span>
<?php echo (is_numeric($x_tasa)) ? FormatPercent($x_tasa,0,-2,-2,-2) : $x_tasa; ?>
</span></td>
		<td><span>
<?php echo $x_plazo; ?>
</span></td>
		<td><span>
<?php echo FormatDateTime($x_fecha_vencimiento,7); ?>
</span></td>
		<td><span>
<?php echo (is_numeric($x_tasa_moratoria)) ? FormatPercent($x_tasa_moratoria,0,-2,-2,-2) : $x_tasa_moratoria; ?>
</span></td>
		<td><span>
<?php
if ((!is_null($x_medio_pago_id)) && ($x_medio_pago_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `medio_pago`";
	$sTmp = $x_medio_pago_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `medio_pago_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_medio_pago_id = $x_medio_pago_id; // Backup Original Value
$x_medio_pago_id = $sTmp;
?>
<?php echo $x_medio_pago_id; ?>
<?php $x_medio_pago_id = $ox_medio_pago_id; // Restore Original Value ?>
</span></td>
		<td><span>
<?php echo $x_referencia_pago; ?>
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
	global $x_fondeo_credito_id;
	$sSql = "SELECT * FROM `credito`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_fondeo_credito_id) : $x_fondeo_credito_id;
	$sWhere .= "(`credito_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_fondeo_credito_id"] = $row["credito_id"];
		$GLOBALS["x_fondeo_credito_tipo_id"] = $row["credito_tipo_id"];
		$GLOBALS["x_solicitud_id"] = $row["solicitud_id"];
		$GLOBALS["x_fondeo_credito_status_id"] = $row["credito_status_id"];
		$GLOBALS["x_fecha_otrogamiento"] = $row["fecha_otrogamiento"];
		$GLOBALS["x_importe"] = $row["importe"];
		$GLOBALS["x_tasa"] = $row["tasa"];
		$GLOBALS["x_plazo"] = $row["plazo"];
		$GLOBALS["x_fecha_vencimiento"] = $row["fecha_vencimiento"];
		$GLOBALS["x_tasa_moratoria"] = $row["tasa_moratoria"];
		$GLOBALS["x_medio_pago_id"] = $row["medio_pago_id"];
		$GLOBALS["x_referencia_pago"] = $row["referencia_pago"];
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
	global $x_fondeo_credito_id;
	$sSql = "SELECT * FROM `credito`";
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
	global $x_fondeo_credito_id;
	$sSql = "Delete FROM `credito`";
	$sSql .= " WHERE " . $sqlKey;
	phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	return true;
}
?>
