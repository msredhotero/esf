<?php 
die();
set_time_limit(0); ?>
<?php session_start(); ?>
<?php ob_start(); ?>
<?php

// Initialize common variables
?>
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
Mail($para, $titulo, $x_mensaje, $cabeceras);	
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
Mail($para, $titulo, $x_mensaje, $cabeceras);	

//SELECCIONAMOS TODOS LOS CREDITOS QUE TIENE PAGO PROGRAMADO PARA EL DIA DE MAÑANA Y LES ENVIAMOS UN SMS PARA RECORDARLES.
$sqlCredito = "SELECT credito.credito_id, solicitud_id, vencimiento_num , total_venc  FROM  vencimiento JOIN credito ON credito.credito_id = vencimiento.credito_id WHERE vencimiento.fecha_vencimiento = \"$x_mañana\" AND vencimiento.vencimiento_status_id = 1";
$rsCredito = phpmkr_query($sqlCredito, $conn)or die ("Error al seleccionar credito-id".phpmkr_error()."sql:".$sqlCredito);
#echo "sql credito_id:".$sqlCredito."<br>";
$x_contador_1 = 0;
while($rowCredito = phpmkr_fetch_array($rsCredito)){
	$x_no = 1;
	$x_credito_id = $rowCredito["credito_id"];
	$x_solicitud_id = $rowCredito["solicitud_id"];
	$x_vencimeinto_id = $rowCredito["vencimiento_id"];
	$x_total_venc =FormatNumber($rowCredito["total_venc"],2,0,0,1);
	$x_vencimiento_num = $rowCredito["vencimiento_num"];
	#echo "credito_id".$x_credito_id."<br>";
	#echo "cliente_id".$x_cliente_id."<br>";
	#echo "vencimeinto_num".$x_vencimiento_num."<br>";
	#echo "importe ven".$x_total_venc."<br>";
	
	$x_total_pagos = 0;
	$sqlTotalPagos = "SELECT COUNT(*)  AS total_pagos FROM vencimiento WHERE credito_id = $x_credito_id ";
	$rsTotalPagos = phpmkr_query($sqlTotalPagos,$conn) or die("Error total de pagos". phpmkr_error()."sql: ". $sqlTotalPagos);
	$rowTotalPagos = phpmkr_fetch_array($rsTotalPagos);
	$x_total_pagos = $rowTotalPagos["total_pagos"];
		

	// buscamos el cliente_id
	$x_cliente_id = 0;
	$sqlCliente = "SELECT cliente_id FROM solicitud_cliente WHERE solicitud_id = $x_solicitud_id ";
	$rsCliente = phpmkr_query($sqlCliente, $conn) or die("Error al seleccionar cliente_id ". phpmkr_error(). "Sql:". $sqlCliente);
	$rowCliente = phpmkr_fetch_array($rsCliente);
	$x_cliente_id = $rowCliente["cliente_id"];
	
	// bus
	
	// seleccionamos el tlefono del cliente 
	if (!empty($x_cliente_id)){
		 $sqlCelular = "SELECT numero FROM telefono WHERE cliente_id = $x_cliente_id  AND telefono_tipo_id = 2 ORDER BY `telefono_id` DESC ";
		 $rsCelular = phpmkr_query($sqlCelular, $conn) or die ("Error al seleccioanr el numero de celuar". phpmkr_error()."sql:".$sqlCelular);
		while($rowCelular = phpmkr_fetch_array($rsCelular)){
		 $x_no_celular = $rowCelular["numero"];
		 $x_compania = $rowCelular["compania_id"];
		 //$x_no_celular = "5540663927";
		 
		 // ya tenemos el numero de celular  y los datos del mensaje, ahora hacemos el envio del mensaje de texto al celular
		 if(!empty($x_no_celular) && $x_no_celular != "5555555555"){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
						$x_mensaje = "FINANCIERA CREA: LE RECUERDA QUE SU PAGO $x_vencimiento_num DE $x_total_pagos  POR $x_total_venc ";
						$x_mensaje .= "VENCE EL DIA DE MANIANA. VALORAMOS SU PUNTUALIDAD.";		
						echo "MENSAJE :". $x_mensaje."<BR>";										
						// Varios destinatarios
						$para  = 'sms@financieracrea.com'; // atención a la coma
						// subject
						$titulo = $x_no_celular;						
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						// Mail it						
						 Mail($para, $titulo, $x_mensaje, $cabeceras);	
						//echo $x_mensaje."<br>";
						$x_contador_1 ++;					
						$tiposms = 3;  // reccordatorio de pago.						
						#CAMBIAR A CHIP 1
						# se envia el mensaje y se inswerta en tabla sde sms
						insertaSmsTabla($conn, $x_cliente_id , $x_credito_id, $tiposms, $chip, $x_mensaje, $titulo, $x_compania );				
						
			}	
		}// fin del while de los celulares
		}		
	}// fin while credito_id
	
	
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
		 
		 // ya tenemos el numero de celular  y los datos del mensaje, ahora hacemos el envio del mensaje de texto al celular
		 if(!empty($x_no_celular) && $x_no_celular != "5555555555"){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
						$x_mensaje = "FINANCIERA CREA: CLIENTE DISTINGUIDO ";
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
						
			}	
		 }// while telefonos
		}		
			
			
			
			
			}
#echo "<br><br>TIPO CLIENTE =  ". $GLOBALS["x_tipo_cliente"]."<br>";
			}// if mitad del credito
			}// while
			
			echo "<br><br>".$x_contador_2."<br><br>";
		phpmkr_free_result($responseVenc);
		
		
		



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
		 
		 $x_mensaje = "";
		 //creditos que se liquidaron y que no tienen comision de cobranza generada.. solo incluye los que entran en el nuevo esquema de penalizaciones
		  if($x_penalizacion > 0){
			  // los que no tienen comision generada
			  if($x_vencimiento_num_comision == 0){				  
				  $x_mensaje = "FINANCIERA CREA:SU CREDITO HA SIDO LIQUIDADO. USTED PODRA OBTENER UN NUEVO CREDITO ";
				  $x_mensaje .= " COMUNICANDOSE AL: DF 51350259:LADA SIN COSTO 018008376133.";
				  
				  }else{
					  // se les genero comision					  
				  $x_mensaje = "FINANCIERA CREA: SU CRÉDITO HA SIDO LIQUIDADO. GRACIAS.";
				  
					  }		  
			  }else{
				  // son los creditos anteriores
				   $x_mensaje = "FINANCIERA CREA: SU CRÉDITO HA SIDO LIQUIDADO. GRACIAS.";
				  
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
						//Mail it						
						Mail($para, $titulo, $x_mensaje, $cabeceras);	
						$x_contador_3 ++;
						
						$tiposms = "8";
						$chip = "";
						#cambiamos a chip 2
						
						insertaSmsTabla($conn, $x_cliente_idl, $x_credito_id_liquidado, $tiposms, $chip, $x_mensaje, $titulo, $x_compania);
						
						
						
			}			
		}		
			
			
		#	echo "CREDITO LIQUIDADO AYER".$x_credito_id_liquidado."<BR>";
			} // while celulares
			
			}
			
			echo "<br><br>dd".$x_contador_3."<br><br>";
			
			
#CUENTA VENCIDA		

	#seleccionamos los pagos con fecha de ayer
	#contamos los pagos vencidos del credito
	#seleccionamos la suma del mosnto de lo vencido 
	# restamos la suma de los moratorios.
	
	

	
	
################################################################################################################################	
##########################################     CUENTA VENCIDA     ##############################################################	
################################################################################################################################
 # seleccionamos todos los vencimientos que se vencieron el dia de ayer
 # se dejan 3 dias de plazo porque los pagos no pasan el mismo dia, aveces se tardan en subir los pagos al sistema. 
 // se debe valorar que tipo de credito es si es de los nuevos tipos de credito que se hacen con penalizaciones o si son de los antiguos en los que el importe de moratorios esta ´resente en el mismo registro del vencimiento.
 // el lo nuveos tipo de credito el moratorio se  registra en un nuevo vencimiento y se reconoce como penalizaciones.
 
 $sqlMañana = " SELECT DATE_SUB(CURDATE(),INTERVAL 3 DAY) AS ayer";
$rsMañana =  phpmkr_query($sqlMañana, $conn) or die ("erorr al seleccionar el dia de mañana ". phpmkr_error()."sql:". $sqlMañana);
$rowMañana = phpmkr_fetch_array($rsMañana);
$x_dos_dias_antes_de_hoy = $rowMañana["ayer"];
 
 
 $sqlVencimientosAyer = "SELECT  * FROM vencimiento WHERE   vencimiento.fecha_vencimiento = \"$x_dos_dias_antes_de_hoy\" AND  	vencimiento_status_id  in (3) group by credito_id ";
 $rsVencimientosAyer = phpmkr_query($sqlVencimientosAyer,$conn) or die ("error al seleccioanr los vencimeitnos vncidos". phpmkr_error()."sql:". $sqlVencimientosAyer);
 
# echo "sql".$sqlVencimientosAyer."<br>";
$x_contador_5 = 0;
 	while($rowVencimientosAyer = phpmkr_fetch_array($rsVencimientosAyer)){
		$x_cred_id_a = $rowVencimientosAyer["credito_id"];
		echo $x_cred_id_a."hh<br>";
		
		// seleccionamos los datos del credito
		$sqlDCP = "SELECT * FROM credito WHERE credito_id = $x_cred_id_a ";
		$rsDCP = phpmkr_query($sqlDCP,$conn) or die ("error al seleccionar los datos del credito en".phpmkr_error()."sql:".$sqlDCP);
		$rowDCP = phpmkr_fetch_array($rsDCP);
		$x_penalizacion = $rowDCP["penalizacion"];
		
		
		if($x_penalizacion > 0){
			
			$sqlVencidos = "SELECT * FROM vencimiento WHERE credito_id = $x_cred_id_a and  vencimiento_status_id  in (3) AND vencimiento_num < 2000";
		#echo $sqlVencidos;
		$rsVencidos = phpmkr_query($sqlVencidos, $conn) or die ("Error al seleccinar". phpmkr_error()."sql :". $sqlVencidos);
		$x_por_pagar = "";
		$x_no_vencidos = 0;
		$x_moratorios = 0;
		while($rowVencidos = phpmkr_fetch_array($rsVencidos)){
			$x_no_vencidos ++;
			$x_por_pagar = $x_por_pagar + $rowVencidos["total_venc"];
			#echo "por pagar".$x_por_pagar."<br>";
			
								
			}
		$sqlVencidos = "SELECT * FROM vencimiento WHERE credito_id = $x_cred_id_a and  vencimiento_status_id  in (3,1) AND vencimiento_num > 2000";
		#echo $sqlVencidos;
		$rsVencidos = phpmkr_query($sqlVencidos, $conn) or die ("Error al seleccinar". phpmkr_error()."sql :". $sqlVencidos);
		$x_por_penalizaciones = "";
		
		$x_moratorios = 0;
		while($rowVencidos = phpmkr_fetch_array($rsVencidos)){
			$x_no_vencidos ++;
			$x_por_penalizaciones = $x_por_penalizaciones + $rowVencidos["total_venc"];
			#echo "por pagar".$x_por_pagar."<br>";
			
								
			}
			
			
		$x_pago_final = $x_por_pagar ;
		$x_pago_final_aval = $x_por_pagar + $x_por_penalizaciones ;
		
		$x_mensaje = "FINANCIERA CREA: SU CUENTA PRESENTA $x_no_vencidos PAGOS VENCIDOS, SOLICITAMOS SU PAGO POR $x_pago_final MAS PENALIZACIONES POR $x_por_penalizaciones A LA BREVEDAD.";
	#	echo $x_mensaje."<br> $x_cred_id_a<br>";
		# se hace el encvio del mail al celular
		
		
		// seleccionamos los datos del vala y se le envia el mensaje al aval para que el avala haga presion sobre el cliente y se haga el pago del vencido.
		
		$x_mensaje_aval = "FINANCIERA CREA LA CUENTA QUE USTED AVALA PRESENTA PRESENTA $x_pago_final VENCIDOS EVITE COBROS. .";
		// se envia mensaje			
			$sqlClientel = " SELECT cliente_id FROM solicitud_cliente JOIN solicitud ON solicitud.solicitud_id = solicitud_cliente.solicitud_id JOIN ";
			$sqlClientel .= " credito ON credito.solicitud_id = solicitud.solicitud_id WHERE credito_id = $x_cred_id_a";
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
		 
		// $x_no_celular = "5540663927";
		 
		 // ya tenemos el numero de celular  y los datos del mensaje, ahora hacemos el envio del mensaje de texto al celular
		 if(!empty($x_no_celular) && $x_no_celular != "5555555555"){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
						$x_mensaje = "FINANCIERA CREA: SU CUENTA PRESENTA $x_no_vencidos PAGOS VENCIDOS, ";
						$x_mensaje .= "SOLICITAMOS SU PAGO POR $x_pago_final MAS PENALIZACIONES POR $x_por_penalizaciones A LA BREVEDAD." ;	
						echo "MENSAJE :". $x_mensaje."<BR>";										
						//Varios destinatarios
						$para  = 'sms@financieracrea.com'; // atención a la coma
						//subject
						$titulo = $x_no_celular;						
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						//Mail it						
						Mail($para, $titulo, $x_mensaje, $cabeceras);	
						$x_lon =  strlen($x_mensaje);
						echo "longitud de la cadena = ".$x_lon."<br>";
						$x_contador_5 ++;
						
						$tiposms = "4";
						$chip = "";
						#CAMBIAR A CHIP NO 2
						
						insertaSmsTabla($conn, $x_cliente_idl, $x_cred_id_a, $tiposms, $chip, $x_mensaje, $titulo, $x_compania);
						
			}		
		 }// while celulares
		}		
		
		
		
			################################################################################################################## 
		#################################################    SMS  AVAL  ###############################################
		################################################################################################################## 		
		// se envia mensaje		
		
			$sqlClientel = " SELECT datos_aval_id FROM datos_aval JOIN solicitud_cliente on datos_aval.cliente_id = solicitud_cliente.cliente_id  JOIN solicitud ON solicitud.solicitud_id = solicitud_cliente.solicitud_id JOIN ";
			$sqlClientel .= " credito ON credito.solicitud_id = solicitud.solicitud_id WHERE credito_id = $x_cred_id_a";
			$rsClientel = phpmkr_query($sqlClientel, $conn) or die ("Error al seleccionar el cliente _id". phpmkr_error(). "sql:". $sqlClientel);
			$rowClientel = phpmkr_fetch_array($rsClientel);
			$x_aval_idl = 	$rowClientel["datos_aval_id"];		
		
		
			//seleccionamos si la cuanta tiene o no  la comision de cobranza generada
			$sqlCG = "SELECT COUNT(*)  AS comision FROM vencimiento WHERE credito_id = $x_cred_id_a and vencimiento_num >= 3000 ";
			$rsCG = phpmkr_query($sqlCG,$conn) or die ("Error al seleccionar  la comision en aval sms  cuenta vencida".phpmkr_error()."sql:".$sqlCG);
			$rowCG = phpmkr_fetch_array($rsCG);
			$x_comision_generada = $rowCG["comision"];
			$x_mensaje_aval = "";
			if($x_comision_generada == 0){
				$x_mensaje_aval = "FINANCIERA CREA LA CUENTA QUE USTED AVALA PRESENTA PRESENTA $x_pago_final_aval VENCIDOS EVITE COBROS. .";
				}else{
					$x_mensaje_aval = "FINANCIERA CREA LA CUENTA QUE USTED AVALA PRESENTA $x_pago_final VENCIDOS, MAS INTERESES  MORATORIOS POR $x_por_penalizaciones PAGUE A LA BREVEDAD . .";
					}
			
			if (!empty($x_aval_idl)){
		 $x_no_celular  = "";
		 $sqlCelular = "SELECT numero FROM telefono WHERE aval_id = $x_aval_idl  AND telefono_tipo_id = 2 ORDER BY `telefono_id` DESC ";
		 $rsCelular = phpmkr_query($sqlCelular, $conn) or die ("Error al seleccioanr el numero de celuar". phpmkr_error()."sql:".$sqlCelular);
		 while($rowCelular = phpmkr_fetch_array($rsCelular)){
		 $x_no_celular_aval = $rowCelular["numero"];
		 $x_compania = $rowCelular["compania_id"];
		 
		 $x_no_celular_aval = "5540663927";
		 
		 // ya tenemos el numero de celular  y los datos del mensaje, ahora hacemos el envio del mensaje de texto al celular
		 if(!empty($x_no_celular_aval) && $x_no_celular_aval != "5555555555"){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
							
						echo "MENSAJE :". $x_mensaje."<BR>";										
						//Varios destinatarios
						$para  = 'sms@financieracrea.com'; // atención a la coma
						//subject
						$titulo = $x_no_celular;						
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						//Mail it						
						Mail($para, $titulo, $x_mensaje_aval, $cabeceras);	
						$x_lon =  strlen($x_mensaje_aval);
						echo "longitud de la cadena = ".$x_lon."<br>";
						$x_contador_5 ++;
						
						$tiposms = "9";
						$chip = "";
						#CAMBIAR A CHIP NO 2
						
						insertaSmsTabla($conn, $x_cliente_idl, $x_cred_id_a, $tiposms, $chip, $x_mensaje_aval, $titulo, $x_compania);
						
			}			
		 }// while celelares
		}		
				
				
				
			
//			el credito es del nuevo tipo
			}else{
				// la mora esta en el mismo vencimeinto
				// sleccionamos todos los vencidos del credito		
		$sqlVencidos = "SELECT * FROM vencimiento WHERE credito_id = $x_cred_id_a and  vencimiento_status_id  in (3)";
		#echo $sqlVencidos;
		$rsVencidos = phpmkr_query($sqlVencidos, $conn) or die ("Error al seleccinar". phpmkr_error()."sql :". $sqlVencidos);
		$x_por_pagar = "";
		$x_no_vencidos = 0;
		$x_moratorios = 0;
		while($rowVencidos = phpmkr_fetch_array($rsVencidos)){
			$x_no_vencidos ++;
			$x_por_pagar = $x_por_pagar + $rowVencidos["total_venc"];
			#echo "por pagar".$x_por_pagar."<br>";
			$x_moratorios = $x_moratorios + $rowVencidos["interes_moratorio"];	
			#echo "mora".$x_moratorios."<br>";					
			}
		$x_pago_final = $x_por_pagar -$x_moratorios;
		$x_penalizaciones = $x_moratorios;
		
		$x_mensaje = "FINANCIERA CREA: SU CUENTA PRESENTA $x_no_vencidos PAGOS VENCIDOS, SOLICITAMOS SU PAGO POR $x_pago_final MAS PENALIZACIONES POR $x_penalizaciones A LA BREVEDAD.";
	#	echo $x_mensaje."<br> $x_cred_id_a<br>";
		# se hace el encvio del mail al celular
		
		
		// seleccionamos los datos del vala y se le envia el mensaje al aval para que el avala haga presion sobre el cliente y se haga el pago del vencido.
		
		$x_mensaje_aval = "FINANCIERA CREA LA CUENTA QUE USTED AVALA PRESENTA PRESENTA $x_pago_final VENCIDOS EVITE COBROS. .";
		
		################################################################################################################## 
		#################################################    SMS  CLIENTE  ###############################################
		################################################################################################################## 		
		// se envia mensaje			
			$sqlClientel = " SELECT cliente_id FROM solicitud_cliente JOIN solicitud ON solicitud.solicitud_id = solicitud_cliente.solicitud_id JOIN ";
			$sqlClientel .= " credito ON credito.solicitud_id = solicitud.solicitud_id WHERE credito_id = $x_cred_id_a";
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
		 
		// $x_no_celular = "5540663927";
		 
		 // ya tenemos el numero de celular  y los datos del mensaje, ahora hacemos el envio del mensaje de texto al celular
		 if(!empty($x_no_celular) && $x_no_celular != "5555555555"){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
						$x_mensaje = "FINANCIERA CREA: SU CUENTA PRESENTA $x_no_vencidos PAGOS VENCIDOS, ";
						$x_mensaje .= "SOLICITAMOS SU PAGO POR $x_pago_final MAS PENALIZACIONES POR $x_penalizaciones A LA BREVEDAD." ;	
						echo "MENSAJE :". $x_mensaje."<BR>";										
						//Varios destinatarios
						$para  = 'sms@financieracrea.com'; // atención a la coma
						//subject
						$titulo = $x_no_celular;						
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						//Mail it						
						 Mail($para, $titulo, $x_mensaje, $cabeceras);	
						$x_lon =  strlen($x_mensaje);
						echo "longitud de la cadena = ".$x_lon."<br>";
						$x_contador_5 ++;
						
						$tiposms = "4";
						$chip = "";
						#CAMBIAR A CHIP NO 2
						
						insertaSmsTabla($conn, $x_cliente_idl, $x_cred_id_a, $tiposms, $chip, $x_mensaje, $titulo, $x_compania);
						
			}			
		 } // while celulares
		}		
		
			
		################################################################################################################## 
		#################################################    SMS  AVAL  ###############################################
		################################################################################################################## 		
		// se envia mensaje		
		
			$sqlClientel = " SELECT datos_aval_id FROM datos_aval JOIN solicitud_cliente on datos_aval.cliente_id = solicitud_cliente.cliente_id  JOIN solicitud ON solicitud.solicitud_id = solicitud_cliente.solicitud_id JOIN ";
			$sqlClientel .= " credito ON credito.solicitud_id = solicitud.solicitud_id WHERE credito_id = $x_cred_id_a";
			$rsClientel = phpmkr_query($sqlClientel, $conn) or die ("Error al seleccionar el cliente _id". phpmkr_error(). "sql:". $sqlClientel);
			$rowClientel = phpmkr_fetch_array($rsClientel);
			$x_aval_idl = 	$rowClientel["datos_aval_id"];		
		
		
			//seleccionamos si la cuanta tiene o no  la comision de cobranza generada
			$sqlCG = "SELECT COUNT(*)  AS comision FROM vencimiento WHERE credito_id = $x_cred_id_a and vencimiento_num >= 3000 ";
			$rsCG = phpmkr_query($sqlCG,$conn) or die ("Error al seleccionar  la comision en aval sms  cuenta vencida".phpmkr_error()."sql:".$sqlCG);
			$rowCG = phpmkr_fetch_array($rsCG);
			$x_comision_generada = $rowCG["comision"];
			$x_mensaje_aval = "";
			if($x_comision_generada == 0){
				$x_mensaje_aval = "FINANCIERA CREA LA CUENTA QUE USTED AVALA PRESENTA PRESENTA $x_pago_final VENCIDOS EVITE COBROS. .";
				}else{
					$x_mensaje_aval = "FINANCIERA CREA LA CUENTA QUE USTED AVALA PRESENTA $x_pago_final VENCIDOS, MAS INTERESES  MORAATORIOS POR $x_penalizaciones PAGUE A LA BREVEDAD . .";
					}
			
			if (!empty($x_aval_idl)){
		 $x_no_celular  = "";
		 $sqlCelular = "SELECT numero FROM telefono WHERE aval_id = $x_aval_idl  AND telefono_tipo_id = 2 ORDER BY `telefono_id` DESC ";
		 $rsCelular = phpmkr_query($sqlCelular, $conn) or die ("Error al seleccioanr el numero de celuar". phpmkr_error()."sql:".$sqlCelular);
		 while($rowCelular = phpmkr_fetch_array($rsCelular)){
		 $x_no_celular_aval = $rowCelular["numero"];
		 $x_compania = $rowCelular["compania_id"];
		 
		 $x_no_celular_aval = "5540663927";
		 
		 // ya tenemos el numero de celular  y los datos del mensaje, ahora hacemos el envio del mensaje de texto al celular
		 if(!empty($x_no_celular_aval) && $x_no_celular_aval != "5555555555"){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
							
						echo "MENSAJE :". $x_mensaje."<BR>";										
						//Varios destinatarios
						$para  = 'sms@financieracrea.com'; // atención a la coma
						//subject
						$titulo = $x_no_celular;						
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						//Mail it						
						 Mail($para, $titulo, $x_mensaje, $cabeceras);	
						$x_lon =  strlen($x_mensaje_aval);
						echo "longitud de la cadena = ".$x_lon."<br>";
						$x_contador_5 ++;
						
						$tiposms = "9";
						$chip = "";
						#CAMBIAR A CHIP NO 2
						
						insertaSmsTabla($conn, $x_cliente_idl, $x_cred_id_a, $tiposms, $chip, $x_mensaje, $titulo, $x_compania);
						
			}		
		 }//while celulares
		}
				
				
				
				
				}// if panelizacion > 0
				
		
		
		
		
		
		
				
		}
 echo "<br><br>hh".$x_contador_5."<br><br>";







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


	$sqlPP = "SELECT  crm_tarea.*, crm_caso.credito_id FROM  crm_tarea  JOIN crm_caso ON crm_caso.crm_caso_id =  crm_tarea.crm_caso_id ";
	$sqlPP .= " WHERE orden = 2 and crm_tarea_tipo_id = 6 and fecha_ejecuta <= \"$x_hoy\" AND crm_tarea_status_id = 1";
	$rsPP = phpmkr_query($sqlPP, $conn) or die ("Error al seleccionar las tareas de pp". phpmkr_error()."sql:".$sqlPP);
	$x_contador_6 = 0;
	echo "sql".$sqlPP;
	while($rowPP = phpmkr_fetch_array($rsPP)){
		// mientras encuentre tareas buscamos el numero de cliente y su numero telefonic si lo tiene se envia el mail y se cierra tarea
		$x_crm_tarea_id = $rowPP["crm_tarea_id"];
		$x_crm_caso_id = $rowPP["crm_caso_id"];
		$x_credito_id = $rowPP["credito_id"];
		
		
		/**********************************************************************
		******************** SMS CLIENTE *************************************
		**********************************************************************/
		echo "credito_id yy".$x_credito_id."<br>";
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
		 
		 ################################################################################################################## 
		#####################################################  SMS  ######################################################
		################################################################################################################## 		
		 // ya tenemos el numero de celular  y los datos del mensaje, ahora hacemos el envio del mensaje de texto al celular
		 if(!empty($x_no_celular) && $x_no_celular != "5555555555"){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
						$x_mensaje = "FINANCIERA CREA: LE RECUERDA SU PROMESA DE PAGO EL DIA DE HOY. ";
						#$x_mensaje .= " USTED PODRA OBTENER UN 10% DE DESCUENTO EN SU RENOVACION, SI CONTINUA PAGANDO PUNTUALMENTE.";							
						echo "MENSAJE :". $x_mensaje."<BR>";										
						// Varios destinatarios
						$para  = 'sms@financieracrea.com'; // atención a la coma
						// subject
						$titulo = $x_no_celular;						
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						// Mail it						
						Mail($para, $titulo, $x_mensaje, $cabeceras);						
						
						$tiposms = "5";
						$chip = "";
						#CAMBIAR A CHIP NO 1						
						insertaSmsTabla($conn, $x_cliente_idp, $x_credito_id, $tiposms, $chip, $x_mensaje, $titulo, $x_no_celular);					
						#se manda mensaje y se actualiza la tarea del CRM a completa
						$UpdateTarea = "UPDATE  crm_tarea SET crm_tarea_status_id = 3 WHERE	crm_tarea_id =	$x_crm_tarea_id";
					    #$rsUpdate = phpmkr_query($UpdateTarea,$conn) or die ("Error al actualiza la tarea".phpmkr_error()."sql:".$UpdateTarea);
						// echo "<br>".$x_mensaje."<br>";	
						 $x_contador_6 ++;		
						
			}			
		 }//while celulares
		}
		
		
		
		/**********************************************************************
		********************   SMS AVAL   *************************************
		**********************************************************************/
		echo "credito_id yy".$x_credito_id."<br>";
		// sacamos los datos del cliente con credito_id
		$sqlCliente = " SELECT datos_aval_id, cliente.cliente_id FROM datos_aval JOIN  solicitud_cliente ON solicitud_cliente.solicitud_id = datos_aval.solicitud_id JOIN solicitud ON solicitud.solicitud_id = solicitud_cliente.solicitud_id JOIN ";
		$sqlCliente .= " credito ON credito.solicitud_id = solicitud.solicitud_id WHERE credito_id = $x_credito_id";
		$rsCliente = phpmkr_query($sqlCliente, $conn) or die ("Error al seleccionar el cliente _id". phpmkr_error(). "sql:". $sqlCliente);
		$rowClientet = phpmkr_fetch_array($rsCliente);
		$x_aval_id = 	$rowClientet["datos_aval_id"];
		$x_cliente_idp = $rowClientet["cliente_id"];
			
			if (!empty($x_aval_id)){
		 $sqlCelular = "SELECT numero FROM telefono WHERE aval_id = $x_aval_id  AND telefono_tipo_id = 2 ORDER BY `telefono_id` DESC ";
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
						$x_mensaje = " FINANCIERA CREA: LE INFORMA QUE LA CUENTA QUE USTED AVALA TIENE  PROMESA DE PAGO PARA EL DIA DE HOY. ";
						#$x_mensaje .= " USTED PODRA OBTENER UN 10% DE DESCUENTO EN SU RENOVACION, SI CONTINUA PAGANDO PUNTUALMENTE.";
							
						echo "MENSAJE :". $x_mensaje."<BR>";										
						// Varios destinatarios
						$para  = 'sms@financieracrea.com'; // atención a la coma
						// subject
						$titulo = $x_no_celular;						
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						// Mail it						
						Mail($para, $titulo, $x_mensaje, $cabeceras);						
						
						$tiposms = "15";
						$chip = "";
						#CAMBIAR A CHIP NO 1
						
						insertaSmsTabla($conn, $x_cliente_idp, $x_credito_id, $tiposms, $chip, $x_mensaje, $titulo, $x_no_celular);					
						#se manda mensaje y se actualiza la tarea del CRM a completa
						
						// echo "<br>".$x_mensaje."<br>";	
						 $x_contador_6 ++;		
						
			}			
		 }// while celulares
		}
		
		
		
		
		}
echo "<br><br>".$x_contador_6."<br><br>";		
$x_total_mensaje = $x_contador_1 + $x_contador_2 + $x_contador_3 + $x_contador_4 + $x_contador_5 + $x_contador_6;


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

$x_fecha_base_busqueda = "2012-06-01";
$x_fecha_sep = explode("-",$x_fecha_base_busqueda);
$x_anio = $x_fecha_sep[0];
$x_mes = $x_fecha_sep[1];
$x_dia = $x_fecha_sep[2];


//SELECT name, birth FROM pet WHERE MONTH(birth) = 5;

$sqlCumple = "SELECT cliente.cliente_id, solicitud.solicitud_id FROM solicitud JOIN solicitud_cliente ON solicitud_cliente.solicitud_id = solicitud.solicitud_id JOIN  cliente ON cliente.cliente_id = solicitud_cliente.cliente_id WHERE solicitud.fecha_registro >= \"$x_fecha_base_busqueda\"";
$rsCumple = phpmkr_query($sqlCumple, $conn) or die ("Error al seleccionar la fecha de la la solcituid".phpmkr_error()."sql:".$sqlCumple);
while($rowCumple = phpmkr_fetch_array($rsCumple)){
	$x_solicitud_id = $rowCumple["solicitud_id"];
	$x_cliente_id = $rowCumple["cliente_id"];
	echo "sol_id".$x_solicitud_id ."<br>";
	echo "cliente _id".$x_cliente_id."<br>";
	// buscamos los datos
	// seleccionamso los clientes del dia de hoy
	
	if(!empty($x_solicitud_id) && !empty($x_cliente_id)){
	$sqlCumple2 = "SELECT * FROM cliente WHERE MONTH(fecha_nac) = $x_mes and DAY(fecha_nac) = $x_dia and cliente_id = $x_cliente_id";//   = \"$x_currdate\" ";
	$rsCumple2 = phpmkr_query($sqlCumple2, $conn) or die ("Error al seleccionar la fecha de la la solcituid".phpmkr_error()."sql:".$sqlCumple2);
	echo "month ".$sqlCumple2;
	while($rowCumple2 = phpmkr_fetch_array($rsCumple2)){
		
		// sacamos los datos del cliente con credito_id
		$sqlCliente = " SELECT cliente_id FROM solicitud_cliente JOIN solicitud ON solicitud.solicitud_id = solicitud_cliente.solicitud_id JOIN ";
		$sqlCliente .= " credito ON credito.solicitud_id = solicitud.solicitud_id WHERE solicitud.solicitud_id = $x_solicitud_id";
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
						$x_mensaje = "FINANCIERA CREA LE DESEA FELIZ CUMPLEANOS!!. ";
						#$x_mensaje .= " USTED PODRA OBTENER UN 10% DE DESCUENTO EN SU RENOVACION, SI CONTINUA PAGANDO PUNTUALMENTE.";							
						echo "MENSAJE :". $x_mensaje."<BR>";										
						// Varios destinatarios
						$para  = 'sms@financieracrea.com'; // atención a la coma
						// subject
						$titulo = $x_no_celular;						
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						// Mail it						
						Mail($para, $titulo, $x_mensaje, $cabeceras);												
						$tiposms = "7";
						$chip = "";
						#CAMBIAR A CHIP NO 1						
						insertaSmsTabla($conn, $x_cliente_idp, $x_credito_id, $tiposms, $chip, $x_mensaje, $titulo, $x_no_celular);					
						#se manda mensaje y se actualiza la tarea del CRM a completa
						
						// echo "<br>".$x_mensaje."<br>";	
						 $x_contador_6 ++;	
						 
					}		
		 }// while celulares
			}
			
			}// c2
	}//fin if sol y client no empty
	}//C
	

###############################################################################################################
###############################################################################################################
###############################################################################################################
############################   FELIZ CUMPLEAÑOS   AVAL  #######################################################
###############################################################################################################
###############################################################################################################
###############################################################################################################
$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
$currdate = ConvertDateToMysqlFormat($currdate);

$x_fecha_base_busqueda = "2012-06-01";
$x_fecha_sep = explode("-",$x_fecha_base_busqueda);
$x_anio = $x_fecha_sep[0];
$x_mes = $x_fecha_sep[1];
$x_dia = $x_fecha_sep[2];



$sqlCumple = "SELECT cliente.cliente_id, solicitud.solicitud_id FROM solicitud JOIN solicitud_cliente ON solicitud_cliente.solicitud_id = solicitud.solicitud_id JOIN  cliente ON cliente.cliente_id = solicitud_cliente.cliente_id WHERE solicitud.fecha_registro >= \"$x_fecha_base_busqueda\" ";
$rsCumple = phpmkr_query($sqlCumple, $conn) or die ("Error al seleccionar la fecha de la la solcituid".phpmkr_error()."sql:".$sqlCumple);
while($rowCumple = phpmkr_fetch_array($rsCumple)){
	$x_solicitud_id = $rowCumple["solicitud_id"];
	$x_cliente_id = $rowCumple["cliente_id"];
		$x_cliente_id = $rowCumple["cliente_id"];
	echo "sol_id2".$x_solicitud_id ."<br>";
	echo "cliente _id2".$x_cliente_id."<br>";
	// buscamos los datos
	// seleccionamso los clientes del dia de hoy
	if(!empty($x_solicitud_id) && !empty($x_cliente_id)){
	$sqlCumple2 = "SELECT * FROM datos_aval WHERE MONTH(fecha_nacimiento) = $x_mes and DAY(fecha_nacimiento) = $x_dia and solicitud_id = $x_solicitud_id";//   = \"$x_currdate\" ";
	$rsCumple2 = phpmkr_query($sqlCumple2, $conn) or die ("Error al seleccionar la fecha de la la solcituid".phpmkr_error()."sql:".$sqlCumple2);
	echo "month ".$sqlCumple2;
	while($rowCumple2 = phpmkr_fetch_array($rsCumple2)){
		
		$x_aval_id = $rowCumple2["datos_aval_id"];
			
			if (!empty($x_aval_id)){
		 $sqlCelular = "SELECT numero FROM telefono WHERE aval_id = $x_aval_id  AND telefono_tipo_id = 2 ORDER BY `telefono_id` DESC ";
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
						$x_mensaje = "FINANCIERA CREA LE DESEA FELIZ CUMPLEANOS!!. ";
												
						echo "MENSAJE :". $x_mensaje."<BR>";										
						// Varios destinatarios
						$para  = 'sms@financieracrea.com'; // atención a la coma
						// subject
						$titulo = $x_no_celular;						
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						// Mail it						
						Mail($para, $titulo, $x_mensaje, $cabeceras);												
						$tiposms = "14";
						$chip = "";
						#CAMBIAR A CHIP NO 1						
						insertaSmsTabla($conn, $x_cliente_idp, $x_credito_id, $tiposms, $chip, $x_mensaje, $titulo, $x_no_celular);					
						#se manda mensaje y se actualiza la tarea del CRM a completa
						
						 echo "<br>".$x_mensaje."<br>";	
						 $x_contador_6 ++;	
						 
					}	
		 }// while celulares
			}
			
			}// c2
	}// sol_id clien is no empty
	}//C






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
		
		
		
		//$sqlMañana = " SELECT DATE_SUB(CURDATE(),INTERVAL 1 WEEK) AS semana_antes_penultimo_pago";
		
		$sqlMañana = " SELECT DATE_SUB(\"$x_fecha_ultimo_vencimiento\",INTERVAL 1 WEEK) AS semana_antes_penultimo_pago";
		$rsMañana =  phpmkr_query($sqlMañana, $conn) or die ("erorr al seleccionar el dia de mañana 2 ". phpmkr_error()."sql:". $sqlMañana);
		$rowMañana = phpmkr_fetch_array($rsMañana);
		$x_semana_antes_penultimo_pago = $rowMañana["semana_antes_penultimo_pago"];
		
		
		if($x_semana_antes_penultimo_pago == $x_hoy){
		// se hace el calculo para ver si se envia el mensaje.
		// seleccionamos todos los vencimientos pagados y la fecha en que se pagaron y vemos si se pagaron bien o con atraso		
		// cuando la fecha de pago sea diferente a la fecha de venciiento,se considera como pago malo.
		// seleccionamos todos los pagos que se registraron ayer
		$sqlVencimientos = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and vencimiento.vencimiento_status_id = 2 ORDER BY `vencimiento`.`vencimiento_id` ASC ";
		$responseVenc = phpmkr_query($sqlVencimientos, $conn) or die ("error al seleccionar los vencimientos".phpmkr_error()."sql:".$sqlVencimientos);
		$x_inicio = 1;
		$x_pago_con_atraso = 0;
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
						Mail($para, $titulo, $x_mensaje, $cabeceras);												
						$tiposms = "5";
						$chip = "";
						#CAMBIAR A CHIP NO 1						
						insertaSmsTabla($conn, $x_cliente_idp, $x_credito_id, $tiposms, $chip, $x_mensaje, $titulo, $x_no_celular);					
						#se manda mensaje y se actualiza la tarea del CRM a completa				
						 
						 $x_contador_6 ++;	
						 
					}	
		 }//while celulares
			}			
			
		}
		
		
		}//semana == hoy			
	}//main while
		
		
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
  
  echo "---".$sqlCreditos4."<br>";
  $rsCredito4 =  phpmkr_query($sqlCreditos4, $conn) or die ("Error al seleccionar los creditos con 4 venc".phpmkr_error()."sql:".$sqlCreditos4);
  while($rowCredito4 = phpmkr_fetch_array($rsCredito4)){
	  $x_credito_id = $rowCredito4["credito_id"];
	  $x_numero_vencimientos = $rowCredito4["numero_vencimientos"];	
	 
	 // SELECCIONAMOS LOS DATOS DEL CREDITO
	   // seleccionamos la forma de pago del credito
		$sqlCreditodD = "SELECT * FROM credito WHERE credito_id  = $x_credito_id";
		$rsCreditoD = phpmkr_query($sqlCreditodD, $conn) or die("error al seleccionar los datos del credito".phpmkr_error()."sql".$sqlCreditodD);
		$rowCreditoD = phpmkr_fetch_array($rsCreditoD);
		$x_forma_pago_id = $rowCreditoD["forma_pago_id"];
		$x_penalizacion =  $rowCreditoD["penalizacion"];
		
		if ($x_penalizacion > 1){
			// se hace el proceso para el envio del mensaje
			$x_aplicar_proceso = 0;
			// buscamos el monto de 1 vencimeinto
			$sqlVencimientos = "SELECT SUM(total_venc) AS importe_vencimiento FROM vencimiento WHERE credito_id = $x_credito_id and vencimiento_num = 1";
			$responseVenc = phpmkr_query($sqlVencimientos, $conn) or die ("error al seleccionar los vencimientos".phpmkr_error()."sql:".$sqlVencimientos);
			$rowVenc = phpmkr_fetch_array($responseVenc);
			$x_importe_vencimiento = $rowVenc["importe_vencimiento"];
			
			//consultamos el monto vencido
			$sqlVencimientos = "SELECT SUM(total_venc) AS vencido FROM vencimiento WHERE credito_id = $x_credito_id and vencimiento.vencimiento_status_id = 3 ";
			$responseVenc = phpmkr_query($sqlVencimientos, $conn) or die ("error al seleccionar los vencimientos".phpmkr_error()."sql:".$sqlVencimientos);
			$rowVenc = phpmkr_fetch_array($responseVenc);
			$x_importe_vencido = $rowVenc["vencido"];
			
			
			// consultamos el monto del isg pendiente
			$sqlVencimientos = "SELECT SUM(total_venc) AS pendiente FROM vencimiento WHERE credito_id = $x_credito_id ";
			$sqlVencimeintos .= " and vencimiento.vencimiento_status_id = 1 and fecha_vencimiento = \"$x_ayer \"";
			$responseVenc = phpmkr_query($sqlVencimientos, $conn) or die ("error al seleccionar los vencimientos".phpmkr_error()."sql:".$sqlVencimientos);
			$rowVenc = phpmkr_fetch_array($responseVenc);
			$x_importe_pendiente = $rowVenc["pendiente"];
			$x_monto_pagar = $x_importe_pendiente + $x_importe_vencido;
			
			
			
			// verificamos si ya se le genro comison por gasto de cobranza
			$sqlVencimientos = "SELECT COUNT(*) AS comision FROM vencimiento WHERE credito_id = $x_credito_id and vencimiento_num >= 3000 ";
			$responseVenc = phpmkr_query($sqlVencimientos, $conn) or die ("error al seleccionar los vencimientos".phpmkr_error()."sql:".$sqlVencimientos);
			$rowVenc = phpmkr_fetch_array($responseVenc);
			$x_comision = $rowVenc["comision"];
			
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
			
			if(($x_comision == 0) && ($x_monto_pagar >=  $x_importe_compara)){
				// se envia al mensaje al cliente
				
				/**********************************************************
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
		 
		 
	    ################################################################################################################## 
		#####################################################  SMS  ######################################################
		################################################################################################################## 		
		 // ya tenemos el numero de celular  y los datos del mensaje, ahora hacemos el envio del mensaje de texto al celular
		 if(!empty($x_no_celular) && $x_no_celular != "5555555555"){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
						$x_mensaje = "FINANCIERA CREA: LE INFORMA QUE SE APLICARA UNA COMISION DE COBRANZA";
						$x_mensaje = " CORRESPONDIENTE A $x_importe_comision. SI NO PRESENTA SU PAGO ANTES DEL $x_ayer.";
						
											
						echo "MENSAJE :". $x_mensaje."<BR>";										
						// Varios destinatarios
						$para  = 'sms@financieracrea.com'; // atención a la coma
						// subject
						$titulo = $x_no_celular;						
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						// Mail it						
						Mail($para, $titulo, $x_mensaje, $cabeceras);												
						$tiposms = "12";
						$chip = "";
						#CAMBIAR A CHIP NO 1						
						insertaSmsTabla($conn, $x_cliente_idp, $x_credito_id, $tiposms, $chip, $x_mensaje, $titulo, $x_no_celular);					
						#se manda mensaje y se actualiza la tarea del CRM a completa
						

						 
						 $x_contador_6 ++;	
						 
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
		
		$sqlClientel = " SELECT datos_aval_id FROM datos_aval JOIN solicitud_cliente on datos_aval.cliente_id = solicitud_cliente.cliente_id  JOIN solicitud ON solicitud.solicitud_id = solicitud_cliente.solicitud_id JOIN ";
			$sqlClientel .= " credito ON credito.solicitud_id = solicitud.solicitud_id WHERE credito_id = $x_credito_id";
			$rsClientel = phpmkr_query($sqlClientel, $conn) or die ("Error al seleccionar el cliente _id". phpmkr_error(). "sql:". $sqlClientel);
			$rowClientel = phpmkr_fetch_array($rsClientel);
			$x_aval_idp = 	$rowClientel["datos_aval_id"];	
		
			
			if (!empty($x_aval_idp)){
		 $sqlCelular = "SELECT numero FROM telefono WHERE aval_id = $x_aval_idp  AND telefono_tipo_id = 2 ORDER BY `telefono_id` DESC ";
		 $rsCelular = phpmkr_query($sqlCelular, $conn) or die ("Error al seleccioanr el numero de celuar". phpmkr_error()."sql:".$sqlCelular);
		 $rowCelular = phpmkr_fetch_array($rsCelular);
		 $x_no_celular = $rowCelular["numero"];
		 $x_compania = $rowCelular["compania_id"];
		// $x_no_celular = "5540663927";
		 
		 
	    ################################################################################################################## 
		#####################################################  SMS  ######################################################
		################################################################################################################## 		
		 // ya tenemos el numero de celular  y los datos del mensaje, ahora hacemos el envio del mensaje de texto al celular
		 if(!empty($x_no_celular) && $x_no_celular != "5555555555"){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
						$x_mensaje = "F CREA:  LA CUENTA QUE USTED AVALA GENERARA UNA COMISION DE COBRANZA CORRESPONDIENTE A $x_importe_comision.";
						$x_mensaje = " SI NO PRESENTA  PAGO ANTES DEL $x_ayer ";
											
						echo "MENSAJE :". $x_mensaje."<BR>";										
						// Varios destinatarios
						$para  = 'sms@financieracrea.com'; // atención a la coma
						// subject
						$titulo = $x_no_celular;						
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						// Mail it						
						Mail($para, $titulo, $x_mensaje, $cabeceras);												
						$tiposms = "13";
						$chip = "";
						#CAMBIAR A CHIP NO 1						
						insertaSmsTabla($conn, $x_cliente_idp, $x_credito_id, $tiposms, $chip, $x_mensaje, $titulo, $x_no_celular);					
						#se manda mensaje y se actualiza la tarea del CRM a completa
						

						 echo "<br> en genera comision".$x_mensaje."<br>";	
						 $x_contador_6 ++;	
						 
					}	
				
			}//cliete_idl
						
				
				}//if comision =0 
			
			
			
			
			}// penalizacion mayor a 1
	  
		
  }// MAIN WHILE
		

	


echo "TOTAL DE MENSAJE ENVIADOS HOY = ". $x_total_mensaje."<br>";
$x_no_celular = "5540663927";
$x_mensaje = "TERMINA EL ENVIO DE MENSAJES";
echo "MENSAJE :". $x_mensaje."<BR>";										
// Varios destinatarios
$para  = 'sms@financieracrea.com'; // atención a la coma
// subject
$titulo = $x_no_celular;						
//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$cabeceras = 'From: zortiz@createc.mx';
// Mail it						
Mail($para, $titulo, $x_mensaje, $cabeceras);	
$x_no_celular = "5554018885";
$x_mensaje = "TERMINA EL ENVIO DE MENSAJES";
echo "MENSAJE :". $x_mensaje."<BR>";										
// Varios destinatarios
$para  = 'sms@financieracrea.com'; // atención a la coma
// subject
$titulo = $x_no_celular;						
//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$cabeceras = 'From: zortiz@createc.mx';
// Mail it						
Mail($para, $titulo, $x_mensaje, $cabeceras);	
	 	

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