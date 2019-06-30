<?php include("db.php") ?>
<?php include("phpmkrfn.php") ?>
<?php
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
// actualiza_localidad = 

//actulaizamos todas las localidades 

$x_estado = 15;
$x_primera = 0;
if($x_primera){
$sqlUodate1 = " UPDATE direccion SET localidad_id =  (localidad_id + 1000) where entidad = $x_estado ";


$x_result = phpmkr_query($sqlUodate1, $conn);
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
		exit();
	}	
}

$sqlMunicipios = "SELECT delegacion_id_rsp, delegacion_id as dele FROM direccion WHERE 	entidad = $x_estado and  delegacion_id_rsp IS NOT NULL   and delegacion_id < 1000 group by delegacion_id_rsp";
$x_resultQ = phpmkr_query($sqlMunicipios, $conn);
	if(!$x_resultQ){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
		exit();
	}	
while($row2 = phpmkr_fetch_array($x_resultQ))	{
	$x_delegacion_id =  $row2["delegacion_id_rsp"];
	$x_dele =  $row2["dele"];
	
	// busco todos las localidades de ese municipio
	$sqlLocalidades = " SELECT localidad_id_rsp FROM direccion WHERE entidad = ".$x_estado." and delegacion_id_rsp = ".$x_delegacion_id." and localidad_id_rsp IS NOT NULL group by localidad_id_rsp ";
	#echo $sqlLocalidades."<br>";
	$rsLoc =  phpmkr_query($sqlLocalidades, $conn) or die($sqlLocalidades);
	while($row2a = phpmkr_fetch_array($rsLoc))	{
		$x_localidad_id =  $row2a["localidad_id"];
		$x_localidad_busqueda =  $row2a["localidad_id_rsp"];
		
		#echo  "LOCALIDAD RESPALDO=>".$x_localidad_busqueda."<br>";
		
		// tenemos el id de la localidad buscamos el nuebvo id
		$sqlLOC = "SELECT * FROM localidad WHERE localidad_id = ".$x_localidad_busqueda."  and delegacion_id = ".$x_delegacion_id." ";
		$rsLocqw =  phpmkr_query($sqlLOC, $conn) or die($sqlLOC);
		
		
		while($row2aw = phpmkr_fetch_array($rsLocqw))	{
		$descripion_loc = $row2aw["descripcion"];
		
		#echo $descripion_loc ."<br>";
		// con la descricion buscamos los dato
		
		$sqlInegi =  "SELECT * FROM inegi_localidad WHERE estado_id = ".$x_estado." AND municipio_id = ".$x_dele." and 	`descripcion` LIKE  '%".$descripion_loc."%'";
		$rsLocqwIN =  phpmkr_query($sqlInegi, $conn) or die($sqlInegi);
		$x_localidad_id_inegi = 0;
		#echo $sqlInegi;
		while($rowIne = phpmkr_fetch_array($rsLocqwIN))	{
			
			$x_localidad_id_inegi = $rowIne["localidad_id"];
			
			
			}
			
			if($x_localidad_id_inegi){
				
				#echo "<br>Localidad id inegi =>".$x_localidad_id_inegi." localiad direccion ".$descripion_loc."loc_resp_id =>".$x_localidad_busqueda ." mun =>".$x_dele;
				$sqlUpdateLoc =  "UPDATE direccion set localidad_id = ".$x_localidad_id_inegi." WHERE entidad = ".$x_estado ." and localidad_id_rsp = ".$x_localidad_busqueda.";";
				#phpmkr_query($sqlUpdateLoc, $conn) or die($sqlUpdateLoc);
				 echo $sqlUpdateLoc."<br><br>";
				}else{
					#echo "<br>NO SE ENCOTRO localiad direccion ".$descripion_loc ."loc_resp_id =>".$x_localidad_busqueda ." mun =>".$x_dele;
					}
		
		}
		
		
		
		}
	
	
	}

/*
UPDATE direccion set localidad_id = 1 WHERE entidad = 6 and localidad_id_rsp = 3300;
UPDATE direccion set localidad_id = 96 WHERE entidad = 6 and localidad_id_rsp = 3343;
UPDATE direccion set localidad_id = 184 WHERE entidad = 6 and localidad_id_rsp = 3403;
UPDATE direccion set localidad_id = 218 WHERE entidad = 6 and localidad_id_rsp = 3428;
UPDATE direccion set localidad_id = 229 WHERE entidad = 6 and localidad_id_rsp = 3438;
UPDATE direccion set localidad_id = 646 WHERE entidad = 6 and localidad_id_rsp = 1434;
UPDATE direccion set localidad_id = 1 WHERE entidad = 6 and localidad_id_rsp = 2239;
UPDATE direccion set localidad_id = 188 WHERE entidad = 6 and localidad_id_rsp = 2247;

UPDATE direccion set localidad_id = 1 WHERE entidad = 9 and localidad_id_rsp = 1;
UPDATE direccion set localidad_id = 1 WHERE entidad = 9 and localidad_id_rsp = 11;
UPDATE direccion set localidad_id = 1 WHERE entidad = 9 and localidad_id_rsp = 12;
UPDATE direccion set localidad_id = 1 WHERE entidad = 9 and localidad_id_rsp = 13;
UPDATE direccion set localidad_id = 1 WHERE entidad = 9 and localidad_id_rsp = 37;
UPDATE direccion set localidad_id = 1 WHERE entidad = 9 and localidad_id_rsp = 38;
UPDATE direccion set localidad_id = 1 WHERE entidad = 9 and localidad_id_rsp = 39;
UPDATE direccion set localidad_id = 1 WHERE entidad = 9 and localidad_id_rsp = 40;
UPDATE direccion set localidad_id = 1 WHERE entidad = 9 and localidad_id_rsp = 41;
UPDATE direccion set localidad_id = 1 WHERE entidad = 9 and localidad_id_rsp = 75;
UPDATE direccion set localidad_id = 1 WHERE entidad = 9 and localidad_id_rsp = 320;
UPDATE direccion set localidad_id = 307 WHERE entidad = 9 and localidad_id_rsp = 369;
UPDATE direccion set localidad_id = 19 WHERE entidad = 9 and localidad_id_rsp = 370;
UPDATE direccion set localidad_id = 115 WHERE entidad = 9 and localidad_id_rsp = 382;
UPDATE direccion set localidad_id = 209 WHERE entidad = 9 and localidad_id_rsp = 427;
UPDATE direccion set localidad_id = 1 WHERE entidad = 9 and localidad_id_rsp = 561;



*/




$sql = " select * from  direccion ";
?>