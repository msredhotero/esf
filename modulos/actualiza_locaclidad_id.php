<?php include("../db.php") ?>
<?php include("../phpmkrfn.php") ?>
<?php
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
// actualiza_localidad = 

//actulaizamos todas las localidades 

$x_estado = 9;
$x_primera = 0;
if($x_primera){
$sqlUodate1 = " UPDATE direccion SET localidad_id =  (localidad_id + 1000) where entidad = $x_estado ";


$x_result = phpmkr_query($sqlUodate1, $conn);
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
		exit();
	}	
}

$sqlMunicipios = "SELECT delegacion_id_rsp, delegacion_id as dele FROM direcccion WHERE 	estado_id = $x_estado and  IS NOT NULL delegacion_id  and delegacion_id != 1000";
$x_resultQ = phpmkr_query($sqlMunicipios, $conn);
	if(!$x_resultQ){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
		exit();
	}	
while($row2 = phpmkr_fetch_array($x_resultQ))	{
	$x_delegacion_id =  $row2["delegacion_id_rsp"];
	$x_dele =  $row2["dele"];
	
	// busco todos las localidades de ese municipio
	$sqlLocalidades = " SELECT localidad_id_rsp FROM direccion WHERE entidad = ".$x_estado." and delegacion_id = ".$x_delegacion_id." ";
	$rsLoc =  phpmkr_query($sqlLocalidades, $conn) or die($sqlLocalidades);
	while($row2a = phpmkr_fetch_array($rsLoc))	{
		$x_localidad_id =  $row2["localidad_id"];
		$x_localidad_busqueda =  $row2["localidad_id_rsp"];
		
		// tenemos el id de la localidad buscamos el nuebvo id
		$sqlLOC = "SELECT * FROM localidad WHERE localidad_id = ".$x_localidad_busqueda." ";
		$rsLocqw =  phpmkr_query($sqlLOC, $conn) or die($sqlLOC);
		while($row2aw = phpmkr_fetch_array($rsLocqw))	{
		$descripion_loc = $row2aw["descripcion"];
		// con la descricion buscamos los dato
		
		$sqlInegi =  "SELECT * FROM inegi_localiad WHERE estado_id = ".$x_estado." AND municipio_id = ".$x_dele." and 	`descripcion` LIKE  '%".$descripion_loc."%'";
		$rsLocqwIN =  phpmkr_query($sqlInegi, $conn) or die($sqlLOC);
		$x_localidad_id_inegi = 0;
		while($rowIne = phpmkr_fetch_array($rsLocqwIN))	{
			
			$x_localidad_id_inegi = $rowIne["localidad_id"];
			
			
			}
			
			if($x_localidad_id_inegi){
				
				echo "Localidad id inegi =>".$x_localidad_id_inegi." localiad direccion ".$descripion_loc;
				}else{
					echo "NO SE ENCOTRO localiad direccion ".$descripion_loc;
					}
		
		}
		
		
		
		}
	
	
	}



$sql = " select * from  direccion ";
?>