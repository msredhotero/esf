<?php session_start(); ?>
<?php ob_start(); ?>
<?php
 include("db.php");
 include("phpmkrfn.php");?>
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
<?php
// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
set_time_limit(0);

// selecciona de la lista de clientes todos los nombres de los clientes

$sqlClientes  = "SELECT * FROM cliente";
$rsCliente = phpmkr_query($sqlClientes,$conn) or die("Error en describe 1".$sqlClientes. phpmkr_error());

// Build SQL
$tr_str = '<table  align="center" class="ewTable">
		<tr class="ewTableHeader">
		<td valign="top"><span> Nombre</span></td>
        <td valign="top"><span> Paterno</span></td>
		<td valign="top"><span> Materno</span></td>
		<td valign="top"><span> credito num</span></td>
		<td valign="top"><span> Lista </span></td>
		</tr>';
		
$tr_str2 = '<table id="ewlistmain" class="ewTable">
		<tr class="ewTableHeader">
		<td valign="top"><span> Nombre</span></td>
        <td valign="top"><span> Paterno</span></td>
		<td valign="top"><span> Materno</span></td>
		<td valign="top"><span> credito num</span></td>
		<td valign="top"><span> Lista </span></td>
		</tr>
		
		<tr>
		<td valign="top"><span>&nbsp;</span></td>
		<td valign="top"><span>&nbsp;</span></td>
		<td valign="top"><span>&nbsp;</span></td>
		<td valign="top"><span>&nbsp;</span></td>
		<td valign="top"><span>&nbsp;</span></td>
		</tr></table>';
		$total_registros = 0;
		
while($rowCliente = phpmkr_fetch_array($rsCliente)){
		$nombre = trim($rowCliente["nombre_completo"]);
		$paterno = trim($rowCliente["apellido_paterno"]);
		$materno = trim($rowCliente["apellido_materno"]);
		
		$nombre = ($nombre != '' )?$nombre." ":'';
		$paterno = ($paterno != '' )?$paterno." ":'';
		$materno = ($materno != '' )?$materno:'';
		$x_existe = 0;
		$x_existe_negra=0;
		
		$x_cliente_id = $rowCliente["cliente_id"];
		$x_credito_id = 0;
		$x_solicitud_id = 0;
		$x_lista = '';
		$sqlSolcitud  = "SELECT solicitud_id FROM solicitud_cliente WHERE cliente_id =  ".$x_cliente_id." AND solicitud_id NOT IN (281,8162,8394,7013,5809,8162)";
		$rsSol = phpmkr_query($sqlSolcitud,$conn) or die("Error en sol".phpmkr_error());
		 #echo $sqlSolcitud."<br>";
		while($rowSol = phpmkr_fetch_array($rsSol)){
			$x_solicitud_id = $rowSol["solicitud_id"];		
		}
		
		// buscamos el credito
		if($x_solicitud_id){
			$sqlCredito  = "SELECT credito_id, credito_num, credito_tipo_id, credito_status_id FROM credito WHERE solicitud_id =  ".$x_solicitud_id;
			$rsCre = phpmkr_query($sqlCredito,$conn) or die("Error en credito");
			#echo $sqlCredito;
			while($rowCre = phpmkr_fetch_array($rsCre)){
			$x_credito_id = $rowCre["credito_id"];
			$credito_num = $rowCre["credito_num"];
			$credito_tipo_id = $rowCre["credito_tipo_id"];
			$credito_status_id = $rowCre["credito_status_id"];		
			}
			# echo "<br>credito_id==>".$x_credito_id ;
			if($x_credito_id){			
				################################# buscamos en la lista OFAC #######################################
				$x_nombre_busqueda_OFAC = $paterno.$materno.", ".$nombre; // formato de la lista OFAC RUELAS AVILA, Jose Luis
				$x_nombre_busqueda_OFAC = trim($x_nombre_busqueda_OFAC);
				
				if($materno =='' || empty($materno))
				$x_nombre_busqueda_OFAC = trim($paterno).", ".$nombre; // formato de la lista RUELAS AVILA, Jose Luis
	
				$x_nombre_busqueda_OFAC =  trim($x_nombre_busqueda_OFAC);
				
				$sSqlOFAC = "SELECT * FROM `csv_sdn` WHERE `sdn_name` LIKE  _utf8'%".$x_nombre_busqueda_OFAC."%' COLLATE utf8_general_ci";
				#echo $sSqlOFAC."<br>";
				$rsOFAC = phpmkr_query($sSqlOFAC,$conn) or die("Error en busqueda en la lista OFAC". phpmkr_error());
					$x_existe = 0;		
				while($rowOFAC = phpmkr_fetch_array($rsOFAC)){
					$x_sdn_name =$rowOFAC["sdn_name"];
					$x_ent_num =$rowOFAC["ent_num"];
					$x_existe=1;
					}
				################################# buscamos en la lista negra CNBV #######################################
				/*$x_nombre_busqueda_lista_negra = $nombre.$paterno.$materno; // formato de la lista 
				$x_nombre_busqueda_lista_negra = trim($x_nombre_busqueda_lista_negra);
				$sSqlNegra = "SELECT * FROM `csv_lista_negra_cnbv` WHERE `nombre_completo` LIKE _utf8 '%".$x_nombre_busqueda_lista_negra."%' COLLATE utf8_general_ci";
				$rsNEGRA = phpmkr_query($sSqlNegra,$conn) or die("Error en busqueda en la lista NEGRA". phpmkr_error());
				#echo $sSqlNegra."<br>";	
				while($rowNEGRA = phpmkr_fetch_array($rsNEGRA)){
					$x_nombre_completo_negra =$rowNEGRA["nombre_completo"];
					$x_existe_negra=1;
					}*/ # 24/11/2018 se cambia la tabla de la CNBV
					
					
				$x_existe_negra = 0;
				$x_nombre_completo_negra ='';
				$x_nombre_busqueda_lista_negra = $nombre.$paterno.$materno; // 
				if($materno =='' || empty($materno))
				$x_nombre_busqueda_lista_negra = $nombre.$paterno; 
				
				$x_nombre_busqueda_lista_negra = trim($x_nombre_busqueda_lista_negra);
				$sSqlNegra = "SELECT * FROM `csv_lista_lpb` WHERE `nombre_completo` LIKE _utf8 '%".$x_nombre_busqueda_lista_negra."%' COLLATE utf8_general_ci";
				$rsNEGRA = phpmkr_query($sSqlNegra,$conn) or die("Error en busqueda en la lista NEGRA". phpmkr_error());
					
				while($rowNEGRA = phpmkr_fetch_array($rsNEGRA)){
					$x_nombre_completo_negra =$rowNEGRA["nombre_completo"];
					$x_existe_negra=1;
					}
				
				$x_nombre_busqueda_lista_negra = $paterno.$materno." ".$nombre; // formato de la lista RUELAS AVILA, Jose Luis
				if($materno =='' || empty($materno))
				$x_nombre_busqueda_lista_negra = $paterno.$nombre; 
				
				
				$x_nombre_busqueda_lista_negra = trim($x_nombre_busqueda_lista_negra);
				$sSqlNegra = "SELECT * FROM `csv_lista_lpb` WHERE `nombre_completo` LIKE _utf8 '%".$x_nombre_busqueda_lista_negra."%' COLLATE utf8_general_ci";
				$rsNEGRA = phpmkr_query($sSqlNegra,$conn) or die("Error en busqueda en la lista NEGRA". phpmkr_error());
					
					
		
				if($x_existe || $x_existe_negra){
					// si se encontro el registro se manda un mail al oficial de cumpliento
					// se bloquea la solicitud
					// se genera un registro de tipo OPERACION INUSUAL  ==se espcifica que es de 24 horas
					#MAIL
					$x_mensaje = "";
					$tiposms = "";
					$x_today = date("Y-m-d");
					
					// BUCAMOS SI LA ALERTA YA ESXISTE 
						$SQL_alerta =  "SELECT  * FROM reporte_cnbv WHERE solicitud_id = ".$x_solicitud_id." AND cliente_id = ".$x_cliente_id. " AND  razon_reporte  LIKE  '%=POR PROCESO= El nombre del cliente fue encontrado en la lista OFAC%'  ";
						$rs_ALERT = phpmkr_query($SQL_alerta,$conn) or die("Error al LISTADO ALERTAS ".phpmkr_error()."sql :".$SQL_alerta);
						$numero_de_alertas_ofac =  phpmkr_num_rows($rs_ALERT);
						
						// BUCAMOS SI LA ALERTA YA ESXISTE 
						$SQL_alerta =  "SELECT  * FROM reporte_cnbv WHERE solicitud_id = ".$x_solicitud_id." AND cliente_id = ".$x_cliente_id. " AND  razon_reporte  LIKE  '%=POR PROCESO= El nombre del cliente fue encontrado en la lista negra de la CNBV%'  ";
						$rs_ALERT = phpmkr_query($SQL_alerta,$conn) or die("Error al LISTADO ALERTAS ".phpmkr_error()."sql :".$SQL_alerta);
						$numero_de_alertas_cnbv =  phpmkr_num_rows($rs_ALERT);
						
					if($x_existe && $numero_de_alertas_ofac < 1 ){
						
						
				
						$x_lista = 'OFAC <BR>';
						$x_mensaje = " PROCEDIMIENTO BUSQUEDA EN LISTA OFAC, SE REGISTRO UNA SOLICITUD DONDE SE INDICO PERSONA DE LA LISTA OFAC CON EL CLIENTE ".$nombre ." ". $paterno. " ".$materno ."\n \n EL DÍA "	.date("Y-m-d")."  ENT_NUM =  ".$x_ent_num." NOMNBRE => ".$x_sdn_name;
						$sSqlInsert = "	INSERT INTO `reporte_cnbv` (`reporte_cnbv_id`, `cliente_id`, `solicitud_id`, `tipo_reporte_id`, `descripcion_operacion` , `razon_reporte`, 	`status_datos`, `nombre`, `fecha_registro`) VALUES (NULL, '".$x_cliente_id."', '".$x_solicitud_id."', '2','Reporte de 24 horas == AQUI LA DESCRIPCION DE LA OPERACION, GENERADO POR PROCESO==','=POR PROCESO= El nombre del cliente fue encontrado en la lista OFAC con el ENT_NUM = ".$x_ent_num."', '1', '".$x_sdn_name."' , '".$x_today."' )";
						}
					if($x_existe_negra && $numero_de_alertas_cnbv < 1){
						$x_mensaje = "PROCEDIMIENTO BUSQUEDA EN LISTA NEGRA, SE REGISTRO UNA SOLICITUD DONDE SE INDICO PERSONA EN LA LISTA NEGRA DE LA CNBV CON EL CLIENTE ".$nombre ." ". $paterno. " ".$materno ."\n \n EL DÍA "	.date("Y-m-d")." POR FAVOR REVISE LA SOLITUD Y DIRIJASE A LA LISTA NEGRA DE LA CNBV PARA CONFIRMAR LA INFORMACION \n \n DESPUES REALICE EL REPORTE DE OPERACION INUSUAL SI ES NECESARIO";
						$sSqlInsert2 = " INSERT INTO `reporte_cnbv` (`reporte_cnbv_id`, `cliente_id`, `solicitud_id`, `tipo_reporte_id`, `descripcion_operacion` , `razon_reporte`, 	`status_datos`, `nombre`, `fecha_registro`) VALUES (NULL, '".$x_cliente_id."', '".$x_solicitud_id."', '2','Reporte de 24 horas == AQUI LA DESCRIPCION DE LA OPERACION, GENERADO POR PROCESO ==','=POR PROCESO= El nombre del cliente fue encontrado en la lista negra de la CNBV ".$x_ent_num."', '1', '".$x_nombre_completo_negra."', '".$x_today."'  )";
						$x_lista .= 'LISTA NEGRA <BR>';
						}
								
								
								$para  = 'oficialdecumplimiento@financieracrea.com'; // atención a la coma
								// subject
								$titulo = "== SE HA REGISTRADO UNA OPERACION INUSUAL DE 24 HRS GENERADO POR PROCESO ==";												
								//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
								$cabeceras = 'From: zortiz@createc.mx';
								$cabeceras2 = 'From: atencionalcliente@financieracrea.com';
								
								$mensajeMail = $x_mensaje."\n \n * Este mensaje ha sido enviado de forma automatica, por favor no lo responda. \n \n";
								$mensajeMail .=  " Cualquier duda comuniquese al (55) 51350259 del interior de la republica  al (01800) 8376133 "; 
								// Mail it		
								if($x_existe && $numero_de_alertas_ofac < 1  || $x_existe_negra && $numero_de_alertas_cnbv < 1 )	{			
								mail($para, $titulo, $x_mensaje, $cabeceras);				
								}
								$hoy = date("Y-m-d");
								$x_fecha = ConvertDateToMysqlFormat($hoy);
								
					#BLOQUEO DE SOLICITUD
					 //$sqlUpdate = "UPDATE solicitud  SET  `solicitud_status_id` =  '13' WHERE  `solicitud`.`solicitud_id` =".$GLOBALS["x_solicitud_id"]." ";
					// phpmkr_query($sqlUpdate, $conn) or die ("Error al modificar el status de la solicitud". phpmkr_error()."sql :". $sqlUpdate);
					
					
					#GENERA LA OPERACION INUSUAL DE 24 HORAS 
					if($x_existe && $numero_de_alertas_ofac < 1){
					phpmkr_query($sSqlInsert, $conn) or die ("Error al insertar la operacion inusual 24 horas en la listado reporte cnbv". phpmkr_error()."sql :". $sSqlInsert);
					}
					if($x_existe_negra && $numero_de_alertas_cnbv < 1){
					phpmkr_query($sSqlInsert2, $conn) or die ("Error al insertar la operacion inusual 24 horas en la listado reporte cnbv". phpmkr_error()."sql :". $sSqlInsert);
					}
					$total_registros ++; 
					
					// Set row color
					$sItemRowClass = " class=\"ewTableRow\"";
				
					// Display alternate color for rows
					if ($total_registros % 2 <> 0) {
						$sItemRowClass = " class=\"ewTableAltRow\"";
					}
					
				$tr_str .= '<tr '.$sItemRowClass.'>
							<td valign="top"><span>'.$nombre.'</span></td>
							<td valign="top"><span>'.$paterno.'</span></td>
							<td valign="top"><span>'.$materno.'</span></td> 
							<td valign="top"><span>'.$credito_num.'</span></td>
							<td valign="top"><span>'.$x_lista.'</span></td>
							</tr>';
					
					
					}		
				
				}// if credito id
			
		}// if solicitud
		

}// while cliente
	
	$tr_str .= '</table> <p><p><p>';		

		








?>
<BODY>
<?PHP
if($total_registros>0){
	echo  $tr_str ;
	?>
    <P>
<P>
<div class="content-box-gray">
POR FAVOR VERIQUE LA LISTA DE REPORTES PLD DE TIPO INUSUAL, AH&Iacute; PODR&Aacute; ENCONTRAR LOS REGISTROS QUE SE GENERARON PARA ESTOS CASOS
<br /><b>NOTA:</b> Es necesario ingresar a cada regristro y editarlo para terminar el proceso</div>
 <?php
	
	}else{
		
		echo  $tr_str2 ;
		echo "NO SE ENCONTRARON COINCIDENCIAS CON LA LISTA OFAC O CON LA LISTA NEGRA DE LA CNBV";
		
		}

?>


</BODY>