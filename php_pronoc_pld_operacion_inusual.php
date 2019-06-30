<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>

<?PHP
//seleccionamos los pagos registrados el dia de hoy
$x_today =  date("Y-m-d",time()-86400);;
$conn = phpmkr_db_connect(HOST, USER, PASS,DB, PORT);
$sqlPagos = "SELECT recibo.*, vencimiento.credito_id FROM recibo, recibo_vencimiento, vencimiento WHERE  recibo.recibo_id = recibo_vencimiento.recibo_id and  recibo_vencimiento.vencimiento_id = vencimiento.vencimiento_id and fecha_registro = \"$x_today\"  group by credito_id ";
echo $sqlPagos;
$rsPagos = phpmkr_query($sqlPagos,$conn) or die ("Erro pagos".phpmkr_error().$sqlPagos);
while($rowPagos = mysql_fetch_assoc($rsPagos)){ 
	
	/*foreach($rowPagos as $campo => $valor){
	$campo = "x_".$campo;
	$$campo = $valor;	
	echo "campo valor".$campo." ".$valor." ---";
	}*/
	
	//$x_importe_pago = $x_importe;
	//echo "<br>IMPORTE PAGO".$x_importe_pago."<BR>";
	
	$x_fecha_pago = $rowPagos["fecha_pago"];
	$x_fecha_registro = $rowPagos["fecha_registro"];
	$x_importe = $rowPagos["importe"];
	$x_credito_id = $rowPagos["credito_id"];
	//$x_fecha_pago = $rowPagos["fecha_pago"];
	
	$x_importe_pago = $x_importe;
	echo "<br>IMPORTE PAGO".$x_importe_pago."<BR>";
	
	// seleccionamos el importe del credito
	$sqlCredito = "SELECT importe, solicitud_id, credito_num FROM credito WHERE credito_id = $x_credito_id ";
	$rsCredito = phpmkr_query($sqlCredito,$conn)or die ("Error l seleccionar credito".phpmkr_error().$sqlCredito);
	$rowCredito = phpmkr_fetch_array($rsCredito);
	$x_importe_credito = $rowCredito["importe"];
	$x_solicitud_id = $rowCredito["solicitud_id"];
	$x_credito_num = $rowCredito["credito_num"];
	$x_mitad_credito = ($x_importe_credito/2)+1;
	if($x_importe > $x_mitad_credito ){
		// operacion inusual
		echo "importe".$x_importe."<br>";
		
		// se inserta el registro
		$sqlInsert = "INSERT INTO `pld_operacion_inusual` (`pld_operacion_inusual_id`, `credito_id`, `fecha`, `fecha_operacion`, `fecha_registro_operacion`, `monto_operacion`, `monto_credito`, `solicitud_id`) ";		
		$sqlInsert .= " VALUES (NULL, $x_credito_id, \"$x_today\",\"$x_fecha_pago\", \"$x_fecha_registro\", $x_importe, $x_importe_credito, $x_solicitud_id); ";		
		$rsInsert = phpmkr_query($sqlInsert,$conn) or die ("Error al INSERTAR".phpmkr_error().$sqlInsert);
		
		echo $sqlInsert;		
		// se manda mail		
		$x_mensaje = "SE HA REPORTADO UNA OPERACION INUSUAL EN LOS PAGOS REALIZADOS EL DIA DE AYER, POR FAVOR INGRESA AL SISTEMA Y VERIFICA EL LISTADO DE OPERACIONES INUSUALES PARA OBTENER MAS DETALLES DEL CASO DEL CREDITO LA OPERACION SE REGISTRO EL DIA ".$x_today;
		echo "MENSAJE :". $x_mensaje."<BR>";										
		// Varios destinatarios
		$para  = 'oficialdecumplimiento@financieracrea.com'; // atención a la coma
		// subject
		$titulo = "=== OPERACION INUSUAL CREDITO $x_credito_num ===";						
		//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$cabeceras = 'From: j.foncerrada@financieracrea.com';
		mail($para, $titulo, $x_mensaje, $cabeceras);	
		
		}
	echo 	$recibo_id. "<br> ".$fecha_registro."<br>";
	print_r($rowPagos);
	
	echo "<br>-----------------------------------<br>";
}

