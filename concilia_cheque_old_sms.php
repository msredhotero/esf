<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php session_start(); ?>
<?php
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$x_credito_id = $_GET["x_credito_id"]; #1
$x_forma_pago = $_GET["q2"];#2

	$x_result = "";
if($x_credito_id > 0) {
	
	    $sqlSe = "SELECT status FROM  `cheque_conciliado` WHERE credito_id= $x_credito_id " ;
		$rsSe = phpmkr_query($sqlSe, $conn) or die ("error alseleccioanr el status". phpmkr_error()."sql".$sqlSe);
		$rowSe = phpmkr_fetch_array($rsSe);
		$x_status = $rowSe["status"];
		
		if($x_status == 1){
			$sql  = "UPDATE `cheque_conciliado` SET status = 0 WHERE credito_id= $x_credito_id";
			if (($_SESSION["php_project_esf_status_UserRolID"] == 1)){
			$x_result .='<input type="checkbox" name="x_concilia_cheque'.$x_credito_id.'"  onclick="ConciliaCheque('.$x_credito_id.');" />';
			}			
			
			}else {
				$sql  = "UPDATE `cheque_conciliado` SET status = 1 WHERE credito_id= $x_credito_id";
			
				if (($_SESSION["php_project_esf_status_UserRolID"] == 4)){
				$x_result .='<input type="checkbox" name="x_concilia_cheque" disabled="disabled"  checked="checked" />';
				}else{
					$x_result .='<input type="checkbox" name="x_concilia_cheque'.$x_credito_id.'" checked="checked" onclick="ConciliaCheque('.$x_credito_id.');" />';
					}
				$x_result .= "Conciliado";
				}
	$rsSe = phpmkr_query($sql, $conn) or die ("error alseleccioanr el status". phpmkr_error()."sql".$sqlSe);
		echo $x_result;

}else{
 echo "No localizado";
}
?>