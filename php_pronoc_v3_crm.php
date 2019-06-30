
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



#############################

//PROMESA DE PAGO CARTA 1

$sqlAsignaFeccha = "UPDATE crm_tarea_cv set  promesa_pago = \"$x_ayer\" where promesa_pago IS NULL ";

$sqlAsignaFeccha .= " and crm_tarea_id in (SELECT  `crm_tarea_id` FROM  `crm_tarea` WHERE orden = 1 AND crm_tarea_tipo_id =5  and crm_tarea_status_id = 3)";

$rsAsignaFecha = phpmkr_query($sqlAsignaFeccha,$conn)or die ("Erro al actualiza la fecha de ppc1".phpmkr_error().$sqlAsignaFeccha);



//PROMESA DE PAGO CARTA 1

$sqlAsignaFeccha = "UPDATE crm_tarea_cv set  promesa_pago = \"$x_ayer\" where promesa_pago IS NULL ";

$sqlAsignaFeccha .= " and crm_tarea_id in (SELECT  `crm_tarea_id` FROM  `crm_tarea` WHERE orden = 5 AND crm_tarea_tipo_id = 5 and crm_tarea_status_id = 3)";

$rsAsignaFecha = phpmkr_query($sqlAsignaFeccha,$conn)or die ("Erro al actualiza la fecha de ppc1".phpmkr_error().$sqlAsignaFeccha);



//PROMESA DE PAGO CARTA 2

$sqlAsignaFeccha = "UPDATE crm_tarea_cv set  promesa_pago = \"$x_ayer\" where promesa_pago IS NULL ";

$sqlAsignaFeccha .= " and crm_tarea_id in (SELECT  `crm_tarea_id` FROM  `crm_tarea` WHERE orden = 9 AND crm_tarea_tipo_id = 5 and crm_tarea_status_id = 3 )";

$rsAsignaFecha = phpmkr_query($sqlAsignaFeccha,$conn)or die ("Erro al actualiza la fecha de ppc1".phpmkr_error().$sqlAsignaFeccha);



//PROMESA DE PAGO CARTA 3

$sqlAsignaFeccha = "UPDATE crm_tarea_cv set  promesa_pago = \"$x_ayer\" where promesa_pago IS NULL ";

$sqlAsignaFeccha .= " and crm_tarea_id in (SELECT  `crm_tarea_id` FROM  `crm_tarea` WHERE orden = 13 AND crm_tarea_tipo_id = 5 and crm_tarea_status_id = 3 )";

$rsAsignaFecha = phpmkr_query($sqlAsignaFeccha,$conn)or die ("Erro al actualiza la fecha de ppc1".phpmkr_error().$sqlAsignaFeccha);



//PROMESA DE PAGO CARTA d

$sqlAsignaFeccha = "UPDATE crm_tarea_cv set  promesa_pago = \"$x_ayer\" where promesa_pago IS NULL ";

$sqlAsignaFeccha .= " and crm_tarea_id in (SELECT  `crm_tarea_id` FROM  `crm_tarea` WHERE orden = 17 AND crm_tarea_tipo_id = 5 and crm_tarea_status_id = 3 )";

$rsAsignaFecha = phpmkr_query($sqlAsignaFeccha,$conn)or die ("Erro al actualiza la fecha de ppc1".phpmkr_error().$sqlAsignaFeccha);





// ************************************************   VENCE TAREAS

$sSqlWrk = "update crm_tarea set crm_tarea_status_id = 2 where fecha_ejecuta < '$x_ayer' and crm_tarea_status_id = 1 ";

phpmkr_query($sSqlWrk,$conn);





// vencemos las tareas del promotor en TAREA_DIARIA-PROMOTOR; para que se puedan utilizar paracompletar dias...

/*$sqlUTDP = "UPDATE tarea_diaria_promotor SET status_tarea = 2 where fecha_lista < '$currdate' and tarea_id in (SELECT crm_tarea_id from crm_tarea WHERE crm_tarea_status_id = 2 )AND  status_tarea = 1";

phpmkr_query($sqlUTDP,$conn)or die ("Error al actualiza el status de la tarea".phpmkr_error()."sql:".$sqlUTDP);*/

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

	// cerramos las tareas y cerramos el caso		

		$sSqlWrkven = "update crm_tarea set crm_tarea_status_id = 4 where crm_caso_id = $x_crm_caso_id  and crm_tarea_status_id in (1,2)";

		phpmkr_query($sSqlWrkven,$conn) or die("Error al actulizar los estatusde las tareas liquidadas".phpmkr_error()."sql :".$sSqlWrkven);

		

		/*$sSqlWrkven1 = "update tarea_diaria_promotor set status_tarea = 4 where caso_id = $x_crm_caso_id and status_tarea in (1,2)";

		phpmkr_query($sSqlWrkven1,$conn) or die("Error al actulizar los estatusde las tareas liquidadas en tareas diarias promotor".phpmkr_error()."sql :".$sSqlWrkven1);*/

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

	}

}







// ************************************************   MORATORIOS

$sSqlWrk = "SELECT vencimiento.*, credito.penalizacion, credito.credito_status_id, credito.credito_tipo_id,credito.fecha_otrogamiento, credito.importe as importe_credito, credito.tasa_moratoria, credito.credito_num+0 as crednum, credito.tasa, forma_pago.valor as forma_pago_valor, credito.iva as iva_credito FROM vencimiento join credito 

on credito.credito_id = vencimiento.credito_id join forma_pago on forma_pago.forma_pago_id = credito.forma_pago_id 

where credito.credito_status_id in (1,4) and vencimiento.fecha_vencimiento < '$currdate' and vencimiento.vencimiento_status_id in (1,3,6)  and credito.credito_id not in (35,1064,1049,2499,5762)   order by vencimiento.fecha_vencimiento ";

// and credito.fecha_otrogamiento >= \"2012-06-01\"



//and credito.credito_id > 5000  and credito.credito_id < 5020 

//not in (35,1064,1266,1552,1049,1816,1564,464,1120,557,2499,1375,2164,68,1641,2306,2311,2444,1668,1312,1063,2359,2555,2308,2368)



echo "sql :<br><br><br>***************".$sSqlWrk."<br><br><br><br><br>";



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

   # echo "penal".$x_penalizacion."<br>";

   # aqui volvemos a contar los dias de atraso para ejecutar el crm

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

		

echo "entra a CRM credi. id ".$x_credito_id ."<br>";

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

		AND crm_caso.credito_id = $x_credito_id ";

	

	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);

	$datawrk = phpmkr_fetch_array($rswrk);

	$x_caso_abierto = $datawrk["caso_abierto"];		

	@phpmkr_free_result($rswrk);

	

	

	

	if($x_caso_abierto == 0){		

		// si no hay caso abierto.. quiere decir que es el primer atraso del cliente por lo tanto lo getionara el responsable de sucursal

		// ya no los gestiona el responsable de sucursal; ahora los gestiona el ROL de COBRANZA TEMPRANA //

		$sqlGestorCobranzatemprana = "SELECT count(*) as total_reg  FROM  gestor_credito WHERE credito_id = $x_credito_id ";

		$rsGestorCobranza = phpmkr_query($sqlGestorCobranzatemprana,$conn)or die("Error al seelccionar los casos de cobranza temprana".phpmkr_error()."qls;".$sqlGestorCobranzatemprana);

		$rowGestorCobranza = phpmkr_fetch_array($rsGestorCobranza);

		//$x_user_CT = 7202;

		$x_total_egistrados = $rowGestorCobranza["total_reg"];

		if($x_total_egistrados <1 ){

			// registro la fila en getor credito

			// el getor de cobrana temprana es // Jose Luis Trejo

			$x_gestor_cobri_tem = 7202;

			$sqlInsert = "INSERT INTO gestor_credito VALUES(NULL,$x_credito_id, $x_user_CT)";

			//$rsInsert = phpmkr_query($sqlInsert,$conn) or die ("Error al insertar en gestor credito". phpmkr_error()."sql :". $sqlInsert);			

			}else{

				// ya existe el registro solo se debe modificar el usuario.

				$sqlInsert = "UPDATE gestor_credito SET usuario_id = $x_user_CT WHERE credito_id = $x_credito_id ";

			//$rsInsert = phpmkr_query($sqlInsert,$conn) or die ("Error al insertar en gestor credito". phpmkr_error()."sql :". $sqlInsert);	

				}

		

		

		  //kuki2

		$sqlCT = "SELECT usuario_id FROM gestor_credito WHERE credito_id = $x_credito_id ";

		$rsCT = phpmkr_query($sqlCT,$conn) or die("Error".phpmkr_error().$sqlCT);

		$rowCT = phpmkr_fetch_array($rsCT);

		$x_user_CT = $rowCT["usuario_id"];

		$x_user_CT = 7202;// caso abierto = 0 va entrando a mora asi que le gestor que atendera el caso sera el de cobarnza temprana

		mysql_free_result($rsCT);

		

		echo "ENTRA A CASO ABIERTO == 0<br>" .$x_credito_id."<br>";

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

		echo "<br> cliente_ id ".$sSqlWrk;

		

		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);

		$datawrk = phpmkr_fetch_array($rswrk);

		$x_cliente_id = $datawrk["cliente_id"];

		@phpmkr_free_result($rswrk);

		echo "<br> cliente id ".$x_cliente_id ;

			

		$sSql = "INSERT INTO crm_caso values (0,3,1,1,$x_cliente_id,'".$currdate."',$x_origen,7202,'$x_bitacora','".$currdate."',NULL,$x_credito_id)";	

		$x_result = phpmkr_query($sSql, $conn) or die ("error al insertar carta 1". phpmkr_error()."sql.".$sSql);

		echo "INSERT  :<BR>".$sSql."<BR>";

		$x_crm_caso_id = mysql_insert_id();	

		

		$x_usuario_id = $x_user_CT;

		

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

			LIMIT 1 ";

			

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

	

			#phpmkr_query($sSql, $conn);

			$sqlBuscaStatus = "select * from crm_credito_status where credito_id = $x_credito_id ";

			$rsBuscaStatus = phpmkr_query($sqlBuscaStatus,$conn) or die ("Error al seleccioanr los datos del ststaus".phpmkr_error()."sql:".$sqlBuscaStatus);

			$rowBuscaStatus = phpmkr_fetch_array($rsBuscaStatus);

			$x_credito_status_id = $rowBuscaStatus["credito_id"];

			$x_fecha_h = date("Y-m-d");

			$x_hora = date("H:i:s");

			if(empty($x_credito_status_id)){

				// si no existe lo insertamos				

				$sqlInsert = " INSERT INTO crm_credito_status (`crm_credito_status_id`, `credito_id`, `crm_tarea_id`, `crm_cartera_status_id`, `fecha`) ";

				$sqlInsert .=  " VALUES (NULL, $x_credito_id, $x_tarea_ida,'1', '$x_fecha_h') ";				

				}else{

					// si existe actualizamos el ststus

					$sqlInsert = " UPDATE  `crm_credito_status` SET  `crm_cartera_status_id` =  '1' WHERE  `credito_id` = $x_credito_id " ;

					}

										

			phpmkr_query($sqlInsert,$conn) or die("error al inbsertar en crm_credito_staus".phpmkr_error()."sql;".$sqlInsert);			

			$sqlBuscaContacto = "SELECT * FROM telefono_contacto_status WHERE credito_id = $x_credito_id ";

			$rsBuscaContacto = phpmkr_query($sqlBuscaContacto,$conn) or die ("Error contacto ".phpmkr_error()."sql: ".$sqlBuscaContacto);

			$rowBuscaContacto = phpmkr_fetch_array($rsBuscaContacto);

			$x_id_contato = $rowBuscaContacto["telefono_contacto_status_id"];

			if(empty($x_id_contato)){

			$sqlInsertContacto = " INSERT INTO `telefono_contacto_status` (`telefono_contacto_status_id`, `credito_id`, `telefono_status_id`, `usuario_id`, `fecha`, `hora`) ";

			$sqlInsertContacto .= " VALUES (NULL, $x_credito_id, '1', '5565', '$x_fecha_h', '$x_hora')";

			$rsContacto = phpmkr_query($sqlInsertContacto,$conn) or die ("error al isertar el contacto status".phpmkr_error()."sql;".$sqlInsertContacto); 			

			}

				

		

	

		}

		

	

	}

	}// dias vencidos

	

}// fin while moratorios

//@phpmkr_free_result($rswrk);

phpmkr_db_close($conn);		

		