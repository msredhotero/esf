<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$sqlBuscaClientes = "SELECT cliente.usuario_id, cliente.cliente_id,credito.credito_id  FROM  cliente, solicitud_cliente, credito, usuario ";
$sqlBuscaClientes .= " WHERE credito.credito_tipo_id = 1 and cliente.cliente_id = solicitud_cliente.cliente_id and solicitud_cliente.solicitud_id = credito.solicitud_id  and cliente.usuario_id = usuario.usuario_id group by cliente.cliente_id order by credito.credito_id DESC ";
$rsBuscaCliente = phpmkr_query($sqlBuscaClientes,$conn) or die("Error 1".phpmkr_error().$sqlBuscaClientes);
while($rowClis = phpmkr_fetch_array($rsBuscaCliente)){
	$x_cliente_id = $rowClis["cliente_id"];
	$x_usuario_id = $rowClis["usuario_id"];
	$x_credito_id = $rowClis["credito_id"];
	
	$sqlCredito = "SELECT solicitud.solicitud_id FROM  solicitud_cliente JOIN solicitud ON solicitud.solicitud_id = solicitud_cliente.solicitud_id join credito on credito.solicitud_id = solicitud.solicitud_id where cliente_id  = $x_cliente_id  and solicitud.solicitud_status_id != 5 order by  solicitud.solicitud_id DESC";
	$rsCredito = phpmkr_query($sqlCredito,$conn) or die ("Error al seleccionar el credito".phpmkr_error()."sql:".$sqlCredito);
	$rowCredito = phpmkr_fetch_array($rsCredito);
	echo $sqlCredito."<br>";
	$x_solicitud_id = $rowCredito["solicitud_id"];
	if(!empty($x_solicitud_id)){
	$sqlCredito = "SELECT credito_id FROM  credito where solicitud_id  = $x_solicitud_id ";
	$rsCredito = phpmkr_query($sqlCredito,$conn) or die ("Error al seleccionar el credito".phpmkr_error()."sql:".$sqlCredito);
	$rowCredito = phpmkr_fetch_array($rsCredito);
	$x_credito_id  = $rowCredito["credito_id"];
	echo "<br>sol_id ".$x_solicitud_id. "credito _id ".$x_credito_id;
	
	echo "usuario ".$x_usuario_id. " credito_id ".$x_credito_id."<br>";	
	$sqlUsuario = "SELECT nombre, usuario_id FROM usuario WHERE usuario_id = $x_usuario_id ";
	$rsUser = phpmkr_query($sqlUsuario,$conn)or die("Error2");
	while($rowUSer = phpmkr_fetch_array($rsUser)){
		$x_user_id = $rowUSer["usuario_id"];
		$x_nombre_usuario = $rowUSer["nombre"];	
			
		if($x_user_id > 0){
		// seleccionamos todos los usuarios que sean clientes y tengan el ismo nombre
		$sqlUsuaroRepetido = "SELECT *
								FROM `usuario`
								WHERE `nombre` LIKE \"%$x_nombre_usuario%\"";
		$rsUsuarioRepetido = phpmkr_query($sqlUsuaroRepetido,$conn)or die("erro 3");		
		// seleccionamos el ultimo credito
		//select
		$sqlCOUNT = "SELECT COUNT(*) as insertados FROM  usuario_cliente_credito where cliente_id = $x_cliente_id ";
		$rsCUNT = phpmkr_query($sqlCOUNT,$conn) or die("Error al seleccionar los dats".phpmkr_error());
		$rowCOUNT = phpmkr_fetch_array($rsCUNT);
		$x_total = $rowCOUNT["insertados"];
		//$x_total = 0;
		if($x_total < 1){
			echo "insertados ".$x_total."------";
			
		while($rowUsuarioRepetido = phpmkr_fetch_array($rsUsuarioRepetido)) {
			$x_user = $rowUsuarioRepetido["usuario_id"];
			echo $x_nombre_usuario."<br> ";			
			// vericamos que ya
			// aqui se inserta por cda registro encontrado en la base de datos
			$sqlInsert = "INSERT INTO `usuario_cliente_credito` ";
			$sqlInsert .= " (`usuario_cliente_credito_id`, `cliente_id`, `credito_id`, `usuario_id`) ";
			$sqlInsert .= " VALUES (NULL, $x_cliente_id, $x_credito_id, $x_user)";
			$rsInsert = phpmkr_query($sqlInsert,$conn)or die("Error l selecionar ".phpmkr_error().$sqlInsert);
			echo $sqlInsert."<br>";
			
			}		
		}
		}
		
		
		}
	
	
	}// si sol_id esta llena
	} 