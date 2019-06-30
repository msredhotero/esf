<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>

<?php

$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);



		$x_credito_id = 4276;
		$x_credito_id = 4258;
		$x_credito_id = 4246;
		$x_credito_id = 3943;
	

	$sSqlWrk = "SELECT solicitud_id, credito_tipo_id  FROM credito where credito_id = $x_credito_id";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$rowwrk = phpmkr_fetch_array($rswrk);
	$x_solicitud_id = $rowwrk["solicitud_id"];
	echo "solcitud_id ".$x_solicitud_id."<br>"; 
	$x_tipo_credito = $rowwrk["credito_tipo_id"];
	@phpmkr_free_result($rswrk);

	$sSqlWrk = "SELECT count(*) as pendientes FROM vencimiento where credito_id = $x_credito_id and vencimiento_status_id in (1,3,6)";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$rowwrk = phpmkr_fetch_array($rswrk);
	$x_pendientes = $rowwrk["pendientes"];
	@phpmkr_free_result($rswrk);

	if($x_pendientes == 0){
	//FINIQUITA CREDITO
		$sSqlWrk = "UPDATE credito set credito_status_id = 3 where credito_id = $x_credito_id";
		//phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);

		$sSqlWrk = "UPDATE solicitud set solicitud_status_id = 7 where solicitud_id = $x_solicitud_id";
		//phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		
		$GLOBALS["x_folio_triple"] = 1;
		//seleccionam22os los vencimientos
		$sqlVencimientos = "SELECT * FROM vencimiento where credito_id = $x_credito_id";
		$responseVen = phpmkr_query($sqlVencimientos,$conn)  or die("Error en select vencimientos".phpmkr_error()."sql:".$sqlVencimientos);
		
		$x_vencimiento_id = "";
		$x_fecha_vencimiento = "";
		while($rowVencimientos = phpmkr_fetch_array($responseVen)){
			echo "entro a vencimeinto <P>";
			echo "<p>";
			// mientras hay vencimientos
			$x_vencimiento_id = $rowVencimientos["vencimiento_id"];
			$x_fecha_vencimiento_act = $rowVencimientos["fecha_vencimiento"];
			$GLOBALS["x_fecha_vencimiento_ACT"] = $rowVencimientos["fecha_vencimiento"];
			
			$sqlRecibo_Vencimientos = "SELECT * FROM recibo_vencimiento  where vencimiento_id = $x_vencimiento_id";
			$responseREC_VEN = phpmkr_query($sqlRecibo_Vencimientos,$conn)  or die("Error en selectrecibo_vencimientos".phpmkr_error()."sql:".$sqlRecibo_Vencimientos);
			while($rowRec_ven = phpmkr_fetch_array($responseREC_VEN)){
				
				echo "entro a ven_recibo";
				$x_recibo_vencimiento_id = $rowRec_ven["recibo_vencimiento_id"];
				$x_recibo_id =  $rowRec_ven["recibo_id"];
				
				
				$sqlRecibo = "SELECT * FROM recibo  where recibo_id = $x_recibo_id";
				$responseRec = phpmkr_query($sqlRecibo ,$conn)  or die("Error en select recibo".phpmkr_error()."sql:".$sqlRecibo );
				
				while($rowRecibos = phpmkr_fetch_array($responseRec)){
					echo "entro a recibo<p>";
					$x_recibo_id = $rowRecibos["recibo_id"];
					$x_fecha_pago = $rowRecibos["fecha_pago"];
					
					$fecha_del_vencimiento = $GLOBALS["x_fecha_vencimiento_ACT"];
					/*$fecha_del_vencimiento = strtotime($fecha_del_vencimiento);   
					$fecha_del_pago = '"'.$x_fecha_pago.'"'
					$fecha_del_pago = strtotime($fecha_del_pago);*/
					
					echo "fecha_vencimiento".$fecha_del_vencimiento."<p>";				
					echo "fecha_pago".$x_fecha_pago."<p>";
					echo "fecha_del_vencimiento == fecha_del_pago".$fecha_del_vencimiento == $fecha_del_pago."";
					echo "fecha_del_pago < fecha_del_vencimiento".$fecha_del_pago < $fecha_del_vencimiento."-";
					if(($fecha_del_vencimiento == $x_fecha_pago)||($x_fecha_pago < $fecha_del_vencimiento)){
						echo("fecha vencimiento es igual a fecha pago o es menor la fecha de vencimiento");
						if($GLOBALS["x_folio_triple"] == 1){
						$GLOBALS["x_folio_triple"] = 1;	
						
						
						echo "folio triple es =".$GLOBALS["x_folio_triple"]."-<p>"; 
						}
					}else{
						
						echo "fecha pago es mayo a fecha vencimeinto";
							$GLOBALS["x_folio_triple"] = 0;
								echo "folio triple es =".$GLOBALS["x_folio_triple"]."-<p>"; 
							}
					
					
					
					}//while recibos
					phpmkr_free_result($responseRec);
				
				}	// while recibo vencimeinto
				phpmkr_free_result($responseREC_VEN);
		
													
		}// fin while vencimientos
		 phpmkr_free_result($responseVen);
		 
		 // compara fechas
		 
		
		 
		
		//$fecha_actual = strtotime(date("d-m-Y H:i:00",time())); 
		//$fecha_inicio_sorteo = strtotime("2011-04-01");
		//$fecha_limite_sorteo = strtotime("2011-12-04");
		
		$fecha_actual = strtotime(date("Y-m-d ",time())); 
		$fecha_inicio_sorteo = strtotime("2011-04-01");
		$fecha_limite_sorteo = strtotime("2011-12-10");
		//$fecha_limite_sorteo = strtotime("04-04-2011 21:00:00");
		
		
		//FOLIOS PARA CREDITOS PERSONALES
		
		if($x_tipo_credito == 1){
		if($GLOBALS["x_folio_triple"] == 1){
			//pagos puntuales 3 folios	
			echo "entro a folio triple 1 en credito individual";	  
		if(($fecha_actual > $fecha_inicio_sorteo)&&($fecha_actual < $fecha_limite_sorteo)){ 
		//fecha valida para generar los folios de la rifa
				echo "entro a fecha del sorteo";
			//generamos la clave del folio
			$x_cont = 1;
			while($x_cont <= 3){
				echo "entro a while 3<p>";
				
					$sSqlFOL = "SELECT count(*) as folios FROM folio_rifa ";
					$rswrkF = phpmkr_query($sSqlFOL,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlFOL);
					$rowwrkF = phpmkr_fetch_array($rswrkF);
					$x_numero_folios = $rowwrkF["folios"];
					@phpmkr_free_result($rswrkF);

				
				if($x_numero_folios < 2000){
					echo "numero de folios menor de 2000";
			//alimentamos el generador de aleatorios
			//mt_srand((double)microtime()*1000000);			
			//mt_srand (microtime()*1000000);
			//generamos un número aleatorio
			//$numero_aleatorio = mt_rand(1,2000);
		// SE CAMBIA LA FORMA DE GENERAR EL FOLIO A HORA SERA CONSECUTIVO
		
		$sqlFolio = "SELECT MAX(folio) as ultimo_folio FROM folio_rifa";
		$responseFolio = phpmkr_query($sqlFolio,$conn)or die("error en folio max".phpmkr_error()."sql: ".$sqlFolio);
		$rowFolio = phpmkr_fetch_array($responseFolio);
		$x_ultimo_folio =  $rowFolio["ultimo_folio"];
		$x_folio_nuevo = intval($x_ultimo_folio)+ 1;
			/*
			$GLOBALS["rowNA"] = 1;
			while($GLOBALS["rowNA"] == 1){
				// el numero ya existe se genera otro
				mt_srand (microtime()*1000000);
				$numero_aleatorio = mt_rand(1,2000);
					
				echo "folio es :".$numero_aleatorio."<p>";
				$sqlNA ="SELECT count(*) as folio_repetido FROM folio_rifa WHERE folio = $numero_aleatorio";
				$responseNA = phpmkr_query($sqlNA, $conn) or die ("Error en select folio");
				$rowNA = phpmkr_fetch_array($responseNA);
				$x_folio_repetido = $rowNA["folio_repetido"];
				echo "folio repatido =".$x_folio_repetido."-<p>";
				if($x_folio_repetido == 0){
					$GLOBALS["rowNA"] = 2;					
					}		
			
			echo $numero_aleatorio. "<p>";
			
			
			}*/// fin while $rowNA= 1
			
			
			//insertar en la tabla folio_rifa
				$fieldList = NULL;	
				// Field visitas
				$theValue = ($x_solicitud_id != "") ? intval($x_solicitud_id) : "0";
				$fieldList["`solicitud_id`"] = $theValue;		
				
				// Field clave
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_nombre_completo) : $x_nombre_completo; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`nombre_completo`"] = $theValue;			
			
				// Field visitas
				$theValue = ($x_folio_nuevo != "") ? intval($x_folio_nuevo) : "0";
				$fieldList["`folio`"] = $theValue;				
				
				// insert into database
				$sSql = "INSERT INTO `folio_rifa` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
				
				//$x_result = phpmkr_query($sSql, $conn);
				echo $sSql."<br>";
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
			
				}//fin numero de folios
				
				
				$x_cont++;
			} // while 3
			
			
		}// fechas sorteo	
				
				
			
				
			
			}else if($GLOBALS["x_folio_triple"] == 0){
				// pagos no puntales  1 folio
				echo "folio_triple = 0";
				
				if(($fecha_actual > $fecha_inicio_sorteo)&&($fecha_actual < $fecha_limite_sorteo)){ 
		//fecha valida para generar los folios de la rifa
		echo("fecha dentro sorteo bien");
			//generamos la clave del folio
			$x_cont = 1;
			while($x_cont <= 1){
				
				$sSqlFOL = "SELECT count(*) as folios FROM folio_rifa ";
				$rswrkF = phpmkr_query($sSqlFOL,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlFOL);
				$rowwrkF = phpmkr_fetch_array($rswrkF);
				$x_numero_folios = $rowwrkF["folios"];
				@phpmkr_free_result($rswrkF);				
				
				if($x_numero_folios < 2000 ){
			//alimentamos el generador de aleatorios
			//mt_srand((double)microtime()*1000000);			
			//mt_srand (microtime()*1000000);
			//generamos un número aleatorio
			//$numero_aleatorio = mt_rand(1,2000); 
			
			/*$rowNA = 1;
			while($rowNA == 1){
				// el numero ya existe se genera otro
				mt_srand (microtime()*1000000);
				$numero_aleatorio = mt_rand(1,2000);				
				$sqlNA ="SELECT count(*) as folio_repetido FROM folio_rifa WHERE folio = $numero_aleatorio";
				$responseNA = phpmkr_query($sqlNA, $conn) or die ("Error en insert folio");
				$rowNA = phpmkr_fetch_array($responseNA);
				$x_folio_repetido = $rowNA["folio_repetido"];
				if($x_folio_repetido == 0){
					$rowNA = 2;
					
					}		
			
			echo $numero_aleatorio. "<p>";
			
			
			}*/// fin while $rowNA= 1
			
			//folios consecutivos
		$sqlFolio = "SELECT MAX(folio) as ultimo_folio FROM folio_rifa";
		$responseFolio = phpmkr_query($sqlFolio,$conn)or die("error en folio max".phpmkr_error()."sql: ".$sqlFolio);
		$rowFolio = phpmkr_fetch_array($responseFolio);
		$x_ultimo_folio =  $rowFolio["ultimo_folio"];
		$x_folio_nuevo = intval($x_ultimo_folio)+ 1;
			
			
			
			//insertar en la tabla folio_rifa
				$fieldList = NULL;	
				// Field visitas
				$theValue = ($x_solicitud_id != "") ? intval($x_solicitud_id) : "0";
				$fieldList["`solicitud_id`"] = $theValue;
				// Field visitas
				// Field clave
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_nombre_completo) : $x_nombre_completo; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`nombre_completo`"] = $theValue;
				// Field visitas
				$theValue = ($x_folio_nuevo != "") ? intval($x_folio_nuevo) : "0";
				$fieldList["`folio`"] = $theValue;				
				
				// insert into database
				$sSql = "INSERT INTO `folio_rifa` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
				//$x_result = phpmkr_query($sSql, $conn);
				$x_result = true;
				
				echo $sSql."<br>";
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
			
			
				}
			$x_cont++;
			} // while 3
			
			
		}// fechas sorteo
				
				
				}
		} else {
			//FOLIOS PARA CREDITOS SOLIDARIOS
			echo "tipo de credito grupal<br>";
			// SELECCIONAMOS EL ID DEL GRUPO
			$sqlgid = "SELECT * FROM  creditosolidario  WHERE creditoSolidario_id IN(SELECT grupo_id FROM solicitud_grupo WHERE  solicitud_id = $x_solicitud_id)";
			$responsegid = phpmkr_query($sqlgid, $conn) or die("error al seleccionar los datos del gruopo".phpmkr_error()."sql;".$sqlgid);
			$rowGrupo = phpmkr_fetch_array($responsegid);
			echo  $sqlgid."<br>";
			$GLOBALS["x_creditoSolidario_id"] =  $rowGrupo["creditoSolidario_id"];
			$GLOBALS["x_nombre_grupo"] = $rowGrupo["nombre_grupo"];
			
			$x_cont_gid = 1;
			while($x_cont_gid <= 10){	
			echo "entro al while de folio para cada integrante <br> integrante ".$x_cont_gid."<br>";
				
				$x_cliente_id_grupo_actual = $rowGrupo["cliente_id_$x_cont_gid"];
				echo "cliente id".$x_cliente_id_grupo_actual."<br>";
			
			
			if(!empty($x_cliente_id_grupo_actual) && is_numeric($x_cliente_id_grupo_actual)){
			
			echo "entras---------------------------------------";
			
			if($GLOBALS["x_folio_triple"] == 1){
			//pagos puntuales 3 folios	
			echo "entro a folio triple 1";	  
		if(($fecha_actual > $fecha_inicio_sorteo)&&($fecha_actual < $fecha_limite_sorteo)){ 
		//fecha valida para generar los folios de la rifa
				echo "entro a fecha del sorteo";
			//generamos la clave del folio
			$x_cont = 1;
			while($x_cont <= 3){
				echo "entro a while 3<p>";
				
					$sSqlFOL = "SELECT count(*) as folios FROM folio_rifa ";
					$rswrkF = phpmkr_query($sSqlFOL,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlFOL);
					$rowwrkF = phpmkr_fetch_array($rswrkF);
					$x_numero_folios = $rowwrkF["folios"];
					@phpmkr_free_result($rswrkF);

				
				if($x_numero_folios < 2000){
					echo "numero de folios menor de 2000";
			//alimentamos el generador de aleatorios
			//mt_srand((double)microtime()*1000000);			
			//mt_srand (microtime()*1000000);
			//generamos un número aleatorio
			//$numero_aleatorio = mt_rand(1,2000);
		// SE CAMBIA LA FORMA DE GENERAR EL FOLIO A HORA SERA CONSECUTIVO
		
		$sqlFolio = "SELECT MAX(folio) as ultimo_folio FROM folio_rifa";
		$responseFolio = phpmkr_query($sqlFolio,$conn)or die("error en folio max".phpmkr_error()."sql: ".$sqlFolio);
		$rowFolio = phpmkr_fetch_array($responseFolio);
		$x_ultimo_folio =  $rowFolio["ultimo_folio"];
		$x_folio_nuevo = intval($x_ultimo_folio)+ 1;
			/*
			$GLOBALS["rowNA"] = 1;
			while($GLOBALS["rowNA"] == 1){
				// el numero ya existe se genera otro
				mt_srand (microtime()*1000000);
				$numero_aleatorio = mt_rand(1,2000);
					
				echo "folio es :".$numero_aleatorio."<p>";
				$sqlNA ="SELECT count(*) as folio_repetido FROM folio_rifa WHERE folio = $numero_aleatorio";
				$responseNA = phpmkr_query($sqlNA, $conn) or die ("Error en select folio");
				$rowNA = phpmkr_fetch_array($responseNA);
				$x_folio_repetido = $rowNA["folio_repetido"];
				echo "folio repatido =".$x_folio_repetido."-<p>";
				if($x_folio_repetido == 0){
					$GLOBALS["rowNA"] = 2;					
					}		
			
			echo $numero_aleatorio. "<p>";
			
			
			}*/// fin while $rowNA= 1
			
			
			//insertar en la tabla folio_rifa
				$fieldList = NULL;	
				// Field solcitud_id
				$theValue = ($x_solicitud_id != "") ? intval($x_solicitud_id) : "0";
				$fieldList["`solicitud_id`"] = $theValue;
				
				// Field cliente_id
				$theValue = ($x_cliente_id_grupo_actual != "") ? intval($x_cliente_id_grupo_actual) : "0";
				$fieldList["`cliente_id`"] = $theValue;		
				
				
				// Field clave
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_nombre_completo) : $x_nombre_completo; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`nombre_completo`"] = $theValue;			
			
				// Field visitas
				$theValue = ($x_folio_nuevo != "") ? intval($x_folio_nuevo) : "0";
				$fieldList["`folio`"] = $theValue;				
				
				// insert into database
				$sSql = "INSERT INTO `folio_rifa` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
				//$x_result = phpmkr_query($sSql, $conn);
				
				
				$x_result = true;
				echo "--------".$sSql."<br>";
				
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
			
				}//fin numero de folios
				
				
				$x_cont++;
			} // while 3
			
			
		}// fechas sorteo	
				
				
			
				
			
			}else if($GLOBALS["x_folio_triple"] == 0){
				// pagos no puntales  1 folio
				echo "folio_triple = 0";
				
				if(($fecha_actual > $fecha_inicio_sorteo)&&($fecha_actual < $fecha_limite_sorteo)){ 
		//fecha valida para generar los folios de la rifa
		echo("fecha dentro sorteo bien");
			//generamos la clave del folio
			$x_cont = 1;
			while($x_cont <= 1){
				
				$sSqlFOL = "SELECT count(*) as folios FROM folio_rifa ";
				$rswrkF = phpmkr_query($sSqlFOL,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlFOL);
				$rowwrkF = phpmkr_fetch_array($rswrkF);
				$x_numero_folios = $rowwrkF["folios"];
				@phpmkr_free_result($rswrkF);				
				
				if($x_numero_folios < 2000 ){
			
			
			//folios consecutivos
		$sqlFolio = "SELECT MAX(folio) as ultimo_folio FROM folio_rifa";
		$responseFolio = phpmkr_query($sqlFolio,$conn)or die("error en folio max".phpmkr_error()."sql: ".$sqlFolio);
		$rowFolio = phpmkr_fetch_array($responseFolio);
		$x_ultimo_folio =  $rowFolio["ultimo_folio"];
		$x_folio_nuevo = intval($x_ultimo_folio)+ 1;
			
			
			
			//insertar en la tabla folio_rifa
				$fieldList = NULL;	
				// Field visitas
				$theValue = ($x_solicitud_id != "") ? intval($x_solicitud_id) : "0";
				$fieldList["`solicitud_id`"] = $theValue;
				// Field cliente_id
				$theValue = ($x_cliente_id_grupo_actual != "") ? intval($x_cliente_id_grupo_actual) : "0";
				$fieldList["`cliente_id`"] = $theValue;		
				// Field clave
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_nombre_completo) : $x_nombre_completo; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`nombre_completo`"] = $theValue;
				// Field visitas
				$theValue = ($x_folio_nuevo != "") ? intval($x_folio_nuevo) : "0";
				$fieldList["`folio`"] = $theValue;				
				
				// insert into database
				$sSql = "INSERT INTO `folio_rifa` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
				//$x_result = phpmkr_query($sSql, $conn);
				$x_result = true;
				echo  $sSql ."folio 1  <br>";
				
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
			
			
				}
			$x_cont++;
			} // while 3
			
			
		} // fechas sorteo
		echo "entra ".$x_cont_gid."vez<br>";		
				
				}				
			
			}// nuerico no vacio
			
			
			$x_cont_gid ++;
			}
				
			
				
				
				
				
				
				
			
			
			
			}
		
		
		
		//FOLIOS PARA CREDITOS SOLIDARIOS
	}
		
		
		?>