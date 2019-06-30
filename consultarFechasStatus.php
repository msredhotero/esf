<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>

<?php

$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
mysql_query ("SET NAMES 'utf8'");
$x_id = $_GET["id"]; #1
$x_valore = explode("-",$x_id);
$x_solicitud_id = $x_valore[0];
$x_status_id = $x_valore[1];
 
// echo "SOLICTUD_ID ".$x_solicitud_id." status id ".$x_status_id;
 if(!empty($x_status_id)){
	 $x_numero = 1;
 // seleccionamos todas las fechas existentes en ese estatus de la solicitud correspondiente.
 $sqlSOL = "SELECT * FROM solicitud_fecha_status where solicitud_id = $x_solicitud_id and status_id = $x_status_id order by solicitud_fecha_status_id ";
 $rsSol = phpmkr_query($sqlSOL, $conn) or die ("Error al seleccionar los datos".phpmkr_error()."sql:".$sqlSOL);
 while ($rowSol = phpmkr_fetch_array($rsSol)){
	 $x_usuario_id = $rowSol["usuario_id"];
	 $sqlNombre ="Select nombre from usuario WHERE usuario_id = $x_usuario_id ";
	 $rsNombre = phpmkr_query($sqlNombre,$conn) or die ("Error al seleccionar le nombre del usuario".phpmkr_error()."sql: ".$sqlNombre);
	 $rowNobre = phpmkr_fetch_array($rsNombre);
	 $x_usuario =  $rowNobre["nombre"];	 
	 $x_fecha = $rowSol["fecha"];
	 $x_hora = $rowSol["hora"];
	 $x_td = $x_td ."<tr><td> $x_numero.- El ".$x_fecha."  </td><td> a las ".$x_hora."  </td><td> por ". htmlspecialchars($x_usuario)."</td></tr>";
	  $x_numero ++;
	 }
 }else{
	 echo "NO HAY MAS FECHAS REGISTRADAS";
	 }
 
 $x_tabla = "<table>";
 $x_tabla .= $x_td."</table>";
 
 if(empty($x_fecha)){
	 echo "NO HAY MAS FECHAS REGISTRADAS";
	 }else{
		 echo  $x_tabla;
		 }

 
?>