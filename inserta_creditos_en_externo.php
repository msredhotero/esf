<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>

<?php
## SELECCIONAMOS LOS CAMPOS SOLICITUD_ID, CREDITO_ID FROM CREDITO CUANDO EL NUMERO DE CREDITO CORRESPOENDA AL  NUMERO DE CREDITO DE LA LISTA QUE SE OTORGA EN EXCEL.

## DESPUES SE INSERTAN LOS REGISTROS EN LA TABLA CREDITO_EN_EXTERNO 


$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);


$x_lista_numeros_creditos = "3306,3387,3609,3620,3692,3693,3733,3734,3745,3751,3752,3788,3808,3857,3869,3870,3882,3897,3903,3928,3956,";
$x_lista_numeros_creditos .="3969,3974,3981,3982,4051,4144,4183,4184,4289,4309,4373,5593,5594,5653,5654,5655,5656,5657,5736,5737,5738,5739,5740,5926,5927,5928,5929,5930";


$sqlBusca = "SELECT credito_id, solicitud_id FROM credito WHERE credito_num in ( ".$x_lista_numeros_creditos .")";
$rsBusca = phpmkr_query($sqlBusca, $conn) or die ("Erroe al seleccionar los datos en credito". phpmkr_error()."sql:".$sqlBusca);
while($rowBusca = phpmkr_fetch_array($rsBusca)){
	// por cada uno se inserta en credito_en_externo
	
	$x_credito_id = $rowBusca["credito_id"];
	$x_solictud_id = $rowBusca["solicitud_id"];
	$x_today = date("Y-m-d");
	
	$sqlInserta = "INSERT INTO `credito_en_externo` (`credito_en_externo_id` ,`credito_id` ,`solicitud_id` ,`fecha`)";
	$sqlInserta .= " VALUES ( NULL , $x_credito_id, $x_solictud_id, '$x_today')";
	$rsInserta = phpmkr_query($sqlInserta, $conn) or die ("no se pudo insertar". phpmkr_error()."sql".$sqlInserta);
	echo $sqlInserta ."<br>";
	}



?>