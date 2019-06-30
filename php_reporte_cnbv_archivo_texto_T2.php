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
$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];
$currdate = $currentdate["year"]."/".$currentdate["mon"]."/".$currentdate["mday"];
?>

<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString

$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);



#Generamos un archivo de texto plano

$x_today = date("Y-m-d");



# el reporte se hace por trimestres, se ejecuta manual #PRIMER TRIMESTRE ANUAL 
$arra_fecha = explode("-",$x_today);
$anio = $arra_fecha[0];
#echo $anio;

$fecha_inicio =  $arra_fecha[0]."-"."04"."-01";
$fecha_fin = $arra_fecha[0]."-"."06"."-30";

$año_nombre_reporte = substr($arra_fecha[0],2,4);
$nombre_archivo =  "1069119".$año_nombre_reporte."06.002";
$fp = fopen("reportes_cnbv/".$nombre_archivo ,"w");

// hacemos la consulta a la base de datos para buscar todos los registros del archivo
		$sSql = " SELECT * FROM reporte_cnbv WHERE eliminar IS NULL AND  tipo_reporte_id=1 AND `fecha_registro` >=  '".$fecha_inicio."' AND `fecha_registro` <=  '".$fecha_fin."' "; // WHERE reporte_cnbv_id  = ".$reporte_id;
		$rs = phpmkr_query($sSql,$conn) or die("Error al seleccionar los datos de la solicitud".phpmkr_error()."sql :".$sql);
		#echo  $sSql ;
		if(!$rs ){
			$x_load_data  = false;
			}
		
		
		$x_count= 0;
		while($row = phpmkr_fetch_array($rs)){
			
		$sql_Campos = " DESCRIBE reporte_cnbv ";
		$rs_CAMPOS = phpmkr_query($sql_Campos,$conn) or die("Error al seleccionar los datos de la solicitud".phpmkr_error()."sql :".$sql_Campos);	
		$cadena = '';
		while($rowcMPOS = phpmkr_fetch_array($rs_CAMPOS)){
			//
			$x_nombre_campo = $rowcMPOS["Field"];
			if(($x_nombre_campo != 'reporte_cnbv_id') && ($x_nombre_campo != 'cliente_id') && ($x_nombre_campo != 'solicitud_id')  ){
			$x_campo = "x_".$rowcMPOS["Field"];
			$$campo = $row[$x_nombre_campo];
			$tipo_dato = $rowcMPOS["Type"];
			
			$arra_tipo_dato = explode("(",$tipo_dato);
			$descricion_tipo = $arra_tipo_dato[0];
			$logitud_tipo = trim( $arra_tipo_dato[1], ")") ;
			
			if($descricion_tipo =='varchar'){
				#echo $descricion_tipo ." de ".$logitud_tipo."==>";
				// se quita ";" se pasa a mayusculas y se trunca  a la longitud establecida
				$cadena_temporal = preparaCampoVarchar($row[$x_nombre_campo],$logitud_tipo);
				$cadena .= $cadena_temporal.";";
				}else{
			$cadena .= $row[$x_nombre_campo].";";
				}
			#echo $cadena."<br>";
			}	
		}
			fwrite($fp,$cadena.PHP_EOL) or die("Error la escribir");
			$x_count ++;
		
		}
		
		if($x_count ==0){
			// no hay ningun registro
			 #cuando no hay registros durante el periodo correspondient deberan enviar el archivo capturando unicamente la informacion
			 # corrspondiente a las columnas
			 # 1 tipo de reporte
			 # 2 periodo reporte
			 # 4 organo supervisor y
			 # 5 clave del sujeto obligado
			 $fecha = $arra_fecha[0]."06";
			 
			 $cadena = '';
			 #$cadena  = "1;".$fecha.";;001002;069119;";			 
			 $cadena = "1;".$fecha.";;001002;069119;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;";
			 fwrite($fp,$cadena.  PHP_EOL);
			
			}
		
		
		

#fclose($fp);


// insertamos en la base de datos


$SQLInsert = "INSERT INTO `reporte_cnv_archivos` (`reporte_cnbv_id`, `archivo`, `fecha_creacion`, `no_registros`)";
$SQLInsert .= " VALUES (NULL, '".$nombre_archivo."', '".$x_today."', $x_count)";

$rs2 = phpmkr_query($SQLInsert,$conn) or die("Error al seleccionar los datos de la solicitud".phpmkr_error()."sql :".$SQLInsert);
	
	
	echo "<center>
	ARCHIVO GENERADO CORRECTAMENTE <br><BR> TRIMESTRE 2 DEL A&Ntilde;O ".$arra_fecha[0]."</B><BR>".$nombre_archivo."<br></center>";
	echo "<CENTER><a  href=\"php_reporte_cnbv_archivolist.php\" > consultar archivos</a></center>";





function preparaCampoVarchar($cadena, $long_max){
	
	$temporal = trim($cadena," ");// quitamos espacios
	// quitamos los ";" que tenga la cadena	
	$temporal = str_replace(";", " ", $temporal);
	// pasamos a mayusculas la cadena
	$temporal =  strtoupper($temporal);
	//cortamos la cadena a la logitud establecida
	$tamaño_actual = strlen($temporal);
	if($tamaño_actual >$long_max ){
		#echo "*****************************<br>";
		$temporal =  substr ( $temporal,0,$long_max);	
	
	}
	return $temporal ;
}
?>