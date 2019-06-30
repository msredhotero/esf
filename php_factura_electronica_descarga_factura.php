<?php
$x_nombre_fichero = "facturas_notas_clientes_txt/".$_GET["nombre_fichero"];
$x_descarga = DescargarArchivo($x_nombre_fichero);
    function DescargarArchivo($fichero){

    $basefichero = basename($fichero);

    header( "Content-Type: application/octet-stream");

    header( "Content-Length: ".filesize($fichero));

    header( "Content-Disposition:attachment;filename=" .$basefichero."");
    readfile($fichero);
    }


?>