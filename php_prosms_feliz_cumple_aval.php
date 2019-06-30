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
$x_today = date("Y-m-d");
$x_fecha_sep = explode("-",$x_today);
$x_anio = $x_fecha_sep[0];
$x_mes = $x_fecha_sep[1];
$x_dia = $x_fecha_sep[2];

$sqlCumple2 = "SELECT * FROM datos_aval WHERE  MONTH(fecha_nacimiento) = $x_mes and DAY(fecha_nacimiento) = $x_dia";//   = \"$x_currdate\" ";
	$rsCumple2 = phpmkr_query($sqlCumple2, $conn) or die ("Error al seleccionar la fecha de la la solcituid".phpmkr_error()."sql:".$sqlCumple2);
	echo "month ".$sqlCumple2;
	echo "sql fecha aval: ".$sqlCumple2."<br>" ;
	while($rowCumple2 = phpmkr_fetch_array($rsCumple2)){		
		$x_aval_id_list = $x_aval_id_list.$rowCumple2["solicitud_id"].", ";
	}
	
$x_aval_id_list = trim($x_aval_id_list,", ");
echo "<br> lista".$x_aval_id_list."<br>";


$sqlCumple = "SELECT cliente.cliente_id, solicitud.solicitud_id FROM solicitud JOIN solicitud_cliente ON solicitud_cliente.solicitud_id = solicitud.solicitud_id JOIN  cliente ON cliente.cliente_id = solicitud_cliente.cliente_id WHERE solicitud.fecha_registro >= \"$x_fecha_base_busqueda\" and solicitud.solicitud_id in ($x_aval_id_list) ";
$rsCumple = phpmkr_query($sqlCumple, $conn) or die ("Error al seleccionar la fecha de la la solcituid".phpmkr_error()."sql:".$sqlCumple);
echo "sql gral ".$sqlCumple."<br>";
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
	echo "sql fecha aval: ".$sqlCumple2."<br>" ;
	while($rowCumple2 = phpmkr_fetch_array($rsCumple2)){		
		$x_aval_id = $rowCumple2["datos_aval_id"];
			
			if (!empty($x_aval_id)){
				
		 $sqlCelular = "SELECT telefono_c FROM datos_aval WHERE datos_aval_id =$x_aval_id";
		 $rsCelular = phpmkr_query($sqlCelular, $conn) or die ("Error al seleccioanr el numero de celuar". phpmkr_error()."sql:".$sqlCelular);
		 echo "celular ".$sqlCelular;
		 while($rowCelular = phpmkr_fetch_array($rsCelular)){
		 $x_no_celular = $rowCelular["telefono_c"];
		 $x_compania = $rowCelular["compania_id"];
		// $x_no_celular = "5540663927";
		 echo "<br> cel ".$x_no_celular;
		 
		 $sqlCreditonum= "select credito_num from credito where solicitud_id = $x_solicitud_id";
						$rsCreditoNun = phpmkr_query($sqlCreditonum,$conn) or die("Error al seleccionar credito num".phpmkr_error()."sql:".$sqlCreditonum);
						$rowCN = phpmkr_fetch_array($rsCreditoNun);
						$x_credito_num = $rowCN["credito_num"];
	    //$x_credito_num = 1;
echo "credito num ". $sqlCreditonum ."<br> num ". $x_credito_num;		################################################################################################################## 
		#####################################################  SMS  ######################################################
		################################################################################################################## 		
		 // ya tenemos el numero de celular  y los datos del mensaje, ahora hacemos el envio del mensaje de texto al celular
		 if(!empty($x_no_celular) && $x_no_celular != "5555555555" && $x_no_celular != "0000000000" && !empty($x_credito_num)){
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
						$x_mensaje = "FINANCIERA CREA LE DESEA FELIZ CUMPLEANOS!! ";
												
						echo "MENSAJE :". $x_mensaje."<BR>";										
						// Varios destinatarios
						$para  = 'sms@financieracrea.com'; // atención a la coma
						// subject
						$titulo = $x_no_celular;						
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						// Mail it						
						mail($para, $titulo, $x_mensaje, $cabeceras);												
						$tiposms = "14";
						$chip = "";
						#se manda mensaje y se actualiza la tarea del CRM a completa
						
						
						$x_fecha_sms = date("Y-m-d");
						$sqlInsertsms =  "INSERT INTO `sms_enviados` (`id_sms_enviado`, `id_tipo_mensaje`, `contenido`, `no_credito`, `no_celular`, `fecha_registro`, `tipo_envio`, `destino`, `cliente_id`) VALUES (NULL, '14','" .$x_mensaje."', $x_credito_num, '".$x_no_celular."', '".$x_fecha_sms."', '1', '1',$x_cliente_id)";
	
	$rsInsert = phpmkr_query($sqlInsertsms, $conn) or die ("Error al inserta en sms tabla nueva q". phpmkr_error()."sql :". $sqlInsertsms);
						
						 echo "<br>".$x_mensaje."<br>";	
						 $x_contador_6 ++;	
						 
					}	
		 }// while celulares
			}
			
			}// c2
	}// sol_id clien is no empty
	}//C
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