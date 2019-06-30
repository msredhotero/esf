<?php set_time_limit(0); ?>
<?php session_start(); ?>
<?php ob_start(); ?>
<?php
die();

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

//$x_dia = strtoupper($currentdate["weekday"]);

//$currdate = "2007-07-10";
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);


// ************************************************   MORATORIOS
$sSqlWrk = "SELECT vencimiento.*, credito.credito_status_id, credito.credito_tipo_id, credito.importe as importe_credito, credito.tasa_moratoria, credito.credito_num+0 as crednum, credito.tasa, forma_pago.valor as forma_pago_valor, credito.iva as iva_credito FROM vencimiento join credito 
on credito.credito_id = vencimiento.credito_id join forma_pago on forma_pago.forma_pago_id = credito.forma_pago_id 
where credito.credito_status_id in (1,4) and vencimiento.fecha_vencimiento < '$currdate' and vencimiento.vencimiento_status_id in (1,3) order by vencimiento.fecha_vencimiento";

//" and credito.credito_num = '283'";
$rswrkmain = phpmkr_query($sSqlWrk,$conn) or exit();
while($datawrkmain = phpmkr_fetch_array($rswrkmain)){
	$x_vencimiento_id = $datawrkmain["vencimiento_id"];
	$x_vencimiento_num = $datawrkmain["vencimiento_num"];	
	$x_fecha_vencimiento = $datawrkmain["fecha_vencimiento"];
	$x_importe = $datawrkmain["importe"];	
	$x_interes = $datawrkmain["interes"];		
	$x_interes_moratorio = $datawrkmain["interes_moratorio"];			
	$x_tasa_moratoria = $datawrkmain["tasa_moratoria"];	
	$x_credito_num = $datawrkmain["crednum"];		
	$x_credito_status_id = $datawrkmain["credito_status_id"];
	$x_importe_credito = $datawrkmain["importe_credito"];			
	$x_forma_pago_valor = $datawrkmain["forma_pago_valor"];				
	$x_tasa = $datawrkmain["tasa"];		
	$x_iva_credito = $datawrkmain["iva_credito"];				
	$x_iva = $datawrkmain["iva"];			
	$x_credito_id = $datawrkmain["credito_id"];	
	$x_credito_tipo_id = $datawrkmain["credito_tipo_id"];	
	if(empty($x_iva)){
		$x_iva = 0;
	}
	
		
	if(is_null($x_interes_moratorio)){
		$x_interes_moratorio = 0;
	}

	$x_dias_vencidos = datediff('d', $x_fecha_vencimiento, $currdate, false);	

	$x_dia = strtoupper(date('l',strtotime($x_fecha_vencimiento)));


	$x_dias_gracia = 2;
	switch ($x_dia)
	{
		case "MONDAY": // Get a record to display
			$x_dias_gracia = 2;
			break;
		case "TUESDAY": // Get a record to display
			$x_dias_gracia = 2;
			break;
		case "WEDNESDAY": // Get a record to display
			$x_dias_gracia = 4;
			break;
		case "THURSDAY": // Get a record to display
			$x_dias_gracia = 4;
			break;
		case "FRIDAY": // Get a record to display
			$x_dias_gracia = 3;
			break;
		case "SATURDAY": // Get a record to display
			$x_dias_gracia = 2;
			break;
		case "SUNDAY": // Get a record to display
			$x_dias_gracia = 2;
			break;		
	}

	
	if($x_dias_vencidos > $x_dias_gracia){
		$x_importe_mora = $x_tasa_moratoria * $x_dias_vencidos;
		if($x_iva_credito == 1){
//			$x_iva_mor = round($x_importe_mora * .15);
			$x_iva_mor = 0;			
		}else{
			$x_iva_mor = 0;
		}
		
//moratorios no majyores a 	2	

		if($x_credito_tipo_id == 2){			
			if($x_importe_mora > 250){
				$x_importe_mora = 250;
				}			
			}else{		
		if($x_importe_mora > (($x_interes + $x_iva) * 2)){
			$x_importe_mora = ($x_interes + $x_iva) * 2;
		}
			}
		$x_tot_venc = $x_importe + $x_interes + $x_importe_mora + $x_iva + $x_iva_mor;		

		if($x_credito_num > 809){
		$sSqlWrk = "update vencimiento set vencimiento_status_id = 3, interes_moratorio = $x_importe_mora, iva_mor = $x_iva_mor, total_venc = $x_tot_venc where vencimiento_id = $x_vencimiento_id ";	
		}else{
		$sSqlWrk = "update vencimiento set vencimiento_status_id = 3 where vencimiento_id = $x_vencimiento_id ";	
		}
		phpmkr_query($sSqlWrk,$conn);



//GENERA CASO CRM


// valida que no haya ya un caso abierto para este credito



	$sSqlWrk = "
	SELECT count(*) as caso_abierto
	FROM 
		crm_caso
	WHERE 
		crm_caso.crm_caso_tipo_id = 3
		AND crm_caso.crm_caso_status_id = 1
		AND crm_caso.credito_id = $x_credito_id
	";
	
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$datawrk = phpmkr_fetch_array($rswrk);
	$x_caso_abierto = $datawrk["caso_abierto"];		
	@phpmkr_free_result($rswrk);

	
	if($x_caso_abierto == 0){
		
		$sSqlWrk = "
		SELECT *
		FROM 
			crm_playlist
		WHERE 
			crm_playlist.crm_caso_tipo_id = 3
			AND crm_playlist.orden = 1
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
			credito.credito_id = $x_credito_id
		LIMIT 1
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_cliente_id = $datawrk["cliente_id"];
		@phpmkr_free_result($rswrk);


	
		$sSqlWrk = "
		SELECT usuario_id
		FROM 
			usuario
		WHERE 
			usuario.usuario_rol_id = 3
		LIMIT 1
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_usuario_id = $datawrk["usuario_id"];
		@phpmkr_free_result($rswrk);
		
	
		$sSql = "INSERT INTO crm_caso values (0,3,1,1,$x_cliente_id,'".$currdate."',$x_origen,$x_usuario_id,'$x_bitacora','".$currdate."',NULL,$x_credito_id)";
	
		$x_result = phpmkr_query($sSql, $conn);
		$x_crm_caso_id = mysql_insert_id();	


		if($x_crm_caso_id > 0){

			$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 2, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
		
			$x_result = phpmkr_query($sSql, $conn);
	
			$sSqlWrk = "
			SELECT 
				comentario_int
			FROM 
				credito_comment
			WHERE 
				credito_id = ".$x_credito_id."
			LIMIT 1
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_comment_ant = $datawrk["comentario_int"];
			@phpmkr_free_result($rswrk);
	
	
			if(empty($x_comment_ant)){
				$sSql = "insert into credito_comment values(0, $x_credito_id, '$x_bitacora', NULL)";
			}else{
				$x_bitacora = $x_comment_ant . "\n\n------------------------------\n" . $x_bitacora;
				$sSql = "UPDATE credito_comment set comentario_int = '$x_bitacora' where credito_id = $x_credito_id";
			}
	
			phpmkr_query($sSql, $conn);
		}
		
	
	}


	}else{
		$x_vence = " NOvence ";
	}
	
//	echo "VencNo: $x_vencimiento_id  FecVenc:  $x_fecha_vencimiento  DiasVen: $x_dias_vencidos   DiaVenc:  $x_dia  DiaGracia:  $x_dias_gracia  Reporta:  $x_vence  <br>";
	
	
}




// ************************************************   VENCE TAREAS
$sSqlWrk = "update crm_tarea set crm_tarea_status_id = 2 where fecha_ejecuta < '$currdate' and crm_tarea_status_id = 1 ";
phpmkr_query($sSqlWrk,$conn);




// ************************************************   CIERRA TAREAS DE CREDITOS SIN PAGOS VENCIDOS


$sSqlWrk = "select * from crm_caso where crm_caso_status_id = 1 and crm_caso_tipo_id = 3 ";
$rswrkct = phpmkr_query($sSqlWrk,$conn) or exit();
while($datawrkct = phpmkr_fetch_array($rswrkct)){
	$x_crm_caso_id = $datawrkct["crm_caso_id"];	
	$x_credito_id = $datawrkct["credito_id"];		
	
	$sSqlWrkven = "select count(*) as vencidos from vencimiento where credito_id = $x_credito_id and vencimiento_status_id = 3 ";
	$rswrkven = phpmkr_query($sSqlWrkven,$conn) or exit();
	$datawrkven = phpmkr_fetch_array($rswrkven);
	$x_vencidos = $datawrkven["vencidos"];	
	@phpmkr_free_result($rswrkven);
	if(intval($x_vencidos) == 0){
		
		
	
	if(empty($x_crm_caso_id)){
		$x_crm_caso_id = -1;
	}	
	if(empty($x_credito_id)){
		$x_credito_id = -1;
	}	
		
	$sSqlWrkven = "insert into temp_crm values($x_crm_caso_id,$x_credito_id);";
	phpmkr_query($sSqlWrkven,$conn) or exit();
	
		
/*		
		$sSqlWrkven = "update crm_tarea set crm_tarea_status_id = 4 where crm_caso_id = $x_crm_caso_id  and crm_tarea_status_id in (1,2)";
		phpmkr_query($sSqlWrkven,$conn) or exit();

		//bitacora
		$x_bitacora = "Cartera Vencida - (".FormatDateTime($currdate,7)." - $currtime)";
		$x_bitacora .= "\n";
		$x_bitacora .= "Cierre de Caso - El credito ya no presenta pagos vencidos.";	

		$sSqlWrk = "SELECT comentario_int FROM credito_comment WHERE credito_id = ".$x_credito_id." LIMIT 1 ";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_comment_ant = $datawrk["comentario_int"];
		@phpmkr_free_result($rswrk);

		if(empty($x_comment_ant)){
			$sSql = "insert into credito_comment values(0, $x_credito_id, '$x_bitacora', NULL)";
		}else{
			$x_bitacora = $x_comment_ant . "\n\n------------------------------\n" . $x_bitacora;
			$sSql = "UPDATE credito_comment set comentario_int = '$x_bitacora' where credito_id = $x_credito_id";
		}

		$sSqlWrkven = "update crm_caso set crm_caso_status_id = 2 where crm_caso_id = $x_crm_caso_id ";
		phpmkr_query($sSqlWrkven,$conn) or exit();

*/


	}
}


// ************************************************   SEGUIMIENTO DE CREDITOS

//mitad del credito total venc 7 / 2

/* 
1 selecciona todos los venc del dia de hoy
2 cuenta total de vencimientos y compara (totvenc / 2) = num_venc o (totvenc / 2)-1 = num_venc o (totvenc / 2) + 1 = num_venc
3 valida que no tenga tareas de seguimiento a mitad de credito ya registrada en crm
crm_caso_tipo_id = 2
crm_tarea_tipo_id = 3
orden = 2
crm_playlist_id = 17
destino_id = 2

*/


// LA FECHA DEBE SER UN DIA HABIL DESPUES DE LA MITAD DEL CREDITO.

// la fecha se queda igual, pero solo aplica a los que no tiene pagos vencidos. 

// FECHA DE LA LLAMADA
$temptime = strtotime($currdate);	
$temptime = DateAdd('w',1,$temptime);
$fecha_llamada = strftime('%Y-%m-%d',$temptime);			
$x_dia = strftime('%A',$temptime);
if($x_dia == "SUNDAY"){
	$temptime = strtotime($fecha_llamada);
	$temptime = DateAdd('w',1,$temptime);
	$fecha_llamada = strftime('%Y-%m-%d',$temptime);
}
$temptime = strtotime($fecha_llamada);

echo  "dia actual".$currdate;
// fin fecha llamada



$sSqlWrk = "select * from vencimiento where fecha_vencimiento = '$currdate'";
$rswrkct = phpmkr_query($sSqlWrk,$conn) or exit();
while($datawrkct = phpmkr_fetch_array($rswrkct)){
	$x_credito_id = $datawrkct["credito_id"];	
	$x_vencimiento_id = $datawrkct["vencimiento_id"];		
	$x_vencimiento_num = $datawrkct["vencimiento_num"];			

	$sSqlWrk = "SELECT max(vencimiento_num) as venc_num_tot FROM vencimiento WHERE credito_id = ".$x_credito_id;
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$datawrk = phpmkr_fetch_array($rswrk);
	$x_venc_num_tot = $datawrk["venc_num_tot"];
	$x_mitad_credito = intval($x_venc_num_tot / 2);
	@phpmkr_free_result($rswrk);

	if(($x_mitad_credito == $x_vencimiento_num) || (($x_mitad_credito - 1) == $x_vencimiento_num) || (($x_mitad_credito + 1) == $x_vencimiento_num)){
		
		echo "mitad del credito <br>";
	$sqlVencidos = "select count(*)as vencidos from vencimiento where credito_id = $x_credito_id and `vencimiento_status_id` = 3";
	$responseV = phpmkr_query($sqlVencidos,$conn) or die("error en vencidos");
	$rowVencidos = phpmkr_fetch_array($responseV);
	$x_pagos_vencidos = $rowVencidos["vencidos"];
	phpmkr_free_result($rowVencidos);
		
		
	// si tiene pagos vencidos no se hace la llamada
	if($x_pagos_vencidos == 0 ){
	// valida que no haya ya un caso abierto para este credito
	
		$sSqlWrk = "
		SELECT count(*) as caso_abierto
		FROM 
			crm_caso join crm_tarea
			on crm_tarea.crm_caso_id = crm_caso.crm_caso_id
		WHERE 
			crm_caso.crm_caso_tipo_id = 2
			AND crm_caso.crm_caso_status_id = 1
			AND crm_caso.credito_id = $x_credito_id
			AND crm_tarea.orden = 2
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_caso_abierto = $datawrk["caso_abierto"];		
		@phpmkr_free_result($rswrk);
		
		if($x_caso_abierto == 0){
			
			$sSqlWrk = "
			SELECT *
			FROM 
				crm_playlist
			WHERE 
				crm_playlist.crm_caso_tipo_id = 2
				AND crm_playlist.orden = 2
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
			$x_destino_id = $datawrk["destino_id"];					
			@phpmkr_free_result($rswrk);
		
/*		
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
*/		
		
		
			$x_origen = 1;
			$x_bitacora = "Seguimiento - (".FormatDateTime($currdate,7)." - $currtime)";
		
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
	
	
		
			$sSqlWrk = "
			SELECT usuario_id
			FROM 
				usuario
			WHERE 
				usuario.usuario_rol_id = $x_destino_id
			LIMIT 1
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_usuario_id = $datawrk["usuario_id"];
			@phpmkr_free_result($rswrk);
			
			// aqui se cambia la fecha actual, por la fecha de la llamada 
			$sSql = "INSERT INTO crm_caso values (0,2,1,1,$x_cliente_id,'".$currdate."',$x_origen,$x_usuario_id,'$x_bitacora','".$currdate."',NULL,$x_credito_id)";
		
			$x_result = phpmkr_query($sSql, $conn);
			$x_crm_caso_id = mysql_insert_id();	
	
	
			if($x_crm_caso_id > 0){
				// aqui tambien se cambia la fecha actual por la fecha de la llamada
				$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$currdate',NULL,NULL,NULL, 1, 1, 2, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
			
				$x_result = phpmkr_query($sSql, $conn);
		
				$sSqlWrk = "
				SELECT 
					comentario_int
				FROM 
					credito_comment
				WHERE 
					credito_id = ".$x_credito_id."
				LIMIT 1
				";
				
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_comment_ant = $datawrk["comentario_int"];
				@phpmkr_free_result($rswrk);
		
		
				if(empty($x_comment_ant)){
					$sSql = "insert into credito_comment values(0, $x_credito_id, '$x_bitacora', NULL)";
				}else{
					$x_bitacora = $x_comment_ant . "\n\n------------------------------\n" . $x_bitacora;
					$sSql = "UPDATE credito_comment set comentario_int = '$x_bitacora' where credito_id = $x_credito_id";
				}
		
				phpmkr_query($sSql, $conn);
			}
			
		
		}// caso abierto
	}// pagos vencidos
		
	}//mitad de credito
}



// penultimo pago

/* 
1 selecciona todos los venc del dia de hoy
2 cuenta total de vencimientos y compara (totvenc - 1) = num_venc
3 valida que no tenga tareas de seguimiento a final de credito ya registrada en crm
*/

// la fecha de venciemiente debe ser dos dias mas que la fecha actual.
//Fecha Vencimiento mañana

// cambiar fecha de vencimiento a pasado mañana(2 dias)
$temptime = strtotime($currdate);	
$temptime = DateAdd('w',2,$temptime);
$fecha_venc = strftime('%Y-%m-%d',$temptime);			
$x_dia = strftime('%A',$temptime);
if($x_dia == "SUNDAY"){
	$temptime = strtotime($fecha_venc);
	$temptime = DateAdd('w',1,$temptime);
	$fecha_venc = strftime('%Y-%m-%d',$temptime);
}
$temptime = strtotime($fecha_venc);
echo "dia actual".$currdate."<br>";
echo "fecha vencimiento".  ConvertDateToMysqlFormat($fecha_venc);

$sSqlWrk = "select * from vencimiento where fecha_vencimiento = '$fecha_venc' and vencimiento_status_id = 1";
$rswrkct = phpmkr_query($sSqlWrk,$conn) or exit();
while($datawrkct = phpmkr_fetch_array($rswrkct)){
	$x_credito_id = $datawrkct["credito_id"];	
	$x_vencimiento_id = $datawrkct["vencimiento_id"];		
	$x_vencimiento_num = intval($datawrkct["vencimiento_num"]);	
	
	


	$sSqlWrk = "SELECT max(vencimiento_num) as venc_num_tot FROM vencimiento WHERE credito_id = ".$x_credito_id;
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$datawrk = phpmkr_fetch_array($rswrk);
	$x_venc_num_tot = $datawrk["venc_num_tot"];
	$x_penultimo_credito = intval($x_venc_num_tot - 1);
	@phpmkr_free_result($rswrk);

	if($x_venc_num_tot == $x_vencimiento_num){
		
	$sqlVencidos = "select count(*)as vencidos from vencimiento where credito_id = $x_credito_id and `vencimiento_status_id` = 3";
	$responseV = phpmkr_query($sqlVencidos,$conn) or die("error en vencidos");
	$rowVencidos = phpmkr_fetch_array($responseV);
	$x_pagos_vencidos = $rowVencidos["vencidos"];
	
	if($x_pagos_vencidos == 0 ){			
								
	// valida que no haya ya un caso abierto para este credito
		$sSqlWrk = "
		SELECT count(*) as caso_abierto
		FROM 
			crm_caso join crm_tarea
			on crm_tarea.crm_caso_id = crm_caso.crm_caso_id
		WHERE 
			crm_caso.crm_caso_tipo_id = 2
			AND crm_caso.crm_caso_status_id = 1
			AND crm_caso.credito_id = $x_credito_id
			AND crm_tarea.orden = 3
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_caso_abierto = $datawrk["caso_abierto"];		
		@phpmkr_free_result($rswrk);
		
		if($x_caso_abierto == 0){
			
			$sSqlWrk = "
			SELECT *
			FROM 
				crm_playlist
			WHERE 
				crm_playlist.crm_caso_tipo_id = 2
				AND crm_playlist.orden = 3
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_crm_playlist_id = $datawrk["crm_playlist_id"];
			$x_prioridad_id = $datawrk["prioridad_id"];
			// RENOVACION PUNTUAL
			$x_asunto = $datawrk["asunto"];	
			$x_asunto =$x_asunto ."por puntualidad";
			$x_descripcion = $datawrk["descripcion"];		
			$x_tarea_tipo_id = $datawrk["tarea_fuente"];		
			$x_orden = $datawrk["orden"];	
			$x_dias_espera = $datawrk["dias_espera"];		
			$x_destino_id = $datawrk["destino_id"];					
			@phpmkr_free_result($rswrk);
		
/*		
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
*/		
		
		
			$x_origen = 1;
			$x_bitacora = "Seguimiento - (".FormatDateTime($currdate,7)." - $currtime)";
		
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
	
	
		
			$sSqlWrk = "
			SELECT usuario_id
			FROM 
				usuario
			WHERE 
				usuario.usuario_rol_id = $x_destino_id
			LIMIT 1
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_usuario_id = $datawrk["usuario_id"];
			@phpmkr_free_result($rswrk);
			
		
			$sSql = "INSERT INTO crm_caso values (0,2,1,1,$x_cliente_id,'".$currdate."',$x_origen,$x_usuario_id,'$x_bitacora','".$currdate."',NULL,$x_credito_id)";
		
			$x_result = phpmkr_query($sSql, $conn);
			$x_crm_caso_id = mysql_insert_id();	
	

	
			if($x_crm_caso_id > 0){
	
				$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$currdate',NULL,NULL,NULL, 1, 1, 2, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
			
				$x_result = phpmkr_query($sSql, $conn);
			
				$sSqlWrk = "
				SELECT 
					comentario_int
				FROM 
					credito_comment
				WHERE 
					credito_id = ".$x_credito_id."
				LIMIT 1
				";
				
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_comment_ant = $datawrk["comentario_int"];
				@phpmkr_free_result($rswrk);
		
		
				if(empty($x_comment_ant)){
					$sSql = "insert into credito_comment values(0, $x_credito_id, '$x_bitacora', NULL)";
				}else{
					$x_bitacora = $x_comment_ant . "\n\n------------------------------\n" . $x_bitacora;
					$sSql = "UPDATE credito_comment set comentario_int = '$x_bitacora' where credito_id = $x_credito_id";
				}
		
				phpmkr_query($sSql, $conn);
			}
			
		
		}

	}// fin pagos vencidos


	}// numero total de vencimeintos
}



/******************************************************************A 1 DIA **************************************************************************************
*****************************************************************************************************************************************************************
// penultimo pago

/* 
1 selecciona todos los venc del dia de hoy
2 cuenta total de vencimientos y compara (totvenc - 1) = num_venc
3 valida que no tenga tareas de seguimiento a final de credito ya registrada en crm
*/

// la fecha de venciemiente debe ser dos dias mas que la fecha actual.
//Fecha Vencimiento mañana

// cambiar fecha de vencimiento a pasado mañana(2 dias)
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
echo "dia actual".$currdate."<br>";
echo "fecha vencimiento".  ConvertDateToMysqlFormat($fecha_venc);

$sSqlWrk = "select * from vencimiento where fecha_vencimiento = '$fecha_venc' and vencimiento_status_id = 1";
$rswrkct = phpmkr_query($sSqlWrk,$conn) or exit();
while($datawrkct = phpmkr_fetch_array($rswrkct)){
	$x_credito_id = $datawrkct["credito_id"];	
	$x_vencimiento_id = $datawrkct["vencimiento_id"];		
	$x_vencimiento_num = intval($datawrkct["vencimiento_num"]);	
	
	


	$sSqlWrk = "SELECT max(vencimiento_num) as venc_num_tot FROM vencimiento WHERE credito_id = ".$x_credito_id;
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$datawrk = phpmkr_fetch_array($rswrk);
	$x_venc_num_tot = $datawrk["venc_num_tot"];
	$x_penultimo_credito = intval($x_venc_num_tot - 1);
	@phpmkr_free_result($rswrk);

	if($x_venc_num_tot == $x_vencimiento_num){
		
	$sqlVencidos = "select count(*)as vencidos from vencimiento where credito_id = $x_credito_id and `vencimiento_status_id` = 3";
	$responseV = phpmkr_query($sqlVencidos,$conn) or die("error en vencidos");
	$rowVencidos = phpmkr_fetch_array($responseV);
	$x_pagos_vencidos = $rowVencidos["vencidos"];
	
	if($x_pagos_vencidos == 0 ){			
								
	// valida que no haya ya un caso abierto para este credito
		$sSqlWrk = "
		SELECT count(*) as caso_abierto
		FROM 
			crm_caso join crm_tarea
			on crm_tarea.crm_caso_id = crm_caso.crm_caso_id
		WHERE 
			crm_caso.crm_caso_tipo_id = 2
			AND crm_caso.crm_caso_status_id = 1
			AND crm_caso.credito_id = $x_credito_id
			AND crm_tarea.orden = 3
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_caso_abierto = $datawrk["caso_abierto"];		
		@phpmkr_free_result($rswrk);
		
		if($x_caso_abierto == 0){
			
			$sSqlWrk = "
			SELECT *
			FROM 
				crm_playlist
			WHERE 
				crm_playlist.crm_caso_tipo_id = 2
				AND crm_playlist.orden = 3
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_crm_playlist_id = $datawrk["crm_playlist_id"];
			$x_prioridad_id = $datawrk["prioridad_id"];	
			//renovacion por puntualidad
			$x_asunto = $datawrk["asunto"];
			$x_asunto =$x_asunto ."por puntualidad";
			$x_descripcion = $datawrk["descripcion"];		
			$x_tarea_tipo_id = $datawrk["tarea_fuente"];		
			$x_orden = $datawrk["orden"];	
			$x_dias_espera = $datawrk["dias_espera"];		
			$x_destino_id = $datawrk["destino_id"];					
			@phpmkr_free_result($rswrk);
		
/*		
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
*/		
		
		
			$x_origen = 1;
			$x_bitacora = "Seguimiento - (".FormatDateTime($currdate,7)." - $currtime)";
		
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
	
	
		
			$sSqlWrk = "
			SELECT usuario_id
			FROM 
				usuario
			WHERE 
				usuario.usuario_rol_id = $x_destino_id
			LIMIT 1
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_usuario_id = $datawrk["usuario_id"];
			@phpmkr_free_result($rswrk);
			
		
			$sSql = "INSERT INTO crm_caso values (0,2,1,1,$x_cliente_id,'".$currdate."',$x_origen,$x_usuario_id,'$x_bitacora','".$currdate."',NULL,$x_credito_id)";
		
			$x_result = phpmkr_query($sSql, $conn);
			$x_crm_caso_id = mysql_insert_id();	
	

	
			if($x_crm_caso_id > 0){
	
				$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$currdate',NULL,NULL,NULL, 1, 1, 2, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
			
				$x_result = phpmkr_query($sSql, $conn);
			
				$sSqlWrk = "
				SELECT 
					comentario_int
				FROM 
					credito_comment
				WHERE 
					credito_id = ".$x_credito_id."
				LIMIT 1
				";
				
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_comment_ant = $datawrk["comentario_int"];
				@phpmkr_free_result($rswrk);
		
		
				if(empty($x_comment_ant)){
					$sSql = "insert into credito_comment values(0, $x_credito_id, '$x_bitacora', NULL)";
				}else{
					$x_bitacora = $x_comment_ant . "\n\n------------------------------\n" . $x_bitacora;
					$sSql = "UPDATE credito_comment set comentario_int = '$x_bitacora' where credito_id = $x_credito_id";
				}
		
				phpmkr_query($sSql, $conn);
			}
			
		
		}

	}// fin pagos vencidos


	}// numero total de vencimeintos
}
























die();

//@phpmkr_free_result($rswrk);
phpmkr_db_close($conn);
?>
