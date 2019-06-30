<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php session_start(); ?>
<?php
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$x_garantia_id = $_GET["x_garantia_liquida_id"]; #1
$x_forma_pago = $_GET["q2"];#2

	$x_result = "";
if($x_garantia_id > 0) {	
	    $sqlSe = "SELECT conciliada FROM  `garantia_liquida` WHERE garantia_liquida_id = $x_garantia_id " ;
		$rsSe = phpmkr_query($sqlSe, $conn) or die ("error alseleccioanr el status". phpmkr_error()."sql".$sqlSe);
		$rowSe = phpmkr_fetch_array($rsSe);
		$x_status = $rowSe["conciliada"];
		
		if($x_status == 1){
			$sql  = "UPDATE `garantia_liquida` SET conciliada = 0 WHERE garantia_liquida_id= $x_garantia_id";
			if (($_SESSION["php_project_esf_status_UserRolID"] == 1)){
			$x_result .='<input type="checkbox" name="x_concilia_garantia'.$x_garantia_id.'"  onclick="ConciliaGarantia('.$x_garantia_id.');" />';
			}		
			}else {
				$sql  = "UPDATE `garantia_liquida` SET conciliada = 1 WHERE garantia_liquida_id= $x_garantia_id";			
				if (($_SESSION["php_project_esf_status_UserRolID"] == 4)){
				$x_result .='<input type="checkbox" name="x_concilia_garantia" disabled="disabled"  checked="checked" />';
				}else{
					$x_result .='<input type="checkbox" name="x_concilia_garantia'.$x_garantia_id.'" checked="checked" onclick="ConciliaGarantia('.$x_garantia_id.');" />';
					}
				$x_result .= "Conciliada";
			}
				
	$rsSe = phpmkr_query($sql, $conn) or die ("error al seleccionr el status". phpmkr_error()."sql".$sqlSe);
		echo $x_result;

}else{
 echo "No localizado";
}




?>