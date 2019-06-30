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



$sqlCredito = "SELECT credito_id,vencimiento_num FROM vencimiento WHERE fecha_vencimiento = \"$currdate\" AND vencimiento_status_id = 1";
$rsCredito = phpmkr_query($sqlCredito, $conn) or die ("Error al seleccionar los vencimeintos pendietes".phpmkr_error()."sql:".$sqlCredito);
while($rowCredito = phpmkr_fetch_array($rsCredito)){
	$x_credito_id_a = $rowCredito["credito_id"];
	echo "credito id".$x_credito_id_a."<br>"; 
	$x_vencimiento_num = $rowCredito["vencimiento_num"];
		$sqlDC = "SELECT * FROM credito WHERE credito_id = $x_credito_id_a ";
		$rsDC = phpmkr_query($sqlDC,$conn) or die("Error al seleccionar los datos del credito".phpmkr_error()."sql:".$sqlDC);
		$rowDC = phpmkr_fetch_array($rsDC);
		$x_forma_pago_id = $rowDC["forma_pago_id"]; 
		
		$sqlCOUN = "SELECT COUNT(*) as penalizaciones FROM vencimiento WHERE credito_id = $x_credito_id_a and vencimiento_num > 2000 and vencimiento_num < 3000 ";
		$rsCount = phpmkr_query($sqlCOUN,$conn) or die ("error al selecionar los vencimeintos".phpmkr_error()."sql".$sqlCOUN);
		$rowCount = phpmkr_fetch_array($rsCount);
		$x_no_penalizaciones = $rowCount["penalizaciones"];
		
		$sqlCOUNP = "SELECT COUNT(*) as pendientes FROM vencimiento WHERE credito_id = $x_credito_id_a and vencimiento_num < 100 and vencimiento_status_id = 1 ";
		$rsCountP = phpmkr_query($sqlCOUNP,$conn) or die ("error al selecionar los vencimeintos".phpmkr_error()."sql".$sqlCOUNP);
		$rowCountP = phpmkr_fetch_array($rsCountP);
		$x_no_pendientes = $rowCount["penalizaciones"];
		echo "penalizaciones ".$x_no_penalizaciones."<br>";
		
		
		
		// la tarea sera asignada al 	RESPONSABLE DE SUCURSAL	
		# seleccionamos los datos del responsable de sucursal.
		$sqlSolId = "SELECT solicitud_id FROM credito WHERE credito_id = $x_credito_id_a";
		$rsSolId = phpmkr_query($sqlSolId,$conn) or die ("Error al seleccionar el id de la solicitud del credito".phpmkr_error()."sql:");
		$rowSolId = phpmkr_fetch_array($rsSolId);
		$x_solicitud_id_c = $rowSolId["solicitud_id"];
		echo "solcitud_id".$x_solicitud_id_c."<br>";
		
		// seleccionamos el promotor
		$sqlPromotor = "SELECT promotor_id FROM solicitud WHERE solicitud_id = $x_solicitud_id_c";
		$rsPromotor = phpmkr_query($sqlPromotor,$conn) or die ("Error al seleccionar el promotor del credito".phpmkr_error()."sql :".$sqlPromotor);
		$rowPromotor = phpmkr_fetch_array($rsPromotor);
		$x_promotor_id_c = $rowPromotor["promotor_id"];
		echo "promotor id ".$x_promotor_id_c."<br>";
		
		if($x_promotor_id_c > 0){
			// buscamos a que sucursal pertence el promotor
			$sqlSucursal = "SELECT sucursal_id FROM promotor WHERE promotor_id = $x_promotor_id_c";
			$rsSucursal = phpmkr_query($sqlSucursal,$conn) or die ("Error al seleccionar la sucursal". phpmkr_error()."Sql:".$sqlSucursal); 
			$rowSucuersal = phpmkr_fetch_array($rsSucursal);
			$x_sucursal_id_c = $rowSucuersal["sucursal_id"];
			 echo "sucursal_id ".$x_sucursal_id_c ."<br>";
			if($x_sucursal_id_c > 0){
				// si ya tenbemos la sucursal, buscamos el representante de essa sucursal
				$sqlResponsable = "SELECT usuario_id FROM responsable_sucursal WHERE sucursal_id = $x_sucursal_id_c ";
				$rsResponsable = phpmkr_query($sqlResponsable,$conn) or die ("error al seleccionar el usuario del responsable de suscursal".phpmkr_error()."sql:".$sqlResponsable);
				$rowResponsable = phpmkr_fetch_array($rsResponsable);
				$x_responsable_susursal_usuario_id = $rowResponsable["usuario_id"];	
				
				echo "usuario responsable suc.".$x_responsable_susursal_usuario_id."<br>";	
				
				}
			} 
		
		if($x_responsable_susursal_usuario_id < 1){
			// no hay resposable se la asigna la atrea al promotor
			$sqlResponsable = "SELECT usuario_id FROM promotor WHERE promotor_id = $x_promotor_id_c ";
				$rsResponsable = phpmkr_query($sqlResponsable,$conn) or die ("error al seleccionar el usuario del responsable de suscursal".phpmkr_error()."sql:".$sqlResponsable);
				$rowResponsable = phpmkr_fetch_array($rsResponsable);
				$x_responsable_susursal_usuario_id = $rowResponsable["usuario_id"];	
			echo "usuario promotor.".$x_responsable_susursal_usuario_id."<br>";	

			}
		
		// forma de pago = 
		
	//1= semanla
	//2= catorcela
	//3 = mensual
//	4= quincenal
$x_manda_tarea = 0;
		if($x_forma_pago_id == 1 and $x_vencimiento_num >= 18 ){
			if($x_no_penalizaciones > 1){
			$x_manda_tarea = 1;
			}
			}else if($x_forma_pago_id == 2 and $x_vencimiento_num >= 11){
				if($x_no_penalizaciones > 1){
			$x_manda_tarea = 1;
			}
				
				}else if($x_forma_pago_id == 4 and $x_vencimiento_num >= 11){
				if($x_no_penalizaciones > 1){
			$x_manda_tarea = 1;
			}
					}else if($x_forma_pago_id == 2 and $x_vencimiento_num >= 11){
					if($x_no_penalizaciones > 1){
			$x_manda_tarea = 1;
			}
						
						}
						
		echo "froma pago = ".$x_forma_pago_id."<br>";
		echo "pendientes de pago ".$x_no_pendientes."<br>";	
		echo "vencimiento actual ".$x_vencimiento_num."<br>";			
		if($x_manda_tarea == 1){
			 echo "entras  ";
		//validamos que aun no exista el caso
		$sSqlWrk = "    SELECT crm_caso_id as caso_abierto
						FROM 
						crm_caso
						WHERE 
						crm_caso.crm_caso_tipo_id = 3
						AND crm_caso.crm_caso_status_id = 1
						AND crm_caso.credito_id = $x_credito_id_a
						";
	
						$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
						$datawrk = phpmkr_fetch_array($rswrk);
						$x_caso_id = $datawrk["caso_abierto"];		
						@phpmkr_free_result($rswrk);

		// validamos que no esta la tarea registrada 				
		$sqlTrea = "Select COUNT(*) AS tareaas from crm_tarea WHERE crm_caso_id = $x_caso_id and  orden = 44	";
		$rsTarea = phpmkr_query($sqlTrea,$conn)or die ("erro al seleccionar las tareas de aviso penalizaciones".phpmkr_error()."sql;".$sqlTrea);
		$rowTrea = phpmkr_fetch_array($rsTarea);
		$x_tareas_existentes = $rowTrea["tareaas"];		
		if($x_tareas_existentes == 0){				
		$sSqlWrk = "
		SELECT *
		FROM 
			crm_playlist
		WHERE 
			crm_playlist.crm_caso_tipo_id = 3
			AND crm_playlist.orden = 44
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_crm_playlist_id = $datawrk["crm_playlist_id"];
		$x_prioridad_id = $datawrk["prioridad_id"];	
		$x_asunto = $datawrk["asunto"];	
		$x_descripcion = $datawrk["descripcion"];		
		$x_tarea_tipo_id = $datawrk["tarea_fuente"];		
		$x_orden = $datawrk["orden"];	
		$x_dias_espera = $datawrk["dias_espera"];		
		@phpmkr_free_result($rswrk);
	
	
		//Fecha Vencimiento
		$temptime = strtotime($currdate);	
		$temptime = DateAdd('w',$x_dias_espera,$temptime);
		$fecha_venc = strftime('%Y-%m-%d',$temptime);			
		$x_dia = strftime('%A',$temptime);
		if($x_dia == "SUNDAY"){
			$temptime = strtotime($fecha_venc);
			$temptime = DateAdd('w',1,$temptime);
			$fecha_venc = strftime('%Y-%m-%d',$temptime);
		}
		$temptime = strtotime($fecha_venc);	
	
		$x_origen = 1;
		$x_bitacora = "Cartera Vencida - (".FormatDateTime($currdate,7)." - $currtime)";
	
		$x_bitacora .= "\n";
		$x_bitacora .= "$x_asunto - $x_descripcion ";	

		$sSqlWrk = "
		SELECT cliente.cliente_id
		FROM 
			cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id join solicitud on solicitud.solicitud_id = solicitud_cliente.solicitud_id join credito on credito.solicitud_id = solicitud.solicitud_id
		WHERE 
			credito.credito_id = $x_credito_id_a
		LIMIT 1
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_cliente_id = $datawrk["cliente_id"];
		@phpmkr_free_result($rswrk);
	if(!empty($x_caso_id)){			
			$sSql = "INSERT INTO crm_tarea values (0,$x_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 2, $x_responsable_susursal_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";	
//	$rsU = phpmkr_query($sSql,$conn)or die("error al inserta tarea recordatorio de pago de penalizaciones".phpmkr_error()." sql".$sSql);

echo "sql: ".$sSql."<br>";
	}
			
		}// termina la tarea de aviso de penalizaciones;
			}// fin de manda tarea				
			
		
	
	}
    
    
    
    ?>