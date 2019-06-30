<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>


<?php

$fecha = date("Y-m-d");

if($fecha < "2012-06-26"){
	echo "hoy es ".$fecha ." la fecha es menor de 30 de junio<br>";	
	
	}else{
		echo "la fecha es ".$fecha ."<br>";
		}

?>
</body>
</html>