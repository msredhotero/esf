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
$x_mensajeria_id = Null; 
$ox_mensajeria_id = Null;
$x_fecha_registro = Null; 
$ox_fecha_registro = $currdate;
$x_fecha_inicio = Null; 
$ox_fecha_inicio = Null;
$x_fecha_fin = Null; 
$ox_fecha_fin = Null;
$x_asunto = Null; 
$ox_asunto = Null;
$x_mensajeria_tipo_id = Null; 
$ox_mensajeria_tipo_id = Null;
$x_formato_docto_id = Null; 
$ox_formato_docto_id = Null;
$x_mensajeria_status_id = Null; 
$ox_mensajeria_status_id = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
$x_mensajeria_id = @$_GET["mensajeria_id"];
if (empty($x_mensajeria_id)) {
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
	$x_mensajeria_id = @$_POST["x_mensajeria_id"];
	$x_fecha_registro = @$_POST["x_fecha_registro"];
	$x_fecha_inicio = @$_POST["x_fecha_inicio"];
	$x_fecha_fin = @$_POST["x_fecha_fin"];
	$x_asunto = @$_POST["x_asunto"];
	$x_mensajeria_tipo_id = @$_POST["x_mensajeria_tipo_id"];
	$x_formato_docto_id = @$_POST["x_formato_docto_id"];
	$x_mensajeria_status_id = @$_POST["x_mensajeria_status_id"];
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron los datos.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_mensajerialist.php");
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "Los datos han sido actualizados.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_mensajerialist.php");
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
	if (!EW_onError(EW_this, EW_this.x_fecha_registro, "TEXT", "Ls fecha de registro es requerida."))
		return false;
}
if (EW_this.x_fecha_registro && !EW_checkeurodate(EW_this.x_fecha_registro.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_registro, "TEXT", "Ls fecha de registro es requerida."))
		return false; 
}
if (EW_this.x_fecha_inicio && !EW_hasValue(EW_this.x_fecha_inicio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_inicio, "TEXT", "La fecha de inicio es requerida."))
		return false;
}
if (EW_this.x_fecha_inicio && !EW_checkeurodate(EW_this.x_fecha_inicio.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_inicio, "TEXT", "La fecha de inicio es requerida."))
		return false; 
}
if (EW_this.x_fecha_fin && !EW_hasValue(EW_this.x_fecha_fin, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_fin, "TEXT", "La fecha de fin es requerida."))
		return false;
}
if (EW_this.x_fecha_fin && !EW_checkeurodate(EW_this.x_fecha_fin.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_fin, "TEXT", "La fecha de fin es requerida."))
		return false; 
}
if (EW_this.x_asunto && !EW_hasValue(EW_this.x_asunto, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_asunto, "TEXT", "El asunto del mensaje es requerido."))
		return false;
}
if (EW_this.x_mensajeria_tipo_id && !EW_hasValue(EW_this.x_mensajeria_tipo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_mensajeria_tipo_id, "SELECT", "El asunto del mensaje es requerido."))
		return false;
}
if (EW_this.x_formato_docto_id && !EW_hasValue(EW_this.x_formato_docto_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_formato_docto_id, "SELECT", "El formato del mensaje es requerido."))
		return false;
}
if (EW_this.x_mensajeria_status_id && !EW_hasValue(EW_this.x_mensajeria_status_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_mensajeria_status_id, "SELECT", "El status del mensaje es querido."))
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
<script type="text/javascript" src="jscalendar/lang/calendar-sp.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<p><span class="phpmaker">Mensajeria<br>
  <br>
    <a href="php_mensajerialist.php">Regresar a la lista</a></span></p>
<form name="mensajeriaadd" id="mensajeriaadd" action="php_mensajeriaadd.php" method="post" onSubmit="return EW_checkMyForm(this);">
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
<table class="ewTable_small">
	<tr>
	  <td class="ewTableHeaderThin"><span>Tipo de mensajeria</span></td>
	  <td class="ewTableAltRow"><span>
	    <?php if (!(!is_null($x_mensajeria_tipo_id)) || ($x_mensajeria_tipo_id == "")) { $x_mensajeria_tipo_id = 0;} // Set default value ?>
        <?php
$x_mensajeria_tipo_idList = "<select name=\"x_mensajeria_tipo_id\">";
$x_mensajeria_tipo_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT `mensajeria_tipo_id`, `descripcion` FROM `mensajeria_tipo`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_mensajeria_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["mensajeria_tipo_id"] == @$x_mensajeria_tipo_id) {
			$x_mensajeria_tipo_idList .= "' selected";
		}
		$x_mensajeria_tipo_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_mensajeria_tipo_idList .= "</select>";
echo $x_mensajeria_tipo_idList;
?>
      </span></td>
	  </tr>
	<tr>
	  <td class="ewTableHeaderThin"><span>Asunto</span></td>
	  <td class="ewTableAltRow"><span>
	    <input type="text" name="x_asunto" id="x_asunto" size="50" maxlength="50" value="<?php echo htmlspecialchars(@$x_asunto) ?>" />
	  </span></td>
	  </tr>
	<tr>
	  <td class="ewTableHeaderThin"><span>Formato</span></td>
	  <td class="ewTableAltRow"><span>
	    <?php if (!(!is_null($x_formato_docto_id)) || ($x_formato_docto_id == "")) { $x_formato_docto_id = 0;} // Set default value ?>
        <?php
$x_formato_docto_idList = "<select name=\"x_formato_docto_id\">";
$x_formato_docto_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT `formato_docto_id`, `descripcion` FROM `formato_docto`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_formato_docto_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["formato_docto_id"] == @$x_formato_docto_id) {
			$x_formato_docto_idList .= "' selected";
		}
		$x_formato_docto_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_formato_docto_idList .= "</select>";
echo $x_formato_docto_idList;
?>
      </span></td>
	  </tr>
	<tr>
		<td width="148" class="ewTableHeaderThin"><span>Fecha de registro</span></td>
		<td width="840" class="ewTableAltRow"><span>
<input type="text" name="x_fecha_registro" id="x_fecha_registro" value="<?php echo FormatDateTime(@$x_fecha_registro,7); ?>">
&nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_registro" alt="Calendario" style="cursor:pointer;cursor:hand;">
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
		<td class="ewTableHeaderThin"><span>Fecha de inicio</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_fecha_inicio" id="x_fecha_inicio" value="<?php echo FormatDateTime(@$x_fecha_inicio,7); ?>">
&nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_inicio" alt="Calendario" style="cursor:pointer;cursor:hand;">
<script type="text/javascript">
Calendar.setup(
{
inputField : "x_fecha_inicio", // ID of the input field
ifFormat : "%d/%m/%Y", // the date format
button : "cx_fecha_inicio" // ID of the button
}
);
</script>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Fecha de fin</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_fecha_fin" id="x_fecha_fin" value="<?php echo FormatDateTime(@$x_fecha_fin,7); ?>">
&nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_fin" alt="Calendario" style="cursor:pointer;cursor:hand;">
<script type="text/javascript">
Calendar.setup(
{
inputField : "x_fecha_fin", // ID of the input field
ifFormat : "%d/%m/%Y", // the date format
button : "cx_fecha_fin" // ID of the button
}
);
</script>
</span></td>
	</tr>
	
	<tr>
		<td class="ewTableHeaderThin"><span>Status</span></td>
		<td class="ewTableAltRow"><span>
<?php if (!(!is_null($x_mensajeria_status_id)) || ($x_mensajeria_status_id == "")) { $x_mensajeria_status_id = 0;} // Set default value ?>
<?php
$x_mensajeria_status_idList = "<select name=\"x_mensajeria_status_id\">";
$x_mensajeria_status_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT `mensajeria_status_id`, `descripcion` FROM `mensajeria_status`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_mensajeria_status_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["mensajeria_status_id"] == @$x_mensajeria_status_id) {
			$x_mensajeria_status_idList .= "' selected";
		}
		$x_mensajeria_status_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_mensajeria_status_idList .= "</select>";
echo $x_mensajeria_status_idList;
?>
</span></td>
	</tr>
</table>
<p>
<input type="submit" name="Action" value="Agregar">
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
	global $x_mensajeria_id;
	$sSql = "SELECT * FROM `mensajeria`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_mensajeria_id) : $x_mensajeria_id;
	$sWhere .= "(`mensajeria_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_mensajeria_id"] = $row["mensajeria_id"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
		$GLOBALS["x_fecha_inicio"] = $row["fecha_inicio"];
		$GLOBALS["x_fecha_fin"] = $row["fecha_fin"];
		$GLOBALS["x_asunto"] = $row["asunto"];
		$GLOBALS["x_mensajeria_tipo_id"] = $row["mensajeria_tipo_id"];
		$GLOBALS["x_formato_docto_id"] = $row["formato_docto_id"];
		$GLOBALS["x_mensajeria_status_id"] = $row["mensajeria_status_id"];
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
	global $x_mensajeria_id;
	$sSql = "SELECT * FROM `mensajeria`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";

	// Check for duplicate key
	$bCheckKey = true;
	$sWhereChk = $sWhere;
	if ((@$x_mensajeria_id == "") || (is_null($x_mensajeria_id))) {
		$bCheckKey = false;
	} else {
		if ($sWhereChk <> "") { $sWhereChk .= " AND "; }
		$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_mensajeria_id) : $x_mensajeria_id;			
		$sWhereChk .= "(`mensajeria_id` = " . addslashes($sTmp) . ")";
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

	// Field fecha_inicio
	$theValue = ($GLOBALS["x_fecha_inicio"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_inicio"]) . "'" : "Null";
	$fieldList["`fecha_inicio`"] = $theValue;

	// Field fecha_fin
	$theValue = ($GLOBALS["x_fecha_fin"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_fin"]) . "'" : "Null";
	$fieldList["`fecha_fin`"] = $theValue;

	// Field asunto
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_asunto"]) : $GLOBALS["x_asunto"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`asunto`"] = $theValue;

	// Field mensajeria_tipo_id
	$theValue = ($GLOBALS["x_mensajeria_tipo_id"] != "") ? intval($GLOBALS["x_mensajeria_tipo_id"]) : "NULL";
	$fieldList["`mensajeria_tipo_id`"] = $theValue;

	// Field formato_docto_id
	$theValue = ($GLOBALS["x_formato_docto_id"] != "") ? intval($GLOBALS["x_formato_docto_id"]) : "NULL";
	$fieldList["`formato_docto_id`"] = $theValue;

	// Field mensajeria_status_id
	$theValue = ($GLOBALS["x_mensajeria_status_id"] != "") ? intval($GLOBALS["x_mensajeria_status_id"]) : "NULL";
	$fieldList["`mensajeria_status_id`"] = $theValue;

	// insert into database
	$sSql = "INSERT INTO `mensajeria` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	return true;
}
?>
