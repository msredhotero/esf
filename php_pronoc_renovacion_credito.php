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
$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
$currdate = ConvertDateToMysqlFormat($currdate);
$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];	

$x_ayer = date("Y-m-d",time()-(24*60*60*2));


echo "CURDATE".$x_ayer."<BR>";


//$x_dia = strtoupper($currentdate["weekday"]);

//$currdate = "2007-07-10";
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

// seleccionamos todas las tareas de los usuario que son para el responsable;
$sqlListado = " SELECT crm_tarea.*, crm_caso.credito_id FROM crm_tarea  join crm_caso on crm_caso.crm_caso_id = crm_tarea.crm_caso_id WHERE orden = 3 and crm_tarea_tipo_id = 3 and destino = 6769 "; 
$rsListad = phpmkr_query($sqlListado,$conn) or die("error al seleccionar el listado de las renovaciones". phpmkr_error()."sql:".$sqlListado);
while($rowListado = phpmkr_fetch_array($rsListad)){
	$x_crm_tarea_id = $rowListado["crm_tarea_id"];
	$x_credito_id = $rowListado["credito_id"];
	echo "<br>trea_id ".$x_crm_tarea_id;
	// seleccionamos el promotor del credito
	$sqlCredito = "SELECT promotor.sucursal_id, promotor.sucursal_id FROM  credito  join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id  WHERE credito_id = $x_credito_id ";
	$rsCredio = phpmkr_query($sqlCredito,$conn) or die("error al seleccionar el query".phpmkr_error()."sql:".$sqlCredito);
	$rowCredito = phpmkr_fetch_array($rsCredio);
	$x_promotor_id = $rowCredito["promotor_id"];
	$x_sucursal_id = $rowCredito["sucursal_id"];
	$sqlSucursal = "SELECT * FROM responsable_sucursal WHERE sucursal_id = $x_sucursal_id ";
	$rsSucursal = phpmkr_query($sqlSucursal,$conn)or die("Error l seleccionar la sucursal".phpmkr_error()."sql:".$sqlSucursal);
	$rowSuc = phpmkr_fetch_array($rsSucursal);
	$x_usuario_id = $rowSuc["usuario_id"];
	if(!empty($x_usuario_id)){
	$sqlUpdate = "UPDATE crm_tarea SET destino = $x_usuario_id WHERE crm_tarea_id = $x_crm_tarea_id  ";
	$rsUpdate = phpmkr_query($sqlUpdate,$conn)or die ("erro en update".phpmkr_error()."sql:".$sqlUpdate);
	echo $sqlUpdate;
	}
	
	
	}
	
// seleccionamos todas las tareas de los usuario que son para el responsable;
$sqlListado = " SELECT crm_tarea.*, crm_caso.credito_id FROM crm_tarea  join crm_caso on crm_caso.crm_caso_id = crm_tarea.crm_caso_id WHERE orden = 1 and crm_tarea_tipo_id = 5 and destino = 7202 "; 
$rsListad = phpmkr_query($sqlListado,$conn) or die("error al seleccionar el listado de las renovaciones". phpmkr_error()."sql:".$sqlListado);
while($rowListado = phpmkr_fetch_array($rsListad)){
	$x_crm_tarea_id = $rowListado["crm_tarea_id"];
	$x_credito_id = $rowListado["credito_id"];
	echo "<br>trea_id ".$x_crm_tarea_id;
	// seleccionamos el promotor del credito
	$sqlCredito = "SELECT promotor.sucursal_id, promotor.sucursal_id FROM  credito  join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id  WHERE credito_id = $x_credito_id ";
	$rsCredio = phpmkr_query($sqlCredito,$conn) or die("error al seleccionar el query".phpmkr_error()."sql:".$sqlCredito);
	$rowCredito = phpmkr_fetch_array($rsCredio);
	$x_promotor_id = $rowCredito["promotor_id"];
	$x_sucursal_id = $rowCredito["sucursal_id"];
	$sqlSucursal = "SELECT * FROM responsable_sucursal WHERE sucursal_id = $x_sucursal_id ";
	$rsSucursal = phpmkr_query($sqlSucursal,$conn)or die("Error l seleccionar la sucursal".phpmkr_error()."sql:".$sqlSucursal);
	$rowSuc = phpmkr_fetch_array($rsSucursal);
	$x_usuario_id = $rowSuc["usuario_id"];
	if(!empty($x_usuario_id)){
	$sqlUpdate = "UPDATE crm_tarea SET destino = $x_usuario_id WHERE crm_tarea_id = $x_crm_tarea_id  ";
	$rsUpdate = phpmkr_query($sqlUpdate,$conn)or die ("erro en update".phpmkr_error()."sql:".$sqlUpdate);
	echo $sqlUpdate;
	}
	
	
	}	





?>