<?php session_start(); ?>
<?php ob_start(); ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Cache-Control: private");
header("Pragma: no-cache"); // HTTP/1.0 
?>
<?php
$ewCurSec = 0; // Initialise

// User levels
define("ewAllowadd", 1, true);
define("ewAllowdelete", 2, true);
define("ewAllowedit", 4, true);
define("ewAllowview", 8, true);
define("ewAllowlist", 8, true);
define("ewAllowreport", 8, true);
define("ewAllowsearch", 8, true);																														
define("ewAllowadmin", 16, true);						
?>
<?php
if (@$_SESSION["php_project_esf_status"] <> "login") {
	header("Location:  login.php");
	exit();
}
?>

<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>

<?php
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

// Ana nov 2018
// desde el Credito id => 7096
	$sSql = "SELECT * FROM credito WHERE `credito_id` >= 7096 and `credito_id` < 7179   ";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	while($row = @phpmkr_fetch_array($rs)){
		$x_credito_id =  $row["credito_id"];
		$x_solicitud_id =  $row["solicitud_id"];
		
	 echo "credito_id =>".$x_credito_id."  <br>Sol_id".$x_solicitud_id."<br> ";
		
		// buscamo el id cliente
		
		$sqlCliente =  " SELECT cliente_id FROM solicitud_cliente WHERE solicitud_id = ".$x_solicitud_id;
		$rsCliente = phpmkr_query($sqlCliente,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sqlCliente);
		$rowCliente = @phpmkr_fetch_array($rsCliente);
		$x_cliente_id =  $rowCliente["cliente_id"];
		
		echo "cliente_id =>".$x_cliente_id."<br>";
		// actualizamos la nacionalidad del cliente
		
		
		$sqlClienteU = "SELECT ppe, entidad_nacimiento_id FROM cliente WHERE cliente_id = ".$x_cliente_id;
		$rsClienteU = phpmkr_query($sqlClienteU,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sqlClienteU);
		$rowClienteU = @phpmkr_fetch_array($rsClienteU);
		$x_ppe =  $rowClienteU["ppe"];
		$x_entidad_nacimiento =  $rowClienteU["entidad_nacimiento_id"];
		echo $sqlClienteU ."<br>";
		
		$SqlUpdateNacionalidad =  " UPDATE cliente SET nacionalidad_id = 58 WHERE cliente_id = ".$x_cliente_id;
		$rsNacionalidad = phpmkr_query($SqlUpdateNacionalidad,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $SqlUpdateNacionalidad);
		echo $SqlUpdateNacionalidad ."<br>";
		
		$sqlSolicitud =  "UPDATE solicitud SET lugar_otorgamiento = 9, doctos_completos_id = 1 WHERE solicitud_id = ".$x_solicitud_id;
		$rsSolAct = phpmkr_query($sqlSolicitud,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sqlSolicitud);
		echo $sqlSolicitud ."<br>";
		
		#seleccionamos la direccion del cliente
		
		$sqlDireccion  =  "SELECT  * FROM direccion WHERE cliente_id=".$x_cliente_id." AND  direccion_tipo_id = 1";
		$rsDireccion =  phpmkr_query($sqlDireccion,$conn) or die("Error:");
		$rowDirecciones = @phpmkr_fetch_array($rsDireccion);
		$x_entidad_id =  $rowDirecciones["entidad"];
		
		echo "direccion edo =>".$x_entidad_id;
		
		
		///////////////////////////////////////////////////////////////////////////////////
		// hacemos la suma de los puntajes de la solicitud
			$x_suma_puntajes =  0;
			$x_puntaje_id = 0;
			
			$x_persona_policamente_expuesta = 0;
			$x_cliente_de_nomina = 0;
			$x_documentacion_completa = 0;
			$x_estado_nacimiento = 0;
			$x_estado_residencia = 0;
			$x_estado_otorgamiento = 0;
			
			$x_array_edo_5 = array(2,3,6,8,12,16,25,26);
			$x_array_edo_2 = array(5,9,15,22,23,31);
			
			
			
			
			$x_persona_policamente_expuesta = ($x_ppe == 1)?5:0;
			$x_cliente_de_nomina = 0;
			$x_documentacion_completa = 0;
			if(in_array($x_entidad_nacimiento, $x_array_edo_5)){
				$x_estado_nacimiento = 4;
			}
			if(in_array($x_entidad_nacimiento,$x_array_edo_2)){
				$x_estado_nacimiento = 2;
			}
			
			if(in_array($x_entidad_id,$x_array_edo_5)){
				$x_estado_residencia = 4;
			}
			if(in_array($x_entidad_id,$x_array_edo_2)){
				$x_estado_residencia = 2;
			}
			
			
				$x_estado_otorgamiento = 2;
			
			echo "<br>residencia =>".$x_entidad_id."<br> nacimeinto ".$x_entidad_nacimiento."<br>otorgamiento => 9.<br>";
			echo "VALORES";
			
			echo "<br>ppe =>".$x_persona_policamente_expuesta ."<br>cliente nomina ==>" .$x_cliente_de_nomina ."<br>doctos==>".  $x_documentacion_completa ."<br> edo_nacimiento==>".  $x_estado_nacimiento ."<br>edo_residencia==>".  $x_estado_residencia ."<br> edo_otorgamiento==>".  $x_estado_otorgamiento;
			
			$x_suma_puntajes = $x_persona_policamente_expuesta + $x_cliente_de_nomina +  $x_documentacion_completa + $x_estado_nacimiento + $x_estado_residencia + $x_estado_otorgamiento;
			
			#$x_suma_puntajes = 26;
			$grado = ($x_suma_puntajes<22)?"BAJO":(($x_suma_puntajes>21 && $x_suma_puntajes<27)?"MEDIO":"ALTO");
			$sqlPuntaje =  "SELECT * FROM puntaje WHERE solicitud_id = ".$x_solicitud_id." ";
			$rspuntaje = phpmkr_query($sqlPuntaje,$conn) or die("Error al buscar puntaje".phpmkr_error()."sql:".$sqlPuntaje);
			while($rowPuntaje = phpmkr_fetch_array($rspuntaje)){
				$x_puntaje_id = $rowPuntaje["puntaje_id"];	 
				}//fin else  phpmkr_num_rows($rs) == 0
			
			if($x_puntaje_id>0){
				/*$fieldList = NULL;
				$theValue = ($x_suma_puntajes != "") ? intval($x_suma_puntajes) : "0";
				$fieldList["`valor`"] = $theValue;		
				$fieldList["`cliente_id`"] = $GLOBALS["x_cliente_id"];
				$fieldList["`solicitud_id`"] = $GLOBALS["x_solicitud_id"];
				
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($grado) : $grado; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`grado`"] = $theValue;	
				$sSql = "UPDATE `puntaje` SET ";
				foreach ($fieldList as $key=>$temp) {
					$sSql .= "$key = $temp, ";
				}
				if (substr($sSql, -2) == ", ") {
					$sSql = substr($sSql, 0, strlen($sSql)-2);
				}
				$sSql .= " WHERE solicitud_id = ".$GLOBALS["x_solicitud_id"]."";
				$x_result = phpmkr_query($sSql, $conn);
					if(!$x_result){
						echo phpmkr_error() . '<br>SQL:ERRORRRRRRRRRRRRR ' . $sSql;
						phpmkr_query('rollback;', $conn);	 
						exit();
					}else{
						echo "listooooooooooo=>";
						}
				echo  $sSql;*/
				// se actualiza el puntaje de la solicitud
				}else{
					// se inserta el puntaje de la solicitud			
					$fieldList = NULL;
					$theValue = ($x_suma_puntajes != "") ? intval($x_suma_puntajes) : "0";
					$fieldList["`valor`"] = $theValue;		
					$fieldList["`cliente_id`"] = $GLOBALS["x_cliente_id"];
					$fieldList["`solicitud_id`"] = $GLOBALS["x_solicitud_id"];
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($grado) : $grado; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`grado`"] = $theValue;	
					
					// insert into database
					$strsql = "INSERT INTO `puntaje` (";
					$strsql .= implode(",", array_keys($fieldList));
					$strsql .= ") VALUES (";
					$strsql .= implode(",", array_values($fieldList));
					$strsql .= ")";
					
					$x_result = phpmkr_query($strsql, $conn);
					if(!$x_result){
						echo phpmkr_error() . '<br>SQL: ' . $strsql;
						phpmkr_query('rollback;', $conn);	 
						exit();
					}
					
					echo $strsql;
				}
		
		
		
		
		
		
		}


		$bEditData = false; // Update Failed

	



?>