<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
</head>

<body>

<?php
$x_fecha_vencimiento = '2008-09-24';
$temptime = date('l',strtotime($fecha_act));
//$fecha_act = strftime('%Y%m%d',$temptime);
$dia = $temptime["weekday"];
//echo "FEcha: ".$fecha_act;
$x_dia = strtoupper(date('l',strtotime($x_fecha_vencimiento)));
echo "FEcha: ".$x_dia;
?>
</body>
</html>
