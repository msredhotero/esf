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
$x_fondeo_credito_id = Null; 
$ox_fondeo_credito_id = Null;
$x_fondeo_credito_tipo_id = Null; 
$ox_fondeo_credito_tipo_id = Null;
$x_solicitud_id = Null; 
$ox_solicitud_id = Null;
$x_fondeo_credito_status_id = Null; 
$ox_fondeo_credito_status_id = Null;
$x_fecha_otrogamiento = Null; 
$ox_fecha_otrogamiento = Null;
$x_importe = Null; 
$ox_importe = Null;
$x_tasa = Null; 
$ox_tasa = Null;
$x_plazo = Null; 
$ox_plazo = Null;
$x_fecha_vencimiento = Null; 
$ox_fecha_vencimiento = Null;
$x_tasa_moratoria = Null; 
$ox_tasa_moratoria = Null;
$x_medio_pago_id = Null; 
$ox_medio_pago_id = Null;
$x_referencia_pago = Null; 
$ox_referencia_pago = Null;
$x_num_pagos = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
$x_fondeo_credito_id = @$_GET["credito_id"];
if (empty($x_fondeo_credito_id)) {
	$bCopy = false;
}

	
// Get action
$sAction = @$_POST["a_add"];
if (($sAction == "") || ((is_null($sAction)))) {
	if ($bCopy) {
		$sAction = "C"; // Copy record
	}else{
		$sAction = "S"; // Display blank record
	}
}else{

	// Get fields from form
	$x_fondeo_credito_id = @$_POST["x_fondeo_credito_id"];
	$x_fondeo_credito_tipo_id = @$_POST["x_fondeo_credito_tipo_id"];
/*
	$x_solicitud_id = @$_GET["x_solicitud_id"];
	if (empty($x_fondeo_credito_id)) {
		$x_solicitud_id = @$_POST["x_solicitud_id"];
	}
*/	

	
	$x_fondeo_credito_status_id = 1;
	$x_fondeo_credito_num = $_POST["x_fondeo_credito_num"];	
	$x_fondeo_empresa_id = $_POST["x_fondeo_empresa_id"];		
	$x_fecha_otrogamiento = @$_POST["x_fecha_otrogamiento"];
	$x_importe = @$_POST["x_importe"];
	$x_tasa = @$_POST["x_tasa"];
	$x_iva = @$_POST["x_iva"];	
	$x_plazo = @$_POST["x_plazo_id"];
	$x_forma_pago_id = @$_POST["x_forma_pago_id"];	
	$x_fecha_vencimiento = @$_POST["x_fecha_vencimiento"];
	$x_tasa_moratoria = @$_POST["x_tasa_moratoria"];
	$x_medio_pago_id = @$_POST["x_medio_pago_id"];
	$x_banco_id = @$_POST["x_banco_id"];	
	$x_referencia_pago = @$_POST["x_referencia_pago"];
	$x_num_pagos = @$_POST["x_num_pagos"];	
	$x_tdp = @$_POST["x_tdp"];		
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron los datos.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_fondeo_creditolist.php");
			exit();
		}
		break;
	case "A": // Add
	
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "Los datos han sido registrados.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_fondeo_creditolist.php");
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
function solicitud_data(){
	EW_this = document.creditoadd;
	EW_this.a_add.value = "S";
	EW_this.submit();
}


function EW_checkMyForm() {
EW_this = document.creditoadd;
validada = true;
if (validada == true && EW_this.x_fondeo_credito_num && !EW_hasValue(EW_this.x_fondeo_credito_num, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fondeo_credito_num, "TEXT", "El numero de credito es requerido."))
		validada = false;
}
if (validada == true && EW_this.x_fondeo_empresa_id && !EW_hasValue(EW_this.x_fondeo_empresa_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_fondeo_empresa_id, "SELECT", "La empresa es requerida."))
		validada = false;
}

if (validada == true && EW_this.x_fecha_otrogamiento && !EW_hasValue(EW_this.x_fecha_otrogamiento, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_otrogamiento, "TEXT", "La fecha de otorgamiento es requerida."))
		validada = false;
}
if (validada == true && EW_this.x_fecha_otrogamiento && !EW_checkeurodate(EW_this.x_fecha_otrogamiento.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_otrogamiento, "TEXT", "La fecha de otorgamiento es requerida."))
		validada = false;
}
if (validada == true && EW_this.x_num_pagos && !EW_hasValue(EW_this.x_num_pagos, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_num_pagos, "TEXT", "El numero de pagos es requerido."))
		validada = false;
}
if (validada == true && EW_this.x_num_pagos && !EW_checkinteger(EW_this.x_num_pagos.value)) {
	if (!EW_onError(EW_this, EW_this.x_num_pagos, "TEXT", "El numero de pagos es incorrecto."))
		validada = false;
}
if (validada == true && EW_this.x_tasa && !EW_hasValue(EW_this.x_tasa, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tasa, "TEXT", "La tasa es requerida."))
		validada = false;
}
if (validada == true && EW_this.x_tasa && !EW_checknumber(EW_this.x_tasa.value)) {
	if (!EW_onError(EW_this, EW_this.x_tasa, "TEXT", "La tasa es requerida."))
		validada = false;
}
if (validada == true && EW_this.x_tasa_moratoria && !EW_hasValue(EW_this.x_tasa_moratoria, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tasa_moratoria, "TEXT", "La tasa moratoria es requerida."))
		validada = false;
}
if (validada == true && EW_this.x_tasa_moratoria && !EW_checknumber(EW_this.x_tasa_moratoria.value)) {
	if (!EW_onError(EW_this, EW_this.x_tasa_moratoria, "TEXT", "La tasa moratoria es requerida."))
		validada = false;
}
if (validada == true && EW_this.x_medio_pago_id && !EW_hasValue(EW_this.x_medio_pago_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_medio_pago_id, "SELECT", "El medio de pago es requerido."))
		validada = false;
}
if (validada == true && EW_this.x_banco_id && !EW_hasValue(EW_this.x_banco_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_banco_id, "SELECT", "EL banco y cuenta son requeridos."))
		validada = false;
}

if (validada == true && EW_this.x_referencia_pago && !EW_hasValue(EW_this.x_referencia_pago, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_referencia_pago, "TEXT", "La refencia es requerida."))
		validada = false;
}

if (validada == true){
	EW_this.a_add.value = "A";
	EW_this.submit();
}

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
<p><span class="phpmaker">FONDOS - CREDITOS<br>
  <br>
    <a href="php_fondeo_creditolist.php">Regresar al listado</a></span></p>
<form name="creditoadd" id="creditoadd" action="php_fondeo_creditoadd.php" method="post">
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
	  <td class="ewTableHeaderThin">Empresa</td>
	  <td class="ewTableAltRow"><?php if (!(!is_null($x_fondeo_empresa_id)) || ($x_fondeo_empresa_id == "")) { $x_fondeo_empresa_id = 0;} // Set default value ?>
        <?php
$x_medio_pago_idList = "<select name=\"x_fondeo_empresa_id\">";
$x_medio_pago_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT fondeo_empresa_id, nombre FROM fondeo_empresa";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_medio_pago_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["fondeo_empresa_id"] == @$x_fondeo_empresa_id) {
			$x_medio_pago_idList .= "' selected";
		}
		$x_medio_pago_idList .= ">" . $datawrk["nombre"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_medio_pago_idList .= "</select>";
echo $x_medio_pago_idList;
?></td>
	  </tr>
	<tr>
	  <td width="161" class="ewTableHeaderThin">Cr&eacute;dito Num.:</td>
	  <td width="827" class="ewTableAltRow">
	    <?php
$sSqlWrk = "SELECT max(credito_num)+1 as credito_num FROM fondeo_credito ";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
$x_fondeo_credito_num = $datawrk["credito_num"];
@phpmkr_free_result($rswrk);
if(empty($x_fondeo_credito_num)){
$x_fondeo_credito_num = 1;	
}
?>
	    <input name="x_fondeo_credito_num" type="hidden" id="x_fondeo_credito_num" value="<?php echo $x_fondeo_credito_num; ?>" />
	    <?php echo $x_fondeo_credito_num; ?>
	    </td>
	  </tr>
	<tr >
	  <td class="ewTableHeaderThin"><span>Fecha de otrogamiento</span></td>
	  <td class="ewTableAltRow"><span>
  <input type="text" name="x_fecha_otrogamiento" id="x_fecha_otrogamiento" value="<?php echo FormatDateTime(@$x_fecha_otrogamiento,7); ?>">
  &nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_otrogamiento" alt="Pick a Date" style="cursor:pointer;cursor:hand;">
  <script type="text/javascript">
Calendar.setup(
{
inputField : "x_fecha_otrogamiento", // ID of the input field
ifFormat : "%d/%m/%Y", // the date format
button : "cx_fecha_otrogamiento" // ID of the button
}
);
</script>
  </span></td>
	  </tr>
	<tr >
		<td class="ewTableHeaderThin"><span>Importe</span></td>
		<td class="ewTableAltRow"><span>
		
<input type="text" name="x_importe" id="x_importe" value="<?php echo @$x_importe_solicitado; ?>">
</span></td>
	</tr>
	
	<tr >
		<td class="ewTableHeaderThin"><span>Plazo</span></td>
		<td class="ewTableAltRow"><span>
<?php if (!(!is_null($x_plazo_id)) || ($x_plazo_id == "")) { $x_medio_pago_id = 0;} // Set default value ?>
<?php
$x_medio_pago_idList = "<select name=\"x_plazo_id\">";
$x_medio_pago_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT `plazo_id`, `descripcion` FROM `plazo`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_medio_pago_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["plazo_id"] == @$x_plazo_id) {
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
	
	<tr >
	  <td class="ewTableHeaderThin">Forma de Pago </td>
	  <td class="ewTableAltRow">
<?php if (!(!is_null($x_forma_pago_id)) || ($x_forma_pago_id == "")) { $x_medio_pago_id = 0;} // Set default value ?>
<?php
$x_medio_pago_idList = "<select name=\"x_forma_pago_id\">";
$x_medio_pago_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT `forma_pago_id`, `descripcion` FROM `forma_pago`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_medio_pago_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["forma_pago_id"] == @$x_forma_pago_id) {
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
	  
</td>
	  </tr>
	<tr >
	  <td class="ewTableHeaderThin">Numero de Pagos </td>
	  <td class="ewTableAltRow">
	  <input name="x_num_pagos" type="text" id="x_num_pagos" value="<?php echo @$x_num_pagos; ?>" size="8" maxlength="5" />	  </td>
	  </tr>
	<tr >
	  <td class="ewTableHeaderThin">Tasa</td>
	  <td class="ewTableAltRow"><input name="x_tasa" type="text" id="x_tasa" value="<?php echo htmlspecialchars(@$x_tasa) ?>" size="8" maxlength="5" /> 
	    % </td>
	  </tr>
	<tr >
	  <td class="ewTableHeaderThin">IVA</td>
	  <td class="ewTableAltRow">
      <input type="radio" name="x_iva" value="1" <?php if($x_iva == 1){ echo "checked";} ?> />&nbsp;SI
      &nbsp;&nbsp;
      <input type="radio" name="x_iva" value="2" <?php if($x_iva == 2){ echo "checked";} ?>/>&nbsp;NO
	  </td>
	  </tr>
      
	<tr >
	  <td class="ewTableHeaderThin"><span>Tasa moratoria</span></td>
	  <td class="ewTableAltRow"><span>
  <input name="x_tasa_moratoria" type="text" id="x_tasa_moratoria" value="<?php echo htmlspecialchars(@$x_tasa_moratoria) ?>" size="10" maxlength="10"> 
	    %
  </span></td>
	  </tr>
	<tr >
	  <td class="ewTableHeaderThin"><span>Referencia</span></td>
	  <td class="ewTableAltRow"><span>
	    <input type="text" name="x_referencia_pago" id="x_referencia_pago" size="30" maxlength="50" value="<?php echo htmlspecialchars(@$x_referencia_pago) ?>">
	    </span></td>
	  </tr>
	</table>
<p>
<div id="aceptar" >
<input type="button" name="Action" value="Activar" onclick="EW_checkMyForm();">
</div>
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
	global $x_fondeo_credito_id;
	$sSql = "SELECT * FROM `credito`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_fondeo_credito_id) : $x_fondeo_credito_id;
	$sWhere .= "(`credito_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_fondeo_credito_id"] = $row["credito_id"];
		$GLOBALS["x_fondeo_credito_num"] = $row["credito_num"];		
		$GLOBALS["x_cliente_num"] = $row["cliente_num"];				
		$GLOBALS["x_fondeo_credito_tipo_id"] = $row["credito_tipo_id"];
		$GLOBALS["x_solicitud_id"] = $row["solicitud_id"];
		$GLOBALS["x_fondeo_credito_status_id"] = $row["credito_status_id"];
		$GLOBALS["x_fecha_otrogamiento"] = $row["fecha_otrogamiento"];
		$GLOBALS["x_importe"] = $row["importe"];
		$GLOBALS["x_tasa"] = $row["tasa"];
		$GLOBALS["x_plazo"] = $row["plazo"];
		$GLOBALS["x_fecha_vencimiento"] = $row["fecha_vencimiento"];
		$GLOBALS["x_tasa_moratoria"] = $row["tasa_moratoria"];
		$GLOBALS["x_medio_pago_id"] = $row["medio_pago_id"];
		$GLOBALS["x_referencia_pago"] = $row["referencia_pago"];
		$GLOBALS["x_num_pagos"] = $row["num_pagos"];		
		$GLOBALS["x_tdp"] = $row["tarjeta_num"];				
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
	global $x_fondeo_credito_id;
	$sSql = "SELECT * FROM `credito`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";

	// Check for duplicate key
	$bCheckKey = true;
	$sWhereChk = $sWhere;
	if ((@$x_fondeo_credito_id == "") || (is_null($x_fondeo_credito_id))) {
		$bCheckKey = false;
	} else {
		if ($sWhereChk <> "") { $sWhereChk .= " AND "; }
		$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_fondeo_credito_id) : $x_fondeo_credito_id;			
		$sWhereChk .= "(`credito_id` = " . addslashes($sTmp) . ")";
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

	phpmkr_query('START TRANSACTION;', $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: BEGIN TRAN');





	$theValue = ($GLOBALS["x_fondeo_empresa_id"] != "") ? " '" . $GLOBALS["x_fondeo_empresa_id"] . "'" : "0";
	$fieldList["`fondeo_empresa_id`"] = $theValue;


	$theValue = ($GLOBALS["x_fondeo_credito_num"] != "") ? " '" . $GLOBALS["x_fondeo_credito_num"] . "'" : "0";
	$fieldList["`credito_num`"] = $theValue;


	// Field credito_tipo_id
	$theValue = ($GLOBALS["x_fondeo_credito_tipo_id"] != "") ? intval($GLOBALS["x_fondeo_credito_tipo_id"]) : "0";
	$fieldList["`credito_tipo_id`"] = $theValue;


	// Field credito_status_id
	$theValue = ($GLOBALS["x_fondeo_credito_status_id"] != "") ? intval($GLOBALS["x_fondeo_credito_status_id"]) : "1";
	$fieldList["`credito_status_id`"] = $theValue;

	// Field fecha_otrogamiento
	$theValue = ($GLOBALS["x_fecha_otrogamiento"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_otrogamiento"]) . "'" : "Null";
	$fieldList["`fecha_otrogamiento`"] = $theValue;

	// Field importe
	$theValue = ($GLOBALS["x_importe"] != "") ? " '" . doubleval($GLOBALS["x_importe"]) . "'" : "NULL";
	$fieldList["`importe`"] = $theValue;

	// Field plazo
	$theValue = ($GLOBALS["x_forma_pago_id"] != "") ? intval($GLOBALS["x_forma_pago_id"]) : "0";
	$fieldList["`forma_pago_id`"] = $theValue;


	// Field tasa
	$theValue = ($GLOBALS["x_tasa"] != "") ? " '" . doubleval($GLOBALS["x_tasa"]) . "'" : "0";
	$fieldList["`tasa`"] = $theValue;

	$theValue = ($GLOBALS["x_iva"] != "") ? " '" . intval($GLOBALS["x_iva"]) . "'" : "2";
	$fieldList["`iva`"] = $theValue;

	// Field plazo
	$theValue = ($GLOBALS["x_plazo"] != "") ? intval($GLOBALS["x_plazo"]) : "0";
	$fieldList["`plazo_id`"] = $theValue;

	// Field fecha_vencimiento
	$theValue = ($GLOBALS["x_fecha_vencimiento"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_vencimiento"]) . "'" : "Null";
	$fieldList["`fecha_vencimiento`"] = $theValue;

	// Field tasa_moratoria
	$theValue = ($GLOBALS["x_tasa_moratoria"] != "") ? " '" . doubleval($GLOBALS["x_tasa_moratoria"]) . "'" : "0";
	$fieldList["`tasa_moratoria`"] = $theValue;


	// Field referencia_pago
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_pago"]) : $GLOBALS["x_referencia_pago"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`referencia_pago`"] = $theValue;


	// 
	$theValue = ($GLOBALS["x_num_pagos"] != "") ? intval($GLOBALS["x_num_pagos"]) : "0";
	$fieldList["`num_pagos`"] = $theValue;


	// insert into database
	$sSql = "INSERT INTO `fondeo_credito` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	$x_result = phpmkr_query($sSql, $conn);
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
		phpmkr_query('rollback;', $conn);	 
	 	exit();
	}
	$x_fondeo_credito_id = mysql_insert_id();


	phpmkr_query('commit;', $conn);	 
	return true;
}
?>

