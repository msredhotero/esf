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
$x_recibo_qr_id = Null;
$x_promotor_id = Null;
$x_fecha = Null;
$x_hora = Null;
$x_cliente = Null;
$x_credito = Null;
$x_monto = Null;
$x_comentario = Null;
$x_folio = Null;
$x_confirma_cliente = Null;
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
	$x_recibo_qr_id = @$_POST["x_recibo_qr_id"];
	$x_promotor_id = @$_POST["x_promotor_id"];
	$x_fecha = @$_POST["x_fecha"];
	$x_hora = @$_POST["x_hora"];
	$x_cliente = @$_POST["x_cliente"];
	$x_credito = @$_POST["x_credito"];
	$x_monto = @$_POST["x_monto"];
	$x_comentario = @$_POST["x_comentario"];
	$x_folio = @$_POST["x_folio"];
	$x_confirma_cliente = @$_POST["x_confirma_cliente"];
}
if (($sKey == "") || ((is_null($sKey)))) {
	ob_end_clean();
	header("Location: php_recibo_qrlist.php");
}
$conn = phpmkr_db_connect(HOST,USER,PASS,DB);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($sKey,$conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No Record Found for Key = " . $sKey;
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_recibo_qrlist.php");
		}
		break;
	case "U": // Update
		if (EditData($sKey,$conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "Update Record Successful for Key = " . $sKey;
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_recibo_qrlist.php");
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
if (EW_this.x_promotor_id && !EW_checkinteger(EW_this.x_promotor_id.value)) {
	if (!EW_onError(EW_this, EW_this.x_promotor_id, "TEXT", "Incorrect integer - promotor id"))
		return false; 
}
if (EW_this.x_fecha && !EW_checkdate(EW_this.x_fecha.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha, "TEXT", "Incorrect date, format = yyyy/mm/dd - fecha"))
		return false; 
}
if (EW_this.x_monto && !EW_checknumber(EW_this.x_monto.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto, "TEXT", "Incorrect floating point number - monto"))
		return false; 
}
if (EW_this.x_folio && !EW_hasValue(EW_this.x_folio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_folio, "TEXT", "Please enter required field - folio"))
		return false;
}
if (EW_this.x_folio && !EW_checkinteger(EW_this.x_folio.value)) {
	if (!EW_onError(EW_this, EW_this.x_folio, "TEXT", "Incorrect integer - folio"))
		return false; 
}
if (EW_this.x_confirma_cliente && !EW_hasValue(EW_this.x_confirma_cliente, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_confirma_cliente, "TEXT", "Please enter required field - confirma cliente"))
		return false;
}
if (EW_this.x_confirma_cliente && !EW_checkinteger(EW_this.x_confirma_cliente.value)) {
	if (!EW_onError(EW_this, EW_this.x_confirma_cliente, "TEXT", "Incorrect integer - confirma cliente"))
		return false; 
}
return true;
}

//-->
</script>
<p><span class="phpmaker">Edit TABLE: recibo qr<br><br><a href="php_recibo_qrlist.php">Back to List</a></span></p>
<form name="recibo_qredit" id="recibo_qredit" action="php_recibo_qredit.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<input type="hidden" name="key" value="<?php echo htmlspecialchars($sKey); ?>">
<table class="ewTable">
	<tr>
		<td class="ewTableHeader"><span>recibo qr id</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_recibo_qr_id; ?><input type="hidden" name="x_recibo_qr_id" value="<?php echo $x_recibo_qr_id; ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>promotor id</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_promotor_id" id="x_promotor_id" size="30" value="<?php echo htmlspecialchars(@$x_promotor_id) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>fecha</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_fecha" id="x_fecha" value="<?php echo FormatDateTime(@$x_fecha,5); ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>hora</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_hora" id="x_hora" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_hora) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>cliente</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_cliente" id="x_cliente" value="<?php echo htmlspecialchars(@$x_cliente) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>credito</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_credito" id="x_credito" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_credito) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>monto</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_monto" id="x_monto" size="30" value="<?php echo htmlspecialchars(@$x_monto) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>comentario</span></td>
		<td class="ewTableAltRow"><span>
<textarea cols="35" rows="4" id="x_comentario" name="x_comentario"><?php echo @$x_comentario; ?></textarea>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>folio</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_folio" id="x_folio" size="30" value="<?php echo htmlspecialchars(@$x_folio) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>confirma cliente</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_confirma_cliente" id="x_confirma_cliente" size="30" value="<?php echo htmlspecialchars(@$x_confirma_cliente) ?>">
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
	$sSql = "SELECT * FROM `recibo_qr`";
	$sSql .= " WHERE `recibo_qr_id` = " . $sKeyWrk;
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
		$GLOBALS["x_recibo_qr_id"] = $row["recibo_qr_id"];
		$GLOBALS["x_promotor_id"] = $row["promotor_id"];
		$GLOBALS["x_fecha"] = $row["fecha"];
		$GLOBALS["x_hora"] = $row["hora"];
		$GLOBALS["x_cliente"] = $row["cliente"];
		$GLOBALS["x_credito"] = $row["credito"];
		$GLOBALS["x_monto"] = $row["monto"];
		$GLOBALS["x_comentario"] = $row["comentario"];
		$GLOBALS["x_folio"] = $row["folio"];
		$GLOBALS["x_confirma_cliente"] = $row["confirma_cliente"];
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
	$sSql = "SELECT * FROM `recibo_qr`";
	$sSql .= " WHERE `recibo_qr_id` = " . $sKeyWrk;
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
		$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
		$fieldList["`promotor_id`"] = $theValue;
		$theValue = ($GLOBALS["x_fecha"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha"]) . "'" : "NULL";
		$fieldList["`fecha`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_hora"]) : $GLOBALS["x_hora"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`hora`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_cliente"]) : $GLOBALS["x_cliente"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`cliente`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_credito"]) : $GLOBALS["x_credito"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`credito`"] = $theValue;
		$theValue = ($GLOBALS["x_monto"] != "") ? " '" . doubleval($GLOBALS["x_monto"]) . "'" : "NULL";
		$fieldList["`monto`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_comentario"]) : $GLOBALS["x_comentario"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`comentario`"] = $theValue;
		$theValue = ($GLOBALS["x_folio"] != "") ? intval($GLOBALS["x_folio"]) : "NULL";
		$fieldList["`folio`"] = $theValue;
		$theValue = ($GLOBALS["x_confirma_cliente"] != "") ? intval($GLOBALS["x_confirma_cliente"]) : "NULL";
		$fieldList["`confirma_cliente`"] = $theValue;

		// update
		$sSql = "UPDATE `recibo_qr` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE `recibo_qr_id` =". $sKeyWrk;
		phpmkr_query($sSql,$conn);
		$EditData = true; // Update Successful
	}
	return $EditData;
}
?>
