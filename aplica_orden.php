<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php session_start(); ?>
<?php

$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$x_convenio_orden_id = $_GET["x_convenio_orden_id"]; #1
$x_forma_pago = $_GET["q2"];#2

	$x_result = "";
if($x_convenio_orden_id > 0) {	
	    $sqlSe = "SELECT status_id FROM  `convenio_orden` WHERE convenio_orden_id = $x_convenio_orden_id " ;
		$rsSe = phpmkr_query($sqlSe, $conn) or die ("error alseleccioanr el status". phpmkr_error()."sql".$sqlSe);
		$rowSe = phpmkr_fetch_array($rsSe);
		$x_status = $rowSe["status_id"];
		
		if($x_status == 1){
			$sql  = "UPDATE `convenio_orden` SET status_id = 4 WHERE convenio_orden_id= $x_convenio_orden_id";
			if (($_SESSION["php_project_esf_status_UserRolID"] == 1) || (($_SESSION["php_project_esf_status_UserRolID"] == 4))){
			$x_result .='<input type="checkbox" name="convenio_orden'.$x_convenio_orden_id.'" checked="checked" onclick="ConvenioAplicado('.$x_convenio_orden_id.');" />';
			$x_result .= "ORDEN APLICADA";
			}
			$x_status = "ORDEN APLICADA";		
			}else {
				$sql  = "UPDATE `convenio_orden` SET status_id = 1 WHERE convenio_orden_id= $x_convenio_orden_id";			
				if (($_SESSION["php_project_esf_status_UserRolID"] == 12)){
				$x_result .='<input type="checkbox" name="aplica_propuesta" disabled="disabled"   />';
				}else{
					$x_result .='<input type="checkbox" name="aplica_orden'.$x_convenio_orden_id.'"  onclick="OrdenAplicado('.$x_convenio_orden_id.');" />';
					}
				
				$x_status = "PENDIENTE DE APLICAR";
			}
				
	$rsSe = phpmkr_query($sql, $conn) or die ("error al seleccionr el status". phpmkr_error()."sql".$sqlSe);
		echo $x_result."---".$x_status;

}else{
 echo "No localizado";
}