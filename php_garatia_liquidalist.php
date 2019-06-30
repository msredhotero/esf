<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
// seleccionamos todas  las garantias liquidas

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$sql = "SELECT * FROM garantia_liquida join garantia_liquida_status where garantia_liquida_status.garantia_liquida_status_id= garantia_liquida.status order by status, fecha  ";
$rsGarantia = phpmkr_query($sql,$conn)or die ("Error al seleccionar la garantia iquida ".phpmkr_error()."sql :".$sql);
while($rowGarania = phpmkr_fetch_array($rsGarantia)){
	$x_garantia_liquida_id = $rowGarania["garantia_liquida_id"];
	$x_credito_id = $rowGarania["credito_id"];
	$x_monto = $rowGarania["monto"];
	$x_status = $rowGarania["status"];
	$x_fecha = $rowGarania["fecha"];
	$x_descripcion = $rowGarania["descripcion"];
	
	$sqlCredio = "SELECT * FROM  credito WHERE credito_id = $x_credito_id ";
	$rsCredito = phpmkr_query($sqlCredio,$conn)or die ("error al seleccionar los credito".phpmkr_error());
	$rowCredito = phpmkr_fetch_array($rsCredito);
	$x_credito_status = $rowCredito["credito_status_id"];
	$x_credito_garantia = $rowCredito["garantia_liquida"];
	if($x_credito_status == 1)
		$x_credito_status = "ACTIVO";
		
	if($x_credito_status == 3)
		$x_credito_status = "LIQUIDADO";	
	
	 
	 if($x_credito_status == 2)
		$x_credito_status = "CANCELADO";
		
	if($x_credito_status == 4)
		$x_credito_status = "COBRANZA EXTERN";	
		
	if($x_credito_status == 5)
		$x_credito_status = "INCOBRABLE";			
	
	
	echo "id ".$x_garantia_liquida_id ."status ".$x_descripcion." monto ".$x_monto." fecha ".$x_fecha. "credito_id ".$x_credito_id."<br>";
	 echo $x_credito_status ." gar ".$x_credito_garantia."<br><BR>";
	 
	 if($x_credito_status == "LIQUIDADO" && $x_descripcion =="VIGENTE" ){
		 echo " revisar <br><br>";
		 }
		 
		 
	if($x_credito_status != "LIQUIDADO" && $x_descripcion =="APLICADA A ULTIMO PAGO" ){
		 echo " revisar ................<br><br>";
		 }	 
	
	
	
}


//echo $X_LIQUIDADO
?>