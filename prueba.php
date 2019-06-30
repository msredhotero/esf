<?php
//include("amount2txt.php");
//$x_valor = covertirNumLetras("5325.22");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>
<!---
<script type="text/javascript" src="amount2txt.js"></script>
-->
<script type="text/javascript">
<!--

function calcula(campo){

// alert(covertirNumLetras(campo.value));
 window.document.form1.textfield2.value = covertirNumLetras(campo.value);
}

//-->
</script>



<body>
<?php

include("utilerias/datefunc.php");

//$temptime = time();

$temptime = strtotime("2007-10-07");


//$temptime = DateAdd('ww',1,$temptime);
$fecha_fin = strftime('%Y-%m-%d',$temptime);
$x_dia = strftime('%A',$temptime);


echo $fecha_fin;
echo "<br>";
echo strtoupper($x_dia)."<br>";

if(strtoupper($x_dia) == "SUNDAY"){
echo "SI<br>";
$temptime = strtotime($fecha_fin);
$temptime = DateAdd('w',1,$temptime);
$fecha_fin = strftime('%Y-%m-%d',$temptime);
$x_dia = strftime('%A',$temptime);
echo $fecha_fin;
echo "<br>";
echo strtoupper($x_dia)."<br>";

}else{
echo "NO";
}


"
SELECT credito.credito_id, credito.credito_num
FROM vencimiento join credito 
on credito.credito_id = vencimiento.credito_id 
WHERE (credito.credito_status_id in (1,3)) AND 
(vencimiento.vencimiento_status_id in(3)) AND
(credito.credito_id not in (
SELECT vencimiento.credito_id 
FROM vencimiento join credito on credito.credito_id = vencimiento.credito_id 
JOIN solicitud on solicitud.solicitud_id = credito.solicitud_id 
WHERE (credito.credito_status_id in (1,3)) AND 
(vencimiento.vencimiento_status_id = 3)))
order by credito.credito_num



?>

</body>
</html>
