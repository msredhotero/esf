<?php set_time_limit(0); ?>
<?php session_start(); ?>
<?php ob_start(); ?> 
<?php
include ("db.php");
include("phpmkrfn.php");

$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$x_fecha =  date("Y-m-d"); //


$x_fecha_fin  = "2013-12-31";


$sqlCreditoActivo =  "SELECT * FROM credito WHERE credito_status_id = 1 and credito_id not in (15, 1064,2499 ) ";//and credito_id = 6977 ";// and credito_id not in (15, 1064,2499 )"; 
$rsCreditoActivo = phpmkr_query($sqlCreditoActivo, $conn) or die ("Error al seleccionar los credito activos".phpmkr_error()."sql:".$sqlCreditoActivo);
while ($rowCReditoActivo = phpmkr_fetch_array($rsCreditoActivo)){
	$x_credito_id = $rowCReditoActivo["credito_id"];
	$x_iva = $rowCReditoActivo["iva"];	
	$x_importe = $rowCReditoActivo["importe"];
	$x_fecha_otorgamiento = $rowCReditoActivo["fecha_otrogamiento"];
	$x_credito_num = $rowCReditoActivo["credito_num"];
	
	$sqlPrimerVencimiento = "SELECT fecha_vencimiento AS primer_vencimiento  FROM  vencimiento WHERE credito_id = $x_credito_id and vencimiento_num = 1 ORDER BY vencimiento_id ASC";
	$rsPrimerVencimiento = phpmkr_query($sqlPrimerVencimiento,$conn) or die("Erro al seleccionar el primer vencimiento");
	$rowPrimerVencimiento = phpmkr_fetch_array($rsPrimerVencimiento);
	$x_primer_vencimiento = $rowPrimerVencimiento["primer_vencimiento"];
	
	if($x_primer_vencimiento <= "2014-01-31"){
	
	$sqlVencimientosPagados = "SELECT SUM(total_venc) as total_pagado FROM vencimiento WHERE credito_id = $x_credito_id and vencimiento_status_id = 2 ";
	$rsVencimientosPagados =  phpmkr_query($sqlVencimientosPagados, $conn)or die ("Error al seleccionar los pagados".phpmkr_error());
	$rowVencimientosPagados = phpmkr_fetch_array($rsVencimientosPagados);
	$x_total_pagado =  $rowVencimientosPagados["total_pagado"];
	echo "<BR>Total pagado  ".$x_total_pagado;
	
	$sqlVenicmientosInteresesDevengados = "SELECT SUM(interes) AS interes_devengado FROM vencimiento WHERE credito_id = $x_credito_id and fecha_vencimiento <= \"$x_fecha_fin \"";
	$rsVencimientosInteresesDevengados = phpmkr_query($sqlVenicmientosInteresesDevengados,$conn)or die ("Error al seleciconar devengados ".phpmkr_error()."sql:".$sqlVenicmientosInteresesDevengados);
	$rowVencimientosInteresesDevengados = phpmkr_fetch_array($rsVencimientosInteresesDevengados);
	$x_intereses_devengados = $rowVencimientosInteresesDevengados["interes_devengado"];
	
	echo "INTERESES DEVENGADOS ".$x_intereses_devengados."<BR>";
	
	$sqlVenicmientosIvaDevengados = " SELECT SUM(iva) AS iva_devengado FROM vencimiento WHERE credito_id = $x_credito_id and fecha_vencimiento <= \"$x_fecha_fin \"";
	$rsVencimientosIvaDevengados = phpmkr_query($sqlVenicmientosIvaDevengados,$conn)or die ("Error al seleciconar devengados ".phpmkr_error()."sql:".$sqlVenicmientosIvaDevengados);
	$rowVencimientosIvaDevengados = phpmkr_fetch_array($rsVencimientosIvaDevengados);
	$x_iva_devengados = $rowVencimientosIvaDevengados["iva_devengado"];
	echo "iva  ". $x_iva_devengados."<br>";
	
	$sqlVenicmientosMoraDevengados = " SELECT SUM(interes_moratorio) AS interes_moratorio_devengado FROM vencimiento WHERE credito_id = $x_credito_id and fecha_vencimiento <= \"$x_fecha_fin \"";
	$rsVencimientosMoraDevengados = phpmkr_query($sqlVenicmientosMoraDevengados,$conn)or die ("Error al seleciconar devengados ".phpmkr_error()."sql:".$sqlVenicmientosMoraDevengados);
	$rowVencimientosMoraDevengados = phpmkr_fetch_array($rsVencimientosMoraDevengados);
	$x_interes_moratorio_devengado = $rowVencimientosMoraDevengados["interes_moratorio_devengado"];
	
	echo "mora ".$x_interes_moratorio_devengado."<br>";
	
	
	$sqlVenicmientosIvaMoraDevengados = " SELECT SUM(iva_mor) AS iva_moratorio_devengado FROM vencimiento WHERE credito_id = $x_credito_id and fecha_vencimiento <= \"$x_fecha_fin \"";
	$rsVencimientosIvaMoraDevengados = phpmkr_query($sqlVenicmientosIvaMoraDevengados,$conn)or die ("Error al seleciconar devengados ".phpmkr_error()."sql:".$sqlVenicmientosIvaMoraDevengados);
	$rowVencimientosIvaMoraDevengados = phpmkr_fetch_array($rsVencimientosIvaMoraDevengados);
	$x_iva_moratorio_devengado = $rowVencimientosIvaMoraDevengados["iva_moratorio_devengado"];
	 echo "mora iva ".$x_iva_moratorio_devengado;
	
	$x_saldo_final = ($x_importe + ($x_intereses_devengados + $x_iva_devengados + $x_interes_moratorio_devengado + $x_iva_moratorio_devengado)) - $x_total_pagado;
	
	
	echo "<BR> CREDITO NUM ".$x_credito_num ." SALDO FINAL ".$x_saldo_final ."credito_id ". $x_credito_id ;
	
	$SQLSaldoFinal = "INSERT INTO `estado_cuenta` (`estado_cuenta_id`, `credito_id`, `fecha`, `numero`, `saldo_promedio`, `tasa_bruta`, `dias_periodo`, `saldo_final`, `tipo_cuenta`) ";
	$SQLSaldoFinal .= " VALUES (NULL, $x_credito_id, '2013-12-31', '0', '0', '0', '0', $x_saldo_final, '0') ";
	$RSSALDOFINAL = phpmkr_query($SQLSaldoFinal,$conn) or die ("Error en saldo final".phpmkr_error());
	
	
	
	}else{
		echo "***********************************************************".$x_credito_id ."--- ".$x_credito_num."**************************************************";
		} // primer vencimiento para febreo



}


?>