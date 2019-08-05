<?php
function FechaLetras($x_fecha){
    $date_time_array = getdate($x_fecha);
    $month = $date_time_array['mon'];
    $day = $date_time_array['mday'];
    $year = $date_time_array['year'];

    switch ($day) {
        case 1:
			$x_dia = " primero ";
            break;
        case 2:
			$x_dia = " dos ";
            break;
        case 3:
			$x_dia = " tres ";
            break;
        case 4:
			$x_dia = " cuatro ";
            break;
        case 5:
			$x_dia = " cinco ";
            break;
        case 6:
			$x_dia = " seis ";
            break;
        case 7:
			$x_dia = " siete ";
            break;
        case 8:
			$x_dia = " ocho ";
            break;
        case 9:
			$x_dia = " nueve ";
            break;
        case 10:
			$x_dia = " diez ";
            break;
        case 11:
			$x_dia = " once ";
            break;
        case 12:
			$x_dia = " doce ";
            break;
        case 13:
			$x_dia = " trece ";
            break;
        case 14:
			$x_dia = " catorce ";
            break;
        case 15:
			$x_dia = " quince ";
            break;
        case 16:
			$x_dia = " diecis&eacute;is ";
            break;
        case 17:
			$x_dia = " diecisiete ";
            break;			
        case 18:
			$x_dia = " dieciocho ";
            break;
        case 19:
			$x_dia = " diecinueve ";
            break;
        case 20:
			$x_dia = " veinte ";
            break;
        case 21:
			$x_dia = " veintiuno ";
            break;
        case 22:
			$x_dia = " veintid&oacute;s ";
            break;
        case 23:
			$x_dia = " veintitr&eacute;s ";
            break;
        case 24:
			$x_dia = " veinticuatro ";
            break;
        case 25:
			$x_dia = " veinticinco ";
            break;
        case 26:
			$x_dia = " veintis&eacute;is ";
            break;
        case 27:
			$x_dia = " veintisiete ";
            break;
        case 28:
			$x_dia = " veintiocho ";
            break;
        case 29:
			$x_dia = " veintinueve ";
            break;
        case 30:
			$x_dia = " treinta ";
            break;
        case 31:
			$x_dia = " treintaiuno ";
            break;
	}

    switch ($month) {
        case 1:
			$x_mes = " enero ";
            break;
        case 2:
			$x_mes = " febrero ";
            break;
        case 3:
			$x_mes = " marzo ";
            break;
        case 4:
			$x_mes = " abril ";
            break;
        case 5:
			$x_mes = " mayo ";
            break;
        case 6:
			$x_mes = " junio ";
            break;
        case 7:
			$x_mes = " julio ";
            break;
        case 8:
			$x_mes = " agosto ";
            break;
        case 9:
			$x_mes = " septiembre ";
            break;
        case 10:
			$x_mes = " octubre ";
            break;
        case 11:
			$x_mes = " noviembre ";
            break;
        case 12:
			$x_mes = " diciembre ";
            break;
	}

	$fecha_letras = $x_dia." de ".$x_mes." del a&ntilde;o ".$year;
	
    return $fecha_letras;
}

?>