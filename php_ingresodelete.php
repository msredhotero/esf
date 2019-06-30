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
$x_ingreso_id = Null; 
$ox_ingreso_id = Null;
$x_cliente_id = Null; 
$ox_cliente_id = Null;
$x_ingresos_negocio = Null; 
$ox_ingresos_negocio = Null;
$x_ingresos_familiar_1 = Null; 
$ox_ingresos_familiar_1 = Null;
$x_parentesco_tipo_id = Null; 
$ox_parentesco_tipo_id = Null;
$x_ingresos_familiar_2 = Null; 
$ox_ingresos_familiar_2 = Null;
$x_parentesco_tipo_id2 = Null; 
$ox_parentesco_tipo_id2 = Null;
$x_otros_ingresos = Null; 
$ox_otros_ingresos = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
$arRecKey = Null;

// Load Key Parameters
$sKey = "";
$bSingleDelete = true;
$x_ingreso_id = @$_GET["ingreso_id"];
if (!empty($x_ingreso_id)) {
	if ($sKey <> "") { $sKey .= ","; }
	$sKey .= $x_ingreso_id;
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
	header("Location: php_ingresolist.php");
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
	$sDbWhere .= "`ingreso_id`=" . $sRecKey . " AND ";
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
			header("Location: php_ingresolist.php");
			exit();
		}
		break;
	case "D": // Delete
		if (DeleteData($sDbWhere,$conn)) {
			$_SESSION["ewmsg"] = "Delete Successful";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_ingresolist.php");
			exit();
		}
		break;
}
?>
<?php include ("header.php") ?>
<p><span class="phpmaker">Delete from TABLE: ingreso<br><br><a href="php_ingresolist.php">Back to List</a></span></p>
<form action="php_ingresodelete.php" method="post">
<p>
<input type="hidden" name="a_delete" value="D">
<?php $sKey = (get_magic_quotes_gpc()) ? stripslashes($sKey) : $sKey; ?>
<input type="hidden" name="key_d" value="<?php echo htmlspecialchars($sKey); ?>">
<table class="ewTable">
	<tr class="ewTableHeader">
		<td valign="top"><span>No</span></td>
		<td valign="top"><span>Cliente</span></td>
		<td valign="top"><span>Ingresos del negocio</span></td>
		<td valign="top"><span>Ingresos familiares 1</span></td>
		<td valign="top"><span>Parentesco</span></td>
		<td valign="top"><span>Ingresos familiares 2</span></td>
		<td valign="top"><span>Parentesco</span></td>
		<td valign="top"><span>Otros ingresos</span></td>
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
	$x_ingreso_id = $sRecKey;
	if (LoadData($conn)) {
?>
	<tr<?php echo $sItemRowClass;?>>
		<td><span>
<?php echo $x_ingreso_id; ?>
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
		<td><span>
<?php echo (is_numeric($x_ingresos_negocio)) ? FormatNumber($x_ingresos_negocio,0,0,0,-2) : $x_ingresos_negocio; ?>
</span></td>
		<td><span>
<?php echo (is_numeric($x_ingresos_familiar_1)) ? FormatNumber($x_ingresos_familiar_1,0,0,0,-2) : $x_ingresos_familiar_1; ?>
</span></td>
		<td><span>
<?php
if ((!is_null($x_parentesco_tipo_id)) && ($x_parentesco_tipo_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `parentesco_tipo`";
	$sTmp = $x_parentesco_tipo_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `parentesco_tipo_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_parentesco_tipo_id = $x_parentesco_tipo_id; // Backup Original Value
$x_parentesco_tipo_id = $sTmp;
?>
<?php echo $x_parentesco_tipo_id; ?>
<?php $x_parentesco_tipo_id = $ox_parentesco_tipo_id; // Restore Original Value ?>
</span></td>
		<td><span>
<?php echo (is_numeric($x_ingresos_familiar_2)) ? FormatNumber($x_ingresos_familiar_2,0,0,0,-2) : $x_ingresos_familiar_2; ?>
</span></td>
		<td><span>
<?php
if ((!is_null($x_parentesco_tipo_id2)) && ($x_parentesco_tipo_id2 <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `parentesco_tipo`";
	$sTmp = $x_parentesco_tipo_id2;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `parentesco_tipo_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_parentesco_tipo_id2 = $x_parentesco_tipo_id2; // Backup Original Value
$x_parentesco_tipo_id2 = $sTmp;
?>
<?php echo $x_parentesco_tipo_id2; ?>
<?php $x_parentesco_tipo_id2 = $ox_parentesco_tipo_id2; // Restore Original Value ?>
</span></td>
		<td><span>
<?php echo (is_numeric($x_otros_ingresos)) ? FormatNumber($x_otros_ingresos,0,0,0,-2) : $x_otros_ingresos; ?>
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
	global $x_ingreso_id;
	$sSql = "SELECT * FROM `ingreso`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_ingreso_id) : $x_ingreso_id;
	$sWhere .= "(`ingreso_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_ingreso_id"] = $row["ingreso_id"];
		$GLOBALS["x_cliente_id"] = $row["cliente_id"];
		$GLOBALS["x_ingresos_negocio"] = $row["ingresos_negocio"];
		$GLOBALS["x_ingresos_familiar_1"] = $row["ingresos_familiar_1"];
		$GLOBALS["x_parentesco_tipo_id"] = $row["parentesco_tipo_id"];
		$GLOBALS["x_ingresos_familiar_2"] = $row["ingresos_familiar_2"];
		$GLOBALS["x_parentesco_tipo_id2"] = $row["parentesco_tipo_id2"];
		$GLOBALS["x_otros_ingresos"] = $row["otros_ingresos"];
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
	global $x_ingreso_id;
	$sSql = "SELECT * FROM `ingreso`";
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
	global $x_ingreso_id;
	$sSql = "Delete FROM `ingreso`";
	$sSql .= " WHERE " . $sqlKey;
	phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	return true;
}
?>
