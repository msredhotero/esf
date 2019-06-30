<?php #include ("db.php") ?>
<?php #include ("phpmkrfn.php") ?>
<?php 
//$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);


function registraUsuario($user_id, $conn){
	$x_usuario =  $user_id;
	$sqlUsuario = "SELECT * FROM usuario  WHERE usuario_id = $x_usuario ";
	$rsUsuario = phpmkr_query($sqlUsuario,$conn) or die ("Error al seleccionar el usuario function".phpmkr_error()."sql;".$sqlUsuario);
	$rowUsuario = phpmkr_fetch_array($rsUsuario);
	$x_nombre =$rowUsuario["nombre"];
	
	$arrayPalabras = explode(" ", $x_nombre);
foreach($arrayPalabras as $palabra){
    $letra = $letra.$palabra{0};
}
	$x_iniciales = strtoupper($letra);
	
//	echo "iniciales desde funcion";
	return $x_iniciales;
	
	}

?>