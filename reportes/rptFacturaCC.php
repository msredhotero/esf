<?php

date_default_timezone_set('Europe/Madrid');

include ('../includes/funcionesUsuarios.php');
include ('../includes/funciones.php');
include ('../includes/funcionesHTML.php');
include ('../includes/funcionesReferencias.php');

define('EURO',chr(128));

$serviciosUsuarios  		= new ServiciosUsuarios();
$serviciosFunciones 		= new Servicios();
$serviciosHTML				= new ServiciosHTML();
$serviciosReferencias 			= new ServiciosReferencias();

$fecha = date('Y-m-d');

require('fpdf.php');

//$header = array("Hora", "Cancha 1", "Cancha 2", "Cancha 3");

////***** Parametros ****////////////////////////////////

$id         =  $_GET['id'];

$resTaxa = $serviciosReferencias->traerTaxa();

$taxaPer = mysql_result($resTaxa,0,1);
$taxaTur = mysql_result($resTaxa,0,2);
$taxaMax = mysql_result($resTaxa,0,3);

$resPagos = $serviciosReferencias->traerPagosPorIdCompleto($id);

if (mysql_num_rows($resPagos) > 0) {

	$idlloguer		=		mysql_result($resPagos,0,'reflloguers');
	$monto			=		mysql_result($resPagos,0,'monto');
	$taxa				=		mysql_result($resPagos,0,'taxa');
	$fechapago		=		mysql_result($resPagos,0,'fechapago');
	$formapago		=		mysql_result($resPagos,0,'formapago');

	$resLloguer =  $serviciosReferencias->traerLloguersPorIdCompleto($idlloguer);

	$resLloguerAdicional =  $serviciosReferencias->traerLloguersadicionalPorLloguer($idlloguer);


	$totalTaxaPersona = 0;

	$taxaturisticaAdicional = 0;
	$taxaturisticaAdicionalPersonas = 0;

	while ($rowAd = mysql_fetch_array($resLloguerAdicional)) {

		$taxaturisticaAdicionalPersonas += $rowAd['personas'];

		$taxaturisticaAdicional += $rowAd['taxaturistica'];

		$totalTaxaPersona += $rowAd['taxapersona'];

	}

	$fechaInicio	=	strtotime(mysql_result($resLloguer,0,'entrada'));
	$fechaFin		=	strtotime(mysql_result($resLloguer,0,'sortida'));

	$nom = utf8_decode(mysql_result($resLloguer,0,'nom'));
	$cognom = utf8_decode(mysql_result($resLloguer,0,'cognom'));
	$cuit = mysql_result($resLloguer,0,'nif');

	//die(var_dump($withX));

	$pdf = new FPDF();


	function Footer($pdf)
	{

	$pdf->SetY(-20);

	$pdf->SetFont('Arial','I',12);
	$pdf->Cell(0,10,utf8_decode( 'PLAYA CANYELLES PETITES - ROSES - COSTA BRAVA - ESPAÑA'),0,0,'C');
	$pdf->SetFont('Arial','I',10);
	$pdf->Cell(0,20,'Pagina '.$pdf->PageNo()." - Fecha: ".date('Y-m-d'),0,0,'R');
	}


	$cantidadJugadores = 0;
	#Establecemos los márgenes izquierda, arriba y derecha:
	//$pdf->SetMargins(2, 2 , 2);

	#Establecemos el margen inferior:
	$pdf->SetAutoPageBreak(false,1);

	$pdf->AddPage();
	/***********************************    PRIMER CUADRANTE ******************************************/
	$pdf->Image('../imagenes/logo_casa_caliente.png',150,12,40);
	/***********************************    FIN ******************************************/

	//////////////////// Aca arrancan a cargarse los datos  /////////////////////////
	$pdf->SetFillColor(183,183,183);
   $pdf->SetTextColor(110,110,110);
	$pdf->SetFont('Arial','B',26);
	$pdf->Ln();
	$pdf->Ln();
	$pdf->SetY(12);
	$pdf->SetX(5);
	$pdf->Cell(140,13,'Apartaments Casa Caliente',0,0,'L',false);
   $pdf->SetFont('Arial','B',9);

   $pdf->Ln();
   $pdf->SetX(5);
   $pdf->Cell(140,5,'RENTING ; TERRAZA APARTMENTS - PISCINA - SOLARIUM - PARKING PRIVE - JARDIN',0,0,'L',false);
   $pdf->SetFont('Arial','B',8);

   $pdf->Ln();
   $pdf->SetX(5);
   $pdf->Cell(140,5,utf8_decode('Apartaments Casa Caliente - Apartado 431 - 17480 Roses - España.'),0,0,'L',false);

   $pdf->Ln();
   $pdf->SetX(5);
   $pdf->Cell(140,5,'Tel. +34.972.255650 Fax. +34.972.150611',0,0,'L',false);

   $pdf->Ln();
   $pdf->SetX(5);
   $pdf->Cell(140,5,'www.casacaliente.net - casacaliente@casacaliente.net',0,0,'L',false);

   $pdf->Ln();
   $pdf->Line(5, 47, 200, 47);


	$pdf->SetFont('Arial','B',9);
	$pdf->Ln();
	$pdf->SetX(5);
	$pdf->Cell(18,5,'DATA',1,0,'C',false);
	$pdf->Cell(18,5,utf8_decode('N° FACT'),1,0,'C',false);
	$pdf->Cell(25,5,'NIF',1,0,'C',false);
	$pdf->Cell(40,5,'NOM',1,0,'C',false);
	$pdf->Cell(40,5,'COGNOM',1,0,'C',false);
	$pdf->Cell(25,5,'LLOGUERS',1,0,'C',false);
	$pdf->Cell(10,5,'IVA',1,0,'C',false);
	$pdf->Cell(25,5,'TOTAL',1,0,'C',false);

	$pdf->Ln();
	$pdf->SetX(5);
	$pdf->Cell(18,5,$fechapago,1,0,'C',false);
	$pdf->Cell(18,5,$id,1,0,'R',false);
	$pdf->Cell(25,5,$cuit,1,0,'C',false);
	$pdf->Cell(40,5,$nom,1,0,'L',false);
	$pdf->Cell(40,5,$cognom,1,0,'L',false);
	$pdf->Cell(25,5,number_format( $monto,2,',','.').' '.EURO,1,0,'R',false);
	$pdf->Cell(10,5,number_format( 0,2,',','.'),1,0,'R',false);
	$pdf->Cell(25,5,number_format( $monto,2,',','.').' '.EURO,1,0,'R',false);





	Footer($pdf);

	$nombreTurno = "FACTURA-".$fecha.".pdf";

	$pdf->Output($nombreTurno,'I');
} else {
	echo '<h4>No se encontro ninguna factura</h4>';
	exit();
}

?>
