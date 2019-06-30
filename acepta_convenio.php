<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$x_credito_id = $_GET["x_credito_id"]; #1
$x_forma_pago = $_GET["q2"];#2


//buscamos los datos
$x_hoy = date("Y-m-d");

$sqlUltimoPago = "SELECT * FROM vencimiento  WHERE credito_id = $x_credito_id ORDER BY fecha_vencimiento DESC LIMIT 0 , 1";
$rsUltimoPago = phpmkr_query($sqlUltimoPago, $conn) or die("Error al selecconar e ulmo vencimiento". phpmkr_error()."sql:". $sqlUltimoPago) ;
$rowUltimoPago = phpmkr_fetch_array($rsUltimoPago);
$x_ultimo_pago = $rowUltimoPago["fecha_vencimiento"];



if($x_credito_id > 0) {
	
		$sql  = "UPDATE `credito` SET convenio = 1, fecha_convenio = \"$x_hoy\", fecha_ultimo_pago = \"$x_ultimo_pago\" WHERE credito_id= $x_credito_id";
		$rsv = phpmkr_query($sql, $conn) or die("Error al seleccin".phpmkr_error()."sql :".$sql);
		
			$x_result = '<input type="button" name="Action" value="MODIFICAR" onclick="modifica(1)"  disabled="disabled"/>';
			$x_result .= "-";
			$x_result .= '<label><input type="button" name="btnrecalcular" id="btnrecalcular" value="Recalcular Cr&eacute;dito" onclick="modifica(2)" disabled="disabled" /></label>';
			$x_result .= "-";
			$x_result .= '<label><input type="button" name="btn_rest" id="btn_rest" value="Restructurar cartera Vencida" onclick="javascript: document.getElementById(\'vencdesk\').src = \'php_restructura.php?credito_id=<?php echo $x_credito_id; ?>\';"  disabled="disabled" /></label>';
			$x_result .= "-";
			
			$x_result .= "CONVENIO ACEPTADO, USTED NO PODRA VOLVER A REESTRUCTURAR EL CR&Eacute;DITO";
		
		echo $x_result;

}else{
 echo "No localizado";
}
?>