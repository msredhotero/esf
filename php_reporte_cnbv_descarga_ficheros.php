<?php
//Si la variable archivo que pasamos por URL no esta 
//establecida acabamos la ejecucion del script.
//Utilizamos basename por seguridad, devuelve el 
//nombre del archivo eliminando cualquier ruta. 
$id = ($_GET['archivo']);
$enlace = 'reportes_cnbv/'.$archivo;
#echo "=>".$enlace ;
#$enlace = $path_a_tu_doc."/".$id;
$fichero = 'reportes_cnbv/'.$id;
/*if(!file_exists($enlace))
  { header ("HTTP/1.0 404 Not Found");
    return;
  }
  
header ("Content-Disposition: attachment; filename=".$id." ");
header ("Content-Type: application/octet-stream");
header ("Content-Length: ".filesize($enlace));
readfile($enlace);*/

if (file_exists($fichero)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($fichero).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($fichero));
    readfile($fichero);
    exit;
}else{
	echo "No se necontro fichereo".$fichero;
	}

?>