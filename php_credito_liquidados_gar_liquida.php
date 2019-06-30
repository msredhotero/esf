<?php session_start(); ?>
<?php ob_start(); ?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);


// sleccionamos todo los credito semanales
echo "================================ CREDITOS SEMANALES ===================================<BR>";
$sqlSemana = "SELECT credito_id, credito_num FROM credito where forma_pago_id =  1 and credito_status_id = 1 and garantia_liquida = 1";
$rsSemanla =  phpmkr_query($sqlSemana,$conn) or die ("Error al seleccionar el crdito semanal".phpmkr_error()."sql:".$sqlSemana);
while($rowSemanal =  mysql_fetch_assoc($rsSemanla)){
	$x_num_pago = 0;
	$X_CREDITO_ID = $rowSemanal["credito_id"];
	$X_CREDITO_NUM = $rowSemanal["credito_num"];
	//sleccionamos los datos de los vencimientos
	$sqlVencimientos = "SELECT vencimiento_id FROM vencimiento WHERE credito_id = $X_CREDITO_ID and vencimiento.vencimiento_num = 18 and vencimiento_status_id = 2  ";
	$rosVencimiento = phpmkr_query($sqlVencimientos,$conn)or die("Error al seleccionar los vencimeinto".phpmkr_error()."sql:".$sqlVencimientos);
	$rowVenci = phpmkr_fetch_array($rosVencimiento);
	$x_vencimiento_id = $rowVenci["vencimiento_id"];
	if($x_vencimiento_id  > 0){
		echo " VENCIMIENTO_ID ".$x_vencimiento_id." ===== ";
		echo "ESTE CREDITO DEBERIA ESTAR LIQUIDADO NUMERO DE CREDITO =>".$X_CREDITO_NUM."<br>";
		}	
}

echo "================================ CREDITOS QUINCENALES ===================================<BR>";
$sqlSemana = "SELECT credito_id, credito_num FROM credito where forma_pago_id =  4 and credito_status_id = 1 and garantia_liquida = 1";
$rsSemanla =  phpmkr_query($sqlSemana,$conn) or die ("Error al seleccionar el crdito semanal".phpmkr_error()."sql:".$sqlSemana);
while($rowSemanal =  mysql_fetch_assoc($rsSemanla)){
	$x_num_pago = 0;
	$X_CREDITO_ID = $rowSemanal["credito_id"];
	$X_CREDITO_NUM = $rowSemanal["credito_num"];
	//sleccionamos los datos de los vencimientos
	$sqlVencimientos = "SELECT vencimiento_id FROM vencimiento WHERE credito_id = $X_CREDITO_ID and vencimiento.vencimiento_num = 11 and vencimiento_status_id = 2  ";
	$rosVencimiento = phpmkr_query($sqlVencimientos,$conn)or die("Error al seleccionar los vencimeinto".phpmkr_error()."sql:".$sqlVencimientos);
	$rowVenci = phpmkr_fetch_array($rosVencimiento);
	$x_vencimiento_id = $rowVenci["vencimiento_id"];
	if($x_vencimiento_id  > 0){
		echo " VENCIMIENTO_ID ".$x_vencimiento_id." ===== ";
		echo "ESTE CREDITO DEBERIA ESTAR LIQUIDADO NUMERO DE CREDITO =>".$X_CREDITO_NUM."<br>";
		}	
}
echo "=============================== CREDITOS CATORCENALES ===================================<BR>";
$sqlSemana = "SELECT credito_id, credito_num FROM credito where forma_pago_id =  2 and credito_status_id = 1 and garantia_liquida = 1";
$rsSemanla =  phpmkr_query($sqlSemana,$conn) or die ("Error al seleccionar el crdito semanal".phpmkr_error()."sql:".$sqlSemana);
while($rowSemanal =  mysql_fetch_assoc($rsSemanla)){
	$x_num_pago = 0;
	$X_CREDITO_ID = $rowSemanal["credito_id"];
	$X_CREDITO_NUM = $rowSemanal["credito_num"];
	//sleccionamos los datos de los vencimientos
	$sqlVencimientos = "SELECT vencimiento_id FROM vencimiento WHERE credito_id = $X_CREDITO_ID and vencimiento.vencimiento_num = 11 and vencimiento_status_id = 2  ";
	$rosVencimiento = phpmkr_query($sqlVencimientos,$conn)or die("Error al seleccionar los vencimeinto".phpmkr_error()."sql:".$sqlVencimientos);
	$rowVenci = phpmkr_fetch_array($rosVencimiento);
	$x_vencimiento_id = $rowVenci["vencimiento_id"];
	if($x_vencimiento_id  > 0){
		echo " VENCIMIENTO_ID ".$x_vencimiento_id." ===== ";
		echo "ESTE CREDITO DEBERIA ESTAR LIQUIDADO NUMERO DE CREDITO =>".$X_CREDITO_NUM."<br>";
		}	
}
echo "=============================== CREDITOS CATORCENALES ===================================<BR>";
$sqlSemana = "SELECT credito_id, credito_num FROM credito where forma_pago_id =  3 and credito_status_id = 1 and garantia_liquida = 1";
$rsSemanla =  phpmkr_query($sqlSemana,$conn) or die ("Error al seleccionar el crdito semanal".phpmkr_error()."sql:".$sqlSemana);
while($rowSemanal =  mysql_fetch_assoc($rsSemanla)){
	$x_num_pago = 0;
	$X_CREDITO_ID = $rowSemanal["credito_id"];
	$X_CREDITO_NUM = $rowSemanal["credito_num"];
	//sleccionamos los datos de los vencimientos
	$sqlVencimientos = "SELECT vencimiento_id FROM vencimiento WHERE credito_id = $X_CREDITO_ID and vencimiento.vencimiento_num = 11 and vencimiento_status_id = 2  ";
	$rosVencimiento = phpmkr_query($sqlVencimientos,$conn)or die("Error al seleccionar los vencimeinto".phpmkr_error()."sql:".$sqlVencimientos);
	$rowVenci = phpmkr_fetch_array($rosVencimiento);
	$x_vencimiento_id = $rowVenci["vencimiento_id"];
	if($x_vencimiento_id  > 0){
		echo " VENCIMIENTO_ID ".$x_vencimiento_id." ===== ";
		echo "ESTE CREDITO DEBERIA ESTAR LIQUIDADO NUMERO DE CREDITO =>".$X_CREDITO_NUM."<br>";
		}	
}
	
	
?>