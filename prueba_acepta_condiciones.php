<?php set_time_limit(0); ?>
<?php session_start(); ?>
<?php ob_start(); ?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php include ("utilerias/datefunc.php") ?>
<?php


$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
$currdate = ConvertDateToMysqlFormat($currdate);
$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];		

//$x_dia = strtoupper($currentdate["weekday"]);

//$currdate = "2007-07-10";
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);


$x_date_c = date("Y-m-d");
echo $x_date_c;


$sqlUpdateCond = "UPDATE `credito_condiciones` SET `status` = 1 WHERE fecha = '".$x_date_c."' ";
$rsUpdateCond = phpmkr_query($sqlUpdateCond, $conn) or die ("Error al actulizar las condicones de credito".phpmkr_error()."sql:". $sqlUpdateCond);

echo "<br> sql:".$sqlUpdateCond;

?>