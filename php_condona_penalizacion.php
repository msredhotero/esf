<?php session_start(); ?>
<?php ob_start(); ?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$x_credito_id = $_GET["x_credito_id"];
$x_ref_id = $_GET["x_refloc"];
$x_expl_vencimiento_id = explode("/",$x_ref_id);
$x_vencimiento_id =  $x_expl_vencimiento_id[0] ;
$x_fecha_hoy = date("Y-m-d");  

$x_usuario_id = $_SESSION["php_project_esf_status_UserID"];
// echo $x_vencimiento_id; 
 // entra a al condonacion de la penalizacion
# 1.- se cambia el status de la penalizacion
# 2.- se guarda en la tabla de
# 3.- verificar la fecha del vencimiento Si el vencimiento es con fecha menor al 01/enero/2014 no se registra como nota de credito
if(!empty($x_vencimiento_id)){
	// seleccionamos los datos del vecimiento
	$sqLvencimiento = "SELECT * FROM  vencimiento WHERE vencimiento_id = $x_vencimiento_id ";
	$rsvencimeinto = phpmkr_query($sqLvencimiento,$conn) or die("Error al seleccionar la fecha del vencimiento".phpmkr_error()."sql:".$sqLvencimiento);
	$rowvencimeinto = phpmkr_fetch_array($rsvencimeinto); 
	$x_fecha_vencimiento = $rowvencimeinto["fecha_vencimiento"];
	$x_credito_id =  $rowvencimeinto["credito_id"];
	if($x_fecha_vencimiento >= "2014-01-01"){
		// se cambia el status y se gusrada el registro
		$sqlUpdateReg = "UPDATE vencimiento SET vencimiento_status_id  = 8 WHERE vencimiento_id = $x_vencimiento_id ";
		$rsupdate = phpmkr_query($sqlUpdateReg,$conn) or die("error al actualizar".phpmkr_error()."sql :".$sqlUpdateReg);		
		$sqlInsert = "INSERT INTO `condonacion` (`condonacion_id`, `fecha_registro`, `credito_id`, `vencimiento_id`, `status_id` ,`usuario_id`)";
		$sqlInsert .= " VALUES (NULL, \"$x_fecha_hoy\", $x_credito_id, $x_vencimiento_id, '1',$x_usuario_id )";
		$rsQuery = phpmkr_query($sqlInsert,$conn) or die("Error al insertar ".phpmkr_error()."sql:".$sqlInsert);	
		//echo $sqlInsert ."<br>".$sqlUpdateReg."";
		}else{			
		// se cambia se garda el registro pero no se guarda como nota de credito	
			$sqlUpdateReg = "UPDATE vencimiento SET vencimiento_status_id  = 8 WHERE vencimiento_id = $x_vencimiento_id ";
			$rsupdate = phpmkr_query($sqlUpdateReg,$conn) or die("error al actualizar".phpmkr_error()."sql :".$sqlUpdateReg);		
			$sqlInsert = "INSERT INTO `condonacion` (`condonacion_id`, `fecha_registro`, `credito_id`, `vencimiento_id`, `status_id`,`usuario_id`)";
			$sqlInsert .= " VALUES (NULL, \"$x_fecha_hoy\", $x_credito_id, $x_vencimiento_id, '2',$x_usuario_id)";
			$rsQuery = phpmkr_query($sqlInsert,$conn) or die("Error al insertar ".phpmkr_error()."sql:".$sqlInsert);
			//echo $sqlInsert ."<br>".$sqlUpdateReg."";
			}
}
header("Location:  php_vencimientolist.php?credito_id=$x_credito_id");
?>