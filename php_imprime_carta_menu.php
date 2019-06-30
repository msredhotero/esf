<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>

<?php
include ("db.php");
include ("phpmkrfn.php");
$conn = phpmkr_db_connect(HOST, USER, PASS,DB, PORT);
$x_credito_id = $_GET["credito_id"];
// buscamos si tiene garantia liquida
$sqlGaratia =  "SELECT * FROM credito WHERE credito_id = $x_credito_id ";
$rsGarantia =  phpmkr_query($sqlGaratia,$conn) or die ("Error la seleccionar los dtaos del credito".phpmkr_error()."sql:".$sqlGaratia);
$rowGarantia =  phpmkr_fetch_array($rsGarantia);
$x_garantia_liquida =  $rowGarantia["garantia_liquida"];
$x_penalizacion =  $rowGarantia["penalizacion"];
mysql_close($conn);
?>
<center>
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="32">&nbsp;</td>
    <td width="32">&nbsp;</td>
    <td colspan="5"><center>CARTAS DE COBRANZA</center> </td>
    <td width="32">&nbsp;</td>
    <td width="37">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"><?php if ($x_garantia_liquida > 0 || $x_penalizacion > 0){ ?><a href="php_imprime_carta_1_garantia.php?credito_id=<?php echo $x_credito_id;?>" target="_blank">Carta 1</a><?php }else{?><a href="php_imprime_Carta_1.php?credito_id=<?php echo $x_credito_id;?>" target="_blank">Carta 1</a>  <?php }?></td>
    <td width="97">&nbsp;</td>
    <td width="175">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"><?php if ($x_garantia_liquida > 0 || $x_penalizacion > 0){?><a href="php_imprime_Carta_2_garantia.php?credito_id=<?php echo $x_credito_id?>" target="_blank">Carta 2</a><?php }else{?><a href="php_imprime_Carta_2.php?credito_id=<?php echo $x_credito_id?>" target="_blank">Carta 2</a><?php }?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"><?php if ($x_garantia_liquida > 0 || $x_penalizacion > 0){?><a href="php_imprime_Carta_3_garantia.php?credito_id=<?php echo $x_credito_id?>" target="_blank">Carta 3</a><?php }else{?><a href="php_imprime_Carta_3.php?credito_id=<?php echo $x_credito_id?>" target="_blank">Carta 3</a><?php }?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"><a href="php_imprime_Carta_3_mora.php?credito_id=<?php echo $x_credito_id?>" target="_blank">Carta Judicial Mora</a></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
   <tr>
    <td colspan="5">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table></center>

</body>
</html>