<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php session_start(); ?>
<?php
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$x_recibo_qr_id = $_GET["x_recibo_qr_id"]; #1

$x_forma_pago = $_GET["q2"];#2

	$x_result = "";
if($x_recibo_qr_id > 0) {	
	    $sqlSe = "SELECT conflicto FROM  `recibo_qr` WHERE recibo_qr_id = $x_recibo_qr_id " ;
		$rsSe = phpmkr_query($sqlSe, $conn) or die ("error alseleccioanr el status". phpmkr_error()."sql".$sqlSe);
		$rowSe = phpmkr_fetch_array($rsSe);
		$x_status = $rowSe["conflicto"];
		
		if($x_status == 1){
			$sql  = "UPDATE `recibo_qr` SET conflicto = 0 WHERE recibo_qr_id= $x_recibo_qr_id";
			if (($_SESSION["php_project_esf_status_UserRolID"] == 1)){
			$x_result .='<input type="checkbox" name="x_recibo_conflicto'.$x_recibo_qr_id.'"  onclick="ReciboConflicto('.$x_recibo_qr_id.');" />';
			}		
			}else {
				$sql  = "UPDATE `recibo_qr` SET conflicto = 1 WHERE recibo_qr_id= $x_recibo_qr_id";			
				if (($_SESSION["php_project_esf_status_UserRolID"] == 4)){
				$x_result .='<input type="checkbox" name="x_recibo_conflicto" disabled="disabled"  checked="checked" />';
				
				$para = "5554018885";
				//$para = "5540663927";
				$x_mensaje = "se registro un pago QR con conflicto id = $x_recibo_qr_id";
				$cabeceras = 'From: zortiz@createc.mx';
				mail($para, $titulo, $x_mensaje, $cabeceras);	
				}else{
					$x_result .='<input type="checkbox" name="x_recibo_conflicto'.$x_recibo_qr_id.'" checked="checked" onclick="ReciboConflicto('.$x_recibo_qr_id.');" />';
					}
				$x_result .= "pago en conflicto, mensaje enviado";
			}
			$rsSe = phpmkr_query($sql, $conn) or die ("error alseleccioanr el status". phpmkr_error()."sql".$sqlSe);
		echo $x_result;

}else{
 echo "No localizado";
}




?>