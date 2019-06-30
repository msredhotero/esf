<?php 
die();set_time_limit(0); ?>
<?php session_start(); ?>
<?php ob_start(); ?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php include ("utilerias/datefunc.php") ?>
<?php
// mensaje de tipo dos 
//RECORDATORIO DE PAGO PARA EL DIA DE MAÑANA
$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
$currdate = ConvertDateToMysqlFormat($currdate);
$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];		

//$x_dia = strtoupper($currentdate["weekday"]);
//$currdate = "2007-07-10";
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$x_hoy = date("Y-m-d");

$sqlMañana = " SELECT DATE_ADD(CURDATE(),INTERVAL 1 DAY) AS manana";
$rsMañana =  phpmkr_query($sqlMañana, $conn) or die ("erorr al seleccionar el dia de mañana ". phpmkr_error()."sql:". $sqlMañana);
$rowMañana = phpmkr_fetch_array($rsMañana);
$x_mañana = $rowMañana["manana"];
echo "HOY ES:".$x_hoy."<br>";
echo "MAÑANA ES:".$x_mañana."<br>";
//$x_no_celular = "5540663927";
$x_no_celular = "5540663927";
$x_mensaje = "INICIA EL ENVIO DE MENSAJES A LOS CLIENTES".$x_hoy;
echo "MENSAJE :". $x_mensaje."<BR>";										
// Varios destinatarios
$para  = 'sms@financieracrea.com'; // atención a la coma
// subject
$titulo = $x_no_celular;						
//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$cabeceras = 'From: zortiz@createc.mx';
// Mail it						
mail($para, $titulo, $x_mensaje, $cabeceras);	
$x_no_celular = "5554018885";
$x_mensaje = "INICIA EL ENVIO DE MENSAJES A LOS CLIENTES".$x_hoy;
echo "MENSAJE :". $x_mensaje."<BR>";										
// Varios destinatarios
$para  = 'sms@financieracrea.com'; // atención a la coma
// subject
$titulo = $x_no_celular;						
//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$cabeceras = 'From: zortiz@createc.mx';
// Mail it						
//mail($para, $titulo, $x_mensaje, $cabeceras);	
?>





<?php 
	
################################################################################################################################	
##########################################     CUENTA VENCIDA     ##############################################################	
################################################################################################################################
 # seleccionamos todos los vencimientos que se vencieron el dia de ayer
 # se dejan 3 dias de plazo porque los pagos no pasan el mismo dia, aveces se tardan en subir los pagos al sistema. 
 // se debe valorar que tipo de credito es si es de los nuevos tipos de credito que se hacen con penalizaciones o si son de los antiguos en los que el importe de moratorios esta ´resente en el mismo registro del vencimiento.
 // el lo nuveos tipo de credito el moratorio se  registra en un nuevo vencimiento y se reconoce como penalizaciones.
 
 $sqlMañana = " SELECT DATE_SUB(CURDATE(),INTERVAL 3 DAY) AS ayer";
$rsMañana =  phpmkr_query($sqlMañana, $conn) or die ("erorr al seleccionar el dia de mañana ". phpmkr_error()."sql:". $sqlMañana);
$rowMañana = phpmkr_fetch_array($rsMañana);
$x_dos_dias_antes_de_hoy = $rowMañana["ayer"];
 
 
 $sqlVencimientosAyer = "SELECT  * FROM vencimiento WHERE   vencimiento.fecha_vencimiento = \"$x_dos_dias_antes_de_hoy\" AND  	vencimiento_status_id  in (3) group by credito_id ";
 $rsVencimientosAyer = phpmkr_query($sqlVencimientosAyer,$conn) or die ("error al seleccioanr los vencimeitnos vncidos". phpmkr_error()."sql:". $sqlVencimientosAyer);
 
echo "sql".$sqlVencimientosAyer."<br>";
$x_contador_5 = 0;
 	while($rowVencimientosAyer = phpmkr_fetch_array($rsVencimientosAyer)){
		$x_cred_id_a = $rowVencimientosAyer["credito_id"];
		echo $x_cred_id_a."- ";
		
		// seleccionamos los datos del credito
		$sqlDCP = "SELECT * FROM credito WHERE credito_id = $x_cred_id_a ";
		$rsDCP = phpmkr_query($sqlDCP,$conn) or die ("error al seleccionar los datos del credito en".phpmkr_error()."sql:".$sqlDCP);
		echo $sqlDCP."<br>";
		$rowDCP = phpmkr_fetch_array($rsDCP);
		$x_penalizacion = $rowDCP["penalizacion"];
		$x_credito_num = $rowDCP["credito_num"];
		
		
		if($x_penalizacion > 0){
			echo "00tiene penalizacion ";
			$sqlVencidos = "SELECT * FROM vencimiento WHERE credito_id = $x_cred_id_a and  vencimiento_status_id  in (3) AND vencimiento_num < 2000";
		echo "--------".$sqlVencidos;
		$rsVencidos = phpmkr_query($sqlVencidos, $conn) or die ("Error al seleccinar". phpmkr_error()."sql :". $sqlVencidos);
		$x_por_pagar = "";
		$x_no_vencidos = 0;
		$x_moratorios = 0;
		while($rowVencidos = phpmkr_fetch_array($rsVencidos)){
			$x_no_vencidos ++;
			$x_por_pagar = $x_por_pagar + $rowVencidos["total_venc"];
			echo "por pagar".$x_por_pagar."<br>";
			
								
			}
		$sqlVencidos = "SELECT * FROM vencimiento WHERE credito_id = $x_cred_id_a and  vencimiento_status_id  in (3,1) AND vencimiento_num > 2000";
		#echo $sqlVencidos;
		$rsVencidos = phpmkr_query($sqlVencidos, $conn) or die ("Error al seleccinar". phpmkr_error()."sql :". $sqlVencidos);
		$x_por_penalizaciones = "";
		
		$x_moratorios = 0;
		while($rowVencidos = phpmkr_fetch_array($rsVencidos)){
			$x_no_vencidos ++;
			$x_por_penalizaciones = $x_por_penalizaciones + $rowVencidos["total_venc"];
			#echo "por pagar".$x_por_pagar."<br>";
			
								
			}
			
			
		$x_pago_final = $x_por_pagar ;
		$x_pago_final_aval = $x_por_pagar + $x_por_penalizaciones ;
		
		$x_mensaje = "FINANCIERA CREA: SU CUENTA PRESENTA $x_no_vencidos PAGOS VENCIDOS, SOLICITAMOS SU PAGO POR $x_pago_final MAS PENALIZACIONES POR $x_por_penalizaciones A LA BREVEDAD.";
	#	echo $x_mensaje."<br> $x_cred_id_a<br>";
		# se hace el encvio del mail al celular
		
		
		// seleccionamos los datos del vala y se le envia el mensaje al aval para que el avala haga presion sobre el cliente y se haga el pago del vencido.
		
		$x_mensaje_aval = "FINANCIERA CREA LA CUENTA QUE USTED AVALA PRESENTA PRESENTA $x_pago_final VENCIDOS EVITE COBROS. .";
		// se envia mensaje			
			$sqlClientel = " SELECT cliente_id FROM solicitud_cliente JOIN solicitud ON solicitud.solicitud_id = solicitud_cliente.solicitud_id JOIN ";
			$sqlClientel .= " credito ON credito.solicitud_id = solicitud.solicitud_id WHERE credito_id = $x_cred_id_a";
			$rsClientel = phpmkr_query($sqlClientel, $conn) or die ("Error al seleccionar el cliente _id". phpmkr_error(). "sql:". $sqlClientel);
			$rowClientel = phpmkr_fetch_array($rsClientel);
			$x_cliente_idl = 	$rowClientel["cliente_id"];
			//seleccionamos el email del cliente
			$sqlMail = "SELECT  email FROM  cliente WHERE  cliente_id = ".$x_cliente_idl." ";
			$rsMail =  phpmkr_query($sqlMail,$conn) or die ("Error al seelccionar el mail");
			$rowmail =  phpmkr_fetch_array($rsMail);
			$email =  $rowmail["email"];
			echo " <br> ".$sqlMail;
			echo "eamail ".$email ."<br>";
			
			
			if (!empty($x_cliente_idl)){
				echo "<br> entra al mensaje ";
		 $x_no_celular  = "";
		 $sqlCelular = "SELECT numero FROM telefono WHERE cliente_id = $x_cliente_idl  AND telefono_tipo_id = 2 ORDER BY `telefono_id` DESC ";
		 $rsCelular = phpmkr_query($sqlCelular, $conn) or die ("Error al seleccioanr el numero de celuar". phpmkr_error()."sql:".$sqlCelular);
		 while($rowCelular = phpmkr_fetch_array($rsCelular)){
		 $x_no_celular = $rowCelular["numero"];
		 $x_compania = $rowCelular["compania_id"];
		 
		// $x_no_celular = "5540663927";
		 
		 // ya tenemos el numero de celular  y los datos del mensaje, ahora hacemos el envio del mensaje de texto al celular
		 if(!empty($x_no_celular) && $x_no_celular != "5555555555" && $x_no_celular != "0000000000"){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
						$x_mensaje = "CREA CRED $x_credito_num SU CUENTA PRESENTA $x_no_vencidos PAGOS VENCIDOS, ";
						$x_mensaje .= "SOLICITAMOS SU PAGO POR $x_pago_final MAS PENALIZACIONES POR $x_por_penalizaciones A LA BREVEDAD." ;	
						echo "MENSAJE :". $x_mensaje."<BR>";										
						//Varios destinatarios
						$para  = 'sms@financieracrea.com'; // atención a la coma
						//subject
						$titulo = $x_no_celular;						
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';						
						$cabeceras2 = 'From: atencionalcliente@financieracrea.com';
						$titulo2 = "FINANCIERA CREA";
						$mensajeMail = $x_mensaje."\n \n * Este mensaje ha sido enviado de forma automatica, por favor no lo responda. \n \n";
						$mensajeMail .=  " Cualquier duda comuniquese al (55) 51350259 del interior de la republica  al (01800) 8376133 ";
						
						//Mail it						
						mail($para, $titulo, $x_mensaje, $cabeceras);	
						$x_lon =  strlen($x_mensaje);
						echo "longitud de la cadena = ".$x_lon."<br>";
						$x_contador_5 ++;
						
						$tiposms = "4";
						$chip = "";
						#CAMBIAR A CHIP NO 2
						
						
						
						 $sqlCreditonum= "select credito_num from credito where credito_id = $x_cred_id_a";
						$rsCreditoNun = phpmkr_query($sqlCreditonum,$conn) or die("Error al seleccionar credito num".phpmkr_error()."sql:".$sqlCreditonum);
						$rowCN = phpmkr_fetch_array($rsCreditoNun);
						$x_credito_num = $rowCN["credito_num"];
						$x_fecha_sms = date("Y-m-d");
						$sqlInsertsms =  "INSERT INTO `sms_enviados` (`id_sms_enviado`, `id_tipo_mensaje`, `contenido`, `no_credito`, `no_celular`, `fecha_registro`, `tipo_envio`, `destino`, `cliente_id`) VALUES (NULL, '4','" .$x_mensaje."', $x_credito_num, '".$x_no_celular."', '".$x_fecha_sms."', '1', '1',$x_cliente_idl)";
	
	$result = $rsInsert = phpmkr_query($sqlInsertsms, $conn) or die ("Error al inserta en sms tabla nueva". phpmkr_error()."sql :". $sqlInsert);
	
	
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
//    echo "Esta dirección de correo ($email_a) es válida.";
mail($email, $titulo2, $mensajeMail, $cabeceras2);	
$sqlInsertsms =  "INSERT INTO `sms_mail_enviados` (`id_sms_mail_enviado`, `id_tipo_mensaje`, `contenido`, `no_credito`, `email`, `fecha_registro`, `tipo_envio`, `destino`,, `cliente_id`) VALUES (NULL, '4','" .$mensajeMail."', $x_credito_num, '".$email."', '".$x_fecha_sms."', '1', '1',$x_cliente_idl)";
$rsInsert = phpmkr_query($sqlInsertsms, $conn) or die ("Error al inserta en sms tabla nueva". phpmkr_error()."sql :". $sqlInsertsms);
}
						
			}		
		 }// while celulares
		}		
		
		
		
			################################################################################################################## 
		#################################################    SMS  AVAL  ###############################################
		################################################################################################################## 		
		// se envia mensaje		
		
			$sqlClientel = " SELECT datos_aval_id FROM datos_aval JOIN  credito ON credito.solicitud_id = datos_aval.solicitud_id WHERE credito.credito_id = $x_cred_id_a";
			$rsClientel = phpmkr_query($sqlClientel, $conn) or die ("Error al seleccionar el cliente _id". phpmkr_error(). "sql:". $sqlClientel);
			$rowClientel = phpmkr_fetch_array($rsClientel);
			$x_aval_idl = 	$rowClientel["datos_aval_id"];		
		
		echo " +++++++++++++++++       ++++++++++++++++++++++++datos del aval<br>".$sqlClientel."<br>";
			//seleccionamos si la cuanta tiene o no  la comision de cobranza generada
			$sqlCG = "SELECT COUNT(*)  AS comision FROM vencimiento WHERE credito_id = $x_cred_id_a and vencimiento_num >= 3000 ";
			$rsCG = phpmkr_query($sqlCG,$conn) or die ("Error al seleccionar  la comision en aval sms  cuenta vencida".phpmkr_error()."sql:".$sqlCG);
			echo "comision <br>".$sqlCG."<br>";
			$rowCG = phpmkr_fetch_array($rsCG);
			$x_comision_generada = $rowCG["comision"];
			echo "comison".$x_comision_generada;
			$x_mensaje_aval = "";
			if($x_comision_generada == 0){
				$x_mensaje_aval = "FINANCIERA CREA LA CUENTA QUE USTED AVALA PRESENTA PRESENTA $x_pago_final_aval VENCIDOS EVITE COBROS. .";
				}else{
					$x_mensaje_aval = "FINANCIERA CREA LA CUENTA QUE USTED AVALA PRESENTA $x_pago_final VENCIDOS, MAS INTERESES  MORATORIOS POR $x_por_penalizaciones PAGUE A LA BREVEDAD . .";
					}
			
			if (!empty($x_aval_idl)){
		 $x_no_celular  = "";
		 
		 echo "****************tiene datos de vala    +++++++++++++++++++++++++++++++<br>";
		 $sqlCelular = "SELECT telefono_c FROM datos_aval WHERE datos_aval_id = $x_aval_idl   ";
		 
		 
		 echo "datos cel aval ".$sqlCelular."<br>";
		 $rsCelular = phpmkr_query($sqlCelular, $conn) or die ("Error al seleccioanr el numero de celuar". phpmkr_error()."sql:".$sqlCelular);
		 while($rowCelular = phpmkr_fetch_array($rsCelular)){
		 $x_no_celular_aval = $rowCelular["telefono_c"];
		 $x_compania = $rowCelular["compania_id"];
		 
		 //$x_no_celular_aval = "5540663927";
		 echo "<br>celular ".$x_no_celular_aval;
		 
		 
		 // ya tenemos el numero de celular  y los datos del mensaje, ahora hacemos el envio del mensaje de texto al celular
		 if(!empty($x_no_celular_aval) && $x_no_celular_aval != "5555555555"){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
							
						echo "MENSAJE :". $x_mensaje."<BR>";										
						//Varios destinatarios
						$para  = 'sms@financieracrea.com'; // atención a la coma
						//subject
						$titulo = $x_no_celular_aval;						
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						//Mail it						
						mail($para, $titulo, $x_mensaje_aval, $cabeceras);	
						$x_lon =  strlen($x_mensaje_aval);
						
						$x_contador_5 ++;
						
						$tiposms = "9";
						$chip = "";
						#CAMBIAR A CHIP NO 2
						
						insertaSmsTabla($conn, $x_cliente_idl, $x_cred_id_a, $tiposms, $chip, $x_mensaje_aval, $titulo, $x_compania);
						
						$sqlCreditonum= "select credito_num from credito where credito_id = $x_cred_id_a";
						$rsCreditoNun = phpmkr_query($sqlCreditonum,$conn) or die("Error al seleccionar credito num".phpmkr_error()."sql:".$sqlCreditonum);
						$rowCN = phpmkr_fetch_array($rsCreditoNun);
						$x_credito_num = $rowCN["credito_num"];
						$x_fecha_sms = date("Y-m-d");
						$sqlInsertsms =  "INSERT INTO `sms_enviados` (`id_sms_enviado`, `id_tipo_mensaje`, `contenido`, `no_credito`, `no_celular`, `fecha_registro`, `tipo_envio`, `destino`, `cliente_id`) VALUES (NULL, '11','" .$x_mensaje."', $x_credito_num, '".$x_no_celular."', '".$x_fecha_sms."', '1', '2',$x_cliente_idl)";
	
	$result = $rsInsert = phpmkr_query($sqlInsertsms, $conn) or die ("Error al inserta en sms tabla nueva". phpmkr_error()."sql :". $sqlInsert);
						
			}			
		 }// while celelares
		}		
				
				
				
			
//			el credito es del nuevo tipo
			}else{
				// la mora esta en el mismo vencimeinto
				// sleccionamos todos los vencidos del credito		
		$sqlVencidos = "SELECT * FROM vencimiento WHERE credito_id = $x_cred_id_a and  vencimiento_status_id  in (3)";
		#echo $sqlVencidos;
		$rsVencidos = phpmkr_query($sqlVencidos, $conn) or die ("Error al seleccinar". phpmkr_error()."sql :". $sqlVencidos);
		$x_por_pagar = "";
		$x_no_vencidos = 0;
		$x_moratorios = 0;
		while($rowVencidos = phpmkr_fetch_array($rsVencidos)){
			$x_no_vencidos ++;
			$x_por_pagar = $x_por_pagar + $rowVencidos["total_venc"];
			#echo "por pagar".$x_por_pagar."<br>";
			$x_moratorios = $x_moratorios + $rowVencidos["interes_moratorio"];	
			#echo "mora".$x_moratorios."<br>";					
			}
		$x_pago_final = $x_por_pagar -$x_moratorios;
		$x_penalizaciones = $x_moratorios;
		
		$x_mensaje = "FINANCIERA CREA: SU CUENTA PRESENTA $x_no_vencidos PAGOS VENCIDOS, SOLICITAMOS SU PAGO POR $x_pago_final MAS PENALIZACIONES POR $x_penalizaciones A LA BREVEDAD.";
	#	echo $x_mensaje."<br> $x_cred_id_a<br>";
		# se hace el encvio del mail al celular
		
		
		// seleccionamos los datos del vala y se le envia el mensaje al aval para que el avala haga presion sobre el cliente y se haga el pago del vencido.
		
		$x_mensaje_aval = "FINANCIERA CREA LA CUENTA QUE USTED AVALA PRESENTA PRESENTA $x_pago_final VENCIDOS EVITE COBROS. .";
		
		################################################################################################################## 
		#################################################    SMS  CLIENTE  ###############################################
		################################################################################################################## 		
		// se envia mensaje			
			$sqlClientel = " SELECT cliente_id FROM solicitud_cliente JOIN solicitud ON solicitud.solicitud_id = solicitud_cliente.solicitud_id JOIN ";
			$sqlClientel .= " credito ON credito.solicitud_id = solicitud.solicitud_id WHERE credito_id = $x_cred_id_a";
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
		 
		// $x_no_celular = "5540663927";
		 
	//seleccionamos el email del cliente
$sqlMail = "SELECT  email FROM  cliente WHERE  cliente_id = ".$GLOBALS["x_cliente_id"]." ";
$rsMail =  phpmkr_query($sqlMail,$conn) or die ("Error al seelccionar el mail");
$rowmail =  phpmkr_fetch_array($rsMail);
$email =  $rowmail["email"];
echo " <br> ".$sqlMail;
echo "eamail ".$email ."<br>";	 
		 
		 // ya tenemos el numero de celular  y los datos del mensaje, ahora hacemos el envio del mensaje de texto al celular
		 if(!empty($x_no_celular) && $x_no_celular != "5555555555"){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
						$x_mensaje = "FINANCIERA CREA: SU CUENTA PRESENTA $x_no_vencidos PAGOS VENCIDOS, ";
						$x_mensaje .= "SOLICITAMOS SU PAGO POR $x_pago_final MAS PENALIZACIONES POR $x_penalizaciones A LA BREVEDAD." ;	
						echo "MENSAJE :". $x_mensaje."<BR>";										
						//Varios destinatarios
						$para  = 'sms@financieracrea.com'; // atención a la coma
						//subject
						$titulo = $x_no_celular;						
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						$cabeceras2 = 'From: atencionalcliente@financieracrea.com';
						$titulo2 = "FINANCIERA CREA";
						$mensajeMail = $x_mensaje."\n \n * Este mensaje ha sido enviado de forma automatica, por favor no lo responda. \n \n";
						$mensajeMail .=  " Cualquier duda comuniquese al (55) 51350259 del interior de la republica  al (01800) 8376133 "; 

						//Mail it						
						 mail($para, $titulo, $x_mensaje, $cabeceras);	
						$x_lon =  strlen($x_mensaje);
						echo "longitud de la cadena = ".$x_lon."<br>";
						$x_contador_5 ++;
						
						$tiposms = "4";
						$chip = "";
						#CAMBIAR A CHIP NO 2
						
						insertaSmsTabla($conn, $x_cliente_idl, $x_cred_id_a, $tiposms, $chip, $x_mensaje, $titulo, $x_compania);
						
						
				
				
				
				
				
				
				
				
						
			}			
		 } // while celulares
		}		
		
			
		################################################################################################################## 
		#################################################    SMS  AVAL  ###############################################
		################################################################################################################## 		
		// se envia mensaje		
		
			$sqlClientel = " SELECT datos_aval_id FROM datos_aval JOIN solicitud_cliente on datos_aval.cliente_id = solicitud_cliente.cliente_id  JOIN solicitud ON solicitud.solicitud_id = solicitud_cliente.solicitud_id JOIN ";
			$sqlClientel .= " credito ON credito.solicitud_id = solicitud.solicitud_id WHERE credito_id = $x_cred_id_a";
			$rsClientel = phpmkr_query($sqlClientel, $conn) or die ("Error al seleccionar el cliente _id". phpmkr_error(). "sql:". $sqlClientel);
			$rowClientel = phpmkr_fetch_array($rsClientel);
			$x_aval_idl = 	$rowClientel["datos_aval_id"];		
		
		
			//seleccionamos si la cuanta tiene o no  la comision de cobranza generada
			$sqlCG = "SELECT COUNT(*)  AS comision FROM vencimiento WHERE credito_id = $x_cred_id_a and vencimiento_num >= 3000 ";
			$rsCG = phpmkr_query($sqlCG,$conn) or die ("Error al seleccionar  la comision en aval sms  cuenta vencida".phpmkr_error()."sql:".$sqlCG);
			$rowCG = phpmkr_fetch_array($rsCG);
			$x_comision_generada = $rowCG["comision"];
			$x_mensaje_aval = "";
			if($x_comision_generada == 0){
				$x_mensaje_aval = "FINANCIERA CREA LA CUENTA QUE USTED AVALA PRESENTA PRESENTA $x_pago_final VENCIDOS EVITE COBROS. .";
				}else{
					$x_mensaje_aval = "FINANCIERA CREA LA CUENTA QUE USTED AVALA PRESENTA $x_pago_final VENCIDOS, MAS INTERESES  MORAATORIOS POR $x_penalizaciones PAGUE A LA BREVEDAD . .";
					}
			
			if (!empty($x_aval_idl)){
		 $x_no_celular  = "";
		 $sqlCelular = "SELECT numero FROM telefono WHERE aval_id = $x_aval_idl  AND telefono_tipo_id = 2 ORDER BY `telefono_id` DESC ";
		 $rsCelular = phpmkr_query($sqlCelular, $conn) or die ("Error al seleccioanr el numero de celuar". phpmkr_error()."sql:".$sqlCelular);
		 while($rowCelular = phpmkr_fetch_array($rsCelular)){
		 $x_no_celular_aval = $rowCelular["numero"];
		 $x_compania = $rowCelular["compania_id"];
		 
		 $x_no_celular_aval = "5540663927";
		 
		 // ya tenemos el numero de celular  y los datos del mensaje, ahora hacemos el envio del mensaje de texto al celular
		 if(!empty($x_no_celular_aval) && $x_no_celular_aval != "5555555555"){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
							
						echo "MENSAJE :". $x_mensaje."<BR>";										
						//Varios destinatarios
						$para  = 'sms@financieracrea.com'; // atención a la coma
						//subject
						$titulo = $x_no_celular;						
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						//Mail it						
						 mail($para, $titulo, $x_mensaje, $cabeceras);	
						$x_lon =  strlen($x_mensaje_aval);
						echo "longitud de la cadena = ".$x_lon."<br>";
						$x_contador_5 ++;
						
						$tiposms = "9";
						$chip = "";
						#CAMBIAR A CHIP NO 2
						
						insertaSmsTabla($conn, $x_cliente_idl, $x_cred_id_a, $tiposms, $chip, $x_mensaje, $titulo, $x_compania);
						
			}		
		 }//while celulares
		}
				
				
				
				
				}// if panelizacion > 0
				
		
		
		
		
		
		
				
		}
 echo "<br><br>hh".$x_contador_5."<br><br>";
 ?>
 <?php 
function insertaSmsTabla($conn, $cliente, $credito, $tiposms, $chip, $texto, $celular, $compañia ){	
	$x_cliente = $cliente;
	$x_credito_id = $credito;
	$x_sms_tipo = $tiposms;
	$x_chip = $chip;
	$x_texto = $texto;
	$x_celular = $celular;
	$x_fecha = date("Y-m-d");	
	$sqlInsert = "INSERT INTO `msm` (`sms_id`, `cliente_id`, `credito_id`, `sms_tipo`, `texto`, `fecha`, `chip`, `celular`) ";
	$sqlInsert .= " VALUES (NULL, $x_cliente, $x_credito_id, $x_sms_tipo, '$x_texto', '$x_fecha', '$x_chip', '$x_celular');";
	$result = $rsInsert = phpmkr_query($sqlInsert, $conn) or die ("Error al inserta en sms tabla". phpmkr_error()."sql :". $sqlInsert);	
	}



phpmkr_db_close($conn);
?>



