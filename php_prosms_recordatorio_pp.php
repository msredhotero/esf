<?php 
die();
set_time_limit(0); ?>
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
$x_mensaje = "INICIA EL ENVIO DE MENSAJES A LOS CLIENTES PP para mañana".$x_hoy;
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
$x_mensaje = "INICIA EL ENVIO DE MENSAJES A LOS CLIENTES PP para mañana".$x_hoy;
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
##########################################    RECORDATORIO PP    ###############################################################	
################################################################################################################################

// LOS CLIENTES QUE TIENE PROMESA PARA EL DIA DE MAÑANA DE PAGO EN EL CRM MANDARLE UN MENSAJE Y EL STATUS DE LA TAREA ES PENDIENTE

	// SELECCIONAMOS TODOS LOS PAGOS DE AYER Y VEMOS CUALES CREDITOS SE PAGARON EL DIA DE AYER.
$sqlMañana = " SELECT DATE_ADD(CURDATE(),INTERVAL 1 DAY) AS maniana";
$rsMañana =  phpmkr_query($sqlMañana, $conn) or die ("erorr al seleccionar el dia de mañana ". phpmkr_error()."sql:". $sqlMañana);
$rowMañana = phpmkr_fetch_array($rsMañana);
$x_mañana = $rowMañana["maniana"];
$x_hoy = date("Y-m-d");

 #	$sqlPP = "SELECT crm_tarea_cv.*, crm_tarea.* FROM `crm_tarea_cv`  join crm_tarea ON crm_tarea.crm_tarea_id = crm_tarea_cv.crm_tarea_id ";
#	$sqlPP .= " WHERE crm_tarea_cv.promesa_pago= \"$x_mañana\" AND crm_tarea.crm_tarea_status_id = 1";

// cuando la promesa de pagao es para hoy

//
$x_lista_promesas_pago = "";
$SQLppHOY =  "SELECT * FROM crm_tarea_cv WHERE crm_tarea_cv.promesa_pago = \"$x_hoy\" ";
$rsPPHoy  = phpmkr_query($SQLppHOY,$conn)or die ("Error al seleccionar las promesas de pago para el dia de hoy".phpmkr_error()."sql:".$SQLppHOY);
while($rowPPHoy = phpmkr_fetch_array($rsPPHoy)){
	$x_lista_promesas_pago .= $rowPPHoy["crm_tarea_id"].", "; 
	
	} 
	$x_lista_promesas_pago = trim($x_lista_promesas_pago,", ");
	echo "<BR> LISTA DE PROMESSA DE PAGO PARA HOY ".$x_lista_promesas_pago."<BR>";

if(strlen($x_lista_promesas_pago)> 1){
	$sqlPP = "SELECT  crm_tarea.*, crm_caso.credito_id FROM  crm_tarea  JOIN crm_caso ON crm_caso.crm_caso_id =  crm_tarea.crm_caso_id ";
	$sqlPP .= " WHERE   crm_tarea_id in ($x_lista_promesas_pago)";
	$rsPP = phpmkr_query($sqlPP, $conn) or die ("Error al seleccionar las tareas de pp". phpmkr_error()."sql:".$sqlPP);
	$x_contador_6 = 0;
	#echo "sql".$sqlPP."<br>";

	while($rowPP = phpmkr_fetch_array($rsPP)){
		// mientras encuentre tareas buscamos el numero de cliente y su numero telefonic si lo tiene se envia el mail y se cierra tarea
		$x_crm_tarea_id = $rowPP["crm_tarea_id"];
		$x_crm_caso_id = $rowPP["crm_caso_id"];
		$x_credito_id = $rowPP["credito_id"];
		
		
		// seleccionamos el numero de pago de la tabla de credito
	$sqlCCCC = "SELECT num_pagos, credito_num FROM credito WHERE credito_id = $x_credito_id ";
	$rsCCCC = phpmkr_query($sqlCCCC,$conn) or die ("Error al seleccionar el num ero de pagos de la tabal de credito". phpmkr_error()."sql: ".$sqlCCCC);
	$rowCCCC = phpmkr_fetch_array($rsCCCC);
	$x_total_pagos = $rowCCCC["num_pagos"];
	$x_credito_num = $rowCCCC["credito_num"];
		
		// BUSCAMOS EL MONTO DE LA PP DEL AL TABALA CRM_ATREA_CV_MONTO
		
		$SQLMONTO = "SELECT monto FROM crm_tarea_cv_monto WHERE crm_tarea_id = $x_crm_tarea_id order by crm_tarea_cv_monto_id DESC ";
		$rsMonto = phpmkr_query($SQLMONTO,$conn) or die ("Error al seleccionar el monto".phpmkr_error()."sql: ".$SQLMONTO);
		$rowMonto = phpmkr_fetch_array($rsMonto);
		#echo "monto ".$SQLMONTO."<br>";
		
		$x_monto_pp = $rowMonto["monto"];
			
		/**********************************************************************
		******************** SMS CLIENTE *************************************
		**********************************************************************/
		#echo "credito_id yy".$x_credito_id."-<br> ";
		#echo "atrea_id ".$x_crm_tarea_id."<br> ";
		// sacamos los datos del cliente con credito_id
		$sqlCliente = " SELECT cliente_id FROM solicitud_cliente JOIN solicitud ON solicitud.solicitud_id = solicitud_cliente.solicitud_id JOIN ";
		$sqlCliente .= " credito ON credito.solicitud_id = solicitud.solicitud_id WHERE credito_id = $x_credito_id";
		$rsCliente = phpmkr_query($sqlCliente, $conn) or die ("Error al seleccionar el cliente _id". phpmkr_error(). "sql:". $sqlCliente);
		$rowClientet = phpmkr_fetch_array($rsCliente);
		$x_cliente_idp = 	$rowClientet["cliente_id"];
			
			if (!empty($x_cliente_idp)){
		 $sqlCelular = "SELECT numero FROM telefono WHERE cliente_id = $x_cliente_idp  AND telefono_tipo_id = 2 ORDER BY `telefono_id` DESC ";
		 $rsCelular = phpmkr_query($sqlCelular, $conn) or die ("Error al seleccioanr el numero de celuar". phpmkr_error()."sql:".$sqlCelular);
		 while($rowCelular = phpmkr_fetch_array($rsCelular)){
		 $x_no_celular = $rowCelular["numero"];
		 $x_compania = $rowCelular["compania_id"];
		// $x_no_celular = "5540663927";
		
		
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
		 if(!empty($x_no_celular) && $x_no_celular != "5555555555" && $x_no_celular != "0000000000" && $x_monto_pp > 0){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
						$x_mensaje = "CREA CRED $x_credito_num LE RECUERDA SU PROMESA DE PAGO EL DIA DE HOY ";
						if($x_monto_pp > 0){
							$x_mensaje = $x_mensaje."POR LA CANTIDAD DE $".$x_monto_pp." ";
							}
						#$x_mensaje .= " USTED PODRA OBTENER UN 10% DE DESCUENTO EN SU RENOVACION, SI CONTINUA PAGANDO PUNTUALMENTE.";							
						echo "tarea _id "-$x_crm_tarea_id."<br>";
						echo "monto ".$x_monto_pp."<br>";	
						echo "numero de celular ".$x_no_celular."<br>";
						echo "SE ENVIA EL MENSAJE AL CLIENTE <BR>";
						echo "MENSAJE :". $x_mensaje."<BR>";	
						echo strlen($x_mensaje)."<br>";									
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
						$tiposms = "5";
						$chip = "";
						#CAMBIAR A CHIP NO 1						
						//insertaSmsTabla($conn, $x_cliente_idp, $x_credito_id, $tiposms, $chip, $x_mensaje, $titulo, $x_no_celular);					
						#se manda mensaje y se actualiza la tarea del CRM a completa
						$UpdateTarea = "UPDATE  crm_tarea SET crm_tarea_status_id = 3 WHERE	crm_tarea_id =	$x_crm_tarea_id";
					    $rsUpdate = phpmkr_query($UpdateTarea,$conn) or die ("Error al actualiza la tarea".phpmkr_error()."sql:".$UpdateTarea);						
						echo "Update ".$UpdateTarea."<br>";
						// echo "<br>".$x_mensaje."<br>";	
						 $x_contador_6 ++;		
						 
						 $sqlCreditonum= "select credito_num from credito where credito_id = $x_credito_id";
						$rsCreditoNun = phpmkr_query($sqlCreditonum,$conn) or die("Error al seleccionar credito num".phpmkr_error()."sql:".$sqlCreditonum);
						$rowCN = phpmkr_fetch_array($rsCreditoNun);
						$x_credito_num = $rowCN["credito_num"];
						$x_fecha_sms = date("Y-m-d");
						$sqlInsertsms =  "INSERT INTO `sms_enviados` (`id_sms_enviado`, `id_tipo_mensaje`, `contenido`, `no_credito`, `no_celular`, `fecha_registro`, `tipo_envio`, `destino`, `cliente_id`) VALUES (NULL, '5','" .$x_mensaje."', $x_credito_num, '".$x_no_celular."', '".$x_fecha_sms."', '1', '1',$x_cliente_idp)";
	
	$result = $rsInsert = phpmkr_query($sqlInsertsms, $conn) or die ("Error al inserta en sms tabla nueva". phpmkr_error()."sql :". $sqlInsert);
	
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
//    echo "Esta dirección de correo ($email_a) es válida.";
		mail($email, $titulo2, $mensajeMail, $cabeceras2);	
		$sqlInsertsms =  "INSERT INTO `sms_mail_enviados` (`id_sms_mail_enviado`, `id_tipo_mensaje`, `contenido`, `no_credito`, `email`, `fecha_registro`, `tipo_envio`, `destino` , `cliente_id`) VALUES (NULL, '5','" .$mensajeMail."',  $x_credito_num, '".$email."', '".$x_fecha_sms."', '1', '1' ,$x_cliente_idp)";
		$rsInsert = phpmkr_query($sqlInsertsms, $conn) or die ("Error al inserta en sms tabla nueva". phpmkr_error()."sql :". $sqlInsertsms);
}
	
						
			}			
		 }//while celulares
		}
		
		
		
		/**********************************************************************
		********************   SMS AVAL   *************************************
		**********************************************************************/
		#echo "credito_id yy".$x_credito_id."<br>";
		// sacamos los datos del cliente con credito_id
		$sqlCliente = " SELECT datos_aval_id FROM datos_aval JOIN   credito ON credito.solicitud_id = datos_aval.solicitud_id WHERE credito_id = $x_credito_id";
		$rsCliente = phpmkr_query($sqlCliente, $conn) or die ("Error al seleccionar el cliente _id". phpmkr_error(). "sql:". $sqlCliente);
		$rowClientet = phpmkr_fetch_array($rsCliente);
		$x_aval_id = 	$rowClientet["datos_aval_id"];
		$x_cliente_idp = $rowClientet["cliente_id"];
			
			if (!empty($x_aval_id)){
		 $sqlCelular = "SELECT telefono_c FROM datos_aval WHERE datos_aval_id = $x_aval_id  ";
		 $rsCelular = phpmkr_query($sqlCelular, $conn) or die ("Error al seleccioanr el numero de celuar". phpmkr_error()."sql:".$sqlCelular);
		 while($rowCelular = phpmkr_fetch_array($rsCelular)){
		 $x_no_celular = $rowCelular["numero"];
		 $x_compania = $rowCelular["compania_id"];
		// $x_no_celular = "5540663927";
		 	#echo "numero de celular ".$x_no_celular."<br>";
		 ################################################################################################################## 
		#####################################################  SMS  ######################################################
		################################################################################################################## 		
		 // ya tenemos el numero de celular  y los datos del mensaje, ahora hacemos el envio del mensaje de texto al celular
		 if(!empty($x_no_celular) && $x_no_celular != "5555555555"){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
						$x_mensaje = "CREA CRED $x_credito_num LE INFORMA QUE LA CUENTA QUE USTED AVALA TIENE  PROMESA DE PAGO PARA EL DIA DE HOY. ";
						#$x_mensaje .= " USTED PODRA OBTENER UN 10% DE DESCUENTO EN SU RENOVACION, SI CONTINUA PAGANDO PUNTUALMENTE.";
						echo "TAMBIEN AL AVAL<BR>";	
						echo "MENSAJE :". $x_mensaje."<BR>";										
						// Varios destinatarios
						$para  = 'sms@financieracrea.com'; // atención a la coma
						// subject
						$titulo = $x_no_celular;						
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						// Mail it						
						mail($para, $titulo, $x_mensaje, $cabeceras);						
						
						$tiposms = "15";
						$chip = "";
						#CAMBIAR A CHIP NO 1
						
						//insertaSmsTabla($conn, $x_cliente_idp, $x_credito_id, $tiposms, $chip, $x_mensaje, $titulo, $x_no_celular);					
						#se manda mensaje y se actualiza la tarea del CRM a completa
						
						// echo "<br>".$x_mensaje."<br>";	
						
						 $x_contador_6 ++;	
						 
						  $sqlCreditonum= "select credito_num from credito where credito_id = $x_credito_id";
						$rsCreditoNun = phpmkr_query($sqlCreditonum,$conn) or die("Error al seleccionar credito num".phpmkr_error()."sql:".$sqlCreditonum);
						$rowCN = phpmkr_fetch_array($rsCreditoNun);
						$x_credito_num = $rowCN["credito_num"];
						$x_fecha_sms = date("Y-m-d");
						$sqlInsertsms =  "INSERT INTO `sms_enviados` (`id_sms_enviado`, `id_tipo_mensaje`, `contenido`, `no_credito`, `no_celular`, `fecha_registro`, `tipo_envio`, `destino`) VALUES (NULL, '15','" .$x_mensaje."', $x_credito_num, '".$x_no_celular."', '".$x_fecha_sms."', '1', '2')";
	
	$result = $rsInsert = phpmkr_query($sqlInsertsms, $conn) or die ("Error al inserta en sms tabla nueva". phpmkr_error()."sql :". $sqlInsert);
						 
						 	
						
			}			
		 }// while celulares
		}
		
		
		
		
		}
echo "<br><br>".$x_contador_6."<br><br>";
}

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

