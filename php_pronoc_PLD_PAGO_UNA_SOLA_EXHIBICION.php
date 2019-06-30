<?php
/* 
 * financiera.com
 * ZOA 
 */
// este proceso se ejecuta el primer dia del mes
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php include ("utilerias/datefunc.php") ?>
<?php 
//x_monto_genera_alerta_1
// la primer alerta se debe generar cuando se pague el correspondiente a  7500 dolares americanos
$x_monto_genera_alerta_nivel_1 = 7500;
$x_monto_genera_alerta_nivel_2 = 1000000;
$x_monto_genera_alerta_1 = $x_monto_genera_alerta_nivel_1;
$x_monto_genera_alerta_2 = $x_monto_genera_alerta_nivel_2;


  $x_cont =1;
$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
$currdate = ConvertDateToMysqlFormat($currdate);
$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];		
$currdate= "2018-04-01";
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

// Buscamos el tipo de cambio para saber en cuanto queda el monto de la alerta
$SqlTipoCambio = " SELECT * FROM tipo_cambio  ORDER BY tipo_cambio_id LIMIT 0, 1";
$rsTipoCambio = phpmkr_query($SqlTipoCambio,$conn) or die ("Error al seleccionar el tipo de cambio".phpmkr_error()."sql.".$SqlTipoCambio);

$rowTipoCambio = phpmkr_fetch_array($rsTipoCambio);
$tipoCambio = $rowTipoCambio["monto"]; 
$x_monto_genera_alerta_1 = $tipoCambio * 7500 ;

#echo "TIPO CAMBIO ".$tipoCambio."<BR>";
#echo "MONTO ALERTA 1 ". $tipoCambio ." * 7500 = ".$x_monto_genera_alerta_1."<BR>";


#######################################

$x_monto_genera_alerta_1 = 10000;
############################################
$SqlF = " SELECT DATE_SUB('".$currdate."', INTERVAL 1 DAY) as fecha_fin_mes ";
$rsF = phpmkr_query($SqlF,$conn) or die ("Error al seleccionar la fecha menos 31 dias".phpmkr_error()."sql.".$SqlF);

$rowF = phpmkr_fetch_array($rsF);
$currdate = $rowF["fecha_fin_mes"]; 
$fecha_fin_mes = $rowF["fecha_fin_mes"]; 
#print_r($currentdate);
#echo "<br>fecha actual".$currentdate." fecha ultimo dia del mes pasado =". $fecha_fin_mes ."<br>";
 $array_dias = explode('-', $fecha_fin_mes);
 $digito_ultimo_dia = $array_dias[2];
 $digito_año = $array_dias[0];
 $digito_mes = $array_dias[1];
 $digito_primer_dia = '01';
 
 echo date("Y-m-d")."<br>";
 $fecha_inico_mes = $digito_año."-".$digito_mes."-".$digito_primer_dia;
 #echo "FECHA INICIO MES: ".$fecha_inico_mes." FECHA FIN DE MES: ".$fecha_fin_mes."<BR>";
 //seleccionamos los credito activos y los creditos liquidados que seran los que entren en el proceso
$sqlCreditosActivos = "SELECT credito_id, credito_status_id, solicitud_id, credito_tipo_id, credito_tipo_id, credito_num FROM credito    where credito_status_id = 3 and credito_id > 6000 ";
$rsCreditosActivos = phpmkr_query($sqlCreditosActivos,$conn);
while($rowCreditoActivo = phpmkr_fetch_array($rsCreditosActivos)){
    $x_credito_id = $rowCreditoActivo["credito_id"]; 
    $x_credito_status_id = $rowCreditoActivo["credito_status_id"]; 
    $x_solicitud_id= $rowCreditoActivo["solicitud_id"]; 
    $x_credito_tipo_id = $rowCreditoActivo["credito_tipo_id"];  
    $x_credito_num = $rowCreditoActivo["credito_num"]; 
    
    //seleccionamos el cliente_id
    $sqlCliente = "SELECT cliente_id FROM solicitud_cliente     WHERE solicitud_id = ".$x_solicitud_id." ";
    $rsCliente = phpmkr_query($sqlCliente,$conn);
    $rowCliente = phpmkr_fetch_array($rsCliente);
    $x_cliente_id = $rowCliente["cliente_id"];
    
   /* if($x_credito_tipo_id== 7){
        $x_monto_genera_alerta_1=500000;      
    }*/
  
    if($x_credito_status_id ==3  ){
			
			$sqlVencimientos =  "SELECT credito.credito_id, recibo . * ";
			$sqlVencimientos .= " FROM recibo ";
			$sqlVencimientos .= " JOIN recibo_vencimiento ON recibo.recibo_id = recibo_vencimiento.recibo_id ";
			$sqlVencimientos .= " JOIN vencimiento ON vencimiento.vencimiento_id = recibo_vencimiento.vencimiento_id ";
			$sqlVencimientos .= " JOIN credito ON credito.credito_id = vencimiento.credito_id ";
			$sqlVencimientos .= " WHERE credito.credito_id = ".$x_credito_id. " ";
			$sqlVencimientos .=  " GROUP BY fecha_pago  ";
			$rsVen_rec=  phpmkr_query($sqlVencimientos,$conn)or die("Error al buscar todos los vencimientos del credito liquidado".$sqlVencimientos );
       		$numero_fechas =  phpmkr_num_rows($rsVen_rec);
			
			echo "CREDITO_ID=>".$x_credito_id." CREDITO NUM  =>".$x_credito_num." numero de fechas en los pagos =>".$numero_fechas."<br>";
			
			$sqlNumeroVencimientos = "SELECT * FROM  vencimiento  WHERE credito_id = ".$x_credito_id. " ";
			$rsVen_no=  phpmkr_query($sqlNumeroVencimientos,$conn)or die("Error al buscar todos los vencimientos del credito liquidado".$sqlNumeroVencimientos );
       		$numero_vencimientos =  phpmkr_num_rows($rsVen_no);
			
			#if($numero_fechas == 1 && $numero_vencimientos> 1){
				
				// BUCAMOS SI LA ALERTA YA ESXISTE 
				$SQL_alerta =  "SELECT  * FROM reporte_cnbv WHERE solicitud_id = ".$x_solicitud_id." AND cliente_id = ".$x_cliente_id. " AND  razon_reporte  LIKE  '%EL CREDITO SE LIQUIDO EN UNA SOLA EXHIBICION%'  ";
				$rs_ALERT = phpmkr_query($SQL_alerta,$conn) or die("Error al LISTADO ALERTAS ".phpmkr_error()."sql :".$SQL_alerta);
				$numero_de_alertas =  phpmkr_num_rows($rs_ALERT);
		
			if($numero_fechas == 1 && $numero_de_alertas < 1){
				// se genera la alerta ya que se liquido en una sola exhibicion
				$x_today = date("Y-m-d");
					
				// se registra la alerta
				$_SESSION["ewmsg"] .= '<BR><strong>SE REGISTRO UNA ALERTA PLD PARA ESTE CREDITO, CAUSA: EL CREDITO SE LIQUIDO EN UNA SOLA EXHIBICION </strong><br>';
				$sSql = "INSERT INTO `reporte_cnbv` (`reporte_cnbv_id`, `cliente_id`, `solicitud_id`, `tipo_reporte_id`, `fecha_registro`, `razon_reporte`)";
				
				 $sSql .= "VALUES (NULL,".$x_cliente_id.",".$x_solicitud_id.", '2', '".$x_today."', 'EL CREDITO SE LIQUIDO EN UNA SOLA EXHIBICION ');";
				 $rs = phpmkr_query($sSql,$conn) or die("Error al seleccionar los datos de la solicitud ".phpmkr_error()."sql :".$sSql);
		
						$para  = 'oficialdecumplimiento@financieracrea.com'; // atencion a la coma
						// subject
						$titulo = 'ALERTA PLD '.$x_today;						
						$cabeceras = 'From: zortiz@createc.mx';	
						$x_mensaje	= 'SE REGISTRO UNA ALERTA PLD PARA ESTA SOLICITUD, CAUSA: EL CREDITO SE LIQUIDO EN UNA SOLA EXHIBICION, SOLICITUD_ID = '.$x_solicitud_id;				
						$mensajeMail = $x_mensaje."\n \n * Este mensaje ha sido enviado de forma automatica, por favor no lo responda. \n \n";			 
						// Mail it						
						mail($para, $titulo, $mensajeMail, $cabeceras);
						echo $x_mensaje."<BR>";	
				}	
				
						
		
	}
    
   
    
    }// while credito activo
    
  
    

?>
