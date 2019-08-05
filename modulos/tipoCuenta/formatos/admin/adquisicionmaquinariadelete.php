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

// Load Key Parameters
$sKey = @$HTTP_GET_VARS["key"];
if (($sKey == "") || (($sKey == NULL))) {
	$sKey = @$HTTP_POST_VARS["key_d"];
}
$sDbWhere = "";
$arRecKey = split(",",$sKey);

// Single delete record
if (($sKey == "") || (($sKey == NULL))) {
	ob_end_clean();
	header("Location: adquisicionmaquinarialist.php");
}
	$sKey = (get_magic_quotes_gpc()) ? $sKey : addslashes($sKey);
$sDbWhere .= "`adquisicionMaquinaria_id`=" . trim($sKey) . "";

// Get action
$sAction = @$HTTP_POST_VARS["a_delete"];
if (($sAction == "") || (($sAction == NULL))) {
	$sAction = "I";	// Display with input box
}
$conn = phpmkr_db_connect(HOST, USER, PASS,DB);
switch ($sAction)
{
	case "I": // Display
		if (LoadRecordCount($sDbWhere,$conn) <= 0) {
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: adquisicionmaquinarialist.php");
		}
		break;
	case "D": // Delete
		if (DeleteData($sDbWhere,$conn)) {
			$HTTP_SESSION_VARS["ewmsg"] = "Delete Successful For Key = " . stripslashes($sKey);
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: adquisicionmaquinarialist.php");
		}
		break;
}
?>
<?php include ("header.php") ?>
<p><span class="phpmaker">Delete from TABLE: adquisicionmaquinaria<br><br><a href="adquisicionmaquinarialist.php">Back to List</a></span></p>
<form action="adquisicionmaquinariadelete.php" method="post">
<p>
<input type="hidden" name="a_delete" value="D">
<?php $sKey = (get_magic_quotes_gpc()) ? stripslashes($sKey) : $sKey; ?>
<input type="hidden" name="key_d" value="<?php echo  htmlspecialchars($sKey); ?>">
<table class="ewTable">
	<tr class="ewTableHeader">
		<td valign="top"><span>adquisicion Maquinaria id</span></td>
		<td valign="top"><span>nombre</span></td>
		<td valign="top"><span>rfc</span></td>
		<td valign="top"><span>curp</span></td>
		<td valign="top"><span>fecha nacimiento</span></td>
		<td valign="top"><span>sexo</span></td>
		<td valign="top"><span>integrantes familia</span></td>
		<td valign="top"><span>dependientes</span></td>
		<td valign="top"><span>correo electronico</span></td>
		<td valign="top"><span>esposa</span></td>
		<td valign="top"><span>calle domicilio</span></td>
		<td valign="top"><span>colonia domicilio</span></td>
		<td valign="top"><span>entidad domicilio</span></td>
		<td valign="top"><span>codigo postal domicilio</span></td>
		<td valign="top"><span>ubicacion domicilio</span></td>
		<td valign="top"><span>tipo vivienda</span></td>
		<td valign="top"><span>telefono domicilio</span></td>
		<td valign="top"><span>celular</span></td>
		<td valign="top"><span>otro tel domicilio 1</span></td>
		<td valign="top"><span>otro telefono domicilio 2</span></td>
		<td valign="top"><span>antiguedad</span></td>
		<td valign="top"><span>tel arrendatario domicilio</span></td>
		<td valign="top"><span>renta mensula domicilio</span></td>
		<td valign="top"><span>giro negocio</span></td>
		<td valign="top"><span>calle negocio</span></td>
		<td valign="top"><span>colonia negocio</span></td>
		<td valign="top"><span>entidad negocio</span></td>
		<td valign="top"><span>ubicacion negocio</span></td>
		<td valign="top"><span>codigo postal negocio</span></td>
		<td valign="top"><span>tipo local negocio</span></td>
		<td valign="top"><span>antiguedad negocio</span></td>
		<td valign="top"><span>tel arrendatario negocio</span></td>
		<td valign="top"><span>renta mensual</span></td>
		<td valign="top"><span>tel negocio</span></td>
		<td valign="top"><span>solicitud compra</span></td>
		<td valign="top"><span>referencia com 1</span></td>
		<td valign="top"><span>referencia com 2</span></td>
		<td valign="top"><span>referencia com 3</span></td>
		<td valign="top"><span>referencia com 4</span></td>
		<td valign="top"><span>tel referencia 1</span></td>
		<td valign="top"><span>tel referencia 2</span></td>
		<td valign="top"><span>tel referencia 3</span></td>
		<td valign="top"><span>tel referencia 4</span></td>
		<td valign="top"><span>parentesco ref 1</span></td>
		<td valign="top"><span>parentesco ref 2</span></td>
		<td valign="top"><span>parentesco ref 3</span></td>
		<td valign="top"><span>parentesco ref 4</span></td>
		<td valign="top"><span>ing fam negocio</span></td>
		<td valign="top"><span>ing fam otro th</span></td>
		<td valign="top"><span>ing fam 1</span></td>
		<td valign="top"><span>ing fam 2</span></td>
		<td valign="top"><span>ing fam deuda 1</span></td>
		<td valign="top"><span>ing fam deuda 2</span></td>
		<td valign="top"><span>ing fam total</span></td>
		<td valign="top"><span>ing fam cuales 1</span></td>
		<td valign="top"><span>ing fam cuales 2</span></td>
		<td valign="top"><span>ing fam cuales 3</span></td>
		<td valign="top"><span>ing fam cuales 4</span></td>
		<td valign="top"><span>ing fam cuales 5</span></td>
		<td valign="top"><span>flujos neg ventas</span></td>
		<td valign="top"><span>flujos neg proveedor 1</span></td>
		<td valign="top"><span>flujos neg proveedor 2</span></td>
		<td valign="top"><span>flujos neg proveedor 3</span></td>
		<td valign="top"><span>flujos neg proveedor 4</span></td>
		<td valign="top"><span>flujos neg gasto 1</span></td>
		<td valign="top"><span>flujos neg gasto 2</span></td>
		<td valign="top"><span>flujos neg gasto 3</span></td>
		<td valign="top"><span>flujos neg cual 1</span></td>
		<td valign="top"><span>flujos neg cual 2</span></td>
		<td valign="top"><span>flujos neg cual 3</span></td>
		<td valign="top"><span>flujos neg cual 4</span></td>
		<td valign="top"><span>flujos neg cual 5</span></td>
		<td valign="top"><span>flujos neg cual 6</span></td>
		<td valign="top"><span>flujos neg cual 7</span></td>
		<td valign="top"><span>ingreso negocio</span></td>
		<td valign="top"><span>inv neg fija conc 1</span></td>
		<td valign="top"><span>inv neg fija conc 2</span></td>
		<td valign="top"><span>inv neg fija conc 3</span></td>
		<td valign="top"><span>inv neg fija conc 4</span></td>
		<td valign="top"><span>inv neg fija valor 1</span></td>
		<td valign="top"><span>inv neg fija valor 2</span></td>
		<td valign="top"><span>inv neg fija valor 3</span></td>
		<td valign="top"><span>inv neg fija valor 4</span></td>
		<td valign="top"><span>inv neg total fija</span></td>
		<td valign="top"><span>inv neg var conc 1</span></td>
		<td valign="top"><span>inv neg var conc 2</span></td>
		<td valign="top"><span>inv neg var conc 3</span></td>
		<td valign="top"><span>inv neg var conc 4</span></td>
		<td valign="top"><span>inv neg var valor 1</span></td>
		<td valign="top"><span>inv neg var valor 2</span></td>
		<td valign="top"><span>inv neg var valor 3</span></td>
		<td valign="top"><span>inv neg var valor 4</span></td>
		<td valign="top"><span>inv neg total var</span></td>
		<td valign="top"><span>inv neg activos totales</span></td>
		<td valign="top"><span>fecha</span></td>
	</tr>
<?php
$nRecCount = 0;
foreach ($arRecKey as $sRecKey) {
	$sRecKey = trim($sRecKey);
	$sRecKey = (get_magic_quotes_gpc()) ? stripslashes($sRecKey) : $sRecKey;
	$nRecCount = $nRecCount + 1;

	// Set row color
	$sItemRowClass = " class=\"ewTableRow\"";

	// Display alternate color for rows
	if ($nRecCount % 2 <> 0) {
		$sItemRowClass = " class=\"ewTableAltRow\"";
	}
	if (LoadData($sRecKey,$conn)) {
?>
	<tr<?php echo $sItemRowClass;?>>
		<td><span>
<?php echo $x_adquisicionMaquinaria_id; ?>
</span></td>
		<td><span>
<?php echo $x_nombre; ?>
</span></td>
		<td><span>
<?php echo $x_rfc; ?>
</span></td>
		<td><span>
<?php echo $x_curp; ?>
</span></td>
		<td><span>
<?php echo FormatDateTime($x_fecha_nacimiento,5); ?>
</span></td>
		<td><span>
<?php echo $x_sexo; ?>
</span></td>
		<td><span>
<?php echo $x_integrantes_familia; ?>
</span></td>
		<td><span>
<?php echo $x_dependientes; ?>
</span></td>
		<td><span>
<?php echo $x_correo_electronico; ?>
</span></td>
		<td><span>
<?php echo $x_esposa; ?>
</span></td>
		<td><span>
<?php echo $x_calle_domicilio; ?>
</span></td>
		<td><span>
<?php echo $x_colonia_domicilio; ?>
</span></td>
		<td><span>
<?php echo $x_entidad_domicilio; ?>
</span></td>
		<td><span>
<?php echo $x_codigo_postal_domicilio; ?>
</span></td>
		<td><span>
<?php echo $x_ubicacion_domicilio; ?>
</span></td>
		<td><span>
<?php echo $x_tipo_vivienda; ?>
</span></td>
		<td><span>
<?php echo $x_telefono_domicilio; ?>
</span></td>
		<td><span>
<?php echo $x_celular; ?>
</span></td>
		<td><span>
<?php echo $x_otro_tel_domicilio_1; ?>
</span></td>
		<td><span>
<?php echo $x_otro_telefono_domicilio_2; ?>
</span></td>
		<td><span>
<?php echo $x_antiguedad; ?>
</span></td>
		<td><span>
<?php echo $x_tel_arrendatario_domicilio; ?>
</span></td>
		<td><span>
<?php echo $x_renta_mensula_domicilio; ?>
</span></td>
		<td><span>
<?php echo $x_giro_negocio; ?>
</span></td>
		<td><span>
<?php echo $x_calle_negocio; ?>
</span></td>
		<td><span>
<?php echo $x_colonia_negocio; ?>
</span></td>
		<td><span>
<?php echo $x_entidad_negocio; ?>
</span></td>
		<td><span>
<?php echo $x_ubicacion_negocio; ?>
</span></td>
		<td><span>
<?php echo $x_codigo_postal_negocio; ?>
</span></td>
		<td><span>
<?php echo $x_tipo_local_negocio; ?>
</span></td>
		<td><span>
<?php echo $x_antiguedad_negocio; ?>
</span></td>
		<td><span>
<?php echo $x_tel_arrendatario_negocio; ?>
</span></td>
		<td><span>
<?php echo $x_renta_mensual; ?>
</span></td>
		<td><span>
<?php echo $x_tel_negocio; ?>
</span></td>
		<td><span>
<?php echo str_replace(chr(10), "<br>", @$x_solicitud_compra); ?>
</span></td>
		<td><span>
<?php echo $x_referencia_com_1; ?>
</span></td>
		<td><span>
<?php echo $x_referencia_com_2; ?>
</span></td>
		<td><span>
<?php echo $x_referencia_com_3; ?>
</span></td>
		<td><span>
<?php echo $x_referencia_com_4; ?>
</span></td>
		<td><span>
<?php echo $x_tel_referencia_1; ?>
</span></td>
		<td><span>
<?php echo $x_tel_referencia_2; ?>
</span></td>
		<td><span>
<?php echo $x_tel_referencia_3; ?>
</span></td>
		<td><span>
<?php echo $x_tel_referencia_4; ?>
</span></td>
		<td><span>
<?php echo $x_parentesco_ref_1; ?>
</span></td>
		<td><span>
<?php echo $x_parentesco_ref_2; ?>
</span></td>
		<td><span>
<?php echo $x_parentesco_ref_3; ?>
</span></td>
		<td><span>
<?php echo $x_parentesco_ref_4; ?>
</span></td>
		<td><span>
<?php echo $x_ing_fam_negocio; ?>
</span></td>
		<td><span>
<?php echo $x_ing_fam_otro_th; ?>
</span></td>
		<td><span>
<?php echo $x_ing_fam_1; ?>
</span></td>
		<td><span>
<?php echo $x_ing_fam_2; ?>
</span></td>
		<td><span>
<?php echo $x_ing_fam_deuda_1; ?>
</span></td>
		<td><span>
<?php echo $x_ing_fam_deuda_2; ?>
</span></td>
		<td><span>
<?php echo $x_ing_fam_total; ?>
</span></td>
		<td><span>
<?php echo $x_ing_fam_cuales_1; ?>
</span></td>
		<td><span>
<?php echo $x_ing_fam_cuales_2; ?>
</span></td>
		<td><span>
<?php echo $x_ing_fam_cuales_3; ?>
</span></td>
		<td><span>
<?php echo $x_ing_fam_cuales_4; ?>
</span></td>
		<td><span>
<?php echo $x_ing_fam_cuales_5; ?>
</span></td>
		<td><span>
<?php echo $x_flujos_neg_ventas; ?>
</span></td>
		<td><span>
<?php echo $x_flujos_neg_proveedor_1; ?>
</span></td>
		<td><span>
<?php echo $x_flujos_neg_proveedor_2; ?>
</span></td>
		<td><span>
<?php echo $x_flujos_neg_proveedor_3; ?>
</span></td>
		<td><span>
<?php echo $x_flujos_neg_proveedor_4; ?>
</span></td>
		<td><span>
<?php echo $x_flujos_neg_gasto_1; ?>
</span></td>
		<td><span>
<?php echo $x_flujos_neg_gasto_2; ?>
</span></td>
		<td><span>
<?php echo $x_flujos_neg_gasto_3; ?>
</span></td>
		<td><span>
<?php echo $x_flujos_neg_cual_1; ?>
</span></td>
		<td><span>
<?php echo $x_flujos_neg_cual_2; ?>
</span></td>
		<td><span>
<?php echo $x_flujos_neg_cual_3; ?>
</span></td>
		<td><span>
<?php echo $x_flujos_neg_cual_4; ?>
</span></td>
		<td><span>
<?php echo $x_flujos_neg_cual_5; ?>
</span></td>
		<td><span>
<?php echo $x_flujos_neg_cual_6; ?>
</span></td>
		<td><span>
<?php echo $x_flujos_neg_cual_7; ?>
</span></td>
		<td><span>
<?php echo $x_ingreso_negocio; ?>
</span></td>
		<td><span>
<?php echo $x_inv_neg_fija_conc_1; ?>
</span></td>
		<td><span>
<?php echo $x_inv_neg_fija_conc_2; ?>
</span></td>
		<td><span>
<?php echo $x_inv_neg_fija_conc_3; ?>
</span></td>
		<td><span>
<?php echo $x_inv_neg_fija_conc_4; ?>
</span></td>
		<td><span>
<?php echo $x_inv_neg_fija_valor_1; ?>
</span></td>
		<td><span>
<?php echo $x_inv_neg_fija_valor_2; ?>
</span></td>
		<td><span>
<?php echo $x_inv_neg_fija_valor_3; ?>
</span></td>
		<td><span>
<?php echo $x_inv_neg_fija_valor_4; ?>
</span></td>
		<td><span>
<?php echo $x_inv_neg_total_fija; ?>
</span></td>
		<td><span>
<?php echo $x_inv_neg_var_conc_1; ?>
</span></td>
		<td><span>
<?php echo $x_inv_neg_var_conc_2; ?>
</span></td>
		<td><span>
<?php echo $x_inv_neg_var_conc_3; ?>
</span></td>
		<td><span>
<?php echo $x_inv_neg_var_conc_4; ?>
</span></td>
		<td><span>
<?php echo $x_inv_neg_var_valor_1; ?>
</span></td>
		<td><span>
<?php echo $x_inv_neg_var_valor_2; ?>
</span></td>
		<td><span>
<?php echo $x_inv_neg_var_valor_3; ?>
</span></td>
		<td><span>
<?php echo $x_inv_neg_var_valor_4; ?>
</span></td>
		<td><span>
<?php echo $x_inv_neg_total_var; ?>
</span></td>
		<td><span>
<?php echo $x_inv_neg_activos_totales; ?>
</span></td>
		<td><span>
<?php echo FormatDateTime($x_fecha,5); ?>
</span></td>
	</tr>
<?php
	}
}
?>
</table>
<p>
<input type="submit" name="Action" value="CONFIRM DELETE">
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
// Function LoadRecordCount
// - Load Record Count based on input sql criteria sqlKey

function LoadRecordCount($sqlKey,$conn)
{
	global $HTTP_SESSION_VARS;
	$sSql = "SELECT * FROM `adquisicionmaquinaria`";
	$sSql .= " WHERE " . $sqlKey;
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
	return phpmkr_num_rows($rs);
	phpmkr_free_result($rs);
}
?>
<?php

//-------------------------------------------------------------------------------
// Function DeleteData
// - Delete Records based on input sql criteria sqlKey

function DeleteData($sqlKey,$conn)
{
	global $HTTP_SESSION_VARS;
	$sSql = "Delete FROM `adquisicionmaquinaria`";
	$sSql .= " WHERE " . $sqlKey;
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
	phpmkr_query($sSql,$conn);
	return true;
}
?>
