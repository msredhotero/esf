<?php
require_once '../appconfig.php'; 
require_once ('appconfig.php');	 
$appconfig	= new appconfig();
$datos		= $appconfig->conexion();
$hostname	= $datos['hostname'];
$database	= $datos['database'];
$username	= $datos['username'];
$password	= $datos['password'];

$mysqli = new mysqli($server, $username, $password, $database);
if ($mysqli->connect_errno) {
	echo "Fallo al contenctar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
$codifica =true;
if($codifica){
	$mysqli->set_charset("utf8");
}

?>
