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
$x_solicitud_id = @$_GET["key"];
if (($x_solicitud_id == "") || ((is_null($x_solicitud_id)))) {
	$x_solicitud_id = @$_POST["x_solicitud_id"];
	if (($x_solicitud_id == "") || ((is_null($x_solicitud_id)))) {	
		echo "Solicitud no localizada.";
		exit();
	}
}
$x_solicitud_inciso_id = @$_GET["solicitud_inciso_id"];
if (($x_solicitud_inciso_id == "") || ((is_null($x_solicitud_inciso_id)))) {
	$x_solicitud_inciso_id = @$_POST["x_solicitud_inciso_id"];
	if (($x_solicitud_inciso_id == "") || ((is_null($x_solicitud_inciso_id)))) {	
		echo "Asociado no localizado.";
		exit();
	}
}

?>
<?php

// Initialize common variables
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
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = false;

// Get action
$sAction = @$_POST["a_add"];
if (($sAction == "") || ((is_null($sAction)))) {
		$sAction = "C"; // Display blank record
}else{

	// Get fields from form
	$x_solicitud_id = @$_POST["x_solicitud_id"];
	$x_solicitud_inciso_id = @$_POST["x_solicitud_inciso_id"];	
	
	$x_credito_tipo_id = @$_POST["x_credito_tipo_id"];
	$x_solicitud_status_id = 1;
	global $x_folio;
	$x_folio = "01ABC";
	$x_fecha_registro = @$_POST["x_fecha_registro"];
	$x_promotor_id = @$_POST["x_promotor_id"];
	$x_importe_solicitado = @$_POST["x_importe_solicitado"];
	$x_plazo_id = @$_POST["x_plazo_id"];
	$x_forma_pago_id = @$_POST["x_forma_pago_id"];	


	$x_cliente_id = @$_POST["x_cliente_id"];
	$x_usuario_id = 0;
	$x_nombre_completo = @$_POST["x_nombre_completo"];
	$x_tipo_negocio = @$_POST["x_tipo_negocio"];
	$x_edad = @$_POST["x_edad"];
	$x_sexo = @$_POST["x_sexo"];
	$x_estado_civil_id = @$_POST["x_estado_civil_id"];
	$x_numero_hijos = @$_POST["x_numero_hijos"];
	$x_numero_hijos_dep = @$_POST["x_numero_hijos_dep"];	
	$x_nombre_conyuge = @$_POST["x_nombre_conyuge"];
	$x_email = @$_POST["x_email"];	


	$x_direccion_id = @$_POST["x_direccion_id"];
	$x_calle = @$_POST["x_calle"];
	$x_colonia = @$_POST["x_colonia"];
	$x_entidad_id = @$_POST["x_entidad_id"];	
	$x_delegacion_id = @$_POST["x_delegacion_id"];
	$x_propietario = @$_POST["x_propietario"];
	$x_codigo_postal = @$_POST["x_codigo_postal"];
	$x_ubicacion = @$_POST["x_ubicacion"];
	$x_antiguedad = @$_POST["x_antiguedad"];
	$x_vivienda_tipo_id = @$_POST["x_vivienda_tipo_id"];
	$x_otro_tipo_vivienda = @$_POST["x_otro_tipo_vivienda"];
	$x_telefono = @$_POST["x_telefono"];
	$x_telefono_secundario = @$_POST["x_telefono_secundario"];


	$x_direccion_id2 = @$_POST["x_direccion_id2"];
	$x_calle2 = @$_POST["x_calle2"];
	$x_colonia2 = @$_POST["x_colonia2"];
	$x_entidad_id2 = @$_POST["x_entidad_id2"];	
	$x_delegacion_id2 = @$_POST["x_delegacion_id2"];
	$x_propietario2 = @$_POST["x_propietario2"];
	$x_codigo_postal2 = @$_POST["x_codigo_postal2"];
	$x_ubicacion2 = @$_POST["x_ubicacion2"];
	$x_antiguedad2 = @$_POST["x_antiguedad2"];
	$x_otro_tipo_vivienda2 = @$_POST["x_otro_tipo_vivienda2"];
	$x_telefono2 = @$_POST["x_telefono2"];
	$x_telefono_secundario2 = @$_POST["x_telefono_secundario2"];


	$x_garantia_id = @$_POST["x_garantia_id"];
	$x_descripcion = @$_POST["x_garantia_desc"];
	$x_valor = @$_POST["x_garantia_valor"];
	$x_documento = NULL;


	$x_ingreso_id = @$_POST["x_ingreso_id"];
	$x_ingresos_negocio = @$_POST["x_ingresos_negocio"];
	$x_ingresos_familiar_1 = @$_POST["x_ingresos_familiar_1"];
	$x_parentesco_tipo_id_ing_1 = @$_POST["x_parentesco_tipo_id_ing_1"];
	$x_ingresos_familiar_2 = @$_POST["x_ingresos_familiar_2"];
	$x_parentesco_tipo_id_ing_2 = @$_POST["x_parentesco_tipo_id_ing_2"];
	$x_otros_ingresos = @$_POST["x_otros_ingresos"];



	$x_nombre_completo_ref_1 = @$_POST["x_nombre_completo_ref_1"];
	$x_telefono_ref_1 = @$_POST["x_telefono_ref_1"];
	$x_parentesco_tipo_id_ref_1 = @$_POST["x_parentesco_tipo_id_ref_1"];

	$x_nombre_completo_ref_2 = @$_POST["x_nombre_completo_ref_2"];
	$x_telefono_ref_2 = @$_POST["x_telefono_ref_2"];
	$x_parentesco_tipo_id_ref_2 = @$_POST["x_parentesco_tipo_id_ref_2"];

	$x_nombre_completo_ref_3 = @$_POST["x_nombre_completo_ref_3"];
	$x_telefono_ref_3 = @$_POST["x_telefono_ref_3"];
	$x_parentesco_tipo_id_ref_3 = @$_POST["x_parentesco_tipo_id_ref_3"];

	$x_nombre_completo_ref_4 = @$_POST["x_nombre_completo_ref_4"];
	$x_telefono_ref_4 = @$_POST["x_telefono_ref_4"];
	$x_parentesco_tipo_id_ref_4 = @$_POST["x_parentesco_tipo_id_ref_4"];

	$x_nombre_completo_ref_5 = @$_POST["x_nombre_completo_ref_5"];
	$x_telefono_ref_5 = @$_POST["x_telefono_ref_5"];
	$x_parentesco_tipo_id_ref_5 = @$_POST["x_parentesco_tipo_id_ref_5"];


}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron los datos";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_solicitud_incisolist.php?key=$x_solicitud_id");
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "El Socio ha sido actualizado.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_solicitud_incisolist.php?key=$x_solicitud_id");
			exit();
		}
		break;
	case "X": // Add

//CLIENTE

		if($x_cliente_id > 0 ){
	
			$sSql = "select * from cliente where cliente_id = $x_cliente_id";
			$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$row2 = phpmkr_fetch_array($rs2);
			$GLOBALS["x_cliente_id"] = $row2["cliente_id"];
			$GLOBALS["x_usuario_id"] = $row2["usuario_id"];
			$GLOBALS["x_nombre_completo"] = $row2["nombre_completo"];
			$GLOBALS["x_apellido_paterno"] = $row2["apellido_paterno"];
			$GLOBALS["x_apellido_materno"] = $row2["apellido_materno"];									
			$GLOBALS["x_tipo_negocio"] = $row2["tipo_negocio"];
			$GLOBALS["x_edad"] = $row2["edad"];
			$GLOBALS["x_tit_rfc"] = $row2["rfc"];
			$GLOBALS["x_tit_curp"] = $row2["curp"];						

			$GLOBALS["x_tit_fecha_nac"] = $row2["fecha_nac"];								
			$GLOBALS["x_sexo"] = $row2["sexo"];
			$GLOBALS["x_estado_civil_id"] = $row2["estado_civil_id"];
			$GLOBALS["x_numero_hijos"] = $row2["numero_hijos"];
			$GLOBALS["x_numero_hijos_dep"] = $row2["numero_hijos_dep"];			
			$GLOBALS["x_nombre_conyuge"] = $row2["nombre_conyuge"];
			$GLOBALS["x_email"] = $row2["email"];		
			$GLOBALS["x_empresa"] = $row2["empresa"];		
			$GLOBALS["x_puesto"] = $row2["puesto"];		
			$GLOBALS["x_fecha_contratacion"] = $row2["fecha_contratacion"];		
			$GLOBALS["x_salario_mensual"] = $row2["salario_mensual"];														
			$GLOBALS["x_nacionalidad_id"] = $row2["nacionalidad_id"];	
	
					
			$sSql = "select * from direccion where cliente_id = ".$GLOBALS["x_cliente_id"]." and direccion_tipo_id = 1";
			$rs3 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$row3 = phpmkr_fetch_array($rs3);
			$GLOBALS["x_direccion_id"] = $row3["direccion_id"];
			$GLOBALS["x_calle"] = $row3["calle"];
			$GLOBALS["x_colonia"] = $row3["colonia"];
			$GLOBALS["x_entidad_id"] = $row3["entidad_id"];			
			$GLOBALS["x_delegacion_id"] = $row3["delegacion_id"];
			$GLOBALS["x_propietario"] = $row3["x_propietario"];
			$GLOBALS["x_codigo_postal"] = $row3["codigo_postal"];
			$GLOBALS["x_ubicacion"] = $row3["ubicacion"];
			$GLOBALS["x_antiguedad"] = $row3["antiguedad"];
			$GLOBALS["x_vivienda_tipo_id"] = $row3["vivienda_tipo_id"];
			$GLOBALS["x_otro_tipo_vivienda"] = $row3["otro_tipo_vivienda"];
			$GLOBALS["x_telefono"] = $row3["telefono"];
			$GLOBALS["x_telefono_sec"] = $row3["telefono_movil"];			
			$GLOBALS["x_telefono_secundario"] = $row3["telefono_secundario"];
	
			$sSql = "select * from direccion where cliente_id = ".$GLOBALS["x_cliente_id"]." and direccion_tipo_id = 2";
			$rs4 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$row4 = phpmkr_fetch_array($rs4);
			$GLOBALS["x_direccion_id2"] = $row4["direccion_id"];
			$GLOBALS["x_calle2"] = $row4["calle"];
			$GLOBALS["x_colonia2"] = $row4["colonia"];
			$GLOBALS["x_entidad_id2"] = $row4["entidad_id"];			
			$GLOBALS["x_delegacion_id2"] = $row4["delegacion_id"];
			$GLOBALS["x_propietario2"] = $row4["x_propietario"];
			$GLOBALS["x_codigo_postal2"] = $row4["codigo_postal"];
			$GLOBALS["x_ubicacion2"] = $row4["ubicacion"];
			$GLOBALS["x_antiguedad2"] = $row4["antiguedad"];
	
			$GLOBALS["x_otro_tipo_vivienda2"] = $row4["otro_tipo_vivienda"];
			$GLOBALS["x_telefono2"] = $row4["telefono"];
			$GLOBALS["x_telefono_secundario2"] = $row4["telefono_secundario"];
	
			phpmkr_free_result($rs2);	
			phpmkr_free_result($rs3);		
			phpmkr_free_result($rs4);			
		}else{
			$GLOBALS["x_cliente_id"] = 0;
			$GLOBALS["x_usuario_id"] = 0;
			$GLOBALS["x_nombre_completo"] = "";
			$GLOBALS["x_tipo_negocio"] = "";
			$GLOBALS["x_edad"] = "";
			$GLOBALS["x_sexo"] = 1;
			$GLOBALS["x_estado_civil_id"] = "";
			$GLOBALS["x_numero_hijos"] = "";
			$GLOBALS["x_numero_hijos_dep"] = "";			
			$GLOBALS["x_nombre_conyuge"] = "";
			$GLOBALS["x_email"] = "";		
	
					
			$GLOBALS["x_direccion_id"] = "";
			$GLOBALS["x_calle"] = "";
			$GLOBALS["x_colonia"] = "";
			$GLOBALS["x_entidad_id"] = "";			
			$GLOBALS["x_delegacion_id"] = "";
			$GLOBALS["x_propietario"] = "";

			$GLOBALS["x_codigo_postal"] = "";
			$GLOBALS["x_ubicacion"] = "";
			$GLOBALS["x_antiguedad"] = "";
			$GLOBALS["x_vivienda_tipo_id"] = "";
			$GLOBALS["x_otro_tipo_vivienda"] = "";
			$GLOBALS["x_telefono"] = "";
			$GLOBALS["x_telefono_secundario"] = "";
	
			$GLOBALS["x_direccion_id2"] = "";
			$GLOBALS["x_calle2"] = "";
			$GLOBALS["x_colonia2"] = "";
			$GLOBALS["x_entidad_id2"] = "";			
			$GLOBALS["x_delegacion_id2"] = "";
			$GLOBALS["x_propietario2"] = "";

			$GLOBALS["x_codigo_postal2"] = "";
			$GLOBALS["x_ubicacion2"] = "";
			$GLOBALS["x_antiguedad2"] = "";
	
			$GLOBALS["x_otro_tipo_vivienda2"] = "";
			$GLOBALS["x_telefono2"] = "";
			$GLOBALS["x_telefono_secundario2"] = "";
		}
	
		break;
	case "D": // DIRECCIONES
		if($_POST["x_delegacion_id_temp"] != ""){
			$x_delegacion_id2 = $_POST["x_delegacion_id_temp"];
		}
		break;		
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Financiera CRECE</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">td img {display: block;}</style>
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#FFFFFF">

<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>

<script type="text/javascript">
<!--

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

if(EW_this.x_importe_solicitado.value < 3000){
	alert("El importe es incorrecto valor minimo 3000");
	EW_this.x_importe_solicitado.focus();
}

/*	
}else{

	if (EW_this.x_importe_solicitado.value > 5000){
		document.getElementById('aval').className = "TG_visible";
	}else{
		document.getElementById('aval').className = "TG_hidden";	
	}
	
	if (EW_this.x_importe_solicitado.value > 10000){
		document.getElementById('garantias').className = "TG_visible";
	}else{
		document.getElementById('garantias').className = "TG_hidden";	
	}
}
*/
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
	/*
	if (validada == true && EW_this.x_delegacion_id.value == 17) {
		if (validada == true && EW_this.x_otra_delegacion && !EW_hasValue(EW_this.x_otra_delegacion, "TEXT" )) {
			if (!EW_onError(EW_this, EW_this.x_otra_delegacion, "TEXT", "Indique la delegación del domicilio particular."))
				validada = false;
		}
	}
	*/
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

//		var indice = EW_this.x_delegacion_id.selectedIndex;
//		EW_this.x_delegacion_id2.options[indice].selected = true;

		EW_this.x_delegacion_id_temp.value = EW_this.x_delegacion_id.value;
		EW_this.a_add.value = "D";	
		EW_this.submit();	


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



function viviendatipo(viv){
EW_this = document.solicitudadd;
	if(viv == 1){
		if(EW_this.x_vivienda_tipo_id.value == 3){
			document.getElementById('prop1').className = "TG_visible";
		}else{
			document.getElementById('prop1').className = "TG_hidden";	
		}
	}
	if(viv == 2){
		if(EW_this.x_vivienda_tipo_id2.value == 3){
			document.getElementById('prop2').className = "TG_visible";
		}else{
			document.getElementById('prop2').className = "TG_hidden";	
		}
	}
	
}

function EW_checkMyForm() {
EW_this = document.solicitudadd;
validada = true;


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
	if (!EW_onError(EW_this, EW_this.x_nombre_completo, "TEXT", "Indique su Nombre completo."))
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



//if(document.getElementById('aval').className == "TG_visible"){

if(1==2){	
	if (validada == true && EW_this.x_nombre_completo_aval && !EW_hasValue(EW_this.x_nombre_completo_aval, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_nombre_completo_aval, "TEXT", "Indique el nombre completo del Aval."))
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
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-1.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<p><span class="phpmaker"><a href="php_solicitud_incisolist.php?key=<?php echo $x_solicitud_id; ?>">Regresar a la Lista de Asociados </a></span></p>
<form name="solicitudadd" id="solicitudadd" action="php_solicitud_incisoedit.php" method="post" >
<p>
<input type="hidden" name="a_add" value="A">
<input type="hidden" name="x_solicitud_id" value="<?php echo $x_solicitud_id; ?>">
<input type="hidden" name="x_solicitud_inciso_id" value="<?php echo $x_solicitud_inciso_id; ?>">
<input type="hidden" name="x_cliente_id" value="<?php echo $x_cliente_id; ?>" />
<input type="hidden" name="x_direccion_id" value="<?php echo $x_direccion_id; ?>" />
<input type="hidden" name="x_direccion_id2" value="<?php echo $x_direccion_id2; ?>" />
<input type="hidden" name="x_aval_id" value="<?php echo $x_aval_id; ?>" />
<input type="hidden" name="x_garantia_id" value="<?php echo $x_garantia_id; ?>" />
<input type="hidden" name="x_ingreso_id" value="<?php echo $x_ingreso_id; ?>" />

<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION["ewmsg"] ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>


<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  
  <tr>
    <td width="141">&nbsp;</td>
    <td width="433">&nbsp;</td>
    <td width="126">&nbsp;</td>
  </tr>
  
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

<table width="700" border="0" cellspacing="0" cellpadding="0">
	<tr>
	  <td width="165"><span class="texto_normal">Nombre(s): </span></td>
	  <td colspan="4">
	  <?php echo htmlentities(@$x_nombre_completo) ?>	  </td>
	  </tr>
	<tr>
	  <td><span class="texto_normal">Apellido Paterno</span></td>
	  <td colspan="4"><?php echo htmlentities(@$x_apellido_paterno) ?></td>
	  </tr>
	<tr>
	  <td><span class="texto_normal">Apellido Materno</span></td>
	  <td colspan="4"><?php echo htmlentities(@$x_apellido_materno) ?></td>
	  </tr>
	<tr>
	  <td><span class="texto_normal">RFC:</span></td>
	  <td colspan="4"><?php echo htmlentities(@$x_tit_rfc) ?></td>
	  </tr>
	<tr>
	  <td><span class="texto_normal">CURP:</span></td>
	  <td colspan="4"><?php echo htmlentities(@$x_tit_curp) ?></td>
	  </tr>
	<tr>
	  <td><span class="texto_normal">Tipo de Negocio: </span></td>
	  <td colspan="4">
	  <?php echo htmlentities(@$x_tipo_negocio) ?>	  </td>
	  </tr>
	<tr>
	  <td><span class="texto_normal">Fecha de Nacimiento:</span></td>
	  <td colspan="4"><table width="524" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="131"><span class="texto_normal"><?php echo FormatDateTime(@$x_tit_fecha_nac,7); ?></span></td>
          <td width="176"><div align="left"><span class="texto_normal">Genero:
                <?php if (@$x_sexo == "1"){ echo "M"; }else{ echo "F"; } ?>
          </span></div></td>
          <td width="217"><div align="left"><span class="texto_normal">Edo. Civil:
                <?php
		$sSqlWrk = "SELECT estado_civil_id, descripcion FROM estado_civil where estado_civil_id = $x_estado_civil_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		?>
            </span></div></td>
        </tr>
      </table></td>
	  </tr>
	
      <tr>
        <td><span class="texto_normal">&nbsp;No. de hijos
              : </span></td>
        <td colspan="3"><span class="texto_normal">
          <?php echo htmlspecialchars(@$x_numero_hijos) ?>
        Hijos dependientes:
        <?php echo htmlspecialchars(@$x_numero_hijos_dep) ?>        </span></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Nombre del Conyuge:</span></td>
        <td width="535" colspan="3"><?php echo htmlentities(@$x_nombre_conyuge) ?></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Email</span>:</td>
        <td colspan="3"><?php echo htmlspecialchars(@$x_email) ?></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Nacionalidad:</span></td>
        <td colspan="3"><span class="texto_normal">
          <?php
		$sSqlWrk = "SELECT nacionalidad_id, pais_nombre FROM nacionalidad where nacionalidad_id = $x_nacionalidad_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["pais_nombre"]);
		@phpmkr_free_result($rswrk);
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
        <td colspan="3"><?php echo htmlentities(@$x_calle) ?></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Colonia: </span></td>
        <td colspan="3"><?php echo htmlentities(@$x_colonia) ?></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Entidad:</span></td>
        <td width="172"><span class="texto_normal">
          <?php
		$sSqlWrk = "SELECT entidad_id, nombre FROM entidad where entidad_id = $x_entidad_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["nombre"]);
		@phpmkr_free_result($rswrk);
		?>
        </span></td>
        <td width="309"><div align="left"><span class="texto_normal">Del/Mun:
              
        </span><span class="texto_normal">
        <?php
		$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where delegacion_id = $x_delegacion_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		?>
        </span></div></td>
        <td width="54"><div align="left"></div></td>
      </tr>
	    <tr>
	      <td><span class="texto_normal">C.P.
          : </span></td>
	      <td colspan="4"><span class="texto_normal"><?php echo htmlspecialchars(@$x_codigo_postal) ?></span></td>
	      </tr>
	    <td><span class="texto_normal">Referencia de Ubicación:</span></td>
	  <td colspan="4"><?php echo htmlentities(@$x_ubicacion) ?></td>
	  </tr>
	<tr>
	  <td class="texto_normal">Antiguedad en Domicilio: </td>
	  <td colspan="4"><span class="texto_normal">
	    <?php echo htmlspecialchars(@$x_antiguedad) ?> (años)</span></td>
	  </tr>
	<tr>
	  <td class="texto_normal">Tipo de Vivienda: </td>
	  <td colspan="4"><span class="texto_normal">
	    <?php
		$sSqlWrk = "SELECT vivienda_tipo_id, descripcion FROM vivienda_tipo where vivienda_tipo_id = $x_vivienda_tipo_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		?>
	  </span></td>
	  </tr>
      <tr>
        <td > </td>
        <td colspan="4" class="texto_normal">
		<div id="prop1" class="<?php if($x_vivienda_tipo_id == 3){ echo "TG_visible";}else{ echo "TG_hidden";} ?>">
		Propietario de la Vivienda:&nbsp;<?php echo htmlentities($x_propietario); ?></div>		</td>
      </tr>
	<tr>
	  <td class="texto_normal">Tels. Particular: </td>
	  <td colspan="4"><span class="texto_normal"><?php echo htmlspecialchars(@$x_telefono) ?></span><span class="texto_normal">&nbsp;-
	      <?php echo htmlspecialchars(@$x_telefono_sec); ?></span></td>
	  </tr>
	<tr>
	  <td class="texto_normal">Tel. Celular:</td>
	  <td colspan="4"><span class="texto_normal"><?php echo htmlspecialchars(@$x_telefono_secundario); ?></span></td>
	  </tr>
	<tr>
	  <td class="texto_normal">&nbsp;</td>
	  <td colspan="4">&nbsp;</td>
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
        <td><span class="texto_normal">Empresa: </span></td>
        <td colspan="3"><span class="texto_normal"><?php echo htmlentities(@$x_empresa) ?></span></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Puesto: </span></td>
        <td colspan="3"><span class="texto_normal"><?php echo htmlentities(@$x_puesto) ?></span></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Fecha Contratacion:</span></td>
        <td colspan="3"><span class="texto_normal"><?php echo FormatDateTime(@$x_fecha_contratacion,7); ?></span></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Salario Mensual: </span></td>
        <td colspan="3"><span class="texto_normal"><?php echo htmlentities(@$x_salario_mensual) ?></span></td>
      </tr>
      <tr>
        <td width="165"><span class="texto_normal">Calle no. Ext e Int. : </span></td>
        <td colspan="3"><span class="texto_normal"><?php echo htmlentities(@$x_calle2) ?></span></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Colonia: </span></td>
        <td colspan="3"><span class="texto_normal"><?php echo htmlentities(@$x_colonia2) ?></span></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Entidad:</span></td>
        <td width="172"><span class="texto_normal">
          <?php
   		if($x_entidad_id2 > 0){							  						  
		$sSqlWrk = "SELECT entidad_id, nombre FROM entidad where entidad_id = $x_entidad_id2";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["nombre"]);
		@phpmkr_free_result($rswrk);
		}
		?>
        </span></td>
        <td width="309"><div align="left"><span class="texto_normal">
		<input type="hidden" name="x_delegacion_id_temp" value="" />
		Del/Mun:
        </span><span class="texto_normal">
        <?php
   		if($x_delegacion_id2 > 0){							  						  
		$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where delegacion_id = $x_delegacion_id2";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		}
		?>
        </span></div></td>
        <td width="54"><div align="left"></div></td>
      </tr>
	  <!---
	  <tr>
	    <td class="texto_normal">Otra delegación: </td>
	    <td colspan="4">
		<input name="x_otra_delegacion2" type="text" class="texto_normal" id="x_otra_delegacion2" value="<?php echo htmlspecialchars(@$x_otra_delegacion2) ?>" size="80" maxlength="250" />		</td>
	    </tr>
	  <tr>
	  --->
      <tr>
        <td><span class="texto_normal">C.P.
          :</span></td>
        <td colspan="4"><span class="texto_normal"><?php echo htmlspecialchars(@$x_codigo_postal2) ?></span></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Referencia de Ubicación:</span></td>
        <td colspan="4"><span class="texto_normal"><?php echo htmlentities(@$x_ubicacion2) ?></span></td>
      </tr>
      <tr>
        <td class="texto_normal">Antiguedad en Domicilio: </td>
        <td colspan="4"><span class="texto_normal">
          <?php echo htmlspecialchars(@$x_antiguedad2) ?>        (años)</span></td>
      </tr>
      <tr>
        <td class="texto_normal">Tel.: </td>
        <td colspan="4"><span class="texto_normal"><?php echo htmlspecialchars(@$x_telefono2) ?></span><span class="texto_normal">&nbsp;        </span></td>
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
	
<!--	<div id="aval" class="TG_hidden"> --><!-- 	</div>	-->	</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" bgcolor="#FFE6E6"><div align="center" class="texto_normal_bold">Garantías</div></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">

<!-- 	<div id="garantias" class="TG_hidden" > -->
	
	<table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="165"><span class="texto_normal">Descripción</span></td>
        <td width="84" class="texto_normal">&nbsp;</td>
        <td width="163" class="texto_normal">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3"><span class="texto_normal"><?php echo htmlentities(@$x_garantia_desc) ?></span></td>
        </tr>
      <tr>
        <td><span class="texto_normal">Valor</span></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><span class="texto_normal"><?php echo FormatNumber(@$x_garantia_valor,0,0,0,1) ?></span></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
	
<!--	</div>	-->	</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  

  <tr>
    <td colspan="3" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Ingresos Mensuales </td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="165"><span class="texto_normal">Ingresos del Negocio:</span></td>
        <td width="84"><span class="texto_normal"><?php echo FormatNumber(@$x_ingresos_negocio,0,0,0,1) ?></span></td>
        <td width="163" class="texto_normal">Otros Ingresos: </td>
        <td width="68"><span class="texto_normal"><?php echo FormatNumber(@$x_otros_ingresos,0,0,0,1) ?></span></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Ingresos Familiares: </span></td>
        <td><span class="texto_normal"><?php echo FormatNumber(@$x_ingresos_familiar_1,0,0,0,1) ?></span></td>
        <td><span class="texto_normal">Parentesco: </span></td>
        <td><span class="texto_normal">
          <?php
   		if($x_parentesco_tipo_id_ing_1 > 0){							  				
		$sSqlWrk = "SELECT parentesco_tipo_id, descripcion FROM parentesco_tipo where parentesco_tipo_id = $x_parentesco_tipo_id_ing_1";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		}
		?>
        </span></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Ingresos Familiares: </span></td>
        <td><span class="texto_normal"><?php echo FormatNumber(@$x_ingresos_familiar_2,0,0,0,1) ?></span></td>
        <td><span class="texto_normal">Parentesco:</span></td>
        <td><span class="texto_normal">
          <?php
   		if($x_parentesco_tipo_id_ing_2 > 0){							  				
		$sSqlWrk = "SELECT parentesco_tipo_id, descripcion FROM parentesco_tipo where parentesco_tipo_id = $x_parentesco_tipo_id_ing_2";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		}
		?>
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
        <td><span class="texto_normal"><?php echo htmlentities(@$x_nombre_completo_ref_1) ?></span></td>
        <td><span class="texto_normal"><?php echo htmlspecialchars(@$x_telefono_ref_1) ?></span></td>
        <td><span class="texto_normal">
          <?php
		if($x_parentesco_tipo_id_ref_1 > 0){
		$sSqlWrk = "SELECT parentesco_tipo_id, descripcion FROM parentesco_tipo where parentesco_tipo_id = $x_parentesco_tipo_id_ref_1";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		}
		?>
        </span></td>
        </tr>
      <tr>
        <td><span class="texto_normal"><?php echo htmlentities(@$x_nombre_completo_ref_2) ?></span></td>
        <td><span class="texto_normal"><?php echo htmlspecialchars(@$x_telefono_ref_2) ?></span></td>
        <td><span class="texto_normal">
          <?php
		if($x_parentesco_tipo_id_ref_2 > 0){
		$sSqlWrk = "SELECT parentesco_tipo_id, descripcion FROM parentesco_tipo where parentesco_tipo_id = $x_parentesco_tipo_id_ref_2";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		}
		?>
        </span></td>
        </tr>
      <tr>
        <td><span class="texto_normal"><?php echo htmlentities(@$x_nombre_completo_ref_3) ?></span></td>
        <td><span class="texto_normal"><?php echo htmlspecialchars(@$x_telefono_ref_3) ?></span></td>
        <td><span class="texto_normal">
          <?php
		if($x_parentesco_tipo_id_ref_3 > 0){
		$sSqlWrk = "SELECT parentesco_tipo_id, descripcion FROM parentesco_tipo where parentesco_tipo_id = $x_parentesco_tipo_id_ref_3";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		}
		?>
        </span></td>
      </tr>
      <tr>
        <td><span class="texto_normal"><?php echo htmlentities(@$x_nombre_completo_ref_4) ?></span></td>
        <td><span class="texto_normal"><?php echo htmlspecialchars(@$x_telefono_ref_4) ?></span></td>
        <td><span class="texto_normal">
          <?php
		if($x_parentesco_tipo_id_ref_4 > 0){
		$sSqlWrk = "SELECT parentesco_tipo_id, descripcion FROM parentesco_tipo where parentesco_tipo_id = $x_parentesco_tipo_id_ref_4";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		}
		?>
        </span></td>
      </tr>
      <tr>
        <td><span class="texto_normal"><?php echo htmlentities(@$x_nombre_completo_ref_5) ?></span></td>
        <td><span class="texto_normal"><?php echo htmlspecialchars(@$x_telefono_ref_5) ?></span></td>
        <td><span class="texto_normal">
          <?php
		if($x_parentesco_tipo_id_ref_5 > 0){
		$sSqlWrk = "SELECT parentesco_tipo_id, descripcion FROM parentesco_tipo where parentesco_tipo_id = $x_parentesco_tipo_id_ref_5";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo htmlentities($datawrk["descripcion"]);
		@phpmkr_free_result($rswrk);
		}
		?>
        </span></td>
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
    <td><div align="center">
	<a href="php_solicitud_incisoprint.php?solicitud_inciso_id=<?php echo $x_solicitud_inciso_id; ?>" target="_blank">Imprimir Solicitud</a>
	</div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div align="center"></div></td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>
</form>
</body>
</html>
<?php
phpmkr_db_close($conn);
?>
<?php


function LoadData($conn)
{
	global $x_solicitud_id;
	global $x_solicitud_inciso_id;	
	
	$sSql = "SELECT * FROM `solicitud`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_solicitud_id) : $x_solicitud_id;
	$sWhere .= "(`solicitud_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_solicitud_id"] = $row["solicitud_id"];
		$GLOBALS["x_credito_tipo_id"] = $row["credito_tipo_id"];
		$GLOBALS["x_solicitud_status_id"] = $row["solicitud_status_id"];
		$GLOBALS["x_folio"] = $row["folio"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
		$GLOBALS["x_promotor_id"] = $row["promotor_id"];
		$GLOBALS["x_importe_solicitado"] = $row["importe_solicitado"];
		$GLOBALS["x_plazo_id"] = $row["plazo_id"];
		$GLOBALS["x_forma_pago_id"] = $row["forma_pago_id"];		
		$GLOBALS["x_contrato"] = $row["contrato"];
		$GLOBALS["x_pagare"] = $row["pagare"];
		$GLOBALS["x_comentario_promotor"] = $row["comentario_promotor"];
		$GLOBALS["x_comentario_comite"] = $row["comentario_comite"];

// solicitud_inciso

		$sSql = "select * from solicitud_inciso where solicitud_inciso.solicitud_inciso_id = $x_solicitud_inciso_id";
		$rs1 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row1 = phpmkr_fetch_array($rs1);
		$GLOBALS["x_fecha_registro"] = $row1["fecha_registro"];
		$GLOBALS["x_cliente_id"] = $row1["cliente_id"];
		$GLOBALS["x_ingreso_id"] = $row1["ingreso_id"];
		$GLOBALS["x_garantia_id"] = $row1["garantia_id"];




//CLIENTE

		$sSql = "select * from cliente where cliente_id = ".$GLOBALS["x_cliente_id"];
		$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row2 = phpmkr_fetch_array($rs2);
		$GLOBALS["x_usuario_id"] = $row2["usuario_id"];
		$GLOBALS["x_nombre_completo"] = $row2["nombre_completo"];
		$GLOBALS["x_apellido_paterno"] = $row2["apellido_paterno"];
		$GLOBALS["x_apellido_materno"] = $row2["apellido_materno"];						
		
		$GLOBALS["x_tipo_negocio"] = $row2["tipo_negocio"];

		$GLOBALS["x_tit_rfc"] = $row2["rfc"];
		$GLOBALS["x_tit_curp"] = $row2["curp"];						

		$GLOBALS["x_tit_fecha_nac"] = $row2["fecha_nac"];						
		
		$GLOBALS["x_edad"] = $row2["edad"];
		$GLOBALS["x_sexo"] = $row2["sexo"];
		$GLOBALS["x_estado_civil_id"] = $row2["estado_civil_id"];
		$GLOBALS["x_nacionalidad_id"] = $row2["nacionalidad_id"];		
		
		$GLOBALS["x_numero_hijos"] = $row2["numero_hijos"];
		$GLOBALS["x_numero_hijos_dep"] = $row2["numero_hijos_dep"];		
		$GLOBALS["x_nombre_conyuge"] = $row2["nombre_conyuge"];
		$GLOBALS["x_email"] = $row2["email"];		
		$GLOBALS["x_empresa"] = $row2["empresa"];		
		$GLOBALS["x_puesto"] = $row2["puesto"];		
		$GLOBALS["x_fecha_contratacion"] = $row2["fecha_contratacion"];		
		$GLOBALS["x_salario_mensual"] = $row2["salario_mensual"];														

				
		$sSql = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where cliente_id = ".$GLOBALS["x_cliente_id"]." and direccion_tipo_id = 1";
		$rs3 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row3 = phpmkr_fetch_array($rs3);
		$GLOBALS["x_direccion_id"] = $row3["direccion_id"];
		$GLOBALS["x_calle"] = $row3["calle"];
		$GLOBALS["x_colonia"] = $row3["colonia"];
		$GLOBALS["x_delegacion_id"] = $row3["delegacion_id"];
		$GLOBALS["x_propietario"] = $row3["propietario"];
		$GLOBALS["x_entidad_id"] = $row3["entidad_id"];
		$GLOBALS["x_codigo_postal"] = $row3["codigo_postal"];
		$GLOBALS["x_ubicacion"] = $row3["ubicacion"];
		$GLOBALS["x_antiguedad"] = $row3["antiguedad"];
		$GLOBALS["x_vivienda_tipo_id"] = $row3["vivienda_tipo_id"];
		$GLOBALS["x_otro_tipo_vivienda"] = $row3["otro_tipo_vivienda"];
		$GLOBALS["x_telefono"] = $row3["telefono"];
		$GLOBALS["x_telefono_sec"] = $row3["telefono_movil"];			
		$GLOBALS["x_telefono_secundario"] = $row3["telefono_secundario"];

		$sSql = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where cliente_id = ".$GLOBALS["x_cliente_id"]." and direccion_tipo_id = 2";
		$rs4 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row4 = phpmkr_fetch_array($rs4);
		$GLOBALS["x_direccion_id2"] = $row4["direccion_id"];
		$GLOBALS["x_calle2"] = $row4["calle"];
		$GLOBALS["x_colonia2"] = $row4["colonia"];
		$GLOBALS["x_delegacion_id2"] = $row4["delegacion_id"];
//		$GLOBALS["x_otra_delegacion2"] = $row4["otra_delegacion"];
		$GLOBALS["x_entidad_id2"] = $row4["entidad_id"];
		$GLOBALS["x_codigo_postal2"] = $row4["codigo_postal"];
		$GLOBALS["x_ubicacion2"] = $row4["ubicacion"];
		$GLOBALS["x_antiguedad2"] = $row4["antiguedad"];

		$GLOBALS["x_otro_tipo_vivienda2"] = $row4["otro_tipo_vivienda"];
		$GLOBALS["x_telefono2"] = $row4["telefono"];
		$GLOBALS["x_telefono_secundario2"] = $row4["telefono_secundario"];


		$sSql = "select * from garantia where garantia_id = ".$GLOBALS["x_garantia_id"];
		$rs7 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row7 = phpmkr_fetch_array($rs7);
		$GLOBALS["x_garantia_desc"] = $row7["descripcion"];
		$GLOBALS["x_garantia_valor"] = $row7["valor"];		

		$sSql = "select * from ingreso where ingreso_id = ".$GLOBALS["x_ingreso_id"];
		$rs8 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row8 = phpmkr_fetch_array($rs8);
//		$GLOBALS["x_ingreso_id"] = $row8["ingreso_id"];
		$GLOBALS["x_ingresos_negocio"] = $row8["ingresos_negocio"];
		$GLOBALS["x_ingresos_familiar_1"] = $row8["ingresos_familiar_1"];
		$GLOBALS["x_parentesco_tipo_id_ing_1"] = $row8["parentesco_tipo_id"];
		$GLOBALS["x_ingresos_familiar_2"] = $row8["ingresos_familiar_2"];
		$GLOBALS["x_parentesco_tipo_id_ing_2"] = $row8["parentesco_tipo_id2"];
		$GLOBALS["x_otros_ingresos"] = $row8["otros_ingresos"];

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_nombre_completo_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_nombre_completo_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_nombre_completo_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_nombre_completo_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_nombre_completo_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";
	

		$x_count = 1;
		$sSql = "select referencia.* from 
		referencia join inciso_referencia 
		on inciso_referencia.referencia_id = referencia.referencia_id
		where inciso_referencia.solicitud_inciso_id = ".$GLOBALS["x_solicitud_inciso_id"]." order by referencia.referencia_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9 = phpmkr_fetch_array($rs9)){
			$GLOBALS["x_referencia_id_$x_count"] = $row9["referencia_id"];
			$GLOBALS["x_nombre_completo_ref_$x_count"] = $row9["nombre_completo"];
			$GLOBALS["x_telefono_ref_$x_count"] = $row9["telefono"];
			$GLOBALS["x_parentesco_tipo_id_ref_$x_count"] = $row9["parentesco_tipo_id"];
			$x_count++;
		}


//CREDITO
		if ($GLOBALS["x_solicitud_status_id"] == 6){
			$sSql = "select credito_id from credito where solicitud_id = ".$GLOBALS["x_solicitud_id"];
			$rs10 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$row10 = phpmkr_fetch_array($rs10);
			$GLOBALS["x_credito_id"] = $row10["credito_id"];
		}else{
			$x_credito_id = "";
		}

	}
	phpmkr_free_result($rs);
	phpmkr_free_result($rs1);		
	phpmkr_free_result($rs2);	
	phpmkr_free_result($rs3);		
	phpmkr_free_result($rs4);			
	phpmkr_free_result($rs5);				
	phpmkr_free_result($rs6);					
	phpmkr_free_result($rs6_2);						
	phpmkr_free_result($rs7);						
	phpmkr_free_result($rs8);
	phpmkr_free_result($rs9);								
	phpmkr_free_result($rs10);									
	
	return $bLoadData;
}


//-------------------------------------------------------------------------------
// Function AddData
// - Add Data
// - Variables used: field variables

function AddData($conn)
{
	global $x_solicitud_id;

/*
mssql_query('begin tran');
$result = mssql_query('insert into table_name (fname, lname) values
('joe', 'bob')');
if(!$result)
{
mssql_query('rollback tran');
}

mssql_query('commit tran');
*/


	$currentdate = getdate(time());
	$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	


	phpmkr_query('START TRANSACTION;', $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: BEGIN TRAN');
//CLIENTE

		$fieldList = NULL;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_completo"]) : $GLOBALS["x_nombre_completo"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`nombre_completo`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tipo_negocio"]) : $GLOBALS["x_tipo_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`tipo_negocio`"] = $theValue;
		$theValue = ($GLOBALS["x_edad"] != "") ? intval($GLOBALS["x_edad"]) : "NULL";
		$fieldList["`edad`"] = $theValue;
		$theValue = ($GLOBALS["x_sexo"] != "") ? intval($GLOBALS["x_sexo"]) : "NULL";
		$fieldList["`sexo`"] = $theValue;
		$theValue = ($GLOBALS["x_estado_civil_id"] != "") ? intval($GLOBALS["x_estado_civil_id"]) : "0";
		$fieldList["`estado_civil_id`"] = $theValue;
		$theValue = ($GLOBALS["x_numero_hijos"] != "") ? intval($GLOBALS["x_numero_hijos"]) : "NULL";
		$fieldList["`numero_hijos`"] = $theValue;
		$theValue = ($GLOBALS["x_numero_hijos_dep"] != "") ? intval($GLOBALS["x_numero_hijos_dep"]) : "0";
		$fieldList["`numero_hijos_dep`"] = $theValue;		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_conyuge"]) : $GLOBALS["x_nombre_conyuge"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`nombre_conyuge`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_email"]) : $GLOBALS["x_email"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`email`"] = $theValue;

		// update
		$sSql = "UPDATE `cliente` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE cliente_id = ".$GLOBALS["x_cliente_id"] ;
		$x_result = phpmkr_query($sSql,$conn);

		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}


//DIR PAR

		$fieldList = NULL;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle"]) : $GLOBALS["x_calle"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`calle`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia"]) : $GLOBALS["x_colonia"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`colonia`"] = $theValue;
		$theValue = ($GLOBALS["x_delegacion_id"] != "") ? intval($GLOBALS["x_delegacion_id"]) : "NULL";
		$fieldList["`delegacion_id`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_propietario"]) : $GLOBALS["x_propietario"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`propietario`"] = $theValue;
/*		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_entidad"]) : $GLOBALS["x_entidad"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`entidad`"] = $theValue;
*/		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_codigo_postal"]) : $GLOBALS["x_codigo_postal"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`codigo_postal`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion"]) : $GLOBALS["x_ubicacion"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ubicacion`"] = $theValue;
		$theValue = ($GLOBALS["x_antiguedad"] != "") ? intval($GLOBALS["x_antiguedad"]) : "NULL";
		$fieldList["`antiguedad`"] = $theValue;
		$theValue = ($GLOBALS["x_vivienda_tipo_id"] != "") ? intval($GLOBALS["x_vivienda_tipo_id"]) : "NULL";
		$fieldList["`vivienda_tipo_id`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otro_tipo_vivienda"]) : $GLOBALS["x_otro_tipo_vivienda"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`otro_tipo_vivienda`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono"]) : $GLOBALS["x_telefono"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_secundario"]) : $GLOBALS["x_telefono_secundario"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_secundario`"] = $theValue;

		// update
		$sSql = "UPDATE `direccion` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE direccion_id = " . $GLOBALS["x_direccion_id"];
		$x_result = phpmkr_query($sSql,$conn);

		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}


//DIR NEG

		$fieldList = NULL;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle2"]) : $GLOBALS["x_calle2"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`calle`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia2"]) : $GLOBALS["x_colonia2"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`colonia`"] = $theValue;
		$theValue = ($GLOBALS["x_delegacion_id2"] != "") ? intval($GLOBALS["x_delegacion_id2"]) : "NULL";
		$fieldList["`delegacion_id`"] = $theValue;
/*		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otra_delegacion2"]) : $GLOBALS["x_otra_delegacion2"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`otra_delegacion`"] = $theValue;
*/
/*		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_entidad2"]) : $GLOBALS["x_entidad2"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`entidad`"] = $theValue;
*/		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_codigo_postal2"]) : $GLOBALS["x_codigo_postal2"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`codigo_postal`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion2"]) : $GLOBALS["x_ubicacion2"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ubicacion`"] = $theValue;
		$theValue = ($GLOBALS["x_antiguedad2"] != "") ? intval($GLOBALS["x_antiguedad2"]) : "NULL";
		$fieldList["`antiguedad`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono2"]) : $GLOBALS["x_telefono2"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_secundario"]) : $GLOBALS["x_telefono_secundario"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_secundario`"] = $theValue;

		// update
		$sSql = "UPDATE `direccion` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE direccion_id = " . $GLOBALS["x_direccion_id2"];
		$x_result = phpmkr_query($sSql,$conn);

		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}



//GARANTIAS


		if($GLOBALS["x_garantia_id"] > 0){
			
			$fieldList = NULL;		
			
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_descripcion"]) : $GLOBALS["x_descripcion"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`descripcion`"] = $theValue;
			$theValue = ($GLOBALS["x_valor"] != "") ? " '" . doubleval($GLOBALS["x_valor"]) . "'" : "NULL";
			$fieldList["`valor`"] = $theValue;
			
			// update
			$sSql = "UPDATE `garantia` SET ";
			foreach ($fieldList as $key=>$temp) {
				$sSql .= "$key = $temp, ";
			}
			if (substr($sSql, -2) == ", ") {
				$sSql = substr($sSql, 0, strlen($sSql)-2);
			}
			$sSql .= " WHERE garantia_id = " . $GLOBALS["x_garantia_id"];
			$x_result = phpmkr_query($sSql,$conn);

			if(!$x_result){
				echo phpmkr_error() . '<br>SQL: ' . $sSql;
				phpmkr_query('rollback;', $conn);	 
				exit();
			}
			
		}else{
		
			if($GLOBALS["x_garantia_desc"] != ""){
				$fieldList = NULL;
				// Field cliente_id
			//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
				$fieldList["`solicitud_id`"] = $GLOBALS["x_solicitud_id"];
			
				// Field descripcion
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_garantia_desc"]) : $GLOBALS["x_garantia_desc"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`descripcion`"] = $theValue;
			
				// Field valor
				$theValue = ($GLOBALS["x_garantia_valor"] != "") ? " '" . doubleval($GLOBALS["x_garantia_valor"]) . "'" : "NULL";
				$fieldList["`valor`"] = $theValue;
			
			
				// insert into database
				$sSql = "INSERT INTO `garantia` (";
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
				$GLOBALS["x_garantia_id"] = mysql_insert_id();				
			}
		
		}

//INGRESOS

		$fieldList = NULL;

		$theValue = ($GLOBALS["x_ingresos_negocio"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_negocio"]) . "'" : "NULL";
		$fieldList["`ingresos_negocio`"] = $theValue;
		$theValue = ($GLOBALS["x_ingresos_familiar_1"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_familiar_1"]) . "'" : "NULL";
		$fieldList["`ingresos_familiar_1`"] = $theValue;
		$theValue = ($GLOBALS["x_parentesco_tipo_id_ing_1"] != "") ? intval($GLOBALS["x_parentesco_tipo_id_ing_1"]) : "0";
		$fieldList["`parentesco_tipo_id`"] = $theValue;
		$theValue = ($GLOBALS["x_ingresos_familiar_2"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_familiar_2"]) . "'" : "NULL";
		$fieldList["`ingresos_familiar_2`"] = $theValue;
		$theValue = ($GLOBALS["x_parentesco_tipo_id_ing_2"] != "") ? intval($GLOBALS["x_parentesco_tipo_id_ing_2"]) : "0";
		$fieldList["`parentesco_tipo_id2`"] = $theValue;
		$theValue = ($GLOBALS["x_otros_ingresos"] != "") ? " '" . doubleval($GLOBALS["x_otros_ingresos"]) . "'" : "NULL";
		$fieldList["`otros_ingresos`"] = $theValue;

		// update
		$sSql = "UPDATE `ingreso` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE ingreso_id = " . $GLOBALS["x_ingreso_id"];
		$x_result = phpmkr_query($sSql,$conn);

		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}



//REFERENCIAS CICLO

	$x_count = 1;
	$sSql = "select referencia.* from 
	referencia join inciso_referencia 
	on inciso_referencia.referencia_id = referencia.referencia_id
	where inciso_referencia.solicitud_inciso_id = ".$GLOBALS["x_solicitud_inciso_id"]." order by referencia.referencia_id";
	$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	while ($row9 = phpmkr_fetch_array($rs9)){
		$sSql = " delete from referencia WHERE referencia_id = " . $row9["referencia_id"];
		$x_result = phpmkr_query($sSql,$conn);
	
		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}
		
		$x_count++;
	}
	$sSql = " delete from inciso_referencia WHERE solicitud_inciso_id = " . $GLOBALS["x_solicitud_inciso_id"];
	$x_result = phpmkr_query($sSql,$conn);

	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
		phpmkr_query('rollback;', $conn);	 
		exit();
	}

	
	$x_counter = 1;
	while($x_counter < 6){

		$fieldList = NULL;
		// Field cliente_id
//		$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
		$fieldList["`solicitud_id`"] = 0;


		if($GLOBALS["x_nombre_completo_ref_$x_counter"] != ""){
		
			// Field nombre_completo
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_completo_ref_$x_counter"]) : $GLOBALS["x_nombre_completo_ref_$x_counter"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`nombre_completo`"] = $theValue;
		
			// Field telefono
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_ref_$x_counter"]) : $GLOBALS["x_telefono_ref_$x_counter"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`telefono`"] = $theValue;
		
			// Field parentesco_tipo_id
			$theValue = ($GLOBALS["x_parentesco_tipo_id_ref_$x_counter"] != "") ? intval($GLOBALS["x_parentesco_tipo_id_ref_$x_counter"]) : "NULL";
			$fieldList["`parentesco_tipo_id`"] = $theValue;
		
			// insert into database
			$sSql = "INSERT INTO `referencia` (";
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
			$x_referencia_id = mysql_insert_id();
			
			//INCISO REFERENCIA
			$sSql = "insert into inciso_referencia values(0,".$GLOBALS["x_solicitud_inciso_id"].", $x_referencia_id)";
			$x_result = phpmkr_query($sSql, $conn);
			if(!$x_result){
				echo phpmkr_error() . '<br>SQL: ' . $sSql;
				phpmkr_query('rollback;', $conn);	 
				exit();
			}
		}

		$x_counter++;
	}


//ACTUALIZA SOLICITUD INCISO


	$GLOBALS["x_cliente_id"] = ($GLOBALS["x_cliente_id"] > 0) ? $GLOBALS["x_cliente_id"] : "0";
	$GLOBALS["x_ingreso_id"] = ($GLOBALS["x_ingreso_id"] > 0) ? $GLOBALS["x_ingreso_id"] : "0";
	$GLOBALS["x_garantia_id"] = ($GLOBALS["x_garantia_id"] > 0) ? $GLOBALS["x_garantia_id"] : "0";		
	
	$sSql = "update solicitud_inciso set cliente_id = ".$GLOBALS["x_cliente_id"].", ingreso_id = ".$GLOBALS["x_ingreso_id"].", garantia_id = ".$GLOBALS["x_garantia_id"]." ";
	$sSql .= " where solicitud_inciso_id = ".$GLOBALS["x_solicitud_inciso_id"];	
	$x_result = phpmkr_query($sSql, $conn);
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
		phpmkr_query('rollback;', $conn);	 
		exit();
	}

	phpmkr_query('commit;', $conn);	 
	
	return true;
}
?>
