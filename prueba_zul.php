<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php include ("utilerias/datefunc.php") ?>

<?php 
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

$x_fecha_promesa_pago_c2 = date("Y-m-d");

// se agregan dos dias a la fecha de promesa de pago para aseurar que el pago ya se registro
				$sqlADDDAY = "SELECT DATE_ADD(\"$x_fecha_promesa_pago_c2\", INTERVAL 2 DAY) as dias_gracia ";
				$rsADDDAY = phpmkr_query($sqlADDDAY,$conn)or die ("Error al gragar dias a la PPC2".phpmkr_error()."sql:");
				$rowADDDAY = phpmkr_fetch_array($rsADDDAY);
				$x_fecha_promesa_pago_c2 = $rowADDDAY["dias_gracia"];
				
				echo "hoy mas 2 dias ".$x_fecha_promesa_pago_c2;
				
				phpmkr_db_close($conn);	


?>

