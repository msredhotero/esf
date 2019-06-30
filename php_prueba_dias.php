<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php include ("utilerias/datefunc.php") ?>



<?php

$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
$currdate = ConvertDateToMysqlFormat($currdate);
$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];		

//$x_dia = strtoupper($currentdate["weekday"]);

//$currdate = "2007-07-10";
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

// ************************************************   MORATORIOS
$sSqlWrk = "SELECT vencimiento.*, credito.credito_status_id, credito.credito_tipo_id, credito.importe as importe_credito, credito.tasa_moratoria, credito.credito_num+0 as crednum, credito.tasa, forma_pago.valor as forma_pago_valor, credito.iva as iva_credito FROM vencimiento join credito 
on credito.credito_id = vencimiento.credito_id join forma_pago on forma_pago.forma_pago_id = credito.forma_pago_id 
where credito.credito_status_id in (1,4) and vencimiento.fecha_vencimiento < '$currdate' and vencimiento.vencimiento_status_id in (1,3,6) order by vencimiento.fecha_vencimiento";

//" and credito.credito_num = '283'";
$rswrkmain = phpmkr_query($sSqlWrk,$conn) or exit();
while($datawrkmain = phpmkr_fetch_array($rswrkmain)){
	$x_vencimiento_id = $datawrkmain["vencimiento_id"];
	$x_vencimiento_num = $datawrkmain["vencimiento_num"];
	echo "numero de vencimeinto".$x_vencimiento_num."<br>";
	$x_fecha_vencimiento = $datawrkmain["fecha_vencimiento"];
	$x_importe = $datawrkmain["importe"];	
	$x_interes = $datawrkmain["interes"];		
	$x_interes_moratorio = $datawrkmain["interes_moratorio"];			
	$x_tasa_moratoria = $datawrkmain["tasa_moratoria"];	
	$x_credito_num = $datawrkmain["crednum"];	
	echo "numero de credito".$x_credito_num."<br>";
	
	$x_credito_status_id = $datawrkmain["credito_status_id"];
	$x_importe_credito = $datawrkmain["importe_credito"];			
	$x_forma_pago_valor = $datawrkmain["forma_pago_valor"];				
	$x_tasa = $datawrkmain["tasa"];		
	$x_iva_credito = $datawrkmain["iva_credito"];				
	$x_iva = $datawrkmain["iva"];			
	$x_credito_id = $datawrkmain["credito_id"];	
	$x_credito_tipo_id = $datawrkmain["credito_tipo_id"];	
	
	$x_numero_de_pagos = 0;

	
	$sqlVenNum = "SELECT COUNT(*) AS numero_de_pagos FROM vencimiento WHERE  fecha_vencimiento =  \"$x_fecha_vencimiento\" AND  credito_id =  $x_credito_id";
	$response = phpmkr_query($sqlVenNum, $conn) or die("error en numero de vencimiento".phpmkr_error()."sql:".$sqlVenNum);
	$rownpagos = phpmkr_fetch_array($response);  
	$x_numero_de_pagos =  $rownpagos["numero_de_pagos"];
	
	echo "numero de pagos".$x_numero_de_pagos."<br>";

	if($x_numero_de_pagos < 2){
	// si el numero de pagos es mayor a uno significa que ya se cobraron los moratoios o parte de ellos y ya no se deben de volver a recalcular los moratorios...
	// solo entra al ciclo de moratorios si solo existe un  pago con esta fecha.
	
	echo "si numero de pagos es menor de 2<br>";
	if(empty($x_iva)){
		$x_iva = 0;
	}
	
		
	if(is_null($x_interes_moratorio)){
		$x_interes_moratorio = 0;
	}

echo "fecha vencimiento".$x_fecha_vencimiento."<br>";
	$x_dias_vencidos = datediff('d', $x_fecha_vencimiento, $currdate, false);	
echo "dias vencidos".$x_dias_vencidos."<br>";
	$x_dia = strtoupper(date('l',strtotime($x_fecha_vencimiento)));
echo "dia".$x_dia."<br>";

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

	
	if($x_dias_vencidos >= $x_dias_gracia){
		
		echo "si dias vecido es mayors a dias de gracia entra aqui y calcula moratorios<br>".
		$x_importe_mora = $x_tasa_moratoria * $x_dias_vencidos;
		if($x_iva_credito == 1){
//			$x_iva_mor = round($x_importe_mora * .15);
			$x_iva_mor = 0;			
		}else{
			$x_iva_mor = 0;
		}
		
//moratorios no majyores a 	2	

		if($x_credito_tipo_id == 2){			
			if($x_importe_mora > 0){
				$x_importe_mora = 250;
				}			
			}else{		
		if($x_importe_mora > (($x_interes + $x_iva) * 2)){
			$x_importe_mora = ($x_interes + $x_iva) * 2;
		}
			}
		$x_tot_venc = $x_importe + $x_interes + $x_importe_mora + $x_iva + $x_iva_mor;		

		if($x_credito_num > 809){
		//$sSqlWrk = "update vencimiento set vencimiento_status_id = 3, interes_moratorio = $x_importe_mora, iva_mor = $x_iva_mor, total_venc = $x_tot_venc where vencimiento_id = $x_vencimiento_id ";
		
		echo "cambaimos el staus y le ponemos moratorios:<br>";
		}else{
		//$sSqlWrk = "update vencimiento set vencimiento_status_id = 3 where vencimiento_id = $x_vencimiento_id ";	
		echo "cambiamos el status<br>";
		}
		
	}
	
	}
	
}
		phpmkr_query($sSqlWrk,$conn);

  ?>