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

$SQLCredito =  "SELECT * FROM  credito WHERE credito_status_id != 2 AND credito_status_id != 3 AND garantia_liquida = 1 ";
$rsCredito = phpmkr_query($SQLCredito,$conn) or die ("Errror al seleccionar los credito con garantia".phpmkr_error()."sql:".$SQLCredito);
while($rowCredito = phpmkr_fetch_array($rsCredito)){
	foreach($rowCredito as $campo => $valor){
		$$campo = $valor;		
		}
	// datos de la garantia 
	
	$sqlGarania = "SELECT monto as monto_garantia, status as status_garantia FROM garantia_liquida WHERE credito_id =  $credito_id ";
	$rsGarantia =  phpmkr_query($sqlGarania,$conn) or die ("Error l seleccionar los montos de las grantias".phpmkr_error());
	$rowGarantia =  phpmkr_fetch_array($rsGarantia);
	foreach($rowGarantia as $campo => $valor){
		$$campo = $valor;		
		}
		
	//datos de la comision 
	$sqlComison = "SELECT vencimiento_id, total_venc as monto_comision FROM  vencimiento WHERE credito_id = $credito_id and vencimiento_num =  3001 ";
	$rsComison =  phpmkr_query($sqlComison,$conn) or die ("Error al seleccinar el query comison".phpmkr_error()."sql-:");
	$rowComision =  phpmkr_fetch_array($rsComison);	
	foreach($rowComision as $campo => $valor){
		$$campo = $valor;		
		}	
		
		
	// si el monto de la garantia es igual amonto de la comision entonces se aplica la garantia liquida coo pago a las comison
	 //existe la cmision ?
	 
	 if(!empty($monto_comision) and $monto_comision > 0){
		 // ejecutamos la operacion
		 
		 if($monto_comision ==  $monto_garantia ){
			 
			 
			 // verificamos el estatus de la garantia
			 if($status_garantia == 1 || $status_garantia == 7){
				 // se puede aplicar la garatia a la comison 
				 			
				 	$x_referencia_pago2 = "SE APLICO LA GARANTIA LIQUIDA A LA COMISION ";
					$x_banco_id = 1;
					$x_medio_pago_id = 7;
					$x_referencia_pago_1 = "INBURSA";
					$fieldList = NULL;
					// Field recibo_status_id
					$fieldList["`recibo_status_id`"] = 1;
											
					$theValue = ($x_banco_id != "") ? intval($x_banco_id) : "0";
					$fieldList["`banco_id`"] = $theValue;
					
					// Field medio_pago_id
					$theValue = ($x_medio_pago_id != "") ? intval($x_medio_pago_id) : "NULL";
					$fieldList["`medio_pago_id`"] = $theValue;
				
					// Field referencia_pago
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_referencia_pago_1) : $x_referencia_pago_1; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`referencia_pago`"] = $theValue;
				
					// Field referencia_pago
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_referencia_pago2) : $x_referencia_pago2; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`referencia_pago_2`"] = $theValue;
				
					// Field fecha_registro
					$theValue = (date("Y-m-d") != "") ? " '" . ConvertDateToMysqlFormat(date("Y-m-d")) . "'" : "Null";
					$fieldList["`fecha_registro`"] = $theValue;
				
					// Field fecha_registro
					$theValue = (date("Y-m-d") != "") ? " '" . ConvertDateToMysqlFormat(date("Y-m-d")) . "'" : "Null";
					$fieldList["`fecha_pago`"] = $theValue;
				
					// Field importe
					$theValue = ($monto_garantia != "") ? " '" . doubleval($monto_garantia) . "'" : "NULL";
					$fieldList["`importe`"] = $theValue;
				
					// insert into database
					$sSql = "INSERT INTO `recibo` (";
					$sSql .= implode(",", array_keys($fieldList));
					$sSql .= ") VALUES (";
					$sSql .= implode(",", array_values($fieldList));
					$sSql .= ")";
					//phpmkr_query($sSql, $conn) or die("Failed to execute query RECIBO PARA COMISION PAGADA CON GARANTIA....: " . phpmkr_error() . '<br>SQL: ' . $sSql);
				#	echo "INSERTA EN RECIBO".$sSql."<BR>";
					$x_recibo_id_v9 = mysql_insert_id();
				echo  $sSql."<br>";
				
					$sSql = "insert into recibo_vencimiento values(0,$vencimiento_id,$x_recibo_id_v9)";
					//$x_result = phpmkr_query($sSql, $conn);
					echo 	$sSql."<br>";
					
					
					$sqlUV9 = "UPDATE vencimiento SET vencimiento_status_id = 2 WHERE vencimiento_id = $vencimiento_id and vencimiento_status_id in (1,9)";
						//$x_result = phpmkr_query($sqlUV9,$conn);
						if(!$x_result){
							echo phpmkr_error() . '<br>SQL: ' . $sqlUV9;
							
							//exit();
						}	
						echo $sqlUV9."<br>";
					//actualizamos el status de la garantia
						$updag = "UPDATE  garantia_liquida SET status = 4 WHERE  credito_id = $x_credito_id and status= 1";
						//$x_result = phpmkr_query($updag,$conn);
						if(!$x_result){
							echo phpmkr_error() . '<br>SQL: ' . $updag;
							//phpmkr_query('rollback;', $conn);	 
							//exit();
						}	
				echo $updag."+";
				
				 
				 }// garatia se puede usar
			  
			 } //if cmison  == garantias
		 
		 }	// if comison > 0
	
	
	die();
	
	}






// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$filter = array();

?>