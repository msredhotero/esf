<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>

<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php include ("utilerias/datefunc.php") ?>
<?php
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

// seleccioanmos todos los creditos que tiene vencimientos, vencidos
$sqlCV = "SELECT credito_id FROM vencimiento ";
$sqlCV .= " WHERE  vencimiento_status_id = 3";
$sqlCV .= " GROUP BY credito_id ";
$rsCV = phpmkr_query($sqlCV, $conn) or die ("Error al seleccionar los creditos vencidos". phpmkr_error()."sql:".$sqlCV);
while($rowCV = phpmkr_fetch_array($rsCV)){
	// mientras encuentre credito_id hace el proceso
	
	$x_credito_id_actual = $rowCV["credito_id"];
	#seleccionamos el primer vencimeinto que esta vencido del credito actual en el ciclo, PARA TENER LA FECHA MAS ANTIGUA Y SABER DESDE CUANDO ESTA VENCIDO
	$sqlVenc = "SELECT fecha_vencimiento ";
	$sqlVenc .= " FROM vencimiento ";
	$sqlVenc .= " WHERE credito_id = $x_credito_id_actual ";
	$sqlVenc .= " AND  vencimiento_status_id = 3 ";
	$sqlVenc .= " ORDER BY vencimiento.vencimiento_num ASC ";
	$sqlVenc .= " LIMIT 0,1 ";
	$rsVenc = phpmkr_query($sqlVenc,$conn) or die("Error al seleccioanr el primer vencido". phpmkr_error()."sql:".$sqlVenc);
	$rowVenc = phpmkr_fetch_array($rsVenc);
	$x_fecha_vencido = $rowVenc["fecha_vencimiento"];
	
	
	//INSERTAMOS EN LA TABLA CREDITO_VENCIDO_PERIODO
	$sqlInsert = "INSERT INTO `financ13_esf`.`credito_vencido_periodo` ";
	$sqlInsert .= " (`credito_vencido_periodo_id` ,`credito_id` ,`fecha` ,`fecha_pago` )";
	$sqlInsert .= " VALUES (NULL , $x_credito_id_actual, \"$x_fecha_vencido\", NULL);";
	$rsInsert = phpmkr_query($sqlInsert,$conn) or die ("Error al insertar". phpmkr_error()."sql:".$sqlInsert);
	
	}

?>

</body>
</html>