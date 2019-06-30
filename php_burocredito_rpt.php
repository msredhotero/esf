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
	header('Content-Disposition: attachment; filename=burocredito.xls');
}
if ($sExport == "word") {
	header('Content-Type: application/vnd.ms-word');
	header('Content-Disposition: attachment; filename=circulocredito.doc');
}
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php include ("utilerias/datefunc.php") ?>
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

// Build WHERE condition
$x_fecha_desde = @$_GET["key1"];
$x_fecha_hasta = @$_GET["key2"];
if (($x_fecha_desde == "") || ((is_null($x_fecha_desde)))) {
	$x_fecha_desde = @$_POST["x_fecha_desde"];
	$x_fecha_hasta = @$_POST["x_fecha_hasta"];
	if (($x_fecha_desde == "") || ((is_null($x_fecha_desde)))) {	
		header("Location:  php_burocredito_list.php");
		exit();
	}
}



// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);


// Build SQL
$sSql = "SELECT * FROM `vencimiento` join credito on credito.credito_id = vencimiento.credito_id ";

// Load Default Filter
$sDefaultFilter = "";
$sGroupBy = "";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = "";

$sDbWhere = "(vencimiento.fecha_vencimiento >= '".ConvertDateToMysqlFormat($x_fecha_desde)."') AND ";
$sDbWhere .= "(vencimiento.fecha_vencimiento <= '".ConvertDateToMysqlFormat($x_fecha_hasta)."') AND ";
$sDbWhere .= "(credito.credito_tipo_id = 1) AND ";


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
	$sSql .= " ORDER BY vencimiento.fecha_vencimiento ";
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
<script type="text/javascript">
<!--
var firstrowoffset = 1; // first data row start at
var tablename = 'ewlistmain'; // table name
var lastrowoffset = 0; // footer row
var usecss = true; // use css
var rowclass = 'ewTableRow'; // row class
var rowaltclass = 'ewTableAltRow'; // row alternate class
var rowmoverclass = 'ewTableHighlightRow'; // row mouse over class
var rowselectedclass = 'ewTableSelectRow'; // row selected class
var roweditclass = 'ewTableEditRow'; // row edit class
var rowcolor = '#FFFFFF'; // row color
var rowaltcolor = '#F5F5F5'; // row alternate color
var rowmovercolor = '#FFCCFF'; // row mouse over color
var rowselectedcolor = '#CCFFFF'; // row selected color
var roweditcolor = '#FFFF99'; // row edit color

//-->
</script>
<?php

// Set up Record Set
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$nTotalRecs = phpmkr_num_rows($rs);
if ($nDisplayRecs <= 0) { // Display All Records
	$nDisplayRecs = $nTotalRecs;
}
$nStartRec = 1;

?>
<?php if ($sExport <> "word" && $sExport <> "excel") { ?>
<a href="php_burocredito_list.php">Regresar</a><br /><br />
<?php } ?>

<?php if ($nTotalRecs > 0)  { ?>
<form method="post" name="principal" action="php_burocredito_rpt.php">
<input type="hidden" name="credito_id" value="<?php echo $x_credito_id; ?>"  />

<?php if ($sExport == "") { ?>
<br /><br />
<?php } ?>
<?php

// Avoid starting record > total records
if ($nStartRec > $nTotalRecs) {
	$nStartRec = $nTotalRecs;
}

// Set the last record to display
$nStopRec = $nStartRec + $nDisplayRecs - 1;

// Move to first record directly for performance reason
$nRecCount = $nStartRec - 1;
if (phpmkr_num_rows($rs) > 0) {
	phpmkr_data_seek($rs, $nStartRec -1);
}

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


//DDMMAAAA
$x_fecha_reporte_dia = $currentdate["mday"];	
if(strlen($x_fecha_reporte_dia) == 1){
	$x_fecha_reporte_dia = "0".$x_fecha_reporte_dia;
}
$x_fecha_reporte_mes = $currentdate["mon"];	
if(strlen($x_fecha_reporte_mes) == 1){
	$x_fecha_reporte_mes = "0".$x_fecha_reporte_mes;
}
$x_fecha_reporte_year = $currentdate["year"];	

$x_fecha_reporte = $x_fecha_reporte_dia . $x_fecha_reporte_mes . $x_fecha_reporte_year;


//Abrir Nuevo archivo
$x_arch = "bctmp/reporte_buro_credito".$x_fecha_reporte.".txt";
$fp = fopen($x_arch, 'w+');
if(!$fp) {
	echo "Error al abrir archivo.";
	exit();
}

/*
encabezado  INTF

01 etiqueta del segumento INTF
05 version 2 (fijo 10)
07 clave del usuario 10
17 nombre del usuario 16
33 numero de ciclo 2 (espacios)
35 fecha del reporte 8
43 uso futuro 10 (llenar con 0)
53 informacion adicional del usuario 98 (espacios)
Ejemplo:
INTF10ZZ99990001BANCO LATINO 01310819980000000000
INTF10SS10340001PRUEBA            170820090000000000

claves de prueba
clave = SS10340001
nombre = PRUEBA
aqui
*/

$x_clave_otorgante = "SS10340001";

$x_nombre_otorgante = "PRUEBA";
$x_dif = (16 - strlen($x_nombre_otorgante));
$x_nombre_otorgante = $x_nombre_otorgante.str_repeat(" ",$x_dif);

$x_espacios = str_repeat("0",98);
$x_enc = "INTF10".$x_clave_otorgante.$x_nombre_otorgante."  ".$x_fecha_reporte."0000000000".str_repeat("0",98);
fwrite($fp,$x_enc);


$nRecActual = 0;
while (($row = @phpmkr_fetch_array($rs)) && ($nRecCount < $nStopRec)) {
	$nRecCount = $nRecCount + 1;
	if ($nRecCount >= $nStartRec) {
		$nRecActual++;

		// Set row color
		$sItemRowClass = " class=\"ewTableRow\"";
		$sListTrJs = " ";

		// Display alternate color for rows
		if ($nRecCount % 2 <> 1) {
			$sItemRowClass = " class=\"ewTableAltRow\"";
		}
		$x_vencimiento_id = $row["vencimiento_id"];
		$x_vencimiento_num = $row["vencimiento_num"];		
		$x_credito_id = $row["credito_id"];
		$x_vencimiento_status_id = $row["vencimiento_status_id"];
		if(($x_vencimiento_status_id == 2) || ($x_vencimiento_status_id == 5)){
		
			$sSqlWrk = "SELECT fecha_pago FROM recibo join recibo_vencimiento on recibo_vencimiento.recibo_id = recibo.recibo_id where recibo_vencimiento.vencimiento_id = $x_vencimiento_id";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$rowwrk = phpmkr_fetch_array($rswrk);
			$x_fecha_pago = $rowwrk["fecha_pago"];

			@phpmkr_free_result($rswrk);

		}else{
			$x_fecha_pago  = "";
		}
		

		
		$x_fecha_otorgamiento = $row["fecha_otorgamiento"];

		$temptime = strtotime($x_fecha_otorgamiento);
		$x_fecha_otorgamiento = strftime('%d%m%Y',$temptime);

		$x_fecha_vencimiento = $row["fecha_vencimiento"];		
		$x_importe = $row["importe"];
		$x_interes = $row["interes"];
		$x_interes_moratorio = $row["interes_moratorio"];
		
		$x_total = $x_importe + $x_interes + $x_interes_moratorio;

		$x_limite_credito = $x_importe;
		$x_limite_credito = FormatNumber($x_limite_credito,0,0,0,0);
		$x_total_pagos = $x_total_pagos + $x_importe;
		$x_total_interes = $x_total_interes + $x_interes;
		$x_total_moratorios = $x_total_moratorios + $x_interes_moratorio;
		$x_total_total = $x_total_total + $x_total;
		
		
//DATOS GRALS DEL CLIENTE

		$sSqlWrk = "SELECT * FROM 
		credito join solicitud
		on solicitud.solicitud_id = credito.solicitud_id join solicitud_cliente
		on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente
		on cliente.cliente_id = solicitud_cliente.cliente_id 
		where credito.credito_id = $x_credito_id and solicitud.credito_tipo_id = 1";
		//echo $sSqlWrk;
		$rswrk_cli = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk_cli);
		$x_cliente_id = $datawrk["cliente_id"];				
		$x_apellido_paterno = $datawrk["apellido_paterno"];
		$x_apellido_materno = $datawrk["apellido_materno"];		
		$x_nombre = $datawrk["nombre_completo"];		
		$x_fecha_nacimiento = $datawrk["fecha_nac"];	//falta
		
		$temptime = strtotime($x_fecha_nacimiento);
		$x_fecha_nacimiento = strftime('%d%m%Y',$temptime);
		
		
		$x_rfc = $datawrk["rfc"];		
		$x_curp = $datawrk["curp"];						
		$x_nacionalidad_id = $datawrk["nacionalidad_id"];	//falta
		$x_estado_civil_id = $datawrk["estado_civil_id"];				
		if($datawrk["sexo"] == 1){$x_sexo = "M";}else{$x_sexo = "F";}						
		$x_ife = $datawrk["ife"];	//falta		
		$x_dependientes = $datawrk["numero_hijos_dep"];	//falta				
		$x_empresa = $datawrk["empresa"];
		$x_empresa_puesto = $datawrk["puesto"];
		$x_empresa_salario_mensual = $datawrk["salario_mensual"];		
		$x_empresa_clave_moneda = $datawrk["clave_moneda"];				
		$x_empresa_fecha_contratacion = $datawrk["fecha_contratacion"];		

		$temptime = strtotime($x_empresa_fecha_contratacion);
		$x_empresa_fecha_contratacion = strftime('%Y%m%d',$temptime);
		
		$x_fecha_solicitud = $datawrk["fecha_registro"];		

		$temptime = strtotime($x_fecha_solicitud);
		$x_fecha_solicitud = strftime('%Y%m%d',$temptime);
		
		$x_credito_num = $datawrk["credito_num"];		
		$x_num_pagos = $datawrk["num_pagos"];				
		$x_solicitud_id = $datawrk["solicitud_id"];				
		$x_forma_pago_id = $datawrk["forma_pago_id"];						
		$x_fecha_credito = $datawrk["fecha_otrogamiento"];				

		$temptime = strtotime($x_fecha_credito);
		$x_fecha_credito = strftime('%Y%m%d',$temptime);
		
		$x_cliente_num = $datawrk["cliente_num"];		
		$x_fecha_finiquito_credito = "";		
		
		if($datawrk["credito_status_id"] == 3){
			$x_fecha_finiquito_credito = $datawrk["fecha_vencimiento"];
			$temptime = strtotime($x_fecha_finiquito_credito);
			$x_fecha_finiquito_credito = strftime('%Y%m%d',$temptime);		
		}
		@phpmkr_free_result($rswrk_cli);

		if($x_estado_civil_id  > 0 ){
/*		
			$sSqlWrk = "SELECT clave FROM estado_civil where estado_civil_id = $x_estado_civil_id";
				//echo $sSqlWrk;
				$rswrk_cli = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk_cli);
				$x_estado_civil = $datawrk["clave"];
				@phpmkr_free_result($rswrk_cli);
*/
			switch ($x_estado_civil_id)
			{
				case "1": // Get a record to display
					$x_estado_civil = "S";
					break;
				case "2": // Get a record to display
					$x_estado_civil = "C";
					break;
				case "3": // Get a record to display
					$x_estado_civil = "L";
					break;
				case "4": // Get a record to display
					$x_estado_civil = "D";
					break;
				case "6": // Get a record to display
					$x_estado_civil = "V";
					break;
			}
				
		}else{
			$x_estado_civil = "No definidio";
		}
		

//Nacionalidad

		if($x_nacionalidad_id > 0 ){
			$sSqlWrk = "SELECT clave FROM nacionalidad where nacionalidad_id = $x_nacionalidad_id";
				//echo $sSqlWrk;
				$rswrk_cli = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk_cli);
				$x_nacionalidad = $datawrk["clave"];
				@phpmkr_free_result($rswrk_cli);
		}else{
			$x_nacionalidad = " ";
		}

		
		
//DOM PART

		if($x_cliente_id > 0 ){
			$sSqlWrk = "SELECT * from direccion where cliente_id = $x_cliente_id and direccion_tipo_id = 1";
			//echo $sSqlWrk;
			$rswrk_cli = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk_cli);
			$x_direccion = $datawrk["calle"];				
			$x_colonia = $datawrk["colonia"];							
			$x_delegacion_id = $datawrk["delegacion_id"]; //cat cc			
			$x_ciudad = $datawrk["ciudad"]; 
			$x_estado_id = $datawrk["estado_id"]; //cat cc						
			$x_codigo_postal = $datawrk["codigo_postal"];							
//			$x_codigo_postal = "=TEXT($x_codigo_postal,\"00000\")";
			
			
			$x_fecha_residencia = $datawrk["fecha_residencia"]; //falta			

			$temptime = strtotime($x_fecha_residencia);
			$x_fecha_residencia = strftime('%Y%m%d',$temptime);
			
			$x_telefono = $datawrk["telefono"]; 	
			$x_telefono = str_replace("-","",$x_telefono);
			$x_telefono = str_replace(".","",$x_telefono);
			$x_telefono = str_replace("(","",$x_telefono);
			$x_telefono = str_replace(")","",$x_telefono);
			/*
			if(!is_integer($x_telefono)){
				$x_telefono = "";
			}
			*/
			$x_residencia = $datawrk["vivienda_tipo_id"];
			$x_asentamiento_tipo_id = $datawrk["asentamiento_tipo_id"]; //cat cc			
			@phpmkr_free_result($rswrk_cli);
		}		


		if($x_delegacion_id  > 0 ){
			$sSqlWrk = "SELECT descripcion, entidad_id FROM delegacion where delegacion_id = $x_delegacion_id";
				//echo $sSqlWrk;
				$rswrk_cli = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk_cli);
				$x_delegacion = $datawrk["descripcion"];
				$x_entidad_id = $datawrk["entidad_id"];				
				@phpmkr_free_result($rswrk_cli);
		}else{
			$x_delegacion = "";
		}

		if($x_entidad_id  > 0 ){
			$sSqlWrk = "SELECT clave FROM entidad where entidad_id = $x_entidad_id";
				//echo $sSqlWrk;
				$rswrk_cli = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk_cli);
				$x_estado = $datawrk["clave"];
				@phpmkr_free_result($rswrk_cli);
		}else{
			$x_estado = "";
		}



		
		if($x_asentamiento_tipo_id  > 0 ){
			$sSqlWrk = "SELECT valor_cc FROM asentamiento_tipo where asentamiento_tipo_id = $x_asentamiento_tipo_id";
				//echo $sSqlWrk;
				$rswrk_cli = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk_cli);
				$x_asentamiento_tipo_id = $datawrk["valor_cc"];
				@phpmkr_free_result($rswrk_cli);
		}else{
			$x_asentamiento_tipo_id = "";
		}
		
		
//DOM EMPRESA

		if($x_cliente_id > 0 ){
			$sSqlWrk = "SELECT * from direccion where cliente_id = $x_cliente_id and direccion_tipo_id = 2";
			//echo $sSqlWrk;
			$rswrk_cli = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk_cli);
			$x_empresa_direccion = $datawrk["calle"];				
			$x_empresa_colonia = $datawrk["colonia"];							
			$x_empresa_delegacion_id = $datawrk["delegacion_id"]; //cat cc			
			$x_empresa_ciudad = $datawrk["ciudad"]; 
			$x_empresa_estado_id = $datawrk["estado_id"]; //cat cc						
			$x_empresa_codigo_postal = $datawrk["codigo_postal"];							

			$x_empresa_telefono = $datawrk["telefono"]; 	
			$x_empresa_telefono = str_replace("-","",$x_empresa_telefono);
			$x_empresa_telefono = str_replace(".","",$x_empresa_telefono);
			$x_empresa_telefono = str_replace("(","",$x_empresa_telefono);
			$x_empresa_telefono = str_replace(")","",$x_empresa_telefono);
/*
			if(!is_integer($x_empresa_telefono)){
				$x_empresa_telefono = "";
			}
*/
			
			$x_empresa_fax = $datawrk["fax"];
			$x_empresa_extension = $datawrk["extension"];			

			@phpmkr_free_result($rswrk_cli);
		}		
		
		if($x_empresa_delegacion_id  > 0 ){
			$sSqlWrk = "SELECT descripcion, entidad_id FROM delegacion where delegacion_id = $x_empresa_delegacion_id";
				//echo $sSqlWrk;
				$rswrk_cli = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk_cli);
				$x_empresa_delegacion = $datawrk["descripcion"];
				$x_empresa_entidad_id = $datawrk["entidad_id"];				
				@phpmkr_free_result($rswrk_cli);
		}else{
			$x_empresa_delegacion = "";
		}

		if($x_empresa_entidad_id  > 0 ){
			$sSqlWrk = "SELECT clave FROM entidad where entidad_id = $x_empresa_entidad_id";
				//echo $sSqlWrk;
				$rswrk_cli = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk_cli);
				$x_empresa_estado = $datawrk["clave"];
				@phpmkr_free_result($rswrk_cli);
		}else{
			$x_empresa_estado = "";
		}



//DATOS DE LA CUENTA
			$sSqlWrk = "SELECT count(*) as responsabilidad FROM aval where solicitud_id = $x_solicitud_id";
			//echo $sSqlWrk;
			$rswrk_cli = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk_cli);
			$x_responsabilidad = $datawrk["responsabilidad"];
			@phpmkr_free_result($rswrk_cli);

			if($x_responsabilidad == 0){
				$x_tipo_responsabilidad	= "I";				
			}else{
				$x_tipo_responsabilidad	= "T";								
			}



			/*
			F	Pagos Fijos
			H	Hipoteca
			L	Sin Límite Preestablecido
			R	Revolvente
			M	Monitoreo
			*/		
			$x_tipo_cuenta = "F";	
			$x_tipo_contrato = "PP";	
			$x_unidad_monetaria = "MX";
			

			$sSqlWrk = "SELECT * FROM garantia where solicitud_id = $x_solicitud_id";
			//echo $sSqlWrk;
			$rswrk_cli = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk_cli);
			$x_garantia_descripcion = $datawrk["descripcion"];
			$x_garantia_valor = $datawrk["valor"];			
			@phpmkr_free_result($rswrk_cli);



			switch ($x_forma_pago_id)
			{
				case "1": // semanal
					$x_frecuencia_pagos = "W";
					break;
				case "2": // catorcenal
					$x_frecuencia_pagos = "K";
					break;
				case "3": // Mensual
					$x_frecuencia_pagos = "M";
					break;
				case "4": // Quincenal
					$x_frecuencia_pagos = "S";
					break;
			}
/*
	B = bimestral
	D = Diario
	H = Semestral
	K = Catorcenal
	M = Mesual
	P = deduccion de salario
	Q = Trimestral
	S = Quincenal
	V = Variable
	W = semanal
	Y = Anual
	Z = Pago minimo
*/
/*

			$sSqlWrk = "SELECT valor_cc FROM forma_pago where forma_pago_id = $x_forma_pago_id";
			//echo $sSqlWrk;
			$rswrk_cli = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk_cli);
			$x_frecuencia_pagos = $datawrk["valor_cc"];
			@phpmkr_free_result($rswrk_cli);
*/			
			$x_monto_pagar = FormatNumber(intval($x_total),0,0,0,0);

			$sSqlWrk = "SELECT max(fecha_pago) as ultimo_pago FROM vencimiento join recibo_vencimiento
			on recibo_vencimiento.vencimiento_id = vencimiento.vencimiento_id join recibo
			on recibo.recibo_id = recibo_vencimiento.recibo_id
			where recibo_status_id = 1 and vencimiento.credito_id = $x_credito_id ";
			//echo $sSqlWrk;
			$rswrk_cli = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk_cli);
			$x_fecha_ultimo_pago = $datawrk["ultimo_pago"];

			$temptime = strtotime($x_fecha_ultimo_pago);
			$x_fecha_ultimo_pago = strftime('%d%m%Y',$temptime);

			@phpmkr_free_result($rswrk_cli);
							

			$sSqlWrk = "SELECT max(importe) as credito_maximo FROM credito where cliente_num = '$x_cliente_num' and credito_status_id not in (3)";
			//echo $sSqlWrk;
			$rswrk_cli = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk_cli);
			$x_credito_maximo = $datawrk["credito_maximo"];
			$x_credito_maximo = FormatNumber($x_credito_maximo,0,0,0,0);
			@phpmkr_free_result($rswrk_cli);


			$sSqlWrk = "SELECT count(*) as numero_pagos FROM vencimiento where credito_id = $x_credito_id order by vencimiento_id";
			//echo $sSqlWrk;
			$rswrk_cli = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk_cli);
			$x_numero_pagos = $datawrk["numero_pagos"];
			@phpmkr_free_result($rswrk_cli);




			$sSqlWrk = "SELECT * FROM vencimiento where credito_id = $x_credito_id order by vencimiento_id";
			$rswrk_cli = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$x_tot_imp = 0;							
			$x_tot_imp_pagos = 0;				
			$x_tot_imp_pagos_pen = 0;								
			$x_tot_pagos_ven = 0;
			$x_tot_imp_pagos_ven = 0;		
			
			$x_fecha_ultimo_vencido = "";
			
			while($datawrk = phpmkr_fetch_array($rswrk_cli)){
				$x_tot_imp = $x_tot_imp  + $datawrk["total_venc"];
				if($datawrk["vencimiento_status_id"] == 1){
					$x_tot_imp_pagos_pen = $x_tot_imp_pagos_pen + $datawrk["total_venc"];			
				}
				if($datawrk["vencimiento_status_id"] == 3){
					$x_tot_pagos_ven = $x_tot_pagos_ven + 1;
					$x_tot_imp_pagos_ven = $x_tot_imp_pagos_ven + $datawrk["total_venc"];			
					$x_fecha_ultimo_vencido = $datawrk["fecha_vencncimiento"];					
				}
				if($datawrk["vencimiento_status_id"] == 2){
					$x_tot_imp_pagos = $x_tot_imp_pagos + $datawrk["total_venc"];		
				}
				if($datawrk["vencimiento_status_id"] == 5){
					$x_tot_imp_pagos = $x_tot_imp_pagos + $datawrk["total_venc"];						
				}
				if(($datawrk["vencimiento_status_id"] == 1) || ($datawrk["vencimiento_status_id"] == 3) && ($datawrk["fecha_vencimiento"] < ConvertDateToMysqlFormat($currdate_venc))){
					$x_importe_venc = $datawrk["total_venc"];
				}
			}
			@phpmkr_free_result($rswrk_cli);

			$x_num_pagos_venc = $x_tot_pagos_ven;
			$x_saldo_vencido = $x_tot_imp_pagos_ven;
			$x_saldo_actual =  $x_tot_imp - $x_tot_imp_pagos;



//TOTALES			
			$x_tot_saldo_actual = $x_tot_saldo_actual + $x_saldo_actual;
			$x_tot_saldo_vencido = $x_tot_saldo_vencido + $x_saldo_vencido;			
			$x_tot_nombres_reportados = $nRecCount;
			$x_tot_domicilio_reportados = $nRecCount;			
			if($x_empresa != ""){
				$x_tot_empleo_reportados = $x_tot_empleo_reportados + 1;			
			}
			$x_tot_cuenta_reportados = $nRecCount;		




$x_lin = "";
/* Armado buro de credito 
ETIQUETA SEG + LONG CAMPO + ETQ DE DATO + LONG CAMPO + DATO

EJEMPO SEG NOMBRE
PN06MENDEZ0008GONZALES0208ANTUANET0513MEGA510503RE3
*/


//etiquitas
/*
encabezado INTF 150 fijo
SEG NOMBRE PN 375 MAX
SEG DIRECCION PA 326 MAX
SEG CUENTA TL 436 MAX
SEG CIERRE TR 253 FIJO
*/

/*segumento nombre
PN apellido_paterno 26
00 apellido_materno 26 opc NO PROPORCIONADO
02 nombre 26
04 fecha_nac 8 ddmmaaaa
05 rfc 13
*/

$x_pref = "PN";
$x_lon = strlen($x_apellido_paterno);
if($x_lon > 26){
	$x_dato = strtoupper(substr($x_apellido_paterno,0,25));	
	$x_lon = 26;
}else{
	$x_dato = strtoupper($x_apellido_paterno);
}

if(strlen($x_lon) < 2){
	$x_lon = "0".$x_lon;
}

$x_lin .= $x_pref.$x_lon.$x_dato;

$x_pref = "00";
$x_lon = strlen($apellido_materno);
if($x_lon > 26){
	$x_dato = strtoupper(substr($x_apellido_materno,0,25));	
	$x_lon = 26;
}else{
	if($x_lon > 0 ){
		$x_dato = strtoupper($x_apellido_materno);
	}else{
		$x_dato = "NO PROPORCIONADO";		
		$x_lon = 16;		
	}
}

if(strlen($x_lon) < 2){
	$x_lon = "0".$x_lon;
}

$x_lin .= $x_pref.$x_lon.$x_dato;

$x_pref = "02";
$x_lon = strlen($x_nombre);
if($x_lon > 26){
	$x_dato = strtoupper(substr($x_nombre,0,25));	
	$x_lon = 26;
}else{
	$x_dato = strtoupper($x_nombre);
}

if(strlen($x_lon) < 2){
	$x_lon = "0".$x_lon;
}

$x_lin .= $x_pref.$x_lon.$x_dato;

$x_pref = "04";
$x_lon = "08";
if(strlen($x_fecha_nacimiento) != 8){
	$x_fecha_nacimiento	= "00000000";
}

$x_lin .= $x_pref.$x_lon.$x_dato;

$x_pref = "05";
$x_lon = strlen($x_rfc);
if($x_lon != 13){
	if($x_lon > 13){
		$x_dato = strtoupper(substr($x_rfc,0,12));	
	}else{
		$x_dif = 13 - $x_lon;
		$x_dato = strtoupper($x_rfc . str_repeat("0",$x_dif));
	}
	$x_lon = 13;	
}else{
	$x_dato = strtoupper($x_rfc);
}

if(strlen($x_lon) < 2){
	$x_lon = "0".$x_lon;
}

$x_lin .= $x_pref.$x_lon.$x_dato;


/*segumento direccion
PA direccion1 40
00 direccion2 40 opc
01 colonia 40
02 del_mun 40
03 ciudad 40
04 estado 4 cat
05 cp 5
*/

$x_pref = "PA";
$x_lon = strlen($x_direccion);
if($x_lon > 40){
	$x_dato = strtoupper(substr($x_direccion,0,39));	
	$x_lon = 40;
}else{
	$x_dato = strtoupper($x_direccion);
}

if(strlen($x_lon) < 2){
	$x_lon = "0".$x_lon;
}

$x_lin .= $x_pref.$x_lon.$x_dato;

$x_pref = "01";
$x_lon = strlen($x_colonia);
if($x_lon > 40){
	$x_dato = strtoupper(substr($x_colonia,0,39));	
	$x_lon = 40;
}else{
	$x_dato = strtoupper($x_colonia);
}

if(strlen($x_lon) < 2){
	$x_lon = "0".$x_lon;
}

$x_lin .= $x_pref.$x_lon.$x_dato;

$x_pref = "02";
$x_lon = strlen($x_delegacion);
if($x_lon > 40){
	$x_dato = strtoupper(substr($x_delegacion,0,39));	
	$x_lon = 40;
}else{
	$x_dato = strtoupper($x_delegacion);
}

if(strlen($x_lon) < 2){
	$x_lon = "0".$x_lon;
}

$x_lin .= $x_pref.$x_lon.$x_dato;

$x_pref = "03";
$x_lon = strlen($x_ciudad);
if($x_lon > 40){
	$x_dato = strtoupper(substr($x_ciudad,0,39));	
	$x_lon = 40;
}else{
	$x_dato = strtoupper($x_ciudad);
}

if(strlen($x_lon) < 2){
	$x_lon = "0".$x_lon;
}

$x_lin .= $x_pref.$x_lon.$x_dato;

$x_pref = "04";
$x_lon = strlen($x_estado);
if($x_lon > 4){
	$x_dato = strtoupper(substr($x_estado,0,3));	
	$x_lon = 4;
}else{
	$x_dato = strtoupper($x_estado);
}

if(strlen($x_lon) < 2){
	$x_lon = "0".$x_lon;
}

$x_lin .= $x_pref.$x_lon.$x_dato;

$x_pref = "05";
$x_lon = strlen($x_codigo_postal);
if($x_lon > 5){
	$x_dato = substr($x_codigo_postal,0,3);	
	$x_lon = 4;
}else{
	if($x_lon < 5){	
		$x_dif = 5 - $x_lin;
		$x_dato = str_repeat("0",$x_dif).$x_codigo_postal;
	}else{
		$x_dato = $x_codigo_postal;		
	}
}

if(strlen($x_lon) < 2){
	$x_lon = "0".$x_lon;
}

$x_lin .= $x_pref.$x_lon.$x_dato;


/*segumento credito
TL 
01 cve_otorgante 10
02 nombre_usuario 16
04 numero_cuenta_actual 25 (numero de credito 4 caract como minimo)
06 tipo_cuenta 1 cat 
	I = pagos fijos
	M = Hipoteca
	O = sin limite pre establecido
	R = revolvente
07 tipo_contrato 2 cat
	PA = prestamo personas fisicas act empresartial
	PL = prestamo personal
08 cve_unidad_monetaria MX
10 numero_pagos 4
11 fecuencia_pago 1 cat
	B = bimestral
	D = Diario
	H = Semestral
	K = Catorcenal
	M = Mesual
	P = deduccion de salario
	Q = Trimestral
	S = Quincenal
	V = Variable
	W = semanal
	Y = Anual
	Z = Pago minimo

12 monto_pagar 9
13 fecha_apertura_cuenta 8
14 FECHA_ULTIMO_PAGO 8
15 fecha_ultima_compra 8 (ultimo credito)
21 credito_maximo 9 (importe mas alto de creditos del cliente)
23 limite_credito 9 (importe del credito) opc
26 forma_pago 2 cat
	00 = muy reciente para ser informada
	01 = cuenta al corriente
	02 = atraso 01 a 29 dias
	03 = atraso 30 a 59 dias
	04 = atraso 60 a 89
	05 = atraso 90 a 119
	06 = atraso 120 a 149
	07 = atraso 150 a 12 meses
	96 = atraso 12 meses
	97 = deuda parcial o total sin recuperar
	99 = fraude cometido por el consumidor
99 FIN DE SEG 3 (DEBE DE SER "FIN")
*/


$x_pref = "TL";
$x_lon = strlen($x_nombre_otorgante);
if($x_lon > 16){
	$x_dato = strtoupper(substr($x_nombre_otorgante,0,15));	
	$x_lon = 16;
}else{
	$x_dato = strtoupper($x_nombre_otorgante);
}

if(strlen($x_lon) < 2){
	$x_lon = "0".$x_lon;
}

$x_lin .= $x_pref.$x_lon.$x_dato;

$x_pref = "04";
$x_lon = strlen($x_credito_num);
if($x_lon > 25){
	$x_dato = strtoupper(substr($x_credito_num,0,24));	
	$x_lon = 25;
}else{
	$x_dato = strtoupper($x_credito_num);
}

if(strlen($x_lon) < 2){
	$x_lon = "0".$x_lon;
}

$x_lin .= $x_pref.$x_lon.$x_dato;

$x_pref = "06";
$x_lon = "01";
$x_dato = "I";

$x_lin .= $x_pref.$x_lon.$x_dato;

$x_pref = "07";
$x_lon = "02";
$x_dato = "PL";

$x_lin .= $x_pref.$x_lon.$x_dato;

$x_pref = "08";
$x_lon = "02";
$x_dato = "MX";

$x_lin .= $x_pref.$x_lon.$x_dato;

$x_pref = "10";
$x_lon = strlen($x_numero_pagos);
if($x_lon > 4){
	$x_dato = strtoupper(substr($x_numero_pagos,0,3));	
	$x_lon = 4;
}else{
	if($x_lon < 4){
		$x_dif = (4 - strlen($x_numero_pagos)); 
		$x_dato = str_repeat("0",$x_dif).$x_numero_pagos;		
	}else{
		$x_dato = strtoupper($x_numero_pagos);
	}
	$x_lon = 4;	
}

if(strlen($x_lon) < 2){
	$x_lon = "0".$x_lon;
}

$x_lin .= $x_pref.$x_lon.$x_dato;

$x_pref = "11";
$x_lon = strlen($x_frecuencia_pagos);
if($x_lon > 1){
	$x_dato = strtoupper(substr($x_frecuencia_pagos,0,1));	
	$x_lon = 1;
}else{
	$x_dato = strtoupper($x_frecuencia_pagos);
}

if(strlen($x_lon) < 2){
	$x_lon = "0".$x_lon;
}

$x_lin .= $x_pref.$x_lon.$x_dato;

$x_pref = "12";
$x_lon = strlen($x_monto_pagar);
if($x_lon > 9){
	$x_dato = strtoupper(substr($x_monto_pagar,0,8));	
	$x_lon = 9;
}else{
	if($x_lon < 9){	
		$x_dif = (9 - strlen($x_monto_pagar)); 
		$x_dato = str_repeat("0",$x_dif).$x_monto_pagar;		
	}else{
		$x_dato = $x_monto_pagar;
	}
	$x_lon = 9;	
}

if(strlen($x_lon) < 2){
	$x_lon = "0".$x_lon;
}

$x_lin .= $x_pref.$x_lon.$x_dato;

$x_pref = "13";
$x_lon = strlen($x_fecha_otorgamiento);
if($x_lon > 8){
	$x_dato = strtoupper(substr($x_fecha_otorgamiento,0,7));	
	$x_lon = 8;
}else{
	$x_dato = strtoupper($x_fecha_otorgamiento);
}

if(strlen($x_lon) < 2){
	$x_lon = "0".$x_lon;
}

$x_lin .= $x_pref.$x_lon.$x_dato;

$x_pref = "14";
$x_lon = strlen($x_fecha_ultimo_pago);
if($x_lon > 8){
	$x_dato = strtoupper(substr($x_fecha_ultimo_pago,0,7));	
	$x_lon = 8;
}else{
	$x_dato = strtoupper($x_fecha_ultimo_pago);
}

if(strlen($x_lon) < 2){
	$x_lon = "0".$x_lon;
}

$x_lin .= $x_pref.$x_lon.$x_dato;

$x_pref = "21";
$x_lon = strlen($x_credito_maximo);
if($x_lon > 9){
	$x_dato = strtoupper(substr($x_credito_maximo,0,8));	
	$x_lon = 9;
}else{
	if($x_lon < 9){	
		$x_dif = (9 - strlen($x_credito_maximo));
		$x_dato = str_repeat("0",$x_dif).$x_credito_maximo;
	}else{
		$x_dato = $x_credito_maximo;		
	}
	$x_lon = 9;	
}

if(strlen($x_lon) < 2){
	$x_lon = "0".$x_lon;
}

$x_lin .= $x_pref.$x_lon.$x_dato;

$x_pref = "23";
$x_lon = strlen($x_limite_credito);
if($x_lon > 9){
	$x_dato = strtoupper(substr($x_limite_credito,0,8));	
	$x_lon = 9;
}else{
	if($x_lon < 9){	
		$x_dif = (9 - strlen($x_limite_credito));
		$x_dato = str_repeat("0",$x_dif).$x_limite_credito;
	}else{
		$x_dato = $x_limite_credito;		
	}
	$x_lon = 9;	
}

if(strlen($x_lon) < 2){
	$x_lon = "0".$x_lon;
}

$x_lin .= $x_pref.$x_lon.$x_dato;


$temptime = strtotime($x_fecha_ultimo_pago);
$x_fecha_ultimo_pago = strftime('%d%m%Y',$temptime);


if($x_fecha_ultimo_vencido != ""){
	$hoy_base = getdate(time());
	$hoy = $hoy_base["mday"]."/".$hoy_base["mon"]."/".$hoy_base["year"];	
	$hoy = ConvertDateToMysqlFormat($hoy);
	
	$x_dias_vencidos = datediff('d', $x_fecha_ultimo_vencido, $hoy, false);	

	switch ($x_dias_vencidos)
	{
		case ($x_dias_vencidos > 0 && $x_dias_vencidos < 30): // Get a record to display
			$x_fpgo = "02";
			break;
		case ($x_dias_vencidos > 29 && $x_dias_vencidos < 60): // Get a record to display
			$x_fpgo = "03";
			break;
		case ($x_dias_vencidos > 59 && $x_dias_vencidos < 90): // Get a record to display
			$x_fpgo = "04";
			break;
		case ($x_dias_vencidos > 89 && $x_dias_vencidos < 120): // Get a record to display
			$x_fpgo = "05";
			break;
		case ($x_dias_vencidos > 119 && $x_dias_vencidos < 150): // Get a record to display
			$x_fpgo = "06";
			break;
		case ($x_dias_vencidos > 149 && $x_dias_vencidos < 361): // Get a record to display
			$x_fpgo = "07";
			break;
		case ($x_dias_vencidos > 361 && $x_dias_vencidos < 370): // Get a record to display
			$x_fpgo = "96";
			break;
	}

}else{
	$x_fpgo = "01";	 //al corriente	
}


$x_pref = "26";
$x_lon = strlen($x_fpgo);
$x_dato = strtoupper($x_fpgo);

if(strlen($x_lon) < 2){
	$x_lon = "0".$x_lon;
}

$x_lin .= $x_pref.$x_lon.$x_dato;

//$x_lin .= "99"."03"."FIN";


$x_lin = limpiatxt($x_lin);

fwrite($fp,$x_lin);


	}
}

/*segmento cierre
1 etiqueta del seguemtno  TRLR
5 total de saldos actuales 14
19 total de saldos venc 14
33 total de segmentos INTF (encabezados reportados) 3
36 total de seguemtnos PN  9 nombre
45 total de seguemtnos PA 9 direccion
54 total de seguemento PE 9 empleo
63 total de seguemento TL 9 creditos
72 contador de bloques  6 (llenar con ceros)
78 nombre del otorgante 16
94 domicilio para devolucion 160
*/

$x_pref = "TRLR";
$x_lon = strlen($x_tot_saldo_actual);
if($x_lon > 9){
	$x_dato = strtoupper(substr($x_tot_saldo_actual,0,8));	
	$x_lon = 9;
}else{
	if($x_lon < 9){	
		$x_dif = (9 - strlen($x_tot_saldo_actual));
		$x_dato = str_repeat("0",$x_dif).$x_tot_saldo_actual;
	}else{
		$x_dato = $x_tot_saldo_actual;		
	}
	$x_lon = 9;	
}

if(strlen($x_lon) < 2){
	$x_lon = "0".$x_lon;
}

$x_lin .= $x_pref.$x_lon.$x_dato;

$x_pref = "19";
$x_lon = strlen($x_tot_saldo_vencido);
if($x_lon > 9){
	$x_dato = strtoupper(substr($x_tot_saldo_vencido,0,8));	
	$x_lon = 9;
}else{
	if($x_lon < 9){	
		$x_dif = (9 - strlen($x_tot_saldo_vencido));
		$x_dato = str_repeat("0",$x_dif).$x_tot_saldo_vencido;
	}else{
		$x_dato = $x_tot_saldo_vencido;		
	}
	$x_lon = 9;	
}

if(strlen($x_lon) < 2){
	$x_lon = "0".$x_lon;
}

$x_lin .= $x_pref.$x_lon.$x_dato;

$x_lin .= "33"."03"."001";

$x_tot_nombres_reportados = FormatNumber($x_tot_nombres_reportados,0,0,0,0);
if(strlen($x_tot_nombres_reportados) < 9){
	$x_dif = 9 - strlen($x_tot_nombres_reportados);
	$x_tot_nombres_reportados = str_repeat("0",9).$x_tot_nombres_reportados;
}
$x_lin .= "36"."09".$x_tot_nombres_reportados;

$x_tot_domicilio_reportados = FormatNumber($x_tot_domicilio_reportados,0,0,0,0);
if(strlen($x_tot_domicilio_reportados) < 9){
	$x_dif = 9 - strlen($x_tot_domicilio_reportados);
	$x_tot_domicilio_reportados = str_repeat("0",9).$x_tot_domicilio_reportados;
}
$x_lin .= "45"."09".$x_tot_domicilio_reportados;

$x_lin .= "54"."09"."000000000";

$x_tot_cuenta_reportados = FormatNumber($x_tot_cuenta_reportados,0,0,0,0);
if(strlen($x_tot_cuenta_reportados) < 9){
	$x_dif = 9 - strlen($x_tot_cuenta_reportados);
	$x_tot_cuenta_reportados = str_repeat("0",9).$x_tot_cuenta_reportados;
}
$x_lin .= "63"."09".$x_tot_cuenta_reportados;

$x_lin .= "72"."06"."000000";

$x_lin .= "78"."16".$x_nombre_otorgante;

$x_lin .= "94"."160".str_repeat("0",160);


fwrite($fp,$x_lin);

//cierra archiv
fclose($fp);
//muestra link de archivo

?>
<p align="left" class="texto_titulo" style=" padding-left:10px">
<a href="<?php echo $x_arch; ?>" target="_blank">Descargar reporte generado</a>
</p>
<?php if ($sExport == "") { ?>
<?php if ($nRecActual > 0) { ?>
<p>
  <?php } ?>
  <?php } ?>
</p>
</form>
<?php }else{ ?>
No hay datos que mostrar.
<?php } ?>
<?php

// Close recordset and connection
phpmkr_free_result($rs);
phpmkr_db_close($conn);


function limpiatxt($x_txt){

$x_txt = str_replace("N","N",$x_txt);
$x_txt = str_replace("#","N",$x_txt);



return $x_txt;
 
}

?>
<?php if ($sExport <> "word" && $sExport <> "excel") { ?>
</body>
</html>
<?php } ?>


