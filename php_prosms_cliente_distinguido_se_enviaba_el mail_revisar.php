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
//echo "<br><br>".$x_contador_1."<br><br>";

//CLENTE DISTINGUIDO
# SELECCIONAMOS TODO LOS PAGOS DEL DIA DE AYER, Y VEMOS SI ALGUNO ES EL PENULTIMO PAGO DE ALGUN CREDITO SI ES ASI, REVISAMOS SI SE PAGARON BIEN LOS DEMAS VENCIMIENTOS SI NINGUNO PRESENTA ATRASO ENTONCES SE ENVIA EL MENSAJE DE CLIENTE DISTINGUIDO CON UN 10% DE EDESCUANTO EN SU PROXIMO CREDITO.
# revisamos todos los pagos efectuados el dia de ayer
# si alguno corresponde al penultimo pago del credito entonces
# revisamos que todos los pagos del credito no presenten atrasos
# si todo se pago a tiempo entonces entonces mandamos el mensaje de cliente distinguido con un 10% de descuento en el siguente credito
$sqlMañana = " SELECT DATE_SUB(CURDATE(),INTERVAL 1 DAY) AS ayer";
$rsMañana =  phpmkr_query($sqlMañana, $conn) or die ("erorr al seleccionar el dia de mañana ". phpmkr_error()."sql:". $sqlMañana);
$rowMañana = phpmkr_fetch_array($rsMañana);
$x_ayer = $rowMañana["ayer"];

// seleccionamos todos los pagos que se registraron ayer
$sqlPagos  = " SELECT vencimiento.credito_id ";
$sqlPagos .= " FROM recibo ";
$sqlPagos .= " JOIN recibo_vencimiento ON recibo_vencimiento.recibo_id = recibo.recibo_id";
$sqlPagos .= " JOIN vencimiento ON vencimiento.vencimiento_id = recibo_vencimiento.vencimiento_id ";
$sqlPagos .= " WHERE fecha_registro <= \"$x_ayer\" ";
$sqlPagos .= " GROUP BY credito_id ";
$rsPagos = phpmkr_query($sqlPagos, $conn)or die ("error al selc los pagos de ayer para cliente distinguido". phpmkr_error()."sql:".$sqlPagos);
$x_listado_creditos_pagados = "";
$x_contador_2 = 0;
while ($rowPagos = phpmkr_fetch_array($rsPagos)){
	$x_listado_creditos_pagados = $x_listado_creditos_pagados.$rowPagos["credito_id"].", ";
	}
$x_listado_creditos_pagados = substr($x_listado_creditos_pagados, 0, strlen($x_listado_creditos_pagados)-2); 

	
		$x_dias_diferencia_mayor = 0; 
		$sqlVencimientos = "SELECT * FROM vencimiento WHERE credito_id in ( $x_listado_creditos_pagados )  group by credito_id ORDER BY `vencimiento`.`vencimiento_id` ASC ";
		$responseVenc = phpmkr_query($sqlVencimientos, $conn) or die ("error al seleccionar los vencimientos".phpmkr_error()."sql:".$sqlVencimientos);
		$x_inicio = 1;
		while ($rowvenc = phpmkr_fetch_array($responseVenc)){
			$x_vencimiento_id = $rowvenc["vencimiento_id"];
			//echo "vencimeinto _id =".$x_vencimiento_id."<br>";
			$x_credito_id = $rowvenc["credito_id"];
		#	echo "<br><br> CREDITO_ID".$x_credito_id."<br>";
			$x_fecha_vencimiento = $rowvenc["fecha_vencimiento"];
			
			// seleccionamos el ultimo vencimiento del credito
			$sqlUltimoVenc = "SELECT COUNT(*) as faltantes FROM vencimiento WHERE credito_id = $x_credito_id  and vencimiento.vencimiento_status_id in (1,3,6)";
			$rsUltimoVenc = phpmkr_query($sqlUltimoVenc, $conn) or die ("Error al seleccionar no_venci faltantes". phpmkr_error()."sql:".$sqlUltimoVenc);
			$rowUltimoVenc = phpmkr_fetch_array($rsUltimoVenc);
			$x_faltantes = $rowUltimoVenc["faltantes"];
			$x_dias_diferencia = 0;
			
			$x_vencimiento_num = 0;
			$sSqlWrk12 = "select vencimiento_num from vencimiento where fecha_vencimiento = '$x_ayer' and credito_id = $x_credito_id  and  vencimiento_status_id = 2";
			$rswrkct12 = phpmkr_query($sSqlWrk12,$conn) or die("error al seleccionar el num ven en 10%tasa".phpmkr_error()."sql:".$sSqlWrk12);
			$row12 = phpmkr_fetch_array($rswrkct12);
			$x_vencimiento_num = $row12["vencimiento_num"];
			
			$x_mitad_credito = 0;
			$sSqlWrk = "SELECT max(vencimiento_num) as venc_num_tot FROM vencimiento WHERE credito_id =  $x_credito_id ";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_venc_num_tot = $datawrk["venc_num_tot"];
			$x_mitad_credito = intval($x_venc_num_tot / 2);
			@phpmkr_free_result($rswrk);
		#	echo "faltantes".$x_faltantes;
			//if ($x_faltantes <= 1){
				if(($x_mitad_credito == $x_vencimiento_num) || (($x_mitad_credito - 1) == $x_vencimiento_num) || (($x_mitad_credito + 1) == $x_vencimiento_num)){
			//echo "fecha venciamiento".$x_fecha_vencimiento."<br>";
			$x_pago_con_atraso = 0;
			$sSqlFP = "SELECT fecha_pago FROM recibo JOIN recibo_vencimiento ON recibo_vencimiento.recibo_id = recibo.recibo_id JOIN vencimiento ON  vencimiento.vencimiento_id = recibo_vencimiento.vencimiento_id  WHERE vencimiento.credito_id =  $x_credito_id ";
		#	echo "sql:".$sSqlFP;
			$responseFP = phpmkr_query($sSqlFP,$conn) or die("Erorr al seleccionar la fecha de pago".phpmkr_error()."sql:".$sSqlFP);
			while($rowFP = phpmkr_fetch_array($responseFP)){			
				if($x_fecha_pago > $x_fecha_vencimiento){
			$x_pago_con_atraso = 1;
			}				
				
				}// whilefaltastes menor o igual a 1
		#	echo "inicio ".$x_inicio."<br>";
		if($x_pago_con_atraso == 0){
			$GLOBALS["x_tipo_cliente"] = "BUENO";
			#se hace el envio del mensaje de texto para decirle al cliente que tiene un 10% en su tasa 
			##################################################################################################################################
			##########################################               SMS              ########################################################
			##################################################################################################################################
			
			$sqlCliente = " SELECT cliente_id FROM solicitud_cliente JOIN solicitud ON solicitud.solicitud_id = solicitud_cliente.solicitud_id JOIN ";
			$sqlCliente .= " credito ON credito.solicitud_id = solicitud.solicitud_id WHERE credito_id = $x_credito_id";
			#echo "sql cliente".$sqlCliente."<br>";
			$rsCliente = phpmkr_query($sqlCliente, $conn) or die ("Error al seleccionar el cliente _id". phpmkr_error(). "sql:". $sqlCliente);
			$rowClientet = phpmkr_fetch_array($rsCliente);
			$x_cliente_idt = 	$rowClientet["cliente_id"];
			#echo "cliente id ".$x_cliente_idt."<br>";
			
			if (!empty($x_cliente_idt) ){
		 $sqlCelular = "SELECT numero FROM telefono WHERE cliente_id = $x_cliente_idt  AND telefono_tipo_id = 2 ORDER BY `telefono_id` DESC ";
		 $rsCelular = phpmkr_query($sqlCelular, $conn) or die ("Error al seleccioanr el numero de celuar". phpmkr_error()."sql:".$sqlCelular);
		 while($rowCelular = phpmkr_fetch_array($rsCelular)){
		 $x_no_celular = $rowCelular["numero"];
		 $x_compania = $rowCelular["compania_id"];
		 //$x_no_celular = "5540663927";
		 echo  "numero de celular ".$x_no_celular."<br>";
		 
		 // ya tenemos el numero de celular  y los datos del mensaje, ahora hacemos el envio del mensaje de texto al celular
		 if(!empty($x_no_celular) && $x_no_celular != "5555555555"){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
						$sqlCreditonum= "select credito_num from credito where credito_id = $x_credito_id";
						$rsCreditoNun = phpmkr_query($sqlCreditonum,$conn) or die("Error al seleccionar credito num".phpmkr_error()."sql:".$sqlCreditonum);
						$rowCN = phpmkr_fetch_array($rsCreditoNun);
						$x_credito_num = $rowCN["credito_num"];
						$x_mensaje = "CREA CRED ".$x_credito_num." CLIENTE DISTINGUIDO ";
						$x_mensaje .= " USTED PODRA OBTENER UN 10% DE DESCUENTO EN SU RENOVACION, SI CONTINUA PAGANDO PUNTUALMENTE.";
							
						echo "MENSAJE :". $x_mensaje."<BR>";										
						// Varios destinatarios
						$para  = 'sms@financieracrea.com'; // atención a la coma
						// subject
						$titulo = $x_no_celular;						
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						// Mail it						
						// Mail($para, $titulo, $x_mensaje, $cabeceras);	
						$x_contador_2 ++;
						
						
						
						$tiposms = 6;
						$chip = "";
						# CAMBIAR A CHIP NO 2
						insertaSmsTabla($conn, $x_cliente_idt, $x_credito_id, $tiposms, $chip, $x_mensaje, $titulo, $x_compania);
						
						
						$x_fecha_sms = date("Y-m-d");
						$sqlInsertsms =  "INSERT INTO `sms_enviados` (`id_sms_enviado`, `id_tipo_mensaje`, `contenido`, `no_credito`, `no_celular`, `fecha_registro`, `tipo_envio`, `destino`) VALUES (NULL, '6','" .$x_mensaje."', $x_credito_num, '".$x_no_celular."', '".$x_fecha_sms."', '1', '1')";
						
							
	$result = $rsInsert = phpmkr_query($sqlInsertsms, $conn) or die ("Error al inserta en sms tabla nueva". phpmkr_error()."sql :". $sqlInsert);
						
						
			}	
		 }// while telefonos
		}		
			
			
			
			
			}
#echo "<br><br>TIPO CLIENTE =  ". $GLOBALS["x_tipo_cliente"]."<br>";
			}// if mitad del credito
			}// while
			
			echo "<br><br>".$x_contador_2."<br><br>";
		phpmkr_free_result($responseVenc);
		
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