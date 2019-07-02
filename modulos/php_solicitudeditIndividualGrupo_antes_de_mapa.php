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
<?php include ("../db.php") ?>
<?php include ("../phpmkrfn.php") ?>
<?php

$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

// Load key from QueryString
$x_solicitud_id = @$_GET["solicitud_id"];
$x_cliente_id_g = @$_GET["cliente_id"];
if(empty($x_solicitud_id)){
	$x_solicitud_id = @$_POST["x_solicitud_id"];
}
if(empty($x_cliente_id_g) || ($x_cliente_id_g == "")){
$sSqlWrk = "SELECT cliente_id FROM solicitud_cliente where solicitud_id = $x_solicitud_id";		
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
$x_cliente_id = $datawrk["cliente_id"];
@phpmkr_free_result($rswrk);
}else{
	$x_cliente_id = $x_cliente_id_g;
	
	}
$x_win = @$_GET["win"];

if(empty($x_win)){
	$x_win = @$_POST["x_win"];
	
}




//if (!empty($x_solicitud_id )) $x_solicitud_id  = (get_magic_quotes_gpc()) ? stripslashes($x_solicitud_id ) : $x_solicitud_id ;
// Get action

$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I";	// Display with input box
} else {

	// Get fields from form
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
	

	$x_actividad_id = $_POST["x_actividad_id"];
	$x_actividad_desc = $_POST["x_actividad_desc"];
	
	//agregados por requerimientos para el reporte de pronafin...
	//en cliente
	$x_rol_hogar_id = @$_POST["x_rol_hogar_id"];
	$x_fecha_ini_act_prod = @$_POST["x_fecha_ini_act_prod"];
	
//en direccion
	$x_numero_exterior = @$_POST["x_numero_exterior"];
	$x_compania_celular_id = @$_POST["x_compania_celular_id"];
	
	$x_telefono_secundario_2 = @$_POST["x_telefono_secundario_2"];
	$x_compania_celular_id_2 = @$_POST["x_compania_celular_id_2"];

//en negocio
	$x_giro_negocio_id = @$_POST["x_giro_negocio_id"];
	$x_tipo_inmueble_id = @$_POST["x_tipo_inmueble_id"];
	$x_atiende_titular = @$_POST["x_atiende_titular"];
	$x_personas_trabajando = @$_POST["x_personas_trabajando"];	
	$x_negocio_id = @$_POST["x_negocio_id"];
	$x_destino_credito_id = @$_POST["x_destino_credito_id"];

	$x_cliente_id = @$_POST["x_cliente_id"];
	$x_nombre_completo = @$_POST["x_nombre_completo"];
	$x_apellido_paterno = @$_POST["x_apellido_paterno"];	
	$x_apellido_materno = @$_POST["x_apellido_materno"];			
	$x_tit_rfc = @$_POST["x_tit_rfc"];	
	$x_tit_curp = @$_POST["x_tit_curp"];		
	$x_tipo_negocio = @$_POST["x_tipo_negocio"];
	$x_tit_fecha_nac = @$_POST["x_tit_fecha_nac"];	
		
	$x_edad = @$_POST["x_edad"];
	$x_sexo = @$_POST["x_sexo"];
	$x_estado_civil_id = @$_POST["x_estado_civil_id"];
	$x_numero_hijos = @$_POST["x_numero_hijos"];
	$x_numero_hijos_dep = @$_POST["x_numero_hijos_dep"];		
	$x_nombre_conyuge = @$_POST["x_nombre_conyuge"];
	$x_email = @$_POST["x_email"];	
	$x_nacionalidad_id = @$_POST["x_nacionalidad_id"];		
	$x_empresa = @$_POST["x_empresa"];		
	$x_puesto = @$_POST["x_puesto"];			
	$x_fecha_contratacion = @$_POST["x_fecha_contratacion"];				
	$x_salario_mensual = @$_POST["x_salario_mensual"];					


	$x_direccion_id = @$_POST["x_direccion_id"];
	$x_calle = @$_POST["x_calle"];
	$x_colonia = @$_POST["x_colonia"];
	$x_entidad_id = @$_POST["x_entidad_id"];	
	$x_delegacion_id = @$_POST["x_delegacion_id"];

	if(!empty($_POST["x_propietario_renta"])){
		$x_propietario = $_POST["x_propietario_renta"];	
	}
	if(!empty($_POST["x_propietario_familiar"])){
		$x_propietario = $_POST["x_propietario_familiar"];	
	}
	if(!empty($_POST["x_propietario_ch"])){
		$x_propietario = $_POST["x_propietario_ch"];	
	}


	$x_codigo_postal = @$_POST["x_codigo_postal"];
	$x_ubicacion = @$_POST["x_ubicacion"];
	$x_antiguedad = @$_POST["x_antiguedad"];
	$x_vivienda_tipo_id = @$_POST["x_vivienda_tipo_id"];
	$x_otro_tipo_vivienda = @$_POST["x_otro_tipo_vivienda"];
	$x_telefono = @$_POST["x_telefono"];
	$x_telefono_sec = @$_POST["x_telefono_sec"];		
	$x_telefono_secundario = @$_POST["x_telefono_secundario"];


	$x_direccion_id2 = @$_POST["x_direccion_id2"];
	$x_calle2 = @$_POST["x_calle2"];
	$x_colonia2 = @$_POST["x_colonia2"];
	$x_entidad_id2 = @$_POST["x_entidad_id2"];	
	$x_delegacion_id2 = @$_POST["x_delegacion_id2"];

	if(!empty($_POST["x_propietario_renta2"])){
		$x_propietario2 = $_POST["x_propietario_renta2"];	
	}
	if(!empty($_POST["x_propietario_familiar2"])){
		$x_propietario2 = $_POST["x_propietario_familiar2"];	
	}
	if(!empty($_POST["x_propietario_ch2"])){
		$x_propietario2 = $_POST["x_propietario_ch2"];	
	}



//	$x_propietario2 = @$_POST["x_propietario2"];
	$x_codigo_postal2 = @$_POST["x_codigo_postal2"];
	$x_ubicacion2 = @$_POST["x_ubicacion2"];
	$x_antiguedad2 = @$_POST["x_antiguedad2"];
	$x_otro_tipo_vivienda2 = @$_POST["x_otro_tipo_vivienda2"];
	$x_telefono2 = @$_POST["x_telefono2"];
	$x_telefono_secundario2 = @$_POST["x_telefono_secundario2"];


	$x_aval_id = @$_POST["x_aval_id"];
	$x_nombre_completo_aval = @$_POST["x_nombre_completo_aval"];
	$x_apellido_paterno_aval = @$_POST["x_apellido_paterno_aval"];	
	$x_apellido_materno_aval = @$_POST["x_apellido_materno_aval"];		
	
	$x_parentesco_tipo_id_aval = @$_POST["x_parentesco_tipo_id_aval"];
	
	$x_aval_rfc = @$_POST["x_aval_rfc"];	
	$x_aval_curp = @$_POST["x_aval_curp"];		

	$x_tipo_negocio_aval = @$_POST["x_tipo_negocio_aval"];
	$x_tit_fecha_nac_aval = @$_POST["x_tit_fecha_nac_aval"];	
	
	$x_edad_aval = @$_POST["x_edad_aval"];
	$x_sexo_aval = @$_POST["x_sexo_aval"];
	$x_estado_civil_id_aval = @$_POST["x_estado_civil_id_aval"];
	$x_numero_hijos_aval = @$_POST["x_numero_hijos_aval"];
	$x_numero_hijos_dep_aval = @$_POST["x_numero_hijos_dep_aval"];	
	$x_nombre_conyuge_aval = @$_POST["x_nombre_conyuge_aval"];
	$x_email_aval = @$_POST["x_email_aval"];
	$x_nacionalidad_id_aval  = @$_POST["x_nacionalidad_id_aval"];	


	$x_direccion_id3 = @$_POST["x_direccion_id3"];
	$x_calle3 = @$_POST["x_calle3"];
	$x_colonia3 = @$_POST["x_colonia3"];
	$x_delegacion_id3 = @$_POST["x_delegacion_id3"];
	$x_propietario3 = @$_POST["x_propietario3"];
	$x_codigo_postal3 = @$_POST["x_codigo_postal3"];
	$x_codigo_postal3 = @$_POST["x_codigo_postal3"];
	$x_ubicacion32 = @$_POST["x_ubicacion3"];
	$x_antiguedad3 = @$_POST["x_antiguedad3"];
	$x_vivienda_tipo_id2 = @$_POST["x_vivienda_tipo_id2"];
	$x_otro_tipo_vivienda3 = @$_POST["x_otro_tipo_vivienda3"];
	$x_telefono3 = @$_POST["x_telefono3"];
	$x_telefono3_sec = @$_POST["x_telefono3_sec"];	
		
	$x_telefono_secundario3 = @$_POST["x_telefono_secundario3"];

	$x_ingreso_aval_id = @$_POST["x_ingreso_aval_id"];
	$x_ingresos_mensuales = @$_POST["x_ingresos_mensuales"];
	$x_ingresos_familiar_1_aval = @$_POST["x_ingresos_familiar_1_aval"];				
	$x_parentesco_tipo_id_ing_1_aval = @$_POST["x_parentesco_tipo_id_ing_1_aval"];				
	$x_ingresos_familiar_2_aval = @$_POST["x_ingresos_familiar_2_aval"];					
	$x_parentesco_tipo_id_ing_2_aval = @$_POST["x_parentesco_tipo_id_ing_2_aval"];					
	$x_otros_ingresos_aval = @$_POST["x_otros_ingresos_aval"];	

	$x_origen_ingresos_aval = @$_POST["x_origen_ingresos_aval"];		
	$x_origen_ingresos_aval2 = @$_POST["x_origen_ingresos_aval2"];			
	$x_origen_ingresos_aval3 = @$_POST["x_origen_ingresos_aval3"];				
	
	$x_ocupacion = @$_POST["x_ocupacion"];

	
	$x_direccion_id4 = @$_POST["x_direccion_id4"];
	$x_calle3_neg = @$_POST["x_calle3_neg"];
	$x_colonia3_neg = @$_POST["x_colonia3_neg"];
	$x_entidad_id3_neg = @$_POST["x_entidad_id3_neg"];	
	$x_delegacion_id3_neg = @$_POST["x_delegacion_id3_neg"];
	$x_propietario3_neg = @$_POST["x_propietario3_neg"];
	$x_codigo_postal3_neg = @$_POST["x_codigo_postal3_neg"];
	$x_ubicacion3_neg = @$_POST["x_ubicacion3_neg"];
	$x_antiguedad3_neg = @$_POST["x_antiguedad3_neg"];
	$x_vivienda_tipo_id2_neg = @$_POST["x_vivienda_tipo_id2_neg"];
	$x_otro_tipo_vivienda3_neg = @$_POST["x_otro_tipo_vivienda3_neg"];
	$x_telefono3_neg = @$_POST["x_telefono3_neg"];
	$x_telefono_secundario3_neg = @$_POST["x_telefono_secundario3_neg"];

	$x_garantia_id = @$_POST["x_garantia_id"];
	$x_garantia_desc = @$_POST["x_garantia_desc"];
	$x_garantia_valor = @$_POST["x_garantia_valor"];
	$x_documento = NULL;


	$x_ingreso_id = @$_POST["x_ingreso_id"];
	$x_ingresos_negocio = @$_POST["x_ingresos_negocio"];
	$x_ingresos_familiar_1 = @$_POST["x_ingresos_familiar_1"];
	$x_parentesco_tipo_id_ing_1 = @$_POST["x_parentesco_tipo_id_ing_1"];
	$x_ingresos_familiar_2 = @$_POST["x_ingresos_familiar_2"];
	$x_parentesco_tipo_id_ing_2 = @$_POST["x_parentesco_tipo_id_ing_2"];
	$x_otros_ingresos = @$_POST["x_otros_ingresos"];
	$x_origen_ingresos = @$_POST["x_origen_ingresos"];	
	$x_origen_ingresos2 = @$_POST["x_origen_ingresos2"];	
	$x_origen_ingresos3 = @$_POST["x_origen_ingresos3"];			


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

	$x_comentario_promotor = @$_POST["x_comentario_promotor"];
	$x_comentario_comite = @$_POST["x_comentario_comite"];	


	$x_checklist_1 = isset($_POST["x_checklist_1"]) ? 1 : 0;	
	$x_checklist_2 = isset($_POST["x_checklist_2"]) ? 1 : 0;	
	$x_checklist_3 = isset($_POST["x_checklist_3"]) ? 1 : 0;	
	$x_checklist_4 = isset($_POST["x_checklist_4"]) ? 1 : 0;	
	$x_checklist_5 = isset($_POST["x_checklist_5"]) ? 1 : 0;	
	$x_checklist_6 = isset($_POST["x_checklist_6"]) ? 1 : 0;	
	$x_checklist_7 = isset($_POST["x_checklist_7"]) ? 1 : 0;	
	$x_checklist_8 = isset($_POST["x_checklist_8"]) ? 1 : 0;	
	$x_checklist_9 = isset($_POST["x_checklist_9"]) ? 1 : 0;	
	$x_checklist_10 = isset($_POST["x_checklist_10"]) ? 1 : 0;	
	$x_checklist_11 = isset($_POST["x_checklist_11"]) ? 1 : 0;	
	$x_checklist_12 = isset($_POST["x_checklist_12"]) ? 1 : 0;	
	$x_checklist_13 = isset($_POST["x_checklist_13"]) ? 1 : 0;	
	$x_checklist_14 = isset($_POST["x_checklist_14"]) ? 1 : 0;	
	$x_checklist_15 = isset($_POST["x_checklist_15"]) ? 1 : 0;	
	$x_checklist_16 = isset($_POST["x_checklist_16"]) ? 1 : 0;	
	$x_checklist_17 = isset($_POST["x_checklist_17"]) ? 1 : 0;	
	$x_checklist_18 = isset($_POST["x_checklist_18"]) ? 1 : 0;	
	$x_checklist_19 = isset($_POST["x_checklist_19"]) ? 1 : 0;	
	$x_checklist_20 = isset($_POST["x_checklist_20"]) ? 1 : 0;	
	$x_checklist_21 = isset($_POST["x_checklist_21"]) ? 1 : 0;		
	$x_checklist_22 = isset($_POST["x_checklist_22"]) ? 1 : 0;		
	$x_checklist_23 = isset($_POST["x_checklist_23"]) ? 1 : 0;		
	$x_checklist_24 = isset($_POST["x_checklist_24"]) ? 1 : 0;		
	$x_checklist_25 = isset($_POST["x_checklist_25"]) ? 1 : 0;			

	$x_checklist_26 = isset($_POST["x_checklist_26"]) ? 1 : 0;			
	$x_checklist_27 = isset($_POST["x_checklist_27"]) ? 1 : 0;			
	$x_checklist_28 = isset($_POST["x_checklist_28"]) ? 1 : 0;			
	$x_checklist_29 = isset($_POST["x_checklist_29"]) ? 1 : 0;			
	$x_checklist_30 = isset($_POST["x_checklist_30"]) ? 1 : 0;			
	$x_checklist_31 = isset($_POST["x_checklist_31"]) ? 1 : 0;				
	$x_checklist_32 = isset($_POST["x_checklist_32"]) ? 1 : 0;					
	

	$x_det_ck1 = @$_POST["x_det_ck1"];	
	$x_det_ck2 = @$_POST["x_det_ck2"];	
	$x_det_ck3 = @$_POST["x_det_ck3"];	
	$x_det_ck4 = @$_POST["x_det_ck4"];	
	$x_det_ck5 = @$_POST["x_det_ck5"];	
	$x_det_ck6 = @$_POST["x_det_ck6"];	
	$x_det_ck7 = @$_POST["x_det_ck7"];	
	$x_det_ck8 = @$_POST["x_det_ck8"];	
	$x_det_ck9 = @$_POST["x_det_ck9"];	
	$x_det_ck10 = @$_POST["x_det_ck10"];	
	$x_det_ck11 = @$_POST["x_det_ck11"];	
	$x_det_ck12 = @$_POST["x_det_ck12"];	
	$x_det_ck13 = @$_POST["x_det_ck13"];	
	$x_det_ck14 = @$_POST["x_det_ck14"];	
	$x_det_ck15 = @$_POST["x_det_ck15"];	
	$x_det_ck16 = @$_POST["x_det_ck16"];	
	$x_det_ck17 = @$_POST["x_det_ck17"];	
	$x_det_ck18 = @$_POST["x_det_ck18"];	
	$x_det_ck19 = @$_POST["x_det_ck19"];	
	$x_det_ck20 = @$_POST["x_det_ck20"];	
	$x_det_ck21 = @$_POST["x_det_ck21"];		
	$x_det_ck22 = @$_POST["x_det_ck22"];		
	$x_det_ck23 = @$_POST["x_det_ck23"];		
	$x_det_ck24 = @$_POST["x_det_ck24"];		
	$x_det_ck25 = @$_POST["x_det_ck25"];			

	$x_det_ck26 = @$_POST["x_det_ck26"];			
	$x_det_ck27 = @$_POST["x_det_ck27"];			
	$x_det_ck28 = @$_POST["x_det_ck28"];			
	$x_det_ck29 = @$_POST["x_det_ck29"];			
	$x_det_ck30 = @$_POST["x_det_ck30"];			
	$x_det_ck31 = @$_POST["x_det_ck31"];			
	$x_det_ck32 = @$_POST["x_det_ck32"];				

	$x_gasto_id = @$_POST["x_gasto_id"];
	$x_gastos_prov1 = @$_POST["x_gastos_prov1"];			
	$x_gastos_prov2 = @$_POST["x_gastos_prov2"];			
	$x_gastos_prov3 = @$_POST["x_gastos_prov3"];				
	$x_otro_prov = @$_POST["x_otro_prov"];					
	$x_gastos_empleados = @$_POST["x_gastos_empleados"];					
	$x_gastos_renta_negocio = @$_POST["x_gastos_renta_negocio"];					
	$x_gastos_renta_casa = @$_POST["x_gastos_renta_casa"];					
	$x_gastos_credito_hipotecario = @$_POST["x_gastos_credito_hipotecario"];					
	$x_gastos_otros = @$_POST["x_gastos_otros"];					

	$x_gasto_aval_id = @$_POST["x_gasto_aval_id"];
	$x_gastos_prov1_aval = @$_POST["x_gastos_prov1_aval"];			
	$x_gastos_prov2_aval = @$_POST["x_gastos_prov2_aval"];			
	$x_gastos_prov3_aval = @$_POST["x_gastos_prov3_aval"];				
	$x_otro_prov_aval = @$_POST["x_otro_prov_aval"];				
	$x_gastos_empleados_aval = @$_POST["x_gastos_empleados_aval"];				
	$x_gastos_renta_negocio_aval = @$_POST["x_gastos_renta_negocio_aval"];		
	$x_gastos_renta_casa2 = @$_POST["x_gastos_renta_casa2"];					
	$x_gastos_credito_hipotecario_aval = @$_POST["x_gastos_credito_hipotecario_aval"];					
	$x_gastos_otros_aval = @$_POST["x_gastos_otros_aval"];	
	
	
	
	//telefonos nueva seccion
	// telefonos nueva seccion 
	$contador_telefono =  $_POST["contador_telefono"];
	$contador_celular =   $_POST["contador_celular"];
	
	$x_telefono_casa_1 =  $_POST["x_telefono_casa_1"];
	$x_telefono_casa_2 =  $_POST["x_telefono_casa_2"];
	$x_telefono_casa_3 =  $_POST["x_telefono_casa_3"];
	$x_telefono_casa_4 =  $_POST["x_telefono_casa_4"];
	$x_telefono_casa_5 =  $_POST["x_telefono_casa_5"];
	$x_telefono_casa_6 =  $_POST["x_telefono_casa_6"];
	$x_telefono_casa_7 =  $_POST["x_telefono_casa_7"];
	$x_telefono_casa_8 =  $_POST["x_telefono_casa_8"];
	$x_telefono_casa_9 =  $_POST["x_telefono_casa_9"];
	$x_telefono_casa_10 =  $_POST["x_telefono_casa_10"];
	$x_comentario_casa_1 = $_POST["x_comentario_casa_1"];
	$x_comentario_casa_2 = $_POST["x_comentario_casa_2"];
	$x_comentario_casa_3 = $_POST["x_comentario_casa_3"];
	$x_comentario_casa_4 = $_POST["x_comentario_casa_4"];
	$x_comentario_casa_5 = $_POST["x_comentario_casa_5"];
	$x_comentario_casa_6 = $_POST["x_comentario_casa_6"];
	$x_comentario_casa_7 = $_POST["x_comentario_casa_7"];
	$x_comentario_casa_8 = $_POST["x_comentario_casa_8"];
	$x_comentario_casa_9 = $_POST["x_comentario_casa_9"];
	$x_comentario_casa_10 = $_POST["x_comentario_casa_10"];
	
	$x_telefono_celular_1 =  $_POST["x_telefono_celular_1"];
	$x_telefono_celular_2 =  $_POST["x_telefono_celular_2"];
	$x_telefono_celular_3 =  $_POST["x_telefono_celular_3"];
	$x_telefono_celular_4 =  $_POST["x_telefono_celular_4"];
	$x_telefono_celular_5 =  $_POST["x_telefono_celular_5"];
	$x_telefono_celular_6 =  $_POST["x_telefono_celular_6"];
	$x_telefono_celular_7 =  $_POST["x_telefono_celular_7"];
	$x_telefono_celular_8 =  $_POST["x_telefono_celular_8"];
	$x_telefono_celular_9 =  $_POST["x_telefono_celular_9"];
	$x_telefono_celular_10 =  $_POST["x_telefono_celular_10"];
	$x_comentario_celular_1 = $_POST["x_comentario_celular_1"];
	$x_comentario_celular_2 = $_POST["x_comentario_celular_2"];
	$x_comentario_celular_3 = $_POST["x_comentario_celular_3"];
	$x_comentario_celular_4 = $_POST["x_comentario_celular_4"];
	$x_comentario_celular_5 = $_POST["x_comentario_celular_5"];
	$x_comentario_celular_6 = $_POST["x_comentario_celular_6"];
	$x_comentario_celular_7 = $_POST["x_comentario_celular_7"];
	$x_comentario_celular_8 = $_POST["x_comentario_celular_8"];
	$x_comentario_celular_9 = $_POST["x_comentario_celular_9"];
	$x_comentario_celular_10 = $_POST["x_comentario_celular_10"];
	
	$x_compania_celular_1 =  $_POST["x_compania_celular_1"];
	$x_compania_celular_2 =  $_POST["x_compania_celular_2"];
	$x_compania_celular_3 =  $_POST["x_compania_celular_3"];
	$x_compania_celular_4 =  $_POST["x_compania_celular_4"];
	$x_compania_celular_5 =  $_POST["x_compania_celular_5"];
	$x_compania_celular_6 =  $_POST["x_compania_celular_6"];
	$x_compania_celular_7 =  $_POST["x_compania_celular_7"];
	$x_compania_celular_8 =  $_POST["x_compania_celular_8"];
	$x_compania_celular_9 =  $_POST["x_compania_celular_9"];
	$x_compania_celular_10 =  $_POST["x_compania_celular_10"];



}

// Check if valid key
if (($x_solicitud_id == "") || (is_null($x_solicitud_id))) {
	ob_end_clean();
	header("Location: php_solicitudlist.php");
	exit();
}

switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron los datos";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_solicitudlist.php");
			exit();
		}
		break;
	case "U": // Update
	
		if (EditData($conn)) { // Update Record based on key
		
		
				$GLOBALS["x_editar"] = "false";
				
			if($x_win == 1){
				
				header("Location: cuentas_view.php?key=$x_cliente_id");
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
				
				header("Location: casos_view.php?key=$x_crm_caso_id");
			}
			if($x_win == 3){
				echo "
				<br /><br /><p align='center'>
				<a href='javascript: window.close();'>Los datos han sido actualizados. De clic aqui para cerrar esta Ventana</a>
				</p>";
			}
			if($x_win == 4){
				header("Location: avales_view.php?key=$x_aval_id");				
			}else {
				// win  no tiene valor, se esta editando desde la caraula de solidario, desde la liga ver datos 
				echo"
				<br /><br /><p align='center'>
				<a href='javascript: window.close();'>Los datos han sido actualizados. De clic aqui para cerrar esta Ventana...</a>
				</p>";
				die();
				
				}
			
			phpmkr_db_close($conn);
			ob_end_clean();

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
<!-- <link href="../crm.css" rel="stylesheet" type="text/css"/> -->
<link href="../php_project_esf.css" rel="stylesheet" type="text/css" />
</head>

<body>

<script type="text/javascript" src="../ew.js"></script>
<script language="javascript" src="tipoCuenta/formatos/js/carga_telefonos.js"></script>
<script src="paisedohint.js"></script> 
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<script type="text/javascript">
<!--
function act(){

vact = document.solicitudedit.x_actividad_id.value;

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
EW_this = document.solicitudedit;
	EW_this.a_edit.value = "D";	
	EW_this.submit();	
}

function buscacliente(){
EW_this = document.solicitudedit;
	EW_this.a_edit.value = "X";
	EW_this.submit();

}
function validaimporte(){

EW_this = document.solicitudedit;

if(EW_this.x_importe_solicitado.value < 1000){
	alert("El importe es incorrecto valor minimo 1000");
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
	EW_this = document.solicitudedit;
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
			if (!EW_onError(EW_this, EW_this.x_delegacion_id, "SELECT", "Indique la delegaci贸n del domicilio particular."))
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
	EW_this = document.solicitudedit;
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
			if (!EW_onError(EW_this, EW_this.x_delegacion_id, "SELECT", "Indique la delegaci贸n del domicilio particular del titular."))
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
	EW_this = document.solicitudedit;
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
			if (!EW_onError(EW_this, EW_this.x_delegacion_id2, "SELECT", "Indique la delegaci贸n del domicilio del negocio del titular."))
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
	EW_this = document.solicitudedit;
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
			if (!EW_onError(EW_this, EW_this.x_delegacion_id3, "SELECT", "Indique la delegaci贸n del domicilio particular del aval."))
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
EW_this = document.solicitudedit;
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



function calcular_edad(fecha){ 
    //calculo la fecha de hoy 
    hoy=new Date();
	//alert("HOY ES"+ hoy);
    //alert(hoy) 

    //calculo la fecha que recibo 
    //La descompongo en un array 
	
    var array_fecha = fecha.split('/');
    //si el array no tiene tres partes, la fecha es incorrecta 
    if (array_fecha.length!=3) {
       return false 
		//alert("fecha incompleta");
	}
    //compruebo que los ano, mes, dia son correctos 
    var anio 
    anio = parseInt(array_fecha[2]); 
	//alert("anio"+ anio);
    if (isNaN(anio)) 
       return false 

    var mes 
    mes = parseInt(array_fecha[1]);
	//alert("mes"+mes);
    if (isNaN(mes)) 
       return false 

    var dia 
    dia = parseInt(array_fecha[0]);
	//alert("dia"+dia);
    if (isNaN(dia)) 
       return false 


    //si el a駉 de la fecha que recibo solo tiene 2 cifras hay que cambiarlo a 4 
    if (anio<=99) 
       anio +=1900 

    //resto los a駉s de las dos fechas 
    edad=hoy.getFullYear()- anio - 1; //-1 porque no se si ha cumplido a駉s ya este a駉 
	aa = hoy.getFullYear();
	//alert("hoy anio"+ aa);
	//alert("edad ="+edad);
    //si resto los meses y me da menor que 0 entonces no ha cumplido a駉s. Si da mayor si ha cumplido 
    if (hoy.getMonth() + 1 - mes < 0) //+ 1 porque los meses empiezan en 0 
       return edad 
    if (hoy.getMonth() + 1 - mes > 0) 
       return edad+1 

    //entonces es que eran iguales. miro los dias 
    //si resto los dias y me da menor que 0 entonces no ha cumplido a駉s. Si da mayor o igual si ha cumplido 
    if (hoy.getUTCDate() - dia >= 0) 
       return edad + 1 

    return edad 
} 

function actualizaReferencia(tipo){
	
	this.tipo = tipo;
	
	if(tipo == 1){
		
		var nuevoValor = parseInt(document.getElementById("contador_telefono").value);
		nuevoValor = nuevoValor + 1;
		document.getElementById("contador_telefono").value = nuevoValor;
		cargaCampoTelefono();
		}else if( tipo == 2){
			var nuevoValor = parseInt(document.getElementById("contador_celular").value);
			nuevoValor = nuevoValor + 1;
			document.getElementById("contador_celular").value = nuevoValor;
			cargaCampoCelular();
			}
	
	
	
	}
	
function cargaCampoTelefono(){
	var id = parseInt(document.getElementById("contador_telefono").value);
	process_tel(id, 1);
	
	}
	
function cargaCampoCelular(){
	var id = document.getElementById("contador_celular").value;
	process_tel(id, 2);
	
	}	




function EW_checkMyForm() {
EW_this = document.solicitudedit;
validada = true;
telefono_casa = 0;
telefono_cel = 0;


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
//alert("entro a paso numero de pagos");
if (validada == true && EW_this.x_plazo_id && EW_hasValue(EW_this.x_plazo_id, "TEXT" )) {
//verifcar si el numero de pagos es correcto
numer_pag = EW_this.x_plazo_id.value;
				 if((numer_pag < 2 ) ||(numer_pag > 104)){
					 // el numero de pagos es incorrecto deben ser minimo 2 maximo 88
					 alert("El numero de pago es incorreto verifique por favor, MIN 2, MAX 104");			 
					 validada = false;
					 }
 }
 //alert("entro a salgo denumero  de pagos");
if (validada == true && EW_this.x_forma_pago_id && !EW_hasValue(EW_this.x_forma_pago_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_forma_pago_id, "TEXT", "Indique la forma de pago solicitada."))
		validada = false;
}

if (validada == true && EW_this.x_nombre_completo && !EW_hasValue(EW_this.x_nombre_completo, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre_completo, "TEXT", "Indique su Nombre completo."))
		validada = false;
}
if (validada == true && EW_this.x_apellido_paterno && !EW_hasValue(EW_this.x_apellido_paterno, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_apellido_paterno, "TEXT", "Indique su Apellido Paterno."))
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
if (validada == true && EW_this.x_sexo && !EW_hasValue(EW_this.x_sexo, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_sexo, "TEXT", "Indique su genero."))
		validada = false;
}
if (validada == true && EW_this.x_estado_civil_id && !EW_hasValue(EW_this.x_estado_civil_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_estado_civil_id, "SELECT", "Indique su Estado Civil."))
		validada = false;
}


if (EW_this.x_rol_hogar_id && !EW_hasValue(EW_this.x_rol_hogar_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_rol_hogar_id, "SELECT", "Por el rol del titular en el hogar"))
		validada = false;
}

if (EW_this.x_fecha_ini_act_prod && !EW_hasValue(EW_this.x_fecha_ini_act_prod, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_ini_act_prod, "TEXT", "Por favor introduzca la fecha de inico de actividad productiva del titular"))
		validada = false;
}

if (validada == true && EW_this.x_calle && !EW_hasValue(EW_this.x_calle, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_calle, "TEXT", "Indique la calle del domicilio particular."))
		validada = false;
}


if (EW_this.x_numero_exterior && !EW_hasValue(EW_this.x_numero_exterior, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_numero_exterior, "TEXT", "Por favor introduzca el campo requerido - numero exterior"))
		validada = false;
}
if (EW_this.x_numero_exterior && !EW_checkinteger(EW_this.x_numero_exterior.value)) {
	if (!EW_onError(EW_this, EW_this.x_numero_exterior, "TEXT", "Valor incorrecto, se espera un entero. - numero exteriror"))
		validada = false; 
}

if (validada == true && EW_this.x_colonia && !EW_hasValue(EW_this.x_colonia, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_colonia, "TEXT", "Indique la colonia del domicilio particular."))
		validada = false;
}



if (EW_this.x_tit_fecha_nac && !EW_hasValue(EW_this.x_tit_fecha_nac, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tit_fecha_nac, "TEXT", "Por favor introduzca el campo requerido - fecha nacimiento"))
		validada = false;
}


if(EW_this.x_tit_fecha_nac.value.length != 0){
	
  var x_fecha_n = String(EW_this.x_tit_fecha_nac.value);
 // alert(x_fecha_n);
	
	x_edad_titular = calcular_edad(x_fecha_n);
	//alert ("la edad del titular es = "+x_edad_titular)
	if((x_edad_titular < 18) || (x_edad_titular > 90)){
	alert("El titular debe ser mayor de 18 a駉s, verifique la fecha de nacimiento formato dd/mm/aaaa");
	validada = false;
	}
}

if (validada == true && EW_this.x_codigo_postal && !EW_hasValue(EW_this.x_codigo_postal, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal, "TEXT", "Indique el Codigo Postal del domicilio particular."))
		validada = false;
}
if (validada == true && EW_this.x_ubicacion && !EW_hasValue(EW_this.x_ubicacion, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ubicacion, "TEXT", "Indique la Ubicacion del domicilio particular."))
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




// validacion para el telefono
if(EW_this.x_telefono.value.length == 0 && EW_this.x_telefono_secundario.value.length == 0 &&
   EW_this.x_telefono_sec.value.length == 0){
	
	// no se ha llenado ningun telefono...es necesario llenar almenos uno de los 4		
		alert("Por favor introduzca almenos 1 de los telefono listados en la seccion domicilio");
		validada = false;
	}


 if(EW_this.x_telefono_secundario.value.length > 0){
	 	 if((EW_this.x_telefono_secundario.value.length == 10)){			 
			 if (EW_this.x_compania_celular_id && !EW_hasValue(EW_this.x_compania_celular_id, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_compania_celular_id, "SELECT", "Debe seleccionar una compa駃a de telefonos para  el campo - celular"))
				validada = false;
				} 
			 
			 }else{
				 alert("Debe introducir el numero de celular a 10 digitos");
			 validada = false;
				 
				 }
	
	 
	 }
	 
	 //otro cel aqui
	 if(EW_this.x_telefono_secundario_2.value.length > 0){
	 	 if((EW_this.x_telefono_secundario_2.value.length == 10)){			 
			 if (EW_this.x_compania_celular_id_2 && !EW_hasValue(EW_this.x_compania_celular_id_2, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_compania_celular_id_2, "SELECT", "Debe seleccionar una compa駃a de telefonos para el campo - Otro Celular"))
				validada = false;
				} 
			 
			 }else{
				 alert("Debe introducir el numero de celular a 10 digitos - otro celular - ");
			 validada = false;
				 
				 }
	
	 
	 }



if ( validada && EW_this.x_telefono_casa_1 && EW_this.x_telefono_casa_1.value.length > 0) {
	if((EW_this.x_telefono_casa_1.value.length != 10)){
		alert ("El numero de telefono 1 debe tener 10 digitos");
		validada = false;
	}
 telefono_casa =  telefono_casa + 1 ;	
}	

if ( validada && EW_this.x_telefono_casa_2 && EW_this.x_telefono_casa_2.value.length > 0) {
	if((EW_this.x_telefono_casa_2.value.length != 10)){
		alert("El numero de telefono 2 debe tener 10 digitos");	
		validada = false;
	}
	 telefono_casa =  telefono_casa + 1 ;	
}	


if ( validada && EW_this.x_telefono_casa_3 && EW_this.x_telefono_casa_3.value.length > 0) {
	if((EW_this.x_telefono_casa_3.value.length != 10)){
		alert ("El numero de telefono 3 debe tener 10 digitos");	
		validada = false;
	}
	 telefono_casa =  telefono_casa + 1 ;	
}

if ( validada && EW_this.x_telefono_casa_4 && EW_this.x_telefono_casa_4.value.length > 0) {
	if((EW_this.x_telefono_casa_4.value.length != 10)){
		alert ("El numero de telefono 4 debe tener 10 digitos");	
		validada = false;
	}
	 telefono_casa =  telefono_casa + 1 ;	
}	

if ( validada && EW_this.x_telefono_casa_5 && EW_this.x_telefono_casa_5.value.length > 0) {
	if((EW_this.x_telefono_casa_5.value.length != 10)){
		alert ("El numero de telefono 5 debe tener 10 digitos");	
		validada = false;
	}
	 telefono_casa =  telefono_casa + 1 ;	
}	


if ( validada  && EW_this.x_telefono_casa_6 && EW_this.x_telefono_casa_6.value.length > 0) {
	if((EW_this.x_telefono_casa_6.value.length != 10)){
		alert ("El numero de telefono 6 debe tener 10 digitos");	
		validada = false;
	}
	 telefono_casa =  telefono_casa + 1 ;	
}



if ( validada && EW_this.x_telefono_casa_7 && EW_this.x_telefono_casa_7.value.length > 0) {
	if((EW_this.x_telefono_casa_7.value.length != 10)){
		alert ("El numero de telefono 7 debe tener 10 digitos");	
		validada = false;
	}
	 telefono_casa =  telefono_casa + 1 ;	
}	

if ( validada && EW_this.x_telefono_casa_8 && EW_this.x_telefono_casa_8.value.length > 0) {
	if((EW_this.x_telefono_casa_8.value.length != 10)){
		alert ("El numero de telefono 8 debe tener 10 digitos");	
		validada = false;
	}
	 telefono_casa =  telefono_casa + 1 ;	
}	


if ( validada && EW_this.x_telefono_casa_9 && EW_this.x_telefono_casa_9.value.length > 0) {
	if((EW_this.x_telefono_casa_9.value.length != 10)){
		alert ("El numero de telefono 9 debe tener 10 digitos");	
		validada = false;
	}
	 telefono_casa =  telefono_casa + 1 ;	
}
	 
if ( validada && EW_this.x_telefono_casa_10 && EW_this.x_telefono_casa_10.value.length > 0) {
	if((EW_this.x_telefono_casa_10.value.length != 10)){
		alert ("El numero de telefono 10 debe tener 10 digitos");	
		validada = false;
	}
	 telefono_casa =  telefono_casa + 1 ;	
}	



if ( validada && EW_this.x_telefono_celular_1  && EW_this.x_telefono_celular_1.value.length > 0) {
	if((EW_this.x_telefono_celular_1.value.length != 10)){
		alert ("El numero de celular 1 debe tener 10 digitos");	
		validada = false;
	}else{
		if (EW_this.x_compania_celular_1 && !EW_hasValue(EW_this.x_compania_celular_1, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_compania_celular_1, "SELECT", "Debe seleccionar una compania de telefonos para el celular 1"))
				validada = false;
		}
		
		}
		telefono_cel = telefono_cel + 1;
}

if ( validada && EW_this.x_telefono_celular_2 && EW_this.x_telefono_celular_2.value.length > 0) {
	if((EW_this.x_telefono_celular_2.value.length != 10)){
		alert ("El numero de celular 2 debe tener 10 digitos");	
		validada = false;
	}else{
		if (EW_this.x_compania_celular_2 && !EW_hasValue(EW_this.x_compania_celular_2, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_compania_celular_2, "SELECT", "Debe seleccionar una compania de telefonos para el celular 2"))
				validada = false;
		}
		
		}
		telefono_cel = telefono_cel + 1;
}


if ( validada && EW_this.x_telefono_celular_4 && EW_this.x_telefono_celular_3.value.length > 0) {
	if((EW_this.x_telefono_celular_3.value.length != 10)){
		alert ("El numero de celular 1 debe tener 10 digitos");	
		validada = false;
	}else{
		if (EW_this.x_compania_celular_3 && !EW_hasValue(EW_this.x_compania_celular_3, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_compania_celular_3, "SELECT", "Debe seleccionar una compania de telefonos para el celular 3"))
				validada = false;
		}
		
		}
		telefono_cel = telefono_cel + 1;
}

if ( validada  && EW_this.x_telefono_celular_4 && EW_this.x_telefono_celular_4.value.length > 0) {
	if((EW_this.x_telefono_celular_4.value.length != 10)){
		alert ("El numero de celular 4 debe tener 10 digitos");	
		validada = false;
	}else{
		if (EW_this.x_compania_celular_4 && !EW_hasValue(EW_this.x_compania_celular_4, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_compania_celular_4, "SELECT", "Debe seleccionar una compania de telefonos para el celular 4"))
				validada = false;
		}
		
		}
		telefono_cel = telefono_cel + 1;
}


if ( validada && EW_this.x_telefono_celular_5 && EW_this.x_telefono_celular_5.value.length > 0) {
	if((EW_this.x_telefono_celular_5.value.length != 10)){
		alert ("El numero de celular 5 debe tener 10 digitos");	
		validada = false;
	}else{
		if (EW_this.x_compania_celular_5 && !EW_hasValue(EW_this.x_compania_celular_5, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_compania_celular_5, "SELECT", "Debe seleccionar una compania de telefonos para el celular 5"))
				validada = false;
		}
		
		}
		telefono_cel = telefono_cel + 1;
}


if ( validada && EW_this.x_telefono_celular_6 && EW_this.x_telefono_celular_6.value.length > 0) {
	if((EW_this.x_telefono_celular_6.value.length != 10)){
		alert ("El numero de celular 6 debe tener 10 digitos");	
		validada = false;
	}else{
		if (EW_this.x_compania_celular_6 && !EW_hasValue(EW_this.x_compania_celular_6, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_compania_celular_6, "SELECT", "Debe seleccionar una compania de telefonos para el celular 6"))
				validada = false;
		}
		
		}
		telefono_cel = telefono_cel + 1;
}


if ( validada && EW_this.x_telefono_celular_7 && EW_this.x_telefono_celular_7.value.length > 0) {
	if((EW_this.x_telefono_celular_7.value.length != 10)){
		alert ("El numero de celular 7 debe tener 10 digitos");	
		validada = false;
	}else{
		if (EW_this.x_compania_celular_7 && !EW_hasValue(EW_this.x_compania_celular_7, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_compania_celular_7, "SELECT", "Debe seleccionar una compania de telefonos para el celular 7"))
				validada = false;
		}
		
		}
		telefono_cel = telefono_cel + 1;
}

if ( validada && EW_this.x_telefono_celular_8 && EW_this.x_telefono_celular_8.value.length > 0) {
	if((EW_this.x_telefono_celular_8.value.length != 10)){
		alert ("El numero de celular 8 debe tener 10 digitos");	
		validada = false;
	}else{
		if (EW_this.x_compania_celular_8 && !EW_hasValue(EW_this.x_compania_celular_8, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_compania_celular_8, "SELECT", "Debe seleccionar una compania de telefonos para el celular 8"))
				validada = false;
		}
		
		}
		telefono_cel = telefono_cel + 1;
}


if ( validada && EW_this.x_telefono_celular_9 && EW_this.x_telefono_celular_9.value.length > 0) {
	if((EW_this.x_telefono_celular_9.value.length != 10)){
		alert ("El numero de celular 9 debe tener 10 digitos");	
		validada = false;
	}else{
		if (EW_this.x_compania_celular_9 && !EW_hasValue(EW_this.x_compania_celular_9, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_compania_celular_9, "SELECT", "Debe seleccionar una compania de telefonos para el celular 9"))
				validada = false;
		}
		
		}
		telefono_cel = telefono_cel + 1;
}

if ( validada && EW_this.x_telefono_celular_10 && EW_this.x_telefono_celular_10.value.length > 0) {
	if((EW_this.x_telefono_celular_10.value.length != 10)){
		alert ("El numero de celular 10 debe tener 10 digitos");	
		validada = false;
	}else{
		if (EW_this.x_compania_celular_10 && !EW_hasValue(EW_this.x_compania_celular_10, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_compania_celular_10, "SELECT", "Debe seleccionar una compania de telefonos para el celular 10"))
				validada = false;
		}
		
		}
		telefono_cel = telefono_cel + 1;
		
}


if( telefono_casa < 1  && telefono_cel < 1){
	validada = false;
	alert("Debe introducir por lo menos un telefono en la nueva seccion de telefonos, puede ser fijo o  celular")
	
	}








if (EW_this.x_giro_negocio_id && !EW_hasValue(EW_this.x_giro_negocio_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_giro_negocio_id, "SELECT", "Por debe seleccionar un valor para el campo giro negocio"))
		validada = false;
}

if (EW_this.x_tipo_inmueble_id && !EW_hasValue(EW_this.x_tipo_inmueble_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_tipo_inmueble_id, "SELECT", "Por debe seleccionar un valor para el campo tipo negocio"))
		validada = false;
}
if (EW_this.x_atiende_titular && !EW_hasValue(EW_this.x_atiende_titular, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_atiende_titular, "SELECT", "Por debe seleccionar un valor para el campo atiende titular"))
		validada = false;
}
if (EW_this.x_personas_trabajando && !EW_hasValue(EW_this.x_personas_trabajando, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_personas_trabajando, "TEXT", "Por favor indique el numero de personas que trabajan en el negocio"))
		validada = false;
}

if (EW_this.x_personas_trabajando && !EW_checkinteger(EW_this.x_personas_trabajando.value)) {
	if (!EW_onError(EW_this, EW_this.x_personas_trabajando, "TEXT", "Valor incorrecto, se espera un entero. - personas trabajando"))
		validada = false; 
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
	if (!EW_onError(EW_this, EW_this.x_telefono_ref_1, "TEXT", "Indique el tel茅fono de la referencia 1."))
		validada = false;
}
if (validada == true && EW_this.x_parentesco_tipo_id_ref_1 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_1, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_1, "SELECT", "Indique el parentesco en la referencia 1."))
		validada = false;
}

if (validada == true && EW_this.x_nombre_completo_ref_2 && EW_hasValue(EW_this.x_nombre_completo_ref_2, "TEXT" )) {
	if (validada == true && EW_this.x_telefono_ref_2 && !EW_hasValue(EW_this.x_telefono_ref_2, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_telefono_ref_2, "TEXT", "Indique el tel茅fono de la referencia 2."))
			validada = false;
	}
	if (validada == true && EW_this.x_parentesco_tipo_id_ref_2 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_2, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_2, "SELECT", "Indique el parentesco en la referencia 2."))
			validada = false;
	}
}

if (validada == true && EW_this.x_nombre_completo_ref_3 && EW_hasValue(EW_this.x_nombre_completo_ref_3, "TEXT" )) {
	if (validada == true && EW_this.x_telefono_ref_3 && !EW_hasValue(EW_this.x_telefono_ref_3, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_telefono_ref_3, "TEXT", "Indique el tel茅fono de la referencia 3."))
			validada = false;
	}
	if (validada == true && EW_this.x_parentesco_tipo_id_ref_3 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_3, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_3, "SELECT", "Indique el parentesco en la referencia 3."))
			validada = false;
	}
}

if (validada == true && EW_this.x_nombre_completo_ref_4 && EW_hasValue(EW_this.x_nombre_completo_ref_4, "TEXT" )) {
	if (validada == true && EW_this.x_telefono_ref_4 && !EW_hasValue(EW_this.x_telefono_ref_4, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_telefono_ref_4, "TEXT", "Indique el tel茅fono de la referencia 4."))
			validada = false;
	}
	if (validada == true && EW_this.x_parentesco_tipo_id_ref_4 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_4, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_4, "SELECT", "Indique el parentesco en la referencia 4."))
			validada = false;
	}
}
if (validada == true && EW_this.x_nombre_completo_ref_5 && EW_hasValue(EW_this.x_nombre_completo_ref_5, "TEXT" )) {
	if (validada == true && EW_this.x_telefono_ref_5 && !EW_hasValue(EW_this.x_telefono_ref_5, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_telefono_ref_5, "TEXT", "Indique el tel茅fono de la referencia 5."))
			validada = false;
	}
	if (validada == true && EW_this.x_parentesco_tipo_id_ref_5 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_5, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_5, "SELECT", "Indique el parentesco en la referencia 5."))
			validada = false;
	}
}



//referencias requeridos 2
if((EW_this.x_nombre_completo_ref_1.value.length != 0 && EW_this.x_nombre_completo_ref_2.value.length != 0)||
	(EW_this.x_nombre_completo_ref_1.value.length != 0 && EW_this.x_nombre_completo_ref_3.value.length != 0) ||
	(EW_this.x_nombre_completo_ref_1.value.length != 0 && EW_this.x_nombre_completo_ref_4.value.length != 0) ||
	(EW_this.x_nombre_completo_ref_1.value.length != 0 && EW_this.x_nombre_completo_ref_5.value.length != 0) ||
	(EW_this.x_nombre_completo_ref_2.value.length != 0 && EW_this.x_nombre_completo_ref_3.value.length != 0) ||
	(EW_this.x_nombre_completo_ref_2.value.length != 0 && EW_this.x_nombre_completo_ref_4.value.length != 0) ||
	(EW_this.x_nombre_completo_ref_2.value.length != 0 && EW_this.x_nombre_completo_ref_5.value.length != 0) ||
	(EW_this.x_nombre_completo_ref_3.value.length != 0 && EW_this.x_nombre_completo_ref_4.value.length != 0) ||
	(EW_this.x_nombre_completo_ref_3.value.length != 0 && EW_this.x_nombre_completo_ref_5.value.length != 0) ||
	(EW_this.x_nombre_completo_ref_4.value.length != 0 && EW_this.x_nombre_completo_ref_5.value.length != 0)){
	// almenos dos referencias requeridas
	if (validada == true && EW_this.x_nombre_completo_ref_1 && EW_hasValue(EW_this.x_nombre_completo_ref_1, "TEXT" )) {
		if (validada == true && EW_this.x_telefono_ref_1 && !EW_hasValue(EW_this.x_telefono_ref_1, "TEXT" )) {
				if (!EW_onError(EW_this, EW_this.x_telefono_ref_1, "TEXT", "Indique el tel閒ono de la referencia 1."))
					validada = false;
			}
			if (validada == true && EW_this.x_parentesco_tipo_id_ref_1 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_1, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_1, "SELECT", "Indique el parentesco en la referencia 1."))
					validada = false;
			}
				}
				
		if (validada == true && EW_this.x_nombre_completo_ref_2 && EW_hasValue(EW_this.x_nombre_completo_ref_2, "TEXT" )) {
		if (validada == true && EW_this.x_telefono_ref_2 && !EW_hasValue(EW_this.x_telefono_ref_2, "TEXT" )) {
				if (!EW_onError(EW_this, EW_this.x_telefono_ref_2, "TEXT", "Indique el tel閒ono de la referencia 2."))
					validada = false;
			}
			if (validada == true && EW_this.x_parentesco_tipo_id_ref_2 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_2, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_2, "SELECT", "Indique el parentesco en la referencia 2."))
					validada = false;
			}
				}
				
				
				if (validada == true && EW_this.x_nombre_completo_ref_3 && EW_hasValue(EW_this.x_nombre_completo_ref_3, "TEXT" )) {
		if (validada == true && EW_this.x_telefono_ref_3 && !EW_hasValue(EW_this.x_telefono_ref_3, "TEXT" )) {
				if (!EW_onError(EW_this, EW_this.x_telefono_ref_3, "TEXT", "Indique el tel閒ono de la referencia 3."))
					validada = false;
			}
			if (validada == true && EW_this.x_parentesco_tipo_id_ref_3 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_3, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_3, "SELECT", "Indique el parentesco en la referencia 3."))
					validada = false;
			}
				}
				
				
		if (validada == true && EW_this.x_nombre_completo_ref_4 && EW_hasValue(EW_this.x_nombre_completo_ref_4, "TEXT" )) {
		if (validada == true && EW_this.x_telefono_ref_4 && !EW_hasValue(EW_this.x_telefono_ref_4, "TEXT" )) {
				if (!EW_onError(EW_this, EW_this.x_telefono_ref_4, "TEXT", "Indique el tel閒ono de la referencia 4."))
					validada = false;
			}
			if (validada == true && EW_this.x_parentesco_tipo_id_ref_4 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_4, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_4, "SELECT", "Indique el parentesco en la referencia 4."))
					validada = false;
			}
				}		
				
			
			if (validada == true && EW_this.x_nombre_completo_ref_5 && EW_hasValue(EW_this.x_nombre_completo_ref_5, "TEXT" )) {
		if (validada == true && EW_this.x_telefono_ref_5 && !EW_hasValue(EW_this.x_telefono_ref_5, "TEXT" )) {
				if (!EW_onError(EW_this, EW_this.x_telefono_ref_5, "TEXT", "Indique el tel閒ono de la referencia 5."))
					validada = false;
			}
			if (validada == true && EW_this.x_parentesco_tipo_id_ref_5 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_5, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_5, "SELECT", "Indique el parentesco en la referencia 5."))
					validada = false;
			}
				}
			
			
				
	}else{
		alert("Debe introducir almenos dos referencias");
		validada = false;
		
		}


/*
if(validada == true && EW_this.x_acepto.checked == false){
	alert("Debe de marcar la casilla: Aceptao los t茅rminos y condiciones.");
	validada = false;
}
*/
if(validada == true){
	EW_this.a_edit.value = "U";
	EW_this.submit();
}
}




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
<form name="solicitudedit" id="solicitudedit" action="php_solicitudeditIndividualGrupo.php" method="post" >
<input type="hidden" name="x_win" value="<?php echo $x_win; ?>">
<input type="hidden" name="a_edit" value="U">
<input type="hidden" name="x_solicitud_id" value="<?php echo $x_solicitud_id; ?>"  />
<input type="hidden" name="x_direccion_id" value="<?php echo $x_direccion_id; ?>" />
<span class="texto_normal">
<?php if($x_win == 1){ ?>
<a href="cuentas_view.php?key=<?php echo $x_cliente_id; ?>">Regresar a los datos del Cliente</a>

<?php 
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

<a href="casos_view.php?key=<?php echo $x_crm_caso_id; ?>">Regresar a los datos del Caso</a>
<?php 
}
if($x_win == 3){
?>
<a href='javascript: window.close();'>De clic aqui para cerrar esta Ventana</a>
<?php } ?>
<?php if($x_win == 4){ ?>
<a href="avales_view.php?key=<?php echo $x_aval_id; ?>">Regresar a los datos del Aval</a>
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
<input type="hidden" name="x_negocio_id" value="<?php echo $x_negocio_id;?>" />
 
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
  <a href="php_solicitud_print.php?solicitud_id=<?php echo $x_solicitud_id; ?>" title="Imprimir Solicitud" target="_blank">        
    <img src="../images/tbarImport.gif" width="28" height="27" border="0" />
    </a>         
        </td>
        <td width="30">&nbsp;</td>
        <td width="144" align="center" valign="middle">&nbsp;</td>
        <td width="31">&nbsp;</td>
        <td width="143" align="center" valign="middle">&nbsp;</td>
        </tr>
      <tr>
        <td align="center" valign="middle">Imprimir Solicitud</td>
        <td>&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td>&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
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

		if($x_solicitud_status_id < 5){

		$x_estado_civil_idList = "<select name=\"x_solicitud_status_id\" class=\"texto_normal\" readonly=\"readonly\" >";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		if($_SESSION["crm_UserRolID"] == 7) {
			$sSqlWrk = "SELECT solicitud_status_id, descripcion FROM solicitud_status Where  solicitud_status_id = ".@$x_solicitud_status_id;
		}else{
			$sSqlWrk = "SELECT `solicitud_status_id`, `descripcion` FROM `solicitud_status`";		
		}
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["solicitud_status_id"] == @$x_solicitud_status_id) {
					$x_estado_civil_idList .= "' selected";
					$X_solicitud_status_descripcion = $datawrk["descripcion"];
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $X_solicitud_status_descripcion;
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

		?>        
        
        </td>
      </tr>
      <tr>
        <td class="texto_normal">Cliente No:</td>
        <td colspan="2"><div class="phpmaker"><b> <?php echo htmlspecialchars(@$x_cliente_id) ?>
                  <input type="hidden" name="x_cliente_id" value="<?php echo htmlspecialchars(@$x_cliente_id) ?>" />
        </b></div></td>
        <td><div align="right"><span class="texto_normal">Credito No:</span></div></td>
        <td><div class="phpmaker"><b> <?php echo htmlspecialchars(@$x_credito_id) ?> </b></div></td>
      </tr>
      <tr>
        <td class="texto_normal">Promotor:</td>  
        <td colspan="4"><div class="phpmaker">
            <?php
		$x_estado_civil_idList = "<select name=\"x_promotor_id\" class=\"texto_normal\" >";
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
					$X_descripcion_promotor = $datawrk["nombre_completo"];
				}
				$x_estado_civil_idList .= ">" . htmlentities($datawrk["nombre_completo"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $X_descripcion_promotor;
?>
        </div></td>
      </tr>
      <tr>
        <td width="159" class="texto_normal">Tipo de Cr&eacute;dito: </td>
        <td colspan="2" class="texto_normal"><b>INDIVIDUAL GRUPO</b>
		<input type="hidden" name="x_credito_tipo_id" value="2" />
        </td>
        <td width="230"><div align="right"><span class="texto_normal">&nbsp;Fecha Solicitud:</span></div></td>
        <td width="164"><span class="texto_normal"> <b> <?php echo $currdate; ?> </b> </span>
            <input name="x_fecha_registro" type="hidden" value="<?php echo $currdate; ?>" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Importe Grupal solicitado: </span></td>
        <td width="111"><div align="left">
            <!-- <input class="importe" name="x_importe_solicitado" type="text" id="x_importe_solicitado" value=" --><?php echo FormatNumber(@$x_importe_solicitado,0,0,0,0) ?><!-- " size="10" maxlength="10" onkeypress="return solonumeros(this,event)" onblur="validaimporte()"  /> -->
        </div></td>
        <td width="10">&nbsp;</td>
        <td><div align="right"><span class="texto_normal">N&uacute;mero de pagos:</span></div></td>
        <td><span class="texto_normal"><!-- <input type="text" name="x_plazo_id" id="x_plazo_id" maxlength="3"  value=" --><?php echo $x_plazo_id;?><!-- " size="15" onKeyPress="return solonumeros(this,event)" onchange="valorMax();" /> -->
          <?php
		/*$x_estado_civil_idList = "<select name=\"x_plazo_id\" class=\"texto_normal\" readonly=\"readonly\">";
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
		$x_estado_civil_idList = "<select name=\"x_forma_pago_id\" class=\"texto_normal\" >";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `forma_pago_id`, `descripcion` FROM `forma_pago`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["forma_pago_id"] == @$x_forma_pago_id) {
					$x_estado_civil_idList .= "' selected";
					$X_descripcion_forma_pago = $datawrk["descripcion"];
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $X_descripcion_forma_pago;
		?>
        </span></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td></td>
        <td colspan="4">&nbsp;</td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="4" class="texto_normal">      &nbsp;
          <div id="actividad2" class="TG_hidden">Consistentes en:</div>
      <div id="actividad3" class="TG_hidden">Especificar qu&eacute; y para qu&eacute;:</div>      </td>
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
	  <td width="165"><span class="texto_normal">Titular:</span></td>
	  <td colspan="4"><table width="534" border="0" cellspacing="0" cellpadding="0">
	    <tr>
	      <td width="155"><div align="center">
	        <input name="x_nombre_completo" type="text" class="texto_normal" id="x_nombre_completo" value="<?php echo htmlentities(@$x_nombre_completo) ?>" size="25" maxlength="250" />
	        </div></td>
	      <td width="178"><div align="center">
	        <input name="x_apellido_paterno" type="text" class="texto_normal" id="x_apellido_paterno" value="<?php echo htmlentities(@$x_apellido_paterno) ?>" size="25" maxlength="250" />
	        </div></td>
	      <td width="201"><div align="center">
	        <input name="x_apellido_materno" type="text" class="texto_normal" id="x_apellido_materno" value="<?php echo htmlentities(@$x_apellido_materno) ?>" size="25" maxlength="250" />
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
	    <input name="x_tit_rfc" type="text" class="texto_normal" id="x_tit_rfc" value="<?php echo htmlentities(@$x_tit_rfc) ?>" size="20" maxlength="20" /></td>
	  </tr>
	<tr>
	  <td class="texto_normal">CURP:</td>
	  <td colspan="4">
      <input name="x_tit_curp" type="text" class="texto_normal" id="x_tit_curp" value="<?php echo htmlentities(@$x_tit_curp) ?>" size="20" maxlength="20" /></td>
	  </tr>
      
	<tr>
	  <td><span class="texto_normal">Tipo de Negocio: </span></td>
	  <td colspan="4"><input name="x_tipo_negocio" type="text" class="texto_normal" id="x_tipo_negocio" value="<?php echo htmlentities(@$x_tipo_negocio) ?>" size="80" maxlength="250" /></td>
	  </tr>
	<tr>
	  <td><span class="texto_normal">Fecha de Nacimiento:</span></td>
	  <td colspan="4"><table width="533" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="121" align="left">
            <span class="texto_normal">
            <input name="x_tit_fecha_nac" type="text" id="x_tit_fecha_nac" value="<?php echo FormatDateTime(@$x_tit_fecha_nac,7); ?>" size="12" maxlength="12">
            &nbsp;<img src="../images/ew_calendar.gif" id="cx_tit_fecha_nac" alt="Calendario" style="cursor:pointer;cursor:hand;">
            <script type="text/javascript">
            Calendar.setup(
            {
            inputField : "x_tit_fecha_nac", // ID of the input field
            ifFormat : "%d/%m/%Y", // the date format
            button : "cx_tit_fecha_nac" // ID of the button
            }
            );
            </script>
            </span>            </td>
          <td width="160" align="left" valign="middle"><div align="left"><span class="texto_normal">Sexo:&nbsp;
          <select name="x_sexo" id="x_sexo">
          <option> Seleccione </option>
        <option value="1" <?php if($x_sexo == 1){echo("SELECTED");} ?> >Masculino</option> 
		<option value="2" <?php if($x_sexo == 2){echo("SELECTED");} ?>>Femenino</option>
        </select>
           <!-- <input name="x_sexo" type="radio" value="<?php echo htmlspecialchars("1"); ?>" checked="checked" <?php if (@$x_sexo == "1") { echo "checked"; } ?> />
              <?php echo "M"; ?> <?php echo EditOptionSeparator(0); ?>
              <input type="radio" name="x_sexo"<?php if (@$x_sexo == "2") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("2"); ?>" />
              <?php echo "F"; ?> <?php echo EditOptionSeparator(1); ?> </span></div> --></td>
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
		<input name="x_nombre_conyuge" type="text" class="texto_normal" id="x_nombre_conyuge" value="<?php echo htmlentities(@$x_nombre_conyuge) ?>" size="80" maxlength="250">		</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Email</span>:</td>
        <td colspan="3"><input name="x_email" type="text" class="texto_normal" id="x_email" value="<?php echo htmlspecialchars(@$x_email) ?>" size="50" maxlength="150" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Nacionalidad:</span></td>
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
      
      <tr>
        <td class="texto_normal">Rol en el hogar:</td>
        <td colspan="3"><?php
		$x_entidad_idList = "<select name=\"x_rol_hogar_id\" id=\"x_rol_hogar_id\" class=\"texto_normal\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `rol_hogar_id`, `descripcion` FROM `rol_hogar`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["rol_hogar_id"] == @$x_rol_hogar_id) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?></td>
      </tr>
      <tr>
        <td class="texto_normal">Fecha inicio actividad productiva:</td>
        <td colspan="3"><span><input type="text" name="x_fecha_ini_act_prod" id="x_fecha_ini_act_prod" value="<?php echo FormatDateTime(@$x_fecha_ini_act_prod,7);?>"   maxlength="100" size="30"/>
      &nbsp;<img src="../images/ew_calendar.gif" id="cxfecha_ini_act_prod" onclick="javascript: Calendar.setup(
            {
            inputField : 'x_fecha_ini_act_prod', 
           ifFormat : '%d/%m/%Y', 
            button : 'cxfecha_ini_act_prod' 
            }
            );" style="cursor:pointer;cursor:hand;" /></span></td>
      </tr>
      <tr>
        <td class="texto_normal">Destino del credito</td>
        <td colspan="3"><?php
		$x_entidad_idList = "<select name=\"x_destino_credito_id\" id=\"x_destino_credito_id\" class=\"texto_normal\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `destino_credito_id`, `descripcion` FROM `destino_credito`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["destino_credito_id"] == @$x_destino_credito_id) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?></td>
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
        <td colspan="3"><input name="x_calle" type="text" class="texto_normal" id="x_calle" value="<?php echo htmlentities(@$x_calle) ?>" size="80" maxlength="150" /></td>
      </tr>
      <tr>
        <td width="165"><span class="texto_normal">No exterior  : </span></td>
        <td colspan="3"><input type="text" name="x_numero_exterior" id="x_numero_exterior" value="<?php echo ($x_numero_exterior);?>"  maxlength="20" size="20"/></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Colonia: </span></td>
        <td colspan="3"><input name="x_colonia" type="text" class="texto_normal" id="x_colonia" value="<?php echo htmlentities(@$x_colonia) ?>" size="80" maxlength="150" /></td>
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
        <div id="txtHint1" class="texto_normal">
        Del/Mun:
        <?php
		if($x_entidad_id > 0) {
		$x_delegacion_idList = "<select name=\"x_delegacion_id\" class=\"texto_normal\">";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where entidad_id = $x_entidad_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["delegacion_id"] == @$x_delegacion_id) {
					$x_delegacion_idList .= "' selected";
				}
				$x_delegacion_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_delegacion_idList .= "</select>";
		echo $x_delegacion_idList;
		}
		?>
        </div>
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
	    <td><span class="texto_normal">Referencia de Ubicaci贸n:</span></td>
	  <td colspan="4"><input name="x_ubicacion" type="text" class="texto_normal" id="x_ubicacion" value="<?php echo htmlentities(@$x_ubicacion) ?>" size="80" maxlength="250" /></td>
	  </tr>
	<tr>
	  <td class="texto_normal">Antiguedad en Domicilio: </td>
	  <td colspan="4"><span class="texto_normal">
	    <input name="x_antiguedad" type="text" class="texto_normal" id="x_antiguedad" onKeyPress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_antiguedad) ?>" size="2" maxlength="2"/>
(a&ntilde;os)</span></td> 
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
		<input class="texto_normal" type="text" name="x_propietario_renta" id="x_propietario_renta" value="<?php echo $x_propietario_renta; ?>" size="25" maxlength="150" />&nbsp;
		Renta:
		<input class="importe" name="x_gastos_renta_casa" type="text" id="x_gastos_renta_casa" value="<?php echo htmlspecialchars(@$x_gastos_renta_casa) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/>        
		</div>		
        
		<div id="prop1" class="<?php if($x_vivienda_tipo_id == 3){ echo "TG_visible";}else{ echo "TG_hidden";} ?>">
		Propietario de la Vivienda:&nbsp;
		<input class="texto_normal" type="text" name="x_propietario_familiar" id="x_propietario_familiar" value="<?php echo $x_propietario; ?>" size="50" maxlength="150" />
		</div>		

		<div id="prop1credito" class="<?php if($x_vivienda_tipo_id == 4){ echo "TG_visible";}else{ echo "TG_hidden";} ?>">
		Empresa:&nbsp;
		<input class="texto_normal" type="text" name="x_propietario_ch" id="x_propietario_ch" value="<?php echo $x_propietario_ch; ?>" size="25" maxlength="150" />&nbsp;
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
	  &nbsp;Compa&ntilde;ia&nbsp;<?php
		$x_entidad_idList = "<select name=\"x_compania_celular_id\" id=\"x_compania_celular_id\" class=\"texto_normal\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_id) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?></span></td>
	  </tr>
      <tr>
	  <td class="texto_normal">Tel. Celular: </td>
	  <td colspan="4"><span class="texto_normal">
	    <input name="x_telefono_secundario_2" type="text" class="texto_normal" id="x_telefono_secundario_2" value="<?php echo htmlspecialchars(@$x_telefono_secundario_2) ?>" size="20" maxlength="20" />
	  &nbsp;Compa&ntilde;ia&nbsp;<?php
		$x_entidad_idList = "<select name=\"x_compania_celular_id_2\" id=\"x_compania_celular_id_2\" class=\"texto_normal\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_id_2) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?></span></td>
	  </tr>
    </table></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Telefonos nueva seccion </td>
    </tr>
    
    <tr>
      <td colspan="11" id="tableHead"><table width="100%" border="0">
  <tr>
    <td colspan="4"  bgcolor="#CCFFCC">Telefonos fijos <?php if($contador_telefono < 1){$contador_telefono = 1;}?> 
      <input type="hidden" name="contador_telefono"  id="contador_telefono" value="<?php echo $contador_telefono ?>"/> </td>
    <td width="58%" colspan="6"  bgcolor="#87CEEB">Celulares<?php if($contador_celular < 1) {$contador_celular = 1;}?> <input type="hidden" name="contador_celular" id="contador_celular" value="<?php echo $contador_celular;?>" /></td>
    </tr> </table>
    <table width="100%" border="0">
  <tr>
    <td width="41%"><table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_1" type="text" value="<?php echo $x_telefono_casa_1 ?>" size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td><input name="x_comentario_casa_1" type="text" value="<?php echo $x_comentario_casa_1; ?>" size="30" maxlength="250" /></td>
      </tr>
    </table></td>
    <td width="59%"><table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_1" type="text" size="13" maxlength="250" value="<?php echo $x_telefono_celular_1 ?>" onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%">
		<?php
		$x_entidad_idList = "<select name=\"x_compania_celular_1\" id=\"x_compania_celular_1\" class=\"texto_normal\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_1) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?> 
		</td>
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_1" value="<?php echo $x_comentario_celular_1;?>"  size="30" maxlength="250" /></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td> <div id="x_telefono_casa_2">
    <?php if($x_telefono_casa_2){ ?>
    <table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_2" type="text" value="<?php echo $x_telefono_casa_2;?>" size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td><input name="x_comentario_casa_2'" type="text" value="<?php echo $x_comentario_casa_2;?>" size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?>  
    </div></td>
    <td><div id="telefono_celular_2">
    <?php if(!empty($x_telefono_celular_2)) {?>
    <table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_2"  value="<?php echo $x_telefono_celular_2; ?>" type="text" size="13" maxlength="250"  onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%"><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_2\" id=\"x_compania_celular_2\" class=\"texto_normal\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {

			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_2) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?> </td>
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_2"  value="<?php echo $x_comentario_celular_2;?>"size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?>    
    
    </div></td>
  </tr>
  <tr>
    <td> <div id="x_telefono_casa_3">
      <?php if($x_telefono_casa_3){ ?>
    <table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_3" type="text" value="<?php $x_telefono_casa_3;?>" size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td><input name="x_comentario_casa_3'" type="text" value="<?php x_comentario_casa_3;?>" size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?> 
    
    </div></td>
    <td><div id="telefono_celular_3">
    <?php if(!empty($x_telefono_celular_3)) {?>
    <table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_3"  value="<?php echo $x_telefono_celular_3; ?>"type="text" size="13" maxlength="250"  onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%"><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_3\" id=\"x_compania_celular_3\" class=\"texto_normal\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_3) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?> </td>
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_3"  value="<?php echo $x_comentario_celular_3;?>"size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?>    
    
    </div></td>
  </tr>
  <tr>
    <td> <div id="x_telefono_casa_4">
      <?php if($x_telefono_casa_4){ ?>
    <table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_4" type="text" value="<?php $x_telefono_casa_4;?>" size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td><input name="x_comentario_casa_4'" type="text" value="<?php x_comentario_casa_4;?>" size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?> 
    </div></td>
    <td><div id="telefono_celular_4">
    <?php if(!empty($x_telefono_celular_4)) {?>
    <table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_4"  value="<?php echo $x_telefono_celular_4; ?>"type="text" size="13" maxlength="250"  onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%"><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_4\" id=\"x_compania_celular_4\" class=\"texto_normal\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_4) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?> </td>
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_4"  value="<?php echo $x_comentario_celular_4;?>"size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?>    
    </div></td>
  </tr><tr>
    <td> <div id="x_telefono_casa_5">
      <?php if($x_telefono_casa_5){ ?>
    <table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_5" type="text" value="<?php $x_telefono_casa_5;?>" size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td><input name="x_comentario_casa_5'" type="text" value="<?php x_comentario_casa_5;?>" size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?> 
    </div></td>
    <td><div id="telefono_celular_5">
    <?php if(!empty($x_telefono_celular_5)) {?>
    <table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_5"  value="<?php echo $x_telefono_celular_5; ?>"type="text" size="13" maxlength="250"  onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%"><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_5\" id=\"x_compania_celular5\" class=\"texto_normal\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_5) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?> </td>
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_5"  value="<?php echo $x_comentario_celular_5;?>"size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?>    
    </div></td>
  </tr><tr>
    <td> <div id="x_telefono_casa_6">
      <?php if($x_telefono_casa_6){ ?>
    <table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_6" type="text" value="<?php $x_telefono_casa_6;?>" size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td><input name="x_comentario_casa_6'" type="text" value="<?php x_comentario_casa_6;?>" size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?> 
    </div></td>
    <td><div id="telefono_celular_6">
    <?php if(!empty($x_telefono_celular_6)) {?>
    <table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_6"  value="<?php echo $x_telefono_celular_6; ?>"type="text" size="13" maxlength="250"  onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%"><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_6\" id=\"x_compania_celular_6\" class=\"texto_normal\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_6) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?> </td>
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_6"  value="<?php echo $x_comentario_celular_6;?>"size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?>    
    </div></td>
  </tr><tr>
    <td> <div id="x_telefono_casa_7">
      <?php if($x_telefono_casa_7){ ?>
    <table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_7" type="text" value="<?php $x_telefono_casa_7;?>" size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td><input name="x_comentario_casa_7'" type="text" value="<?php x_comentario_casa_7;?>" size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?> 
    </div></td>
    <td><div id="telefono_celular_7">
    <?php if(!empty($x_telefono_celular_7)) {?>
    <table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_7"  value="<?php echo $x_telefono_celular_7; ?>"type="text" size="13" maxlength="250"  onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%"><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_7\" id=\"x_compania_celular_7\" class=\"texto_normal\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_7) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?> </td>
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_7"  value="<?php echo $x_comentario_celular_7;?>"size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?>    
    </div></td>
  </tr><tr>
    <td> <div id="x_telefono_casa_8">
      <?php if($x_telefono_casa_8){ ?>
    <table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_8" type="text" value="<?php $x_telefono_casa_8;?>" size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td><input name="x_comentario_casa_8'" type="text" value="<?php x_comentario_casa_8;?>" size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?> 
    </div></td>
    <td><div id="telefono_celular_8">
    <?php if(!empty($x_telefono_celular_8)) {?>
    <table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_8"  value="<?php echo $x_telefono_celular_8; ?>"type="text" size="13" maxlength="250"  onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%"><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_8\" id=\"x_compania_celular_8\" class=\"texto_normal\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_8) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?> </td>
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_8"  value="<?php echo $x_comentario_celular_8;?>"size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?>    
    </div></td>
  </tr><tr>
    <td> <div id="x_telefono_casa_9">
      <?php if($x_telefono_casa_9){ ?>
    <table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_9" type="text" value="<?php $x_telefono_casa_9;?>" size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td><input name="x_comentario_casa_9'" type="text" value="<?php x_comentario_casa_9;?>" size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?> 
    </div></td>
    <td><div id="telefono_celular_9">
    <?php if(!empty($x_telefono_celular_9)) {?>
    <table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_9"  value="<?php echo $x_telefono_celular_9; ?>"type="text" size="13" maxlength="250"  onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%"><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_9\" id=\"x_compania_celular_9\" class=\"texto_normal\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_9) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?> </td>
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_9"  value="<?php echo $x_comentario_celular_9;?>"size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?>    
    </div></td>
  </tr><tr>
    <td> <div id="x_telefono_casa_10">
      <?php if($x_telefono_casa_10){ ?>
    <table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_10" type="text" value="<?php $x_telefono_casa_10;?>" size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td><input name="x_comentario_casa_10'" type="text" value="<?php x_comentario_casa_10;?>" size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?> 
    </div></td>
    <td><div id="telefono_celular_10">
    <?php if(!empty($x_telefono_celular_10)) {?>
    <table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_10"  value="<?php echo $x_telefono_celular_10; ?>"type="text" size="13" maxlength="250"  onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%"><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_10\" id=\"x_compania_celular_10\" class=\"texto_normal\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_10) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?> </td>
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_10"  value="<?php echo $x_comentario_celular_10;?>"size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?>    
    </div></td>
  </tr><tr>
    <td> <div id="x_telefono_casa_11">
    </div></td>
    <td><div id="telefono_celular_11">
    </div></td>
  </tr>
  
  <tr>
    <td align="right"><input type="button" value="Agregar telefono casa" onClick="actualizaReferencia(1);" /></td>
    <td align="right"><input type="button" value="Agregar celular" onClick="actualizaReferencia(2);" /></td>
  </tr>
</table>

</td>
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
        聽<span class="texto_normal">Mismos que el Dom. Part.</span>		</div>		</td>
        </tr>	
      <tr>
        <td><span class="texto_normal">Empresa: </span></td>
        <td colspan="3"><input name="x_empresa" type="text" class="texto_normal" id="x_empresa" value="<?php echo htmlentities(@$x_empresa) ?>" size="80" maxlength="250" /></td>
      </tr>
      <tr>
        <td class="texto_normal">Giro Negocio</td>
        <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="41%"><?php
		$x_entidad_idList = "<select name=\"x_giro_negocio_id\" id=\"x_giro_negocio_id\" class=\"texto_normal\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `giro_negocio_id`, `descripcion` FROM `giro_negocio`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["giro_negocio_id"] == @$x_giro_negocio_id) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?></td>
            <td width="19%">Tipo Negocio</td>
            <td width="40%"><?php
		$x_entidad_idList = "<select name=\"x_tipo_inmueble_id\" id=\"x_tipo_inmueble_id\" class=\"texto_normal\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `tipo_inmueble_id`, `descripcion` FROM `tipo_inmueble`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["tipo_inmueble_id"] == @$x_tipo_inmueble_id) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td class="texto_normal">Atiende Titular</td>
        <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="41%"><select name="x_atiende_titular">
      <option>Seleccione</option>
		<option value="si" <?php if($x_atiende_titular == "si"){?> selected="selected" <?php } ?>>Si</option>
        <option value="no" <?php if($x_atiende_titular == "no"){?> selected="selected" <?php } ?>>No</option>
      </select></td>
            <td width="19%">No.Personas trabajando</td>
            <td width="40%"><input type="text" name="x_personas_trabajando" value="<?php echo($x_personas_trabajando);?>" maxlength="20" size="10" /></td>
          </tr>
        </table></td>
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
             &nbsp;<img src="../../../images/ew_calendar.gif" id="cx_fecha_contratacion" onmouseover="javascript: Calendar.setup(
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
        <td><span class="texto_normal">Puesto: </span></td>
        <td colspan="3"><input name="x_puesto" type="text" class="texto_normal" id="x_puesto" value="<?php echo htmlentities(@$x_puesto) ?>" size="80" maxlength="250" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Fecha Contratacion:</span></td>
        <td colspan="3">
		<span class="texto_normal">
        <input name="x_fecha_contratacion" type="text" id="x_fecha_contratacion" value="<?php echo FormatDateTime(@$x_fecha_contratacion,7); ?>" size="12" maxlength="12">
            &nbsp;<img src="../images/ew_calendar.gif" id="cx_fecha_contratacion" alt="Calendario" style="cursor:pointer;cursor:hand;">
            <script type="text/javascript">
            Calendar.setup(
            {
            inputField : "x_fecha_contratacion", // ID of the input field
            ifFormat : "%d/%m/%Y", // the date format  
            button : "cx_fecha_contratacion" // ID of the button
            }
            );
            </script>
            </span>         
        </td>
      </tr>
      <tr>
        <td><span class="texto_normal">Salario Mensual: </span></td>
        <td colspan="3"><input class="importe" name="x_salario_mensual" type="text" id="x_salario_mensual" value="<?php echo htmlspecialchars(@$x_salario_mensual) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"  /></td>
      </tr>
      <tr>
        <td width="165"><span class="texto_normal">Calle no. Ext e Int. : </span></td>
        <td colspan="3"><input name="x_calle2" type="text" class="texto_normal" id="x_calle2" value="<?php echo htmlentities(@$x_calle2) ?>" size="80" maxlength="150" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Colonia: </span></td>
        <td colspan="3"><input name="x_colonia2" type="text" class="texto_normal" id="x_colonia2" value="<?php echo htmlentities(@$x_colonia2) ?>" size="80" maxlength="150" /></td>
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
        <div id="txtHint2" class="texto_normal">        
		Del/Mun:        
        <?php
		if($x_entidad_id2 > 0 ){
		$x_delegacion_idList = "<select name=\"x_delegacion_id2\" class=\"texto_normal\">";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where entidad_id = $x_entidad_id2";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["delegacion_id"] == @$x_delegacion_id2) {
					$x_delegacion_idList .= "' selected";
				}
				$x_delegacion_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_delegacion_idList .= "</select>";
		echo $x_delegacion_idList;
		}
		?>
        </div>
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
        <td><span class="texto_normal">Referencia de Ubicaci贸n:</span></td>
        <td colspan="4"><input name="x_ubicacion2" type="text" class="texto_normal" id="x_ubicacion2" value="<?php echo htmlentities(@$x_ubicacion2) ?>" size="80" maxlength="250" /></td>
      </tr>
      <tr>
        <td class="texto_normal">Antiguedad en Domicilio: </td>
        <td colspan="4"><span class="texto_normal">
          <input name="x_antiguedad2" type="text" class="texto_normal" id="x_antiguedad2" onKeyPress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_antiguedad2) ?>" size="2" maxlength="2"/>
        (a&ntilde;os)</span></td>
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
    <td colspan="3"><!--	</div>	-->	</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
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
        <td width="240"><span class="texto_normal">Ingresos del Negocio: </span></td>
        <td width="122"><input class="importe" name="x_ingresos_negocio" type="text" id="x_ingresos_negocio" value="<?php echo htmlspecialchars(@$x_ingresos_negocio) ?>" size="10" maxlength="10" onKeyPress="return solonumeros(this,event)"/></td>
        <td width="134" class="texto_normal">Venta Mensual</td>
        <td width="204">&nbsp;</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Otros Ingresos: </span></td>
        <td><input class="importe" name="x_otros_ingresos" type="text" id="x_otros_ingresos" value="<?php echo htmlspecialchars(@$x_otros_ingresos) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)" /></td>
        <td class="texto_normal">Origen:</td>
        <td><input class="texto_normal" name="x_origen_ingresos" type="text" id="x_origen_ingresos" value="<?php echo htmlentities(@$x_origen_ingresos) ?>" size="30" maxlength="150" /></td>
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
          <input class="texto_normal" name="x_origen_ingresos2" type="text" id="x_origen_ingresos2" value="<?php echo htmlentities(@$x_origen_ingresos2) ?>" size="30" maxlength="150" />
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
          <input class="texto_normal" name="x_origen_ingresos3" type="text" id="x_origen_ingresos3" value="<?php echo htmlentities(@$x_origen_ingresos3) ?>" size="30" maxlength="150" />
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
    <td colspan="3"><table width="700" border="0" cellspacing="0" cellpadding="0">
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
        <td><input class="importe" name="x_gastos_renta_negocio" type="text" id="x_gastos_renta_negocio" value="<?php echo htmlspecialchars(@$x_gastos_renta_negocio) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  
  <tr>
    <td colspan="3" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Referencias</td>
    </tr>
  <tr>
    <td colspan="3" class="texto_normal">Indique por lo menos una referencia de trabajo (Cliente &oacute; Proveedor) </td>
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
        <td width="84" class="texto_normal">Tel&eacute;fono</td>
        <td width="163" class="texto_normal">Parentesco</td>
        </tr>
      <tr>
        <td><input name="x_nombre_completo_ref_1" type="text" class="texto_normal" id="x_nombre_completo_ref_1" value="<?php echo htmlentities(@$x_nombre_completo_ref_1) ?>" size="50" maxlength="250" /></td>
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
        <td><input name="x_nombre_completo_ref_3" type="text" class="texto_normal" id="x_nombre_completo_ref_3" value="<?php echo htmlentities(@$x_nombre_completo_ref_3) ?>" size="50" maxlength="250" /></td>
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
        <td><input name="x_nombre_completo_ref_4" type="text" class="texto_normal" id="x_nombre_completo_ref_4" value="<?php echo htmlentities(@$x_nombre_completo_ref_4) ?>" size="50" maxlength="250" /></td>
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
        <td><input name="x_nombre_completo_ref_5" type="text" class="texto_normal" id="x_nombre_completo_ref_5" value="<?php echo htmlentities(@$x_nombre_completo_ref_5) ?>" size="50" maxlength="250" /></td>
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
	    <textarea name="x_comentario_promotor" cols="60" rows="5"><?php echo $x_comentario_promotor; ?></textarea>
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
	    <textarea name="x_comentario_comite" cols="60" rows="5"><?php echo $x_comentario_comite; ?></textarea>
	      </div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

    <td colspan="3" align="left" valign="top" class="texto_normal">
	
	</td>
    </tr>
   
    <tr>
      <td colspan="3" align="left" valign="top">&nbsp;</td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div align="center">
	<input name="Action" type="button"class="boton_medium" value="Editar" <?php if(!empty($GLOBALS["x_editar"])){?>disabled="disabled"<?php }?> onclick="EW_checkMyForm();" />
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

function LoadData($conn)
{
	global $x_solicitud_id;
	global $x_cliente_id;
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
		//$GLOBALS["x_comentario_promotor"] = $row["comentario_promotor"];
		//$GLOBALS["x_comentario_comite"] = $row["comentario_comite"];
		$GLOBALS["x_actividad_id"] = $row["actividad_id"];
		$GLOBALS["x_actividad_desc"] = $row["actividad_desc"];				



//CLIENTE

		$sSql = "select cliente.* from cliente WHERE cliente_id = $x_cliente_id";
		$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row2 = phpmkr_fetch_array($rs2);
		$GLOBALS["x_cliente_id"] = $row2["cliente_id"];
		$GLOBALS["x_usuario_id"] = $row2["usuario_id"];
		$GLOBALS["x_nombre_completo"] = $row2["nombre_completo"];
		$GLOBALS["x_apellido_paterno"] = $row2["apellido_paterno"];		
		$GLOBALS["x_apellido_materno"] = $row2["apellido_materno"];				
		$GLOBALS["x_tit_rfc"] = $row2["rfc"];
		$GLOBALS["x_tit_curp"] = $row2["curp"];						
		$GLOBALS["x_tit_fecha_nac"] = $row2["fecha_nac"];					
		
		$GLOBALS["x_tipo_negocio"] = $row2["tipo_negocio"];
		$GLOBALS["x_edad"] = $row2["edad"];
		$GLOBALS["x_sexo"] = $row2["sexo"];
		$GLOBALS["x_estado_civil_id"] = $row2["estado_civil_id"];
		$GLOBALS["x_numero_hijos"] = $row2["numero_hijos"];
		$GLOBALS["x_numero_hijos_dep"] = $row2["numero_hijos_dep"];		
		$GLOBALS["x_nombre_conyuge"] = $row2["nombre_conyuge"];
		$GLOBALS["x_email"] = $row2["email"];		
		$GLOBALS["x_nacionalidad_id"] = $row2["nacionalidad_id"];				
		$GLOBALS["x_empresa"] = $row2["empresa"];		
		$GLOBALS["x_puesto"] = $row2["puesto"];		
		$GLOBALS["x_fecha_contratacion"] = $row2["fecha_contratacion"];		
		$GLOBALS["x_salario_mensual"] = $row2["salario_mensual"];
		$GLOBALS["x_comentario_promotor"] = $row2["comentario_promotor"];
		$GLOBALS["x_comentario_comite"] = $row2["comentario_comite"];
		
		$GLOBALS["x_rol_hogar_id"] = $row2["rol_hogar_id"];
		$GLOBALS["x_fecha_ini_act_prod"] = $row2["fecha_ini_act_prod"];



				
		$sSql = "select * from direccion  where cliente_id = ".$x_cliente_id." and direccion_tipo_id = 1 order by direccion_id desc limit 1";
		$rs3 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row3 = phpmkr_fetch_array($rs3);
		$GLOBALS["x_direccion_id"] = $row3["direccion_id"];
		$GLOBALS["x_calle"] = $row3["calle"];
		$GLOBALS["x_colonia"] = $row3["colonia"];
		$GLOBALS["x_delegacion_id"] = $row3["delegacion_id"];
		$GLOBALS["x_propietario"] = $row3["propietario"];
		$GLOBALS["x_entidad_id"] = $row3["entidad"];
		$GLOBALS["x_codigo_postal"] = $row3["codigo_postal"];
		$GLOBALS["x_ubicacion"] = $row3["ubicacion"];
		$GLOBALS["x_antiguedad"] = $row3["antiguedad"];
		$GLOBALS["x_vivienda_tipo_id"] = $row3["vivienda_tipo_id"];
		$GLOBALS["x_otro_tipo_vivienda"] = $row3["otro_tipo_vivienda"];
		$GLOBALS["x_telefono"] = $row3["telefono"];
		$GLOBALS["x_telefono_sec"] = $row3["telefono_movil"];					
		$GLOBALS["x_telefono_secundario"] = $row3["telefono_secundario"];
		
		$GLOBALS["x_numero_exterior"] = $row3["numero_exterior"];
		$GLOBALS["x_compania_celular_id"] = $row3["compania_celular_id"];
		
		$GLOBALS["x_telefono_secundario_2"] = $row3["telefono_movil_2"];
		$GLOBALS["x_compania_celular_id_2"] = $row3["compania_celular_id_2"];


		$sSql = "select * from direccion  where cliente_id = ".$x_cliente_id." and direccion_tipo_id = 2 order by direccion_id desc limit 1";
		$rs4 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row4 = phpmkr_fetch_array($rs4);
		$GLOBALS["x_direccion_id2"] = $row4["direccion_id"];
		$GLOBALS["x_calle2"] = $row4["calle"];
		$GLOBALS["x_colonia2"] = $row4["colonia"];
		$GLOBALS["x_delegacion_id2"] = $row4["delegacion_id"];
//		$GLOBALS["x_otra_delegacion2"] = $row4["otra_delegacion"];
		$GLOBALS["x_entidad_id2"] = $row4["entidad"];
		$GLOBALS["x_codigo_postal2"] = $row4["codigo_postal"];
		$GLOBALS["x_ubicacion2"] = $row4["ubicacion"];
		$GLOBALS["x_antiguedad2"] = $row4["antiguedad"];

		$GLOBALS["x_otro_tipo_vivienda2"] = $row4["otro_tipo_vivienda"];
		$GLOBALS["x_telefono2"] = $row4["telefono"];
		$GLOBALS["x_telefono_secundario2"] = $row4["telefono_secundario"];


		$sSql = "select * from negocio where cliente_id = $x_cliente_id";
		$rsn5 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$rown5 = phpmkr_fetch_array($rsn5);
		$GLOBALS["x_negocio_id"] = $rown5["negocio_id"];
		$GLOBALS["x_giro_negocio_id"] = $rown5["giro_negocio_id"];
		$GLOBALS["x_tipo_inmueble_id"] = $rown5["tipo_inmueble_id"];
		$GLOBALS["x_atiende_titular"] = $rown5["atiende_titular"];
		$GLOBALS["x_personas_trabajando"] = $rown5["personas_trabajando"];
		$GLOBALS["x_destino_credito_id"] = $rown5["destino_credito_id"];
		
		
		

		$sSql = "select * from ingreso_2 where  cliente_id = $x_cliente_id";
		$rs8 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row8 = phpmkr_fetch_array($rs8);
		$GLOBALS["x_ingreso_id"] = $row8["ingreso_id"];
		$GLOBALS["x_ingresos_negocio"] = $row8["ingresos_negocio"];
		$GLOBALS["x_ingresos_familiar_1"] = $row8["ingresos_familiar_1"];
		$GLOBALS["x_parentesco_tipo_id_ing_1"] = $row8["parentesco_tipo_id"];
		$GLOBALS["x_ingresos_familiar_2"] = $row8["ingresos_familiar_2"];
		$GLOBALS["x_parentesco_tipo_id_ing_2"] = $row8["parentesco_tipo_id2"];
		$GLOBALS["x_otros_ingresos"] = $row8["otros_ingresos"];
		$GLOBALS["x_origen_ingresos"] = $row8["origen_ingresos"];		
		$GLOBALS["x_origen_ingresos2"] = $row8["origen_ingresos_fam_1"];				
		$GLOBALS["x_origen_ingresos3"] = $row8["origen_ingresos_fam_2"];												


		$sSql = "select * from gasto_2 where  cliente_id = $x_cliente_id";
		$rs12 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row12 = phpmkr_fetch_array($rs12);
		$GLOBALS["x_gasto_id"] = $row12["gasto_id"];
		$GLOBALS["x_gastos_prov1"] = $row12["gastos_prov1"];
		$GLOBALS["x_gastos_prov2"] = $row12["gastos_prov2"];
		$GLOBALS["x_gastos_prov3"] = $row12["gastos_prov3"];
		$GLOBALS["x_otro_prov"] = $row12["otro_prov"];			
		$GLOBALS["x_gastos_empleados"] = $row12["gastos_empleados"];					
		$GLOBALS["x_gastos_renta_negocio"] = $row12["gastos_renta_negocio"];
		$GLOBALS["x_gastos_renta_casa"] = $row12["gastos_renta_casa"];
		$GLOBALS["x_gastos_credito_hipotecario"] = $row12["gastos_credito_hipotecario"];
		$GLOBALS["x_gastos_otros"] = $row12["gastos_otros"];		


		if(!empty($GLOBALS["x_propietario"])){
			if(!empty($GLOBALS["x_gastos_renta_casa"])){
				$GLOBALS["x_propietario_renta"] = $GLOBALS["x_propietario"];
				$GLOBALS["x_propietario"] = "";				
			}else{
				if(!empty($GLOBALS["x_gastos_credito_hipotecario"])){
					$GLOBALS["x_propietario_ch"] = $GLOBALS["x_propietario"];
					$GLOBALS["x_propietario"] = "";
				}
			}
		}





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
		$sSql = "select * from referencia_2 where solicitud_id = ".$GLOBALS["x_solicitud_id"]." AND cliente_id = $x_cliente_id order by referencia_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9 = phpmkr_fetch_array($rs9)){
			$GLOBALS["x_referencia_id_$x_count"] = $row9["referencia_id"];
			$GLOBALS["x_nombre_completo_ref_$x_count"] = $row9["nombre_completo"];
			$GLOBALS["x_telefono_ref_$x_count"] = $row9["telefono"];
			$GLOBALS["x_parentesco_tipo_id_ref_$x_count"] = $row9["parentesco_tipo_id"];
			$x_count++;
		}
		
		
		
		$x_count_2 = 1;
		$sSql = "select * from  telefono where cliente_id = ".$GLOBALS["x_cliente_id"]."  AND telefono_tipo_id = 1 order by telefono_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9 = phpmkr_fetch_array($rs9)){			
			$GLOBALS["x_telefono_casa_$x_count_2"] = $row9["numero"];
			$GLOBALS["x_comentario_casa_$x_count_2"] = $row9["comentario"];
			$GLOBALS["contador_telefono"] = $x_count_2;
			$x_count_2++;
			
		}
		
		$x_count_3 = 1;
		$sSql = "select * from  telefono where cliente_id = ".$GLOBALS["x_cliente_id"]."  AND telefono_tipo_id = 2 order by telefono_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9e = phpmkr_fetch_array($rs9)){			
			$GLOBALS["x_telefono_celular_$x_count_3"] = $row9e["numero"];
			$GLOBALS["x_comentario_celular_$x_count_3"] = $row9e["comentario"];
			$GLOBALS["x_compania_celular_$x_count_3"] = $row9e["compania_id"];	
			$GLOBALS["contador_celular"] = $x_count_3;
			$x_count_3++;
			
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



//checklist
		$x_count = 1;
		$sSql = "select * from solicitud_checklist where solicitud_id = ".$GLOBALS["x_solicitud_id"]." order by solicitud_checklist_id";
		$rs11 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row11 = phpmkr_fetch_array($rs11)){
			$GLOBALS["x_checklist_$x_count"] = $row11["valor"];
			$GLOBALS["x_det_ck$x_count"] = $row11["detalle"];
			$x_count++;
		}





	}
	phpmkr_free_result($rs);
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
	phpmkr_free_result($rs11);										

	return $bLoadData;
}
?>

<?php

//-------------------------------------------------------------------------------
// Function EditData
// - Edit Data based on Key Value sKey
// - Variables used: field variables


function EditData($conn){
	
	phpmkr_query('START TRANSACTION;', $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: BEGIN TRAN');
	//echo "entro a la funcion";
	
	global $x_solicitud_id;
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
		$bEditData = false; // Update Failed
	}else{
	
	
		
	
	
	/*//GRUPO
	$fieldList = NULL;
	
	$x_ct_int = 1;
	
	while($x_ct_int <=10){
		$x_id_cliente = "x_id_cliente"
		
		$x_ct_int
		}*/
	
	//G
	//CLIENTE

		$fieldList = NULL;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_completo"]) : $GLOBALS["x_nombre_completo"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`nombre_completo`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_paterno"]) : $GLOBALS["x_apellido_paterno"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`apellido_paterno`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_materno"]) : $GLOBALS["x_apellido_materno"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`apellido_materno`"] = $theValue;

		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tit_rfc"]) : $GLOBALS["x_tit_rfc"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`rfc`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tit_curp"]) : $GLOBALS["x_tit_curp"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`curp`"] = $theValue;


		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tipo_negocio"]) : $GLOBALS["x_tipo_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`tipo_negocio`"] = $theValue;
		
		$theValue = ($GLOBALS["x_edad"] != "") ? intval($GLOBALS["x_edad"]) : "0";
		$fieldList["`edad`"] = $theValue;

		$theValue = ($GLOBALS["x_tit_fecha_nac"] != "") ? "'" . ConvertDateToMysqlFormat($GLOBALS["x_tit_fecha_nac"]) . "'" : "Null";
		$fieldList["`fecha_nac`"] = $theValue;
		
		$theValue = ($GLOBALS["x_sexo"] != "") ? intval($GLOBALS["x_sexo"]) : "0";
		$fieldList["`sexo`"] = $theValue;
		$theValue = ($GLOBALS["x_estado_civil_id"] != "") ? intval($GLOBALS["x_estado_civil_id"]) : "0";
		$fieldList["`estado_civil_id`"] = $theValue;
		$theValue = ($GLOBALS["x_numero_hijos"] != "") ? intval($GLOBALS["x_numero_hijos"]) : "0";
		$fieldList["`numero_hijos`"] = $theValue;
		$theValue = ($GLOBALS["x_numero_hijos_dep"] != "") ? intval($GLOBALS["x_numero_hijos_dep"]) : "0";
		$fieldList["`numero_hijos_dep`"] = $theValue;		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_conyuge"]) : $GLOBALS["x_nombre_conyuge"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`nombre_conyuge`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_email"]) : $GLOBALS["x_email"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`email`"] = $theValue;

		$theValue = ($GLOBALS["x_nacionalidad_id"] != "") ? intval($GLOBALS["x_nacionalidad_id"]) : "0";
		$fieldList["`nacionalidad_id`"] = $theValue;


		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_empresa"]) : $GLOBALS["x_empresa"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`empresa`"] = $theValue;
	
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_puesto"]) : $GLOBALS["x_puesto"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`puesto`"] = $theValue;
	
		// Field fecha_registro
		$theValue = ($GLOBALS["x_fecha_contratacion"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_contratacion"]) . "'" : "Null";
		$fieldList["`fecha_contratacion`"] = $theValue;
	//echo "fecha contratacion".$theValue."-"; 
		$theValue = ($GLOBALS["x_salario_mensual"] != "") ? " '" . doubleval($GLOBALS["x_salario_mensual"]) . "'" : "NULL";
		$fieldList["`salario_mensual`"] = $theValue;
		
		
		//coiemntarios se garegaron por requerimiento
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_comentario_promotor"]) : $GLOBALS["x_comentario_promotor"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`comentario_promotor`"] = $theValue;
	
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_comentario_comite"]) : $GLOBALS["x_comentario_comite"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`comentario_comite`"] = $theValue;
		
		
		$theValue = ($GLOBALS["x_rol_hogar_id"] != "") ? intval($GLOBALS["x_rol_hogar_id"]) : "NULL";
		$fieldList["`rol_hogar_id`"] = $theValue;
		// Field fecha_inicio_act
		$theValue = ($GLOBALS["x_fecha_ini_act_prod"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_ini_act_prod"]) . "'" : "Null";
		$fieldList["`fecha_ini_act_prod`"] = $theValue;
		

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
	
//	echo "$x_result decliente = ".$x_result."-";
	
	if($GLOBALS["x_negocio_id"] > 0){
			//echo "negocio id" . $GLOBALS["x_negocio_id"]."-";
			
			//se modifica el registro
			$fieldList = NULL;
			
				$theValue = ($GLOBALS["x_giro_negocio_id"] != "") ? intval($GLOBALS["x_giro_negocio_id"]) : "0";
				$fieldList["`giro_negocio_id`"] = $theValue;
				
					// Field antiguedad
				$theValue = ($GLOBALS["x_tipo_inmueble_id"] != "") ? intval($GLOBALS["x_tipo_inmueble_id"]) : "0";
				$fieldList["`tipo_inmueble_id`"] = $theValue;
				
					// Field antiguedad
				$theValue = ($GLOBALS["x_personas_trabajando"] != "") ? intval($GLOBALS["x_personas_trabajando"]) : "0";
				$fieldList["`personas_trabajando`"] = $theValue;
			
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_atiende_titular"]) : $GLOBALS["x_atiende_titular"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`atiende_titular`"] = $theValue;
			
				// Field antiguedad
				$theValue = ($GLOBALS["x_destino_credito_id"] != "") ? intval($GLOBALS["x_destino_credito_id"]) : "0";
				$fieldList["`destino_credito_id`"] = $theValue;
			
			
					// update
					$sSql = "UPDATE `negocio` SET ";
					foreach ($fieldList as $key=>$temp) {
						$sSql .= "$key = $temp, ";
					}
					if (substr($sSql, -2) == ", ") {
						$sSql = substr($sSql, 0, strlen($sSql)-2);
					}
					$sSql .= " WHERE negocio_id = ". $GLOBALS["x_negocio_id"]."";
					//$x_result = "";
					$x_result = phpmkr_query($sSql,$conn) or die("ERROR...".phpmkr_error()."sql direccion dos".$sSql);
					 //echo "x_result de negocio =".$x_result."";
					//exit();
					if(!$x_result){
						echo phpmkr_error() . '<br>SQL cliente neg: ' . $sSql;
						phpmkr_query('rollback;', $conn);	 
						exit();
					}
			
			}else{
				// no existia el registro asi que se inserta
				
				//NEGOCIO
				$fieldList = NULL;
				$fieldList["`cliente_id`"] = $GLOBALS["x_cliente_id"];
					// Field antiguedad
				$theValue = ($GLOBALS["x_giro_negocio_id"] != "") ? intval($GLOBALS["x_giro_negocio_id"]) : "0";
				$fieldList["`giro_negocio_id`"] = $theValue;
				
					// Field antiguedad
				$theValue = ($GLOBALS["x_tipo_inmueble_id"] != "") ? intval($GLOBALS["x_tipo_inmueble_id"]) : "0";
				$fieldList["`tipo_inmueble_id`"] = $theValue;
				
					// Field antiguedad
				$theValue = ($GLOBALS["x_personas_trabajando"] != "") ? intval($GLOBALS["x_personas_trabajando"]) : "0";
				$fieldList["`personas_trabajando`"] = $theValue;
			
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_atiende_titular"]) : $GLOBALS["x_atiende_titular"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`atiende_titular`"] = $theValue;
			
				// Field antiguedad
				$theValue = ($GLOBALS["x_destino_credito_id"] != "") ? intval($GLOBALS["x_destino_credito_id"]) : "0";
				$fieldList["`destino_credito_id`"] = $theValue;
			
			
				// insert into database
				$sSql = "INSERT INTO `negocio` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";


				$x_result = phpmkr_query($sSql, $conn);
				//$x_id_nuevo = mysql_insert_id();
				//echo "x_result de insert". $x_result."--"."id_insertado-".$x_id_nuevo ."-";
				//echo "query".$sSql;
				
				//die();
				//exit();
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
						
				
				}// fin negocio
	
	
		//update el nombre del cliente  en la tabla credit solidario
		/*$sqlSolicitudes = "select grupo_id FROM  solicitud_grupo  WHERE". $sWhere;
		$resposeGrupo = phpmkr_query($sqlSolicitudes,$conn)or die("error en grupo".phpmkr_error()."sql:".$sqlSolicitudes);
		$rowgp = phpmkr_fetch_array($resposeGrupo);
		$x_grupo_id = $rowgp["grupo_id"];
		phpmkr_free_result($rowgp);
		
		
		$sqlCreditoSol = "SELECT * FROM creditosolidario where creditoSolidario_id  = $x_grupo_id ";
		$responseGrp = phpmkr_query($sqlCreditoSol,$conn)or die("error selec datos grupo".phpmkr_error()."sql".$sqlCreditoSol);
		$rowDatosG = phpmkr_fetch_array($responseGrp);
		
		
		$x_nombre_completo = $GLOBALS["x_nombre_completo"]." ".$GLOBALS["x_apellido_paterno"]." ".$GLOBALS["x_apellido_materno"];
		echo  "nombre".$x_nombre_completo;
		exit();
		$x_cont_cliente_id = 1;
		while($x_cont_cliente_id <= 10){
			$x_c_id = "cliente_id_$x_cont_cliente_id";
			$x_c_id = $$x_c_id;
			echo $x_c_id;
			$x_nombre_c = "integrante_$x_cont_cliente_id";
			$x_nombre_c = $$x_nombre_c ;
			echo  $x_nombre_c ;
			
		$GLOBALS["X_CLIENTE_ID_$x_cont_cliente_id"] =  $rowDatosG["$x_c_id"];
		
			
		if($GLOBALS["X_CLIENTE_ID_$x_cont_cliente_id"] == $GLOBALS["x_cliente_id"] ){
			echo"encuantra el id".$GLOBALS["x_cliente_id"]." ";
			// si el cliente id de la edicion es igual al cliente id del ciclo			
			// se edita el nombre del integrante en esa posicion			
			$sqlEditNombre = "UPDATE creditosolidario SET $x_nombre_c = \" $x_nombre_completo \" WHERE creditoSolidario_id  = $x_grupo_id ";
			$RESPOSEup = phpmkr_query($sqlEditNombre,$conn)or die("error en update nombre".phpmkr_error()."sql".$sqlEditNombre);
			
			}			
			
			$x_cont_cliente_id++;
			}
	phpmkr_free_result($rowDatosG);*/
	//exit();
	//DIR PAR


		if($GLOBALS["x_direccion_id"] > 0 ){

			$fieldList = NULL;
	
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle"]) : $GLOBALS["x_calle"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`calle`"] = $theValue;
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia"]) : $GLOBALS["x_colonia"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`colonia`"] = $theValue;
			$theValue = ($GLOBALS["x_delegacion_id"] != "") ? intval($GLOBALS["x_delegacion_id"]) : "0";
			$fieldList["`delegacion_id`"] = $theValue;
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_propietario"]) : $GLOBALS["x_propietario"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`propietario`"] = $theValue;

			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_codigo_postal"]) : $GLOBALS["x_codigo_postal"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`codigo_postal`"] = $theValue;
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion"]) : $GLOBALS["x_ubicacion"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`ubicacion`"] = $theValue;
			$theValue = ($GLOBALS["x_antiguedad"] != "") ? intval($GLOBALS["x_antiguedad"]) : "0";
			$fieldList["`antiguedad`"] = $theValue;
			$theValue = ($GLOBALS["x_vivienda_tipo_id"] != "") ? intval($GLOBALS["x_vivienda_tipo_id"]) : "0";
			$fieldList["`vivienda_tipo_id`"] = $theValue;
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otro_tipo_vivienda"]) : $GLOBALS["x_otro_tipo_vivienda"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`otro_tipo_vivienda`"] = $theValue;
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono"]) : $GLOBALS["x_telefono"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`telefono`"] = $theValue;


			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_sec"]) : $GLOBALS["x_telefono_sec"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`telefono_movil`"] = $theValue;
			
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_secundario"]) : $GLOBALS["x_telefono_secundario"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`telefono_secundario`"] = $theValue;
			
			
			// Field vivienda_tipo_id
			$theValue = ($GLOBALS["x_compania_celular_id"] != "") ? intval($GLOBALS["x_compania_celular_id"]) : "0";
			$fieldList["`compania_celular_id`"] = $theValue;
		
			$theValue = ($GLOBALS["x_numero_exterior"] != "") ? intval($GLOBALS["x_numero_exterior"]) : "0";
			$fieldList["`numero_exterior`"] = $theValue;
			
			
			
			
			 // Field telefono_secundario
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_secundario_2"]) :$GLOBALS["x_telefono_secundario_2"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`telefono_movil_2`"] = $theValue;
				 
			$theValue = ($GLOBALS["x_compania_celular_id_2"] != "") ? intval($GLOBALS["x_compania_celular_id_2"]) : "0";
			$fieldList["`compania_celular_id_2`"] = $theValue;
	
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
				echo phpmkr_error() . '<br>SQL cliente par: ' . $sSql;
				phpmkr_query('rollback;', $conn);	 
				exit();
			}

		}else{

			if($GLOBALS["x_calle"] != ""){

				$fieldList = NULL;
				// Field cliente_id
			//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
				$fieldList["`cliente_id`"] = $GLOBALS["x_cliente_id"];
			
				// Field aval_id
			//	$theValue = ($GLOBALS["x_aval_id"] != "") ? intval($GLOBALS["x_aval_id"]) : "NULL";
				$fieldList["`aval_id`"] = 0;
			
				// Field promotor_id
			//	$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
				$fieldList["`promotor_id`"] = 0;
			
				// Field direccion_tipo_id
			//	$theValue = ($GLOBALS["x_direccion_tipo_id"] != "") ? intval($GLOBALS["x_direccion_tipo_id"]) : "NULL";
				$fieldList["`direccion_tipo_id`"] = 1;
			
				// Field calle
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle"]) : $GLOBALS["x_calle"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`calle`"] = $theValue;
			
				// Field colonia
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia"]) : $GLOBALS["x_colonia"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`colonia`"] = $theValue;

				// Field delegacion_id
				$theValue = ($GLOBALS["x_delegacion_id"] != "") ? intval($GLOBALS["x_delegacion_id"]) : "0";
				$fieldList["`delegacion_id`"] = $theValue;
			
				// Field propietario
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_propietario"]) : $GLOBALS["x_propietario"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`propietario`"] = $theValue;
			
				// Field codigo_postal
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_codigo_postal"]) : $GLOBALS["x_codigo_postal"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`codigo_postal`"] = $theValue;
			
				// Field ubicacion
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion"]) : $GLOBALS["x_ubicacion"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`ubicacion`"] = $theValue;
			
				// Field antiguedad
				$theValue = ($GLOBALS["x_antiguedad"] != "") ? intval($GLOBALS["x_antiguedad"]) : "0";
				$fieldList["`antiguedad`"] = $theValue;
			
				// Field vivienda_tipo_id
				$theValue = ($GLOBALS["x_vivienda_tipo_id"] != "") ? intval($GLOBALS["x_vivienda_tipo_id"]) : "0";
				$fieldList["`vivienda_tipo_id`"] = $theValue;

				// Field otro_tipo_vivienda
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otro_tipo_vivienda"]) : $GLOBALS["x_otro_tipo_vivienda"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`otro_tipo_vivienda`"] = $theValue;
			
				// Field telefono
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono"]) : $GLOBALS["x_telefono"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono`"] = $theValue;

				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_sec"]) : $GLOBALS["x_telefono_sec"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono_movil`"] = $theValue;

				// Field telefono_secundario
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_secundario"]) : $GLOBALS["x_telefono_secundario"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono_secundario`"] = $theValue;
				
				// Field vivienda_tipo_id
				$theValue = ($GLOBALS["x_compania_celular_id"] != "") ? intval($GLOBALS["x_compania_celular_id"]) : "0";
				$fieldList["`compania_celular_id`"] = $theValue;
			
				$theValue = ($GLOBALS["x_numero_exterior"] != "") ? intval($GLOBALS["x_numero_exterior"]) : "0";
				$fieldList["`numero_exterior`"] = $theValue;
					
					
				// Field telefono_secundario
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_secundario_2"]) :$GLOBALS["x_telefono_secundario_2"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono_movil_2`"] = $theValue;
					 
				$theValue = ($GLOBALS["x_compania_celular_id_2"] != "") ? intval($GLOBALS["x_compania_celular_id_2"]) : "0";
				$fieldList["`compania_celular_id_2`"] = $theValue;
			
				// insert into database
				$sSql = "INSERT INTO `direccion` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
			
				$x_result = phpmkr_query($sSql, $conn);
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL AQUI: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
			}
		}
	
	
	
	
	//DIR NEG

		if($GLOBALS["x_direccion_id2"] > 0 ){
	
			$fieldList = NULL;
	
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle2"]) : $GLOBALS["x_calle2"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`calle`"] = $theValue;
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia2"]) : $GLOBALS["x_colonia2"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`colonia`"] = $theValue;
			$theValue = ($GLOBALS["x_delegacion_id2"] != "") ? intval($GLOBALS["x_delegacion_id2"]) : "0";
			$fieldList["`delegacion_id`"] = $theValue;

			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_codigo_postal2"]) : $GLOBALS["x_codigo_postal2"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`codigo_postal`"] = $theValue;
			
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion2"]) : $GLOBALS["x_ubicacion2"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`ubicacion`"] = $theValue;
			$theValue = ($GLOBALS["x_antiguedad2"] != "") ? intval($GLOBALS["x_antiguedad2"]) : "0";
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
				echo phpmkr_error() . '<br>SQL cliente neg: ' . $sSql;
				phpmkr_query('rollback;', $conn);	 
			exit();
			}

		}else{

			if($GLOBALS["x_calle2"] != ""){

				$fieldList = NULL;
				// Field cliente_id
			//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
				$fieldList["`cliente_id`"] = $GLOBALS["x_cliente_id"];
			
				// Field aval_id
			//	$theValue = ($GLOBALS["x_aval_id"] != "") ? intval($GLOBALS["x_aval_id"]) : "NULL";
				$fieldList["`aval_id`"] = 0;
			
				// Field promotor_id
			//	$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
				$fieldList["`promotor_id`"] = 0;
			
				// Field direccion_tipo_id
			//	$theValue = ($GLOBALS["x_direccion_tipo_id"] != "") ? intval($GLOBALS["x_direccion_tipo_id"]) : "NULL";
				$fieldList["`direccion_tipo_id`"] = 2;
			
				// Field calle
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle2"]) : $GLOBALS["x_calle2"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`calle`"] = $theValue;
			
				// Field colonia
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia2"]) : $GLOBALS["x_colonia2"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`colonia`"] = $theValue;
			
				// Field delegacion_id
				$theValue = ($GLOBALS["x_delegacion_id2"] != "") ? intval($GLOBALS["x_delegacion_id2"]) : "0";
				$fieldList["`delegacion_id`"] = $theValue;

				// Field codigo_postal
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_codigo_postal2"]) : $GLOBALS["x_codigo_postal2"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`codigo_postal`"] = $theValue;
			
				// Field ubicacion
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion2"]) : $GLOBALS["x_ubicacion2"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`ubicacion`"] = $theValue;
			
				// Field antiguedad
				$theValue = ($GLOBALS["x_antiguedad"] != "") ? intval($GLOBALS["x_antiguedad2"]) : "0";
				$fieldList["`antiguedad`"] = $theValue;
			
				// Field vivienda_tipo_id
				$theValue = ($GLOBALS["x_vivienda_tipo_id2"] != "") ? intval($GLOBALS["x_vivienda_tipo_id2"]) : "0";
				$fieldList["`vivienda_tipo_id`"] = $theValue;
			
				// Field otro_tipo_vivienda
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otro_tipo_vivienda2"]) : $GLOBALS["x_otro_tipo_vivienda2"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`otro_tipo_vivienda`"] = $theValue;
			
				// Field telefono
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono2"]) : $GLOBALS["x_telefono2"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono`"] = $theValue;
			
				// Field telefono_secundario
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_secundario2"]) : $GLOBALS["x_telefono_secundario2"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono_secundario`"] = $theValue;
			
				// insert into database
				$sSql = "INSERT INTO `direccion` (";
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
			}
		}
			
		
		
	//GARANTIAS


		if($GLOBALS["x_garantia_id"] != ""){
			
			$fieldList = NULL;		
			
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_garantia_desc"]) : $GLOBALS["x_garantia_desc"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`descripcion`"] = $theValue;
			$theValue = ($GLOBALS["x_garantia_valor"] != "") ? " '" . doubleval($GLOBALS["x_garantia_valor"]) . "'" : "NULL";
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
				//exit();
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
		
			}
		
		}



//INGRESOS

	if($GLOBALS["x_ingreso_id"] > 0){ 
		$fieldList = NULL;

		// Field ingresos_negocio
		$theValue = ($GLOBALS["x_ingresos_negocio"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_negocio"]) . "'" : "NULL";
		$fieldList["`ingresos_negocio`"] = $theValue;
	
		// Field ingresos_familiar_1
		$theValue = ($GLOBALS["x_ingresos_familiar_1"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_familiar_1"]) . "'" : "NULL";
		$fieldList["`ingresos_familiar_1`"] = $theValue;
	
		// Field parentesco_tipo_id
		$theValue = ($GLOBALS["x_parentesco_tipo_id_ing_1"] != "") ? intval($GLOBALS["x_parentesco_tipo_id_ing_1"]) : "0";
		$fieldList["`parentesco_tipo_id`"] = $theValue;
	
		// Field ingresos_familiar_2
		$theValue = ($GLOBALS["x_ingresos_familiar_2"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_familiar_2"]) . "'" : "NULL";
		$fieldList["`ingresos_familiar_2`"] = $theValue;
	
		// Field parentesco_tipo_id2
		$theValue = ($GLOBALS["x_parentesco_tipo_id_ing_2"] != "") ? intval($GLOBALS["x_parentesco_tipo_id_ing_2"]) : "0";
		$fieldList["`parentesco_tipo_id2`"] = $theValue;
	
		// Field otros_ingresos
		$theValue = ($GLOBALS["x_otros_ingresos"] != "") ? " '" . doubleval($GLOBALS["x_otros_ingresos"]) . "'" : "NULL";
		$fieldList["`otros_ingresos`"] = $theValue;
	
		$theValue = ($GLOBALS["x_origen_ingresos"] != "") ? " '" . $GLOBALS["x_origen_ingresos"] . "'" : "NULL";
		$fieldList["`origen_ingresos`"] = $theValue;
	
	
		$theValue = ($GLOBALS["x_origen_ingresos2"] != "") ? " '" . $GLOBALS["x_origen_ingresos2"] . "'" : "NULL";
		$fieldList["`origen_ingresos_fam_1`"] = $theValue;
	
		$theValue = ($GLOBALS["x_origen_ingresos3"] != "") ? " '" . $GLOBALS["x_origen_ingresos3"] . "'" : "NULL";
		$fieldList["`origen_ingresos_fam_2`"] = $theValue;

		// update
		$sSql = "UPDATE `ingreso_2` SET ";
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
		
	}else{		
		
			
		$fieldList = NULL;
		// Field cliente_id
	//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
		$fieldList["`solicitud_id`"] = $GLOBALS["x_solicitud_id"];
		$fieldList["`cliente_id`"] = $GLOBALS["x_cliente_id"];
		// Field ingresos_negocio
		$theValue = ($GLOBALS["x_ingresos_negocio"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_negocio"]) . "'" : "NULL";
		$fieldList["`ingresos_negocio`"] = $theValue;
	
		// Field ingresos_familiar_1
		$theValue = ($GLOBALS["x_ingresos_familiar_1"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_familiar_1"]) . "'" : "NULL";
		$fieldList["`ingresos_familiar_1`"] = $theValue;
	
		// Field parentesco_tipo_id
		$theValue = ($GLOBALS["x_parentesco_tipo_id_ing_1"] != "") ? intval($GLOBALS["x_parentesco_tipo_id_ing_1"]) : "0";
		$fieldList["`parentesco_tipo_id`"] = $theValue;
	
		// Field ingresos_familiar_2
		$theValue = ($GLOBALS["x_ingresos_familiar_2"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_familiar_2"]) . "'" : "NULL";
		$fieldList["`ingresos_familiar_2`"] = $theValue;
	
		// Field parentesco_tipo_id2
		$theValue = ($GLOBALS["x_parentesco_tipo_id_ing_2"] != "") ? intval($GLOBALS["x_parentesco_tipo_id_ing_2"]) : "0";
		$fieldList["`parentesco_tipo_id2`"] = $theValue;
	
		// Field otros_ingresos
		$theValue = ($GLOBALS["x_otros_ingresos"] != "") ? " '" . doubleval($GLOBALS["x_otros_ingresos"]) . "'" : "NULL";
		$fieldList["`otros_ingresos`"] = $theValue;
	
		$theValue = ($GLOBALS["x_origen_ingresos"] != "") ? " '" . $GLOBALS["x_origen_ingresos"] . "'" : "NULL";
		$fieldList["`origen_ingresos`"] = $theValue;
	
	
		$theValue = ($GLOBALS["x_origen_ingresos2"] != "") ? " '" . $GLOBALS["x_origen_ingresos2"] . "'" : "NULL";
		$fieldList["`origen_ingresos_fam_1`"] = $theValue;
	
		$theValue = ($GLOBALS["x_origen_ingresos3"] != "") ? " '" . $GLOBALS["x_origen_ingresos3"] . "'" : "NULL";
		$fieldList["`origen_ingresos_fam_2`"] = $theValue;
	
		// insert into database
		$sSql = "INSERT INTO `ingreso_2` (";
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
	}		
		

//gastos

	if($GLOBALS["x_gasto_id"] > 0){ 
		$fieldList = NULL;
	
		$theValue = ($GLOBALS["x_gastos_prov1"] != "") ? " '" . doubleval($GLOBALS["x_gastos_prov1"]) . "'" : "NULL";
		$fieldList["`gastos_prov1`"] = $theValue;
	
	
		$theValue = ($GLOBALS["x_gastos_prov2"] != "") ? " '" . doubleval($GLOBALS["x_gastos_prov2"]) . "'" : "NULL";
		$fieldList["`gastos_prov2`"] = $theValue;
	
	
		$theValue = ($GLOBALS["x_gastos_prov3"] != "") ? doubleval($GLOBALS["x_gastos_prov3"]) : "NULL";
		$fieldList["`gastos_prov3`"] = $theValue;


		$theValue = ($GLOBALS["x_otro_prov"] != "") ? doubleval($GLOBALS["x_otro_prov"]) : "NULL";
		$fieldList["`otro_prov`"] = $theValue;


		$theValue = ($GLOBALS["x_gastos_empleados"] != "") ? doubleval($GLOBALS["x_gastos_empleados"]) : "NULL";
		$fieldList["`gastos_empleados`"] = $theValue;


		$theValue = ($GLOBALS["x_gastos_renta_negocio"] != "") ? " '" . doubleval($GLOBALS["x_gastos_renta_negocio"]) . "'" : "NULL";
		$fieldList["`gastos_renta_negocio`"] = $theValue;
	
	
		$theValue = ($GLOBALS["x_gastos_renta_casa"] != "") ? doubleval($GLOBALS["x_gastos_renta_casa"]) : "0";
		$fieldList["`gastos_renta_casa`"] = $theValue;
	
	
		$theValue = ($GLOBALS["x_gastos_credito_hipotecario"] != "") ? " '" . doubleval($GLOBALS["x_gastos_credito_hipotecario"]) . "'" : "NULL";
		$fieldList["`gastos_credito_hipotecario`"] = $theValue;
	
		$theValue = ($GLOBALS["x_gastos_otros"] != "") ? " '" . doubleval($GLOBALS["x_gastos_otros"]) . "'" : "NULL";
		$fieldList["`gastos_otros`"] = $theValue;

		// update
		$sSql = "UPDATE `gasto_2` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE gasto_id = " . $GLOBALS["x_gasto_id"];
		$x_result = phpmkr_query($sSql,$conn);

		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}
		
	}else{		
		
			
		$fieldList = NULL;
		// Field cliente_id
	//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
		$fieldList["`solicitud_id`"] = $GLOBALS["x_solicitud_id"];
		$fieldList["`cliente_id`"] = $GLOBALS["x_cliente_id"];
		
		$theValue = ($GLOBALS["x_gastos_prov1"] != "") ? " '" . doubleval($GLOBALS["x_gastos_prov1"]) . "'" : "NULL";
		$fieldList["`gastos_prov1`"] = $theValue;
	
	
		$theValue = ($GLOBALS["x_gastos_prov2"] != "") ? " '" . doubleval($GLOBALS["x_gastos_prov2"]) . "'" : "NULL";
		$fieldList["`gastos_prov2`"] = $theValue;
	
	
		$theValue = ($GLOBALS["x_gastos_prov3"] != "") ? doubleval($GLOBALS["x_gastos_prov3"]) : "NULL";
		$fieldList["`gastos_prov3`"] = $theValue;


		$theValue = ($GLOBALS["x_otro_prov"] != "") ? doubleval($GLOBALS["x_otro_prov"]) : "NULL";
		$fieldList["`otro_prov`"] = $theValue;


		$theValue = ($GLOBALS["x_gastos_empleados"] != "") ? doubleval($GLOBALS["x_gastos_empleados"]) : "NULL";
		$fieldList["`gastos_empleados`"] = $theValue;


		$theValue = ($GLOBALS["x_gastos_renta_negocio"] != "") ? " '" . doubleval($GLOBALS["x_gastos_renta_negocio"]) . "'" : "NULL";
		$fieldList["`gastos_renta_negocio`"] = $theValue;
	
	
		$theValue = ($GLOBALS["x_gastos_renta_casa"] != "") ? doubleval($GLOBALS["x_gastos_renta_casa"]) : "0";
		$fieldList["`gastos_renta_casa`"] = $theValue;
	
	
		$theValue = ($GLOBALS["x_gastos_credito_hipotecario"] != "") ? " '" . doubleval($GLOBALS["x_gastos_credito_hipotecario"]) . "'" : "NULL";
		$fieldList["`gastos_credito_hipotecario`"] = $theValue;
	
		$theValue = ($GLOBALS["x_gastos_otros"] != "") ? " '" . doubleval($GLOBALS["x_gastos_otros"]) . "'" : "NULL";
		$fieldList["`gastos_otros`"] = $theValue;
		
		// insert into database
		$sSql = "INSERT INTO `gasto_2` (";
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
	}		
		
		//TELEFONOS

		$sSql = " delete from telefono WHERE cliente_id = " . $GLOBALS["x_cliente_id"];
		$x_result = phpmkr_query($sSql,$conn);

		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}
		
		$x_count_telefonos_casa = 1 ;
		echo   $GLOBALS["contador_telefono"];
	while($x_count_telefonos_casa  <=  $GLOBALS["contador_telefono"]){
		
		$x_aux_num = "x_telefono_casa_$x_count_telefonos_casa";
		$x_aux_num = $$x_aux_num;
		
		$x_aux_coment = "x_comentario_casa_$x_count_telefonos_casa";
		$x_aux_coment = $$x_aux_coment;
		
		if($GLOBALS["x_telefono_casa_$x_count_telefonos_casa"] != ""){		
		
		$fieldList = NULL;
		
		$fieldList["`cliente_id`"] = $GLOBALS["x_cliente_id"];
		$fieldList["`telefono_tipo_id`"] = 1; // uno es telefono fijo	
		
		// Field numero
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_casa_$x_count_telefonos_casa"]) : $GLOBALS["x_telefono_casa_$x_count_telefonos_casa"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`numero`"] = $theValue;
		
		// Field comentario
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_comentario_casa_$x_count_telefonos_casa"]) : $GLOBALS["x_comentario_casa_$x_count_telefonos_casa"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`comentario`"] = $theValue;
		
		
		// insert into database
			$sSql = "INSERT INTO `telefono` (";
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
		}
		
		$x_count_telefonos_casa ++;
		}
	
	
		$x_count_telefonos_cel = 1 ;
	while($x_count_telefonos_cel  <=  $GLOBALS["contador_celular"]){
		
		$x_aux_cel = "x_telefono_celular_$x_count_telefonos_cel";
		$x_aux_cel = $$x_aux_cel;
		
		$x_aux_coment = "x_comentario_celular_$x_count_telefonos_cel";
		$x_aux_coment = $$x_aux_coment;
		
		$x_aux_comp = "x_compania_celular_$x_count_telefonos_cel";
		$x_aux_comp = $$x_aux_comp;
		
		if($GLOBALS["x_telefono_celular_$x_count_telefonos_cel"] != ""){		
		
		$fieldList = NULL;
		
		$fieldList["`cliente_id`"] = $GLOBALS["x_cliente_id"];
		$fieldList["`telefono_tipo_id`"] = 2; // uno es telefono fijo	
		
		// Field numero
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_celular_$x_count_telefonos_cel"]) : $GLOBALS["x_telefono_celular_$x_count_telefonos_cel"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`numero`"] = $theValue;
		
		// Field comentario
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_comentario_celular_$x_count_telefonos_cel"]) : $GLOBALS["x_comentario_celular_$x_count_telefonos_cel"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`comentario`"] = $theValue;
		
		// Field acomania cel
		$theValue = ($GLOBALS["x_compania_celular_$x_count_$x_count_telefonos_cel"] != "") ? intval($GLOBALS["x_compania_celular_$x_count_telefonos_cel"]) : "0";
		$fieldList["`compania_id`"] = $theValue;
		
		
		// insert into database
			$sSql = "INSERT INTO `telefono` (";
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
		}
		
		$x_count_telefonos_cel ++;
		}	
		
		
		
		//REFERENCIAS

		$sSql = "delete from referencia_2 WHERE solicitud_id = " . $GLOBALS["x_solicitud_id"]." AND cliente_id = ". $GLOBALS["x_cliente_id"];
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
			$fieldList["`solicitud_id`"] = $GLOBALS["x_solicitud_id"];
			$fieldList["`cliente_id`"] = $GLOBALS["x_cliente_id"];
	
	
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
				$sSql = "INSERT INTO `referencia_2` (";
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
	
			}
	
	
			$x_counter++;
		}
		
	
	}//else principal
	
	phpmkr_query('commit;', $conn);
	return true;
	}


?>

