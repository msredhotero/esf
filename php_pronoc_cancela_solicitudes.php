<?php

die();
 set_time_limit(0); ?>
<?php session_start(); ?>
<?php ob_start(); ?> 
<?php
// Initialize common variables
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php include ("utilerias/datefunc.php") ?>
<?php
$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
$currdate = ConvertDateToMysqlFormat($currdate);
$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];	

echo "CURDATE".$currdate."<BR>";
//$x_dia = strtoupper($currentdate["weekday"]);

//$currdate = "2007-07-10";
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

//$currdate = "2007-07-10";
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
//cancelar solitudes con mas de 5 dias como aprobadas
$today = date("Y-m-d, H:i:s");
//echo "hoy".$today."<br>";
$fecha_menos5dias = date('Y-m-d',time()-(6*24*60*60));
//echo "hoy menos 5 dias ".$fecha_menos5dias."<br>";

	// Varios destinatarios
		$para  = "jfoncerrada@financieracrea.com"; // atención a la coma
		$para2  = "analisis@financieracrea.com"; // atención a la coma
		// subject
		$titulo = " === SOLICITUD CANCELADA ===";						
		$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$cabeceras .= 'From: cronjobs@financieracrea.com';
		


#####################################################################
#####################################################################
################              NUEVAS          #######################
#####################################################################
#####################################################################
$sqlSolAPROBADAS = "SELECT * FROM solicitud where fecha_registro < \"$fecha_menos5dias\"  AND fecha_registro > \"2013-01-01\" AND fecha_registro IS NOT NULL and solicitud_status_id = 1 ";
echo "<BR><BR> PREANALISIS :".$sqlSolAPROBADAS."<br>";
$rsSOLAPROBADAS = phpmkr_query($sqlSolAPROBADAS,$conn) or die("Error al buscar aprobadas".phpmkr_error()."sql:".$sqlSolAPROBADAS);
while($rowSolAprobadas = phpmkr_fetch_array($rsSOLAPROBADAS)){
	// se cancelan las solictudes y se guarda en bitacora
	$x_solicitud_aprobada_id = $rowSolAprobadas["solicitud_id"];
	$x_comentario_comite = $rowSolAprobadas["comentario_comite"];
	echo "<br>solcitud ".$x_solicitud_aprobada_id." <br> comentarios ".$x_comentario_comite."<br> status ". $rowSolAprobadas["solicitud_status_id"];
	?>
    <a href="http://financieracrea.com/esf/php_solicitudview.php?solicitud_id=<?php echo $x_solicitud_aprobada_id;?>"> Ver solictud </a>
    <?php
	$bitacora = " ZOA ".$today. "\n ESTA SOLICITUD SE CANCELO AUTOMATICAMENTE PORQUE TENIA MAS DE 5 DIAS EN NUEVA, SI DESEA OTORGAR EL CREDITO DEBE INICIAR EL PROCESO NUEVAMENTE --- \n \n".$x_comentario_comite;
	$sqlUpdateSol = "UPDATE solicitud SET solicitud_status_id = 5,comentario_comite = \"$bitacora\"  WHERE solicitud_id = $x_solicitud_aprobada_id ";
	$rsUpdate = phpmkr_query($sqlUpdateSol,$conn) or die ("Error al cancelar las solitudes".phpmkr_error()."sql:".$sqlUpdateSol);
	echo "<br> BITACORA ".$bitacora;	
	$x_mensaje = " SOLICITUD NUEVA CANCELADA POR PROCESO NOCTURNO <br><br>";
	$x_mensaje .= "<a href=\"http://financieracrea.com/esf/php_solicitudview.php?solicitud_id=".$x_solicitud_aprobada_id."\"> Ver solicitud </a>";
	mail($para, $titulo, $x_mensaje, $cabeceras);	
	mail($para, $titulo, $x_mensaje, $cabeceras);	
	}


#####################################################################
#####################################################################
################            PREANALISIS       #######################
#####################################################################
#####################################################################
$sqlSolAPROBADAS = "SELECT * FROM solicitud where fecha_preanalisis < \"$fecha_menos5dias\" AND fecha_preanalisis IS NOT NULL and solicitud_status_id = 11 ";
echo "<BR><BR> PREANALISIS :".$sqlSolAPROBADAS."<br>";
$rsSOLAPROBADAS = phpmkr_query($sqlSolAPROBADAS,$conn) or die("Error al buscar aprobadas".phpmkr_error()."sql:".$sqlSolAPROBADAS);
while($rowSolAprobadas = phpmkr_fetch_array($rsSOLAPROBADAS)){
	// se cancelan las solictudes y se guarda en bitacora
	$x_solicitud_aprobada_id = $rowSolAprobadas["solicitud_id"];
	$x_comentario_comite = $rowSolAprobadas["comentario_comite"];
	echo "<br>solcitud ".$x_solicitud_aprobada_id." <br> comentarios ".$x_comentario_comite."<br> status ". $rowSolAprobadas["solicitud_status_id"];
	?>
    <a href="http://financieracrea.com/esf/php_solicitudview.php?solicitud_id=<?php echo $x_solicitud_aprobada_id;?>"> Ver solictud </a>
    <?php
	$bitacora = " ZOA ".$today. "\n ESTA SOLICITUD SE CANCELO AUTOMATICAMENTE PORQUE TENIA MAS DE 5 DIAS EN PREANALISIS, SI DESEA OTORGAR EL CREDITO DEBE INICIAR EL PROCESO NUEVAMENTE --- \n \n".$x_comentario_comite;
	$sqlUpdateSol = "UPDATE solicitud SET solicitud_status_id = 5,comentario_comite = \"$bitacora\"  WHERE solicitud_id = $x_solicitud_aprobada_id ";
	$rsUpdate = phpmkr_query($sqlUpdateSol,$conn) or die ("Error al cancelar las solitudes".phpmkr_error()."sql:".$sqlUpdateSol);
	echo "<br> BITACORA ".$bitacora;	
	$x_mensaje = " SOLICITUD EN PRE ANALISIS CANCELADA POR PROCESO NOCTURNO <br><br>";
	$x_mensaje .= "<a href=\"http://financieracrea.com/esf/php_solicitudview.php?solicitud_id=".$x_solicitud_aprobada_id."\"> Ver solicitud </a>";
	mail($para, $titulo, $x_mensaje, $cabeceras);	
	mail($para, $titulo, $x_mensaje, $cabeceras);	
	}
	
	
	
	
	#####################################################################
	#####################################################################
	################            SUPERVISION       #######################
	#####################################################################
	#####################################################################
	$sqlSolAPROBADAS = "SELECT * FROM solicitud where fecha_supervision < \"$fecha_menos5dias\"  and  fecha_supervision IS NOT NULL and solicitud_status_id = 9  and solicitud_id not in (9149,9025)";
	echo "<BR><BR> SUPERVISION :".$sqlSolAPROBADAS."<br>";
	$rsSOLAPROBADAS = phpmkr_query($sqlSolAPROBADAS,$conn) or die("Error al buscar aprobadas".phpmkr_error()."sql:".$sqlSolAPROBADAS);
	while($rowSolAprobadas = phpmkr_fetch_array($rsSOLAPROBADAS)){
		// se cancelan las solictudes y se guarda en bitacora
		$x_solicitud_aprobada_id = $rowSolAprobadas["solicitud_id"];
		$x_comentario_comite = $rowSolAprobadas["comentario_comite"];
		echo "<br>solcitud ".$x_solicitud_aprobada_id." <br> comentarios ".$x_comentario_comite."<br> status ". $rowSolAprobadas["solicitud_status_id"];
		?>
    <a href="http://financieracrea.com/esf/modulos/php_solicitudeditIndividual.php?solicitud_id=<?php echo $x_solicitud_aprobada_id;?>"> Ver solictud </a>
    <?php
		$bitacora = " ZOA ".$today. "\n ESTA SOLICITUD SE CANCELO AUTOMATICAMENTE PORQUE TENIA MAS DE 5 DIAS EN SUPERVISION, SI DESEA OTORGAR EL CREDITO DEBE INICIAR EL PROCESO NUEVAMENTE --- \n \n".$x_comentario_comite;
		$sqlUpdateSol = "UPDATE solicitud SET solicitud_status_id = 5,comentario_comite = \"$bitacora\"  WHERE solicitud_id = $x_solicitud_aprobada_id ";
		$rsUpdate = phpmkr_query($sqlUpdateSol,$conn) or die ("Error al cancelar las solitudes".phpmkr_error()."sql:".$sqlUpdateSol);
		echo "<br> BITACORA ".$bitacora."<BR><BR>";	
		
		$x_mensaje = " SOLICITUD EN SUPERVISION CANCELADA POR PROCESO NOCTURNO<br><br> ";
	    $x_mensaje .= "<a href=\"http://financieracrea.com/esf/php_solicitudview.php?solicitud_id=".$x_solicitud_aprobada_id."\"> Ver solicitud </a>";
		mail($para, $titulo, $x_mensaje, $cabeceras);	
		mail($para, $titulo, $x_mensaje, $cabeceras);	
		}	
	


	#####################################################################
	#####################################################################
	################            COMITE            #######################
	#####################################################################
	#####################################################################
	$sqlSolAPROBADAS = "SELECT * FROM solicitud where fecha_investigacion < \"$fecha_menos5dias\" and fecha_investigacion IS NOT NULL and solicitud_status_id = 2 ";
	echo "<BR><BR> COMITE :".$sqlSolAPROBADAS."<br>";
	$rsSOLAPROBADAS = phpmkr_query($sqlSolAPROBADAS,$conn) or die("Error al buscar aprobadas".phpmkr_error()."sql:".$sqlSolAPROBADAS);
	while($rowSolAprobadas = phpmkr_fetch_array($rsSOLAPROBADAS)){
		// se cancelan las solictudes y se guarda en bitacora
		$x_solicitud_aprobada_id = $rowSolAprobadas["solicitud_id"];
		$x_comentario_comite = $rowSolAprobadas["comentario_comite"];
		echo "<br>solcitud ".$x_solicitud_aprobada_id." <br> comentarios ".$x_comentario_comite."<br> status ". $rowSolAprobadas["solicitud_status_id"];
		
		$bitacora = " ZOA ".$today. "\n ESTA SOLICITUD SE CANCELO AUTOMATICAMENTE PORQUE TENIA MAS DE 5 DIAS EN COMITE, SI DESEA OTORGAR EL CREDITO DEBE INICIAR EL PROCESO NUEVAMENTE --- \n \n".$x_comentario_comite;
		$sqlUpdateSol = "UPDATE solicitud SET solicitud_status_id = 5,comentario_comite = \"$bitacora\"  WHERE solicitud_id = $x_solicitud_aprobada_id ";
		$rsUpdate = phpmkr_query($sqlUpdateSol,$conn) or die ("Error al cancelar las solitudes".phpmkr_error()."sql:".$sqlUpdateSol);
		echo "<br> BITACORA ".$bitacora;
		
		$x_mensaje = " SOLICITUD EN COMITE CANCELADA POR PROCESO NOCTURNO<BR><BR> ";
	    $x_mensaje .= "<a href=\"http://financieracrea.com/esf/php_solicitudview.php?solicitud_id=".$x_solicitud_aprobada_id."\"> Ver solicitud </a>";
			
		mail($para, $titulo, $x_mensaje, $cabeceras);	
		mail($para, $titulo, $x_mensaje, $cabeceras);		
		}	



	
	#####################################################################
	#####################################################################
	################            APROBADA          #######################
	#####################################################################
	#####################################################################
	$sqlSolAPROBADAS = "SELECT * FROM solicitud where fecha_aprobada < \"$fecha_menos5dias\"  and fecha_aprobada IS NOT NULL and solicitud_status_id = 3  ";
	echo "<BR><BR> APROBADA :".$sqlSolAPROBADAS."<br>";
	//$rsSOLAPROBADAS = phpmkr_query($sqlSolAPROBADAS,$conn) or die("Error al buscar aprobadas".phpmkr_error()."sql:".$sqlSolAPROBADAS);
	while($rowSolAprobadas = phpmkr_fetch_array($rsSOLAPROBADAS)){
		// se cancelan las solictudes y se guarda en bitacora
		$x_solicitud_aprobada_id = $rowSolAprobadas["solicitud_id"];
		$x_comentario_comite = $rowSolAprobadas["comentario_comite"];
		echo "<br>solcitud ".$x_solicitud_aprobada_id." <br> comentarios ".$x_comentario_comite."<br> status ". $rowSolAprobadas["solicitud_status_id"];
		
		$bitacora = " ZOA ".$today. "\n ESTA SOLICITUD SE CANCELO AUTOMATICAMENTE PORQUE TENIA MAS DE 5 DIAS COMO APROBADA, SI DESEA OTORGAR EL CREDITO DEBE INICIAR EL PROCESO NUEVAMENTE --- \n \n".$x_comentario_comite;
		$sqlUpdateSol = "UPDATE solicitud SET solicitud_status_id = 5,comentario_comite = \"$bitacora\"  WHERE solicitud_id = $x_solicitud_aprobada_id ";
		$rsUpdate = phpmkr_query($sqlUpdateSol,$conn) or die ("Error al cancelar las solitudes".phpmkr_error()."sql:".$sqlUpdateSol);

		echo "<br> BITACORA ".$bitacora."<BR><BR>";	
		
		$x_mensaje = " SOLICITUD APROVADA CANCELADA POR PROCESO NOCTURNO<BR><BR> ";
	    $x_mensaje .= "<a href=\"http://financieracrea.com/esf/php_solicitudview.php?solicitud_id=".$x_solicitud_aprobada_id."\"> Ver solicitud </a>";
		mail($para, $titulo, $x_mensaje, $cabeceras);	
		mail($para, $titulo, $x_mensaje, $cabeceras);	
		}	


?>