

<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
$x_listado_integrantes = trim($_GET["x_lista_integrantes"],"-");

$x_integrantes = explode("-", $x_listado_integrantes);

//print_r($x_integrantes);
$x_numero_pagos = $_GET["x_numero_pagos"];
$x_importe_solicitado = $_GET["x_importe_solicitado"];
 $tamaño = sizeof($x_integrantes);
 
$x_columnas=3;
$x_compara = 1;
$x_inicio_tabla =  "<table border='0' cellpadding='0' cellspacing='0' width='100%'>"; // se inicia la tabla
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
foreach($x_integrantes as  $valor){
	//sql Seleccionamos el nombre del integrante de comite
	$sqlComite = "SELECT * FROM  comite_credito_participantes_lista where comite_credito_participantes_lista_id = $valor ";
	$rsComite = phpmkr_query($sqlComite,$conn) or die ("Error al seeccionar los dtos del particpante".phpmkr_error()."sql_".$sqlComite);
	$rowComite =  phpmkr_fetch_array($rsComite);
	$x_nombre_integrante =  $rowComite["nombre"];
	 if( $valor== 2 || $valor== 5 || $valor== 7 ){
		 $x_imagen = "<img src=\"images/login/user2.jpg\" width=\"96\" height=\"94\" />";
		 }else{
			  $x_imagen = "<img src=\"images/login/user1.jpg\" width=\"96\" height=\"94\" />";
			 }
	
	
			 
	$x_tabla =  "<td><table class=\"entera\"><tr><td>&nbsp;</td><td>&nbsp;</td></tr>";		 
	$x_tabla .="<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
			    </tr>
			  <tr>
					<td align=\"center\"> $x_imagen <br> $x_nombre_integrante</td>
					<td rowspan=\"2\"><div id=\"x_firma_correcta_".$valor."\"><input type=\"hidden\"   class=\"campo_oculto\" name=\"x_oculto_".$x_valor."\" id=\"x_oculto_".$x_id."\"  value=\"0\" /></div></td>
			  </tr>
			  <tr>
					<td align=\"center\"><div id=\"x_firma_".$valor."\"><input type=\"password\"  class=\"x_password\" name=\"x_password_".$valor."\" id=\"x_password_".$valor."\"></div></td>
			  </tr>
			  <tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
			  </tr>";
			  $x_tabla .= "</table></td>";
			  
	$celdacontenido = 	$x_tabla ;
	if ($x_compara==1){	
	
	$x_inicio_tabla .= "<tr>".$celdacontenido;// agreagamos el tr y la celda
}
if ($x_compara<>1){
	if ($x_compara<>$x_columnas){		
		 $x_inicio_tabla .= $celdacontenido;
		 }
	} 
if ($x_compara == $x_columnas){
 $x_inicio_tabla .= $celdacontenido."</tr>"; 
$x_compara = 1;
}
else {$x_compara ++;
}	  
			  
	}

$x_inicio_tabla .= "</table>"; // cierra la tabla
echo  $x_inicio_tabla;

		
			
			
?>
