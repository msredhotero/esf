<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Pragma: no-cache"); // HTTP/1.0 
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>e - SF >  CREA Technologies</title>
</head>
<body>
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td>Mes/a&ntilde;o</td>
<td>Interes ordinario</td>
<td>iva</td>
<td>Interes moratorio</td>
<td>iva mora</td>
</tr>
<?php

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);


$x_year = 2007;
$x_mes = 4;

while ($x_year < 2013) {
	
	$sSql = "select sum(interes) as interes, sum(iva) as iva, sum(interes_moratorio) as moratorios, sum(iva_mor) as ivmor from vencimiento where year(fecha_vencimiento) = $x_year and month(fecha_vencimiento) = $x_mes";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
	$row = @phpmkr_fetch_array($rs);
	$x_interes = $row["interes"];
	$x_iva = $row["iva"];
	$x_moratorios = $row["moratorios"];
	$x_ivmor = $row["ivmor"];
	phpmkr_free_result($rs);
	
	echo "
	<tr>
		<td>$x_mes/$x_year</td>	
		<td>$x_interes</td>
		<td>$x_iva</td>
		<td>$x_moratorios</td>
		<td>$x_ivamor</td>						
	</tr>";
	
	$x_mes++;
	if($x_mes == 13){
		$x_mes = 1;
		$x_year++;	
	}
	
}
?>
</table>

<?php
// Close recordset and connection

phpmkr_db_close($conn);
?>
<?php include ("footer.php") ?>
