<?php set_time_limit(0); ?>
<?php session_start(); ?>
<?php ob_start(); ?> 
<?php

// Initialize common variables

?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php include ("utilerias/datefunc.php") ?>


<?php

$currdate = "2018-09-25";
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$sSqlWrk = "SELECT * FROM vencimiento WHERE `vencimiento_num` >2000 AND `iva_mor` =0  GROUP BY credito_id";
$x_cont = 1;
$rswrkmain = phpmkr_query($sSqlWrk,$conn) or die("error el query 1".phpmkr_error()."ql. :");
while($datawrkmain = phpmkr_fetch_array($rswrkmain)){
	$x_vencimiento_id = $datawrkmain["vencimiento_id"];
	$x_credito_id = $datawrkmain["credito_id"];
	
	
	$sqlCredito = " SELECT iva, credito_num, penalizacion, credito_status_id FROM credito WHERE credito_id = $x_credito_id";
	$rswrkCredito= phpmkr_query($sqlCredito,$conn) or die("error el query 2".phpmkr_error()."ql. :");
	while($dataCredito = phpmkr_fetch_array($rswrkCredito)){	
	if($dataCredito["iva"] == 1  && $dataCredito["penalizacion"]>0 &&  ($dataCredito["credito_status_id"] == 1 || $dataCredito["credito_status_id"] == 4 || $dataCredito["credito_status_id"] == 5) ){
		
		echo $x_cont ." El CREDITO NUM =>".$dataCredito["credito_num"]." tiene iva  y no se refleja en las penalizaciones  ".$dataCredito["iva"]."===>CREDITO ID: ".$x_credito_id. " PENALIZACION:".$dataCredito["penalizacion"]." <br> ";
		$x_cont ++;
		}	 
		
		
	}
}
