<?php

require_once '/../appconfig.php';
$appconfig	= new appconfig();
$datos		= $appconfig->conexion();
$hostname	= $datos['hostname'];
$database	= $datos['database'];
$username	= $datos['username'];
$password	= $datos['password'];

define("SERVIDOR_LOCAL", "http://localhost/crmcreditos.git/trunk/");
define("SERVIDOR",   "http://financieracrea.com/crmcreditos/");
 $directorio_local = ($_SERVER['SERVER_NAME']=='localhost')?'http://localhost/crmcreditos.git/trunk/':'http://'.$_SERVER['SERVER_NAME'].'/crmcreditos/';

define("DIR_LOCAL", $directorio_local);

define("DIR_UPLOAD", $directorio_local."upload/");


$mysqli = new mysqli($server, $username, $password, $database);
if ($mysqli->connect_errno) {
	echo "Fallo al contenctar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
$codifica =true;
if($codifica){
	$mysqli->set_charset("utf8");
}

?>
