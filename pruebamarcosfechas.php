<?php

define("DEFAULT_CURRENCY_SYMBOL", "$");
define("DEFAULT_MON_DECIMAL_POINT", ".");
define("DEFAULT_MON_THOUSANDS_SEP", ",");
define("DEFAULT_POSITIVE_SIGN", "");
define("DEFAULT_NEGATIVE_SIGN", "-");
define("DEFAULT_FRAC_DIGITS", 2);
define("DEFAULT_P_CS_PRECEDES", true);
define("DEFAULT_P_SEP_BY_SPACE", false);
define("DEFAULT_N_CS_PRECEDES", true);
define("DEFAULT_N_SEP_BY_SPACE", false);
define("DEFAULT_P_SIGN_POSN", 3);
define("DEFAULT_N_SIGN_POSN", 3);


define("DEFAULT_DATE_FORMAT", "dd/mm/yyyy");
define("EW_DATE_SEPARATOR","/");


function ConvertDateToMysqlFormat($dateStr)
{
	@list($datePt, $timePt) = explode(" ", $dateStr);
	$arDatePt = explode(EW_DATE_SEPARATOR, $datePt);
	if (count($arDatePt) == 3) {
		switch (DEFAULT_DATE_FORMAT) {
		case "yyyy" . EW_DATE_SEPARATOR . "mm" . EW_DATE_SEPARATOR . "dd":
		    list($year, $month, $day) = $arDatePt;
		    break;
		case "mm" . EW_DATE_SEPARATOR . "dd" . EW_DATE_SEPARATOR . "yyyy":
		    list($month, $day, $year) = $arDatePt;
		    break;
		case "dd" . EW_DATE_SEPARATOR . "mm" . EW_DATE_SEPARATOR . "yyyy":
		    list($day, $month, $year) = $arDatePt;
		    break;
		}
		return trim($year . "-" . $month . "-" . $day . " " . $timePt);
	} else {
		return $dateStr;
	}
}

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


$fechaAA = '2019-09-20';

$x_forma_pago = 15;

$temptime = strtotime(ConvertDateToMysqlFormat(ConvertDateToMysqlFormat($fechaAA)));

$x_fecha_dd = ConvertDateToMysqlFormat($fechaAA);


$fecha_act = DateAdd('m',1,$temptime);

$fecha_act = DateAdd('w',$x_forma_pago,$temptime);


$fecha_act = strftime('%Y-%m-%d',$temptime);

$x_dia = strftime('%A',$temptime);

echo $temptime.'<br><br>';

echo $x_fecha_dd.'<br><br>';

echo $fecha_act.'<br><br>';



?>