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
$sqlCredito6 = "SELECT * 
				FROM  `credito` 
				JOIN solicitud ON solicitud.solicitud_id = credito.solicitud_id
				JOIN promotor ON promotor.promotor_id = solicitud.promotor_id
				WHERE solicitud.promotor_id
				IN (
				
				SELECT promotor_id
				FROM promotor
				WHERE promotor.sucursal_id NOT 
				IN ( 7 )
				)
				AND credito.credito_status_id
				IN ( 1 ) ";
$rsCredito6 = phpmkr_query($sqlCredito6,$conn) or die ("Error al seleccionar los c redito menores a fp".phpmkr_error()."sql:".$sqlCredito6);
while($rowCredito6 = phpmkr_fetch_array($rsCredito6)){	
	$x_credito_id = $rowCredito6["credito_id"];
// buscamos si tiene algun vencimiento vencido... si lo tiene le mandamos el mensaj
$x_vencidos = 10;
if($x_vencidos > 0){
				
			
			// se hace el envio del mensaje			
			$sqlClientel = " SELECT cliente_id FROM solicitud_cliente JOIN solicitud ON solicitud.solicitud_id = solicitud_cliente.solicitud_id JOIN ";
			$sqlClientel .= " credito ON credito.solicitud_id = solicitud.solicitud_id WHERE credito_id = $x_credito_id ";
		//	echo $sqlClientel;
			$rsClientel = phpmkr_query($sqlClientel, $conn) or die ("Error al seleccionar el cliente _id". phpmkr_error(). "sql:". $sqlClientel);
			$rowClientel = phpmkr_fetch_array($rsClientel);
			$x_cliente_idl = 	$rowClientel["cliente_id"];
			// echo "<br>cliente_id ".$x_cliente_idl;
			
			if($x_cliente_idl > 0){
			//buscamos la sucursal del  cliente 
			$sqlPromotor = "SELECT cliente.promotor_id, sucursal.nombre  as suc from cliente join promotor on promotor.promotor_id = cliente.promotor_id join sucursal on sucursal.sucursal_id = promotor.sucursal_id where cliente_id = $x_cliente_idl ";
			$rsPromotor = phpmkr_query($sqlPromotor,$conn) or die ("Error al seleccionar las sucursal ".phpmkr_error()."sql:". $sqlPromotor);
			$rowPromotor = phpmkr_fetch_array($rsPromotor);
			$x_sucursal =  strtoupper($rowPromotor["suc"]);
			
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
				  $x_mensaje1 = " CREA: SU CRÉDITO LE DA DERECHO A PRECIO SOCIO EN FASHION PRICE; LENCERÍA Y ROPA PARA LA FAMILIA: AVENIDA CENTRAL A UN LADO DE METRO MUZQUIZ.";	
				   $x_mensaje2 = " FASHION PRICE: PLAYERA YAZBEK 24, FAJA BODY SILUETTE 134, BRA PLAYTEX 196, DORIAN GREY 28, BOXER ALFANI 65, CAMISETA BAMBI 22. tel 26173021";			 		  
				  echo "<br>". $x_mensaje;
				  $x_logitud = strlen($x_mensaje1);
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
						mail($para, $titulo, $x_mensaje1, $cabeceras);
						mail($para, $titulo, $x_mensaje2, $cabeceras);	
						$x_contador_3 ++;

						 
						
						
			}			
		}		
			
			
		#	echo "CREDITO LIQUIDADO AYER".$x_credito_id_liquidado."<BR>";
			} // while celulares
	
	
			}
	
	}

}#while principal
?>