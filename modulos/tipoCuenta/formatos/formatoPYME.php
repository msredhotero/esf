<?php session_start(); ?>
<?php ob_start(); ?>

<?php include("admin/db.php"); ?>
<?php include("admin/phpmkrfn.php"); ?>
<?php

$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];
$currdate = $currentdate["year"]."/".$currentdate["mon"]."/".$currentdate["mday"];

$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];
$x_preventa = true;

$x_msg = "";
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

?>


<?php
//$x_envio_datos  = $_POST["datos"];
if($_POST["datos"] == "enviados"){
		
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
	$x_propiedad_hipot = @$_POST["x_propiedad_hipot"];
	$x_antiguedad_negocio = @$_POST["x_antiguedad_negocio"];
	$x_tel_arrendatario_negocio = @$_POST["x_tel_arrendatario_negocio"];
	$x_renta_mensual = @$_POST["x_renta_mensual"];
	$x_tel_negocio = @$_POST["x_tel_negocio"];
	$x_garantias = @$_POST["x_garantias"];
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
	
	//conectar con la base
	$conn = phpmkr_db_connect(HOST, USER, PASS,DB);
	//ARRAY CON LOS VALORES DE LOS CAMPOS
	
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
	
	
	// Field tipo_local_negocio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_propiedad_hipot"]) : $GLOBALS["x_propiedad_hipot"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`propiedad_hipot`"] = $theValue;
	

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
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_garantias"]) : $GLOBALS["x_garantias"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`garantias`"] = $theValue;

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
	$strsql = "INSERT INTO `formatopyme` (";
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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Formato PYME</title>
<LINK REL="stylesheet" TYPE="text/css" href="../cssFormatos/cssFormatos.css">

<script type="text/javascript" src="admin/ew.js"></script>
<link href="../crm.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<script type="text/javascript" >
window.onload = function(){
	document.getElementById("siguiente").onclick = muestraOculta;
	document.getElementById("anterior").onclick = muestraOculta;
	document.getElementById("enviar").onclick = EW_checkMyForm;
	document.getElementById("seEnvioFormulario").onclick = ocultaMens;
	
	document.getElementById("x_nombre").onchange = aMayus;
	document.getElementById("x_rfc").onchange = aMayus;
	document.getElementById("x_curp").onchange = aMayus;
	document.getElementById("x_esposa").onchange = aMayus;
	document.getElementById("x_calle_domicilio").onchange = aMayus;
	document.getElementById("x_colonia_domicilio").onchange = aMayus;
	document.getElementById("x_entidad_domicilio").onchange = aMayus;
	document.getElementById("x_ubicacion_domicilio").onchange = aMayus;
	document.getElementById("x_tipo_vivienda").onchange = aMayus;
	document.getElementById("x_giro_negocio").onchange = aMayus;
	document.getElementById("x_calle_negocio").onchange = aMayus;
	document.getElementById("x_colonia_negocio").onchange = aMayus;
	document.getElementById("x_entidad_negocio").onchange = aMayus;
	document.getElementById("x_ubicacion_negocio").onchange = aMayus;
	document.getElementById("x_tipo_local_negocio").onchange = aMayus;
	document.getElementById("x_propiedad_hipot").onchange = aMayus;
	
	document.getElementById("x_referencia_com_1").onchange = aMayus;
	document.getElementById("x_referencia_com_2").onchange = aMayus;
	document.getElementById("x_referencia_com_3").onchange = aMayus;
	document.getElementById("x_referencia_com_4").onchange = aMayus;
	document.getElementById("x_parentesco_ref_1").onchange = aMayus;
	document.getElementById("x_parentesco_ref_2").onchange = aMayus;
	document.getElementById("x_parentesco_ref_3").onchange = aMayus;
	document.getElementById("x_parentesco_ref_4").onchange = aMayus;
	document.getElementById("x_ing_fam_cuales_1").onchange = aMayus;
	document.getElementById("x_ing_fam_cuales_2").onchange = aMayus;
	document.getElementById("x_ing_fam_cuales_3").onchange = aMayus;
	document.getElementById("x_ing_fam_cuales_4").onchange = aMayus;
	document.getElementById("x_ing_fam_cuales_5").onchange = aMayus;
	document.getElementById("x_flujos_neg_cual_1").onchange = aMayus;
	document.getElementById("x_flujos_neg_cual_2").onchange = aMayus;
	document.getElementById("x_flujos_neg_cual_3").onchange = aMayus;
	document.getElementById("x_flujos_neg_cual_4").onchange = aMayus;
	document.getElementById("x_flujos_neg_cual_5").onchange = aMayus;
	document.getElementById("x_flujos_neg_cual_6").onchange = aMayus;
	document.getElementById("x_flujos_neg_cual_7").onchange = aMayus;
	document.getElementById("x_inv_neg_fija_conc_1").onchange = aMayus;
	document.getElementById("x_inv_neg_fija_conc_2").onchange = aMayus;
	document.getElementById("x_inv_neg_fija_conc_3").onchange = aMayus;
	document.getElementById("x_inv_neg_fija_conc_4").onchange = aMayus;
	document.getElementById("x_inv_neg_var_conc_1").onchange = aMayus;
	document.getElementById("x_inv_neg_var_conc_2").onchange = aMayus;
	document.getElementById("x_inv_neg_var_conc_3").onchange = aMayus;
	document.getElementById("x_inv_neg_var_conc_4").onchange = aMayus;
	
	
	 
	 
	 
	 
	

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
	
	 
	function ocultaMens (){
	document.getElementById("seEnvioFormulario").style.display = "none";	
	}
	
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

 
//validaciond e los campos 
 <!-- -->
function EW_checkMyForm() {
	
	EW_this = document.frmPYME;
	validada = true;

///////////////////////////////////////////////////
if (EW_this.x_nombre && !EW_hasValue(EW_this.x_nombre, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre, "TEXT", "Por favor introduzca el campo requerido - nombre"))
		validada = false;
}
if (EW_this.x_rfc && !EW_hasValue(EW_this.x_rfc, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_rfc, "TEXT", "Por favor introduzca el campo requerido - RFC"))
		validada = false;
}
if (EW_this.x_curp && !EW_hasValue(EW_this.x_curp, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_curp, "TEXT", "Por favor introduzca el campo requerido - CURP"))
		validada = false;
}
if (EW_this.x_fecha_nacimiento && !EW_hasValue(EW_this.x_fecha_nacimiento, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_nacimiento, "TEXT", "Por favor introduzca el campo requerido - fecha nacimiento"))
		validada = false;
}
if (EW_this.x_fecha_nacimiento && !EW_checkdate(EW_this.x_fecha_nacimiento.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_nacimiento, "TEXT", "Formato de fecha incorrecto, verifique por favor., format = aaaa/mm/dd - fecha nacimiento"))
		validada = false; 
}
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
if (EW_this.x_correo_electronico && !EW_hasValue(EW_this.x_correo_electronico, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_correo_electronico, "TEXT", "Por favor introduzca el campo requerido - correo electronico"))
		validada = false;
}
if (EW_this.x_correo_electronico && !EW_checkemail(EW_this.x_correo_electronico.value)) {
	if (!EW_onError(EW_this, EW_this.x_correo_electronico, "TEXT", "Email incorrecto, verifque por favor - correo electronico"))
		validada = false; 
}
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
if (EW_this.x_codigo_postal_domicilio && !EW_hasValue(EW_this.x_codigo_postal_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal_domicilio, "TEXT", "Por favor introduzca el campo requerido - codigo postal domicilio"))
		validada = false;
}
if (EW_this.x_codigo_postal_domicilio && !EW_checkinteger(EW_this.x_codigo_postal_domicilio.value)) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal_domicilio, "TEXT", "Valor incorrecto, se espera un entero. - codigo postal domicilio"))
		validada = false; 
}
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
if (EW_this.x_giro_negocio && !EW_hasValue(EW_this.x_giro_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_giro_negocio, "TEXT", "Por favor introduzca el campo requerido - giro negocio"))
		validada = false;
}
if (EW_this.x_calle_negocio && !EW_hasValue(EW_this.x_calle_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_calle_negocio, "TEXT", "Por favor introduzca el campo requerido - calle negocio"))
		validada = false;
}
if (EW_this.x_colonia_negocio && !EW_hasValue(EW_this.x_colonia_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_colonia_negocio, "TEXT", "Por favor introduzca el campo requerido - colonia negocio"))
		validada = false;
}
if (EW_this.x_entidad_negocio && !EW_hasValue(EW_this.x_entidad_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_entidad_negocio, "TEXT", "Por favor introduzca el campo requerido - entidad negocio"))
		validada = false;
}
if (EW_this.x_ubicacion_negocio && !EW_hasValue(EW_this.x_ubicacion_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ubicacion_negocio, "TEXT", "Por favor introduzca el campo requerido - ubicacion negocio"))
		validada = false;
}
if (EW_this.x_codigo_postal_negocio && !EW_checkinteger(EW_this.x_codigo_postal_negocio.value)) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal_negocio, "TEXT", "Valor incorrecto, se espera un entero. - codigo postal negocio"))
		validada = false; 
}
if (EW_this.x_renta_mensual && !EW_checknumber(EW_this.x_renta_mensual.value)) {
	if (!EW_onError(EW_this, EW_this.x_renta_mensual, "TEXT", "Formato de numero incorrecto, verifique por favor- renta mensual"))
		validada = false; 
}
if (EW_this.x_garantias && !EW_hasValue(EW_this.x_garantias, "TEXTAREA" )) {
	if (!EW_onError(EW_this, EW_this.x_garantias, "TEXTAREA", "Por favor introduzca el campo requerido - solicitud compra"))
		validada = false;
}
if (EW_this.x_referencia_com_1 && !EW_hasValue(EW_this.x_referencia_com_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_referencia_com_1, "TEXT", "Por favor introduzca el campo requerido - referencia com 1"))
		validada = false;
}
if (EW_this.x_tel_referencia_1 && !EW_hasValue(EW_this.x_tel_referencia_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tel_referencia_1, "TEXT", "Por favor introduzca el campo requerido - tel referencia 1"))
		validada = false;
}
if (EW_this.x_parentesco_ref_1 && !EW_hasValue(EW_this.x_parentesco_ref_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_ref_1, "TEXT", "Por favor introduzca el campo requerido - parentesco ref 1"))
		validada = false;
}
if (EW_this.x_ing_fam_negocio && !EW_hasValue(EW_this.x_ing_fam_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_negocio, "TEXT", "Por favor introduzca el campo requerido - ing fam negocio"))
		validada = false;
}
if (EW_this.x_ing_fam_negocio && !EW_checknumber(EW_this.x_ing_fam_negocio.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_negocio, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam negocio"))
		validada = false; 
}
if (EW_this.x_ing_fam_otro_th && !EW_hasValue(EW_this.x_ing_fam_otro_th, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_otro_th, "TEXT", "Por favor introduzca el campo requerido - ing fam otro th"))
		validada = false;
}
if (EW_this.x_ing_fam_otro_th && !EW_checknumber(EW_this.x_ing_fam_otro_th.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_otro_th, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam otro th"))
		validada = false; 
}
if (EW_this.x_ing_fam_1 && !EW_hasValue(EW_this.x_ing_fam_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_1, "TEXT", "Por favor introduzca el campo requerido - ing fam 1"))
		validada = false;
}
if (EW_this.x_ing_fam_1 && !EW_checknumber(EW_this.x_ing_fam_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_1, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam 1"))
		validada = false; 
}
if (EW_this.x_ing_fam_2 && !EW_hasValue(EW_this.x_ing_fam_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_2, "TEXT", "Por favor introduzca el campo requerido - ing fam 2"))
		validada = false;
}
if (EW_this.x_ing_fam_2 && !EW_checknumber(EW_this.x_ing_fam_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_2, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam 2"))
		validada = false; 
}
if (EW_this.x_ing_fam_deuda_1 && !EW_hasValue(EW_this.x_ing_fam_deuda_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_1, "TEXT", "Por favor introduzca el campo requerido - ing fam deuda 1"))
		validada = false;
}
if (EW_this.x_ing_fam_deuda_1 && !EW_checknumber(EW_this.x_ing_fam_deuda_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_1, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam deuda 1"))
		validada = false; 
}
if (EW_this.x_ing_fam_deuda_2 && !EW_hasValue(EW_this.x_ing_fam_deuda_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_2, "TEXT", "Por favor introduzca el campo requerido - ing fam deuda 2"))
		validada = false;
}
if (EW_this.x_ing_fam_deuda_2 && !EW_checknumber(EW_this.x_ing_fam_deuda_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_2, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam deuda 2"))
		validada = false; 
}
if (EW_this.x_ing_fam_total && !EW_hasValue(EW_this.x_ing_fam_total, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_total, "TEXT", "Por favor introduzca el campo requerido - ing fam total"))
		validada = false;
}
if (EW_this.x_ing_fam_total && !EW_checknumber(EW_this.x_ing_fam_total.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_total, "TEXT", "Formato de numero incorrecto, verifique por favor- ing fam total"))
		validada = false; 
}
if (EW_this.x_flujos_neg_ventas && !EW_hasValue(EW_this.x_flujos_neg_ventas, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_ventas, "TEXT", "Por favor introduzca el campo requerido - flujos neg ventas"))
		validada = false;
}
if (EW_this.x_flujos_neg_ventas && !EW_checknumber(EW_this.x_flujos_neg_ventas.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_ventas, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg ventas"))
		validada = false; 
}
if (EW_this.x_flujos_neg_proveedor_1 && !EW_hasValue(EW_this.x_flujos_neg_proveedor_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_1, "TEXT", "Por favor introduzca el campo requerido - flujos neg proveedor 1"))
		validada = false;
}
if (EW_this.x_flujos_neg_proveedor_1 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_1, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg proveedor 1"))
		validada = false; 
}
if (EW_this.x_flujos_neg_proveedor_2 && !EW_hasValue(EW_this.x_flujos_neg_proveedor_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_2, "TEXT", "Por favor introduzca el campo requerido - flujos neg proveedor 2"))
		validada = false;
}
if (EW_this.x_flujos_neg_proveedor_2 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_2, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg proveedor 2"))
		validada = false; 
}
if (EW_this.x_flujos_neg_proveedor_3 && !EW_hasValue(EW_this.x_flujos_neg_proveedor_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_3, "TEXT", "Por favor introduzca el campo requerido - flujos neg proveedor 3"))
		validada = false;
}
if (EW_this.x_flujos_neg_proveedor_3 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_3, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg proveedor 3"))
		validada = false; 
}
if (EW_this.x_flujos_neg_proveedor_4 && !EW_hasValue(EW_this.x_flujos_neg_proveedor_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_4, "TEXT", "Por favor introduzca el campo requerido - flujos neg proveedor 4"))
		validada = false;
}
if (EW_this.x_flujos_neg_proveedor_4 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_4.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_4, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg proveedor 4"))
		validada = false; 
}
if (EW_this.x_flujos_neg_gasto_1 && !EW_hasValue(EW_this.x_flujos_neg_gasto_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_1, "TEXT", "Por favor introduzca el campo requerido - flujos neg gasto 1"))
		validada = false;
}
if (EW_this.x_flujos_neg_gasto_1 && !EW_checknumber(EW_this.x_flujos_neg_gasto_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_1, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg gasto 1"))
		validada = false; 
}
if (EW_this.x_flujos_neg_gasto_2 && !EW_hasValue(EW_this.x_flujos_neg_gasto_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_2, "TEXT", "Por favor introduzca el campo requerido - flujos neg gasto 2"))
		validada = false;
}
if (EW_this.x_flujos_neg_gasto_2 && !EW_checknumber(EW_this.x_flujos_neg_gasto_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_2, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg gasto 2"))
		validada = false; 
}
if (EW_this.x_flujos_neg_gasto_3 && !EW_hasValue(EW_this.x_flujos_neg_gasto_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_3, "TEXT", "Por favor introduzca el campo requerido - flujos neg gasto 3"))
		validada = false;
}
if (EW_this.x_flujos_neg_gasto_3 && !EW_checknumber(EW_this.x_flujos_neg_gasto_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_3, "TEXT", "Formato de numero incorrecto, verifique por favor- flujos neg gasto 3"))
		validada = false; 
}
if (EW_this.x_ingreso_negocio && !EW_checknumber(EW_this.x_ingreso_negocio.value)) {
	if (!EW_onError(EW_this, EW_this.x_ingreso_negocio, "TEXT", "Formato de numero incorrecto, verifique por favor- ingreso negocio"))
		validada = false; 
}
if (EW_this.x_inv_neg_fija_valor_1 && !EW_hasValue(EW_this.x_inv_neg_fija_valor_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_1, "TEXT", "Por favor introduzca el campo requerido - inv neg fija valor 1"))
		validada = false;
}
if (EW_this.x_inv_neg_fija_valor_1 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_1, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg fija valor 1"))
		validada = false; 
}
if (EW_this.x_inv_neg_fija_valor_2 && !EW_hasValue(EW_this.x_inv_neg_fija_valor_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_2, "TEXT", "Por favor introduzca el campo requerido - inv neg fija valor 2"))
		validada = false;
}
if (EW_this.x_inv_neg_fija_valor_2 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_2, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg fija valor 2"))
		validada = false; 
}
if (EW_this.x_inv_neg_fija_valor_3 && !EW_hasValue(EW_this.x_inv_neg_fija_valor_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_3, "TEXT", "Por favor introduzca el campo requerido - inv neg fija valor 3"))
		validada = false;
}
if (EW_this.x_inv_neg_fija_valor_3 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_3, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg fija valor 3"))
		validada = false; 
}
if (EW_this.x_inv_neg_fija_valor_4 && !EW_hasValue(EW_this.x_inv_neg_fija_valor_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_4, "TEXT", "Por favor introduzca el campo requerido - inv neg fija valor 4"))
		validada = false;
}
if (EW_this.x_inv_neg_fija_valor_4 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_4.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_4, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg fija valor 4"))
		validada = false; 
}
if (EW_this.x_inv_neg_total_fija && !EW_hasValue(EW_this.x_inv_neg_total_fija, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_fija, "TEXT", "Por favor introduzca el campo requerido - inv neg total fija"))
		validada = false;
}
if (EW_this.x_inv_neg_total_fija && !EW_checknumber(EW_this.x_inv_neg_total_fija.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_fija, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg total fija"))
		validada = false; 
}
if (EW_this.x_inv_neg_var_valor_1 && !EW_hasValue(EW_this.x_inv_neg_var_valor_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_1, "TEXT", "Por favor introduzca el campo requerido - inv neg var valor 1"))
		validada = false;
}
if (EW_this.x_inv_neg_var_valor_1 && !EW_checknumber(EW_this.x_inv_neg_var_valor_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_1, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg var valor 1"))
		validada = false; 
}
if (EW_this.x_inv_neg_var_valor_2 && !EW_hasValue(EW_this.x_inv_neg_var_valor_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_2, "TEXT", "Por favor introduzca el campo requerido - inv neg var valor 2"))
		validada = false;
}
if (EW_this.x_inv_neg_var_valor_2 && !EW_checknumber(EW_this.x_inv_neg_var_valor_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_2, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg var valor 2"))
		validada = false; 
}
if (EW_this.x_inv_neg_var_valor_3 && !EW_hasValue(EW_this.x_inv_neg_var_valor_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_3, "TEXT", "Por favor introduzca el campo requerido - inv neg var valor 3"))
		validada = false;
}
if (EW_this.x_inv_neg_var_valor_3 && !EW_checknumber(EW_this.x_inv_neg_var_valor_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_3, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg var valor 3"))
		validada = false; 
}
if (EW_this.x_inv_neg_var_valor_4 && !EW_hasValue(EW_this.x_inv_neg_var_valor_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_4, "TEXT", "Por favor introduzca el campo requerido - inv neg var valor 4"))
		validada = false;
}
if (EW_this.x_inv_neg_var_valor_4 && !EW_checknumber(EW_this.x_inv_neg_var_valor_4.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_4, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg var valor 4"))
		validada = false; 
}
if (EW_this.x_inv_neg_total_var && !EW_hasValue(EW_this.x_inv_neg_total_var, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_var, "TEXT", "Por favor introduzca el campo requerido - inv neg total var"))
		validada = false;
}
if (EW_this.x_inv_neg_total_var && !EW_checknumber(EW_this.x_inv_neg_total_var.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_var, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg total var"))
		validada = false; 
}
if (EW_this.x_inv_neg_activos_totales && !EW_hasValue(EW_this.x_inv_neg_activos_totales, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_activos_totales, "TEXT", "Por favor introduzca el campo requerido - inv neg activos totales"))
		validada = false;
}
if (EW_this.x_inv_neg_activos_totales && !EW_checknumber(EW_this.x_inv_neg_activos_totales.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_activos_totales, "TEXT", "Formato de numero incorrecto, verifique por favor- inv neg activos totales"))
		validada = false; 
}
if (EW_this.x_fecha && !EW_hasValue(EW_this.x_fecha, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha, "TEXT", "Por favor introduzca el campo requerido - fecha"))
		validada = false;
}
if (EW_this.x_fecha && !EW_checkdate(EW_this.x_fecha.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha, "TEXT", "Formato de fecha incorrecto, verifique por favor., format = aaaa/mm/dd - fecha"))
		validada = false; 
}

///////////////////////////////////////


if(validada == true){
	EW_this.submit();	
	 
	//EW_this.getElementById("seEnvioFormulario").style.display="block"; 
	
}


}// fin de la validadcion
}
</script>


</head>

<body> 

<div id="contenedor" >

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

<div id="paginaUno">
<table width="100%" id="pagina1">
<tr><td><input type="hidden" name="datos" id="datos" value="enviados" /></td></tr>
<tr>
  <td><table width="91%">
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
      <td  colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold"> Datos del Titular o Representante Legal</td>
    </tr>
     <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td width="223" >Titular</td>
      <td colspan="8" align="center"><table width="98%">
        <tr>
          <td><input type="text" name="x_nombre" id="x_nombre"  maxlength="250" size="35"/></td>
          <td><input type="text" name="x_apellido_paterno" id="x_apellido_parterno" maxlength="250" size="35" /></td>
          <td><input type="text" name="x_apellido_materno" id="x_apellido_materno" maxlength="250" size="35" /></td>
          </tr>
        <tr>
          <td>Nombre</td>
          <td>Apellido Paterno</td>
          <td>Apellido Materno</td>
          </tr>
        </table></td>
    </tr>
      <tr>
        <td>RFC</td>
        <td colspan="4"><input type="text" name="x_rfc" id="x_rfc"  maxlength="30" size="25"/></td>
        <td width="172">CURP</td>
        <td colspan="5"><input type="text" name="x_curp" id="x_curp"  maxlength="30" size="25"/></td>
      </tr>
    <tr>
      <td>Fecha Nacimiento</td>
      <td colspan="2"><span class="texto_normal">
              <input name="x_fecha_nacimiento" type="text" id="x_fecha_nacimiento" value="<?php echo FormatDateTime(@$x_tit_fecha_nac,7); ?>" size="25"  />
              &nbsp;<img src="../images/ew_calendar.gif" id="cx_fecha_nacimiento" onMouseOver="javascript: Calendar.setup(
            {
            inputField : 'x_fecha_nacimiento', 
            ifFormat : '%d/%m/%Y', 
            button : 'cx_fecha_nacimiento' 
            }
            );" style="cursor:pointer;cursor:hand;" /></span></td>
      <td width="105">Sexo</td>
      <td width="103"><label>
        <select name="x_sexo" id="x_sexo">
        <option value="">Seleccione</option>
        <option value="2">Masculino</option> 
		<option value="1">Femenino</option>
        </select>
      </label></td>
      <td>Integrantes Familia</td>
      <td colspan="5"><input type="text" name="x_integrantes_familia" id="x_integrantes_familia"  maxlength="30" size="25" onKeyPress="return solonumeros(this,event)"/></td>
    </tr>
    <tr>
      <td>Dependientes</td>
      <td colspan="2"><input type="text" name="x_dependientes" id="x_dependientes"  maxlength="30" size="30" onKeyPress="return solonumeros(this,event)"/></td>
      <td colspan="2">Correo Electronico</td>
      <td colspan="6"><input type="text" name="x_correo_electronico" id="x_correo_electronico"  maxlength="100" size="46"/></td>
    </tr>
    <tr>
      <td>Esposo(a)</td>
      <td colspan="10"><input type="text" name="x_esposa" id="x_esposa"  maxlength="250" size="100"/></td>
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
      <td colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Domicilio Particular</td>
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
      <td colspan="10"><input type="text" name="x_calle_domicilio" id="x_calle_domicilio"  maxlength="100" size="100"/></td>
    </tr>
    <tr>
      <td>Colonia</td>
      <td colspan="4"><input type="text" name="x_colonia_domicilio" id="x_colonia_domicilio"  maxlength="100" size="50"/></td>
      <td>C&oacute;digo Postal</td>
      <td colspan="5"><input type="text" name="x_codigo_postal_domicilio" id="x_codigo_postal_domicilio"  maxlength="10" size="20" onKeyPress="return solonumeros(this,event)"/></td>
    </tr>
    <tr>
      <td>Entidad</td>
      <td colspan="4"><!-- <input type="text" name="x_entidad_domicilio" id="x_entidad_domicilio"  maxlength="250" size="50"/> -->
      <?php
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
		<div id="txtHint1" class="texto_normal"></div>        
        </span></div></td>
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
      <td>Ubicacion</td>
      <td colspan="10"><input type="text" name="x_ubicacion_domicilio" id="x_ubicacion_domicilio"  maxlength="250" size="50"/></td>
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
      
      </td>
      <td>Antiguedad</td>
      <td colspan="5"><input type="text" name="x_antiguedad" id="x_antiguedad"  maxlength="10" size="20"/></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="4">&nbsp;</td>
      <td>Tel Arrendatario</td>
      <td colspan="5"><input type="text" name="x_tel_arrendatario_domicilio" id="x_tel_arrendatario_domicilio"  maxlength="20" size="20"/></td>
    </tr>
    <tr>
      <td height="24">Tel Domicilio</td>
      <td width="133"><input type="text" name="x_telefono_domicilio" id="x_telefono_domicilio"  maxlength="50" size="25"/></td>
      <td width="76">Otro Tel</td>
      <td colspan="2"><input type="text" name="x_otro_tel_domicilio_1" id="x_otro_tel_domicilio_1"  maxlength="50" size="25"/></td>
      <td>Renta Mensual</td>
      <td colspan="5"><input type="text" name="x_renta_mensula_domicilio" id="x_renta_mensula_domicilio"  maxlength="25" size="20" onKeyPress="return solonumeros(this,event)"/></td>
    </tr>
    <tr>
      <td>Celular</td>
      <td><input type="text" name="x_celular" id="x_celular"  maxlength="50" size="25"/></td>
      <td>Otro Tel</td>
      <td colspan="2"><input type="text" name="x_otro_telefono_domicilio_2" id="x_otro_telefono_domicilio_2"  maxlength="50" size="25"/></td>
      <td colspan="6">&nbsp;</td>
    </tr>
      <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Datos del Negocio </td>
    </tr>
     <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td>Nombre Emp / Per F&iacute;sica</td>
      <td colspan="10"><input type="text" name="x_giro_negocio" id="x_giro_negocio"  maxlength="250" size="100"/></td>
    </tr>
    <tr>
      <td>Calle</td>
      <td colspan="10"><input type="text" name="x_calle_negocio" id="x_calle_negocio"  maxlength="250" size="100"/></td>
    </tr>
    <tr>
      <td>Colonia</td>
      <td colspan="4"><input type="text" name="x_colonia_negocio" id="x_colonia_negocio"  maxlength="250" size="70"/></td>
      <td><p>C&oacute;digo Postal</p></td>
      <td colspan="5"><input type="text" name="x_codigo_postal_negocio" id="x_codigo_postal_negocio"  maxlength="25" size="20" onKeyPress="return solonumeros(this,event)"/></td>
    </tr>
    <tr>
      <td>Entidad</td>
      <td colspan="4"><!-- <input type="text" name="x_entidad_negocio" id="x_entidad_negocio"  maxlength="250" size="70"/> -->
      <?php
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
        <div id="txtHint2" class="texto_normal"></div>
        </span></div></td>
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
      <td>Ubicacion</td>
      <td colspan="10"><input type="text" name="x_ubicacion_negocio" id="x_ubicacion_negocio"  maxlength="250" size="100"/></td>
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
      
      
      </td>
      <td>Antiguedad Negocio</td>
      <td colspan="5"><input type="text" name="x_antiguedad_negocio" id="x_antiguedad_negocio"  maxlength="10" size="20"/></td>
    </tr>
    <tr>
      <td>Prop/Hipotec</td>
      <td colspan="4"><input type="text" name="x_propiedad_hipot" id="x_propiedad_hipot" size="50" maxlength="250" /></td>
      <td>Tel. Contacto</td>
      <td colspan="5"><input type="text" name="x_tel_arrendatario_negocio" id="x_tel_arrendatario_negocio"  maxlength="20" size="20"/></td>
    </tr>
    <tr>
      <td>Tel Negocio</td>
      <td colspan="4"><input type="text" name="x_tel_negocio" id="x_tel_negocio"  maxlength="50" size="25"/></td>
      <td>Renta Mensual</td>
      <td colspan="5"><input type="text" name="x_renta_mensual" id="x_renta_mensual"  maxlength="50" size="20" onKeyPress="return solonumeros(this,event)"/></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="4">&nbsp;</td>
      <td>Antiguedad ubicaion act</td>
      <td colspan="5"><input type="text" name="x_antiguedad_ubicacion" id="x_antiguedad_ubicacion"  maxlength="50" size="20" onKeyPress="return solonumeros(this,event)"/></td>
    </tr>
      <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Garantias</td>
    </tr>
     <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td colspan="11"><center><textarea cols="80" rows="4" id="x_garantias" name="x_garantias"></textarea></center></td>
    </tr>
    <tr>
      <td colspan="11"><table width="80%">
        <tr>
          <td>Valor</td>
          <td><input type="text" name="x_garantia_valor" id="x_garantia_valor" onKeyPress="return solonumeros(this,event)" /></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="11">&nbsp;</td>
    </tr></table>
    <table width="90%">
      <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
  <tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Referencias Comerciales</td>
    </tr>
     <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td height="36" colspan="2"><table width="300"><tr>
        <td width="48">1.-</td>
        <td width="276"><input type="text" name="x_referencia_com_1" id="x_referencia_com_1"  maxlength="250" size="40"/></td></tr></table></td>
      <td colspan="2"><table width="160" height="29"><tr>
        <td width="36">Tel</td>
        <td width="137"><input type="text" name="x_tel_referencia_1" id="x_tel_referencia_1"  maxlength="20" size="30"/></td></tr></table></td>
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
		?>
      
      
      
      
      
       </td>
      </tr>
    <tr>
      <td colspan="2"><table width="300"><tr>
        <td width="48">2.-</td>
        <td width="277"><input type="text" name="x_referencia_com_2" id="x_referencia_com_2"  maxlength="250" size="40"/></td></tr></table></td>
      <td colspan="2"><table width="160"><tr>
        <td width="36">Tel</td>
        <td width="137"><input type="text" name="x_tel_referencia_2" id="x_tel_referencia_2"  maxlength="20" size="30"/></td></tr></table></td>
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
		?>  
      
      </td>
      </tr>
    <tr>
      <td colspan="2"><table width="300"><tr>
        <td width="48">3.-</td>
        <td width="276"><input type="text" name="x_referencia_com_3" id="x_referencia_com_3"  maxlength="250" size="40"/></td></tr></table></td>
      <td colspan="2"><table width="183"><tr>
        <td width="34">Tel</td>
        <td width="137"><input type="text" name="x_tel_referencia_3" id="x_tel_referencia_3"  maxlength="20" size="30"/></td></tr></table></td>
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
		?>
      
      
      
      </td>
      </tr>
    <tr>
      <td colspan="2"><table width="300"><tr>
        <td width="48">4.-</td>
        <td width="276"><input type="text" name="x_referencia_com_4" id="x_referencia_com_4"  maxlength="250" size="40"/></td></tr></table></td>
     <td colspan="2"><table width="160"><tr>
        <td width="35">Tel</td>
        <td width="137"><input type="text" name="x_tel_referencia_4" id="x_tel_referencia_4"  maxlength="20" size="30"/></td></tr></table></td>
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
    
    
    
<table width="90%" id="tablePag" style="display:none"><tr><td><div id="paginacion">

<div  align="right"><a href="#" id="siguiente">Siguiente</a></div>

</div></td></tr></table></tr>
  </table>
  
</div><!--pagina uno -->


<p>&nbsp;</p>

<div id="paginaDos" style="display:block">
<!-- paginaDos-->
<table width="100%" id="pagina2" style="display:block">
<tr>
  <td><table width="90%" >
   
    <tr>
      <td colspan="22"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Flujos de Negocio</td>
         
    </tr>
     <tr>
      <td colspan="22" id="tableHead"><p></td>
    </tr>
    <tr>
      <td width="147">Ventas</td>
      <td colspan="4"><input type="text" name="x_flujos_neg_ventas" id="x_flujos_neg_ventas"  maxlength="30" size="35" onKeyPress="return solonumeros(this,event)"/></td>
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
      <td colspan="4"><input type="text" name="x_flujos_neg_proveedor_1" id="x_flujos_neg_proveedor_1"  maxlength="250" size="35" onKeyPress="return solonumeros(this,event)"/></td>
      <td colspan="2">Cu&aacute;l</td>
      <td colspan="4"><input type="text" name="x_flujos_neg_cual_1" id="x_flujos_neg_cual_1"  maxlength="100" size="35"/></td>
      <td width="127">&nbsp;</td>
      <td colspan="10">&nbsp;</td>
    </tr>
     <tr>
      <td width="147">Proveedor 2</td>
      <td colspan="4"><input type="text" name="x_flujos_neg_proveedor_2" id="x_flujos_neg_proveedor_2"  maxlength="250" size="35" onKeyPress="return solonumeros(this,event)"/></td>
      <td colspan="2">Cu&aacute;l</td>
      <td colspan="4"><input type="text" name="x_flujos_neg_cual_2" id="x_flujos_neg_cual_2"  maxlength="100" size="35"/></td>
      <td width="127">&nbsp;</td>
      <td colspan="10">&nbsp;</td>
    </tr>
     <tr>
      <td width="147">Proveedor 3</td>
      <td colspan="4"><input type="text" name="x_flujos_neg_proveedor_3" id="x_flujos_neg_proveedor_3"  maxlength="250" size="35" onKeyPress="return solonumeros(this,event)"/></td>
      <td colspan="2">Cu&aacute;l</td>
      <td colspan="4"><input type="text" name="x_flujos_neg_cual_3" id="x_flujos_neg_cual_3"  maxlength="100" size="35"/></td>
      <td width="127">&nbsp;</td>
      <td colspan="10">&nbsp;</td>
    </tr>
     <tr>
      <td width="147">Proveedor 4</td>
      <td colspan="4"><input type="text" name="x_flujos_neg_proveedor_4" id="x_flujos_neg_proveedor_4"  maxlength="250" size="35" onKeyPress="return solonumeros(this,event)"/></td>
      <td colspan="2">Cu&aacute;l</td>
      <td colspan="4"><input type="text" name="x_flujos_neg_cual_4" id="x_flujos_neg_cual_4"  maxlength="100" size="35"/></td>
      <td width="127">&nbsp;</td>
      <td colspan="10">&nbsp;</td>
    </tr>
     <tr>
      <td width="147">Gasto 1</td>
      <td colspan="4"><input type="text" name="x_flujos_neg_gasto_1" id="x_flujos_neg_gasto_1"  maxlength="250" size="35" onKeyPress="return solonumeros(this,event)"/></td>
      <td colspan="2">Cu&aacute;l</td>
      <td colspan="4"><input type="text" name="x_flujos_neg_cual_5" id="x_flujos_neg_cual_5"  maxlength="100" size="35"/></td>
      <td width="127">&nbsp;</td>
      <td colspan="10">&nbsp;</td>
    </tr>
     <tr>
      <td width="147">Gasto 2</td>
      <td colspan="4"><input type="text" name="x_flujos_neg_gasto_2" id="x_flujos_neg_gasto_2"  maxlength="250" size="35" onKeyPress="return solonumeros(this,event)"/></td>
      <td colspan="2">Cu&aacute;l</td>
      <td colspan="4"><input type="text" name="x_flujos_neg_cual_6" id="x_flujos_neg_cual_6"  maxlength="100" size="35"/></td>
      <td width="127">&nbsp;</td>
      <td colspan="10">&nbsp;</td>
    </tr>
    <tr>
      <td width="147">Gasto 3</td>
      <td colspan="4"><input type="text" name="x_flujos_neg_gasto_3" id="x_flujos_neg_gasto_3"  maxlength="250" size="35" onKeyPress="return solonumeros(this,event)"/></td>
      <td colspan="2">Cu&aacute;l</td>
      <td colspan="4"><input type="text" name="x_flujos_neg_cual_7" id="x_flujos_neg_cual_7"  maxlength="100" size="35"/></td>
      <td width="127">&nbsp;</td>
      <td colspan="10">&nbsp;</td>
    </tr>
    <tr>
      <td width="147">Ingreso Negocio</td>
      <td colspan="4"><input type="text" name="x_ingreso_negocio" id="x_ingreso_negocio"  maxlength="250" size="35" onKeyPress="return solonumeros(this,event)"/></td>
      <td colspan="2">&nbsp;</td>
      <td colspan="4">&nbsp;</td>
      <td width="127">&nbsp;</td>
      <td colspan="10">&nbsp;</td>
    </tr></table>
    
    
    
    
    
    <table width="90%"><tr>
    <tr>
      <td colspan="19" id="tableHead"><p></td>
    </tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Inversion del Negocio</td>
    </tr>
     <tr>
      <td colspan="19" id="tableHead"><p></td>
    </tr>
    <tr>
      <td height="30" colspan="5"><center>FIJA</center></td>
      <td colspan="2">&nbsp;</td>
      <td colspan="2"><center>VARIABLE</center></td>
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
      <td width="226"><input type="text" name="x_inv_neg_fija_conc_1" id="x_inv_neg_fija_conc_1"  maxlength="250" size="35"/></td>
      <td colspan="4"><input type="text" name="x_inv_neg_fija_valor_1" id="x_inv_neg_fija_valor_1"  maxlength="250" size="25" onKeyPress="return solonumeros(this,event)"/></td>
      <td colspan="2">&nbsp;</td>
      <td><input type="text" name="x_inv_neg_var_conc_1" id="x_inv_neg_var_conc_1"  maxlength="250" size="35"/></td>
      <td width="171" colspan="-2"><input type="text" name="x_inv_neg_var_valor_1" id="x_inv_neg_var_valor_1"  maxlength="250" size="25" onKeyPress="return solonumeros(this,event)"/></td>
      <td colspan="10">&nbsp;</td>
    </tr>
     <tr>
      <td width="226"><input type="text" name="x_inv_neg_fija_conc_2" id="x_inv_neg_fija_conc_2"  maxlength="250" size="35"/></td>
      <td colspan="4"><input type="text" name="x_inv_neg_fija_valor_2" id="x_inv_neg_fija_valor_2"  maxlength="250" size="25" onKeyPress="return solonumeros(this,event)"/></td>
      <td colspan="2">&nbsp;</td>
      <td><input type="text" name="x_inv_neg_var_conc_2" id="x_inv_neg_var_conc_2"  maxlength="250" size="35"/></td>
      <td width="171" colspan="-2"><input type="text" name="x_inv_neg_var_valor_2" id="x_inv_neg_var_valor_2"  maxlength="250" size="25" onKeyPress="return solonumeros(this,event)"/></td>
      <td colspan="10">&nbsp;</td>
    </tr>
     <tr>
      <td width="226"><input type="text" name="x_inv_neg_fija_conc_3" id="x_inv_neg_fija_conc_3"  maxlength="250" size="35"/></td>
      <td colspan="4"><input type="text" name="x_inv_neg_fija_valor_3" id="x_inv_neg_fija_valor_3"  maxlength="250" size="25" onKeyPress="return solonumeros(this,event)"/></td>
      <td colspan="2">&nbsp;</td>
      <td><input type="text" name="x_inv_neg_var_conc_3" id="x_inv_neg_var_conc_3"  maxlength="250" size="35"/></td>
      <td width="171" colspan="-2"><input type="text" name="x_inv_neg_var_valor_3" id="x_inv_neg_var_valor_3"  maxlength="250" size="25" onKeyPress="return solonumeros(this,event)"/></td>
      <td colspan="10">&nbsp;</td>
    </tr>
     <tr>
      <td width="226"><input type="text" name="x_inv_neg_fija_conc_4" id="x_inv_neg_fija_conc_4"  maxlength="250" size="35"/></td>
      <td colspan="4"><input type="text" name="x_inv_neg_fija_valor_4" id="x_inv_neg_fija_valor_4"  maxlength="250" size="25" onKeyPress="return solonumeros(this,event)"/></td>
      <td colspan="2">&nbsp;</td>
      <td><input type="text" name="x_inv_neg_var_conc_4" id="x_inv_neg_var_conc_4"  maxlength="250" size="35"/></td>
      <td width="171" colspan="-2"><input type="text" name="x_inv_neg_var_valor_4" id="x_inv_neg_var_valor_4"  maxlength="250" size="25" onKeyPress="return solonumeros(this,event)"/></td>
      <td colspan="10">&nbsp;</td>
    </tr>
     <tr>
      <td width="226" align="right">Total</td>
      <td colspan="4"><input type="text" name="x_inv_neg_total_fija" id="x_inv_neg_total_fija"  maxlength="250" size="25" onKeyPress="return solonumeros(this,event)"/></td>
      <td colspan="2">&nbsp;</td>
      <td align="right">Total</td>
      <td width="171" colspan="-2"><input type="text" name="x_inv_neg_total_var" id="x_inv_neg_total_var"  maxlength="250" size="25" onKeyPress="return solonumeros(this,event)"/></td>
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
      <td width="171" colspan="-2"><input type="text" name="x_inv_neg_activos_totales" id="x_inv_neg_activos_totales"  maxlength="250" size="25" onKeyPress="return solonumeros(this,event)"/></td>
      <td colspan="10">&nbsp;</td>
      <tr>
      <td colspan="19" id="tableHead"><p></td>
    </tr>
   
    <tr><br /><br />
      <td  colspan="19" id="declaracione" ><p></td>
         
    </tr>
   
    
    <tr><td id="tablePag"><div id="paginacion">
</td>


    <tr align="right"><td colspan="19"> </td></tr>
    </table>
    
    </td>
</tr>
</table>
<table width="90%">
<tr>
    <td width="5">&nbsp;</td>
    <td width="917">&nbsp;</td>
    <td width="7">&nbsp;</td>
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

</table>


<div id="altaSucces" style="display:none" ><table width="90%" class="error_box"><tr ><td align="center">REGISTRO DADO DE ALTA.</td></tr></table></div>
<div id="seEnvioFormulario" style="display:none"><table width="90%" class="error_box"><tr ><td align="center">REGISTRO DADO DE ALTA.</td></tr></table> </div>
</div><!--cierre paginaDos -->

</div><!-- contenedor -->

</body>
</html>