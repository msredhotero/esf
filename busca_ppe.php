<?php session_start(); ?>
<?php ob_start(); ?>
<?php
 include("db.php");
 include("phpmkrfn.php");
// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);


// selecciona de la lista de clientes todos los nombres de los clientes

$sqlClientes  = "SELECT * FROM clientes ";
$rsCliente = phpmkr_query($sqlClientes,$conn) or die("Error en describe");

// Build SQL

while($rowCliente = phpmkr_fetch_array($rsPpE)){
		$nombre = trim($rowCliente["nombre_completo"]);
		$paterno = trim($rowCliente["apellido_paterno"]);
		$materno = trim($rowCliente["apellido_materno"]);
		
		$nombre = ($nombre != '' )?$nombre." ":'';
		$paterno = ($paterno != '' )?$paterno." ":'';
		$materno = ($materno != '' )?$materno." ":'';
		$x_existe = 0;
		$x_existe_negra=0;
		
		$x_cliente_id = $rowCliente["cliente_id"];
		$x_credito_id = 0;
		$x_solicitud_id = 0;
		$sqlSolcitud  = "SELECT solicitud_id FROM solicitud WHERE cliente_id =  ".$x_cliente_id;
		$rsSol = phpmkr_query($sqlSolcitud,$conn) or die("Error en sol");
		
		while($rowSol = phpmkr_fetch_array($rsSol)){
			$x_solicitud_id = $rowSol["solicitud_id"];		
		}
		
		// buscamos el credito
		if($x_solicitud_id){
			$sqlCredito  = "SELECT credito_id, credito_num, credito_tipo_id, credito_status_id FROM credito WHERE solicitud_id =  ".$x_solicitud_id;
			$rsCre = phpmkr_query($sqlCredito,$conn) or die("Error en credito");
			while($rowCre = phpmkr_fetch_array($rsSol)){
			$x_credito_id = $rowCre["credito_id"];		
			}
			
			if($x_credito_id){			
				################################# buscamos en la lista OFAC #######################################
				$x_nombre_busqueda_OFAC = $paterno.$materno.", ".$nombre; // formato de la lista OFAC RUELAS AVILA, Jose Luis
				$x_nombre_busqueda_OFAC = trim($x_nombre_busqueda_OFAC);
				$sSqlOFAC = "SELECT * FROM `csv_sdn` WHERE `sdn_name` LIKE  _utf8'%".$x_nombre_busqueda_OFAC."%' COLLATE utf8_general_ci";
				$rsOFAC = phpmkr_query($sSqlOFAC,$conn) or die("Error en busqueda en la lista OFAC". phpmkr_error());
							
				while($rowOFAC = phpmkr_fetch_array($rsOFAC)){
					$x_sdn_name =$rowOFAC["sdn_name"];
					$x_ent_num =$rowOFAC["ent_num"];
					$x_existe=1;
					}
				################################# buscamos en la lista negra CNBV #######################################
				$x_nombre_busqueda_lista_negra = $nombre.$paterno.$paterno; // formato de la lista 
				$x_nombre_busqueda_lista_negra = trim($x_nombre_busqueda_lista_negra);
				$sSqlNegra = "SELECT * FROM `csv_lista_negra_cnbv` WHERE `nombre_completo` LIKE _utf8 '%".$x_nombre_busqueda_lista_negra."%' COLLATE utf8_general_ci";
				$rsNEGRA = phpmkr_query($sSqlNegra,$conn) or die("Error en busqueda en la lista NEGRA". phpmkr_error());
					
				while($rowNEGRA = phpmkr_fetch_array($rsNEGRA)){
					$x_nombre_completo_negra =$rowNEGRA["nombre_completo"];
					$x_existe_negra=1;
					}
		
				if($x_existe || $x_existe_negra){
					// si se encontro el registro se manda un mail al oficial de cumpliento
					// se bloquea la solicitud
					// se genera un registro de tipo OPERACION INUSUAL  ==se espcifica que es de 24 horas
					#MAIL
					$x_mensaje = "";
					$tiposms = "";
					$x_today = date("Y-m-d");
					if($x_existe){
						$x_mensaje = " PROCEDIMIENTO BUSQUEDA EN LISTA OFAC, SE REGISTRO UNA SOLICITUD DONDE SE INDICO PERSONA DE LA LISTA OFAC CON EL CLIENTE ".$nombre ." ". $paterno. " ".$materno ."\n \n EL DÍA "	.date("Y-m-d")."  ENT_NUM =  ".$x_ent_num." NOMNBRE => ".$x_sdn_name;
						$sSqlInsert = "	INSERT INTO `reporte_cnbv` (`reporte_cnbv_id`, `cliente_id`, `solicitud_id`, `tipo_reporte_id`, `descripcion_operacion` , `razon_reporte`, 	`status_datos`, `nombre`, `fecha_registro`) VALUES (NULL, '".$x_cliente_id."', '".$x_solicitud_id."', '2','Reporte de 24 horas == AQUÍ LA DESCRIPCIÓN DE LA OPERACIÓN, GENERADO POR PROCESO==','El nombre del cliente fue encontrado en la lista OFAC con el ENT_NUM = ".$x_ent_num."', '1', '".$x_sdn_name."' , '".$x_today."' )";
						}
					if($x_existe_negra){
						$x_mensaje = "PROCEDIMIENTO BUSQUEDA EN LISTA NEGRA, SE REGISTRO UNA SOLICITUD DONDE SE INDICO PERSONA EN LA LISTA NEGRA DE LA CNBV CON EL CLIENTE ".$nombre ." ". $paterno. " ".$materno ."\n \n EL DÍA "	.date("Y-m-d")." POR FAVOR REVISE LA SOLITUD Y DIRIJASE A LA LISTA NEGRA DE LA CNBV PARA CONFIRMAR LA INFORMACION \n \n DESPUES REALICE EL REPORTE DE OPERACION INUSUAL SI ES NECESARIO";
						$sSqlInsert = "	INSERT INTO `reporte_cnbv` (`reporte_cnbv_id`, `cliente_id`, `solicitud_id`, `tipo_reporte_id`, `descripcion_operacion` , `razon_reporte`, 	`status_datos`, `nombre`, `fecha_registro`) VALUES (NULL, '".$x_cliente_id."', '".$x_solicitud_id."', '2','Reporte de 24 horas == AQUÍ LA DESCRIPCIÓN DE LA OPERACIÓN, GENERADO POR PROCESO ==','El nombre del cliente fue encontrado en la lista negra de la CNBV ".$x_ent_num."', '1', '".$x_nombre_completo_negra."' , '".$x_today."' )";
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
								mail($para, $titulo, $x_mensaje, $cabeceras);					
								$hoy = date("Y-m-d");
								$x_fecha = ConvertDateToMysqlFormat($hoy);
								
					#BLOQUEO DE SOLICITUD
					 //$sqlUpdate = "UPDATE solicitud  SET  `solicitud_status_id` =  '13' WHERE  `solicitud`.`solicitud_id` =".$GLOBALS["x_solicitud_id"]." ";
					// phpmkr_query($sqlUpdate, $conn) or die ("Error al modificar el status de la solicitud". phpmkr_error()."sql :". $sqlUpdate);
					
					
					#GENERA LA OPERACION INUSUAL DE 24 HORAS 
					phpmkr_query($sSqlInsert, $conn) or die ("Error al insertar la operacion inusual 24 horas en la listado reporte cnbv". phpmkr_error()."sql :". $sSqlInsert);
				
					
					
					}
				
				
				
				
				
				
				}// if credito id
			
		}// if solicitud
		

}// while cliente
$x_existe ='';
$sSqlPpE = "SELECT * FROM `lista_ppe` WHERE `nombre` LIKE '%".$nombre."%' AND `paterno` LIKE '%".$paterno."%' AND `materno` LIKE '%".$materno."%'";
$rsPpE = phpmkr_query($sSqlPpE,$conn) or die("Error en describe");
if(!$rsPpE ){
	$x_load_data  = false;			
}	else{
	$x_existe=1;
	}	
			
while($rowPPE = phpmkr_fetch_array($rsPpE)){
	$x_nombre =$rowPPE["nombre"];
	$x_paterno =$rowPPE["paterno"];
	$x_materno =$rowPPE["materno"];
	$x_puesto =$rowPPE["puesto"];
	$x_dependencia =$rowPPE["dependencia"];
	
		}
		
if($x_existe){
	echo '
	<table id="ewlistmain" class="ewTable">
	
	<tr class="ewTableHeader">
		<td valign="top"><span> Nombre</span></td>
        <td valign="top"><span> Paterno</span></td>
		<td valign="top"><span> Materno</span></td>
		<td valign="top"><span> Puesto</span></td>
		<td valign="top"><span> Dependencia</span></td>       
		</tr>
	<tr>
		<td valign="top"><span>'.$x_nombre.'</span></td>
        <td valign="top"><span>'.$x_paterno.'</span></td>
		<td valign="top"><span>'.$x_materno.'</span></td> 
		<td valign="top"><span>'.$x_puesto.'</span></td>
		<td valign="top"><span>'.$x_dependencia.'</span></td>      
		</tr><tr></table>';
	
	}else{
		
		echo "este registro no se encontro en la lista PPE";
		
		}







?>