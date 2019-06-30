<?php session_start(); ?>
<?php ob_start(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link  href="../crm/crm.css" rel="stylesheet" type="text/css" />
<link href="../crm/modulos/pagare_css.css" rel="stylesheet" type="text/css" media="print" />
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Cache-Control: private");
header("Pragma: no-cache"); // HTTP/1.0 
/*
if (@$_SESSION["php_project_esf_status"] <> "login") {
	header("Location:  login.php");
	exit();
}
*/
?>
<STYLE>
@media all {
   div.saltopagina{
      display: none;
   }
}
   
@media print{
   div.saltopagina{
      display:block;
      page-break-before:always;
   }
}
</STYLE> 
<?php
include ("db.php");
include ("phpmkrfn.php");
include("../crm/datefunc.php");
include("amount2txt.php");
include("php_busca_iniciales_usuario.php");

$x_hoy = date("Y-m-d");

$x_gestor_id = $_GET["x_gestor_srch"];
$x_fecha_carta = $_GET["fecha_carta"];
$x_destino_c = $_GET["dirigido"];
$x_edo_cuenta = $_GET["edo_cuenta"];
$x_credito_id = $_GET["credito_id"];
$x_imprimir_estado_cuenta = $x_edo_cuenta;

if($x_imprimir_estado_cuenta == 1){
	ob_end_clean();
	header("Location: php_credito_view_masivo.php?promotor_id=$x_promotor_id&destino=$x_destino_c");		
	}
//destino 1 = titular;
//destino 2 = aval;
$conn = phpmkr_db_connect(HOST, USER, PASS,DB, PORT);

//seleccionamos el usuario del promotor

########################################
//$x_promotor_id = 18;
//$x_hoy = "2013-03-05";
//$x_destino_c = 2;
#echo "destino".$x_destino_c."<br>";
########################################

$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
$currdate = ConvertDateToMysqlFormat($currdate);
$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];	



// seleccionamos todos los casos que tengan para hoy una carta para imprimir
$sSqlGral = "SELECT credito.* from credito ";
$sSqlGral .= " WHERE credito_id = $x_credito_id ";

##se quito del query porque no straba las cartas




$rsGral = phpmkr_query($sSqlGral,$conn) or die ("error al selecciona las tareas de hoy".phpmkr_error()."sql:".$sSqlGral);
$x_registros = mysql_num_rows($rsGral);
#echo "registros  encontrados ".$x_registros."<br>";
while($rowGral = phpmkr_fetch_array($rsGral)){
	// JALAMOS LOS VALORE DE LA TAREA

	$x_orden = 12;
	$x_crm_tarea_tipo_id = 9;
	#echo "tarea_status".$x_crm_tarea_status_id ."<br>";

	
	
	#$x_actualiza_tarea = 0;
	
	$sSql = "SELECT * FROM credito where credito_id = $x_credito_id";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_solicitud_id = $row["solicitud_id"];
	phpmkr_free_result($rs); //KUKI
	
	//AVAL
$sSql = "SELECT * FROM aval where solicitud_id = $x_solicitud_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_nombre_aval = $row["nombre_completo"]." ".$row["apellido_paterno"]." ".$row["apellido_materno"];
phpmkr_free_result($rs);


	
if(strlen ($x_nombre_aval)< 5 ){
	//buscamos el aval en las otras tablas donde puede estar si es que ya no esta en la tabal vieja
	#los datos del aval estan en la tabla datos_aval; por el cambio del formto en la solitud del aval	
	$sSql = " SELECT * FROM  datos_aval WHERE solicitud_id = $x_solicitud_id";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);	
  $x_nombre_aval = $row["nombre_completo"]." ".$row["apellido_paterno"]." ".$row["apellido_materno"];
}
	
	// seleccionamos la fecha de otorgamiento del credito
	// los credito que no se otorgaron antes del 30 DE ABRIL DEL 2012
	// SE IMPRIMIRAN CON CARTA DE NUEVA ADMINISTRACION
	$x_fecha_otorgamiento = "";
	
	$SQLFECHAO = "SELECT fecha_otrogamiento FROM credito WHERE credito_id = $x_credito_id";
	$rsFcehao = phpmkr_query($SQLFECHAO,$conn)or die ("Error al seleccionar la fecha otor del cre". phpmkr_error()."sql:".$SQLFECHAO);
	$rowFechao = phpmkr_fetch_array($rsFcehao);
	$x_fecha_otorgamiento = $rowFechao["fecha_otrogamiento"];
	#echo "fecha_otorgamiento".$x_fecha_otorgamiento."<br>";
	
	
	$sqlCredito = "SELECT * FROM credito WHERE credito_id = $x_credito_id ";
	$rsCredito = phpmkr_query($sqlCredito,$conn)or die ("Error al seleccionar los datos del credito".phpmkr_error()."sql".$sqlCredito);
	$rowCredito = phpmkr_fetch_array($rsCredito);
	$x_credito_tipo_id = $rowCredito["credito_tipo_id"];
	$x_solicitud_id = $rowCredito["solicitud_id"];
	
#	echo "sol_id".$x_solicitud_id."<br>";
	if($x_credito_tipo_id == 2){
	#	echo "sol_id..".$x_solicitud_id."<br>";
		$x_lista_integrantes_id = "";
			// ES UN CREDITO SOLIDARIO
			//echo "load data solidario";
			$sqlGrupo = "SELECT * FROM creditosolidario WHERE  solicitud_id = $x_solicitud_id";
		#	echo "sql solidario".$sqlGrupo."<br>";
			$responseGrupo = phpmkr_query($sqlGrupo,$conn) or die ("error al ejecutar query grupo".phpmkr_error()."sql: ".$sqlGrupo);
			$rowGrupo = phpmkr_fetch_array($responseGrupo);
			$GLOBALS["x_creditoSolidario_id"] =  $rowGrupo["creditoSolidario_id"];
			$GLOBALS["x_nombre_grupo"] = $rowGrupo["nombre_grupo"];
			#echo $GLOBALS["x_nombre_grupo"]."..";
			
			
			$x_cont_g = 1;
			while($x_cont_g <= 10){
				
				$GLOBALS["x_integrante_$x_cont_g"] = $rowGrupo["integrante_$x_cont_g"];
				$GLOBLAS["x_nombre_integrante_$x_cont_g"] = $rowGrupo["integrante_$x_cont_g"];
				
				#echo "integrante".$rowGrupo["integrante_$x_cont_g"]."id ".$rowGrupo["cliente_id_$x_cont_g"]. "<br>";
				
				if(!empty($rowGrupo["cliente_id_$x_cont_g"]) && $rowGrupo["cliente_id_$x_cont_g"] +0 > 1 ){
					$x_lista_integrantes = $rowGrupo["integrante_$x_cont_g"].", ";
					$x_lista_integrantes_id = $x_lista_integrantes_id.$rowGrupo["cliente_id_$x_cont_g"].", ";					
					}
				//BUSCO AL REPRESENTANTE DEL GRUPO
				if($GLOBALS["x_rol_integrante_$x_cont_g"] == 1){
					$GLOBALS["$x_representate_grupo"] = $rowGrupo["integrante_$x_cont_g"];
					$GLOBALS["x_representante_cliente_id"] =  $rowGrupo["cliente_id_$x_cont_g"];
					}
								
				$x_cont_g++;
				}
			
			$x_lista_integrantes_id = trim($x_lista_integrantes_id, ", ");
			
			#echo "lista de integrantes".$x_lista_integrantes_id;
			$x_array_cliente_grupo  = explode(",",$x_lista_integrantes_id);
			foreach($x_array_cliente_grupo as $x_no_cliente){
				#Imprimimos le formato para cada integrante del grupo
			#	echo "entra al foreach";
					if(($x_orden == 12)){
			// se trata de una carta 1
			//$x_formato = 6;// va dirgida al titular
			//nuevos formatos
			$x_formato = 49; #c2th
			
			if($x_destino_c == 2){
				//$x_formato = 7;
				// nuevos formatos
				$x_formato = 50;// va dirgida al aval
				
				
				#echo "formato".$x_formato;
				}
			//	echo "formato ".$x_formato."<br>";
				if ($x_formato == 46 || $x_formato == 53 ){
				$x_tabla1 = loadDataCartaAdmin($conn, $x_credito_id, $x_formato, $x_no_cliente);
				if($x_formato == 53){
				//	$x_tabla1 = "";
					}
					
				}else{
				$x_tabla1 = loadDataCarta1($conn, $x_credito_id, $x_formato, $x_no_cliente);
				if($x_formato == 50){
					//$x_tabla1 = "";
					}
				}
				//echo "formato".$x_formato."<br>";
			echo $x_tabla1;
			?>
			<div class="saltopagina"></div>			
			<?php
			
			$x_edo_cuenta = LoadDataEdoCuenta($conn,$x_credito_id);			
			echo $x_edo_cuenta;
			?>
			<div class="saltopagina"></div>			
			<?php
			
			
			
			}// if carat 1
				
				}
			
			phpmkr_free_result($rowGrupo);
			
			
		
		
		
	
	}else{
	//verificamos que tipo de tarea es, para saber si es carta y cual carta es	
	// carta 1 = orden --> 4 ; crm_tarea_tipo_id -->8
	// carta 2 = orden --> 8 ; crm_tarea_tipo_id -->9
	// carta 3 = orden --> 12 ; crm_tarea_tipo_id -->10
	// carta D = orden --> 20 ; crm_tarea_tipo_id -->12
	
	
	if(($x_orden == 8) || ($x_orden == 9) || ($x_orden == 12) || ($x_orden == 20) ){
	if( ($x_orden == 8) || ($x_orden == 12) ){		
		//ENTRA PARA IMPRIMIR LAS CARTAS
		$x_cli = 0;
		if(($x_orden == 8)){
			// se trata de una carta 1
			//$x_formato = 6;// va dirgida al titular
			//nuevos formatos
			$x_formato = 51;
			
			
			if($x_destino_c == 2){
				//$x_formato = 7;
				// nuevos formatos
				$x_formato = 50;// va dirgida al aval
				
				
				#echo "formato".$x_formato;
				}
			//	echo "formato ".$x_formato."<br>";
				if ($x_formato == 46 || $x_formato == 53 ){
				$x_tabla1 = loadDataCartaAdmin($conn, $x_credito_id, $x_formato,$x_cli);
				}else{
				$x_tabla1 = loadDataCarta1($conn, $x_credito_id, $x_formato,$x_cli);
				}
			echo $x_tabla1;
			?>
			<div class="saltopagina"></div>			
			<?php
			
			$x_edo_cuenta = LoadDataEdoCuenta($conn,$x_credito_id);
	#echo "sale edocuenta<br>";
	echo $x_edo_cuenta;
			?>
			<div class="saltopagina"></div>			
			<?php
	
			
			
			if(strlen ($x_nombre_aval)> 5 ){
				//SI HAY AVAL IMPRIMIMOS LA CARTA DEL AVAL
				#echo "FROMATO ELTRO DE IF AVAL----".$x_formato." ---<br>".$x_nombre_aval."---";
				if ($x_formato == 46  ){
					$x_formato = 53;
				$x_tabla1 = loadDataCartaAdmin($conn, $x_credito_id, $x_formato,$x_cli);
				}else{
					$x_formato = 50;					
					$x_tabla1 = loadDataCarta1($conn, $x_credito_id, $x_formato,$x_cli);
				
			#echo "la tabla es :".$x_tabla1 ."<br><br>";	
				}
				echo $x_tabla1;
			?>
			<div class="saltopagina"></div>			
			<?php
			
				$x_edo_cuenta = LoadDataEdoCuenta($conn,$x_credito_id);
				echo $x_edo_cuenta;
#	echo "sale edocuenta<br>";
			?>
			<div class="saltopagina"></div>			
			<?php
				}
		}else if ( ($x_orden == 12) || ($x_orden == 20) ){			
			
			
			$x_formato = 51;
			
			
			if($x_destino_c == 2){
				//$x_formato = 7;
				// nuevos formatos
				$x_formato = 52;// va dirgida al aval
				
				
				#echo "formato".$x_formato;
				}
			//	echo "formato ".$x_formato."<br>";
				if ($x_formato == 46 || $x_formato == 53 ){
				$x_tabla1 = loadDataCartaAdmin($conn, $x_credito_id, $x_formato,$x_cli);
				}else{
				$x_tabla1 = loadDataCarta1($conn, $x_credito_id, $x_formato,$x_cli);
				}
			echo $x_tabla1;
			?>
			<div class="saltopagina"></div>			
			<?php
			
			$x_edo_cuenta = LoadDataEdoCuenta($conn,$x_credito_id);
	#echo "sale edocuenta<br>";
	echo $x_edo_cuenta;
			?>
			<div class="saltopagina"></div>			
			<?php
	
			
			
			if(strlen ($x_nombre_aval)> 5 ){
				//SI HAY AVAL IMPRIMIMOS LA CARTA DEL AVAL
				#echo "FROMATO ELTRO DE IF AVAL----".$x_formato." ---<br>".$x_nombre_aval."---";
				if ($x_formato == 46  ){
					$x_formato = 53;
				$x_tabla1 = loadDataCartaAdmin($conn, $x_credito_id, $x_formato,$x_cli);
				}else{
					$x_formato = 52;					
					$x_tabla1 = loadDataCarta1($conn, $x_credito_id, $x_formato,$x_cli);
				
			#echo "la tabla es :".$x_tabla1 ."<br><br>";	
				}
				echo $x_tabla1;
			?>
			<div class="saltopagina"></div>			
			<?php
			
				$x_edo_cuenta = LoadDataEdoCuenta($conn,$x_credito_id);
				echo $x_edo_cuenta;
#	echo "sale edocuenta<br>";
			?>
			<div class="saltopagina"></div>			
			<?php
				}
		
			
			
			
			}
			
			
			
			}// if carat 1
			
		
			
		}
	
	}//else credito grupal
	
	
//	}// si no esta cerrado el caso
	}// while principal



	####################################################
	#########     PROMESA DE PAGO CARTA 3     ##########
	####################################################
	
	$sqlCaso = "SELECT  * FROM crm_caso WHERE  credito_id = $x_credito_id and crm_caso_status_id = 1 ";
	$rsCaso = phpmkr_query($sqlCaso,$conn) or die("Error al seleccionar el caso para el credito".phpmkr_error()."Sql:".$sqlCaso);
	$rowCaso = phpmkr_fetch_array($rsCaso);
	$x_caso_id = $rowCaso["crm_caso_id"];
	
	
		if(!empty($x_caso_id)){ 
					$x_carta_1PP = 0;
					$sqlCarta1PP = "SELECT COUNT(*) AS carta_1_PP FROM crm_tarea WHERE crm_caso_id = $x_caso_id AND crm_tarea_tipo_id = 5 AND orden= 13 AND crm_tarea_status_id in (1,2,3)";
					$rsCarta1PP = phpmkr_query($sqlCarta1PP,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCarta1PP);
					$rowCarata1PP = phpmkr_fetch_array($rsCarta1PP);
					$x_carta_1PP = $rowCarata1PP["carta_1_PP"] +0;
					}else{
		$x_carta_1PP = 0;
		//no existe el caso entonces se crea el caso
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
		LIMIT 1	";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_cliente_id = $datawrk["cliente_id"];
		@phpmkr_free_result($rswrk);
		$currdate = date("Y-m-d");
		
		// buscamos el usuario; todas las tareas se mandaran al usuario responsable de sucursal...
		
			
		$sqlSolId = "SELECT solicitud_id FROM credito WHERE credito_id = $x_credito_id";
		$rsSolId = phpmkr_query($sqlSolId,$conn) or die ("Error al seleccionar el id de la solicitud del credito".phpmkr_error()."sql:");
		$rowSolId = phpmkr_fetch_array($rsSolId);
		$x_solicitud_id_c = $rowSolId["solicitud_id"];
		
		// seleccionamos el promotor
		$sqlPromotor = "SELECT solicitud.promotor_id, promotor.sucursal_id  as sucursal FROM solicitud join promotor on promotor.promotor_id = solicitud.promotor_id WHERE solicitud_id = $x_solicitud_id_c";
		$rsPromotor = phpmkr_query($sqlPromotor,$conn) or die ("Error al seleccionar el promotor del credito".phpmkr_error()."sql :".$sqlPromotor);
		$rowPromotor = phpmkr_fetch_array($rsPromotor);
		$x_promotor_id_c = $rowPromotor["promotor_id"];
		$x_sucursal_id = $rowPromotor["sucursal"];
		
		// seleccionamos el usuario del responsable de sucursal		
		$sqlUsuarioi = " SELECT usuario_id FROM  responsable_sucursal where sucursal_id = $x_sucursal_id ";
		$rsUsuarioi = phpmkr_query($sqlUsuarioi,$conn)or die("Error al seleccionar el usuario de la sucursal".phpmkr_error()."sql:".$sqlUsuarioi);
		$rowUsuarioi =phpmkr_fetch_array($rsUsuarioi);
		$x_usuario_idi = $rowUsuarioi["usuario_id"];
		
	$currdate= date("Y-m-d");
		$sSql = "INSERT INTO crm_caso values (0,3,1,1,$x_cliente_id,'".$currdate."',$x_origen,$x_usuario_idi,'$x_bitacora','".$currdate."',NULL,$x_credito_id)";	
		$x_result = phpmkr_query($sSql, $conn) or die ("error al insertar carta 1". phpmkr_error()."sql.".$sSql);
		echo "INSERT  :<BR>".$sSql."<BR>";
		$x_crm_caso_id = mysql_insert_id();	
		
		
		}			
		
		
		if ($x_carta_1PP > 0){
						// cerramos todas las anteriores
						$sqlCarta1PP = "UPDATE crm_tarea SET crm_tarea_status_id = 4 WHERE crm_caso_id = $x_caso_id AND crm_tarea_tipo_id = 5 AND orden= 5 AND crm_tarea_status_id in (1,2) ";
				  		$rsCarta1PP = phpmkr_query($sqlCarta1PP,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCarta1PP);						
						$sqlCarta1PP = "UPDATE crm_tarea SET crm_tarea_status_id = 4 WHERE crm_caso_id = $x_caso_id AND crm_tarea_tipo_id = 5 AND orden= 9 AND crm_tarea_status_id in (1,2) ";
				  		$rsCarta1PP = phpmkr_query($sqlCarta1PP,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCarta1PP);
						$sqlCarta1PP = "UPDATE crm_tarea SET crm_tarea_status_id = 4 WHERE crm_caso_id = $x_caso_id AND crm_tarea_tipo_id = 5 AND orden= 13 AND crm_tarea_status_id in (1,2) ";
				  		$rsCarta1PP = phpmkr_query($sqlCarta1PP,$conn)or die ("Error al seleccionar la carta 1".phpmkr_error()."sql:".$sqlCarta1PP);
						}
					
					#echo "promesa de pago".$sqlCarta1PP."<br>"; 
				#	echo "promesas de pago para las cartas = ". $x_carta_1PP."<br>";
					if($x_carta_1PP < 1 ||$x_carta_1PP > 1 ){
					#echo "no hay promesa de pago pendiente<br>";							
					#SELECCIONAMOS LOS DATOS DE  LA TAREA					
					$sSqlWrk = "
					SELECT *
					FROM 
						crm_playlist
					WHERE 
						crm_playlist.crm_caso_tipo_id = 3
						AND crm_playlist.orden = 13 "; // PP carat 3					
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
					$currdate = date("Y-m-d");
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
					// el usuario debe ser al gestor asignado
							// seleccionamos nuevamente el usuario
					$sqlUsuario = "SELECT usuario_id FROM gestor_credito WHERE credito_id = $x_credito_id ";
					$rsUsuario = phpmkr_query($sqlUsuario,$conn)or die ("Erroro al aseleccionar el usuario".phpmkr_error()."sql:".$sqlUsuario);
					$rowUsuario = phpmkr_fetch_array($rsUsuario);
					$x_usuario_id = $rowUsuario["usuario_id"];
					@phpmkr_free_result($rsUsuario);
						
			
					if(($x_caso_id +0 > 0)){
								
						$sSql = "INSERT INTO crm_tarea values (0,$x_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$fecha_venc',NULL,NULL,NULL, 1, 1, 2, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
					
						$x_result = phpmkr_query($sSql, $conn) or die ("error al inserta PP carat 1".phpmkr_error()."sql;".$sSql);
						$x_tarea_id_pp = mysql_insert_id();
						#echo "INSERTA PROMESA DE PAGO CARTA 1<BR>".$sSql."<BR>";
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
						$x_comment_ant = $datawrk["comentario_int"];
						@phpmkr_free_result($rswrk);
						
						$HOY= date("Y-m-d");
						$x_usuaio_registrado = registraUsuario($_SESSION["php_project_esf_status_UserID"],$conn);
						$x_bitacora = $x_usuaio_registrado." ".$currdate." SE IMPRIME CARTA 3 ";			
						if(empty($x_comment_ant)){
							$sSql = "insert into credito_comment values(0, $x_credito_id, '$x_bitacora', NULL)";
						}else{
							$x_bitacora = $x_comment_ant . "\n\n------------------------------\n" . $x_bitacora;
							$sSql = "UPDATE credito_comment set comentario_int = '$x_bitacora' where credito_id = $x_credito_id";
						}				
						phpmkr_query($sSql, $conn);		
						$x_fecha_h = date("Y-m-d");
								// si existe actualizamos el ststus
								$sqlInsert = " UPDATE  `crm_credito_status` SET  `crm_cartera_status_id` =  '2' , `fecha` = '$x_fecha_h' WHERE  `credito_id` =$x_credito_id " ;							
								phpmkr_query($sqlInsert,$conn) or die("error al inbsertar en crm_credito_staus".phpmkr_error()."sql;".$sqlInsert);		
					
					}// CASO CRM > 0 DE CARTA 1					
					}// SI NO EXISTE LA PROMESA DE PAGO PARA CARAT 3	



function LoadDataCartaAdmin($conn, $credito_id, $x_formato, $x_cliente_ida)
{
	//CREDITO
	#echo "entro carta admin credito_id = ".$credito_id;
	
	
	$x_credito_id = $credito_id;
	$sSql = "SELECT * FROM credito where credito_id = $x_credito_id";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_solicitud_id = $row["solicitud_id"];
	$x_tarjeta_numero = $row["tarjeta_num"];
	$x_fecha_otorgamiento = $row["fecha_otrogamiento"];
	phpmkr_free_result($rs);
	#echo "no entra";
	$sSql = "SELECT * FROM solicitud where solicitud_id = $x_solicitud_id";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	if (phpmkr_num_rows($rs) == 0) {
		$bLoadData = false;
	}else{
		#echo "entra";
		$bLoadData = true;
		$row = phpmkr_fetch_array($rs);

		$GLOBALS["x_solicitud_id"] = $row["solicitud_id"];
		$GLOBALS["x_credito_tipo_id"] = $row["credito_tipo_id"];
		$GLOBALS["x_solicitud_status_id"] = $row["solicitud_status_id"];
		$GLOBALS["x_folio"] = $row["folio"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
		$GLOBALS["x_promotor_id"] = $row["promotor_id"];
		$GLOBALS["x_importe_solicitado"] = $row["importe_solicitado"];
		$GLOBALS["x_plazo_id"] = $row["plazo_id"];
		$GLOBALS["x_forma_pago_id"] = $row["forma_pago_id"];		
		$GLOBALS["x_contrato"] = $row["contrato"];
		$GLOBALS["x_pagare"] = $row["pagare"];
		
		
		$sSql = "SELECT * FROM credito  WHERE solicitud_id = ".$GLOBALS["x_solicitud_id"]." ";
		$rs = phpmkr_query($sSql, $conn) or die ("Error al selccionar los datos del credito". phpmkr_error()."sql;".$sSql);
		$rowC = phpmkr_fetch_array($rs);
		$GLOBALS["x_penalizacion"] = $rowC["penalizacion"];
		$GLOBALS["x_garantia_liquida"] = $rowC["garantia_liquida"];
		$GLOBALS["x_credito_id"] = $rowC["credito_id"];
		
		
		//INTEGRANTES DEL GRUPO
		$x_soli_id =  $GLOBALS["x_solicitud_id"];
		if($GLOBALS["x_credito_tipo_id"] == 2){
			// ES UN CREDITO SOLIDARIO
			//echo "load data solidario";
			$sqlGrupo = "SELECT * FROM creditosolidario WHERE  solicitud_id = $x_soli_id";
			//echo "sql solidario".$sqlGrupo."<br>";+
			$responseGrupo = phpmkr_query($sqlGrupo,$conn) or die ("error al ejecutar query grupo".phpmkr_error()."sql: ".$sqlGrupo);
			$rowGrupo = phpmkr_fetch_array($responseGrupo);
			$GLOBALS["x_creditoSolidario_id"] =  $rowGrupo["creditoSolidario_id"];
			$x_nombre_grupo = "GRUPO ".$rowGrupo["nombre_grupo"];
			
			$x_cont_g = 1;
			while($x_cont_g <= 10){
				
				$GLOBALS["x_integrante_$x_cont_g"] = $rowGrupo["integrante_$x_cont_g"];
				//$x_monto_i =  $rowGrupo["monto_$x_cont_g"];
				//$GLOBALS["x_monto_$x_cont_g"] = number_format($x_monto_i);
				$GLOBALS["x_monto_$x_cont_g"] =  $rowGrupo["monto_$x_cont_g"];
				$GLOBALS["x_rol_integrante_$x_cont_g"] = $rowGrupo["rol_integrante_$x_cont_g"]; 
				$GLOBALS["x_cliente_id_$x_cont_g"] = $rowGrupo["cliente_id_$x_cont_g"];
				
				//BUSCO AL REPRESENTANTE DEL GRUPO
				if($GLOBALS["x_rol_integrante_$x_cont_g"] == 1){
					$GLOBALS["$x_representate_grupo"] = $rowGrupo["integrante_$x_cont_g"];
					$GLOBALS["x_representante_cliente_id"] =  $rowGrupo["cliente_id_$x_cont_g"];
					}
				
				$x_cont_g++;
				}
			
			
			
			phpmkr_free_result($rowGrupo);
			
			}



	//CREDITO
	//SELECIONO LOS DATIS DE CREDITO PARA HACER LA TABLA  CON LA TASA AL 60%


//CLIENTE

if($x_cliente_ida > 1){
	$sSql = "select cliente.* from cliente  where cliente_id = $x_cliente_ida ";	
	}else{

		$sSql = "select cliente.* from cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id where solicitud_cliente.solicitud_id = $x_solicitud_id";	
	}
		$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row2 = phpmkr_fetch_array($rs2);
		$GLOBALS["x_cliente_id"] = $row2["cliente_id"];
		$GLOBALS["x_usuario_id"] = $row2["usuario_id"];
		$x_nombre_completo = $row2["nombre_completo"];
		$x_apellido_paterno = $row2["apellido_paterno"];		
		$x_apellido_materno = $row2["apellido_materno"];				
		$GLOBALS["x_tipo_negocio"] = $row2["tipo_negocio"];
		$GLOBALS["x_edad"] = $row2["edad"];
		$x_sexo = $row2["sexo"];
		$GLOBALS["x_estado_civil_id"] = $row2["estado_civil_id"];
		$GLOBALS["x_numero_hijos"] = $row2["numero_hijos"];
		$GLOBALS["x_nombre_conyuge"] = $row2["nombre_conyuge"];
		$GLOBALS["x_email"] = $row2["email"];	
		
		$x_cliente =  $x_nombre_completo." ".$x_apellido_paterno." ".$x_apellido_materno;
		if($x_formato != 53){
		if($x_sexo == 1){
	$x_cliente = "ESTIMADO ".$x_cliente;
}else{
	$x_cliente = "ESTIMADA ".$x_cliente;
}	
		}
		
		
		


				
		



		$sSql = "select * from aval where solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rs5 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row5 = phpmkr_fetch_array($rs5);
		$GLOBALS["x_aval_id"] = $row5["aval_id"];
		$GLOBALS["x_nombre_completo_aval"] = $row5["nombre_completo"];
		$GLOBALS["x_apellido_paterno_aval"] = $row5["apellido_paterno"];
		$GLOBALS["x_apellido_materno_aval"] = $row5["apellido_materno"];										
		$GLOBALS["x_parentesco_tipo_id_aval"] = $row5["parentesco_tipo_id"];
		$GLOBALS["x_telefono3"] = $row5["telefono"];
		$GLOBALS["x_ingresos_mensuales"] = $row5["ingresos_mensuales"];
		$GLOBALS["x_ocupacion"] = $row5["ocupacion"];


		if($GLOBALS["x_aval_id"] != ""){
			$sSql = "select * from direccion where aval_id = ".$GLOBALS["x_aval_id"]." and direccion_tipo_id = 3";
			$rs6 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$row6 = phpmkr_fetch_array($rs6);
			$GLOBALS["x_direccion_id6"] = $row6["direccion_id"];
			$GLOBALS["x_calle3"] = $row6["calle"];
			$GLOBALS["x_colonia3"] = $row6["colonia"];
			$GLOBALS["x_delegacion_id3"] = $row6["delegacion_id"];
			$GLOBALS["x_otra_delegacion3"] = $row6["otra_delegacion"];
			$GLOBALS["x_entidad3"] = $row6["entidad"];
			$GLOBALS["x_codigo_postal3"] = $row6["codigo_postal"];
			$GLOBALS["x_ubicacion3"] = $row6["ubicacion"];
			$GLOBALS["x_antiguedad3"] = $row6["antiguedad"];
			$GLOBALS["x_vivienda_tipo_id2"] = $row6["vivienda_tipo_id"];
			$GLOBALS["x_otro_tipo_vivienda3"] = $row6["otro_tipo_vivienda"];

			$GLOBALS["x_telefono3"] = $row6["telefono"];
			$GLOBALS["x_telefono_secundario3"] = $row6["telefono_secundario"];
		}else{
			$GLOBALS["x_direccion_id3"] = "";
			$GLOBALS["x_calle3"] = "";
			$GLOBALS["x_colonia3"] = "";
			$GLOBALS["x_delegacion_id3"] = "";
			$GLOBALS["x_otra_delegacion3"] = "";
			$GLOBALS["x_entidad3"] = "";
			$GLOBALS["x_codigo_postal3"] = "";
			$GLOBALS["x_ubicacion3"] = "";
			$GLOBALS["x_antiguedad3"] = "";
			$GLOBALS["x_vivienda_tipo_id2"] = "";
			$GLOBALS["x_otro_tipo_vivienda3"] = "";
			$GLOBALS["x_telefono3"] = "";
			$GLOBALS["x_telefono_secundario3"] = "";
		}


		$sSql = "select * from garantia where solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rs7 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row7 = phpmkr_fetch_array($rs7);
		$GLOBALS["x_garantia_desc"] = $row7["descripcion"];
		$GLOBALS["x_garantia_valor"] = $row7["valor"];		

		$sSql = "select * from ingreso where solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rs8 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row8 = phpmkr_fetch_array($rs8);
		$GLOBALS["x_ingreso_id"] = $row8["ingreso_id"];
		$GLOBALS["x_ingresos_negocio"] = $row8["ingresos_negocio"];
		$GLOBALS["x_ingresos_familiar_1"] = $row8["ingresos_familiar_1"];
		$GLOBALS["x_parentesco_tipo_id_ing_1"] = $row8["parentesco_tipo_id"];
		$GLOBALS["x_ingresos_familiar_2"] = $row8["ingresos_familiar_2"];

		$GLOBALS["x_parentesco_tipo_id_ing_2"] = $row8["parentesco_tipo_id2"];
		$GLOBALS["x_otros_ingresos"] = $row8["otros_ingresos"];

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_nombre_completo_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_nombre_completo_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_nombre_completo_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_nombre_completo_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_nombre_completo_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";
	

		$x_count = 1;
		$sSql = "select * from referencia where solicitud_id = ".$GLOBALS["x_solicitud_id"]." order by referencia_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9 = phpmkr_fetch_array($rs9)){
			$GLOBALS["x_referencia_id_$x_count"] = $row9["referencia_id"];
			$GLOBALS["x_nombre_completo_ref_$x_count"] = $row9["nombre_completo"];
			$GLOBALS["x_telefono_ref_$x_count"] = $row9["telefono"];
			$GLOBALS["x_parentesco_tipo_id_ref_$x_count"] = $row9["parentesco_tipo_id"];
			$x_count++;
		}




		$x_contenido_id = 0;
		
		$sSql = "SELECT count(*) as gara FROM garantia where solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row = phpmkr_fetch_array($rs);
		$x_gara = $row["gara"];
		phpmkr_free_result($rs);

	/*	if($x_gara > 0){
			$x_contenido_id = 3;
		}else{
			$x_contenido_id = 12;
		}*/
		
		$sSql = "SELECT nombre_completo AS avales FROM datos_aval WHERE solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row = phpmkr_fetch_array($rs);
		$x_aval = $row["avales"];
		if( strlen(trim($x_aval)) > 0  ){
			$x_aval = 1;
			}
		$x_valor_credito_tipo_id = $GLOBALS["x_credito_tipo_id"];
		
		
				
				//echo "contenido".$x_contenido_id;		
		$sSql = "select contenido from formato_docto where formato_docto_id = $x_formato";
		$rs10 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row10 = phpmkr_fetch_array($rs10);
		$x_contenido = $row10["contenido"];
		

		#seleccionamos los datos de los vencimeintos
		$currdate = date("Y-m-d");
		$x_total_gral = 0;
		$x_capital_ordinario = 0;
		$x_total_gral_sin_tope = 0;
		$x_total_sin_tope = 0;
		
		$sqlSaldoD = "SELECT SUM(total_venc) as total_p  FROM vencimiento WHERE credito_id = ". $GLOBALS["x_credito_id"]." and vencimiento_status_id in  (1,3,6)  ";
		$rsSaldoD = phpmkr_query($sqlSaldoD,$conn)or die("error sal ven".phpmkr_error()."sql :".$sqlSaldoD);
		$rowSaldoD = phpmkr_fetch_array($rsSaldoD);
		$_total_faltante = $rowSaldoD["total_p"];//total
		
		$sqlSaldoD = "SELECT SUM(interes_moratorio) as moratorio  FROM vencimiento WHERE credito_id = ". $GLOBALS["x_credito_id"]." and vencimiento_status_id in  (1,3,6)  ";
		$rsSaldoD = phpmkr_query($sqlSaldoD,$conn)or die("error sal ven".phpmkr_error()."sql :".$sqlSaldoD);
		$rowSaldoD = phpmkr_fetch_array($rsSaldoD);
		$_moratorio = $rowSaldoD["moratorio"];
		
		$sqlSaldoD = "SELECT SUM(iva_mor) as iva_moratorio  FROM vencimiento WHERE credito_id = ". $GLOBALS["x_credito_id"]." and vencimiento_status_id in  (1,3,6)  ";
		$rsSaldoD = phpmkr_query($sqlSaldoD,$conn)or die("error sal ven".phpmkr_error()."sql :".$sqlSaldoD);
		$rowSaldoD = phpmkr_fetch_array($rsSaldoD);
		$_iva_moratorio = $rowSaldoD["iva_moratorio"];
		
		$x_condonado = $_total_faltante -($_moratorio + $_iva_moratorio );
	#	echo "condonado".$x_condonado."<br>";
		
	
		
		
	#echo "total faltante ".$_total_faltante."<br>";	
		$SQLcRDITO = "SELECT * FROM credito WHERE credito_id = ". $GLOBALS["x_credito_id"]." ";
		$rscRDITO = phpmkr_query($SQLcRDITO,$conn) or die("error la seleccionar los datos del credito".phpmkr_error()."sql;".$SQLcRDITO);
		$rowscRDITO = phpmkr_fetch_array($rscRDITO);
		$x_tasa_moratoria = $rowscRDITO["tasa_moratoria"];
		
		$sqlVencimeintos = "SELECT * FROM vencimiento WHERE credito_id = ". $GLOBALS["x_credito_id"]." and vencimiento_status_id in  (1,3)  order by vencimiento_num  ";
		#echo "sql mayor ".$sqlVencimeintos."<br>";
		$x_total_dias_vencidos  = 0;
		$rsVencimeintos = phpmkr_query($sqlVencimeintos,$conn)or die ("Error al seleccionar los vencimientos". phpmkr_error()."sql:".$sqlVencimeintos);
		while($rowVencimientos = phpmkr_fetch_array($rsVencimeintos)){
			// seleccionamos el primer vancimeinto.
			// Y  vemos si esta vencido o o no
			$x_vencimiento_id = $rowVencimientos["vencimeinto_id"];
			$x_vencimiento_num = $rowVencimientos["vencimiento_num"];
			$x_vencimiento_status_id = $rowVencimientos["vencimiento_status_id"];//status vencimiento
			#echo "<br>venc staus----".$x_vencimiento_status_id;
			$x_fecha_vencimiento = $rowVencimientos["fecha_vencimiento"];// fecha de pago
			$x_importe = $rowVencimientos["importe"]; // importe	
			$x_interes = $rowVencimientos["interes"]; // interes
			$x_interes_moratorio = $rowVencimientos["interes_moratorio"]; //interes moratorio
			$x_iva = $rowVencimientos["iva"]; // iva
			$x_iva_mor = $rowVencimientos["iva_mor"];//  iva mor
			$x_total_venc = $rowVencimientos["total_venc"]; // total venc		
		
			
			$x_credito_id = $rowVencimientos["credito_id"];
		#	echo "<br>credito_id = ".$x_credito_id; 			
			$x_total_con_tope = $x_total_venc;			
			$x_c_ordi = $x_importe + $x_interes + $x_iva;	
					
			// status_vencimeito_id
			// 1 .- pendiente
			// 2.- pagado
			// 3.- vencido
			// 4.- cabcelado
			
			if(($x_vencimiento_status_id == 3)){
				// se recalculan los moratorios sin toparlos				
				#echo "<br>entra al if<br>";
				$sqlVenNum = "SELECT COUNT(*) AS numero_de_pagos FROM vencimiento WHERE  fecha_vencimiento = \"$x_fecha_vencimiento\" AND  credito_id =  $x_credito_id";
				$response = phpmkr_query($sqlVenNum, $conn) or die("error en numero de vencimiento".phpmkr_error()."sql:".$sqlVenNum);
				$rownpagos = phpmkr_fetch_array($response);  
				$x_numero_de_pagos =  $rownpagos["numero_de_pagos"];
				
				//if($x_numero_de_pagos < 2){
				//se hace el clculo de los moratorios				
				$x_dias_vencidos = datediff('d', $x_fecha_vencimiento, $currdate, false);					
					$x_dia = strtoupper(date('l',strtotime($x_fecha_vencimiento)));				
				
					#echo "<br>fecha  vencimeinto".$x_fecha_vencimiento;
	#echo "<br>fecha actual  ".$currdate;	
	
				
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
	#echo "dias de gracia".$x_dias_gracia;
					#	echo "froma de pago =".$x_forma_pago_valor."<br>";
					#	echo "penalizacion ".$x_penalizacion."<br>";
						if(($x_dias_vencidos >= $x_dias_gracia) ){
							$x_importe_mora = 10 * $x_dias_vencidos;
							#echo "dias vencido".$x_dias_vencidos."<br>";							
							#echo "numero venc".$x_vencimiento_num."<br><br>";
							#echo "<br>importe mora".$x_importe_mora."<br>";
							if($x_iva_credito == 1){
								$x_iva_mor = round($x_importe_mora * .16);
								$x_iva_mor = 0;			
							}else{
								$x_iva_mor = 0;
							}
							
					//moratorios no majyores a 	2						
							if($x_credito_tipo_id == 2){			
								if($x_importe_mora > 0){
									$x_importe_mora = 250;
									}			
								}
								#echo "importe mora truncado".$x_importe_mora."<br>";
								
								if($x_vencimiento_num < 1000){
							$x_total_sin_tope = $x_importe + $x_interes + $x_importe_mora + $x_iva + $x_iva_mor;					
							#echo "total sin tope dentro del if". $x_total_sin_tope."-- <br>";
						#	echo " staus credito".$x_vencimiento_status_id ." <br>dias vencido  ".$x_dias_vencidos."<br>";
							$x_total_dias_vencidos = $x_total_dias_vencidos +$x_dias_vencidos;
							#echo "<br><br> total ".$x_total_dias_vencidos."<br>";
								}else{
									$x_total_sin_tope = $x_total_venc;
									
									}
							
						}// dias vencidos mayor o ogial a dias de gracia....
				
				
				//}// numero de pagos menor a 2
				
				
		#	echo "numero de dias ".$x_dias_vencidos." vencimiento num ".$x_vencimiento_num."total del vencime".$x_total_con_tope."<br>";				
				$x_total_gral_sin_tope = $x_total_gral_sin_tope +$x_total_sin_tope;
		#	echo "<br> sin tope  total".$x_total_gral_sin_tope."<br>";
			$x_capital_ordinario = $x_capital_ordinario +$x_c_ordi;
			$x_total_gral_con_tope = $x_total_gral_con_tope + $x_total_con_tope;
			#echo "<br> CON tope".$x_total_gral_con_tope;
		#	echo "<br> ordinario".$x_capital_ordinario;
		#		
				}//vencimeinto_vencido
		#	echo "no entra<br>";
			$x_total_con_tope = $x_total_venc;
			
		
			}//while
			
$x_nombre_aval = "";			
//AVAL
$sSql = "SELECT * FROM aval where solicitud_id = $x_solicitud_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
if($row["sexo"] == 1){
	$x_saludo = "Sr.";
}else{
	$x_saludo = "Sra.";	
}

//$x_aval = $row["nombre_completo"];
  $x_nombre_aval = $row["nombre_completo"]." ".$row["apellido_paterno"]." ".$row["apellido_materno"];
	$x_aval = $x_saludo." ". $x_nombre_aval;
	#echo "el aval es".$x_aval."<br>";						

phpmkr_free_result($rs);

if(strlen ($x_nombre_aval)< 5){
	//buscamos el aval en las otras tablas donde puede estar si es que ya no esta en la tabal vieja
	#los datos del aval estan en la tabla datos_aval; por el cambio del formto en la solitud del aval
	
	
	$sSql = " SELECT * FROM  datos_aval WHERE solicitud_id = $x_solicitud_id";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	if($row["sexo"] == 1){
		$x_saludo = "Sr.";
	}else{
		$x_saludo = "Sra.";	
	}
  $x_nombre_aval = $row["nombre_completo"]." ".$row["apellido_paterno"]." ".$row["apellido_materno"];
//$x_aval = $row["nombre_completo"];
	$x_aval = $x_saludo." ". $x_nombre_aval;	
	}			

$GLOBALS["x_total_gral_sin_tope"] = $x_total_gral_sin_tope ;
$GLOBALS["x_capital_ordinario"] = $x_capital_ordinario ;
$GLOBALS["x_total_gral_con_tope"] = $x_total_gral_con_tope ;


// carta cambio de administracion

//echo "x_saldo_total_credito".$x_saldo_total_credito."<br>";
//x_saldo_total_credito
#echo "--- DV".$x_total_dias_vencidos."<br>";
$x_total_gral_sin_tope = ($x_total_dias_vencidos * 10) + $x_condonado;
$x_total_gral_con_tope = "$".FormatNumber($_total_faltante,2,0,0,1);
$x_total_gral_sin_tope = "$".FormatNumber($x_total_gral_sin_tope,2,0,0,1);
$x_capital_ordinario = "$".FormatNumber($x_capital_ordinario,2,0,0,1);
$x_condonado = "$".FormatNumber($x_condonado,2,0,0,1);

$x_hoy = date("Y-m-d");

$sqlDV = "select DATE_ADD(\"$x_hoy\",INTERVAL 10 DAY) as dia_valido";
#echo "sql".$sqlDV." ";
$rsDV = phpmkr_query($sqlDV,$conn)or die("Erro al sle dia val".phpmkr_error());
$rowDV = phpmkr_fetch_array($rsDV);
$x_dia_valido = $rowDV["dia_valido"];
#echo "dia valido".$x_dia_valido."<br>";

$currdate = date("Y-m-d");

$x_fecha_actual  = FechaLetras_normal(strtotime(ConvertDateToMysqlFormat($currdate)),false);

$x_fecha_valida =  FechaLetras_normal(strtotime($x_dia_valido),false);
$x_contenido = str_replace("\$x_saldo_total_credito",$x_total_gral_con_tope,$x_contenido);
$x_contenido = str_replace("\$x_mora_sin_truncar",$x_total_gral_sin_tope,$x_contenido);
$x_contenido = str_replace("\$x_capital_ordinario_iva",$x_condonado,$x_contenido);
$x_contenido = str_replace("\$x_fecha_valida",$x_fecha_valida,$x_contenido);
$x_contenido = str_replace("\$x_fecha_actual",$x_fecha_actual,$x_contenido);
$x_contenido = str_replace("\$x_cliente",htmlentities($x_cliente),$x_contenido);
$x_contenido = str_replace("\$x_aval",htmlentities($x_aval),$x_contenido);
$x_contenido = str_replace("\$x_nombre_grupo",$x_nombre_grupo,$x_contenido);


	}
	phpmkr_free_result($rs);
	phpmkr_free_result($rs2);	
	phpmkr_free_result($rs3);		
	phpmkr_free_result($rs4);			
	phpmkr_free_result($rs5);				
	phpmkr_free_result($rs6);					
	phpmkr_free_result($rs7);						
	phpmkr_free_result($rs8);
	phpmkr_free_result($rs9);								
	phpmkr_free_result($rs10);	

#echo "nombre aval".$x_nombre_aval."<br>";
	
	if((strlen ($x_nombre_aval)< 5 )and ($x_formato == 48 || $x_formato == 53) ){
	$x_contenido ="";
	}		
	

	
									
	return $x_contenido;
}




function loadDataCarta1($conn, $credito_id, $x_formato, $x_cliente_ida){
	//CREDITO
	$x_credito_id = $credito_id;
	$sSql = "SELECT * FROM credito where credito_id = $x_credito_id";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_solicitud_id = $row["solicitud_id"];
	$x_tarjeta_numero = $row["tarjeta_num"];
	$x_fecha_otorgamiento = $row["fecha_otrogamiento"];
	phpmkr_free_result($rs);
	$x_fecha_otorgamiento = FechaLetras_normal(strtotime($x_fecha_otorgamiento),false);
	
	
	//INTEGRANTES DEL GRUPO
		$x_soli_id =  $x_solicitud_id;
		if($GLOBALS["x_credito_tipo_id"] == 2){
			// ES UN CREDITO SOLIDARIO
			//echo "load data solidario";
			$sqlGrupo = "SELECT * FROM creditosolidario WHERE  solicitud_id = $x_soli_id";
			//echo "sql solidario".$sqlGrupo."<br>";+
			$responseGrupo = phpmkr_query($sqlGrupo,$conn) or die ("error al ejecutar query grupo".phpmkr_error()."sql: ".$sqlGrupo);
			$rowGrupo = phpmkr_fetch_array($responseGrupo);
			$GLOBALS["x_creditoSolidario_id"] =  $rowGrupo["creditoSolidario_id"];

			$x_nombre_grupo = $rowGrupo["nombre_grupo"];
			
			$x_cont_g = 1;
			while($x_cont_g <= 10){
				
				$GLOBALS["x_integrante_$x_cont_g"] = $rowGrupo["integrante_$x_cont_g"];
				//$x_monto_i =  $rowGrupo["monto_$x_cont_g"];
				//$GLOBALS["x_monto_$x_cont_g"] = number_format($x_monto_i);
				$GLOBALS["x_monto_$x_cont_g"] =  $rowGrupo["monto_$x_cont_g"];
				$GLOBALS["x_rol_integrante_$x_cont_g"] = $rowGrupo["rol_integrante_$x_cont_g"]; 
				$GLOBALS["x_cliente_id_$x_cont_g"] = $rowGrupo["cliente_id_$x_cont_g"];
				
				//BUSCO AL REPRESENTANTE DEL GRUPO
				if($GLOBALS["x_rol_integrante_$x_cont_g"] == 1){
					$GLOBALS["$x_representate_grupo"] = $rowGrupo["integrante_$x_cont_g"];
					$GLOBALS["x_representante_cliente_id"] =  $rowGrupo["cliente_id_$x_cont_g"];
					}
				
				$x_cont_g++;
				}
			
			
			
			phpmkr_free_result($rowGrupo);
			
			}

//CLIENTE

if($x_cliente_ida > 1){
	$sSqlWrk = "SELECT cliente.usuario_id, cliente.sexo, cliente.nombre_completo as cliente_nombre, cliente.apellido_paterno, cliente.apellido_materno FROM cliente Where cliente_id = $x_cliente_ida";
	}else{


$sSqlWrk = "SELECT cliente.usuario_id, cliente.sexo, cliente.nombre_completo as cliente_nombre, cliente.apellido_paterno, cliente.apellido_materno FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id Where credito.credito_id = $x_credito_id ";
	}
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	if($rowwrk["sexo"] == 1){
		$x_saludo = "Sr.";
	}else{
		$x_saludo = "Sra.";	
	}
	$x_titular = $x_saludo." ".$rowwrk["cliente_nombre"]." ".$rowwrk["apellido_paterno"]." ".$rowwrk["apellido_materno"];	
	#echo "cliente ".$x_titular."<br>";					
	$x_usuario_id = $rowwrk["usuario_id"];
}else{
	$x_titular = "";					
	$x_usuario_id = "";						
}
@phpmkr_free_result($rswrk);

//



//AVAL
$sSql = "SELECT * FROM aval where solicitud_id = $x_solicitud_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
if($row["sexo"] == 1){
	$x_saludo = "Sr.";
}else{
	$x_saludo = "Sra.";	
}

//$x_aval = $row["nombre_completo"];
  $x_nombre_aval = $row["nombre_completo"]." ".$row["apellido_paterno"]." ".$row["apellido_materno"];
	$x_aval = $x_saludo." ". $x_nombre_aval;
	#echo "el aval es".$x_aval."<br>";						

phpmkr_free_result($rs);


	
if(strlen ($x_nombre_aval)< 5 ){
	//buscamos el aval en las otras tablas donde puede estar si es que ya no esta en la tabal vieja
	#los datos del aval estan en la tabla datos_aval; por el cambio del formto en la solitud del aval
	
	
	$sSql = " SELECT * FROM  datos_aval WHERE solicitud_id = $x_solicitud_id";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	if($row["sexo"] == 1){
		$x_saludo = "Sr.";
	}else{
		$x_saludo = "Sra.";	
	}
  $x_nombre_aval = $row["nombre_completo"]." ".$row["apellido_paterno"]." ".$row["apellido_materno"];
//$x_aval = $row["nombre_completo"];
	$x_aval = $x_saludo." ". $x_nombre_aval;	
	}
#echo "nombre aval ". $x_nombre_aval."<br>";



//fecha limite pago (actual + 8 dias
$temptime = strtotime(ConvertDateToMysqlFormat($currdate_original));	
$temptime = DateAdd('w',8,$temptime);
$fecha_act = strftime('%Y-%m-%d',$temptime);			
$x_dia = strftime('%A',$temptime);
//Validar domingos
if($x_dia == "SUNDAY"){
	$temptime = strtotime($fecha_act);
	$temptime = DateAdd('w',1,$temptime);
	$fecha_act = strftime('%Y-%m-%d',$temptime);
}
$fecha_act = FechaLetras_normal(strtotime(ConvertDateToMysqlFormat($fecha_act)),false);


//vencimiento
$sSqlWrk = "SELECT *, TO_DAYS('".ConvertDateToMysqlFormat($currdate_original)."') - TO_DAYS(vencimiento.fecha_vencimiento) as dias_venc FROM vencimiento where (credito_id = $x_credito_id) AND (vencimiento.vencimiento_status_id = 3)";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);


$x_total_importe = 0;
$x_total_interes = 0;
$x_total_moratorios = 0;
$x_total_total = 0;
$x_dias_venc_ant = 0;
$x_contador = 0;
$x_iva_mor = 0;
$x_iva = 0;
$x_total_2  = 0;
$x_total  = 0;

while($rowwrk = phpmkr_fetch_array($rswrk)) {
	$x_importe = $rowwrk["importe"];
	$x_interes = $rowwrk["interes"];
	$x_interes_moratorio = $rowwrk["interes_moratorio"];
	$x_dias_venc = $rowwrk["dias_venc"];	
	$x_iva_mor = $rowwrk["iva_mor"];
	$x_iva = $rowwrk["iva"];
	$x_total_venc = $rowwrk["total_venc"];
	
	
	if($x_dias_venc > $x_dias_venc_ant){
		$x_dias_venc_ant = $x_dias_venc;
		$x_fecha_venc = $rowwrk["fecha_vencimiento"];				
	}
	$x_total_importe = $x_total_importe + $x_importe;
	$x_total_interes = $x_total_interes + $x_interes;
	$x_total_moratorios = $x_total_moratorios + $x_interes_moratorio;
	$x_total_iva_mor = $x_total_iva_mor + $x_iva_mor;
	$x_total_iva = $x_total_iva + $x_iva;
	
	$x_total = $x_importe + $x_interes + $x_iva + $x_interes_moratorio + $x_iva_mor;
	
	$x_total_total = $x_total_total + $x_total ;
	$x_total_2 = $x_total_2 + $x_total_venc ;
	$x_contador++;
}
@phpmkr_free_result($rswrk);

//buscamos el vencimeito siguiente
$sqlVS = "SELECT * FROM vencimiento WHERE vencimiento.vencimiento_status_id = 1 and credito_id = $x_credito_id ORDER BY vencimiento.vencimiento_num ASC LIMIT 0,1 ";
$RSvs = phpmkr_query($sqlVS,$conn)or die ("Error al seleccionar el vencimeinto siguiente".phpmkr_error()."sql".$sqlVS);
$rowVS = phpmkr_fetch_array($RSvs);
$x_fecha_ven_si = $rowVS["fecha_vencimiento"];
$x_total_venci_sig =$rowVS["total_venc"];
$x_total_venci_sig_letras = covertirNumLetras($x_total_venci_sig);
$x_monto_vencimiento_siguiente = "$".FormatNumber($x_total_venci_sig,2,0,0,1);

$x_fecha_venc = FechaLetras_normal(strtotime(ConvertDateToMysqlFormat($x_fecha_venc)),false);
$x_fecha_vencimiento_siguiente = FechaLetras_normal(strtotime(ConvertDateToMysqlFormat($x_fecha_ven_si)),false);


$x_moratorio_letras = covertirNumLetras($x_total_moratorios);
$x_moratorios = "$".FormatNumber($x_total_moratorios,2,0,0,1)." (-$x_moratorio_letras-) ";

$x_total_letras = covertirNumLetras($x_total_total);
$x_total_pagar = "$".FormatNumber($x_total_total,2,0,0,1);

$x_saldo_vencido  = $x_total_pagar;
//$x_total_letras = covertirNumLetras($x_total_2);
//$x_total_pagar = "$".FormatNumber($x_total_2,2,0,0,1)." (-$x_total_letras-) ";


//pagos vigentes pendientes
$sSql = "SELECT count(*) as vig_pen FROM vencimiento where credito_id = $x_credito_id and vencimiento_status_id = 1";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_vigentes = $row["vig_pen"];
phpmkr_free_result($rs);

if($x_vigentes > 0){
	$x_parrafo_1 = "";
	$x_parrafo_2 = " para ponerse al corriente es de ".$x_total_pagar.".";	
}else{
	$x_parrafo_1 = "Le recordamos que el cr&eacute;dito se encuentra totalmente vencido. ";# desde el ".$fecha_act.".";
	$x_parrafo_2 = " para liquidar su deuda es de ".$x_total_pagar.".";		
}


//FORMATO
$sSql = "select contenido from formato_docto where formato_docto_id = $x_formato";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_contenido = $row["contenido"];
phpmkr_free_result($rs);

$currdate = date("Y-m-d");

$x_fecha_actual  = FechaLetras_normal(strtotime(ConvertDateToMysqlFormat($currdate)),false);
//$x_fecha_actual = FechaLetras_normal(strtotime(ConvertDateToMysqlFormat($x_fecha_hoy)),false);

$x_hoy = date("Y-m-d");
$sqlDV = "select DATE_ADD(\"$x_hoy\",INTERVAL 10 DAY) as dia_valido";
#echo "sql".$sqlDV." ";
$rsDV = phpmkr_query($sqlDV,$conn)or die("Erro al sle dia val".phpmkr_error());
$rowDV = phpmkr_fetch_array($rsDV);
$x_dia_valido = $rowDV["dia_valido"];
#echo "dia valido".$x_dia_valido."<br>";

$x_fecha_valida =  FechaLetras_normal(strtotime($x_dia_valido),false);

$x_contenido = str_replace("\$x_fecha_actual",$x_fecha_actual,$x_contenido);
$x_contenido = str_replace("\$x_aval",htmlentities($x_aval),$x_contenido);
$x_contenido = str_replace("\$x_titular",htmlentities($x_titular),$x_contenido);
$x_contenido = str_replace("\$x_fecha_otorgamiento",$x_fecha_otorgamiento,$x_contenido);
$x_contenido = str_replace("\$x_parrafo_1",$x_parrafo_1,$x_contenido);
$x_contenido = str_replace("\$x_parrafo_2",$x_parrafo_2,$x_contenido);
$x_contenido = str_replace("\$x_fecha_limite_pago",$fecha_act,$x_contenido);
$x_contenido = str_replace("\$x_moratorios",$x_moratorios,$x_contenido);
$x_contenido = str_replace("\$x_tarjeta_num",htmlentities($x_tarjeta_numero),$x_contenido);
$x_contenido = str_replace("\$x_saldo_vencido",$x_saldo_vencido,$x_contenido);
$x_contenido = str_replace("\$x_nombre_grupo",$x_nombre_grupo,$x_contenido);
$x_contenido = str_replace("\$x_monto_vencimiento_siguiente",$x_monto_vencimiento_siguiente,$x_contenido);
$x_contenido = str_replace("\$x_fecha_vencimiento_siguiente",$x_fecha_vencimiento_siguiente,$x_contenido);
//echo "formato".$x_formato ."<br>";

$x_contenido = str_replace("\$x_usuario",$x_usuario,$x_contenido);
$x_contenido = str_replace("\$x_clave",$x_clave,$x_contenido);
#echo "nombre aval el loas data ".$x_nombre_aval ."--------";
if(strlen($x_nombre_aval) < 5){
	
if(($x_formato == 48) || ($x_formato == 53) ){
	$x_contenido ="";
	}
	
	}	
	return $x_contenido;
	
	}
	
function loadDataCarta1Aval($conn, $credito_id, $x_formato){
	
	//CREDITO
	$x_credito_id = $credito_id;
	$sSql = "SELECT * FROM credito where credito_id = $x_credito_id";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_solicitud_id = $row["solicitud_id"];
	$x_tarjeta_numero = $row["tarjeta_num"];
	$x_fecha_otorgamiento = $row["fecha_otrogamiento"];
	phpmkr_free_result($rs);
	$x_fecha_otorgamiento = FechaLetras_normal(strtotime($x_fecha_otorgamiento),false);

//CLIENTE
$sSqlWrk = "SELECT cliente.usuario_id, cliente.sexo, cliente.nombre_completo as cliente_nombre, cliente.apellido_paterno, cliente.apellido_materno FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id Where credito.credito_id = $x_credito_id ";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	if($rowwrk["sexo"] == 1){
		$x_saludo = "Sr.";
	}else{
		$x_saludo = "Sra.";	
	}
	$x_titular = $x_saludo." ".$rowwrk["cliente_nombre"]." ".$rowwrk["apellido_paterno"]." ".$rowwrk["apellido_materno"];						
	$x_usuario_id = $rowwrk["usuario_id"];
}else{
	$x_titular = "";					
	$x_usuario_id = "";						
}
@phpmkr_free_result($rswrk);

//AVAL
$sSql = "SELECT * FROM aval where solicitud_id = $x_solicitud_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
if($row["sexo"] == 1){
	$x_saludo = "Sr.";
}else{
	$x_saludo = "Sra.";	
}

//$x_aval = $row["nombre_completo"];

	$x_aval = $x_saludo." ".$row["nombre_completo"]." ".$row["apellido_paterno"]." ".$row["apellido_materno"];						

phpmkr_free_result($rs);




//fecha limite pago (actual + 8 dias
$temptime = strtotime(ConvertDateToMysqlFormat($currdate_original));	
$temptime = DateAdd('w',8,$temptime);
$fecha_act = strftime('%Y-%m-%d',$temptime);			
$x_dia = strftime('%A',$temptime);
//Validar domingos
if($x_dia == "SUNDAY"){
	$temptime = strtotime($fecha_act);
	$temptime = DateAdd('w',1,$temptime);
	$fecha_act = strftime('%Y-%m-%d',$temptime);
}
$fecha_act = FechaLetras_normal(strtotime(ConvertDateToMysqlFormat($fecha_act)),false);


//vencimiento
$sSqlWrk = "SELECT *, TO_DAYS('".ConvertDateToMysqlFormat($currdate_original)."') - TO_DAYS(vencimiento.fecha_vencimiento) as dias_venc FROM vencimiento where (credito_id = $x_credito_id) AND (vencimiento.vencimiento_status_id = 3)";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);


$x_total_importe = 0;
$x_total_interes = 0;
$x_total_moratorios = 0;
$x_total_total = 0;
$x_dias_venc_ant = 0;
$x_contador = 0;
$x_iva_mor = 0;
$x_iva = 0;
$x_total_2  = 0;
$x_total  = 0;

while($rowwrk = phpmkr_fetch_array($rswrk)) {
	$x_importe = $rowwrk["importe"];
	$x_interes = $rowwrk["interes"];
	$x_interes_moratorio = $rowwrk["interes_moratorio"];
	$x_dias_venc = $rowwrk["dias_venc"];	
	$x_iva_mor = $rowwrk["iva_mor"];
	$x_iva = $rowwrk["iva"];
	$x_total_venc = $rowwrk["total_venc"];
	
	
	if($x_dias_venc > $x_dias_venc_ant){
		$x_dias_venc_ant = $x_dias_venc;
		$x_fecha_venc = $rowwrk["fecha_vencimiento"];				
	}
	$x_total_importe = $x_total_importe + $x_importe;
	$x_total_interes = $x_total_interes + $x_interes;
	$x_total_moratorios = $x_total_moratorios + $x_interes_moratorio;
	$x_total_iva_mor = $x_total_iva_mor + $x_iva_mor;
	$x_total_iva = $x_total_iva + $x_iva;
	
	$x_total = $x_importe + $x_interes + $x_iva + $x_interes_moratorio + $x_iva_mor;
	
	$x_total_total = $x_total_total + $x_total ;
	$x_total_2 = $x_total_2 + $x_total_venc ;
	$x_contador++;
}
@phpmkr_free_result($rswrk);

$x_fecha_venc = FechaLetras_normal(strtotime(ConvertDateToMysqlFormat($x_fecha_venc)),false);;

$x_moratorio_letras = covertirNumLetras($x_total_moratorios);
$x_moratorios = "$".FormatNumber($x_total_moratorios,2,0,0,1)." (-$x_moratorio_letras-) ";

$x_total_letras = covertirNumLetras($x_total_total);
$x_total_pagar = "$".FormatNumber($x_total_total,2,0,0,1)." (-$x_total_letras-) ";


//$x_total_letras = covertirNumLetras($x_total_2);
//$x_total_pagar = "$".FormatNumber($x_total_2,2,0,0,1)." (-$x_total_letras-) ";


//pagos vigentes pendientes
$sSql = "SELECT count(*) as vig_pen FROM vencimiento where credito_id = $x_credito_id and vencimiento_status_id = 1";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_vigentes = $row["vig_pen"];
phpmkr_free_result($rs);

if($x_vigentes > 0){
	$x_parrafo_1 = "";
	$x_parrafo_2 = " para ponerse al corriente es de ".$x_total_pagar.".";	
}else{
	$x_parrafo_1 = "Le recordamos que el cr&eacute;dito se encuentra totalmente vencido. ";# desde el ".$fecha_act.".";
	$x_parrafo_2 = " para liquidar su deuda es de ".$x_total_pagar.".";		
}


//FORMATO
$sSql = "select contenido from formato_docto where formato_docto_id = $x_formato";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_contenido = $row["contenido"];
phpmkr_free_result($rs);

$x_contenido = str_replace("\$x_fecha_actual",$currdate,$x_contenido);
$x_contenido = str_replace("\$x_aval",htmlentities($x_aval),$x_contenido);
$x_contenido = str_replace("\$x_titular",htmlentities($x_titular),$x_contenido);
$x_contenido = str_replace("\$x_fecha_otorgamiento",$x_fecha_otorgamiento,$x_contenido);
$x_contenido = str_replace("\$x_parrafo_1",$x_parrafo_1,$x_contenido);
$x_contenido = str_replace("\$x_parrafo_2",$x_parrafo_2,$x_contenido);
$x_contenido = str_replace("\$x_fecha_limite_pago",$fecha_act,$x_contenido);
$x_contenido = str_replace("\$x_moratorios",$x_moratorios,$x_contenido);
$x_contenido = str_replace("\$x_tarjeta_num",htmlentities($x_tarjeta_numero),$x_contenido);

$x_contenido = str_replace("\$x_usuario",$x_usuario,$x_contenido);
$x_contenido = str_replace("\$x_clave",$x_clave,$x_contenido);
	
	return $x_contenido;
	
	
	}	
	
	
	
function telefonos($conn, $x_cliente_id){
	
	$x_c_id = $x_cliente_id;
	$x_count_2 = 1;
		$sSql = "select * from  telefono where cliente_id = $x_c_id  AND telefono_tipo_id = 1 order by telefono_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9 = phpmkr_fetch_array($rs9)){			
			$GLOBALS["x_telefono_casa_$x_count_2"] = $row9["numero"];
			$GLOBALS["x_comentario_casa_$x_count_2"] = $row9["comentario"];
			$GLOBALS["contador_telefono"] = $x_count_2;
			$x_count_2++;
			
		}
		
	//	echo "telefonos". $GLOBALS["x_telefono_casa_1"].$GLOBALS["x_telefono_casa_2"]; 



		$x_count_3 = 1;
		$sSql = "select * from  telefono where cliente_id = $x_c_id  AND telefono_tipo_id = 2 order by telefono_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9e = phpmkr_fetch_array($rs9)){			
			$GLOBALS["x_telefono_celular_$x_count_3"] = $row9e["numero"];
			$GLOBALS["x_comentario_celular_$x_count_3"] = $row9e["comentario"];
			$GLOBALS["x_compania_celular_$x_count_3"] = $row9e["compania_id"];	
			$GLOBALS["contador_celular"] = $x_count_3;
			$x_count_3++;
			
		}
		
			//echo "telefonos". $GLOBALS["x_telefono_celular_1"].$GLOBALS["x_telefono_celular_2"]; 
}

function cargaIntegrantes($conn, $x_sol_id){
	$x_soli_id = $x_sol_id;
	//echo "load data solidario";
			$sqlGrupo = "SELECT * FROM creditosolidario WHERE  solicitud_id = $x_soli_id";
			//echo "sql solidario".$sqlGrupo."<br>";
			$responseGrupo = phpmkr_query($sqlGrupo,$conn) or die ("error al ejecutar query grupo".phpmkr_error()."sql: ".$sqlGrupo);
			$rowGrupo = phpmkr_fetch_array($responseGrupo);
			$GLOBALS["x_creditoSolidario_id"] =  $rowGrupo["creditoSolidario_id"];
			$GLOBALS["x_nombre_grupo"] = $rowGrupo["nombre_grupo"];
			//echo $GLOBALS["x_nombre_grupo"]."..";
			$x_cont_g = 1;
			while($x_cont_g <= 10){
				
				$GLOBALS["x_integrante_$x_cont_g"] = $rowGrupo["integrante_$x_cont_g"];
				$GLOBLAS["x_nombre_integrante_$x_cont_g"] = $rowGrupo["integrante_$x_cont_g"];
				 
				//$x_monto_i =  $rowGrupo["monto_$x_cont_g"];
				//$GLOBALS["x_monto_$x_cont_g"] = number_format($x_monto_i);
				
				//echo "integrante".$rowGrupo["integrante_$x_cont_g"]."<br>";
				$GLOBALS["x_monto_$x_cont_g"] =  $rowGrupo["monto_$x_cont_g"];
				$GLOBALS["x_rol_integrante_$x_cont_g"] = $rowGrupo["rol_integrante_$x_cont_g"]; 
				$GLOBALS["x_cliente_id_$x_cont_g"] = $rowGrupo["cliente_id_$x_cont_g"];
				echo "cliente id".$GLOBALS["x_cliente_id_$x_cont_g"]." grupo <br>";
				$grupoId[]= $GLOBALS["x_cliente_id_$x_cont_g"];
				
				foreach( $grupoId as $valos){
					echo "arreglo".$valos;
					}
				//BUSCO AL REPRESENTANTE DEL GRUPO
				if($GLOBALS["x_rol_integrante_$x_cont_g"] == 1){
					$GLOBALS["$x_representate_grupo"] = $rowGrupo["integrante_$x_cont_g"];
					$GLOBALS["x_representante_cliente_id"] =  $rowGrupo["cliente_id_$x_cont_g"];
					}
					
					
					
					
				
				$x_cont_g++;
				}
			
			
			
			phpmkr_free_result($rowGrupo);
	
	
	
	
	}

function LoadDataEdoCuenta($conn, $x_credito_id ){


$x_solcre_id =$x_credito_id;
//CREDITO

$x_tabla_fina = "";
	$x_respuesta = "";
	$x_respuesta1 = "";
	$x_respuesta2 = "";
	$x_respuesta3 = "";
	$x_respuesta4 = "";
	$x_respuesta5 = "";
	$x_respuesta6 = "";
	$x_respuesta7 = "";
	$x_respuesta8 = "";
	$x_respuesta9 = "";
	$x_respuesta10 = "";
	$x_respuesta11 = "";
	$x_respuesta12 = "";
	$x_respuesta13 = "";
	$x_respuesta14 = "";
	$x_respuesta15 = "";
	$x_respuesta16 = "";
	$x_respuesta17 = "";
	$x_respuesta18 = "";
	$x_respuesta19 = "";
	$x_respuesta20 = "";
	$x_respuesta21 = "";
	$x_respuesta26 = "";

$x_respuesta = "";
$sSql = "select * from credito where credito_id = ".$x_solcre_id;
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
	$GLOBALS["x_credito_id"] = $row["credito_id"];
	$x_credito_num = $row["credito_num"];		
	$x_cliente_num = $row["cliente_num"];				
	$x_credito_tipo_id = $row["credito_tipo_id"];
	$GLOBALS["x_credito_tipo_id"] = $row["credito_tipo_id"];
	$GLOBALS["x_solicitud_id"] = $row["solicitud_id"];
	$x_solicitud_id = $row["solicitud_id"];
	$x_credito_status_id = $row["credito_status_id"];
	$x_fecha_otrogamiento = $row["fecha_otrogamiento"];
	$x_importe = $row["importe"];
	$x_tasa = $row["tasa"];
	$x_plazo = $row["plazo_id"];
	$x_fecha_vencimiento = $row["fecha_vencimiento"];
	$x_tasa_moratoria = $row["tasa_moratoria"];
	$x_medio_pago_id= $row["medio_pago_id"];
	$x_banco_id = $row["banco_id"];		
	$x_referencia_pago = $row["referencia_pago"];
	$x_forma_pago_id = $row["forma_pago_id"];	
	$GLOBALS["x_forma_pago_id"] = $row["forma_pago_id"];	
	$x_num_pagos= $row["num_pagos"];				
	$x_tdp = $row["tarjeta_num"];		
	$GLOBALS["credito_tipo_id"] = $row["credito_tipo_id"];
	$credito_tipo_id = $row["credito_tipo_id"];
	if ($GLOBALS["credito_tipo_id"] == 1){
		$x_tipo_credito = " Individual";
		}else {
			$x_tipo_credito = " Solidario";
			}
	
	
					
// seleccionamos los comentarios..

$sSqlcomentarios = "SELECT * FROM `credito_comment` where credito_id = ".$GLOBALS["x_credito_id"]."";
$rsComentarios = phpmkr_query($sSqlcomentarios, $conn) or die ("Error al seleccionar la bitacora".phpmkr_error()."sql:".$sSqlcomentarios);
$rowComentarios = phpmkr_fetch_array($rsComentarios);
//$GLOBALS["x_credito_id"] = $row["credito_id"];
$charlimit = 1700;
$x_comentario_int = "";
$x_comentario_ext = "";
$x_comentario_int = $rowComentarios["comentario_int"];
$x_comentario_ext = $rowComentarios["comentario_ext"];
$x_comentario_ext = cut_string($x_comentario_ext, $charlimit);
$x_comentario_int = cut_string($x_comentario_int, $charlimit);


// seleccionamos los datos del ststaus del contacto =
$SqlContactoStatus = "SELECT * FROM telefono_contacto_status, telefono_status WHERE  telefono_status.telefono_status_id = telefono_contacto_status.telefono_status_id and telefono_contacto_status.credito_id = ".$GLOBALS["x_credito_id"]."";
$rsContactoStatus = phpmkr_query($SqlContactoStatus,$conn) or die ("Error al seleccionar el status del contacto".phpmkr_error()."sql:".$SqlContactoStatus);
$rowContactoStatus = phpmkr_fetch_array($rsContactoStatus);
$x_status_de_contacto = $rowContactoStatus["descripcion"];
mysql_free_result($rsContactoStatus);
//Cuenta
$sSql = "select cliente.* from cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id join solicitud on solicitud.solicitud_id = solicitud_cliente.solicitud_id 
where solicitud.solicitud_id = ".$GLOBALS["x_solicitud_id"];
$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row2 = phpmkr_fetch_array($rs2);
	$GLOBALS["x_cuenta"] = $row2["nombre_completo"]." ".$row2["apellido_paterno"]." ".$row2["apellido_materno"];
	$x_cuenta = $row2["nombre_completo"]." ".$row2["apellido_paterno"]." ".$row2["apellido_materno"];
	$x_c_id = $row2["cliente_id"];

// telefonos
/*if(!empty( $x_c_id)){
 telefonos($conn, $x_c_id);
}*/
$x_count_2 = 1;
if(!empty($x_c_id)){
		$sSql = "select * from  telefono where cliente_id = $x_c_id  AND telefono_tipo_id = 1 order by telefono_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9 = phpmkr_fetch_array($rs9)){			
			$GLOBALS["x_telefono_casa_$x_count_2"] = $row9["numero"];
			$x_telefono_casa[] =$row9["numero"];
			$GLOBALS["x_comentario_casa_$x_count_2"] = $row9["comentario"];
			$GLOBALS["contador_telefono"] = $x_count_2;
			$x_count_2++;
			
		}
}
		



		$x_count_3 = 1;
		if(!empty($x_c_id)){
		$sSql = "select * from  telefono where cliente_id = $x_c_id  AND telefono_tipo_id = 2 order by telefono_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9e = phpmkr_fetch_array($rs9)){			
			$GLOBALS["x_telefono_celular_$x_count_3"] = $row9e["numero"];
			$x_telefono_celular[] = $row9e["numero"];
			$GLOBALS["x_comentario_celular_$x_count_3"] = $row9e["comentario"];
			$GLOBALS["x_compania_celular_$x_count_3"] = $row9e["compania_id"];	
			$GLOBALS["contador_celular"] = $x_count_3;
			$x_count_3++;
			
		}
		}

// direcciones

if(!empty($x_c_id)){
#personal domicilio
$sqlD = "SELECT * FROM direccion WHERE cliente_id = $x_c_id and direccion_tipo_id = 1 order by direccion_id desc limit 1";		
		$sSql2 = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where cliente_id = $x_c_id and direccion_tipo_id = 1 order by direccion_id desc limit 1";
		$rs3 = phpmkr_query($sqlD,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sqlD);
		$row3 = phpmkr_fetch_array($rs3);
		$GLOBALS["x_direccion_id"] = $row3["direccion_id"];		
		$GLOBALS["x_calle_domicilio"] = $row3["calle"];
		$GLOBALS["x_colonia_domicilio"] = $row3["colonia"];
		$GLOBALS["x_delegacion_id"] = $row3["delegacion_id"];
		$GLOBALS["x_localidad_id"] = $row3["localidad_id"];
		$GLOBALS["x_propietario"] = $row3["propietario"];
		$GLOBALS["x_entidad_domicilio"] = $row3["entidad"];
		$GLOBALS["x_codigo_postal_domicilio"] = $row3["codigo_postal"];
		$GLOBALS["x_ubicacion_domicilio"] = $row3["ubicacion"];	
		$GLOBALS["x_numero_exterior"] = $row3["numero_exterior"];
		$x_telefono_casa[] = $row3["telefono"];
		$x_telefono_casa[] = $row3["telefono_secundario"];
		$x_telefono_celular[] = $row3["telefono_movil"];
		
		if (!empty($GLOBALS["x_delegacion_id"])){
		$sqlDel = "SELECT * FROM `delegacion` WHERE `delegacion_id` = ".$GLOBALS["x_delegacion_id"]." ";
		$rsDesl = phpmkr_query($sqlDel, $conn) or die ("Error al seleccionar los del".phpmkr_error()."sql:". $sqlDel);
		$rowDel = phpmkr_fetch_array($rsDesl);
		}
		$x_delegacion = $rowDel["descripcion"];
		
		$x_direccion_dom_th = "Calle : ".$GLOBALS["x_calle_domicilio"]." ". $GLOBALS["x_numero_exterior"].", colonia: ".$GLOBALS["x_colonia_domicilio"].", localidad :".$GLOBALS["x_localidad_id"].", delegacion: ".$x_delegacion.", C.P.: ".$GLOBALS["x_codigo_postal_domicilio"]; 
$x_delegacion = "";
		//falta telefono secundario
#direccion negocio
		$sqlD = "SELECT * FROM direccion WHERE cliente_id = $x_c_id and direccion_tipo_id = 2 order by direccion_id desc limit 1";	
		$sSql = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where cliente_id = $x_c_id  and direccion_tipo_id = 2 order by direccion_id desc limit 1";
		$rs4 = phpmkr_query($sqlD,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row4 = phpmkr_fetch_array($rs4);
		
			$GLOBALS["x_direccion_id2"] = $row4["direccion_id"];
			$GLOBALS["x_giro_negocio"] = $row4["giro_negocio"];
			$GLOBALS["x_calle_negocio"] = $row4["calle"];
			$GLOBALS["x_colonia_negocio"] = $row4["colonia"];
			$GLOBALS["x_entidad_negocio"] = $row4["entidad"];
			$GLOBALS["x_ubicacion_negocio"] = $row4["ubicacion"];
			$GLOBALS["x_codigo_postal_negocio"] = $row4["codigo_postal"];			
			$GLOBALS["x_delegacion_id2"] = $row4["delegacion_id"];
			$GLOBALS["x_localidad_id2"] = $row4["localidad_id"];
			$GLOBALS["x_numero_exterior2"] = $row4["numero_exterior"];
			$x_telefono_casa[] = $row3["telefono"];
			$x_telefono_casa[] = $row3["telefono_secundario"];
			$x_telefono_celular[] = $row3["telefono_movil"];
			
			
			 if(!empty($GLOBALS["x_delegacion_id2"])){
				$sqlDel = "SELECT * FROM `delegacion` WHERE `delegacion_id` = ".$GLOBALS["x_delegacion_id2"]." ";
		$rsDesl = phpmkr_query($sqlDel, $conn) or die ("Error al seleccionar los del".phpmkr_error()."sql:". $sqlDel);
		$rowDel = phpmkr_fetch_array($rsDesl);
			 }
		$x_delegacion1 = $rowDel["descripcion"];
				$x_direccion_neg_th = "Calle : ".$GLOBALS["x_calle_negocio"]." ".$GLOBALS["x_numero_exterior2"].", colonia: ".$GLOBALS["x_colonia_negocio"].", localidad :".$GLOBALS["x_localidad_id2"].", delegacion: ".$x_delegacion1.", C.P.: ".$GLOBALS["x_codigo_postal_negocio"]; 
				
		$x_delegacion= "";		
		
		
}

## direccion aval
$sSqlWrka = "SELECT * FROM datos_aval Where solicitud_id = ".$GLOBALS["x_solicitud_id"] ."  group by datos_aval_id";
	$rswrka = phpmkr_query($sSqlWrka,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrka);
	#echo $sSqlWrka;
	$rowwrka = phpmkr_fetch_array($rswrka);
	$x_aval = $rowwrka["aval"];
	$x_datos_aval_id = $rowwrka["datos_aval_id"];
	#echo $x_datos_aval_id."aid";
	@phpmkr_free_result($rswrk);
	    $GLOBALS["x_nombre_completo"] = $rowwrka["nombre_completo"];
		$GLOBALS["x_apellido_paterno"] = $rowwrka["apellido_paterno"];
		$GLOBALS["x_apellido_materno"] = $rowwrka["apellido_materno"];
		$GLOBALS["x_nombre_avala"]= " ". $GLOBALS["x_nombre_completo"] ." ".$GLOBALS["x_apellido_paterno"]." ".$GLOBALS["x_apellido_materno"]." ";
		$x_nombre_aval= " ". $GLOBALS["x_nombre_completo"] ." ".$GLOBALS["x_apellido_paterno"]." ".$GLOBALS["x_apellido_materno"]." ";
		$GLOBALS["x_rfc"] = $rowwrka["rfc"];
		$GLOBALS["x_curp"] = $rowwrka["curp"];
		$GLOBALS["x_calle"] = $rowwrka["calle"];
		$GLOBALS["x_colonia"] = $rowwrka["colonia"];
		$GLOBALS["x_entidad"] = $rowwrka["entidad"];
		$GLOBALS["x_codigo_postal"] = $rowwrka["codigo_postal"];
		$GLOBALS["x_delegacion"] = $rowwrka["delegacion"];
		$GLOBALS["x_vivienda_tipo"] = $rowwrka["vivienda_tipo"];
		$GLOBALS["x_telefono_p"] = $rowwrka["telefono_p"];
		$GLOBALS["x_telefono_c"] = $rowwrka["telefono_c"];
		$GLOBALS["x_telefono_o"] = $rowwrka["telefono_o"];
		$GLOBALS["x_antiguedad_v"] = $rowwrka["antiguedad_v"];
		$GLOBALS["x_tel_arrendatario_v"] = $rowwrka["tel_arrendatario_v"];
		$GLOBALS["x_renta_mensual_v"] = $rowwrka["renta_mensual_v"];
		$GLOBALS["x_calle_2"] = $rowwrka["calle_2"];
		$GLOBALS["x_colonia_2"] = $rowwrka["colonia_2"];
		$GLOBALS["x_entidad_2"] = $rowwrka["entidad_2"];
		$GLOBALS["x_codigo_postal_2"] = $rowwrka["codigo_postal_2"];
		$GLOBALS["x_delegacion_2"] = $rowwrka["delegacion_2"];
		
		if(!empty($GLOBALS["x_delegacion"])) {
		$sqlDel = "SELECT * FROM `delegacion` WHERE `delegacion_id` = ".$GLOBALS["x_delegacion"]." ";
		$rsDesl = phpmkr_query($sqlDel, $conn) or die ("Error al seleccionar los del".phpmkr_error()."sql:". $sqlDel);
		$rowDel = phpmkr_fetch_array($rsDesl);
		}
		$x_delegacion2 = $rowDel["descripcion"];
		
		$x_direccion_dom_av = "Calle : ".$GLOBALS["x_calle"].", colonia: ".$GLOBALS["x_colonia"].", delegacion: ".$x_delegacion2 ;
		$x_delegacion = "";
		if(!empty($GLOBALS["x_delegacion_2"])){
		$sqlDel = "SELECT * FROM `delegacion` WHERE `delegacion_id` = ".$GLOBALS["x_delegacion_2"]." ";
		$rsDesl = phpmkr_query($sqlDel, $conn) or die ("Error al seleccionar los del".phpmkr_error()."sql:". $sqlDel);
		$rowDel = phpmkr_fetch_array($rsDesl);
		}
		$x_delegacion3 = $rowDel["descripcion"]; 
		$x_direccion_neg_av = "Calle : ".$GLOBALS["x_calle_2"].", colonia: ".$GLOBALS["x_colonia_2"].", delegacion: ".$x_delegacion3; 
$x_delegacion = "";


if (empty($x_datos_aval_id)){
	// se buscan los datos del avla en la tabla anterior

	$sqlAval	 = "SELECT * FROM aval	WHERE solicitud_id = $x_solicitud_id ";
	$rsAval = phpmkr_query($sqlAval, $conn)  or die ("Error al seleccionar los adtos del aval de la tabla prima".phpmkr_error()."sql:".$sqlAval);
	$rowAval = phpmkr_fetch_array($rsAval);
	$x_aval_id = $rowAval["aval_id"];
	$x_nombre_aval =" ".$rowAval["nombre_completo"]. " ". $rowAval["apellido_paterno"]." ".$rowAval["apellido_materno"];
	
	//echo "nombre aval".$x_nombre_aval;
	if(!empty($x_aval_id)){
	//seleccionamos las direcciones de los avlaes de las tablas primarias
	$sqlD = "SELECT * FROM direccion WHERE aval_id = $x_aval_id and direccion_tipo_id = 3  order by direccion_id desc limit 1";	
	//echo "dir aval".$sqlD;	
		$sSql2 = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where aval_id = $x_aval_id and direccion_tipo_id = 1 order by direccion_id desc limit 1";
		$rs3 = phpmkr_query($sqlD,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sqlD);
		$row3 = phpmkr_fetch_array($rs3);
		$GLOBALS["x_direccion_id"] = $row3["direccion_id"];		
		$GLOBALS["x_calle_domicilio"] = $row3["calle"];
		$GLOBALS["x_colonia_domicilio"] = $row3["colonia"];
		$GLOBALS["x_delegacion_id"] = $row3["delegacion_id"];
		$GLOBALS["x_localidad_id"] = $row3["localidad_id"];
		$GLOBALS["x_propietario"] = $row3["propietario"];
		$GLOBALS["x_entidad_domicilio"] = $row3["entidad"];
		$GLOBALS["x_codigo_postal_domicilio"] = $row3["codigo_postal"];
		$GLOBALS["x_ubicacion_domicilio"] = $row3["ubicacion"];	
		$GLOBALS["x_numero_exterior"] = $row3["numero_exterior"];
		$x_telefono_casa_aval[] = $row3["telefono"];
		$x_telefono_casa_aval[] = $row3["telefono_secundario"];
		$x_telefono_casa_aval[] = $row3["telefono_movil"];
		
		//echo "aval".$row3["telefono"]." ".$row3["telefono_secundario"].$row3["telefono_movil"];
		
		
		if (!empty($GLOBALS["x_delegacion_id"])){
		$sqlDel = "SELECT * FROM `delegacion` WHERE `delegacion_id` = ".$GLOBALS["x_delegacion_id"]." ";
		$rsDesl = phpmkr_query($sqlDel, $conn) or die ("Error al seleccionar los del".phpmkr_error()."sql:". $sqlDel);
		$rowDel = phpmkr_fetch_array($rsDesl);
		}
		$x_delegacion = $rowDel["descripcion"];
		
		$x_direccion_dom_av = "Calle : ".$GLOBALS["x_calle_domicilio"]." ". $GLOBALS["x_numero_exterior"].", colonia: ".$GLOBALS["x_colonia_domicilio"].", localidad :".$GLOBALS["x_localidad_id"].", delegacion: ".$x_delegacion.", C.P.: ".$GLOBALS["x_codigo_postal_domicilio"]; 
$x_delegacion = "";
		//falta telefono secundario
		
	}
	
	}
//promotor

$sSql = "select promotor.nombre_completo from promotor join solicitud on solicitud.promotor_id = promotor.promotor_id where solicitud.solicitud_id = ".$GLOBALS["x_solicitud_id"];
$rs3 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row3 = phpmkr_fetch_array($rs3);
$x_promotor = $row3["nombre_completo"];


//Pagos Vencidos
$sSql = "select count(*) as vencidos from vencimiento
where credito_id = ".$GLOBALS["x_credito_id"]." and vencimiento_status_id = 3 ";
$rs4 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row4 = phpmkr_fetch_array($rs4);
	$GLOBALS["x_pagos_vencidos"] = $row4["vencidos"];
	
	
	
	
	//INTEGRANTES DEL GRUPO
		$x_soli_id =  $GLOBALS["x_solicitud_id"];
		if($GLOBALS["x_credito_tipo_id"] == 2){
		//	cargaIntegrantes($conn, $x_soli_id);
			// ES UN CREDITO SOLIDARIO
			
			$sqlGrupo = "SELECT * FROM creditosolidario WHERE  solicitud_id = $x_soli_id";
			//echo "sql solidario".$sqlGrupo."<br>";
			$responseGrupo = phpmkr_query($sqlGrupo,$conn) or die ("error al ejecutar query grupo".phpmkr_error()."sql: ".$sqlGrupo);
			$rowGrupo = phpmkr_fetch_array($responseGrupo);
			$GLOBALS["x_creditoSolidario_id"] =  $rowGrupo["creditoSolidario_id"];
			$GLOBALS["x_nombre_grupo"] = $rowGrupo["nombre_grupo"];
			//echo $GLOBALS["x_nombre_grupo"]."..";
			$x_cont_g = 1;
			while($x_cont_g <= 10){
				
				$GLOBALS["x_integrante_$x_cont_g"] = $rowGrupo["integrante_$x_cont_g"];
				$GLOBLAS["x_nombre_integrante_$x_cont_g"] = $rowGrupo["integrante_$x_cont_g"];
				 
				//$x_monto_i =  $rowGrupo["monto_$x_cont_g"];
				//$GLOBALS["x_monto_$x_cont_g"] = number_format($x_monto_i);
				
				//echo "integrante".$rowGrupo["integrante_$x_cont_g"]."<br>";
				$GLOBALS["x_monto_$x_cont_g"] =  $rowGrupo["monto_$x_cont_g"];
				$GLOBALS["x_rol_integrante_$x_cont_g"] = $rowGrupo["rol_integrante_$x_cont_g"]; 
				$GLOBALS["x_cliente_id_$x_cont_g"] = $rowGrupo["cliente_id_$x_cont_g"];
				//echo "cliente id".$GLOBALS["x_cliente_id_$x_cont_g"]." grupo <br>";
				$grupoId[]= $GLOBALS["x_cliente_id_$x_cont_g"];
				
				
				//BUSCO AL REPRESENTANTE DEL GRUPO
				if($GLOBALS["x_rol_integrante_$x_cont_g"] == 1){
					$GLOBALS["$x_representate_grupo"] = $rowGrupo["integrante_$x_cont_g"];
					$GLOBALS["x_representante_cliente_id"] =  $rowGrupo["cliente_id_$x_cont_g"];
					}
					
					
					
					
				
				$x_cont_g++;
				}
			
			
			
			
			
			
			}


phpmkr_free_result($rs);
phpmkr_free_result($rs2);
phpmkr_free_result($rs3);
phpmkr_free_result($rs4);


$x_respuesta = '
<table align="center" width="700" border="0" cellspacing="0" cellpadding="0">
<tr>
<td>
  <img src="images/logo_arbol_completo.gif" width="115" height="93" />
  </td></tr>
</table>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="0" class="link_oculto">
<tr >
  <td colspan="5" class="txt_negro_medio" style=" border-bottom: solid 1px #C00; border-top: solid 1px #C00; padding-bottom: 5px; padding-top: 5px;" bgcolor="#ffffff">
  </td>
  </tr></table>
  
  <table align="center" width="700" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td class="txt_negro_medio" >

  </td>
  <td colspan="4" class="txt_datos_azul" align="center">Credito'.$x_tipo_credito.'</td>
</tr>

<tr>
  <td class="txt_negro_medio">Status del contacto:</td>
  <td class="txt_datos_azul">'.$x_status_de_contacto.'</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
</tr>

<tr>
  <td class="txt_negro_medio">Cuenta</td>
  <td colspan="4" class="txt_datos_azul"> '.$x_cuenta.'</td>
  </tr>
   <tr>
  <td class="txt_negro_medio">Cliente No.</td>
  <td colspan="4" class="txt_datos_azul"> '.$x_cliente_num.'</td>
  </tr>
<tr>
  <td class="txt_negro_medio">Promotor:</td>
  <td colspan="4" class="txt_datos_azul">'. $x_promotor.'</td>
  </tr>
<tr>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
</tr>
<tr>
<td width="132" class="txt_negro_medio">Credito No.
</td>
<td width="108" class="txt_datos_azul">'.$x_credito_num.'</td>
<td width="41">&nbsp;</td>
<td width="106" class="txt_negro_medio">Status</td>
<td width="313" class="txt_datos_azul">';
  
if ((!is_null($x_credito_status_id)) && ($x_credito_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `credito_status`";
	$sTmp = $x_credito_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk11 .= " WHERE `credito_status_id` = " . $sTmp . "";
	$rswrk11 = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk11 && $rowwrk11 = phpmkr_fetch_array($rswrk11)) {
		$sTmp = $rowwrk11["descripcion"];
	}
	@phpmkr_free_result($rswrk11);
} else {
	$sTmp = "";
}
$ox_credito_status_id = $x_credito_status_id; // Backup Original Value
$x_credito_status_id = $sTmp;

  $x_respuesta .= " $x_credito_status_id";
   $x_credito_status_id = $ox_credito_status_id; // Restore Original Value 
  
  
   $x_respuesta .= "</td></tr>";
  
$x_respuesta .=  '<tr>
  <td class="txt_negro_medio"> Tarjeta Num.</td>
  <td class="txt_datos_azul">'.$x_tdp.'</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">Otorgado</td>
  <td class="txt_datos_azul">'. FormatDateTime($x_fecha_otrogamiento,7).'</td>
</tr>';
$x_respuesta .= '<tr>
  <td class="txt_negro_medio">Vencimiento</td>
  <td class="txt_datos_azul">'. FormatDateTime($x_fecha_vencimiento,7).'</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">Importe</td>
  <td class="txt_datos_azul">'.FormatNumber($x_importe,0,0,0,-2).'</td>
</tr>';
$x_respuesta .= '<tr>
  <td class="txt_negro_medio">Tasa</td>
  <td class="txt_datos_azul">'. FormatPercent(($x_tasa / 100),2,0,0,0).'</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">Num. pagos</td>
  <td class="txt_datos_azul">'.$x_num_pagos.'</td>
</tr>';
$x_respuesta .= '<tr>
  <td class="txt_negro_medio">Tasa Moratorios</td>
  <td class="txt_datos_azul">'.FormatPercent(($x_tasa_moratoria / 100),2,0,0,0).'</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">Forma de pago</td>
  <td class="txt_datos_azul">';
    
		$sSqlWrk = "SELECT descripcion FROM forma_pago where forma_pago_id = ".$GLOBALS["x_forma_pago_id"]."";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
	$x_respuesta .= '' .$datawrk["descripcion"].'';
		@phpmkr_free_result($rswrk);
		
  $x_respuesta .=  '</td>
</tr>';
$x_respuesta .= '<tr>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
</tr>
</table>';
 if( $x_credito_tipo_id == 2){
	//$x_respuesta .= "CHULMIS";
	 	//imprimimos la tabla de los integrantes
	//echo $x_tabla_integrantes;
	

	//$x_no_integrante = 1;
//while($x_no_integrante <= 10){
	 foreach($grupoId as $x_cliente_idG){
	
		$x_id_int =  $x_cliente_idG;
	//	echo "CLIENTE ID".$x_id_int."<BR>";

	if(!empty($x_id_int) && $x_id_int >0 ){
		//Buscamos las direcciones y los datos del clientre
		
		
		//Nombre
		//Cuenta
$sSql = "select cliente.* from cliente where cliente_id = $x_id_int ";
//echo $sSql;
$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row2 = phpmkr_fetch_array($rs2);
	$x_cuenta_g = $row2["nombre_completo"]." ".$row2["apellido_paterno"]." ".$row2["apellido_materno"];
		//echo $GLOBALS["x_cuenta"];
		unset($x_telefono_casa);
		unset($x_telefono_celular);
		if(!empty($x_id_int)){
		telefonos($conn,$x_id_int);
		}
		
		$x_respuesta .= '<table align="center" width="700" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td align="center" class="txt_negro_medio" style=" border-bottom: solid 1px #000">DATOS DEL CLIENTE:   '.$x_cuenta_g.'</td>
</tr>';
$x_count_2 = 1;
		$sSql = "select * from  telefono where cliente_id = $x_id_int  AND telefono_tipo_id = 1 order by telefono_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9 = phpmkr_fetch_array($rs9)){			
			$GLOBALS["x_telefono_casa_$x_count_2"] = $row9["numero"];
			$x_telefono_casa[] =$row9["numero"];
			$GLOBALS["x_comentario_casa_$x_count_2"] = $row9["comentario"];
			$GLOBALS["contador_telefono"] = $x_count_2;
			$x_count_2++;			
		}
		
		



		$x_count_3 = 1;
		$sSql = "select * from  telefono where cliente_id = $x_id_int  AND telefono_tipo_id = 2 order by telefono_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9e = phpmkr_fetch_array($rs9)){			
			$GLOBALS["x_telefono_celular_$x_count_3"] = $row9e["numero"];
			$x_telefono_celular[] =$row9e["numero"];
			$GLOBALS["x_comentario_celular_$x_count_3"] = $row9e["comentario"];
			$GLOBALS["x_compania_celular_$x_count_3"] = $row9e["compania_id"];	
			$GLOBALS["contador_celular"] = $x_count_3;
			$x_count_3++;
			
		}		
		
		#personal domicilio
$sqlD = "SELECT * FROM direccion WHERE cliente_id = $x_id_int and direccion_tipo_id = 1 order by direccion_id desc limit 1";		
		$sSql2 = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where cliente_id = $x_c_id and direccion_tipo_id = 1 order by direccion_id desc limit 1";
		$rs3 = phpmkr_query($sqlD,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sqlD);
		$row3 = phpmkr_fetch_array($rs3);
		$GLOBALS["x_direccion_id"] = $row3["direccion_id"];		
		$GLOBALS["x_calle_domicilio"] = $row3["calle"];
		$GLOBALS["x_colonia_domicilio"] = $row3["colonia"];
		$GLOBALS["x_delegacion_id"] = $row3["delegacion_id"];
		$GLOBALS["x_localidad_id"] = $row3["localidad_id"];
		$GLOBALS["x_propietario"] = $row3["propietario"];
		$GLOBALS["x_entidad_domicilio"] = $row3["entidad"];
		$GLOBALS["x_codigo_postal_domicilio"] = $row3["codigo_postal"];
		$GLOBALS["x_ubicacion_domicilio"] = $row3["ubicacion"];	
		$GLOBALS["x_numero_exterior"] = $row3["numero_exterior"];
		$GLOBALS["x_telefono"] = $row3["telefono"];
		$GLOBALS["x_telefono_secundario"] = $row3["telefono_secundario"];
		$GLOBALS["x_telefono_movil"] = $row3["telefono_movil"];
		$x_telefono_casa[] = $row3["telefono"];
		$x_telefono_casa[] = $row3["telefono_secundario"];
		$x_telefono_celular[] = $row3["telefono_movil"];
		
		if (!empty($GLOBALS["x_delegacion_id"])){
		$sqlDel = "SELECT * FROM `delegacion` WHERE `delegacion_id` = ".$GLOBALS["x_delegacion_id"]." ";
		$rsDesl = phpmkr_query($sqlDel, $conn) or die ("Error al seleccionar los del".phpmkr_error()."sql:". $sqlDel);
		$rowDel = phpmkr_fetch_array($rsDesl);
		}
		$x_delegacion = $rowDel["descripcion"];
		
		$x_direccion_dom_th_g = "Calle : ".$GLOBALS["x_calle_domicilio"]." ". $GLOBALS["x_numero_exterior"].", colonia: ".$GLOBALS["x_colonia_domicilio"].", localidad :".$GLOBALS["x_localidad_id"].", delegacion: ".$x_delegacion.", C.P.: ".$GLOBALS["x_codigo_postal_domicilio"]; 
$x_delegacion = "";

//echo $GLOBALS["x_direccion_dom_th"] ;

		//falta telefono secundario
#direccion negocio
		$sqlD = "SELECT * FROM direccion WHERE cliente_id = $x_id_int and direccion_tipo_id = 2 order by direccion_id desc limit 1";	
		$sSql = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where cliente_id = $x_c_id  and direccion_tipo_id = 2 order by direccion_id desc limit 1";
		$rs4 = phpmkr_query($sqlD,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row4 = phpmkr_fetch_array($rs4);
		
			$GLOBALS["x_direccion_id2"] = $row4["direccion_id"];
			$GLOBALS["x_giro_negocio"] = $row4["giro_negocio"];
			$GLOBALS["x_calle_negocio"] = $row4["calle"];
			$GLOBALS["x_colonia_negocio"] = $row4["colonia"];
			$GLOBALS["x_entidad_negocio"] = $row4["entidad"];
			$GLOBALS["x_ubicacion_negocio"] = $row4["ubicacion"];
			$GLOBALS["x_codigo_postal_negocio"] = $row4["codigo_postal"];			
			$GLOBALS["x_delegacion_id2"] = $row4["delegacion_id"];
			$GLOBALS["x_localidad_id2"] = $row4["localidad_id"];
			$GLOBALS["x_numero_exterior2"] = $row4["numero_exterior"];
			$x_telefono_casa[] = $row3["telefono"];
		$x_telefono_casa[] = $row3["telefono_secundario"];
		$x_telefono_celular[] = $row3["telefono_movil"];
			
			
			 if(!empty($GLOBALS["x_delegacion_id2"])){
				$sqlDel = "SELECT * FROM `delegacion` WHERE `delegacion_id` = ".$GLOBALS["x_delegacion_id2"]." ";
		$rsDesl = phpmkr_query($sqlDel, $conn) or die ("Error al seleccionar los del".phpmkr_error()."sql:". $sqlDel);
		$rowDel = phpmkr_fetch_array($rsDesl);
			 }
		$x_delegacion1 = $rowDel["descripcion"];
				$x_direccion_neg_th_g = "Calle : ".$GLOBALS["x_calle_negocio"]." ".$GLOBALS["x_numero_exterior2"].", colonia: ".$GLOBALS["x_colonia_negocio"].", localidad :".$GLOBALS["x_localidad_id2"].", delegacion: ".$x_delegacion1.", C.P.: ".$GLOBALS["x_codigo_postal_negocio"]; 
				
		$x_delegacion= "";		
		//echo $GLOBALS["x_direccion_neg_th"];
		

		
	$x_lis_tel = "";	
	if(!empty($x_telefono_casa)){
foreach($x_telefono_casa as $telefonos_casa){
	$x_lis_tel = $x_lis_tel.$telefonos_casa .", ";
	}
	}
	$x_lis_cel = "";
	if(!empty($x_telefono_celeular)){
foreach($x_telefono_celeular as $telefonos_cel){
	$x_lis_cel = $x_lis_cel.$telefonos_cel .", ";
	}	
	}
	
	if(!empty($x_telefono_casa_aval)){
		foreach( $x_telefono_casa_aval as $telefonos_aval){
			$x_lis_tel_aval = $x_lis_tel_aval. $telefonos_aval. " "; 
			
			}
		
		}

$x_respuesta .='<tr>
  <td  class="txt_negro_medio">TH DOM:'. $x_direccion_dom_th_g.'</td>
</tr>
<tr>
  <td  class="txt_negro_medio">TH NEG:'. $x_direccion_neg_th_g.'</td>
</tr>
<tr>';
$x_respuesta .= '<td  class="txt_negro_medio">Telefonos:  " Casa: "'.$x_lis_tel.' " Cel: "'.$x_lis_cel.'</td>
</tr>
<tr>
  <td  class="txt_negro_medio">&nbsp;</td>
</tr>
</table>';


	}

}
	
	
	
	
	
	
	}else{
    
$x_respuesta7=' <table align="center" width="700" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td colspan="5" align="center" class="txt_negro_medio" style=" border-bottom: solid 1px #000">Datos de Cliente</td>
</tr>
<tr>
  <td colspan="5"  class="txt_negro_medio">TH DOM:'.$x_direccion_dom_th.'</td>
</tr>
<tr>
  <td colspan="5"  class="txt_negro_medio">TH NEG: '.$x_direccion_neg_th.'</td>
</tr>
<tr>';


if(!empty($x_telefono_casa)){
foreach($x_telefono_casa as $telefonos_casa){
	$x_lis_tel = $x_lis_tel.$telefonos_casa .", ";
	}
}
if(!empty($x_telefono_celular)){
foreach($x_telefono_celular as $telefonos_cel){
	$x_lis_cel = $x_lis_cel.$telefonos_cel .", ";
	}
}
if(!empty($x_telefono_casa_aval)){
		foreach( $x_telefono_casa_aval as $telefonos_aval){
			$x_lis_tel_aval = $x_lis_tel_aval. $telefonos_aval. " "; 
			
			}
		
		}

$x_respuesta8='<td colspan="5"  class="txt_negro_medio">Telefonos:  Casa: '.$x_lis_tel. ' Cel: '.$x_lis_cel.'</td>
</tr>
<tr>
  <td colspan="5"  class="txt_negro_medio">&nbsp;</td>
</tr>
<tr>
  <td colspan="5" align="center" class="txt_negro_medio" style=" border-bottom: solid 1px #000">Datos de Aval</td>
</tr>
<tr>
  <td colspan="5" class="txt_negro_medio">Aval :'. $x_nombre_avala.'</td>
</tr>
<tr>
  <td colspan="5"  class="txt_negro_medio">AV DOM: '.$x_direccion_dom_av.'</td>
</tr>
<tr>
  <td colspan="5"  class="txt_negro_medio">AV NEG:'. $x_direccion_neg_av.'</td>
</tr>
<tr>';



$x_respuesta9=' <td colspan="5"  class="txt_negro_medio">Telefonos: Casa: '.$x_lis_tel_aval. ' Cel: '.$x_telefono_c.'</td>
</tr>
<tr>
  <td colspan="5"  class="txt_negro_medio">&nbsp;</td>
</tr>
</table>';
}
$x_respuesta10= '<table width="700" align="center">
<tr>
  <td colspan="5" align="center" class="txt_negro_medio" style=" border-bottom: solid 1px #000">Estado de Cliente</td>
</tr>
<tr>
  <td colspan="5" align="center" class="txt_negro_medio">&nbsp;</td>
</tr>
<tr>
  <td colspan="5" class="txt_negro_medio">';
  
$x_credito_id = $x_solcre_id;
	$sSqlvencimeinto = "SELECT * FROM vencimiento WHERE (vencimiento.credito_id = $x_solcre_id) and vencimiento.vencimiento_num < 2000 ORDER BY vencimiento.vencimiento_num+0";  
	//and vencimiento.vencimiento_num < 2000
	#echo $sSqlvencimeinto;
	$rsVencimiento = phpmkr_query($sSqlvencimeinto,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSqlvencimiento);
  
  
  
$x_respuesta11='<table width="750"  align="center" cellpadding="0" cellspacing="0" border="0"> 
    
    <tr class="ewTableHeader">
      <td width="1%">&nbsp;  </td>
      <td width="4%" align="center" valign="middle" class="txt_negro_medio">No</td>
      <td width="8%" align="center" valign="middle" class="txt_negro_medio"> Status </td>
      <td width="7%" align="center" valign="middle" class="txt_negro_medio"> Fecha </td>
      <td width="13%" align="center" valign="middle" class="txt_negro_medio"> Fecha de Pago </td>
      <td width="9%" align="center" valign="middle" class="txt_negro_medio"> Capital </td>
      <td width="8%" align="center" valign="middle" class="txt_negro_medio"> Importe </td>
      <td width="9%" align="center" valign="middle" class="txt_negro_medio"> Inter&eacute;s </td>
      <td width="8%" align="center" valign="middle" class="txt_negro_medio">IVA</td>
      <td width="11%" align="center" valign="middle" class="txt_negro_medio"> Moratorios </td>
      <td width="9%" align="center" valign="middle" class="txt_negro_medio">IVA M.</td>
      <td width="13%" align="center" valign="middle" class="txt_negro_medio"> Total </td>
    </tr>';
    


$sSqlWrk = "SELECT importe FROM credito where credito_id = $x_solcre_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
$x_saldo = $datawrk["importe"];
@phpmkr_free_result($rswrk);

$x_total = 0;
$x_total_pagos = 0;
$x_total_interes = 0;
$x_total_iva = 0;
$x_total_moratorios = 0;
$x_total_iva_moratorios = 0;
$x_total_total = 0;

$x_total_d = 0;
$x_total_pagos_d = 0;
$x_total_interes_d = 0;
$x_total_iva_d = 0;
$x_total_moratorios_d = 0;
$x_total_iva_moratorios_d = 0;
$x_total_total_d = 0;

//NUEVO
$x_total_pagos_v = 0;
$x_total_interes_v = 0;
$x_total_iva_v = 0;
$x_total_iva_mor_v = 0;
$x_total_moratorios_v = 0;
$x_total_total_v = 0;


$x_total_a = 0;
$x_total_pagos_a = 0;
$x_total_interes_a = 0;
$x_total_iva_a = 0;
$x_total_moratorios_a = 0;
$x_total_iva_moratorios_a = 0;
$x_total_total_a = 0;


while ($rowVencimeinto = @phpmkr_fetch_array($rsVencimiento)) {

		$x_vencimiento_id = $rowVencimeinto["vencimiento_id"];
		$x_vencimiento_num = $rowVencimeinto["vencimiento_num"];		
		$x_vencimiento_status_id = $rowVencimeinto["vencimiento_status_id"];
		
		$x_fecha_vencimiento = $rowVencimeinto["fecha_vencimiento"];
		$x_importe = $rowVencimeinto["importe"];
		$x_interes = $rowVencimeinto["interes"];
		$x_iva = $rowVencimeinto["iva"];		
		$x_iva_mor = $rowVencimeinto["iva_mor"];				
		if(empty($x_iva)){
			$x_iva = 0;
		}
		if(empty($x_iva_mor)){
			$x_iva_mor = 0;
		}
		
		$x_interes_moratorio = $rowVencimeinto["interes_moratorio"];
		
		$x_total = $x_importe + $x_interes + $x_iva + $x_interes_moratorio + $x_iva_mor;

		$x_total_pagos = $x_total_pagos + $x_importe;
		$x_total_interes = $x_total_interes + $x_interes;
		$x_total_iva = $x_total_iva + $x_iva;		
		$x_total_moratorios = $x_total_moratorios + $x_interes_moratorio;
		$x_total_iva_moratorios = $x_total_iva_moratorios + $x_iva_mor;		
		$x_total_total = $x_total_total + $x_total;
		
		if(($x_vencimiento_status_id == 2) || ($x_vencimiento_status_id == 5)){
		
			$sSqlWrk = "SELECT fecha_pago, referencia_pago_2 FROM recibo join recibo_vencimiento on recibo_vencimiento.recibo_id = recibo.recibo_id where recibo_vencimiento.vencimiento_id = $x_vencimiento_id and recibo.recibo_status_id = 1";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$rowwrk = phpmkr_fetch_array($rswrk);
			$x_fecha_pago = $rowwrk["fecha_pago"];
			$x_referencia_pago2 = $rowwrk["referencia_pago_2"];			

			@phpmkr_free_result($rswrk);

			$x_total_pagos_a = $x_total_pagos_a + $x_importe;
			$x_total_interes_a = $x_total_interes_a + $x_interes;
			$x_total_iva_a = $x_total_iva_a + $x_iva;			
			$x_total_moratorios_a = $x_total_moratorios_a + $x_interes_moratorio;
			$x_total_iva_moratorios_a = $x_total_iva_moratorios_a + $x_iva_mor;			
			$x_total_total_a = $x_total_total_a + $x_total;

		}else{
			$x_fecha_pago  = "";
			$x_referencia_pago2 = "";						

			$x_total_pagos_d = $x_total_pagos_d + $x_importe;
			$x_total_interes_d = $x_total_interes_d + $x_interes;
			$x_total_iva_d = $x_total_iva_d + $x_iva;			
			$x_total_moratorios_d = $x_total_moratorios_d + $x_interes_moratorio;
			$x_total_iva_moratorios_d = $x_total_iva_moratorios_d + $x_iva_mor;			
			$x_total_total_d = $x_total_total_d + $x_total;
			
			//NUEVO
			
			if($x_vencimiento_status_id == 3){
				$x_total_pagos_v = $x_total_pagos_v + $x_importe;
				$x_total_interes_v = $x_total_interes_v + $x_interes;
				$x_total_iva_v = $x_total_iva_v + $x_iva;			
				$x_total_iva_mor_v = $x_total_iva_mor_v + $x_iva_mor;						
				$x_total_moratorios_v = $x_total_moratorios_v + $x_interes_moratorio;
				$x_total_total_v = $x_total_total_v + $x_total;
			}
			
			
			
			
			
			
		}
		
		$x_ref_loc = str_pad($x_vencimiento_id, 5, "0", STR_PAD_LEFT)."/".str_pad($x_vencimiento_num, 2, "0", STR_PAD_LEFT);
		

// $x_respuesta11.= '<tr'. $sItemRowClass.$sListTrJs .'>';
    $x_respuesta11.= '<tr class="ewTableHeader">';
      
$x_respuesta11.= '<td> </td>

     
     
      <td align="right"> <span class="txt_datos_azul">'. $x_vencimiento_num.' </span></td>
      
     
      <td align="center">
        <span class="txt_datos_azul">';
        
if ((!is_null($x_vencimiento_status_id)) && ($x_vencimiento_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `vencimiento_status`";
	$sTmp = $x_vencimiento_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `vencimiento_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_vencimiento_status_id = $x_vencimiento_status_id; // Backup Original Value
$x_vencimiento_status_id = $sTmp;

        $x_respuesta11 .= $x_vencimiento_status_id;         
         $x_vencimiento_status_id = $ox_vencimiento_status_id; // Restore Original Value ?     
       $x_respuesta11 .=' </span></td>
      
      <td align="center"> <span class="txt_datos_azul">'. FormatDateTime($x_fecha_vencimiento,7).'</span></td>
      <td align="center"> <span class="txt_datos_azul">'. FormatDateTime($x_fecha_pago,7).'</span></td>
      <td align="right"> <span class="txt_datos_azul">'.  FormatNumber($x_saldo,2,0,0,1).'</span></td>
      
      <td align="right"> <span class="txt_datos_azul">'. FormatNumber($x_importe,2,0,0,1).' </span></td>
     
      <td align="right"> <span class="txt_datos_azul">'.FormatNumber($x_interes,2,0,0,1).'</span></td>
      <td align="right"><span class="txt_datos_azul">'.FormatNumber($x_iva,2,0,0,1).'</span></td>
      
      <td align="right"> <span class="txt_datos_azul">'. FormatNumber($x_interes_moratorio,2,0,0,1).'</span></td>
      <td align="right"><span class="txt_datos_azul">'. FormatNumber($x_iva_mor,2,0,0,1).'</span></td>
      <td align="right"> <span class="txt_datos_azul">'. FormatNumber($x_total,2,0,0,1).'</span></td>
    </tr>';
    
$x_saldo = $x_saldo - $x_importe;
}

$sqlSumaPenalizaciones = "SELECT SUM(interes) as interes_pena, SUM(iva) as iva_pena, SUM(interes_moratorio) as mora_pena , SUM(iva_mor) AS iva_mor_pena 	from vencimiento where credito_id = $x_credito_id and vencimiento_num > 2000 and  vencimiento_num < 3000 ";
 $rsSumaPenalizaciones = phpmkr_query($sqlSumaPenalizaciones,$conn) or die("error".phpmkr_error().$sqlSumaPenalizaciones);
 $rowPena = phpmkr_fetch_array($rsSumaPenalizaciones);
 
 $sqlSumaPenalizaciones = "SELECT SUM(interes) as interes_comi, SUM(iva) as iva_comi, SUM(interes_moratorio) as mora_comi , SUM(iva_mor) AS iva_mor_comi 	from vencimiento where credito_id = $x_credito_id and vencimiento_num > 3000 ";
 $rsSumaComi = phpmkr_query($sqlSumaPenalizaciones,$conn) or die("error".phpmkr_error().$sqlSumaPenalizaciones);
 $rowComi = phpmkr_fetch_array($rsSumaComi);
 //$x_comi = mysql_num_rows($rsSumaPenalizaciones);
 
 // seleccionar lo pagado de penalizacion
 
 $sqlSumaPenalizaciones = "SELECT SUM(interes) as interes_pena, SUM(iva) as iva_pena, SUM(interes_moratorio) as mora_pena , SUM(iva_mor) AS iva_mor_pena 	from vencimiento where vencimiento.vencimiento_status_id = 2 and credito_id = $x_credito_id and vencimiento_num > 2000 and vencimiento_num < 3000 ";
 $rsSumaPenalizaciones = phpmkr_query($sqlSumaPenalizaciones,$conn) or die("error".phpmkr_error().$sqlSumaPenalizaciones);
 $rowPenaPagado = phpmkr_fetch_array($rsSumaPenalizaciones);
 $x_inter_pena_pagado =  $rowPenaPagado["mora_pena"];
 $x_iva_pena_pagado =  $rowPenaPagado["iva_mor_pena"];
 
 // lo pagado de comision
 
  $sqlSumaPenalizaciones = "SELECT SUM(interes) as interes_pena, SUM(iva) as iva_pena, SUM(interes_moratorio) as mora_pena , SUM(iva_mor) AS iva_mor_pena 	from vencimiento where vencimiento.vencimiento_status_id = 2 and credito_id = $x_credito_id and vencimiento_num > 3000 ";
 $rsSumaPenalizaciones = phpmkr_query($sqlSumaPenalizaciones,$conn) or die("error".phpmkr_error().$sqlSumaPenalizaciones);
 $rowPenaPagado = phpmkr_fetch_array($rsSumaPenalizaciones);
 $x_inter_comi_pagado =  $rowPenaPagado["mora_pena"];
 $x_iva_comi_pagado =  $rowPenaPagado["iva_mor_pena"];

 
 // seleccionar lo vencido
 
 
 $sqlSumaPenalizaciones = "SELECT SUM(interes) as interes_pena, SUM(iva) as iva_pena, SUM(interes_moratorio) as mora_pena , SUM(iva_mor) AS iva_mor_pena,  SUM(total_venc) as TT_P	from vencimiento where vencimiento.vencimiento_status_id = 3 and credito_id = $x_credito_id and vencimiento_num > 2000 and vencimiento_num < 3000 ";
 $rsSumaPenalizaciones = phpmkr_query($sqlSumaPenalizaciones,$conn) or die("error".phpmkr_error().$sqlSumaPenalizaciones);
$rowPenaVencido = phpmkr_fetch_array($rsSumaPenalizaciones);
 $x_inter_pena_vencido =  $rowPenaVencido["mora_pena"];
 $x_iva_pena_vencido =  $rowPenaVencido["iva_mor_pena"];
 $x_total_pena_vencido = $rowPenaVencido["TT_P"];
 // lo vencido de comisiones
 
  
 $sqlSumaPenalizaciones = "SELECT SUM(interes) as interes_pena, SUM(iva) as iva_pena, SUM(interes_moratorio) as mora_pena , SUM(iva_mor) AS iva_mor_pena , SUM(total_venc) as TT_P 	from vencimiento where vencimiento.vencimiento_status_id = 3 and credito_id = $x_credito_id and vencimiento_num > 3000 ";
 $rsSumaPenalizaciones = phpmkr_query($sqlSumaPenalizaciones,$conn) or die("error".phpmkr_error().$sqlSumaPenalizaciones);
$rowPenaVencido = phpmkr_fetch_array($rsSumaPenalizaciones);
 $x_inter_comi_vencido =  $rowPenaVencido["mora_pena"];
 $x_iva_comi_vencido =  $rowPenaVencido["iva_mor_pena"];
 $x_total_comi_vencido = $rowPenaVencido["TT_P"];
 
 //pendiente
 
 $sqlSumaPenalizaciones = "SELECT SUM(interes) as interes_pena, SUM(iva) as iva_pena, SUM(interes_moratorio) as mora_pena , SUM(iva_mor) AS iva_mor_pena , SUM(total_venc) as TT_P	from vencimiento where vencimiento.vencimiento_status_id = 1 and credito_id = $x_credito_id and vencimiento_num > 2000  and vencimiento_num < 3000";
 $rsSumaPenalizaciones = phpmkr_query($sqlSumaPenalizaciones,$conn) or die("error".phpmkr_error().$sqlSumaPenalizaciones);
 $rowPenaPendiente = phpmkr_fetch_array($rsSumaPenalizaciones);
 
 $x_inter_pena_pendiente =  $rowPenaPendiente["mora_pena"];
 $x_iva_pena_pendiente =   $rowPenaPendiente["iva_mor_pena"];
 $x_total_pena_pendiente = $rowPenaPendiente["TT_P"];
 
  $sqlSumaPenalizaciones = "SELECT SUM(interes) as interes_pena, SUM(iva) as iva_pena, SUM(interes_moratorio) as mora_pena , SUM(iva_mor) AS iva_mor_pena , SUM(total_venc) as TT_P	from vencimiento where vencimiento.vencimiento_status_id = 1 and credito_id = $x_credito_id and vencimiento_num > 3000  ";
 $rsSumaPenalizaciones = phpmkr_query($sqlSumaPenalizaciones,$conn) or die("error".phpmkr_error().$sqlSumaPenalizaciones);
 $rowPenaPendiente = phpmkr_fetch_array($rsSumaPenalizaciones);
 
 $x_inter_comi_pendiente =  $rowPenaPendiente["mora_pena"];
 $x_iva_comi_pendiente =   $rowPenaPendiente["iva_mor_pena"];
 $x_total_comi_pendiente = $rowPenaPendiente["TT_P"];
 # echo "pendiente ". $x_inter_pena_pendiente ." ". $x_iva_pena_pendiente."<br>";
 #echo "sql :". $sqlSumaPenalizaciones;
 
 $x_saldo_pendiente_mora = $x_total_pena_pendiente +  $x_total_comi_pendiente;
 $x_saldo_vencido_mora = $x_total_comi_vencido +  $x_total_pena_vencido;
 $x_saldo_deudor_t_m =   $x_saldo_pendiente_mora +  $x_saldo_vencido_mora ;
 $x_saldo_deudor_inter_m = $x_inter_comi_pendiente +  $x_inter_pena_pendiente +$x_inter_comi_vencido + $x_inter_pena_vencido ;
 $x_saldo_deudor_iva_m =  $x_iva_comi_pendiente + $x_iva_pena_pendiente + $x_iva_pena_vencido + $x_iva_pena_vencido ;
 //echo "mora ".$rowPena["mora_pena"]. "iva ".$rowPena["iva_mor_pena"]."<br>";
 
if($rowPena["mora_pena"] > 0){
$x_respuesta16.= '<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td colspan="7" align="left"><span class="txt_datos_azul">Interes moratorio</span></td>
<td align="right"><span class="txt_datos_azul">'. FormatNumber($rowPena["mora_pena"],2,0,0,1).'</span></td>
<td align="right"><span class="txt_datos_azul">'. FormatNumber($rowPena["iva_mor_pena"],2,0,0,1).'</span></td>
<td align="right"><span class="txt_datos_azul">'. FormatNumber(($rowPena["iva_mor_pena"] + $rowPena["mora_pena"]),2,0,0,1).'
</span></td>
	</tr>';
}
	if($rowComi["mora_comi"] > 0){
$x_respuesta16.= '<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td colspan="7" align="left"><span class="txt_datos_azul">Comision Cobranza</span></td>
<td align="right"><span class="txt_datos_azul">'. FormatNumber($rowComi["mora_comi"],2,0,0,1).'</span></td>
<td align="right"><span class="txt_datos_azul">'. FormatNumber($rowComi["iva_mor_comi"],2,0,0,1).'</span></td>
<td align="right"><span class="txt_datos_azul">'. FormatNumber(($rowComi["iva_mor_comi"] + $rowComi["mora_comi"]),2,0,0,1).'
</span></td>
	</tr>';	
	}

	


$x_respuesta16.='<tr>
      <td >&nbsp;</td>
      <td >&nbsp;</td>
      <td >&nbsp;</td>
      <td ><span>&nbsp;</span></td>
      <td ><span>&nbsp;</span></td>
      <td ><span>&nbsp;</span></td>
      <td ></td>
      <td ></td>
      <td ></td>
      <td ></td>
      <td ></td>
      <td ></td>
    </tr>
    <tr>
      <td style=" border-top: solid 1px #000">&nbsp;</td>
      <td style=" border-top: solid 1px #000">&nbsp;</td>
      <td style=" border-top: solid 1px #000">&nbsp;</td>
      <td style=" border-top: solid 1px #000" align="right"><span>&nbsp;</span></td>
      <td style=" border-top: solid 1px #000"><span>&nbsp;</span></td>
      <td style=" border-top: solid 1px #000" align="right"><span>&nbsp;</span></td>
      <td style=" border-top: solid 1px #000" align="right"> <span class="txt_datos_azul"><b> '. FormatNumber($x_total_pagos,2,0,0,1).'</b></span></td>
      <td style=" border-top: solid 1px #000" align="right"> <span class="txt_datos_azul"><b>'.FormatNumber($x_total_interes,2,0,0,1).'</b></span></td>
      <td align="right" class="txt_datos_azul" style=" border-top: solid 1px #000"><b>'. FormatNumber($x_total_iva,2,0,0,1).'</b></td>
      <td style=" border-top: solid 1px #000" align="right"> <span class="txt_datos_azul"><b>'. FormatNumber(($x_total_moratorios +  $rowPena["mora_pena"] + $rowComi["mora_comi"]),2,0,0,1).'</b></span></td>
      <td align="right" class="txt_datos_azul" style=" border-top: solid 1px #000"><b>'.FormatNumber(($x_total_iva_moratorios+ $rowPena["iva_mor_pena"] + $rowComi["iva_mor_comi"]),2,0,0,1).'</b></td>
      <td style=" border-top: solid 1px #000" align="right"> <span class="txt_datos_azul"><b>'.FormatNumber(($x_total_total +  $rowPena["mora_pena"] +$rowPena["iva_mor_pena"] + $rowComi["mora_comi"] + $rowComi["iva_mor_comi"]  ),2,0,0,1).'</b></span></td>
    </tr>
    
    <tr >
      <td  colspan="5" align="right" class="txt_negro_medio">SALDO DEUDOR:</td>
      <td  align="right"><span>&nbsp;</span></td>
      <td  align="right"> <span class="txt_datos_azul"><b> '.FormatNumber($x_total_pagos_d,2,0,0,1).'</b></span></td>
      <td align="right"> <span class="txt_datos_azul"><b> '. FormatNumber($x_total_interes_d,2,0,0,1).'</b></span></td>
      <td  align="right" class="txt_datos_azul"><b>'. FormatNumber($x_total_iva_d,2,0,0,1).'</b></td>
      <td  align="right"> <span class="txt_datos_azul"><b> '.FormatNumber(($x_total_moratorios_d + $x_saldo_deudor_inter_m ),2,0,0,1).'</b></span></td>
      <td  align="right" class="txt_datos_azul"><b>'.FormatNumber(($x_total_iva_moratorios_d + $x_saldo_deudor_iva_m),2,0,0,1).'</b></td>
      <td  align="right"> <span class="txt_datos_azul"><b>'.FormatNumber(($x_total_total_d +  $x_saldo_deudor_t_m),2,0,0,1).'</b></span></td>
    </tr>
    <tr>
  <td colspan="5" align="right" class="txt_negro_medio">SALDO VENCIDO:</td>
  <td align="right">&nbsp;</td>
  <td align="right"><span class="txt_datos_azul"><b>'.FormatNumber($x_total_pagos_v,2,0,0,1).'</b></span></td>
  <td align="right"><span class="txt_datos_azul"><b>'.FormatNumber($x_total_interes_v,2,0,0,1).'</b></span></td>
  <td align="right"><span class="txt_datos_azul"><b>'.FormatNumber($x_total_iva_v,2,0,0,1).'</b></span></td>
  <td align="right"><span class="txt_datos_azul"><b>'.FormatNumber(($x_total_moratorios_v + $x_inter_pena_vencido + $x_inter_comi_vencido ),2,0,0,1).'</b></span></td>
  <td align="right"><span class="txt_datos_azul"><b>'.FormatNumber(($x_total_iva_mor_v + $x_iva_pena_vencido + $x_iva_comi_vencido),2,0,0,1).'</b></span></td>
  <td align="right"><span class="txt_datos_azul"><b>'.FormatNumber(($x_total_total_v + $x_inter_pena_vencido + $x_iva_pena_vencido + $x_inter_comi_vencido + $x_iva_comi_vencido),2,0,0,1).'</b></span></td>
</tr>
    <tr>
      <td colspan="5" align="right" class="txt_negro_medio">TOTAL PAGADO:</td>
      <td  align="right">&nbsp;</td>
      <td align="right" class="txt_datos_azul"><b>'.FormatNumber($x_total_pagos_a,2,0,0,1).'</b></td>
      <td align="right" class="txt_datos_azul"><b>'.FormatNumber($x_total_interes_a,2,0,0,1).'</b></td>
      <td align="right" class="txt_datos_azul"><b>'.FormatNumber($x_total_iva_a,2,0,0,1).'</b></td>
      <td align="right" class="txt_datos_azul"><b>'.FormatNumber(($x_total_moratorios_a + $x_inter_pena_pagado + $x_inter_comi_pagado),2,0,0,1).'</b></td>
      <td align="right" class="txt_datos_azul"><b>'.FormatNumber(($x_total_iva_moratorios_a +$x_iva_pena_pagado+ $x_iva_comi_pagado ),2,0,0,1).'</b></td>
      <td align="right" class="txt_datos_azul"><b>'.FormatNumber(($x_total_total_a + $x_inter_pena_pagado +$x_iva_pena_pagado + $x_inter_comi_pagado + $x_iva_comi_pagado),2,0,0,1).'</b></td>
    </tr>
  </table></td>
  </tr>
  <tr><td colspan="5">&nbsp;</td></tr>
  <tr><td colspan="5">&nbsp;</td></tr>
  <tr>
    <td colspan="5">
  <table width="700"  align="center" cellpadding="0" cellspacing="0" border="0"> 
  
   ';
    /*<tr class="ewTableHeader">
      <td width="2%"><span> &nbsp; </span></td>
      <td width="5%" align="center" valign="middle" class="txt_negro_medio"><span> No </span></td>
      <td width="9%" align="center" valign="middle" class="txt_negro_medio"> Status </td>
      <td width="8%" align="center" valign="middle" class="txt_negro_medio"> Fecha </td>
      <td width="14%" align="center" valign="middle" class="txt_negro_medio"> Fecha de Pago </td>
      <td width="10%" align="center" valign="middle" class="txt_negro_medio"> Capital </td>
      <td width="9%" align="center" valign="middle" class="txt_negro_medio"> Importe </td>
      <td width="9%" align="center" valign="middle" class="txt_negro_medio"> Inter&eacute;s </td>
      <td width="12%" align="center" valign="middle" class="txt_negro_medio">IVA</td>
      <td width="12%" align="center" valign="middle" class="txt_negro_medio"> Moratorios </td>
      <td width="7%" align="center" valign="middle" class="txt_negro_medio">IVA M.</td>
      <td width="7%" align="center" valign="middle" class="txt_negro_medio"> Total </td>
    </tr>*/
    
$sqlFP = 	"SELECT forma_pago_id,  penalizacion, garantia_liquida FROM credito WHERE credito_id =  $x_credito_id";
$rsFP = phpmkr_query($sqlFP, $conn) or die ("Error al selccioanl la forma de pago". phpmkr_error()."sql:".$sqlFP);
$rowFP = phpmkr_fetch_array($rsFP);
$x_forma_pago_id = $rowFP["forma_pago_id"];
$x_penalizacion = $rowFP["penalizacion"];
$x_garantia_liquida = $rowFP["garantia_liquida"];	
if ((!empty($x_penalizacion) && $x_penalizacion > 0 ) || true){ 
$x_respuesta16 .='
<table id="ewlistmain" class="ewTable" align="center">
  <tr class="ewTableHeader"><td colspan="14" class="txt_negro_medio"><center><strong>
  <h3>INTERESES MORATORIOS</h3></strong></center> </td></tr>
	<!-- Table header -->
	<tr >
<td width="37"><span>
&nbsp;
</span></td>
		<td width="71" valign="middle" class="txt_negro_medio">No</td>
		<td width="105" valign="middle" class="txt_negro_medio"><span>Status</span></td>
		<td width="104" valign="middle" class="txt_negro_medio"><span>Fecha</span></td>   
		<td width="64" valign="middle" class="txt_negro_medio"><span>Fecha de Pago</span></td>        
        <td width="9" valign="middle" class="txt_negro_medio">&nbsp;</td>				        
	  <td width="57" valign="middle" class="txt_negro_medio"><span>Ref. de Pago</span></td>				                
		<td width="39" valign="middle" class="txt_negro_medio"><span>Capital</span></td>				
		<td width="118" valign="middle" class="txt_negro_medio">Importe</td>
		<td width="111" valign="middle" class="txt_negro_medio">Inter&eacute;s</td>
		<td width="27" valign="middle" class="txt_negro_medio">IVA</td>
		<td width="130" valign="middle" class="txt_negro_medio"><span>Moratorio</span></td>
		<td width="32" valign="middle" class="txt_negro_medio">IVA M.</td>
		<td width="36" valign="middle" class="txt_negro_medio"><span>Total</span></td>						
	</tr>';


$sqlPenalizaciones = "SELECT * FROM vencimiento WHERE (vencimiento.credito_id = $x_credito_id) AND (vencimiento.vencimiento_num > 2000 and vencimiento.vencimiento_num < 3000) order by vencimiento_num asc  ";

//Secho $sqlPenalizaciones;
$rsP = phpmkr_query($sqlPenalizaciones, $conn) or die ("Error".phpmkr_error()."sql".$sqlPenalizaciones);
while (($row = @phpmkr_fetch_array($rsP))) {
	$nRecCount = $nRecCount + 1;
	if ($nRecCount >= $nStartRec) {
		$nRecActual++;
//echo "entra en penaliazciones";
		// Set row color
		$sItemRowClass = " class=\"ewTableRow\"";
		$sListTrJs = " onmouseover='ew_mouseover(this);' onmouseout='ew_mouseout(this);' onclick='ew_click(this);'";

		// Display alternate color for rows
		if ($nRecCount % 2 <> 1) {
			$sItemRowClass = " class=\"ewTableAltRow\"";
		}
		$x_vencimiento_id = $row["vencimiento_id"];
		//echo "vencimeinto id". $x_vencimiento_id;
		$x_vencimiento_num = $row["vencimiento_num"];		
		$x_credito_id = $row["credito_id"];
		$x_vencimiento_status_id = $row["vencimiento_status_id"];


		
		$x_fecha_vencimiento = $row["fecha_vencimiento"];
		$x_importe = $row["importe"];
		$x_interes = $row["interes"];
		$x_iva = $row["iva"];		
		$x_iva_mor = $row["iva_mor"];	
		$x_fecha_remanente = $row["fecha_genera_remanente"];
		if(empty($x_iva)){
			$x_iva = 0;
		}		
		if(empty($x_iva_mor)){
			$x_iva_mor = 0;
		}		
		
		$x_interes_moratorio = $row["interes_moratorio"];
		//Secho $x_interes_moratorio."<br>";
		
		$x_total = $x_importe + $x_interes + $x_interes_moratorio + $x_iva + $x_iva_mor;
 //echo "total venci".$x_total."<br>";

		$x_total_pagos = $x_total_pagos + $x_importe;
		$x_total_interes = $x_total_interes + $x_interes;
		$x_total_iva = $x_total_iva + $x_iva;		
		$x_total_iva_mor = $x_total_iva_mor + $x_iva_mor;				
		$x_total_moratorios = $x_total_moratorios + $x_interes_moratorio;
		$x_total_total = $x_total_total + $x_total;
		
		if(($x_vencimiento_status_id == 2) || ($x_vencimiento_status_id == 5)){
		
			$sSqlWrk = "SELECT fecha_pago, referencia_pago_2 FROM recibo join recibo_vencimiento on recibo_vencimiento.recibo_id = recibo.recibo_id where recibo_vencimiento.vencimiento_id = $x_vencimiento_id and recibo.recibo_status_id = 1";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$rowwrk = phpmkr_fetch_array($rswrk);
			$x_fecha_pago = $rowwrk["fecha_pago"];
			$x_referencia_pago2 = $rowwrk["referencia_pago_2"];			

			@phpmkr_free_result($rswrk);

			$x_total_pagos_a = $x_total_pagos_a + $x_importe;
			$x_total_interes_a = $x_total_interes_a + $x_interes;
			$x_total_iva_a = $x_total_iva_a + $x_iva;			
			$x_total_iva_mor_a = $x_total_iva_mor_a + $x_iva_mor;						
			$x_total_moratorios_a = $x_total_moratorios_a + $x_interes_moratorio;
			$x_total_total_a = $x_total_total_a + $x_total;
			

		}else{
			$x_fecha_pago  = "";
			$x_referencia_pago2 = "";						

			$x_total_pagos_d = $x_total_pagos_d + $x_importe;
			$x_total_interes_d = $x_total_interes_d + $x_interes;
			$x_total_iva_d = $x_total_iva_d + $x_iva;			
			$x_total_iva_mor_d = $x_total_iva_mor_d + $x_iva_mor;						
			$x_total_moratorios_d = $x_total_moratorios_d + $x_interes_moratorio;
			$x_total_total_d = $x_total_total_d + $x_total;
			
			if($x_vencimiento_status_id == 3){
				$x_total_pagos_v = $x_total_pagos_v + $x_importe;
				$x_total_interes_v = $x_total_interes_v + $x_interes;
				$x_total_iva_v = $x_total_iva_v + $x_iva;			
				$x_total_iva_mor_v = $x_total_iva_mor_v + $x_iva_mor;						
				$x_total_moratorios_v = $x_total_moratorios_v + $x_interes_moratorio;
				$x_total_total_v = $x_total_total_v + $x_total;
			}
			
		}
		

		

		$x_ref_loc = str_pad($x_vencimiento_id, 5, "0", STR_PAD_LEFT)."/".str_pad($x_vencimiento_num, 2, "0", STR_PAD_LEFT);
		
$x_respuesta16 .= "
	
	<tr><td><span class=\"phpmaker\"></span></td>
		<!-- vencimiento_id -->
		<td align=\"right\" class=\"txt_datos_azul\"><span> ".$x_vencimiento_num."</span></td>
		<td align=\"right\" class=\"txt_datos_azul\"><span>";
if ((!is_null($x_vencimiento_status_id)) && ($x_vencimiento_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `vencimiento_status`";
	$sTmp = $x_vencimiento_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `vencimiento_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_vencimiento_status_id = $x_vencimiento_status_id; // Backup Original Value
$x_vencimiento_status_id = $sTmp;
if($x_vencimiento_status_id == "MORATORIOS"){
	$x_vencimiento_status_id = "PENDIENTE";
	}

$x_respuesta16 .= $x_vencimiento_status_id;
 $x_vencimiento_status_id = $ox_vencimiento_status_id; // Restore Original Value 

$x_respuesta16 .= "</span></td>
		<!-- fecha_vencimiento -->
		<td align=\"right\" class=\"txt_datos_azul\">".FormatDateTime($x_fecha_vencimiento,7)."</td>
		<td align=\"right\" class=\"txt_datos_azul\">".FormatDateTime($x_fecha_pago,7)."</td>
<td align=\"center\">&nbsp;</td> <td align=\"right\" class=\"txt_datos_azul\">".$x_referencia_pago2."</td>
		<td align=\"right\">&nbsp;</td>
		<!-- importe -->
		<td align=\"right\">&nbsp;</td>
		<!-- interes -->
		<td align=\"right\"> </td>
		<td align=\"right\">&nbsp;</td>
		<!-- interes_moratorio -->
		<td align=\"right\" class=\"txt_datos_azul \"><span>".FormatNumber($x_interes_moratorio,2,0,0,1)." </span></td>
		<td align=\"right\" class=\"txt_datos_azul \">".FormatNumber($x_iva_mor,2,0,0,1)."</td>
		<td align=\"right\" class=\"txt_datos_azul \"><span>".FormatNumber($x_total,2,0,0,1)."</span></td>
	</tr>";

$x_saldo = $x_saldo - $x_importe;
	}
}

}// termina penalizacion //


// comison de cobranza

$sqlComsion = "SELECT vencimiento.*, credito.fecha_genera_comision as fecha_comision FROM vencimiento join credito on credito.credito_id = vencimiento.credito_id WHERE (vencimiento.credito_id = $x_credito_id) AND (vencimiento.vencimiento_num >= 3000) ";
$rsc = phpmkr_query($sqlComsion, $conn) or die ("Error".phpmkr_error()."sql".$sqlComsion);
$x_no_comisiones = mysql_num_rows($rsc);

if($x_no_comisiones > 0  || true){

$x_respuesta16 .= "</p>
<table id=\"ewlistmain\" class=\"ewTable\" align=\"center\">
	<!-- Table header -->
	<tr class=\"ewTableHeader\">
<td colspan=\"5\" class=\"txt_negro_medio\"><center><strong>
<h3>COMISI&Oacute;N POR GASTOS DE COBRANZA LEGAL</h3></strong></center>
 </td>
	</tr>
    <tr>
<td width=\"53\"> </td>
<td width=\"176\" class=\"txt_negro_medio\">Se gener&oacute;</td>		
		<td width=\"177\"class=\"txt_negro_medio\">Monto</td>		
		<td width=\"244\" align=\"left\" class=\"txt_negro_medio\">Status</td>
	    <td width=\"326\" align=\"center\" class=\"txt_negro_medio\">Fecha limite de pago</td>
		</tr>";




$sqlComsion = "SELECT vencimiento.*, credito.fecha_genera_comision as fecha_comision FROM vencimiento join credito on credito.credito_id = vencimiento.credito_id WHERE (vencimiento.credito_id = $x_credito_id) AND (vencimiento.vencimiento_num >= 3000) ";
$rsc = phpmkr_query($sqlComsion, $conn) or die ("Error".phpmkr_error()."sql".$sqlComsion);
//echo "sql :".$sqlComsion;
while (($rowc = @phpmkr_fetch_array($rsc))) {
	$nRecCount = $nRecCount + 1;
	if ($nRecCount >= $nStartRec) {
		$nRecActual++;
		// Set row color
		$sItemRowClass = " class=\"ewTableRow\"";
		$sListTrJs = " onmouseover='ew_mouseover(this);' onmouseout='ew_mouseout(this);' onclick='ew_click(this);'";
		//echo "vencimeinto id". $x_vencimiento_id;
		$x_vencimiento_idc = $rowc["vencimiento_id"];
		$x_importec = $rowc["importe"];
		
		$x_interes_moratorio = $rowc["interes_moratorio"];
		$x_iva_mor = $rowc["iva_mor"];
		$x_importec = $x_interes_moratorio + $x_iva_mor;
		#echo "vencimeinto id". $x_vencimiento_idc;
		$x_vencimiento_numc = $row["vencimiento_num"];		
		$x_credito_idc = $rowc["credito_id"];
		$x_vencimiento_status_idc = $rowc["vencimiento_status_id"];
		$x_fecha_vencimientoc = $rowc["fecha_vencimiento"];
		$x_fecha_comision = $rowc["fecha_comision"];
		//$x_importec = $rowc["importe"];
		$x_interesc = $rowc["interes"];
		$x_ivac = $rowc["iva"];		
		$x_iva_morc = $rowc["iva_mor"];	
		$x_fecha_remanentec = $rowc["fecha_genera_remanente"];
		if(empty($x_iva)){
			$x_iva = 0;
		}		
		if(empty($x_iva_mor)){
			$x_iva_mor = 0;
		}		
		$x_ref_loc = str_pad($x_vencimiento_idc, 5, "0", STR_PAD_LEFT)."/".str_pad($x_vencimiento_numc, 2, "0", STR_PAD_LEFT);

		
?>
	<!-- Table body -->
	
<?php
$x_saldo = $x_saldo - $x_importe;
	}

$x_respuesta16 .= "
<tr>
<td>";
$x_respuesta16 .= "</span></td>
<td  class=\"txt_datos_azul\">". FormatDateTime($x_fecha_comision,7)."</td>
<td  class=\"txt_datos_azul\">". FormatNumber($x_importec,2,0,0,1)."</td>
<td  class=\"txt_datos_azul\">";
if ((!is_null($x_vencimiento_status_idc)) && ($x_vencimiento_status_idc <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `vencimiento_status`";
	$sTmp = $x_vencimiento_status_idc;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `vencimiento_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_vencimiento_status_id = $x_vencimiento_status_id; // Backup Original Value
$x_vencimiento_status_id = $sTmp;
if($x_vencimiento_status_id == "COMISION"){
	$x_vencimiento_status_id = "PENDIENTE";
	}
$x_respuesta16 .= $x_vencimiento_status_id;
$x_vencimiento_status_id = $ox_vencimiento_status_id; // Restore Original Value 

$x_respuesta16 .="</td><td   align=\"center\" class=\"txt_datos_azul\"<span>".FormatDateTime($x_fecha_vencimientoc,7)."</span></td>
</tr></table> ";
 }
}// fin comision


if($x_garantia_liquida == 1){
$sqlGrantia = "SELECT garantia_liquida.*, garantia_liquida_status.descripcion, garantia_liquida_status.detalle FROM garantia_liquida JOIN garantia_liquida_status ON garantia_liquida_status.garantia_liquida_status_id = garantia_liquida.status  WHERE (credito_id = $x_credito_id)   ";
$rs = phpmkr_query($sqlGrantia, $conn) or die ("Error".phpmkr_error()."sql".$sqlPenalizaciones);
while (($row = @phpmkr_fetch_array($rs))) {
	$nRecCount = $nRecCount + 1;
	if ($nRecCount >= $nStartRec) {
		$nRecActual++;

		// Set row color
		$sItemRowClass = " class=\"ewTableRow\"";
		$sListTrJs = " onmouseover='ew_mouseover(this);' onmouseout='ew_mouseout(this);' onclick='ew_click(this);'";

		// Display alternate color for rows
		if ($nRecCount % 2 <> 1) {
			$sItemRowClass = " class=\"ewTableAltRow\"";
		}
		$x_garantia_liquida_id = $row["garantia_liquida_id"];
		//echo "vencimeinto id". $x_vencimiento_id;
		$x_monto = $row["monto"];		
		$x_fecha = $row["fecha"];
		$x_status = $row["descripcion"];
		$x_detalle = $row["detalle"];

	}
}

$x_respuesta16.= "<table id=\"ewlistmain\" class=\"ewTable\" align=\"center\">
	
	<tr class=\"ewTableHeader\">
<td colspan=\"4\" class=\"txt_negro_medio\"><center><strong>
<h3>GARANT&Iacute;A L&Iacute;QUIDA</h3></strong></center>
 </td>
	</tr>
    <tr>
<!--
<td><span class=\"phpmaker\"></span></td>
--->
<td width=\"114\" class=\"txt_negro_medio\">Fecha en que se gener&oacute;</td>
		<!-- vencimiento_id -->		<!-- credito_id -->
		<!-- vencimiento_status_id -->
		<td width=\"169\" class=\"txt_negro_medio\">Monto</td>
		<!-- fecha_vencimiento -->
		<td width=\"236\" align=\"left\" class=\"txt_negro_medio\">Status</td>
	  <td align=\"center\" class=\"txt_negro_medio\">Detalle</td>
	</tr>";	
$x_respuesta16.="	<tr>
<td class=\"txt_datos_azul\">".FormatDateTime($x_fecha,7)."</td>
<td class=\"txt_datos_azul\">".FormatNumber($x_monto,2,0,0,1)."</td>
<td class=\"txt_datos_azul\">". $x_status."</td>
<td align=\"center\" class=\"txt_datos_azul\">". $x_detalle."</td>
</tr>
</table>";
	
}// tienen garantia liquida imprime la tabla



// seleccionamos si tiene garantia liquida o no
$sqlGarantiaLiquida = "SELECT garantia_liquida, num_pagos,forma_pago_id FROM credito WHERE credito_id = $x_credito_id ";
$rsGarantiaLiquida = phpmkr_query($sqlGarantiaLiquida,$conn) or die ("Erro al seleccionar la garatia liquida del credito".phpmkr_error()."sql:".$sqlGarantiaLiquida);
$rowGarantiaLiquida = phpmkr_fetch_array($rsGarantiaLiquida);
$x_tiene_garantia_liquida = $rowGarantiaLiquida["garantia_liquida"];
$x_numero_de_pagos = $rowGarantiaLiquida["num_pagos"];
$x_forma_pago = $rowGarantiaLiquida["forma_pago_id"];
$x_numero_1 = 0;
$x_numero_2 = 0;
$x_numero_3 = 0;
if ($x_tiene_garantia_liquida == 1){
	// si tiene garantia liquda
	if($x_forma_pago == 1){
		//semanla
		if($x_numero_de_pagos == 40){
			$x_numero_1 = 40;
			$x_numero_2 = 39;
			$x_numero_3 = 38;
			}else{
				$x_numero_1 = 20;
				$x_numero_2 = 19;
				$x_numero_3 = 0;
				}
		}else if($x_forma_pago == 2){
			//catorcenal
			if($x_numero_de_pagos == 24){
				$x_numero_1 = 24;
				$x_numero_2 = 23;
				$x_numero_3 = 0;				
				}else{
					$x_numero_1 = 12;
					$x_numero_2 = 0;
					$x_numero_3 = 0;					
					}		
			
			}else if($x_forma_pago == 3){
				//mensual
				$x_numero_1 = 12;
				$x_numero_2 = 0;
				$x_numero_3 = 0;
				
				}else if($x_forma_pago == 4){
					//quincenal
					if($x_numero_de_pagos == 24){
				$x_numero_1 = 24;
				$x_numero_2 = 23;
				$x_numero_3 = 0;				
				}else{
					$x_numero_1 = 12;
					$x_numero_2 = 0;
					$x_numero_3 = 0;					
					}
					
					}
	
	}

$sSqlWrk = "SELECT importe FROM credito where credito_id = $x_solcre_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
$x_saldo = $datawrk["importe"];
@phpmkr_free_result($rswrk);

$x_total = 0;
$x_total_pagos = 0;
$x_total_interes = 0;
$x_total_iva = 0;
$x_total_moratorios = 0;
$x_total_iva_moratorios = 0;
$x_total_total = 0;

$x_total_d = 0;
$x_total_pagos_d = 0;
$x_total_interes_d = 0;

$x_total_iva_d = 0;
$x_total_moratorios_d = 0;
$x_total_iva_moratorios_d = 0;
$x_total_total_d = 0;

//NUEVO
$x_total_pagos_v = 0;
$x_total_interes_v = 0;
$x_total_iva_v = 0;
$x_total_iva_mor_v = 0;
$x_total_moratorios_v = 0;
$x_total_total_v = 0;


$x_total_a = 0;
$x_total_pagos_a = 0;
$x_total_interes_a = 0;
$x_total_iva_a = 0;
$x_total_moratorios_a = 0;
$x_total_iva_moratorios_a = 0;
$x_total_total_a = 0;

    $x_respuesta21=' <tr>
      <td >&nbsp;</td>
      <td >&nbsp;</td>
      <td >&nbsp;</td>
      <td ><span>&nbsp;</span></td>
      <td ><span>&nbsp;</span></td>
      <td ><span>&nbsp;</span></td>
      <td ></td>
      <td ></td>
      <td ></td>
      <td ></td>
      <td ></td>
      <td ></td>
    </tr>
 
  </table>
  
  
  </td></tr>
     <table width="700" align="center">
<tr>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
</tr>
<tr>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
</tr>
<tr>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
</tr>
<tr>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
</tr>
 
<tr>
  <td colspan="5" class="txt_negro_medio"><h4>Solicitamos su pago a la brevedad, para mayores informes comuniquese a nuestra linea de atencion a clientes en el D.F al 51350259 en el interior de la republica a la linea sin consto 018008376133.</h4></td>
</tr>
</table>';

$x_credito_id = $x_solcre_id;
$x_today = date("Y-m-d");
$x_f_p = explode("-",$x_today);
$x_dia = $x_f_p[2];
$x_mes = $x_f_p[1];
$x_anio = $x_f_p[0];
#echo "dia ".$x_dia."mes ".$x_mes."año ".$x_anio."<br>";

// seleccionamos el ultimo dia del mes que corresponde a la fecha actual
$x_ultimo_dia_mes_f = strftime("%d", mktime(0, 0, 0, $x_mes+1, 0, $x_anio));
#echo "yultimo dia de mes". $x_ultimo_dia_mes_f."<br>";

$x_fecha_inicio_mes = $x_anio."-".$x_mes."-01";
$x_fecha_fin_mes = $x_anio."-".$x_mes."-".$x_ultimo_dia_mes_f; 
#echo "incio mes".$x_fecha_inicio_mes."<br>";
#echo "fin mes".$x_fecha_fin_mes."<br>";

$sqlSaldo = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and  fecha_vencimiento <= '$x_fecha_fin_mes' and vencimiento_status_id in (1,3,6,7) ";
$rsSaldo = phpmkr_query($sqlSaldo, $conn) or die ("error en saldos". phpmkr_error()."sql:".$sqlSaldo);
$x_total_liquida_con_interes = 0;
$x_lista_con_interes = "";
while ($rowSaldo = phpmkr_fetch_array($rsSaldo)){
	#echo "entra";
	#echo $rowSaldo["vencimiento_id"]."<br>";
	$x_total_liquida_con_interes = $x_total_liquida_con_interes + $rowSaldo["total_venc"];
	$x_lista_con_interes = $x_lista_con_interes.$rowSaldo["vencimiento_num"].", ";
	}
	$x_lista_con_interes = trim($x_lista_con_interes,", ");
$sqlSaldo2 = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and  fecha_vencimiento > '$x_fecha_fin_mes' and vencimiento_status_id in (1,3,6) ";
$rsSaldo2 = phpmkr_query($sqlSaldo2, $conn) or die ("error en saldos". phpmkr_error()."sql:".$sqlSaldo2);
$x_total_liquida_sin_interes = 0;
$x_total_interes_i = 0;
$x_total_iva_i = 0;
$x_lista_sin_interes ="";
while ($rowSaldo2 = phpmkr_fetch_array($rsSaldo2)){
	#echo "entra";
	#echo $rowSaldo["vencimiento_id"]."<br>";
	$x_total_liquida_sin_interes = $x_total_liquida_sin_interes + $rowSaldo2["total_venc"];
	$x_total_interes_i = $x_total_interes_i + $rowSaldo2["interes"];
	$x_total_iva_i = $x_total_iva_i + $rowSaldo2["iva"];
	$x_lista_sin_interes = $x_lista_sin_interes.$rowSaldo2["vencimiento_num"].", ";
	}	
$x_lista_sin_interes = trim($x_lista_sin_interes,", ");	
$sqlSaldo3 = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and  fecha_vencimiento > '$x_fecha_fin_mes' and vencimiento_status_id in (7) ";
$rsSaldo3 = phpmkr_query($sqlSaldo3, $conn) or die ("error en saldos". phpmkr_error()."sql:".$sqlSaldo3);
$x_total_penalizaciones = 0;
$x_lista_penalizaciones = "";
while ($rowSaldo3 = phpmkr_fetch_array($rsSaldo3)){
	#echo "entra";
	#echo $rowSaldo["vencimiento_id"]."<br>";
	$x_total_penalizaciones = $x_total_penalizaciones + $rowSaldo3["total_venc"];	
	$x_lista_penalizaciones = $x_lista_penalizaciones.$rowSaldo3["vencimiento_num"].", ";
	}	
	$x_lista_penalizaciones = trim($x_lista_penalizaciones, ", ");
#echo "total_liqui con interes".$x_total_liquida_con_interes."<br>";
#echo "total_pendiente".$x_total_liquida_sin_interes."<br>";
#echo "total interes pendiente". $x_total_interes_i."<br>";
#echo "total_iva_pendiente".$x_total_iva_i."<br>";

$x_total_liquida_sin_interes = $x_total_liquida_sin_interes -$x_total_interes_i -$x_total_iva_i;
#echo "total_pendiente".$x_total_liquida_sin_interes."<br>";
$x_fecha_de_vigencia = $x_fecha_fin_mes;
$x_saldo_a_liquidar = $x_total_liquida_con_interes +  $x_total_liquida_sin_interes+ $x_total_penalizaciones;


# seleccionamos la forma de pago 
$sqlFormaPago = "SELECT forma_pago_id FROM credito WHERE credito_id = $x_credito_id ";
$rsFormaPago = phpmkr_query($sqlFormaPago, $conn) or die ("Error al seleccionar".phpmkr_error()."sql:".$sqlFormaPago);
$rowFormaPago = phpmkr_fetch_array($rsFormaPago);
$x_forma_pago_id = $rowFormaPago["forma_pago_id"];


$x_respuesta22= '<br style="page-break-before:always" clear="all"> 
<table align="center" width="700" border="0"  id="ewlistmain" class="ewTable" style="font-family:Verdana, Geneva, sans-serif; font-size:12px; border-bottom-color:#CCCCCC;text-align:justify" >
<tr>
<td><strong> Comentarios Internos</strong> Credito No.<strong>'. $x_credito_num.'</strong></td>

<td>  </td>

</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>'. $x_comentario_int.'</td>
<td></td>
</tr>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td><strong> Comentarios Externos</strong></td>
<td></td>
</tr>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>'.$x_comentario_ext.'</td>';
$x_respuesta23= '<td></td>

</tr>
</table>';
 if ($x_forma_pago_id == 3){ 
 $x_respuesta24= '<table   align="center" width="700" border="0" id="ewlistmain" class="ewTable" style="font-family:Verdana, Geneva, sans-serif; font-size:12px; border-bottom-color:#CCCCCC">
  <tr>
    <td colspan="3"><strong>LIQUIDACI&Oacute;N DE CR&Eacute;DITO</strong></td>
    </tr>
  <tr>
    <td width="493" >Fecha de vigencia</td>
    <td colspan="2">'. FormatDateTime($x_fecha_de_vigencia,7).'</td>
    </tr>
  <tr>
    <td>Monto para liquidar</td>
    <td colspan="2"><strong>'.FormatNumber($x_saldo_a_liquidar,2,0,0,1).'</strong></td>
    </tr>
     <tr>
    <td>Detalle</td>
    <td width="374" >Vencimientos</td>
    <td width="119" >Saldo</td>
  </tr>
  <tr>
    <td>Vencimientos pendientes con interes</td>
    <td>'. $x_lista_con_interes.'</td>
    <td  align="right">'. FormatNumber($x_total_liquida_con_interes,2,0,0,1).'</td>
  </tr>
  <tr>
    <td>Vencimientos pendientes sin interes</td>
    <td>'. $x_lista_sin_interes.'</td>
    <td align="right">'. FormatNumber($x_total_liquida_sin_interes,2,0,0,1).'</td>
  </tr>
  <tr>
    <td>Moratorios</td>
    <td>'.$x_lista_penalizaciones.'</td>
    <td align="right">'. FormatNumber($x_total_penalizaciones,2,0,0,1).'</td>
  </tr>
  <tr>
    <td colspan="2" align="right">Total: </td>
    <td align="right"><strong>'. FormatNumber($x_saldo_a_liquidar,2,0,0,1).'</strong></td>
  </tr>
</table>';

 }
 $x_respuesta26='<br style="page-break-before:always" clear="all">';

//echo "respuesta dos -----".$x_respuesta2;
//echo "respuesta tres -----".$x_respuesta3;
//echo "respuesta cuatros -----".$x_respuesta4;



$sqlVencidos = "SELECT vencimiento_num FROM vencimiento WHERE credito_id = $x_credito_id AND vencimiento_status_id = 3 ORDER BY vencimiento_num DESC LIMIT 0,1 ";
$rsVencidos = phpmkr_query($sqlVencidos,$conn) or die ("Error al seeleccionar el ultimo encimiento vencido".phpmkr_error()."sql:". $sqlVencidos);
$rowVencidos = phpmkr_fetch_array($rsVencidos);
$x_no_referencia_ven = $rowVencidos["vencimiento_num"];
if(!empty($x_no_referencia_ven)){
	// si existe uno o mas vencimientos vencido se toma el ultimo como numero de referencia
	// y buscamos el que le sigue que estar pendiente y se quedara cmo numero de referencia
	$sqlPendiente = "SELECT vencimiento_num, fecha_vencimiento FROM vencimiento WHERE credito_id = $x_credito_id AND vencimiento_status_id = 1 AND vencimiento_num > $x_no_referencia_ven  ORDER BY vencimiento_num ASC LIMIT 0,1 ";
	$rsPendiente = phpmkr_query($sqlPendiente,$conn) or die ("Error al seeleccionar el ultimo encimiento vencido".phpmkr_error()."sql:". $sqlPendiente);
	$rowPendiente = phpmkr_fetch_array($rsPendiente);
	$x_no_referencia_ven = $rowPendiente["vencimiento_num"];
	$x_fecha_referencia = $rowPendiente["fecha_vencimiento"];	
	}else{
		//si numeor de referencia esta vencido entonces se toma como numero de referenia el primer vencimiento pendiente
			$sqlPendiente = "SELECT vencimiento_num, fecha_vencimiento FROM vencimiento WHERE credito_id = $x_credito_id AND vencimiento_status_id = 1 ORDER BY vencimiento_num ASC LIMIT 0,1 ";
	$rsPendiente = phpmkr_query($sqlPendiente,$conn) or die ("Error al seeleccionar el ultimo encimiento vencido".phpmkr_error()."sql:". $sqlPendiente);
	$rowPendiente = phpmkr_fetch_array($rsPendiente);
	$x_no_referencia_ven = $rowPendiente["vencimiento_num"];
	$x_fecha_referencia = $rowPendiente["fecha_vencimiento"];		
			
		}
$x_today = date("Y-m-d");
$x_f_p = explode("-",$x_today);
$x_dia = $x_f_p[2];
$x_mes = $x_f_p[1];
$x_anio = $x_f_p[0];
#echo "dia ".$x_dia."mes ".$x_mes."año ".$x_anio."<br>";
// seleccionamos el ultimo dia del mes que corresponde a la fecha actual
$x_ultimo_dia_mes_f = strftime("%d", mktime(0, 0, 0, $x_mes+1, 0, $x_anio));
#echo "yultimo dia de mes". $x_ultimo_dia_mes_f."<br>";
$x_fecha_inicio_mes = $x_anio."-".$x_mes."-01";
$x_fecha_fin_mes = $x_anio."-".$x_mes."-".$x_ultimo_dia_mes_f; 
#echo "incio mes".$x_fecha_inicio_mes."<br>";
#echo "fin mes".$x_fecha_fin_mes."<br>";
if(!empty($x_no_referencia_ven)){
$sqlSaldo = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and  vencimiento_num <= $x_no_referencia_ven and vencimiento_status_id in (1,3,6,7) order by  vencimiento_num asc";
$rsSaldo = phpmkr_query($sqlSaldo, $conn) or die ("error en saldos". phpmkr_error()."sql:".$sqlSaldo);
$x_total_liquida_con_interes = 0;
$x_lista_con_interes = "";
while ($rowSaldo = phpmkr_fetch_array($rsSaldo)){
	#echo "entra";
	#echo $rowSaldo["vencimiento_id"]."<br>";
	$x_total_liquida_con_interes = $x_total_liquida_con_interes + $rowSaldo["total_venc"];
	$x_lista_con_interes = $x_lista_con_interes.$rowSaldo["vencimiento_num"].", ";
	}
}
	$x_lista_con_interes = trim($x_lista_con_interes,", ");
	if(!empty($x_no_referencia_ven)){
$sqlSaldo2 = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and   vencimiento_num > $x_no_referencia_ven and vencimiento_num < 2000 and vencimiento_status_id in (1,3,6) order by  vencimiento_num asc";
$rsSaldo2 = phpmkr_query($sqlSaldo2, $conn) or die ("error en saldos". phpmkr_error()."sql:".$sqlSaldo2);
	
$x_total_liquida_sin_interes = 0;
$x_total_interes_i = 0;
$x_total_iva_i = 0;
$x_lista_sin_interes ="";
while ($rowSaldo2 = phpmkr_fetch_array($rsSaldo2)){
	#echo "entra";
	#echo $rowSaldo["vencimiento_id"]."<br>";
	$x_total_liquida_sin_interes = $x_total_liquida_sin_interes + $rowSaldo2["total_venc"];
	$x_total_interes_i = $x_total_interes_i + $rowSaldo2["interes"];
	$x_total_iva_i = $x_total_iva_i + $rowSaldo2["iva"];
	$x_lista_sin_interes = $x_lista_sin_interes.$rowSaldo2["vencimiento_num"].", ";
	}	
}
$x_lista_sin_interes = trim($x_lista_sin_interes,", ");	
if(!empty($x_no_referencia_ven)){
$sqlSaldo3 = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and   vencimiento_num > $x_no_referencia_ven and vencimiento_num >= 2000 and vencimiento_status_id in (1,3,6) order by  vencimiento_num asc";
$rsSaldo3 = phpmkr_query($sqlSaldo3, $conn) or die ("error en saldos". phpmkr_error()."sql:".$sqlSaldo3);
#echo "sal penalizaciones".$sqlSaldo3;
$x_total_penalizaciones = 0;
$x_lista_penalizaciones = "";
while ($rowSaldo3 = phpmkr_fetch_array($rsSaldo3)){
	#echo "entra";
	#echo $rowSaldo["vencimiento_id"]."<br>";
	$x_total_penalizaciones = $x_total_penalizaciones + $rowSaldo3["total_venc"];	
	$x_lista_penalizaciones = $x_lista_penalizaciones.$rowSaldo3["vencimiento_num"].", ";
	}	
}
	$x_lista_penalizaciones = trim($x_lista_penalizaciones, ", ");
#echo "total_liqui con interes".$x_total_liquida_con_interes."<br>";
#echo "total_pendiente".$x_total_liquida_sin_interes."<br>";
#echo "total interes pendiente". $x_total_interes_i."<br>";
#echo "total_iva_pendiente".$x_total_iva_i."<br>";

$x_total_liquida_sin_interes = $x_total_liquida_sin_interes -$x_total_interes_i -$x_total_iva_i;
#echo "total_pendiente".$x_total_liquida_sin_interes."<br>";
$x_fecha_de_vigencia = $x_fecha_referencia ;
$x_saldo_a_liquidar = $x_total_liquida_con_interes +  $x_total_liquida_sin_interes+ $x_total_penalizaciones;

$sqlFormaPago = "SELECT forma_pago_id, penalizacion FROM credito WHERE credito_id = $x_credito_id ";
$rsFormaPago = phpmkr_query($sqlFormaPago, $conn) or die ("Error al seleccionar".phpmkr_error()."sql:".$sqlFormaPago);
$rowFormaPago = phpmkr_fetch_array($rsFormaPago);
$x_forma_pago_id = $rowFormaPago["forma_pago_id"];
$x_penalizacion = $rowFormaPago["penalizacion"];
$x_garantia_liquida = $rowFormaPago["garantia_liquida"];



 if ($x_penalizacion > 0){ 
$x_penalizacion = '
<table align="center" width="700" border="0" id="ewlistmain" class="ewTable" style="font-family:Verdana, Geneva, sans-serif; font-size:12px">
  <tr>
    <td colspan="3"><strong>LIQUIDACI&Oacute;N DE CR&Eacute;DITO ANTICIPADA</strong></td>
    </tr>
  <tr>
    <td width="493" >Fecha de vigencia</td>
    <td colspan="2">'.FormatDateTime($x_fecha_referencia,7).'</td>
    </tr>
  <tr>
    <td>Monto para liquidar</td>
    <td colspan="2"><strong>'. FormatNumber($x_saldo_a_liquidar,2,0,0,1).'</strong></td>
    </tr>
     <tr>
    <td>Detalle</td>
    <td width="374" >Vencimientos</td>
    <td width="119" >Saldo</td>
  </tr>
  <tr>
    <td>Vencimientos pendientes con interes</td>
    <td>'.$x_lista_con_interes.'</td>
    <td  align="right">'.FormatNumber($x_total_liquida_con_interes,2,0,0,1).'</td>
  </tr>
  <tr>
    <td>Vencimientos pendientes sin interes</td>
    <td>'.$x_lista_sin_interes.'</td>
    <td align="right">'. FormatNumber($x_total_liquida_sin_interes,2,0,0,1).'</td>
  </tr>
  <tr>
    <td>Moratorios</td>
    <td>'. $x_lista_penalizaciones.'</td>
    <td align="right">'.FormatNumber($x_total_penalizaciones,2,0,0,1).'</td>
  </tr>
  <tr>
    <td colspan="2" align="right">Total: </td>
    <td align="right"><strong>'.FormatNumber($x_saldo_a_liquidar,2,0,0,1).'</strong></td>
  </tr>
</table>
<p>';



}

//return $bLoadData;
$x_tabla_fina = $x_respuesta. $x_respuesta1.$x_respuesta2.$x_respuesta3.$x_respuesta4.$x_respuesta5.$x_respuesta6.$x_respuesta7.$x_respuesta8.$x_respuesta9.$x_respuesta10.$x_respuesta11.$x_respuesta12.$x_respuesta13.$x_respuesta14.$x_respuesta15.$x_respuesta16.$x_respuesta17.$x_respuesta18.$x_respuesta19.$x_respuesta20.$x_respuesta21.$x_respuesta26.$x_tabla_liquida.$x_penalizacion.$x_tabla_garantia;

// se quitaron los comentarios en lo qu se busca como truncarlos  $x_respuesta22.$x_respuesta23.$x_respuesta24.
return $x_tabla_fina ;
}



function LoadDataEdoCuenta_old($conn, $x_credito_id ){


$x_solcre_id =$x_credito_id;
//CREDITO

$x_tabla_fina = "";
	$x_respuesta = "";
	$x_respuesta1 = "";
	$x_respuesta2 = "";
	$x_respuesta3 = "";
	$x_respuesta4 = "";
	$x_respuesta5 = "";
	$x_respuesta6 = "";
	$x_respuesta7 = "";
	$x_respuesta8 = "";
	$x_respuesta9 = "";
	$x_respuesta10 = "";
	$x_respuesta11 = "";
	$x_respuesta12 = "";
	$x_respuesta13 = "";
	$x_respuesta14 = "";
	$x_respuesta15 = "";
	$x_respuesta16 = "";
	$x_respuesta17 = "";
	$x_respuesta18 = "";
	$x_respuesta19 = "";
	$x_respuesta20 = "";
	$x_respuesta21 = "";
	$x_respuesta26 = "";

$x_respuesta = "";
$sSql = "select * from credito where credito_id = ".$x_solcre_id;
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
	$GLOBALS["x_credito_id"] = $row["credito_id"];
	$x_credito_num = $row["credito_num"];		
	$x_cliente_num = $row["cliente_num"];				
	$x_credito_tipo_id = $row["credito_tipo_id"];
	$GLOBALS["x_credito_tipo_id"] = $row["credito_tipo_id"];
	$GLOBALS["x_solicitud_id"] = $row["solicitud_id"];
	$x_solicitud_id = $row["solicitud_id"];
	$x_credito_status_id = $row["credito_status_id"];
	$x_fecha_otrogamiento = $row["fecha_otrogamiento"];
	$x_importe = $row["importe"];
	$x_tasa = $row["tasa"];
	$x_plazo = $row["plazo_id"];
	$x_fecha_vencimiento = $row["fecha_vencimiento"];
	$x_tasa_moratoria = $row["tasa_moratoria"];
	$x_medio_pago_id= $row["medio_pago_id"];
	$x_banco_id = $row["banco_id"];		
	$x_referencia_pago = $row["referencia_pago"];
	$x_forma_pago_id = $row["forma_pago_id"];	
	$GLOBALS["x_forma_pago_id"] = $row["forma_pago_id"];	
	$x_num_pagos= $row["num_pagos"];				
	$x_tdp = $row["tarjeta_num"];		
	$GLOBALS["credito_tipo_id"] = $row["credito_tipo_id"];
	$credito_tipo_id = $row["credito_tipo_id"];
	if ($GLOBALS["credito_tipo_id"] == 1){
		$x_tipo_credito = " Individual";
		}else {
			$x_tipo_credito = " Solidario";
			}
	$x_penalizacion = $row["penalizacion"];
	
					
// seleccionamos los comentarios..

$sSqlcomentarios = "SELECT * FROM `credito_comment` where credito_id = ".$GLOBALS["x_credito_id"]."";
$rsComentarios = phpmkr_query($sSqlcomentarios, $conn) or die ("Error al seleccionar la bitacora".phpmkr_error()."sql:".$sSqlcomentarios);
$rowComentarios = phpmkr_fetch_array($rsComentarios);
//$GLOBALS["x_credito_id"] = $row["credito_id"];
$x_comentario_int = "";
$x_comentario_ext = "";
$x_comentario_int = $rowComentarios["comentario_int"];
$x_comentario_ext = $rowComentarios["comentario_ext"];

// seleccionamos los datos del ststaus del contacto =
$SqlContactoStatus = "SELECT * FROM telefono_contacto_status, telefono_status WHERE  telefono_status.telefono_status_id = telefono_contacto_status.telefono_status_id and telefono_contacto_status.credito_id = ".$GLOBALS["x_credito_id"]."";
$rsContactoStatus = phpmkr_query($SqlContactoStatus,$conn) or die ("Error al seleccionar el status del contacto".phpmkr_error()."sql:".$SqlContactoStatus);
$rowContactoStatus = phpmkr_fetch_array($rsContactoStatus);
$x_status_de_contacto = $rowContactoStatus["descripcion"];


//Cuenta
$sSql = "select cliente.* from cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id join solicitud on solicitud.solicitud_id = solicitud_cliente.solicitud_id 
where solicitud.solicitud_id = ".$GLOBALS["x_solicitud_id"];
$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row2 = phpmkr_fetch_array($rs2);
	$GLOBALS["x_cuenta"] = $row2["nombre_completo"]." ".$row2["apellido_paterno"]." ".$row2["apellido_materno"];
	$x_cuenta = $row2["nombre_completo"]." ".$row2["apellido_paterno"]." ".$row2["apellido_materno"];
	$x_c_id = $row2["cliente_id"];

// telefonos
/*if(!empty( $x_c_id)){
 telefonos($conn, $x_c_id);
}*/
$x_count_2 = 1;
if(!empty($x_c_id)){
		$sSql = "select * from  telefono where cliente_id = $x_c_id  AND telefono_tipo_id = 1 order by telefono_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9 = phpmkr_fetch_array($rs9)){			
			$GLOBALS["x_telefono_casa_$x_count_2"] = $row9["numero"];
			$x_telefono_casa[] =$row9["numero"];
			$GLOBALS["x_comentario_casa_$x_count_2"] = $row9["comentario"];
			$GLOBALS["contador_telefono"] = $x_count_2;
			$x_count_2++;
			
		}
}
		



		$x_count_3 = 1;
		if(!empty($x_c_id)){
		$sSql = "select * from  telefono where cliente_id = $x_c_id  AND telefono_tipo_id = 2 order by telefono_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9e = phpmkr_fetch_array($rs9)){			
			$GLOBALS["x_telefono_celular_$x_count_3"] = $row9e["numero"];
			$x_telefono_celular[] = $row9e["numero"];
			$GLOBALS["x_comentario_celular_$x_count_3"] = $row9e["comentario"];
			$GLOBALS["x_compania_celular_$x_count_3"] = $row9e["compania_id"];	
			$GLOBALS["contador_celular"] = $x_count_3;
			$x_count_3++;
			
		}
		}

// direcciones

if(!empty($x_c_id)){
#personal domicilio
$sqlD = "SELECT * FROM direccion WHERE cliente_id = $x_c_id and direccion_tipo_id = 1 order by direccion_id desc limit 1";		
		$sSql2 = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where cliente_id = $x_c_id and direccion_tipo_id = 1 order by direccion_id desc limit 1";
		$rs3 = phpmkr_query($sqlD,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sqlD);
		$row3 = phpmkr_fetch_array($rs3);
		$GLOBALS["x_direccion_id"] = $row3["direccion_id"];		
		$GLOBALS["x_calle_domicilio"] = $row3["calle"];
		$GLOBALS["x_colonia_domicilio"] = $row3["colonia"];
		$GLOBALS["x_delegacion_id"] = $row3["delegacion_id"];
		$GLOBALS["x_localidad_id"] = $row3["localidad_id"];
		$GLOBALS["x_propietario"] = $row3["propietario"];
		$GLOBALS["x_entidad_domicilio"] = $row3["entidad"];
		$GLOBALS["x_codigo_postal_domicilio"] = $row3["codigo_postal"];
		$GLOBALS["x_ubicacion_domicilio"] = $row3["ubicacion"];	
		$GLOBALS["x_numero_exterior"] = $row3["numero_exterior"];
		$x_telefono_casa[] = $row3["telefono"];
		$x_telefono_casa[] = $row3["telefono_secundario"];
		$x_telefono_celular[] = $row3["telefono_movil"];
		
		if (!empty($GLOBALS["x_delegacion_id"])){
		$sqlDel = "SELECT * FROM `delegacion` WHERE `delegacion_id` = ".$GLOBALS["x_delegacion_id"]." ";
		$rsDesl = phpmkr_query($sqlDel, $conn) or die ("Error al seleccionar los del".phpmkr_error()."sql:". $sqlDel);
		$rowDel = phpmkr_fetch_array($rsDesl);
		}
		$x_delegacion = $rowDel["descripcion"];
		
		$x_direccion_dom_th = "Calle : ".$GLOBALS["x_calle_domicilio"]." ". $GLOBALS["x_numero_exterior"].", colonia: ".$GLOBALS["x_colonia_domicilio"].", localidad :".$GLOBALS["x_localidad_id"].", delegacion: ".$x_delegacion.", C.P.: ".$GLOBALS["x_codigo_postal_domicilio"]; 
$x_delegacion = "";
		//falta telefono secundario
#direccion negocio
		$sqlD = "SELECT * FROM direccion WHERE cliente_id = $x_c_id and direccion_tipo_id = 2 order by direccion_id desc limit 1";	
		$sSql = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where cliente_id = $x_c_id  and direccion_tipo_id = 2 order by direccion_id desc limit 1";
		$rs4 = phpmkr_query($sqlD,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row4 = phpmkr_fetch_array($rs4);
		
			$GLOBALS["x_direccion_id2"] = $row4["direccion_id"];
			$GLOBALS["x_giro_negocio"] = $row4["giro_negocio"];
			$GLOBALS["x_calle_negocio"] = $row4["calle"];
			$GLOBALS["x_colonia_negocio"] = $row4["colonia"];
			$GLOBALS["x_entidad_negocio"] = $row4["entidad"];
			$GLOBALS["x_ubicacion_negocio"] = $row4["ubicacion"];
			$GLOBALS["x_codigo_postal_negocio"] = $row4["codigo_postal"];			
			$GLOBALS["x_delegacion_id2"] = $row4["delegacion_id"];
			$GLOBALS["x_localidad_id2"] = $row4["localidad_id"];
			$GLOBALS["x_numero_exterior2"] = $row4["numero_exterior"];
			$x_telefono_casa[] = $row3["telefono"];
			$x_telefono_casa[] = $row3["telefono_secundario"];
			$x_telefono_celular[] = $row3["telefono_movil"];
			
			
			 if(!empty($GLOBALS["x_delegacion_id2"])){
				$sqlDel = "SELECT * FROM `delegacion` WHERE `delegacion_id` = ".$GLOBALS["x_delegacion_id2"]." ";
		$rsDesl = phpmkr_query($sqlDel, $conn) or die ("Error al seleccionar los del".phpmkr_error()."sql:". $sqlDel);
		$rowDel = phpmkr_fetch_array($rsDesl);
			 }
		$x_delegacion1 = $rowDel["descripcion"];
				$x_direccion_neg_th = "Calle : ".$GLOBALS["x_calle_negocio"]." ".$GLOBALS["x_numero_exterior2"].", colonia: ".$GLOBALS["x_colonia_negocio"].", localidad :".$GLOBALS["x_localidad_id2"].", delegacion: ".$x_delegacion1.", C.P.: ".$GLOBALS["x_codigo_postal_negocio"]; 
				
		$x_delegacion= "";		
		
		
}

## direccion aval
$sSqlWrka = "SELECT * FROM datos_aval Where solicitud_id = ".$GLOBALS["x_solicitud_id"] ."  group by datos_aval_id";
	$rswrka = phpmkr_query($sSqlWrka,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrka);
	#echo $sSqlWrka;
	$rowwrka = phpmkr_fetch_array($rswrka);
	$x_aval = $rowwrka["aval"];
	$x_datos_aval_id = $rowwrka["datos_aval_id"];
	#echo $x_datos_aval_id."aid";
	@phpmkr_free_result($rswrk);
	    $GLOBALS["x_nombre_completo"] = $rowwrka["nombre_completo"];
		$GLOBALS["x_apellido_paterno"] = $rowwrka["apellido_paterno"];
		$GLOBALS["x_apellido_materno"] = $rowwrka["apellido_materno"];
		$GLOBALS["x_nombre_avala"]= " ". $GLOBALS["x_nombre_completo"] ." ".$GLOBALS["x_apellido_paterno"]." ".$GLOBALS["x_apellido_materno"]." ";
		$x_nombre_aval= " ". $GLOBALS["x_nombre_completo"] ." ".$GLOBALS["x_apellido_paterno"]." ".$GLOBALS["x_apellido_materno"]." ";
		$GLOBALS["x_rfc"] = $rowwrka["rfc"];
		$GLOBALS["x_curp"] = $rowwrka["curp"];
		$GLOBALS["x_calle"] = $rowwrka["calle"];
		$GLOBALS["x_colonia"] = $rowwrka["colonia"];
		$GLOBALS["x_entidad"] = $rowwrka["entidad"];
		$GLOBALS["x_codigo_postal"] = $rowwrka["codigo_postal"];
		$GLOBALS["x_delegacion"] = $rowwrka["delegacion"];
		$GLOBALS["x_vivienda_tipo"] = $rowwrka["vivienda_tipo"];
		$GLOBALS["x_telefono_p"] = $rowwrka["telefono_p"];
		$GLOBALS["x_telefono_c"] = $rowwrka["telefono_c"];
		$GLOBALS["x_telefono_o"] = $rowwrka["telefono_o"];
		$GLOBALS["x_antiguedad_v"] = $rowwrka["antiguedad_v"];
		$GLOBALS["x_tel_arrendatario_v"] = $rowwrka["tel_arrendatario_v"];
		$GLOBALS["x_renta_mensual_v"] = $rowwrka["renta_mensual_v"];
		$GLOBALS["x_calle_2"] = $rowwrka["calle_2"];
		$GLOBALS["x_colonia_2"] = $rowwrka["colonia_2"];
		$GLOBALS["x_entidad_2"] = $rowwrka["entidad_2"];
		$GLOBALS["x_codigo_postal_2"] = $rowwrka["codigo_postal_2"];
		$GLOBALS["x_delegacion_2"] = $rowwrka["delegacion_2"];
		
		if(!empty($GLOBALS["x_delegacion"])) {
		$sqlDel = "SELECT * FROM `delegacion` WHERE `delegacion_id` = ".$GLOBALS["x_delegacion"]." ";
		$rsDesl = phpmkr_query($sqlDel, $conn) or die ("Error al seleccionar los del".phpmkr_error()."sql:". $sqlDel);
		$rowDel = phpmkr_fetch_array($rsDesl);
		}
		$x_delegacion2 = $rowDel["descripcion"];
		
		$x_direccion_dom_av = "Calle : ".$GLOBALS["x_calle"].", colonia: ".$GLOBALS["x_colonia"].", delegacion: ".$x_delegacion2 ;
		$x_delegacion = "";
		if(!empty($GLOBALS["x_delegacion_2"])){
		$sqlDel = "SELECT * FROM `delegacion` WHERE `delegacion_id` = ".$GLOBALS["x_delegacion_2"]." ";
		$rsDesl = phpmkr_query($sqlDel, $conn) or die ("Error al seleccionar los del".phpmkr_error()."sql:". $sqlDel);
		$rowDel = phpmkr_fetch_array($rsDesl);
		}
		$x_delegacion3 = $rowDel["descripcion"]; 
		$x_direccion_neg_av = "Calle : ".$GLOBALS["x_calle_2"].", colonia: ".$GLOBALS["x_colonia_2"].", delegacion: ".$x_delegacion3; 
$x_delegacion = "";


if (empty($x_datos_aval_id)){
	// se buscan los datos del avla en la tabla anterior

	$sqlAval	 = "SELECT * FROM aval	WHERE solicitud_id = $x_solicitud_id ";
	$rsAval = phpmkr_query($sqlAval, $conn)  or die ("Error al seleccionar los adtos del aval de la tabla prima".phpmkr_error()."sql:".$sqlAval);
	$rowAval = phpmkr_fetch_array($rsAval);
	$x_aval_id = $rowAval["aval_id"];
	$x_nombre_aval =" ".$rowAval["nombre_completo"]. " ". $rowAval["apellido_paterno"]." ".$rowAval["apellido_materno"];
	
	//echo "nombre aval".$x_nombre_aval;
	if(!empty($x_aval_id)){
	//seleccionamos las direcciones de los avlaes de las tablas primarias
	$sqlD = "SELECT * FROM direccion WHERE aval_id = $x_aval_id and direccion_tipo_id = 3  order by direccion_id desc limit 1";	
	//echo "dir aval".$sqlD;	
		$sSql2 = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where aval_id = $x_aval_id and direccion_tipo_id = 1 order by direccion_id desc limit 1";
		$rs3 = phpmkr_query($sqlD,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sqlD);
		$row3 = phpmkr_fetch_array($rs3);
		$GLOBALS["x_direccion_id"] = $row3["direccion_id"];		
		$GLOBALS["x_calle_domicilio"] = $row3["calle"];
		$GLOBALS["x_colonia_domicilio"] = $row3["colonia"];
		$GLOBALS["x_delegacion_id"] = $row3["delegacion_id"];
		$GLOBALS["x_localidad_id"] = $row3["localidad_id"];
		$GLOBALS["x_propietario"] = $row3["propietario"];
		$GLOBALS["x_entidad_domicilio"] = $row3["entidad"];
		$GLOBALS["x_codigo_postal_domicilio"] = $row3["codigo_postal"];
		$GLOBALS["x_ubicacion_domicilio"] = $row3["ubicacion"];	
		$GLOBALS["x_numero_exterior"] = $row3["numero_exterior"];
		$x_telefono_casa_aval[] = $row3["telefono"];
		$x_telefono_casa_aval[] = $row3["telefono_secundario"];
		$x_telefono_casa_aval[] = $row3["telefono_movil"];
		
		//echo "aval".$row3["telefono"]." ".$row3["telefono_secundario"].$row3["telefono_movil"];
		
		
		if (!empty($GLOBALS["x_delegacion_id"])){
		$sqlDel = "SELECT * FROM `delegacion` WHERE `delegacion_id` = ".$GLOBALS["x_delegacion_id"]." ";
		$rsDesl = phpmkr_query($sqlDel, $conn) or die ("Error al seleccionar los del".phpmkr_error()."sql:". $sqlDel);
		$rowDel = phpmkr_fetch_array($rsDesl);
		}
		$x_delegacion = $rowDel["descripcion"];
		
		$x_direccion_dom_av = "Calle : ".$GLOBALS["x_calle_domicilio"]." ". $GLOBALS["x_numero_exterior"].", colonia: ".$GLOBALS["x_colonia_domicilio"].", localidad :".$GLOBALS["x_localidad_id"].", delegacion: ".$x_delegacion.", C.P.: ".$GLOBALS["x_codigo_postal_domicilio"]; 
$x_delegacion = "";
		//falta telefono secundario
		
	}
	
	}
//promotor

$sSql = "select promotor.nombre_completo from promotor join solicitud on solicitud.promotor_id = promotor.promotor_id where solicitud.solicitud_id = ".$GLOBALS["x_solicitud_id"];
$rs3 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row3 = phpmkr_fetch_array($rs3);
$x_promotor = $row3["nombre_completo"];


//Pagos Vencidos
$sSql = "select count(*) as vencidos from vencimiento
where credito_id = ".$GLOBALS["x_credito_id"]." and vencimiento_status_id = 3 ";
$rs4 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row4 = phpmkr_fetch_array($rs4);
	$GLOBALS["x_pagos_vencidos"] = $row4["vencidos"];
	
	
	
	
	//INTEGRANTES DEL GRUPO
		$x_soli_id =  $GLOBALS["x_solicitud_id"];
		if($GLOBALS["x_credito_tipo_id"] == 2){
		//	cargaIntegrantes($conn, $x_soli_id);
			// ES UN CREDITO SOLIDARIO
			
			$sqlGrupo = "SELECT * FROM creditosolidario WHERE  solicitud_id = $x_soli_id";
			//echo "sql solidario".$sqlGrupo."<br>";
			$responseGrupo = phpmkr_query($sqlGrupo,$conn) or die ("error al ejecutar query grupo".phpmkr_error()."sql: ".$sqlGrupo);
			$rowGrupo = phpmkr_fetch_array($responseGrupo);
			$GLOBALS["x_creditoSolidario_id"] =  $rowGrupo["creditoSolidario_id"];
			$GLOBALS["x_nombre_grupo"] = $rowGrupo["nombre_grupo"];
			//echo $GLOBALS["x_nombre_grupo"]."..";
			$x_cont_g = 1;
			while($x_cont_g <= 10){
				
				$GLOBALS["x_integrante_$x_cont_g"] = $rowGrupo["integrante_$x_cont_g"];
				$GLOBLAS["x_nombre_integrante_$x_cont_g"] = $rowGrupo["integrante_$x_cont_g"];
				 
				//$x_monto_i =  $rowGrupo["monto_$x_cont_g"];
				//$GLOBALS["x_monto_$x_cont_g"] = number_format($x_monto_i);
				
				//echo "integrante".$rowGrupo["integrante_$x_cont_g"]."<br>";
				$GLOBALS["x_monto_$x_cont_g"] =  $rowGrupo["monto_$x_cont_g"];
				$GLOBALS["x_rol_integrante_$x_cont_g"] = $rowGrupo["rol_integrante_$x_cont_g"]; 
				$GLOBALS["x_cliente_id_$x_cont_g"] = $rowGrupo["cliente_id_$x_cont_g"];
				//echo "cliente id".$GLOBALS["x_cliente_id_$x_cont_g"]." grupo <br>";
				$grupoId[]= $GLOBALS["x_cliente_id_$x_cont_g"];
				
				
				//BUSCO AL REPRESENTANTE DEL GRUPO
				if($GLOBALS["x_rol_integrante_$x_cont_g"] == 1){
					$GLOBALS["$x_representate_grupo"] = $rowGrupo["integrante_$x_cont_g"];
					$GLOBALS["x_representante_cliente_id"] =  $rowGrupo["cliente_id_$x_cont_g"];
					}
					
					
					
					
				
				$x_cont_g++;
				}
			
			
			
			
			
			
			}


phpmkr_free_result($rs);
phpmkr_free_result($rs2);
phpmkr_free_result($rs3);
phpmkr_free_result($rs4);


$x_respuesta = '
<table align="center" width="700" border="0" cellspacing="0" cellpadding="0">
<tr>
<td>
  <img src="images/logo_arbol_completo.gif" width="115" height="93" />
  </td></tr>
</table>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="0" class="link_oculto">
<tr >
  <td colspan="5" class="txt_negro_medio" style=" border-bottom: solid 1px #C00; border-top: solid 1px #C00; padding-bottom: 5px; padding-top: 5px;" bgcolor="#ffffff">
  </td>
  </tr></table>
  
  <table align="center" width="700" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td class="txt_negro_medio" >

  </td>
  <td colspan="4" class="txt_datos_azul" align="center">Credito'.$x_tipo_credito.'</td>
</tr>
<tr>
  <td class="txt_negro_medio">Status del contacto:</td>
  <td class="txt_datos_azul">'.$x_status_de_contacto.'</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
</tr>
<tr>
  <td class="txt_negro_medio">Cuenta</td>
  <td colspan="4" class="txt_datos_azul"> '.$x_cuenta.'</td>
  </tr>
   <tr>
  <td class="txt_negro_medio">Cliente No.</td>
  <td colspan="4" class="txt_datos_azul"> '.$x_cliente_num.'</td>
  </tr>
<tr>
  <td class="txt_negro_medio">Promotor:</td>
  <td colspan="4" class="txt_datos_azul">'. $x_promotor.'</td>
  </tr>
<tr>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
</tr>
<tr>
<td width="132" class="txt_negro_medio">Credito No.
</td>
<td width="108" class="txt_datos_azul">'.$x_credito_num.'</td>
<td width="41">&nbsp;</td>
<td width="106" class="txt_negro_medio">Status</td>
<td width="313" class="txt_datos_azul">';
  
if ((!is_null($x_credito_status_id)) && ($x_credito_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `credito_status`";
	$sTmp = $x_credito_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk11 .= " WHERE `credito_status_id` = " . $sTmp . "";
	$rswrk11 = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk11 && $rowwrk11 = phpmkr_fetch_array($rswrk11)) {
		$sTmp = $rowwrk11["descripcion"];
	}
	@phpmkr_free_result($rswrk11);
} else {
	$sTmp = "";
}
$ox_credito_status_id = $x_credito_status_id; // Backup Original Value
$x_credito_status_id = $sTmp;

  $x_respuesta .= " $x_credito_status_id";
   $x_credito_status_id = $ox_credito_status_id; // Restore Original Value 
  
  
   $x_respuesta .= "</td></tr>";
  
$x_respuesta .=  '<tr>
  <td class="txt_negro_medio"> Tarjeta Num.</td>
  <td class="txt_datos_azul">'.$x_tdp.'</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">Otorgado</td>
  <td class="txt_datos_azul">'. FormatDateTime($x_fecha_otrogamiento,7).'</td>
</tr>';
$x_respuesta .= '<tr>
  <td class="txt_negro_medio">Vencimiento</td>
  <td class="txt_datos_azul">'. FormatDateTime($x_fecha_vencimiento,7).'</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">Importe</td>
  <td class="txt_datos_azul">'.FormatNumber($x_importe,0,0,0,-2).'</td>
</tr>';
$x_respuesta .= '<tr>
  <td class="txt_negro_medio">Tasa</td>
  <td class="txt_datos_azul">'. FormatPercent(($x_tasa / 100),2,0,0,0).'</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">Num. pagos</td>
  <td class="txt_datos_azul">'.$x_num_pagos.'</td>
</tr>';
$x_respuesta .= '<tr>
  <td class="txt_negro_medio">Tasa Moratorios</td>
  <td class="txt_datos_azul">'.FormatPercent(($x_tasa_moratoria / 100),2,0,0,0).'</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">Forma de pago</td>
  <td class="txt_datos_azul">';
    
		$sSqlWrk = "SELECT descripcion FROM forma_pago where forma_pago_id = ".$GLOBALS["x_forma_pago_id"]."";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
	$x_respuesta .= '' .$datawrk["descripcion"].'';
		@phpmkr_free_result($rswrk);
		
  $x_respuesta .=  '</td>
</tr>';
$x_respuesta .= '<tr>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
</tr>
</table>';
 if( $x_credito_tipo_id == 2){
	//$x_respuesta .= "CHULMIS";
	 	//imprimimos la tabla de los integrantes
	//echo $x_tabla_integrantes;
	

	//$x_no_integrante = 1;
//while($x_no_integrante <= 10){
	 foreach($grupoId as $x_cliente_idG){
	
		$x_id_int =  $x_cliente_idG;
	//	echo "CLIENTE ID".$x_id_int."<BR>";

	if(!empty($x_id_int) && $x_id_int >0 ){
		//Buscamos las direcciones y los datos del clientre
		
		
		//Nombre
		//Cuenta
$sSql = "select cliente.* from cliente where cliente_id = $x_id_int ";
//echo $sSql;
$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row2 = phpmkr_fetch_array($rs2);
	$x_cuenta_g = $row2["nombre_completo"]." ".$row2["apellido_paterno"]." ".$row2["apellido_materno"];
		//echo $GLOBALS["x_cuenta"];
		unset($x_telefono_casa);
		unset($x_telefono_celular);
		if(!empty($x_id_int)){
		telefonos($conn,$x_id_int);
		}
		
		$x_respuesta .= '<table align="center" width="700" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td align="center" class="txt_negro_medio" style=" border-bottom: solid 1px #000">DATOS DEL CLIENTE:   '.$x_cuenta_g.'</td>
</tr>';
$x_count_2 = 1;
		$sSql = "select * from  telefono where cliente_id = $x_id_int  AND telefono_tipo_id = 1 order by telefono_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9 = phpmkr_fetch_array($rs9)){			
			$GLOBALS["x_telefono_casa_$x_count_2"] = $row9["numero"];
			$x_telefono_casa[] =$row9["numero"];
			$GLOBALS["x_comentario_casa_$x_count_2"] = $row9["comentario"];
			$GLOBALS["contador_telefono"] = $x_count_2;
			$x_count_2++;			
		}
		
		



		$x_count_3 = 1;
		$sSql = "select * from  telefono where cliente_id = $x_id_int  AND telefono_tipo_id = 2 order by telefono_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9e = phpmkr_fetch_array($rs9)){			
			$GLOBALS["x_telefono_celular_$x_count_3"] = $row9e["numero"];
			$x_telefono_celular[] =$row9e["numero"];
			$GLOBALS["x_comentario_celular_$x_count_3"] = $row9e["comentario"];
			$GLOBALS["x_compania_celular_$x_count_3"] = $row9e["compania_id"];	
			$GLOBALS["contador_celular"] = $x_count_3;
			$x_count_3++;
			
		}		
		
		#personal domicilio
$sqlD = "SELECT * FROM direccion WHERE cliente_id = $x_id_int and direccion_tipo_id = 1 order by direccion_id desc limit 1";		
		$sSql2 = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where cliente_id = $x_c_id and direccion_tipo_id = 1 order by direccion_id desc limit 1";
		$rs3 = phpmkr_query($sqlD,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sqlD);
		$row3 = phpmkr_fetch_array($rs3);
		$GLOBALS["x_direccion_id"] = $row3["direccion_id"];		
		$GLOBALS["x_calle_domicilio"] = $row3["calle"];
		$GLOBALS["x_colonia_domicilio"] = $row3["colonia"];
		$GLOBALS["x_delegacion_id"] = $row3["delegacion_id"];
		$GLOBALS["x_localidad_id"] = $row3["localidad_id"];
		$GLOBALS["x_propietario"] = $row3["propietario"];
		$GLOBALS["x_entidad_domicilio"] = $row3["entidad"];
		$GLOBALS["x_codigo_postal_domicilio"] = $row3["codigo_postal"];
		$GLOBALS["x_ubicacion_domicilio"] = $row3["ubicacion"];	
		$GLOBALS["x_numero_exterior"] = $row3["numero_exterior"];
		$GLOBALS["x_telefono"] = $row3["telefono"];
		$GLOBALS["x_telefono_secundario"] = $row3["telefono_secundario"];
		$GLOBALS["x_telefono_movil"] = $row3["telefono_movil"];
		$x_telefono_casa[] = $row3["telefono"];
		$x_telefono_casa[] = $row3["telefono_secundario"];
		$x_telefono_celular[] = $row3["telefono_movil"];
		
		if (!empty($GLOBALS["x_delegacion_id"])){
		$sqlDel = "SELECT * FROM `delegacion` WHERE `delegacion_id` = ".$GLOBALS["x_delegacion_id"]." ";
		$rsDesl = phpmkr_query($sqlDel, $conn) or die ("Error al seleccionar los del".phpmkr_error()."sql:". $sqlDel);
		$rowDel = phpmkr_fetch_array($rsDesl);
		}
		$x_delegacion = $rowDel["descripcion"];
		
		$x_direccion_dom_th_g = "Calle : ".$GLOBALS["x_calle_domicilio"]." ". $GLOBALS["x_numero_exterior"].", colonia: ".$GLOBALS["x_colonia_domicilio"].", localidad :".$GLOBALS["x_localidad_id"].", delegacion: ".$x_delegacion.", C.P.: ".$GLOBALS["x_codigo_postal_domicilio"]; 
$x_delegacion = "";

//echo $GLOBALS["x_direccion_dom_th"] ;

		//falta telefono secundario
#direccion negocio
		$sqlD = "SELECT * FROM direccion WHERE cliente_id = $x_id_int and direccion_tipo_id = 2 order by direccion_id desc limit 1";	
		$sSql = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where cliente_id = $x_c_id  and direccion_tipo_id = 2 order by direccion_id desc limit 1";
		$rs4 = phpmkr_query($sqlD,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row4 = phpmkr_fetch_array($rs4);
		
			$GLOBALS["x_direccion_id2"] = $row4["direccion_id"];
			$GLOBALS["x_giro_negocio"] = $row4["giro_negocio"];
			$GLOBALS["x_calle_negocio"] = $row4["calle"];
			$GLOBALS["x_colonia_negocio"] = $row4["colonia"];
			$GLOBALS["x_entidad_negocio"] = $row4["entidad"];
			$GLOBALS["x_ubicacion_negocio"] = $row4["ubicacion"];
			$GLOBALS["x_codigo_postal_negocio"] = $row4["codigo_postal"];			
			$GLOBALS["x_delegacion_id2"] = $row4["delegacion_id"];
			$GLOBALS["x_localidad_id2"] = $row4["localidad_id"];
			$GLOBALS["x_numero_exterior2"] = $row4["numero_exterior"];
			$x_telefono_casa[] = $row3["telefono"];
		$x_telefono_casa[] = $row3["telefono_secundario"];
		$x_telefono_celular[] = $row3["telefono_movil"];
			
			
			 if(!empty($GLOBALS["x_delegacion_id2"])){
				$sqlDel = "SELECT * FROM `delegacion` WHERE `delegacion_id` = ".$GLOBALS["x_delegacion_id2"]." ";
		$rsDesl = phpmkr_query($sqlDel, $conn) or die ("Error al seleccionar los del".phpmkr_error()."sql:". $sqlDel);
		$rowDel = phpmkr_fetch_array($rsDesl);
			 }
		$x_delegacion1 = $rowDel["descripcion"];
				$x_direccion_neg_th_g = "Calle : ".$GLOBALS["x_calle_negocio"]." ".$GLOBALS["x_numero_exterior2"].", colonia: ".$GLOBALS["x_colonia_negocio"].", localidad :".$GLOBALS["x_localidad_id2"].", delegacion: ".$x_delegacion1.", C.P.: ".$GLOBALS["x_codigo_postal_negocio"]; 
				
		$x_delegacion= "";		
		//echo $GLOBALS["x_direccion_neg_th"];
		

		
	$x_lis_tel = "";	
	if(!empty($x_telefono_casa)){
foreach($x_telefono_casa as $telefonos_casa){
	$x_lis_tel = $x_lis_tel.$telefonos_casa .", ";
	}
	}
	$x_lis_cel = "";
	if(!empty($x_telefono_celeular)){
foreach($x_telefono_celeular as $telefonos_cel){
	$x_lis_cel = $x_lis_cel.$telefonos_cel .", ";
	}	
	}
	
	if(!empty($x_telefono_casa_aval)){
		foreach( $x_telefono_casa_aval as $telefonos_aval){
			$x_lis_tel_aval = $x_lis_tel_aval. $telefonos_aval. " "; 
			
			}
		
		}

$x_respuesta .='<tr>
  <td  class="txt_negro_medio">TH DOM:'. $x_direccion_dom_th_g.'</td>
</tr>
<tr>
  <td  class="txt_negro_medio">TH NEG:'. $x_direccion_neg_th_g.'</td>
</tr>
<tr>';
$x_respuesta .= '<td  class="txt_negro_medio">Telefonos:  " Casa: "'.$x_lis_tel.' " Cel: "'.$x_lis_cel.'</td>
</tr>
<tr>
  <td  class="txt_negro_medio">&nbsp;</td>
</tr>
</table>';


	}

}
	
	
	
	
	
	
	}else{
    
$x_respuesta7=' <table align="center" width="700" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td colspan="5" align="center" class="txt_negro_medio" style=" border-bottom: solid 1px #000">Datos de Cliente</td>
</tr>
<tr>
  <td colspan="5"  class="txt_negro_medio">TH DOM:'.$x_direccion_dom_th.'</td>
</tr>
<tr>
  <td colspan="5"  class="txt_negro_medio">TH NEG: '.$x_direccion_neg_th.'</td>
</tr>
<tr>';


if(!empty($x_telefono_casa)){
foreach($x_telefono_casa as $telefonos_casa){
	$x_lis_tel = $x_lis_tel.$telefonos_casa .", ";
	}
}
if(!empty($x_telefono_celular)){
foreach($x_telefono_celular as $telefonos_cel){
	$x_lis_cel = $x_lis_cel.$telefonos_cel .", ";
	}
}
if(!empty($x_telefono_casa_aval)){
		foreach( $x_telefono_casa_aval as $telefonos_aval){
			$x_lis_tel_aval = $x_lis_tel_aval. $telefonos_aval. " "; 
			
			}
		
		}

$x_respuesta8='<td colspan="5"  class="txt_negro_medio">Telefonos:  Casa: '.$x_lis_tel. ' Cel: '.$x_lis_cel.'</td>
</tr>
<tr>
  <td colspan="5"  class="txt_negro_medio">&nbsp;</td>
</tr>
<tr>
  <td colspan="5" align="center" class="txt_negro_medio" style=" border-bottom: solid 1px #000">Datos de Aval</td>
</tr>
<tr>
  <td colspan="5" class="txt_negro_medio">Aval :'. $x_nombre_avala.'</td>
</tr>
<tr>
  <td colspan="5"  class="txt_negro_medio">AV DOM: '.$x_direccion_dom_av.'</td>
</tr>
<tr>
  <td colspan="5"  class="txt_negro_medio">AV NEG:'. $x_direccion_neg_av.'</td>
</tr>
<tr>';



$x_respuesta9=' <td colspan="5"  class="txt_negro_medio">Telefonos: Casa: '.$x_lis_tel_aval. ' Cel: '.$x_telefono_c.'</td>
</tr>
<tr>
  <td colspan="5"  class="txt_negro_medio">&nbsp;</td>
</tr>
</table>';
}
$x_respuesta10= '<table width="700" align="center">
<tr>
  <td colspan="5" align="center" class="txt_negro_medio" style=" border-bottom: solid 1px #000">Estado de Cliente</td>
</tr>
<tr>
  <td colspan="5" align="center" class="txt_negro_medio">&nbsp;</td>
</tr>
<tr>
  <td colspan="5" class="txt_negro_medio">';
  

	$sSqlvencimeinto = "SELECT * FROM vencimiento WHERE (vencimiento.credito_id = $x_solcre_id)   and vencimiento.vencimiento_num < 2000 ORDER BY vencimiento.vencimiento_num+0";  
	//and vencimiento.vencimiento_num < 2000
	//echo $sSqlvencimeinto;
	$rsVencimiento = phpmkr_query($sSqlvencimeinto,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSqlvencimiento);
  
  
  
$x_respuesta11='<table width="750"  align="center" cellpadding="0" cellspacing="0" border="0"> 
    
    <tr class="ewTableHeader">
    <td width="1%">&nbsp;  </td>
      <td width="4%" align="center" valign="middle" class="txt_negro_medio">No</td>
      <td width="8%" align="center" valign="middle" class="txt_negro_medio"> Status </td>
      <td width="7%" align="center" valign="middle" class="txt_negro_medio"> Fecha </td>
      <td width="13%" align="center" valign="middle" class="txt_negro_medio"> Fecha de Pago </td>
      <td width="9%" align="center" valign="middle" class="txt_negro_medio"> Capital </td>
      <td width="8%" align="center" valign="middle" class="txt_negro_medio"> Importe </td>
      <td width="9%" align="center" valign="middle" class="txt_negro_medio"> Inter&eacute;s </td>
      <td width="8%" align="center" valign="middle" class="txt_negro_medio">IVA</td>
      <td width="11%" align="center" valign="middle" class="txt_negro_medio"> Moratorios </td>
      <td width="9%" align="center" valign="middle" class="txt_negro_medio">IVA M.</td>
      <td width="13%" align="center" valign="middle" class="txt_negro_medio"> Total </td>
    </tr>';
    


$sSqlWrk = "SELECT importe FROM credito where credito_id = $x_solcre_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
$x_saldo = $datawrk["importe"];
@phpmkr_free_result($rswrk);

$x_total = 0;
$x_total_pagos = 0;
$x_total_interes = 0;
$x_total_iva = 0;
$x_total_moratorios = 0;
$x_total_iva_moratorios = 0;
$x_total_total = 0;

$x_total_d = 0;
$x_total_pagos_d = 0;
$x_total_interes_d = 0;
$x_total_iva_d = 0;
$x_total_moratorios_d = 0;
$x_total_iva_moratorios_d = 0;
$x_total_total_d = 0;

//NUEVO
$x_total_pagos_v = 0;
$x_total_interes_v = 0;
$x_total_iva_v = 0;
$x_total_iva_mor_v = 0;
$x_total_moratorios_v = 0;
$x_total_total_v = 0;


$x_total_a = 0;
$x_total_pagos_a = 0;
$x_total_interes_a = 0;
$x_total_iva_a = 0;
$x_total_moratorios_a = 0;
$x_total_iva_moratorios_a = 0;
$x_total_total_a = 0;


while ($rowVencimeinto = @phpmkr_fetch_array($rsVencimiento)) {

		$x_vencimiento_id = $rowVencimeinto["vencimiento_id"];
		$x_vencimiento_num = $rowVencimeinto["vencimiento_num"];		
		$x_vencimiento_status_id = $rowVencimeinto["vencimiento_status_id"];
		
		$x_fecha_vencimiento = $rowVencimeinto["fecha_vencimiento"];
		$x_importe = $rowVencimeinto["importe"];
		$x_interes = $rowVencimeinto["interes"];
		$x_iva = $rowVencimeinto["iva"];		
		$x_iva_mor = $rowVencimeinto["iva_mor"];				
		if(empty($x_iva)){
			$x_iva = 0;
		}
		if(empty($x_iva_mor)){
			$x_iva_mor = 0;
		}
		
		$x_interes_moratorio = $rowVencimeinto["interes_moratorio"];
		
		$x_total = $x_importe + $x_interes + $x_iva + $x_interes_moratorio + $x_iva_mor;

		$x_total_pagos = $x_total_pagos + $x_importe;
		$x_total_interes = $x_total_interes + $x_interes;
		$x_total_iva = $x_total_iva + $x_iva;		
		$x_total_moratorios = $x_total_moratorios + $x_interes_moratorio;
		$x_total_iva_moratorios = $x_total_iva_moratorios + $x_iva_mor;		
		$x_total_total = $x_total_total + $x_total;
		
		if(($x_vencimiento_status_id == 2) || ($x_vencimiento_status_id == 5)){
		
			$sSqlWrk = "SELECT fecha_pago, referencia_pago_2 FROM recibo join recibo_vencimiento on recibo_vencimiento.recibo_id = recibo.recibo_id where recibo_vencimiento.vencimiento_id = $x_vencimiento_id and recibo.recibo_status_id = 1";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$rowwrk = phpmkr_fetch_array($rswrk);
			$x_fecha_pago = $rowwrk["fecha_pago"];
			$x_referencia_pago2 = $rowwrk["referencia_pago_2"];			

			@phpmkr_free_result($rswrk);

			$x_total_pagos_a = $x_total_pagos_a + $x_importe;
			$x_total_interes_a = $x_total_interes_a + $x_interes;
			$x_total_iva_a = $x_total_iva_a + $x_iva;			
			$x_total_moratorios_a = $x_total_moratorios_a + $x_interes_moratorio;
			$x_total_iva_moratorios_a = $x_total_iva_moratorios_a + $x_iva_mor;			
			$x_total_total_a = $x_total_total_a + $x_total;

		}else{
			$x_fecha_pago  = "";
			$x_referencia_pago2 = "";						

			$x_total_pagos_d = $x_total_pagos_d + $x_importe;
			$x_total_interes_d = $x_total_interes_d + $x_interes;
			$x_total_iva_d = $x_total_iva_d + $x_iva;			
			$x_total_moratorios_d = $x_total_moratorios_d + $x_interes_moratorio;
			$x_total_iva_moratorios_d = $x_total_iva_moratorios_d + $x_iva_mor;			
			$x_total_total_d = $x_total_total_d + $x_total;
			
			//NUEVO
			
			if($x_vencimiento_status_id == 3){
				$x_total_pagos_v = $x_total_pagos_v + $x_importe;
				$x_total_interes_v = $x_total_interes_v + $x_interes;
				$x_total_iva_v = $x_total_iva_v + $x_iva;			
				$x_total_iva_mor_v = $x_total_iva_mor_v + $x_iva_mor;						
				$x_total_moratorios_v = $x_total_moratorios_v + $x_interes_moratorio;
				$x_total_total_v = $x_total_total_v + $x_total;
			}
			
			
			
			
			
			
		}
		
		$x_ref_loc = str_pad($x_vencimiento_id, 5, "0", STR_PAD_LEFT)."/".str_pad($x_vencimiento_num, 2, "0", STR_PAD_LEFT);
		

// $x_respuesta11.= '<tr'. $sItemRowClass.$sListTrJs .'>';
    $x_respuesta11.= '<tr class="ewTableHeader">';
      
$x_respuesta11.= '<td> </td>

     
     
      <td align="right"> <span class="txt_datos_azul">'. $x_vencimiento_num.' </span></td>
      
     
      <td align="center">
        <span class="txt_datos_azul">';
        
if ((!is_null($x_vencimiento_status_id)) && ($x_vencimiento_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `vencimiento_status`";
	$sTmp = $x_vencimiento_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `vencimiento_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_vencimiento_status_id = $x_vencimiento_status_id; // Backup Original Value
$x_vencimiento_status_id = $sTmp;

        $x_respuesta11 .= $x_vencimiento_status_id;         
         $x_vencimiento_status_id = $ox_vencimiento_status_id; // Restore Original Value ?     
       $x_respuesta11 .=' </span></td>
      
      <td align="center"> <span class="txt_datos_azul">'. FormatDateTime($x_fecha_vencimiento,7).'</span></td>
      <td align="center"> <span class="txt_datos_azul">'. FormatDateTime($x_fecha_pago,7).'</span></td>
      <td align="right"> <span class="txt_datos_azul">'.  FormatNumber($x_saldo,2,0,0,1).'</span></td>
      
      <td align="right"> <span class="txt_datos_azul">'. FormatNumber($x_importe,2,0,0,1).' </span></td>
     
      <td align="right"> <span class="txt_datos_azul">'.FormatNumber($x_interes,2,0,0,1).'</span></td>
      <td align="right"><span class="txt_datos_azul">'.FormatNumber($x_iva,2,0,0,1).'</span></td>
      
      <td align="right"> <span class="txt_datos_azul">'. FormatNumber($x_interes_moratorio,2,0,0,1).'</span></td>
      <td align="right"><span class="txt_datos_azul">'. FormatNumber($x_iva_mor,2,0,0,1).'</span></td>
      <td align="right"> <span class="txt_datos_azul">'. FormatNumber($x_total,2,0,0,1).'</span></td>
    </tr>';
    
$x_saldo = $x_saldo - $x_importe;
}




$sqlSumaPenalizaciones = "SELECT SUM(interes) as interes_pena, SUM(iva) as iva_pena, SUM(interes_moratorio) as mora_pena , SUM(iva_mor) AS iva_mor_pena 	from vencimiento where credito_id = $x_credito_id and vencimiento_num > 2000 and  vencimiento_num < 3000 ";
 $rsSumaPenalizaciones = phpmkr_query($sqlSumaPenalizaciones,$conn) or die("error".phpmkr_error().$sqlSumaPenalizaciones);
 $rowPena = phpmkr_fetch_array($rsSumaPenalizaciones);
 
 $sqlSumaPenalizaciones = "SELECT SUM(interes) as interes_comi, SUM(iva) as iva_comi, SUM(interes_moratorio) as mora_comi , SUM(iva_mor) AS iva_mor_comi 	from vencimiento where credito_id = $x_credito_id and vencimiento_num > 3000 ";
 $rsSumaComi = phpmkr_query($sqlSumaPenalizaciones,$conn) or die("error".phpmkr_error().$sqlSumaPenalizaciones);
 $rowComi = phpmkr_fetch_array($rsSumaComi);
 //$x_comi = mysql_num_rows($rsSumaPenalizaciones);
 
 // seleccionar lo pagado de penalizacion
 
 $sqlSumaPenalizaciones = "SELECT SUM(interes) as interes_pena, SUM(iva) as iva_pena, SUM(interes_moratorio) as mora_pena , SUM(iva_mor) AS iva_mor_pena 	from vencimiento where vencimiento.vencimiento_status_id = 2 and credito_id = $x_credito_id and vencimiento_num > 2000 and vencimiento_num < 3000 ";
 $rsSumaPenalizaciones = phpmkr_query($sqlSumaPenalizaciones,$conn) or die("error".phpmkr_error().$sqlSumaPenalizaciones);
 $rowPenaPagado = phpmkr_fetch_array($rsSumaPenalizaciones);
 $x_inter_pena_pagado =  $rowPenaPagado["mora_pena"];
 $x_iva_pena_pagado =  $rowPenaPagado["iva_mor_pena"];
 
 // lo pagado de comision
 
  $sqlSumaPenalizaciones = "SELECT SUM(interes) as interes_pena, SUM(iva) as iva_pena, SUM(interes_moratorio) as mora_pena , SUM(iva_mor) AS iva_mor_pena 	from vencimiento where vencimiento.vencimiento_status_id = 2 and credito_id = $x_credito_id and vencimiento_num > 3000 ";
 $rsSumaPenalizaciones = phpmkr_query($sqlSumaPenalizaciones,$conn) or die("error".phpmkr_error().$sqlSumaPenalizaciones);
 $rowPenaPagado = phpmkr_fetch_array($rsSumaPenalizaciones);
 $x_inter_comi_pagado =  $rowPenaPagado["mora_pena"];
 $x_iva_comi_pagado =  $rowPenaPagado["iva_mor_pena"];

 
 // seleccionar lo vencido
 
 
 $sqlSumaPenalizaciones = "SELECT SUM(interes) as interes_pena, SUM(iva) as iva_pena, SUM(interes_moratorio) as mora_pena , SUM(iva_mor) AS iva_mor_pena 	from vencimiento where vencimiento.vencimiento_status_id = 3 and credito_id = $x_credito_id and vencimiento_num > 2000 and vencimiento_num < 3000 ";
 $rsSumaPenalizaciones = phpmkr_query($sqlSumaPenalizaciones,$conn) or die("error".phpmkr_error().$sqlSumaPenalizaciones);
$rowPenaVencido = phpmkr_fetch_array($rsSumaPenalizaciones);
 $x_inter_pena_vencido =  $rowPenaVencido["mora_pena"];
 $x_iva_pena_vencido =  $rowPenaVencido["iva_mor_pena"];
 
 // lo vencido de comisiones
 
  
 $sqlSumaPenalizaciones = "SELECT SUM(interes) as interes_pena, SUM(iva) as iva_pena, SUM(interes_moratorio) as mora_pena , SUM(iva_mor) AS iva_mor_pena 	from vencimiento where vencimiento.vencimiento_status_id = 3 and credito_id = $x_credito_id and vencimiento_num > 3000 ";
 $rsSumaPenalizaciones = phpmkr_query($sqlSumaPenalizaciones,$conn) or die("error".phpmkr_error().$sqlSumaPenalizaciones);
$rowPenaVencido = phpmkr_fetch_array($rsSumaPenalizaciones);
 $x_inter_comi_vencido =  $rowPenaVencido["mora_pena"];
 $x_iva_comi_vencido =  $rowPenaVencido["iva_mor_pena"];
 
 
 //pendiente
 
 $sqlSumaPenalizaciones = "SELECT SUM(interes) as interes_pena, SUM(iva) as iva_pena, SUM(interes_moratorio) as mora_pena , SUM(iva_mor) AS iva_mor_pena , SUM(total_venc) as TT_P	from vencimiento where vencimiento.vencimiento_status_id = 1 and credito_id = $x_credito_id and vencimiento_num > 2000  and vencimiento_num < 3000";
 $rsSumaPenalizaciones = phpmkr_query($sqlSumaPenalizaciones,$conn) or die("error".phpmkr_error().$sqlSumaPenalizaciones);
 $rowPenaPendiente = phpmkr_fetch_array($rsSumaPenalizaciones);
 
 $x_inter_pena_pendiente =  $rowPenaPendiente["mora_pena"];
 $x_iva_pena_pendiente =   $rowPenaPendiente["iva_mor_pena"];
 $x_total_pena_pendiente = $rowPenaPendiente["total_venc"];
 
  $sqlSumaPenalizaciones = "SELECT SUM(interes) as interes_pena, SUM(iva) as iva_pena, SUM(interes_moratorio) as mora_pena , SUM(iva_mor) AS iva_mor_pena , SUM(total_venc) as TT_P	from vencimiento where vencimiento.vencimiento_status_id = 1 and credito_id = $x_credito_id and vencimiento_num > 3000  ";
 $rsSumaPenalizaciones = phpmkr_query($sqlSumaPenalizaciones,$conn) or die("error".phpmkr_error().$sqlSumaPenalizaciones);
 $rowPenaPendiente = phpmkr_fetch_array($rsSumaPenalizaciones);
 
 $x_inter_comi_pendiente =  $rowPenaPendiente["mora_pena"];
 $x_iva_comi_pendiente =   $rowPenaPendiente["iva_mor_pena"];
 $x_total_comi_pendiente = $rowPenaPendiente["total_venc"];
 # echo "pendiente ". $x_inter_pena_pendiente ." ". $x_iva_pena_pendiente."<br>";
 #echo "sql :". $sqlSumaPenalizaciones;
 
 //echo "mora ".$rowPena["mora_pena"]. "iva ".$rowPena["iva_mor_pena"]."<br>";
 
if($rowPena["mora_pena"] > 0){
$x_respuesta16.= '<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td colspan="7" align="left"><span class="txt_datos_azul">Interes moratorio</span></td>
<td align="right"><span class="txt_datos_azul">'. FormatNumber($rowPena["mora_pena"],2,0,0,1).'</span></td>
<td align="right"><span class="txt_datos_azul">'. FormatNumber($rowPena["iva_mor_pena"],2,0,0,1).'</span></td>
<td align="right"><span class="txt_datos_azul">'. FormatNumber(($rowPena["iva_mor_pena"] + $rowPena["mora_pena"]),2,0,0,1).'
</span></td>
	</tr>';
}
	if($rowComi["mora_comi"] > 0){
$x_respuesta16.= '<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td colspan="7" align="left"><span class="txt_datos_azul">Comision Cobranza</span></td>
<td align="right"><span class="txt_datos_azul">'. FormatNumber($rowComi["mora_comi"],2,0,0,1).'</span></td>
<td align="right"><span class="txt_datos_azul">'. FormatNumber($rowComi["iva_mor_comi"],2,0,0,1).'</span></td>
<td align="right"><span class="txt_datos_azul">'. FormatNumber(($rowComi["iva_mor_comi"] + $rowComi["mora_comi"]),2,0,0,1).'
</span></td>
	</tr>';	
	}

	


$x_respuesta16.='<tr>
      <td >&nbsp;</td>
      <td >&nbsp;</td>
      <td >&nbsp;</td>
      <td ><span>&nbsp;</span></td>
      <td ><span>&nbsp;</span></td>
      <td ><span>&nbsp;</span></td>
      <td ></td>
      <td ></td>
      <td ></td>
      <td ></td>
      <td ></td>
      <td ></td>
    </tr>
    <tr>
      <td style=" border-top: solid 1px #000">&nbsp;</td>
      <td style=" border-top: solid 1px #000">&nbsp;</td>
      <td style=" border-top: solid 1px #000">&nbsp;</td>
      <td style=" border-top: solid 1px #000" align="right"><span>&nbsp;</span></td>
      <td style=" border-top: solid 1px #000"><span>&nbsp;</span></td>
      <td style=" border-top: solid 1px #000" align="right"><span>&nbsp;</span></td>
      <td style=" border-top: solid 1px #000" align="right"> <span class="txt_datos_azul"><b> '. FormatNumber($x_total_pagos,2,0,0,1).'</b></span></td>
      <td style=" border-top: solid 1px #000" align="right"> <span class="txt_datos_azul"><b>'.FormatNumber($x_total_interes,2,0,0,1).'</b></span></td>
      <td align="right" class="txt_datos_azul" style=" border-top: solid 1px #000"><b>'. FormatNumber($x_total_iva,2,0,0,1).'</b></td>
      <td style=" border-top: solid 1px #000" align="right"> <span class="txt_datos_azul"><b>'. FormatNumber(($x_total_moratorios +  $rowPena["mora_pena"] +  $rowComi["mora_comi"]),2,0,0,1).'</b></span></td>
      <td align="right" class="txt_datos_azul" style=" border-top: solid 1px #000"><b>'.FormatNumber(($x_total_iva_moratorios+ $rowPena["iva_mor_pena"] + $rowComi["iva_mor_comi"]),2,0,0,1).'</b></td>
      <td style=" border-top: solid 1px #000" align="right"> <span class="txt_datos_azul"><b>'.FormatNumber(($x_total_total +  $rowPena["mora_pena"]  +  $rowComi["mora_comi"] +$rowPena["iva_mor_pena"] +$rowComi["iva_mor_comi"]),2,0,0,1).'</b></span></td>
    </tr>
    
    <tr >
      <td  colspan="5" align="right" class="txt_negro_medio">SALDO DEUDOR:</td>
      <td  align="right"><span>&nbsp;</span></td>
      <td  align="right"> <span class="txt_datos_azul"><b> '.FormatNumber($x_total_pagos_d,2,0,0,1).'</b></span></td>
      <td align="right"> <span class="txt_datos_azul"><b> '. FormatNumber($x_total_interes_d,2,0,0,1).'</b></span></td>
      <td  align="right" class="txt_datos_azul"><b>'. FormatNumber($x_total_iva_d,2,0,0,1).'</b></td>
      <td  align="right"> <span class="txt_datos_azul"><b> '.FormatNumber(($x_total_moratorios_d + $x_inter_pena_pendiente + $x_inter_comi_pendiente),2,0,0,1).'</b></span></td>
      <td  align="right" class="txt_datos_azul"><b>'.FormatNumber(($x_total_iva_moratorios_d + $x_iva_pena_pendiente + $x_iva_comi_pendiente),2,0,0,1).'</b></td>
      <td  align="right"> <span class="txt_datos_azul"><b>'.FormatNumber(($x_total_total_d +  $x_inter_pena_pendiente + $x_iva_pena_pendiente +  $x_inter_comi_pendiente + $x_iva_comi_pendiente),2,0,0,1).'</b></span></td>
    </tr>
    <tr>
  <td colspan="5" align="right" class="txt_negro_medio">SALDO VENCIDO:</td>
  <td align="right">&nbsp;</td>
  <td align="right"><span class="txt_datos_azul"><b>'.FormatNumber($x_total_pagos_v,2,0,0,1).'</b></span></td>
  <td align="right"><span class="txt_datos_azul"><b>'.FormatNumber($x_total_interes_v,2,0,0,1).'</b></span></td>
  <td align="right"><span class="txt_datos_azul"><b>'.FormatNumber($x_total_iva_v,2,0,0,1).'</b></span></td>
  <td align="right"><span class="txt_datos_azul"><b>'.FormatNumber(($x_total_moratorios_v + $x_inter_pena_vencido + $x_inter_comi_vencido),2,0,0,1).'</b></span></td>
  <td align="right"><span class="txt_datos_azul"><b>'.FormatNumber(($x_total_iva_mor_v + $x_iva_pena_vencido  + $x_iva_comi_vencido),2,0,0,1).'</b></span></td>
  <td align="right"><span class="txt_datos_azul"><b>'.FormatNumber(($x_total_total_v + $x_inter_pena_vencido +$x_iva_pena_vencido + $x_inter_comi_vencido +$x_iva_comi_vencido),2,0,0,1).'</b></span></td>
</tr>
    <tr>
      <td colspan="5" align="right" class="txt_negro_medio">TOTAL PAGADO:</td>
      <td  align="right">&nbsp;</td>
      <td align="right" class="txt_datos_azul"><b>'.FormatNumber($x_total_pagos_a,2,0,0,1).'</b></td>
      <td align="right" class="txt_datos_azul"><b>'.FormatNumber($x_total_interes_a,2,0,0,1).'</b></td>
      <td align="right" class="txt_datos_azul"><b>'.FormatNumber($x_total_iva_a,2,0,0,1).'</b></td>
      <td align="right" class="txt_datos_azul"><b>'.FormatNumber(($x_total_moratorios_a + $x_inter_pena_pagado  + $x_inter_comi_pagado),2,0,0,1).'</b></td>
      <td align="right" class="txt_datos_azul"><b>'.FormatNumber(($x_total_iva_moratorios_a +$x_iva_pena_pagado +$x_iva_comi_pagado ),2,0,0,1).'</b></td>
      <td align="right" class="txt_datos_azul"><b>'.FormatNumber(($x_total_total_a + $x_inter_pena_pagado +$x_iva_pena_pagado + $x_inter_comi_pagado +$x_iva_comi_pagado),2,0,0,1).'</b></td>
    </tr>
  </table></td>
  </tr>
  <tr><td colspan="5">&nbsp;</td></tr>
  <tr><td colspan="5">&nbsp;</td></tr>
  <tr>
    <td colspan="5">
  <table width="700"  align="center" cellpadding="0" cellspacing="0" border="0"> 
  
   ';
    /*<tr class="ewTableHeader">
      <td width="2%"><span> &nbsp; </span></td>
      <td width="5%" align="center" valign="middle" class="txt_negro_medio"><span> No </span></td>
      <td width="9%" align="center" valign="middle" class="txt_negro_medio"> Status </td>
      <td width="8%" align="center" valign="middle" class="txt_negro_medio"> Fecha </td>
      <td width="14%" align="center" valign="middle" class="txt_negro_medio"> Fecha de Pago </td>
      <td width="10%" align="center" valign="middle" class="txt_negro_medio"> Capital </td>
      <td width="9%" align="center" valign="middle" class="txt_negro_medio"> Importe </td>
      <td width="9%" align="center" valign="middle" class="txt_negro_medio"> Inter&eacute;s </td>
      <td width="12%" align="center" valign="middle" class="txt_negro_medio">IVA</td>
      <td width="12%" align="center" valign="middle" class="txt_negro_medio"> Moratorios </td>
      <td width="7%" align="center" valign="middle" class="txt_negro_medio">IVA M.</td>
      <td width="7%" align="center" valign="middle" class="txt_negro_medio"> Total </td>
    </tr>*/
    
$sqlFP = 	"SELECT forma_pago_id, penalizacion penalizacion, garantia_liquida FROM credito WHERE credito_id =  $x_credito_id";
$rsFP = phpmkr_query($sqlFP, $conn) or die ("Error al selccioanl la forma de pago". phpmkr_error()."sql:".$sqlFP);
$rowFP = phpmkr_fetch_array($rsFP);
$x_forma_pago_id = $rowFP["forma_pago_id"];
$x_penalizacion = $rowFP["penalizacion"];	
if (!empty($x_penalizacion) && $x_penalizacion > 0 ){ 
$x_respuesta16 .='
<table id="ewlistmain" class="ewTable" align="center">
  <tr class="ewTableHeader"><td colspan="14" class="txt_negro_medio"><center><strong>
  <h3>INTERESES MORATORIOS</h3></strong></center> </td></tr>
	<!-- Table header -->
	<tr >
<td width="37"><span>
&nbsp;
</span></td>
		<td width="71" valign="middle" class="txt_negro_medio">No</td>
		<td width="105" valign="middle" class="txt_negro_medio"><span>Status</span></td>
		<td width="104" valign="middle" class="txt_negro_medio"><span>Fecha</span></td>   
		<td width="64" valign="middle" class="txt_negro_medio"><span>Fecha de Pago</span></td>        
        <td width="9" valign="middle" class="txt_negro_medio">&nbsp;</td>				        
	  <td width="57" valign="middle" class="txt_negro_medio"><span>Ref. de Pago</span></td>				                
		<td width="39" valign="middle" class="txt_negro_medio"><span>Capital</span></td>				
		<td width="118" valign="middle" class="txt_negro_medio">Importe</td>
		<td width="111" valign="middle" class="txt_negro_medio">Inter&eacute;s</td>
		<td width="27" valign="middle" class="txt_negro_medio">IVA</td>
		<td width="130" valign="middle" class="txt_negro_medio"><span>Moratorio</span></td>
		<td width="32" valign="middle" class="txt_negro_medio">IVA M.</td>
		<td width="36" valign="middle" class="txt_negro_medio"><span>Total</span></td>						
	</tr>';


$sqlPenalizaciones = "SELECT * FROM vencimiento WHERE (vencimiento.credito_id = $x_credito_id) AND (vencimiento.vencimiento_num > 2000 and vencimiento.vencimiento_num < 3000) order by vencimiento_num asc  ";

//Secho $sqlPenalizaciones;
$rsP = phpmkr_query($sqlPenalizaciones, $conn) or die ("Error".phpmkr_error()."sql".$sqlPenalizaciones);
while (($row = @phpmkr_fetch_array($rsP))) {
	$nRecCount = $nRecCount + 1;
	if ($nRecCount >= $nStartRec) {
		$nRecActual++;
//echo "entra en penaliazciones";
		// Set row color
		$sItemRowClass = " class=\"ewTableRow\"";
		$sListTrJs = " onmouseover='ew_mouseover(this);' onmouseout='ew_mouseout(this);' onclick='ew_click(this);'";

		// Display alternate color for rows
		if ($nRecCount % 2 <> 1) {
			$sItemRowClass = " class=\"ewTableAltRow\"";
		}
		$x_vencimiento_id = $row["vencimiento_id"];
		//echo "vencimeinto id". $x_vencimiento_id;
		$x_vencimiento_num = $row["vencimiento_num"];		
		$x_credito_id = $row["credito_id"];
		$x_vencimiento_status_id = $row["vencimiento_status_id"];


		
		$x_fecha_vencimiento = $row["fecha_vencimiento"];
		$x_importe = $row["importe"];
		$x_interes = $row["interes"];
		$x_iva = $row["iva"];		
		$x_iva_mor = $row["iva_mor"];	
		$x_fecha_remanente = $row["fecha_genera_remanente"];
		if(empty($x_iva)){
			$x_iva = 0;
		}		
		if(empty($x_iva_mor)){
			$x_iva_mor = 0;
		}		
		
		$x_interes_moratorio = $row["interes_moratorio"];
		//Secho $x_interes_moratorio."<br>";
		
		$x_total = $x_importe + $x_interes + $x_interes_moratorio + $x_iva + $x_iva_mor;
 //echo "total venci".$x_total."<br>";

		$x_total_pagos = $x_total_pagos + $x_importe;
		$x_total_interes = $x_total_interes + $x_interes;
		$x_total_iva = $x_total_iva + $x_iva;		
		$x_total_iva_mor = $x_total_iva_mor + $x_iva_mor;				
		$x_total_moratorios = $x_total_moratorios + $x_interes_moratorio;
		$x_total_total = $x_total_total + $x_total;
		
		if(($x_vencimiento_status_id == 2) || ($x_vencimiento_status_id == 5)){
		
			$sSqlWrk = "SELECT fecha_pago, referencia_pago_2 FROM recibo join recibo_vencimiento on recibo_vencimiento.recibo_id = recibo.recibo_id where recibo_vencimiento.vencimiento_id = $x_vencimiento_id and recibo.recibo_status_id = 1";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$rowwrk = phpmkr_fetch_array($rswrk);
			$x_fecha_pago = $rowwrk["fecha_pago"];
			$x_referencia_pago2 = $rowwrk["referencia_pago_2"];			

			@phpmkr_free_result($rswrk);

			$x_total_pagos_a = $x_total_pagos_a + $x_importe;
			$x_total_interes_a = $x_total_interes_a + $x_interes;
			$x_total_iva_a = $x_total_iva_a + $x_iva;			
			$x_total_iva_mor_a = $x_total_iva_mor_a + $x_iva_mor;						
			$x_total_moratorios_a = $x_total_moratorios_a + $x_interes_moratorio;
			$x_total_total_a = $x_total_total_a + $x_total;
			

		}else{
			$x_fecha_pago  = "";
			$x_referencia_pago2 = "";						

			$x_total_pagos_d = $x_total_pagos_d + $x_importe;
			$x_total_interes_d = $x_total_interes_d + $x_interes;
			$x_total_iva_d = $x_total_iva_d + $x_iva;			
			$x_total_iva_mor_d = $x_total_iva_mor_d + $x_iva_mor;						
			$x_total_moratorios_d = $x_total_moratorios_d + $x_interes_moratorio;
			$x_total_total_d = $x_total_total_d + $x_total;
			
			if($x_vencimiento_status_id == 3){
				$x_total_pagos_v = $x_total_pagos_v + $x_importe;
				$x_total_interes_v = $x_total_interes_v + $x_interes;
				$x_total_iva_v = $x_total_iva_v + $x_iva;			
				$x_total_iva_mor_v = $x_total_iva_mor_v + $x_iva_mor;						
				$x_total_moratorios_v = $x_total_moratorios_v + $x_interes_moratorio;
				$x_total_total_v = $x_total_total_v + $x_total;
			}
			
		}
		

		

		$x_ref_loc = str_pad($x_vencimiento_id, 5, "0", STR_PAD_LEFT)."/".str_pad($x_vencimiento_num, 2, "0", STR_PAD_LEFT);
		
$x_respuesta16 .= "
	
	<tr><td><span class=\"phpmaker\"></span></td>
		<!-- vencimiento_id -->
		<td align=\"right\" class=\"txt_datos_azul\"><span> ".$x_vencimiento_num."</span></td>
		<td align=\"right\" class=\"txt_datos_azul\"><span>";
if ((!is_null($x_vencimiento_status_id)) && ($x_vencimiento_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `vencimiento_status`";
	$sTmp = $x_vencimiento_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `vencimiento_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_vencimiento_status_id = $x_vencimiento_status_id; // Backup Original Value
$x_vencimiento_status_id = $sTmp;
if($x_vencimiento_status_id == "MORATORIOS"){
	$x_vencimiento_status_id = "PENDIENTE";
	}

$x_respuesta16 .= $x_vencimiento_status_id;
 $x_vencimiento_status_id = $ox_vencimiento_status_id; // Restore Original Value 

$x_respuesta16 .= "</span></td>
		<!-- fecha_vencimiento -->
		<td align=\"right\" class=\"txt_datos_azul\">".FormatDateTime($x_fecha_vencimiento,7)."</td>
		<td align=\"right\" class=\"txt_datos_azul\">".FormatDateTime($x_fecha_pago,7)."</td>
<td align=\"center\">&nbsp;</td> <td align=\"right\" class=\"txt_datos_azul\">".$x_referencia_pago2."</td>
		<td align=\"right\">&nbsp;</td>
		<!-- importe -->
		<td align=\"right\">&nbsp;</td>
		<!-- interes -->
		<td align=\"right\"> </td>
		<td align=\"right\">&nbsp;</td>
		<!-- interes_moratorio -->
		<td align=\"right\" class=\"txt_datos_azul \"><span>".FormatNumber($x_interes_moratorio,2,0,0,1)." </span></td>
		<td align=\"right\" class=\"txt_datos_azul \">".FormatNumber($x_iva_mor,2,0,0,1)."</td>
		<td align=\"right\" class=\"txt_datos_azul \"><span>".FormatNumber($x_total,2,0,0,1)."</span></td>
	</tr>";

$x_saldo = $x_saldo - $x_importe;
	}
}

}// termina penalizacion //


// comison de cobranza

$sqlComsion = "SELECT vencimiento.*, credito.fecha_genera_comision as fecha_comision FROM vencimiento join credito on credito.credito_id = vencimiento.credito_id WHERE (vencimiento.credito_id = $x_credito_id) AND (vencimiento.vencimiento_num >= 3000) ";
$rsc = phpmkr_query($sqlComsion, $conn) or die ("Error".phpmkr_error()."sql".$sqlComsion);
$x_no_comisiones = mysql_num_rows($rsc);

if($x_no_comisiones > 0 ){

$x_respuesta16 .= "</p>
<table id=\"ewlistmain\" class=\"ewTable\" align=\"center\">
	<!-- Table header -->
	<tr class=\"ewTableHeader\">
<td colspan=\"5\" class=\"txt_negro_medio\"><center><strong>
<h3>COMISI&Oacute;N POR GASTOS DE COBRANZA LEGAL</h3></strong></center>
 </td>
	</tr>
    <tr>
<td width=\"53\"> </td>
<td width=\"176\" class=\"txt_negro_medio\">Se gener&oacute;</td>		
		<td width=\"177\"class=\"txt_negro_medio\">Monto</td>		
		<td width=\"244\" align=\"left\" class=\"txt_negro_medio\">Status</td>
	    <td width=\"326\" align=\"center\" class=\"txt_negro_medio\">Fecha limite de pago</td>
		</tr>";




$sqlComsion = "SELECT vencimiento.*, credito.fecha_genera_comision as fecha_comision FROM vencimiento join credito on credito.credito_id = vencimiento.credito_id WHERE (vencimiento.credito_id = $x_credito_id) AND (vencimiento.vencimiento_num >= 3000) ";
$rsc = phpmkr_query($sqlComsion, $conn) or die ("Error".phpmkr_error()."sql".$sqlComsion);
//echo "sql :".$sqlComsion;
while (($rowc = @phpmkr_fetch_array($rsc))) {
	$nRecCount = $nRecCount + 1;
	if ($nRecCount >= $nStartRec) {
		$nRecActual++;
		// Set row color
		$sItemRowClass = " class=\"ewTableRow\"";
		$sListTrJs = " onmouseover='ew_mouseover(this);' onmouseout='ew_mouseout(this);' onclick='ew_click(this);'";
		//echo "vencimeinto id". $x_vencimiento_id;
		$x_vencimiento_idc = $rowc["vencimiento_id"];
		$x_importec = $rowc["importe"];
		
		$x_interes_moratorio = $rowc["interes_moratorio"];
		$x_iva_mor = $rowc["iva_mor"];
		$x_importec = $x_interes_moratorio + $x_iva_mor;
		#echo "vencimeinto id". $x_vencimiento_idc;
		$x_vencimiento_numc = $row["vencimiento_num"];		
		$x_credito_idc = $rowc["credito_id"];
		$x_vencimiento_status_idc = $rowc["vencimiento_status_id"];
		$x_fecha_vencimientoc = $rowc["fecha_vencimiento"];
		$x_fecha_comision = $rowc["fecha_comision"];
		//$x_importec = $rowc["importe"];
		$x_interesc = $rowc["interes"];
		$x_ivac = $rowc["iva"];		
		$x_iva_morc = $rowc["iva_mor"];	
		$x_fecha_remanentec = $rowc["fecha_genera_remanente"];
		if(empty($x_iva)){
			$x_iva = 0;
		}		
		if(empty($x_iva_mor)){
			$x_iva_mor = 0;
		}		
		$x_ref_loc = str_pad($x_vencimiento_idc, 5, "0", STR_PAD_LEFT)."/".str_pad($x_vencimiento_numc, 2, "0", STR_PAD_LEFT);

		
?>
	<!-- Table body -->
	
<?php
$x_saldo = $x_saldo - $x_importe;
	}

$x_respuesta16 .= "
<tr>
<td>";
$x_respuesta16 .= "</span></td>
<td  class=\"txt_datos_azul\">". FormatDateTime($x_fecha_comision,7)."</td>
<td  class=\"txt_datos_azul\">". FormatNumber($x_importec,2,0,0,1)."</td>
<td  class=\"txt_datos_azul\">";
if ((!is_null($x_vencimiento_status_idc)) && ($x_vencimiento_status_idc <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `vencimiento_status`";
	$sTmp = $x_vencimiento_status_idc;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `vencimiento_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_vencimiento_status_id = $x_vencimiento_status_id; // Backup Original Value
$x_vencimiento_status_id = $sTmp;
if($x_vencimiento_status_id == "COMISION"){
	$x_vencimiento_status_id = "PENDIENTE";
	}
$x_respuesta16 .= $x_vencimiento_status_id;
$x_vencimiento_status_id = $ox_vencimiento_status_id; // Restore Original Value 

$x_respuesta16 .="</td><td   align=\"center\" class=\"txt_datos_azul\"<span>".FormatDateTime($x_fecha_vencimientoc,7)."</span></td>
</tr></table> ";
 }
}// fin comision

$sqlGrantia = "SELECT garantia_liquida.*, garantia_liquida_status.descripcion, garantia_liquida_status.detalle FROM garantia_liquida JOIN garantia_liquida_status ON garantia_liquida_status.garantia_liquida_status_id = garantia_liquida.status  WHERE (credito_id = $x_credito_id)   ";
$rs = phpmkr_query($sqlGrantia, $conn) or die ("Error".phpmkr_error()."sql".$sqlPenalizaciones);
while (($row = @phpmkr_fetch_array($rs))) {
	$nRecCount = $nRecCount + 1;
	if ($nRecCount >= $nStartRec) {
		$nRecActual++;

		// Set row color
		$sItemRowClass = " class=\"ewTableRow\"";
		$sListTrJs = " onmouseover='ew_mouseover(this);' onmouseout='ew_mouseout(this);' onclick='ew_click(this);'";

		// Display alternate color for rows
		if ($nRecCount % 2 <> 1) {
			$sItemRowClass = " class=\"ewTableAltRow\"";
		}
		$x_garantia_liquida_id = $row["garantia_liquida_id"];
		//echo "vencimeinto id". $x_vencimiento_id;
		$x_monto = $row["monto"];		
		$x_fecha = $row["fecha"];
		$x_status = $row["descripcion"];
		$x_detalle = $row["detalle"];


		
		$x_fecha_vencimiento = $row["fecha_vencimiento"];
		$x_importe = $row["importe"];
		$x_interes = $row["interes"];
		$x_iva = $row["iva"];		
		$x_iva_mor = $row["iva_mor"];	
		$x_fecha_remanente = $row["fecha_genera_remanente"];
		if(empty($x_iva)){
			$x_iva = 0;
		}		
		if(empty($x_iva_mor)){
			$x_iva_mor = 0;
		}		
		

		
?>
	<!-- Table body -->
	
<?php
$x_saldo = $x_saldo - $x_importe;
	}
}

$x_respuesta16.= "<table id=\"ewlistmain\" class=\"ewTable\" align=\"center\">
	
	<tr class=\"ewTableHeader\">
<td colspan=\"4\" class=\"txt_negro_medio\"><center><strong>
<h3>GARANT&Iacute;A L&Iacute;QUIDA</h3></strong></center>
 </td>
	</tr>
    <tr>
<!--
<td><span class=\"phpmaker\"></span></td>
--->
<td width=\"114\" class=\"txt_negro_medio\">Fecha en que se gener&oacute;</td>
		<!-- vencimiento_id -->		<!-- credito_id -->
		<!-- vencimiento_status_id -->
		<td width=\"169\" class=\"txt_negro_medio\">Monto</td>
		<!-- fecha_vencimiento -->
		<td width=\"236\" align=\"left\" class=\"txt_negro_medio\">Status</td>
	  <td align=\"center\" class=\"txt_negro_medio\">Detalle</td>
	</tr>";	
$x_respuesta16.="	<tr>
<td class=\"txt_datos_azul\">".FormatDateTime($x_fecha,7)."</td>
<td class=\"txt_datos_azul\">".FormatNumber($x_monto,2,0,0,1)."</td>
<td class=\"txt_datos_azul\">". $x_status."</td>
<td align=\"center\" class=\"txt_datos_azul\">". $x_detalle."</td>
</tr>
</table>";





 
    /*<tr class="ewTableHeader">
      <td width="2%"><span> &nbsp; </span></td>
      <td width="5%" align="center" valign="middle" class="txt_negro_medio"><span> No </span></td>
      <td width="9%" align="center" valign="middle" class="txt_negro_medio"> Status </td>
      <td width="8%" align="center" valign="middle" class="txt_negro_medio"> Fecha </td>
      <td width="14%" align="center" valign="middle" class="txt_negro_medio"> Fecha de Pago </td>
      <td width="10%" align="center" valign="middle" class="txt_negro_medio"> Capital </td>
      <td width="9%" align="center" valign="middle" class="txt_negro_medio"> Importe </td>
      <td width="9%" align="center" valign="middle" class="txt_negro_medio"> Inter&eacute;s </td>
      <td width="12%" align="center" valign="middle" class="txt_negro_medio">IVA</td>
      <td width="12%" align="center" valign="middle" class="txt_negro_medio"> Moratorios </td>
      <td width="7%" align="center" valign="middle" class="txt_negro_medio">IVA M.</td>
      <td width="7%" align="center" valign="middle" class="txt_negro_medio"> Total </td>
    </tr>*/
    


$sSqlWrk = "SELECT importe FROM credito where credito_id = $x_solcre_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
$x_saldo = $datawrk["importe"];
@phpmkr_free_result($rswrk);

$x_total = 0;
$x_total_pagos = 0;
$x_total_interes = 0;
$x_total_iva = 0;
$x_total_moratorios = 0;
$x_total_iva_moratorios = 0;
$x_total_total = 0;

$x_total_d = 0;
$x_total_pagos_d = 0;
$x_total_interes_d = 0;

$x_total_iva_d = 0;
$x_total_moratorios_d = 0;
$x_total_iva_moratorios_d = 0;
$x_total_total_d = 0;

//NUEVO
$x_total_pagos_v = 0;
$x_total_interes_v = 0;
$x_total_iva_v = 0;
$x_total_iva_mor_v = 0;
$x_total_moratorios_v = 0;
$x_total_total_v = 0;


$x_total_a = 0;
$x_total_pagos_a = 0;
$x_total_interes_a = 0;
$x_total_iva_a = 0;
$x_total_moratorios_a = 0;
$x_total_iva_moratorios_a = 0;
$x_total_total_a = 0;



    $x_respuesta21=' <tr>
      <td >&nbsp;</td>
      <td >&nbsp;</td>
      <td >&nbsp;</td>
      <td ><span>&nbsp;</span></td>
      <td ><span>&nbsp;</span></td>
      <td ><span>&nbsp;</span></td>
      <td ></td>
      <td ></td>
      <td ></td>
      <td ></td>
      <td ></td>
      <td ></td>
    </tr>
 
  </table>
  
  
  </td></tr>
     <table width="700" align="center">
<tr>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
</tr>
<tr>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
</tr>

 
<tr>
  <td colspan="5" class="txt_negro_medio"><h4>Solicitamos su pago a la brevedad, para mayores informes comuniquese a nuestra linea de atencion a clientes en el D.F al 51350259 en el interior de la republica a la linea sin consto 018008376133.</h4></td>
</tr>
</table>';

$x_credito_id = $x_solcre_id;
$x_today = date("Y-m-d");
$x_f_p = explode("-",$x_today);
$x_dia = $x_f_p[2];
$x_mes = $x_f_p[1];
$x_anio = $x_f_p[0];
#echo "dia ".$x_dia."mes ".$x_mes."año ".$x_anio."<br>";

// seleccionamos el ultimo dia del mes que corresponde a la fecha actual
$x_ultimo_dia_mes_f = strftime("%d", mktime(0, 0, 0, $x_mes+1, 0, $x_anio));
#echo "yultimo dia de mes". $x_ultimo_dia_mes_f."<br>";

$x_fecha_inicio_mes = $x_anio."-".$x_mes."-01";
$x_fecha_fin_mes = $x_anio."-".$x_mes."-".$x_ultimo_dia_mes_f; 
#echo "incio mes".$x_fecha_inicio_mes."<br>";
#echo "fin mes".$x_fecha_fin_mes."<br>";

$sqlSaldo = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and  fecha_vencimiento <= '$x_fecha_fin_mes' and vencimiento_status_id in (1,3,6,7) ";
$rsSaldo = phpmkr_query($sqlSaldo, $conn) or die ("error en saldos". phpmkr_error()."sql:".$sqlSaldo);
$x_total_liquida_con_interes = 0;
$x_lista_con_interes = "";
while ($rowSaldo = phpmkr_fetch_array($rsSaldo)){
	#echo "entra";
	#echo $rowSaldo["vencimiento_id"]."<br>";
	$x_total_liquida_con_interes = $x_total_liquida_con_interes + $rowSaldo["total_venc"];
	$x_lista_con_interes = $x_lista_con_interes.$rowSaldo["vencimiento_num"].", ";
	}
	$x_lista_con_interes = trim($x_lista_con_interes,", ");
$sqlSaldo2 = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and  fecha_vencimiento > '$x_fecha_fin_mes' and vencimiento_status_id in (1,3,6) ";
$rsSaldo2 = phpmkr_query($sqlSaldo2, $conn) or die ("error en saldos". phpmkr_error()."sql:".$sqlSaldo2);
$x_total_liquida_sin_interes = 0;
$x_total_interes_i = 0;
$x_total_iva_i = 0;
$x_lista_sin_interes ="";
while ($rowSaldo2 = phpmkr_fetch_array($rsSaldo2)){
	#echo "entra";
	#echo $rowSaldo["vencimiento_id"]."<br>";
	$x_total_liquida_sin_interes = $x_total_liquida_sin_interes + $rowSaldo2["total_venc"];
	$x_total_interes_i = $x_total_interes_i + $rowSaldo2["interes"];
	$x_total_iva_i = $x_total_iva_i + $rowSaldo2["iva"];
	$x_lista_sin_interes = $x_lista_sin_interes.$rowSaldo2["vencimiento_num"].", ";
	}	
$x_lista_sin_interes = trim($x_lista_sin_interes,", ");	
$sqlSaldo3 = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and  fecha_vencimiento > '$x_fecha_fin_mes' and vencimiento_status_id in (7) ";
$rsSaldo3 = phpmkr_query($sqlSaldo3, $conn) or die ("error en saldos". phpmkr_error()."sql:".$sqlSaldo3);
$x_total_penalizaciones = 0;
$x_lista_penalizaciones = "";
while ($rowSaldo3 = phpmkr_fetch_array($rsSaldo3)){
	#echo "entra";
	#echo $rowSaldo["vencimiento_id"]."<br>";
	$x_total_penalizaciones = $x_total_penalizaciones + $rowSaldo3["total_venc"];	
	$x_lista_penalizaciones = $x_lista_penalizaciones.$rowSaldo3["vencimiento_num"].", ";
	}	
	$x_lista_penalizaciones = trim($x_lista_penalizaciones, ", ");
#echo "total_liqui con interes".$x_total_liquida_con_interes."<br>";
#echo "total_pendiente".$x_total_liquida_sin_interes."<br>";
#echo "total interes pendiente". $x_total_interes_i."<br>";
#echo "total_iva_pendiente".$x_total_iva_i."<br>";

$x_total_liquida_sin_interes = $x_total_liquida_sin_interes -$x_total_interes_i -$x_total_iva_i;
#echo "total_pendiente".$x_total_liquida_sin_interes."<br>";
$x_fecha_de_vigencia = $x_fecha_fin_mes;
$x_saldo_a_liquidar = $x_total_liquida_con_interes +  $x_total_liquida_sin_interes+ $x_total_penalizaciones;


# seleccionamos la forma de pago 
$sqlFormaPago = "SELECT forma_pago_id FROM credito WHERE credito_id = $x_credito_id ";
$rsFormaPago = phpmkr_query($sqlFormaPago, $conn) or die ("Error al seleccionar".phpmkr_error()."sql:".$sqlFormaPago);
$rowFormaPago = phpmkr_fetch_array($rsFormaPago);
$x_forma_pago_id = $rowFormaPago["forma_pago_id"];


$x_respuesta22= '<br style="page-break-before:always" clear="all"> 
<table align="center" width="700" border="0"  id="ewlistmain" class="ewTable" style="font-family:Verdana, Geneva, sans-serif; font-size:12px; border-bottom-color:#CCCCCC;text-align:justify" >
<tr>
<td><strong> Comentarios Internos</strong> Credito No.<strong>'. $x_credito_num.'</strong></td>

<td>  </td>

</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>'. $x_comentario_int.'</td>
<td></td>
</tr>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td><strong> Comentarios Externos</strong></td>
<td></td>
</tr>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>'.$x_comentario_ext.'</td>';
$x_respuesta23= '<td></td>

</tr>
</table>';
 if ($x_forma_pago_id == 3){ 
 $x_respuesta24= '<table   align="center" width="700" border="0" id="ewlistmain" class="ewTable" style="font-family:Verdana, Geneva, sans-serif; font-size:12px; border-bottom-color:#CCCCCC">
  <tr>
    <td colspan="3"><strong>LIQUIDACI&Oacute;N DE CR&Eacute;DITO</strong></td>
    </tr>
  <tr>
    <td width="493" >Fecha de vigencia</td>
    <td colspan="2">'. FormatDateTime($x_fecha_de_vigencia,7).'</td>
    </tr>
  <tr>
    <td>Monto para liquidar</td>
    <td colspan="2"><strong>'.FormatNumber($x_saldo_a_liquidar,2,0,0,1).'</strong></td>
    </tr>
     <tr>
    <td>Detalle</td>
    <td width="374" >Vencimientos</td>
    <td width="119" >Saldo</td>
  </tr>
  <tr>
    <td>Vencimientos pendientes con interes</td>
    <td>'. $x_lista_con_interes.'</td>
    <td  align="right">'. FormatNumber($x_total_liquida_con_interes,2,0,0,1).'</td>
  </tr>
  <tr>
    <td>Vencimientos pendientes sin interes</td>
    <td>'. $x_lista_sin_interes.'</td>
    <td align="right">'. FormatNumber($x_total_liquida_sin_interes,2,0,0,1).'</td>
  </tr>
  <tr>
    <td>Moratorios</td>
    <td>'.$x_lista_penalizaciones.'</td>
    <td align="right">'. FormatNumber($x_total_penalizaciones,2,0,0,1).'</td>
  </tr>
  <tr>
    <td colspan="2" align="right">Total: </td>
    <td align="right"><strong>'. FormatNumber($x_saldo_a_liquidar,2,0,0,1).'</strong></td>
  </tr>
</table>';

 }
 $x_respuesta26='<br style="page-break-before:always" clear="all">';

//echo "respuesta dos -----".$x_respuesta2;
//echo "respuesta tres -----".$x_respuesta3;
//echo "respuesta cuatros -----".$x_respuesta4;

$sqlVencidos = "SELECT vencimiento_num FROM vencimiento WHERE credito_id = $x_credito_id AND vencimiento_status_id = 3 ORDER BY vencimiento_num DESC LIMIT 0,1 ";
$rsVencidos = phpmkr_query($sqlVencidos,$conn) or die ("Error al seeleccionar el ultimo encimiento vencido".phpmkr_error()."sql:". $sqlVencidos);
$rowVencidos = phpmkr_fetch_array($rsVencidos);
$x_no_referencia_ven = $rowVencidos["vencimiento_num"];
if(!empty($x_no_referencia_ven)){
	// si existe uno o mas vencimientos vencido se toma el ultimo como numero de referencia
	// y buscamos el que le sigue que estar pendiente y se quedara cmo numero de referencia
	$sqlPendiente = "SELECT vencimiento_num, fecha_vencimiento FROM vencimiento WHERE credito_id = $x_credito_id AND vencimiento_status_id = 1 AND vencimiento_num > $x_no_referencia_ven  ORDER BY vencimiento_num ASC LIMIT 0,1 ";
	$rsPendiente = phpmkr_query($sqlPendiente,$conn) or die ("Error al seeleccionar el ultimo encimiento vencido".phpmkr_error()."sql:". $sqlPendiente);
	$rowPendiente = phpmkr_fetch_array($rsPendiente);
	$x_no_referencia_ven = $rowPendiente["vencimiento_num"];
	$x_fecha_referencia = $rowPendiente["fecha_vencimiento"];	
	}else{
		//si numeor de referencia esta vencido entonces se toma como numero de referenia el primer vencimiento pendiente
			$sqlPendiente = "SELECT vencimiento_num, fecha_vencimiento FROM vencimiento WHERE credito_id = $x_credito_id AND vencimiento_status_id = 1 ORDER BY vencimiento_num ASC LIMIT 0,1 ";
	$rsPendiente = phpmkr_query($sqlPendiente,$conn) or die ("Error al seeleccionar el ultimo encimiento vencido".phpmkr_error()."sql:". $sqlPendiente);
	$rowPendiente = phpmkr_fetch_array($rsPendiente);
	$x_no_referencia_ven = $rowPendiente["vencimiento_num"];
	$x_fecha_referencia = $rowPendiente["fecha_vencimiento"];		
			
		}


$x_today = date("Y-m-d");
$x_f_p = explode("-",$x_today);
$x_dia = $x_f_p[2];
$x_mes = $x_f_p[1];
$x_anio = $x_f_p[0];
#echo "dia ".$x_dia."mes ".$x_mes."año ".$x_anio."<br>";

// seleccionamos el ultimo dia del mes que corresponde a la fecha actual
$x_ultimo_dia_mes_f = strftime("%d", mktime(0, 0, 0, $x_mes+1, 0, $x_anio));
#echo "yultimo dia de mes". $x_ultimo_dia_mes_f."<br>";

$x_fecha_inicio_mes = $x_anio."-".$x_mes."-01";
$x_fecha_fin_mes = $x_anio."-".$x_mes."-".$x_ultimo_dia_mes_f; 
#echo "incio mes".$x_fecha_inicio_mes."<br>";
#echo "fin mes".$x_fecha_fin_mes."<br>";
if(!empty($x_no_referencia_ven)){
$sqlSaldo = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and  vencimiento_num <= $x_no_referencia_ven and vencimiento_status_id in (1,3,6,7) order by  vencimiento_num asc";
$rsSaldo = phpmkr_query($sqlSaldo, $conn) or die ("error en saldos". phpmkr_error()."sql:".$sqlSaldo);
$x_total_liquida_con_interes = 0;
$x_lista_con_interes = "";
while ($rowSaldo = phpmkr_fetch_array($rsSaldo)){
	#echo "entra";
	#echo $rowSaldo["vencimiento_id"]."<br>";
	$x_total_liquida_con_interes = $x_total_liquida_con_interes + $rowSaldo["total_venc"];
	$x_lista_con_interes = $x_lista_con_interes.$rowSaldo["vencimiento_num"].", ";
	}
}
	$x_lista_con_interes = trim($x_lista_con_interes,", ");
	if(!empty($x_no_referencia_ven)){
$sqlSaldo2 = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and   vencimiento_num > $x_no_referencia_ven and vencimiento_num < 2000 and vencimiento_status_id in (1,3,6) order by  vencimiento_num asc";
$rsSaldo2 = phpmkr_query($sqlSaldo2, $conn) or die ("error en saldos". phpmkr_error()."sql:".$sqlSaldo2);
	
$x_total_liquida_sin_interes = 0;
$x_total_interes_i = 0;
$x_total_iva_i = 0;
$x_lista_sin_interes ="";
while ($rowSaldo2 = phpmkr_fetch_array($rsSaldo2)){
	#echo "entra";
	#echo $rowSaldo["vencimiento_id"]."<br>";
	$x_total_liquida_sin_interes = $x_total_liquida_sin_interes + $rowSaldo2["total_venc"];
	$x_total_interes_i = $x_total_interes_i + $rowSaldo2["interes"];
	$x_total_iva_i = $x_total_iva_i + $rowSaldo2["iva"];
	$x_lista_sin_interes = $x_lista_sin_interes.$rowSaldo2["vencimiento_num"].", ";
	}	
}
$x_lista_sin_interes = trim($x_lista_sin_interes,", ");	
if(!empty($x_no_referencia_ven)){
$sqlSaldo3 = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and   vencimiento_num > $x_no_referencia_ven and vencimiento_num >= 2000 and vencimiento_status_id in (1,3,6) order by  vencimiento_num asc";
$rsSaldo3 = phpmkr_query($sqlSaldo3, $conn) or die ("error en saldos". phpmkr_error()."sql:".$sqlSaldo3);
#echo "sal penalizaciones".$sqlSaldo3;
$x_total_penalizaciones = 0;
$x_lista_penalizaciones = "";
while ($rowSaldo3 = phpmkr_fetch_array($rsSaldo3)){
	#echo "entra";
	#echo $rowSaldo["vencimiento_id"]."<br>";
	$x_total_penalizaciones = $x_total_penalizaciones + $rowSaldo3["total_venc"];	
	$x_lista_penalizaciones = $x_lista_penalizaciones.$rowSaldo3["vencimiento_num"].", ";
	}	
}
	$x_lista_penalizaciones = trim($x_lista_penalizaciones, ", ");
#echo "total_liqui con interes".$x_total_liquida_con_interes."<br>";
#echo "total_pendiente".$x_total_liquida_sin_interes."<br>";
#echo "total interes pendiente". $x_total_interes_i."<br>";
#echo "total_iva_pendiente".$x_total_iva_i."<br>";

$x_total_liquida_sin_interes = $x_total_liquida_sin_interes -$x_total_interes_i -$x_total_iva_i;
#echo "total_pendiente".$x_total_liquida_sin_interes."<br>";
$x_fecha_de_vigencia = $x_fecha_referencia ;
$x_saldo_a_liquidar = $x_total_liquida_con_interes +  $x_total_liquida_sin_interes+ $x_total_penalizaciones;

$sqlFormaPago = "SELECT forma_pago_id, penalizacion FROM credito WHERE credito_id = $x_credito_id ";
$rsFormaPago = phpmkr_query($sqlFormaPago, $conn) or die ("Error al seleccionar".phpmkr_error()."sql:".$sqlFormaPago);
$rowFormaPago = phpmkr_fetch_array($rsFormaPago);
$x_forma_pago_id = $rowFormaPago["forma_pago_id"];
$x_penalizacion = $rowFormaPago["penalizacion"];
$x_garantia_liquida = $rowFormaPago["garantia_liquida"];



 if ($x_penalizacion > 0){ 
$x_penalizacion = '
<table align="center" width="700" border="0" id="ewlistmain" class="ewTable" style="font-family:Verdana, Geneva, sans-serif; font-size:12px">
  <tr>
    <td colspan="3"><strong>LIQUIDACI&Oacute;N DE CR&Eacute;DITO ANTICIPADA</strong></td>
    </tr>
  <tr>
    <td width="493" >Fecha de vigencia</td>
    <td colspan="2">'.FormatDateTime($x_fecha_referencia,7).'</td>
    </tr>
  <tr>
    <td>Monto para liquidar</td>
    <td colspan="2"><strong>'. FormatNumber($x_saldo_a_liquidar,2,0,0,1).'</strong></td>
    </tr>
     <tr>
    <td>Detalle</td>
    <td width="374" >Vencimientos</td>
    <td width="119" >Saldo</td>
  </tr>
  <tr>
    <td>Vencimientos pendientes con interes</td>
    <td>'.$x_lista_con_interes.'</td>
    <td  align="right">'.FormatNumber($x_total_liquida_con_interes,2,0,0,1).'</td>
  </tr>
  <tr>
    <td>Vencimientos pendientes sin interes</td>
    <td>'.$x_lista_sin_interes.'</td>
    <td align="right">'. FormatNumber($x_total_liquida_sin_interes,2,0,0,1).'</td>
  </tr>
  <tr>
    <td>Moratorios</td>
    <td>'. $x_lista_penalizaciones.'</td>
    <td align="right">'.FormatNumber($x_total_penalizaciones,2,0,0,1).'</td>
  </tr>
  <tr>
    <td colspan="2" align="right">Total: </td>
    <td align="right"><strong>'.FormatNumber($x_saldo_a_liquidar,2,0,0,1).'</strong></td>
  </tr>
</table>
<p>';



 if (!empty($x_penalizacion) && $x_penalizacion > 0){ 

$sqlVencidos = "SELECT vencimiento_num FROM vencimiento WHERE credito_id = $x_credito_id AND vencimiento_status_id = 3 ORDER BY vencimiento_num DESC LIMIT 0,1 ";
$rsVencidos = phpmkr_query($sqlVencidos,$conn) or die ("Error al seeleccionar el ultimo encimiento vencido".phpmkr_error()."sql:". $sqlVencidos);
$rowVencidos = phpmkr_fetch_array($rsVencidos);
$x_no_referencia_ven = $rowVencidos["vencimiento_num"];
if(!empty($x_no_referencia_ven)){
	// si existe uno o mas vencimientos vencido se toma el ultimo como numero de referencia
	// y buscamos el que le sigue que estar pendiente y se quedara cmo numero de referencia
	$sqlPendiente = "SELECT vencimiento_num FROM vencimiento WHERE credito_id = $x_credito_id AND vencimiento_status_id = 1 AND vencimiento_num > $x_no_referencia_ven  ORDER BY vencimiento_num ASC LIMIT 0,1 ";
	$rsPendiente = phpmkr_query($sqlPendiente,$conn) or die ("Error al seeleccionar el ultimo encimiento vencido".phpmkr_error()."sql:". $sqlPendiente);
	$rowPendiente = phpmkr_fetch_array($rsPendiente);
	$x_no_referencia_ven = $rowPendiente["vencimiento_num"];	
	}else{
		//si numeor de referencia esta vencido entonces se toma como numero de referenia el primer vencimiento pendiente
			$sqlPendiente = "SELECT vencimiento_num FROM vencimiento WHERE credito_id = $x_credito_id AND vencimiento_status_id = 1 ORDER BY vencimiento_num ASC LIMIT 0,1 ";
	$rsPendiente = phpmkr_query($sqlPendiente,$conn) or die ("Error al seeleccionar el ultimo encimiento vencido".phpmkr_error()."sql:". $sqlPendiente);
	$rowPendiente = phpmkr_fetch_array($rsPendiente);
	$x_no_referencia_ven = $rowPendiente["vencimiento_num"];			
		}
		
		
$x_today = date("Y-m-d");
$x_f_p = explode("-",$x_today);
$x_dia = $x_f_p[2];
$x_mes = $x_f_p[1];
$x_anio = $x_f_p[0];
#echo "dia ".$x_dia."mes ".$x_mes."año ".$x_anio."<br>";

// seleccionamos el ultimo dia del mes que corresponde a la fecha actual
$x_ultimo_dia_mes_f = strftime("%d", mktime(0, 0, 0, $x_mes+1, 0, $x_anio));
#echo "yultimo dia de mes". $x_ultimo_dia_mes_f."<br>";

$x_fecha_inicio_mes = $x_anio."-".$x_mes."-01";
$x_fecha_fin_mes = $x_anio."-".$x_mes."-".$x_ultimo_dia_mes_f; 
#echo "incio mes".$x_fecha_inicio_mes."<br>";
#echo "fin mes".$x_fecha_fin_mes."<br>";

if(!empty($x_no_referencia_ven)){
$sqlSaldo = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and  vencimiento_num <= $x_no_referencia_ven and vencimiento_status_id in (1,3,6,7) ";
$rsSaldo = phpmkr_query($sqlSaldo, $conn) or die ("error en saldos". phpmkr_error()."sql:".$sqlSaldo);

$x_total_liquida_con_interes = 0;
$x_lista_con_interes = "";
while ($rowSaldo = phpmkr_fetch_array($rsSaldo)){
	#echo "entra";
	#echo $rowSaldo["vencimiento_id"]."<br>";
	$x_total_liquida_con_interes = $x_total_liquida_con_interes + $rowSaldo["total_venc"];
	$x_lista_con_interes = $x_lista_con_interes.$rowSaldo["vencimiento_num"].", ";
	}
}
	$x_lista_con_interes = trim($x_lista_con_interes,", ");
	
if(!empty($x_no_referencia_ven)){
$sqlSaldo2 = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and   vencimiento_num > $x_no_referencia_ven and vencimiento_num < 2000 and vencimiento_status_id in (1,3,6) order by  vencimiento_num asc ";
$rsSaldo2 = phpmkr_query($sqlSaldo2, $conn) or die ("error en saldos". phpmkr_error()."sql:".$sqlSaldo2);

$x_total_liquida_sin_interes = 0;
$x_total_interes_i = 0;
$x_total_iva_i = 0;
$x_lista_sin_interes ="";
while ($rowSaldo2 = phpmkr_fetch_array($rsSaldo2)){
	#echo "entra";
	#echo $rowSaldo["vencimiento_id"]."<br>";
	$x_total_liquida_sin_interes = $x_total_liquida_sin_interes + $rowSaldo2["total_venc"];
	$x_total_interes_i = $x_total_interes_i + $rowSaldo2["interes"];
	$x_total_iva_i = $x_total_iva_i + $rowSaldo2["iva"];
	$x_lista_sin_interes = $x_lista_sin_interes.$rowSaldo2["vencimiento_num"].", ";
	}
}
$x_lista_sin_interes = trim($x_lista_sin_interes,", ");	

if(!empty($x_no_referencia_ven)){
$sqlSaldo3 = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and   vencimiento_num > $x_no_referencia_ven and vencimiento_num >= 2000 and vencimiento_status_id in (1,3,6) order by  vencimiento_num asc ";

$rsSaldo3 = phpmkr_query($sqlSaldo3, $conn) or die ("error en saldos". phpmkr_error()."sql:".$sqlSaldo3);
$x_total_penalizaciones = 0;
$x_lista_penalizaciones = "";
while ($rowSaldo3 = phpmkr_fetch_array($rsSaldo3)){
	#echo "entra";
	#echo $rowSaldo["vencimiento_id"]."<br>";
	$x_total_penalizaciones = $x_total_penalizaciones + $rowSaldo3["total_venc"];	
	$x_lista_penalizaciones = $x_lista_penalizaciones.$rowSaldo3["vencimiento_num"].", ";
	}
}
	$x_lista_penalizaciones = trim($x_lista_penalizaciones, ", ");
#echo "total_liqui con interes".$x_total_liquida_con_interes."<br>";
#echo "total_pendiente".$x_total_liquida_sin_interes."<br>";
#echo "total interes pendiente". $x_total_interes_i."<br>";
#echo "total_iva_pendiente".$x_total_iva_i."<br>";

$x_total_liquida_sin_interes = $x_total_liquida_sin_interes -$x_total_interes_i -$x_total_iva_i;
#echo "total_pendiente".$x_total_liquida_sin_interes."<br>";
$x_fecha_de_vigencia = $x_fecha_referencia;
$x_saldo_a_liquidar = $x_total_liquida_con_interes +  $x_total_liquida_sin_interes+ $x_total_penalizaciones;

if($x_saldo_a_liquidar>0){

$x_tabla_liquida = '
<table id="ewlistmain" class="ewTable" align="center" width="700">
	<!-- Table header -->
	<tr class="ewTableHeader">
<td colspan="4"><center><strong>
<h3>LIQUIDACI&Oacute;N ANTICIPADA</h3></strong></center>
 </td>
	</tr>
    <tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>

<td width="114">&nbsp;</td>
		<!-- vencimiento_id -->		<!-- credito_id -->
		<!-- vencimiento_status_id -->
		<td width="169">Fecha vigencia</td>
		<!-- fecha_vencimiento -->
		<td width="236" align="left">&nbsp;</td>
	  <td align="center">Monto para liquidar</td>
		<!-- importe -->		<!-- interes -->
		<!-- interes_moratorio -->	</tr>';


// Avoid starting record > total records
if ($nStartRec > $nTotalRecs) {
	$nStartRec = $nTotalRecs;
}

// Set the last record to display
$nStopRec = $nStartRec + $nDisplayRecs - 1;

// Move to first record directly for performance reason
$nRecCount = $nStartRec - 1;
if (phpmkr_num_rows($rs) > 0) {
	phpmkr_data_seek($rs, $nStartRec -1);
}


$sSqlWrk = "SELECT importe FROM credito where credito_id = $x_credito_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
$x_saldo = $datawrk["importe"];
@phpmkr_free_result($rswrk);

$x_total = 0;
$x_total_pagos = 0;
$x_total_interes = 0;
$x_total_iva = 0;
$x_total_iva_mor = 0;
$x_total_moratorios = 0;
$x_total_total = 0;

$x_total_d = 0;
$x_total_pagos_d = 0;
$x_total_interes_d = 0;
$x_total_iva_d = 0;
$x_total_iva_mor_d = 0;
$x_total_moratorios_d = 0;
$x_total_total_d = 0;

$x_total_pagos_v = 0;
$x_total_interes_v = 0;
$x_total_iva_v = 0;
$x_total_iva_mor_v = 0;
$x_total_moratorios_v = 0;
$x_total_total_v = 0;


$x_total_a = 0;
$x_total_pagos_a = 0;
$x_total_interes_a = 0;
$x_total_iva_a = 0;
$x_total_iva_mor_a = 0;
$x_total_moratorios_a = 0;
$x_total_total_a = 0;

$nRecActual = 0;


 }
 }
}


//return $bLoadData;
$x_tabla_fina = $x_respuesta. $x_respuesta1.$x_respuesta2.$x_respuesta3.$x_respuesta4.$x_respuesta5.$x_respuesta6.$x_respuesta7.$x_respuesta8.$x_respuesta9.$x_respuesta10.$x_respuesta11.$x_respuesta12.$x_respuesta13.$x_respuesta14.$x_respuesta15.$x_respuesta16.$x_respuesta17.$x_respuesta18.$x_respuesta19.$x_respuesta20.$x_respuesta21.$x_respuesta26.$x_tabla_liquida.$x_penalizacion.$x_tabla_garantia;

// se quitaron los comentarios en lo qu se busca como truncarlos  $x_respuesta22.$x_respuesta23.$x_respuesta24.
return $x_tabla_fina ;
}	
	
function cut_string($string, $charlimit){
if(substr($string,$charlimit-1,1) != ' '){
$string = substr($string,'0',$charlimit);
$array = explode(' ',$string);
array_pop($array);
$new_string = implode(' ',$array);
return $new_string.' ...';
}
else
{ 
return substr($string,'0',$charlimit-1).' ...';
}
}