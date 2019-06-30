<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php include("utilerias/datefunc.php") ?>

<?php
//seleccionamos todos lo creditos que ya estan liquidados y que no estan en la lista de creditos liquidados de la nueva tabla.
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);


$sqlLiquidados = "SELECT * FROM credito WHERE credito.credito_status_id = 3 AND credito_id NOT IN( SELECT credito_id FROM credito_liquidado )";
$rsLiquidados = phpmkr_query($sqlLiquidados, $conn) or die ("Error al seleccionar  los creditos liquidados".phpmkr_error."sql:".$sqlLiquidados);
$x_cont = 1;
while($rowLiquidados = phpmkr_fetch_array($rsLiquidados)){
	$x_credito_id = $rowLiquidados["credito_id"];
	$x_solicitud_id = $rowLiquidados["solicitud_id"];
	$x_numero_credito = $rowLiquidados["credito_num"];
	echo "NUMERO :".$x_cont." credito_num ".$x_numero_credito." <BR>";
	$x_cont++;
	// seleccionamos el ultimo vencimiento del credito
	$sqlVencimiento = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id  order by vencimiento_id DESC limit 0,1";
	$rsVencimiento = phpmkr_query($sqlVencimiento, $conn) or die("error al seleccionar el ultimo venc". phpmkr_error()."sql:".$sqlVencimiento);
	$rowVencimiento = mysql_fetch_array($rsVencimiento);
	$x_vencimeinto_id = $rowVencimiento["vencimiento_id"];
	$x_no_venc = $rowVencimiento["vencimiento_num"];
	// selecionamos la fecha de pago del vencimiento
	$sqlRecibo = "SELECT * FROM recibo JOIN recibo_vencimiento ON recibo_vencimiento.recibo_id = recibo.recibo_id WHERE recibo_vencimiento.vencimiento_id = $x_vencimeinto_id ";
	$rsRecibo = phpmkr_query($sqlRecibo, $conn) or die ("Error al seleccionar los datos del recibo". phpmkr_error()."sql:".$sqlRecibo);
	$rowRecibo = phpmkr_fetch_array($rsRecibo);
	$x_fecha_pago = $rowRecibo["fecha_pago"];
	
	//insertamos en la tabla de credito liquidado
	$sqlInsertLiquidado = "INSERT INTO credito_liquidado(`credito_liquidado_id`,`credito_id`,`solicitud_id`,`fecha`,`hora`,`status`,`x`, `xx`)";
	$sqlInsertLiquidado .= " VALUES ('NULL', $x_credito_id, $x_solicitud_id,'$x_fecha_pago','',1,1,1)";
	echo "Sql insert :".$sqlInsertLiquidado."<br>";
	$rsInsertLiquidado = phpmkr_query($sqlInsertLiquidado, $conn) or die("Error al insertar en liquida".phpmkr_error()."sql:".$sqlInsertLiquidado);
	if($rsInsertLiquidado){
		echo "SE INSERTO EN CREDITO LIQUIDADO";
		
		}
	
	
	
	
	}





?>