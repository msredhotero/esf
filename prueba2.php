
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<?php
/*
include("utilerias/datefunc.php");

$x_fecha_vencimiento = "2008-01-03";
$currdate = "2008-01-14"; 

$x_dias_vencidos = datediff('d', $x_fecha_vencimiento, $currdate, false);	
echo $x_dias_vencidos;
*/
$x_dias_vencidos = 15;
$x_semanas = ($x_dias_vencidos / 7);
$x_semanas = ceil($x_semanas);
$x_total_moratorios = (50 * $x_semanas);

echo ceil($x_total_moratorios); 

?>
</body>
</html>
