<?php
function generaXmlNew($factura_id, $conn){
	
	// SACAMOS LOS DATOS DE LAS TABLAS
	$x_factura = $x_factura_id ;
	$x_sello = $x_sello;
	$conn = $conn;
	
echo "id".$x_factura ."<br>";
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

$ar=fopen("archivosPEM/certificado.txt","r") or die("No se pudo abrir el archivo");
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

$x_cuenta++;
	}

$d1=trim($x_descripcion_1); $precio1=trim($x_valor_unitario_1); $cantidad1=trim($x_cantidad_1 ); $unidad1=$x_unidad_1;
$d2=trim($x_descripcion_2); $precio2=trim($x_valor_unitario_2); $cantidad2=trim($x_cantidad_2 ); $unidad2=$x_unidad_2;
$d3=trim($x_descripcion_3); $precio3=trim($x_valor_unitario_3); $cantidad3=trim($x_cantidad_3); $unidad3=$x_unidad_3;
$d4=trim($x_descripcion_4); $precio4=trim($x_valor_unitario_4); $cantidad4=trim($x_cantidad_4 ); $unidad4=$x_unidad_4;
$d5=trim($x_descripcion_5); $precio5=trim($x_valor_unitario_5); $cantidad5=trim($x_cantidad_5); $unidad5=$x_unidad_5;
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
			
class PDF extends FPDF
{
    //Encabezado de página
    function Header()
    {   
		$this->SetFillColor(140,240,90);
        $this->Image('imgs/logo.jpg',6,8,56);
		$this->Image('imgs/cedula.jpg',10,192,39);
		$this->SetFont('Arial','B',12);
		$this->Cell(50,4,"",0,0,'C');
		$this->Cell(95,4,utf8_decode("Microfinanciera Crece, S.A. de C.V., SOFOM, E.N.R."),0,0,'C');
		$this->SetFont('Arial','B',8);
		$this->Cell(50,4,"FACTURA",1,1,'C',true);
		$this->SetFont('Arial','B',9);
		$this->Cell(50,4,"",0,0,'C');
		$this->Cell(95,4,utf8_decode("R.F.C. ISP900909Q88"),0,0,'C');
		$this->SetFont('Arial','',8);
		$this->Cell(25,4,"Serie: ".$x_serie,1,0,'C');
		$this->Cell(25,4,"Folio: ".$x_folio,1,1,'C');
		$this->SetFont('Arial','B',8);
		$this->Cell(50,4,"",0,0,'C');
		$this->Cell(95,4,utf8_decode("Alvaro Obregón Num. 1909, Col. San Angel"),0,1,'C');
		$this->Cell(50,4,"",0,0,'C');
		$this->Cell(95,4,utf8_decode('Del. Albaro obregon, México Distrito Federal, CP. 01000'),0,1,'C');
		$this->Ln(4);
    } 
}
        
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
	$cadena_original="||2.0|$serie|$folio|$fecha|$aprobacion|$year_aprobacion|$tipo_cfd|$forma_pago|$dias_credito días";
	$cadena_original.="|".number_format($subtotal2, 2, '.','')."|".number_format($total_cadena, 2, '.','');
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
	
	//detalle de impuestos		
	$cadena_original.="|IVA|".$x_iva."|".number_format($x_iva, 2, '.','')."|".number_format($x_iva, 2, '.','')."||";		
	$cadena_original=str_replace("  "," ",$cadena_original);
			
	//Digestion SHA1, firmamos con nuestra clave y pasamos a base 64, requiere de openssl instalado		
	$key='archivosPEM/aaa010101aaa_csd_01.key.pem';
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
	$cadena_xml.=' version="2.0" serie="'.$serie.'" folio="'.$folio.'" fecha="'.$fecha.'" sello="'.$sello.'"';
	$cadena_xml.=' noAprobacion="'.$aprobacion.'" anoAprobacion="'.$year_aprobacion.'" formaDePago="'.$forma_pago.'" noCertificado="'.$num_certificado.'"  certificado="'.$certificado_texto.'"';
	$cadena_xml.=' condicionesDePago="'.$dias_credito.' días"';
	$cadena_xml.=' subTotal="'.number_format($subtotal2, 2, '.','').'" total="'.number_format($total_cadena, 2, '.','').'" tipoDeComprobante="'.$tipo_cfd.'"';
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
			
	$cadena_xml.='</Conceptos>'."\r\n";
	$cadena_xml.='<Impuestos totalImpuestosTrasladados="'.number_format($iva, 2, '.','').'">'."\r".'<Traslados>'."\r".'<Traslado impuesto="IVA" tasa="'.$_REQUEST[iva].'" importe="'.number_format($iva, 2, '.','').'"/>'."\r\n";
	$cadena_xml.='</Traslados>'."\r\n".'</Impuestos>'."\r\n".'</Comprobante>';
			
	$cadena_xml=str_replace("  "," ",$cadena_xml);
						
	//creo un archivo de texto plano y meto la cadena del xml
	$new_xml = fopen ("facturas/Factura ".$serie."-".$folio.".xml", "w");
	fwrite($new_xml,$cadena_xml);
	fclose($new_xml);
			
	$pdf->Output("facturas/Factura ".$serie."-".$folio.".pdf","F");  //guardo en disco
    $pdf->Output();//muestro el pdf

	
	
	
	
	return true;
		
	
	
	}



?>