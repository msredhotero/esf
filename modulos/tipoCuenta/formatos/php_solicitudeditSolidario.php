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
<link href="../crm.css" rel="stylesheet" type="text/css" />
</head>

<body>

<script type="text/javascript" src="../ew.js"></script>
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
			if (!EW_onError(EW_this, EW_this.x_delegacion_id, "SELECT", "Indique la delegaciÃ³n del domicilio particular."))
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
			if (!EW_onError(EW_this, EW_this.x_delegacion_id, "SELECT", "Indique la delegaciÃ³n del domicilio particular del titular."))
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
			if (!EW_onError(EW_this, EW_this.x_delegacion_id2, "SELECT", "Indique la delegaciÃ³n del domicilio del negocio del titular."))
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
			if (!EW_onError(EW_this, EW_this.x_delegacion_id3, "SELECT", "Indique la delegaciÃ³n del domicilio particular del aval."))
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



function EW_checkMyForm() {
EW_this = document.solicitudedit;
validada = true;

if (validada == true && EW_this.x_promotor_id && !EW_hasValue(EW_this.x_promotor_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_promotor_id, "SELECT", "Indique el promotor."))
		validada = false;
}


if (validada == true && EW_this.x_credito_tipo_id && !EW_hasValue(EW_this.x_credito_tipo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_credito_tipo_id, "SELECT", "Indique el crÃ©dito deseado."))
		validada = false;
}
if (validada == true && EW_this.x_importe_solicitado && !EW_hasValue(EW_this.x_importe_solicitado, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_importe_solicitado, "TEXT", "Indique el importe del crÃ©dito a solicitar."))
		validada = false;
}
if (validada == true && EW_this.x_importe_solicitado && !EW_checknumber(EW_this.x_importe_solicitado.value)) {
	if (!EW_onError(EW_this, EW_this.x_importe_solicitado, "TEXT", "El importe del crÃ©dito solicitado es incorrecto, por favor verifiquelo."))
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
	if (!EW_onError(EW_this, EW_this.x_delegacion_id, "SELECT", "Indique la delegaciÃ³n del domicilio particular."))
		validada = false;
}

if (validada == true && EW_this.x_delegacion_id.value == 17) {
	if (validada == true && EW_this.x_otra_delegacion && !EW_hasValue(EW_this.x_otra_delegacion, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_otra_delegacion, "TEXT", "Indique la delegaciÃ³n del domicilio particular."))
			validada = false;
	}
}
*/
/*
if (validada == true && EW_this.x_entidad && !EW_hasValue(EW_this.x_entidad, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_entidad, "TEXT", "Indique la entidad del domicilio particular."))

		validada = false;
}
*/
/*
if (validada == true && EW_this.x_codigo_postal && !EW_hasValue(EW_this.x_codigo_postal, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal, "TEXT", "Indique el CÃ³digo Postal del domicilio particular."))
		validada = false;
}
if (validada == true && EW_this.x_ubicacion && !EW_hasValue(EW_this.x_ubicacion, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ubicacion, "TEXT", "Indique la UbicaciÃ³n del domicilio particular."))
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
	if (!EW_onError(EW_this, EW_this.x_delegacion_id2, "SELECT", "Indique la delegaciÃ³n del domicilio de negocio."))
		validada = false;
}
*/
/*
if (validada == true && EW_this.x_delegacion_id2.value == 17) {
	if (validada == true && EW_this.x_otra_delegacion2 && !EW_hasValue(EW_this.x_otra_delegacion2, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_otra_delegacion2, "TEXT", "Indique la delegaciÃ³n del domicilio de negocio."))
			validada = false;
	}
}
*/
/*
if (validada == true && EW_this.x_entidad2 && !EW_hasValue(EW_this.x_entidad2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_entidad2, "TEXT", "Indique la entidad del domicilio de negocio."))
		validada = false;
}
*/
/*
if (validada == true && EW_this.x_codigo_postal2 && !EW_hasValue(EW_this.x_codigo_postal2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal2, "TEXT", "Indique el CÃ³digo Postal del domicilio de negocio."))
		validada = false;
}
if (validada == true && EW_this.x_ubicacion2 && !EW_hasValue(EW_this.x_ubicacion2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ubicacion2, "TEXT", "Indique la UbicaciÃ³n del domicilio de negocio."))
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
/*	
	if (validada == true && EW_this.x_nombre_completo_aval && !EW_hasValue(EW_this.x_nombre_completo_aval, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_nombre_completo_aval, "TEXT", "Indique el nombre completo del Aval."))
			validada = false;
	}
	if (validada == true && EW_this.x_parentesco_tipo_id_aval && !EW_hasValue(EW_this.x_parentesco_tipo_id_aval, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_aval, "SELECT", "Indique el parentesco del Aval."))
			validada = false;
	}
	if (validada == true && EW_this.x_telefono3 && !EW_hasValue(EW_this.x_telefono3, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_telefono3, "TEXT", "Indique el telÃ©fono del Aval."))
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
		if (!EW_onError(EW_this, EW_this.x_delegacion_id3, "SELECT", "Indique la delegaciÃ³n del domicilio del aval."))
			validada = false;
	}
	
	if (validada == true && EW_this.x_delegacion_id3.value == 17) {
		if (validada == true && EW_this.x_otra_delegacion3 && !EW_hasValue(EW_this.x_otra_delegacion3, "TEXT" )) {
			if (!EW_onError(EW_this, EW_this.x_otra_delegacion3, "TEXT", "Indique la delegaciÃ³n del domicilio del aval."))
				validada = false;
		}
	}
	
	if (validada == true && EW_this.x_entidad3 && !EW_hasValue(EW_this.x_entidad3, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_entidad3, "TEXT", "Indique la entidad del domicilio del aval."))
			validada = false;
	}
	
	if (validada == true && EW_this.x_codigo_postal3 && !EW_hasValue(EW_this.x_codigo_postal3, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_codigo_postal3, "TEXT", "Indique el CÃ³digo Postal del domicilio del aval."))
			validada = false;
	}
	if (validada == true && EW_this.x_ubicacion3 && !EW_hasValue(EW_this.x_ubicacion3, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_ubicacion3, "TEXT", "Indique la UbicaciÃ³n del domicilio del aval."))
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
		if (!EW_onError(EW_this, EW_this.x_ocupacion, "TEXT", "Indique la ocupaciÃ³n del Aval."))
			validada = false;
	}	
}



if(document.getElementById('garantias').className == "TG_visible"){
	if (validada == true && EW_this.x_garantia_desc && !EW_hasValue(EW_this.x_garantia_desc, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_garantia_desc, "TEXT", "Indique la descripciÃ³n de la garantÃ­a."))
			validada = false;
	}
	if (validada == true && EW_this.x_garantia_valor && !EW_hasValue(EW_this.x_garantia_valor, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_garantia_valor, "TEXT", "Indique el valor de la garantÃ­a."))
			validada = false;
	}
	if (validada == true && EW_this.x_garantia_valor && !EW_checknumber(EW_this.x_garantia_valor.value)) {
		if (!EW_onError(EW_this, EW_this.x_garantia_valor, "TEXT", "El valor de la garantÃ­a es incorrecto, por favor verifiquelo."))
			validada = false;
	}
}

*/

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
	if (!EW_onError(EW_this, EW_this.x_telefono_ref_1, "TEXT", "Indique el telÃ©fono de la referencia 1."))
		validada = false;
}
if (validada == true && EW_this.x_parentesco_tipo_id_ref_1 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_1, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_1, "SELECT", "Indique el parentesco en la referencia 1."))
		validada = false;
}

if (validada == true && EW_this.x_nombre_completo_ref_2 && EW_hasValue(EW_this.x_nombre_completo_ref_2, "TEXT" )) {
	if (validada == true && EW_this.x_telefono_ref_2 && !EW_hasValue(EW_this.x_telefono_ref_2, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_telefono_ref_2, "TEXT", "Indique el telÃ©fono de la referencia 2."))
			validada = false;
	}
	if (validada == true && EW_this.x_parentesco_tipo_id_ref_2 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_2, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_2, "SELECT", "Indique el parentesco en la referencia 2."))
			validada = false;
	}
}

if (validada == true && EW_this.x_nombre_completo_ref_3 && EW_hasValue(EW_this.x_nombre_completo_ref_3, "TEXT" )) {
	if (validada == true && EW_this.x_telefono_ref_3 && !EW_hasValue(EW_this.x_telefono_ref_3, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_telefono_ref_3, "TEXT", "Indique el telÃ©fono de la referencia 3."))
			validada = false;
	}
	if (validada == true && EW_this.x_parentesco_tipo_id_ref_3 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_3, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_3, "SELECT", "Indique el parentesco en la referencia 3."))
			validada = false;
	}
}

if (validada == true && EW_this.x_nombre_completo_ref_4 && EW_hasValue(EW_this.x_nombre_completo_ref_4, "TEXT" )) {
	if (validada == true && EW_this.x_telefono_ref_4 && !EW_hasValue(EW_this.x_telefono_ref_4, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_telefono_ref_4, "TEXT", "Indique el telÃ©fono de la referencia 4."))
			validada = false;
	}
	if (validada == true && EW_this.x_parentesco_tipo_id_ref_4 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_4, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_4, "SELECT", "Indique el parentesco en la referencia 4."))
			validada = false;
	}
}
if (validada == true && EW_this.x_nombre_completo_ref_5 && EW_hasValue(EW_this.x_nombre_completo_ref_5, "TEXT" )) {
	if (validada == true && EW_this.x_telefono_ref_5 && !EW_hasValue(EW_this.x_telefono_ref_5, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_telefono_ref_5, "TEXT", "Indique el telÃ©fono de la referencia 5."))
			validada = false;
	}
	if (validada == true && EW_this.x_parentesco_tipo_id_ref_5 && !EW_hasValue(EW_this.x_parentesco_tipo_id_ref_5, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_parentesco_tipo_id_ref_5, "SELECT", "Indique el parentesco en la referencia 5."))
			validada = false;
	}
}




/*
if(validada == true && EW_this.x_acepto.checked == false){
	alert("Debe de marcar la casilla: Aceptao los tÃ©rminos y condiciones.");
	validada = false;
}
*/
if(validada == true){
	EW_this.a_edit.value = "U";
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
<form name="solicitudedit" id="solicitudedit" action="php_solicitudedit.php" method="post" >
<input type="hidden" name="x_win" value="<?=$x_win?>">
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
  <a href="php_solicitud_print.php?solicitud_id=<?=$x_solicitud_id?>" title="Imprimir Solicitud" target="_blank">        
    <img src="../images/tbarImport.gif" width="28" height="27" border="0" />
    </a>         
        </td>
        <td width="30">&nbsp;</td>
        <td width="144" align="center" valign="middle">
        <?php if($x_soliitud_status_id > 5){ ?>
<a href="php_pagare_print.php?solicitud_id=<?=$x_solicitud_id?>" title="Imprimir Pagare" target="_blank">        
        <img src="../images/tbarImport.gif" width="28" height="27" border="0" />
        </a>          
        <?php } ?>
        </td>
        <td width="31">&nbsp;</td>
        <td width="143" align="center" valign="middle">
        <?php if($x_soliitud_status_id > 5){ ?>
  <a href="php_contrato_print.php?solicitud_id=<?=$x_solicitud_id?>" title="Imprimir Contrato" target="_blank">        
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
        <td colspan="2" class="texto_normal"><b>PERSONAL</b>
		<input type="hidden" name="x_credito_tipo_id" value="1" />
        </td>
        <td width="230"><div align="right"><span class="texto_normal">&nbsp;Fecha Solicitud:</span></div></td>
        <td width="164"><span class="texto_normal"> <b> <?php echo $currdate; ?> </b> </span>
            <input name="x_fecha_registro" type="hidden" value="<?php echo $currdate; ?>" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Importe solicitado: </span></td>
        <td width="111"><div align="left">
            <input class="importe" name="x_importe_solicitado" type="text" id="x_importe_solicitado" value="<?php echo FormatNumber(@$x_importe_solicitado,0,0,0,0) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)" onblur="validaimporte()" />
        </div></td>
        <td width="10">&nbsp;</td>
        <td><div align="right"><span class="texto_normal">Plazo deseado (Meses):</span></div></td>
        <td><span class="texto_normal">
          <?php
		$x_estado_civil_idList = "<select name=\"x_plazo_id\" class=\"texto_normal\">";
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
		echo $x_estado_civil_idList;
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
      <div id="actividad1" class="TG_visible">EspecÃ­ficamente:</div>
      <div id="actividad2" class="TG_hidden">Consistentes en:</div>
      <div id="actividad3" class="TG_hidden">Especificar qu&eacute; y para qu&eacute;:</div>      </td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="4"><textarea name="x_actividad_desc" cols="60" rows="5" id="x_actividad_desc"><?php echo htmlentities($x_actividad_desc); ?></textarea></td>
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
            &nbsp;<img src="images/ew_calendar.gif" id="cx_tit_fecha_nac" alt="Calendario" style="cursor:pointer;cursor:hand;">
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
          <td width="160" align="left" valign="middle"><div align="left"><span class="texto_normal">Genero:
            <input name="x_sexo" type="radio" value="<?php echo htmlspecialchars("1"); ?>" checked="checked" <?php if (@$x_sexo == "1") { echo "checked"; } ?> />
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
	    <td><span class="texto_normal">Referencia de UbicaciÃ³n:</span></td>
	  <td colspan="4"><input name="x_ubicacion" type="text" class="texto_normal" id="x_ubicacion" value="<?php echo htmlentities(@$x_ubicacion) ?>" size="80" maxlength="250" /></td>
	  </tr>
	<tr>
	  <td class="texto_normal">Antiguedad en Domicilio: </td>
	  <td colspan="4"><span class="texto_normal">
	    <input name="x_antiguedad" type="text" class="texto_normal" id="x_antiguedad" onKeyPress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_antiguedad) ?>" size="2" maxlength="2"/>
(aÃ±os)</span></td>
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
        Â <span class="texto_normal">Mismos que el Dom. Part.</span>		</div>		</td>
        </tr>	
      <tr>
        <td><span class="texto_normal">Empresa: </span></td>
        <td colspan="3"><input name="x_empresa" type="text" class="texto_normal" id="x_empresa" value="<?php echo htmlentities(@$x_empresa) ?>" size="80" maxlength="250" /></td>
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
            &nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_contratacion" alt="Calendario" style="cursor:pointer;cursor:hand;">
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
        <td><span class="texto_normal">Referencia de UbicaciÃ³n:</span></td>
        <td colspan="4"><input name="x_ubicacion2" type="text" class="texto_normal" id="x_ubicacion2" value="<?php echo htmlentities(@$x_ubicacion2) ?>" size="80" maxlength="250" /></td>
      </tr>
      <tr>
        <td class="texto_normal">Antiguedad en Domicilio: </td>
        <td colspan="4"><span class="texto_normal">
          <input name="x_antiguedad2" type="text" class="texto_normal" id="x_antiguedad2" onKeyPress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_antiguedad2) ?>" size="2" maxlength="2"/>
        (aÃ±os)</span></td>
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
    <td colspan="3" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Datos Aval </td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="texto_normal">Aval: </td>
        <td colspan="3"><table width="534" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="155"><div align="center"><span class="texto_normal">
              <input name="x_nombre_completo_aval" type="text" class="texto_normal" id="x_nombre_completo_aval" value="<?php echo htmlentities(@$x_nombre_completo_aval) ?>" size="25" maxlength="100" />
            </span></div></td>
            <td width="178"><div align="center"><span class="texto_normal">
              <input name="x_apellido_paterno_aval" type="text" class="texto_normal" id="x_apellido_paterno_aval" value="<?php echo htmlentities(@$x_apellido_paterno_aval) ?>" size="25" maxlength="100" />
            </span></div></td>
            <td width="201"><div align="center"><span class="texto_normal">
              <input name="x_apellido_materno_aval" type="text" class="texto_normal" id="x_apellido_materno_aval" value="<?php echo htmlentities(@$x_apellido_materno_aval) ?>" size="25" maxlength="100" />
            </span></div></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="22" class="texto_normal">&nbsp;</td>
        <td colspan="3"><table width="534" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="155"><div align="center"><span class="texto_normal">Nombre</span></div></td>
            <td width="178"><div align="center"><span class="texto_normal">Apellido Paterno</span></div></td>
            <td width="201"><div align="center"><span class="texto_normal">Apellido Materno</span></div></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td class="texto_normal">Parentesco:</td>
        <td><?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_tipo_id_aval\" class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_tipo_id_aval) {
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
        <td class="texto_normal">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal">RFC:</td>
        <td><input name="x_aval_rfc" type="text" class="texto_normal" id="x_aval_rfc" value="<?php echo htmlspecialchars(@$x_aval_rfc) ?>" size="20" maxlength="20" /></td>
        <td class="texto_normal">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal">CURP:</td>
        <td><input name="x_aval_curp" type="text" class="texto_normal" id="x_aval_curp" value="<?php echo htmlspecialchars(@$x_aval_curp) ?>" size="20" maxlength="20" /></td>
        <td class="texto_normal">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal">Tipo de Negocio: </td>
        <td colspan="2"><input name="x_tipo_negocio_aval" type="text" class="texto_normal" id="x_tipo_negocio_aval" value="<?php echo htmlspecialchars(@$x_tipo_negocio_aval) ?>" size="80" maxlength="250" /></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal">Fecha de Nacimiento:</td>
        <td colspan="2" align="left" valign="top"><table width="533" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="121" align="left"><span class="texto_normal">
              <input name="x_tit_fecha_nac_aval" type="text" id="x_tit_fecha_nac_aval" value="<?php echo FormatDateTime(@$x_tit_fecha_nac_aval,7); ?>" size="12" maxlength="12" />
              &nbsp;<img src="images/ew_calendar.gif" id="cx_tit_fecha_nac_aval" alt="Calendario" style="cursor:pointer;cursor:hand;" />
              <script type="text/javascript">
            Calendar.setup(
            {
            inputField : "x_tit_fecha_nac_aval", // ID of the input field
            ifFormat : "%d/%m/%Y", // the date format
            button : "cx_tit_fecha_nac_aval" // ID of the button
            }
            );
            </script>
            </span></td>
            <td width="160" align="left" valign="middle"><div align="left"><span class="texto_normal">Genero:
              <input name="x_sexo_aval" type="radio" value="<?php echo htmlspecialchars("1"); ?>" checked="checked"<?php if (@$x_sexo_aval == "1") { echo "checked"; } ?> />
              <?php echo "M"; ?> <?php echo EditOptionSeparator(0); ?>
              <input type="radio" name="x_sexo_aval"<?php if (@$x_sexo_aval == "2") { echo "checked"; } ?> value="<?php echo htmlspecialchars("2"); ?>" />
              <?php echo "F"; ?> <?php echo EditOptionSeparator(1); ?></span></div></td>
            <td width="243"><div align="left"><span class="texto_normal">Edo. Civil:
              <?php
		$x_estado_civil_idList = "<select name=\"x_estado_civil_id_aval\" class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `estado_civil_id`, `descripcion` FROM `estado_civil`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["estado_civil_id"] == @$x_estado_civil_id_aval) {
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
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal">&nbsp;No. de hijos
          : </td>
        <td colspan="2"><span class="texto_normal">
          <input name="x_numero_hijos_aval" type="text" class="texto_normal" id="x_numero_hijos_aval"  onkeypress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_numero_hijos_aval) ?>" size="2" maxlength="1"/>
          Hijos dependientes:
          <input name="x_numero_hijos_dep_aval" type="text" class="texto_normal" id="x_numero_hijos_dep_aval"  onkeypress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_numero_hijos_dep_aval) ?>" size="2" maxlength="1"/>
        </span></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal">Nombre del Conyuge:</td>
        <td colspan="2"><input name="x_nombre_conyuge_aval" type="text" class="texto_normal" id="x_nombre_conyuge_aval" value="<?php echo htmlentities(@$x_nombre_conyuge_aval) ?>" size="80" maxlength="250" /></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal">Email:</td>
        <td colspan="2"><input name="x_email_aval" type="text" class="texto_normal" id="x_email_aval" value="<?php echo htmlspecialchars(@$x_email_aval) ?>" size="50" maxlength="150" /></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal">Nacionalidad:</td>
        <td colspan="2"><span class="texto_normal">
          <?php
		$x_nac_idList = "<select name=\"x_nacionalidad_id_aval\" class=\"texto_normal\">";
		$x_nac_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT nacionalidad_id, pais_nombre FROM nacionalidad";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_nac_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["nacionalidad_id"] == @$x_nacionalidad_id_aval) {
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
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal">Tels.:</td>
        <td colspan="2"><span class="texto_normal">
          <input name="x_telefono3" type="text" class="texto_normal" id="x_telefono3" value="<?php echo htmlspecialchars(@$x_telefono3) ?>" size="20" maxlength="20" />
          -
          <input name="x_telefono3_sec" type="text" class="texto_normal" id="x_telefono3_sec" value="<?php echo htmlspecialchars(@$x_telefono3_sec) ?>" size="20" maxlength="20" />
        </span></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal">Tel&eacute;fono celular:</td>
        <td><span class="texto_normal">
          <input name="x_telefono_secundario3" type="text" class="texto_normal" id="x_telefono_secundario3" value="<?php echo htmlspecialchars(@$x_telefono_secundario3) ?>" size="20" maxlength="20" />
        </span></td>
        <td class="texto_normal">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4" class="texto_normal" >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4" class="texto_normal" style=" border: solid 1px #666"><input type="checkbox" name="x_mismos_titluar" value="0" onclick="mismosdomtit()" />
          <em>Mismo domicilio Particular que el  Titular</em></td>
        </tr>
      <tr>
        <td class="texto_normal_bold">&nbsp;</td>
        <td colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal_bold">Domicilio Particular </td>
        <td colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td width="159"><span class="texto_normal">Calle no. Ext e Int. : </span></td>
        <td colspan="3"><input name="x_calle3" type="text" class="texto_normal" id="x_calle3" value="<?php echo htmlentities(@$x_calle3) ?>" size="80" maxlength="150" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Colonia: </span></td>
        <td colspan="3"><input name="x_colonia3" type="text" class="texto_normal" id="x_colonia3" value="<?php echo htmlentities(@$x_colonia3) ?>" size="80" maxlength="150" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Entidad:</span></td>
        <td width="125"><span class="texto_normal">
          <?php
		$x_delegacion_idList = "<select name=\"x_entidad_id3\" class=\"texto_normal\" onchange=\"showHint(this,'txtHint3', 'x_delegacion_id3')\">";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `entidad_id`, `nombre` FROM `entidad`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["entidad_id"] == @$x_entidad_id3) {
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
        <td width="400"><div align="left"><span class="texto_normal">
          <input type="hidden" name="x_delegacion_id_temp1" value="" />
          </span><span class="texto_normal">
		<div id="txtHint3" class="texto_normal">          
          Del/Mun: 
            <?php
		if($x_entidad_id3 > 0 ){
		$x_delegacion_idList = "<select name=\"x_delegacion_id3\" class=\"texto_normal\">";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where entidad_id = $x_entidad_id3";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["delegacion_id"] == @$x_delegacion_id3) {
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
        <td width="16"><div align="left"></div></td>
      </tr>
      <tr>
        <td><span class="texto_normal">C.P.
          : </span></td>
        <td colspan="4"><span class="texto_normal">
          <input name="x_codigo_postal3" type="text" class="texto_normal" id="x_codigo_postal3" onkeypress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_codigo_postal3) ?>" size="5" maxlength="10"/>
        </span></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Referencia de Ubicaci&oacute;n:</span></td>
        <td colspan="4"><input name="x_ubicacion3" type="text" class="texto_normal" id="x_ubicacion3" value="<?php echo htmlentities(@$x_ubicacion3) ?>" size="80" maxlength="250" /></td>
      </tr>
      <tr>
        <td class="texto_normal">Antiguedad en Domicilio: </td>
        <td colspan="4"><span class="texto_normal">
          <input name="x_antiguedad3" type="text" class="texto_normal" id="x_antiguedad3" onkeypress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_antiguedad3) ?>" size="2" maxlength="2"/>
          (a&ntilde;os) </span></td>
      </tr>
      <tr>
        <td class="texto_normal"> Tipo de Vivienda:</td>
        <td colspan="4"><span class="texto_normal">
          <?php
		$x_vivienda_tipo_idList = "<select name=\"x_vivienda_tipo_id2\" class=\"texto_normal\" onchange=\"viviendatipo('2')\">";
		$x_vivienda_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `vivienda_tipo_id`, `descripcion` FROM `vivienda_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_vivienda_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["vivienda_tipo_id"] == @$x_vivienda_tipo_id2) {
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
        <td ></td>
        <td colspan="4" class="texto_normal">


		<div id="prop1rentada2" class="<?php if($x_vivienda_tipo_id2 == 2){ echo "TG_visible";}else{ echo "TG_hidden";} ?>">
		Arrendatario (Nombre y Tel):&nbsp;
		<input class="texto_normal" type="text" name="x_propietario_renta2" id="x_propietario_renta2" value="<?php echo $x_propietario_renta2; ?>" size="25" maxlength="150" />&nbsp;
		Renta:
		<input class="importe" name="x_gastos_renta_casa2" type="text" id="x_gastos_renta_casa2" value="<?php echo htmlspecialchars(@$x_gastos_renta_casa2) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/>        
		</div>		
        
		<div id="prop2" class="<?php if($x_vivienda_tipo_id2 == 3){ echo "TG_visible";}else{ echo "TG_hidden";} ?>">
		Propietario de la Vivienda:&nbsp;
		<input class="texto_normal" type="text" name="x_propietario_familiar2" id="x_propietario_familiar2" value="<?php echo $x_propietario2; ?>" size="50" maxlength="150" />
		</div>		

		<div id="prop1credito2" class="<?php if($x_vivienda_tipo_id2 == 4){ echo "TG_visible";}else{ echo "TG_hidden";} ?>">
		Empresa:&nbsp;
		<input class="texto_normal" type="text" name="x_propietario_ch2" id="x_propietario_ch2" value="<?php echo $x_propietario_ch2; ?>" size="25" maxlength="150" />&nbsp;
        Pago Mensual:
		<input class="importe" name="x_gastos_credito_hipotecario2" type="text" id="x_gastos_credito_hipotecario2" value="<?php echo htmlspecialchars(@$x_gastos_credito_hipotecario2) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/>        
		</div>		
                
        </td>
      </tr>
      <tr>
        <td colspan="5" class="texto_normal" >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="5" class="texto_normal" style=" border-left : solid 1px #666; border-right : solid 1px #666; border-top : solid 1px #666"><input type="checkbox" name="x_mismosava2" value="0" onclick="mismosdomtitneg()" />
          <em>&nbsp;Mismo domicilio de Negocio  (TITULAR).</em></td>
      </tr>
      <tr>
        <td colspan="5" class="texto_normal" style=" border-left : solid 1px #666; border-right : solid 1px #666; border-bottom : solid 1px #666">
		<div align="left">
          <input type="checkbox" name="x_mismosava" value="0" onclick="mismosdomava()" />
        Â <em><span class="texto_normal">Mismo domicilio que el Particular (AVAL).</span></em><span class="texto_normal"></span></div>		</td>
        </tr>
      <tr>
        <td class="texto_normal_bold">&nbsp;</td>
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_normal_bold">Domicilio del Negocio </td>
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td width="159"><span class="texto_normal">Calle no. Ext e Int. : </span></td>
        <td colspan="3"><input name="x_calle3_neg" type="text" class="texto_normal" id="x_calle3_neg" value="<?php echo htmlentities(@$x_calle3_neg) ?>" size="80" maxlength="150" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Colonia: </span></td>
        <td colspan="3"><input name="x_colonia3_neg" type="text" class="texto_normal" id="x_colonia3_neg" value="<?php echo htmlentities(@$x_colonia3_neg) ?>" size="80" maxlength="150" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Entidad:</span></td>
        <td width="125"><span class="texto_normal">
          <?php
		$x_delegacion_idList = "<select name=\"x_entidad_id3_neg\" class=\"texto_normal\" onchange=\"showHint(this,'txtHint3_neg', 'x_delegacion_id3_neg')\">";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `entidad_id`, `nombre` FROM `entidad`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["entidad_id"] == @$x_entidad_id3_neg) {
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
        <td width="400"><div align="left"><span class="texto_normal">
          <input type="hidden" name="x_delegacion_id_temp1_2" value="" />
          <input type="hidden" name="x_delegacion_id_temp2" value="" />
           </span><span class="texto_normal">
		<div id="txtHint3_neg" class="texto_normal">
			Del/Mun:           
            <?php
		if($x_entidad_id3_neg > 0 ){
		$x_delegacion_idList = "<select name=\"x_delegacion_id3_neg\" class=\"texto_normal\">";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where entidad_id = $x_entidad_id3_neg";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["delegacion_id"] == @$x_delegacion_id3_neg) {
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
        <td width="16"><div align="left"></div></td>
      </tr>
      <tr>
        <td><span class="texto_normal">C.P.
          : </span></td>
        <td colspan="4"><span class="texto_normal">
          <input name="x_codigo_postal3_neg" type="text" class="texto_normal" id="x_codigo_postal3_neg" onkeypress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_codigo_postal3_neg) ?>" size="5" maxlength="10"/>
        </span></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Referencia de Ubicaci&oacute;n:</span></td>
        <td colspan="4"><input name="x_ubicacion3_neg" type="text" class="texto_normal" id="x_ubicacion3_neg" value="<?php echo htmlentities(@$x_ubicacion3_neg) ?>" size="80" maxlength="250" /></td>
      </tr>
      <tr>
        <td class="texto_normal">Antiguedad en Domicilio: </td>
        <td colspan="4"><span class="texto_normal">
          <input name="x_antiguedad3_neg" type="text" class="texto_normal" id="x_antiguedad3_neg" onkeypress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_antiguedad3_neg) ?>" size="2" maxlength="2"/>
          (a&ntilde;os) </span></td>
      </tr>
      <tr>
        <td class="texto_normal">Tel.;</td>
        <td colspan="4"><input name="x_telefono3_neg" type="text" class="texto_normal" id="x_telefono3_neg" value="<?php echo htmlspecialchars(@$x_telefono3_neg) ?>" size="20" maxlength="20" /></td>
      </tr>
      <tr>
        <td class="texto_normal">Ocupaci&oacute;n: </td>
        <td colspan="4"><span class="texto_normal">
          <input name="x_ocupacion" type="text" class="texto_normal" id="x_ocupacion" value="<?php echo htmlspecialchars(@$x_ocupacion) ?>" size="30" maxlength="150" />
          </span></td>
      </tr>
      <tr>
        <td class="texto_normal">&nbsp;</td>
        <td colspan="4">&nbsp;</td>
      </tr>
    </table>      <!-- 	</div>	-->	</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" bgcolor="#FFE6E6"><div align="center" class="texto_normal_bold">GarantÃ­as</div></td>
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
        <td width="165"><span class="texto_normal">DescripciÃ³n</span></td>
        <td width="84" class="texto_normal">&nbsp;</td>
        <td width="163" class="texto_normal">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3"><input name="x_garantia_desc" type="text" class="texto_normal" id="x_garantia_desc" value="<?php echo htmlentities(@$x_garantia_desc) ?>" size="115" maxlength="250"  /></td>
        </tr>
      <tr>
        <td><span class="texto_normal">Valor</span></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><input name="x_garantia_valor" type="text" class="texto_normal" id="x_garantia_valor" value="<?php echo htmlspecialchars(@$x_garantia_valor) ?>" size="20" maxlength="20" onkeypress="return solonumeros(this,event)" /></td>
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
    <td colspan="3" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Ingresos Mensuales Aval</td>
  </tr>
  <tr>
    <td colspan="3" align="center" valign="top" class="texto_normal_bold">&nbsp;</td>
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
    <td colspan="3" align="center" valign="top" class="texto_normal_bold">&nbsp;</td>
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
        <td><input class="importe" name="x_gastos_renta_negocio_aval" type="text" id="x_gastos_renta_negocio_aval" value="<?php echo htmlspecialchars(@$x_gastos_renta_negocio_aval) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/></td>
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
    <td colspan="3" class="texto_normal">Indique por lo menos una referencia de trabajo (Cliente Ã³ Proveedor) </td>
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
        <td width="84" class="texto_normal">TelÃ©fono</td>
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
      <td colspan="3" align="center" valign="middle" bgcolor="#FFE6E6"><span class="texto_normal_bold">Checklist Promotores</span></td>
    </tr>
    <tr>
      <td colspan="3" align="left" valign="top"><table width="700" border="0" cellspacing="0" cellpadding="0">
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
                <td width="38" align="center" valign="middle"><input type="checkbox" name="x_checklist_1" id="x_checklist_1" <?php if($x_checklist_1 == 1){echo "checked='checked'";}?> /></td>
                <td width="9">&nbsp;</td>
                <td width="229">VerificaciÃ³n del Negocio</td>
                <td width="10">&nbsp;</td>
                <td width="364"><input name="x_det_ck1" type="text" id="x_det_ck1" value="<?php echo $x_det_ck1; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input name="x_checklist_2" type="checkbox" id="x_checklist_2"  <?php if($x_checklist_2 == 1){echo "checked='checked'";}?>  /></td>
                <td>&nbsp;</td>
                <td>VerificaciÃ³n Domicilio</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck2" type="text" id="x_det_ck2" value="<?php echo $x_det_ck2; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_3" id="x_checklist_3" <?php if($x_checklist_3 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Firma Buro de CrÃ©dito</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck3" type="text" id="x_det_ck3" value="<?php echo $x_det_ck3; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_4" id="x_checklist_4" <?php if($x_checklist_4 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>IdentificaciÃ³n oficial</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck4" type="text" id="x_det_ck4" value="<?php echo $x_det_ck4; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_5" id="x_checklist_5" <?php if($x_checklist_5 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Comprobante de domicilio particular</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck5" type="text" id="x_det_ck5" value="<?php echo $x_det_ck5; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_6" id="x_checklist_6" <?php if($x_checklist_6 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Comprobante de domicilio de Negocio</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck6" type="text" id="x_det_ck6" value="<?php echo $x_det_ck6; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_7" id="x_checklist_7" <?php if($x_checklist_7 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Datos para estudio de capacidad de pago.</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck7" type="text" id="x_det_ck7" value="<?php echo $x_det_ck7; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_8" id="x_checklist_8" <?php if($x_checklist_8 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Datos garantÃ­a</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck8" type="text" id="x_det_ck8" value="<?php echo $x_det_ck8; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_9" id="x_checklist_9" <?php if($x_checklist_9 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Telefono(s)</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck9" type="text" id="x_det_ck9" value="<?php echo $x_det_ck9; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_10" id="x_checklist_10" <?php if($x_checklist_10 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Referencias</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck10" type="text" id="x_det_ck10" value="<?php echo $x_det_ck10; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_11" id="x_checklist_11" <?php if($x_checklist_11 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Foto Titular</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck11" type="text" id="x_det_ck11" value="<?php echo $x_det_ck11; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_12" id="x_checklist_12" <?php if($x_checklist_12 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Foto Domicilio</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck12" type="text" id="x_det_ck12" value="<?php echo $x_det_ck12; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_13" id="x_checklist_13" <?php if($x_checklist_13 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Foto Negocio</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck13" type="text" id="x_det_ck13" value="<?php echo $x_det_ck13; ?>" size="60" /></td>
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
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_14" id="x_checklist_14" <?php if($x_checklist_14 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>VerificaciÃ³n del Negocio</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck14" type="text" id="x_det_ck14" value="<?php echo $x_det_ck14; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_15" id="x_checklist_15" <?php if($x_checklist_15 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>VerificaciÃ³n Domicilio</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck15" type="text" id="x_det_ck15" value="<?php echo $x_det_ck15; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_16" id="x_checklist_16" <?php if($x_checklist_16 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Firma Buro de CrÃ©dito</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck16" type="text" id="x_det_ck16" value="<?php echo $x_det_ck16; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_17" id="x_checklist_17" <?php if($x_checklist_17 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>IdentificaciÃ³n oficial</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck17" type="text" id="x_det_ck17" value="<?php echo $x_det_ck17; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_18" id="x_checklist_18" <?php if($x_checklist_18 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Comprobante de domicilio Particular</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck18" type="text" id="x_det_ck18" value="<?php echo $x_det_ck18; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_19" id="x_checklist_19" <?php if($x_checklist_19 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Comprobante de domicilio de Negocio</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck19" type="text" id="x_det_ck19" value="<?php echo $x_det_ck19; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_20" id="x_checklist_20" <?php if($x_checklist_20 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Datos para estudio de capacidad de pago.</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck20" type="text" id="x_det_ck20" value="<?php echo $x_det_ck20; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_21" id="x_checklist_21" <?php if($x_checklist_21 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Telefono(s)</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck21" type="text" id="x_det_ck21" value="<?php echo $x_det_ck21; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_22" id="x_checklist_22" <?php if($x_checklist_22 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Referencias</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck22" type="text" id="x_det_ck22" value="<?php echo $x_det_ck22; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_23" id="x_checklist_23" <?php if($x_checklist_23 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Foto Aval</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck23" type="text" id="x_det_ck23" value="<?php echo $x_det_ck23; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_24" id="x_checklist_24" <?php if($x_checklist_24 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Foto Domicilio</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck24" type="text" id="x_det_ck24" value="<?php echo $x_det_ck24; ?>" size="60" /></td>
              </tr>
              <tr>
                <td align="center" valign="middle"><input type="checkbox" name="x_checklist_25" id="x_checklist_25" <?php if($x_checklist_25 == 1){echo "checked='checked'";}?> /></td>
                <td>&nbsp;</td>
                <td>Foto Negocio</td>
                <td>&nbsp;</td>
                <td><input name="x_det_ck25" type="text" id="x_det_ck25" value="<?php echo $x_det_ck25; ?>" size="60" /></td>
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
          </div></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" align="center" valign="middle" bgcolor="#FFE6E6"><?php if($_SESSION["crm_UserRolID"] < 4){ ?>
            Checklist Coordinador de CrÃ©ditos
            <?php } ?></td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>
          
          <?php if($_SESSION["crm_UserRolID"] < 4){ ?>
          
          
          <table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td align="center" valign="middle">&nbsp;</td>
              <td>&nbsp;</td>
              <td><strong>Revision de Datos</strong></td>
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
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_32" id="x_checklist_32" <?php if($x_checklist_32 == 1){echo "checked='checked'";}?> /></td>
              <td>&nbsp;</td>
              <td>Historial Crediticio</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck32" type="text" id="x_det_ck32" value="<?php echo $x_det_ck32; ?>" size="60" /></td>
            </tr>
            <tr>
              <td width="38" align="center" valign="middle"><input type="checkbox" name="x_checklist_26" id="x_checklist_26" <?php if($x_checklist_26 == 1){echo "checked='checked'";}?> /></td>
              <td width="9">&nbsp;</td>
              <td width="229">Validacion de datos del arrendador</td>
              <td width="10">&nbsp;</td>
              <td width="364"><input name="x_det_ck26" type="text" id="x_det_ck26" value="<?php echo $x_det_ck26; ?>" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input name="x_checklist_27" type="checkbox" id="x_checklist_27"  <?php if($x_checklist_27 == 1){echo "checked='checked'";}?>  /></td>
              <td>&nbsp;</td>
              <td>Validacion de datos del aval</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck27" type="text" id="x_det_ck27" value="<?php echo $x_det_ck27; ?>" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_28" id="x_checklist_28" <?php if($x_checklist_28 == 1){echo "checked='checked'";}?> /></td>
              <td>&nbsp;</td>
              <td>Validacion de datos titular</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck28" type="text" id="x_det_ck28" value="<?php echo $x_det_ck28; ?>" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_29" id="x_checklist_29" <?php if($x_checklist_29 == 1){echo "checked='checked'";}?> /></td>
              <td>&nbsp;</td>
              <td>Validacion de referencias titular</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck29" type="text" id="x_det_ck29" value="<?php echo $x_det_ck29; ?>" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_30" id="x_checklist_30" <?php if($x_checklist_30 == 1){echo "checked='checked'";}?> /></td>
              <td>&nbsp;</td>
              <td>Validacion referencias aval</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck30" type="text" id="x_det_ck30" value="<?php echo $x_det_ck30; ?>" size="60" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="checkbox" name="x_checklist_31" id="x_checklist_31" <?php if($x_checklist_31 == 1){echo "checked='checked'";}?> /></td>
              <td>&nbsp;</td>
              <td>Opinion de Capacidad de Pago</td>
              <td>&nbsp;</td>
              <td><input name="x_det_ck31" type="text" id="x_det_ck31" value="<?php echo $x_det_ck31; ?>" size="60" /></td>
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
          
          <?php } ?>          
          
          </td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>
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
  			$x_ventas_menusuales = ($x_ingresos_negocio != "") ? $x_ingresos_negocio : 0;
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

			echo "<b>
			Capacidad de Pago Mensual AVAL: ".FormatNumber(@$x_cpm,0,2,0,1)."<br>
			Capacidad de Pago Semanal AVAL: ".FormatNumber(@$x_cps,0,2,0,1)."		
			</b>
			";


















		  } ?>
          </td>
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
    <td><div align="center">
	<input name="Action" type="button"class="boton_medium" value="Editar" onclick="EW_checkMyForm();" />
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
		$GLOBALS["x_actividad_id"] = $row["actividad_id"];
		$GLOBALS["x_actividad_desc"] = $row["actividad_desc"];				



//CLIENTE

		$sSql = "select cliente.* from cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id where solicitud_cliente.solicitud_id = $x_solicitud_id";
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

				
		$sSql = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where cliente_id = ".$GLOBALS["x_cliente_id"]." and direccion_tipo_id = 1 order by direccion_id desc limit 1";
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
		on delegacion.delegacion_id = direccion.delegacion_id where cliente_id = ".$GLOBALS["x_cliente_id"]." and direccion_tipo_id = 2 order by direccion_id desc limit 1";
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


		$sSql = "select * from garantia where solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rs7 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row7 = phpmkr_fetch_array($rs7);
		$GLOBALS["x_garantia_id"] = $row7["garantia_id"];		
		$GLOBALS["x_garantia_desc"] = $row7["descripcion"];
		$GLOBALS["x_garantia_valor"] = $row7["valor"];		

		
		
		
		
		/*$GLOBALS["x_ingreso_negocio"] = $row8["ingreso_negocio"];
		$GLOBALS["x_inv_neg_fija_conc_1"] = $row8["inv_neg_fija_conc_1"];
		$GLOBALS["x_inv_neg_fija_conc_2"] = $row8["inv_neg_fija_conc_2"];
		$GLOBALS["x_inv_neg_fija_conc_3"] = $row8["inv_neg_fija_conc_3"];	
		$GLOBALS["x_parentesco_tipo_id_ing_1"] = $row8["parentesco_tipo_id"];
		$GLOBALS["x_ingresos_familiar_2"] = $row8["ing_fam_2"];
		$GLOBALS["x_parentesco_tipo_id_ing_2"] = $row8["parentesco_tipo_id2"];
		$GLOBALS["x_otros_ingresos"] = $row8["otros_ingresos"];
		$GLOBALS["x_origen_ingresos"] = $row8["origen_ingresos"];		
		$GLOBALS["x_origen_ingresos2"] = $row8["origen_ingresos_fam_1"];				
		$GLOBALS["x_origen_ingresos3"] = $row8["origen_ingresos_fam_2"];*/												


		$sSql = "select * from gasto where solicitud_id = ".$GLOBALS["x_solicitud_id"];
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
		$sSql = "select * from referencia where solicitud_id = ".$GLOBALS["x_solicitud_id"]." order by referencia_id";
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

function EditData($conn)
{

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

		$theValue = ($GLOBALS["x_tit_fecha_nac"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_tit_fecha_nac"]) . "'" : "Null";
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
	
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_piesto"]) : $GLOBALS["x_puesto"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`puesto`"] = $theValue;
	
		// Field fecha_registro
		$theValue = ($GLOBALS["x_fecha_contratacion"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_contratacion"]) . "'" : "Null";
		$fieldList["`fecha_contratacion`"] = $theValue;
	
		$theValue = ($GLOBALS["x_salario_mensual"] != "") ? " '" . doubleval($GLOBALS["x_salario_mensual"]) . "'" : "NULL";
		$fieldList["`salario_mensual`"] = $theValue;

		// Field promotor_id
		$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
		$fieldList["`promotor_id`"] = $theValue;

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
//AVAL

		if($GLOBALS["x_aval_id"] > 0){
	
			$fieldList = NULL;
			
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_completo_aval"]) : $GLOBALS["x_nombre_completo_aval"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`nombre_completo`"] = $theValue;


			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_paterno_aval"]) : $GLOBALS["x_apellido_paterno_aval"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`apellido_paterno`"] = $theValue;
	
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_materno_aval"]) : $GLOBALS["x_apellido_materno_aval"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`apellido_materno`"] = $theValue;
			
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_aval_rfc"]) : $GLOBALS["x_aval_rfc"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`rfc`"] = $theValue;
		
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_aval_curp"]) : $GLOBALS["x_aval_curp"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`curp`"] = $theValue;
			
			$theValue = ($GLOBALS["x_parentesco_tipo_id_aval"] != "") ? intval($GLOBALS["x_parentesco_tipo_id_aval"]) : "0";
			$fieldList["`parentesco_tipo_id`"] = $theValue;


			// Field tipo_negocio
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tipo_negocio_aval"]) : $GLOBALS["x_tipo_negocio_aval"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`tipo_negocio`"] = $theValue;
		
		
			// Field fecha_registro
			$theValue = ($GLOBALS["x_tit_fecha_nac_aval"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_tit_fecha_nac_aval"]) . "'" : "Null";
			$fieldList["`fecha_nac`"] = $theValue;
		
		
			// Field edad
			$theValue = ($GLOBALS["x_edad_aval"] != "") ? intval($GLOBALS["x_edad_aval"]) : "0";
			$fieldList["`edad`"] = $theValue;
		
			// Field sexo
			$theValue = ($GLOBALS["x_sexo_aval"] != "") ? intval($GLOBALS["x_sexo_aval"]) : "0";
			$fieldList["`sexo`"] = $theValue;
		
			// Field estado_civil_id
			$theValue = ($GLOBALS["x_estado_civil_id_aval"] != "") ? intval($GLOBALS["x_estado_civil_id_aval"]) : "0";
			$fieldList["`estado_civil_id`"] = $theValue;
		
			// Field numero_hijos
			$theValue = ($GLOBALS["x_numero_hijos_aval"] != "") ? intval($GLOBALS["x_numero_hijos_aval"]) : "0";
			$fieldList["`numero_hijos`"] = $theValue;
		
		
			// Field numero_hijos_dep
			$theValue = ($GLOBALS["x_numero_hijos_dep_aval"] != "") ? intval($GLOBALS["x_numero_hijos_dep_aval"]) : "0";
			$fieldList["`numero_hijos_dep`"] = $theValue;
		
			// Field nombre_conyuge
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_conyuge_aval"]) : $GLOBALS["x_nombre_conyuge_aval"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`nombre_conyuge`"] = $theValue;
		
			// Field nombre_conyuge
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_email_aval"]) : $GLOBALS["x_email_aval"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`email`"] = $theValue;
		
		
			$theValue = ($GLOBALS["x_nacionalidad_id_aval"] != "") ? intval($GLOBALS["x_nacionalidad_id_aval"]) : "0";
			$fieldList["`nacionalidad_id`"] = $theValue;



			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono3"]) : $GLOBALS["x_telefono3"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`telefono`"] = $theValue;
			
			$theValue = ($GLOBALS["x_ingresos_mensuales"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_mensuales"]) . "'" : "NULL";
			$fieldList["`ingresos_mensuales`"] = $theValue;
			
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ocupacion"]) : $GLOBALS["x_ocupacion"];
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`ocupacion`"] = $theValue;
	
			// update
			$sSql = "UPDATE `aval` SET ";
			foreach ($fieldList as $key=>$temp) {
				$sSql .= "$key = $temp, ";
			}
			if (substr($sSql, -2) == ", ") {
				$sSql = substr($sSql, 0, strlen($sSql)-2);
			}
			$sSql .= " WHERE aval_id = " . $GLOBALS["x_aval_id"];
			$x_result = phpmkr_query($sSql,$conn);

			if(!$x_result){
				echo phpmkr_error() . '<br>SQL: ' . $sSql;
				phpmkr_query('rollback;', $conn);	 
				exit();
			}


			if($GLOBALS["x_direccion_id3"] > 0) {

				$fieldList = NULL;
		
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle3"]) : $GLOBALS["x_calle3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`calle`"] = $theValue;
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia3"]) : $GLOBALS["x_colonia3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`colonia`"] = $theValue;
				$theValue = ($GLOBALS["x_delegacion_id3"] != "") ? intval($GLOBALS["x_delegacion_id3"]) : "0";
				$fieldList["`delegacion_id`"] = $theValue;
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_propietario2"]) : $GLOBALS["x_propietario2"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`propietario`"] = $theValue;
	
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_codigo_postal3"]) : $GLOBALS["x_codigo_postal3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`codigo_postal`"] = $theValue;
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion3"]) : $GLOBALS["x_ubicacion3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`ubicacion`"] = $theValue;
				$theValue = ($GLOBALS["x_antiguedad3"] != "") ? intval($GLOBALS["x_antiguedad3"]) : "0";
				$fieldList["`antiguedad`"] = $theValue;
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono3"]) : $GLOBALS["x_telefono3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono`"] = $theValue;

				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono3_sec"]) : $GLOBALS["x_telefono3_sec"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono_movil`"] = $theValue;
				
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_secundario3"]) : $GLOBALS["x_telefono_secundario3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono_secundario`"] = $theValue;
				$theValue = ($GLOBALS["x_vivienda_tipo_id2"] != "") ? intval($GLOBALS["x_vivienda_tipo_id2"]) : "0";
				$fieldList["`vivienda_tipo_id`"] = $theValue;
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otro_tipo_vivienda2"]) : $GLOBALS["x_otro_tipo_vivienda2"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`otro_tipo_vivienda`"] = $theValue;
		
				// update
				$sSql = "UPDATE `direccion` SET ";
				foreach ($fieldList as $key=>$temp) {
					$sSql .= "$key = $temp, ";
				}
				if (substr($sSql, -2) == ", ") {
					$sSql = substr($sSql, 0, strlen($sSql)-2);
				}
				$sSql .= " WHERE direccion_id = " . $GLOBALS["x_direccion_id3"];
				$x_result = phpmkr_query($sSql,$conn);
	
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL aval par: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
			}else{

				if($GLOBALS["x_calle3"] != ""){

					$fieldList = NULL;
					// Field cliente_id
				//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
					$fieldList["`cliente_id`"] = 0;
				
					// Field aval_id
				//	$theValue = ($GLOBALS["x_aval_id"] != "") ? intval($GLOBALS["x_aval_id"]) : "NULL";
					$fieldList["`aval_id`"] = $GLOBALS["x_aval_id"];
				
					// Field promotor_id
				//	$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
					$fieldList["`promotor_id`"] = 0;
				
					// Field direccion_tipo_id
				//	$theValue = ($GLOBALS["x_direccion_tipo_id"] != "") ? intval($GLOBALS["x_direccion_tipo_id"]) : "NULL";
					$fieldList["`direccion_tipo_id`"] = 3;
				
					// Field calle
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle3"]) : $GLOBALS["x_calle3"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`calle`"] = $theValue;

					// Field colonia
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia3"]) : $GLOBALS["x_colonia3"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`colonia`"] = $theValue;
				
					// Field delegacion_id
					$theValue = ($GLOBALS["x_delegacion_id3"] != "") ? intval($GLOBALS["x_delegacion_id3"]) : "0";
					$fieldList["`delegacion_id`"] = $theValue;
				
					// Field propietario
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_propietario2"]) : $GLOBALS["x_propietario2"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`propietario`"] = $theValue;
				
					// Field codigo_postal
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_codigo_postal3"]) : $GLOBALS["x_codigo_postal3"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`codigo_postal`"] = $theValue;
				
					// Field ubicacion
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion3"]) : $GLOBALS["x_ubicacion3"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`ubicacion`"] = $theValue;
				
					// Field antiguedad
					$theValue = ($GLOBALS["x_antiguedad3"] != "") ? intval($GLOBALS["x_antiguedad3"]) : "0";
					$fieldList["`antiguedad`"] = $theValue;
				
					// Field vivienda_tipo_id
					$theValue = ($GLOBALS["x_vivienda_tipo_id2"] != "") ? intval($GLOBALS["x_vivienda_tipo_id2"]) : "0";
					$fieldList["`vivienda_tipo_id`"] = $theValue;
				
					// Field otro_tipo_vivienda
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otro_tipo_vivienda3"]) : $GLOBALS["x_otro_tipo_vivienda3"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`otro_tipo_vivienda`"] = $theValue;
				
					// Field telefono
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono3"]) : $GLOBALS["x_telefono3"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`telefono`"] = $theValue;

					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono3_sec"]) : $GLOBALS["x_telefono3_sec"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`telefono_movil`"] = $theValue;
				
					// Field telefono_secundario
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_secundario3"]) : $GLOBALS["x_telefono_secundario3"]; 
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


//DIR NEG AVAL

			if($GLOBALS["x_direccion_id4"] > 0) {
				$fieldList = NULL;
		
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle3_neg"]) : $GLOBALS["x_calle3_neg"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`calle`"] = $theValue;
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia3_neg"]) : $GLOBALS["x_colonia3_neg"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`colonia`"] = $theValue;
				$theValue = ($GLOBALS["x_delegacion_id3_neg"] != "") ? intval($GLOBALS["x_delegacion_id3_neg"]) : "0";
				$fieldList["`delegacion_id`"] = $theValue;
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_propietario2_neg"]) : $GLOBALS["x_propietario2_neg"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`propietario`"] = $theValue;
	/*			
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_entidad3"]) : $GLOBALS["x_entidad3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`entidad`"] = $theValue;
	*/			
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_codigo_postal3_neg"]) : $GLOBALS["x_codigo_postal3_neg"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`codigo_postal`"] = $theValue;
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion3_neg"]) : $GLOBALS["x_ubicacion3_neg"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`ubicacion`"] = $theValue;
				$theValue = ($GLOBALS["x_antiguedad3"] != "") ? intval($GLOBALS["x_antiguedad3_neg"]) : "0";
				$fieldList["`antiguedad`"] = $theValue;
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono3_neg"]) : $GLOBALS["x_telefono3_neg"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono`"] = $theValue;
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_secundario3_neg"]) : $GLOBALS["x_telefono_secundario3_neg"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono_secundario`"] = $theValue;
				$theValue = ($GLOBALS["x_vivienda_tipo_id2"] != "") ? intval($GLOBALS["x_vivienda_tipo_id2_neg"]) : "0";
				$fieldList["`vivienda_tipo_id`"] = $theValue;
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otro_tipo_vivienda2"]) : $GLOBALS["x_otro_tipo_vivienda2_neg"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`otro_tipo_vivienda`"] = $theValue;
		
				// update
				$sSql = "UPDATE `direccion` SET ";
				foreach ($fieldList as $key=>$temp) {
					$sSql .= "$key = $temp, ";
				}
				if (substr($sSql, -2) == ", ") {
					$sSql = substr($sSql, 0, strlen($sSql)-2);
				}
				$sSql .= " WHERE direccion_id = " . $GLOBALS["x_direccion_id4"];
				$x_result = phpmkr_query($sSql,$conn);
	
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL aval neg: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}

			}else{

				if($GLOBALS["x_calle3_neg"] != ""){

					$fieldList = NULL;
					// Field cliente_id
				//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
					$fieldList["`cliente_id`"] = 0;
				
					// Field aval_id
				//	$theValue = ($GLOBALS["x_aval_id"] != "") ? intval($GLOBALS["x_aval_id"]) : "NULL";
					$fieldList["`aval_id`"] = $GLOBALS["x_aval_id"];
				
					// Field promotor_id
				//	$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
					$fieldList["`promotor_id`"] = 0;
				
					// Field direccion_tipo_id
				//	$theValue = ($GLOBALS["x_direccion_tipo_id"] != "") ? intval($GLOBALS["x_direccion_tipo_id"]) : "NULL";
					$fieldList["`direccion_tipo_id`"] = 4;
				
					// Field calle
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle3_neg"]) : $GLOBALS["x_calle3_neg"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`calle`"] = $theValue;
				
					// Field colonia
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia3_neg"]) : $GLOBALS["x_colonia3_neg"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`colonia`"] = $theValue;
				
					// Field delegacion_id
					$theValue = ($GLOBALS["x_delegacion_id3_neg"] != "") ? intval($GLOBALS["x_delegacion_id3_neg"]) : "0";
					$fieldList["`delegacion_id`"] = $theValue;
				
					// Field propietario

					// Field codigo_postal
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_codigo_postal3_neg"]) : $GLOBALS["x_codigo_postal3_neg"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`codigo_postal`"] = $theValue;
				
					// Field ubicacion
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion3_neg"]) : $GLOBALS["x_ubicacion3_neg"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`ubicacion`"] = $theValue;
				
					// Field antiguedad
					$theValue = ($GLOBALS["x_antiguedad3_neg"] != "") ? intval($GLOBALS["x_antiguedad3_neg"]) : "0";
					$fieldList["`antiguedad`"] = $theValue;
				
					// Field vivienda_tipo_id
					$theValue = ($GLOBALS["x_vivienda_tipo_id2_neg"] != "") ? intval($GLOBALS["x_vivienda_tipo_id2_neg"]) : "0";
					$fieldList["`vivienda_tipo_id`"] = $theValue;
				
					// Field otro_tipo_vivienda
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_propietario3_neg"]) : $GLOBALS["x_propietario3_neg"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`propietario`"] = $theValue;

					// Field telefono
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono3_neg"]) : $GLOBALS["x_telefono3_neg"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`telefono`"] = $theValue;
				
					// Field telefono_secundario
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_secundario3_neg"]) : $GLOBALS["x_telefono_secundario3_neg"]; 
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


			//ING AVAL
			if($GLOBALS["x_ingreso_aval_id"] > 0){			
				$fieldList = NULL;

				// Field ingresos_negocio
				$theValue = ($GLOBALS["x_ingresos_mensuales"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_mensuales"]) . "'" : "0";
				$fieldList["`ingresos_negocio`"] = $theValue;
			
				// Field ingresos_familiar_1
				$theValue = ($GLOBALS["x_ingresos_familiar_1_aval"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_familiar_1_aval"]) . "'" : "0";
				$fieldList["`ingresos_familiar_1`"] = $theValue;
			
				// Field parentesco_tipo_id
				$theValue = ($GLOBALS["x_parentesco_tipo_id_ing_1_aval"] != "") ? intval($GLOBALS["x_parentesco_tipo_id_ing_1_aval"]) : "0";
				$fieldList["`parentesco_tipo_id`"] = $theValue;
			
				// Field ingresos_familiar_2
				$theValue = ($GLOBALS["x_ingresos_familiar_2_aval"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_familiar_2_aval"]) . "'" : "NULL";
				$fieldList["`ingresos_familiar_2`"] = 0;
			
				// Field parentesco_tipo_id2
				$theValue = ($GLOBALS["x_parentesco_tipo_id_ing_2_aval"] != "") ? intval($GLOBALS["x_parentesco_tipo_id_ing_2_aval"]) : "0";
				$fieldList["`parentesco_tipo_id2`"] = 0;
			
				// Field otros_ingresos
				$theValue = ($GLOBALS["x_otros_ingresos_aval"] != "") ? " '" . doubleval($GLOBALS["x_otros_ingresos_aval"]) . "'" : "0";
				$fieldList["`otros_ingresos`"] = $theValue;
			
				$theValue = ($GLOBALS["x_origen_ingresos_aval"] != "") ? " '" . $GLOBALS["x_origen_ingresos_aval"] . "'" : "NULL";
				$fieldList["`origen_ingresos`"] = $theValue;
			
				$theValue = ($GLOBALS["x_origen_ingresos_aval2"] != "") ? " '" . $GLOBALS["x_origen_ingresos_aval2"] . "'" : "NULL";
				$fieldList["`origen_ingresos_fam_1`"] = $theValue;
			
				$theValue = ($GLOBALS["x_origen_ingresos_aval3"] != "") ? " '" . $GLOBALS["x_origen_ingresos_aval3"] . "'" : "NULL";
				$fieldList["`origen_ingresos_fam_2`"] = $theValue;


				// update
				$sSql = "UPDATE `ingreso_aval` SET ";
				foreach ($fieldList as $key=>$temp) {
					$sSql .= "$key = $temp, ";
				}
				if (substr($sSql, -2) == ", ") {
					$sSql = substr($sSql, 0, strlen($sSql)-2);
				}
				$sSql .= " WHERE aval_id = " . $GLOBALS["x_aval_id"];
				$x_result = phpmkr_query($sSql,$conn);
		
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
			}else{

				//Ingresos AVAL
				$fieldList = NULL;
				// Field cliente_id
			//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
				$fieldList["`aval_id`"] = $GLOBALS["x_aval_id"];
			
				// Field ingresos_negocio
				$theValue = ($GLOBALS["x_ingresos_mensuales"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_mensuales"]) . "'" : "0";
				$fieldList["`ingresos_negocio`"] = $theValue;
			
				// Field ingresos_familiar_1
				$theValue = ($GLOBALS["x_ingresos_familiar_1_aval"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_familiar_1_aval"]) . "'" : "0";
				$fieldList["`ingresos_familiar_1`"] = $theValue;
			
				// Field parentesco_tipo_id
				$theValue = ($GLOBALS["x_parentesco_tipo_id_ing_1_aval"] != "") ? intval($GLOBALS["x_parentesco_tipo_id_ing_1_aval"]) : "0";
				$fieldList["`parentesco_tipo_id`"] = $theValue;
			
				// Field ingresos_familiar_2
				$theValue = ($GLOBALS["x_ingresos_familiar_2_aval"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_familiar_2_aval"]) . "'" : "NULL";
				$fieldList["`ingresos_familiar_2`"] = 0;
			
				// Field parentesco_tipo_id2
				$theValue = ($GLOBALS["x_parentesco_tipo_id_ing_2_aval"] != "") ? intval($GLOBALS["x_parentesco_tipo_id_ing_2_aval"]) : "0";
				$fieldList["`parentesco_tipo_id2`"] = 0;
			
				// Field otros_ingresos
				$theValue = ($GLOBALS["x_otros_ingresos_aval"] != "") ? " '" . doubleval($GLOBALS["x_otros_ingresos_aval"]) . "'" : "0";
				$fieldList["`otros_ingresos`"] = $theValue;
			
				$theValue = ($GLOBALS["x_origen_ingresos_aval"] != "") ? " '" . $GLOBALS["x_origen_ingresos_aval"] . "'" : "NULL";
				$fieldList["`origen_ingresos`"] = $theValue;
			
				$theValue = ($GLOBALS["x_origen_ingresos_aval2"] != "") ? " '" . $GLOBALS["x_origen_ingresos_aval2"] . "'" : "NULL";
				$fieldList["`origen_ingresos_fam_1`"] = $theValue;
			
				$theValue = ($GLOBALS["x_origen_ingresos_aval3"] != "") ? " '" . $GLOBALS["x_origen_ingresos_aval3"] . "'" : "NULL";
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
			
			}
			
			
			//gastos aval
			if($GLOBALS["x_gasto_aval_id"] > 0){
				$fieldList = NULL;
				$fieldList["`aval_id`"] = $GLOBALS["x_aval_id"];
			
				$theValue = ($GLOBALS["x_gastos_prov1_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_prov1_aval"]) . "'" : "NULL";
				$fieldList["`gastos_prov1`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_prov2_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_prov2_aval"]) . "'" : "NULL";
				$fieldList["`gastos_prov2`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_prov3_aval"] != "") ? doubleval($GLOBALS["x_gastos_prov3_aval"]) : "NULL";
				$fieldList["`gastos_prov3`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_otro_prov_aval"] != "") ? doubleval($GLOBALS["x_otro_prov_aval"]) : "NULL";
				$fieldList["`otro_prov`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_empleados_aval"] != "") ? doubleval($GLOBALS["x_gastos_empleados_aval"]) : "NULL";
				$fieldList["`gastos_empleados`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_renta_negocio_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_renta_negocio_aval"]) . "'" : "NULL";
				$fieldList["`gastos_renta_negocio`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_renta_casa2"] != "") ? doubleval($GLOBALS["x_gastos_renta_casa2"]) : "0";
				$fieldList["`gastos_renta_casa`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_credito_hipotecario_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_credito_hipotecario_aval"]) . "'" : "NULL";
				$fieldList["`gastos_credito_hipotecario`"] = $theValue;
			
				$theValue = ($GLOBALS["x_gastos_otros_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_otros_aval"]) . "'" : "NULL";
				$fieldList["`gastos_otros`"] = $theValue;
			
			
				// update
				$sSql = "UPDATE `gasto_aval` SET ";
				foreach ($fieldList as $key=>$temp) {
					$sSql .= "$key = $temp, ";
				}
				if (substr($sSql, -2) == ", ") {
					$sSql = substr($sSql, 0, strlen($sSql)-2);
				}
				$sSql .= " WHERE aval_id = " . $GLOBALS["x_aval_id"];
				$x_result = phpmkr_query($sSql,$conn);
		
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
			
			}else{
				$fieldList = NULL;
				$fieldList["`aval_id`"] = $GLOBALS["x_aval_id"];
			
				$theValue = ($GLOBALS["x_gastos_prov1_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_prov1_aval"]) . "'" : "NULL";
				$fieldList["`gastos_prov1`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_prov2_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_prov2_aval"]) . "'" : "NULL";
				$fieldList["`gastos_prov2`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_prov3_aval"] != "") ? doubleval($GLOBALS["x_gastos_prov3_aval"]) : "NULL";
				$fieldList["`gastos_prov3`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_otro_prov_aval"] != "") ? doubleval($GLOBALS["x_otro_prov_aval"]) : "NULL";
				$fieldList["`otro_prov`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_empleados_aval"] != "") ? doubleval($GLOBALS["x_gastos_empleados_aval"]) : "NULL";
				$fieldList["`gastos_empleados`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_renta_negocio_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_renta_negocio_aval"]) . "'" : "NULL";
				$fieldList["`gastos_renta_negocio`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_renta_casa2"] != "") ? doubleval($GLOBALS["x_gastos_renta_casa2"]) : "0";
				$fieldList["`gastos_renta_casa`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_credito_hipotecario_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_credito_hipotecario_aval"]) . "'" : "NULL";
				$fieldList["`gastos_credito_hipotecario`"] = $theValue;
			
				$theValue = ($GLOBALS["x_gastos_otros_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_otros_aval"]) . "'" : "NULL";
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

			}


		}else{
		

			if($GLOBALS["x_nombre_completo_aval"] != ""){

				$fieldList = NULL;
				// Field cliente_id
			//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
				$fieldList["`solicitud_id`"] = $GLOBALS["x_solicitud_id"];
			
				// Field nombre_completo
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_completo_aval"]) : $GLOBALS["x_nombre_completo_aval"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`nombre_completo`"] = $theValue;

				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_paterno_aval"]) : $GLOBALS["x_apellido_paterno_aval"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`apellido_paterno`"] = $theValue;
		
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_materno_aval"]) : $GLOBALS["x_apellido_materno_aval"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`apellido_materno`"] = $theValue;

				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_aval_rfc"]) : $GLOBALS["x_aval_rfc"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`rfc`"] = $theValue;
			
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_aval_curp"]) : $GLOBALS["x_aval_curp"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`curp`"] = $theValue;
			
				// Field parentesco_tipo_id
				$theValue = ($GLOBALS["x_parentesco_tipo_id_aval"] != "") ? intval($GLOBALS["x_parentesco_tipo_id_aval"]) : "0";
				$fieldList["`parentesco_tipo_id`"] = $theValue;


	
				// Field tipo_negocio
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tipo_negocio_aval"]) : $GLOBALS["x_tipo_negocio_aval"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`tipo_negocio`"] = $theValue;
			
			
				// Field fecha_registro
				$theValue = ($GLOBALS["x_tit_fecha_nac_aval"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_tit_fecha_nac_aval"]) . "'" : "Null";
				$fieldList["`fecha_nac`"] = $theValue;
			
			
				// Field edad
				$theValue = ($GLOBALS["x_edad_aval"] != "") ? intval($GLOBALS["x_edad_aval"]) : "0";
				$fieldList["`edad`"] = $theValue;
			
				// Field sexo
				$theValue = ($GLOBALS["x_sexo_aval"] != "") ? intval($GLOBALS["x_sexo_aval"]) : "0";
				$fieldList["`sexo`"] = $theValue;
			
				// Field estado_civil_id
				$theValue = ($GLOBALS["x_estado_civil_id_aval"] != "") ? intval($GLOBALS["x_estado_civil_id_aval"]) : "0";
				$fieldList["`estado_civil_id`"] = $theValue;
			
				// Field numero_hijos
				$theValue = ($GLOBALS["x_numero_hijos_aval"] != "") ? intval($GLOBALS["x_numero_hijos_aval"]) : "0";
				$fieldList["`numero_hijos`"] = $theValue;
			
			
				// Field numero_hijos_dep
				$theValue = ($GLOBALS["x_numero_hijos_dep_aval"] != "") ? intval($GLOBALS["x_numero_hijos_dep_aval"]) : "0";
				$fieldList["`numero_hijos_dep`"] = $theValue;
			
				// Field nombre_conyuge
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_conyuge_aval"]) : $GLOBALS["x_nombre_conyuge_aval"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`nombre_conyuge`"] = $theValue;
			
				// Field nombre_conyuge
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_email_aval"]) : $GLOBALS["x_email_aval"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`email`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_nacionalidad_id_aval"] != "") ? intval($GLOBALS["x_nacionalidad_id_aval"]) : "0";
				$fieldList["`nacionalidad_id`"] = $theValue;


				// Field telefono
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono3"]) : $GLOBALS["x_telefono3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono`"] = $theValue;
			
				// Field ingresos_mensuales
				$theValue = ($GLOBALS["x_ingresos_mensuales"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_mensuales"]) . "'" : "NULL";
				$fieldList["`ingresos_mensuales`"] = $theValue;
			
				// Field ocupacion
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ocupacion"]) : $GLOBALS["x_ocupacion"]; 
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
			//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
				$fieldList["`cliente_id`"] = 0;
			
				// Field aval_id
			//	$theValue = ($GLOBALS["x_aval_id"] != "") ? intval($GLOBALS["x_aval_id"]) : "NULL";
				$fieldList["`aval_id`"] = $x_aval_id;
			
				// Field promotor_id
			//	$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
				$fieldList["`promotor_id`"] = 0;
			
				// Field direccion_tipo_id
			//	$theValue = ($GLOBALS["x_direccion_tipo_id"] != "") ? intval($GLOBALS["x_direccion_tipo_id"]) : "NULL";
				$fieldList["`direccion_tipo_id`"] = 3;
			
				// Field calle
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle3"]) : $GLOBALS["x_calle3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`calle`"] = $theValue;
			
				// Field colonia
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia3"]) : $GLOBALS["x_colonia3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`colonia`"] = $theValue;
			
				// Field delegacion_id
				$theValue = ($GLOBALS["x_delegacion_id3"] != "") ? intval($GLOBALS["x_delegacion_id3"]) : "0";
				$fieldList["`delegacion_id`"] = $theValue;
			
				// Field propietario
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_propietario2"]) : $GLOBALS["x_propietario2"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`propietario`"] = $theValue;
			
		/*
				// Field entidad
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_entidad3"]) : $GLOBALS["x_entidad3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`entidad`"] = $theValue;
		*/	
				// Field codigo_postal
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_codigo_postal3"]) : $GLOBALS["x_codigo_postal3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`codigo_postal`"] = $theValue;
			
				// Field ubicacion
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion3"]) : $GLOBALS["x_ubicacion3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`ubicacion`"] = $theValue;
			
				// Field antiguedad

				$theValue = ($GLOBALS["x_antiguedad3"] != "") ? intval($GLOBALS["x_antiguedad3"]) : "0";
				$fieldList["`antiguedad`"] = $theValue;
			
				// Field vivienda_tipo_id
				$theValue = ($GLOBALS["x_vivienda_tipo_id2"] != "") ? intval($GLOBALS["x_vivienda_tipo_id2"]) : "0";
				$fieldList["`vivienda_tipo_id`"] = $theValue;
			
				// Field otro_tipo_vivienda
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otro_tipo_vivienda3"]) : $GLOBALS["x_otro_tipo_vivienda3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`otro_tipo_vivienda`"] = $theValue;
			
				// Field telefono
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono3"]) : $GLOBALS["x_telefono3"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono`"] = $theValue;

				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono3_sec"]) : $GLOBALS["x_telefono3_sec"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono_movil`"] = $theValue;

				// Field telefono_secundario
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_secundario3"]) : $GLOBALS["x_telefono_secundario3"]; 
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
			//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
				$fieldList["`cliente_id`"] = 0;
			
				// Field aval_id
			//	$theValue = ($GLOBALS["x_aval_id"] != "") ? intval($GLOBALS["x_aval_id"]) : "NULL";
				$fieldList["`aval_id`"] = $x_aval_id;
			
				// Field promotor_id
			//	$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
				$fieldList["`promotor_id`"] = 0;
			
				// Field direccion_tipo_id
			//	$theValue = ($GLOBALS["x_direccion_tipo_id"] != "") ? intval($GLOBALS["x_direccion_tipo_id"]) : "NULL";
				$fieldList["`direccion_tipo_id`"] = 4;
			
				// Field calle
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle3_neg"]) : $GLOBALS["x_calle3_neg"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`calle`"] = $theValue;
			
				// Field colonia
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia3_neg"]) : $GLOBALS["x_colonia3_neg"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`colonia`"] = $theValue;
			
				// Field delegacion_id
				$theValue = ($GLOBALS["x_delegacion_id3_neg"] != "") ? intval($GLOBALS["x_delegacion_id3_neg"]) : "0";
				$fieldList["`delegacion_id`"] = $theValue;
			
				// Field codigo_postal
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_codigo_postal3_neg"]) : $GLOBALS["x_codigo_postal3_neg"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`codigo_postal`"] = $theValue;
			
				// Field ubicacion
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion3_neg"]) : $GLOBALS["x_ubicacion3_neg"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`ubicacion`"] = $theValue;
			
				// Field antiguedad
				$theValue = ($GLOBALS["x_antiguedad3_neg"] != "") ? intval($GLOBALS["x_antiguedad3_neg"]) : "0";
				$fieldList["`antiguedad`"] = $theValue;
			
				// Field vivienda_tipo_id
				$theValue = ($GLOBALS["x_vivienda_tipo_id2_neg"] != "") ? intval($GLOBALS["x_vivienda_tipo_id2_neg"]) : "0";
				$fieldList["`vivienda_tipo_id`"] = $theValue;
			
				// Field otro_tipo_vivienda
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_propietario3_neg"]) : $GLOBALS["x_propietario3_neg"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`propietario`"] = $theValue;
			
				// Field telefono
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono3_neg"]) : $GLOBALS["x_telefono3_neg"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`telefono`"] = $theValue;
			
				// Field telefono_secundario
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_secundario3_neg"]) : $GLOBALS["x_telefono_secundario3_neg"]; 
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
			//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
				$fieldList["`aval_id`"] = $x_aval_id;
			
				// Field ingresos_negocio
				$theValue = ($GLOBALS["x_ingresos_mensuales"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_mensuales"]) . "'" : "0";
				$fieldList["`ingresos_negocio`"] = $theValue;
			
				// Field ingresos_familiar_1
				$theValue = ($GLOBALS["x_ingresos_familiar_1_aval"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_familiar_1_aval"]) . "'" : "0";
				$fieldList["`ingresos_familiar_1`"] = $theValue;
			
				// Field parentesco_tipo_id
				$theValue = ($GLOBALS["x_parentesco_tipo_id_ing_1_aval"] != "") ? intval($GLOBALS["x_parentesco_tipo_id_ing_1_aval"]) : "0";
				$fieldList["`parentesco_tipo_id`"] = $theValue;
			
				// Field ingresos_familiar_2
				$theValue = ($GLOBALS["x_ingresos_familiar_2_aval"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_familiar_2_aval"]) . "'" : "NULL";
				$fieldList["`ingresos_familiar_2`"] = 0;
			
				// Field parentesco_tipo_id2
				$theValue = ($GLOBALS["x_parentesco_tipo_id_ing_2_aval"] != "") ? intval($GLOBALS["x_parentesco_tipo_id_ing_2_aval"]) : "0";
				$fieldList["`parentesco_tipo_id2`"] = 0;
			
				// Field otros_ingresos
				$theValue = ($GLOBALS["x_otros_ingresos_aval"] != "") ? " '" . doubleval($GLOBALS["x_otros_ingresos_aval"]) . "'" : "0";
				$fieldList["`otros_ingresos`"] = $theValue;
			
				$theValue = ($GLOBALS["x_origen_ingresos_aval"] != "") ? " '" . $GLOBALS["x_origen_ingresos_aval"] . "'" : "NULL";
				$fieldList["`origen_ingresos`"] = $theValue;
			
				$theValue = ($GLOBALS["x_origen_ingresos_aval2"] != "") ? " '" . $GLOBALS["x_origen_ingresos_aval2"] . "'" : "NULL";
				$fieldList["`origen_ingresos_fam_1`"] = $theValue;
			
				$theValue = ($GLOBALS["x_origen_ingresos_aval3"] != "") ? " '" . $GLOBALS["x_origen_ingresos_aval3"] . "'" : "NULL";
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

				
				//gasto aval

				
				$fieldList = NULL;
				$fieldList["`aval_id`"] = $x_aval_id;
			
				$theValue = ($GLOBALS["x_gastos_prov1_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_prov1_aval"]) . "'" : "NULL";
				$fieldList["`gastos_prov1`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_prov2_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_prov2_aval"]) . "'" : "NULL";
				$fieldList["`gastos_prov2`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_prov3_aval"] != "") ? doubleval($GLOBALS["x_gastos_prov3_aval"]) : "NULL";
				$fieldList["`gastos_prov3`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_otro_prov_aval"] != "") ? doubleval($GLOBALS["x_otro_prov_aval"]) : "NULL";
				$fieldList["`otro_prov`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_empleados_aval"] != "") ? doubleval($GLOBALS["x_gastos_empleados_aval"]) : "NULL";
				$fieldList["`gastos_empleados`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_renta_negocio_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_renta_negocio_aval"]) . "'" : "NULL";
				$fieldList["`gastos_renta_negocio`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_renta_casa2"] != "") ? doubleval($GLOBALS["x_gastos_renta_casa2"]) : "0";
				$fieldList["`gastos_renta_casa`"] = $theValue;
			
			
				$theValue = ($GLOBALS["x_gastos_credito_hipotecario_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_credito_hipotecario_aval"]) . "'" : "NULL";
				$fieldList["`gastos_credito_hipotecario`"] = $theValue;
			
				$theValue = ($GLOBALS["x_gastos_otros_aval"] != "") ? " '" . doubleval($GLOBALS["x_gastos_otros_aval"]) . "'" : "NULL";
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
		
	}else{		
		
			
		$fieldList = NULL;
		// Field cliente_id
	//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
		$fieldList["`solicitud_id`"] = $GLOBALS["x_solicitud_id"];
	
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
		// Field cliente_id
	//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
		$fieldList["`solicitud_id`"] = $GLOBALS["x_solicitud_id"];
		
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
	
			}
	
	
			$x_counter++;
		}
		



//no hace tareas si ya esta en un status mayor a en investigacion
if(($GLOBALS["x_solicitud_status_id"] != 0) && ($GLOBALS["x_solicitud_status_id"] < 3)){

	//CHECKLIST
	
	$x_chk_done_pro = 0;
	$x_chk_done_cre = 0;
	$x_contador = 1;
	while($x_contador < 33){
	
		$x_chk = $GLOBALS["x_checklist_$x_contador"];
		$x_det_ck = $GLOBALS["x_det_ck$x_contador"];	
	
		if(!empty($x_det_ck)){
			$x_det_ck = "'" . $x_det_ck ."'";
		}else{
			$x_det_ck = "NULL";
		}
		
		$sSql = "UPDATE solicitud_checklist set valor = $x_chk,  detalle = $x_det_ck where solicitud_id = ".$GLOBALS["x_solicitud_id"]. " and checklist_id = $x_contador ";
	
		phpmkr_query($sSql, $conn);
		
		if($x_contador < 26){
			$x_chk_done_pro = $x_chk_done_pro + $x_chk;
		}else{
			$x_chk_done_cre = $x_chk_done_cre + $x_chk;		
		}
	
		$x_contador++;	
	}
	
	
	
	//GENERA CASO CRM
	$currentdate = getdate(time());
	$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
	$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];		
	
	//GENERAR TAREA CRM SI NO SE COMPLETO EL CHKLIST
	if($x_chk_done_pro < 25){
	
	}else{
	//CHKLIST COMPLETO GENERA TAREA DE VALIDACION DE DATOS
	
		include_once("../datefunc.php");
	
	
		$sSqlWrk = "
		SELECT 
			crm_caso_id,
			crm_caso_tipo_id
		FROM 
			crm_caso
		WHERE 
			solicitud_id = ".$GLOBALS["x_solicitud_id"]."
			and crm_caso_tipo_id = 1
		";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_crm_caso_id = $datawrk["crm_caso_id"];
		$x_crm_caso_tipo_id = $datawrk["crm_caso_tipo_id"];	
		@phpmkr_free_result($rswrk);
	
	
	//VALIDA TAREA COMPLETADA
		$sSqlWrk = "
		SELECT count(*) as tarea_completa
		FROM crm_tarea
		WHERE 
			crm_caso_id = $x_crm_caso_id
			AND crm_tarea_tipo_id = 1
			AND crm_tarea_status_id = 3
		";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_completada = $datawrk["tarea_completa"];
		@phpmkr_free_result($rswrk);
		
		//La tarea del checklist promo ha sido concluida y genera tarea checklist coordinador 
		if($x_completada == 0){
			
			
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
				
				$x_credito_id = "NULL";
			
			
				if(empty($x_crm_caso_id)){
					//GENERA CASO
					echo "ERROR no se localizo el caso ";
					exit();
			
				}
			
			
			//CIERRA LA TAREA PENDIENTE
			
				$sSqlWrk = "
				SELECT bitacora
				FROM 
					crm_caso
				WHERE 
					crm_caso_id = $x_crm_caso_id
				LIMIT 1
				";
				
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_bitacora_ant = $datawrk["bitacora"];
				@phpmkr_free_result($rswrk);
			
				$x_bitacora = $x_bitacora_ant . "\n\n";
				$x_bitacora .= "Originacion de Credito - (".FormatDateTime($currdate,7)." - $currtime)";
				$x_bitacora .= "\n";
				$x_bitacora .= "CHECKLIST PROMOTOR - COMPLETADA";	
				$x_bitacora .= "\n";
				$x_bitacora .= "comentario promotor:  ".$GLOBALS["x_comentario_promotor"];	
				$x_bitacora .= "\n\n";
				$x_bitacora .= "comentario comite:  ".$GLOBALS["x_comentario_comite"];	
			
			
				$sSqlWrk = "
				UPDATE crm_caso
				SET
					bitacora = '$x_bitacora'
				WHERE 
					crm_caso_id = $x_crm_caso_id
				";
			
				phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			
				//Bitacora solicitud
				$sSql = "UPDATE solicitud_comment set comentario_int = '$x_bitacora' where solicitud_id = $x_solicitud_id";
				phpmkr_query($sSql, $conn);
			
			
				$sSqlWrk = "
				UPDATE crm_tarea 
				SET
					crm_tarea_status_id = 3,
					fecha_status = '".ConvertDateToMysqlFormat($currdate)."'
				WHERE 
					crm_caso_id = $x_crm_caso_id
					AND crm_tarea_tipo_id = 1
				";
			
				phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			
			
				$sSqlWrk = "
				SELECT *
				FROM 
					crm_playlist
				WHERE 
					crm_playlist.crm_caso_tipo_id = $x_crm_caso_tipo_id
					AND crm_playlist.orden = 2
				ORDER BY crm_playlist_id
				";
			
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				while($datawrk = phpmkr_fetch_array($rswrk)){
					$x_crm_playlist_id = $datawrk["crm_playlist_id"];
					$x_prioridad_id = $datawrk["prioridad_id"];	
					$x_asunto = $datawrk["asunto"];	
					$x_descripcion = $datawrk["descripcion"];		
					$x_tarea_tipo_id = $datawrk["tarea_fuente"];		
					$x_orden = $datawrk["orden"];					
					$x_dias_espera = $datawrk["dias_espera"];									
	
					//Fecha Vencimiento
					$temptime = strtotime(ConvertDateToMysqlFormat($currdate));	
					$temptime = DateAdd('w',$x_dias_espera,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);			
					$x_dia = strftime('%A',$temptime);
					if($x_dia == "SUNDAY"){
						$temptime = strtotime($fecha_venc);
						$temptime = DateAdd('w',1,$temptime);
						$fecha_venc = strftime('%Y-%m-%d',$temptime);
					}
					$temptime = strtotime($fecha_venc);
	
	
	
	
					$sSqlWrk = "
					SELECT bitacora
					FROM 
						crm_caso
					WHERE 
						crm_caso_id = $x_crm_caso_id
					LIMIT 1
					";
					
					$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
					$datawrk = phpmkr_fetch_array($rswrk);
					$x_bitacora_ant = $datawrk["bitacora"];
					@phpmkr_free_result($rswrk);
				
					$x_bitacora = $x_bitacora_ant . "\n\n";
					$x_bitacora .= "Originacion de Credito - (".FormatDateTime($currdate,7)." - $currtime)";
					$x_bitacora .= "\n";
					$x_bitacora .= "$x_asunto - $x_descripcion";	
					$x_bitacora .= "\n";
					$x_bitacora .= "comentario promotor:  ".$GLOBALS["x_comentario_promotor"];	
					$x_bitacora .= "\n\n";
					$x_bitacora .= "comentario comite:  ".$GLOBALS["x_comentario_comite"];	
	
					$sSqlWrk = "
					UPDATE crm_caso
					SET
						bitacora = '$x_bitacora'
					WHERE 
						crm_caso_id = $x_crm_caso_id
					";
				
					phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	
	
					//Bitacora solicitud
					$sSql = "UPDATE solicitud_comment set comentario_int = '$x_bitacora' where solicitud_id = $x_solicitud_id";
					phpmkr_query($sSql, $conn);
	
	
					$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".ConvertDateToMysqlFormat($currdate)."','$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 2,  $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
				
					$x_result = phpmkr_query($sSql, $conn);
					
					if(!$x_result){
						echo phpmkr_error() . '<br>SQL: ' . $sSql;
						phpmkr_query('rollback;', $conn);	 
						exit();
					}
				}
				@phpmkr_free_result($rswrk);
		}
	
	
	
	
	
	
	
	
	
	
		//valida tarea checklist coord aqui
		$sSqlWrk = "
		SELECT count(*) as tarea_completa
		FROM crm_tarea
		WHERE 
			crm_caso_id = $x_crm_caso_id
			AND crm_tarea_tipo_id = 1
			AND crm_tarea_status_id in(1,2)
		";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_completada_cre = $datawrk["tarea_completa"];
		@phpmkr_free_result($rswrk);
		
		
		
		//tarea checklist coord en proceso o terminada aqui
		if($x_completada_cre > 0){
	
			if($x_chk_done_cre < 7){
				//tarea en proceso
				
			}else{
				//tarea completada
	
	
	
			//Coordinador de credito
				$sSqlWrk = "
				SELECT usuario_id
				FROM 
					usuario
				WHERE 
					usuario.usuario_rol_id = 1
				LIMIT 1
				";
				
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_usuario_id = $datawrk["usuario_id"];
				@phpmkr_free_result($rswrk);
	
	
	
			//CIERRA LA TAREA PENDIENTE
			
				$sSqlWrk = "
				SELECT bitacora
				FROM 
					crm_caso
				WHERE 
					crm_caso_id = $x_crm_caso_id
				LIMIT 1
				";
				
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_bitacora_ant = $datawrk["bitacora"];
				@phpmkr_free_result($rswrk);
			
				$x_bitacora = $x_bitacora_ant . "\n\n";
				$x_bitacora .= "Originacion de Credito - (".FormatDateTime($currdate,7)." - $currtime)";
				$x_bitacora .= "\n";
				$x_bitacora .= "CHECKLIST COORD. CREDITO - COMPLETADA";	
				$x_bitacora .= "\n";
				$x_bitacora .= "comentario promotor:  ".$GLOBALS["x_comentario_promotor"];	
				$x_bitacora .= "\n\n";
				$x_bitacora .= "comentario comite:  ".$GLOBALS["x_comentario_comite"];	
			
			
				$sSqlWrk = "
				UPDATE crm_caso
				SET
					bitacora = '$x_bitacora'
				WHERE 
					crm_caso_id = $x_crm_caso_id
				";
			
				phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	
				//Bitacora solicitud
				$sSql = "UPDATE solicitud_comment set comentario_int = '$x_bitacora' where solicitud_id = $x_solicitud_id";
				phpmkr_query($sSql, $conn);
	
			
				$sSqlWrk = "
				UPDATE crm_tarea 
				SET
					crm_tarea_status_id = 3,
					fecha_status = '".ConvertDateToMysqlFormat($currdate)."'
				WHERE 
					crm_caso_id = $x_crm_caso_id
					AND crm_tarea_tipo_id = 1
					and crm_tarea_status_id in (1,2)
				";
			
				phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			
			
				$sSqlWrk = "
				SELECT *
				FROM 
					crm_playlist
				WHERE 
					crm_playlist.crm_caso_tipo_id = $x_crm_caso_tipo_id
					AND crm_playlist.orden = 3
				ORDER BY crm_playlist_id
				";
			
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				while($datawrk = phpmkr_fetch_array($rswrk)){
					$x_crm_playlist_id = $datawrk["crm_playlist_id"];
					$x_prioridad_id = $datawrk["prioridad_id"];	
					$x_asunto = $datawrk["asunto"];	
					$x_descripcion = $datawrk["descripcion"];		
					$x_tarea_tipo_id = $datawrk["tarea_fuente"];		
					$x_orden = $datawrk["orden"];					
					$x_dias_espera = $datawrk["dias_espera"];									
	
					//Fecha Vencimiento
					$temptime = strtotime(ConvertDateToMysqlFormat($currdate));	
					$temptime = DateAdd('w',$x_dias_espera,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);			
					$x_dia = strftime('%A',$temptime);
					if($x_dia == "SUNDAY"){
						$temptime = strtotime($fecha_venc);
						$temptime = DateAdd('w',1,$temptime);
						$fecha_venc = strftime('%Y-%m-%d',$temptime);
					}
					$temptime = strtotime($fecha_venc);
	
	
	
	
					$sSqlWrk = "
					SELECT bitacora
					FROM 
						crm_caso
					WHERE 
						crm_caso_id = $x_crm_caso_id
					LIMIT 1
					";
					
					$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
					$datawrk = phpmkr_fetch_array($rswrk);
					$x_bitacora_ant = $datawrk["bitacora"];
					@phpmkr_free_result($rswrk);
				
					$x_bitacora = $x_bitacora_ant . "\n\n";
					$x_bitacora .= "Originacion de Credito - (".FormatDateTime($currdate,7)." - $currtime)";
					$x_bitacora .= "\n";
					$x_bitacora .= "$x_asunto - $x_descripcion";	
	
					$sSqlWrk = "
					UPDATE crm_caso
					SET
						bitacora = '$x_bitacora'
					WHERE 
						crm_caso_id = $x_crm_caso_id
					";
				
					phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	
	
					//Bitacora solicitud
					$sSql = "UPDATE solicitud_comment set comentario_int = '$x_bitacora' where solicitud_id = $x_solicitud_id";
					phpmkr_query($sSql, $conn);
	
	
					$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".ConvertDateToMysqlFormat($currdate)."','$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 2,  $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
				
					$x_result = phpmkr_query($sSql, $conn);
					
					if(!$x_result){
						echo phpmkr_error() . '<br>SQL: ' . $sSql;
						phpmkr_query('rollback;', $conn);	 
						exit();
					}
				}
	
			}
			
		}
	
	
	
	
	
	}


}



		phpmkr_query('commit;', $conn);	 


		$bEditData = true; // Update Successful
		
	}
	
	
	
	return $bEditData;
}
?>