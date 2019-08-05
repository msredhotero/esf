<?php
session_start();
ob_start();

if (@$_SESSION["php_project_esf_status"] <> "login") {
	header("Location:  ../../../login.php");
	exit();
}
?>

<?php
if(ini_set("max_execution_time", "0"))
//echo "Parámetro cambiado";

//echo "Error al cambiar el parámetro";
?> 

<?php 
$x_carga_id = $_POST['x_carga_id'];
$x_banco_id = $_POST['x_banco_id'];
$x_medio_pago_id = $_POST['x_medio_pago_id ='];

if(!$x_carga_id || $x_carga_id == ""){
	$x_carga_id = $_GET['x_carga_id'];
}

include ("../../../db.php");
include ("../../../phpmkrfn.php");
include_once ("./includes.inc");
include_once ("./settings.inc");
include_once ("$parser_path/excelparser.php");
//include_once("../../excelparser.php");

if ( !isset($_POST['step']) )
	$_POST['step'] = 0;
	
?>

<html>
<head>
<title>Carga de Pagos</title>
<STYLE>
<!--
body, table, tr, td {font-size: 12px; font-family: Verdana, MS sans serif, Arial, Helvetica, sans-serif}
td.index {font-size: 10px; color: #000000; font-weight: bold}
td.empty {font-size: 10px; color: #000000; font-weight: bold}
td.dt_string {font-size: 10px; color: #000090; font-weight: bold}
td.dt_int {font-size: 10px; color: #909000; font-weight: bold}
td.dt_float {font-size: 10px; color: #007000; font-weight: bold}
td.dt_unknown {font-size: 10px; background-color: #f0d0d0; font-weight: bold}
td.empty {font-size: 10px; background-color: #f0f0f0; font-weight: bold}
-->
</STYLE>
<script type="text/javascript">
<!--
function closeform(){
	window.opener.document.filtro.x_procesado.value = 1;
	window.opener.document.filtro.x_elimina.value = 1;
		
	window.opener.document.filtro.submit();		
//	window.opener.location.reload();
	window.close();
}	
//-->
</script>
</head>
<body text="#000000" link="#000000" vlink="#000000" alink="#000000" topmargin="0" leftmargin="2" marginwidth="0" marginheight="0">

<table width="100%" align="center" bgcolor="#006699">
<tr>
	<td>&nbsp;</td>
	<td width="60%"><font color="#FFFFFF" size="+2">Enjoy - Carga de pagos</font></td>
	<td width="40%" align="right"><font color="#FFFFFF">createchnologies</font> </td>
	<td>&nbsp;</td>
</tr>
</table>

<?php

// Outputting fileselect form (step 0)

if ( $_POST['step'] == 0 )
	echo <<<FORM
<table width="100%" border="0" align="center" bgcolor="#7EA9D3">
<tr>
<td>&nbsp;</td>
<td>
<p>&nbsp;</p>
Seleccione el archivo de Excel 
<p>&nbsp;</p>
</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>

<table border="0">
<form name="exc_upload" method="post" action="" enctype="multipart/form-data">
<input type="hidden" name="x_carga_id" value="$x_carga_id">
<tr><td>Archivo Excel:</td><td><input type="file" size=30 name="excel_file"></td></tr>
<tr><td><div Id=mensaje Style="visibility:hidden;">Procesando espere...</div></td><td></td></tr>
<!--
<tr><td>Utilizar primera fila como encabezados:</td><td><input type="checkbox" name="useheaders"></td></tr>
-->
<tr><td colspan="2" align="right">
<input type="hidden" name="step" value="1">
<input type="hidden" name="useheaders" value="1">
<input type="button" value="Siguiente" onClick="
javascript:
if( (document.exc_upload.excel_file.value.length==0))
{ 
alert('Primero debe de indicar el archivo'); 
submit();
return; 
}else{
mensaje.style.visibility='visible';
submit();
return;
} 
"></td></tr>


</form>
</table>

</td>
</tr>


<tr>
<td>&nbsp;</td>
<td align="right">
<p>&nbsp;</p>
createchnologies.&nbsp;&nbsp;
</td>
</tr>
</table>

FORM;

// Processing excel file (step 1)

if ( $_POST['step'] == 1 ) {
	
	echo "<br>";
	
	// Uploading file
	
	$excel_file = $_FILES['excel_file'];
	if( $excel_file )
		$excel_file = $_FILES['excel_file']['tmp_name'];

	if( $excel_file == '' ) fatal("No se cargo el archivo");
	
	move_uploaded_file( $excel_file, 'upload/' . $_FILES['excel_file']['name']);	
	$excel_file = 'upload/' . $_FILES['excel_file']['name'];
	
	$fh = @fopen ($excel_file,'rb');
	if( !$fh ) fatal("No se cargo el archivo");
	if( filesize($excel_file)== 0 ) fatal("No file uploaded");

	$fc = fread( $fh, filesize($excel_file) );
	@fclose($fh);
	if( strlen($fc) < filesize($excel_file) )
		fatal("No se puede leer el archivo.");	
	
	
	// Check excel file
	
	$exc = new ExcelFileParser;
	$res = $exc->ParseFromString($fc);
	
	switch ($res) {
		case 0: break;
		case 1: fatal("No se puede abrir el archivo");
		case 2: fatal("El archio no parece ser un archivo de excel");
		case 3: fatal("Error en el encabezado del archivo");
		case 4: fatal("Error al leer el archivo");
		case 5: fatal("Este no es un archivo de excel o la version es muy antigua");
		case 6: fatal("El archivo esta corrupto");
		case 7: fatal("No se localizaron datos en el archivo");
		case 8: fatal("Esta version del archivo no puede ser procesada");

		default:
			fatal("Error");
	}
	
		
	// Pricessing worksheets
	// numero de hojas del archivo
	$ws_number = count($exc->worksheet['name']);
	if( $ws_number < 1 ) fatal("No hay datos en el archivo.");
	
	$ws_number = 1; // Setting to process only the first worksheet
	
	for ($ws_n = 0; $ws_n < $ws_number; $ws_n++) {
		
		$ws = $exc -> worksheet['data'][$ws_n]; // Get worksheet data
			
		if ( !$exc->worksheet['unicode'][$ws_n] )
			$db_table = $ws_name = $exc -> worksheet['name'][$ws_n];
		else 	{
			$ws_name = uc2html( $exc -> worksheet['name'][$ws_n] );
			$db_table = convertUnicodeString ( $exc -> worksheet['name'][$ws_n] );
			}
		
		echo "<div align=\"center\"><b>Lista de Pagos<br><br><font color='red'>Revise la lista, que todos los campos presenten el valor en el formato correcto. Presione el bot&oacute;n procesar localizado al final de la p&aacute;gina.</font></div><br>";

		
		$max_row = $ws['max_row'];
		$max_col = $ws['max_col'];
		
		if ( $max_row > 0 && $max_col > 0 )
			getTableData ( &$ws, &$exc ); // Get structure and data of worksheet
		else fatal("Hoja vacia");
		
	}
	
}

if ( $_POST['step'] == 2 ) { // Adding data into mysql (step 2)
		
	echo "<br>";
	
	extract ($_POST);

	$db_table = ereg_replace ( "[^a-zA-Z0-9$]", "", $db_table );
	$db_table = ereg_replace ( "^[0-9]+", "", $db_table );

	//crear tabla tmeporal de carga
/*	
	$db_table = "tmp_pgo_".rand(1,1000);

	if ( empty ( $db_table ) )
		$db_table = "tmp_pgo_lis";
*/
	$db_table = "tmp_apl_pgo";

	// Database connect check
	
	if ( !$link = @mysql_connect ($db_host, $db_user, $db_pass) )
        fatal("Error en la conexion con la base de datos.");
	
	if ( !$connect = mysql_select_db ($db_name ) )
        fatal("Base de datos incorrecta.");
		
	if ( empty ($db_table) )
		fatal("Nombre de la tabla vacia.");
	
	if ( !isset ($fieldcheck) )
		fatal("No se seleccionaron los campos.");
	
	if ( !is_array ($fieldcheck) )
		fatal("No se seleccionaron los campos.");
	
	$tbl_SQL .= "CREATE TABLE IF NOT EXISTS $db_table ( ";
	
	foreach ($fieldcheck as $fc)
		if ( empty ( $fieldname[$fc] ) )
			fatal("Nombre de campo vacio para el dato $fc.");
		else {
			// Prepare table structure
			
			$fieldname[$fc] = ereg_replace ( "[^a-zA-Z0-9$]", "", $fieldname[$fc] );
			$fieldname[$fc] = ereg_replace ( "^[0-9]+", "", $fieldname[$fc] );
			if ( empty ( $fieldname[$fc] ) )
					$fieldname[$fc] = "field" . $fc;
			
			$tbl_SQL .= $fieldname[$fc] . " varchar(250) NOT NULL,";
			
		}
	
	$tbl_SQL = rtrim($tbl_SQL, ',');
	
	//$tbl_SQL .= ") TYPE=MyISAM";  // en el servidor local marca error near  'TYPE=MyISAM';
	$tbl_SQL .= ") ENGINE=MyISAM";

	
	$fh = @fopen ($excel_file,'rb');
	if( !$fh ) fatal("No se pudo cargar el arhivo");
	if( filesize($excel_file)==0 ) fatal("No se pudo cargar el archivo");

	$fc = fread( $fh, filesize($excel_file) );
	@fclose($fh);
	if( strlen($fc) < filesize($excel_file) )
		fatal("No es posible leer el archivo");		
	
	
	$exc = new ExcelFileParser;
	$res = $exc->ParseFromString($fc);
	
	switch ($res) {
		case 0: break;
		case 1: fatal("No es posible abrir el archivo");
		case 2: fatal("El archivo npo parece ser un archivo de excel");
		case 3: fatal("Error en el encabezado");
		case 4: fatal("Error de lectura");
		case 5: fatal("Este no es un archivo de excel");
		case 6: fatal("El archivo esta corrupto");
		case 7: fatal("No se localizaron datos en el archivo");
		case 8: fatal("Version incorrecta del archivo");

		default:
			fatal("Error");
	}
	
	// Pricessing worksheets
	
	$ws_number = count($exc->worksheet['name']);
	if( $ws_number < 1 ) fatal("No hay datos en el archivo.");
	
	$ws_number = 1; // Setting to process only the first worksheet
	
	for ($ws_n = 0; $ws_n < $ws_number; $ws_n++) {
		
		$ws = $exc -> worksheet['data'][$ws_n]; // Get worksheet data
			
		$max_row = $ws['max_row'];
		$max_col = $ws['max_col'];
		
		if ( $max_row > 0 && $max_col > 0 )
			$SQL = prepareTableData ( &$exc, &$ws, $fieldcheck, $fieldname );
		else fatal("No hay datos en el archivo.");
		
	}
	
		
	if (empty ( $SQL ))
		fatal("Error de salida de datos");


	// Output data into database
	
	
	// Drop table
	
	if ($db_drop == 1) {
	
		$drop_tbl_SQL = "DROP TABLE IF EXISTS $db_table";
		
		if ( !mysql_query ($drop_tbl_SQL) )
			fatal ("Error en base de datos......");
	
	}
	
	// Create table
	
	if ( !mysql_query ($tbl_SQL)){
		mysql_query ($tbl_SQL) or die("error al crear la base de datos.....".phpmkr_error()."sql:".$tbl_SQL);
			fatal ("Error en base de datos****");
		}
																							
	
	
	$sql_pref = "INSERT INTO " . $db_table . " SET ";
	
	$err = "";	
	$nmb = 0; // Number of inserted rows
	
	foreach ( $SQL as $sql ) {
	
		if($nmb > 0){
	
			$sql = $sql_pref . $sql;
			
			if ( !mysql_query ($sql) ) {
			$err .= "<b>SQL error in</b> :<br>$sql <br>";
				
			}
		}
		
		$nmb++;
			
	}
	
	if ( empty ($err) ) {

		//Valida informacion
		$x_validado = true;
		$x_err_msg = "";		
		$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);			
		
		$sSql = "select * from $db_table ";
		$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row = phpmkr_fetch_array($rs)){

			$x_ref_pago = $row[0];
			$x_nombre_cliente = $row[1];
			$x_numero_cliente = $row[2];
			$x_importe = $row[3];
			$x_fecha_movimiento = $row[4];
																							

			if($x_ref_pago == ""){
				$x_validado = false;			
				$x_err_msg .= "dato en referencia de pago vacio.<br>";
			}
			if($x_nombre_cliente == ""){
				$x_validado = false;			
				$x_err_msg .= "dato en nombre del cliente vacio.<br>";
			}		
			if($x_numero_cliente == ""){
				$x_validado = false;			
				$x_err_msg .= "dato en numero de cliente vacio.<br>";
			}
			if (!intval($x_numero_cliente)){
				$x_validado = false;			
				$x_err_msg .= "dato en numero de cliente incorrecto.<br>";
			}
			
			if($x_importe == ""){
				$x_validado = false;			
				$x_err_msg .= "dato en importe vacio.<br>";
			}
			if (!is_numeric($x_importe)){
				$x_validado = false;			
				$x_err_msg .= "dato en importe incorrecto.<br>";
			}
						
			if($x_fecha_movimiento == ""){
				$x_validado = false;			
				$x_err_msg .= "dato en fecha movimiento vacio.<br>";
			}
			$stamp = strtotime(ConvertDateToMysqlFormat($x_fecha_movimiento));
			if (!is_numeric($stamp)){
				$x_validado = false;			
				$x_err_msg .= "dato en fecha movimiento incorrecto. $x_fecha_movimiento<br>";
			}
			$month = date( 'm', $stamp );
			$day   = date( 'd', $stamp );
			$year  = date( 'Y', $stamp );
			if (!checkdate($month, $day, $year)){
				$x_validado = false;			
				$x_err_msg .= "formato en fecha movimiento incorrecto.<br>";
			} 
			
			
			

			



			
		}			
		phpmkr_free_result($rs);			


		//insertamos en tabla masiva pagos
		if($x_validado == true){	
				
			$currentdate = getdate(time());
			$currdate = $currentdate["year"]."-".$currentdate["mon"]."-".$currentdate["mday"];
			
				
			$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
		
			// numero_de sucursal
			$x_sucursal = 1;		
			$x_file_name = $excel_file;
			$x_name = explode("/", $x_file_name);
			$x_file_n = $x_name[1];
			
			$fecha = time ();
			$x_hora = date ( "h:i:s" , $fecha );
			$x_total_registros = intval($max_row);
		
		
			$sqlFILEN = "SELECT * FROM uploaded_file WHERE nombre = \"$x_file_n\" "	;
			$responseFN = phpmkr_query($sqlFILEN, $conn) or die ("error al buscar el nombre del archivo".phpmkr_error()."sql :".$sqlFILEN);
			$numero_archivos = mysql_num_rows($responseFN);
			if($numero_archivos < 1){
			$sqlUF = "INSERT INTO uploaded_file values(0,\"$x_file_n\",\"$currdate\",$x_sucursal,\"$x_hora\",$x_total_registros)";
			phpmkr_query($sqlUF, $conn) or die ("error al insertar el archivo upload_file".phpmkr_error()."sql:".$sqlUF );
			
			$x_uploaded_file_id = mysql_insert_id();
			
			
			$sSql = "select * from $db_table ";
			$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>ERROR SQL: ' . $sSql);
			$x_counter = 1;
			
			// numero de sucursal
			
			while ($row = phpmkr_fetch_array($rs)){
				
				$x_ref_pago = ($row[0] != "") ? " '" . $row[0] . "'" : "Null";								
				$x_nombre_cliente = ($row[1] != "") ? " '" . $row[1] . "'" : "Null";								
				$x_numero_cliente = ($row[2] != "") ? intval($row[2]) : "Null";												
				$x_importe = ($row[3] != "") ? " '" . $row[3] . "'" : "Null";								
				$x_fecha_movimiento = ($row[4] != "") ? " '" . $row[4] . "'" : "Null";	
				$x_fecha_registro = "'" . ConvertDateToMysqlFormat($currdate) . "'";
				
				//Valida  
				$sSql5 = "insert into masiva_pago_2 " ;
				$sSql5 .= " values(0,$x_carga_id,$x_fecha_registro,0,$x_ref_pago,$x_nombre_cliente,$x_numero_cliente,$x_importe,$x_fecha_movimiento,\"$x_file_n\",$x_sucursal,$x_uploaded_file_id,0) ";
				phpmkr_query($sSql5,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql5);
				$x_counter = $x_counter + 1;
			
			}
			phpmkr_free_result($rs);
			//fin numero de archivos
		}else{
			// si ya se habia cargado un archivo con ese nombre.....
			//$x_validado = false;
			echo "
			<br><br>
			<div align=\"center\"><font color=\"Red\">
			<b>Operacion fallida</b></font><br><br>
			El archivo que intenta cargar ya ha sido cargado anteriormente.<br><br> ARCHIVO : $x_file_n <br><br> Por favor verifique que el archivo que intenta cargar sea correspondiente al dia de hoy<br><br>
			<br>
			<a href=\"javascript:window.close()\">Cerrar</a>
			</div>";
			
			}
		}

		$drop_tbl_SQL = "DROP TABLE IF EXISTS $db_table";
		phpmkr_query($drop_tbl_SQL,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $drop_tbl_SQL);
		phpmkr_db_close($conn);
		if(($x_validado == true) && ($numero_archivos < 1)){	
			echo "
			<br><br>
			<div align=\"center\"><font color=\"Green\">
			<b>Operacion exsitosa</b></font><br><br>
			La informaci&oacute;n ha sido cargada,  presione el siguiente boton para cerrar esta ventana.<br><br>
			<br>
			<input name=\"cerrar\" type=\"button\" onclick='closeform();' value=\"Cerrar Ventana\"/>
			</div>";
		}else{
			if(($numero_archivos > 1)){
				}else{
			echo "
			<br><br>
			<div align=\"center\"><font color=\"Red\">
			<b>Operacion Fallida</b></font><br><br>
			$x_err_msg
			<br><br>
			<br><a href=\"javascript:window.close()\">Cerrar</a>
			</div>";
				}
		}
		
	}else{
	 	echo "<br><br><font color=\"red\">$err</font><br><br><div align=\"center\"><a href=\"\">Inicio</a></div>";
	}
	
	@unlink ($excel_file);

	echo "
	<br><br>
	<div align=\"right\">
	enjoy.&nbsp;&nbsp;
	</div>";
	
}		
		
?>