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
$hoy = date("Y-m-d");

//$x_dia = strtoupper($currentdate["weekday"]);

//$currdate = "2007-07-10";
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);


// buscamos todos las propuestas de convenio que se registraron el dia de hoy
$sqlPropuestas = " SELECT * FROM convenio_propuesta WHERE fecha_registro = \"$hoy\" ";
echo $sqlPropuestas;
$rsPropuesta = phpmkr_query($sqlPropuestas,$conn) or die("error al seleccionar el dato".phpmkr_error()."sql:".$sqlPropuestas);
while($rowPropuestas = phpmkr_fetch_array($rsPropuesta)){
	$x_credito_num = $rowPropuestas["credito_num"];
	$x_monto = $rowPropuestas["monto_pp"];
	$x_fecha = $rowPropuestas["fecha_pp"];
	echo "<br>credito num ".$x_credito_num ;
	
	$sqlCredito = "SELECT credito_id FROM  credito WHERE credito_num = $x_credito_num ";
	$rsCredito = phpmkr_query($sqlCredito,$conn) or die ("Error al seleccioa".phpmkr_error()."sql:".$sqlCredito);
	$rowCredito = phpmkr_fetch_array($rsCredito);
	$x_credito_id = $rowCredito["credito_id"];
	
	$sqlTareasCasos = "SELECT crm_caso_id  FROM crm_caso where credito_id = $x_credito_id and crm_caso_tipo_id = 3 ";
	$rsTareasCasos = phpmkr_query($sqlTareasCasos,$conn) or die ("Error al seleccioa".phpmkr_error()."sql:".$sqlCredito);
	$rowTareasCasos = phpmkr_fetch_array($rsTareasCasos);
	$x_crm_caso_id = $rowTareasCasos["crm_caso_id"];
	if($x_crm_caso_id >0){
		//cerramos todas las tareas y el caso
		$sqlUdateTarea = "UPDATE crm_caso SET crm_caso_status_id = 4 WHERE crm_caso_id = $x_crm_caso_id ";
		$rsUpdate = phpmkr_query($sqlUdateTarea,$conn)or die ("Error".phpmkr_error()."sql:".$sqlUdateTarea);
		$sqlUdateTarea = "UPDATE crm_tarea SET crm_tarea_status_id = 4 WHERE crm_caso_id = $x_crm_caso_id ";
		$rsUpdate = phpmkr_query($sqlUdateTarea,$conn)or die ("Error".phpmkr_error()."sql:".$sqlUdateTarea);
		
		}		
	// ya se cerraron todas las tareas ahora le agregamos una nueva promesa de pago al cliente	
		
		
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
		$x_asunto = $x_asunto. "= PP POR CONVENIO =";
	
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

      //$x_responsable_susursal_usuario_id_ext = $x_responsable_susursal_usuario_id;
		## sepone vacia para qe no asigne cartas 1.. siempre debe de iniciar con pp para el cliente.
		#if(empty($x_responsable_susursal_usuario_id_ext)  ){ originla
		$x_usuario_id  = 7202; // se cambia el usuario
			
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
			echo "INSERT TAREA:<BR>".$sSql."";
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




?>