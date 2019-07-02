<?php
$user = "financ13_admin61";
$hosts = "localhost";
$db = "financ13_esf";
$password = "creapwd35961";

include("db.php");
$conn = conexion($hosts, $user, $password, $db);

$sqlLista = "SELECT * FROM credito ";
$rs = query($sqlLista, $conn) or die("error al seleccinar los datos del credto ststus".mysql_error()."sql:".$sqlLista);
while($row = mysql_fetch_array($rs)){
	echo "<br> status_id ".$row[0];
	echo "  descripcion ".$row[1];
	
	
	}

?>