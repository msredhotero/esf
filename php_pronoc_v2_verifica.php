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

echo "CURDATE".$currdate."<BR>";
//$x_dia = strtoupper($currentdate["weekday"]);

//$currdate = "2007-07-10";
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

// ************************************************   VENCE TAREAS
$sSqlWrk = "update crm_tarea set crm_tarea_status_id = 2 where fecha_ejecuta < '$currdate' and crm_tarea_status_id = 1 ";
#phpmkr_query($sSqlWrk,$conn);


// vencemos las tareas del promotor en TAREA_DIARIA-PROMOTOR; para que se puedan utilizar paracompletar dias...

$sqlUTDP = "UPDATE tarea_diaria_promotor SET status_tarea = 2 where fecha_lista < '$currdate' and tarea_id in (SELECT crm_tarea_id from crm_tarea WHERE crm_tarea_status_id = 2 )AND  status_tarea = 1";
#phpmkr_query($sqlUTDP,$conn)or die ("Error al actualiza el status de la tarea".phpmkr_error()."sql:".$sqlUTDP);

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
	#phpmkr_query($sSqlWrkven,$conn) or exit();
	
	
	// cerramos las tareas y cerramos el caso
	
	
	
	
		
		
		$sSqlWrkven = "update crm_tarea set crm_tarea_status_id = 4 where crm_caso_id = $x_crm_caso_id  and crm_tarea_status_id in (1,2)";
		#phpmkr_query($sSqlWrkven,$conn) or die("Error al actulizar los estatusde las tareas liquidadas".phpmkr_error()."sql :".$sSqlWrkven);
		
		$sSqlWrkven1 = "update tarea_diaria_promotor set status_tarea = 4 where caso_id = $x_crm_caso_id and status_tarea in (1,2)";
		#phpmkr_query($sSqlWrkven1,$conn) or die("Error al actulizar los estatusde las tareas liquidadas en tareas diarias promotor".phpmkr_error()."sql :".$sSqlWrkven1);

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
		#phpmkr_query($sSqlWrkven,$conn) or exit();




	}
}



// ************************************************   MORATORIOS
$sSqlWrk = "SELECT vencimiento.*, credito.penalizacion, credito.credito_status_id, credito.credito_tipo_id, credito.importe as importe_credito, credito.tasa_moratoria, credito.credito_num+0 as crednum, credito.tasa, forma_pago.valor as forma_pago_valor, credito.iva as iva_credito FROM vencimiento join credito 
on credito.credito_id = vencimiento.credito_id join forma_pago on forma_pago.forma_pago_id = credito.forma_pago_id 
where credito.credito_status_id in (1,4) and vencimiento.fecha_vencimiento < '$currdate' and vencimiento.vencimiento_status_id in (1,3,6) and credito.credito_id in (4217) order by vencimiento.fecha_vencimiento";

//and credito.credito_id > 5000  and credito.credito_id < 5020 

echo "sql :".$sSqlWrk."<br>";
//" and credito.credito_num = '283'";
$rswrkmain = phpmkr_query($sSqlWrk,$conn) or die("error el query 1".phpmkr_error()."ql. :");
while($datawrkmain = phpmkr_fetch_array($rswrkmain)){
	#echo "entra <br>";
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
	echo "credito_id".$x_credito_id;
	$x_credito_tipo_id = $datawrkmain["credito_tipo_id"];	
	
	$x_numero_de_pagos = 0;
	
		//campo de penalizacion
	$x_penalizacion = $datawrkmain["penalizacion"];
#	echo "penal".$x_penalizacion."<br>";

#aqui volvemos a contar los dias de atraso para ejecutar el crm

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

#	echo "froma de pago =".$x_forma_pago_valor."<br>";
#	echo "penalizacion ".$x_penalizacion."<br>";
	if($x_dias_vencidos >= $x_dias_gracia){
		
		
#echo "entra a CRM credi. id ".$x_credito_id ."<br>";
//GENERA CASO CRM
// valida que no haya ya un caso abierto para este credito
//EN LA PRIMER FASE DE COBRANBZA LA TAREA SE ASIGNA AL RESPONSABLE DE SUSCURSAL


//seleccionamo la fecha de otorgamiento del credito



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
	
	
	
	//kukia
	// contamos el numero de venciemientos que tiene vencidos...
	// si son mas de dos la tarea se asigna al asesor de credito
	
	// para sacar el numero de vencimientos vencidos, seleccionamos el primer vencimeinto y le restamos lo que tenga en moratorio y lo que tenbga de iva en mora
	// asi tenemos el monto incial del vencimeinto y pordremos calcular el numero de vencimetos vencidos por capital.
	$x_numero_de_vencimientos_vencidos = 0;
	$sqlMora = "SELECT total_venc, interes_moratorio,iva_mor FROM vencimiento WHERE credito_id = $x_credito_id and vencimiento_num	= 1";
		$rsMora = phpmkr_query($sqlMora,$conn) or die ("erro al seleccionar la comison en casaos anteriores".phpmkr_error()."sql:".$sqlMora);
		$rowMora = phpmkr_fetch_array($rsMora);
		$x_monto_vencimeinto_mora = $rowMora["total_venc"];
		$x_monto_moratorios_mora = $rowMora["interes_moratorio"];
		$x_monto_iva_mora_mora = $rowMora["iva_mor"];		
		$x_monto_pago_mora = $x_monto_vencimeinto_mora -($x_monto_moratorios_mora  + $x_monto_iva_mora_mora);
		#echo "sql monto".$sqlMora."<br><br>";
		#echo "monto pago mora".$x_monto_pago_mora."<br>";
		#echo "total venc".$x_monto_vencimeinto_mora."<br>";
		#echo "ineres moratorio".$x_monto_moratorios_mora."<br>";
		#echo "iva mora".$x_monto_iva_mora_mora."<br>";
		#echo " fromula monto = total ven -(mora + iva mora)"; //kuki
		
		#buscamos el monto vencido
		$sqlBuscaMontoVencV = " SELECT SUM(total_venc) AS monto_vencido FROM vencimiento WHERE credito_id = $x_credito_id and  vencimiento_status_id in (3,6)";
		$rsBuscaMontoVencV = phpmkr_query($sqlBuscaMontoVencV,$conn) or die ("Error al seleccionar el monto del vencimiento".phpmkr_error()."sql:".$sqlBuscaMontoVencV);
		$rowBuscaMontoVencV = phpmkr_fetch_array($rsBuscaMontoVencV);
		$x_monto_VVencidoV = $rowBuscaMontoVencV["monto_vencido"];
		#echo "sql vencido".$sqlBuscaMontoVencV ."<br><br>";
		#echo "m0onto vencido--".$x_monto_VVencidoV."<br>";
		$x_numero_de_vencimientos_vencidos =  $x_monto_VVencidoV / $x_monto_pago_mora;
		#echo "vencimeintos vencidos arriba".$x_numero_de_vencimientos_vencidos."<br>";
	
	
	$sqlNoVencimientosVencidos = "SELECT COUNT(*) AS vencimientos_vencidos FROM vencimiento WHERE credito_id = $x_credito_id AND vencimiento_status_id = 3";
	$rsNoVncimeitosVencidos = phpmkr_query($sqlNoVencimientosVencidos,$conn)or die ("Error al seleccionar el numeros de venciemientos vencisdos del credito".phpmkr_error()."sql:".$sqlNoVencimientosVencidos);
	$rowNoVnecimientosVencidos = phpmkr_fetch_array($rsNoVncimeitosVencidos);
	//$x_numero_de_vencimientos_vencidos = $rowNoVnecimientosVencidos["vencimientos_vencidos"];
	$x_responsable_susursal_usuario_id = 0;
	$x_forma_pago_id = 0;
	$sqlFormaPago  = "SELECT forma_pago_id FROM credito WHERE credito_id = $x_credito_id ";
	$rsFormaPago = phpmkr_query($sqlFormaPago,$conn) or die ("Error al selecccionar la forma de pago del credito".phpmkr_error()."sql; ".$sqlFormaPago);
	$rowFormaPago = phpmkr_fetch_array($rsFormaPago);
	$x_forma_pago_id = $rowFormaPago["forma_pago_id"];
	
	
	echo "numero de vencimientos_vencidos".$x_numero_de_vencimientos_vencidos."<br>";
	if($x_numero_de_vencimientos_vencidos < 2){
		// la tarea sera asignada al 	RESPONSABLE DE SUCURSAL	
		# seleccionamos los datos del responsable de sucursal.
		$sqlSolId = "SELECT solicitud_id FROM credito WHERE credito_id = $x_credito_id";
		$rsSolId = phpmkr_query($sqlSolId,$conn) or die ("Error al seleccionar el id de la solicitud del credito".phpmkr_error()."sql:");
		$rowSolId = phpmkr_fetch_array($rsSolId);
		$x_solicitud_id_c = $rowSolId["solicitud_id"];
		
		// seleccionamos el promotor
		$sqlPromotor = "SELECT promotor_id FROM solicitud WHERE solicitud_id = $x_solicitud_id_c";
		$rsPromotor = phpmkr_query($sqlPromotor,$conn) or die ("Error al seleccionar el promotor del credito".phpmkr_error()."sql :".$sqlPromotor);
		$rowPromotor = phpmkr_fetch_array($rsPromotor);
		$x_promotor_id_c = $rowPromotor["promotor_id"];
		
		if($x_promotor_id_c > 0){
			// buscamos a que sucursal pertence el promotor
			$sqlSucursal = "SELECT sucursal_id FROM promotor WHERE promotor_id = $x_promotor_id_c";
			$rsSucursal = phpmkr_query($sqlSucursal,$conn) or die ("Error al seleccionar la sucursal". phpmkr_error()."Sql:".$sqlSucursal); 
			$rowSucuersal = phpmkr_fetch_array($rsSucursal);
			$x_sucursal_id_c = $rowSucuersal["sucursal_id"];
			
			if($x_sucursal_id_c > 0){
				// si ya tenbemos la sucursal, buscamos el representante de essa sucursal
				$sqlResponsable = "SELECT usuario_id FROM responsable_sucursal WHERE sucursal_id = $x_sucursal_id_c ";
				$rsResponsable = phpmkr_query($sqlResponsable,$conn) or die ("error al seleccionar el usuario del responsable de suscursal".phpmkr_error()."sql:".$sqlResponsable);
				$rowResponsable = phpmkr_fetch_array($rsResponsable);
				$x_responsable_susursal_usuario_id = $rowResponsable["usuario_id"];		
				
				}
			} 
		
		
		}
	
	if($x_caso_abierto == 0){
		
		// si no hay caso abierto.. quiere decir que es el primer atraso del cliente por lo tanto lo gationara el responsable de sucursal
		echo "ENTRA A CASO ABIERTO == 0<br>" .$x_credito_id."<br>";
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


		if(empty($x_responsable_susursal_usuario_id)){
			// si el usuario de rsponsable de sucursal esta vacio entonces se busca la persona a la que se le asignara el caso.
			// en este caso pasa al asesor de credito.
			
			
		$sqlSolId = "SELECT solicitud_id FROM credito WHERE credito_id = $x_credito_id";
		$rsSolId = phpmkr_query($sqlSolId,$conn) or die ("Error al seleccionar el id de la solicitud del credito".phpmkr_error()."sql:");
		$rowSolId = phpmkr_fetch_array($rsSolId);
		$x_solicitud_id_c = $rowSolId["solicitud_id"];
		
		// seleccionamos el promotor
		$sqlPromotor = "SELECT promotor_id FROM solicitud WHERE solicitud_id = $x_solicitud_id_c";
		$rsPromotor = phpmkr_query($sqlPromotor,$conn) or die ("Error al seleccionar el promotor del credito".phpmkr_error()."sql :".$sqlPromotor);
		$rowPromotor = phpmkr_fetch_array($rsPromotor);
		$x_promotor_id_c = $rowPromotor["promotor_id"];
		
		if($x_promotor_id_c > 0){
		// seleccionamos el usuario del promotor
		
				
		$sSqlWrk = "
		SELECT usuario_id
		FROM 
			promotor
		WHERE 
			promotor.promotor_id = $x_promotor_id_c ";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_usuario_id = $datawrk["usuario_id"];
		@phpmkr_free_result($rswrk);
		}
		
		#si el responsable de sucursal esta vacio, significa que son mas de dos pagos los que se deben
		# y significa que la tarea que se debe de asignar es la fase dos.
		# que corresponde a la carata 1
		
		#seleccionamos los datos de la carata 1
		
		$sSqlWrk = "SELECT *
							FROM 
							crm_playlist
							WHERE 
							crm_playlist.crm_caso_tipo_id = 3
							AND crm_playlist.orden = 4 "; // orden 4 CARTA  1		
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
	
	
	
				$x_origen = 1;  // el origen se podria cambiar a responsable de sucursal.
				$x_bitacora = "Cartera Vencida  INICIA EN ETAPA 2  CARTA 1 - (".FormatDateTime($currdate,7)." - $currtime)";
			
				$x_bitacora .= "\n";
				$x_bitacora .= "$x_asunto - $x_descripcion ";	
		
		
		
		
		}else{
			// si el usuario de responsable viene lleno...
			# se le asigna la tarea a el....
			$x_usuario_id  = $x_responsable_susursal_usuario_id;
			
			}
			if($x_cliente_id <1){
				$x_cliente_id = 0;
				}
	
		$sSql = "INSERT INTO crm_caso values (0,3,1,1,$x_cliente_id,'".$currdate."',$x_origen,$x_usuario_id,'$x_bitacora','".$currdate."',NULL,$x_credito_id)";
	
		$x_result = phpmkr_query($sSql, $conn) or die ("error al insertar carta 1". phpmkr_error()."sql.".$sSql);
		echo "INSERT  :<BR>".$sSql."<BR>";
		$x_crm_caso_id = mysql_insert_id();	


		if(($x_crm_caso_id > 0)){

			$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 2, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
		
			$x_result = phpmkr_query($sSql, $conn);
			$x_tarea_ida = mysql_insert_id();
	
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
			echo "INSERT TAREA:<BR>".$sSql."<BR> TERMINA CLICLO NO TIENE TAREA <BR><BR>";
			$x_comment_ant = $datawrk["comentario_int"];
			@phpmkr_free_result($rswrk);
	
	
			if(empty($x_comment_ant)){
				$sSql = "insert into credito_comment values(0, $x_credito_id, '$x_bitacora', NULL)";
			}else{
				$x_bitacora = $x_comment_ant . "\n\n------------------------------\n" . $x_bitacora;
				$sSql = "UPDATE credito_comment set comentario_int = '$x_bitacora' where credito_id = $x_credito_id";
			}
	
			phpmkr_query($sSql, $conn);
		
		
		if(empty($x_responsable_susursal_usuario_id)){
	
        ########################################################################################################################################################
		############################################################ TAREAS DIARIAS PARA PROMOTORES	############################################################
		########################################################################################################################################################
		##credamos las lista de los promotores con la tareas diarias.		
		## seleccionaos la soza de la solcitud		
		$sqlZonaId = "SELECT dia FROM  zona JOIN solicitud ON solicitud.zona_id = zona.zona_id WHERE solicitud.solicitud_id = $x_solicitud_id_c";
		$rsZonaId = phpmkr_query($sqlZonaId,$conn) or die ("Error al seleccionar el dia de la zona".phpmkr_error()."sql:".$sqlZonaId);
		$rowZonaId = phpmkr_fetch_array($rsZonaId);
		$x_dia_zona = $rowZonaId["dia"];
		
		$x_fecha_tarea_f = $currdate;
		$x_dia_zona_f = $x_dia_zona;
		$x_promotor_id_f = $x_promotor_id_c ;
		$x_caso_id_f = $x_crm_caso_id;
		$x_tarea_id_f = $x_tarea_ida;
		
		
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
		echo " -------- DIAS AGREGADOS ------ ".$x_dias_agregados."<br> "; 
		//kuki
		
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
				echo "UPDATAE TREA---".$sqlUpdateFecha."<br>";
				
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
			$sqlUpdateFecha = "UPDATE crm_tarea SET fecha_ejecuta = \"$x_fecha_ejecuta_act\" WHERE crm_tarea_id = $x_tarea_id_f ";
				$rsUpdateFecha = phpmkr_query($sqlUpdateFecha,$conn) or die ("Error al actualiza la fecha de latarea despues del calculo semana".phpmkr_error()."sql;".$sqlUpdateFecha);
				echo "UPDATAE TREA---".$sqlUpdateFecha."<br>";
			}
		#echo "FECHA EJECUTA".$x_fecha_ejecuta_act;
		#####################################################################################################################
		##################################### TAREAS DIARIAS PROMOTOR #######################################################
		#####################################################################################################################
		
		// primero verifamos que la tarea aun no este en la lista, es decir, que se trate de la primera vez que entra al cliclo para este credito.
		$sqlBuscaatreaAsignada = "SELECT COUNT(*) AS atreas_asignadas FROM `tarea_diaria_promotor` WHERE fecha_ingreso =  \"$currdate\" AND promotor_id = $x_promotor_id_f ";
		$sqlBuscaatreaAsignada .= " AND `caso_id` = $x_caso_id_f";
		$rsBuscatareaAsignada = phpmkr_query($sqlBuscaatreaAsignada,$conn) or die("Erro al buscar atrea".phpmkr_error()."sql:".$sqlBuscaatreaAsignada);
		echo "UPDATE TAREAS".$rsBuscatareaAsignada."<BR>";
		#echo "BUSCA TAREAS".$sqlBuscaatreaAsignada."<BR>";
		$rowBuscaTareaAsignada = phpmkr_fetch_array($rsBuscatareaAsignada);
		$x_tareas_asignadas_del_caso = $rowBuscaTareaAsignada["atreas_asignadas"];
		#echo "TAREAS ASIGNADAS ".$x_tareas_asignadas_del_caso."<br>";
		//se inserta la tarea en la lista de las actividades diarias del promotor
		if ($x_tareas_asignadas_del_caso < 1){
		$sqlInsertListaTarea = "INSERT INTO `tarea_diaria_promotor`";
		$sqlInsertListaTarea .= " (`tarea_diaria_promotor_id`, `promotor_id`, `zona_id`, `dia_semana`, `fecha_ingreso`, `fecha_lista`, `caso_id`, ";
		$sqlInsertListaTarea .= " `tarea_id`, `reingreso`, `fase`, `status_tarea`, `credito_id`) ";
		$sqlInsertListaTarea .= "VALUES (NULL, $x_promotor_id_f, $x_dia_zona_f , $x_dia_zona_f, \"$currdate\",\"$x_fecha_tarea_f\", $x_caso_id_f, $x_tarea_id_f, '0', '2', '1', $x_credito_id);";
		$rsInsertListaTarea = phpmkr_query($sqlInsertListaTarea,$conn)or die("Error al insertar en lista diaria tareas".phpmkr_error()."sql:".$sqlInsertListaTarea);	 
		
		
		
		}
		}
		}
		
	
	}else{
		
		$x_avanza_fase = 0;
		$x_promesas_c1 = 0;
		$x_fecha_promesa_pago_c1 = 0;
		$x_promesas_c2 = 0;
		$x_fecha_promesa_pago_c2 = 0;
		$x_promesas_c3 = 0;
		$x_fecha_promesa_pago_c3 = 0;
		$x_promesas_cD = 0;
		$x_fecha_promesa_pago_cD = 0; 
		
	
			
		
		
	
			
		
		$sqlCartaD = "SELECT COUNT(*) AS crm_tarea_id FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND  crm_tarea_tipo_id = 12 AND orden= 20";
		$rsCartaD = phpmkr_query($sqlCartaD,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCartaD);
		$rowCarataD = phpmkr_fetch_array($rsCartaD);
		$x_carta_D_tarea_id = $rowCarataD["crm_tarea_id"];
		if($x_carta_D_tarea_id >0 ){
			//buscamos que ya tenga Una promesa de pago y que este vencida para entrar al ciclo			
			$sqlPPCD = "SELECT * FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id  AND crm_tarea_tipo_id = 5 AND orden = 24 ";
			$rsppcD = phpmkr_query($sqlPPCD,$conn)or die ("Error al seleccioanr la pp c2".phpmkr_error()."sql:".$sqlPPCD);
			$rowppcD = phpmkr_fetch_array($rsppcD);
			$x_fecha_registro_tarea = $rowppcD["fecha_registro"];
			$x_tarea_ppcD =$rowppcD["crm_tarea_id"];
			$x_caso_ppcD = $rowppcD["crm_caso_id"];			
			if($x_caso_ppcD> 0){
				// la promesa de pago esta registrada; buscamos la fecha de pagoo de la promesa
				$sqlcrm_tarea_cvD = "SELECT COUNT(*) AS promesas, promesa_pago  FROM crm_tarea_cv WHERE crm_tarea_id = $x_tarea_ppc1 ";
				$rscrm_tarea_cvD = phpmkr_query($sqlcrm_tarea_cvD,$conn)or die("Error al selecionar la fecha de promesa de pago".phpmkr_error()."sql:".$sqlcrm_tarea_cvD);
				$rowcrm_tarea_cvD = phpmkr_fetch_array($rscrm_tarea_cvD);
				$x_promesas_cD = $rowcrm_tarea_cvD["promesas"];
				$x_fecha_promesa_pago_cD = $rowcrm_tarea_cvD["promesa_pago"]; 			
				}			
			}
			
					
		#ya existe un caso abierto pero ahora debemos de revisar si el credito
		#ya presneta mas de dos venciemitpos vencidos entonces cambiamos el caso y la tarea al asesor de credito.
		
		//seleccionamos los datos del responsable de susursal
		
		# se debe seleccionar si
		# ya tiene carat 1
		# ya tiene carta 2
		# ya tiene carta 3 
		# ya tiene demanda
		
		# si ya tiene comision por monto
		
		
		
		
		// contamos los vencimientos pendientes, para las validaciones de los ciclos de las promesas de pago
		// los vencimetos debe de estar como pendientes o como remanentes;
		$x_vencimeintos_pendientes_de_pago = 0;
		$sqlVencimientosPendientes = "SELECT COUNT(*)  vecimientos_pendientes_por_pagar FROM vencimiento WHERE credito_id = $x_credito_id";
		$sqlVencimientosPendientes .= " and vencimiento.vencimiento_status_id in (1,7)";
		$rsVencimientosPendientes = phpmkr_query($sqlVencimientosPendientes,$conn) or die ("Error en vencimeintos pendientes". phpmkr_error()."sql:".$sqlVencimientosPendientes);
		$rowVencimeintosPendientes = phpmkr_fetch_array($rsVencimientosPendientes);
		$x_vencimeintos_pendientes_de_pago = $rowVencimeintosPendientes["vecimientos_pendientes_por_pagar"];
	
		echo "ENTRA EN CASO ABIERTO DIFERENTE A 0 <br> ".$x_credito_id."<br>";
		$sSqlWrk = "SELECT crm_caso_id 
					FROM 
						crm_caso
					WHERE 
						crm_caso.crm_caso_tipo_id = 3
						AND crm_caso.crm_caso_status_id = 1
						AND crm_caso.credito_id = $x_credito_id";
						
	
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$datawrk = phpmkr_fetch_array($rswrk);
	$x_crm_caso_id = $datawrk["crm_caso_id"];	
	
	//VERFICAMOS QUE PARA EL DIA DE HOY AUN NO EXISTA NINGUNA TAREA REGISTRADA....
	// SI YA EXISTE ALGUNE ATREA REGISTRADA SE SALE DEL CLICLO.
	$sqlTreaHoy = "SELECT COUNT(*) AS tarea_asignada_hoy FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND crm_tarea_status_id = 1";
	$rsTareaHoy = phpmkr_query($sqlTreaHoy,$conn) or die ("Error a seleccionar la tarea de hoy".phpmkr_error()."sql:".$sqlTreaHoy);
	$rowTareaHoy = phpmkr_fetch_array($rsTareaHoy);
	$x_tarea_del_caso_hoy = $rowTareaHoy["tarea_asignada_hoy"] + 0;
	
	echo "sql ".$sqlTreaHoy."<br>";
	echo "tareas de hoy ".$x_tarea_del_caso_hoy."<br>";
	
	if($x_tarea_del_caso_hoy < 1){
	echo "caso_id".$x_crm_caso_id."<br>";
	$x_comision_generada = 0;
	$x_penalizacion_a = 0;	
	$x_monto_VVencido = 0;
	$x_forma_de_pago = 0;
	$x_carta_1 = 0;
	$x_carta_2 = 0;
	$x_carta_3 = 0;
	$x_carta_D = 0;
	$x_ciclo_carta_2 = 0;
	$x_ciclo_carta_3 = 0;
	$x_ciclo_carta_D = 0;
	
	
	$sqlCarta1 = "SELECT COUNT(*) AS carta_1 FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND  crm_tarea_tipo_id = 8 AND orden= 4";
	$rsCarta1 = phpmkr_query($sqlCarta1,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCarta1);
	$rowCarata1 = phpmkr_fetch_array($rsCarta1);
	$x_carta_1 = $rowCarata1["carta_1"] +0;
	echo "carta 1..". $x_carta_1."<br>";
	echo "vencimientos pendientes de pago ".$x_vencimeintos_pendientes_de_pago."<br>";
	$x_cambia_cliclo_c1 = 0;
	if( $x_vencimeintos_pendientes_de_pago == 0){
		// ya todos estan vencidos 7 puede que el monto no sea suficiente para generar la comision por cobranza
		// SELECCIONAMOS LA PP DE CARTA 1
		echo "ya no hay pagos pendientes todo el credito esta vencido<br>";
	$sqlPPC1 = "SELECT * FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id  AND crm_tarea_tipo_id = 5 AND orden = 5 ";
	$rsppc1 = phpmkr_query($sqlPPC1,$conn)or die ("Error al seleccioanr la pp c2".phpmkr_error()."sql:".$sqlPPC1);
	$rowppc1 = phpmkr_fetch_array($rsppc1);
	$x_fecha_registro_tarea = $rowppc1["fecha_registro"];
	$x_tarea_ppc1 =$rowppc1["crm_tarea_id"];
	$x_caso_ppc1 = $rowppc1["crm_caso_id"];
	
	
	//selecciono la carta 1
	$sqlCarta1_id = "SELECT crm_tarea_id AS carta_1_id FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND  crm_tarea_tipo_id = 8 AND orden= 4";
	$rsCarta1_id = phpmkr_query($sqlCarta1_id,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCarta1_id);
	$rowCarata1_id = phpmkr_fetch_array($rsCarta1_id);
	$x_carat_1_id = $rowCarata1_id["carta_1_id"];
	echo "carta 1 id ***".$x_carat_1_id."<br>";
	
	if(!empty($x_carat_1_id)){
	$sqlFechappc1 = "SELECT * FROM  crm_tarea_cv WHERE crm_tarea_id = $x_carat_1_id ";
	$rsFechappc1 = phpmkr_query($sqlFechappc1,$conn)or die ("Error al seleccionar la fechappc1a".phpmkr_error()."sql:".$sqlFechappc1);
	$rowfechappc1 = phpmkr_fetch_array($rsFechappc1);
	$x_fecha_entrega_c1 = $rowfechapp1["promesa_pago"];
	
echo "entra el primer if carta 1 no vaia<br>";
	#########################################################
	#############     CICLO POR DIAS    #####################
	#########################################################
	if(!empty($x_carat_1_id)){
	$sqlFechaCarata1 = "SELECT fecha_entrega FROM  crm_tarea_cv WHERE crm_tarea_id = $x_carat_1_id ";
	$rsFechaCarta1 = phpmkr_query($sqlFechaCarata1,$conn) or die ("Error al seleccionar la fecha de entrega de la carata". phpmkr_error()."sql :".$sqlFechaCarata1);
	$rowFechaCarta1 = phpmkr_fetch_array($rsFechaCarta1);
	$x_fecha_imprime_carat1 = $rowFechaCarta1["fecha_entrega"];
	echo "entra el segundo if carta 1 no vaia<br>";
	// SE BUSCA LA FECHA DE LA PROMESA DE PAGO PARA LA CARTA 1;
	// YA NO SE BUSCA LA PROMESA DE PAGO PARA LA CARTA
	// SE BUESCA LA ULTIMA PROMESA DE PAGO
	
	$sqlPPC1 = "SELECT * FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id  AND crm_tarea_tipo_id = 5 AND orden = 5 ORDER BY crm_tarea_id DESC LIMIT 0,1 ";
	$rsppc1 = phpmkr_query($sqlPPC1,$conn)or die ("Error al seleccioanr la pp c2".phpmkr_error()."sql:".$sqlPPC1);
	$rowppc1 = phpmkr_fetch_array($rsppc1);
	$x_fecha_registro_tarea_PP_U = $rowppc1["fecha_registro"];
	$x_tarea_ppc1_PP_U =$rowppc1["crm_tarea_id"];
	//TENEMOS LA ULTIMA PROMESA DE PAGO
	echo "ultima promesa de pago<br>".$sqlPPC1 ."<br>-----".$x_tarea_ppc1_PP_U." ----<br>";
	
	// SELECCIONAMOS LA FECHA QUE DUIO EL CLIENTE PARA PAGAR
	if (!empty($x_tarea_ppc1_PP_U)){
				// seleccionamos la fecha de  la promesa de pago
	$sqlFechappc1 = "SELECT * FROM  crm_tarea_cv WHERE crm_tarea_id = $x_tarea_ppc1_PP_U ";
	$rsFechappc1 = phpmkr_query($sqlFechappc1,$conn)or die ("Error al seleccionar la fechappc1a".phpmkr_error()."sql:".$sqlFechappc1);
	$rowfechappc1 = phpmkr_fetch_array($rsFechappc1);
	echo "<br> FECHA PROMESA DE PAGO ..........<br>".$sqlFechappc1."<BR>";
	$x_fecha_entrega_PP_U = $rowfechappc1["promesa_pago"]; //esta fecha de promesa de pago si esta bien......
	echo "____+".$rowfechappc1["promesa_pago"]."+_____";
		}
	
	
	
	}
	
	echo "<br>===============FECHA DE PROMESA DE PAGO  =============".$x_fecha_entrega_PP_U."<br>";
		
	$x_dias_transcurridos_c1  = datediff('d', $x_fecha_entrega_PP_U, $currdate, false);
	echo "<br>===============dias transcurridos entre la fecha imprime y hoy =============".$x_dias_transcurridos_c1."<br>";
	$x_cambia_cliclo_c1 = 0;
	// verificar la forma  de pago del credito
	
	// fomas de pago
	// 1 = semanal
	// 2 = catorcenal
	// 3 = mensual
	// 4 = quincenal
	
	
	
	echo "dias necesarios <br>";
	echo "vencimientos_pendienes de pgo".$x_vencimeintos_pendientes_de_pago."<br>";
	if($x_vencimeintos_pendientes_de_pago == 0){
		echo "entra a todo venciado switch<br>";
		echo "forma de pago id --->".$x_forma_pago_id."<-----<br>";
	switch($x_forma_pago_id)
	{
		case 1:
		echo " dias necesarios 20<br>";
		if($x_dias_transcurridos_c1 > 20){
			$x_cambia_cliclo_c1 = 1;
			echo "20<br>";
			}
		break;
		case 2:
		if($x_dias_transcurridos_c1 > 28){
			$x_cambia_cliclo_c1 = 1;
			echo "28<br>";
			}
		break;
		case 3:
		if($x_dias_transcurridos_c1 > 34){
			$x_cambia_cliclo_c1 = 1;
			echo "34<br>";
			}
			break;
		case 4;
		if($x_dias_transcurridos_c1 > 30){
			$x_cambia_cliclo_c1 = 1;
			echo "30<br>";
			}		
		
		
		
		}
	
	
	
	}else{
		$x_cambia_cliclo_c1 = 0;		
		}
	}
	
	
	
		
		}
	echo "CAMBIA CICLO ".$x_cambia_cliclo_c1."<BR>";
	
	

	
	$sqlCarta2 = "SELECT COUNT(*) AS carta_2 FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND  crm_tarea_tipo_id = 9 AND orden= 8";
	$rsCarta2 = phpmkr_query($sqlCarta2,$conn)or die ("Error al seleccionar la carta 2".phpmkr_error()."sql:".$sqlCarta2);
	$rowCarata2 = phpmkr_fetch_array($rsCarta2);
	$x_carta_2 = $rowCarata2["carta_2"] +0;
	
	echo "carta 2..". $x_carta_2."<br>";
	
	// SELECCIONAMOS LA PP DE CARTA 2
	$sqlPPC2 = "SELECT * FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id  AND crm_tarea_tipo_id = 5 AND orden = 9 ";
	$rsppc2 = phpmkr_query($sqlPPC2,$conn)or die ("Error al seleccioanr la pp c2".phpmkr_error()."sql:".$sqlPPC2);
	$rowppc2 = phpmkr_fetch_array($rsppc2);
	$x_fecha_registro_tarea = $rowppc2["fecha_registro"];
	$x_tarea_ppc2 =$rowppc2["crm_tarea_id"];
	$x_caso_ppc2 = $rowppc2["crm_caso_id"];
	//carta 2
	$sqlC2_id = "SELECT * FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND  crm_tarea_tipo_id = 9 AND orden= 8";
	$rsc2_id = phpmkr_query($sqlC2_id,$conn)or die ("Error al seleccioanr la pp c2".phpmkr_error()."sql:".$sqlC2_id);
	$rowc2_id = phpmkr_fetch_array($rsc2_id);
	$x_tarea_c2_id =$rowc2_id["crm_tarea_id"];
	
	#########################################################
	#############     CICLO POR DIAS    #####################
	#########################################################
	if(!empty($x_tarea_c2_id)){
		
		// si existe la carta 2; dia en  que se imprimio la carata C2
	$sqlFechaCarata2 = "SELECT fecha_entrega FROM  crm_tarea_cv WHERE crm_tarea_id 	 = $x_tarea_c2_id ";
	$rsFechaCarta2 = phpmkr_query($sqlFechaCarata2,$conn) or die ("Error al seleccionar la fecha de entrega de la carata". phpmkr_error()."sql :".$sqlFechaCarata2);
	$rowFechaCarta2 = phpmkr_fetch_array($rsFechaCarta2);
	$x_fecha_imprime_carat2 = $rowFechaCarta2["fecha_entrega"];	
	// SE BUSCA LA FECHA DE LA PROMESA DE PAGO PARA LA CARTA 1;
	// YA NO SE BUSCA LA fecha de impresion DE PAGO PARA LA CARTA
	// SE BUESCA LA ULTIMA PROMESA DE PAGO
	$sqlPPC2 = "SELECT * FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id  AND crm_tarea_tipo_id = 5 AND orden = 9 ORDER BY crm_tarea_id DESC LIMIT 0,1 ";
	$rsppc2 = phpmkr_query($sqlPPC2,$conn)or die ("Error al seleccioanr la pp c2".phpmkr_error()."sql:".$sqlPPC2);
	$rowppc2 = phpmkr_fetch_array($rsppc2);
	$x_fecha_registro_tarea = $rowppc2["fecha_registro"];
	$x_tarea_ppc2_PP_U =$rowppc2["crm_tarea_id"];
	$x_caso_ppc2 = $rowppc2["crm_caso_id"];
	
	
	if(!empty($x_tarea_ppc2_PP_U)){
		// si existe la carta 2// buscamos la fecha de la promesa de pago
		$sqlFechaCarata2 = "SELECT promesa_pago FROM  crm_tarea_cv WHERE crm_tarea_id 	 = $x_tarea_ppc2_PP_U ";
	$rsFechaCarta2 = phpmkr_query($sqlFechaCarata2,$conn) or die ("Error al seleccionar la fecha de entrega de la carata". phpmkr_error()."sql :".$sqlFechaCarata2);
	$rowFechaCarta2 = phpmkr_fetch_array($rsFechaCarta2);
	$x_fecha_imprime_carat2_PP_U = $rowFechaCarta2["promesa_pago"];			
		}
	
	
	// si no hay bpromesa de pago; cambia ciclo se quedara en 1 porque los dias de diferencia seran enormes....
	
	
	}
	$x_dias_transcurridos_c2  = datediff('d', $x_fecha_imprime_carat2_PP_U, $currdate, false);
	$x_cambia_cliclo_c2 = 0;
	// verificar la forma  de pago del credito
	
	
	
	// fomas de pago
	// 1 = semanal
	// 2 = catorcenal
	// 3 = mensual
	// 4 = quincenal
	if($x_vencimeintos_pendientes_de_pago == 0){
	switch($x_forma_pago_id)
	{
		case 1:
		if($x_dias_transcurridos_c2 > 20){
			$x_cambia_cliclo_c2 = 1;
			}
		break;
		case 2:
		if($x_dias_transcurridos_c2 > 28){
			$x_cambia_cliclo_c2 = 1;
			}
		break;
		case 3:
		if($x_dias_transcurridos_c2 > 34){
			$x_cambia_cliclo_c2 = 1;
			}
			break;
		case 4;
		if($x_dias_transcurridos_c2 > 30){
			$x_cambia_cliclo_c2 = 1;
			}		
		
		
		
		}
	
	
	
	}else{
		$x_cambia_cliclo_c2 = 0;		
		}
	
	
	
	echo "busca fecha tarea ".$sqlPPC2."<br>";
	
	if(!empty($x_tarea_ppc2)){
	$sqlFechappc2 = "SELECT * FROM  crm_tarea_cv WHERE crm_tarea_id = $x_tarea_ppc2 ";
	$rsFechappc2 = phpmkr_query($sqlFechappc2,$conn)or die ("Error al seleccionar la fechappc1a".phpmkr_error()."sql:".$sqlFechappc2);
	$rowfechappc2 = phpmkr_fetch_array($rsFechappc2);
	$x_fecha_promesa_pago_c2 = $rowfechapp2["promesa_pago"];
	$x_crm_tarea_cv_ppc2 = $rowfechapp2["crm_tarea_cv_id"] +0;
	

	}
	if($x_crm_tarea_cv_ppc2 < 1){
		if(!empty($x_tarea_c2_id )){
		// no tenemos promesa de pago; se debe remplazar esa fecha por la fecha de impresion de la carta;
	$sqlFechappc2 = "SELECT * FROM  crm_tarea_cv WHERE crm_tarea_id = $x_tarea_c2_id ";
	$rsFechappc2 = phpmkr_query($sqlFechappc2,$conn)or die ("Error al seleccionar la fechappc1a".phpmkr_error()."sql:".$sqlFechappc2);
	$rowfechappc2 = phpmkr_fetch_array($rsFechappc2);
	$x_fecha_promesa_pago_c2 = $rowfechapp2["fecha_entrega"]; // fecha ne que se entrego la carta
		}
	
		}
	// tenemos la fecha de la tarea y tenemos la fecha de la promesa de pago..
	// buscamos si relizo algun pago desde la fecha de la atrea hasta la fecha e la promesa de pago.
	
	
	////////////////////// esta la fecha en que se registro la tarea...////////////////////////////////////////
	$x_pago_registrado_ppc2 = 0;
	$sqlBuscapagoPPC2 = "SELECT COUNT(*) AS pago_registrado FROM recibo JOIN recibo_vencimiento ON recibo_vencimiento.recibo_id = recibo.recibo_id";
	$sqlBuscapagoPPC2 .= " JOIN vencimiento ON vencimiento.vencimiento_id = recibo_vencimiento.vencimiento_id ";
	$sqlBuscapagoPPC2 .= " WHERE vencimiento.credito_id = $x_credito_id ";
	$sqlBuscapagoPPC2 .= " AND recibo.fecha_pago >= \"$x_fecha_registro_tarea\" "; 
	$rsbuscaPPC2 = phpmkr_query($sqlBuscapagoPPC2,$conn) or die("error al buscar el pago de ppc2".phpmkr_error()."sql:".$sqlBuscapagoPPC2);
	$rowbuscapagoPPC2 = phpmkr_fetch_array($rsbuscaPPC2);
	$x_pago_registrado_ppc2 = $rowbuscapagoPPC2["pago_registrado"]+0;
	
	echo "<BR><BR>NUMERO DE PAGOS VENCIDOS CARTA 2 --".$x_numero_de_vencimientos_vencidos."<BR><BR>"; 
	
	
	echo "pagos registrados".$x_pago_registrado_ppc2."<br>";
	echo "sql busca pago".$sqlBuscapagoPPC2."<br>";
	
	$x_cumple_promesa_pago = 0;
	
	
	 if ($x_cambia_cliclo_c2 == 0){
	if ($x_pago_registrado_ppc2 > 0){
		$x_cumple_promesa_pago = 1;
		}
		echo "cumple promesa de pago".$x_cumple_promesa_pago."<br>";
		echo "nuemo de vencimeintos vencidos".$x_numero_de_vencimientos_vencidos."<br>";
		echo "forma de pago".$x_forma_pago_id."<br>"; 
	if($x_forma_pago_id == 1){
			// semanal
			if($x_cumple_promesa_pago == 1){
				// si cumplio la promesa de pago
				if($x_numero_de_vencimientos_vencidos >= 6){
					$x_ciclo_carta_2 = 0;
					}else{
						$x_ciclo_carta_2 = 1;
						// se cicla
						}
				
				}else{
					// no cumplio la promesa de pago
					if($x_numero_de_vencimientos_vencidos < 5){
						$x_ciclo_carta_2 = 1; // no pago pero no debe mas de 5 5vencimiento. lo ciclamos en esta etapa
						}else{
							$x_ciclo_carta_2 = 0; // lo mandamos a la siguiente etapa
							}				
					}
			
			}else if(($x_forma_pago_id == 2) || ($x_forma_pago_id == 4)){
				// catorcenal ó quincenal
					if($x_cumple_promesa_pago == 1){
					// si cumplio la promesa de pago
					if($x_numero_de_vencimientos_vencidos >= 4){
						$x_ciclo_carta_2 = 0;
						}else{
							$x_ciclo_carta_2 = 1;
							// se cicla
							}					
					}else{
						// no cumplio la promesa de pago
						if($x_numero_de_vencimientos_vencidos < 4){
							$x_ciclo_carta_2 = 1; // no pago pero no debe mas de 5 5vencimiento. lo ciclamos en esta etapa
							}else{
								$x_ciclo_carta_2 = 0; // lo mandamos a la siguiente etapa
								}				
						}
				
				}else if($x_forma_pago_id == 3){
					//MENSUAL
					if($x_cumple_promesa_pago == 1){
					// si cumplio la promesa de pago
					if($x_numero_de_vencimientos_vencidos >= 4){
						$x_ciclo_carta_2 = 0;
						}else{
							$x_ciclo_carta_2 = 1;
							// se cicla
							}					
					}else{
						// no cumplio la promesa de pago
						if($x_numero_de_vencimientos_vencidos < 4){
							$x_ciclo_carta_2 = 1; // no pago pero no debe mas de 4 vencimiento. lo ciclamos en esta etapa
							}else{
								$x_ciclo_carta_2 = 0; // lo mandamos a la siguiente etapa
								}				
						}
					
					}
	 } else{
		 $x_ciclo_carta_2 = 0;		 
		 }
	echo "CICLO CARTA 2".$x_ciclo_carta_2."<BR>";
	
	
	
	
	
	
	$sqlCarta3 = "SELECT COUNT(*) AS carta_3 FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND  crm_tarea_tipo_id = 10 AND orden= 12";
	$rsCarta3 = phpmkr_query($sqlCarta3,$conn)or die ("Error al seleccionar la carta 3".phpmkr_error()."sql:".$sqlCarta3);
	$rowCarata3 = phpmkr_fetch_array($rsCarta3);
	$x_carta_3 = $rowCarata3["carta_3"] +0;
	echo "carta 3..". $x_carta_3."<br>";
	
		// SELECCIONAMOS LA PP DE CARTA 3
		if($x_carta_3 > 0){
	$sqlPPC3 = "SELECT * FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id  AND crm_tarea_tipo_id = 5 AND orden = 13 ";
	$rsppc3 = phpmkr_query($sqlPPC3,$conn)or die ("Error al seleccioanr la pp c2".phpmkr_error()."sql:".$sqlPPC3);
	$rowppc3 = phpmkr_fetch_array($rsppc3);
	$x_fecha_registro_tarea_3 = $rowppc3["fecha_registro"];
	$x_tarea_ppc3 =$rowppc3["crm_tarea_id"];
	$x_caso_ppc3 = $rowppc3["crm_caso_id"];
	
	
	$sqlCarta3_id = "SELECT crm_tarea_id  FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND  crm_tarea_tipo_id = 10 AND orden= 12";
	$rsCarta3_id = phpmkr_query($sqlCarta3_id,$conn)or die ("Error al seleccionar la carta 3".phpmkr_error()."sql:".$sqlCarta3_id);
	$rowCarata3_id = phpmkr_fetch_array($rsCarta3_id);
	$x_carta_3_id = $rowCarata3_id["crm_tarea_id"];
	
	$sqlCarta3_id = "SELECT crm_tarea_id  FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND  crm_tarea_tipo_id = 10 AND orden= 12 ORDER BY crm_tarea_id DESC LIMIT 0,1 ";
	$rsCarta3_id = phpmkr_query($sqlCarta3_id,$conn)or die ("Error al seleccionar la carta 3".phpmkr_error()."sql:".$sqlCarta3_id);
	$rowCarata3_id = phpmkr_fetch_array($rsCarta3_id);
	$x_carta_3_id_PP_U = $rowCarata3_id["crm_tarea_id"];
	
	$sqlFechappc3 = "SELECT * FROM  crm_tarea_cv WHERE crm_tarea_id = $x_carta_3_id_PP_U ";
	$rsFechappc3 = phpmkr_query($sqlFechappc3,$conn)or die ("Error al seleccionar la fechappc1".phpmkr_error()."sql:".$sqlFechappc3);
	$rowfechappc3 = phpmkr_fetch_array($rsFechappc3);
	$x_fecha_promesa_pago_c3_pp_u = $rowfechapp3["promesa_pago"];
	
	
	
	#########################################################
	#############     CICLO POR DIAS    #####################
	#########################################################
	$sqlFechaCarata3 = "SELECT fecha_entrega FROM  crm_tarea_cv WHERE crm_tarea_id 	 = $x_carta_3_id ";
	$rsFechaCarta3 = phpmkr_query($sqlFechaCarata3,$conn) or die ("Error al seleccionar la fecha de entrega de la carata". phpmkr_error()."sql :".$sqlFechaCarata3);
	$rowFechaCarta3 = phpmkr_fetch_array($rsFechaCarta3);
	$x_fecha_imprime_carat3 = $rowFechaCarta3["fecha_entrega"];
	
	
		
	$x_dias_transcurridos_c3  = datediff('d', $x_fecha_promesa_pago_c3_pp_u, $currdate, false);
	$x_cambia_cliclo_c3 = 0;
	// verificar la forma  de pago del credito
	
	echo "<br>dias transcurridos desde la impresion de la carat 3".$x_dias_transcurridos_c3 ."<br>";
	
	// fomas de pago
	// 1 = semanal
	// 2 = catorcenal
	// 3 = mensual
	// 4 = quincenal
	
	if($x_vencimeintos_pendientes_de_pago == 0){
	switch($x_forma_pago_id)
	{
		case 1:
		if($x_dias_transcurridos_c3 > 20){
			$x_cambia_cliclo_c3 = 1;
			}
		break;
		case 2:
		if($x_dias_transcurridos_c3 > 28){
			$x_cambia_cliclo_c3 = 1;
			}
		break;
		case 3:
		if($x_dias_transcurridos_c3 > 34){
			$x_cambia_cliclo_c3 = 1;
			}
			break;
		case 4;
		if($x_dias_transcurridos_c3 > 30){
			$x_cambia_cliclo_c3 = 1;
			}		
		
		
		
		}
	}else{
		$x_cambia_cliclo_c3 = 0;		
		}
		
		echo "cambia ciclo 3".$x_cambia_cliclo_c3."<br>";
	
	if ($x_tarea_ppc3 > 0){
	
	$sqlFechappc3 = "SELECT * FROM  crm_tarea_cv WHERE crm_tarea_id = $x_tarea_ppc3 ";
	$rsFechappc3 = phpmkr_query($sqlFechappc3,$conn)or die ("Error al seleccionar la fechappc1".phpmkr_error()."sql:".$sqlFechappc3);
	$rowfechappc3 = phpmkr_fetch_array($rsFechappc3);
	$x_fecha_promesa_pago_c3 = $rowfechapp3["promesa_pago"];
	
	}
	
		if(!empty($x_tarea_ppc3)){
	$sqlFechappc3 = "SELECT * FROM  crm_tarea_cv WHERE crm_tarea_id = $x_tarea_ppc3 ";
	$rsFechappc3 = phpmkr_query($sqlFechappc3,$conn)or die ("Error al seleccionar la fechappc1a".phpmkr_error()."sql:".$sqlFechappc3);
	$rowfechappc3 = phpmkr_fetch_array($rsFechappc3);
	$x_fecha_promesa_pago_c3 = $rowfechapp3["promesa_pago"];
	

	
	}
	
	$x_pago_registrado_ppc3 = 0;
	// tenemos la fecha de la tarea y tenemos la fecha de la promesa de pago..
	// buscamos si relizo algun pago desde la fecha de la atrea hasta la fecha e la promesa de pago.
	if(!empty($x_fecha_registro_tarea_3)){
	$sqlBuscapagoPPC3 = "SELECT COUNT(*) AS pago_registrado FROM recibo JOIN recibo_vencimiento ON recibo_vencimiento.recibo_id = recibo.recibo_id";
	$sqlBuscapagoPPC3 .= " JOIN vencimiento ON vencimiento.vencimiento_id = recibo_vencimiento.vencimiento_id ";
	$sqlBuscapagoPPC3 .= " WHERE vencimiento.credito_id = $x_credito_id ";
	$sqlBuscapagoPPC3 .= " AND recibo.fecha_pago >= \"$x_fecha_registro_tarea_3\" "; 
	$rsbuscaPPC3 = phpmkr_query($sqlBuscapagoPPC3,$conn) or die("error al buscar el pago de ppc3".phpmkr_error()."sql:".$sqlBuscapagoPPC3);
	$rowbuscapagoPPC3 = phpmkr_fetch_array($rsbuscaPPC3);
		}
	$x_pago_registrado_ppc3 = $rowbuscapagoPPC3["pago_registrado"]+0;
	$x_ciclo_carta_3 = 0;
	echo "PAGO REG".$x_pago_registrado_ppc3."<BR>";
	echo "SQL:".$sqlBuscapagoPPC3."<br>";
		}
		
		
		
	if($x_cambia_cliclo_c3 == 0){
	$x_cumple_promesa_pago3 = 0;
	if ($x_pago_registrado_ppc3 > 0){
		$x_cumple_promesa_pago3 = 1;
		}
	
	if($x_forma_pago_id == 1){
			// semanal
			if($x_cumple_promesa_pago3 == 1){
				// si cumplio la promesa de pago
				if($x_numero_de_vencimientos_vencidos >= 8){
					$x_ciclo_carta_3 = 0;
					}else{
						$x_ciclo_carta_3 = 1;
						// se cicla
						}
				
				}else{
					// no cumplio la promesa de pago
					if($x_numero_de_vencimientos_vencidos <= 6){
						$x_ciclo_carta_3 = 1; // no pago pero no debe mas de 6 vencimiento. lo ciclamos en esta etapa
						}else{
							$x_ciclo_carta_3 = 0; // lo mandamos a la siguiente etapa
							}				
					}
			
			}else if(($x_forma_pago_id == 2) || ($x_forma_pago_id == 4)){
				// catorcenal ó quincenal
					if($x_cumple_promesa_pago3 == 1){
					// si cumplio la promesa de pago
					if($x_numero_de_vencimientos_vencidos >= 6){
						$x_ciclo_carta_3 = 0;
						}else{
							$x_ciclo_carta_3 = 1;
							// se cicla
							}					
					}else{
						// no cumplio la promesa de pago
						if($x_numero_de_vencimientos_vencidos <= 5){
							$x_ciclo_carta_3 = 1; // no pago pero no debe mas de 5 5vencimiento. lo ciclamos en esta etapa
							}else{
								$x_ciclo_carta_3 = 0; // lo mandamos a la siguiente etapa
								}				
						}
				
				}else if($x_forma_pago_id == 3){
					//MENSUAL
					if($x_cumple_promesa_pago3 == 1){
					// si cumplio la promesa de pago
					if($x_numero_de_vencimientos_vencidos >= 5){
						$x_ciclo_carta_3 = 0;
						}else{
							$x_ciclo_carta_3 = 1;
							// se cicla
							}					
					}else{
						// no cumplio la promesa de pago
						if($x_numero_de_vencimientos_vencidos < 5){
							$x_ciclo_carta_3 = 1; // no pago pero no debe mas de 5 5vencimiento. lo ciclamos en esta etapa
							}else{
								$x_ciclo_carta_3 = 0; // lo mandamos a la siguiente etapa
								}				
						}
					
					}
	}else{ $x_ciclo_carta_3 = 0;}
					
					
					
	echo "CICLO CARATA 3".$x_ciclo_carta_3."<BR>";
	
	
	
	
	
	$sqlCartaD = "SELECT COUNT(*) AS carta_D FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND  crm_tarea_tipo_id = 12 AND orden= 20";
	$rsCartaD = phpmkr_query($sqlCartaD,$conn)or die ("Error al seleccionar la carta 3".phpmkr_error()."sql:".$sqlCartaD);
	$rowCarataD = phpmkr_fetch_array($rsCartaD);
	$x_carta_D = $rowCarataD["carta_D"] +0;
	
	if($x_carta_D > 0){
       //seleccionasmos la PP de la demanda
	$sqlPPCD = "SELECT * FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id  AND crm_tarea_tipo_id = 5 AND orden = 24 ";
	$rsppcD = phpmkr_query($sqlPPCD,$conn)or die ("Error al seleccioanr la pp c2".phpmkr_error()."sql:".$sqlPPCD);
	$rowppcD = phpmkr_fetch_array($rsppcD);
	$x_fecha_registro_tarea_D = $rowppcD["fecha_registro"];
	$x_tarea_ppcD =$rowppcD["crm_tarea_id"];
	$x_caso_ppcD = $rowppcD["crm_caso_id"];
	   
	   
		}
	
	if(!empty($x_fecha_registro_tarea_D )){
	$sqlBuscapagoPPC3 = "SELECT COUNT(*) AS pago_registrado FROM recibo JOIN recibo_vencimiento ON recibo_vencimiento.recibo_id = recibo.recibo_id";
	$sqlBuscapagoPPC3 .= " JOIN vencimiento ON vencimiento.vencimiento_id = recibo_vencimiento.vencimiento_id ";
	$sqlBuscapagoPPC3 .= " WHERE vencimiento.credito_id = $x_credito_id ";
	$sqlBuscapagoPPC3 .= " AND recibo.fecha_pago >= \"$x_fecha_registro_tarea_D\" "; 
	$rsbuscaPPC3 = phpmkr_query($sqlBuscapagoPPC3,$conn) or die("error al buscar el pago de ppc3".phpmkr_error()."sql:".$sqlBuscapagoPPC3);
	$rowbuscapagoPPC3 = phpmkr_fetch_array($rsbuscaPPC3);
	}
	$x_pago_registrado_ppcd = $rowbuscapagoPPC3["pago_registrado"]+0;
	echo "carta D..". $x_carta_D."<br>";
	echo "pago registrado cc".$sqlBuscapagoPPC3."<br>";
	echo "pago registrado cc".$x_pago_registrado_ppc3."<br>";
	if($x_pago_registrado_ppcd == 0){
		$x_ciclo_carta_D = 0;
		}else{
			$x_ciclo_carta_D = 1;
			}
	
	$sqlSolId = "SELECT solicitud_id FROM credito WHERE credito_id = $x_credito_id";
	$rsSolId = phpmkr_query($sqlSolId,$conn) or die ("Error al seleccionar el id de la solicitud del credito".phpmkr_error()."sql:");
	$rowSolId = phpmkr_fetch_array($rsSolId);
	$x_solicitud_id_c = $rowSolId["solicitud_id"];
		
	// seleccionamos el promotor
	$sqlPromotor = "SELECT promotor_id FROM solicitud WHERE solicitud_id = $x_solicitud_id_c";
	$rsPromotor = phpmkr_query($sqlPromotor,$conn) or die ("Error al seleccionar el promotor del credito".phpmkr_error()."sql :".$sqlPromotor);
	$rowPromotor = phpmkr_fetch_array($rsPromotor);
	$x_promotor_id_c = $rowPromotor["promotor_id"];
	
	
	//SELECCIONAMOS LOS DATOS  DEL CREDITO, PARA SABER SI YA EL CAMPO DE PENALIZACION EN SUS ESTRUCTRUCATURA
	$sqlDatosCredito = "SELECT penalizacion,forma_pago_id FROM credito WHERE credito_id = $x_credito_id	";
	$rsDatosCredito = phpmkr_query($sqlDatosCredito,$conn)or die("error al seleccionar los datosd el credito penalizacion campo nuevo".phpmkr_error()."sql:".$sqlDatosCredito);
	$rowDatosCredito = phpmkr_fetch_array($rsDatosCredito);
	$x_penalizacion_a = $rowDatosCredito["penalizacion"];
	$x_forma_de_pago = $rowDatosCredito["forma_pago_id"];
	
	// si el campo penalizacion-a esta lleno significa que el tipod e credito es de ls nuevos; si no esta lleno el credito es de los viejitos y la penaliacion se gestinoara por MONTO no por registro de penalizacion
	
//	; tomando en cuenta la tabla con la que se genera las penalizacones;
$x_comision_generada = 0;

	if($x_cambia_cliclo_c1 == 0){
		echo "entro a cambia ciclo 0";
	if($x_penalizacion_a > 0){
		echo "penalizacion_a mayor a 0";
		// si es mayo de 0 solo se busca si la penalizcion ya existe
		$sqlPenalizacion = "SELECT COUNT(*) AS comision_generada FROM vencimiento WHERE credito_id = $x_credito_id and vencimiento_num	= 3001";
		$rsPenalizacion = phpmkr_query($sqlPenalizacion,$conn) or die ("Error al seelcccionar la penalizacion en el credito".phpmkr_error()."SQL;".$sqlPenalizacion);
		$rowPenalizacion = phpmkr_fetch_array($rsPenalizacion);
		$x_comision_generada = $rowPenalizacion["comision_generada"];
		
		//else es los creditos viejos y no tiene ni tendra nunca una penalizacion generada, se hace el calculo por monto
		}else{
		// se hace el calculo de la comiosn	
		$sqlPenalizacion = "SELECT total_venc, interes_moratorio,iva_mor FROM vencimiento WHERE credito_id = $x_credito_id and vencimiento_num	= 1";
		$rsPenalizacion = phpmkr_query($sqlPenalizacion,$conn) or die ("erro al seleccionar la comison en casaos anteriores".phpmkr_error()."sql:".$sqlPenalizacion);
		$rowPenalizacion = phpmkr_fetch_array($rsPenalizacion);
		$x_monto_vencimeinto = $rowPenalizacion["total_venc"];
		$x_monto_moratorios = $rowPenalizacion["interes_moratorio"];
		$x_monto_iva_mora = $rowPenalizacion["iva_mor"];		
		$x_monto_pago = $x_monto_vencimeinto -($x_monto_moratorios  + $x_monto_iva_mora);
		
		
		#buscamos el monto vencido
		$sqlBuscaMontoVenc = " SELECT SUM(total_venc) AS monto_vencido FROM vencimiento WHERE credito_id = $x_credito_id and  vencimiento_status_id in (3,6)";
		$rsBuscaMontoVenc = phpmkr_query($sqlBuscaMontoVenc,$conn) or die ("Error al seleccionar el monto del vencimiento".phpmkr_error()."sql:".$sqlBuscaMontoVenc);
		$rowBuscaMontoVenc = phpmkr_fetch_array($rsBuscaMontoVenc);
		$x_monto_VVencido = $rowBuscaMontoVenc["monto_vencido"];
		 $x_monto_garantia_liquida = 0;
		
		
		if($x_forma_de_pago == 1){
			// la forma de pago es  semanal
			$x_total_vencido_para_generar =  $x_monto_pago * 3;
			}else{
				// la forma de pago es mensual, quincenal, o catorcenal
				$x_total_vencido_para_generar =  $x_monto_pago * 2;
				}
			
			echo "monto vencido ".$x_monto_VVencido."<br>";
			echo "monto necesario para genera".$x_total_vencido_para_generar."<br>";
			if($x_monto_VVencido > $x_total_vencido_para_generar ){
				// el importe vencido ya generaria comison de cobranza si estuveira en le nuevo tipo de credito asi que que se toma como si ya existiera la comsion de cobranza
				echo "el monto vencido es mayor al monto para generar comision; se pone comison en 1";
				$x_comision_generada = 1;
				}
			}
		//SELECCIONAMOS EL MOSNTO DE LA COMISION DE COBRANZA
		//$x_comision_generada = 0;
	}else{
		$x_comision_generada = 1;
		
		}
		
		echo "comision generada----".$x_comision_generada."----<br>";
		echo "cliclo carta 1---".$x_ciclo_carta_1." ---<br>";
		
		echo "carata d".$x_carta_D."<br>";
		echo "ciclo crata d".$x_ciclo_carta_D."<br>";
			
	
	################################################################
	################################################################
	################################################################
	
	//$x_comision_generada = 1;
	//$x_ciclo_carta_2 = 1;
	//$x_ciclo_carta_3 = 1;
	
	
	################################################################	
	################################################################
	################################################################	
	
	
	
	if($x_carta_1 == 0){
	###########################################################################################################################################
	###########################################################################################################################################
	###################################################               CARTA 1              ####################################################
	###########################################################################################################################################
	###########################################################################################################################################
	//PRMIER CASO CARATA 1	
	
	// antes de insertar la tarea de carta 1 verificamos que no existe dicha tarea.
		
		#echo "entra a CARTA 1<br>";
		
		if($x_promotor_id_c > 0){
			// buscamos a que sucursal pertence el promotor
			$sqlSucursal = "SELECT sucursal_id FROM promotor WHERE promotor_id = $x_promotor_id_c";
			$rsSucursal = phpmkr_query($sqlSucursal,$conn) or die ("Error al seleccionar la sucursal". phpmkr_error()."Sql:".$sqlSucursal); 
			$rowSucuersal = phpmkr_fetch_array($rsSucursal);
			$x_sucursal_id_c = $rowSucuersal["sucursal_id"];
				
			
			if($x_sucursal_id_c > 0){
				// si ya tenbemos la sucursal, buscamos el representante de essa sucursal
				$sqlResponsable = "SELECT usuario_id FROM responsable_sucursal WHERE sucursal_id = $x_sucursal_id_c ";
				$rsResponsable = phpmkr_query($sqlResponsable,$conn) or die ("error al seleccionar el usuario del responsable de suscursal".phpmkr_error()."sql:".$sqlResponsable);
				$rowResponsable = phpmkr_fetch_array($rsResponsable);
				$x_responsable_susursal_usuario_id = $rowResponsable["usuario_id"];	
						
				
				}
			} 
			
		
		if($x_numero_de_vencimientos_vencidos > 1){
			// cambios de dueño el caso y la tarea.
			if($x_responsable_susursal_usuario_id > 0){
			$sqlCasoResponsable = "SELECT count(*) as caso_abierto
						FROM 
						crm_caso
						WHERE 
						crm_caso.crm_caso_tipo_id = 3
						AND crm_caso.crm_caso_status_id = 1
						AND crm_caso.credito_id = $x_credito_id
						AND crm_caso.responsable = $x_responsable_susursal_usuario_id";
						
			$rsCasoResponsable = phpmkr_query($sqlCasoResponsable,$conn) or die("Error al seleccionar los casos del responsabel de sucursal".phpmkr_error()."sql:".$sqlCasoResponsable);			
			$rowCasoResponsable = phpmkr_fetch_array($rsCasoResponsable);
				
			$x_caso_responsabel_id = $rowCasoResponsable["crm_caso_id"];
			$x_caso_id_e = $rowCasoResponsable["caso_id"];
		}
			
			//   1.- cerramos las tareas, cerramos el caso, porque se agragra una nueva tarea pero ahora sera para el promotor
			
			//   2.- Abrimos una nueva tarea caso para el asesor de credito.
			if(!empty($x_caso_responsabel_id)){
			$sqlUpdateTareas = "UPDATE crm_tarea SET crm_tarea_status_id = 4 WHERE crm_tarea.crm_caso_id = $x_caso_responsabel_id "; // cerrramos la tarea
			$rsUpdateTareas = phpmkr_query($sqlUpdateTareas,$conn) or die ("Error al cerrar las tareas del responsable de sucrsal que pasan al asesor de credito".phpmkr_error()."sql:".$sqlUpdateTareas);
			"ACTIALIZA USUARIO TAREA ".$sqlUpdateTareas."<BR>";
			}
			
			//$sqlUpdateCasos = "UPDATE crm_caso SET crm_caso_status_id = 4 WHERE crm_caso_id = $x_caso_responsabel_id " ;// cerrado por cambio de ETAPA
			//$rsUpdateCasos = phpmkr_query($sqlUpdateCasos,$conn) or die ("Error al actualizar los casos del responsable de suscursal que pasan al asesor de credito".phpmkr_error()."sql :".$sqlUpdateCasos);
			// no se cierra el caso para seguir usandolo con las demas fases.
			
			// selelccionamos el usuario del promotor
			$sSqlWrk = "SELECT usuario_id
						FROM 
						promotor
						WHERE 
						promotor.promotor_id = $x_promotor_id_c ";		
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_promotor_usuario_id = $datawrk["usuario_id"];
			@phpmkr_free_result($rswrk);
			
			
			
			
			
			// seleccionamos los datos para la nueva tarea
				
				$sSqlWrk = "SELECT *
							FROM 
							crm_playlist
							WHERE 
							crm_playlist.crm_caso_tipo_id = 3
							AND crm_playlist.orden = 4 "; // orden 4 CARTA  1		
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
	
	
	
				$x_origen = 1;  // el origen se podria cambiar a responsable de sucursal.
				$x_bitacora = "Cartera Vencida  SE PASA A ETAPA 2  CARTA 1- (".FormatDateTime($currdate,7)." - $currtime)";
			
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


		
		
		// seleccionamos el usuario del promotor 7// CARAT 1 EL RESPONSABLE DE LA TAREA ES EL ASESOR DEL CREDITO
		
				
		$sSqlWrk = "
		SELECT usuario_id
		FROM 
			promotor
		WHERE 
			promotor.promotor_id = $x_promotor_id_c ";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		#echo  $sqlPromotor. $sSqlWrk."<br>";
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_usuario_id = $datawrk["usuario_id"];
		@phpmkr_free_result($rswrk);
		
	
			// se debe verificar antes de ingresar la tarea que no exista ninguna tarea para el caso de este tipo
			$sqlBuscaTarea =  "SELECT COUNT(*) AS tareas_exist from  crm_tarea where crm_caso_id = $x_crm_caso_id AND crm_tarea_tipo_id = $x_tarea_tipo_id ";
			$sqlBuscaTarea .= " AND crm_tarea_status_id IN (1,2) AND destino = $x_usuario_id";	
			$rsBuscaTarea = phpmkr_query($sqlBuscaTarea,$conn) or die ("Erro al insertar en tarea 1".phpmkr_error()."sql:".$sqlBuscaTarea);
			$rowBuscaTarea = phpmkr_fetch_array($rsBuscaTarea);
			$x_tareas_abiertas = $rowBuscaTarea["tareas_exist"];
			
			#echo "<br><br> TREAS ABIERTAS ".$x_tareas_abiertas."<BR><BR>".$sqlBuscaTarea."<BR><BR>";
			if($x_tareas_abiertas == 0){
			$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 7, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
			// la tarea la ejecuta el asesor de crédito
			$rs = phpmkr_query($sSql,$conn) or die("Error al seleccionar insertar en tarea para promotor".phpmkr_error()."sql:".$sSql);
		echo "INSERTAMOS TAREA CARTA 1 desde el segundo cliclo<BR>".$sSql."<BR>";
			//$x_result = phpmkr_query($sSql, $conn);
			$x_tarea_id = mysql_insert_id();
	
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
			
			
		########################################################################################################################################################
		############################################################ TAREAS DIARIAS PARA PROMOTORES	############################################################
		########################################################################################################################################################
		##credamos las lista de los promotores con la tareas diarias.		
		## seleccionaos la soza de la solcitud		
		$sqlZonaId = "SELECT dia FROM  zona JOIN solicitud ON solicitud.zona_id = zona.zona_id WHERE solicitud.solicitud_id = $x_solicitud_id_c";
		$rsZonaId = phpmkr_query($sqlZonaId,$conn) or die ("Error al seleccionar el dia de la zona".phpmkr_error()."sql:".$sqlZonaId);
		$rowZonaId = phpmkr_fetch_array($rsZonaId);
		$x_dia_zona = $rowZonaId["dia"];
		
		$x_fecha_tarea_f = $currdate;
		$x_dia_zona_f = $x_dia_zona;
		$x_promotor_id_f = $x_promotor_id_c ;
		$x_caso_id_f = $x_crm_caso_id;
		$x_tarea_id_f = $x_tarea_id;
		
		
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
		#echo "BUSCA TAREAS".$sqlBuscaatreaAsignada."<BR>";
		$rowBuscaTareaAsignada = phpmkr_fetch_array($rsBuscatareaAsignada);
		$x_tareas_asignadas_del_caso = $rowBuscaTareaAsignada["atreas_asignadas"];
		#echo "TAREAS ASIGNADAS ".$x_tareas_asignadas_del_caso."<br>";
		//se inserta la tarea en la lista de las actividades diarias del promotor
		if ($x_tareas_asignadas_del_caso < 1){
		$sqlInsertListaTarea = "INSERT INTO `tarea_diaria_promotor`";
		$sqlInsertListaTarea .= " (`tarea_diaria_promotor_id`, `promotor_id`, `zona_id`, `dia_semana`, `fecha_ingreso`, `fecha_lista`, `caso_id`, ";
		$sqlInsertListaTarea .= " `tarea_id`, `reingreso`, `fase`, `status_tarea`, `credito_id`) ";
		$sqlInsertListaTarea .= "VALUES (NULL, $x_promotor_id_f, $x_dia_zona_f , $x_dia_zona_f, \"$currdate\",\"$x_fecha_tarea_f\", $x_caso_id_f, $x_tarea_id_f, '0', '2', '1', $x_credito_id);";
		$rsInsertListaTarea = phpmkr_query($sqlInsertListaTarea,$conn)or die("Error al insertar en lista diaria tareas".phpmkr_error()."sql:".$sqlInsertListaTarea);	 
		}
		
		
			}// tareas registradas = 0
			}// vencimeitos vencidos mayor de UNO
		
	}//CARTA 1
	
	
	
	if(($x_carta_1 > 0) &&($x_comision_generada < 1)){
		// ya se le genero la carta 1
		// ya esta en fase 3
		// ya tiene una o mas promesas de pago...
		// SE genera una promesa de pagao al promotor de credito (angelica, monica, jose) 
		// carta 1 ya existe, pero debemos de verificar que la tarea esta vencida o esta cerrada
		// si ya se vencio la tarea o ya se netrego la carta entonces si ya podemos programar la sigueinte tarea
		// de lo contrario si el credito tuvuera mas de dos vencidos.. generaria la carata y tambien generaria la PP 
		
		#verificamos que no tenga ninguna PP con status de pendiente;
		echo "entra  pp carta 1 <br>";
		
		$x_carta_1PP = 0;
		$sqlCarta1PP = "SELECT COUNT(*) AS carta_1_PP FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND crm_tarea_tipo_id = 5 AND orden= 5 AND crm_tarea_status_id in (1,2,3)";
		$rsCarta1PP = phpmkr_query($sqlCarta1PP,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCarta1PP);
		$rowCarata1PP = phpmkr_fetch_array($rsCarta1PP);
		$x_carta_1PP = $rowCarata1PP["carta_1_PP"] +0;
		echo "busca PP pendiente".$sqlCarta1PP ."<br><br>";
		
		// contamos si ya tiene una PP pero esta vencida; si es asi... buscamos su promesa de pago 
		$x_carta_1PP_vencida = 0;
		$sqlCarta1PP_vencida = "SELECT COUNT(*) AS carta_1_PP_v FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND crm_tarea_tipo_id = 5 AND orden= 5 AND crm_tarea_status_id in (2,3)";
		$rsCarta1PP_vencida = phpmkr_query($sqlCarta1PP_vencida,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCarta1PP_vencida);
		$rowCarata1PP_vencida = phpmkr_fetch_array($rsCarta1PP_vencida);
		$x_carta_1PP_vencida = $rowCarata1PP_vencida["carta_1_PP_v"] +0;
		echo "busca PP vencidas,o cerradas".$sqlCarta1PP ."<br><br>";
		#echo "promesa de pago".$sqlCarta1PP."<br>"; 
		#echo "promesas de pago para las cartas = ". $x_carta_1PP."<br>";
		if(($x_carta_1PP < 1)){
		echo "no hay promesa de pago pendiente<, ni vencida entro y genero la PPC1 <br>";	
			
		#SELECCIONAMOS LOS DATOS DE  LA TAREA
		
		$sSqlWrk = "
		SELECT *
		FROM 
			crm_playlist
		WHERE 
			crm_playlist.crm_caso_tipo_id = 3
			AND crm_playlist.orden = 5 "; // PP carat 1
		
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
		
		
		
		
		
		// seleccionamos el promotor
		$sqlPromotor = "SELECT promotor_id FROM solicitud WHERE solicitud_id =   $x_solicitud_id_c";
		$rsPromotor = phpmkr_query($sqlPromotor,$conn) or die ("Error al seleccionar el promotor del credito".phpmkr_error()."sql :".$sqlPromotor);
		$rowPromotor = phpmkr_fetch_array($rsPromotor);
		$x_promotor_id_c = $rowPromotor["promotor_id"];
	#	 echo "sql promotor".$sqlPromotor."<br>";
		// selelccionamos el usuario del promotor
			$sSqlWrk = "SELECT usuario_id
						FROM 
						promotor
						WHERE 
						promotor.promotor_id = $x_promotor_id_c ";		
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_promotor_usuario_id = $datawrk["usuario_id"];
			$x_usuario_id = $x_promotor_usuario_id;
			@phpmkr_free_result($rswrk);
		
			
			



		if(($x_crm_caso_id +0 > 0)){
			#echo "crm_caso_id > 0 <br>";

			$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 2, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
		
			$x_result = phpmkr_query($sSql, $conn) or die ("error al inserta PP carat 1".phpmkr_error()."sql;".$sSql);
			$x_tarea_id_pp = mysql_insert_id();
			echo "INSERTA PROMESA DE PAGO CARTA 1<BR>".$sSql."<BR>";
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
		
		
		// buscar el dia que se asignara la tarea al prmotor
		########################################################################################################################################################
		############################################################ TAREAS DIARIAS PARA PROMOTORES	############################################################
		########################################################################################################################################################
		##credamos las lista de los promotores con la tareas diarias.		
		## seleccionaos la soza de la solcitud		
		$sqlZonaId = "SELECT dia FROM  zona JOIN solicitud ON solicitud.zona_id = zona.zona_id WHERE solicitud.solicitud_id = $x_solicitud_id_c";
		$rsZonaId = phpmkr_query($sqlZonaId,$conn) or die ("Error al seleccionar el dia de la zona".phpmkr_error()."sql:".$sqlZonaId);
		$rowZonaId = phpmkr_fetch_array($rsZonaId);
		$x_dia_zona = $rowZonaId["dia"];
		
		$x_fecha_tarea_f = $currdate;
		$x_dia_zona_f = $x_dia_zona;
		$x_promotor_id_f = $x_promotor_id_c ;
		$x_caso_id_f = $x_crm_caso_id;
		$x_tarea_id_f = $x_tarea_id_pp;
	
		
		
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
				
			#	echo "fecha dia------".$x_fecha_tarea_f."<br>";
				$temptime = strtotime($x_fecha_tarea_f);	
				$temptime = DateAdd('w',$x_dias_faltantes,$temptime);
				$fecha_nueva = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				$x_fecha_tarea_f = $fecha_nueva;	
				echo "nueva fecha...ESTA semana<br> dias faltantes".$x_dias_faltantes."<br>";			
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
					echo "nueva fecha...sigueinte semana<br> dias faltantes".$x_dias_faltantes."<br>";
			#		echo "DIA DE LA SEMANA EN FECHA".$x_dia_de_semana_en_fecha."<br>";
			#		echo "dia zona".$x_dia_zona_f;					
					}
			}
		
		$x_dias_agregados = calculaSemanas($conn, $x_fecha_tarea_f, $x_dia_zona_f, $x_promotor_id_f, $x_caso_id_f, $x_tarea_id_f);
		echo "dias agragados en PP CARTA 1 ------".$x_dias_agregados." ";
		
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
				echo "UPDATAE TREA 2".$sqlUpdateFecha."<br>";
			
			}
	#	echo "FECHA EJECUTA".$x_fecha_ejecuta_act;
		#####################################################################################################################
		##################################### TAREAS DIARIAS PROMOTOR #######################################################
		#####################################################################################################################
		
		// primero verifamos que la tarea aun no este en la lista, es decir, que se trate de la primera vez que entra al cliclo para este credito.
		$sqlBuscaatreaAsignada = "SELECT COUNT(*) AS atreas_asignadas FROM `tarea_diaria_promotor` WHERE fecha_ingreso =  \"$currdate\" AND promotor_id = $x_promotor_id_f ";
		$sqlBuscaatreaAsignada .= " AND `caso_id` = $x_caso_id_f";
		$rsBuscatareaAsignada = phpmkr_query($sqlBuscaatreaAsignada,$conn) or die("Erro al buscar atrea".phpmkr_error()."sql:".$sqlBuscaatreaAsignada);
		#echo "BUSCA TAREAS".$sqlBuscaatreaAsignada."<BR>";
		$rowBuscaTareaAsignada = phpmkr_fetch_array($rsBuscatareaAsignada);
		$x_tareas_asignadas_del_caso = $rowBuscaTareaAsignada["atreas_asignadas"];
#	echo "TAREAS ASIGNADAS ".$x_tareas_asignadas_del_caso."<br>";
		//se inserta la tarea en la lista de las actividades diarias del promotor
		if ($x_tareas_asignadas_del_caso < 1){
		$sqlInsertListaTarea = "INSERT INTO `tarea_diaria_promotor`";
		$sqlInsertListaTarea .= " (`tarea_diaria_promotor_id`, `promotor_id`, `zona_id`, `dia_semana`, `fecha_ingreso`, `fecha_lista`, `caso_id`, ";
		$sqlInsertListaTarea .= " `tarea_id`, `reingreso`, `fase`, `status_tarea`, `credito_id`) ";
		$sqlInsertListaTarea .= "VALUES (NULL, $x_promotor_id_f, $x_dia_zona_f , $x_dia_zona_f, \"$currdate\",\"$x_fecha_tarea_f\", $x_caso_id_f, $x_tarea_id_pp, '0', '3', '1', $x_credito_id);";
		$rsInsertListaTarea = phpmkr_query($sqlInsertListaTarea,$conn)or die("Error al insertar en lista diaria tareas".phpmkr_error()."sql:".$sqlInsertListaTarea);	 
		}
		
		
		
		
		
		
		}// CASO CRM > 0 DE CARTA 1
		
		}// SI NO EXISTE LA PROMESA DE PAGO PARA CARAT 1
		
		}// if carta 1>0 comision_genarada < 1

	
	if((($x_comision_generada + 0) > 0) && ($x_carta_2 == 0)){
		echo "entra a generar la carat 2";
		
		$x_promesas_c1 = 0;
		$x_fecha_promesa_pago_c1 = 0;
		//verificamos la Promesa de pago de C1
		$sqlCarta1 = "SELECT COUNT(*) AS crm_tarea_id FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND  crm_tarea_tipo_id = 8 AND orden= 4";
		echo "QUERY  ...C1 .... ".$sqlCarta1."<br>";
		$rsCarta1 = phpmkr_query($sqlCarta1,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCarta1);
		$rowCarata1 = phpmkr_fetch_array($rsCarta1);
		$x_carta_1_tarea_id = $rowCarata1["crm_tarea_id"];
		if($x_carta_1_tarea_id >0 ){
			//buscamos que ya tenga Una promesa de pago y que este vencida para entrar al ciclo			
			$sqlPPC1 = "SELECT * FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id  AND crm_tarea_tipo_id = 5 AND orden = 5 ";
			echo "---QUERY PPC1---- ".$sqlPPC1 ."<br>";
			$rsppc1 = phpmkr_query($sqlPPC1,$conn)or die ("Error al seleccioanr la pp c2".phpmkr_error()."sql:".$sqlPPC1);
			$rowppc1 = phpmkr_fetch_array($rsppc1);
			$x_fecha_registro_tarea = $rowppc1["fecha_registro"];
			$x_tarea_ppc1 =$rowppc1["crm_tarea_id"];
			$x_caso_ppc1 = $rowppc1["crm_caso_id"];			
			if($x_caso_ppc1> 0){
				echo "entra al if interno <br>";
				// la promesa de pago esta registrada; buscamos la fecha de pagoo de la promesa
				$sqlcrm_tarea_cv = "SELECT COUNT(*) AS promesas, promesa_pago  FROM crm_tarea_cv WHERE crm_tarea_id = $x_tarea_ppc1 and promesa_pago IS NOT NULL ";
				echo "sql pp de pago++ ".$sqlcrm_tarea_cv."<br>";
				$rscrm_tarea_cv = phpmkr_query($sqlcrm_tarea_cv,$conn)or die("Error al selecionar la fecha de promesa de pago".phpmkr_error()."sql:".$sqlcrm_tarea_cv);
				$rowcrm_tarea_cv = phpmkr_fetch_array($rscrm_tarea_cv);
				$x_promesas_c1 = $rowcrm_tarea_cv["promesas"];
				$x_fecha_promesa_pago_c1 = $rowcrm_tarea_cv["promesa_pago"]; 			
				}			
			}
			
			echo "pps  ".$x_promesas_c1."<br><br>";
			echo "fecha de la promesa de pago =".$x_fecha_promesa_pago_c1."<br>";;
		
		$x_hoy_Z = date("Y-m-d");
		if($x_promesas_c1 > 0){
			echo "promesas de pago mayor a 1<br>";
			if( $x_hoy_Z > $x_fecha_promesa_pago_c1){		
		#YA SE GENERO LA COMISON DE COBRANZA PERO AUN NO SE GENERA LA TAREA PARA IMPRIMIR  LA CARTA 2		
		#AQUI SE GENERA LA TREA DE CARATA 2 ;PROCESO IDENTICO A CARTA 1
		echo "entra a carta 2 <br>";
			###########################################################################################################################################
	###########################################################################################################################################
	###################################################               CARTA 2              ####################################################
	###########################################################################################################################################
	###########################################################################################################################################
	//SEGUNDO CASO CARTA 2			
	
			
					
			// seleccionamos los datos para la nueva tarea
				
				$sSqlWrk = "SELECT *
							FROM 
							crm_playlist
							WHERE 
							crm_playlist.crm_caso_tipo_id = 3
							AND crm_playlist.orden = 8 "; // orden 8 CARTA  2		
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
	
	
	
				$x_origen = 1;  // el origen se podria cambiar a responsable de sucursal.
				$x_bitacora = "Cartera Vencida  SE PASA A ETAPA 4  CARTA 2- (".FormatDateTime($currdate,7)." - $currtime)";
			
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


		
		
		// seleccionamos el usuario del GESTOR DE COBRANZA// CARAT 2 EL RESPONSABLE DE LA TAREA ES EL GESTOR DE COBRANZA HASTA AHORA LE SR RAUL
		
				
		$sSqlWrk = "
		SELECT usuario_id
		FROM 
			usuario
		WHERE 
			usuario_rol_id =  14 "; // GESTOR COBRANZA
			
			
		// el gestor de cobranza estara relaciona con los promotores
		// esto se hizo asi, porque si se le asignaran todos los casao al gestor de cobranza de todos lo promotores le generaria tareas para los clientes que son de colima y en este caso es imposible que un gestor de cobranza del df pueda tomar lo casoas de los clientes de colima.
		
		# seleccionamos los datos del promotor.
		$x_promotor_de_gestor = 0;
		$sqlpromotor = "SELECT promotor_id FROM solicitud WHERE solicitud_id = $x_solicitud_id_c";
		$rspromotor = phpmkr_query($sqlpromotor,$conn) or die ("Error la selccionar le promotor-id".phpmk_error()."sql:".$sqlpromotor);
		$rowpromotor = phpmkr_fetch_array($rspromotor);	
		$x_promotor_de_gestor = $rowpromotor["promotor_id"];
		echo "promotor de gastor".$x_promotor_de_gestor."<br>";
		
		$sqlGestor = "SELECT gestor_id FROM gestor_promotor WHERE promotor_id = $x_promotor_de_gestor ";
		$rsGestor = phpmkr_query($sqlGestor,$conn)or die("Error al seleccionar el gestor".phpmkr_error()."sql:".$sqlGestor);
		$rowGestor = phpmkr_fetch_array($rsGestor);
		$x_gestor_id = $rowGestor["gestor_id"];
		
		// seleccionamos el usuario del gestor
		$sqlUsuario = "SELECT usuario_id  FROM gestor WHERE gestor_id = $x_gestor_id";
		$rsusuer = phpmkr_query($sqlUsuario,$conn)or die("Erro en usuario 2".phpmkr_query()."sql: ".$sqlUsuario);
		$rowuser = phpmkr_fetch_array($rsusuer);		
		$x_usuario_id = $rowuser["usuario_id"];
	
			// se debe verificar antes de ingresar la tarea que no exista ninguna tarea para el caso de este tipo
			$sqlBuscaTarea =  "SELECT COUNT(*) AS tareas_exist from  crm_tarea where crm_caso_id = $x_crm_caso_id AND crm_tarea_tipo_id = $x_tarea_tipo_id ";
			$sqlBuscaTarea .= " AND crm_tarea_status_id IN (1,2) AND destino = $x_usuario_id";	
			$rsBuscaTarea = phpmkr_query($sqlBuscaTarea,$conn) or die ("Erro al insertar en tarea".phpmkr_error()."sql:".$sqlBuscaTarea);
			$rowBuscaTarea = phpmkr_fetch_array($rsBuscaTarea);
			$x_tareas_abiertas = $rowBuscaTarea["tareas_exist"];
			
			#echo "<br><br> TREAS ABIERTAS ".$x_tareas_abiertas."<BR><BR>".$sqlBuscaTarea."<BR><BR>";
			if($x_tareas_abiertas == 0){
			$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 7, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
			// la tarea la ejecuta el asesor de crédito
			$rs = phpmkr_query($sSql,$conn) or die("Error al seleccionar insertar en tarea para promotor".phpmkr_error()."sql:".$sSql);
			echo "INSERTA CARTA 2<BR>".$sSql."<BR>";
			//$x_result = phpmkr_query($sSql, $conn);
			$x_tarea_id = mysql_insert_id();
	
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
			
			
		########################################################################################################################################################
		############################################################ TAREAS DIARIAS PARA GESTORES	############################################################
		########################################################################################################################################################
		##credamos las lista de los promotores con la tareas diarias.		
		## seleccionaos la soza de la solcitud		
		$sqlZonaId = "SELECT dia FROM  zona_gestor JOIN solicitud ON solicitud.promotor_id = zona_gestor.promotor_id WHERE solicitud.solicitud_id = $x_solicitud_id_c";
		$rsZonaId = phpmkr_query($sqlZonaId,$conn) or die ("Error al seleccionar el dia de la zona".phpmkr_error()."sql:".$sqlZonaId);
		$rowZonaId = phpmkr_fetch_array($rsZonaId);
		$x_dia_zona_gestor = $rowZonaId["dia"];
		echo "sql dia zona gestor ".$sqlZonaId."<br>";
		echo "dia zona gestor".$x_dia_zona_gestor."<br>";
		
		$x_fecha_tarea_f = $currdate;
		$x_dia_zona_f = $x_dia_zona_gestor;
		$x_promotor_id_f = $x_promotor_id_c ;
		$x_caso_id_f = $x_crm_caso_id;
		$x_tarea_id_f = $x_tarea_id;
		
		
		#tenemos la fecha de hoy  --> hoy es miercoles 30 de enero
		# se debe buscar la fecha de la zona --> si la zona fuera 5.. la fecha se deberia cambiar a viernes 1 de febrero
		# o si la zona fuera  2 como las tareas de la zona 2 ya fueron asignadas.. se debe buscar la fecha de la zona dos de la sig semana
		
		$sqlDiaSemana = "SELECT DAYOFWEEK('$x_fecha_tarea_f') AS  dia";
		$rsDiaSemana = phpmkr_query($sqlDiaSemana,$conn) or die ("Error al seleccionar el dia de la semana".phpmkr_error()."sql:".$sqlDiaSemana);
		$rowDiaSemana = phpmkr_fetch_array($rsDiaSemana);
		$x_dia_de_semana_en_fecha = $rowDiaSemana["dia"];
		echo "fecha day of week".$x_dia_de_semana_en_fecha ."<br>";
		
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
				
			#	echo "fecha dia------".$x_fecha_tarea_f."<br>";
				$temptime = strtotime($x_fecha_tarea_f);	
				$temptime = DateAdd('w',$x_dias_faltantes,$temptime);
				$fecha_nueva = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				$x_fecha_tarea_f = $fecha_nueva;	
				echo "nueva fecha...ESTA semana<br> dias faltantes".$x_dias_faltantes."<br>".$x_fecha_tarea_f;			
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
					echo "nueva fecha...sigueinte semana<br> dias faltantes".$x_dias_faltantes."<br>";
				#	echo "DIA DE LA SEMANA EN FECHA".$x_dia_de_semana_en_fecha."<br>";
				#	echo "dia zona".$x_dia_zona_f;					
					}
			}
		
		$x_dias_agregados = calculaSemanasGestor($conn, $x_fecha_tarea_f, $x_dia_zona_f, $x_promotor_id_f, $x_caso_id_f, $x_tarea_id_f, $x_gestor_id);
		echo "dias agragados".$x_dias_agregados." ";
		
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
				$x_fecha_ejecuta_act = $fecha_venc; //kuki
				
				$sqlUpdateFecha = "UPDATE crm_tarea SET fecha_ejecuta = \"$x_fecha_ejecuta_act\" WHERE crm_tarea_id = $x_tarea_id_f ";
				$rsUpdateFecha = phpmkr_query($sqlUpdateFecha,$conn) or die ("Error al actualiza la fecha de latarea despues del calculo semana".phpmkr_error()."sql;".$sqlUpdateFecha);
				echo "UPDATAE TREA 1".$sqlUpdateFecha."<br>";
				
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
				echo "UPDATAE TREA 2".$sqlUpdateFecha."<br>";
			
			
			}
	#	echo "FECHA EJECUTA".$x_fecha_ejecuta_act;
		#####################################################################################################################
		###############################    TAREAS DIARIAS GESTOR DE COBRANZA    #############################################
		#####################################################################################################################
		$x_tareas_asignadas_del_caso = 0;
		// primero verifamos que la tarea aun no este en la lista, es decir, que se trate de la primera vez que entra al cliclo para este credito.
		$sqlBuscaatreaAsignada = "SELECT COUNT(*) AS atreas_asignadas FROM `tarea_diaria_gestor` WHERE fecha_ingreso =  \"$currdate\" AND gestor_id = $x_gestor_id ";
		$sqlBuscaatreaAsignada .= " AND `caso_id` = $x_caso_id_f";
		$rsBuscatareaAsignada = phpmkr_query($sqlBuscaatreaAsignada,$conn) or die("Erro al buscar atrea".phpmkr_error()."sql:".$sqlBuscaatreaAsignada);
	#echo "BUSCA TAREAS".$sqlBuscaatreaAsignada."<BR>";
		$rowBuscaTareaAsignada = phpmkr_fetch_array($rsBuscatareaAsignada);
		$x_tareas_asignadas_del_caso = $rowBuscaTareaAsignada["atreas_asignadas"];
	#	echo "TAREAS ASIGNADAS ".$x_tareas_asignadas_del_caso."<br>";
	#	//se inserta la tarea en la lista de las actividades diarias del promotor
		if ($x_tareas_asignadas_del_caso < 1){
		$sqlInsertListaTarea = "INSERT INTO `tarea_diaria_gestor`";
		$sqlInsertListaTarea .= " (`tarea_diaria_gestor_id`, `gestor_id`, `zona_id`, `dia_semana`, `fecha_ingreso`, `fecha_lista`, `caso_id`, ";
		$sqlInsertListaTarea .= " `tarea_id`, `reingreso`, `fase`, `status_tarea`, `credito_id`) ";
		$sqlInsertListaTarea .= "VALUES (NULL, $x_gestor_id, $x_dia_zona_f , $x_dia_zona_f, \"$currdate\",\"$x_fecha_tarea_f\", $x_caso_id_f, $x_tarea_id_f, '0', '2', '1', $x_credito_id);";
		$rsInsertListaTarea = phpmkr_query($sqlInsertListaTarea,$conn)or die("Error al insertar en lista diaria tareas +++++".phpmkr_error()."sql:".$sqlInsertListaTarea);	 
		}
		
		
			}// tareas registradas = 0
			
		
		
			}//la promesa de pago es menor a hoy
		}//existe la promesa  de pago
		
		}// if comision generada > 1 and carta 2 = 0
		
		
	if(($x_carta_2 > 0) && ($x_ciclo_carta_2 == 1) && ($x_carta_3 == 0) ){
		// ya se le genero la carta 2
		// ya esta en fase 5 Y AQUI SE CICLA
		// ya tiene una o mas promesas de pago...
		// SE genera una promesa de pagao al promotor de credito (angelica, monica, jose) 
		// carta 1 ya existe, pero debemos de verificar que la tarea esta vencida o esta cerrada
		// si ya se vencio la tarea o ya se netrego la carta entonces si ya podemos programar la sigueinte tarea
		// de lo contrario si el credito tuvuera mas de dos vencidos.. generaria la carata y tambien generaria la PP 
		
	
		
		# PONEMOS LA CONDICIONES DEL CICLO		
		#verificamos que no tenga ninguna PP con status de pendiente;	
		$x_carta_2PP = 0;
		$sqlCarta2PP = "SELECT COUNT(*) AS carta_2_PP FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND crm_tarea_tipo_id = 5 AND orden= 9 AND crm_tarea_status_id = 1";
		$rsCarta2PP = phpmkr_query($sqlCarta2PP,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCarta2PP);
		$rowCarata2PP = phpmkr_fetch_array($rsCarta2PP);
		$x_carta_2PP = $rowCarata2PP["carta_2_PP"];
		// se agrega la tarea siemore y cuando no exista una tarea igaul en status pendiente	
		
			$x_carta_2PP_v = 0;
		$sqlCarta2PP_vencida = "SELECT COUNT(*) AS carta_2_PP FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND crm_tarea_tipo_id = 5 AND orden= 9 AND crm_tarea_status_id in (2,3)";
		$rsCarta2PP_vencida = phpmkr_query($sqlCarta2PP_vencida,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCarta2PP_vencida);
		$rowCarata2PP_vencida = phpmkr_fetch_array($rsCarta2PP_vencida);
		$x_carta_2PP_v = $rowCarata2PP_vencida["carta_2_PP"];
		
		if(($x_carta_2PP < 1) && (($x_carta_2PP_v < 1))){
			
		#SELECCIONAMOS LOS DATOS DE  LA TAREA
		
		$sSqlWrk = "
		SELECT *
		FROM 
			crm_playlist
		WHERE 
			crm_playlist.crm_caso_tipo_id = 3
			AND crm_playlist.orden = 9"; // PROMESA DE PAGO CARTA 2
		
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
		
		
		
		
		###############################################################################
		######################  aqui el usuario del sr RAUL     #######################
		###############################################################################
		
		# seleccionamos los datos del promotor.
		$x_promotor_de_gestor = 0;
		$sqlpromotor = "SELECT promotor_id FROM solicitud WHERE solicitud_id = $x_solicitud_id_c";
		$rspromotor = phpmkr_query($sqlpromotor,$conn) or die ("Error la selccionar le promotor-id".phpmk_error()."sql:".$sqlpromotor);
		$rowpromotor = phpmkr_fetch_array($rspromotor);
		mysql_free_result($rspromotor);	
		$x_promotor_de_gestor = $rowpromotor["promotor_id"];
	#	 echo "promotor de gestor".$x_promotor_de_gestor."<br>";
		$sqlGestor = "SELECT gestor_id FROM gestor_promotor WHERE promotor_id = $x_promotor_de_gestor ";
		$rsGestor = phpmkr_query($sqlGestor,$conn)or die("Error al seleccionar el gestor".phpmkr_error()."sql:".$sqlGestor);
		$rowGestor = phpmkr_fetch_array($rsGestor);
		mysql_free_result($rsGestor);
		$x_gestor_id = $rowGestor["gestor_id"];
		
		// seleccionamos el usuario del gestor
		$sqlUsuario = "SELECT usuario_id  FROM gestor WHERE gestor_id = $x_gestor_id";
		$rsusuer = phpmkr_query($sqlUsuario,$conn)or die("Erro en usuario 1".phpmkr_query()."sql: ".$sqlUsuario);
		$rowuser = phpmkr_fetch_array($rsusuer);	
		mysql_free_result($rsusuer);
		$x_usuario_id = $rowuser["usuario_id"];
		
			
			



		if(($x_crm_caso_id > 0)){

			$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 2, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
			
			echo "SE INSERTA CARAT 2<BR>".$sSql."<BR>";
		
			$x_result = phpmkr_query($sSql, $conn);
			$x_tarea_id = mysql_insert_id();
	
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
		
		
		// buscar el dia que se asignara la tarea al prmotor
		########################################################################################################################################################
		############################################################ TAREAS DIARIAS PARA GESTOR 	############################################################
		########################################################################################################################################################
		##credamos las lista de los promotores con la tareas diarias.		
		## seleccionaos la soza de la solcitud		
		$sqlZonaId = "SELECT dia FROM  zona_gestor JOIN solicitud ON solicitud.promotor_id = zona_gestor.promotor_id WHERE solicitud.solicitud_id = $x_solicitud_id_c";
		//echo "selecciono el dia de la zona gestor".$sqlZonaId."<br>" ;
		$rsZonaId = phpmkr_query($sqlZonaId,$conn) or die ("Error al seleccionar el dia de la zona".phpmkr_error()."sql:".$sqlZonaId);
		$rowZonaId = phpmkr_fetch_array($rsZonaId);
		$x_dia_zona = $rowZonaId["dia"];
		echo "dia zona ".$x_dia_zona."<br>";
		$x_fecha_tarea_f = $currdate;
		$x_dia_zona_f = $x_dia_zona;
		$x_promotor_id_f = $x_promotor_id_c ;
		$x_caso_id_f = $x_crm_caso_id;
		$x_tarea_id_f = $x_tarea_id;
	
		
		
		#tenemos la fecha de hoy  --> hoy es miercoles 30 de enero
		# se debe buscar la fecha de la zona --> si la zona fuera 5.. la fecha se deberia cambiar a viernes 1 de febrero
		# o si la zona fuera  2 como las tareas de la zona 2 ya fueron asignadas.. se debe buscar la fecha de la zona dos de la sig semana
		
		$sqlDiaSemana = "SELECT DAYOFWEEK('$x_fecha_tarea_f') AS  dia";
		$rsDiaSemana = phpmkr_query($sqlDiaSemana,$conn) or die ("Error al seleccionar el dia de la semana".phpmkr_error()."sql:".$sqlDiaSemana);
		$rowDiaSemana = phpmkr_fetch_array($rsDiaSemana);
		$x_dia_de_semana_en_fecha = $rowDiaSemana["dia"];
		echo "fecha day of week".$x_dia_de_semana_en_fecha ."<br>";
		
		if($x_dia_de_semana_en_fecha != $x_dia_zona_f ){
			// el dia de la zona y el dia de la fecha no es el mismo se debe incrementar la fecha hasta llegar al mismo dia
			
			//echo "LOS DIAS SON DIFERENTES<BR>";
		//	$x_dia_de_semana_en_fecha = 1;
		//	$x_dia_zona_f= 5;
		//	$x_fecha_tarea_f = "2013-01-29";
			if($x_dia_de_semana_en_fecha < $x_dia_zona_f){
				// la fecha es mayor dia_en _fecha = 2; dia_zona = 5
				$x_dias_faltantes = $x_dia_zona_f - $x_dia_de_semana_en_fecha;	
				//hacemos el incremento en la fecha		
				//Fecha de tarea para la lista
				
			//	echo "fecha dia------".$x_fecha_tarea_f."<br>";
				$temptime = strtotime($x_fecha_tarea_f);	
				$temptime = DateAdd('w',$x_dias_faltantes,$temptime);
				$fecha_nueva = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				$x_fecha_tarea_f = $fecha_nueva;	
			//	echo "nueva fecha...ESTA semana<br> dias faltantes".$x_dias_faltantes."<br>";			
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
				//	echo "nueva fecha...sigueinte semana<br> dias faltantes".$x_dias_faltantes."<br>";
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
			#	echo "UPDATAE TREA".$sqlUpdateFecha."<br>";
				
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
		echo "FECHA EJECUTA".$x_fecha_ejecuta_act;
		#####################################################################################################################
		##################################### TAREAS DIARIAS GESTOR   #######################################################
		#####################################################################################################################
		
		// primero verifamos que la tarea aun no este en la lista, es decir, que se trate de la primera vez que entra al cliclo para este credito.
		$sqlBuscaatreaAsignada = "SELECT COUNT(*) AS atreas_asignadas FROM `tarea_diaria_gestor` WHERE fecha_ingreso =  \"$currdate\" AND gestor_id = $x_gestor_id";
		$sqlBuscaatreaAsignada .= " AND `caso_id` = $x_caso_id_f";
		$rsBuscatareaAsignada = phpmkr_query($sqlBuscaatreaAsignada,$conn) or die("Erro al buscar atrea".phpmkr_error()."sql:".$sqlBuscaatreaAsignada);
		#echo "BUSCA TAREAS".$sqlBuscaatreaAsignada."<BR>";
		$rowBuscaTareaAsignada = phpmkr_fetch_array($rsBuscatareaAsignada);
		$x_tareas_asignadas_del_caso = $rowBuscaTareaAsignada["atreas_asignadas"];
		#echo "TAREAS ASIGNADAS ".$x_tareas_asignadas_del_caso."<br>";
		//se inserta la tarea en la lista de las actividades diarias del promotor
		if ($x_tareas_asignadas_del_caso < 1){
		$sqlInsertListaTarea = "INSERT INTO `tarea_diaria_gestor`";
		$sqlInsertListaTarea .= " (`tarea_diaria_gestor_id`, `gestor_id`, `zona_id`, `dia_semana`, `fecha_ingreso`, `fecha_lista`, `caso_id`, ";
		$sqlInsertListaTarea .= " `tarea_id`, `reingreso`, `fase`, `status_tarea`, `credito_id`) ";
		$sqlInsertListaTarea .= "VALUES (NULL, $x_gestor_id, $x_dia_zona_f , $x_dia_zona_f, \"$currdate\",\"$x_fecha_tarea_f\", $x_caso_id_f, $x_tarea_id_f, '0', '2', '1', $x_credito_id);";
		$rsInsertListaTarea = phpmkr_query($sqlInsertListaTarea,$conn)or die("Error al insertar en lista diaria tareas ppc2".phpmkr_error()."sql:".$sqlInsertListaTarea);	 
		}
		
		
		
		
		
		
		}// CASO CRM > 0 DE CARTA 1
		
		}// SI NO EXISTE LA PROMESA DE PAGO PARA CARAT 1
		
		}// carta 2 ya existe pero entra a ciclo PP carta 2	
		
		
	if(($x_carta_2 > 0) && ($x_ciclo_carta_2 == 0) && ($x_carta_3 == 0)){		
	echo "ciclo carta 2 == 0 -->".$x_ciclo_carta_2."<br>";
	echo "<BR><BR>ENTRA A ACARTA 333333<BR><BR>";
		
		$x_promesas_c2 = 0;
		$x_fecha_promesa_pago_c2 = 0;	
			
			$sqlCarta2 = "SELECT COUNT(*) AS crm_tarea_id FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND  crm_tarea_tipo_id = 9 AND orden= 8";
			$rsCarta2 = phpmkr_query($sqlCarta2,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCarta2);
			$rowCarata2 = phpmkr_fetch_array($rsCarta2);
			$x_carta_2_tarea_id = $rowCarata2["crm_tarea_id"];
			if($x_carta_2_tarea_id >0 ){
				//buscamos que ya tenga Una promesa de pago y que este vencida para entrar al ciclo			
				$sqlPPC2 = "SELECT * FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id  AND crm_tarea_tipo_id = 5 AND orden = 9 ";
				$rsppc2 = phpmkr_query($sqlPPC2,$conn)or die ("Error al seleccioanr la pp c2".phpmkr_error()."sql:".$sqlPPC2);
				$rowppc2 = phpmkr_fetch_array($rsppc1);
				$x_fecha_registro_tarea = $rowppc2["fecha_registro"];
				$x_tarea_ppc2 =$rowppc2["crm_tarea_id"];
				$x_caso_ppc2 = $rowppc2["crm_caso_id"];			
				if($x_caso_ppc2> 0){
					// la promesa de pago esta registrada; buscamos la fecha de pagoo de la promesa
					$sqlcrm_tarea_cv2 = "SELECT COUNT(*) AS promesas, promesa_pago  FROM crm_tarea_cv WHERE crm_tarea_id = $x_tarea_ppc2 ";
					$rscrm_tarea_cv2 = phpmkr_query($sqlcrm_tarea_cv2,$conn)or die("Error al selecionar la fecha de promesa de pago".phpmkr_error()."sql:".$sqlcrm_tarea_cv2);
					$rowcrm_tarea_cv2 = phpmkr_fetch_array($rscrm_tarea_cv2);
					$x_promesas_c2 = $rowcrm_tarea_cv2["promesas"];
					$x_fecha_promesa_pago_c2 = $rowcrm_tarea_cv2["promesa_pago"]; 
					echo "1|fecha de la promesa de pago de la carta 2".$x_fecha_promesa_pago_c2."<br>";			
					}			
				}
				
				
				echo "2|fecha de la promesa de pago de la carta 2".$x_fecha_promesa_pago_c2."<br>";
				$x_hoy_p = date("Y-m-d");
				if($x_promesas_c2 > 0){
					if( $x_hoy_p > $x_fecha_promesa_pago_c2 ){				
	###########################################################################################################################################
	###########################################################################################################################################
	###################################################               CARTA 3              ####################################################
	###########################################################################################################################################
	###########################################################################################################################################
	//ETAPA 6 CASO CARTA 3			
	#echo "ENTRA A CARTA 3<BR>";
	
	
			// buscar usuario del rs raul	
			
			############################################
			#############  falta codigo  ##############
			############################################	
			//LA ATAREA SE LE GENERA LA GESTROR NO AL PROMOTOR
			$sSqlWrk = "SELECT usuario_id
						FROM 
						promotor
						WHERE 
						promotor.promotor_id = $x_promotor_id_c ";		
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_promotor_usuario_id = $datawrk["usuario_id"];
			@phpmkr_free_result($rswrk);
					
			// seleccionamos los datos para la nueva tarea
				
				$sSqlWrk = "SELECT *
							FROM 
							crm_playlist
							WHERE 
							crm_playlist.crm_caso_tipo_id = 3
							AND crm_playlist.orden = 12 "; // orden 12 CARTA  3		
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
	
	
	
				$x_origen = 1;  // el origen se podria cambiar a responsable de sucursal.
				$x_bitacora = "Cartera Vencida  SE PASA A ETAPA 6  CARTA 3- (".FormatDateTime($currdate,7)." - $currtime)";
			
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


		
		
		###############################################################################
		######################  aqui el usuario del sr RAUL     #######################
		###############################################################################
		
		# seleccionamos los datos del promotor.
		$x_promotor_de_gestor = 0;
		$sqlpromotor = "SELECT promotor_id FROM solicitud WHERE solicitud_id = $x_solicitud_id_c";
		$rspromotor = phpmkr_query($sqlpromotor,$conn) or die ("Error la selccionar le promotor-id".phpmk_error()."sql:".$sqlpromotor);
		$rowpromotor = phpmkr_fetch_array($rspromotor);
		mysql_free_result($rspromotor);	
		$x_promotor_de_gestor = $rowpromotor["promotor_id"];
		//echo "prmotor de gestor".$x_promotor_de_gestor."<br>";
		
		$sqlGestor = "SELECT gestor_id FROM gestor_promotor WHERE promotor_id = $x_promotor_de_gestor ";
		$rsGestor = phpmkr_query($sqlGestor,$conn)or die("Error al seleccionar el gestor".phpmkr_error()."sql:".$sqlGestor);
		$rowGestor = phpmkr_fetch_array($rsGestor);
		mysql_free_result($rsGestor);
		$x_gestor_id = $rowGestor["gestor_id"];
		
		// seleccionamos el usuario del gestor
		$sqlUsuario = "SELECT usuario_id  FROM gestor WHERE gestor_id = $x_gestor_id";
		$rsusuer = phpmkr_query($sqlUsuario,$conn)or die("Erro en usuario x".phpmkr_query()."sql: ".$sqlUsuario);
		$rowuser = phpmkr_fetch_array($rsusuer);	
		mysql_free_result($rsusuer);
		$x_usuario_id = $rowuser["usuario_id"];
		
				

		
	
			// se debe verificar antes de ingresar la tarea que no exista ninguna tarea para el caso de este tipo
			$sqlBuscaTarea =  "SELECT COUNT(*) AS tareas_exist from  crm_tarea where crm_caso_id = $x_crm_caso_id AND crm_tarea_tipo_id = $x_tarea_tipo_id ";
			$sqlBuscaTarea .= " AND crm_tarea_status_id IN (1,2) AND destino = $x_usuario_id";	
			$rsBuscaTarea = phpmkr_query($sqlBuscaTarea,$conn) or die ("Erro al insertar en tarea 2".phpmkr_error()."sql:".$sqlBuscaTarea);
			$rowBuscaTarea = phpmkr_fetch_array($rsBuscaTarea);
			$x_tareas_abiertas = $rowBuscaTarea["tareas_exist"];
			
			//echo "<br><br> TREAS ABIERTAS ".$x_tareas_abiertas."<BR><BR>".$sqlBuscaTarea."<BR><BR>";
			if($x_tareas_abiertas == 0){
			$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 7, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
			// la tarea la ejecuta el asesor de crédito
			$rs = phpmkr_query($sSql,$conn) or die("Error al seleccionar insertar en tarea para promotor".phpmkr_error()."sql:".$sSql);
		echo "SE INSERTA CARATA 3<BR>".$sSql."<BR>";
			//$x_result = phpmkr_query($sSql, $conn);
			$x_tarea_id = mysql_insert_id();
	
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
			
			
		########################################################################################################################################################
		############################################################ TAREAS DIARIAS PARA GESTORES	############################################################
		########################################################################################################################################################
		##credamos las lista de los promotores con la tareas diarias.		
		## seleccionaos la soza de la solcitud		
		$sqlZonaId = "SELECT dia FROM  zona_gestor JOIN solicitud ON solicitud.promotor_id = zona_gestor.promotor_id WHERE solicitud.solicitud_id = $x_solicitud_id_c";
		$rsZonaId = phpmkr_query($sqlZonaId,$conn) or die ("Error al seleccionar el dia de la zona".phpmkr_error()."sql:".$sqlZonaId);
		$rowZonaId = phpmkr_fetch_array($rsZonaId);
		$x_dia_zona_gestor = $rowZonaId["dia"];
		
		$x_fecha_tarea_f = $currdate;
		$x_dia_zona_f = $x_dia_zona_gestor;
		$x_promotor_id_f = $x_promotor_id_c ;
		$x_caso_id_f = $x_crm_caso_id;
		$x_tarea_id_f = $x_tarea_id;
		
		
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
			
		#	echo "LOS DIAS SON DIFERENTES<BR>";
		//	$x_dia_de_semana_en_fecha = 1;
		//	$x_dia_zona_f= 5;
		//	$x_fecha_tarea_f = "2013-01-29";
			if($x_dia_de_semana_en_fecha < $x_dia_zona_f){
				// la fecha es mayor dia_en _fecha = 2; dia_zona = 5
				$x_dias_faltantes = $x_dia_zona_f - $x_dia_de_semana_en_fecha;	
				//hacemos el incremento en la fecha		
				//Fecha de tarea para la lista
				
			#	echo "fecha dia------".$x_fecha_tarea_f."<br>";
				$temptime = strtotime($x_fecha_tarea_f);	
				$temptime = DateAdd('w',$x_dias_faltantes,$temptime);
				$fecha_nueva = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				$x_fecha_tarea_f = $fecha_nueva;	
			#	echo "nueva fecha...ESTA semana<br> dias faltantes".$x_dias_faltantes."<br>";			
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
					echo "nueva fecha...sigueinte semana<br> dias faltantes".$x_dias_faltantes."<br>";
					echo "DIA DE LA SEMANA EN FECHA".$x_dia_de_semana_en_fecha."<br>";
					echo "dia zona".$x_dia_zona_f;					
					}
			}
		
		$x_dias_agregados = calculaSemanasGestor($conn, $x_fecha_tarea_f, $x_dia_zona_f, $x_promotor_id_f, $x_caso_id_f, $x_tarea_id_f, $x_gestor_id);
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
		###############################    TAREAS DIARIAS GESTOR DE COBRANZA    ##############################################
		#####################################################################################################################
		$x_tareas_asignadas_del_caso = 0;
		// primero verifamos que la tarea aun no este en la lista, es decir, que se trate de la primera vez que entra al cliclo para este credito.
		$sqlBuscaatreaAsignada = "SELECT COUNT(*) AS atreas_asignadas FROM `tarea_diaria_gestor` WHERE fecha_ingreso =  \"$currdate\" AND gestor_id = $x_gestor_id ";
		$sqlBuscaatreaAsignada .= " AND `caso_id` = $x_caso_id_f";
		$rsBuscatareaAsignada = phpmkr_query($sqlBuscaatreaAsignada,$conn) or die("Erro al buscar atrea".phpmkr_error()."sql:".$sqlBuscaatreaAsignada);
	#	echo "BUSCA TAREAS".$sqlBuscaatreaAsignada."<BR>";
		$rowBuscaTareaAsignada = phpmkr_fetch_array($rsBuscatareaAsignada);
		$x_tareas_asignadas_del_caso = $rowBuscaTareaAsignada["atreas_asignadas"];
	#	echo "TAREAS ASIGNADAS ".$x_tareas_asignadas_del_caso."<br>";
		//se inserta la tarea en la lista de las actividades diarias del promotor
		if ($x_tareas_asignadas_del_caso < 1){
		$sqlInsertListaTarea = "INSERT INTO `tarea_diaria_gestor`";
		$sqlInsertListaTarea .= " (`tarea_diaria_gestor_id`, `gestor_id`, `zona_id`, `dia_semana`, `fecha_ingreso`, `fecha_lista`, `caso_id`, ";
		$sqlInsertListaTarea .= " `tarea_id`, `reingreso`, `fase`, `status_tarea`, `credito_id`) ";
		$sqlInsertListaTarea .= "VALUES (NULL, $x_gestor_id, $x_dia_zona_f , $x_dia_zona_f, \"$currdate\",\"$x_fecha_tarea_f\", $x_caso_id_f, $x_tarea_id_f, '0', '2', '1', $x_credito_id);";
		$rsInsertListaTarea = phpmkr_query($sqlInsertListaTarea,$conn)or die("Error al insertar en lista diaria tareas  ----".phpmkr_error()."sql:".$sqlInsertListaTarea);	 
		}
		
		
			}// tareas registradas = 0
			
		
		
		}// la fecha de la promesa de pago ya vencio
				}//promesa de pago mayor a 0
		
		}// ya se tiene la carat 2; ya no entraa ciclo carta dos; y aun no existe la carta 3	
		
		
	if(($x_carta_3 > 0) && ($x_ciclo_carta_3 == 1) && ($x_carta_D == 0)){
		// ya se le genero la carta 3
		// ya esta en fase 7 Y AQUI SE CICLA
		// ya tiene una o mas promesas de pago...
		// SE genera una promesa de pagao al promotor de credito (angelica, monica, jose) 
		// carta 1 ya existe, pero debemos de verificar que la tarea esta vencida o esta cerrada
		// si ya se vencio la tarea o ya se netrego la carta entonces si ya podemos programar la sigueinte tarea
		// de lo contrario si el credito tuvuera mas de dos vencidos.. generaria la carata y tambien generaria la PP 
		
		# PONEMOS LA CONDICIONES DEL CICLO		
		#verificamos que no tenga ninguna PP con status de pendiente;	
		$x_carta_3PP = 0;
		$sqlCarta3PP = "SELECT COUNT(*) AS carta_3_PP FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND crm_tarea_tipo_id = 5 AND orden= 13 AND crm_tarea_status_id = 1";
		$rsCarta3PP = phpmkr_query($sqlCarta3PP,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCarta3PP);
		$rowCarata3PP = phpmkr_fetch_array($rsCarta3PP);
		$x_carta_3PP = $rowCarata3PP["carta_3_PP"];
		// se agrega la tarea siemore y cuando no exista una tarea igaul en status pendiente	
		$sqlCarta3PP_vencida = "SELECT COUNT(*) AS carta_3_PP FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND crm_tarea_tipo_id = 5 AND orden= 13 AND crm_tarea_status_id in (2,3)";
		$rsCarta3PP_vencida = phpmkr_query($sqlCarta3PP_vencida,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCarta3PP_vencida);
		$rowCarata3PP_vencida = phpmkr_fetch_array($rsCarta3PP_vencida);
		$x_carta_3PP_vencida = $rowCarata3PP_vencida["carta_3_PP"];
		
		if(($x_carta_3PP < 1) && ($x_carta_3PP_vencida < 1)){
			
		#SELECCIONAMOS LOS DATOS DE  LA TAREA
		
		$sSqlWrk = "
		SELECT *
		FROM 
			crm_playlist
		WHERE 
			crm_playlist.crm_caso_tipo_id = 3
			AND crm_playlist.orden = 13"; // PROMESA DE PAGO CARTA 3
		
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
		
		
		
		
		###############################################################################
		######################  aqui el usuario del sr RAUL     #######################
		###############################################################################		
		# seleccionamos los datos del promotor.
		$x_promotor_de_gestor = 0;
		$sqlpromotor = "SELECT promotor_id FROM solicitud WHERE solicitud_id = $x_solicitud_id_c";
		$rspromotor = phpmkr_query($sqlpromotor,$conn) or die ("Error la selccionar le promotor-id".phpmk_error()."sql:".$sqlpromotor);
		$rowpromotor = phpmkr_fetch_array($rspromotor);
		mysql_free_result($rspromotor);	
		$x_promotor_de_gestor = $rowpromotor["promotor_id"];
		
		$sqlGestor = "SELECT gestor_id FROM gestor_promotor WHERE promotor_id = $x_promotor_de_gestor ";
		$rsGestor = phpmkr_query($sqlGestor,$conn)or die("Error al seleccionar el gestor".phpmkr_error()."sql:".$sqlGestor);
		$rowGestor = phpmkr_fetch_array($rsGestor);
		mysql_free_result($rsGestor);
		$x_gestor_id = $rowGestor["gestor_id"];
		
		// seleccionamos el usuario del gestor
		$sqlUsuario = "SELECT usuario_id  FROM gestor WHERE gestor_id = $x_gestor_id";
		$rsusuer = phpmkr_query($sqlUsuario,$conn)or die("Erro en usuario".phpmkr_query()."sql: ".$sqlUsuario);
		$rowuser = phpmkr_fetch_array($rsusuer);	
		mysql_free_result($rsusuer);
		$x_usuario_id = $rowuser["usuario_id"];
		
			
			



		if(($x_crm_caso_id > 0)){

			$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 2, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
		
			$x_result = phpmkr_query($sSql, $conn);
			$x_tarea_id = mysql_insert_id();
	 echo "promesa de pago carata 3".$sSql."<br>";
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
		
		
		// buscar el dia que se asignara la tarea al prmotor
		########################################################################################################################################################
		############################################################ TAREAS DIARIAS PARA GESTORES	############################################################
		########################################################################################################################################################
		##credamos las lista de los promotores con la tareas diarias.		
		## seleccionaos la soza de la solcitud		
		$sqlZonaId = "SELECT dia FROM  zona_gestor JOIN solicitud ON solicitud.promotor_id = zona_gestor.promotor_id WHERE solicitud.solicitud_id = $x_solicitud_id_c";
		$rsZonaId = phpmkr_query($sqlZonaId,$conn) or die ("Error al seleccionar el dia de la zona".phpmkr_error()."sql:".$sqlZonaId);
		$rowZonaId = phpmkr_fetch_array($rsZonaId);
		$x_dia_zona = $rowZonaId["dia"];
		
		$x_fecha_tarea_f = $currdate;
		$x_dia_zona_f = $x_dia_zona;
		$x_promotor_id_f = $x_promotor_id_c ;
		$x_caso_id_f = $x_crm_caso_id;
		$x_tarea_id_f = $x_tarea_id;
	
		
		
		#tenemos la fecha de hoy  --> hoy es miercoles 30 de enero
		# se debe buscar la fecha de la zona --> si la zona fuera 5.. la fecha se deberia cambiar a viernes 1 de febrero
		# o si la zona fuera  2 como las tareas de la zona 2 ya fueron asignadas.. se debe buscar la fecha de la zona dos de la sig semana
		
		$sqlDiaSemana = "SELECT DAYOFWEEK('$x_fecha_tarea_f') AS  dia";
		$rsDiaSemana = phpmkr_query($sqlDiaSemana,$conn) or die ("Error al seleccionar el dia de la semana".phpmkr_error()."sql:".$sqlDiaSemana);
		$rowDiaSemana = phpmkr_fetch_array($rsDiaSemana);
		$x_dia_de_semana_en_fecha = $rowDiaSemana["dia"];
		echo "fecha day of week".$x_dia_de_semana_en_fecha ."<br>";
		
		if($x_dia_de_semana_en_fecha != $x_dia_zona_f ){
			// el dia de la zona y el dia de la fecha no es el mismo se debe incrementar la fecha hasta llegar al mismo dia
			
			echo "LOS DIAS SON DIFERENTES<BR>";
		//	$x_dia_de_semana_en_fecha = 1;
		//	$x_dia_zona_f= 5;
		//	$x_fecha_tarea_f = "2013-01-29";
			if($x_dia_de_semana_en_fecha < $x_dia_zona_f){
				// la fecha es mayor dia_en _fecha = 2; dia_zona = 5
				$x_dias_faltantes = $x_dia_zona_f - $x_dia_de_semana_en_fecha;	
				//hacemos el incremento en la fecha		
				//Fecha de tarea para la lista
				
				echo "fecha dia------".$x_fecha_tarea_f."<br>";
				$temptime = strtotime($x_fecha_tarea_f);	
				$temptime = DateAdd('w',$x_dias_faltantes,$temptime);
				$fecha_nueva = strftime('%Y-%m-%d',$temptime);			
				$x_dia = strftime('%A',$temptime);
				$x_fecha_tarea_f = $fecha_nueva;	
				echo "nueva fecha...ESTA semana<br> dias faltantes".$x_dias_faltantes."<br>";			
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
					echo "nueva fecha...sigueinte semana<br> dias faltantes".$x_dias_faltantes."<br>";
					echo "DIA DE LA SEMANA EN FECHA".$x_dia_de_semana_en_fecha."<br>";
					echo "dia zona".$x_dia_zona_f;					
					}
			}
		
		$x_dias_agregados = calculaSemanasGestor($conn, $x_fecha_tarea_f, $x_dia_zona_f,$x_promotor_id , $x_caso_id_f, $x_tarea_id_f, $x_gestor_id);
		
		echo "dias agragados".$x_dias_agregados." ";
		
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
		echo "FECHA EJECUTA".$x_fecha_ejecuta_act;
		#####################################################################################################################
		#####################################  TAREAS DIARIAS GESTOR  #######################################################
		#####################################################################################################################
		
		// primero verifamos que la tarea aun no este en la lista, es decir, que se trate de la primera vez que entra al cliclo para este credito.
		$sqlBuscaatreaAsignada = "SELECT COUNT(*) AS atreas_asignadas FROM `tarea_diaria_gestor` WHERE fecha_ingreso =  \"$currdate\" AND gestor_id = $x_gestor_id ";
		$sqlBuscaatreaAsignada .= " AND `caso_id` = $x_caso_id_f";
		$rsBuscatareaAsignada = phpmkr_query($sqlBuscaatreaAsignada,$conn) or die("Erro al buscar atrea".phpmkr_error()."sql:".$sqlBuscaatreaAsignada);
		echo "BUSCA TAREAS".$sqlBuscaatreaAsignada."<BR>";
		$rowBuscaTareaAsignada = phpmkr_fetch_array($rsBuscatareaAsignada);
		$x_tareas_asignadas_del_caso = $rowBuscaTareaAsignada["atreas_asignadas"];
		echo "TAREAS ASIGNADAS ".$x_tareas_asignadas_del_caso."<br>";
		//se inserta la tarea en la lista de las actividades diarias del promotor
		if ($x_tareas_asignadas_del_caso < 1){
		$sqlInsertListaTarea = "INSERT INTO `tarea_diaria_gestor`";
		$sqlInsertListaTarea .= " (`tarea_diaria_gestor_id`, `gestor_id`, `zona_id`, `dia_semana`, `fecha_ingreso`, `fecha_lista`, `caso_id`, ";
		$sqlInsertListaTarea .= " `tarea_id`, `reingreso`, `fase`, `status_tarea`, `credito_id`) ";
		$sqlInsertListaTarea .= "VALUES (NULL, $x_gestor_id, $x_dia_zona_f , $x_dia_zona_f, \"$currdate\",\"$x_fecha_tarea_f\", $x_caso_id_f, $x_tarea_id_f, '0', '6', '1', $x_credito_id);";
		$rsInsertListaTarea = phpmkr_query($sqlInsertListaTarea,$conn)or die("Error al insertar en lista diaria tareasqqq".phpmkr_error()."sql:".$sqlInsertListaTarea);	 
		}
		
		
		
		
		
		
		}// CASO CRM > 0 DE CARTA 1
		
		}// SI NO EXISTE LA PROMESA DE PAGO PARA CARAT 1
		
		}// if carta3 ya existe, pero entra al ciclo de pp carta 3	
		
		
	if(($x_carta_3 > 0) && ($x_ciclo_carta_3 == 0) && ($x_carta_D == 0)){
		
		
		
			$sqlCarta3 = "SELECT COUNT(*) AS crm_tarea_id FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND  crm_tarea_tipo_id = 10 AND orden= 12";
		$rsCarta3 = phpmkr_query($sqlCarta3,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCarta3);

		$rowCarata3 = phpmkr_fetch_array($rsCarta3);
		$x_carta_3_tarea_id = $rowCarata3["crm_tarea_id"];
		if($x_carta_3_tarea_id >0 ){
			//buscamos que ya tenga Una promesa de pago y que este vencida para entrar al ciclo			
			$sqlPPC3 = "SELECT * FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id  AND crm_tarea_tipo_id = 5 AND orden = 13 ";
			$rsppc3 = phpmkr_query($sqlPPC3,$conn)or die ("Error al seleccioanr la pp c2".phpmkr_error()."sql:".$sqlPPC3);
			$rowppc3 = phpmkr_fetch_array($rsppc3);
			$x_fecha_registro_tarea = $rowppc3["fecha_registro"];
			$x_tarea_ppc3 =$rowppc3["crm_tarea_id"];
			$x_caso_ppc3 = $rowppc3["crm_caso_id"];			
			if($x_caso_ppc3> 0){
				// la promesa de pago esta registrada; buscamos la fecha de pagoo de la promesa
				$sqlcrm_tarea_cv3 = "SELECT COUNT(*) AS promesas, promesa_pago  FROM crm_tarea_cv WHERE crm_tarea_id = $x_tarea_ppc1 ";
				$rscrm_tarea_cv3 = phpmkr_query($sqlcrm_tarea_cv3,$conn)or die("Error al selecionar la fecha de promesa de pago".phpmkr_error()."sql:".$sqlcrm_tarea_cv3);
				$rowcrm_tarea_cv3 = phpmkr_fetch_array($rscrm_tarea_cv3);
				$x_promesas_c3 = $rowcrm_tarea_cv3["promesas"];
				$x_fecha_promesa_pago_c3 = $rowcrm_tarea_cv3["promesa_pago"]; 			
				}			
			}	
			$x_hoy_z = date("Y-m-d");
			if($x_promesas_c3 > 0){
				if($x_hoy_z > $x_fecha_promesa_pago_c3){					
					
		
	###########################################################################################################################################
	###########################################################################################################################################
	###################################################            CARTA DEMANDA           ####################################################
	###########################################################################################################################################
	###########################################################################################################################################
	//etapa 8 CARTA DEMANDA			
	
		
	# SI Y SOLO SI NO EXISTE UNA TREA DE CARTA 3 GENERADA PARA ESE DIA;
	
	$SQLCARTA3 = "SELECT COUNT(*) AS carta3 FROM crm_tarea WHERE fecha_registro = \"$currdate\" AND crm_caso_id = $x_crm_caso_id AND orden = 20 AND crm_tarea_tipo_id= 12 ";	
	#echo "carata 3".$SQLCARTA3."<br>";
	$RSCARTA3 = phpmkr_query($SQLCARTA3,$conn)or die ("Error al buscar c3 existente".phpmkr_error()."sql:". $SQLCARTA3);
	$ROWCARA3 = phpmkr_fetch_array($RSCARTA3);	
	$x_carta3_existente = $ROWCARA3["carta3"] +0;	
#	echo "carta 3 existe".$x_carta3_existente."--<br>";	
			// seleccionamos los datos para la nueva tarea
			
			# si no existe carata 3 entonces si insertamos la demanda
		if($x_carta3_existente < 1)	{	
				$sSqlWrk = "SELECT *
							FROM 
							crm_playlist
							WHERE 
							crm_playlist.crm_caso_tipo_id = 3
							AND crm_playlist.orden = 20 "; // orden 20 DEMANDA	
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
	
	
	
				$x_origen = 1;  // el origen se podria cambiar a responsable de sucursal.
				$x_bitacora = "Cartera Vencida  SE PASA A ETAPA 8 DEMANDA- (".FormatDateTime($currdate,7)." - $currtime)";
			
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


		
		
		// seleccionamos el usuario del GESTOR JURIDICO EL RESPONSABLE DE LA TAREA ES EL GESTOR JURIDICO HASTA AHORA Rodirigo
		
				
		$sSqlWrk = "
		SELECT usuario_id
		FROM 
			usuario
		WHERE 
			usuario_rol_id = 13"; // el usuario juridica // es este momento solo tenemos a rodrigo registrado.
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		echo "sql uuario demanda".$sSqlWrk."<br>";
		$x_usuario_id = $datawrk["usuario_id"];
		@phpmkr_free_result($rswrk);
		
	
			// se debe verificar antes de ingresar la tarea que no exista ninguna tarea para el caso de este tipo
			$sqlBuscaTarea =  "SELECT COUNT(*) AS tareas_exist from  crm_tarea where crm_caso_id = $x_crm_caso_id AND crm_tarea_tipo_id = $x_tarea_tipo_id ";
			$sqlBuscaTarea .= " AND crm_tarea_status_id IN (1,2) AND destino = $x_usuario_id";	
			$rsBuscaTarea = phpmkr_query($sqlBuscaTarea,$conn) or die ("Erro al insertar en tarea 3".phpmkr_error()."sql:".$sqlBuscaTarea);
			$rowBuscaTarea = phpmkr_fetch_array($rsBuscaTarea);
			$x_tareas_abiertas = $rowBuscaTarea["tareas_exist"];
			
		#	echo "<br><br> TREAS ABIERTAS ".$x_tareas_abiertas."<BR><BR>".$sqlBuscaTarea."<BR><BR>";
			if($x_tareas_abiertas == 0){
			$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 7, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
			// la tarea la ejecuta el asesor de crédito
			$rs = phpmkr_query($sSql,$conn) or die("Error al seleccionar insertar en tarea para promotor".phpmkr_error()."sql:".$sSql);
		echo "INSERTA DEMANDA<BR> ".$sSql."<BR>";
			//$x_result = phpmkr_query($sSql, $conn);
			$x_tarea_id = mysql_insert_id();
	
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
		
			}// tareas registradas = 0
			
		}// ya se vencio la fecha de promesa de pago				
			}//existe la promesa de pago
		
	}//carata 3 no existe
		
		}// SE GENERA LA DEMANDA Y SE CAMBIA TAREA A RODRIGO		
		
	if(($x_carta_D > 0) && ($x_ciclo_carta_D == 1)){
		// ya se le genero la carta D
		// ya esta en fase 9 Y AQUI SE CICLA
		// ya tiene una o mas promesas de pago...
		// SE genera una promesa de pagao al promotor de credito (angelica, monica, jose) 
		// carta 1 ya existe, pero debemos de verificar que la tarea esta vencida o esta cerrada
		// si ya se vencio la tarea o ya se netrego la carta entonces si ya podemos programar la sigueinte tarea
		// de lo contrario si el credito tuvuera mas de dos vencidos.. generaria la carata y tambien generaria la PP 
		
		# PONEMOS LA CONDICIONES DEL CICLO		
		#verificamos que no tenga ninguna PP con status de pendiente;	
		
		echo "ENTRO A PROMESA DE PAGO DEMANADA -------------------------<BR>";
		$x_carta_DPP = 0;
		$sqlCartaDPP = "SELECT COUNT(*) AS carta_D_PP FROM crm_tarea WHERE crm_caso_id = $x_crm_caso_id AND crm_tarea_tipo_id = 5 AND orden= 24 AND crm_tarea_status_id = 1";
		$rsCartaDPP = phpmkr_query($sqlCartaDPP,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCartaDPP);
		$rowCarataDPP = phpmkr_fetch_array($rsCartaDPP);
		$x_carta_DPP = $rowCarataDPP["carta_D_PP"]+0;
		// se agrega la tarea siemore y cuando no exista una tarea igaul en status pendiente	
		
		if(($x_carta_DPP < 1)){
			
		#SELECCIONAMOS LOS DATOS DE  LA TAREA
		
		$sSqlWrk = "
		SELECT *
		FROM 
			crm_playlist
		WHERE 
			crm_playlist.crm_caso_tipo_id = 3
			AND crm_playlist.orden = 24"; // PROMESA DE PAGO CARTA D
		
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
			usuario_rol_id = 13"; // el usuario juridica // es este momento solo tenemos a rodrigo registrado.
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_usuario_id = $datawrk["usuario_id"];
		@phpmkr_free_result($rswrk);
		
		// seleccionamos el usuario del juridico
		
		
		
			
			



		if(($x_crm_caso_id > 0)){

			$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 2, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
		
			$x_result = phpmkr_query($sSql, $conn);
			$x_tarea_id = mysql_insert_id();
	
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
	
			phpmkr_query($sSql, $conn) or die("Error al actualiza los comentarios de la bitacora".phpmkr_error()."sql:".$sSql);		
		
		
		}// CASO CRM > 0 DE CARTA 1
		
		}// SI NO EXISTE LA PROMESA DE PAGO PARA CARAT 1
		
		}// if carta3 ya existe, pero entra al ciclo de pp carta 3	
	
	if( ($x_carta_D > 0) && ($x_ciclo_carta_D == 0))	{
		// se agrega el caso a comite de mora
		
		
		##################################################################################
		##################################################################################
		########################     codigo comite de mora      ##########################
		##################################################################################
		##################################################################################
		
		// primero verificamos que no existe ese caso en comite de mora
		$x_comite_i = 0;
		$sqBuscaComite =  "SELECT COUNT(*) AS insertado FROM comite_mora WHERE credito_id = $x_credito_id" ;
		$rsBuscaComite = phpmkr_query($sqBuscaComite,$conn)or die ("error al seleccionar de comite de mora".phpmkr_error()."sql:".$sqBuscaComite);
		$rowBuscacomite = phpmkr_fetch_array($rsBuscaComite);
		$x_comite_i = $rowBuscacomite["insertado"] + 0;
		if($x_comite_i < 1){
		$sqlInsercomite = "INSERT INTO `comite_mora` (`comite_mora_id`, `credito_id`, `fecha_ingreso`, `otro`)";
		$sqlInsercomite .= " VALUES (NULL, $x_credito_id, \"$currdate\", NULL)";
		$rsInsertcomite = phpmkr_query($sqlInsercomite,$conn) or die("WError al insertar en comite mora".phpmkr_error()."sql:".$sqlInsercomite);
		}
		
		}
		
	}// si no hay tatras pendientes del caso
	}// else caso abierto
	}// dias vencido mayor a dias de gracia para CRM
	
	
}// fin while moratorios




	
	
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
		 
		 $x_contador_tareas_auxiliar = 0;
	
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
					echo "entra a contador<br>".$x_contador_tareas_auxiliar."<br>";
						
						
					 $x_tareas_asignadas_zona_promotor = $rowCuentaTareasZona["total_tareas_asignadas"];
						// cambiamos la fecha de la tarea para verla en  la lista
						
						#####################################################
						#################    updates   ######################
						#####################################################
						
						$sqlUpdateFechaTar = "UPDATE tarea_diaria_promotor SET fecha_lista = \"$x_fecha_criterio\", reingreso = (reingreso +1) WHERE tarea_diaria_promotor_id =$x_tar_zona_pro_id";
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
						$sqlResago = "INSERT INTO `tarea_resagada` (`tarea_resagada_id`, `crm_tarea_id`, `fecha_ingreso`) ";
						$sqlResago .= " VALUES (NULL, $x_tarea_id,\"$x_fecha_ejecuta_act\") ";
						$rsResago  = phpmkr_query($sqlResago,$conn)or die ("Error al insertar en RESAGO".phpmkr_error()."sql:".$sqlResago);
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
	
		 if($x_tareas_asignadas_de_hoy < 25){
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
		
		


//@phpmkr_free_result($rswrk);
phpmkr_db_close($conn);		
		