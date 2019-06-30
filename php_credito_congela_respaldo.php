<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$x_credito_id = $_GET["x_credito_respaldo_id"]; #1
$x_today =  date("Y-m-d");
if($x_credito_id > 0) {	
		$sql  = "INSERT INTO `congela_respaldo` (`congela_respaldo_id`, `credito_respaldo_id`, `status`, `fecha` ) VALUES (NULL, $x_credito_id, '1')";
		$rsv = phpmkr_query($sql, $conn) or die("Error al seleccin".phpmkr_error()."sql :".$sql);			
			$x_result .= "RESPALDO DE CR&Eacute;DITO CONGELADO USTED NO PODRA EDITAR ESTE RESPALDO ";		
		echo $x_result;

}else{
 echo "No localizado";
}
?>