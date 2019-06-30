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

###############################################################################################################
###############################################################################################################
###############################################################################################################
############################   FELIZ CUMPLEAÑOS   #############################################################
###############################################################################################################
###############################################################################################################
###############################################################################################################
$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
$currdate = ConvertDateToMysqlFormat($currdate);
$x_today = date("Y-m-d");
$x_fecha_base_busqueda = "2012-06-01";
$x_fecha_sep = explode("-",$x_today);
$x_anio = $x_fecha_sep[0];
$x_mes = $x_fecha_sep[1];
$x_dia = $x_fecha_sep[2];


//SELECT name, birth FROM pet WHERE MONTH(birth) = 5;

$sqlCumple = "SELECT cliente.*, solicitud.solicitud_id FROM solicitud JOIN solicitud_cliente ON solicitud_cliente.solicitud_id = solicitud.solicitud_id JOIN  cliente ON cliente.cliente_id = solicitud_cliente.cliente_id WHERE solicitud.fecha_registro >= \"$x_fecha_base_busqueda\" and  MONTH(cliente.fecha_nac) = $x_mes and  DAY(cliente.fecha_nac) = $x_dia ";
$rsCumple = phpmkr_query($sqlCumple, $conn) or die ("Error al seleccionar la fecha de la la solcituid".phpmkr_error()."sql:".$sqlCumple);
echo "sql fecha cumpleaños ".$sqlCumple."<br><br>";
while($rowCumple = phpmkr_fetch_array($rsCumple)){
	$x_solicitud_id = $rowCumple["solicitud_id"];
	$x_cliente_id = $rowCumple["cliente_id"];
	echo "<br>sol_id".$x_solicitud_id ."-";
	echo "<br>cliente _id".$x_cliente_id."- ";
	// buscamos los datos
	// seleccionamso los clientes del dia de hoy
	
	
		
		// sacamos los datos del cliente con credito_id
		$sqlCliente = " SELECT cliente_id FROM solicitud_cliente JOIN solicitud ON solicitud.solicitud_id = solicitud_cliente.solicitud_id JOIN ";
		$sqlCliente .= " credito ON credito.solicitud_id = solicitud.solicitud_id WHERE solicitud.solicitud_id = $x_solicitud_id";
		$rsCliente = phpmkr_query($sqlCliente, $conn) or die ("Error al seleccionar el cliente _id". phpmkr_error(). "sql:". $sqlCliente);
		$rowClientet = phpmkr_fetch_array($rsCliente);
		$x_cliente_idp = 	$x_cliente_id;
			
			if (!empty($x_cliente_idp)){
		 $sqlCelular = "SELECT numero FROM telefono WHERE cliente_id = $x_cliente_idp  AND telefono_tipo_id = 2 ORDER BY `telefono_id` DESC ";
		 $rsCelular = phpmkr_query($sqlCelular, $conn) or die ("Error al seleccioanr el numero de celuar". phpmkr_error()."sql:".$sqlCelular);
		 while($rowCelular = phpmkr_fetch_array($rsCelular)){
		 $x_no_celular = $rowCelular["numero"];
		 $x_compania = $rowCelular["compania_id"];
		// $x_no_celular = "5540663927";
		 echo "<br> numero de celular ". $x_no_celular;
		 
		 //seleccionamos el email del cliente
		$sqlMail = "SELECT  email FROM  cliente WHERE  cliente_id = ".$x_cliente_idp." ";
		$rsMail =  phpmkr_query($sqlMail,$conn) or die ("Error al seelccionar el mail");
		$rowmail =  phpmkr_fetch_array($rsMail);
		$email =  $rowmail["email"];
		echo " <br> ".$sqlMail;
		echo "eamail ".$email ."<br>";

		 
						 $sqlCreditonum= "select credito_num from credito where solicitud_id = $x_solicitud_id";
						$rsCreditoNun = phpmkr_query($sqlCreditonum,$conn) or die("Error al seleccionar credito num".phpmkr_error()."sql:".$sqlCreditonum);
						$rowCN = phpmkr_fetch_array($rsCreditoNun);
						$x_credito_num = $rowCN["credito_num"];
		 
	    ################################################################################################################## 
		#####################################################  SMS  ######################################################
		################################################################################################################## 		
		 // ya tenemos el numero de celular  y los datos del mensaje, ahora hacemos el envio del mensaje de texto al celular
		 if(!empty($x_no_celular) && $x_no_celular != "5555555555" && !empty($x_credito_num)){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
						$x_mensaje = "FINANCIERA CREA LE DESEA FELIZ CUMPLEANOS!! ";
						#$x_mensaje .= " USTED PODRA OBTENER UN 10% DE DESCUENTO EN SU RENOVACION, SI CONTINUA PAGANDO PUNTUALMENTE.";							
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
						$tiposms = "7";
						$chip = "";
						
						
						
						
						$x_fecha_sms = date("Y-m-d");
						$sqlInsertsms =  "INSERT INTO `sms_enviados` (`id_sms_enviado`, `id_tipo_mensaje`, `contenido`, `no_credito`, `no_celular`, `fecha_registro`, `tipo_envio`, `destino`, `cliente_id`) VALUES (NULL, '7','" .$x_mensaje."', $x_credito_num, '".$x_no_celular."', '".$x_fecha_sms."', '1', '1',$x_cliente_idp)";
	
	$rsInsert = phpmkr_query($sqlInsertsms, $conn) or die ("Error al inserta en sms tabla nueva q". phpmkr_error()."sql :". $sqlInsertsms);
						
						if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
						    echo "Esta dirección de correo ($email_a) es válida.";
						mail($email, $titulo2, $mensajeMail, $cabeceras2);	
						$sqlInsertsms =  "INSERT INTO `sms_mail_enviados` (`id_sms_mail_enviado`, `id_tipo_mensaje`, `contenido`, `no_credito`, `email`, `fecha_registro`, `tipo_envio`, `destino`) VALUES (NULL, '7','" .$mensajeMail."', $x_credito_num, '".$email."', '".$x_fecha_sms."', '1', '1')";
						$rsInsert = phpmkr_query($sqlInsertsms, $conn) or die ("Error al inserta en sms tabla nueva". phpmkr_error()."sql :". $sqlInsertsms);
						}
						
						
						
						 
					}		
		 }// while celulares
			}
			
	}//fin if sol y client no empty
	



?>

<?php 

phpmkr_db_close($conn);
?>