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
$idioma		=	$_GET['idioma'];

$id         =  $_GET['id'];

$resTaxa = $serviciosReferencias->traerTaxa();

$taxaPer = mysql_result($resTaxa,0,1);
$taxaTur = mysql_result($resTaxa,0,2);
$taxaMax = mysql_result($resTaxa,0,3);

$resLloguer =  $serviciosReferencias->traerLloguersPorIdCompleto($id);

$resLloguerAdicional =  $serviciosReferencias->traerLloguersadicionalPorLloguer($id);

$resPagos = $serviciosReferencias->traerPagosPorLloguers($id);

$totalTaxaPersona = 0;

/*
if (mysql_result($resLloguer,0,'dias') < 7) {
	$totalTaxaPersona = mysql_result($resLloguer,0,'numpertax') * 1 * $taxaPer;
} else {
	$totalTaxaPersona = mysql_result($resLloguer,0,'numpertax') * mysql_result($resLloguer,0,'dias') / 7 * $taxaPer;
}
*/
//die(var_dump($taxaPer));


$taxaturisticaAdicional = 0;
$taxaturisticaAdicionalPersonas = 0;

/*
$taxaturisticaAdicional = 1 * mysql_result($resLloguer,0,'dias') * $taxaTur;

if ($taxaturisticaAdicional > $taxaMax) {
	$taxaturisticaAdicional  = mysql_result($resLloguer,0,'numpertax') * $taxaMax;
} else {
	$taxaturisticaAdicional = mysql_result($resLloguer,0,'numpertax') * mysql_result($resLloguer,0,'dias') * $taxaTur;
}
*/
//die(var_dump($taxaturisticaAdicional));

while ($rowAd = mysql_fetch_array($resLloguerAdicional)) {
	/*
	$totalTaxaTuristica = 1 * $rowAd['dias'] * $rowAd['taxaturistica'];

	if ($totalTaxaTuristica > $taxaMax) {
		$totalTaxaTuristica  = $rowAd['personas'] * $taxaMax;
	} else {
		$totalTaxaTuristica = $rowAd['personas'] * $rowAd['dias'] * $rowAd['taxaturistica'];
	}
	*/
	$taxaturisticaAdicionalPersonas += $rowAd['personas'];

	$taxaturisticaAdicional += $rowAd['taxaturistica'];

	$totalTaxaPersona += $rowAd['taxapersona'];
	/*
	if ($rowAd['dias'] < 7) {
		$totalTaxaPersona += $rowAd['personas'] * 1 * mysql_result($resLloguer,0,'taxa');
	} else {
		$totalTaxaPersona += $rowAd['personas'] * $rowAd['dias'] / 7 * $rowAd['taxapersona'];
	}
	*/
}


$fechaInicio	=	strtotime(mysql_result($resLloguer,0,'entrada'));
$fechaFin		=	strtotime(mysql_result($resLloguer,0,'sortida'));

$periodoLbl = '';
$totalTarifa = 0;
$k = -1;
$dias = 0;

for($i=$fechaInicio+86400; $i<=$fechaFin; $i+=86400){
	$resPeriodo = $serviciosReferencias->calcularCoeficienteTarifa(mysql_result($resLloguer,0,'idtipoubicacion'),date("Y-m-d", $i));
	//echo $resPeriodo['periodo'];
	if ($resPeriodo['periodo'] != $periodoLbl) {
		$totalTarifa = 0;
		$dias += 1;
		$k += 1;
		$periodoLbl = $resPeriodo['periodo'];
		$totalTarifa += round($resPeriodo['tarifa'],2);
		$detalleLloguer[] = array(
			'periodo' => $resPeriodo['periodo'],
			$periodoLbl=> $totalTarifa,
			'precio'=> round($resPeriodo['precio'],2),
			'dias'.$periodoLbl => $dias
		);
		$dias = 0;

	} else {
		$detalleLloguer[$k][$periodoLbl] += round($resPeriodo['tarifa'],2);
		$detalleLloguer[$k]['dias'.$periodoLbl] += 1;
	}

}

if (isset($_GET['any'])) {
	$any = $_GET['any'];
} else {
	$any = date('Y',strtotime( mysql_result($resLloguer,0,'entrada')));
}

$resPeriodos = $serviciosReferencias->traerPeriodosPorOrdenPorAny($any);
$resTipo =     $serviciosReferencias->traerTipoubicacion();

$countX = mysql_num_rows($resTipo);

if ($countX == 0) {
	$withX = 160 / 1;
} else {
	$withX = 160 / $countX;
}


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



	//////////////////// Aca arrancan a cargarse los datos de los equipos  /////////////////////////


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

   $pdf->SetFont('Arial','B',14);
   $pdf->Ln();
   $pdf->SetX(5);
   $pdf->Cell(130,5,utf8_decode('CONTRATO Nº: ').$id,0,0,'L',false);

   $pdf->SetFont('Arial','',11);
   $pdf->SetTextColor(0,0,0);
   $pdf->setX(130);
   $pdf->Cell(70,5,utf8_decode(mysql_result($resLloguer,0,'nom').' '.mysql_result($resLloguer,0,'cognom')),0,0,'L',false);

   $pdf->Ln();
   $pdf->setX(130);
   $pdf->Cell(70,5,'NIF '.mysql_result($resLloguer,0,'nif'),0,0,'L',false);

   $pdf->Ln();
   $pdf->setX(130);
   $pdf->Cell(70,5,utf8_decode(mysql_result($resLloguer,0,'carrer')),0,0,'L',false);

   $pdf->Ln();
   $pdf->setX(130);
   $pdf->Cell(70,5,mysql_result($resLloguer,0,'codipostal').'  '.utf8_decode(mysql_result($resLloguer,0,'ciutat')),0,0,'L',false);

   $pdf->Ln();
   $pdf->setX(130);
   $pdf->Cell(70,5,utf8_decode(mysql_result($resLloguer,0,'pais')),0,0,'L',false);

   $pdf->Ln();
   $pdf->Ln();
   $pdf->setX(5);
   $pdf->Cell(70,5,'Estimado/a Sr. / Sra '.utf8_decode(mysql_result($resLloguer,0,'nom')),0,0,'L',false);

   $pdf->Ln();
   $pdf->Ln();
   $pdf->setX(5);
   $pdf->MultiCell(200,5,utf8_decode('Tenemos reservado para usted el apartamento N° '.mysql_result($resLloguer,0,'codapartament').' de '.mysql_result($resLloguer,0,'dormitorio').' dormitorio/s '.mysql_result($resLloguer,0,'dias').' dias, del '.mysql_result($resLloguer,0,'entradacorta').' (17h) hasta '.mysql_result($resLloguer,0,'sortidacorta').' (9h). Pueden pagar por transferencia bancaria'),0,'L',false);

	$pdf->Ln();
   $pdf->setX(5);
   $pdf->Cell(70,5,'Atentamente, Apartaments Casa Caliente',0,0,'L',false);

	$pdf->Ln();
   $pdf->Line(5, 112, 200, 112);

	$pdf->Ln();
   $pdf->setX(5);
   $pdf->Cell(70,5,'Detalle del pago',0,0,'L',false);

	$pdf->SetFillColor(195,195,195);

	$pdf->Ln();
	$pdf->Ln();
   $pdf->setX(5);
   $pdf->Cell(40,5,'PERIODO',0,0,'C',true);
	$pdf->Cell(40,5,'DIAS',0,0,'C',true);
	$pdf->Cell(40,5,'TARIFA',0,0,'C',true);
	$pdf->Cell(40,5,'PERSONAS',0,0,'C',true);
	$pdf->Cell(40,5,'PRECIO',0,0,'C',true);

	$totalTarifaParcial = 0;

	for ($j=0;$j<count($detalleLloguer);$j++) {
		//echo $j;
		$pdf->Ln();
	   $pdf->setX(5);
	   $pdf->Cell(40,5,$detalleLloguer[$j]['periodo'],0,0,'C',false);
		$pdf->Cell(40,5,$detalleLloguer[$j]['dias'.$detalleLloguer[$j]['periodo']],0,0,'C',false);
		$pdf->Cell(40,5,$detalleLloguer[$j]['precio'].' '.EURO,0,0,'C',false);
		$pdf->Cell(40,5,mysql_result($resLloguer,0,'numpertax'),0,0,'C',false);
		$pdf->Cell(40,5,$detalleLloguer[$j][$detalleLloguer[$j]['periodo']].' '.EURO,0,0,'C',false);

		$totalTarifaParcial += $detalleLloguer[$j][$detalleLloguer[$j]['periodo']];
	}

	$pdf->SetFont('Arial','',10);
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Ln();
   $pdf->setX(5);
   $pdf->Cell(170,6,utf8_decode('Apart. N°').mysql_result($resLloguer,0,'codapartament').' x '.mysql_result($resLloguer,0,'dias').utf8_decode(' días = '),0,0,'R',false);
	$pdf->Cell(30,6,number_format( $totalTarifaParcial,2,',','.').' '.EURO,0,0,'R',false);

	$pdf->Ln();
   $pdf->setX(5);
   $pdf->Cell(170,6,utf8_decode('Nº personas. x Nº semanas x 20 = '),0,0,'R',false);
	$pdf->Cell(30,6,number_format( $totalTaxaPersona,2,',','.').' '.EURO,0,0,'R',false);

	$pdf->Ln();
   $pdf->setX(5);
   $pdf->Cell(170,6,utf8_decode('(Impuesto sobre estancias en establecimientos turísticos) '.$taxaturisticaAdicionalPersonas.' personas mayores de 16 años x '.mysql_result($resLloguer,0,'dias').' días x ').$taxaTur.EURO.' = ',0,0,'R',false);
	$pdf->Cell(30,6,number_format( $taxaturisticaAdicional,2,',','.').' '.EURO,0,0,'R',false);

	$pdf->SetFont('Arial','B',12);

	$pdf->Ln();
	$pdf->Line(170, $pdf->getY(), 205, $pdf->getY());

	$pdf->Ln();
   $pdf->setX(5);
   $pdf->Cell(170,6,utf8_decode('Suma total = '),0,0,'R',false);
	$pdf->Cell(30,6,number_format( $totalTarifaParcial + $totalTaxaPersona + $taxaturisticaAdicional,2,',','.').' '.EURO,0,0,'R',false);

	$pdf->Ln();
	$k = 0;
	$pdf->SetFont('Arial','',11);
	while ($row = mysql_fetch_array($resPagos))
	{
		$k += 1;
		$pdf->Ln();
	   $pdf->setX(5);
	   $pdf->Cell(200,6,$k.utf8_decode('º Pago de ').number_format( ($row['cuota'] + $row['taxa']),2,',','.').' '.EURO.' antes del dia '.date('d/m/Y',strtotime( $row['fechapago'])),0,0,'R',false);
	}




	$pdf->SetFont('Arial','',9);
	$pdf->Ln();
	$pdf->setX(5);
	$pdf->Cell(200,6,utf8_decode('(Posibles gastos bancarios a cargo del inquilino)'),0,0,'R',false);

	$pdf->SetFont('Arial','',11);
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Ln();
	$pdf->setX(5);
	$pdf->Cell(200,6,utf8_decode('Banco; Banco Sabadell, nr. 0081 0213 31 0001951501 a nombre de IJSSELSTEIN KROM CB. Roses'),0,0,'L',false);

	$pdf->Ln();
	$pdf->setX(5);
	$pdf->Cell(200,6,utf8_decode('IBAN: ES40 0081 0213 31 0001951501'),0,0,'L',false);

	$pdf->Ln();
	$pdf->setX(5);
	$pdf->Cell(200,6,utf8_decode('Nombre: IJSSELSTEIN KROM CB. Dirección: Rahola Molinas Nº5, 17480 Roses - España. NIF: E55293088'),0,0,'L',false);

	//$pdf->SetY($contadorY1);

/******************************************************************************** fin primera pagina *********************/



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

$pdf->SetFont('Arial','B',17);
$pdf->Ln();
$pdf->Ln();
$pdf->SetX(5);
$pdf->Cell(130,5,utf8_decode('PRECIOS Y CONDICIONES'),0,0,'L',false);


$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial','',12);

$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->setX(5);
$pdf->SetFont('Arial','BU',11);
$pdf->Cell(23,5,utf8_decode('Los precios'),0,0,'L',false);
$pdf->SetFont('Arial','',11);
$pdf->Cell(177,5,utf8_decode('incluyen el uso completo del inventario, sábanas una plaza de aparcamiento y de la piscina.'),0,0,'L',false);

$pdf->Ln();
$pdf->setX(5);
$pdf->Cell(180,5,utf8_decode('En invierno una estufa de gas butano o eléctrica'),0,0,'L',false);

$pdf->Ln();
$pdf->Ln();
$pdf->setX(5);
$pdf->SetFont('Arial','BU',11);
$pdf->Cell(28,5,utf8_decode('No se incluyen'),0,0,'L',false);
$pdf->SetFont('Arial','',11);
$pdf->Cell(172,5,utf8_decode('las toallas y trapos de cocina.'),0,0,'L',false);


$pdf->Ln();
$pdf->Ln();
$pdf->setX(5);
$pdf->SetFont('Arial','BU',11);
$pdf->Cell(46,5,utf8_decode('Los periodos de alquiler'),0,0,'L',false);
$pdf->SetFont('Arial','',11);
$pdf->Cell(70,5,utf8_decode('empiezan el sábado de su llegada a las'),0,0,'L',false);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(10,5,utf8_decode('17 h'),0,0,'L',false);
$pdf->SetFont('Arial','',11);
$pdf->Cell(40,5,utf8_decode('y terminan el sábado de su salida'),0,0,'L',false);
$pdf->Ln();
$pdf->setX(5);
$pdf->Cell(9,5,utf8_decode('a las'),0,0,'L',false);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(10,5,utf8_decode('9h.'),0,0,'L',false);


$pdf->Ln();
$pdf->Ln();
$pdf->setX(5);
$pdf->SetFont('Arial','BU',11);
$pdf->Cell(12,5,utf8_decode('Pago'),0,0,'L',false);
$pdf->SetFont('Arial','',11);
$pdf->Cell(35,5,utf8_decode('50 % en el plazo de'),0,0,'L',false);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(4,5,utf8_decode('5'),0,0,'C',false);
$pdf->SetFont('Arial','',11);
$pdf->Cell(151,5,utf8_decode(' dias después de recibir nuestra confirmación de reserva y el resto 30 dias'),0,0,'L',false);
$pdf->Ln();
$pdf->setX(5);
$pdf->SetFont('Arial','',11);
$pdf->Cell(200,5,utf8_decode('antes del comienzo del periodo de alquiler.'),0,0,'L',false);

$pdf->Ln();
$pdf->Ln();
$pdf->setX(5);
$pdf->SetFont('Arial','',11);
$pdf->Cell(200,5,utf8_decode('En caso de no cumplir con nuestras condiciones, nos reservamos el derecho de cancelar su reserva, sin'),0,0,'L',false);
$pdf->Ln();
$pdf->setX(5);
$pdf->SetFont('Arial','',11);
$pdf->Cell(200,5,utf8_decode('previo aviso.'),0,0,'L',false);

$pdf->Ln();
$pdf->Ln();
$pdf->setX(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(200,5,utf8_decode('Les aconsejamos que contraten un seguro de anulacion - vacaciones.'),0,0,'L',false);

$pdf->Ln();
$pdf->Ln();
$pdf->setX(5);
$pdf->SetFont('Arial','',11);
$pdf->Cell(200,5,utf8_decode('Animales de compañia, no se admiten.'),0,0,'L',false);


$pdf->Ln();
$pdf->setX(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(200,5,utf8_decode('Precios por semana (I.V.A. incluido)'),1,0,'C',false);

$pdf->Ln();
$pdf->setX(5);
$pdf->SetFont('Arial','B',14);
$pdf->MultiCell(40,5,utf8_decode('Temporada ').$any,1,'C',false);

$yAux = $pdf->getY() - 10;

$pdf->SetFont('Arial','B',9);
$yC = 1;
while ($rowY = mysql_fetch_array($resTipo)) {
	$pdf->setY($yAux);
	$pdf->setX(($yC * $withX) + 25);
	$pdf->MultiCell($withX,5,$rowY['tipoubicacion'],1,'C',false);
	$yC += 1;
}


while ($rowX = mysql_fetch_array($resPeriodos)) {

	$resTipoAux =    $serviciosReferencias->traerTipoubicacion();

	$pdf->setX(5);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(10,5,$rowX['periodo'],1,0,'C',false);
	$pdf->Cell(30,5,date('d/m', strtotime( $rowX['desdeperiode'])).' - '.date('d/m', strtotime( $rowX['finsaperiode'])),1,0,'C',false);

	while ($rowY = mysql_fetch_array($resTipoAux)) {
		$resTarifa = $serviciosReferencias->traerTarifasPorPeriodoTipoUbicacion($rowX[0],$rowY[0]);
		if (mysql_num_rows($resTarifa)>0) {
			$pdf->Cell($withX,5,mysql_result($resTarifa,0,'tarifa'),1,0,'C',false);
		} else {
			$pdf->Cell($withX,5,'0',1,0,'C',false);
		}

	}
	$pdf->Ln();
}

$pdf->setX(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(200,5,utf8_decode('Suplemento por persona: 20 ').EURO.utf8_decode(' por persona y semana. (adulto, niño o bebé).'),1,0,'L',false);

$pdf->Ln();
$pdf->setX(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(200,5,utf8_decode('No se admiten animales de compañia.'),1,0,'L',false);

$pdf->Ln();
$pdf->Ln();
$pdf->setX(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(200,6,utf8_decode('A los precios indicados hay que añadir el impuesto sobre estancias en establecimientos turísticos, a '),0,0,'L',false);
$pdf->Ln();
$pdf->setX(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(200,6,utf8_decode('partir de 17 años: 0,90 ').EURO.utf8_decode(' pers / día con un máximo de 7 unidades de estancia por persona (= 6,30').EURO.utf8_decode(' / pers)'),0,0,'L',false);



/************************************  fin de la tercer pagina *****************************************************************/


$pdf->AddPage();
/***********************************    PRIMER CUADRANTE ******************************************/

$pdf->Image('../imagenes/logo_casa_caliente.png',150,12,40);

/***********************************    FIN ******************************************/

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


$pdf->SetTextColor(0,0,0);

$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Arial','',9);
$pdf->SetX(5);
$pdf->MultiCell(200,5,utf8_decode('De conformidad con lo establecido en el Art. 12.2 del R.D. 1720/2007, de 21 de diciembre, por el que se aprueba el Reglamento de desarrollo de la Ley Orgánica 15/1999, de 13 de diciembre, de Protección de Datos de carácter personal, Vd. queda informado y consiente expresamente que los datos de carácter personal que proporciona al rellenar el presente Contrato, serán incorporados a los ficheros de IJsselstein Krom C.B, con domicilio en Rahola Molinas 5, 17480 Roses, para que éste pueda efectuar el tratamiento, automatizado o no, de los mismos con la finalidad de recabar los datos básicos del cliente, prestando su consentimiento expreso para que dichos datos puedan ser comunicados para su utilización con los fines anteriores a otras Entidades. Así mismo, queda informado que podrá ejercer los derechos de acceso, rectificación, cancelación y oposición dirigiéndose a la dirección indicada anteriormente.'),0,'L',false);

$pdf->Ln();
$pdf->Ln();
$pdf->Ln();


Footer($pdf);



$nombreTurno = "CONTRACT-".$fecha.".pdf";

$pdf->Output($nombreTurno,'I');


?>
