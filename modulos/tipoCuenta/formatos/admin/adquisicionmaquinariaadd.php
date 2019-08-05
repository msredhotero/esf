<?php session_start(); ?>
<?php ob_start(); ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Pragma: no-cache"); // HTTP/1.0 
?>
<?php
$ewCurSec = 0; // Initialise

// User levels
define("ewAllowAdd", 1, true);
define("ewAllowDelete", 2, true);
define("ewAllowEdit", 4, true);
define("ewAllowView", 8, true);
define("ewAllowList", 8, true);
define("ewAllowReport", 8, true);
define("ewAllowSearch", 8, true);																														
define("ewAllowAdmin", 16, true);						
?>
<?php

// Initialize common variables
$x_adquisicionMaquinaria_id = Null;
$x_nombre = Null;
$x_rfc = Null;
$x_curp = Null;
$x_fecha_nacimiento = Null;
$x_sexo = Null;
$x_integrantes_familia = Null;
$x_dependientes = Null;
$x_correo_electronico = Null;
$x_esposa = Null;
$x_calle_domicilio = Null;
$x_colonia_domicilio = Null;
$x_entidad_domicilio = Null;
$x_codigo_postal_domicilio = Null;
$x_ubicacion_domicilio = Null;
$x_tipo_vivienda = Null;
$x_telefono_domicilio = Null;
$x_celular = Null;
$x_otro_tel_domicilio_1 = Null;
$x_otro_telefono_domicilio_2 = Null;
$x_antiguedad = Null;
$x_tel_arrendatario_domicilio = Null;
$x_renta_mensula_domicilio = Null;
$x_giro_negocio = Null;
$x_calle_negocio = Null;
$x_colonia_negocio = Null;
$x_entidad_negocio = Null;
$x_ubicacion_negocio = Null;
$x_codigo_postal_negocio = Null;
$x_tipo_local_negocio = Null;
$x_antiguedad_negocio = Null;
$x_tel_arrendatario_negocio = Null;
$x_renta_mensual = Null;
$x_tel_negocio = Null;
$x_solicitud_compra = Null;
$x_referencia_com_1 = Null;
$x_referencia_com_2 = Null;
$x_referencia_com_3 = Null;
$x_referencia_com_4 = Null;
$x_tel_referencia_1 = Null;
$x_tel_referencia_2 = Null;
$x_tel_referencia_3 = Null;
$x_tel_referencia_4 = Null;
$x_parentesco_ref_1 = Null;
$x_parentesco_ref_2 = Null;
$x_parentesco_ref_3 = Null;
$x_parentesco_ref_4 = Null;
$x_ing_fam_negocio = Null;
$x_ing_fam_otro_th = Null;
$x_ing_fam_1 = Null;
$x_ing_fam_2 = Null;
$x_ing_fam_deuda_1 = Null;
$x_ing_fam_deuda_2 = Null;
$x_ing_fam_total = Null;
$x_ing_fam_cuales_1 = Null;
$x_ing_fam_cuales_2 = Null;
$x_ing_fam_cuales_3 = Null;
$x_ing_fam_cuales_4 = Null;
$x_ing_fam_cuales_5 = Null;
$x_flujos_neg_ventas = Null;
$x_flujos_neg_proveedor_1 = Null;
$x_flujos_neg_proveedor_2 = Null;
$x_flujos_neg_proveedor_3 = Null;
$x_flujos_neg_proveedor_4 = Null;
$x_flujos_neg_gasto_1 = Null;
$x_flujos_neg_gasto_2 = Null;
$x_flujos_neg_gasto_3 = Null;
$x_flujos_neg_cual_1 = Null;
$x_flujos_neg_cual_2 = Null;
$x_flujos_neg_cual_3 = Null;
$x_flujos_neg_cual_4 = Null;
$x_flujos_neg_cual_5 = Null;
$x_flujos_neg_cual_6 = Null;
$x_flujos_neg_cual_7 = Null;
$x_ingreso_negocio = Null;
$x_inv_neg_fija_conc_1 = Null;
$x_inv_neg_fija_conc_2 = Null;
$x_inv_neg_fija_conc_3 = Null;
$x_inv_neg_fija_conc_4 = Null;
$x_inv_neg_fija_valor_1 = Null;
$x_inv_neg_fija_valor_2 = Null;
$x_inv_neg_fija_valor_3 = Null;
$x_inv_neg_fija_valor_4 = Null;
$x_inv_neg_total_fija = Null;
$x_inv_neg_var_conc_1 = Null;
$x_inv_neg_var_conc_2 = Null;
$x_inv_neg_var_conc_3 = Null;
$x_inv_neg_var_conc_4 = Null;
$x_inv_neg_var_valor_1 = Null;
$x_inv_neg_var_valor_2 = Null;
$x_inv_neg_var_valor_3 = Null;
$x_inv_neg_var_valor_4 = Null;
$x_inv_neg_total_var = Null;
$x_inv_neg_activos_totales = Null;
$x_fecha = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Get action
$sAction = @$HTTP_POST_VARS["a_add"];
if (($sAction == "") || (($sAction == NULL))) {
	$sKey = @$HTTP_GET_VARS["key"];
	$sKey = (get_magic_quotes_gpc()) ? stripslashes($sKey) : $sKey;
	if ($sKey <> "") {
		$sAction = "C"; // Copy record
	}
	else
	{
		$sAction = "I"; // Display blank record
	}
}
else
{

	// Get fields from form
	$x_adquisicionMaquinaria_id = @$HTTP_POST_VARS["x_adquisicionMaquinaria_id"];
	$x_nombre = @$HTTP_POST_VARS["x_nombre"];
	$x_rfc = @$HTTP_POST_VARS["x_rfc"];
	$x_curp = @$HTTP_POST_VARS["x_curp"];
	$x_fecha_nacimiento = @$HTTP_POST_VARS["x_fecha_nacimiento"];
	$x_sexo = @$HTTP_POST_VARS["x_sexo"];
	$x_integrantes_familia = @$HTTP_POST_VARS["x_integrantes_familia"];
	$x_dependientes = @$HTTP_POST_VARS["x_dependientes"];
	$x_correo_electronico = @$HTTP_POST_VARS["x_correo_electronico"];
	$x_esposa = @$HTTP_POST_VARS["x_esposa"];
	$x_calle_domicilio = @$HTTP_POST_VARS["x_calle_domicilio"];
	$x_colonia_domicilio = @$HTTP_POST_VARS["x_colonia_domicilio"];
	$x_entidad_domicilio = @$HTTP_POST_VARS["x_entidad_domicilio"];
	$x_codigo_postal_domicilio = @$HTTP_POST_VARS["x_codigo_postal_domicilio"];
	$x_ubicacion_domicilio = @$HTTP_POST_VARS["x_ubicacion_domicilio"];
	$x_tipo_vivienda = @$HTTP_POST_VARS["x_tipo_vivienda"];
	$x_telefono_domicilio = @$HTTP_POST_VARS["x_telefono_domicilio"];
	$x_celular = @$HTTP_POST_VARS["x_celular"];
	$x_otro_tel_domicilio_1 = @$HTTP_POST_VARS["x_otro_tel_domicilio_1"];
	$x_otro_telefono_domicilio_2 = @$HTTP_POST_VARS["x_otro_telefono_domicilio_2"];
	$x_antiguedad = @$HTTP_POST_VARS["x_antiguedad"];
	$x_tel_arrendatario_domicilio = @$HTTP_POST_VARS["x_tel_arrendatario_domicilio"];
	$x_renta_mensula_domicilio = @$HTTP_POST_VARS["x_renta_mensula_domicilio"];
	$x_giro_negocio = @$HTTP_POST_VARS["x_giro_negocio"];
	$x_calle_negocio = @$HTTP_POST_VARS["x_calle_negocio"];
	$x_colonia_negocio = @$HTTP_POST_VARS["x_colonia_negocio"];
	$x_entidad_negocio = @$HTTP_POST_VARS["x_entidad_negocio"];
	$x_ubicacion_negocio = @$HTTP_POST_VARS["x_ubicacion_negocio"];
	$x_codigo_postal_negocio = @$HTTP_POST_VARS["x_codigo_postal_negocio"];
	$x_tipo_local_negocio = @$HTTP_POST_VARS["x_tipo_local_negocio"];
	$x_antiguedad_negocio = @$HTTP_POST_VARS["x_antiguedad_negocio"];
	$x_tel_arrendatario_negocio = @$HTTP_POST_VARS["x_tel_arrendatario_negocio"];
	$x_renta_mensual = @$HTTP_POST_VARS["x_renta_mensual"];
	$x_tel_negocio = @$HTTP_POST_VARS["x_tel_negocio"];
	$x_solicitud_compra = @$HTTP_POST_VARS["x_solicitud_compra"];
	$x_referencia_com_1 = @$HTTP_POST_VARS["x_referencia_com_1"];
	$x_referencia_com_2 = @$HTTP_POST_VARS["x_referencia_com_2"];
	$x_referencia_com_3 = @$HTTP_POST_VARS["x_referencia_com_3"];
	$x_referencia_com_4 = @$HTTP_POST_VARS["x_referencia_com_4"];
	$x_tel_referencia_1 = @$HTTP_POST_VARS["x_tel_referencia_1"];
	$x_tel_referencia_2 = @$HTTP_POST_VARS["x_tel_referencia_2"];
	$x_tel_referencia_3 = @$HTTP_POST_VARS["x_tel_referencia_3"];
	$x_tel_referencia_4 = @$HTTP_POST_VARS["x_tel_referencia_4"];
	$x_parentesco_ref_1 = @$HTTP_POST_VARS["x_parentesco_ref_1"];
	$x_parentesco_ref_2 = @$HTTP_POST_VARS["x_parentesco_ref_2"];
	$x_parentesco_ref_3 = @$HTTP_POST_VARS["x_parentesco_ref_3"];
	$x_parentesco_ref_4 = @$HTTP_POST_VARS["x_parentesco_ref_4"];
	$x_ing_fam_negocio = @$HTTP_POST_VARS["x_ing_fam_negocio"];
	$x_ing_fam_otro_th = @$HTTP_POST_VARS["x_ing_fam_otro_th"];
	$x_ing_fam_1 = @$HTTP_POST_VARS["x_ing_fam_1"];
	$x_ing_fam_2 = @$HTTP_POST_VARS["x_ing_fam_2"];
	$x_ing_fam_deuda_1 = @$HTTP_POST_VARS["x_ing_fam_deuda_1"];
	$x_ing_fam_deuda_2 = @$HTTP_POST_VARS["x_ing_fam_deuda_2"];
	$x_ing_fam_total = @$HTTP_POST_VARS["x_ing_fam_total"];
	$x_ing_fam_cuales_1 = @$HTTP_POST_VARS["x_ing_fam_cuales_1"];
	$x_ing_fam_cuales_2 = @$HTTP_POST_VARS["x_ing_fam_cuales_2"];
	$x_ing_fam_cuales_3 = @$HTTP_POST_VARS["x_ing_fam_cuales_3"];
	$x_ing_fam_cuales_4 = @$HTTP_POST_VARS["x_ing_fam_cuales_4"];
	$x_ing_fam_cuales_5 = @$HTTP_POST_VARS["x_ing_fam_cuales_5"];
	$x_flujos_neg_ventas = @$HTTP_POST_VARS["x_flujos_neg_ventas"];
	$x_flujos_neg_proveedor_1 = @$HTTP_POST_VARS["x_flujos_neg_proveedor_1"];
	$x_flujos_neg_proveedor_2 = @$HTTP_POST_VARS["x_flujos_neg_proveedor_2"];
	$x_flujos_neg_proveedor_3 = @$HTTP_POST_VARS["x_flujos_neg_proveedor_3"];
	$x_flujos_neg_proveedor_4 = @$HTTP_POST_VARS["x_flujos_neg_proveedor_4"];
	$x_flujos_neg_gasto_1 = @$HTTP_POST_VARS["x_flujos_neg_gasto_1"];
	$x_flujos_neg_gasto_2 = @$HTTP_POST_VARS["x_flujos_neg_gasto_2"];
	$x_flujos_neg_gasto_3 = @$HTTP_POST_VARS["x_flujos_neg_gasto_3"];
	$x_flujos_neg_cual_1 = @$HTTP_POST_VARS["x_flujos_neg_cual_1"];
	$x_flujos_neg_cual_2 = @$HTTP_POST_VARS["x_flujos_neg_cual_2"];
	$x_flujos_neg_cual_3 = @$HTTP_POST_VARS["x_flujos_neg_cual_3"];
	$x_flujos_neg_cual_4 = @$HTTP_POST_VARS["x_flujos_neg_cual_4"];
	$x_flujos_neg_cual_5 = @$HTTP_POST_VARS["x_flujos_neg_cual_5"];
	$x_flujos_neg_cual_6 = @$HTTP_POST_VARS["x_flujos_neg_cual_6"];
	$x_flujos_neg_cual_7 = @$HTTP_POST_VARS["x_flujos_neg_cual_7"];
	$x_ingreso_negocio = @$HTTP_POST_VARS["x_ingreso_negocio"];
	$x_inv_neg_fija_conc_1 = @$HTTP_POST_VARS["x_inv_neg_fija_conc_1"];
	$x_inv_neg_fija_conc_2 = @$HTTP_POST_VARS["x_inv_neg_fija_conc_2"];
	$x_inv_neg_fija_conc_3 = @$HTTP_POST_VARS["x_inv_neg_fija_conc_3"];
	$x_inv_neg_fija_conc_4 = @$HTTP_POST_VARS["x_inv_neg_fija_conc_4"];
	$x_inv_neg_fija_valor_1 = @$HTTP_POST_VARS["x_inv_neg_fija_valor_1"];
	$x_inv_neg_fija_valor_2 = @$HTTP_POST_VARS["x_inv_neg_fija_valor_2"];
	$x_inv_neg_fija_valor_3 = @$HTTP_POST_VARS["x_inv_neg_fija_valor_3"];
	$x_inv_neg_fija_valor_4 = @$HTTP_POST_VARS["x_inv_neg_fija_valor_4"];
	$x_inv_neg_total_fija = @$HTTP_POST_VARS["x_inv_neg_total_fija"];
	$x_inv_neg_var_conc_1 = @$HTTP_POST_VARS["x_inv_neg_var_conc_1"];
	$x_inv_neg_var_conc_2 = @$HTTP_POST_VARS["x_inv_neg_var_conc_2"];
	$x_inv_neg_var_conc_3 = @$HTTP_POST_VARS["x_inv_neg_var_conc_3"];
	$x_inv_neg_var_conc_4 = @$HTTP_POST_VARS["x_inv_neg_var_conc_4"];
	$x_inv_neg_var_valor_1 = @$HTTP_POST_VARS["x_inv_neg_var_valor_1"];
	$x_inv_neg_var_valor_2 = @$HTTP_POST_VARS["x_inv_neg_var_valor_2"];
	$x_inv_neg_var_valor_3 = @$HTTP_POST_VARS["x_inv_neg_var_valor_3"];
	$x_inv_neg_var_valor_4 = @$HTTP_POST_VARS["x_inv_neg_var_valor_4"];
	$x_inv_neg_total_var = @$HTTP_POST_VARS["x_inv_neg_total_var"];
	$x_inv_neg_activos_totales = @$HTTP_POST_VARS["x_inv_neg_activos_totales"];
	$x_fecha = @$HTTP_POST_VARS["x_fecha"];
}
$conn = phpmkr_db_connect(HOST, USER, PASS,DB);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($sKey,$conn)) { // Load Record based on key
			$HTTP_SESSION_VARS["ewmsg"] = "No Record Found for Key = " . $sKey;
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: adquisicionmaquinarialist.php");
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$HTTP_SESSION_VARS["ewmsg"] = "Add New Record Successful";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: adquisicionmaquinarialist.php");
		}
		break;
}
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<script type="text/javascript">
<!--
function EW_checkMyForm(EW_this) {
if (EW_this.x_nombre && !EW_hasValue(EW_this.x_nombre, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre, "TEXT", "Please enter required field - nombre"))
		return false;
}
if (EW_this.x_rfc && !EW_hasValue(EW_this.x_rfc, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_rfc, "TEXT", "Please enter required field - rfc"))
		return false;
}
if (EW_this.x_curp && !EW_hasValue(EW_this.x_curp, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_curp, "TEXT", "Please enter required field - curp"))
		return false;
}
if (EW_this.x_fecha_nacimiento && !EW_hasValue(EW_this.x_fecha_nacimiento, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_nacimiento, "TEXT", "Please enter required field - fecha nacimiento"))
		return false;
}
if (EW_this.x_fecha_nacimiento && !EW_checkdate(EW_this.x_fecha_nacimiento.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_nacimiento, "TEXT", "Incorrect date, format = yyyy/mm/dd - fecha nacimiento"))
		return false; 
}
if (EW_this.x_sexo && !EW_hasValue(EW_this.x_sexo, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_sexo, "TEXT", "Please enter required field - sexo"))
		return false;
}
if (EW_this.x_integrantes_familia && !EW_hasValue(EW_this.x_integrantes_familia, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_integrantes_familia, "TEXT", "Please enter required field - integrantes familia"))
		return false;
}
if (EW_this.x_integrantes_familia && !EW_checkinteger(EW_this.x_integrantes_familia.value)) {
	if (!EW_onError(EW_this, EW_this.x_integrantes_familia, "TEXT", "Incorrect integer - integrantes familia"))
		return false; 
}
if (EW_this.x_dependientes && !EW_hasValue(EW_this.x_dependientes, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_dependientes, "TEXT", "Please enter required field - dependientes"))
		return false;
}
if (EW_this.x_dependientes && !EW_checkinteger(EW_this.x_dependientes.value)) {
	if (!EW_onError(EW_this, EW_this.x_dependientes, "TEXT", "Incorrect integer - dependientes"))
		return false; 
}
if (EW_this.x_correo_electronico && !EW_hasValue(EW_this.x_correo_electronico, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_correo_electronico, "TEXT", "Please enter required field - correo electronico"))
		return false;
}
if (EW_this.x_correo_electronico && !EW_checkemail(EW_this.x_correo_electronico.value)) {
	if (!EW_onError(EW_this, EW_this.x_correo_electronico, "TEXT", "Incorrect email - correo electronico"))
		return false; 
}
if (EW_this.x_calle_domicilio && !EW_hasValue(EW_this.x_calle_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_calle_domicilio, "TEXT", "Please enter required field - calle domicilio"))
		return false;
}
if (EW_this.x_colonia_domicilio && !EW_hasValue(EW_this.x_colonia_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_colonia_domicilio, "TEXT", "Please enter required field - colonia domicilio"))
		return false;
}
if (EW_this.x_entidad_domicilio && !EW_hasValue(EW_this.x_entidad_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_entidad_domicilio, "TEXT", "Please enter required field - entidad domicilio"))
		return false;
}
if (EW_this.x_codigo_postal_domicilio && !EW_hasValue(EW_this.x_codigo_postal_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal_domicilio, "TEXT", "Please enter required field - codigo postal domicilio"))
		return false;
}
if (EW_this.x_codigo_postal_domicilio && !EW_checkinteger(EW_this.x_codigo_postal_domicilio.value)) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal_domicilio, "TEXT", "Incorrect integer - codigo postal domicilio"))
		return false; 
}
if (EW_this.x_ubicacion_domicilio && !EW_hasValue(EW_this.x_ubicacion_domicilio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ubicacion_domicilio, "TEXT", "Please enter required field - ubicacion domicilio"))
		return false;
}
if (EW_this.x_tipo_vivienda && !EW_hasValue(EW_this.x_tipo_vivienda, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tipo_vivienda, "TEXT", "Please enter required field - tipo vivienda"))
		return false;
}
if (EW_this.x_renta_mensula_domicilio && !EW_checknumber(EW_this.x_renta_mensula_domicilio.value)) {
	if (!EW_onError(EW_this, EW_this.x_renta_mensula_domicilio, "TEXT", "Incorrect floating point number - renta mensula domicilio"))
		return false; 
}
if (EW_this.x_giro_negocio && !EW_hasValue(EW_this.x_giro_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_giro_negocio, "TEXT", "Please enter required field - giro negocio"))
		return false;
}
if (EW_this.x_calle_negocio && !EW_hasValue(EW_this.x_calle_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_calle_negocio, "TEXT", "Please enter required field - calle negocio"))
		return false;
}
if (EW_this.x_colonia_negocio && !EW_hasValue(EW_this.x_colonia_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_colonia_negocio, "TEXT", "Please enter required field - colonia negocio"))
		return false;
}
if (EW_this.x_entidad_negocio && !EW_hasValue(EW_this.x_entidad_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_entidad_negocio, "TEXT", "Please enter required field - entidad negocio"))
		return false;
}
if (EW_this.x_ubicacion_negocio && !EW_hasValue(EW_this.x_ubicacion_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ubicacion_negocio, "TEXT", "Please enter required field - ubicacion negocio"))
		return false;
}
if (EW_this.x_codigo_postal_negocio && !EW_checkinteger(EW_this.x_codigo_postal_negocio.value)) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal_negocio, "TEXT", "Incorrect integer - codigo postal negocio"))
		return false; 
}
if (EW_this.x_renta_mensual && !EW_checknumber(EW_this.x_renta_mensual.value)) {
	if (!EW_onError(EW_this, EW_this.x_renta_mensual, "TEXT", "Incorrect floating point number - renta mensual"))
		return false; 
}
if (EW_this.x_solicitud_compra && !EW_hasValue(EW_this.x_solicitud_compra, "TEXTAREA" )) {
	if (!EW_onError(EW_this, EW_this.x_solicitud_compra, "TEXTAREA", "Please enter required field - solicitud compra"))
		return false;
}
if (EW_this.x_referencia_com_1 && !EW_hasValue(EW_this.x_referencia_com_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_referencia_com_1, "TEXT", "Please enter required field - referencia com 1"))
		return false;
}
if (EW_this.x_tel_referencia_1 && !EW_hasValue(EW_this.x_tel_referencia_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tel_referencia_1, "TEXT", "Please enter required field - tel referencia 1"))
		return false;
}
if (EW_this.x_parentesco_ref_1 && !EW_hasValue(EW_this.x_parentesco_ref_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_parentesco_ref_1, "TEXT", "Please enter required field - parentesco ref 1"))
		return false;
}
if (EW_this.x_ing_fam_negocio && !EW_hasValue(EW_this.x_ing_fam_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_negocio, "TEXT", "Please enter required field - ing fam negocio"))
		return false;
}
if (EW_this.x_ing_fam_negocio && !EW_checknumber(EW_this.x_ing_fam_negocio.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_negocio, "TEXT", "Incorrect floating point number - ing fam negocio"))
		return false; 
}
if (EW_this.x_ing_fam_otro_th && !EW_hasValue(EW_this.x_ing_fam_otro_th, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_otro_th, "TEXT", "Please enter required field - ing fam otro th"))
		return false;
}
if (EW_this.x_ing_fam_otro_th && !EW_checknumber(EW_this.x_ing_fam_otro_th.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_otro_th, "TEXT", "Incorrect floating point number - ing fam otro th"))
		return false; 
}
if (EW_this.x_ing_fam_1 && !EW_hasValue(EW_this.x_ing_fam_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_1, "TEXT", "Please enter required field - ing fam 1"))
		return false;
}
if (EW_this.x_ing_fam_1 && !EW_checknumber(EW_this.x_ing_fam_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_1, "TEXT", "Incorrect floating point number - ing fam 1"))
		return false; 
}
if (EW_this.x_ing_fam_2 && !EW_hasValue(EW_this.x_ing_fam_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_2, "TEXT", "Please enter required field - ing fam 2"))
		return false;
}
if (EW_this.x_ing_fam_2 && !EW_checknumber(EW_this.x_ing_fam_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_2, "TEXT", "Incorrect floating point number - ing fam 2"))
		return false; 
}
if (EW_this.x_ing_fam_deuda_1 && !EW_hasValue(EW_this.x_ing_fam_deuda_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_1, "TEXT", "Please enter required field - ing fam deuda 1"))
		return false;
}
if (EW_this.x_ing_fam_deuda_1 && !EW_checknumber(EW_this.x_ing_fam_deuda_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_1, "TEXT", "Incorrect floating point number - ing fam deuda 1"))
		return false; 
}
if (EW_this.x_ing_fam_deuda_2 && !EW_hasValue(EW_this.x_ing_fam_deuda_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_2, "TEXT", "Please enter required field - ing fam deuda 2"))
		return false;
}
if (EW_this.x_ing_fam_deuda_2 && !EW_checknumber(EW_this.x_ing_fam_deuda_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_2, "TEXT", "Incorrect floating point number - ing fam deuda 2"))
		return false; 
}
if (EW_this.x_ing_fam_total && !EW_hasValue(EW_this.x_ing_fam_total, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_total, "TEXT", "Please enter required field - ing fam total"))
		return false;
}
if (EW_this.x_ing_fam_total && !EW_checknumber(EW_this.x_ing_fam_total.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_total, "TEXT", "Incorrect floating point number - ing fam total"))
		return false; 
}
if (EW_this.x_flujos_neg_ventas && !EW_hasValue(EW_this.x_flujos_neg_ventas, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_ventas, "TEXT", "Please enter required field - flujos neg ventas"))
		return false;
}
if (EW_this.x_flujos_neg_ventas && !EW_checknumber(EW_this.x_flujos_neg_ventas.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_ventas, "TEXT", "Incorrect floating point number - flujos neg ventas"))
		return false; 
}
if (EW_this.x_flujos_neg_proveedor_1 && !EW_hasValue(EW_this.x_flujos_neg_proveedor_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_1, "TEXT", "Please enter required field - flujos neg proveedor 1"))
		return false;
}
if (EW_this.x_flujos_neg_proveedor_1 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_1, "TEXT", "Incorrect floating point number - flujos neg proveedor 1"))
		return false; 
}
if (EW_this.x_flujos_neg_proveedor_2 && !EW_hasValue(EW_this.x_flujos_neg_proveedor_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_2, "TEXT", "Please enter required field - flujos neg proveedor 2"))
		return false;
}
if (EW_this.x_flujos_neg_proveedor_2 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_2, "TEXT", "Incorrect floating point number - flujos neg proveedor 2"))
		return false; 
}
if (EW_this.x_flujos_neg_proveedor_3 && !EW_hasValue(EW_this.x_flujos_neg_proveedor_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_3, "TEXT", "Please enter required field - flujos neg proveedor 3"))
		return false;
}
if (EW_this.x_flujos_neg_proveedor_3 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_3, "TEXT", "Incorrect floating point number - flujos neg proveedor 3"))
		return false; 
}
if (EW_this.x_flujos_neg_proveedor_4 && !EW_hasValue(EW_this.x_flujos_neg_proveedor_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_4, "TEXT", "Please enter required field - flujos neg proveedor 4"))
		return false;
}
if (EW_this.x_flujos_neg_proveedor_4 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_4.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_4, "TEXT", "Incorrect floating point number - flujos neg proveedor 4"))
		return false; 
}
if (EW_this.x_flujos_neg_gasto_1 && !EW_hasValue(EW_this.x_flujos_neg_gasto_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_1, "TEXT", "Please enter required field - flujos neg gasto 1"))
		return false;
}
if (EW_this.x_flujos_neg_gasto_1 && !EW_checknumber(EW_this.x_flujos_neg_gasto_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_1, "TEXT", "Incorrect floating point number - flujos neg gasto 1"))
		return false; 
}
if (EW_this.x_flujos_neg_gasto_2 && !EW_hasValue(EW_this.x_flujos_neg_gasto_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_2, "TEXT", "Please enter required field - flujos neg gasto 2"))
		return false;
}
if (EW_this.x_flujos_neg_gasto_2 && !EW_checknumber(EW_this.x_flujos_neg_gasto_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_2, "TEXT", "Incorrect floating point number - flujos neg gasto 2"))
		return false; 
}
if (EW_this.x_flujos_neg_gasto_3 && !EW_hasValue(EW_this.x_flujos_neg_gasto_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_3, "TEXT", "Please enter required field - flujos neg gasto 3"))
		return false;
}
if (EW_this.x_flujos_neg_gasto_3 && !EW_checknumber(EW_this.x_flujos_neg_gasto_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_3, "TEXT", "Incorrect floating point number - flujos neg gasto 3"))
		return false; 
}
if (EW_this.x_ingreso_negocio && !EW_checknumber(EW_this.x_ingreso_negocio.value)) {
	if (!EW_onError(EW_this, EW_this.x_ingreso_negocio, "TEXT", "Incorrect floating point number - ingreso negocio"))
		return false; 
}
if (EW_this.x_inv_neg_fija_valor_1 && !EW_hasValue(EW_this.x_inv_neg_fija_valor_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_1, "TEXT", "Please enter required field - inv neg fija valor 1"))
		return false;
}
if (EW_this.x_inv_neg_fija_valor_1 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_1, "TEXT", "Incorrect floating point number - inv neg fija valor 1"))
		return false; 
}
if (EW_this.x_inv_neg_fija_valor_2 && !EW_hasValue(EW_this.x_inv_neg_fija_valor_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_2, "TEXT", "Please enter required field - inv neg fija valor 2"))
		return false;
}
if (EW_this.x_inv_neg_fija_valor_2 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_2, "TEXT", "Incorrect floating point number - inv neg fija valor 2"))
		return false; 
}
if (EW_this.x_inv_neg_fija_valor_3 && !EW_hasValue(EW_this.x_inv_neg_fija_valor_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_3, "TEXT", "Please enter required field - inv neg fija valor 3"))
		return false;
}
if (EW_this.x_inv_neg_fija_valor_3 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_3, "TEXT", "Incorrect floating point number - inv neg fija valor 3"))
		return false; 
}
if (EW_this.x_inv_neg_fija_valor_4 && !EW_hasValue(EW_this.x_inv_neg_fija_valor_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_4, "TEXT", "Please enter required field - inv neg fija valor 4"))
		return false;
}
if (EW_this.x_inv_neg_fija_valor_4 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_4.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_4, "TEXT", "Incorrect floating point number - inv neg fija valor 4"))
		return false; 
}
if (EW_this.x_inv_neg_total_fija && !EW_hasValue(EW_this.x_inv_neg_total_fija, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_fija, "TEXT", "Please enter required field - inv neg total fija"))
		return false;
}
if (EW_this.x_inv_neg_total_fija && !EW_checknumber(EW_this.x_inv_neg_total_fija.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_fija, "TEXT", "Incorrect floating point number - inv neg total fija"))
		return false; 
}
if (EW_this.x_inv_neg_var_valor_1 && !EW_hasValue(EW_this.x_inv_neg_var_valor_1, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_1, "TEXT", "Please enter required field - inv neg var valor 1"))
		return false;
}
if (EW_this.x_inv_neg_var_valor_1 && !EW_checknumber(EW_this.x_inv_neg_var_valor_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_1, "TEXT", "Incorrect floating point number - inv neg var valor 1"))
		return false; 
}
if (EW_this.x_inv_neg_var_valor_2 && !EW_hasValue(EW_this.x_inv_neg_var_valor_2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_2, "TEXT", "Please enter required field - inv neg var valor 2"))
		return false;
}
if (EW_this.x_inv_neg_var_valor_2 && !EW_checknumber(EW_this.x_inv_neg_var_valor_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_2, "TEXT", "Incorrect floating point number - inv neg var valor 2"))
		return false; 
}
if (EW_this.x_inv_neg_var_valor_3 && !EW_hasValue(EW_this.x_inv_neg_var_valor_3, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_3, "TEXT", "Please enter required field - inv neg var valor 3"))
		return false;
}
if (EW_this.x_inv_neg_var_valor_3 && !EW_checknumber(EW_this.x_inv_neg_var_valor_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_3, "TEXT", "Incorrect floating point number - inv neg var valor 3"))
		return false; 
}
if (EW_this.x_inv_neg_var_valor_4 && !EW_hasValue(EW_this.x_inv_neg_var_valor_4, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_4, "TEXT", "Please enter required field - inv neg var valor 4"))
		return false;
}
if (EW_this.x_inv_neg_var_valor_4 && !EW_checknumber(EW_this.x_inv_neg_var_valor_4.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_4, "TEXT", "Incorrect floating point number - inv neg var valor 4"))
		return false; 
}
if (EW_this.x_inv_neg_total_var && !EW_hasValue(EW_this.x_inv_neg_total_var, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_var, "TEXT", "Please enter required field - inv neg total var"))
		return false;
}
if (EW_this.x_inv_neg_total_var && !EW_checknumber(EW_this.x_inv_neg_total_var.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_var, "TEXT", "Incorrect floating point number - inv neg total var"))
		return false; 
}
if (EW_this.x_inv_neg_activos_totales && !EW_hasValue(EW_this.x_inv_neg_activos_totales, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_activos_totales, "TEXT", "Please enter required field - inv neg activos totales"))
		return false;
}
if (EW_this.x_inv_neg_activos_totales && !EW_checknumber(EW_this.x_inv_neg_activos_totales.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_activos_totales, "TEXT", "Incorrect floating point number - inv neg activos totales"))
		return false; 
}
if (EW_this.x_fecha && !EW_hasValue(EW_this.x_fecha, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha, "TEXT", "Please enter required field - fecha"))
		return false;
}
if (EW_this.x_fecha && !EW_checkdate(EW_this.x_fecha.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha, "TEXT", "Incorrect date, format = yyyy/mm/dd - fecha"))
		return false; 
}
return true;
}

//-->
</script>
<script type="text/javascript" src="popcalendar.js"></script>
<!-- New popup calendar -->
<!--link rel="stylesheet" type="text/css" media="all" href="calendar/calendar-win2k-1.css" title="win2k-1" /-->
<!--script type="text/javascript" src="calendar/calendar.js"></script-->
<!--script type="text/javascript" src="calendar/calendar-en.js"></script-->
<!--script type="text/javascript" src="calendar/calendar-setup.js"></script-->
<p><span class="phpmaker">Add to TABLE: adquisicionmaquinaria<br><br><a href="adquisicionmaquinarialist.php">Back to List</a></span></p>
<form name="adquisicionmaquinariaadd" id="adquisicionmaquinariaadd" action="adquisicionmaquinariaadd.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_add" value="A">
<table class="ewTable">
	<tr>
		<td class="ewTableHeader"><span>nombre</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_nombre" id="x_nombre" value="<?php echo htmlspecialchars(@$x_nombre) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>rfc</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_rfc" id="x_rfc" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_rfc) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>curp</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_curp" id="x_curp" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_curp) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>fecha nacimiento</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_fecha_nacimiento" id="x_fecha_nacimiento" value="<?php echo FormatDateTime(@$x_fecha_nacimiento,5); ?>">
&nbsp;<input type="image" src="images/ew_calendar.gif" alt="Pick a Date" onClick="popUpCalendar(this, this.form.x_fecha_nacimiento,'yyyy/mm/dd');return false;">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>sexo</span></td>
		<td class="ewTableAltRow"><span>
<?php if (!($x_sexo != NULL) || ($x_sexo == "")) { $x_sexo = "MASCULINO";} // Set default value ?>
<input type="text" name="x_sexo" id="x_sexo" size="30" maxlength="60" value="<?php echo htmlspecialchars(@$x_sexo) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>integrantes familia</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_integrantes_familia" id="x_integrantes_familia" size="30" value="<?php echo htmlspecialchars(@$x_integrantes_familia) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>dependientes</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_dependientes" id="x_dependientes" size="30" value="<?php echo htmlspecialchars(@$x_dependientes) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>correo electronico</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_correo_electronico" id="x_correo_electronico" value="<?php echo htmlspecialchars(@$x_correo_electronico) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>esposa</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_esposa" id="x_esposa" value="<?php echo htmlspecialchars(@$x_esposa) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>calle domicilio</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_calle_domicilio" id="x_calle_domicilio" value="<?php echo htmlspecialchars(@$x_calle_domicilio) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>colonia domicilio</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_colonia_domicilio" id="x_colonia_domicilio" value="<?php echo htmlspecialchars(@$x_colonia_domicilio) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>entidad domicilio</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_entidad_domicilio" id="x_entidad_domicilio" value="<?php echo htmlspecialchars(@$x_entidad_domicilio) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>codigo postal domicilio</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_codigo_postal_domicilio" id="x_codigo_postal_domicilio" size="30" value="<?php echo htmlspecialchars(@$x_codigo_postal_domicilio) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>ubicacion domicilio</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_ubicacion_domicilio" id="x_ubicacion_domicilio" value="<?php echo htmlspecialchars(@$x_ubicacion_domicilio) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>tipo vivienda</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_tipo_vivienda" id="x_tipo_vivienda" value="<?php echo htmlspecialchars(@$x_tipo_vivienda) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>telefono domicilio</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_telefono_domicilio" id="x_telefono_domicilio" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_telefono_domicilio) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>celular</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_celular" id="x_celular" value="<?php echo htmlspecialchars(@$x_celular) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>otro tel domicilio 1</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_otro_tel_domicilio_1" id="x_otro_tel_domicilio_1" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_otro_tel_domicilio_1) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>otro telefono domicilio 2</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_otro_telefono_domicilio_2" id="x_otro_telefono_domicilio_2" value="<?php echo htmlspecialchars(@$x_otro_telefono_domicilio_2) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>antiguedad</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_antiguedad" id="x_antiguedad" value="<?php echo htmlspecialchars(@$x_antiguedad) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>tel arrendatario domicilio</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_tel_arrendatario_domicilio" id="x_tel_arrendatario_domicilio" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_tel_arrendatario_domicilio) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>renta mensula domicilio</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_renta_mensula_domicilio" id="x_renta_mensula_domicilio" size="30" value="<?php echo htmlspecialchars(@$x_renta_mensula_domicilio) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>giro negocio</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_giro_negocio" id="x_giro_negocio" value="<?php echo htmlspecialchars(@$x_giro_negocio) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>calle negocio</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_calle_negocio" id="x_calle_negocio" value="<?php echo htmlspecialchars(@$x_calle_negocio) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>colonia negocio</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_colonia_negocio" id="x_colonia_negocio" value="<?php echo htmlspecialchars(@$x_colonia_negocio) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>entidad negocio</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_entidad_negocio" id="x_entidad_negocio" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_entidad_negocio) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>ubicacion negocio</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_ubicacion_negocio" id="x_ubicacion_negocio" value="<?php echo htmlspecialchars(@$x_ubicacion_negocio) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>codigo postal negocio</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_codigo_postal_negocio" id="x_codigo_postal_negocio" size="30" value="<?php echo htmlspecialchars(@$x_codigo_postal_negocio) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>tipo local negocio</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_tipo_local_negocio" id="x_tipo_local_negocio" value="<?php echo htmlspecialchars(@$x_tipo_local_negocio) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>antiguedad negocio</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_antiguedad_negocio" id="x_antiguedad_negocio" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_antiguedad_negocio) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>tel arrendatario negocio</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_tel_arrendatario_negocio" id="x_tel_arrendatario_negocio" value="<?php echo htmlspecialchars(@$x_tel_arrendatario_negocio) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>renta mensual</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_renta_mensual" id="x_renta_mensual" size="30" value="<?php echo htmlspecialchars(@$x_renta_mensual) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>tel negocio</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_tel_negocio" id="x_tel_negocio" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_tel_negocio) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>solicitud compra</span></td>
		<td class="ewTableAltRow"><span>
<textarea cols="35" rows="4" id="x_solicitud_compra" name="x_solicitud_compra"><?php echo @$x_solicitud_compra; ?></textarea>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>referencia com 1</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_referencia_com_1" id="x_referencia_com_1" value="<?php echo htmlspecialchars(@$x_referencia_com_1) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>referencia com 2</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_referencia_com_2" id="x_referencia_com_2" value="<?php echo htmlspecialchars(@$x_referencia_com_2) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>referencia com 3</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_referencia_com_3" id="x_referencia_com_3" value="<?php echo htmlspecialchars(@$x_referencia_com_3) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>referencia com 4</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_referencia_com_4" id="x_referencia_com_4" value="<?php echo htmlspecialchars(@$x_referencia_com_4) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>tel referencia 1</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_tel_referencia_1" id="x_tel_referencia_1" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_tel_referencia_1) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>tel referencia 2</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_tel_referencia_2" id="x_tel_referencia_2" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_tel_referencia_2) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>tel referencia 3</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_tel_referencia_3" id="x_tel_referencia_3" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_tel_referencia_3) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>tel referencia 4</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_tel_referencia_4" id="x_tel_referencia_4" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_tel_referencia_4) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>parentesco ref 1</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_parentesco_ref_1" id="x_parentesco_ref_1" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_parentesco_ref_1) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>parentesco ref 2</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_parentesco_ref_2" id="x_parentesco_ref_2" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_parentesco_ref_2) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>parentesco ref 3</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_parentesco_ref_3" id="x_parentesco_ref_3" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_parentesco_ref_3) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>parentesco ref 4</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_parentesco_ref_4" id="x_parentesco_ref_4" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_parentesco_ref_4) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>ing fam negocio</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_ing_fam_negocio" id="x_ing_fam_negocio" size="30" value="<?php echo htmlspecialchars(@$x_ing_fam_negocio) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>ing fam otro th</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_ing_fam_otro_th" id="x_ing_fam_otro_th" size="30" value="<?php echo htmlspecialchars(@$x_ing_fam_otro_th) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>ing fam 1</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_ing_fam_1" id="x_ing_fam_1" size="30" value="<?php echo htmlspecialchars(@$x_ing_fam_1) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>ing fam 2</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_ing_fam_2" id="x_ing_fam_2" size="30" value="<?php echo htmlspecialchars(@$x_ing_fam_2) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>ing fam deuda 1</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_ing_fam_deuda_1" id="x_ing_fam_deuda_1" size="30" value="<?php echo htmlspecialchars(@$x_ing_fam_deuda_1) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>ing fam deuda 2</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_ing_fam_deuda_2" id="x_ing_fam_deuda_2" size="30" value="<?php echo htmlspecialchars(@$x_ing_fam_deuda_2) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>ing fam total</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_ing_fam_total" id="x_ing_fam_total" size="30" value="<?php echo htmlspecialchars(@$x_ing_fam_total) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>ing fam cuales 1</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_ing_fam_cuales_1" id="x_ing_fam_cuales_1" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_ing_fam_cuales_1) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>ing fam cuales 2</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_ing_fam_cuales_2" id="x_ing_fam_cuales_2" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_ing_fam_cuales_2) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>ing fam cuales 3</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_ing_fam_cuales_3" id="x_ing_fam_cuales_3" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_ing_fam_cuales_3) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>ing fam cuales 4</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_ing_fam_cuales_4" id="x_ing_fam_cuales_4" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_ing_fam_cuales_4) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>ing fam cuales 5</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_ing_fam_cuales_5" id="x_ing_fam_cuales_5" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_ing_fam_cuales_5) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>flujos neg ventas</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_flujos_neg_ventas" id="x_flujos_neg_ventas" size="30" value="<?php echo htmlspecialchars(@$x_flujos_neg_ventas) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>flujos neg proveedor 1</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_flujos_neg_proveedor_1" id="x_flujos_neg_proveedor_1" size="30" value="<?php echo htmlspecialchars(@$x_flujos_neg_proveedor_1) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>flujos neg proveedor 2</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_flujos_neg_proveedor_2" id="x_flujos_neg_proveedor_2" size="30" value="<?php echo htmlspecialchars(@$x_flujos_neg_proveedor_2) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>flujos neg proveedor 3</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_flujos_neg_proveedor_3" id="x_flujos_neg_proveedor_3" size="30" value="<?php echo htmlspecialchars(@$x_flujos_neg_proveedor_3) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>flujos neg proveedor 4</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_flujos_neg_proveedor_4" id="x_flujos_neg_proveedor_4" size="30" value="<?php echo htmlspecialchars(@$x_flujos_neg_proveedor_4) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>flujos neg gasto 1</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_flujos_neg_gasto_1" id="x_flujos_neg_gasto_1" size="30" value="<?php echo htmlspecialchars(@$x_flujos_neg_gasto_1) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>flujos neg gasto 2</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_flujos_neg_gasto_2" id="x_flujos_neg_gasto_2" size="30" value="<?php echo htmlspecialchars(@$x_flujos_neg_gasto_2) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>flujos neg gasto 3</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_flujos_neg_gasto_3" id="x_flujos_neg_gasto_3" size="30" value="<?php echo htmlspecialchars(@$x_flujos_neg_gasto_3) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>flujos neg cual 1</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_flujos_neg_cual_1" id="x_flujos_neg_cual_1" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_1) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>flujos neg cual 2</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_flujos_neg_cual_2" id="x_flujos_neg_cual_2" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_2) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>flujos neg cual 3</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_flujos_neg_cual_3" id="x_flujos_neg_cual_3" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_3) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>flujos neg cual 4</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_flujos_neg_cual_4" id="x_flujos_neg_cual_4" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_4) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>flujos neg cual 5</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_flujos_neg_cual_5" id="x_flujos_neg_cual_5" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_5) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>flujos neg cual 6</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_flujos_neg_cual_6" id="x_flujos_neg_cual_6" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_6) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>flujos neg cual 7</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_flujos_neg_cual_7" id="x_flujos_neg_cual_7" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_7) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>ingreso negocio</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_ingreso_negocio" id="x_ingreso_negocio" size="30" value="<?php echo htmlspecialchars(@$x_ingreso_negocio) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>inv neg fija conc 1</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_inv_neg_fija_conc_1" id="x_inv_neg_fija_conc_1" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_inv_neg_fija_conc_1) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>inv neg fija conc 2</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_inv_neg_fija_conc_2" id="x_inv_neg_fija_conc_2" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_inv_neg_fija_conc_2) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>inv neg fija conc 3</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_inv_neg_fija_conc_3" id="x_inv_neg_fija_conc_3" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_inv_neg_fija_conc_3) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>inv neg fija conc 4</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_inv_neg_fija_conc_4" id="x_inv_neg_fija_conc_4" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_inv_neg_fija_conc_4) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>inv neg fija valor 1</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_inv_neg_fija_valor_1" id="x_inv_neg_fija_valor_1" size="30" value="<?php echo htmlspecialchars(@$x_inv_neg_fija_valor_1) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>inv neg fija valor 2</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_inv_neg_fija_valor_2" id="x_inv_neg_fija_valor_2" size="30" value="<?php echo htmlspecialchars(@$x_inv_neg_fija_valor_2) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>inv neg fija valor 3</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_inv_neg_fija_valor_3" id="x_inv_neg_fija_valor_3" size="30" value="<?php echo htmlspecialchars(@$x_inv_neg_fija_valor_3) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>inv neg fija valor 4</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_inv_neg_fija_valor_4" id="x_inv_neg_fija_valor_4" size="30" value="<?php echo htmlspecialchars(@$x_inv_neg_fija_valor_4) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>inv neg total fija</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_inv_neg_total_fija" id="x_inv_neg_total_fija" size="30" value="<?php echo htmlspecialchars(@$x_inv_neg_total_fija) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>inv neg var conc 1</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_inv_neg_var_conc_1" id="x_inv_neg_var_conc_1" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_inv_neg_var_conc_1) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>inv neg var conc 2</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_inv_neg_var_conc_2" id="x_inv_neg_var_conc_2" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_inv_neg_var_conc_2) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>inv neg var conc 3</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_inv_neg_var_conc_3" id="x_inv_neg_var_conc_3" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_inv_neg_var_conc_3) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>inv neg var conc 4</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_inv_neg_var_conc_4" id="x_inv_neg_var_conc_4" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_inv_neg_var_conc_4) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>inv neg var valor 1</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_inv_neg_var_valor_1" id="x_inv_neg_var_valor_1" size="30" value="<?php echo htmlspecialchars(@$x_inv_neg_var_valor_1) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>inv neg var valor 2</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_inv_neg_var_valor_2" id="x_inv_neg_var_valor_2" size="30" value="<?php echo htmlspecialchars(@$x_inv_neg_var_valor_2) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>inv neg var valor 3</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_inv_neg_var_valor_3" id="x_inv_neg_var_valor_3" size="30" value="<?php echo htmlspecialchars(@$x_inv_neg_var_valor_3) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>inv neg var valor 4</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_inv_neg_var_valor_4" id="x_inv_neg_var_valor_4" size="30" value="<?php echo htmlspecialchars(@$x_inv_neg_var_valor_4) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>inv neg total var</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_inv_neg_total_var" id="x_inv_neg_total_var" size="30" value="<?php echo htmlspecialchars(@$x_inv_neg_total_var) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>inv neg activos totales</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_inv_neg_activos_totales" id="x_inv_neg_activos_totales" size="30" value="<?php echo htmlspecialchars(@$x_inv_neg_activos_totales) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>fecha</span></td>
		<td class="ewTableAltRow"><div>
<input type="text" name="x_fecha" id="x_fecha" value="<?php echo FormatDateTime(@$x_fecha,5); ?>">
&nbsp;<input type="image" src="images/ew_calendar.gif" alt="Pick a Date" onClick="popUpCalendar(this, this.form.x_fecha,'yyyy/mm/dd');return false;"></div>
</td>
	</tr>
</table>
<p>
<input type="submit" name="Action" value="ADD">
</form>
<?php include ("footer.php") ?>
<?php
phpmkr_db_close($conn);
?>
<?php

//-------------------------------------------------------------------------------
// Function LoadData
// - Load Data based on Key Value sKey
// - Variables setup: field variables

function LoadData($sKey,$conn)
{
	global $HTTP_SESSION_VARS;
	$sKeyWrk = "" . addslashes($sKey) . "";
	$sSql = "SELECT * FROM `adquisicionmaquinaria`";
	$sSql .= " WHERE `adquisicionMaquinaria_id` = " . $sKeyWrk;
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sGroupBy <> "") {
		$sSql .= " GROUP BY " . $sGroupBy;
	}
	if ($sHaving <> "") {
		$sSql .= " HAVING " . $sHaving;
	}
	if ($sOrderBy <> "") {
		$sSql .= " ORDER BY " . $sOrderBy;
	}
	$rs = phpmkr_query($sSql,$conn);
	if (phpmkr_num_rows($rs) == 0) {
		$LoadData = false;
	}else{
		$LoadData = true;
		$row = phpmkr_fetch_array($rs);

		// Get the field contents
		$GLOBALS["x_adquisicionMaquinaria_id"] = $row["adquisicionMaquinaria_id"];
		$GLOBALS["x_nombre"] = $row["nombre"];
		$GLOBALS["x_rfc"] = $row["rfc"];
		$GLOBALS["x_curp"] = $row["curp"];
		$GLOBALS["x_fecha_nacimiento"] = $row["fecha_nacimiento"];
		$GLOBALS["x_sexo"] = $row["sexo"];
		$GLOBALS["x_integrantes_familia"] = $row["integrantes_familia"];
		$GLOBALS["x_dependientes"] = $row["dependientes"];
		$GLOBALS["x_correo_electronico"] = $row["correo_electronico"];
		$GLOBALS["x_esposa"] = $row["esposa"];
		$GLOBALS["x_calle_domicilio"] = $row["calle_domicilio"];
		$GLOBALS["x_colonia_domicilio"] = $row["colonia_domicilio"];
		$GLOBALS["x_entidad_domicilio"] = $row["entidad_domicilio"];
		$GLOBALS["x_codigo_postal_domicilio"] = $row["codigo_postal_domicilio"];
		$GLOBALS["x_ubicacion_domicilio"] = $row["ubicacion_domicilio"];
		$GLOBALS["x_tipo_vivienda"] = $row["tipo_vivienda"];
		$GLOBALS["x_telefono_domicilio"] = $row["telefono_domicilio"];
		$GLOBALS["x_celular"] = $row["celular"];
		$GLOBALS["x_otro_tel_domicilio_1"] = $row["otro_tel_domicilio_1"];
		$GLOBALS["x_otro_telefono_domicilio_2"] = $row["otro_telefono_domicilio_2"];
		$GLOBALS["x_antiguedad"] = $row["antiguedad"];
		$GLOBALS["x_tel_arrendatario_domicilio"] = $row["tel_arrendatario_domicilio"];
		$GLOBALS["x_renta_mensula_domicilio"] = $row["renta_mensula_domicilio"];
		$GLOBALS["x_giro_negocio"] = $row["giro_negocio"];
		$GLOBALS["x_calle_negocio"] = $row["calle_negocio"];
		$GLOBALS["x_colonia_negocio"] = $row["colonia_negocio"];
		$GLOBALS["x_entidad_negocio"] = $row["entidad_negocio"];
		$GLOBALS["x_ubicacion_negocio"] = $row["ubicacion_negocio"];
		$GLOBALS["x_codigo_postal_negocio"] = $row["codigo_postal_negocio"];
		$GLOBALS["x_tipo_local_negocio"] = $row["tipo_local_negocio"];
		$GLOBALS["x_antiguedad_negocio"] = $row["antiguedad_negocio"];
		$GLOBALS["x_tel_arrendatario_negocio"] = $row["tel_arrendatario_negocio"];
		$GLOBALS["x_renta_mensual"] = $row["renta_mensual"];
		$GLOBALS["x_tel_negocio"] = $row["tel_negocio"];
		$GLOBALS["x_solicitud_compra"] = $row["solicitud_compra"];
		$GLOBALS["x_referencia_com_1"] = $row["referencia_com_1"];
		$GLOBALS["x_referencia_com_2"] = $row["referencia_com_2"];
		$GLOBALS["x_referencia_com_3"] = $row["referencia_com_3"];
		$GLOBALS["x_referencia_com_4"] = $row["referencia_com_4"];
		$GLOBALS["x_tel_referencia_1"] = $row["tel_referencia_1"];
		$GLOBALS["x_tel_referencia_2"] = $row["tel_referencia_2"];
		$GLOBALS["x_tel_referencia_3"] = $row["tel_referencia_3"];
		$GLOBALS["x_tel_referencia_4"] = $row["tel_referencia_4"];
		$GLOBALS["x_parentesco_ref_1"] = $row["parentesco_ref_1"];
		$GLOBALS["x_parentesco_ref_2"] = $row["parentesco_ref_2"];
		$GLOBALS["x_parentesco_ref_3"] = $row["parentesco_ref_3"];
		$GLOBALS["x_parentesco_ref_4"] = $row["parentesco_ref_4"];
		$GLOBALS["x_ing_fam_negocio"] = $row["ing_fam_negocio"];
		$GLOBALS["x_ing_fam_otro_th"] = $row["ing_fam_otro_th"];
		$GLOBALS["x_ing_fam_1"] = $row["ing_fam_1"];
		$GLOBALS["x_ing_fam_2"] = $row["ing_fam_2"];
		$GLOBALS["x_ing_fam_deuda_1"] = $row["ing_fam_deuda_1"];
		$GLOBALS["x_ing_fam_deuda_2"] = $row["ing_fam_deuda_2"];
		$GLOBALS["x_ing_fam_total"] = $row["ing_fam_total"];
		$GLOBALS["x_ing_fam_cuales_1"] = $row["ing_fam_cuales_1"];
		$GLOBALS["x_ing_fam_cuales_2"] = $row["ing_fam_cuales_2"];
		$GLOBALS["x_ing_fam_cuales_3"] = $row["ing_fam_cuales_3"];
		$GLOBALS["x_ing_fam_cuales_4"] = $row["ing_fam_cuales_4"];
		$GLOBALS["x_ing_fam_cuales_5"] = $row["ing_fam_cuales_5"];
		$GLOBALS["x_flujos_neg_ventas"] = $row["flujos_neg_ventas"];
		$GLOBALS["x_flujos_neg_proveedor_1"] = $row["flujos_neg_proveedor_1"];
		$GLOBALS["x_flujos_neg_proveedor_2"] = $row["flujos_neg_proveedor_2"];
		$GLOBALS["x_flujos_neg_proveedor_3"] = $row["flujos_neg_proveedor_3"];
		$GLOBALS["x_flujos_neg_proveedor_4"] = $row["flujos_neg_proveedor_4"];
		$GLOBALS["x_flujos_neg_gasto_1"] = $row["flujos_neg_gasto_1"];
		$GLOBALS["x_flujos_neg_gasto_2"] = $row["flujos_neg_gasto_2"];
		$GLOBALS["x_flujos_neg_gasto_3"] = $row["flujos_neg_gasto_3"];
		$GLOBALS["x_flujos_neg_cual_1"] = $row["flujos_neg_cual_1"];
		$GLOBALS["x_flujos_neg_cual_2"] = $row["flujos_neg_cual_2"];
		$GLOBALS["x_flujos_neg_cual_3"] = $row["flujos_neg_cual_3"];
		$GLOBALS["x_flujos_neg_cual_4"] = $row["flujos_neg_cual_4"];
		$GLOBALS["x_flujos_neg_cual_5"] = $row["flujos_neg_cual_5"];
		$GLOBALS["x_flujos_neg_cual_6"] = $row["flujos_neg_cual_6"];
		$GLOBALS["x_flujos_neg_cual_7"] = $row["flujos_neg_cual_7"];
		$GLOBALS["x_ingreso_negocio"] = $row["ingreso_negocio"];
		$GLOBALS["x_inv_neg_fija_conc_1"] = $row["inv_neg_fija_conc_1"];
		$GLOBALS["x_inv_neg_fija_conc_2"] = $row["inv_neg_fija_conc_2"];
		$GLOBALS["x_inv_neg_fija_conc_3"] = $row["inv_neg_fija_conc_3"];
		$GLOBALS["x_inv_neg_fija_conc_4"] = $row["inv_neg_fija_conc_4"];
		$GLOBALS["x_inv_neg_fija_valor_1"] = $row["inv_neg_fija_valor_1"];
		$GLOBALS["x_inv_neg_fija_valor_2"] = $row["inv_neg_fija_valor_2"];
		$GLOBALS["x_inv_neg_fija_valor_3"] = $row["inv_neg_fija_valor_3"];
		$GLOBALS["x_inv_neg_fija_valor_4"] = $row["inv_neg_fija_valor_4"];
		$GLOBALS["x_inv_neg_total_fija"] = $row["inv_neg_total_fija"];
		$GLOBALS["x_inv_neg_var_conc_1"] = $row["inv_neg_var_conc_1"];
		$GLOBALS["x_inv_neg_var_conc_2"] = $row["inv_neg_var_conc_2"];
		$GLOBALS["x_inv_neg_var_conc_3"] = $row["inv_neg_var_conc_3"];
		$GLOBALS["x_inv_neg_var_conc_4"] = $row["inv_neg_var_conc_4"];
		$GLOBALS["x_inv_neg_var_valor_1"] = $row["inv_neg_var_valor_1"];
		$GLOBALS["x_inv_neg_var_valor_2"] = $row["inv_neg_var_valor_2"];
		$GLOBALS["x_inv_neg_var_valor_3"] = $row["inv_neg_var_valor_3"];
		$GLOBALS["x_inv_neg_var_valor_4"] = $row["inv_neg_var_valor_4"];
		$GLOBALS["x_inv_neg_total_var"] = $row["inv_neg_total_var"];
		$GLOBALS["x_inv_neg_activos_totales"] = $row["inv_neg_activos_totales"];
		$GLOBALS["x_fecha"] = $row["fecha"];
	}
	phpmkr_free_result($rs);
	return $LoadData;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function AddData
// - Add Data
// - Variables used: field variables

function AddData($conn)
{
	global $HTTP_SESSION_VARS;
	global $HTTP_POST_VARS;
	global $HTTP_POST_FILES;
	global $HTTP_ENV_VARS;

	// Add New Record
	$sSql = "SELECT * FROM `adquisicionmaquinaria`";
	$sSql .= " WHERE 0 = 1";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sGroupBy <> "") {
		$sSql .= " GROUP BY " . $sGroupBy;
	}
	if ($sHaving <> "") {
		$sSql .= " HAVING " . $sHaving;
	}
	if ($sOrderBy <> "") {
		$sSql .= " ORDER BY " . $sOrderBy;
	}

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
	phpmkr_query($strsql, $conn);
	return true;
}
?>
