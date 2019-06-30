<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>

<?PHP
//seleccionamos los pagos registrados el dia de hoy
$x_today =  date("Y-m-d",time()-86400); // el ultimo dia del mes; el proceso corre el primero de cada mes
$x_fech = explode("-",$x_today);
$x_primer_dia_mes = $x_fech[0]."-".$x_fech[1]."-"."01";


$conn = phpmkr_db_connect(HOST, USER, PASS,DB, PORT);
$sqlPagos = "SELECT  vencimiento.credito_id FROM recibo, recibo_vencimiento, vencimiento WHERE  recibo.recibo_id = recibo_vencimiento.recibo_id and  recibo_vencimiento.vencimiento_id = vencimiento.vencimiento_id and fecha_registro >= \"$x_primer_dia_mes\"  and fecha_registro <= \"$x_today\" group by credito_id ";

#$sqlPagos = "SELECT recibo.recibo_id, recibo.importe, vencimiento.credito_id FROM recibo, recibo_vencimiento, vencimiento WHERE  recibo.recibo_id = recibo_vencimiento.recibo_id and  recibo_vencimiento.vencimiento_id = vencimiento.vencimiento_id and fecha_registro >= \"$x_primer_dia_mes\"  and fecha_registro <= \"$x_today\" ";//group by recibo_id ";


echo $sqlPagos;
$rsPagos = phpmkr_query($sqlPagos,$conn) or die ("Erro pagos".phpmkr_error().$sqlPagos);
while($rowPagos = mysql_fetch_assoc($rsPagos)){ 
	
	$x_credito_id = $rowPagos["credito_id"];
	
	
	
	
	/*$x_fecha_pago = $rowPagos["fecha_pago"];
	$x_fecha_registro = $rowPagos["fecha_registro"];
	$x_importe = $rowPagos["importe"];
	$x_credito_id = $rowPagos["credito_id"];*/
	//$x_fecha_pago = $rowPagos["fecha_pago"];
	$x_suma_pagos = 0;
	
	//vencimientos // sleccionamos todos los vencimiento que esten pagados
	$sqlVen = "SELECT vencimiento_id FROM vencimiento WHERE credito_id = $x_credito_id  and vencimiento_status_id = 2";
	$rsVen = phpmkr_query($sqlVen,$conn) or die("Error".phpmkr_error());
	while($rowV = phpmkr_fetch_array($rsVen)){
		$x_vencimiento_id .= $rowV["vencimiento_id"].", ";
		}
		$x_vencimiento_id = trim(", ",$x_vencimiento_id);
		echo "<br>lista venc".$x_vencimiento_id;
		die();
		
	$sqlVen = "SELECT distinct recibo_id  FROM recibo_vencimiento WHERE vencimiento_id in ($x_vencimiento_id) ";
	$rsVen = phpmkr_query($sqlVen,$conn) or die("Error".phpmkr_error());
	while($rowV =phpmkr_fetch_array($rsVen)){
		$x_recibo_id_l .= $rowV["recibo_id"].", ";
		}
		$x_recibo_id_l = trim(", ",$x_recibo_id_l);
		echo "<br>lista recibo_id".$x_recibo_id_l;	
	// seleccionamos todos los pagos de ese mes
	$x_suma_pagos= 0;
	$sqlPgos = "SELECT  recibo.importe  FROM recibo where recibo_id in ($x_recibo_id_l) and fecha_registro >= \"$x_primer_dia_mes\"  and fecha_registro <= \"$x_today\"  ";
	//$rsPgos = phpmkr_query($sqlPgos,$conn) or die("Erro al seleccionar".$sqlPgos."sql.".$sqlPgos);
	while($rosPgos = phpmkr_fetch_array($rsPgos)){
		$x_suma_pagos += $rosPgos["importe"]; 
		} 
		echo "...".$x_suma_pagos."<br>";

	$x_importe_pago = $x_suma_pagos;
	echo "<br>IMPORTE PAGO".$x_importe_pago."<BR>";
	
	// seleccionamos el importe del credito
	$sqlCredito = "SELECT importe, solicitud_id, credito_num FROM credito WHERE credito_id = $x_credito_id ";
	$rsCredito = phpmkr_query($sqlCredito,$conn)or die ("Error l seleccionar credito".phpmkr_error().$sqlCredito);
	$rowCredito = phpmkr_fetch_array($rsCredito);
	$x_importe_credito = $rowCredito["importe"];
	$x_solicitud_id = $rowCredito["solicitud_id"];
	$x_credito_num = $rowCredito["credito_num"];
	$x_mitad_credito = 4000;
	
	//$x_importe = 1000000;
	if($x_importe_pago >= $x_mitad_credito ){
		// operacion inusual
		echo "importe.......".$x_importe."<br>";
		
		// se inserta el registro
		$sqlInsert = "INSERT INTO `pld_operacion_relevante` (`pld_operacion_relevante_id`, `credito_id`, `fecha`, `fecha_operacion`, `fecha_registro_operacion`, `monto_operacion`, `monto_credito`, `solicitud_id`) ";		
		$sqlInsert .= " VALUES (NULL, $x_credito_id, \"$x_today\",\"$x_fecha_pago\", \"$x_fecha_registro\", $x_importe, $x_importe_credito, $x_solicitud_id); ";		
		$rsInsert = phpmkr_query($sqlInsert,$conn) or die ("Error al INSERTAR".phpmkr_error().$sqlInsert);
		
		echo $sqlInsert;		
		// se manda mail		
		$x_mensaje = "SE HA REPORTADO UNA OPERACION RELEVANTE EN LOS PAGOS REALIZADOS EL MES ANTERIOR, POR FAVOR INGRESA AL SISTEMA Y VERIFICA EL LISTADO DE OPERACIONES RELEVANTES PARA OBTENER MAS DETALLES DEL CASO DEL CREDITO, LA OPERACION SE REGISTRO EL DIA ".$x_today;
		//echo "MENSAJE :". $x_mensaje."<BR>";										
		// Varios destinatarios
		$para  = 'oficialdecumplimiento@financieracrea.com'; // atención a la coma
		// subject
		$titulo = "=== OPERACION RELEVANTE CREDITO $x_credito_num ===";						
		//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$cabeceras = 'From: j.foncerrada@financieracrea.com';
		mail($para, $titulo, $x_mensaje, $cabeceras);	
		
		}
	echo 	$recibo_id. "<br> ".$fecha_registro."<br>";
	print_r($rowPagos);
	
	echo "<br>-----------------------------------<br>";
}

