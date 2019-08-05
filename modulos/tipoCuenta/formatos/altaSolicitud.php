<?php
//insertar en base de datos

//conexion con db
$conn = phpmkr_db_connect(HOST, USER, PASS,DB);

function insertaCreditoIndividual(){
	//esta funcion se utiliza para insertar los  campos de las solictudes individuales de los integrantes de un grupo
	$currentdate = getdate(time());
	$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
	$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];		


	$conn = phpmkr_db_connect(HOST, USER, PASS,DB);
	// Get fields from form
	$x_solicitud_id = 0;
	$x_credito_tipo_id = @$_POST["x_tipo_credito"];
	$x_solicitud_status_id = 1;
	global $x_folio;
	$x_folio = "01ABC";
	$x_fecha_registro = @$_POST["x_fecha_registro"];
	$x_hora_registro = $currtime;	
	$x_promotor_id = @$_POST["x_promotor_id"];
	$x_importe_solicitado = @$_POST["x_importe_solicitado"];
	$x_plazo_id = @$_POST["x_plazo_id"];
	$x_forma_pago_id = @$_POST["x_forma_pago_id"];	

	$x_actividad_id = $_POST["x_actividad_id"];
	$x_actividad_desc = $_POST["x_actividad_desc"];



	//$x_cliente_id = @$_POST["x_cliente_id"];

	$x_usuario_id = 0;
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


	$x_direccion_id_par = 0;
	$x_direccion_tipo_id_par = 1;
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


	$x_direccion_id_neg = 0;
	$x_direccion_tipo_id_neg = 2;
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

	// DATOS DEL AVAL SE QUITARON POR REQUERIMIENTO
	
	
	/* $x_aval_id = 0;
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

	$x_calle3 = @$_POST["x_calle3"];
	$x_colonia3 = @$_POST["x_colonia3"];
	$x_entidad_id3 = @$_POST["x_entidad_id3"];	
	$x_delegacion_id3 = @$_POST["x_delegacion_id3"];
	$x_propietario3 = @$_POST["x_propietario3"];
	$x_codigo_postal3 = @$_POST["x_codigo_postal3"];
	$x_ubicacion3 = @$_POST["x_ubicacion3"];
	$x_antiguedad3 = @$_POST["x_antiguedad3"];
	$x_vivienda_tipo_id2 = @$_POST["x_vivienda_tipo_id2"];
	$x_otro_tipo_vivienda3 = @$_POST["x_otro_tipo_vivienda3"];
	$x_telefono3 = @$_POST["x_telefono3"];
	$x_telefono3_sec = @$_POST["x_telefono3_sec"];	
	$x_telefono_secundario3 = @$_POST["x_telefono_secundario3"];


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





	$x_garantia_id = 0;
	$x_garantia_desc = @$_POST["x_garantia_desc"];
	$x_garantia_valor = @$_POST["x_garantia_valor"];
	$x_documento = NULL;
*/

	$x_ingreso_id = 0;
	$x_ingresos_negocio = @$_POST["x_ingresos_negocio"];
	$x_ingresos_familiar_1 = @$_POST["x_ingresos_familiar_1"];
	$x_parentesco_tipo_id_ing_1 = @$_POST["x_parentesco_tipo_id_ing_1"];
	$x_ingresos_familiar_2 = @$_POST["x_ingresos_familiar_2"];
	$x_parentesco_tipo_id_ing_2 = @$_POST["x_parentesco_tipo_id_ing_2"];
	$x_otros_ingresos = @$_POST["x_otros_ingresos"];
	$x_origen_ingresos = @$_POST["x_origen_ingresos"];	
	$x_origen_ingresos2 = @$_POST["x_origen_ingresos2"];	
	$x_origen_ingresos3 = @$_POST["x_origen_ingresos3"];			


	$x_referencia_id = 0;
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


	$x_gastos_prov1 = @$_POST["x_gastos_prov1"];			
	$x_gastos_prov2 = @$_POST["x_gastos_prov2"];			
	$x_gastos_prov3 = @$_POST["x_gastos_prov3"];				
	$x_otro_prov = @$_POST["x_otro_prov"];				
	$x_gastos_empleados = @$_POST["x_gastos_empleados"];				
	$x_gastos_renta_negocio = @$_POST["x_gastos_renta_negocio"];					
	$x_gastos_renta_casa = @$_POST["x_gastos_renta_casa"];					
	$x_gastos_credito_hipotecario = @$_POST["x_gastos_credito_hipotecario"];					
	$x_gastos_otros = @$_POST["x_gastos_otros"];					


	$x_gastos_prov1_aval = @$_POST["x_gastos_prov1_aval"];			
	$x_gastos_prov2_aval = @$_POST["x_gastos_prov2_aval"];			
	$x_gastos_prov3_aval = @$_POST["x_gastos_prov3_aval"];				
	$x_otro_prov_aval = @$_POST["x_otro_prov_aval"];				
	$x_gastos_empleados_aval = @$_POST["x_gastos_empleados_aval"];				
	$x_gastos_renta_negocio_aval = @$_POST["x_gastos_renta_negocio_aval"];		
	$x_gastos_renta_casa2 = @$_POST["x_gastos_renta_casa2"];					
	$x_gastos_credito_hipotecario_aval = @$_POST["x_gastos_credito_hipotecario_aval"];					
	$x_gastos_otros_aval = @$_POST["x_gastos_otros_aval"];					

	
	phpmkr_query('START TRANSACTION;', $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: BEGIN TRAN');

//SOLICITUD

	// Field credito_tipo_id
	$theValue = ($x_credito_tipo_id != "") ? intval($x_credito_tipo_id) : "NULL";
	$fieldList["`credito_tipo_id`"] = $theValue;

	// Field solicitud_status_id
	$theValue = ($x_solicitud_status_id != "") ? intval($x_solicitud_status_id) : "NULL";
	$fieldList["`solicitud_status_id`"] = $theValue;

	// Field folio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_folio) : $x_folio; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`folio`"] = $theValue;

	// Field fecha_registro
	$theValue = ($x_fecha_registro != "") ? " '" . ConvertDateToMysqlFormat($x_fecha_registro) . "'" : "Null";
	$fieldList["`fecha_registro`"] = $theValue;

	// Field promotor_id
	$theValue = ($x_promotor_id != "") ? intval($x_promotor_id) : "NULL";
	$fieldList["`promotor_id`"] = $theValue;

	// Field importe_solicitado
	$theValue = ($x_importe_solicitado != "") ? " '" . doubleval($x_importe_solicitado) . "'" : "Null";
	$fieldList["`importe_solicitado`"] = $theValue;

	// Field plazo
	$theValue = ($x_plazo_id != "") ? intval($x_plazo_id) : "NULL";
	$fieldList["`plazo_id`"] = $theValue;

	$theValue = ($x_forma_pago_id != "") ? intval($x_forma_pago_id) : "NULL";
	$fieldList["`forma_pago_id`"] = $theValue;

	// Field contrato
	$theValue = $x_contrato[0];
	$theValue = ($theValue != "") ? intval($theValue) : "NULL";
	$fieldList["`contrato`"] = $theValue;

	// Field pagare
	$theValue = $x_pagare[0];
	$theValue = ($theValue != "") ? intval($theValue) : "NULL";
	$fieldList["`pagare`"] = $theValue;


	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_comentario_promotor) : $x_comentario_promotor; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`comentario_promotor`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_comentario_comite) : $x_comentario_comite; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`comentario_comite`"] = $theValue;


	$theValue = ($x_actividad_id != "") ? intval($x_actividad_id) : "0";
	$fieldList["`actividad_id`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_actividad_desc) : $x_actividad_desc; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`actividad_desc`"] = $theValue;

	// Field fromato_nuevo
	$theValue = 1;
	$theValue = ($theValue != "") ? intval($theValue) : "NULL";
	$fieldList["`formato_nuevo`"] = $theValue;

	// insert into database
	$sSql = "INSERT INTO `solicitud` (";
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




//CLIENTE

	$fieldList = NULL;
	// Field solicitud_id
//	$fieldList["`solicitud_id`"] = $x_solicitud_id;

	// Field usuario_id
//	$theValue = ($x_usuario_id != "") ? intval($x_usuario_id) : "NULL";
	$fieldList["`usuario_id`"] = 0;

	// Field nombre_completo
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_nombre_completo) : $x_nombre_completo; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre_completo`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_apellido_paterno) : $x_apellido_paterno; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`apellido_paterno`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_apellido_materno) : $x_apellido_materno; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`apellido_materno`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_tit_rfc) : $x_tit_rfc; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`rfc`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_tit_curp) : $x_tit_curp; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`curp`"] = $theValue;


	// Field tipo_negocio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_tipo_negocio) : $x_tipo_negocio; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`tipo_negocio`"] = $theValue;


	// Field fecha_registro
	$theValue = ($x_tit_fecha_nac != "") ? " '" . ConvertDateToMysqlFormat($x_tit_fecha_nac) . "'" : "Null";
	$fieldList["`fecha_nac`"] = $theValue;


	// Field edad
	$theValue = ($x_edad != "") ? intval($x_edad) : "NULL";
	$fieldList["`edad`"] = $theValue;

	// Field sexo
	$theValue = ($x_sexo != "") ? intval($x_sexo) : "NULL";
	$fieldList["`sexo`"] = $theValue;

	// Field estado_civil_id
	$theValue = ($x_estado_civil_id != "") ? intval($x_estado_civil_id) : "0";
	$fieldList["`estado_civil_id`"] = $theValue;

	// Field numero_hijos
	$theValue = ($x_numero_hijos != "") ? intval($x_numero_hijos) : "0";
	$fieldList["`numero_hijos`"] = $theValue;


	// Field numero_hijos_dep
	$theValue = ($x_numero_hijos_dep != "") ? intval($x_numero_hijos_dep) : "0";
	$fieldList["`numero_hijos_dep`"] = $theValue;

	// Field nombre_conyuge
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_nombre_conyuge) : $x_nombre_conyuge; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre_conyuge`"] = $theValue;

	// Field nombre_conyuge
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_email) : $x_email; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`email`"] = $theValue;


	$theValue = ($x_nacionalidad_id != "") ? intval($x_nacionalidad_id) : "0";
	$fieldList["`nacionalidad_id`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_empresa) : $x_empresa; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`empresa`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_puesto) : $x_puesto; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`puesto`"] = $theValue;

	// Field fecha_registro
	$theValue = ($x_fecha_contratacion != "") ? " '" . ConvertDateToMysqlFormat($x_fecha_contratacion) . "'" : "Null";
	$fieldList["`fecha_contratacion`"] = $theValue;

	$theValue = ($x_salario_mensual != "") ? " '" . doubleval($x_salario_mensual) . "'" : "Null";
	$fieldList["`salario_mensual`"] = $theValue;


	// Field promotor_id
	$theValue = ($x_promotor_id != "") ? intval($x_promotor_id) : "NULL";
	$fieldList["`promotor_id`"] = $theValue;


	// insert into database
	$sSql = "INSERT INTO `cliente` (";
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
	$x_cliente_id = mysql_insert_id();



//SOLICITUD CLIENTE

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


//DIRECCION PART

	$fieldList = NULL;
	// Field cliente_id
//	$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";
	$fieldList["`cliente_id`"] = $x_cliente_id;

	// Field aval_id
//	$theValue = ($x_aval_id != "") ? intval($x_aval_id) : "NULL";
	$fieldList["`aval_id`"] = 0;

	// Field promotor_id
//	$theValue = ($x_promotor_id != "") ? intval($x_promotor_id) : "NULL";
	$fieldList["`promotor_id`"] = 0;

	// Field direccion_tipo_id
//	$theValue = ($x_direccion_tipo_id != "") ? intval($x_direccion_tipo_id) : "NULL";
	$fieldList["`direccion_tipo_id`"] = 1;

	// Field calle
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_calle) : $x_calle; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`calle`"] = $theValue;

	// Field colonia
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_colonia) : $x_colonia; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`colonia`"] = $theValue;

	// Field delegacion_id
	$theValue = ($x_delegacion_id != "") ? intval($x_delegacion_id) : "0";
	$fieldList["`delegacion_id`"] = $theValue;

	// Field propietario
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_propietario) : $x_propietario; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`propietario`"] = $theValue;

/*
	// Field entidad
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_entidad) : $x_entidad; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`entidad`"] = $theValue;
*/
	// Field codigo_postal
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_codigo_postal) : $x_codigo_postal; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`codigo_postal`"] = $theValue;

	// Field ubicacion
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_ubicacion) : $x_ubicacion; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ubicacion`"] = $theValue;

	// Field antiguedad
	$theValue = ($x_antiguedad != "") ? intval($x_antiguedad) : "0";
	$fieldList["`antiguedad`"] = $theValue;

	// Field vivienda_tipo_id
	$theValue = ($x_vivienda_tipo_id != "") ? intval($x_vivienda_tipo_id) : "0";
	$fieldList["`vivienda_tipo_id`"] = $theValue;

	// Field otro_tipo_vivienda
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_otro_tipo_vivienda) : $x_otro_tipo_vivienda; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`otro_tipo_vivienda`"] = $theValue;

	// Field telefono
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_telefono) : $x_telefono; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_telefono_sec) : $x_telefono_sec; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono_movil`"] = $theValue;

	// Field telefono_secundario
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_telefono_secundario) : $x_telefono_secundario; 
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
		echo phpmkr_error() . '<br>SQL AQUI: ' . $sSql;
		phpmkr_query('rollback;', $conn);	 
	 	exit();
	}


//DIRECCION NEG

	$fieldList = NULL;
	// Field cliente_id
//	$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";
	$fieldList["`cliente_id`"] = $x_cliente_id;

	// Field aval_id
//	$theValue = ($x_aval_id != "") ? intval($x_aval_id) : "NULL";
	$fieldList["`aval_id`"] = 0;

	// Field promotor_id
//	$theValue = ($x_promotor_id != "") ? intval($x_promotor_id) : "NULL";
	$fieldList["`promotor_id`"] = 0;

	// Field direccion_tipo_id
//	$theValue = ($x_direccion_tipo_id != "") ? intval($x_direccion_tipo_id) : "NULL";
	$fieldList["`direccion_tipo_id`"] = 2;

	// Field calle
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_calle2) : $x_calle2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`calle`"] = $theValue;

	// Field colonia
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_colonia2) : $x_colonia2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`colonia`"] = $theValue;

	// Field delegacion_id
	$theValue = ($x_delegacion_id2 != "") ? intval($x_delegacion_id2) : "0";
	$fieldList["`delegacion_id`"] = $theValue;

	// Field codigo_postal
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_codigo_postal2) : $x_codigo_postal2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`codigo_postal`"] = $theValue;

	// Field ubicacion
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_ubicacion2) : $x_ubicacion2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ubicacion`"] = $theValue;

	// Field antiguedad
	$theValue = ($x_antiguedad != "") ? intval($x_antiguedad2) : "0";
	$fieldList["`antiguedad`"] = $theValue;

	// Field vivienda_tipo_id
	$theValue = ($x_vivienda_tipo_id2 != "") ? intval($x_vivienda_tipo_id2) : "0";
	$fieldList["`vivienda_tipo_id`"] = $theValue;

	// Field otro_tipo_vivienda
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_otro_tipo_vivienda2) : $x_otro_tipo_vivienda2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`otro_tipo_vivienda`"] = $theValue;

	// Field telefono
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_telefono2) : $x_telefono2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono`"] = $theValue;

	// Field telefono_secundario
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_telefono_secundario2) : $x_telefono_secundario2; 
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

// SE QUITO LA PARTE DEL AVAL POR REQUERIRMIRNTO DE SISTEMA
//AVAL

	/*if($x_nombre_completo_aval != ""){

		$fieldList = NULL;
		// Field cliente_id
	//	$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";
		$fieldList["`solicitud_id`"] = $x_solicitud_id;
	
		// Field nombre_completo
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_nombre_completo_aval) : $x_nombre_completo_aval; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`nombre_completo`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_apellido_paterno_aval) : $x_apellido_paterno_aval; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`apellido_paterno`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_apellido_materno_aval) : $x_apellido_materno_aval; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`apellido_materno`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_aval_rfc) : $x_aval_rfc; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`rfc`"] = $theValue;
	
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_aval_curp) : $x_aval_curp; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`curp`"] = $theValue;

		// Field parentesco_tipo_id
		$theValue = ($x_parentesco_tipo_id_aval != "") ? intval($x_parentesco_tipo_id_aval) : "0";
		$fieldList["`parentesco_tipo_id`"] = $theValue;


		// Field tipo_negocio
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_tipo_negocio_aval) : $x_tipo_negocio_aval; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`tipo_negocio`"] = $theValue;
	
	
		// Field fecha_registro
		$theValue = ($x_tit_fecha_nac_aval != "") ? " '" . ConvertDateToMysqlFormat($x_tit_fecha_nac_aval) . "'" : "Null";
		$fieldList["`fecha_nac`"] = $theValue;
	
	
		// Field edad
		$theValue = ($x_edad_aval != "") ? intval($x_edad_aval) : "0";
		$fieldList["`edad`"] = $theValue;
	
		// Field sexo
		$theValue = ($x_sexo_aval != "") ? intval($x_sexo_aval) : "0";
		$fieldList["`sexo`"] = $theValue;
	
		// Field estado_civil_id
		$theValue = ($x_estado_civil_id_aval != "") ? intval($x_estado_civil_id_aval) : "0";
		$fieldList["`estado_civil_id`"] = $theValue;
	
		// Field numero_hijos
		$theValue = ($x_numero_hijos_aval != "") ? intval($x_numero_hijos_aval) : "0";
		$fieldList["`numero_hijos`"] = $theValue;
	
	
		// Field numero_hijos_dep
		$theValue = ($x_numero_hijos_dep_aval != "") ? intval($x_numero_hijos_dep_aval) : "0";
		$fieldList["`numero_hijos_dep`"] = $theValue;
	
		// Field nombre_conyuge
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_nombre_conyuge_aval) : $x_nombre_conyuge_aval; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`nombre_conyuge`"] = $theValue;
	
		// Field nombre_conyuge
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_email_aval) : $x_email_aval; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`email`"] = $theValue;
	
	
		$theValue = ($x_nacionalidad_id_aval != "") ? intval($x_nacionalidad_id_aval) : "0";
		$fieldList["`nacionalidad_id`"] = $theValue;


		// Field telefono
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_telefono3) : $x_telefono3; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono`"] = $theValue;
	
		// Field ingresos_mensuales
		$theValue = ($x_ingresos_mensuales != "") ? " '" . doubleval($x_ingresos_mensuales) . "'" : "Null";
		$fieldList["`ingresos_mensuales`"] = $theValue;
	
		// Field ocupacion
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_ocupacion) : $x_ocupacion; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ocupacion`"] = $theValue;
	
		// insert into database
		$sSql = "INSERT INTO `aval` (";
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
	
		$x_aval_id = mysql_insert_id();
		
	//DOM PART AVAL
	
		$fieldList = NULL;
		// Field cliente_id
	//	$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";
		$fieldList["`cliente_id`"] = 0;
	
		// Field aval_id
	//	$theValue = ($x_aval_id != "") ? intval($x_aval_id) : "NULL";
		$fieldList["`aval_id`"] = $x_aval_id;
	
		// Field promotor_id
	//	$theValue = ($x_promotor_id != "") ? intval($x_promotor_id) : "NULL";
		$fieldList["`promotor_id`"] = 0;
	
		// Field direccion_tipo_id
	//	$theValue = ($x_direccion_tipo_id != "") ? intval($x_direccion_tipo_id) : "NULL";
		$fieldList["`direccion_tipo_id`"] = 3;
	
		// Field calle
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_calle3) : $x_calle3; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`calle`"] = $theValue;
	
		// Field colonia
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_colonia3) : $x_colonia3; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`colonia`"] = $theValue;
	
		// Field delegacion_id
		$theValue = ($x_delegacion_id3 != "") ? intval($x_delegacion_id3) : "0";
		$fieldList["`delegacion_id`"] = $theValue;
	
		// Field propietario
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_propietario2) : $x_propietario2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`propietario`"] = $theValue;
	
		// Field codigo_postal
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_codigo_postal3) : $x_codigo_postal3; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`codigo_postal`"] = $theValue;
	
		// Field ubicacion
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_ubicacion3) : $x_ubicacion3; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ubicacion`"] = $theValue;
	
		// Field antiguedad
		$theValue = ($x_antiguedad3 != "") ? intval($x_antiguedad3) : "0";
		$fieldList["`antiguedad`"] = $theValue;
	
		// Field vivienda_tipo_id
		$theValue = ($x_vivienda_tipo_id2 != "") ? intval($x_vivienda_tipo_id2) : "0";
		$fieldList["`vivienda_tipo_id`"] = $theValue;
	
		// Field otro_tipo_vivienda
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_otro_tipo_vivienda3) : $x_otro_tipo_vivienda3; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`otro_tipo_vivienda`"] = $theValue;
	
		// Field telefono
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_telefono3) : $x_telefono3; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono`"] = $theValue;

		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_telefono3_sec) : $x_telefono3_sec; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_movil`"] = $theValue;
	
		// Field telefono_secundario
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_telefono_secundario3) : $x_telefono_secundario3; 
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


	//DOM NEG AVAL
	
		$fieldList = NULL;
		// Field cliente_id
	//	$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";
		$fieldList["`cliente_id`"] = 0;
	
		// Field aval_id
	//	$theValue = ($x_aval_id != "") ? intval($x_aval_id) : "NULL";
		$fieldList["`aval_id`"] = $x_aval_id;
	
		// Field promotor_id
	//	$theValue = ($x_promotor_id != "") ? intval($x_promotor_id) : "NULL";
		$fieldList["`promotor_id`"] = 0;
	
		// Field direccion_tipo_id
	//	$theValue = ($x_direccion_tipo_id != "") ? intval($x_direccion_tipo_id) : "NULL";
		$fieldList["`direccion_tipo_id`"] = 4;
	
		// Field calle
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_calle3_neg) : $x_calle3_neg; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`calle`"] = $theValue;
	
		// Field colonia
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_colonia3_neg) : $x_colonia3_neg; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`colonia`"] = $theValue;
	
		// Field delegacion_id
		$theValue = ($x_delegacion_id3_neg != "") ? intval($x_delegacion_id3_neg) : "0";
		$fieldList["`delegacion_id`"] = $theValue;
	
		// Field codigo_postal
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_codigo_postal3_neg) : $x_codigo_postal3_neg; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`codigo_postal`"] = $theValue;
	
		// Field ubicacion
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_ubicacion3_neg) : $x_ubicacion3_neg; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ubicacion`"] = $theValue;
	
		// Field antiguedad
		$theValue = ($x_antiguedad3_neg != "") ? intval($x_antiguedad3_neg) : "0";
		$fieldList["`antiguedad`"] = $theValue;
	
		// Field vivienda_tipo_id
		$theValue = ($x_vivienda_tipo_id2_neg != "") ? intval($x_vivienda_tipo_id2_neg) : "0";
		$fieldList["`vivienda_tipo_id`"] = $theValue;
	
		// Field otro_tipo_vivienda
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_propietario3_neg) : $x_propietario3_neg; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`propietario`"] = $theValue;
	
		// Field telefono
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_telefono3_neg) : $x_telefono3_neg; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono`"] = $theValue;
	
		// Field telefono_secundario
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_telefono_secundario3_neg) : $x_telefono_secundario3_neg; 
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



	//Ingresos AVAL
	$fieldList = NULL;
	// Field cliente_id
//	$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";
	$fieldList["`aval_id`"] = $x_aval_id;

	// Field ingresos_negocio
	$theValue = ($x_ingresos_mensuales != "") ? " '" . doubleval($x_ingresos_mensuales) . "'" : "0";
	$fieldList["`ingresos_negocio`"] = $theValue;

	// Field ingresos_familiar_1
	$theValue = ($x_ingresos_familiar_1_aval != "") ? " '" . doubleval($x_ingresos_familiar_1_aval) . "'" : "0";
	$fieldList["`ingresos_familiar_1`"] = $theValue;

	// Field parentesco_tipo_id
	$theValue = ($x_parentesco_tipo_id_ing_1_aval != "") ? intval($x_parentesco_tipo_id_ing_1_aval) : "0";
	$fieldList["`parentesco_tipo_id`"] = $theValue;

	// Field ingresos_familiar_2
	$theValue = ($x_ingresos_familiar_2_aval != "") ? " '" . doubleval($x_ingresos_familiar_2_aval) . "'" : "Null";
	$fieldList["`ingresos_familiar_2`"] = 0;

	// Field parentesco_tipo_id2
	$theValue = ($x_parentesco_tipo_id_ing_2_aval != "") ? intval($x_parentesco_tipo_id_ing_2_aval) : "0";
	$fieldList["`parentesco_tipo_id2`"] = 0;

	// Field otros_ingresos
	$theValue = ($x_otros_ingresos_aval != "") ? " '" . doubleval($x_otros_ingresos_aval) . "'" : "0";
	$fieldList["`otros_ingresos`"] = $theValue;

	$theValue = ($x_origen_ingresos_aval != "") ? " '" . $x_origen_ingresos_aval . "'" : "NULL";
	$fieldList["`origen_ingresos`"] = $theValue;

	$theValue = ($x_origen_ingresos_aval2 != "") ? " '" . $x_origen_ingresos_aval2 . "'" : "NULL";
	$fieldList["`origen_ingresos_fam_1`"] = $theValue;

	$theValue = ($x_origen_ingresos_aval3 != "") ? " '" . $x_origen_ingresos_aval3 . "'" : "NULL";
	$fieldList["`origen_ingresos_fam_2`"] = $theValue;


	// insert into database
	$sSql = "INSERT INTO `ingreso_aval` (";
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

	



//gastos aval

	$fieldList = NULL;
	$fieldList["`aval_id`"] = $x_aval_id;

	$theValue = ($x_gastos_prov1_aval != "") ? " '" . doubleval($x_gastos_prov1_aval) . "'" : "Null";
	$fieldList["`gastos_prov1`"] = $theValue;


	$theValue = ($x_gastos_prov2_aval != "") ? " '" . doubleval($x_gastos_prov2_aval) . "'" : "Null";
	$fieldList["`gastos_prov2`"] = $theValue;


	$theValue = ($x_gastos_prov3_aval != "") ? doubleval($x_gastos_prov3_aval) : "NULL";
	$fieldList["`gastos_prov3`"] = $theValue;


	$theValue = ($x_otro_prov_aval != "") ? doubleval($x_otro_prov_aval) : "NULL";
	$fieldList["`otro_prov`"] = $theValue;


	$theValue = ($x_gastos_empleados_aval != "") ? doubleval($x_gastos_empleados_aval) : "NULL";
	$fieldList["`gastos_empleados`"] = $theValue;


	$theValue = ($x_gastos_renta_negocio_aval != "") ? " '" . doubleval($x_gastos_renta_negocio_aval) . "'" : "Null";
	$fieldList["`gastos_renta_negocio`"] = $theValue;


	$theValue = ($x_gastos_renta_casa2 != "") ? doubleval($x_gastos_renta_casa2) : "0";
	$fieldList["`gastos_renta_casa`"] = $theValue;


	$theValue = ($x_gastos_credito_hipotecario_aval != "") ? " '" . doubleval($x_gastos_credito_hipotecario_aval) . "'" : "Null";
	$fieldList["`gastos_credito_hipotecario`"] = $theValue;

	$theValue = ($x_gastos_otros_aval != "") ? " '" . doubleval($x_gastos_otros_aval) . "'" : "Null";
	$fieldList["`gastos_otros`"] = $theValue;


	// insert into database
	$sSql = "INSERT INTO `gasto_aval` (";
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


		
	}*/



//SE QUITO LA PARTE DE LAS GARANTIAS POR REQUERIMIENTO


//GARANTIAS

	/*if($x_garantia_desc != ""){
		$fieldList = NULL;
		// Field cliente_id
	//	$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";
		$fieldList["`solicitud_id`"] = $x_solicitud_id;
	
		// Field descripcion
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_garantia_desc) : $x_garantia_desc; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`descripcion`"] = $theValue;
	
		// Field valor
		$theValue = ($x_garantia_valor != "") ? " '" . doubleval($x_garantia_valor) . "'" : "Null";
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

	}*/


//INGRESOS

	$fieldList = NULL;
	// Field cliente_id
//	$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";
	$fieldList["`solicitud_id`"] = $x_solicitud_id;

	// Field ingresos_negocio
	$theValue = ($x_ingresos_negocio != "") ? " '" . doubleval($x_ingresos_negocio) . "'" : "Null";
	$fieldList["`ingresos_negocio`"] = $theValue;

	// Field ingresos_familiar_1
	$theValue = ($x_ingresos_familiar_1 != "") ? " '" . doubleval($x_ingresos_familiar_1) . "'" : "Null";
	$fieldList["`ingresos_familiar_1`"] = $theValue;

	// Field parentesco_tipo_id
	$theValue = ($x_parentesco_tipo_id_ing_1 != "") ? intval($x_parentesco_tipo_id_ing_1) : "0";
	$fieldList["`parentesco_tipo_id`"] = $theValue;

	// Field ingresos_familiar_2
	$theValue = ($x_ingresos_familiar_2 != "") ? " '" . doubleval($x_ingresos_familiar_2) . "'" : "Null";
	$fieldList["`ingresos_familiar_2`"] = $theValue;

	// Field parentesco_tipo_id2
	$theValue = ($x_parentesco_tipo_id_ing_2 != "") ? intval($x_parentesco_tipo_id_ing_2) : "0";
	$fieldList["`parentesco_tipo_id2`"] = $theValue;

	// Field otros_ingresos
	$theValue = ($x_otros_ingresos != "") ? " '" . doubleval($x_otros_ingresos) . "'" : "Null";
	$fieldList["`otros_ingresos`"] = $theValue;

	$theValue = ($x_origen_ingresos != "") ? " '" . $x_origen_ingresos . "'" : "NULL";
	$fieldList["`origen_ingresos`"] = $theValue;


	$theValue = ($x_origen_ingresos2 != "") ? " '" . $x_origen_ingresos2 . "'" : "NULL";
	$fieldList["`origen_ingresos_fam_1`"] = $theValue;

	$theValue = ($x_origen_ingresos3 != "") ? " '" . $x_origen_ingresos3 . "'" : "NULL";
	$fieldList["`origen_ingresos_fam_2`"] = $theValue;


	// insert into database
	$sSql = "INSERT INTO `ingreso` (";
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




//gastos

	$fieldList = NULL;
	// Field cliente_id
//	$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";
	$fieldList["`solicitud_id`"] = $x_solicitud_id;


	$theValue = ($x_gastos_prov1 != "") ? " '" . doubleval($x_gastos_prov1) . "'" : "Null";
	$fieldList["`gastos_prov1`"] = $theValue;


	$theValue = ($x_gastos_prov2 != "") ? " '" . doubleval($x_gastos_prov2) . "'" : "Null";
	$fieldList["`gastos_prov2`"] = $theValue;


	$theValue = ($x_gastos_prov3 != "") ? doubleval($x_gastos_prov3) : "NULL";
	$fieldList["`gastos_prov3`"] = $theValue;


	$theValue = ($x_otro_prov != "") ? doubleval($x_otro_prov) : "NULL";
	$fieldList["`otro_prov`"] = $theValue;


	$theValue = ($x_gastos_empleados != "") ? doubleval($x_gastos_empleados) : "NULL";
	$fieldList["`gastos_empleados`"] = $theValue;


	$theValue = ($x_gastos_renta_negocio != "") ? " '" . doubleval($x_gastos_renta_negocio) . "'" : "Null";
	$fieldList["`gastos_renta_negocio`"] = $theValue;


	$theValue = ($x_gastos_renta_casa != "") ? doubleval($x_gastos_renta_casa) : "0";
	$fieldList["`gastos_renta_casa`"] = $theValue;


	$theValue = ($x_gastos_credito_hipotecario != "") ? " '" . doubleval($x_gastos_credito_hipotecario) . "'" : "Null";
	$fieldList["`gastos_credito_hipotecario`"] = $theValue;

	$theValue = ($x_gastos_otros != "") ? " '" . doubleval($x_gastos_otros) . "'" : "Null";
	$fieldList["`gastos_otros`"] = $theValue;


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




//REFERENCIAS CICLO


	$x_counter = 1;
	while($x_counter < 6){

		$fieldList = NULL;
		// Field cliente_id
//		$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";
		$fieldList["`solicitud_id`"] = $x_solicitud_id;

		$x_aux = "x_nombre_completo_ref_$x_counter";
		$x_aux = $$x_aux;
		if($x_aux != ""){
		
			// Field nombre_completo
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_aux) : $x_aux; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`nombre_completo`"] = $theValue;
		
			// Field telefono
			$aux_tel = "x_telefono_ref_$x_counter";
			$uax_tel = $$aux_tel;
			
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($uax_tel) : $uax_tel; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`telefono`"] = $theValue;
			
			$aux_parent = "x_parentesco_tipo_id_ref_$x_counter";
			$aux_parent = $$aux_parent;
			
			// Field parentesco_tipo_id
			$theValue = ($aux_parent != "") ? intval($aux_parent) : "NULL";
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




//CHECKLIST

$x_chk_done = 0;
$x_contador = 1;
while($x_contador < 33){
	
	$x_chk = "x_checklist_$x_contador";
	$x_chk = $$x_chk;
	
	$x_det_ck = "x_det_ck$x_contador";	
	$x_det_ck= $$x_det_ck;
	
	if(!empty($x_det_ck)){
		$x_det_ck = "'" . $x_det_ck ."'";
	}else{
		$x_det_ck = "NULL";
	}
	
	$sSql = "INSERT INTO solicitud_checklist values (0,$x_contador,$x_solicitud_id,$x_chk,$x_det_ck)";

	$x_result = phpmkr_query($sSql, $conn);
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
		phpmkr_query('rollback;', $conn);	 
		exit();
	}
	if($x_contador < 26){
		$x_chk_done = $x_chk_done + $x_chk;		
	}
		
	$x_contador++;	
}


//GENERA CASO CRM


//GENERAR TAREA CRM SI NO SE COMPLETO EL CHKLIST
include_once("../datefunc.php");

if($x_chk_done < 25){

	$sSqlWrk = "
	SELECT *
	FROM 
		crm_playlist
	WHERE 
		crm_playlist.crm_caso_tipo_id = 1
		AND crm_playlist.orden = 1
	";
	
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$datawrk = phpmkr_fetch_array($rswrk);
	$x_crm_playlist_id = $datawrk["crm_playlist_id"];
	$x_prioridad_id = $datawrk["prioridad_id"];	
	$x_asunto = $datawrk["asunto"];	
	$x_descripcion = $datawrk["descripcion"];		
	$x_tarea_tipo_id = $datawrk["tarea_fuente"];		
	$x_orden = $datawrk["orden"];	
	$x_dias_espera = $datawrk["dias_espera"];		
	@phpmkr_free_result($rswrk);


	//Fecha Vencimiento
	$temptime = strtotime(ConvertDateToMysqlFormat($x_fecha_registro));	
	$temptime = DateAdd('w',$x_dias_espera,$temptime);
	$fecha_venc = strftime('%Y-%m-%d',$temptime);			
	$x_dia = strftime('%A',$temptime);
	if($x_dia == "SUNDAY"){
		$temptime = strtotime($fecha_venc);
		$temptime = DateAdd('w',1,$temptime);
		$fecha_venc = strftime('%Y-%m-%d',$temptime);
	}
	$temptime = strtotime($fecha_venc);



	$x_origen = 1;
	$x_bitacora = "Originacion de Credito - (".FormatDateTime($x_fecha_registro,7)." - ".$x_hora_registro.")";

	$x_bitacora .= "\n";
	$x_bitacora .= "$x_asunto - $x_descripcion ";	
	
	$x_credito_id = "NULL";
	

	$sSqlWrk = "
	SELECT usuario_id
	FROM 
		promotor
	WHERE 
		promotor_id = $x_promotor_id
	LIMIT 1
	";
	
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$datawrk = phpmkr_fetch_array($rswrk);
	$x_usuario_id = $datawrk["usuario_id"];
	echo("usuario id".$x_usuario_id ."....");
	@phpmkr_free_result($rswrk);
	

	$sSql = "INSERT INTO crm_caso values (0,1,1,1,".$x_cliente_id.",'".ConvertDateToMysqlFormat($x_fecha_registro)."',$x_origen,$x_usuario_id,'$x_bitacora','".ConvertDateToMysqlFormat($x_fecha_registro)."',$x_solicitud_id,$x_credito_id)";

	$x_result = phpmkr_query($sSql, $conn);
	$x_crm_caso_id = mysql_insert_id();	
	
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL:  primero caso' . $sSql;
		phpmkr_query('rollback;', $conn);	 
		exit();
	}


	$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".ConvertDateToMysqlFormat($x_fecha_registro)."','".$x_hora_registro."','$fecha_venc',NULL,NULL,NULL, 1, 1, 7, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";

	$x_result = phpmkr_query($sSql, $conn);
	
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: primero tarea ' . $sSql;
		phpmkr_query('rollback;', $conn);	 
		exit();
	}

	//Bitacora solicitud aqui
	$sSql = "INSERT INTO solicitud_comment values (0,$x_solicitud_id,'$x_bitacora',NULL)";
	phpmkr_query($sSql, $conn) or die("Failed to execute query 1: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	


}else{
//CHKLIST COMPLETO GENERA TAREA DE VALIDACION DE DATOS

	$sSqlWrk = "
	SELECT *
	FROM 
		crm_playlist
	WHERE 
		crm_playlist.crm_caso_tipo_id = 1
		AND crm_playlist.orden = 2
	";
	
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$datawrk = phpmkr_fetch_array($rswrk);
	$x_crm_playlist_id = $datawrk["crm_playlist_id"];
	$x_prioridad_id = $datawrk["prioridad_id"];	
	$x_asunto = $datawrk["asunto"];	
	$x_descripcion = $datawrk["descripcion"];		
	$x_tarea_tipo_id = $datawrk["tarea_fuente"];	
	$x_orden = $datawrk["orden"];		
	$x_dias_espera = $datawrk["dias_espera"];			
	@phpmkr_free_result($rswrk);

	//Fecha Vencimiento
	$temptime = strtotime(ConvertDateToMysqlFormat($x_fecha_registro));	
	$temptime = DateAdd('w',$x_dias_espera,$temptime);
	$fecha_venc = strftime('%Y-%m-%d',$temptime);			
	$x_dia = strftime('%A',$temptime);
	if($x_dia == "SUNDAY"){
		$temptime = strtotime($fecha_venc);
		$temptime = DateAdd('w',1,$temptime);
		$fecha_venc = strftime('%Y-%m-%d',$temptime);
	}
	$temptime = strtotime($fecha_venc);


//Coordinador de credito
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

	$x_origen = 1;
	
	$x_bitacora = "Originacion de Credito - (".FormatDateTime($x_fecha_registro,7).")";
	$x_bitacora .= "\n";
	$x_bitacora .= "$x_asunto - $x_descripcion";	
	
	$x_credito_id = "NULL";

	$sSql = "INSERT INTO crm_caso values (0,1,1,1,$x_cliente_id,'".ConvertDateToMysqlFormat($x_fecha_registro)."',$x_origen,$x_usuario_id,'$x_bitacora','".ConvertDateToMysqlFormat($x_fecha_registro)."',$x_solicitud_id,$x_credito_id)";

	$x_result = phpmkr_query($sSql, $conn);
	$x_crm_caso_id = mysql_insert_id();	
	
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: segundo caso ' . $sSql;
		phpmkr_query('rollback;', $conn);	 
		exit();
	}


	$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id,$x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".ConvertDateToMysqlFormat($x_fecha_registro)."','".$x_hora_registro."','$fecha_venc',NULL,NULL,NULL, 1, 1, 2,  $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";

	$x_result = phpmkr_query($sSql, $conn) ;
	
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: segundo tarea' . $sSql;
		phpmkr_query('rollback;', $conn);	 
		exit();
	}

	//Bitacora solicitud
	$sSql = "INSERT INTO solicitud_comment values (0,$x_solicitud_id,'$x_bitacora',NULL)";
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);






}


	phpmkr_query('commit;', $conn);	 
	
	//mensaje alta exitosa
	$_SESSION["mensajeAlta"] = "REGISTRO EXITOSO, YA PUEDE CERRAR ESTA VENTANA";
	
	
	}//function inserta credito Individual
/**************************************************/	
/**********************************************************************************************************************************************************************************************************************************************************************************************************/



/**************************************************/	
function insertaCreditoSolidario(){
	$conn = phpmkr_db_connect(HOST, USER, PASS,DB);
	
	// Get fields from form
	$x_credito_tipo_id =@$_POST["x_tipo_credito"];
	$x_creditoSolidario_id = @$_POST["x_creditoSolidario_id"];
	$x_nombre_grupo = @$_POST["x_nombre_grupo"];
	$x_promotor = @$_POST["x_promotor_id"];
	$x_importe_solicitado = @$_POST["x_importe_solicitado"];
	$x_plazo_id = @$_POST["x_plazo_id"];
	$x_forma_pago_id = @$_POST["x_forma_pago_id"];	

	$x_actividad_id = $_POST["x_actividad_id"];
	$x_actividad_desc = $_POST["x_actividad_desc"];
	
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
	echo("fecha registro".$x_fecha_registro."...");
	
	//FORMAR ARRAY CON VALORES DE LOS CAMPOS PARA INSERTAR EN LA BASE DE DATOS
	$conn = phpmkr_db_connect(HOST, USER, PASS,DB);
	
	
		phpmkr_query('START TRANSACTION;', $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: BEGIN TRAN');

	//SOLICITUD

	// Field credito_tipo_id
	$theValue = ($x_credito_tipo_id != "") ? intval($x_credito_tipo_id) : "NULL";
	$fieldList["`credito_tipo_id`"] = $theValue;

	// Field solicitud_status_id
	$theValue = ($x_solicitud_status_id != "") ? intval($x_solicitud_status_id) : "NULL";
	$fieldList["`solicitud_status_id`"] = 1;

	// Field folio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_folio) : $x_folio; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`folio`"] = $theValue;

	// Field fecha_registro
	$theValue = ($x_fecha_registro != "") ? " '" . ConvertDateToMysqlFormat($x_fecha_registro) . "'" : "Null";
	$fieldList["`fecha_registro`"] = $theValue;

	// Field promotor_id
	$theValue = ($x_promotor != "") ? intval($x_promotor) : "NULL";
	$fieldList["`promotor_id`"] = $theValue;

	// Field importe_solicitado
	$theValue = ($x_importe_solicitado != "") ? " '" . doubleval($x_importe_solicitado) . "'" : "Null";
	$fieldList["`importe_solicitado`"] = $theValue;

	// Field plazo
	$theValue = ($x_plazo_id != "") ? intval($x_plazo_id) : "NULL";
	$fieldList["`plazo_id`"] = $theValue;

	$theValue = ($x_forma_pago_id != "") ? intval($x_forma_pago_id) : "NULL";
	$fieldList["`forma_pago_id`"] = $theValue;

	// Field contrato
	$theValue = $x_contrato[0];
	$theValue = ($theValue != "") ? intval($theValue) : "NULL";
	$fieldList["`contrato`"] = $theValue;

	// Field pagare
	$theValue = $x_pagare[0];
	$theValue = ($theValue != "") ? intval($theValue) : "NULL";
	$fieldList["`pagare`"] = $theValue;


	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_comentario_promotor) : $x_comentario_promotor; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`comentario_promotor`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_comentario_comite) : $x_comentario_comite; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`comentario_comite`"] = $theValue;


	$theValue = ($x_actividad_id != "") ? intval($x_actividad_id) : "0";
	$fieldList["`actividad_id`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_actividad_desc) : $x_actividad_desc; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`actividad_desc`"] = $theValue;

	//se guarada el nombre del grupo
	
	$theValue = (!get_magic_quotes_gpc()) ?  addslashes($x_nombre_grupo) : $x_nombre_grupo; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`grupo_nombre`"] = $theValue;
	
		// Field fromato_nuevo
	$theValue = 1;
	$theValue = ($theValue != "") ? intval($theValue) : "NULL";
	$fieldList["`formato_nuevo`"] = $theValue;



	// insert into database
	$sSql = "INSERT INTO `solicitud` (";
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



// se inserta en credito solidario para gaurdar lo valores de los integrantes de l grupo
	
	
	$fieldList = NULL;
	// Field nombre_grupo
	$theValue = (!get_magic_quotes_gpc()) ?  addslashes($x_nombre_grupo) : $x_nombre_grupo; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre_grupo`"] = $theValue;

	// Field promotor
	$theValue = (!get_magic_quotes_gpc()) ?  addslashes($x_promotor) : $x_promotor; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`promotor`"] = $theValue;

	// Field representante_sugerido
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_representante_sugerido) : $x_representante_sugerido; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`representante_sugerido`"] = $theValue;

	// Field tesorero
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_tesorero) : $x_tesorero; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`tesorero`"] = $theValue;

	// Field numero_integrantes
	$theValue = ($x_numero_integrantes != "") ? intval($x_numero_integrantes) : "NULL";
	$fieldList["`numero_integrantes`"] = $theValue;

	// Field integrante_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_integrante_1) : $x_integrante_1; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`integrante_1`"] = $theValue;

	// Field monto_1
	$theValue = ($x_monto_1 != "") ? " '" . $x_monto_1 . "'" : "NULL";
	$fieldList["`monto_1`"] = $theValue;
	
	$x_new_one = "newone";
	$theValueN = (!get_magic_quotes_gpc()) ? addslashes($x_new_one) : $x_new_one; 
	$theValueN = ($theValueN != "") ? " '" . $theValueN . "'" : "NULL";
	
	
	// field rol_integrante
	$theValue = ($x_rol_integrante_1 != "") ? intval($x_rol_integrante_1) : "NULL";
	$fieldList["`rol_integrante_1`"] = $theValue;
	
		// Field cliente_id_10	
	$fieldList["`cliente_id_1`"] = $theValueN;

	// Field integrante_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_integrante_2) : $x_integrante_2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`integrante_2`"] = $theValue;

	// Field monto_2
	$theValue = ($x_monto_2 != "") ? " '" . $x_monto_2 . "'" : "NULL";
	$fieldList["`monto_2`"] = $theValue;
	
	// field rol_integrante
	$theValue = ($x_rol_integrante_2 != "") ? intval($x_rol_integrante_2) : "NULL";
	$fieldList["`rol_integrante_2`"] = $theValue;
	
		// Field cliente_id_10	
	$fieldList["`cliente_id_2`"] = $theValueN;

	// Field integrante_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_integrante_3) : $x_integrante_3; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`integrante_3`"] = $theValue;

	// Field monto_3
	$theValue = ($x_monto_3 != "") ? " '" . $x_monto_3 . "'" : "NULL";
	$fieldList["`monto_3`"] = $theValue;
	
	// field rol_integrante
	$theValue = ($x_rol_integrante_3 != "") ? intval($x_rol_integrante_3) : "NULL";
	$fieldList["`rol_integrante_3`"] = $theValue;
	
		// Field cliente_id_10	
	$fieldList["`cliente_id_3`"] = $theValueN;

	// Field integrante_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_integrante_4) : $x_integrante_4; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`integrante_4`"] = $theValue;

	// Field monto_4
	$theValue = ($x_monto_4 != "") ? " '" . $x_monto_4 . "'" : "NULL";
	$fieldList["`monto_4`"] = $theValue;
	
	// field rol_integrante
	$theValue = ($x_rol_integrante_4 != "") ? intval($x_rol_integrante_4) : "NULL";
	$fieldList["`rol_integrante_4`"] = $theValue;
		// Field cliente_id_10	
	$fieldList["`cliente_id_4`"] = $theValueN;

	// Field integrante_5
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_integrante_5) : $x_integrante_5; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`integrante_5`"] = $theValue;

	// Field monto_5
	$theValue = ($x_monto_5 != "") ? " '" . $x_monto_5 . "'" : "NULL";
	$fieldList["`monto_5`"] = $theValue;
	
	// field rol_integrante
	$theValue = ($x_rol_integrante_5 != "") ? intval($x_rol_integrante_5) : "NULL";
	$fieldList["`rol_integrante_5`"] = $theValue;
	
		// Field cliente_id_10	
	$fieldList["`cliente_id_5`"] = $theValueN;

	// Field integrante_6
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_integrante_6) : $x_integrante_6; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`integrante_6`"] = $theValue;

	// Field monto_6
	$theValue = ($x_monto_6 != "") ? " '" . $x_monto_6 . "'" : "NULL";
	$fieldList["`monto_6`"] = $theValue;
	
	// field rol_integrante
	$theValue = ($x_rol_integrante_6 != "") ? intval($x_rol_integrante_6) : "NULL";
	$fieldList["`rol_integrante_6`"] = $theValue;
	
		// Field cliente_id_10	
	$fieldList["`cliente_id_6`"] = $theValueN;

	// Field integrante_7
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_integrante_7) : $x_integrante_7; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`integrante_7`"] = $theValue;

	// Field monto_7
	$theValue = ($x_monto_7 != "") ? " '" . $x_monto_7 . "'" : "NULL";
	$fieldList["`monto_7`"] = $theValue;
	
		// Field cliente_id_10	
	$fieldList["`cliente_id_7`"] = $theValueN;
	
	// field rol_integrante
	$theValue = ($x_rol_integrante_8 != "") ? intval($x_rol_integrante_8) : "NULL";
	$fieldList["`rol_integrante_8`"] = $theValue;

	// Field integrante_8
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_integrante_8) : $x_integrante_8; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`integrante_8`"] = $theValue;

	// Field monto_8
	$theValue = ($x_monto_8 != "") ? " '" . $x_monto_8 . "'" : "NULL";
	$fieldList["`monto_8`"] = $theValue;
	
		// Field cliente_id_10	
	$fieldList["`cliente_id_8`"] = $theValueN;
	

	// Field integrante_9
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_integrante_9) : $x_integrante_9; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`integrante_9`"] = $theValue;
	
	

	// Field monto_9
	$theValue = ($x_monto_9 != "") ? " '" . $x_monto_9 . "'" : "NULL";
	$fieldList["`monto_9`"] = $theValue;
	
	// field rol_integrante
	$theValue = ($x_rol_integrante_9 != "") ? intval($x_rol_integrante_9) : "NULL";
	$fieldList["`rol_integrante_9`"] = $theValue;
	
	// Field cliente_id_10	
	$fieldList["`cliente_id_9`"] = $theValueN;

	// Field integrante_10
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_integrante_10) : $x_integrante_10; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`integrante_10`"] = $theValue;

	// Field monto_10
	$theValue = ($x_monto_10 != "") ? " '" . $x_monto_10 . "'" : "NULL";
	$fieldList["`monto_10`"] = $theValue;
	
	// field rol_integrante
	$theValue = ($x_rol_integrante_10 != "") ? intval($x_rol_integrante_10) : "NULL";
	$fieldList["`rol_integrante_10`"] = $theValue;
	
	
	// Field cliente_id_10	
	$fieldList["`cliente_id_10`"] = $theValueN;
	
	

	// Field monto_total
	$theValue = ($x_monto_total != "") ? " '" . $x_monto_total . "'" : "NULL";
	$fieldList["`monto_total`"] = $theValue;

	
	// Field fecha
	$theValue = ($x_fecha != "") ? " '" . ConvertDateToMysqlFormat($x_fecha) . "'" : "NULL";
	$fieldList["`fecha_registro`"] = $theValue;
	
	// el id de la solicitud
	$fieldList["`solicitud_id`"] = $x_solicitud_id;
	

	// insert into database
	$strsql = "INSERT INTO `creditosolidario` (";
	$strsql .= implode(",", array_keys($fieldList));
	$strsql .= ") VALUES (";
	$strsql .= implode(",", array_values($fieldList));
	$strsql .= ")";
	$x_result = phpmkr_query($strsql, $conn);
	
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: ' . $strsql;
		phpmkr_query('rollback;', $conn);	 
	 	exit();
	}
	$x_grupo_id = mysql_insert_id();
	
	
	
	




	



//SOLICITUD GRUPO

	$fieldList = NULL;
	// Field solicitud_id
	$fieldList["`solicitud_id`"] = $x_solicitud_id;

	$fieldList["`grupo_id`"] = $x_grupo_id;

	// insert into database
	$sSql = "INSERT INTO `solicitud_grupo` (";
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
	
	//fin de la transaccion
	
	phpmkr_query('commit;', $conn);
	
	
	$_SESSION["mensajeAlta"] = "REGISTRO EXITOSO, YA PUEDE CERRAR ESTA VENTANA" ;	
	
	
	
	
	}






/*************************************************/	
function insertaCreditoMaquinaria(){
	$conn = phpmkr_db_connect(HOST, USER, PASS,DB);
	
	// Get fields from form
	//basicos
	$x_solicitud_id = 0;
	$x_credito_tipo_id = @$_POST["x_tipo_credito"];
	$x_solicitud_status_id = 1;
	global $x_folio;
	$x_folio = "01ABC";
	$x_fecha_registro = @$_POST["x_fecha_registro"];
		
	$x_promotor_id = @$_POST["x_promotor_id"];
	$x_importe_solicitado = @$_POST["x_importe_solicitado"];
	$x_plazo_id = @$_POST["x_plazo_id"];
	$x_forma_pago_id = @$_POST["x_forma_pago_id"];	

	$x_actividad_id = $_POST["x_actividad_id"];
	$x_actividad_desc = $_POST["x_actividad_desc"];
	
	//SE VAN A LA TABLA CLIENTE
	$x_nombre_completo = @$_POST["x_nombre"]; 
	$x_apellido_paterno = @$_POST["x_apellido_paterno"];
	$x_apellido_materno = @$_POST["x_apellido_materno"];  	
	$x_tit_rfc = @$_POST["x_rfc"]; 
	$x_tit_curp = @$_POST["x_curp"]; 	
	$x_tit_fecha_nac = @$_POST["x_fecha_nacimiento"]; 	
	$x_sexo = @$_POST["x_sexo"]; 
	$x_numero_hijos = @$_POST["x_integrantes_familia"];
	$x_numero_hijos_dep = @$_POST["x_dependientes"]; 
	$x_email = @$_POST["x_correo_electronico"]; 
	$x_nombre_conyuge = @$_POST["x_esposa"]; 
	
	
	//SE VAN A TABLA DOMICILIO 
	$x_calle = @$_POST["x_calle_domicilio"]; 
	$x_colonia = @$_POST["x_colonia_domicilio"]; 
	
	
	//DELEGACION NO ESTA REQUERIDA EN EL FORMATO
	// $x_delegacion_id = @$_POST["delegacion_domicilio"]; //ya pero necesito revisarlo no esta en formulario
	$x_entidad_domicilio = @$_POST["x_entidad_domicilio"]; 	
	$x_codigo_postal = @$_POST["x_codigo_postal_domicilio"]; 
	$x_ubicacion = @$_POST["x_ubicacion_domicilio"]; 
	$x_vivienda_tipo_id = @$_POST["x_tipo_vivienda"]; // ya pero revisarlo es un lista
	$x_telefono = @$_POST["x_telefono_domicilio"]; 	
	$x_telefono_movil = @$_POST["x_celular"]; 	
	$x_telefono_secundario = @$_POST["x_otro_tel_domicilio_1"];
	$x_antiguedad = @$_POST["x_antiguedad"]; 
	$x_propietario = @$_POST["x_tel_arrendatario_domicilio"]; // ya pero en la base de datos hace rerencia la nombre del arrendatario
	
	
	$x_delegacion_id = @$_POST["x_delegacion_id"];
	$x_delegacion_id2 = @$_POST["x_delegacion_id2"];
	
	
	// SE GUARDARA EN DOMICILIO CON UN TIPO 2
	$x_calle2 = @$_POST["x_calle_negocio"]; 
	$x_colonia2 = @$_POST["x_colonia_negocio"]; 
	
	$x_entidad_negocio = @$_POST["x_entidad_negocio"];
	
	$x_ubicacion2 = @$_POST["x_ubicacion_negocio"]; 
	$x_codigo_postal2 = @$_POST["x_codigo_postal_negocio"];
	$x_vivienda_tipo_id2 = @$_POST["x_tipo_local_negocio"];
	$x_antiguedad2 = @$_POST["x_antiguedad_negocio"]; 
	$x_propietario2 = @$_POST["x_tel_arrendatario_negocio"]; //ya se pueso en un campo que es para nombre .....
	$x_telefono2 = @$_POST["x_tel_negocio"]; 
	
	
	
	
	//GASTO
	$x_renta_mensula_domicilio = @$_POST["x_renta_mensula_domicilio"]; 	//DOMICILIO
	$x_renta_mensula = @$_POST["x_renta_mensual"];  //NEGOCIO
	
	
	// estos no estan en otra tabla...debe de guarda en la principal credito adquiMaquinaria.
	
	$x_solicitud_compra = @$_POST["x_solicitud_compra"]; 
	$x_telefono_sec = @$_POST["x_otro_telefono_domicilio_2"]; //ya este campo se guaradara en el formato de pyme
	
	$x_empresa = @$_POST["x_giro_negocio"]; 	
	//$x_propiedad_hipot = @$_POST["x_propiedad_hipot"];// este campo no esta en la tabla de domicilio.
	$x_referencia_com_1 = @$_POST["x_referencia_com_1"]; //YA
	$x_referencia_com_2 = @$_POST["x_referencia_com_2"]; //YA
	$x_referencia_com_3 = @$_POST["x_referencia_com_3"]; //YA
	$x_referencia_com_4 = @$_POST["x_referencia_com_4"]; //YA
	$x_tel_referencia_1 = @$_POST["x_tel_referencia_1"]; //YA
	$x_tel_referencia_2 = @$_POST["x_tel_referencia_2"]; //YA
	$x_tel_referencia_3 = @$_POST["x_tel_referencia_3"]; //YA
	$x_tel_referencia_4 = @$_POST["x_tel_referencia_4"]; //YA
	$x_parentesco_ref_1 = @$_POST["x_parentesco_ref_1"]; //ya
	$x_parentesco_ref_2 = @$_POST["x_parentesco_ref_2"]; //YA
	$x_parentesco_ref_3 = @$_POST["x_parentesco_ref_3"]; //YA
	$x_parentesco_ref_4 = @$_POST["x_parentesco_ref_4"]; //YA
	
	//SE GUARADA EN adquimaquinaria
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
	
	
	//SE GAURADA EN adquiMaquinaria.
	$x_giro_negocio = @$_POST["x_giro_negocio"];
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
	$x_credito_tipo_id = @$_POST["x_tipo_credito"];
	$x_solicitud_status_id = 1;
	$x_folio = "01ABC";
	$x_fecha_contratacion = @$_POST["x_fecha"];
	
	//conectar con la base
	$conn = phpmkr_db_connect(HOST, USER, PASS,DB);
	//ARRAY CON LOS VALORES DE LOS CAMPOS
	
	//SOLICITUD

	// Field credito_tipo_id
	$theValue = ($x_credito_tipo_id != "") ? intval($x_credito_tipo_id) : "NULL";
	$fieldList["`credito_tipo_id`"] = $theValue;

	// Field solicitud_status_id
	$theValue = ($x_solicitud_status_id != "") ? intval($x_solicitud_status_id) : "NULL";
	$fieldList["`solicitud_status_id`"] = $theValue;

	// Field folio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_folio) : $x_folio; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`folio`"] = $theValue;

	// Field fecha_registro......checar la fecha en el formulario
	$theValue = ($x_fecha_registro != "") ? " '" . ConvertDateToMysqlFormat($x_fecha_registro) . "'" : "Null";
	$fieldList["`fecha_registro`"] = $theValue;

	// Field promotor_id
	$theValue = ($x_promotor_id != "") ? intval($x_promotor_id) : "NULL";
	$fieldList["`promotor_id`"] = $theValue;

	// Field importe_solicitado
	$theValue = ($x_importe_solicitado != "") ? " '" . doubleval($x_importe_solicitado) . "'" : "Null";
	$fieldList["`importe_solicitado`"] = $theValue;

	// Field plazo
	$theValue = ($x_plazo_id != "") ? intval($x_plazo_id) : "NULL";
	$fieldList["`plazo_id`"] = $theValue;

	$theValue = ($x_forma_pago_id != "") ? intval($x_forma_pago_id) : "NULL";
	$fieldList["`forma_pago_id`"] = $theValue;

	// Field contrato
	$theValue = $x_contrato[0];
	$theValue = ($theValue != "") ? intval($theValue) : "NULL";
	$fieldList["`contrato`"] = $theValue;

	// Field pagare
	$theValue = $x_pagare[0];
	$theValue = ($theValue != "") ? intval($theValue) : "NULL";
	$fieldList["`pagare`"] = $theValue;


	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_comentario_promotor) : $x_comentario_promotor; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`comentario_promotor`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_comentario_comite) : $x_comentario_comite; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`comentario_comite`"] = $theValue;


	$theValue = ($x_actividad_id != "") ? intval($x_actividad_id) : "0";
	$fieldList["`actividad_id`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_actividad_desc) : $x_actividad_desc; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`actividad_desc`"] = $theValue;
		// Field fromato_nuevo
	$theValue = 1;
	$theValue = ($theValue != "") ? intval($theValue) : "NULL";
	$fieldList["`formato_nuevo`"] = $theValue;


	// insert into database
	$sSql = "INSERT INTO `solicitud` (";
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
	
	//solicitud agregada con un numero de filio asignado
	
	
	//CLIENTE

	$fieldList = NULL;
	// Field solicitud_id
//	$fieldList["`solicitud_id`"] = $x_solicitud_id;

	// Field usuario_id
//	$theValue = ($x_usuario_id != "") ? intval($x_usuario_id) : "NULL";
	$fieldList["`usuario_id`"] = 0;

	// Field nombre_completo
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_nombre_completo) : $x_nombre_completo; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre_completo`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_apellido_paterno) : $x_apellido_paterno; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`apellido_paterno`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_apellido_materno) : $x_apellido_materno; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`apellido_materno`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_tit_rfc) : $x_tit_rfc; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`rfc`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_tit_curp) : $x_tit_curp; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`curp`"] = $theValue;


	// Field tipo_negocio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_tipo_negocio) : $x_tipo_negocio; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`tipo_negocio`"] = $theValue;


	// Field nacimiento
	$theValue = ($x_tit_fecha_nac != "") ? " '" . ConvertDateToMysqlFormat($x_tit_fecha_nac) . "'" : "Null";
	$fieldList["`fecha_nac`"] = $theValue;




	// Field edad
	$theValue = ($x_edad != "") ? intval($x_edad) : "NULL";
	$fieldList["`edad`"] = $theValue;

	// Field sexo
	$theValue = ($x_sexo != "") ? intval($x_sexo) : "NULL";
	$fieldList["`sexo`"] = $theValue;

	// Field estado_civil_id
	$theValue = ($x_estado_civil_id != "") ? intval($x_estado_civil_id) : "0";
	$fieldList["`estado_civil_id`"] = $theValue;

	// Field numero_hijos
	$theValue = ($x_numero_hijos != "") ? intval($x_numero_hijos) : "0";
	$fieldList["`numero_hijos`"] = $theValue;


	// Field numero_hijos_dep
	$theValue = ($x_numero_hijos_dep != "") ? intval($x_numero_hijos_dep) : "0";
	$fieldList["`numero_hijos_dep`"] = $theValue;

	// Field nombre_conyuge
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_nombre_conyuge) : $x_nombre_conyuge; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre_conyuge`"] = $theValue;

	// Field email
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_email) : $x_email; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`email`"] = $theValue;


	$theValue = ($x_nacionalidad_id != "") ? intval($x_nacionalidad_id) : "0";
	$fieldList["`nacionalidad_id`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_empresa) : $x_empresa; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`empresa`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_piesto) : $x_puesto; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`puesto`"] = $theValue;

	// Field fecha_registro
	$theValue = ($x_fecha_contratacion != "") ? " '" . ConvertDateToMysqlFormat($x_fecha_contratacion) . "'" : "Null";
	$fieldList["`fecha_contratacion`"] = $theValue;

	$theValue = ($x_salario_mensual != "") ? " '" . doubleval($x_salario_mensual) . "'" : "Null";
	$fieldList["`salario_mensual`"] = $theValue;


	// Field promotor_id
	$theValue = ($x_promotor_id != "") ? intval($x_promotor_id) : "NULL";
	$fieldList["`promotor_id`"] = $theValue;


	// insert into database
	$sSql = "INSERT INTO `cliente` (";
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
	$x_cliente_id = mysql_insert_id();

	
	//SOLICITUD CLIENTE

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

	
	//DIRECCION PART

	$fieldList = NULL;
	// Field cliente_id
//	$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";
	$fieldList["`cliente_id`"] = $x_cliente_id;

	// Field aval_id
//	$theValue = ($x_aval_id != "") ? intval($x_aval_id) : "NULL";
	$fieldList["`aval_id`"] = 0;

	// Field promotor_id
//	$theValue = ($x_promotor_id != "") ? intval($x_promotor_id) : "NULL";
	$fieldList["`promotor_id`"] = 0;

	// Field direccion_tipo_id
//	$theValue = ($x_direccion_tipo_id != "") ? intval($x_direccion_tipo_id) : "NULL";
	$fieldList["`direccion_tipo_id`"] = 1;

	// Field calle
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_calle) : $x_calle; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`calle`"] = $theValue;

	// Field colonia
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_colonia) : $x_colonia; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`colonia`"] = $theValue;

	// Field delegacion_id
	$theValue = ($x_delegacion_id != "") ? intval($x_delegacion_id) : "0";
	$fieldList["`delegacion_id`"] = $theValue;

	// Field propietario
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_propietario) : $x_propietario; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`propietario`"] = $theValue;

/*
	// Field entidad
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_entidad) : $x_entidad; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`entidad`"] = $theValue;
*/
	// Field codigo_postal
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_codigo_postal) : $x_codigo_postal; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`codigo_postal`"] = $theValue;

	// Field ubicacion
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_ubicacion) : $x_ubicacion; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ubicacion`"] = $theValue;

	// Field antiguedad
	$theValue = ($x_antiguedad != "") ? intval($x_antiguedad) : "0";
	$fieldList["`antiguedad`"] = $theValue;

	// Field vivienda_tipo_id
	$theValue = ($x_vivienda_tipo_id != "") ? intval($x_vivienda_tipo_id) : "0";
	$fieldList["`vivienda_tipo_id`"] = $theValue;

	// Field otro_tipo_vivienda
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_otro_tipo_vivienda) : $x_otro_tipo_vivienda; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`otro_tipo_vivienda`"] = $theValue;

	// Field telefono
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_telefono) : $x_telefono; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono`"] = $theValue;
	
	// Field ENTIDAD
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_entidad_domicilio) : $x_entidad_domicilio; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`entidad`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_telefono_movil) : $x_telefono_movil; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono_movil`"] = $theValue;
	

	// Field telefono_secundario
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_telefono_secundario) : $x_telefono_secundario; 
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
		echo phpmkr_error() . '<br>SQL AQUI: ' . $sSql;
		phpmkr_query('rollback;', $conn);	 
	 	exit();
	}


//DIRECCION NEG

	$fieldList = NULL;
	// Field cliente_id
//	$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";
	$fieldList["`cliente_id`"] = $x_cliente_id;

	// Field aval_id
//	$theValue = ($x_aval_id != "") ? intval($x_aval_id) : "NULL";
	$fieldList["`aval_id`"] = 0;

	// Field promotor_id
//	$theValue = ($x_promotor_id != "") ? intval($x_promotor_id) : "NULL";
	$fieldList["`promotor_id`"] = 0;

	// Field direccion_tipo_id
//	$theValue = ($x_direccion_tipo_id != "") ? intval($x_direccion_tipo_id) : "NULL";
	$fieldList["`direccion_tipo_id`"] = 2;

	// Field calle
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_calle2) : $x_calle2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`calle`"] = $theValue;

	// Field colonia
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_colonia2) : $x_colonia2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`colonia`"] = $theValue;

	// Field delegacion_id
	$theValue = ($x_delegacion_id2 != "") ? intval($x_delegacion_id2) : "0";
	$fieldList["`delegacion_id`"] = $theValue;

	// Field codigo_postal
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_codigo_postal2) : $x_codigo_postal2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`codigo_postal`"] = $theValue;

	// Field ubicacion
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_ubicacion2) : $x_ubicacion2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ubicacion`"] = $theValue;

	// Field antiguedad
	$theValue = ($x_antiguedad != "") ? intval($x_antiguedad2) : "0";
	$fieldList["`antiguedad`"] = $theValue;

	// Field vivienda_tipo_id
	$theValue = ($x_vivienda_tipo_id2 != "") ? intval($x_vivienda_tipo_id2) : "0";
	$fieldList["`vivienda_tipo_id`"] = $theValue;

	// Field otro_tipo_vivienda
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_otro_tipo_vivienda2) : $x_otro_tipo_vivienda2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`otro_tipo_vivienda`"] = $theValue;
	
	// Field ENTIDAD
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_entidad_negocio) : $x_entidad_negocio; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`entidad`"] = $theValue; 

	// Field telefono
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_telefono2) : $x_telefono2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono`"] = $theValue;

	// Field telefono_secundario
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_telefono_secundario2) : $x_telefono_secundario2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono_secundario`"] = $theValue;
	
	// Field propietario
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_propietario2) : $x_propietario2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`propietario`"] = $theValue;
	
	
	


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

	
	//GARANTIAS

	if($x_garantia_desc != ""){
		$fieldList = NULL;
		// Field cliente_id
	//	$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";
		$fieldList["`solicitud_id`"] = $x_solicitud_id;
	
	
	
		// Field valor
		$theValue = ($x_garantia_valor != "") ? " '" . doubleval($x_garantia_valor) . "'" : "Null";
		$fieldList["`valor`"] = $theValue;
	
	
		// insert into database
		$sSql = "INSERT INTO `garantia` (";
		$sSql .= implode(",", array_keys($fieldList));
		$sSql .= ") VALUES (";
		$sSql .= implode(",", array_values($fieldList));
		$sSql .= ")";
		/*$x_result = phpmkr_query($sSql, $conn);
		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		} */

	}
	
	//GASTOS

	$fieldList = NULL;
	// Field cliente_id
//	$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";
	$fieldList["`solicitud_id`"] = $x_solicitud_id;

	$theValue = ($x_renta_mensula != "") ? " '" . doubleval($x_renta_mensula) . "'" : "0";
	$fieldList["`gastos_renta_negocio`"] = $theValue;


	$theValue = ($x_renta_mensula_domicilio != "") ? doubleval($x_renta_mensula_domicilio) : "0";
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

	
	
	
	
	
	
	//REFERENCIAS CICLO


	$x_counter = 1;
	while($x_counter < 5){

		$fieldList = NULL;
		// Field cliente_id
//		$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";
		$fieldList["`solicitud_id`"] = $x_solicitud_id;

		$x_aux = "x_referencia_com_$x_counter";
		$x_aux = $$x_aux;
		if($x_aux != ""){
		
			// Field nombre_completo
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_aux) : $x_aux; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`nombre_completo`"] = $theValue;
		
			// Field telefono
			$aux_tel = "x_tel_referencia_$x_counter";
			$uax_tel = $$aux_tel;
			
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($uax_tel) : $uax_tel; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`telefono`"] = $theValue;
			
			$aux_parent = "x_parentesco_ref_$x_counter";
			$aux_parent = $$aux_parent;
			
			// Field parentesco_tipo_id
			$theValue = ($aux_parent != "") ? intval($aux_parent) : "NULL";
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
	








	
	//..GASTOS E INGRESOS....SE INSERTA EN LA TABLA ORIGINAL MAQUINARIA
	
	
	// Field referencia_com_1
	$fieldList = NULL;
	
	//	$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";
	$fieldList["`cliente_id`"] = $x_cliente_id;
	
	/*$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_referencia_com_1) : $x_referencia_com_1; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`referencia_com_1`"] = $theValue;	
	

	// Field referencia_com_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_referencia_com_2) : $x_referencia_com_2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`referencia_com_2`"] = $theValue;

	// Field referencia_com_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_referencia_com_3) : $x_referencia_com_3; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`referencia_com_3`"] = $theValue;

	// Field referencia_com_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_referencia_com_4) : $x_referencia_com_4; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`referencia_com_4`"] = $theValue;

	// Field tel_referencia_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_tel_referencia_1) : $x_tel_referencia_1; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`tel_referencia_1`"] = $theValue;

	// Field tel_referencia_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_tel_referencia_2) : $x_tel_referencia_2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`tel_referencia_2`"] = $theValue;

	// Field tel_referencia_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_tel_referencia_3) : $x_tel_referencia_3; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`tel_referencia_3`"] = $theValue;

	// Field tel_referencia_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_tel_referencia_4) : $x_tel_referencia_4; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`tel_referencia_4`"] = $theValue;

	// Field parentesco_ref_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_parentesco_ref_1) : $x_parentesco_ref_1; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`parentesco_ref_1`"] = $theValue;

	// Field parentesco_ref_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_parentesco_ref_2) : $x_parentesco_ref_2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`parentesco_ref_2`"] = $theValue;

	// Field parentesco_ref_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_parentesco_ref_3) : $x_parentesco_ref_3; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`parentesco_ref_3`"] = $theValue;

	// Field parentesco_ref_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_parentesco_ref_4) : $x_parentesco_ref_4; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`parentesco_ref_4`"] = $theValue; */
	
	// Field solicitud_compra 
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_solicitud_compra) : $x_solicitud_compra; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`solicitud_compra`"] = $theValue;
	
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_giro_negocio) : $x_giro_negocio; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`giro_negocio`"] = $theValue;
	
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_telefono_sec) : $x_telefono_sec; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`otro_telefono_domicilio_2`"] = $theValue;
	
	// Field ing_fam_negocio
	$theValue = ($x_ing_fam_negocio != "") ? " '" . $x_ing_fam_negocio . "'" : "NULL";
	$fieldList["`ing_fam_negocio`"] = $theValue;

	// Field ing_fam_otro_th
	$theValue = ($x_ing_fam_otro_th != "") ? " '" . $x_ing_fam_otro_th . "'" : "NULL";
	$fieldList["`ing_fam_otro_th`"] = $theValue;

	// Field ing_fam_1
	$theValue = ($x_ing_fam_1 != "") ? " '" . $x_ing_fam_1 . "'" : "NULL";
	$fieldList["`ing_fam_1`"] = $theValue;

	// Field ing_fam_2
	$theValue = ($x_ing_fam_2 != "") ? " '" . $x_ing_fam_2 . "'" : "NULL";
	$fieldList["`ing_fam_2`"] = $theValue;

	// Field ing_fam_deuda_1
	$theValue = ($x_ing_fam_deuda_1 != "") ? " '" . $x_ing_fam_deuda_1 . "'" : "NULL";
	$fieldList["`ing_fam_deuda_1`"] = $theValue;

	// Field ing_fam_deuda_2
	$theValue = ($x_ing_fam_deuda_2 != "") ? " '" . $x_ing_fam_deuda_2 . "'" : "NULL";
	$fieldList["`ing_fam_deuda_2`"] = $theValue;

	// Field ing_fam_total
	$theValue = ($x_ing_fam_total != "") ? " '" . $x_ing_fam_total . "'" : "NULL";
	$fieldList["`ing_fam_total`"] = $theValue;

	// Field ing_fam_cuales_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_ing_fam_cuales_1) : $x_ing_fam_cuales_1; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ing_fam_cuales_1`"] = $theValue;

	// Field ing_fam_cuales_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_ing_fam_cuales_2) : $x_ing_fam_cuales_2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ing_fam_cuales_2`"] = $theValue;

	// Field ing_fam_cuales_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_ing_fam_cuales_3) : $x_ing_fam_cuales_3; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ing_fam_cuales_3`"] = $theValue;

	// Field ing_fam_cuales_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_ing_fam_cuales_4) : $x_ing_fam_cuales_4; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ing_fam_cuales_4`"] = $theValue;

	// Field ing_fam_cuales_5
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_ing_fam_cuales_5) : $x_ing_fam_cuales_5; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ing_fam_cuales_5`"] = $theValue;

	// Field flujos_neg_ventas
	$theValue = ($x_flujos_neg_ventas != "") ? " '" . $x_flujos_neg_ventas . "'" : "NULL";
	$fieldList["`flujos_neg_ventas`"] = $theValue;

	// Field flujos_neg_proveedor_1
	$theValue = ($x_flujos_neg_proveedor_1 != "") ? " '" . $x_flujos_neg_proveedor_1 . "'" : "NULL";
	$fieldList["`flujos_neg_proveedor_1`"] = $theValue;

	// Field flujos_neg_proveedor_2
	$theValue = ($x_flujos_neg_proveedor_2 != "") ? " '" . $x_flujos_neg_proveedor_2 . "'" : "NULL";
	$fieldList["`flujos_neg_proveedor_2`"] = $theValue;

	// Field flujos_neg_proveedor_3
	$theValue = ($x_flujos_neg_proveedor_3 != "") ? " '" . $x_flujos_neg_proveedor_3 . "'" : "NULL";
	$fieldList["`flujos_neg_proveedor_3`"] = $theValue;

	// Field flujos_neg_proveedor_4
	$theValue = ($x_flujos_neg_proveedor_4 != "") ? " '" . $x_flujos_neg_proveedor_4 . "'" : "NULL";
	$fieldList["`flujos_neg_proveedor_4`"] = $theValue;

	// Field flujos_neg_gasto_1
	$theValue = ($x_flujos_neg_gasto_1 != "") ? " '" . $x_flujos_neg_gasto_1 . "'" : "NULL";
	$fieldList["`flujos_neg_gasto_1`"] = $theValue;

	// Field flujos_neg_gasto_2
	$theValue = ($x_flujos_neg_gasto_2 != "") ? " '" . $x_flujos_neg_gasto_2 . "'" : "NULL";
	$fieldList["`flujos_neg_gasto_2`"] = $theValue;

	// Field flujos_neg_gasto_3
	$theValue = ($x_flujos_neg_gasto_3 != "") ? " '" . $x_flujos_neg_gasto_3 . "'" : "NULL";
	$fieldList["`flujos_neg_gasto_3`"] = $theValue;

	// Field flujos_neg_cual_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_flujos_neg_cual_1) : $x_flujos_neg_cual_1; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_1`"] = $theValue;

	// Field flujos_neg_cual_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_flujos_neg_cual_2) : $x_flujos_neg_cual_2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_2`"] = $theValue;

	// Field flujos_neg_cual_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_flujos_neg_cual_3) : $x_flujos_neg_cual_3; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_3`"] = $theValue;

	// Field flujos_neg_cual_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_flujos_neg_cual_4) : $x_flujos_neg_cual_4; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_4`"] = $theValue;

	// Field flujos_neg_cual_5
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_flujos_neg_cual_5) : $x_flujos_neg_cual_5; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_5`"] = $theValue;

	// Field flujos_neg_cual_6
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_flujos_neg_cual_6) : $x_flujos_neg_cual_6; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_6`"] = $theValue;

	// Field flujos_neg_cual_7
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_flujos_neg_cual_7) : $x_flujos_neg_cual_7; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_7`"] = $theValue;

	// Field ingreso_negocio
	$theValue = ($x_ingreso_negocio != "") ? " '" . $x_ingreso_negocio . "'" : "NULL";
	$fieldList["`ingreso_negocio`"] = $theValue;

	// Field inv_neg_fija_conc_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_inv_neg_fija_conc_1) : $x_inv_neg_fija_conc_1; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_fija_conc_1`"] = $theValue;

	// Field inv_neg_fija_conc_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_inv_neg_fija_conc_2) : $x_inv_neg_fija_conc_2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_fija_conc_2`"] = $theValue;

	// Field inv_neg_fija_conc_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_inv_neg_fija_conc_3) : $x_inv_neg_fija_conc_3; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_fija_conc_3`"] = $theValue;


	// Field inv_neg_fija_conc_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_inv_neg_fija_conc_4) : $x_inv_neg_fija_conc_4; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_fija_conc_4`"] = $theValue;

	// Field inv_neg_fija_valor_1
	$theValue = ($x_inv_neg_fija_valor_1 != "") ? " '" . $x_inv_neg_fija_valor_1 . "'" : "NULL";
	$fieldList["`inv_neg_fija_valor_1`"] = $theValue;

	// Field inv_neg_fija_valor_2
	$theValue = ($x_inv_neg_fija_valor_2 != "") ? " '" . $x_inv_neg_fija_valor_2 . "'" : "NULL";
	$fieldList["`inv_neg_fija_valor_2`"] = $theValue;

	// Field inv_neg_fija_valor_3
	$theValue = ($x_inv_neg_fija_valor_3 != "") ? " '" . $x_inv_neg_fija_valor_3 . "'" : "NULL";
	$fieldList["`inv_neg_fija_valor_3`"] = $theValue;

	// Field inv_neg_fija_valor_4
	$theValue = ($x_inv_neg_fija_valor_4 != "") ? " '" . $x_inv_neg_fija_valor_4 . "'" : "NULL";
	$fieldList["`inv_neg_fija_valor_4`"] = $theValue;

	// Field inv_neg_total_fija
	$theValue = ($x_inv_neg_total_fija != "") ? " '" . $x_inv_neg_total_fija . "'" : "NULL";
	$fieldList["`inv_neg_total_fija`"] = $theValue;

	// Field inv_neg_var_conc_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_inv_neg_var_conc_1) : $x_inv_neg_var_conc_1; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_var_conc_1`"] = $theValue;

	// Field inv_neg_var_conc_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_inv_neg_var_conc_2) : $x_inv_neg_var_conc_2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_var_conc_2`"] = $theValue;

	// Field inv_neg_var_conc_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_inv_neg_var_conc_3) : $x_inv_neg_var_conc_3; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_var_conc_3`"] = $theValue;

	// Field inv_neg_var_conc_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_inv_neg_var_conc_4) : $x_inv_neg_var_conc_4; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_var_conc_4`"] = $theValue;

	// Field inv_neg_var_valor_1
	$theValue = ($x_inv_neg_var_valor_1 != "") ? " '" . $x_inv_neg_var_valor_1 . "'" : "NULL";
	$fieldList["`inv_neg_var_valor_1`"] = $theValue;

	// Field inv_neg_var_valor_2
	$theValue = ($x_inv_neg_var_valor_2 != "") ? " '" . $x_inv_neg_var_valor_2 . "'" : "NULL";
	$fieldList["`inv_neg_var_valor_2`"] = $theValue;

	// Field inv_neg_var_valor_3
	$theValue = ($x_inv_neg_var_valor_3 != "") ? " '" . $x_inv_neg_var_valor_3 . "'" : "NULL";
	$fieldList["`inv_neg_var_valor_3`"] = $theValue;

	// Field inv_neg_var_valor_4
	$theValue = ($x_inv_neg_var_valor_4 != "") ? " '" . $x_inv_neg_var_valor_4 . "'" : "NULL";
	$fieldList["`inv_neg_var_valor_4`"] = $theValue;

	// Field inv_neg_total_var
	$theValue = ($x_inv_neg_total_var != "") ? " '" . $x_inv_neg_total_var . "'" : "NULL";
	$fieldList["`inv_neg_total_var`"] = $theValue;

	// Field inv_neg_activos_totales
	$theValue = ($x_inv_neg_activos_totales != "") ? " '" . $x_inv_neg_activos_totales . "'" : "NULL";
	$fieldList["`inv_neg_activos_totales`"] = $theValue;

	// Field fecha
	$theValue = ($x_fecha_contratacion != "") ? " '" . ConvertDateToMysqlFormat($x_fecha_contratacion) . "'" : "NULL";
	$fieldList["`fecha`"] = $theValue;
	
	
	
	// insert into database
	$sSql = "INSERT INTO `adquisicionmaquinaria` (";
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
	
	
	phpmkr_query('commit;', $conn);	 
	
	//mensaje alta exitosa
	$_SESSION["mensajeAlta"] = "REGISTRO EXITOSO, YA PUEDE CERRAR ESTA VENTANA";
	
	
	
	
	
	
	
	
	
	}//functionb insertaCreditoMaquinaria
	
	
	
	
/************************************************/	
function insertaCreditoPYME(){
	
	
	// Get fields from form
	//basicos
	$x_solicitud_id = 0;
	$x_credito_tipo_id = @$_POST["x_credito_tipo_id"];
	$x_solicitud_status_id = 1;
	global $x_folio;
	$x_folio = "01ABC";
	$x_fecha_registro = @$_POST["x_fecha_registro"];
		
	$x_promotor_id = @$_POST["x_promotor_id"];
	$x_importe_solicitado = @$_POST["x_importe_solicitado"];
	$x_plazo_id = @$_POST["x_plazo_id"];
	$x_forma_pago_id = @$_POST["x_forma_pago_id"];	

	$x_actividad_id = $_POST["x_actividad_id"];
	$x_actividad_desc = $_POST["x_actividad_desc"];
	
	//SE VAN A LA TABLA CLIENTE
	$x_nombre_completo = @$_POST["x_nombre"]; 
	$x_apellido_paterno = @$_POST["x_apellido_paterno"];
	$x_apellido_materno = @$_POST["x_apellido_materno"];  	
	$x_tit_rfc = @$_POST["x_rfc"]; 
	$x_tit_curp = @$_POST["x_curp"]; 	
	$x_tit_fecha_nac = @$_POST["x_fecha_nacimiento"]; 
	//sexo verificar el valor de secxo
	$x_sexo = @$_POST["x_sexo"];// ---------------------------->
	$x_numero_hijos = @$_POST["x_integrantes_familia"];
	$x_numero_hijos_dep = @$_POST["x_dependientes"]; 
	$x_email = @$_POST["x_correo_electronico"]; 
	$x_nombre_conyuge = @$_POST["x_esposa"]; 
	
	
	//SE VAN A TABLA DOMICILIO 
	$x_calle = @$_POST["x_calle_domicilio"]; 
	$x_colonia = @$_POST["x_colonia_domicilio"]; 
	
	
	//DELEGACION NO ESTA REQUERIDA EN EL FORMATO
	 //ya pero necesito revisarlo no esta en formulario
	$x_entidad_domicilio = @$_POST["x_entidad_domicilio"]; 	//-------------------------------------->
	$x_codigo_postal = @$_POST["x_codigo_postal_domicilio"]; 
	$x_ubicacion = @$_POST["x_ubicacion_domicilio"]; 
	$x_vivienda_tipo_id = @$_POST["x_tipo_vivienda"]; // ya pero revisarlo es un lista
	$x_telefono = @$_POST["x_telefono_domicilio"]; 	
	$x_telefono_movil = @$_POST["x_celular"]; 	
	$x_telefono_secundario = @$_POST["x_otro_tel_domicilio_1"];
	$x_antiguedad = @$_POST["x_antiguedad"]; 
	$x_propietario = @$_POST["x_tel_arrendatario_domicilio"]; // ya pero en la base de datos hace rerencia la nombre del arrendatario
	
	
	
	$x_delegacion_id = @$_POST["x_delegacion_id"];
	$x_delegacion_id2 = @$_POST["x_delegacion_id2"];
	
	// SE GUARDARA EN DOMICILIO CON UN TIPO 2
	$x_calle2 = @$_POST["x_calle_negocio"]; 
	$x_colonia2 = @$_POST["x_colonia_negocio"]; 
	//$x_delegacion_id2 =@$_POST["x_delegacion_id2"];// no existe en el formulario es necessario agragarlo
	$x_entidad_negocio = @$_POST["x_entidad_negocio"];
	
	$x_ubicacion2 = @$_POST["x_ubicacion_negocio"]; 
	$x_codigo_postal2 = @$_POST["x_codigo_postal_negocio"];
	$x_vivienda_tipo_id2 = @$_POST["x_tipo_local_negocio"];
	$x_antiguedad2 = @$_POST["x_antiguedad_negocio"]; 
	$x_propietario2 = @$_POST["x_tel_arrendatario_negocio"]; //ya se pueso en un campo que es para nombre .....
	$x_telefono2 = @$_POST["x_tel_negocio"]; 
	
	
	
	//SE VA A LA TABLA GARANTIA
	 $x_garantia_desc = @$_POST["x_garantias"]; 
	 $x_garantia_valor = @$_POST["x_garantia_valor"];
	
	
	//se guarda en tabla GASTOS
	$x_renta_mensula_domicilio = @$_POST["x_renta_mensula_domicilio"];
	$x_renta_mensual = @$_POST["x_renta_mensual"];
	
	
	
	// estos no estan en otra tabla...debe de guarda en la principal credito PYME
	
	//GASTOS
	$x_renta_mensula_domicilio = @$_POST["x_renta_mensula_domicilio"];  // se guaradara en el gasto	
	$x_renta_mensula_domicilio2 = @$_POST["x_renta_mensual"]; //no existe en la tabla domicilio
//

	
	//REFERENCIAS
	$x_referencia_com_1 = @$_POST["x_referencia_com_1"]; //YA
	$x_referencia_com_2 = @$_POST["x_referencia_com_2"]; //YA
	$x_referencia_com_3 = @$_POST["x_referencia_com_3"]; //YA
	$x_referencia_com_4 = @$_POST["x_referencia_com_4"]; //YA
	$x_tel_referencia_1 = @$_POST["x_tel_referencia_1"]; //YA
	$x_tel_referencia_2 = @$_POST["x_tel_referencia_2"]; //YA
	$x_tel_referencia_3 = @$_POST["x_tel_referencia_3"]; //YA
	$x_tel_referencia_4 = @$_POST["x_tel_referencia_4"]; //YA
	$x_parentesco_ref_1 = @$_POST["x_parentesco_ref_1"]; //ya
	$x_parentesco_ref_2 = @$_POST["x_parentesco_ref_2"]; //YA
	$x_parentesco_ref_3 = @$_POST["x_parentesco_ref_3"]; //YA
	$x_parentesco_ref_4 = @$_POST["x_parentesco_ref_4"]; //YA
	
	//SE GUARADA EN PYME	
	$x_giro_negocio = @$_POST["x_giro_negocio"];
	$x_propiedad_hipot = @$_POST["x_propiedad_hipot"];// este campo no esta en la tabla de domicilio.
	$x_telefono_sec = @$_POST["x_otro_telefono_domicilio_2"]; //ya este campo se guaradara en el formato de pyme  
	$x_empresa = @$_POST["x_giro_negocio"]; //se guaradara en el formato de pyme	
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
	$x_antiguedad_ubicacion = @$_POST["x_antiguedad_ubicacion"];
	
	//SE GAURADA EN PYME
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
	$x_credito_tipo_id = @$_POST["x_tipo_credito"];
	$x_solicitud_status_id = 1;
	$x_folio = "01ABC";
	$x_fecha_contratacion = @$_POST["x_fecha"];
	
	//conectar con la base
	$conn = phpmkr_db_connect(HOST, USER, PASS,DB);
	//ARRAY CON LOS VALORES DE LOS CAMPOS
	
	//SOLICITUD

	// Field credito_tipo_id
	$theValue = ($x_credito_tipo_id != "") ? intval($x_credito_tipo_id) : "NULL";
	$fieldList["`credito_tipo_id`"] = $theValue;

	// Field solicitud_status_id
	$theValue = ($x_solicitud_status_id != "") ? intval($x_solicitud_status_id) : "NULL";
	$fieldList["`solicitud_status_id`"] = $theValue;

	// Field folio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_folio) : $x_folio; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`folio`"] = $theValue;

	// Field fecha_registro......checar la fecha en el formulario
	$theValue = ($x_fecha_registro != "") ? " '" . ConvertDateToMysqlFormat($x_fecha_registro) . "'" : "Null";
	$fieldList["`fecha_registro`"] = $theValue;

	// Field promotor_id
	$theValue = ($x_promotor_id != "") ? intval($x_promotor_id) : "NULL";
	$fieldList["`promotor_id`"] = $theValue;

	// Field importe_solicitado
	$theValue = ($x_importe_solicitado != "") ? " '" . doubleval($x_importe_solicitado) . "'" : "Null";
	$fieldList["`importe_solicitado`"] = $theValue;

	// Field plazo
	$theValue = ($x_plazo_id != "") ? intval($x_plazo_id) : "NULL";
	$fieldList["`plazo_id`"] = $theValue;

	$theValue = ($x_forma_pago_id != "") ? intval($x_forma_pago_id) : "NULL";
	$fieldList["`forma_pago_id`"] = $theValue;

	// Field contrato
	$theValue = $x_contrato[0];
	$theValue = ($theValue != "") ? intval($theValue) : "NULL";
	$fieldList["`contrato`"] = $theValue;

	// Field pagare
	$theValue = $x_pagare[0];
	$theValue = ($theValue != "") ? intval($theValue) : "NULL";
	$fieldList["`pagare`"] = $theValue;


	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_comentario_promotor) : $x_comentario_promotor; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`comentario_promotor`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_comentario_comite) : $x_comentario_comite; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`comentario_comite`"] = $theValue;


	$theValue = ($x_actividad_id != "") ? intval($x_actividad_id) : "0";
	$fieldList["`actividad_id`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_actividad_desc) : $x_actividad_desc; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`actividad_desc`"] = $theValue;

		// Field fromato_nuevo
	$theValue = 1;
	$theValue = ($theValue != "") ? intval($theValue) : "NULL";
	$fieldList["`formato_nuevo`"] = $theValue;
	
	// insert into database
	$sSql = "INSERT INTO `solicitud` (";
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
	
	//solicitud agregada con un numero de filio asignado
	
	
	//CLIENTE

	$fieldList = NULL;
	// Field solicitud_id
//	$fieldList["`solicitud_id`"] = $x_solicitud_id;

	// Field usuario_id
//	$theValue = ($x_usuario_id != "") ? intval($x_usuario_id) : "NULL";
	$fieldList["`usuario_id`"] = 0;

	// Field nombre_completo
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_nombre_completo) : $x_nombre_completo; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre_completo`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_apellido_paterno) : $x_apellido_paterno; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`apellido_paterno`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_apellido_materno) : $x_apellido_materno; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`apellido_materno`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_tit_rfc) : $x_tit_rfc; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`rfc`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_tit_curp) : $x_tit_curp; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`curp`"] = $theValue;


	// Field tipo_negocio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_tipo_negocio) : $x_tipo_negocio; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`tipo_negocio`"] = $theValue;


	// Field nacimiento
	$theValue = ($x_tit_fecha_nac != "") ? " '" . ConvertDateToMysqlFormat($x_tit_fecha_nac) . "'" : "Null";
	$fieldList["`fecha_nac`"] = $theValue;




	// Field edad
	$theValue = ($x_edad != "") ? intval($x_edad) : "NULL";
	$fieldList["`edad`"] = $theValue;

	// Field sexo
	$theValue = ($x_sexo != "") ? intval($x_sexo) : "NULL";
	$fieldList["`sexo`"] = $theValue;

	// Field estado_civil_id
	$theValue = ($x_estado_civil_id != "") ? intval($x_estado_civil_id) : "0";
	$fieldList["`estado_civil_id`"] = $theValue;

	// Field numero_hijos
	$theValue = ($x_numero_hijos != "") ? intval($x_numero_hijos) : "0";
	$fieldList["`numero_hijos`"] = $theValue;


	// Field numero_hijos_dep
	$theValue = ($x_numero_hijos_dep != "") ? intval($x_numero_hijos_dep) : "0";
	$fieldList["`numero_hijos_dep`"] = $theValue;

	// Field nombre_conyuge
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_nombre_conyuge) : $x_nombre_conyuge; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre_conyuge`"] = $theValue;

	// Field email
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_email) : $x_email; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`email`"] = $theValue;


	$theValue = ($x_nacionalidad_id != "") ? intval($x_nacionalidad_id) : "0";
	$fieldList["`nacionalidad_id`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_empresa) : $x_empresa; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`empresa`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_piesto) : $x_puesto; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`puesto`"] = $theValue;

	// Field fecha_registro
	$theValue = ($x_fecha_contratacion != "") ? " '" . ConvertDateToMysqlFormat($x_fecha_contratacion) . "'" : "Null";
	$fieldList["`fecha_contratacion`"] = $theValue;

	$theValue = ($x_salario_mensual != "") ? " '" . doubleval($x_salario_mensual) . "'" : "Null";
	$fieldList["`salario_mensual`"] = $theValue;


	// Field promotor_id
	$theValue = ($x_promotor_id != "") ? intval($x_promotor_id) : "NULL";
	$fieldList["`promotor_id`"] = $theValue;


	// insert into database
	$sSql = "INSERT INTO `cliente` (";
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
	$x_cliente_id = mysql_insert_id();

	
	//SOLICITUD CLIENTE

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

	
	//DIRECCION PART

	$fieldList = NULL;
	// Field cliente_id
//	$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";
	$fieldList["`cliente_id`"] = $x_cliente_id;

	// Field aval_id
//	$theValue = ($x_aval_id != "") ? intval($x_aval_id) : "NULL";
	$fieldList["`aval_id`"] = 0;

	// Field promotor_id
//	$theValue = ($x_promotor_id != "") ? intval($x_promotor_id) : "NULL";
	$fieldList["`promotor_id`"] = 0;

	// Field direccion_tipo_id
//	$theValue = ($x_direccion_tipo_id != "") ? intval($x_direccion_tipo_id) : "NULL";
	$fieldList["`direccion_tipo_id`"] = 1;

	// Field calle
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_calle) : $x_calle; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`calle`"] = $theValue;

	// Field colonia
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_colonia) : $x_colonia; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`colonia`"] = $theValue;

	// Field delegacion_id
	$theValue = ($x_delegacion_id != "") ? intval($x_delegacion_id) : "0";
	$fieldList["`delegacion_id`"] = $theValue;

	// Field propietario
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_propietario) : $x_propietario; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`propietario`"] = $theValue;

/*
	// Field entidad
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_entidad) : $x_entidad; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`entidad`"] = $theValue;
*/
	// Field codigo_postal
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_codigo_postal) : $x_codigo_postal; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`codigo_postal`"] = $theValue;

	// Field ubicacion
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_ubicacion) : $x_ubicacion; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ubicacion`"] = $theValue;

	// Field antiguedad
	$theValue = ($x_antiguedad != "") ? intval($x_antiguedad) : "0";
	$fieldList["`antiguedad`"] = $theValue;

	// Field vivienda_tipo_id
	$theValue = ($x_vivienda_tipo_id != "") ? intval($x_vivienda_tipo_id) : "0";
	$fieldList["`vivienda_tipo_id`"] = $theValue;

	// Field otro_tipo_vivienda
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_otro_tipo_vivienda) : $x_otro_tipo_vivienda; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`otro_tipo_vivienda`"] = $theValue;

	// Field telefono
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_telefono) : $x_telefono; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono`"] = $theValue;
	
	// Field ENTIDAD
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_entidad_domicilio) : $x_entidad_domicilio; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`entidad`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_telefono_movil) : $x_telefono_movil; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono_movil`"] = $theValue;
	

	// Field telefono_secundario
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_telefono_secundario) : $x_telefono_secundario; 
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
		echo phpmkr_error() . '<br>SQL AQUI: ' . $sSql;
		phpmkr_query('rollback;', $conn);	 
	 	exit();
	}


//DIRECCION NEG

	$fieldList = NULL;
	// Field cliente_id
//	$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";
	$fieldList["`cliente_id`"] = $x_cliente_id;

	// Field aval_id
//	$theValue = ($x_aval_id != "") ? intval($x_aval_id) : "NULL";
	$fieldList["`aval_id`"] = 0;

	// Field promotor_id
//	$theValue = ($x_promotor_id != "") ? intval($x_promotor_id) : "NULL";
	$fieldList["`promotor_id`"] = 0;

	// Field direccion_tipo_id
//	$theValue = ($x_direccion_tipo_id != "") ? intval($x_direccion_tipo_id) : "NULL";
	$fieldList["`direccion_tipo_id`"] = 2;

	// Field calle
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_calle2) : $x_calle2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`calle`"] = $theValue;

	// Field colonia
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_colonia2) : $x_colonia2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`colonia`"] = $theValue;

	// Field delegacion_id
	$theValue = ($x_delegacion_id2 != "") ? intval($x_delegacion_id2) : "0";
	$fieldList["`delegacion_id`"] = $theValue;

	// Field codigo_postal
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_codigo_postal2) : $x_codigo_postal2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`codigo_postal`"] = $theValue;

	// Field ubicacion
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_ubicacion2) : $x_ubicacion2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ubicacion`"] = $theValue;

	// Field antiguedad
	$theValue = ($x_antiguedad != "") ? intval($x_antiguedad2) : "0";
	$fieldList["`antiguedad`"] = $theValue;

	// Field vivienda_tipo_id
	$theValue = ($x_vivienda_tipo_id2 != "") ? intval($x_vivienda_tipo_id2) : "0";
	$fieldList["`vivienda_tipo_id`"] = $theValue;

	// Field otro_tipo_vivienda
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_otro_tipo_vivienda2) : $x_otro_tipo_vivienda2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`otro_tipo_vivienda`"] = $theValue;
	
	// Field ENTIDAD
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_entidad_negocio) : $x_entidad_negocio; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`entidad`"] = $theValue; 

	// Field telefono
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_telefono2) : $x_telefono2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono`"] = $theValue;

	// Field telefono_secundario
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_telefono_secundario2) : $x_telefono_secundario2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono_secundario`"] = $theValue;
	
	// Field propietario
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_propietario2) : $x_propietario2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`propietario`"] = $theValue;
	
	
	


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

	
	//GARANTIAS

	if($x_garantia_desc != ""){
		$fieldList = NULL;
		// Field cliente_id
	//	$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";
		$fieldList["`solicitud_id`"] = $x_solicitud_id;
	
		// Field descripcion
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_garantia_desc) : $x_garantia_desc; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`descripcion`"] = $theValue;
	
		// Field valor
		$theValue = ($x_garantia_valor != "") ? " '" . doubleval($x_garantia_valor) . "'" : "Null";
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
	
	
	
		//GASTOS

	$fieldList = NULL;
	// Field cliente_id

	$fieldList["`solicitud_id`"] = $x_solicitud_id;

	$theValue = ($x_renta_mensual != "") ? " '" . doubleval($x_renta_mensual) . "'" : "Null";
	$fieldList["`gastos_renta_negocio`"] = $theValue;


	$theValue = ($x_renta_mensula_domicilio != "") ? doubleval($x_renta_mensula_domicilio) : "0";
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
	
	
//REFERENCIAS CICLO


	$x_counter = 1;
	while($x_counter < 5){

		$fieldList = NULL;
		// Field cliente_id
//		$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";
		$fieldList["`solicitud_id`"] = $x_solicitud_id;

		$x_aux = "x_referencia_com_$x_counter";
		$x_aux = $$x_aux;
		if($x_aux != ""){
		
			// Field nombre_completo
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_aux) : $x_aux; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`nombre_completo`"] = $theValue;
		
			// Field telefono
			$aux_tel = "x_tel_referencia_$x_counter";
			$uax_tel = $$aux_tel;
			
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($uax_tel) : $uax_tel; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`telefono`"] = $theValue;
			
			$aux_parent = "x_parentesco_ref_$x_counter";
			$aux_parent = $$aux_parent;
			
			// Field parentesco_tipo_id
			$theValue = ($aux_parent != "") ? intval($aux_parent) : "NULL";
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
	





	
	//REFERENCIAS COMERCIALES...GASTOS E INGRESOS....SE INSERTA EN LA TABLA ORIGINAL PYME
	
	
	// Field referencia_com_1
	$fieldList = NULL;
	
	//	$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";
	$fieldList["`cliente_id`"] = $x_cliente_id;
	
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_telefono_sec) : $x_telefono_sec; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`otro_telefono_domicilio_2`"] = $theValue;
/*
	// Field referencia_com_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_referencia_com_2) : $x_referencia_com_2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`referencia_com_2`"] = $theValue;
	
	// Field parentesco_ref_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_giro_negocio) : $x_parentesco_ref_3; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`parentesco_ref_3`"] = $theValue; */
	//FIEL GRIO NEGOCIO
	$theValue = (!get_magic_quotes_gpc($x_giro_negocio)) ? addslashes($x_giro_negocio) : $x_giro_negocio; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`giro_negocio`"] = $theValue;



	// Field prp_hipotec
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_propiedad_hipot) : $x_propiedad_hipot; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`prop_hipotec`"] = $theValue;
	
	
	// Field antiguedad ubicacion
	$theValue = ($x_antiguedad_ubicacion != "") ? intval($x_antiguedad_ubicacion) : "0";
	$fieldList["`antiguedad_ubicacion`"] = $theValue;
	
	
	// Field ing_fam_negocio
	$theValue = ($x_ing_fam_negocio != "") ? " '" . $x_ing_fam_negocio . "'" : "NULL";
	$fieldList["`ing_fam_negocio`"] = $theValue;

	// Field ing_fam_otro_th
	$theValue = ($x_ing_fam_otro_th != "") ? " '" . $x_ing_fam_otro_th . "'" : "NULL";
	$fieldList["`ing_fam_otro_th`"] = $theValue;

	// Field ing_fam_1
	$theValue = ($x_ing_fam_1 != "") ? " '" . $x_ing_fam_1 . "'" : "NULL";
	$fieldList["`ing_fam_1`"] = $theValue;

	// Field ing_fam_2
	$theValue = ($x_ing_fam_2 != "") ? " '" . $x_ing_fam_2 . "'" : "NULL";
	$fieldList["`ing_fam_2`"] = $theValue;

	// Field ing_fam_deuda_1
	$theValue = ($x_ing_fam_deuda_1 != "") ? " '" . $x_ing_fam_deuda_1 . "'" : "NULL";
	$fieldList["`ing_fam_deuda_1`"] = $theValue;

	// Field ing_fam_deuda_2
	$theValue = ($x_ing_fam_deuda_2 != "") ? " '" . $x_ing_fam_deuda_2 . "'" : "NULL";
	$fieldList["`ing_fam_deuda_2`"] = $theValue;

	// Field ing_fam_total
	$theValue = ($x_ing_fam_total != "") ? " '" . $x_ing_fam_total . "'" : "NULL";
	$fieldList["`ing_fam_total`"] = $theValue;

	// Field ing_fam_cuales_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_ing_fam_cuales_1) : $x_ing_fam_cuales_1; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ing_fam_cuales_1`"] = $theValue;

	// Field ing_fam_cuales_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_ing_fam_cuales_2) : $x_ing_fam_cuales_2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ing_fam_cuales_2`"] = $theValue;

	// Field ing_fam_cuales_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_ing_fam_cuales_3) : $x_ing_fam_cuales_3; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ing_fam_cuales_3`"] = $theValue;

	// Field ing_fam_cuales_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_ing_fam_cuales_4) : $x_ing_fam_cuales_4; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ing_fam_cuales_4`"] = $theValue;

	// Field ing_fam_cuales_5
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_ing_fam_cuales_5) : $x_ing_fam_cuales_5; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ing_fam_cuales_5`"] = $theValue;

	// Field flujos_neg_ventas
	$theValue = ($x_flujos_neg_ventas != "") ? " '" . $x_flujos_neg_ventas . "'" : "NULL";
	$fieldList["`flujos_neg_ventas`"] = $theValue;

	// Field flujos_neg_proveedor_1
	$theValue = ($x_flujos_neg_proveedor_1 != "") ? " '" . $x_flujos_neg_proveedor_1 . "'" : "NULL";
	$fieldList["`flujos_neg_proveedor_1`"] = $theValue;

	// Field flujos_neg_proveedor_2
	$theValue = ($x_flujos_neg_proveedor_2 != "") ? " '" . $x_flujos_neg_proveedor_2 . "'" : "NULL";
	$fieldList["`flujos_neg_proveedor_2`"] = $theValue;

	// Field flujos_neg_proveedor_3
	$theValue = ($x_flujos_neg_proveedor_3 != "") ? " '" . $x_flujos_neg_proveedor_3 . "'" : "NULL";
	$fieldList["`flujos_neg_proveedor_3`"] = $theValue;

	// Field flujos_neg_proveedor_4
	$theValue = ($x_flujos_neg_proveedor_4 != "") ? " '" . $x_flujos_neg_proveedor_4 . "'" : "NULL";
	$fieldList["`flujos_neg_proveedor_4`"] = $theValue;

	// Field flujos_neg_gasto_1
	$theValue = ($x_flujos_neg_gasto_1 != "") ? " '" . $x_flujos_neg_gasto_1 . "'" : "NULL";
	$fieldList["`flujos_neg_gasto_1`"] = $theValue;

	// Field flujos_neg_gasto_2
	$theValue = ($x_flujos_neg_gasto_2 != "") ? " '" . $x_flujos_neg_gasto_2 . "'" : "NULL";
	$fieldList["`flujos_neg_gasto_2`"] = $theValue;

	// Field flujos_neg_gasto_3
	$theValue = ($x_flujos_neg_gasto_3 != "") ? " '" . $x_flujos_neg_gasto_3 . "'" : "NULL";
	$fieldList["`flujos_neg_gasto_3`"] = $theValue;

	// Field flujos_neg_cual_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_flujos_neg_cual_1) : $x_flujos_neg_cual_1; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_1`"] = $theValue;

	// Field flujos_neg_cual_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_flujos_neg_cual_2) : $x_flujos_neg_cual_2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_2`"] = $theValue;

	// Field flujos_neg_cual_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_flujos_neg_cual_3) : $x_flujos_neg_cual_3; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_3`"] = $theValue;

	// Field flujos_neg_cual_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_flujos_neg_cual_4) : $x_flujos_neg_cual_4; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_4`"] = $theValue;

	// Field flujos_neg_cual_5
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_flujos_neg_cual_5) : $x_flujos_neg_cual_5; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_5`"] = $theValue;

	// Field flujos_neg_cual_6
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_flujos_neg_cual_6) : $x_flujos_neg_cual_6; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_6`"] = $theValue;

	// Field flujos_neg_cual_7
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_flujos_neg_cual_7) : $x_flujos_neg_cual_7; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_7`"] = $theValue;

	// Field ingreso_negocio
	$theValue = ($x_ingreso_negocio != "") ? " '" . $x_ingreso_negocio . "'" : "NULL";
	$fieldList["`ingreso_negocio`"] = $theValue;

	// Field inv_neg_fija_conc_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_inv_neg_fija_conc_1) : $x_inv_neg_fija_conc_1; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_fija_conc_1`"] = $theValue;

	// Field inv_neg_fija_conc_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_inv_neg_fija_conc_2) : $x_inv_neg_fija_conc_2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_fija_conc_2`"] = $theValue;

	// Field inv_neg_fija_conc_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_inv_neg_fija_conc_3) : $x_inv_neg_fija_conc_3; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_fija_conc_3`"] = $theValue;


	// Field inv_neg_fija_conc_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_inv_neg_fija_conc_4) : $x_inv_neg_fija_conc_4; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_fija_conc_4`"] = $theValue;

	// Field inv_neg_fija_valor_1
	$theValue = ($x_inv_neg_fija_valor_1 != "") ? " '" . $x_inv_neg_fija_valor_1 . "'" : "NULL";
	$fieldList["`inv_neg_fija_valor_1`"] = $theValue;

	// Field inv_neg_fija_valor_2
	$theValue = ($x_inv_neg_fija_valor_2 != "") ? " '" . $x_inv_neg_fija_valor_2 . "'" : "NULL";
	$fieldList["`inv_neg_fija_valor_2`"] = $theValue;

	// Field inv_neg_fija_valor_3
	$theValue = ($x_inv_neg_fija_valor_3 != "") ? " '" . $x_inv_neg_fija_valor_3 . "'" : "NULL";
	$fieldList["`inv_neg_fija_valor_3`"] = $theValue;

	// Field inv_neg_fija_valor_4
	$theValue = ($x_inv_neg_fija_valor_4 != "") ? " '" . $x_inv_neg_fija_valor_4 . "'" : "NULL";
	$fieldList["`inv_neg_fija_valor_4`"] = $theValue;

	// Field inv_neg_total_fija
	$theValue = ($x_inv_neg_total_fija != "") ? " '" . $x_inv_neg_total_fija . "'" : "NULL";
	$fieldList["`inv_neg_total_fija`"] = $theValue;

	// Field inv_neg_var_conc_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_inv_neg_var_conc_1) : $x_inv_neg_var_conc_1; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_var_conc_1`"] = $theValue;

	// Field inv_neg_var_conc_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_inv_neg_var_conc_2) : $x_inv_neg_var_conc_2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_var_conc_2`"] = $theValue;

	// Field inv_neg_var_conc_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_inv_neg_var_conc_3) : $x_inv_neg_var_conc_3; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_var_conc_3`"] = $theValue;

	// Field inv_neg_var_conc_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_inv_neg_var_conc_4) : $x_inv_neg_var_conc_4; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_var_conc_4`"] = $theValue;

	// Field inv_neg_var_valor_1
	$theValue = ($x_inv_neg_var_valor_1 != "") ? " '" . $x_inv_neg_var_valor_1 . "'" : "NULL";
	$fieldList["`inv_neg_var_valor_1`"] = $theValue;

	// Field inv_neg_var_valor_2
	$theValue = ($x_inv_neg_var_valor_2 != "") ? " '" . $x_inv_neg_var_valor_2 . "'" : "NULL";
	$fieldList["`inv_neg_var_valor_2`"] = $theValue;

	// Field inv_neg_var_valor_3
	$theValue = ($x_inv_neg_var_valor_3 != "") ? " '" . $x_inv_neg_var_valor_3 . "'" : "NULL";
	$fieldList["`inv_neg_var_valor_3`"] = $theValue;

	// Field inv_neg_var_valor_4
	$theValue = ($x_inv_neg_var_valor_4 != "") ? " '" . $x_inv_neg_var_valor_4 . "'" : "NULL";
	$fieldList["`inv_neg_var_valor_4`"] = $theValue;

	// Field inv_neg_total_var
	$theValue = ($x_inv_neg_total_var != "") ? " '" . $x_inv_neg_total_var . "'" : "NULL";
	$fieldList["`inv_neg_total_var`"] = $theValue;

	// Field inv_neg_activos_totales
	$theValue = ($x_inv_neg_activos_totales != "") ? " '" . $x_inv_neg_activos_totales . "'" : "NULL";
	$fieldList["`inv_neg_activos_totales`"] = $theValue;

	// Field fecha
	$theValue = ($x_fecha_contratacion != "") ? " '" . ConvertDateToMysqlFormat($x_fecha_contratacion) . "'" : "NULL";
	$fieldList["`fecha`"] = $theValue;
	
	
	
	
	
	
	
	
	
	
	// insert into database
	$sSql = "INSERT INTO `formatopyme` (";
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
	
	
	phpmkr_query('commit;', $conn);	 
	
	//mensaje alta exitosa
	$_SESSION["mensajeAlta"] = "REGISTRO EXITOSO, YA PUEDE CERRAR ESTA VENTANA";
	
	
}//TERMINA LA FUNCION  GRAL INSERTA EN PYME
	
	
	
	
	///////////////////////////////////////////////////////////////
	//															 //	
	//FORMATO INDIVIDUAL PERSONAL                                //		
	//															 //
	///////////////////////////////////////////////////////////////
	
	
function insertaCreditoIndividualPersonal(){
	
	
	// Get fields from form
	//basicos
	$x_solicitud_id = 0;
	$x_credito_tipo_id = @$_POST["x_credito_tipo_id"];
	$x_solicitud_status_id = 1;
	global $x_folio;
	$x_folio = "01ABC";
	$x_fecha_registro = @$_POST["x_fecha_registro"];
		
	$x_promotor_id = @$_POST["x_promotor_id"];
	$x_importe_solicitado = @$_POST["x_importe_solicitado"];
	$x_plazo_id = @$_POST["x_plazo_id"];
	$x_forma_pago_id = @$_POST["x_forma_pago_id"];	

	$x_actividad_id = $_POST["x_actividad_id"];
	$x_actividad_desc = $_POST["x_actividad_desc"];
	
	//SE VAN A LA TABLA CLIENTE
	$x_nombre_completo = @$_POST["x_nombre"]; 
	$x_apellido_paterno = @$_POST["x_apellido_paterno"];
	$x_apellido_materno = @$_POST["x_apellido_materno"];  	
	$x_tit_rfc = @$_POST["x_rfc"]; 
	$x_tit_curp = @$_POST["x_curp"]; 	
	$x_tit_fecha_nac = @$_POST["x_fecha_nacimiento"]; 
	//sexo verificar el valor de secxo
	$x_sexo = @$_POST["x_sexo"];// ---------------------------->
	$x_numero_hijos = @$_POST["x_integrantes_familia"];
	$x_numero_hijos_dep = @$_POST["x_dependientes"]; 
	$x_email = @$_POST["x_correo_electronico"]; 
	$x_nombre_conyuge = @$_POST["x_esposa"]; 
	
	
	//SE VAN A TABLA DOMICILIO 
	$x_calle = @$_POST["x_calle_domicilio"]; 
	$x_colonia = @$_POST["x_colonia_domicilio"]; 
	
	
	//DELEGACION NO ESTA REQUERIDA EN EL FORMATO
	// $x_delegacion_id = @$_POST["delegacion_domicilio"]; //ya pero necesito revisarlo no esta en formulario
	$x_entidad_domicilio = @$_POST["x_entidad_domicilio"]; 	//-------------------------------------->
	$x_codigo_postal = @$_POST["x_codigo_postal_domicilio"]; 
	$x_ubicacion = @$_POST["x_ubicacion_domicilio"]; 
	$x_vivienda_tipo_id = @$_POST["x_tipo_vivienda"]; // ya pero revisarlo es un lista
	$x_telefono = @$_POST["x_telefono_domicilio"]; 	
	$x_telefono_movil = @$_POST["x_celular"]; 	
	$x_telefono_secundario = @$_POST["x_otro_tel_domicilio_1"];
	$x_antiguedad = @$_POST["x_antiguedad"]; 
	$x_propietario = @$_POST["x_tel_arrendatario_domicilio"]; // ya pero en la base de datos hace rerencia la nombre del arrendatario
	
	
	$x_delegacion_id = @$_POST["x_delegacion_id"];
	$x_delegacion_id2 = @$_POST["x_delegacion_id2"];
	
	
	// SE GUARDARA EN DOMICILIO CON UN TIPO 2
	$x_calle2 = @$_POST["x_calle_negocio"]; 
	$x_colonia2 = @$_POST["x_colonia_negocio"]; 
	
	$x_entidad_negocio = @$_POST["x_entidad_negocio"];
	
	$x_ubicacion2 = @$_POST["x_ubicacion_negocio"]; 
	$x_codigo_postal2 = @$_POST["x_codigo_postal_negocio"];
	$x_vivienda_tipo_id2 = @$_POST["x_tipo_local_negocio"];
	$x_antiguedad2 = @$_POST["x_antiguedad_negocio"]; 
	$x_propietario2 = @$_POST["x_tel_arrendatario_negocio"]; //ya se pueso en un campo que es para nombre .....
	$x_telefono2 = @$_POST["x_tel_negocio"]; 
	
	
	
	//SE VA A LA TABLA GARANTIA
	 $x_garantia_desc = @$_POST["x_garantias"]; 
	 $x_garantia_valor = @$_POST["x_garantia_valor"];
	
	
	//se guarda en tabla GASTOS
	$x_renta_mensula_domicilio = @$_POST["x_renta_mensula_domicilio"];
	$x_renta_mensual = @$_POST["x_renta_mensual"];
	
	
	
	// estos no estan en otra tabla...debe de guarda en la principal credito PYME
	
	//GASTOS
	$x_renta_mensula_domicilio = @$_POST["x_renta_mensula_domicilio"];  // se guaradara en el gasto	
	$x_renta_mensula_domicilio2 = @$_POST["x_renta_mensual"]; //no existe en la tabla domicilio
//

	
	//REFERENCIAS
	$x_referencia_com_1 = @$_POST["x_referencia_com_1"]; //YA
	$x_referencia_com_2 = @$_POST["x_referencia_com_2"]; //YA
	$x_referencia_com_3 = @$_POST["x_referencia_com_3"]; //YA
	$x_referencia_com_4 = @$_POST["x_referencia_com_4"]; //YA
	$x_tel_referencia_1 = @$_POST["x_tel_referencia_1"]; //YA
	$x_tel_referencia_2 = @$_POST["x_tel_referencia_2"]; //YA
	$x_tel_referencia_3 = @$_POST["x_tel_referencia_3"]; //YA
	$x_tel_referencia_4 = @$_POST["x_tel_referencia_4"]; //YA
	$x_parentesco_ref_1 = @$_POST["x_parentesco_ref_1"]; //ya
	$x_parentesco_ref_2 = @$_POST["x_parentesco_ref_2"]; //YA
	$x_parentesco_ref_3 = @$_POST["x_parentesco_ref_3"]; //YA
	$x_parentesco_ref_4 = @$_POST["x_parentesco_ref_4"]; //YA
	
	//SE GUARADA EN formatoIndividual	
	$x_propiedad_hipot = @$_POST["x_propiedad_hipot"];// este campo no esta en la tabla de domicilio.
	$x_telefono_sec = @$_POST["x_otro_telefono_domicilio_2"]; //ya este campo se guaradara en el formato de pyme  
	$x_empresa = @$_POST["x_giro_negocio"]; //se guaradara en el formato de pyme	
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
	
	
	//SE GAURADA EN formatoIndividual
	$x_giro_negocio = $_POST["x_giro_negocio"];
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
	$x_credito_tipo_id = @$_POST["x_tipo_credito"];
	$x_solicitud_status_id = 1;
	$x_folio = "01ABC";
	$x_fecha_contratacion = @$_POST["x_fecha"];
	
	//conectar con la base
	$conn = phpmkr_db_connect(HOST, USER, PASS,DB);
	//ARRAY CON LOS VALORES DE LOS CAMPOS
	
	//SOLICITUD

	// Field credito_tipo_id
	$theValue = ($x_credito_tipo_id != "") ? intval($x_credito_tipo_id) : "NULL";
	$fieldList["`credito_tipo_id`"] = $theValue;

	// Field solicitud_status_id
	$theValue = ($x_solicitud_status_id != "") ? intval($x_solicitud_status_id) : "NULL";
	$fieldList["`solicitud_status_id`"] = $theValue;

	// Field folio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_folio) : $x_folio; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`folio`"] = $theValue;

	// Field fecha_registro......checar la fecha en el formulario
	$theValue = ($x_fecha_registro != "") ? " '" . ConvertDateToMysqlFormat($x_fecha_registro) . "'" : "Null";
	$fieldList["`fecha_registro`"] = $theValue;

	// Field promotor_id
	$theValue = ($x_promotor_id != "") ? intval($x_promotor_id) : "NULL";
	$fieldList["`promotor_id`"] = $theValue;

	// Field importe_solicitado
	$theValue = ($x_importe_solicitado != "") ? " '" . doubleval($x_importe_solicitado) . "'" : "Null";
	$fieldList["`importe_solicitado`"] = $theValue;

	// Field plazo
	$theValue = ($x_plazo_id != "") ? intval($x_plazo_id) : "NULL";
	$fieldList["`plazo_id`"] = $theValue;

	$theValue = ($x_forma_pago_id != "") ? intval($x_forma_pago_id) : "NULL";
	$fieldList["`forma_pago_id`"] = $theValue;

	// Field contrato
	$theValue = $x_contrato[0];
	$theValue = ($theValue != "") ? intval($theValue) : "NULL";
	$fieldList["`contrato`"] = $theValue;

	// Field pagare
	$theValue = $x_pagare[0];
	$theValue = ($theValue != "") ? intval($theValue) : "NULL";
	$fieldList["`pagare`"] = $theValue;


	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_comentario_promotor) : $x_comentario_promotor; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`comentario_promotor`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_comentario_comite) : $x_comentario_comite; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`comentario_comite`"] = $theValue;


	$theValue = ($x_actividad_id != "") ? intval($x_actividad_id) : "0";
	$fieldList["`actividad_id`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_actividad_desc) : $x_actividad_desc; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`actividad_desc`"] = $theValue;


		// Field fromato_nuevo
	$theValue = 1;
	$theValue = ($theValue != "") ? intval($theValue) : "NULL";
	$fieldList["`formato_nuevo`"] = $theValue;
	
	// insert into database
	$sSql = "INSERT INTO `solicitud` (";
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
	
	//solicitud agregada con un numero de filio asignado
	
	
	//CLIENTE

	$fieldList = NULL;
	// Field solicitud_id
//	$fieldList["`solicitud_id`"] = $x_solicitud_id;

	// Field usuario_id
//	$theValue = ($x_usuario_id != "") ? intval($x_usuario_id) : "NULL";
	$fieldList["`usuario_id`"] = 0;

	// Field nombre_completo
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_nombre_completo) : $x_nombre_completo; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre_completo`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_apellido_paterno) : $x_apellido_paterno; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`apellido_paterno`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_apellido_materno) : $x_apellido_materno; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`apellido_materno`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_tit_rfc) : $x_tit_rfc; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`rfc`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_tit_curp) : $x_tit_curp; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`curp`"] = $theValue;


	// Field tipo_negocio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_tipo_negocio) : $x_tipo_negocio; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`tipo_negocio`"] = $theValue;


	// Field nacimiento
	$theValue = ($x_tit_fecha_nac != "") ? " '" . ConvertDateToMysqlFormat($x_tit_fecha_nac) . "'" : "Null";
	$fieldList["`fecha_nac`"] = $theValue;




	// Field edad
	$theValue = ($x_edad != "") ? intval($x_edad) : "NULL";
	$fieldList["`edad`"] = $theValue;

	// Field sexo
	$theValue = ($x_sexo != "") ? intval($x_sexo) : "NULL";
	$fieldList["`sexo`"] = $theValue;

	// Field estado_civil_id
	$theValue = ($x_estado_civil_id != "") ? intval($x_estado_civil_id) : "0";
	$fieldList["`estado_civil_id`"] = $theValue;

	// Field numero_hijos
	$theValue = ($x_numero_hijos != "") ? intval($x_numero_hijos) : "0";
	$fieldList["`numero_hijos`"] = $theValue;


	// Field numero_hijos_dep
	$theValue = ($x_numero_hijos_dep != "") ? intval($x_numero_hijos_dep) : "0";
	$fieldList["`numero_hijos_dep`"] = $theValue;

	// Field nombre_conyuge
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_nombre_conyuge) : $x_nombre_conyuge; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre_conyuge`"] = $theValue;

	// Field email
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_email) : $x_email; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`email`"] = $theValue;


	$theValue = ($x_nacionalidad_id != "") ? intval($x_nacionalidad_id) : "0";
	$fieldList["`nacionalidad_id`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_empresa) : $x_empresa; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`empresa`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_piesto) : $x_puesto; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`puesto`"] = $theValue;

	// Field fecha_registro
	$theValue = ($x_fecha_contratacion != "") ? " '" . ConvertDateToMysqlFormat($x_fecha_contratacion) . "'" : "Null";
	$fieldList["`fecha_contratacion`"] = $theValue;

	$theValue = ($x_salario_mensual != "") ? " '" . doubleval($x_salario_mensual) . "'" : "Null";
	$fieldList["`salario_mensual`"] = $theValue;


	// Field promotor_id
	$theValue = ($x_promotor_id != "") ? intval($x_promotor_id) : "NULL";
	$fieldList["`promotor_id`"] = $theValue;


	// insert into database
	$sSql = "INSERT INTO `cliente` (";
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
	$x_cliente_id = mysql_insert_id();

	
	//SOLICITUD CLIENTE

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

	
	//DIRECCION PART

	$fieldList = NULL;
	// Field cliente_id
//	$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";
	$fieldList["`cliente_id`"] = $x_cliente_id;

	// Field aval_id
//	$theValue = ($x_aval_id != "") ? intval($x_aval_id) : "NULL";
	$fieldList["`aval_id`"] = 0;

	// Field promotor_id
//	$theValue = ($x_promotor_id != "") ? intval($x_promotor_id) : "NULL";
	$fieldList["`promotor_id`"] = 0;

	// Field direccion_tipo_id
//	$theValue = ($x_direccion_tipo_id != "") ? intval($x_direccion_tipo_id) : "NULL";
	$fieldList["`direccion_tipo_id`"] = 1;

	// Field calle
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_calle) : $x_calle; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`calle`"] = $theValue;

	// Field colonia
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_colonia) : $x_colonia; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`colonia`"] = $theValue;

	// Field delegacion_id
	$theValue = ($x_delegacion_id != "") ? intval($x_delegacion_id) : "0";
	$fieldList["`delegacion_id`"] = $theValue;

	// Field propietario
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_propietario) : $x_propietario; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`propietario`"] = $theValue;

/*
	// Field entidad
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_entidad) : $x_entidad; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`entidad`"] = $theValue;
*/
	// Field codigo_postal
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_codigo_postal) : $x_codigo_postal; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`codigo_postal`"] = $theValue;

	// Field ubicacion
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_ubicacion) : $x_ubicacion; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ubicacion`"] = $theValue;

	// Field antiguedad
	$theValue = ($x_antiguedad != "") ? intval($x_antiguedad) : "0";
	$fieldList["`antiguedad`"] = $theValue;

	// Field vivienda_tipo_id
	$theValue = ($x_vivienda_tipo_id != "") ? intval($x_vivienda_tipo_id) : "0";
	$fieldList["`vivienda_tipo_id`"] = $theValue;

	// Field otro_tipo_vivienda
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_otro_tipo_vivienda) : $x_otro_tipo_vivienda; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`otro_tipo_vivienda`"] = $theValue;

	// Field telefono
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_telefono) : $x_telefono; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono`"] = $theValue;
	
	// Field ENTIDAD
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_entidad_domicilio) : $x_entidad_domicilio; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`entidad`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_telefono_movil) : $x_telefono_movil; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono_movil`"] = $theValue;
	

	// Field telefono_secundario
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_telefono_secundario) : $x_telefono_secundario; 
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
		echo phpmkr_error() . '<br>SQL AQUI: ' . $sSql;
		phpmkr_query('rollback;', $conn);	 
	 	exit();
	}


//DIRECCION NEG

	$fieldList = NULL;
	// Field cliente_id
//	$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";
	$fieldList["`cliente_id`"] = $x_cliente_id;

	// Field aval_id
//	$theValue = ($x_aval_id != "") ? intval($x_aval_id) : "NULL";
	$fieldList["`aval_id`"] = 0;

	// Field promotor_id
//	$theValue = ($x_promotor_id != "") ? intval($x_promotor_id) : "NULL";
	$fieldList["`promotor_id`"] = 0;

	// Field direccion_tipo_id
//	$theValue = ($x_direccion_tipo_id != "") ? intval($x_direccion_tipo_id) : "NULL";
	$fieldList["`direccion_tipo_id`"] = 2;

	// Field calle
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_calle2) : $x_calle2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`calle`"] = $theValue;

	// Field colonia
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_colonia2) : $x_colonia2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`colonia`"] = $theValue;

	// Field delegacion_id
	$theValue = ($x_delegacion_id2 != "") ? intval($x_delegacion_id2) : "0";
	$fieldList["`delegacion_id`"] = $theValue;

	// Field codigo_postal
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_codigo_postal2) : $x_codigo_postal2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`codigo_postal`"] = $theValue;

	// Field ubicacion
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_ubicacion2) : $x_ubicacion2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ubicacion`"] = $theValue;

	// Field antiguedad
	$theValue = ($x_antiguedad != "") ? intval($x_antiguedad2) : "0";
	$fieldList["`antiguedad`"] = $theValue;

	// Field vivienda_tipo_id
	$theValue = ($x_vivienda_tipo_id2 != "") ? intval($x_vivienda_tipo_id2) : "0";
	$fieldList["`vivienda_tipo_id`"] = $theValue;

	// Field otro_tipo_vivienda
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_otro_tipo_vivienda2) : $x_otro_tipo_vivienda2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`otro_tipo_vivienda`"] = $theValue;
	
	// Field ENTIDAD
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_entidad_negocio) : $x_entidad_negocio; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`entidad`"] = $theValue; 

	// Field telefono
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_telefono2) : $x_telefono2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono`"] = $theValue;

	// Field telefono_secundario
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_telefono_secundario2) : $x_telefono_secundario2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono_secundario`"] = $theValue;
	
	// Field propietario
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_propietario2) : $x_propietario2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`propietario`"] = $theValue;
	
	
	


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

	
	//GARANTIAS

	if($x_garantia_desc != ""){
		$fieldList = NULL;
		// Field cliente_id
	//	$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";
		$fieldList["`solicitud_id`"] = $x_solicitud_id;
	
		// Field descripcion
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_garantia_desc) : $x_garantia_desc; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`descripcion`"] = $theValue;
	
		// Field valor
		$theValue = ($x_garantia_valor != "") ? " '" . doubleval($x_garantia_valor) . "'" : "Null";
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
	
	
	
		//GASTOS

	$fieldList = NULL;
	// Field cliente_id

	$fieldList["`solicitud_id`"] = $x_solicitud_id;

	$theValue = ($x_renta_mensual != "") ? " '" . doubleval($x_renta_mensual) . "'" : "Null";
	$fieldList["`gastos_renta_negocio`"] = $theValue;


	$theValue = ($x_renta_mensula_domicilio != "") ? doubleval($x_renta_mensula_domicilio) : "0";
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
	
	
//REFERENCIAS CICLO


	$x_counter = 1;
	while($x_counter < 5){

		$fieldList = NULL;
		// Field cliente_id
//		$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";
		$fieldList["`solicitud_id`"] = $x_solicitud_id;

		$x_aux = "x_referencia_com_$x_counter";
		$x_aux = $$x_aux;
		if($x_aux != ""){
		
			// Field nombre_completo
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_aux) : $x_aux; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`nombre_completo`"] = $theValue;
		
			// Field telefono
			$aux_tel = "x_tel_referencia_$x_counter";
			$uax_tel = $$aux_tel;
			
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($uax_tel) : $uax_tel; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`telefono`"] = $theValue;
			
			$aux_parent = "x_parentesco_ref_$x_counter";
			$aux_parent = $$aux_parent;
			
			// Field parentesco_tipo_id
			$theValue = ($aux_parent != "") ? intval($aux_parent) : "NULL";
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
	





	
	//REFERENCIAS COMERCIALES...GASTOS E INGRESOS....SE INSERTA EN LA TABLA ORIGINAL PYME
	
	
	// Field referencia_com_1
	$fieldList = NULL;
	
	//	$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";
	$fieldList["`cliente_id`"] = $x_cliente_id;
	
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_telefono_sec) : $x_telefono_sec; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`otro_telefono_domicilio_2`"] = $theValue;
/*
	// Field referencia_com_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_referencia_com_2) : $x_referencia_com_2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`referencia_com_2`"] = $theValue;

	

	// Field parentesco_ref_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_parentesco_ref_3) : $x_parentesco_ref_3; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`parentesco_ref_3`"] = $theValue; */

	
	//FIEL GRIO NEGOCIO
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_giro_negocio"]) : $GLOBALS["x_giro_negocio"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`giro_negocio`"] = $theValue;

	
	// Field ing_fam_negocio
	$theValue = ($x_ing_fam_negocio != "") ? " '" . $x_ing_fam_negocio . "'" : "NULL";
	$fieldList["`ing_fam_negocio`"] = $theValue;

	// Field ing_fam_otro_th
	$theValue = ($x_ing_fam_otro_th != "") ? " '" . $x_ing_fam_otro_th . "'" : "NULL";
	$fieldList["`ing_fam_otro_th`"] = $theValue;

	// Field ing_fam_1
	$theValue = ($x_ing_fam_1 != "") ? " '" . $x_ing_fam_1 . "'" : "NULL";
	$fieldList["`ing_fam_1`"] = $theValue;

	// Field ing_fam_2
	$theValue = ($x_ing_fam_2 != "") ? " '" . $x_ing_fam_2 . "'" : "NULL";
	$fieldList["`ing_fam_2`"] = $theValue;

	// Field ing_fam_deuda_1
	$theValue = ($x_ing_fam_deuda_1 != "") ? " '" . $x_ing_fam_deuda_1 . "'" : "NULL";
	$fieldList["`ing_fam_deuda_1`"] = $theValue;

	// Field ing_fam_deuda_2
	$theValue = ($x_ing_fam_deuda_2 != "") ? " '" . $x_ing_fam_deuda_2 . "'" : "NULL";
	$fieldList["`ing_fam_deuda_2`"] = $theValue;

	// Field ing_fam_total
	$theValue = ($x_ing_fam_total != "") ? " '" . $x_ing_fam_total . "'" : "NULL";
	$fieldList["`ing_fam_total`"] = $theValue;

	// Field ing_fam_cuales_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_ing_fam_cuales_1) : $x_ing_fam_cuales_1; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ing_fam_cuales_1`"] = $theValue;

	// Field ing_fam_cuales_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_ing_fam_cuales_2) : $x_ing_fam_cuales_2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ing_fam_cuales_2`"] = $theValue;

	// Field ing_fam_cuales_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_ing_fam_cuales_3) : $x_ing_fam_cuales_3; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ing_fam_cuales_3`"] = $theValue;

	// Field ing_fam_cuales_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_ing_fam_cuales_4) : $x_ing_fam_cuales_4; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ing_fam_cuales_4`"] = $theValue;

	// Field ing_fam_cuales_5
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_ing_fam_cuales_5) : $x_ing_fam_cuales_5; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ing_fam_cuales_5`"] = $theValue;

	// Field flujos_neg_ventas
	$theValue = ($x_flujos_neg_ventas != "") ? " '" . $x_flujos_neg_ventas . "'" : "NULL";
	$fieldList["`flujos_neg_ventas`"] = $theValue;

	// Field flujos_neg_proveedor_1
	$theValue = ($x_flujos_neg_proveedor_1 != "") ? " '" . $x_flujos_neg_proveedor_1 . "'" : "NULL";
	$fieldList["`flujos_neg_proveedor_1`"] = $theValue;

	// Field flujos_neg_proveedor_2
	$theValue = ($x_flujos_neg_proveedor_2 != "") ? " '" . $x_flujos_neg_proveedor_2 . "'" : "NULL";
	$fieldList["`flujos_neg_proveedor_2`"] = $theValue;

	// Field flujos_neg_proveedor_3
	$theValue = ($x_flujos_neg_proveedor_3 != "") ? " '" . $x_flujos_neg_proveedor_3 . "'" : "NULL";
	$fieldList["`flujos_neg_proveedor_3`"] = $theValue;

	// Field flujos_neg_proveedor_4
	$theValue = ($x_flujos_neg_proveedor_4 != "") ? " '" . $x_flujos_neg_proveedor_4 . "'" : "NULL";
	$fieldList["`flujos_neg_proveedor_4`"] = $theValue;

	// Field flujos_neg_gasto_1
	$theValue = ($x_flujos_neg_gasto_1 != "") ? " '" . $x_flujos_neg_gasto_1 . "'" : "NULL";
	$fieldList["`flujos_neg_gasto_1`"] = $theValue;

	// Field flujos_neg_gasto_2
	$theValue = ($x_flujos_neg_gasto_2 != "") ? " '" . $x_flujos_neg_gasto_2 . "'" : "NULL";
	$fieldList["`flujos_neg_gasto_2`"] = $theValue;

	// Field flujos_neg_gasto_3
	$theValue = ($x_flujos_neg_gasto_3 != "") ? " '" . $x_flujos_neg_gasto_3 . "'" : "NULL";
	$fieldList["`flujos_neg_gasto_3`"] = $theValue;

	// Field flujos_neg_cual_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_flujos_neg_cual_1) : $x_flujos_neg_cual_1; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_1`"] = $theValue;

	// Field flujos_neg_cual_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_flujos_neg_cual_2) : $x_flujos_neg_cual_2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_2`"] = $theValue;

	// Field flujos_neg_cual_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_flujos_neg_cual_3) : $x_flujos_neg_cual_3; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_3`"] = $theValue;

	// Field flujos_neg_cual_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_flujos_neg_cual_4) : $x_flujos_neg_cual_4; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_4`"] = $theValue;

	// Field flujos_neg_cual_5
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_flujos_neg_cual_5) : $x_flujos_neg_cual_5; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_5`"] = $theValue;

	// Field flujos_neg_cual_6
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_flujos_neg_cual_6) : $x_flujos_neg_cual_6; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_6`"] = $theValue;

	// Field flujos_neg_cual_7
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_flujos_neg_cual_7) : $x_flujos_neg_cual_7; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_7`"] = $theValue;

	// Field ingreso_negocio
	$theValue = ($x_ingreso_negocio != "") ? " '" . $x_ingreso_negocio . "'" : "NULL";
	$fieldList["`ingreso_negocio`"] = $theValue;

	// Field inv_neg_fija_conc_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_inv_neg_fija_conc_1) : $x_inv_neg_fija_conc_1; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_fija_conc_1`"] = $theValue;

	// Field inv_neg_fija_conc_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_inv_neg_fija_conc_2) : $x_inv_neg_fija_conc_2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_fija_conc_2`"] = $theValue;

	// Field inv_neg_fija_conc_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_inv_neg_fija_conc_3) : $x_inv_neg_fija_conc_3; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_fija_conc_3`"] = $theValue;


	// Field inv_neg_fija_conc_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_inv_neg_fija_conc_4) : $x_inv_neg_fija_conc_4; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_fija_conc_4`"] = $theValue;

	// Field inv_neg_fija_valor_1
	$theValue = ($x_inv_neg_fija_valor_1 != "") ? " '" . $x_inv_neg_fija_valor_1 . "'" : "NULL";
	$fieldList["`inv_neg_fija_valor_1`"] = $theValue;

	// Field inv_neg_fija_valor_2
	$theValue = ($x_inv_neg_fija_valor_2 != "") ? " '" . $x_inv_neg_fija_valor_2 . "'" : "NULL";
	$fieldList["`inv_neg_fija_valor_2`"] = $theValue;

	// Field inv_neg_fija_valor_3
	$theValue = ($x_inv_neg_fija_valor_3 != "") ? " '" . $x_inv_neg_fija_valor_3 . "'" : "NULL";
	$fieldList["`inv_neg_fija_valor_3`"] = $theValue;

	// Field inv_neg_fija_valor_4
	$theValue = ($x_inv_neg_fija_valor_4 != "") ? " '" . $x_inv_neg_fija_valor_4 . "'" : "NULL";
	$fieldList["`inv_neg_fija_valor_4`"] = $theValue;

	// Field inv_neg_total_fija
	$theValue = ($x_inv_neg_total_fija != "") ? " '" . $x_inv_neg_total_fija . "'" : "NULL";
	$fieldList["`inv_neg_total_fija`"] = $theValue;

	// Field inv_neg_var_conc_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_inv_neg_var_conc_1) : $x_inv_neg_var_conc_1; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_var_conc_1`"] = $theValue;

	// Field inv_neg_var_conc_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_inv_neg_var_conc_2) : $x_inv_neg_var_conc_2; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_var_conc_2`"] = $theValue;

	// Field inv_neg_var_conc_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_inv_neg_var_conc_3) : $x_inv_neg_var_conc_3; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_var_conc_3`"] = $theValue;

	// Field inv_neg_var_conc_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_inv_neg_var_conc_4) : $x_inv_neg_var_conc_4; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_var_conc_4`"] = $theValue;

	// Field inv_neg_var_valor_1
	$theValue = ($x_inv_neg_var_valor_1 != "") ? " '" . $x_inv_neg_var_valor_1 . "'" : "NULL";
	$fieldList["`inv_neg_var_valor_1`"] = $theValue;

	// Field inv_neg_var_valor_2
	$theValue = ($x_inv_neg_var_valor_2 != "") ? " '" . $x_inv_neg_var_valor_2 . "'" : "NULL";
	$fieldList["`inv_neg_var_valor_2`"] = $theValue;

	// Field inv_neg_var_valor_3
	$theValue = ($x_inv_neg_var_valor_3 != "") ? " '" . $x_inv_neg_var_valor_3 . "'" : "NULL";
	$fieldList["`inv_neg_var_valor_3`"] = $theValue;

	// Field inv_neg_var_valor_4
	$theValue = ($x_inv_neg_var_valor_4 != "") ? " '" . $x_inv_neg_var_valor_4 . "'" : "NULL";
	$fieldList["`inv_neg_var_valor_4`"] = $theValue;

	// Field inv_neg_total_var
	$theValue = ($x_inv_neg_total_var != "") ? " '" . $x_inv_neg_total_var . "'" : "NULL";
	$fieldList["`inv_neg_total_var`"] = $theValue;

	// Field inv_neg_activos_totales
	$theValue = ($x_inv_neg_activos_totales != "") ? " '" . $x_inv_neg_activos_totales . "'" : "NULL";
	$fieldList["`inv_neg_activos_totales`"] = $theValue;

	// Field fecha
	$theValue = ($x_fecha_contratacion != "") ? " '" . ConvertDateToMysqlFormat($x_fecha_contratacion) . "'" : "NULL";
	$fieldList["`fecha`"] = $theValue;
	
	
	
	
	
	
	
	
	
	
	// insert into database
	$sSql = "INSERT INTO `formatoindividual` (";
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
	
	
	phpmkr_query('commit;', $conn);	 
	
	//mensaje alta exitosa
	$_SESSION["mensajeAlta"] = "REGISTRO EXITOSO, YA PUEDE CERRAR ESTA VENTANA";
	
	
}//TERMINA LA FUNCION  GRAL INSERTA EN PYME
		
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/************************************************************************************************************************************************/
	
?>