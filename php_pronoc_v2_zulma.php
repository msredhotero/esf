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

//$currdate = "2007-07-10";
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

// ************************************************   VENCE TAREAS
$sSqlWrk = "update crm_tarea set crm_tarea_status_id = 2 where fecha_ejecuta < '$currdate' and crm_tarea_status_id = 1 ";
phpmkr_query($sSqlWrk,$conn);


// vencemos las tareas del promotor en TAREA_DIARIA-PROMOTOR; para que se puedan utilizar paracompletar dias...

$sqlUTDP = "UPDATE tarea_diaria_promotor SET status_tarea = 2 where fecha_lista < '$currdate' and tarea_id in (SELECT crm_tarea_id from crm_tarea WHERE crm_tarea_status_id = 2 )AND  status_tarea = 1";
phpmkr_query($sqlUTDP,$conn)or die ("Error al actualiza el status de la tarea".phpmkr_error()."sql:".$sqlUTDP);


// ************************************************   CIERRA TAREAS DE CREDITOS SIN PAGOS VENCIDOS


$sSqlWrk = "select * from crm_caso where crm_caso_status_id = 1 and crm_caso_tipo_id = 3 ";
$rswrkct = phpmkr_query($sSqlWrk,$conn) or exit();
while($datawrkct = phpmkr_fetch_array($rswrkct)){
	$x_crm_caso_id = $datawrkct["crm_caso_id"];	
	$x_credito_id = $datawrkct["credito_id"];		
	
	$sSqlWrkven = "select count(*) as vencidos from vencimiento where credito_id = $x_credito_id and vencimiento_status_id = 3 ";
	$rswrkven = phpmkr_query($sSqlWrkven,$conn) or exit();
	$datawrkven = phpmkr_fetch_array($rswrkven);
	$x_vencidos = $datawrkven["vencidos"];	
	@phpmkr_free_result($rswrkven);
	if(intval($x_vencidos) == 0){
		
		
	
	if(empty($x_crm_caso_id)){
		$x_crm_caso_id = -1;
	}	
	if(empty($x_credito_id)){
		$x_credito_id = -1;
	}	
		
	$sSqlWrkven = "insert into temp_crm values($x_crm_caso_id,$x_credito_id);";
	phpmkr_query($sSqlWrkven,$conn) or exit();
	
	
	// cerramos las tareas y cerramos el caso
	
	
	
	
		
		
		$sSqlWrkven = "update crm_tarea set crm_tarea_status_id = 4 where crm_caso_id = $x_crm_caso_id  and crm_tarea_status_id in (1,2)";
		phpmkr_query($sSqlWrkven,$conn) or die("Error al actulizar los estatusde las tareas liquidadas".phpmkr_error()."sql :".$sSqlWrkven);
		
		$sSqlWrkven1 = "update tarea_diaria_promotor set status_tarea = 4 where caso_id = $x_crm_caso_id and status_tarea in (1,2)";
		phpmkr_query($sSqlWrkven1,$conn) or die("Error al actulizar los estatusde las tareas liquidadas en tareas diarias promotor".phpmkr_error()."sql :".$sSqlWrkven1);

		//bitacora
		$x_bitacora = "Cartera Vencida - (".FormatDateTime($currdate,7)." - $currtime)";
		$x_bitacora .= "\n";
		$x_bitacora .= "Cierre de Caso - El credito ya no presenta pagos vencidos.";	

		$sSqlWrk = "SELECT comentario_int FROM credito_comment WHERE credito_id = ".$x_credito_id." LIMIT 1 ";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_comment_ant = $datawrk["comentario_int"];
		@phpmkr_free_result($rswrk);

		if(empty($x_comment_ant)){
			$sSql = "insert into credito_comment values(0, $x_credito_id, '$x_bitacora', NULL)";
		}else{
			$x_bitacora = $x_comment_ant . "\n\n------------------------------\n" . $x_bitacora;
			$sSql = "UPDATE credito_comment set comentario_int = '$x_bitacora' where credito_id = $x_credito_id";
		}

		$sSqlWrkven = "update crm_caso set crm_caso_status_id = 2 where crm_caso_id = $x_crm_caso_id ";
		phpmkr_query($sSqlWrkven,$conn) or exit();




	}
}


// ************************************************   MORATORIOS
$sSqlWrk = "SELECT vencimiento.*, credito.penalizacion, credito.credito_status_id, credito.credito_tipo_id, credito.importe as importe_credito, credito.tasa_moratoria, credito.credito_num+0 as crednum, credito.tasa, forma_pago.valor as forma_pago_valor, credito.iva as iva_credito FROM vencimiento join credito 
on credito.credito_id = vencimiento.credito_id join forma_pago on forma_pago.forma_pago_id = credito.forma_pago_id 
where credito.credito_status_id in (1,4,3) and vencimiento.fecha_vencimiento < '$currdate' and vencimiento.vencimiento_status_id in (1,3,6,2) and credito.credito_id  in (6473) order by vencimiento.fecha_vencimiento";

//and credito.credito_id > 5000  and credito.credito_id < 5020 

echo "<br>sql :<br>".$sSqlWrk."<br><br>";
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
	echo "credito_id".$x_credito_id;
	$x_credito_tipo_id = $datawrkmain["credito_tipo_id"];	
	
	$x_numero_de_pagos = 0;
	
		//campo de penalizacion
	$x_penalizacion = $datawrkmain["penalizacion"];
#	echo "penal".$x_penalizacion."<br>";

	
	
	#################################################################
	###########   MORATORIOS NUEVO CASO   ###########################
	#################################################################
	
	# SI EL CAMPO DE PENALIZACION ES MAYOR DE 0 SIGNIFICA QUE  ES UN CREDITO QUE NO SE COBRARA MORATORIOS, SOLO SE GENERARAN PENALIZACIONES Y COMISION 
	# DE COMBRANZA SI APLICARA EL CASO.
	
	if($x_penalizacion > 0){
		echo "entra cred_num".$x_credito_num."<br>";
		
		#aqui entra el calculo de las penalizaciones y las comisiones de los creditos con el nuevo esquema de penalizaciones
		if(empty($x_iva)){
		$x_iva = 0;
		}
		
		$x_dias_vencidos = datediff('d', $x_fecha_vencimiento, $currdate, false);	

		$x_dia = strtoupper(date('l',strtotime($x_fecha_vencimiento)));
	echo "fecha  vencimeinto".$x_fecha_vencimiento;
	echo "fecha actual".$currdate;	
	echo "dias vencido".$x_dias_vencidos."<br>";
	
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
		if($x_vencimiento_num_nuevo < 3000){
		#verificamos que ese venciemiento vencido no tenga aun ninguna penalizacion generada.
		$sqlBuscaPenalizacion  = "SELECT * FROM vencimiento WHERE vencimiento_num = $x_vencimiento_num_nuevo  and credito_id = $x_credito_id";
		$rsBuscaPenalizacion = phpmkr_query($sqlBuscaPenalizacion, $conn) or die ("Error al seleccionar".phpmkr_error()."sql:".$sqlBuscaPenalizacion);
		#echo "sql penalizaciones".$sqlBuscaPenalizacion."<br>";
		$x_no_penalizaciones  = mysql_num_rows($rsBuscaPenalizacion); 
		#echo "nuemro de  penalizaciones = ".$x_no_penalizaciones ."<br>";
		if($x_no_penalizaciones < 1){		
		#Si o hay ningun registro de penalizacion para el vencimeinto, se genera uno
		$sSqlWrk = "INSERT INTO `vencimiento` (`vencimiento_id`, `credito_id`, `vencimiento_num`, `vencimiento_status_id`, `fecha_vencimiento`, `importe`, `interes`, `interes_moratorio`, `iva`, `iva_mor`, `total_venc`, `fecha_genera_remanente`)";
		$sSqlWrk .= " VALUES (NULL, $x_credito_id, $x_vencimiento_num_nuevo, '1', \"$x_fecha_nuevo_vencimiento\", '0', '0', $x_interes_moratorio , '0', $x_iva_moratorio, $x_tot_venc, NULL); ";			
		phpmkr_query($sSqlWrk,$conn) or die ("Error al insertar el nuevo vencimiento".phpmkr_error()."sql:". $sSqlWrk);	
				
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
				
				$sSqlWrk = "INSERT INTO `vencimiento` (`vencimiento_id`, `credito_id`, `vencimiento_num`, `vencimiento_status_id`, `fecha_vencimiento`, `importe`, `interes`, `interes_moratorio`, `iva`, `iva_mor`, `total_venc`, `fecha_genera_remanente`)";
		$sSqlWrk .= " VALUES (NULL, $x_credito_id, '3001', '1', \"$x_fecha_nuevo_vencimiento\", $x_monto_garantia_liquida, '0', '0' , '0', '0', $x_monto_garantia_liquida, NULL); ";			
		phpmkr_query($sSqlWrk,$conn) or die ("Error al insertar el nuevo vencimiento como comision por gasto de cobranza.".phpmkr_error()."sql:". $sSqlWrk);	
				$x_hoy_d = date("Y-m-d"); 
				
				
				#seleccionamos el campo fecha de generacion de comisio, si esta vacia se actualiza la fecha, si esta llena se deja asi
				
				$sqlFechaGeneraComision  = "SELECT fecha_genera_comision FROM credito ";
				$rsFechaGeneraComision = phpmkr_query($sqlFechaGeneraComision, $conn) or die ("Error al seleccionar la fecha de generacion  de comision".phpmkr_error()."sql".$sqlFechaGeneraComision);
				$rowFechaGeneraComision= phpmkr_fetch_array($rsFechaGeneraComision);
				$x_FGC = $rowFechaGeneraComision["fecha_genera_comision"];
				if(empty($x_FGC) || is_null($x_FGC)){				
				$sqlfechaComision = "UPDATE credito SET fecha_genera_comision = \"$x_hoy_d\"  WHERE credito_id = $x_credito_id";
				$rsfechaComision = phpmkr_query($sqlfechaComision, $conn) or die ("Error al actualiza los campos". phpmkr_error()."sql".$sqlfechaComision);
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
							$sSqlWrk = "INSERT INTO `vencimiento` (`vencimiento_id`, `credito_id`, `vencimiento_num`, `vencimiento_status_id`, `fecha_vencimiento`,";
							$sSqlWrk .= " `importe`, `interes`, `interes_moratorio`, `iva`, `iva_mor`, `total_venc`, `fecha_genera_remanente`)";
							$sSqlWrk .= " VALUES (NULL, $x_credito_id, '3001', '1', \"$x_fecha_nuevo_vencimiento\", $x_monto_garantia_liquida, ";
							$sSqlWrk .= " '0', '0' , '0','0', $x_monto_garantia_liquida, NULL); ";			
							phpmkr_query($sSqlWrk,$conn) or die ("Error al insertar el nuevo vencimiento como comision por gasto de cobranza.".phpmkr_error()."sql:". $sSqlWrk);	
							$x_hoy_d = date("Y-m-d"); 
							echo "hoy es -------".$x_hoy_d."<br>";				
				
						#seleccionamos el campo fecha de generacion de comisio, si esta vacia se actualiza la fecha, si esta llena se deja asi				
						$sqlFechaGeneraComision  = "SELECT fecha_genera_comision FROM credito ";
						$rsFechaGeneraComision = phpmkr_query($sqlFechaGeneraComision, $conn) or die ("Error al seleccionar la fecha de generacion  de comision".phpmkr_error()."sql".$sqlFechaGeneraComision);
						$rowFechaGeneraComision= phpmkr_fetch_array($rsFechaGeneraComision);
						$x_FGC = $rowFechaGeneraComision["fecha_genera_comision"];
						if(empty($x_FGC) || is_null($x_FGC)){				
								$sqlfechaComision = "UPDATE credito SET fecha_genera_comision = \"$x_hoy_d\"  WHERE credito_id = $x_credito_id";
								$rsfechaComision = phpmkr_query($sqlfechaComision, $conn) or die ("Error al actualiza los campos". phpmkr_error()."sql".$sqlfechaComision);
								echo "entra".$sqlfechaComision."<br>";
						}// fin fecha genera comision
					}// fin no existe comision
					
					
					}
				
				
				}
			
			}
		  
		  
		
		}else{
			#aqui entra el calculo de los interese moratorios de los creditos anteriores.
			 echo "credito_id segundo calculo".$x_credito_id."<br>";
				$sqlVenNum = "SELECT COUNT(*) AS numero_de_pagos FROM vencimiento WHERE  fecha_vencimiento =  \"$x_fecha_vencimiento\" AND  credito_id =  $x_credito_id";
				$response = phpmkr_query($sqlVenNum, $conn) or die("error en numero de vencimiento".phpmkr_error()."sql:".$sqlVenNum);
				$rownpagos = phpmkr_fetch_array($response);  
				$x_numero_de_pagos =  $rownpagos["numero_de_pagos"];
				
				if($x_numero_de_pagos < 2){
					

				# echo "dentro<br>";
				// si el numero de pagos es mayor a uno significa que ya se cobraron los moratoios o parte de ellos y ya no se deben de volver a recalcular los moratorios...
				// solo entra al ciclo de moratorios si solo existe un  pago con esta fecha.	
				// tenemos dos opciones  la priemera es como se calculaban los moratorios en eun inicio, se sigue respentando para los cleintes antiguoas que quieran seguir tomando creditos asi.	

					if(empty($x_iva)){
						$x_iva = 0;
					}			
	
		
					if(is_null($x_interes_moratorio)){
						$x_interes_moratorio = 0;
					}
				
					$x_dias_vencidos = datediff('d', $x_fecha_vencimiento, $currdate, false);	
				
					$x_dia = strtoupper(date('l',strtotime($x_fecha_vencimiento)));
				
				
					echo "fecha  vencimeinto".$x_fecha_vencimiento;
	echo "fecha actual".$currdate;	
	echo "dias vencido".$x_dias_vencidos."<br>";
				
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
	echo "dias de gracia".$x_dias_gracia;
					#	echo "froma de pago =".$x_forma_pago_valor."<br>";
					#	echo "penalizacion ".$x_penalizacion."<br>";
						if($x_dias_vencidos >= $x_dias_gracia){
							echo "dias vecnido es mayor o igul a dias de gracia";
								#echo "<br> ++++++++ el credito NO es mensual ++++++++";
							$x_importe_mora = $x_tasa_moratoria * $x_dias_vencidos;
							#echo "dias vencido".$x_dias_vencidos."<br>";
							#echo "tasa mora". $x_tasa_moratoria."<br>";
							#echo "importe mora".$x_importe_mora."<br>";
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
								#echo "importe mora truncado".$x_importe_mora."<br>";
							$x_tot_venc = $x_importe + $x_interes + $x_importe_mora + $x_iva + $x_iva_mor;		
					
							if($x_credito_num > 809){
							$sSqlWrk = "update vencimiento set vencimiento_status_id = 3, interes_moratorio = $x_importe_mora, iva_mor = $x_iva_mor, total_venc = $x_tot_venc where vencimiento_id = $x_vencimiento_id ";	
							}else{
							$sSqlWrk = "update vencimiento set vencimiento_status_id = 3 where vencimiento_id = $x_vencimiento_id ";	
							}
							phpmkr_query($sSqlWrk,$conn);
							#echo $sSqlWrk."<br>";	
		
						}// dias vencido mayor a dias gracia
					// nomero de pagos menos de 2
					}else{
						//nuemro de pagos es mayor o igual a dos
						echo "entra a parcuiales";
						// aqui solamente tenemos que cambiar el status del vencimeinto a vencido
						$sSqlWrkA = "update vencimiento set vencimiento_status_id = 3 where vencimiento_status_id = 1 and fecha_vencimiento =  \"$x_fecha_vencimiento\"  and credito_id = $x_credito_id ";
						phpmkr_query($sSqlWrkA,$conn) or die ("Error al actualizar los moratorios de los vencimeintos que estan parcialemte pagados.".phpmkr_error()."sql:".$sSqlWrkA);
						echo "sql parciales".$sSqlWrkA."<br>";
						
						}			
			}// else penalizacion es mayor de 0
	
	

	
#aqui volvemos a contar los dias de atraso para ejecutar el crm

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
			$x_dias_gracia = 4;
			break;

		case "SATURDAY": // Get a record to display
			$x_dias_gracia = 3;
			break;
		case "SUNDAY": // Get a record to display
			$x_dias_gracia = 2;
			break;		
	}

#	echo "froma de pago =".$x_forma_pago_valor."<br>";
#	echo "penalizacion ".$x_penalizacion."<br>";
	if($x_dias_vencidos >= $x_dias_gracia){
		
		
#echo "entra a CRM credi. id ".$x_credito_id ."<br>";
//GENERA CASO CRM
// valida que no haya ya un caso abierto para este credito
//EN LA PRIMER FASE DE COBRANBZA LA TAREA SE ASIGNA AL RESPONSABLE DE SUSCURSAL


//seleccionamo la fecha de otorgamiento del credito



	$sSqlWrk = "
	SELECT count(*) as caso_abierto
	FROM 
		crm_caso
	WHERE 
		crm_caso.crm_caso_tipo_id = 3
		AND crm_caso.crm_caso_status_id = 1
		AND crm_caso.credito_id = $x_credito_id
	";
	echo "busca caso abierto ".$sSqlWrk."<br>";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$datawrk = phpmkr_fetch_array($rswrk);
	$x_caso_abierto = $datawrk["caso_abierto"];		
	@phpmkr_free_result($rswrk);
	
	
	
	//kukia
	// contamos el numero de venciemientos que tiene vencidos...
	// si son mas de dos la tarea se asigna al asesor de credito
	
	// para sacar el numero de vencimientos vencidos, seleccionamos el primer vencimeinto y le restamos lo que tenga en moratorio y lo que tenbga de iva en mora
	// asi tenemos el monto incial del vencimeinto y pordremos calcular el numero de vencimetos vencidos por capital.
	$x_numero_de_vencimientos_vencidos = 0;
	$sqlMora = "SELECT total_venc, interes_moratorio,iva_mor FROM vencimiento WHERE credito_id = $x_credito_id and vencimiento_num	= 1";
		$rsMora = phpmkr_query($sqlMora,$conn) or die ("erro al seleccionar la comison en casaos anteriores".phpmkr_error()."sql:".$sqlMora);
		$rowMora = phpmkr_fetch_array($rsMora);
		$x_monto_vencimeinto_mora = $rowMora["total_venc"];
		$x_monto_moratorios_mora = $rowMora["interes_moratorio"];
		$x_monto_iva_mora_mora = $rowMora["iva_mor"];		
		$x_monto_pago_mora = $x_monto_vencimeinto_mora -($x_monto_moratorios_mora  + $x_monto_iva_mora_mora);
		#echo "sql monto".$sqlMora."<br><br>";
		#echo "monto pago mora".$x_monto_pago_mora."<br>";
		#echo "total venc".$x_monto_vencimeinto_mora."<br>";
		#echo "ineres moratorio".$x_monto_moratorios_mora."<br>";
		#echo "iva mora".$x_monto_iva_mora_mora."<br>";
		#echo " fromula monto = total ven -(mora + iva mora)"; //kuki
		
		#buscamos el monto vencido
		$sqlBuscaMontoVencV = " SELECT SUM(total_venc) AS monto_vencido FROM vencimiento WHERE credito_id = $x_credito_id and  vencimiento_status_id in (3,6)";
		$rsBuscaMontoVencV = phpmkr_query($sqlBuscaMontoVencV,$conn) or die ("Error al seleccionar el monto del vencimiento".phpmkr_error()."sql:".$sqlBuscaMontoVencV);
		$rowBuscaMontoVencV = phpmkr_fetch_array($rsBuscaMontoVencV);
		$x_monto_VVencidoV = $rowBuscaMontoVencV["monto_vencido"];
		#echo "sql vencido".$sqlBuscaMontoVencV ."<br><br>";
		#echo "m0onto vencido--".$x_monto_VVencidoV."<br>";
		$x_numero_de_vencimientos_vencidos =  $x_monto_VVencidoV / $x_monto_pago_mora;
		#echo "vencimeintos vencidos arriba".$x_numero_de_vencimientos_vencidos."<br>";
	
	
	$sqlNoVencimientosVencidos = "SELECT COUNT(*) AS vencimientos_vencidos FROM vencimiento WHERE credito_id = $x_credito_id AND vencimiento_status_id = 3";
	$rsNoVncimeitosVencidos = phpmkr_query($sqlNoVencimientosVencidos,$conn)or die ("Error al seleccionar el numeros de venciemientos vencisdos del credito".phpmkr_error()."sql:".$sqlNoVencimientosVencidos);
	$rowNoVnecimientosVencidos = phpmkr_fetch_array($rsNoVncimeitosVencidos);
	//$x_numero_de_vencimientos_vencidos = $rowNoVnecimientosVencidos["vencimientos_vencidos"];
	$x_responsable_susursal_usuario_id = 0;
	$x_forma_pago_id = 0;
	$sqlFormaPago  = "SELECT forma_pago_id FROM credito WHERE credito_id = $x_credito_id ";
	$rsFormaPago = phpmkr_query($sqlFormaPago,$conn) or die ("Error al selecccionar la forma de pago del credito".phpmkr_error()."sql; ".$sqlFormaPago);
	$rowFormaPago = phpmkr_fetch_array($rsFormaPago);
	$x_forma_pago_id = $rowFormaPago["forma_pago_id"];
	
	
	echo "numero de vencimientos_vencidos".$x_numero_de_vencimientos_vencidos."<br>";
	if($x_numero_de_vencimientos_vencidos < 2){
		// la tarea sera asignada al 	RESPONSABLE DE SUCURSAL	
		# seleccionamos los datos del responsable de sucursal.
		$sqlSolId = "SELECT solicitud_id FROM credito WHERE credito_id = $x_credito_id";
		$rsSolId = phpmkr_query($sqlSolId,$conn) or die ("Error al seleccionar el id de la solicitud del credito".phpmkr_error()."sql:");
		$rowSolId = phpmkr_fetch_array($rsSolId);
		$x_solicitud_id_c = $rowSolId["solicitud_id"];
		
		// seleccionamos el promotor
		$sqlPromotor = "SELECT promotor_id FROM solicitud WHERE solicitud_id = $x_solicitud_id_c";
		$rsPromotor = phpmkr_query($sqlPromotor,$conn) or die ("Error al seleccionar el promotor del credito".phpmkr_error()."sql :".$sqlPromotor);
		$rowPromotor = phpmkr_fetch_array($rsPromotor);
		$x_promotor_id_c = $rowPromotor["promotor_id"];
		
		if($x_promotor_id_c > 0){
			// buscamos a que sucursal pertence el promotor
			$sqlSucursal = "SELECT sucursal_id FROM promotor WHERE promotor_id = $x_promotor_id_c";
			$rsSucursal = phpmkr_query($sqlSucursal,$conn) or die ("Error al seleccionar la sucursal". phpmkr_error()."Sql:".$sqlSucursal); 
			$rowSucuersal = phpmkr_fetch_array($rsSucursal);
			$x_sucursal_id_c = $rowSucuersal["sucursal_id"];
			
			if($x_sucursal_id_c > 0){
				// si ya tenbemos la sucursal, buscamos el representante de essa sucursal
				$sqlResponsable = "SELECT usuario_id FROM responsable_sucursal WHERE sucursal_id = $x_sucursal_id_c ";
				$rsResponsable = phpmkr_query($sqlResponsable,$conn) or die ("error al seleccionar el usuario del responsable de suscursal".phpmkr_error()."sql:".$sqlResponsable);
				$rowResponsable = phpmkr_fetch_array($rsResponsable);
				$x_responsable_susursal_usuario_id = $rowResponsable["usuario_id"];		
				
				}
			} 
		
		
		}
	
	if($x_caso_abierto == 0){
		
		// si no hay caso abierto.. quiere decir que es el primer atraso del cliente por lo tanto lo gationara el responsable de sucursal
		echo "ENTRA A CASO ABIERTO == 0<br>" .$x_credito_id."<br>";
		$sSqlWrk = "
		SELECT *
		FROM 
			crm_playlist
		WHERE 
			crm_playlist.crm_caso_tipo_id = 3
			AND crm_playlist.orden = 1
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_crm_playlist_id = $datawrk["crm_playlist_id"];
		$x_prioridad_id = $datawrk["prioridad_id"];	
		$x_asunto = $datawrk["asunto"];	
		$x_descripcion = $datawrk["descripcion"];		
		$x_tarea_tipo_id = $datawrk["tarea_fuente"];		
		$x_orden = $datawrk["orden"];	
		$x_dias_espera = $datawrk["dias_espera"];		
		@phpmkr_free_result($rswrk);
	
	
		//Fecha Vencimiento
		$temptime = strtotime($currdate);	
		$temptime = DateAdd('w',$x_dias_espera,$temptime);
		$fecha_venc = strftime('%Y-%m-%d',$temptime);			
		$x_dia = strftime('%A',$temptime);
		if($x_dia == "SUNDAY"){
			$temptime = strtotime($fecha_venc);
			$temptime = DateAdd('w',1,$temptime);
			$fecha_venc = strftime('%Y-%m-%d',$temptime);
		}
		$temptime = strtotime($fecha_venc);
	
	
	
		$x_origen = 1;
		$x_bitacora = "Cartera Vencida - (".FormatDateTime($currdate,7)." - $currtime)";
	
		$x_bitacora .= "\n";
		$x_bitacora .= "$x_asunto - $x_descripcion ";	


		$sSqlWrk = "
		SELECT cliente.cliente_id
		FROM 
			cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id join solicitud on solicitud.solicitud_id = solicitud_cliente.solicitud_id join credito on credito.solicitud_id = solicitud.solicitud_id
		WHERE 
			credito.credito_id = $x_credito_id
		LIMIT 1
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_cliente_id = $datawrk["cliente_id"];
		@phpmkr_free_result($rswrk);


		if(empty($x_responsable_susursal_usuario_id)){
			// si el usuario de rsponsable de sucursal esta vacio entonces se busca la persona a la que se le asignara el caso.
			// en este caso pasa al asesor de credito.
			
			
		$sqlSolId = "SELECT solicitud_id FROM credito WHERE credito_id = $x_credito_id";
		$rsSolId = phpmkr_query($sqlSolId,$conn) or die ("Error al seleccionar el id de la solicitud del credito".phpmkr_error()."sql:");
		$rowSolId = phpmkr_fetch_array($rsSolId);
		$x_solicitud_id_c = $rowSolId["solicitud_id"];
		
		// seleccionamos el promotor
		$sqlPromotor = "SELECT promotor_id FROM solicitud WHERE solicitud_id = $x_solicitud_id_c";
		$rsPromotor = phpmkr_query($sqlPromotor,$conn) or die ("Error al seleccionar el promotor del credito".phpmkr_error()."sql :".$sqlPromotor);
		$rowPromotor = phpmkr_fetch_array($rsPromotor);
		$x_promotor_id_c = $rowPromotor["promotor_id"];
		
		if($x_promotor_id_c > 0){
		// seleccionamos el usuario del promotor
		
				
		$sSqlWrk = "
		SELECT usuario_id
		FROM 
			promotor
		WHERE 
			promotor.promotor_id = $x_promotor_id_c ";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_usuario_id = $datawrk["usuario_id"];
		@phpmkr_free_result($rswrk);
		}
		
		#si el responsable de sucursal esta vacio, significa que son mas de dos pagos los que se deben
		# y significa que la tarea que se debe de asignar es la fase dos.
		# que corresponde a la carata 1
		
		#seleccionamos los datos de la carata 1
		
		$sSqlWrk = "SELECT *
							FROM 
							crm_playlist
							WHERE 
							crm_playlist.crm_caso_tipo_id = 3
							AND crm_playlist.orden = 4 "; // orden 4 CARTA  1		
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_crm_playlist_id = $datawrk["crm_playlist_id"];
				$x_prioridad_id = $datawrk["prioridad_id"];	
				$x_asunto = $datawrk["asunto"];	
				$x_descripcion = $datawrk["descripcion"];		
				$x_tarea_tipo_id = $datawrk["tarea_fuente"];		
				$x_orden = $datawrk["orden"];	
				$x_dias_espera = $datawrk["dias_espera"];		
				@phpmkr_free_result($rswrk);
				
				
				
				//Fecha Vencimiento
				$temptime = strtotime($currdate);	
				$temptime = DateAdd('w',$x_dias_espera,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if($x_dia == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$temptime = strtotime($fecha_venc);
	
	
	
				$x_origen = 1;  // el origen se podria cambiar a responsable de sucursal.
				$x_bitacora = "Cartera Vencida  INICIA EN ETAPA 2  CARTA 1 - (".FormatDateTime($currdate,7)." - $currtime)";
			
				$x_bitacora .= "\n";
				$x_bitacora .= "$x_asunto - $x_descripcion ";	
		
		
		
		
		}else{
			// si el usuario de responsable viene lleno...
			# se le asigna la tarea a el....
			$x_usuario_id  = $x_responsable_susursal_usuario_id;
			
			}
			if($x_cliente_id <1){
				$x_cliente_id = 0;
				}
	
		$sSql = "INSERT INTO crm_caso values (0,3,1,1,$x_cliente_id,'".$currdate."',$x_origen,$x_usuario_id,'$x_bitacora','".$currdate."',NULL,$x_credito_id)";
	
		$x_result = phpmkr_query($sSql, $conn) or die ("error al insertar carta 1". phpmkr_error()."sql.".$sSql);
		echo "INSERT  :<BR>".$sSql."<BR>";
		$x_crm_caso_id = mysql_insert_id();	


		if(($x_crm_caso_id > 0)){

			$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 2, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
		
			$x_result = phpmkr_query($sSql, $conn);
			$x_tarea_ida = mysql_insert_id();
	
			$sSqlWrk = "
			SELECT 
				comentario_int
			FROM 
				credito_comment
			WHERE 
				credito_id = ".$x_credito_id."
			LIMIT 1
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			echo "INSERT TAREA:<BR>".$sSql."<BR> TERMINA CLICLO NO TIENE TAREA <BR><BR>";
			$x_comment_ant = $datawrk["comentario_int"];
			@phpmkr_free_result($rswrk);
	
	
			if(empty($x_comment_ant)){
				$sSql = "insert into credito_comment values(0, $x_credito_id, '$x_bitacora', NULL)";
			}else{
				$x_bitacora = $x_comment_ant . "\n\n------------------------------\n" . $x_bitacora;
				$sSql = "UPDATE credito_comment set comentario_int = '$x_bitacora' where credito_id = $x_credito_id";
			}
	
			phpmkr_query($sSql, $conn);
		
		
		if(empty($x_responsable_susursal_usuario_id)){
	
        ########################################################################################################################################################
		############################################################ TAREAS DIARIAS PARA PROMOTORES	############################################################
		########################################################################################################################################################
		##credamos las lista de los promotores con la tareas diarias.		
		## seleccionaos la soza de la solcitud		
		$sqlZonaId = "SELECT dia FROM  zona JOIN solicitud ON solicitud.zona_id = zona.zona_id WHERE solicitud.solicitud_id = $x_solicitud_id_c";
		$rsZonaId = phpmkr_query($sqlZonaId,$conn) or die ("Error al seleccionar el dia de la zona".phpmkr_error()."sql:".$sqlZonaId);
		$rowZonaId = phpmkr_fetch_array($rsZonaId);
		$x_dia_zona = $rowZonaId["dia"];
		
		$x_fecha_tarea_f = $currdate;
		$x_dia_zona_f = $x_dia_zona;
		$x_promotor_id_f = $x_promotor_id_c ;
		$x_caso_id_f = $x_crm_caso_id;
		$x_tarea_id_f = $x_tarea_ida;
		
		
		#tenemos la fecha de hoy  --> hoy es miercoles 30 de enero
		# se debe buscar la fecha de la zona --> si la zona fuera 5.. la fecha se deberia cambiar a viernes 1 de febrero
		# o si la zona fuera  2 como las tareas de la zona 2 ya fueron asignadas.. se debe buscar la fecha de la zona dos de la sig semana
		
		$sqlDiaSemana = "SELECT DAYOFWEEK('$x_fecha_tarea_f') AS  dia";
		$rsDiaSemana = phpmkr_query($sqlDiaSemana,$conn) or die ("Error al seleccionar el dia de la semana".phpmkr_error()."sql:".$sqlDiaSemana);
		$rowDiaSemana = phpmkr_fetch_array($rsDiaSemana);
		$x_dia_de_semana_en_fecha = $rowDiaSemana["dia"];
		#echo "fecha day of week".$x_dia_de_semana_en_fecha ."<br>";
		
		if($x_dia_de_semana_en_fecha != $x_dia_zona_f ){
			// el dia de la zona y el dia de la fecha no es el mismo se debe incrementar la fecha hasta llegar al mismo dia
			
			#echo "LOS DIAS SON DIFERENTES<BR>";
		//	$x_dia_de_semana_en_fecha = 1;
		//	$x_dia_zona_f= 5;
		//	$x_fecha_tarea_f = "2013-01-29";
			if($x_dia_de_semana_en_fecha < $x_dia_zona_f){
				// la fecha es mayor dia_en _fecha = 2; dia_zona = 5
				$x_dias_faltantes = $x_dia_zona_f - $x_dia_de_semana_en_fecha;	
				//hacemos el incremento en la fecha		
				//Fecha de tarea para la lista
				
				#echo "fecha dia------".$x_fecha_tarea_f."<br>";
				$temptime = strtotime($x_fecha_tarea_f);	
				$temptime = DateAdd('w',$x_dias_faltantes,$temptime);
				$fecha_nueva = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				$x_fecha_tarea_f = $fecha_nueva;	
				#echo "nueva fecha...ESTA semana<br> dias faltantes".$x_dias_faltantes."<br>";			
				}else{
					// el dia dela fecha es mayor al dia de la zona...las tareas asignadas para esa zona ya pasaron; porque ya paso el dia de esa zona
					// se debe asigna la tarea para la semana sig el la fecha de la zona.
					$x_dias_faltantes = (7- $x_dia_de_semana_en_fecha)+ $x_dia_zona_f;
					//hacemos el incremento en la fecha		
					//Fecha de tarea para la lista
					$temptime = strtotime($x_fecha_tarea_f);	
					$temptime = DateAdd('w',$x_dias_faltantes,$temptime);
					$fecha_nueva = strftime('%Y-%m-%d',$temptime);			
					$x_dia = strftime('%A',$temptime);
					$x_fecha_tarea_f = $fecha_nueva;	
					#echo "nueva fecha...sigueinte semana<br> dias faltantes".$x_dias_faltantes."<br>";
					#echo "DIA DE LA SEMANA EN FECHA".$x_dia_de_semana_en_fecha."<br>";
					#echo "dia zona".$x_dia_zona_f;					
					}
			}
		
		$x_dias_agregados = calculaSemanas($conn, $x_fecha_tarea_f, $x_dia_zona_f, $x_promotor_id_f, $x_caso_id_f, $x_tarea_id_f);
		echo " -------- DIAS AGREGADOS ------ ".$x_dias_agregados."<br> "; 
		//kuki
		
		// se gragan los dias que faltan si es que es mayor de 0
		if($x_dias_agregados > 0){
					$temptime = strtotime($x_fecha_tarea_f);	
					$temptime = DateAdd('w',$x_dias_agregados,$temptime);
					$fecha_nueva = strftime('%Y-%m-%d',$temptime);			
					$x_fecha_tarea_f = $fecha_nueva;
				// se hizo el incremento en la fecha				
				//se actualiza la tarea con la fecha nueva
				$x_fecha_ejecuta_act = $x_fecha_tarea_f;
				
				$temptime = strtotime($x_fecha_ejecuta_act);	
				$temptime = DateAdd('w',$x_dias_espera,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if($x_dia == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$x_fecha_ejecuta_act = $fecha_venc;
				
				$sqlUpdateFecha = "UPDATE crm_tarea SET  fecha_ejecuta = \"$x_fecha_ejecuta_act\" WHERE crm_tarea_id = $x_tarea_id_f ";
				$rsUpdateFecha = phpmkr_query($sqlUpdateFecha,$conn) or die ("Error al actualiza la fecha de latarea despues del calculo semana".phpmkr_error()."sql;".$sqlUpdateFecha);
				echo "UPDATAE TREA---".$sqlUpdateFecha."<br>";
				
		}else{
			$x_fecha_ejecuta_act  = $x_fecha_tarea_f;
			$temptime = strtotime($x_fecha_ejecuta_act);	
				$temptime = DateAdd('w',$x_dias_espera,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if($x_dia == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$x_fecha_ejecuta_act = $fecha_venc;
			$sqlUpdateFecha = "UPDATE crm_tarea SET fecha_ejecuta = \"$x_fecha_ejecuta_act\" WHERE crm_tarea_id = $x_tarea_id_f ";
				$rsUpdateFecha = phpmkr_query($sqlUpdateFecha,$conn) or die ("Error al actualiza la fecha de latarea despues del calculo semana".phpmkr_error()."sql;".$sqlUpdateFecha);
				echo "UPDATAE TREA---".$sqlUpdateFecha."<br>";
			}
		#echo "FECHA EJECUTA".$x_fecha_ejecuta_act;
		#####################################################################################################################
		##################################### TAREAS DIARIAS PROMOTOR #######################################################
		#####################################################################################################################
		
		// primero verifamos que la tarea aun no este en la lista, es decir, que se trate de la primera vez que entra al cliclo para este credito.
		$sqlBuscaatreaAsignada = "SELECT COUNT(*) AS atreas_asignadas FROM `tarea_diaria_promotor` WHERE fecha_ingreso =  \"$currdate\" AND promotor_id = $x_promotor_id_f ";
		$sqlBuscaatreaAsignada .= " AND `caso_id` = $x_caso_id_f";
		$rsBuscatareaAsignada = phpmkr_query($sqlBuscaatreaAsignada,$conn) or die("Erro al buscar atrea".phpmkr_error()."sql:".$sqlBuscaatreaAsignada);
		echo "UPDATE TAREAS".$rsBuscatareaAsignada."<BR>";
		#echo "BUSCA TAREAS".$sqlBuscaatreaAsignada."<BR>";
		$rowBuscaTareaAsignada = phpmkr_fetch_array($rsBuscatareaAsignada);
		$x_tareas_asignadas_del_caso = $rowBuscaTareaAsignada["atreas_asignadas"];
		#echo "TAREAS ASIGNADAS ".$x_tareas_asignadas_del_caso."<br>";
		//se inserta la tarea en la lista de las actividades diarias del promotor
		if ($x_tareas_asignadas_del_caso < 1){
		$sqlInsertListaTarea = "INSERT INTO `tarea_diaria_promotor`";
		$sqlInsertListaTarea .= " (`tarea_diaria_promotor_id`, `promotor_id`, `zona_id`, `dia_semana`, `fecha_ingreso`, `fecha_lista`, `caso_id`, ";
		$sqlInsertListaTarea .= " `tarea_id`, `reingreso`, `fase`, `status_tarea`, `credito_id`) ";
		$sqlInsertListaTarea .= "VALUES (NULL, $x_promotor_id_f, $x_dia_zona_f , $x_dia_zona_f, \"$currdate\",\"$x_fecha_tarea_f\", $x_caso_id_f, $x_tarea_id_f, '0', '2', '1', $x_credito_id);";
		$rsInsertListaTarea = phpmkr_query($sqlInsertListaTarea,$conn)or die("Error al insertar en lista diaria tareas".phpmkr_error()."sql:".$sqlInsertListaTarea);	 
		
		
		
		}
		}
		}
		
	
	}else{
		
		$x_avanza_fase = 0;
		$x_promesas_c1 = 0;
		$x_fecha_promesa_pago_c1 = 0;
		$x_promesas_c2 = 0;
		$x_fecha_promesa_pago_c2 = 0;
		$x_promesas_c3 = 0;
		$x_fecha_promesa_pago_c3 = 0;
		$x_promesas_cD = 0;
		$x_fecha_promesa_pago_cD = 0; 
		
	
			
		
		
	
			
		
		$sqlCartaD = "SELECT COUNT(*) AS crm_tarea_id FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND  crm_tarea_tipo_id = 12 AND orden= 20";
		$rsCartaD = phpmkr_query($sqlCartaD,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCartaD);
		$rowCarataD = phpmkr_fetch_array($rsCartaD);
		$x_carta_D_tarea_id = $rowCarataD["crm_tarea_id"];
		if($x_carta_D_tarea_id >0 ){
			//buscamos que ya tenga Una promesa de pago y que este vencida para entrar al ciclo			
			$sqlPPCD = "SELECT * FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id  AND crm_tarea_tipo_id = 5 AND orden = 24 ";
			$rsppcD = phpmkr_query($sqlPPCD,$conn)or die ("Error al seleccioanr la pp c2".phpmkr_error()."sql:".$sqlPPCD);
			$rowppcD = phpmkr_fetch_array($rsppcD);
			$x_fecha_registro_tarea = $rowppcD["fecha_registro"];
			$x_tarea_ppcD =$rowppcD["crm_tarea_id"];
			$x_caso_ppcD = $rowppcD["crm_caso_id"];			
			if($x_caso_ppcD> 0){
				// la promesa de pago esta registrada; buscamos la fecha de pagoo de la promesa
				$sqlcrm_tarea_cvD = "SELECT COUNT(*) AS promesas, promesa_pago  FROM crm_tarea_cv WHERE crm_tarea_id = $x_tarea_ppc1 ";
				$rscrm_tarea_cvD = phpmkr_query($sqlcrm_tarea_cvD,$conn)or die("Error al selecionar la fecha de promesa de pago".phpmkr_error()."sql:".$sqlcrm_tarea_cvD);
				$rowcrm_tarea_cvD = phpmkr_fetch_array($rscrm_tarea_cvD);
				$x_promesas_cD = $rowcrm_tarea_cvD["promesas"];
				$x_fecha_promesa_pago_cD = $rowcrm_tarea_cvD["crm_tarea_cv"]; 			
				}			
			}
			
					
		#ya existe un caso abierto pero ahora debemos de revisar si el credito
		#ya presneta mas de dos venciemitpos vencidos entonces cambiamos el caso y la tarea al asesor de credito.
		
		//seleccionamos los datos del responsable de susursal
		
		# se debe seleccionar si
		# ya tiene carat 1
		# ya tiene carta 2
		# ya tiene carta 3 
		# ya tiene demanda
		
		# si ya tiene comision por monto
		
		
		
		
		// contamos los vencimientos pendientes, para las validaciones de los ciclos de las promesas de pago
		// los vencimetos debe de estar como pendientes o como remanentes;
		$x_vencimeintos_pendientes_de_pago = 0;
		$sqlVencimientosPendientes = "SELECT COUNT(*)  vecimientos_pendientes_por_pagar FROM vencimiento WHERE credito_id = $x_credito_id";
		$sqlVencimientosPendientes .= " and vencimiento.vencimiento_status_id in (1,7)";
		$rsVencimientosPendientes = phpmkr_query($sqlVencimientosPendientes,$conn) or die ("Error en vencimeintos pendientes". phpmkr_error()."sql:".$sqlVencimientosPendientes);
		$rowVencimeintosPendientes = phpmkr_fetch_array($rsVencimientosPendientes);
		$x_vencimeintos_pendientes_de_pago = $rowVencimeintosPendientes["vecimientos_pendientes_por_pagar"];
	
		echo "ENTRA EN CASO ABIERTO DIFERENTE A 0 <br> ".$x_credito_id."<br>";
		$sSqlWrk = "SELECT crm_caso_id 
					FROM 
						crm_caso
					WHERE 
						crm_caso.crm_caso_tipo_id = 3
						AND crm_caso.crm_caso_status_id = 1
						AND crm_caso.credito_id = $x_credito_id";
						
	
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$datawrk = phpmkr_fetch_array($rswrk);
	$x_crm_caso_id = $datawrk["crm_caso_id"];	
	
	//VERFICAMOS QUE PARA EL DIA DE HOY AUN NO EXISTA NINGUNA TAREA REGISTRADA....
	// SI YA EXISTE ALGUNE ATREA REGISTRADA SE SALE DEL CLICLO.
	$sqlTreaHoy = "SELECT COUNT(*) AS tarea_asignada_hoy FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND crm_tarea_status_id = 1";
	$rsTareaHoy = phpmkr_query($sqlTreaHoy,$conn) or die ("Error a seleccionar la tarea de hoy".phpmkr_error()."sql:".$sqlTreaHoy);
	$rowTareaHoy = phpmkr_fetch_array($rsTareaHoy);
	$x_tarea_del_caso_hoy = $rowTareaHoy["tarea_asignada_hoy"] + 0;
	
	echo "sql ".$sqlTreaHoy."<br>";
	echo "tareas de hoy ".$x_tarea_del_caso_hoy."<br>";
	
	if($x_tarea_del_caso_hoy < 1){
	echo "caso_id".$x_crm_caso_id."<br>";
	$x_comision_generada = 0;
	$x_penalizacion_a = 0;	
	$x_monto_VVencido = 0;
	$x_forma_de_pago = 0;
	$x_carta_1 = 0;
	$x_carta_2 = 0;
	$x_carta_3 = 0;
	$x_carta_D = 0;
	$x_ciclo_carta_2 = 0;
	$x_ciclo_carta_3 = 0;
	$x_ciclo_carta_D = 0;
	
	
	$sqlCarta1 = "SELECT COUNT(*) AS carta_1 FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND  crm_tarea_tipo_id = 8 AND orden= 4";
	$rsCarta1 = phpmkr_query($sqlCarta1,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCarta1);
	$rowCarata1 = phpmkr_fetch_array($rsCarta1);
	$x_carta_1 = $rowCarata1["carta_1"] +0;
	echo "carta 1..". $x_carta_1."<br>";
	echo "vencimientos pendientes de pago ".$x_vencimeintos_pendientes_de_pago."<br>";
	$x_cambia_cliclo_c1 = 0;
	if( $x_vencimeintos_pendientes_de_pago == 0){
		// ya todos estan vencidos 7 puede que el monto no sea suficiente para generar la comision por cobranza
		// SELECCIONAMOS LA PP DE CARTA 1
	$sqlPPC1 = "SELECT * FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id  AND crm_tarea_tipo_id = 5 AND orden = 5 ";
	$rsppc1 = phpmkr_query($sqlPPC1,$conn)or die ("Error al seleccioanr la pp c2".phpmkr_error()."sql:".$sqlPPC1);
	$rowppc1 = phpmkr_fetch_array($rsppc1);
	$x_fecha_registro_tarea = $rowppc1["fecha_registro"];
	$x_tarea_ppc1 =$rowppc1["crm_tarea_id"];
	$x_caso_ppc1 = $rowppc1["crm_caso_id"];
	
	
	//selecciono la carta 1
	$sqlCarta1_id = "SELECT crm_tarea_id AS carta_1_id FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND  crm_tarea_tipo_id = 8 AND orden= 4";
	$rsCarta1_id = phpmkr_query($sqlCarta1_id,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCarta1_id);
	$rowCarata1_id = phpmkr_fetch_array($rsCarta1_id);
	$x_carat_1_id = $rowCarata1_id["carta_1_id"];
	echo "carta 1 id ***".$x_carat_1_id."<br>";
	
	if(!empty($x_carat_1_id)){
	$sqlFechappc1 = "SELECT * FROM  crm_tarea_cv WHERE crm_tarea_id = $x_carat_1_id ";
	$rsFechappc1 = phpmkr_query($sqlFechappc1,$conn)or die ("Error al seleccionar la fechappc1a".phpmkr_error()."sql:".$sqlFechappc1);
	$rowfechappc1 = phpmkr_fetch_array($rsFechappc1);
	$x_fecha_entrega_c1 = $rowfechapp1["promesa_pago"];
	

	#########################################################
	#############     CICLO POR DIAS    #####################
	#########################################################
	if(!empty($x_carat_1_id)){
	$sqlFechaCarata1 = "SELECT fecha_entrega FROM  crm_tarea_cv WHERE crm_tarea_id = $x_carat_1_id ";
	$rsFechaCarta1 = phpmkr_query($sqlFechaCarata1,$conn) or die ("Error al seleccionar la fecha de entrega de la carata". phpmkr_error()."sql :".$sqlFechaCarata1);
	$rowFechaCarta1 = phpmkr_fetch_array($rsFechaCarta1);
	$x_fecha_imprime_carat1 = $rowFechaCarta1["fecha_entrega"];
	
	// SE BUSCA LA FECHA DE LA PROMESA DE PAGO PARA LA CARTA 1;
	// YA NO SE BUSCA LA PROMESA DE PAGO PARA LA CARTA
	// SE BUESCA LA ULTIMA PROMESA DE PAGO
	
	$sqlPPC1 = "SELECT * FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id  AND crm_tarea_tipo_id = 5 AND orden = 5 ORDER BY crm_tarea_id DESC LIMIT 0,1 ";
	$rsppc1 = phpmkr_query($sqlPPC1,$conn)or die ("Error al seleccioanr la pp c2".phpmkr_error()."sql:".$sqlPPC1);
	$rowppc1 = phpmkr_fetch_array($rsppc1);
	$x_fecha_registro_tarea_PP_U = $rowppc1["fecha_registro"];
	$x_tarea_ppc1_PP_U =$rowppc1["crm_tarea_id"];
	//TENEMOS LA ULTIMA PROMESA DE PAGO
	echo "ultima promesa de pago".$sqlPPC1 ."<br>".$x_tarea_ppc1_PP_U." ----<br>";
	
	// SELECCIONAMOS LA FECHA QUE DUIO EL CLIENTE PARA PAGAR
	if (!empty($x_tarea_ppc1_PP_U)){
				// seleccionamos la fecha de  la promesa de pago
	$sqlFechappc1 = "SELECT * FROM  crm_tarea_cv WHERE crm_tarea_id = $x_tarea_ppc1_PP_U ";
	$rsFechappc1 = phpmkr_query($sqlFechappc1,$conn)or die ("Error al seleccionar la fechappc1a".phpmkr_error()."sql:".$sqlFechappc1);
	$rowfechappc1 = phpmkr_fetch_array($rsFechappc1);
	echo "<br> FECHA PROMESA DE PAGO ..........".$sqlFechappc1."<BR>";
	$x_fecha_entrega_PP_U = $rowfechappc1["promesa_pago"]; //esta fecha de promesa de pago si esta bien......
	echo "____".$rowfechapp1["promesa_pago"]."_____";
		}
	
	
	
	}
	
	echo "<br>===============FECHA DE PROMESA DE PAGO  =============".$x_fecha_entrega_PP_U."<br>";
		
	$x_dias_transcurridos_c1  = datediff('d', $x_fecha_entrega_PP_U, $currdate, false);
	echo "<br>===============dias transcurridos entre la fecha imprime y hoy =============".$x_dias_transcurridos_c1."<br>";
	$x_cambia_cliclo_c1 = 0;
	// verificar la forma  de pago del credito
	
	// fomas de pago
	// 1 = semanal
	// 2 = catorcenal
	// 3 = mensual
	// 4 = quincenal
	
	
	
	
	echo "vencimientos_pendienes de pgo".$x_vencimeintos_pendientes_de_pago."<br>";
	if($x_vencimeintos_pendientes_de_pago == 0){
	switch($x_forma_pago_id)
	{
		case 1:
		if($x_dias_transcurridos_c1 > 20){
			$x_cambia_cliclo_c1 = 1;
			}
		break;
		case 2:
		if($x_dias_transcurridos_c1 > 28){
			$x_cambia_cliclo_c1 = 1;
			}
		break;
		case 3:
		if($x_dias_transcurridos_c1 > 34){
			$x_cambia_cliclo_c1 = 1;
			}
			break;
		case 4;
		if($x_dias_transcurridos_c1 > 30){
			$x_cambia_cliclo_c1 = 1;
			}		
		
		
		
		}
	
	
	
	}else{
		$x_cambia_cliclo_c1 = 0;		
		}
	}
	
	
	
		
		}
	echo "CAMBIA CICLO ".$x_cambia_cliclo_c1."<BR>";
	
	

	
	$sqlCarta2 = "SELECT COUNT(*) AS carta_2 FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND  crm_tarea_tipo_id = 9 AND orden= 8";
	$rsCarta2 = phpmkr_query($sqlCarta2,$conn)or die ("Error al seleccionar la carta 2".phpmkr_error()."sql:".$sqlCarta2);
	$rowCarata2 = phpmkr_fetch_array($rsCarta2);
	$x_carta_2 = $rowCarata2["carta_2"] +0;
	
	echo "carta 2..". $x_carta_2."<br>";
	
	// SELECCIONAMOS LA PP DE CARTA 2
	$sqlPPC2 = "SELECT * FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id  AND crm_tarea_tipo_id = 5 AND orden = 9 ";
	$rsppc2 = phpmkr_query($sqlPPC2,$conn)or die ("Error al seleccioanr la pp c2".phpmkr_error()."sql:".$sqlPPC2);
	$rowppc2 = phpmkr_fetch_array($rsppc2);
	$x_fecha_registro_tarea = $rowppc2["fecha_registro"];
	$x_tarea_ppc2 =$rowppc2["crm_tarea_id"];
	$x_caso_ppc2 = $rowppc2["crm_caso_id"];
	//carta 2
	$sqlC2_id = "SELECT * FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND  crm_tarea_tipo_id = 9 AND orden= 8";
	$rsc2_id = phpmkr_query($sqlC2_id,$conn)or die ("Error al seleccioanr la pp c2".phpmkr_error()."sql:".$sqlC2_id);
	$rowc2_id = phpmkr_fetch_array($rsc2_id);
	$x_tarea_c2_id =$rowc2_id["crm_tarea_id"];
	
	#########################################################
	#############     CICLO POR DIAS    #####################
	#########################################################
	if(!empty($x_tarea_c2_id)){
		
		// si existe la carta 2; dia en  que se imprimio la carata C2
	$sqlFechaCarata2 = "SELECT fecha_entrega FROM  crm_tarea_cv WHERE crm_tarea_id 	 = $x_tarea_c2_id ";
	$rsFechaCarta2 = phpmkr_query($sqlFechaCarata2,$conn) or die ("Error al seleccionar la fecha de entrega de la carata". phpmkr_error()."sql :".$sqlFechaCarata2);
	$rowFechaCarta2 = phpmkr_fetch_array($rsFechaCarta2);
	$x_fecha_imprime_carat2 = $rowFechaCarta2["fecha_entrega"];	
	// SE BUSCA LA FECHA DE LA PROMESA DE PAGO PARA LA CARTA 1;
	// YA NO SE BUSCA LA fecha de impresion DE PAGO PARA LA CARTA
	// SE BUESCA LA ULTIMA PROMESA DE PAGO
	$sqlPPC2 = "SELECT * FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id  AND crm_tarea_tipo_id = 5 AND orden = 9 ORDER BY crm_tarea_id DESC LIMIT 0,1 ";
	$rsppc2 = phpmkr_query($sqlPPC2,$conn)or die ("Error al seleccioanr la pp c2".phpmkr_error()."sql:".$sqlPPC2);
	$rowppc2 = phpmkr_fetch_array($rsppc2);
	$x_fecha_registro_tarea = $rowppc2["fecha_registro"];
	$x_tarea_ppc2_PP_U =$rowppc2["crm_tarea_id"];
	$x_caso_ppc2 = $rowppc2["crm_caso_id"];
	
	
	if(!empty($x_tarea_ppc2_PP_U)){
		// si existe la carta 2// buscamos la fecha de la promesa de pago
		$sqlFechaCarata2 = "SELECT promesa_pago FROM  crm_tarea_cv WHERE crm_tarea_id 	 = $x_tarea_ppc2_PP_U ";
	$rsFechaCarta2 = phpmkr_query($sqlFechaCarata2,$conn) or die ("Error al seleccionar la fecha de entrega de la carata". phpmkr_error()."sql :".$sqlFechaCarata2);
	$rowFechaCarta2 = phpmkr_fetch_array($rsFechaCarta2);
	$x_fecha_imprime_carat2_PP_U = $rowFechaCarta2["promesa_pago"];			
		}
	
	
	// si no hay bpromesa de pago; cambia ciclo se quedara en 1 porque los dias de diferencia seran enormes....
	
	
	}
	$x_dias_transcurridos_c2  = datediff('d', $x_fecha_imprime_carat2_PP_U, $currdate, false);
	$x_cambia_cliclo_c2 = 0;
	// verificar la forma  de pago del credito
	
	
	
	// fomas de pago
	// 1 = semanal
	// 2 = catorcenal
	// 3 = mensual
	// 4 = quincenal
	if($x_vencimeintos_pendientes_de_pago == 0){
	switch($x_forma_pago_id)
	{
		case 1:
		if($x_dias_transcurridos_c2 > 20){
			$x_cambia_cliclo_c2 = 1;
			}
		break;
		case 2:
		if($x_dias_transcurridos_c2 > 28){
			$x_cambia_cliclo_c2 = 1;
			}
		break;
		case 3:
		if($x_dias_transcurridos_c2 > 34){
			$x_cambia_cliclo_c2 = 1;
			}
			break;
		case 4;
		if($x_dias_transcurridos_c2 > 30){
			$x_cambia_cliclo_c2 = 1;
			}		
		
		
		
		}
	
	
	
	}else{
		$x_cambia_cliclo_c2 = 0;		
		}
	
	
	
	echo "busca fecha tarea ".$sqlPPC2."<br>";
	
	if(!empty($x_tarea_ppc2)){
	$sqlFechappc2 = "SELECT * FROM  crm_tarea_cv WHERE crm_tarea_id = $x_tarea_ppc2 ";
	$rsFechappc2 = phpmkr_query($sqlFechappc2,$conn)or die ("Error al seleccionar la fechappc1a".phpmkr_error()."sql:".$sqlFechappc2);
	$rowfechappc2 = phpmkr_fetch_array($rsFechappc2);
	$x_fecha_promesa_pago_c2 = $rowfechapp2["promesa_pago"];
	$x_crm_tarea_cv_ppc2 = $rowfechapp2["crm_tarea_cv_id"] +0;
	

	}
	if($x_crm_tarea_cv_ppc2 < 1){
		if(!empty($x_tarea_c2_id )){
		// no tenemos promesa de pago; se debe remplazar esa fecha por la fecha de impresion de la carta;
	$sqlFechappc2 = "SELECT * FROM  crm_tarea_cv WHERE crm_tarea_id = $x_tarea_c2_id ";
	$rsFechappc2 = phpmkr_query($sqlFechappc2,$conn)or die ("Error al seleccionar la fechappc1a".phpmkr_error()."sql:".$sqlFechappc2);
	$rowfechappc2 = phpmkr_fetch_array($rsFechappc2);
	$x_fecha_promesa_pago_c2 = $rowfechapp2["fecha_entrega"]; // fecha ne que se entrego la carta
		}
	
		}
	// tenemos la fecha de la tarea y tenemos la fecha de la promesa de pago..
	// buscamos si relizo algun pago desde la fecha de la atrea hasta la fecha e la promesa de pago.
	
	
	////////////////////// esta la fecha en que se registro la tarea...////////////////////////////////////////
	$x_pago_registrado_ppc2 = 0;
	$sqlBuscapagoPPC2 = "SELECT COUNT(*) AS pago_registrado FROM recibo JOIN recibo_vencimiento ON recibo_vencimiento.recibo_id = recibo.recibo_id";
	$sqlBuscapagoPPC2 .= " JOIN vencimiento ON vencimiento.vencimiento_id = recibo_vencimiento.vencimiento_id ";
	$sqlBuscapagoPPC2 .= " WHERE vencimiento.credito_id = $x_credito_id ";
	$sqlBuscapagoPPC2 .= " AND recibo.fecha_pago >= \"$x_fecha_registro_tarea\" "; 
	$rsbuscaPPC2 = phpmkr_query($sqlBuscapagoPPC2,$conn) or die("error al buscar el pago de ppc2".phpmkr_error()."sql:".$sqlBuscapagoPPC2);
	$rowbuscapagoPPC2 = phpmkr_fetch_array($rsbuscaPPC2);
	$x_pago_registrado_ppc2 = $rowbuscapagoPPC2["pago_registrado"]+0;
	
	echo "<BR><BR>NUMERO DE PAGOS VENCIDOS CARTA 2 --".$x_numero_de_vencimientos_vencidos."<BR><BR>"; 
	
	
	echo "pagos registrados".$x_pago_registrado_ppc2."<br>";
	echo "sql busca pago".$sqlBuscapagoPPC2."<br>";
	
	$x_cumple_promesa_pago = 0;
	
	
	 if ($x_cambia_cliclo_c2 == 0){
	if ($x_pago_registrado_ppc2 > 0){
		$x_cumple_promesa_pago = 1;
		}
		echo "cumple promesa de pago".$x_cumple_promesa_pago."<br>";
		echo "nuemo de vencimeintos vencidos".$x_numero_de_vencimientos_vencidos."<br>";
		echo "forma de pago".$x_forma_pago_id."<br>"; 
	if($x_forma_pago_id == 1){
			// semanal
			if($x_cumple_promesa_pago == 1){
				// si cumplio la promesa de pago
				if($x_numero_de_vencimientos_vencidos >= 6){
					$x_ciclo_carta_2 = 0;
					}else{
						$x_ciclo_carta_2 = 1;
						// se cicla
						}
				
				}else{
					// no cumplio la promesa de pago
					if($x_numero_de_vencimientos_vencidos < 5){
						$x_ciclo_carta_2 = 1; // no pago pero no debe mas de 5 5vencimiento. lo ciclamos en esta etapa
						}else{
							$x_ciclo_carta_2 = 0; // lo mandamos a la siguiente etapa
							}				
					}
			
			}else if(($x_forma_pago_id == 2) || ($x_forma_pago_id == 4)){
				// catorcenal ó quincenal
					if($x_cumple_promesa_pago == 1){
					// si cumplio la promesa de pago
					if($x_numero_de_vencimientos_vencidos >= 4){
						$x_ciclo_carta_2 = 0;
						}else{
							$x_ciclo_carta_2 = 1;
							// se cicla
							}					
					}else{
						// no cumplio la promesa de pago
						if($x_numero_de_vencimientos_vencidos < 4){
							$x_ciclo_carta_2 = 1; // no pago pero no debe mas de 5 5vencimiento. lo ciclamos en esta etapa
							}else{
								$x_ciclo_carta_2 = 0; // lo mandamos a la siguiente etapa
								}				
						}
				
				}else if($x_forma_pago_id == 3){
					//MENSUAL
					if($x_cumple_promesa_pago == 1){
					// si cumplio la promesa de pago
					if($x_numero_de_vencimientos_vencidos >= 4){
						$x_ciclo_carta_2 = 0;
						}else{
							$x_ciclo_carta_2 = 1;
							// se cicla
							}					
					}else{
						// no cumplio la promesa de pago
						if($x_numero_de_vencimientos_vencidos < 4){
							$x_ciclo_carta_2 = 1; // no pago pero no debe mas de 4 vencimiento. lo ciclamos en esta etapa
							}else{
								$x_ciclo_carta_2 = 0; // lo mandamos a la siguiente etapa
								}				
						}
					
					}
	 } else{
		 $x_ciclo_carta_2 = 0;		 
		 }
	echo "CICLO CARTA 2".$x_ciclo_carta_2."<BR>";
	
	
	
	
	
	
	$sqlCarta3 = "SELECT COUNT(*) AS carta_3 FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND  crm_tarea_tipo_id = 10 AND orden= 12";
	$rsCarta3 = phpmkr_query($sqlCarta3,$conn)or die ("Error al seleccionar la carta 3".phpmkr_error()."sql:".$sqlCarta3);
	$rowCarata3 = phpmkr_fetch_array($rsCarta3);
	$x_carta_3 = $rowCarata3["carta_3"] +0;
	echo "carta 3..". $x_carta_3."<br>";
	
		// SELECCIONAMOS LA PP DE CARTA 3
		if($x_carta_3 > 0){
	$sqlPPC3 = "SELECT * FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id  AND crm_tarea_tipo_id = 5 AND orden = 13 ";
	$rsppc3 = phpmkr_query($sqlPPC3,$conn)or die ("Error al seleccioanr la pp c2".phpmkr_error()."sql:".$sqlPPC3);
	$rowppc3 = phpmkr_fetch_array($rsppc3);
	$x_fecha_registro_tarea_3 = $rowppc3["fecha_registro"];
	$x_tarea_ppc3 =$rowppc3["crm_tarea_id"];
	$x_caso_ppc3 = $rowppc3["crm_caso_id"];
	
	
	$sqlCarta3_id = "SELECT crm_tarea_id  FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND  crm_tarea_tipo_id = 10 AND orden= 12";
	$rsCarta3_id = phpmkr_query($sqlCarta3_id,$conn)or die ("Error al seleccionar la carta 3".phpmkr_error()."sql:".$sqlCarta3_id);
	$rowCarata3_id = phpmkr_fetch_array($rsCarta3_id);
	$x_carta_3_id = $rowCarata3_id["crm_tarea_id"];
	
	$sqlCarta3_id = "SELECT crm_tarea_id  FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND  crm_tarea_tipo_id = 10 AND orden= 12 ORDER BY crm_tarea_id DESC LIMIT 0,1 ";
	$rsCarta3_id = phpmkr_query($sqlCarta3_id,$conn)or die ("Error al seleccionar la carta 3".phpmkr_error()."sql:".$sqlCarta3_id);
	$rowCarata3_id = phpmkr_fetch_array($rsCarta3_id);
	$x_carta_3_id_PP_U = $rowCarata3_id["crm_tarea_id"];
	
	$sqlFechappc3 = "SELECT * FROM  crm_tarea_cv WHERE crm_tarea_id = $x_carta_3_id_PP_U ";
	$rsFechappc3 = phpmkr_query($sqlFechappc3,$conn)or die ("Error al seleccionar la fechappc1".phpmkr_error()."sql:".$sqlFechappc3);
	$rowfechappc3 = phpmkr_fetch_array($rsFechappc3);
	$x_fecha_promesa_pago_c3_pp_u = $rowfechapp3["promesa_pago"];
	
	
	
	#########################################################
	#############     CICLO POR DIAS    #####################
	#########################################################
	$sqlFechaCarata3 = "SELECT fecha_entrega FROM  crm_tarea_cv WHERE crm_tarea_id 	 = $x_carta_3_id ";
	$rsFechaCarta3 = phpmkr_query($sqlFechaCarata3,$conn) or die ("Error al seleccionar la fecha de entrega de la carata". phpmkr_error()."sql :".$sqlFechaCarata3);
	$rowFechaCarta3 = phpmkr_fetch_array($rsFechaCarta3);
	$x_fecha_imprime_carat3 = $rowFechaCarta3["fecha_entrega"];
	
	
		
	$x_dias_transcurridos_c3  = datediff('d', $x_fecha_promesa_pago_c3_pp_u, $currdate, false);
	$x_cambia_cliclo_c3 = 0;
	// verificar la forma  de pago del credito
	
	echo "<br>dias transcurridos desde la impresion de la carat 3".$x_dias_transcurridos_c3 ."<br>";
	
	// fomas de pago
	// 1 = semanal
	// 2 = catorcenal
	// 3 = mensual
	// 4 = quincenal
	
	if($x_vencimeintos_pendientes_de_pago == 0){
	switch($x_forma_pago_id)
	{
		case 1:
		if($x_dias_transcurridos_c3 > 20){
			$x_cambia_cliclo_c3 = 1;
			}
		break;
		case 2:
		if($x_dias_transcurridos_c3 > 28){
			$x_cambia_cliclo_c3 = 1;
			}
		break;
		case 3:
		if($x_dias_transcurridos_c3 > 34){
			$x_cambia_cliclo_c3 = 1;
			}
			break;
		case 4;
		if($x_dias_transcurridos_c3 > 30){
			$x_cambia_cliclo_c3 = 1;
			}		
		
		
		
		}
	}else{
		$x_cambia_cliclo_c3 = 0;		
		}
		
		echo "cambia ciclo 3".$x_cambia_cliclo_c3."<br>";
	
	if ($x_tarea_ppc3 > 0){
	
	$sqlFechappc3 = "SELECT * FROM  crm_tarea_cv WHERE crm_tarea_id = $x_tarea_ppc3 ";
	$rsFechappc3 = phpmkr_query($sqlFechappc3,$conn)or die ("Error al seleccionar la fechappc1".phpmkr_error()."sql:".$sqlFechappc3);
	$rowfechappc3 = phpmkr_fetch_array($rsFechappc3);
	$x_fecha_promesa_pago_c3 = $rowfechapp3["promesa_pago"];
	
	}
	
		if(!empty($x_tarea_ppc3)){
	$sqlFechappc3 = "SELECT * FROM  crm_tarea_cv WHERE crm_tarea_id = $x_tarea_ppc3 ";
	$rsFechappc3 = phpmkr_query($sqlFechappc3,$conn)or die ("Error al seleccionar la fechappc1a".phpmkr_error()."sql:".$sqlFechappc3);
	$rowfechappc3 = phpmkr_fetch_array($rsFechappc3);
	$x_fecha_promesa_pago_c3 = $rowfechapp3["promesa_pago"];
	

	
	}
	
	$x_pago_registrado_ppc3 = 0;
	// tenemos la fecha de la tarea y tenemos la fecha de la promesa de pago..
	// buscamos si relizo algun pago desde la fecha de la atrea hasta la fecha e la promesa de pago.
	if(!empty($x_fecha_registro_tarea_3)){
	$sqlBuscapagoPPC3 = "SELECT COUNT(*) AS pago_registrado FROM recibo JOIN recibo_vencimiento ON recibo_vencimiento.recibo_id = recibo.recibo_id";
	$sqlBuscapagoPPC3 .= " JOIN vencimiento ON vencimiento.vencimiento_id = recibo_vencimiento.vencimiento_id ";
	$sqlBuscapagoPPC3 .= " WHERE vencimiento.credito_id = $x_credito_id ";
	$sqlBuscapagoPPC3 .= " AND recibo.fecha_pago >= \"$x_fecha_registro_tarea_3\" "; 
	$rsbuscaPPC3 = phpmkr_query($sqlBuscapagoPPC3,$conn) or die("error al buscar el pago de ppc3".phpmkr_error()."sql:".$sqlBuscapagoPPC3);
	$rowbuscapagoPPC3 = phpmkr_fetch_array($rsbuscaPPC3);
		}
	$x_pago_registrado_ppc3 = $rowbuscapagoPPC3["pago_registrado"]+0;
	$x_ciclo_carta_3 = 0;
	echo "PAGO REG".$x_pago_registrado_ppc3."<BR>";
	echo "SQL:".$sqlBuscapagoPPC3."<br>";
		}
		
		
		
	if($x_cambia_cliclo_c3 == 0){
	$x_cumple_promesa_pago3 = 0;
	if ($x_pago_registrado_ppc3 > 0){
		$x_cumple_promesa_pago3 = 1;
		}
	
	if($x_forma_pago_id == 1){
			// semanal
			if($x_cumple_promesa_pago3 == 1){
				// si cumplio la promesa de pago
				if($x_numero_de_vencimientos_vencidos >= 8){
					$x_ciclo_carta_3 = 0;
					}else{
						$x_ciclo_carta_3 = 1;
						// se cicla
						}
				
				}else{
					// no cumplio la promesa de pago
					if($x_numero_de_vencimientos_vencidos <= 6){
						$x_ciclo_carta_3 = 1; // no pago pero no debe mas de 6 vencimiento. lo ciclamos en esta etapa
						}else{
							$x_ciclo_carta_3 = 0; // lo mandamos a la siguiente etapa
							}				
					}
			
			}else if(($x_forma_pago_id == 2) || ($x_forma_pago_id == 4)){
				// catorcenal ó quincenal
					if($x_cumple_promesa_pago3 == 1){
					// si cumplio la promesa de pago
					if($x_numero_de_vencimientos_vencidos >= 6){
						$x_ciclo_carta_3 = 0;
						}else{
							$x_ciclo_carta_3 = 1;
							// se cicla
							}					
					}else{
						// no cumplio la promesa de pago
						if($x_numero_de_vencimientos_vencidos <= 5){
							$x_ciclo_carta_3 = 1; // no pago pero no debe mas de 5 5vencimiento. lo ciclamos en esta etapa
							}else{
								$x_ciclo_carta_3 = 0; // lo mandamos a la siguiente etapa
								}				
						}
				
				}else if($x_forma_pago_id == 3){
					//MENSUAL
					if($x_cumple_promesa_pago3 == 1){
					// si cumplio la promesa de pago
					if($x_numero_de_vencimientos_vencidos >= 5){
						$x_ciclo_carta_3 = 0;
						}else{
							$x_ciclo_carta_3 = 1;
							// se cicla
							}					
					}else{
						// no cumplio la promesa de pago
						if($x_numero_de_vencimientos_vencidos < 5){
							$x_ciclo_carta_3 = 1; // no pago pero no debe mas de 5 5vencimiento. lo ciclamos en esta etapa
							}else{
								$x_ciclo_carta_3 = 0; // lo mandamos a la siguiente etapa
								}				
						}
					
					}
	}else{ $x_ciclo_carta_3 = 0;}
					
					
					
	echo "CICLO CARATA 3".$x_ciclo_carta_3."<BR>";
	
	
	
	
	
	$sqlCartaD = "SELECT COUNT(*) AS carta_D FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND  crm_tarea_tipo_id = 12 AND orden= 20";
	$rsCartaD = phpmkr_query($sqlCartaD,$conn)or die ("Error al seleccionar la carta 3".phpmkr_error()."sql:".$sqlCartaD);
	$rowCarataD = phpmkr_fetch_array($rsCartaD);
	$x_carta_D = $rowCarataD["carta_D"] +0;
	
	if($x_carta_D > 0){
       //seleccionasmos la PP de la demanda
	$sqlPPCD = "SELECT * FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id  AND crm_tarea_tipo_id = 5 AND orden = 24 ";
	$rsppcD = phpmkr_query($sqlPPCD,$conn)or die ("Error al seleccioanr la pp c2".phpmkr_error()."sql:".$sqlPPCD);
	$rowppcD = phpmkr_fetch_array($rsppcD);
	$x_fecha_registro_tarea_D = $rowppcD["fecha_registro"];
	$x_tarea_ppcD =$rowppcD["crm_tarea_id"];
	$x_caso_ppcD = $rowppcD["crm_caso_id"];
	   
	   
		}
	
	if(!empty($x_fecha_registro_tarea_D )){
	$sqlBuscapagoPPC3 = "SELECT COUNT(*) AS pago_registrado FROM recibo JOIN recibo_vencimiento ON recibo_vencimiento.recibo_id = recibo.recibo_id";
	$sqlBuscapagoPPC3 .= " JOIN vencimiento ON vencimiento.vencimiento_id = recibo_vencimiento.vencimiento_id ";
	$sqlBuscapagoPPC3 .= " WHERE vencimiento.credito_id = $x_credito_id ";
	$sqlBuscapagoPPC3 .= " AND recibo.fecha_pago >= \"$x_fecha_registro_tarea_D\" "; 
	$rsbuscaPPC3 = phpmkr_query($sqlBuscapagoPPC3,$conn) or die("error al buscar el pago de ppc3".phpmkr_error()."sql:".$sqlBuscapagoPPC3);
	$rowbuscapagoPPC3 = phpmkr_fetch_array($rsbuscaPPC3);
	}
	$x_pago_registrado_ppcd = $rowbuscapagoPPC3["pago_registrado"]+0;
	echo "carta D..". $x_carta_D."<br>";
	echo "pago registrado cc".$sqlBuscapagoPPC3."<br>";
	echo "pago registrado cc".$x_pago_registrado_ppc3."<br>";
	if($x_pago_registrado_ppcd == 0){
		$x_ciclo_carta_D = 0;
		}else{
			$x_ciclo_carta_D = 1;
			}
	
	$sqlSolId = "SELECT solicitud_id FROM credito WHERE credito_id = $x_credito_id";
	$rsSolId = phpmkr_query($sqlSolId,$conn) or die ("Error al seleccionar el id de la solicitud del credito".phpmkr_error()."sql:");
	$rowSolId = phpmkr_fetch_array($rsSolId);
	$x_solicitud_id_c = $rowSolId["solicitud_id"];
		
	// seleccionamos el promotor
	$sqlPromotor = "SELECT promotor_id FROM solicitud WHERE solicitud_id = $x_solicitud_id_c";
	$rsPromotor = phpmkr_query($sqlPromotor,$conn) or die ("Error al seleccionar el promotor del credito".phpmkr_error()."sql :".$sqlPromotor);
	$rowPromotor = phpmkr_fetch_array($rsPromotor);
	$x_promotor_id_c = $rowPromotor["promotor_id"];
	
	
	//SELECCIONAMOS LOS DATOS  DEL CREDITO, PARA SABER SI YA EL CAMPO DE PENALIZACION EN SUS ESTRUCTRUCATURA
	$sqlDatosCredito = "SELECT penalizacion,forma_pago_id FROM credito WHERE credito_id = $x_credito_id	";
	$rsDatosCredito = phpmkr_query($sqlDatosCredito,$conn)or die("error al seleccionar los datosd el credito penalizacion campo nuevo".phpmkr_error()."sql:".$sqlDatosCredito);
	$rowDatosCredito = phpmkr_fetch_array($rsDatosCredito);
	$x_penalizacion_a = $rowDatosCredito["penalizacion"];
	$x_forma_de_pago = $rowDatosCredito["forma_pago_id"];
	
	// si el campo penalizacion-a esta lleno significa que el tipod e credito es de ls nuevos; si no esta lleno el credito es de los viejitos y la penaliacion se gestinoara por MONTO no por registro de penalizacion
	
//	; tomando en cuenta la tabla con la que se genera las penalizacones;
$x_comision_generada = 0;
	if($x_cambia_cliclo_c1 == 0){
	if($x_penalizacion_a > 0){
		// si es mayo de 0 solo se busca si la penalizcion ya existe
		$sqlPenalizacion = "SELECT COUNT(*) AS comision_generada FROM vencimiento WHERE credito_id = $x_credito_id and vencimiento_num	= 3001";
		$rsPenalizacion = phpmkr_query($sqlPenalizacion,$conn) or die ("Error al seelcccionar la penalizacion en el credito".phpmkr_error()."SQL;".$sqlPenalizacion);
		$rowPenalizacion = phpmkr_fetch_array($rsPenalizacion);
		$x_comision_generada = $rowPenalizacion["comision_generada"];
		
		//else es los creditos viejos y no tiene ni tendra nunca una penalizacion generada, se hace el calculo por monto
		}else{
		// se hace el calculo de la comiosn	
		$sqlPenalizacion = "SELECT total_venc, interes_moratorio,iva_mor FROM vencimiento WHERE credito_id = $x_credito_id and vencimiento_num	= 1";
		$rsPenalizacion = phpmkr_query($sqlPenalizacion,$conn) or die ("erro al seleccionar la comison en casaos anteriores".phpmkr_error()."sql:".$sqlPenalizacion);
		$rowPenalizacion = phpmkr_fetch_array($rsPenalizacion);
		$x_monto_vencimeinto = $rowPenalizacion["total_venc"];
		$x_monto_moratorios = $rowPenalizacion["interes_moratorio"];
		$x_monto_iva_mora = $rowPenalizacion["iva_mor"];		
		$x_monto_pago = $x_monto_vencimeinto -($x_monto_moratorios  + $x_monto_iva_mora);
		
		
		#buscamos el monto vencido
		$sqlBuscaMontoVenc = " SELECT SUM(total_venc) AS monto_vencido FROM vencimiento WHERE credito_id = $x_credito_id and  vencimiento_status_id in (3,6)";
		$rsBuscaMontoVenc = phpmkr_query($sqlBuscaMontoVenc,$conn) or die ("Error al seleccionar el monto del vencimiento".phpmkr_error()."sql:".$sqlBuscaMontoVenc);
		$rowBuscaMontoVenc = phpmkr_fetch_array($rsBuscaMontoVenc);
		$x_monto_VVencido = $rowBuscaMontoVenc["monto_vencido"];
		 $x_monto_garantia_liquida = 0;
		
		
		if($x_forma_de_pago == 1){
			// la forma de pago es  semanal
			$x_total_vencido_para_generar =  $x_monto_pago * 3;
			}else{
				// la forma de pago es mensual, quincenal, o catorcenal
				$x_total_vencido_para_generar =  $x_monto_pago * 2;
				}
			
			
			if($x_monto_VVencido > $x_total_vencido_para_generar ){
				// el importe vencido ya generaria comison de cobranza si estuveira en le nuevo tipo de credito asi que que se toma como si ya existiera la comsion de cobranza
				
				$x_comision_generada = 1;
				}
			}
		//SELECCIONAMOS EL MOSNTO DE LA COMISION DE COBRANZA
		//$x_comision_generada = 0;
	}else{
		$x_comision_generada = 1;
		}
		
		echo "comision generada----".$x_comision_generada."----<br>";
		echo "cliclo carta 1---".$x_ciclo_carta_1." ---<br>";
		
		echo "carata d".$x_carta_D."<br>";
		echo "ciclo crata d".$x_ciclo_carta_D."<br>";
			
	
	################################################################
	################################################################
	################################################################
	
	//$x_comision_generada = 1;
	//$x_ciclo_carta_2 = 1;
	//$x_ciclo_carta_3 = 1;
	
	
	################################################################	
	################################################################
	################################################################	
	
	
	
	if($x_carta_1 == 0){
	###########################################################################################################################################
	###########################################################################################################################################
	###################################################               CARTA 1              ####################################################
	###########################################################################################################################################
	###########################################################################################################################################
	//PRMIER CASO CARATA 1	
	
	// antes de insertar la tarea de carta 1 verificamos que no existe dicha tarea.
		
		#echo "entra a CARTA 1<br>";
		
		if($x_promotor_id_c > 0){
			// buscamos a que sucursal pertence el promotor
			$sqlSucursal = "SELECT sucursal_id FROM promotor WHERE promotor_id = $x_promotor_id_c";
			$rsSucursal = phpmkr_query($sqlSucursal,$conn) or die ("Error al seleccionar la sucursal". phpmkr_error()."Sql:".$sqlSucursal); 
			$rowSucuersal = phpmkr_fetch_array($rsSucursal);
			$x_sucursal_id_c = $rowSucuersal["sucursal_id"];
				
			
			if($x_sucursal_id_c > 0){
				// si ya tenbemos la sucursal, buscamos el representante de essa sucursal
				$sqlResponsable = "SELECT usuario_id FROM responsable_sucursal WHERE sucursal_id = $x_sucursal_id_c ";
				$rsResponsable = phpmkr_query($sqlResponsable,$conn) or die ("error al seleccionar el usuario del responsable de suscursal".phpmkr_error()."sql:".$sqlResponsable);
				$rowResponsable = phpmkr_fetch_array($rsResponsable);
				$x_responsable_susursal_usuario_id = $rowResponsable["usuario_id"];	
						
				
				}
			} 
			
		
		if($x_numero_de_vencimientos_vencidos > 1){
			// cambios de dueño el caso y la tarea.
			if($x_responsable_susursal_usuario_id > 0){
			$sqlCasoResponsable = "SELECT count(*) as caso_abierto
						FROM 
						crm_caso
						WHERE 
						crm_caso.crm_caso_tipo_id = 3
						AND crm_caso.crm_caso_status_id = 1
						AND crm_caso.credito_id = $x_credito_id
						AND crm_caso.responsable = $x_responsable_susursal_usuario_id";
						
			$rsCasoResponsable = phpmkr_query($sqlCasoResponsable,$conn) or die("Error al seleccionar los casos del responsabel de sucursal".phpmkr_error()."sql:".$sqlCasoResponsable);			
			$rowCasoResponsable = phpmkr_fetch_array($rsCasoResponsable);
				
			$x_caso_responsabel_id = $rowCasoResponsable["crm_caso_id"];
			$x_caso_id_e = $rowCasoResponsable["caso_id"];
		}
			
			//   1.- cerramos las tareas, cerramos el caso, porque se agragra una nueva tarea pero ahora sera para el promotor
			
			//   2.- Abrimos una nueva tarea caso para el asesor de credito.
			if(!empty($x_caso_responsabel_id)){
			$sqlUpdateTareas = "UPDATE crm_tarea SET crm_tarea_status_id = 4 WHERE crm_tarea.crm_caso_id = $x_caso_responsabel_id "; // cerrramos la tarea
			$rsUpdateTareas = phpmkr_query($sqlUpdateTareas,$conn) or die ("Error al cerrar las tareas del responsable de sucrsal que pasan al asesor de credito".phpmkr_error()."sql:".$sqlUpdateTareas);
			"ACTIALIZA USUARIO TAREA ".$sqlUpdateTareas."<BR>";
			}
			
			//$sqlUpdateCasos = "UPDATE crm_caso SET crm_caso_status_id = 4 WHERE crm_caso_id = $x_caso_responsabel_id " ;// cerrado por cambio de ETAPA
			//$rsUpdateCasos = phpmkr_query($sqlUpdateCasos,$conn) or die ("Error al actualizar los casos del responsable de suscursal que pasan al asesor de credito".phpmkr_error()."sql :".$sqlUpdateCasos);
			// no se cierra el caso para seguir usandolo con las demas fases.
			
			// selelccionamos el usuario del promotor
			$sSqlWrk = "SELECT usuario_id
						FROM 
						promotor
						WHERE 
						promotor.promotor_id = $x_promotor_id_c ";		
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_promotor_usuario_id = $datawrk["usuario_id"];
			@phpmkr_free_result($rswrk);
			
			
			
			
			
			// seleccionamos los datos para la nueva tarea
				
				$sSqlWrk = "SELECT *
							FROM 
							crm_playlist
							WHERE 
							crm_playlist.crm_caso_tipo_id = 3
							AND crm_playlist.orden = 4 "; // orden 4 CARTA  1		
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_crm_playlist_id = $datawrk["crm_playlist_id"];
				$x_prioridad_id = $datawrk["prioridad_id"];	
				$x_asunto = $datawrk["asunto"];	
				$x_descripcion = $datawrk["descripcion"];		
				$x_tarea_tipo_id = $datawrk["tarea_fuente"];		
				$x_orden = $datawrk["orden"];	
				$x_dias_espera = $datawrk["dias_espera"];		
				@phpmkr_free_result($rswrk);
				
				
				
				//Fecha Vencimiento
				$temptime = strtotime($currdate);	
				$temptime = DateAdd('w',$x_dias_espera,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if($x_dia == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$temptime = strtotime($fecha_venc);
	
	
	
				$x_origen = 1;  // el origen se podria cambiar a responsable de sucursal.
				$x_bitacora = "Cartera Vencida  SE PASA A ETAPA 2  CARTA 1- (".FormatDateTime($currdate,7)." - $currtime)";
			
				$x_bitacora .= "\n";
				$x_bitacora .= "$x_asunto - $x_descripcion ";	


				$sSqlWrk = "
		SELECT cliente.cliente_id
		FROM 
			cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id join solicitud on solicitud.solicitud_id = solicitud_cliente.solicitud_id join credito on credito.solicitud_id = solicitud.solicitud_id
		WHERE 
			credito.credito_id = $x_credito_id
		LIMIT 1
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_cliente_id = $datawrk["cliente_id"];
		
		@phpmkr_free_result($rswrk);


		
		
		// seleccionamos el usuario del promotor 7// CARAT 1 EL RESPONSABLE DE LA TAREA ES EL ASESOR DEL CREDITO
		
				
		$sSqlWrk = "
		SELECT usuario_id
		FROM 
			promotor
		WHERE 
			promotor.promotor_id = $x_promotor_id_c ";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		#echo  $sqlPromotor. $sSqlWrk."<br>";
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_usuario_id = $datawrk["usuario_id"];
		@phpmkr_free_result($rswrk);
		
	
			// se debe verificar antes de ingresar la tarea que no exista ninguna tarea para el caso de este tipo
			$sqlBuscaTarea =  "SELECT COUNT(*) AS tareas_exist from  crm_tarea where crm_caso_id = $x_crm_caso_id AND crm_tarea_tipo_id = $x_tarea_tipo_id ";
			$sqlBuscaTarea .= " AND crm_tarea_status_id IN (1,2) AND destino = $x_usuario_id";	
			$rsBuscaTarea = phpmkr_query($sqlBuscaTarea,$conn) or die ("Erro al insertar en tarea 1".phpmkr_error()."sql:".$sqlBuscaTarea);
			$rowBuscaTarea = phpmkr_fetch_array($rsBuscaTarea);
			$x_tareas_abiertas = $rowBuscaTarea["tareas_exist"];
			
			#echo "<br><br> TREAS ABIERTAS ".$x_tareas_abiertas."<BR><BR>".$sqlBuscaTarea."<BR><BR>";
			if($x_tareas_abiertas == 0){
			$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 7, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
			// la tarea la ejecuta el asesor de crédito
			$rs = phpmkr_query($sSql,$conn) or die("Error al seleccionar insertar en tarea para promotor".phpmkr_error()."sql:".$sSql);
		echo "INSERTAMOS TAREA CARTA 1 desde el segundo cliclo<BR>".$sSql."<BR>";
			//$x_result = phpmkr_query($sSql, $conn);
			$x_tarea_id = mysql_insert_id();
	
			$sSqlWrk = "
			SELECT 
				comentario_int
			FROM 
				credito_comment
			WHERE 
				credito_id = ".$x_credito_id."
			LIMIT 1
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_comment_ant = $datawrk["comentario_int"];
			@phpmkr_free_result($rswrk);
	
	
			if(empty($x_comment_ant)){
				$sSql = "insert into credito_comment values(0, $x_credito_id, '$x_bitacora', NULL)";
			}else{
				$x_bitacora = $x_comment_ant . "\n\n------------------------------\n" . $x_bitacora;
				$sSql = "UPDATE credito_comment set comentario_int = '$x_bitacora' where credito_id = $x_credito_id";
			}
	
			phpmkr_query($sSql, $conn);
			
			
		########################################################################################################################################################
		############################################################ TAREAS DIARIAS PARA PROMOTORES	############################################################
		########################################################################################################################################################
		##credamos las lista de los promotores con la tareas diarias.		
		## seleccionaos la soza de la solcitud		
		$sqlZonaId = "SELECT dia FROM  zona JOIN solicitud ON solicitud.zona_id = zona.zona_id WHERE solicitud.solicitud_id = $x_solicitud_id_c";
		$rsZonaId = phpmkr_query($sqlZonaId,$conn) or die ("Error al seleccionar el dia de la zona".phpmkr_error()."sql:".$sqlZonaId);
		$rowZonaId = phpmkr_fetch_array($rsZonaId);
		$x_dia_zona = $rowZonaId["dia"];
		
		$x_fecha_tarea_f = $currdate;
		$x_dia_zona_f = $x_dia_zona;
		$x_promotor_id_f = $x_promotor_id_c ;
		$x_caso_id_f = $x_crm_caso_id;
		$x_tarea_id_f = $x_tarea_id;
		
		
		#tenemos la fecha de hoy  --> hoy es miercoles 30 de enero
		# se debe buscar la fecha de la zona --> si la zona fuera 5.. la fecha se deberia cambiar a viernes 1 de febrero
		# o si la zona fuera  2 como las tareas de la zona 2 ya fueron asignadas.. se debe buscar la fecha de la zona dos de la sig semana
		
		$sqlDiaSemana = "SELECT DAYOFWEEK('$x_fecha_tarea_f') AS  dia";
		$rsDiaSemana = phpmkr_query($sqlDiaSemana,$conn) or die ("Error al seleccionar el dia de la semana".phpmkr_error()."sql:".$sqlDiaSemana);
		$rowDiaSemana = phpmkr_fetch_array($rsDiaSemana);
		$x_dia_de_semana_en_fecha = $rowDiaSemana["dia"];
		#echo "fecha day of week".$x_dia_de_semana_en_fecha ."<br>";
		
		if($x_dia_de_semana_en_fecha != $x_dia_zona_f ){
			// el dia de la zona y el dia de la fecha no es el mismo se debe incrementar la fecha hasta llegar al mismo dia
			
			#echo "LOS DIAS SON DIFERENTES<BR>";
		//	$x_dia_de_semana_en_fecha = 1;
		//	$x_dia_zona_f= 5;
		//	$x_fecha_tarea_f = "2013-01-29";
			if($x_dia_de_semana_en_fecha < $x_dia_zona_f){
				// la fecha es mayor dia_en _fecha = 2; dia_zona = 5
				$x_dias_faltantes = $x_dia_zona_f - $x_dia_de_semana_en_fecha;	
				//hacemos el incremento en la fecha		
				//Fecha de tarea para la lista
				
				#echo "fecha dia------".$x_fecha_tarea_f."<br>";
				$temptime = strtotime($x_fecha_tarea_f);	
				$temptime = DateAdd('w',$x_dias_faltantes,$temptime);
				$fecha_nueva = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				$x_fecha_tarea_f = $fecha_nueva;	
				#echo "nueva fecha...ESTA semana<br> dias faltantes".$x_dias_faltantes."<br>";			
				}else{
					// el dia dela fecha es mayor al dia de la zona...las tareas asignadas para esa zona ya pasaron; porque ya paso el dia de esa zona
					// se debe asigna la tarea para la semana sig el la fecha de la zona.
					$x_dias_faltantes = (7- $x_dia_de_semana_en_fecha)+ $x_dia_zona_f;
					//hacemos el incremento en la fecha		
					//Fecha de tarea para la lista
					$temptime = strtotime($x_fecha_tarea_f);	
					$temptime = DateAdd('w',$x_dias_faltantes,$temptime);
					$fecha_nueva = strftime('%Y-%m-%d',$temptime);			
					$x_dia = strftime('%A',$temptime);
					$x_fecha_tarea_f = $fecha_nueva;	
					#echo "nueva fecha...sigueinte semana<br> dias faltantes".$x_dias_faltantes."<br>";
					#echo "DIA DE LA SEMANA EN FECHA".$x_dia_de_semana_en_fecha."<br>";
					#echo "dia zona".$x_dia_zona_f;					
					}
			}
		
		$x_dias_agregados = calculaSemanas($conn, $x_fecha_tarea_f, $x_dia_zona_f, $x_promotor_id_f, $x_caso_id_f, $x_tarea_id_f);
		#echo "dias agragados".$x_dias_agregados." ";
		
		// se gragan los dias que faltan si es que es mayor de 0
		if($x_dias_agregados > 0){
					$temptime = strtotime($x_fecha_tarea_f);	
					$temptime = DateAdd('w',$x_dias_agregados,$temptime);
					$fecha_nueva = strftime('%Y-%m-%d',$temptime);			
					$x_fecha_tarea_f = $fecha_nueva;
				// se hizo el incremento en la fecha				
				//se actualiza la tarea con la fecha nueva
				$x_fecha_ejecuta_act = $x_fecha_tarea_f;
				
				$temptime = strtotime($x_fecha_ejecuta_act);	
				$temptime = DateAdd('w',$x_dias_espera,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if($x_dia == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$x_fecha_ejecuta_act = $fecha_venc;
				
				$sqlUpdateFecha = "UPDATE crm_tarea SET  fecha_ejecuta = \"$x_fecha_ejecuta_act\" WHERE crm_tarea_id = $x_tarea_id_f ";
				$rsUpdateFecha = phpmkr_query($sqlUpdateFecha,$conn) or die ("Error al actualiza la fecha de latarea despues del calculo semana".phpmkr_error()."sql;".$sqlUpdateFecha);
				echo "UPDATAE TREA".$sqlUpdateFecha."<br>";
				
		}else{
			$x_fecha_ejecuta_act  = $x_fecha_tarea_f;
			$temptime = strtotime($x_fecha_ejecuta_act);	
				$temptime = DateAdd('w',$x_dias_espera,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if($x_dia == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$x_fecha_ejecuta_act = $fecha_venc;
				
				$sqlUpdateFecha = "UPDATE crm_tarea SET  fecha_ejecuta = \"$x_fecha_ejecuta_act\" WHERE crm_tarea_id = $x_tarea_id_f ";
				$rsUpdateFecha = phpmkr_query($sqlUpdateFecha,$conn) or die ("Error al actualiza la fecha de latarea despues del calculo semana".phpmkr_error()."sql;".$sqlUpdateFecha);
			
			
			
			}
		#echo "FECHA EJECUTA".$x_fecha_ejecuta_act;
		#####################################################################################################################
		##################################### TAREAS DIARIAS PROMOTOR #######################################################
		#####################################################################################################################
		
		// primero verifamos que la tarea aun no este en la lista, es decir, que se trate de la primera vez que entra al cliclo para este credito.
		$sqlBuscaatreaAsignada = "SELECT COUNT(*) AS atreas_asignadas FROM `tarea_diaria_promotor` WHERE fecha_ingreso =  \"$currdate\" AND promotor_id = $x_promotor_id_f ";
		$sqlBuscaatreaAsignada .= " AND `caso_id` = $x_caso_id_f";
		$rsBuscatareaAsignada = phpmkr_query($sqlBuscaatreaAsignada,$conn) or die("Erro al buscar atrea".phpmkr_error()."sql:".$sqlBuscaatreaAsignada);
		#echo "BUSCA TAREAS".$sqlBuscaatreaAsignada."<BR>";
		$rowBuscaTareaAsignada = phpmkr_fetch_array($rsBuscatareaAsignada);
		$x_tareas_asignadas_del_caso = $rowBuscaTareaAsignada["atreas_asignadas"];
		#echo "TAREAS ASIGNADAS ".$x_tareas_asignadas_del_caso."<br>";
		//se inserta la tarea en la lista de las actividades diarias del promotor
		if ($x_tareas_asignadas_del_caso < 1){
		$sqlInsertListaTarea = "INSERT INTO `tarea_diaria_promotor`";
		$sqlInsertListaTarea .= " (`tarea_diaria_promotor_id`, `promotor_id`, `zona_id`, `dia_semana`, `fecha_ingreso`, `fecha_lista`, `caso_id`, ";
		$sqlInsertListaTarea .= " `tarea_id`, `reingreso`, `fase`, `status_tarea`, `credito_id`) ";
		$sqlInsertListaTarea .= "VALUES (NULL, $x_promotor_id_f, $x_dia_zona_f , $x_dia_zona_f, \"$currdate\",\"$x_fecha_tarea_f\", $x_caso_id_f, $x_tarea_id_f, '0', '2', '1', $x_credito_id);";
		$rsInsertListaTarea = phpmkr_query($sqlInsertListaTarea,$conn)or die("Error al insertar en lista diaria tareas".phpmkr_error()."sql:".$sqlInsertListaTarea);	 
		}
		
		
			}// tareas registradas = 0
			}// vencimeitos vencidos mayor de UNO
		
	}//CARTA 1
	
	
	
	if(($x_carta_1 > 0) &&($x_comision_generada < 1)){
		// ya se le genero la carta 1
		// ya esta en fase 3
		// ya tiene una o mas promesas de pago...
		// SE genera una promesa de pagao al promotor de credito (angelica, monica, jose) 
		// carta 1 ya existe, pero debemos de verificar que la tarea esta vencida o esta cerrada
		// si ya se vencio la tarea o ya se netrego la carta entonces si ya podemos programar la sigueinte tarea
		// de lo contrario si el credito tuvuera mas de dos vencidos.. generaria la carata y tambien generaria la PP 
		
		#verificamos que no tenga ninguna PP con status de pendiente;
		echo "entra  pp carta 1 <br>";
		
		$x_carta_1PP = 0;
		$sqlCarta1PP = "SELECT COUNT(*) AS carta_1_PP FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND crm_tarea_tipo_id = 5 AND orden= 5 AND crm_tarea_status_id in (1,2,3)";
		$rsCarta1PP = phpmkr_query($sqlCarta1PP,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCarta1PP);
		$rowCarata1PP = phpmkr_fetch_array($rsCarta1PP);
		$x_carta_1PP = $rowCarata1PP["carta_1_PP"] +0;
		echo "busca PP pendiente".$sqlCarta1PP ."<br><br>";
		
		// contamos si ya tiene una PP pero esta vencida; si es asi... buscamos su promesa de pago 
		$x_carta_1PP_vencida = 0;
		$sqlCarta1PP_vencida = "SELECT COUNT(*) AS carta_1_PP_v FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND crm_tarea_tipo_id = 5 AND orden= 5 AND crm_tarea_status_id in (2,3)";
		$rsCarta1PP_vencida = phpmkr_query($sqlCarta1PP_vencida,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCarta1PP_vencida);
		$rowCarata1PP_vencida = phpmkr_fetch_array($rsCarta1PP_vencida);
		$x_carta_1PP_vencida = $rowCarata1PP_vencida["carta_1_PP_v"] +0;
		echo "busca PP vencidas,o cerradas".$sqlCarta1PP ."<br><br>";
		#echo "promesa de pago".$sqlCarta1PP."<br>"; 
		#echo "promesas de pago para las cartas = ". $x_carta_1PP."<br>";
		if(($x_carta_1PP < 1)){
		echo "no hay promesa de pago pendiente<, ni vencida entro y genero la PPC1 <br>";	
			
		#SELECCIONAMOS LOS DATOS DE  LA TAREA
		
		$sSqlWrk = "
		SELECT *
		FROM 
			crm_playlist
		WHERE 
			crm_playlist.crm_caso_tipo_id = 3
			AND crm_playlist.orden = 5 "; // PP carat 1
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_crm_playlist_id = $datawrk["crm_playlist_id"];
		$x_prioridad_id = $datawrk["prioridad_id"];	
		$x_asunto = $datawrk["asunto"];	
		$x_descripcion = $datawrk["descripcion"];		
		$x_tarea_tipo_id = $datawrk["tarea_fuente"];		
		$x_orden = $datawrk["orden"];	
		$x_dias_espera = $datawrk["dias_espera"];		
		@phpmkr_free_result($rswrk);
	
	
		//Fecha Vencimiento
		$temptime = strtotime($currdate);	
		$temptime = DateAdd('w',$x_dias_espera,$temptime);
		$fecha_venc = strftime('%Y-%m-%d',$temptime);			
		$x_dia = strftime('%A',$temptime);
		if($x_dia == "SUNDAY"){
			$temptime = strtotime($fecha_venc);
			$temptime = DateAdd('w',1,$temptime);
			$fecha_venc = strftime('%Y-%m-%d',$temptime);
		}
		$temptime = strtotime($fecha_venc);
	
	
	
		$x_origen = 1;
		$x_bitacora = "Cartera Vencida - (".FormatDateTime($currdate,7)." - $currtime)";
	
		$x_bitacora .= "\n";
		$x_bitacora .= "$x_asunto - $x_descripcion ";	


		$sSqlWrk = "
		SELECT cliente.cliente_id
		FROM 
			cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id join solicitud on solicitud.solicitud_id = solicitud_cliente.solicitud_id join credito on credito.solicitud_id = solicitud.solicitud_id
		WHERE 
			credito.credito_id = $x_credito_id
		LIMIT 1
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_cliente_id = $datawrk["cliente_id"];
		@phpmkr_free_result($rswrk);
		
		
		
		
		
		// seleccionamos el promotor
		$sqlPromotor = "SELECT promotor_id FROM solicitud WHERE solicitud_id =   $x_solicitud_id_c";
		$rsPromotor = phpmkr_query($sqlPromotor,$conn) or die ("Error al seleccionar el promotor del credito".phpmkr_error()."sql :".$sqlPromotor);
		$rowPromotor = phpmkr_fetch_array($rsPromotor);
		$x_promotor_id_c = $rowPromotor["promotor_id"];
	#	 echo "sql promotor".$sqlPromotor."<br>";
		// selelccionamos el usuario del promotor
			$sSqlWrk = "SELECT usuario_id
						FROM 
						promotor
						WHERE 
						promotor.promotor_id = $x_promotor_id_c ";		
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_promotor_usuario_id = $datawrk["usuario_id"];
			$x_usuario_id = $x_promotor_usuario_id;
			@phpmkr_free_result($rswrk);
		
			
			



		if(($x_crm_caso_id +0 > 0)){
			#echo "crm_caso_id > 0 <br>";

			$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 2, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
		
			$x_result = phpmkr_query($sSql, $conn) or die ("error al inserta PP carat 1".phpmkr_error()."sql;".$sSql);
			$x_tarea_id_pp = mysql_insert_id();
			echo "INSERTA PROMESA DE PAGO CARTA 1<BR>".$sSql."<BR>";
			$sSqlWrk = "
			SELECT 
				comentario_int
			FROM 
				credito_comment
			WHERE 
				credito_id = ".$x_credito_id."
			LIMIT 1
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_comment_ant = $datawrk["comentario_int"];
			@phpmkr_free_result($rswrk);
	
	
			if(empty($x_comment_ant)){
				$sSql = "insert into credito_comment values(0, $x_credito_id, '$x_bitacora', NULL)";
			}else{
				$x_bitacora = $x_comment_ant . "\n\n------------------------------\n" . $x_bitacora;
				$sSql = "UPDATE credito_comment set comentario_int = '$x_bitacora' where credito_id = $x_credito_id";
			}
	
			phpmkr_query($sSql, $conn);
		
		
		// buscar el dia que se asignara la tarea al prmotor
		########################################################################################################################################################
		############################################################ TAREAS DIARIAS PARA PROMOTORES	############################################################
		########################################################################################################################################################
		##credamos las lista de los promotores con la tareas diarias.		
		## seleccionaos la soza de la solcitud		
		$sqlZonaId = "SELECT dia FROM  zona JOIN solicitud ON solicitud.zona_id = zona.zona_id WHERE solicitud.solicitud_id = $x_solicitud_id_c";
		$rsZonaId = phpmkr_query($sqlZonaId,$conn) or die ("Error al seleccionar el dia de la zona".phpmkr_error()."sql:".$sqlZonaId);
		$rowZonaId = phpmkr_fetch_array($rsZonaId);
		$x_dia_zona = $rowZonaId["dia"];
		
		$x_fecha_tarea_f = $currdate;
		$x_dia_zona_f = $x_dia_zona;
		$x_promotor_id_f = $x_promotor_id_c ;
		$x_caso_id_f = $x_crm_caso_id;
		$x_tarea_id_f = $x_tarea_id_pp;
	
		
		
		#tenemos la fecha de hoy  --> hoy es miercoles 30 de enero
		# se debe buscar la fecha de la zona --> si la zona fuera 5.. la fecha se deberia cambiar a viernes 1 de febrero
		# o si la zona fuera  2 como las tareas de la zona 2 ya fueron asignadas.. se debe buscar la fecha de la zona dos de la sig semana
		
		$sqlDiaSemana = "SELECT DAYOFWEEK('$x_fecha_tarea_f') AS  dia";
		$rsDiaSemana = phpmkr_query($sqlDiaSemana,$conn) or die ("Error al seleccionar el dia de la semana".phpmkr_error()."sql:".$sqlDiaSemana);
		$rowDiaSemana = phpmkr_fetch_array($rsDiaSemana);
		$x_dia_de_semana_en_fecha = $rowDiaSemana["dia"];
		#echo "fecha day of week".$x_dia_de_semana_en_fecha ."<br>";
		
		if($x_dia_de_semana_en_fecha != $x_dia_zona_f ){
			// el dia de la zona y el dia de la fecha no es el mismo se debe incrementar la fecha hasta llegar al mismo dia
			
			#echo "LOS DIAS SON DIFERENTES<BR>";
		//	$x_dia_de_semana_en_fecha = 1;
		//	$x_dia_zona_f= 5;
		//	$x_fecha_tarea_f = "2013-01-29";
			if($x_dia_de_semana_en_fecha < $x_dia_zona_f){
				// la fecha es mayor dia_en _fecha = 2; dia_zona = 5
				$x_dias_faltantes = $x_dia_zona_f - $x_dia_de_semana_en_fecha;	
				//hacemos el incremento en la fecha		
				//Fecha de tarea para la lista
				
			#	echo "fecha dia------".$x_fecha_tarea_f."<br>";
				$temptime = strtotime($x_fecha_tarea_f);	
				$temptime = DateAdd('w',$x_dias_faltantes,$temptime);
				$fecha_nueva = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				$x_fecha_tarea_f = $fecha_nueva;	
				echo "nueva fecha...ESTA semana<br> dias faltantes".$x_dias_faltantes."<br>";			
				}else{
					// el dia dela fecha es mayor al dia de la zona...las tareas asignadas para esa zona ya pasaron; porque ya paso el dia de esa zona
					// se debe asigna la tarea para la semana sig el la fecha de la zona.
					$x_dias_faltantes = (7- $x_dia_de_semana_en_fecha)+ $x_dia_zona_f;
					//hacemos el incremento en la fecha		
					//Fecha de tarea para la lista
					$temptime = strtotime($x_fecha_tarea_f);	
					$temptime = DateAdd('w',$x_dias_faltantes,$temptime);
					$fecha_nueva = strftime('%Y-%m-%d',$temptime);			
					$x_dia = strftime('%A',$temptime);
					$x_fecha_tarea_f = $fecha_nueva;	
					echo "nueva fecha...sigueinte semana<br> dias faltantes".$x_dias_faltantes."<br>";
			#		echo "DIA DE LA SEMANA EN FECHA".$x_dia_de_semana_en_fecha."<br>";
			#		echo "dia zona".$x_dia_zona_f;					
					}
			}
		
		$x_dias_agregados = calculaSemanas($conn, $x_fecha_tarea_f, $x_dia_zona_f, $x_promotor_id_f, $x_caso_id_f, $x_tarea_id_f);
		echo "dias agragados en PP CARTA 1 ------".$x_dias_agregados." ";
		
		// se gragan los dias que faltan si es que es mayor de 0
		if($x_dias_agregados > 0){
					$temptime = strtotime($x_fecha_tarea_f);	
					$temptime = DateAdd('w',$x_dias_agregados,$temptime);
					$fecha_nueva = strftime('%Y-%m-%d',$temptime);			
					$x_fecha_tarea_f = $fecha_nueva;
				// se hizo el incremento en la fecha				
				//se actualiza la tarea con la fecha nueva
				$x_fecha_ejecuta_act = $x_fecha_tarea_f;
				
				$temptime = strtotime($x_fecha_ejecuta_act);	
				$temptime = DateAdd('w',$x_dias_espera,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if($x_dia == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$x_fecha_ejecuta_act = $fecha_venc;
				
				$sqlUpdateFecha = "UPDATE crm_tarea SET  fecha_ejecuta = \"$x_fecha_ejecuta_act\" WHERE crm_tarea_id = $x_tarea_id_f ";
				$rsUpdateFecha = phpmkr_query($sqlUpdateFecha,$conn) or die ("Error al actualiza la fecha de latarea despues del calculo semana".phpmkr_error()."sql;".$sqlUpdateFecha);
				echo "UPDATAE TREA".$sqlUpdateFecha."<br>";
				
		}else{
			$x_fecha_ejecuta_act  = $x_fecha_tarea_f;
			$temptime = strtotime($x_fecha_ejecuta_act);	
				$temptime = DateAdd('w',$x_dias_espera,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if($x_dia == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$x_fecha_ejecuta_act = $fecha_venc;
				
				$sqlUpdateFecha = "UPDATE crm_tarea SET  fecha_ejecuta = \"$x_fecha_ejecuta_act\" WHERE crm_tarea_id = $x_tarea_id_f ";
				$rsUpdateFecha = phpmkr_query($sqlUpdateFecha,$conn) or die ("Error al actualiza la fecha de latarea despues del calculo semana".phpmkr_error()."sql;".$sqlUpdateFecha);
				echo "UPDATAE TREA 2".$sqlUpdateFecha."<br>";
			
			}
	#	echo "FECHA EJECUTA".$x_fecha_ejecuta_act;
		#####################################################################################################################
		##################################### TAREAS DIARIAS PROMOTOR #######################################################
		#####################################################################################################################
		
		// primero verifamos que la tarea aun no este en la lista, es decir, que se trate de la primera vez que entra al cliclo para este credito.
		$sqlBuscaatreaAsignada = "SELECT COUNT(*) AS atreas_asignadas FROM `tarea_diaria_promotor` WHERE fecha_ingreso =  \"$currdate\" AND promotor_id = $x_promotor_id_f ";
		$sqlBuscaatreaAsignada .= " AND `caso_id` = $x_caso_id_f";
		$rsBuscatareaAsignada = phpmkr_query($sqlBuscaatreaAsignada,$conn) or die("Erro al buscar atrea".phpmkr_error()."sql:".$sqlBuscaatreaAsignada);
		#echo "BUSCA TAREAS".$sqlBuscaatreaAsignada."<BR>";
		$rowBuscaTareaAsignada = phpmkr_fetch_array($rsBuscatareaAsignada);
		$x_tareas_asignadas_del_caso = $rowBuscaTareaAsignada["atreas_asignadas"];
#	echo "TAREAS ASIGNADAS ".$x_tareas_asignadas_del_caso."<br>";
		//se inserta la tarea en la lista de las actividades diarias del promotor
		if ($x_tareas_asignadas_del_caso < 1){
		$sqlInsertListaTarea = "INSERT INTO `tarea_diaria_promotor`";
		$sqlInsertListaTarea .= " (`tarea_diaria_promotor_id`, `promotor_id`, `zona_id`, `dia_semana`, `fecha_ingreso`, `fecha_lista`, `caso_id`, ";
		$sqlInsertListaTarea .= " `tarea_id`, `reingreso`, `fase`, `status_tarea`, `credito_id`) ";
		$sqlInsertListaTarea .= "VALUES (NULL, $x_promotor_id_f, $x_dia_zona_f , $x_dia_zona_f, \"$currdate\",\"$x_fecha_tarea_f\", $x_caso_id_f, $x_tarea_id_pp, '0', '3', '1', $x_credito_id);";
		$rsInsertListaTarea = phpmkr_query($sqlInsertListaTarea,$conn)or die("Error al insertar en lista diaria tareas".phpmkr_error()."sql:".$sqlInsertListaTarea);	 
		}
		
		
		
		
		
		
		}// CASO CRM > 0 DE CARTA 1
		
		}// SI NO EXISTE LA PROMESA DE PAGO PARA CARAT 1
		
		}// if carta 1>0 comision_genarada < 1

	
	if((($x_comision_generada + 0) > 0) && ($x_carta_2 == 0)){
		
		$x_promesas_c1 = 0;
		$x_fecha_promesa_pago_c1 = 0;
		//verificamos la Promesa de pago de C1
		$sqlCarta1 = "SELECT COUNT(*) AS crm_tarea_id FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND  crm_tarea_tipo_id = 8 AND orden= 4";
		$rsCarta1 = phpmkr_query($sqlCarta1,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCarta1);
		$rowCarata1 = phpmkr_fetch_array($rsCarta1);
		$x_carta_1_tarea_id = $rowCarata1["crm_tarea_id"];
		if($x_carta_1_tarea_id >0 ){
			//buscamos que ya tenga Una promesa de pago y que este vencida para entrar al ciclo			
			$sqlPPC1 = "SELECT * FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id  AND crm_tarea_tipo_id = 5 AND orden = 5 ";
			$rsppc1 = phpmkr_query($sqlPPC1,$conn)or die ("Error al seleccioanr la pp c2".phpmkr_error()."sql:".$sqlPPC1);
			$rowppc1 = phpmkr_fetch_array($rsppc1);
			$x_fecha_registro_tarea = $rowppc1["fecha_registro"];
			$x_tarea_ppc1 =$rowppc1["crm_tarea_id"];
			$x_caso_ppc1 = $rowppc1["crm_caso_id"];			
			if($x_caso_ppc1> 0){
				// la promesa de pago esta registrada; buscamos la fecha de pagoo de la promesa
				$sqlcrm_tarea_cv = "SELECT COUNT(*) AS promesas, promesa_pago  FROM crm_tarea_cv WHERE crm_tarea_id = $x_tarea_ppc1 ";
				$rscrm_tarea_cv = phpmkr_query($sqlcrm_tarea_cv,$conn)or die("Error al selecionar la fecha de promesa de pago".phpmkr_error()."sql:".$sqlcrm_tarea_cv);
				$rowcrm_tarea_cv = phpmkr_fetch_array($rscrm_tarea_cv);
				$x_promesas_c1 = $rowcrm_tarea_cv["promesas"];
				$x_fecha_promesa_pago_c1 = $rowcrm_tarea_cv["crm_tarea_cv"]; 			
				}			
			}
		
		$x_hoy_Z = date("Y-m-d");
		if($x_promesas_c1 > 1){
			if( $x_hoy_Z > $x_fecha_promesa_pago_c1){		
		#YA SE GENERO LA COMISON DE COBRANZA PERO AUN NO SE GENERA LA TAREA PARA IMPRIMIR  LA CARTA 2		
		#AQUI SE GENERA LA TREA DE CARATA 2 ;PROCESO IDENTICO A CARTA 1
		echo "entra a carta 2 <br>";
			###########################################################################################################################################
	###########################################################################################################################################
	###################################################               CARTA 2              ####################################################
	###########################################################################################################################################
	###########################################################################################################################################
	//SEGUNDO CASO CARTA 2			
	
			
					
			// seleccionamos los datos para la nueva tarea
				
				$sSqlWrk = "SELECT *
							FROM 
							crm_playlist
							WHERE 
							crm_playlist.crm_caso_tipo_id = 3
							AND crm_playlist.orden = 8 "; // orden 8 CARTA  2		
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_crm_playlist_id = $datawrk["crm_playlist_id"];
				$x_prioridad_id = $datawrk["prioridad_id"];	
				$x_asunto = $datawrk["asunto"];	
				$x_descripcion = $datawrk["descripcion"];		
				$x_tarea_tipo_id = $datawrk["tarea_fuente"];		
				$x_orden = $datawrk["orden"];	
				$x_dias_espera = $datawrk["dias_espera"];		
				@phpmkr_free_result($rswrk);
				
				
				
				//Fecha Vencimiento
				$temptime = strtotime($currdate);	
				$temptime = DateAdd('w',$x_dias_espera,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if($x_dia == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$temptime = strtotime($fecha_venc);
	
	
	
				$x_origen = 1;  // el origen se podria cambiar a responsable de sucursal.
				$x_bitacora = "Cartera Vencida  SE PASA A ETAPA 4  CARTA 2- (".FormatDateTime($currdate,7)." - $currtime)";
			
				$x_bitacora .= "\n";
				$x_bitacora .= "$x_asunto - $x_descripcion ";	


				$sSqlWrk = "
		SELECT cliente.cliente_id
		FROM 
			cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id join solicitud on solicitud.solicitud_id = solicitud_cliente.solicitud_id join credito on credito.solicitud_id = solicitud.solicitud_id
		WHERE 
			credito.credito_id = $x_credito_id
		LIMIT 1
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_cliente_id = $datawrk["cliente_id"];
		
		@phpmkr_free_result($rswrk);


		
		
		// seleccionamos el usuario del GESTOR DE COBRANZA// CARAT 2 EL RESPONSABLE DE LA TAREA ES EL GESTOR DE COBRANZA HASTA AHORA LE SR RAUL
		
				
		$sSqlWrk = "
		SELECT usuario_id
		FROM 
			usuario
		WHERE 
			usuario_rol_id =  14 "; // GESTOR COBRANZA
			
			
		// el gestor de cobranza estara relaciona con los promotores
		// esto se hizo asi, porque si se le asignaran todos los casao al gestor de cobranza de todos lo promotores le generaria tareas para los clientes que son de colima y en este caso es imposible que un gestor de cobranza del df pueda tomar lo casoas de los clientes de colima.
		
		# seleccionamos los datos del promotor.
		$x_promotor_de_gestor = 0;
		$sqlpromotor = "SELECT promotor_id FROM solicitud WHERE solicitud_id = $x_solicitud_id_c";
		$rspromotor = phpmkr_query($sqlpromotor,$conn) or die ("Error la selccionar le promotor-id".phpmk_error()."sql:".$sqlpromotor);
		$rowpromotor = phpmkr_fetch_array($rspromotor);	
		$x_promotor_de_gestor = $rowpromotor["promotor_id"];
		echo "promotor de gastor".$x_promotor_de_gestor."<br>";
		
		$sqlGestor = "SELECT gestor_id FROM gestor_promotor WHERE promotor_id = $x_promotor_de_gestor ";
		$rsGestor = phpmkr_query($sqlGestor,$conn)or die("Error al seleccionar el gestor".phpmkr_error()."sql:".$sqlGestor);
		$rowGestor = phpmkr_fetch_array($rsGestor);
		$x_gestor_id = $rowGestor["gestor_id"];
		
		// seleccionamos el usuario del gestor
		$sqlUsuario = "SELECT usuario_id  FROM gestor WHERE gestor_id = $x_gestor_id";
		$rsusuer = phpmkr_query($sqlUsuario,$conn)or die("Erro en usuario 2".phpmkr_query()."sql: ".$sqlUsuario);
		$rowuser = phpmkr_fetch_array($rsusuer);		
		$x_usuario_id = $rowuser["usuario_id"];
	
			// se debe verificar antes de ingresar la tarea que no exista ninguna tarea para el caso de este tipo
			$sqlBuscaTarea =  "SELECT COUNT(*) AS tareas_exist from  crm_tarea where crm_caso_id = $x_crm_caso_id AND crm_tarea_tipo_id = $x_tarea_tipo_id ";
			$sqlBuscaTarea .= " AND crm_tarea_status_id IN (1,2) AND destino = $x_usuario_id";	
			$rsBuscaTarea = phpmkr_query($sqlBuscaTarea,$conn) or die ("Erro al insertar en tarea".phpmkr_error()."sql:".$sqlBuscaTarea);
			$rowBuscaTarea = phpmkr_fetch_array($rsBuscaTarea);
			$x_tareas_abiertas = $rowBuscaTarea["tareas_exist"];
			
			#echo "<br><br> TREAS ABIERTAS ".$x_tareas_abiertas."<BR><BR>".$sqlBuscaTarea."<BR><BR>";
			if($x_tareas_abiertas == 0){
			$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 7, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
			// la tarea la ejecuta el asesor de crédito
			$rs = phpmkr_query($sSql,$conn) or die("Error al seleccionar insertar en tarea para promotor".phpmkr_error()."sql:".$sSql);
			echo "INSERTA CARTA 2<BR>".$sSql."<BR>";
			//$x_result = phpmkr_query($sSql, $conn);
			$x_tarea_id = mysql_insert_id();
	
			$sSqlWrk = "
			SELECT 
				comentario_int
			FROM 
				credito_comment
			WHERE 
				credito_id = ".$x_credito_id."
			LIMIT 1
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_comment_ant = $datawrk["comentario_int"];
			@phpmkr_free_result($rswrk);
	
	
			if(empty($x_comment_ant)){
				$sSql = "insert into credito_comment values(0, $x_credito_id, '$x_bitacora', NULL)";
			}else{
				$x_bitacora = $x_comment_ant . "\n\n------------------------------\n" . $x_bitacora;
				$sSql = "UPDATE credito_comment set comentario_int = '$x_bitacora' where credito_id = $x_credito_id";
			}
	
			phpmkr_query($sSql, $conn);
			
			
		########################################################################################################################################################
		############################################################ TAREAS DIARIAS PARA GESTORES	############################################################
		########################################################################################################################################################
		##credamos las lista de los promotores con la tareas diarias.		
		## seleccionaos la soza de la solcitud		
		$sqlZonaId = "SELECT dia FROM  zona_gestor JOIN solicitud ON solicitud.promotor_id = zona_gestor.promotor_id WHERE solicitud.solicitud_id = $x_solicitud_id_c";
		$rsZonaId = phpmkr_query($sqlZonaId,$conn) or die ("Error al seleccionar el dia de la zona".phpmkr_error()."sql:".$sqlZonaId);
		$rowZonaId = phpmkr_fetch_array($rsZonaId);
		$x_dia_zona_gestor = $rowZonaId["dia"];
		echo "sql dia zona gestor ".$sqlZonaId."<br>";
		echo "dia zona gestor".$x_dia_zona_gestor."<br>";
		
		$x_fecha_tarea_f = $currdate;
		$x_dia_zona_f = $x_dia_zona_gestor;
		$x_promotor_id_f = $x_promotor_id_c ;
		$x_caso_id_f = $x_crm_caso_id;
		$x_tarea_id_f = $x_tarea_id;
		
		
		#tenemos la fecha de hoy  --> hoy es miercoles 30 de enero
		# se debe buscar la fecha de la zona --> si la zona fuera 5.. la fecha se deberia cambiar a viernes 1 de febrero
		# o si la zona fuera  2 como las tareas de la zona 2 ya fueron asignadas.. se debe buscar la fecha de la zona dos de la sig semana
		
		$sqlDiaSemana = "SELECT DAYOFWEEK('$x_fecha_tarea_f') AS  dia";
		$rsDiaSemana = phpmkr_query($sqlDiaSemana,$conn) or die ("Error al seleccionar el dia de la semana".phpmkr_error()."sql:".$sqlDiaSemana);
		$rowDiaSemana = phpmkr_fetch_array($rsDiaSemana);
		$x_dia_de_semana_en_fecha = $rowDiaSemana["dia"];
		echo "fecha day of week".$x_dia_de_semana_en_fecha ."<br>";
		
		if($x_dia_de_semana_en_fecha != $x_dia_zona_f ){
			// el dia de la zona y el dia de la fecha no es el mismo se debe incrementar la fecha hasta llegar al mismo dia
			
			#echo "LOS DIAS SON DIFERENTES<BR>";
		//	$x_dia_de_semana_en_fecha = 1;
		//	$x_dia_zona_f= 5;
		//	$x_fecha_tarea_f = "2013-01-29";
			if($x_dia_de_semana_en_fecha < $x_dia_zona_f){
				// la fecha es mayor dia_en _fecha = 2; dia_zona = 5
				$x_dias_faltantes = $x_dia_zona_f - $x_dia_de_semana_en_fecha;	
				//hacemos el incremento en la fecha		
				//Fecha de tarea para la lista
				
			#	echo "fecha dia------".$x_fecha_tarea_f."<br>";
				$temptime = strtotime($x_fecha_tarea_f);	
				$temptime = DateAdd('w',$x_dias_faltantes,$temptime);
				$fecha_nueva = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				$x_fecha_tarea_f = $fecha_nueva;	
				echo "nueva fecha...ESTA semana<br> dias faltantes".$x_dias_faltantes."<br>".$x_fecha_tarea_f;			
				}else{
					// el dia dela fecha es mayor al dia de la zona...las tareas asignadas para esa zona ya pasaron; porque ya paso el dia de esa zona
					// se debe asigna la tarea para la semana sig el la fecha de la zona.
					$x_dias_faltantes = (7- $x_dia_de_semana_en_fecha)+ $x_dia_zona_f;
					//hacemos el incremento en la fecha		
					//Fecha de tarea para la lista
					$temptime = strtotime($x_fecha_tarea_f);	
					$temptime = DateAdd('w',$x_dias_faltantes,$temptime);
					$fecha_nueva = strftime('%Y-%m-%d',$temptime);			
					$x_dia = strftime('%A',$temptime);
					$x_fecha_tarea_f = $fecha_nueva;	
					echo "nueva fecha...sigueinte semana<br> dias faltantes".$x_dias_faltantes."<br>";
				#	echo "DIA DE LA SEMANA EN FECHA".$x_dia_de_semana_en_fecha."<br>";
				#	echo "dia zona".$x_dia_zona_f;					
					}
			}
		
		$x_dias_agregados = calculaSemanasGestor($conn, $x_fecha_tarea_f, $x_dia_zona_f, $x_promotor_id_f, $x_caso_id_f, $x_tarea_id_f, $x_gestor_id);
		echo "dias agragados".$x_dias_agregados." ";
		
		// se gragan los dias que faltan si es que es mayor de 0
		if($x_dias_agregados > 0){
					$temptime = strtotime($x_fecha_tarea_f);	
					$temptime = DateAdd('w',$x_dias_agregados,$temptime);
					$fecha_nueva = strftime('%Y-%m-%d',$temptime);			
					$x_fecha_tarea_f = $fecha_nueva;
				// se hizo el incremento en la fecha				
				//se actualiza la tarea con la fecha nueva
				$x_fecha_ejecuta_act = $x_fecha_tarea_f;
				
				$temptime = strtotime($x_fecha_ejecuta_act);	
				$temptime = DateAdd('w',$x_dias_espera,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if($x_dia == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$x_fecha_ejecuta_act = $fecha_venc; //kuki
				
				$sqlUpdateFecha = "UPDATE crm_tarea SET fecha_ejecuta = \"$x_fecha_ejecuta_act\" WHERE crm_tarea_id = $x_tarea_id_f ";
				$rsUpdateFecha = phpmkr_query($sqlUpdateFecha,$conn) or die ("Error al actualiza la fecha de latarea despues del calculo semana".phpmkr_error()."sql;".$sqlUpdateFecha);
				echo "UPDATAE TREA 1".$sqlUpdateFecha."<br>";
				
		}else{
			$x_fecha_ejecuta_act  = $x_fecha_tarea_f;
			$temptime = strtotime($x_fecha_ejecuta_act);	
				$temptime = DateAdd('w',$x_dias_espera,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if($x_dia == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$x_fecha_ejecuta_act = $fecha_venc;
				
				$sqlUpdateFecha = "UPDATE crm_tarea SET  fecha_ejecuta = \"$x_fecha_ejecuta_act\" WHERE crm_tarea_id = $x_tarea_id_f ";
				$rsUpdateFecha = phpmkr_query($sqlUpdateFecha,$conn) or die ("Error al actualiza la fecha de latarea despues del calculo semana".phpmkr_error()."sql;".$sqlUpdateFecha);
				echo "UPDATAE TREA 2".$sqlUpdateFecha."<br>";
			
			
			}
	#	echo "FECHA EJECUTA".$x_fecha_ejecuta_act;
		#####################################################################################################################
		###############################    TAREAS DIARIAS GESTOR DE COBRANZA    #############################################
		#####################################################################################################################
		$x_tareas_asignadas_del_caso = 0;
		// primero verifamos que la tarea aun no este en la lista, es decir, que se trate de la primera vez que entra al cliclo para este credito.
		$sqlBuscaatreaAsignada = "SELECT COUNT(*) AS atreas_asignadas FROM `tarea_diaria_gestor` WHERE fecha_ingreso =  \"$currdate\" AND gestor_id = $x_gestor_id ";
		$sqlBuscaatreaAsignada .= " AND `caso_id` = $x_caso_id_f";
		$rsBuscatareaAsignada = phpmkr_query($sqlBuscaatreaAsignada,$conn) or die("Erro al buscar atrea".phpmkr_error()."sql:".$sqlBuscaatreaAsignada);
	#echo "BUSCA TAREAS".$sqlBuscaatreaAsignada."<BR>";
		$rowBuscaTareaAsignada = phpmkr_fetch_array($rsBuscatareaAsignada);
		$x_tareas_asignadas_del_caso = $rowBuscaTareaAsignada["atreas_asignadas"];
	#	echo "TAREAS ASIGNADAS ".$x_tareas_asignadas_del_caso."<br>";
	#	//se inserta la tarea en la lista de las actividades diarias del promotor
		if ($x_tareas_asignadas_del_caso < 1){
		$sqlInsertListaTarea = "INSERT INTO `tarea_diaria_gestor`";
		$sqlInsertListaTarea .= " (`tarea_diaria_gestor_id`, `gestor_id`, `zona_id`, `dia_semana`, `fecha_ingreso`, `fecha_lista`, `caso_id`, ";
		$sqlInsertListaTarea .= " `tarea_id`, `reingreso`, `fase`, `status_tarea`, `credito_id`) ";
		$sqlInsertListaTarea .= "VALUES (NULL, $x_gestor_id, $x_dia_zona_f , $x_dia_zona_f, \"$currdate\",\"$x_fecha_tarea_f\", $x_caso_id_f, $x_tarea_id_f, '0', '2', '1', $x_credito_id);";
		$rsInsertListaTarea = phpmkr_query($sqlInsertListaTarea,$conn)or die("Error al insertar en lista diaria tareas +++++".phpmkr_error()."sql:".$sqlInsertListaTarea);	 
		}
		
		
			}// tareas registradas = 0
			
		
		
			}//la promesa de pago es menor a hoy
		}//existe la promesa  de pago
		
		}// if comision generada > 1 and carta 2 = 0
		
		
	if(($x_carta_2 > 0) && ($x_ciclo_carta_2 == 1) && ($x_carta_3 == 0) ){
		// ya se le genero la carta 2
		// ya esta en fase 5 Y AQUI SE CICLA
		// ya tiene una o mas promesas de pago...
		// SE genera una promesa de pagao al promotor de credito (angelica, monica, jose) 
		// carta 1 ya existe, pero debemos de verificar que la tarea esta vencida o esta cerrada
		// si ya se vencio la tarea o ya se netrego la carta entonces si ya podemos programar la sigueinte tarea
		// de lo contrario si el credito tuvuera mas de dos vencidos.. generaria la carata y tambien generaria la PP 
		
	
		
		# PONEMOS LA CONDICIONES DEL CICLO		
		#verificamos que no tenga ninguna PP con status de pendiente;	
		$x_carta_2PP = 0;
		$sqlCarta2PP = "SELECT COUNT(*) AS carta_2_PP FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND crm_tarea_tipo_id = 5 AND orden= 9 AND crm_tarea_status_id = 1";
		$rsCarta2PP = phpmkr_query($sqlCarta2PP,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCarta2PP);
		$rowCarata2PP = phpmkr_fetch_array($rsCarta2PP);
		$x_carta_2PP = $rowCarata2PP["carta_2_PP"];
		// se agrega la tarea siemore y cuando no exista una tarea igaul en status pendiente	
		
			$x_carta_2PP_v = 0;
		$sqlCarta2PP_vencida = "SELECT COUNT(*) AS carta_2_PP FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND crm_tarea_tipo_id = 5 AND orden= 9 AND crm_tarea_status_id in (2,3)";
		$rsCarta2PP_vencida = phpmkr_query($sqlCarta2PP_vencida,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCarta2PP_vencida);
		$rowCarata2PP_vencida = phpmkr_fetch_array($rsCarta2PP_vencida);
		$x_carta_2PP_v = $rowCarata2PP_vencida["carta_2_PP"];
		
		if(($x_carta_2PP < 1) && (($x_carta_2PP_v < 1))){
			
		#SELECCIONAMOS LOS DATOS DE  LA TAREA
		
		$sSqlWrk = "
		SELECT *
		FROM 
			crm_playlist
		WHERE 
			crm_playlist.crm_caso_tipo_id = 3
			AND crm_playlist.orden = 9"; // PROMESA DE PAGO CARTA 2
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_crm_playlist_id = $datawrk["crm_playlist_id"];
		$x_prioridad_id = $datawrk["prioridad_id"];	
		$x_asunto = $datawrk["asunto"];	
		$x_descripcion = $datawrk["descripcion"];		
		$x_tarea_tipo_id = $datawrk["tarea_fuente"];		
		$x_orden = $datawrk["orden"];	
		$x_dias_espera = $datawrk["dias_espera"];		
		@phpmkr_free_result($rswrk);
	
	
		//Fecha Vencimiento
		$temptime = strtotime($currdate);	
		$temptime = DateAdd('w',$x_dias_espera,$temptime);
		$fecha_venc = strftime('%Y-%m-%d',$temptime);			
		$x_dia = strftime('%A',$temptime);
		if($x_dia == "SUNDAY"){
			$temptime = strtotime($fecha_venc);
			$temptime = DateAdd('w',1,$temptime);
			$fecha_venc = strftime('%Y-%m-%d',$temptime);
		}
		$temptime = strtotime($fecha_venc);
	
	
	
		$x_origen = 1;
		$x_bitacora = "Cartera Vencida - (".FormatDateTime($currdate,7)." - $currtime)";
	
		$x_bitacora .= "\n";
		$x_bitacora .= "$x_asunto - $x_descripcion ";	


		$sSqlWrk = "
		SELECT cliente.cliente_id
		FROM 
			cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id join solicitud on solicitud.solicitud_id = solicitud_cliente.solicitud_id join credito on credito.solicitud_id = solicitud.solicitud_id
		WHERE 
			credito.credito_id = $x_credito_id
		LIMIT 1
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_cliente_id = $datawrk["cliente_id"];
		@phpmkr_free_result($rswrk);
		
		
		
		
		###############################################################################
		######################  aqui el usuario del sr RAUL     #######################
		###############################################################################
		
		# seleccionamos los datos del promotor.
		$x_promotor_de_gestor = 0;
		$sqlpromotor = "SELECT promotor_id FROM solicitud WHERE solicitud_id = $x_solicitud_id_c";
		$rspromotor = phpmkr_query($sqlpromotor,$conn) or die ("Error la selccionar le promotor-id".phpmk_error()."sql:".$sqlpromotor);
		$rowpromotor = phpmkr_fetch_array($rspromotor);
		mysql_free_result($rspromotor);	
		$x_promotor_de_gestor = $rowpromotor["promotor_id"];
	#	 echo "promotor de gestor".$x_promotor_de_gestor."<br>";
		$sqlGestor = "SELECT gestor_id FROM gestor_promotor WHERE promotor_id = $x_promotor_de_gestor ";
		$rsGestor = phpmkr_query($sqlGestor,$conn)or die("Error al seleccionar el gestor".phpmkr_error()."sql:".$sqlGestor);
		$rowGestor = phpmkr_fetch_array($rsGestor);
		mysql_free_result($rsGestor);
		$x_gestor_id = $rowGestor["gestor_id"];
		
		// seleccionamos el usuario del gestor
		$sqlUsuario = "SELECT usuario_id  FROM gestor WHERE gestor_id = $x_gestor_id";
		$rsusuer = phpmkr_query($sqlUsuario,$conn)or die("Erro en usuario 1".phpmkr_query()."sql: ".$sqlUsuario);
		$rowuser = phpmkr_fetch_array($rsusuer);	
		mysql_free_result($rsusuer);
		$x_usuario_id = $rowuser["usuario_id"];
		
			
			



		if(($x_crm_caso_id > 0)){

			$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 2, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
			
			echo "SE INSERTA CARAT 2<BR>".$sSql."<BR>";
		
			$x_result = phpmkr_query($sSql, $conn);
			$x_tarea_id = mysql_insert_id();
	
			$sSqlWrk = "
			SELECT 
				comentario_int
			FROM 
				credito_comment
			WHERE 
				credito_id = ".$x_credito_id."
			LIMIT 1
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_comment_ant = $datawrk["comentario_int"];
			@phpmkr_free_result($rswrk);
	
	
			if(empty($x_comment_ant)){
				$sSql = "insert into credito_comment values(0, $x_credito_id, '$x_bitacora', NULL)";
			}else{
				$x_bitacora = $x_comment_ant . "\n\n------------------------------\n" . $x_bitacora;
				$sSql = "UPDATE credito_comment set comentario_int = '$x_bitacora' where credito_id = $x_credito_id";
			}
	
			phpmkr_query($sSql, $conn);
		
		
		// buscar el dia que se asignara la tarea al prmotor
		########################################################################################################################################################
		############################################################ TAREAS DIARIAS PARA GESTOR 	############################################################
		########################################################################################################################################################
		##credamos las lista de los promotores con la tareas diarias.		
		## seleccionaos la soza de la solcitud		
		$sqlZonaId = "SELECT dia FROM  zona_gestor JOIN solicitud ON solicitud.promotor_id = zona_gestor.promotor_id WHERE solicitud.solicitud_id = $x_solicitud_id_c";
		//echo "selecciono el dia de la zona gestor".$sqlZonaId."<br>" ;
		$rsZonaId = phpmkr_query($sqlZonaId,$conn) or die ("Error al seleccionar el dia de la zona".phpmkr_error()."sql:".$sqlZonaId);
		$rowZonaId = phpmkr_fetch_array($rsZonaId);
		$x_dia_zona = $rowZonaId["dia"];
		echo "dia zona ".$x_dia_zona."<br>";
		$x_fecha_tarea_f = $currdate;
		$x_dia_zona_f = $x_dia_zona;
		$x_promotor_id_f = $x_promotor_id_c ;
		$x_caso_id_f = $x_crm_caso_id;
		$x_tarea_id_f = $x_tarea_id;
	
		
		
		#tenemos la fecha de hoy  --> hoy es miercoles 30 de enero
		# se debe buscar la fecha de la zona --> si la zona fuera 5.. la fecha se deberia cambiar a viernes 1 de febrero
		# o si la zona fuera  2 como las tareas de la zona 2 ya fueron asignadas.. se debe buscar la fecha de la zona dos de la sig semana
		
		$sqlDiaSemana = "SELECT DAYOFWEEK('$x_fecha_tarea_f') AS  dia";
		$rsDiaSemana = phpmkr_query($sqlDiaSemana,$conn) or die ("Error al seleccionar el dia de la semana".phpmkr_error()."sql:".$sqlDiaSemana);
		$rowDiaSemana = phpmkr_fetch_array($rsDiaSemana);
		$x_dia_de_semana_en_fecha = $rowDiaSemana["dia"];
		echo "fecha day of week".$x_dia_de_semana_en_fecha ."<br>";
		
		if($x_dia_de_semana_en_fecha != $x_dia_zona_f ){
			// el dia de la zona y el dia de la fecha no es el mismo se debe incrementar la fecha hasta llegar al mismo dia
			
			//echo "LOS DIAS SON DIFERENTES<BR>";
		//	$x_dia_de_semana_en_fecha = 1;
		//	$x_dia_zona_f= 5;
		//	$x_fecha_tarea_f = "2013-01-29";
			if($x_dia_de_semana_en_fecha < $x_dia_zona_f){
				// la fecha es mayor dia_en _fecha = 2; dia_zona = 5
				$x_dias_faltantes = $x_dia_zona_f - $x_dia_de_semana_en_fecha;	
				//hacemos el incremento en la fecha		
				//Fecha de tarea para la lista
				
			//	echo "fecha dia------".$x_fecha_tarea_f."<br>";
				$temptime = strtotime($x_fecha_tarea_f);	
				$temptime = DateAdd('w',$x_dias_faltantes,$temptime);
				$fecha_nueva = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				$x_fecha_tarea_f = $fecha_nueva;	
			//	echo "nueva fecha...ESTA semana<br> dias faltantes".$x_dias_faltantes."<br>";			
				}else{
					// el dia dela fecha es mayor al dia de la zona...las tareas asignadas para esa zona ya pasaron; porque ya paso el dia de esa zona
					// se debe asigna la tarea para la semana sig el la fecha de la zona.
					$x_dias_faltantes = (7- $x_dia_de_semana_en_fecha)+ $x_dia_zona_f;
					//hacemos el incremento en la fecha		
					//Fecha de tarea para la lista
					$temptime = strtotime($x_fecha_tarea_f);	
					$temptime = DateAdd('w',$x_dias_faltantes,$temptime);
					$fecha_nueva = strftime('%Y-%m-%d',$temptime);			
					$x_dia = strftime('%A',$temptime);
					$x_fecha_tarea_f = $fecha_nueva;	
				//	echo "nueva fecha...sigueinte semana<br> dias faltantes".$x_dias_faltantes."<br>";
					#echo "DIA DE LA SEMANA EN FECHA".$x_dia_de_semana_en_fecha."<br>";
					#echo "dia zona".$x_dia_zona_f;					
					}
			}
		
		$x_dias_agregados = calculaSemanas($conn, $x_fecha_tarea_f, $x_dia_zona_f, $x_promotor_id_f, $x_caso_id_f, $x_tarea_id_f);
		#echo "dias agragados".$x_dias_agregados." ";
		
		// se gragan los dias que faltan si es que es mayor de 0
		if($x_dias_agregados > 0){
					$temptime = strtotime($x_fecha_tarea_f);	
					$temptime = DateAdd('w',$x_dias_agregados,$temptime);
					$fecha_nueva = strftime('%Y-%m-%d',$temptime);			
					$x_fecha_tarea_f = $fecha_nueva;
				// se hizo el incremento en la fecha				
				//se actualiza la tarea con la fecha nueva
				$x_fecha_ejecuta_act = $x_fecha_tarea_f;
				
				$temptime = strtotime($x_fecha_ejecuta_act);	
				$temptime = DateAdd('w',$x_dias_espera,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if($x_dia == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$x_fecha_ejecuta_act = $fecha_venc;
				
				$sqlUpdateFecha = "UPDATE crm_tarea SET  fecha_ejecuta = \"$x_fecha_ejecuta_act\" WHERE crm_tarea_id = $x_tarea_id_f ";
				$rsUpdateFecha = phpmkr_query($sqlUpdateFecha,$conn) or die ("Error al actualiza la fecha de latarea despues del calculo semana".phpmkr_error()."sql;".$sqlUpdateFecha);
			#	echo "UPDATAE TREA".$sqlUpdateFecha."<br>";
				
		}else{
			$x_fecha_ejecuta_act  = $x_fecha_tarea_f;
			$temptime = strtotime($x_fecha_ejecuta_act);	
				$temptime = DateAdd('w',$x_dias_espera,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if($x_dia == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$x_fecha_ejecuta_act = $fecha_venc;
			$sqlUpdateFecha = "UPDATE crm_tarea SET  fecha_ejecuta = \"$x_fecha_ejecuta_act\" WHERE crm_tarea_id = $x_tarea_id_f ";
				$rsUpdateFecha = phpmkr_query($sqlUpdateFecha,$conn) or die ("Error al actualiza la fecha de latarea despues del calculo semana".phpmkr_error()."sql;".$sqlUpdateFecha);
			}
		echo "FECHA EJECUTA".$x_fecha_ejecuta_act;
		#####################################################################################################################
		##################################### TAREAS DIARIAS GESTOR   #######################################################
		#####################################################################################################################
		
		// primero verifamos que la tarea aun no este en la lista, es decir, que se trate de la primera vez que entra al cliclo para este credito.
		$sqlBuscaatreaAsignada = "SELECT COUNT(*) AS atreas_asignadas FROM `tarea_diaria_gestor` WHERE fecha_ingreso =  \"$currdate\" AND gestor_id = $x_gestor_id";
		$sqlBuscaatreaAsignada .= " AND `caso_id` = $x_caso_id_f";
		$rsBuscatareaAsignada = phpmkr_query($sqlBuscaatreaAsignada,$conn) or die("Erro al buscar atrea".phpmkr_error()."sql:".$sqlBuscaatreaAsignada);
		#echo "BUSCA TAREAS".$sqlBuscaatreaAsignada."<BR>";
		$rowBuscaTareaAsignada = phpmkr_fetch_array($rsBuscatareaAsignada);
		$x_tareas_asignadas_del_caso = $rowBuscaTareaAsignada["atreas_asignadas"];
		#echo "TAREAS ASIGNADAS ".$x_tareas_asignadas_del_caso."<br>";
		//se inserta la tarea en la lista de las actividades diarias del promotor
		if ($x_tareas_asignadas_del_caso < 1){
		$sqlInsertListaTarea = "INSERT INTO `tarea_diaria_gestor`";
		$sqlInsertListaTarea .= " (`tarea_diaria_gestor_id`, `gestor_id`, `zona_id`, `dia_semana`, `fecha_ingreso`, `fecha_lista`, `caso_id`, ";
		$sqlInsertListaTarea .= " `tarea_id`, `reingreso`, `fase`, `status_tarea`, `credito_id`) ";
		$sqlInsertListaTarea .= "VALUES (NULL, $x_gestor_id, $x_dia_zona_f , $x_dia_zona_f, \"$currdate\",\"$x_fecha_tarea_f\", $x_caso_id_f, $x_tarea_id_f, '0', '2', '1', $x_credito_id);";
		$rsInsertListaTarea = phpmkr_query($sqlInsertListaTarea,$conn)or die("Error al insertar en lista diaria tareas ppc2".phpmkr_error()."sql:".$sqlInsertListaTarea);	 
		}
		
		
		
		
		
		
		}// CASO CRM > 0 DE CARTA 1
		
		}// SI NO EXISTE LA PROMESA DE PAGO PARA CARAT 1
		
		}// carta 2 ya existe pero entra a ciclo PP carta 2	
		
		
	if(($x_carta_2 > 0) && ($x_ciclo_carta_2 == 0) && ($x_carta_3 == 0)){		
		
		$x_promesas_c2 = 0;
		$x_fecha_promesa_pago_c2 = 0;	
			
			$sqlCarta2 = "SELECT COUNT(*) AS crm_tarea_id FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND  crm_tarea_tipo_id = 9 AND orden= 8";
			$rsCarta2 = phpmkr_query($sqlCarta2,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCarta2);
			$rowCarata2 = phpmkr_fetch_array($rsCarta2);
			$x_carta_2_tarea_id = $rowCarata2["crm_tarea_id"];
			if($x_carta_2_tarea_id >0 ){
				//buscamos que ya tenga Una promesa de pago y que este vencida para entrar al ciclo			
				$sqlPPC2 = "SELECT * FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id  AND crm_tarea_tipo_id = 5 AND orden = 9 ";
				$rsppc2 = phpmkr_query($sqlPPC2,$conn)or die ("Error al seleccioanr la pp c2".phpmkr_error()."sql:".$sqlPPC2);
				$rowppc2 = phpmkr_fetch_array($rsppc1);
				$x_fecha_registro_tarea = $rowppc2["fecha_registro"];
				$x_tarea_ppc2 =$rowppc2["crm_tarea_id"];
				$x_caso_ppc2 = $rowppc2["crm_caso_id"];			
				if($x_caso_ppc2> 0){
					// la promesa de pago esta registrada; buscamos la fecha de pagoo de la promesa
					$sqlcrm_tarea_cv2 = "SELECT COUNT(*) AS promesas, promesa_pago  FROM crm_tarea_cv WHERE crm_tarea_id = $x_tarea_ppc2 ";
					$rscrm_tarea_cv2 = phpmkr_query($sqlcrm_tarea_cv2,$conn)or die("Error al selecionar la fecha de promesa de pago".phpmkr_error()."sql:".$sqlcrm_tarea_cv2);
					$rowcrm_tarea_cv2 = phpmkr_fetch_array($rscrm_tarea_cv2);
					$x_promesas_c2 = $rowcrm_tarea_cv2["promesas"];
					$x_fecha_promesa_pago_c2 = $rowcrm_tarea_cv2["crm_tarea_cv"]; 			
					}			
				}
				
				$x_hoy_p = date("Y-m-d");
				if($x_promesas_c2 > 0){
					if( $x_hoy_p > $x_fecha_promesa_pago_c2 ){				
	###########################################################################################################################################
	###########################################################################################################################################
	###################################################               CARTA 3              ####################################################
	###########################################################################################################################################
	###########################################################################################################################################
	//ETAPA 6 CASO CARTA 3			
	#echo "ENTRA A CARTA 3<BR>";
	
	
			// buscar usuario del rs raul	
			
			############################################
			#############  falta codigo  ##############
			############################################	
			//LA ATAREA SE LE GENERA LA GESTROR NO AL PROMOTOR
			$sSqlWrk = "SELECT usuario_id
						FROM 
						promotor
						WHERE 
						promotor.promotor_id = $x_promotor_id_c ";		
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_promotor_usuario_id = $datawrk["usuario_id"];
			@phpmkr_free_result($rswrk);
					
			// seleccionamos los datos para la nueva tarea
				
				$sSqlWrk = "SELECT *
							FROM 
							crm_playlist
							WHERE 
							crm_playlist.crm_caso_tipo_id = 3
							AND crm_playlist.orden = 12 "; // orden 12 CARTA  3		
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_crm_playlist_id = $datawrk["crm_playlist_id"];
				$x_prioridad_id = $datawrk["prioridad_id"];	
				$x_asunto = $datawrk["asunto"];	
				$x_descripcion = $datawrk["descripcion"];		
				$x_tarea_tipo_id = $datawrk["tarea_fuente"];		
				$x_orden = $datawrk["orden"];	
				$x_dias_espera = $datawrk["dias_espera"];		
				@phpmkr_free_result($rswrk);
				
				
				
				//Fecha Vencimiento
				$temptime = strtotime($currdate);	
				$temptime = DateAdd('w',$x_dias_espera,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if($x_dia == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$temptime = strtotime($fecha_venc);
	
	
	
				$x_origen = 1;  // el origen se podria cambiar a responsable de sucursal.
				$x_bitacora = "Cartera Vencida  SE PASA A ETAPA 6  CARTA 3- (".FormatDateTime($currdate,7)." - $currtime)";
			
				$x_bitacora .= "\n";
				$x_bitacora .= "$x_asunto - $x_descripcion ";	


				$sSqlWrk = "
		SELECT cliente.cliente_id
		FROM 
			cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id join solicitud on solicitud.solicitud_id = solicitud_cliente.solicitud_id join credito on credito.solicitud_id = solicitud.solicitud_id
		WHERE 
			credito.credito_id = $x_credito_id
		LIMIT 1
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_cliente_id = $datawrk["cliente_id"];
		
		@phpmkr_free_result($rswrk);


		
		
		###############################################################################
		######################  aqui el usuario del sr RAUL     #######################
		###############################################################################
		
		# seleccionamos los datos del promotor.
		$x_promotor_de_gestor = 0;
		$sqlpromotor = "SELECT promotor_id FROM solicitud WHERE solicitud_id = $x_solicitud_id_c";
		$rspromotor = phpmkr_query($sqlpromotor,$conn) or die ("Error la selccionar le promotor-id".phpmk_error()."sql:".$sqlpromotor);
		$rowpromotor = phpmkr_fetch_array($rspromotor);
		mysql_free_result($rspromotor);	
		$x_promotor_de_gestor = $rowpromotor["promotor_id"];
		//echo "prmotor de gestor".$x_promotor_de_gestor."<br>";
		
		$sqlGestor = "SELECT gestor_id FROM gestor_promotor WHERE promotor_id = $x_promotor_de_gestor ";
		$rsGestor = phpmkr_query($sqlGestor,$conn)or die("Error al seleccionar el gestor".phpmkr_error()."sql:".$sqlGestor);
		$rowGestor = phpmkr_fetch_array($rsGestor);
		mysql_free_result($rsGestor);
		$x_gestor_id = $rowGestor["gestor_id"];
		
		// seleccionamos el usuario del gestor
		$sqlUsuario = "SELECT usuario_id  FROM gestor WHERE gestor_id = $x_gestor_id";
		$rsusuer = phpmkr_query($sqlUsuario,$conn)or die("Erro en usuario x".phpmkr_query()."sql: ".$sqlUsuario);
		$rowuser = phpmkr_fetch_array($rsusuer);	
		mysql_free_result($rsusuer);
		$x_usuario_id = $rowuser["usuario_id"];
		
				

		
	
			// se debe verificar antes de ingresar la tarea que no exista ninguna tarea para el caso de este tipo
			$sqlBuscaTarea =  "SELECT COUNT(*) AS tareas_exist from  crm_tarea where crm_caso_id = $x_crm_caso_id AND crm_tarea_tipo_id = $x_tarea_tipo_id ";
			$sqlBuscaTarea .= " AND crm_tarea_status_id IN (1,2) AND destino = $x_usuario_id";	
			$rsBuscaTarea = phpmkr_query($sqlBuscaTarea,$conn) or die ("Erro al insertar en tarea 2".phpmkr_error()."sql:".$sqlBuscaTarea);
			$rowBuscaTarea = phpmkr_fetch_array($rsBuscaTarea);
			$x_tareas_abiertas = $rowBuscaTarea["tareas_exist"];
			
			//echo "<br><br> TREAS ABIERTAS ".$x_tareas_abiertas."<BR><BR>".$sqlBuscaTarea."<BR><BR>";
			if($x_tareas_abiertas == 0){
			$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 7, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
			// la tarea la ejecuta el asesor de crédito
			$rs = phpmkr_query($sSql,$conn) or die("Error al seleccionar insertar en tarea para promotor".phpmkr_error()."sql:".$sSql);
		echo "SE INSERTA CARATA 3<BR>".$sSql."<BR>";
			//$x_result = phpmkr_query($sSql, $conn);
			$x_tarea_id = mysql_insert_id();
	
			$sSqlWrk = "
			SELECT 
				comentario_int
			FROM 
				credito_comment
			WHERE 
				credito_id = ".$x_credito_id."
			LIMIT 1
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_comment_ant = $datawrk["comentario_int"];
			@phpmkr_free_result($rswrk);
	
	
			if(empty($x_comment_ant)){
				$sSql = "insert into credito_comment values(0, $x_credito_id, '$x_bitacora', NULL)";
			}else{
				$x_bitacora = $x_comment_ant . "\n\n------------------------------\n" . $x_bitacora;
				$sSql = "UPDATE credito_comment set comentario_int = '$x_bitacora' where credito_id = $x_credito_id";
			}
	
			phpmkr_query($sSql, $conn);
			
			
		########################################################################################################################################################
		############################################################ TAREAS DIARIAS PARA GESTORES	############################################################
		########################################################################################################################################################
		##credamos las lista de los promotores con la tareas diarias.		
		## seleccionaos la soza de la solcitud		
		$sqlZonaId = "SELECT dia FROM  zona_gestor JOIN solicitud ON solicitud.promotor_id = zona_gestor.promotor_id WHERE solicitud.solicitud_id = $x_solicitud_id_c";
		$rsZonaId = phpmkr_query($sqlZonaId,$conn) or die ("Error al seleccionar el dia de la zona".phpmkr_error()."sql:".$sqlZonaId);
		$rowZonaId = phpmkr_fetch_array($rsZonaId);
		$x_dia_zona_gestor = $rowZonaId["dia"];
		
		$x_fecha_tarea_f = $currdate;
		$x_dia_zona_f = $x_dia_zona_gestor;
		$x_promotor_id_f = $x_promotor_id_c ;
		$x_caso_id_f = $x_crm_caso_id;
		$x_tarea_id_f = $x_tarea_id;
		
		
		#tenemos la fecha de hoy  --> hoy es miercoles 30 de enero
		# se debe buscar la fecha de la zona --> si la zona fuera 5.. la fecha se deberia cambiar a viernes 1 de febrero
		# o si la zona fuera  2 como las tareas de la zona 2 ya fueron asignadas.. se debe buscar la fecha de la zona dos de la sig semana
		
		$sqlDiaSemana = "SELECT DAYOFWEEK('$x_fecha_tarea_f') AS  dia";
		$rsDiaSemana = phpmkr_query($sqlDiaSemana,$conn) or die ("Error al seleccionar el dia de la semana".phpmkr_error()."sql:".$sqlDiaSemana);
		$rowDiaSemana = phpmkr_fetch_array($rsDiaSemana);
		$x_dia_de_semana_en_fecha = $rowDiaSemana["dia"];
		#echo "fecha day of week".$x_dia_de_semana_en_fecha ."<br>";
		
		if($x_dia_de_semana_en_fecha != $x_dia_zona_f ){
			// el dia de la zona y el dia de la fecha no es el mismo se debe incrementar la fecha hasta llegar al mismo dia
			
		#	echo "LOS DIAS SON DIFERENTES<BR>";
		//	$x_dia_de_semana_en_fecha = 1;
		//	$x_dia_zona_f= 5;
		//	$x_fecha_tarea_f = "2013-01-29";
			if($x_dia_de_semana_en_fecha < $x_dia_zona_f){
				// la fecha es mayor dia_en _fecha = 2; dia_zona = 5
				$x_dias_faltantes = $x_dia_zona_f - $x_dia_de_semana_en_fecha;	
				//hacemos el incremento en la fecha		
				//Fecha de tarea para la lista
				
			#	echo "fecha dia------".$x_fecha_tarea_f."<br>";
				$temptime = strtotime($x_fecha_tarea_f);	
				$temptime = DateAdd('w',$x_dias_faltantes,$temptime);
				$fecha_nueva = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				$x_fecha_tarea_f = $fecha_nueva;	
			#	echo "nueva fecha...ESTA semana<br> dias faltantes".$x_dias_faltantes."<br>";			
				}else{
					// el dia dela fecha es mayor al dia de la zona...las tareas asignadas para esa zona ya pasaron; porque ya paso el dia de esa zona
					// se debe asigna la tarea para la semana sig el la fecha de la zona.
					$x_dias_faltantes = (7- $x_dia_de_semana_en_fecha)+ $x_dia_zona_f;
					//hacemos el incremento en la fecha		
					//Fecha de tarea para la lista
					$temptime = strtotime($x_fecha_tarea_f);	
					$temptime = DateAdd('w',$x_dias_faltantes,$temptime);
					$fecha_nueva = strftime('%Y-%m-%d',$temptime);			
					$x_dia = strftime('%A',$temptime);
					$x_fecha_tarea_f = $fecha_nueva;	
					echo "nueva fecha...sigueinte semana<br> dias faltantes".$x_dias_faltantes."<br>";
					echo "DIA DE LA SEMANA EN FECHA".$x_dia_de_semana_en_fecha."<br>";
					echo "dia zona".$x_dia_zona_f;					
					}
			}
		
		$x_dias_agregados = calculaSemanasGestor($conn, $x_fecha_tarea_f, $x_dia_zona_f, $x_promotor_id_f, $x_caso_id_f, $x_tarea_id_f, $x_gestor_id);
		#echo "dias agragados".$x_dias_agregados." ";
		
		// se gragan los dias que faltan si es que es mayor de 0
		if($x_dias_agregados > 0){
					$temptime = strtotime($x_fecha_tarea_f);	
					$temptime = DateAdd('w',$x_dias_agregados,$temptime);
					$fecha_nueva = strftime('%Y-%m-%d',$temptime);			
					$x_fecha_tarea_f = $fecha_nueva;
				// se hizo el incremento en la fecha				
				//se actualiza la tarea con la fecha nueva
				$x_fecha_ejecuta_act = $x_fecha_tarea_f;
				
				$temptime = strtotime($x_fecha_ejecuta_act);	
				$temptime = DateAdd('w',$x_dias_espera,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if($x_dia == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$x_fecha_ejecuta_act = $fecha_venc;
				
				$sqlUpdateFecha = "UPDATE crm_tarea SET  fecha_ejecuta = \"$x_fecha_ejecuta_act\" WHERE crm_tarea_id = $x_tarea_id_f ";
				$rsUpdateFecha = phpmkr_query($sqlUpdateFecha,$conn) or die ("Error al actualiza la fecha de latarea despues del calculo semana".phpmkr_error()."sql;".$sqlUpdateFecha);
				echo "UPDATAE TREA".$sqlUpdateFecha."<br>";
				
		}else{
			$x_fecha_ejecuta_act  = $x_fecha_tarea_f;
				$temptime = strtotime($x_fecha_ejecuta_act);	
				$temptime = DateAdd('w',$x_dias_espera,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if($x_dia == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$x_fecha_ejecuta_act = $fecha_venc;
				
				$sqlUpdateFecha = "UPDATE crm_tarea SET  fecha_ejecuta = \"$x_fecha_ejecuta_act\" WHERE crm_tarea_id = $x_tarea_id_f ";
				$rsUpdateFecha = phpmkr_query($sqlUpdateFecha,$conn) or die ("Error al actualiza la fecha de latarea despues del calculo semana".phpmkr_error()."sql;".$sqlUpdateFecha);
			
			}
		#echo "FECHA EJECUTA".$x_fecha_ejecuta_act;
		#####################################################################################################################
		###############################    TAREAS DIARIAS GESTOR DE COBRANZA    ##############################################
		#####################################################################################################################
		$x_tareas_asignadas_del_caso = 0;
		// primero verifamos que la tarea aun no este en la lista, es decir, que se trate de la primera vez que entra al cliclo para este credito.
		$sqlBuscaatreaAsignada = "SELECT COUNT(*) AS atreas_asignadas FROM `tarea_diaria_gestor` WHERE fecha_ingreso =  \"$currdate\" AND gestor_id = $x_gestor_id ";
		$sqlBuscaatreaAsignada .= " AND `caso_id` = $x_caso_id_f";
		$rsBuscatareaAsignada = phpmkr_query($sqlBuscaatreaAsignada,$conn) or die("Erro al buscar atrea".phpmkr_error()."sql:".$sqlBuscaatreaAsignada);
	#	echo "BUSCA TAREAS".$sqlBuscaatreaAsignada."<BR>";
		$rowBuscaTareaAsignada = phpmkr_fetch_array($rsBuscatareaAsignada);
		$x_tareas_asignadas_del_caso = $rowBuscaTareaAsignada["atreas_asignadas"];
	#	echo "TAREAS ASIGNADAS ".$x_tareas_asignadas_del_caso."<br>";
		//se inserta la tarea en la lista de las actividades diarias del promotor
		if ($x_tareas_asignadas_del_caso < 1){
		$sqlInsertListaTarea = "INSERT INTO `tarea_diaria_gestor`";
		$sqlInsertListaTarea .= " (`tarea_diaria_gestor_id`, `gestor_id`, `zona_id`, `dia_semana`, `fecha_ingreso`, `fecha_lista`, `caso_id`, ";
		$sqlInsertListaTarea .= " `tarea_id`, `reingreso`, `fase`, `status_tarea`, `credito_id`) ";
		$sqlInsertListaTarea .= "VALUES (NULL, $x_gestor_id, $x_dia_zona_f , $x_dia_zona_f, \"$currdate\",\"$x_fecha_tarea_f\", $x_caso_id_f, $x_tarea_id_f, '0', '2', '1', $x_credito_id);";
		$rsInsertListaTarea = phpmkr_query($sqlInsertListaTarea,$conn)or die("Error al insertar en lista diaria tareas  ----".phpmkr_error()."sql:".$sqlInsertListaTarea);	 
		}
		
		
			}// tareas registradas = 0
			
		
		
		}// la fecha de la promesa de pago ya vencio
				}//promesa de pago mayor a 0
		
		}// ya se tiene la carat 2; ya no entraa ciclo carta dos; y aun no existe la carta 3	
		
		
	if(($x_carta_3 > 0) && ($x_ciclo_carta_3 == 1) && ($x_carta_D == 0)){
		// ya se le genero la carta 3
		// ya esta en fase 7 Y AQUI SE CICLA
		// ya tiene una o mas promesas de pago...
		// SE genera una promesa de pagao al promotor de credito (angelica, monica, jose) 
		// carta 1 ya existe, pero debemos de verificar que la tarea esta vencida o esta cerrada
		// si ya se vencio la tarea o ya se netrego la carta entonces si ya podemos programar la sigueinte tarea
		// de lo contrario si el credito tuvuera mas de dos vencidos.. generaria la carata y tambien generaria la PP 
		
		# PONEMOS LA CONDICIONES DEL CICLO		
		#verificamos que no tenga ninguna PP con status de pendiente;	
		$x_carta_3PP = 0;
		$sqlCarta3PP = "SELECT COUNT(*) AS carta_3_PP FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND crm_tarea_tipo_id = 5 AND orden= 13 AND crm_tarea_status_id = 1";
		$rsCarta3PP = phpmkr_query($sqlCarta3PP,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCarta3PP);
		$rowCarata3PP = phpmkr_fetch_array($rsCarta3PP);
		$x_carta_3PP = $rowCarata3PP["carta_3_PP"];
		// se agrega la tarea siemore y cuando no exista una tarea igaul en status pendiente	
		$sqlCarta3PP_vencida = "SELECT COUNT(*) AS carta_3_PP FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND crm_tarea_tipo_id = 5 AND orden= 13 AND crm_tarea_status_id in (2,3)";
		$rsCarta3PP_vencida = phpmkr_query($sqlCarta3PP_vencida,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCarta3PP_vencida);
		$rowCarata3PP_vencida = phpmkr_fetch_array($rsCarta3PP_vencida);
		$x_carta_3PP_vencida = $rowCarata3PP_vencida["carta_3_PP"];
		
		if(($x_carta_3PP < 1) && ($x_carta_3PP_vencida < 1)){
			
		#SELECCIONAMOS LOS DATOS DE  LA TAREA
		
		$sSqlWrk = "
		SELECT *
		FROM 
			crm_playlist
		WHERE 
			crm_playlist.crm_caso_tipo_id = 3
			AND crm_playlist.orden = 13"; // PROMESA DE PAGO CARTA 3
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_crm_playlist_id = $datawrk["crm_playlist_id"];
		$x_prioridad_id = $datawrk["prioridad_id"];	
		$x_asunto = $datawrk["asunto"];	
		$x_descripcion = $datawrk["descripcion"];		
		$x_tarea_tipo_id = $datawrk["tarea_fuente"];		
		$x_orden = $datawrk["orden"];	
		$x_dias_espera = $datawrk["dias_espera"];		
		@phpmkr_free_result($rswrk);
	
	
		//Fecha Vencimiento
		$temptime = strtotime($currdate);	
		$temptime = DateAdd('w',$x_dias_espera,$temptime);
		$fecha_venc = strftime('%Y-%m-%d',$temptime);			
		$x_dia = strftime('%A',$temptime);
		if($x_dia == "SUNDAY"){
			$temptime = strtotime($fecha_venc);
			$temptime = DateAdd('w',1,$temptime);
			$fecha_venc = strftime('%Y-%m-%d',$temptime);
		}
		$temptime = strtotime($fecha_venc);
	
	
	
		$x_origen = 1;
		$x_bitacora = "Cartera Vencida - (".FormatDateTime($currdate,7)." - $currtime)";
	
		$x_bitacora .= "\n";
		$x_bitacora .= "$x_asunto - $x_descripcion ";	


		$sSqlWrk = "
		SELECT cliente.cliente_id
		FROM 
			cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id join solicitud on solicitud.solicitud_id = solicitud_cliente.solicitud_id join credito on credito.solicitud_id = solicitud.solicitud_id
		WHERE 
			credito.credito_id = $x_credito_id
		LIMIT 1
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_cliente_id = $datawrk["cliente_id"];
		@phpmkr_free_result($rswrk);
		
		
		
		
		###############################################################################
		######################  aqui el usuario del sr RAUL     #######################
		###############################################################################		
		# seleccionamos los datos del promotor.
		$x_promotor_de_gestor = 0;
		$sqlpromotor = "SELECT promotor_id FROM solicitud WHERE solicitud_id = $x_solicitud_id_c";
		$rspromotor = phpmkr_query($sqlpromotor,$conn) or die ("Error la selccionar le promotor-id".phpmk_error()."sql:".$sqlpromotor);
		$rowpromotor = phpmkr_fetch_array($rspromotor);
		mysql_free_result($rspromotor);	
		$x_promotor_de_gestor = $rowpromotor["promotor_id"];
		
		$sqlGestor = "SELECT gestor_id FROM gestor_promotor WHERE promotor_id = $x_promotor_de_gestor ";
		$rsGestor = phpmkr_query($sqlGestor,$conn)or die("Error al seleccionar el gestor".phpmkr_error()."sql:".$sqlGestor);
		$rowGestor = phpmkr_fetch_array($rsGestor);
		mysql_free_result($rsGestor);
		$x_gestor_id = $rowGestor["gestor_id"];
		
		// seleccionamos el usuario del gestor
		$sqlUsuario = "SELECT usuario_id  FROM gestor WHERE gestor_id = $x_gestor_id";
		$rsusuer = phpmkr_query($sqlUsuario,$conn)or die("Erro en usuario".phpmkr_query()."sql: ".$sqlUsuario);
		$rowuser = phpmkr_fetch_array($rsusuer);	
		mysql_free_result($rsusuer);
		$x_usuario_id = $rowuser["usuario_id"];
		
			
			



		if(($x_crm_caso_id > 0)){

			$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 2, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
		
			$x_result = phpmkr_query($sSql, $conn);
			$x_tarea_id = mysql_insert_id();
	 echo "promesa de pago carata 3".$sSql."<br>";
			$sSqlWrk = "
			SELECT 
				comentario_int
			FROM 
				credito_comment
			WHERE 
				credito_id = ".$x_credito_id."
			LIMIT 1
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_comment_ant = $datawrk["comentario_int"];
			@phpmkr_free_result($rswrk);
	
	
			if(empty($x_comment_ant)){
				$sSql = "insert into credito_comment values(0, $x_credito_id, '$x_bitacora', NULL)";
			}else{
				$x_bitacora = $x_comment_ant . "\n\n------------------------------\n" . $x_bitacora;
				$sSql = "UPDATE credito_comment set comentario_int = '$x_bitacora' where credito_id = $x_credito_id";
			}
	
			phpmkr_query($sSql, $conn);
		
		
		// buscar el dia que se asignara la tarea al prmotor
		########################################################################################################################################################
		############################################################ TAREAS DIARIAS PARA GESTORES	############################################################
		########################################################################################################################################################
		##credamos las lista de los promotores con la tareas diarias.		
		## seleccionaos la soza de la solcitud		
		$sqlZonaId = "SELECT dia FROM  zona_gestor JOIN solicitud ON solicitud.promotor_id = zona_gestor.promotor_id WHERE solicitud.solicitud_id = $x_solicitud_id_c";
		$rsZonaId = phpmkr_query($sqlZonaId,$conn) or die ("Error al seleccionar el dia de la zona".phpmkr_error()."sql:".$sqlZonaId);
		$rowZonaId = phpmkr_fetch_array($rsZonaId);
		$x_dia_zona = $rowZonaId["dia"];
		
		$x_fecha_tarea_f = $currdate;
		$x_dia_zona_f = $x_dia_zona;
		$x_promotor_id_f = $x_promotor_id_c ;
		$x_caso_id_f = $x_crm_caso_id;
		$x_tarea_id_f = $x_tarea_id;
	
		
		
		#tenemos la fecha de hoy  --> hoy es miercoles 30 de enero
		# se debe buscar la fecha de la zona --> si la zona fuera 5.. la fecha se deberia cambiar a viernes 1 de febrero
		# o si la zona fuera  2 como las tareas de la zona 2 ya fueron asignadas.. se debe buscar la fecha de la zona dos de la sig semana
		
		$sqlDiaSemana = "SELECT DAYOFWEEK('$x_fecha_tarea_f') AS  dia";
		$rsDiaSemana = phpmkr_query($sqlDiaSemana,$conn) or die ("Error al seleccionar el dia de la semana".phpmkr_error()."sql:".$sqlDiaSemana);
		$rowDiaSemana = phpmkr_fetch_array($rsDiaSemana);
		$x_dia_de_semana_en_fecha = $rowDiaSemana["dia"];
		echo "fecha day of week".$x_dia_de_semana_en_fecha ."<br>";
		
		if($x_dia_de_semana_en_fecha != $x_dia_zona_f ){
			// el dia de la zona y el dia de la fecha no es el mismo se debe incrementar la fecha hasta llegar al mismo dia
			
			echo "LOS DIAS SON DIFERENTES<BR>";
		//	$x_dia_de_semana_en_fecha = 1;
		//	$x_dia_zona_f= 5;
		//	$x_fecha_tarea_f = "2013-01-29";
			if($x_dia_de_semana_en_fecha < $x_dia_zona_f){
				// la fecha es mayor dia_en _fecha = 2; dia_zona = 5
				$x_dias_faltantes = $x_dia_zona_f - $x_dia_de_semana_en_fecha;	
				//hacemos el incremento en la fecha		
				//Fecha de tarea para la lista
				
				echo "fecha dia------".$x_fecha_tarea_f."<br>";
				$temptime = strtotime($x_fecha_tarea_f);	
				$temptime = DateAdd('w',$x_dias_faltantes,$temptime);
				$fecha_nueva = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				$x_fecha_tarea_f = $fecha_nueva;	
				echo "nueva fecha...ESTA semana<br> dias faltantes".$x_dias_faltantes."<br>";			
				}else{
					// el dia dela fecha es mayor al dia de la zona...las tareas asignadas para esa zona ya pasaron; porque ya paso el dia de esa zona
					// se debe asigna la tarea para la semana sig el la fecha de la zona.
					$x_dias_faltantes = (7- $x_dia_de_semana_en_fecha)+ $x_dia_zona_f;
					//hacemos el incremento en la fecha		
					//Fecha de tarea para la lista
					$temptime = strtotime($x_fecha_tarea_f);	
					$temptime = DateAdd('w',$x_dias_faltantes,$temptime);
					$fecha_nueva = strftime('%Y-%m-%d',$temptime);			
					$x_dia = strftime('%A',$temptime);
					$x_fecha_tarea_f = $fecha_nueva;	
					echo "nueva fecha...sigueinte semana<br> dias faltantes".$x_dias_faltantes."<br>";
					echo "DIA DE LA SEMANA EN FECHA".$x_dia_de_semana_en_fecha."<br>";
					echo "dia zona".$x_dia_zona_f;					
					}
			}
		
		$x_dias_agregados = calculaSemanasGestor($conn, $x_fecha_tarea_f, $x_dia_zona_f,$x_promotor_id , $x_caso_id_f, $x_tarea_id_f, $x_gestor_id);
		
		echo "dias agragados".$x_dias_agregados." ";
		
		// se gragan los dias que faltan si es que es mayor de 0
		if($x_dias_agregados > 0){
					$temptime = strtotime($x_fecha_tarea_f);	
					$temptime = DateAdd('w',$x_dias_agregados,$temptime);
					$fecha_nueva = strftime('%Y-%m-%d',$temptime);			
					$x_fecha_tarea_f = $fecha_nueva;
				// se hizo el incremento en la fecha				
				//se actualiza la tarea con la fecha nueva
				$x_fecha_ejecuta_act = $x_fecha_tarea_f;
				
				$temptime = strtotime($x_fecha_ejecuta_act);	
				$temptime = DateAdd('w',$x_dias_espera,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if($x_dia == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$x_fecha_ejecuta_act = $fecha_venc;
				
				$sqlUpdateFecha = "UPDATE crm_tarea SET  fecha_ejecuta = \"$x_fecha_ejecuta_act\" WHERE crm_tarea_id = $x_tarea_id_f ";
				$rsUpdateFecha = phpmkr_query($sqlUpdateFecha,$conn) or die ("Error al actualiza la fecha de latarea despues del calculo semana".phpmkr_error()."sql;".$sqlUpdateFecha);
				echo "UPDATAE TREA".$sqlUpdateFecha."<br>";
				
		}else{
			$x_fecha_ejecuta_act  = $x_fecha_tarea_f;
			$temptime = strtotime($x_fecha_ejecuta_act);	
				$temptime = DateAdd('w',$x_dias_espera,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if($x_dia == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$x_fecha_ejecuta_act = $fecha_venc;
				
				$sqlUpdateFecha = "UPDATE crm_tarea SET  fecha_ejecuta = \"$x_fecha_ejecuta_act\" WHERE crm_tarea_id = $x_tarea_id_f ";
				$rsUpdateFecha = phpmkr_query($sqlUpdateFecha,$conn) or die ("Error al actualiza la fecha de latarea despues del calculo semana".phpmkr_error()."sql;".$sqlUpdateFecha);
			
			}
		echo "FECHA EJECUTA".$x_fecha_ejecuta_act;
		#####################################################################################################################
		#####################################  TAREAS DIARIAS GESTOR  #######################################################
		#####################################################################################################################
		
		// primero verifamos que la tarea aun no este en la lista, es decir, que se trate de la primera vez que entra al cliclo para este credito.
		$sqlBuscaatreaAsignada = "SELECT COUNT(*) AS atreas_asignadas FROM `tarea_diaria_gestor` WHERE fecha_ingreso =  \"$currdate\" AND gestor_id = $x_gestor_id ";
		$sqlBuscaatreaAsignada .= " AND `caso_id` = $x_caso_id_f";
		$rsBuscatareaAsignada = phpmkr_query($sqlBuscaatreaAsignada,$conn) or die("Erro al buscar atrea".phpmkr_error()."sql:".$sqlBuscaatreaAsignada);
		echo "BUSCA TAREAS".$sqlBuscaatreaAsignada."<BR>";
		$rowBuscaTareaAsignada = phpmkr_fetch_array($rsBuscatareaAsignada);
		$x_tareas_asignadas_del_caso = $rowBuscaTareaAsignada["atreas_asignadas"];
		echo "TAREAS ASIGNADAS ".$x_tareas_asignadas_del_caso."<br>";
		//se inserta la tarea en la lista de las actividades diarias del promotor
		if ($x_tareas_asignadas_del_caso < 1){
		$sqlInsertListaTarea = "INSERT INTO `tarea_diaria_gestor`";
		$sqlInsertListaTarea .= " (`tarea_diaria_gestor_id`, `gestor_id`, `zona_id`, `dia_semana`, `fecha_ingreso`, `fecha_lista`, `caso_id`, ";
		$sqlInsertListaTarea .= " `tarea_id`, `reingreso`, `fase`, `status_tarea`, `credito_id`) ";
		$sqlInsertListaTarea .= "VALUES (NULL, $x_gestor_id, $x_dia_zona_f , $x_dia_zona_f, \"$currdate\",\"$x_fecha_tarea_f\", $x_caso_id_f, $x_tarea_id_f, '0', '6', '1', $x_credito_id);";
		$rsInsertListaTarea = phpmkr_query($sqlInsertListaTarea,$conn)or die("Error al insertar en lista diaria tareasqqq".phpmkr_error()."sql:".$sqlInsertListaTarea);	 
		}
		
		
		
		
		
		
		}// CASO CRM > 0 DE CARTA 1
		
		}// SI NO EXISTE LA PROMESA DE PAGO PARA CARAT 1
		
		}// if carta3 ya existe, pero entra al ciclo de pp carta 3	
		
		
	if(($x_carta_3 > 0) && ($x_ciclo_carta_3 == 0) && ($x_carta_D == 0)){
		
		
		
			$sqlCarta3 = "SELECT COUNT(*) AS crm_tarea_id FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND  crm_tarea_tipo_id = 10 AND orden= 12";
		$rsCarta3 = phpmkr_query($sqlCarta3,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCarta3);
		$rowCarata3 = phpmkr_fetch_array($rsCarta3);
		$x_carta_3_tarea_id = $rowCarata3["crm_tarea_id"];
		if($x_carta_3_tarea_id >0 ){
			//buscamos que ya tenga Una promesa de pago y que este vencida para entrar al ciclo			
			$sqlPPC3 = "SELECT * FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id  AND crm_tarea_tipo_id = 5 AND orden = 13 ";
			$rsppc3 = phpmkr_query($sqlPPC3,$conn)or die ("Error al seleccioanr la pp c2".phpmkr_error()."sql:".$sqlPPC3);
			$rowppc3 = phpmkr_fetch_array($rsppc3);
			$x_fecha_registro_tarea = $rowppc3["fecha_registro"];
			$x_tarea_ppc3 =$rowppc3["crm_tarea_id"];
			$x_caso_ppc3 = $rowppc3["crm_caso_id"];			
			if($x_caso_ppc3> 0){
				// la promesa de pago esta registrada; buscamos la fecha de pagoo de la promesa
				$sqlcrm_tarea_cv3 = "SELECT COUNT(*) AS promesas, promesa_pago  FROM crm_tarea_cv WHERE crm_tarea_id = $x_tarea_ppc1 ";
				$rscrm_tarea_cv3 = phpmkr_query($sqlcrm_tarea_cv3,$conn)or die("Error al selecionar la fecha de promesa de pago".phpmkr_error()."sql:".$sqlcrm_tarea_cv3);
				$rowcrm_tarea_cv3 = phpmkr_fetch_array($rscrm_tarea_cv3);
				$x_promesas_c3 = $rowcrm_tarea_cv3["promesas"];
				$x_fecha_promesa_pago_c3 = $rowcrm_tarea_cv3["crm_tarea_cv"]; 			
				}			
			}	
			$x_hoy_z = date("Y-m-d");
			if($x_promesas_c3 > 0){
				if($x_hoy_z > $x_fecha_promesa_pago_c3){					
					
		
	###########################################################################################################################################
	###########################################################################################################################################
	###################################################            CARTA DEMANDA           ####################################################
	###########################################################################################################################################
	###########################################################################################################################################
	//etapa 8 CARTA DEMANDA			
	
		
	# SI Y SOLO SI NO EXISTE UNA TREA DE CARTA 3 GENERADA PARA ESE DIA;
	
	$SQLCARTA3 = "SELECT COUNT(*) AS carta3 FROM crm_tarea WHERE fecha_registro = \"$currdate\" AND crm_caso_id = $x_crm_caso_id AND orden = 20 AND crm_tarea_tipo_id= 12 ";	
	#echo "carata 3".$SQLCARTA3."<br>";
	$RSCARTA3 = phpmkr_query($SQLCARTA3,$conn)or die ("Error al buscar c3 existente".phpmkr_error()."sql:". $SQLCARTA3);
	$ROWCARA3 = phpmkr_fetch_array($RSCARTA3);	
	$x_carta3_existente = $ROWCARA3["carta3"] +0;	
#	echo "carta 3 existe".$x_carta3_existente."--<br>";	
			// seleccionamos los datos para la nueva tarea
			
			# si no existe carata 3 entonces si insertamos la demanda
		if($x_carta3_existente < 1)	{	
				$sSqlWrk = "SELECT *
							FROM 
							crm_playlist
							WHERE 
							crm_playlist.crm_caso_tipo_id = 3
							AND crm_playlist.orden = 20 "; // orden 20 DEMANDA	
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_crm_playlist_id = $datawrk["crm_playlist_id"];
				$x_prioridad_id = $datawrk["prioridad_id"];	
				$x_asunto = $datawrk["asunto"];	
				$x_descripcion = $datawrk["descripcion"];		
				$x_tarea_tipo_id = $datawrk["tarea_fuente"];		
				$x_orden = $datawrk["orden"];	
				$x_dias_espera = $datawrk["dias_espera"];		
				@phpmkr_free_result($rswrk);
				
				
				
				//Fecha Vencimiento
				$temptime = strtotime($currdate);	
				$temptime = DateAdd('w',$x_dias_espera,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if($x_dia == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$temptime = strtotime($fecha_venc);
	
	
	
				$x_origen = 1;  // el origen se podria cambiar a responsable de sucursal.
				$x_bitacora = "Cartera Vencida  SE PASA A ETAPA 8 DEMANDA- (".FormatDateTime($currdate,7)." - $currtime)";
			
				$x_bitacora .= "\n";
				$x_bitacora .= "$x_asunto - $x_descripcion ";	


				$sSqlWrk = "
		SELECT cliente.cliente_id
		FROM 
			cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id join solicitud on solicitud.solicitud_id = solicitud_cliente.solicitud_id join credito on credito.solicitud_id = solicitud.solicitud_id
		WHERE 
			credito.credito_id = $x_credito_id
		LIMIT 1
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_cliente_id = $datawrk["cliente_id"];
		
		@phpmkr_free_result($rswrk);


		
		
		// seleccionamos el usuario del GESTOR JURIDICO EL RESPONSABLE DE LA TAREA ES EL GESTOR JURIDICO HASTA AHORA Rodirigo
		
				
		$sSqlWrk = "
		SELECT usuario_id
		FROM 
			usuario
		WHERE 
			usuario_rol_id = 13"; // el usuario juridica // es este momento solo tenemos a rodrigo registrado.
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo "sql uuario demanda".$sSqlWrk."<br>";
		$x_usuario_id = $datawrk["usuario_id"];
		@phpmkr_free_result($rswrk);
		
	
			// se debe verificar antes de ingresar la tarea que no exista ninguna tarea para el caso de este tipo
			$sqlBuscaTarea =  "SELECT COUNT(*) AS tareas_exist from  crm_tarea where crm_caso_id = $x_crm_caso_id AND crm_tarea_tipo_id = $x_tarea_tipo_id ";
			$sqlBuscaTarea .= " AND crm_tarea_status_id IN (1,2) AND destino = $x_usuario_id";	
			$rsBuscaTarea = phpmkr_query($sqlBuscaTarea,$conn) or die ("Erro al insertar en tarea 3".phpmkr_error()."sql:".$sqlBuscaTarea);
			$rowBuscaTarea = phpmkr_fetch_array($rsBuscaTarea);
			$x_tareas_abiertas = $rowBuscaTarea["tareas_exist"];
			
		#	echo "<br><br> TREAS ABIERTAS ".$x_tareas_abiertas."<BR><BR>".$sqlBuscaTarea."<BR><BR>";
			if($x_tareas_abiertas == 0){
			$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 7, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
			// la tarea la ejecuta el asesor de crédito
			$rs = phpmkr_query($sSql,$conn) or die("Error al seleccionar insertar en tarea para promotor".phpmkr_error()."sql:".$sSql);
		echo "INSERTA DEMANDA<BR> ".$sSql."<BR>";
			//$x_result = phpmkr_query($sSql, $conn);
			$x_tarea_id = mysql_insert_id();
	
			$sSqlWrk = "
			SELECT 
				comentario_int
			FROM 
				credito_comment
			WHERE 
				credito_id = ".$x_credito_id."
			LIMIT 1
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_comment_ant = $datawrk["comentario_int"];
			@phpmkr_free_result($rswrk);
	
	
			if(empty($x_comment_ant)){
				$sSql = "insert into credito_comment values(0, $x_credito_id, '$x_bitacora', NULL)";
			}else{
				$x_bitacora = $x_comment_ant . "\n\n------------------------------\n" . $x_bitacora;
				$sSql = "UPDATE credito_comment set comentario_int = '$x_bitacora' where credito_id = $x_credito_id";
			}
	
			phpmkr_query($sSql, $conn);
		
			}// tareas registradas = 0
			
		}// ya se vencio la fecha de promesa de pago				
			}//existe la promesa de pago
		
	}//carata 3 no existe
		
		}// SE GENERA LA DEMANDA Y SE CAMBIA TAREA A RODRIGO		
		
	if(($x_carta_D > 0) && ($x_ciclo_carta_D == 1)){
		// ya se le genero la carta D
		// ya esta en fase 9 Y AQUI SE CICLA
		// ya tiene una o mas promesas de pago...
		// SE genera una promesa de pagao al promotor de credito (angelica, monica, jose) 
		// carta 1 ya existe, pero debemos de verificar que la tarea esta vencida o esta cerrada
		// si ya se vencio la tarea o ya se netrego la carta entonces si ya podemos programar la sigueinte tarea
		// de lo contrario si el credito tuvuera mas de dos vencidos.. generaria la carata y tambien generaria la PP 
		
		# PONEMOS LA CONDICIONES DEL CICLO		
		#verificamos que no tenga ninguna PP con status de pendiente;	
		
		echo "ENTRO A PROMESA DE PAGO DEMANADA -------------------------<BR>";
		$x_carta_DPP = 0;
		$sqlCartaDPP = "SELECT COUNT(*) AS carta_D_PP FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND crm_tarea_tipo_id = 5 AND orden= 24 AND crm_tarea_status_id = 1";
		$rsCartaDPP = phpmkr_query($sqlCartaDPP,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCartaDPP);
		$rowCarataDPP = phpmkr_fetch_array($rsCartaDPP);
		$x_carta_DPP = $rowCarataDPP["carta_D_PP"]+0;
		// se agrega la tarea siemore y cuando no exista una tarea igaul en status pendiente	
		
		if(($x_carta_DPP < 1)){
			
		#SELECCIONAMOS LOS DATOS DE  LA TAREA
		
		$sSqlWrk = "
		SELECT *
		FROM 
			crm_playlist
		WHERE 
			crm_playlist.crm_caso_tipo_id = 3
			AND crm_playlist.orden = 24"; // PROMESA DE PAGO CARTA D
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_crm_playlist_id = $datawrk["crm_playlist_id"];
		$x_prioridad_id = $datawrk["prioridad_id"];	
		$x_asunto = $datawrk["asunto"];	
		$x_descripcion = $datawrk["descripcion"];		
		$x_tarea_tipo_id = $datawrk["tarea_fuente"];		
		$x_orden = $datawrk["orden"];	
		$x_dias_espera = $datawrk["dias_espera"];		
		@phpmkr_free_result($rswrk);
	
	
		//Fecha Vencimiento
		$temptime = strtotime($currdate);	
		$temptime = DateAdd('w',$x_dias_espera,$temptime);
		$fecha_venc = strftime('%Y-%m-%d',$temptime);			
		$x_dia = strftime('%A',$temptime);
		if($x_dia == "SUNDAY"){
			$temptime = strtotime($fecha_venc);
			$temptime = DateAdd('w',1,$temptime);
			$fecha_venc = strftime('%Y-%m-%d',$temptime);
		}
		$temptime = strtotime($fecha_venc);
	
	
	
		$x_origen = 1;
		$x_bitacora = "Cartera Vencida - (".FormatDateTime($currdate,7)." - $currtime)";
	
		$x_bitacora .= "\n";
		$x_bitacora .= "$x_asunto - $x_descripcion ";	


		$sSqlWrk = "
		SELECT cliente.cliente_id
		FROM 
			cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id join solicitud on solicitud.solicitud_id = solicitud_cliente.solicitud_id join credito on credito.solicitud_id = solicitud.solicitud_id
		WHERE 
			credito.credito_id = $x_credito_id
		LIMIT 1
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_cliente_id = $datawrk["cliente_id"];
		@phpmkr_free_result($rswrk);
		
		
		
		
		
		$sSqlWrk = "
		SELECT usuario_id
		FROM 
			usuario
		WHERE 
			usuario_rol_id = 13"; // el usuario juridica // es este momento solo tenemos a rodrigo registrado.
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_usuario_id = $datawrk["usuario_id"];
		@phpmkr_free_result($rswrk);
		
		// seleccionamos el usuario del juridico
		
		
		
			
			



		if(($x_crm_caso_id > 0)){

			$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 2, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
		
			$x_result = phpmkr_query($sSql, $conn);
			$x_tarea_id = mysql_insert_id();
	
			$sSqlWrk = "
			SELECT 
				comentario_int
			FROM 
				credito_comment
			WHERE 
				credito_id = ".$x_credito_id."
			LIMIT 1
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_comment_ant = $datawrk["comentario_int"];
			@phpmkr_free_result($rswrk);
	
	
			if(empty($x_comment_ant)){
				$sSql = "insert into credito_comment values(0, $x_credito_id, '$x_bitacora', NULL)";
			}else{
				$x_bitacora = $x_comment_ant . "\n\n------------------------------\n" . $x_bitacora;
				$sSql = "UPDATE credito_comment set comentario_int = '$x_bitacora' where credito_id = $x_credito_id";
			}
	
			phpmkr_query($sSql, $conn) or die("Error al actualiza los comentarios de la bitacora".phpmkr_error()."sql:".$sSql);		
		
		
		}// CASO CRM > 0 DE CARTA 1
		
		}// SI NO EXISTE LA PROMESA DE PAGO PARA CARAT 1
		
		}// if carta3 ya existe, pero entra al ciclo de pp carta 3	
	
	if( ($x_carta_D > 0) && ($x_ciclo_carta_D == 0))	{
		// se agrega el caso a comite de mora
		
		
		##################################################################################
		##################################################################################
		########################     codigo comite de mora      ##########################
		##################################################################################
		##################################################################################
		
		// primero verificamos que no existe ese caso en comite de mora
		$x_comite_i = 0;
		$sqBuscaComite =  "SELECT COUNT(*) AS insertado FROM comite_mora WHERE credito_id = $x_credito_id" ;
		$rsBuscaComite = phpmkr_query($sqBuscaComite,$conn)or die ("error al seleccionar de comite de mora".phpmkr_error()."sql:".$sqBuscaComite);
		$rowBuscacomite = phpmkr_fetch_array($rsBuscaComite);
		$x_comite_i = $rowBuscacomite["insertado"] + 0;
		if($x_comite_i < 1){
		$sqlInsercomite = "INSERT INTO `comite_mora` (`comite_mora_id`, `credito_id`, `fecha_ingreso`, `otro`)";
		$sqlInsercomite .= " VALUES (NULL, $x_credito_id, \"$currdate\", NULL)";
		$rsInsertcomite = phpmkr_query($sqlInsercomite,$conn) or die("WError al insertar en comite mora".phpmkr_error()."sql:".$sqlInsercomite);
		}
		
		}
		
	}// si no hay tatras pendientes del caso
	}// else caso abierto
	}// dias vencido mayor a dias de gracia para CRM





}// fin while moratorios







// ************************************************   SEGUIMIENTO DE CREDITOS

//mitad del credito total venc 7 / 2

/* 
1 selecciona todos los venc del dia de hoy
2 cuenta total de vencimientos y compara (totvenc / 2) = num_venc o (totvenc / 2)-1 = num_venc o (totvenc / 2) + 1 = num_venc
3 valida que no tenga tareas de seguimiento a mitad de credito ya registrada en crm
crm_caso_tipo_id = 2
crm_tarea_tipo_id = 3
orden = 2
crm_playlist_id = 17
destino_id = 2

*/


// LA FECHA DEBE SER UN DIA HABIL DESPUES DE LA MITAD DEL CREDITO.

// la fecha se queda igual, pero solo aplica a los que no tiene pagos vencidos. 

// FECHA DE LA LLAMADA
$temptime = strtotime($currdate);	
$temptime = DateAdd('w',1,$temptime);
$fecha_llamada = strftime('%Y-%m-%d',$temptime);			
$x_dia = strftime('%A',$temptime);
if($x_dia == "SUNDAY"){
	$temptime = strtotime($fecha_llamada);
	$temptime = DateAdd('w',1,$temptime);
	$fecha_llamada = strftime('%Y-%m-%d',$temptime);
}
$temptime = strtotime($fecha_llamada);

//echo  "dia actual".$currdate;
// fin fecha llamada



$sSqlWrk = "select * from vencimiento where fecha_vencimiento = '$currdate'";
$rswrkct = phpmkr_query($sSqlWrk,$conn) or exit();
while($datawrkct = phpmkr_fetch_array($rswrkct)){
	$x_credito_id = $datawrkct["credito_id"];	
	$x_vencimiento_id = $datawrkct["vencimiento_id"];		
	$x_vencimiento_num = $datawrkct["vencimiento_num"];			

	$sSqlWrk = "SELECT max(vencimiento_num) as venc_num_tot FROM vencimiento WHERE credito_id = ".$x_credito_id;
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$datawrk = phpmkr_fetch_array($rswrk);
	$x_venc_num_tot = $datawrk["venc_num_tot"];
	$x_mitad_credito = intval($x_venc_num_tot / 2);
	@phpmkr_free_result($rswrk);

	if(($x_mitad_credito == $x_vencimiento_num) || (($x_mitad_credito - 1) == $x_vencimiento_num) || (($x_mitad_credito + 1) == $x_vencimiento_num)){
		
		//echo "mitad del credito <br>";
	$sqlVencidos = "select count(*)as vencidos from vencimiento where credito_id = $x_credito_id and `vencimiento_status_id` = 3";
	$responseV = phpmkr_query($sqlVencidos,$conn) or die("error en vencidos");
	$rowVencidos = phpmkr_fetch_array($responseV);
	$x_pagos_vencidos = $rowVencidos["vencidos"];
	phpmkr_free_result($rowVencidos);
		
		
	// si tiene pagos vencidos no se hace la llamada
	if($x_pagos_vencidos == 0 ){
	// valida que no haya ya un caso abierto para este credito
	
		$sSqlWrk = "
		SELECT count(*) as caso_abierto
		FROM 
			crm_caso join crm_tarea
			on crm_tarea.crm_caso_id = crm_caso.crm_caso_id
		WHERE 
			crm_caso.crm_caso_tipo_id = 2
			AND crm_caso.crm_caso_status_id = 1
			AND crm_caso.credito_id = $x_credito_id
			AND crm_tarea.orden = 2
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_caso_abierto = $datawrk["caso_abierto"];		
		@phpmkr_free_result($rswrk);
		
		if($x_caso_abierto == 0){
			
			$sSqlWrk = "
			SELECT *
			FROM 
				crm_playlist
			WHERE 
				crm_playlist.crm_caso_tipo_id = 2
				AND crm_playlist.orden = 2
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_crm_playlist_id = $datawrk["crm_playlist_id"];
			$x_prioridad_id = $datawrk["prioridad_id"];	
			$x_asunto = $datawrk["asunto"];	
			$x_descripcion = $datawrk["descripcion"];		
			$x_tarea_tipo_id = $datawrk["tarea_fuente"];		
			$x_orden = $datawrk["orden"];	
			$x_dias_espera = $datawrk["dias_espera"];		
			$x_destino_id = $datawrk["destino_id"];					
			@phpmkr_free_result($rswrk);
		
/*		
			//Fecha Vencimiento
			$temptime = strtotime($currdate);	
			$temptime = DateAdd('w',$x_dias_espera,$temptime);
			$fecha_venc = strftime('%Y-%m-%d',$temptime);			
			$x_dia = strftime('%A',$temptime);
			if($x_dia == "SUNDAY"){
				$temptime = strtotime($fecha_venc);
				$temptime = DateAdd('w',1,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);
			}
			$temptime = strtotime($fecha_venc);
*/		
		
		
			$x_origen = 1;
			$x_bitacora = "Seguimiento - (".FormatDateTime($currdate,7)." - $currtime)";
		
			$x_bitacora .= "\n";
			$x_bitacora .= "$x_asunto - $x_descripcion ";	
	
	
			$sSqlWrk = "
			SELECT cliente.cliente_id
			FROM 
				cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id join solicitud on solicitud.solicitud_id = solicitud_cliente.solicitud_id join credito on credito.solicitud_id = solicitud.solicitud_id
			WHERE 
				credito.credito_id = $x_credito_id
			LIMIT 1
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_cliente_id = $datawrk["cliente_id"];
			@phpmkr_free_result($rswrk);
	
	
		
			$sSqlWrk = "
			SELECT usuario_id
			FROM 
				usuario
			WHERE 
				usuario.usuario_rol_id = $x_destino_id
			LIMIT 1
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_usuario_id = $datawrk["usuario_id"];
			@phpmkr_free_result($rswrk);
			
			// aqui se cambia la fecha actual, por la fecha de la llamada 
			$sSql = "INSERT INTO crm_caso values (0,2,1,1,$x_cliente_id,'".$currdate."',$x_origen,$x_usuario_id,'$x_bitacora','".$currdate."',NULL,$x_credito_id)";
		
			$x_result = phpmkr_query($sSql, $conn);
			$x_crm_caso_id = mysql_insert_id();	
	
	
			if($x_crm_caso_id > 0){
				// aqui tambien se cambia la fecha actual por la fecha de la llamada
				$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$currdate',NULL,NULL,NULL, 1, 1, 2, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
			
				$x_result = phpmkr_query($sSql, $conn);
		
				$sSqlWrk = "
				SELECT 
					comentario_int
				FROM 
					credito_comment
				WHERE 
					credito_id = ".$x_credito_id."
				LIMIT 1
				";
				
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_comment_ant = $datawrk["comentario_int"];
				@phpmkr_free_result($rswrk);
		
		
				if(empty($x_comment_ant)){
					$sSql = "insert into credito_comment values(0, $x_credito_id, '$x_bitacora', NULL)";
				}else{
					$x_bitacora = $x_comment_ant . "\n\n------------------------------\n" . $x_bitacora;
					$sSql = "UPDATE credito_comment set comentario_int = '$x_bitacora' where credito_id = $x_credito_id";
				}
		
				phpmkr_query($sSql, $conn);
			}
			
		
		}// caso abierto
	}// pagos vencidos
		
	}//mitad de credito
}



// penultimo pago

/* 
1 selecciona todos los venc del dia de hoy
2 cuenta total de vencimientos y compara (totvenc - 1) = num_venc
3 valida que no tenga tareas de seguimiento a final de credito ya registrada en crm
*/

// la fecha de venciemiente debe ser dos dias mas que la fecha actual.
//Fecha Vencimiento mañana

// cambiar fecha de vencimiento a pasado mañana(2 dias)
$temptime = strtotime($currdate);	
$temptime = DateAdd('w',2,$temptime);
$fecha_venc = strftime('%Y-%m-%d',$temptime);			
$x_dia = strftime('%A',$temptime);
if($x_dia == "SUNDAY"){
	$temptime = strtotime($fecha_venc);
	$temptime = DateAdd('w',1,$temptime);
	$fecha_venc = strftime('%Y-%m-%d',$temptime);
}
$temptime = strtotime($fecha_venc);
//echo "dia actual".$currdate."<br>";
//echo "fecha vencimiento".  ConvertDateToMysqlFormat($fecha_venc);

$sSqlWrk = "select * from vencimiento where fecha_vencimiento = '$fecha_venc' and vencimiento_status_id = 1";
$rswrkct = phpmkr_query($sSqlWrk,$conn) or exit();
while($datawrkct = phpmkr_fetch_array($rswrkct)){
	$x_credito_id = $datawrkct["credito_id"];	
	$x_vencimiento_id = $datawrkct["vencimiento_id"];		
	$x_vencimiento_num = intval($datawrkct["vencimiento_num"]);	
	
	


	$sSqlWrk = "SELECT max(vencimiento_num) as venc_num_tot FROM vencimiento WHERE credito_id = ".$x_credito_id;
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$datawrk = phpmkr_fetch_array($rswrk);
	$x_venc_num_tot = $datawrk["venc_num_tot"];
	$x_penultimo_credito = intval($x_venc_num_tot - 1);
	@phpmkr_free_result($rswrk);

	if($x_venc_num_tot == $x_vencimiento_num){
		
	$sqlVencidos = "select count(*)as vencidos from vencimiento where credito_id = $x_credito_id and `vencimiento_status_id` = 3";
	$responseV = phpmkr_query($sqlVencidos,$conn) or die("error en vencidos");
	$rowVencidos = phpmkr_fetch_array($responseV);
	$x_pagos_vencidos = $rowVencidos["vencidos"];
	
	if($x_pagos_vencidos == 0 ){			
								
	// valida que no haya ya un caso abierto para este credito
		$sSqlWrk = "
		SELECT count(*) as caso_abierto
		FROM 
			crm_caso join crm_tarea
			on crm_tarea.crm_caso_id = crm_caso.crm_caso_id
		WHERE 
			crm_caso.crm_caso_tipo_id = 2
			AND crm_caso.crm_caso_status_id = 1
			AND crm_caso.credito_id = $x_credito_id
			AND crm_tarea.orden = 3
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_caso_abierto = $datawrk["caso_abierto"];		
		@phpmkr_free_result($rswrk);
		
		if($x_caso_abierto == 0){
			
			$sSqlWrk = "
			SELECT *
			FROM 
				crm_playlist
			WHERE 
				crm_playlist.crm_caso_tipo_id = 2
				AND crm_playlist.orden = 3
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_crm_playlist_id = $datawrk["crm_playlist_id"];
			$x_prioridad_id = $datawrk["prioridad_id"];
			// RENOVACION PUNTUAL
			$x_asunto = $datawrk["asunto"];	
			$x_asunto =$x_asunto ."por puntualidad";
			$x_descripcion = $datawrk["descripcion"];		
			$x_tarea_tipo_id = $datawrk["tarea_fuente"];		
			$x_orden = $datawrk["orden"];	
			$x_dias_espera = $datawrk["dias_espera"];		
			$x_destino_id = $datawrk["destino_id"];					
			@phpmkr_free_result($rswrk);
		
/*		
			//Fecha Vencimiento
			$temptime = strtotime($currdate);	
			$temptime = DateAdd('w',$x_dias_espera,$temptime);
			$fecha_venc = strftime('%Y-%m-%d',$temptime);			
			$x_dia = strftime('%A',$temptime);
			if($x_dia == "SUNDAY"){
				$temptime = strtotime($fecha_venc);
				$temptime = DateAdd('w',1,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);
			}
			$temptime = strtotime($fecha_venc);
*/		
		
		
			$x_origen = 1;
			$x_bitacora = "Seguimiento - (".FormatDateTime($currdate,7)." - $currtime)";
		
			$x_bitacora .= "\n";
			$x_bitacora .= "$x_asunto - $x_descripcion ";	
	
	
			$sSqlWrk = "
			SELECT cliente.cliente_id
			FROM 
				cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id join solicitud on solicitud.solicitud_id = solicitud_cliente.solicitud_id join credito on credito.solicitud_id = solicitud.solicitud_id
			WHERE 
				credito.credito_id = $x_credito_id
			LIMIT 1
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_cliente_id = $datawrk["cliente_id"];
			@phpmkr_free_result($rswrk);
	
	
		
			$sSqlWrk = "
			SELECT usuario_id
			FROM 
				usuario
			WHERE 
				usuario.usuario_rol_id = $x_destino_id
			LIMIT 1
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_usuario_id = $datawrk["usuario_id"];
			@phpmkr_free_result($rswrk);
			
		
			$sSql = "INSERT INTO crm_caso values (0,2,1,1,$x_cliente_id,'".$currdate."',$x_origen,$x_usuario_id,'$x_bitacora','".$currdate."',NULL,$x_credito_id)";
		
			$x_result = phpmkr_query($sSql, $conn);
			$x_crm_caso_id = mysql_insert_id();	
	

	
			if($x_crm_caso_id > 0){
	
				$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$currdate',NULL,NULL,NULL, 1, 1, 2, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
			
				$x_result = phpmkr_query($sSql, $conn);
			
				$sSqlWrk = "
				SELECT 
					comentario_int
				FROM 
					credito_comment
				WHERE 
					credito_id = ".$x_credito_id."
				LIMIT 1
				";
				
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_comment_ant = $datawrk["comentario_int"];
				@phpmkr_free_result($rswrk);
		
		
				if(empty($x_comment_ant)){
					$sSql = "insert into credito_comment values(0, $x_credito_id, '$x_bitacora', NULL)";
				}else{
					$x_bitacora = $x_comment_ant . "\n\n------------------------------\n" . $x_bitacora;
					$sSql = "UPDATE credito_comment set comentario_int = '$x_bitacora' where credito_id = $x_credito_id";
				}
		
				phpmkr_query($sSql, $conn);
			}
			
		
		}

	}// fin pagos vencidos


	}// numero total de vencimeintos
}





$sqlvencidos = "SELECT * FROM vencimiento WHERE vencimiento_status_id = 3 AND vencimiento.credito_id NOT IN  (
35,68,310,464,557,634,959,968,1049,1058,1063,1089,1120,1147,1208,1242,1259,1266,1279,1305,1310,1312,1318,1321,1357,1368,1375,1552,1564,
1641,1668,1682,1725,1739,1740,1780,1816,1818,1821,1904,1953,1963,1987,1996,2011,2066,2113,2135,2160,2164,2165,2170,2171,2194,2204,2239,2277,2278,
2306,2308,2311,2340,2359,2368,2375,2383,2408,2423,2444,2448,2453,2495,2511,2517,2555,2585,2586,2602,2615,2645,2671,2672,2673,2687,2708,2776,2877,2920,2935,2938,2944,2950,2953,
2957,2974,2984,2985,2993,2997,3000,3001,3006,3028,3029,3032,3034,3057,3058,3059,3072,3075,3104,3134,3144,3158,3167,3185,3190,3198,3205,3230,3249,3258,3264,3270,
3277,3290,3292,3294,3306,3312,3327,3361,3374,3377,3384,3393,3413,3416,3421,3447,3452,3454,3458,3463,3483,3511,3518,3521,3531,3539,3544,3552,3553,3578,3579,3581,3588,3593,3602,3606,3612,3626,3639,3641,3651,3652,3656,3659,3664,3670,3673,3680,3689,3690,3694,3697,3698,3704,3706,3707,3709,3713,3718,4277,3728,3742,3749,3750,3764,3768,4300,
3789,4278,4274,4257,3806,3807,3808,3809,3812,4364,815,3816,3822,3837,3839,3843,4319,3852,3855,3857,3859,3869,3878,4320,3880,3881,3882,
3884,3892,3894,3904,3917,3927,3928,3933,3944,3945,3946,3947,3954,3959,3961,3968,3971,3973,3974,3979,3982,3984,3988,3989,3990,3993,3996,4004,4007,4008,4011,4016,
4021,4026,4027,4032,4034,4035,4040,4042,4045,4049,4050,4053,4054,4056,4057,4058,4060,4061,4062,4066,4070,4071,4074,4075,4078,4083,4091,4116,4118,4119,4124,
4127,4135,4140,4149,4155,4158,170,4171,4172,4174,4179,4187,4193,4202,4207,4211,4212,4214,4217,4220,4223,4225,4231,4237,4241,4243,4245,4249,4251,4253,4254,4261,4262,4266,4271,4273,4287,4290,4310,4311,4321,4322,4326,4328,4331,4332,4333,4334,4338,4349,4355,4359,4361,4362,4369,4371,4373,4374,4375,4377,4382,4383,4386,4397,4400,4403,4404,4410,4411,4413,
4416,4417,4418,4420,4425,4431,4432,4437,4444,4462,4466,4467,4471,4474,4475,4480,4481,4482,4484,4486,4491,4493,4495,4496,4498,4500,4504,4505,4509,4511,4518,4522,4528,
4538,4554,4557,4561,4568,4569,4574,4575,4578,4580,4582,4585,4593,4594,4598,4604,4605,4606,4609,4610,4616,4618,4619,4622,4628,4630,4642,4646,4655,4665,4666,4677,
4679,4688,4686,4685,4704,4707,4708,4711,4717,4722,4724,4730,4729,4731,4734,4735,4737,4743,4746,4747,4753,4755,4757,4761,4763,4875,4775,4781,4782,4783,4784,4788,4792,4794,4807,4808,4809,4812,4813,4815,4820,4821,4823,4828,4833,4848,4854,4855,4857,4860,4861,4866,4877,4885,4886,4890,4891,4900,4905,4908,4911,4912,4913,4919,4921,4926,4927,4931,4936,4938,4939,4942,4945,4950,4959,4960,4968,4969,4970,4974,4977,4978,4979,4981,4984,4986,987,4988,4993,4994,4996,4997,5000,5004,5015,5016,5018,5030,5031,5036,5039,
5041,5042,5043,5045,5063,5068,5073,5074,5077,5091,5092,5093,5096,5108,5109,5111,5113,5248,5123,5125,5127,5130,5132,5133,5138,5140,5148,5149,5160,5163,5164,5165,5175,5180,
5191,5192,5200,5217,5224,5230,5235,5237,5239,5244,5245,5250,5255,5257,5258,5259,5271,5273,5275,5276,5278,5283,5292,5298,5300,5302,5309,5310,5311,5314,5318,5324,5325,5328,5330,5331,5333,5334,5335,5338,5339,5340,5341,5343,5347,5350,5351,5356,5359,5360,5365,5366,5371,5380,5381,5400,5406,5407,5410,5411,5412,5415,5419,5420,5421,5423,5427,5429,5431,5432,5437,5439,5443,5446,5447,5450,5451,5452,5454,5465,5468,5469,5475,5483,5488,5489,5491,5492,5495,5496,5499,5500,5501,5502,5503,5506,5508,5510,5511,5515,5516,5518,5519,5521,5523,5525,5529,5535,5536,5537,5540,5542,5547,5549,5550,5554,5555,5556,5558,5561,5562,5564,5569,5571,5572,5573,5574,5578,5583,5586,5587,5588,5590,5595,5596,5599,5601,5602,5603,5604,5606,5608,5609,5610,5611,5615,5616,5618,5626,5627,5632,5636,5638,5642,5644,5646,5647,5648,5655,5658,5659,5661,5663,5666,5669,5672,5674,5675,5677,5679,5680,5681,5683,5684,5685,5686,5688,5689,5692,5693,5694,5696,5698,5699,5704,5705,5706,5707,5708,5709,5710,5711,5712,5713,5714,5715,5716,5717,5718,5719,5720,5722,5723,5725,5726,5731,5733,5736,5738,5739,5740,5746,5747,5749,5751,5752,5753,5755,5758,5760,5761,5762,5763,5772,5765,5767,5768,5769,5770,5771,5773,5774,5775,5776,5780,5782,5786,5787,5789,5790,5791,5792,5793,5794,5797,5800,5803,5804,5805,5808,5810,5812,5813,5815,5816,5821,5822,5825,5827,5829,5831,5833,5834,5835,5837,5838,5843,5844,5845,5847,5848,5849,5852,5853,5854,5855,5856,5860,5861,5862,5867,5869,5870,5873,5875,5876,5881,5882,5893,5895,5898,5900,5901,5905,5906,5908,5910,5912,5915,5922,5931,5934,5935,5937,5941,5944,5948,5958,5960,5962,5969,5970,5974,5975,5976,5977,5982,5988,5989,6000,6013,6015,6020,6022,6023,6027,6036,6044,
6045,6046,6047,6048,6049,6056,6063,6073,6076,6077,6081,6082,6084,6087,6091,6094,6096,6099,6102,6103,6106,6127,6131,6162) group by credito_id";
$rsvencimientos = phpmkr_query($sqlvencidos, $conn) or die ("erroe al  selccionar los vencidos". phpmkr_error()."sql". $sqlvencidos);
while ($rowVV = phpmkr_fetch_array($rsvencimientos)){
	$x_credito_id = $rowVV["credito_id"];
	#echo "credito_id".$x_credito_id."<br>";
	$x_today_v = date("Y-m-d");
	
	
	$sqlInserVencido = "INSERT INTO `credito_vencido` ( `credito_vencido_id` , `credito_id` , `fecha` )";
	$sqlInserVencido .=  " VALUES ( NULL , $x_credito_id , '$x_today_v' );";
	
#	echo "sql insert".$sqlInserVencido."<br>";
	$rsInsertvencido = phpmkr_query($sqlInserVencido, $conn) or die ("Error al insertar en credito vencido". phpmkr_error(). "sql :".$sqlInserVencido);
	
	
	
#	echo "sql insert".$sqlInserVencido."<br>";
	
	
	}// WHILE VENCIDOS


// acepta condiciones de credito

$x_date_c = date("Y-m-d");
$sqlUpdateCond = "UPDATE `credito_condiciones` SET `status` = 1 WHERE fecha <= '".$x_date_c."' ";
$rsUpdateCond = phpmkr_query($sqlUpdateCond, $conn) or die ("Error al actulizar las condicones de credito".phpmkr_error()."sql:". $sqlUpdateCond);

// verifico si hay creditos que ya no tienen pagos pendientes 

#echo $sqlUpdateCond;
# cerrar tareas que cuando ya pago el cliente

// creditos que aun estan vencidos
$x_lista_creditos_aun_venc = "";
$sqlCC = "SELECT credito_id FROM vencimiento where vencimiento_status_id  = 3";
$rsCC = phpmkr_query($sqlCC, $conn) or die ("Error al seleccionar los aun vencidos".phpmkr_error()."sql:".$sqlCC);
while ($rowCC = phpmkr_fetch_array($rsCC)){
	$x_lista_creditos_aun_venc = $x_lista_creditos_aun_venc.$rowCC["credito_id"].", ";
	}
	$x_lista_creditos_aun_venc = substr($x_lista_creditos_aun_venc, 0, strlen($x_lista_creditos_aun_venc)-2);
	
#echo "lista de creditos aun vencidos ". $x_lista_creditos_aun_venc."<br>";	
$x_lista_casos_por_cerrar = "";
$sqlCaso = "SELECT crm_caso_id FROM crm_caso where fecha_registro > \"2012-08-01\" and crm_caso_tipo_id = 3 and credito_id not in ( $x_lista_creditos_aun_venc )";
$rsCaso = phpmkr_query($sqlCaso, $conn) or die ("Error al seleccionar los aun vencidos".phpmkr_error()."sql:".$sqlCaso);
while ($rowCaso = phpmkr_fetch_array($rsCaso)){
	$x_lista_casos_por_cerrar  = $x_lista_casos_por_cerrar.$rowCaso["crm_caso_id"].", ";
	}
	$x_lista_casos_por_cerrar  = substr($x_lista_casos_por_cerrar, 0, strlen($x_lista_casos_por_cerrar)-2);

#echo "lista de casos por cerrar". $x_lista_casos_por_cerrar."<br>";

$sqlUpdateCaso  = "UPDATE crm_caso SET crm_caso_status_id = 2 WHERE crm_caso_id in ($x_lista_casos_por_cerrar)";
//$rsUpdate = phpmkr_query($sqlUpdateCaso, $conn) or die ("error al actulizar las tareas".phpmkr_error()."SQL:".$sqlUpdateCaso);

$sqlUpdateTarea  = "UPDATE crm_tarea SET crm_tarea_status_id = 4 WHERE crm_caso_id in ($x_lista_casos_por_cerrar)";
//$rsUpdate = phpmkr_query($sqlUpdateTarea, $conn) or die ("error al actulizar las tareas".phpmkr_error()."SQL:".$sqlUpdateTarea);

 
// seleccionamos los vendos en general

$sqlvencidos = "SELECT * FROM vencimiento WHERE vencimiento_status_id = 3  group by credito_id";
$rsvencimientos = phpmkr_query($sqlvencidos, $conn) or die ("erroe al  selccionar los vencidos". phpmkr_error()."sql". $sqlvencidos);
while ($rowVV = phpmkr_fetch_array($rsvencimientos)){
	$x_credito_id = $rowVV["credito_id"];		  

// antes de insertar en credito vencido periodo validamos si existe en la tabla
	$sqlCVPE = "SELECT fecha_pago FROM  credito_vencido_periodo WHERE credito_id = $x_credito_id ";
	$rsCVPE = phpmkr_query($sqlCVPE, $conn) or die ("Error al seleccionar la fecha de pago existente". phpmkr_error()."sql:".$sqlCVPE);
	#$x_registros_existentes = mysql_num_rows($rsCVPE);
	$x_inserta = 1; // se iniciliza con uno para asegurar la insersion del registro, solo en caso que encuentre una fecha nula entonces ya no se inserta, porque ya tenemos el registro y aun no sale de vencido.
	while($rowCVPE = phpmkr_fetch_array($rsCVPE)){
		$x_fecha_pago_vv = $rowCVPE["fecha_pago"];
		if (is_null($x_fecha_pago_vv)){
			//si alguna fecha esta nula entonces NO se inerta el registro, si todas las fechas estan llenas entonces se inserta el registro
			 $x_inserta = 0;
			}
		}	
	if($x_inserta == 1){		
		// buscamos la fecha del vencimeinto porque el sistema lo vence despues de los dias de gracias, asiq ue la fecha actual no es la misma que la fecha del venciemiento.			
	#seleccionamos el primer vencimeinto que esta vencido del credito actual en el ciclo, PARA TENER LA FECHA MAS ANTIGUA Y SABER DESDE CUANDO ESTA VENCIDO
	$sqlVencz = "SELECT fecha_vencimiento ";
	$sqlVencz .= " FROM vencimiento ";
	$sqlVencz .= " WHERE credito_id = $x_credito_id ";
	$sqlVencz .= " AND  vencimiento_status_id = 3 ";
	$sqlVencz .= " ORDER BY vencimiento.vencimiento_num ASC ";
	$sqlVencz .= " LIMIT 0,1 ";
	$rsVencz = phpmkr_query($sqlVencz,$conn) or die("Error al seleccioanr el primer vencido". phpmkr_error()."sql:".$sqlVencz);
	$rowVencz = phpmkr_fetch_array($rsVencz);
	$x_fecha_vencidoz = $rowVencz["fecha_vencimiento"];	
		
	$sqlInserVencidoP = "INSERT INTO `credito_vencido_periodo` ( `credito_vencido_periodo_id` , `credito_id` , `fecha` ,`fecha_pago` )";
	$sqlInserVencidoP .=  " VALUES ( NULL , $x_credito_id , '$x_fecha_vencidoz' , NULL );";
	$rsInsertvencidoP = phpmkr_query($sqlInserVencidoP, $conn) or die ("Error al insertar en credito vencido". phpmkr_error(). "sql :".$sqlInserVencidoP);
	}
 

}

// creditos que aun estan vencidos
$x_lista_creditos_aun_venc = "";
$sqlCC = "SELECT credito_id FROM vencimiento where vencimiento_status_id  = 3";
$rsCC = phpmkr_query($sqlCC, $conn) or die ("Error al seleccionar los aun vencidos".phpmkr_error()."sql:".$sqlCC);
while ($rowCC = phpmkr_fetch_array($rsCC)){
	$x_lista_creditos_aun_venc = $x_lista_creditos_aun_venc.$rowCC["credito_id"].", ";
	}
	$x_lista_creditos_aun_venc = substr($x_lista_creditos_aun_venc, 0, strlen($x_lista_creditos_aun_venc)-2);
	
	//PARA ACTUALIZAR LOS CREDITOS_VENCIDO_PERIODO
	
	
	$x_hoy = date("Y-m-d");
	$fecha = "SELECT DATE_SUB(\"$x_hoy\", INTERVAL 1 DAY) as ayer;";
	$rsFecha= phpmkr_query($fecha,$conn) or die("Error al restar 1 dia".phpmkr_error()."SQL:".$fecha);
	$rowFecha = phpmkr_fetch_array($rsFecha);
	$x_ayer =  $rowFecha["ayer"];
	
	
$sqlVP = "SELECT vencimiento.credito_id as c_id, recibo.fecha_pago as f_p
		  FROM `recibo`
		  JOIN recibo_vencimiento ON recibo_vencimiento.recibo_id = recibo.recibo_id
          JOIN vencimiento ON vencimiento.vencimiento_id = recibo_vencimiento.vencimiento_id
          WHERE recibo.`fecha_registro` = \"$x_ayer\" and vencimiento.credito_id not in ($x_lista_creditos_aun_venc)";
		  
$rsVP = phpmkr_query($sqlVP, $conn) or die ("Error al seleccionar los creditos que se pagaron hoy". phpmkr_error()."sql: ". $sqlVP);
while($rowVP = phpmkr_fetch_array($rsVP)){
	$x_cred_id = $rowVP["c_id"];
	$x_fecha_pago = $rowVP["f_p"];	
	// si ya no tiene ningun vencimiento vencido entonces se asigna la fecha de pago a la tabla credito vencido periodo, la fecha de ago sera la fecha de pago del recibo.	
	$sqlFP = "UPDATE credito_vencido_periodo SET fecha_pago = \"$x_fecha_pago\"  WHERE credito_id = $x_cred_id and  fecha_pago IS NULL ";
	$rsFP = phpmkr_query($sqlFP, $conn) or die ("Error al seleccionar fecha de pago en  credito_venc_periodo".phpmkr_error()."sql:".$sqlFP);	
	// si encuentra un registro que no tenga fecha asignada, entonces le asignamos la fecha de pago del recibo.	
	// verificamos si ya se liquido el credito, si es asi insertamos en la columna de liquidado, esto es para identificar los que estaban vencidos hasta que se liquidaron	
	$sqlPendientes = "SELECT * FROM vencimiento WHERE credito_id = $x_cred_id and vencimiento_status_id in (1,3,6) ";
	$rsPendientes = phpmkr_query($sqlPendientes, $conn) or die ("Error al seleccionar los vencimientos faltantes". phpmkr_error()."sql:". $sqlPendientes);	
	$x_no_pagos = mysql_num_rows($rsPendientes);
	if($x_no_pagos == 0){
		// ya no hay pagos pendientes significa que le credito se liquido vencido.
		// actualizamos la tabla credito_vencido_periodo		
		$sqlUpdateL = "UPDATE credito_vencido_periodo SET liquidado = 1 WHERE credito_id = $x_cred_id";
		$rsUpdateL = phpmkr_query($sqlUpdateL, $conn) or die("error al actualizar c_v_p liquidado ". phpmkr_error()."sql:". $rsUpdate);
		}	
	}	




// condonar comisones; regresar la comision a pendiente si es que se llego la fecha de pago y aun no se ha liquidados el credito.
// credito aun activos, que  ya llegron a la fecha limite de condonacion de comision
$sSqlWrkc = "SELECT vencimiento.*, credito.credito_id, credito.penalizacion, credito.fecha_limite_condonacion, credito.credito_status_id  FROM vencimiento join credito 
on credito.credito_id = vencimiento.credito_id join forma_pago on forma_pago.forma_pago_id = credito.forma_pago_id 
where credito.credito_status_id in (1,4) and credito.fecha_limite_condonacion < '$currdate' and credito.fecha_limite_condonacion > '0000-00-00' ";
$rsSqlC = phpmkr_query($sSqlWrkc,$conn) or die ("Error al seleccionar la fecha limite de pago para condonacion de comisiomn".phpmkr_error()."sql:". $sSqlWrkc);
while ($rowCond = phpmkr_fetch_array($rsSqlC)){
	// mientras encuantre registros
	$x_fecha_limite_condonacion= $rowCond["fecha_limite_condonacion"];
	$x_credito_id_c = $rowCond["credito_id"];
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
			//entonces regresamos la comision a su estado de pendiente
			$sqlUpdateComision = "UDATE vencimiento SET vencimiento_status_id = 3 WHERE  credito_id = $x_credito_id_c and vencimiento_num = 3001 ";
			$rsUpdateComision = phpmkr_query($sqlUpdateComision,$conn) or die("Erorr al actulizar las comiones condonadas".phpmkr_error()."sql:".$sqlUpdateComision);
			echo "comison condonada actulaizada";
			}
	
	
	
	
	}
	
	
####################################################################################################################
####################################################################################################################
################################# CUENTA TAREAS ASIGNADAS PROMOTORES ###############################################
####################################################################################################################
####################################################################################################################	

$sqlDia = "SELECT DAYOFWEEK('$currdate') AS dia_zona";
$rsDia = phpmkr_query($sqlDia,$conn)or die ("Error al seleccionar el dia de la semana".phpmkr_error()."sql:".$sqlDia);
$rowDia = phpmkr_fetch_array($rsDia);
$x_dia_zona = $rowDia["dia_zona"];
echo "DIA ZONA".$x_dia_zona;
$x_dia_zona --;
if(($x_dia_zona >0) and($x_dia_zona<7)){
$sqlPromotres = "SELECT DISTINCT  `promotor_id` 
				 FROM  `solicitud` ";
				// WHERE solicitud_status_id =6";
$rsPromotores = phpmkr_query($sqlPromotres,$conn)or die("Error al seleccionar los promotores".phpmkr_error()."SQL:".$sqlPromotres);		
while($rowPromotores = phpmkr_fetch_array($rsPromotores)){
	$x_promotor_tarea_id = $rowPromotores["promotor_id"];		
	if(!empty($x_promotor_tarea_id)){ 
cuentaTareasVencidas($conn, $x_promotor_tarea_id, $x_dia_zona, $currdate );
	}
}
}// fin del dia de zonas

####################################################################################################################
####################################################################################################################
################################# CUENTA TAREAS ASIGNADAS PROMOTORES ###############################################
####################################################################################################################
####################################################################################################################	

$sqlDia = "SELECT DAYOFWEEK('$currdate') AS dia_zona";
$rsDia = phpmkr_query($sqlDia,$conn)or die ("Error al seleccionar el dia de la semana".phpmkr_error()."sql:".$sqlDia);
$rowDia = phpmkr_fetch_array($rsDia);
$x_dia_zona = $rowDia["dia_zona"];
echo "DIA ZONA".$x_dia_zona;
//$x_dia_zona --;
if(($x_dia_zona >1) and($x_dia_zona<7)){
$sqlPromotres = "SELECT DISTINCT  `promotor_id` 
				 FROM  `solicitud` 
				 WHERE solicitud_status_id =6";
$rsPromotores = phpmkr_query($sqlPromotres,$conn)or die("Error al seleccionar los promotores".phpmkr_error()."SQL:".$sqlPromotres);		
while($rowPromotores = phpmkr_fetch_array($rsPromotores)){
	$x_promotor_tarea_id = $rowPromotores["promotor_id"];	
	if(!empty($x_promotor_tarea_id)){	 
cuentaTareasVencidas($conn, $x_promotor_tarea_id, $x_dia_zona, $currdate );
	}
}
}// fin del dia de zonas

####################################################################################################################
####################################################################################################################
#################################  CUENTA TAREAS ASIGNADAS GESTORES  ###############################################
####################################################################################################################
####################################################################################################################	

$sqlDia = "SELECT DAYOFWEEK('$currdate') AS dia_zona";
$rsDia = phpmkr_query($sqlDia,$conn)or die ("Error al seleccionar el dia de la semana".phpmkr_error()."sql:".$sqlDia);
$rowDia = phpmkr_fetch_array($rsDia);
$x_dia_zona = $rowDia["dia_zona"];
echo "DIA ZONA".$x_dia_zona;
$x_dia_zona --;
if(($x_dia_zona >1) and($x_dia_zona<6)){
$sqlPromotres = "SELECT DISTINCT  `gestor_id` 
				 FROM  `gestor` ";
				// WHERE solicitud_status_id =6";
$rsPromotores = phpmkr_query($sqlPromotres,$conn)or die("Error al seleccionar los promotores".phpmkr_error()."SQL:".$sqlPromotres);		
while($rowPromotores = phpmkr_fetch_array($rsPromotores)){
	$x_promotor_tarea_id = $rowPromotores["promotor_id"];		
	if(!empty($x_promotor_tarea_id)) {
cuentaTareasVencidas($conn, $x_gestor_id, $x_dia_zona, $currdate );
	}
}
}// fin del dia de zonas
	
	
function calculaSemanas($conn, $x_fecha_tarea, $x_dia_zona, $x_promotor_id, $x_caso_id, $x_tarea_id){
	// esta funcion sirve para saber con que fecha se guardara la tarea del CRM en la lista de las tareas que se le mostraran diariamente a los promotores.
	 #1.- contar el numero de tareas de la zona que pertenecen al promotor
	 #2.- deben de ser con fecha mayor a la fecha_tarea
	 #3.- al numero resultante lo deividimos entre 25 para saber cuantas semanas ya estan ocupadas
	 #4.- sacamos el modulo de la division ppara saber si la ultima semana ya tiene las 25 tareas completas.. o aun tiene espacio para mas.
	 #5.- regresamos la fecha con la que se debe guardar la tarea en la lista diaria
	 $x_dias_para_agregar = 0;
	 
	 $sqlCuentaTareasDelDia = "SELECT COUNT(*) AS total_tareas FROM  tarea_diaria_promotor WHERE fecha_lista = \"$x_fecha_tarea\" AND promotor_id = $x_promotor_id ";
	 $rsCuentaTareasDelDia = phpmkr_query($sqlCuentaTareasDelDia,$conn) or die ("Error al seleccionar las tareas del dia para el promotor A".phpmkr_error()."sql: cs1".$sqlCuentaTareasDelDia);
	 $rowCuentaTareasDelDia = phpmkr_fetch_array($rsCuentaTareasDelDia);
	 mysql_free_result($rsCuentaTareasDelDia);
	 $x_tareas_asignadas_de_hoy = $rowCuentaTareasDelDia["total_tareas"];
	 echo "tareas asignadas para hoy".$sqlCuentaTareasDelDia."<br>";
	 echo "TAREAS ASIGNADAS HOY  PARA ".$x_promotor_id." SON ".$x_tareas_asignadas_de_hoy;
	 
	 if($x_tareas_asignadas_de_hoy < 25){
		 // insertamos la tarea en la lista
		  $x_dias_para_agregar = 0;
		 
		 // else ----> se hace el calculo
		 }else{
			
		$sqlCuentaTareasZona = "SELECT COUNT(*) AS total_tareas_asignadas FROM  tarea_diaria_promotor WHERE fecha_lista > \"$x_fecha_tarea\" AND promotor_id = $x_promotor_id and zona_id = $x_dia_zona ";
	 $rsCuentaTareasZona = phpmkr_query($sqlCuentaTareasZona,$conn) or die ("Error al seleccionar las tareas de la zona para el promotor".phpmkr_error()."sql: cs1".$sqlCuentaTareasZona);
	 $rowCuentaTareasZona = phpmkr_fetch_array($rsCuentaTareasZona);
	 mysql_free_result($rsCuentaTareasZona);
	 echo "<BR>CUENTA TAREAS ASIGNADAS".$sqlCuentaTareasZona."<BR>";
	 $x_tareas_asignadas_zona_promotor = $rowCuentaTareasZona["total_tareas_asignadas"];
	  echo "tareas asignadas por zona".$x_tareas_asignadas_zona_promotor ."<BR>";
			
			
			$x_semanas_llenas = intval($x_tareas_asignadas_zona_promotor/25);
			echo "semanas llenas ".$x_semanas_llenas."<br>";
			
			//$x_modulo_semamas_llenas = 25%$x_tareas_asignadas_zona_promotor;
			echo "modulo semanas llenas ".$x_modulo_semamas_llenas."<br>";
			//kuki
			if($x_semanas_llenas > 0){
				$x_semanas_llenas = $x_semanas_llenas +1; 
				}
			if($x_semanas_llenas == 0){
				 $x_semanas_llenas = 1;
				}
				
				$x_dias_para_nueva_fecha = $x_semanas_llenas * 7; // 7 = a una semana completa..hoy es lunes.. 7 es el sig lunes 
				 $x_dias_para_agregar = $x_dias_para_nueva_fecha;
				 
				#insertamos la tarea; en tareas resagadas
	$x_fe_resago = date("Y-m-d");
	$sqlResago = "INSERT INTO `financ13_esf`.`tarea_resagada` (`tarea_resagada_id`, `crm_tarea_id`, `fecha_ingreso`) VALUES (NULL, $x_tarea_id, \"$x_fe_resago\")";
	$rsResago = phpmkr_query($sqlResago,$conn)or die ("error al isertar en resago".phpmkr_error()."sql:".$sqlResago); 
			 
			 }	 
	//1.- agregamos lo dias indicados a la fecha de la lista de tares y agregamos la tarea a la lista dia promotor
	//2.- cambiamos la fecha de vencimiento de la tarea.
	echo "DPA".$x_dias_para_agregar."<br>";
	
	
	
	return $x_dias_para_agregar;
	
	}	
	
				
function calculaSemanasGestor($conn, $x_fecha_tarea, $x_dia_zona, $x_promotor_id, $x_caso_id, $x_tarea_id, $x_gestor_id){
	// esta funcion sirve para saber con que fecha se guardara la tarea del CRM en la lista de las tareas que se le mostraran diariamente a los gestores de cobranza.
	 #1.- contar el numero de tareas de la zona que pertenecen al gestor
	 #2.- deben de ser con fecha mayor a la fecha_tarea
	 #3.- al numero resultante lo deividimos entre 25 para saber cuantas semanas ya estan ocupadas
	 #4.- sacamos el modulo de la division ppara saber si la ultima semana ya tiene las 25 tareas completas.. o aun tiene espacio para mas.
	 #5.- regresamos la fecha con la que se debe guardar la tarea en la lista diaria
	 $x_dias_para_agregar = 0;
	 
	 $sqlCuentaTareasDelDia = "SELECT COUNT(*) AS total_tareas FROM  tarea_diaria_gestor WHERE fecha_lista = \"$x_fecha_tarea\" AND gestor_id = $x_gestor_id ";
	 $rsCuentaTareasDelDia = phpmkr_query($sqlCuentaTareasDelDia,$conn) or die ("Error al seleccionar las tareas del dia para el promotor jjj".phpmkr_error()."sql: cs1".$sqlCuentaTareasDelDia);
	 $rowCuentaTareasDelDia = phpmkr_fetch_array($rsCuentaTareasDelDia);
	 mysql_free_result($rsCuentaTareasDelDia);
	 $x_tareas_asignadas_de_hoy = $rowCuentaTareasDelDia["total_tareas"];
	 echo "TAREAS ASIGNADAS HOY  PARA ".$x_promotor_id." SON ".$x_tareas_asignadas_de_hoy;
	 
	 if($x_tareas_asignadas_de_hoy < 25){
		 // insertamos la tarea en la lista
		  $x_dias_para_agregar = 0;
		 
		 // else ----> se hace el calculo
		 }else{
			
		$sqlCuentaTareasZona = "SELECT COUNT(*) AS total_tareas_asignadas FROM  tarea_diaria_gestor WHERE fecha_lista > \"$x_fecha_tarea\" AND gestor_id = $x_gestor_id and zona_id = $x_dia_zona";
	 $rsCuentaTareasZona = phpmkr_query($sqlCuentaTareasZona,$conn) or die ("Error al seleccionar las tareas de la zona para el promotor".phpmkr_error()."sql: cs1".$sqlCuentaTareasZona);
	 $rowCuentaTareasZona = phpmkr_fetch_array($rsCuentaTareasZona);
	 mysql_free_result($rsCuentaTareasZona);
	 $x_tareas_asignadas_zona_promotor = $rowCuentaTareasZona["total_tareas_asignadas"];
			
			
			$x_semanas_llenas = intval($x_tareas_asignadas_zona_promotor/25);
			
			
			
			if($x_semanas_llenas > 0){
				$x_semanas_llenas = $x_semanas_llenas +1; 
				}
			if($x_semanas_llenas == 0){
				 $x_semanas_llenas = 1;
				}
			
			
				
				 $x_dias_para_nueva_fecha = $x_semanas_llenas * 7; // 7 = a una semana completa..hoy es lunes.. 7 es el sig lunes 
				 $x_dias_para_agregar = $x_dias_para_nueva_fecha;
			 
			 }	 
	//1.- agregamos lo dias indicados a la fecha de la lista de tares y agregamos la tarea a la lista dia promotor
	//2.- cambiamos la fecha de vencimiento de la tarea.
	
	return $x_dias_para_agregar;
	
	}	
	
		
function cuentaTareasVencidas($conn, $x_promotor_id, $x_zona_id, $x_fecha_criterio){
		
		#1.- contamos el numero de tareas vencidas del prmotor en el dia de la zona
		#2.- si el numero de tareas es menor de 25; llenasmo la lista con tareas evencida de las semanas pasadas
		#3.- se ordenan por fecha de vencimeito de la mas antigua a las mas actual
		#4.- seleccionamos las que faltan para las 25 tareas y actualizamos la fecha del listado
		#5.-actualizamos el contador de las veces que ha sido reasignada la tarea
		 $sqlCuentaTareasDelDia = "SELECT COUNT(*) AS total_tareas FROM  tarea_diaria_promotor WHERE fecha_lista = \"$x_fecha_criterio\" AND promotor_id = $x_promotor_id ";
		 $rsCuentaTareasDelDia = phpmkr_query($sqlCuentaTareasDelDia,$conn) or die ("Error al seleccionar las tareas del dia para el promotor5555".phpmkr_error()."sql: cs1".$sqlCuentaTareasDelDia);
		 $rowCuentaTareasDelDia = phpmkr_fetch_array($rsCuentaTareasDelDia);
		 $x_tareas_asignadas_de_hoy = $rowCuentaTareasDelDia["total_tareas"];
		 
		 $x_contador_tareas_auxiliar = 0;
	
		 if($x_tareas_asignadas_de_hoy < 26){
		 // faltan tareas es necesario, llenar la lista con 25 actividades
		 echo "TAREAS ASIGNADAS HOY SON MENOS DE 25";
		 $x_contador_zona = 1;
		 $x_zona_auxiliar = $x_zona_id;
		 $x_contador_tareas_auxiliar = $x_tareas_asignadas_de_hoy;
		  //$x_contador_tareas_auxiliar = 24;
		 
				 while($x_contador_tareas_auxiliar < 25){
					 // mientras no existan 25 tareas
					 
					 // buscamos tareas de la zona en curso
					 $sqlCuentaTareasZona = "SELECT *  FROM  tarea_diaria_promotor WHERE fecha_lista < \"$x_fecha_criterio\" AND promotor_id = $x_promotor_id and zona_id =  $x_zona_auxiliar  and status_tarea = 2";// tarea vencida
					 echo "SQL :".$sqlCuentaTareasZona."<br>";
					 $rsCuentaTareasZona = phpmkr_query($sqlCuentaTareasZona,$conn) or die ("Error al seleccionar las tareas de la zona para el promotor".phpmkr_error()."sql: cs1".$sqlCuentaTareasZona);
					while($rowCuentaTareasZona = phpmkr_fetch_array($rsCuentaTareasZona)){
						$x_tar_zona_pro_id = $rowCuentaTareasZona["tarea_diaria_promotor_id"];
						$x_tarea_id = $rowCuentaTareasZona["tarea_id"];
						$x_contador_tareas_auxiliar =  $x_contador_tareas_auxiliar + 1;
					echo "entra a contador<br>".$x_contador_tareas_auxiliar."<br>";
						
						
					 $x_tareas_asignadas_zona_promotor = $rowCuentaTareasZona["total_tareas_asignadas"];
						// cambiamos la fecha de la tarea para verla en  la lista
						
						#####################################################
						#################    updates   ######################
						#####################################################
						
						$sqlUpdateFechaTar = "UPDATE tarea_diaria_promotor SET fecha_lista = \"$x_fecha_criterio\", reingreso = (reingreso +1) WHERE tarea_diaria_promotor_id =$x_tar_zona_pro_id";
						$rsUpdateFechaTar = phpmkr_query($sqlUpdateFechaTar,$conn)or die ("error al actualizar la taraa en a lista". phpmkr_error()."sql".$sqlUpdateFechaTar); echo "sql:".$sqlUpdateFechaTar."<br>";
						
						// le damos dos dias, de garcias a partir de la fecha en que se muestra en la lista.
						$x_dias_espera = 2;
						$temptime = strtotime($x_fecha_criterio);	
						$temptime = DateAdd('w',$x_dias_espera,$temptime);
						$fecha_venc = strftime('%Y-%m-%d',$temptime);			
						$x_dia = strftime('%A',$temptime);
						if($x_dia == "SUNDAY"){
							$temptime = strtotime($fecha_venc);
							$temptime = DateAdd('w',1,$temptime);
							$fecha_venc = strftime('%Y-%m-%d',$temptime);
						}
						$x_fecha_ejecuta_act = $fecha_venc;
								
						$sqlUpdateFechaTar = "UPDATE crm_tarea SET fecha_ejecuta = \"$x_fecha_ejecuta_act\" WHERE crm_tarea_id = $x_tarea_id";
						$rsUpdateFechaTar = phpmkr_query($sqlUpdateFechaTar,$conn)or die ("error al actualizar la taraa en a lista". phpmkr_error()."sql".$sqlUpdateFechaTar);
						
						//insertar en listado de resagos.........
						$sqlResago = "INSERT INTO `tarea_resagada` (`tarea_resagada_id`, `crm_tarea_id`, `fecha_ingreso`) ";
						$sqlResago .= " VALUES (NULL, $x_tarea_id,\"$x_fecha_ejecuta_act\") ";
						$rsResago  = phpmkr_query($sqlResago,$conn)or die ("Error al insertar en RESAGO".phpmkr_error()."sql:".$sqlResago);
						// validar los casos id
						// validas verificar las tareas id
						// correrlo nuevamente para verificar todo
						// cambiar los filtro a 25 se quedaron el dos
						
						
						
						if ($x_contador_tareas_auxiliar ==  25){
							// rompemos el while del sql
							echo "<br> Se rompe el while <br> ";
							unset($rowCuentaTareasZona);
							break;
							}
					}
					 mysql_free_result($rsCuentaTareasZona);
					// fin while row
							if($x_zona_auxiliar == 6){
								$x_zona_auxiliar = 0;
								}else{
									$x_zona_auxiliar ++; // buscamos en la siguente zona
									}
						
						$x_contador_zona ++;
						if($x_contador_zona == 7){
							// forsamos la salida del cliclo
							// quiere decir que ya busco en todas las zona y no encontro vencido en ninguna de ellas... 
							// no hay tareas suficientes para llenar la lista pero, debemos de sali del while porque de lo contrario, 
							// el preoceso consumiria la memoria
							$x_contador_tareas_auxiliar = 25;
							break;
							// si ya paso por todas las zonas y ya no como llenar la lista.. manda una felicitacion :D
							
							
							}	
						}
					
					 
					 
					 
					 
	 
	 }// if tareas asignadas < 26
		
		
		
		
		}
		
	
function cuentaTareasVencidasGestor($conn, $x_promotor_id, $x_zona_id, $x_fecha_criterio){
		
		#1.- contamos el numero de tareas vencidas del prmotor en el dia de la zona
		#2.- si el numero de tareas es menor de 25; llenasmo la lista con tareas evencida de las semanas pasadas
		#3.- se ordenan por fecha de vencimeito de la mas antigua a las mas actual
		#4.- seleccionamos las que faltan para las 25 tareas y actualizamos la fecha del listado
		#5.-actualizamos el contador de las veces que ha sido reasignada la tarea
		 $sqlCuentaTareasDelDia = "SELECT COUNT(*) AS total_tareas FROM  tarea_diaria_gestor WHERE fecha_lista = \"$x_fecha_criterio\" AND gestor_id = $x_gestor_id ";
		 $rsCuentaTareasDelDia = phpmkr_query($sqlCuentaTareasDelDia,$conn) or die ("Error al seleccionar las tareas del dia para el GESTOR B".phpmkr_error()."sql: cs1".$sqlCuentaTareasDelDia);
		 $rowCuentaTareasDelDia = phpmkr_fetch_array($rsCuentaTareasDelDia);
		 $x_tareas_asignadas_de_hoy = $rowCuentaTareasDelDia["total_tareas"];
	
		 if($x_tareas_asignadas_de_hoy < 25){
		 // faltan tareas es necesario, llenar la lista con 25 actividades
		 echo "TAREAS ASIGNADAS HOY SON MENOS DE 25";
		 $x_contador_zona = 1;
		 $x_zona_auxiliar = $x_zona_id;
		 $x_contador_tareas_auxiliar = $x_tareas_asignadas_de_hoy;
		  //$x_contador_tareas_auxiliar = 24;
		 
				 while($x_contador_tareas_auxiliar < 26){
					 // mientras no existan 25 tareas
					 
					 // buscamos tareas de la zona en curso
					 $sqlCuentaTareasZona = "SELECT *  FROM  tarea_diaria_gestor WHERE fecha_lista < \"$x_fecha_criterio\" AND gestor_id = $x_gestor_id and zona_id =  $x_zona_auxiliar  and status_tarea = 2";// tarea vencida
					 echo "SQL :".$sqlCuentaTareasZona."<br>";
					 $rsCuentaTareasZona = phpmkr_query($sqlCuentaTareasZona,$conn) or die ("Error al seleccionar las tareas de la zona para el asesor".phpmkr_error()."sql: cs1".$sqlCuentaTareasZona);
					while($rowCuentaTareasZona = phpmkr_fetch_array($rsCuentaTareasZona)){
						$x_tar_zona_pro_id = $rowCuentaTareasZona["tarea_diaria_promotor_id"];
						$x_tarea_id = $rowCuentaTareasZona["tarea_id"];
						$x_contador_tareas_auxiliar =  $x_contador_tareas_auxiliar + 1;
					echo "entra a contador<br>".$x_contador_tareas_auxiliar."<br>";
						
						
					 $x_tareas_asignadas_zona_promotor = $rowCuentaTareasZona["total_tareas_asignadas"];
						// cambiamos la fecha de la tarea para verla en  la lista
						
						#####################################################
						#################    updates   ######################

						#####################################################
						
						$sqlUpdateFechaTar = "UPDATE tarea_diaria_gestor SET fecha_lista = \"$x_fecha_criterio\", reingreso = (reingreso +1) WHERE tarea_diaria_gestor_id =$x_tar_zona_ges_id";
						$rsUpdateFechaTar = phpmkr_query($sqlUpdateFechaTar,$conn)or die ("error al actualizar la taraa en a lista". phpmkr_error()."sql".$sqlUpdateFechaTar); echo "sql:".$sqlUpdateFechaTar."<br>";
						
						// le damos dos dias, de garcias a partir de la fecha en que se muestra en la lista.
						$x_dias_espera = 2;
						$temptime = strtotime($x_fecha_criterio);	
						$temptime = DateAdd('w',$x_dias_espera,$temptime);
						$fecha_venc = strftime('%Y-%m-%d',$temptime);			
						$x_dia = strftime('%A',$temptime);
						if($x_dia == "SUNDAY"){
							$temptime = strtotime($fecha_venc);
							$temptime = DateAdd('w',1,$temptime);
							$fecha_venc = strftime('%Y-%m-%d',$temptime);
						}
						$x_fecha_ejecuta_act = $fecha_venc;
								
						$sqlUpdateFechaTar = "UPDATE crm_tarea SET fecha_ejecuta = \"$x_fecha_ejecuta_act\" WHERE crm_tarea_id = $x_tarea_id";
						$rsUpdateFechaTar = phpmkr_query($sqlUpdateFechaTar,$conn)or die ("error al actualizar la taraa en a lista". phpmkr_error()."sql".$sqlUpdateFechaTar);
						
						//insertar en listado de resagos.........
						$sqlResago = "INSERT INTO `tarea_resagada_gestor` (`tarea_resagada_id`, `crm_tarea_id`, `fecha_ingreso`) ";
						$sqlResago .= " VALUES (NULL, $x_tarea_id,\"$x_fecha_ejecuta_act\") ";
						$rsResago  = phpmkr_query($sqlResago,$conn)or die ("Error al insertar en RESAGO".phpmkr_error()."sql:".$sqlResago);
						// validar los casos id
						// validas verificar las tareas id
						// correrlo nuevamente para verificar todo
						// cambiar los filtro a 25 se quedaron el dos
						
						
						
						if ($x_contador_tareas_auxiliar ==  25){
							// rompemos el while del sql
							unset($rowCuentaTareasZona);
							break;
							}
					}
					 mysql_free_result($rsCuentaTareasZona);
					// fin while row
							if($x_zona_auxiliar == 6){
								$x_zona_auxiliar = 0;
								}else{
									$x_zona_auxiliar ++; // buscamos en la siguente zona
									}
						
						$x_contador_zona ++;
						if($x_contador_zona == 7){
							// forsamos la salida del cliclo
							// quiere decir que ya busco en todas las zona y no encontro vencido en ninguna de ellas... 
							// no hay tareas suficientes para llenar la lista pero, debemos de sali del while porque de lo contrario, 
							// el preoceso consumiria la memoria
							$x_contador_tareas_auxiliar = 25;
							break;
							// si ya paso por todas las zonas y ya no como llenar la lista.. manda una felicitacion :D
							
							
							}	
						}
					
					 
					 
					 
					 
	 
	 }// if tareas asignadas < 26
		
		
		
		
		}		
		



/// recordatorio de pago de penalizaciones alos clientes

$sqlCredito = "SELECT credito_id,vencimiento_num FROM vencimiento WHERE fecha_vencimiento = \"$currdate\" AND vencimiento_status_id = 1";
$rsCredito = phpmkr_query($sqlCredito, $conn) or die ("Error al seleccionar los vencimeintos pendietes".phpmkr_error()."sql:".$sqlCredito);
while($rowCredito = phpmkr_fetch_array($rsCredito)){
	$x_credito_id_a = $rowCredito["credito_id"];
	echo "credito id".$x_credito_id_a."<br>"; 
	$x_vencimiento_num = $rowCredito["vencimiento_num"];
		$sqlDC = "SELECT * FROM credito WHERE credito_id = $x_credito_id_a ";
		$rsDC = phpmkr_query($sqlDC,$conn) or die("Error al seleccionar los datos del credito".phpmkr_error()."sql:".$sqlDC);
		$rowDC = phpmkr_fetch_array($rsDC);
		$x_forma_pago_id = $rowDC["forma_pago_id"]; 
		
		$sqlCOUN = "SELECT COUNT(*) as penalizaciones FROM vencimiento WHERE credito_id = $x_credito_id_a and vencimiento_num > 2000 and vencimiento_num < 3000 ";
		$rsCount = phpmkr_query($sqlCOUN,$conn) or die ("error al selecionar los vencimeintos".phpmkr_error()."sql".$sqlCOUN);
		$rowCount = phpmkr_fetch_array($rsCount);
		$x_no_penalizaciones = $rowCount["penalizaciones"];
		
		$sqlCOUNP = "SELECT COUNT(*) as pendientes FROM vencimiento WHERE credito_id = $x_credito_id_a and vencimiento_num < 100 and vencimiento_status_id = 1 ";
		$rsCountP = phpmkr_query($sqlCOUNP,$conn) or die ("error al selecionar los vencimeintos".phpmkr_error()."sql".$sqlCOUNP);
		$rowCountP = phpmkr_fetch_array($rsCountP);
		$x_no_pendientes = $rowCount["penalizaciones"];
		echo "penalizaciones ".$x_no_penalizaciones."<br>";
		
		
		
		// la tarea sera asignada al 	RESPONSABLE DE SUCURSAL	
		# seleccionamos los datos del responsable de sucursal.
		$sqlSolId = "SELECT solicitud_id FROM credito WHERE credito_id = $x_credito_id_a";
		$rsSolId = phpmkr_query($sqlSolId,$conn) or die ("Error al seleccionar el id de la solicitud del credito".phpmkr_error()."sql:");
		$rowSolId = phpmkr_fetch_array($rsSolId);
		$x_solicitud_id_c = $rowSolId["solicitud_id"];
		echo "solcitud_id".$x_solicitud_id_c."<br>";
		
		// seleccionamos el promotor
		$sqlPromotor = "SELECT promotor_id FROM solicitud WHERE solicitud_id = $x_solicitud_id_c";
		$rsPromotor = phpmkr_query($sqlPromotor,$conn) or die ("Error al seleccionar el promotor del credito".phpmkr_error()."sql :".$sqlPromotor);
		$rowPromotor = phpmkr_fetch_array($rsPromotor);
		$x_promotor_id_c = $rowPromotor["promotor_id"];
		echo "promotor id ".$x_promotor_id_c."<br>";
		
		if($x_promotor_id_c > 0){
			// buscamos a que sucursal pertence el promotor
			$sqlSucursal = "SELECT sucursal_id FROM promotor WHERE promotor_id = $x_promotor_id_c";
			$rsSucursal = phpmkr_query($sqlSucursal,$conn) or die ("Error al seleccionar la sucursal". phpmkr_error()."Sql:".$sqlSucursal); 
			$rowSucuersal = phpmkr_fetch_array($rsSucursal);
			$x_sucursal_id_c = $rowSucuersal["sucursal_id"];
			 echo "sucursal_id ".$x_sucursal_id_c ."<br>";
			if($x_sucursal_id_c > 0){
				// si ya tenbemos la sucursal, buscamos el representante de essa sucursal
				$sqlResponsable = "SELECT usuario_id FROM responsable_sucursal WHERE sucursal_id = $x_sucursal_id_c ";
				$rsResponsable = phpmkr_query($sqlResponsable,$conn) or die ("error al seleccionar el usuario del responsable de suscursal".phpmkr_error()."sql:".$sqlResponsable);
				$rowResponsable = phpmkr_fetch_array($rsResponsable);
				$x_responsable_susursal_usuario_id = $rowResponsable["usuario_id"];	
				
				echo "usuario responsable suc.".$x_responsable_susursal_usuario_id."<br>";	
				
				}
			} 
		
		if($x_responsable_susursal_usuario_id < 1){
			// no hay resposable se la asigna la atrea al promotor
			$sqlResponsable = "SELECT usuario_id FROM promotor WHERE promotor_id = $x_promotor_id_c ";
				$rsResponsable = phpmkr_query($sqlResponsable,$conn) or die ("error al seleccionar el usuario del responsable de suscursal".phpmkr_error()."sql:".$sqlResponsable);
				$rowResponsable = phpmkr_fetch_array($rsResponsable);
				$x_responsable_susursal_usuario_id = $rowResponsable["usuario_id"];	
			echo "usuario promotor.".$x_responsable_susursal_usuario_id."<br>";	

			}
		
		// forma de pago = 
		
	//1= semanla
	//2= catorcela
	//3 = mensual
//	4= quincenal
$x_manda_tarea = 0;
		if($x_forma_pago_id == 1 and $x_vencimiento_num >= 18 ){
			if($x_no_penalizaciones > 1){
			$x_manda_tarea = 1;
			}
			}else if($x_forma_pago_id == 2 and $x_vencimiento_num >= 11){
				if($x_no_penalizaciones > 1){
			$x_manda_tarea = 1;
			}
				
				}else if($x_forma_pago_id == 4 and $x_vencimiento_num >= 11){
				if($x_no_penalizaciones > 1){
			$x_manda_tarea = 1;
			}
					}else if($x_forma_pago_id == 2 and $x_vencimiento_num >= 11){
					if($x_no_penalizaciones > 1){
			$x_manda_tarea = 1;
			}
						
						}
						
		echo "froma pago = ".$x_forma_pago_id."<br>";
		echo "pendientes de pago ".$x_no_pendientes."<br>";	
		echo "vencimiento actual ".$x_vencimiento_num."<br>";			
		if($x_manda_tarea == 1){
			 echo "entra a aviso penalizaciones<br> ";
		//validamos que aun no exista el caso
		$sSqlWrk = "    SELECT crm_caso_id as caso_abierto
						FROM 
						crm_caso
						WHERE 
						crm_caso.crm_caso_tipo_id = 3
						AND crm_caso.crm_caso_status_id = 1
						AND crm_caso.credito_id = $x_credito_id_a
						";
	
						$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
						$datawrk = phpmkr_fetch_array($rswrk);
						$x_caso_id = $datawrk["caso_abierto"];		
						@phpmkr_free_result($rswrk);

		// validamos que no esta la tarea registrada 				
		$sqlTrea = "Select COUNT(*) AS tareaas from crm_tarea WHERE crm_caso_id = $x_caso_id and  orden = 44	";
		$rsTarea = phpmkr_query($sqlTrea,$conn)or die ("erro al seleccionar las tareas de aviso penalizaciones".phpmkr_error()."sql;".$sqlTrea);
		$rowTrea = phpmkr_fetch_array($rsTarea);
		$x_tareas_existentes = $rowTrea["tareaas"];		
		if($x_tareas_existentes == 0){				
		$sSqlWrk = "
		SELECT *
		FROM 
			crm_playlist
		WHERE 
			crm_playlist.crm_caso_tipo_id = 3
			AND crm_playlist.orden = 44
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_crm_playlist_id = $datawrk["crm_playlist_id"];
		$x_prioridad_id = $datawrk["prioridad_id"];	
		$x_asunto = $datawrk["asunto"];	
		$x_descripcion = $datawrk["descripcion"];		
		$x_tarea_tipo_id = $datawrk["tarea_fuente"];		
		$x_orden = $datawrk["orden"];	
		$x_dias_espera = $datawrk["dias_espera"];		
		@phpmkr_free_result($rswrk);
	
	
		//Fecha Vencimiento
		$temptime = strtotime($currdate);	
		$temptime = DateAdd('w',$x_dias_espera,$temptime);
		$fecha_venc = strftime('%Y-%m-%d',$temptime);			
		$x_dia = strftime('%A',$temptime);
		if($x_dia == "SUNDAY"){
			$temptime = strtotime($fecha_venc);
			$temptime = DateAdd('w',1,$temptime);
			$fecha_venc = strftime('%Y-%m-%d',$temptime);
		}
		$temptime = strtotime($fecha_venc);	
	
		$x_origen = 1;
		$x_bitacora = "Cartera Vencida - (".FormatDateTime($currdate,7)." - $currtime)";
	
		$x_bitacora .= "\n";
		$x_bitacora .= "$x_asunto - $x_descripcion ";	

		$sSqlWrk = "
		SELECT cliente.cliente_id
		FROM 
			cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id join solicitud on solicitud.solicitud_id = solicitud_cliente.solicitud_id join credito on credito.solicitud_id = solicitud.solicitud_id
		WHERE 
			credito.credito_id = $x_credito_id_a
		LIMIT 1
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_cliente_id = $datawrk["cliente_id"];
		@phpmkr_free_result($rswrk);
	if(!empty($x_caso_id)){			
			$sSql = "INSERT INTO crm_tarea values (0,$x_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 2, $x_responsable_susursal_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";	
//	$rsU = phpmkr_query($sSql,$conn)or die("error al inserta tarea recordatorio de pago de penalizaciones".phpmkr_error()." sql".$sSql);

echo "sql: ".$sSql."<br>";
	}
			
		}// termina la tarea de aviso de penalizaciones;
			}// fin de manda tarea				
			
		
	
	}

	

//@phpmkr_free_result($rswrk);
phpmkr_db_close($conn);


?>
