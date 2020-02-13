<?php

date_default_timezone_set('America/Mexico_City');


include ('../includes/funcionesReferencias.php');


$serviciosReferencias 			= new ServiciosReferencias();

$fecha = date('Y-m-d H:i:s');

require('fpdf.php');

//$header = array("Hora", "Cancha 1", "Cancha 2", "Cancha 3");

////***** Parametros ****////////////////////////////////
$apellidopaterno     =     $_GET['apellidopaterno'];
$apellidomaterno     =     $_GET['apellidomaterno'];
$nombre              =     $_GET['nombre'];
$proveedor           =     $_GET['proveedor'];
$beneficiario        =     $_GET['beneficiario'];

$resV['datoscliente'] = '';
$resV['datosproveedor'] = '';
$resV['datosbeneficiario'] = '';
$resV['error'] = false;

$cadResultado = '';

$cliente = str_replace(' ', '', trim($apellidopaterno.$apellidomaterno.$nombre));
$clientereverso = str_replace(' ', '', trim($nombre.$apellidopaterno.$apellidomaterno));
$proveedor = str_replace(' ', '', trim($proveedor));
$beneficiario = str_replace(' ', '', trim($beneficiario));

$res = $serviciosReferencias->verificarListaNegraOfac($cliente,$proveedor,$beneficiario,$clientereverso);

if ($res['cliente']==1) {
	$resV['datoscliente']= 'El cliente '.$apellidopaterno.' '.$apellidomaterno.' '.$nombre.' NO fue encuentrado/a en listas negras ';
} else {
	$resV['datoscliente']= 'El cliente '.$apellidopaterno.' '.$apellidomaterno.' '.$nombre.' SI fue encuentrado/a en listas negras ';
	$resV['error'] = true;
}

if ($res['proveedor']==1) {
	$resV['datosproveedor'] = 'El proveedor '.$_GET['proveedor'].' NO fue encuentrado/a en listas negras ';
} else {
	$resV['datosproveedor'] = 'El proveedor '.$_GET['proveedor'].' SI fue encuentrado/a en listas negras ';
	$resV['error'] = true;
}

if ($res['beneficiario']==1) {
	$resV['datosbeneficiario'] = 'El beneficiario '.$_GET['beneficiario'].' NO fue encuentrado/a en listas negras ';
} else {
	$resV['datosbeneficiario'] = 'El beneficiario '.$_GET['beneficiario'].' SI fue encuentrado/a en listas negras ';
	$resV['error'] = true;
}

/////////////////////////////  fin parametross  ///////////////////////////


$pdf = new FPDF();


function Footer($pdf)
{

$pdf->SetY(-10);

$pdf->SetFont('Arial','I',10);

$pdf->Cell(0,10,'Firma: ______________________________________________  -  Pagina '.$pdf->PageNo()." - Fecha: ".date('Y-m-d H:i:s'),0,0,'C');
}



#Establecemos los márgenes izquierda, arriba y derecha:
//$pdf->SetMargins(2, 2 , 2);

#Establecemos el margen inferior:
$pdf->SetAutoPageBreak(false,1);



	$pdf->AddPage();


	//////////////////// Aca arrancan a cargarse los datos de los equipos  /////////////////////////


	$pdf->SetFillColor(183,183,183);
	$pdf->SetFont('Arial','B',12);
	$pdf->Ln();
	$pdf->Ln();
	$pdf->SetX(5);
	$pdf->Cell(200,5,'CONSTANCIA DE SOLICITUD',1,0,'C',true);
	$pdf->SetFont('Arial','',10);
	$pdf->Ln();
	$pdf->Ln();
	$pdf->SetX(5);

	$pdf->SetFont('Arial','',12);

	$pdf->Cell(200,5,'CLIENTE: '.$resV['datoscliente'],0,0,'L',false);
	$pdf->Ln();
	$pdf->Ln();
	$pdf->SetX(5);
	$pdf->Cell(200,5,'PROVEEDOR: '.$resV['datosproveedor'],0,0,'L',false);
	$pdf->Ln();
	$pdf->Ln();
	$pdf->SetX(5);
	$pdf->Cell(200,5,'BENEFICIARIO: '.$resV['datosbeneficiario'],0,0,'L',false);
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Ln();
	$pdf->SetX(5);
	if ($resV['error']) {
		$pdf->Cell(200,5,'NO SE PUEDE GENERAR UNA SOLICITUD ',0,0,'C',false);
	} else {
		$pdf->Cell(200,5,'LA SOLICITUD PUEDE SER CARGADA',0,0,'C',false);
	}

$pdf->Ln();


Footer($pdf);



$nombreTurno = "CONSTANCIA-".$fecha.".pdf";

$pdf->Output($nombreTurno,'I');


?>
