
<?php
#GENERACION DE LA CADENA ORIGINAL DE LA FACTURA PARA PODER SER SELLADA POSTERIORMENTE

function generaCadenaOriginal($x_factura_id, $conn,  $x_mes, $x_anio){
	
	$x_factura = $x_factura_id ;
	$conn = $conn;
	$mes = $x_mes;
	$anio = $x_anio;
	
	// sleeccionamos los datos de la factura para generar la cadena original 
	
	$x_cadena_original = "";
	$sqlFactura = "SELECT * FROM factura WHERE factura_id = $x_factura ";
	$rsFactura = phpmkr_query($sqlFactura,$conn) or die ("Error al seleccionar la factura en la genracion del xml". phpmkr_error()."Query:".$sqlFactura);
	$rowFactura = phpmkr_fetch_array($rsFactura);
	$x_cliente_id  = $rowFactura["cliente_id"];
	$x_fecha_generacion = $rowFactura["fecha_generacion"];
	$x_folio = $rowFactura["folio"];
	$x_sub_total_XML = $rowFactura["sub_total"];
	$x_total_XML = $rowFactura["total"];
	$x_forma_pago  = limpiaCadena($rowFactura["forma_pago"]);
	$x_metodo_pago = limpiaCadena($rowFactura["metodo_pago"]);
	$x_tipo_comprobante  = limpiaCadena($rowFactura["tipo_comprobante"]);
	$x_total_iva = ($x_total_XML - $x_sub_total_XML);
	$x_importe_iva = $x_total_iva;
	mysql_free_result($rsFactura);
	
	
	$sqlCliente = "SELECT * FROM factura_datos WHERE factura_id = $x_factura " ;
	$rsCliente = phpmkr_query($sqlCliente, $conn) or die ("Error al seleccionar los datos del cliente en la genracion del xml". phpmkr_error()."Query:".$sqlCliente);
	$rowCliente = phpmkr_fetch_array($rsCliente);
	$x_cliente_id = $rowCliente["cliente_id"];
	$x_nombre = limpiaCadena($rowCliente["nombre"]);
	$x_paterno = limpiaCadena($rowCliente["paterno"]);
	$x_materno = limpiaCadena($rowCliente["materno"]);
	$x_rfc = limpiaCadena($rowCliente["rfc"]);
	$x_calle = limpiaCadena($rowCliente["calle"]);
	$x_colonia = limpiaCadena($rowCliente["colonia"]);
	$x_numero = limpiaCadena($rowCliente["numero"]);
	$x_delegacion = limpiaCadena($rowCliente["delegacion"]);
	$x_estado = limpiaCadena($rowCliente["estado"]);
	$x_codigo_postal = limpiaCadena($rowCliente["codigo_postal"]);
	$x_nombre_completo = $x_nombre." ".$x_paterno." ".$x_paterno;
	$x_nombre_completo = limpiaCadena($x_nombre_completo);
	
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
		${"x_descripcion_".$x_cont} = limpiaCadena($rowPagos["descripcion"]);
		${"x_valor_unitario_".$x_cont} = $rowPagos["valor_unitario"];
		${"x_importe_".$x_cont} = $rowPagos["importe"];
		${"x_impuesto_".$x_cont} = $rowPagos["impuesto"];
		${"x_importe_impuesto_".$x_cont} = $rowPagos["importe_impuesto"];
		${"x_tasa_".$x_cont} = $rowPagos["tasa"];
		}
		
		$GLOBALS["x_total_pagos"] = $x_cont;
		$x_factura_certificado_id = 1;
		
		
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
	$x_fecha_T = $x_fecha_transaccion."T".$x_hora_transaccion;
	$x_fecha_R = $x_fecha_transaccion." ".$x_hora_transaccion;
	
	mysql_free_result($rsCertificado);
	
	
	
	$x_cadena_original = "||";
	$x_cadena_original .= "2.0"; // version 
	$x_cadena_original .= "|$x_serie";
	$x_cadena_original .= "|$x_folio";
	$x_cadena_original .= "|$x_fecha_T";
	$x_cadena_original .= "|$x_numero_aprobacion";
	$x_cadena_original .= "|$x_anio_aprobacion";
	$x_cadena_original .= "|ingreso"; //tipoDeComprobante
	$x_cadena_original .= "|$x_forma_pago";
	#$x_cadena_original .= "|$x_metodo_pago";
	$x_cadena_original .= "|$x_sub_total_XML";
	$x_cadena_original .= "|$x_total_XML";
	$x_cadena_original .= "|$x_rfc_contribuyente";
	$x_cadena_original .= "|FINANCIERA CREA";
	$x_cadena_original .= "|AVENIDA REVOLUCION";
	$x_cadena_original .= "|1909";
	$x_cadena_original .= "|2";
	$x_cadena_original .= "|SAN ANGEL";
	$x_cadena_original .= "|ALVARO OBREGON";
	$x_cadena_original .= "|DISTRITO FEDERAL";
	$x_cadena_original .= "|MEXICO";
	$x_cadena_original .= "|01000";
	$x_cadena_original .= "|$x_rfc";
	$x_cadena_original .= "|$x_nombre_completo";
	$x_cadena_original .= "|$x_calle";
	$x_cadena_original .= "|$x_numero";
	$x_cadena_original .= "|$x_colonia";
	$x_cadena_original .= "|$x_delegacion";
	$x_cadena_original .= "|$x_estado";
	$x_cadena_original .= "|MEXICO";
	$x_cadena_original .= "|$x_codigo_postal";
	
	$x_cuenta = 1;
	//UN REGISTRO POR CADA PAGO
	while($x_cuenta <= $x_cont){
		
	//sacamos los valores de las variables	
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
		
	$x_cadena_original .= "|$x_cantidad";	
	$x_cadena_original .= "|$x_unidad";
	$x_cadena_original .= "|$x_descripcion";
	$x_cadena_original .= "|$x_valor_unitario";
	$x_cadena_original .= "|$x_importe";
	$x_cuenta++;
	}
	
	$x_cadena_original .= "|IVA";
	$x_cadena_original .= "|$x_importe_iva";
	$x_cadena_original .= "|$x_total_iva";
	$x_cadena_original .= "||"; // fin de la cadena original
	 
	 
	$x_cadena_original = utf8_encode($x_cadena_original);	
	//segumos con la cadena original
	
	
	#creamos el archivo para el reporte mensual 
	$x_ms =  $mes;
	$x_ao = $anio;
	
	$_x_tot = substr ($x_total_XML, 0, strlen($x_total_XML) - 1);
	$_x_iva = substr ($x_total_iva, 0, strlen($x_total_iva) - 1);
	if(($_x_iva <0)){
		$_x_iva = "";
		}


	$x_nombre_archivo = "1".$rcf_contibuyente.$mes.$x_ao.".txt";
	$x_registro_actual .= "|$x_rfc";
	$x_registro_actual .= "|$x_serie";
	$x_registro_actual .= "|$x_folio";
	$x_registro_actual .= "|$x_numero_aprobacion";
	$x_registro_actual .= "|$x_fecha_R";
	$x_registro_actual .= "|$_x_tot";
	$x_registro_actual .= "|$_x_iva";
	$x_registro_actual .= "|1";
	$x_registro_actual .= "|I";
	$x_registro_actual .= "|";
	$x_registro_actual .= "|";
	$x_registro_actual .= "|\r\n";

	
	
	//abrimos el archivo y le conactenamos el registro actual......
	$file_t =fopen("CFDS/REPORTES_MENSUALES/$x_nombre_archivo","a+");
	fwrite($file_t, $x_registro_actual);
	fclose($file_t);
	return $x_cadena_original;
	}




function limpiaCadena($x_texto){
	
	$x_text = $x_texto;
	$x_text = preg_replace('/\s\s+/', ' ', $x_text); // regla 5a y 5c
	$x_text = str_replace('|', ' ', $x_text); //regla 1
	$x_text = str_replace('  ', ' ', $x_text); // regla 5c
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