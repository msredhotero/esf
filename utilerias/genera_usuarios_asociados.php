<?php include ("../db.php") ?>
<?php include ("../phpmkrfn.php") ?>

<?php 
include("gen_pass_ec.php");
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

$sSql = "SELECT cliente.*, credito.fecha_otrogamiento FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join solicitud_inciso on solicitud_inciso.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_inciso.cliente_id where cliente.usuario_id = 0";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$x_usu_count = 0;
while($row = phpmkr_fetch_array($rs)){
	$x_cliente_id = $row["cliente_id"];
	$x_nombre = $row["nombre_completo"]." ". $row["apellido_paterno"]." ".$row["apellido_materno"];
	$x_email = $row["email"];	

//USUARIO Y PASSWORD
	$x_asignada = 0;
	while($x_asignada == 0){
		$clave = generate(8, "No", "Yes", "Yes");
		$sSql = "Select * from usuario where usuario = '$clave'";
		$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		if (phpmkr_num_rows($rs2) == 0) {	
			$x_usuario = $clave;
			$x_asignada = 10;
		}
		phpmkr_free_result($rs2);
	}

	$x_asignada = 0;
	while($x_asignada == 0){
		$clave = generate(8, "No", "Yes", "Yes");
		$sSql = "Select * from usuario where clave = '$clave'";
		$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		if (phpmkr_num_rows($rs2) == 0) {	
			/*		
			$sSql = "update socios set clave = '$clave' where socio_id = $x_socio_id";
			phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			*/
			$x_clave = $clave;
			$x_asignada = 10;
		}
		phpmkr_free_result($rs2);
	}


	$fieldList = NULL;
	// Field usuario_rol_id
	$fieldList["`usuario_rol_id`"] = 8;

	// Field usuario_status_id
	$fieldList["`usuario_status_id`"] = 1;

	// Field usuario
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_usuario) : $x_usuario; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`usuario`"] = $theValue;

	// Field clave
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_clave) : $x_clave; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`clave`"] = $theValue;

	// Field nombre
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_nombre) : $x_nombre; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre`"] = $theValue;

	// Field fecha_registro
	$theValue = ($x_fecha_otrogamiento != "") ? " '" . ConvertDateToMysqlFormat($x_fecha_otrogamiento) . "'" : "Null";
	$fieldList["`fecha_registro`"] = $theValue;

	// Field fecha_caduca
	$theValue = ($GLOBALS["x_fecha_caduca"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_caduca"]) . "'" : "Null";
	$fieldList["`fecha_caduca`"] = $theValue;

	// Field fecha_visita
	$theValue = ($GLOBALS["x_fecha_visita"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_visita"]) . "'" : "Null";
	$fieldList["`fecha_visita`"] = $theValue;

	// Field visitas
	$theValue = ($GLOBALS["x_visitas"] != "") ? intval($GLOBALS["x_visitas"]) : "0";
	$fieldList["`visitas`"] = $theValue;

	// Field email
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_email) : $x_email; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`email`"] = $theValue;

	// insert into database
	$sSql = "INSERT INTO `usuario` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	$x_result = phpmkr_query($sSql, $conn);
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
	 	exit();
	}
	
	$x_usuario_id = mysql_insert_id();

	$sSql = "update cliente set usuario_id = $x_usuario_id where cliente_id = $x_cliente_id";
	$x_result = phpmkr_query($sSql, $conn);
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
	 	exit();
	}
	$x_usu_count++;

}
phpmkr_free_result($rs);		
phpmkr_db_close($conn);
echo "fin de proceso, usuarios: ".$x_usu_count;
?>