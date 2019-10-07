<?php include("../db.php") ?>
<?php include("../phpmkrfn.php") ?>
<?php

$x_solicitud_id = @$_GET["key"];

if(empty($x_solicitud_id)){
	$x_solicitud_id = @$_POST["x_solicitud_id"];
	if(empty($x_solicitud_id)){
		echo "No se losscalizaron los datos de la galeria";
		exit();
	}
}






?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../../esf/php_project_esf.css" rel="stylesheet" type="text/css" />
<title>Documento sin título</title>
</head>

<body>
<form name="frm_visor" method="post" action="php_visor_aval.php">
<input type="hidden" name="x_solicitud_id" value="<?php echo $x_solicitud_id; ?>" />
<?php
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

$sSqlWrk = "SELECT galeria_fotografica_id FROM galeria_fotografica Where solicitud_id = $x_solicitud_id  and tipo_galeria_id= 1";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$rowwrk = phpmkr_fetch_array($rswrk);
	$x_galeria_fotografica_id = $rowwrk["galeria_fotografica_id"];
	
	@phpmkr_free_result($rswrk);
	if($x_galeria_fotografica_id > 0 ){
		echo "<a href='../php_galeriaview.php?x_galeria_fotografica_id=$x_galeria_fotografica_id' target='_blank'>Ir a galeria cliente</a>";
	}else{
		echo " ";

	}


?>




</form>
</body>
</html>