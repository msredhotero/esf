<?php

date_default_timezone_set('America/Mexico_City');


include ('../includes/funcionesReferencias.php');


$serviciosReferencias 			= new ServiciosReferencias();

$fecha = date('Y-m-d H:i:s');

require('fpdf.php');

//$header = array("Hora", "Cancha 1", "Cancha 2", "Cancha 3");

////***** Parametros ****////////////////////////////////
$apellidopaterno     =     $_POST['apellidopaterno'];
$apellidomaterno     =     $_POST['apellidomaterno'];
$nombre              =     $_POST['nombre'];
$proveedor           =     $_POST['proveedor'];
$beneficiario        =     $_POST['beneficiario'];


/////////////////////////////  fin parametross  ///////////////////////////


$pdf = new FPDF();


function Footer($pdf)
{

$pdf->SetY(-10);

$pdf->SetFont('Arial','I',10);

$pdf->Cell(0,10,'Firma: ______________________________________________  -  Pagina '.$pdf->PageNo()." - Fecha: ".date('Y-m-d'),0,0,'C');
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
	$pdf->Cell(5,5,'',1,0,'C',true);
	$pdf->Cell(60,5,'CLIENTE: ',1,0,'C',true);
	$pdf->Cell(60,5,'PROVEEDOR: ',1,0,'C',true);
	$pdf->Cell(60,5,'BENEFICIARIO: ',1,0,'C',true);

$pdf->Ln();


Footer($pdf);



$nombreTurno = "CONSTANCIA-".$fecha.".pdf";

$pdf->Output($nombreTurno,'I');


?>

