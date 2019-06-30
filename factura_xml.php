<?php
function generaXmlNew($x_factura_id,$conn){
	
	// SACAMOS LOS DATOS DE LAS TABLAS
	$x_factura = $x_factura_id ;
	$x_sello = $x_sello;
	$conn = $conn;
	$X_FECHA_CAR = date("Y-m-d");
	$x_t_d = trim(date("Y-m-d").'T'.date("H:i:s"));
	 
	
#echo "id".$x_factura ."<br>";
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
	#	echo "total de pagos++++++++++++>".$x_cont."<br>";
		$GLOBALS["x_total_pagos"] = $x_cont;
		$x_factura_certificado_id = 1;
	#	echo "x_valor_unitario_".$x_valor_unitario_1."************************************<br>";
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
	
	
	
	
	
	
	
	
	//datos de la empresa que factura
$rfc_emisor= $x_rfc_contribuyente;
$razon_social_emisor='Microfinanciera Crece, S.A. de C.V., SOFOM, E.N.R.';
$calle_emisor='AVENIDA REVOLUCION';
$num_exterior_emisor='1909';
$colonia_emisor='San Angel';
$municipio_emisor='ALVARO OBREGON';
$estado_emisor='Distrito Federal';
$codigo_postal_emisor='01000';
$pais_emisor='México';

$ar=fopen("certificados/certificado.txt","r") or die("No se pudo abrir el archivo");

while (!feof($ar))
	  {
		$certificado_texto.= fgets($ar);
	  }
fclose($ar);
	
	$x_fecha = date("Y-m-d");
		
//recupero las variables
$forma_pago=trim($x_forma_pago);
$tipo_cfd=trim($x_tipo_comprobante);
trim($fecha=$x_fecha);
$aprobacion=trim($x_anio_aprobacion);
$year_aprobacion=trim($x_anio_aprobacion);
$serie=trim($x_serie ); $folio=trim($x_folio); 
$dias_credito=0; 
$iva=16;
$num_certificado=trim($x_serie_certificado);
	
$rfc=trim($x_rfc);
$razon_social=trim($x_nombre_completo);
$calle= trim($x_calle);
$num_exterior= trim($x_numero);
//$num_interior= trim($_REQUEST[num_interior]);
$colonia= trim($x_colonia);
$localidad=trim($x_delegacion);
$municipio=trim($x_delegacion);
$estado=trim($x_estado);
$pais="Mexico";
$codigo_postal=trim($x_codigo_postal); 
//$referencia=trim($_REQUEST[referencia]);
	/*$x_conta = 1;
	while($x_conta <= $x_cont ){
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
	
${"d_".$x_conta} =trim($x_cantidad); ${"precio_".$x_conta}=trim($x_valor_unitario); ${"cantidad_".$x_conta}=trim($x_cantidad); ${"unidad1_".$x_conta}=$x_unidad;

${"monto_".$x_conta} = ${"precio_".$x_conta} * ${"cantidad_".$x_conta};

$x_conta++;	
}*/


$x_conta = 1;
	while($x_conta <= $x_cont ){
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

$x_conta++;
	}

$d1=trim($x_descripcion_1); $precio1=trim($x_valor_unitario_1); $cantidad1=trim($x_cantidad_1 ); $unidad1=$x_unidad_1;
$d2=trim($x_descripcion_2); $precio2=trim($x_valor_unitario_2); $cantidad2=trim($x_cantidad_2 ); $unidad2=$x_unidad_2;
$d3=trim($x_descripcion_3); $precio3=trim($x_valor_unitario_3); $cantidad3=trim($x_cantidad_3); $unidad3=$x_unidad_3;
$d4=trim($x_descripcion_4); $precio4=trim($x_valor_unitario_4); $cantidad4=trim($x_cantidad_4 ); $unidad4=$x_unidad_4;
$d5=trim($x_descripcion_5); $precio5=trim($x_valor_unitario_5); $cantidad5=trim($x_cantidad_5); $unidad5=$x_unidad_5;
$d6=trim($x_descripcion_6); $precio6=trim($x_valor_unitario_6); $cantidad6=trim($x_cantidad_6 ); $unidad6=$x_unidad_6;
$d7=trim($x_descripcion_7); $precio7=trim($x_valor_unitario_7); $cantidad7=trim($x_cantidad_7 ); $unidad7=$x_unidad_7;
$d8=trim($x_descripcion_8); $precio8=trim($x_valor_unitario_8); $cantidad8=trim($x_cantidad_8); $unidad8=$x_unidad_8;
$d9=trim($x_descripcion_9); $precio9=trim($x_valor_unitario_9); $cantidad9=trim($x_cantidad_9 ); $unidad9=$x_unidad_9;
$d10=trim($x_descripcion_10); $precio10=trim($x_valor_unitario_10); $cantidad10=trim($x_cantidad_10); $unidad10=$x_unidad_10;
$d11=trim($x_descripcion_11); $precio11=trim($x_valor_unitario_11); $cantidad11=trim($x_cantidad_11); $unidad11=$x_unidad_11;
$d12=trim($x_descripcion_12); $precio12=trim($x_valor_unitario_12); $cantidad12=trim($x_cantidad_12); $unidad12=$x_unidad_12;
$d13=trim($x_descripcion_13); $precio13=trim($x_valor_unitario_13); $cantidad13=trim($x_cantidad_13); $unidad13=$x_unidad_13;
$d14=trim($x_descripcion_14); $precio14=trim($x_valor_unitario_14); $cantidad14=trim($x_cantidad_14); $unidad14=$x_unidad_14;
$d15=trim($x_descripcion_15); $precio15=trim($x_valor_unitario_15); $cantidad15=trim($x_cantidad_15); $unidad15=$x_unidad_15;
$descuento=0;
	
	$iva= $x_iva/100;         
   	$numero = $serie."-".$folio; //numero de factura
	$monto1 = $cantidad1 * $precio1;
	$monto2 = $cantidad2 * $precio2;
	$monto3 = $cantidad3 * $precio3;
	$monto4 = $cantidad4 * $precio4;
	$monto5 = $cantidad5 * $precio5;
    $subtotal = $monto1+$monto2+$monto3+$monto4+$monto5;
    $subtotal2 = $subtotal - $descuento;
	$iva = $iva*$subtotal2;
    $total = $subtotal2 + $iva;

$descuento=0;
	
	$iva= $iva/100;         
   	$numero = $serie."-".$folio; //numero de factura
	$monto1 = $cantidad1 * $precio1;
	$monto2 = $cantidad2 * $precio2;
	$monto3 = $cantidad3 * $precio3;
	$monto4 = $cantidad4 * $precio4;
	$monto5 = $cantidad5 * $precio5;
	$monto1 = $cantidad1 * $precio1;
	$monto2 = $cantidad2 * $precio2;
	$monto3 = $cantidad3 * $precio3;
	$monto4 = $cantidad4 * $precio4;
	$monto5 = $cantidad5 * $precio5;
    $subtotal = $monto1+$monto2+$monto3+$monto4+$monto5;
    $subtotal2 = $subtotal - $descuento;
	$iva = $iva*$subtotal2;
    $total = $subtotal2 + $iva;
			

        
    $pdf=new PDF('P','mm','Letter');
    $pdf->AliasNbPages();
    $pdf->AddPage();    
    $pdf->Ln(7);
	$pdf->SetFillColor(140,240,90);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(105,4,"CLIENTE",1,0,'C',true);
	$pdf->Cell(10,4,"",0,0,'C');
	$pdf->SetFont('Arial','',8);
	
	//datos del cliente y datos del CFD
	$pdf->Cell(80,4,utf8_decode("Lugar de Expedición: $pais_emisor $estado_emisor."),"LRT",1,'L');
	$pdf->Cell(105,4,utf8_decode($x_nombre_completo),"LR",0,'L');
	$pdf->Cell(10,4,"",0,0,'C');
	$pdf->Cell(80,4,utf8_decode("Fecha y Hora: $fecha"),"LR",1,'L');
	$pdf->Cell(105,4,"RFC: ".$rfc,"LR",0,'L');
	$pdf->Cell(10,4,"",0,0,'C');
	$pdf->Cell(80,4,utf8_decode("Certificado: $num_certificado"),"LR",1,'L');
	$pdf->Cell(105,4,utf8_decode("Calle $calle #$num_exterior $num_interior, Col. $colonia"),"LR",0,'L');
	$pdf->Cell(10,4,"",0,0,'C');
	$pdf->Cell(80,4,utf8_decode("Aprobación: $aprobacion   Año: $year_aprobacion"),"LR",1,'L');
	$pdf->Cell(105,4,utf8_decode("$municipio, $estado, $pais CP.$codigo_postal"),"LBR",0,'L');
	$pdf->Cell(10,4,"",0,0,'C');
	$pdf->Cell(80,4,"","LRB",1,'L');
    
	//detalle de conceptos
	$pdf->Ln(5);
	$pdf->SetFont('Arial','B',8);
    $pdf->Cell(105,5,utf8_decode('DESCRIPCIÓN'),1,0,'C',true);
    $pdf->Cell(30,5,'PRECIO',1,0,'C',true);
    $pdf->Cell(30,5,"CANTIDAD",1,0,'C',true);
    $pdf->Cell(30,5,"MONTO",1,1,'C',true);
	$pdf->SetFont('Arial','',8);
                               
	if ($d1!=""){
    $posy1=$pdf->GetY();//posición antes de escribir concepto
    $pdf->MultiCell(105,5,"\n".utf8_decode($d1),"L",'L');
    $posy2=$pdf->GetY();$posX2=$pdf->GetX();//posicion despues de escribir concepto
    $dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas
    $pdf->SetY($posy1);$pdf->SetX(115);//reposiciono Y y X despues del concepto, 10 de margen en x
    $pdf->Cell(30,$dif_y,"$".number_format($precio1, 2, '.', ','),'L',0,'C');
    $pdf->Cell(30,$dif_y,$cantidad1." unidad1",'L',0,'C');
    $pdf->Cell(30,$dif_y,"$".number_format($monto1, 2, '.', ','),'LR',1,'C');} 	
								
    if ($d2!=""){
	$posy1=$pdf->GetY();//posición antes de escribir concepto
    $pdf->MultiCell(105,5,"\n".utf8_decode($d2),'L','L');
    $posy2=$pdf->GetY();$posX2=$pdf->GetX();//posicion despues de escribir concepto
    $dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas
    $pdf->SetY($posy1);$pdf->SetX(115);//reposiciono Y y X despues del concepto, 10 de margen en x
    $pdf->Cell(30,$dif_y,"$".number_format($precio2, 2, '.', ','),"L",0,'C');
    $pdf->Cell(30,$dif_y,$cantidad2." $unidad2",'L',0,'C');
    $pdf->Cell(30,$dif_y,"$".number_format($monto2, 2, '.', ','),'LR',1,'C');}         
            
    if ($d3!=""){                         
	$posy1=$pdf->GetY();//posición antes de escribir concepto
    $pdf->MultiCell(105,5,"\n".utf8_decode($d3),'L','L');
    $posy2=$pdf->GetY();$posX2=$pdf->GetX();//posicion despues de escribir concepto
    $dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas
    $pdf->SetY($posy1);$pdf->SetX(115);//reposiciono Y y X despues del concepto, 10 de margen en x
    $pdf->Cell(30,$dif_y,"$".number_format($precio3, 2, '.', ','),'L',0,'C');
    $pdf->Cell(30,$dif_y,$cantidad3." $unidad3",'L',0,'C');
    $pdf->Cell(30,$dif_y,"$".number_format($monto3, 2, '.', ','),'LR',1,'C');}
            
    if ($d4!=""){
	$posy1=$pdf->GetY();//posición antes de escribir concepto
    $pdf->MultiCell(105,5,"\n".utf8_decode($d4),'L','L');
    $posy2=$pdf->GetY();$posX2=$pdf->GetX();//posicion despues de escribir concepto
    $dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas
    $pdf->SetY($posy1);$pdf->SetX(115);//reposiciono Y y X despues del concepto, 10 de margen en x
    $pdf->Cell(30,$dif_y,"$".number_format($precio4, 2, '.', ','),'L',0,'C');
    $pdf->Cell(30,$dif_y,$cantidad4." $unidad4",'L',0,'C');
    $pdf->Cell(30,$dif_y,"$".number_format($monto4, 2, '.', ','),'LR',1,'C');}
            
    if ($d5!=""){
	$posy1=$pdf->GetY();//posición antes de escribir concepto
    $pdf->MultiCell(105,4,"\n".utf8_decode($d5),'L','L');
    $posy2=$pdf->GetY();$posX2=$pdf->GetX();//posicion despues de escribir concepto
    $dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas
    $pdf->SetY($posy1);$pdf->SetX(115);//reposiciono Y y X despues del concepto, 10 de margen en x
    $pdf->Cell(30,$dif_y,"$".number_format($precio5, 2, '.', ','),'L',0,'C');
    $pdf->Cell(30,$dif_y,$cantidad5." $unidad5",'L',0,'C');
    $pdf->Cell(30,$dif_y,"$".number_format($monto5, 2, '.', ','),'LR',1,'C');}
         
	//cerrar tabla de conceptos
    $h = 190-($pdf->GetY());
    $pdf->Cell(105,$h," ",'LB',0,'C');
    $pdf->Cell(30,$h," ",'LB',0,'C');
    $pdf->Cell(30,$h," ",'LB',0,'C');
    $pdf->Cell(30,$h," ",'LRB',1,'C');
            
    //subtotal y pagarè
	$pdf->SetFont('Arial','',6);
    $pdf->Cell(42,4," ",0,0,'L');
    $pdf->Cell(93,4,utf8_decode("Debo y pagaré a la orden de $razon_social_emisor "),0,0,'L');
	$pdf->SetFont('Arial','',8); $pdf->Cell(30,5,"Subtotal: ",0,0,'R');
    $pdf->Cell(30,4,"$".number_format($subtotal, 2, '.', ','),0,1,'C');

    //descuento 
    $pdf->SetFont('Arial','',6);
	$pdf->Cell(42,4," ",0,0,'L');
    $pdf->Cell(93,4,utf8_decode("en cualquier plaza donde se requiera el pago de la cantidad consignada"),0,0,'L');
    $pdf->SetFont('Arial','',8); $pdf->Cell(30,4,"Descuento: ",0,0,'R');
    $pdf->Cell(30,4,"$".number_format($descuento, 2, '.', ','),0,1,'C');

    //subtotal 2
    $pdf->SetFont('Arial','',6);
	$pdf->Cell(42,4," ",0,0,'L');
    $pdf->Cell(93,4,utf8_decode("en éste título de credito, en un plazo no mayor a $dias_credito dias a partir del $fecha"),0,0,'L');
    $pdf->SetFont('Arial','',8); $pdf->Cell(30,4,"Subtotal: ",0,0,'R');
    $pdf->Cell(30,4,"$".number_format($subtotal2, 2, '.', ','),0,1,'C');

    //IVA y ejecutivo
    $pdf->Cell(165,4,"IVA: ",0,0,'R');
    $pdf->Cell(30,4,"$".number_format($iva, 2, '.', ','),0,1,'C');

    //cantidad con letra y total
    $letras=utf8_decode(num2letras($total,0,0)." pesos  ");
	$total_cadena=$total;
	$total = "$".number_format($total, 2, '.', ',');
	$ultimo = substr (strrchr ($total, "."), 1 ); //recupero lo que este despues del decimal
	$letras = $letras." ".$ultimo."/100 M. N.";
			
	$pdf->SetFont('Arial','',6);
    $pdf->Cell(35,4,"",0,0,'R');
	$pdf->Cell(100,4,"____________________",0,0,'C');
			
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(30,4,"Total: ",0,0,'R');
    $pdf->Cell(30,4,$total,0,1,'C');
			
	$pdf->SetFont('Arial','',6);
	$pdf->Cell(35,4,"",0,0,'R');
	$pdf->Cell(100,4,"Firma",0,1,'C');
			
	$pdf->Ln(3);$pdf->SetFont('Arial','B',8);
	$pdf->Cell(35,4,"",0,0,'R');
	$pdf->Cell(160,4,"Importe en letra: ".$letras,0,1,'C');
	$pdf->Ln(3);	
		
	// genera cadena original para ingresos, para traslados hay que agregar a la cadena las condicionales
	// de los campos de cada traslado
	//datos del comprobante
	$cadena_original="||2.0|$serie|$folio|$x_t_d|$aprobacion|$year_aprobacion|$tipo_cfd|$forma_pago|$dias_credito días";
	$cadena_original.="|".number_format($x_sub_total_XML, 2, '.','')."|".number_format($x_total_XML, 2, '.','');
	//datos del emisor
	$cadena_original.="|$rfc_emisor|$razon_social_emisor|$calle_emisor|$num_exterior_emisor|$colonia_emisor|$municipio_emisor";
	$cadena_original.="|$estado_emisor|$pais_emisor|$codigo_postal_emisor";
	//datos del cliente
	$cadena_original.="|$rfc|$razon_social|$calle|$num_exterior";
	if($num_interior!=""){$cadena_original.="|$num_interior";}
	$cadena_original.="|$colonia";
	if($localidad!=""){$cadena_original.="|$localidad";}
	if($referencia!=""){$cadena_original.="|$referencia";}
	$cadena_original.="|$municipio|$estado|$pais|$codigo_postal";
	//detalle de conceptos
	if ($d1!="")
	{$cadena_original.="|$cantidad1|$unidad1|$d1|".number_format($precio1, 2, '.','')."|".number_format($monto1, 2, '.','');}
	if ($d2!="")
	{$cadena_original.="|$cantidad2|$unidad2|$d2|".number_format($precio2, 2, '.','')."|".number_format($monto2, 2, '.','');}
	if ($d3!="")
	{$cadena_original.="|$cantidad3|$unidad3|$d3|".number_format($precio3, 2, '.','')."|".number_format($monto3, 2, '.','');}
	if ($d4!="")
	{$cadena_original.="|$cantidad4|$unidad4|$d4|".number_format($precio4, 2, '.','')."|".number_format($monto4, 2, '.','');}
	if ($d5!="")
	{$cadena_original.="|$cantidad5|$unidad5|$d5|".number_format($precio5, 2, '.','')."|".number_format($monto5, 2, '.','');}
	if ($d6!="")
	{$cadena_original.="|$cantidad6|$unidad6|$d6|".number_format($precio6, 2, '.','')."|".number_format($monto6, 2, '.','');}
	if ($d7!="")
	{$cadena_original.="|$cantidad7|$unidad7|$d7|".number_format($precio7, 2, '.','')."|".number_format($monto7, 2, '.','');}
	if ($d8!="")
	{$cadena_original.="|$cantidad8|$unidad8|$d8|".number_format($precio8, 2, '.','')."|".number_format($monto8, 2, '.','');}
	if ($d9!="")
	{$cadena_original.="|$cantidad9|$unidad9|$d9|".number_format($precio9, 2, '.','')."|".number_format($monto9, 2, '.','');}
	if ($d10!="")
	{$cadena_original.="|$cantidad10|$unidad10|$d10|".number_format($precio10, 2, '.','')."|".number_format($monto10, 2, '.','');}
	if ($d11!="")
	{$cadena_original.="|$cantidad11|$unidad11|$d11|".number_format($precio11, 2, '.','')."|".number_format($monto11, 2, '.','');}
	if ($d12!="")
	{$cadena_original.="|$cantidad12|$unidad12|$d12|".number_format($precio12, 2, '.','')."|".number_format($monto12, 2, '.','');}
	if ($d13!="")
	{$cadena_original.="|$cantidad13|$unidad13|$d13|".number_format($precio13, 2, '.','')."|".number_format($monto13, 2, '.','');}
	if ($d14!="")
	{$cadena_original.="|$cantidad14|$unidad14|$d14|".number_format($precio14, 2, '.','')."|".number_format($monto14, 2, '.','');}
	if ($d15!="")
	{$cadena_original.="|$cantidad15|$unidad15|$d15|".number_format($precio15, 2, '.','')."|".number_format($monto15, 2, '.','');}
	
	//detalle de impuestos		
	$cadena_original.="|IVA|16|".number_format($x_importe_iva, 2, '.','')."|".number_format($x_importe_iva, 2, '.','')."||";	
	$cadena_original=str_replace("  "," ",$cadena_original);
			
	//Digestion SHA1, firmamos con nuestra clave y pasamos a base 64, requiere de openssl instalado		
	$key='certificados/mcr070419kn2_1012292351s.key.pem';
	$fp = fopen($key, "r");
	$priv_key = fread($fp, 8192);
	fclose($fp);		
	$pkeyid = openssl_get_privatekey($priv_key);
	#openssl_sign($cadena_original,$cadenafirmada,$pkeyid,OPENSSL_ALGO_SHA1);
	openssl_sign($cadena_original,$cadenafirmada,$pkeyid);
	$sello = base64_encode($cadenafirmada);
			
	$pdf->SetFont('Arial','B',5);
	$pdf->Cell(42,3,"",0,0,'C');
	$pdf->MultiCell(0,3,utf8_decode("Cadena Original"),0,'L');
	$pdf->SetFont('Arial','',4);
	$pdf->Cell(42,3,"",0,0,'C');
	$pdf->MultiCell(0,3,utf8_decode($cadena_original),0,'L');
	$pdf->Ln(1);
	$pdf->SetFont('Arial','B',5);
	$pdf->Cell(42,3,"",0,0,'C');
	$pdf->MultiCell(0,3,utf8_decode("Sello Digital"),0,'L');
	$pdf->SetFont('Arial','',4);
	$pdf->Cell(42,3,"",0,0,'C');
	$pdf->MultiCell(0,3,utf8_decode($sello),0,'L');
	$pdf->Ln(1);
	$pdf->SetFont('Arial','B',5);
	$pdf->Cell(42,3,"",0,0,'C');
	$pdf->MultiCell(0,3,utf8_decode('Este documento es una representación impresa de un CFD'),0,'L');

	//creo el xml en memoria
	$cadena_xml='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'."\r\n";
	$cadena_xml.='<Comprobante xmlns="http://www.sat.gob.mx/cfd/2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"';
	$cadena_xml.=' version="2.0" serie="'.$serie.'" folio="'.$folio.'" fecha="'.$x_t_d.'" sello="'.$sello.'"';
	$cadena_xml.=' noAprobacion="'.$aprobacion.'" anoAprobacion="'.$year_aprobacion.'" formaDePago="'.$forma_pago.'" noCertificado="'.$num_certificado.'"  certificado="'.$certificado_texto.'"';
	$cadena_xml.=' condicionesDePago="'.$dias_credito.' días"';
	$cadena_xml.=' subTotal="'.number_format($x_sub_total_XML, 2, '.','').'" total="'.number_format($x_total_XML, 2, '.','').'" tipoDeComprobante="'.$tipo_cfd.'"';
	$cadena_xml.=' xsi:schemaLocation="http://www.sat.gob.mx/cfd/2 http://www.sat.gob.mx/sitio_internet/cfd/2/cfdv2.xsd">'."\r\n";
	$cadena_xml.='<Emisor rfc="'.$rfc_emisor.'" nombre="'.$razon_social_emisor.'">'."\r\n";
	$cadena_xml.='<DomicilioFiscal calle="'.$calle_emisor.'" noExterior="'.$num_exterior_emisor.'" colonia="'.$colonia_emisor.'" municipio="'.$municipio_emisor.'"';
	$cadena_xml.=' estado="'.$estado_emisor.'" pais="'.$pais_emisor.'" codigoPostal="'.$codigo_postal_emisor.'"/>'."\r".'</Emisor>'."\r\n";
	$cadena_xml.='<Receptor rfc="'.$rfc.'" nombre="'.$razon_social.'">'."\r\n";
	$cadena_xml.='<Domicilio calle="'.$calle.'" noExterior="'.$num_exterior.'"';
	if ($num_interior!=''){$cadena_xml.=' noInterior="'.$num_interior.'"';}
	$cadena_xml.=' colonia="'.$colonia.'"';
	if ($localidad!=''){$cadena_xml.=' localidad="'.$localidad.'"';}
	if ($referencia!=''){$cadena_xml.=' referencia="'.$referencia.'"';}
	$cadena_xml.=' municipio="'.$municipio.'" estado="'.$estado.'" pais="'.$pais.'" codigoPostal="'.$codigo_postal.'"/>'."\r\n".'</Receptor>'."\r\n".'<Conceptos>'."\r\n";
			
	if ($d1!="")
	{$cadena_xml.='<Concepto cantidad="'.$cantidad1.'" unidad="'.$unidad1.'" descripcion="'.$d1.'" valorUnitario="'.number_format($precio1, 2, '.','').'" importe="'.number_format($monto1, 2, '.','').'"/>'."\r\n";}
			
	if ($d2!="")
	{$cadena_xml.='<Concepto cantidad="'.$cantidad2.'" unidad="'.$unidad2.'" descripcion="'.$d2.'" valorUnitario="'.number_format($precio2, 2, '.','').'" importe="'.number_format($monto2, 2, '.','').'"/>'."\r\n";}
			
	if ($d3!="")
	{$cadena_xml.='<Concepto cantidad="'.$cantidad3.'" unidad="'.$unidad3.'" descripcion="'.$d3.'" valorUnitario="'.number_format($precio3, 2, '.','').'" importe="'.number_format($monto3, 2, '.','').'"/>'."\r\n";}
			
	if ($d4!="")
	{$cadena_xml.='<Concepto cantidad="'.$cantidad4.'" unidad="'.$unidad4.'" descripcion="'.$d4.'" valorUnitario="'.number_format($precio4, 2, '.','').'" importe="'.number_format($monto4, 2, '.','').'"/>'."\r\n";}
			
	if ($d5!="")
	{$cadena_xml.='<Concepto cantidad="'.$cantidad5.'" unidad="'.$unidad5.'" descripcion="'.$d5.'" valorUnitario="'.number_format($precio5, 2, '.','').'" importe="'.number_format($monto5, 2, '.','').'"/>'."\r\n";}
	
	if ($d6!="")
	{$cadena_xml.='<Concepto cantidad="'.$cantidad6.'" unidad="'.$unidad6.'" descripcion="'.$d6.'" valorUnitario="'.number_format($precio6, 2, '.','').'" importe="'.number_format($monto6, 2, '.','').'"/>'."\r\n";}
	
	if ($d7!="")
	{$cadena_xml.='<Concepto cantidad="'.$cantidad7.'" unidad="'.$unidad7.'" descripcion="'.$d7.'" valorUnitario="'.number_format($precio7, 2, '.','').'" importe="'.number_format($monto7, 2, '.','').'"/>'."\r\n";}
	
	if ($d8!="")
	{$cadena_xml.='<Concepto cantidad="'.$cantidad8.'" unidad="'.$unidad8.'" descripcion="'.$d8.'" valorUnitario="'.number_format($precio8, 2, '.','').'" importe="'.number_format($monto8, 2, '.','').'"/>'."\r\n";}
	
	if ($d9!="")
	{$cadena_xml.='<Concepto cantidad="'.$cantidad9.'" unidad="'.$unidad9.'" descripcion="'.$d9.'" valorUnitario="'.number_format($precio9, 2, '.','').'" importe="'.number_format($monto9, 2, '.','').'"/>'."\r\n";}
	
	if ($d10!="")
	{$cadena_xml.='<Concepto cantidad="'.$cantidad10.'" unidad="'.$unidad10.'" descripcion="'.$d10.'" valorUnitario="'.number_format($precio10, 2, '.','').'" importe="'.number_format($monto10, 2, '.','').'"/>'."\r\n";}
	if ($d11!="")
	{$cadena_xml.='<Concepto cantidad="'.$cantidad11.'" unidad="'.$unidad11.'" descripcion="'.$d11.'" valorUnitario="'.number_format($precio11, 2, '.','').'" importe="'.number_format($monto11, 2, '.','').'"/>'."\r\n";}
	if ($d10!="")
	{$cadena_xml.='<Concepto cantidad="'.$cantidad12.'" unidad="'.$unidad12.'" descripcion="'.$d12.'" valorUnitario="'.number_format($precio12, 2, '.','').'" importe="'.number_format($monto12, 2, '.','').'"/>'."\r\n";}
	if ($d13!="")
	{$cadena_xml.='<Concepto cantidad="'.$cantidad13.'" unidad="'.$unidad13.'" descripcion="'.$d13.'" valorUnitario="'.number_format($precio13, 2, '.','').'" importe="'.number_format($monto13, 2, '.','').'"/>'."\r\n";}
	if ($d14!="")
	{$cadena_xml.='<Concepto cantidad="'.$cantidad14.'" unidad="'.$unidad14.'" descripcion="'.$d14.'" valorUnitario="'.number_format($precio14, 2, '.','').'" importe="'.number_format($monto14, 2, '.','').'"/>'."\r\n";}
	if ($d15!="")
	{$cadena_xml.='<Concepto cantidad="'.$cantidad15.'" unidad="'.$unidad15.'" descripcion="'.$d15.'" valorUnitario="'.number_format($precio15, 2, '.','').'" importe="'.number_format($monto15, 2, '.','').'"/>'."\r\n";}
			
	$cadena_xml.='</Conceptos>'."\r\n";
	$cadena_xml.='<Impuestos totalImpuestosTrasladados="'.number_format($x_importe_iva, 2, '.','').'">'."\r".'<Traslados>'."\r".'<Traslado impuesto="IVA" tasa="16" importe="'.number_format($x_importe_iva, 2, '.','').'"/>'."\r\n";
	$cadena_xml.='</Traslados>'."\r\n".'</Impuestos>'."\r\n".'</Comprobante>';
			
	$cadena_xml=str_replace("  "," ",$cadena_xml);
		
	$x_fecha_actual = date("Y-m-d");	
	$x_no_ale = rand(100, 1000);
	$x_nn = str_replace(" ", "_",$x_nombre);
	$x_nombre_cfd = "CFD_".$x_fecha_actual."_".$x_nn."_".$x_paterno."_".$x_materno."_".$serie."_".$folio.".xml";	
	//creo un archivo de texto plano y meto la cadena del xml
	$new_xml = fopen ("CFDS/XML/$x_nombre_cfd", "w");
	fwrite($new_xml,$cadena_xml);
	fclose($new_xml);
   #$x_nombre_pdf = "PDF_".$x_fecha_actual."_".$x_nn."_".$x_paterno."_".$x_materno."_".$serie."_".$folio.".pdf";		
   #$pdf->Output("CFDS/PDF/$x_nombre_pdf","F");  //guardo en disco
   #$pdf->Output();//muestro el pdf


	#INSERTAMOS EL ARCHIVO EN LA TABLA
	$sqlxml = "INSERT INTO factura_cfd (`factura_cfd_id`, `factura_id`, `archivo`, `fecha`) VALUES (NULL, $x_factura,'$x_nombre_cfd', '$x_fecha_actual');";
	$rsxml = phpmkr_query($sqlxml, $conn) or die("Error al seleccionar".phpmkr_error()."sql :".$sqlxml);
	if($rsxml){
		#echo "sen inserto en xml";
		}		
	$x_pdf = generaPDF($x_factura, $conn,$cadena_original, $sello, $x_t_d);
	
	
	
	return true;
		
	
	
	}



?>