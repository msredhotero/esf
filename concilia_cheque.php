<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php session_start(); ?>
<?php
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$x_credito_id = $_GET["x_credito_id"]; #1
$x_forma_pago = $_GET["q2"];#2

	$x_result = "";
if($x_credito_id > 0) {	
	    $sqlSe = "SELECT status FROM  `cheque_conciliado` WHERE credito_id= $x_credito_id " ;
		$rsSe = phpmkr_query($sqlSe, $conn) or die ("error alseleccioanr el status". phpmkr_error()."sql".$sqlSe);
		$rowSe = phpmkr_fetch_array($rsSe);
		$x_status = $rowSe["status"];
		
		if($x_status == 1){
			$sql  = "UPDATE `cheque_conciliado` SET status = 0 WHERE credito_id= $x_credito_id";
			if (($_SESSION["php_project_esf_status_UserRolID"] == 1)){
			$x_result .='<input type="checkbox" name="x_concilia_cheque'.$x_credito_id.'"  onclick="ConciliaCheque('.$x_credito_id.');" />';
			}		
			}else {
				$sql  = "UPDATE `cheque_conciliado` SET status = 1 WHERE credito_id= $x_credito_id";			
				if (($_SESSION["php_project_esf_status_UserRolID"] == 4)){
				$x_result .='<input type="checkbox" name="x_concilia_cheque" disabled="disabled"  checked="checked" />';
				}else{
					$x_result .='<input type="checkbox" name="x_concilia_cheque'.$x_credito_id.'" checked="checked" onclick="ConciliaCheque('.$x_credito_id.');" />';
					}
				$x_result .= "Conciliado";
				// aqui el cheque ya se concilio ahora mandamos el sms al cleinte para informale que sui cheque ya fue cobrado.				
				$sqlNoC = "SELECT cliente.cliente_id FROM  cliente JOIN solicitud_cliente ON solicitud_cliente.cliente_id = cliente.cliente_id JOIN solicitud ON solicitud.solicitud_id = solicitud_cliente.solicitud_id JOIN credito ON credito.solicitud_id = solicitud.solicitud_id  where credito_id = $x_credito_id ";
				$rsNoC = phpmkr_query($sqlNoC, $conn) or die ("Error al seleccionar el id del cliente".phpmkr_error()."SQL:".$sqlNoC);
				$rowNoC = phpmkr_fetch_array($rsNoC);
				$x_cliente_id = $rowNoC["cliente_id"];		
				
				//echo "cliente id ".$sqlNoC;
				
				
				
						
				if (!empty($x_cliente_id)){
					# si ya tenemos el numero de cliete encotnces buscamos us telefono de tipo celular.
					# telefono_tipo_id = celular
					# simepre se toma el ultimo numero de celualr que esta registrado puesto que si se registro, es porque se cambio;
					//echo "entro";
						//seleccionamos el email del cliente
						$sqlMail = "SELECT  email FROM  cliente WHERE  cliente_id = ".$x_cliente_id." ";
						$rsMail =  phpmkr_query($sqlMail,$conn) or die ("Error al seelccionar el mail");
						$rowmail =  phpmkr_fetch_array($rsMail);
						$email =  $rowmail["email"];
						//echo " <br> ".$sqlMail;
						//echo "eamail ".$email ."<br>";					
					
					
					$sqlCelular = "SELECT numero FROM telefono WHERE cliente_id = $x_cliente_id  AND telefono_tipo_id = 2 ORDER BY `telefono_id` DESC ";
					$rsCelular = phpmkr_query($sqlCelular, $conn) or die ("Error al seleccioanr el numero de celuar". phpmkr_error()."sql:".$sqlCelular);
					$rowCelular = phpmkr_fetch_array($rsCelular);
					$x_no_celular = $rowCelular["numero"];
					//echo "celular". $sqlCelular;
					//$x_no_celular = "5540663927";					
					if(!empty($x_no_celular) && $x_no_celular != "5555555555" && $x_no_celular != "0000000000"){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
						$x_mensaje = "FINANCIERA CREA: SU CHEQUE HA SIDO COBRADO. AGRADECEMOS SU PREFERENCIA.";
						$x_mensaje .= " LINEA DE ATENCION A CLIENTES:DF 51350259:LADA SIN COSTO 018008376133";						
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
						$tiposms =  1;
												
						#se manda mensaje y se actualiza la tarea del CRM a completa
						
						$sqlCreditonum= "select credito_num from credito where credito_id = $x_credito_id";
						$rsCreditoNun = phpmkr_query($sqlCreditonum,$conn) or die("Error al seleccionar credito num".phpmkr_error()."sql:".$sqlCreditonum);
						$rowCN = phpmkr_fetch_array($rsCreditoNun);
						$x_credito_num = $rowCN["credito_num"];
						$x_fecha_sms = date("Y-m-d");
						$sqlInsertsms =  "INSERT INTO `sms_enviados` (`id_sms_enviado`, `id_tipo_mensaje`, `contenido`, `no_credito`, `no_celular`, `fecha_registro`, `tipo_envio`, `destino` , `cliente_id`) VALUES (NULL, '1','" .$x_mensaje."', $x_credito_num, '".$x_no_celular."', '".$x_fecha_sms."', '1', '1',$x_cliente_id)";
	
	$rsInsert = phpmkr_query($sqlInsertsms, $conn) or die ("Error al inserta en sms tabla nueva". phpmkr_error()."sql :". $sqlInsert);
						
						
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
//    echo "Esta dirección de correo ($email_a) es válida.";
mail($email, $titulo2, $mensajeMail, $cabeceras2);	
$sqlInsertsms =  "INSERT INTO `sms_mail_enviados` (`id_sms_mail_enviado`, `id_tipo_mensaje`, `contenido`, `no_credito`, `email`, `fecha_registro`, `tipo_envio`, `destino`, `cliente_id`) VALUES (NULL, '1','" .$mensajeMail."', $x_credito_num, '".$email."', '".$x_fecha_sms."', '1', '1',$x_cliente_id)";
$rsInsert = phpmkr_query($sqlInsertsms, $conn) or die ("Error al inserta en sms tabla nueva". phpmkr_error()."sql :". $sqlInsertsms);
}					
						
						
						}					
					}				
				}
	$rsSe = phpmkr_query($sql, $conn) or die ("error alseleccioanr el status". phpmkr_error()."sql".$sqlSe);
		echo $x_result;

}else{
 echo "No localizado";
}


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


?>