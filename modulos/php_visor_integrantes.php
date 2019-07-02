<?php include ("../../esf/db.php") ?>
<?php include ("../../esf/phpmkrfn.php") ?>
<?php

$x_cliente_id = @$_GET["key"];
$x_nombre = @$_GET["nombre"];
$x_paterno = @$_GET["paterno"];
$x_materno = @$_GET["materno"];
$x_rol = @$_GET["rol"];
$x_monto =@$_GET["monto"];
$x_numero = @$_GET["numero"];
$x_sol_id = @$_GET["sol_id"];
$x_grupo_id = $_GET["grupo_id"];

if(empty($x_cliente_id)){
	$x_cliente_id = @$_POST["x_cliente_id"];
	if(empty($x_cliente_id)){
		echo "No se losscalizaron los datos del cliente";
		exit();
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
<link href="../../esf/php_project_esf.css" rel="stylesheet" type="text/css" />
<style>
.body{
	margin-top:0px;
}
</style>
</head>
<body>
<form name="frm_visor" method="post" action="php_visor_integrantes.php">
<input type="hidden" name="x_credito_id" value="<?php echo $x_credito_id; ?>" />
<input type="hidden" name="x_cliente_id" value="modificado"/>
<p align="left">
<span>
<?php 
if($x_cliente_id == "newone"){
	// no se ha dado de lata al  clinete asi 	que lo dmos de alta ahora
	echo "<a href='tipoCuenta/formatos/formatoIndividualG.php?key=$x_cliente_id&nombre=$x_nombre&paterno=$x_paterno&materno=$x_materno&rol=$x_rol&soli_id=$x_sol_id&monto=$x_monto&numero=$x_numero&sol_id=$x_sol_id&grupo_id=$x_grupo_id' target='_blank'>Registrar Datos</a>";
	
	}else if($x_cliente_id == "modificado"){
		// el cliente ya existe solo lo modoficamos	
		//echo "<a href='php_solicitudeditIndividualGrupo.php?cliente_id=$x_cliente_id&solicitud_id=$x_sol_id' target='_blank'>Ver Datos</a>";
		echo "Datos Completos";		
		}else if($x_cliente_id == "vacio"){
			echo"&nbsp;";
			}else{
				//echo "Datos Completos";
				echo "<a href='php_solicitudeditIndividualGrupo.php?cliente_id=$x_cliente_id&solicitud_id=$x_sol_id' target='_blank'>Ver Datos</a>";
				}

?>
<?php 
	/*$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
	$sSqlWrk = "SELECT count(*) as cliente FROM cliente Where cliente_id = $x_cliente_id ";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$rowwrk = phpmkr_fetch_array($rswrk);
	$x_cliente = $rowwrk["cliente"];
	@phpmkr_free_result($rswrk);
	if($x_cliente > 0 ){
		echo "<a href='tipoCuenta/formatos/formatoIndivdualG.php?key=$x_credito_id' target='_blank'>Registrar Datos</a>";
	}else{
		echo "<a href='php_solicitudedit.php?key=$x_cliente_id' target='_blank'>Editar</a>";
	}*/
?>        
</span>
</p>
</form>
</body>
</html>
