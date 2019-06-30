<?php





function generaXml($x_factura_id, $x_sello, $certificado ,$conn){
	
	$x_factura = $x_factura_id ;
	$x_sello = $x_sello;
	$conn = $conn;
	

	$sqlFactura = "SELECT * FROM factura WHERE factura_id = $x_factura ";
	$rsFactura = phpmkr_query($sqlFactura,$conn) or die ("Error al seleccionar la factura en la genracion del xml". phpmkr_error()."Query:".$sqlFactura);
	$rowFactura = phpmkr_fetch_array($rsFactura);
	$x_cliente_id  = $rowFactura["cliente_id"];
	$x_fecha_generacion = $rowFactura["fecha_generacion"];
	$x_folio = $rowFactura["folio"];
	$x_sub_total_XML = $rowFactura["sub_total"];
	$x_total_XML = $rowFactura["total"];
	$x_forma_pago  = limpiaTexto($rowFactura["forma_pago"]);
	$x_metodo_pago = limpiaTexto($rowFactura["metodo_pago"]);
	$x_tipo_comprobante  = limpiaTexto($rowFactura["tipo_comprobante"]);
	$x_total_iva = ($x_total_XML - $x_sub_total_XML);
	$x_importe_iva = $x_total_iva;
	#echo "XML SUBTOTAL XML*****".$x_sub_total_XML."<BR>***";
	mysql_free_result($rsFactura);
	
	$sqlCliente = "SELECT * FROM factura_datos WHERE factura_id = $x_factura " ;
	$rsCliente = phpmkr_query($sqlCliente, $conn) or die ("Error al seleccionar los datos del cliente en la genracion del xml". phpmkr_error()."Query:".$sqlCliente);
	$rowCliente = phpmkr_fetch_array($rsCliente);
	$x_cliente_id = $rowCliente["cliente_id"];
	$x_nombre = limpiaTexto($rowCliente["nombre"]);
	$x_paterno = limpiaTexto($rowCliente["paterno"]);
	$x_materno = limpiaTexto($rowCliente["materno"]);
	$x_rfc = limpiaTexto($rowCliente["rfc"]);
	$x_calle = limpiaTexto($rowCliente["calle"]);
	$x_colonia = limpiaTexto($rowCliente["colonia"]);
	$x_numero = limpiaTexto($rowCliente["numero"]);
	$x_delegacion = limpiaTexto($rowCliente["delegacion"]);
	$x_estado = limpiaTexto($rowCliente["estado"]);
	$x_codigo_postal = limpiaTexto($rowCliente["codigo_postal"]);
	$x_nombre_completo = $x_nombre." ".$x_paterno." ".$x_materno;
	$x_nombre_completo = limpiaTexto($x_nombre_completo);
	mysql_free_result($rsCliente);
	
	$sqlPagos = "SELECT * FROM factura_pago WHERE factura_id = $x_factura";
	$rsPagos = phpmkr_query($sqlPagos,$conn) or die ("Error al seleccionar los datos de los pagos en la genracion del xml". phpmkr_error()."Query:".$sqlPagos);
	$x_cont = 0;
	while($rowPagos = phpmkr_fetch_array($rsPagos)){
		$x_cont ++;
		// mientras hay pagos se genera un renglon del xml por cada pago.
		$GLOBALS["x_recibo_id_$x_cont"] = $rowPagos["recibo_id"];
		${"x_cantidad_".$x_cont} = $rowPagos["cantidad"];
		${"x_unidad_".$x_cont} = $rowPagos["unidad"];
		${"x_descripcion_".$x_cont} = limpiaTexto($rowPagos["descripcion"]);
		${"x_valor_unitario_".$x_cont} = $rowPagos["valor_unitario"];
		${"x_importe_".$x_cont} = $rowPagos["importe"];
		${"x_impuesto_".$x_cont} = $rowPagos["impuesto"];
		${"x_importe_impuesto_".$x_cont} = $rowPagos["importe_impuesto"];
		${"x_tasa_".$x_cont} = $rowPagos["tasa"];
		}
		echo "total de pagos++++++++++++>".$x_cont."<br>";
		$GLOBALS["x_total_pagos"] = $x_cont;
		$x_factura_certificado_id = 1;
		echo "x_valor_unitario_".$x_valor_unitario_1."************************************<br>";
	mysql_free_result($rsPagos);
	
	$sqlCertificado = "SELECT * FROM factura_certificado  WHERE factura_certificado_id = $x_factura_certificado_id";
	$rsCertificado = phpmkr_query($sqlCertificado, $conn) or die ("Error al seleccionar los datos de los pagos en la genracion del xml". phpmkr_error()."Query:".$sqlCertificado);
	$rowCertificado = phpmkr_fetch_array($rsCertificado);
	$x_rfc_contribuyente = $rowCertificado["rfc_contribuyente"];
	$x_numero_aprobacion = $rowCertificado["numero_aprobacion"];
	$x_anio_aprobacion = $rowCertificado["anio_aprobacion"];
	$x_serie_certificado = $rowCertificado["serie_certificado"];
	$x_serie = $rowCertificado["serie"];
	$x_fecha_transaccion = $rowCertificado["fecha_transaccion"];
	$x_hora_transaccion = $rowCertificado["hora_transaccion"];
	$x_hora_transaccion = $rowCertificado["hora_transaccion"];
	$x_hora_transaccion = $rowCertificado["hora_transaccion"];
	$x_hora_transaccion = $rowCertificado["hora_transaccion"];
	$x_fecha_T = 	$x_fecha_transaccion."T".$x_hora_transaccion;
	
	mysql_free_result($rsCertificado);
	//generamos el XML
	
//NODO BASE	

#metodoDePago  ...................Atributo opcional de texto libre para expresar el método de pago de los bienes o servicios amparados por el comprobante. Se entiende como método de pago leyendas tales como: cheque, tarjeta de crédito o debito, depósito en cuenta, etc.
#subTotal ....Atributo requerido para representar la suma de los importes antes de descuentos e impuestos.
#total........Atributo requerido para representar la suma del subtotal, menos los descuentos aplicables, más los impuestos trasladados, menos los impuestos retenidos
$x_file_xml ='<?xml version="1.0" encoding="utf-8" ?>';	
$x_file_xml .= "<Comprobante ";
$x_versio = "2.0";
$x_file_xml .= 'version="2.0" ';
$x_file_xml .= 'serie="'.$x_serie.'" '; 
$x_file_xml .= 'folio="'.$x_folio.'" ';
$x_file_xml .= 'fecha="'.$x_fecha_T.'" ' ;//02/14/12 17:21:00 
$x_file_xml .= 'sello="'.$x_sello."\"";
$x_file_xml .= ' noCertificado="'.$x_serie_certificado.'"';
$x_file_xml .= " certificado=\"".$certificado."\" ";
$x_file_xml .= 'subTotal="'.$x_sub_total_XML.'" ';
$x_file_xml .= 'total="'.$x_total_XML.'" ';
$x_file_xml .= 'noAprobacion="'.$x_numero_aprobacion.'" ';
$x_file_xml .= 'anoAprobacion="'.$x_anio_aprobacion.'" ';
$x_file_xml .= "formaDePago=\"".$x_forma_pago."\" ";
#$x_file_xml .= 'metodoDePago="'.$x_metodo_pago.'" ';
$x_file_xml .= 'tipoDeComprobante="'.$x_tipo_comprobante.'" ';
$x_file_xml .= 'xsi:schemaLocation="http://www.sat.gob.mx/cfd/2 http://www.sat.gob.mx/sitio_internet/cfd/2/cfdv2.xsd" ';
$x_file_xml .= 'xmlns="http://www.sat.gob.mx/cfd/2" ';
$x_file_xml .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"';
$x_file_xml .='>' ;
	
//NODO DEL EMISOR....
$x_file_xml .= "<Emisor ";
$x_file_xml .= 'rfc="'.$x_rfc_contribuyente.'" ';
$x_file_xml .= 'nombre="Microfinanciera Crece, S.A. de C.V., SOFOM, E.N.R."';
$x_file_xml .= ">";
$x_file_xml .= "<DomicilioFiscal ";
$x_file_xml .= 'calle="AVENIDA REVOLUCION" ';
$x_file_xml .= 'noExterior="1909" ';
$x_file_xml .= 'noInterior="2" ';
$x_file_xml .= 'colonia="SAN ANGEL" ';
$x_file_xml .= 'municipio="ALVARO OBREGON" ';
$x_file_xml .= 'estado="DISTRITO FEDERAL" ';
$x_file_xml .= 'pais="MEXICO" ';
$x_file_xml .= 'codigoPostal="01000"';
$x_file_xml .= '/>';
$x_file_xml .= '</Emisor>';
	
//NODO DEL RECEPTOR
#calle ........requerido
#noExterior ...opcional
#colonia ......opcional
#municipio.....requerido
#estado........requerido
#pais..........requerido
#codigoPostal..requerido
$x_file_xml .= '<Receptor ';
$x_file_xml .= 'rfc="'.$x_rfc.'" ';
$x_file_xml .= 'nombre="'.$x_nombre_completo.'"';
$x_file_xml .= '>';
$x_file_xml .= '<Domicilio ';
$x_file_xml .= 'calle="'.$x_calle.'" ';
$x_file_xml .= 'noExterior="'.$x_numero.'" ';
$x_file_xml .= 'colonia="'.$x_colonia.'" ';
$x_file_xml .= 'municipio="'.$x_delegacion.'" ';
$x_file_xml .= 'estado="'.$x_estado.'" ';
$x_file_xml .= 'pais="MEXICO" ';
$x_file_xml .= 'codigoPostal="'.$x_codigo_postal.'"';
$x_file_xml .= '/>';
$x_file_xml .='</Receptor>';


//NODO CONCEPTOS
#cantidad .......Atributo requerido para precisar la cantidad de bienes o servicios del tipo particular definido por el presente concepto
#unidad .........Atributo opcional para precisar la unidad de medida aplicable para la cantidad expresada en el concepto.
#descripcion.....Atributo requerido para precisar la descripción del bien o servicio cubierto por el presente concepto
#valorUnitario...Atributo requerido para precisar el valor o precio unitario del bien o servicio cubierto por el presente concepto.
#importe.........Atributo requerido para precisar el importe total de los bienes o servicios del presente concepto. Debe ser equivalente al resultado de multiplicar la cantidad por el valor unitario expresado en el concepto.

$x_file_xml .= '<Conceptos>';
$x_cuenta = 1;
while($x_cuenta <= $x_cont){
// un renglon por pago generado en el mes correspondiente.........
$x_cantidad_n = "x_cantidad_$x_cuenta";
$x_cantidad = $$x_cantidad_n;
$x_unidad_n = "x_unidad_$x_cuenta";
$x_unidad = $$x_unidad_n;
$x_descripcion_n = "x_descripcion_$x_cuenta";
$x_descripcion = $$x_descripcion_n;
$x_valor_unitario_n = "x_valor_unitario_$x_cuenta";
$x_valor_unitario = $$x_valor_unitario_n;
$x_importe_n = "x_importe_$x_cuenta";
$x_importe = $$x_importe_n;

$x_file_xml .= '<Concepto cantidad="'.$x_cantidad.'" unidad="'.$x_unidad.'" descripcion="'.$x_descripcion.'" valorUnitario="'.$x_valor_unitario.'" importe="'.$x_importe.'"/>';

$x_cuenta++;
}
$x_file_xml .= '</Conceptos>';


//NODO IMPUESTOS
$x_file_xml .= '<Impuestos totalImpuestosRetenidos="'.$x_total_iva.'">';
$x_file_xml .= '<Retenciones>';
$x_file_xml .= '<Retencion impuesto="IVA" importe="'.$x_importe_iva.'" />'; //tasa="16.00" en el de traslado si lleva ese valor en retencion no aplica
$x_file_xml .= '</Retenciones>';
$x_file_xml .= '</Impuestos>';
$x_file_xml .= '</Comprobante>';

//ternianmos de crear el xml
$x_fecha_actual = date("Y-m-d");
//limpiamos segun las reglas de requerimientos tecnicos de cfd
$x_no_ale = rand(100, 1000);
$x_nn = str_replace(" ", "_",$x_nombre);
$x_nombre_cfd = "CFD_".$x_fecha_actual."_".$x_nn."_".$x_paterno."_".$x_materno."_".$x_no_ale.".xml";
$file=fopen("CFDS/XML/$x_nombre_cfd","w+");
fwrite ($file,$x_file_xml);
fclose($file);

echo "cfd".$x_nombre_cfd.".............<br>";

	#INSERTAMOS EL ARCHIVO EN LA TABLA
	$sqlxml = "INSERT INTO factura_cfd (`factura_cfd_id`, `factura_id`, `archivo`, `fecha`) VALUES (NULL, $x_factura,'$x_nombre_cfd', '$x_fecha_actual');";
	$rsxml = phpmkr_query($sqlxml, $conn) or die("Error al seleccionar".phpmkr_error()."sql :".$sqlxml);
	if($rsxml){
		echo "sen inserto en xml";
		}	
	
	}// fin funcion main






// generamos  sello digital



function limpiaTexto($x_texto){
	
	$x_text = $x_texto;
	$x_text = preg_replace('/\s\s+/', ' ', $x_text); // regla 5a y 5c
	$x_text = str_replace('|', ' ', $x_text); //regla 1
	$x_text = str_replace('  ', ' ', $x_text); // regla 5c
	$x_text = str_replace('&', '&amp;', $x_text); //regla xml
	$x_text = str_replace('"', '&quot;', $x_text); //regla xml
	$x_text = str_replace('<', '&lt;', $x_text); // regla xml
	$x_text = str_replace('>', '&gt;', $x_text); //regla xml
	$x_text = str_replace("'", '&apos;', $x_text); //regla xml
	$x_text = str_replace('|', ' ', $x_text); // regla 1
	$x_text = str_replace('á', 'a', $x_text);
	$x_text = str_replace('Á', 'A', $x_text);
	$x_text = str_replace('é', 'e', $x_text);
	$x_text = str_replace('É', 'E', $x_text);
	$x_text = str_replace('í', 'i', $x_text);
	$x_text = str_replace('Í', 'I', $x_text);
	$x_text = str_replace('ó', 'o', $x_text);
	$x_text = str_replace('Ó', 'O', $x_text);
	$x_text = str_replace('ú', 'u', $x_text);
	$x_text = str_replace('Ú', 'U', $x_text);
	$x_text = trim($x_text); //regla 5b	
	return $x_text;
	}



?>