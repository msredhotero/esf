<?php session_start(); ?>
<?php ob_start(); ?>

<?php include("admin/db.php"); ?>
<?php include("admin/phpmkrfn.php"); ?>
<?php

$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];

$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];
$x_preventa = true;

$x_msg = "";
$conn = phpmkr_db_connect(HOST, USER, PASS,DB);


?>
<?php
//$x_envio_datos  = $_POST["datos"];
if($_POST["envidos"] == "datosEnviados"){
	
	
	// Get fields from form
	$x_adquisicionMaquinaria_id = @$_POST["x_adquisicionMaquinaria_id"];
	$x_nombre = @$_POST["x_nombre"];
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
	
	
	//HACEMOS EL ARRAY CON LOS VALORES DE LOS CAMPOS
	
	// Field nombre
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre"]) : $GLOBALS["x_nombre"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre`"] = $theValue;

	// Field rfc
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_rfc"]) : $GLOBALS["x_rfc"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`rfc`"] = $theValue;

	// Field curp
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_curp"]) : $GLOBALS["x_curp"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`curp`"] = $theValue;

	// Field fecha_nacimiento
	$theValue = ($GLOBALS["x_fecha_nacimiento"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_nacimiento"]) . "'" : "NULL";
	$fieldList["`fecha_nacimiento`"] = $theValue;

	// Field sexo
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_sexo"]) : $GLOBALS["x_sexo"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`sexo`"] = $theValue;

	// Field integrantes_familia
	$theValue = ($GLOBALS["x_integrantes_familia"] != "") ? intval($GLOBALS["x_integrantes_familia"]) : "NULL";
	$fieldList["`integrantes_familia`"] = $theValue;

	// Field dependientes
	$theValue = ($GLOBALS["x_dependientes"] != "") ? intval($GLOBALS["x_dependientes"]) : "NULL";
	$fieldList["`dependientes`"] = $theValue;

	// Field correo_electronico
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_correo_electronico"]) : $GLOBALS["x_correo_electronico"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`correo_electronico`"] = $theValue;

	// Field esposa
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_esposa"]) : $GLOBALS["x_esposa"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`esposa`"] = $theValue;

	// Field calle_domicilio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle_domicilio"]) : $GLOBALS["x_calle_domicilio"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`calle_domicilio`"] = $theValue;

	// Field colonia_domicilio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia_domicilio"]) : $GLOBALS["x_colonia_domicilio"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`colonia_domicilio`"] = $theValue;

	// Field entidad_domicilio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_entidad_domicilio"]) : $GLOBALS["x_entidad_domicilio"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`entidad_domicilio`"] = $theValue;

	// Field codigo_postal_domicilio
	$theValue = ($GLOBALS["x_codigo_postal_domicilio"] != "") ? intval($GLOBALS["x_codigo_postal_domicilio"]) : "NULL";
	$fieldList["`codigo_postal_domicilio`"] = $theValue;

	// Field ubicacion_domicilio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion_domicilio"]) : $GLOBALS["x_ubicacion_domicilio"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ubicacion_domicilio`"] = $theValue;

	// Field tipo_vivienda
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tipo_vivienda"]) : $GLOBALS["x_tipo_vivienda"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`tipo_vivienda`"] = $theValue;

	// Field telefono_domicilio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_domicilio"]) : $GLOBALS["x_telefono_domicilio"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono_domicilio`"] = $theValue;

	// Field celular
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_celular"]) : $GLOBALS["x_celular"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`celular`"] = $theValue;

	// Field otro_tel_domicilio_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otro_tel_domicilio_1"]) : $GLOBALS["x_otro_tel_domicilio_1"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`otro_tel_domicilio_1`"] = $theValue;

	// Field otro_telefono_domicilio_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otro_telefono_domicilio_2"]) : $GLOBALS["x_otro_telefono_domicilio_2"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`otro_telefono_domicilio_2`"] = $theValue;

	// Field antiguedad
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_antiguedad"]) : $GLOBALS["x_antiguedad"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`antiguedad`"] = $theValue;

	// Field tel_arrendatario_domicilio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_arrendatario_domicilio"]) : $GLOBALS["x_tel_arrendatario_domicilio"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`tel_arrendatario_domicilio`"] = $theValue;

	// Field renta_mensula_domicilio
	$theValue = ($GLOBALS["x_renta_mensula_domicilio"] != "") ? " '" . $GLOBALS["x_renta_mensula_domicilio"] . "'" : "NULL";
	$fieldList["`renta_mensula_domicilio`"] = $theValue;

	// Field giro_negocio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_giro_negocio"]) : $GLOBALS["x_giro_negocio"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`giro_negocio`"] = $theValue;

	// Field calle_negocio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle_negocio"]) : $GLOBALS["x_calle_negocio"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`calle_negocio`"] = $theValue;

	// Field colonia_negocio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia_negocio"]) : $GLOBALS["x_colonia_negocio"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`colonia_negocio`"] = $theValue;

	// Field entidad_negocio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_entidad_negocio"]) : $GLOBALS["x_entidad_negocio"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`entidad_negocio`"] = $theValue;

	// Field ubicacion_negocio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion_negocio"]) : $GLOBALS["x_ubicacion_negocio"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ubicacion_negocio`"] = $theValue;

	// Field codigo_postal_negocio
	$theValue = ($GLOBALS["x_codigo_postal_negocio"] != "") ? intval($GLOBALS["x_codigo_postal_negocio"]) : "NULL";
	$fieldList["`codigo_postal_negocio`"] = $theValue;

	// Field tipo_local_negocio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tipo_local_negocio"]) : $GLOBALS["x_tipo_local_negocio"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`tipo_local_negocio`"] = $theValue;

	// Field antiguedad_negocio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_antiguedad_negocio"]) : $GLOBALS["x_antiguedad_negocio"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`antiguedad_negocio`"] = $theValue;

	// Field tel_arrendatario_negocio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_arrendatario_negocio"]) : $GLOBALS["x_tel_arrendatario_negocio"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`tel_arrendatario_negocio`"] = $theValue;

	// Field renta_mensual
	$theValue = ($GLOBALS["x_renta_mensual"] != "") ? " '" . $GLOBALS["x_renta_mensual"] . "'" : "NULL";
	$fieldList["`renta_mensual`"] = $theValue;

	// Field tel_negocio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_negocio"]) : $GLOBALS["x_tel_negocio"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`tel_negocio`"] = $theValue;

	// Field solicitud_compra
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_solicitud_compra"]) : $GLOBALS["x_solicitud_compra"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`solicitud_compra`"] = $theValue;

	// Field referencia_com_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_com_1"]) : $GLOBALS["x_referencia_com_1"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`referencia_com_1`"] = $theValue;

	// Field referencia_com_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_com_2"]) : $GLOBALS["x_referencia_com_2"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`referencia_com_2`"] = $theValue;

	// Field referencia_com_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_com_3"]) : $GLOBALS["x_referencia_com_3"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`referencia_com_3`"] = $theValue;

	// Field referencia_com_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_com_4"]) : $GLOBALS["x_referencia_com_4"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`referencia_com_4`"] = $theValue;

	// Field tel_referencia_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_referencia_1"]) : $GLOBALS["x_tel_referencia_1"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`tel_referencia_1`"] = $theValue;

	// Field tel_referencia_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_referencia_2"]) : $GLOBALS["x_tel_referencia_2"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`tel_referencia_2`"] = $theValue;

	// Field tel_referencia_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_referencia_3"]) : $GLOBALS["x_tel_referencia_3"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`tel_referencia_3`"] = $theValue;

	// Field tel_referencia_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_referencia_4"]) : $GLOBALS["x_tel_referencia_4"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`tel_referencia_4`"] = $theValue;

	// Field parentesco_ref_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_parentesco_ref_1"]) : $GLOBALS["x_parentesco_ref_1"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`parentesco_ref_1`"] = $theValue;

	// Field parentesco_ref_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_parentesco_ref_2"]) : $GLOBALS["x_parentesco_ref_2"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`parentesco_ref_2`"] = $theValue;

	// Field parentesco_ref_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_parentesco_ref_3"]) : $GLOBALS["x_parentesco_ref_3"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`parentesco_ref_3`"] = $theValue;

	// Field parentesco_ref_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_parentesco_ref_4"]) : $GLOBALS["x_parentesco_ref_4"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`parentesco_ref_4`"] = $theValue;

	// Field ing_fam_negocio
	$theValue = ($GLOBALS["x_ing_fam_negocio"] != "") ? " '" . $GLOBALS["x_ing_fam_negocio"] . "'" : "NULL";
	$fieldList["`ing_fam_negocio`"] = $theValue;

	// Field ing_fam_otro_th
	$theValue = ($GLOBALS["x_ing_fam_otro_th"] != "") ? " '" . $GLOBALS["x_ing_fam_otro_th"] . "'" : "NULL";
	$fieldList["`ing_fam_otro_th`"] = $theValue;

	// Field ing_fam_1
	$theValue = ($GLOBALS["x_ing_fam_1"] != "") ? " '" . $GLOBALS["x_ing_fam_1"] . "'" : "NULL";
	$fieldList["`ing_fam_1`"] = $theValue;

	// Field ing_fam_2
	$theValue = ($GLOBALS["x_ing_fam_2"] != "") ? " '" . $GLOBALS["x_ing_fam_2"] . "'" : "NULL";
	$fieldList["`ing_fam_2`"] = $theValue;

	// Field ing_fam_deuda_1
	$theValue = ($GLOBALS["x_ing_fam_deuda_1"] != "") ? " '" . $GLOBALS["x_ing_fam_deuda_1"] . "'" : "NULL";
	$fieldList["`ing_fam_deuda_1`"] = $theValue;

	// Field ing_fam_deuda_2
	$theValue = ($GLOBALS["x_ing_fam_deuda_2"] != "") ? " '" . $GLOBALS["x_ing_fam_deuda_2"] . "'" : "NULL";
	$fieldList["`ing_fam_deuda_2`"] = $theValue;

	// Field ing_fam_total
	$theValue = ($GLOBALS["x_ing_fam_total"] != "") ? " '" . $GLOBALS["x_ing_fam_total"] . "'" : "NULL";
	$fieldList["`ing_fam_total`"] = $theValue;

	// Field ing_fam_cuales_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_1"]) : $GLOBALS["x_ing_fam_cuales_1"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ing_fam_cuales_1`"] = $theValue;

	// Field ing_fam_cuales_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_2"]) : $GLOBALS["x_ing_fam_cuales_2"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ing_fam_cuales_2`"] = $theValue;

	// Field ing_fam_cuales_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_3"]) : $GLOBALS["x_ing_fam_cuales_3"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ing_fam_cuales_3`"] = $theValue;

	// Field ing_fam_cuales_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_4"]) : $GLOBALS["x_ing_fam_cuales_4"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ing_fam_cuales_4`"] = $theValue;

	// Field ing_fam_cuales_5
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_5"]) : $GLOBALS["x_ing_fam_cuales_5"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ing_fam_cuales_5`"] = $theValue;

	// Field flujos_neg_ventas
	$theValue = ($GLOBALS["x_flujos_neg_ventas"] != "") ? " '" . $GLOBALS["x_flujos_neg_ventas"] . "'" : "NULL";
	$fieldList["`flujos_neg_ventas`"] = $theValue;

	// Field flujos_neg_proveedor_1
	$theValue = ($GLOBALS["x_flujos_neg_proveedor_1"] != "") ? " '" . $GLOBALS["x_flujos_neg_proveedor_1"] . "'" : "NULL";
	$fieldList["`flujos_neg_proveedor_1`"] = $theValue;

	// Field flujos_neg_proveedor_2
	$theValue = ($GLOBALS["x_flujos_neg_proveedor_2"] != "") ? " '" . $GLOBALS["x_flujos_neg_proveedor_2"] . "'" : "NULL";
	$fieldList["`flujos_neg_proveedor_2`"] = $theValue;

	// Field flujos_neg_proveedor_3
	$theValue = ($GLOBALS["x_flujos_neg_proveedor_3"] != "") ? " '" . $GLOBALS["x_flujos_neg_proveedor_3"] . "'" : "NULL";
	$fieldList["`flujos_neg_proveedor_3`"] = $theValue;

	// Field flujos_neg_proveedor_4
	$theValue = ($GLOBALS["x_flujos_neg_proveedor_4"] != "") ? " '" . $GLOBALS["x_flujos_neg_proveedor_4"] . "'" : "NULL";
	$fieldList["`flujos_neg_proveedor_4`"] = $theValue;

	// Field flujos_neg_gasto_1
	$theValue = ($GLOBALS["x_flujos_neg_gasto_1"] != "") ? " '" . $GLOBALS["x_flujos_neg_gasto_1"] . "'" : "NULL";
	$fieldList["`flujos_neg_gasto_1`"] = $theValue;

	// Field flujos_neg_gasto_2
	$theValue = ($GLOBALS["x_flujos_neg_gasto_2"] != "") ? " '" . $GLOBALS["x_flujos_neg_gasto_2"] . "'" : "NULL";
	$fieldList["`flujos_neg_gasto_2`"] = $theValue;

	// Field flujos_neg_gasto_3
	$theValue = ($GLOBALS["x_flujos_neg_gasto_3"] != "") ? " '" . $GLOBALS["x_flujos_neg_gasto_3"] . "'" : "NULL";
	$fieldList["`flujos_neg_gasto_3`"] = $theValue;

	// Field flujos_neg_cual_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_1"]) : $GLOBALS["x_flujos_neg_cual_1"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_1`"] = $theValue;

	// Field flujos_neg_cual_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_2"]) : $GLOBALS["x_flujos_neg_cual_2"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_2`"] = $theValue;

	// Field flujos_neg_cual_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_3"]) : $GLOBALS["x_flujos_neg_cual_3"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_3`"] = $theValue;

	// Field flujos_neg_cual_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_4"]) : $GLOBALS["x_flujos_neg_cual_4"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_4`"] = $theValue;

	// Field flujos_neg_cual_5
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_5"]) : $GLOBALS["x_flujos_neg_cual_5"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_5`"] = $theValue;

	// Field flujos_neg_cual_6
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_6"]) : $GLOBALS["x_flujos_neg_cual_6"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_6`"] = $theValue;

	// Field flujos_neg_cual_7
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_7"]) : $GLOBALS["x_flujos_neg_cual_7"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_7`"] = $theValue;

	// Field ingreso_negocio
	$theValue = ($GLOBALS["x_ingreso_negocio"] != "") ? " '" . $GLOBALS["x_ingreso_negocio"] . "'" : "NULL";
	$fieldList["`ingreso_negocio`"] = $theValue;

	// Field inv_neg_fija_conc_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_fija_conc_1"]) : $GLOBALS["x_inv_neg_fija_conc_1"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_fija_conc_1`"] = $theValue;

	// Field inv_neg_fija_conc_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_fija_conc_2"]) : $GLOBALS["x_inv_neg_fija_conc_2"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_fija_conc_2`"] = $theValue;

	// Field inv_neg_fija_conc_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_fija_conc_3"]) : $GLOBALS["x_inv_neg_fija_conc_3"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_fija_conc_3`"] = $theValue;

	// Field inv_neg_fija_conc_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_fija_conc_4"]) : $GLOBALS["x_inv_neg_fija_conc_4"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_fija_conc_4`"] = $theValue;

	// Field inv_neg_fija_valor_1
	$theValue = ($GLOBALS["x_inv_neg_fija_valor_1"] != "") ? " '" . $GLOBALS["x_inv_neg_fija_valor_1"] . "'" : "NULL";
	$fieldList["`inv_neg_fija_valor_1`"] = $theValue;

	// Field inv_neg_fija_valor_2
	$theValue = ($GLOBALS["x_inv_neg_fija_valor_2"] != "") ? " '" . $GLOBALS["x_inv_neg_fija_valor_2"] . "'" : "NULL";
	$fieldList["`inv_neg_fija_valor_2`"] = $theValue;

	// Field inv_neg_fija_valor_3
	$theValue = ($GLOBALS["x_inv_neg_fija_valor_3"] != "") ? " '" . $GLOBALS["x_inv_neg_fija_valor_3"] . "'" : "NULL";
	$fieldList["`inv_neg_fija_valor_3`"] = $theValue;

	// Field inv_neg_fija_valor_4
	$theValue = ($GLOBALS["x_inv_neg_fija_valor_4"] != "") ? " '" . $GLOBALS["x_inv_neg_fija_valor_4"] . "'" : "NULL";
	$fieldList["`inv_neg_fija_valor_4`"] = $theValue;

	// Field inv_neg_total_fija
	$theValue = ($GLOBALS["x_inv_neg_total_fija"] != "") ? " '" . $GLOBALS["x_inv_neg_total_fija"] . "'" : "NULL";
	$fieldList["`inv_neg_total_fija`"] = $theValue;

	// Field inv_neg_var_conc_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_var_conc_1"]) : $GLOBALS["x_inv_neg_var_conc_1"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_var_conc_1`"] = $theValue;

	// Field inv_neg_var_conc_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_var_conc_2"]) : $GLOBALS["x_inv_neg_var_conc_2"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_var_conc_2`"] = $theValue;

	// Field inv_neg_var_conc_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_var_conc_3"]) : $GLOBALS["x_inv_neg_var_conc_3"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_var_conc_3`"] = $theValue;

	// Field inv_neg_var_conc_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_var_conc_4"]) : $GLOBALS["x_inv_neg_var_conc_4"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_var_conc_4`"] = $theValue;

	// Field inv_neg_var_valor_1
	$theValue = ($GLOBALS["x_inv_neg_var_valor_1"] != "") ? " '" . $GLOBALS["x_inv_neg_var_valor_1"] . "'" : "NULL";
	$fieldList["`inv_neg_var_valor_1`"] = $theValue;

	// Field inv_neg_var_valor_2
	$theValue = ($GLOBALS["x_inv_neg_var_valor_2"] != "") ? " '" . $GLOBALS["x_inv_neg_var_valor_2"] . "'" : "NULL";
	$fieldList["`inv_neg_var_valor_2`"] = $theValue;

	// Field inv_neg_var_valor_3
	$theValue = ($GLOBALS["x_inv_neg_var_valor_3"] != "") ? " '" . $GLOBALS["x_inv_neg_var_valor_3"] . "'" : "NULL";
	$fieldList["`inv_neg_var_valor_3`"] = $theValue;

	// Field inv_neg_var_valor_4
	$theValue = ($GLOBALS["x_inv_neg_var_valor_4"] != "") ? " '" . $GLOBALS["x_inv_neg_var_valor_4"] . "'" : "NULL";
	$fieldList["`inv_neg_var_valor_4`"] = $theValue;

	// Field inv_neg_total_var
	$theValue = ($GLOBALS["x_inv_neg_total_var"] != "") ? " '" . $GLOBALS["x_inv_neg_total_var"] . "'" : "NULL";
	$fieldList["`inv_neg_total_var`"] = $theValue;

	// Field inv_neg_activos_totales
	$theValue = ($GLOBALS["x_inv_neg_activos_totales"] != "") ? " '" . $GLOBALS["x_inv_neg_activos_totales"] . "'" : "NULL";
	$fieldList["`inv_neg_activos_totales`"] = $theValue;

	// Field fecha
	$theValue = ($GLOBALS["x_fecha"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha"]) . "'" : "NULL";
	$fieldList["`fecha`"] = $theValue;
	
	// insert into database
	$strsql = "INSERT INTO `adquisicionmaquinaria` (";
	$strsql .= implode(",", array_keys($fieldList));
	$strsql .= ") VALUES (";
	$strsql .= implode(",", array_values($fieldList));
	$strsql .= ")";
	phpmkr_query($strsql, $conn) or die ("EEROR AL EJECUTAR QUERY 1 INSERT :" .phpmkr_error()."SENETENCIA :".$strsql);
	
	
	$_SESSION["mensajeAlta"] = "REGISTRO EXITOSO";
	
	
	//if enviados
	}

?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link rel="stylesheet" type="text/css" href="../cssFormatos/cssFormatos.css" />

<script type="text/javascript" src="admin/ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<script language="javascript">
window.onload = function (){
	document.getElementById("enviaFormulario").onclick  =  EW_checkMyForm;
	
	
	
	
function EW_checkMyForm() {
	
	EW_this = document.frmAdqMaquinaria;
	validada = true;
	
if (EW_this.x_nombre && !EW_hasValue(EW_this.x_nombre, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre, "TEXT", "Please enter required field - nombre"))
		validada = false;
		alert("entro nombre"+validada);
}
if (EW_this.x_rfc && !EW_hasValue(EW_this.x_rfc, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_rfc, "TEXT", "Please enter required field - rfc"))
		validada = false; 
		alert("entro rfc"+validada);
}
if (EW_this.x_curp && !EW_hasValue(EW_this.x_curp, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_curp, "TEXT", "Please enter required field - curp"))
		validada = false; 
		alert("entro curp"+validada);
}
/*
if (EW_this.x_fecha_nacimiento && !EW_hasValue(EW_this.x_fecha_nacimiento, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_nacimiento, "TEXT", "Please enter required field - fecha nacimiento"))
		validada = false;
		alert("entro nfec"+validada);
}
if (EW_this.x_fecha_nacimiento && !EW_checkdate(EW_this.x_fecha_nacimiento.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_nacimiento, "TEXT", "Incorrect date, format = yyyy/mm/dd - fecha nacimiento"))
		{validada = false; 
		alert("entro nacimiento"+validada);}
}*/

if (EW_this.x_integrantes_familia && !EW_hasValue(EW_this.x_integrantes_familia, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_integrantes_familia, "TEXT", "Please enter required field - integrantes familia"))
		{validada = false; 
		alert("entro integrantes"+validada);}
}
if (EW_this.x_integrantes_familia && !EW_checkinteger(EW_this.x_integrantes_familia.value)) {
	if (!EW_onError(EW_this, EW_this.x_integrantes_familia, "TEXT", "Incorrect integer - integrantes familia"))
		{validada = false; 
		alert("entro integrantes"+validada);} 
}
if (EW_this.x_dependientes && !EW_hasValue(EW_this.x_dependientes, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_dependientes, "TEXT", "Please enter required field - dependientes"))
		{validada = false; 
		alert("entro dependientes"+validada);} 
}
if (EW_this.x_dependientes && !EW_checkinteger(EW_this.x_dependientes.value)) {
	if (!EW_onError(EW_this, EW_this.x_dependientes, "TEXT", "Incorrect integer - dependientes"))
		validada = false;  
}
if (EW_this.x_correo_electronico && !EW_hasValue(EW_this.x_correo_electronico, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_correo_electronico, "TEXT", "Please enter required field - correo electronico"))
		{validada = false; 
		alert("mail"+validada);} 
}
if (EW_this.x_correo_electronico && !EW_checkemail(EW_this.x_correo_electronico.value)) {
	if (!EW_onError(EW_this, EW_this.x_correo_electronico, "TEXT", "Incorrect email - correo electronico"))
		{validada = false; 
		alert("mail"+validada);}  
}
if (EW_this.x_calle_domicilio && !EW_hasValue(EW_this.x_calle_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_calle_domicilio, "TEXT", "Please enter required field - calle domicilio"))
		{validada = false; 
		alert("entro calle dom"+validada);}
}
if (EW_this.x_colonia_domicilio && !EW_hasValue(EW_this.x_colonia_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_colonia_domicilio, "TEXT", "Please enter required field - colonia domicilio"))
		{validada = false; 
		alert("entro colonia "+validada);} 
}
if (EW_this.x_entidad_domicilio && !EW_hasValue(EW_this.x_entidad_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_entidad_domicilio, "TEXT", "Please enter required field - entidad domicilio"))
		validada = false; 
}
if (EW_this.x_codigo_postal_domicilio && !EW_hasValue(EW_this.x_codigo_postal_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal_domicilio, "TEXT", "Please enter required field - codigo postal domicilio"))
		validada = false; 
}
if (EW_this.x_codigo_postal_domicilio && !EW_checkinteger(EW_this.x_codigo_postal_domicilio.value)) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal_domicilio, "TEXT", "Incorrect integer - codigo postal domicilio"))
		validada = false;  
}
if (EW_this.x_ubicacion_domicilio && !EW_hasValue(EW_this.x_ubicacion_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ubicacion_domicilio, "TEXT", "Please enter required field - ubicacion domicilio"))
		validada = false; 
}
if (EW_this.x_tipo_vivienda && !EW_hasValue(EW_this.x_tipo_vivienda, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tipo_vivienda, "TEXT", "Please enter required field - tipo vivienda"))
		validada = false; 
}
if (EW_this.x_renta_mensula_domicilio && !EW_checknumber(EW_this.x_renta_mensula_domicilio.value)) {
	if (!EW_onError(EW_this, EW_this.x_renta_mensula_domicilio, "TEXT", "Incorrect floating point number - renta mensula domicilio"))
		validada = false;  
}
if (EW_this.x_giro_negocio && !EW_hasValue(EW_this.x_giro_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_giro_negocio, "TEXT", "Please enter required field - giro negocio"))
		{validada = false; 
		alert("entro giro "+validada);}  
}
if (EW_this.x_calle_negocio && !EW_hasValue(EW_this.x_calle_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_calle_negocio, "TEXT", "Please enter required field - calle negocio"))
		{validada = false; 
		alert("entro ccalle "+validada);} 
}
if (EW_this.x_colonia_negocio && !EW_hasValue(EW_this.x_colonia_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_colonia_negocio, "TEXT", "Please enter required field - colonia negocio"))
		{validada = false; 
		alert("entro colonia neg "+validada);} 
}
if (EW_this.x_entidad_negocio && !EW_hasValue(EW_this.x_entidad_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_entidad_negocio, "TEXT", "Please enter required field - entidad negocio"))
		validada = false; 
}
if (EW_this.x_ubicacion_negocio && !EW_hasValue(EW_this.x_ubicacion_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ubicacion_negocio, "TEXT", "Please enter required field - ubicacion negocio"))
		{validada = false; 
		alert("entro ubi neg "+validada);}  
}
if (EW_this.x_codigo_postal_negocio && !EW_checkinteger(EW_this.x_codigo_postal_negocio.value)) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal_negocio, "TEXT", "Incorrect integer - codigo postal negocio"))
		validada = false;  
}
if (EW_this.x_renta_mensual && !EW_checknumber(EW_this.x_renta_mensual.value)) {
	if (!EW_onError(EW_this, EW_this.x_renta_mensual, "TEXT", "Incorrect floating point number - renta mensual"))
		{validada = false; 
		alert("entro renta mensual "+validada);} 
}
/*
if (EW_this.x_solicitud_compra && !EW_hasValue(EW_this.x_solicitud_compra, "TEXTAREA" )) {
	if (!EW_onError(EW_this, EW_this.x_solicitud_compra, "TEXTAREA", "Please enter required field - solicitud compra"))
		validada = false; 
} */

if (EW_this.x_referencia_com_1 && !EW_hasValue(EW_this.x_referencia_com_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_referencia_com_1, "TEXT", "Please enter required field - referencia com 1"))
		{validada = false; 
		alert("ref 1 "+validada);} 
}
if (EW_this.x_tel_referencia_1 && !EW_hasValue(EW_this.x_tel_referencia_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tel_referencia_1, "TEXT", "Please enter required field - tel referencia 1"))
		{validada = false; 
		alert("entro ref te 1 "+validada);}  
}
if (EW_this.x_parentesco_ref_1 && !EW_hasValue(EW_this.x_parentesco_ref_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_ref_1, "TEXT", "Please enter required field - parentesco ref 1"))
		{validada = false; 
		alert("entro parentesco ref 1 "+validada);} 
}




if (EW_this.x_ing_fam_negocio && !EW_hasValue(EW_this.x_ing_fam_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_negocio, "TEXT", "Please enter required field - ing fam negocio"))
		{validada = false; 
		alert("entro in fam neg "+validada);}  
}
/*
if (EW_this.x_ing_fam_negocio && !EW_checknumber(EW_this.x_ing_fam_negocio.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_negocio, "TEXT", "Incorrect floating point number - ing fam negocio"))
		validada = false;  
}*/

if (EW_this.x_ing_fam_otro_th && !EW_hasValue(EW_this.x_ing_fam_otro_th, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_otro_th, "TEXT", "Please enter required field - ing fam otro th"))
		{validada = false; 
		alert("entro otro th "+validada);} 
}
/*
if (EW_this.x_ing_fam_otro_th && !EW_checknumber(EW_this.x_ing_fam_otro_th.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_otro_th, "TEXT", "Incorrect floating point number - ing fam otro th"))
		validada = false;  
}*/
if (EW_this.x_ing_fam_1 && !EW_hasValue(EW_this.x_ing_fam_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_1, "TEXT", "Please enter required field - ing fam 1"))
		{validada = false; 
		alert("entro x_ing_fam_1 "+validada);}  
}
/*
if (EW_this.x_ing_fam_1 && !EW_checknumber(EW_this.x_ing_fam_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_1, "TEXT", "Incorrect floating point number - ing fam 1"))
		validada = false;  
}*/
if (EW_this.x_ing_fam_2 && !EW_hasValue(EW_this.x_ing_fam_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_2, "TEXT", "Please enter required field - ing fam 2"))
		{validada = false; 
		alert("entro x_ing_fam_2 "+validada);} 
}
/*
if (EW_this.x_ing_fam_2 && !EW_checknumber(EW_this.x_ing_fam_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_2, "TEXT", "Incorrect floating point number - ing fam 2"))
		validada = false;  
}*/
if (EW_this.x_ing_fam_deuda_1 && !EW_hasValue(EW_this.x_ing_fam_deuda_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_1, "TEXT", "Please enter required field - ing fam deuda 1"))
		{validada = false; 
		alert("entro x_ing_fadeuda 1 "+validada);} 
}
/*
if (EW_this.x_ing_fam_deuda_1 && !EW_checknumber(EW_this.x_ing_fam_deuda_1.value)) {
	alert("ENTRO A CHECHNUMBER FAM DEU 1");
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_1, "TEXT", "Incorrect floating point number - ing fam deuda 1"))
	{
		alert("ENTRO A INCORRECT FLOAT FAM DEU 1");
		validada = false;
		
		}
		  
}*/

if (EW_this.x_ing_fam_deuda_2 && !EW_hasValue(EW_this.x_ing_fam_deuda_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_2, "TEXT", "Please enter required field - ing fam deuda 2"))
		{validada = false; 
		alert("entro x_dueda_2 "+validada);}  
}
/*
if (EW_this.x_ing_fam_deuda_2 && !EW_checknumber(EW_this.x_ing_fam_deuda_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_2, "TEXT", "Incorrect floating point number - ing fam deuda 2"))
		validada = false;  
}*/
if (EW_this.x_ing_fam_total && !EW_hasValue(EW_this.x_ing_fam_total, "TEXT" )) {
	alert("tengo el valor de ingreso familair total" + EW_this.x_ing_fam_total.value)
	if (!EW_onError(EW_this, EW_this.x_ing_fam_total, "TEXT", "Please enter required field - ing fam total"))
		{validada = false; 
		alert("entro x_fam_tota_1 "+validada);}  
}
/*
if (EW_this.x_ing_fam_total && !EW_checknumber(EW_this.x_ing_fam_total.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_total, "TEXT", "Incorrect floating point number - ing fam total"))
		validada = false;  
}*/
if (EW_this.x_flujos_neg_ventas && !EW_hasValue(EW_this.x_flujos_neg_ventas, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_ventas, "TEXT", "Please enter required field - flujos neg ventas"))
		{validada = false; 
		alert("entro xflu_neg_vn "+validada);}  
}
/*
if (EW_this.x_flujos_neg_ventas && !EW_checknumber(EW_this.x_flujos_neg_ventas.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_ventas, "TEXT", "Incorrect floating point number - flujos neg ventas"))
		validada = false;  
}*/
if (EW_this.x_flujos_neg_proveedor_1 && !EW_hasValue(EW_this.x_flujos_neg_proveedor_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_1, "TEXT", "Please enter required field - flujos neg proveedor 1"))
		{validada = false; 
		alert("entro x_ifluj_neg_p1 "+validada);} 
}
/*
if (EW_this.x_flujos_neg_proveedor_1 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_1, "TEXT", "Incorrect floating point number - flujos neg proveedor 1"))
		validada = false;  
}*/
if (EW_this.x_flujos_neg_proveedor_2 && !EW_hasValue(EW_this.x_flujos_neg_proveedor_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_2, "TEXT", "Please enter required field - flujos neg proveedor 2"))
		{validada = false; 
		alert("entro x_ifluj_neg_p1 "+validada);}  
}
/*
if (EW_this.x_flujos_neg_proveedor_2 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_2, "TEXT", "Incorrect floating point number - flujos neg proveedor 2"))
		validada = false;  
}*/
if (EW_this.x_flujos_neg_proveedor_3 && !EW_hasValue(EW_this.x_flujos_neg_proveedor_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_3, "TEXT", "Please enter required field - flujos neg proveedor 3"))
		{validada = false; 
		alert("entro x_ifluj_neg_p1 "+validada);} 
}
/*
if (EW_this.x_flujos_neg_proveedor_3 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_3, "TEXT", "Incorrect floating point number - flujos neg proveedor 3"))
		validada = false;  
}*/
if (EW_this.x_flujos_neg_proveedor_4 && !EW_hasValue(EW_this.x_flujos_neg_proveedor_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_4, "TEXT", "Please enter required field - flujos neg proveedor 4"))
		{validada = false; 
		alert("entro x_ifluj_neg_p1 "+validada);} 
}
/*
if (EW_this.x_flujos_neg_proveedor_4 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_4.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_4, "TEXT", "Incorrect floating point number - flujos neg proveedor 4"))
		validada = false;  
}*/
if (EW_this.x_flujos_neg_gasto_1 && !EW_hasValue(EW_this.x_flujos_neg_gasto_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_1, "TEXT", "Please enter required field - flujos neg gasto 1"))
		{validada = false; 
		alert("entro x_ifluj_gasto_1 "+validada);} 
}
/*
if (EW_this.x_flujos_neg_gasto_1 && !EW_checknumber(EW_this.x_flujos_neg_gasto_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_1, "TEXT", "Incorrect floating point number - flujos neg gasto 1"))
		validada = false;  
}*/
if (EW_this.x_flujos_neg_gasto_2 && !EW_hasValue(EW_this.x_flujos_neg_gasto_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_2, "TEXT", "Please enter required field - flujos neg gasto 2"))
		{validada = false; 
		alert("entro x_ifluj_gasto_1 "+validada);}  
}
/*
if (EW_this.x_flujos_neg_gasto_2 && !EW_checknumber(EW_this.x_flujos_neg_gasto_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_2, "TEXT", "Incorrect floating point number - flujos neg gasto 2"))
		validada = false;  
}*/
if (EW_this.x_flujos_neg_gasto_3 && !EW_hasValue(EW_this.x_flujos_neg_gasto_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_3, "TEXT", "Please enter required field - flujos neg gasto 3"))
		{validada = false; 
		alert("entro x_ifluj_gasto_1 "+validada);}  
}
/*
if (EW_this.x_flujos_neg_gasto_3 && !EW_checknumber(EW_this.x_flujos_neg_gasto_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_3, "TEXT", "Incorrect floating point number - flujos neg gasto 3"))
		validada = false;  
}

//////////////////CHECAR ESTE CASO//////////////
if (EW_this.x_ingreso_negocio && !EW_checknumber(EW_this.x_ingreso_negocio.value)) {
	if (!EW_onError(EW_this, EW_this.x_ingreso_negocio, "TEXT", "Incorrect floating point number - ingreso negocio"))
		validada = false;  
}*/
if (EW_this.x_inv_neg_fija_valor_1 && !EW_hasValue(EW_this.x_inv_neg_fija_valor_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_1, "TEXT", "Please enter required field - inv neg fija valor 1"))
		{validada = false; 
		alert("entro x_fija_valor_1 "+validada);}  
}
/*
if (EW_this.x_inv_neg_fija_valor_1 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_1, "TEXT", "Incorrect floating point number - inv neg fija valor 1"))
		validada = false;  
}*/
if (EW_this.x_inv_neg_fija_valor_2 && !EW_hasValue(EW_this.x_inv_neg_fija_valor_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_2, "TEXT", "Please enter required field - inv neg fija valor 2"))
		{validada = false; 
		alert("entro x_fija_valor_1 "+validada);}  
}
/*
if (EW_this.x_inv_neg_fija_valor_2 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_2, "TEXT", "Incorrect floating point number - inv neg fija valor 2"))
		validada = false;  
}*/
if (EW_this.x_inv_neg_fija_valor_3 && !EW_hasValue(EW_this.x_inv_neg_fija_valor_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_3, "TEXT", "Please enter required field - inv neg fija valor 3"))
		{validada = false; 
		alert("entro x_fija_valor_1 "+validada);}  
}
/*
if (EW_this.x_inv_neg_fija_valor_3 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_3, "TEXT", "Incorrect floating point number - inv neg fija valor 3"))
		validada = false;  
}*/
if (EW_this.x_inv_neg_fija_valor_4 && !EW_hasValue(EW_this.x_inv_neg_fija_valor_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_4, "TEXT", "Please enter required field - inv neg fija valor 4"))
		{validada = false; 
		alert("entro x_fija_valor_1 "+validada);} 
}
/*
if (EW_this.x_inv_neg_fija_valor_4 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_4.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_4, "TEXT", "Incorrect floating point number - inv neg fija valor 4"))
		validada = false;  
}*/
if (EW_this.x_inv_neg_total_fija && !EW_hasValue(EW_this.x_inv_neg_total_fija, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_fija, "TEXT", "Please enter required field - inv neg total fija"))
		{validada = false; 
		alert("entro x_fija_valor_tot "+validada);}  
}
/*
if (EW_this.x_inv_neg_total_fija && !EW_checknumber(EW_this.x_inv_neg_total_fija.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_fija, "TEXT", "Incorrect floating point number - inv neg total fija"))
		validada = false;  
}*/
if (EW_this.x_inv_neg_var_valor_1 && !EW_hasValue(EW_this.x_inv_neg_var_valor_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_1, "TEXT", "Please enter required field - inv neg var valor 1"))
		{validada = false; 
		alert("entro x_variable_valor_1 "+validada);}  
}
/*
if (EW_this.x_inv_neg_var_valor_1 && !EW_checknumber(EW_this.x_inv_neg_var_valor_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_1, "TEXT", "Incorrect floating point number - inv neg var valor 1"))
		validada = false;  
}*/
if (EW_this.x_inv_neg_var_valor_2 && !EW_hasValue(EW_this.x_inv_neg_var_valor_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_2, "TEXT", "Please enter required field - inv neg var valor 2"))
		{validada = false; 
		alert("entro x_variable_valor_1 "+validada);}   
}
/*
if (EW_this.x_inv_neg_var_valor_2 && !EW_checknumber(EW_this.x_inv_neg_var_valor_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_2, "TEXT", "Incorrect floating point number - inv neg var valor 2"))
		validada = false;  
}*/
if (EW_this.x_inv_neg_var_valor_3 && !EW_hasValue(EW_this.x_inv_neg_var_valor_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_3, "TEXT", "Please enter required field - inv neg var valor 3"))
		{validada = false; 
		alert("entro x_variable_valor_1 "+validada);}   
}
/*
if (EW_this.x_inv_neg_var_valor_3 && !EW_checknumber(EW_this.x_inv_neg_var_valor_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_3, "TEXT", "Incorrect floating point number - inv neg var valor 3"))
		validada = false;  
}*/
if (EW_this.x_inv_neg_var_valor_4 && !EW_hasValue(EW_this.x_inv_neg_var_valor_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_4, "TEXT", "Please enter required field - inv neg var valor 4"))
		{validada = false; 
		alert("entro x_variable_valor_1 "+validada);}  
}
/*
if (EW_this.x_inv_neg_var_valor_4 && !EW_checknumber(EW_this.x_inv_neg_var_valor_4.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_4, "TEXT", "Incorrect floating point number - inv neg var valor 4"))
		validada = false;  
}*/
if (EW_this.x_inv_neg_total_var && !EW_hasValue(EW_this.x_inv_neg_total_var, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_var, "TEXT", "Please enter required field - inv neg total var"))
		{validada = false; 
		alert("entro x_variabletotal_1 "+validada);}   
}
/*
if (EW_this.x_inv_neg_total_var && !EW_checknumber(EW_this.x_inv_neg_total_var.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_var, "TEXT", "Incorrect floating point number - inv neg total var"))
		validada = false;  
}*/
if (EW_this.x_inv_neg_activos_totales && !EW_hasValue(EW_this.x_inv_neg_activos_totales, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_activos_totales, "TEXT", "Please enter required field - inv neg activos totales"))
		{validada = false; 
		alert("entro x_act_totales "+validada);}  
}
/*
if (EW_this.x_inv_neg_activos_totales && !EW_checknumber(EW_this.x_inv_neg_activos_totales.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_activos_totales, "TEXT", "Incorrect floating point number - inv neg activos totales"))
		validada = false;  
}*/

/*
if (EW_this.x_fecha && !EW_hasValue(EW_this.x_fecha, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha, "TEXT", "Please enter required field - fecha"))
		{validada = false; 
		alert("entro x_fechaur_1 "+validada);}  
}
if (EW_this.x_fecha && !EW_checkdate(EW_this.x_fecha.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha, "TEXT", "Incorrect date, format = yyyy/mm/dd - fecha"))
		{validada = false; 
		alert("entro xfechau "+validada);}   
}*/

if(validada == true){
	alert("entro al if validada");
	
	EW_this.submit();	
	 alert("documento enviado");
	//EW_this.getElementById("seEnvioFormulario").style.display="block"; 
	
}
}	
	
	
	}//window.onload





</script>


</head>

<body>
<div id="contenedor">
<form name="frmAdqMaquinaria" id="frmAdqMaquinaria" method="post" action="old_adquisicionMaquinaria2.php">
<input type="hidden" id="enviados" name="enviados" value="datosEnviados" />
<div id="primeraParte">
  <table width="90%">
  <tr>
    <td colspan="10" id="tableHead">Datos personales</td>
  </tr>
  <tr>
    <td colspan="2">Nombre</td>
    <td colspan="8"><input type="text" name="x_nombre" id="x_nombre" value="<?php echo htmlspecialchars(@$x_nombre) ?>"></td>
    </tr>
  <tr>
    <td colspan="2">RFC</td>
    <td width="25%"><input type="text" name="x_rfc" id="x_rfc" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_rfc) ?>"></td>
    <td width="8%">&nbsp;</td>
    <td width="12%">&nbsp;</td>
    <td colspan="2">Curp</td>
    <td colspan="3"><input type="text" id="x_curp" name="x_curp" size="30"  /></td>
    </tr>
  <tr>
    <td colspan="2">Fecha Nacimeinto</td>
    <td><input type="text" id="x_fecha_nacimiento" name="x_fecha_nacimiento" size="20" /></td>
    <td>Sexo</td>
    <td><select name="x_sexo" id="x_sexo">
      <option value="MASCULINO">Masculino</option>
      <option value="FEMENINO">Femenino</option>
    </select></td>
    <td colspan="2">Integrantes Famila</td>
    <td colspan="3"><input type="text" id="x_integrantes_familia" name="x_integrantes_familia" size="20" /></td>
    </tr>
  <tr>
    <td colspan="2">Dependientes</td>
    <td><input type="text" id="x_dependientes" name="x_dependientes"  size="20"/></td>
    <td colspan="2">Correo Electronico</td>
    <td colspan="5"><input type="text"  id="x_correo_electronico" name="x_correo_electronico" size="50" /></td>
    </tr>
  <tr>
    <td colspan="2">Esposo</td>
    <td colspan="5"><input type="text" id="x_esposa" name="x_esposa" size="50" /></td>
    <td width="12%">&nbsp;</td>
    <td width="4%">&nbsp;</td>
    <td width="6%">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="10" id="tableHead">Domicilio particular</td>
    </tr>
  <tr>
    <td colspan="2">Calle</td>
    <td colspan="6"><input type="text" id="x_calle_domicilio" name="x_calle_domicilio" size="80" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">Colonia</td>
    <td><input type="text" id="x_colonia_domicilio" name="x_colonia_domicilio" size="40" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">Codigo Postal</td>
    <td><input type="text" id="x_codigo_postal_domicilio" name="x_codigo_postal_domicilio" size="15" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">Entidad</td>
    <td><input type="text" id="x_entidad_domicilio" name="x_entidad_domicilio" size="30" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">Municipio</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">Ubicacion</td>
    <td colspan="5"><input type="text" id="x_ubicacion_domicilio" name="x_ubicacion_domicilio" size="40" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">Tipo Ubicion</td>
    <td><input type="text" id="x_tipo_vivienda" name="x_tipo_vivienda" size="45" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">Antiguedad</td>
    <td><input type="text" id="x_antiguedad" name="x_antiguedad" size="20" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="3%">&nbsp;</td>
    <td width="14%">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">Tel. Arrendatario</td>
    <td><input type="text" id="x_tel_arrendatario_domicilio" name="x_tel_arrendatario_domicilio" size="20" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">Telefono Domicilio</td>
    <td><input type="text" id="x_telefono_domicilio" name="x_telefono_domicilio" size="20" /> </td>
    <td>otro Tel</td>
    <td><input type="text" id="x_otro_tel_domicilio_1" name="x_otro_tel_domicilio_1" size="20" /></td>
    <td colspan="2">Renta Mensual</td>
    <td><input type="text" id="x_renta_mensula_domicilio" name="x_renta_mensula_domicilio" size="20" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">Celular</td>
    <td><input type="text" id="x_celular" name="x_celular" size="20" /></td>
    <td>otro Tel</td>
    <td><input type="text" id="x_otro_telefono_domicilio_2" name="x_otro_telefono_domicilio_2" size="22" /></td>
    <td width="5%">&nbsp;</td>
    <td width="11%">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="10" id="tableHead">Domicilio Negocio</td>
    </tr>
  <tr>
    <td colspan="2">Giro</td>
    <td><input type="text" id="x_giro_negocio" name="x_giro_negocio" size="40" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">Calle</td>
    <td colspan="6"><input type="text" id="x_calle_negocio" name="x_calle_negocio" size="80" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">Colonia</td>
    <td><input type="text" id="x_colonia_negocio" name="x_colonia_negocio" size="40" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">Codigo Postal</td>
    <td><input type="text" id="x_codigo_postal_negocio" name="x_codigo_postal_negocio" size="20" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">Entidad</td>
    <td><input type="text" id="x_entidad_negocio" name="x_entidad_negocio" size="40" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">Municipio</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">UBICACION</td>
    <td colspan="5"><input type="text" id="x_ubicacion_negocio" name="x_ubicacion_negocio" size="80" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">Tipo Local</td>
    <td><input type="text" id="x_tipo_local_negocio" name="x_tipo_local_negocio" size="40" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">Antiguedad Negocio</td>
    <td><input type="text" id="x_antiguedad_negocio" name="x_antiguedad_negocio" size="20" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">Tel. Arrendatario</td>
    <td><input type="text" id="x_tel_arrendatario_negocio" name="x_tel_arrendatario_negocio" size="20" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">Telefono</td>
    <td><input type="text" id="x_tel_negocio" name="x_tel_negocio" size="20" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">Renta Mensual</td>
    <td><input type="text" id="x_renta_mensual" name="x_renta_mensual" size="20" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="10" id="tableHead">Solicitud Compra</td>
    </tr>
  <tr>
    <td colspan="10"><textarea cols="70" rows="4" id="x_solicitud_compra" name="x_solicitud_compra"> </textarea></td>
    </tr>
  <tr>
    <td colspan="10">&nbsp;</td>
    </tr></table>
  
  <table width="90%">
  <tr>
    <td colspan="6" id="tableHead"><p>Referencias Comerciales</p></td>
    </tr>
  <tr>
    <td width="3%">1</td>
    <td width="15%"><input type="text" id="x_referencia_com_1" name="x_referencia_com_1" size="40" /></td>
    <td width="10%">Telefono</td>
    <td width="27%"><input type="text" id="x_tel_referencia_1" name="x_tel_referencia_1" size="40" /></td>
    <td width="23%">Parentesco</td>
    <td><input type="text" id="x_parentesco_ref_1" name="x_parentesco_ref_1" size="40" /></td>
    </tr>
  <tr>
    <td>2</td>
    <td><input type="text" id="x_referencia_com_2" name="x_referencia_com_2" size="40" /></td>
    <td>Telefono</td>
    <td><input type="text" name="x_tel_referencia_2" id="x_tel_referencia_2" size="40"  /></td>
    <td>Parentesco</td>
    <td><input type="text" id="x_parentesco_ref_2" name="x_parentesco_ref_2" size="40" /></td>
    </tr>
    <tr>
    <td>3</td>
    <td><input type="text" id="x_referencia_com_3" name="x_referencia_com_3" size="40" /></td>
    <td>Telefono</td>
    <td><input type="text" id="x_tel_referencia_3" name="x_tel_referencia_3" size="40" /></td>
    <td>Parentesco</td>
    <td><input type="text" id="x_parentesco_ref_3" name="x_parentesco_ref_3" size="40" /></td>
    </tr>
    <tr>
    <td>4</td>
    <td><input type="text" id="x_referencia_com_4" name="x_referencia_com_4" size="40" /></td>
    <td>Telefono</td>
    <td><input type="text" id="x_tel_referencia_4" name="x_tel_referencia_4" size="40" /></td>
    <td>Parentesco</td>
    <td><input type="text" id="x_parentesco_ref_4" name="x_parentesco_ref_4" size="40" /></td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
    <tr>
    <td colspan="6">&nbsp;</td>
    </tr>
    
</table>
</div><!-- primera Parte-->

<table width="90%">
  <tr>
    <td colspan="10" id="tableHead">DATOS PERSONALES DEL TITULAR Ó AVAL</td>
    </tr>
  <tr>
    <td width="5%">&nbsp;</td>
    <td width="11%">&nbsp;</td>
    <td width="16%">Ingreso Negocio</td>
    <td width="10%"><input type="text" id="x_ing_fam_negocio" name="x_ing_fam_negocio" size="40" /></td>
    <td width="9%">&nbsp;</td>
    <td width="11%">&nbsp;</td>
    <td width="10%">&nbsp;</td>
    <td width="9%">&nbsp;</td>
    <td width="8%">&nbsp;</td>
    <td width="11%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Otro Ingreso TH</td>
    <td><input type="text" id="x_ing_fam_otro_th" name="x_ing_fam_otro_th" size="40" /></td>
    <td>&nbsp;</td>
    <td>Cuales</td>
    <td><input type="text" id="x_ing_fam_cuales_1" name="x_ing_fam_cuales_1" size="40" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Ingreso Familiar 1</td>
    <td> <input type="text" id="x_ing_fam_1" name="x_ing_fam_1" size="40" /></td>
    <td>&nbsp;</td>
    <td>Cuales</td>
    <td><input type="text" id="x_ing_fam_cuales_2" name="x_ing_fam_cuales_2" size="40" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Ingreso Familiar 2</td>
    <td><input type="text" id="x_ing_fam_2" name="x_ing_fam_2" size="40" /></td>
    <td>&nbsp;</td>
    <td>Cuales</td>
    <td><input type="text" id="x_ing_fam_cuales_3" name="x_ing_fam_cuales_3" size="40" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Deuda 1</td>
    <td><input type="text" id="x_ing_fam_deuda_1"  name="x_ing_fam_deuda_1" size="40" /></td>
    <td>&nbsp;</td>
    <td>Cuales</td>
    <td><input type="text" id="x_ing_fam_cuales_4" name="x_ing_fam_cuales_4" size="40" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Deuda 2</td>
    <td><input type="text" id="x_ing_fam_deuda_2" name="x_ing_fam_deuda_2" size="40" /></td>
    <td>&nbsp;</td>
    <td>Cuales</td>
    <td><input type="text" id="x_ing_fam_cuales_5" name="x_ing_fam_cuales_5" size="40" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Total</td>
    <td><input type="text" id="x_ing_fam_total" name="x_ing_fam_total" size="40" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="10" id="tableHead">FLUJOS NEGOCIO</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Ventas</td>
    <td><input type="text" id="x_flujos_neg_ventas" name="x_flujos_neg_ventas" size="40" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Proovedor 1</td>
    <td><input type="text" id="x_flujos_neg_proveedor_1" name="x_flujos_neg_proveedor_1" size="40" /></td>
    <td>&nbsp;</td>
    <td>Cual</td>
    <td><input type="text" id="x_flujos_neg_cual_1" name="x_flujos_neg_cual_1" size="40" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Proovedor 2</td>
    <td><input type="text" id="x_flujos_neg_proveedor_2" name="x_flujos_neg_proveedor_2" size="40" /></td>
    <td>&nbsp;</td>
    <td>Cual</td>
    <td><input type="text" id="x_flujos_neg_cual_2" name="x_flujos_neg_cual_2" size="40" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Proovedor 3</td>
    <td><input type="text" id="x_flujos_neg_proveedor_3" name="x_flujos_neg_proveedor_3" size="40" /></td>
    <td>&nbsp;</td>
    <td>Cual</td>
    <td><input type="text" id="x_flujos_neg_cual_3" name="x_flujos_neg_cual_3" size="40" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Proovedor 4</td>
    <td><input type="text" id="x_flujos_neg_proveedor_1" name="x_flujos_neg_proveedor_1" size="40" /></td>
    <td>&nbsp;</td>
    <td>Cual</td>
    <td><input type="text" id="x_flujos_neg_cual_4" name="x_flujos_neg_cual_4" size="40" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Gasto 1</td>
    <td><input type="text" id="x_flujos_neg_gasto_1" name="x_flujos_neg_gasto_1" size="40" /></td>
    <td>&nbsp;</td>
    <td>Cual</td>
    <td><input type="text" id="x_flujos_neg_cual_5" name="x_flujos_neg_cual_5" size="40" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Gasto 2</td>
    <td><input type="text" id="x_flujos_neg_gasto_2" name="x_flujos_neg_gasto_2" size="40" /></td>
    <td>&nbsp;</td>
    <td>Cual</td>
    <td><input type="text" id="x_flujos_neg_cual_6" name="x_flujos_neg_cual_6" size="40" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Gasto 3</td>
    <td><input type="text" id="x_flujos_neg_gasto_2" name="x_flujos_neg_gasto_2" size="40"  /></td>
    <td>&nbsp;</td>
    <td>Cual</td>
    <td><input type="text" id="x_flujos_neg_cual_7" name="x_flujos_neg_cual_7" size="40" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Ingreso Negocio</td>
    <td><input type="text" id="x_ingreso_negocio" name="x_ingreso_negocio" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr></table>
  <table width="90%">
  <tr>
    <td colspan="10" id="tableHead">INVERSION DEL NEGOCIO</td>
    </tr>
  <tr>
    <td colspan="5">Fija</td>
    <td colspan="5">Variable</td>
    </tr>
  <tr>
    <td width="6%">&nbsp;</td>
    <td width="18%">Concepto</td>
    <td width="7%">&nbsp;</td>
    <td width="12%">Valor</td>
    <td width="5%">&nbsp;</td>
    <td width="5%">&nbsp;</td>
    <td width="18%">concepto</td>
    <td width="6%">&nbsp;</td>
    <td width="12%">valor</td>
    <td width="11%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="text" id="x_inv_neg_fija_conc_1" name="x_inv_neg_fija_conc_1" size="40" /></td>
    <td>&nbsp;</td>
    <td><input type="text" id="x_inv_neg_fija_valor_1" name="x_inv_neg_fija_valor_1" size="40" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type="text" id="x_inv_neg_var_conc_1" name="x_inv_neg_var_conc_1" size="40" /></td>
    <td>&nbsp;</td>
    <td><input type="text" id="x_inv_neg_var_valor_1" name="x_inv_neg_var_valor_1" size="40" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="text" id="x_inv_neg_fija_conc_2" name="x_inv_neg_fija_conc_2" size="40" /></td>
    <td>&nbsp;</td>
    <td><input type="text" id="x_inv_neg_fija_valor_2" name="x_inv_neg_fija_valor_2" size="40" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type="text" id="x_inv_neg_var_conc_2" name="x_inv_neg_var_conc_2" size="40" /></td>
    <td>&nbsp;</td>
    <td><input type="text" id="x_inv_neg_var_valor_2" name="x_inv_neg_var_valor_2" size="40" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="text" id="x_inv_neg_fija_conc_3" name="x_inv_neg_fija_conc_3" size="40" /></td>
    <td>&nbsp;</td>
    <td><input type="text" id="x_inv_neg_fija_valor_3" name="x_inv_neg_fija_valor_3" size="40" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type="text" id="x_inv_neg_var_conc_3" name="x_inv_neg_var_conc_3" size="40" /></td>
    <td>&nbsp;</td>
    <td><input type="text" id="x_inv_neg_var_valor_3" name="x_inv_neg_var_valor_3" size="40" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="text" name="x_inv_neg_fija_conc_4" id="x_inv_neg_fija_conc_4" size="40" /></td>
    <td>&nbsp;</td>
    <td><input type="text" id="x_inv_neg_fija_valor_4" name="x_inv_neg_fija_valor_4" size="40" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type="text" id="x_inv_neg_var_conc_4" name="x_inv_neg_var_conc_4" size="40" /></td>
    <td>&nbsp;</td>
    <td><input type="text" id="x_inv_neg_var_valor_4" name="x_inv_neg_var_valor_4" size="40" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Total</td>
    <td><input type="text" id="x_inv_neg_total_fija" name="x_inv_neg_total_fija" size="40" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Total</td>
    <td><input type="text" id="x_inv_neg_total_var" name="x_inv_neg_total_var" size="40" /></td>
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
    <td>&nbsp;</td>
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
    <td>Activos Totales</td>
    <td colspan="2"><input type="text" id="x_inv_neg_activos_totales" name="x_inv_neg_activos_totales" size="40" /></td>
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
    <tr>
    <td colspan="10" id="tableHead">DECLARACIONES</td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td>Fecha</td>
    <td colspan="3"><input type="text" id="x_fecha" name="x_fecha" size="40" />&nbsp;<input type="image" src="admin/images/ew_calendar.gif" alt="Pick a Date" onClick="popUpCalendar(this, this.form.x_fecha,'yyyy/mm/dd');return false;"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
    <tr>
    <td colspan="10" id="declaracione">Autorizo a Microfinanciera Crece SA de CV SOFOM ENR (CRECE), para que lleve a cabo investigaciones sobre mi comportamiento e historial crediticio, as&iacute; como de cualquier otra informaci&oacute;n de naturaleza an&aacute;loga en las sociedades de informaci&oacute;n crediticia que estime convenientes. As&iacute; mismo, declaro que conozco el alcance de la informaci&oacute;n que se solicitar&aacute;, del uso que CRECE har&aacute; de tal informaci&oacute;n y que est&aacute; podra realizar consultas peri&oacute;dicas de mi historial crediticio, consintiendo que dicha autorizaci&oacute;n se encuentre vigentepor un per&iacute;odo de tres a&ntilde;os a partir de la fecha de su expedici&oacute;n y en todo caso, durante el tiempo en que mantegamos una relaci&oacute;n jur&iacute;dica.<br />Estoy consciente y acepto que &eacute;ste documento quede bajo propiedad de CRECE para efectos de control y cumplimiento del art&iacute;culo 28 de la ley para regular a las sociedades de informaci&oacute;n crediticia<br />De acuerdo al cap&iacute;tulo II, secci&oacute;n I, art&iacute;culo 3, clausula 4 de la ley para la transparencia y ordenamiento de los servicios financieros aplicables a los contratos de adhesi&oacute;n, publicidad, estados de cuenta y comprobantes de operaci&oacute;n de las sociedades financieras de objeto m&uacute;ltiple no reguladas; por &eacute;ste medio expreso mi consentimiento que a trav&eacute;s del personal facultado de CRECE he sido enterado del costo anual total ( CAT) del cr&eacute;dito que estoy interesado(a) en celebrar. Tambi&eacute;n he sido enterado de la tasa de inter&eacute;s moratoria que se cobrar&aacute; en caso de presentar atraso(s) en alguno(s) de los vencimientos del pr&eacute;stamo.<br />
      Tambi&eacute;n de acuerdo al cap&iacute;tulo IV, secci&oacute;n I, art&iacute;culo 23 de la ley mencionada anteriormente, estoy de acuerdo en consultar mi estado de cuenta a trav&eacute;s de medios electr&oacute;nicos; espec&iacute;ficamente mediante la p&aacute;gina www.financieracrea.com con el usuario y contrase&ntilde;a que CRECE a trav&eacute;s de su personal facultado me haga saber toda vez que se firme el  pagar&eacute; correspondiente al cr&eacute;dito &oacute; a los cr&eacute;ditos que celebre con ellos.<br />Manifiesto bajo protesta decir verdad, que la informaci&oacute;n que proporcion&eacute; en &eacute;ste documento esta apegada estrictamente a la realidad, y por tanto, soy responsable  de la veracidad de la misma. As&iacute; mismo, manifiesto conocer que en caso de falseo &oacute; alteraci&oacute;n de dicha informaci&oacute;n,se aplicar&aacute;n las sanciones correspondientes.</td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
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
    <td><input type="button" id="enviaFormulario" name="enviaFormulario" value="ENVIAR" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

<?php
if (@$_SESSION["mensajeAlta"] <> "") {
?>
    <div id="seEnvioFormulario" style="display:block"><table width="90%" class="error_box"><tr ><td align="center"><?php echo($_SESSION["mensajeAlta"]); ?></td></tr></table> </div>
      
    <?php
	$_SESSION["mensajeAlta"] = ""; // Clear message
}else{
?>
     <div id="seEnvioFormulario" style="display:none"> </div>
    <?php }
?>    
</form>
</div>
</body>
</html>