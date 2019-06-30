<?php set_time_limit(0); ?>
<?php session_start(); ?>
<?php ob_start(); ?>

<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php include ("utilerias/datefunc.php") ?>

<?php
// duplica los mensajes enviados y los manda al aval

//$currdate = "2007-07-10";
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$x_hoy = date("Y-m-d");
$x_tipo = array(1 => 26,2=>27,3=>28,6 =>29,9=>30,10=>31,12=>32,17=>33,23=>34,24=>25);
print_r($x_tipo);
foreach($x_tipo as $campo => $valor){
$sqlSMSenviado = "SELECT * FROM `sms_enviados`  WHERE fecha_registro = \"$x_hoy\" AND `enviado_aval` IS NULL and id_tipo_mensaje = $campo and  destino =  1 ";
echo $sqlSMSenviado."<br>";
$rsSMSenviado =  phpmkr_query($sqlSMSenviado,$conn) or die ("Error al seleccionar los ssms enviadod hoy".phpmkr_error()."sql : ".$sqlSMSenviado);
while($rowSMSenviado = phpmkr_fetch_array($rsSMSenviado)){
	echo  "<br>".$rowSMSenviado[0].$rowSMSenviado[1].$rowSMSenviado[2].$rowSMSenviado[3].$rowSMSenviado[4].$rowSMSenviado[5]."";
	$x_credito_num = $rowSMSenviado["no_credito"];
	$x_id_sms_enviado = $rowSMSenviado["id_sms_enviado"];
	$x_contenido = $rowSMSenviado["contenido"];
	
	
	
	
	// seleccionamos los datos del aval
		/**********************************************************************
		********************   SMS AVAL   *************************************
		**********************************************************************/
		#echo "credito_id yy".$x_credito_id."<br>";
		// sacamos los datos del cliente con credito_id
		$sqlCliente = " SELECT datos_aval_id FROM datos_aval JOIN   credito ON credito.solicitud_id = datos_aval.solicitud_id WHERE credito_num = $x_credito_num";
		$rsCliente = phpmkr_query($sqlCliente, $conn) or die ("Error al seleccionar el cliente _id". phpmkr_error(). "sql:". $sqlCliente);
		$rowClientet = phpmkr_fetch_array($rsCliente);
		$x_aval_id = 	$rowClientet["datos_aval_id"];
		$x_cliente_idp = $rowClientet["cliente_id"];
			echo "<br> AVAL ID ".$x_aval_id;
			if (!empty($x_aval_id)){
		 $sqlCelular = "SELECT telefono_c FROM datos_aval WHERE datos_aval_id = $x_aval_id  ";
		 $rsCelular = phpmkr_query($sqlCelular, $conn) or die ("Error al seleccioanr el numero de celuar". phpmkr_error()."sql:".$sqlCelular);
		 while($rowCelular = phpmkr_fetch_array($rsCelular)){
		 $x_no_celular = $rowCelular["telefono_c"];
		 $x_compania = $rowCelular["compania_id"];
		// $x_no_celular = "5540663927";
		 	echo "numero de celular ".$x_no_celular."<br>";
		 ################################################################################################################## 
		#####################################################  SMS  ######################################################
		################################################################################################################## 		
		 // ya tenemos el numero de celular  y los datos del mensaje, ahora hacemos el envio del mensaje de texto al celular
		 if(!empty($x_no_celular) && $x_no_celular != "5555555555"){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
						$x_mensaje = $x_contenido;
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
						$sqlInsert = "INSERT INTO sms_enviados(id_tipo_mensaje,contenido,no_credito,no_celular,fecha_registro,tipo_envio,destino,cliente_id,enviado_aval) ( SELECT id_tipo_mensaje,contenido,no_credito,no_celular,fecha_registro,tipo_envio,destino,cliente_id,enviado_aval
 FROM sms_enviados WHERE id_sms_enviado = $x_id_sms_enviado )";
	$rsInsert = phpmkr_query($sqlInsert,$conn) or die("erro ar indert".phpmkr_error()."sql:".$sqlInsert);
	$id_sms_aval = mysql_insert_id();
	echo "id generado ".$id;
	$sqlUpdate = "UPDATE sms_enviados SET destino = 2 WHERE id_sms_enviado =$id_sms_aval ";
	$rsUpdate = phpmkr_query($sqlUpdate,$conn);
						
			}			
		 }// while celulares
		}
	
	
	
	$sqlUpdate = "UPDATE sms_enviados SET enviado_aval = 1 WHERE id_sms_enviado =$x_id_sms_enviado ";
	$rsUpdate = phpmkr_query($sqlUpdate,$conn);
	
	
	//die();
	
	}// while sms enviado
	
}






?>