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
	 $sqlCount = "SELECT COUNT(*) as carta2 FROM crm_tarea WHERE `asunto` LIKE  'Carta 2'  AND crm_caso_id = $x_crm_caso_id  " ;
	  $sqlCount .= " AND fecha_registro <= \"2013-03-12\" ";
	 $rsCount = phpmkr_query($sqlCount,$conn)or die("Error al selecionar las pp c1".phpmkr_error()."sql:".$sqlCount);
	 $rowCount = phpmkr_fetch_array($rsCount);
	 $x_carta2 = $rowCount["carta2"];
	 
	 
	 echo  "carata enc".$x_carta2."<br>";
	 if($x_carta2 > 0){
		 $x_cartas_2 = "";
		 //buscamos la tareas que tengan la fecha de la promesa de pago que no esten completas
		 echo "tiene carata<br>";
		 $sqlCount = "SELECT * FROM crm_tarea WHERE `asunto` LIKE  'Carta 2'  AND crm_caso_id = $x_crm_caso_id ORDER BY crm_tarea_status_id";
		 $rsCount = phpmkr_query($sqlCount,$conn)or die("Error al selecionar las pp c1".phpmkr_error()."sql:".$sqlCount);
		 while($rowCount = phpmkr_fetch_array($rsCount)){
		 $x_crm_tarea_id = $rowCount["crm_tarea_id"]; 
		 echo "tAREA ID". $x_crm_tarea_id."<BR>";
		 $x_crm_tarea_status_id = $rowCount["crm_tarea_status_id"];
		 $x_fecha_registro = $rowCount["fecha_registro"];
		 $x_cartas_2 = $x_cartas_2.$x_crm_tarea_id.", "; 
		 }
		$x_cartas_2 = trim($x_cartas_2, ", ");
		 
		 
		 echo "<br><br> lista de cartas ".$x_cartas_2."<br> ";
			if($x_cartas_2 != ""){
		$sql = "DELETE FROM crm_tarea WHERE crm_tarea_id in ($x_cartas_2)";
		//$rsD = phpmkr_query($sql,$conn)or die ("Error");
		
		$sele = "SELECT * FROM crm_tarea WHERE crm_tarea_id in ($x_cartas_2) ";
		$rsD = phpmkr_query($sele,$conn)or die ("Error");
		while($rowt = phpmkr_fetch_array($rsD)){
			$x_casra_id = $rowt["crm_tarea_id"];
			//echo $x_casra_id."<br>";
			}
		
			}
		
		
		 
		 
	 }
	
	 
	 }

$x_asignaGestor = $_POST["asignaGestor"];