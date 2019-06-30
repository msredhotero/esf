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
# CREDITO LIQUIDADO....
// SELECCIONAMOS TODOS LOS PAGOS DE AYER Y VEMOS CUALES CREDITOS SE LIQUIDARON EL DIA DE AYER.
$sqlMañana = " SELECT DATE_SUB(CURDATE(),INTERVAL 1 DAY) AS ayer";
$rsMañana =  phpmkr_query($sqlMañana, $conn) or die ("erorr al seleccionar el dia de mañana ". phpmkr_error()."sql:". $sqlMañana);
$rowMañana = phpmkr_fetch_array($rsMañana);
$x_ayer = $rowMañana["ayer"];

// seleccionamos todos los pagos que se registraron ayer
$sqlPagos  = " SELECT vencimiento.credito_id ";
$sqlPagos .= " FROM recibo ";
$sqlPagos .= " JOIN recibo_vencimiento ON recibo_vencimiento.recibo_id = recibo.recibo_id";
$sqlPagos .= " JOIN vencimiento ON vencimiento.vencimiento_id = recibo_vencimiento.vencimiento_id ";
$sqlPagos .= " WHERE fecha_registro = \"$x_ayer\" ";
$sqlPagos .= " GROUP BY credito_id ";
$rsPagos = phpmkr_query($sqlPagos, $conn)or die ("error al selc los pagos de ayer para cliente distinguido". phpmkr_error()."sql:".$sqlPagos);
$x_listado_creditos_pagados = "";
while ($rowPagos = phpmkr_fetch_array($rsPagos)){
	$x_listado_creditos_pagados = $x_listado_creditos_pagados.$rowPagos["credito_id"].", ";
	}
$x_listado_creditos_pagados = substr($x_listado_creditos_pagados, 0, strlen($x_listado_creditos_pagados)-2); 

if(empty($x_listado_creditos_pagados)){
	$x_listado_creditos_pagados = 0;
	}
$sqlVencimientos = "SELECT * FROM vencimiento JOIN credito ON credito.credito_id = vencimiento.credito_id WHERE vencimiento.credito_id in ( $x_listado_creditos_pagados ) AND  credito.credito_status_id in (3) group by vencimiento.credito_id ORDER BY `vencimiento`.`vencimiento_id` ASC ";
		$responseVenc = phpmkr_query($sqlVencimientos, $conn) or die ("error al seleccionar los vencimientos".phpmkr_error()."sql:".$sqlVencimientos);
		$x_inicio = 1;
		$x_contador_3 = 0;
		while ($rowvenc = phpmkr_fetch_array($responseVenc)){
			$x_credito_id_liquidado = $rowvenc["credito_id"];
			
			
			// seleccionamos si se le genero una comision al credito
			$sqlDatosCredito = "SELECT * FROM credito WHERE credito_id = $x_credito_id_liquidado";
			$rsDatosCredito = phpmkr_query($sqlDatosCredito, $conn) or die ("Error al seleccionar los datos del credito". phpmkr_error()."sql:".$sqlDatosCredito);
			$rowDatosCredito = phpmkr_fetch_array($rsDatosCredito);
			$x_penalizacion = $rowDatosCredito["penalizacion"];
			$x_garantia_liquida = $rowDatosCredito["garantia_liquida"];
			$x_credito_num = $rowDatosCredito["credito_num"];
			
			// si existe la penalizacion, buscamos si al credito se le genero alguna comision por gasto de cobranza legal y buscamos el status de la comison
			$x_vencimiento_num_comision = 0;
			if($x_penalizacion > 0){
			 $sqlDatosCredito = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id_liquidado AND vencimiento_num >= 3000";
			$rsDatosCredito = phpmkr_query($sqlDatosCredito, $conn) or die ("Error al seleccionar los datos del credito". phpmkr_error()."sql:".$sqlDatosCredito);
			$rowDatosCredito = phpmkr_fetch_array($rsDatosCredito);
			$x_vencimiento_num_comision = $rowDatosCredito["vencimiento_num"];
			$x_vencimiento_status_id  = $rowDatosCredito["vencimiento_status_id"];		
			}
			// se envia mensaje
			
			$sqlClientel = " SELECT cliente_id FROM solicitud_cliente JOIN solicitud ON solicitud.solicitud_id = solicitud_cliente.solicitud_id JOIN ";
			$sqlClientel .= " credito ON credito.solicitud_id = solicitud.solicitud_id WHERE credito_id = $x_credito_id_liquidado";
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
		 
		//seleccionamos el email del cliente
		$sqlMail = "SELECT  email FROM  cliente WHERE  cliente_id = ".$x_cliente_idl." ";
		$rsMail =  phpmkr_query($sqlMail,$conn) or die ("Error al seelccionar el mail");
		$rowmail =  phpmkr_fetch_array($rsMail);
		$email =  $rowmail["email"];
		echo " <br> ".$sqlMail;
		echo "eamail ".$email ."<br>";
				 
		 
		 
		 $x_mensaje = "";
		 //creditos que se liquidaron y que no tienen comision de cobranza generada.. solo incluye los que entran en el nuevo esquema de penalizaciones
		  if($x_penalizacion > 0){
			  // los que no tienen comision generada
			  if($x_vencimiento_num_comision == 0){				  
				  $x_mensaje = "CREA CRED $x_credito_num SU CREDITO HA SIDO LIQUIDADO. USTED PODRA OBTENER UN NUEVO CREDITO ";
				  $x_mensaje .= " COMUNICANDOSE AL: DF 51350259:LADA SIN COSTO 018008376133.";
				  
				  }else{
					  // se les genero comision					  
				  $x_mensaje = "FINANCIERA CREA: SU CRÉDITO NUMERO $x_credito_num HA SIDO LIQUIDADO. GRACIAS.";
				  
					  }		  
			  }else{
				  // son los creditos anteriores
				   $x_mensaje = "FINANCIERA CREA: SU CRÉDITO NUMERO $x_credito_num HA SIDO LIQUIDADO. GRACIAS.";
				  
				  }
		 
		 // ya tenemos el numero de celular  y los datos del mensaje, ahora hacemos el envio del mensaje de texto al celular
		 if(!empty($x_no_celular) &&  $x_no_celular != "5555555555"){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
												
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
						$x_contador_3 ++;
						
						$tiposms = "8";
						$chip = "";
						#cambiamos a chip 2
						
						
						 $sqlCreditonum= "select credito_num from credito where credito_id = $x_credito_id_liquidado";
						$rsCreditoNun = phpmkr_query($sqlCreditonum,$conn) or die("Error al seleccionar credito num".phpmkr_error()."sql:".$sqlCreditonum);
						$rowCN = phpmkr_fetch_array($rsCreditoNun);
						$x_credito_num = $rowCN["credito_num"];
						$x_fecha_sms = date("Y-m-d");
						$sqlInsertsms =  "INSERT INTO `sms_enviados` (`id_sms_enviado`, `id_tipo_mensaje`, `contenido`, `no_credito`, `no_celular`, `fecha_registro`, `tipo_envio`, `destino`, `cliente_id`) VALUES (NULL, '8','" .$x_mensaje."', $x_credito_num, '".$x_no_celular."', '".$x_fecha_sms."', '1', '1',$x_cliente_idl)";
	
	$result = $rsInsert = phpmkr_query($sqlInsertsms, $conn) or die ("Error al inserta en sms tabla nueva". phpmkr_error()."sql :". $sqlInsert);
						
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
//    echo "Esta dirección de correo ($email_a) es válida.";
mail($email, $titulo2, $mensajeMail, $cabeceras2);	
$sqlInsertsms =  "INSERT INTO `sms_mail_enviados` (`id_sms_mail_enviado`, `id_tipo_mensaje`, `contenido`, `no_credito`, `email`, `fecha_registro`, `tipo_envio`, `destino`) VALUES (NULL, '8','" .$mensajeMail."', $x_credito_num, '".$email."', '".$x_fecha_sms."', '1', '1')";
$rsInsert = phpmkr_query($sqlInsertsms, $conn) or die ("Error al inserta en sms tabla nueva". phpmkr_error()."sql :". $sqlInsertsms);
}				
						
			}			
		}		
			
			
		#	echo "CREDITO LIQUIDADO AYER".$x_credito_id_liquidado."<BR>";
			} // while celulares
			
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