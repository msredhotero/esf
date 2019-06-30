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
$x_movimiento_cont_id = Null; 
$ox_movimiento_cont_id = Null;
$x_fecha_registro = Null; 
$ox_fecha_registro = Null;
$x_fecha_movimiento = Null; 
$ox_fecha_movimiento = Null;
$x_cuenta_cont_id = Null; 
$ox_cuenta_cont_id = Null;
$x_referencia = Null; 
$ox_referencia = Null;
$x_importe = Null; 
$ox_importe = Null;
$x_movimiento_cont_status_id = Null; 
$ox_movimiento_cont_status_id = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
$x_movimiento_cont_id = @$_GET["movimiento_cont_id"];
if (empty($x_movimiento_cont_id)) {
	$bCopy = false;
}

// Get action
$sAction = @$_POST["a_add"];
if (($sAction == "") || ((is_null($sAction)))) {
	if ($bCopy) {
		$sAction = "C"; // Copy record
	}else{
		$sAction = "I"; // Display blank record
	}
}else{

	// Get fields from form
	$x_movimiento_cont_id = @$_POST["x_movimiento_cont_id"];
	$x_fecha_registro = @$_POST["x_fecha_registro"];
	$x_fecha_movimiento = @$_POST["x_fecha_movimiento"];
	$x_cuenta_cont_id = @$_POST["x_cuenta_cont_id"];
	$x_referencia = @$_POST["x_referencia"];
	$x_importe = @$_POST["x_importe"];
	$x_movimiento_cont_status_id = @$_POST["x_movimiento_cont_status_id"];
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No records found";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_movimiento_contlist.php");
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "Add New Record Successful";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_movimiento_contlist.php");
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
if (EW_this.x_fecha_registro && !EW_hasValue(EW_this.x_fecha_registro, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_registro, "TEXT", "La fecha de registro es requerida."))
		return false;
}
if (EW_this.x_fecha_registro && !EW_checkeurodate(EW_this.x_fecha_registro.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_registro, "TEXT", "La fecha de registro es requerida."))
		return false; 
}
if (EW_this.x_fecha_movimiento && !EW_hasValue(EW_this.x_fecha_movimiento, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_movimiento, "TEXT", "La fecha del movimiento es requerida."))
		return false;
}
if (EW_this.x_fecha_movimiento && !EW_checkeurodate(EW_this.x_fecha_movimiento.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_movimiento, "TEXT", "La fecha del movimiento es requerida."))
		return false; 
}
if (EW_this.x_cuenta_cont_id && !EW_hasValue(EW_this.x_cuenta_cont_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_cuenta_cont_id, "SELECT", "La cuenta es requerida."))
		return false;
}
if (EW_this.x_importe && !EW_hasValue(EW_this.x_importe, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_importe, "TEXT", "El importe es requerida."))
		return false;
}
if (EW_this.x_importe && !EW_checknumber(EW_this.x_importe.value)) {
	if (!EW_onError(EW_this, EW_this.x_importe, "TEXT", "El importe es requerida."))
		return false; 
}
if (EW_this.x_movimiento_cont_status_id && !EW_hasValue(EW_this.x_movimiento_cont_status_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_movimiento_cont_status_id, "SELECT", "El status del movimiento es requerido."))
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
<p><span class="phpmaker">Add to TABLE: Contabilidad Movimientos<br><br><a href="php_movimiento_contlist.php">Back to List</a></span></p>
<form name="movimiento_contadd" id="movimiento_contadd" action="php_movimiento_contadd.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_add" value="A">
<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION["ewmsg"] ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>
<table class="ewTable">
	<tr>
		<td class="ewTableHeader"><span>Fecha de registro</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_fecha_registro" id="x_fecha_registro" value="<?php echo FormatDateTime(@$x_fecha_registro,7); ?>">
&nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_registro" alt="Pick a Date" style="cursor:pointer;cursor:hand;">
<script type="text/javascript">
Calendar.setup(
{
inputField : "x_fecha_registro", // ID of the input field
ifFormat : "%d/%m/%Y", // the date format
button : "cx_fecha_registro" // ID of the button
}
);
</script>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Fecha del movimiento</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_fecha_movimiento" id="x_fecha_movimiento" value="<?php echo FormatDateTime(@$x_fecha_movimiento,7); ?>">
&nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_movimiento" alt="Pick a Date" style="cursor:pointer;cursor:hand;">
<script type="text/javascript">
Calendar.setup(
{
inputField : "x_fecha_movimiento", // ID of the input field
ifFormat : "%d/%m/%Y", // the date format
button : "cx_fecha_movimiento" // ID of the button
}
);
</script>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Cuenta</span></td>
		<td class="ewTableAltRow"><span>
<?php if (!(!is_null($x_cuenta_cont_id)) || ($x_cuenta_cont_id == "")) { $x_cuenta_cont_id = 0;} // Set default value ?>
<?php
$x_cuenta_cont_idList = "<select name=\"x_cuenta_cont_id\">";
$x_cuenta_cont_idList .= "<option value=''>Please Select</option>";
$sSqlWrk = "SELECT `cuenta_cont_id`, `descricpion` FROM `cuenta_cont`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_cuenta_cont_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["cuenta_cont_id"] == @$x_cuenta_cont_id) {
			$x_cuenta_cont_idList .= "' selected";
		}
		$x_cuenta_cont_idList .= ">" . $datawrk["descricpion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_cuenta_cont_idList .= "</select>";
echo $x_cuenta_cont_idList;
?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Referencia</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_referencia" id="x_referencia" size="30" maxlength="250" value="<?php echo htmlspecialchars(@$x_referencia) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Importe</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_importe" id="x_importe" size="30" value="<?php echo htmlspecialchars(@$x_importe) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Status</span></td>
		<td class="ewTableAltRow"><span>
<?php if (!(!is_null($x_movimiento_cont_status_id)) || ($x_movimiento_cont_status_id == "")) { $x_movimiento_cont_status_id = 0;} // Set default value ?>
<?php
$x_movimiento_cont_status_idList = "<select name=\"x_movimiento_cont_status_id\">";
$x_movimiento_cont_status_idList .= "<option value=''>Please Select</option>";
$sSqlWrk = "SELECT `movimiento_cont_status_id`, `descripcion` FROM `movimiento_cont_status`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_movimiento_cont_status_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["movimiento_cont_status_id"] == @$x_movimiento_cont_status_id) {
			$x_movimiento_cont_status_idList .= "' selected";
		}
		$x_movimiento_cont_status_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_movimiento_cont_status_idList .= "</select>";
echo $x_movimiento_cont_status_idList;
?>
</span></td>
	</tr>
</table>
<p>
<input type="submit" name="Action" value="ADD">
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
	global $x_movimiento_cont_id;
	$sSql = "SELECT * FROM `movimiento_cont`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_movimiento_cont_id) : $x_movimiento_cont_id;
	$sWhere .= "(`movimiento_cont_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_movimiento_cont_id"] = $row["movimiento_cont_id"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
		$GLOBALS["x_fecha_movimiento"] = $row["fecha_movimiento"];
		$GLOBALS["x_cuenta_cont_id"] = $row["cuenta_cont_id"];
		$GLOBALS["x_referencia"] = $row["referencia"];
		$GLOBALS["x_importe"] = $row["importe"];
		$GLOBALS["x_movimiento_cont_status_id"] = $row["movimiento_cont_status_id"];
	}
	phpmkr_free_result($rs);
	return $bLoadData;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function AddData
// - Add Data
// - Variables used: field variables

function AddData($conn)
{
	global $x_movimiento_cont_id;
	$sSql = "SELECT * FROM `movimiento_cont`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";

	// Check for duplicate key
	$bCheckKey = true;
	$sWhereChk = $sWhere;
	if ((@$x_movimiento_cont_id == "") || (is_null($x_movimiento_cont_id))) {
		$bCheckKey = false;
	} else {
		if ($sWhereChk <> "") { $sWhereChk .= " AND "; }
		$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_movimiento_cont_id) : $x_movimiento_cont_id;			
		$sWhereChk .= "(`movimiento_cont_id` = " . addslashes($sTmp) . ")";
	}
	if ($bCheckKey) {
		$sSqlChk = $sSql . " WHERE " . $sWhereChk;
		$rsChk = phpmkr_query($sSqlChk, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSqlChk);
		if (phpmkr_num_rows($rsChk) > 0) {
			$_SESSION["ewmsg"] = "Duplicate value for primary key";
			phpmkr_free_result($rsChk);
			return false;
		}
		phpmkr_free_result($rsChk);
	}

	// Field fecha_registro
	$theValue = ($GLOBALS["x_fecha_registro"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_registro"]) . "'" : "Null";
	$fieldList["`fecha_registro`"] = $theValue;

	// Field fecha_movimiento
	$theValue = ($GLOBALS["x_fecha_movimiento"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_movimiento"]) . "'" : "Null";
	$fieldList["`fecha_movimiento`"] = $theValue;

	// Field cuenta_cont_id
	$theValue = ($GLOBALS["x_cuenta_cont_id"] != "") ? intval($GLOBALS["x_cuenta_cont_id"]) : "NULL";
	$fieldList["`cuenta_cont_id`"] = $theValue;

	// Field referencia
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia"]) : $GLOBALS["x_referencia"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`referencia`"] = $theValue;

	// Field importe
	$theValue = ($GLOBALS["x_importe"] != "") ? " '" . doubleval($GLOBALS["x_importe"]) . "'" : "NULL";
	$fieldList["`importe`"] = $theValue;

	// Field movimiento_cont_status_id
	$theValue = ($GLOBALS["x_movimiento_cont_status_id"] != "") ? intval($GLOBALS["x_movimiento_cont_status_id"]) : "NULL";
	$fieldList["`movimiento_cont_status_id`"] = $theValue;

	// insert into database
	$sSql = "INSERT INTO `movimiento_cont` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	return true;
}
?>
