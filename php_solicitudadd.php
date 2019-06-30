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
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
$x_solicitud_id = @$_GET["solicitud_id"];
if (empty($x_solicitud_id)) {
	$bCopy = false;
}

// Get action
$sAction = @$_POST["a_add"];
if (($sAction == "") || ((is_null($sAction)))) {
	if ($bCopy) {
		$sAction = "C"; // Copy record
	}else{
		$sAction = "I"; // Display blank record
	}
}else{

	// Get fields from form
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



	$x_cliente_id = @$_POST["x_cliente_id"];
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
	$x_propietario = @$_POST["x_propietario"];
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
	$x_propietario2 = @$_POST["x_propietario2"];
	$x_codigo_postal2 = @$_POST["x_codigo_postal2"];
	$x_ubicacion2 = @$_POST["x_ubicacion2"];
	$x_antiguedad2 = @$_POST["x_antiguedad2"];
	$x_otro_tipo_vivienda2 = @$_POST["x_otro_tipo_vivienda2"];
	$x_telefono2 = @$_POST["x_telefono2"];
	$x_telefono_secundario2 = @$_POST["x_telefono_secundario2"];


	$x_aval_id = 0;
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
	$x_otros_ingresos_aval = @$_POST["x_otros_ingresos_aval"];	
	$x_origen_ingresos_aval = @$_POST["x_origen_ingresos_aval"];		
	$x_origen_ingresos_aval2 = @$_POST["x_origen_ingresos_aval2"];			
	$x_ingresos_familiar_1_aval = @$_POST["x_ingresos_familiar_1_aval"];				
	$x_parentesco_tipo_id_ing_1_aval = @$_POST["x_parentesco_tipo_id_ing_1_aval"];				
	$x_ocupacion = @$_POST["x_ocupacion"];





	$x_garantia_id = 0;
	$x_garantia_desc = @$_POST["x_garantia_desc"];
	$x_garantia_valor = @$_POST["x_garantia_valor"];
	$x_documento = NULL;


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



}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron los datos";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_solicitudlist.php");
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "La solicitud ha sido registrada.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_solicitudlist.php");
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
			$GLOBALS["x_nacionalidad_id"] = $row2["nacionalidad_id"];					
			
			$GLOBALS["x_empresa"] = $row2["empresa"];		
			$GLOBALS["x_puesto"] = $row2["puesto"];		
			$GLOBALS["x_fecha_contratacion"] = $row2["fecha_contratacion"];		
			$GLOBALS["x_salario_mensual"] = $row2["salario_mensual"];														
	
					
			$sSql = "select * from direccion join delegacion on delegacion.delegacion_id = direccion.delegacion_id where cliente_id = ".$GLOBALS["x_cliente_id"]." and direccion_tipo_id = 1";
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


			$sSql = "select * from direccion join delegacion on delegacion.delegacion_id = direccion.delegacion_id where cliente_id = ".$GLOBALS["x_cliente_id"]." and direccion_tipo_id = 2";
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
	

			$x_loc = false;
			$sSql = "select solicitud_id from solicitud_cliente where cliente_id = ".$GLOBALS["x_cliente_id"]." order by solicitud_id desc";
			$rs7 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			while ($row7 = phpmkr_fetch_array($rs7)){
				$x_sol_tmp = $row7["solicitud_id"];
				if($x_sol_tmp > 0){
					$sSql = "select count(*) as ing from ingreso where solicitud_id = $x_sol_tmp";
					$rs71 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
					$row71 = phpmkr_fetch_array($rs71);
					if($row71["ing"] > 0 ){
						$x_sol_loc = $row7["solicitud_id"];					
						$x_loc = true;				
					}
					phpmkr_free_result($rs71);									
				}
			}
			phpmkr_free_result($rs7);									
	
	
			if($x_loc == true){

				$sSql = "select * from aval where solicitud_id = $x_sol_loc";
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
				phpmkr_free_result($rs5);
				
				if($GLOBALS["x_aval_id"] != ""){
					$sSql = "select * from direccion join delegacion on delegacion.delegacion_id = direccion.delegacion_id where aval_id = ".$GLOBALS["x_aval_id"]." and direccion_tipo_id = 3";
					$rs5 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
					$row5 = phpmkr_fetch_array($rs5);
					$GLOBALS["x_direccion_id3"] = $row5["direccion_id"];
					$GLOBALS["x_calle3"] = $row5["calle"];
					$GLOBALS["x_colonia3"] = $row5["colonia"];
					$GLOBALS["x_delegacion_id3"] = $row5["delegacion_id"];
					$GLOBALS["x_propietario2"] = $row5["propietario"];
					$GLOBALS["x_entidad_id3"] = $row5["entidad_id"];
					$GLOBALS["x_codigo_postal3"] = $row5["codigo_postal"];
					$GLOBALS["x_ubicacion3"] = $row5["ubicacion"];
					$GLOBALS["x_antiguedad3"] = $row5["antiguedad"];
					$GLOBALS["x_vivienda_tipo_id2"] = $row5["vivienda_tipo_id"];
					$GLOBALS["x_otro_tipo_vivienda3"] = $row5["otro_tipo_vivienda"];
					$GLOBALS["x_telefono3"] = $row5["telefono"];
					$GLOBALS["x_telefono3_sec"] = $row5["telefono_movil"];					
					$GLOBALS["x_telefono_secundario3"] = $row5["telefono_secundario"];
					phpmkr_free_result($rs5);			
					

					$sSql = "select * from direccion join delegacion on delegacion.delegacion_id = direccion.delegacion_id where aval_id = ".$GLOBALS["x_aval_id"]." and direccion_tipo_id = 4";
					$rs5 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
					$row5 = phpmkr_fetch_array($rs5);
					$GLOBALS["x_direccion_id4"] = $row5["direccion_id"];
					$GLOBALS["x_calle3_neg"] = $row5["calle"];
					$GLOBALS["x_colonia3_neg"] = $row5["colonia"];
					$GLOBALS["x_delegacion_id3_neg"] = $row5["delegacion_id"];
					$GLOBALS["x_propietario2_neg"] = $row5["propietario"];
					$GLOBALS["x_entidad_id3_neg"] = $row5["entidad_id"];
					$GLOBALS["x_codigo_postal3_neg"] = $row5["codigo_postal"];
					$GLOBALS["x_ubicacion3_neg"] = $row5["ubicacion"];
					$GLOBALS["x_antiguedad3_neg"] = $row5["antiguedad"];
					$GLOBALS["x_vivienda_tipo_id2_neg"] = $row5["vivienda_tipo_id"];
					$GLOBALS["x_otro_tipo_vivienda3_neg"] = $row5["otro_tipo_vivienda"];
					$GLOBALS["x_telefono3_neg"] = $row5["telefono"];
					$GLOBALS["x_telefono_secundario3_neg"] = $row5["telefono_secundario"];
					phpmkr_free_result($rs5);		

					$sSql = "select * from ingreso_aval where aval_id = ".$GLOBALS["x_aval_id"];
					$rs5 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
					$row5 = phpmkr_fetch_array($rs5);
					$GLOBALS["x_ingreso_aval_id"] = $row5["ingreso_aval_id"];
					$GLOBALS["x_ingresos_mensuales"] = $row5["ingresos_negocio"];
					$GLOBALS["x_ingresos_familiar_1_aval"] = $row5["ingresos_familiar_1"];
					$GLOBALS["x_parentesco_tipo_id_ing_1_aval"] = $row5["parentesco_tipo_id"];
					$GLOBALS["x_otros_ingresos_aval"] = $row5["otros_ingresos"];
					$GLOBALS["x_origen_ingresos_aval"] = $row5["origen_ingresos"];		
					$GLOBALS["x_origen_ingresos_aval2"] = $row5["origen_ingresos_fam_1"];							
					
					phpmkr_free_result($rs5);		
				}
		
				$sSql = "select * from ingreso where solicitud_id = ".$x_sol_loc;
				$rs5 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
				$row5 = phpmkr_fetch_array($rs5);
				$GLOBALS["x_ingreso_id"] = $row5["ingreso_id"];
				$GLOBALS["x_ingresos_negocio"] = $row5["ingresos_negocio"];
				$GLOBALS["x_ingresos_familiar_1"] = $row5["ingresos_familiar_1"];
				$GLOBALS["x_parentesco_tipo_id_ing_1"] = $row5["parentesco_tipo_id"];
				$GLOBALS["x_ingresos_familiar_2"] = $row5["ingresos_familiar_2"];
				$GLOBALS["x_parentesco_tipo_id_ing_2"] = $row5["parentesco_tipo_id2"];
				$GLOBALS["x_otros_ingresos"] = $row5["otros_ingresos"];

				$GLOBALS["x_origen_ingresos"] = $row5["origen_ingresos"];				
				$GLOBALS["x_origen_ingresos2"] = $row5["origen_ingresos_fam_1"];				
				$GLOBALS["x_origen_ingresos3"] = $row5["origen_ingresos_fam_2"];												
		
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
				$sSql = "select * from referencia where solicitud_id = ".$x_sol_loc." order by referencia_id";
				$rs6 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
				while ($row6 = phpmkr_fetch_array($rs6)){
					$GLOBALS["x_referencia_id_$x_count"] = $row6["referencia_id"];
					$GLOBALS["x_nombre_completo_ref_$x_count"] = $row6["nombre_completo"];
					$GLOBALS["x_telefono_ref_$x_count"] = $row6["telefono"];
					$GLOBALS["x_parentesco_tipo_id_ref_$x_count"] = $row6["parentesco_tipo_id"];
					$x_count++;
				}
				
				phpmkr_free_result($rs5);			
				phpmkr_free_result($rs6);		
				
				
											
	
			}
			phpmkr_free_result($rs2);	
			phpmkr_free_result($rs3);		
			phpmkr_free_result($rs4);			
		}else{
			$GLOBALS["x_cliente_id"] = 0;
			$GLOBALS["x_usuario_id"] = 0;
			$GLOBALS["x_nombre_completo"] = "";
			$GLOBALS["x_apellido_paterno"] = "";
			$GLOBALS["x_apellido_materno"] = "";						
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
		if($_POST["x_delegacion_id_temp1"] != ""){
			$x_delegacion_id3 = $_POST["x_delegacion_id_temp1"];		
			$x_delegacion_id3_neg = $_POST["x_delegacion_id_temp1_2"];
		}		
		if($_POST["x_delegacion_id_temp2"] != ""){
			$x_delegacion_id3_neg = $_POST["x_delegacion_id_temp2"];
		}
		break;
}
?>
<?php 
include ("header.php");
 ?>
<script type="text/javascript" src="ew.js"></script>
<script src="paisedohint.js"></script> 
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>

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
<p><span class="phpmaker"><a href="php_solicitudlist.php">Regresar a la Lista</a></span></p>
<form name="solicitudadd" id="solicitudadd" action="php_solicitudadd.php" method="post" >
<p>
<input type="hidden" name="a_add" value="A">
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
    <td colspan="3" align="left" valign="top">
	<table width="674" border="0" cellspacing="0" cellpadding="0">
	
	<tr>
	  <td class="texto_normal">Promotor:</td>
	  <td colspan="4"><span class="phpmaker">
	    <?php
		$x_estado_civil_idList = "<select name=\"x_promotor_id\" class=\"texto_normal\">";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		if($_SESSION["php_project_esf_status_UserRolID"] == 7) {
			$sSqlWrk = "SELECT promotor_id, nombre_completo FROM promotor Where promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"];
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
				$x_estado_civil_idList .= ">" . $datawrk["nombre_completo"] . "</option>";
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
	  <td class="texto_normal">Cliente:</td>
	  <td colspan="4"><span class="phpmaker">
	    <?php
		$x_estado_civil_idList = "<select name=\"x_cliente_id\" onchange=\"buscacliente()\" class=\"texto_normal\" >";
		$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		if($x_cliente_id == 0){
			$x_estado_civil_idList .= "<option value='0' selected>Nuevo</option>";		
		}else{
			$x_estado_civil_idList .= "<option value='0'>Nuevo</option>";				
		}
		$sSqlWrk = "SELECT `cliente_id`, `nombre_completo`, `apellido_paterno`, `apellido_materno`  FROM `cliente` order by nombre_completo, cliente_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			$x_nom_rec = "";
			while ($datawrk = phpmkr_fetch_array($rswrk)) {

				$x_nom = $datawrk["nombre_completo"]." ".$datawrk["apellido_paterno"]." ".$datawrk["apellido_materno"];
			
				if($rowcntwrk > 0 && $x_nom_rec != $x_nom){
					$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($x_cliente_id_rec) . "\"";
					if ($x_cliente_id_rec == @$x_cliente_id) {
						$x_estado_civil_idList .= "' selected";
					}
					$x_estado_civil_idList .= ">" . htmlspecialchars($x_nom_rec) . "</option>";
				}
				$rowcntwrk++;				
				
				$x_nom_rec = $datawrk["nombre_completo"]." ".$datawrk["apellido_paterno"]." ".$datawrk["apellido_materno"];
				$x_cliente_id_rec = $datawrk["cliente_id"];
			
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
?>
	  </span></td>
	  </tr>
	<tr>
	  <td width="159" class="texto_normal">Tipo de Crédito: </td>
	  <td colspan="2" class="texto_normal"><b>PERSONAL</b>
	  <input type="hidden" name="x_credito_tipo_id" value="1" />
	  </td>
	  <td width="230"><div align="right"><span class="texto_normal">&nbsp;Fecha Solicitud:</span></div></td>
	  <td width="164">
	  <span class="texto_normal">
	  <b>
	  <?php echo $currdate; ?>	  </b>	  </span>
	  <input name="x_fecha_registro" type="hidden" value="<?php echo $currdate; ?>" /></td>
	  </tr>
	<tr>
	  <td><span class="texto_normal">Importe solicitado: </span></td>
	  <td width="111"><div align="left">
	    <input class="importe" name="x_importe_solicitado" type="text" id="x_importe_solicitado" value="<?php echo htmlspecialchars(@$x_importe_solicitado) ?>" size="10" maxlength="10" onKeyPress="return solonumeros(this,event)" onblur="validaimporte()" />
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
	  <td class="texto_normal">Actividad Empresarial:</td>
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
	  <td colspan="4" class="texto_normal">
      &nbsp;
      <div id="actividad1" class="TG_visible">Específicamente:</div>
      <div id="actividad2" class="TG_hidden">Consistentes en:</div>
      <div id="actividad3" class="TG_hidden">Especificar qu&eacute; y para qu&eacute;:</div>      </td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td colspan="4"><textarea name="x_actividad_desc" cols="60" rows="5" id="x_actividad_desc"><?php echo $x_actividad_desc; ?></textarea></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>
	</table>	</td>
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
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
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
				$x_nac_idList .= ">" . $datawrk["pais_nombre"] . "</option>";
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
				$x_delegacion_idList .= ">" . $datawrk["nombre"] . "</option>";
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
				$x_vivienda_tipo_idList .= ">" . $datawrk["descripcion"] . "</option>";
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
		<div id="prop1" class="<?php if($x_vivienda_tipo_id == 3){ echo "TG_visible";}else{ echo "TG_hidden";} ?>">
		Propietario de la Vivienda:&nbsp;
		<input class="texto_normal" type="text" name="x_propietario" value="<?php echo $x_propietario; ?>" size="50" maxlength="150" />
		</div>		</td>
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
				$x_delegacion_idList .= ">" . $datawrk["nombre"] . "</option>";
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
    <td colspan="3" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Datos Aval </td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top">
	
<!--	<div id="aval" class="TG_hidden"> -->
	
	<table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="texto_normal">Aval: </td>
        <td colspan="3"><table width="534" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="155"><div align="center"><span class="texto_normal">
              <input name="x_nombre_completo_aval" type="text" class="texto_normal" id="x_nombre_completo_aval" value="<?php echo htmlspecialchars(@$x_nombre_completo_aval) ?>" size="25" maxlength="100" />
            </span></div></td>
            <td width="178"><div align="center"><span class="texto_normal">
              <input name="x_apellido_paterno_aval" type="text" class="texto_normal" id="x_apellido_paterno_aval" value="<?php echo htmlspecialchars(@$x_apellido_paterno_aval) ?>" size="25" maxlength="100" />
            </span></div></td>
            <td width="201"><div align="center"><span class="texto_normal">
              <input name="x_apellido_materno_aval" type="text" class="texto_normal" id="x_apellido_materno_aval" value="<?php echo htmlspecialchars(@$x_apellido_materno_aval) ?>" size="25" maxlength="100" />
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
        <td>
		<?php
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
				$x_parentesco_tipo_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?>		</td>
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
              <input name="x_sexo_aval" type="radio" value="<?php echo htmlspecialchars("1"); ?>" checked="checked" <?php if (@$x_sexo_aval == "1") { echo "checked=checked"; } ?> />
              <?php echo "M"; ?> <?php echo EditOptionSeparator(0); ?>
              <input type="radio" name="x_sexo_aval" <?php if (@$x_sexo_aval == "2"){ echo "checked=checked"; } ?> value="<?php echo htmlspecialchars("2"); ?>" />
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
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
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
        <td colspan="2"><input name="x_nombre_conyuge_aval" type="text" class="texto_normal" id="x_nombre_conyuge_aval" value="<?php echo htmlspecialchars(@$x_nombre_conyuge_aval) ?>" size="80" maxlength="250" /></td>
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
				$x_nac_idList .= ">" . $datawrk["pais_nombre"] . "</option>";
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
        <td colspan="4" class="texto_normal">&nbsp;</td>
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
        <td colspan="3"><input name="x_calle3" type="text" class="texto_normal" id="x_calle3" value="<?php echo htmlspecialchars(@$x_calle3) ?>" size="80" maxlength="150" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Colonia: </span></td>
        <td colspan="3"><input name="x_colonia3" type="text" class="texto_normal" id="x_colonia3" value="<?php echo htmlspecialchars(@$x_colonia3) ?>" size="80" maxlength="150" /></td>
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
				$x_delegacion_idList .= ">" . $datawrk["nombre"] . "</option>";
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
		<div id="txtHint3" class="texto_normal"></div>
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
        <td><span class="texto_normal">Referencia de Ubicación:</span></td>
        <td colspan="4"><input name="x_ubicacion3" type="text" class="texto_normal" id="x_ubicacion3" value="<?php echo htmlspecialchars(@$x_ubicacion3) ?>" size="80" maxlength="250" /></td>
      </tr>
      <tr>
        <td class="texto_normal">Antiguedad en Domicilio: </td>
        <td colspan="4"><span class="texto_normal">
          <input name="x_antiguedad3" type="text" class="texto_normal" id="x_antiguedad3" onKeyPress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_antiguedad3) ?>" size="2" maxlength="2"/>
          (años)
          
        </span></td>
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
				$x_vivienda_tipo_idList .= ">" . $datawrk["descripcion"] . "</option>";
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
		<div id="prop2" class="<?php if($x_vivienda_tipo_id2 == 3){ echo "TG_visible";}else{ echo "TG_hidden";} ?>">
		Propietario de la Vivienda:&nbsp;
		<input class="texto_normal" type="text" name="x_propietario2" value="<?php echo $x_propietario2; ?>" size="50" maxlength="150" />
		</div>		</td>
      </tr>
	  
      <tr>
        <td colspan="5" class="texto_normal">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="5" class="texto_normal" style=" border-left : solid 1px #666; border-right : solid 1px #666; border-top : solid 1px #666"><input type="checkbox" name="x_mismosava2" value="0" onclick="mismosdomtitneg()" />
          <em>&nbsp;Mismo domicilio de Negocio  (TITULAR).</em></td>
      </tr>
      <tr>
        <td colspan="5" class="texto_normal" style=" border-left : solid 1px #666; border-right : solid 1px #666; border-bottom : solid 1px #666">
		<div align="left">
          <input type="checkbox" name="x_mismosava" value="0" onclick="mismosdomava()" />
         <em><span class="texto_normal">Mismo domicilio que el Particular (AVAL).</span></em><span class="texto_normal"></span></div>		</td>
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
        <td colspan="3"><input name="x_calle3_neg" type="text" class="texto_normal" id="x_calle3_neg" value="<?php echo htmlspecialchars(@$x_calle3_neg) ?>" size="80" maxlength="150" /></td>
      </tr>
      <tr>
        <td><span class="texto_normal">Colonia: </span></td>
        <td colspan="3"><input name="x_colonia3_neg" type="text" class="texto_normal" id="x_colonia3_neg" value="<?php echo htmlspecialchars(@$x_colonia3_neg) ?>" size="80" maxlength="150" /></td>
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
				$x_delegacion_idList .= ">" . $datawrk["nombre"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_delegacion_idList .= "</select>";
		echo $x_delegacion_idList;
		?>
        </span></td>
        <td width="400"><div align="left"><span class="texto_normal">

        </span><span class="texto_normal">
		<div id="txtHint3_neg" class="texto_normal"></div>
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
        <td><span class="texto_normal">Referencia de Ubicación:</span></td>
        <td colspan="4"><input name="x_ubicacion3_neg" type="text" class="texto_normal" id="x_ubicacion3_neg" value="<?php echo htmlspecialchars(@$x_ubicacion3_neg) ?>" size="80" maxlength="250" /></td>
      </tr>
      <tr>
        <td class="texto_normal">Antiguedad en Domicilio: </td>
        <td colspan="4"><span class="texto_normal">
          <input name="x_antiguedad3_neg" type="text" class="texto_normal" id="x_antiguedad3_neg" onKeyPress="return solonumeros(this,event)" value="<?php echo htmlspecialchars(@$x_antiguedad3_neg) ?>" size="2" maxlength="2"/>
          (años)
          
        </span></td>
      </tr>
      





      <tr>
        <td class="texto_normal">Tel.;</td>
        <td colspan="4"><input name="x_telefono3_neg" type="text" class="texto_normal" id="x_telefono3_neg" value="<?php echo htmlspecialchars(@$x_telefono3_neg) ?>" size="20" maxlength="20" /></td>
      </tr>
      <tr>
        <td class="texto_normal_bold">Ingresos</td>
        <td colspan="4">&nbsp;</td>
      </tr>
      
      <tr>
        <td class="texto_normal">Ingresos Mensuales : </td>
        <td colspan="4"><input class="importe" name="x_ingresos_mensuales" type="text" id="x_ingresos_mensuales" value="<?php echo htmlspecialchars(@$x_ingresos_mensuales) ?>" size="10" maxlength="10" onKeyPress="return solonumeros(this,event)"/></td>
      </tr>
      <tr>
        <td class="texto_normal">Otros Ingresos: </td>
        <td colspan="4">
		<input class="importe" name="x_otros_ingresos_aval" type="text" id="x_otros_ingresos_aval" value="<?php echo htmlspecialchars(@$x_otros_ingresos_aval) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)" />
        <span class="texto_normal">&nbsp;Origen:
        <input class="texto_normal" name="x_origen_ingresos_aval" type="text" id="x_origen_ingresos_aval" value="<?php echo htmlspecialchars(@$x_origen_ingresos_aval) ?>" size="30" maxlength="150" />
        </span></td>
      </tr>
      <tr>
        <td class="texto_normal">Ingresos Familiares: </td>
        <td colspan="4">
		<input class="importe" name="x_ingresos_familiar_1_aval" type="text" id="x_ingresos_familiar_1_aval" value="<?php echo htmlspecialchars(@$x_ingresos_familiar_1_aval) ?>" size="10" maxlength="10" onkeypress="return solonumeros(this,event)"/> 
          <span class="texto_normal">Parentesco:</span>            <?php
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
				$x_parentesco_tipo_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?></td>
      </tr>
      <tr>
        <td class="texto_normal">Origen: </td>
        <td colspan="4"><span class="texto_normal">
          <input class="texto_normal" name="x_origen_ingresos_aval2" type="text" id="x_origen_ingresos_aval2" value="<?php echo htmlspecialchars(@$x_origen_ingresos_aval2) ?>" size="30" maxlength="150" />
        </span></td>
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
    </table>
	
<!-- 	</div>	-->	</td>
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

	
	<table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="165"><span class="texto_normal">Descripción</span></td>
        <td width="84" class="texto_normal">&nbsp;</td>
        <td width="163" class="texto_normal">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3"><input name="x_garantia_desc" type="text" class="texto_normal" id="x_garantia_desc" value="<?php echo htmlspecialchars(@$x_garantia_desc) ?>" size="115" maxlength="250"  /></td>
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
        <td width="240"><span class="texto_normal">Ingresos del Negocio:</span></td>
        <td width="122"><input class="importe" name="x_ingresos_negocio" type="text" id="x_ingresos_negocio" value="<?php echo htmlspecialchars(@$x_ingresos_negocio) ?>" size="10" maxlength="10" onKeyPress="return solonumeros(this,event)"/></td>
        <td width="123" class="texto_normal">Otros Ingresos: </td>
        <td width="215"><input class="importe" name="x_otros_ingresos" type="text" id="x_otros_ingresos" value="<?php echo htmlspecialchars(@$x_otros_ingresos) ?>" size="10" maxlength="10" onKeyPress="return solonumeros(this,event)" /></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
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
				$x_parentesco_tipo_idList .= ">" . $datawrk["descripcion"] . "</option>";
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
				$x_parentesco_tipo_id2List .= ">" . $datawrk["descripcion"] . "</option>";
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
				$x_parentesco_tipo_idList .= ">" . $datawrk["descripcion"] . "</option>";
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
				$x_parentesco_tipo_idList .= ">" . $datawrk["descripcion"] . "</option>";
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
				$x_parentesco_tipo_idList .= ">" . $datawrk["descripcion"] . "</option>";
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
				$x_parentesco_tipo_idList .= ">" . $datawrk["descripcion"] . "</option>";
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
				$x_parentesco_tipo_idList .= ">" . $datawrk["descripcion"] . "</option>";
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

  
  <tr>
    <td colspan="3" bgcolor="#FFE6E6"><div align="center" class="texto_normal_bold">Términos y condiciones </div></td>
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
        <td><div align="left" class="texto_small">Por este conducto autorizo expresamente a Microfinanciera CRECE, S. A. de C.V. SOFOM E.N.R., para que por conducto de sus funcionarios facultados lleve a cabo Investigaciones, sobre mi comportamiento e historia&iacute; crediticio, asi como de cualquier otra informaci&oacute;n de naturaleza an&aacute;loga, en las Sociedades de Informaci&oacute;n Crediticia que estime conveniente. As&iacute; mismo, declaro que conozco la naturaleza y alcance de la informaci&oacute;n que se solicitar&aacute;, del uso que Microfinanciera CRECE, S. A. de C.V. SOFOM E.N.R. har&aacute; de ta&iacute; informaci&oacute;n y de que &eacute;sta podr&aacute; realzar consultas peri&oacute;dicas de mi historial crediticio, consintiendo que esta autorizaci&oacute;n se encuentre vigente por un periodo de 3 a&ntilde;os contados a partir de la fecha de su expedici&oacute;n y
            en todo caso durante el tiempo que mantengamos una relaci&oacute;n jur&iacute;dica. Estoy conciente y acepto que este documento quede bajo propiedad de Microfinanciera CRECE, S. A. de C.V. SOFOM E.N.R. para efectos de control y cumplimiento del art. 28 de la Ley para regular a las Sociedades e informaci&oacute;n Cr&eacute;diticia. <br />
            <br />
            De acuerdo al Cap&iacute;tulo II, Secci&oacute;n Primera, Art&iacute;culo 3, cl&aacute;usula cuatro de la Ley para la Transparencia y Ordenamiento de los Servicios Financieros Aplicables a los Contratos de Adhesi&oacute;n, Publicidad, Estados de Cuenta y Comprobantes de Operaci&oacute;n de las Sociedades Financieras de Objeto M&uacute;ltiple No Reguladas; por &eacute;ste medio expreso mi consentimiento que a trav&eacute;s del personal facultado de &quot;Microfinanciera Crece SOFOM ENR&quot;, he sido enterado del Costo Anual Total del cr&eacute;dito que estoy interesado en celebrar. Tambi&eacute;n he sido enterado de la tasa de inter&eacute;s moratoria que se cobrar&aacute; en caso de presentar atraso(s) en alguno(s) de los vencimientos del pr&eacute;stamo. Tambi&eacute;n de acuerdo al Cap&iacute;tulo IV, Secci&oacute;n Primera, Art&iacute;culo 23 de la misma; estoy de acuerdo
            en consultar mi estado de cuenta a trav&eacute;s de internet mediante la p&aacute;gina www.financieracrece.com con el usuario y contrase&ntilde;a que &quot;Microfinanciera Crece SOFOM ENR&quot; a trav&eacute;s de su personal facultado me hagan saber toda vez que se firme el pagar&eacute; correspondiente al cr&eacute;dito que estoy interesado en pactar.</div>		</td>
        <td>&nbsp;</td>
      </tr>

      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><table width="300" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="55"><div align="center">
              <input name="x_acepto" type="checkbox" class="texto_normal" value="1" />
            </div></td>
            <td width="245" class="texto_normal">Acepto estos Términos y condiciones.</td>
            </tr>
        </table></td>
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
      <input type="button" value="Registrar Solicitud" class="boton_medium" onclick="EW_checkMyForm()" />
    </div></td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>
</form>
<?php include ("footer.php") ?>
<?php
phpmkr_db_close($conn);
?>
<?php
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




	phpmkr_query('START TRANSACTION;', $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: BEGIN TRAN');

//SOLICITUD

	// Field credito_tipo_id
	$theValue = ($GLOBALS["x_credito_tipo_id"] != "") ? intval($GLOBALS["x_credito_tipo_id"]) : "NULL";
	$fieldList["`credito_tipo_id`"] = $theValue;

	// Field solicitud_status_id
	$theValue = ($GLOBALS["x_solicitud_status_id"] != "") ? intval($GLOBALS["x_solicitud_status_id"]) : "NULL";
	$fieldList["`solicitud_status_id`"] = $theValue;

	// Field folio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_folio"]) : $GLOBALS["x_folio"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`folio`"] = $theValue;

	// Field fecha_registro
	$theValue = ($GLOBALS["x_fecha_registro"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_registro"]) . "'" : "Null";
	$fieldList["`fecha_registro`"] = $theValue;

	// Field promotor_id
	$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
	$fieldList["`promotor_id`"] = $theValue;

	// Field importe_solicitado
	$theValue = ($GLOBALS["x_importe_solicitado"] != "") ? " '" . doubleval($GLOBALS["x_importe_solicitado"]) . "'" : "NULL";
	$fieldList["`importe_solicitado`"] = $theValue;

	// Field plazo
	$theValue = ($GLOBALS["x_plazo_id"] != "") ? intval($GLOBALS["x_plazo_id"]) : "NULL";
	$fieldList["`plazo_id`"] = $theValue;

	$theValue = ($GLOBALS["x_forma_pago_id"] != "") ? intval($GLOBALS["x_forma_pago_id"]) : "NULL";
	$fieldList["`forma_pago_id`"] = $theValue;

	// Field contrato
	$theValue = $GLOBALS["x_contrato"][0];
	$theValue = ($theValue != "") ? intval($theValue) : "NULL";
	$fieldList["`contrato`"] = $theValue;

	// Field pagare
	$theValue = $GLOBALS["x_pagare"][0];
	$theValue = ($theValue != "") ? intval($theValue) : "NULL";
	$fieldList["`pagare`"] = $theValue;


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
//	$theValue = ($GLOBALS["x_usuario_id"] != "") ? intval($GLOBALS["x_usuario_id"]) : "NULL";
	$fieldList["`usuario_id`"] = 0;

	// Field nombre_completo
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


	// Field tipo_negocio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tipo_negocio"]) : $GLOBALS["x_tipo_negocio"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`tipo_negocio`"] = $theValue;


	// Field fecha_registro
	$theValue = ($GLOBALS["x_tit_fecha_nac"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_tit_fecha_nac"]) . "'" : "Null";
	$fieldList["`fecha_nac`"] = $theValue;


	// Field edad
	$theValue = ($GLOBALS["x_edad"] != "") ? intval($GLOBALS["x_edad"]) : "NULL";
	$fieldList["`edad`"] = $theValue;

	// Field sexo
	$theValue = ($GLOBALS["x_sexo"] != "") ? intval($GLOBALS["x_sexo"]) : "NULL";
	$fieldList["`sexo`"] = $theValue;

	// Field estado_civil_id
	$theValue = ($GLOBALS["x_estado_civil_id"] != "") ? intval($GLOBALS["x_estado_civil_id"]) : "0";
	$fieldList["`estado_civil_id`"] = $theValue;

	// Field numero_hijos
	$theValue = ($GLOBALS["x_numero_hijos"] != "") ? intval($GLOBALS["x_numero_hijos"]) : "0";
	$fieldList["`numero_hijos`"] = $theValue;


	// Field numero_hijos_dep
	$theValue = ($GLOBALS["x_numero_hijos_dep"] != "") ? intval($GLOBALS["x_numero_hijos_dep"]) : "0";
	$fieldList["`numero_hijos_dep`"] = $theValue;

	// Field nombre_conyuge
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_conyuge"]) : $GLOBALS["x_nombre_conyuge"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre_conyuge`"] = $theValue;

	// Field nombre_conyuge
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
//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
	$fieldList["`cliente_id`"] = $x_cliente_id;

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

/*
	// Field entidad
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_entidad"]) : $GLOBALS["x_entidad"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`entidad`"] = $theValue;
*/
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


//DIRECCION NEG

	$fieldList = NULL;
	// Field cliente_id
//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
	$fieldList["`cliente_id`"] = $x_cliente_id;

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


//AVAL

	if($GLOBALS["x_nombre_completo_aval"] != ""){

		$fieldList = NULL;
		// Field cliente_id
	//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
		$fieldList["`solicitud_id`"] = $x_solicitud_id;
	
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
//	$theValue = ($GLOBALS["x_ingresos_familiar_2"] != "") ? " '" . doubleval($GLOBALS["x_ingresos_familiar_2"]) . "'" : "NULL";
	$fieldList["`ingresos_familiar_2`"] = 0;

	// Field parentesco_tipo_id2
//	$theValue = ($GLOBALS["x_parentesco_tipo_id_ing_2"] != "") ? intval($GLOBALS["x_parentesco_tipo_id_ing_2"]) : "0";
	$fieldList["`parentesco_tipo_id2`"] = 0;

	// Field otros_ingresos
	$theValue = ($GLOBALS["x_otros_ingresos_aval"] != "") ? " '" . doubleval($GLOBALS["x_otros_ingresos_aval"]) . "'" : "0";
	$fieldList["`otros_ingresos`"] = $theValue;

	$theValue = ($GLOBALS["x_origen_ingresos_aval"] != "") ? " '" . $GLOBALS["x_origen_ingresos_aval"] . "'" : "NULL";
	$fieldList["`origen_ingresos`"] = $theValue;

	$theValue = ($GLOBALS["x_origen_ingresos_aval2"] != "") ? " '" . $GLOBALS["x_origen_ingresos_aval2"] . "'" : "NULL";
	$fieldList["`origen_ingresos_fam_1`"] = $theValue;

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

//GARANTIAS

	if($GLOBALS["x_garantia_desc"] != ""){
		$fieldList = NULL;
		// Field cliente_id
	//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
		$fieldList["`solicitud_id`"] = $x_solicitud_id;
	
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


//INGRESOS

	$fieldList = NULL;
	// Field cliente_id
//	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
	$fieldList["`solicitud_id`"] = $x_solicitud_id;

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



//REFERENCIAS CICLO


	$x_counter = 1;
	while($x_counter < 6){

		$fieldList = NULL;
		// Field cliente_id
//		$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
		$fieldList["`solicitud_id`"] = $x_solicitud_id;


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

	phpmkr_query('commit;', $conn);	 
	
	return true;
}
?>
