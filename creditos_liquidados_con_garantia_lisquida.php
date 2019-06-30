<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>

<?php
###################################################################################
###################################################################################
############## Aplicar todas las garantis liquidas vigentes a la comision #########
###################################################################################
###################################################################################

// buscar todos los credito que tienen garantia liquida
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

$SQLCredito =  "SELECT * FROM  credito WHERE credito_status_id =3 AND garantia_liquida = 1 and fecha_otrogamiento > \" 2013-02-01\" ";
$rsCredito = phpmkr_query($SQLCredito,$conn) or die ("Errror al seleccionar los credito con garantia".phpmkr_error()."sql:".$SQLCredito);
while($rowCredito = phpmkr_fetch_array($rsCredito)){
	foreach($rowCredito as $campo => $valor){
		$$campo = $valor;		
		}
		

 $sqlGarania = "SELECT monto as monto_garantia, status as status_garantia FROM garantia_liquida WHERE credito_id =  $credito_id ";
	$rsGarantia =  phpmkr_query($sqlGarania,$conn) or die ("Error l seleccionar los montos de las grantias".phpmkr_error());
	$rowGarantia =  phpmkr_fetch_array($rsGarantia);
	foreach($rowGarantia as $campo => $valor){
		$$campo = $valor;		
		}
		$x_control =  $status_garantia;
		
		if($status_garantia == 1){
			$status_garantia = " vigente ";
			}
			
		if($status_garantia == 2){
			$status_garantia = " Aplicada a ultimo pago ";
			}
			
		if($status_garantia == 3){
			$status_garantia = " en deuda  ";
			}	
			
		if($status_garantia == 4){
			$status_garantia = " gastos de cobranza ";
			}	
			
		if($status_garantia == 7){
			$status_garantia = " PENDIENTE ";
			}			
			
		if($x_control == 1 || $x_control == 7)	{
echo "credito_id ". $credito_id ."NUM ".$credito_num . "status ".$credito_status_id." garantia ".$garantia_liquida;
		echo " status gar ".$status_garantia. " monto ".$monto_garantia."<br>";
		}
		
		
}