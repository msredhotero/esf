<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php session_start(); ?>
<?php
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$x_convenio_orden_id = $_GET["x_convenio_orden_id"]; #1
$x_forma_pago = $_GET["q2"];#2

	$x_result = "";
if($x_convenio_orden_id > 0) {	
	    $sqlSe = "SELECT archivado FROM  `convenio_orden` WHERE convenio_orden_id = $x_convenio_orden_id " ;
		$rsSe = phpmkr_query($sqlSe, $conn) or die ("error alseleccioanr el status". phpmkr_error()."sql".$sqlSe);
		$rowSe = phpmkr_fetch_array($rsSe);
		$x_status = $rowSe["archivado"];
		
		if($x_status == 1){
			$sql  = "UPDATE `convenio_orden` SET archivado = 0 WHERE convenio_orden_id= $x_convenio_orden_id";
			if (($_SESSION["php_project_esf_status_UserRolID"] == 1)){
			$x_result .='<input type="checkbox" name="convenio_orden'.$x_convenio_orden_id.'"  onclick="ConvenioOrden('.$x_convenio_orden_id.');" />';
			}		
			}else {
				$sql  = "UPDATE `convenio_orden` SET archivado = 1 WHERE convenio_orden_id= $x_convenio_orden_id";			
				if (($_SESSION["php_project_esf_status_UserRolID"] == 4)){
				$x_result .='<input type="checkbox" name="convenio_orden" disabled="disabled"  checked="checked" />';
				}else{
					$x_result .='<input type="checkbox" name="convenio_orden'.$x_convenio_orden_id.'" checked="checked" onclick="ConvenioOrden('.$x_convenio_orden_id.');" />';
					}
				$x_result .= "archivado";
			}
				
	$rsSe = phpmkr_query($sql, $conn) or die ("error al seleccionr el status". phpmkr_error()."sql".$sqlSe);
		echo $x_result;

}else{
 echo "No localizado";
}




?>