<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>
<?php include ("phpmkrfn.php") ?>

<?php
 		 $currentdate2 = getdate(time());
		 $currdate2 = $currentdate2["mday"]."/".$currentdate2["mon"]."/".$currentdate2["year"];
		 
		//$fecha_actual = ConvertDateToMysqlFormat($currdate2);
		
		$fecha_actual = strtotime(date("Y-m-d ",time())); 
		$fecha_inicio_sorteo = strtotime("2011-04-01");
		$fecha_limite_sorteo = strtotime("2011-12-04");
		
		echo  "fecha actual;".$fecha_actual;
		echo "fecha_inicio".$fecha_inicio_sorteo;
		echo "fecha_fin".$fecha_limite_sorteo;
		
		if(($fecha_actual > $fecha_inicio_sorteo)&&($fecha_actual < $fecha_limite_sorteo)){ 
		//fecha valida para generar los folios de la rifa
				echo "entro a fecha del sorteo<p>";
				
		}else{
			echo "no entro al sorteo";
			}
			
			
	echo "<p>forma antigua <p>";		
			
			
		$fecha_actual = strtotime(date("d-m-Y H:i:00",time())); 
		$fecha_inicio_sorteo = strtotime("30-04-2011 01:00:00");
		$fecha_limite_sorteo = strtotime("01-12-2011 21:00:00");
		//$fecha_limite_sorteo = strtotime("04-04-2011 21:00:00");
		echo "fecha actual =".$fecha_actual ;
		echo "fecha_inicio".$fecha_inicio_sorteo;
		echo "fecha_fin".$fecha_limite_sorteo;
		
			//pagos puntuales 3 folios	
			echo "entro a folio triple 1";	  
		if(($fecha_actual > $fecha_inicio_sorteo)&&($fecha_actual < $fecha_limite_sorteo)){ 
		echo "entro al sorte 2";
		
		}else{
			echo "no entro al sorteo 2";
			}
?>
</body>
</html>