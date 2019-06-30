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
	#echo $x_solicitud_id;
// Get action
$sAction = @$_POST["a_add"];
if (($sAction == "") || ((is_null($sAction)))) {
	if ($bCopy) {
		$sAction = "C"; // Copy record
	}else{
		$sAction = "S"; // Display blank record
	}
}else{


	foreach($_POST as $campo => $valor){
		$$campo = $valor;
		}	
	// Get fields from form	
	$x_credito_status_id = 1;
		
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
		$GLOBALS["x_tdp"] = $row["tarjeta_referenciada"];
		$GLOBALS["x_referencia_pago"] = $row["numero_cheque"];		
		#echo "BUSCA LA TAREJETA DE CREDITO".$sSql."<BR>";				
		phpmkr_free_result($rs);	
if($GLOBALS["x_credito_tipo_id"] > 0){
		$sSql = "SELECT cliente_id FROM solicitud_cliente where solicitud_id = $x_solicitud_id";
		$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row = phpmkr_fetch_array($rs);
		$GLOBALS["x_cliente_id"] = $row["cliente_id"];		
		phpmkr_free_result($rs);			
if(empty($GLOBALS["x_tdp"])){
		$sSql = "SELECT credito.tarjeta_num, cliente.cliente_num FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id where cliente.cliente_id = $x_cliente_id and not isnull(credito.tarjeta_num) limit 1";
		$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row = phpmkr_fetch_array($rs);			
		#echo "busca tarjeta".$sSql."<br>";
		$GLOBALS["x_tdp"] = $row["tarjeta_num"];
		$GLOBALS["x_cliente_num"] = $row["cliente_num"];		
		phpmkr_free_result($rs);		
}

}
		#seleccionamos los adtos del ultimo credito credito ara ver que dia liquido y poder saber de cuanto es el amuento
		$SQLcREDITOaNTERIOR = "	SELECT * FROM credito WHERE solicitud_id = $x_solicitud_id and credito_status_id in (1,3)";
		$rsCreditoAnterior = phpmkr_query($SQLcREDITOaNTERIOR,$conn) or die ("Error al seleccionar los adtos del redito anterior".phpmkr_error()."sql:".$SQLcREDITOaNTERIOR);
		$rowCreditpAnterior = phpmkr_fetch_array($rsCreditoAnterior);
		$x_credito_anterior_id = $rowCreditpAnterior["credito_id"];
		$x_monto_credito_anterior = $rowCreditpAnterior["importe"]; 
		$x_importe_nuevo_credito = 0;
		 
		 $sqlVencimiento  = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_anterior_id ";
		 $rsVencimiento = phpmkr_query($sqlVencimiento,$conn) or die ("Error al seleccionar los datos del vencimiento anterior".phpmkr_error()."sql:".$sqlVencimiento);
		 $rowVencimiento = phpmkr_fetch_array($rsVencimiento);
		 $x_fecha_vencimiento = $rowVencimiento["fecha_vencimiento"];
		  
		// buscamos la ultima fecha de pago de ese credito...
		$sqlUltimoPago="SELECT recibo . * 
						FROM recibo
						JOIN recibo_vencimiento ON recibo_vencimiento.recibo_id = recibo.recibo_id
						JOIN vencimiento ON vencimiento.vencimiento_id = recibo_vencimiento.vencimiento_id
						WHERE vencimiento.credito_id = $x_credito_anterior_id ORDER BY fecha_registro DESC  LIMIT 0,1";
		$rsUltimo_pago = phpmkr_query($sqlUltimoPago,$conn)or die ("Error al seleccionar el ultimo pago del cliente".phpmkr_error()."sql: ".$sqlUltimoPago);
		$rowUltomoPago = phpmkr_fetch_array($rsUltimo_pago);
		$x_fecha_ultimo_pago = $rowUltomoPago["fecha_registro"];	
		
		//echo "fecha_ultimo pago".$x_fecha_ultimo_pago. "fechja de vencimeinto ".$x_fecha_vencimiento."<br>";
		if(!empty($x_fecha_ultimo_pago)){
			// si la fecha de pago no es vacia se hace el camlu de los dias en que pago
			$x_today = date("Y-m-d");
		$sqlDif = "SELECT DATEDIFF('$x_fecha_ultimo_pago','$x_fecha_vencimiento') AS dias";
		$rsDif = phpmkr_query($sqlDif, $conn)or die ("Error al seleccionar la dif de dias".phpmkr_error()."sql:".$sqlDif);
		$rowDif = phpmkr_fetch_array($rsDif);
		$x_dias_pago = $rowDif["dias"];
			
			}
		$x_importe_calculado = 0;
		if($x_dias_pago < 11){
			// aumenta 150
			$x_importe_nuevo_credito = $x_monto_credito_anterior + 150;
			$x_importe_calculado = 1;
			}else if($x_dias_pago < 21){
				// aumenta 100
				$x_importe_nuevo_credito = $x_monto_credito_anterior + 100;
				$x_importe_calculado = 1;
				}else if($x_dias_pago > 20){
					// aumenta 50
					$x_importe_nuevo_credito = $x_monto_credito_anterior + 50;
					$x_importe_calculado = 1;
					}
							
		
		// el campo de penalizacion saldra lleno por default
		$sqlPenalizacion = " SELECT * FROM penalizacion where forma_pago_id = ".$GLOBALS["x_forma_pago_id"]." ";
		$rsPenalizacion = phpmkr_query($sqlPenalizacion, $conn) or die ("Error al selccionar los detos de penalizacion".phpmkr_error()."sql:". $sqlPenalizacion);
		$rowPenalizacion  = phpmkr_fetch_array($rsPenalizacion);
		$x_p_penalizacion_id = $rowPenalizacion["penalizacion_id"];
		$x_p_forma_pago_id = $rowPenalizacion["penalizacion_id"];
		$x_p_penalizacion_base = $rowPenalizacion["penalizacion_base"];
		$x_p_monto_base = $rowPenalizacion["monto_base"];
		$x_p_incremento_penalizacion = $rowPenalizacion["incremento_penalizacion"];
		$x_p_rango_para_incremento = $rowPenalizacion["rango_para_incremento"];
		if($GLOBALS["x_importe_solicitado"] > $x_p_monto_base){
		$x_m = $GLOBALS["x_importe_solicitado"] - $x_p_monto_base;		
		$x_m_2 = $x_m / $x_p_rango_para_incremento;		
		$x_m_2 = ceil($x_m_2);			
		$x_m_3 = round($x_m_2 * $x_p_incremento_penalizacion);		
		$x_m_4 = 250;
		}else{
			// si es menor la penalizacio es la indicada en la base
			$x_m_4 = $x_p_penalizacion_base;			
			
			}
		$x_m_4 = 250;
		#echo "penalizacion ".$x_m_4."<br>"; 
		
		
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
<script type="text/javascript" src="scripts/jquery-1.4.js"></script>
<script type="text/javascript" src="scripts/jquery-ui-1.8.custom.min.js"></script>
<script type="text/javascript" src="scripts/jquery.themeswitcher.js"></script>

<script language="javascript">
$(document).ready(function(e) {
	
	if(confirm("EL CLIENTE DESEA AUMENTAR EL MONTO DEL CREDITO ?")){
		alert("se da el aumento en el credito");
		var valor_calculado = $("#x_importe_nuevo_credito").val();
		$("#x_importe").attr('value', valor_calculado);
		}else{
			$("#x_importe_calculado").attr('value', 0);
			}
	$('#x_num_pagos').change(function(evento){
		var garantia = $("input[name='x_garantia_liquida']:checked").val(); 
		var numero_pagos = $('#x_num_pagos').val();
		var forma_pago_id = $('#x_forma_pago_id').val();
		if(garantia == 1){
		if(forma_pago_id == 1){
			// es semanal solo pueden ser 20 o 40 pagos
			if((numero_pagos != 20) && (numero_pagos != 40)){
				alert("El numero de pagos SEMANALES para aplicar garantia liquida solo puede ser 20 o 40 ");
				 $("input[name='x_garantia_liquida']").attr('checked', false);
				  $("#x_garantia_liquida_no").attr('checked', true);					
				}		
			}
			
		if(forma_pago_id == 2){
			// es semanal solo pueden ser 20 o 40 pagos
			if((numero_pagos != 12) && (numero_pagos != 24)){
				alert("El numero de pagos CATROCENALES para  APLICAR GARANTIA LIQUIDA solo puede ser 20 o 40 ");
				 $("input[name='x_garantia_liquida']").attr('checked', false);
				 $("#x_garantia_liquida_no").attr('checked', true);				
				}				
			}	
			
		if(forma_pago_id == 4){
			// es semanal solo pueden ser 20 o 40 pagos
			if((numero_pagos != 12) && (numero_pagos != 24)){
				alert("El numero de pagos QUINCENALES para APLICAR GARANTIA LIQUIDA solo puede ser 20 o 40 ");
				 $("input[name='x_garantia_liquida']").attr('checked', false);
				 $("#x_garantia_liquida_no").attr('checked', true);				
				}		
			}	
			
		if(forma_pago_id == 3){
			// es semanal solo pueden ser 20 o 40 pagos
			if((numero_pagos != 12) ){
				alert("El numero de pagos MENSUALES para APLICAR GARANTIA LIQUIDA solo puede ser 20 o 40 ");
				 $("input[name='x_garantia_liquida']").attr('checked', false);
				 $("#x_garantia_liquida_no").attr('checked', true);
				}		
			
			}	
		}
		});
	
	
    $("input[name='x_garantia_liquida']").change(function(evento){
	var garantia = $("input[name='x_garantia_liquida']:checked").val(); 
	var numero_pagos = $('#x_num_pagos').val();
	var forma_pago_id = $('#x_forma_pago_id').val();
	
	if(garantia == 1){
		if(forma_pago_id == 1){
			// es semanal solo pueden ser 20 o 40 pagos
			if((numero_pagos != 20) && (numero_pagos != 40)){
				alert("El numero de pagos SEMANALES para aplicar garantia liquida solo puede ser 20 o 40 ");
				 $("input[name='x_garantia_liquida']").attr('checked', false);
				  $("#x_garantia_liquida_no").attr('checked', true);					
				}		
			}
			
		if(forma_pago_id == 2){
			// es semanal solo pueden ser 20 o 40 pagos
			if((numero_pagos != 12) && (numero_pagos != 24)){
				alert("El numero de pagos CATROCENALES para  APLICAR GARANTIA LIQUIDA solo puede ser 20 o 40 ");
				$("input[name='x_garantia_liquida']").attr('checked', false);
				 $("#x_garantia_liquida_no").attr('checked', true);				
				}				
			}	
			
		if(forma_pago_id == 4){
			// es semanal solo pueden ser 20 o 40 pagos
			if((numero_pagos != 12) && (numero_pagos != 24)){
				alert("El numero de pagos QUINCENALES para APLICAR GARANTIA LIQUIDA solo puede ser 20 o 40 ");
				$("input[name='x_garantia_liquida']").attr('checked', false);
				 $("#x_garantia_liquida_no").attr('checked', true);				
				}		
			}	
			
		if(forma_pago_id == 3){
			// es semanal solo pueden ser 20 o 40 pagos
			if((numero_pagos != 12) ){
				alert("El numero de pagos MENSUALES para APLICAR GARANTIA LIQUIDA solo puede ser 20 o 40 ");
				$("input[name='x_garantia_liquida']").attr('checked', false);
				 $("#x_garantia_liquida_no").attr('checked', true);
				}		
			
			}	
		}
	});
});


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

if (validada == true && EW_this.x_garantia_liquida && !EW_hasValue(EW_this.x_garantia_liquida, "RADIO" )) {
	if (!EW_onError(EW_this, EW_this.x_garantia_liquida, "RADIO", "Indique si hay GARANTIA LIQUIDA o no."))
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

// validar si la garantia liquida este seleccioanda si es asi
//validar que los pagos solo sean los permitido para generar garantia liquida

if (validada == true){
	EW_this.a_add.value = "A";
	EW_this.submit();
}

}

//-->
</script>

<script>
function  montoValida(monto){
	var monto_validado = monto;
	
	var importe_calculado = document.getElementById("x_importe_calculado").value;	
	if(importe_calculado == 1){
		// EL IMPORTE FUE CALCULADO NO SE DEBE MODIFICAR
		
		var monto_importe_calculado = document.getElementById("x_importe_nuevo_credito").value;
		if(monto_validado != monto_importe_calculado){
			alert("No puede cambiar el monto que se calculo para el credito");
			document.getElementById("x_importe").value = monto_importe_calculado; 			
			}
		
		}else{
			// si no se calculo el importe; solo se valida que le nuevo importe no sea mayor a 150 de aumento..
			var importe_anterior = document.getElementById("x_importe_credito_anterior").value; 
			var maxi = 150;		
			var importe_maximo = parseInt(importe_anterior) + parseInt(maxi);
			if(monto_validado > importe_maximo){
				alert("EL MONTO A OTORGAR NO PUEDE SER MAYOR A" + importe_maximo);
				document.getElementById("x_importe").value = importe_anterior;
				}
			if(monto_validado > 3000){
					alert("EL MONTO NO PUEDE SER MAYOR DE $3000");
					document.getElementById("x_importe").value = 3000;
				}
			
			}
		
	
	}

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
<p><span class="phpmaker">CREDITOS TIPO M<br>
  <br>
    <a href="php_solicitudlist.php">listado de cr&eacute;ditos</a></span></p>
<form name="creditoadd" id="creditoadd" action="" method="post">
<p>
<input type="hidden" name="a_add" value="A">
<input type="hidden" name="x_monto_linea_credito" id="x_monto_linea_credito" value=""  />
<input type="hidden" name="x_importe_nuevo_credito" id="x_importe_nuevo_credito" value="<?php echo $x_importe_nuevo_credito;?>" />
<input type="hidden" name="x_importe_calculado" id="x_importe_calculado" value="<?php echo $x_importe_calculado;?>" />
<input type="hidden" name="x_importe_credito_anterior" id="x_importe_credito_anterior" value="<?php echo $x_monto_credito_anterior;?>" />
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
	  <td class="ewTableHeaderThin" width="207">Solicitud</td>
	  <td class="ewTableAltRow"><span>
      <input type="hidden" name="x_solicitud_id" value="<?php echo $x_solicitud_id; ?>"  />
        <?php
			echo $x_solicitud_id;
		?>
      </span></td>
	  </tr>
	<tr>
	  <td height="23" colspan="2" class="ewTableAltRow">&nbsp;</td>
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
	  <td class="ewTableAltRow"><?php
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
		<td width="207" class="ewTableHeaderThin"><span>Tipo de Cr&eacute;dito </span></td>
		<td width="781" class="ewTableAltRow"><span>
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
	  <td class="ewTableHeaderThin"><span>Fecha de otorgamiento</span></td>
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
		<td class="ewTableHeaderThin"><span>Importe</span></td>
		<td class="ewTableAltRow"><span>		
<input type="text" name="x_importe" id="x_importe" value="<?php echo FormatNumber(@$x_importe_solicitado,2,0,0,1); ?>" onchange="montoValida(this.value);">
</span></td>
	</tr>
	
	<tr>
		<td class="ewTableHeaderThin"><span>Plazo</span></td>
		<td class="ewTableAltRow"><span>
		<?php 
		$sSqlWrk = "SELECT plazo_id, descripcion FROM plazo where plazo_id = 11 ";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo $datawrk["descripcion"];
		@phpmkr_free_result($rswrk);
		?>		
<input type="hidden" name="x_plazo_id" id="x_plazo_id" value="<?php echo @$datawrk["plazo_id"]; ?>"> <?php echo $datawrk["plazo_id"];?>
</span></td>
	</tr>
	
	<tr>
	  <td class="ewTableHeaderThin">Forma de Pago </td>
	  <td class="ewTableAltRow"><?php 
		$sSqlWrk = "SELECT descripcion FROM forma_pago where forma_pago_id = 3";
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
	  <input name="x_num_pagos" type="text" id="x_num_pagos" value="1" size="8" maxlength="5"  />	  </td>
	  </tr>
	<tr>
	  <td class="ewTableHeaderThin">Tipo de Calculo</td>
	  <td class="ewTableAltRow"><input type="radio" name="x_tipo_calculo" value="1" />
	    Promediado 
	      <input type="radio" name="x_tipo_calculo" value="2"   checked="checked"/>
	      Tradicional</td>
	  </tr>
	<tr>
	  <td class="ewTableHeaderThin">Tasa Anual</td>
	  <td class="ewTableAltRow"><input name="x_tasa" type="text" id="x_tasa" value="95" size="8" maxlength="5" /> 
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
<input name="x_tasa_moratoria" type="text" id="x_tasa_moratoria" value="10" size="10" maxlength="10"> (diarios)
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
		if ($datawrk["medio_pago_id"] == 2) {
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
	  <td class="ewTableHeaderThin">Penalizaci&oacute;n:</td>
	  <td class="ewTableAltRow"><input name="x_penalizacion" type="text" id="x_penalizacion" value="<?php echo @$x_m_4; ?>" size="20" maxlength="50" /></td>
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
	global $x_solicitud_id;
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
	
		global $x_solicitud_id;
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
	
	//seleccionamos todos los datos de la solicitud para generarla automaticamente.....
	
	$sqlSolcitude = "SElECT * FROM solicitud WHERE solicitud_id = $x_solicitud_id ";
	$rsSolcitud = phpmkr_query($sqlSolcitude,$conn) or die ("Error al seleccionar la solcitud".phpmkr_error()."sql .".$sqlSolcitude);
	$rowSolicitud = mysql_fetch_assoc($rsSolcitud);
	$rowSolicitud_1 = array_shift($rowSolicitud);
	$x_today_z = date("Y-m-d");
	$x_hora = date("H:i:s");
	echo "<br>".$x_hora;
	$x_fecha_vacia = "0000-00-00";
	print_r($rowSolicitud);
	$sqlClienteId = "SELECT * FROM solicitud_cliente WHERE solicitud_id = $x_solicitud_id  ";
	$rsCliente_id = phpmkr_query($sqlClienteId,$conn)or die ("Error al seleccionar el cliente".phpmkr_error()."sql:".$sqlClienteId);
	$rowCliente = phpmkr_fetch_array($rsCliente_id);
	$x_cliente_id = $rowCliente["cliente_id"];
	foreach($rowSolicitud as $campo => &$val){
		
		switch($campo)
		{		
			case "hora_registro":
			$val = "'".$x_hora."'";
			break;
			case "folio":
			$val = "'".$val."'";
			break;
			case "fecha_registro":
			$val = "'".$x_today_z."'";
			break;
			case "comentario_promotor":
			$val = "'".$val."'";
			break;
			case "comentario_comite":
			$val = "'".$val."'";
			break;
			case "actividad_desc":
			$val = "'".$val."'";
			break;
			case "fecha_investigacion":
			$val = "'".$x_today_z."'";
			break;
			case "fecha_otorga_credito":
			$val = "'".$x_today_z."'";
			break;
			case "fecha_cancelada":
			$val = "'".$x_fecha_vacia."'";
			break;
			case "cuerpo_mail":
			$val = "'".$val."'";
			break;
			case "destinatario_mail":
			$val = "'".$val."'";
			break;
			case "fecha_rechazada":
			$val = "'".$x_fecha_vacia."'";
			break;
			case "fecha_preanalisis":
			$val = "'".$x_today_z."'";
			break;
			case "fecha_en_otorgamiento":
			$val = "'".$x_today_z."'";
			break;
			case "dia_otorga_credito":
			$val = "'".$x_today_z."'";
			break;
			case "tarjeta_referenciada":
			$val = "'".$val."'";
			break;
			case "numero_cheque":
			$val = "'".$val."'";
			break;
			case "hora_otorgamiento":
			$val = "'".$x_hora."'";
			break;
			case "fecha_visita_supervision":
			$val = "'".$x_today_z."'";
			break;
			case "fecha_calcula_capacidad_pago":
			$val = "'".$x_today_z."'";
			break;
			case "fecha_calcula_capacidad_pago":
			$val = "'".$x_today_z."'";
			break;
			case "fecha_calcula_capacidad_pago":
			$val = "'".$x_today_z."'";
			break;
			case "fecha_calcula_capacidad_pago":
			$val = "'".$x_today_z."'";
			break;
			case "fecha_calcula_capacidad_pago":
			$val = "'".$x_today_z."'";
			break;
			case "fecha_aprobada":
			$val = "'".$x_today_z."'";
			break;
			case "fecha_supervision":
			$val = "'".$x_today_z."'";
			break;
			case "fecha_calcula_capacidad_pago":
			$val = "'".$x_today_z."'";
			break;
			
			case "hora_preanalisis":
			$val = "'".$x_hora."'";
			break;
			
			case "hora_supervision":
			$val = "'".$x_hora."'";
			break;
			
			case "hora_comite":
			$val = "'".$x_hora."'";
			break;
			
			case "hora_otorgamiento": 
			$val = "'".$x_hora."'";
			break;
			case "hora_aprobada": 
			$val = "'".$x_hora."'";
			break;
			}	
		if(	empty($val)){
			$val = 0;
			}	
		echo $val."<br>";	
		}
	unset($valor);
	print_r($rowSolicitud);
	// insert into database
	$sSql = "INSERT INTO `solicitud` (";
	$sSql .= implode(",", array_keys($rowSolicitud));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($rowSolicitud));
	$sSql .= ")";
	$x_result = phpmkr_query($sSql, $conn);
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
		phpmkr_query('rollback;', $conn);	 
	 	exit();
	}
	
	
	
	$x_solicitud_id = mysql_insert_id();
	
	//FOLIO	
	$currentdate_fol = getdate(time());
	$x_solicitud_fol = str_pad($x_solicitud_id, 5, "0", STR_PAD_LEFT);
	$x_dia_fol = str_pad($currentdate_fol["mday"], 2, "0", STR_PAD_LEFT);
	$x_mes_fol = str_pad($currentdate_fol["mon"], 2, "0", STR_PAD_LEFT);
	$x_year_fol = str_pad($currentdate_fol["year"], 2, "0", STR_PAD_LEFT);			
	
	$x_folio = "CP$x_solicitud_fol".$x_dia_fol.$x_mes_fol.$x_year_fol;	
	$sSql = "update solicitud set folio = '$x_folio' where solicitud_id = $x_solicitud_id";
	$x_result = phpmkr_query($sSql, $conn);
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
		phpmkr_query('rollback;', $conn);	 
	 	exit();
	}
	
	$fieldList = NULL;
	// Field solicitud_id
	$fieldList["`solicitud_id`"] = $x_solicitud_id;

	$fieldList["`cliente_id`"] = $x_cliente_id;

	// insert into database
	$sSql = "INSERT INTO `solicitud_cliente` (";
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
	
	$fieldList = NULL;
	
	$x_credito_id = mysql_insert_id();
	
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
	$theValue = ($GLOBALS["x_plazo_id"] != "") ? intval($GLOBALS["x_plazo_id"]) : "NULL";
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

	// Field penalizacion
	$theValue = ($GLOBALS["x_penalizacion"] != "") ? " '" . doubleval($GLOBALS["x_penalizacion"]) . "'" : "NULL";
	$fieldList["`penalizacion`"] = $theValue;
	// garantia liquida
	$theValue = ($GLOBALS["x_garantia_liquida"] != "") ? intval($GLOBALS["x_garantia_liquida"]) : "0";
	$fieldList["`garantia_liquida`"] = $theValue;


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
	
//	$theValue = ($GLOBALS["x_num_pagos"] != "") ? intval($GLOBALS["x_num_pagos"]) : "0";
	$fieldList["`credito_respaldo_id`"] = intval($x_credito_id);
	
	// se hace para gaurdar una copia de como se otorgo el credito originalmente.
	// insert into database
	$sSql = "INSERT INTO `credito_respaldo` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	//$x_result = phpmkr_query($sSql, $conn) or die("No se inserto en respaldo credito".phpmkr_error()."sql :". $sSql);
	$x_result = phpmkr_query($sSql, $conn);
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
		phpmkr_query('rollback;', $conn);	 
	 	exit();
	}
	
	// insertamos en linea de credito
	
	$fieldList = NULL;
	// Field usuario_rol_id
	$fieldList["`credito_id`"] = $x_credito_id; 	
	// Field monto autorizado
	$theValue = ($GLOBALS["x_importe"] != "") ? " '" . doubleval($GLOBALS["x_importe"]) . "'" : "NULL";
	$fieldList["`monto_apertura`"] = $theValue;	
	$theValue = ($GLOBALS["x_monto_maximo_aprobado"] != "") ? " '" . doubleval($GLOBALS["x_monto_maximo_aprobado"]) . "'" : "NULL";
	$fieldList["`monto_maximo_aprobado`"] = $theValue;		
	
	// Field saldo anterior
	#$theValue = ($GLOBALS["x_saldo_anterior"] != "") ? " '" . doubleval($GLOBALS["x_saldo_anterior"]) . "'" : "NULL";
	#$fieldList["`saldo_anterior`"] = $theValue;
	
	// Field consumo
	#$theValue = ($GLOBALS["x_consumo"] != "") ? " '" . doubleval($GLOBALS["x_consumo"]) . "'" : "NULL";
	#$fieldList["`consumo`"] = $theValue;	
	
	// Field cargos
	#$theValue = ($GLOBALS["x_cargos"] != "") ? " '" . doubleval($GLOBALS["x_cargos"]) . "'" : "NULL";
	#$fieldList["`cargos`"] = $theValue;
	
	// Field monto autorizado
	#$theValue = ($GLOBALS["x_pagos"] != "") ? " '" . doubleval($GLOBALS["x_pagos"]) . "'" : "NULL";
	#$fieldList["`pagos`"] = $theValue;
	
	// Field saldo_final
	#$theValue = ($GLOBALS["x_saldo_final"] != "") ? " '" . doubleval($GLOBALS["x_saldo_final"]) . "'" : "NULL";
	#$fieldList["`saldo_final`"] = $theValue;
	
		// Field dia_corte
	#$theValue = ($GLOBALS["x_monto_disponible"] != "") ? " '" . doubleval($GLOBALS["x_monto_disponible"]) . "'" : "NULL";
	#$fieldList["`monto_disponible`"] = $theValue;
		
		// Field monto dia_corte
#	$theValue = ($GLOBALS["x_dia_corte"] != "") ? " '" . doubleval($GLOBALS["x_dia_corte"]) . "'" : "NULL";
	#$fieldList["`dia_corte`"] = $theValue;
	
	

	// insert into database
	$sSql = "INSERT INTO `linea_credito` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	//$x_result = phpmkr_query($sSql, $conn) or die("No se inserto en respaldo credito".phpmkr_error()."sql :". $sSql);
	$x_result = phpmkr_query($sSql, $conn);
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
		phpmkr_query('rollback;', $conn);	 
	 	exit();
	}
	$x_linea_credito_id = mysql_insert_id();
	
	// insertamos en incredmentos		
	$fieldList = NULL;
	
	$fieldList["`credito_id`"] = $x_credito_id; 	
	// Field monto autorizado
	$theValue = ($GLOBALS["x_importe"] != "") ? " '" . doubleval($GLOBALS["x_importe"]) . "'" : "NULL";
	$fieldList["`monto_inicio`"] = $theValue;	
	
	$theValue = ($GLOBALS["x_importe"] != "") ? " '" . doubleval($GLOBALS["x_importe"]) . "'" : "NULL";
	$fieldList["`monto_incrementado`"] = $theValue;		
	$x_today  = date("Y-m-d");
	// Field fecha_otrogamiento
	$theValue = ($x_today != "") ? " '" . ConvertDateToMysqlFormat($x_today) . "'" : "Null";
	$fieldList["`fecha_incremento`"] = $theValue;
	
	// insert into database
	$sSql = "INSERT INTO `credito_incremento` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	//$x_result = phpmkr_query($sSql, $conn) or die("No se inserto en respaldo credito".phpmkr_error()."sql :". $sSql);
	$x_result = phpmkr_query($sSql, $conn);
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
		phpmkr_query('rollback;', $conn);	 
	 	exit();
	}
	
	$fieldList = NULL;	
	// credito_condicones
	//GENERA VENCIMIENTOS

	include("utilerias/datefunc.php");

	$sSql = "SELECT valor FROM plazo where plazo_id = ".$GLOBALS["x_plazo_id"];
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

	
	$x_fecha_o = ConvertDateToMysqlFormat($GLOBALS["x_fecha_otrogamiento"]);
//icrementamos la fecha en 1 mes
$sqlFceha =  " SELECT DATE_ADD(\"".$x_fecha_o."\", INTERVAL ". $x_num_pagos." MONTH) AS proximo_mes";
$rsFecha = phpmkr_query($sqlFceha,$conn)or die ("error al seleccionar la fecha de mes ".phpmkr_error()."sql :".$sqlFceha);
$rowFceha = phpmkr_fetch_array($rsFecha);
$x_fecha_mes_proximo = $rowFceha["proximo_mes"];	

// dias periodo =  fecha aumentada menos fecha de otorgamiento 
$sqlDiasPeriodo = " SELECT DATEDIFF(\"$x_fecha_mes_proximo\",\"$x_fecha_o\") AS dias_del_periodo ";
$rsDiasPeriodo = phpmkr_query($sqlDiasPeriodo,$conn)or die("Error al seleccionar los dias del periodo".phpmkr_error().$sqlDiasPeriodo);
$rowDiasPeriodo = phpmkr_fetch_array($rsDiasPeriodo);
$x_dias_periodo = $rowDiasPeriodo["dias_del_periodo"];

$interes_vencimiento = round($GLOBALS["x_importe"] * ((($GLOBALS["x_tasa"] /100)/360) * $x_dias_periodo));
if($GLOBALS["x_iva"] == 1){
	$x_iva_vencimiento = round($interes_vencimiento * .16 );
	$x_total_vencimiento = $interes_vencimiento + $x_iva_vencimiento+ $GLOBALS["x_importe"];
	}else{
		$x_iva_vencimiento = 0;
		$x_total_vencimiento = $interes_vencimiento + $GLOBALS["x_importe"];
		} 
		
		echo "importe " . $GLOBALS["x_importe"]."total del vencimiento = ".$x_total_vencimiento ."<br>";
$sSql = "insert into vencimiento values(0,$x_credito_id, 1,1, '".$x_fecha_mes_proximo."',".$GLOBALS["x_importe"].", $interes_vencimiento, 0, $x_iva_vencimiento, 0, $x_total_vencimiento,NULL)";
echo "inserte en vencimiento : ".$sSql."<br>";
#$rsSql =  phpmkr_query($sSql,$conn) or die ("Error al insertar en vencimiento".phpmkr_error()."sql:".$sSql);
$x_result = phpmkr_query($sSql, $conn);
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
		phpmkr_query('rollback;', $conn);	 
		exit();
	}






	$sSql = "update credito set fecha_vencimiento = '$x_fecha_mes_proximo' where credito_id = $x_credito_id";
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


	$sSqlv = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id ";
	$rsv = phpmkr_query($sSqlv, $conn) or die("error al seleccionar los vencimeinto del credito". phpmkr_error()."sql:");
	while($rowv = phpmkr_fetch_array($rsv)){
		//seleccionamos todos los campos del vencimiento, y los copiamos a la tabla de respaldo.
		$x_vencimiento_id = $rowv["vencimiento_id"];
		$x_vencimiento_num = $rowv["vencimiento_num"];		
		$x_credito_id = $rowv["credito_id"];
		$x_vencimiento_status_id = $rowv["vencimiento_status_id"];		
		$x_fecha_vencimiento = $rowv["fecha_vencimiento"]; //5
		$x_importe = $rowv["importe"];
		$x_interes = $rowv["interes"];
		$x_iva = $rowv["iva"];		
		$x_iva_mor = $rowv["iva_mor"];	
		$x_fecha_remanente = $rowv["fecha_genera_remanente"];//10
		$x_interes_moratorio = $rowv["interes_moratorio"];
		$x_total_venc = $rowv["total_venc"];
		
		
		#insertamos el registro de cada vencimiento en la tabla de respaldo vencimiento.
		
		#$sqlI ="INSERT INTO vencimiento_respaldo values() ";
		$sqlI = "insert into vencimiento_respaldo values(0,$x_credito_id, $x_vencimiento_num,1, '$x_fecha_vencimiento', $x_importe, $x_interes, 0, $x_iva, 0, $x_total_venc,NULL)";
		$x_result = phpmkr_query($sqlI, $conn) or die("No se respaldo el vencimineto". phpmkr_error()."sql :".$sqlI);
		
		
		}// fin while vencimientos

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


#die();
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


}


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

	$dateC = date("Y_m_d");
	$sqlCondi = "INSERT INTO `credito_condiciones` (`credito_condiciones_id` ,`credito_id` ,`fecha` ,`status`) ";
	$sqlCondi .= " VALUES ( NULL , $x_credito_id, \"$dateC\", '0')";
	$rsCondi = phpmkr_query ($sqlCondi, $conn) or die ("error al isertar en condiciones de credito". phpmkr_error()."sql :". $sqlCondi);
	
	$sqlCondi = "INSERT INTO `cheque_conciliado` (`cheque_conciliado_id` ,`credito_id` ,`fecha` ,`status`) ";
	$sqlCondi .= " VALUES ( NULL , $x_credito_id, \"$dateC\", '0')";
	$rsCondi = phpmkr_query ($sqlCondi, $conn) or die ("error al isertar en condiciones de credito". phpmkr_error()."sql :". $sqlCondi);


	phpmkr_query('commit;', $conn);	


	
	return true;
}


#ultio dia del mes

function ultimoDiaMes($x_fecha_f){
	echo "entra a ultimo dia mes con fecha ".$x_fecha_f."<br>";
					$temptime_f =  strtotime($x_fecha_f);	
					$x_numero_dia_f =  strftime('%d', $temptime_f);
					$x_numero_mes_f = strftime('%m', $temptime_f);
					echo "numero mes".$x_numero_mes_f."<br>";
					$x_numero_anio_f = strftime('%Y', $temptime_f);
					$x_ultimo_dia_mes_f = strftime("%d", mktime(0, 0, 0, $x_numero_mes_f+2, 0, $x_numero_anio_f));
	echo "el ultimo dia de mes es".	$x_ultimo_dia_mes_f ."<br>";	
	
		
	return $x_ultimo_dia_mes_f;
}
?>

