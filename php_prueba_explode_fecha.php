<?php

include ("phpmkrfn.php");

$x_today  = date("Y-m-d");
$x_fe_explode = explode("-",$x_today);

echo "año".$x_fe_explode[0];
echo "mes".$x_fe_explode[1];
echo "dia".$x_fe_explode[2]."<br>";
$x_anio_c = $x_fe_explode[0];
$x_mes_c = $x_fe_explode[1];
$x_dia_c = $x_fe_explode[2];

$x_fecha_movimiento = $x_fecha_2;
$x_fecha_2 = $x_anio_c."/".$x_mes_c."/".$x_dia_c;
echo $x_fecha_2;

$x_fecha_movimiento = $x_fecha_2;
$x_1 = ConvertDateToMysqlFormat($x_fecha_movimiento);
echo " uno ".$x_1;
$stamp = strtotime(ConvertDateToMysqlFormat($x_fecha_movimiento));
echo "numero ".$stamp."<br>";
			if (!is_numeric($stamp)){
				$x_validado = false;			
				$x_err_msg .= "dato en fecha movimiento incorrecto. $x_fecha_movimiento<br>-- $stamp -- fecha mov-- $x_fecha_movimiento <br>";
			}
			
			
			
echo $x_err_msg;			
			
?>