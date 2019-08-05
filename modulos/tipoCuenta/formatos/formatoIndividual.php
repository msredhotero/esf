<?php session_start(); ?>
<?php ob_start(); ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Cache-Control: private");
header("Pragma: no-cache"); // HTTP/1.0 

$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];		
 include("admin/db.php"); ?>
<?php include("admin/phpmkrfn.php"); ?>

<?php
$ewCurSec = 0; // Initialise
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../crm.css" rel="stylesheet" type="text/css" />

</head>

<body>


<script type="text/javascript">
<!--

function act(){

vact = document.solicitudadd.x_actividad_id.value;

	if(vact == 1){
		document.getElementById("actividad1").className = "TG_visible";
		document.getElementById("actividad2").className = "TG_hidden";
		document.getElementById("actividad3").className = "TG_hidden";				
	}
	if(vact == 2){
		document.getElementById("actividad1").className = "TG_hidden";
		document.getElementById("actividad2").className = "TG_visible";
		document.getElementById("actividad3").className = "TG_hidden";				
	}
	if(vact == 3){
		document.getElementById("actividad1").className = "TG_hidden";
		document.getElementById("actividad2").className = "TG_hidden";
		document.getElementById("actividad3").className = "TG_visible";				
	}
}

function buscadelegacion(){
	EW_this = document.solicitudadd;
	EW_this.a_add.value = "D";	
	EW_this.submit();	
}

function buscacliente(){
	EW_this = document.solicitudadd;
	EW_this.a_add.value = "X";
	EW_this.submit();

}
function validaimporte(){
	EW_this = document.solicitudadd;
	if(EW_this.x_importe_solicitado.value < 1000){
		alert("El importe es incorrecto valor minimo 1000");
		EW_this.x_importe_solicitado.focus();
	}
}


function mismosdom(){
	EW_this = document.solicitudadd;
	validada = true;
	
	if(EW_this.x_mismos.checked == true){
		if (validada == true && EW_this.x_calle && !EW_hasValue(EW_this.x_calle, "TEXT" )) {
			if (!EW_onError(EW_this, EW_this.x_calle, "TEXT", "Indique la calle del domicilio particular."))
				validada = false;
		}
		if (validada == true && EW_this.x_colonia && !EW_hasValue(EW_this.x_colonia, "TEXT" )) {
			if (!EW_onError(EW_this, EW_this.x_colonia, "TEXT", "Indique la colonia del domicilio particular."))
				validada = false;
		}
		if (validada == true && EW_this.x_entidad_id && !EW_hasValue(EW_this.x_entidad_id, "SELECT" )) {
			if (!EW_onError(EW_this, EW_this.x_entidad_id, "SELECT", "Indique la entidad del domicilio particular."))
				validada = false;
		}
		
		if (validada == true && EW_this.x_delegacion_id && !EW_hasValue(EW_this.x_delegacion_id, "SELECT" )) {
			if (!EW_onError(EW_this, EW_this.x_delegacion_id, "SELECT", "Indique la delegación del domicilio particular."))
				validada = false;
		}
		if(validada == false){
			EW_this.x_mismos.checked = false;
		}else{
			EW_this.x_calle2.value = EW_this.x_calle.value;
			EW_this.x_colonia2.value = EW_this.x_colonia.value;
			EW_this.x_codigo_postal2.value = EW_this.x_codigo_postal.value;
			EW_this.x_ubicacion2.value = EW_this.x_ubicacion.value;
			EW_this.x_antiguedad2.value = EW_this.x_antiguedad.value;
			EW_this.x_telefono2.value = EW_this.x_telefono.value;		
	
			var indice = EW_this.x_entidad_id.selectedIndex;
			EW_this.x_entidad_id2.options[indice].selected = true;
	
			EW_this.x_delegacion_id_temp.value = EW_this.x_delegacion_id.value;
			EW_this.a_add.value = "D";	
			showHint(EW_this.x_entidad_id2,'txtHint2', 'x_delegacion_id2', EW_this.x_delegacion_id.value);


//			EW_this.submit();	
	
		}
	}else{
		EW_this.x_calle2.value = "";
		EW_this.x_colonia2.value = "";
		EW_this.x_entidad_id2.options[0].selected = true;	
	//	EW_this.x_delegacion_id2.options[0].selected = true;
		EW_this.x_entidad2.value = "";
		EW_this.x_codigo_postal2.value = "";
		EW_this.x_ubicacion2.value = "";
		EW_this.x_antiguedad2.value = "";
		EW_this.x_telefono2.value = "";			
	}
	
}


function mismosdomtit(){
	EW_this = document.solicitudadd;
	validada = true;
	
	if(EW_this.x_mismos_titluar.checked == true){
		if (validada == true && EW_this.x_calle && !EW_hasValue(EW_this.x_calle, "TEXT" )) {
			if (!EW_onError(EW_this, EW_this.x_calle, "TEXT", "Indique la calle del domicilio particular del titular."))
				validada = false;
		}
		if (validada == true && EW_this.x_colonia && !EW_hasValue(EW_this.x_colonia, "TEXT" )) {
			if (!EW_onError(EW_this, EW_this.x_colonia, "TEXT", "Indique la colonia del domicilio particular del titular."))
				validada = false;
		}
		if (validada == true && EW_this.x_entidad_id && !EW_hasValue(EW_this.x_entidad_id, "SELECT" )) {
			if (!EW_onError(EW_this, EW_this.x_entidad_id, "SELECT", "Indique la entidad del domicilio particular del titular."))
				validada = false;
		}
		
		if (validada == true && EW_this.x_delegacion_id && !EW_hasValue(EW_this.x_delegacion_id, "SELECT" )) {
			if (!EW_onError(EW_this, EW_this.x_delegacion_id, "SELECT", "Indique la delegación del domicilio particular del titular."))
				validada = false;
		}
		if(validada == false){
			EW_this.x_mismos_titluar.checked = false;
		}else{
			EW_this.x_calle3.value = EW_this.x_calle.value;
			EW_this.x_colonia3.value = EW_this.x_colonia.value;
			EW_this.x_codigo_postal3.value = EW_this.x_codigo_postal.value;
			EW_this.x_ubicacion3.value = EW_this.x_ubicacion.value;
			EW_this.x_antiguedad3.value = EW_this.x_antiguedad.value;
			EW_this.x_telefono3.value = EW_this.x_telefono.value;		

			var indice_vt = EW_this.x_vivienda_tipo_id.selectedIndex;
			EW_this.x_vivienda_tipo_id2.options[indice_vt].selected = true;
	

			var indice = EW_this.x_entidad_id.selectedIndex;
			EW_this.x_entidad_id3.options[indice].selected = true;

			showHint(EW_this.x_entidad_id3,'txtHint3', 'x_delegacion_id3', EW_this.x_delegacion_id.value);
	
		}
	}else{
		EW_this.x_calle3.value = "";
		EW_this.x_colonia3.value = "";
		EW_this.x_vivienda_tipo_id2.options[0].selected = true;			
		EW_this.x_entidad_id3.options[0].selected = true;	
		EW_this.x_codigo_postal3.value = "";
		EW_this.x_ubicacion3.value = "";
		EW_this.x_antiguedad3.value = "";
		EW_this.x_telefono3.value = "";			

	}
}


function mismosdomtitneg(){
	EW_this = document.solicitudadd;
	validada = true;
	
	if(EW_this.x_mismosava2.checked == true){
		EW_this.x_mismosava.checked = false;
		if (validada == true && EW_this.x_calle2 && !EW_hasValue(EW_this.x_calle2, "TEXT" )) {
			if (!EW_onError(EW_this, EW_this.x_calle2, "TEXT", "Indique la calle del domicilio del negocio del titular."))
				validada = false;
		}
		
		if (validada == true && EW_this.x_colonia2 && !EW_hasValue(EW_this.x_colonia2, "TEXT" )) {
			if (!EW_onError(EW_this, EW_this.x_colonia2, "TEXT", "Indique la colonia del domicilio del negocio del titular."))
				validada = false;
		}
		if (validada == true && EW_this.x_entidad_id2 && !EW_hasValue(EW_this.x_entidad_id2, "SELECT" )) {
			if (!EW_onError(EW_this, EW_this.x_entidad_id2, "SELECT", "Indique la entidad del domicilio del negocio del titular."))
				validada = false;
		}
		
		if (validada == true && EW_this.x_delegacion_id2 && !EW_hasValue(EW_this.x_delegacion_id2, "SELECT" )) {
			if (!EW_onError(EW_this, EW_this.x_delegacion_id2, "SELECT", "Indique la delegación del domicilio del negocio del titular."))
				validada = false;
		}
		if(validada == false){
			EW_this.x_mismosava2.checked = false;
			EW_this.x_mismosava.checked = false;
		}else{

			EW_this.x_calle3_neg.value = EW_this.x_calle2.value;
			EW_this.x_colonia3_neg.value = EW_this.x_colonia2.value;
			EW_this.x_codigo_postal3_neg.value = EW_this.x_codigo_postal2.value;
			EW_this.x_ubicacion3_neg.value = EW_this.x_ubicacion2.value;
			EW_this.x_antiguedad3_neg.value = EW_this.x_antiguedad2.value;
			EW_this.x_telefono3_neg.value = EW_this.x_telefono2.value;		


			var indice2 = EW_this.x_entidad_id2.selectedIndex;
			EW_this.x_entidad_id3_neg.options[indice2].selected = true;

			showHint(EW_this.x_entidad_id3_neg,'txtHint3_neg', 'x_delegacion_id3_neg', EW_this.x_delegacion_id2.value);

	
		}
	}else{
		EW_this.x_calle3_neg.value = "";
		EW_this.x_colonia3_neg.value = "";
		EW_this.x_entidad_id3_neg.options[0].selected = true;	
		EW_this.x_codigo_postal3_neg.value = "";
		EW_this.x_ubicacion3_neg.value = "";
		EW_this.x_antiguedad3_neg.value = "";
		EW_this.x_telefono3_neg.value = "";			
	}
}

function mismosdomava(){
	EW_this = document.solicitudadd;
	validada = true;
	
	if(EW_this.x_mismosava.checked == true){
		EW_this.x_mismosava2.checked = false;		
		if (validada == true && EW_this.x_calle3 && !EW_hasValue(EW_this.x_calle3, "TEXT" )) {
			if (!EW_onError(EW_this, EW_this.x_calle3, "TEXT", "Indique la calle del domicilio particular del aval."))
				validada = false;
		}
		if (validada == true && EW_this.x_colonia3 && !EW_hasValue(EW_this.x_colonia3, "TEXT" )) {
			if (!EW_onError(EW_this, EW_this.x_colonia3, "TEXT", "Indique la colonia del domicilio particular del aval."))
				validada = false;
		}
		if (validada == true && EW_this.x_entidad_id3 && !EW_hasValue(EW_this.x_entidad_id3, "SELECT" )) {
			if (!EW_onError(EW_this, EW_this.x_entidad_id3, "SELECT", "Indique la entidad del domicilio particular del aval."))
				validada = false;
		}
		
		if (validada == true && EW_this.x_delegacion_id3 && !EW_hasValue(EW_this.x_delegacion_id3, "SELECT" )) {
			if (!EW_onError(EW_this, EW_this.x_delegacion_id3, "SELECT", "Indique la delegación del domicilio particular del aval."))
				validada = false;
		}
		if(validada == false){
			EW_this.x_mismosava.checked = false;
			EW_this.x_mismosava2.checked = false;			
		}else{
			EW_this.x_calle3_neg.value = EW_this.x_calle3.value;
			EW_this.x_colonia3_neg.value = EW_this.x_colonia3.value;
			EW_this.x_codigo_postal3_neg.value = EW_this.x_codigo_postal3.value;
			EW_this.x_ubicacion3_neg.value = EW_this.x_ubicacion3.value;
			EW_this.x_antiguedad3_neg.value = EW_this.x_antiguedad3.value;
			EW_this.x_telefono3_neg.value = EW_this.x_telefono3.value;		
	
			var indice = EW_this.x_entidad_id3.selectedIndex;
			EW_this.x_entidad_id3_neg.options[indice].selected = true;
	
			showHint(EW_this.x_entidad_id3_neg,'txtHint3_neg', 'x_delegacion_id3_neg', EW_this.x_delegacion_id3.value);

	
		}
	}else{
		EW_this.x_calle3_neg.value = "";
		EW_this.x_colonia3_neg.value = "";
		EW_this.x_entidad_id3_neg.options[0].selected = true;
		EW_this.x_entidad3_neg.value = "";
		EW_this.x_codigo_postal3_neg.value = "";
		EW_this.x_ubicacion3_neg.value = "";
		EW_this.x_antiguedad3_neg.value = "";
		EW_this.x_telefono3_neg.value = "";
	}

}



function viviendatipo(viv){
	EW_this = document.solicitudadd;
	if(viv == 1){
		if(EW_this.x_vivienda_tipo_id.value == 2){
			document.getElementById('prop1rentada').className = "TG_visible";
		}else{
			document.getElementById('prop1rentada').className = "TG_hidden";
			EW_this.x_propietario_renta.value = "";
			EW_this.x_gastos_renta_casa.value = "";
		}

		if(EW_this.x_vivienda_tipo_id.value == 3){
			document.getElementById('prop1').className = "TG_visible";
		}else{
			document.getElementById('prop1').className = "TG_hidden";
			EW_this.x_propietario_familiar.value = "";			
		}

		if(EW_this.x_vivienda_tipo_id.value == 4){
			document.getElementById('prop1credito').className = "TG_visible";
		}else{
			document.getElementById('prop1credito').className = "TG_hidden";
			EW_this.x_propietario_ch.value = "";			
			EW_this.x_gastos_credito_hipotecario.value = "";
		}

	}
	if(viv == 2){
		if(EW_this.x_vivienda_tipo_id2.value == 2){
			document.getElementById('prop1rentada2').className = "TG_visible";
		}else{
			document.getElementById('prop1rentada2').className = "TG_hidden";
			EW_this.x_propietario_renta2.value = "";
			EW_this.x_gastos_renta_casa2.value = "";
		}

		if(EW_this.x_vivienda_tipo_id2.value == 3){
			document.getElementById('prop2').className = "TG_visible";
		}else{
			document.getElementById('prop2').className = "TG_hidden";
			EW_this.x_propietario_familiar2.value = "";			
		}

		if(EW_this.x_vivienda_tipo_id2.value == 4){
			document.getElementById('prop1credito2').className = "TG_visible";
		}else{
			document.getElementById('prop1credito2').className = "TG_hidden";
			EW_this.x_propietario_ch2.value = "";			
			EW_this.x_gastos_credito_hipotecario2.value = "";
		}
/*
		if(EW_this.x_vivienda_tipo_id2.value == 3){
			document.getElementById('prop2').className = "TG_visible";
		}else{
			document.getElementById('prop2').className = "TG_hidden";
		}
*/		
	}
}

function EW_checkMyForm() {
EW_this = document.solicitudadd;
validada = true;

if (validada == true && EW_this.x_promotor_id && !EW_hasValue(EW_this.x_promotor_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_promotor_id, "SELECT", "Indique el promotor."))
		validada = false;
}

if (validada == true && EW_this.x_credito_tipo_id && !EW_hasValue(EW_this.x_credito_tipo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_credito_tipo_id, "SELECT", "Indique el crédito deseado."))
		validada = false;
}
if (validada == true && EW_this.x_importe_solicitado && !EW_hasValue(EW_this.x_importe_solicitado, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_importe_solicitado, "TEXT", "Indique el importe del crédito a solicitar."))
		validada = false;
}
if (validada == true && EW_this.x_importe_solicitado && !EW_checknumber(EW_this.x_importe_solicitado.value)) {
	if (!EW_onError(EW_this, EW_this.x_importe_solicitado, "TEXT", "El importe del crédito solicitado es incorrecto, por favor verifiquelo."))
		validada = false;
}
if (validada == true && EW_this.x_plazo_id && !EW_hasValue(EW_this.x_plazo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_plazo_id, "TEXT", "Indique el plazo solicitado."))
		validada = false;
}
if (validada == true && EW_this.x_forma_pago_id && !EW_hasValue(EW_this.x_forma_pago_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_forma_pago_id, "TEXT", "Indique la forma de pago solicitada."))
		validada = false;
}




if (validada == true && EW_this.x_nombre_completo && !EW_hasValue(EW_this.x_nombre_completo, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre_completo, "TEXT", "Indique su Nombre."))
		validada = false;
}
if (validada == true && EW_this.x_apellido_paterno && !EW_hasValue(EW_this.x_apellido_paterno, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_apellido_paterno, "TEXT", "Indique su Apellido Paterno."))
		validada = false;
}

if (validada == true && EW_this.x_tipo_negocio && !EW_hasValue(EW_this.x_tipo_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tipo_negocio, "TEXT", "Indique el tipo de su negocio."))
		validada = false;
}
if (validada == true && EW_this.x_edad && !EW_hasValue(EW_this.x_edad, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_edad, "TEXT", "Indique su Edad."))
		validada = false;
}
if (validada == true && EW_this.x_edad && !EW_checkinteger(EW_this.x_edad.value)) {
	if (!EW_onError(EW_this, EW_this.x_edad, "TEXT", "Su Edad es incorrecta, por favor verifiquela."))
		validada = false;
}
if (validada == true && EW_this.x_sexo && !EW_hasValue(EW_this.x_sexo, "RADIO" )) {
	if (!EW_onError(EW_this, EW_this.x_sexo, "RADIO", "Indique su genero."))
		validada = false;
}
if (validada == true && EW_this.x_estado_civil_id && !EW_hasValue(EW_this.x_estado_civil_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_estado_civil_id, "SELECT", "Indique su Estado Civil."))
		validada = false;
}
/*
if (validada == true && EW_this.x_email && !EW_hasValue(EW_this.x_email, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_email, "TEXT", "Indique su Email."))
		validada = false;
}
if (validada == true && EW_this.x_email && !EW_checkemail(EW_this.x_email.value)) {
	if (!EW_onError(EW_this, EW_this.x_email, "TEXT", "Su Email es incorrecto, por favor verifiquelo."))
		validada = false;
}
*/



if (validada == true && EW_this.x_calle && !EW_hasValue(EW_this.x_calle, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_calle, "TEXT", "Indique la calle del domicilio particular."))
		validada = false;
}
if (validada == true && EW_this.x_colonia && !EW_hasValue(EW_this.x_colonia, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_colonia, "TEXT", "Indique la colonia del domicilio particular."))
		validada = false;
}
/*
if (validada == true && EW_this.x_delegacion_id && !EW_hasValue(EW_this.x_delegacion_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_delegacion_id, "SELECT", "Indique la delegación del domicilio particular."))
		validada = false;
}
if (validada == true && EW_this.x_delegacion_id.value == 17) {
	if (validada == true && EW_this.x_otra_delegacion && !EW_hasValue(EW_this.x_otra_delegacion, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_otra_delegacion, "TEXT", "Indique la delegación del domicilio particular."))
			validada = false;
	}
}
*/
/*
if (validada == true && EW_this.x_entidad && !EW_hasValue(EW_this.x_entidad, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_entidad, "TEXT", "Indique la entidad del domicilio particular."))

		validada = false;
}
if (validada == true && EW_this.x_codigo_postal && !EW_hasValue(EW_this.x_codigo_postal, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal, "TEXT", "Indique el Código Postal del domicilio particular."))
		validada = false;
}
if (validada == true && EW_this.x_ubicacion && !EW_hasValue(EW_this.x_ubicacion, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ubicacion, "TEXT", "Indique la Ubicación del domicilio particular."))
		validada = false;
}
if (validada == true && EW_this.x_antiguedad && !EW_hasValue(EW_this.x_antiguedad, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_antiguedad, "TEXT", "Indique la Antiguedad en el domicilio particular."))
		validada = false;
}
if (validada == true && EW_this.x_antiguedad && !EW_checkinteger(EW_this.x_antiguedad.value)) {
	if (!EW_onError(EW_this, EW_this.x_antiguedad, "TEXT", "La Antiguedad del domicilio particular es incorrecta, por favor veriqfiquela."))
		validada = false;
}
if (validada == true && EW_this.x_vivienda_tipo_id && !EW_hasValue(EW_this.x_vivienda_tipo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_vivienda_tipo_id, "SELECT", "Indique el tipo de vivienda del domicilio particular."))
		validada = false;
}
*/


/*

if (validada == true && EW_this.x_calle2 && !EW_hasValue(EW_this.x_calle2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_calle2, "TEXT", "Indique la calle del domicilio de negocio."))
		validada = false;
}
if (validada == true && EW_this.x_colonia2 && !EW_hasValue(EW_this.x_colonia2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_colonia2, "TEXT", "Indique la colonia del domicilio de negocio."))
		validada = false;
}
if (validada == true && EW_this.x_delegacion_id2 && !EW_hasValue(EW_this.x_delegacion_id2, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_delegacion_id2, "SELECT", "Indique la delegación del domicilio de negocio."))
		validada = false;
}
if (validada == true && EW_this.x_delegacion_id2.value == 17) {
	if (validada == true && EW_this.x_otra_delegacion2 && !EW_hasValue(EW_this.x_otra_delegacion2, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_otra_delegacion2, "TEXT", "Indique la delegación del domicilio de negocio."))
			validada = false;
	}
}

if (validada == true && EW_this.x_entidad2 && !EW_hasValue(EW_this.x_entidad2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_entidad2, "TEXT", "Indique la entidad del domicilio de negocio."))
		validada = false;
}
if (validada == true && EW_this.x_codigo_postal2 && !EW_hasValue(EW_this.x_codigo_postal2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal2, "TEXT", "Indique el Código Postal del domicilio de negocio."))
		validada = false;
}
if (validada == true && EW_this.x_ubicacion2 && !EW_hasValue(EW_this.x_ubicacion2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ubicacion2, "TEXT", "Indique la Ubicación del domicilio de negocio."))
		validada = false;
}
if (validada == true && EW_this.x_antiguedad2 && !EW_hasValue(EW_this.x_antiguedad2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_antiguedad2, "TEXT", "Indique la Antiguedad en el domicilio de negocio."))
		validada = false;
}
if (validada == true && EW_this.x_antiguedad2 && !EW_checkinteger(EW_this.x_antiguedad2.value)) {
	if (!EW_onError(EW_this, EW_this.x_antiguedad2, "TEXT", "La Antiguedad del domicilio de negocio es incorrecta, por favor veriqfiquela."))
		validada = false;
}

*/

//if(document.getElementById('aval').className == "TG_visible"){

if(1==2){	
	if (validada == true && EW_this.x_nombre_completo_aval && !EW_hasValue(EW_this.x_nombre_completo_aval, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_nombre_completo_aval, "TEXT", "Indique el nombre del Aval."))
			validada = false;
	}
	if (validada == true && EW_this.x_apellido_paterno_aval && !EW_hasValue(EW_this.x_apellido_paterno_aval, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_apellido_paterno_aval, "TEXT", "Indique el apellido paterno del Aval."))
			validada = false;
	}
	if (validada == true && EW_this.x_apellido_materno_aval && !EW_hasValue(EW_this.x_apellido_materno_aval, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_apellido_materno_aval, "TEXT", "Indique el apellido materno del Aval."))
			validada = false;
	}
	
	
	if (validada == true && EW_this.x_parentesco_tipo_id_aval && !EW_hasValue(EW_this.x_parentesco_tipo_id_aval, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_aval, "SELECT", "Indique el parentesco del Aval."))
			validada = false;
	}
	if (validada == true && EW_this.x_telefono3 && !EW_hasValue(EW_this.x_telefono3, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_telefono3, "TEXT", "Indique el teléfono del Aval."))
			validada = false;
	}
	

	if (validada == true && EW_this.x_calle3 && !EW_hasValue(EW_this.x_calle3, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_calle3, "TEXT", "Indique la calle del domicilio del aval."))
			validada = false;
	}
	if (validada == true && EW_this.x_colonia3 && !EW_hasValue(EW_this.x_colonia3, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_colonia3, "TEXT", "Indique la colonia del domicilio del aval."))
			validada = false;
	}
	if (validada == true && EW_this.x_delegacion_id3 && !EW_hasValue(EW_this.x_delegacion_id3, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_delegacion_id3, "SELECT", "Indique la delegación del domicilio del aval."))
			validada = false;
	}
	
	if (validada == true && EW_this.x_delegacion_id3.value == 17) {
		if (validada == true && EW_this.x_otra_delegacion3 && !EW_hasValue(EW_this.x_otra_delegacion3, "TEXT" )) {
			if (!EW_onError(EW_this, EW_this.x_otra_delegacion3, "TEXT", "Indique la delegación del domicilio del aval."))
				validada = false;
		}
	}
	
	if (validada == true && EW_this.x_entidad3 && !EW_hasValue(EW_this.x_entidad3, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_entidad3, "TEXT", "Indique la entidad del domicilio del aval."))
			validada = false;
	}
	if (validada == true && EW_this.x_codigo_postal3 && !EW_hasValue(EW_this.x_codigo_postal3, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_codigo_postal3, "TEXT", "Indique el Código Postal del domicilio del aval."))
			validada = false;
	}
	if (validada == true && EW_this.x_ubicacion3 && !EW_hasValue(EW_this.x_ubicacion3, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_ubicacion3, "TEXT", "Indique la Ubicación del domicilio del aval."))
			validada = false;
	}
	if (validada == true && EW_this.x_vivienda_tipo_id2 && !EW_hasValue(EW_this.x_vivienda_tipo_id2, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_vivienda_tipo_id2, "SELECT", "Indique el tipo de vivienda del domicilio deL aval."))
			validada = false;
	}

	if (validada == true && EW_this.x_ingresos_mensuales && !EW_hasValue(EW_this.x_ingresos_mensuales, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_ingresos_mensuales, "TEXT", "Indique los ingresos mensuales del Aval."))
			validada = false;
	}
	if (validada == true && EW_this.x_ingresos_mensuales && !EW_checknumber(EW_this.x_ingresos_mensuales.value)) {
		if (!EW_onError(EW_this, EW_this.x_ingresos_mensuales, "TEXT", "Los ingresos mensuales del Aval son incorrectos, por favor verifiquelos."))
			validada = false;
	}
	if (validada == true && EW_this.x_ocupacion && !EW_hasValue(EW_this.x_ocupacion, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_ocupacion, "TEXT", "Indique la ocupación del Aval."))
			validada = false;
	}	
}



// if(document.getElementById('garantias').className == "TG_visible"){

if(1==2){
	if (validada == true && EW_this.x_garantia_desc && !EW_hasValue(EW_this.x_garantia_desc, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_garantia_desc, "TEXT", "Indique la descripción de la garantía."))
			validada = false;
	}
	if (validada == true && EW_this.x_garantia_valor && !EW_hasValue(EW_this.x_garantia_valor, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_garantia_valor, "TEXT", "Indique el valor de la garantía."))
			validada = false;
	}
	if (validada == true && EW_this.x_garantia_valor && !EW_checknumber(EW_this.x_garantia_valor.value)) {
		if (!EW_onError(EW_this, EW_this.x_garantia_valor, "TEXT", "El valor de la garantía es incorrecto, por favor verifiquelo."))
			validada = false;
	}
}



if (validada == true && EW_this.x_ingresos_negocio && !EW_checknumber(EW_this.x_ingresos_negocio.value)) {
	if (!EW_onError(EW_this, EW_this.x_ingresos_negocio, "TEXT", "Los ingresos del negocio son incorrectos, por favor verifiquelos."))
		validada = false;
}
if (validada == true && EW_this.x_ingresos_familiar_1 && !EW_checknumber(EW_this.x_ingresos_familiar_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_ingresos_familiar_1, "TEXT", "Los ingresos familiares 1 son incorrectos, por favor verifiquelos."))
		validada = false;
}

if(EW_this.x_ingresos_familiar_1.value > 0){
	if (validada == true && EW_this.x_parentesco_tipo_id_ing_1 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ing_1, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ing_1, "SELECT", "Indique el parentesco 1 en ingresos familiares."))
			validada = false;
	}
}

if (validada == true && EW_this.x_ingresos_familiar_2 && !EW_checknumber(EW_this.x_ingresos_familiar_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_ingresos_familiar_2, "TEXT", "Los ingresos familiares 2 son incorrectos, por favor verifiquelos."))
		validada = false;
}
if(EW_this.x_ingresos_familiar_2.value > 0){
	if (validada == true && EW_this.x_parentesco_tipo_id_ing_2 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ing_2, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ing_2, "SELECT", "indique el parentesco 2, en ingresos familiares."))
			validada = false;
	}
}
if (validada == true && EW_this.x_otros_ingresos && !EW_checknumber(EW_this.x_otros_ingresos.value)) {
	if (!EW_onError(EW_this, EW_this.x_otros_ingresos, "TEXT", "Los Otros ingresos son incorrectos, por favor verifiquelos."))
		validada = false;
}



if (validada == true && EW_this.x_nombre_completo_ref_1 && !EW_hasValue(EW_this.x_nombre_completo_ref_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre_completo_ref_1, "TEXT", "Indique el Nombre completo de la Referencia 1."))
		validada = false;
}
if (validada == true && EW_this.x_telefono_ref_1 && !EW_hasValue(EW_this.x_telefono_ref_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_telefono_ref_1, "TEXT", "Indique el teléfono de la referencia 1."))
		validada = false;
}
if (validada == true && EW_this.x_parentesco_tipo_id_ref_1 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_1, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_1, "SELECT", "Indique el parentesco en la referencia 1."))
		validada = false;
}

if (validada == true && EW_this.x_nombre_completo_ref_2 && EW_hasValue(EW_this.x_nombre_completo_ref_2, "TEXT" )) {
	if (validada == true && EW_this.x_telefono_ref_2 && !EW_hasValue(EW_this.x_telefono_ref_2, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_telefono_ref_2, "TEXT", "Indique el teléfono de la referencia 2."))
			validada = false;
	}
	if (validada == true && EW_this.x_parentesco_tipo_id_ref_2 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_2, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_2, "SELECT", "Indique el parentesco en la referencia 2."))
			validada = false;
	}
}

if (validada == true && EW_this.x_nombre_completo_ref_3 && EW_hasValue(EW_this.x_nombre_completo_ref_3, "TEXT" )) {
	if (validada == true && EW_this.x_telefono_ref_3 && !EW_hasValue(EW_this.x_telefono_ref_3, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_telefono_ref_3, "TEXT", "Indique el teléfono de la referencia 3."))
			validada = false;
	}
	if (validada == true && EW_this.x_parentesco_tipo_id_ref_3 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_3, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_3, "SELECT", "Indique el parentesco en la referencia 3."))
			validada = false;
	}
}

if (validada == true && EW_this.x_nombre_completo_ref_4 && EW_hasValue(EW_this.x_nombre_completo_ref_4, "TEXT" )) {
	if (validada == true && EW_this.x_telefono_ref_4 && !EW_hasValue(EW_this.x_telefono_ref_4, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_telefono_ref_4, "TEXT", "Indique el teléfono de la referencia 4."))
			validada = false;
	}
	if (validada == true && EW_this.x_parentesco_tipo_id_ref_4 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_4, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_4, "SELECT", "Indique el parentesco en la referencia 4."))
			validada = false;
	}
}
if (validada == true && EW_this.x_nombre_completo_ref_5 && EW_hasValue(EW_this.x_nombre_completo_ref_5, "TEXT" )) {
	if (validada == true && EW_this.x_telefono_ref_5 && !EW_hasValue(EW_this.x_telefono_ref_5, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_telefono_ref_5, "TEXT", "Indique el teléfono de la referencia 5."))
			validada = false;
	}
	if (validada == true && EW_this.x_parentesco_tipo_id_ref_5 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_5, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_5, "SELECT", "Indique el parentesco en la referencia 5."))
			validada = false;
	}
}



/*
if(validada == true && EW_this.x_acepto.checked == false){
	alert("Debe de marcar la casilla: Aceptao los términos y condiciones.");
	validada = false;
}
*/
if(validada == true){
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

<!-- <form name="solicitudadd" id="solicitudadd" action="php_solicitudadd.php" method="post" > -->
<p>
<input type="hidden" name="a_add" value="A">


<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
  
  <tr>
    <td colspan="3" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Datos Personales</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	  <td width="165" class="texto_normal">Titular:</td>
	  <td colspan="4"><table width="534" border="0" cellspacing="0" cellpadding="0">
	    <tr>
	      <td width="155"><div align="center">
	        <input name="x_nombre_completo" type="text" class="texto_normal" id="x_nombre_completo" value="<?php echo htmlspecialchars(@$x_nombre_completo) ?>" size="25" maxlength="250" />
	      </div></td>
	      <td width="178"><div align="center">
	        <input name="x_apellido_paterno" type="text" class="texto_normal" id="x_apellido_paterno" value="<?php echo htmlspecialchars(@$x_apellido_paterno) ?>" size="25" maxlength="250" />
	      </div></td>
	      <td width="201"><div align="center">
	        <input name="x_apellido_materno" type="text" class="texto_normal" id="x_apellido_materno" value="<?php echo htmlspecialchars(@$x_apellido_materno) ?>" size="25" maxlength="250" />
	      </div></td>
	      </tr>
	    </table></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td colspan="4"><table width="534" border="0" cellspacing="0" cellpadding="0">
	    <tr>
	      <td width="155"><div align="center"><span class="texto_normal">Nombre</span></div></td>
	      <td width="178"><div align="center"><span class="texto_normal">Apellido Paterno</span></div></td>
	      <td width="201"><div align="center"><span class="texto_normal">Apellido Materno</span></div></td>
	      </tr>
	    </table></td>
	  </tr>
	<tr>
	  <td class="texto_normal">RFC:</td>
	  <td colspan="4">
	    <input name="x_tit_rfc" type="text" class="texto_normal" id="x_tit_rfc" value="<?php echo htmlspecialchars(@$x_tit_rfc) ?>" size="20" maxlength="20" /></td>
	  </tr>
	<tr>
	  <td class="texto_normal">CURP:</td>
	  <td colspan="4">
      <input name="x_tit_curp" type="text" class="texto_normal" id="x_tit_curp" value="<?php echo htmlspecialchars(@$x_tit_curp) ?>" size="20" maxlength="20" /></td>
	  </tr>
	<tr>
	  <td><span class="texto_normal">Tipo de Negocio: </span></td>
	  <td colspan="4"><input name="x_tipo_negocio" type="text" class="texto_normal" id="x_tipo_negocio" value="<?php echo htmlspecialchars(@$x_tipo_negocio) ?>" size="80" maxlength="250" /></td>
	  </tr>
	<tr>
	  <td><span class="texto_normal">Fecha de Nacimiento:</span></td>
	  <td colspan="4"><table width="533" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="121" align="left">
            <span class="texto_normal">
            <input name="x_tit_fecha_nac" type="text" id="x_tit_fecha_nac" value="<?php echo FormatDateTime(@$x_tit_fecha_nac,7); ?>" size="12" maxlength="12">
            &nbsp;<img src="../images/ew_calendar.gif" id="cx_fecha_nacimiento" onmouseover="javascript: Calendar.setup(
            {
            inputField : 'x_tit_fecha_nac', 
            ifFormat : '%d/%m/%Y', 
            button : 'cx_fecha_nacimiento' 
            }
            );" style="cursor:pointer;cursor:hand;" />
              </span>       </td>
          <td width="160" align="left" valign="middle"><div align="left"><span class="texto_normal">Genero:
            <input name="x_sexo" type="radio" value="<?php echo htmlspecialchars("1"); ?>" checked="checked"<?php if (@$x_sexo == "1") { ?> checked<?php } ?> />
              <?php echo "M"; ?> <?php echo EditOptionSeparator(0); ?>
              <input type="radio" name="x_sexo"<?php if (@$x_sexo == "2") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("2"); ?>" />
              <?php echo "F"; ?> <?php echo EditOptionSeparator(1); ?> </span></div></td>
          <td width="243"><div align="left"><span class="texto_normal">Edo. Civil:
            <?php
		$x_estado_civil_idList = "<select name=\"x_estado_civil_id\" class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `estado_civil_id`, `descripcion` FROM `estado_civil`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["estado_civil_id"] == @$x_estado_civil_id) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?>
            </span></div></td>
        </tr>
      </table></td>
	  </tr>
	
      <tr>
        <td><span class="texto_normal">&nbsp;No. de hijos
              : </span></td>
        <td colspan="3"><span class="texto_normal">
          <input name="x_numero_hijos" type="text" class="texto_normal" id="x_numero_hijos"  onkeypress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_numero_hijos) ?>" size="2" maxlength="1"/>
        Hijos dependientes:
        <input name="x_numero_hijos_dep" type="text" class="texto_normal" id="x_numero_hijos_dep"  onkeypress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_numero_hijos_dep) ?>" size="2" maxlength="1"/>
        </span></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Nombre del Conyuge:</span></td>
        <td width="535" colspan="3">
		<input name="x_nombre_conyuge" type="text" class="texto_normal" id="x_nombre_conyuge" value="<?php echo htmlspecialchars(@$x_nombre_conyuge) ?>" size="80" maxlength="250">		</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Email</span>:</td>
        <td colspan="3"><input name="x_email" type="text" class="texto_normal" id="x_email" value="<?php echo htmlspecialchars(@$x_email) ?>" size="50" maxlength="150" /></td>
      </tr>
      <tr>
        <td class="texto_normal">Nacionalidad:</td>
        <td colspan="3"><span class="texto_normal">
          <?php
		$x_nac_idList = "<select name=\"x_nacionalidad_id\" class=\"texto_normal\">";
		$x_nac_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT nacionalidad_id, pais_nombre FROM nacionalidad";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_nac_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["nacionalidad_id"] == @$x_nacionalidad_id) {
					$x_nac_idList .= "' selected";
				}
				$x_nac_idList .= ">" . htmlentities($datawrk["pais_nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_nac_idList .= "</select>";
		echo $x_nac_idList;

		?>
        </span></td>
      </tr>
	</table>	</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Domicilio Particular </td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="165"><span class="texto_normal">Calle no. Ext e Int. : </span></td>
        <td colspan="3"><input name="x_calle" type="text" class="texto_normal" id="x_calle" value="<?php echo htmlspecialchars(@$x_calle) ?>" size="80" maxlength="150" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Colonia: </span></td>
        <td colspan="3"><input name="x_colonia" type="text" class="texto_normal" id="x_colonia" value="<?php echo htmlspecialchars(@$x_colonia) ?>" size="80" maxlength="150" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Entidad:</span></td>
        <td width="172"><span class="texto_normal">
		<?php
		$x_delegacion_idList = "<select name=\"x_entidad_id\" class=\"texto_normal\" onchange=\"showHint(this,'txtHint1', 'x_delegacion_id')\">";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `entidad_id`, `nombre` FROM `entidad`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["entidad_id"] == @$x_entidad_id) {
					$x_delegacion_idList .= "' selected";
				}
				$x_delegacion_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_delegacion_idList .= "</select>";
		echo $x_delegacion_idList;
		?>
        </span></td>
        <td width="309"><div align="left"><span class="texto_normal">
        </span><span class="texto_normal">
		<div id="txtHint1" class="texto_normal"></div>        
        </span></div></td>
        <td width="54"><div align="left"></div></td>
      </tr>

	    <tr>
	      <td><span class="texto_normal">C.P.
          : </span></td>
	      <td colspan="4"><span class="texto_normal">
	        <input name="x_codigo_postal" type="text" class="texto_normal" id="x_codigo_postal" onkeypress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_codigo_postal) ?>" size="5" maxlength="10" />
	      </span></td>
	      </tr>
	    <td><span class="texto_normal">Referencia de Ubicación:</span></td>
	  <td colspan="4"><input name="x_ubicacion" type="text" class="texto_normal" id="x_ubicacion" value="<?php echo htmlspecialchars(@$x_ubicacion) ?>" size="80" maxlength="250" /></td>
	  </tr>
	<tr>
	  <td class="texto_normal">Antiguedad en Domicilio: </td>
	  <td colspan="4"><span class="texto_normal">
	    <input name="x_antiguedad" type="text" class="texto_normal" id="x_antiguedad" onKeyPress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_antiguedad) ?>" size="2" maxlength="2"/>
(años)</span></td>
	  </tr>
	<tr>
	  <td class="texto_normal">Tipo de Vivienda: </td>
	  <td colspan="4"><span class="texto_normal">
	 <?php
		$x_vivienda_tipo_idList = "<select name=\"x_vivienda_tipo_id\" class=\"texto_normal\" onchange=\"viviendatipo('1')\">";
		$x_vivienda_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `vivienda_tipo_id`, `descripcion` FROM `vivienda_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_vivienda_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["vivienda_tipo_id"] == @$x_vivienda_tipo_id) {
					$x_vivienda_tipo_idList .= "' selected";
				}
				$x_vivienda_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_vivienda_tipo_idList .= "</select>";
		echo $x_vivienda_tipo_idList;
		?>
	  </span></td>
	  </tr>
      <tr>
        <td > </td>
        <td colspan="4" class="texto_normal">

		<div id="prop1rentada" class="<?php if($x_vivienda_tipo_id == 2){ echo "TG_visible";}else{ echo "TG_hidden";} ?>">
		Arrendatario (Nombre y Tel):&nbsp;
		<input class="texto_normal" type="text" name="x_propietario_renta" id="x_propietario_renta" value="<?php echo $x_propietario; ?>" size="25" maxlength="150" />&nbsp;
		Renta:
		<input class="importe" name="x_gastos_renta_casa" type="text" id="x_gastos_renta_casa" value="<?php echo htmlspecialchars(@$x_gastos_renta_casa) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/>        
		</div>		
        
		<div id="prop1" class="<?php if($x_vivienda_tipo_id == 3){ echo "TG_visible";}else{ echo "TG_hidden";} ?>">
		Propietario de la Vivienda:&nbsp;
		<input class="texto_normal" type="text" name="x_propietario_familiar" id="x_propietario_familiar" value="<?php echo $x_propietario; ?>" size="50" maxlength="150" />
		</div>		

		<div id="prop1credito" class="<?php if($x_vivienda_tipo_id == 4){ echo "TG_visible";}else{ echo "TG_hidden";} ?>">
		Empresa:&nbsp;
		<input class="texto_normal" type="text" name="x_propietario_ch" id="x_propietario_ch" value="<?php echo $x_propietario; ?>" size="25" maxlength="150" />&nbsp;
        Pago Mensual:
		<input class="importe" name="x_gastos_credito_hipotecario" type="text" id="x_gastos_credito_hipotecario" value="<?php echo htmlspecialchars(@$x_gastos_credito_hipotecario) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/>        
		</div>		
        
        </td>
      </tr>
	<tr>
	  <td class="texto_normal">Tels. Particular: </td>
	  <td colspan="4"><input name="x_telefono" type="text" class="texto_normal" id="x_telefono" value="<?php echo htmlspecialchars(@$x_telefono) ?>" size="20" maxlength="20" />
	    <span class="texto_normal">&nbsp;- 
	    <input name="x_telefono_sec" type="text" class="texto_normal" id="x_telefono_sec" value="<?php echo htmlspecialchars(@$x_telefono_sec) ?>" size="20" maxlength="20" />
	    </span></td>
	  </tr>
	<tr>
	  <td class="texto_normal">Tel. Celular: </td>
	  <td colspan="4"><span class="texto_normal">
	    <input name="x_telefono_secundario" type="text" class="texto_normal" id="x_telefono_secundario" value="<?php echo htmlspecialchars(@$x_telefono_secundario) ?>" size="20" maxlength="20" />
	  </span></td>
	  </tr>
    </table></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Domicilio del negocio </td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top"><table width="700" border="0" cellspacing="0" cellpadding="0">

      <tr>
        <td colspan="4" style=" border: solid 1px #666">
		<div align="left">
          <input type="checkbox" name="x_mismos" value="0" onclick="mismosdom()" />
         <span class="texto_normal">Mismos que el Dom. Part.</span>		</div>		</td>
        </tr>	
      <tr>
        <td>&nbsp;</td>
        <td colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Empresa: </span></td>
        <td colspan="3"><input name="x_empresa" type="text" class="texto_normal" id="x_empresa" value="<?php echo htmlspecialchars(@$x_empresa) ?>" size="80" maxlength="250" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Puesto: </span></td>
        <td colspan="3"><input name="x_puesto" type="text" class="texto_normal" id="x_puesto" value="<?php echo htmlspecialchars(@$x_puesto) ?>" size="80" maxlength="250" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Fecha Contratacion:</span></td>
        <td colspan="3">
		<span class="texto_normal">
        <input name="x_fecha_contratacion" type="text" id="x_fecha_contratacion" value="<?php echo FormatDateTime(@$x_fecha_contratacion,7); ?>" size="12" maxlength="12">
             &nbsp;<img src="../images/ew_calendar.gif" id="cx_fecha_contratacion" onmouseover="javascript: Calendar.setup(
            {
            inputField : 'x_fecha_contratacion', 
            ifFormat : '%d/%m/%Y', 
            button : 'cx_fecha_contratacion' 
            }
            );" style="cursor:pointer;cursor:hand;" />
              </span>                      
        </td>
      </tr>
      <tr>
        <td><span class="texto_normal">Salario Mensual: </span></td>
        <td colspan="3"><input class="importe" name="x_salario_mensual" type="text" id="x_salario_mensual" value="<?php echo htmlspecialchars(@$x_salario_mensual) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"  /></td>
      </tr>
      <tr>
        <td width="165"><span class="texto_normal">Calle no. Ext e Int. : </span></td>
        <td colspan="3"><input name="x_calle2" type="text" class="texto_normal" id="x_calle2" value="<?php echo htmlspecialchars(@$x_calle2) ?>" size="80" maxlength="150" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Colonia: </span></td>
        <td colspan="3"><input name="x_colonia2" type="text" class="texto_normal" id="x_colonia2" value="<?php echo htmlspecialchars(@$x_colonia2) ?>" size="80" maxlength="150" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Entidad:</span></td>
        <td width="172"><span class="texto_normal">
        <?php
		$x_delegacion_idList = "<select name=\"x_entidad_id2\" class=\"texto_normal\" onchange=\"showHint(this,'txtHint2', 'x_delegacion_id2')\">";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `entidad_id`, `nombre` FROM `entidad`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["entidad_id"] == @$x_entidad_id2) {
					$x_delegacion_idList .= "' selected";
				}
				$x_delegacion_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_delegacion_idList .= "</select>";
		echo $x_delegacion_idList;
		?>
        </span></td>
        <td width="309"><div align="left"><span class="texto_normal">
		<input type="hidden" name="x_delegacion_id_temp" value="" />
        </span><span class="texto_normal">
        <div id="txtHint2" class="texto_normal"></div>
        </span></div></td>
        <td width="54"><div align="left"></div></td>
      </tr>
      <tr>
        <td><span class="texto_normal">C.P.
          :</span></td>
        <td colspan="4"><span class="texto_normal">
          <input name="x_codigo_postal2" type="text" class="texto_normal" id="x_codigo_postal2" onkeypress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_codigo_postal2) ?>" size="5" maxlength="10"/>
        </span></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Referencia de Ubicación:</span></td>
        <td colspan="4"><input name="x_ubicacion2" type="text" class="texto_normal" id="x_ubicacion2" value="<?php echo htmlspecialchars(@$x_ubicacion2) ?>" size="80" maxlength="250" /></td>
      </tr>
      <tr>
        <td class="texto_normal">Antiguedad en Domicilio: </td>
        <td colspan="4"><span class="texto_normal">
          <input name="x_antiguedad2" type="text" class="texto_normal" id="x_antiguedad2" onKeyPress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_antiguedad2) ?>" size="2" maxlength="2"/>
        (años)</span></td>
      </tr>
      <tr>
        <td class="texto_normal">Tel.: </td>
        <td colspan="4"><input name="x_telefono2" type="text" class="texto_normal" id="x_telefono2" value="<?php echo htmlspecialchars(@$x_telefono2) ?>" size="20" maxlength="20" />
          <span class="texto_normal">&nbsp;        </span></td>
      </tr>
    </table></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td colspan="3" align="left" valign="top">
	
<!--	<div id="aval" class="TG_hidden"> -->
	
	
	
<!-- 	</div>	-->	</td>
    </tr>

 
 
  
  

  <tr>
    <td colspan="3" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Ingresos Mensuales Titular</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="240"><span class="texto_normal">Ingresos del Negocio:</span></td>
        <td width="122"><input class="importe" name="x_ingresos_negocio" type="text" id="x_ingresos_negocio" value="<?php echo htmlspecialchars(@$x_ingresos_negocio) ?>" size="10" maxlength="10" onKeyPress="return solonumeros(this,event)"/></td>
        <td width="123" class="texto_normal">&nbsp;</td>
        <td width="215">&nbsp;</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Otros Ingresos: </span></td>
        <td><input class="importe" name="x_otros_ingresos" type="text" id="x_otros_ingresos" value="<?php echo htmlspecialchars(@$x_otros_ingresos) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)" /></td>
        <td class="texto_normal">Origen:</td>
        <td>
		<input class="texto_normal" name="x_origen_ingresos" type="text" id="x_origen_ingresos" value="<?php echo htmlspecialchars(@$x_origen_ingresos) ?>" size="30" maxlength="150" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Ingresos Familiares: </span></td>
        <td><input class="importe" name="x_ingresos_familiar_1" type="text" id="x_ingresos_familiar_1" value="<?php echo htmlspecialchars(@$x_ingresos_familiar_1) ?>" size="10" maxlength="10" onKeyPress="return solonumeros(this,event)"/></td>
        <td><span class="texto_normal">Parentesco: </span></td>
        <td>
		<?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_tipo_id_ing_1\" class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_tipo_id_ing_1) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?>		</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Origen: </span></td>
        <td colspan="3"><div align="left">
          <input class="texto_normal" name="x_origen_ingresos2" type="text" id="x_origen_ingresos2" value="<?php echo htmlspecialchars(@$x_origen_ingresos2) ?>" size="30" maxlength="150" />
        </div></td>
        </tr>
      <tr>
        <td><span class="texto_normal">Ingresos Familiares: </span></td>
        <td><input class="importe" name="x_ingresos_familiar_2" type="text" id="x_ingresos_familiar_2" value="<?php echo htmlspecialchars(@$x_ingresos_familiar_2) ?>" size="10" maxlength="10" onKeyPress="return solonumeros(this,event)" /></td>
        <td><span class="texto_normal">Parentesco:</span></td>
        <td>
		<?php
		$x_parentesco_tipo_id2List = "<select name=\"x_parentesco_tipo_id_ing_2\" class=\"texto_normal\">";
		$x_parentesco_tipo_id2List .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_id2List .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_tipo_id_ing_2) {
					$x_parentesco_tipo_id2List .= "' selected";
				}
				$x_parentesco_tipo_id2List .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_id2List .= "</select>";
		echo $x_parentesco_tipo_id2List;
		?>		</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Origen: </span></td>
        <td colspan="3"><div align="left">
          <input class="texto_normal" name="x_origen_ingresos3" type="text" id="x_origen_ingresos3" value="<?php echo htmlspecialchars(@$x_origen_ingresos3) ?>" size="30" maxlength="150" />
        </div></td>
        </tr>
      
    </table></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="middle" bgcolor="#FFE6E6">Gastos Mensuales Titular</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="241">Proveedor 1:</td>
        <td width="459"><input class="importe" name="x_gastos_prov1" type="text" id="x_gastos_prov1" value="<?php echo htmlspecialchars(@$x_gastos_prov1) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td>Proveedor 2:</td>
        <td><input class="importe" name="x_gastos_prov2" type="text" id="x_gastos_prov2" value="<?php echo htmlspecialchars(@$x_gastos_prov2) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td>Proveedor 3:</td>
        <td><input class="importe" name="x_gastos_prov3" type="text" id="x_gastos_prov3" value="<?php echo htmlspecialchars(@$x_gastos_prov3) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td>Otro Proveedor:</td>
        <td><input class="importe" name="x_otro_prov" type="text" id="x_otro_prov" value="<?php echo htmlspecialchars(@$x_otro_prov) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td>Empleados:</td>
        <td><input class="importe" name="x_gastos_empleados" type="text" id="x_gastos_empleados" value="<?php echo htmlspecialchars(@$x_gastos_empleados) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td>Renta Local u Oficina del Negocio:</td>
        <td><input class="importe" name="x_gastos_renta_negocio" type="text" id="x_gastos_renta_negcio" value="<?php echo htmlspecialchars(@$x_gastos_renta_negocio) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td>Otros Gastos:</td>
        <td><input class="importe" name="x_gastos_otros" type="text" id="x_gastos_otros" value="<?php echo htmlspecialchars(@$x_gastos_otros) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Ingresos Mensuales Aval</td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top" class="texto_normal_bold">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top" class="texto_normal_bold"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="240"><span class="texto_normal">Ingresos del Negocio:</span></td>
        <td width="122"><input class="importe" name="x_ingresos_mensuales" type="text" id="x_ingresos_mensuales" value="<?php echo htmlspecialchars(@$x_ingresos_mensuales) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
        <td width="123" class="texto_normal">&nbsp;</td>
        <td width="215">&nbsp;</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Otros Ingresos: </span></td>
        <td><input class="importe" name="x_otros_ingresos_aval" type="text" id="x_otros_ingresos_aval" value="<?php echo htmlspecialchars(@$x_otros_ingresos_aval) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)" /></td>
        <td class="texto_normal">Origen:</td>
        <td><span class="texto_normal">
          <input class="texto_normal" name="x_origen_ingresos_aval" type="text" id="x_origen_ingresos_aval" value="<?php echo htmlspecialchars(@$x_origen_ingresos_aval) ?>" size="30" maxlength="150" />
        </span></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Ingresos Familiares: </span></td>
        <td><input class="importe" name="x_ingresos_familiar_1_aval" type="text" id="x_ingresos_familiar_1_aval" value="<?php echo htmlspecialchars(@$x_ingresos_familiar_1_aval) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
        <td><span class="texto_normal">Parentesco: </span></td>
        <td><?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_tipo_id_ing_1_aval\" class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_tipo_id_ing_1_aval) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Origen: </span></td>
        <td colspan="3"><div align="left"><span class="texto_normal">
          <input class="texto_normal" name="x_origen_ingresos_aval2" type="text" id="x_origen_ingresos_aval2" value="<?php echo htmlspecialchars(@$x_origen_ingresos_aval2) ?>" size="30" maxlength="150" />
        </span></div></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Ingresos Familiares: </span></td>
        <td><input class="importe" name="x_ingresos_familiar_2_aval" type="text" id="x_ingresos_familiar_2_aval" value="<?php echo htmlspecialchars(@$x_ingresos_familiar_2_aval) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
        <td><span class="texto_normal">Parentesco:</span></td>
        <td><?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_tipo_id_ing_2_aval\" class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_tipo_id_ing_2_aval) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Origen: </span></td>
        <td colspan="3"><div align="left"><span class="texto_normal">
          <input class="texto_normal" name="x_origen_ingresos_aval3" type="text" id="x_origen_ingresos_aval3" value="<?php echo htmlspecialchars(@$x_origen_ingresos_aval3) ?>" size="30" maxlength="150" />
        </span></div></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="top" class="texto_normal_bold">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Gastos Mensuales Aval</td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top" class="texto_normal_bold">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top" class="texto_normal_bold"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="241">Proveedor 1:</td>
        <td width="459"><input class="importe" name="x_gastos_prov1_aval" type="text" id="x_gastos_prov1_aval" value="<?php echo htmlspecialchars(@$x_gastos_prov1_aval) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td>Proveedor 2:</td>
        <td><input class="importe" name="x_gastos_prov2_aval" type="text" id="x_gastos_prov2_aval" value="<?php echo htmlspecialchars(@$x_gastos_prov2_aval) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td>Proveedor 3:</td>
        <td><input class="importe" name="x_gastos_prov3_aval" type="text" id="x_gastos_prov3_aval" value="<?php echo htmlspecialchars(@$x_gastos_prov3_aval) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td>Otro Proveedor:</td>
        <td><input class="importe" name="x_otro_prov_aval" type="text" id="x_otro_prov_aval" value="<?php echo htmlspecialchars(@$x_otro_prov_aval) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td>Empleados:</td>
        <td><input class="importe" name="x_gastos_empleados_aval" type="text" id="x_gastos_empleados_aval" value="<?php echo htmlspecialchars(@$x_gastos_empleados_aval) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td>Renta Local u Oficina del Negocio:</td>
        <td><input class="importe" name="x_gastos_renta_negcio_aval" type="text" id="x_gastos_renta_negcio_aval" value="<?php echo htmlspecialchars(@$x_gastos_renta_negocio_aval) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td>Otros Gastos:</td>
        <td><input class="importe" name="x_gastos_otros_aval" type="text" id="x_gastos_otros_aval" value="<?php echo htmlspecialchars(@$x_gastos_otros_aval) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="top" class="texto_normal_bold">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Referencias</td>
  </tr>
  <tr>
    <td colspan="3" class="texto_normal">Indique por lo menos una referencia de trabajo (Cliente ó Proveedor) </td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="165"><span class="texto_normal">Nombre</span></td>
        <td width="84" class="texto_normal">Teléfono</td>
        <td width="163" class="texto_normal">Parentesco</td>
        </tr>
      <tr>
        <td><input name="x_nombre_completo_ref_1" type="text" class="texto_normal" id="x_nombre_completo_ref_1" value="<?php echo htmlspecialchars(@$x_nombre_completo_ref_1) ?>" size="50" maxlength="250" /></td>
        <td><input name="x_telefono_ref_1" type="text" class="texto_normal" id="x_telefono_ref_1" value="<?php echo htmlspecialchars(@$x_telefono_ref_1) ?>" size="20" maxlength="20" /></td>
        <td>
		<?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_tipo_id_ref_1\" class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_tipo_id_ref_1) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?>		</td>
        </tr>
      <tr>
        <td><input name="x_nombre_completo_ref_2" type="text" class="texto_normal" id="x_nombre_completo_ref_2" value="<?php echo htmlspecialchars(@$x_nombre_completo_ref_2) ?>" size="50" maxlength="250" /></td>
        <td><input name="x_telefono_ref_2" type="text" class="texto_normal" id="x_telefono_ref_2" value="<?php echo htmlspecialchars(@$x_telefono_ref_2) ?>" size="20" maxlength="20" /></td>
        <td>
		<?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_tipo_id_ref_2\" class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_tipo_id_ref_2) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?>		</td>
        </tr>
      <tr>
        <td><input name="x_nombre_completo_ref_3" type="text" class="texto_normal" id="x_nombre_completo_ref_3" value="<?php echo htmlspecialchars(@$x_nombre_completo_ref_3) ?>" size="50" maxlength="250" /></td>
        <td><input name="x_telefono_ref_3" type="text" class="texto_normal" id="x_telefono_ref_3" value="<?php echo htmlspecialchars(@$x_telefono_ref_3) ?>" size="20" maxlength="20" /></td>
        <td>
		<?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_tipo_id_ref_3\"  class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_tipo_id_ref_3) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?>		</td>
      </tr>
      <tr>
        <td><input name="x_nombre_completo_ref_4" type="text" class="texto_normal" id="x_nombre_completo_ref_4" value="<?php echo htmlspecialchars(@$x_nombre_completo_ref_4) ?>" size="50" maxlength="250" /></td>
        <td><input name="x_telefono_ref_4" type="text" class="texto_normal" id="x_telefono_ref_4" value="<?php echo htmlspecialchars(@$x_telefono_ref_4) ?>" size="20" maxlength="20" /></td>
        <td>
		<?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_tipo_id_ref_4\"  class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_tipo_id_ref_4) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?>		</td>
      </tr>
      <tr>
        <td><input name="x_nombre_completo_ref_5" type="text" class="texto_normal" id="x_nombre_completo_ref_5" value="<?php echo htmlspecialchars(@$x_nombre_completo_ref_5) ?>" size="50" maxlength="250" /></td>
        <td><input name="x_telefono_ref_5" type="text" class="texto_normal" id="x_telefono_ref_5" value="<?php echo htmlspecialchars(@$x_telefono_ref_5) ?>" size="20" maxlength="20" /></td>
        <td>
		<?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_tipo_id_ref_5\" class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_tipo_id_ref_5) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?>		</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
    </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" bgcolor="#FFE6E6"><div align="center"><span class="texto_normal_bold">Comentarios del Promotor</span></div></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="left" valign="top">
	  <div align="center">
	    <textarea name="x_comentario_promotor" cols="60" rows="5"><?php echo htmlentities($x_comentario_promotor); ?></textarea>
	      </div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" bgcolor="#FFE6E6"><div align="center"><span class="texto_normal_bold">Comentarios del Comite de Cr&eacute;dito </span></div></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="left" valign="top">
	  <div align="center">
	    <textarea name="x_comentario_comite" cols="60" rows="5"><?php echo htmlentities($x_comentario_comite); ?></textarea>
	      </div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  
  <tr>
    <td colspan="3" bgcolor="#FFE6E6"><div align="center" class="texto_normal_bold">Checklist Promotor</div></td>
    </tr>
  <tr>
    <td colspan="3" align="left" valign="top" class="texto_normal"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="34">&nbsp;</td>
        <td width="600">&nbsp;</td>
        <td width="66">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="left" class="texto_small">
          <table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td align="center" valign="middle">&nbsp;</td>
              <td>&nbsp;</td>
              <td><strong>TITULAR</strong></td>
              <td>&nbsp;</td>
              <td><strong>Comentarios</strong></td>
            </tr>
            <tr>
              <td align="center" valign="middle">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td width="38" align="center" valign="middle"><input type="checkbox" name="x_checklist_1" id="x_checklist_1" /></td>
              <td width="9">&nbsp;</td>
              <td width="229">Verificación del Negocio</td>
              <td width="10">&nbsp;</td>
              <td width="364"><input name="x_det_ck1" type="text" id="x_det_ck1" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_2" id="x_checklist_2"  /></td>
              <td>&nbsp;</td>
              <td>Verificación Domicilio</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck2" type="text" id="x_det_ck2" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_3" id="x_checklist_3" /></td>
              <td>&nbsp;</td>
              <td>Firma Buro de Crédito</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck3" type="text" id="x_det_ck3" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_4" id="x_checklist_4" /></td>
              <td>&nbsp;</td>
              <td>Identificación oficial</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck4" type="text" id="x_det_ck4" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_5" id="x_checklist_5" /></td>
              <td>&nbsp;</td>
              <td>Comprobante de domicilio particular</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck5" type="text" id="x_det_ck5" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_6" id="x_checklist_6" /></td>
              <td>&nbsp;</td>
              <td>Comprobante de domicilio de Negocio</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck6" type="text" id="x_det_ck6" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_7" id="x_checklist_7" /></td>
              <td>&nbsp;</td>
              <td>Datos para estudio de capacidad de pago.</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck7" type="text" id="x_det_ck7" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_8" id="x_checklist_8" /></td>
              <td>&nbsp;</td>
              <td>Datos garantía</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck8" type="text" id="x_det_ck8" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_9" id="x_checklist_9" /></td>
              <td>&nbsp;</td>
              <td>Telefono(s)</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck9" type="text" id="x_det_ck9" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_10" id="x_checklist_10" /></td>
              <td>&nbsp;</td>
              <td>Referencias</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck10" type="text" id="x_det_ck10" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_11" id="x_checklist_11" /></td>
              <td>&nbsp;</td>
              <td>Foto Titular</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck11" type="text" id="x_det_ck11" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_12" id="x_checklist_12" /></td>
              <td>&nbsp;</td>
              <td>Foto Domicilio</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck12" type="text" id="x_det_ck12" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_13" id="x_checklist_13" /></td>
              <td>&nbsp;</td>
              <td>Foto Negocio</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck13" type="text" id="x_det_ck13" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="center" valign="middle">&nbsp;</td>
              <td>&nbsp;</td>
              <td><strong>AVAL</strong></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="center" valign="middle">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_14" id="x_checklist_14" /></td>
              <td>&nbsp;</td>
              <td>Verificación del Negocio</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck14" type="text" id="x_det_ck14" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_15" id="x_checklist_15" /></td>
              <td>&nbsp;</td>
              <td>Verificación Domicilio</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck15" type="text" id="x_det_ck15" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_16" id="x_checklist_16" /></td>
              <td>&nbsp;</td>
              <td>Firma Buro de Crédito</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck16" type="text" id="x_det_ck16" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_17" id="x_checklist_17" /></td>
              <td>&nbsp;</td>
              <td>Identificación oficial</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck17" type="text" id="x_det_ck17" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_18" id="x_checklist_18" /></td>
              <td>&nbsp;</td>
              <td>Comprobante de domicilio Particular</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck18" type="text" id="x_det_ck18" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_19" id="x_checklist_19" /></td>
              <td>&nbsp;</td>
              <td>Comprobante de domicilio de Negocio</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck19" type="text" id="x_det_ck19" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_20" id="x_checklist_20" /></td>
              <td>&nbsp;</td>
              <td>Datos para estudio de capacidad de pago.</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck20" type="text" id="x_det_ck20" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_21" id="x_checklist_21" /></td>
              <td>&nbsp;</td>
              <td>Telefono(s)</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck21" type="text" id="x_det_ck21" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_22" id="x_checklist_22" /></td>
              <td>&nbsp;</td>
              <td>Referencias</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck22" type="text" id="x_det_ck22" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_23" id="x_checklist_23" /></td>
              <td>&nbsp;</td>
              <td>Foto Aval</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck23" type="text" id="x_det_ck23" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_24" id="x_checklist_24" /></td>
              <td>&nbsp;</td>
              <td>Foto Domicilio</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck24" type="text" id="x_det_ck24" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_25" id="x_checklist_25" /></td>
              <td>&nbsp;</td>
              <td>Foto Negocio</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck25" type="text" id="x_det_ck25" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="center" valign="middle">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>
        </div>		</td>
        <td>&nbsp;</td>
      </tr>

      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div align="center"></div></td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>
<!--</form> -->
</body>
</html>
<?php
phpmkr_db_close($conn);
?>

