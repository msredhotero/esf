<?php


require_once("satxarre.php");
require_once("satxmlsv2.php");
$edidata = "";    // Genera cadena EDI en base a ERP
$data = satxarre($nufa);	// Genera arreglo asociativo de la factura
$xml = satxmlsv2($data,$edidata,"/dev/null","edi");    // Genera cadena XML en base a arreglo asociativo y cadena EDI
print $xml;		// Muestra el XML

?>
