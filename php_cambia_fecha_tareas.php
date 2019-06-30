
<?php session_start(); ?>
<?php ob_start(); ?>

<?php
include ("db.php");
include ("phpmkrfn.php");
include("datefunc.php");



$x_hoy = date("Y-m-d");
$conn = phpmkr_db_connect(HOST, USER, PASS,DB, PORT);


#1.- seleccionar todas las tareas de la zona 4
#2.- Recorrer las tareas 1 semana
#3.- Recorrer la fecha lista en tareas diarias promotor, tareas diarias gestor
#4.- Cambian las fechas de vencimiento de tareas en crm_tarea


//zona de hoy es miercoles dia semana 4 zona 4

$sqlZona4 = " SELECT * FROM tarea_diaria_promotor WHERE dia_semana = 4  ";
$rsZona4 = phpmkr_query($sqlZona4,$conn) or die ("Error al seleccionar la tareas zona 4". phpmkr_error()."sql:".$sqlZona4);
while( $rowZona4 = phpmkr_fetch_array($rsZona4)){
	$x_tarea_diaria_promotor_id = $rowZona4["tarea_diaria_promotor_id"];
	$x_crm_caso_id = $rowZona4["caso_id"];
	$x_crm_tarea_id = $rowZona4["tarea_id"];
	$x_fecha_lista = $rowZona4["fecha_lista"];
	echo "FECHA LISTA EN TAREA DIARIA".$x_fecha_lista. "--".$x_tarea_diaria_promotor_id." --<BR>";
	
	# a la fecha lista le agregamos 1 semana
	$sqlSemana = "SELECT DATE_ADD(\"$x_fecha_lista\", INTERVAL 7 DAY) AS fecha_nueva";
	$rsSemana = phpmkr_query($sqlSemana,$conn) or die ("Error al agragra 7 dias".phpmkr_error()."sql:".$sqlSemana);
	$rowSemana = phpmkr_fetch_array($rsSemana);
	$x_fecha_nueva = $rowSemana["fecha_nueva"];
	
	#actualizamos las fechas en las tareas
	$sqlUpdateTP = "UPDATE tarea_diaria_promotor SET fecha_lista = \"$x_fecha_nueva\" WHERE tarea_diaria_promotor_id = $x_tarea_diaria_promotor_id ";
	$rsUpdateTP = phpmkr_query($sqlUpdateTP,$conn)or die ("Error al actualiza fechas TDP".phpmkr_error()."sql :".$sqlUpdateTP);
	echo $sqlUpdateTP."<br>";
	#seleccionamos la fceha de la tareas en CRM_TAREA
	$sqlCRMT = "SELECT * FROM crm_tarea WHERE crm_tarea_id = $x_crm_tarea_id ";
	$rsCRMT = phpmkr_query($sqlCRMT,$conn)or die("Error al sel las tareas de CRM_TREA".phpmkr_error()."sql:".$sqlCRMT);
	$rowCRMT = phpmkr_fetch_array($rsCRMT);
	$x_fecha_vencimiento = $rowCRMT["fecha_ejecuta"];
	$x_descripcion = $rowCRMT["descripcion"];
	echo "DESCRIPCION".$x_descripcion."<BR>";
	echo "fecha ejecuta". $x_fecha_vencimiento." --".$x_crm_tarea_id."--<br>";
	
	# a la fecha lista le agregamos 1 semana
	$sqlSemana = "SELECT DATE_ADD(\"$x_fecha_vencimiento\", INTERVAL 7 DAY) AS fecha_nueva";
	$rsSemana = phpmkr_query($sqlSemana,$conn) or die ("Error al agragra 7 dias".phpmkr_error()."sql:".$sqlSemana);
	$rowSemana = phpmkr_fetch_array($rsSemana);
	$x_fecha_nueva = $rowSemana["fecha_nueva"];
	#actualizamos las fechas en las tareas
	$sqlUpdateTP = "UPDATE crm_tarea SET fecha_ejecuta = \"$x_fecha_nueva\" WHERE crm_tarea_id = $x_crm_tarea_id ";
	$rsUpdateTP = phpmkr_query($sqlUpdateTP,$conn)or die ("Error al actualiza fechas TDP".phpmkr_error()."sql :".$sqlUpdateTP);
	echo $sqlUpdateTP."<br>";
	
	
	}//while zona 4   




echo "<br><br><br><br>";
$sqlZona4 = " SELECT * FROM tarea_diaria_gestor WHERE dia_semana = 4  ";
$rsZona4 = phpmkr_query($sqlZona4,$conn) or die ("Error al seleccionar la tareas zona 4". phpmkr_error()."sql:".$sqlZona4);
while( $rowZona4 = phpmkr_fetch_array($rsZona4)){
	$x_tarea_diaria_gestor_id = $rowZona4["tarea_diaria_gestor_id"];
	$x_crm_caso_id = $rowZona4["caso_id"];
	$x_crm_tarea_id = $rowZona4["tarea_id"];
	$x_fecha_lista = $rowZona4["fecha_lista"];
	echo "tarea gestor id".$x_tarea_diaria_gestor_id."<br>";
	echo "FECHA LISTA EN TAREA DIARIA".$x_fecha_lista. "--".$x_tarea_diaria_promotor_id." --<BR>";
	
	# a la fecha lista le agregamos 1 semana
	$sqlSemana = "SELECT DATE_ADD(\"$x_fecha_lista\", INTERVAL 7 DAY) AS fecha_nueva";
	$rsSemana = phpmkr_query($sqlSemana,$conn) or die ("Error al agragra 7 dias".phpmkr_error()."sql:".$sqlSemana);
	$rowSemana = phpmkr_fetch_array($rsSemana);
	$x_fecha_nueva = $rowSemana["fecha_nueva"];
	
	#actualizamos las fechas en las tareas
	$sqlUpdateTP = "UPDATE tarea_diaria_gestor SET fecha_lista = \"$x_fecha_nueva\" WHERE tarea_diaria_gestor_id = $x_tarea_diaria_gestor_id ";
	$rsUpdateTP = phpmkr_query($sqlUpdateTP,$conn)or die ("Error al actualiza fechas TDP".phpmkr_error()."sql :".$sqlUpdateTP);
	echo $sqlUpdateTP."<br>";
	#seleccionamos la fceha de la tareas en CRM_TAREA
	$sqlCRMT = "SELECT * FROM crm_tarea WHERE crm_tarea_id = $x_crm_tarea_id ";
	$rsCRMT = phpmkr_query($sqlCRMT,$conn)or die("Error al sel las tareas de CRM_TREA".phpmkr_error()."sql:".$sqlCRMT);
	$rowCRMT = phpmkr_fetch_array($rsCRMT);
	$x_fecha_vencimiento = $rowCRMT["fecha_ejecuta"];
	$x_descripcion = $rowCRMT["descripcion"];
	echo "DESCRIPCION".$x_descripcion." CRM_ATREA ID ".$x_crm_tarea_id."<BR>";
	echo "fecha ejecuta". $x_fecha_vencimiento." --".$x_crm_tarea_id."--<br>";
	
	# a la fecha lista le agregamos 1 semana
	$sqlSemana = "SELECT DATE_ADD(\"$x_fecha_vencimiento\", INTERVAL 7 DAY) AS fecha_nueva";
	$rsSemana = phpmkr_query($sqlSemana,$conn) or die ("Error al agragra 7 dias".phpmkr_error()."sql:".$sqlSemana);
	$rowSemana = phpmkr_fetch_array($rsSemana);
	$x_fecha_nueva = $rowSemana["fecha_nueva"];
	#actualizamos las fechas en las tareas
	$sqlUpdateTP = "UPDATE crm_tarea SET fecha_ejecuta = \"$x_fecha_nueva\" WHERE crm_tarea_id = $x_crm_tarea_id ";
	$rsUpdateTP = phpmkr_query($sqlUpdateTP,$conn)or die ("Error al actualiza fechas TDP".phpmkr_error()."sql :".$sqlUpdateTP);
	echo $sqlUpdateTP."<br>";
	
	
	}//while zona 4   





















?>