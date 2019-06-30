<?php
die(); set_time_limit(0); ?>
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
$x_hoy = date("Y-m-d  H:i:s");

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
###############################################################################################
###############################################################################################
###############################################################################################
####################         COMISION                   #######################################
###############################################################################################
###############################################################################################		
		
$sqlMañana = " SELECT DATE_SUB(CURDATE(),INTERVAL 5 DAY) AS ayer";
$rsMañana =  phpmkr_query($sqlMañana, $conn) or die ("erorr al seleccionar el dia de mañana ". phpmkr_error()."sql:". $sqlMañana);
$rowMañana = phpmkr_fetch_array($rsMañana);
$x_ayer = $rowMañana["ayer"];		
		
		
$sqlMañana = " SELECT DATE_ADD(CURDATE(),INTERVAL 5 DAY) AS ayer";
$rsMañana =  phpmkr_query($sqlMañana, $conn) or die ("erorr al seleccionar el dia de mañana ". phpmkr_error()."sql:". $sqlMañana);
$rowMañana = phpmkr_fetch_array($rsMañana);
$x_ayer = $rowMañana["ayer"];		
		
$sqlCreditos4 = " SELECT DISTINCT `fecha_vencimiento`, credito_id, count( * ) AS numero_vencimientos FROM vencimiento WHERE `vencimiento_status_id` in (3) AND fecha_vencimiento =";
  $sqlCreditos4 .= " \"$x_ayer\" GROUP BY credito_id HAVING numero_vencimientos >= 1  AND  numero_vencimientos < 4 ";
  // $sqlCreditos4 .= " and credito_id  = 7034";
  
  echo "---".$sqlCreditos4."<br>";
  $rsCredito4 =  phpmkr_query($sqlCreditos4, $conn) or die ("Error al seleccionar los creditos con 4 venc".phpmkr_error()."sql:".$sqlCreditos4);
  while($rowCredito4 = phpmkr_fetch_array($rsCredito4)){
	  $x_credito_id = $rowCredito4["credito_id"];
	  $x_numero_vencimientos = $rowCredito4["numero_vencimientos"];	
	  echo "credito_id".$x_credito_id."<br> ";
	 
	 // SELECCIONAMOS LOS DATOS DEL CREDITO
	   // seleccionamos la forma de pago del credito
		$sqlCreditodD = "SELECT * FROM credito WHERE credito_id  = $x_credito_id";
		$rsCreditoD = phpmkr_query($sqlCreditodD, $conn) or die("error al seleccionar los datos del credito".phpmkr_error()."sql".$sqlCreditodD);
		$rowCreditoD = phpmkr_fetch_array($rsCreditoD);
		$x_forma_pago_id = $rowCreditoD["forma_pago_id"];
		$x_penalizacion =  $rowCreditoD["penalizacion"];
		$x_numero_credito = $rowCreditoD["credito_num"];
		
		
		if ($x_penalizacion > 1){
			// se hace el proceso para el envio del mensaje
			$x_aplicar_proceso = 0;
			echo " penalizacion mayor a uno.. <br>";
			// buscamos el monto de 1 vencimeinto
			$sqlVencimientos = "SELECT SUM(total_venc) AS importe_vencimiento FROM vencimiento WHERE credito_id = $x_credito_id and vencimiento_num = 1";
			$responseVenc = phpmkr_query($sqlVencimientos, $conn) or die ("error al seleccionar los vencimientos".phpmkr_error()."sql:".$sqlVencimientos);
			$rowVenc = phpmkr_fetch_array($responseVenc);
			$x_importe_vencimiento = $rowVenc["importe_vencimiento"];
			
			echo " importe vencimeinto = ".$x_importe_vencimiento."-<br>";
			//consultamos el monto vencido
			$sqlVencimientos = "SELECT SUM(total_venc) AS vencido FROM vencimiento WHERE credito_id = $x_credito_id and vencimiento.vencimiento_status_id = 3 ";
			$responseVenc = phpmkr_query($sqlVencimientos, $conn) or die ("error al seleccionar los vencimientos".phpmkr_error()."sql:".$sqlVencimientos);
			$rowVenc = phpmkr_fetch_array($responseVenc);
			$x_importe_vencido = $rowVenc["vencido"];
			echo "importe vencido ".$x_importe_vencido. "-<br>";
			
			// consultamos el monto del isg pendiente
			$sqlVencimientos = "SELECT SUM(total_venc) AS pendiente FROM vencimiento WHERE credito_id = $x_credito_id ";
			$sqlVencimeintos .= " and vencimiento.vencimiento_status_id = 1 and fecha_vencimiento = \"$x_ayer \"";
			$responseVenc = phpmkr_query($sqlVencimientos, $conn) or die ("error al seleccionar los vencimientos".phpmkr_error()."sql:".$sqlVencimientos);
			$rowVenc = phpmkr_fetch_array($responseVenc);
			$x_importe_pendiente = $rowVenc["pendiente"];
			$x_monto_pagar = $x_importe_pendiente + $x_importe_vencido;
			echo "monto a pagar".$x_monto_pagar."-";
			
			
			
			// verificamos si ya se le genro comison por gasto de cobranza
			$sqlVencimientos = "SELECT COUNT(*) AS comision FROM vencimiento WHERE credito_id = $x_credito_id and vencimiento_num >= 3000 ";
			$responseVenc = phpmkr_query($sqlVencimientos, $conn) or die ("error al seleccionar los vencimientos".phpmkr_error()."sql:".$sqlVencimientos);
			$rowVenc = phpmkr_fetch_array($responseVenc);
			$x_comision = $rowVenc["comision"];
			echo "<br> existe comision ".$x_comision."- ";
			
			$x_importe_compara= 0;
			$x_importe_comision = 0;
			if($x_forma_pago_id == 1){
				// semanal
				$x_importe_compara = $x_importe_vencido * 3;	
				$x_importe_comision = $x_importe_vencimiento * 2;	
				}else{
					$x_importe_compara = $x_importe_vencido * 2;	
					$x_importe_comision = $x_importe_vencimiento;					
					}
			echo "importe compara ".$x_importe_compara;
			if(($x_comision == 0) && ($x_monto_pagar >=  $x_importe_compara)){
				//if($x_comision != 0){
				// se envia al mensaje al cliente
				echo "Se enevia el mensaje al cliente ..";
				/*****************************************;*****************
				******************  SMS CLIENTE  *************************
				**********************************************************/
				
				// sacamos los datos del cliente con credito_id
		$sqlCliente = " SELECT cliente_id FROM solicitud_cliente JOIN solicitud ON solicitud.solicitud_id = solicitud_cliente.solicitud_id JOIN ";
		$sqlCliente .= " credito ON credito.solicitud_id = solicitud.solicitud_id WHERE credito.credito_id = $x_credito_id";
		$rsCliente = phpmkr_query($sqlCliente, $conn) or die ("Error al seleccionar el cliente _id". phpmkr_error(). "sql:". $sqlCliente);
		$rowClientet = phpmkr_fetch_array($rsCliente);
		$x_cliente_idp = 	$rowClientet["cliente_id"];
			
			if (!empty($x_cliente_idp)){
		 $sqlCelular = "SELECT numero FROM telefono WHERE cliente_id = $x_cliente_idp  AND telefono_tipo_id = 2 ORDER BY `telefono_id` DESC ";
		 $rsCelular = phpmkr_query($sqlCelular, $conn) or die ("Error al seleccioanr el numero de celuar". phpmkr_error()."sql:".$sqlCelular);
		 $rowCelular = phpmkr_fetch_array($rsCelular);
		 $x_no_celular = $rowCelular["numero"];
		 $x_compania = $rowCelular["compania_id"];
		// $x_no_celular = "5540663927";
		 
		 
// seleccionamos el mail
//seleccionamos el email del cliente
$sqlMail = "SELECT  email FROM  cliente WHERE  cliente_id = ".$x_cliente_idp." ";
$rsMail =  phpmkr_query($sqlMail,$conn) or die ("Error al seelccionar el mail");
$rowmail =  phpmkr_fetch_array($rsMail);
$email =  $rowmail["email"];
echo " <br> ".$sqlMail;
echo "eamail ".$email ."<br>";	    
		
		################################################################################################################## 
		#####################################################  SMS  ######################################################
		################################################################################################################## 		
		 // ya tenemos el numero de celular  y los datos del mensaje, ahora hacemos el envio del mensaje de texto al celular
		 if(!empty($x_no_celular) && $x_no_celular != "5555555555" && $x_no_celular != "0000000000"){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
						$x_mensaje = "CREA CRED $x_numero_credito LE INFORMA QUE SE APLICARA UNA COMISION DE COBRANZA";
						$x_mensaje .= " CORRESPONDIENTE A $x_importe_comision. SI NO PRESENTA SU PAGO ANTES DEL $x_ayer.";
						
											
						echo "MENSAJE :". $x_mensaje."<BR>";										
						// Varios destinatarios
						$para  = 'sms@financieracrea.com'; // atención a la coma
						// subject
						$titulo = $x_no_celular;						
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						
						$cabeceras2 = 'From: atencionalcliente@financieracrea.com';
						$titulo2 = "FINANCIERA CREA";
						$mensajeMail = $x_mensaje."\n \n * Este mensaje ha sido enviado de forma automatica, por favor no lo responda. \n \n";
						$mensajeMail .=  " Cualquier duda comuniquese al (55) 51350259 del interior de la republica  al (01800) 8376133 "; 

						
						// Mail it						
						mail($para, $titulo, $x_mensaje, $cabeceras);												
						$tiposms = "12";
						$chip = "";
						#CAMBIAR A CHIP NO 1						
						//insertaSmsTabla($conn, $x_cliente_idp, $x_credito_id, $tiposms, $chip, $x_mensaje, $titulo, $x_no_celular);					
						#se manda mensaje y se actualiza la tarea del CRM a completa
						

						 
						 $x_contador_6 ++;	
						 
						  $sqlCreditonum= "select credito_num from credito where credito_id = $x_credito_id";
						$rsCreditoNun = phpmkr_query($sqlCreditonum,$conn) or die("Error al seleccionar credito num".phpmkr_error()."sql:".$sqlCreditonum);
						$rowCN = phpmkr_fetch_array($rsCreditoNun);
						$x_credito_num = $rowCN["credito_num"];
						$x_fecha_sms = date("Y-m-d");
						$sqlInsertsms =  "INSERT INTO `sms_enviados` (`id_sms_enviado`, `id_tipo_mensaje`, `contenido`, `no_credito`, `no_celular`, `fecha_registro`, `tipo_envio`, `destino`, `cliente_id`) VALUES (NULL, '12','" .$x_mensaje."', $x_credito_num, '".$x_no_celular."', '".$x_fecha_sms."', '1', '1',$x_cliente_idp )";
	
	$result = $rsInsert = phpmkr_query($sqlInsertsms, $conn) or die ("Error al inserta en sms tabla nueva". phpmkr_error()."sql :". $sqlInsert);
	
	
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
//    echo "Esta dirección de correo ($email_a) es válida.";
mail($email, $titulo2, $mensajeMail, $cabeceras2);	
$sqlInsertsms =  "INSERT INTO `sms_mail_enviados` (`id_sms_mail_enviado`, `id_tipo_mensaje`, `contenido`, `no_credito`, `email`, `fecha_registro`, `tipo_envio`, `destino`, `cliente_id`) VALUES (NULL, '12','" .$mensajeMail."', $x_credito_num, '".$email."', '".$x_fecha_sms."', '1', '1',,$x_cliente_idp )";
$rsInsert = phpmkr_query($sqlInsertsms, $conn) or die ("Error al inserta en sms tabla nueva". phpmkr_error()."sql :". $sqlInsertsms);
}
						 
					}	
				
			}//cliete_idl
			
			
				/**********************************************************
				******************  SMS  AVAL     *************************
				**********************************************************/
			
			// sacamos los datos del cliente con credito_id
		$sqlCliente = " SELECT cliente_id FROM solicitud_cliente JOIN solicitud ON solicitud.solicitud_id = solicitud_cliente.solicitud_id JOIN ";
		$sqlCliente .= " credito ON credito.solicitud_id = solicitud.solicitud_id WHERE credito.credito_id = $x_credito_id";
		$rsCliente = phpmkr_query($sqlCliente, $conn) or die ("Error al seleccionar el cliente _id". phpmkr_error(). "sql:". $sqlCliente);
		$rowClientet = phpmkr_fetch_array($rsCliente);
		$x_cliente_idp = 	$rowClientet["cliente_id"];
		
		$sqlClientel = " SELECT datos_aval_id FROM datos_aval JOIN credito ON credito.solicitud_id = datos_aval.solicitud_id WHERE credito_id = $x_credito_id";
			$rsClientel = phpmkr_query($sqlClientel, $conn) or die ("Error al seleccionar el cliente _id". phpmkr_error(). "sql:". $sqlClientel);
			$rowClientel = phpmkr_fetch_array($rsClientel);
			$x_aval_idp = 	$rowClientel["datos_aval_id"];	
			echo  "<br>".$sqlClientel."<br>";
		
			
			if (!empty($x_aval_idp)){
		 $sqlCelular = "SELECT telefono_c FROM datos_aval WHERE datos_aval_id =$x_aval_idp";
		 $rsCelular = phpmkr_query($sqlCelular, $conn) or die ("Error al seleccioanr el numero de celuar". phpmkr_error()."sql:".$sqlCelular);
		 $rowCelular = phpmkr_fetch_array($rsCelular);
		 $x_no_celular = $rowCelular["telefono_c"];
		 $x_compania = $rowCelular["compania_id"];
		// $x_no_celular = "5540663927";
		 
		 
	    ################################################################################################################## 
		#####################################################  SMS  ######################################################
		################################################################################################################## 		
		 // ya tenemos el numero de celular  y los datos del mensaje, ahora hacemos el envio del mensaje de texto al celular
		 if(!empty($x_no_celular) && $x_no_celular != "5555555555" && $x_no_celular != "0000000000"){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
						$x_mensaje = "CREA CRED $x_numero_credito  LA CUENTA QUE USTED AVALA GENERARA UNA COMISION DE COBRANZA CORRESPONDIENTE A $x_importe_comision.";
						$x_mensaje .= " SI NO PRESENTA  PAGO ANTES DEL $x_ayer ";
											
						echo "MENSAJE aval :". $x_mensaje."<BR>";										
						// Varios destinatarios
						$para  = 'sms@financieracrea.com'; // atención a la coma
						// subject
						$titulo = $x_no_celular;						
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						// Mail it						
						mail($para, $titulo, $x_mensaje, $cabeceras);												
						$tiposms = "13";
						$chip = "";
						#CAMBIAR A CHIP NO 1						
						
						

						 echo "<br> en genera comision".$x_mensaje."<br>";	
						 $x_contador_6 ++;	
						 
						  $sqlCreditonum= "select credito_num from credito where credito_id = $x_credito_id";
						$rsCreditoNun = phpmkr_query($sqlCreditonum,$conn) or die("Error al seleccionar credito num".phpmkr_error()."sql:".$sqlCreditonum);
						$rowCN = phpmkr_fetch_array($rsCreditoNun);
						$x_credito_num = $rowCN["credito_num"];
						$x_fecha_sms = date("Y-m-d");
						$sqlInsertsms =  "INSERT INTO `sms_enviados` (`id_sms_enviado`, `id_tipo_mensaje`, `contenido`, `no_credito`, `no_celular`, `fecha_registro`, `tipo_envio`, `destino`) VALUES (NULL, '13','" .$x_mensaje."', $x_credito_num, '".$x_no_celular."', '".$x_fecha_sms."', '1', '2')";
	
	$result = $rsInsert = phpmkr_query($sqlInsertsms, $conn) or die ("Error al inserta en sms tabla nueva". phpmkr_error()."sql :". $sqlInsert);
						 
					}	
				
			}//cliete_idl
						
				
				}//if comision =0 
			
			
			
			
			}// penalizacion mayor a 1
	  
		
  }// MAIN WHILE
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