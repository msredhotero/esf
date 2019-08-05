<?php
include("gen_pass_ec.php");
include ("../db.php");
include ("../phpmkrfn.php");

$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$sSql = "Select * from socios ";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
while($row = phpmkr_fetch_array($rs)){

	$x_socio_id = $row["socio_id"];									
	
	$x_asignada = 0;
	while($x_asignada == 0){
		$clave = generate(8, "No", "Yes", "Yes");
		$sSql = "Select * from socios where clave = '$clave'";
		$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		if (phpmkr_num_rows($rs2) == 0) {	
			$sSql = "update socios set clave = '$clave' where socio_id = $x_socio_id";
			phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$x_asignada = 10;
		}
		phpmkr_free_result($rs2);
	}
	
	
}
echo "Fin";

?>