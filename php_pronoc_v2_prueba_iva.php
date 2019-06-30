<?php set_time_limit(0); ?>
<?php session_start(); ?>
<?php ob_start(); ?> 
<?php

// Initialize common variables

?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php include ("utilerias/datefunc.php") ?>
<?php
die();
$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
$currdate = ConvertDateToMysqlFormat($currdate);
$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];	

echo "CURDATE".$currdate."<BR>";
//$x_dia = strtoupper($currentdate["weekday"]);

$currdate = "2018-09-25";
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

// ************************************************   VENCE TAREAS

 
 ##   VENCEMOS LAS PENALIZACIONES, LOS REMANENTES Y LAS COMISIONES POR COBRANZA 
 
  ###########################################################################
 ###########################################################################




// ************************************************   MORATORIOS
$sSqlWrk = "SELECT vencimiento.*, credito.penalizacion, credito.credito_status_id, credito.credito_tipo_id, credito.importe as importe_credito, credito.tasa_moratoria, credito.credito_num+0 as crednum, credito.tasa, forma_pago.valor as forma_pago_valor, credito.iva as iva_credito FROM vencimiento join credito 
on credito.credito_id = vencimiento.credito_id join forma_pago on forma_pago.forma_pago_id = credito.forma_pago_id 
where credito.credito_status_id in (1,4) and vencimiento.fecha_vencimiento < '$currdate' and vencimiento.vencimiento_status_id in (1,3,6) and credito.credito_id not in (1064,2499)   order by vencimiento.fecha_vencimiento";

$sSqlWrk = "SELECT vencimiento.*, credito.penalizacion, credito.credito_status_id, credito.credito_tipo_id, credito.importe as importe_credito, credito.tasa_moratoria, credito.credito_num+0 as crednum, credito.tasa, forma_pago.valor as forma_pago_valor, credito.iva as iva_credito FROM vencimiento join credito 
on credito.credito_id = vencimiento.credito_id join forma_pago on forma_pago.forma_pago_id = credito.forma_pago_id 
where credito.credito_status_id in (1,4) and vencimiento.fecha_vencimiento < '$currdate' and vencimiento.vencimiento_status_id in (1,3,6) and credito.credito_id in (7083, 7100)   order by vencimiento.fecha_vencimiento";


$rswrkmain = phpmkr_query($sSqlWrk,$conn) or die("error el query 1".phpmkr_error()."ql. :");
while($datawrkmain = phpmkr_fetch_array($rswrkmain)){
	$x_vencimiento_id = $datawrkmain["vencimiento_id"];
	$x_vencimiento_num = $datawrkmain["vencimiento_num"];	
	$x_fecha_vencimiento = $datawrkmain["fecha_vencimiento"];
	$x_importe = $datawrkmain["importe"];	
	$x_interes = $datawrkmain["interes"];		
	$x_interes_moratorio = $datawrkmain["interes_moratorio"];			
	$x_tasa_moratoria = $datawrkmain["tasa_moratoria"];	
	$x_credito_num = $datawrkmain["crednum"];		
	$x_credito_status_id = $datawrkmain["credito_status_id"];
	$x_importe_credito = $datawrkmain["importe_credito"];			
	$x_forma_pago_valor = $datawrkmain["forma_pago_valor"];				
	$x_tasa = $datawrkmain["tasa"];		
	$x_iva_credito = $datawrkmain["iva_credito"];				
	$x_iva = $datawrkmain["iva"];			
	$x_credito_id = $datawrkmain["credito_id"];		
}
//and credito.credito_id > 5000  and credito.credito_id < 5020 

echo "sql :".$sSqlWrk."<br><br>";
//" and credito.credito_num = '283'";
$rswrkmain = phpmkr_query($sSqlWrk,$conn) or die("error el query 1".phpmkr_error()."ql. :");
while($datawrkmain = phpmkr_fetch_array($rswrkmain)){
	#echo "entra <br>";
	$x_vencimiento_id = $datawrkmain["vencimiento_id"];
	$x_vencimiento_num = $datawrkmain["vencimiento_num"];	
	$x_fecha_vencimiento = $datawrkmain["fecha_vencimiento"];
	$x_importe = $datawrkmain["importe"];	
	$x_interes = $datawrkmain["interes"];		
	$x_interes_moratorio = $datawrkmain["interes_moratorio"];			
	$x_tasa_moratoria = $datawrkmain["tasa_moratoria"];	
	$x_credito_num = $datawrkmain["crednum"];		
	$x_credito_status_id = $datawrkmain["credito_status_id"];
	$x_importe_credito = $datawrkmain["importe_credito"];			
	$x_forma_pago_valor = $datawrkmain["forma_pago_valor"];				
	$x_tasa = $datawrkmain["tasa"];		
	$x_iva_credito = $datawrkmain["iva_credito"];				
	$x_iva = $datawrkmain["iva"];			
	$x_credito_id = $datawrkmain["credito_id"];	
	echo "<br>************credito_id".$x_credito_id;
	$x_credito_tipo_id = $datawrkmain["credito_tipo_id"];	
	
	$x_numero_de_pagos = 0;
	
		//campo de penalizacion
	$x_penalizacion = $datawrkmain["penalizacion"];
#	echo "penal".$x_penalizacion."<br>";
echo "<br>";
print_r($datawrkmain);
echo "<br>";	
	
	#################################################################
	###########   MORATORIOS NUEVO CASO   ###########################
	#################################################################
	
	# SI EL CAMPO DE PENALIZACION ES MAYOR DE 0 SIGNIFICA QUE  ES UN CREDITO QUE NO SE COBRARA MORATORIOS, SOLO SE GENERARAN PENALIZACIONES Y COMISION 
	# DE COMBRANZA SI APLICARA EL CASO.
	
	if($x_penalizacion > 0){
		echo "<br>Entra cred_num".$x_credito_num."<br>";
		
		#aqui entra el calculo de las penalizaciones y las comisiones de los creditos con el nuevo esquema de penalizaciones
		if(empty($x_iva)){
		$x_iva = 0;
		}
		
		$x_dias_vencidos = datediff('d', $x_fecha_vencimiento, $currdate, false);	

		$x_dia = strtoupper(date('l',strtotime($x_fecha_vencimiento)));
	echo "<br>fecha  vencimeinto".$x_fecha_vencimiento;
	echo "<br>fecha actual".$currdate;	
	echo "<br>dias vencido".$x_dias_vencidos."<br>";
	echo "<br> IVA".$x_iva."<br>";
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
					$x_dias_gracia = 4;
					break;
				case "SATURDAY": // Get a record to display
					$x_dias_gracia = 3;
					break;
				case "SUNDAY": // Get a record to display
					$x_dias_gracia = 2;
					break;		
		}
		
		if($x_dias_vencidos >= $x_dias_gracia){
			
			#echo "foram de pago mensual y  penalizacion mayor de 0 <br>";			
			# es la nueva forma de pago 
			#se gereran con la fecha del ultimo vencimiento
			#todoas con la misma fecha
			#el no de vencimeinto se toma para generar el no de penalizacion mas 2000
			# en monto de la penalizacion sera igual al campode penalizaciones, si tiene iva el monto debera cubrir iva. monto pen = 150 iva =20.68 + interes moratorio = 129.31
			#solo se aplica si la forma de pago es mensual y si el campo de penalizacion esta lleno
			if(empty($x_iva)){
			$x_iva = 0;
			}
			
					
		# si dias vencidos es mayor a dias de gracia entonces se gera el nuevo vencimeinto.
		if($x_iva > 0){
			# si el iva es mayo de cero entonces se calcula el monto del iva
			$x_interes_moratorio = $x_penalizacion /  1.16 ;
			$x_iva_moratorio = $x_penalizacion - $x_interes_moratorio; 		
			}else{
				$x_interes_moratorio = $x_penalizacion;
				$x_iva_moratorio = 0;
				}		
		$x_tot_venc =$x_interes_moratorio + $x_iva_moratorio ;		
		$x_vencimiento_num_nuevo = $x_vencimiento_num + 2000;
		
	
		
		# seleccionamos la fecha del ultimo vencimeinto, para generar las penalizaciones con esa misma fecha
		$sqlFecha = "SELECT  fecha_vencimiento  FROM vencimiento WHERE credito_id = $x_credito_id  and vencimiento_num < 2000  order by  vencimiento_num DESC limit 0,1 ";
		$rsFecha = phpmkr_query($sqlFecha, $conn) or die ("Error al seleccioanr la fecha del ultimo venciento.". phpmkr_error(). "sql:".$sqlFecha);
		$rowFecha = phpmkr_fetch_array($rsFecha);
		$x_fecha_nuevo_vencimiento = $rowFecha["fecha_vencimiento"];
		#no 2000 para penalizaciones
		#no 3000 para comisiones
		
		echo "<br> vencimeito num < 300**". $x_vencimiento_num_nuevo;
		if($x_vencimiento_num_nuevo < 3000){
			
			
		#verificamos que ese venciemiento vencido no tenga aun ninguna penalizacion generada.
		$sqlBuscaPenalizacion  = "SELECT * FROM vencimiento WHERE vencimiento_num = $x_vencimiento_num_nuevo  and credito_id = $x_credito_id";
		$rsBuscaPenalizacion = phpmkr_query($sqlBuscaPenalizacion, $conn) or die ("Error al seleccionar".phpmkr_error()."sql:".$sqlBuscaPenalizacion);
		#echo "sql penalizaciones".$sqlBuscaPenalizacion."<br>";
		$x_no_penalizaciones  = mysql_num_rows($rsBuscaPenalizacion); 
		#echo "nuemro de  penalizaciones = ".$x_no_penalizaciones ."<br>";
		if($x_no_penalizaciones < 1 || true){		
		#Si o hay ningun registro de penalizacion para el vencimeinto, se genera uno
		$sSqlWrk = "INSERT INTO `vencimiento` (`vencimiento_id`, `credito_id`, `vencimiento_num`, `vencimiento_status_id`, `fecha_vencimiento`, `importe`, `interes`, `interes_moratorio`, `iva`, `iva_mor`, `total_venc`, `fecha_genera_remanente`)";
		$sSqlWrk .= " VALUES (NULL, $x_credito_id, $x_vencimiento_num_nuevo, '1', \"$x_fecha_nuevo_vencimiento\", '0', '0', $x_interes_moratorio , '0', $x_iva_moratorio, $x_tot_venc, NULL); ";			
		#phpmkr_query($sSqlWrk,$conn) or die ("Error al insertar el nuevo vencimiento".phpmkr_error()."sql:". $sSqlWrk);	
				echo "<br>".$sSqlWrk;
		}
			
			
		# actualizamos el vencimeinto a vencido
		$sSqlWrk = "update vencimiento set vencimiento_status_id = 3 where vencimiento_id = $x_vencimiento_id ";
		phpmkr_query($sSqlWrk,$conn);
		}// fin si vencimiento numero es menor de 3000
			
		# VERICAMOS EL MONTO QUE TIENE EL CREDITO COMO VENCIDO
		# SI EL MONTO VENCIDO CARRESPONDE AL PAGO COMPLETO DE DOS VENCIMIENTOS ENTONCES SE GENERA LA COMSION
		# CORRESPONDIENTE A LAS TABLAS DE PENALIZACIONES		
		# DOS PAGOS VECIDO PARA CREDITOS MENSUALES, QUINCENALES Y CATORCENALES
		# TRES PAGOS VENCIDOS PARA LOS CREDITOS SEMANALES
		
		# buscamos el monto del venicimeinto
		$sqlBuscaMontoVenc = " SELECT SUM(total_venc) AS monto_vencimiento FROM vencimiento WHERE credito_id = $x_credito_id and vencimiento_num = 1 ";
		$rsBuscaMontoVenc = phpmkr_query($sqlBuscaMontoVenc,$conn) or die ("Error al seleccionar el monto del vencimiento".phpmkr_error()."sql:".$sqlBuscaMontoVenc);
		$rowBuscaMontoVenc = phpmkr_fetch_array($rsBuscaMontoVenc);
		$x_monto_VV = $rowBuscaMontoVenc["monto_vencimiento"];
		
		#buscamos el monto vencido
		$sqlBuscaMontoVenc = " SELECT SUM(total_venc) AS monto_vencido FROM vencimiento WHERE credito_id = $x_credito_id and  vencimiento_status_id in (3,6)";
		$rsBuscaMontoVenc = phpmkr_query($sqlBuscaMontoVenc,$conn) or die ("Error al seleccionar el monto del vencimiento".phpmkr_error()."sql:".$sqlBuscaMontoVenc);
		$rowBuscaMontoVenc = phpmkr_fetch_array($rsBuscaMontoVenc);
		$x_monto_VVencido = $rowBuscaMontoVenc["monto_vencido"];
		 $x_monto_garantia_liquida = 0;
		if($x_forma_pago_valor == 7){
			$x_total_vencido_para_generar =  $x_monto_VV * 3;
			$x_monto_garantia_liquida = $x_monto_VV * 2;
			}else{ 
		 		$x_total_vencido_para_generar =  $x_monto_VV * 2;
				$x_monto_garantia_liquida = $x_monto_VV * 1;
			}
			
		/*$sqlBuscaPenalizacion  = "SELECT * FROM vencimiento WHERE  credito_id = $x_credito_id and vencimiento_num > 2000 ";
		$rsBuscaPenalizacion = phpmkr_query($sqlBuscaPenalizacion, $conn) or die ("Error al seleccionar".phpmkr_error()."sql:".$sqlBuscaPenalizacion);				
		$x_no_penalizaciones_total  = mysql_num_rows($rsBuscaPenalizacion);*/	
		
		
		#echo "numero de penalizaciones total ".$x_no_penalizaciones_total."<br>";
		if($x_monto_VVencido  >= $x_total_vencido_para_generar){
			// si el mosnto de lo vencido corresponde a la cantidad necesaria para generar la comision, entonces se generan los gatos de cobranza

			#se calcula el valor de la garatia liquida.
			
			$sqlGarantiaLiquida = "SELECT monto FROM  garantia_liquida WHERE credito_id = $x_credito_id " ;
			$rsGarantiaLiquida = phpmkr_query($sqlGarantiaLiquida, $conn) or die ("Error al seleccionar el monto de la garantia liquida". phpmkr_error()."sql :". $sqlGarantiaLiquida);
			$rowGarantiaLiquida = phpmkr_fetch_array($rsGarantiaLiquida);
			#$x_monto_garantia_liquida = $rowGarantiaLiquida["monto"];
			
			#ahora verificacmos que no esxista ya un cobro por comision
			$sqlNoComision = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and vencimiento_num > 3000";
			$rsNoComision = phpmkr_query($sqlNoComision, $conn) or die ("Error al seleccionar el monto de la garantia". phpmkr_error()."sql:". $sqlNoComision);
			$x_no_comision = mysql_num_rows($rsNoComision);
			if ($x_no_comision <1){
				// si es menor a uno se genra la comision d po cobranza
				
				$sqlIVACC = "SELECT iva FROM  credito WHERE credito_id = $x_credito_id ";
				$rsIVACC = phpmkr_query($sqlIVACC,$conn)or die ("Error al seleccionar el iva del credito".phpmkr_error().$sqlIVACC);
				$rowIVACC = phpmkr_fetch_array($rsIVACC);
				$x_tiene_iva = $rowIVACC["iva"];
				
				if($x_tiene_iva == 1){
				$x_aux_monto_gar_li = $x_monto_garantia_liquida;
				$x_monto_mor = $x_aux_monto_gar_li / 1.16;
				$x_monto_iva_mor = $x_monto_garantia_liquida - $x_monto_mor;
				
				}else{
					
					$x_monto_mor = $x_monto_garantia_liquida;
					$x_monto_iva_mor = 0;
					
					}
				
				$sSqlWrk = "INSERT INTO `vencimiento` (`vencimiento_id`, `credito_id`, `vencimiento_num`, `vencimiento_status_id`, `fecha_vencimiento`, `importe`, `interes`, `interes_moratorio`, `iva`, `iva_mor`, `total_venc`, `fecha_genera_remanente`)";
		$sSqlWrk .= " VALUES (NULL, $x_credito_id, '3001', '1', \"$x_fecha_nuevo_vencimiento\",'0', '0', $x_monto_mor, '0', $x_monto_iva_mor, $x_monto_garantia_liquida, NULL); ";			
		#phpmkr_query($sSqlWrk,$conn) or die ("Error al insertar el nuevo vencimiento como comision por gasto de cobranza.".phpmkr_error()."sql:". $sSqlWrk);	
				$x_hoy_d = date("Y-m-d"); 
				//die();
				
				
				#seleccionamos el campo fecha de generacion de comisio, si esta vacia se actualiza la fecha, si esta llena se deja asi
				
				$sqlFechaGeneraComision  = "SELECT fecha_genera_comision FROM credito ";
				$rsFechaGeneraComision = phpmkr_query($sqlFechaGeneraComision, $conn) or die ("Error al seleccionar la fecha de generacion  de comision".phpmkr_error()."sql".$sqlFechaGeneraComision);
				$rowFechaGeneraComision= phpmkr_fetch_array($rsFechaGeneraComision);
				$x_FGC = $rowFechaGeneraComision["fecha_genera_comision"];
				if(empty($x_FGC) || is_null($x_FGC)){				
				$sqlfechaComision = "UPDATE credito SET fecha_genera_comision = \"$x_hoy_d\"  WHERE credito_id = $x_credito_id";
				#$rsfechaComision = phpmkr_query($sqlfechaComision, $conn) or die ("Error al actualiza los campos". phpmkr_error()."sql".$sqlfechaComision);
					echo "entra".$sqlfechaComision."<br>";
				}
				} // fin comision
			
			
						
			
			}// fin genera comision
			
	  		# verificamos si el vecimiento ctual es el ultimo
			# si es el ultimo revisamos que el credito no tenga penalizaciones pendientes o vencidas
			# si tiene se genera comision por gastos de cobranza legal en caso de que aun no la tega generada
			
			$sqlUltimoVenc = "SELECT `vencimiento_num` AS ultimo_venc_num FROM `vencimiento` WHERE `credito_id` = $x_credito_id ";
			$sqlUltimoVenc .= " AND `vencimiento_num` < 2000 ORDER BY `vencimiento_num` DESC LIMIT 0,1 ";
			$rsUltimoVenc = phpmkr_query($sqlUltimoVenc, $conn) or die ("Error al seleccionar el ultimo vencimiento del credit".phpmkr_error()."sql:".$sqlUltimoVenc);
			$rowUltimoVenc = phpmkr_fetch_array($rsUltimoVenc);
			$x_no_ultimo_vencimiento = $rowUltimoVenc["ultimo_venc_num"];
			
			if($x_vencimiento_num == $x_no_ultimo_vencimiento){
				// si corresponde al ultimo pago se aplica la verificacion de la comision
				$sqlUltimoVenc = "SELECT * FROM `vencimiento` WHERE `credito_id` = $x_credito_id ";
				$sqlUltimoVenc .= " AND `vencimiento_num` > 2000  AND vencimiento_status_id IN (1,3) ";
				$rsUltimoVenc = phpmkr_query($sqlUltimoVenc, $conn) or die ("Error al seleccionar el ultimo vencimiento del credit".phpmkr_error()."sql:".$sqlUltimoVenc);
				
			    $x_numero_de_penalizaciones = mysql_num_rows($rsUltimoVenc);
				if($x_numero_de_penalizaciones > 0){
					// si hay alguna penalizacion sin pagar se genera la comsion de gatos por cobranza
					
					#ahora verificacmos que no esxista ya un cobro por comision
					$sqlNoComision = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and vencimiento_num > 3000";
					$rsNoComision = phpmkr_query($sqlNoComision, $conn) or die ("Error al seleccionar el monto de la garantia". phpmkr_error()."sql:". $sqlNoComision);
					$x_no_comision = mysql_num_rows($rsNoComision);
								
					if ($x_no_comision < 1){
						
						$sqlIVACC = "SELECT iva FROM  credito WHERE credito_id = $x_credito_id ";
				$rsIVACC = phpmkr_query($sqlIVACC,$conn)or die ("Error al seleccionar el iva del credito".phpmkr_error().$sqlIVACC);
				$rowIVACC = phpmkr_fetch_array($rsIVACC);
				$x_tiene_iva = $rowIVACC["iva"];
				
				if($x_tiene_iva == 1){
				$x_aux_monto_gar_li = $x_monto_garantia_liquida;
				$x_monto_mor = $x_aux_monto_gar_li / 1.16;
				$x_monto_iva_mor = $x_monto_garantia_liquida - $x_monto_mor;
				
				}else{
					
					$x_monto_mor = $x_monto_garantia_liquida;
					$x_monto_iva_mor = 0;
					
					}
							$sSqlWrk = "INSERT INTO `vencimiento` (`vencimiento_id`, `credito_id`, `vencimiento_num`, `vencimiento_status_id`, `fecha_vencimiento`,";
							$sSqlWrk .= " `importe`, `interes`, `interes_moratorio`, `iva`, `iva_mor`, `total_venc`, `fecha_genera_remanente`)";
							$sSqlWrk .= " VALUES (NULL, $x_credito_id, '3001', '1', \"$x_fecha_nuevo_vencimiento\", '0', ";
							$sSqlWrk .= " '0', $x_monto_mor , '0',$x_monto_iva_mor, $x_monto_garantia_liquida, NULL); ";			
							#phpmkr_query($sSqlWrk,$conn) or die ("Error al insertar el nuevo vencimiento como comision por gasto de cobranza.".phpmkr_error()."sql:". $sSqlWrk);	
							$x_hoy_d = date("Y-m-d"); 
							echo "hoy es -------".$x_hoy_d."<br>";				
				
						#seleccionamos el campo fecha de generacion de comisio, si esta vacia se actualiza la fecha, si esta llena se deja asi				
						$sqlFechaGeneraComision  = "SELECT fecha_genera_comision FROM credito ";
						$rsFechaGeneraComision = phpmkr_query($sqlFechaGeneraComision, $conn) or die ("Error al seleccionar la fecha de generacion  de comision".phpmkr_error()."sql".$sqlFechaGeneraComision);
						$rowFechaGeneraComision= phpmkr_fetch_array($rsFechaGeneraComision);
						$x_FGC = $rowFechaGeneraComision["fecha_genera_comision"];
						if(empty($x_FGC) || is_null($x_FGC)){				
								$sqlfechaComision = "UPDATE credito SET fecha_genera_comision = \"$x_hoy_d\"  WHERE credito_id = $x_credito_id";
								#$rsfechaComision = phpmkr_query($sqlfechaComision, $conn) or die ("Error al actualiza los campos". phpmkr_error()."sql".$sqlfechaComision);
								echo "entra".$sqlfechaComision."<br>";
						}// fin fecha genera comision
					}// fin no existe comision
					
					
					}
				
				
				}
			
			}
		  
		  
		
		}
	
	






}// fin while moratorios









	





	

//@phpmkr_free_result($rswrk);
phpmkr_db_close($conn);


?>
