<?php session_start(); ?>
<?php ob_start(); ?>

<?php
// seleccionamos todos los credito que esten totalmente vencidos
include ("db.php");
include ("phpmkrfn.php");
include("../crm/datefunc.php");
$conn = phpmkr_db_connect(HOST, USER, PASS,DB, PORT);

$X_HOY = date("Y-m-d");
$currdate = date("Y-m-d");

// seleccionamos los creditos que sean menor a 6000 en credito_num
$X_CONTADOR = 0;
$sqlCredito6 = "SELECT  credito_id  FROM credito WHERE credito_num  < 6000 and credito_status_id in (1,4)";
$rsCredito6 = phpmkr_query($sqlCredito6,$conn) or die ("Error al seleccionar los c redito menores a 6000".phpmkr_error()."sql:".$sqlCredito6);
while($rowCredito6 = phpmkr_fetch_array($rsCredito6)){
	
	$x_credito_id = $rowCredito6["credito_id"];
// buscamos si tiene algun vencimiento vencido... si lo tiene le mandamos el mensaj 

$sqlvencimeintoVencimiento =  "SELECT COUNT(*) AS vencidos FROM vencimiento  WHERE  vencimiento_status_id = 3 AND credito_id = $x_credito_id ";
$rsvencimiento_vencido = phpmkr_query($sqlvencimeintoVencimiento,$conn)or die("Error al seleccionar los vecidos".phpmkr_error()."sql:".$sqlvencimeintoVencimiento);
$rowVencimientoVencido = phpmkr_fetch_array($rsvencimiento_vencido);
$x_vencidos = $rowVencimientoVencido["vencidos"];

if($x_vencidos > 0){
	// si tiene alguno vencido le mandamos sms
	$sqlVV = "SELECT * FROM  vencimiento WHERE credito_id = $x_credito_id and  vencimiento_status_id = 3";
	$rsVV = phpmkr_query($sqlVV,$conn)or die("eRROR aL SELECCIONAR LoS VENCIDOS".phpmkr_error()."sql:".$sqlVV);
	$x_general = 0;
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
				
			$x_general = $x_general +  $x_total_venc;						
			// status_vencimeito_id
			// 1 .- pendiente
			// 2.- pagado
			// 3.- vencido
			// 4.- cabcelado
			
			}
			
			
			// se hace el envio del mensaje
			
			$sqlClientel = " SELECT cliente_id FROM solicitud_cliente JOIN solicitud ON solicitud.solicitud_id = solicitud_cliente.solicitud_id JOIN ";
			$sqlClientel .= " credito ON credito.solicitud_id = solicitud.solicitud_id WHERE credito_id = $x_credito_id ";
		//	echo $sqlClientel;
			$rsClientel = phpmkr_query($sqlClientel, $conn) or die ("Error al seleccionar el cliente _id". phpmkr_error(). "sql:". $sqlClientel);
			$rowClientel = phpmkr_fetch_array($rsClientel);
			$x_cliente_idl = 	$rowClientel["cliente_id"];
			// echo "<br>cliente_id ".$x_cliente_idl;
			
			if($x_cliente_idl > 0){
		
			
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
			  			  
				  $x_mensaje = "FINANCIERA CREA: USTED PRESENTA UN ADEUDO ";
				   $x_mensaje .= " POR ".FormatNumber($x_general,2,0,0,1)." PAGUE DE INMEDIATO";
				  
				 
				  echo "<BR> CREDITO_ID ".$x_credito_id." CLIENTE ID ".$x_cliente_idl;
				  echo "<br>". $x_mensaje;
				 $x_logitud = strlen($x_mensaje);
				 echo 	" ----  LOGITUD ".$x_logitud." MENSAJE ".$X_CONTADOR;  
			 
		 $X_CONTADOR ++;
		 // ya tenemos el numero de celular  y los datos del mensaje, ahora hacemos el envio del mensaje de texto al celular
		 if(!empty($x_no_celular) &&  $x_no_celular != "5555555555" &&  $x_no_celular != "0000000000"){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
												
					//	echo "MENSAJE :". $x_mensaje." c_id ".$x_cliente_idl." num ".$x_no_celular."<BR>";										
						//Varios destinatarios
						$para  = 'sms@financieracrea.com'; // atención a la coma
						//subject
						$titulo = $x_no_celular;						
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						//Mail it						
						#mail($para, $titulo, $x_mensaje, $cabeceras);	
						$x_contador_3 ++;
						
								
						 
						
						
			}			
		}		
			
			
		#	echo "CREDITO LIQUIDADO AYER".$x_credito_id_liquidado."<BR>";
			} // while celulares
	
	
			}
	
	}

}#while principal
?>
