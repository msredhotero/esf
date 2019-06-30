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
$x_credito_id = Null; 
$ox_credito_id = Null;
$x_credito_tipo_id = Null; 
$ox_credito_tipo_id = Null;
$x_solicitud_id = Null; 
$ox_solicitud_id = Null;
$x_credito_status_id = Null; 
$ox_credito_status_id = Null;
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
$x_credito_id = @$_GET["credito_id"];
if (empty($x_credito_id)) {
	$bCopy = false;
}

$x_solicitud_id = @$_GET["x_solicitud_id"];
if (empty($x_solicitud_id)) {
	$x_solicitud_id = @$_POST["x_solicitud_id"];
}
if (empty($x_solicitud_id)) {
	$_SESSION["ewmsg"] = "No se especifico una solicitud.";
	ob_end_clean();
	header("Location: php_solicitudlist.php");
	exit();
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
	$x_credito_id = @$_POST["x_credito_id"];
	$x_credito_tipo_id = @$_POST["x_credito_tipo_id"];
/*
	$x_solicitud_id = @$_GET["x_solicitud_id"];
	if (empty($x_credito_id)) {
		$x_solicitud_id = @$_POST["x_solicitud_id"];
	}
*/	

	
	
	
	$x_fondeo_credito_id = $_POST["x_fondeo_credito_id"];
	
	$x_credito_status_id = 1;
	$x_credito_num = $_POST["x_credito_num"];	
	$x_cliente_num = $_POST["x_cliente_num"];		
	$x_fecha_otrogamiento = @$_POST["x_fecha_otrogamiento"];
//	$x_fecha_primerpago = @$_POST["x_fecha_primerpago"];	
	$x_fecha_abonocap = @$_POST["x_fecha_abonocap"];	
	$x_periodos_gracia = @$_POST["x_periodos_gracia"];
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
	$x_tipo_calculo = $_POST["x_tipo_calculo"];
	
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron los datos.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_creditolist.php");
			exit();
		}
		break;
	case "A": // Add
	
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "Los datos han sido registrados.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_creditolist.php");
			exit();
		}
		break;
	case "S": // Add
		$sSql = "SELECT * FROM solicitud where solicitud_id = $x_solicitud_id";
		$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row = phpmkr_fetch_array($rs);
		$GLOBALS["x_credito_tipo_id"] = $row["credito_tipo_id"];
		$GLOBALS["x_importe_solicitado"] = $row["importe_solicitado"];
		$GLOBALS["x_plazo_id"] = $row["plazo_id"];
		$GLOBALS["x_forma_pago_id"] = $row["forma_pago_id"];		
		phpmkr_free_result($rs);		

if($GLOBALS["x_credito_tipo_id"] == 1){
		$sSql = "SELECT cliente_id FROM solicitud_cliente where solicitud_id = $x_solicitud_id";
		$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row = phpmkr_fetch_array($rs);
		$GLOBALS["x_cliente_id"] = $row["cliente_id"];		
		phpmkr_free_result($rs);		

		$sSql = "SELECT credito.tarjeta_num FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id where cliente.cliente_id = $x_cliente_id and not isnull(credito.tarjeta_num) limit 1";
		$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row = phpmkr_fetch_array($rs);
		$GLOBALS["x_tdp"] = $row["tarjeta_num"];
		phpmkr_free_result($rs);		
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
if (validada == true && EW_this.x_credito_num && !EW_hasValue(EW_this.x_credito_num, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_credito_num, "TEXT", "El numero de credito es requerido."))
		validada = false;
}
if (validada == true && EW_this.x_cliente_num && !EW_hasValue(EW_this.x_cliente_num, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_cliente_num, "TEXT", "El numero de cliente es requerido."))
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

/*
if (validada == true && EW_this.x_fecha_primerpago && !EW_hasValue(EW_this.x_fecha_primerpago, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_primerpago, "TEXT", "La fecha del primer pago es requerida."))
		validada = false;
}

if (validada == true && EW_this.x_fecha_primerpago && !EW_checkeurodate(EW_this.x_fecha_primerpago.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_primerpago, "TEXT", "La fecha de primer pago incorrecta."))
		validada = false;
}

*/
/*
if (validada == true && EW_this.x_fecha_abonocap && !EW_hasValue(EW_this.x_fecha_abonocap, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_abonocap, "TEXT", "La fecha de abono a capital es requerida."))
		validada = false;
}
if (validada == true && EW_this.x_fecha_abonocap && !EW_checkeurodate(EW_this.x_fecha_abonocap.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_abonocap, "TEXT", "La fecha de abono a capital es incorrecta."))
		validada = false;
}
*/






if (validada == true && EW_this.x_num_pagos && !EW_hasValue(EW_this.x_num_pagos, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_num_pagos, "TEXT", "El numero de pagos es requerido."))
		validada = false;
}
if (validada == true && EW_this.x_num_pagos && !EW_checkinteger(EW_this.x_num_pagos.value)) {
	if (!EW_onError(EW_this, EW_this.x_num_pagos, "TEXT", "El numero de pagos es incorrecto."))
		validada = false;
}
if (validada == true && EW_this.x_tipo_calculo && !EW_hasValue(EW_this.x_tipo_calculo, "RADIO" )) {
	if (!EW_onError(EW_this, EW_this.x_tipo_calculo, "RADIO", "Indique el tipo de calculo."))
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
/////////////////////////////////************************////////////////////////////////
if (validada == true && EW_this.x_fondeo_credito_id && !EW_hasValue(EW_this.x_fondeo_credito_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_fondeo_credito_id, "SELECT", "Debe seleccionar los fondos."))
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
<p><span class="phpmaker">CREDITOS<br>
  <br>
    <a href="php_solicitudlist.php">listado de créditos</a></span></p>
<form name="creditoadd" id="creditoadd" action="php_creditoadd.php" method="post">
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
	  <td class="ewTableHeaderThin" width="161">Solicitud</td>
	  <td class="ewTableAltRow"><span>
      <input type="hidden" name="x_solicitud_id" value="<?php echo $x_solicitud_id; ?>"  />
        <?php
			echo $x_solicitud_id;
		?>
      </span></td>
	  </tr>
	<tr>
	  <td colspan="2" class="ewTableAltRow">&nbsp;</td>
	  </tr>
	<tr>
	  <td class="ewTableHeaderThin">Fondo:</td>
	  <td class="ewTableAltRow"><?php if (!(!is_null($x_fondeo_credito_id)) || ($x_fondeo_credito_id == "")) { $x_fondeo_credito_id = 0;} // Set default value ?>
	    <?php
$x_medio_pago_idList = "<select name=\"x_fondeo_credito_id\" id=\"x_fondeo_credito_id\">";
$x_medio_pago_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT fondeo_credito_id, credito_num, fondeo_credito.fondeo_empresa_id,  fondeo_empresa.nombre, fondeo_credito.importe FROM fondeo_credito join fondeo_empresa on fondeo_empresa.fondeo_empresa_id = fondeo_credito.fondeo_empresa_id order by fondeo_credito.fondeo_empresa_id ";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		
/*
		$sSqlWrk2 = "SELECT sum(importe) as otorgado FROM credito where credito_id in (select credito_id from fondeo_colocacion join fondeo_credito on fondeo_credito.fondeo_credito_id = fondeo_colocacion.fondeo_credito_id where fondeo_credito.fondeo_credito_id = ".$datawrk["fondeo_credito_id"].") and credito.credito_status_id in (1, 3,4,5)";
		$rswrk2 = phpmkr_query($sSqlWrk2,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk2);
		$datawrk2 = phpmkr_fetch_array($rswrk2);
		$x_fondeo_importe = $datawrk["importe"];		
		$x_fondeo_otorgado = $datawrk2["otorgado"];		
		$x_fondeo_saldo = $datawrk["importe"] - $datawrk2["otorgado"];
		@phpmkr_free_result($rswrk2);
*/

		$x_medio_pago_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["fondeo_credito_id"] == @$x_fondeo_credito_id) {
			$x_medio_pago_idList .= "' selected";
		}


		if(strtoupper($datawrk["nombre"]) == "FONDOS PROPIOS"){
			$x_medio_pago_idList .= ">" . $datawrk["nombre"] . "</option>";
		}else{
//			$x_medio_pago_idList .= ">" . $datawrk["nombre"] . " Credito No.: " . $datawrk["credito_num"] . " Importe: " . FormatNumber($x_fondeo_importe,0,0,0,1) . " Otorgado: ".FormatNumber($x_fondeo_otorgado,0,0,0,1)."</option>";

			$x_medio_pago_idList .= ">" . $datawrk["nombre"] . " Credito No.: " . $datawrk["credito_num"] . " Importe: " . FormatNumber($x_fondeo_importe,0,0,0,1)."</option>";

		}

		
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_medio_pago_idList .= "</select>";
echo $x_medio_pago_idList;
?></td>
	  </tr>
	<tr>
	  <td class="ewTableHeaderThin">Cr&eacute;dito Num.:</td>
	  <td class="ewTableAltRow">
      <?php
$sSqlWrk = "SELECT max(credito_num+0) as credito_num_new FROM credito ";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
$x_credito_num_new = $datawrk["credito_num_new"] + 1;
@phpmkr_free_result($rswrk);
	  ?>
      <input name="x_credito_num" type="text" id="x_credito_num" value="<?php echo $x_credito_num_new; ?>" size="5" maxlength="5" /></td>
	  </tr>
	<tr>
	  <td class="ewTableHeaderThin">Cliente Num.:</td>
	  <td class="ewTableAltRow">

      <?php
	  if(empty($x_cliente_num) || $x_cliente_num == 0){
$sSqlWrk = "SELECT max(cliente_num+0) as cliente_num_new FROM cliente ";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
$x_cliente_num = $datawrk["cliente_num_new"] + 1;
@phpmkr_free_result($rswrk);
	  }
	  ?>
      <input name="x_cliente_num" type="text" id="x_cliente_num" value="<?php echo $x_cliente_num; ?>" size="5" maxlength="5" /></td>
	  </tr>
	<tr>
		<td width="161" class="ewTableHeaderThin"><span>Tipo de Cr&eacute;dito </span></td>
		<td width="827" class="ewTableAltRow"><span>
<?php if (!(!is_null($x_credito_tipo_id)) || ($x_credito_tipo_id == "")) { $x_credito_tipo_id = 0;} // Set default value ?>
<?php
$sSqlWrk = "SELECT `credito_tipo_id`, `descripcion` FROM `credito_tipo` Where credito_tipo_id = $x_credito_tipo_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
echo $datawrk["descripcion"];
@phpmkr_free_result($rswrk);
?>
<input type="hidden" name="x_credito_tipo_id" value="<?php echo $x_credito_tipo_id; ?>" />
</span></td>
	</tr>
	<tr>
	  <td class="ewTableHeaderThin"><span>Fecha de otrogamiento</span></td>
	  <td class="ewTableAltRow"><span>
  <input type="text" name="x_fecha_otrogamiento" id="x_fecha_otrogamiento" value="<?php echo FormatDateTime(@$x_fecha_otrogamiento,7); ?>">
  &nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_otrogamiento" alt="Calendario" style="cursor:pointer;cursor:hand;">
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
	<tr>
	  <td class="ewTableHeaderThin">Periodos de gracia</td>
	  <td class="ewTableAltRow">
  <span>
  <input type="text" name="x_periodos_gracia" value="0" maxlength="5" size="5"  />
  </span>      
	    gracia  solo intereses</td>
	  </tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Importe</span></td>
		<td class="ewTableAltRow"><span>
		<?php echo "$".FormatNumber(@$x_importe_solicitado,2,0,0,1); ?>
<input type="hidden" name="x_importe" id="x_importe" value="<?php echo @$x_importe_solicitado; ?>">
</span></td>
	</tr>
	
	<tr>
		<td class="ewTableHeaderThin"><span>Plazo</span></td>
		<td class="ewTableAltRow"><span>
		<?php 
		$sSqlWrk = "SELECT descripcion FROM plazo where plazo_id = $x_plazo_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo $datawrk["descripcion"];
		@phpmkr_free_result($rswrk);
		?>		
<input type="hidden" name="x_plazo_id" id="x_plazo_id" value="<?php echo @$x_plazo_id; ?>">
</span></td>
	</tr>
	
	<tr>
	  <td class="ewTableHeaderThin">Forma de Pago </td>
	  <td class="ewTableAltRow"><?php 
		$sSqlWrk = "SELECT descripcion FROM forma_pago where forma_pago_id = $x_forma_pago_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo $datawrk["descripcion"];
		@phpmkr_free_result($rswrk);
		?>
        <input type="hidden" name="x_forma_pago_id" id="x_forma_pago_id" value="<?php echo @$x_forma_pago_id; ?>" /></td>
	  </tr>
	<tr>
	  <td class="ewTableHeaderThin">Numero de Pagos </td>
	  <td class="ewTableAltRow">
	  <input name="x_num_pagos" type="text" id="x_num_pagos" value="<?php echo @$x_num_pagos; ?>" size="8" maxlength="5" />	  </td>
	  </tr>
	<tr>
	  <td class="ewTableHeaderThin">Tipo de Calculo</td>
	  <td class="ewTableAltRow"><input type="radio" name="x_tipo_calculo" value="1" <?php if($x_tipo_calculo == 1){ echo "checked";} ?> />
	    Promediado 
	      <input type="radio" name="x_tipo_calculo" value="2" <?php if($x_tipo_calculo == 2){ echo "checked";} ?> />
	      Tradicional</td>
	  </tr>
	<tr>
	  <td class="ewTableHeaderThin">Tasa Anual</td>
	  <td class="ewTableAltRow"><input name="x_tasa" type="text" id="x_tasa" value="<?php echo htmlspecialchars(@$x_tasa) ?>" size="8" maxlength="5" /> 
	    % </td>
	  </tr>
	<tr>
	  <td class="ewTableHeaderThin">IVA</td>
	  <td class="ewTableAltRow">
      <input type="radio" name="x_iva" value="1" <?php if($x_iva == 1){ echo "checked";} ?> />&nbsp;SI
      &nbsp;&nbsp;
      <input type="radio" name="x_iva" value="2" <?php if($x_iva == 2){ echo "checked";} ?>/>&nbsp;NO
	  </td>
	  </tr>
      
	<tr>
		<td class="ewTableHeaderThin"><span>Tasa moratoria</span></td>
		<td class="ewTableAltRow"><span>
<input name="x_tasa_moratoria" type="text" id="x_tasa_moratoria" value="<?php echo htmlspecialchars(@$x_tasa_moratoria) ?>" size="10" maxlength="10"> 
%
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Medio de pago</span></td>
		<td class="ewTableAltRow"><span>
<?php if (!(!is_null($x_medio_pago_id)) || ($x_medio_pago_id == "")) { $x_medio_pago_id = 0;} // Set default value ?>
<?php
$x_medio_pago_idList = "<select name=\"x_medio_pago_id\">";
$x_medio_pago_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT `medio_pago_id`, `descripcion` FROM `medio_pago`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_medio_pago_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["medio_pago_id"] == @$x_medio_pago_id) {
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
	  <td class="ewTableHeaderThin">Banco y Cuenta</td>
	  <td class="ewTableAltRow"><span>
<?php if (!(!is_null($x_banco_id)) || ($x_banco_id == "")) { $x_banco_id = 0;} // Set default value ?>
<?php
$x_medio_pago_idList = "<select name=\"x_banco_id\">";
$x_medio_pago_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT `banco_id`, `nombre`, `cuenta` FROM `banco`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_medio_pago_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["banco_id"] == @$x_banco_id) {
			$x_medio_pago_idList .= "' selected";
		}
		$x_medio_pago_idList .= ">" . $datawrk["nombre"] . " - " . $datawrk["cuenta"] . "</option>";
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
		<td class="ewTableHeaderThin"><span>Referencia</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_referencia_pago" id="x_referencia_pago" size="30" maxlength="50" value="<?php echo htmlspecialchars(@$x_referencia_pago) ?>">
</span></td>
	</tr>
	<tr>
	  <td class="ewTableHeaderThin">Numero de Tarjeta:</td>
	  <td class="ewTableAltRow"><input name="x_tdp" type="text" id="x_tdp" value="<?php echo @$x_tdp; ?>" size="20" maxlength="50" /></td>
	  </tr>
</table>
<p>
<div id="aceptar" class="<?php if($x_solicitud_id > 0){echo "TG_visible";}else{echo "TG_hidden";}?>">
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
	global $x_credito_id;
	$sSql = "SELECT * FROM `credito`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_credito_id) : $x_credito_id;
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
		$GLOBALS["x_credito_id"] = $row["credito_id"];
		$GLOBALS["x_credito_num"] = $row["credito_num"];		
		$GLOBALS["x_cliente_num"] = $row["cliente_num"];				
		$GLOBALS["x_credito_tipo_id"] = $row["credito_tipo_id"];
		$GLOBALS["x_solicitud_id"] = $row["solicitud_id"];
		$GLOBALS["x_credito_status_id"] = $row["credito_status_id"];
		$GLOBALS["x_fecha_otrogamiento"] = $row["fecha_otrogamiento"];
		$GLOBALS["x_fecha_primerpago"] = $row["fecha_primerpago"];		
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
	global $x_credito_id;
	$sSql = "SELECT * FROM `credito`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";

	// Check for duplicate key
	$bCheckKey = true;
	$sWhereChk = $sWhere;
	if ((@$x_credito_id == "") || (is_null($x_credito_id))) {
		$bCheckKey = false;
	} else {
		if ($sWhereChk <> "") { $sWhereChk .= " AND "; }
		$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_credito_id) : $x_credito_id;			
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


	$theValue = ($GLOBALS["x_credito_num"] != "") ? " '" . $GLOBALS["x_credito_num"] . "'" : "0";
	$fieldList["`credito_num`"] = $theValue;

	$theValue = ($GLOBALS["x_cliente_num"] != "") ? " '" . $GLOBALS["x_cliente_num"] . "'" : "0";
	$fieldList["`cliente_num`"] = $theValue;

	// Field credito_tipo_id
	$theValue = ($GLOBALS["x_credito_tipo_id"] != "") ? intval($GLOBALS["x_credito_tipo_id"]) : "NULL";
	$fieldList["`credito_tipo_id`"] = $theValue;

	// Field solicitud_id
	$theValue = ($GLOBALS["x_solicitud_id"] != "") ? intval($GLOBALS["x_solicitud_id"]) : "NULL";
	$fieldList["`solicitud_id`"] = $theValue;

	// Field credito_status_id
	$theValue = ($GLOBALS["x_credito_status_id"] != "") ? intval($GLOBALS["x_credito_status_id"]) : "NULL";
	$fieldList["`credito_status_id`"] = $theValue;

	// Field fecha_otrogamiento
	$theValue = ($GLOBALS["x_fecha_otrogamiento"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_otrogamiento"]) . "'" : "Null";
	$fieldList["`fecha_otrogamiento`"] = $theValue;

	// Field fecha_otrogamiento
	$theValue = ($GLOBALS["x_fecha_primerpago"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_primerpago"]) . "'" : "Null";
	$fieldList["`fecha_primerpago`"] = $theValue;

	$theValue = ($GLOBALS["x_fecha_abonocap"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_abonocap"]) . "'" : "Null";
	$fieldList["`fecha_abonocap`"] = $theValue;


	$theValue = ($GLOBALS["x_periodos_gracia"] != "") ? intval($GLOBALS["x_periodos_gracia"]) : 0;
	$fieldList["`periodos_gracia`"] = $theValue;



	// Field importe
	$theValue = ($GLOBALS["x_importe"] != "") ? " '" . doubleval($GLOBALS["x_importe"]) . "'" : "NULL";
	$fieldList["`importe`"] = $theValue;

	// Field plazo
	$theValue = ($GLOBALS["x_forma_pago_id"] != "") ? intval($GLOBALS["x_forma_pago_id"]) : "NULL";
	$fieldList["`forma_pago_id`"] = $theValue;

	$theValue = ($GLOBALS["x_tipo_calculo"] != "") ? intval($GLOBALS["x_tipo_calculo"]) : "NULL";
	$fieldList["`tipo_calculo`"] = $theValue;

	// Field tasa
	$theValue = ($GLOBALS["x_tasa"] != "") ? " '" . doubleval($GLOBALS["x_tasa"]) . "'" : "NULL";
	$fieldList["`tasa`"] = $theValue;

	$theValue = ($GLOBALS["x_iva"] != "") ? " '" . intval($GLOBALS["x_iva"]) . "'" : "2";
	$fieldList["`iva`"] = $theValue;

	// Field plazo
	$theValue = ($GLOBALS["x_plazo"] != "") ? intval($GLOBALS["x_plazo"]) : "NULL";
	$fieldList["`plazo_id`"] = $theValue;

	// Field fecha_vencimiento
	$theValue = ($GLOBALS["x_fecha_vencimiento"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_vencimiento"]) . "'" : "Null";
	$fieldList["`fecha_vencimiento`"] = $theValue;

	// Field tasa_moratoria
	$theValue = ($GLOBALS["x_tasa_moratoria"] != "") ? " '" . doubleval($GLOBALS["x_tasa_moratoria"]) . "'" : "NULL";
	$fieldList["`tasa_moratoria`"] = $theValue;

	// Field medio_pago_id
	$theValue = ($GLOBALS["x_medio_pago_id"] != "") ? intval($GLOBALS["x_medio_pago_id"]) : "NULL";
	$fieldList["`medio_pago_id`"] = $theValue;

	$theValue = ($GLOBALS["x_banco_id"] != "") ? intval($GLOBALS["x_banco_id"]) : "0";
	$fieldList["`banco_id`"] = $theValue;

	// Field referencia_pago
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_pago"]) : $GLOBALS["x_referencia_pago"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`referencia_pago`"] = $theValue;


	// 
	$theValue = ($GLOBALS["x_num_pagos"] != "") ? intval($GLOBALS["x_num_pagos"]) : "0";
	$fieldList["`num_pagos`"] = $theValue;


	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tdp"]) : $GLOBALS["x_tdp"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`tarjeta_num`"] = $theValue;


	// insert into database
	$sSql = "INSERT INTO `credito` (";
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
	$x_credito_id = mysql_insert_id();
	
	
//GENERA VENCIMIENTOS

	include("utilerias/datefunc.php");

	$sSql = "SELECT valor FROM plazo where plazo_id = ".$GLOBALS["x_plazo"];
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_plazo = $row["valor"];
	phpmkr_free_result($rs);		

	$sSql = "SELECT valor FROM forma_pago where forma_pago_id = ".$GLOBALS["x_forma_pago_id"];
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_forma_pago = $row["valor"];
	phpmkr_free_result($rs);		


//	$x_num_pagos = $x_plazo * $x_forma_pago;
	$x_num_pagos = $GLOBALS["x_num_pagos"];	

	$GLOBALS["x_importe"] = str_replace(",","",$GLOBALS["x_importe"]);
	$temptime = strtotime(ConvertDateToMysqlFormat($GLOBALS["x_fecha_otrogamiento"]));	
	$x_interes = 0;
	$x_pago_act = 1;
	

//tasa mensual
$x_tasa_mensual = (($GLOBALS["x_tasa"]/12)/100); 

//determinar tasa segun forma pago
switch ($x_forma_pago)
{
	case 28: // Mensual
		$x_tasa_calculada = (($GLOBALS["x_tasa"]/12)/100); 
		break;
	case 15: // Quincenal
		$x_tasa_calculada = (($GLOBALS["x_tasa"]/24)/100); 
		break;
	case 14: // Catorcenal
		$x_tasa_calculada = (($GLOBALS["x_tasa"]/26)/100); 
		break;
	case 7: // Semanal
		$x_tasa_calculada = (($GLOBALS["x_tasa"]/52)/100); 
		break;
}

if($GLOBALS["x_iva"] == 1){
	$x_tasa_calculada_pow = $x_tasa_calculada * 1.16;
}else{
	$x_tasa_calculada_pow = $x_tasa_calculada;	
}

	
/////// calculo promediado	
if($GLOBALS["x_tipo_calculo"] == 1){


	while($x_pago_act < $x_num_pagos + 1){
		$temptime = DateAdd('w',$x_forma_pago,$temptime);


		$fecha_act = strftime('%Y-%m-%d',$temptime);			
		$x_dia = strftime('%A',$temptime);

//Validar domingos
		if($x_dia == "SUNDAY"){
			$temptime = strtotime($fecha_act);
			$temptime = DateAdd('w',1,$temptime);
			$fecha_act = strftime('%Y-%m-%d',$temptime);
		}

//		$x_interes_act = (1/pow((1+doubleval($GLOBALS["x_tasa"]  / 100 )),$x_pago_act));

		$x_interes_act = (1/pow((1+doubleval($x_tasa_calculada)),$x_pago_act));


		$x_interes = $x_interes + $x_interes_act;
	
		$sSql = "insert into vencimiento values(0,$x_credito_id, $x_pago_act,1, '$fecha_act', 0, 0, 0, 0, 0, 0,NULL)";
		$x_result = phpmkr_query($sSql, $conn);
		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}
		
		$temptime = strtotime($fecha_act);
		$x_pago_act++;	
	}		

	$x_total_venc = round($GLOBALS["x_importe"] / $x_interes);
	$x_capital_venc = ($GLOBALS["x_importe"] / $x_num_pagos);
	$x_interes_venc = round($x_total_venc - $x_capital_venc);
	if($GLOBALS["x_iva"] == 1){
		$x_iva_venc = round($x_interes_venc * .15);	
		$x_total_venc = $x_total_venc + $x_iva_venc;
	}else{
		$x_iva_venc = 0;
	}

	$sSql = "update vencimiento set importe = $x_capital_venc, interes = $x_interes_venc, total_venc = $x_total_venc, iva = $x_iva_venc where credito_id = $x_credito_id";
	$x_result = phpmkr_query($sSql, $conn);
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
		phpmkr_query('rollback;', $conn);	 
		exit();
	}



////////////////// fin calculo promediado


}else{


//calculo de los interes dias de gracias fecha_primerpago - fecha_oorgamiento
// se suma al pago semanal dividido en todos los pagos
// hay que respetar el interes normal del primer pago osea se suma al int del periodo de gracia.

/*

PARA ACCELI
-----------------------
PRIMERO SE CALCULA EN INTERES DEL TOTAL Y ESTO CORRESPONDE AL IMPORTE DEL VENCIMIENTO
DE CADA PAGO HASTA EL DIA DE PRIMER PAGO. DESPUES SE DEBEN DE CALCULAR LOS INTERESES
NORMAL DE FORMA TRADICIONAL CON ABONOS A CAPITAL


1 determinar el total a pagar del credito importe + int + iva
2 determinar num de venc solo int y sumar importe
3 restar total int sin capital al importe total para sacar importe restante
4 importe venc dividir imp restante entre vencimientos sobrantes redondeado
5 primer venc abono cap = (importe venc - interes)
6 resta abono cap determina interes y abono cap = (importe venc  - interes)
7 en el ultimo venc se debe de ajustar el abono a cap e importe del venc para ello se deben de ir sumando los abonos para restarlos al importe del prestamo)

calcinteres.xls en doctos accelicap

*/

//include("utilerias/datefunc.php");



//aqui


	$x_fecha_otrogamiento = $GLOBALS["x_fecha_otrogamiento"];
	$x_fecha_primerpago = $GLOBALS["x_fecha_primerpago"];  //esta vacia
	$x_fecha_abonocap = $GLOBALS["x_fecha_abonocap"];
	$x_periodos_gracia = $GLOBALS["x_periodos_gracia"];
	$x_importe = $GLOBALS["x_importe"];
	$x_vencimiento_actual = 0;


	//dias de gracia - calcula saldo de interes para aplicar en el primer vencmiemtno
	$x_saldo_intereses = 0;
	if($x_fecha_primerpago != $x_fecha_otrogamiento && false){

		$x_fecha_ant = strtotime(ConvertDateToMysqlFormat($x_fecha_otrogamiento));
		$fecha_act = strtotime(ConvertDateToMysqlFormat($x_fecha_primerpago));

		$difference = $fecha_act - $x_fecha_ant; // Difference in seconds
		$x_dias_total = floor($difference / 86400);

		//calcular interes con tasa diaria
		$x_interes_gracia = ($x_importe * (($GLOBALS["x_tasa"]/360)/100)) * $x_dias_total;
		
		$x_saldo_intereses = $x_interes_gracia;
		
		if($GLOBALS["x_iva"] == 1){
			$x_iva_gracia = round($x_saldo_intereses * .16);	
		}else{
			$x_iva_gracia = 0;
		}
		
		
		
	}
	
	$x_venc_num_sac = 0;

	//genera vencimientos sin abono a capital
	
	
//	if($x_fecha_abonocap != $x_fecha_otrogamiento){
	if($x_periodos_gracia > 0 && false){		
		//genera vencimientos sin abono a capital
		$x_venc_num_sac = 1;		
		$fecha_ant = $x_fecha_otrogamiento;
		$fecha_act = ConvertDateToMysqlFormat($x_fecha_otrogamiento);		
		
		$x_contador = 1;
		
//		while(strtotime($fecha_act) < strtotime(ConvertDateToMysqlFormat($x_fecha_abonocap)) || strtotime($fecha_act) == strtotime(ConvertDateToMysqlFormat($x_fecha_abonocap))){
		while($x_contador <= $x_periodos_gracia || $x_contador == $x_periodos_gracia){
			
			$fecha_ant = strtotime(ConvertDateToMysqlFormat($fecha_ant));				
			$fecha_act = DateAdd('w',$x_forma_pago,$fecha_ant);
			$fecha_act = strftime('%Y-%m-%d',$fecha_act);
			$fecha_act = strtotime(ConvertDateToMysqlFormat($fecha_act));

			
			$difference = $fecha_act - $fecha_ant; // Difference in seconds
			$x_dias_total = floor($difference / 86400);		

			$x_interes_sac = ($x_importe * (($GLOBALS["x_tasa"]/360)/100)) * $x_dias_total;

			if($x_saldo_intereses > 0 && $x_venc_num_sac == 1){
				$x_interes_sac = $x_interes_sac + $x_saldo_intereses;
			}
			
			if($GLOBALS["x_iva"] == 1){
				$x_iva_sac = round($x_interes_sac * .16);	
			}else{
				$x_iva_sac = 0;
			}
			
			$x_importe_vencimiento_sac = $x_interes_sac + $x_iva_sac;


			$fecha_act = strftime('%Y-%m-%d',$fecha_act);
			$fecha_ant = strftime('%Y-%m-%d',$fecha_ant);	

			$sSql = "insert into vencimiento values(0,$x_credito_id, $x_venc_num_sac,1, '".$fecha_act."', 0, $x_interes_sac, 0, $x_iva_sac, 0, $x_importe_vencimiento_sac,NULL)";
			$x_result = phpmkr_query($sSql, $conn);
			if(!$x_result){
				echo phpmkr_error() . '<br>SQL: ' . $sSql;
				phpmkr_query('rollback;', $conn);	 
				exit();
			}


			echo $sSql."<br>";
			echo $fecha_ant ." -- " .$x_fecha_abonocap."<br>";
			echo strtotime($fecha_ant) ." - ". strtotime(ConvertDateToMysqlFormat($x_fecha_abonocap))."<br>";
			echo $fecha_act . " < " .  $x_fecha_abonocap." - ".$x_contador."<br>";

			$x_saldo_intereses = $x_saldo_intereses + $x_interes_sac;
			$fecha_ant = $fecha_act; 
			$x_contador++;
			$x_venc_num_sac++;
			
		}
		$x_venc_num_sac = $x_venc_num_sac - 1;
	}


//	die();

	//genera vencimientos con abono a capital
	//deterrmina venc restantes


	$x_venc_num_cac = $x_num_pagos - $x_venc_num_sac;
	$x_saldo_intereses = 0;
	$x_fecha_abonocap = $fecha_act;
	
	
	//calculo de pago de vencimientos
	$temptime = $x_fecha_abonocap;
//	$temptime = strtotime(ConvertDateToMysqlFormat($x_fecha_abonocap));		
	$x_pago_act = 1;
	while($x_pago_act < ($x_venc_num_cac + 1)){
		$temptime = DateAdd('w',$x_forma_pago,$temptime);

		$fecha_act = strftime('%Y-%m-%d',$temptime);			
		$x_dia = strftime('%A',$temptime);

		//Validar domingos
		if($x_dia == "SUNDAY"){
			$temptime = strtotime($fecha_act);
			$temptime = DateAdd('w',1,$temptime);
			$fecha_act = strftime('%Y-%m-%d',$temptime);
		}

		$x_interes_act = (1/pow((1+doubleval($x_tasa_calculada_pow)),$x_pago_act));
		
		$x_interes = $x_interes + $x_interes_act;
	
		$temptime = strtotime($fecha_act);
		$x_pago_act++;	
	}		
	

	$x_total_venc = round($x_importe / $x_interes);	
/*		
	$x_total_intereses = ($x_total_venc * $x_num_pagos) - $x_importe;
	if($GLOBALS["x_iva"] == 1){
		$x_total_intereses = $x_total_intereses * 1.16;
		$x_interes_venc = round($x_total_intereses / $x_num_pagos);		
		$x_total_venc = $x_total_venc + $x_interes_venc;
	}
*/
	
	
/*
	if($x_saldo_intereses > 0){
		$x_total_venc = ($x_total_venc + (($x_saldo_intereses + $x_iva_gracia) / $x_venc_num_cac)); 
	}
*/
	//genera vencimientos saldos insolutos
	
	
	//$temptime = strtotime(ConvertDateToMysqlFormat($x_fecha_abonocap));		esta fecha viene vacia por eso nos da el error. >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	$temptime = strtotime(ConvertDateToMysqlFormat(ConvertDateToMysqlFormat($GLOBALS["x_fecha_otrogamiento"])));	
	$x_saldo = $x_importe;
	if($x_venc_num_sac > 0){
		$x_venc_num = $x_venc_num_sac + 1;
	}else{
		$x_venc_num = 1;
	}
	$x_pago_act = 1;
	$x_capital_ajuste = 0;
	while($x_pago_act < $x_venc_num_cac + 1){
		$x_interes_pp = 0;

		
		$fecha_act = DateAdd('w',$x_forma_pago,$temptime);
		$fecha_act = strftime('%Y-%m-%d',$fecha_act);			
		$temptime = strftime('%Y-%m-%d',$temptime);					
		
		$difference = strtotime($fecha_act) - strtotime($temptime); // Difference in seconds
		$x_dias_total = floor($difference / 86400);


		//calcular interes con tasa diaria
		$x_interes_pp = round(($x_saldo * (($GLOBALS["x_tasa"]/360)/100)) * $x_dias_total);
		
		//calcular interes con tasa caluclada segun forma de pago
		$x_interes_pp = round($x_saldo * $x_tasa_calculada);		

		//sumar saldo de interes gracia
		/*
		if($x_pago_act == 1 && $x_saldo_intereses > 0){
			$x_interes_pp = $x_interes_pp + $x_saldo_intereses;
			$x_saldo_intereses = 0;
		}
		*/
		if($GLOBALS["x_iva"] == 1){
			$x_iva_pp = round($x_interes_pp * .16);	
		}else{
			$x_iva_pp = 0;
		}





/*
		$x_interes_pp = $x_interes_pp + $x_saldo_intereses;
		
		if((($x_interes_pp + $x_iva_pp) < $x_total_venc) || (($x_interes_pp + $x_iva_pp) == $x_total_venc)){
			$x_capital_pp = $x_total_venc - $x_interes_pp - $x_iva_pp;
			$x_saldo_intereses = 0;
		}else{
			$x_capital_pp = 0;
			$x_saldo_intereses = $x_interes_pp - ($x_total_venc + $x_iva_pp);
			$x_interes_pp = ($x_total_venc - $x_iva_pp);
		}

*/

		$x_capital_pp = $x_total_venc - $x_interes_pp - $x_iva_pp;
		$x_saldo_intereses = 0;




//ajuste de ultimo vencimiento
		$x_capital_ajuste = $x_capital_ajuste + $x_capital_pp;
		if($x_pago_act == $x_venc_num_cac){
			
			if($x_capital_ajuste < $x_importe){
				$x_ajuste = $x_importe - $x_capital_ajuste;
				$x_capital_pp = $x_capital_pp + $x_ajuste;
				$x_interes_pp = $x_interes_pp - $x_ajuste;
			}

			if($x_capital_ajuste > $x_importe){
				$x_ajuste = $x_capital_ajuste - $x_importe;
				$x_capital_pp = $x_capital_pp - $x_ajuste;
				$x_interes_pp = $x_interes_pp + $x_ajuste;
			}
			
		}



		$sSql = "insert into vencimiento values(0,$x_credito_id, $x_venc_num,1, '$fecha_act', $x_capital_pp, $x_interes_pp, 0, $x_iva_pp, 0, $x_total_venc,NULL)";
		$x_result = phpmkr_query($sSql, $conn);
		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}
		
		$temptime = strtotime($fecha_act);
		$x_saldo = $x_saldo - $x_capital_pp;	
		$x_venc_num++;	
		$x_pago_act++;	
	}		

	//$fecha_act = strftime('%Y-%m-%d',$fecha_act);

///////////////// fin calculo no promediado


}



	$sSql = "update credito set fecha_vencimiento = '$fecha_act' where credito_id = $x_credito_id";
	$x_result = phpmkr_query($sSql, $conn);
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
		phpmkr_query('rollback;', $conn);	 
		exit();
	}

	$sSql = "update solicitud set solicitud_status_id = 6 where solicitud_id =  ".$GLOBALS["x_solicitud_id"];
	$x_result = phpmkr_query($sSql, $conn);
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
		phpmkr_query('rollback;', $conn);	 
		exit();
	}




//credito_tipo_id


	$sSql = "SELECT credito_tipo_id FROM solicitud where solicitud_id =  ".$GLOBALS["x_solicitud_id"];
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_credito_tipo_id = $row["credito_tipo_id"];
	phpmkr_free_result($rs);		


if($x_credito_tipo_id != 2){

	$sSql = "SELECT cliente_id FROM solicitud_cliente where solicitud_id =  ".$GLOBALS["x_solicitud_id"];
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_cliente_id = $row["cliente_id"];
	phpmkr_free_result($rs);		


	$sSql = "update cliente set cliente_num = '".$GLOBALS["x_cliente_num"]."' where cliente_id = $x_cliente_id ";
	$x_result = phpmkr_query($sSql, $conn);
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
		phpmkr_query('rollback;', $conn);	 
		exit();
	}



//USUARIO Y PASSWORD
	include("utilerias/gen_pass_ec.php");

	$x_asignada = 0;
	while($x_asignada == 0){
		$clave = generate(8, "No", "Yes", "Yes");
		$sSql = "Select * from usuario where usuario = '$clave'";
		$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		if (phpmkr_num_rows($rs2) == 0) {	
			$x_usuario = $clave;
			$x_asignada = 10;
		}
		phpmkr_free_result($rs2);
	}

	$x_asignada = 0;
	while($x_asignada == 0){
		$clave = generate(8, "No", "Yes", "Yes");
		$sSql = "Select * from usuario where clave = '$clave'";
		$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		if (phpmkr_num_rows($rs2) == 0) {	
			/*		
			$sSql = "update socios set clave = '$clave' where socio_id = $x_socio_id";
			phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			*/
			$x_clave = $clave;
			$x_asignada = 10;
		}
		phpmkr_free_result($rs2);
	}

	$sSql = "SELECT * FROM cliente where cliente_id = $x_cliente_id";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_nombre = $row["nombre_completo"]." ". $row["apellido_paterno"]." ".$row["apellido_materno"];
	$x_email = $row["email"];
	phpmkr_free_result($rs);		


	$fieldList = NULL;
	// Field usuario_rol_id
	$fieldList["`usuario_rol_id`"] = 8;

	// Field usuario_status_id
	$fieldList["`usuario_status_id`"] = 1;

	// Field usuario
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_usuario) : $x_usuario; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`usuario`"] = $theValue;

	// Field clave
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_clave) : $x_clave; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`clave`"] = $theValue;

	// Field nombre
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_nombre) : $x_nombre; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre`"] = $theValue;

	// Field fecha_registro
	$theValue = ($GLOBALS["x_fecha_otrogamiento"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_otrogamiento"]) . "'" : "Null";
	$fieldList["`fecha_registro`"] = $theValue;

	// Field fecha_caduca
	$theValue = ($GLOBALS["x_fecha_caduca"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_caduca"]) . "'" : "Null";
	$fieldList["`fecha_caduca`"] = $theValue;

	// Field fecha_visita
	$theValue = ($GLOBALS["x_fecha_visita"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_visita"]) . "'" : "Null";
	$fieldList["`fecha_visita`"] = $theValue;

	// Field visitas
	$theValue = ($GLOBALS["x_visitas"] != "") ? intval($GLOBALS["x_visitas"]) : "0";
	$fieldList["`visitas`"] = $theValue;

	// Field email
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_email) : $x_email; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`email`"] = $theValue;

	// insert into database
	$sSql = "INSERT INTO `usuario` (";
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
	
	$x_usuario_id = mysql_insert_id();

	$sSql = "update cliente set usuario_id = $x_usuario_id where cliente_id = $x_cliente_id";
	$x_result = phpmkr_query($sSql, $conn);
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
		phpmkr_query('rollback;', $conn);	 
	 	exit();
	}


}else{

include("utilerias/gen_pass_ec.php");

				


	// seleccionamos el grupo_ID
	$sqlGrupo = "SELECT grupo_id FROM solicitud_grupo WHERE solicitud_id = ".$GLOBALS["x_solicitud_id"]."";
	$responseGid = phpmkr_query($sqlGrupo, $conn) or die("Error al seleccionar grupo id".phpmkr_error()."sql:".$sqlGrupo);
	$rowgid = phpmkr_fetch_array($responseGid);
	$x_grupo_id =  $rowgid["grupo_id"];
	
	if(!empty($x_grupo_id)){
		
		
		//seleccionamos los id de los clientes que pertenecen a ese grupo
		$sqlClientes = "SELECT * FROM creditosolidario  WHERE creditoSolidario_id = $x_grupo_id ";
		$resposeClientes = phpmkr_query($sqlClientes, $conn) or die("Error a seleccionar los clientes del grupo".phpmkr_error()."sql:".$sqlClientes);
		$rowClientes = phpmkr_fetch_array($resposeClientes);
			$GLOBALS["x_creditoSolidario_id"] =  $rowClientes["creditoSolidario_id"];
			$GLOBALS["x_nombre_grupo"] = $rowClientes["nombre_grupo"];
			 echo "seleccionamos los id de los clintes";
			$x_cont_g = 1;
			while($x_cont_g <= 10){
				
				$GLOBALS["x_integrante_$x_cont_g"] = $rowClientes["integrante_$x_cont_g"];
				$GLOBALS["x_cliente_id_$x_cont_g"] = $rowClientes["cliente_id_$x_cont_g"];
				
				
				// mientras hay integrantes, si no esta vacio el id y es numerico, se le asigna un numero de usuario.
				if ( !empty($GLOBALS["x_cliente_id_$x_cont_g"]) && ($GLOBALS["x_cliente_id_$x_cont_g"] != "") && intval($GLOBALS["x_cliente_id_$x_cont_g"])){
					echo "entramos la while con el id ".$GLOBALS["x_cliente_id_$x_cont_g"]."<br>";
					// seleccionamos el ultimo numero de cliente asignado y le agregamos 1					 
							$sSqlWrkn = "SELECT max(cliente_num+0) as cliente_num_new FROM cliente ";
							$rswrkn = phpmkr_query($sSqlWrkn,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrkn);
							$datawrkn = phpmkr_fetch_array($rswrkn);
							$x_cliente_num = $datawrkn["cliente_num_new"] + 1;
							@phpmkr_free_result($rswrkn);
					 
	  				// actualizamos el numero de cliente					
							$sSql = "UPDATE cliente SET cliente_num = $x_cliente_num WHERE cliente_id = ". $GLOBALS["x_cliente_id_$x_cont_g"]."";
							$x_result = phpmkr_query($sSql, $conn);
							if(!$x_result){
								echo phpmkr_error() . '<br>SQL: ' . $sSql;
								phpmkr_query('rollback;', $conn);	 
								exit();
							}
							
							echo "numero de cliente = " .$x_cliente_num."<br>";
					
					//USUARIO Y PASSWORD
					$x_asignada = 0;
					while($x_asignada == 0){
						$clave = generate(8, "No", "Yes", "Yes");
						$sSql = "Select * from usuario where usuario = '$clave'";
						$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
						if (phpmkr_num_rows($rs2) == 0) {	
							$x_usuario = $clave;
							$x_asignada = 10;
						}
						phpmkr_free_result($rs2);
					}
				
					$x_asignada = 0;
					while($x_asignada == 0){
						$clave = generate(8, "No", "Yes", "Yes");
						$sSql = "Select * from usuario where clave = '$clave'";
						$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
						if (phpmkr_num_rows($rs2) == 0) {	
							/*		
							$sSql = "update socios set clave = '$clave' where socio_id = $x_socio_id";
							phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
							*/
							$x_clave = $clave;
							$x_asignada = 10;
						}
						phpmkr_free_result($rs2);
					}
				
					$sSql = "SELECT * FROM cliente where cliente_id =".$GLOBALS["x_cliente_id_$x_cont_g"]. "";
					$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
					$row = phpmkr_fetch_array($rs);
					$x_nombre = $row["nombre_completo"]." ". $row["apellido_paterno"]." ".$row["apellido_materno"];
					$x_email = $row["email"];
					phpmkr_free_result($rs);		
				
				
					$fieldList = NULL;
					// Field usuario_rol_id
					$fieldList["`usuario_rol_id`"] = 8;
				
					// Field usuario_status_id
					$fieldList["`usuario_status_id`"] = 1;
				
					// Field usuario
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_usuario) : $x_usuario; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`usuario`"] = $theValue;
				
					// Field clave
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_clave) : $x_clave; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`clave`"] = $theValue;
				
					// Field nombre
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_nombre) : $x_nombre; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`nombre`"] = $theValue;
				
					// Field fecha_registro
					$theValue = ($GLOBALS["x_fecha_otrogamiento"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_otrogamiento"]) . "'" : "Null";
					$fieldList["`fecha_registro`"] = $theValue;
				
					// Field fecha_caduca
					$theValue = ($GLOBALS["x_fecha_caduca"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_caduca"]) . "'" : "Null";
					$fieldList["`fecha_caduca`"] = $theValue;
				
					// Field fecha_visita
					$theValue = ($GLOBALS["x_fecha_visita"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_visita"]) . "'" : "Null";
					$fieldList["`fecha_visita`"] = $theValue;
				
					// Field visitas
					$theValue = ($GLOBALS["x_visitas"] != "") ? intval($GLOBALS["x_visitas"]) : "0";
					$fieldList["`visitas`"] = $theValue;
				
					// Field email
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_email) : $x_email; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`email`"] = $theValue;
				
					// insert into database
					$sSql = "INSERT INTO `usuario` (";
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
					
					$x_usuario_id = mysql_insert_id();
				
					$sSql = "update cliente set usuario_id = $x_usuario_id where cliente_id = ". $GLOBALS["x_cliente_id_$x_cont_g"] ." ";
					$x_result = phpmkr_query($sSql, $conn);
					if(!$x_result){
						echo phpmkr_error() . '<br>SQL: ' . $sSql;
						phpmkr_query('rollback;', $conn);	 
						exit();
					}					
					
					
					}// if vacio! , numerico
				$x_cont_g++;
				}// while 10
		phpmkr_free_result($rowClientes);
		
		
		}// ! vacio grupo id

		
} // else if tipo !2


//RENOVACIONES
	$sSql = "SELECT promotor_id, solicitud_id_ant FROM solicitud where solicitud_id = ".$GLOBALS["x_solicitud_id"];
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_promotor_id = $row["promotor_id"];
	$x_solicitud_id_ant = $row["solicitud_id_ant"];	
	phpmkr_free_result($rs);		



	if($x_solicitud_id_ant > 0 ){

		$sSql = "SELECT credito_id FROM credito where solicitud_id = $x_solicitud_id_ant";
		$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row = phpmkr_fetch_array($rs);
		$x_credito_id_ant = $row["credito_id"];
		phpmkr_free_result($rs);		
	
		$sSql = "update credito set credito_id_ant = $x_credito_id_ant where credito_id = $x_credito_id";
		$x_result = phpmkr_query($sSql, $conn);
		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}
	
		$x_comision_importe = 50;	
	}else{
		$x_comision_importe = 100;
	}


//COMISIONES



	$fieldList = NULL;
	
	// Field promotor_id
	$fieldList["`promotor_id`"] = $x_promotor_id;

	// Field solicitud_id
	$fieldList["`credito_id`"] = $x_credito_id;

	// Field solicitud_id
	$fieldList["`vencimiento_id`"] = 0;

	// Field fecha_registro
	$theValue = ($GLOBALS["x_fecha_otrogamiento"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_otrogamiento"]) . "'" : "Null";
	$fieldList["`fecha_registro`"] = $theValue;

	// Field comision
	$theValue = ($GLOBALS["x_comision"] != "") ? " '" . doubleval($GLOBALS["x_comision"]) . "'" : "NULL";
	$fieldList["`comision`"] = $theValue;

	// Field comision_importe
	$fieldList["`comision_importe`"] = $x_comision_importe;

	// Field referencia
	if($GLOBALS["x_credito_num"] != ""){
		$fieldList["`referencia`"] = "'".$GLOBALS["x_credito_num"]."'";
	}else{
		$fieldList["`referencia`"] = "NULL";
	}

	// Field promotor_comision_status_id
	$fieldList["`promotor_comision_status_id`"] = 1;

	// insert into database
	$sSql = "INSERT INTO `promotor_comision` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);





	$sSql = "SELECT cliente_id FROM solicitud_cliente where solicitud_id =  ".$GLOBALS["x_solicitud_id"];
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_cliente_id = $row["cliente_id"];
	phpmkr_free_result($rs);

//GENERA CASO CRM
// debe ser dos dias despues de que se otorgo el credito, y se le deben dar dos dias de gracia.
$currentdatecrm = getdate(time());
$currtime = $currentdatecrm["hours"].":".$currentdatecrm["minutes"].":".$currentdatecrm["seconds"];	

$cdate = getdate(time());
$currdate2 = $cdate["mday"]."/".$cdate["mon"]."/".$cdate["year"];	
$currdate2 = ConvertDateToMysqlFormat($currdate2);
// fecha en que se otorgo el credito, no la fecha actual.
$x_fecha_otorg = ConvertDateToMysqlFormat($GLOBALS["x_fecha_otrogamiento"]);
$currdate2 = $x_fecha_otorg;

$temptime = strtotime($currdate2);	
$temptime = DateAdd('w',2,$temptime);
$fecha_tarea = strftime('%Y-%m-%d',$temptime);	

/*$x_dia = strftime('%A',$temptime);
if($x_dia == "SUNDAY"){
	$temptime = strtotime($fecha_venc);
	$temptime = DateAdd('w',1,$temptime);
	$fecha_tarea = strftime('%Y-%m-%d',$temptime);
}
$temptime = strtotime($fecha_tarea);*/



	$sSqlWrk = "
	SELECT *
	FROM 
		crm_playlist
	WHERE 
		crm_playlist.crm_caso_tipo_id = 2
		AND crm_playlist.orden = 1
	";
	// constancia de credito recibido
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$datawrk = phpmkr_fetch_array($rswrk);
	$x_crm_playlist_id = $datawrk["crm_playlist_id"];
	$x_prioridad_id = $datawrk["prioridad_id"];	
	$x_asunto = $datawrk["asunto"];	
	$x_descripcion = $datawrk["descripcion"];		
	$x_tarea_tipo_id = $datawrk["tarea_fuente"];		
	$x_orden = $datawrk["orden"];	
	$x_dias_espera = $datawrk["dias_espera"];
	$x_dias_espera =$x_dias_espera +1;
	@phpmkr_free_result($rswrk);


	//Fecha Vencimiento
	$temptime = strtotime(ConvertDateToMysqlFormat($fecha_tarea));	
	$temptime = DateAdd('w',$x_dias_espera,$temptime);
	$fecha_venc = strftime('%Y-%m-%d',$temptime);	
	echo "fecha".$fecha_venc."";
	$x_dia = strftime('%A',$temptime);
	if($x_dia == "SUNDAY"){
		$temptime = strtotime($fecha_venc);
		$temptime = DateAdd('w',1,$temptime);
		$fecha_venc = strftime('%Y-%m-%d',$temptime);
	}
	$temptime = strtotime($fecha_venc);


	// SE CAMBIO LA FECHA, ANTES TENIA FECHA DE OTORGAMIENTO.
	$x_origen = 1;
	$x_bitacora = "Seguimiento de Credito - (".FormatDateTime($fecha_tarea,7)." - $currtime)";

	$x_bitacora .= "\n";
	$x_bitacora .= "$x_asunto - $x_descripcion ";	
	

	$sSqlWrk = "
	SELECT usuario_id
	FROM 
		usuario
	WHERE 
		usuario.usuario_rol_id = 2
	LIMIT 1
	";
	
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$datawrk = phpmkr_fetch_array($rswrk);
	$x_usuario_id = $datawrk["usuario_id"];
	@phpmkr_free_result($rswrk);
	
// SE CAMBIO LA FECHA ANTES TENIA FECHA DE OTORGAMIENTO
	$sSql = "INSERT INTO crm_caso values (0,2,1,1,$x_cliente_id,'".ConvertDateToMysqlFormat($fecha_tarea)."',$x_origen,$x_usuario_id,'$x_bitacora','".ConvertDateToMysqlFormat($GLOBALS["x_fecha_otrogamiento"])."',NULL,$x_credito_id)";

	$x_result = phpmkr_query($sSql, $conn);
	$x_crm_caso_id = mysql_insert_id();	
	
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
		phpmkr_query('rollback;', $conn);	 
		exit();
	}

// SE CAMBIO LA FECHA ANTES TENIA FECHA DE OTORGAMIENTO
	$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".ConvertDateToMysqlFormat($fecha_tarea)."','$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 2, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";

	$x_result = phpmkr_query($sSql, $conn);
	
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
		phpmkr_query('rollback;', $conn);	 
		exit();
	}
	



//FONDEO
	
	if(!empty($GLOBALS["x_fondeo_credito_id"]) && $GLOBALS["x_fondeo_credito_id"] > 0){
		$sSql = "INSERT INTO fondeo_colocacion values (0,".$GLOBALS["x_fondeo_credito_id"].",$x_credito_id)";
	
		$x_result = phpmkr_query($sSql, $conn);
		
		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}
	}

	phpmkr_query('commit;', $conn);	 
	return true;
}
?>

