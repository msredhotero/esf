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

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
$x_ingreso_id = @$_GET["ingreso_id"];
if (empty($x_ingreso_id)) {
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
	$x_ingreso_id = @$_POST["x_ingreso_id"];
	$x_cliente_id = @$_POST["x_cliente_id"];
	$x_ingresos_negocio = @$_POST["x_ingresos_negocio"];
	$x_ingresos_familiar_1 = @$_POST["x_ingresos_familiar_1"];
	$x_parentesco_tipo_id = @$_POST["x_parentesco_tipo_id"];
	$x_ingresos_familiar_2 = @$_POST["x_ingresos_familiar_2"];
	$x_parentesco_tipo_id2 = @$_POST["x_parentesco_tipo_id2"];
	$x_otros_ingresos = @$_POST["x_otros_ingresos"];
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No records found";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_ingresolist.php");
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "Add New Record Successful";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_ingresolist.php");
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
if (EW_this.x_cliente_id && !EW_hasValue(EW_this.x_cliente_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_cliente_id, "SELECT", "El cliente es requerido."))
		return false;
}
if (EW_this.x_ingresos_negocio && !EW_checknumber(EW_this.x_ingresos_negocio.value)) {
	if (!EW_onError(EW_this, EW_this.x_ingresos_negocio, "TEXT", "Incorrect floating point number - Ingresos del negocio"))
		return false; 
}
if (EW_this.x_ingresos_familiar_1 && !EW_checknumber(EW_this.x_ingresos_familiar_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_ingresos_familiar_1, "TEXT", "Incorrect floating point number - Ingresos familiares 1"))
		return false; 
}
if (EW_this.x_parentesco_tipo_id && !EW_hasValue(EW_this.x_parentesco_tipo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id, "SELECT", "El parentesco 1 es requerido."))
		return false;
}
if (EW_this.x_ingresos_familiar_2 && !EW_checknumber(EW_this.x_ingresos_familiar_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_ingresos_familiar_2, "TEXT", "Incorrect floating point number - Ingresos familiares 2"))
		return false; 
}
if (EW_this.x_parentesco_tipo_id2 && !EW_hasValue(EW_this.x_parentesco_tipo_id2, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id2, "SELECT", "El parentesco 2 es requerido."))
		return false;
}
if (EW_this.x_otros_ingresos && !EW_checknumber(EW_this.x_otros_ingresos.value)) {
	if (!EW_onError(EW_this, EW_this.x_otros_ingresos, "TEXT", "Incorrect floating point number - Otros ingresos"))
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
<p><span class="phpmaker">Add to TABLE: ingreso<br><br><a href="php_ingresolist.php">Back to List</a></span></p>
<form name="ingresoadd" id="ingresoadd" action="php_ingresoadd.php" method="post" onSubmit="return EW_checkMyForm(this);">
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
		<td class="ewTableHeader"><span>Cliente</span></td>
		<td class="ewTableAltRow"><span>
<?php if (!(!is_null($x_cliente_id)) || ($x_cliente_id == "")) { $x_cliente_id = 0;} // Set default value ?>
<?php
$x_cliente_idList = "<select name=\"x_cliente_id\">";
$x_cliente_idList .= "<option value=''>Please Select</option>";
$sSqlWrk = "SELECT `cliente_id`, `nombre_completo` FROM `cliente`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_cliente_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["cliente_id"] == @$x_cliente_id) {
			$x_cliente_idList .= "' selected";
		}
		$x_cliente_idList .= ">" . $datawrk["nombre_completo"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_cliente_idList .= "</select>";
echo $x_cliente_idList;
?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Ingresos del negocio</span></td>
		<td class="ewTableAltRow"><span>
<input name="x_ingresos_negocio" type="text" id="x_ingresos_negocio" value="<?php echo htmlspecialchars(@$x_ingresos_negocio) ?>" size="10" maxlength="10">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Ingresos familiares 1</span></td>
		<td class="ewTableAltRow"><span>
<input name="x_ingresos_familiar_1" type="text" id="x_ingresos_familiar_1" value="<?php echo htmlspecialchars(@$x_ingresos_familiar_1) ?>" size="10" maxlength="10">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Parentesco</span></td>
		<td class="ewTableAltRow"><span>
<?php if (!(!is_null($x_parentesco_tipo_id)) || ($x_parentesco_tipo_id == "")) { $x_parentesco_tipo_id = 0;} // Set default value ?>
<?php
$x_parentesco_tipo_idList = "<select name=\"x_parentesco_tipo_id\">";
$x_parentesco_tipo_idList .= "<option value=''>Please Select</option>";
$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_tipo_id) {
			$x_parentesco_tipo_idList .= "' selected";
		}
		$x_parentesco_tipo_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_parentesco_tipo_idList .= "</select>";
echo $x_parentesco_tipo_idList;
?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Ingresos familiares 2</span></td>
		<td class="ewTableAltRow"><span>
<input name="x_ingresos_familiar_2" type="text" id="x_ingresos_familiar_2" value="<?php echo htmlspecialchars(@$x_ingresos_familiar_2) ?>" size="10" maxlength="10">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Parentesco</span></td>
		<td class="ewTableAltRow"><span>
<?php
$x_parentesco_tipo_id2List = "<select name=\"x_parentesco_tipo_id2\">";
$x_parentesco_tipo_id2List .= "<option value=''>Please Select</option>";
$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_parentesco_tipo_id2List .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_tipo_id2) {
			$x_parentesco_tipo_id2List .= "' selected";
		}
		$x_parentesco_tipo_id2List .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_parentesco_tipo_id2List .= "</select>";
echo $x_parentesco_tipo_id2List;
?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Otros ingresos</span></td>
		<td class="ewTableAltRow"><span>
<input name="x_otros_ingresos" type="text" id="x_otros_ingresos" value="<?php echo htmlspecialchars(@$x_otros_ingresos) ?>" size="10" maxlength="10">
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
// Function AddData
// - Add Data
// - Variables used: field variables

function AddData($conn)
{
	global $x_ingreso_id;
	$sSql = "SELECT * FROM `ingreso`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";

	// Check for duplicate key
	$bCheckKey = true;
	$sWhereChk = $sWhere;
	if ((@$x_ingreso_id == "") || (is_null($x_ingreso_id))) {
		$bCheckKey = false;
	} else {
		if ($sWhereChk <> "") { $sWhereChk .= " AND "; }
		$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_ingreso_id) : $x_ingreso_id;			
		$sWhereChk .= "(`ingreso_id` = " . addslashes($sTmp) . ")";
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

	// Field cliente_id
	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
	$fieldList["`cliente_id`"] = $theValue;

	// Field ingresos_negocio
	$theValue = ($GLOBALS["x_ingresos_negocio"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_negocio"]) . "'" : "NULL";
	$fieldList["`ingresos_negocio`"] = $theValue;

	// Field ingresos_familiar_1
	$theValue = ($GLOBALS["x_ingresos_familiar_1"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_familiar_1"]) . "'" : "NULL";
	$fieldList["`ingresos_familiar_1`"] = $theValue;

	// Field parentesco_tipo_id
	$theValue = ($GLOBALS["x_parentesco_tipo_id"] != "") ? intval($GLOBALS["x_parentesco_tipo_id"]) : "NULL";
	$fieldList["`parentesco_tipo_id`"] = $theValue;

	// Field ingresos_familiar_2
	$theValue = ($GLOBALS["x_ingresos_familiar_2"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_familiar_2"]) . "'" : "NULL";
	$fieldList["`ingresos_familiar_2`"] = $theValue;

	// Field parentesco_tipo_id2
	$theValue = ($GLOBALS["x_parentesco_tipo_id2"] != "") ? intval($GLOBALS["x_parentesco_tipo_id2"]) : "NULL";
	$fieldList["`parentesco_tipo_id2`"] = $theValue;

	// Field otros_ingresos
	$theValue = ($GLOBALS["x_otros_ingresos"] != "") ? " '" . doubleval($GLOBALS["x_otros_ingresos"]) . "'" : "NULL";
	$fieldList["`otros_ingresos`"] = $theValue;

	// insert into database
	$sSql = "INSERT INTO `ingreso` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	return true;
}
?>
