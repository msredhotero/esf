<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

$conn = phpmkr_db_connect(HOST, USER, PASS,DB, PORT);

$sqlNombre2 = "SELECT comite_credito_solicitud_id, nombre_cliente, no_cliente FROM comite_credito_solicitud ";
$rsNombres = phpmkr_query($sqlNombre2,$conn) or die("Erroro en nombre 1".phpmkr_error());
while($rosNombre = phpmkr_fetch_array($rsNombres)){
	
	$x_id = $rosNombre["comite_credito_solicitud_id"];
	$x_nombre = $rosNombre["nombre_cliente"];
	$x_numero_cliente = $rosNombre["no_cliente"];
	echo $x_numero_cliente."<br>";
	
	if(!empty($x_numero_cliente)){
	
	$sqlNombre = "SELECT cliente_id, nombre_completo, apellido_paterno, apellido_materno from cliente where cliente_num =  $x_numero_cliente";
	$rsNombre = phpmkr_query($sqlNombre,$conn) or die("Erroro en nombre2".phpmkr_error().$sqlNombre);
	$rowNombre = phpmkr_fetch_array($rsNombre);
	$x_cliente_id = $rowNombre["cliente_id"];
	$x_nom = $rowNombre["nombre_completo"];
	$x_a_patr = $rowNombre["apellido_paterno"];
	$x_a_mat = $rowNombre["apellido_materno"];
	$x_clie_t = $x_nom." ".$x_a_patr." ".$x_a_mat;
	if($x_cliente_id >0){
	$sqlNombre = "SELECT cliente_id, solicitud_id from solicitud_cliente where cliente_id =  $x_cliente_id  order by solicitud_id DESC";
	$rsNombre = phpmkr_query($sqlNombre,$conn) or die("Erroro en nombre3".phpmkr_error().$sqlNombre);
	$rowNombre = phpmkr_fetch_array($rsNombre);
	$x_solicitud_id = $rowNombre["solicitud_id"];
	
	$sqlUpdate = "UPDATE comite_credito_solicitud SET solicitud_id = $x_solicitud_id where comite_credito_solicitud_id = $x_id ";
	$rsUpdate = phpmkr_query($sqlUpdate,$conn) or die ("Error en updte".phpmkr_error());
	echo "<br>".$sqlUpdate;
	
	}
	echo "solictud_id".$x_solicitud_id."cliente_id ".$x_cliente_id." cliente ". $x_nombre."cliene ttt".$x_clie_t."<br>";
	
	
	}
	
	}


?>