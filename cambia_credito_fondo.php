<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$x_credito_id = $_GET["x_credito_id"];
//echo $x_credito_id;
// select 
if(!empty($x_credito_id)){
$sqlFondo = "SELECT * FROM fondeo_colocacion where credito_id = $x_credito_id ";
$rsFondeo = phpmkr_query($sqlFondo,$conn) or die("Erro al seleccionar le fondeo ".phpmkr_error()."sql:".$sqlFondo);
$rowFondeo = phpmkr_fetch_array($rsFondeo);
$x_fondeo = $rowFondeo["fondeo_credito_id"];
if($x_fondeo == 6){
	// fondeos propios se cambia el credito a pronafim
	$update = "UPDATE fondeo_colocacion SET  fondeo_credito_id = 7 where credito_id = $x_credito_id and fondeo_credito_id = 6 ";
	$rsUpdate = phpmkr_query($update,$conn)or die ("Erro al actualizar el fondo".phpmkr_error()."sql :".$update);
	$x_resultado =	"FIM";
	}else{
	// fondeos propios se cambia el credito a propios
	$update = "UPDATE fondeo_colocacion SET  fondeo_credito_id = 6 where credito_id = $x_credito_id and fondeo_credito_id = 7 ";
	$rsUpdate = phpmkr_query($update,$conn)or die ("Erro al actualizar el fondo".phpmkr_error()."sql :".$update);
		$x_resultado =	"FP";
		}
}

echo $x_resultado."\n";

