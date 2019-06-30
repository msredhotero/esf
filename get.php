<?php session_start(); ?>
<?php ob_start(); ?>
<?php
//Continue the session

$x_tipo = $_GET["tipo"];

if ( ($_GET["txtsscode"] == $_SESSION["security_code"]) && (!empty($_GET["txtsscode"]) && !empty($_SESSION["security_code"])) ) {

	$resp = 1;

} else { //If CAPTCHA invalid!

	$resp = 0;
	
}

echo $resp;

?>