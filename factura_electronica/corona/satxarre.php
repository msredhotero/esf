<?php
//
// +---------------------------------------------------------------------------+
// | satxarre.php : Genera arreglo asociativo en base a la factura del ERP     |
// +---------------------------------------------------------------------------+
// | Copyright (c) 2005  Fabrica de Jabon la Corona, SA de CV                  |
// +---------------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or             |
// | modify it under the terms of the GNU General Public License               |
// | as published by the Free Software Foundation; either version 2            |
// | of the License, or (at your option) any later version.                    |
// |                                                                           |
// | This program is distributed in the hope that it will be useful,           |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
// | GNU General Public License for more details.                              |
// |                                                                           |
// | You should have received a copy of the GNU General Public License         |
// | along with this program; if not, write to the Free Software               |
// | Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA|
// +---------------------------------------------------------------------------+
// | Autor: Fernando Ortiz <fortiz@lacorona.com.mx>                            |
// +---------------------------------------------------------------------------+
// |                                                                           |
// +---------------------------------------------------------------------------+
//



#la funcion satarre recibe dos parapetros el primero es el numero de la factura y el segundo es la addenda en  el caso de FINANCIERACREA  no se utiliza addenda asi que este valor queda  vacio.sd.
function satxarre($nufa,$addenda="") {
// {{{ carga librerias requeridas para obtener los datos
#require_once("dbi/clfactur.class.php");  // Es el registro maestro de la factura
#require_once("dbi/clflinea.class.php");  // Regisro por partida de la factura
#require_once("dbi/clcadena.class.php");  // Cadenas
#require_once("dbi/clclient.class.php");  // Clientes
#require_once("lib/cn_envio_bodega.php");  // Lista de clientes 'nuestros'
#require_once("lib/cn_envio_export.php");  // Bodegas nuestras extranjero
#require_once("lib/getrefe.php");  
#$require_once("lib/fmtfech.php");          // Cambia de dmy a ymd
global $conn;                            // Conexion adodb a la base de datos
// }}}
// {{{ Inicialice varibles / contadores globales
//error_reporting(E_ALL);
error_reporting(E_ALL ^ E_NOTICE);
/* Lectura de tablas de la base de datos */
//            Facturas
#$fact = new Clfactur($conn,$nufa,'renglon'); // datos de la factura
//            Lineas de la factura, un renglon por producto facturado
#$fali = new Clflinea($conn, $nufa); #datos del cliente, nombre
//           Archivo maestro de clientes
#$clie = new Clclient($conn,(int)$fact->row['factnucl'],'renglon'); #datos del cliente, domicilio
//           Catalogo de cadenas (Domicilio fiscal de Soriana, walmart, etc)
#$cade = new Clcadena($conn,(int)$clie->row['clienuca'],'renglon');# 
$arr = array();
// }}}
// {{{ Encabezados generales 
$arr['folio'] = substr($nufa,4); 
$arr['fecha'] = str_replace(array('-',' ',':'),'',fix_fdoc_arre($fact->row["factfdoc"])); // seleciono la fecha de la factura, desde la base de datos.
$arr['serie'] = substr($nufa,0,4);
//  
// +---------------------------------------------------------------------------+
// | $arr['noAprobacion'] 
// | $arr['anoAprobacion'] 
// | $arr['noCertificado'] 
//require "satxfoli.inc.php"; 
// +---------------------------------------------------------------------------+
//  

//subtotal de la factura = 500
$x_subtotal = 1680.00;
$x_total = 2000.00;

#$arr['subTotal'] = $fact->row["factneto"]+$fact->row['factnet2'];  // Antes de impuestos.. se cambio por constante
$arr['subTotal'] = $x_subtotal;
//$arr['subTotal'] = $fact->row["factimpa"]+$fact->row['factimp2'];  // Antes de impuestos
//$arr['descuento'] = $fact->row["factdecl"]+$fact->row['factdec2'];  // prueba
#$arr['total'] = $fact->row["factimto"];  // Despues de impuestos // se cambio por constante
$arr['total'] = $x_total ;  // Despues de impuestos
$arr['metodoDePago'] = "NO IDENTIFICADO";
#$nuca = $fact->row["factnuca"];  // se cambio por constante
$x_medio_pago = 4;
$nuca = $x_medio_pago ; 
if ( /*cn_envio_bodega*/($fact->row["factnucl"]) && 
     /*!cn_envio_export*/($fact->row["factnucl"])) {
         #
         # Trasalado entre nuestras bodegas
         #
    $arr['tipoDeComprobante'] = "traslado";
    $orig = trim($conn->GetOne("Select bodenomb from clbodega WHERE bodenubo = ".$fact->row["factnubo"]));
    $dest = trim($conn->GetOne("Select bodenomb from clbodega WHERE bodenucl = ".$fact->row["factnucl"]));
    $arr['formaDePago'] = "ESTE COMPROBANTE SE EXPIDE PARA TRANSPORTAR MERCANCIAS DE NUESTRA PROPIEDAD DE BODEGA $orig A BODEGA $dest";
} else {
         #
         # Todas las demas facturas
         #
    $arr['tipoDeComprobante'] = "ingreso";
    $arr['formaDePago'] = "EL PAGO DE ESTA FACTURA (CONTRAPRESTACION) SE EFECTUARA EN UNA SOLA EXHIBICION, SI POR ALGUNA RAZON NO FUERA ASI, EMITIREMOS LOS COMPROBANTES DE LAS PARCIALIDADES RESPECTIVAS";
}
if ($nuca == 1 || $nuca == 2 || $nuca == 3 || $nuca ==4 ||
    $nuca == 5 || $nuca == 6 || $nuca == 7 || $nuca == 8)  $arr['formaDePago'] = "PAGO EN UNA SOLA EXHIBICION";
 
 
$arr['condicionesDePago']="NO IDENTIFICADO";
$arr['TipoCambio']="1.0";
$arr['Moneda']="MXN";
#$arr['LugarExpedicion']=trim($fact->row["factecol"])." ".trim($fact->row["factepob"]);  // se cambio por constante
$arr['LugarExpedicion']= " SAN ANGEL "." "."DISTRITO FEDERAL";
 
 
 
$arr['Emisor']['nombre'] = "FINANCIERA CREA";
$arr['Emisor']['rfc'] = "MCR070419KN2";
$arr['Emisor']['Regimen'] = "REGIMEN GENERAL DE LEY DE PERSONAS MORALES";
/*$arr['Emisor']['ExpedidoEn']['calle'] = $fact->row["factedir"];
$arr['Emisor']['ExpedidoEn']['noExterior'] = $fact->row["facteext"];
$arr['Emisor']['ExpedidoEn']['noInterior'] = $fact->row["facteint"];
$arr['Emisor']['ExpedidoEn']['localidad'] = substr($fact->row["factepob"],6);
$arr['Emisor']['ExpedidoEn']['municipio'] = substr($fact->row["factepob"],6);
$arr['Emisor']['ExpedidoEn']['estado'] = get_estado($fact->row["factepob"]);
$arr['Emisor']['ExpedidoEn']['pais'] = "MEXICO";
$arr['Emisor']['ExpedidoEn']['codigoPostal'] = substr($fact->row["factepob"],0,5);*/

$arr['Emisor']['ExpedidoEn']['calle'] = "AVENIDA REVOLUCION";
$arr['Emisor']['ExpedidoEn']['noExterior'] = "1909";
$arr['Emisor']['ExpedidoEn']['noInterior'] = "9";
$arr['Emisor']['ExpedidoEn']['localidad'] = "SAN ANGEL";
$arr['Emisor']['ExpedidoEn']['municipio'] = "ALVARO OBREGON";
$arr['Emisor']['ExpedidoEn']['estado'] = "DISTRITO FEDERAL";
$arr['Emisor']['ExpedidoEn']['pais'] = "MEXICO";
$arr['Emisor']['ExpedidoEn']['codigoPostal'] = "01000";
 
 
#$arr['Receptor']['nombre'] = $fact->row["factnopr"]; 
$x_nombre_cliente = "Zulma Ortiz Anzurez";
$arr['Receptor']['nombre'] = $x_nombre_cliente;
// Usa tabla ISO8859-2
if ($nuca == 29)
    $arr['Receptor']['nombre'] = "ORGANIZACI".chr(211)."N SAHUAYO, S.A. DE C.V.";
$arr['Receptor']['rfc'] = $fact->row["factrfca"];
 
if (strlen(trim($cade->row['cadecall']))) {
    $a_edo = $cade->row["cadecoes"];
    $a_calle = $cade->row["cadecall"];
    $a_noExterior = $cade->row["cadenext"];
    $a_noInterior = $cade->row["cadenint"];
    $a_colonia = $cade->row["cadecolo"];
    $a_localidad = $cade->getPueb("cadecoes","cademuni","cadepobl");
    $a_municipio = $cade->getMuni("cadecoes","cademuni");
    $a_codigoPostal = str_pad($cade->row["cadecodp"],5,'0',STR_PAD_LEFT);
} else {
    /*$a_edo = $fact->row["factedoe"];
    $a_calle = $fact->row["factcdir"];
    $a_noExterior = $fact->row["factnext"];
    $a_noInterior = $fact->row["factnint"];
    $a_colonia = $fact->row["factccol"];
	$a_localidad = $clie->getPueb("cliecoes","cliemuni","cliepueb");
    $a_municipio = $clie->getMuni("cliecoes","cliemuni");
    $a_codigoPostal = str_pad($fact->row["factcodp"],5,'0',STR_PAD_LEFT);
	
	*/ // se cambio a constantes
	$a_edo = $x_estado_cliente;
    $a_calle = $x_calle_cliente;
    $a_noExterior = $x_no_ext_cliente;
    $a_noInterior = $x_no_int_cliente;
    $a_colonia = $x_colonia_cliente;
    // 25/10/2011 Para que lo tome de donde debe
    // $a_localidad = $fact->row["factnpue"];
    // $a_municipio = $fact->row["factnpue"];
    $a_localidad = $x_localidad_cliente;
    $a_municipio = $x_municipio_cliente;
    $a_codigoPostal = $x_codigo_postal_cliente;
	$edo = "MORELOS";
	$pais = "MEXICO";
}
 
if ($nuca == 29) $a_edo = $clie->row["cliecoes"];
if ($nuca == 29) $a_municipio = $clie->getPueb("cliecoes","cliemuni","cliepueb");
if ($nuca == 29) $a_colonia = satxarre_fix($fact->row["factccol"],35);
//$edo = trim(getrefe("cte",6,"CLIECOES",$a_edo));
//$pais = ($a_edo>40) ? $edo : "MEXICO";
$arr['Receptor']['Domicilio']['calle'] = $a_calle;
$arr['Receptor']['Domicilio']['noExterior'] = $a_noExterior;
$arr['Receptor']['Domicilio']['noInterior'] = $a_noInterior;
$arr['Receptor']['Domicilio']['colonia'] = $a_colonia;
$arr['Receptor']['Domicilio']['localidad'] = $a_localidad;
$arr['Receptor']['Domicilio']['municipio'] = $a_municipio;
$arr['Receptor']['Domicilio']['estado'] = $edo;
$arr['Receptor']['Domicilio']['pais'] = $pais;
$arr['Receptor']['Domicilio']['codigoPostal'] = $a_codigoPostal;
// }}}
// {{{  Datos para el esquema de detalllista solo si hace falta
// +--------------------------------------------------------------------+
// | Lee los datos de npec, fpec, gln solo si quieren complemento       |
// +--------------------------------------------------------------------+
//
if ($addenda=="detallista") {
    $arr['Complemento']['npec'] = $fact->row['factnpec'];
    $arr['Complemento']['fpec'] = $fact->row['factfpec'];
    $arr['Complemento']['gln'] = str_pad($fact->row['factnucl'],13,'0',STR_PAD_LEFT);
}
// }}}
// {{{  Diconsa
// +--------------------------------------------------------------------+
// | Lee los datos de almacen solo si es diconsa                        |
// +--------------------------------------------------------------------+
//
if ($addenda=="diconsa") {
    $arr['diconsa']['proveedor'] = 217;
    $arr['diconsa']['almacen'] = trim($clie->row['cliesucu']);
    $arr['diconsa']['negociacion'] = trim($fact->row['factnpec']);
    $arr['diconsa']['pedido'] = 0;
}
// }}}
// {{{  IMSS
// +--------------------------------------------------------------------+
// | Lee los datos de almacen solo si es imss                           |
// +--------------------------------------------------------------------+
//
if ($addenda=="imss") {
    $sucu = (int)$clie->row["cliezovo"];
    $sucu = str_pad($sucu, 5, "0", STR_PAD_LEFT);
    $arr['imss']['proveedor'] = "0000029727";
    $arr['imss']['delegacion'] = $sucu;
    $arr['imss']['conceptodocumento'] = "ORIGINAL";
    $arr['imss']['documento'] = "FACTURA";
    $arr['imss']['moneda'] = "MXN";
    $arr['imss']['transaccion'] = "FACTURACION";
    $arr['imss']['cambio'] = "1.00";
    $arr['imss']['concepto'] = "TN";
    $arr['imss']['pedido'] = trim($fact->row['factnpec']);
    $arr['imss']['recepcion'] = trim($conn->getone("select corenpec from clcorevi where coredocu = '$nufa'"));
    $arr['imss']['serie'] = "N/A";
}
// }}}
// {{{   Para cada linea/partida de la factura (para cada producto
// +--------------------------------------------------------------------+
// | AHora si procesa la ocurrencia de productos de la factura          |
// +--------------------------------------------------------------------+
//
for ($i=0; $i<sizeof($fali->faliprod); $i++) {
    $cant = $fali->faliunif[$i];
    $unid = get_unidad($fali->faliprod[$i]);
    $impo = $fali->falineto[$i];
    if ($arr['serie']=='FIVA' && $fact->row["factrfca"]!="XAXX010101000")
        $arr['Conceptos'][$i+1]['descripcion'] = $fact->row['factobs1'].' '.
                                                 $fact->row['factobs2'].' '.
                                                 $fact->row['factobs3'];
    else
    $arr['Conceptos'][$i+1]['descripcion'] = $fali->falideco[$i];
    $arr['Conceptos'][$i+1]['cantidad'] = $cant;
    $arr['Conceptos'][$i+1]['unidad']=$unid;
    $arr['Conceptos'][$i+1]['noIdentificacion']=$fali->falicbar[$i];
    $si = (strcmp(fmtfech(substr($fact->row["factfdoc"],0,10)),'2012-01-09')>=0)?'t':'f';
    $decimales = ($si=="t") ? 6 : 2;
    $prun = round((double)$impo / (double)$cant,$decimales);
    $arr['Conceptos'][$i+1]['valorUnitario'] = $prun;
    $arr['Conceptos'][$i+1]['importe'] = $impo;
    if ($addenda=="detallista") {
        $arr['Conceptos'][$i+1]['poim'] = $fali->falipoim[$i];
        $arr['Conceptos'][$i+1]['impu'] = $fali->faliimpu[$i];
        $arr['Conceptos'][$i+1]['gtin'] = $fali->falicbar[$i];
        $arr['Conceptos'][$i+1]['prun'] = $prun;
        $arr['Conceptos'][$i+1]['neto'] = $fali->falineto[$i];
    }
}

$arr['Conceptos'][1]['descripcion'] = "descripcion del concepto";
$arr['Conceptos'][1]['cantidad'] = 10;
$arr['Conceptos'][1]['unidad']= "unidad";
$arr['Conceptos'][1]['noIdentificacion'] = "no indentificacion";
$arr['Conceptos'][1]['importe'] = 6000;
$arr['Conceptos'][1]['valorUnitario'] = 200;
// }}}
// {{{ Finaliza el arreglo
$arr['Traslados']['impuesto'] = "IVA";
#$arr['Traslados']['tasa'] = $fact->row["factpoim"];
$arr['Traslados']['tasa'] = 1;
#$arr['Traslados']['importe'] = $fact->row["factimpu"];
$arr['Traslados']['importe'] = 6010;
return($arr);
}
// }}}
// {{{ get_unidad : lee la descripcion de undiad del document del producto cajas, pzas, etc
function get_unidad($prod) {
global $conn;
$desc = trim($conn->getone("select docurefe from document, clproduc where prodprod=$prod and docunuli = produven and docucvsi = 6 and docutire = 'PRODUVEN'"));
if ($desc=="") $desc="Cajas";
    $conn->debug=false;
return($desc);
}
// }}}
// {{{ fix_fdoc_arre : Cuando el timestamp viene dd/mm/yyyy lo convierte a yyyy-mm-dd
function fix_fdoc_arre($fdoc) {
    if (strpos($fdoc,"/")!==FALSE) { // tiene diagonales viene dd/mm/yyyy hh:mm
        list($f,$h)=explode(" ",$fdoc);
        list($d,$m,$y)=explode("/",$f);
        $fdoc = "$y-$m-$d $h";
    }
    return ($fdoc);
}
// }}}
// {{{ 'Busca' el estado dentro del campo
function get_estado($esta) {
    $coes="XX";
    if (stristr($esta,"AGUAS")) $coes="AGUASCALIENTES";
    if (stristr($esta,"MEXICALI")) $coes="BAJA CALIFORNIA";
    if (stristr($esta,"B.C.S.")) $coes="BAJA CALIFORNIA SUR";
    if (stristr($esta,"CAMPE")) $coes="CAMPECHE";
    if (stristr($esta,"TUXTLA")) $coes="CHIAPAS";
    if (stristr($esta,"TAPACHULA")) $coes="CHIAPAS";
    if (stristr($esta,"CHIH")) $coes="CHIHUAHUA";
    if (stristr($esta,"COAH")) $coes="COAHUILA";
    if (stristr($esta,"COLIMA")) $coes="COLIMA";
    if (stristr($esta,"D.F.")) $coes="DISTRITO FEDERAL";
    if (stristr($esta,"DURANGO")) $coes="DURANGO";
    if (stristr($esta,"IRAPUATO")) $coes="GUANAJUATO";
    if (stristr($esta,"ACAPULCO")) $coes="GUERRERO";
    if (stristr($esta,"HIDALGO")) $coes="HIDALGO";
    if (stristr($esta,"GUADALAJARA")) $coes="JALISCO";
    if (stristr($esta,"XALOSTOC")) $coes="EDO. DE MEXICO";
    if (stristr($esta,"MORELIA")) $coes="MICHOACAN";
    if (stristr($esta,"UNDAMEO MICH")) $coes="MICHOACAN";
    if (stristr($esta,"MORELOS")) $coes="MORELOS";
    if (stristr($esta,"NAYARIT")) $coes="NAYARIT";
    if (stristr($esta,"GARZA")) $coes="NUEVO LEON";
    if (stristr($esta,"OAX")) $coes="OAXACA";
    if (stristr($esta,"PUEBLA")) $coes="PUEBLA";
    if (stristr($esta,"QUERETARO")) $coes="QUERETARO";
    if (stristr($esta,"QUINTANA")) $coes="QUINTANA ROO";
    if (stristr($esta,"SAN LUIS")) $coes="SAN LUIS POTOSI";
    if (stristr($esta,"CULIACAN")) $coes="SINALOA";
    if (stristr($esta,"HERMOSILLO")) $coes="SONORA";
    if (stristr($esta,"VILLAHERMOSA")) $coes="TABASCO";
    if (stristr($esta,"ALTAMIRA")) $coes="TAMAULIPAS";
    if (stristr($esta,"TLAXCALA")) $coes="TLAXCALA";
    if (stristr($esta,"PARAJE NUEVO")) $coes="VERACRUZ";
    if (stristr($esta,"UMAN")) $coes="YUCATAN";
    if (stristr($esta,"ZACATECAS")) $coes="ZACATECAS";
    return ($coes);
}
// }}}
// {{{ Convierte el caracter especial a char(209)
function satxarre_fix($str,$largo=-1) {
    $tmp = trim($str);
    $tmp = str_replace(array("}","{"),chr(209),$tmp);
    if ($largo>0) $tmp = substr($tmp,0,$largo-1);
    return ($tmp);
                }
// }}}
?>
