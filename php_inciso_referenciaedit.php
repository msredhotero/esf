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
$x_inciso_referencia = Null; 
$ox_inciso_referencia = Null;
$x_solicitud_inciso_id = Null; 
$ox_solicitud_inciso_id = Null;
$x_referencia_id = Null; 
$ox_referencia_id = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Load key from QueryString
$x_inciso_referencia = @$_GET["inciso_referencia"];

//if (!empty($x_inciso_referencia )) $x_inciso_referencia  = (get_magic_quotes_gpc()) ? stripslashes($x_inciso_referencia ) : $x_inciso_referencia ;
// Get action

$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I";	// Display with input box
} else {

	// Get fields from form
	$x_inciso_referencia = @$_POST["x_inciso_referencia"];
	$x_solicitud_inciso_id = @$_POST["x_solicitud_inciso_id"];
	$x_referencia_id = @$_POST["x_referencia_id"];
}

// Check if valid key
if (($x_inciso_referencia == "") || (is_null($x_inciso_referencia))) {
	ob_end_clean();
	header("Location: php_inciso_referencialist.php");
	exit();
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No records found";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_inciso_referencialist.php");
			exit();
		}
		break;
	case "U": // Update
		if (EditData($conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "Update Record Successful";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_inciso_referencialist.php");
			exit();
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
if (EW_this.x_solicitud_inciso_id && !EW_hasValue(EW_this.x_solicitud_inciso_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_solicitud_inciso_id, "SELECT", "El inciso es requerido."))
		return false;
}
if (EW_this.x_referencia_id && !EW_hasValue(EW_this.x_referencia_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_referencia_id, "SELECT", "La referencia es requerida."))
		return false;
}
return true;
}

//-->
</script>
<script type="text/javascript">
<!--
var EW_HTMLArea;

//-->
</script>
<p><span class="phpmaker">Edit TABLE: inciso referencia<br><br><a href="php_inciso_referencialist.php">Back to List</a></span></p>
<form name="inciso_referenciaedit" id="inciso_referenciaedit" action="php_inciso_referenciaedit.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<table class="ewTable">
	<tr>
		<td class="ewTableHeader"><span>No</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_inciso_referencia; ?><input type="hidden" id="x_inciso_referencia" name="x_inciso_referencia" value="<?php echo htmlspecialchars(@$x_inciso_referencia); ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Inciso</span></td>
		<td class="ewTableAltRow"><span>
<?php
$x_solicitud_inciso_idList = "<select name=\"x_solicitud_inciso_id\">";
$x_solicitud_inciso_idList .= "<option value=''>Please Select</option>";
$sSqlWrk = "SELECT `solicitud_inciso_id` FROM `solicitud_inciso`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_solicitud_inciso_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["solicitud_inciso_id"] == @$x_solicitud_inciso_id) {
			$x_solicitud_inciso_idList .= "' selected";
		}
		$x_solicitud_inciso_idList .= ">" . $datawrk["solicitud_inciso_id"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_solicitud_inciso_idList .= "</select>";
echo $x_solicitud_inciso_idList;
?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Referencia</span></td>
		<td class="ewTableAltRow"><span>
<?php
$x_referencia_idList = "<select name=\"x_referencia_id\">";
$x_referencia_idList .= "<option value=''>Please Select</option>";
$sSqlWrk = "SELECT `referencia_id` FROM `referencia`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_referencia_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["referencia_id"] == @$x_referencia_id) {
			$x_referencia_idList .= "' selected";
		}
		$x_referencia_idList .= ">" . $datawrk["referencia_id"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_referencia_idList .= "</select>";
echo $x_referencia_idList;
?>
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

function LoadData($conn)
{
	global $x_inciso_referencia;
	$sSql = "SELECT * FROM `inciso_referencia`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_inciso_referencia) : $x_inciso_referencia;
	$sWhere .= "(`inciso_referencia` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_inciso_referencia"] = $row["inciso_referencia"];
		$GLOBALS["x_solicitud_inciso_id"] = $row["solicitud_inciso_id"];
		$GLOBALS["x_referencia_id"] = $row["referencia_id"];
	}
	phpmkr_free_result($rs);
	return $bLoadData;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function EditData
// - Edit Data based on Key Value sKey
// - Variables used: field variables

function EditData($conn)
{
	global $x_inciso_referencia;
	$sSql = "SELECT * FROM `inciso_referencia`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_inciso_referencia) : $x_inciso_referencia;	
	$sWhere .= "(`inciso_referencia` = " . addslashes($sTmp) . ")";
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
		$bEditData = false; // Update Failed
	}else{
		$theValue = ($GLOBALS["x_solicitud_inciso_id"] != "") ? intval($GLOBALS["x_solicitud_inciso_id"]) : "NULL";
		$fieldList["`solicitud_inciso_id`"] = $theValue;
		$theValue = ($GLOBALS["x_referencia_id"] != "") ? intval($GLOBALS["x_referencia_id"]) : "NULL";
		$fieldList["`referencia_id`"] = $theValue;

		// update
		$sSql = "UPDATE `inciso_referencia` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE " . $sWhere;
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$bEditData = true; // Update Successful
	}
	return $bEditData;
}
?>
