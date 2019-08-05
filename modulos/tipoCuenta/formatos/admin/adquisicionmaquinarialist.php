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
$nStartRec = 0;
$nStopRec = 0;
$nTotalRecs = 0;
$nRecCount = 0;
$nRecActual = 0;
$sKeyMaster = "";
$sDbWhereMaster = "";
$sSrchAdvanced = "";
$sSrchBasic = "";
$sSrchWhere = "";
$sDbWhere = "";
$sDefaultOrderBy = "";
$sDefaultFilter = "";
$sWhere = "";
$sGroupBy = "";
$sHaving = "";
$sOrderBy = "";
$sSqlMaster = "";
$nDisplayRecs = 20;
$nRecRange = 10;

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS,DB);

// Handle Reset Command
ResetCmd();

// Get Search Criteria for Basic Search
SetUpBasicSearch();

// Build Search Criteria
if ($sSrchAdvanced != "") {
	$sSrchWhere = $sSrchAdvanced; // Advanced Search
}
elseif ($sSrchBasic != "") {
	$sSrchWhere = $sSrchBasic; // Basic Search
}

// Save Search Criteria
if ($sSrchWhere != "") {
	$HTTP_SESSION_VARS["adquisicionmaquinaria_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$HTTP_SESSION_VARS["adquisicionmaquinaria_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$HTTP_SESSION_VARS["adquisicionmaquinaria_searchwhere"];
}

// Build WHERE condition
$sDbWhere = "";
if ($sDbWhereMaster != "") {
	$sDbWhere .= "(" . $sDbWhereMaster . ") AND ";
}
if ($sSrchWhere != "") {
	$sDbWhere .= "(" . $sSrchWhere . ") AND ";
}
if (strlen($sDbWhere) > 5) {
	$sDbWhere = substr($sDbWhere, 0, strlen($sDbWhere)-5); // Trim rightmost AND
}

// Build SQL
$sSql = "SELECT * FROM `adquisicionmaquinaria`";

// Load Default Filter
$sDefaultFilter = "";
$sGroupBy = "";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = "";
$sWhere = "";
if ($sDefaultFilter != "") {
	$sWhere .= "(" . $sDefaultFilter . ") AND ";
}
if ($sDbWhere != "") {
	$sWhere .= "(" . $sDbWhere . ") AND ";
}
if (substr($sWhere, -5) == " AND ") {
	$sWhere = substr($sWhere, 0, strlen($sWhere)-5);
}
if ($sWhere != "") {
	$sSql .= " WHERE " . $sWhere;
}
if ($sGroupBy != "") {
	$sSql .= " GROUP BY " . $sGroupBy;
}
if ($sHaving != "") {
	$sSql .= " HAVING " . $sHaving;
}

// Set Up Sorting Order
$sOrderBy = "";
SetUpSortOrder();
if ($sOrderBy != "") {
	$sSql .= " ORDER BY " . $sOrderBy;
}
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<?php

// Set up Record Set
$rs = phpmkr_query($sSql,$conn);
$nTotalRecs = phpmkr_num_rows($rs);
if ($nDisplayRecs <= 0) { // Display All Records
	$nDisplayRecs = $nTotalRecs;
}
$nStartRec = 1;
SetUpStartRec(); // Set Up Start Record Position
?>
<p><span class="phpmaker">TABLE: adquisicionmaquinaria
</span></p>
<form action="adquisicionmaquinarialist.php">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><span class="phpmaker">
			<input type="text" name="psearch" size="20">
			<input type="Submit" name="Submit" value="Search &nbsp;(*)">&nbsp;&nbsp;
			<a href="adquisicionmaquinarialist.php?cmd=reset">Show all</a>&nbsp;&nbsp;
		</span></td>
	</tr>
	<tr><td><span class="phpmaker"><input type="radio" name="psearchtype" value="" checked>Exact phrase&nbsp;&nbsp;<input type="radio" name="psearchtype" value="AND">All words&nbsp;&nbsp;<input type="radio" name="psearchtype" value="OR">Any word</span></td></tr>
</table>
</form>
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><span class="phpmaker"><a href="adquisicionmaquinariaadd.php">Add</a></span></td>
	</tr>
</table>
<p>
<?php
if (@$HTTP_SESSION_VARS["ewmsg"] <> "") {
?>
<p><span class="phpmaker" style="color: Red;"><?php echo $HTTP_SESSION_VARS["ewmsg"]; ?></span></p>
<?php
	$HTTP_SESSION_VARS["ewmsg"] = ""; // Clear message
}
?>
<form method="post">
<table class="ewTable">
<?php if ($nTotalRecs > 0) { ?>
	<!-- Table header -->
	<tr class="ewTableHeader">
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("adquisicionMaquinaria_id"); ?>" style="color: #FFFFFF;">adquisicion Maquinaria id<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_adquisicionMaquinaria_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_adquisicionMaquinaria_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("nombre"); ?>" style="color: #FFFFFF;">nombre&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_nombre_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_nombre_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("rfc"); ?>" style="color: #FFFFFF;">rfc&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_rfc_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_rfc_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("curp"); ?>" style="color: #FFFFFF;">curp&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_curp_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_curp_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("fecha_nacimiento"); ?>" style="color: #FFFFFF;">fecha nacimiento<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_fecha_nacimiento_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_fecha_nacimiento_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("sexo"); ?>" style="color: #FFFFFF;">sexo&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_sexo_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_sexo_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("integrantes_familia"); ?>" style="color: #FFFFFF;">integrantes familia<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_integrantes_familia_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_integrantes_familia_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("dependientes"); ?>" style="color: #FFFFFF;">dependientes<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_dependientes_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_dependientes_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("correo_electronico"); ?>" style="color: #FFFFFF;">correo electronico&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_correo_electronico_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_correo_electronico_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("esposa"); ?>" style="color: #FFFFFF;">esposa&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_esposa_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_esposa_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("calle_domicilio"); ?>" style="color: #FFFFFF;">calle domicilio&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_calle_domicilio_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_calle_domicilio_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("colonia_domicilio"); ?>" style="color: #FFFFFF;">colonia domicilio&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_colonia_domicilio_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_colonia_domicilio_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("entidad_domicilio"); ?>" style="color: #FFFFFF;">entidad domicilio&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_entidad_domicilio_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_entidad_domicilio_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("codigo_postal_domicilio"); ?>" style="color: #FFFFFF;">codigo postal domicilio<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_codigo_postal_domicilio_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_codigo_postal_domicilio_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("ubicacion_domicilio"); ?>" style="color: #FFFFFF;">ubicacion domicilio&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ubicacion_domicilio_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ubicacion_domicilio_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("tipo_vivienda"); ?>" style="color: #FFFFFF;">tipo vivienda&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tipo_vivienda_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tipo_vivienda_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("telefono_domicilio"); ?>" style="color: #FFFFFF;">telefono domicilio&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_telefono_domicilio_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_telefono_domicilio_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("celular"); ?>" style="color: #FFFFFF;">celular&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_celular_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_celular_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("otro_tel_domicilio_1"); ?>" style="color: #FFFFFF;">otro tel domicilio 1&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_otro_tel_domicilio_1_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_otro_tel_domicilio_1_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("otro_telefono_domicilio_2"); ?>" style="color: #FFFFFF;">otro telefono domicilio 2&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_otro_telefono_domicilio_2_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_otro_telefono_domicilio_2_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("antiguedad"); ?>" style="color: #FFFFFF;">antiguedad&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_antiguedad_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_antiguedad_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("tel_arrendatario_domicilio"); ?>" style="color: #FFFFFF;">tel arrendatario domicilio&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_arrendatario_domicilio_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_arrendatario_domicilio_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("renta_mensula_domicilio"); ?>" style="color: #FFFFFF;">renta mensula domicilio<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_renta_mensula_domicilio_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_renta_mensula_domicilio_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("giro_negocio"); ?>" style="color: #FFFFFF;">giro negocio&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_giro_negocio_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_giro_negocio_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("calle_negocio"); ?>" style="color: #FFFFFF;">calle negocio&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_calle_negocio_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_calle_negocio_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("colonia_negocio"); ?>" style="color: #FFFFFF;">colonia negocio&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_colonia_negocio_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_colonia_negocio_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("entidad_negocio"); ?>" style="color: #FFFFFF;">entidad negocio&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_entidad_negocio_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_entidad_negocio_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("ubicacion_negocio"); ?>" style="color: #FFFFFF;">ubicacion negocio&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ubicacion_negocio_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ubicacion_negocio_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("codigo_postal_negocio"); ?>" style="color: #FFFFFF;">codigo postal negocio<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_codigo_postal_negocio_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_codigo_postal_negocio_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("tipo_local_negocio"); ?>" style="color: #FFFFFF;">tipo local negocio&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tipo_local_negocio_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tipo_local_negocio_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("antiguedad_negocio"); ?>" style="color: #FFFFFF;">antiguedad negocio&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_antiguedad_negocio_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_antiguedad_negocio_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("tel_arrendatario_negocio"); ?>" style="color: #FFFFFF;">tel arrendatario negocio&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_arrendatario_negocio_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_arrendatario_negocio_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("renta_mensual"); ?>" style="color: #FFFFFF;">renta mensual<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_renta_mensual_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_renta_mensual_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("tel_negocio"); ?>" style="color: #FFFFFF;">tel negocio&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_negocio_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_negocio_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("solicitud_compra"); ?>" style="color: #FFFFFF;">solicitud compra&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_solicitud_compra_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_solicitud_compra_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("referencia_com_1"); ?>" style="color: #FFFFFF;">referencia com 1&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_1_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_1_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("referencia_com_2"); ?>" style="color: #FFFFFF;">referencia com 2&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_2_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_2_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("referencia_com_3"); ?>" style="color: #FFFFFF;">referencia com 3&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_3_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_3_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("referencia_com_4"); ?>" style="color: #FFFFFF;">referencia com 4&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_4_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_4_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("tel_referencia_1"); ?>" style="color: #FFFFFF;">tel referencia 1&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_1_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_1_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("tel_referencia_2"); ?>" style="color: #FFFFFF;">tel referencia 2&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_2_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_2_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("tel_referencia_3"); ?>" style="color: #FFFFFF;">tel referencia 3&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_3_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_3_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("tel_referencia_4"); ?>" style="color: #FFFFFF;">tel referencia 4&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_4_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_4_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("parentesco_ref_1"); ?>" style="color: #FFFFFF;">parentesco ref 1&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_1_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_1_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("parentesco_ref_2"); ?>" style="color: #FFFFFF;">parentesco ref 2&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_2_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_2_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("parentesco_ref_3"); ?>" style="color: #FFFFFF;">parentesco ref 3&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_3_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_3_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("parentesco_ref_4"); ?>" style="color: #FFFFFF;">parentesco ref 4&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_4_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_4_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("ing_fam_negocio"); ?>" style="color: #FFFFFF;">ing fam negocio<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_negocio_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_negocio_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("ing_fam_otro_th"); ?>" style="color: #FFFFFF;">ing fam otro th<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_otro_th_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_otro_th_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("ing_fam_1"); ?>" style="color: #FFFFFF;">ing fam 1<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_1_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_1_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("ing_fam_2"); ?>" style="color: #FFFFFF;">ing fam 2<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_2_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_2_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("ing_fam_deuda_1"); ?>" style="color: #FFFFFF;">ing fam deuda 1<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_deuda_1_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_deuda_1_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("ing_fam_deuda_2"); ?>" style="color: #FFFFFF;">ing fam deuda 2<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_deuda_2_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_deuda_2_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("ing_fam_total"); ?>" style="color: #FFFFFF;">ing fam total<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_total_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_total_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("ing_fam_cuales_1"); ?>" style="color: #FFFFFF;">ing fam cuales 1&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_1_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_1_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("ing_fam_cuales_2"); ?>" style="color: #FFFFFF;">ing fam cuales 2&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_2_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_2_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("ing_fam_cuales_3"); ?>" style="color: #FFFFFF;">ing fam cuales 3&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_3_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_3_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("ing_fam_cuales_4"); ?>" style="color: #FFFFFF;">ing fam cuales 4&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_4_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_4_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("ing_fam_cuales_5"); ?>" style="color: #FFFFFF;">ing fam cuales 5&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_5_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_5_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("flujos_neg_ventas"); ?>" style="color: #FFFFFF;">flujos neg ventas<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_ventas_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_ventas_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("flujos_neg_proveedor_1"); ?>" style="color: #FFFFFF;">flujos neg proveedor 1<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_1_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_1_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("flujos_neg_proveedor_2"); ?>" style="color: #FFFFFF;">flujos neg proveedor 2<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_2_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_2_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("flujos_neg_proveedor_3"); ?>" style="color: #FFFFFF;">flujos neg proveedor 3<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_3_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_3_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("flujos_neg_proveedor_4"); ?>" style="color: #FFFFFF;">flujos neg proveedor 4<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_4_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_4_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("flujos_neg_gasto_1"); ?>" style="color: #FFFFFF;">flujos neg gasto 1<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_gasto_1_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_gasto_1_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("flujos_neg_gasto_2"); ?>" style="color: #FFFFFF;">flujos neg gasto 2<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_gasto_2_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_gasto_2_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("flujos_neg_gasto_3"); ?>" style="color: #FFFFFF;">flujos neg gasto 3<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_gasto_3_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_gasto_3_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("flujos_neg_cual_1"); ?>" style="color: #FFFFFF;">flujos neg cual 1&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_1_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_1_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("flujos_neg_cual_2"); ?>" style="color: #FFFFFF;">flujos neg cual 2&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_2_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_2_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("flujos_neg_cual_3"); ?>" style="color: #FFFFFF;">flujos neg cual 3&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_3_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_3_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("flujos_neg_cual_4"); ?>" style="color: #FFFFFF;">flujos neg cual 4&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_4_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_4_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("flujos_neg_cual_5"); ?>" style="color: #FFFFFF;">flujos neg cual 5&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_5_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_5_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("flujos_neg_cual_6"); ?>" style="color: #FFFFFF;">flujos neg cual 6&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_6_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_6_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("flujos_neg_cual_7"); ?>" style="color: #FFFFFF;">flujos neg cual 7&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_7_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_7_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("ingreso_negocio"); ?>" style="color: #FFFFFF;">ingreso negocio<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ingreso_negocio_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ingreso_negocio_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("inv_neg_fija_conc_1"); ?>" style="color: #FFFFFF;">inv neg fija conc 1&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_1_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_1_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("inv_neg_fija_conc_2"); ?>" style="color: #FFFFFF;">inv neg fija conc 2&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_2_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_2_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("inv_neg_fija_conc_3"); ?>" style="color: #FFFFFF;">inv neg fija conc 3&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_3_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_3_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("inv_neg_fija_conc_4"); ?>" style="color: #FFFFFF;">inv neg fija conc 4&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_4_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_4_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("inv_neg_fija_valor_1"); ?>" style="color: #FFFFFF;">inv neg fija valor 1<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_1_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_1_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("inv_neg_fija_valor_2"); ?>" style="color: #FFFFFF;">inv neg fija valor 2<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_2_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_2_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("inv_neg_fija_valor_3"); ?>" style="color: #FFFFFF;">inv neg fija valor 3<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_3_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_3_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("inv_neg_fija_valor_4"); ?>" style="color: #FFFFFF;">inv neg fija valor 4<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_4_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_4_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("inv_neg_total_fija"); ?>" style="color: #FFFFFF;">inv neg total fija<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_total_fija_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_total_fija_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("inv_neg_var_conc_1"); ?>" style="color: #FFFFFF;">inv neg var conc 1&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_1_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_1_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("inv_neg_var_conc_2"); ?>" style="color: #FFFFFF;">inv neg var conc 2&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_2_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_2_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("inv_neg_var_conc_3"); ?>" style="color: #FFFFFF;">inv neg var conc 3&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_3_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_3_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("inv_neg_var_conc_4"); ?>" style="color: #FFFFFF;">inv neg var conc 4&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_4_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_4_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("inv_neg_var_valor_1"); ?>" style="color: #FFFFFF;">inv neg var valor 1<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_1_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_1_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("inv_neg_var_valor_2"); ?>" style="color: #FFFFFF;">inv neg var valor 2<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_2_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_2_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("inv_neg_var_valor_3"); ?>" style="color: #FFFFFF;">inv neg var valor 3<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_3_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_3_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("inv_neg_var_valor_4"); ?>" style="color: #FFFFFF;">inv neg var valor 4<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_4_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_4_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("inv_neg_total_var"); ?>" style="color: #FFFFFF;">inv neg total var<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_total_var_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_total_var_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("inv_neg_activos_totales"); ?>" style="color: #FFFFFF;">inv neg activos totales<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_activos_totales_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_activos_totales_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="adquisicionmaquinarialist.php?order=<?php echo urlencode("fecha"); ?>" style="color: #FFFFFF;">fecha<?php if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_fecha_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_fecha_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
	</tr>
<?php } ?>
<?php

// Avoid starting record > total records
if ($nStartRec > $nTotalRecs) {
	$nStartRec = $nTotalRecs;
}

// Set the last record to display
$nStopRec = $nStartRec + $nDisplayRecs - 1;

// Move to first record directly for performance reason
$nRecCount = $nStartRec - 1;
if (phpmkr_num_rows($rs) > 0) {
	phpmkr_data_seek($rs, $nStartRec -1);
}
$nRecActual = 0;
while (($row = @phpmkr_fetch_array($rs)) && ($nRecCount < $nStopRec)) {
	$nRecCount = $nRecCount + 1;
	if ($nRecCount >= $nStartRec) {
		$nRecActual = $nRecActual + 1;

	// Set row color
	$sItemRowClass = " class=\"ewTableRow\"";

	// Display alternate color for rows
	if ($nRecCount % 2 <> 0) {
		$sItemRowClass = " class=\"ewTableAltRow\"";
	}

		// Load Key for record
		$sKey = $row["adquisicionMaquinaria_id"];
		$x_adquisicionMaquinaria_id = $row["adquisicionMaquinaria_id"];
		$x_nombre = $row["nombre"];
		$x_rfc = $row["rfc"];
		$x_curp = $row["curp"];
		$x_fecha_nacimiento = $row["fecha_nacimiento"];
		$x_sexo = $row["sexo"];
		$x_integrantes_familia = $row["integrantes_familia"];
		$x_dependientes = $row["dependientes"];
		$x_correo_electronico = $row["correo_electronico"];
		$x_esposa = $row["esposa"];
		$x_calle_domicilio = $row["calle_domicilio"];
		$x_colonia_domicilio = $row["colonia_domicilio"];
		$x_entidad_domicilio = $row["entidad_domicilio"];
		$x_codigo_postal_domicilio = $row["codigo_postal_domicilio"];
		$x_ubicacion_domicilio = $row["ubicacion_domicilio"];
		$x_tipo_vivienda = $row["tipo_vivienda"];
		$x_telefono_domicilio = $row["telefono_domicilio"];
		$x_celular = $row["celular"];
		$x_otro_tel_domicilio_1 = $row["otro_tel_domicilio_1"];
		$x_otro_telefono_domicilio_2 = $row["otro_telefono_domicilio_2"];
		$x_antiguedad = $row["antiguedad"];
		$x_tel_arrendatario_domicilio = $row["tel_arrendatario_domicilio"];
		$x_renta_mensula_domicilio = $row["renta_mensula_domicilio"];
		$x_giro_negocio = $row["giro_negocio"];
		$x_calle_negocio = $row["calle_negocio"];
		$x_colonia_negocio = $row["colonia_negocio"];
		$x_entidad_negocio = $row["entidad_negocio"];
		$x_ubicacion_negocio = $row["ubicacion_negocio"];
		$x_codigo_postal_negocio = $row["codigo_postal_negocio"];
		$x_tipo_local_negocio = $row["tipo_local_negocio"];
		$x_antiguedad_negocio = $row["antiguedad_negocio"];
		$x_tel_arrendatario_negocio = $row["tel_arrendatario_negocio"];
		$x_renta_mensual = $row["renta_mensual"];
		$x_tel_negocio = $row["tel_negocio"];
		$x_solicitud_compra = $row["solicitud_compra"];
		$x_referencia_com_1 = $row["referencia_com_1"];
		$x_referencia_com_2 = $row["referencia_com_2"];
		$x_referencia_com_3 = $row["referencia_com_3"];
		$x_referencia_com_4 = $row["referencia_com_4"];
		$x_tel_referencia_1 = $row["tel_referencia_1"];
		$x_tel_referencia_2 = $row["tel_referencia_2"];
		$x_tel_referencia_3 = $row["tel_referencia_3"];
		$x_tel_referencia_4 = $row["tel_referencia_4"];
		$x_parentesco_ref_1 = $row["parentesco_ref_1"];
		$x_parentesco_ref_2 = $row["parentesco_ref_2"];
		$x_parentesco_ref_3 = $row["parentesco_ref_3"];
		$x_parentesco_ref_4 = $row["parentesco_ref_4"];
		$x_ing_fam_negocio = $row["ing_fam_negocio"];
		$x_ing_fam_otro_th = $row["ing_fam_otro_th"];
		$x_ing_fam_1 = $row["ing_fam_1"];
		$x_ing_fam_2 = $row["ing_fam_2"];
		$x_ing_fam_deuda_1 = $row["ing_fam_deuda_1"];
		$x_ing_fam_deuda_2 = $row["ing_fam_deuda_2"];
		$x_ing_fam_total = $row["ing_fam_total"];
		$x_ing_fam_cuales_1 = $row["ing_fam_cuales_1"];
		$x_ing_fam_cuales_2 = $row["ing_fam_cuales_2"];
		$x_ing_fam_cuales_3 = $row["ing_fam_cuales_3"];
		$x_ing_fam_cuales_4 = $row["ing_fam_cuales_4"];
		$x_ing_fam_cuales_5 = $row["ing_fam_cuales_5"];
		$x_flujos_neg_ventas = $row["flujos_neg_ventas"];
		$x_flujos_neg_proveedor_1 = $row["flujos_neg_proveedor_1"];
		$x_flujos_neg_proveedor_2 = $row["flujos_neg_proveedor_2"];
		$x_flujos_neg_proveedor_3 = $row["flujos_neg_proveedor_3"];
		$x_flujos_neg_proveedor_4 = $row["flujos_neg_proveedor_4"];
		$x_flujos_neg_gasto_1 = $row["flujos_neg_gasto_1"];
		$x_flujos_neg_gasto_2 = $row["flujos_neg_gasto_2"];
		$x_flujos_neg_gasto_3 = $row["flujos_neg_gasto_3"];
		$x_flujos_neg_cual_1 = $row["flujos_neg_cual_1"];
		$x_flujos_neg_cual_2 = $row["flujos_neg_cual_2"];
		$x_flujos_neg_cual_3 = $row["flujos_neg_cual_3"];
		$x_flujos_neg_cual_4 = $row["flujos_neg_cual_4"];
		$x_flujos_neg_cual_5 = $row["flujos_neg_cual_5"];
		$x_flujos_neg_cual_6 = $row["flujos_neg_cual_6"];
		$x_flujos_neg_cual_7 = $row["flujos_neg_cual_7"];
		$x_ingreso_negocio = $row["ingreso_negocio"];
		$x_inv_neg_fija_conc_1 = $row["inv_neg_fija_conc_1"];
		$x_inv_neg_fija_conc_2 = $row["inv_neg_fija_conc_2"];
		$x_inv_neg_fija_conc_3 = $row["inv_neg_fija_conc_3"];
		$x_inv_neg_fija_conc_4 = $row["inv_neg_fija_conc_4"];
		$x_inv_neg_fija_valor_1 = $row["inv_neg_fija_valor_1"];
		$x_inv_neg_fija_valor_2 = $row["inv_neg_fija_valor_2"];
		$x_inv_neg_fija_valor_3 = $row["inv_neg_fija_valor_3"];
		$x_inv_neg_fija_valor_4 = $row["inv_neg_fija_valor_4"];
		$x_inv_neg_total_fija = $row["inv_neg_total_fija"];
		$x_inv_neg_var_conc_1 = $row["inv_neg_var_conc_1"];
		$x_inv_neg_var_conc_2 = $row["inv_neg_var_conc_2"];
		$x_inv_neg_var_conc_3 = $row["inv_neg_var_conc_3"];
		$x_inv_neg_var_conc_4 = $row["inv_neg_var_conc_4"];
		$x_inv_neg_var_valor_1 = $row["inv_neg_var_valor_1"];
		$x_inv_neg_var_valor_2 = $row["inv_neg_var_valor_2"];
		$x_inv_neg_var_valor_3 = $row["inv_neg_var_valor_3"];
		$x_inv_neg_var_valor_4 = $row["inv_neg_var_valor_4"];
		$x_inv_neg_total_var = $row["inv_neg_total_var"];
		$x_inv_neg_activos_totales = $row["inv_neg_activos_totales"];
		$x_fecha = $row["fecha"];
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
		<!-- adquisicionMaquinaria_id -->
		<td><span>
<?php echo $x_adquisicionMaquinaria_id; ?>
</span></td>
		<!-- nombre -->
		<td><span>
<?php echo $x_nombre; ?>
</span></td>
		<!-- rfc -->
		<td><span>
<?php echo $x_rfc; ?>
</span></td>
		<!-- curp -->
		<td><span>
<?php echo $x_curp; ?>
</span></td>
		<!-- fecha_nacimiento -->
		<td><span>
<?php echo FormatDateTime($x_fecha_nacimiento,5); ?>
</span></td>
		<!-- sexo -->
		<td><span>
<?php echo $x_sexo; ?>
</span></td>
		<!-- integrantes_familia -->
		<td><span>
<?php echo $x_integrantes_familia; ?>
</span></td>
		<!-- dependientes -->
		<td><span>
<?php echo $x_dependientes; ?>
</span></td>
		<!-- correo_electronico -->
		<td><span>
<?php echo $x_correo_electronico; ?>
</span></td>
		<!-- esposa -->
		<td><span>
<?php echo $x_esposa; ?>
</span></td>
		<!-- calle_domicilio -->
		<td><span>
<?php echo $x_calle_domicilio; ?>
</span></td>
		<!-- colonia_domicilio -->
		<td><span>
<?php echo $x_colonia_domicilio; ?>
</span></td>
		<!-- entidad_domicilio -->
		<td><span>
<?php echo $x_entidad_domicilio; ?>
</span></td>
		<!-- codigo_postal_domicilio -->
		<td><span>
<?php echo $x_codigo_postal_domicilio; ?>
</span></td>
		<!-- ubicacion_domicilio -->
		<td><span>
<?php echo $x_ubicacion_domicilio; ?>
</span></td>
		<!-- tipo_vivienda -->
		<td><span>
<?php echo $x_tipo_vivienda; ?>
</span></td>
		<!-- telefono_domicilio -->
		<td><span>
<?php echo $x_telefono_domicilio; ?>
</span></td>
		<!-- celular -->
		<td><span>
<?php echo $x_celular; ?>
</span></td>
		<!-- otro_tel_domicilio_1 -->
		<td><span>
<?php echo $x_otro_tel_domicilio_1; ?>
</span></td>
		<!-- otro_telefono_domicilio_2 -->
		<td><span>
<?php echo $x_otro_telefono_domicilio_2; ?>
</span></td>
		<!-- antiguedad -->
		<td><span>
<?php echo $x_antiguedad; ?>
</span></td>
		<!-- tel_arrendatario_domicilio -->
		<td><span>
<?php echo $x_tel_arrendatario_domicilio; ?>
</span></td>
		<!-- renta_mensula_domicilio -->
		<td><span>
<?php echo $x_renta_mensula_domicilio; ?>
</span></td>
		<!-- giro_negocio -->
		<td><span>
<?php echo $x_giro_negocio; ?>
</span></td>
		<!-- calle_negocio -->
		<td><span>
<?php echo $x_calle_negocio; ?>
</span></td>
		<!-- colonia_negocio -->
		<td><span>
<?php echo $x_colonia_negocio; ?>
</span></td>
		<!-- entidad_negocio -->
		<td><span>
<?php echo $x_entidad_negocio; ?>
</span></td>
		<!-- ubicacion_negocio -->
		<td><span>
<?php echo $x_ubicacion_negocio; ?>
</span></td>
		<!-- codigo_postal_negocio -->
		<td><span>
<?php echo $x_codigo_postal_negocio; ?>
</span></td>
		<!-- tipo_local_negocio -->
		<td><span>
<?php echo $x_tipo_local_negocio; ?>
</span></td>
		<!-- antiguedad_negocio -->
		<td><span>
<?php echo $x_antiguedad_negocio; ?>
</span></td>
		<!-- tel_arrendatario_negocio -->
		<td><span>
<?php echo $x_tel_arrendatario_negocio; ?>
</span></td>
		<!-- renta_mensual -->
		<td><span>
<?php echo $x_renta_mensual; ?>
</span></td>
		<!-- tel_negocio -->
		<td><span>
<?php echo $x_tel_negocio; ?>
</span></td>
		<!-- solicitud_compra -->
		<td><span>
<?php echo str_replace(chr(10), "<br>", @$x_solicitud_compra); ?>
</span></td>
		<!-- referencia_com_1 -->
		<td><span>
<?php echo $x_referencia_com_1; ?>
</span></td>
		<!-- referencia_com_2 -->
		<td><span>
<?php echo $x_referencia_com_2; ?>
</span></td>
		<!-- referencia_com_3 -->
		<td><span>
<?php echo $x_referencia_com_3; ?>
</span></td>
		<!-- referencia_com_4 -->
		<td><span>
<?php echo $x_referencia_com_4; ?>
</span></td>
		<!-- tel_referencia_1 -->
		<td><span>
<?php echo $x_tel_referencia_1; ?>
</span></td>
		<!-- tel_referencia_2 -->
		<td><span>
<?php echo $x_tel_referencia_2; ?>
</span></td>
		<!-- tel_referencia_3 -->
		<td><span>
<?php echo $x_tel_referencia_3; ?>
</span></td>
		<!-- tel_referencia_4 -->
		<td><span>
<?php echo $x_tel_referencia_4; ?>
</span></td>
		<!-- parentesco_ref_1 -->
		<td><span>
<?php echo $x_parentesco_ref_1; ?>
</span></td>
		<!-- parentesco_ref_2 -->
		<td><span>
<?php echo $x_parentesco_ref_2; ?>
</span></td>
		<!-- parentesco_ref_3 -->
		<td><span>
<?php echo $x_parentesco_ref_3; ?>
</span></td>
		<!-- parentesco_ref_4 -->
		<td><span>
<?php echo $x_parentesco_ref_4; ?>
</span></td>
		<!-- ing_fam_negocio -->
		<td><span>
<?php echo $x_ing_fam_negocio; ?>
</span></td>
		<!-- ing_fam_otro_th -->
		<td><span>
<?php echo $x_ing_fam_otro_th; ?>
</span></td>
		<!-- ing_fam_1 -->
		<td><span>
<?php echo $x_ing_fam_1; ?>
</span></td>
		<!-- ing_fam_2 -->
		<td><span>
<?php echo $x_ing_fam_2; ?>
</span></td>
		<!-- ing_fam_deuda_1 -->
		<td><span>
<?php echo $x_ing_fam_deuda_1; ?>
</span></td>
		<!-- ing_fam_deuda_2 -->
		<td><span>
<?php echo $x_ing_fam_deuda_2; ?>
</span></td>
		<!-- ing_fam_total -->
		<td><span>
<?php echo $x_ing_fam_total; ?>
</span></td>
		<!-- ing_fam_cuales_1 -->
		<td><span>
<?php echo $x_ing_fam_cuales_1; ?>
</span></td>
		<!-- ing_fam_cuales_2 -->
		<td><span>
<?php echo $x_ing_fam_cuales_2; ?>
</span></td>
		<!-- ing_fam_cuales_3 -->
		<td><span>
<?php echo $x_ing_fam_cuales_3; ?>
</span></td>
		<!-- ing_fam_cuales_4 -->
		<td><span>
<?php echo $x_ing_fam_cuales_4; ?>
</span></td>
		<!-- ing_fam_cuales_5 -->
		<td><span>
<?php echo $x_ing_fam_cuales_5; ?>
</span></td>
		<!-- flujos_neg_ventas -->
		<td><span>
<?php echo $x_flujos_neg_ventas; ?>
</span></td>
		<!-- flujos_neg_proveedor_1 -->
		<td><span>
<?php echo $x_flujos_neg_proveedor_1; ?>
</span></td>
		<!-- flujos_neg_proveedor_2 -->
		<td><span>
<?php echo $x_flujos_neg_proveedor_2; ?>
</span></td>
		<!-- flujos_neg_proveedor_3 -->
		<td><span>
<?php echo $x_flujos_neg_proveedor_3; ?>
</span></td>
		<!-- flujos_neg_proveedor_4 -->
		<td><span>
<?php echo $x_flujos_neg_proveedor_4; ?>
</span></td>
		<!-- flujos_neg_gasto_1 -->
		<td><span>
<?php echo $x_flujos_neg_gasto_1; ?>
</span></td>
		<!-- flujos_neg_gasto_2 -->
		<td><span>
<?php echo $x_flujos_neg_gasto_2; ?>
</span></td>
		<!-- flujos_neg_gasto_3 -->
		<td><span>
<?php echo $x_flujos_neg_gasto_3; ?>
</span></td>
		<!-- flujos_neg_cual_1 -->
		<td><span>
<?php echo $x_flujos_neg_cual_1; ?>
</span></td>
		<!-- flujos_neg_cual_2 -->
		<td><span>
<?php echo $x_flujos_neg_cual_2; ?>
</span></td>
		<!-- flujos_neg_cual_3 -->
		<td><span>
<?php echo $x_flujos_neg_cual_3; ?>
</span></td>
		<!-- flujos_neg_cual_4 -->
		<td><span>
<?php echo $x_flujos_neg_cual_4; ?>
</span></td>
		<!-- flujos_neg_cual_5 -->
		<td><span>
<?php echo $x_flujos_neg_cual_5; ?>
</span></td>
		<!-- flujos_neg_cual_6 -->
		<td><span>
<?php echo $x_flujos_neg_cual_6; ?>
</span></td>
		<!-- flujos_neg_cual_7 -->
		<td><span>
<?php echo $x_flujos_neg_cual_7; ?>
</span></td>
		<!-- ingreso_negocio -->
		<td><span>
<?php echo $x_ingreso_negocio; ?>
</span></td>
		<!-- inv_neg_fija_conc_1 -->
		<td><span>
<?php echo $x_inv_neg_fija_conc_1; ?>
</span></td>
		<!-- inv_neg_fija_conc_2 -->
		<td><span>
<?php echo $x_inv_neg_fija_conc_2; ?>
</span></td>
		<!-- inv_neg_fija_conc_3 -->
		<td><span>
<?php echo $x_inv_neg_fija_conc_3; ?>
</span></td>
		<!-- inv_neg_fija_conc_4 -->
		<td><span>
<?php echo $x_inv_neg_fija_conc_4; ?>
</span></td>
		<!-- inv_neg_fija_valor_1 -->
		<td><span>
<?php echo $x_inv_neg_fija_valor_1; ?>
</span></td>
		<!-- inv_neg_fija_valor_2 -->
		<td><span>
<?php echo $x_inv_neg_fija_valor_2; ?>
</span></td>
		<!-- inv_neg_fija_valor_3 -->
		<td><span>
<?php echo $x_inv_neg_fija_valor_3; ?>
</span></td>
		<!-- inv_neg_fija_valor_4 -->
		<td><span>
<?php echo $x_inv_neg_fija_valor_4; ?>
</span></td>
		<!-- inv_neg_total_fija -->
		<td><span>
<?php echo $x_inv_neg_total_fija; ?>
</span></td>
		<!-- inv_neg_var_conc_1 -->
		<td><span>
<?php echo $x_inv_neg_var_conc_1; ?>
</span></td>
		<!-- inv_neg_var_conc_2 -->
		<td><span>
<?php echo $x_inv_neg_var_conc_2; ?>
</span></td>
		<!-- inv_neg_var_conc_3 -->
		<td><span>
<?php echo $x_inv_neg_var_conc_3; ?>
</span></td>
		<!-- inv_neg_var_conc_4 -->
		<td><span>
<?php echo $x_inv_neg_var_conc_4; ?>
</span></td>
		<!-- inv_neg_var_valor_1 -->
		<td><span>
<?php echo $x_inv_neg_var_valor_1; ?>
</span></td>
		<!-- inv_neg_var_valor_2 -->
		<td><span>
<?php echo $x_inv_neg_var_valor_2; ?>
</span></td>
		<!-- inv_neg_var_valor_3 -->
		<td><span>
<?php echo $x_inv_neg_var_valor_3; ?>
</span></td>
		<!-- inv_neg_var_valor_4 -->
		<td><span>
<?php echo $x_inv_neg_var_valor_4; ?>
</span></td>
		<!-- inv_neg_total_var -->
		<td><span>
<?php echo $x_inv_neg_total_var; ?>
</span></td>
		<!-- inv_neg_activos_totales -->
		<td><span>
<?php echo $x_inv_neg_activos_totales; ?>
</span></td>
		<!-- fecha -->
		<td><span>
<?php echo FormatDateTime($x_fecha,5); ?>
</span></td>
<td><span class="phpmaker"><a href="<?php if (($sKey != NULL)) { echo "adquisicionmaquinariaview.php?key=" . urlencode($sKey); } else { echo "javascript:alert('Invalid Record! Key is null');";  } ?>">View</a></span></td>
<td><span class="phpmaker"><a href="<?php if (($sKey != NULL)) { echo "adquisicionmaquinariaedit.php?key=" . urlencode($sKey); } else { echo "javascript:alert('Invalid Record! Key is null');"; } ?>">Edit</a></span></td>
<td><span class="phpmaker"><a href="<?php if (($sKey != NULL)) { echo "adquisicionmaquinariaadd.php?key=" . urlencode($sKey); } else { echo "javascript:alert('Invalid Record! Key is null');"; } ?>">Copy</a></span></td>
<td><span class="phpmaker"><a href="<?php if (($sKey != NULL)) { echo "adquisicionmaquinariadelete.php?key=" . urlencode($sKey); } else { echo "javascript:alert('Invalid Record! Key is null');"; }  ?>">Delete</a></span></td>
	</tr>
<?php
	}
}
?>
</table>
</form>
<?php

// Close recordset and connection
phpmkr_free_result($rs);
phpmkr_db_close($conn);
?>
<form action="adquisicionmaquinarialist.php" name="ewpagerform" id="ewpagerform">
<table class="ewTablePager">
	<tr>
		<td nowrap>
<?php
if ($nTotalRecs > 0) {
	$rsEof = ($nTotalRecs < ($nStartRec + $nDisplayRecs));
	$PrevStart = $nStartRec - $nDisplayRecs;
	if ($PrevStart < 1) { $PrevStart = 1; }
	$NextStart = $nStartRec + $nDisplayRecs;
	if ($NextStart > $nTotalRecs) { $NextStart = $nStartRec ; }
	$LastStart = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
	?>
	<table border="0" cellspacing="0" cellpadding="0"><tr><td><span class="phpmaker">Page&nbsp;</span></td>
<!--first page button-->
	<?php if ($nStartRec == 1) { ?>
	<td><img src="images/firstdisab.gif" alt="First" width="16" height="16" border="0"></td>
	<?php } else { ?>
	<td><a href="adquisicionmaquinarialist.php?start=1"><img src="images/first.gif" alt="First" width="16" height="16" border="0"></a></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($PrevStart == $nStartRec) { ?>
	<td><img src="images/prevdisab.gif" alt="Previous" width="16" height="16" border="0"></td>
	<?php } else { ?>
	<td><a href="adquisicionmaquinarialist.php?start=<?php echo $PrevStart; ?>"><img src="images/prev.gif" alt="Previous" width="16" height="16" border="0"></a></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="pageno" value="<?php echo intval(($nStartRec-1)/$nDisplayRecs+1); ?>" size="4"></td>
<!--next page button-->
	<?php if ($NextStart == $nStartRec) { ?>
	<td><img src="images/nextdisab.gif" alt="Next" width="16" height="16" border="0"></td>
	<?php } else { ?>
	<td><a href="adquisicionmaquinarialist.php?start=<?php echo $NextStart; ?>"><img src="images/next.gif" alt="Next" width="16" height="16" border="0"></a></td>
	<?php  } ?>
<!--last page button-->
	<?php if ($LastStart == $nStartRec) { ?>
	<td><img src="images/lastdisab.gif" alt="Last" width="16" height="16" border="0"></td>
	<?php } else { ?>
	<td><a href="adquisicionmaquinarialist.php?start=<?php echo $LastStart; ?>"><img src="images/last.gif" alt="Last" width="16" height="16" border="0"></a></td>
	<?php } ?>
	<td><span class="phpmaker">&nbsp;of <?php echo intval(($nTotalRecs-1)/$nDisplayRecs+1);?></span></td>
	</tr></table>
	<?php if ($nStartRec > $nTotalRecs) { $nStartRec = $nTotalRecs; }
	$nStopRec = $nStartRec + $nDisplayRecs - 1;
	$nRecCount = $nTotalRecs - 1;
	if ($rsEof) { $nRecCount = $nTotalRecs; }
	if ($nStopRec > $nRecCount) { $nStopRec = $nRecCount; } ?>
	<span class="phpmaker">Records <?php echo $nStartRec; ?> to <?php echo $nStopRec; ?> of <?php echo $nTotalRecs; ?></span>
<?php } else { ?>
	<span class="phpmaker">No records found</span>
<?php } ?>
		</td>
	</tr>
</table>
</form>
<?php include ("footer.php") ?>
<?php

//-------------------------------------------------------------------------------
// Function BasicSearchSQL
// - Build WHERE clause for a keyword

function BasicSearchSQL($Keyword)
{
	$sKeyword = (!get_magic_quotes_gpc()) ? addslashes($Keyword) : $Keyword;
	$BasicSearchSQL = "";
	$BasicSearchSQL.= "`nombre` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`rfc` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`curp` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`sexo` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`correo_electronico` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`esposa` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`calle_domicilio` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`colonia_domicilio` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`entidad_domicilio` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`ubicacion_domicilio` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`tipo_vivienda` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`telefono_domicilio` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`celular` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`otro_tel_domicilio_1` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`otro_telefono_domicilio_2` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`antiguedad` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`tel_arrendatario_domicilio` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`giro_negocio` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`calle_negocio` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`colonia_negocio` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`entidad_negocio` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`ubicacion_negocio` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`tipo_local_negocio` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`antiguedad_negocio` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`tel_arrendatario_negocio` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`tel_negocio` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`solicitud_compra` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`referencia_com_1` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`referencia_com_2` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`referencia_com_3` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`referencia_com_4` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`tel_referencia_1` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`tel_referencia_2` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`tel_referencia_3` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`tel_referencia_4` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`parentesco_ref_1` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`parentesco_ref_2` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`parentesco_ref_3` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`parentesco_ref_4` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`ing_fam_cuales_1` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`ing_fam_cuales_2` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`ing_fam_cuales_3` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`ing_fam_cuales_4` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`ing_fam_cuales_5` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`flujos_neg_cual_1` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`flujos_neg_cual_2` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`flujos_neg_cual_3` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`flujos_neg_cual_4` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`flujos_neg_cual_5` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`flujos_neg_cual_6` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`flujos_neg_cual_7` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`inv_neg_fija_conc_1` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`inv_neg_fija_conc_2` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`inv_neg_fija_conc_3` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`inv_neg_fija_conc_4` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`inv_neg_var_conc_1` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`inv_neg_var_conc_2` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`inv_neg_var_conc_3` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`inv_neg_var_conc_4` LIKE '%" . $sKeyword . "%' OR ";
	if (substr($BasicSearchSQL, -4) == " OR ") { $BasicSearchSQL = substr($BasicSearchSQL, 0, strlen($BasicSearchSQL)-4); }
	return $BasicSearchSQL;
}

//-------------------------------------------------------------------------------
// Function SetUpBasicSearch
// - Set up Basic Search parameter based on form elements pSearch & pSearchType
// - Variables setup: sSrchBasic

function SetUpBasicSearch()
{
	global $HTTP_GET_VARS;
	global $sSrchBasic;
	$sSearch = (!get_magic_quotes_gpc()) ? addslashes(@$HTTP_GET_VARS["psearch"]) : @$HTTP_GET_VARS["psearch"];
	$sSearchType = @$HTTP_GET_VARS["psearchtype"];
	if ($sSearch <> "") {
		if ($sSearchType <> "") {
			while (strpos($sSearch, "  ") != false) {
				$sSearch = str_replace("  ", " ",$sSearch);
			}
			$arKeyword = split(" ", trim($sSearch));
			foreach ($arKeyword as $sKeyword)
			{
				$sSrchBasic .= "(" . BasicSearchSQL($sKeyword) . ") " . $sSearchType . " ";
			}
		}
		else
		{
			$sSrchBasic = BasicSearchSQL($sSearch);
		}
	}
	if (substr($sSrchBasic, -4) == " OR ") { $sSrchBasic = substr($sSrchBasic, 0, strlen($sSrchBasic)-4); }
	if (substr($sSrchBasic, -5) == " AND ") { $sSrchBasic = substr($sSrchBasic, 0, strlen($sSrchBasic)-5); }
}

//-------------------------------------------------------------------------------
// Function SetUpSortOrder
// - Set up Sort parameters based on Sort Links clicked
// - Variables setup: sOrderBy, Session("Table_OrderBy"), Session("Table_Field_Sort")

function SetUpSortOrder()
{
	global $HTTP_SESSION_VARS;
	global $HTTP_GET_VARS;
	global $sOrderBy;
	global $sDefaultOrderBy;

	// Check for an Order parameter
	if (strlen(@$HTTP_GET_VARS["order"]) > 0) {
		$sOrder = @$HTTP_GET_VARS["order"];

		// Field adquisicionMaquinaria_id
		if ($sOrder == "adquisicionMaquinaria_id") {
			$sSortField = "`adquisicionMaquinaria_id`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_adquisicionMaquinaria_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_adquisicionMaquinaria_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_adquisicionMaquinaria_id_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_adquisicionMaquinaria_id_Sort"] = ""; }
		}

		// Field nombre
		if ($sOrder == "nombre") {
			$sSortField = "`nombre`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_nombre_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_nombre_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_nombre_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_nombre_Sort"] = ""; }
		}

		// Field rfc
		if ($sOrder == "rfc") {
			$sSortField = "`rfc`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_rfc_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_rfc_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_rfc_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_rfc_Sort"] = ""; }
		}

		// Field curp
		if ($sOrder == "curp") {
			$sSortField = "`curp`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_curp_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_curp_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_curp_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_curp_Sort"] = ""; }
		}

		// Field fecha_nacimiento
		if ($sOrder == "fecha_nacimiento") {
			$sSortField = "`fecha_nacimiento`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_fecha_nacimiento_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_fecha_nacimiento_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_fecha_nacimiento_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_fecha_nacimiento_Sort"] = ""; }
		}

		// Field sexo
		if ($sOrder == "sexo") {
			$sSortField = "`sexo`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_sexo_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_sexo_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_sexo_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_sexo_Sort"] = ""; }
		}

		// Field integrantes_familia
		if ($sOrder == "integrantes_familia") {
			$sSortField = "`integrantes_familia`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_integrantes_familia_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_integrantes_familia_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_integrantes_familia_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_integrantes_familia_Sort"] = ""; }
		}

		// Field dependientes
		if ($sOrder == "dependientes") {
			$sSortField = "`dependientes`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_dependientes_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_dependientes_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_dependientes_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_dependientes_Sort"] = ""; }
		}

		// Field correo_electronico
		if ($sOrder == "correo_electronico") {
			$sSortField = "`correo_electronico`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_correo_electronico_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_correo_electronico_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_correo_electronico_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_correo_electronico_Sort"] = ""; }
		}

		// Field esposa
		if ($sOrder == "esposa") {
			$sSortField = "`esposa`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_esposa_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_esposa_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_esposa_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_esposa_Sort"] = ""; }
		}

		// Field calle_domicilio
		if ($sOrder == "calle_domicilio") {
			$sSortField = "`calle_domicilio`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_calle_domicilio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_calle_domicilio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_calle_domicilio_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_calle_domicilio_Sort"] = ""; }
		}

		// Field colonia_domicilio
		if ($sOrder == "colonia_domicilio") {
			$sSortField = "`colonia_domicilio`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_colonia_domicilio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_colonia_domicilio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_colonia_domicilio_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_colonia_domicilio_Sort"] = ""; }
		}

		// Field entidad_domicilio
		if ($sOrder == "entidad_domicilio") {
			$sSortField = "`entidad_domicilio`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_entidad_domicilio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_entidad_domicilio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_entidad_domicilio_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_entidad_domicilio_Sort"] = ""; }
		}

		// Field codigo_postal_domicilio
		if ($sOrder == "codigo_postal_domicilio") {
			$sSortField = "`codigo_postal_domicilio`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_codigo_postal_domicilio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_codigo_postal_domicilio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_codigo_postal_domicilio_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_codigo_postal_domicilio_Sort"] = ""; }
		}

		// Field ubicacion_domicilio
		if ($sOrder == "ubicacion_domicilio") {
			$sSortField = "`ubicacion_domicilio`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ubicacion_domicilio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ubicacion_domicilio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ubicacion_domicilio_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ubicacion_domicilio_Sort"] = ""; }
		}

		// Field tipo_vivienda
		if ($sOrder == "tipo_vivienda") {
			$sSortField = "`tipo_vivienda`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tipo_vivienda_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tipo_vivienda_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tipo_vivienda_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tipo_vivienda_Sort"] = ""; }
		}

		// Field telefono_domicilio
		if ($sOrder == "telefono_domicilio") {
			$sSortField = "`telefono_domicilio`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_telefono_domicilio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_telefono_domicilio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_telefono_domicilio_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_telefono_domicilio_Sort"] = ""; }
		}

		// Field celular
		if ($sOrder == "celular") {
			$sSortField = "`celular`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_celular_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_celular_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_celular_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_celular_Sort"] = ""; }
		}

		// Field otro_tel_domicilio_1
		if ($sOrder == "otro_tel_domicilio_1") {
			$sSortField = "`otro_tel_domicilio_1`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_otro_tel_domicilio_1_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_otro_tel_domicilio_1_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_otro_tel_domicilio_1_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_otro_tel_domicilio_1_Sort"] = ""; }
		}

		// Field otro_telefono_domicilio_2
		if ($sOrder == "otro_telefono_domicilio_2") {
			$sSortField = "`otro_telefono_domicilio_2`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_otro_telefono_domicilio_2_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_otro_telefono_domicilio_2_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_otro_telefono_domicilio_2_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_otro_telefono_domicilio_2_Sort"] = ""; }
		}

		// Field antiguedad
		if ($sOrder == "antiguedad") {
			$sSortField = "`antiguedad`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_antiguedad_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_antiguedad_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_antiguedad_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_antiguedad_Sort"] = ""; }
		}

		// Field tel_arrendatario_domicilio
		if ($sOrder == "tel_arrendatario_domicilio") {
			$sSortField = "`tel_arrendatario_domicilio`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_arrendatario_domicilio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_arrendatario_domicilio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_arrendatario_domicilio_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_arrendatario_domicilio_Sort"] = ""; }
		}

		// Field renta_mensula_domicilio
		if ($sOrder == "renta_mensula_domicilio") {
			$sSortField = "`renta_mensula_domicilio`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_renta_mensula_domicilio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_renta_mensula_domicilio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_renta_mensula_domicilio_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_renta_mensula_domicilio_Sort"] = ""; }
		}

		// Field giro_negocio
		if ($sOrder == "giro_negocio") {
			$sSortField = "`giro_negocio`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_giro_negocio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_giro_negocio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_giro_negocio_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_giro_negocio_Sort"] = ""; }
		}

		// Field calle_negocio
		if ($sOrder == "calle_negocio") {
			$sSortField = "`calle_negocio`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_calle_negocio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_calle_negocio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_calle_negocio_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_calle_negocio_Sort"] = ""; }
		}

		// Field colonia_negocio
		if ($sOrder == "colonia_negocio") {
			$sSortField = "`colonia_negocio`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_colonia_negocio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_colonia_negocio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_colonia_negocio_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_colonia_negocio_Sort"] = ""; }
		}

		// Field entidad_negocio
		if ($sOrder == "entidad_negocio") {
			$sSortField = "`entidad_negocio`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_entidad_negocio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_entidad_negocio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_entidad_negocio_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_entidad_negocio_Sort"] = ""; }
		}

		// Field ubicacion_negocio
		if ($sOrder == "ubicacion_negocio") {
			$sSortField = "`ubicacion_negocio`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ubicacion_negocio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ubicacion_negocio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ubicacion_negocio_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ubicacion_negocio_Sort"] = ""; }
		}

		// Field codigo_postal_negocio
		if ($sOrder == "codigo_postal_negocio") {
			$sSortField = "`codigo_postal_negocio`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_codigo_postal_negocio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_codigo_postal_negocio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_codigo_postal_negocio_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_codigo_postal_negocio_Sort"] = ""; }
		}

		// Field tipo_local_negocio
		if ($sOrder == "tipo_local_negocio") {
			$sSortField = "`tipo_local_negocio`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tipo_local_negocio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tipo_local_negocio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tipo_local_negocio_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tipo_local_negocio_Sort"] = ""; }
		}

		// Field antiguedad_negocio
		if ($sOrder == "antiguedad_negocio") {
			$sSortField = "`antiguedad_negocio`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_antiguedad_negocio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_antiguedad_negocio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_antiguedad_negocio_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_antiguedad_negocio_Sort"] = ""; }
		}

		// Field tel_arrendatario_negocio
		if ($sOrder == "tel_arrendatario_negocio") {
			$sSortField = "`tel_arrendatario_negocio`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_arrendatario_negocio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_arrendatario_negocio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_arrendatario_negocio_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_arrendatario_negocio_Sort"] = ""; }
		}

		// Field renta_mensual
		if ($sOrder == "renta_mensual") {
			$sSortField = "`renta_mensual`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_renta_mensual_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_renta_mensual_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_renta_mensual_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_renta_mensual_Sort"] = ""; }
		}

		// Field tel_negocio
		if ($sOrder == "tel_negocio") {
			$sSortField = "`tel_negocio`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_negocio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_negocio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_negocio_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_negocio_Sort"] = ""; }
		}

		// Field solicitud_compra
		if ($sOrder == "solicitud_compra") {
			$sSortField = "`solicitud_compra`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_solicitud_compra_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_solicitud_compra_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_solicitud_compra_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_solicitud_compra_Sort"] = ""; }
		}

		// Field referencia_com_1
		if ($sOrder == "referencia_com_1") {
			$sSortField = "`referencia_com_1`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_1_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_1_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_1_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_1_Sort"] = ""; }
		}

		// Field referencia_com_2
		if ($sOrder == "referencia_com_2") {
			$sSortField = "`referencia_com_2`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_2_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_2_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_2_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_2_Sort"] = ""; }
		}

		// Field referencia_com_3
		if ($sOrder == "referencia_com_3") {
			$sSortField = "`referencia_com_3`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_3_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_3_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_3_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_3_Sort"] = ""; }
		}

		// Field referencia_com_4
		if ($sOrder == "referencia_com_4") {
			$sSortField = "`referencia_com_4`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_4_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_4_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_4_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_4_Sort"] = ""; }
		}

		// Field tel_referencia_1
		if ($sOrder == "tel_referencia_1") {
			$sSortField = "`tel_referencia_1`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_1_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_1_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_1_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_1_Sort"] = ""; }
		}

		// Field tel_referencia_2
		if ($sOrder == "tel_referencia_2") {
			$sSortField = "`tel_referencia_2`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_2_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_2_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_2_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_2_Sort"] = ""; }
		}

		// Field tel_referencia_3
		if ($sOrder == "tel_referencia_3") {
			$sSortField = "`tel_referencia_3`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_3_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_3_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_3_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_3_Sort"] = ""; }
		}

		// Field tel_referencia_4
		if ($sOrder == "tel_referencia_4") {
			$sSortField = "`tel_referencia_4`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_4_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_4_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_4_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_4_Sort"] = ""; }
		}

		// Field parentesco_ref_1
		if ($sOrder == "parentesco_ref_1") {
			$sSortField = "`parentesco_ref_1`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_1_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_1_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_1_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_1_Sort"] = ""; }
		}

		// Field parentesco_ref_2
		if ($sOrder == "parentesco_ref_2") {
			$sSortField = "`parentesco_ref_2`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_2_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_2_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_2_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_2_Sort"] = ""; }
		}

		// Field parentesco_ref_3
		if ($sOrder == "parentesco_ref_3") {
			$sSortField = "`parentesco_ref_3`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_3_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_3_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_3_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_3_Sort"] = ""; }
		}

		// Field parentesco_ref_4
		if ($sOrder == "parentesco_ref_4") {
			$sSortField = "`parentesco_ref_4`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_4_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_4_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_4_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_4_Sort"] = ""; }
		}

		// Field ing_fam_negocio
		if ($sOrder == "ing_fam_negocio") {
			$sSortField = "`ing_fam_negocio`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_negocio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_negocio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_negocio_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_negocio_Sort"] = ""; }
		}

		// Field ing_fam_otro_th
		if ($sOrder == "ing_fam_otro_th") {
			$sSortField = "`ing_fam_otro_th`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_otro_th_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_otro_th_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_otro_th_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_otro_th_Sort"] = ""; }
		}

		// Field ing_fam_1
		if ($sOrder == "ing_fam_1") {
			$sSortField = "`ing_fam_1`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_1_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_1_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_1_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_1_Sort"] = ""; }
		}

		// Field ing_fam_2
		if ($sOrder == "ing_fam_2") {
			$sSortField = "`ing_fam_2`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_2_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_2_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_2_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_2_Sort"] = ""; }
		}

		// Field ing_fam_deuda_1
		if ($sOrder == "ing_fam_deuda_1") {
			$sSortField = "`ing_fam_deuda_1`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_deuda_1_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_deuda_1_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_deuda_1_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_deuda_1_Sort"] = ""; }
		}

		// Field ing_fam_deuda_2
		if ($sOrder == "ing_fam_deuda_2") {
			$sSortField = "`ing_fam_deuda_2`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_deuda_2_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_deuda_2_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_deuda_2_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_deuda_2_Sort"] = ""; }
		}

		// Field ing_fam_total
		if ($sOrder == "ing_fam_total") {
			$sSortField = "`ing_fam_total`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_total_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_total_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_total_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_total_Sort"] = ""; }
		}

		// Field ing_fam_cuales_1
		if ($sOrder == "ing_fam_cuales_1") {
			$sSortField = "`ing_fam_cuales_1`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_1_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_1_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_1_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_1_Sort"] = ""; }
		}

		// Field ing_fam_cuales_2
		if ($sOrder == "ing_fam_cuales_2") {
			$sSortField = "`ing_fam_cuales_2`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_2_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_2_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_2_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_2_Sort"] = ""; }
		}

		// Field ing_fam_cuales_3
		if ($sOrder == "ing_fam_cuales_3") {
			$sSortField = "`ing_fam_cuales_3`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_3_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_3_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_3_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_3_Sort"] = ""; }
		}

		// Field ing_fam_cuales_4
		if ($sOrder == "ing_fam_cuales_4") {
			$sSortField = "`ing_fam_cuales_4`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_4_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_4_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_4_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_4_Sort"] = ""; }
		}

		// Field ing_fam_cuales_5
		if ($sOrder == "ing_fam_cuales_5") {
			$sSortField = "`ing_fam_cuales_5`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_5_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_5_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_5_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_5_Sort"] = ""; }
		}

		// Field flujos_neg_ventas
		if ($sOrder == "flujos_neg_ventas") {
			$sSortField = "`flujos_neg_ventas`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_ventas_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_ventas_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_ventas_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_ventas_Sort"] = ""; }
		}

		// Field flujos_neg_proveedor_1
		if ($sOrder == "flujos_neg_proveedor_1") {
			$sSortField = "`flujos_neg_proveedor_1`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_1_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_1_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_1_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_1_Sort"] = ""; }
		}

		// Field flujos_neg_proveedor_2
		if ($sOrder == "flujos_neg_proveedor_2") {
			$sSortField = "`flujos_neg_proveedor_2`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_2_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_2_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_2_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_2_Sort"] = ""; }
		}

		// Field flujos_neg_proveedor_3
		if ($sOrder == "flujos_neg_proveedor_3") {
			$sSortField = "`flujos_neg_proveedor_3`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_3_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_3_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_3_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_3_Sort"] = ""; }
		}

		// Field flujos_neg_proveedor_4
		if ($sOrder == "flujos_neg_proveedor_4") {
			$sSortField = "`flujos_neg_proveedor_4`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_4_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_4_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_4_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_4_Sort"] = ""; }
		}

		// Field flujos_neg_gasto_1
		if ($sOrder == "flujos_neg_gasto_1") {
			$sSortField = "`flujos_neg_gasto_1`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_gasto_1_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_gasto_1_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_gasto_1_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_gasto_1_Sort"] = ""; }
		}

		// Field flujos_neg_gasto_2
		if ($sOrder == "flujos_neg_gasto_2") {
			$sSortField = "`flujos_neg_gasto_2`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_gasto_2_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_gasto_2_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_gasto_2_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_gasto_2_Sort"] = ""; }
		}

		// Field flujos_neg_gasto_3
		if ($sOrder == "flujos_neg_gasto_3") {
			$sSortField = "`flujos_neg_gasto_3`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_gasto_3_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_gasto_3_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_gasto_3_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_gasto_3_Sort"] = ""; }
		}

		// Field flujos_neg_cual_1
		if ($sOrder == "flujos_neg_cual_1") {
			$sSortField = "`flujos_neg_cual_1`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_1_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_1_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_1_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_1_Sort"] = ""; }
		}

		// Field flujos_neg_cual_2
		if ($sOrder == "flujos_neg_cual_2") {
			$sSortField = "`flujos_neg_cual_2`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_2_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_2_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_2_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_2_Sort"] = ""; }
		}

		// Field flujos_neg_cual_3
		if ($sOrder == "flujos_neg_cual_3") {
			$sSortField = "`flujos_neg_cual_3`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_3_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_3_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_3_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_3_Sort"] = ""; }
		}

		// Field flujos_neg_cual_4
		if ($sOrder == "flujos_neg_cual_4") {
			$sSortField = "`flujos_neg_cual_4`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_4_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_4_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_4_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_4_Sort"] = ""; }
		}

		// Field flujos_neg_cual_5
		if ($sOrder == "flujos_neg_cual_5") {
			$sSortField = "`flujos_neg_cual_5`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_5_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_5_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_5_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_5_Sort"] = ""; }
		}

		// Field flujos_neg_cual_6
		if ($sOrder == "flujos_neg_cual_6") {
			$sSortField = "`flujos_neg_cual_6`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_6_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_6_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_6_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_6_Sort"] = ""; }
		}

		// Field flujos_neg_cual_7
		if ($sOrder == "flujos_neg_cual_7") {
			$sSortField = "`flujos_neg_cual_7`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_7_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_7_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_7_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_7_Sort"] = ""; }
		}

		// Field ingreso_negocio
		if ($sOrder == "ingreso_negocio") {
			$sSortField = "`ingreso_negocio`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ingreso_negocio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ingreso_negocio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ingreso_negocio_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ingreso_negocio_Sort"] = ""; }
		}

		// Field inv_neg_fija_conc_1
		if ($sOrder == "inv_neg_fija_conc_1") {
			$sSortField = "`inv_neg_fija_conc_1`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_1_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_1_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_1_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_1_Sort"] = ""; }
		}

		// Field inv_neg_fija_conc_2
		if ($sOrder == "inv_neg_fija_conc_2") {
			$sSortField = "`inv_neg_fija_conc_2`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_2_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_2_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_2_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_2_Sort"] = ""; }
		}

		// Field inv_neg_fija_conc_3
		if ($sOrder == "inv_neg_fija_conc_3") {
			$sSortField = "`inv_neg_fija_conc_3`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_3_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_3_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_3_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_3_Sort"] = ""; }
		}

		// Field inv_neg_fija_conc_4
		if ($sOrder == "inv_neg_fija_conc_4") {
			$sSortField = "`inv_neg_fija_conc_4`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_4_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_4_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_4_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_4_Sort"] = ""; }
		}

		// Field inv_neg_fija_valor_1
		if ($sOrder == "inv_neg_fija_valor_1") {
			$sSortField = "`inv_neg_fija_valor_1`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_1_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_1_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_1_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_1_Sort"] = ""; }
		}

		// Field inv_neg_fija_valor_2
		if ($sOrder == "inv_neg_fija_valor_2") {
			$sSortField = "`inv_neg_fija_valor_2`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_2_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_2_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_2_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_2_Sort"] = ""; }
		}

		// Field inv_neg_fija_valor_3
		if ($sOrder == "inv_neg_fija_valor_3") {
			$sSortField = "`inv_neg_fija_valor_3`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_3_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_3_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_3_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_3_Sort"] = ""; }
		}

		// Field inv_neg_fija_valor_4
		if ($sOrder == "inv_neg_fija_valor_4") {
			$sSortField = "`inv_neg_fija_valor_4`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_4_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_4_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_4_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_4_Sort"] = ""; }
		}

		// Field inv_neg_total_fija
		if ($sOrder == "inv_neg_total_fija") {
			$sSortField = "`inv_neg_total_fija`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_total_fija_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_total_fija_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_total_fija_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_total_fija_Sort"] = ""; }
		}

		// Field inv_neg_var_conc_1
		if ($sOrder == "inv_neg_var_conc_1") {
			$sSortField = "`inv_neg_var_conc_1`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_1_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_1_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_1_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_1_Sort"] = ""; }
		}

		// Field inv_neg_var_conc_2
		if ($sOrder == "inv_neg_var_conc_2") {
			$sSortField = "`inv_neg_var_conc_2`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_2_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_2_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_2_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_2_Sort"] = ""; }
		}

		// Field inv_neg_var_conc_3
		if ($sOrder == "inv_neg_var_conc_3") {
			$sSortField = "`inv_neg_var_conc_3`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_3_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_3_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_3_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_3_Sort"] = ""; }
		}

		// Field inv_neg_var_conc_4
		if ($sOrder == "inv_neg_var_conc_4") {
			$sSortField = "`inv_neg_var_conc_4`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_4_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_4_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_4_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_4_Sort"] = ""; }
		}

		// Field inv_neg_var_valor_1
		if ($sOrder == "inv_neg_var_valor_1") {
			$sSortField = "`inv_neg_var_valor_1`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_1_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_1_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_1_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_1_Sort"] = ""; }
		}

		// Field inv_neg_var_valor_2
		if ($sOrder == "inv_neg_var_valor_2") {
			$sSortField = "`inv_neg_var_valor_2`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_2_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_2_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_2_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_2_Sort"] = ""; }
		}

		// Field inv_neg_var_valor_3
		if ($sOrder == "inv_neg_var_valor_3") {
			$sSortField = "`inv_neg_var_valor_3`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_3_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_3_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_3_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_3_Sort"] = ""; }
		}

		// Field inv_neg_var_valor_4
		if ($sOrder == "inv_neg_var_valor_4") {
			$sSortField = "`inv_neg_var_valor_4`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_4_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_4_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_4_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_4_Sort"] = ""; }
		}

		// Field inv_neg_total_var
		if ($sOrder == "inv_neg_total_var") {
			$sSortField = "`inv_neg_total_var`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_total_var_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_total_var_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_total_var_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_total_var_Sort"] = ""; }
		}

		// Field inv_neg_activos_totales
		if ($sOrder == "inv_neg_activos_totales") {
			$sSortField = "`inv_neg_activos_totales`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_activos_totales_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_activos_totales_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_activos_totales_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_activos_totales_Sort"] = ""; }
		}

		// Field fecha
		if ($sOrder == "fecha") {
			$sSortField = "`fecha`";
			$sLastSort = @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_fecha_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["adquisicionmaquinaria_x_fecha_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_fecha_Sort"] <> "") { @$HTTP_SESSION_VARS["adquisicionmaquinaria_x_fecha_Sort"] = ""; }
		}
		$HTTP_SESSION_VARS["adquisicionmaquinaria_OrderBy"] = $sSortField . " " . $sThisSort;
		$HTTP_SESSION_VARS["adquisicionmaquinaria_REC"] = 1;
	}
	$sOrderBy = @$HTTP_SESSION_VARS["adquisicionmaquinaria_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$HTTP_SESSION_VARS["adquisicionmaquinaria_OrderBy"] = $sOrderBy;
	}
}

//-------------------------------------------------------------------------------
// Function SetUpStartRec
//- Set up Starting Record parameters based on Pager Navigation
// - Variables setup: nStartRec

function SetUpStartRec()
{

	// Check for a START parameter
	global $HTTP_SESSION_VARS;
	global $HTTP_GET_VARS;
	global $nStartRec;
	global $nDisplayRecs;
	global $nTotalRecs;
	if (strlen(@$HTTP_GET_VARS["start"]) > 0) {
		$nStartRec = @$HTTP_GET_VARS["start"];
		$HTTP_SESSION_VARS["adquisicionmaquinaria_REC"] = $nStartRec;
	}
	elseif (strlen(@$HTTP_GET_VARS["pageno"]) > 0) {
		$nPageNo = @$HTTP_GET_VARS["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}
			elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$HTTP_SESSION_VARS["adquisicionmaquinaria_REC"] = $nStartRec;
		}
		else
		{
			$nStartRec = @$HTTP_SESSION_VARS["adquisicionmaquinaria_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$HTTP_SESSION_VARS["adquisicionmaquinaria_REC"] = $nStartRec;
			}
		}
	}
	else
	{
		$nStartRec = @$HTTP_SESSION_VARS["adquisicionmaquinaria_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$HTTP_SESSION_VARS["adquisicionmaquinaria_REC"] = $nStartRec;
		}
	}
}

//-------------------------------------------------------------------------------
// Function ResetCmd
// - Clear list page parameters
// - RESET: reset search parameters
// - RESETALL: reset search & master/detail parameters
// - RESETSORT: reset sort parameters

function ResetCmd()
{
		global $HTTP_SESSION_VARS;
		global $HTTP_GET_VARS;

	// Get Reset Cmd
	if (strlen(@$HTTP_GET_VARS["cmd"]) > 0) {
		$sCmd = @$HTTP_GET_VARS["cmd"];

		// Reset Search Criteria
		if (strtoupper($sCmd) == "RESET") {
			$sSrchWhere = "";
			$HTTP_SESSION_VARS["adquisicionmaquinaria_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}
		elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$HTTP_SESSION_VARS["adquisicionmaquinaria_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$HTTP_SESSION_VARS["adquisicionmaquinaria_OrderBy"] = $sOrderBy;
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_adquisicionMaquinaria_id_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_adquisicionMaquinaria_id_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_nombre_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_nombre_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_rfc_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_rfc_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_curp_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_curp_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_fecha_nacimiento_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_fecha_nacimiento_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_sexo_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_sexo_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_integrantes_familia_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_integrantes_familia_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_dependientes_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_dependientes_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_correo_electronico_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_correo_electronico_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_esposa_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_esposa_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_calle_domicilio_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_calle_domicilio_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_colonia_domicilio_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_colonia_domicilio_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_entidad_domicilio_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_entidad_domicilio_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_codigo_postal_domicilio_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_codigo_postal_domicilio_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ubicacion_domicilio_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_ubicacion_domicilio_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tipo_vivienda_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_tipo_vivienda_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_telefono_domicilio_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_telefono_domicilio_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_celular_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_celular_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_otro_tel_domicilio_1_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_otro_tel_domicilio_1_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_otro_telefono_domicilio_2_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_otro_telefono_domicilio_2_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_antiguedad_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_antiguedad_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_arrendatario_domicilio_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_arrendatario_domicilio_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_renta_mensula_domicilio_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_renta_mensula_domicilio_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_giro_negocio_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_giro_negocio_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_calle_negocio_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_calle_negocio_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_colonia_negocio_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_colonia_negocio_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_entidad_negocio_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_entidad_negocio_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ubicacion_negocio_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_ubicacion_negocio_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_codigo_postal_negocio_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_codigo_postal_negocio_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tipo_local_negocio_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_tipo_local_negocio_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_antiguedad_negocio_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_antiguedad_negocio_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_arrendatario_negocio_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_arrendatario_negocio_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_renta_mensual_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_renta_mensual_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_negocio_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_negocio_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_solicitud_compra_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_solicitud_compra_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_1_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_1_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_2_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_2_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_3_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_3_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_4_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_referencia_com_4_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_1_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_1_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_2_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_2_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_3_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_3_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_4_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_tel_referencia_4_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_1_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_1_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_2_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_2_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_3_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_3_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_4_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_parentesco_ref_4_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_negocio_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_negocio_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_otro_th_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_otro_th_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_1_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_1_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_2_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_2_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_deuda_1_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_deuda_1_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_deuda_2_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_deuda_2_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_total_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_total_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_1_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_1_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_2_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_2_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_3_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_3_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_4_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_4_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_5_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_ing_fam_cuales_5_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_ventas_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_ventas_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_1_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_1_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_2_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_2_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_3_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_3_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_4_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_proveedor_4_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_gasto_1_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_gasto_1_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_gasto_2_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_gasto_2_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_gasto_3_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_gasto_3_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_1_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_1_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_2_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_2_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_3_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_3_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_4_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_4_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_5_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_5_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_6_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_6_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_7_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_flujos_neg_cual_7_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_ingreso_negocio_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_ingreso_negocio_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_1_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_1_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_2_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_2_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_3_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_3_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_4_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_conc_4_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_1_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_1_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_2_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_2_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_3_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_3_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_4_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_fija_valor_4_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_total_fija_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_total_fija_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_1_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_1_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_2_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_2_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_3_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_3_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_4_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_conc_4_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_1_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_1_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_2_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_2_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_3_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_3_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_4_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_var_valor_4_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_total_var_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_total_var_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_activos_totales_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_inv_neg_activos_totales_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["adquisicionmaquinaria_x_fecha_Sort"] <> "") { $HTTP_SESSION_VARS["adquisicionmaquinaria_x_fecha_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$HTTP_SESSION_VARS["adquisicionmaquinaria_REC"] = $nStartRec;
	}
}
?>
