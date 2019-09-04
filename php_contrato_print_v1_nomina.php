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
<title>RECA 1735-140-027143/02-06015-1117</title>







<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1"/>



</head>

<body bgcolor="#FFFFFF">

<script type="text/javascript" src="ew.js"></script>

<p>

<font size="2">

<?php

include("utilerias/datefunc.php");

include("amount2txt.php");





//DATOS GENERALES

$sSql = "SELECT estado_id, descripcion FROM inegi_municipio where municipio_id = $x_delegacion_id and estado_id = ".$x_entidad;

$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query dg: " . phpmkr_error() . '<br>SQL: ' . $sSql);

$row = phpmkr_fetch_array($rs);

$x_delegacion = $row["descripcion"];

$x_entidad_id = $row["estado_id"];

phpmkr_free_result($rs);



$sSql = "SELECT * FROM inegi_estado WHERE estado_id = $x_entidad_id";

$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query dg: " . phpmkr_error() . '<br>SQL: ' . $sSql);

$row = phpmkr_fetch_array($rs);

$x_entidad = $row["descripcion"];

phpmkr_free_result($rs);







$x_direccion = " en la calle ".$x_calle.". Colonia ".$x_colonia.". C.P. ".$x_codigo_postal.". en la Delegacion ".$x_delegacion.", ".$x_entidad;

$x_cliente = strtoupper($x_nombre_completo)." ".strtoupper($x_apellido_paterno)." ".strtoupper($x_apellido_materno);

$x_direccion = strtoupper($x_direccion);

$x_estado = $x_entidad;



//domicilio empresa para caso pyme

if($x_credito_tipo_id == 4){

$sSql = "SELECT estado_id, descripcion FROM inegi_municipio where municipio_id = $x_delegacion_id2";

$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query dg: " . phpmkr_error() . '<br>SQL: ' . $sSql);

$row = phpmkr_fetch_array($rs);

$x_delegacion2 = $row["descripcion"];

$x_entidad_id2 = $row["entidad_id"];

phpmkr_free_result($rs);



$sSql = "SELECT * FROM inegi_estado WHERE estado_id = $x_entidad_id2";

$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query dg: " . phpmkr_error() . '<br>SQL: ' . $sSql);

$row = phpmkr_fetch_array($rs);

$x_entidad2 = $row["descripcion"];

phpmkr_free_result($rs);

}

if(!empty($x_direccion_id2)){

$x_domicilio_empresa = " en la calle ".$x_calle2.". Colonia ".$x_colonia2.". C.P. ".$x_codigo_postal2." en la delagacion ".$x_delegacion2.",". $x_entidad2;

}else{

	$x_domicilio_empresa = "";

	}



$x_solocliente = $x_cliente;



if($x_sexo == 1){

	$x_cliente = "EL C. ".$x_cliente;

}else{

	$x_cliente = "LA C. ".$x_cliente;

}





// el campo plazo tine el valor de numero de pagos ya no tiene el valor de meses o quincenas....tiene un valor entero

//SOLICITUD

$sSql = "SELECT valor FROM plazo where plazo_id = $x_plazo_id";

$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);

$row = phpmkr_fetch_array($rs);

//el valor ahora es directo ya no se necesita selacciona de la base de datos

$x_plazo_valor = $x_plazo_id;

//$x_plazo_valor = $row["valor"];

phpmkr_free_result($rs);



$sSql = "SELECT valor FROM forma_pago where forma_pago_id = $x_forma_pago_id";

$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);

$row = phpmkr_fetch_array($rs);

$x_forma_pago_valor = $row["valor"];

phpmkr_free_result($rs);



$x_importe_letras = covertirNumLetras($x_importe_solicitado);

$x_importe = "$".FormatNumber($x_importe_solicitado,2,0,0,1)." (-$x_importe_letras-) ";





//$5,000.00 (-Cinco mil pesos, 00/100 M.N.-),

$x_penalizacion_letras = covertirNumLetras($x_penalizacion);

$x_penalizacion= "$".FormatNumber($x_penalizacion,2,0,0,1)." ( -$x_penalizacion_letras-) ";



$x_monto_garantia_liquida_letras = covertirNumLetras($x_monto_garantia_liquida);

$x_monto_garantia_liquida= "$".FormatNumber($x_monto_garantia_liquida,2,0,0,1)." ( -$x_monto_garantia_liquida_letras-) ";



$x_comision_letras = covertirNumLetras($x_comision);

$x_comision = "$".FormatNumber($x_comision,2,0,0,1)." ( -$x_comision_letras-) ";



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

		$x_periodicidad_pago = " a la semana transcurrida";



		//$x_numero_plazo = $x_plazo_valor * 4; // ya no se multiplica porque ta trae el valor int

		$x_numero_plazo = $x_plazo_valor;

		$x_plazo = $x_numero_plazo." semanas ";



		//$x_tasa_anual_valor = $x_tasa_valor * 52;

		$x_tasa_anual_valor = $x_tasa_valor ;

		$x_tasa =  $x_tasa_anual_valor / 52;



		break;

	case 2: // CATORCENAL

		$x_forma_pago_plural = "catorcenas";

		$x_forma_pago_singular = "catorcena";

		$x_forma_pago_periodo = "catorcenal";

		$x_forma_pagoperiodo_plural = "catorcenales";

		$x_periodicidad_pago = " a la catorcena transcurrida";



		//$x_numero_plazo = $x_plazo_valor * 2;

		$x_numero_plazo = $x_plazo_valor;

		$x_plazo = $x_numero_plazo." catorcenas ";



		//$x_tasa_anual_valor = $x_tasa_valor * 26;

		$x_tasa_anual_valor = $x_tasa_valor;

		$x_tasa =  $x_tasa_anual_valor / 26;



		break;

	case 3: // MENSUAL

		$x_forma_pago_plural = "meses";

		$x_forma_pago_singular = "mes";

		$x_forma_pago_periodo = "mensual";

		$x_forma_pagoperiodo_plural = "mensuales";

		$x_periodicidad_pago = " al mes transcurrido";



		//$x_numero_plazo = $x_plazo_valor * 1;

		$x_numero_plazo = $x_plazo_valor;

		$x_plazo = $x_numero_plazo." meses ";



		//$x_tasa_anual_valor = $x_tasa_valor * 13.03;

		$x_tasa_anual_valor = $x_tasa_valor;

		$x_tasa =  $x_tasa_anual_valor / 13.03;



		break;

	case 4: // QUINCENAL

		$x_forma_pago_plural = "quincenas";

		$x_forma_pago_singular = "quincena";

		$x_forma_pago_periodo = "quincenal";

		$x_forma_pagoperiodo_plural = "quincenales";

		$x_periodicidad_pago = " a la quincena transcurrida";



		//$x_numero_plazo = $x_plazo_valor * 2;

		$x_numero_plazo = $x_plazo_valor;

		$x_plazo = $x_numero_plazo." quincenas ";



		//$x_tasa_anual_valor = $x_tasa_valor * 24.33;

		$x_tasa_anual_valor = $x_tasa_valor;

		$x_tasa =  $x_tasa_anual_valor / 24.33;



		break;



}

//echo "tasa ".$x_tasa."<br>";



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



//$x_tasa_letras = covertirPorcientoLetras($x_tasa);

//$x_tasa = FormatNumber($x_tasa,$x_tasa_decimales,0,0,1)."%(-".trim($x_tasa_letras)."-) ";



$x_tt = explode($x_tasa, "."); // separamos la cadena por el punto decimal

 $x_parte_entera = $x_tt[0]; // antes del punto

 $x_cont_enteros = strlen($x_parte_entera); // digitos de la parte entera

 $x_digitos_c = $x_cont_enteros + 3; // los digitos de la parte entera maas el punto mas dos decimales(+3);





$x_parte_dos = substr($x_tasa, 0,  $x_digitos_c);  // abcd





$x_tasa_letras = covertirPorcientoLetras($x_tasa);

#echo "la tasa es antes de redondear".$x_tasa_valor."<br>";

#$x_tasa = FormatNumber($x_tasa_valor,$x_tasa_decimales,0,0,1)."%(-".trim($x_tasa_letras)."-) ";

$x_tasa = " ".$x_parte_dos."%(-".trim($x_tasa_letras)."-) ";





$x_tasa_anual_letras = covertirPorcientoLetras($x_tasa_anual_valor);

$x_anual_tasa = FormatNumber($x_tasa_anual_valor,$x_tasa_anual_decimales,0,0,1)."%(-".trim($x_tasa_anual_letras)."-) ";

/**** nuevo marcos *****/
$x_tasa_anual_doble_letras = covertirPorcientoLetras(($x_tasa_anual_valor * 2));

$x_anual_doble_tasa = FormatNumber(($x_tasa_anual_valor * 2),($x_tasa_anual_decimales),0,0,1)."%(-".trim($x_tasa_anual_doble_letras)."-) ";
/**** fin ****/


//1.98% (-uno punto noventa y ocho porciento-),



//$x_tasa_moratoria_letras = covertirPorcientoLetras($x_tasa_moratoria_valor);

//$x_moratorios = FormatNumber($x_tasa_moratoria_valor,$x_tasa_moratoria_decimales,0,0,1)."%(-".trim($x_tasa_moratoria_letras)."-) ";



$x_tasa_moratoria_letras = covertirNumLetras($x_tasa_moratoria_valor);

$x_moratorios = "$".FormatNumber($x_tasa_moratoria_valor,2,0,0,1)." (- $x_tasa_moratoria_letras -) ";

//1.98% (-uno punto noventa y ocho porciento-),



//$x_fecha_contrato = FechaLetras($x_fecha_otorgamiento_valor);



$x_fecha_contrato = FechaLetras(strtotime(ConvertDateToMysqlFormat($x_fecha_otorgamiento_valor)));


//die(var_dump($x_fecha_contrato));

$x_fecha_vencimiento = FechaLetras(strtotime(ConvertDateToMysqlFormat($x_fecha_vencimiento_valor)));



$x_fecha_ultimo_pago =  FechaLetras(strtotime(ConvertDateToMysqlFormat($x_fecha_ultimo_pago)));

$x_fech_penultimo_ven = FechaLetras(strtotime(ConvertDateToMysqlFormat($x_fech_penultimo_ven)));









//DATOS DEL GRUPO SI ES EL CASO

//nombre del representante

if(!empty($x_representante_cliente_id)){

	//hay un representante del grupo seleccionamos sus datos...

	$sqlRepresentante = "SELECT * FROM 	cliente WHERE cliente_id = $x_representante_cliente_id";

	$responseRep = phpmkr_query($sqlRepresentante,$conn) or die("erroe en el representante".phpmkr_error()."sql".$sqlRepresentante);

	$rowRep = phpmkr_fetch_array($responseRep);

	$x_nombre_completo_rep =   $rowRep["nombre_completo"]. " ";

	$x_nombre_completo_rep .=   $rowRep["apellido_paterno"]." ";

	$x_nombre_completo_rep .=   $rowRep["apellido_materno"];

	$x_sexo_rep =   $rowRep["sexo"];



	phpmkr_free_result($rowRep);



	if($x_sexo_rep == 1){

	$x_representate_grupo = " a el  C. ".$x_nombre_completo_rep;

}else{

	$x_representate_grupo = " a la  C. ".$x_nombre_completo_rep;

}





	}





//INTEGRANTES DEL GRUPO

if($GLOBALS["x_creditoSolidario_id"]>0){

	$x_lista_acreditados =	"<table  align='center' >";





	//$x_lista_acreditados .= "<tr>";

	$x_cont_int = 1; //$GLOBALS["x_cliente_id_$x_cont_g"]

	$x_conta_nt = 1;

	while ($x_cont_int <= 10){

		$x_cliente_id_act = "x_cliente_id_$x_cont_int";

		$x_cliente_id_act = $$x_cliente_id_act;



		if(($x_cliente_id_act != "newone") && ($x_cliente_id_act != "vacio") && ($x_cliente_id_act != "NEWONE") && ($x_cliente_id_act != "VACIO") ){



		$sqlintegrante = "SELECT * FROM 	cliente WHERE cliente_id = $x_cliente_id_act";

		$responseInt = phpmkr_query($sqlintegrante,$conn) or die("erroe en el representante".phpmkr_error()."sql".$sqlRepresentante);

		$rowInT = phpmkr_fetch_array($responseInt);

		$x_nombre_completo_int =   $rowInT["nombre_completo"]. " ";

		$x_nombre_completo_int .=   $rowInT["apellido_paterno"]." ";

		$x_nombre_completo_int .=   $rowInT["apellido_materno"];



		phpmkr_free_result($rowInT);





		$x_lista_acreditados .="<tr><td align=\"center\" >&nbsp;</td></tr>";

		$x_lista_acreditados .="<tr><td>&nbsp;</td></tr>";

		$x_lista_acreditados .="<tr><td align=\"center\">______________________________</td></tr>";

		$x_lista_acreditados .= "<tr><td align=\"center\"><span style=\"line-height: 115%; font-family: 'Calibri','sans-serif'; font-size: 11pt; mso-fareast-font-family: Calibri; mso-bidi-font-family: 'Times New Roman'; mso-fareast-language: EN-US; mso-ansi-language: ES-MX; mso-bidi-language: AR-SA\"> $x_nombre_completo_int </td></tr>";

		$x_conta_nt ++;

		}// FIN IF DIERENTE DE TEXTO

		$x_cont_int++;



		}



	$x_lista_acreditados .= "</table>";



	}





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

		$x_importe_tab2 +=  $x_iva_tab ;



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

// se cambia el query porque se agrego un nueva tabla para guardar todos los campos del aval ya se solo seria neceario en ocoasiones.

// la nueva tabla se llama datos_aval y se relaciona con el campo solicitud_id

/*$sSql = "SELECT aval_id, nombre_completo, apellido_paterno, apellido_materno FROM aval where solicitud_id = $x_solicitud_id";

$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);

$row = phpmkr_fetch_array($rs);

$x_aval_id = $row["aval_id"];

$x_aval = strtoupper($row["nombre_completo"]." ".$row["apellido_paterno"]." ".$row["apellido_materno"]);

phpmkr_free_result($rs);*/



$sSql = "SELECT datos_aval_id, sexo, nombre_completo, apellido_paterno, apellido_materno FROM datos_aval where solicitud_id = $x_solicitud_id";

$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);

$row = phpmkr_fetch_array($rs);

$x_aval_id = $row["datos_aval_id"];

$x_sexo_aval =$row["sexo"];





// si es mujer o si  es hombre



if($x_sexo_aval == 1){

	$x_aval = "";

	$x_aval = "EL C. ";

}else{

	$x_aval = "";

	$x_aval = "LA C. ";

}



$x_aval .= strtoupper($row["nombre_completo"]." ".$row["apellido_paterno"]." ".$row["apellido_materno"]);

phpmkr_free_result($rs);





//AVAL DIR

// la direccion del aval tambien se guarada en la tabla anterior datos_aval

if(($x_aval_id > 0) && (strlen(trim($x_aval)) > 0) ){

/*$sSql = "SELECT * FROM direccion where direccion_tipo_id = 3 and aval_id = $x_aval_id order by direccion_id desc limit 1";

$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);*/



$sSql = "SELECT * FROM datos_aval where  solicitud_id = $x_solicitud_id";

$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);

$row = phpmkr_fetch_array($rs);

//$x_aval_direccion_id = $row["direccion_id"];

$x_aval_calle = $row["calle"];

$x_aval_colonia = $row["colonia"];

$x_aval_delegacion_id = $row["delegacion"];

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



if(!empty($x_aval_delegacion_id)){

$sSql = "SELECT estado_id, descripcion FROM inegi_municipio where municipio_id = $x_aval_delegacion_id";

$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query aval: " . phpmkr_error() . '<br>SQL: ' . $sSql);

$row = phpmkr_fetch_array($rs);

$x_aval_delegacion = $row["descripcion"];

$x_aval_entidad_id = $row["entidad_id"];

phpmkr_free_result($rs);

}



if(!empty($x_aval_entidad_id)){

	$sSql = "SELECT * FROM inegi_estado WHERE estado_id = $x_aval_entidad_id";

	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query aval $x_aval_id: " . phpmkr_error() . '<br>SQL: ' . $sSql);

	$row = phpmkr_fetch_array($rs);

	$x_aval_entidad = $row["descripcion"];

	phpmkr_free_result($rs);

}else{

	$x_aval_entidad = " ";

}



$x_diraval = " en la calle ".$x_aval_calle.". Colonia ".$x_aval_colonia.". C.P. ".$x_aval_codigo_postal.", en la Delegacion ".$x_aval_delegacion.", ".

$x_aval_entidad;

$x_diraval = strtoupper($x_diraval);



}else{



$x_diraval = "";



}



//echo "dir aval".$x_diraval."<br>";



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

                  <td height='59' align='left' valign='middle' bgcolor='#F3F3F3'><strong>Monto del Cr�dito:</strong></td>

                  <td align='left' valign='middle'>&nbsp;</td>

                  <td align='left' valign='middle'>$x_importe</td>

                </tr>



                <tr>

                  <td align='left' valign='middle'>&nbsp;</td>

                  <td align='left' valign='middle'>&nbsp;</td>

                  <td align='left' valign='middle'>&nbsp;</td>

                </tr>

                <tr>

                  <td height='60' align='left' valign='middle' bgcolor='#F3F3F3'><strong>Tasa de inter�s:</strong></td>

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

                  <td align='left' valign='middle' bgcolor='#F3F3F3'><strong>Datos de La unidad especializada de atenci&oacute;n a usuarios:</strong></td>

                  <td align='left' valign='middle'>&nbsp;</td>

                  <td align='left' valign='middle'>No. telef&oacute;nico 1993 3278, &oacute; para atenci&oacute;n personalizada en el domicilio ubicado en Av. Revoluci&oacute;n 1909 piso 8 col. San Angel. Delegaci&oacute;n Alvaro Obregon D.F.</td>

                </tr>

              </table>";





$x_initabla = "<div style=\"page-break-after: always;\">$x_initabla</div>";

//$x_initabla = "<div style=\"page-break-after: always;height:0; line-height:0;\">$x_initabla</div>";

//die(var_dump($x_tasa));
//die(var_dump($x_contenido));

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

$x_contenido = str_replace("\$x_domicilio",htmlentities($x_direccion),$x_contenido);

$x_contenido = str_replace("\$x_diraval",htmlentities($x_diraval),$x_contenido);

$x_contenido = str_replace("\$x_dir_aval",htmlentities($x_diraval),$x_contenido);



$x_contenido = str_replace("\$x_forma_pagoperiodo_plural",$x_forma_pagoperiodo_plural,$x_contenido);

$x_contenido = str_replace("\$x_forma_pago_plural",$x_forma_pago_plural,$x_contenido);

$x_contenido = str_replace("\$x_forma_pago_prural",$x_forma_pago_plural,$x_contenido);

$x_contenido = str_replace("\$x_forma_pago_singular",$x_forma_pago_singular,$x_contenido);

$x_contenido = str_replace("\$x_forma_pago_periodo",$x_forma_pago_periodo,$x_contenido);

$x_contenido = str_replace("\$x_plazo",$x_plazo,$x_contenido);



$x_contenido = str_replace("\$x_importe",$x_importe,$x_contenido);



$x_contenido = str_replace("\$x_moratorios",$x_moratorios,$x_contenido);

$x_contenido = str_replace("\$x_tasa",$x_tasa,$x_contenido);



$x_contenido = str_replace("\$x_anual_tasa",$x_anual_tasa,$x_contenido);

///// marcos agrega
$x_contenido = str_replace("\$x_anual_doble_tasa",$x_anual_doble_tasa,$x_contenido);

$x_contenido = str_replace("\$x_fecha_contrato",$x_fecha_contrato,$x_contenido);

$x_contenido = str_replace("\$x_fecha_vencimiento",$x_fecha_vencimiento,$x_contenido);



//grupo

$x_contenido = str_replace("\$x_nombre_grupo",$x_nombre_grupo,$x_contenido);

$x_contenido = str_replace("\$x_representate_grupo",$x_nombre_completo_rep,$x_contenido);

$x_contenido = str_replace("\$x_lista_acreditados",$x_lista_acreditados,$x_contenido);

$x_contenido = str_replace("\$x_estado",$x_estado,$x_contenido);



//PYME

$x_contenido = str_replace("\$x_nombre_empresa",$x_nombre_empresa,$x_contenido);

$x_contenido = str_replace("\$x_domicilio_empresa",$x_domicilio_empresa,$x_contenido);



//conteditdo de ccreditos con penalizacoes y garabtia liquida

$x_contenido = str_replace("\$x_penalizacion",$x_penalizacion,$x_contenido);

$x_contenido = str_replace("\$x_gar_liquida",$x_monto_garantia_liquida,$x_contenido);

$x_contenido = str_replace("\$x_fecha_ultimo_pago",$x_fecha_ultimo_pago,$x_contenido);

$x_contenido = str_replace("\$x_estado",$x_estado_nombre,$x_contenido);

$x_contenido = str_replace("\$x_estado",$x_estado_nombre,$x_contenido);

$x_contenido = str_replace("\$x_comision",$x_comision,$x_contenido);

$x_contenido = str_replace("\$x_no_genera_com",$x_no_genera_com,$x_contenido);

$x_contenido = str_replace("\$x_numpagos",$x_numpagos,$x_contenido);



$x_contenido = str_replace("\$x_fech_penultimo_ven",$x_fech_penultimo_ven,$x_contenido);

$x_contenido = str_replace("\$x_no_pagos_gar_liquida",$x_no_pagos_gar_liquida,$x_contenido);



$x_contenido = str_replace("\$x_perio_pact",$x_forma_pago_valor,$x_contenido);


$x_contenido = str_replace("\$x_periodicidad_pago",$x_periodicidad_pago,$x_contenido);

//$x_periodicidad_pago





//echo htmlspecialchars_decode($x_contenido);

echo utf8_decode($x_contenido);



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

		$x_valor_credito_tipo_id =  $row["credito_tipo_id"];

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



		//agregados por lo nuevos formatos

		$GLOBALS["x_nombre_grupo"] = $row["grupo_nombre"];



		// sleccionamos los datos de garantia liaquida y de penalicacion del credito



		$sSql = "SELECT * FROM credito  WHERE solicitud_id = ".$GLOBALS["x_solicitud_id"]." ";

		$rs = phpmkr_query($sSql, $conn) or die ("Error al selccionar los datos del credito". phpmkr_error()."sql;".$sSql);

		$rowC = phpmkr_fetch_array($rs);

		$GLOBALS["x_penalizacion"] = $rowC["penalizacion"];

		$GLOBALS["x_garantia_liquida"] = $rowC["garantia_liquida"];

		$GLOBALS["x_credito_id"] = $rowC["credito_id"];











		if($GLOBALS["x_forma_pago_id"] == 1){

			// es semanal

			$GLOBALS["x_no_pagos_gar_liquida"]=" a los dos �ltimos pagos del cr�dito";

			$GLOBALS["x_fech_penultimo_ven"]= "";

			// seleleccionamos la fecha del penultimo vencimiento

			$sqlFVVU = "SELECT * FROM `vencimiento` WHERE `credito_id` = ".$GLOBALS["x_credito_id"]." order by vencimiento_num DESC LIMIT 2,1 ";

			$GLOBALS["x_fech_penultimo_ven"] = " del antepen�ltimo pago ";



			}else{

				// es mensual, quincenal o catorcenal

						$GLOBALS["x_no_pagos_gar_liquida"]=" al �ltimo pago del cr�dito";

				$GLOBALS["x_fech_penultimo_ven"]= "";

				//seleccionamos la fecha del penultimo vencimiento

				$sqlFVVU = "SELECT * FROM `vencimiento` WHERE `credito_id` = ".$GLOBALS["x_credito_id"]." order by vencimiento_num DESC LIMIT 1,1 ";

				$GLOBALS["x_fech_penultimo_ven"] = " del pen�ltimo pago ";







				}





		$rsCid = phpmkr_query($sqlFVVU,$conn) or die ("erro al seleccionar la fecha del ultimo vencimeinto".phpmkr_error()."sql:".$sqlFVVU);

		$rowCid = phpmkr_fetch_arraY($rsCid);

		$GLOBALS["x_fech_penultimo_ven"] = $rowCid["fecha_vencimiento"];









		if ($GLOBALS["x_garantia_liquida"] == 1){

			// seleccionamos los datos de la garantia lisquida

			$sqlG = "SELECT * FROM garantia_liquida WHERE credito_id = ".$GLOBALS["x_credito_id"]."";

			$rsG = phpmkr_query($sqlG,$conn) or die("Error al seleccionar los datos de la garantia".phpmkr_error()."Sql:".$sqlG);

			$rowG = phpmkr_fetch_array($rsG);

			$GLOBALS["x_monto_garantia_liquida"]= $rowG["monto"];

			// entonces formato docto = 26



			}else{

				$GLOBALS["x_garantia_liquida"] = 0;

				}





		// calculamos la comsion del credito

		// esta depende  de la forma de pagao del credito

		// si es semanal la comsion sera de  2 pagos para el caso de semanal y de 1 pago para el caso de los demas tipos de pago



		// selecionamos los datos del vencimiento

		$sqlV = "SELECT * FROM vencimiento WHERE credito_id = ".$GLOBALS["x_credito_id"]." order by vencimiento_num DESC limit 0,1";

		$rsV = phpmkr_query($sqlV, $conn) or die("Error al selcceionar los datos del vencimeinto". phpmkr_error()."sql;". $sqlV);

		$rowV = phpmkr_fetch_array($rsV);

		$GLOBALS["x_monto_vencimiento"] = $rowV["total_venc"];

		$GLOBALS["x_fecha_ultimo_pago"] = $rowV["fecha_vencimiento"];



		if($GLOBALS["x_forma_pago_id"] == 1){

			// es semanal entonces la comsion es del doble de un vencimiento

			$GLOBALS["x_comision"] =  $GLOBALS["x_monto_vencimiento"] * 2;

			$GLOBALS["x_no_genera_com"] = 3;

			}else{

				$GLOBALS["x_comision"] =  $GLOBALS["x_monto_vencimiento"];

					$GLOBALS["x_no_genera_com"] = 2;

				}





		//INTEGRANTES DEL GRUPO

		$x_soli_id =  $GLOBALS["x_solicitud_id"];

		if($GLOBALS["x_credito_tipo_id"] == 2){

			// ES UN CREDITO SOLIDARIO



			$sqlGrupo = "SELECT * FROM creditosolidario WHERE  solicitud_id = $x_soli_id";

			$responseGrupo = phpmkr_query($sqlGrupo,$conn) or die ("error al ejecutar query grupo".phpmkr_error()."sql: ".$sqlGrupo);

			$rowGrupo = phpmkr_fetch_array($responseGrupo);

			$GLOBALS["x_creditoSolidario_id"] =  $rowGrupo["creditoSolidario_id"];

			$GLOBALS["x_nombre_grupo"] = $rowGrupo["nombre_grupo"];



			$x_cont_g = 1;

			while($x_cont_g <= 10){



				$GLOBALS["x_integrante_$x_cont_g"] = $rowGrupo["integrante_$x_cont_g"];

				$GLOBALS["x_monto_$x_cont_g"] =$rowGrupo["monto_$x_cont_g"];

				$GLOBALS["x_rol_integrante_$x_cont_g"] = $rowGrupo["rol_integrante_$x_cont_g"];

				$GLOBALS["x_cliente_id_$x_cont_g"] = $rowGrupo["cliente_id_$x_cont_g"];



				//BUSCO AL REPRESENTANTE DEL GRUPO

				if($GLOBALS["x_rol_integrante_$x_cont_g"] == 1){

					$GLOBALS["$x_representate_grupo"] = $rowGrupo["integrante_$x_cont_g"];

					$GLOBALS["x_representante_cliente_id"] =  $rowGrupo["cliente_id_$x_cont_g"];

					}



				$x_cont_g++;

				}







			phpmkr_free_result($rowGrupo);



			}









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





		// nombre de la empresa si es un credito PYME

		$x_clie_id = $GLOBALS["x_cliente_id"];

		if($GLOBALS["x_credito_tipo_id"] == 4){

			$sqlNombrePyme = "SELECT  giro_negocio  FROM formatopyme WHERE cliente_id =  $x_clie_id";

			$responsePyme = phpmkr_query($sqlNombrePyme,$conn) or die("errro en nombre pyme:".phpmkr_error()."sql".$sqlNombrePyme);

			$rowPyme = phpmkr_fetch_array($responsePyme);

			$GLOBALS["x_nombre_empresa"] = $rowPyme["giro_negocio"];

			phpmkr_free_result($rowPyme);



			}







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



		// seleccionamos los datos del estdo



		if(!empty($GLOBALS["x_entidad"])){

		$sqlEdo = "SELECT * FROM inegi_estado WHERE estado_id = ".$GLOBALS["x_entidad"] ." ";

		$rsEdo = phpmkr_query($sqlEdo,$conn) or die("Error al seleccionar el estado".phpmkr_error()."sql".$sqlEdo);

		$rowEdo = phpmkr_fetch_array($rsEdo);

		$GLOBALS["x_estado_nombre"] = $rowEdo["nombre"];

		}else{

			$GLOBALS["x_estado_nombre"]= "";

			}

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



	/*	if($x_gara > 0){

			$x_contenido_id = 3;

		}else{

			$x_contenido_id = 12;

		}*/



		$sSql = "SELECT nombre_completo AS avales FROM datos_aval WHERE solicitud_id = ".$GLOBALS["x_solicitud_id"];

		$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);

		$row = phpmkr_fetch_array($rs);

		$x_aval = $row["avales"];

		if( strlen(trim($x_aval)) > 0  ){

			$x_aval = 1;

			}



			#$x_gara = 0;

			#$x_aval = 1;

		//echo "aval".$x_aval."<br>";

		#$x_aval = 0;

		//echo "penalizacion".$GLOBALS["x_penalizacion"]."<br>";

		#$GLOBALS["x_penalizacion"] = 0;

		#$GLOBALS["x_garantia_liquida"] = 0;

		#$x_aval = 1;

		$GLOBALS["x_garantia"] = $x_gara;



		#echo "garantia".$GLOBALS["x_garantia"]."<br>";



		if($GLOBALS["x_solicitud_id"] > 5000){

			#echo "se cargan los formatos nuevos";

			if($GLOBALS["x_penalizacion"] > 0){

				if($GLOBALS["x_penalizacion"] > 0 && $GLOBALS["x_garantia_liquida"] > 0 && $x_aval > 0){

					//CONTRATO GAR LIQ, PENALIZACIO Y COMISION CON AVAL.

					$x_contenido_id = 31;

					}else if($GLOBALS["x_penalizacion"] > 0 && $GLOBALS["x_garantia_liquida"] > 0 && $x_aval == 0){

						//CONTRATO GAR LIQ, PENALIZACIO Y COMISION SIN AVAL.

					$x_contenido_id = 30;

						}else if($GLOBALS["x_penalizacion"] > 0 && $GLOBALS["x_garantia_liquida"] == 0 && $x_aval > 0 && $GLOBALS["x_garantia"] == 0){

							//CONTRATO SIN GAR LIQ, CON PENALIZACIO Y COM Y AVAL

							$x_contenido_id = 33;

							}else if ($GLOBALS["x_penalizacion"] > 0 && $GLOBALS["x_garantia_liquida"] == 0 && $x_aval == 0 && $GLOBALS["x_garantia"] == 0){

								//CONTRATO SIN GAR LIQ, CON PENALIZACION Y COMISION

								$x_contenido_id = 32;

								}else if($GLOBALS["x_penalizacion"] > 0 && $GLOBALS["x_garantia_liquida"] == 0 && $GLOBALS["x_garantia"] > 0 && $x_aval > 0){

									//CONTRATO CON GAR PREN, PENA Y COM Y AVAL

									$x_contenido_id = 37;

									}else if($GLOBALS["x_penalizacion"] > 0 && $GLOBALS["x_garantia"] > 0 && $x_aval == 0){

										 	//CONTRATO CON GAR PREN, PENALIZACION Y COMISION

											 $x_contenido_id = 36;

										}

				}else{

					// son con moratorio

					if($x_aval > 0){

						//CONTRATO SIN GAR LIQ CON MORA DIARIA CON AVAL

						$x_contenido_id = 35;

						}else{

							//CONTRATO SIN GAR LIQ CON MORA DIARIA

							$x_contenido_id = 34;

							}

					}









			}else{

				#se cargan los formatos viejitos



				if(($x_gara > 0) && ($x_aval>0) ){

					// el tipo de contrato es el de INTERES GARANTIA AVAL

					//16 contrato simple con interes, con garantia y aval

					$x_contenido_id = 16;



					}else if($x_gara > 0){

						//14 Contrato credito simple con interes con garantia

						$x_contenido_id = 14;

						}else if(($x_aval>0)){

							//15 Contarto simple con interres con aval

							$x_contenido_id = 15;

							}else{

								//17 Contrato simple con interes

								$x_contenido_id = 17;

								}



				}





		#echo $x_contenido_id;



	//	if(!empty($GLOBALS["x_penalizacion"]) && $GLOBALS["x_penalizacion"] > 0){

			#1.-Tiene garantia liquida = formato 26

            #2.-con garantia liquida y aval = formato

	//		if($GLOBALS["x_monto_garantia_liquida"] > 0 && $x_aval>0){

				#GARATIA LIQUIDA Y AVAL

	//			$x_contenido_id = 27;

	//			}else if($GLOBALS["x_monto_garantia_liquida"] > 0 && $x_aval < 1){

					#solo tiene garatia liquida

	//					$x_contenido_id = 26;

	//				}/*else if(){

						#no tiene gartia liquida ni aval

						// verificar si tendra de otro tipo de garanbtia



	//					}*/



			//TIENE GARANTIA NORMAL, PERO TIENE LLENO EL CAMPO DE PENALIZACION

	//		if(($x_gara > 0) && ($x_aval>0) ){

					// el tipo de contrato es el de INTERES GARANTIA AVAL

					//16 contrato simple con interes, con garantia y aval

	//				$x_contenido_id = 29;



	//				}else if($x_gara > 0){

						//14 Contrato credito simple con interes con garantia

	//					$x_contenido_id = 28;

	//					}else if(($x_aval>0)){

							//15 Contarto simple con interres con aval

	//						$x_contenido_id = 15;

	//						}else{

								//17 Contrato simple con interes

	//							$x_contenido_id = 17;

	//							}









	//		}else{

	//	if($x_valor_credito_tipo_id == 1){

			// el tipo de credito es PERSONAL

	//			if(($x_gara > 0) && ($x_aval>0) ){

					// el tipo de contrato es el de INTERES GARANTIA AVAL

					//16 contrato simple con interes, con garantia y aval

	//				$x_contenido_id = 16;



	//				}else if($x_gara > 0){

						//14 Contrato credito simple con interes con garantia

	//					$x_contenido_id = 14;

	//					}else if(($x_aval>0)){

							//15 Contarto simple con interres con aval

	//						$x_contenido_id = 15;

	//						}else{

								//17 Contrato simple con interes

	//							$x_contenido_id = 17;

	//							}





	//		}else if($x_valor_credito_tipo_id == 2){

				//el tipo de credito es SOLIDARIO

				//24 Contrato Solidario

	//			$x_contenido_id = 24;

	//			}else if($x_valor_credito_tipo_id == 3){

					//EL TIP� DE CREDITO ES MAQUINARIA

					// NO TIENE GARANTIA...PERO PUEDE TENER AVAL

	//				if(($x_aval>0)){

						//15 Contarto simple con interres con aval

	//					$x_contenido_id = 15;

	//					}else{

							// no tiene aval

							//17 Contrato simple con interes

	//						$x_contenido_id = 17;

	//						}





	//				}else if($x_valor_credito_tipo_id == 4){

						//credito PYME

						//13 Contrato PYME

	//					$x_contenido_id = 13;

	//					}



	//		}// penalizaciones



















		//$x_contenido_id = 30;

		$sSql = "select contenido from formato_docto where formato_docto_id = 58";
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
