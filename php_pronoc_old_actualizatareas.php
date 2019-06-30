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
where credito.credito_status_id in (1,4) and vencimiento.fecha_vencimiento < '$currdate' and vencimiento.vencimiento_status_id in (1,3,6) order by vencimiento.fecha_vencimiento";

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
	
	$x_numero_de_pagos = 0;

	
	$sqlVenNum = "SELECT COUNT(*) AS numero_de_pagos FROM vencimiento WHERE  fecha_vencimiento =  \"$x_fecha_vencimiento\" AND  credito_id =  $x_credito_id";
	$response = phpmkr_query($sqlVenNum, $conn) or die("error en numero de vencimiento".phpmkr_error()."sql:".$sqlVenNum);
	$rownpagos = phpmkr_fetch_array($response);  
	$x_numero_de_pagos =  $rownpagos["numero_de_pagos"];
	

	if($x_numero_de_pagos < 2){
	// si el numero de pagos es mayor a uno significa que ya se cobraron los moratoios o parte de ellos y ya no se deben de volver a recalcular los moratorios...
	// solo entra al ciclo de moratorios si solo existe un  pago con esta fecha.
	
	
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
			$x_dias_gracia = 4;
			break;
		case "SATURDAY": // Get a record to display
			$x_dias_gracia = 3;
			break;
		case "SUNDAY": // Get a record to display
			$x_dias_gracia = 2;
			break;		
	}

	
	if($x_dias_vencidos >= $x_dias_gracia){
		$x_importe_mora = $x_tasa_moratoria * $x_dias_vencidos;
		if($x_iva_credito == 1){
//			$x_iva_mor = round($x_importe_mora * .15);
			$x_iva_mor = 0;			
		}else{
			$x_iva_mor = 0;
		}
		
//moratorios no majyores a 	2	

		if($x_credito_tipo_id == 2){			
			if($x_importe_mora > 0){
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



}// fin valida numero de pagos......
	
}// fin while moratorios




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

//echo  "dia actual".$currdate;
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
		
		//echo "mitad del credito <br>";
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
//echo "dia actual".$currdate."<br>";
//echo "fecha vencimiento".  ConvertDateToMysqlFormat($fecha_venc);

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





$sqlvencidos = "SELECT * FROM vencimiento WHERE vencimiento_status_id = 3 AND vencimiento.credito_id NOT IN  (
35,68,310,464,557,634,959,968,1049,1058,1063,1089,1120,1147,1208,1242,1259,1266,1279,1305,1310,1312,1318,1321,1357,1368,1375,1552,1564,
1641,1668,1682,1725,1739,1740,1780,1816,1818,1821,1904,1953,1963,1987,1996,2011,2066,2113,2135,2160,2164,2165,2170,2171,2194,2204,2239,2277,2278,
2306,2308,2311,2340,2359,2368,2375,2383,2408,2423,2444,2448,2453,2495,2511,2517,2555,2585,2586,2602,2615,2645,2671,2672,2673,2687,2708,2776,2877,2920,2935,2938,2944,2950,2953,
2957,2974,2984,2985,2993,2997,3000,3001,3006,3028,3029,3032,3034,3057,3058,3059,3072,3075,3104,3134,3144,3158,3167,3185,3190,3198,3205,3230,3249,3258,3264,3270,
3277,3290,3292,3294,3306,3312,3327,3361,3374,3377,3384,3393,3413,3416,3421,3447,3452,3454,3458,3463,3483,3511,3518,3521,3531,3539,3544,3552,3553,3578,3579,3581,3588,3593,3602,3606,3612,3626,3639,3641,3651,3652,3656,3659,3664,3670,3673,3680,3689,3690,3694,3697,3698,3704,3706,3707,3709,3713,3718,4277,3728,3742,3749,3750,3764,3768,4300,
3789,4278,4274,4257,3806,3807,3808,3809,3812,4364,815,3816,3822,3837,3839,3843,4319,3852,3855,3857,3859,3869,3878,4320,3880,3881,3882,
3884,3892,3894,3904,3917,3927,3928,3933,3944,3945,3946,3947,3954,3959,3961,3968,3971,3973,3974,3979,3982,3984,3988,3989,3990,3993,3996,4004,4007,4008,4011,4016,
4021,4026,4027,4032,4034,4035,4040,4042,4045,4049,4050,4053,4054,4056,4057,4058,4060,4061,4062,4066,4070,4071,4074,4075,4078,4083,4091,4116,4118,4119,4124,
4127,4135,4140,4149,4155,4158,170,4171,4172,4174,4179,4187,4193,4202,4207,4211,4212,4214,4217,4220,4223,4225,4231,4237,4241,4243,4245,4249,4251,4253,4254,4261,4262,4266,4271,4273,4287,4290,4310,4311,4321,4322,4326,4328,4331,4332,4333,4334,4338,4349,4355,4359,4361,4362,4369,4371,4373,4374,4375,4377,4382,4383,4386,4397,4400,4403,4404,4410,4411,4413,
4416,4417,4418,4420,4425,4431,4432,4437,4444,4462,4466,4467,4471,4474,4475,4480,4481,4482,4484,4486,4491,4493,4495,4496,4498,4500,4504,4505,4509,4511,4518,4522,4528,
4538,4554,4557,4561,4568,4569,4574,4575,4578,4580,4582,4585,4593,4594,4598,4604,4605,4606,4609,4610,4616,4618,4619,4622,4628,4630,4642,4646,4655,4665,4666,4677,
4679,4688,4686,4685,4704,4707,4708,4711,4717,4722,4724,4730,4729,4731,4734,4735,4737,4743,4746,4747,4753,4755,4757,4761,4763,4875,4775,4781,4782,4783,4784,4788,4792,4794,4807,4808,4809,4812,4813,4815,4820,4821,4823,4828,4833,4848,4854,4855,4857,4860,4861,4866,4877,4885,4886,4890,4891,4900,4905,4908,4911,4912,4913,4919,4921,4926,4927,4931,4936,4938,4939,4942,4945,4950,4959,4960,4968,4969,4970,4974,4977,4978,4979,4981,4984,4986,987,4988,4993,4994,4996,4997,5000,5004,5015,5016,5018,5030,5031,5036,5039,
5041,5042,5043,5045,5063,5068,5073,5074,5077,5091,5092,5093,5096,5108,5109,5111,5113,5248,5123,5125,5127,5130,5132,5133,5138,5140,5148,5149,5160,5163,5164,5165,5175,5180,
5191,5192,5200,5217,5224,5230,5235,5237,5239,5244,5245,5250,5255,5257,5258,5259,5271,5273,5275,5276,5278,5283,5292,5298,5300,5302,5309,5310,5311,5314,5318,5324,5325,5328,5330,5331,5333,5334,5335,5338,5339,5340,5341,5343,5347,5350,5351,5356,5359,5360,5365,5366,5371,5380,5381,5400,5406,5407,5410,5411,5412,5415,5419,5420,5421,5423,5427,5429,5431,5432,5437,5439,5443,5446,5447,5450,5451,5452,5454,5465,5468,5469,5475,5483,5488,5489,5491,5492,5495,5496,5499,5500,5501,5502,5503,5506,5508,5510,5511,5515,5516,5518,5519,5521,5523,5525,5529,5535,5536,5537,5540,5542,5547,5549,5550,5554,5555,5556,5558,5561,5562,5564,5569,5571,5572,5573,5574,5578,5583,5586,5587,5588,5590,5595,5596,5599,5601,5602,5603,5604,5606,5608,5609,5610,5611,5615,5616,5618,5626,5627,5632,5636,5638,5642,5644,5646,5647,5648,5655,5658,5659,5661,5663,5666,5669,5672,5674,5675,5677,5679,5680,5681,5683,5684,5685,5686,5688,5689,5692,5693,5694,5696,5698,5699,5704,5705,5706,5707,5708,5709,5710,5711,5712,5713,5714,5715,5716,5717,5718,5719,5720,5722,5723,5725,5726,5731,5733,5736,5738,5739,5740,5746,5747,5749,5751,5752,5753,5755,5758,5760,5761,5762,5763,5772,5765,5767,5768,5769,5770,5771,5773,5774,5775,5776,5780,5782,5786,5787,5789,5790,5791,5792,5793,5794,5797,5800,5803,5804,5805,5808,5810,5812,5813,5815,5816,5821,5822,5825,5827,5829,5831,5833,5834,5835,5837,5838,5843,5844,5845,5847,5848,5849,5852,5853,5854,5855,5856,5860,5861,5862,5867,5869,5870,5873,5875,5876,5881,5882,5893,5895,5898,5900,5901,5905,5906,5908,5910,5912,5915,5922,5931,5934,5935,5937,5941,5944,5948,5958,5960,5962,5969,5970,5974,5975,5976,5977,5982,5988,5989,6000,6013,6015,6020,6022,6023,6027,6036,6044,
6045,6046,6047,6048,6049,6056,6063,6073,6076,6077,6081,6082,6084,6087,6091,6094,6096,6099,6102,6103,6106,6127,6131,6162) group by credito_id";
$rsvencimientos = phpmkr_query($sqlvencidos, $conn) or die ("erroe al  selccionar los vencidos". phpmkr_error()."sql". $sqlvencidos);
while ($rowVV = phpmkr_fetch_array($rsvencimientos)){
	$x_credito_id = $rowVV["credito_id"];
	#echo "credito_id".$x_credito_id."<br>";
	$x_today_v = date("Y-m-d");
	
	
	$sqlInserVencido = "INSERT INTO `credito_vencido` ( `credito_vencido_id` , `credito_id` , `fecha` )";
	$sqlInserVencido .=  " VALUES ( NULL , $x_credito_id , '$x_today_v' );";
	
#	echo "sql insert".$sqlInserVencido."<br>";
	$rsInsertvencido = phpmkr_query($sqlInserVencido, $conn) or die ("Error al insertar en credito vencido". phpmkr_error(). "sql :".$sqlInserVencido);
	
	$sqlInserVencidoP = "INSERT INTO `credito_vencido_periodo` ( `credito_vencido_periodo_id` , `credito_id` , `fecha` )";
	$sqlInserVencidoP .=  " VALUES ( NULL , $x_credito_id , '$x_today_v' );";
	
#	echo "sql insert".$sqlInserVencido."<br>";
	$rsInsertvencidoP = phpmkr_query($sqlInserVencidoP, $conn) or die ("Error al insertar en credito vencido". phpmkr_error(). "sql :".$sqlInserVencidoP);
	
	
	
	
	
	
	
	}// WHILE VENCIDOS


// acepta condiciones de credito

$x_date_c = date("Y-m-d");
$sqlUpdateCond = "UPDATE `credito_condiciones` SET `status` = 1 WHERE fecha <= '".$x_date_c."' ";
$rsUpdateCond = phpmkr_query($sqlUpdateCond, $conn) or die ("Error al actulizar las condicones de credito".phpmkr_error()."sql:". $sqlUpdateCond);

// verifico si hay creditos que ya no tienen pagos pendientes 

#echo $sqlUpdateCond;
# cerrar tareas que cuando ya pago el cliente

// creditos que aun estan vencidos
$x_lista_creditos_aun_venc = "";
$sqlCC = "SELECT credito_id FROM vencimiento where vencimiento_status_id  = 3";
$rsCC = phpmkr_query($sqlCC, $conn) or die ("Error al seleccionar los aun vencidos".phpmkr_error()."sql:".$sqlCC);
while ($rowCC = phpmkr_fetch_array($rsCC)){
	$x_lista_creditos_aun_venc = $x_lista_creditos_aun_venc.$rowCC["credito_id"].", ";
	}
	$x_lista_creditos_aun_venc = substr($x_lista_creditos_aun_venc, 0, strlen($x_lista_creditos_aun_venc)-2);
	
	
$x_lista_casos_por_cerrar = "";
$sqlCaso = "SELECT crm_caso_id FROM crm_caso where fecha_registro > \"2012-08-01\" and crm_caso_tipo_id = 3 and credito_id not in ( $x_lista_creditos_aun_venc )";
$rsCaso = phpmkr_query($sqlCaso, $conn) or die ("Error al seleccionar los aun vencidos".phpmkr_error()."sql:".$sqlCaso);
while ($rowCaso = phpmkr_fetch_array($rsCaso)){
	$x_lista_casos_por_cerrar  = $x_lista_casos_por_cerrar.$rowCaso["crm_caso_id"].", ";
	}
	$x_lista_casos_por_cerrar  = substr($x_lista_casos_por_cerrar, 0, strlen($x_lista_casos_por_cerrar)-2);



$sqlUpdateCaso  = "UPDATE crm_caso SET crm_caso_status_id = 2 WHERE crm_caso_id in ($x_lista_casos_por_cerrar)";
$rsUpdate = phpmkr_query($sqlUpdateTarea, $conn) or die ("error al actulizar las tareas".phpmkr_error()."SQL:".$sqlUpdateTarea);

$sqlUpdateTarea  = "UPDATE crm_tarea SET crm_tarea_status_id = 4 WHERE crm_caso_id in ($x_lista_casos_por_cerrar)";
$rsUpdate = phpmkr_query($sqlUpdateTarea, $conn) or die ("error al actulizar las tareas".phpmkr_error()."SQL:".$sqlUpdateTarea);

//@phpmkr_free_result($rswrk);
phpmkr_db_close($conn);
?>
