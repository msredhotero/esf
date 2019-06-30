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
$x_responsable_sucursal_status_id = Null;
$x_descripcion = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
$sKey = @$_GET["key"];
if (($sKey == "") || (is_null($sKey))) { $sKey = @$_POST["key"]; }
if (!empty($sKey)) $sKey = (get_magic_quotes_gpc()) ? stripslashes($sKey) : $sKey;

// Get action
$sAction = @$_POST["a_edit"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";	// Display with input box
} else {

	// Get fields from form
	$x_responsable_sucursal_status_id = @$_POST["x_responsable_sucursal_status_id"];
	$x_descripcion = @$_POST["x_descripcion"];
}
if (($sKey == "") || ((is_null($sKey)))) {
	ob_end_clean();
	header("Location: php_responsable_sucursal_statuslist.php");
}
$conn = phpmkr_db_connect(HOST,USER,PASS,DB);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($sKey,$conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No Record Found for Key = " . $sKey;
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_responsable_sucursal_statuslist.php");
		}
		break;
	case "U": // Update
		if (EditData($sKey,$conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "Update Record Successful for Key = " . $sKey;
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_responsable_sucursal_statuslist.php");
		}
		break;
}
?>
<?php include ("header.php") ?>
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
<p><span class="phpmaker">Edit TABLE: responsable sucursal status<br><br><a href="php_responsable_sucursal_statuslist.php">Back to List</a></span></p>
<form name="responsable_sucursal_statusedit" id="responsable_sucursal_statusedit" action="php_responsable_sucursal_statusedit.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<input type="hidden" name="key" value="<?php echo htmlspecialchars($sKey); ?>">
<table class="ewTable">
	<tr>
		<td class="ewTableHeader"><span>responsable sucursal status id</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_responsable_sucursal_status_id; ?><input type="hidden" name="x_responsable_sucursal_status_id" value="<?php echo $x_responsable_sucursal_status_id; ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>descripcion</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_descripcion" id="x_descripcion" size="30" maxlength="50" value="<?php echo htmlspecialchars(@$x_descripcion) ?>">
</span></td>
	</tr>
</table>
<p>
<input type="submit" name="Action" value="EDIT">
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
	$sSql = "SELECT * FROM `responsable_sucursal_status`";
	$sSql .= " WHERE `responsable_sucursal_status_id` = " . $sKeyWrk;
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
		$GLOBALS["x_responsable_sucursal_status_id"] = $row["responsable_sucursal_status_id"];
		$GLOBALS["x_descripcion"] = $row["descripcion"];
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
	$sSql = "SELECT * FROM `responsable_sucursal_status`";
	$sSql .= " WHERE `responsable_sucursal_status_id` = " . $sKeyWrk;
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
		$EditData = false; // Update Failed
	}else{
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_descripcion"]) : $GLOBALS["x_descripcion"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`descripcion`"] = $theValue;

		// update
		$sSql = "UPDATE `responsable_sucursal_status` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE `responsable_sucursal_status_id` =". $sKeyWrk;
		phpmkr_query($sSql,$conn);
		$EditData = true; // Update Successful
	}
	return $EditData;
}
?>
