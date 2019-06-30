<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

$x_id = $_GET["id"];
$x_password = $_GET["password"];

$sqlPassword = "SELECT clave_comite FROM comite_credito_participantes_lista WHERE comite_credito_participantes_lista_id = $x_id ";
$rsPassword = phpmkr_query($sqlPassword,$conn) or die ("RROR AL SELCCIONAR E PASSWORD".phpmkr_error()."sql:".$sqlPassword);
$rowPassword = phpmkr_fetch_array($rsPassword);
$x_contraseña =  $rowPassword["clave_comite"];

if(strcmp($x_contraseña,$x_password)== 0){
	// la contraseña es correcta mandamos la imamen de contra correcta y quiamos le campo de password
	$x_respuesta = "<center><img src=\"images/login/login_ok_small.jpg\" width=\"96\" height=\"94\" /></center>";
	$x_respuesta .= "<center><input type=\"hidden\" name=\"x_oculto_".$x_id."\" class=\"campo_oculto\"  id=\"x_oculto_".$x_id."\"value=\"1\" /></center>";
	
	}else{
		
	$x_respuesta = "<center><img src=\"images/login/login_bat_small.jpg\" width=\"96\" height=\"94\" /></center><br>";
	$x_respuesta .= "<center>Datos incorrectos<br> vuelva a intentarlo. <input type=\"hidden\" class=\"campo_oculto\" name=\"x_oculto_".$x_id."\"  id=\"x_oculto_".$x_id."\" value=\"0\" /> </center>";
		
		}

echo $x_respuesta;
?>
