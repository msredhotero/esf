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

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

#echo $sSql; // Uncomment to show SQL for debugging
?>
<?php include ("header.php") ?>



<?php 


#buscamos las tareas de PP que se generaron el dia  martes y miercoles.....
$x_tarea_duplicada = 0;
 $sqlPP = "SELECT * 
FROM  `crm_tarea` 
WHERE  `asunto` LIKE  'Carta 1' ";
 $rsPP = phpmkr_query($sqlPP,$conn)or die("Error al seleccionar las C1".phpmkr_error()."sql:".$sqlPP);
 echo "GRAL SQL :".$sqlPP."<BR>";
 while($rowPP = phpmkr_fetch_array($rsPP)){
	 echo "<br><br>";
	 $x_lista_tareas_ok = "";
	 $x_crm_caso_id = $rowPP["crm_caso_id"];
	 echo "x_crm_caso_id ".$x_crm_caso_id."<br>";
	 // seleccionmos todas las Promesas de pago 
	 $sqlCount = "SELECT COUNT(*) as no_promesas_pago_c1 FROM crm_tarea WHERE `asunto` LIKE  'PP Carta 1'  AND crm_caso_id = $x_crm_caso_id ";
	 $rsCount = phpmkr_query($sqlCount,$conn)or die("Error al selecionar las pp c1".phpmkr_error()."sql:".$sqlCount);
	 $rowCount = phpmkr_fetch_array($rsCount);
	 $x_promesas_pago_c1 = $rowCount["no_promesas_pago_c1"];
	 
	 if($x_promesas_pago_c1 > 1){
		 //buscamos la tareas que tengan la fecha de la promesa de pago que no esten completas
		 
		 $sqlCount = "SELECT * FROM crm_tarea WHERE `asunto` LIKE  'PP Carta 1'  AND crm_caso_id = $x_crm_caso_id ORDER BY crm_tarea_status_id";
		 $rsCount = phpmkr_query($sqlCount,$conn)or die("Error al selecionar las pp c1".phpmkr_error()."sql:".$sqlCount);
		 while($rowCount = phpmkr_fetch_array($rsCount)){
		 $x_crm_tarea_id = $rowCount["crm_tarea_id"]; 
		 $x_crm_tarea_status_id = $rowCount["crm_tarea_status_id"];
		 $x_fecha_registro = $rowCount["fecha_registro"];
		 
			 // bucamos las que tengan PP
			$sqlBuscaPP = "SELECT * FROM crm_tarea_cv WHERE crm_tarea_id = $x_crm_tarea_id ";
			$rsBPP = phpmkr_query($sqlBuscaPP,$conn) or die("Eror al buscar la promesa de pago para la tarea".phpmkr_error()."sql :".$sqlBuscaPP);
			$rowBuscaPP = phpmkr_fetch_array($rsBPP);
			$x_fecha_promesa = $rowBuscaPP["promesa_pago"];
			$x_crm_atrea_vc = $rowBuscaPP["crm_tarea_cv_id"];
			if( strlen($x_fecha_promesa) >3 ){
				echo "tiene promesa de pp <br>";
				$x_lista_tareas_ok = $x_lista_tareas_ok.$x_crm_tarea_id.", ";
				}
		
		 
		 }
		 
		 	echo "lista con pp de pago ".$x_lista_tareas_ok."<br>";	
				
			// si aun no hay nada en la lista buscamos la primer vencida
			if(strlen($x_lista_tareas_ok < 2)){
				//Buscamos la primer de las vencidas.
				
		 $sqlCount = "SELECT * FROM crm_tarea WHERE `asunto` LIKE  'PP Carta 1'  AND crm_caso_id = $x_crm_caso_id  and crm_tarea_status_id = 2 ORDER BY crm_tarea_id LIMIT 0,1 ";
		 $rsCount = phpmkr_query($sqlCount,$conn)or die("Error al selecionar las pp c1".phpmkr_error()."sql:".$sqlCount);
		 while($rowCount = phpmkr_fetch_array($rsCount)){
		 $x_crm_tarea_id = $rowCount["crm_tarea_id"]; 
		 $x_crm_tarea_status_id = $rowCount["crm_tarea_status_id"];
		 $x_fecha_registro = $rowCount["fecha_registro"];
				$x_lista_tareas_ok = $x_lista_tareas_ok.$x_crm_tarea_id.", ";
				
				}	 
		 
		 }
		 
		 echo "LISTA CON VENCIDAS ".$x_lista_tareas_ok."<BR>";
		 // si aun no hay nada en la lista buscamos la primer vencida
			if(strlen($x_lista_tareas_ok < 2)){
			//agararamos la primer pendiente
			$sqlCount = "SELECT * FROM crm_tarea WHERE `asunto` LIKE  'PP Carta 1'  AND crm_caso_id = $x_crm_caso_id  and crm_tarea_status_id = 1 ORDER BY crm_tarea_id LIMIT 0,1 ";
		 $rsCount = phpmkr_query($sqlCount,$conn)or die("Error al selecionar las pp c1".phpmkr_error()."sql:".$sqlCount);
		 while($rowCount = phpmkr_fetch_array($rsCount)){
		 $x_crm_tarea_id = $rowCount["crm_tarea_id"]; 
		 $x_crm_tarea_status_id = $rowCount["crm_tarea_status_id"];
		 $x_fecha_registro = $rowCount["fecha_registro"];
				$x_lista_tareas_ok = $x_lista_tareas_ok.$x_crm_tarea_id.", ";
				
				}	 
			
			
			}
	echo "lista con estatus nueva ".$x_lista_tareas_ok." <br>";
	 
	 $x_lista_tareas_ok = trim($x_lista_tareas_ok, ", ");
	 if (strlen($x_lista_tareas_ok)){
		// Eliminamos las tareas que no esten en la lista de ok
		
		#de la lista del promotor
			$sqlDeleteTareaPromotor = "DELETE FROM  tarea_diaria_promotor WHERE caso_id = $x_crm_caso_id and tarea_id != $x_crm_caso_id";
			$sqlDeleteTareaPromotor .= " and  tarea_id not in ($x_lista_tareas_ok)";
			$rsDTP = phpmkr_query($sqlDeleteTareaPromotor, $conn) or die("error al delete TP".phpmkr_error()."sql :".$sqlDeleteTareaPromotor);
			echo "Elimina tarea de la lista de promotor<br>".$sqlDeleteTareaPromotor."<br>";
			
			$sqdDeteleCRMT = "DELETE FROM crm_tarea WHERE  crm_caso_id =  $x_crm_caso_id and  `asunto` LIKE  'PP Carta 1' and crm_tarea_id  not in ($x_lista_tareas_ok) ";
			$rsDCT = phpmkr_query($sqdDeteleCRMT,$conn) or die("error al eliminar de CRM_TAREA".phpmkr_error()."sql :".$sqdDeteleCRMT);
			echo "Elimina tarea de la lista TAREAS CRM <br>".$sqdDeteleCRMT ."<br>";
			
			echo "las seleccionamos <br>";
			$sqlDeleteTareaPromotor = "Select * FROM  tarea_diaria_promotor WHERE caso_id = $x_crm_caso_id and tarea_id != $x_crm_caso_id ";
			$sqlDeleteTareaPromotor .= " and  tarea_id not in ($x_lista_tareas_ok)";
		//	$rsTT = phpmkr_query($sqlDeleteTareaPromotor,$conn)or die ("error".phpmkr_error().$sqlDeleteTareaPromotor);
			/*while($rowTT = phpmkr_fetch_array($rsTT)){
				echo $rowTT["tarea_id"]." -- ";
				}
				echo "<br>";*/
				
				$sqlDeleteTareaPromotor = "Select * FROM crm_tarea WHERE  crm_caso_id =  $x_crm_caso_id and  `asunto` LIKE  'PP Carta 1' and crm_tarea_id  not in ($x_lista_tareas_ok)";
		//	$rsTT = phpmkr_query($sqlDeleteTareaPromotor,$conn)or die ("error".phpmkr_error().$sqlDeleteTareaPromotor);
		/*	while($rowTT = phpmkr_fetch_array($rsTT)){
				echo $rowTT["crm_tarea_id"]." ** ";
				}*/
				
			
		
		
		
		
		 
		 
		 }
	 }
	
	 
	 }

$x_asignaGestor = $_POST["asignaGestor"];