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
if(empty($x_solicitud_id)){
	$x_solicitud_id = @$_POST["x_solicitud_id"];
}
$sSqlWrk = "SELECT cliente_id FROM solicitud_cliente where solicitud_id = $x_solicitud_id";		
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
$x_cliente_id = $datawrk["cliente_id"];
@phpmkr_free_result($rswrk);

$x_win = @$_GET["win"];
if(empty($x_win)){
	$x_win = $_POST["x_win"];
	
}




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

	$x_actividad_id = $_POST["x_actividad_id"];
	$x_actividad_desc = $_POST["x_actividad_desc"];
	$x_cliente_id = @$_POST["x_cliente_id"];
	
	//agregados por requerimientos para el reporte de pronafin...
	//en cliente
	$x_rol_hogar_id = @$_POST["x_rol_hogar_id"];
	$x_fecha_ini_act_prod = @$_POST["x_fecha_ini_act_prod"];
	
//en direccion
	$x_numero_exterior = @$_POST["x_numero_exterior"];
	$x_compania_celular_id = @$_POST["x_compania_celular_id"];
	$x_telefono_movil_2 = @$_POST["x_otro_telefono_domicilio_2"];
	$x_compania_celular_id_2 = $_POST["x_compania_celular_id_2"];
	
	//en fechas
	$x_fecha_investigacion = @$_POST["x_fecha_investigacion"];
	$x_fecha_otorga_credito = @$_POST["x_fecha_otorga_credito"];
	$x_actualiza_fecha_investigacion = @$_POST["x_actualiza_fecha_investigacion"];

//en negocio
	$x_negocio_id = @$_POST["x_negocio_id"];
	$x_giro_negocio_id = @$_POST["x_giro_negocio_id"];
	$x_tipo_inmueble_id = @$_POST["x_tipo_inmueble_id"];
	$x_atiende_titular = @$_POST["x_atiende_titular"];
	$x_personas_trabajando = @$_POST["x_personas_trabajando"];
	$x_destino_credito_id = @$_POST["x_destino_credito_id"];
	//nuevos valores
	$x_nombre = @$_POST["x_nombre"];
	$x_apellido_paterno = @$_POST["x_apellido_paterno"];	
	$x_apellido_materno = @$_POST["x_apellido_materno"];
	$x_rfc = @$_POST["x_rfc"];
	$x_curp = @$_POST["x_curp"];
	
	
	$x_fecha_nacimiento = @$_POST["x_fecha_nacimiento"];
	$x_sexo = @$_POST["x_sexo"];
	$x_integrantes_familia = @$_POST["x_integrantes_familia"];
	$x_dependientes = @$_POST["x_dependientes"];
	$x_correo_electronico = @$_POST["x_correo_electronico"];
	$x_esposa = @$_POST["x_esposa"];
	$x_calle_domicilio = @$_POST["x_calle_domicilio"];
	$x_colonia_domicilio = @$_POST["x_colonia_domicilio"];
	$x_entidad_domicilio = @$_POST["x_entidad_domicilio"];
	$x_codigo_postal_domicilio = @$_POST["x_codigo_postal_domicilio"];
	$x_ubicacion_domicilio = @$_POST["x_ubicacion_domicilio"];
	$x_tipo_vivienda = @$_POST["x_tipo_vivienda"];
	$x_telefono_domicilio = @$_POST["x_telefono_domicilio"];
	$x_celular = @$_POST["x_celular"];
	$x_otro_tel_domicilio_1 = @$_POST["x_otro_tel_domicilio_1"];
	$x_otro_telefono_domicilio_2 = @$_POST["x_otro_telefono_domicilio_2"];
	$x_antiguedad = @$_POST["x_antiguedad"];
	$x_tel_arrendatario_domicilio = @$_POST["x_tel_arrendatario_domicilio"];
	$x_renta_mensula_domicilio = @$_POST["x_renta_mensula_domicilio"];
	$x_giro_negocio = @$_POST["x_giro_negocio"];
	$x_calle_negocio = @$_POST["x_calle_negocio"];
	$x_colonia_negocio = @$_POST["x_colonia_negocio"];
	$x_entidad_negocio = @$_POST["x_entidad_negocio"];
	$x_ubicacion_negocio = @$_POST["x_ubicacion_negocio"];
	$x_codigo_postal_negocio = @$_POST["x_codigo_postal_negocio"];
	$x_tipo_local_negocio = @$_POST["x_tipo_local_negocio"];
	$x_antiguedad_negocio = @$_POST["x_antiguedad_negocio"];
	$x_tel_arrendatario_negocio = @$_POST["x_tel_arrendatario_negocio"];
	$x_renta_mensual = @$_POST["x_renta_mensual"];
	$x_tel_negocio = @$_POST["x_tel_negocio"];
	$x_solicitud_compra = @$_POST["x_solicitud_compra"];
	$x_referencia_com_1 = @$_POST["x_referencia_com_1"];
	$x_referencia_com_2 = @$_POST["x_referencia_com_2"];
	$x_referencia_com_3 = @$_POST["x_referencia_com_3"];
	$x_referencia_com_4 = @$_POST["x_referencia_com_4"];
	$x_tel_referencia_1 = @$_POST["x_tel_referencia_1"];
	$x_tel_referencia_2 = @$_POST["x_tel_referencia_2"];
	$x_tel_referencia_3 = @$_POST["x_tel_referencia_3"];
	$x_tel_referencia_4 = @$_POST["x_tel_referencia_4"];
	$x_parentesco_ref_1 = @$_POST["x_parentesco_ref_1"];
	$x_parentesco_ref_2 = @$_POST["x_parentesco_ref_2"];
	$x_parentesco_ref_3 = @$_POST["x_parentesco_ref_3"];
	$x_parentesco_ref_4 = @$_POST["x_parentesco_ref_4"];
	$x_ing_fam_negocio = @$_POST["x_ing_fam_negocio"];
	$x_ing_fam_otro_th = @$_POST["x_ing_fam_otro_th"];
	$x_ing_fam_1 = @$_POST["x_ing_fam_1"];
	$x_ing_fam_2 = @$_POST["x_ing_fam_2"];
	$x_ing_fam_deuda_1 = @$_POST["x_ing_fam_deuda_1"];
	$x_ing_fam_deuda_2 = @$_POST["x_ing_fam_deuda_2"];
	$x_ing_fam_total = @$_POST["x_ing_fam_total"];
	$x_ing_fam_cuales_1 = @$_POST["x_ing_fam_cuales_1"];
	$x_ing_fam_cuales_2 = @$_POST["x_ing_fam_cuales_2"];
	$x_ing_fam_cuales_3 = @$_POST["x_ing_fam_cuales_3"];
	$x_ing_fam_cuales_4 = @$_POST["x_ing_fam_cuales_4"];
	$x_ing_fam_cuales_5 = @$_POST["x_ing_fam_cuales_5"];
	$x_flujos_neg_ventas = @$_POST["x_flujos_neg_ventas"];
	$x_flujos_neg_proveedor_1 = @$_POST["x_flujos_neg_proveedor_1"];
	$x_flujos_neg_proveedor_2 = @$_POST["x_flujos_neg_proveedor_2"];
	$x_flujos_neg_proveedor_3 = @$_POST["x_flujos_neg_proveedor_3"];
	$x_flujos_neg_proveedor_4 = @$_POST["x_flujos_neg_proveedor_4"];
	$x_flujos_neg_gasto_1 = @$_POST["x_flujos_neg_gasto_1"];
	$x_flujos_neg_gasto_2 = @$_POST["x_flujos_neg_gasto_2"];
	$x_flujos_neg_gasto_3 = @$_POST["x_flujos_neg_gasto_3"];
	$x_flujos_neg_cual_1 = @$_POST["x_flujos_neg_cual_1"];
	$x_flujos_neg_cual_2 = @$_POST["x_flujos_neg_cual_2"];
	$x_flujos_neg_cual_3 = @$_POST["x_flujos_neg_cual_3"];
	$x_flujos_neg_cual_4 = @$_POST["x_flujos_neg_cual_4"];
	$x_flujos_neg_cual_5 = @$_POST["x_flujos_neg_cual_5"];
	$x_flujos_neg_cual_6 = @$_POST["x_flujos_neg_cual_6"];
	$x_flujos_neg_cual_7 = @$_POST["x_flujos_neg_cual_7"];
	$x_ingreso_negocio = @$_POST["x_ingreso_negocio"];
	$x_inv_neg_fija_conc_1 = @$_POST["x_inv_neg_fija_conc_1"];
	$x_inv_neg_fija_conc_2 = @$_POST["x_inv_neg_fija_conc_2"];
	$x_inv_neg_fija_conc_3 = @$_POST["x_inv_neg_fija_conc_3"];
	$x_inv_neg_fija_conc_4 = @$_POST["x_inv_neg_fija_conc_4"];
	$x_inv_neg_fija_valor_1 = @$_POST["x_inv_neg_fija_valor_1"];
	$x_inv_neg_fija_valor_2 = @$_POST["x_inv_neg_fija_valor_2"];
	$x_inv_neg_fija_valor_3 = @$_POST["x_inv_neg_fija_valor_3"];
	$x_inv_neg_fija_valor_4 = @$_POST["x_inv_neg_fija_valor_4"];
	$x_inv_neg_total_fija = @$_POST["x_inv_neg_total_fija"];
	$x_inv_neg_var_conc_1 = @$_POST["x_inv_neg_var_conc_1"];
	$x_inv_neg_var_conc_2 = @$_POST["x_inv_neg_var_conc_2"];
	$x_inv_neg_var_conc_3 = @$_POST["x_inv_neg_var_conc_3"];
	$x_inv_neg_var_conc_4 = @$_POST["x_inv_neg_var_conc_4"];
	$x_inv_neg_var_valor_1 = @$_POST["x_inv_neg_var_valor_1"];
	$x_inv_neg_var_valor_2 = @$_POST["x_inv_neg_var_valor_2"];
	$x_inv_neg_var_valor_3 = @$_POST["x_inv_neg_var_valor_3"];
	$x_inv_neg_var_valor_4 = @$_POST["x_inv_neg_var_valor_4"];
	$x_inv_neg_total_var = @$_POST["x_inv_neg_total_var"];
	$x_inv_neg_activos_totales = @$_POST["x_inv_neg_activos_totales"];
	$x_fecha = @$_POST["x_fecha"];
	
	
	
	
	//olds
	//$x_nombre_completo = @$_POST["x_nombre"];
			
	//$x_tit_rfc = @$_POST["x_rfc"];	
	//$x_tit_curp = @$_POST["x_curp"];
	//$x_tit_fecha_nac = @$_POST["x_fecha_nacimiento"];
	$x_numero_hijos = @$_POST["x_integrantes_familia"];
	$x_numero_hijos_dep = @$_POST["x_dependientes"];
	$x_email = @$_POST["x_correo_electronico"];	
	$x_nombre_conyuge = @$_POST["x_esposa"];
	$x_sexo = @$_POST["x_sexo"];
	
	
	$x_tipo_negocio = @$_POST["x_tipo_negocio"];	
	$x_edad = @$_POST["x_edad"];	
	$x_estado_civil_id = @$_POST["x_estado_civil_id"];
	$x_nacionalidad_id = @$_POST["x_nacionalidad_id"];		
	$x_empresa = @$_POST["x_empresa"];		
	$x_puesto = @$_POST["x_puesto"];			
	$x_fecha_contratacion = @$_POST["x_fecha_contratacion"];				
	$x_salario_mensual = @$_POST["x_salario_mensual"];					


	$x_direccion_id = @$_POST["x_direccion_id"]; //checar debe estar como un hidden es  necesario
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

//los mios


	$x_ing_fam_negocio = @$_POST["x_ing_fam_negocio"];
	$x_ing_fam_otro_th = @$_POST["x_ing_fam_otro_th"];
	$x_ing_fam_1 = @$_POST["x_ing_fam_1"];
	$x_ing_fam_2 = @$_POST["x_ing_fam_2"];
	$x_ing_fam_deuda_1 = @$_POST["x_ing_fam_deuda_1"];
	$x_ing_fam_deuda_2 = @$_POST["x_ing_fam_deuda_2"];
	$x_ing_fam_total = @$_POST["x_ing_fam_total"];
	$x_ing_fam_cuales_1 = @$_POST["x_ing_fam_cuales_1"];
	$x_ing_fam_cuales_2 = @$_POST["x_ing_fam_cuales_2"];
	$x_ing_fam_cuales_3 = @$_POST["x_ing_fam_cuales_3"];
	$x_ing_fam_cuales_4 = @$_POST["x_ing_fam_cuales_4"];
	$x_ing_fam_cuales_5 = @$_POST["x_ing_fam_cuales_5"];
	$x_flujos_neg_ventas = @$_POST["x_flujos_neg_ventas"];
	$x_flujos_neg_proveedor_1 = @$_POST["x_flujos_neg_proveedor_1"];
	$x_flujos_neg_proveedor_2 = @$_POST["x_flujos_neg_proveedor_2"];
	$x_flujos_neg_proveedor_3 = @$_POST["x_flujos_neg_proveedor_3"];
	$x_flujos_neg_proveedor_4 = @$_POST["x_flujos_neg_proveedor_4"];
	$x_flujos_neg_gasto_1 = @$_POST["x_flujos_neg_gasto_1"];
	$x_flujos_neg_gasto_2 = @$_POST["x_flujos_neg_gasto_2"];
	$x_flujos_neg_gasto_3 = @$_POST["x_flujos_neg_gasto_3"];
	$x_flujos_neg_cual_1 = @$_POST["x_flujos_neg_cual_1"];
	$x_flujos_neg_cual_2 = @$_POST["x_flujos_neg_cual_2"];
	$x_flujos_neg_cual_3 = @$_POST["x_flujos_neg_cual_3"];
	$x_flujos_neg_cual_4 = @$_POST["x_flujos_neg_cual_4"];
	$x_flujos_neg_cual_5 = @$_POST["x_flujos_neg_cual_5"];
	$x_flujos_neg_cual_6 = @$_POST["x_flujos_neg_cual_6"];
	$x_flujos_neg_cual_7 = @$_POST["x_flujos_neg_cual_7"];
	$x_ingreso_negocio = @$_POST["x_ingreso_negocio"];
	$x_inv_neg_fija_conc_1 = @$_POST["x_inv_neg_fija_conc_1"];
	$x_inv_neg_fija_conc_2 = @$_POST["x_inv_neg_fija_conc_2"];
	$x_inv_neg_fija_conc_3 = @$_POST["x_inv_neg_fija_conc_3"];
	$x_inv_neg_fija_conc_4 = @$_POST["x_inv_neg_fija_conc_4"];
	$x_inv_neg_fija_valor_1 = @$_POST["x_inv_neg_fija_valor_1"];
	$x_inv_neg_fija_valor_2 = @$_POST["x_inv_neg_fija_valor_2"];
	$x_inv_neg_fija_valor_3 = @$_POST["x_inv_neg_fija_valor_3"];
	$x_inv_neg_fija_valor_4 = @$_POST["x_inv_neg_fija_valor_4"];
	$x_inv_neg_total_fija = @$_POST["x_inv_neg_total_fija"];
	$x_inv_neg_var_conc_1 = @$_POST["x_inv_neg_var_conc_1"];
	$x_inv_neg_var_conc_2 = @$_POST["x_inv_neg_var_conc_2"];
	$x_inv_neg_var_conc_3 = @$_POST["x_inv_neg_var_conc_3"];
	$x_inv_neg_var_conc_4 = @$_POST["x_inv_neg_var_conc_4"];
	$x_inv_neg_var_valor_1 = @$_POST["x_inv_neg_var_valor_1"];
	$x_inv_neg_var_valor_2 = @$_POST["x_inv_neg_var_valor_2"];
	$x_inv_neg_var_valor_3 = @$_POST["x_inv_neg_var_valor_3"];
	$x_inv_neg_var_valor_4 = @$_POST["x_inv_neg_var_valor_4"];
	$x_inv_neg_total_var = @$_POST["x_inv_neg_total_var"];
	$x_inv_neg_activos_totales  = @$_POST["x_inv_neg_activos_totales"];
/////////////////////


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

<link href="../php_project_esf.css" rel="stylesheet" type="text/css" />
</head>

<body onLoad="cargaEventos();">

<script type="text/javascript" src="../ew.js"></script>
<script src="paisedohint.js"></script> 
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<script type="text/javascript">
<!--



function cargaEventos(){
	
	
	//alert("Credito para Adquisicion de Maquinaria")
	 //ACTUALIZA EL CONTENIDO DEL FORMATO ADQUISICION DE MAQUINARIA
	 //0document.getElementById("siguiente").onclick = muestraOculta;
	//document.getElementById("anterior").onclick = muestraOculta;
	//document.getElementById("enviar").onclick = EW_checkMyForm;
	//document.getElementById("seEnvioFormulario").onclick = ocultaMens;
	
	document.getElementById("x_inv_neg_var_valor_1").onchange = totalVarible;
	document.getElementById("x_inv_neg_var_valor_2").onchange = totalVarible;
	document.getElementById("x_inv_neg_var_valor_3").onchange = totalVarible;
	document.getElementById("x_inv_neg_var_valor_4").onchange = totalVarible;
	document.getElementById("x_inv_neg_fija_valor_1").onchange= totalFija;
	document.getElementById("x_inv_neg_fija_valor_2").onchange = totalFija;
	document.getElementById("x_inv_neg_fija_valor_3").onchange = totalFija;
	document.getElementById("x_inv_neg_fija_valor_4").onchange = totalFija;
	document.getElementById("x_ing_fam_negocio").onchange = ingresoFamiliar;
	document.getElementById("x_ing_fam_otro_th").onchange = ingresoFamiliar;
	document.getElementById("x_ing_fam_1").onchange = ingresoFamiliar;
	document.getElementById("x_ing_fam_2").onchange = ingresoFamiliar;
	document.getElementById("x_ing_fam_deuda_1").onchange = ingresoFamiliar;
	document.getElementById("x_ing_fam_deuda_2").onchange = ingresoFamiliar;
	document.getElementById("x_flujos_neg_ventas").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_proveedor_1").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_proveedor_2").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_proveedor_3").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_proveedor_4").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_gasto_1").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_gasto_2").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_gasto_3").onchange = flujosDeNegocio;
	
	 
	
	
function muestraOculta(){
 	var id = this.id; 
 	if(id =="siguiente"){
		document.getElementById("paginaUno").style.display ="none";
		document.getElementById("paginaDos").style.display ="block";
		document.getElementById("siguiente").style.display="none";
		document.getElementById("anterior").style.display="block";	
		} else{
			document.getElementById("paginaUno").style.display ="block";
			document.getElementById("paginaDos").style.display ="none";
			document.getElementById("anterior").style.display="none";
			document.getElementById("siguiente").style.display="block";
			}
 }//function muestraOculta 
	 
		function totalVarible (){
			t1 = document.getElementById("x_inv_neg_var_valor_1").value;
			t2 = document.getElementById("x_inv_neg_var_valor_2").value;
			t3 = document.getElementById("x_inv_neg_var_valor_3").value;
			t4 = document.getElementById("x_inv_neg_var_valor_4").value;
			total = 0;
			
			if(t1 == '')
				n1 = 0;
				else
				n1 = parseFloat(document.getElementById("x_inv_neg_var_valor_1").value);
				
			if(t2 == '')
				n2 = 0;
				else
				n2 = parseFloat(document.getElementById("x_inv_neg_var_valor_2").value);
				
			if(t3 == '')
				n3 = 0;
				else
				n3 = parseFloat(document.getElementById("x_inv_neg_var_valor_3").value);
				
			if(t4 == '')
				n4 = 0;
				else
				n4 = parseFloat(document.getElementById("x_inv_neg_var_valor_4").value);
			
			suma = n1 + n2 + n3 + n4;
			
			tempT = document.getElementById("x_inv_neg_total_fija").value;
			
			if(tempT == '')
				nt = 0;
				else
				nt = parseFloat(document.getElementById("x_inv_neg_total_fija").value);
			
			
			document.getElementById("x_inv_neg_total_var").value = suma;
			document.getElementById("x_inv_neg_activos_totales").value = (suma + nt);
			
			}
	 
	     
	 
	function totalFija (){
			t1 = document.getElementById("x_inv_neg_fija_valor_1").value;
			t2 = document.getElementById("x_inv_neg_fija_valor_2").value;
			t3 = document.getElementById("x_inv_neg_fija_valor_3").value;
			t4 = document.getElementById("x_inv_neg_fija_valor_4").value;
			total = 0;
			
			if(t1 == '')
				n1 = 0;
				else
				n1 = parseFloat(document.getElementById("x_inv_neg_fija_valor_1").value);
				
			if(t2 == '')
				n2 = 0;
				else
				n2 = parseFloat(document.getElementById("x_inv_neg_fija_valor_2").value);
				
			if(t3 == '')
				n3 = 0;
				else
				n3 = parseFloat(document.getElementById("x_inv_neg_fija_valor_3").value);
				
			if(t4 == '')
				n4 = 0;
				else
				n4 = parseFloat(document.getElementById("x_inv_neg_fija_valor_4").value);
			
			suma = n1 + n2 + n3 + n4;
			
			tempT = document.getElementById("x_inv_neg_total_var").value;
			
			if(tempT == '')
				nt = 0;
				else
				nt = parseFloat(document.getElementById("x_inv_neg_total_var").value);
			
			
			document.getElementById("x_inv_neg_total_fija").value = suma;
			document.getElementById("x_inv_neg_activos_totales").value = (suma + nt);
			
			}	
			
			function ingresoFamiliar(){
				t1 = document.getElementById("x_ing_fam_negocio").value;
				t2 = document.getElementById("x_ing_fam_otro_th").value;
				t3 = document.getElementById("x_ing_fam_1").value;
				t4 = document.getElementById("x_ing_fam_2").value;
				t5 = document.getElementById("x_ing_fam_deuda_1").value;
				t6 = document.getElementById("x_ing_fam_deuda_2").value;
				
				if(t1 == '')
				n1 = 0;
				else
				n1 = parseFloat(document.getElementById("x_ing_fam_negocio").value);
				
				if(t2 == '')
				n2 = 0;
				else
				n2 = parseFloat(document.getElementById("x_ing_fam_otro_th").value);
				
				
				if(t3 == '')
				n3 = 0;
				else
				n3 = parseFloat(document.getElementById("x_ing_fam_1").value);
				
				if(t4 == '')
				n4 = 0;
				else
				n4 = parseFloat(document.getElementById("x_ing_fam_2").value);
				
				if(t5 == '')
				n5 = 0;
				else
				n5 = parseFloat(document.getElementById("x_ing_fam_deuda_1").value);
				
				
				if(t6 == '')
				n6 = 0;
				else
				n6 = parseFloat(document.getElementById("x_ing_fam_deuda_2").value);
				
				total =(n1 + n2 +n3 +n4)-(n5 + n6);	
				
				
				document.getElementById("x_ing_fam_total").value = total;			
				
				}
				
				function flujosDeNegocio (){
					
					t1 = document.getElementById("x_flujos_neg_ventas").value;
					t2 = document.getElementById("x_flujos_neg_proveedor_1").value;
					t3 = document.getElementById("x_flujos_neg_proveedor_2").value;
					t4 = document.getElementById("x_flujos_neg_proveedor_3").value;
					t5 = document.getElementById("x_flujos_neg_proveedor_4").value;
					t6 = document.getElementById("x_flujos_neg_gasto_1").value;
					t7 = document.getElementById("x_flujos_neg_gasto_2").value;
					t8 = document.getElementById("x_flujos_neg_gasto_3").value;
					total = 0;
					
					if(t1 == '')
					n1 = 0;
					else
					n1 = parseFloat(document.getElementById("x_flujos_neg_ventas").value);
					
					if(t2 == '')
					n2 = 0;
					else
					n2 = parseFloat(document.getElementById("x_flujos_neg_proveedor_1").value);
					
					if(t3 == '')
					n3 = 0;
					else
					n3 = parseFloat(document.getElementById("x_flujos_neg_proveedor_2").value);
					
					if(t4 == '')
					n4 = 0;
					else
					n4 = parseFloat(document.getElementById("x_flujos_neg_proveedor_3").value);
					
					if(t5 == '')
					n5 = 0;
					else
					n5 = parseFloat(document.getElementById("x_flujos_neg_proveedor_4").value);
					
					if(t6 == '')
					n6 = 0;
					else
					n6 = parseFloat(document.getElementById("x_flujos_neg_gasto_1").value);
					
					if(t7 == '')
					n7 = 0;
					else
					n7 = parseFloat(document.getElementById("x_flujos_neg_gasto_2").value);
					
					if(t8 == '')
					n8 = 0;
					else
					n8 = parseFloat(document.getElementById("x_flujos_neg_gasto_3").value);
					
					
					total = ((n1)-(n2 + n3 + n4 + n5 +n6 +n7 +n8));					
					
					 document.getElementById("x_ingreso_negocio").value = total;
					 }
	
	

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



function EW_checkMyForm() {
EW_this = document.solicitudedit;
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


//las validaciones del formato qdquisicion de maquinaria

if (EW_this.x_nombre && !EW_hasValue(EW_this.x_nombre, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre, "TEXT", "Por favor introduzca el campo requerido - nombre"))
		validada = false;
}
if (EW_this.x_apellido_paterno && !EW_hasValue(EW_this.x_apellido_paterno, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_apellido_paterno, "TEXT", "Por favor introduzca el campo requerido - apellido paterno"))
		validada = false;
}

/*
if (EW_this.x_rfc && !EW_hasValue(EW_this.x_rfc, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_rfc, "TEXT", "Por favor introduzca el campo requerido - RFC"))
		validada = false;
}
if (EW_this.x_curp && !EW_hasValue(EW_this.x_curp, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_curp, "TEXT", "Por favor introduzca el campo requerido - CURP"))
		validada = false;
}*/
if (EW_this.x_fecha_nacimiento && !EW_hasValue(EW_this.x_fecha_nacimiento, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_nacimiento, "TEXT", "Por favor introduzca el campo requerido - fecha nacimiento"))
		validada = false;
}
/*
if (EW_this.x_fecha_nacimiento && !EW_checkdate(EW_this.x_fecha_nacimiento.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_nacimiento, "TEXT", "Formato de fecha incorrecto, verifique por favor., format = aaaa/mm/dd - fecha nacimiento"))
		validada = false; 
}*/
if (EW_this.x_sexo && !EW_hasValue(EW_this.x_sexo, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_sexo, "TEXT", "Por favor introduzca el campo requerido - sexo"))
		validada = false;
}
if (EW_this.x_integrantes_familia && !EW_hasValue(EW_this.x_integrantes_familia, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_integrantes_familia, "TEXT", "Por favor introduzca el campo requerido - integrantes familia"))
		validada = false;
}
if (EW_this.x_integrantes_familia && !EW_checkinteger(EW_this.x_integrantes_familia.value)) {
	if (!EW_onError(EW_this, EW_this.x_integrantes_familia, "TEXT", "Valor incorrecto, se espera un entero. - integrantes familia"))
		validada = false; 
}
if (EW_this.x_dependientes && !EW_hasValue(EW_this.x_dependientes, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_dependientes, "TEXT", "Por favor introduzca el campo requerido - dependientes"))
		validada = false;
}
if (EW_this.x_dependientes && !EW_checkinteger(EW_this.x_dependientes.value)) {
	if (!EW_onError(EW_this, EW_this.x_dependientes, "TEXT", "Valor incorrecto, se espera un entero. - dependientes"))
		validada = false; 
}
/*
if (EW_this.x_correo_electronico && !EW_hasValue(EW_this.x_correo_electronico, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_correo_electronico, "TEXT", "Por favor introduzca el campo requerido - correo electronico"))
		validada = false;
}
if (EW_this.x_correo_electronico && !EW_checkemail(EW_this.x_correo_electronico.value)) {
	if (!EW_onError(EW_this, EW_this.x_correo_electronico, "TEXT", "Email incorrecto, verifque por favor - correo electronico"))
		validada = false; 

}*/
if (EW_this.x_calle_domicilio && !EW_hasValue(EW_this.x_calle_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_calle_domicilio, "TEXT", "Por favor introduzca el campo requerido - calle domicilio"))
		validada = false;
}
if (EW_this.x_colonia_domicilio && !EW_hasValue(EW_this.x_colonia_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_colonia_domicilio, "TEXT", "Por favor introduzca el campo requerido - colonia domicilio"))
		validada = false;
}
if (EW_this.x_entidad_domicilio && !EW_hasValue(EW_this.x_entidad_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_entidad_domicilio, "TEXT", "Por favor introduzca el campo requerido - entidad domicilio"))
		validada = false;
}
/*
if (EW_this.x_codigo_postal_domicilio && !EW_hasValue(EW_this.x_codigo_postal_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal_domicilio, "TEXT", "Por favor introduzca el campo requerido - codigo postal domicilio"))
		validada = false;
}
if (EW_this.x_codigo_postal_domicilio && !EW_checkinteger(EW_this.x_codigo_postal_domicilio.value)) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal_domicilio, "TEXT", "Valor incorrecto, se espera un entero. - codigo postal domicilio"))
		validada = false; 
}*/
if (EW_this.x_ubicacion_domicilio && !EW_hasValue(EW_this.x_ubicacion_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ubicacion_domicilio, "TEXT", "Por favor introduzca el campo requerido - ubicacion domicilio"))
		validada = false;
}
if (EW_this.x_tipo_vivienda && !EW_hasValue(EW_this.x_tipo_vivienda, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tipo_vivienda, "TEXT", "Por favor introduzca el campo requerido - tipo vivienda"))
		validada = false;
}
if (EW_this.x_renta_mensula_domicilio && !EW_checknumber(EW_this.x_renta_mensula_domicilio.value)) {
	if (!EW_onError(EW_this, EW_this.x_renta_mensula_domicilio, "TEXT", "Formato de numero incorrecto, verifique por favor- renta mensula domicilio"))
		validada = false; 
}



// validacion de los telefonos del domicilio+

if(EW_this.x_telefono_domicilio.value.length != 0 || EW_this.x_celular.value.length != 0 ||
   EW_this.x_otro_telefono_domicilio_2.value.length != 0 || EW_this.x_otro_tel_domicilio_1.value.length != 0 ){
	//alguno de los telefono esta llenos
	}else{
		// no se ha llenado ningun telefono...es necesario llenar almenos uno de los 4		
		alert("Por favor introduzca almenos 1 de los telefono listados en la seccion domicilio")
		validada = false;
		
		}



// validacio de las referencias

if((EW_this.x_referencia_com_1.value.length != 0  && EW_this.x_referencia_com_2.value.length != 0) ||
   (EW_this.x_referencia_com_1.value.length != 0  && EW_this.x_referencia_com_3.value.length != 0) ||
   (EW_this.x_referencia_com_1.value.length != 0  && EW_this.x_referencia_com_4.value.length != 0) ||
   (EW_this.x_referencia_com_2.value.length != 0  && EW_this.x_referencia_com_3.value.length != 0) ||
   (EW_this.x_referencia_com_2.value.length != 0  && EW_this.x_referencia_com_4.value.length != 0) ||
   (EW_this.x_referencia_com_3.value.length != 0  && EW_this.x_referencia_com_4.value.length != 0 )){
	
	if (EW_this.x_referencia_com_1 && EW_hasValue(EW_this.x_referencia_com_1, "TEXT" )) {	
		if (EW_this.x_parentesco_ref_1 && !EW_hasValue(EW_this.x_parentesco_ref_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_ref_1, "TEXT", "Por favor introduzca el campo requerido - parentesco referencia 1"))
		validada = false;
		}	
			if (EW_this.x_tel_referencia_1 && !EW_hasValue(EW_this.x_tel_referencia_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tel_referencia_1, "TEXT", "Por favor introduzca el campo requerido - telefono referencia 1"))
		validada = false;
		}
				
	}//valida referncia uno
	
	
	
	if (EW_this.x_referencia_com_2 && EW_hasValue(EW_this.x_referencia_com_2, "TEXT" )) {	
		if (EW_this.x_parentesco_ref_2 && !EW_hasValue(EW_this.x_parentesco_ref_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_ref_2, "TEXT", "Por favor introduzca el campo requerido - parentesco referencia 2"))
		validada = false;
		}	
			if (EW_this.x_tel_referencia_2 && !EW_hasValue(EW_this.x_tel_referencia_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tel_referencia_2, "TEXT", "Por favor introduzca el campo requerido - telefono referencia 2"))
		validada = false;
		}
				
	}//valida referncia 2
	
	
	
	if (EW_this.x_referencia_com_3 && EW_hasValue(EW_this.x_referencia_com_3, "TEXT" )) {	
		if (EW_this.x_parentesco_ref_3 && !EW_hasValue(EW_this.x_parentesco_ref_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_ref_3, "TEXT", "Por favor introduzca el campo requerido - parentesco referencia 3"))
		validada = false;
		}	
			if (EW_this.x_tel_referencia_3 && !EW_hasValue(EW_this.x_tel_referencia_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tel_referencia_3, "TEXT", "Por favor introduzca el campo requerido - telefono referencia 3"))
		validada = false;
		}
				
	}//valida referncia 3
	
	
	if (EW_this.x_referencia_com_4 && EW_hasValue(EW_this.x_referencia_com_4, "TEXT" )) {	
		if (EW_this.x_parentesco_ref_4 && !EW_hasValue(EW_this.x_parentesco_ref_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_ref_4, "TEXT", "Por favor introduzca el campo requerido - parentesco referencia 4"))
		validada = false;
		}	
			if (EW_this.x_tel_referencia_4 && !EW_hasValue(EW_this.x_tel_referencia_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tel_referencia_4, "TEXT", "Por favor introduzca el campo requerido - telefono referencia 4"))
		validada = false;
		}
				
	}//valida referncia 4
	
	
	
	//se llenaron almenos dos campos
		
	}else{
		alert("Debe introducir al menos dos referencias");		
		
		validada = false;
	
	}


if(EW_this.x_celular.value.length > 0){
	 	 if((EW_this.x_celular.value.length == 10)){			 
			 if (EW_this.x_compania_celular_id && !EW_hasValue(EW_this.x_compania_celular_id, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_compania_celular_id, "SELECT", "Debe seleccionar una compa�ia de telefonos para el campo - celular"))
				validada = false;
				} 
			 
			 }else{
				 alert("Debe introducir el numero de celular a 10 digitos");
			 validada = false;
				 
				 }
	
	 
	 }
	 
  if(EW_this.x_otro_telefono_domicilio_2.value.length > 0){
	 	 if((EW_this.x_otro_telefono_domicilio_2.value.length == 10)){			 
			 if (EW_this.x_compania_celular_id_2 && !EW_hasValue(EW_this.x_compania_celular_id_2, "SELECT" )) {
				if (!EW_onError(EW_this, EW_this.x_compania_celular_id_2, "SELECT", "Debe seleccionar una compa�ia de telefonos para el campo - Otro Celular"))
				validada = false;
				} 
			 
			 }else{
				 alert("Debe introducir el numero de celular a 10 digitos - otro celular - ");
			 validada = false;
				 
				 }
	
	 
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























//termina validaciones del formato adquision de maquinaria
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
<form name="solicitudedit" id="solicitudedit" action="php_solicitudeditMaquinaria.php" method="post" >
<input type="hidden" name="x_win" value="<?php echo $x_win ;?>">
<input type="hidden" name="a_edit" value="U">
<input type="hidden" name="x_solicitud_id" value="<?php echo $x_solicitud_id; ?>"  />
<input type="hidden" name="x_direccion_id" value="<?php echo $x_direccion_id; ?>" />
<input type="hidden" id="x_fecha_investigacion" name="x_fecha_investigacion" value="<?php echo htmlspecialchars(@$currdate); ?>">
<input type="hidden" name="x_actualiza_fecha_investigacion" value="<?php  echo($x_actualiza_fecha_investigacion);?>" />
<?php echo $x_mensaje;
if(!empty($x_mensaje)){exit();
}
?>
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

		if($x_solicitud_status_id < 5){

		$x_estado_civil_idList = "<select name=\"x_solicitud_status_id\" class=\"texto_normal\">";
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
        <td><span class="texto_normal"> <input type="text" name="x_plazo_id" id="x_plazo_id" maxlength="3"   value="<?php echo $x_plazo_id;?>" size="15" onKeyPress="return solonumeros(this,event)" onChange="valorMax();" />
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
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><span class="texto_normal">Actividad Empresarial:</span></td>
        <td colspan="4"><span class="phpmaker">
          <?php
		$x_estado_civil_idList = "<select name=\"x_actividad_id\" class=\"texto_normal\" onchange='act()'>";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `actividad_id`, `descripcion` FROM `actividad`";		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["actividad_id"] == @$x_actividad_id) {
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
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="4" class="texto_normal">      &nbsp;
      <div id="actividad1" class="TG_visible">Específicamente:</div>
      <div id="actividad2" class="TG_hidden">Consistentes en:</div>
      <div id="actividad3" class="TG_hidden">Especificar qu&eacute; y para qu&eacute;:</div>      </td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="4"><textarea name="x_actividad_desc" cols="60" rows="5" id="x_actividad_desc"><?php echo htmlentities($x_actividad_desc); ?></textarea>
          </td>
        </tr>
    </table></td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr></table>
  
  
  <!--aqui inserto el formulario----.................................................................................................-->
  <table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
   <tr>
      <td colspan="11"  align="left" valign="top"class="texto_normal_bold"><div></div></td>
    </tr>
   <tr>
      <td colspan="11"  align="left" valign="top"class="texto_normal_bold"><div><iframe name="aval" src="php_visor_aval.php?key=<?php echo $x_solicitud_id;?>" scrolling="no" style="margin-left:0px; width:150px; height:35px; margin-top:-5px" frameborder="0" allowtransparency="true" id="contenido"></iframe></div></td>
    </tr>
  <tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Datos Personales </td>
    </tr>
     <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td class="texto_normal" >Titular</td>
      <td colspan="8" align="center"><table width="98%">
        <tr>
          <td>
          <input type="text" name="x_nombre" id="x_nombre" value="<?php echo htmlentities($x_nombre_completo)?>" maxlength="250" size="35"  />
          </td>
          <td><input type="text" name="x_apellido_paterno" id="x_apellido_parterno" value="<?php echo htmlentities(@$x_apellido_paterno) ?>" maxlength="250" size="35" /></td>
          <td><input type="text" name="x_apellido_materno" id="x_apellido_materno" value="<?php echo htmlentities(@$x_apellido_materno) ?>" maxlength="250" size="35" /></td>
          </tr>
        <tr>
          <td class="texto_normal"> Nombre</td>
          <td>Apellido Paterno</td>
          <td>Apellido Materno</td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td>RFC</td>
      <td colspan="4"><input type="text" name="x_rfc" id="x_rfc" size="30" maxlength="150" value="<?php echo htmlentities(@$x_rfc) ?>" /></td>
      <td width="172">CURP</td>
      <td colspan="5"><input type="text" name="x_curp" id="x_curp" size="30" maxlength="150" value="<?php echo htmlentities(@$x_curp) ?>" /></td>
    </tr>
    <tr>
      <td>Fecha Nac</td>
      <td colspan="2"><span class="texto_normal">
              <input name="x_fecha_nacimiento" type="text" id="x_fecha_nacimiento" value="<?php echo FormatDateTime(@$x_tit_fecha_nac,7); ?>" size="25"  />
              &nbsp;<img src="../images/ew_calendar.gif" id="cx_fecha_nacimiento" onMouseOver="javascript: Calendar.setup(
            {
            inputField : 'x_fecha_nacimiento', 
            ifFormat : '%d/%m/%Y',
            button : 'cx_fecha_nacimiento' 
            }
            );" style="cursor:pointer;cursor:hand;" />
              </span></td>
      <td width="105">Sexo</td>
      <td width="103"><label>
        <select name="x_sexo" id="x_sexo">
        <option value="1" <?php if($x_sexo == 1){echo("SELECTED");} ?> >Masculino</option> 
		<option value="2" <?php if($x_sexo == 2){echo("SELECTED");} ?>>Femenino</option>
        </select>
      </label></td>
      <td>Integrantes Familia</td>
      <td colspan="5"><input type="text" name="x_integrantes_familia" id="x_integrantes_familia" value="<?php echo htmlspecialchars(@$x_integrantes_familia) ?>" maxlength="30" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
    </tr>
    <tr>
      <td>Dependientes</td>
      <td colspan="2"><input type="text" name="x_dependientes" id="x_dependientes" value="<?php echo htmlspecialchars(@$x_dependientes) ?>"  maxlength="30" size="30"  onKeyPress="return solonumeros(this,event)"/></td>
      <td colspan="2">Correo Electronico</td>
      <td colspan="6"><input type="text" name="x_correo_electronico" id="x_correo_electronico" value="<?php echo htmlspecialchars(@$x_correo_electronico) ?>"  maxlength="100" size="46"/></td>
    </tr>
    <tr>
      <td height="25">Esposo(a)</td>
      <td colspan="10"><input type="text" name="x_esposa" id="x_esposa"  value="<?php echo htmlspecialchars(@$x_esposa) ?>" maxlength="250" size="100"/></td>
    </tr>
     <tr>
      <td>Rol en el hogar</td>
      <td colspan="2"> <?php
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
      <td colspan="2">Fecha inicio de actividad productiva</td>
      <td colspan="6"><input type="text" name="x_fecha_ini_act_prod" id="x_fecha_ini_act_prod" value="<?php echo FormatDateTime(@$x_fecha_ini_act_prod,7);?>"   maxlength="100" size="30"/>
      &nbsp;<img src="../images/ew_calendar.gif" id="cxfecha_ini_act_prod" onclick="javascript: Calendar.setup(
            {
            inputField : 'x_fecha_ini_act_prod', 
           ifFormat : '%d/%m/%Y', 
            button : 'cxfecha_ini_act_prod' 
            }
            );" style="cursor:pointer;cursor:hand;" /></td>
    </tr>
    <tr>
      <td>Destino Credito</td>
      <td colspan="2"><?php
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
      <td colspan="2">&nbsp;</td>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="11" id="tableHead"></td>
    </tr>
    <tr>
      <td colspan="11" id="tableHead"></td>
    </tr>
    <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td colspan="11" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold"> Domicilio </td>
    </tr>
      <tr>
      <td colspan="11" id="tableHead"></td>
    </tr>
    <tr>
      <td colspan="11" id="tableHead"></td>
    </tr>
    <tr>
      <td colspan="11" id="tableHead"></td>
    </tr>
    <tr>
      <td>Calle</td>
      <td colspan="4"><input type="text" name="x_calle_domicilio" id="x_calle_domicilio" value="<?php echo htmlentities($x_calle_domicilio);?>"  maxlength="100" size="60"/></td>
      <td colspan="5">&nbsp;N&uacute;mero exterior&nbsp;&nbsp;<input type="text" name="x_numero_exterior" id="x_numero_exterior" value="<?php echo ($x_numero_exterior);?>"  maxlength="20" size="20"/></td>
    <tr>
      <td>Colonia</td>
      <td colspan="4"><input type="text" name="x_colonia_domicilio" id="x_colonia_domicilio"  value="<?php echo htmlspecialchars(@$x_colonia_domicilio) ?>" maxlength="100" size="50"/></td>
      <td>C&oacute;digo Postal </td>
      <td colspan="5"><input type="text" name="x_codigo_postal_domicilio" id="x_codigo_postal_domicilio" value="<?php echo htmlspecialchars(@$x_codigo_postal_domicilio) ?>"  maxlength="10" size="20"  onKeyPress="return solonumeros(this,event)"/></td>
    </tr>
    <tr>
      <td>Entidad</td>
      <td colspan="4"><?php
		$x_entidad_idList = "<select name=\"x_entidad_domicilio\" id=\"x_entidad_domicilio\" class=\"texto_normal\" onchange=\"showHint(this,'txtHint1', 'x_delegacion_id')\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `entidad_id`, `nombre` FROM `entidad`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["entidad_id"] == @$x_entidad_domicilio) {
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
      <td><div align="left"><span class="texto_normal">
              
        </span><span class="texto_normal">
        <div id="txtHint1" class="texto_normal">
        Del/Mun:
        <?php
		if($x_entidad_domicilio > 0) {
		$x_delegacion_idList = "<select name=\"x_delegacion_id\" class=\"texto_normal\">";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where entidad_id = $x_entidad_domicilio";
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
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
      <td>Ubicacion</td>
      <td colspan="10"><input type="text" name="x_ubicacion_domicilio" id="x_ubicacion_domicilio" value="<?php echo htmlspecialchars(@$x_ubicacion_domicilio) ?>"  maxlength="250" size="50"/></td>
    </tr>
    <tr>
      <td>Tipo Vivienda</td>
      <td colspan="4">
      <?php
		$x_vivienda_tipo_idList = "<select name=\"x_tipo_vivienda\" id=\"x_tipo_vivienda\"  class=\"texto_normal\" onchange=\"viviendatipo('1')\">";
		$x_vivienda_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `vivienda_tipo_id`, `descripcion` FROM `vivienda_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_vivienda_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["vivienda_tipo_id"] == @$x_tipo_vivienda) {
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
      
      
      
      
      </td>
      <td>Antiguedad (a&ntilde;os)</td>
      <td colspan="5"><input type="text" name="x_antiguedad" id="x_antiguedad"  value="<?php echo htmlspecialchars(@$x_antiguedad) ?>" maxlength="10" size="20"/></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="4">&nbsp;</td>
      <td>Tel. Arrendatario</td>
      <td colspan="5"><input type="text" name="x_tel_arrendatario_domicilio" id="x_tel_arrendatario_domicilio"  value="<?php echo htmlspecialchars(@$x_tel_arrendatario_domicilio) ?>" maxlength="20" size="20"/></td>
    </tr>
    <tr>
      <td height="24">Tel Domicilio</td>
      <td width="133"><input type="text" name="x_telefono_domicilio" id="x_telefono_domicilio"  value="<?php echo htmlspecialchars(@$x_telefono_domicilio) ?>" maxlength="50" size="25"/></td>
      <td width="76">Otro Tel</td>
      <td colspan="2"><input type="text" name="x_otro_tel_domicilio_1" id="x_otro_tel_domicilio_1"  value="<?php echo htmlspecialchars(@$x_otro_tel_domicilio_1) ?>" maxlength="50" size="25"/></td>
      <td>Renta Mensual</td>
      <td colspan="5"><input type="text" name="x_renta_mensula_domicilio" id="x_renta_mensula_domicilio" value="<?php echo number_format(@$x_renta_mensula_domicilio,0,'.','') ?>" maxlength="25" size="20"  onKeyPress="return solonumeros(this,event)"/></td>
    </tr>
     <tr>
      <td>Celular</td>
      <td><input type="text" name="x_celular" id="x_celular" value="<?php echo $x_celular;?>"  maxlength="10" size="25"/></td>
      <td>Compa&ntilde;ia</td>
      <td colspan="2"><?php
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
		?></td>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td>Otro Celular</td>
      <td><input type="text" name="x_otro_telefono_domicilio_2" id="x_otro_telefono_domicilio_2"  value="<?php echo $x_otro_telefono_domicilio_2;?>" maxlength="50" size="25"/></td>
      <td>Compa&ntilde;ia</td>
      <td colspan="2"><?php
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
		?></td>
      <td colspan="6">&nbsp;</td>
    </tr>
      <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Datos del Negocio</td>
    </tr>
     <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td>Giro Negocio</td>
      <td colspan="4"> <?php
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
      <td><p>Tipo Negocio</p></td>
      <td colspan="5"> <?php
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
    <tr>
      <td>Atiende Titular</td>
      <td colspan="4"><select name="x_atiende_titular">
      <option>Seleccione</option>
		<option value="si" <?php if($x_atiende_titular == "si"){?> selected="selected" <?php } ?> >Si</option>
        <option value="no" <?php if($x_atiende_titular == "no"){?> selected="selected" <?php } ?>>No</option>
      </select></td>
      <td><p>No.Personas trabajando</p></td>
      <td colspan="5"><input type="text" name="x_personas_trabajando" value="<?php echo($x_personas_trabajando);?>" maxlength="20" size="10" /></td>
    </tr>
    <tr>
      <td>Calle</td>
      <td colspan="10"><input type="text" name="x_calle_negocio" id="x_calle_negocio" value="<?php echo htmlspecialchars(@$x_calle_negocio) ?>"  maxlength="250" size="100"/></td>
    </tr>
    <tr>
      <td>Colonia</td>
      <td colspan="4"><input type="text" name="x_colonia_negocio" id="x_colonia_negocio"  value="<?php echo htmlspecialchars(@$x_colonia_negocio) ?>" maxlength="250" size="70"/></td>
      <td><p>C&oacute;digo Postal</p></td>
      <td colspan="5">
      <input type="text" name="x_codigo_postal_negocio" id="x_codigo_postal_negocio" value="<?php echo htmlspecialchars(@$x_codigo_postal_negocio)?>"  maxlength="25" size="30"  onKeyPress="return solonumeros(this,event)"/></td>
    </tr>
    <tr>
      <td>Entidad</td>
      <td colspan="4"><?php
		$x_entidad_idList2 = "<select name=\"x_entidad_negocio\" id=\"x_entidad_negocio\" class=\"texto_normal\" onchange=\"showHint(this,'txtHint2', 'x_delegacion_id2')\" >";
		$x_entidad_idList2 .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `entidad_id`, `nombre` FROM `entidad`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList2 .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["entidad_id"] == @$x_entidad_negocio) {
					$x_entidad_idList2 .= "' selected";
				}
				$x_entidad_idList2 .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList2 .= "</select>";
		echo $x_entidad_idList2;
		?>
    </td>
      <td><div align="left"><span class="texto_normal">
		<input type="hidden" name="x_delegacion_id_temp" value="" />

        </span><span class="texto_normal">
        <div id="txtHint2" class="texto_normal">        
		Del/Mun:        
        <?php
		if($x_entidad_negocio > 0 ){
		$x_delegacion_idList = "<select name=\"x_delegacion_id2\" class=\"texto_normal\">";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where entidad_id = $x_entidad_negocio";
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
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
      <td>Ubicacion</td>
      <td colspan="10"><input type="text" name="x_ubicacion_negocio" id="x_ubicacion_negocio" value="<?php echo htmlspecialchars(@$x_ubicacion_negocio) ?>" maxlength="250" size="100"/></td>
    </tr>
    <tr>
      <td>Tipo Local</td>
      <td colspan="4">
      <?php
		$x_vivienda_tipo_idList = "<select name=\"x_tipo_local_negocio\" id=\"x_tipo_local_negocio\"  class=\"texto_normal\" onchange=\"viviendatipo('1')\">";
		$x_vivienda_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `vivienda_tipo_id`, `descripcion` FROM `vivienda_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_vivienda_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["vivienda_tipo_id"] == @$x_tipo_local_negocio) {
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
      
      
      
      
      
      </td>
      <td>Antiguedad (a&ntilde;os)</td>
      <td colspan="5"><input type="text" name="x_antiguedad_negocio" id="x_antiguedad_negocio"  value="<?php echo htmlspecialchars(@$x_antiguedad_negocio) ?>" maxlength="10" size="20"/></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="4">&nbsp;</td>
      <td>Tel. Arrendatario</td>
      <td colspan="5"><input type="text" name="x_tel_arrendatario_negocio" id="x_tel_arrendatario_negocio"  value="<?php echo htmlspecialchars(@$x_tel_arrendatario_negocio) ?>" maxlength="20" size="20"/></td>
    </tr>
    <tr>
      <td>Tel Negocio</td>
      <td colspan="4"><input type="text" name="x_tel_negocio" id="x_tel_negocio"  value="<?php echo htmlspecialchars(@$x_tel_negocio) ?>" maxlength="50" size="25"/></td>
      <td>Renta Mensual</td>
      <td colspan="5"><input type="text" name="x_renta_mensual" id="x_renta_mensual"  value="<?php echo number_format(@$x_renta_mensual, 0, '.', '') ?>" maxlength="50" size="20"  onKeyPress="return solonumeros(this,event)"/></td>
    </tr>
      <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Solicitud de Compra</td>
    </tr>
     <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td colspan="11"><center><textarea cols="80" rows="4" id="x_solicitud_compra" name="x_solicitud_compra"><?php echo @$x_solicitud_compra; ?></textarea></center></td>
    </tr>
    <tr>
      <td colspan="11">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="11">&nbsp;</td>
  </tr></table>
   
    <table width="90%">
      <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr></table>
    <table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Referencias Comerciales</td>
    </tr>
     <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td height="36" colspan="2"><table width="300"><tr>
        <td width="48">1.-</td>
        <td width="276"><input type="text" name="x_referencia_com_1" id="x_referencia_com_1" value="<?php echo htmlspecialchars(@$x_referencia_com_1) ?>"  maxlength="250" size="40"/></td></tr></table></td>
      <td colspan="2"><table width="160" height="29"><tr>
        <td width="36">Tel</td>
        <td width="137"><input type="text" name="x_tel_referencia_1" id="x_tel_referencia_1"  value="<?php echo htmlspecialchars(@$x_tel_referencia_1) ?>" maxlength="20" size="30"/></td></tr></table></td>
      <td width="107">Parentesco</td>
      <td colspan="6">
         <?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_ref_1\" id=\"x_parentesco_ref_1\" class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_ref_1) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?>
      
      
      
      
      
      
      
      </td>
      </tr>
    <tr>
      <td colspan="2"><table width="300"><tr>
        <td width="48">2.-</td>
        <td width="277"><input type="text" name="x_referencia_com_2" id="x_referencia_com_2"  value="<?php echo htmlspecialchars(@$x_referencia_com_2) ?>" maxlength="250" size="40"/></td></tr></table></td>
      <td colspan="2"><table width="160"><tr>
        <td width="36">Tel</td>
        <td width="137"><input type="text" name="x_tel_referencia_2" id="x_tel_referencia_2"  value="<?php echo htmlspecialchars(@$x_tel_referencia_2) ?>" maxlength="20" size="30"/></td></tr></table></td>
      <td>Parentesco</td>
      <td colspan="6">
      <?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_ref_2\" id=\"x_parentesco_ref_2\" class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_ref_2) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?>
      
      
      
      </td>
      </tr>
    <tr>
      <td colspan="2"><table width="300"><tr>
        <td width="48">3.-</td>
        <td width="276"><input type="text" name="x_referencia_com_3" id="x_referencia_com_3"  value="<?php echo htmlspecialchars(@$x_referencia_com_3) ?>" maxlength="250" size="40"/></td></tr></table></td>
      <td colspan="2"><table width="183"><tr>
        <td width="34">Tel</td>
        <td width="137"><input type="text" name="x_tel_referencia_3" id="x_tel_referencia_3"  value="<?php echo htmlspecialchars(@$x_tel_referencia_3) ?>"  maxlength="20" size="30"/></td></tr></table></td>
      <td>Parentesco</td>
      <td colspan="6">
      <?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_ref_3\" id=\"x_parentesco_ref_3\" class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_ref_3) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?>
      
      
      
      </td>
      </tr>
    <tr>
      <td colspan="2"><table width="300"><tr>
        <td width="48">4.-</td>
        <td width="276"><input type="text" name="x_referencia_com_4" id="x_referencia_com_4"  value="<?php echo htmlspecialchars(@$x_referencia_com_4) ?>" maxlength="250" size="40"/></td></tr></table></td>
     <td colspan="2"><table width="160"><tr>
        <td width="35">Tel</td>
        <td width="137"><input type="text" name="x_tel_referencia_4" id="x_tel_referencia_4"  value="<?php echo htmlspecialchars(@$x_tel_referencia_4) ?>" maxlength="20" size="30"/></td></tr></table></td>
      <td>Parentesco</td>
      <td colspan="6">
      <?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_ref_4\" id=\"x_parentesco_ref_4\" class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_ref_4) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}

		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?>
      
      
      
      
      </td>
      </tr>
    <tr>
      <td width="201">&nbsp;</td>
      <td width="108">&nbsp;</td>
      <td width="159">&nbsp;</td>
      <td width="69">&nbsp;</td>
      <td>&nbsp;</td>
      <td width="51">&nbsp;</td>
      <td width="140">&nbsp;</td>
      <td width="3">&nbsp;</td>
      <td width="3">&nbsp;</td>
      <td width="3">&nbsp;</td>
      <td width="48">&nbsp;</td>
    </tr>
    
</table>
   
    

  




<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="22"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Ingresos Familiares</td>
      </tr>
      <tr>
        <td colspan="11" id="tableHead2"><p></p></td>
      </tr>
      <tr>
        <td width="147">Ingreso del Negocio</td>
        <td colspan="4"><input type="text" name="x_ing_fam_negocio" id="x_ing_fam_negocio"  value="<?php echo number_format(@$x_ing_fam_negocio, 0, '.', '') ?>" maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">&nbsp;</td>
        <td width="76">&nbsp;</td>
        <td width="52">&nbsp;</td>
        <td width="69">&nbsp;</td>
        <td width="56">&nbsp;</td>
        <td width="127">&nbsp;</td>
        <td width="23" colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Otros Ingresos TH</td>
        <td colspan="4"><input type="text" name="x_ing_fam_otro_th" id="x_ing_fam_otro_th" value="<?php echo number_format(@$x_ing_fam_otro_th, 0, '.', '') ?>"  maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_ing_fam_cuales_1" id="x_ing_fam_cuales_1"   value="<?php echo htmlspecialchars(@$x_ing_fam_cuales_1) ?>" maxlength="250" size="35"  /></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Ingreso Familiar 1</td>
        <td colspan="4"><input type="text" name="x_ing_fam_1" id="x_ing_fam_1"   value="<?php echo number_format(@$x_ing_fam_1, 0, '.', '') ?>" maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_ing_fam_cuales_2" id="x_ing_fam_cuales_2" value="<?php echo htmlspecialchars(@$x_ing_fam_cuales_2) ?>" maxlength="250" size="35" /></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Ingreso Familiar  2</td>
        <td colspan="4"><input type="text" name="x_ing_fam_2" id="x_ing_fam_2"  value="<?php echo number_format(@$x_ing_fam_2, 0, '.', '') ?>" maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_ing_fam_cuales_3" id="x_ing_fam_cuales_3"  value="<?php echo htmlspecialchars(@$x_ing_fam_cuales_3) ?>" maxlength="250" size="35"  /></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Deuda 1</td>
        <td colspan="4"><input type="text" name="x_ing_fam_deuda_1" id="x_ing_fam_deuda_1"  value="<?php echo number_format(@$x_ing_fam_deuda_1, 0, '.', '') ?>" maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_ing_fam_cuales_4" id="x_ing_fam_cuales_4"  value="<?php echo htmlspecialchars(@$x_ing_fam_cuales_4) ?>" maxlength="250" size="35" /></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Deuda 2</td>
        <td colspan="4"><input type="text" name="x_ing_fam_deuda_2" id="x_ing_fam_deuda_2"  value="<?php echo number_format(@$x_ing_fam_deuda_2, 0, '.', '') ?>" maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_ing_fam_cuales_5" id="x_ing_fam_cuales_5" value="<?php echo htmlspecialchars(@$x_ing_fam_cuales_5) ?>"  maxlength="250" size="35"  /></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Total</td>
        <td colspan="4"><input type="text" name="x_ing_fam_total" id="x_ing_fam_total"  value="<?php echo number_format(@$x_ing_fam_total, 0, '.', '') ?>" maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">&nbsp;</td>
        <td width="76">&nbsp;</td>
        <td width="52">&nbsp;</td>
        <td width="69">&nbsp;</td>
        <td width="56">&nbsp;</td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">&nbsp;</td>
        <td width="71">&nbsp;</td>
        <td width="60">&nbsp;</td>
        <td width="72">&nbsp;</td>
        <td width="65">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
        <td width="76">&nbsp;</td>
        <td width="52">&nbsp;</td>
        <td width="69">&nbsp;</td>
        <td width="56">&nbsp;</td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="22" id="tableHead2"><p></p></td>
      </tr>
      <tr>
        <td colspan="22"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Flujos del Negocio</td>
      </tr>
      <tr>
        <td colspan="22" id="tableHead2"><p></p></td>
      </tr>
      <tr>
        <td width="147">Ventas</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_ventas" id="x_flujos_neg_ventas" value="<?php echo number_format(@$x_flujos_neg_ventas, 0, '.', '') ?>"  maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">&nbsp;</td>
        <td width="76">&nbsp;</td>
        <td width="52">&nbsp;</td>
        <td width="69">&nbsp;</td>
        <td width="56">&nbsp;</td>
        <td width="127">&nbsp;</td>
        <td width="23" colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Proveedor 1</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_proveedor_1" id="x_flujos_neg_proveedor_1" value="<?php echo number_format(@$x_flujos_neg_proveedor_1, 0, '.', '') ?>"  maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_cual_1" id="x_flujos_neg_cual_1"  value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_1) ?>" maxlength="100" size="35"/></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Proveedor 2</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_proveedor_2" id="x_flujos_neg_proveedor_2"  value="<?php echo number_format(@$x_flujos_neg_proveedor_2, 0, '.', '') ?>" maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_cual_2" id="x_flujos_neg_cual_2" value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_2) ?>"  maxlength="100" size="35"/></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Proveedor 3</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_proveedor_3" id="x_flujos_neg_proveedor_3"  value="<?php echo number_format(@$x_flujos_neg_proveedor_3, 0, '.', '') ?>" maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_cual_3" id="x_flujos_neg_cual_3"  value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_3) ?>" maxlength="100" size="35"/></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Proveedor 4</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_proveedor_4" id="x_flujos_neg_proveedor_4"  value="<?php echo number_format(@$x_flujos_neg_proveedor_4, 0, '.', '') ?>" maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_cual_4" id="x_flujos_neg_cual_4"  value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_4) ?>" maxlength="100" size="35"/></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Gasto 1</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_gasto_1" id="x_flujos_neg_gasto_1" value="<?php echo number_format(@$x_flujos_neg_gasto_1, 0, '.', '') ?>"  maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_cual_5" id="x_flujos_neg_cual_5"  value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_5) ?>" maxlength="100" size="35"/></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Gasto 2</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_gasto_2" id="x_flujos_neg_gasto_2"  value="<?php echo number_format(@$x_flujos_neg_gasto_2, 0, '.', '') ?>" maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_cual_6" id="x_flujos_neg_cual_6"  value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_6) ?>" maxlength="100" size="35"/></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Gasto 3</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_gasto_3" id="x_flujos_neg_gasto_3"  value="<?php echo number_format(@$x_flujos_neg_gasto_3, 0, '.', '') ?>" maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_cual_7" id="x_flujos_neg_cual_7"  value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_7) ?>" maxlength="100" size="35"/></td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="147">Ingreso Negocio</td>
        <td colspan="4"><input type="text" name="x_ingreso_negocio" id="x_ingreso_negocio"  value="<?php echo number_format(@$x_ingreso_negocio, 0, '.', '') ?>" maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)"/></td>
        <td colspan="2">&nbsp;</td>
        <td colspan="4">&nbsp;</td>
        <td width="127">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
 
        <tr></tr>
        <tr>
          <td colspan="19" id="tableHead2"><p></p></td>
        </tr>
        <td colspan="19"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Inversion del Negocio</td>
        </tr>
        <tr>
          <td colspan="19" id="tableHead2"><p></p></td>
        </tr>
        <tr>
          <td height="30" colspan="5"><center>
            FIJA
          </center></td>
          <td colspan="2">&nbsp;</td>
          <td colspan="2"><center>
            VARIABLE
          </center></td>
          <td width="21" colspan="10">&nbsp;</td>
        </tr>
        <tr>
          <td width="226">Concepto</td>
          <td colspan="4">Valor</td>
          <td colspan="2">&nbsp;</td>
          <td width="267">Concepto</td>
          <td width="171" colspan="-2">Valor</td>
          <td colspan="10">&nbsp;</td>
        </tr>
        <tr>
          <td width="226"><input type="text" name="x_inv_neg_fija_conc_1" id="x_inv_neg_fija_conc_1" value="<?php echo htmlspecialchars(@$x_inv_neg_fija_conc_1) ?>"  maxlength="250" size="35"/></td>
          <td colspan="4"><input type="text" name="x_inv_neg_fija_valor_1" id="x_inv_neg_fija_valor_1"  value="<?php echo number_format(@$x_inv_neg_fija_valor_1, 0, '.', '') ?>"  maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          <td colspan="2">&nbsp;</td>
          <td><input type="text" name="x_inv_neg_var_conc_1" id="x_inv_neg_var_conc_1"  value="<?php echo htmlspecialchars(@$x_inv_neg_var_conc_1) ?>" maxlength="250" size="35"/></td>
          <td width="171" colspan="-2"><input type="text" name="x_inv_neg_var_valor_1" value="<?php echo number_format(@$x_inv_neg_var_valor_1, 0, '.', '') ?>" id="x_inv_neg_var_valor_1"  maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          <td colspan="10">&nbsp;</td>
        </tr>
        <tr>
          <td width="226"><input type="text" name="x_inv_neg_fija_conc_2" id="x_inv_neg_fija_conc_2"  value="<?php echo htmlspecialchars(@$x_inv_neg_fija_conc_2) ?>" maxlength="250" size="35"/></td>
          <td colspan="4"><input type="text" name="x_inv_neg_fija_valor_2" id="x_inv_neg_fija_valor_2" value="<?php echo number_format(@$x_inv_neg_fija_valor_2, 0, '.', '') ?>"  maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          <td colspan="2">&nbsp;</td>
          <td><input type="text" name="x_inv_neg_var_conc_2" id="x_inv_neg_var_conc_2"  value="<?php echo htmlspecialchars(@$x_inv_neg_var_conc_2) ?>" maxlength="250" size="35"/></td>
          <td width="171" colspan="-2"><input type="text" name="x_inv_neg_var_valor_2"  value="<?php echo number_format(@$x_inv_neg_var_valor_2, 0, '.', '') ?>" id="x_inv_neg_var_valor_2"  maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          <td colspan="10">&nbsp;</td>
        </tr>
        <tr>
          <td width="226"><input type="text" name="x_inv_neg_fija_conc_3" id="x_inv_neg_fija_conc_3"   value="<?php echo htmlspecialchars(@$x_inv_neg_fija_conc_3) ?>" maxlength="250" size="35"/></td>
          <td colspan="4"><input type="text" name="x_inv_neg_fija_valor_3" id="x_inv_neg_fija_valor_3"  value="<?php echo number_format(@$x_inv_neg_fija_valor_3, 0, '.', '') ?>" maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          <td colspan="2">&nbsp;</td>
          <td><input type="text" name="x_inv_neg_var_conc_3" id="x_inv_neg_var_conc_3"  value="<?php echo htmlspecialchars(@$x_inv_neg_var_conc_3) ?>" maxlength="250" size="35"/></td>
          <td width="171" colspan="-2"><input type="text" name="x_inv_neg_var_valor_3" id="x_inv_neg_var_valor_3"  value="<?php echo number_format(@$x_inv_neg_var_valor_3, 0, '.', '') ?>" maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          <td colspan="10">&nbsp;</td>
        </tr>
        <tr>
          <td width="226"><input type="text" name="x_inv_neg_fija_conc_4" id="x_inv_neg_fija_conc_4"   value="<?php echo htmlspecialchars(@$x_inv_neg_fija_conc_4) ?>" maxlength="250" size="35"/></td>
          <td colspan="4"><input type="text" name="x_inv_neg_fija_valor_4" id="x_inv_neg_fija_valor_4" value="<?php echo number_format(@$x_inv_neg_fija_valor_4, 0, '.', '') ?>" maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          <td colspan="2">&nbsp;</td>
          <td><input type="text" name="x_inv_neg_var_conc_4" id="x_inv_neg_var_conc_4" value="<?php echo htmlspecialchars(@$x_inv_neg_var_conc_4) ?>"  maxlength="250" size="35"/></td>
          <td width="171" colspan="-2"><input type="text" name="x_inv_neg_var_valor_4" id="x_inv_neg_var_valor_4"  value="<?php echo number_format(@$x_inv_neg_var_valor_4, 0, '.', '') ?>" maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          <td colspan="10">&nbsp;</td>
        </tr>
        <tr>
          <td width="226" align="right">Total</td>
          <td colspan="4"><input type="text" name="x_inv_neg_total_fija" id="x_inv_neg_total_fija"  value="<?php echo number_format(@$x_inv_neg_total_fija, 0, '.', '') ?>" maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          <td colspan="2">&nbsp;</td>
          <td align="right">TotalL</td>
          <td width="171" colspan="-2"><input type="text" name="x_inv_neg_total_var" id="x_inv_neg_total_var"   value="<?php echo number_format(@$x_inv_neg_total_var, 0, '.', '') ?>"maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          <td colspan="10">&nbsp;</td>
        </tr>
        <tr>
          <td width="226">&nbsp;</td>
          <td colspan="4">&nbsp;</td>
          <td colspan="2">&nbsp;</td>
          <td align="right"></td>
          <td width="171" colspan="-2"></td>
          <td colspan="10">&nbsp;</td>
        </tr>
        <tr>
          <td width="226">&nbsp;</td>
          <td colspan="4">&nbsp;</td>
          <td colspan="2">&nbsp;</td>
          <td align="right">ACTIVOS TOTALES:</td>
          <td width="171" colspan="-2"><input type="text" name="x_inv_neg_activos_totales" id="x_inv_neg_activos_totales" value="<?php echo number_format(@$x_inv_neg_activos_totales, 0, '.', '') ?>" maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)"/></td>
          <td colspan="10">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="19" id="tableHead2"><p></p></td>
        </tr>
        
     
       
      </table>
      
      <table width="870" border="0" align="center" cellpadding="0" cellspacing="0">
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
      
      
      
      
      </table>
      
      
  
  
  <!-- aqui termina.....................................................................................................................-->
  	
	
         
          
             
            
        
            
             
             
            
     <table><tr><td>  
     <?php if($_SESSION["crm_UserRolID"] < 4){ 
/*
capacidad de pago mensual TITULAR (CPM)----------------------------------------------------------------

ingresos1 = ventas mensuales - proveedor 1 - proveedor 2 - proveedor 3 - otros gastos - empleados - renta negocio)

ingresos2 = recibos de nomina

ingresos =  ingresos1 + ingresos2

cpm = (ingresos + otros ingresos + ingresos familiares) - (personas dependientes * (700)  + credito hipotecario o renta(solicitar datos del arrendatario: nombre y telefono)
 + costos de vivienda(200) + tel(400))
 
capacidad de pago semanal (cps) = (cpm / 4) * .40
  
Esto solo lo ve el coordinador de credito.
*/
  			/*$x_ventas_menusuales = ($x_ingresos_negocio != "") ? $x_ingresos_negocio : 0;
  			$x_salario = ($x_salario_mensual != "") ? $x_salario_mensual : 0;			
  			$x_otros_ing = ($x_otros_ingresos != "") ? $x_otros_ingresos : 0;			
  			$x_ing_fam1 = ($x_ingresos_familiar_1 != "") ? $x_ingresos_familiar_1 : 0;						
  			$x_ing_fam2 = ($x_ingresos_familiar_2 != "") ? $x_ingresos_familiar_2 : 0;									

  			$x_prov1 = ($x_gastos_prov1 != "") ? $x_gastos_prov1 : 0;          
  			$x_prov2 = ($x_gastos_prov2 != "") ? $x_gastos_prov2 : 0;          
  			$x_prov3 = ($x_gastos_prov3 != "") ? $x_gastos_prov3 : 0;          			
  			$x_otrop = ($x_otro_prov != "") ? $x_otro_prov : 0;          						
  			$x_empleados = ($x_gastos_empleados != "") ? $x_gastos_empleados : 0;          						
  			$x_ren_neg = ($x_gastos_renta_negocio != "") ? $x_gastos_renta_negocio : 0;          			
  			$x_ren_cas = ($x_gastos_renta_casa != "") ? $x_gastos_renta_casa : 0;          						
  			$x_hipo = ($x_gastos_credito_hipotecario != "") ? $x_gastos_credito_hipotecario : 0;
  			$x_gas_otros = ($x_gastos_otros != "") ? $x_gastos_otros : 0;          									
	
			$x_dep = ($x_numero_hijos_dep != "") ? $x_numero_hijos_dep : 0;
			$x_ren = $x_ren_cas + $x_hipo;
			

			$x_ingresos1 = ($x_ventas_menusuales - ($x_prov1 + $x_prov2 + $x_prov3 + $x_otrop + $x_gas_otros + $x_empleados + $x_ren_neg));
			
			$x_ingresos2 = $x_salario;

			$x_ingresos = $x_ingresos1 + $x_ingresos2;

			$x_cpm = (($x_ingresos + $x_otros_ing + $x_ing_fam1 + $x_ing_fam2) - (($x_dep * 700) + $x_ren + 200 + 300));
			
			$x_cps = ($x_cpm / 4) * .40;*/


			$x_ing_fam_total = ($x_ing_fam_total != "") ? $x_ing_fam_total : 0;
			$x_ingreso_negocio = ($x_ingreso_negocio != "") ? $x_ingreso_negocio : 0;
			$x_renta_mensula_domicilio = ($x_renta_mensula_domicilio != "") ? $x_renta_mensula_domicilio : 0;
			$x_renta_mensual = ($x_renta_mensual != "") ? $x_renta_mensual : 0;
			$x_dependientes = ($x_dependientes != "") ? $x_dependientes : 0;
			
			
			$x_ingresos = $x_ing_fam_total + $x_ingreso_negocio;
			$x_rentas = $x_renta_mensula_domicilio + $x_renta_mensual +200 +300;
			$x_gasto_hijos = ($x_dependientes * 700);


			$x_cpm = $x_ingresos - ($x_rentas+ $x_gasto_hijos );
			$x_cps = ($x_cpm / 4) * .40;



			echo "<b>
			Capacidad de Pago Mensual: ".FormatNumber(@$x_cpm,0,2,0,1)."<br>
			Capacidad de Pago Semanal: ".FormatNumber(@$x_cps,0,2,0,1)."		
			</b>
			";



/*
capacidad de pago mensual AVAL (CPM)----------------------------------------------------------------

ingresos1 = ventas mensuales - proveedor 1 - proveedor 2 - proveedor 3 - otros gastos - empleados - renta negocio)

ingresos2 = recibos de nomina

ingresos =  ingresos1 + ingresos2

cpm = (ingresos + otros ingresos + ingresos familiares) - (personas dependientes * (700)  + credito hipotecario o renta(solicitar datos del arrendatario: nombre y telefono)
 + costos de vivienda(200) + tel(400))
 
capacidad de pago semanal (cps) = (cpm / 4) * .40
  
Esto solo lo ve el coordinador de credito.
*/
  			$x_ventas_menusuales = ($x_ingresos_mensuales != "") ? $x_ingresos_mensuales : 0;
//  			$x_salario = ($x_salario_mensual != "") ? $x_salario_mensual : 0;			
  			$x_otros_ing = ($x_otros_ingresos_aval != "") ? $x_otros_ingresos_aval : 0;			
  			$x_ing_fam1 = ($x_ingresos_familiar_1_aval != "") ? $x_ingresos_familiar_1_aval : 0;						
  			$x_ing_fam2 = ($x_ingresos_familiar_2_aval != "") ? $x_ingresos_familiar_2_aval : 0;									

  			$x_prov1 = ($x_gastos_prov1_aval != "") ? $x_gastos_prov1_aval : 0;          
  			$x_prov2 = ($x_gastos_prov2_aval != "") ? $x_gastos_prov2_aval : 0;          
  			$x_prov3 = ($x_gastos_prov3_aval != "") ? $x_gastos_prov3_aval : 0;          			
  			$x_otrop = ($x_otro_prov_aval != "") ? $x_otro_prov_aval : 0;          						
  			$x_empleados = ($x_gastos_empleados_aval != "") ? $x_gastos_empleados_aval : 0;          						
  			$x_ren_neg = ($x_gastos_renta_negocio_aval != "") ? $x_gastos_renta_negocio_aval : 0;          			
  			$x_ren_cas = ($x_gastos_renta_casa2 != "") ? $x_gastos_renta_casa2 : 0;          						
  			$x_hipo = ($x_gastos_credito_hipotecario_aval != "") ? $x_gastos_credito_hipotecario_aval : 0;
  			$x_gas_otros = ($x_gastos_otros_aval != "") ? $x_gastos_otros_aval : 0;          									
	
			$x_dep = ($x_numero_hijos_dep_aval != "") ? $x_numero_hijos_dep_aval : 0;
			$x_ren = $x_ren_cas + $x_hipo;
			

			$x_ingresos1 = ($x_ventas_menusuales - ($x_prov1 + $x_prov2 + $x_prov3 + $x_otrop + $x_gas_otros + $x_empleados + $x_ren_neg));
			
//			$x_ingresos2 = $x_salario;
			$x_ingresos2 = 0;

			$x_ingresos = $x_ingresos1 + $x_ingresos2;

			$x_cpm = (($x_ingresos + $x_otros_ing + $x_ing_fam1 + $x_ing_fam2) - (($x_dep * 700) + $x_ren + 200 + 300));
			
			$x_cps = ($x_cpm / 4) * .40;

			/*echo "<b>
			Capacidad de Pago Mensual AVAL: ".FormatNumber(@$x_cpm,0,2,0,1)."<br>
			Capacidad de Pago Semanal AVAL: ".FormatNumber(@$x_cps,0,2,0,1)."		
			</b>
			";*/
			} ?></td>

    <td><div align="center">
      
    </div></td>
    <td>&nbsp;</td>
  </tr>
</table>

<table align="center">
<tr><td> <input name="button" type="button" class="boton_medium" onclick="window.open('/esf/modulos/tipoCuenta/formatos/php_solicitudMaquinaria_print.php?solicitud_id=<?php echo $x_solicitud_id; ?>','Solicitud','width=700,height=500,left=150,top=150,scrollbars=yes');" value="Imprimir" /></td></tr>
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

		// Get the field contents $x_cliente_id 
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
		$GLOBALS["x_actividad_id"] = $row["actividad_id"];
		$GLOBALS["x_actividad_desc"] = $row["actividad_desc"];				

		if(intval($row["solicitud_status_id"]) == 2){			
			$GLOBALS["x_actualiza_fecha_investigacion"] = "no";				
			}else{			
				$GLOBALS["x_actualiza_fecha_investigacion"] = "si";					
				}




		//TIPO DE SOLICITUD
		$sqlTC ="SELECT descripcion FROM  credito_tipo JOIN solicitud ON(solicitud.credito_tipo_id = credito_tipo.credito_tipo_id ) ";
		$sqlTC.= "WHERE solicitud.solicitud_id = $x_solicitud_id";
		
		$rsTC = phpmkr_query($sqlTC,$conn) or die("Failed to execute query: TIPO CREDITO" . phpmkr_error() . '<br>SQL: ' . $sSql);
		$rowTC = phpmkr_fetch_array($rsTC);
		
		$GLOBALS["x_tipo_credito_descripcion"] = $rowTC["descripcion"];
		
		
		//CLIENTE		
		$sSql = "select cliente.* from cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id where solicitud_cliente.solicitud_id = $x_solicitud_id";
		$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row2 = phpmkr_fetch_array($rs2);
		$GLOBALS["x_cliente_id"] = $row2["cliente_id"];		
		$GLOBALS["x_usuario_id"] = $row2["usuario_id"];
		$GLOBALS["x_nombre_completo"] = $row2["nombre_completo"];
		
		$GLOBALS["x_apellido_paterno"] = $row2["apellido_paterno"];		
		$GLOBALS["x_apellido_materno"] = $row2["apellido_materno"];				
		$GLOBALS["x_rfc"] = $row2["rfc"];
		$GLOBALS["x_curp"] = $row2["curp"];						
		$GLOBALS["x_tit_fecha_nac"] = $row2["fecha_nac"];					
		$GLOBALS["x_esposa"] = $row2["nombre_conyuge"];
		$GLOBALS["x_tipo_negocio"] = $row2["tipo_negocio"];
		$GLOBALS["x_edad"] = $row2["edad"];
		$GLOBALS["x_sexo"] = $row2["sexo"];
		$GLOBALS["x_estado_civil_id"] = $row2["estado_civil_id"];
		$GLOBALS["x_integrantes_familia"] = $row2["numero_hijos"];
		$GLOBALS["x_dependientes"] = $row2["numero_hijos_dep"];		
		$GLOBALS["x_nombre_conyuge"] = $row2["nombre_conyuge"];
		$GLOBALS["x_correo_electronico"] = $row2["email"];		
		$GLOBALS["x_nacionalidad_id"] = $row2["nacionalidad_id"];				
		$GLOBALS["x_empresa"] = $row2["empresa"];		
		$GLOBALS["x_puesto"] = $row2["puesto"];		
		$GLOBALS["x_fecha_contratacion"] = $row2["fecha_contratacion"];		
		$GLOBALS["x_salario_mensual"] = $row2["salario_mensual"];	
		
		$GLOBALS["x_rol_hogar_id"] = $row2["rol_hogar_id"];
		$GLOBALS["x_fecha_ini_act_prod"] = $row2["fecha_ini_act_prod"];
		
		
		$sSql = "select * from negocio where cliente_id = ".$GLOBALS["x_cliente_id"];
		$rsn5 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$rown5 = phpmkr_fetch_array($rsn5);
		$GLOBALS["x_negocio_id"] = $rown5["negocio_id"];
		$GLOBALS["x_giro_negocio_id"] = $rown5["giro_negocio_id"];
		$GLOBALS["x_tipo_inmueble_id"] = $rown5["tipo_inmueble_id"];
		$GLOBALS["x_atiende_titular"] = $rown5["atiende_titular"];
		$GLOBALS["x_personas_trabajando"] = $rown5["personas_trabajando"];
		$GLOBALS["x_destino_credito_id"] = $rown5["destino_credito_id"];
		
		
		
		//valor de la llave cliente_id
		//$sqlClienteid = "SELECT cliente_id FROM solicitud_cliente WHERE solicitud_cliente ="


		$sqlD = "SELECT * FROM direccion WHERE cliente_id = ".$GLOBALS["x_cliente_id"]." and direccion_tipo_id = 1 order by direccion_id desc limit 1";		
		$sSql2 = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where cliente_id = ".$GLOBALS["x_cliente_id"]." and direccion_tipo_id = 1 order by direccion_id desc limit 1";
		$rs3 = phpmkr_query($sqlD,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row3 = phpmkr_fetch_array($rs3);
		$GLOBALS["x_direccion_id"] = $row3["direccion_id"];
		
		$GLOBALS["x_calle_domicilio"] = $row3["calle"];
		$GLOBALS["x_colonia_domicilio"] = $row3["colonia"];
		$GLOBALS["x_delegacion_id"] = $row3["delegacion_id"];
		$GLOBALS["x_propietario"] = $row3["propietario"];
		$GLOBALS["x_entidad_domicilio"] = $row3["entidad"];
		$GLOBALS["x_codigo_postal_domicilio"] = $row3["codigo_postal"];
		$GLOBALS["x_ubicacion_domicilio"] = $row3["ubicacion"];
		$GLOBALS["x_antiguedad"] = $row3["antiguedad"];
		$GLOBALS["x_tipo_vivienda"] = $row3["vivienda_tipo_id"];
		$GLOBALS["x_otro_tipo_vivienda"] = $row3["otro_tipo_vivienda"];
		$GLOBALS["x_telefono_domicilio"] = $row3["telefono"];		
		$GLOBALS["x_celular"] = $row3["telefono_movil"];					
		$GLOBALS["x_otro_tel_domicilio_1"] = $row3["telefono_secundario"];
		$GLOBALS["x_tel_arrendatario_domicilio"] = $row3["propietario"];
		$GLOBALS["x_otro_tel_domicilio_1"] = $row3["telefono_secundario"];
		$GLOBALS["x_tel_arrendatario_domicilio"] = $row3["propietario"];
		
		$GLOBALS["x_numero_exterior"] = $row3["numero_exterior"];
		$GLOBALS["x_compania_celular_id"] = $row3["compania_celular_id"];
		
		$GLOBALS["x_otro_telefono_domicilio_2"] = $row3["telefono_movil_2"];
		$GLOBALS["x_compania_celular_id_2"] = $row3["compania_celular_id_2"];
		//falta telefono secundario

		$sqlD = "SELECT * FROM direccion WHERE cliente_id = ".$GLOBALS["x_cliente_id"]." and direccion_tipo_id = 2 order by direccion_id desc limit 1";	
		$sSql = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where cliente_id = ".$GLOBALS["x_cliente_id"]." and direccion_tipo_id = 2 order by direccion_id desc limit 1";
		$rs4 = phpmkr_query($sqlD,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row4 = phpmkr_fetch_array($rs4);
		
			$GLOBALS["x_direccion_id2"] = $row4["direccion_id"];
			$GLOBALS["x_giro_negocio"] = $row4["giro_negocio"];
			$GLOBALS["x_calle_negocio"] = $row4["calle"];
			$GLOBALS["x_colonia_negocio"] = $row4["colonia"];
			$GLOBALS["x_entidad_negocio"] = $row4["entidad"];
			$GLOBALS["x_ubicacion_negocio"] = $row4["ubicacion"];
			$GLOBALS["x_codigo_postal_negocio"] = $row4["codigo_postal"];
			$GLOBALS["x_tipo_local_negocio"] = $row4["vivienda_tipo_id"];
			$GLOBALS["x_antiguedad_negocio"] = $row4["antiguedad"];
			$GLOBALS["x_tel_arrendatario_negocio"] = $row4["propietario"]; 
			$GLOBALS["x_renta_mensual"] = $row4["renta_mensual"]; //renta mensula esta en gasto...mas bien no esta guardada deberia estar en gastos
			$GLOBALS["x_tel_negocio"] = $row4["telefono"];
			$GLOBALS["x_delegacion_id2"] = $row4["delegacion_id"];
		
		
		/*$GLOBALS["x_direccion_id2"] = $row4["direccion_id"];
		$GLOBALS["x_calle2"] = $row4["calle"];
		$GLOBALS["x_colonia2"] = $row4["colonia"];
		
//		$GLOBALS["x_otra_delegacion2"] = $row4["otra_delegacion"];
		$GLOBALS["x_entidad_id2"] = $row4["entidad_id"];
		$GLOBALS["x_codigo_postal2"] = $row4["codigo_postal"];
		$GLOBALS["x_ubicacion2"] = $row4["ubicacion"];
		$GLOBALS["x_antiguedad2"] = $row4["antiguedad"];

		$GLOBALS["x_otro_tipo_vivienda2"] = $row4["otro_tipo_vivienda"];
		$GLOBALS["x_telefono2"] = $row4["telefono"];
		$GLOBALS["x_telefono_secundario2"] = $row4["telefono_secundario"];*/


		$sSql = "select * from aval where solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rs5 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row5 = phpmkr_fetch_array($rs5);
		$GLOBALS["x_aval_id"] = $row5["aval_id"];
		$GLOBALS["x_nombre_completo_aval"] = $row5["nombre_completo"];
		$GLOBALS["x_apellido_paterno_aval"] = $row5["apellido_paterno"];
		$GLOBALS["x_apellido_materno_aval"] = $row5["apellido_materno"];								
		
		$GLOBALS["x_aval_rfc"] = $row5["rfc"];
		$GLOBALS["x_aval_curp"] = $row5["curp"];						
		$GLOBALS["x_parentesco_tipo_id_aval"] = $row5["parentesco_tipo_id"];


		$GLOBALS["x_tipo_negocio_aval"] = $row5["tipo_negocio"];
		$GLOBALS["x_edad_aval"] = $row5["edad"];

		$GLOBALS["x_tit_fecha_nac_aval"] = $row5["fecha_nac"];			
		$GLOBALS["x_sexo_aval"] = $row5["sexo"];
		$GLOBALS["x_estado_civil_id_aval"] = $row5["estado_civil_id"];
		$GLOBALS["x_numero_hijos_aval"] = $row5["numero_hijos"];
		$GLOBALS["x_numero_hijos_dep_aval"] = $row5["numero_hijos_dep"];			
		$GLOBALS["x_nombre_conyuge_aval"] = $row5["nombre_conyuge"];
		$GLOBALS["x_email_aval"] = $row5["email"];		
		$GLOBALS["x_nacionalidad_id_aval"] = $row5["nacionalidad_id"];									


		$GLOBALS["x_telefono3"] = $row5["telefono"];
		$GLOBALS["x_ingresos_mensuales"] = $row5["ingresos_mensuales"];
		$GLOBALS["x_ocupacion"] = $row5["ocupacion"];


		if($GLOBALS["x_aval_id"] != ""){
			
			//notenemos aval en este tipo de solicitud...este codigo no se ejecuta
			$sSql = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where aval_id = ".$GLOBALS["x_aval_id"]." and direccion_tipo_id = 3 order by direccion_id desc limit 1";
			$rs6 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$row6 = phpmkr_fetch_array($rs6);
			$GLOBALS["x_direccion_id3"] = $row6["direccion_id"];
			$GLOBALS["x_calle3"] = $row6["calle"];
			$GLOBALS["x_colonia3"] = $row6["colonia"];
			$GLOBALS["x_delegacion_id3"] = $row6["delegacion_id"];
			$GLOBALS["x_propietario2"] = $row6["propietario"];
			$GLOBALS["x_entidad_id3"] = $row6["entidad_id"];
			$GLOBALS["x_codigo_postal3"] = $row6["codigo_postal"];
			$GLOBALS["x_ubicacion3"] = $row6["ubicacion"];
			$GLOBALS["x_antiguedad3"] = $row6["antiguedad"];
			$GLOBALS["x_vivienda_tipo_id2"] = $row6["vivienda_tipo_id"];
			$GLOBALS["x_otro_tipo_vivienda3"] = $row6["otro_tipo_vivienda"];
			$GLOBALS["x_telefono3"] = $row6["telefono"];
			$GLOBALS["x_telefono3_sec"] = $row6["telefono_movil"];								
			$GLOBALS["x_telefono_secundario3"] = $row6["telefono_secundario"];

			//este codigo no se ejecuta	
			$sSql = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where aval_id = ".$GLOBALS["x_aval_id"]." and direccion_tipo_id = 4 order by direccion_id desc limit 1";
			$rs6_2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$row6_2 = phpmkr_fetch_array($rs6_2);
			
			
			
			$GLOBALS["x_direccion_id4"] = $row6_2["direccion_id"];
			$GLOBALS["x_calle3_neg"] = $row6_2["calle"];
			$GLOBALS["x_colonia3_neg"] = $row6_2["colonia"];
			$GLOBALS["x_delegacion_id3_neg"] = $row6_2["delegacion_id"];
			$GLOBALS["x_propietario2_neg"] = $row6_2["propietario"];
			$GLOBALS["x_entidad_id3_neg"] = $row6_2["entidad_id"];
			$GLOBALS["x_codigo_postal3_neg"] = $row6_2["codigo_postal"];
			$GLOBALS["x_ubicacion3_neg"] = $row6_2["ubicacion"];
			$GLOBALS["x_antiguedad3_neg"] = $row6_2["antiguedad"];
			$GLOBALS["x_vivienda_tipo_id2_neg"] = $row6_2["vivienda_tipo_id"];
			$GLOBALS["x_otro_tipo_vivienda3_neg"] = $row6_2["otro_tipo_vivienda"];
			$GLOBALS["x_telefono3_neg"] = $row6_2["telefono"];
			$GLOBALS["x_telefono_secundario3_neg"] = $row6_2["telefono_secundario"];

			//la tabla ingreso aval no se usa en esta solicitud
			$sSql = "select * from ingreso_aval where aval_id = ".$GLOBALS["x_aval_id"];
			$rs8 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$row8 = phpmkr_fetch_array($rs8);
			$GLOBALS["x_ingreso_aval_id"] = $row8["ingreso_aval_id"];
			$GLOBALS["x_ingresos_mensuales"] = $row8["ingresos_negocio"];
			$GLOBALS["x_ingresos_familiar_1_aval"] = $row8["ingresos_familiar_1"];
			$GLOBALS["x_parentesco_tipo_id_ing_1_aval"] = $row8["parentesco_tipo_id"];
			$GLOBALS["x_ingresos_familiar_2_aval"] = $row8["ingresos_familiar_2"];
			$GLOBALS["x_parentesco_tipo_id_ing_2_aval"] = $row8["parentesco_tipo_id2"];
			$GLOBALS["x_otros_ingresos_aval"] = $row8["otros_ingresos"];
			$GLOBALS["x_origen_ingresos_aval"] = $row8["origen_ingresos"];		
			$GLOBALS["x_origen_ingresos_aval2"] = $row8["origen_ingresos_fam_1"];										
			$GLOBALS["x_origen_ingresos_aval3"] = $row8["origen_ingresos_fam_2"];													

			//la tabla gasto aval no se usa en este tipo de solicitud
			$sSql = "select * from gasto_aval where aval_id = ".$GLOBALS["x_aval_id"];
			$rs12 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$row12 = phpmkr_fetch_array($rs12);
			$GLOBALS["x_gasto_aval_id"] = $row12["gasto_aval_id"];
			$GLOBALS["x_gastos_prov1_aval"] = $row12["gastos_prov1"];
			$GLOBALS["x_gastos_prov2_aval"] = $row12["gastos_prov2"];
			$GLOBALS["x_gastos_prov3_aval"] = $row12["gastos_prov3"];
			$GLOBALS["x_otro_prov_aval"] = $row12["otro_prov"];			
			$GLOBALS["x_gastos_empleados_aval"] = $row12["gastos_empleados"];					
			$GLOBALS["x_gastos_renta_negocio_aval"] = $row12["gastos_renta_negocio"];
			$GLOBALS["x_gastos_renta_casa2"] = $row12["gastos_renta_casa"];
			$GLOBALS["x_gastos_credito_hipotecario_aval"] = $row12["gastos_credito_hipotecario"];
			$GLOBALS["x_gastos_otros_aval"] = $row12["gastos_otros"];		


			if(!empty($GLOBALS["x_propietario2"])){
				if(!empty($GLOBALS["x_gastos_renta_casa2"])){
					$GLOBALS["x_propietario_renta2"] = $GLOBALS["x_propietario2"];
					$GLOBALS["x_propietario2"] = "";				
				}else{
					if(!empty($GLOBALS["x_gastos_credito_hipotecario_aval"])){
						$GLOBALS["x_propietario_ch2"] = $GLOBALS["x_propietario2"];
						$GLOBALS["x_propietario2"] = "";
					}
				}
			}

			
		}else{

			$GLOBALS["x_ingreso_aval_id"] = "";
			$GLOBALS["x_ingresos_mensuales"] = "";
			$GLOBALS["x_ingresos_familiar_1_aval"] = "";
			$GLOBALS["x_parentesco_tipo_id_ing_1_aval"] = "";
			$GLOBALS["x_ingresos_familiar_2_aval"] = "";
			$GLOBALS["x_parentesco_tipo_id_ing_2_aval"] = "";
			$GLOBALS["x_otros_ingresos_aval"] = "";
			$GLOBALS["x_origen_ingresos_aval"] = "";
			$GLOBALS["x_origen_ingresos_aval2"] = "";
			$GLOBALS["x_origen_ingresos_aval3"] = "";

			$GLOBALS["x_gasto_aval_id"] = "";
			$GLOBALS["x_gastos_prov1_aval"] = "";
			$GLOBALS["x_gastos_prov2_aval"] = "";
			$GLOBALS["x_gastos_prov3_aval"] = "";
			$GLOBALS["x_otro_prov_aval"] = "";
			$GLOBALS["x_gastos_empleados_aval"] = "";
			$GLOBALS["x_gastos_renta_negocio_aval"] = "";
			$GLOBALS["x_gastos_renta_casa2"] = "";
			$GLOBALS["x_gastos_credito_hipotecario_aval"] = "";
			$GLOBALS["x_gastos_otros_aval"] = "";



			$GLOBALS["x_direccion_id3"] = "";
			$GLOBALS["x_calle3"] = "";
			$GLOBALS["x_colonia3"] = "";
			$GLOBALS["x_delegacion_id3"] = "";
			$GLOBALS["x_propietario2"] = "";
			$GLOBALS["x_entidad_id3"] = "";
			$GLOBALS["x_codigo_postal3"] = "";
			$GLOBALS["x_ubicacion3"] = "";
			$GLOBALS["x_antiguedad3"] = "";
			$GLOBALS["x_vivienda_tipo_id2"] = "";
			$GLOBALS["x_otro_tipo_vivienda3"] = "";
			$GLOBALS["x_telefono3"] = "";
			$GLOBALS["x_telefono_secundario3"] = "";

			$GLOBALS["x_direccion_id4"] = "";
			$GLOBALS["x_calle3_neg"] = "";
			$GLOBALS["x_colonia3_neg"] = "";
			$GLOBALS["x_delegacion_id3_neg"] = "";
			$GLOBALS["x_propietario2_neg"] = "";
			$GLOBALS["x_entidad_id3_neg"] = "";
			$GLOBALS["x_codigo_postal3_neg"] = "";
			$GLOBALS["x_ubicacion3_neg"] = "";
			$GLOBALS["x_antiguedad3_neg"] = "";
			$GLOBALS["x_vivienda_tipo_id2_neg"] = "";
			$GLOBALS["x_otro_tipo_vivienda3_neg"] = "";
			$GLOBALS["x_telefono3_neg"] = "";
			$GLOBALS["x_telefono_secundario3_neg"] = "";
			
		}

		//la tabla garantia no se usa ene sta solicitud,,. no hay campo garantia solo existe  solicitud dde compra
		$sSql = "select * from garantia where solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rs7 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row7 = phpmkr_fetch_array($rs7);
		$GLOBALS["x_garantia_id"] = $row7["garantia_id"];		
		$GLOBALS["x_garantia_desc"] = $row7["descripcion"];
		$GLOBALS["x_garantia_valor"] = $row7["valor"];		

		
		
		
		
		//seleccion de adquisicion Maquinaria

		$sSql = "SELECT * FROM adquisicionmaquinaria WHERE cliente_id = ".$GLOBALS["x_cliente_id"]."" ;
		
		
		//$sSql = "select * from ingreso where solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rs8 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row8 = phpmkr_fetch_array($rs8);
		
		$GLOBALS["x_otro_telefono_domicilio_2"] = $row8["otro_telefono_domicilio_2"];
		$GLOBALS["x_giro_negocio"] = $row8["giro_negocio"];
		$GLOBALS["x_solicitud_compra"] = $row8["solicitud_compra"];
		$GLOBALS["x_ing_fam_negocio"] = $row8["ing_fam_negocio"];
		$GLOBALS["x_ing_fam_otro_th"] = $row8["ing_fam_otro_th"];//este no estaba
		$GLOBALS["x_ing_fam_1"] = $row8["ing_fam_1"];
		$GLOBALS["x_ing_fam_2"] = $row8["ing_fam_2"];
		$GLOBALS["x_ing_fam_deuda_1"] = $row8["ing_fam_deuda_1"];   
		$GLOBALS["x_ing_fam_deuda_2"] = $row8["ing_fam_deuda_2"];
		$GLOBALS["x_ing_fam_total"] = $row8["ing_fam_total"];
		$GLOBALS["x_ing_fam_cuales_1"] = $row8["ing_fam_cuales_1"];
		$GLOBALS["x_ing_fam_cuales_2"] = $row8["ing_fam_cuales_2"];
		$GLOBALS["x_ing_fam_cuales_3"] = $row8["ing_fam_cuales_3"];
		$GLOBALS["x_ing_fam_cuales_4"] = $row8["ing_fam_cuales_4"];
		$GLOBALS["x_ing_fam_cuales_5"] = $row8["ing_fam_cuales_5"];
		$GLOBALS["x_flujos_neg_ventas"] = $row8["flujos_neg_ventas"];
		$GLOBALS["x_flujos_neg_proveedor_1"] = $row8["flujos_neg_proveedor_1"];
		$GLOBALS["x_flujos_neg_proveedor_2"] = $row8["flujos_neg_proveedor_2"];
		$GLOBALS["x_flujos_neg_proveedor_3"] = $row8["flujos_neg_proveedor_3"];
		$GLOBALS["x_flujos_neg_proveedor_4"] = $row8["flujos_neg_proveedor_4"];
		$GLOBALS["x_flujos_neg_gasto_1"] = $row8["flujos_neg_gasto_1"];
		$GLOBALS["x_flujos_neg_gasto_2"] = $row8["flujos_neg_gasto_2"];
		$GLOBALS["x_flujos_neg_gasto_3"] = $row8["flujos_neg_gasto_3"];
		$GLOBALS["x_flujos_neg_cual_1"] = $row8["flujos_neg_cual_1"];
		$GLOBALS["x_flujos_neg_cual_2"] = $row8["flujos_neg_cual_2"];
		$GLOBALS["x_flujos_neg_cual_3"] = $row8["flujos_neg_cual_3"];
		$GLOBALS["x_flujos_neg_cual_4"] = $row8["flujos_neg_cual_4"];		
		$GLOBALS["x_flujos_neg_cual_5"] = $row8["flujos_neg_cual_5"];
		$GLOBALS["x_flujos_neg_cual_6"] = $row8["flujos_neg_cual_6"];
		$GLOBALS["x_flujos_neg_cual_7"] = $row8["flujos_neg_cual_7"];
		$GLOBALS["x_ingreso_negocio"] = $row8["ingreso_negocio"];
		$GLOBALS["x_inv_neg_fija_conc_1"] = $row8["inv_neg_fija_conc_1"];
		$GLOBALS["x_inv_neg_fija_conc_2"] = $row8["inv_neg_fija_conc_2"];
		$GLOBALS["x_inv_neg_fija_conc_3"] = $row8["inv_neg_fija_conc_3"];		
		$GLOBALS["x_inv_neg_fija_conc_4"] = $row8["inv_neg_fija_conc_4"];
		$GLOBALS["x_inv_neg_fija_valor_1"] = $row8["inv_neg_fija_valor_1"];
		$GLOBALS["x_inv_neg_fija_valor_2"] = $row8["inv_neg_fija_valor_2"];		
		$GLOBALS["x_inv_neg_fija_valor_3"] = $row8["inv_neg_fija_valor_3"];
		$GLOBALS["x_inv_neg_fija_valor_4"] = $row8["inv_neg_fija_valor_4"];
		$GLOBALS["x_inv_neg_total_fija"] = $row8["inv_neg_total_fija"];
		$GLOBALS["x_inv_neg_var_conc_1"] = $row8["inv_neg_var_conc_1"];
		$GLOBALS["x_inv_neg_var_conc_2"] = $row8["inv_neg_var_conc_2"];
		$GLOBALS["x_inv_neg_var_conc_3"] = $row8["inv_neg_var_conc_3"];
		$GLOBALS["x_inv_neg_var_conc_4"] = $row8["inv_neg_var_conc_4"];
		$GLOBALS["x_inv_neg_var_valor_1"] = $row8["inv_neg_var_valor_1"];
		$GLOBALS["x_inv_neg_var_valor_2"] = $row8["inv_neg_var_valor_2"];
		$GLOBALS["x_inv_neg_var_valor_3"] = $row8["inv_neg_var_valor_3"];
		$GLOBALS["x_inv_neg_var_valor_4"] = $row8["inv_neg_var_valor_4"];
		$GLOBALS["x_inv_neg_total_var"] = $row8["inv_neg_total_var"];
		$GLOBALS["x_inv_neg_activos_totales"] = $row8["inv_neg_activos_totales"];
													


		//gasto de la renta de la casa verificar lso datos............  estos datos no se gauardaorn cuando se levanto la solicitud
		$sSql = "select * from gasto where solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rs12 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row12 = phpmkr_fetch_array($rs12);
		$GLOBALS["x_gasto_id"] = $row12["gasto_id"];
		$GLOBALS["x_gastos_prov1"] = $row12["gastos_prov1"];
		$GLOBALS["x_gastos_prov2"] = $row12["gastos_prov2"];
		$GLOBALS["x_gastos_prov3"] = $row12["gastos_prov3"];
		$GLOBALS["x_otro_prov"] = $row12["otro_prov"];			
		$GLOBALS["x_gastos_empleados"] = $row12["gastos_empleados"];					
		$GLOBALS["x_renta_mensual"] = $row12["gastos_renta_negocio"]; //RENTA DEL NEGOCIO
		$GLOBALS["x_renta_mensula_domicilio"] = $row12["gastos_renta_casa"]; //RENTA DEL DOMICILIO
		/*$GLOBALS["x_gastos_credito_hipotecario"] = $row12["gastos_credito_hipotecario"];
		$GLOBALS["x_gastos_otros"] = $row12["gastos_otros"];	*/	

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
		$GLOBALS["x_x_referencia_com_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_x_referencia_com_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_x_referencia_com_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_x_referencia_com_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_x_referencia_com_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";
	

		$x_count = 1;
		$sSql = "select * from referencia where solicitud_id = ".$GLOBALS["x_solicitud_id"]." order by referencia_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9 = phpmkr_fetch_array($rs9)){
			$GLOBALS["x_referencia_id_$x_count"] = $row9["referencia_id"];
			$GLOBALS["x_referencia_com_$x_count"] = $row9["nombre_completo"];
			$GLOBALS["x_tel_referencia_$x_count"] = $row9["telefono"];
			$GLOBALS["x_parentesco_ref_$x_count"] = $row9["parentesco_tipo_id"];
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
	
		$theValue = ($GLOBALS["x_credito_tipo_id"] != "") ? intval($GLOBALS["x_credito_tipo_id"]) : "0";
		$fieldList["`credito_tipo_id`"] = $theValue;
		$theValue = ($GLOBALS["x_solicitud_status_id"] != "") ? intval($GLOBALS["x_solicitud_status_id"]) : "0";
		$fieldList["`solicitud_status_id`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_folio"]) : $GLOBALS["x_folio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`folio`"] = $theValue;
		$theValue = ($GLOBALS["x_fecha_registro"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_registro"]) . "'" : "Null";
		$fieldList["`fecha_registro`"] = $theValue;
		$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "0";
		$fieldList["`promotor_id`"] = $theValue;
		$theValue = ($GLOBALS["x_importe_solicitado"] != "") ? " '" . doubleval($GLOBALS["x_importe_solicitado"]) . "'" : "NULL";
		$fieldList["`importe_solicitado`"] = $theValue;
		$theValue = ($GLOBALS["x_plazo_id"] != "") ? intval($GLOBALS["x_plazo_id"]) : "0";
		$fieldList["`plazo_id`"] = $theValue;
		$theValue = ($GLOBALS["x_forma_pago_id"] != "") ? intval($GLOBALS["x_forma_pago_id"]) : "0";
		$fieldList["`forma_pago_id`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_comentario_promotor"]) : $GLOBALS["x_comentario_promotor"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`comentario_promotor`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_comentario_comite"]) : $GLOBALS["x_comentario_comite"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`comentario_comite`"] = $theValue;


		$theValue = ($GLOBALS["x_actividad_id"] != "") ? intval($GLOBALS["x_actividad_id"]) : "0";
		$fieldList["`actividad_id`"] = $theValue;
	
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_actividad_desc"]) : $GLOBALS["x_actividad_desc"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`actividad_desc`"] = $theValue;



		if((intval($GLOBALS["x_solicitud_status_id"]) == 2)&&($GLOBALS["x_actualiza_fecha_investigacion"] == "si")){		
			
		// Field fecha_investigacion
		$theValue = ($GLOBALS["x_fecha_investigacion"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_investigacion"]) . "'" : "NULL";
		$fieldList["`fecha_investigacion`"] = $theValue;
			}

		// update
		$sSql = "UPDATE `solicitud` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE " . $sWhere;

		$x_result = phpmkr_query($sSql,$conn);

		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);
			
			exit();
		}
		
		//CLIENTE
		$fieldList = NULL;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre"]) : $GLOBALS["x_nombre"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`nombre_completo`"] = $theValue;
		 
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_paterno"]) : $GLOBALS["x_apellido_paterno"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`apellido_paterno`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_materno"]) : $GLOBALS["x_apellido_materno"];
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`apellido_materno`"] = $theValue;	
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_rfc"]) : $GLOBALS["x_rfc"];  
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`rfc`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_curp"]) : $GLOBALS["x_curp"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`curp`"] = $theValue;	
			
		$theValue = ($GLOBALS["x_fecha_nacimiento"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_nacimiento"]) . "'" : "NULL";
		$fieldList["`fecha_nac`"] = $theValue;
		
		$theValue = ($GLOBALS["x_sexo"] != "") ? intval($GLOBALS["x_sexo"]) : "0";
		$fieldList["`sexo`"] = $theValue;	
		
		$theValue = ($GLOBALS["x_integrantes_familia"] != "") ? intval($GLOBALS["x_integrantes_familia"]) : "NULL";
		$fieldList["`numero_hijos`"] = $theValue;
		
		$theValue = ($GLOBALS["x_dependientes"] != "") ? intval($GLOBALS["x_dependientes"]) : "NULL";
		$fieldList["`numero_hijos_dep`"] = $theValue;
		
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_correo_electronico"]) : $GLOBALS["x_correo_electronico"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`email`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_esposa"]) : $GLOBALS["x_esposa"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`nombre_conyuge`"] = $theValue;
		
		// Field promotor_id
		$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
		$fieldList["`promotor_id`"] = $theValue;
			
		
		// Field promotor_id
		$theValue = ($GLOBALS["x_rol_hogar_id"] != "") ? intval($GLOBALS["x_rol_hogar_id"]) : "NULL";
		$fieldList["`rol_hogar_id`"] = $theValue;
		// Field fecha_inicio_act
		$theValue = ($GLOBALS["x_fecha_ini_act_prod"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_ini_act_prod"]) . "'" : "Null";
		$fieldList["`fecha_ini_act_prod`"] = $theValue;
		
		$sSql = "UPDATE `cliente` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE cliente_id = ".$GLOBALS["x_cliente_id"] ;
		$x_result = phpmkr_query($sSql,$conn)or die("erro_".phpmkr_errro()."sql".$sSql);

		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}
		
		
		
		
		
		if($GLOBALS["x_negocio_id"] > 0){
			echo "negocio id" . $GLOBALS["x_negocio_id"]."-";
			
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
					$x_result = phpmkr_query($sSql,$conn) or die("ERROR...".phpmkr_error()."sql direccion dos".$sSql);
					
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
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
						
				
				}// fin negocio
		
		
		
		
		
		
		
		//DIR PARTICULAR


		if($GLOBALS["x_direccion_id"] > 0 ){

			$fieldList = NULL;
			
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle_domicilio"]) : $GLOBALS["x_calle_domicilio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`calle`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia_domicilio"]) : $GLOBALS["x_colonia_domicilio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`colonia`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_entidad_domicilio"]) : $GLOBALS["x_entidad_domicilio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`entidad`"] = $theValue;
		
		$theValue = ($GLOBALS["x_codigo_postal_domicilio"] != "") ? intval($GLOBALS["x_codigo_postal_domicilio"]) : "NULL";
		$fieldList["`codigo_postal`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion_domicilio"]) : $GLOBALS["x_ubicacion_domicilio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ubicacion`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tipo_vivienda"]) : $GLOBALS["x_tipo_vivienda"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`vivienda_tipo_id`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_domicilio"]) : $GLOBALS["x_telefono_domicilio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_celular"]) : $GLOBALS["x_celular"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_movil`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otro_tel_domicilio_1"]) : $GLOBALS["x_otro_tel_domicilio_1"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_secundario`"] = $theValue;
		
		$theValue = ($GLOBALS["x_delegacion_id"] != "") ? intval($GLOBALS["x_delegacion_id"]) : "0";
	    $fieldList["`delegacion_id`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_antiguedad"]) : $GLOBALS["x_antiguedad"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`antiguedad`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_arrendatario_domicilio"]) : $GLOBALS["x_tel_arrendatario_domicilio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`propietario`"] = $theValue;	
				
		// Field vivienda_tipo_id
		$theValue = ($GLOBALS["x_compania_celular_id"] != "") ? intval($GLOBALS["x_compania_celular_id"]) : "0";
		$fieldList["`compania_celular_id`"] = $theValue;
	
		$theValue = ($GLOBALS["x_numero_exterior"] != "") ? intval($GLOBALS["x_numero_exterior"]) : "0";
		$fieldList["`numero_exterior`"] = $theValue;
		
			 // Field telefono_secundario
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_movil_2"]) :$GLOBALS["x_telefono_movil_2"]; 
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
			$x_result = phpmkr_query($sSql,$conn) or die("Error: ".phpmkr_error()."statement :".sSql);
	
			if(!$x_result){
				echo phpmkr_error() . '<br>SQL cliente par: ' . $sSql;
				phpmkr_query('rollback;', $conn);	 
				exit();
			}

		}else{

			if($GLOBALS["x_calle_domicilio"] != ""){

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
				
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle_domicilio"]) : $GLOBALS["x_calle_domicilio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`calle`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia_domicilio"]) : $GLOBALS["x_colonia_domicilio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`colonia`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_entidad_domicilio"]) : $GLOBALS["x_entidad_domicilio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`entidad`"] = $theValue;
		
		$theValue = ($GLOBALS["x_codigo_postal_domicilio"] != "") ? intval($GLOBALS["x_codigo_postal_domicilio"]) : "NULL";
		$fieldList["`codigo_postal`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion_domicilio"]) : $GLOBALS["x_ubicacion_domicilio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ubicacion`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tipo_vivienda"]) : $GLOBALS["x_tipo_vivienda"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`vivienda_tipo_id`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_domicilio"]) : $GLOBALS["x_telefono_domicilio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_celular"]) : $GLOBALS["x_celular"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_movil`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otro_tel_domicilio_1"]) : $GLOBALS["x_otro_tel_domicilio_1"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_secundario`"] = $theValue;
		
		$theValue = ($GLOBALS["x_delegacion_id"] != "") ? intval($GLOBALS["x_delegacion_id"]) : "0";
	    $fieldList["`delegacion_id`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_antiguedad"]) : $GLOBALS["x_antiguedad"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`antiguedad`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_arrendatario_domicilio"]) : $GLOBALS["x_tel_arrendatario_domicilio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`propietario`"] = $theValue;
		
				
		// Field vivienda_tipo_id
		$theValue = ($GLOBALS["x_compania_celular_id"] != "") ? intval($GLOBALS["x_compania_celular_id"]) : "0";
		$fieldList["`compania_celular_id`"] = $theValue;
	
		$theValue = ($GLOBALS["x_numero_exterior"] != "") ? intval($GLOBALS["x_numero_exterior"]) : "0";
		$fieldList["`numero_exterior`"] = $theValue;
		
			 // Field telefono_secundario
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_movil_2"]) :$GLOBALS["x_telefono_movil_2"]; 
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
			
				$x_result = phpmkr_query($sSql, $conn) or die("error  direccion:". phpmkr_error()."statement:".$sSql);
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL AQUI: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
			}
		}// ELSE DIRECCION
		
		//DIR NEG

		if($GLOBALS["x_direccion_id2"] > 0 ){
	
		$fieldList = NULL;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle_negocio"]) : $GLOBALS["x_calle_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`calle`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia_negocio"]) : $GLOBALS["x_colonia_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`colonia`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_entidad_negocio"]) : $GLOBALS["x_entidad_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`entidad`"] = $theValue;
		
		$theValue = ($GLOBALS["x_codigo_postal_domicilio"] != "") ? intval($GLOBALS["x_codigo_postal_negocio"]) : "NULL";
		$fieldList["`codigo_postal`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion_negocio"]) : $GLOBALS["x_ubicacion_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ubicacion`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tipo_local_negocio"]) : $GLOBALS["x_tipo_local_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`vivienda_tipo_id`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_negocio"]) : $GLOBALS["x_tel_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_celular"]) : $GLOBALS["x_celular"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_movil`"] = $theValue;
		
		$theValue = ($GLOBALS["x_delegacion_id2"] != "") ? intval($GLOBALS["x_delegacion_id2"]) : "0";
	    $fieldList["`delegacion_id`"] = $theValue;
		
		
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_antiguedad_negocio"]) : $GLOBALS["x_antiguedad"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`antiguedad`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_arrendatario_negocio"]) : $GLOBALS["x_tel_arrendatario_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`propietario`"] = $theValue;
		
		
	
			// update
			$sSql = "UPDATE `direccion` SET ";
			foreach ($fieldList as $key=>$temp) {
				$sSql .= "$key = $temp, ";
			}
			if (substr($sSql, -2) == ", ") {
				$sSql = substr($sSql, 0, strlen($sSql)-2);
			}
			$sSql .= " WHERE cliente_id = ". $GLOBALS["x_cliente_id"]." and direccion_tipo_id = 2";
			$x_result = phpmkr_query($sSql,$conn) or die("ERROR...".phpmkr_error()."sql direccion dos".$sSql);
			
			if(!$x_result){
				echo phpmkr_error() . '<br>SQL cliente neg: ' . $sSql;
				phpmkr_query('rollback;', $conn);	 
				exit();
			}

		}else{

			if($GLOBALS["x_calle_negocio"] != ""){

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
			
				//*------------------------------------------------------------------------------------------------------
				
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle_negocio"]) : $GLOBALS["x_calle_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`calle`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia_negocio"]) : $GLOBALS["x_colonia_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`colonia`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_entidad_negocio"]) : $GLOBALS["x_entidad_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`entidad`"] = $theValue;
		
		$theValue = ($GLOBALS["x_codigo_postal_domicilio"] != "") ? intval($GLOBALS["x_codigo_postal_negocio"]) : "NULL";
		$fieldList["`codigo_postal`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion_negocio"]) : $GLOBALS["x_ubicacion_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ubicacion`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tipo_local_negocio"]) : $GLOBALS["x_tipo_local_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`vivienda_tipo_id`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_negocio"]) : $GLOBALS["x_tel_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_celular"]) : $GLOBALS["x_celular"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_movil`"] = $theValue;		
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_antiguedad_negocio"]) : $GLOBALS["x_antiguedad"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`antiguedad`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_arrendatario_negocio"]) : $GLOBALS["x_tel_arrendatario_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`propietario`"] = $theValue;
					
		$theValue = ($GLOBALS["x_delegacion_id2"] != "") ? intval($GLOBALS["x_delegacion_id2"]) : "0";
	    $fieldList["`delegacion_id`"] = $theValue;		
			
				// insert into database
				$sSql = "INSERT INTO `direccion` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
			
				$x_result = phpmkr_query($sSql, $conn) or die("Error  insert domicilio dos:".phpmkr_error()."statement".$sSql);
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
			}
		}
		
		
		//GARANTIAS no hay garantias en esta solicitud solo hay solicitud de compra..............


		/*if($GLOBALS["x_garantia_id"] != ""){
			
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
			/*$x_result = phpmkr_query($sSql,$conn);

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
		
			}//FIN GARANTIAS*/
			
		//GASTOS

	if($GLOBALS["x_solicitud_id"] > 0){ 
		$fieldList = NULL;
		
		$theValue = ($GLOBALS["x_renta_mensual"] != "") ? " '" . doubleval($GLOBALS["x_renta_mensual"]) . "'" : "NULL";
		$fieldList["`gastos_renta_negocio`"] = $theValue;
	
	
		$theValue = ($GLOBALS["x_renta_mensula_domicilio"] != "") ? doubleval($GLOBALS["x_renta_mensula_domicilio"]) : "0";
		$fieldList["`gastos_renta_casa`"] = $theValue;	

		// update
		$sSql = "UPDATE `gasto` SET ";
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
		$fieldList["`solicitud_id`"] = $GLOBALS["x_solicitud_id"];
		
		
		$theValue = ($GLOBALS["x_renta_mensual"] != "") ? " '" . doubleval($GLOBALS["x_renta_mensual"]) . "'" : "NULL";
		$fieldList["`gastos_renta_negocio`"] = $theValue;
	
	
		$theValue = ($GLOBALS["x_renta_mensula_domicilio"] != "") ? doubleval($GLOBALS["x_renta_mensula_domicilio"]) : "0";
		$fieldList["`gastos_renta_casa`"] = $theValue;
		
		
		
		// insert into database
		$sSql = "INSERT INTO `gasto` (";
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
			
			
		
		//referencias
		
		
		//REFERENCIAS

		$sSql = " delete from referencia WHERE solicitud_id = " . $GLOBALS["x_solicitud_id"];
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
	
	
			if($GLOBALS["x_referencia_com_$x_counter"] != ""){
			
				// Field nombre_completo
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_com_$x_counter"]) : $GLOBALS["x_referencia_com_$x_counter"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`nombre_completo`"] = $theValue;
			
				// Field telefono
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_referencia_$x_counter"]) : $GLOBALS["tel_referencia_$x_counter"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono`"] = $theValue;
			
				// Field parentesco_tipo_id
				$theValue = ($GLOBALS["x_parentesco_ref_$x_counter"] != "") ? intval($GLOBALS["x_parentesco_ref_$x_counter"]) : "NULL";
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
	
			}
	
	
			$x_counter++;
		}
		
		
		
		
			
			
			
			//ADQUISICION MAQUINARIA

	if($GLOBALS["x_cliente_id"] > 0){ 
	$fieldList = NULL;
		//no esta el campo en la tabla domicilio por esa razon se inserta en la tabla adquisicionmaquinaria
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otro_telefono_domicilio_2"]) : $GLOBALS["x_otro_telefono_domicilio_2"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`otro_telefono_domicilio_2`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_solicitud_compra"]) : $GLOBALS["x_solicitud_compra"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`solicitud_compra`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_giro_negocio"]) : $GLOBALS["x_giro_negocio"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`giro_negocio`"] = $theValue;
		
		$theValue = ($GLOBALS["x_ing_fam_negocio"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_negocio"]). "'" : "NULL";
		$fieldList["`ing_fam_negocio`"] = $theValue;
		
		$theValue = ($GLOBALS["x_ing_fam_otro_th"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_otro_th"]). "'" : "NULL";
		$fieldList["`ing_fam_otro_th`"] = $theValue;
		
		$theValue = ($GLOBALS["x_ing_fam_1"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_1"]). "'" : "NULL";
		$fieldList["`ing_fam_1`"] = $theValue;
		
		$theValue = ($GLOBALS["x_ing_fam_2"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_2"]). "'" : "NULL";
		$fieldList["`ing_fam_2`"] = $theValue;
		
		$theValue = ($GLOBALS["x_ing_fam_deuda_1"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_deuda_1"]). "'" : "NULL";
		$fieldList["`ing_fam_deuda_1`"] = $theValue;
		
		$theValue = ($GLOBALS["x_ing_fam_deuda_2"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_deuda_2"]). "'" : "NULL";
		$fieldList["`ing_fam_deuda_2`"] = $theValue;
		
		$theValue = ($GLOBALS["x_ing_fam_total"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_total"]) . "'" : "NULL";
		$fieldList["`ing_fam_total`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_1"]) : $GLOBALS["x_ing_fam_cuales_1"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ing_fam_cuales_1`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_2"]) : $GLOBALS["x_ing_fam_cuales_2"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ing_fam_cuales_2`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_3"]) : $GLOBALS["x_ing_fam_cuales_3"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ing_fam_cuales_3`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_4"]) : $GLOBALS["x_ing_fam_cuales_4"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ing_fam_cuales_4`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_5"]) : $GLOBALS["x_ing_fam_cuales_5"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ing_fam_cuales_5`"] = $theValue;
		
		$theValue = ($GLOBALS["x_flujos_neg_ventas"] != "") ? " '" .doubleval( $GLOBALS["x_flujos_neg_ventas"]) . "'" : "NULL";
		$fieldList["`flujos_neg_ventas`"] = $theValue;
		
		$theValue = ($GLOBALS["x_flujos_neg_proveedor_1"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_proveedor_1"]) . "'" : "NULL";
		$fieldList["`flujos_neg_proveedor_1`"] = $theValue;
		
		$theValue = ($GLOBALS["x_flujos_neg_proveedor_2"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_proveedor_2"]) . "'" : "NULL";
		$fieldList["`flujos_neg_proveedor_2`"] = $theValue;
		
		$theValue = ($GLOBALS["x_flujos_neg_proveedor_3"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_proveedor_3"]) . "'" : "NULL";
		$fieldList["`flujos_neg_proveedor_3`"] = $theValue;
		
		$theValue = ($GLOBALS["x_flujos_neg_proveedor_4"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_proveedor_4"] ). "'" : "NULL";
		$fieldList["`flujos_neg_proveedor_4`"] = $theValue;
		
		$theValue = ($GLOBALS["x_flujos_neg_gasto_1"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_gasto_1"]) . "'" : "NULL";
		$fieldList["`flujos_neg_gasto_1`"] = $theValue;
		
		$theValue = ($GLOBALS["x_flujos_neg_gasto_2"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_gasto_2"]) . "'" : "NULL";
		$fieldList["`flujos_neg_gasto_2`"] = $theValue;
		
		$theValue = ($GLOBALS["x_flujos_neg_gasto_3"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_gasto_3"]). "'" : "NULL";
		$fieldList["`flujos_neg_gasto_3`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_1"]) : $GLOBALS["x_flujos_neg_cual_1"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_1`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_2"]) : $GLOBALS["x_flujos_neg_cual_2"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_2`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_3"]) : $GLOBALS["x_flujos_neg_cual_3"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_3`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_4"]) : $GLOBALS["x_flujos_neg_cual_4"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_4`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_5"]) : $GLOBALS["x_flujos_neg_cual_5"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_5`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_6"]) : $GLOBALS["x_flujos_neg_cual_6"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_6`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_7"]) : $GLOBALS["x_flujos_neg_cual_7"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`flujos_neg_cual_7`"] = $theValue;
		
		$theValue = ($GLOBALS["x_ingreso_negocio"] != "") ? " '" . doubleval($GLOBALS["x_ingreso_negocio"]). "'" : "NULL";
		$fieldList["`ingreso_negocio`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_fija_conc_1"]) : $GLOBALS["x_inv_neg_fija_conc_1"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_fija_conc_1`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_fija_conc_2"]) : $GLOBALS["x_inv_neg_fija_conc_2"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_fija_conc_2`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_fija_conc_3"]) : $GLOBALS["x_inv_neg_fija_conc_3"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_fija_conc_3`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_fija_conc_4"]) : $GLOBALS["x_inv_neg_fija_conc_4"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_fija_conc_4`"] = $theValue;
		
		$theValue = ($GLOBALS["x_inv_neg_fija_valor_1"] != "") ? " '" . $GLOBALS["x_inv_neg_fija_valor_1"] . "'" : "NULL";
		$fieldList["`inv_neg_fija_valor_1`"] = $theValue;
		
		$theValue = ($GLOBALS["x_inv_neg_fija_valor_2"] != "") ? " '" . $GLOBALS["x_inv_neg_fija_valor_2"] . "'" : "NULL";
		$fieldList["`inv_neg_fija_valor_2`"] = $theValue;
		
		$theValue = ($GLOBALS["x_inv_neg_fija_valor_3"] != "") ? " '" . $GLOBALS["x_inv_neg_fija_valor_3"] . "'" : "NULL";
		$fieldList["`inv_neg_fija_valor_3`"] = $theValue;
		
		$theValue = ($GLOBALS["x_inv_neg_fija_valor_4"] != "") ? " '" . $GLOBALS["x_inv_neg_fija_valor_4"] . "'" : "NULL";
		$fieldList["`inv_neg_fija_valor_4`"] = $theValue;
		
		$theValue = ($GLOBALS["x_inv_neg_total_fija"] != "") ? " '" . $GLOBALS["x_inv_neg_total_fija"] . "'" : "NULL";
		$fieldList["`inv_neg_total_fija`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_var_conc_1"]) : $GLOBALS["x_inv_neg_var_conc_1"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_var_conc_1`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_var_conc_2"]) : $GLOBALS["x_inv_neg_var_conc_2"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_var_conc_2`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_var_conc_3"]) : $GLOBALS["x_inv_neg_var_conc_3"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_var_conc_3`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_var_conc_4"]) : $GLOBALS["x_inv_neg_var_conc_4"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`inv_neg_var_conc_4`"] = $theValue;
		
		$theValue = ($GLOBALS["x_inv_neg_var_valor_1"] != "") ? " '" .doubleval( $GLOBALS["x_inv_neg_var_valor_1"]). "'" : "NULL";
		$fieldList["`inv_neg_var_valor_1`"] = $theValue;
		
		$theValue = ($GLOBALS["x_inv_neg_var_valor_2"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_var_valor_2"]) . "'" : "NULL";
		$fieldList["`inv_neg_var_valor_2`"] = $theValue;
		
		$theValue = ($GLOBALS["x_inv_neg_var_valor_3"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_var_valor_3"]) . "'" : "NULL";
		$fieldList["`inv_neg_var_valor_3`"] = $theValue;
		
		$theValue = ($GLOBALS["x_inv_neg_var_valor_4"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_var_valor_4"]) . "'" : "NULL";
		$fieldList["`inv_neg_var_valor_4`"] = $theValue;
		
		$theValue = ($GLOBALS["x_inv_neg_total_var"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_total_var"]) . "'" : "NULL";
		$fieldList["`inv_neg_total_var`"] = $theValue;
		
		$theValue = ($GLOBALS["x_inv_neg_activos_totales"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_activos_totales"]) . "'" : "NULL";
		$fieldList["`inv_neg_activos_totales`"] = $theValue;
		
		$theValue = ($GLOBALS["x_fecha"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha"]) . "'" : "NULL";
		$fieldList["`fecha`"] = $theValue;
		
		$sSql = "UPDATE `adquisicionmaquinaria` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE cliente_id = ".$GLOBALS["x_cliente_id"]."";
		$x_result = phpmkr_query($sSql,$conn) or die("ERROR FATAL...".phpmkr_error()."SQL STATEMENT".$sSql);
			if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
		}
	}
			
			

	$respuesta = true;	
	
	}//fin else  phpmkr_num_rows($rs) == 0
	
	
	
	
	return $respuesta;
	}



?>