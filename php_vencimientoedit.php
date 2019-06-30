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
$x_vencimiento_id = Null; 
$ox_vencimiento_id = Null;
$x_credito_id = Null; 
$ox_credito_id = Null;
$x_vencimiento_status_id = Null; 
$ox_vencimiento_status_id = Null;
$x_fecha_vencimiento = Null; 
$ox_fecha_vencimiento = Null;
$x_importe = Null; 
$ox_importe = Null;
$x_interes = Null; 
$ox_interes = Null;
$x_interes_moratorio = Null; 
$ox_interes_moratorio = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Load key from QueryString
$x_vencimiento_id = @$_GET["vencimiento_id"];

//if (!empty($x_vencimiento_id )) $x_vencimiento_id  = (get_magic_quotes_gpc()) ? stripslashes($x_vencimiento_id ) : $x_vencimiento_id ;
// Get action

$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I";	// Display with input box
} else {

	// Get fields from form
	$x_vencimiento_id = @$_POST["x_vencimiento_id"];
	$x_credito_id = @$_POST["x_credito_id"];
	$x_vencimiento_status_id = @$_POST["x_vencimiento_status_id"];
	$x_fecha_vencimiento = @$_POST["x_fecha_vencimiento"];
	$x_importe = @$_POST["x_importe"];
	$x_interes = @$_POST["x_interes"];
	$x_interes_moratorio = @$_POST["x_interes_moratorio"];
}

// Check if valid key
if (($x_vencimiento_id == "") || (is_null($x_vencimiento_id))) {
	ob_end_clean();
	header("Location: php_vencimientolist.php");
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
			header("Location: php_vencimientolist.php");
			exit();
		}
		break;
	case "U": // Update
		if (EditData($conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "Update Record Successful";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_vencimientolist.php");
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
if (EW_this.x_credito_id && !EW_hasValue(EW_this.x_credito_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_credito_id, "SELECT", "El crédito es requerido."))
		return false;
}
if (EW_this.x_vencimiento_status_id && !EW_hasValue(EW_this.x_vencimiento_status_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_vencimiento_status_id, "SELECT", "El status es requerido."))
		return false;
}
if (EW_this.x_fecha_vencimiento && !EW_hasValue(EW_this.x_fecha_vencimiento, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_vencimiento, "TEXT", "La fecha de vencimiento es requerida."))
		return false;
}
if (EW_this.x_fecha_vencimiento && !EW_checkeurodate(EW_this.x_fecha_vencimiento.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_vencimiento, "TEXT", "La fecha de vencimiento es requerida."))
		return false; 
}
if (EW_this.x_importe && !EW_hasValue(EW_this.x_importe, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_importe, "TEXT", "El importe es requerido."))
		return false;
}
if (EW_this.x_importe && !EW_checknumber(EW_this.x_importe.value)) {
	if (!EW_onError(EW_this, EW_this.x_importe, "TEXT", "El importe es requerido."))
		return false; 
}
if (EW_this.x_interes && !EW_hasValue(EW_this.x_interes, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_interes, "TEXT", "El interés es requerido."))
		return false;
}
if (EW_this.x_interes && !EW_checknumber(EW_this.x_interes.value)) {
	if (!EW_onError(EW_this, EW_this.x_interes, "TEXT", "El interés es requerido."))
		return false; 
}
if (EW_this.x_interes_moratorio && !EW_checknumber(EW_this.x_interes_moratorio.value)) {
	if (!EW_onError(EW_this, EW_this.x_interes_moratorio, "TEXT", "Incorrect floating point number - Interés moratorio"))
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
<!--script type="text/javascript" src="popcalendar.js"></script-->
<!-- New popup calendar -->
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-1.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<p><span class="phpmaker">Edit TABLE: VENCIMIENTOS<br><br><a href="php_vencimientolist.php">Back to List</a></span></p>
<form name="vencimientoedit" id="vencimientoedit" action="php_vencimientoedit.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<table class="ewTable">
	<tr>
		<td class="ewTableHeader"><span>No</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_vencimiento_id; ?><input type="hidden" id="x_vencimiento_id" name="x_vencimiento_id" value="<?php echo htmlspecialchars(@$x_vencimiento_id); ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Crédito</span></td>
		<td class="ewTableAltRow"><span>
<?php
$x_credito_idList = "<select name=\"x_credito_id\">";
$x_credito_idList .= "<option value=''>Please Select</option>";
$sSqlWrk = "SELECT `credito_id` FROM `credito`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_credito_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["credito_id"] == @$x_credito_id) {
			$x_credito_idList .= "' selected";
		}
		$x_credito_idList .= ">" . $datawrk["credito_id"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_credito_idList .= "</select>";
echo $x_credito_idList;
?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Status</span></td>
		<td class="ewTableAltRow"><span>
<?php
$x_vencimiento_status_idList = "<select name=\"x_vencimiento_status_id\">";
$x_vencimiento_status_idList .= "<option value=''>Please Select</option>";
$sSqlWrk = "SELECT `vencimiento_status_id`, `descripcion` FROM `vencimiento_status`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_vencimiento_status_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["vencimiento_status_id"] == @$x_vencimiento_status_id) {
			$x_vencimiento_status_idList .= "' selected";
		}
		$x_vencimiento_status_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_vencimiento_status_idList .= "</select>";
echo $x_vencimiento_status_idList;
?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Fecha de vencimiento</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_fecha_vencimiento" id="x_fecha_vencimiento" value="<?php echo FormatDateTime(@$x_fecha_vencimiento,7); ?>">
&nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_vencimiento" alt="Pick a Date" style="cursor:pointer;cursor:hand;">
<script type="text/javascript">
Calendar.setup(
{
inputField : "x_fecha_vencimiento", // ID of the input field
ifFormat : "%d/%m/%Y", // the date format
button : "cx_fecha_vencimiento" // ID of the button
}
);
</script>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Importe</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_importe" id="x_importe" size="30" value="<?php echo htmlspecialchars(@$x_importe) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Interés</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_interes" id="x_interes" size="30" value="<?php echo htmlspecialchars(@$x_interes) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Interés moratorio</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_interes_moratorio" id="x_interes_moratorio" size="30" value="<?php echo htmlspecialchars(@$x_interes_moratorio) ?>">
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
	global $x_vencimiento_id;
	$sSql = "SELECT * FROM `vencimiento`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_vencimiento_id) : $x_vencimiento_id;
	$sWhere .= "(`vencimiento_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_vencimiento_id"] = $row["vencimiento_id"];
		$GLOBALS["x_credito_id"] = $row["credito_id"];
		$GLOBALS["x_vencimiento_status_id"] = $row["vencimiento_status_id"];
		$GLOBALS["x_fecha_vencimiento"] = $row["fecha_vencimiento"];
		$GLOBALS["x_importe"] = $row["importe"];
		$GLOBALS["x_interes"] = $row["interes"];
		$GLOBALS["x_interes_moratorio"] = $row["interes_moratorio"];
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
	global $x_vencimiento_id;
	$sSql = "SELECT * FROM `vencimiento`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_vencimiento_id) : $x_vencimiento_id;	
	$sWhere .= "(`vencimiento_id` = " . addslashes($sTmp) . ")";
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
		$theValue = ($GLOBALS["x_credito_id"] != "") ? intval($GLOBALS["x_credito_id"]) : "NULL";
		$fieldList["`credito_id`"] = $theValue;
		$theValue = ($GLOBALS["x_vencimiento_status_id"] != "") ? intval($GLOBALS["x_vencimiento_status_id"]) : "NULL";
		$fieldList["`vencimiento_status_id`"] = $theValue;
		$theValue = ($GLOBALS["x_fecha_vencimiento"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_vencimiento"]) . "'" : "Null";
		$fieldList["`fecha_vencimiento`"] = $theValue;
		$theValue = ($GLOBALS["x_importe"] != "") ? " '" . doubleval($GLOBALS["x_importe"]) . "'" : "NULL";
		$fieldList["`importe`"] = $theValue;
		$theValue = ($GLOBALS["x_interes"] != "") ? " '" . doubleval($GLOBALS["x_interes"]) . "'" : "NULL";
		$fieldList["`interes`"] = $theValue;
		$theValue = ($GLOBALS["x_interes_moratorio"] != "") ? " '" . doubleval($GLOBALS["x_interes_moratorio"]) . "'" : "NULL";
		$fieldList["`interes_moratorio`"] = $theValue;

		// update
		$sSql = "UPDATE `vencimiento` SET ";
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
