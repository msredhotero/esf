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
<?php
// Initialize common variables
$x_vencimiento_id = Null; 
$ox_vencimiento_id = Null;
$x_credito_id = Null; 
$ox_credito_id = Null;
$x_vencimiento_status_id = Null; 
$ox_vencimiento_status_id = Null;
$x_fecha_vencimiento = Null; 
$ox_fecha_vencimiento = Null;
$x_importe = Null; 
$ox_importe = Null;
$x_interes = Null; 
$ox_interes = Null;
$x_interes_moratorio = Null; 
$ox_interes_moratorio = Null;

$currentdate = getdate(time());
$currdate = $currentdate["year"].$currentdate["mon"].$currentdate["mday"];	
$currdate_venc = $currentdate["year"]."-".$currentdate["mon"]."-".$currentdate["mday"];	

$temptime = strtotime($currdate_venc);
$x_fecha_extraccion = strftime('%Y%m%d',$temptime);
$x_fecha_corte = $x_fecha_extraccion;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=lista_clientes.xls');
}
if ($sExport == "word") {
	header('Content-Type: application/vnd.ms-word');
	header('Content-Disposition: attachment; filename=circulocredito.doc');
}
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
$nStartRec = 0;
$nStopRec = 0;
$nTotalRecs = 0;
$nRecCount = 0;
$nRecActual = 0;
$sKeyMaster = "";
$sDbWhereMaster = "";
$sSrchAdvanced = "";
$sDbWhereDetail = "";
$sSrchBasic = "";
$sSrchWhere = "";
$sDbWhere = "";
$sDefaultOrderBy = "";
$sDefaultFilter = "";
$sWhere = "";
$sGroupBy = "";
$sHaving = "";
$sOrderBy = "";
$sSqlMasterBase = "";
$sSqlMaster = "";
$sListTrJs = "";
$bEditRow = "";
$nEditRowCnt = "";
$sDeleteConfirmMsg = "";
$nDisplayRecs = 500;
$nRecRange = 10;

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$x_todayy = date("Y-m-d");
//$x_todayy = "2014-07-31"; 
$x_todayy = "2015-03-31"; 
$file=fopen("facturas_notas_clientes_txt/LISTADO_CLIENTES_$x_todayy.txt","w+") or die("Problemas");

$x_hoy = date("Y-m-d");
//$x_hoy = "2014-07-31";
$x_hoy = "2015-03-31";

$sqlLastDay = "SELECT LAST_DAY (\"$x_hoy\") AS ultimo_dia_mes ";
$rsLastDay = phpmkr_query($sqlLastDay,$conn) or die ("Error en dia".phpmkr_error().$sqlLastDay);
$rowLastDay =  phpmkr_fetch_array($rsLastDay);
$x_ultimo_dia_mes = $rowLastDay["ultimo_dia_mes"];


## el proceso solo se debe ejecutar el ultimo dia del mes por la noche la ultima hora de la noche
## a las 10 de la noche es buena hora

//comparamos si la fecha de hoy es igual al ultimo dia del mes sis es asi entonces ejecutamnos el proceso de  lista de clienten que tendran fcatuaras
//if($x_ultimo_dia_mes == $x_hoy){





$x_fecha_mes = explode("-",$x_ultimo_dia_mes);
$x_anio = $x_fecha_mes[0];
$x_mes = $x_fecha_mes[1];
$x_dia = "01";
$x_dia_fin = $x_fecha_mes[2];
$x_primer_dia_mes = $x_anio."-".$x_mes."-".$x_dia;

$sSql = "SELECT credito_id FROM vencimiento WHERE fecha_vencimiento >= \"$x_primer_dia_mes\"  and fecha_vencimiento <= \"$x_ultimo_dia_mes\" and credito_id not in(1489) GROUP BY credito_id ";

// Load Default Order
$sDefaultOrderBy = "";

if ($sDbWhereDetail <> "") {
	$sDbWhere .= "(" . $sDbWhereDetail . ") AND ";
}
if ($sSrchWhere <> "") {
	$sDbWhere .= "(" . $sSrchWhere . ") AND ";
}
if (strlen($sDbWhere) > 5) {
	$sDbWhere = substr($sDbWhere, 0, strlen($sDbWhere)-5); // Trim rightmost AND
}
$sWhere = "";
if ($sDefaultFilter <> "") {
	$sWhere .= "(" . $sDefaultFilter . ") AND ";
}
if ($sDbWhere <> "") {
	$sWhere .= "(" . $sDbWhere . ") AND ";
}
if (substr($sWhere, -5) == " AND ") {
	$sWhere = substr($sWhere, 0, strlen($sWhere)-5);
}
if ($sWhere != "") {
	$sSql .= " WHERE " . $sWhere;
}
if ($sGroupBy != "") {
	$sSql .= " GROUP BY " . $sGroupBy;
}
if ($sHaving != "") {
	$sSql .= " HAVING " . $sHaving;
}

// Set Up Sorting Order
$sOrderBy = "";

if ($sOrderBy != "") {
	//$sSql .= " ORDER BY vencimiento.credito_id,vencimiento.fecha_vencimiento ";
}

//echo $sSql; // Uncomment to show SQL for debugging
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>

<?php

// Set up Record Set
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$nTotalRecs = phpmkr_num_rows($rs);



$x_total = 0;
$x_total_pagos = 0;
$x_total_interes = 0;
$x_total_moratorios = 0;
$x_total_total = 0;

$x_tot_saldo_actual = 0;
$x_tot_saldo_vencido = 0;			
$x_tot_nombres_reportados = 0;
$x_tot_domicilio_reportados = 0;			
$x_tot_empleo_reportados = 0;			
$x_tot_cuenta_reportados = 0;			
$nRecActual = 0;
while (($row = @phpmkr_fetch_array($rs))) {
	

		
		
		$x_credito_id = $row["credito_id"];
		
		//echo $x_credito_id.".";
		// clientes 
		$SQLsOLICITU = "SELECT solicitud_id, iva FROM credito WHERE credito_id = $x_credito_id ";
		$rsSolictud = phpmkr_query($SQLsOLICITU,$conn)or die("Erro al seleccionar sol".phpmkr_error().$SQLsOLICITU);
		$rowSolicitud = phpmkr_fetch_array($rsSolictud);
		$x_solicitud_id = $rowSolicitud["solicitud_id"];
		$x_iva_credito = $rowSolicitud["iva"];
		
		$sqlCliente = "SELECT cliente_id from  solicitud_cliente WHERE solicitud_id = $x_solicitud_id ";
		$rsCliente = phpmkr_query($sqlCliente,$conn)or die("Error al seleccionar el cliente".phpmkr_error().$sqlCliente);
		$RowCliente = phpmkr_fetch_array($rsCliente);
		$x_cliente_id = $RowCliente["cliente_id"];
		
		
		$SQLsOLICITU = "SELECT * FROM cliente WHERE cliente_id = $x_cliente_id ";
		$rsSolictud = phpmkr_query($SQLsOLICITU,$conn)or die("Erro al seleccionar sol".phpmkr_error().$SQLsOLICITU);
		$rowSolicitud = phpmkr_fetch_array($rsSolictud);
		
		$x_nombre = $rowSolicitud["nombre_completo"];		
		$x_paterno = $rowSolicitud["apellido_paterno"];
		$x_materno = $rowSolicitud["apellido_materno"];
		$x_nombre_completo = $x_nombre." ".$x_paterno." ".$x_materno;
		//echo $x_nombre." ".$x_paterno." ".$x_materno."---";
		$x_nombre_completo = limpiatxt($x_nombre." ".$x_paterno." ".$x_materno);
		//echo $x_nombre_completo."<br>";
		#$x_cliente_id = "=CONCATENAR(REPETIR(\" \",5-LARGO($x_cliente_id)),$x_cliente_id)";		
		//$x_nombre_completo = "=CONCATENAR(\"$x_nombre_completo\",REPETIR(\" \",60-LARGO(\"$x_nombre_completo\")))";		
		//echo "credito_id".$x_credito_id." -"-  $x_iva_credito."<br>";
		if($x_iva_credito == 2){
			// se busca el rfc del cliente
			$x_rfc = $rowSolicitud["rfc"];
			$x_rfc = str_pad($x_rfc, 13, " ");

			if(empty($x_rfc)){
			$x_rfc = "";			
				#$x_rfc = "=CONCATENAR(\"$x_rfc\",REPETIR(\" \",13-LARGO(\"$x_rfc\")))";	
				$x_rfc = str_pad($x_rfc, 13, " ");	
			}
			}else{	
			
			$x_rfc = "";			
				//$x_rfc = "=CONCATENAR(\"$x_rfc\",REPETIR(\" \",13-LARGO(\"$x_rfc\")))";
				$x_rfc = str_pad($x_rfc, 13, " ");				
				}

		if(empty($x_iva_credito)){
			$x_rfc ="vacio";
			}
		
//DOM PART

			$x_clave_cliente = str_pad($x_cliente_id, 5, " ",STR_PAD_LEFT);
			$x_nombre_completo = str_pad($x_nombre_completo, 60, " ");
			
			fputs($file,$x_clave_cliente);
			fputs($file,$x_rfc);
			fputs($file,$x_nombre_completo);			
			fputs($file,"\r\n");	
			
?>
	<!-- Table body -->

<?php			
	}

?>


<?php

$sqlArchivo = "INSERT INTO `factura_archivo_txt` (`factura_archivo_txt_id`, `fecha`, `nombre`, `tipo_archivo`) VALUES (NULL, \"$x_todayy\", \"LISTADO_CLIENTES_$x_todayy.txt\", '1');";
$rsArchivo = phpmkr_query($sqlArchivo,$conn)or die ("Error al insertar los archivos".phpmkr_error()."sql:".$sqlArchivo);




// Close recordset and connection
phpmkr_free_result($rs);
phpmkr_db_close($conn);


function limpiatxt($x_txt){

$x_txt = str_replace("."," ",$x_txt);
$x_txt = str_replace(","," ",$x_txt);
$x_txt = str_replace("/"," ",$x_txt);
$x_txt = str_replace("#"," ",$x_txt);
$x_txt = str_replace("&"," ",$x_txt);
$x_txt = str_replace("$"," ",$x_txt);
$x_txt = str_replace(":"," ",$x_txt);
$x_txt = str_replace("("," ",$x_txt);
$x_txt = str_replace(")"," ",$x_txt);
$x_txt = str_replace("-"," ",$x_txt);
$x_txt = str_replace("á","a",$x_txt);
$x_txt = str_replace("é","e",$x_txt);
$x_txt = str_replace("í","i",$x_txt);
$x_txt = str_replace("ó","o",$x_txt);
$x_txt = str_replace("ú","u",$x_txt);
$x_txt = str_replace("Á","A",$x_txt);
$x_txt = str_replace("É","E",$x_txt);
$x_txt = str_replace("Í","I",$x_txt);
$x_txt = str_replace("Ó","O",$x_txt);
$x_txt = str_replace("Ú","U",$x_txt);


$x_txt = str_replace("Ã¡","a",$x_txt);
$x_txt = str_replace("Ã©","e",$x_txt);
$x_txt = str_replace("Ã­","i",$x_txt);
$x_txt = str_replace("Ã³","o",$x_txt);
$x_txt = str_replace("Ãº","u",$x_txt);
$x_txt = str_replace("Ã±","ñ",$x_txt);


return $x_txt;
 
}
fclose($file);


//}// el dia de hoy era el ultimo dia del mes; el proceso  se ejecuto por hoy

?>
<?php if ($sExport <> "word" && $sExport <> "excel") { ?>
</body>
</html>
<?php } ?>
