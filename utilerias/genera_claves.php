<?php
include("gen_pass_ec.php");

$x_cantidad = 500;
$x_contador = 1;
$x_sql = "";

while($x_contador <= $x_cantidad){
	$x_usuario = generate(8, "No", "Yes", "Yes");
	$x_clave = generate(8, "No", "Yes", "Yes");
	echo "usuario: ".$x_usuario." Password: ".$x_clave."<br>";
	$x_sql .= "insert into usuario values(0,'Expetro 2006','$x_usuario','$x_clave');<br>COMMIT;<br>";
	$x_contador = $x_contador + 1;
}

echo $x_sql;

?>