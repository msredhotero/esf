<?php set_time_limit(0); ?>
<?php session_start(); ?>
<?php ob_start(); ?> 
<?php

// Initialize common variables

?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php include ("utilerias/datefunc.php") ?>
<?php
$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
$currdate = ConvertDateToMysqlFormat($currdate);
$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];	

echo "CURDATE".$currdate."<BR>";
//$x_dia = strtoupper($currentdate["weekday"]);

//$currdate = "2013-03-14";
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

$sqltareas =  "SELECT * FROM crm_caso join crm_tarea on crm_tarea.crm_caso_id = crm_caso.crm_caso_id ";
$rstareas = phpmkr_query($sqltareas,$conn)or die("Error al seleccionar las tareas".phpmkr_error().$sqltareas);
while($rowTareas = phpmkr_fetch_array($rstareas)){
	$x_credito_id = $rowTareas["credito_id"];	
	$x_crm_caso_id = $rowTareas["crm_caso_id"];
	$sqlgestorC = "select usuario_id from gestor_credito where credito_id = $x_credito_id ";
	$rsGestor = phpmkr_query($sqlgestorC,$conn)or die("Erro gest".phpmkr_error().$sqlgestorC);
	$rowgestor = phpmkr_fetch_array($rsGestor);
	$x_usuario_id = $rowgestor["usuario_id"];
	
	
	$sqlUpdate = "UPDATE crm_tarea set destino = $x_usuario_id where crm_caso_id = $x_crm_caso_id and destino = 7202 ";
	$rsUpdate = phpmkr_query($sqlUpdate,$conn)or die ("error".phpmkr_error().$sqlUpdate);
	echo $sqlUpdate."<br>";	
	}