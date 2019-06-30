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
$x_cuenta_cont_id = Null; 
$ox_cuenta_cont_id = Null;
$x_cuenta_cont_tipo_id = Null; 
$ox_cuenta_cont_tipo_id = Null;
$x_cuenta = Null; 
$ox_cuenta = Null;
$x_sub_cuenta = Null; 
$ox_sub_cuenta = Null;
$x_ssub_cuenta = Null; 
$ox_ssub_cuenta = Null;
$x_sssub_cuenta = Null; 
$ox_sssub_cuenta = Null;
$x_descricpion = Null; 
$ox_descricpion = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
$arRecKey = Null;

// Load Key Parameters
$sKey = "";
$bSingleDelete = true;
$x_cuenta_cont_id = @$_GET["cuenta_cont_id"];
if (!empty($x_cuenta_cont_id)) {
	if ($sKey <> "") { $sKey .= ","; }
	$sKey .= $x_cuenta_cont_id;
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
	header("Location: php_cuenta_contlist.php");
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
	$sDbWhere .= "`cuenta_cont_id`=" . $sRecKey . " AND ";
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
			header("Location: php_cuenta_contlist.php");
			exit();
		}
		break;
	case "D": // Delete
		if (DeleteData($sDbWhere,$conn)) {
			$_SESSION["ewmsg"] = "Delete Successful";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_cuenta_contlist.php");
			exit();
		}
		break;
}
?>
<?php include ("header.php") ?>
<p><span class="phpmaker">Delete from TABLE: Contabilidad Catalogo de Cuentas<br><br><a href="php_cuenta_contlist.php">Back to List</a></span></p>
<form action="php_cuenta_contdelete.php" method="post">
<p>
<input type="hidden" name="a_delete" value="D">
<?php $sKey = (get_magic_quotes_gpc()) ? stripslashes($sKey) : $sKey; ?>
<input type="hidden" name="key_d" value="<?php echo htmlspecialchars($sKey); ?>">
<table class="ewTable">
	<tr class="ewTableHeader">
		<td valign="top"><span>No</span></td>
		<td valign="top"><span>Tipo</span></td>
		<td valign="top"><span>Cuenta</span></td>
		<td valign="top"><span>S-cuenta</span></td>
		<td valign="top"><span>SS-cuenta</span></td>
		<td valign="top"><span>SSS-cuenta</span></td>
		<td valign="top"><span>Descricpion</span></td>
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
	$x_cuenta_cont_id = $sRecKey;
	if (LoadData($conn)) {
?>
	<tr<?php echo $sItemRowClass;?>>
		<td><span>
<?php echo $x_cuenta_cont_id; ?>
</span></td>
		<td><span>
<?php
if ((!is_null($x_cuenta_cont_tipo_id)) && ($x_cuenta_cont_tipo_id <> "")) {
	$sSqlWrk = "SELECT `desc_corta` FROM `cuenta_cont_tipo`";
	$sTmp = $x_cuenta_cont_tipo_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `cuenta_cont_tipo_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["desc_corta"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_cuenta_cont_tipo_id = $x_cuenta_cont_tipo_id; // Backup Original Value
$x_cuenta_cont_tipo_id = $sTmp;
?>
<?php echo $x_cuenta_cont_tipo_id; ?>
<?php $x_cuenta_cont_tipo_id = $ox_cuenta_cont_tipo_id; // Restore Original Value ?>
</span></td>
		<td><span>
<?php echo $x_cuenta; ?>
</span></td>
		<td><span>
<?php echo $x_sub_cuenta; ?>
</span></td>
		<td><span>
<?php echo $x_ssub_cuenta; ?>
</span></td>
		<td><span>
<?php echo $x_sssub_cuenta; ?>
</span></td>
		<td><span>
<?php echo $x_descricpion; ?>
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
	global $x_cuenta_cont_id;
	$sSql = "SELECT * FROM `cuenta_cont`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_cuenta_cont_id) : $x_cuenta_cont_id;
	$sWhere .= "(`cuenta_cont_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_cuenta_cont_id"] = $row["cuenta_cont_id"];
		$GLOBALS["x_cuenta_cont_tipo_id"] = $row["cuenta_cont_tipo_id"];
		$GLOBALS["x_cuenta"] = $row["cuenta"];
		$GLOBALS["x_sub_cuenta"] = $row["sub_cuenta"];
		$GLOBALS["x_ssub_cuenta"] = $row["ssub_cuenta"];
		$GLOBALS["x_sssub_cuenta"] = $row["sssub_cuenta"];
		$GLOBALS["x_descricpion"] = $row["descricpion"];
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
	global $x_cuenta_cont_id;
	$sSql = "SELECT * FROM `cuenta_cont`";
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
	global $x_cuenta_cont_id;
	$sSql = "Delete FROM `cuenta_cont`";
	$sSql .= " WHERE " . $sqlKey;
	phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	return true;
}
?>
