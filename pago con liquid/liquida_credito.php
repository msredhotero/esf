<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>


<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

	$x_credito_id = 1;
	$x_solicitud_id = 100;
	$x_fecha_liquida = date("Y-m-d");
	$hora = getdate(time());
	$x_hora = $hora["hours"] . ":" . $hora["minutes"] . ":" . $hora["seconds"]; 

	
	//insertamos en la tabla de liquidacion
	$sqlLiquida = "INSERT INTO credito_liquidado (`credito_liquidado_id` ,`credito_id` ,`solicitud_id` ,";
	$sqlLiquida .= "`fecha` ,`hora` ,`status` ,`x` ,`xx` )";
	$sqlLiquida .= " VALUES (NULL , $x_credito_id, $x_solicitud_id, '$x_fecha_liquida', '$x_hora', '1', '1', '1');";
	$RSC = phpmkr_query($sqlLiquida, $conn) or die("error". phpmkr_error().$sqlLiquida) ;
	
?>
</body>
</html>