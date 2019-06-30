<?php 
die();
session_start(); ?>
<?php ob_start(); ?>

<?php
// seleccionamos todos los credito que esten totalmente vencidos
include ("db.php");
include ("phpmkrfn.php");
include("../crm/datefunc.php");
$conn = phpmkr_db_connect(HOST, USER, PASS,DB, PORT);

$X_HOY = date("Y-m-d");
$currdate = date("Y-m-d");
$sqlUltimoVencimiento =  "SELECT * FROM vencimiento WHERE  fecha_vencimiento <= \"$X_HOY\" group by credito_id  ";
$rsUltimoVencimiento = phpmkr_query($sqlUltimoVencimiento,$conn)or die ("Error al seleccionar el ultimo vencimiento".phpmkr_error()."sql:".$sqlUltimoVencimiento);


$creditoActivos = "SELECT * FROM credito WHERE credito_status_id in (1,4) and  fecha_vencimiento <= \"$X_HOY\"  "; // credito activos o en cobranza
$rsCredito  = phpmkr_query($creditoActivos, $conn) or die("Error al seleccionar los redito activos o en cobranza".phpmkr_error()."sql:".$creditoActivos);
while($rowCredito = phpmkr_fetch_array($rsCredito)){
$x_credito_id = $rowCredito["credito_id"];
$x_fecha_vencimiento =  $rowCredito["fecha_vencimiento"];
$x_fecha_otorgamiento =  $rowCredito["fecha_otrogamiento"];
$x_total_gral_sin_tope = 0;
$sqlVencidos = "SELECT COUNT(*) AS pendientes FROM vencimiento WHERE  credito_id = $x_credito_id and vencimiento_status_id in (1,6,7,8) ";
$rsVencidos = phpmkr_query($sqlVencidos,$conn) or die("Error al seleciconar el credito... ".phpmkr_error()."sql:".$sqlVencidos);
$rowVencidos = phpmkr_fetch_array($rsVencidos);
$x_pendientes = $rowVencidos["pendientes"];

$sqlVencidos = "SELECT COUNT(*) AS vencidos FROM vencimiento WHERE  credito_id = $x_credito_id and vencimiento_status_id in (3) ";
$rsVencidos = phpmkr_query($sqlVencidos,$conn) or die("Error al seleciconar el credito ".phpmkr_error()."sql:".$sqlVencidos);
$rowVencidos = phpmkr_fetch_array($rsVencidos);
$x_vencidos = $rowVencidos["vencidos"];
if ( $x_pendientes== 0 && $x_vencidos > 1 ){
	// se envia el mensaje
	if($x_fecha_otorgamiento <= "2012-07-31"){
		// se manda el monto mayor
		// seleccionamos todos los vencimientos vencidos
		#echo "fecha otrga menor<br>";
		$sqlVV = "SELECT * FROM  vencimiento WHERE credito_id = $x_credito_id and  vencimiento_status_id = 3";
		$rsVV = phpmkr_query($sqlVV,$conn)or die("eRROR L SELECCIONAR LS VENCIDOS".phpmkr_error()."sql:".$sqlVV);		
		while($rowVencimientos = phpmkr_fetch_array($rsVV)){
			
			// seleccionamos el primer vancimeinto.
			// Y  vemos si esta vencido o o no
			$x_vencimiento_id = $rowVencimientos["vencimiento_id"];
			$x_vencimiento_num = $rowVencimientos["vencimiento_num"];
			#echo "vencimiento num ".$x_vencimiento_num."<br>";
			$x_vencimiento_status_id = $rowVencimientos["vencimiento_status_id"];//status vencimiento
			#echo "<br>venc staus----".$x_vencimiento_status_id;
			$x_fecha_vencimiento = $rowVencimientos["fecha_vencimiento"];// fecha de pago
			$x_importe = $rowVencimientos["importe"]; // importe	
			$x_interes = $rowVencimientos["interes"]; // interes
			$x_interes_moratorio = $rowVencimientos["interes_moratorio"]; //interes moratorio
			$x_iva = $rowVencimientos["iva"]; // iva
			$x_iva_mor = $rowVencimientos["iva_mor"];//  iva mor
			$x_total_venc = $rowVencimientos["total_venc"]; // total venc		
		
			
			$x_credito_id = $rowVencimientos["credito_id"];
		#	echo "<br>credito_id = ".$x_credito_id; 			
			$x_total_con_tope = $x_total_venc;			
			$x_c_ordi = $x_importe + $x_interes + $x_iva;	
					
			// status_vencimeito_id
			// 1 .- pendiente
			// 2.- pagado
			// 3.- vencido
			// 4.- cabcelado
			
			if(($x_vencimiento_status_id == 3)){
				// se recalculan los moratorios sin toparlos				
				#echo "<br>entra al if<br>";
				$sqlVenNum = "SELECT COUNT(*) AS numero_de_pagos FROM vencimiento WHERE  fecha_vencimiento = \"$x_fecha_vencimiento\" AND  credito_id =  $x_credito_id";
				$response = phpmkr_query($sqlVenNum, $conn) or die("error en numero de vencimiento".phpmkr_error()."sql:".$sqlVenNum);
				$rownpagos = phpmkr_fetch_array($response);  
				$x_numero_de_pagos =  $rownpagos["numero_de_pagos"];
				
				//if($x_numero_de_pagos < 2){
				//se hace el clculo de los moratorios				
				$x_dias_vencidos = datediff('d', $x_fecha_vencimiento, $currdate, false);					
					$x_dia = strtoupper(date('l',strtotime($x_fecha_vencimiento)));				
				
					#echo "<br>fecha  vencimeinto".$x_fecha_vencimiento;
	#echo "<br>fecha actual  ".$currdate;	
	
				
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
	#echo "dias de gracia".$x_dias_gracia;
					#	echo "froma de pago =".$x_forma_pago_valor."<br>";
					#	echo "penalizacion ".$x_penalizacion."<br>";
						if(($x_dias_vencidos >= $x_dias_gracia) ){
							$x_importe_mora = 10 * $x_dias_vencidos;
							#echo "dias vencido".$x_dias_vencidos."<br>";							
							#echo "numero venc".$x_vencimiento_num."<br><br>";
							#echo "<br>importe mora".$x_importe_mora."<br>";
							if($x_iva_credito == 1){
								$x_iva_mor = round($x_importe_mora * .16);
								$x_iva_mor = 0;			
							}else{
								$x_iva_mor = 0;
							}
							
					//moratorios no majyores a 	2						
							if($x_credito_tipo_id == 2){			
								if($x_importe_mora > 0){
									$x_importe_mora = 250;
									}			
								}
								#echo "importe mora truncado".$x_importe_mora."<br>";
								
								if($x_vencimiento_num < 1000){
							$x_total_sin_tope = $x_importe + $x_interes + $x_importe_mora + $x_iva + $x_iva_mor;					
							#
							#echo "total sin tope dentro del if". $x_total_sin_tope."-- <br>";
						#	echo " staus credito".$x_vencimiento_status_id ." <br>dias vencido  ".$x_dias_vencidos."<br>";
							$x_total_dias_vencidos = $x_total_dias_vencidos +$x_dias_vencidos;
							#echo "<br><br> total ".$x_total_dias_vencidos."<br>";
								}else{
									$x_total_sin_tope = $x_total_venc;
									
									}
							
						}// dias vencidos mayor o ogial a dias de gracia....
				
				
				//}// numero de pagos menor a 2
				
				
		#	echo "numero de dias ".$x_dias_vencidos." vencimiento num ".$x_vencimiento_num."total del vencime".$x_total_con_tope."<br>";				
				$x_total_gral_sin_tope = $x_total_gral_sin_tope +$x_total_sin_tope;
		#	echo "<br> sin tope  total".$x_total_gral_sin_tope."<br>";
			$x_capital_ordinario = $x_capital_ordinario +$x_c_ordi;
			$x_total_gral_con_tope = $x_total_gral_con_tope + $x_total_con_tope;
			#echo "<br> CON tope".$x_total_gral_con_tope;
		#	echo "<br> ordinario".$x_capital_ordinario;
		#		
				}//vencimeinto_vencido
		#	echo "no entra<br>";
			$x_total_con_tope = $x_total_venc;
			
		
			$x_tipo = " TIPO A";
			
			
			
			
			}
		
		}else{
			// se manda el monto original.
			$sqlDeudor = "SELECT SUM(total_venc) AS TOTAL FROM vencimiento WHERE credito_id = $x_credito_id  and vencimiento_status_id in (3,6,7,8) ";
			$rsDeudor = phpmkr_query($sqlDeudor,$conn) or die("Error al seleccionar vencido".phpmkr_error()."Sql:".$sqlDeudor);
			$rowDeudor = phpmkr_fetch_array($rsDeudor);
			$x_total_gral_sin_tope = $rowDeudor["TOTAL"];
			$x_tipo = " TIPO B";
			
			}
			
			echo $x_credito_id ." total general ".$x_total_gral_sin_tope.$x_tipo."<br>";
	
	
	
	
	if ($x_total_gral_sin_tope > 1000){
		// manamos ele mensaje
		$sqlClientel = " SELECT cliente_id FROM solicitud_cliente JOIN solicitud ON solicitud.solicitud_id = solicitud_cliente.solicitud_id JOIN ";
			$sqlClientel .= " credito ON credito.solicitud_id = solicitud.solicitud_id WHERE credito_id = $x_credito_id ";
			$rsClientel = phpmkr_query($sqlClientel, $conn) or die ("Error al seleccionar el cliente _id". phpmkr_error(). "sql:". $sqlClientel);
			$rowClientel = phpmkr_fetch_array($rsClientel);
			$x_cliente_idl = 	$rowClientel["cliente_id"];
			
			if (!empty($x_cliente_idl)){
		 $x_no_celular  = "";
		 $sqlCelular = "SELECT numero FROM telefono WHERE cliente_id = $x_cliente_idl  AND telefono_tipo_id = 2 ORDER BY `telefono_id` DESC ";
		 $rsCelular = phpmkr_query($sqlCelular, $conn) or die ("Error al seleccioanr el numero de celuar". phpmkr_error()."sql:".$sqlCelular);
		 while($rowCelular = phpmkr_fetch_array($rsCelular)){
		 $x_no_celular = $rowCelular["numero"];
		 $x_compania = $rowCelular["compania_id"];
		 
		 //$x_no_celular = "5540663927";
		 
		 $x_mensaje = "";
		 //creditos que se liquidaron y que no tienen comision de cobranza generada.. solo incluye los que entran en el nuevo esquema de penalizaciones
		 
			  // los que no tienen comision generada
			  			  
				  $x_mensaje = "FINANCIERA CREA:SU CUENTA SE ENVIO AL AREA JURIDICA";
				  $x_mensaje .= " SALDO DEUDOR ".FormatNumber($x_total_gral_sin_tope,2,0,0,1)." ";
				  $x_mensaje .= " COMUNIQUESE AL: DF 51350259:LADA SIN COSTO 018008376133.";
				  
				 	  
			 
		 
		 // ya tenemos el numero de celular  y los datos del mensaje, ahora hacemos el envio del mensaje de texto al celular
		 if(!empty($x_no_celular) &&  $x_no_celular != "5555555555"){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
												
						echo "MENSAJE :". $x_mensaje." c_id ".$x_cliente_idl." num ".$x_no_celular."<BR>";										
						//Varios destinatarios
						$para  = 'sms@financieracrea.com'; // atención a la coma
						//subject
						$titulo = $x_no_celular;						
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						//Mail it						
						mail($para, $titulo, $x_mensaje, $cabeceras);	
						$x_contador_3 ++;
						
						$tiposms = "8";
						$chip = "";
						#cambiamos a chip 2
						
						#insertaSmsTabla($conn, $x_cliente_idl, $x_credito_id_liquidado, $tiposms, $chip, $x_mensaje, $titulo, $x_compania);
						 
						
						
			}			
		}		
			
			
		#	echo "CREDITO LIQUIDADO AYER".$x_credito_id_liquidado."<BR>";
			} // while celulares
		
		
		
		}else{
			// manamos ele mensaje
		$sqlClientel = " SELECT cliente_id FROM solicitud_cliente JOIN solicitud ON solicitud.solicitud_id = solicitud_cliente.solicitud_id JOIN ";
			$sqlClientel .= " credito ON credito.solicitud_id = solicitud.solicitud_id WHERE credito_id = $x_credito_id ";
			$rsClientel = phpmkr_query($sqlClientel, $conn) or die ("Error al seleccionar el cliente _id". phpmkr_error(). "sql:". $sqlClientel);
			$rowClientel = phpmkr_fetch_array($rsClientel);
			$x_cliente_idl = 	$rowClientel["cliente_id"];
			
			if (!empty($x_cliente_idl)){
		 $x_no_celular  = "";
		 $sqlCelular = "SELECT numero FROM telefono WHERE cliente_id = $x_cliente_idl  AND telefono_tipo_id = 2 ORDER BY `telefono_id` DESC ";
		 $rsCelular = phpmkr_query($sqlCelular, $conn) or die ("Error al seleccioanr el numero de celuar". phpmkr_error()."sql:".$sqlCelular);
		 while($rowCelular = phpmkr_fetch_array($rsCelular)){
		 $x_no_celular = $rowCelular["numero"];
		 $x_compania = $rowCelular["compania_id"];
		 
		 //$x_no_celular = "5540663927";
		 
		 $x_mensaje = "";
		 //creditos que se liquidaron y que no tienen comision de cobranza generada.. solo incluye los que entran en el nuevo esquema de penalizaciones
		 
			  // los que no tienen comision generada
			  			  
				  $x_mensaje = "FINANCIERA CREA:SU CUENTA ESTA VENCIA PAGUE A LA BREVEDAD";
				  $x_mensaje .= " SALDO DEUDOR ".FormatNumber($x_total_gral_sin_tope,2,0,0,1)." ";
				  $x_mensaje .= " COMUNIQUESE AL: DF 51350259:LADA SIN COSTO 018008376133.";
				  
				 	  
			 
		 
		 // ya tenemos el numero de celular  y los datos del mensaje, ahora hacemos el envio del mensaje de texto al celular
		 if(!empty($x_no_celular) &&  $x_no_celular != "5555555555"){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
												
						echo "MENSAJE :". $x_mensaje." c_id ".$x_cliente_idl." num ".$x_no_celular."<BR>";										
						//Varios destinatarios
						$para  = 'sms@financieracrea.com'; // atención a la coma
						//subject
						$titulo = $x_no_celular;						
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						//Mail it						
						mail($para, $titulo, $x_mensaje, $cabeceras);	
						$x_contador_3 ++;
						
						$tiposms = "8";
						$chip = "";
						#cambiamos a chip 2
						
						#insertaSmsTabla($conn, $x_cliente_idl, $x_credito_id_liquidado, $tiposms, $chip, $x_mensaje, $titulo, $x_compania);
						 
						
						
			}			
		}		
			
			
		#	echo "CREDITO LIQUIDADO AYER".$x_credito_id_liquidado."<BR>";
			} // while celulares
			
			
			
			
			}
}
	}

?>