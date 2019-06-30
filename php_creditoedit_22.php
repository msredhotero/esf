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
$currentdate = getdate(time());
$currdate = $currentdate["year"]."-".$currentdate["mon"]."-".$currentdate["mday"];
$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];	
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
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Load key from QueryString
$x_credito_id = @$_GET["credito_id"];

//if (!empty($x_credito_id )) $x_credito_id  = (get_magic_quotes_gpc()) ? stripslashes($x_credito_id ) : $x_credito_id ;
// Get action

$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I";	// Display with input box
} else {

	// Get fields from form
	$x_credito_id = @$_POST["x_credito_id"];
	$x_credito_num = @$_POST["x_credito_num"];	
	$x_cliente_num = @$_POST["x_cliente_num"];		
	$x_credito_tipo_id = @$_POST["x_credito_tipo_id"];
	$x_solicitud_id = @$_POST["x_solicitud_id"];
	$x_credito_status_id = @$_POST["x_credito_status_id"];
	$x_fecha_otrogamiento = @$_POST["x_fecha_otrogamiento"];
	$x_fecha_primerpago = @$_POST["x_fecha_primerpago"];	
	$x_periodos_gracia = @$_POST["x_periodos_gracia"];		
	$x_importe = @$_POST["x_importe"];
	$x_tipo_calculo = @$_POST["x_tipo_calculo"];
	$x_tasa = @$_POST["x_tasa"];
	$x_iva = @$_POST["x_iva"];		
	$x_plazo = @$_POST["x_plazo"];
	$x_forma_pago_id = @$_POST["x_forma_pago_id"];		
	$x_fecha_vencimiento = @$_POST["x_fecha_vencimiento"];
	$x_tasa_moratoria = @$_POST["x_tasa_moratoria"];
	$x_medio_pago_id = @$_POST["x_medio_pago_id"];
	$x_banco_id = @$_POST["x_banco_id"];	
	$x_referencia_pago = @$_POST["x_referencia_pago"];
	$x_num_pagos = @$_POST["x_num_pagos"];		
	$x_tdp = @$_POST["x_tdp"];	
	$x_fecha_edit_status = @$_POST["x_fecha_edit_status"];
}

// Check if valid key
if (($x_credito_id == "") || (is_null($x_credito_id))) {
	ob_end_clean();
	header("Location: php_creditolist.php");
	exit();
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron los datos.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_creditolist.php");
			exit();
		}
		break;
	case "U": // Update
		if (EditData($conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "Los datos han sido actualizados.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_creditolist.php");
			exit();
		}
		break;
	case "R": // Recalc
		//Valida Vencximientos solo con status pendiente
		$sSql = "SELECT count(*) as venapli FROM vencimiento where credito_id = $x_credito_id and vencimiento_status_id <> 1";
		$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row = phpmkr_fetch_array($rs);
		$x_venapli = $row["venapli"];
		phpmkr_free_result($rs);		
		if($x_venapli > 0 ){
			if (!LoadData($conn)) { // Load Record based on key
				$_SESSION["ewmsg"] = "No se localizaron los datos.";
				phpmkr_db_close($conn);
				ob_end_clean();
				header("Location: php_creditolist.php");
				exit();
			}
			$_SESSION["ewmsg"] = "El credito tiene vencimientos pagados, o vencidos no puede ser recalculado.";			
			
		}else{
			if (RecalData($conn)) { // Update Record based on key
				$_SESSION["ewmsg"] = "El credito ha sido recalculado.";
				if (!LoadData($conn)) { // Load Record based on key
					$_SESSION["ewmsg"] = "No se localizaron los datos.";
					phpmkr_db_close($conn);
					ob_end_clean();
					header("Location: php_creditolist.php");
					exit();
				}
			}
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

function modifica(v_acc) {
EW_this = document.creditoedit;
validada = true;

if(v_acc == 1){
	if (validada == true && EW_this.x_credito_num && !EW_hasValue(EW_this.x_credito_num, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_credito_num, "TEXT", "El numero de credito es requerido."))
			validada = false;
	}
	if (validada == true && EW_this.x_cliente_num && !EW_hasValue(EW_this.x_cliente_num, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_cliente_num, "TEXT", "El numero de cliente es requerido."))
			validada = false;
	}
	
	if (validada == true && EW_this.x_medio_pago_id && !EW_hasValue(EW_this.x_medio_pago_id, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_medio_pago_id, "SELECT", "El medio de pago es requerido."))
			validada = false;
	}
	if (validada == true && EW_this.x_banco_id && !EW_hasValue(EW_this.x_banco_id, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_banco_id, "SELECT", "El banco y cta son requeridos."))
			validada = false;
	}
	
	if (validada == true && EW_this.x_referencia_pago && !EW_hasValue(EW_this.x_referencia_pago, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_referencia_pago, "TEXT", "La refencia es requerida."))
			validada = false;
	}
	if (validada == true && EW_this.x_credito_status_id && !EW_hasValue(EW_this.x_credito_status_id, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_credito_status_id, "SELECT", "El status es requerido."))
			validada = false;
	}

	EW_this.a_edit.value = "U";

}else{

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
	if (validada == true && EW_this.x_tipo_calculo && !EW_hasValue(EW_this.x_tipo_calculo, "RADIO" )) {
		if (!EW_onError(EW_this, EW_this.x_tipo_calculo, "RADIO", "El tipo de calculo es requerido."))
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
	if (validada == true && EW_this.x_forma_pago_id && !EW_hasValue(EW_this.x_forma_pago_id, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_forma_pago_id, "SELECT", "La forma de pago es requerido."))
			validada = false;
	}	
	if (validada == true && EW_this.x_medio_pago_id && !EW_hasValue(EW_this.x_medio_pago_id, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_medio_pago_id, "SELECT", "El medio de pago es requerido."))
			validada = false;
	}
	if (validada == true && EW_this.x_banco_id && !EW_hasValue(EW_this.x_banco_id, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_banco_id, "SELECT", "El banco y la cuenta son requeridos."))
			validada = false;
	}	
	if (validada == true && EW_this.x_referencia_pago && !EW_hasValue(EW_this.x_referencia_pago, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_referencia_pago, "TEXT", "La refencia es requerida."))
			validada = false;
			
			
	if (validada == true && EW_this.x_fondeo_credito_id && !EW_hasValue(EW_this.x_fondeo_credito_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_fondeo_credito_id, "SELECT", "Debe seleccionar los fondos."))
		validada = false;
}		
			
	}

	EW_this.a_edit.value = "R";
}

if(validada == true){
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
    <a href="php_creditolist.php">Regresar a la lista</a></span></p>
<form name="creditoedit" id="creditoedit" action="php_creditoedit.php" method="post">
<p>
<input type="hidden" name="a_edit" value="U">
<input type="hidden" name="x_fecha_edit_status" value="<?php echo $currdate ?>" />
<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION["ewmsg"] ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>

<table class="phpmaker">
  <tr>
    <td class="ewTableHeaderThin">Cr&eacute;diro N&uacute;m:</td>
    <td class="ewTableAltRow"><input name="x_credito_num" type="text" id="x_credito_num" value="<?php echo $x_credito_num; ?>" size="5" maxlength="5" /></td>
  </tr>
  <tr>
    <td class="ewTableHeaderThin">Cliente N&uacute;m:</td>
    <td class="ewTableAltRow"><input name="x_cliente_num" type="text" id="x_cliente_num" value="<?php echo $x_cliente_num; ?>" size="5" maxlength="5" /></td>
  </tr>
  <tr>
    <td class="ewTableHeaderThin">Medio de Pago </td>
    <td class="ewTableAltRow"><?php if (!(!is_null($x_medio_pago_id)) || ($x_medio_pago_id == "")) { $x_medio_pago_id = 0;} // Set default value ?>
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
?></td>
  </tr>
  <tr>
    <td class="ewTableHeaderThin">Banco y Cta:</td>
    <td class="ewTableAltRow"><?php if (!(!is_null($x_banco_id)) || ($x_banco_id == "")) { $x_banco_id = 0;} // Set default value ?>
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
		$x_medio_pago_idList .= ">" . $datawrk["nombre"] . " - " .$datawrk["cuenta"]. "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_medio_pago_idList .= "</select>";
echo $x_medio_pago_idList;
?></td>
  </tr>
  <tr>
    <td class="ewTableHeaderThin">Referencia</td>
    <td class="ewTableAltRow"><input type="text" name="x_referencia_pago" id="x_referencia_pago" size="30" maxlength="50" value="<?php echo htmlspecialchars(@$x_referencia_pago) ?>" /></td>
  </tr>
  <tr>
    <td class="ewTableHeaderThin">Tarjeta Num.</td>
    <td class="ewTableAltRow"><input type="text" name="x_tdp" id="x_tdp" size="20" maxlength="50" value="<?php echo htmlspecialchars(@$x_tdp) ?>" /></td>
  </tr>
  <tr>
    <td width="110" class="ewTableHeaderThin"><span>Status</span></td>
    <td width="678" class="ewTableAltRow"><span> <span>
      <input type="hidden" id="x_credito_id" name="x_credito_id" value="<?php echo htmlspecialchars(@$x_credito_id); ?>" />
      </span>
      
          <?php  
$x_credito_status_idList = "<select name=\"x_credito_status_id\">";
$x_credito_status_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT credito_status_id, descripcion FROM credito_status";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_credito_status_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["credito_status_id"] == @$x_credito_status_id) {
			$x_credito_status_idList .= "' selected";
			$x_descripcion_credito = $datawrk["descripcion"]; 
		}
		$x_credito_status_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_credito_status_idList .= "</select>";
if($_SESSION["php_project_esf_status_UserRolID"] == 1){
echo $x_credito_status_idList;
		  }else{
			  echo $x_descripcion_credito;
			  }
?>
    </span></td>
  </tr>
</table>
<br />
<input type="button" name="Action" value="MODIFICAR" onclick="modifica(1)" />
<p>
<table width="700" cellpadding="1" cellspacing="2" border="0" class="phpmaker" >
  <tr>
    <td class="ewTableHeaderThin">Fondo:</td>
    <td class="ewTableAltRow">
    
<?php 
$sSqlWrk = "select fondeo_empresa.nombre from  fondeo_colocacion join fondeo_credito on fondeo_credito.fondeo_credito_id = fondeo_colocacion.fondeo_credito_id join fondeo_empresa on fondeo_empresa.fondeo_empresa_id = fondeo_credito.fondeo_empresa_id where fondeo_colocacion.credito_id = $x_credito_id ";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	$x_fondo = $rowwrk["nombre"];								
}else{
	$x_fondo = "FONDOS PROPIOS";										
}
@phpmkr_free_result($rswrk);

echo $x_fondo; 
?>
    
    
    </td>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableHeaderThin">Otrogamiento</td>
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
    </span>    
    </td>
  </tr>
  <tr>
    <td width="80" class="ewTableHeaderThin"><span>Cr&eacute;dito No:</span></td>
    <td width="98" class="ewTableAltRow"><span> <?php echo $x_credito_num; ?> </span></td>
    <td width="10" class="ewTableAltRow">&nbsp;</td>
    <td width="100" class="ewTableHeaderThin">Periodos de gracia</td>
    <td width="220" class="ewTableAltRow"><span>
    <input type="text" name="x_periodos_gracia" value="<?php echo $x_periodos_gracia; ?>" maxlength="5" size="5"  />

</span>
</td>
  </tr>
  <tr>
    <td class="ewTableHeaderThin">Cliente No:</td>
    <td class="ewTableAltRow"><span>
    <?php
	echo $x_cliente_num;	  
	?>
    </span></td>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableHeaderThin"><span>Tipo</span></td>
    <td class="ewTableAltRow"><span>
      <?php
if ((!is_null($x_credito_tipo_id)) && ($x_credito_tipo_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `credito_tipo`";
	$sTmp = $x_credito_tipo_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `credito_tipo_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_credito_tipo_id = $x_credito_tipo_id; // Backup Original Value
$x_credito_tipo_id = $sTmp;
?>
      <?php echo $x_credito_tipo_id; ?>
      <?php $x_credito_tipo_id = $ox_credito_tipo_id; // Restore Original Value ?>
    </span></td>
  </tr>
  <tr>
    <td class="ewTableHeaderThin">Status</td>
    <td class="ewTableAltRow"><?php
if ((!is_null($x_credito_status_id)) && ($x_credito_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `credito_status`";
	$sTmp = $x_credito_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `credito_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_credito_status_id = $x_credito_status_id; // Backup Original Value
$x_credito_status_id = $sTmp;
?>
      <?php echo $x_credito_status_id; ?>
      <?php $x_credito_status_id = $ox_credito_status_id; // Restore Original Value ?>
    </td>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableHeaderThin">Vencimiento</td>
    <td class="ewTableAltRow"><?php echo FormatDateTime($x_fecha_vencimiento,7); ?></td>
  </tr>
  <tr>
    <td colspan="5" class="ewTableHeaderThin">&nbsp;</td>
  </tr>
  <tr>
    <td class="ewTableHeaderThin"><span>Importe</span></td>
    <td class="ewTableAltRow"><span>
    <?php //echo "$".FormatNumber(@$x_importe,2,0,0,1); ?>
    <input type="text" name="x_importe" id="x_importe" value="<?php echo @FormatNumber($x_importe,2,0,0,0); ?>" onKeyPress="return solonumeros(this,event)">
    </span></td>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableHeaderThin">Tipo de Calculo</td>
    <td class="ewTableAltRow"><input type="radio" name="x_tipo_calculo" value="1" <?php if($x_tipo_calculo == 1){ echo "checked";} ?> />
Promediado
  <input type="radio" name="x_tipo_calculo" value="2" <?php if($x_tipo_calculo == 2){ echo "checked";} ?> />
Tradicional</td>
  </tr>
  <tr>
    <td class="ewTableHeaderThin">Num. pagos </td>
    <td class="ewTableAltRow">
	<input name="x_num_pagos" type="text" id="x_num_pagos" value="<?php echo @$x_num_pagos; ?>" size="8" maxlength="5" />    </td>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableHeaderThin">Tasa anual</td>
    <td class="ewTableAltRow"><input name="x_tasa" type="text" id="x_tasa" value="<?php echo FormatNumber(@$x_tasa,2,0,0,0) ?>" size="8" maxlength="5" />
%</td>
  </tr>
  <tr>
    <td class="ewTableHeaderThin">IVA</td>
    <td class="ewTableAltRow"><input type="radio" name="x_iva" value="1" <?php if($x_iva == 1){ echo "checked";} ?> />
      &nbsp;SI
      &nbsp;&nbsp;
      <input type="radio" name="x_iva" value="2" <?php if($x_iva == 2){ echo "checked";} ?>/>
      &nbsp;NO </td>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableHeaderThin">Tasa moratoria</td>
    <td class="ewTableAltRow"><input name="x_tasa_moratoria" type="text" id="x_tasa_moratoria" value="<?php echo htmlspecialchars(@$x_tasa_moratoria) ?>" size="8" maxlength="5" />
%</td>
  </tr>
  <tr>
    <td class="ewTableHeaderThin">Plazo</td>
    <td class="ewTableAltRow"><?php 
		$sSqlWrk = "SELECT descripcion FROM plazo where plazo_id = $x_plazo";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo $datawrk["descripcion"];
		@phpmkr_free_result($rswrk);
		?>
      <input type="hidden" name="x_plazo" id="x_plazo" value="<?php echo @$x_plazo; ?>" /></td>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableHeaderThin">Forma de Pago </td>
    <td class="ewTableAltRow"><?php 

/*
		$sSqlWrk = "SELECT descripcion FROM forma_pago where forma_pago_id = $x_forma_pago_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo $datawrk["descripcion"];
		@phpmkr_free_result($rswrk);
*/		
		?>
      <!---       <input type="hidden" name="x_forma_pago_id" id="x_forma_pago_id" value="<?php //echo @$x_forma_pago_id; ?>" />   
 --->
      <?php
		$x_estado_civil_idList = "<select name=\"x_forma_pago_id\" class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `forma_pago_id`, `descripcion` FROM `forma_pago`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["forma_pago_id"] == @$x_forma_pago_id) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?></td>
  </tr>
  <tr>
    <td class="ewTableHeaderThin">Comision Apertura</td>
    <td class="ewTableAltRow"><input name="x_tdp2" type="text" id="x_tdp2" value="<?php echo @$x_tdp; ?>" size="10" maxlength="10" />
%</td>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableHeaderThin">Registro Público</td>
    <td class="ewTableAltRow"><input name="x_tdp3" type="text" id="x_tdp3" value="<?php echo @$x_tdp; ?>" size="13" maxlength="13" /></td>
  </tr>
  <tr>
    <td class="ewTableHeaderThin">Seguro de daños</td>
    <td class="ewTableAltRow"><input name="x_tdp4" type="text" id="x_tdp4" value="<?php echo @$x_tdp; ?>" size="13" maxlength="13" /></td>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableHeaderThin">Notario</td>
    <td class="ewTableAltRow"><input name="x_tdp5" type="text" id="x_tdp5" value="<?php echo @$x_tdp; ?>" size="13" maxlength="13" /></td>
  </tr>
  <tr>
    <td class="ewTableHeaderThin">Otros</td>
    <td class="ewTableAltRow"><input name="x_tdp6" type="text" id="x_tdp6" value="<?php echo @$x_tdp; ?>" size="13" maxlength="13" /></td>
    <td class="ewTableAltRow">&nbsp;</td>
    <td class="ewTableHeaderThin">Desc. otros</td>
    <td class="ewTableAltRow"><input name="x_tdp7" type="text" id="x_tdp7" value="<?php echo @$x_tdp; ?>" size="40" maxlength="40" /></td>
  </tr>
  
  <tr>
    <td colspan="5" class="ewTableRow"><label> <!--<?php if($x_disable_edit == "true"){ ?> disabled="disabled" <?php } ?>-->
      <input type="button" name="btnrecalcular" id="btnrecalcular" value="Recalcular Cr&eacute;dito" onclick="modifica(2)" />
    </label></td>
  </tr>
  <tr>
    <td colspan="5" class="ewTableHeaderThin"><div align="center">Vencimientos</div></td>
  </tr>
  <tr>
    <td colspan="5" class="ewTableAltRow"><label><!--<?php if($x_disable_edit == "true"){ ?> disabled="disabled" <?php } ?>-->
      <input type="button" name="btn_rest" id="btn_rest" value="Restructurar cartera Vencida" onclick="javascript: document.getElementById('vencdesk').src = 'php_restructura.php?credito_id=<?php echo $x_credito_id; ?>';"  />
    </label></td>
  </tr>
  <tr>
    <td colspan="5" class="ewTableAltRow">
    <iframe id="vencdesk" src="php_vencimientolist.php?credito_id=<?php echo $x_credito_id; ?>" name="vencimientos" frameborder="0"  style="margin-left:2px; width:800px; height:700px; border:0 " allowtransparency="true"></iframe>    </td>
  </tr>
</table>
<p>
<p>

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
		$GLOBALS["x_tipo_calculo"] = $row["tipo_calculo"];
		if(empty($GLOBALS["x_tipo_calculo"])){
			$GLOBALS["x_tipo_calculo"] = 1;
		}
		$GLOBALS["x_tasa"] = $row["tasa"];
		$GLOBALS["x_iva"] = $row["iva"];		
		if(empty($GLOBALS["x_iva"])){
			$GLOBALS["x_iva"] = 2;
		}
		$GLOBALS["x_plazo"] = $row["plazo_id"];
		$GLOBALS["x_fecha_vencimiento"] = $row["fecha_vencimiento"];
		$GLOBALS["x_tasa_moratoria"] = $row["tasa_moratoria"];
		$GLOBALS["x_medio_pago_id"] = $row["medio_pago_id"];
		$GLOBALS["x_banco_id"] = $row["banco_id"];		
		$GLOBALS["x_referencia_pago"] = $row["referencia_pago"];
		$GLOBALS["x_forma_pago_id"] = $row["forma_pago_id"];		
		$GLOBALS["x_num_pagos"] = $row["num_pagos"];				
		$GLOBALS["x_tdp"] = $row["tarjeta_num"];	
		$GLOBALS["x_periodos_gracia"] = $row["periodos_gracia"];	
							
	}
	
	
	// seleccionamos el primer vencimiento para saber la fech, esta fecha no puede pasr de un mes  despues de otorgado el credito si pas de un mes entonces no se podra editar el credito
	// asi lo pidio javier
	// ejemplo si el credito se otorgo el 9 de noviembre del 2011, la fecha del primer vencimeinto no puede ser mayor al 9 de diciembre del 2011, si es mayor entonces no se deb poder editarel credito
	// ponemos los botos de reestructura y de recalculo en disable.
	
	$sqlf = "SELECT fecha_vencimiento FROM vencimiento WHERE credito_id =".$GLOBALS["x_credito_id"]." ORDER BY vencimiento_num ";
	$rsf = phpmkr_query($sqlf, $conn) or die("error al seleccionar todos los creditos".phpmkr_error()."sql:".$sqlf);
	$rowf = phpmkr_fetch_array($rsf);
	$x_fecha_primer_vencimiento = $rowf["fecha_vencimiento"];
	include_once("utilerias/datefunc.php");
#	echo "fecha _primer_vencimiento =".$x_fecha_primer_vencimiento."<br>";
#    echo "fecha _primer_otorgamiento =".$GLOBALS["x_fecha_otrogamiento"]."<br>";
	 $fecha_ant = strtotime(ConvertDateToMysqlFormat($GLOBALS["x_fecha_otrogamiento"]));				
	 $fecha_act = DateAdd('m',1,$fecha_ant);
	 $fecha_act = strftime('%Y-%m-%d',$fecha_act);
	 
#	 echo "fecha de otorgamiento mas 1 mes". $fecha_act."<br>";
	 
	 if($x_fecha_primer_vencimiento > $fecha_act  ){
		 // si la fecha del primer vencimeinto es mayor a la fecha actual entonces botton = disable;
		 $GLOBALS[x_disable_edit] = "true";
		 echo "La fecha del primer vencimiento del credito es mayor a la fecha de otorgamiento mas un mes: NO dejara editar el credito.";
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
		$bEditData = false; // Update Failed
	}else{
		$theValue = ($GLOBALS["x_credito_status_id"] != "") ? intval($GLOBALS["x_credito_status_id"]) : "NULL";
		$fieldList["`credito_status_id`"] = $theValue;
		$theValue = ($GLOBALS["x_medio_pago_id"] != "") ? intval($GLOBALS["x_medio_pago_id"]) : "NULL";
		$fieldList["`medio_pago_id`"] = $theValue;

		$theValue = ($GLOBALS["x_banco_id"] != "") ? intval($GLOBALS["x_banco_id"]) : "0";
		$fieldList["`banco_id`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_pago"]) : $GLOBALS["x_referencia_pago"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`referencia_pago`"] = $theValue;
		$theValue = ($GLOBALS["x_credito_num"] != "") ? " '" . $GLOBALS["x_credito_num"] . "'" : "0";
		$fieldList["`credito_num`"] = $theValue;

		$theValue = ($GLOBALS["x_cliente_num"] != "") ? " '" . $GLOBALS["x_cliente_num"] . "'" : "0";
		$fieldList["`cliente_num`"] = $theValue;


		$theValue = ($GLOBALS["x_tdp"] != "") ? " '" . $GLOBALS["x_tdp"] . "'" : "NULL";
		$fieldList["`tarjeta_num`"] = $theValue;
		
		// cobranza externa 4
		if($GLOBALS["x_credito_status_id"] == 4 ){
			$theValue = ($GLOBALS["x_fecha_edit_status"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_edit_status"]) . "'" : "Null";
			$fieldList["`fecha_cobranza_externa`"] = $theValue;			
			}
			
		//incobrable	
		if($GLOBALS["x_credito_status_id"] == 5 ){
			$theValue = ($GLOBALS["x_fecha_edit_status"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_edit_status"]) . "'" : "Null";
			$fieldList["`fecha_incobrable`"] = $theValue;			
			}
			

		// update
		$sSql = "UPDATE `credito` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE " . $sWhere;
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);


	$sSql = "select solicitud_id from credito where credito_id = $x_credito_id";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_solicitud_id = $row["solicitud_id"];
	phpmkr_free_result($rs);	
	
	$sSql = "SELECT cliente_id FROM solicitud_cliente where solicitud_id = $x_solicitud_id";
	$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row2 = phpmkr_fetch_array($rs2);
	$x_cliente_id = $row2["cliente_id"];
	phpmkr_free_result($rs2);				
		
	if(!empty($x_cliente_id)){
		$sSql = "update cliente set cliente_num = '".$GLOBALS["x_cliente_num"]."' where cliente_id = $x_cliente_id";
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	}	

		
		$bEditData = true; // Update Successful
	}
	return $bEditData;
}


//-------------------------------------------------------------------------------
// Function recalData
function RecalData($conn)
{
	global $x_credito_id;
	
	#echo "estamos en recalcula credito";
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
		$bRecalData = false; // Update Failed
	}else{
	
		// Field fecha_otrogamiento
		$theValue = ($GLOBALS["x_fecha_otrogamiento"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_otrogamiento"]) . "'" : "Null";
		$fieldList["`fecha_otrogamiento`"] = $theValue;
		
		#echo "fecha de otrogamiento".$GLOBALS["x_fecha_otrogamiento"] ."<br>";

		$theValue = ($GLOBALS["x_fecha_primerpago"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_primerpago"]) . "'" : "Null";
		$fieldList["`fecha_primerpago`"] = $theValue;

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
	
		// 
		$theValue = ($GLOBALS["x_num_pagos"] != "") ? intval($GLOBALS["x_num_pagos"]) : "0";
		$fieldList["`num_pagos`"] = $theValue;
	
		// update
		$sSql = "UPDATE `credito` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE " . $sWhere;
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);


		//RE GENERA VENCIMIENTOS
	
		$sSql = "delete from vencimiento  where credito_id = $x_credito_id";
		phpmkr_query($sSql, $conn);
	

		include_once("utilerias/datefunc.php");
	
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
	#	echo "forma de pago id".$GLOBALS["x_forma_pago_id"]."<br>";
	#	echo "forma de pago valor".$x_forma_pago."<br>";
	//	$x_num_pagos = $x_plazo * $x_forma_pago;
		$x_num_pagos = $GLOBALS["x_num_pagos"];	

	#	echo "numero de pagos".$x_num_pagos."<br>";
		
		$GLOBALS["x_importe"] = str_replace(",","",$GLOBALS["x_importe"]);
		$temptime = strtotime(ConvertDateToMysqlFormat($GLOBALS["x_fecha_otrogamiento"]));	
		$x_interes = 0;
		$x_pago_act = 1;


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

#echo "tipo de calculo".$GLOBALS["x_tipo_calculo"]."<br>"; 
/////// calculo promediado	
if($GLOBALS["x_tipo_calculo"] == 1){

echo "entro a tipo de calculo uno <br>";
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
	
		$sSql = "insert into vencimiento values(0,$x_credito_id, $x_pago_act,1, '$fecha_act', 0, 0, 0, 0, 0, 0,NUll)";
		$x_result = phpmkr_query($sSql, $conn);
		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}
		
		$temptime = strtotime($fecha_act);
		$x_pago_act++;	
	}		


	$fecha_act = $temptime;


	$x_total_venc = round($GLOBALS["x_importe"] / $x_interes);
	$x_capital_venc = ($GLOBALS["x_importe"] / $x_num_pagos);
	$x_interes_venc = round($x_total_venc - $x_capital_venc);
	if($GLOBALS["x_iva"] == 1){
		$x_iva_venc = round($x_interes_venc * .16);	
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

#echo "entro a tipo de calculo dos <br>";

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
	$x_fecha_primerpago = $GLOBALS["x_fecha_primerpago"];
	$x_fecha_abonocap = $GLOBALS["x_fecha_abonocap"];
	$x_periodos_gracia = $GLOBALS["x_periodos_gracia"];
	$x_importe = $GLOBALS["x_importe"];
	$x_vencimiento_actual = 0;
 # echo "fecha primer pago".$x_fecha_primerpago."<br>";

	//dias de gracia - calcula saldo de interes para aplicar en el primer vencmiemtno
	$x_saldo_intereses = 0;
	if($x_fecha_primerpago != $x_fecha_otrogamiento && false){
		
	#	echo "entro al primer if<br>";

		$x_fecha_ant = strtotime(ConvertDateToMysqlFormat($x_fecha_otrogamiento));
		$fecha_act = strtotime(ConvertDateToMysqlFormat($x_fecha_primerpago));
		
	#	echo "fecha anteriror..... es la fecha d otrogamiento ".$x_fecha_ant."<br>";
	#	echo "fecha actual es la fecha del primer pago".$fecha_act."<br>";

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
	#	echo "segundo if... period de gracia mayor que 0<br>";
		$x_venc_num_sac = 1;		
		$fecha_ant = $x_fecha_otrogamiento;
		$fecha_act = ConvertDateToMysqlFormat($x_fecha_otrogamiento);		
	#	echo "fecha anterior ..".$fecha_ant."<br>";
	#	echo "fecha actual ..".$fecha_act."<br>";
	#	echo "las dos guardan la fecha de otrogaiento";
		$x_contador = 1;
		
//		while(strtotime($fecha_act) < strtotime(ConvertDateToMysqlFormat($x_fecha_abonocap)) || strtotime($fecha_act) == strtotime(ConvertDateToMysqlFormat($x_fecha_abonocap))){
		while($x_contador <= $x_periodos_gracia || $x_contador == $x_periodos_gracia){
			
			$fecha_ant = strtotime(ConvertDateToMysqlFormat($fecha_ant));				
			$fecha_act = DateAdd('w',$x_forma_pago,$fecha_ant);
			$fecha_act = strftime('%Y-%m-%d',$fecha_act);
			$fecha_act = strtotime(ConvertDateToMysqlFormat($fecha_act));

			echo "fecha actual depsues de incrementar los dias".$fecha_act ."<br>";
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
			echo "fecha actual con formato y-m-d".$fecha_act."<br>";

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
	#echo "fecha abono a capital es igual a fecha actual ".$x_fecha_abonocap ."<br>";
	
	//calculo de pago de vencimientos
	$temptime = $x_fecha_abonocap;
//	$temptime = strtotime(ConvertDateToMysqlFormat($x_fecha_abonocap));		
	$x_pago_act = 1;
	while($x_pago_act < ($x_venc_num_cac + 1)){
		$temptime = DateAdd('w',$x_forma_pago,$temptime);

		$fecha_act = strftime('%Y-%m-%d',$temptime);			
		$x_dia = strftime('%A',$temptime);

 #echo "fecha actual es fecha actual mas los dias del pago-----------".$fecha_act."<br>";
		//Validar domingos
		if($x_dia == "SUNDAY"){
			echo "entro a dia sundat";
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
#	echo "fecha abono a capital<<<<<<<<<<<<".ConvertDateToMysqlFormat($x_fecha_abonocap)."<br>";
	
	$temptime = strtotime(ConvertDateToMysqlFormat($GLOBALS["x_fecha_otrogamiento"]));		
	#echo "temptime".strftime('%Y-%m-%d',$temptime)."<br>";
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

	#	echo "dias agregados".$x_forma_pago."<br>";
		$fecha_act = DateAdd('w',$x_forma_pago,$temptime);
		$fecha_act = strftime('%Y-%m-%d',$fecha_act);	
	#	echo "fecha actual".$fecha_act."<br>";
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
		
		
#		echo "sql :".$sSql."<br>";
		
		$temptime = strtotime($fecha_act);
		$x_saldo = $x_saldo - $x_capital_pp;	
		$x_venc_num++;	
		$x_pago_act++;	
	}		

	//$fecha_act = strftime('%Y-%m-%d',$fecha_act);

///////////////// fin calculo no promediado


}



//		$fecha_act = strftime('%Y-%m-%d',$fecha_act);			


		$sSql = "update credito set fecha_vencimiento = '$fecha_act' where credito_id = $x_credito_id";
		$x_result = phpmkr_query($sSql, $conn);
		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}
	
		
		$bRecalData = true; // Update Successful
	}
	
	return $bRecalData;
}
?>
