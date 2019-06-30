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
$currentdate = getdate(time());
$currdate = $currentdate["year"]."/".$currentdate["mon"]."/".$currentdate["mday"];
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
/*
if (@$_SESSION["php_project_esf_status"] <> "login") {
	header("Location:  login.php");
	exit();
}
*/
?>
<?php

$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];		

// Initialize common variables
$x_solicitud_id = Null; 
$ox_solicitud_id = Null;
$x_credito_tipo_id = Null; 
$ox_credito_tipo_id = Null;
$x_solicitud_status_id = Null; 
$ox_solicitud_status_id = Null;
$x_folio = Null; 
$ox_folio = Null;
$x_fecha_registro = Null; 
$ox_fecha_registro = Null;
$x_promotor_id = Null; 
$ox_promotor_id = Null;
$x_importe_solicitado = Null; 
$ox_importe_solicitado = Null;
$x_plazo = Null; 
$ox_plazo = Null;
$x_contrato = Null; 
$ox_contrato = Null;
$x_pagare = Null; 
$ox_pagare = Null;
?>

<?php include("../db.php") ?>
<?php include("../phpmkrfn.php") ?>

<?php

$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

// Load key from QueryString
$x_solicitud_id = @$_GET["solicitud_id"];
if(empty($x_solicitud_id)){
	$x_solicitud_id = @$_POST["x_solicitud_id"];
}
$sSqlWrk = "SELECT grupo_id FROM solicitud_grupo where solicitud_id = $x_solicitud_id";		
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
$x_grupo_id = $datawrk["grupo_id"];
@phpmkr_free_result($rswrk);
$x_win = 1;
//$x_win = @$_GET["win"];
if(empty($x_win)){
	$x_win = $_POST["x_win"];
	
}

$x_grupo = @$_POST["x_creditoSolidario_id"];


//if (!empty($x_solicitud_id )) $x_solicitud_id  = (get_magic_quotes_gpc()) ? stripslashes($x_solicitud_id ) : $x_solicitud_id ;
// Get action

$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I";	// Display with input box
} else {

	// Get fields from form $x_cliente_id
	$x_solicitud_id = @$_POST["x_solicitud_id"];
	// Get fields from form
	$x_credito_tipo_id = @$_POST["x_credito_tipo_id"];
	$x_solicitud_status_id = @$_POST["x_solicitud_status_id"];
	$x_folio = @$_POST["x_folio"];
	$x_fecha_registro = @$_POST["x_fecha_registro"];
	
	$x_fecha_investigacion = @$_POST["x_fecha_investigacion"];
	$x_fecha_otorga_credito = @$_POST["x_fecha_otorga_credito"];
	$x_actualiza_fecha_investigacion = @$_POST["x_actualiza_fecha_investigacion"];
	
	$x_hora_registro = $currtime;		
	$x_promotor_id = @$_POST["x_promotor_id"];
	$x_importe_solicitado = @$_POST["x_importe_solicitado"];
	$x_plazo_id = @$_POST["x_plazo_id"];
	$x_forma_pago_id = @$_POST["x_forma_pago_id"];		
	$x_grupo_id = @$_POST["x_grupo_id"];
	
	
	//nuevos valores
	$x_creditoSolidario_id = @$_POST["x_creditoSolidario_id"];
	$x_nombre_grupo = @$_POST["x_nombre_grupo"];
	$x_promotor = @$_POST["x_promotor"];
	$x_representante_sugerido = @$_POST["x_representante_sugerido"];
	$x_tesorero = @$_POST["x_tesorero"];
	$x_numero_integrantes = @$_POST["x_numero_integrantes"];
	$x_integrante_1 = @$_POST["x_integrante_1"];
	$x_monto_1 = @$_POST["x_monto_1"];
	$x_integrante_2 = @$_POST["x_integrante_2"];
	$x_monto_2 = @$_POST["x_monto_2"];
	$x_integrante_3 = @$_POST["x_integrante_3"];
	$x_monto_3 = @$_POST["x_monto_3"];
	$x_integrante_4 = @$_POST["x_integrante_4"];
	$x_monto_4 = @$_POST["x_monto_4"];
	$x_integrante_5 = @$_POST["x_integrante_5"];
	$x_monto_5 = @$_POST["x_monto_5"];
	$x_integrante_6 = @$_POST["x_integrante_6"];
	$x_monto_6 = @$_POST["x_monto_6"];
	$x_integrante_7 = @$_POST["x_integrante_7"];
	$x_monto_7 = @$_POST["x_monto_7"];
	$x_integrante_8 = @$_POST["x_integrante_8"];
	$x_monto_8 = @$_POST["x_monto_8"];
	$x_integrante_9 = @$_POST["x_integrante_9"];
	$x_monto_9 = @$_POST["x_monto_9"];
	$x_integrante_10 = @$_POST["x_integrante_10"];
	$x_monto_10 = @$_POST["x_monto_10"];
	$x_monto_total = @$_POST["x_monto_total"];
	$x_fecha_registro = @$_POST["x_fecha_registro"];
	$x_rol_integrante_1 = @$_POST["x_rol_integrante_1"];
	$x_rol_integrante_2 = @$_POST["x_rol_integrante_2"];
	$x_rol_integrante_3 = @$_POST["x_rol_integrante_3"];
	$x_rol_integrante_4 = @$_POST["x_rol_integrante_4"];
	$x_rol_integrante_5 = @$_POST["x_rol_integrante_5"];
	$x_rol_integrante_6 = @$_POST["x_rol_integrante_6"];
	$x_rol_integrante_7 = @$_POST["x_rol_integrante_7"];
	$x_rol_integrante_8 = @$_POST["x_rol_integrante_8"];
	$x_rol_integrante_9 = @$_POST["x_rol_integrante_9"];
	$x_rol_integrante_10 = @$_POST["x_rol_integrante_10"];
	$x_paterno_1 = @$_POST["x_paterno_1"];
	$x_paterno_2 = @$_POST["x_paterno_2"];
	$x_paterno_3 = @$_POST["x_paterno_3"];
	$x_paterno_4 = @$_POST["x_paterno_4"];
	$x_paterno_5 = @$_POST["x_paterno_5"];
	$x_paterno_6 = @$_POST["x_paterno_6"];
	$x_paterno_7 = @$_POST["x_paterno_7"];
	$x_paterno_8 = @$_POST["x_paterno_8"];
	$x_paterno_9 = @$_POST["x_paterno_9"];
	$x_paterno_10 = @$_POST["x_paterno_10"];
	$x_materno_1 = @$_POST["x_materno_1"];
	$x_materno_2 = @$_POST["x_materno_2"];
	$x_materno_3 = @$_POST["x_materno_3"];
	$x_materno_4 = @$_POST["x_materno_4"];
	$x_materno_5 = @$_POST["x_materno_5"];
	$x_materno_6 = @$_POST["x_materno_6"];
	$x_materno_7 = @$_POST["x_materno_7"];
	$x_materno_8 = @$_POST["x_materno_8"];
	$x_materno_9 = @$_POST["x_materno_9"];
	$x_materno_10 = @$_POST["x_materno_10"];
	
	$x_cliente_id_1 = @$_POST["x_cliente_id_1"];
	$x_cliente_id_2 = @$_POST["x_cliente_id_2"];
	$x_cliente_id_3 = @$_POST["x_cliente_id_3"];
	$x_cliente_id_4 = @$_POST["x_cliente_id_4"];
	$x_cliente_id_5 = @$_POST["x_cliente_id_5"];
	$x_cliente_id_6 = @$_POST["x_cliente_id_6"];
	$x_cliente_id_7 = @$_POST["x_cliente_id_7"];
	$x_cliente_id_8 = @$_POST["x_cliente_id_8"];
	$x_cliente_id_9 = @$_POST["x_cliente_id_9"];
	$x_cliente_id_10 = @$_POST["x_cliente_id_10"];
	
	


}

// Check if valid key
if (($x_solicitud_id == "") || (is_null($x_solicitud_id))) {
	ob_end_clean();
	echo"x_sol_id esta nulo se sale en :";
	//header("Location: php_solicitu_print.php");
	exit();
}

switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($conn,$x_solicitud_id)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron los datos";
			phpmkr_db_close($conn);
			ob_end_clean();
			echo"el load data no localizo los datos";
			exit();
			//header("Location: php_solicitudlist.php");
			
		}
		break;
	case "U": // Update
		if (EditData($conn)) { // Update Record based on key
			//header("Location: cuentas_view_grupo.php?key=$x_grupo");
			echo "
				<br /><br /><p align='center'>
				<a href='javascript: window.close();'>Los datos han sido actualizados. De clic aqui para cerrar esta Ventana</a>
				</p>";
			$_SESSION["ewmsg"] = "Resgitro Modificado";
			header("Location: ../php_solicitudlist.php?cmd=resetall");	
			//phpmkr_db_close($conn);
			
			exit();
			
			
		}
			
		break;
	case "D": // DIRECCIONES
		if($_POST["x_delegacion_id_temp"] != ""){
			$x_delegacion_id2 = $_POST["x_delegacion_id_temp"];
		}
		break;				
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
<!-- <link rel="stylesheet" href="../../../crm.css" type="text/css" /> -->
<!-- <link rel="stylesheet" href="../crm.css" type="text/css" /> -->
<link href="../php_project_esf.css" rel="stylesheet" type="text/css" />
</head>

<body onLoad="cargaEventos();">

<script language="javascript"  src="../ew.js"></script>

<script src="paisedohint.js"></script> 
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<script type="text/javascript">
<!--

//window.onload = function(){
	
	function cargaEventos(){
		
		document.getElementById("x_monto_1").onchange = actualizaImporteTotal;
	document.getElementById("x_monto_2").onchange = actualizaImporteTotal;
	document.getElementById("x_monto_3").onchange = actualizaImporteTotal;
	document.getElementById("x_monto_4").onchange = actualizaImporteTotal;
	document.getElementById("x_monto_5").onchange = actualizaImporteTotal;
	document.getElementById("x_monto_6").onchange = actualizaImporteTotal;
	document.getElementById("x_monto_7").onchange = actualizaImporteTotal;
	document.getElementById("x_monto_8").onchange = actualizaImporteTotal;
	document.getElementById("x_monto_9").onchange = actualizaImporteTotal;
	document.getElementById("x_monto_10").onchange = actualizaImporteTotal;
	
	document.getElementById("x_integrante_1").onchange = actualizaNumeroIntegrantes;
	document.getElementById("x_integrante_2").onchange = actualizaNumeroIntegrantes;
	document.getElementById("x_integrante_3").onchange = actualizaNumeroIntegrantes;
	document.getElementById("x_integrante_4").onchange = actualizaNumeroIntegrantes;
	document.getElementById("x_integrante_5").onchange = actualizaNumeroIntegrantes;
	document.getElementById("x_integrante_6").onchange = actualizaNumeroIntegrantes;
	document.getElementById("x_integrante_7").onchange = actualizaNumeroIntegrantes;
	document.getElementById("x_integrante_8").onchange = actualizaNumeroIntegrantes;
	document.getElementById("x_integrante_9").onchange = actualizaNumeroIntegrantes;
	document.getElementById("x_integrante_10").onchange = actualizaNumeroIntegrantes;
	
	
	/*function ocultaMens (){
	document.getElementById("seEnvioFormulario").style.display = "none";	
	}*/
	
	function actualizaNumeroIntegrantes (){
			
		numero = 0;
		 t1 = document.getElementById("x_integrante_1").value;
		 t2 = document.getElementById("x_integrante_2").value;
		 t3 = document.getElementById("x_integrante_3").value;
		 t4 = document.getElementById("x_integrante_4").value;
		 t5 = document.getElementById("x_integrante_5").value;
		 t6 = document.getElementById("x_integrante_6").value;
		 t7 = document.getElementById("x_integrante_7").value;
		 t8 = document.getElementById("x_integrante_8").value;
		 t9 = document.getElementById("x_integrante_9").value;
		 t10 = document.getElementById("x_integrante_10").value;
		
		if(t1 != '')
			numero ++;
		if(t2 != '')
			numero ++;
		if(t3 != '')
			numero ++;
		if(t4 != '')
			numero ++;
		if(t5 != '')
			numero ++;
		if(t6 != '')
			numero ++;
		if(t7 != '')
			numero ++;
		if(t8 != '')
			numero ++;
		if(t9 != '')
			numero ++;
		if(t10 != '')
			numero ++;
			
		 document.getElementById("x_numero_integrantes").value = numero;	
		}
	
	
	function actualizaImporteTotal(){
		 impoT = parseFloat(document.getElementById("x_monto_total").value);
		 suma = 0;
		 t1 = document.getElementById("x_monto_1").value;
		 t2 = document.getElementById("x_monto_2").value;
		 t3 = document.getElementById("x_monto_3").value;
		 t4 = document.getElementById("x_monto_4").value;
		 t5 = document.getElementById("x_monto_5").value;
		 t6 = document.getElementById("x_monto_6").value;
		 t7 = document.getElementById("x_monto_7").value;
		 t8 = document.getElementById("x_monto_8").value;
		 t9 = document.getElementById("x_monto_9").value;
		 t10 = document.getElementById("x_monto_10").value;
		 if(t1 == ''){			
			m1 = 0; }else{
			m1 = parseFloat(document.getElementById("x_monto_1").value);
			}
		if(t2 == ''){			
			m2 = 0; }else{
			m2 = parseFloat(document.getElementById("x_monto_2").value);
			}
		if(t3 == ''){			
			m3 = 0; }else{
			m3 = parseFloat(document.getElementById("x_monto_3").value);
			}
		if(t4 == ''){			
			m4 = 0; }else{
			m4 = parseFloat(document.getElementById("x_monto_4").value);
			}
		if(t5 == ''){			
			m5 = 0; }else{
			m5 = parseFloat(document.getElementById("x_monto_5").value);
			}
		if(t6 == ''){			
			m6 = 0; }else{
			m6 = parseFloat(document.getElementById("x_monto_6").value);
			}
		if(t7 == ''){			
			m7 = 0; }else{
			m7 = parseFloat(document.getElementById("x_monto_7").value);
			}
		if(t8 == ''){			
			m8 = 0; }else{
			m8 = parseFloat(document.getElementById("x_monto_8").value);
			}
		 
		
		if(t9 == ''){			
			m9 = 0; }else{
			m9 = parseFloat(document.getElementById("x_monto_9").value);
			}
		if(t10 == ''){			
			m10 = 0; }else{
			m10 = parseFloat(document.getElementById("x_monto_10").value);
			}
		
		suma = m1 + m2 + m3 +m4 +m5 +m6 +m7 +m8 +m9 +m10;	
		document.getElementById("x_monto_total").value = suma;
		document.getElementById("x_importe_solicitado").value= suma;
		}
		
		
		
		
		
		}



function EW_onError(form_object, input_object, object_type, error_message) {
	alert(error_message);									
	if (object_type == "RADIO" || object_type == "CHECKBOX") {
		if (input_object[0])
			input_object[0].focus();
		else
			input_object.focus();
	}	else if (!EW_isHTMLArea(input_object, object_type)) { 
		input_object.focus();  
	}  
	if (object_type == "TEXT" || object_type == "PASSWORD" || object_type == "TEXTAREA" || object_type == "FILE") {
		if (!EW_isHTMLArea(input_object, object_type))
			input_object.select();
	}
	return false;	
}

function EW_hasValue(obj, obj_type) {
	if (obj_type == "TEXT" || obj_type == "PASSWORD" || obj_type == "TEXTAREA" || obj_type == "FILE")	{
		if (obj.value.length == 0) 
			return false;		
		else 
			return true;
	}	else if (obj_type == "SELECT") {
		if (obj.type != "select-multiple" && obj.selectedIndex == 0)
			return false;
		else if (obj.type == "select-multiple" && obj.selectedIndex == -1)
			return false;
		else
			return true;
	}	else if (obj_type == "RADIO" || obj_type == "CHECKBOX")	{
		if (obj[0]) {
			for (i=0; i < obj.length; i++) {
				if (obj[i].checked)
					return true;
			}
		} else {
			return (obj.checked);
		}
		return false;	
	}
}




function EW_checkMyForm() {
	
EW_this = document.solicitudeditSolidario;
validada = true;

if (validada == true && EW_this.x_promotor_id && !EW_hasValue(EW_this.x_promotor_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_promotor_id, "SELECT", "Indique el promotor."))
		validada = false;
}


if (validada == true && EW_this.x_credito_tipo_id && !EW_hasValue(EW_this.x_credito_tipo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_credito_tipo_id, "SELECT", "Indique el credito deseado."))
		validada = false;
}
if (validada == true && EW_this.x_importe_solicitado && !EW_hasValue(EW_this.x_importe_solicitado, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_importe_solicitado, "TEXT", "Indique el importe del credito a solicitar."))
		validada = false;
}
if (validada == true && EW_this.x_importe_solicitado && !EW_checknumber(EW_this.x_importe_solicitado.value)) {
	if (!EW_onError(EW_this, EW_this.x_importe_solicitado, "TEXT", "El importe del credito solicitado es incorrecto, por favor verifiquelo."))
		validada = false;
}
if (validada == true && EW_this.x_plazo_id && !EW_hasValue(EW_this.x_plazo_id, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_plazo_id, "TEXT", "Indique el numero de pagos."))
		validada = false;
}

if (validada == true && EW_this.x_plazo_id && EW_hasValue(EW_this.x_plazo_id, "TEXT" )) {
//verifcar si el numero de pagos es correcto
numer_pag = EW_this.x_plazo_id.value;
				 if((numer_pag < 2 ) ||(numer_pag > 104)){
					 // el numero de pagos es incorrecto deben ser minimo 2 maximo 88
					 alert("El numero de pago es incorreto verifique por favor, MIN 2, MAX 104");			 
					 validada = false;
					 }
 }



if (validada == true && EW_this.x_forma_pago_id && !EW_hasValue(EW_this.x_forma_pago_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_forma_pago_id, "TEXT", "Indique la forma de pago solicitada."))
		validada = false;
}


//roles y montos de los integranes de los integrantes
//INTEGRANTE UNO
if (validada == true && EW_this.x_integrante_1 && EW_hasValue(EW_this.x_integrante_1, "TEXT" )) {
	//monto
	if (validada == true && EW_this.x_monto_1 && !EW_hasValue(EW_this.x_monto_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_monto_1, "TEXT", "Indique el monto del integrante 1."))
		validada = false;
}else if(EW_this.x_monto_1.value == 0){
	alert("Indique el monto del integrante 1.");
	validada = false;
	}
 //rol
 if (validada == true && EW_this.x_rol_integrante_1 && !EW_hasValue(EW_this.x_rol_integrante_1, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_1, "SELECT", "Indique el rol del integrante 1."))
		validada = false;
}
}//fin integrante 1
//INTEGRANTE2DOS
if (validada == true && EW_this.x_integrante_2 && EW_hasValue(EW_this.x_integrante_2, "TEXT" )) {
	//monto
	if (validada == true && EW_this.x_monto_2 && !EW_hasValue(EW_this.x_monto_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_monto_2, "TEXT", "Indique el monto del integrante 2."))
		validada = false;
}else if(EW_this.x_monto_2.value == 0){
	alert("Indique el monto del integrante 2.");
	validada = false;
	}
 //rol
 if (validada == true && EW_this.x_rol_integrante_2 && !EW_hasValue(EW_this.x_rol_integrante_2, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_2, "SELECT", "Indique el rol del integrante 2."))
		validada = false;
}
}//fin integrante 1

//INTEGRANTE TRES
if (validada == true && EW_this.x_integrante_3 && EW_hasValue(EW_this.x_integrante_3, "TEXT" )) {
	//monto
	if (validada == true && EW_this.x_monto_3 && !EW_hasValue(EW_this.x_monto_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_monto_3, "TEXT", "Indique el monto del integrante 3."))
		validada = false;
}else if(EW_this.x_monto_3.value == 0){
	alert("Indique el monto del integrante 3.");
	validada = false;
	}
 //rol
 if (validada == true && EW_this.x_rol_integrante_3 && !EW_hasValue(EW_this.x_rol_integrante_3, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_3, "SELECT", "Indique el rol del integrante 3."))
		validada = false;
}
}//fin integrante 1

//INTEGRANTE CUATRO
if (validada == true && EW_this.x_integrante_4 && EW_hasValue(EW_this.x_integrante_4, "TEXT" )) {
	//monto
	if (validada == true && EW_this.x_monto_4 && !EW_hasValue(EW_this.x_monto_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_monto_4, "TEXT", "Indique el monto del integrante 4."))
		validada = false;
}else if(EW_this.x_monto_4.value == 0){
	alert("Indique el monto del integrante 4.");
	validada = false;
	}
 //rol
 if (validada == true && EW_this.x_rol_integrante_4 && !EW_hasValue(EW_this.x_rol_integrante_4, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_4, "SELECT", "Indique el rol del integrante 4."))
		validada = false;
}
}//fin integrante 1

//INTEGRANTE CINCO
if (validada == true && EW_this.x_integrante_5 && EW_hasValue(EW_this.x_integrante_5, "TEXT" )) {
	//monto
	//alert("entro al integrante 5");
	if (validada == true && EW_this.x_monto_5 && !EW_hasValue(EW_this.x_monto_5, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_monto_5, "TEXT", "Indique el monto del integrante 5."))
		validada = false;
}else if(EW_this.x_monto_5.value == 0){
	alert("Indique el monto del integrante 5.");
	validada = false;
	}
 //rol
 if (validada == true && EW_this.x_rol_integrante_5 && !EW_hasValue(EW_this.x_rol_integrante_5, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_5, "SELECT", "Indique el rol del integrante 5."))
		validada = false;
}
}//fin integrante 1

//INTEGRANTE SEIS
if (validada == true && EW_this.x_integrante_6 && EW_hasValue(EW_this.x_integrante_6, "TEXT" )) {
	//monto
	if (validada == true && EW_this.x_monto_6 && !EW_hasValue(EW_this.x_monto_6, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_monto_6, "TEXT", "Indique el monto del integrante 6."))
		validada = false;
}else if(EW_this.x_monto_6.value == 0){
	alert("Indique el monto del integrante 6.");
	validada = false;
	}
 //rol
 if (validada == true && EW_this.x_rol_integrante_6 && !EW_hasValue(EW_this.x_rol_integrante_6, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_6, "SELECT", "Indique el rol del integrante 6."))
		validada = false;
}
}//fin integrante 1

//INTEGRANTE SIETE
if (validada == true && EW_this.x_integrante_7 && EW_hasValue(EW_this.x_integrante_7, "TEXT" )) {
	//monto
	if (validada == true && EW_this.x_monto_7 && !EW_hasValue(EW_this.x_monto_7, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_monto_7, "TEXT", "Indique el monto del integrante 7."))
		validada = false;
}else if(EW_this.x_monto_7.value == 0){
	alert("Indique el monto del integrante 7.");
	validada = false;
	}
 //rol
 if (validada == true && EW_this.x_rol_integrante_7 && !EW_hasValue(EW_this.x_rol_integrante_7, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_7, "SELECT", "Indique el rol del integrante 7."))
		validada = false;
}
}//fin integrante 1

//INTEGRANTE OCHO
if (validada == true && EW_this.x_integrante_8 && EW_hasValue(EW_this.x_integrante_8, "TEXT" )) {
	//monto
	if (validada == true && EW_this.x_monto_8 && !EW_hasValue(EW_this.x_monto_8, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_monto_8, "TEXT", "Indique el monto del integrante 8."))
		validada = false;
}else if(EW_this.x_monto_8.value == 0){
	alert("Indique el monto del integrante 8.");
	validada = false;
	}
 //rol
 if (validada == true && EW_this.x_rol_integrante_8 && !EW_hasValue(EW_this.x_rol_integrante_8, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_8, "SELECT", "Indique el rol del integrante 8."))
		validada = false;
}
}//fin integrante 1

//INTEGRANTE NUEVE
if (validada == true && EW_this.x_integrante_9 && EW_hasValue(EW_this.x_integrante_9, "TEXT" )) {
	//monto
	if (validada == true && EW_this.x_monto_9 && !EW_hasValue(EW_this.x_monto_9, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_monto_9, "TEXT", "Indique el monto del integrante 9."))
		validada = false;
}else if(EW_this.x_monto_9.value == 0){
	alert("Indique el monto del integrante 9.");
	validada = false;
	}
 //rol
 if (validada == true && EW_this.x_rol_integrante_9 && !EW_hasValue(EW_this.x_rol_integrante_9, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_9, "SELECT", "Indique el rol del integrante 9."))
		validada = false;
}
}//fin integrante 1

//INTEGRANTE DIEZ
if (validada == true && EW_this.x_integrante_10 && EW_hasValue(EW_this.x_integrante_10, "TEXT" )) {
	//monto
	if (validada == true && EW_this.x_monto_10 && !EW_hasValue(EW_this.x_monto_10, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_monto_10, "TEXT", "Indique el monto del integrante 10."))
		validada = false;
}else if(EW_this.x_monto_10.value == 0){
	alert("Indique el monto del integrante 5.");
	validada = false;
	}
 //rol
 if (validada == true && EW_this.x_rol_integrante_10 && !EW_hasValue(EW_this.x_rol_integrante_10, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_integrante_10, "SELECT", "Indique el rol del integrante 10."))
		validada = false;
}
}//fin integrante 10








//termina validaciones del formato adquision de maquinaria
if(validada == true){
	
	
	EW_this.a_edit.value = "U";
	EW_this.submit();
}
}
//window.onload
function valorMax(elemento){
				//verifaca si le valor de campo para numero de pagos es correcto de ser un valor entre 2 hasta 88
				
				numer_pag = document.getElementById("x_plazo_id").value;
				 if((numer_pag < 2 ) ||(numer_pag > 104)){
					 // el numero de pagos es incorrecto deben ser minimo 2 maximo 88
					 alert("El numero de pago es incorreto verifique por favor");					 
					 
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


<form name="solicitudeditSolidario" id="solicitudeditSolidario"  action="php_solicitudeditSolidario.php" method="post"  >
<input type="hidden" name="x_win" value="<?php echo $x_win ;?>">
<input type="hidden" name="a_edit" value="U">
<input type="hidden" name="x_solicitud_id" value="<?php echo $x_solicitud_id; ?>"  />
<input type="hidden" name="x_direccion_id" value="<?php echo $x_direccion_id; ?>" />
<input type="hidden" name="x_creditoSolidario_id" value="<?php echo $x_grupo_id ;?>" />
<input type="hidden" id="x_fecha_investigacion" name="x_fecha_investigacion" value="<?php echo htmlspecialchars(@$currdate); ?>">
<input type="hidden" name="x_actualiza_fecha_investigacion" value="<?php  echo($x_actualiza_fecha_investigacion);?>" />
<span class="texto_normal">
<?php if($x_win == 1){ ?> <?php 
}
if($x_win == 2){
	$sSqlWrk = "
	SELECT 
		crm_caso_id
	FROM 
		crm_caso
	WHERE 
		solicitud_id = ".$x_solicitud_id."
		and crm_caso_tipo_id = 1
	";
	
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$datawrk = phpmkr_fetch_array($rswrk);
	$x_crm_caso_id = $datawrk["crm_caso_id"];
	@phpmkr_free_result($rswrk);
?>
<?php 
}
if($x_win == 3){
?>
<a href='javascript: window.close();'>De clic aqui para cerrar esta Ventana</a>
<?php } ?>
<?php if($x_win == 4){ ?>
<?php } ?>
</span>
<input type="hidden" name="x_direccion_id2" value="<?php echo $x_direccion_id2; ?>" />
<input type="hidden" name="x_direccion_id3" value="<?php echo $x_direccion_id3; ?>" />
<input type="hidden" name="x_direccion_id4" value="<?php echo $x_direccion_id4; ?>" />

<input type="hidden" name="x_aval_id" value="<?php echo $x_aval_id; ?>" />
<input type="hidden" name="x_garantia_id" value="<?php echo $x_garantia_id; ?>" />
<input type="hidden" name="x_ingreso_id" value="<?php echo $x_ingreso_id; ?>" />
<input type="hidden" name="x_gasto_id" value="<?php echo $x_gasto_id; ?>" />
<input type="hidden" name="x_ingreso_aval_id" value="<?php echo $x_ingreso_aval_id; ?>" />
<input type="hidden" name="x_gasto_aval_id" value="<?php echo $x_gasto_aval_id; ?>" />
 

<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  
  <tr>
    <td width="141">&nbsp;</td>
    <td width="433">&nbsp;</td>
    <td width="126">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" style=" border-bottom: solid 1px #C00; border-top: solid 1px #C00; padding-bottom: 5px; padding-top: 5px;" bgcolor="#FFE6E6"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="154" align="center" valign="middle">
        
  <a href="tipoCuenta/formatos/php_solicitudSolidario_print.php?solicitud_id=<?php echo $x_solicitud_id;?>" title="Imprimir Solicitud" target="_blank">        
    <img src="../images/tbarImport.gif" width="28" height="27" border="0" />
    </a>         
        </td>
        <td width="30">&nbsp;</td>
       <td width="144" align="center" valign="middle">
        <?php if($x_solicitud_status_id > 5){ 
        if($x_formato_nuevo == 0){
			$pagina_pagare = "php_pagare_print.php";
        }else if($x_formato_nuevo == 1){			
			$pagina_pagare = "php_pagare_print_v1.php";
			
        }?>
<a href="<?php echo "$pagina_pagare?solicitud_id=$x_solicitud_id"?>" title="Imprimir Pagare" target="_blank">        
        <img src="../images/tbarImport.gif" width="28" height="27" border="0" />
        </a>         
        <?php  } ?>
        </td>
        <td width="31">&nbsp;</td>
        <td width="143" align="center" valign="middle">
        <?php if($x_solicitud_status_id > 5){ 
		if($x_formato_nuevo == 0){
			$pagina_contrato = "php_contrato_print.php";
        }else if($x_formato_nuevo == 1){			
			$pagina_contrato = "php_contrato_print_v1.php";
		}
		?>
  <a href="<?php echo "$pagina_contrato?solicitud_id=$x_solicitud_id"?>" title="Imprimir Contrato" target="_blank">        
    <img src="../images/tbarImport.gif" width="28" height="27" border="0" />
    </a>         
    	<?php } ?>
        </td>
        </tr>
      <tr>
        <td align="center" valign="middle">Imprimir Solicitud</td>
        <td>&nbsp;</td>
        <td align="center" valign="middle">Imprimir Pagare</td>
        <td>&nbsp;</td>
        <td align="center" valign="middle">Imprimir Contrato</td>
        </tr>
    </table></td>
    </tr>
  
  <tr>
    <td colspan="3" align="left" valign="top"><table width="674" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="5" class="texto_normal">&nbsp;       
          </td>
      </tr>
      <tr>
        <td class="texto_normal"></td>
        <td colspan="2">&nbsp;</td>
        <td class="texto_normal">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;
       
        </td>
      </tr>
      <tr>
        <td class="texto_normal">Folio:</td>
        <td colspan="2"><div class="phpmaker"><b> <?php echo htmlspecialchars(@$x_folio) ?>
                  <input type="hidden" name="x_folio" value="<?php echo htmlspecialchars(@$x_folio) ?>" />
        </b></div></td>
        <td class="texto_normal"><div align="right">Status:</div></td>
        <td>
        <?php
			echo $x_solicitud_status_id;
			if(($x_solicitud_status_id == 3 || $x_solicitud_status_id == 12) and ($_SESSION["php_project_esf_status_UserRolID"] == 12 || $_SESSION["php_project_esf_status_UserRolID"] == 1 || $_SESSION["php_project_esf_status_UserRolID"] == 2 ) ){
				$x_estado_civil_idList = "<select name=\"x_solicitud_status_id\"  class=\"texto_normal\">";
				$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
				if($x_solicitud_status_id == 12 ){
				$sSqlWrk = "SELECT `solicitud_status_id`, `descripcion` FROM `solicitud_status` where solicitud_status_id  in (10,12,4) ";
				}else{
					$sSqlWrk = "SELECT `solicitud_status_id`, `descripcion` FROM `solicitud_status` where solicitud_status_id  in (3, 10,12) ";
					}
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["solicitud_status_id"] == @$x_solicitud_status_id) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
				
				}else if($x_solicitud_status_id == 9 and ($_SESSION["php_project_esf_status_UserRolID"] == 12 ||  $_SESSION["php_project_esf_status_UserRolID"] == 1 || $_SESSION["php_project_esf_status_UserRolID"] == 7)){
					$x_estado_civil_idList = "<select name=\"x_solicitud_status_id\"  class=\"texto_normal\">";
				$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
				$sSqlWrk = "SELECT `solicitud_status_id`, `descripcion` FROM `solicitud_status` where solicitud_status_id  in (9, 2) ";
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["solicitud_status_id"] == @$x_solicitud_status_id) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;			
					
					}else{
#echo $x_solicitud_status_id;
		if(($x_solicitud_status_id < 5)||($x_solicitud_status_id == 9) || ($x_solicitud_status_id == 10) || ($x_solicitud_status_id == 11) ){

		$x_estado_civil_idList = "<select name=\"x_solicitud_status_id\" $x_readonly2 class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		if(($_SESSION["php_project_esf_status_UserRolID"] == 7)) {
			$sSqlWrk = "SELECT solicitud_status_id, descripcion FROM solicitud_status Where  solicitud_status_id = ".@$x_solicitud_status_id;
		}else if(($_SESSION["php_project_esf_status_UserRolID"] == 12)) {
			
			if ($x_solicitud_status_id  == 1){
			$sSqlWrk = "SELECT `solicitud_status_id`, `descripcion` FROM `solicitud_status` where solicitud_status_id  in (1, 11) ";	
			}else if($x_solicitud_status_id  == 3){
			$sSqlWrk = "SELECT `solicitud_status_id`, `descripcion` FROM `solicitud_status` where solicitud_status_id in (3, 10) ";	
					}else{
						$sSqlWrk = "SELECT `solicitud_status_id`, `descripcion` FROM `solicitud_status` where solicitud_status_id = $x_solicitud_status_id ";	
						}
				
		}else{
			//$sSqlWrk = "SELECT `solicitud_status_id`, `descripcion` FROM `solicitud_status`";
			$sSqlWrk = "SELECT `solicitud_status_id`, `descripcion` FROM `solicitud_status` where solicitud_status_id not in (6,7,8) ";	
			}
		#echo $sSqlWrk;
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["solicitud_status_id"] == @$x_solicitud_status_id) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		}else{
			$sSqlWrk = "SELECT descripcion FROM solicitud_status Where solicitud_status_id = ".$x_solicitud_status_id;
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			echo $datawrk["descripcion"]; 			
			@phpmkr_free_result($rswrk);			
			
?>

			<input type="hidden" name="x_solicitud_status_id" value="<?php echo $x_solicitud_status_id; ?>"  />
            
<?php
		
		}
				}

		?>        
        
      
        
        </td>
      </tr>
      <tr>
        <td class="texto_normal">Cliente No:</td>
        <td colspan="2"><div class="phpmaker"><b> <?php echo htmlspecialchars(@$x_cliente_id) ?>
                  <input type="hidden" name="x_cliente_id" value="<?php echo htmlspecialchars(@$x_cliente_id) ?>" />
        </b></div></td>
        <td><div align="right"><div><span class="texto_normal">Credito No:</span></div></td>
        <td><div class="phpmaker"><b> <?php echo htmlspecialchars(@$x_credito_id) ?> </b></div></td>
      </tr>
      <tr>
        <td class="texto_normal">Promotor:</td>
        <td colspan="4"><div class="phpmaker">
            <?php
		$x_estado_civil_idList = "<select name=\"x_promotor_id\" class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		if($_SESSION["crm_UserRolID"] == 7) {
			$sSqlWrk = "SELECT promotor_id, nombre_completo FROM promotor Where promotor_id = ".$_SESSION["crm_PromotorID"];
		}else{
			$sSqlWrk = "SELECT `promotor_id`, `nombre_completo` FROM `promotor`";		
		}
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["promotor_id"] == @$x_promotor_id) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . htmlentities($datawrk["nombre_completo"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
?>
        </div></td>
      </tr>
      <tr>
        <td width="159" class="texto_normal">Tipo de Cr&eacute;dito: </td>
        <td colspan="2" class="texto_normal"><b><?php echo($x_tipo_credito_descripcion) ?></b>
		<input type="hidden" name="x_credito_tipo_id" value="<?php echo ($x_credito_tipo_id) ;?>" />
        </td>
        <td width="230"><div align="right"><span class="texto_normal">&nbsp;Fecha Solicitud:</span></div></td>
        <td width="164"><span class="texto_normal"> <b> <?php echo $currdate; ?> </b> </span>
            <input name="x_fecha_registro" type="hidden" value="<?php echo $currdate; ?>" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Importe solicitado: </span></td>
        <td width="111"><div align="left">
            <input class="importe" name="x_importe_solicitado" type="text" id="x_importe_solicitado" value="<?php echo FormatNumber(@$x_importe_solicitado,0,0,0,0) ?>" size="10" maxlength="10" onKeyPress="return solonumeros(this,event)" onBlur="validaimporte()" />
        </div></td>
        <td width="10">&nbsp;</td>
        <td><div align="right"><span class="texto_normal">N&uacute;mero de pagos:</span></div></td>
        <td><span class="texto_normal"><input type="text" name="x_plazo_id" id="x_plazo_id" maxlength="3" size="15"  value="<?php echo $x_plazo_id;?>" onKeyPress="return solonumeros(this,event)" onChange="valorMax();" />
          <?php
		/*$x_estado_civil_idList = "<select name=\"x_plazo_id\" class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `plazo_id`, `descripcion` FROM `plazo` order by valor";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["plazo_id"] == @$x_plazo_id) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;*/
		?>
        </span></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right"><span class="texto_normal">Forma de pago :</span></div></td>
        <td><span class="texto_normal">
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
		?>
        </span></td>
      </tr>
      
      
    
      <tr>
        <td>&nbsp;</td>
        <td colspan="4">&nbsp;</td>
        </tr>
    </table></td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr></table>
  
  
  <!--aqui inserto el formulario----.................................................................................................-->
  <table width="1157"  align="center">
  <tr>
    <td   colspan="10" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Datos del Grupo</td>
  </tr>
  <tr> <td colspan="2">Nombre Grupo</td>
    <td colspan="5"><label>
      <input type="text" name="x_nombre_grupo" id="x_nombre_grupo" maxlength="250" size="80" value="<?php echo htmlentities($x_nombre_grupo);?>" />
    </label></td>
    <td >&nbsp;</td>
  <td >&nbsp;</td>
  <td >&nbsp;</td>
  </tr>
 
  
  
  <tr>
    <td colspan="10" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Integrantes del Grupo</td>
  </tr>
  <tr>
    <td colspan="2">N&uacute;mero de Integrantes</td>
    <td colspan="5">
      <input type="text" name="x_numero_integrantes" id="x_numero_integrantes" size="15" onKeyPress="return solonumeros(this,event)"  value="<?php echo ($x_numero_integrantes);?>"/>
    </td>
    <td >&nbsp;</td>
  <td >&nbsp;</td>
  <td >&nbsp;</td>
  </tr>
  <tr>
    <td height="24" colspan="6"><center>NOMBRE</center></td>
    <td width="18%">MONTO</td>
    <td width="10%">&nbsp;</td>
    <td width="1%">&nbsp;</td>
    <td width="1%">&nbsp;</td>
  </tr>
  <tr>
  <td width="1%" >&nbsp;</td>
  <td width="4%" align="right" >Rol</td>
  <td width="10%" ><?php
		$x_estado_civil_idList = "<select name=\"x_rol_integrante_1\" class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `rol_integrante_grupo_id`, `descripcion` FROM `rol_integrante_grupo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["rol_integrante_grupo_id"] == @$x_rol_integrante_1) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList; 
		
		?></td>
    <td width="1%">&nbsp;</td>
    <td width="9%">Integrante 1</td>
    <td width="45%"><input type="text" id="x_integrante_1" name="x_integrante_1" value="<?php echo $x_integrante_1 ;?>" maxlength="250" size="20" />&nbsp;<input type="text" name="x_paterno_1" value="<?php echo $x_paterno_1 ?>" maxlength="250" size="25" />&nbsp;<input type="text" name="x_materno_1" value="<?php echo $x_materno_1;?>" maxlength="250" size="25" /></td>
    <td><input name="x_monto_1" type="text" id="x_monto_1" size="18" onKeyPress="return solonumeros(this,event)"  value="<?php echo number_format($x_monto_1, 0, '.', '');?>" <?php if($x_solicitud_status_id == 6){?> readonly="readonly" <?php }?>/></td>
   
    <td><?php if(!empty($x_integrante_1)) { ?><iframe name="comentarios" src="php_visor_integrantes.php?key=<?php echo $x_cliente_id_1;?>&nombre=<?php echo htmlentities($x_integrante_1)?>&paterno=<?php echo htmlentities($x_paterno_1)?>&materno=<?php echo htmlentities($x_materno_1)?>&monto=<?php echo number_format($x_monto_1, 0, '.', '');?>&rol=<?php echo $x_rol_integrante_1;?>&numero=1&sol_id=<?php echo($x_solicitud_id);?>&grupo_id=<?php echo $x_grupo_id ;?>" scrolling="no" style="margin-left:0px; width:100px; height:30px; margin-top:-5px" frameborder="0" allowtransparency="true" id="contenido"></iframe><?php } ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
  <td >&nbsp;</td>
  <td align="right">Rol</td>
  <td ><?php
		$x_estado_civil_idList = "<select name=\"x_rol_integrante_2\" class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `rol_integrante_grupo_id`, `descripcion` FROM `rol_integrante_grupo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["rol_integrante_grupo_id"] == @$x_rol_integrante_2) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?></td>
    <td>&nbsp;</td>
    <td>Integrante 2</td>
    <td><input type="text"  name="x_integrante_2" id="x_integrante_2" value="<?php echo $x_integrante_2 ;?>" maxlength="250" size="20" />&nbsp;<input type="text" name="x_paterno_2" value="<?php echo $x_paterno_2 ?>" maxlength="250" size="25" />&nbsp;<input type="text" name="x_materno_2" value="<?php echo $x_materno_2;?>" maxlength="250" size="25" /></td>
    <td><input name="x_monto_2" type="text" id="x_monto_2" size="18" onKeyPress="return solonumeros(this,event)"  value="<?php echo number_format($x_monto_2, 0, '.', '');?>" <?php if($x_solicitud_status_id == 6){?> readonly="readonly" <?php }?> /></td>
    <td><?php if(!empty($x_integrante_2)) { ?><iframe name="comentarios" src="php_visor_integrantes.php?key=<?php echo $x_cliente_id_2;?>&nombre=<?php echo htmlentities($x_integrante_2)?>&paterno=<?php echo htmlentities($x_paterno_2)?>&materno=<?php echo htmlentities($x_materno_2)?>&monto=<?php echo number_format($x_monto_2, 0, '.', '');?>&rol=<?php echo $x_rol_integrante_2;?>&numero=2&sol_id=<?php echo($x_solicitud_id);?>&grupo_id=<?php echo $x_grupo_id ;?>" scrolling="no" style="margin-left:0px; width:100px; height:30px; margin-top:-5px" frameborder="0" allowtransparency="true" id="contenido"></iframe><?php }?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
  <td >&nbsp;</td>
  <td align="right">Rol</td>
  <td ><?php
		$x_estado_civil_idList = "<select name=\"x_rol_integrante_3\" class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `rol_integrante_grupo_id`, `descripcion` FROM `rol_integrante_grupo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["rol_integrante_grupo_id"] == @$x_rol_integrante_3) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?></td>
    <td>&nbsp;</td>
    <td>Integrante 3</td>
    <td><input type="text"  name="x_integrante_3" id="x_integrante_3" value="<?php echo $x_integrante_3 ;?>" maxlength="250" size="20" />&nbsp;<input type="text" name="x_paterno_3" value="<?php echo $x_paterno_3 ?>" maxlength="250" size="25" />&nbsp;<input type="text" name="x_materno_3" value="<?php echo $x_materno_3;?>" maxlength="250" size="25" /></td>
    <td><input name="x_monto_3" type="text" id="x_monto_3" size="18" onKeyPress="return solonumeros(this,event)"   value="<?php echo number_format($x_monto_3, 0, '.', '');?>" <?php if($x_solicitud_status_id == 6){?> readonly="readonly" <?php }?>/></td>
    <td><?php if(!empty($x_integrante_3)) { ?><iframe name="comentarios" src="php_visor_integrantes.php?key=<?php echo $x_cliente_id_3;?>&nombre=<?php echo htmlentities($x_integrante_3)?>&paterno=<?php echo htmlentities($x_paterno_3)?>&materno=<?php echo htmlentities($x_materno_3)?>&monto=<?php echo number_format($x_monto_3, 0, '.', '');?>&rol=<?php echo $x_rol_integrante_3;?>&numero=3&sol_id=<?php echo($x_solicitud_id);?>&grupo_id=<?php echo $x_grupo_id ;?>" scrolling="no" style="margin-left:0px; width:100px; height:30px; margin-top:-5px" frameborder="0" allowtransparency="true" id="contenido"></iframe><?php }?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
  <td >&nbsp;</td>
  <td align="right">Rol</td>
  <td ><?php
		$x_estado_civil_idList = "<select name=\"x_rol_integrante_4\" class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `rol_integrante_grupo_id`, `descripcion` FROM `rol_integrante_grupo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["rol_integrante_grupo_id"] == @$x_rol_integrante_4) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?></td>
    <td>&nbsp;</td>
    <td>Integrante 4</td>
    <td><input type="text"  name="x_integrante_4" id="x_integrante_4" value="<?php echo $x_integrante_4 ;?>" maxlength="250" size="20" />&nbsp;<input type="text" name="x_paterno_4" value="<?php echo $x_paterno_4 ?>" maxlength="250" size="25" />&nbsp;<input type="text" name="x_materno_4" value="<?php echo $x_materno_4;?>" maxlength="250" size="25" /></td>
    <td><input name="x_monto_4" type="text" id="x_monto_4" size="18" onKeyPress="return solonumeros(this,event)"   value="<?php echo number_format($x_monto_4, 0, '.', '');?>" <?php if($x_solicitud_status_id == 6){?> readonly="readonly" <?php }?>/></td>
    <td><?php if(!empty($x_integrante_4)) { ?><iframe name="comentarios" src="php_visor_integrantes.php?key=<?php echo $x_cliente_id_4;?>&nombre=<?php echo htmlentities($x_integrante_4)?>&paterno=<?php echo htmlentities($x_paterno_4)?>&materno=<?php echo htmlentities($x_materno_4)?>&monto=<?php echo number_format($x_monto_4, 0, '.', '');?>&rol=<?php echo $x_rol_integrante_4;?>&numero=4&sol_id=<?php echo($x_solicitud_id);?>&grupo_id=<?php echo $x_grupo_id ;?>" scrolling="no" style="margin-left:0px; width:100px; height:30px; margin-top:-5px" frameborder="0" allowtransparency="true" id="contenido"></iframe><?php }?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
  <td >&nbsp;</td>
  <td align="right" >Rol</td>
  <td ><?php
		$x_estado_civil_idList = "<select name=\"x_rol_integrante_5\" class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `rol_integrante_grupo_id`, `descripcion` FROM `rol_integrante_grupo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["rol_integrante_grupo_id"] == @$x_rol_integrante_5) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?></td>
    <td>&nbsp;</td>
    <td>Integrante 5</td>
    <td><input type="text"  name="x_integrante_5" id="x_integrante_5" value="<?php echo $x_integrante_5 ;?>" maxlength="250" size="20" />&nbsp;<input type="text" name="x_paterno_5" value="<?php echo $x_paterno_5 ?>" maxlength="250" size="25" />&nbsp;<input type="text" name="x_materno_5" value="<?php echo $x_materno_5;?>" maxlength="250" size="25" /></td>
    <td><input name="x_monto_5" type="text" id="x_monto_5" size="18"  onKeyPress="return solonumeros(this,event)"  value="<?php  echo number_format($x_monto_5, 0, '.', '');?>" <?php if($x_solicitud_status_id == 6){?> readonly="readonly" <?php }?>/></td>
    <td><?php if(!empty($x_integrante_5)) { ?><iframe name="comentarios" src="php_visor_integrantes.php?key=<?php echo $x_cliente_id_5;?>&nombre=<?php echo htmlentities($x_integrante_5)?>&paterno=<?php echo htmlentities($x_paterno_5)?>&materno=<?php echo htmlentities($x_materno_5)?>&monto=<?php echo number_format($x_monto_5, 0, '.', '');?>&rol=<?php echo $x_rol_integrante_5;?>&numero=5&sol_id=<?php echo($x_solicitud_id);?>&grupo_id=<?php echo $x_grupo_id ;?>" scrolling="no" style="margin-left:0px; width:100px; height:30px; margin-top:-5px" frameborder="0" allowtransparency="true" id="contenido"></iframe><?php }?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
  <td >&nbsp;</td>
  <td align="right">Rol</td>
  <td ><?php
		$x_estado_civil_idList = "<select name=\"x_rol_integrante_6\" class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `rol_integrante_grupo_id`, `descripcion` FROM `rol_integrante_grupo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["rol_integrante_grupo_id"] == @$x_rol_integrante_6) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?></td>
    <td>&nbsp;</td>
    <td>Integrante 6</td>
    <td><input type="text"  name="x_integrante_6"  id="x_integrante_6" value="<?php echo $x_integrante_6 ;?>" maxlength="250" size="20" />&nbsp;<input type="text" name="x_paterno_6" value="<?php echo $x_paterno_6 ?>" maxlength="250" size="25" />&nbsp;<input type="text" name="x_materno_6" value="<?php echo $x_materno_6;?>" maxlength="250" size="25" /></td>
    <td><input name="x_monto_6" type="text" id="x_monto_6" size="18" onKeyPress="return solonumeros(this,event)"   value="<?php echo number_format($x_monto_6, 0, '.', '');?>" <?php if($x_solicitud_status_id == 6){?> readonly="readonly" <?php }?>/></td>
    <td><?php if(!empty($x_integrante_6)) { ?><iframe name="comentarios" src="php_visor_integrantes.php?key=<?php echo $x_cliente_id_6;?>&nombre=<?php echo htmlentities($x_integrante_6)?>&paterno=<?php echo htmlentities($x_paterno_6)?>&materno=<?php echo htmlentities($x_materno_6)?>&monto=<?php echo number_format($x_monto_6, 0, '.', '');?>&rol=<?php echo $x_rol_integrante_6;?>&numero=6&sol_id=<?php echo($x_solicitud_id);?>&grupo_id=<?php echo $x_grupo_id ;?>" scrolling="no" style="margin-left:0px; width:100px; height:30px; margin-top:-5px" frameborder="0" allowtransparency="true" id="contenido"></iframe><?php }?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
  <td >&nbsp;</td>
  <td align="right">Rol</td>
  <td ><?php
		$x_estado_civil_idList = "<select name=\"x_rol_integrante_7\" class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `rol_integrante_grupo_id`, `descripcion` FROM `rol_integrante_grupo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["rol_integrante_grupo_id"] == @$x_rol_integrante_7) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?></td>
    <td>&nbsp;</td>
    <td>Integrante 7</td>
    <td><input type="text"  name="x_integrante_7"  id="x_integrante_7" value="<?php echo $x_integrante_7 ;?>" maxlength="250" size="20" />&nbsp;<input type="text" name="x_paterno_7" value="<?php echo $x_paterno_7 ?>" maxlength="250" size="25" />&nbsp;<input type="text" name="x_materno_7" value="<?php echo $x_materno_7;?>" maxlength="250" size="25" /></td>
    <td><input name="x_monto_7" type="text" id="x_monto_7" size="18"  onKeyPress="return solonumeros(this,event)"  value="<?php  echo number_format($x_monto_7, 0, '.', '');?>" <?php if($x_solicitud_status_id == 6){?> readonly="readonly" <?php }?>/></td>
    <td><?php if(!empty($x_integrante_7)) { ?><iframe name="comentarios" src="php_visor_integrantes.php?key=<?php echo $x_cliente_id_7;?>&nombre=<?php echo htmlentities($x_integrante_7)?>&paterno=<?php echo htmlentities($x_paterno_7)?>&materno=<?php echo htmlentities($x_materno_7)?>&monto=<?php echo number_format($x_monto_7, 0, '.', '');?>&rol=<?php echo $x_rol_integrante_7;?>&numero=7&sol_id=<?php echo($x_solicitud_id);?>&grupo_id=<?php echo $x_grupo_id ;?>" scrolling="no" style="margin-left:0px; width:100px; height:30px; margin-top:-5px" frameborder="0" allowtransparency="true" id="contenido"></iframe><?php }?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
  <td >&nbsp;</td>
  <td align="right">Rol</td>
  <td ><?php
		$x_estado_civil_idList = "<select name=\"x_rol_integrante_8\" class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `rol_integrante_grupo_id`, `descripcion` FROM `rol_integrante_grupo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["rol_integrante_grupo_id"] == @$x_rol_integrante_8) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?></td>
    <td>&nbsp;</td>
    <td>Integrante 8</td>
    <td><input type="text"  name="x_integrante_8" id="x_integrante_8" value="<?php echo $x_integrante_8 ;?>" maxlength="250" size="20" />&nbsp;<input type="text" name="x_paterno_8" value="<?php echo $x_paterno_8 ?>" maxlength="250" size="25" />&nbsp;<input type="text" name="x_materno_8" value="<?php echo $x_materno_8;?>" maxlength="250" size="25" /></td>
    <td><input name="x_monto_8" type="text" id="x_monto_8" size="18" onKeyPress="return solonumeros(this,event)"  value="<?php echo number_format($x_monto_8, 0, '.', '');?>" <?php if($x_solicitud_status_id == 6){?> readonly="readonly" <?php }?>/></td>
    <td><?php if(!empty($x_integrante_8)) { ?><iframe name="comentarios" src="php_visor_integrantes.php?key=<?php echo $x_cliente_id_8;?>&nombre=<?php echo htmlentities($x_integrante_8)?>&paterno=<?php echo htmlentities($x_paterno_8)?>&materno=<?php echo htmlentities($x_materno_8)?>&monto=<?php echo number_format($x_monto_8, 0, '.', '');?>&rol=<?php echo $x_rol_integrante_8;?>&numero=8&sol_id=<?php echo($x_solicitud_id);?>&grupo_id=<?php echo $x_grupo_id ;?>" scrolling="no" style="margin-left:0px; width:100px; height:30px; margin-top:-5px" frameborder="0" allowtransparency="true" id="contenido"></iframe><?php }?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
  <td >&nbsp;</td>
  <td align="right">Rol</td>
  <td ><?php
		$x_estado_civil_idList = "<select name=\"x_rol_integrante_9\" class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `rol_integrante_grupo_id`, `descripcion` FROM `rol_integrante_grupo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["rol_integrante_grupo_id"] == @$x_rol_integrante_9) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?></td>
    <td>&nbsp;</td>
    <td>Integrante 9</td>
    <td><input type="text"  name="x_integrante_9" id="x_integrante_9" value="<?php echo $x_integrante_9 ;?>" maxlength="250" size="20" />&nbsp;<input type="text" name="x_paterno_9" value="<?php echo $x_paterno_9 ?>" maxlength="250" size="25" />&nbsp;<input type="text" name="x_materno_9" value="<?php echo $x_materno_9;?>" maxlength="250" size="25" /></td>
    <td><input name="x_monto_9" type="text" id="x_monto_9" size="18"  onKeyPress="return solonumeros(this,event)"  value="<?php  echo number_format($x_monto_9, 0, '.', '');?>" <?php if($x_solicitud_status_id == 6){?> readonly="readonly" <?php }?>/></td>
    <td><?php if(!empty($x_integrante_9)) { ?><iframe name="comentarios" src="php_visor_integrantes.php?key=<?php echo $x_cliente_id_9;?>&nombre=<?php echo htmlentities($x_integrante_9)?>&paterno=<?php echo htmlentities($x_paterno_9)?>&materno=<?php echo htmlentities($x_materno_9)?>&monto=<?php echo number_format($x_monto_9, 0, '.', '');?>&rol=<?php echo $x_rol_integrante_9;?>&numero=9&sol_id=<?php echo($x_solicitud_id);?>&grupo_id=<?php echo $x_grupo_id ;?>" scrolling="no" style="margin-left:0px; width:100px; height:30px; margin-top:-5px" frameborder="0" allowtransparency="true" id="contenido"></iframe><?php }?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <tr>
  <td >&nbsp;</td>
  <td align="right">Rol</td>
  <td ><?php
		$x_estado_civil_idList = "<select name=\"x_rol_integrante_10\" class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `rol_integrante_grupo_id`, `descripcion` FROM `rol_integrante_grupo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["rol_integrante_grupo_id"] == @$x_rol_integrante_10) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?></td>
    <td>&nbsp;</td>
    <td>Integrante 10</td>
    <td><input type="text"  name="x_integrante_10" id="x_integrante_10" value="<?php echo $x_integrante_10 ;?>" maxlength="250" size="20" />&nbsp;<input type="text" name="x_paterno_10" value="<?php echo $x_paterno_10 ?>" maxlength="250" size="25" />&nbsp;<input type="text" name="x_materno_10" value="<?php echo $x_materno_10;?>" maxlength="250" size="25" /></td>
    <td><input name="x_monto_10" type="text" id="x_monto_10" size="18"  onKeyPress="return solonumeros(this,event)"  value="<?php echo number_format($x_monto_10, 0, '.', '');?>" <?php if($x_solicitud_status_id == 6){?> readonly="readonly" <?php }?>/></td>
    <td><?php if(!empty($x_integrante_10)) { ?><iframe name="comentarios" src="php_visor_integrantes.php?key=<?php echo $x_cliente_id_10;?>&nombre=<?php echo htmlentities($x_integrante_10)?>&paterno=<?php echo htmlentities($x_paterno_10)?>&materno=<?php echo htmlentities($x_materno_10)?>&monto=<?php echo number_format($x_monto_10, 0, '.', '');?>&rol=<?php echo $x_rol_integrante_10;?>&numero=10&sol_id=<?php echo($x_solicitud_id);?>&grupo_id=<?php echo $x_grupo_id ;?>" scrolling="no" style="margin-left:0px; width:100px; height:30px; margin-top:-5px" frameborder="0" allowtransparency="true" id="contenido"></iframe> <?php }?> </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td >&nbsp;</td>
  <td >&nbsp;</td>
  <td >&nbsp;</td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right">Importe Total: &nbsp;</td>
    <td><input name="x_monto_total" type="text" id="x_monto_total" size="18" onKeyPress="return solonumeros(this,event)"  value="<?php echo number_format($x_monto_total, 0, '.', '');?>" <?php if($x_solicitud_status_id == 6){?> readonly="readonly" <?php }?>/></td>
    <td >&nbsp;</td>
  <td >&nbsp;</td>
  <td >&nbsp;</td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td >&nbsp;</td>
  <td >&nbsp;</td>
  <td >&nbsp;</td>
  </tr>
     <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td><input type="hidden" name="x_cliente_id_1" value="<?php echo $x_cliente_id_1;?>" /> <input type="hidden" name="x_cliente_id_2" value="<?php echo $x_cliente_id_2;?>" /> <input type="hidden" name="x_cliente_id_3" value="<?php echo $x_cliente_id_3;?>" /> <input type="hidden" name="x_cliente_id_4" value="<?php echo $x_cliente_id_4;?>" /> <input type="hidden" name="x_cliente_id_5" value="<?php echo $x_cliente_id_5;?>" /> <input type="hidden" name="x_cliente_id_6" value="<?php echo $x_cliente_id_6;?>" /> <input type="hidden" name="x_cliente_id_7" value="<?php echo $x_cliente_id_7;?>" /> <input type="hidden" name="x_cliente_id_8" value="<?php echo $x_cliente_id_8;?>" /> <input type="hidden" name="x_cliente_id_9" value="<?php echo $x_cliente_id_9;?>" /> <input type="hidden" name="x_cliente_id_10" value="<?php echo $x_cliente_id_10;?>" /></td>
    <td>&nbsp;</td>
    <td ><?php if(($_SESSION["php_project_esf_status_UserRolID"] == 1) || ($_SESSION["php_project_esf_status_UserRolID"] == 2) || ($_SESSION["php_project_esf_status_UserRolID"] == 12) || ($_SESSION["php_project_esf_status_UserRolID"] == 3) ||($_SESSION["php_project_esf_status_UserRolID"] == 7)){ ?>
 <?php if(($x_solicitud_status_id != 6) || (($_SESSION["php_project_esf_status_UserRolID"] == 1) || ($_SESSION["php_project_esf_status_UserRolID"] == 2) || ($_SESSION["php_project_esf_status_UserRolID"] == 3)) ){ echo "sol est = ".$x_solicitud_status_id."<br> user =".$_SESSION["php_project_esf_status_UserRolID"].".." ;?>
 <input name="Action2" type="button"class="boton_medium" value="Editar+" <?php if(!empty($GLOBALS["x_editar"])){?>disabled="disabled"<?php }?> onclick="EW_checkMyForm();" /><?php } else {  echo "sol est = ".$x_solicitud_status_id."<br> user =".$_SESSION["php_project_esf_status_UserRolID"].".."?>
 <input name="Action3" disabled="disabled" type="button" class="boton_medium" value="Editar-"  /><?php }
 }else {?>
 
 <input name="Action4" disabled="disabled" type="button" class="boton_medium" value="Editar*"   /><?php }?>
    
    </td>
  <td >&nbsp;</td>
  <td >&nbsp;</td>
  </tr>
   <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td >&nbsp;</td>
  <td >&nbsp;</td>
  <td >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="10" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Comentarios Promotor</td>
  </tr>
   <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td >&nbsp;</td>
  <td >&nbsp;</td>
  <td >&nbsp;</td>
  </tr>
   <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><?php echo $x_comentarios_promotor ;?></td>
    <td>&nbsp;</td>
    <td >&nbsp;</td>
  <td >&nbsp;</td>
  <td >&nbsp;</td>
  </tr>
   <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td >&nbsp;</td>
  <td >&nbsp;</td>
  <td >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="10" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Comentarios comite</td>
  </tr>
   <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td >&nbsp;</td>
  <td >&nbsp;</td>
  <td >&nbsp;</td>
  </tr>
   <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><?php echo $x_comentarios_comite ;?></td>
    <td>&nbsp;</td>
    <td >&nbsp;</td>
  <td >&nbsp;</td>
  <td >&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td >&nbsp;</td>
  <td >&nbsp;</td>
  <td >&nbsp;</td>
  </tr>
</table>
  <!-- aqui termina.....................................................................................................................-->
     <table><tr><td>  
    </td>

    <td><div align="center">
      
    </div></td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
</body>
</html>

<?php
phpmkr_db_close($conn);
?>
<?php

function LoadData($conn, $x_grupo_id)
{
	$x_grupo =  $x_grupo_id;// contine el valor de solcitud_id
	$sqlSolGrp = "SELECT grupo_id FROM  solicitud_grupo WHERE solicitud_id = $x_grupo";
	$respSG = phpmkr_query($sqlSolGrp,$conn) or die("ERROR EN QUERY".phpmkr_error()."sql:".$sqlSolGrp);
	$no_grupo = mysql_result($respSG,0,0);//contiene el valor de grupo_id
	
	$sSql = "SELECT * FROM `creditosolidario` WHERE creditoSolidario_id  = $no_grupo";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_solicitud_id) : $x_solicitud_id;
	$sWhere .= "(`solicitud_id` = " . addslashes($sTmp) . ")";
	//$sSql .= " WHERE " . $sWhere;
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
		echo"No encontro registro";
		exit();
	}else{
		
		$bLoadData = true;
		$row = phpmkr_fetch_array($rs);
		$GLOBALS["x_solicitud_id"]=$row["solicitud_id"];
		$GLOBALS["x_creditoSolidario_id"]=$row["creditoSolidario_id"];
		$GLOBALS["x_nombre_grupo"]= $row["nombre_grupo"];
		$GLOBALS["x_monto_total"]= $row["monto_total"];
		$GLOBALS["x_numero_integrantes"]= $row["numero_integrantes"];
		
		$x_cont = 1;
		while($x_cont <= 10){
		//$GLOBALS["x_integrante_$x_cont"]= $row["integrante_$x_cont"];
		$x_nombre_completo =  $row["integrante_$x_cont"];
		$x_nombre_completo = explode("-", $x_nombre_completo);
		$GLOBALS["x_integrante_$x_cont"] = $x_nombre_completo[0];// NOMBRE
		$GLOBALS["x_paterno_$x_cont"] = $x_nombre_completo[1];// PATERNO
		$GLOBALS["x_materno_$x_cont"] = $x_nombre_completo[2];// MATERNO		
		$GLOBALS["x_monto_$x_cont"]= $row["monto_$x_cont"];
		$GLOBALS["x_rol_integrante_$x_cont"]= $row["rol_integrante_$x_cont"];		
		$GLOBALS["x_cliente_id_$x_cont"]=$row["cliente_id_$x_cont"];
		
		
		$x_cont++;
		}
		
		
		
		
		$GLOBALS["x_solicitud_id"]= $row["solicitud_id"];
		$GLOBALS["x_comentarios_promotor"] = "";
		$GLOBALS["x_comentarios_comite"] = "";
		//update nombres en la lista del grupo
		$x_cont_cli = 1;
		while($x_cont_cli <= 10){
			if($GLOBALS["x_cliente_id_$x_cont_cli"] != "newone" && $GLOBALS["x_cliente_id_$x_cont_cli"] != "vacio"  ){
		$sqlUpdateName = "SELECT  nombre_completo, apellido_paterno, apellido_materno, comentario_promotor, comentario_comite FROM cliente  WHERE cliente_id = ".$GLOBALS["x_cliente_id_$x_cont_cli"]."";
		$responseName = phpmkr_query($sqlUpdateName,$conn) or die("error en name update".phpmkr_error()."sql".$sqlUpdateName);
		$rowName = phpmkr_fetch_array($responseName);
		$x_name = $rowName["nombre_completo"];
		$x_ape_pat = $rowName["apellido_paterno"];
		$x_ape_mat = $rowName["apellido_materno"];
		$GLOBALS["x_comentarios_promotor"] .= $x_cont_cli.".-".$rowName["comentario_promotor"]."<br>";
		$GLOBALS["x_comentarios_comite"] .= $x_cont_cli.".-".$rowName["comentario_comite"]."<br>";
		$x_nombre_inte_completo =  $x_name."-".$x_ape_pat."-".$x_ape_mat;
		phpmkr_free_result($rowName);
		$X_CLIENTE_ACT = $GLOBALS["x_cliente_id_$x_cont_cli"];
		
		//$X_INTEGRANTE = $GLOBALS["x_integrante_$x_cont_cli"];
		$X_INTEGRANTE = "integrante_$x_cont_cli";
		$X_INTEGRANTE = $$X_INTEGRANTE;
		$sqlUpdate = "UPDATE creditosolidario SET integrante_$x_cont_cli = \" $x_nombre_inte_completo \" WHERE  creditoSolidario_id = ".$GLOBALS["x_creditoSolidario_id"]."";
		$responseUp = phpmkr_query($sqlUpdate,$conn)or die("error en update integrante".phpmkr_error()."sql".$sqlUpdate);
		
			}
		$x_cont_cli++;
		}
		//datos de la solictud
		
		//se vuelve a cargar los datos
		$sSql = "SELECT * FROM `creditosolidario` WHERE creditoSolidario_id  = $no_grupo";
		$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		
		$row = phpmkr_fetch_array($rs);
		$GLOBALS["x_solicitud_id"]=$row["solicitud_id"];
		$GLOBALS["x_creditoSolidario_id"]=$row["creditoSolidario_id"];
		$GLOBALS["x_nombre_grupo"]= $row["nombre_grupo"];
		$GLOBALS["x_monto_total"]= $row["monto_total"];
		$GLOBALS["x_numero_integrantes"]= $row["numero_integrantes"];
		
		$x_cont = 1;
		while($x_cont <= 10){
		//$GLOBALS["x_integrante_$x_cont"]= $row["integrante_$x_cont"];
		
		$x_nombre_completo =  $row["integrante_$x_cont"];
		$x_nombre_completo = explode("-", $x_nombre_completo);
		$GLOBALS["x_integrante_$x_cont"] = $x_nombre_completo[0];// NOMBRE
		$GLOBALS["x_paterno_$x_cont"] = $x_nombre_completo[1];// PATERNO
		$GLOBALS["x_materno_$x_cont"] = $x_nombre_completo[2];// MATERNO	
		$GLOBALS["x_monto_$x_cont"]= $row["monto_$x_cont"];
		$GLOBALS["x_rol_integrante_$x_cont"]= $row["rol_integrante_$x_cont"];		
		$GLOBALS["x_cliente_id_$x_cont"]=$row["cliente_id_$x_cont"];
		
		
		$x_cont++;
		}
		phpmkr_free_result($row);
		
		
		$sSql = "SELECT * FROM `solicitud` where solicitud_id = $x_grupo ";
		$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$rowS = phpmkr_fetch_array($rs);
	    $GLOBALS["x_solicitud_id"] = $rowS["solicitud_id"];
		$GLOBALS["x_credito_tipo_id"] = $rowS["credito_tipo_id"];
		$GLOBALS["x_solicitud_status_id"] = $rowS["solicitud_status_id"];
		$GLOBALS["x_folio"] = $rowS["folio"];
		$GLOBALS["x_fecha_registro"] = $rowS["fecha_registro"];
		$GLOBALS["x_promotor_id"] = $rowS["promotor_id"];
		$GLOBALS["x_importe_solicitado"] = $rowS["importe_solicitado"];
		$GLOBALS["x_plazo_id"] = $rowS["plazo_id"];
		$GLOBALS["x_forma_pago_id"] = $rowS["forma_pago_id"];		
		$GLOBALS["x_contrato"] = $rowS["contrato"];
		$GLOBALS["x_pagare"] = $rowS["pagare"];
		$GLOBALS["x_comentario_promotor"] = $rowS["comentario_promotor"];
		$GLOBALS["x_comentario_comite"] = $rowS["comentario_comite"];
		$GLOBALS["x_actividad_id"] = $rowS["actividad_id"];
		$GLOBALS["x_actividad_desc"] = $rowS["actividad_desc"];				
		$GLOBALS["x_formato_nuevo"] = $rowS["formato_nuevo"];
		$GLOBALS["x_solicitud_id"]= $rowS["solicitud_id"];
		
			if(intval($rowS["solicitud_status_id"]) == 2){			
			$GLOBALS["x_actualiza_fecha_investigacion"] = "no";				
			}else{			
				$GLOBALS["x_actualiza_fecha_investigacion"] = "si";					
				}



		//TIPO DE SOLICITUD
		$sqlTC ="SELECT descripcion FROM  credito_tipo JOIN solicitud ON(solicitud.credito_tipo_id = credito_tipo.credito_tipo_id ) ";
		$sqlTC.= "WHERE solicitud.solicitud_id = $x_grupo";
		
		$rsTC = phpmkr_query($sqlTC,$conn) or die("Failed to execute query: TIPO CREDITO" . phpmkr_error() . '<br>SQL: ' . $sSql);
		$rowTC = phpmkr_fetch_array($rsTC);
		
		$GLOBALS["x_tipo_credito_descripcion"] = $rowTC["descripcion"];		
		
		
	$x_res = true;
	
	
}
return $x_res;
}
?>

<?php

//-------------------------------------------------------------------------------
// Function EditData
// - Edit Data based on Key Value sKey
// - Variables used: field variables


function EditData($conn){

	//
		
	//$x_credito_tipo_id =@$_POST["x_tipo_credito"];
	$x_solicitud_id = @$_POST["x_solicitud_id"];
	$x_solicitud_status_id = @$_POST["x_solicitud_status_id"];
		

	$x_creditoSolidario_id = @$_POST["x_creditoSolidario_id"];
	$x_nombre_grupo = @$_POST["x_nombre_grupo"];
	$x_promotor_id = @$_POST["x_promotor_id"];
	$x_importe_solicitado = @$_POST["x_importe_solicitado"];
	$x_plazo_id = @$_POST["x_plazo_id"];
	$x_forma_pago_id = @$_POST["x_forma_pago_id"];	

	$x_numero_integrantes = @$_POST["x_numero_integrantes"];
	$x_integrante_1 = @$_POST["x_integrante_1"];
	$x_monto_1 = @$_POST["x_monto_1"];
	$x_rol_integrante_1 = @$_POST["x_rol_integrante_1"];
	$x_integrante_2 = @$_POST["x_integrante_2"];
	$x_monto_2 = @$_POST["x_monto_2"];
	$x_rol_integrante_2 = @$_POST["x_rol_integrante_2"];
	$x_integrante_3 = @$_POST["x_integrante_3"];
	$x_monto_3 = @$_POST["x_monto_3"];
	$x_rol_integrante_3 = @$_POST["x_rol_integrante_3"];
	$x_integrante_4 = @$_POST["x_integrante_4"];
	$x_monto_4 = @$_POST["x_monto_4"];
	$x_rol_integrante_4 = @$_POST["x_rol_integrante_4"];
	$x_integrante_5 = @$_POST["x_integrante_5"];
	$x_monto_5 = @$_POST["x_monto_5"];
	$x_rol_integrante_5 = @$_POST["x_rol_integrante_5"];
	$x_integrante_6 = @$_POST["x_integrante_6"];
	$x_monto_6 = @$_POST["x_monto_6"];
	$x_rol_integrante_6 = @$_POST["x_rol_integrante_6"];
	$x_integrante_7 = @$_POST["x_integrante_7"];
	$x_monto_7 = @$_POST["x_monto_7"];
	$x_rol_integrante_7 = @$_POST["x_rol_integrante_7"];
	$x_integrante_8 = @$_POST["x_integrante_8"];
	$x_monto_8 = @$_POST["x_monto_8"];
	$x_rol_integrante_8 = @$_POST["x_rol_integrante_8"];
	$x_integrante_9 = @$_POST["x_integrante_9"];
	$x_monto_9 = @$_POST["x_monto_9"];
	$x_rol_integrante_9 = @$_POST["x_rol_integrante_9"];
	$x_integrante_10 = @$_POST["x_integrante_10"];
	$x_monto_10 = @$_POST["x_monto_10"];
	$x_rol_integrante_10 = @$_POST["x_rol_integrante_10"];
	$x_monto_total = @$_POST["x_monto_total"];
	
	
	$x_paterno_1 = @$_POST["x_paterno_1"];
	$x_paterno_2 = @$_POST["x_paterno_2"];
	$x_paterno_3 = @$_POST["x_paterno_3"];
	$x_paterno_4 = @$_POST["x_paterno_4"];
	$x_paterno_5 = @$_POST["x_paterno_5"];
	$x_paterno_6 = @$_POST["x_paterno_6"];
	$x_paterno_7 = @$_POST["x_paterno_7"];
	$x_paterno_8 = @$_POST["x_paterno_8"];
	$x_paterno_9 = @$_POST["x_paterno_9"];
	$x_paterno_10 = @$_POST["x_paterno_10"];
	$x_materno_1 = @$_POST["x_materno_1"];
	$x_materno_2 = @$_POST["x_materno_2"];
	$x_materno_3 = @$_POST["x_materno_3"];
	$x_materno_4 = @$_POST["x_materno_4"];
	$x_materno_5 = @$_POST["x_materno_5"];
	$x_materno_6 = @$_POST["x_materno_6"];
	$x_materno_7 = @$_POST["x_materno_7"];
	$x_materno_8 = @$_POST["x_materno_8"];
	$x_materno_9 = @$_POST["x_materno_9"];
	$x_materno_10 = @$_POST["x_materno_10"];
	
	
	$x_cliente_id_1 = @$_POST["x_cliente_id_1"];
	$x_cliente_id_2 = @$_POST["x_cliente_id_2"];
	$x_cliente_id_3 = @$_POST["x_cliente_id_3"];
	$x_cliente_id_4 = @$_POST["x_cliente_id_4"];
	$x_cliente_id_5 = @$_POST["x_cliente_id_5"];
	$x_cliente_id_6 = @$_POST["x_cliente_id_6"];
	$x_cliente_id_7 = @$_POST["x_cliente_id_7"];
	$x_cliente_id_8 = @$_POST["x_cliente_id_8"];
	$x_cliente_id_9 = @$_POST["x_cliente_id_9"];
	$x_cliente_id_10 = @$_POST["x_cliente_id_10"];
	//$x_fecha_registro = @$_POST["x_fecha_registro"];
	
	
	//inciamos transaccion
	
	phpmkr_query('START TRANSACTION;', $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: BEGIN TRAN');
	
	//echo"inicai tranasaccion";
	
	//modificacmos los datos de la solicitud
	//SOLICITUD
	$fieldList = NULL;
	
	
	$theValue = ($x_solicitud_status_id != "") ? intval($x_solicitud_status_id) : "NULL";
	$fieldList["`solicitud_status_id`"] = $theValue; 
	
	$theValue = ($x_promotor_id != "") ? intval($x_promotor_id) : "NULL";
	$fieldList["`promotor_id`"] = $theValue;
	
	$theValue = ($x_plazo_id != "") ? intval($x_plazo_id) : "NULL";
	$fieldList["`plazo_id`"] = $theValue;	
	
	$theValue = ($x_forma_pago_id != "") ? intval($x_forma_pago_id) : "NULL";
	$fieldList["`forma_pago_id`"] = $theValue; 	
	
	$theValue = (!get_magic_quotes_gpc()) ?  addslashes($x_nombre_grupo) : $x_nombre_grupo; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`grupo_nombre`"] = $theValue;
	
	
	$theValue = ($x_monto_total != "") ? intval($x_monto_total) : "NULL";
	$fieldList["`importe_solicitado`"] = $theValue;
	
	if((intval($GLOBALS["x_solicitud_status_id"]) == 2)&&($GLOBALS["x_actualiza_fecha_investigacion"] == "si")){		
			
		// Field fecha_investigacion
		$theValue = ($GLOBALS["x_fecha_investigacion"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_investigacion"]) . "'" : "NULL";
		$fieldList["`fecha_investigacion`"] = $theValue;
			}
	
	
	
	$sSql = "UPDATE `solicitud` SET ";
			foreach ($fieldList as $key=>$temp) {
				$sSql .= "$key = $temp, ";
			}
			if (substr($sSql, -2) == ", ") {
				$sSql = substr($sSql, 0, strlen($sSql)-2);
			}
			$sSql .= " WHERE solicitud_id  = " .$x_solicitud_id."";
			$x_result = phpmkr_query($sSql,$conn);

			if(!$x_result){
				echo phpmkr_error() . '<br>SQL dos: ' . $sSql;
				phpmkr_query('rollback;', $conn);	 
				exit();
			}
	
	
	
	$fieldList = NULL;
	// Field nombre_grupo
	$theValue = (!get_magic_quotes_gpc()) ?  addslashes($x_nombre_grupo) : $x_nombre_grupo; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre_grupo`"] = $theValue;



	// Field numero_integrantes
	$theValue = ($x_numero_integrantes != "") ? intval($x_numero_integrantes) : "NULL";
	$fieldList["`numero_integrantes`"] = $theValue;

	
	$x_cont1 = 1;
	
	
	while($x_cont1 <= 10){
		//echo "entro a while  primer while $x_cont1 <p>";
	

		
		$x_integrante = "x_integrante_$x_cont1";
		$x_integrante = $$x_integrante;
		$x_paterno = "x_paterno_$x_cont1";
		$x_paterno = $$x_paterno;
		$x_materno = "x_materno_$x_cont1";
		$x_materno = $$x_materno;
		
		//echo"integrante ".  $x_integrante ."<p>";
		$x_monto = "x_monto_$x_cont1";
		$x_monto = $$x_monto;
		//echo "monto". $x_monto."<p>"; 
		$x_rol_integrante = "x_rol_integrante_$x_cont1";
		$x_rol_integrante = $$x_rol_integrante ;
		//echo "rol ".$x_rol_integrante."<p>" ;
		$x_nombre_completo = $x_integrante."-".$x_paterno."-".$x_materno;
		
		$x_cliente_id = "x_cliente_id_$x_cont1";
		$x_cliente_id = $$x_cliente_id;
		
		//echo "cliente_id ".$x_cliente_id."-";
		
		// VERIFICAMOS SI EL CAMPO NOMBRE INTEGRANTE VIENE VACIO O LLENO
		if( empty($x_integrante ) ||($x_integrante == "") || ($x_integrante == NULL)) {
			// si el campo viene vacio significa que se elimino al integrante
			//y el id_cliente lo llenamos con la cadena  "vacio"
			
			
			//antes de ponerlo a vacio, borran todos los registros que existian de ese cliente, si es que ya se habian registrado sus datos
			
			if(!empty($x_cliente_id) && ($x_cliente_id != "vacio") && ($x_cliente_id != "newone") ){
				
				
				
				/***********************************************************************/
				
				
				$sqlRen = "SELECT renovacion FROM cliente WHERE cliente_id = $x_cliente_id";
				$responseRen = phpmkr_query($sqlRen, $conn) or die("error al buscra renovacion".phpmkr_error()."sql".$sqlRen);
				
				$rowRe = phpmkr_fetch_array($responseRen);
				$renovacion = $rowRe["renovacion"];
				
				if($renovacion == 1){			
				// es un cliente que ya participo en un credito asi que se dejan sus datos en la base por si quiere algun otro tipo de credito.
				// solo se borra de la relacion con esta solicud porque ahora no formara parte del nuevo credito que esta solictando el grupo
				// borro datos de tabla solictud_cliente
				$sqlBorras ="DELETE FROM solicitud_cliente WHERE cliente_id = $x_cliente_id AND solicitud_id = $x_solicitud_id";
				$rsBC = phpmkr_query($sqlBorras,$conn) or die ("error la borra cliente".phpmkr_error()."sql cinco: ".$sqlBorras);
				
			
				
				// se borran el cliente de solicitud_cliente
					
					}else if($renovacion == 0){						
						// si no tien renova en 1 significa que es un cliente que se registro apenas y que no participo del credito pasado,
						// asi que sus datos se borran por completo de la base de datos no nos sirven
				$sqlBorrac ="DELETE FROM cliente WHERE cliente_id = $x_cliente_id";
				$rsBC = phpmkr_query($sqlBorrac,$conn) or die ("error la borra cliente".phpmkr_error()."sql cuatro: ".$sqlBorrac);
				
				// brorro datos de tabla solictud_cliente
				//
				//
				$sqlBorras ="DELETE FROM solicitud_cliente WHERE cliente_id = $x_cliente_id AND solicitud_id = $x_solicitud_id";
				$rsBC = phpmkr_query($sqlBorras,$conn) or die ("error la borra cliente".phpmkr_error()."sql cinco: ".$sqlBorras);
				
				//datos de direccion
				$sqlBorrad ="DELETE FROM direccion WHERE cliente_id = $x_cliente_id";
				$rsBC = phpmkr_query($sqlBorrad,$conn) or die ("error la borra cliente".phpmkr_error()."sql seis: ".$sqlBorrad);
				
				//datos de la tabla ingreso_2
				$sqlBorrai ="DELETE FROM ingreso_2 WHERE cliente_id = $x_cliente_id";
				$rsBC = phpmkr_query($sqlBorrai,$conn) or die ("error la borra cliente".phpmkr_error()."sq sietel: ".$sqlBorrai);
				
				//datos de la tabla gatos_2
				$sqlBorrag ="DELETE FROM gasto_2 WHERE cliente_id = $x_cliente_id";
				$rsBC = phpmkr_query($sqlBorrag,$conn) or die ("error la borra cliente".phpmkr_error()."sql ocho: ".$sqlBorrag);
				
				
				//datos de la tabla referencia_2
				$sqlBorrar ="DELETE FROM referencia_2 WHERE cliente_id = $x_cliente_id";
				$rsBC = phpmkr_query($sqlBorrar,$conn) or die ("error la borra cliente".phpmkr_error()."sql nueve: ".$sqlBorrar);
				
				// se borran todo los registros que se tenian de ese cliente
						
						
						
						}
				
				
				
				
				
				}//fin if empty cliente id
			
			
			
			
			
			
			//echo"EL CAMPO INTEGRANTE_$x_cont ESTA VACIO<p>";
			$theValue = (!get_magic_quotes_gpc($x_nombre_completo)) ? addslashes($x_nombre_completo) : $x_nombre_completo; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`integrante_$x_cont1`"] = $theValue;
			
			
			// Field monto_1
			$theValue = ($x_monto != "") ? " '" . $x_monto . "'" : "NULL";
			$fieldList["`monto_$x_cont1`"] = $theValue;
			
			// field rol_integrante
	    	$theValue = ($x_rol_integrante != "") ? intval($x_rol_integrante) : "NULL";
			$fieldList["`rol_integrante_$x_cont1`"] = $theValue;
			
				// Field cliente_id_10
			
			
			$val = "vacio";
			$theValue = (!get_magic_quotes_gpc($val)) ? addslashes($val) :$val; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`cliente_id_$x_cont1`"] = $theValue;
			//$x_tem = "x_cliente_tem";
			//$x_tem_cliente_id;
			
			//die();
			
			}else{
				//echo "EL CAMPO INTEGRANTE_$x_cont ESTA LLENO <p>";
				// el  campo viene lleno cliente_id_1
				// verificar si el registro ya existia.....si ya existia el cliente id tiene newone o la clave del registro
				$sqlN = "SELECT cliente_id_$x_cont1 FROM creditosolidario  WHERE creditoSolidario_id = $x_creditoSolidario_id";
				$RSn = phpmkr_query($sqlN,$conn) or die("error en el query uno ".phpmkr_error()."sql:".$sqlN );
				$x_cliente_id_act = mysql_result($RSn,0,0);
				
									// si no existia
									if(($x_cliente_id_act == "vacio") ||($x_cliente_id_act == "") || (empty($x_cliente_id_act))){
										//echo"el campo ya existia con valor vacio o no existia <p>";
									//el cliente existia pero se borro por eso tiene el valor "vacio" y ahora se esta llenando
									//el cliente no existia y ahora se va a llenar
										
									$theValue = (!get_magic_quotes_gpc($x_nombre_completo)) ? addslashes($x_nombre_completo) : $x_nombre_completo; 
									$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
									$fieldList["`integrante_$x_cont1`"] = $theValue;
									
									
									// Field monto_1
									$theValue = ($x_monto != "") ? " '" . $x_monto . "'" : "NULL";
									$fieldList["`monto_$x_cont1`"] = $theValue;
									
									// field rol_integrante
									$theValue = ($x_rol_integrante != "") ? intval($x_rol_integrante) : "NULL";
									$fieldList["`rol_integrante_$x_cont1`"] = $theValue;
									
									// Field cliente_$x_cont	
									//se asigna el valor newone ene le campo cliente_id para que tengamos la liga de regristrar datos
									$val = "newone";
									$theValue = (!get_magic_quotes_gpc($val)) ? addslashes($val) :$val; 
									$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
									$fieldList["`cliente_id_$x_cont1`"] = $theValue;			
									
									
									// y se debe actualizar la tabla solicitud_cliente... esta se actualiza cuando se ingresan los datos del cliente
									
									
									
									
									
																											
									}else{
										
									//echo"EL CAMPO ya existia con newone o el id<p>";
										// el clienta ya existia asi que tiene un cliente id con "newone" o con la clave de su registro
										//en cualquiera de los dos casos el cliente_id no se actualiza se queda como esta  
										// solo actulizamos nombre, rol, monto
									
												$theValue = (!get_magic_quotes_gpc($x_nombre_completo)) ? addslashes($x_nombre_completo) : $x_nombre_completo; 
												$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
												$fieldList["`integrante_$x_cont1`"] = $theValue;
												
												
												// Field monto_1
												$theValue = ($x_monto != "") ? " '" . $x_monto . "'" : "NULL";
												$fieldList["`monto_$x_cont1`"] = $theValue;
												
												// field rol_integrante
												$theValue = ($x_rol_integrante != "") ? intval($x_rol_integrante) : "NULL";
												$fieldList["`rol_integrante_$x_cont1`"] = $theValue;
													
										}//fin else el cliente ya existia
			
									
				}// fin else
		
		$x_cont1 ++;
	}// fin while
	
	
	
	// Field monto_total
	$theValue = ($x_monto_total != "") ? " '" . $x_monto_total . "'" : "NULL";
	$fieldList["`monto_total`"] = $theValue;

	
	
	
	//actualizamos los datos del grupo en general
	
	// update
			$sSql = "UPDATE `creditosolidario` SET ";
			foreach ($fieldList as $key=>$temp) {
				$sSql .= "$key = $temp, ";
			}
			if (substr($sSql, -2) == ", ") {
				$sSql = substr($sSql, 0, strlen($sSql)-2);
			}
			$sSql .= " WHERE creditoSolidario_id  = " .$x_creditoSolidario_id."";
			$x_result = phpmkr_query($sSql,$conn);

			if(!$x_result){
				echo phpmkr_error() . '<br>SQL dos: ' . $sSql;
				phpmkr_query('rollback;', $conn);	 
				exit();
			}else{
				//echo"se actualizo el registro";
				}
			
			
		
	//actualizamos los datos de las solicitudes si es que los hay
	
	$x_cont = 1;		
	while($x_cont <= 10){
		//echo "contador.".$x_cont."-";
		//echo"<P>ACTUALIZAMOS LAS SOLICITUDES";
		
		
		
		$sqlCliente_id = "SELECT cliente_id_$x_cont  FROM creditosolidario WHERE creditoSolidario_id = $x_creditoSolidario_id";
		$rsC= phpmkr_query($sqlCliente_id,$conn)or die ("erro el buscar cliente _id en el registro".phpmkr_error()."sql tres:".$sqlCliente_id);
		$x_cliente_id_ac = mysql_result($rsC,0,0);
		if($x_cliente_id_ac == "newone"){
			//el registro es nuevo y aun no existen datos en las demas tablas
			// por lo tanto no se hace ninguna accion  
			//echo"no existia no se hace nada <p>";
			
			
			}else if($x_cliente_id_ac == "vacio"){
			//	echo("tenia el valor de vacio no se hace nada<p>");
				//echo"vaciamos las demas tablas <p>";
				//se borro al integrante de la lista
				//asi que debemos borrar todos los datos que existan de ese registro en las demas tablas
				//borro datos de tabal cliente
				
				/*********************************************************************
				// ya no se borran los datos de los clientes porque javier pidio que se quedaran
				//  mientras el credito este activo no se podran borrar ni agregar integrantes
				// al grupo, hata que deje de estar activo se podran hacer modificacines al grupo.
				***********************************************************************/
				/*$sqlBorrac ="DELETE FROM cliente WHERE cliente_id = $x_cliente_id_ac";
				$rsBC = phpmkr_query($sqlBorrac,$conn) or die ("error la borra cliente".phpmkr_error()."sql cuatro: ".$sqlBorrac);
				
				// brorro datos de tabla solictud_cliente
				//
				//
				$sqlBorras ="DELETE FROM solicitud_cliente WHERE cliente_id = $x_cliente_id_ac AND solicitud_id = $x_solicitud_id";
				$rsBC = phpmkr_query($sqlBorras,$conn) or die ("error la borra cliente".phpmkr_error()."sql cinco: ".$sqlBorras);
				
				//datos de direccion
				$sqlBorrad ="DELETE FROM direccion WHERE cliente_id = $x_cliente_id_ac";
				$rsBC = phpmkr_query($sqlBorrad,$conn) or die ("error la borra cliente".phpmkr_error()."sql seis: ".$sqlBorrad);
				
				//datos de la tabla ingreso_2
				$sqlBorrai ="DELETE FROM ingreso_2 WHERE cliente_id = $x_cliente_id_ac";
				$rsBC = phpmkr_query($sqlBorrai,$conn) or die ("error la borra cliente".phpmkr_error()."sq sietel: ".$sqlBorrai);
				
				//datos de la tabla gatos_2
				$sqlBorrag ="DELETE FROM gasto_2 WHERE cliente_id = $x_cliente_id_ac";
				$rsBC = phpmkr_query($sqlBorrag,$conn) or die ("error la borra cliente".phpmkr_error()."sql ocho: ".$sqlBorrag);
				
				
				//datos de la tabla referencia_2
				$sqlBorrar ="DELETE FROM referencia_2 WHERE cliente_id = $x_cliente_id_ac";
				$rsBC = phpmkr_query($sqlBorrar,$conn) or die ("error la borra cliente".phpmkr_error()."sql nueve: ".$sqlBorrar);
				
				// se borran todo los registros que se tenian de ese cliente
				*/
				
				} else{
				//	echo"si existia y ya habia registro del campo asi q se actuliza el name<p>";
					// si en cliente_id no esta el valor de "newone" ni elde "vacio".. significa que el registro ya existia y sigue existinedo
					// asi que actulizamos el nombre en la tabla cliente					
					// fata separa los nombres paterno materno nombre
					//$x_nombre = "x_nombre_integrante_$x_cont";
					//$x_nombre =  $$x_nombre;
					
					
					$x_integrante = "x_integrante_$x_cont";
					$x_integrante = $$x_integrante;
					$x_paterno = "x_paterno_$x_cont";
					$x_paterno = $$x_paterno;
					$x_materno = "x_materno_$x_cont";
					$x_materno = $$x_materno;
					
					$sqlUpdateN = "UPDATE cliente SET nombre = \"".$x_integrante."\", `apellido_paterno` = \"".$x_paterno."\",`apellido_materno` =\"".$x_materno."\" WHERE cliente_id = $x_cliente_id_ac" ;
					$rsuc = phpmkr_query($sqlUpdateN,$conn) or die ("error en el query ultmo".phpmkr_error()."SQL diez: ".$sqlUpdateN);
									
					}//fin else		
					
					$x_cont ++;
		
	}// fin while
	


	

	
	
		phpmkr_query('commit;', $conn);	
	$res = true;
	
	
	return $res;
	
	
	}



?>


