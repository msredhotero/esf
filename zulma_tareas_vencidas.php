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

$sqlDia = "SELECT DAYOFWEEK('$currdate') AS dia_zona";
$rsDia = phpmkr_query($sqlDia,$conn)or die ("Error al seleccionar el dia de la semana".phpmkr_error()."sql:".$sqlDia);
$rowDia = phpmkr_fetch_array($rsDia);
$x_dia_zona = $rowDia["dia_zona"];
echo "DIA ZONA".$x_dia_zona;
//$x_dia_zona --;
if(($x_dia_zona >1) and($x_dia_zona<7)){
$sqlPromotres = "SELECT DISTINCT  `promotor_id` 
				 FROM  `solicitud` 
				 WHERE solicitud_status_id =6";
$rsPromotores = phpmkr_query($sqlPromotres,$conn)or die("Error al seleccionar los promotores".phpmkr_error()."SQL:".$sqlPromotres);		
while($rowPromotores = phpmkr_fetch_array($rsPromotores)){
	$x_promotor_tarea_id = $rowPromotores["promotor_id"];	
	if(!empty($x_promotor_tarea_id)){	 
cuentaTareasVencidas($conn, $x_promotor_tarea_id, $x_dia_zona, $currdate );
	}
}
}// fin del dia de zonas
	

function calculaSemanas($conn, $x_fecha_tarea, $x_dia_zona, $x_promotor_id, $x_caso_id, $x_tarea_id){
	// esta funcion sirve para saber con que fecha se guardara la tarea del CRM en la lista de las tareas que se le mostraran diariamente a los promotores.
	 #1.- contar el numero de tareas de la zona que pertenecen al promotor
	 #2.- deben de ser con fecha mayor a la fecha_tarea
	 #3.- al numero resultante lo deividimos entre 25 para saber cuantas semanas ya estan ocupadas
	 #4.- sacamos el modulo de la division ppara saber si la ultima semana ya tiene las 25 tareas completas.. o aun tiene espacio para mas.
	 #5.- regresamos la fecha con la que se debe guardar la tarea en la lista diaria
	 $x_dias_para_agregar = 0;
	 
	 $sqlCuentaTareasDelDia = "SELECT COUNT(*) AS total_tareas FROM  tarea_diaria_promotor WHERE fecha_lista = \"$x_fecha_tarea\" AND promotor_id = $x_promotor_id ";
	 $rsCuentaTareasDelDia = phpmkr_query($sqlCuentaTareasDelDia,$conn) or die ("Error al seleccionar las tareas del dia para el promotor A".phpmkr_error()."sql: cs1".$sqlCuentaTareasDelDia);
	 $rowCuentaTareasDelDia = phpmkr_fetch_array($rsCuentaTareasDelDia);
	 mysql_free_result($rsCuentaTareasDelDia);
	 $x_tareas_asignadas_de_hoy = $rowCuentaTareasDelDia["total_tareas"];
	 echo "tareas asignadas para hoy".$sqlCuentaTareasDelDia."<br>";
	 echo "TAREAS ASIGNADAS HOY  PARA ".$x_promotor_id." SON ".$x_tareas_asignadas_de_hoy;
	 
	 if($x_tareas_asignadas_de_hoy < 25){
		 // insertamos la tarea en la lista
		  $x_dias_para_agregar = 0;
		 
		 // else ----> se hace el calculo
		 }else{
			
		$sqlCuentaTareasZona = "SELECT COUNT(*) AS total_tareas_asignadas FROM  tarea_diaria_promotor WHERE fecha_lista > \"$x_fecha_tarea\" AND promotor_id = $x_promotor_id and zona_id = $x_dia_zona ";
	 $rsCuentaTareasZona = phpmkr_query($sqlCuentaTareasZona,$conn) or die ("Error al seleccionar las tareas de la zona para el promotor".phpmkr_error()."sql: cs1".$sqlCuentaTareasZona);
	 $rowCuentaTareasZona = phpmkr_fetch_array($rsCuentaTareasZona);
	 mysql_free_result($rsCuentaTareasZona);
	 echo "<BR>CUENTA TAREAS ASIGNADAS".$sqlCuentaTareasZona."<BR>";
	 $x_tareas_asignadas_zona_promotor = $rowCuentaTareasZona["total_tareas_asignadas"];
	  echo "tareas asignadas por zona".$x_tareas_asignadas_zona_promotor ."<BR>";
			
			
			$x_semanas_llenas = intval($x_tareas_asignadas_zona_promotor/25);
			echo "semanas llenas ".$x_semanas_llenas."<br>";
			
			//$x_modulo_semamas_llenas = 25%$x_tareas_asignadas_zona_promotor;
			echo "modulo semanas llenas ".$x_modulo_semamas_llenas."<br>";
			//kuki
			if($x_semanas_llenas > 0){
				$x_semanas_llenas = $x_semanas_llenas +1; 
				}
			if($x_semanas_llenas == 0){
				 $x_semanas_llenas = 1;
				}
				
				$x_dias_para_nueva_fecha = $x_semanas_llenas * 7; // 7 = a una semana completa..hoy es lunes.. 7 es el sig lunes 
				 $x_dias_para_agregar = $x_dias_para_nueva_fecha;
				 
				#insertamos la tarea; en tareas resagadas
	$x_fe_resago = date("Y-m-d");
	$sqlResago = "INSERT INTO `financ13_esf`.`tarea_resagada` (`tarea_resagada_id`, `crm_tarea_id`, `fecha_ingreso`) VALUES (NULL, $x_tarea_id, \"$x_fe_resago\")";
	$rsResago = phpmkr_query($sqlResago,$conn)or die ("error al isertar en resago".phpmkr_error()."sql:".$sqlResago); 
			 
			 }	 
	//1.- agregamos lo dias indicados a la fecha de la lista de tares y agregamos la tarea a la lista dia promotor
	//2.- cambiamos la fecha de vencimiento de la tarea.
	echo "DPA".$x_dias_para_agregar."<br>";
	
	
	
	return $x_dias_para_agregar;
	
	}	
	
				
function calculaSemanasGestor($conn, $x_fecha_tarea, $x_dia_zona, $x_promotor_id, $x_caso_id, $x_tarea_id, $x_gestor_id){
	// esta funcion sirve para saber con que fecha se guardara la tarea del CRM en la lista de las tareas que se le mostraran diariamente a los gestores de cobranza.
	 #1.- contar el numero de tareas de la zona que pertenecen al gestor
	 #2.- deben de ser con fecha mayor a la fecha_tarea
	 #3.- al numero resultante lo deividimos entre 25 para saber cuantas semanas ya estan ocupadas
	 #4.- sacamos el modulo de la division ppara saber si la ultima semana ya tiene las 25 tareas completas.. o aun tiene espacio para mas.
	 #5.- regresamos la fecha con la que se debe guardar la tarea en la lista diaria
	 $x_dias_para_agregar = 0;
	 
	 $sqlCuentaTareasDelDia = "SELECT COUNT(*) AS total_tareas FROM  tarea_diaria_gestor WHERE fecha_lista = \"$x_fecha_tarea\" AND gestor_id = $x_gestor_id ";
	 $rsCuentaTareasDelDia = phpmkr_query($sqlCuentaTareasDelDia,$conn) or die ("Error al seleccionar las tareas del dia para el promotor jjj".phpmkr_error()."sql: cs1".$sqlCuentaTareasDelDia);
	 $rowCuentaTareasDelDia = phpmkr_fetch_array($rsCuentaTareasDelDia);
	 mysql_free_result($rsCuentaTareasDelDia);
	 $x_tareas_asignadas_de_hoy = $rowCuentaTareasDelDia["total_tareas"];
	 echo "TAREAS ASIGNADAS HOY  PARA ".$x_promotor_id." SON ".$x_tareas_asignadas_de_hoy;
	 
	 if($x_tareas_asignadas_de_hoy < 25){
		 // insertamos la tarea en la lista
		  $x_dias_para_agregar = 0;
		 
		 // else ----> se hace el calculo
		 }else{
			
		$sqlCuentaTareasZona = "SELECT COUNT(*) AS total_tareas_asignadas FROM  tarea_diaria_gestor WHERE fecha_lista > \"$x_fecha_tarea\" AND gestor_id = $x_gestor_id and zona_id = $x_dia_zona";
	 $rsCuentaTareasZona = phpmkr_query($sqlCuentaTareasZona,$conn) or die ("Error al seleccionar las tareas de la zona para el promotor".phpmkr_error()."sql: cs1".$sqlCuentaTareasZona);
	 $rowCuentaTareasZona = phpmkr_fetch_array($rsCuentaTareasZona);
	 mysql_free_result($rsCuentaTareasZona);
	 $x_tareas_asignadas_zona_promotor = $rowCuentaTareasZona["total_tareas_asignadas"];
			
			
			$x_semanas_llenas = intval($x_tareas_asignadas_zona_promotor/25);
			
			
			
			if($x_semanas_llenas > 0){
				$x_semanas_llenas = $x_semanas_llenas +1; 
				}
			if($x_semanas_llenas == 0){
				 $x_semanas_llenas = 1;
				}
			
			
				
				 $x_dias_para_nueva_fecha = $x_semanas_llenas * 7; // 7 = a una semana completa..hoy es lunes.. 7 es el sig lunes 
				 $x_dias_para_agregar = $x_dias_para_nueva_fecha;
			 
			 }	 
	//1.- agregamos lo dias indicados a la fecha de la lista de tares y agregamos la tarea a la lista dia promotor
	//2.- cambiamos la fecha de vencimiento de la tarea.
	
	return $x_dias_para_agregar;
	
	}	
	
		
function cuentaTareasVencidas($conn, $x_promotor_id, $x_zona_id, $x_fecha_criterio){
		
		#1.- contamos el numero de tareas vencidas del prmotor en el dia de la zona
		#2.- si el numero de tareas es menor de 25; llenasmo la lista con tareas evencida de las semanas pasadas
		#3.- se ordenan por fecha de vencimeito de la mas antigua a las mas actual
		#4.- seleccionamos las que faltan para las 25 tareas y actualizamos la fecha del listado
		#5.-actualizamos el contador de las veces que ha sido reasignada la tarea
		 $sqlCuentaTareasDelDia = "SELECT COUNT(*) AS total_tareas FROM  tarea_diaria_promotor WHERE fecha_lista = \"$x_fecha_criterio\" AND promotor_id = $x_promotor_id ";
		 $rsCuentaTareasDelDia = phpmkr_query($sqlCuentaTareasDelDia,$conn) or die ("Error al seleccionar las tareas del dia para el promotor5555".phpmkr_error()."sql: cs1".$sqlCuentaTareasDelDia);
		 $rowCuentaTareasDelDia = phpmkr_fetch_array($rsCuentaTareasDelDia);
		 $x_tareas_asignadas_de_hoy = $rowCuentaTareasDelDia["total_tareas"];
		 echo "tareas asignadas = ".$x_tareas_asignadas_de_hoy."<br>";
		 $x_contador_tareas_auxiliar = 0;
		 echo "contador_aux_afuera ".$x_contador_tareas_auxiliar."<br>";
	
		 if($x_tareas_asignadas_de_hoy < 26){
		 // faltan tareas es necesario, llenar la lista con 25 actividades
		 echo "TAREAS ASIGNADAS HOY SON MENOS DE 25";
		 $x_contador_zona = 1;
		 $x_zona_auxiliar = $x_zona_id;
		 $x_contador_tareas_auxiliar = $x_tareas_asignadas_de_hoy;
		  //$x_contador_tareas_auxiliar = 24;
		 
				 while($x_contador_tareas_auxiliar < 25){
					 // mientras no existan 25 tareas
					 
					 // buscamos tareas de la zona en curso
					 $sqlCuentaTareasZona = "SELECT *  FROM  tarea_diaria_promotor WHERE fecha_lista < \"$x_fecha_criterio\" AND promotor_id = $x_promotor_id and zona_id =  $x_zona_auxiliar  and status_tarea = 2";// tarea vencida
					 echo "SQL :".$sqlCuentaTareasZona."<br>";
					 $rsCuentaTareasZona = phpmkr_query($sqlCuentaTareasZona,$conn) or die ("Error al seleccionar las tareas de la zona para el promotor".phpmkr_error()."sql: cs1".$sqlCuentaTareasZona);
					while($rowCuentaTareasZona = phpmkr_fetch_array($rsCuentaTareasZona)){
						$x_tar_zona_pro_id = $rowCuentaTareasZona["tarea_diaria_promotor_id"];
						$x_tarea_id = $rowCuentaTareasZona["tarea_id"];
						$x_contador_tareas_auxiliar =  $x_contador_tareas_auxiliar + 1;
					echo "entra a contador <br>".$x_contador_tareas_auxiliar."<br>";
						
						
					 $x_tareas_asignadas_zona_promotor = $rowCuentaTareasZona["total_tareas_asignadas"];
						// cambiamos la fecha de la tarea para verla en  la lista
						
						#####################################################
						#################    updates   ######################
						#####################################################
						
						$sqlUpdateFechaTar = "UPDATE tarea_diaria_promotor SET fecha_lista = \"$x_fecha_criterio\", reingreso = (reingreso +1) WHERE tarea_diaria_promotor_id =$x_tar_zona_pro_id";
						//$rsUpdateFechaTar = phpmkr_query($sqlUpdateFechaTar,$conn)or die ("error al actualizar la taraa en a lista". phpmkr_error()."sql".$sqlUpdateFechaTar); echo "sql:".$sqlUpdateFechaTar."<br>";
						
						// le damos dos dias, de garcias a partir de la fecha en que se muestra en la lista.
						$x_dias_espera = 2;
						$temptime = strtotime($x_fecha_criterio);	
						$temptime = DateAdd('w',$x_dias_espera,$temptime);
						$fecha_venc = strftime('%Y-%m-%d',$temptime);			
						$x_dia = strftime('%A',$temptime);
						if($x_dia == "SUNDAY"){
							$temptime = strtotime($fecha_venc);
							$temptime = DateAdd('w',1,$temptime);
							$fecha_venc = strftime('%Y-%m-%d',$temptime);
						}
						$x_fecha_ejecuta_act = $fecha_venc;
								
						$sqlUpdateFechaTar = "UPDATE crm_tarea SET fecha_ejecuta = \"$x_fecha_ejecuta_act\" WHERE crm_tarea_id = $x_tarea_id";
						//$rsUpdateFechaTar = phpmkr_query($sqlUpdateFechaTar,$conn)or die ("error al actualizar la taraa en a lista". phpmkr_error()."sql".$sqlUpdateFechaTar);
						
						//insertar en listado de resagos.........
						$sqlResago = "INSERT INTO `tarea_resagada` (`tarea_resagada_id`, `crm_tarea_id`, `fecha_ingreso`) ";
						$sqlResago .= " VALUES (NULL, $x_tarea_id,\"$x_fecha_ejecuta_act\") ";
					//	$rsResago  = phpmkr_query($sqlResago,$conn)or die ("Error al insertar en RESAGO".phpmkr_error()."sql:".$sqlResago);
						// validar los casos id
						// validas verificar las tareas id
						// correrlo nuevamente para verificar todo
						// cambiar los filtro a 25 se quedaron el dos
						
						
						
						if ($x_contador_tareas_auxiliar ==  25){
							// rompemos el while del sql
							echo "<br> Se rompe el while <br> ";
							unset($rowCuentaTareasZona);
							break;
							}
					}
					 mysql_free_result($rsCuentaTareasZona);
					// fin while row
							if($x_zona_auxiliar == 6){
								$x_zona_auxiliar = 0;
								}else{
									$x_zona_auxiliar ++; // buscamos en la siguente zona
									}
						
						$x_contador_zona ++;
						if($x_contador_zona == 7){
							// forsamos la salida del cliclo
							// quiere decir que ya busco en todas las zona y no encontro vencido en ninguna de ellas... 
							// no hay tareas suficientes para llenar la lista pero, debemos de sali del while porque de lo contrario, 
							// el preoceso consumiria la memoria
							$x_contador_tareas_auxiliar = 25;
							break;
							// si ya paso por todas las zonas y ya no como llenar la lista.. manda una felicitacion :D
							
							
							}	
						}
					
					 
					 
					 
					 
	 
	 }// if tareas asignadas < 26
		
		
		
		
		}
		
	
function cuentaTareasVencidasGestor($conn, $x_promotor_id, $x_zona_id, $x_fecha_criterio){
		
		#1.- contamos el numero de tareas vencidas del prmotor en el dia de la zona
		#2.- si el numero de tareas es menor de 25; llenasmo la lista con tareas evencida de las semanas pasadas
		#3.- se ordenan por fecha de vencimeito de la mas antigua a las mas actual
		#4.- seleccionamos las que faltan para las 25 tareas y actualizamos la fecha del listado
		#5.-actualizamos el contador de las veces que ha sido reasignada la tarea
		 $sqlCuentaTareasDelDia = "SELECT COUNT(*) AS total_tareas FROM  tarea_diaria_gestor WHERE fecha_lista = \"$x_fecha_criterio\" AND gestor_id = $x_gestor_id ";
		 $rsCuentaTareasDelDia = phpmkr_query($sqlCuentaTareasDelDia,$conn) or die ("Error al seleccionar las tareas del dia para el GESTOR B".phpmkr_error()."sql: cs1".$sqlCuentaTareasDelDia);
		 $rowCuentaTareasDelDia = phpmkr_fetch_array($rsCuentaTareasDelDia);
		 $x_tareas_asignadas_de_hoy = $rowCuentaTareasDelDia["total_tareas"];
	
		 if($x_tareas_asignadas_de_hoy < 26){
		 // faltan tareas es necesario, llenar la lista con 25 actividades
		 echo "TAREAS ASIGNADAS HOY SON MENOS DE 25";
		 $x_contador_zona = 1;
		 $x_zona_auxiliar = $x_zona_id;
		 $x_contador_tareas_auxiliar = $x_tareas_asignadas_de_hoy;
		  //$x_contador_tareas_auxiliar = 24;
		 
				 while($x_contador_tareas_auxiliar < 26){
					 // mientras no existan 25 tareas
					 
					 // buscamos tareas de la zona en curso
					 $sqlCuentaTareasZona = "SELECT *  FROM  tarea_diaria_gestor WHERE fecha_lista < \"$x_fecha_criterio\" AND gestor_id = $x_gestor_id and zona_id =  $x_zona_auxiliar  and status_tarea = 2";// tarea vencida
					 echo "SQL :".$sqlCuentaTareasZona."<br>";
					 $rsCuentaTareasZona = phpmkr_query($sqlCuentaTareasZona,$conn) or die ("Error al seleccionar las tareas de la zona para el asesor".phpmkr_error()."sql: cs1".$sqlCuentaTareasZona);
					while($rowCuentaTareasZona = phpmkr_fetch_array($rsCuentaTareasZona)){
						$x_tar_zona_pro_id = $rowCuentaTareasZona["tarea_diaria_promotor_id"];
						$x_tarea_id = $rowCuentaTareasZona["tarea_id"];
						$x_contador_tareas_auxiliar =  $x_contador_tareas_auxiliar + 1;
					echo "entra a contador<br>".$x_contador_tareas_auxiliar."<br>";
						
						
					 $x_tareas_asignadas_zona_promotor = $rowCuentaTareasZona["total_tareas_asignadas"];
						// cambiamos la fecha de la tarea para verla en  la lista
						
						#####################################################
						#################    updates   ######################
						#####################################################
						
						$sqlUpdateFechaTar = "UPDATE tarea_diaria_gestor SET fecha_lista = \"$x_fecha_criterio\", reingreso = (reingreso +1) WHERE tarea_diaria_gestor_id =$x_tar_zona_ges_id";
						$rsUpdateFechaTar = phpmkr_query($sqlUpdateFechaTar,$conn)or die ("error al actualizar la taraa en a lista". phpmkr_error()."sql".$sqlUpdateFechaTar); echo "sql:".$sqlUpdateFechaTar."<br>";
						
						// le damos dos dias, de garcias a partir de la fecha en que se muestra en la lista.
						$x_dias_espera = 2;
						$temptime = strtotime($x_fecha_criterio);	
						$temptime = DateAdd('w',$x_dias_espera,$temptime);
						$fecha_venc = strftime('%Y-%m-%d',$temptime);			
						$x_dia = strftime('%A',$temptime);
						if($x_dia == "SUNDAY"){
							$temptime = strtotime($fecha_venc);
							$temptime = DateAdd('w',1,$temptime);
							$fecha_venc = strftime('%Y-%m-%d',$temptime);
						}
						$x_fecha_ejecuta_act = $fecha_venc;
								
						$sqlUpdateFechaTar = "UPDATE crm_tarea SET fecha_ejecuta = \"$x_fecha_ejecuta_act\" WHERE crm_tarea_id = $x_tarea_id";
						$rsUpdateFechaTar = phpmkr_query($sqlUpdateFechaTar,$conn)or die ("error al actualizar la taraa en a lista". phpmkr_error()."sql".$sqlUpdateFechaTar);
						
						//insertar en listado de resagos.........
						$sqlResago = "INSERT INTO `tarea_resagada_gestor` (`tarea_resagada_id`, `crm_tarea_id`, `fecha_ingreso`) ";
						$sqlResago .= " VALUES (NULL, $x_tarea_id,\"$x_fecha_ejecuta_act\") ";
						$rsResago  = phpmkr_query($sqlResago,$conn)or die ("Error al insertar en RESAGO".phpmkr_error()."sql:".$sqlResago);
						// validar los casos id
						// validas verificar las tareas id
						// correrlo nuevamente para verificar todo
						// cambiar los filtro a 25 se quedaron el dos
						
						
						
						if ($x_contador_tareas_auxiliar ==  25){
							// rompemos el while del sql
							unset($rowCuentaTareasZona);
							break;
							}
					}
					 mysql_free_result($rsCuentaTareasZona);
					// fin while row
							if($x_zona_auxiliar == 6){
								$x_zona_auxiliar = 0;
								}else{
									$x_zona_auxiliar ++; // buscamos en la siguente zona
									}
						
						$x_contador_zona ++;
						if($x_contador_zona == 7){
							// forsamos la salida del cliclo
							// quiere decir que ya busco en todas las zona y no encontro vencido en ninguna de ellas... 
							// no hay tareas suficientes para llenar la lista pero, debemos de sali del while porque de lo contrario, 
							// el preoceso consumiria la memoria
							$x_contador_tareas_auxiliar = 25;
							break;
							// si ya paso por todas las zonas y ya no como llenar la lista.. manda una felicitacion :D
							
							
							}	
						}
					
					 
					 
					 
					 
	 
	 }// if tareas asignadas < 26
		
		
		
		
		}		
		
		
		?>