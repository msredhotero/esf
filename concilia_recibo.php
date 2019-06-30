<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php session_start(); ?>
<?php
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$x_recibo_qr_id = $_GET["x_recibo_qr_id"]; #1


	$x_result = "";
if($x_recibo_qr_id > 0) {	
	    $sqlSe = "SELECT archivado FROM `recibo_qr` WHERE recibo_qr_id = $x_recibo_qr_id " ;
		$rsSe = phpmkr_query($sqlSe, $conn) or die ("error alseleccioanr el status". phpmkr_error()."sql".$sqlSe);
		$rowSe = phpmkr_fetch_array($rsSe);
		$x_status = $rowSe["archivado"];
		
		if($x_status == 1){
			$sql  = "UPDATE `recibo_qr` SET archivado = 0 WHERE recibo_qr_id = $x_recibo_qr_id";
			if (($_SESSION["php_project_esf_status_UserRolID"] == 1)){
			$x_result .='<input type="checkbox" name="x_archivo_recibido'.$x_recibo_qr_id.'"  onclick="ArchivoRecibo('.$x_recibo_qr_id.');" />';
			}		
			}else {
				$sql  = "UPDATE `recibo_qr` SET archivado = 1 WHERE recibo_qr_id = $x_recibo_qr_id";			
				if (($_SESSION["php_project_esf_status_UserRolID"] == 4)){
				$x_result .='<input type="checkbox" name="x_archivo_recibo" disabled="disabled"  checked="checked" />';
				}else{
					$x_result .='<input type="checkbox" name="x_archivo_recibo'.$x_recibo_qr_id.'" checked="checked" onclick="ArchivaRecibo('.$x_recibo_qr_id.');" />';
					}
				$x_result .= "Archivado";
			}
				$rsSe = phpmkr_query($sql, $conn) or die ("error alseleccioanr el status". phpmkr_error()."sql".$sqlSe);
		echo $x_result;

}else{
 echo "No localizado";
}




?>