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

 $x_cont =1;
$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];
$currdate = ConvertDateToMysqlFormat($currdate);
$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];

$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
#######################################

$x_monto_genera_alerta_1 = 0;
###########################################
 echo date("Y-m-d")."<br>";


 echo "<p>date('Y-m-d', mktime(0, 0, 0, date('m'),date('d')-1,date('Y')))".date('Y-m-d', mktime(0, 0, 0, date('m'),date('d')-1,date('Y')))."</p>";


 //die();
 echo "fecha que se buscara".$fecha_ayer;
 $fecha_inico_mes = $digito_año."-".$digito_mes."-".$digito_primer_dia;
 #echo "FECHA INICIO MES: ".$fecha_inico_mes." FECHA FIN DE MES: ".$fecha_fin_mes."<BR>";
 //seleccionamos los credito activos y los creditos liquidados que seran los que entren en el proceso
$sqlCreditosActivos = "SELECT credito_id, credito_status_id, solicitud_id, credito_tipo_id, credito_tipo_id, credito_num, importe FROM credito    where credito_status_id = 3 and credito_id > 6000 ";
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

    if($x_credito_status_id != '' ){
			$fecha_ayer = '2013-06-21';
			$sqlVencimientos_f =  "SELECT  sum(recibo.importe) as monto_pago, credito.credito_id, recibo . * ";
			$sqlVencimientos_f .= " FROM recibo ";
			$sqlVencimientos_f .= " JOIN recibo_vencimiento ON recibo.recibo_id = recibo_vencimiento.recibo_id ";
			$sqlVencimientos_f .= " JOIN vencimiento ON vencimiento.vencimiento_id = recibo_vencimiento.vencimiento_id ";
			$sqlVencimientos_f .= " JOIN credito ON credito.credito_id = vencimiento.credito_id ";
			$sqlVencimientos_f .= " WHERE credito.credito_id = ".$x_credito_id. " and fecha_pago ='".$fecha_ayer."'";
			$sqlVencimientos_f .= " GROUP BY fecha_pago ";

			$rsVen_rec_f=  phpmkr_query($sqlVencimientos_f,$conn)or die("Error al buscar todos los vencimientos del credito liquidado".$sqlVencimientos_f );

   			$rowVen_pago = phpmkr_fetch_array($rsVen_rec_f);
    		$x_monto_pagado = $rowVen_pago["monto_pago"];


			$sqlNumeroVencimientos = "SELECT * FROM  vencimiento  WHERE credito_id = ".$x_credito_id. " ";
			$rsVen_no=  phpmkr_query($sqlNumeroVencimientos,$conn)or die("Error al buscar todos los vencimientos del credito liquidado".$sqlNumeroVencimientos );
       		$numero_vencimientos =  phpmkr_num_rows($rsVen_no);

			#if($numero_fechas == 1 && $numero_vencimientos> 1){

			if($x_monto_pagado > $x_30_porciento ){
				// se genera la alerta ya que se liquido en una sola exhibicion
				$x_today = date("Y-m-d");

				echo "CREDITO_ID=>".$x_credito_id." CREDITO NUM  =>".$x_credito_num." monto_pagado =>".$x_monto_pagado." 30%=> ".$x_30_porciento."<br>";
			echo  $sqlVencimientos."<br>";

				// se registra la alerta
				$_SESSION["ewmsg"] .= '<BR><strong>SE REGISTRO UNA ALERTA PLD PARA ESTE CREDITO, CAUSA: EL MONTO DEL PAGO FUE DE UN 30% O MAYOR</strong><br>';
				$sSql = "INSERT INTO `reporte_cnbv` (`reporte_cnbv_id`, `cliente_id`, `solicitud_id`, `tipo_reporte_id`, `fecha_registro`, `razon_reporte`, `monto`)";

				 $sSql .= "VALUES (NULL,".$x_cliente_id.",".$x_solicitud_id.", '2', '".$x_today."', 'EL MONTO DEL PAGO FUE DE UN 30% O MAYOR ',$x_monto_pagado);";
				 $rs = phpmkr_query($sSql,$conn) or die("Error al seleccionar los datos de la solicitud ".phpmkr_error()."sql :".$sSql);

						$para  = 'oficialdecumplimiento@financieracrea.com'; // atencion a la coma
						// subject
						$titulo = 'ALERTA PLD '.$x_today;
						$cabeceras = 'From: zortiz@createc.mx';
						$x_mensaje	= 'SE REGISTRO UNA ALERTA PLD PARA ESTA SOLICITUD, CAUSA:EL MONTO DEL PAGO FUE DE UN 30% O MAYOR SOLICITUD_ID = '.$x_solicitud_id;
						$mensajeMail = $x_mensaje."\n \n * Este mensaje ha sido enviado de forma automatica, por favor no lo responda. \n \n";
						// Mail it
						mail($para, $titulo, $mensajeMail, $cabeceras);

						echo $x_mensaje."<BR>";
				}



	}



    }// while credito activo




?>
