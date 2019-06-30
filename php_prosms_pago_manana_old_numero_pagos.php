<?php die();
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
	echo "credito_id".$x_credito_id."- ";
	#echo "cliente_id".$x_cliente_id."<br>";
	echo "vencimeinto_num".$x_vencimiento_num."- ";
	echo "importe ven".$x_total_venc."- ";
	
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
						 mail($para, $titulo, $x_mensaje, $cabeceras);	
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