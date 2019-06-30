<?php session_start(); ?>
<?php ob_start(); ?>
<?php
die();
// Initialize common variables

?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php include ("utilerias/datefunc.php") ?>
<?php


$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
$currdate = ConvertDateToMysqlFormat($currdate);
//$x_dia = strtoupper($currentdate["weekday"]);

//$currdate = "2007-07-10";
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);


//MORATORIOS
$sSqlWrk = "SELECT vencimiento.*, credito.credito_status_id, credito.importe as importe_credito, credito.tasa_moratoria, credito.credito_num+0 as crednum, credito.tasa, forma_pago.valor as forma_pago_valor FROM vencimiento join credito 
on credito.credito_id = vencimiento.credito_id join forma_pago on forma_pago.forma_pago_id = credito.forma_pago_id 
where credito.credito_status_id in (1,4) and vencimiento.fecha_vencimiento < '$currdate' and vencimiento.vencimiento_status_id in (1,3) order by vencimiento.fecha_vencimiento";

//" and credito.credito_num = '283'";
$rswrk = phpmkr_query($sSqlWrk,$conn) or exit();
while($datawrk = phpmkr_fetch_array($rswrk)){
	$x_vencimiento_id = $datawrk["vencimiento_id"];
	$x_vencimiento_num = $datawrk["vencimiento_num"];	
	$x_fecha_vencimiento = $datawrk["fecha_vencimiento"];
	$x_importe = $datawrk["importe"];	
	$x_interes = $datawrk["interes"];		
	$x_interes_moratorio = $datawrk["interes_moratorio"];			
	$x_tasa_moratoria = $datawrk["tasa_moratoria"];	
	$x_credito_num = $datawrk["crednum"];		
	$x_credito_status_id = $datawrk["credito_status_id"];
	$x_importe_credito = $datawrk["importe_credito"];			
	$x_forma_pago_valor = $datawrk["forma_pago_valor"];				
	$x_tasa = $datawrk["tasa"];		
	
		
	if(is_null($x_interes_moratorio)){
		$x_interes_moratorio = 0;
	}

	$x_dias_vencidos = datediff('d', $x_fecha_vencimiento, $currdate, false);	

	$x_dia = strtoupper(date('l',strtotime($x_fecha_vencimiento)));


	$x_dias_gracia = 2;
	switch ($x_dia)
	{
		case "MONDAY": // Get a record to display
			$x_dias_gracia = 2;
			break;
		case "TUESDAY": // Get a record to display
			$x_dias_gracia = 2;
			break;
		case "WEDNESDAY": // Get a record to display
			$x_dias_gracia = 4;
			break;
		case "THURSDAY": // Get a record to display
			$x_dias_gracia = 4;
			break;
		case "FRIDAY": // Get a record to display
			$x_dias_gracia = 3;
			break;
		case "SATURDAY": // Get a record to display
			$x_dias_gracia = 2;
			break;
		case "SUNDAY": // Get a record to display
			$x_dias_gracia = 2;
			break;		
	}

	echo "credito: $x_credito_num  dias Venc: $x_dias_vencidos  Dias Gracia: $x_dias_gracia <br>";
	if($x_dias_vencidos > $x_dias_gracia){
		$x_importe_mora = $x_tasa_moratoria * $x_dias_vencidos;
		$x_tot_venc = $x_importe + $x_interes + $x_importe_mora;		

		if($x_credito_num > 809){
		echo "SI $x_importe - $x_interes - $x_importe_mora   -   $x_tot_venc<br>";
//		$sSqlWrk = "update vencimiento set vencimiento_status_id = 3, interes_moratorio = $x_importe_mora, total_venc = $x_tot_venc where vencimiento_id = $x_vencimiento_id ";	
		}else{
		echo "NO<br>";			
//		$sSqlWrk = "update vencimiento set vencimiento_status_id = 3 where vencimiento_id = $x_vencimiento_id ";	
		}
		//phpmkr_query($sSqlWrk,$conn);


	}else{
		$x_vence = " NO vence <br>";
	}
	
//	echo "VencNo: $x_vencimiento_id  FecVenc:  $x_fecha_vencimiento  DiasVen: $x_dias_vencidos   DiaVenc:  $x_dia  DiaGracia:  $x_dias_gracia  Reporta:  $x_vence  <br>";
	
	
}
@phpmkr_free_result($rswrk);
phpmkr_db_close($conn);
?>
