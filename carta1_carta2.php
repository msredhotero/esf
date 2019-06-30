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

//$currdate = "2007-07-10";
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);


$sqlCAsos = "SELECT * FROM crm_caso where  crm_caso_status_id = 1 ";
$rsCaso = phpmkr_query($sqlCAsos,$conn)or die ("error ".phpmkr_error()."sql:".$sqlCAsos);
while($rowcasos = phpmkr_fetch_array($rsCaso)){
	$x_crm_caso_id = $rowcasos["crm_caso_id"];
	$x_credito_id = $rowcasos["credito_id"];
	echo "----------------------------------------------------------<br>";
	echo "----------------------------------------------------------<br>";
	echo "CASO :".$x_crm_caso_id."    CREDITO :".$x_credito_id."<BR>";
	$sqlTarea = "SELECT * from crm_tarea where crm_caso_id = $x_crm_caso_id  order by crm_tarea_id ";
	$rsTarea = phpmkr_query($sqlTarea,$conn)or die ("Error al seleccionar las tareas".phpmkr_error()."sql.".$sqlTarea);
	while($rowTareas = phpmkr_fetch_array($rsTarea)){
		$x_tarea_id = $rowTareas["crm_tarea_id"];
		$x_asunto = $rowTareas["asunto"];
		$x_fecha_registro = $rowTareas["fecha_registro"];
		
		if($x_asunto == "Carta 1"){
			
			//vemos si tine carta 2
	$sqlTareai = "SELECT * from crm_tarea where crm_caso_id = $x_crm_caso_id  order by crm_tarea_id ";
	$rsTareai = phpmkr_query($sqlTareai,$conn)or die ("Error al seleccionar las tareas".phpmkr_error()."sql.".$sqlTareai);
	while($rowTareasi = phpmkr_fetch_array($rsTareai)){
		$x_tarea_idi = $rowTareasi["crm_tarea_id"];
		$x_asuntoi = $rowTareasi["asunto"];
		
		if($x_asuntoi == "Carta 2"){
			echo "++++++++++++++++++++++++++  TIENE AMBAS CARATAS +++++++++++++++++<br>";
				}
			}
		}
		
		echo "tarea id :".$x_tarea_id." --------> $x_asunto  <br>";
		echo "registro ".$x_fecha_registro."<br>";
		echo "ejecuta ".$rowTareas["fecha_ejecuta"]."<br>";
		$sqlCV = "select * from crm_tarea_cv where crm_tarea_id = $x_tarea_id ";
		$rsCV = phpmkr_query($sqlCV,$conn)or die("Error ".phpmkr_error().$sqlCV);
		$rowCV = phpmkr_fetch_array($rsCV);
		$x_promesa_de_pago = $rowCV["promesa_pago"];
		$x_impresion = $rowCV["fecha_entrega"];
		
		
		if(!empty($x_promesa_de_pago)){
			echo "*** fecha promesa *** ".$x_promesa_de_pago;
			
			}
			if(!empty($x_impresion)){
				echo " fecha impresion ".$x_impresion;
				
				}
		echo "<br>";
		}
	
	
	
	
	
	
	}


?>