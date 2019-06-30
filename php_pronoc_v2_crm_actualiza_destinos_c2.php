<?php set_time_limit(0); ?>
<?php session_start(); ?>
<?php ob_start(); ?> 
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php include ("utilerias/datefunc.php") ?>


<?php
die();
// cambiar usuario 
$x_numero  = 1;
 $currdate = date("Y-m-d");

$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$sqlTarea = "SELECT crm_tarea.*, crm_caso.* FROM crm_tarea join crm_caso on crm_caso.crm_caso_id = crm_tarea.crm_caso_id where orden = 5 and crm_tarea_tipo_id = 5  and crm_tarea.crm_caso_id in (1588,1587) ";
$rsTarea = phpmkr_query($sqlTarea,$conn) or die ("Erro al seleccionar la carta 2".phpmkr_error()."sql:".$sqlTarea);
while($rowTarea = phpmkr_fetch_array($rsTarea)){
	
	$x_crm_caso = $rowTarea["crm_caso_id"];
	$x_crm_tarea_id = $rowTarea["crm_tarea_id"];
	$x_asunto = $rowTarea["asunto"];
	$x_destino = $rowTarea["destino"];
	$x_credito_id = $rowTarea["credito_id"];
	
	
	if ($x_destino == 6888){
		$x_destino = "Gestor Cobranza DF (Rodrigo)";
		}else if($x_destino ==6889){
			$x_destino = "Gestor Cobranza Colima";
			}
	
	echo $x_numero ."-- ".$x_asunto." de  ".$x_destino."  " ;
	$x_numero ++;
	
	
	// seleccionamos al promotor del credito
	$sqlP = "SELECT usuario_id, promotor.promotor_id, nombre_completo, solicitud.solicitud_id FROM promotor join solicitud on solicitud.promotor_id= promotor.promotor_id join credito on solicitud.solicitud_id = credito.solicitud_id where credito.credito_id =  $x_credito_id ";
	$rsP = phpmkr_query($sqlP,$conn)or die("Error pp".phpmkr_error()."sql: ".$sqlP);
	$rowP =phpmkr_fetch_array($rsP);	
	echo "----- nuevo destino ". $rowP["nombre_completo"]."...".$rowP["usuario_id"]. "<br>";
	$x_nuevo_destino = $rowP["usuario_id"];
	$x_solicitud_id = $rowP["solicitud_id"];
	$x_solicitud_id_c = $x_solicitud_id;
	$x_promotor_id_c = $rowP["promotor_id"];
	$x_caso_id_f = $x_crm_caso;
	
	//cambiamos el destino de la tarea 
	$sqlUpdate = "UPDATE crm_tarea  SET destino = $x_nuevo_destino WHERE crm_tarea_id =  $x_crm_tarea_id  ";
	$rsUpdate = phpmkr_query($sqlUpdate,$conn)or die("Error al actuzlixzar carta");
	
	echo $sqlUpdate."<br> sol_id ".$x_solicitud_id." ";
	
	// debo ingresar estas tareas en la lista de los promotores 
	
	##credamos las lista de los promotores con la tareas diarias.		
		## seleccionaos la soza de la solcitud		
		$sqlZonaId = "SELECT dia FROM  zona JOIN solicitud ON solicitud.zona_id = zona.zona_id WHERE solicitud.solicitud_id = $x_solicitud_id_c";
		$rsZonaId = phpmkr_query($sqlZonaId,$conn) or die ("Error al seleccionar el dia de la zona".phpmkr_error()."sql:".$sqlZonaId);
		$rowZonaId = phpmkr_fetch_array($rsZonaId);
		$x_dia_zona = $rowZonaId["dia"];
		
		$x_fecha_tarea_f = $currdate;
		$x_dia_zona_f = $x_dia_zona;
		$x_promotor_id_f = $x_promotor_id_c ;
		$x_caso_id_f = $x_crm_caso;
		$x_tarea_id_f = $x_crm_tarea_id ;
		
		
		#tenemos la fecha de hoy  --> hoy es miercoles 30 de enero
		# se debe buscar la fecha de la zona --> si la zona fuera 5.. la fecha se deberia cambiar a viernes 1 de febrero
		# o si la zona fuera  2 como las tareas de la zona 2 ya fueron asignadas.. se debe buscar la fecha de la zona dos de la sig semana
		
		$sqlDiaSemana = "SELECT DAYOFWEEK('$x_fecha_tarea_f') AS  dia";
		$rsDiaSemana = phpmkr_query($sqlDiaSemana,$conn) or die ("Error al seleccionar el dia de la semana".phpmkr_error()."sql:".$sqlDiaSemana);
		$rowDiaSemana = phpmkr_fetch_array($rsDiaSemana);
		$x_dia_de_semana_en_fecha = $rowDiaSemana["dia"];
		#echo "fecha day of week".$x_dia_de_semana_en_fecha ."<br>";
		
		if($x_dia_de_semana_en_fecha != $x_dia_zona_f ){
			// el dia de la zona y el dia de la fecha no es el mismo se debe incrementar la fecha hasta llegar al mismo dia
			
			#echo "LOS DIAS SON DIFERENTES<BR>";
		//	$x_dia_de_semana_en_fecha = 1;
		//	$x_dia_zona_f= 5;
		//	$x_fecha_tarea_f = "2013-01-29";
			if($x_dia_de_semana_en_fecha < $x_dia_zona_f){
				// la fecha es mayor dia_en _fecha = 2; dia_zona = 5
				$x_dias_faltantes = $x_dia_zona_f - $x_dia_de_semana_en_fecha;	
				//hacemos el incremento en la fecha		
				//Fecha de tarea para la lista
				
				#echo "fecha dia------".$x_fecha_tarea_f."<br>";
				$temptime = strtotime($x_fecha_tarea_f);	
				$temptime = DateAdd('w',$x_dias_faltantes,$temptime);
				$fecha_nueva = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				$x_fecha_tarea_f = $fecha_nueva;	
				#echo "nueva fecha...ESTA semana<br> dias faltantes".$x_dias_faltantes."<br>";			
				}else{
					// el dia dela fecha es mayor al dia de la zona...las tareas asignadas para esa zona ya pasaron; porque ya paso el dia de esa zona
					// se debe asigna la tarea para la semana sig el la fecha de la zona.
					$x_dias_faltantes = (7- $x_dia_de_semana_en_fecha)+ $x_dia_zona_f;
					//hacemos el incremento en la fecha		
					//Fecha de tarea para la lista
					$temptime = strtotime($x_fecha_tarea_f);	
					$temptime = DateAdd('w',$x_dias_faltantes,$temptime);
					$fecha_nueva = strftime('%Y-%m-%d',$temptime);			
					$x_dia = strftime('%A',$temptime);
					$x_fecha_tarea_f = $fecha_nueva;	
					#echo "nueva fecha...sigueinte semana<br> dias faltantes".$x_dias_faltantes."<br>";
					#echo "DIA DE LA SEMANA EN FECHA".$x_dia_de_semana_en_fecha."<br>";
					#echo "dia zona".$x_dia_zona_f;					
					}
			}
		
		$x_dias_agregados = calculaSemanas($conn, $x_fecha_tarea_f, $x_dia_zona_f, $x_promotor_id_f, $x_caso_id_f, $x_tarea_id_f);
		#echo "dias agragados".$x_dias_agregados." ";
		
		// se gragan los dias que faltan si es que es mayor de 0
		if($x_dias_agregados > 0){
					$temptime = strtotime($x_fecha_tarea_f);	
					$temptime = DateAdd('w',$x_dias_agregados,$temptime);
					$fecha_nueva = strftime('%Y-%m-%d',$temptime);			
					$x_fecha_tarea_f = $fecha_nueva;
				// se hizo el incremento en la fecha				
				//se actualiza la tarea con la fecha nueva
				$x_fecha_ejecuta_act = $x_fecha_tarea_f;
				
				$temptime = strtotime($x_fecha_ejecuta_act);	
				$temptime = DateAdd('w',$x_dias_espera,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if($x_dia == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$x_fecha_ejecuta_act = $fecha_venc;
				
				$sqlUpdateFecha = "UPDATE crm_tarea SET  fecha_ejecuta = \"$x_fecha_ejecuta_act\" WHERE crm_tarea_id = $x_tarea_id_f ";
				$rsUpdateFecha = phpmkr_query($sqlUpdateFecha,$conn) or die ("Error al actualiza la fecha de latarea despues del calculo semana".phpmkr_error()."sql;".$sqlUpdateFecha);
				echo "UPDATAE TREA".$sqlUpdateFecha."<br>";
				
		}else{
			$x_fecha_ejecuta_act  = $x_fecha_tarea_f;
			$temptime = strtotime($x_fecha_ejecuta_act);	
				$temptime = DateAdd('w',$x_dias_espera,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				if($x_dia == "SUNDAY"){
					$temptime = strtotime($fecha_venc);
					$temptime = DateAdd('w',1,$temptime);
					$fecha_venc = strftime('%Y-%m-%d',$temptime);
				}
				$x_fecha_ejecuta_act = $fecha_venc;
				
				$sqlUpdateFecha = "UPDATE crm_tarea SET  fecha_ejecuta = \"$x_fecha_ejecuta_act\" WHERE crm_tarea_id = $x_tarea_id_f ";
				$rsUpdateFecha = phpmkr_query($sqlUpdateFecha,$conn) or die ("Error al actualiza la fecha de latarea despues del calculo semana".phpmkr_error()."sql;".$sqlUpdateFecha);
			
			
			
			}
		#echo "FECHA EJECUTA".$x_fecha_ejecuta_act;
		#####################################################################################################################
		##################################### TAREAS DIARIAS PROMOTOR #######################################################
		#####################################################################################################################
		
		// primero verifamos que la tarea aun no este en la lista, es decir, que se trate de la primera vez que entra al cliclo para este credito.
		$sqlBuscaatreaAsignada = "SELECT COUNT(*) AS atreas_asignadas FROM `tarea_diaria_promotor` WHERE fecha_ingreso =  \"$currdate\" AND promotor_id = $x_promotor_id_f ";
		$sqlBuscaatreaAsignada .= " AND `caso_id` = $x_caso_id_f";
		$rsBuscatareaAsignada = phpmkr_query($sqlBuscaatreaAsignada,$conn) or die("Erro al buscar atrea".phpmkr_error()."sql:".$sqlBuscaatreaAsignada);
		echo "BUSCA TAREAS".$sqlBuscaatreaAsignada."<BR>";
		$rowBuscaTareaAsignada = phpmkr_fetch_array($rsBuscatareaAsignada);
		$x_tareas_asignadas_del_caso = $rowBuscaTareaAsignada["atreas_asignadas"];
		echo "TAREAS ASIGNADAS ".$x_tareas_asignadas_del_caso."<br>";
		//se inserta la tarea en la lista de las actividades diarias del promotor
		if ($x_tareas_asignadas_del_caso < 1){
		$sqlInsertListaTarea = "INSERT INTO `tarea_diaria_promotor`";
		$sqlInsertListaTarea .= " (`tarea_diaria_promotor_id`, `promotor_id`, `zona_id`, `dia_semana`, `fecha_ingreso`, `fecha_lista`, `caso_id`, ";
		$sqlInsertListaTarea .= " `tarea_id`, `reingreso`, `fase`, `status_tarea`, `credito_id`) ";
		$sqlInsertListaTarea .= "VALUES (NULL, $x_promotor_id_f, $x_dia_zona_f , $x_dia_zona_f, \"$currdate\",\"$x_fecha_tarea_f\", $x_caso_id_f, $x_tarea_id_f, '0', '2', '1', $x_credito_id);";
		$rsInsertListaTarea = phpmkr_query($sqlInsertListaTarea,$conn)or die("Error al insertar en lista diaria tareas".phpmkr_error()."sql:".$sqlInsertListaTarea);	 
		}
	
	$sqlDelete = "DELETE FROM tarea_diaria_gestor WHERE caso_id = $x_crm_caso ";
	$rsDelete = phpmkr_query($sqlDelete,$conn)or die("Error al eliminar de la lista de tareas del gestor".phpmkr_error().$sqlDelete);
	
	}// whilw mayor
	
	
	
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

?>