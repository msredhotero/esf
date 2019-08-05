<?php
function datediff($interval, $datefrom, $dateto, $using_timestamps = false) {
  /*
    $interval can be:
    yyyy - Number of full years
    q - Number of full quarters
    m - Number of full months
    y - Difference between day numbers
      (eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
    d - Number of full days
    w - Number of full weekdays
    ww - Number of full weeks
    h - Number of full hours
    n - Number of full minutes
    s - Number of full seconds (default)
  */

  if (!$using_timestamps) {
    $datefrom = strtotime($datefrom, 0);
    $dateto = strtotime($dateto, 0);
  }
  $difference = $dateto - $datefrom; // Difference in seconds

  switch($interval) {

    case 'yyyy': // Number of full years

      $years_difference = floor($difference / 31536000);
      if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom)+$years_difference) > $dateto) {
        $years_difference--;
      }
      if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) {
        $years_difference++;
      }
      $datediff = $years_difference;
      break;

    case "q": // Number of full quarters

      $quarters_difference = floor($difference / 8035200);
      while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
        $months_difference++;
      }
      $quarters_difference--;
      $datediff = $quarters_difference;
      break;

    case "m": // Number of full months

      $months_difference = floor($difference / 2678400);
      while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
        $months_difference++;
      }
      $months_difference--;
      $datediff = $months_difference;
      break;

    case 'y': // Difference between day numbers

      $datediff = date("z", $dateto) - date("z", $datefrom);
      break;

    case "d": // Number of full days

      $datediff = floor($difference / 86400);
      break;

    case "w": // Number of full weekdays

      $days_difference = floor($difference / 86400);
      $weeks_difference = floor($days_difference / 7); // Complete weeks
      $first_day = date("w", $datefrom);
      $days_remainder = floor($days_difference % 7);
      $odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
      if ($odd_days > 7) { // Sunday
        $days_remainder--;
      }
      if ($odd_days > 6) { // Saturday
        $days_remainder--;
      }
      $datediff = ($weeks_difference * 5) + $days_remainder;
      break;

    case "ww": // Number of full weeks

      $datediff = floor($difference / 604800);
      break;

    case "h": // Number of full hours

      $datediff = floor($difference / 3600);
      break;

    case "n": // Number of full minutes

      $datediff = floor($difference / 60);
      break;

    default: // Number of full seconds (default)

      $datediff = $difference;
      break;
  }

  return $datediff;

}


// per = yyyy (año) m (mes) ww (semanas) d (diasjour) h (horas) n (minutos) s (segundos)
function dateadd_back($per,$n,$d) {
   switch($per) {
      case "yyyy": $n*=12;
      case "m":
         $d=mktime(date("H",$d),date("i",$d)
            ,date("s",$d),date("n",$d)+$n
            ,date("j",$d),date("Y",$d));
         $n=0; break;
      case "ww": $n*=7;
      case "d": $n*=24;
      case "h": $n*=60;
      case "n": $n*=60;
   }
   return $d+$n;
}


/* interval values
yyyy    year
q    Quarter
m    Month
y    Day of year
d    Day
w    Weekday
ww    Week of year
h    Hour
n    Minute
s    Second

asi se usa
$temptime = time();
$temptime = DateAdd('m',$row_ser["duracion_meses"],$temptime);
$fecha_fin = strftime('%Y-%m-%d',$temptime);

*/
function DateAdd($interval, $number, $date) {
    $date_time_array = getdate($date);
    $hours = $date_time_array['hours'];
    $minutes = $date_time_array['minutes'];
    $seconds = $date_time_array['seconds'];
    $month = $date_time_array['mon'];
    $day = $date_time_array['mday'];
    $year = $date_time_array['year'];

    switch ($interval) {

        case 'yyyy':
            $year+=$number;
            break;
        case 'q':
            $year+=($number*3);
            break;
        case 'm':
            $month+=$number;
            break;
        case 'y':
        case 'd':
        case 'w':
            $day+=$number;
            break;
        case 'ww':
            $day+=($number*7);
            break;
        case 'h':
            $hours+=$number;
            break;
        case 'n':
            $minutes+=$number;
            break;
        case 's':
            $seconds+=$number;
            break;
    }
    $timestamp= mktime($hours,$minutes,$seconds,$month,$day,$year);
    return $timestamp;
}

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

function FechaLetras_normal($x_fecha, $x_mexico){
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
			$x_dia = " diecises ";
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
			$x_dia = " veintidos ";
            break;
        case 23:
			$x_dia = " veintitres ";
            break;
        case 24:
			$x_dia = " veinticuatro ";
            break;
        case 25:
			$x_dia = " veinticinco ";
            break;
        case 26:
			$x_dia = " veintiseis ";
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
			$x_mes = " Enero ";
            break;
        case 2:
			$x_mes = " Febrero ";
            break;
        case 3:
			$x_mes = " Marzo ";
            break;
        case 4:
			$x_mes = " Abril ";
            break;
        case 5:
			$x_mes = " Mayo ";
            break;
        case 6:
			$x_mes = " Junio ";
            break;
        case 7:
			$x_mes = " Julio ";
            break;
        case 8:
			$x_mes = " Agosto ";
            break;
        case 9:
			$x_mes = " Septiembre ";
            break;
        case 10:
			$x_mes = " Octubre ";
            break;
        case 11:
			$x_mes = " Noviembre ";
            break;
        case 12:
			$x_mes = " Diciembre ";
            break;
	}

	if($x_mexico){
		$fecha_letras = "M&eacute;xico, D.F. a $day de $x_mes del $year";
	}else{
		$fecha_letras = " $day de $x_mes del $year";	
	}
	
    return $fecha_letras;
}
