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


$sqlCAsos = "SELECT * FROM crm_caso where  crm_caso_status_id = 1 and credito_id in (35,
1552,
1564,
68,
1668,
2555,
968,
3001,
2011,
2278,
3134,
2408,
2448,
3000,
3032,
3384,
3544,
3839,
3713,
3961,
3612,
3626,
4116,
3812,
3971,
4078,
4091,
3944,
3947,
4311,
4466,
4369,
4320,
4070,
4484,
3894,
4486,
4784,
5016,
4854,
3869,
4135,
4794,
3029,
4704,
5031,
5175,
2671,
4978,
5130,
5127,
4938,
5475,
4979,
4815,
4319,
4808,
5245,
5341,
5881,
6020,
3290,
5140,
6357,
6356,
6073,
6012

) ";
$rsCaso = phpmkr_query($sqlCAsos,$conn)or die ("error ".phpmkr_error()."sql:".$sqlCAsos);
while($rowcasos = phpmkr_fetch_array($rsCaso)){
	$x_crm_caso_id = $rowcasos["crm_caso_id"];
	$x_credito_id = $rowcasos["credito_id"];
	$x_responsable = $rowcasos["destino"];
	echo "----------------------------------------------------------<br>";
	echo "----------------------------------------------------------<br>";
	echo "CASO :".$x_crm_caso_id."    CREDITO :".$x_credito_id."<BR>";
	
	
	$x_lista_tareas_caso = "";
	$x_lista_tareas_casoD = "";
	//busco las tareas que sean carata 1 : para cambiarlas a carta 2
	#$sqlcata1 = "SELECT * FROM crm_tarea where crm_caso_id = $x_crm_caso_id  and orden = 4 and crm_tarea_tipo_id = 8 ";
	//busco todas las BUSO TODAS LAS TAREAS PARA BORRARLAS
	$sqlcata1 = "SELECT * FROM crm_tarea where crm_caso_id = $x_crm_caso_id ";
	$rsCarta1 = phpmkr_query($sqlcata1,$conn)or die ("Error al seleccionar c1".phpmkr_error()."sql:".$sqlcata1);
	while($rowC1 = phpmkr_fetch_array($rsCarta1)){
	$x_carta_1_id = $rowC1["crm_tarea_id"];
	$x_asunto = $rowC1["asunto"];
	$x_lista_tareas_caso = $x_lista_tareas_caso.$x_carta_1_id.", ";
	$x_lista_tareas_casoD = $x_lista_tareas_casoD.$x_asunto.", ";
	}
	echo  "LISTA DE TAREAS A BORRAR ".$x_lista_tareas_caso."<BR> descripcion :".$x_lista_tareas_casoD."<BR>";
	$x_lista_tareas_caso = trim($x_lista_tareas_caso,", ");
	
	// BORRAMOS LAS TAREAS CV QUE TENGA REGISTRADAS
	
	$sqlCV = "DELETE from crm_tarea_cv  where crm_tarea_id IN ($x_lista_tareas_caso) ";
	#$rsCV = phpmkr_query($sqlCV,$conn)or die ("Error ".phpmkr_error()."sql:". $sqlCV);
	
	
	#echo "ELIMA TAREAS CV ".$sqlCV."<br>";
	$SQLtdp = "DELETE FROM tarea_diaria_promotor WHERE tarea_id in ($x_lista_tareas_caso)";
	#$rsTDP = phpmkr_query($SQLtdp,$conn)or die ("Error en delete".phpmkr_error()."sql:".$SQLtdp);
	#echo "ELIMINA T DIARIAS PROMOTOR A BORRAR ".$SQLtdp."<BR>";
	
	$sqlTDG = "DELETE FROM tarea_diaria_gestor WHERE caso_id = $x_crm_caso_id ";
	#$rsTDG = phpmkr_query($sqlTDG,$conn)or die ("Error elimimar TDG". phpmkr_error()."sql:".$sqlTDG);
	#echo " ELIMINA T DIARIAS GESTOR ".$sqlTDG."<br>";	
	// seleccionamos la tarea de la ista de tareas de promotor
	$sqlTP = "SELECT * FROM tarea_diaria_promotor WHERE tarea_id in ($x_lista_tareas_caso)";
	$rsTP = phpmkr_query($sqlTP,$conn) or die("Error al seleccionar l tarea del prmotor".phpmkr_error()."sql: ".$sqlTP);
	$rowTP = phpmkr_fetch_array($rsTP);
	$Tarea_id_p = $rowTP["tarea_diaria_promotor_id"];
	$tarea = $rowTP["tarea_id"];
	
	echo "tarea_diaria_promotor_id -->".$Tarea_id_p."<br>";
	echo "tarea gestor --->".$tarea."<br>";
	
	
	//buscamos si existe la tarea en la lis del gestor de cobranza
	$sqlTG = "SELECT * FROM tarea_diaria_gestor WHERE caso_id = $x_crm_caso_id ";
	$rsTG = phpmkr_query($sqlTG,$conn) or die("Error al seleccionar l tarea del prmotor".phpmkr_error()."sql: ".$sqlTG);
	$rowTG = phpmkr_fetch_array($rsTG);
	$Tarea_id_G = $rowTG["tarea_diaria_gestor_id"];
	$tareaG = $rowTG["tarea_id"];
	echo "tarea ".$sqlTG."<br>";
	echo "tarea_diaria_gestor_id--> ".$rowTG["tarea_diaria_gestor_id"]."<br>";
	echo "tarea gestor--->".$tareaG."<br>";
	
	$sqlDeleteCaso = "DELETE FROM crm_caso where crm_caso_id = $x_crm_caso_id ";
	#$rsDetelecaso = phpmkr_query($sqlDeleteCaso,$conn)or die ("Error al eliinar el caso".phpmkr_error()."sql: ".$sqlDeleteCaso);
	#echo "DELETE CASO ".$sqlDeleteCaso."<BR>";
	$sqlDeleteTarea = "DELETE FROM crm_tarea where crm_caso_id = $x_crm_caso_id ";
	#$rsDeleteTarea = phpmkr_query($sqlDeleteTarea,$conn) or die("error al eliminar las tareas".phpmkr_error()."sql: ".$sqlDeleteTarea);
	#echo "DELETE TAREA ".$sqlDeleteTarea."<BR>";
	
	}


?>