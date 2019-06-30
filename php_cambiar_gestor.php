<?php 
die();
include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>

<?php
	
	$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);	
	//Buscamos los creditos que no estan activos y que tengan Folios Asignados
	$sSqlSolicitud = " SELECT * FROM `gestor_credito` WHERE `usuario_id` = 7202  and gestor_credito_id not in (1227, 1229, 1230,1232,1233, 1234,1237, 1337, 1338,1376 )";
	
	//$sSqlSolicitud = " SELECT * FROM `gestor_credito` WHERE `gestor_credito_id` in (1227, 1229, 1230,1232,1233, 1234,1237, 1337, 1338 )";
	$responseSolicitud = phpmkr_query($sSqlSolicitud,$conn) or die("error en select Solicitud".phpmkr_error()."sql;".$sSqlSolicitud);					
				while($rowSolicitud = phpmkr_fetch_array($responseSolicitud)){					
					// por cada registro que encuentre debo seleccionar su sucursal y buscar el usuario del responsable de la sucursal y asignarlo como usuario a este credito en el campo de gestor_id
				$x_credito_id = $rowSolicitud["credito_id"];
				$x_gestor_credito_id = $rowSolicitud["gestor_credito_id"];				
				// alcredito le buscamos la sucursal y sacamor el responsable de la sucrsal......				
					
				$sqlCredito = "SELECT credito.solicitud_id, responsable_sucursal.usuario_id
								FROM credito
								JOIN solicitud ON solicitud.solicitud_id = credito.solicitud_id
								JOIN promotor ON promotor.promotor_id = solicitud.promotor_id
								JOIN sucursal ON sucursal.sucursal_id = promotor.sucursal_id
								JOIN responsable_sucursal ON responsable_sucursal.sucursal_id = sucursal.sucursal_id
								WHERE credito_id = $x_credito_id ";
								//echo "sql : ".$sqlCredito."<br>";
				$rsCredito = phpmkr_query($sqlCredito, $conn) or die ("Error al seleccionar los datos del credito".phpmkr_error()."sql :".$sqlCredito);
				$rowCredito = phpmkr_fetch_array($rsCredito);				
				$x_usuario_id = $rowCredito["usuario_id"];
				
				
				
				//actualizamos los datos del gestor de credito				
				$sqlUpdate = "UPDATE gestor_credito SET usuario_id = $x_usuario_id WHERE gestor_credito_id = $x_gestor_credito_id ";
				$rsUpdate = phpmkr_query($sqlUpdate,$conn) or die ("Error al actualiza el usuario del credito_gestor".phpmkr_error().$sqlUpdate);
					
					echo $sqlUpdate."<br>";
				
				
				
				}
				
				
				
				
?>