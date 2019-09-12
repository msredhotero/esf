<?php include("../db.php") ?>
<?php include("../phpmkrfn.php") ?>
<?php

$x_solicitud_id = @$_GET["key"];

if(empty($x_solicitud_id)){
	$x_solicitud_id = @$_POST["x_solicitud_id"];
	if(empty($x_solicitud_id)){
		echo "No se losscalizaron los datos del aval";
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

$sSqlWrk = "SELECT count(*) as aval, datos_aval_id, nuevos_campos FROM datos_aval Where solicitud_id = $x_solicitud_id  group by datos_aval_id";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$rowwrk = phpmkr_fetch_array($rswrk);
	$x_aval = $rowwrk["aval"];
	$x_datos_aval_id = $rowwrk["datos_aval_id"];
	$x_nuevos_campos = $rowwrk["nuevos_campos"];
	@phpmkr_free_result($rswrk);
	if($x_aval > 0 ){
		if($x_nuevos_campos == 1){
		echo "<a href='tipoCuenta/formatos/formato_aval_nuevos_camposedit.php?datos_aval_id=$x_datos_aval_id' target='_blank'>Editar Aval</a>";
			}else{
		echo "<a href='tipoCuenta/formatos/formato_avaledit.php?datos_aval_id=$x_datos_aval_id' target='_blank'>Editar Aval</a>";
			}
	}else{
		echo "<a href='tipoCuenta/formatos/formato_aval.php?datos_aval_id=$x_datos_aval_id&solicitud_id=$x_solicitud_id' target='_blank'>Agregar Aval</a>";

	}
?>
</form>
</body>
</html>