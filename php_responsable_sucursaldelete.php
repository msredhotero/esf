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

// Initialize common variables
$x_responsable_sucursal_id = Null;
$x_sucursal_id = Null;
$x_usuario_id = Null;
$x_responsable_sucursal_status_id = Null;
$x_nombre_completo = Null;
$x_email = Null;
$x_telefono_sucursal = Null;
$x_telefono_movil = Null;
$x_telefono_casa = Null;
$x_new_field0 = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Load Key Parameters
$sKey = @$_GET["key"];
if (($sKey == "") || ((is_null($sKey)))) {
	$sKey = @$_POST["key_d"];
}
$sDbWhere = "";
$arRecKey = split(",",$sKey);

// Single delete record
if (($sKey == "") || ((is_null($sKey)))) {
	ob_end_clean();
	header("Location: php_responsable_sucursallist.php");
}
	$sKey = (get_magic_quotes_gpc()) ? $sKey : addslashes($sKey);
$sDbWhere .= "`responsable_sucursal_id`=" . trim($sKey) . "";

// Get action
$sAction = @$_POST["a_delete"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";	// Display with input box
}
$conn = phpmkr_db_connect(HOST, USER, PASS,DB);
switch ($sAction)
{
	case "I": // Display
		if (LoadRecordCount($sDbWhere,$conn) <= 0) {
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_responsable_sucursallist.php");
		}
		break;
	case "D": // Delete
		if (DeleteData($sDbWhere,$conn)) {
			$_SESSION["ewmsg"] = "Delete Successful For Key = " . stripslashes($sKey);
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_responsable_sucursallist.php");
		}
		break;
}
?>
<?php include ("header.php") ?>
<p><span class="phpmaker">Delete from TABLE: responsable sucursal<br><br><a href="php_responsable_sucursallist.php">Back to List</a></span></p>
<form action="php_responsable_sucursaldelete.php" method="post">
<p>
<input type="hidden" name="a_delete" value="D">
<?php $sKey = (get_magic_quotes_gpc()) ? stripslashes($sKey) : $sKey; ?>
<input type="hidden" name="key_d" value="<?php echo  htmlspecialchars($sKey); ?>">
<table class="ewTable">
	<tr class="ewTableHeader">
		<td valign="top"><span>responsable sucursal id</span></td>
		<td valign="top"><span>sucursal id</span></td>
		<td valign="top"><span>usuario id</span></td>
		<td valign="top"><span>responsable sucursal status id</span></td>
		<td valign="top"><span>email</span></td>
		<td valign="top"><span>telefono sucursal</span></td>
		<td valign="top"><span>telefono movil</span></td>
		<td valign="top"><span>telefono casa</span></td>
		<td valign="top"><span>new field 0</span></td>
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
<?php echo $x_responsable_sucursal_id; ?>
</span></td>
		<td><span>
<?php echo $x_sucursal_id; ?>
</span></td>
		<td><span>
<?php echo $x_usuario_id; ?>
</span></td>
		<td><span>
<?php
if ((!is_null($x_responsable_sucursal_status_id)) && ($x_responsable_sucursal_status_id <> "")) {
	$sSqlWrk = "SELECT * FROM `responsable_sucursal_status`";
	$sTmp = $x_responsable_sucursal_status_id;
	$sTmp = (!get_magic_quotes_gpc()) ? addslashes($sTmp) : $sTmp;
	$sSqlWrk .= " WHERE `responsable_sucursal_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_responsable_sucursal_status_id = $x_responsable_sucursal_status_id; // Backup Original Value
$x_responsable_sucursal_status_id = $sTmp;
?>
<?php echo $x_responsable_sucursal_status_id; ?>
<?php $x_responsable_sucursal_status_id = $ox_responsable_sucursal_status_id; // Restore Original Value ?>
</span></td>
		<td><span>
<?php echo $x_email; ?>
</span></td>
		<td><span>
<?php echo $x_telefono_sucursal; ?>
</span></td>
		<td><span>
<?php echo $x_telefono_movil; ?>
</span></td>
		<td><span>
<?php echo $x_telefono_casa; ?>
</span></td>
		<td><span>
<?php echo $x_new_field0; ?>
</span></td>
	</tr>
<?php
	}
}
?>
</table>
<p>
<input type="submit" name="Action" value="CONFIRM DELETE">
</form>
<?php include ("footer.php") ?>
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
	$sSql = "SELECT * FROM `responsable_sucursal`";
	$sSql .= " WHERE `responsable_sucursal_id` = " . $sKeyWrk;
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
	$rs = phpmkr_query($sSql,$conn);
	if (phpmkr_num_rows($rs) == 0) {
		$LoadData = false;
	}else{
		$LoadData = true;
		$row = phpmkr_fetch_array($rs);

		// Get the field contents
		$GLOBALS["x_responsable_sucursal_id"] = $row["responsable_sucursal_id"];
		$GLOBALS["x_sucursal_id"] = $row["sucursal_id"];
		$GLOBALS["x_usuario_id"] = $row["usuario_id"];
		$GLOBALS["x_responsable_sucursal_status_id"] = $row["responsable_sucursal_status_id"];
		$GLOBALS["x_nombre_completo"] = $row["nombre_completo"];
		$GLOBALS["x_email"] = $row["email"];
		$GLOBALS["x_telefono_sucursal"] = $row["telefono_sucursal"];
		$GLOBALS["x_telefono_movil"] = $row["telefono_movil"];
		$GLOBALS["x_telefono_casa"] = $row["telefono_casa"];
		$GLOBALS["x_new_field0"] = $row["new_field0"];
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
	$sSql = "SELECT * FROM `responsable_sucursal`";
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
	$rs = phpmkr_query($sSql,$conn);
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
	$sSql = "Delete FROM `responsable_sucursal`";
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
	phpmkr_query($sSql,$conn);
	return true;
}
?>
