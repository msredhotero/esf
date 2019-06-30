<?php 
die();
session_start(); ?>
<?php ob_start(); ?>

<?php
// seleccionamos todos los credito que esten totalmente vencidos
include ("db.php");
include ("phpmkrfn.php");
include("../crm/datefunc.php");
$conn = phpmkr_db_connect(HOST, USER, PASS,DB, PORT);

$X_HOY = date("Y-m-d");
$currdate = date("Y-m-d");

// seleccionamos los creditos que sean menor a 6000 en credito_num
$X_CONTADOR = 0;
$sqlCredito6 = "SELECT  * FROM membresia WHERE membresia.status = 1 and fecha_expiracion <= '$X_HOY' ";
$rsCredito6 = phpmkr_query($sqlCredito6,$conn) or die ("Error al seleccionar los c redito menores a 6000".phpmkr_error()."sql:".$sqlCredito6);
while($rowCredito6 = phpmkr_fetch_array($rsCredito6)){
	
	$x_membresia_id = $rowCredito6["membresia_id"];
// buscamos si tiene algun vencimiento vencido... si lo tiene le mandamos el mensaj 
// actualizamos el sttus de la mebresia
$sqlUpdate = "UPDATE membresia SET status = 2 WHERE membresia_id = $x_membresia_id ";
$rsUpdate = phpmkr_query($sqlUpdate,$conn) or die ("Error al actuilizar el status de la membresia".phpmkr_error()."sql:".$sqlUpdate);





}#while principal
?>
