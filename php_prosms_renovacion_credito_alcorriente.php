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
###########################################################################################
###########################################################################################
######### RENOVACIÓN POR CRÉDITO PENDIENTE POR LIQUIDAR AL CORRIENTE ######################
###########################################################################################
###########################################################################################



  
  $sqlCreditos4 = " SELECT   DISTINCT `fecha_vencimiento`, credito_id, count( * ) AS numero_vencimientos FROM vencimiento WHERE `vencimiento_status_id`";
  $sqlCreditos4 .= " IN ( 1, 3 ) GROUP BY credito_id HAVING numero_vencimientos <= 4";
  $rsCredito4 =  phpmkr_query($sqlCreditos4, $conn) or die ("Error al seleccionar los creditos con 4 venc".phpmkr_error()."sql:".$sqlCreditos4);
  while($rowCredito4 = phpmkr_fetch_array($rsCredito4)){
	  $x_credito_id = $rowCredito4["credito_id"];
	  $x_numero_vencimientos = $rowCredito4["numero_vencimientos"];
	  
	 
	  // seleccionamos la fecha del ultimo vencimiento del credito peniente de pago
	  # seleccionamos la fecha del ultimo vencimeinto, para generar las penalizaciones con esa misma fecha
		$sqlFecha = "SELECT  fecha_vencimiento  FROM vencimiento WHERE credito_id = $x_credito_id  order by  vencimiento_num DESC limit 0,1 ";
		$rsFecha = phpmkr_query($sqlFecha, $conn) or die ("Error al seleccioanr la fecha del ultimo venciento.". phpmkr_error(). "sql:".$sqlFecha);
		$rowFecha = phpmkr_fetch_array($rsFecha);
		//$x_fecha_ultimo_vencimiento = $rowFecha["fecha_vencimiento"];
	  
	  
	    // seleccionamos la forma de pago del credito
		$sqlCreditodD = "SELECT * FROM credito WHERE credito_id  = $x_credito_id";
		$rsCreditoD = phpmkr_query($sqlCreditodD, $conn) or die("error al seleccionar los datos del credito".phpmkr_error()."sql".$sqlCreditodD);
		$rowCreditoD = phpmkr_fetch_array($rsCreditoD);
		$x_forma_pago_id = $rowCreditoD["forma_pago_id"];
		
		$x_hoy = date("y-m-d");
		$x_fecha_compara = "";
		$x_limit = "0,1";
		if($x_forma_pago_id == 1){
			$x_limit = "2,1";
			}else{
			$x_limit = "1,1";
			}
		
		$sqlPenultimoVencimeinto = "SELECT DISTINCT  `fecha_vencimiento` FROM `vencimiento` WHERE `credito_id` = $x_credito_id ORDER BY `fecha_vencimiento` DESC LIMIT $x_limit ";
		$rsPenultimoVencimeinto = phpmkr_query($sqlPenultimoVencimeinto, $conn) or die ("error al seleccionar el penultimo vencimiento".phpmkr_error()."sql".$sqlPenultimoVencimeinto);
		$rowPenultimoVencimeinto = phpmkr_fetch_array($rsPenultimoVencimeinto); 
		$x_fecha_ultimo_vencimiento = $rowPenultimoVencimeinto["fecha_vencimiento"];
		
		echo "fecha penultimo vencimiento". $x_fecha_ultimo_vencimiento."-";
		
		
		
		//$sqlMañana = " SELECT DATE_SUB(CURDATE(),INTERVAL 1 WEEK) AS semana_antes_penultimo_pago";
		
		$sqlMañana = " SELECT DATE_SUB(\"$x_fecha_ultimo_vencimiento\",INTERVAL 1 WEEK) AS semana_antes_penultimo_pago";
		$rsMañana =  phpmkr_query($sqlMañana, $conn) or die ("erorr al seleccionar el dia de mañana 2 ". phpmkr_error()."sql:". $sqlMañana);
		$rowMañana = phpmkr_fetch_array($rsMañana);
		$x_semana_antes_penultimo_pago = $rowMañana["semana_antes_penultimo_pago"];
		 echo "una semana anates del penultimo vencimeinto ".$x_semana_antes_penultimo_pago."- ";
		
		if($x_semana_antes_penultimo_pago == $x_hoy){
		// se hace el calculo para ver si se envia el mensaje.
		// seleccionamos todos los vencimientos pagados y la fecha en que se pagaron y vemos si se pagaron bien o con atraso		
		// cuando la fecha de pago sea diferente a la fecha de venciiento,se considera como pago malo.
		// seleccionamos todos los pagos que se registraron ayer
		$sqlVencimientos = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and vencimiento.vencimiento_status_id = 2 ORDER BY `vencimiento`.`vencimiento_id` ASC ";
		$responseVenc = phpmkr_query($sqlVencimientos, $conn) or die ("error al seleccionar los vencimientos".phpmkr_error()."sql:".$sqlVencimientos);
		$x_inicio = 1;
		$x_pago_con_atraso = 0;
		echo "pago con atraso ".$x_pago_con_atraso."- ";
		while ($rowvenc = phpmkr_fetch_array($responseVenc)){
			$x_vencimiento_id = $rowvenc["vencimiento_id"];
			//echo "vencimeinto _id =".$x_vencimiento_id."<br>";
			$x_credito_id = $rowvenc["credito_id"];
		#	echo "<br><br> CREDITO_ID".$x_credito_id."<br>";
			$x_fecha_vencimiento = $rowvenc["fecha_vencimiento"];
		
		
		
		
		//echo "fecha venciamiento".$x_fecha_vencimiento."<br>";
			$sSqlFP = "SELECT fecha_pago FROM recibo JOIN recibo_vencimiento ON recibo_vencimiento.recibo_id = recibo.recibo_id JOIN vencimiento ON  vencimiento.vencimiento_id = recibo_vencimiento.vencimiento_id  WHERE vencimiento.credito_id =  $x_credito_id  and vencimiento.vencimiento_id = $x_vencimiento_id ";
		#	echo "sql:".$sSqlFP;
			$responseFP = phpmkr_query($sSqlFP,$conn) or die("Erorr al seleccionar la fecha de pago".phpmkr_error()."sql:".$sSqlFP);
			//while($rowFP = phpmkr_fetch_array($responseFP)){
			$rowFP = phpmkr_fetch_array($responseFP);
			$x_fecha_pago = $rowFP["fecha_pago"];
		#	echo "fecha pago =".$x_fecha_pago."<br>";			
			
			if($x_fecha_pago > $x_fecha_vencimiento){
			$x_pago_con_atraso = 1;
			}		
			
			
		
		}// while fecha vencimeinto
		
		if($x_pago_con_atraso  == 0){
			echo "pago con atraso en igual a 0 entro al if ";  
			
			// se envia el mail al cliente
			// sacamos los datos del cliente con credito_id
		$sqlCliente = " SELECT cliente_id FROM solicitud_cliente JOIN solicitud ON solicitud.solicitud_id = solicitud_cliente.solicitud_id JOIN ";
		$sqlCliente .= " credito ON credito.solicitud_id = solicitud.solicitud_id WHERE credito.credito_id = $x_credito_id";
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
		 
		 
	    ################################################################################################################## 
		#####################################################  SMS  ######################################################
		################################################################################################################## 		
		 // ya tenemos el numero de celular  y los datos del mensaje, ahora hacemos el envio del mensaje de texto al celular
		 if(!empty($x_no_celular) && $x_no_celular != "5555555555"){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
						$x_mensaje = "CLIENTE DISTINGUIDO FINANCIERA CREA:";
						$x_mensaje .= " USTED PUEDE SOLICITAR DESDE HOY SU RENOVACION DE CREDITO A LOS TELEFONOS: DF 51350259, 018008376133";
											
						echo "MENSAJE :". $x_mensaje."<BR>";										
						// Varios destinatarios
						$para  = 'sms@financieracrea.com'; // atención a la coma
						// subject
						$titulo = $x_no_celular;						
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						// Mail it						
						mail($para, $titulo, $x_mensaje, $cabeceras);												
						$tiposms = "5";
						$chip = "";
						#CAMBIAR A CHIP NO 1						
						insertaSmsTabla($conn, $x_cliente_idp, $x_credito_id, $tiposms, $chip, $x_mensaje, $titulo, $x_no_celular);					
						#se manda mensaje y se actualiza la tarea del CRM a completa				
						 
						 $x_contador_6 ++;	
						 
						 
						 $sqlCreditonum= "select credito_num from credito where credito_id = $x_credito_id";
						$rsCreditoNun = phpmkr_query($sqlCreditonum,$conn) or die("Error al seleccionar credito num".phpmkr_error()."sql:".$sqlCreditonum);
						$rowCN = phpmkr_fetch_array($rsCreditoNun);
						$x_credito_num = $rowCN["credito_num"];
						$x_fecha_sms = date("Y-m-d");
						$sqlInsertsms =  "INSERT INTO `sms_enviados` (`id_sms_enviado`, `id_tipo_mensaje`, `contenido`, `no_credito`, `no_celular`, `fecha_registro`, `tipo_envio`, `destino`) VALUES (NULL, '6','" .$x_mensaje."', $x_credito_num, '".$x_no_celular."', '".$x_fecha_sms."', '1', '1')";
	
	$result = $rsInsert = phpmkr_query($sqlInsertsms, $conn) or die ("Error al inserta en sms tabla nueva". phpmkr_error()."sql :". $sqlInsert);
						 
						 
						 
						 
					}	
		 }//while celulares
			}			
			
		}
		
		
		}//semana == hoy			
	}//main while
	
	
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