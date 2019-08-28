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

 $fecha_ayer = date('Y-m-d', mktime(0, 0, 0, date('m'),date('d')-1,date('Y')));

 echo "fecha que se buscara".$fecha_ayer;

 #echo "FECHA INICIO MES: ".$fecha_inico_mes." FECHA FIN DE MES: ".$fecha_fin_mes."<BR>";
 //seleccionamos los credito activos y los creditos liquidados que seran los que entren en el proceso
 /*
$sqlCreditosActivos = " SELECT DISTINCT recibo.recibo_id, recibo.importe, vencimiento_id, recibo_vencimiento.recibo_id, recibo.fecha_registro, COUNT( vencimiento_id ) AS total_pagos ";
$sqlCreditosActivos .= " FROM  `recibo_vencimiento` ";
$sqlCreditosActivos .= " JOIN recibo ON recibo.recibo_id = recibo_vencimiento.recibo_id ";
$sqlCreditosActivos .= " WHERE recibo_vencimiento.vencimiento_id >250878 ";
$sqlCreditosActivos .= " AND recibo.recibo_id >143243 ";
$sqlCreditosActivos .= " GROUP BY recibo.recibo_id ";
$sqlCreditosActivos .= " HAVING total_pagos >4 ";
$sqlCreditosActivos .= " ORDER BY  `recibo_vencimiento`.`recibo_id` ASC  ";
*/
$sqlCreditosActivos = "SELECT
    r.fecha_registro
    , count(r.fecha_pago) as total_pagos
    , max(v.vencimiento_id) as vencimiento_id
    , max(r.importe) as importe
    , c.credito_id
FROM
    credito c
    inner join vencimiento v
    on 			v.credito_id = c.credito_id
    inner join recibo_vencimiento rv
    on			v.vencimiento_id = rv.vencimiento_id
    inner join recibo r
    on			r.recibo_id = rv.recibo_id
    where r.fecha_registro > date_sub(now(), interval 2 month)
    group by r.fecha_registro, c.credito_id
    having count(r.fecha_pago)>4";

echo $sqlCreditosActivos."<br>";
$rsCreditosActivos = phpmkr_query($sqlCreditosActivos,$conn);
while($rowCreditoActivo = phpmkr_fetch_array($rsCreditosActivos)){

	$vencimiento_id = $rowCreditoActivo["vencimiento_id"];
	$numero_vencimientos = $rowCreditoActivo["total_pagos"];
	$importe_recibo = $rowCreditoActivo["importe"];
    echo "<br> importe recibo" .$importe_recibo. "numero de venc=>".$numero_vencimientos."<br><br>" ;

	// seleccionamos los datos del credito
	$sql_credito_id = "SELECT  c.credito_id, c.credito_status_id, c.solicitud_id, c.credito_tipo_id, c.credito_tipo_id, c.credito_num, c.importe FROM credito AS c JOIN vencimiento ON vencimiento.credito_id =  c.credito_id WHERE 	vencimiento.vencimiento_id =  ".$vencimiento_id ." ";
    $rsCreditosID = phpmkr_query($sql_credito_id,$conn) or die ($sql_credito_id);
	echo  "<br><br>".$sql_credito_id;


	while($rowCreditoID = phpmkr_fetch_array($rsCreditosID)){
	$x_credito_id = $rowCreditoID["credito_id"];
    $x_credito_status_id = $rowCreditoID["credito_status_id"];
    $x_solicitud_id= $rowCreditoID["solicitud_id"];
    $x_credito_tipo_id = $rowCreditoID["credito_tipo_id"];
    $x_credito_num = $rowCreditoID["credito_num"];
	$total_pagos = $rowCreditoID["total_pagos"];

	if($x_credito_id== 7300){
		echo "entraaaaaaaaaaaaaaa***************<br><br>";
		}
	}

    //seleccionamos el cliente_id
    $sqlCliente = "SELECT cliente_id FROM solicitud_cliente  WHERE solicitud_id = ".$x_solicitud_id." ";
    $rsCliente = phpmkr_query($sqlCliente,$conn);
    $rowCliente = phpmkr_fetch_array($rsCliente);
    $x_cliente_id = $rowCliente["cliente_id"];

	// BUCAMOS SI LA ALERTA YA ESXISTE
				$SQL_alerta =  "SELECT  * FROM reporte_cnbv WHERE solicitud_id = ".$x_solicitud_id." AND cliente_id = ".$x_cliente_id. " AND  razon_reporte  LIKE  '%DEPOSITO DE 5 VENCIMIENTOS O MAS EN CREDITO NUM %'  ";
				$rs_ALERT = phpmkr_query($SQL_alerta,$conn) or die("Error al LISTADO ALERTAS ".phpmkr_error()."sql :".$SQL_alerta);
				$numero_de_alertas =  phpmkr_num_rows($rs_ALERT);

				echo  "ALERTAS>>>>>>>>>>>>>>>>". $SQL_alerta;
				echo "<br>".$numero_de_alertas."zzz";
				echo "<br>".$numero_de_alertas." credito_num==>".$x_credito_num;

    if($x_credito_id != '' && $numero_de_alertas < 1 ){




				// se genera la alerta ya que se liquido en una sola exhibicion
				$x_today = date("Y-m-d");

				echo "CREDITO_ID=>".$x_credito_id." CREDITO NUM  =>".$x_credito_num." monto_pagado =>".$x_monto_pagado." 30%=> ".$x_30_porciento."<br>";
				echo  $sqlVencimientos."<br>";

				// se registra la alerta
				$_SESSION["ewmsg"] .= '<BR><strong>SE REGISTRO UNA ALERTA PLD PARA ESTE CREDITO, CAUSA: DEPOSITO DE 5 VENCIMIENTOS O MAS </strong><br>';
				$sSql = "INSERT INTO `reporte_cnbv` (`reporte_cnbv_id`, `cliente_id`, `solicitud_id`, `tipo_reporte_id`, `fecha_registro`, `razon_reporte`, `monto`)";

				 $sSql .= "VALUES (NULL,".$x_cliente_id.",".$x_solicitud_id.", '2', '".$x_today."', 'DEPOSITO DE 5 VENCIMIENTOS O MAS EN CREDITO NUM ".$x_credito_num." TOTAL DE VENCIMIENTOS ".$numero_vencimientos." ',".$importe_recibo.");";
				 $rs = phpmkr_query($sSql,$conn) or die("Error al seleccionar los datos de la solicitud ".phpmkr_error()."sql :".$sSql);

						$para  = 'oficialdecumplimiento@financieracrea.com'; // atencion a la coma
						// subject
						$titulo = 'ALERTA PLD '.$x_today;
						$cabeceras = 'From: zortiz@createc.mx';
						$x_mensaje	= 'SE REGISTRO UNA ALERTA PLD PARA ESTA SOLICITUD, CAUSA: DEPOSITO DE 5 VENCIMIENTOS O MAS EN CREDITO  = '.$x_credito_num;
						$mensajeMail = $x_mensaje."\n \n * Este mensaje ha sido enviado de forma automatica, por favor no lo responda. \n \n";
						// Mail it
						mail($para, $titulo, $mensajeMail, $cabeceras);

						echo $x_mensaje."<BR>";




	}



    }// while credito activo




?>
