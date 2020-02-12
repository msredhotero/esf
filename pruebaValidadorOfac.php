<?php

include ('includes/funcionesReferencias.php');

$serviciosReferencias		= new ServiciosReferencias();

$res = $serviciosReferencias->verificarListaNegraOfac('URDINOLAd GRAJALES, JULIO FABIO','ROSA MARGARITA ACOSTA PEREZ,','URDINOLA GRAJALESo, JULIO FABIO');

if ($res) {
	echo 1;
} else {
	echo 0;
}

?>