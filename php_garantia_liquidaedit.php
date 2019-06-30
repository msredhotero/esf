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
$x_garantia_liquida_id = Null;
$x_credito_id = Null;
$x_monto = Null;
$x_status = Null;
$x_fecha = Null;
$x_fecha_modificacion = Null;
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
	$x_garantia_liquida_id = @$_POST["x_garantia_liquida_id"];
	$x_credito_id = @$_POST["x_credito_id"];
	$x_monto = @$_POST["x_monto"];
	$x_status = @$_POST["x_status"];
	$x_fecha = @$_POST["x_fecha"];
	$x_fecha_modificacion = @$_POST["x_fecha_modificacion"];
}
if (($sKey == "") || ((is_null($sKey)))) {
	ob_end_clean();
	header("Location: php_garantia_liquidalist.php");
}
$conn = phpmkr_db_connect(HOST,USER,PASS,DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($sKey,$conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No Record Found for Key = " . $sKey;
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_garantia_liquidalist.php");
		}
		break;
	case "U": // Update
		if (EditData($sKey,$conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "Update Record Successful for Key = " . $sKey;
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_garantia_liquidalist.php");
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
if (EW_this.x_credito_id && !EW_hasValue(EW_this.x_credito_id, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_credito_id, "TEXT", "Please enter required field - credito id"))
		return false;
}
if (EW_this.x_credito_id && !EW_checkinteger(EW_this.x_credito_id.value)) {
	if (!EW_onError(EW_this, EW_this.x_credito_id, "TEXT", "Incorrect integer - credito id"))
		return false; 
}
if (EW_this.x_monto && !EW_checknumber(EW_this.x_monto.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto, "TEXT", "Incorrect floating point number - monto"))
		return false; 
}
if (EW_this.x_status && !EW_checkinteger(EW_this.x_status.value)) {
	if (!EW_onError(EW_this, EW_this.x_status, "TEXT", "Incorrect integer - status"))
		return false; 
}


return true;
}

//-->
</script>
<p><span class="phpmaker">Editar Garant&iacute;a L&iacute;quida<br><br>
<a href="php_garantia_liquidalist.php">Regresar a la lista</a></span></p>
<form name="garantia_liquidaedit" id="garantia_liquidaedit" action="php_garantia_liquidaedit.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<input type="hidden" name="key" value="<?php echo htmlspecialchars($sKey); ?>">
<table class="ewTable">
	<tr>
		<td class="ewTableHeader"><span>garantia liquida id</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_garantia_liquida_id; ?><input type="hidden" name="x_garantia_liquida_id" value="<?php echo $x_garantia_liquida_id; ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>credito id</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_credito_id" id="x_credito_id" size="30" value="<?php echo htmlspecialchars(@$x_credito_id) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>monto</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_monto" id="x_monto" size="30" value="<?php echo htmlspecialchars(@$x_monto) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>status</span></td>
		<td class="ewTableAltRow"><span>

<?php
$x_medio_pago_idList = "<select name=\"x_status\">";
$x_medio_pago_idList .= "<option value=''>Seleccione</option>";
if(($_SESSION["php_project_esf_status_UserRolID"] == 12)){
	$sSqlWrk = "SELECT `garantia_liquida_status_id`, `descripcion` FROM `garantia_liquida_status` where garantia_liquida_status_id in (7)";
	}else if(($_SESSION["php_project_esf_status_UserRolID"] == 4)){
		$sSqlWrk = "SELECT `garantia_liquida_status_id`, `descripcion` FROM `garantia_liquida_status` where garantia_liquida_status_id > 0";
		}else{
			$sSqlWrk = "SELECT `garantia_liquida_status_id`, `descripcion` FROM `garantia_liquida_status`";
			}


$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_medio_pago_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["garantia_liquida_status_id"] == @$x_status) {
			$x_medio_pago_idList .= "' selected";
		}
		$x_medio_pago_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_medio_pago_idList .= "</select>";
echo $x_medio_pago_idList;
?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>fecha</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_fecha" id="x_fecha" value="<?php echo FormatDateTime(@$x_fecha,7); ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>fecha modificacion</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_fecha_modificacion1" id="x_fecha_modificacion1" value="<?php echo FormatDateTime(@$x_fecha_modificacion,5); ?>" disabled="disabled">
<input type="hidden" name="x_fecha_modificacion" value="<?php echo FormatDateTime(@$x_fecha_modificacion,7); ?>" />
</span></td>
	</tr>
</table>
<p>
<input type="submit" name="Action" value="EDITAR">
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
	$sSql = "SELECT * FROM `garantia_liquida`";
	$sSql .= " WHERE `garantia_liquida_id` = " . $sKeyWrk;
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
		$GLOBALS["x_garantia_liquida_id"] = $row["garantia_liquida_id"];
		$GLOBALS["x_credito_id"] = $row["credito_id"];
		$GLOBALS["x_monto"] = $row["monto"];
		$GLOBALS["x_status"] = $row["status"];
		$GLOBALS["x_fecha"] = $row["fecha"];
		$GLOBALS["x_fecha_modificacion"] = $row["fecha_modificacion"];
		if (empty($row["fecha_modificacion"])){
				$GLOBALS["x_fecha_modificacion"] = date("Y-m-d");
			}
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
	$sSql = "SELECT * FROM `garantia_liquida`";
	$sSql .= " WHERE `garantia_liquida_id` = " . $sKeyWrk;
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
		$theValue = ($GLOBALS["x_credito_id"] != "") ? intval($GLOBALS["x_credito_id"]) : "NULL";
		$fieldList["`credito_id`"] = $theValue;
		$theValue = ($GLOBALS["x_monto"] != "") ? " '" . doubleval($GLOBALS["x_monto"]) . "'" : "NULL";
		$fieldList["`monto`"] = $theValue;
		$theValue = ($GLOBALS["x_status"] != "") ? intval($GLOBALS["x_status"]) : "NULL";
		$fieldList["`status`"] = $theValue;
		$theValue = ($GLOBALS["x_fecha"] != "") ? " '" .ConvertDateToMysqlFormat($GLOBALS["x_fecha"]) . "'" : "NULL";
		$fieldList["`fecha`"] = $theValue;		
		$theValue = ($GLOBALS["x_fecha_modificacion"] != "") ? " '" .ConvertDateToMysqlFormat($GLOBALS["x_fecha_modificacion"]) . "'" : "NULL";
		$fieldList["`fecha_modificacion`"] = $theValue;

		// update
		$sSql = "UPDATE `garantia_liquida` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE `garantia_liquida_id` =". $sKeyWrk;
		#echo "sql :".$sSql."<br>";
		#die();
		phpmkr_query($sSql,$conn);
		$EditData = true; // Update Successful
	}
	return $EditData;
}
?>
