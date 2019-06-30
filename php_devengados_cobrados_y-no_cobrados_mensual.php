<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

// seleccionamos todos los vencimientos que corresponden al mes anterior
$x_today =  date("Y-m-d");
$mes_anterior  = mktime(0, 0, 0, date("m")-1, date("d"),   date("Y"));
$mes_anterior = date("Y-m-d",$mes_anterior);
$ultimo_dia = mktime(0, 0, 0, date("m"), 0, date("Y"));
$ultimo_dia  = date("Y-m-d",$ultimo_dia);
$primer_dia =  mktime(0,0,0, date("m")-1, 01, date("y"));
$primer_dia = date("Y-m-d",$primer_dia);
echo "<br>".$ultimo_dia;
echo "<br>".$primer_dia ;

// seleccionamos todos los vencimientos que pertenezcan a las fechas de iniio y de fin de mes

// esos son todos los devengados

$sqlDevengados =  "SELECT venc.credito_id,venc.vencimiento_id,venc.vencimiento_status_id, venc.fecha_vencimiento FROM  vencimiento as venc, credito as cred WHERE venc.fecha_vencimiento >= \"$primer_dia\"  and venc.fecha_vencimiento <= \"$ultimo_dia\"  and cred.credito_status_id != 2  and venc.credito_id = cred.credito_id";
$rsVencimiento =  phpmkr_query($sqlDevengados,$conn) or die("error al selecciona los vencimientos devengados".phpmkr_error().$sqlDevengados);
$x_num = 1;
while($rowDevencgados = phpmkr_fetch_array($rsVencimiento)){
	$x_credito_id = $rowDevencgados["credito_id"];
	$x_vencimiento_id = $rowDevencgados["vencimiento_id"];
	$x_vencimiento_status = $rowDevencgados["vencimiento_status_id"];
	echo "credito".$x_credito_id." vencimiento ".$x_venciiento_id."<br>";
	
	// verifocamos si el vencimiento fue pagado o no
	if($x_vencimiento_status == 2){
		// esta pagado
		// se busc la fecha de pago
		$sqlRecibo =  "SELECT fecha_pago FROM recibo, recibo_vencimiento where recibo_vencimiento.recibo_id = recibo.recibo_id and recibo_vencimiento.vencimiento_id = $x_vencimiento_id";
		$rsRecibo =  phpmkr_query($sqlRecibo,$conn)or die ("Error al seleccinar fecha de pago".$sqlRecibo);
		$rowRecibo =  phpmkr_fetch_array($rsRecibo);
		$x_fecha_pago = $rowRecibo["fecha_pago"];
		
		$sqlInsertPagado =  "INSERT INTO `vencimiento_devengado_pagado` (`vencimiento_devengado_pagado_id`, `vencimiento_id`, `pagado_status_id`, `fecha`) VALUES (NULL, $x_vencimiento_id, '1', \"$x_fecha_pago\")";
		$rsInserPagado = phpmkr_query($sqlInsertPagado,$conn);		
		}else{			
			// no se pago
			$sqlInsertPagado =  "INSERT INTO `vencimiento_devengado_pagado` (`vencimiento_devengado_pagado_id`, `vencimiento_id`, `pagado_status_id`, `fecha`) VALUES (NULL, $x_vencimiento_id, '2', \"0000-00-00\")";
			$rsInserPagado = phpmkr_query($sqlInsertPagado,$conn);			
			
			}
	
	
	
	$sqlInsert = "INSERT INTO vencimiento_devengado (select * fROM vencimiento where vencimiento_id =$x_vencimiento_id )";
	$rsInsert = phpmkr_query($sqlInsert,$conn)or die (phpmkr_error().$sqlInsert);
	echo $sqlInsert."<br>";
	$x_num ++;
	
	
	}
	
	echo $x_num;

?>