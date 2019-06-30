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
//buscamos todas las promesas de pago vencidas y le generamos una nueva pp para avisarle que no cumplio la anteriror y puedan cambiar de tarea
$sqlPromesas= "SELECT crm_tarea.crm_tarea_id, crm_tarea.destino,crm_caso.crm_caso_id, crm_caso.credito_id FROM crm_tarea  join crm_caso on crm_caso.crm_caso_id = crm_tarea.crm_caso_id WHERE   `crm_tarea_tipo_id` = 5 and crm_tarea_status_id in (3)  group by crm_caso_id  ";
$rsPromesas = phpmkr_query($sqlPromesas,$conn)or die("Error al seleccionar las promesas de pago".phpmkr_error()."sql:".$sqlPromesas);
while($rowPromesas = phpmkr_fetch_array($rsPromesas)){
	$x_crm_tarea_id = $rowPromesas["crm_tarea_id"];
	$x_crm_caso_id = $rowPromesas["crm_caso_id"];
	$x_credito_id = $rowPromesas["credito_id"];
	$x_destino = $rowPromesas["destino"];
	echo "<br>tarea_id ".$x_crm_tarea_id."<br>";
	echo "<br>caso_id ".$x_crm_caso_id ."<br>";
	echo "destin".$x_destino."<br>";	
	
	// tengo que buscar el plaso de  
	// SELECCIONMOS LA ULTIMA TAREA
	$SQLuLTIMAPP = "SELECT crm_tarea.crm_tarea_id, crm_tarea.destino,crm_caso.crm_caso_id FROM crm_tarea  join crm_caso on crm_caso.crm_caso_id = crm_tarea.crm_caso_id WHERE  `crm_tarea_tipo_id` =5 and crm_tarea_status_id in (3)  and crm_tarea.crm_caso_id = $x_crm_caso_id ORDER BY crm_tarea_id DESC";
	$rsuLTIMAPP = phpmkr_query($SQLuLTIMAPP,$conn)or die("Error al seleccionar la ULTIMA".phpmkr_error()."sql:".$SQLuLTIMAPP);
	$rowuLTIMAPP = phpmkr_fetch_array($rsuLTIMAPP);	
	$x_crm_tarea_id_ULTIMA = $rowuLTIMAPP["crm_tarea_id"];
	
	echo "ultima tarea es ".$x_crm_tarea_id_ULTIMA." ";
	$sqlFecha = "SELECT promesa_pago FROM crm_tarea_cv  where crm_tarea_id = $x_crm_tarea_id_ULTIMA ";
	$rsfecha = phpmkr_query($sqlFecha,$conn)or die("Error al seleccionar la promesa de pago en fecha".phpmkr_error()."sql:".$sqlFecha);
	$rowFP = phpmkr_fetch_array($rsfecha);
	$x_fecha_promesa = $rowFP["promesa_pago"];
	echo  "x_fecha_promesa".$x_fecha_promesa."<br>";
	echo $sqlFecha;
	
	//buscamos el monto de lapp
	$sqlMontoPP = "SELECT monto FROM crm_tarea_cv_monto WHERE crm_tarea_cv_monto.crm_tarea_id =  $x_crm_tarea_id_ULTIMA  ORDER BY crm_tarea_cv_monto_id DESC ";
	$rsMonto = phpmkr_query($sqlMontoPP,$conn) or die("Error al seleccionar el mosnto de la promesa de pago".phpmkr_error()."sql;".$sqlMontoPP);
	$rowMonto = phpmkr_fetch_array($rsMonto);
	$x_monto_de_promesa_de_pago = $rowMonto["monto"]+0;
	echo "monto de la pp".$x_monto_de_promesa_de_pago."<br>";
	
	echo "monto de la promesa de pago =".$x_monto_de_promesa_de_pago."<br>";
	if(empty($x_monto_de_promesa_de_pago)){
		$x_monto_de_promesa_de_pago = 100;
		}
	
	//verificamos que ese dia que se creo la promesa de pago no se haya generado un carta
	
	$sqlTareaFP = "SELECT COUNT(*) AS cartas FROM crm_tarea WHERE fecha_registro = \"$x_fecha_promesa\" and crm_caso_id = $x_crm_caso_id AND  ((`asunto` LIKE  'Carta%') or(`asunto` LIKE  'Demanda%') )";
	$rsTareaFP = phpmkr_query($sqlTareaFP,$conn)or die ("Error al seleccionar las cartas".phpmkr_error()."sql:".$sqlTareaFP);
	$rowTareaFP = phpmkr_fetch_array($rsTareaFP);
	$x_cartas_dia = $rowTareaFP["cartas"];
	
	$x_today = date("Y-m-d",time()-(2*24*60*60));
	echo "<br>fecha de promesa de pago".$x_fecha_promesa."<br>";
	echo $sqlTareaFP;
	if($x_cartas_dia < 1){
		echo "cartas del dia menos de 1<br>";
	if($x_fecha_promesa < $x_today ){		
		// contamos si tiene una promesa de pago abierta
		// SELECCIONMOS LA ULTIMA TAREA
	$SQLuLTIMAPP = "SELECT COUNT(*) AS abiertas  FROM crm_tarea  join crm_caso on crm_caso.crm_caso_id = crm_tarea.crm_caso_id WHERE  `crm_tarea_tipo_id` =5 and crm_tarea_status_id in (1,2)  and crm_tarea.crm_caso_id = $x_crm_caso_id ORDER BY crm_tarea_id DESC";
	$rsuLTIMAPP = phpmkr_query($SQLuLTIMAPP,$conn)or die("Error al seleccionar la ULTIMA".phpmkr_error()."sql:".$SQLuLTIMAPP);
	$rowuLTIMAPP = phpmkr_fetch_array($rsuLTIMAPP);	
	$x_crm_tarea_id_ABIERTA = $rowuLTIMAPP["abiertas"];	
		
		echo "TAREAS ABIERTAS DE PP DE PAGO".$x_crm_tarea_id_ABIERTA." ";
		if($x_crm_tarea_id_ABIERTA < 1){			
		// buscamos lo pgaos que presento el cliente desde la fecha de la promesa de pago hasta hoy			
		// si la fecha de promesa de pago esta vencida.. se genera una nueva promesa de pago para avisar lo que paso
		$x_fecha_actual = date("Y-m-d");
		$sqlPagos = "SELECT SUM(recibo.importe) as TOTAL_PAGO FROM  recibo join recibo_vencimiento on recibo_vencimiento.recibo_id = recibo.recibo_id join vencimiento on vencimiento.vencimiento_id = recibo_vencimiento.vencimiento_id  WHERE recibo.fecha_pago >= \"$x_fecha_promesa \" AND recibo.fecha_pago <= \"$x_fecha_actual\"  AND  vencimiento.credito_id = $x_credito_id ";		
		$rsPagos = phpmkr_query($sqlPagos,$conn) or die ("Error al seleccionar los pagos del credito".phpmkr_error()."sql:".$sqlPagos);
		$rowPagos = phpmkr_fetch_array($rsPagos);
		$x_monto_pagado = $rowPagos["TOTAL_PAGO"]+0;
		
		echo "busca pagos".$sqlPagos."<br>monto pagado".$x_monto_pagado."<br>";
			// reenvia la tarea
			// tenemos dos casos
			#1.-Cumple la promesa completa
			#2.-Cumple pero impleto
			$x_genera_tarea = 0;
			$x_asunto_extra = "";
			
		if($x_monto_pagado < 1){
		// no pago se cambia el status de la tarea a No cumplio la PP
		//Y se genera una nueva PP
			$sqlUpdateStatus = "UPDATE crm_credito_status SET  crm_cartera_status_id = 4 WHERE 	credito_id = $x_credito_id ";
			$rsUpdateStatus = phpmkr_query($sqlUpdateStatus,$conn) or die("Error al seleccionar el status".phpmkr_error()."sql:".$sqlUpdateStatus); 
			$x_genera_tarea = 1;
			$x_asunto_extra = "No cumplio con PP";					
			} else if(($x_monto_pagado == $x_monto_de_promesa_de_pago) || ( $x_monto_pagado > $x_monto_de_promesa_de_pago )){
			// cumplio la promesa e pago
			// se valida si aun debe se genera nueva pp
			//	se cambia el estatus a cumplio PP; requiere nueva pp
			$sqlUpdateStatus = "UPDATE crm_credito_status SET  crm_cartera_status_id = 6 WHERE 	credito_id = $x_credito_id ";
			$rsUpdateStatus = phpmkr_query($sqlUpdateStatus,$conn) or die("Error al seleccionar el status".phpmkr_error()."sql:".$sqlUpdateStatus);
			$x_genera_tarea = 1;
			$x_asunto_extra = "Cumplio con PP; pero aun no esta liquidado";				
				}else if($x_monto_pagado < 	$x_monto_de_promesa_de_pago){
				// se cambia el status a cumplio parcial la pp  se genera nueva pp
				$sqlUpdateStatus = "UPDATE crm_credito_status SET  crm_cartera_status_id = 5 WHERE 	credito_id = $x_credito_id ";
				$rsUpdateStatus = phpmkr_query($sqlUpdateStatus,$conn) or die("Error al seleccionar el status".phpmkr_error()."sql:".$sqlUpdateStatus);
				$x_genera_tarea = 1;
				$x_asunto_extra = "Cumplio parcialmente";				
					}
					echo "<br><br>".$x_asunto_extra."<br><br>";
		echo "<br>fecha pp menoa a antier";				
		$sSqlWrk = "
		SELECT *
		FROM 
			crm_playlist
		WHERE 
			crm_playlist.crm_caso_tipo_id = 3
			AND crm_playlist.orden = 1";
		
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
		$x_asunto = $x_asunto .$x_asunto_extra;
	
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
			credito.credito_id = $x_credito_id
		LIMIT 1
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_cliente_id = $datawrk["cliente_id"];
		@phpmkr_free_result($rswrk);
			
		$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 2, $x_destino, NULL,NULL, '$x_asunto','$x_descripcion',1)";
		
			$x_result = phpmkr_query($sSql, $conn) or die("Error al inserta".phpmkr_error()."sql:".$sSql);
			
			$sSqlWrk = "
			SELECT 
				comentario_int
			FROM 
				credito_comment
			WHERE 
				credito_id = ".$x_credito_id."
			LIMIT 1	";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			echo "INSERT TAREA:<BR>".$sSql."<BR> TERMINA CLICLO NO TIENE TAREA <BR><BR>";
			$x_comment_ant = $datawrk["comentario_int"];
			$x_comment_ant = str_replace("'","-", $x_comment_ant);
			$x_comment_ant = str_replace('"',"-", $x_comment_ant);
			
			$x_bitacora = "SISTEMA: " .$x_asunto_extra.", monto de la promesa ".$x_monto_de_promesa_de_pago." monto pagado ".$x_monto_pagado;
			@phpmkr_free_result($rswrk);	
			$x_fech_acomet = date("Y-m-d");
			$x_hora_comet = date("H:i:s");
			if(empty($x_comment_ant)){
				$sSql = "insert into credito_comment values(0, $x_credito_id, '$x_bitacora', NULL)";
			}else{
				$x_bitacora = $x_comment_ant . "\n\n------------------------------\n" . $x_bitacora;
				$sSql = "UPDATE credito_comment set comentario_int = '$x_bitacora' where credito_id = $x_credito_id";
			}
	
			phpmkr_query($sSql, $conn);
			
			
			
					
	}// SI NO HAY PP ABIERTAS
		
		}
		
		
		
	}//no hay cartas de ese dia
	
	}