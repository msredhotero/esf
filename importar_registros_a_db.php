<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

//obtenemos el archivo .csv
$tipo = $_FILES['archivo']['type'];
 
$tamanio = $_FILES['archivo']['size'];
 
$archivotmp = $_FILES['archivo']['tmp_name'];
 
//cargamos el archivo
$lineas = file($archivotmp);
 $conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
//inicializamos variable a 0, esto nos ayudará a indicarle que no lea la primera línea
$i=0;
 
//Recorremos el bucle para leer línea por línea
foreach ($lineas as $linea_num => $linea)
{ 
   //abrimos bucle
   /*si es diferente a 0 significa que no se encuentra en la primera línea 
   (con los títulos de las columnas) y por lo tanto puede leerla*/
   if($i != 0) 
   { 
       //abrimos condición, solo entrará en la condición a partir de la segunda pasada del bucle.
       /* La funcion explode nos ayuda a delimitar los campos, por lo tanto irá 
       leyendo hasta que encuentre un ; */
       $datos = explode(",",$linea);
 
       //Almacenamos los datos que vamos leyendo en una variable
       //usamos la función utf8_encode para leer correctamente los caracteres especiales
       $campo1 = utf8_decode($datos[1]);
	   $campo2 = utf8_decode($datos[2]);
	   $campo3 = utf8_decode($datos[3]);
	   $campo4 = utf8_decode($datos[4]);
	   $campo5 = utf8_decode($datos[5]);
	   $campo6 = utf8_decode($datos[6]);
	   $campo7 = utf8_decode($datos[7]);
       //guardamos en base de datos la línea leida
       
 		$sSqlWrk = "INSERT INTO csv_lista_negra_cnbv3(id_csv_lista_negra_cnbv,nombre_completo,rfc,fecha_reporte,monto,entidad_reporta,notas) VALUES(NULL,'$campo1','$campo2','$campo3','$campo4','$campo5','$campo6')";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	echo $sSqlWrk."<br>";
       //cerramos condición
   }
 
   /*Cuando pase la primera pasada se incrementará nuestro valor y a la siguiente pasada ya 
   entraremos en la condición, de esta manera conseguimos que no lea la primera línea.*/
   $i++;
   //cerramos bucle
}
?>
</body>
</html>