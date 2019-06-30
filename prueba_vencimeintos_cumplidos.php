
<?php include("db.php");?>
<?php include("phpmkrfn.php"); ?>
<?php include("utilerias/datefunc.php");?>

<?php
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
// penultimo pago

/* 
1 selecciona todos los venc del dia de hoy
2 cuenta total de vencimientos y compara (totvenc - 1) = num_venc
3 valida que no tenga tareas de seguimiento a final de credito ya registrada en crm
*/

// la fecha de venciemiente debe ser dos dias mas que la fecha actual.
//Fecha Vencimiento mañana
$temptime = strtotime($currdate);	
$temptime = DateAdd('w',1,$temptime);
$fecha_venc = strftime('%Y-%m-%d',$temptime);			
$x_dia = strftime('%A',$temptime);
if($x_dia == "SUNDAY"){
	$temptime = strtotime($fecha_venc);
	$temptime = DateAdd('w',1,$temptime);
	$fecha_venc = strftime('%Y-%m-%d',$temptime);
}
$temptime = strtotime($fecha_venc);



$sSqlWrk = "select * from vencimiento where fecha_vencimiento = '$fecha_venc' and vencimiento_status_id = 1";
$rswrkct = phpmkr_query($sSqlWrk,$conn) or exit();
while($datawrkct = phpmkr_fetch_array($rswrkct)){
	$x_credito_id = $datawrkct["credito_id"];	
	echo "CREDITO ID = ".$x_credito_id."<BR>";
	$x_vencimiento_id = $datawrkct["vencimiento_id"];		
	$x_vencimiento_num = intval($datawrkct["vencimiento_num"]);	
	
	


	$sSqlWrk = "SELECT max(vencimiento_num) as venc_num_tot FROM vencimiento WHERE credito_id = ".$x_credito_id;
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$datawrk = phpmkr_fetch_array($rswrk);
	$x_venc_num_tot = $datawrk["venc_num_tot"];
	$x_penultimo_credito = intval($x_venc_num_tot - 1);
	@phpmkr_free_result($rswrk);
	$x_pagos_vencidos = "";
	if($x_venc_num_tot == $x_vencimiento_num){
		echo "numero total de vencimeintos =".$x_venc_num_tot."<br>";
		echo "vencimeito_actual = ".$x_vencimiento_num."<br>";
		
	
	$sqlVencidos = "select count(*)as vencidos from vencimiento where credito_id = $x_credito_id and `vencimiento_status_id` = 3";
	$responseV = phpmkr_query($sqlVencidos,$conn) or die("error en vencidos");
	$rowVencidos = phpmkr_fetch_array($responseV);
	$x_pagos_vencidos = $rowVencidos["vencidos"];
	phpmkr_free_result($rowVencidos);
	
	
	// si tiene pagos vencidos no se hace la renovacion
	if($x_pagos_vencidos == 0 ){
		// se genaran las tareas		
		echo " NO Tiene pagos vencidos SE HACE LA RENOVACION";
		
	
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		}// fin si numero de vencimientos == a numero de recibos
	
	

		 
		 
	}// if numero de venciemientos
	
}// while gral
$currentdatecrm = getdate(time());
$currtime = $currentdatecrm["hours"].":".$currentdatecrm["minutes"].":".$currentdatecrm["seconds"];	

$cdate = getdate(time());
$currdate2 = $cdate["mday"]."/".$cdate["mon"]."/".$cdate["year"];	
$currdate2 = ConvertDateToMysqlFormat($currdate2);


$temptime = strtotime($currdate2);	
$temptime = DateAdd('w',2,$temptime);
$fecha_tarea = strftime('%Y-%m-%d',$temptime);			
$x_dia = strftime('%A',$temptime);
if($x_dia == "SUNDAY"){
	$temptime = strtotime($fecha_venc);
	$temptime = DateAdd('w',1,$temptime);
	$fecha_tarea = strftime('%Y-%m-%d',$temptime);
}
$temptime = strtotime($fecha_tarea);
	
echo "fecho fecha tarea;".$temptime."<br>".$fecha_tarea."<br>";	
echo "mysqlFomat".ConvertDateToMysqlFormat($fecha_tarea)."<br>";	
	
	die();
?>