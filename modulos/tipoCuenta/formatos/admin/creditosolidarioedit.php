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
$x_creditoSolidario_id = Null;
$x_nombre_grupo = Null;
$x_promotor = Null;
$x_representante_sugerido = Null;
$x_tesorero = Null;
$x_numero_integrantes = Null;
$x_integrante_1 = Null;
$x_monto_1 = Null;
$x_integrante_2 = Null;
$x_monto_2 = Null;
$x_integrante_3 = Null;
$x_monto_3 = Null;
$x_integrante_4 = Null;
$x_monto_4 = Null;
$x_integrante_5 = Null;
$x_monto_5 = Null;
$x_integrante_6 = Null;
$x_monto_6 = Null;
$x_integrante_7 = Null;
$x_monto_7 = Null;
$x_integrante_8 = Null;
$x_monto_8 = Null;
$x_integrante_9 = Null;
$x_monto_9 = Null;
$x_integrante_10 = Null;
$x_monto_10 = Null;
$x_monto_total = Null;
$x_fecha_registro = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
$sKey = @$HTTP_GET_VARS["key"];
if (($sKey == "") || ($sKey == NULL)) { $sKey = @$HTTP_POST_VARS["key"]; }
if (!empty($sKey)) $sKey = (get_magic_quotes_gpc()) ? stripslashes($sKey) : $sKey;

// Get action
$sAction = @$HTTP_POST_VARS["a_edit"];
if (($sAction == "") || (($sAction == NULL))) {
	$sAction = "I";	// Display with input box
} else {

	// Get fields from form
	$x_creditoSolidario_id = @$HTTP_POST_VARS["x_creditoSolidario_id"];
	$x_nombre_grupo = @$HTTP_POST_VARS["x_nombre_grupo"];
	$x_promotor = @$HTTP_POST_VARS["x_promotor"];
	$x_representante_sugerido = @$HTTP_POST_VARS["x_representante_sugerido"];
	$x_tesorero = @$HTTP_POST_VARS["x_tesorero"];
	$x_numero_integrantes = @$HTTP_POST_VARS["x_numero_integrantes"];
	$x_integrante_1 = @$HTTP_POST_VARS["x_integrante_1"];
	$x_monto_1 = @$HTTP_POST_VARS["x_monto_1"];
	$x_integrante_2 = @$HTTP_POST_VARS["x_integrante_2"];
	$x_monto_2 = @$HTTP_POST_VARS["x_monto_2"];
	$x_integrante_3 = @$HTTP_POST_VARS["x_integrante_3"];
	$x_monto_3 = @$HTTP_POST_VARS["x_monto_3"];
	$x_integrante_4 = @$HTTP_POST_VARS["x_integrante_4"];
	$x_monto_4 = @$HTTP_POST_VARS["x_monto_4"];
	$x_integrante_5 = @$HTTP_POST_VARS["x_integrante_5"];
	$x_monto_5 = @$HTTP_POST_VARS["x_monto_5"];
	$x_integrante_6 = @$HTTP_POST_VARS["x_integrante_6"];
	$x_monto_6 = @$HTTP_POST_VARS["x_monto_6"];
	$x_integrante_7 = @$HTTP_POST_VARS["x_integrante_7"];
	$x_monto_7 = @$HTTP_POST_VARS["x_monto_7"];
	$x_integrante_8 = @$HTTP_POST_VARS["x_integrante_8"];
	$x_monto_8 = @$HTTP_POST_VARS["x_monto_8"];
	$x_integrante_9 = @$HTTP_POST_VARS["x_integrante_9"];
	$x_monto_9 = @$HTTP_POST_VARS["x_monto_9"];
	$x_integrante_10 = @$HTTP_POST_VARS["x_integrante_10"];
	$x_monto_10 = @$HTTP_POST_VARS["x_monto_10"];
	$x_monto_total = @$HTTP_POST_VARS["x_monto_total"];
	$x_fecha_registro = @$HTTP_POST_VARS["x_fecha_registro"];
}
if (($sKey == "") || (($sKey == NULL))) {
	ob_end_clean();
	header("Location: creditosolidariolist.php");
}
$conn = phpmkr_db_connect(HOST,USER,PASS,DB);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($sKey,$conn)) { // Load Record based on key
			$HTTP_SESSION_VARS["ewmsg"] = "No Record Found for Key = " . $sKey;
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: creditosolidariolist.php");
		}
		break;
	case "U": // Update
		if (EditData($sKey,$conn)) { // Update Record based on key
			$HTTP_SESSION_VARS["ewmsg"] = "Update Record Successful for Key = " . $sKey;
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: creditosolidariolist.php");
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
if (EW_this.x_nombre_grupo && !EW_hasValue(EW_this.x_nombre_grupo, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre_grupo, "TEXT", "Please enter required field - nombre grupo"))
		return false;
}
if (EW_this.x_promotor && !EW_hasValue(EW_this.x_promotor, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_promotor, "TEXT", "Please enter required field - promotor"))
		return false;
}
if (EW_this.x_representante_sugerido && !EW_hasValue(EW_this.x_representante_sugerido, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_representante_sugerido, "TEXT", "Please enter required field - representante sugerido"))
		return false;
}
if (EW_this.x_tesorero && !EW_hasValue(EW_this.x_tesorero, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tesorero, "TEXT", "Please enter required field - tesorero"))
		return false;
}
if (EW_this.x_numero_integrantes && !EW_hasValue(EW_this.x_numero_integrantes, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_numero_integrantes, "TEXT", "Please enter required field - numero integrantes"))
		return false;
}
if (EW_this.x_numero_integrantes && !EW_checkinteger(EW_this.x_numero_integrantes.value)) {
	if (!EW_onError(EW_this, EW_this.x_numero_integrantes, "TEXT", "Incorrect integer - numero integrantes"))
		return false; 
}
if (EW_this.x_monto_1 && !EW_checknumber(EW_this.x_monto_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_1, "TEXT", "Incorrect floating point number - monto 1"))
		return false; 
}
if (EW_this.x_monto_2 && !EW_checknumber(EW_this.x_monto_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_2, "TEXT", "Incorrect floating point number - monto 2"))
		return false; 
}
if (EW_this.x_monto_3 && !EW_checknumber(EW_this.x_monto_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_3, "TEXT", "Incorrect floating point number - monto 3"))
		return false; 
}
if (EW_this.x_monto_4 && !EW_checknumber(EW_this.x_monto_4.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_4, "TEXT", "Incorrect floating point number - monto 4"))
		return false; 
}
if (EW_this.x_monto_5 && !EW_checknumber(EW_this.x_monto_5.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_5, "TEXT", "Incorrect floating point number - monto 5"))
		return false; 
}
if (EW_this.x_monto_6 && !EW_checknumber(EW_this.x_monto_6.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_6, "TEXT", "Incorrect floating point number - monto 6"))
		return false; 
}
if (EW_this.x_monto_7 && !EW_checknumber(EW_this.x_monto_7.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_7, "TEXT", "Incorrect floating point number - monto 7"))
		return false; 
}
if (EW_this.x_monto_8 && !EW_checknumber(EW_this.x_monto_8.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_8, "TEXT", "Incorrect floating point number - monto 8"))
		return false; 
}
if (EW_this.x_monto_9 && !EW_checknumber(EW_this.x_monto_9.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_9, "TEXT", "Incorrect floating point number - monto 9"))
		return false; 
}
if (EW_this.x_monto_10 && !EW_checknumber(EW_this.x_monto_10.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_10, "TEXT", "Incorrect floating point number - monto 10"))
		return false; 
}
if (EW_this.x_monto_total && !EW_checknumber(EW_this.x_monto_total.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_total, "TEXT", "Incorrect floating point number - monto total"))
		return false; 
}
if (EW_this.x_fecha_registro && !EW_hasValue(EW_this.x_fecha_registro, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_registro, "TEXT", "Please enter required field - fecha registro"))
		return false;
}
if (EW_this.x_fecha_registro && !EW_checkdate(EW_this.x_fecha_registro.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_registro, "TEXT", "Incorrect date, format = yyyy/mm/dd - fecha registro"))
		return false; 
}
return true;
}

//-->
</script>
<p><span class="phpmaker">Edit TABLE: creditosolidario<br><br><a href="creditosolidariolist.php">Back to List</a></span></p>
<form name="creditosolidarioedit" id="creditosolidarioedit" action="creditosolidarioedit.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<input type="hidden" name="key" value="<?php echo htmlspecialchars($sKey); ?>">
<table class="ewTable">
	<tr>
		<td class="ewTableHeader"><span>credito Solidario id</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_creditoSolidario_id; ?><input type="hidden" name="x_creditoSolidario_id" value="<?php echo $x_creditoSolidario_id; ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>nombre grupo</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_nombre_grupo" id="x_nombre_grupo" value="<?php echo htmlspecialchars(@$x_nombre_grupo) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>promotor</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_promotor" id="x_promotor" value="<?php echo htmlspecialchars(@$x_promotor) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>representante sugerido</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_representante_sugerido" id="x_representante_sugerido" value="<?php echo htmlspecialchars(@$x_representante_sugerido) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>tesorero</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_tesorero" id="x_tesorero" value="<?php echo htmlspecialchars(@$x_tesorero) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>numero integrantes</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_numero_integrantes" id="x_numero_integrantes" size="30" value="<?php echo htmlspecialchars(@$x_numero_integrantes) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>integrante 1</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_integrante_1" id="x_integrante_1" value="<?php echo htmlspecialchars(@$x_integrante_1) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>monto 1</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_monto_1" id="x_monto_1" size="30" value="<?php echo htmlspecialchars(@$x_monto_1) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>integrante 2</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_integrante_2" id="x_integrante_2" value="<?php echo htmlspecialchars(@$x_integrante_2) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>monto 2</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_monto_2" id="x_monto_2" size="30" value="<?php echo htmlspecialchars(@$x_monto_2) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>integrante 3</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_integrante_3" id="x_integrante_3" value="<?php echo htmlspecialchars(@$x_integrante_3) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>monto 3</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_monto_3" id="x_monto_3" size="30" value="<?php echo htmlspecialchars(@$x_monto_3) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>integrante 4</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_integrante_4" id="x_integrante_4" value="<?php echo htmlspecialchars(@$x_integrante_4) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>monto 4</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_monto_4" id="x_monto_4" size="30" value="<?php echo htmlspecialchars(@$x_monto_4) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>integrante 5</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_integrante_5" id="x_integrante_5" value="<?php echo htmlspecialchars(@$x_integrante_5) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>monto 5</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_monto_5" id="x_monto_5" size="30" value="<?php echo htmlspecialchars(@$x_monto_5) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>integrante 6</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_integrante_6" id="x_integrante_6" value="<?php echo htmlspecialchars(@$x_integrante_6) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>monto 6</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_monto_6" id="x_monto_6" size="30" value="<?php echo htmlspecialchars(@$x_monto_6) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>integrante 7</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_integrante_7" id="x_integrante_7" value="<?php echo htmlspecialchars(@$x_integrante_7) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>monto 7</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_monto_7" id="x_monto_7" size="30" value="<?php echo htmlspecialchars(@$x_monto_7) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>integrante 8</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_integrante_8" id="x_integrante_8" value="<?php echo htmlspecialchars(@$x_integrante_8) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>monto 8</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_monto_8" id="x_monto_8" size="30" value="<?php echo htmlspecialchars(@$x_monto_8) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>integrante 9</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_integrante_9" id="x_integrante_9" value="<?php echo htmlspecialchars(@$x_integrante_9) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>monto 9</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_monto_9" id="x_monto_9" size="30" value="<?php echo htmlspecialchars(@$x_monto_9) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>integrante 10</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_integrante_10" id="x_integrante_10" value="<?php echo htmlspecialchars(@$x_integrante_10) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>monto 10</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_monto_10" id="x_monto_10" size="30" value="<?php echo htmlspecialchars(@$x_monto_10) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>monto total</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_monto_total" id="x_monto_total" size="30" value="<?php echo htmlspecialchars(@$x_monto_total) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>fecha registro</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_fecha_registro" id="x_fecha_registro" value="<?php echo FormatDateTime(@$x_fecha_registro,5); ?>">
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
	global $HTTP_SESSION_VARS;
	$sKeyWrk = "" . addslashes($sKey) . "";
	$sSql = "SELECT * FROM `creditosolidario`";
	$sSql .= " WHERE `creditoSolidario_id` = " . $sKeyWrk;
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
		$GLOBALS["x_creditoSolidario_id"] = $row["creditoSolidario_id"];
		$GLOBALS["x_nombre_grupo"] = $row["nombre_grupo"];
		$GLOBALS["x_promotor"] = $row["promotor"];
		$GLOBALS["x_representante_sugerido"] = $row["representante_sugerido"];
		$GLOBALS["x_tesorero"] = $row["tesorero"];
		$GLOBALS["x_numero_integrantes"] = $row["numero_integrantes"];
		$GLOBALS["x_integrante_1"] = $row["integrante_1"];
		$GLOBALS["x_monto_1"] = $row["monto_1"];
		$GLOBALS["x_integrante_2"] = $row["integrante_2"];
		$GLOBALS["x_monto_2"] = $row["monto_2"];
		$GLOBALS["x_integrante_3"] = $row["integrante_3"];
		$GLOBALS["x_monto_3"] = $row["monto_3"];
		$GLOBALS["x_integrante_4"] = $row["integrante_4"];
		$GLOBALS["x_monto_4"] = $row["monto_4"];
		$GLOBALS["x_integrante_5"] = $row["integrante_5"];
		$GLOBALS["x_monto_5"] = $row["monto_5"];
		$GLOBALS["x_integrante_6"] = $row["integrante_6"];
		$GLOBALS["x_monto_6"] = $row["monto_6"];
		$GLOBALS["x_integrante_7"] = $row["integrante_7"];
		$GLOBALS["x_monto_7"] = $row["monto_7"];
		$GLOBALS["x_integrante_8"] = $row["integrante_8"];
		$GLOBALS["x_monto_8"] = $row["monto_8"];
		$GLOBALS["x_integrante_9"] = $row["integrante_9"];
		$GLOBALS["x_monto_9"] = $row["monto_9"];
		$GLOBALS["x_integrante_10"] = $row["integrante_10"];
		$GLOBALS["x_monto_10"] = $row["monto_10"];
		$GLOBALS["x_monto_total"] = $row["monto_total"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
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
	global $HTTP_SESSION_VARS;
	global $HTTP_POST_VARS;
	global $HTTP_POST_FILES;
	global $HTTP_ENV_VARS;

	// Open record
	$sKeyWrk = "" . addslashes($sKey) . "";
	$sSql = "SELECT * FROM `creditosolidario`";
	$sSql .= " WHERE `creditoSolidario_id` = " . $sKeyWrk;
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
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_grupo"]) : $GLOBALS["x_nombre_grupo"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`nombre_grupo`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_promotor"]) : $GLOBALS["x_promotor"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`promotor`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_representante_sugerido"]) : $GLOBALS["x_representante_sugerido"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`representante_sugerido`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tesorero"]) : $GLOBALS["x_tesorero"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`tesorero`"] = $theValue;
		$theValue = ($GLOBALS["x_numero_integrantes"] != "") ? intval($GLOBALS["x_numero_integrantes"]) : "NULL";
		$fieldList["`numero_integrantes`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_integrante_1"]) : $GLOBALS["x_integrante_1"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`integrante_1`"] = $theValue;
		$theValue = ($GLOBALS["x_monto_1"] != "") ? " '" . $GLOBALS["x_monto_1"] . "'" : "NULL";
		$fieldList["`monto_1`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_integrante_2"]) : $GLOBALS["x_integrante_2"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`integrante_2`"] = $theValue;
		$theValue = ($GLOBALS["x_monto_2"] != "") ? " '" . $GLOBALS["x_monto_2"] . "'" : "NULL";
		$fieldList["`monto_2`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_integrante_3"]) : $GLOBALS["x_integrante_3"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`integrante_3`"] = $theValue;
		$theValue = ($GLOBALS["x_monto_3"] != "") ? " '" . $GLOBALS["x_monto_3"] . "'" : "NULL";
		$fieldList["`monto_3`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_integrante_4"]) : $GLOBALS["x_integrante_4"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`integrante_4`"] = $theValue;
		$theValue = ($GLOBALS["x_monto_4"] != "") ? " '" . $GLOBALS["x_monto_4"] . "'" : "NULL";
		$fieldList["`monto_4`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_integrante_5"]) : $GLOBALS["x_integrante_5"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`integrante_5`"] = $theValue;
		$theValue = ($GLOBALS["x_monto_5"] != "") ? " '" . $GLOBALS["x_monto_5"] . "'" : "NULL";
		$fieldList["`monto_5`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_integrante_6"]) : $GLOBALS["x_integrante_6"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`integrante_6`"] = $theValue;
		$theValue = ($GLOBALS["x_monto_6"] != "") ? " '" . $GLOBALS["x_monto_6"] . "'" : "NULL";
		$fieldList["`monto_6`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_integrante_7"]) : $GLOBALS["x_integrante_7"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`integrante_7`"] = $theValue;
		$theValue = ($GLOBALS["x_monto_7"] != "") ? " '" . $GLOBALS["x_monto_7"] . "'" : "NULL";
		$fieldList["`monto_7`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_integrante_8"]) : $GLOBALS["x_integrante_8"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`integrante_8`"] = $theValue;
		$theValue = ($GLOBALS["x_monto_8"] != "") ? " '" . $GLOBALS["x_monto_8"] . "'" : "NULL";
		$fieldList["`monto_8`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_integrante_9"]) : $GLOBALS["x_integrante_9"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`integrante_9`"] = $theValue;
		$theValue = ($GLOBALS["x_monto_9"] != "") ? " '" . $GLOBALS["x_monto_9"] . "'" : "NULL";
		$fieldList["`monto_9`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_integrante_10"]) : $GLOBALS["x_integrante_10"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`integrante_10`"] = $theValue;
		$theValue = ($GLOBALS["x_monto_10"] != "") ? " '" . $GLOBALS["x_monto_10"] . "'" : "NULL";
		$fieldList["`monto_10`"] = $theValue;
		$theValue = ($GLOBALS["x_monto_total"] != "") ? " '" . $GLOBALS["x_monto_total"] . "'" : "NULL";
		$fieldList["`monto_total`"] = $theValue;
		$theValue = ($GLOBALS["x_fecha_registro"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_registro"]) . "'" : "NULL";
		$fieldList["`fecha_registro`"] = $theValue;

		// update
		$sSql = "UPDATE `creditosolidario` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE `creditoSolidario_id` =". $sKeyWrk;
		phpmkr_query($sSql,$conn);
		$EditData = true; // Update Successful
	}
	return $EditData;
}
?>
