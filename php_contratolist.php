<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

$x_solicitud_id = @$_GET["solicitud_id"];
if(empty($x_solicitud_id)){
	$x_solicitud_id = @$_POST["x_solicitud_id"];
	if(empty($x_solicitud_id)){
		echo "No se localizaron los contratos del cliente del cliente.";
		exit();
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
<style>
.body{
	margin-top:0px;
}
</style>
</head>
<body>


    
</span>
</p>
<table  align="center" width="700" border="0" cellspacing="0" cellpadding="0" class="ewTable_small"  >
  <tr>
    <td class="ewTableHeader">Anexos</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  
   <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><span class="phpmaker"><a href="php_pagare_print_v1_nomina_articulos.php?solicitud_id= <?php echo $x_solicitud_id;?>" target="_blank" > Articulos y disposiciones  </a></span></td>
  </tr> 
  <tr>
    <td><span class="phpmaker"><a href="php_pagare_print_v1_nomina_retencion.php?solicitud_id=<?php echo $x_solicitud_id;?>" target="_blank" > Consentimiento de retenci&oacute;n</a></span></td>
  </tr>
  <tr>
    <td><span class="phpmaker"><a href="php_pagare_print_v1_nomina_caratula.php?solicitud_id=<?php echo $x_solicitud_id;?>" target="_blank" > Car&aacute;tula de Cr&eacute;dito</a></span></td>
  </tr>
  <tr>
    <td><span class="phpmaker"><a href="php_pagare_print_v1_nomina_domicializacion.php?solicitud_id=<?php echo $x_solicitud_id;?>" target="_blank" > Autorizaci&oacute;n para domicializaci&oacute;</a></span></td>
  </tr>
   <tr>
    <td><span class="phpmaker"><a href="php_pagare_print_v1_nomina_privacidad.php?solicitud_id=<?php echo $x_solicitud_id;?>" target="_blank"> Aviso de Privacidad</a></span></td>
  </tr>
   
  
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>

</body>
</html>