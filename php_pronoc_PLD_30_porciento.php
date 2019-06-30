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



$x_cont =1;
$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
$currdate = ConvertDateToMysqlFormat($currdate);
$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];		
#$currdate= "2018-04-16";
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);


############################################
$SqlF = " SELECT DATE_SUB('".$currdate."', INTERVAL 16 DAY) as fecha_fin_mes ";
$rsF = phpmkr_query($SqlF,$conn) or die ("Error al seleccionar la fecha menos 31 dias".phpmkr_error()."sql.".$SqlF);

$rowF = phpmkr_fetch_array($rsF);
$currdate = $rowF["fecha_fin_mes"]; 
$fecha_fin_mes = $rowF["fecha_fin_mes"]; 
print_r($currentdate);
echo "<br>fecha actual".$currentdate." fecha ultimo dia del mes pasado =". $fecha_fin_mes ."<br>";
 $array_dias = explode('-', $fecha_fin_mes);
 $digito_ultimo_dia = $array_dias[2];
 $digito_año = $array_dias[0];
 $digito_mes = $array_dias[1];
 $digito_primer_dia = '01';
 
 
 $fecha_inico_mes = $digito_año."-".$digito_mes."-".$digito_primer_dia;
 echo "FECHA INICIO MES: ".$fecha_inico_mes." FECHA FIN DE MES: ".$fecha_fin_mes."<BR>";

  $fecha_ayer = date('Y-m-d', mktime(0, 0, 0, date('m'),date('d')-1,date('Y')));
  echo "fecha que se buscara".$fecha_ayer;

############################################

 #$fecha_ayer = "2018-03-23";
 //seleccionamos los credito activos y los creditos liquidados que seran los que entren en el proceso
$sqlCreditosActivos = "SELECT credito_id, credito_status_id, solicitud_id, credito_tipo_id, credito_tipo_id, credito_num, importe FROM credito    where credito_status_id = 1 or credito_status_id = 3 ";
$rsCreditosActivos = phpmkr_query($sqlCreditosActivos,$conn);
while($rowCreditoActivo = phpmkr_fetch_array($rsCreditosActivos)){
    $x_credito_id = $rowCreditoActivo["credito_id"]; 
    $x_credito_status_id = $rowCreditoActivo["credito_status_id"]; 
    $x_solicitud_id= $rowCreditoActivo["solicitud_id"]; 
    $x_credito_tipo_id = $rowCreditoActivo["credito_tipo_id"];  
    $x_credito_num = $rowCreditoActivo["credito_num"]; 
    
	
	$x_importe_credito = $rowCreditoActivo["importe"];
	$x_30_porciento = ($x_importe_credito / 100 ) * 30;
	
	
    //seleccionamos el cliente_id
    $sqlCliente = "SELECT cliente_id FROM solicitud_cliente     WHERE solicitud_id = ".$x_solicitud_id." ";
    $rsCliente = phpmkr_query($sqlCliente,$conn);
    $rowCliente = phpmkr_fetch_array($rsCliente);
    $x_cliente_id = $rowCliente["cliente_id"];
    
   /* if($x_credito_tipo_id== 7){
        $x_monto_genera_alerta_1=500000;      
    }*/
  
    $suma_pagos = 0;
	$x_importe_pagado = 0;
    //seleccionamos todos los vencimeintos del credito
    $sqlvencimientos = "SELECT vencimiento_id, importe,total_venc FROM vencimiento WHERE credito_id = ".$x_credito_id." and vencimiento_status_id = 2";
    $rsVencimientos =  phpmkr_query($sqlvencimientos,$conn);
  //  echo $sqlvencimientos;
  $sql_and = '';
  $array_recibo_not_in = "1 ";
    while($rowVencimeintos = phpmkr_fetch_array($rsVencimientos)){
        $x_vencimiento_id = $rowVencimeintos["vencimiento_id"];
        $x_importe_vencimiento = $rowVencimeintos["importe"];
        $x_total_vencimiento = $rowVencimeintos["total_venc"];
      // seleccionamos todos los recibos id de los pagos
        $sqlRecibos = "SELECT recibo_id FROM recibo_vencimiento WHERE vencimiento_id = ".$x_vencimiento_id;
        $rsRecibos =  phpmkr_query($sqlRecibos,$conn);
        $x_muchos_recibos =0;
        //$suma_pagos =0;
		
		 
        while($rowRecibos =  phpmkr_fetch_array($rsRecibos) ){
            $x_recibo_id = $rowRecibos["recibo_id"];
            $array_recibo_not_in .= ", ".$x_recibo_id .", ";
			
            $x_muchos_recibos =0;
            // seleccionamos los montos pagados en el mes
            $sqlPago = "SELECT importe FROM recibo where fecha_registro ='".$fecha_ayer."' AND recibo_id = ".$x_recibo_id." AND recibo_status_id = 1 AND medio_pago_id IN (4)". $sql_and ; 
            $sqlPago = "SELECT importe FROM recibo where fecha_registro ='".$fecha_ayer."' AND recibo_id = ".$x_recibo_id." AND recibo_status_id = 1 ". $sql_and ; 
		    $sqlPago = "SELECT importe FROM recibo where DATE(fecha_pago) BETWEEN '".$fecha_inico_mes."' AND '".$fecha_fin_mes."' AND recibo_id = ".$x_recibo_id." AND recibo_status_id = 1 ". $sql_and ; 
	
			$rsPago = phpmkr_query($sqlPago,$conn);
                $rowPago =  phpmkr_fetch_array($rsPago);
                //print_r($rowPago);
                  $x_importe_pagado = $rowPago["importe"]!= ''?$rowPago["importe"]:0; 
                  $suma_pagos += $x_importe_pagado;
				 
                  if($x_importe_pagado>$x_30_porciento){
					# echo "<br>credito_id".$x_credito_id." credito_num ".$x_credito_num;
                    # echo "<br>vencimiento_id ".$x_vencimiento_id."recibo_id ".$x_recibo_id. "importe pagado ".$x_importe_pagado." -importe vencimiento".$x_total_vencimiento." <br> ";
                  echo " <br>". $sqlPago ;
				  } 
				  $x_muchos_recibos ++;
				  
				  $array_recibo_not_in  = trim( $array_recibo_not_in ,", "); 
				   $sql_and = ' AND recibo_id NOT IN ('.$array_recibo_not_in.')'; 
				    

        }//recibos
		
        
        
      
        
       
    }// while vencimientos
	$array_recibo_not_in = "";
	
	 if($suma_pagos >$x_30_porciento){
					echo "<BR>CREDITO_NUM =>".$x_credito_num."<BR>";	  
				   echo "importe pagado =>".$x_importe_pagado."<br>";
				   echo "suma =>".$suma_pagos."<br>";
				   echo $sqlPago;
				  }
 # echo "<br>".$suma_pagos. "clientes".$x_cliente_id. "sol ". $x_solicitud_id;
    if(( $suma_pagos > $x_30_porciento  ) ){
       // ha pagado mas de lo del monto para generar la alerta; se registra la alerta 
       /*$sSql = "SELECT COUNT(*) as pld  FROM  alerta_pld ";
		$sSql .= " WHERE tipo_alerta_id = 16 and solicitud_id = ".$x_solicitud_id." ";
		$rs = phpmkr_query($sSql,$conn) or die("Error al seleccionar los datos de la solicitud 26".phpmkr_error()."sql :".$sSql);*/
		
		#$row = phpmkr_fetch_array($rs);
			$x_pld =$row["pld"];
			$x_today = date("Y-m-d");
			$x_pld = 0;
			if($x_pld==0){
				// se registra la alerta
				$_SESSION["ewmsg"] .= '<BR><strong>SE REGISTRO UNA ALERTA PLD PARA ESTE CREDITO, CAUSA: EL MONTO DEL PAGO FUE DE UN 30% O MAYOR</strong><br>';
				$sSql = "INSERT INTO `reporte_cnbv` (`reporte_cnbv_id`, `cliente_id`, `solicitud_id`, `tipo_reporte_id`, `fecha_registro`, `razon_reporte`, `monto`)";
				
				 $sSql .= "VALUES (NULL,".$x_cliente_id.",".$x_solicitud_id.", '2', '".$x_today."', 'EL MONTO DEL PAGO FUE DE UN 30% O MAYOR EN EL CREDITO ".$x_credito_num."',$suma_pagos);";
				 $rs = phpmkr_query($sSql,$conn) or die("Error al seleccionar los datos de la solicitud ".phpmkr_error()."sql :".$sSql);
		
						$para  = 'oficialdecumplimiento@financieracrea.com'; // atencion a la coma
						// subject
						$titulo = 'ALERTA PLD '.$x_today;						
						$cabeceras = 'From: zortiz@createc.mx';	
						$x_mensaje	= 'SE REGISTRO UNA ALERTA PLD PARA ESTE CREDITO, CAUSA:EL MONTO DEL PAGO FUE DE UN 30% O MAYOR NUMERO DE CREDITO = '.$x_credito_num;				
						$mensajeMail = $x_mensaje."\n \n * Este mensaje ha sido enviado de forma automatica, por favor no lo responda. \n \n";			 
						// Mail it						
						mail($para, $titulo, $mensajeMail, $cabeceras);
						
						echo $x_mensaje."<BR>";	
		
				
				} 
        
    }
    
    

   
    
    }// while credito activo
    
  
    

?>
