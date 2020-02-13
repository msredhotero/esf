<?php

/**
 * @Usuarios clase en donde se accede a la base de datos
 * @ABM consultas sobre las tablas de usuarios y usarios-clientes
 */

date_default_timezone_set('America/Mexico_City');

class ServiciosReferencias {

	function GUID()
	{
		if (function_exists('com_create_guid') === true)
		{
			return trim(com_create_guid(), '{}');
		}

		return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
	}

	function verificarListaNegraOfacsimple($nombrecompleto) {
		$valida = 1;

		$nombrecompleto 		= strtolower(str_replace(';', '',str_replace('"', '', str_replace('/', '', str_replace('.', '', str_replace(')', '', str_replace('(', '', str_replace('_', '', str_replace('-', '', str_replace(',', '', str_replace('.', '', trim(str_replace(' ', '', trim($nombrecompleto))))))))))))));

		$sqlOfac = sprintf("SELECT * FROM csv_sdn WHERE lower( REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(sdn_name, '".'"'."', ''), '/', ''), '.', ''), ')', ''), '(', ''), '_', ''), '-', ''), ',', ''), ' ', ''), ';', '')  ) ='%s'",
            ($nombrecompleto));

		//die(var_dump($sqlClienteOfac));

		$resOfac = $this->query($sqlOfac,0);
		if (mysql_num_rows($resOfac) > 0) {
			$valida = 0;
		}


		$sqlLN = sprintf("SELECT * FROM csv_lista_lpb WHERE lower(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(nombre_completo, '".'"'."', ''), '/', ''), '.', ''), ')', ''), '(', ''), '_', ''), '-', ''), ',', ''), ' ', ''), ';', '') ) ='%s'",
            ($nombrecompleto));

		//die(var_dump($sqlClienteOfac));

		$resLN = $this->query($sqlLN,0);
		if (mysql_num_rows($resLN) > 0) {
			$valida = 0;
		}

		$sqlLN2 = sprintf("SELECT * FROM csv_lista_negra_cnbv WHERE lower(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(nombre_completo, '".'"'."', ''), '/', ''), '.', ''), ')', ''), '(', ''), '_', ''), '-', ''), ',', ''), ' ', '')) ='%s'",
            ($nombrecompleto));

		//die(var_dump($sqlClienteOfac));

		$resLN2 = $this->query($sqlLN2,0);
		if (mysql_num_rows($resLN2) > 0) {
			$valida = 0;
		}

		return $valida;
	}

	function verificarListaNegraOfac($cliente, $proveedor, $beneficiario,$clientereverso) {
		$validaC = 1;
		$validaP = 1;
		$validaB = 1;

		$cliente 		= strtolower(str_replace(';', '',str_replace('"', '', str_replace('/', '', str_replace('.', '', str_replace(')', '', str_replace('(', '', str_replace('_', '', str_replace('-', '', str_replace(',', '', str_replace('.', '', trim(str_replace(' ', '', trim($cliente))))))))))))));
		$proveedor 		= strtolower(str_replace(';', '',str_replace('"', '', str_replace('/', '', str_replace('.', '', str_replace(')', '', str_replace('(', '', str_replace('_', '', str_replace('-', '', str_replace(',', '', str_replace('.', '', trim(str_replace(' ', '', trim($proveedor))))))))))))));
		$beneficiario 	= strtolower(str_replace(';', '',str_replace('"', '', str_replace('/', '', str_replace('.', '', str_replace(')', '', str_replace('(', '', str_replace('_', '', str_replace('-', '', str_replace(',', '', str_replace('.', '', trim(str_replace(' ', '', trim($beneficiario))))))))))))));
		$clientereverso 		= strtolower(str_replace(';', '',str_replace('"', '', str_replace('/', '', str_replace('.', '', str_replace(')', '', str_replace('(', '', str_replace('_', '', str_replace('-', '', str_replace(',', '', str_replace('.', '', trim(str_replace(' ', '', trim($clientereverso))))))))))))));

		$sqlClienteOfac = sprintf("SELECT * FROM csv_sdn WHERE lower(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(sdn_name, '".'"'."', ''), '/', ''), '.', ''), ')', ''), '(', ''), '_', ''), '-', ''), ',', ''), ' ', ''), ';', '') ) ='%s'",
            ($cliente));

		//die(var_dump($sqlClienteOfac));

		$resClienteOfac = $this->query($sqlClienteOfac,0);
		if (mysql_num_rows($resClienteOfac) > 0) {
			$validaC = 0;
		}

		$sqlClienteReversoOfac = sprintf("SELECT * FROM csv_sdn WHERE lower(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(sdn_name, '".'"'."', ''), '/', ''), '.', ''), ')', ''), '(', ''), '_', ''), '-', ''), ',', ''), ' ', ''), ';', '') ) ='%s'",
            ($clientereverso));

		$resClienteReversoOfac = $this->query($sqlClienteReversoOfac,0);
		if (mysql_num_rows($resClienteReversoOfac) > 0) {
			$validaC = 0;
		}

		$sqlProveedorOfac = sprintf("SELECT * FROM csv_sdn WHERE lower(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(sdn_name, '".'"'."', ''), '/', ''), '.', ''), ')', ''), '(', ''), '_', ''), '-', ''), ',', ''), ' ', ''), ';', '') ) ='%s'",
            ($proveedor));

		//die(var_dump($sqlProveedorOfac));

		$resProveedorOfac = $this->query($sqlProveedorOfac,0);
		if (mysql_num_rows($resProveedorOfac) > 0) {
			$validaP = 0;
		}

		$sqlBeneficiarioOfac = sprintf("SELECT * FROM csv_sdn WHERE lower(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(sdn_name, '".'"'."', ''), '/', ''), '.', ''), ')', ''), '(', ''), '_', ''), '-', ''), ',', ''), ' ', ''), ';', '') ) ='%s'",
            ($beneficiario));

		//die(var_dump($sqlProveedorOfac));

		$resBeneficiarioOfac = $this->query($sqlBeneficiarioOfac,0);
		if (mysql_num_rows($resBeneficiarioOfac) > 0) {
			$validaB = 0;
		}

		/**********************  fin ofac ***************************************/


		$sqlClienteLN = sprintf("SELECT * FROM csv_lista_lpb WHERE lower(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(nombre_completo, '".'"'."', ''), '/', ''), '.', ''), ')', ''), '(', ''), '_', ''), '-', ''), ',', ''), ' ', ''), ';', '') ) ='%s'",
            ($cliente));

		//die(var_dump($sqlClienteOfac));

		$resClienteLN = $this->query($sqlClienteLN,0);
		if (mysql_num_rows($resClienteLN) > 0) {
			$validaC = 0;
		}

		$sqlClienteReversoLN = sprintf("SELECT * FROM csv_lista_lpb WHERE lower(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(nombre_completo, '".'"'."', ''), '/', ''), '.', ''), ')', ''), '(', ''), '_', ''), '-', ''), ',', ''), ' ', ''), ';', '') ) ='%s'",
            ($clientereverso));

		//die(var_dump($sqlClienteOfac));

		$resClienteReversoLN = $this->query($sqlClienteReversoLN,0);
		if (mysql_num_rows($resClienteReversoLN) > 0) {
			$validaC = 0;
		}

		$sqlProveedorLN = sprintf("SELECT * FROM csv_lista_lpb WHERE lower(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(nombre_completo, '".'"'."', ''), '/', ''), '.', ''), ')', ''), '(', ''), '_', ''), '-', ''), ',', ''), ' ', ''), ';', '') ) ='%s'",
            ($proveedor));

		//die(var_dump($sqlProveedorOfac));

		$resProveedorLN = $this->query($sqlProveedorLN,0);
		if (mysql_num_rows($resProveedorLN) > 0) {
			$validaP = 0;
		}

		$sqlBeneficiarioLN = sprintf("SELECT * FROM csv_lista_lpb WHERE lower(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(nombre_completo, '".'"'."', ''), '/', ''), '.', ''), ')', ''), '(', ''), '_', ''), '-', ''), ',', ''), ' ', ''), ';', '') ) ='%s'",
            ($beneficiario));

		//die(var_dump($sqlProveedorOfac));

		$resBeneficiarioLN = $this->query($sqlBeneficiarioLN,0);
		if (mysql_num_rows($resBeneficiarioLN) > 0) {
			$validaB = 0;
		}

		/**********************  fin lista negra lpb ***************************************/


		$sqlClienteLN2 = sprintf("SELECT * FROM csv_lista_negra_cnbv WHERE lower(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(nombre_completo, '".'"'."', ''), '/', ''), '.', ''), ')', ''), '(', ''), '_', ''), '-', ''), ',', ''), ' ', ''), ';', '') ) ='%s'",
            ($cliente));

		//die(var_dump($sqlClienteOfac));

		$resClienteLN2 = $this->query($sqlClienteLN2,0);
		if (mysql_num_rows($resClienteLN2) > 0) {
			$validaC = 0;
		}

		$sqlClienteReversoLN2 = sprintf("SELECT * FROM csv_lista_negra_cnbv WHERE lower(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(nombre_completo, '".'"'."', ''), '/', ''), '.', ''), ')', ''), '(', ''), '_', ''), '-', ''), ',', ''), ' ', ''), ';', '') ) ='%s'",
            ($clientereverso));

		//die(var_dump($sqlClienteOfac));

		$resClienteReversoLN2 = $this->query($sqlClienteReversoLN2,0);
		if (mysql_num_rows($resClienteReversoLN2) > 0) {
			$validaC = 0;
		}

		$sqlProveedorLN2 = sprintf("SELECT * FROM csv_lista_negra_cnbv WHERE lower(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(nombre_completo, '".'"'."', ''), '/', ''), '.', ''), ')', ''), '(', ''), '_', ''), '-', ''), ',', ''), ' ', ''), ';', '') ) ='%s'",
            ($proveedor));

		//die(var_dump($sqlProveedorOfac));

		$resProveedorLN2 = $this->query($sqlProveedorLN2,0);
		if (mysql_num_rows($resProveedorLN2) > 0) {
			$validaP = 0;
		}

		$sqlBeneficiarioLN2 = sprintf("SELECT * FROM csv_lista_negra_cnbv WHERE lower(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(nombre_completo, '".'"'."', ''), '/', ''), '.', ''), ')', ''), '(', ''), '_', ''), '-', ''), ',', ''), ' ', ''), ';', '') ) ='%s'",
            ($beneficiario));

		//die(var_dump($sqlProveedorOfac));

		$resBeneficiarioLN2 = $this->query($sqlBeneficiarioLN2,0);
		if (mysql_num_rows($resBeneficiarioLN2) > 0) {
			$validaB = 0;
		}


		return array('cliente'=>$validaC,'proveedor'=>$validaP,'beneficiario'=>$validaB);
	}

	function devolverEvaluacion($idsolicitud) {
		$sql = "SELECT
				    r.ppe,
				    r.entidad_nacimiento_id,
				    r.credito_tipo_id,
				    r.doctos_completos_id,
				    r.lugar_otorgamiento,
				    r.residencia,
				    r.destino_credito_id,
				    r.ppe + r.entidad_nacimiento_id + r.credito_tipo_id + r.doctos_completos_id + r.lugar_otorgamiento + r.residencia + r.destino_credito_id AS total
				FROM
				    (SELECT
				        (CASE
				                WHEN c.ppe = 1 THEN 5
				                ELSE 0
				            END) AS ppe,
				            (CASE
				                WHEN c.entidad_nacimiento_id IN (2 , 3, 6, 8, 12, 16, 25, 26) THEN 4
				                WHEN c.entidad_nacimiento_id IN (5 , 9, 15, 22, 23, 31) THEN 2
				                ELSE 0
				            END) AS entidad_nacimiento_id,
				            (CASE
				                WHEN s.credito_tipo_id IN (1 , 4) THEN 5
				                ELSE 0
				            END) AS credito_tipo_id,
				            (CASE
				                WHEN s.doctos_completos_id = 1 THEN 0
				                ELSE 5
				            END) AS doctos_completos_id,
				            (CASE
				                WHEN s.lugar_otorgamiento IN (2 , 3, 6, 8, 12, 16, 25, 26) THEN 4
				                WHEN s.lugar_otorgamiento IN (5 , 9, 15, 22, 23, 31) THEN 2
				                ELSE 0
				            END) AS lugar_otorgamiento,
				            (CASE
				                WHEN de.entidad_id IN (2 , 3, 6, 8, 12, 16, 25, 26) THEN 4
				                WHEN de.entidad_id IN (5 , 9, 15, 22, 23, 31) THEN 2
				                ELSE 0
				            END) AS residencia,
				            (CASE
				                WHEN n.destino_credito_id IN (8) THEN 1
				                WHEN n.destino_credito_id IN (7 , 9) THEN 3
				                ELSE 0
				            END) AS destino_credito_id
				    FROM
				        solicitud s
				    INNER JOIN solicitud_cliente sc ON s.solicitud_id = sc.solicitud_id
				    INNER JOIN cliente c ON c.cliente_id = sc.cliente_id
				    LEFT JOIN direccion d ON c.cliente_id = d.cliente_id
				    LEFT JOIN delegacion de ON d.delegacion_id = de.delegacion_id
				    LEFT JOIN negocio n ON n.cliente_id = c.cliente_id
				    WHERE
				        s.solicitud_id = ".$idsolicitud."
				            AND d.direccion_tipo_id = 1) r";

		$res = $this->query($sql,0);

		if (mysql_num_rows($res) > 0) {
			if ((mysql_result($res,0,'total') >= 27) && (mysql_result($res,0,'total') <= 31)) {
				return array('valor'=>mysql_result($res,0,'total'), 'puntaje' => 'ALTO');
			}
			if ((mysql_result($res,0,'total') >= 17) && (mysql_result($res,0,'total') <= 26)) {
				return array('valor'=>mysql_result($res,0,'total'), 'puntaje' => 'MEDIO');
			}
			return array('valor'=>mysql_result($res,0,'total'), 'puntaje' => 'BAJO');
		} else {
			return array('valor'=>0, 'puntaje' => 'BAJO');
		}

	}



	function query($sql,$accion) {



		require_once 'appconfig.php';

		$appconfig	= new appconfig();
		$datos		= $appconfig->conexion();
		$hostname	= $datos['hostname'];
		$database	= $datos['database'];
		$username	= $datos['username'];
		$password	= $datos['password'];

		$conex = mysql_connect($hostname,$username,$password) or die ("no se puede conectar".mysql_error());

		mysql_select_db($database);

		        $error = 0;
		mysql_query("BEGIN");
		$result=mysql_query($sql,$conex);
		if ($accion && $result) {
			$result = mysql_insert_id();
		}
		if(!$result){
			$error=1;
		}
		if($error==1){
			mysql_query("ROLLBACK");
			return false;
		}
		 else{
			mysql_query("COMMIT");
			return $result;
		}

	}

}

?>
