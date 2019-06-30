<?php
include_once("db.php");
include_once("phpmkrfn.php");

$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$x_masiva_pago_id = $_GET["x_mp_id"];

// update aplica pago

//ver le estado

$sqlS = "SELECT no_aplicar_pago FROM masiva_pago_2  WHERE masiva_pago_id = $x_masiva_pago_id ";
$response = phpmkr_query($sqlS, $conn)  or die("error". phpmkr_error()."sql:".$sqlS);
$row = phpmkr_fetch_array($response);
$x_aplica_pago = $row[no_aplicar_pago];
if($x_aplica_pago == 0){
$sqlU = "UPDATE masiva_pago_2  SET no_aplicar_pago = 1 WHERE masiva_pago_id = $x_masiva_pago_id ";
phpmkr_query($sqlU,$conn) or die("Error al actulizar el status en carga masiva".phpmkr_error()."sql:".$sqlU);
echo "<span style ='color: #CC0000;' ><STRONG>NO APLICAR</STRONG></span>";
} else if($x_aplica_pago == 1){
$sqlU = "UPDATE masiva_pago_2  SET no_aplicar_pago = 0 WHERE masiva_pago_id = $x_masiva_pago_id ";
phpmkr_query($sqlU,$conn) or die("Error al actulizar el status en carga masiva".phpmkr_error()."sql:".$sqlU);

	}
phpmkr_db_close($conn);


?>