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
$x_formato_docto_id = Null;
$ox_formato_docto_id = Null;
$x_descripcion = Null;
$ox_descripcion = Null;
$x_contenido = Null;
$ox_contenido = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Get key

$x_formato_docto_id = 1;
$x_solicitud_id = @$_GET["solicitud_id"];

if (($x_solicitud_id == "") || ((is_null($x_solicitud_id)))) {
	ob_end_clean();
	echo "NO se localizaron los datos.";
	exit();
}


//$x_formato_docto_id = (get_magic_quotes_gpc()) ? stripslashes($x_formato_docto_id) : $x_formato_docto_id;
// Get action

$sAction = @$_POST["a_view"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";	// Display with input box
}

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No records found";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_formato_doctolist.php");
			exit();
		}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>e - SF >  CREA Technologies</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">td img {display: block;}</style>
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#FFFFFF">
<script type="text/javascript" src="ew.js"></script>
<p>
<font size="2">
<?php
include("utilerias/datefunc.php");
include("amount2txt.php");


//DATOS GENERALES
$sSql = "SELECT entidad_id, descripcion FROM delegacion where delegacion_id = $x_delegacion_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query dg: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_delegacion = $row["descripcion"];
$x_entidad_id = $row["entidad_id"];
phpmkr_free_result($rs);

$sSql = "SELECT nombre FROM entidad where entidad_id = $x_entidad_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query dg: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_entidad = $row["nombre"];
phpmkr_free_result($rs);



$x_direccion = " en la calle ".$x_calle.". Colonia ".$x_colonia.". C.P. ".$x_codigo_postal.". en la Delegacion ".$x_delegacion.", ".$x_entidad;
$x_cliente = strtoupper($x_nombre_completo)." ".strtoupper($x_apellido_paterno)." ".strtoupper($x_apellido_materno);
$x_direccion = strtoupper($x_direccion);

$x_solocliente = $x_cliente;

if($x_sexo == 1){
	$x_cliente = "EL C. ".$x_cliente;
}else{
	$x_cliente = "LA C. ".$x_cliente;
}


// el campo plazo
//SOLICITUD
$sSql = "SELECT valor FROM plazo where plazo_id = $x_plazo_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_plazo_valor = $row["valor"];
phpmkr_free_result($rs);

$sSql = "SELECT valor FROM forma_pago where forma_pago_id = $x_forma_pago_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_forma_pago_valor = $row["valor"];
phpmkr_free_result($rs);

$x_importe_letras = covertirNumLetras($x_importe_solicitado);
$x_importe = "$".FormatNumber($x_importe_solicitado,2,0,0,1)." (-$x_importe_letras-) ";
//$5,000.00 (-Cinco mil pesos, 00/100 M.N.-),


//CREDITO
$sSql = "SELECT credito.banco_id, credito.tarjeta_num, fecha_vencimiento, referencia_pago, tasa, tasa_moratoria, fecha_otrogamiento, credito.credito_id FROM credito where solicitud_id = $x_solicitud_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_credito_id = $row["credito_id"];
$x_banco_id = $row["banco_id"];
$x_cheque_valor = $row["referencia_pago"];
$x_tasa_valor = $row["tasa"];
$x_tasa_moratoria_valor = $row["tasa_moratoria"];
$x_fecha_otorgamiento_valor = $row["fecha_otrogamiento"];
$x_fecha_vencimiento_valor = $row["fecha_vencimiento"];
$x_tarjeta_numero = $row["tarjeta_num"];

phpmkr_free_result($rs);

$x_cheque = "-$x_cheque_valor-";

switch ($x_forma_pago_id)
{
	case 1: // SEMANAL
		$x_forma_pago_plural = "semanas";
		$x_forma_pago_singular = "semana";
		$x_forma_pago_periodo = "semanal";
		$x_forma_pagoperiodo_plural = "semanales";

		$x_numero_plazo = $x_plazo_valor * 4;
		$x_plazo = $x_numero_plazo." semanas ";

		$x_tasa_anual_valor = $x_tasa_valor * 52;

		break;
	case 2: // CATORCENAL
		$x_forma_pago_plural = "catorcenas";
		$x_forma_pago_singular = "catorcena";
		$x_forma_pago_periodo = "catorcenal";
		$x_forma_pagoperiodo_plural = "catorcenales";

		$x_numero_plazo = $x_plazo_valor * 2;
		$x_plazo = $x_numero_plazo." catorcenas ";

		$x_tasa_anual_valor = $x_tasa_valor * 26;

		break;
	case 3: // MENSUAL
		$x_forma_pago_plural = "meses";
		$x_forma_pago_singular = "mes";
		$x_forma_pago_periodo = "mensual";
		$x_forma_pagoperiodo_plural = "mensuales";

		$x_numero_plazo = $x_plazo_valor * 1;
		$x_plazo = $x_numero_plazo." meses ";

		$x_tasa_anual_valor = $x_tasa_valor * 13.03;

		break;
	case 4: // QUINCENAL
		$x_forma_pago_plural = "quincenas";
		$x_forma_pago_singular = "quincena";
		$x_forma_pago_periodo = "quincenal";
		$x_forma_pagoperiodo_plural = "quincenales";

		$x_numero_plazo = $x_plazo_valor * 2;
		$x_plazo = $x_numero_plazo." quincenas ";

		$x_tasa_anual_valor = $x_tasa_valor * 24.33;

		break;

}

$decimales = strpos($x_tasa_valor,".");
if($decimales > 0){
	$centavos = substr($x_tasa_valor,$decimales+1,2);
}
if(intval($centavos) > 0){
	$x_tasa_decimales = 2;
}else{
	$x_tasa_decimales = 0;
}

$decimales = strpos($x_tasa_moratoria_valor,".");
if($decimales > 0){
	$centavos = substr($x_tasa_moratoria_valor,$decimales+1,2);
}
if(intval($centavos) > 0){
	$x_tasa_moratoria_decimales = 2;
}else{
	$x_tasa_moratoria_decimales = 0;
}

$decimales = strpos($x_tasa_anual_valor,".");
if($decimales > 0){
	$centavos = substr($x_tasa_anual_valor,$decimales+1,2);
}
if(intval($centavos) > 0){
	$x_tasa_anual_decimales = 2;
}else{
	$x_tasa_anual_decimales = 0;
}

$x_tasa_letras = covertirPorcientoLetras($x_tasa_valor);
$x_tasa = FormatNumber($x_tasa_valor,$x_tasa_decimales,0,0,1)."%(-".trim($x_tasa_letras)."-) ";
$x_tasa_anual_letras = covertirPorcientoLetras($x_tasa_anual_valor);
$x_tasa_anual = FormatNumber($x_tasa_anual_valor,$x_tasa_anual_decimales,0,0,1)."%(-".trim($x_tasa_anual_letras)."-) ";


//1.98% (-uno punto noventa y ocho porciento-),

$x_tasa_moratoria_letras = covertirPorcientoLetras($x_tasa_moratoria_valor);
$x_moratorios = FormatNumber($x_tasa_moratoria_valor,$x_tasa_moratoria_decimales,0,0,1)."%(-".trim($x_tasa_moratoria_letras)."-) ";
//1.98% (-uno punto noventa y ocho porciento-),

//$x_fecha_contrato = FechaLetras($x_fecha_otorgamiento_valor);

$x_fecha_contrato = FechaLetras(strtotime(ConvertDateToMysqlFormat($x_fecha_otorgamiento_valor)));

$x_fecha_vencimiento = FechaLetras(strtotime(ConvertDateToMysqlFormat($x_fecha_vencimiento_valor)));



//TABLA DE VENC
$sSql = "SELECT * FROM vencimiento where vencimiento.credito_id = $x_credito_id ORDER BY vencimiento.vencimiento_num";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$x_tabla = "
<table class='ewTable' align='center' >
<tr>
<td valign='top'><span>
<b>Numero de Pago</b>
</span></td>
<td valign='top'><span>
<b>Fecha para realizar el Pago</b>
</span></td>
<td valign='top'><span>
<b>Capital</b>
</span></td>
<td valign='top'><span>
<b>Capital</b>
</span></td>
<td valign='top'><span>
<b>Interes</b>
</span></td>
<td valign='top'><span>
<b>IVA</b>
</span></td>
<td valign='top'><span>
<b>Total a Pagar</b>
</span></td>
</tr>";

$x_saldo = $x_importe_solicitado;
$nRecCount = 0;
while ($row = @phpmkr_fetch_array($rs)) {
	$nRecCount = $nRecCount + 1;
	if ($nRecCount >= $nStartRec) {
		$nRecActual++;

		// Set row color
		$sItemRowClass = " class=\"ewTableRow\"";

		// Display alternate color for rows
		if ($nRecCount % 2 <> 1) {
			$sItemRowClass = " class=\"ewTableAltRow\"";
		}
		$x_vencimiento_id = $row["vencimiento_id"];
		$x_vencimiento_num = $row["vencimiento_num"];
		$x_credito_id = $row["credito_id"];
		$x_vencimiento_status_id = $row["vencimiento_status_id"];



		$x_fecha_vencimiento_tab = $row["fecha_vencimiento"];
		$x_importe_tab = $row["importe"];
		$x_importe_tab2 = $row["importe"] + $row["interes"];
		$x_interes_tab = $row["interes"];
		$x_interes_moratorio_tab = $row["interes_moratorio"];
		$x_iva_tab = $row["iva"];
		if(empty($x_iva_tab)){
			$x_iva_tab = 0;
		}

		$x_total = $x_importe_tab + $x_interes_tab + $x_interes_moratorio_tab + $x_iva_tab;


		$x_total_pagos = $x_total_pagos + $x_importe_tab;
		$x_total_interes = $x_total_interes + $x_interes_tab;
		$x_total_iva = $x_total_iva + $x_iva_tab;				
		$x_total_moratorios = $x_total_moratorios + $x_interes_moratorio_tab;
		$x_total_total = $x_total_total + $x_total;

		if(($x_vencimiento_status_id == 2) || ($x_vencimiento_status_id == 5)){

			$sSqlWrk = "SELECT fecha_pago FROM recibo join recibo_vencimiento on recibo_vencimiento.recibo_id = recibo.recibo_id where recibo_vencimiento.vencimiento_id = $x_vencimiento_id";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$rowwrk = phpmkr_fetch_array($rswrk);
			$x_fecha_pago = $rowwrk["fecha_pago"];

			@phpmkr_free_result($rswrk);

		}else{
			$x_fecha_pago  = "";

			$x_total_pagos_d = $x_total_pagos_d + $x_importe;
			$x_total_interes_d = $x_total_interes_d + $x_interes;
			$x_total_interes_d = $x_total_interes_d + $x_iva;						
			$x_total_moratorios_d = $x_total_moratorios_d + $x_interes_moratorio;
			$x_total_total_d = $x_total_total_d + $x_total;

		}

$x_tabla .= "
<tr $sItemRowClass>
<td align='right'><span>
$x_vencimiento_num
</span></td>
<td align='center'><span>".FormatDateTime($x_fecha_vencimiento_tab,7)."
</span></td>
<td align='right'><span>".FormatNumber($x_saldo,2,0,0,1)."
</span></td>
<td align='right'><span>".FormatNumber($x_importe_tab,2,0,0,1)."
</span></td>
<td align='right'><span>".FormatNumber($x_interes_tab,2,0,0,1)."
</span></td>
<td align='right'><span>".FormatNumber($x_iva_tab,2,0,0,1)."
</span></td>
<td align='right'><span>".FormatNumber($x_total,2,0,0,1)."
</span></td>
</tr>";
$x_saldo = $x_saldo - $x_importe_tab;
	}
}

$x_tabla .= "
<tr>
<td>&nbsp;</td>
<td align='center'><span>
</span></td>
<td align='right'><span>
</span></td>
<td align='right'><span>
</span></td>
<td align='right'><span>
</span></td>
<td align='right'><span>
</span></td>
<td align='right'><span>
<b>".FormatNumber($x_total_total,2,0,0,1)."</b></span></td></tr></table>";
phpmkr_free_result($rs);

$x_numpagos = $nRecCount;
$x_importevenc = $x_importe_tab;



//GARANTIA
$sSql = "SELECT descripcion FROM garantia where solicitud_id = $x_solicitud_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_garantia = $row["descripcion"];
if($x_garantia != ""){
	$x_garantia = ", la cual se describe a continuaci&oacute;n: ".$x_garantia.".";
}else{
	$x_garantia = ".";
}
phpmkr_free_result($rs);

//AVAL
$sSql = "SELECT aval_id, nombre_completo, apellido_paterno, apellido_materno FROM aval where solicitud_id = $x_solicitud_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_aval_id = $row["aval_id"];
$x_aval = strtoupper($row["nombre_completo"]." ".$row["apellido_paterno"]." ".$row["apellido_materno"]);
phpmkr_free_result($rs);


//AVAL DIR
if(($x_aval_id > 0) && (strlen(trim($x_aval)) > 0)){
$sSql = "SELECT * FROM direccion where direccion_tipo_id = 3 and aval_id = $x_aval_id order by direccion_id desc limit 1";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_aval_direccion_id = $row["direccion_id"];
$x_aval_calle = $row["calle"];
$x_aval_colonia = $row["colonia"];
$x_aval_delegacion_id = $row["delegacion_id"];
$x_aval_otra_delegacion = $row["otra_delegacion"];
$x_aval_entidad = $row["entidad"];
$x_aval_codigo_postal = $row["codigo_postal"];
$x_aval_ubicacion = $row["ubicacion"];
$x_aval_antiguedad = $row["antiguedad"];
$x_aval_vivienda_tipo_id = $row["vivienda_tipo_id"];
$x_aval_otro_tipo_vivienda = $row["otro_tipo_vivienda"];
$x_aval_telefono = $row["telefono"];
$x_aval_telefono_secundario = $row["telefono_secundario"];
phpmkr_free_result($rs);

$sSql = "SELECT entidad_id, descripcion FROM delegacion where delegacion_id = $x_aval_delegacion_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query aval: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_aval_delegacion = $row["descripcion"];
$x_aval_entidad_id = $row["entidad_id"];
phpmkr_free_result($rs);

if(!empty($x_aval_entidad_id)){
	$sSql = "SELECT nombre FROM entidad where entidad_id = $x_aval_entidad_id";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query aval $x_aval_id: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_aval_entidad = $row["nombre"];
	phpmkr_free_result($rs);
}else{
	$x_aval_entidad = " ";
}

$x_diraval = " en la calle ".$x_aval_calle.". Colonia ".$x_aval_colonia.". C.P. ".$x_aval_codigo_postal.", en la Delegacion ".$x_aval_delegacion.", ".$x_aval_entidad;
$x_diraval = strtoupper($x_diraval);

}else{

$x_diraval = "";

}

//USUARIO
$sSql = "SELECT * FROM usuario where usuario_id = $x_usuario_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_usuario = $row["usuario"];
$x_clave = $row["clave"];
phpmkr_free_result($rs);



//Actividad

$sSql = "SELECT descripcion FROM actividad where actividad_id = $x_actividad_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_actividad = $row["descripcion"];
phpmkr_free_result($rs);

// aumentar detalle de actividad con prefij ode texto correspondiente.
switch ($x_actividad_id)
{
	case "1": // Get a record to display
	$x_pref_act = " especificamente: ";
	break;
	case "2": // Get a record to display
	$x_pref_act = " consistentes en: ";
	break;
	case "3": // Get a record to display
	$x_pref_act = " : ";
	break;
}
$x_actividad = $x_actividad." ".$x_pref_act." ".$x_actividad_desc;

//TESTIGO Promotor
$sSql = "SELECT * FROM promotor where promotor_id = $x_promotor_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_testigo = $row["nombre_completo"];
phpmkr_free_result($rs);



//BANCO
if($x_banco_id > 0){
$sSql = "SELECT nombre FROM banco where banco_id = $x_banco_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_banco = $row["nombre"];
phpmkr_free_result($rs);
}else{
	$x_banco = "HSBC, S.A.";
}





//TABLA inicial


$x_initabla = "<table width='700' height='805' border='1' align='center' cellpadding='0' cellspacing='0' bordercolor='#CCCCCC'>
                <tr>
                  <td width='300' height='59' align='left' valign='middle' bgcolor='#F3F3F3'><strong>Tasa Anual:</strong></td>
                  <td width='20' align='left' valign='middle' >&nbsp;</td>
                  <td width='380' align='left' valign='middle' >$x_tasa_anual</td>
                </tr>
                <tr>
                  <td align='left' valign='middle'>&nbsp;</td>
                  <td align='left' valign='middle'>&nbsp;</td>
                  <td align='left' valign='middle'>&nbsp;</td>
                </tr>
                <tr>
                  <td height='59' align='left' valign='middle' bgcolor='#F3F3F3'><strong>Monto del Crédito:</strong></td>
                  <td align='left' valign='middle'>&nbsp;</td>
                  <td align='left' valign='middle'>$x_importe</td>
                </tr>
                <tr>
                  <td align='left' valign='middle'>&nbsp;</td>
                  <td align='left' valign='middle'>&nbsp;</td>
                  <td align='left' valign='middle'>&nbsp;</td>
                </tr>
                <tr>
                  <td height='59' align='left' valign='middle' bgcolor='#F3F3F3'><strong>Plazo:</strong></td>
                  <td align='left' valign='middle'>&nbsp;</td>
                  <td align='left' valign='middle'>$x_plazo</td>
                </tr>
                <tr>
                  <td align='left' valign='middle'>&nbsp;</td>
                  <td align='left' valign='middle'>&nbsp;</td>
                  <td align='left' valign='middle'>&nbsp;</td>
                </tr>
                <tr>
                  <td height='60' align='left' valign='middle' bgcolor='#F3F3F3'><strong>Tasa de interés:</strong></td>
                  <td align='left' valign='middle'>&nbsp;</td>
                  <td align='left' valign='middle'>$x_tasa</td>
                </tr>
                <tr>
                  <td align='left' valign='middle'>&nbsp;</td>
                  <td align='left' valign='middle'>&nbsp;</td>
                  <td align='left' valign='middle'>&nbsp;</td>
                </tr>
                <tr>
                  <td height='60' align='left' valign='middle' bgcolor='#F3F3F3'><strong>Comisiones:</strong></td>
                  <td align='left' valign='middle'>&nbsp;</td>
                  <td align='left' valign='middle'>0</td>
                </tr>
                <tr>
                  <td align='left' valign='middle'>&nbsp;</td>
                  <td align='left' valign='middle'>&nbsp;</td>
                  <td align='left' valign='middle'>&nbsp;</td>
                </tr>
                <tr>
                  <td height='61' align='left' valign='middle' bgcolor='#F3F3F3'><strong>Monto y Numero de Pagos:</strong></td>
                  <td align='left' valign='middle'>&nbsp;</td>
                  <td align='left' valign='middle'>$x_numpagos pagos de $x_importe_tab2 </td>
                </tr>
                <tr>
                  <td align='left' valign='middle'>&nbsp;</td>
                  <td align='left' valign='middle'>&nbsp;</td>
                  <td align='left' valign='middle'>&nbsp;</td>
                </tr>
                <tr>
                  <td height='61' align='left' valign='middle' bgcolor='#F3F3F3'><strong>Periocidad de pagos:</strong></td>
                  <td align='left' valign='middle'>&nbsp;</td>
                  <td align='left' valign='middle'>$x_forma_pago_singular</td>
                </tr>
                <tr>
                  <td align='left' valign='middle'>&nbsp;</td>
                  <td align='left' valign='middle'>&nbsp;</td>
                  <td align='left' valign='middle'>&nbsp;</td>
                </tr>
                <tr>
                  <td height='60' align='left' valign='middle' bgcolor='#F3F3F3'><strong>Fecha de corte:</strong></td>
                  <td align='left' valign='middle'>&nbsp;</td>
                  <td align='left' valign='middle'>$x_fecha_vencimiento</td>
                </tr>
                <tr>
                  <td align='left' valign='middle'>&nbsp;</td>
                  <td align='left' valign='middle'>&nbsp;</td>
                  <td align='left' valign='middle'>&nbsp;</td>
                </tr>
                <tr>
                  <td height='51' align='left' valign='middle' bgcolor='#F3F3F3'><strong>Seguros con los que cuenta la operacion:</strong></td>
                  <td align='left' valign='middle'>&nbsp;</td>
                  <td align='left' valign='middle'>------</td>
                </tr>
                <tr>
                  <td align='left' valign='middle'>&nbsp;</td>
                  <td align='left' valign='middle'>&nbsp;</td>
                  <td align='left' valign='middle'>&nbsp;</td>
              </tr>
                <tr>
                  <td align='left' valign='middle' bgcolor='#F3F3F3'><strong>Datos de La unidad especializada de atención a usuarios:</strong></td>
                  <td align='left' valign='middle'>&nbsp;</td>
                  <td align='left' valign='middle'>No. telefónico 1993 3278, ó para atención personalizada en el domicilio ubicado en Av. Revolución 1909 piso 8 col. San Angel. Delegación Alvaro Obregon D.F.</td>
                </tr>
              </table>";


$x_initabla = "<div style=\"page-break-after: always;\">$x_initabla</div>";
//$x_initabla = "<div style=\"page-break-after: always;height:0; line-height:0;\">$x_initabla</div>";



$x_contenido = str_replace("\$x_banco",$x_banco,$x_contenido);
$x_contenido = str_replace("\$x_initabla",$x_initabla,$x_contenido);
$x_contenido = str_replace("\$x_tarjeta",htmlentities($x_tarjeta_numero),$x_contenido);
$x_contenido = str_replace("\$x_usuario",$x_usuario,$x_contenido);
$x_contenido = str_replace("\$x_clave",$x_clave,$x_contenido);
$x_contenido = str_replace("\$x_cheque",$x_cheque,$x_contenido);

$x_contenido = str_replace("\$x_actividad",$x_actividad,$x_contenido);
$x_contenido = str_replace("\$x_testigo",$x_testigo,$x_contenido);

$x_contenido = str_replace("\$x_tabla",$x_tabla,$x_contenido);
$x_contenido = str_replace("\$x_garantia",$x_garantia,$x_contenido);
$x_contenido = str_replace("\$x_aval",htmlentities($x_aval),$x_contenido);
$x_contenido = str_replace("\$x_cliente",$x_cliente,$x_contenido);
$x_contenido = str_replace("\$x_solocliente",htmlentities($x_solocliente),$x_contenido);
$x_contenido = str_replace("\$x_direccion",htmlentities($x_direccion),$x_contenido);
$x_contenido = str_replace("\$x_diraval",htmlentities($x_diraval),$x_contenido);


$x_contenido = str_replace("\$x_forma_pagoperiodo_plural",$x_forma_pagoperiodo_plural,$x_contenido);
$x_contenido = str_replace("\$x_forma_pago_plural",$x_forma_pago_plural,$x_contenido);
$x_contenido = str_replace("\$x_forma_pago_singular",$x_forma_pago_singular,$x_contenido);
$x_contenido = str_replace("\$x_forma_pago_periodo",$x_forma_pago_periodo,$x_contenido);
$x_contenido = str_replace("\$x_plazo",$x_plazo,$x_contenido);

$x_contenido = str_replace("\$x_importe",$x_importe,$x_contenido);

$x_contenido = str_replace("\$x_moratorios",$x_moratorios,$x_contenido);
$x_contenido = str_replace("\$x_tasa",$x_tasa,$x_contenido);
$x_contenido = str_replace("\$x_anual_tasa",$x_tasa_anual,$x_contenido);
$x_contenido = str_replace("\$x_fecha_contrato",$x_fecha_contrato,$x_contenido);
$x_contenido = str_replace("\$x_fecha_vencimiento",$x_fecha_vencimiento,$x_contenido);


//echo htmlspecialchars_decode($x_contenido);
echo $x_contenido;

$sSql = "update solicitud set contrato = 1 where solicitud_id =  ".$x_solicitud_id;
phpmkr_query($sSql, $conn);

?>
</font>
</p>
</body>
</html>

<?php
phpmkr_db_close($conn);
?>
<?php

//-------------------------------------------------------------------------------
// Function LoadData
// - Load Data based on Key Value sKey
// - Variables setup: field variables

function LoadData($conn)
{
	global $x_solicitud_id;

	$sSql = "SELECT * FROM solicitud where solicitud_id = $x_solicitud_id";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	if (phpmkr_num_rows($rs) == 0) {
		$bLoadData = false;
	}else{
		$bLoadData = true;
		$row = phpmkr_fetch_array($rs);

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
		$GLOBALS["x_tipo_negocio"] = $row2["tipo_negocio"];
		$GLOBALS["x_edad"] = $row2["edad"];
		$GLOBALS["x_sexo"] = $row2["sexo"];
		$GLOBALS["x_estado_civil_id"] = $row2["estado_civil_id"];
		$GLOBALS["x_numero_hijos"] = $row2["numero_hijos"];
		$GLOBALS["x_nombre_conyuge"] = $row2["nombre_conyuge"];
		$GLOBALS["x_email"] = $row2["email"];


		$sSql = "select * from direccion where cliente_id = ".$GLOBALS["x_cliente_id"]." and direccion_tipo_id = 1 order by direccion_id desc limit 1";
		$rs3 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row3 = phpmkr_fetch_array($rs3);
		$GLOBALS["x_direccion_id"] = $row3["direccion_id"];
		$GLOBALS["x_calle"] = $row3["calle"];
		$GLOBALS["x_colonia"] = $row3["colonia"];
		$GLOBALS["x_delegacion_id"] = $row3["delegacion_id"];
		$GLOBALS["x_otra_delegacion"] = $row3["otra_delegacion"];
		$GLOBALS["x_entidad"] = $row3["entidad"];
		$GLOBALS["x_codigo_postal"] = $row3["codigo_postal"];
		$GLOBALS["x_ubicacion"] = $row3["ubicacion"];
		$GLOBALS["x_antiguedad"] = $row3["antiguedad"];
		$GLOBALS["x_vivienda_tipo_id"] = $row3["vivienda_tipo_id"];
		$GLOBALS["x_otro_tipo_vivienda"] = $row3["otro_tipo_vivienda"];
		$GLOBALS["x_telefono"] = $row3["telefono"];
		$GLOBALS["x_telefono_secundario"] = $row3["telefono_secundario"];

		$sSql = "select * from direccion where cliente_id = ".$GLOBALS["x_cliente_id"]." and direccion_tipo_id = 2 order by direccion_id desc limit 1";
		$rs4 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row4 = phpmkr_fetch_array($rs4);
		$GLOBALS["x_direccion_id2"] = $row4["direccion_id"];
		$GLOBALS["x_calle2"] = $row4["calle"];
		$GLOBALS["x_colonia2"] = $row4["colonia"];
		$GLOBALS["x_delegacion_id2"] = $row4["delegacion_id"];
		$GLOBALS["x_otra_delegacion2"] = $row4["otra_delegacion"];
		$GLOBALS["x_entidad2"] = $row4["entidad"];
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
		$GLOBALS["x_parentesco_tipo_id_aval"] = $row5["parentesco_tipo_id"];
		$GLOBALS["x_telefono3"] = $row5["telefono"];
		$GLOBALS["x_ingresos_mensuales"] = $row5["ingresos_mensuales"];
		$GLOBALS["x_ocupacion"] = $row5["ocupacion"];


		if($GLOBALS["x_aval_id"] != ""){
			$sSql = "select * from direccion where aval_id = ".$GLOBALS["x_aval_id"]." and direccion_tipo_id = 3";
			$rs6 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$row6 = phpmkr_fetch_array($rs6);
			$GLOBALS["x_direccion_id6"] = $row6["direccion_id"];
			$GLOBALS["x_calle3"] = $row6["calle"];
			$GLOBALS["x_colonia3"] = $row6["colonia"];
			$GLOBALS["x_delegacion_id3"] = $row6["delegacion_id"];
			$GLOBALS["x_otra_delegacion3"] = $row6["otra_delegacion"];
			$GLOBALS["x_entidad3"] = $row6["entidad"];
			$GLOBALS["x_codigo_postal3"] = $row6["codigo_postal"];
			$GLOBALS["x_ubicacion3"] = $row6["ubicacion"];
			$GLOBALS["x_antiguedad3"] = $row6["antiguedad"];
			$GLOBALS["x_vivienda_tipo_id2"] = $row6["vivienda_tipo_id"];
			$GLOBALS["x_otro_tipo_vivienda3"] = $row6["otro_tipo_vivienda"];
			$GLOBALS["x_telefono3"] = $row6["telefono"];
			$GLOBALS["x_telefono_secundario3"] = $row6["telefono_secundario"];
		}else{
			$GLOBALS["x_direccion_id3"] = "";
			$GLOBALS["x_calle3"] = "";
			$GLOBALS["x_colonia3"] = "";
			$GLOBALS["x_delegacion_id3"] = "";
			$GLOBALS["x_otra_delegacion3"] = "";
			$GLOBALS["x_entidad3"] = "";
			$GLOBALS["x_codigo_postal3"] = "";
			$GLOBALS["x_ubicacion3"] = "";
			$GLOBALS["x_antiguedad3"] = "";
			$GLOBALS["x_vivienda_tipo_id2"] = "";
			$GLOBALS["x_otro_tipo_vivienda3"] = "";
			$GLOBALS["x_telefono3"] = "";
			$GLOBALS["x_telefono_secundario3"] = "";
		}


		$sSql = "select * from garantia where solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rs7 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row7 = phpmkr_fetch_array($rs7);
		$GLOBALS["x_garantia_desc"] = $row7["descripcion"];
		$GLOBALS["x_garantia_valor"] = $row7["valor"];

		$sSql = "select * from ingreso where solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rs8 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row8 = phpmkr_fetch_array($rs8);
		$GLOBALS["x_ingreso_id"] = $row8["ingreso_id"];
		$GLOBALS["x_ingresos_negocio"] = $row8["ingresos_negocio"];
		$GLOBALS["x_ingresos_familiar_1"] = $row8["ingresos_familiar_1"];
		$GLOBALS["x_parentesco_tipo_id_ing_1"] = $row8["parentesco_tipo_id"];
		$GLOBALS["x_ingresos_familiar_2"] = $row8["ingresos_familiar_2"];
		$GLOBALS["x_parentesco_tipo_id_ing_2"] = $row8["parentesco_tipo_id2"];
		$GLOBALS["x_otros_ingresos"] = $row8["otros_ingresos"];

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


		$sSql = "SELECT count(*) as gara FROM garantia where solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row = phpmkr_fetch_array($rs);
		$x_gara = $row["gara"];
		phpmkr_free_result($rs);

		if($x_gara > 0){
			$x_contenido_id = 3;
		}else{
			$x_contenido_id = 12;
		}

		$sSql = "select contenido from formato_docto where formato_docto_id = $x_contenido_id";
		$rs10 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row10 = phpmkr_fetch_array($rs10);
		$GLOBALS["x_contenido"] = $row10["contenido"];


	}
	phpmkr_free_result($rs);
	phpmkr_free_result($rs2);
	phpmkr_free_result($rs3);
	phpmkr_free_result($rs4);
	phpmkr_free_result($rs5);
	phpmkr_free_result($rs6);
	phpmkr_free_result($rs7);
	phpmkr_free_result($rs8);
	phpmkr_free_result($rs9);
	phpmkr_free_result($rs10);
	return $bLoadData;
}
?>

