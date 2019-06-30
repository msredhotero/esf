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
$x_aval_id = Null; 
$ox_aval_id = Null;
$x_cliente_id = Null; 
$ox_cliente_id = Null;
$x_nombre_completo = Null; 
$ox_nombre_completo = Null;
$x_parentesco_tipo_id = Null; 
$ox_parentesco_tipo_id = Null;
$x_telefono = Null; 
$ox_telefono = Null;
$x_ingresos_mensuales = Null; 
$ox_ingresos_mensuales = Null;
$x_ocupacion = Null; 
$ox_ocupacion = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
$arRecKey = Null;

// Load Key Parameters
$sKey = "";
$bSingleDelete = true;
$x_aval_id = @$_GET["aval_id"];
if (!empty($x_aval_id)) {
	if ($sKey <> "") { $sKey .= ","; }
	$sKey .= $x_aval_id;
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
	header("Location: php_avallist.php");
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
	$sDbWhere .= "`aval_id`=" . $sRecKey . " AND ";
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
			header("Location: php_avallist.php");
			exit();
		}
		break;
	case "D": // Delete
		if (DeleteData($sDbWhere,$conn)) {
			$_SESSION["ewmsg"] = "Delete Successful";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_avallist.php");
			exit();
		}
		break;
}
?>
<?php include ("header.php") ?>
<p><span class="phpmaker">Delete from TABLE: AVALES<br><br><a href="php_avallist.php">Back to List</a></span></p>
<form action="php_avaldelete.php" method="post">
<p>
<input type="hidden" name="a_delete" value="D">
<?php $sKey = (get_magic_quotes_gpc()) ? stripslashes($sKey) : $sKey; ?>
<input type="hidden" name="key_d" value="<?php echo htmlspecialchars($sKey); ?>">
<table class="ewTable">
	<tr class="ewTableHeader">
		<td valign="top"><span>No</span></td>
		<td valign="top"><span>Cliente</span></td>
		<td valign="top"><span>Nombre Completo</span></td>
		<td valign="top"><span>Parentesco</span></td>
		<td valign="top"><span>Tel�fono</span></td>
		<td valign="top"><span>Ingresos mensuales</span></td>
		<td valign="top"><span>Ocupaci�n</span></td>
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
	$x_aval_id = $sRecKey;
	if (LoadData($conn)) {
?>
	<tr<?php echo $sItemRowClass;?>>
		<td><span>
<?php echo $x_aval_id; ?>
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
<?php echo $x_nombre_completo; ?>
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
<?php echo $x_telefono; ?>
</span></td>
		<td><span>
<?php echo (is_numeric($x_ingresos_mensuales)) ? FormatNumber($x_ingresos_mensuales,0,0,0,-2) : $x_ingresos_mensuales; ?>
</span></td>
		<td><span>
<?php echo $x_ocupacion; ?>
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
	global $x_aval_id;
	$sSql = "SELECT * FROM `aval`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_aval_id) : $x_aval_id;
	$sWhere .= "(`aval_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_aval_id"] = $row["aval_id"];
		$GLOBALS["x_cliente_id"] = $row["cliente_id"];
		$GLOBALS["x_nombre_completo"] = $row["nombre_completo"];
		$GLOBALS["x_parentesco_tipo_id"] = $row["parentesco_tipo_id"];
		$GLOBALS["x_telefono"] = $row["telefono"];
		$GLOBALS["x_ingresos_mensuales"] = $row["ingresos_mensuales"];
		$GLOBALS["x_ocupacion"] = $row["ocupacion"];
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
	global $x_aval_id;
	$sSql = "SELECT * FROM `aval`";
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
	global $x_aval_id;
	$sSql = "Delete FROM `aval`";
	$sSql .= " WHERE " . $sqlKey;
	phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	return true;
}
?>
