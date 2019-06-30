<?php session_start(); ?>
<?php ob_start(); ?>
<?php

// Initialize common variables

?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php include ("utilerias/datefunc.php") ?>
<?php

/* TEMP
$x_aut_id = @$_GET["a7657x545t"];
if (empty($x_aut_id)) {
	echo "no clave";
	exit();
}

*/


$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
$currdate = ConvertDateToMysqlFormat($currdate);

//$currdate = "2007-07-10";
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);


//VALIDA EJECUCCION PREVIA
/*
$sSqlWrk = "SELECT count(*) as ejecutado FROM pnm where fecha_registro = '$currdate'";
$rswrk = phpmkr_query($sSqlWrk,$conn) or exit();
$datawrk = phpmkr_fetch_array($rswrk);
if($datawrk["ejecutado"] > 0 ){
	exit();
}
@phpmkr_free_result($rswrk);
*/


//MORATORIOS
$sSqlWrk = "SELECT vencimiento.*, credito.credito_status_id, credito.importe as importe_credito, credito.tasa_moratoria, credito.credito_num, credito.tasa, forma_pago.valor as forma_pago_valor FROM vencimiento join credito 
on credito.credito_id = vencimiento.credito_id join forma_pago on forma_pago.forma_pago_id = credito.forma_pago_id 
where credito.credito_status_id in (1,4) and vencimiento.fecha_vencimiento < '$currdate' and vencimiento.vencimiento_status_id in (1,3) and credito.fecha_otrogamiento > '2008-5-11'";

//" and credito.credito_num = '283'";
$rswrk = phpmkr_query($sSqlWrk,$conn) or exit();
while($datawrk = phpmkr_fetch_array($rswrk)){
	$x_vencimiento_id = $datawrk["vencimiento_id"];
	$x_vencimiento_num = $datawrk["vencimiento_num"];	
	$x_fecha_vencimiento = $datawrk["fecha_vencimiento"];
	$x_vencimiento_status_id = $datawrk["vencimiento_status_id"];	
	$x_importe = $datawrk["importe"];	
	$x_interes = $datawrk["interes"];		
	$x_interes_moratorio = $datawrk["interes_moratorio"];			
	$x_credito_num = $datawrk["credito_num"];		
	$x_credito_status_id = $datawrk["credito_status_id"];
		
	if(is_null($x_interes_moratorio)){
		$x_interes_moratorio = 0;
	}

	$x_dias_vencidos = datediff('d', $x_fecha_vencimiento, $currdate, false);	
	
	if($x_dias_vencidos > 2){
	
		$x_semanas = ($x_dias_vencidos / 7);
		$x_semanas = ceil($x_semanas);
		$x_total_moratorios = (50 * $x_semanas);
		
		$x_total_calculado = $x_importe + $x_interes + $x_total_moratorios;
		
		$sSqlWrk = "update vencimiento set vencimiento_status_id = 3, interes_moratorio = $x_total_moratorios, total_venc = $x_total_calculado where vencimiento_id = $x_vencimiento_id ";	
		phpmkr_query($sSqlWrk,$conn);
	}

}
@phpmkr_free_result($rswrk);

phpmkr_db_close($conn);

?>
