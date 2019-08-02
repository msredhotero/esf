<?php

require_once('pdf/tcpdf_php4/config/lang/spa.php');
require_once('pdf/tcpdf_php4/tcpdf.php');

function generaPDF($x_factura_id, $conn, $x_cadena_o ){
// Extend the TCPDF class to create custom Header and Footer



// consultamos la informacion de la base de datos...s

$x_factura = $x_factura_id ;
$conn = $conn;
$x_cadena_original = $x_cadena_o;
	

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









class MYPDF extends TCPDF {

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-30);
        // Set font
        $this->SetFont('helvetica', '', 8);
        // Page number
        $this->Cell(0, 10, $this->getAliasNumPage().' Este Documento es una representación impresa de un CFD', 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Financiera Crea');
$pdf->SetTitle('Comprobante Fiscal Digital');

// remove default header/footer
$pdf->setPrintHeader(false);
//$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('Helvetica', '', 10, '', true);
$pdf->SetMargins($left = 18, $top = 25, $right = 18, $keepmargins = false);	

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

//Encabezado
$folio = $x_folio;
$serie_certificado = $x_serie_certificado ;
$aprobacion_numero = $x_numero_aprobacion;
$aprobacion_año = $x_anio_aprobacion;
$fecha_sello = $x_fecha_T;

//Datos Emisor:
$nombre_emisor = "Microfinanciera Crece, S.A. de C.V., SOFOM, E.N.R.";
$rfc_emisor = $x_rfc_contribuyente;
$calle_emisor = "AVENIDA REVOLUCI&Oacute;N";
$colonia_emisor = "SAN ANGEL";
$municipio_emisor = "Alvaro Obre&oacute;n";
$estado_emisor = "Distrito Federal";
$pais_emisor = "México";
$cp_emisor = "01000";

//Datos Receptor
$nombre_receptor = $x_nombre_completo;
$rfc_receptor = $x_rfc;
$calle_receptor = $x_calle;
$colonia_receptor = $x_colonia;
$municipio_receptor = $x_delegacion;
$estado_receptor = $x_estado;
$pais_receptor = "México";
$cp_receptor = $x_codigo_postal;

//Datos Sello Digital

$forma_pago = $x_forma_pago;
$metodo_pago = $x_metodo_pago;
$cadena_original = $x_cadena_original;
$sello_digital = $x_sello_digital;

$cadena_original2 = wordwrap($cadena_original, 120, $break="<br \>", true);
$sello_digital2 = wordwrap($sello_digital, 115, "<br />", true);

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

$x_pagos .= '<tr>
        <td>'.$x_cantidad.'</td>
        <td>'.$x_unidad .'</td>
        <td>'.$x_descripcion.'</td>
        <td>'.$x_valor_unitario.'</td>
        <td align="right">'.$x_importe.'</td>
        </tr>' ;

$x_cuenta++;
}


//echo $cadena_original2;

// Set some content to print
$html = <<<EOD
<table width="100%" border="0" cellspacing="0" cellpadding="4" style="color:#666;">
  <tr>
    <td width="49%" align="left" style="font-size:14pt;">Comprobante Fiscal Digital</td>
    <td width="2%">&nbsp;</td>
    <td width="49%" rowspan="2" align="center" style="border-bottom:1pt solid #333;"><table width="90%" border="0" cellspacing="0" cellpadding="2" style="font-size:8pt;">
      <tr>
        <td colspan="2" align="left">No. de serie del Certificado de Sello Digital</td>
        </tr>
      <tr>
        <td colspan="2" align="left">$serie_certificado</td>
        </tr>
      <tr>
        <td width="50%" align="left">Número de aprobación</td>
        <td width="50%" align="left">Año de Aprobación</td>
      </tr>
      <tr>
        <td align="center">$aprobacion_numero</td>
        <td align="center">$aprobacion_año</td>
      </tr>
      <tr>
        <td colspan="2" align="left">México D.F., $fecha_sello</td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td style="border-bottom:1pt solid #333;">Folio: <span style="font-size:15pt"><strong>$folio</strong></span></td>
    <td style="border-bottom:1pt solid #333;">&nbsp;</td>
  </tr>
  <tr>
    <td height="30" align="center" valign="bottom"><strong>Datos del Emisor</strong></td>
    <td align="center" valign="bottom">&nbsp;</td>
    <td align="center" valign="bottom"><strong>Datos del Receptor</strong></td>
  </tr>
  <tr>
    <td>$nombre_emisor</td>
    <td>&nbsp;</td>
    <td>$nombre_receptor</td>
  </tr>
  <tr>
    <td>$rfc_emisor</td>
    <td>&nbsp;</td>
    <td>$rfc_receptor</td>
  </tr>
  <tr>
    <td style="border-bottom:1pt solid #333;">$calle_emisor, $colonia_emisor, $municipio_emisor, $estado_emisor, $pais_emisor, C.P. $cp_emisor.</td>
    <td  style="border-bottom:1pt solid #333;">&nbsp;</td>
    <td  style="border-bottom:1pt solid #333;">$calle_receptor, $colonia_receptor, $municipio_receptor, $estado_receptor, $pais_receptor, C.P. $cp_receptor.</td>
  </tr>
  <tr>
    <td height="30" colspan="3" align="center" valign="bottom"><strong>Datos de la transacción</strong></td>
  </tr>
  <tr>
    <td colspan="3" align="center" style="border-bottom:1pt solid #333;"><div align="center"><table width="99%" border="0" align="center" cellpadding="2" cellspacing="0">
      <tr>
        <td width="11%" align="center"><strong>Cantidad</strong></td>
        <td width="13%" align="center"><strong>Medida</strong></td>
        <td width="45%" align="center"><strong>Descripción</strong></td>
        <td width="15%" align="center"><strong>Precio Unidad</strong></td>
        <td width="16%" align="center"><strong>Importe</strong></td>
        </tr>
      $x_pagos
      <tr>
        <td colspan="3" align="left">Importe con letra:</td>
        <td align="right"><strong>Subtotal:</strong></td>
        <td align="right">&nbsp;</td>
        </tr>
      <tr>
        <td colspan="3" rowspan="2" align="left" style="font-size:10pt;">&nbsp;</td>
        <td align="right"><strong>IVA:</strong></td>
        <td align="right">&nbsp;</td>
        </tr>
      <tr>
        <td align="right"><strong>Total:</strong></td>
        <td align="right">&nbsp;</td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
    </div></td>
  </tr>
  <tr>
    <td>Forma de pago: $forma_pago</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Metodo de pago: $metodo_pago</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Cadena Original: </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><div style="font-size: 7pt;">$cadena_original2</div></td>
  </tr>
  <tr>
    <td>Sello Digital del Emisor:</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><div style="font-size: 7pt;">$sello_digital2</div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
EOD;

// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('CFDS/PDF/pdf_nombre_fecha.pdf', 'F');

//============================================================+
// END OF FILE
//============================================================+



} // termina la funcion