<?php include ("../db.php") ?>
<?php include ("../phpmkrfn.php") ?>
<?php
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$x_cliente_id = $_GET["q1"]; #1
$x_forma_pago = $_GET["q2"];#2



if($x_cliente_id > 0) {
	
		
		// seleccionamos la ultima direccion registrada del cliente
		$sqlDireccion = "SELECT * FROM direccion where cliente_id = $x_cliente_id AND direccion_tipo_id = 1 order by direccion_id desc limit 1 ";
		$rsDireccion = phpmkr_query($sqlDireccion, $conn) or die("Error al seleccionar los datos de la direccion". phpmkr_error()."sql:".$sqlDireccion);
		$row3 = phpmkr_fetch_array($rsDireccion);
		#echo $sqlDireccion;
		
		$x_calle_domicilio = $row3["calle"];
		$x_colonia_domicilio = $row3["colonia"];
		$x_delegacion_id = $row3["delegacion_id"];
		$x_localidad_id = $row3["localidad_id"];
		$x_propietario = $row3["propietario"];
		$x_entidad_domicilio = $row3["entidad"];
		$x_codigo_postal_domicilio = $row3["codigo_postal"];
		$x_ubicacion_domicilio = $row3["ubicacion"];
		$x_antiguedad = $row3["antiguedad"];
		$x_tipo_vivienda = $row3["vivienda_tipo_id"];
		$x_otro_tipo_vivienda = $row3["otro_tipo_vivienda"];
		$x_telefono_domicilio = $row3["telefono"];		
		$x_celular = $row3["telefono_movil"];					
		$x_otro_tel_domicilio_1 = $row3["telefono_secundario"];
		$x_tel_arrendatario_domicilio = $row3["propietario"];		
		$x_numero_exterior = $row3["numero_exterior"];
		$x_compania_celular_id = $row3["compania_celular_id"];
		$x_otro_telefono_domicilio_2 = $row3["telefono_movil_2"];
		$x_compania_celular_id_2 = $row3["compania_celular_id_2"];
			
		// insertamos en la tabla de direccion_antigua la direccion seleccionada y guardamos la fecha en que se hizo el insert
		$x_today = date("Y-m-d");
		
		$sqlInsert = "INSERT INTO `direccion_antigua` (`direccion_antigua_id`, `cliente_id`, `aval_id`, `promotor_id`, `direccion_tipo_id`, `calle`,"; 		        $sqlInsert .= " `colonia`, `delegacion_id`, `propietario`, `entidad`, `codigo_postal`, `ubicacion`, `antiguedad`, `vivienda_tipo_id`,";
		$sqlInsert .= " `otro_tipo_vivienda`, `telefono`, `telefono_secundario`, `telefono_movil`, `compania_celular_id`, `numero_exterior`, ";
		$sqlInsert .= " `telefono_movil_2`, `compania_celular_id_2`, `localidad_id`, `ubicacion_id`, `fecha`)";
		$sqlInsert .= " VALUES (NULL, $x_cliente_id, '0', '0', '1', \"".$x_calle_domicilio."\", \"".$x_colonia_domicilio."\", $x_delegacion_id,";
		$sqlInsert .= " \"".$x_propietario."\", \"".$x_entidad_domicilio. "\", \"".$x_codigo_postal_domicilio."\",";
		$sqlInsert .= " \"".$x_ubicacion_domicilio."\", $x_antiguedad, $x_tipo_vivienda, \"". $x_otro_tipo_vivienda ."\", \"". $x_telefono_domicilio. "\" ";
		$sqlInsert .= " , \"". $x_otro_tel_domicilio_1 ."\" , \"". $x_celular. "\", \"".$x_compania_celular_id."\", $x_numero_exterior,";
		$sqlInsert .= " \"". $x_otro_telefono_domicilio_2."\" , \"".$x_compania_celular_id_2."\", ";
		$sqlInsert .= " $x_localidad_id, NULL, \"".$x_today."\");";
		$rsInsert = phpmkr_query($sqlInsert, $conn) or die ("Error al insertar en direccion antigua". phpmkr_error()."sql:". $sqlInsert);
		
		  
		if($sqlInsert){	
			$x_result .= "Direcci&oacute;n respaldada, ya puede modificar los datos.";
		}else{
			$x_result .= "Error la direccion antigua no se gaurdo, contacte con el administrardor";
			}
		echo $x_result;

}else{
 echo "No localizado";
}
?>