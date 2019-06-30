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
<?php include ("utilerias/datefunc.php") ?>
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
<title>Financiera CRECE</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">td img {display: block;}</style>
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
<link href="pagare_css.css" rel="stylesheet" type="text/css" media="print" />
</head>
<body bgcolor="#FFFFFF">
<script type="text/javascript" src="ew.js"></script>
<p>
<font size="2">
<?php 

include("amount2txt.php");


//DATOS GENERALES
$sSql = "SELECT descripcion FROM delegacion where delegacion_id = $x_delegacion_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_delegacion = $row["descripcion"];
phpmkr_free_result($rs);

$x_direccion = htmlentities($x_calle)." ".htmlentities($x_colonia)." C.P. ".$x_codigo_postal." en la Delegacion ".htmlentities($x_delegacion).", ".htmlentities($x_entidad);
$x_cliente = strtoupper(htmlentities($x_nombre_completo))." ".strtoupper(htmlentities($x_apellido_paterno))." ".strtoupper(htmlentities($x_apellido_materno));

if($x_sexo == 1){
	$x_cliente = "ESTIMADO ".$x_cliente;
}else{
	$x_cliente = "ESTIMADA ".$x_cliente;
}
$x_direccion = strtoupper($x_direccion);

//DATOS GENERALES
$sSql = "SELECT entidad_id, descripcion FROM delegacion where delegacion_id = $x_delegacion_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query dg: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_delegacion = $row["descripcion"];
$x_entidad_id = $row["entidad_id"];
phpmkr_free_result($rs);


if(!empty($x_entidad_id)){
$sSql = "SELECT nombre FROM entidad where entidad_id = $x_entidad_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query dg: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_entidad = $row["nombre"];
phpmkr_free_result($rs);
}


$x_direccion = " en la calle ".$x_calle.". Colonia ".$x_colonia.". C.P. ".$x_codigo_postal.". en la Delegacion ".$x_delegacion.", ".$x_entidad;
//$x_cliente = strtoupper($x_nombre_completo)." ".strtoupper($x_apellido_paterno)." ".strtoupper($x_apellido_materno);
$x_direccion = strtoupper($x_direccion);
$x_estado = $x_entidad;


//SOLICITUD
$sSql = "SELECT valor FROM plazo where plazo_id = $x_plazo_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
//$x_plazo_valor = $row["valor"];
$x_plazo_valor =  $x_plazo_id;
phpmkr_free_result($rs);

//CREDITO
$sSql = "SELECT credito.tarjeta_num, fecha_vencimiento, referencia_pago, tasa, tasa_moratoria, fecha_otrogamiento, credito.credito_id FROM credito where solicitud_id = $x_solicitud_id";

#echo "credito sql".$sSql."<br>";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_credito_id = $row["credito_id"];
#echo "<br>".$x_credito_id."<br>";
$x_cheque_valor = $row["referencia"];
$x_tasa_valor = $row["tasa"];
$x_tasa_moratoria_valor = $row["tasa_moratoria"];
$x_fecha_otorgamiento_valor = $row["fecha_otrogamiento"];
$x_fecha_vencimiento_valor = $row["fecha_vencimiento"];
$x_tarjeta_numero = $row["tarjeta_num"];

phpmkr_free_result($rs);


$sSql = "SELECT valor FROM forma_pago where forma_pago_id = $x_forma_pago_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_forma_pago_valor = $row["valor"];
phpmkr_free_result($rs);

switch ($x_forma_pago_id)
{
	case 1: // SEMANAL
		$x_forma_pago_plural = "semanas";
		$x_forma_pago_singular = "semana";
		$x_forma_pago_periodo = "semanal";
		
		//$x_numero_plazo = $x_plazo_valor * 4;
		$x_numero_plazo = $x_plazo_valor ;
		$x_plazo = $x_numero_plazo." semanas ";		
		
	//	$x_tasa_anual_valor = $x_tasa_valor * 52;
		
		break;
	case 2: // CATORCENAL
		$x_forma_pago_plural = "catorcenas";
		$x_forma_pago_singular = "catorcena";
		$x_forma_pago_periodo = "catorcenal";

		//$x_numero_plazo = $x_plazo_valor * 2;
		$x_numero_plazo = $x_plazo_valor;
		$x_plazo = $x_numero_plazo." catorcenas ";		
		
//		$x_tasa_anual_valor = $x_tasa_valor * 26;		

		break;
	case 3: // MENSUAL
		$x_forma_pago_plural = "meses";
		$x_forma_pago_singular = "mes";
		$x_forma_pago_periodo = "mensual";

		//$x_numero_plazo = $x_plazo_valor * 1;
		$x_numero_plazo = $x_plazo_valor;
		$x_plazo = $x_numero_plazo." meses ";		
		
		//$x_tasa_anual_valor = $x_tasa_valor * 13.03;		
	
		break;
	case 4: // QUINCENAL
		$x_forma_pago_plural = "quincenas";
		$x_forma_pago_singular = "quincena";
		$x_forma_pago_periodo = "quincenal";

		//$x_numero_plazo = $x_plazo_valor * 2;
		$x_numero_plazo = $x_plazo_valor ;
		$x_plazo = $x_numero_plazo." quincenas ";		
		
	//	$x_tasa_anual_valor = $x_tasa_valor * 24.33;
		
		break;
		
}

#echo "dias venc".$x_total_dias_vencidos."<br>";
$x_total_gral_sin_tope = ($x_total_dias_vencidos * 10) + $x_condonado;
#echo "sin tope".$x_total_gral_sin_tope."<br>";
$x_total_gral_con_tope = "$".FormatNumber($_total_faltante,2,0,0,1);
$x_total_gral_sin_tope = "$".FormatNumber($x_total_gral_sin_tope,2,0,0,1);
$x_capital_ordinario = "$".FormatNumber($x_capital_ordinario,2,0,0,1);


$x_importe_letras = covertirNumLetras($x_importe_solicitado);
$x_importe = "$".FormatNumber($x_importe_solicitado,2,0,0,1)." (-$x_importe_letras-) ";
//$5,000.00 (-Cinco mil pesos, 00/100 M.N.-), 
//$x_total_gral_sin_tope_letras = covertirNumLetras($x_total_gral_sin_tope);
$x_total_gral_sin_tope = "$".FormatNumber($x_total_gral_sin_tope,2,0,0,1);

//$x_total_gral_con_tope_letras = covertirNumLetras($x_total_gral_con_tope);
$x_total_gral_con_tope = "$".FormatNumber($x_total_gral_con_tope,2,0,0,1);

//$x_capital_ordinario_letras = covertirNumLetras($x_capital_ordinario);
$x_capital_ordinario = "$".FormatNumber($x_capital_ordinario,2,0,0,1);

$x_hoy = date("Y-m-d");
$nuevafecha = strtotime ( '+10 day' , strtotime ( $x_hoy ) ) ;
$nuevafecha = date ( 'Y-m-d' , $nuevafecha );
#echo "nueva fecha ".$nuevafecha;

$x_fecha_valida = FechaLetras(strtotime(ConvertDateToMysqlFormat($nuevafecha)));


//CREDITO
$sSql = "SELECT credito.credito_id, credito.tarjeta_num, fecha_vencimiento, referencia_pago, tasa, tasa_moratoria, fecha_otrogamiento FROM credito where solicitud_id = $x_solicitud_id AND credito_status_id = 1";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_cheque_valor = $row["referencia"];
$x_tasa_valor = $row["tasa"];
$x_tasa_moratoria_valor = $row["tasa_moratoria"];
$x_fecha_otorgamiento_valor = $row["fecha_otrogamiento"];
$x_fecha_vencimiento_valor = $row["fecha_vencimiento"];
$x_tarjeta_numero = $row["tarjeta_num"];
//$x_credito_id = $row["credito_id"];
phpmkr_free_result($rs);

$x_cheque = "-$x_cheque_valor-";

// REQUERIDO PARA LA TABLA CON TAS AL 60%

$x_tasa_nueva = 60;



//calculo del credito


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


// tasa valor la tasa que se registra es la tasa anual ahora se debe dividir entre la forma de pago 
// mensual queincenal semananal. catorcenal.

switch ($x_forma_pago_id)
{
	case 1: // SEMANAL
		$x_forma_pago_plural = "semanas";
		$x_forma_pago_singular = "semana";
		$x_forma_pago_periodo = "semanal";
		
		//$x_numero_plazo = $x_plazo_valor * 4;
		$x_numero_plazo = $x_plazo_valor ;
		$x_plazo = $x_numero_plazo." semanas ";		
		
		$x_tasa_anual_valor = $x_tasa_valor ;
		$x_tasa_valor = $x_tasa_valor / 52;
		
		break;
	case 2: // CATORCENAL
		$x_forma_pago_plural = "catorcenas";
		$x_forma_pago_singular = "catorcena";
		$x_forma_pago_periodo = "catorcenal";

		//$x_numero_plazo = $x_plazo_valor * 2;
		$x_numero_plazo = $x_plazo_valor;
		$x_plazo = $x_numero_plazo." catorcenas ";		
		
		$x_tasa_anual_valor = $x_tasa_valor ;	
		$x_tasa_valor = $x_tasa_valor / 26;

		break;
	case 3: // MENSUAL
		$x_forma_pago_plural = "meses";
		$x_forma_pago_singular = "mes";
		$x_forma_pago_periodo = "mensual";

		//$x_numero_plazo = $x_plazo_valor * 1;
		$x_numero_plazo = $x_plazo_valor;
		$x_plazo = $x_numero_plazo." meses ";		
		
		$x_tasa_anual_valor = $x_tasa_valor ;	
		$x_tasa_valor = $x_tasa_valor / 13.03;
	
		break;
	case 4: // QUINCENAL
		$x_forma_pago_plural = "quincenas";
		$x_forma_pago_singular = "quincena";
		$x_forma_pago_periodo = "quincenal";

		//$x_numero_plazo = $x_plazo_valor * 2;
		$x_numero_plazo = $x_plazo_valor ;
		$x_plazo = $x_numero_plazo." quincenas ";		
		
		$x_tasa_anual_valor = $x_tasa_valor ;
		$x_tasa_valor = $x_tasa_valor / 24.33;
		break;
		
}
 $x_tt = explode($x_tasa_valor, "."); // separamos la cadena por el punto decimal
 $x_parte_entera = $x_tt[0]; // antes del punto
 $x_cont_enteros = strlen($x_parte_entera); // digitos de la parte entera
 $x_digitos_c = $x_cont_enteros + 3; // los digitos de la parte entera maas el punto mas dos decimales(+3);
 

$x_parte_dos = substr($x_tasa_valor, 0,  $x_digitos_c);  // abcd


$x_tasa_letras = covertirPorcientoLetras($x_tasa_valor);
#echo "la tasa es antes de redondear".$x_tasa_valor."<br>";
#$x_tasa = FormatNumber($x_tasa_valor,$x_tasa_decimales,0,0,1)."%(-".trim($x_tasa_letras)."-) ";
$x_tasa = " ".$x_parte_dos."%(-".trim($x_tasa_letras)."-) ";
#$x_tasa = printf("%.2f",intval(($x_tasa*100))/100);
$x_tasa_anual_letras = covertirPorcientoLetras($x_tasa_anual_valor);
$x_tasa_anual = FormatNumber($x_tasa_anual_valor,$x_tasa_anual_decimales,0,0,1)."%(-".trim($x_tasa_anual_letras)."-) ";

//$5,000.00 (-Cinco mil pesos, 00/100 M.N.-),
$x_penalizacion_letras = covertirNumLetras($x_penalizacion);
$x_penalizacion= "$".FormatNumber($x_penalizacion,2,0,0,1)." ( -$x_penalizacion_letras-) ";




//$x_tasa_moratoria_letras = covertirPorcientoLetras($x_tasa_moratoria_valor);
//$x_tasa_moratoria_letras = covertirNumLetras($x_tasa_moratoria_valor);

//$x_moratorios = "$".FormatNumber($x_tasa_moratoria_valor,$x_tasa_moratoria_decimales,0,0,1)."(-".trim($x_tasa_moratoria_letras)."-) ";

//$x_moratorios = "$".FormatNumber($x_tasa_moratoria_valor,2,0,0,1)." (-$x_tasa_moratoria_letras-) ";
//1.98% (-uno punto noventa y ocho porciento-), 

$x_tasa_moratoria_letras = covertirNumLetras($x_tasa_moratoria_valor);
$x_moratorios = "$".FormatNumber($x_tasa_moratoria_valor,2,0,0,1)." (- $x_tasa_moratoria_letras -) ";


//$x_fecha_contrato = FechaLetras($x_fecha_otorgamiento_valor);

$x_fecha_contrato = FechaLetras(strtotime(ConvertDateToMysqlFormat($x_fecha_otorgamiento_valor)));
//$x_fecha_contrato = "&nbsp;".FormatDateTime($x_fecha_otorgamiento_valor,7);

$x_fecha_vencimiento = FechaLetras(strtotime(ConvertDateToMysqlFormat($x_fecha_vencimiento_valor)));

//AVAL
//$sSql = "SELECT nombre_completo, apellido_paterno, apellido_materno FROM aval where solicitud_id = $x_solicitud_id";
$sSql = "SELECT nombre_completo, apellido_paterno, apellido_materno FROM datos_aval where solicitud_id = $x_solicitud_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_aval = strtoupper(htmlentities($row["nombre_completo"])." ".htmlentities($row["apellido_paterno"])." ".htmlentities($row["apellido_materno"]));
phpmkr_free_result($rs);

//INTEGRANTES DEL GRUPO
if($GLOBALS["x_creditoSolidario_id"]>0){
	//echo "entro en solidario--------------------<br>";
	$x_lista_acreditados =	"<table class='ewTable' align='center' border=0 cellspacing=0 cellpadding=0 >";
	//$x_lista_acreditados .= "<tr>";
	$x_cont_int = 1; //$GLOBALS["x_cliente_id_$x_cont_g"]
	$conta_num = 1;
	while ($x_cont_int <= 10){
		$x_cliente_id_act = "x_cliente_id_$x_cont_int";
		$x_cliente_id_act = $$x_cliente_id_act;
		
		if(($x_cliente_id_act != "newone") && ($x_cliente_id_act != "vacio") && ($x_cliente_id_act != "NEWONE") && ($x_cliente_id_act != "VACIO") ){
		
		$sqlintegrante = "SELECT * FROM cliente WHERE cliente_id = $x_cliente_id_act";
		$responseInt = phpmkr_query($sqlintegrante,$conn) or die("erroe en el representante".phpmkr_error()."sql".$sqlRepresentante);
		$rowInT = phpmkr_fetch_array($responseInt);
		$x_nombre_completo_int =   $rowInT["nombre_completo"]. " ";
		$x_nombre_completo_int .=   $rowInT["apellido_paterno"]." ";
		$x_nombre_completo_int .=   $rowInT["apellido_materno"];
		
		phpmkr_free_result($rowInT);	
		$x_lista_acreditados .= "<tr><td> $conta_num.-</td><td> $x_nombre_completo_int </td> <td>______________________________</td></tr>";
		$conta_num++;
		}// FIN DIFERENTE DE TEXTO
		
		$x_cont_int++;		
		}
	
	$x_lista_acreditados .= "</table>";
	
	}





////////////////////////////////////////////////////
// tabla de integrantes del grupo   solidario     //
//////////////////////////////////////////(¡///////
										   
										   
										   
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
	$x_lista_acreditados =	"<table  align='center' border=0 cellspacing=0 cellpadding=0 >";
	//$x_lista_acreditados .= "<tr>";
	$x_cont_int = 1; //$GLOBALS["x_cliente_id_$x_cont_g"]
	$x_conta = 1;
	while ($x_cont_int <= 10){
		$x_cliente_id_act = "x_cliente_id_$x_cont_int";
		$x_cliente_id_act = $$x_cliente_id_act;
		
		
		if(($x_cliente_id_act != "newone") && ($x_cliente_id_act != "vacio") && ($x_cliente_id_act != "NEWONE") && ($x_cliente_id_act != "VACIO") ){
			if($x_cliente_id_act != $x_representante_cliente_id){
		$sqlintegrante = "SELECT * FROM 	cliente WHERE cliente_id = $x_cliente_id_act";
		$responseInt = phpmkr_query($sqlintegrante,$conn) or die("erroe en el representante".phpmkr_error()."sql".$sqlRepresentante);
		$rowInT = phpmkr_fetch_array($responseInt);
		$x_nombre_completo_int =   $rowInT["nombre_completo"]. " ";
		$x_nombre_completo_int .=   $rowInT["apellido_paterno"]." ";
		$x_nombre_completo_int .=   $rowInT["apellido_materno"];
		
		
		$GLOBALS["x_nombre_integrante_$x_cont_int"] =  $rowInT["nombre_completo"]. " ".$rowInT["apellido_paterno "];
		phpmkr_free_result($rowInT);
		
		$x_lista_acreditados .="<tr><td align=\"center\" >&nbsp;</td></tr>";
		$x_lista_acreditados .="<tr><td align=\"center\"><span style=\"line-height: 115%; font-family: 'Calibri','sans-serif'; font-size: 11pt; mso-fareast-font-family: Calibri; mso-bidi-font-family: 'Times New Roman'; mso-fareast-language: EN-US; mso-ansi-language: ES-MX; mso-bidi-language: AR-SA\">POR AVAL</td></tr>";
		$x_lista_acreditados .="<tr><td>&nbsp;</td></tr>";
		$x_lista_acreditados .="<tr><td align=\"center\">______________________________</td></tr>";
		$x_lista_acreditados .= "<tr><td align=\"center\"><span style=\"line-height: 115%; font-family: 'Calibri','sans-serif'; font-size: 11pt; mso-fareast-font-family: Calibri; mso-bidi-font-family: 'Times New Roman'; mso-fareast-language: EN-US; mso-ansi-language: ES-MX; mso-bidi-language: AR-SA\"> $x_nombre_completo_int </td></tr>";
		$x_conta++;
		}// FIN IF DIERENTE DE TEXTO
		
		}
		$x_cont_int++;
		}
	
	$x_lista_acreditados .= "</table>";
	
	// se crea la tabla numero dos
	
	//calculo del la catidada a pagar por integrante
}



//TABLA DE VENC
$sSql = "SELECT * FROM vencimiento where vencimiento.credito_id = $x_credito_id ORDER BY vencimiento.vencimiento_num";
#echo "sql vencimeinto".$sSql."<br>";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$x_tabla = "
<table class='ewTable' align='center'>
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
$x_cont_fetc_v = 1;//variable para la tabla 3 integrentes de grupo
while ($row = @phpmkr_fetch_array($rs)) {
	
	
	$nRecCount = $nRecCount + 1;
	$GLOBALS["x_total_numero_vencimeintos"]= $nRecCount;
	if ($nRecCount >= $nStartRec) {
		$nRecActual++;

		// Set row color
		$sItemRowClass = " class=\"ewTableRow\"";

		// Display alternate color for rows
		if ($nRecCount % 2 <> 1) {
			$sItemRowClass = " class=\"ewTableAltRow\"";
		}
	
		//selccion de los valores de tabla de vencimientos
		$x_vencimiento_id = $row["vencimiento_id"];
		$x_vencimiento_num = $row["vencimiento_num"];		
		$x_credito_id = $row["credito_id"];
		$x_vencimiento_status_id = $row["vencimiento_status_id"];


		
		$x_fecha_vencimiento_tab = $row["fecha_vencimiento"];
		$GLOBALS["x_fecha_vencimeinto_$nRecCount"] = $row["fecha_vencimiento"];
		$x_importe_tab = $row["importe"];
		$x_interes_tab = $row["interes"];
		$x_iva_tab = $row["iva"];	
		
		// varible para la tabla 3 integrantes del grupo
		//$x_cont_fetc_v = 1;
		
		//valores para la tabla 3
		
		$x_fecha_vto= $x_fecha_vencimiento_tab; // tu sabrás como la obtienes, solo asegurate que tenga este formato
		//$dias= 2; // los días a restar
		$x_fecha_vto = ConvertDateToMysqlFormat($x_fecha_vencimiento_tab);
		
		$sqlDIAMENOS = "SELECT DATE_SUB('$x_fecha_vto', INTERVAL 1 DAY )as fecha";
		$resposDM = phpmkr_query($sqlDIAMENOS,$conn) or die("error en dia menos".phpmkr_error()."sql:".$sqlDIAMENOS);
		
		$rowDM = phpmkr_fetch_array($resposDM);
		$x_fecha_new_1_day =  $rowDM["fecha"];
		//echo  $x_fecha_new_1_day;
		 date("Y-m-d", strtotime("$x_fecha_vto -$dias day")); 
		$GLOBALS["x_fech_venci_$x_cont_fetc_v"] = $x_fecha_new_1_day;
		$x_cont_fetc_v++;
		///fin varible tabla 3
		
		if(empty($x_iva_tab)){
			$x_iva_tab = 0;
		}
		$x_interes_moratorio_tab = $row["interes_moratorio"];
		
		$x_total = $x_importe_tab + $x_interes_tab + $x_interes_moratorio_tab + $x_iva_tab;
		$GLOBALS["x_monto_total_pago"]= $x_total;
		//echo"el pago es de:".$x_total;
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
<tr $sItemRowClass $sListTrJs>
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



//genero las filas de la tabla numero dos
$x_filas_tabla_2 .= "<tr>
    <td> $x_fecha_vencimiento_tab </td>";
    if(!empty($x_nombre_integrante_1)){ $x_filas_tabla_2 .= "<td>&nbsp;</td>";}
    if(!empty($x_nombre_integrante_2)){ $x_filas_tabla_2 .= "<td>&nbsp;</td>";}
    if(!empty($x_nombre_integrante_3)){ $x_filas_tabla_2 .= " <td>&nbsp;</td>";}
    if(!empty($x_nombre_integrante_4)){ $x_filas_tabla_2 .= " <td>&nbsp;</td>";}
    if(!empty($x_nombre_integrante_5)){ $x_filas_tabla_2 .= "<td>&nbsp;</td>";}
    if(!empty($x_nombre_integrante_6)){ $x_filas_tabla_2 .= " <td>&nbsp;</td>";}
    if(!empty($x_nombre_integrante_7)){ $x_filas_tabla_2 .= " <td>&nbsp;</td>";}
    if(!empty($x_nombre_integrante_8)){ $x_filas_tabla_2 .= "  <td>&nbsp;</td>";}
    if(!empty($x_nombre_integrante_9)){ $x_filas_tabla_2 .= " <td>&nbsp;</td>";}
    if(!empty($x_nombre_integrante_10)){ $x_filas_tabla_2 .= "  <td>&nbsp;</td>";}
   $x_filas_tabla_2 .=" <td> $x_total </td>
  </tr> ";
  $x_int_g = 1;
 while($x_int_g<=10){
	 
	 
	 
	 
	 $x_int_g++;
	 }//fin while integrantes
	 
	
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
$x_numpagos = $x_numpagos."(-".covertirNumLetrasSimple($x_numpagos)."-)";
$x_importevenc = $x_total;
$x_importevenc_letras = covertirNumLetras($x_importevenc);
$x_importevenc = "$".FormatNumber($x_importevenc,2,0,0,1)." (-$x_importevenc_letras-) ";

/***********************************************************************
/*
/*TABLA DOS AL 60%
/*
/**********************************************************************/


////////////////////////////////////////////////////////////////calculo del credito con tasa nueva
	$x_num_pagos = $x_plazo_valor;	
	$x_importe_c = 	$GLOBALS["x_importe_solicitado"];
	$x_importe_c = str_replace(",","",$x_importe_c);
	$x_tasa_nueva = 60;
	$x_forma_pago_new = $x_forma_pago_valor;
	switch ($x_forma_pago_new)
{
	case 28: // Mensual
		$x_tasa_nueva= (($x_tasa_nueva/12)); 
		break;
	case 15: // Quincenal
		$x_tasa_nueva = (($x_tasa_nueva/24)); 
		break;
	case 14: // Catorcenal
		$x_tasa_nueva= (($x_tasa_nueva/26)); 
		break;
	case 7: // Semanal
		$x_tasa_nueva = (($x_tasa_nueva/52)); 
		break;
		
}
	

	$x_interes = 0;
	$x_pago_act = 1;
	while($x_pago_act < $x_num_pagos + 1){
		$x_interes_act = (1/pow((1+doubleval($x_tasa_nueva  / 100 )),$x_pago_act));

		$x_interes = $x_interes + $x_interes_act;
	$GLOBALS["x_interes"]= $x_interes ;
		/*$sSql = "insert into vencimiento values(0,$x_credito_id, $x_pago_act,1, '$fecha_act', 0, 0, 0, 0, 0, 0)";
		$x_result = phpmkr_query($sSql, $conn);
		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}*/
		
		$temptime = strtotime($fecha_act);
		$x_pago_act++;	
	}// fin  numero de venciamientos  	
	//el total del vencimiento
	$GLOBALS["x_total_venc_2"] = round($x_importe_c / $x_interes);

	$x_num_ven_tasa_new = 1;
	$x_saldo_N = $x_importe_c;
	while($x_num_ven_tasa_new <= $GLOBALS["x_total_numero_vencimeintos"]){
		
			$x_fecha_vencimiento_act = "x_fecha_vencimeinto_$x_num_ven_tasa_new";	
			$x_fecha_vencimiento_act = $$x_fecha_vencimiento_act;
			
						
						// Set row color
		$sItemRowClass = " class=\"ewTableRow\"";

		// Display alternate color for rows
		if ($x_num_ven_tasa_new % 2 <> 1) {
			$sItemRowClass = " class=\"ewTableAltRow\"";
		}
						$x_capital_venc = ($GLOBALS["x_importe"] / $x_num_pagos);
						$x_interes_venc = round($x_total_venc - $x_capital_venc);
						if($GLOBALS["x_iva"] == 1){
							$x_iva_venc = round($x_interes_venc * .16);	
							$x_total_venc = $x_total_venc + $x_iva_venc;
						}else{
							$x_iva_venc = 0;
						}
		
		$x_total_ven = $GLOBALS["x_total_venc_2"];
		
		$interes = ($GLOBALS["x_saldo_N"] * ( $x_tasa_nueva/100));
		$x_importe_n = $x_total_ven - $interes ;
		$x_cuenta_venciamientos += $x_total_ven;
		$x_filas_tasa_nueva .= "<tr $sItemRowClass $sListTrJs>
									<td align='center'>$x_num_ven_tasa_new</td>
									<td align='center'>$x_fecha_vencimiento_act</td>
									<td align='right'>". FormatNumber($x_saldo_N,2,0,0,1). "</td>
									<td align='right'>" .FormatNumber($x_importe_n,2,0,0,1). "</td>
									<td align='right'>". FormatNumber($interes,2,0,0,1). "</td>
									<td align='right'>". FormatNumber($x_iva_venc,2,0,0,1). "</td>
									<td align='right'>". FormatNumber($x_total_ven,2,0,0,1). "</td>
			
			</tr>";
			$x_fila_final_nueva = "<tr $sItemRowClass $sListTrJs>
									<td align='center'></td>
									<td align='center'></td>
									<td align='right'></td>
									<td align='right'></td>
									<td align='right'></td>
									<td align='right'></td>
									<td align='right'><strong>".FormatNumber($x_cuenta_venciamientos,2,0,0,1). "</strong></td>
									</tr>";
		
		$GLOBALS["x_saldo_N"] = $x_saldo_N - $x_importe_n;
		
		
		$x_num_ven_tasa_new ++;
		}//FIN NUMERO DE VENCIMIENTOS
	
	
	
	
	$x_nueva_tasa_tabla = "<table class='ewTable' align='center'> <tr>
									<td> <strong>Numero de pago</strong></td>
									<td><strong>Fecha para realizar el pago </strong></td>
									<td><strong>Saldo</strong></td>
									<td><strong>Importe</strong></td>
									<td><strong>Interes</strong></td>
									<td><strong>IVA</strong></td>
									<td><strong>Total</strong></td>
			
			</tr>";
	
	
	$x_nueva_tasa_tabla .= $x_filas_tasa_nueva;
	$x_nueva_tasa_tabla .= $x_fila_final_nueva;
	
	$x_nueva_tasa_tabla .= "</table>";
	
	
	
	
	//////////////////////////////////////////////////////////////termina calculo de tasa nueva		
		







///////////////////////////////////////////
//generamos los caculo de la tabla dos   //
//aplica para los casos de grupo        //
//////////////////////////////////////////

//calculos de la tabla dos
	$x_saldo_grupo = $x_importe_solicitado;
	$x_total_a_pagar = $x_monto_total_pago ;
	$x_cont_cal = 1;
	while($x_cont_cal <= 10){
		
		$x_cant_soli_integrante = "x_monto_$x_cont_cal";
		$x_cant_soli_integrante = $$x_cant_soli_integrante;
		/*echo "total apagar:".$x_total_a_pagar."-";
		echo "inte". $x_cant_soli_integrante."-";
		echo "grupo ". $x_saldo_grupo."--";
		echo "multi".doubleval(($x_total_a_pagar * $x_cant_soli_integrante));
		echo "divi".doubleval(($x_total_a_pagar * $x_cant_soli_integrante))/$x_saldo_grupo;*/
		$x_monto_por_integrante = (($x_total_a_pagar * $x_cant_soli_integrante)/$x_saldo_grupo);
		$x_monto_por_integrante = $x_monto_por_integrante;
		//echo "MONTO INT". $x_monto_por_integrante."";
		$GLOBALS["x_monto_pago_int_$x_cont_cal"] = $x_monto_por_integrante ;
		
		
		
		
		$x_cont_cal++;
		}//while calcula montos por integrante

//genero la fila de la tabla dos



//sugue tabla dos
	
	
	$x_grupo_t = " 
	<table class='ewTable' width='800' align='center'>
  <tr>
    <td width='150' align='cente'>&nbsp;</td>";
    if(!empty($x_nombre_integrante_1)){ $x_grupo_t .= "<td width='50' > $x_nombre_integrante_1</td>"; }
    if(!empty($x_nombre_integrante_2)){ $x_grupo_t .= " <td width='50'> $x_nombre_integrante_2</td>";}
    if(!empty($x_nombre_integrante_3)){ $x_grupo_t .= "<td  width='50'> $x_nombre_integrante_3</td>";}
    if(!empty($x_nombre_integrante_4)){ $x_grupo_t .= "<td width='50' > $x_nombre_integrante_4</td>";}
    if(!empty($x_nombre_integrante_5)){ $x_grupo_t .= " <td width='50'> $x_nombre_integrante_5</td>";}
    if(!empty($x_nombre_integrante_6)){ $x_grupo_t .= "<td width='50'> $x_nombre_integrante_6</td>";}
    if(!empty($x_nombre_integrante_7)){ $x_grupo_t .= " <td width='50'> $x_nombre_integrante_7</td>";}
    if(!empty($x_nombre_integrante_8)){ $x_grupo_t .= " <td width='50' > $x_nombre_integrante_8</td>";}
    if(!empty($x_nombre_integrante_9)){ $x_grupo_t .= " <td width='50'> $x_nombre_integrante_9</td>";}
    if(!empty($x_nombre_integrante_10)){ $x_grupo_t .= " <td width='50'> $x_nombre_integrante_10</td>";}
   $x_grupo_t .="<td width='50'>Monto total</td>
  </tr>
  <tr>
    <td>MONTO</td>";
    if(!empty($x_nombre_integrante_1)){ $x_grupo_t .= "<td>".number_format($x_monto_1)."</td>"; }
    if(!empty($x_nombre_integrante_2)){ $x_grupo_t .= "<td>".number_format($x_monto_2)."</td>"; }
  	if(!empty($x_nombre_integrante_3)){ $x_grupo_t .= "<td>".number_format($x_monto_3)."</td>"; }
   	if(!empty($x_nombre_integrante_4)){ $x_grupo_t .= "<td>".number_format($x_monto_4)."</td>"; }
    if(!empty($x_nombre_integrante_5)){ $x_grupo_t .= "<td>".number_format($x_monto_5)."</td>"; }
   	if(!empty($x_nombre_integrante_6)){ $x_grupo_t .= " <td>".number_format($x_monto_6)."</td>"; }
    if(!empty($x_nombre_integrante_7)){ $x_grupo_t .= "<td>".number_format($x_monto_7)."</td>"; }
  	if(!empty($x_nombre_integrante_8)){ $x_grupo_t .= " <td>".number_format($x_monto_8)."</td>"; }
    if(!empty($x_nombre_integrante_9)){ $x_grupo_t .= " <td>".number_format($x_monto_9)."</td>"; }
    if(!empty($x_nombre_integrante_10)){ $x_grupo_t .= "<td>".number_format($x_monto_10)."</td>"; }
    $x_grupo_t .="<td>&nbsp;</td>
  </tr>
   <tr>
    <td>PAGO</td>";
     if(!empty($x_nombre_integrante_1)){ $x_grupo_t .= "<td>$x_monto_pago_int_1</td>";}
     if(!empty($x_nombre_integrante_2)){ $x_grupo_t .= "<td>$x_monto_pago_int_2</td>";}
     if(!empty($x_nombre_integrante_3)){ $x_grupo_t .= "<td>$x_monto_pago_int_3</td>";}
  	 if(!empty($x_nombre_integrante_4)){ $x_grupo_t .= "<td>$x_monto_pago_int_4</td>";}
   	 if(!empty($x_nombre_integrante_5)){ $x_grupo_t .= "<td>$x_monto_pago_int_5</td>";}
     if(!empty($x_nombre_integrante_6)){ $x_grupo_t .= "<td>$x_monto_pago_int_6</td>";}
     if(!empty($x_nombre_integrante_7)){ $x_grupo_t .= "<td>$x_monto_pago_int_7</td>";}
     if(!empty($x_nombre_integrante_8)){ $x_grupo_t .= "<td>$x_monto_pago_int_8</td>";}
     if(!empty($x_nombre_integrante_9)){ $x_grupo_t .= "<td>$x_monto_pago_int_9</td>";}
     if(!empty($x_nombre_integrante_10)){ $x_grupo_t .= "<td>$x_monto_pago_int_10</td>";}
     $x_grupo_t .= "<td>&nbsp;</td>
   
  </tr>
   
  
  ";//termina encabezado
  $x_link_representante_grupo = "<a href=\"php_pagare_print_representante.php?solicitud_id=$x_solicitud_id\" class=\"link_oculto\">Tabla para Representante </a>";
 $x_link_representante_grupo .="&nbsp;&nbsp;<a href=\"php_tabla_representante_firmas.php?solicitud_id=$x_solicitud_id\" class=\"link_oculto\">Tabla para Representante con firmas </a>" ;
  
  $x_grupo_t .= $x_filas_tabla_2 ;
  $x_grupo_t .= "
  <tr>
    <td>&nbsp;</td>";
     if(!empty($x_nombre_integrante_1)){ $x_grupo_t .= "<td>&nbsp;</td>";}
     if(!empty($x_nombre_integrante_2)){ $x_grupo_t .= "<td>&nbsp;</td>";}
     if(!empty($x_nombre_integrante_3)){ $x_grupo_t .= "<td>&nbsp;</td>";}
  	 if(!empty($x_nombre_integrante_4)){ $x_grupo_t .= "<td>&nbsp;</td>";}
   	 if(!empty($x_nombre_integrante_5)){ $x_grupo_t .= "<td>&nbsp;</td>";}
     if(!empty($x_nombre_integrante_6)){ $x_grupo_t .= "<td>&nbsp;</td>";}
     if(!empty($x_nombre_integrante_7)){ $x_grupo_t .= "<td>&nbsp;</td>";}
     if(!empty($x_nombre_integrante_8)){ $x_grupo_t .= "<td>&nbsp;</td>";}
     if(!empty($x_nombre_integrante_9)){ $x_grupo_t .= "<td>&nbsp;</td>";}
     if(!empty($x_nombre_integrante_10)){ $x_grupo_t .= "<td>&nbsp;</td>";}
    $x_grupo_t .="<td>&nbsp;</td>
  </tr></table>";
	

$x_no_integrante = 1;
while($x_no_integrante <= 10){
	
	$x_nombre_int = "x_nombre_integrante_$x_no_integrante";
	$x_nombre_int = $$x_nombre_int;
	
	//integrante_id
	$x_id_int = "x_cliente_id_$x_no_integrante";
	$x_id_int = $$x_id_int ;
	
	if(!empty($x_nombre_int)){
	$x_cant_soli_integrante = "x_monto_$x_no_integrante";
	$x_cant_soli_integrante = $$x_cant_soli_integrante;
	//echo"cantidad de integrante".$x_saldo_grupo;
	
	$x_monto_integrante = (($x_total_a_pagar * $x_cant_soli_integrante)/$x_saldo_grupo);
	//$x_monto_integrante = $x_monto_por_integrante;
	
	
	//link para las tablas de  los integrantes
	
	 $x_link_integrantes_grupo .= "<a href=\"php_pagare_print_integrante.php?solicitud_id=$x_solicitud_id&integrante_id=$x_id_int\" class=\"link_oculto\">$x_nombre_int </a>&nbsp;";
$_integrates_tablas .= "<table class='ewTable' width='700' border='1'>
  <tr>
    <td colspan='3' align='left'>$x_nombre_int</td>
  </tr>
  <tr>
    <td>FECHA</td>
    <td>MONTO</td>
    <td>FIRMA</td>
  </tr>
";
$x_ciclo = 1;
while($x_ciclo < $x_cont_fetc_v){
	
	$x_fecha = "x_fech_venci_$x_ciclo";
	$x_fecha = $$x_fecha;
	$_integrates_tablas .="<tr>
    <td>$x_fecha</td>
    <td>$x_monto_integrante</td>
    <td>&nbsp;</td>
  </tr>";
 
 $x_ciclo++;
}
 $_integrates_tablas .= "</table>";
	
	}
$x_no_integrante++;
}


$x_link_nueva_tasa_tabla = "<a href=\"php_pagare_print_nueva_tasa.php?solicitud_id=$x_solicitud_id\" class=\"link_oculto\">(CAT) 50% </a>&nbsp;";



$currdate = date("Y-m-d");

$x_fecha_actual  = FechaLetras_normal(strtotime(ConvertDateToMysqlFormat($currdate)),false);

/*
//}//no de integrantes
$x_contenido = str_replace("\$x_numpagos",$x_numpagos,$x_contenido);
$x_contenido = str_replace("\$x_importevenc",$x_importevenc,$x_contenido);
$x_contenido = str_replace("\$x_aval",htmlentities($x_aval),$x_contenido);

//$x_contenido = str_replace("\$x_aval",$x_aval,$x_contenido);
$x_contenido = str_replace("\$x_anual_tasa",$x_tasa_anual,$x_contenido);

$x_contenido = str_replace("\$x_tarjeta",htmlentities($x_tarjeta_numero),$x_contenido);
$x_contenido = str_replace("\$x_tabla",$x_tabla,$x_contenido);
$x_contenido = str_replace("\$x_cliente",htmlentities($x_cliente),$x_contenido);
$x_contenido = str_replace("\$x_direccion",htmlentities($x_direccion),$x_contenido);

$x_contenido = str_replace("\$x_forma_pago_plural",$x_forma_pago_plural,$x_contenido);
$x_contenido = str_replace("\$x_forma_pago_singular",$x_forma_pago_singular,$x_contenido);
$x_contenido = str_replace("\$x_forma_pago_periodo",$x_forma_pago_periodo,$x_contenido);
$x_contenido = str_replace("\$x_plazo",$x_plazo,$x_contenido);

$x_contenido = str_replace("\$x_importe",$x_importe,$x_contenido);

$x_contenido = str_replace("\$x_moratorios",$x_moratorios,$x_contenido);
$x_contenido = str_replace("\$x_tasa",$x_tasa,$x_contenido);
$x_contenido = str_replace("\$x_fecha_contrato",$x_fecha_contrato,$x_contenido);
$x_contenido = str_replace("\$x_fecha_vencimiento",$x_fecha_vencimiento,$x_contenido);
//esatdo
$x_contenido = str_replace("\$x_estado",$x_estado,$x_contenido);
$x_contenido = str_replace("\$x_lista_acreditados",$x_lista_acreditados,$x_contenido);
$x_contenido = str_replace("\$x_representate_grupo",$x_nombre_completo_rep,$x_contenido);
$x_contenido = str_replace("\$x_group_tabla_2",$x_link_representante_grupo,$x_contenido); 
$x_contenido = str_replace("\$_integrates_tablas",$x_link_integrantes_grupo,$x_contenido); 
$x_contenido = str_replace("\$x_nueva_tasa",$x_link_nueva_tasa_tabla,$x_contenido);
//$x_contenido = str_replace("\$x_nueva_tasa",$x_nueva_tasa_tabla,$x_contenido);


$x_contenido = str_replace("\$x_penalizacion",$x_penalizacion,$x_contenido);


$x_contenido = str_replace("\$x_consulta_cuenta",$x_consulta_cuenta,$x_contenido);

*/
// carta cambio de administracion
$x_total_gral_sin_tope = ($x_total_dias_vencidos * 10) + $x_condonado;
$x_total_gral_con_tope = "$".FormatNumber($_total_faltante,2,0,0,1);
$x_total_gral_sin_tope = "$".FormatNumber($x_total_gral_sin_tope,2,0,0,1);
$x_capital_ordinario = "$".FormatNumber($x_capital_ordinario,2,0,0,1);
$x_condonado = "$".FormatNumber($x_condonado,2,0,0,1);
#echo "condonado".$x_condonado."<br>";

$x_contenido = str_replace("\$x_saldo_total_credito",$x_total_gral_con_tope,$x_contenido);
$x_contenido = str_replace("\$x_mora_sin_truncar",$x_total_gral_sin_tope,$x_contenido);
$x_contenido = str_replace("\$x_capital_ordinario_iva",$x_condonado,$x_contenido);
$x_contenido = str_replace("\$x_fecha_valida",$x_fecha_valida,$x_contenido);
$x_contenido = str_replace("\$x_fecha_actual",$x_fecha_actual,$x_contenido);
$x_contenido = str_replace("\$x_cliente",htmlentities($x_cliente),$x_contenido);
$x_contenido = str_replace("\$x_nombre_grupo",$x_nombre_grupo,$x_contenido);



//echo htmlspecialchars_decode($x_contenido); 
echo $x_contenido; 



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
		
		
		$sSql = "SELECT * FROM credito  WHERE solicitud_id = ".$GLOBALS["x_solicitud_id"]." ";
		$rs = phpmkr_query($sSql, $conn) or die ("Error al selccionar los datos del credito". phpmkr_error()."sql;".$sSql);
		$rowC = phpmkr_fetch_array($rs);
		$GLOBALS["x_penalizacion"] = $rowC["penalizacion"];
		$GLOBALS["x_garantia_liquida"] = $rowC["garantia_liquida"];
		$GLOBALS["x_credito_id"] = $rowC["credito_id"];
		
		
		//INTEGRANTES DEL GRUPO
		$x_soli_id =  $GLOBALS["x_solicitud_id"];
		if($GLOBALS["x_credito_tipo_id"] == 2){
			// ES UN CREDITO SOLIDARIO
			//echo "load data solidario";
			$sqlGrupo = "SELECT * FROM creditosolidario WHERE  solicitud_id = $x_soli_id";
			//echo "sql solidario".$sqlGrupo."<br>";+
			$responseGrupo = phpmkr_query($sqlGrupo,$conn) or die ("error al ejecutar query grupo".phpmkr_error()."sql: ".$sqlGrupo);
			$rowGrupo = phpmkr_fetch_array($responseGrupo);
			$GLOBALS["x_creditoSolidario_id"] =  $rowGrupo["creditoSolidario_id"];
			$GLOBALS["x_nombre_grupo"] = $rowGrupo["nombre_grupo"];
			
			$x_cont_g = 1;
			while($x_cont_g <= 10){
				
				$GLOBALS["x_integrante_$x_cont_g"] = $rowGrupo["integrante_$x_cont_g"];
				//$x_monto_i =  $rowGrupo["monto_$x_cont_g"];
				//$GLOBALS["x_monto_$x_cont_g"] = number_format($x_monto_i);
				$GLOBALS["x_monto_$x_cont_g"] =  $rowGrupo["monto_$x_cont_g"];
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



	//CREDITO
	//SELECIONO LOS DATIS DE CREDITO PARA HACER LA TABLA  CON LA TASA AL 60%


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
		
		
//USUARIO
$sSqla = "SELECT * FROM usuario where usuario_id = ".$row2["usuario_id"]. "";
$rsa = phpmkr_query($sSqla,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSqla);
$rowa = phpmkr_fetch_array($rsa);
$x_usuario = $rowa["usuario"];
$x_clave = $rowa["clave"];
phpmkr_free_result($rsa);
$GLOBALS["x_consulta_cuenta"] = "<p>Consulta tu estado de cuenta desde www.financieracrea.com en la seccion ingreso para clientes, tu cuenta es: $x_usuario , tu clave es: $x_clave";

				
		$sSql = "select * from direccion where cliente_id = ".$GLOBALS["x_cliente_id"]." and direccion_tipo_id = 1";
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

		$sSql = "select * from direccion where cliente_id = ".$GLOBALS["x_cliente_id"]." and direccion_tipo_id = 2";
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
		$GLOBALS["x_apellido_paterno_aval"] = $row5["apellido_paterno"];
		$GLOBALS["x_apellido_materno_aval"] = $row5["apellido_materno"];										
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




		$x_contenido_id = 0;
		
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
		$x_valor_credito_tipo_id = $GLOBALS["x_credito_tipo_id"];
		
		
		if($GLOBALS["x_solicitud_id"] > 5000){
			
			if($GLOBALS["x_penalizacion"] > 0){
			// se trata de penalizaciones ne le credito
			#ouede o no tener aval
			if($x_aval > 0){
				$x_contenido_id = 41;
				
				}else{
					$x_contenido_id = 40;					
					}
			
			}else{
				// se tarta de mora diaria
				#puede o no tner aval
					if($x_aval > 0){
				$x_contenido_id = 39;
				
				}else{
					$x_contenido_id = 38;					
					}
				
				
				}
			
			
			
			}else{
		
		if($x_valor_credito_tipo_id == 1){
			// el tipo de credito es PERSONAL
				if(($x_gara > 0) && ($x_aval>0) ){
					// el tipo de Pagare es el de INTERES GARANTIA AVAL
					//22 Pagare interes garantia y aval 
					$x_contenido_id = 22;
					
					}else if($x_gara > 0){
						//20 Pagare interes con garantia 
						$x_contenido_id = 20;
						
						}else if(($x_aval>0)){
							//21 Pagara interes con aval  
							$x_contenido_id = 21;
							}else{
								//23 Pagare con interes  
								$x_contenido_id = 23;
								}
			
			
			}else if($x_valor_credito_tipo_id == 2){
				//el tipo de credito es SOLIDARIO
				//25 Pagare Solidario  
				$x_contenido_id = 25;
				//FALTA DEFINIR
				}else if($x_valor_credito_tipo_id == 3){
					//EL TIPÓ DE CREDITO ES MAQUINARIA
					// NO TIENE GARANTIA...PERO PUEDE TENER AVAL
					if(($x_aval>0)){
						//21 Pagara interes con aval  
							$x_contenido_id = 21;
						}else{
							// no tiene aval
							//23 Pagare con interes  
								$x_contenido_id = 23;							
							}
					
					
					}else if($x_valor_credito_tipo_id == 4){
						//credito PYME
						//19 Pagare PYME 
						$x_contenido_id = 19;
						}
						
						
			}
				
				//echo "contenido".$x_contenido_id;		
		$sSql = "select contenido from formato_docto where formato_docto_id = 46 ";
		$rs10 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row10 = phpmkr_fetch_array($rs10);
		$GLOBALS["x_contenido"] = $row10["contenido"];
		

		#seleccionamos los datos de los vencimeintos
		$currdate = date("Y-m-d");
		$x_total_gral = 0;
		$x_capital_ordinario = 0;
		$x_total_gral_sin_tope = 0;
		$x_total_sin_tope = 0;
		
		$SQLcRDITO = "SELECT * FROM credito WHERE credito_id = ". $GLOBALS["x_credito_id"]." ";
		$rscRDITO = phpmkr_query($SQLcRDITO,$conn) or die("error la seleccionar los datos del credito".phpmkr_error()."sql;".$SQLcRDITO);
		$rowscRDITO = phpmkr_fetch_array($rscRDITO);
		$x_tasa_moratoria = $rowscRDITO["tasa_moratoria"];
		
		
		$sqlSaldoD = "SELECT SUM(total_venc) as total_p  FROM vencimiento WHERE credito_id = ". $GLOBALS["x_credito_id"]." and vencimiento_status_id in  (1,3,6)  ";
		$rsSaldoD = phpmkr_query($sqlSaldoD,$conn)or die("error sal ven".phpmkr_error()."sql :".$sqlSaldoD);
		$rowSaldoD = phpmkr_fetch_array($rsSaldoD);
		$GLOBALS["_total_faltante"] = $rowSaldoD["total_p"];//total
		
		$sqlSaldoD = "SELECT SUM(interes_moratorio) as moratorio  FROM vencimiento WHERE credito_id = ". $GLOBALS["x_credito_id"]." and vencimiento_status_id in  (1,3,6)  ";
		$rsSaldoD = phpmkr_query($sqlSaldoD,$conn)or die("error sal ven".phpmkr_error()."sql :".$sqlSaldoD);
		$rowSaldoD = phpmkr_fetch_array($rsSaldoD);
		$GLOBALS["_moratorio"] = $rowSaldoD["moratorio"];
		
		$sqlSaldoD = "SELECT SUM(iva_mor) as iva_moratorio  FROM vencimiento WHERE credito_id = ". $GLOBALS["x_credito_id"]." and vencimiento_status_id in  (1,3,6)  ";
		$rsSaldoD = phpmkr_query($sqlSaldoD,$conn)or die("error sal ven".phpmkr_error()."sql :".$sqlSaldoD);
		$rowSaldoD = phpmkr_fetch_array($rsSaldoD);
		$GLOBALS["_iva_moratorio"] = $rowSaldoD["iva_moratorio"];
		
		
		$GLOBALS["x_condonado"] = $GLOBALS["_total_faltante"]  -($GLOBALS["_moratorio"]+ $GLOBALS["_iva_moratorio"] );
		#echo "cond".$GLOBALS["x_condonado"]."<br>";
		$sqlVencimeintos = "SELECT * FROM vencimiento WHERE credito_id = ". $GLOBALS["x_credito_id"]." ";
	#	echo "sql mayor ".$sqlVencimeintos."<br>";
		$rsVencimeintos = phpmkr_query($sqlVencimeintos,$conn)or die ("Error al seleccionar los vencimientos". phpmkr_error()."sql:".$sqlVencimeintos);
		while($rowVencimientos = phpmkr_fetch_array($rsVencimeintos)){
			// seleccionamos el primer vancimeinto.
			// Y  vemos si esta vencido o o no
			$x_vencimiento_id = $rowVencimientos["vencimeinto_id"];
			$x_vencimiento_num = $rowVencimientos["vencimiento_num"];
			$x_vencimiento_status_id = $rowVencimientos["vencimiento_status_id"];//status vencimiento
			#echo "<br>venc staus----".$x_vencimiento_status_id;
			$x_fecha_vencimiento = $rowVencimientos["fecha_vencimiento"];// fecha de pago
			$x_importe = $rowVencimientos["importe"]; // importe	
			$x_interes = $rowVencimientos["interes"]; // interes
			$x_interes_moratorio = $rowVencimientos["interes_moratorio"]; //interes moratorio
			$x_iva = $rowVencimientos["iva"]; // iva
			$x_iva_mor = $rowVencimientos["iva_mor"];//  iva mor
			$x_total_venc = $rowVencimientos["total_venc"]; // total venc		
		
			
			$x_credito_id = $rowVencimientos["credito_id"];
		#	echo "<br>credito_id = ".$x_credito_id; 			
			$x_total_con_tope = $x_total_venc;			
			$x_c_ordi = $x_importe + $x_interes + $x_iva;			
			// status_vencimeito_id
			// 1 .- pendiente
			// 2.- pagado
			// 3.- vencido
			// 4.- cabcelado
			
			if(($x_vencimiento_status_id == 3)){
				// se recalculan los moratorios sin toparlos				
				#echo "<br>entra al if<br>";
				$sqlVenNum = "SELECT COUNT(*) AS numero_de_pagos FROM vencimiento WHERE  fecha_vencimiento =  \"$x_fecha_vencimiento\" AND  credito_id =  $x_credito_id";
				$response = phpmkr_query($sqlVenNum, $conn) or die("error en numero de vencimiento".phpmkr_error()."sql:".$sqlVenNum);
				$rownpagos = phpmkr_fetch_array($response);  
				$x_numero_de_pagos =  $rownpagos["numero_de_pagos"];
				
				if($x_numero_de_pagos < 2){
				//se hace el clculo de los moratorios				
				$x_dias_vencidos = datediff('d', $x_fecha_vencimiento, $currdate, false);					
					$x_dia = strtoupper(date('l',strtotime($x_fecha_vencimiento)));				
				
					#echo "<br>fecha  vencimeinto".$x_fecha_vencimiento;
	#echo "<br>fecha actual  ".$currdate;	
	#echo "<br>dias vencido  ".$x_dias_vencidos."<br>";
				
					$x_dias_gracia = 2;
					switch ($x_dia)
					{
						case "MONDAY": // Get a record to display
							$x_dias_gracia = 2;
							break;
						case "TUESDAY": // Get a record to display
							$x_dias_gracia = 2;
							break;
						case "WEDNESDAY": // Get a record to display
							$x_dias_gracia = 4;
							break;
						case "THURSDAY": // Get a record to display
							$x_dias_gracia = 4;
							break;
						case "FRIDAY": // Get a record to display
							$x_dias_gracia = 4;
							break;
						case "SATURDAY": // Get a record to display
							$x_dias_gracia = 3;
							break;
						case "SUNDAY": // Get a record to display
							$x_dias_gracia = 2;
							break;		
					}
	#echo "dias de gracia".$x_dias_gracia;
					#	echo "froma de pago =".$x_forma_pago_valor."<br>";
					#	echo "penalizacion ".$x_penalizacion."<br>";
						if(($x_dias_vencidos >= $x_dias_gracia) ){
							$x_importe_mora = $x_tasa_moratoria * $x_dias_vencidos;
							#echo "dias vencido".$x_dias_vencidos."<br>";							
							#echo "numero venc".$x_vencimiento_num."<br><br>";
							#echo "<br>importe mora".$x_importe_mora."<br>";
							if($x_iva_credito == 1){
					//			$x_iva_mor = round($x_importe_mora * .15);
								$x_iva_mor = 0;			
							}else{
								$x_iva_mor = 0;
							}
							
					//moratorios no majyores a 	2						
							if($x_credito_tipo_id == 2){			
								if($x_importe_mora > 0){
									$x_importe_mora = 250;
									}			
								}
								#echo "importe mora truncado".$x_importe_mora."<br>";
								
								if($x_vencimiento_num < 1000){
							$x_total_sin_tope = $x_importe + $x_interes + $x_importe_mora + $x_iva + $x_iva_mor;					
							#echo "total sin tope dentro del if". $x_total_sin_tope."-- <br>";
							
							$GLOBALS["x_total_dias_vencidos"] = $GLOBALS["x_total_dias_vencidos"] +$x_dias_vencidos;
								}else{
									$x_total_sin_tope = $x_total_venc;
									
									}
							
						}// dias vencidos mayor o ogial a dias de gracia....
				
				
				}// numero de pagos menor a 2
				
				
				#echo "total del vencimeitno".$x_total_con_tope."<br>";				
				$x_total_gral_sin_tope = $x_total_gral_sin_tope +$x_total_sin_tope;
		#	echo "<br> sin tope  total".$x_total_gral_sin_tope;
			$x_capital_ordinario = $x_capital_ordinario +$x_c_ordi;
			$x_total_gral_con_tope = $x_total_gral_con_tope + $x_total_con_tope;
			#echo "<br> CON tope".$x_total_gral_con_tope;
		#	echo "<br> ordinario".$x_capital_ordinario;
		#		
				}//vencimeinto_vencido
		#	echo "no entra<br>";
			$x_total_con_tope = $x_total_venc;
			
		
			}//while

$GLOBALS["x_total_gral_sin_tope"] = $x_total_gral_sin_tope ;
$GLOBALS["x_capital_ordinario"] = $x_capital_ordinario ;
$GLOBALS["x_total_gral_con_tope"] = $x_total_gral_con_tope ;

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


