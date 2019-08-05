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

<?php include("../../../db.php");?>
<?php include("../../../phpmkrfn.php");?>

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
		$x_mensaje = "<br><br><br><p align='center'><a href='javascript: window.close();' >Los datos han sido actualizados de clic aqui para cerrar esta ventana</a></p>";

			//phpmkr_db_close($conn);
			ob_end_clean();
			
		
		
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
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
<link href="../../../php_project_esf.css" rel="stylesheet" type="text/css" />

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
<?php echo $x_mensaje;
if(!empty($x_mensaje)){exit();
}
?>
<span class="texto_normal">

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
    <td colspan="3"></td>
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

	
			$sSqlWrk = "SELECT descripcion FROM solicitud_status Where solicitud_status_id = ".$x_solicitud_status_id;
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			echo $datawrk["descripcion"]; 			
			@phpmkr_free_result($rswrk);			
			
?>
			<input type="hidden" name="x_solicitud_status_id" value="<?php echo $x_solicitud_status_id; ?>"  />
            
        
        
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
		
			$sSqlWrk = "SELECT promotor_id, nombre_completo FROM promotor Where promotor_id = $x_promotor_id";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				
				$promotor = $datawrk["nombre_completo"];
				
			}
		
		@phpmkr_free_result($rswrk);
		
		echo $promotor;
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
           <?php echo FormatNumber(@$x_importe_solicitado,0,0,0,0) ?>
        </div></td>
        <td width="10">&nbsp;</td>
        <td><div align="right"><span class="texto_normal">N&uacute;mero de pagos):</span></div></td>
        <td><span class="texto_normal"><?php echo $x_plazo_id;?>
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
		
		$sSqlWrk = "SELECT `forma_pago_id`, `descripcion` FROM `forma_pago` where forma_pago_id = $x_forma_pago_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				
				$x_forma_pago = $datawrk["forma_pago_id"];
					
				
			
		}
		@phpmkr_free_result($rswrk);
	
		echo $x_forma_pago;
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
  <table width="1079"  align="center">
  <tr>
    <td   colspan="10" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Datos del Grupo</td>
  </tr>
  <tr> <td colspan="2">Nombre Grupo</td>
    <td colspan="5"><label>
     <?php echo htmlentities($x_nombre_grupo);?>
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
      <?php echo ($x_numero_integrantes);?>
    </td>
    <td >&nbsp;</td>
  <td >&nbsp;</td>
  <td >&nbsp;</td>
  </tr>
  <tr>
    <td height="24" colspan="6"><center>NOMBRE</center></td>
    <td width="19%">MONTO</td>
    <td width="10%">&nbsp;</td>
    <td width="1%">&nbsp;</td>
    <td width="1%">&nbsp;</td>
  </tr>
  <tr>
  <td width="1%" >&nbsp;</td>
  <td width="2%" align="right" > <?php
   if(!empty($x_rol_integrante_1)){ ?>Rol <?php }?></td>
  <td width="9%" ><?php
		if(!empty($x_rol_integrante_1)){
		$sSqlWrk = "SELECT `rol_integrante_grupo_id`, `descripcion` FROM `rol_integrante_grupo` where  rol_integrante_grupo_id = $x_rol_integrante_1";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				
				
				$x_rol_1 = $datawrk["descripcion"];
			}
		
		@phpmkr_free_result($rswrk);
	
		echo $x_rol_1; 
		}
		?></td>
    <td width="1%">&nbsp;</td>
    <td width="9%"><?php if(!empty($x_integrante_1)){?>Integrante 1<?php }?></td>
    <td width="47%"><?php echo htmlentities($x_integrante_1);?></td>
    <td><?php if((!empty($x_monto_1) && ($x_monto_1 != 0) )){ echo number_format($x_monto_1, 0, '.', '');}?></td>
   
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
  <td >&nbsp;</td>
  <td align="right"> <?php
   if(!empty($x_rol_integrante_2)){ ?>Rol <?php }?></td>
  <td ><?php
  if(!empty($x_rol_integrante_2)){
		$sSqlWrk = "SELECT `rol_integrante_grupo_id`, `descripcion` FROM `rol_integrante_grupo` where  rol_integrante_grupo_id = $x_rol_integrante_2";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				
				
				$x_rol_2 = $datawrk["descripcion"]; 
			}
		
		@phpmkr_free_result($rswrk);
	
		echo $x_rol_2; 
  }
		?></td>
    <td>&nbsp;</td>
    <td><?php if(!empty($x_integrante_2)){?>Integrante 2<?php }?></td>
    <td><?php echo htmlentities($x_integrante_2);?></td>
    <td><?php if((!empty($x_monto_2) && ($x_monto_2 != 0) )){ echo number_format($x_monto_2, 0, '.', '');}?></td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
  <td >&nbsp;</td>
  <td align="right"> <?php
   if(!empty($x_rol_integrante_3)){ ?>Rol <?php }?></td>
  <td ><?php
  if(!empty($x_rol_integrante_3)){
		$sSqlWrk = "SELECT `rol_integrante_grupo_id`, `descripcion` FROM `rol_integrante_grupo` where  rol_integrante_grupo_id = $x_rol_integrante_3";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				
				
				$x_rol_3 = $datawrk["descripcion"]; 
			}
		
		@phpmkr_free_result($rswrk);
	
		echo $x_rol_3; 
  }
		?></td>
    <td>&nbsp;</td>
    <td><?php if(!empty($x_integrante_3)){?>Integrante 3<?php }?></td>
    <td><?php echo htmlentities($x_integrante_3);?></td>
    <td><?php if((!empty($x_monto_3) && ($x_monto_3 != 0) )){ echo number_format($x_monto_3, 0, '.', '');}?></td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
  <td >&nbsp;</td>
  <td align="right"> <?php
   if(!empty($x_rol_integrante_4)){ ?>Rol <?php }?></td>
  <td ><?php
   if(!empty($x_rol_integrante_4)){
		$sSqlWrk = "SELECT `rol_integrante_grupo_id`, `descripcion` FROM `rol_integrante_grupo` where  rol_integrante_grupo_id = $x_rol_integrante_4";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				
				
				$x_rol_4 = $datawrk["descripcion"]; 
			}
		
		@phpmkr_free_result($rswrk);
	
		echo $x_rol_4;
   }
		?></td>
    <td>&nbsp;</td>

    <td><?php if(!empty($x_integrante_4)){?>Integrante 4<?php }?></td>
    <td><?php echo htmlentities($x_integrante_4);?></td>
    <td><?php if((!empty($x_monto_4) && ($x_monto_4 != 0) )){ echo number_format($x_monto_4, 0, '.', '');}?></td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
  <td >&nbsp;</td>
  <td align="right" > <?php
   if(!empty($x_rol_integrante_5)){ ?>Rol <?php }?></td>
  <td ><?php
   if(!empty($x_rol_integrante_5)){
		$sSqlWrk = "SELECT `rol_integrante_grupo_id`, `descripcion` FROM `rol_integrante_grupo` where  rol_integrante_grupo_id = $x_rol_integrante_5";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				
				
				$x_rol_5 = $datawrk["descripcion"]; 
			}
		
		@phpmkr_free_result($rswrk);
	
		echo $x_rol_5; 
   }
		?></td>
    <td>&nbsp;</td>
    <td><?php if(!empty($x_integrante_5)){?>Integrante 5<?php }?></td>
    <td><?php echo htmlentities($x_integrante_5);?></td>
    <td><?php if((!empty($x_monto_5) && ($x_monto_5 != 0) )){ echo number_format($x_monto_5, 0, '.', '');}?></td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
  <td >&nbsp;</td>
  <td align="right"> <?php
   if(!empty($x_rol_integrante_6)){ ?>Rol <?php }?></td>
  <td ><?php
   if(!empty($x_rol_integrante_6)){
		$sSqlWrk = "SELECT `rol_integrante_grupo_id`, `descripcion` FROM `rol_integrante_grupo` where  rol_integrante_grupo_id = $x_rol_integrante_6";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				
				
				$x_rol_6 = $datawrk["descripcion"]; 
			}
		
		@phpmkr_free_result($rswrk);
	
		echo $x_rol_6; 
   }
		?></td>
    <td>&nbsp;</td>
    <td><?php if(!empty($x_integrante_6)){?>Integrante 6<?php }?></td>
    <td><?php echo htmlentities($x_integrante_6);?></td>
    <td><?php if((!empty($x_monto_6) && ($x_monto_6 != 0) )){ echo number_format($x_monto_6, 0, '.', '');}?></td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
  <td >&nbsp;</td>
  <td align="right"> <?php
   if(!empty($x_rol_integrante_7)){ ?>Rol <?php }?></td>
  <td ><?php
   if(!empty($x_rol_integrante_7)){
		$sSqlWrk = "SELECT `rol_integrante_grupo_id`, `descripcion` FROM `rol_integrante_grupo` where  rol_integrante_grupo_id = $x_rol_integrante_7";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				
				
				$x_rol_7 = $datawrk["descripcion"]; 
			}
		
		@phpmkr_free_result($rswrk);
	
		echo $x_rol_7; 
   }
		?></td>
    <td>&nbsp;</td>
    <td><?php if(!empty($x_integrante_7)){?>Integrante 7<?php }?></td>
    <td><?php echo htmlentities($x_integrante_7);?></td>
    <td><?php if((!empty($x_monto_7) && ($x_monto_7 != 0) )){ echo number_format($x_monto_7, 0, '.', '');}?></td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
  <td >&nbsp;</td>
  <td align="right"> <?php
   if(!empty($x_rol_integrante_8)){ ?>Rol <?php }?></td>
  <td ><?php
   if(!empty($x_rol_integrante_8)){
		$sSqlWrk = "SELECT `rol_integrante_grupo_id`, `descripcion` FROM `rol_integrante_grupo` where  rol_integrante_grupo_id = $x_rol_integrante_8";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				
				
				$x_rol_8 = $datawrk["descripcion"]; 
			}
		
		@phpmkr_free_result($rswrk);
	
		echo $x_rol_8; 
   }
		?></td>
    <td>&nbsp;</td>
    <td><?php if(!empty($x_integrante_8)){?>Integrante 8<?php }?></td>
    <td><?php echo htmlentities($x_integrante_8);?></td>
    <td><?php if((!empty($x_monto_8) && ($x_monto_8 != 0) )){ echo number_format($x_monto_8, 0, '.', '');}?></td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
  <td >&nbsp;</td>
  <td align="right"> <?php
   if(!empty($x_rol_integrante_9)){ ?>Rol <?php }?></td>
  <td ><?php
   if(!empty($x_rol_integrante_9)){
		$sSqlWrk = "SELECT `rol_integrante_grupo_id`, `descripcion` FROM `rol_integrante_grupo` where  rol_integrante_grupo_id = $x_rol_integrante_9";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				
				
				$x_rol_9 = $datawrk["descripcion"]; 
			}
		
		@phpmkr_free_result($rswrk);
	
		echo $x_rol_9; 
   }
		?></td>
    <td>&nbsp;</td>
    <td><?php if(!empty($x_integrante_9)){?>Integrante 9<?php }?></td>
    <td><?php echo htmlentities($x_integrante_9);?></td>
    <td><?php if((!empty($x_monto_9) && ($x_monto_9 != 0) )){ echo number_format($x_monto_9, 0, '.', '');}?></td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <tr>
  <td >&nbsp;</td>
  <td align="right"> <?php
   if(!empty($x_rol_integrante_10)){ ?>Rol <?php }?></td>
  <td ><?php
   if(!empty($x_rol_integrante_10)){
		$sSqlWrk = "SELECT `rol_integrante_grupo_id`, `descripcion` FROM `rol_integrante_grupo` where  rol_integrante_grupo_id = $x_rol_integrante_10";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				
				
				$x_rol_10 = $datawrk["descripcion"]; 
			}
		
		@phpmkr_free_result($rswrk);
	
		echo $x_rol_10; 
   }
		?></td>
    <td>&nbsp;</td>
    <td><?php if(!empty($x_integrante_10)){?>Integrante 10<?php }?></td>
    <td><?php echo htmlentities($x_integrante_10);?></td>
    <td><?php if((!empty($x_monto_10) && ($x_monto_10 != 0) )){ echo number_format($x_monto_10, 0, '.', '');}?></td>
    <td> </td>
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
    <td><?php echo number_format($x_monto_total, 0, '.', '');?></td>
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
		$GLOBALS["x_integrante_$x_cont"]= $row["integrante_$x_cont"];
		$GLOBALS["x_monto_$x_cont"]= $row["monto_$x_cont"];
		$GLOBALS["x_rol_integrante_$x_cont"]= $row["rol_integrante_$x_cont"];		
		$GLOBALS["x_cliente_id_$x_cont"]=$row["cliente_id_$x_cont"];
		
		
		$x_cont++;
		}
		
		
		$GLOBALS["x_solicitud_id"]= $row["solicitud_id"];

		
		
		
		//datos de la solictud
		
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
		//echo"integrante ".  $x_integrante ."<p>";
		$x_monto = "x_monto_$x_cont1";
		$x_monto = $$x_monto;
		//echo "monto". $x_monto."<p>"; 
		$x_rol_integrante = "x_rol_integrante_$x_cont1";
		$x_rol_integrante = $$x_rol_integrante ;
		//echo "rol ".$x_rol_integrante."<p>" ;
		
		// VERIFICAMOS SI EL CAMPO NOMBRE INTEGRANTE VIENE VACIO O LLENO
		if( empty($x_integrante ) ||($x_integrante == "") || ($x_integrante == NULL)) {
			// si el campo viene vacio significa que se elimino al integrante
			//y el id_cliente lo llenamos con la cadena  "vacio"
			
			//echo"EL CAMPO INTEGRANTE_$x_cont ESTA VACIO<p>";
			$theValue = (!get_magic_quotes_gpc($x_integrante)) ? addslashes($x_integrante) : $x_integrante; 
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
										
									$theValue = (!get_magic_quotes_gpc($x_integrante)) ? addslashes($x_integrante) : $x_integrante; 
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
																											
																											
									}else{
										
									//echo"EL CAMPO ya existia con newone o el id<p>";
										// el clienta ya existia asi que tiene un cliente id con "newone" o con la clave de su registro
										//en cualquiera de los dos casos el cliente_id no se actualiza se queda como esta  
										// solo actulizamos nombre, rol, monto
									
												$theValue = (!get_magic_quotes_gpc($x_integrante)) ? addslashes($x_integrante) : $x_integrante; 
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
				$sqlBorrac ="DELETE FROM cliente WHERE cliente_id = $x_cliente_id_ac";
				//$rsBC = phpmkr_query($sqlBorrac,$conn) or die ("error la borra cliente".phpmkr_error()."sql cuatro: ".$sqlBorrac);
				
				// brorro datos de tabla solictud_cliente
				//
				//
				$sqlBorras ="DELETE FROM solicitud_cliente WHERE cliente_id = $x_cliente_id_ac AND solicitud_id = $x_solicitud_id";
				//$rsBC = phpmkr_query($sqlBorras,$conn) or die ("error la borra cliente".phpmkr_error()."sql cinco: ".$sqlBorras);
				
				//datos de direccion
				$sqlBorrad ="DELETE FROM direccion WHERE cliente_id = $x_cliente_id_ac";
				//$rsBC = phpmkr_query($sqlBorrad,$conn) or die ("error la borra cliente".phpmkr_error()."sql seis: ".$sqlBorrad);
				
				//datos de la tabla ingreso_2
				$sqlBorrai ="DELETE FROM ingreso_2 WHERE cliente_id = $x_cliente_id_ac";
				//$rsBC = phpmkr_query($sqlBorrai,$conn) or die ("error la borra cliente".phpmkr_error()."sq sietel: ".$sqlBorrai);
				
				//datos de la tabla gatos_2
				$sqlBorrag ="DELETE FROM gasto_2 WHERE cliente_id = $x_cliente_id_ac";
				//$rsBC = phpmkr_query($sqlBorrag,$conn) or die ("error la borra cliente".phpmkr_error()."sql ocho: ".$sqlBorrag);
				
				
				//datos de la tabla referencia_2
				$sqlBorrar ="DELETE FROM referencia_2 WHERE cliente_id = $x_cliente_id_ac";
				//$rsBC = phpmkr_query($sqlBorrar,$conn) or die ("error la borra cliente".phpmkr_error()."sql nueve: ".$sqlBorrar);
				
				// se borran todo los registros que se tenian de ese cliente
				
				
				} else{
				//	echo"si existia y ya habia registro del campo asi q se actuliza el name<p>";
					// si en cliente_id no esta el valor de "newone" ni elde "vacio".. significa que el registro ya existia y sigue existinedo
					// asi que actulizamos el nombre en la tabla cliente					
					// fata separa los nombres paterno materno nombre
					$x_nombre = "x_nombre_integrante_$x_cont";
					$x_nombre =  $$x_nombre;
					
					$sqlUpdateN = "UPDATE cliente SET nombre = \"".$x_nombre."\" WHERE cliente_id = $x_cliente_id_ac" ;
					$rsuc = phpmkr_query($sqlUpdateN,$conn) or die ("error en el query ultmo".phpmkr_error()."SQL diez: ".$sqlUpdateN);
									
					}//fin else		
					
					$x_cont ++;
		
	}// fin while
	
	
	
	

	
	
		phpmkr_query('commit;', $conn);	
	$res = true;
	return $res;
	
	}



?>


