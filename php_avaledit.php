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

// Load key from QueryString
$x_aval_id = @$_GET["aval_id"];

//if (!empty($x_aval_id )) $x_aval_id  = (get_magic_quotes_gpc()) ? stripslashes($x_aval_id ) : $x_aval_id ;
// Get action

$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I";	// Display with input box
} else {

	// Get fields from form
	$x_aval_id = @$_POST["x_aval_id"];
	$x_cliente_id = @$_POST["x_cliente_id"];
	$x_nombre_completo = @$_POST["x_nombre_completo"];
	$x_parentesco_tipo_id = @$_POST["x_parentesco_tipo_id"];
	$x_telefono = @$_POST["x_telefono"];
	$x_ingresos_mensuales = @$_POST["x_ingresos_mensuales"];
	$x_ocupacion = @$_POST["x_ocupacion"];
}

// Check if valid key
if (($x_aval_id == "") || (is_null($x_aval_id))) {
	ob_end_clean();
	header("Location: php_avallist.php");
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
			header("Location: php_avallist.php");
			exit();
		}
		break;
	case "U": // Update
		if (EditData($conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "Update Record Successful";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_avallist.php");
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
if (EW_this.x_nombre_completo && !EW_hasValue(EW_this.x_nombre_completo, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre_completo, "TEXT", "EL nombre es requerido."))
		return false;
}
if (EW_this.x_parentesco_tipo_id && !EW_hasValue(EW_this.x_parentesco_tipo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id, "SELECT", "El parentesco es requerido."))
		return false;
}
if (EW_this.x_telefono && !EW_hasValue(EW_this.x_telefono, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_telefono, "TEXT", "El teléfono es requerido."))
		return false;
}
if (EW_this.x_ingresos_mensuales && !EW_hasValue(EW_this.x_ingresos_mensuales, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ingresos_mensuales, "TEXT", "Los ingresos mensuales son requeridos."))
		return false;
}
if (EW_this.x_ingresos_mensuales && !EW_checknumber(EW_this.x_ingresos_mensuales.value)) {
	if (!EW_onError(EW_this, EW_this.x_ingresos_mensuales, "TEXT", "Los ingresos mensuales son requeridos."))
		return false; 
}
if (EW_this.x_ocupacion && !EW_hasValue(EW_this.x_ocupacion, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ocupacion, "TEXT", "La oupación es requerida."))
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
<p><span class="phpmaker">Edit TABLE: AVALES<br><br><a href="php_avallist.php">Back to List</a></span></p>
<form name="avaledit" id="avaledit" action="php_avaledit.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<table class="ewTable">
	<tr>
		<td class="ewTableHeader"><span>No</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_aval_id; ?><input type="hidden" id="x_aval_id" name="x_aval_id" value="<?php echo htmlspecialchars(@$x_aval_id); ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Cliente</span></td>
		<td class="ewTableAltRow"><span>
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
		<td class="ewTableHeader"><span>Nombre Completo</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_nombre_completo" id="x_nombre_completo" size="80" maxlength="250" value="<?php echo htmlspecialchars(@$x_nombre_completo) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Parentesco</span></td>
		<td class="ewTableAltRow"><span>
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
		<td class="ewTableHeader"><span>Teléfono</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_telefono" id="x_telefono" size="30" maxlength="50" value="<?php echo htmlspecialchars(@$x_telefono) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Ingresos mensuales</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_ingresos_mensuales" id="x_ingresos_mensuales" size="30" value="<?php echo htmlspecialchars(@$x_ingresos_mensuales) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Ocupación</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_ocupacion" id="x_ocupacion" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_ocupacion) ?>">
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
// Function EditData
// - Edit Data based on Key Value sKey
// - Variables used: field variables

function EditData($conn)
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
		$bEditData = false; // Update Failed
	}else{
		$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
		$fieldList["`cliente_id`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_completo"]) : $GLOBALS["x_nombre_completo"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`nombre_completo`"] = $theValue;
		$theValue = ($GLOBALS["x_parentesco_tipo_id"] != "") ? intval($GLOBALS["x_parentesco_tipo_id"]) : "NULL";
		$fieldList["`parentesco_tipo_id`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono"]) : $GLOBALS["x_telefono"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono`"] = $theValue;
		$theValue = ($GLOBALS["x_ingresos_mensuales"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_mensuales"]) . "'" : "NULL";
		$fieldList["`ingresos_mensuales`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ocupacion"]) : $GLOBALS["x_ocupacion"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ocupacion`"] = $theValue;

		// update
		$sSql = "UPDATE `aval` SET ";
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
