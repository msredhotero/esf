<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
$x_forma_pago_id = $_GET["x_forma_pago_id"];
$x_numero_pagos = $_GET["x_numero_pagos"];
$x_importe_solicitado = $_GET["x_importe_solicitado"];

$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
## calcula penalizaciones
// el campo de penalizacion saldra lleno por default
		$sqlPenalizacion = " SELECT * FROM penalizacion where forma_pago_id = ".$x_forma_pago_id." ";
		$rsPenalizacion = phpmkr_query($sqlPenalizacion, $conn) or die ("Error al selccionar los detos de penalizacion".phpmkr_error()."sql:". $sqlPenalizacion);
		$rowPenalizacion  = phpmkr_fetch_array($rsPenalizacion);
		$x_p_penalizacion_id = $rowPenalizacion["penalizacion_id"];
		$x_p_forma_pago_id = $rowPenalizacion["penalizacion_id"];
		$x_p_penalizacion_base = $rowPenalizacion["penalizacion_base"];
		$x_p_monto_base = $rowPenalizacion["monto_base"];
		$x_p_incremento_penalizacion = $rowPenalizacion["incremento_penalizacion"];
		$x_p_rango_para_incremento = $rowPenalizacion["rango_para_incremento"];
		if($x_importe_solicitado > $x_p_monto_base){
		$x_m = $x_importe_solicitado - $x_p_monto_base;		
		$x_m_2 = $x_m / $x_p_rango_para_incremento;		
		$x_m_2 = ceil($x_m_2);			
		$x_m_3 = round($x_m_2 * $x_p_incremento_penalizacion);		
		$x_m_4 = $x_m_3 + $x_p_penalizacion_base;
		}else{
			// si es menor la penalizacio es la indicada en la base
			$x_m_4 = $x_p_penalizacion_base;			
			
			}
		$x_penalizacion = $x_m_4;	
			
	echo "<input name=\"x_penalizacion\" type=\"text\" id=\"x_penalizacion\" value=\"".$x_penalizacion."\" size=\"20\" maxlength=\"50\" />";		
			
			
?>