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

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = false;
$x_credito_id = @$_GET["credito_id"];
if (empty($x_credito_id)) {
	$x_credito_id = @$_POST["x_credito_id"];
	if (empty($x_credito_id)) {	
		echo "No se locaizo el credito.";	
		exit();
	}
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
	$x_vencimiento_id = @$_POST["x_vencimiento_id"];
	$x_vencimiento_num = @$_POST["x_vencimiento_num"];	
	$x_credito_id = @$_POST["x_credito_id"];
	$x_vencimiento_status_id = @$_POST["x_vencimiento_status_id"];
	$x_fecha_vencimiento = @$_POST["x_fecha_vencimiento"];
	$x_importe = @$_POST["x_importe"];
	$x_interes = @$_POST["x_interes"];
	$x_interes_moratorio = @$_POST["x_interes_moratorio"];
	$x_total_vencimiento = @$_POST["x_total_vencimiento"];	
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron los datos";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_vencimiento_revolventelist.php");
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "se ha agregado un vencimiento.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_restructura_revolvente.php?credito_id=$x_credito_id");
			exit();
		}
		break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Financiera CRECE</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">td img {display: block;}</style>
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#FFFFFF">
<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<script type="text/javascript">
<!--
function EW_checkMyForm(EW_this) {
if (EW_this.x_vencimiento_num && !EW_hasValue(EW_this.x_vencimiento_num, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_vencimiento_num, "TEXT", "El numero es requerido."))
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
	if (!EW_onError(EW_this, EW_this.x_importe, "TEXT", "El importe es incorrecto."))
		return false; 
}
if (EW_this.x_interes && !EW_hasValue(EW_this.x_interes, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_interes, "TEXT", "El interés es requerido."))
		return false;
}
if (EW_this.x_interes && !EW_checknumber(EW_this.x_interes.value)) {
	if (!EW_onError(EW_this, EW_this.x_interes, "TEXT", "El interés es incorrecto."))
		return false; 
}
if (EW_this.x_interes_moratorio && !EW_checknumber(EW_this.x_interes_moratorio.value)) {
	if (!EW_onError(EW_this, EW_this.x_interes_moratorio, "TEXT", "Interés moratorio incorrecto"))
		return false; 
}
if (EW_this.x_tota_vencimiento && !EW_hasValue(EW_this.x_tota_vencimiento, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tota_vencimiento, "TEXT", "El total es requerido."))
		return false;
}
if (EW_this.x_tota_vencimiento && !EW_checknumber(EW_this.x_tota_vencimiento.value)) {
	if (!EW_onError(EW_this, EW_this.x_tota_vencimiento, "TEXT", "El total es incorrecto."))
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
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/skins/aqua/theme.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-es.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<p><span class="phpmaker">Agregar Vencimiento<br><br><a href="php_restructura_revolvente.php?credito_id=<?php echo $x_credito_id; ?>">Cancelar</a></span></p>
<form name="vencimientoadd" id="vencimientoadd" action="" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_add" value="A">
<input type="hidden" name="x_credito_id" value="<?php echo $x_credito_id; ?>">
<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION["ewmsg"] ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>
<table width="383" class="ewTable">

	<tr>
	  <td width="151" class="ewTableHeaderThin">Numero</td>
	  <td width="837" class="ewTableAltRow"><input name="x_vencimiento_num" type="text" id="x_vencimiento_num" value="<?php echo htmlspecialchars(@$x_vencimiento_num) ?>" size="5" maxlength="5" /></td>
    </tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Status</span></td>
		<td  class="ewTableAltRow"><span>
<?php if (!(!is_null($x_vencimiento_status_id)) || ($x_vencimiento_status_id == "")) { $x_vencimiento_status_id = 0;} // Set default value ?>
<?php
$x_vencimiento_status_idList = "<select name=\"x_vencimiento_status_id\">";
$x_vencimiento_status_idList .= "<option value=''>Seleccione</option>";
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
		<td class="ewTableHeader"><span>Fecha </span></td>
		<td class="ewTableAltRow" align="left"><span>
<input name="x_fecha_vencimiento" type="text" id="x_fecha_vencimiento" value="<?php echo FormatDateTime(@$x_fecha_vencimiento,7); ?>" size="12" maxlength="10">
<img src="images/ew_calendar.gif" id="cx_fecha_vencimiento" alt="Calendario" style="cursor:pointer;cursor:hand;">
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
		<td class="ewTableHeaderThin"><span>Importe</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_importe" id="x_importe" size="15" value="<?php echo htmlspecialchars(@$x_importe) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Interés</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_interes" id="x_interes" size="15" value="<?php echo htmlspecialchars(@$x_interes) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span> moratorios</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_interes_moratorio" id="x_interes_moratorio" size="15" value="<?php echo htmlspecialchars(@$x_interes_moratorio) ?>">
</span></td>
	</tr>
	<tr>
	  <td class="ewTableHeaderThin">Total </td>
	  <td class="ewTableAltRow"><input type="text" name="x_total_vencimiento" id="x_total_vencimiento" size="15" value="<?php echo htmlspecialchars(@$x_total_vencimiento) ?>" /></td>
	  </tr>
</table>
<p>
<input type="submit" name="Action" value="Agregar">
</form>
</body>
</html>
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
// Function AddData
// - Add Data
// - Variables used: field variables

function AddData($conn)
{
	global $x_vencimiento_id;
	$sSql = "SELECT * FROM `vencimiento`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";

	// Check for duplicate key
	$bCheckKey = true;
	$sWhereChk = $sWhere;
	if ((@$x_vencimiento_id == "") || (is_null($x_vencimiento_id))) {
		$bCheckKey = false;
	} else {
		if ($sWhereChk <> "") { $sWhereChk .= " AND "; }
		$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_vencimiento_id) : $x_vencimiento_id;			
		$sWhereChk .= "(`vencimiento_id` = " . addslashes($sTmp) . ")";
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

	// Field credito_id
	$theValue = ($GLOBALS["x_credito_id"] != "") ? intval($GLOBALS["x_credito_id"]) : "NULL";
	$fieldList["`credito_id`"] = $theValue;

	// Field fecha_vencimiento
	$theValue = ($GLOBALS["x_vencimiento_num"] != "") ? " '" . $GLOBALS["x_vencimiento_num"] . "'" : "Null";
	$fieldList["`vencimiento_num`"] = $theValue;


	// Field vencimiento_status_id
	$theValue = ($GLOBALS["x_vencimiento_status_id"] != "") ? intval($GLOBALS["x_vencimiento_status_id"]) : "NULL";
	$fieldList["`vencimiento_status_id`"] = $theValue;

	// Field fecha_vencimiento
	$theValue = ($GLOBALS["x_fecha_vencimiento"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_vencimiento"]) . "'" : "Null";
	$fieldList["`fecha_vencimiento`"] = $theValue;

	// Field importe
	$theValue = ($GLOBALS["x_importe"] != "") ? " '" . doubleval($GLOBALS["x_importe"]) . "'" : "NULL";
	$fieldList["`importe`"] = $theValue;

	// Field interes
	$theValue = ($GLOBALS["x_interes"] != "") ? " '" . doubleval($GLOBALS["x_interes"]) . "'" : "NULL";
	$fieldList["`interes`"] = $theValue;

	// Field interes_moratorio
	$theValue = ($GLOBALS["x_interes_moratorio"] != "") ? " '" . doubleval($GLOBALS["x_interes_moratorio"]) . "'" : "NULL";
	$fieldList["`interes_moratorio`"] = $theValue;

	$theValue = ($GLOBALS["x_total_vencimiento"] != "") ? " '" . doubleval($GLOBALS["x_total_vencimiento"]) . "'" : "NULL";
	$fieldList["`total_venc`"] = $theValue;

	// insert into database
	$sSql = "INSERT INTO `vencimiento` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	return true;
}
?>
