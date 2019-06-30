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
	header('Content-Disposition: attachment; filename=circulocredito.xls');
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

// Build WHERE condition
$x_fecha_desde = @$_GET["key1"];
$x_fecha_hasta = @$_GET["key2"];
if (($x_fecha_desde == "") || ((is_null($x_fecha_desde)))) {
	$x_fecha_desde = @$_POST["x_fecha_desde"];
	$x_fecha_hasta = @$_POST["x_fecha_hasta"];
	if (($x_fecha_desde == "") || ((is_null($x_fecha_desde)))) {	
		header("Location:  php_circulocredito_list.php");
		exit();
	}
}



// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);


// Build SQL
$sSql = "SELECT * FROM `vencimiento` join credito on credito.credito_id = vencimiento.credito_id ";

// Load Default Filter
$sDefaultFilter = "";
$sGroupBy = " vencimiento.credito_id ";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = "";

$sDbWhere = "(vencimiento.fecha_vencimiento >= '".ConvertDateToMysqlFormat($x_fecha_desde)."') AND ";
$sDbWhere .= "(vencimiento.fecha_vencimiento <= '".ConvertDateToMysqlFormat($x_fecha_hasta)."') AND ";
$sDbWhere .= "(credito.credito_tipo_id = 1) AND ";
$sDbWhere .= "(credito.credito_status_id = 1) AND ";


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
	$sSql .= " ORDER BY vencimiento.credito_id,vencimiento.fecha_vencimiento ";
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
<a href="php_circulocredito_list.php">Regresar</a><br /><br />
<?php } ?>

<?php if ($nTotalRecs > 0)  { ?>
<form method="post" name="principal" action="php_circulocredito_rpt.php">
<input type="hidden" name="credito_id" value="<?php echo $x_credito_id; ?>"  />

<?php if ($sExport == "") { ?>
<a href="php_circulocredito_rpt.php?export=excel&<?php echo "key1=".$x_fecha_desde."&key2=".$x_fecha_hasta; ?>">Exportar a Excel</a>
<br /><br />
<?php } ?>

<table id="ewlistmain" class="ewTable" align="center">
	<!-- Table header -->
	<tr class="ewTableHeader">
		<td valign="top"><span>
ClaveOtorgante
		</span></td>
		<td valign="top"><span>
NombreOtrogante
		</span></td>
		<td valign="top"><span>
IdentificadorDeMedio
		</span></td>       
		<td valign="top"><span>
FechaExtraccion
		</span></td>				        
		<td valign="top"><span>
NotaOtorgante
		</span></td>				
		<td valign="top"><span>
ApellidoPaterno
		</span></td>
		<td valign="top"><span>
ApellidoMaterno
		</span></td>
		<td valign="top"><span>
ApellidoAdicional
		</span></td>
		<td valign="top"><span>
Nombres
		</span></td>						
		<td valign="top"><span>
FechaNacimiento
		</span></td>						
		<td valign="top"><span>
RFC
		</span></td>						
		<td valign="top"><span>
CURP
		</span></td>						
		<td valign="top"><span>
Nacionalidad
		</span></td>						
		<td valign="top"><span>
Residencia
		</span></td>						
		<td valign="top"><span>
NumeroLicenciaConducir
		</span></td>						
		<td valign="top"><span>
EstadoCivil
		</span></td>						
		<td valign="top"><span>
Sexo
		</span></td>						
		<td valign="top"><span>
ClaveElectorIFE
		</span></td>						
		<td valign="top"><span>
NumeroDependientes
		</span></td>						
		<td valign="top"><span>
FechaDefuncion
		</span></td>						
		<td valign="top"><span>
IndicadorDefuncion
		</span></td>						
		<td valign="top"><span>
TipoPersona
		</span></td>						
		<td valign="top"><span>
Direccion
		</span></td>						
		<td valign="top"><span>
ColoniaPoblacion
		</span></td>						
		<td valign="top"><span>
DelegacionMunicipio
		</span></td>						
		<td valign="top"><span>
Ciudad
		</span></td>						
		<td valign="top"><span>
Estado
		</span></td>						
		<td valign="top"><span>
CP
		</span></td>						
		<td valign="top"><span>
FechaResidencia
		</span></td>						
		<td valign="top"><span>
NumeroTelefono
		</span></td>						
		<td valign="top"><span>
TipoDomicilio
		</span></td>						
		<td valign="top"><span>
TipoAsentamiento
		</span></td>						
		<td valign="top"><span>
NombreEmpresa
		</span></td>						
		<td valign="top"><span>
Direccion
		</span></td>						
		<td valign="top"><span>
ColoniaPoblacion
		</span></td>						
		<td valign="top"><span>
DelegacionMunicipio
		</span></td>						
		<td valign="top"><span>
Ciudad
		</span></td>						
		<td valign="top"><span>
Estado
		</span></td>						
		<td valign="top"><span>
CP
		</span></td>						
		<td valign="top"><span>
NumeroTelefono
		</span></td>						
		<td valign="top"><span>
Extension
		</span></td>						
		<td valign="top"><span>
Fax
		</span></td>						
		<td valign="top"><span>
Puesto
		</span></td>						
		<td valign="top"><span>
FechaContratacion
		</span></td>						
		<td valign="top"><span>
ClaveMoneda
		</span></td>						
		<td valign="top"><span>
SalarioMensual
		</span></td>						
		<td valign="top"><span>
FechaUltimoDiaEmpleo
		</span></td>						
		<td valign="top"><span>
FechaVerificacionEmpleo
		</span></td>						
		<td valign="top"><span>
ClaveActualOtorgante
		</span></td>						
		<td valign="top"><span>
NombreOtorgante
		</span></td>						
		<td valign="top"><span>
CuentaActual
		</span></td>						
		<td valign="top"><span>
TipoResponsabilidad
		</span></td>						
		<td valign="top"><span>
TipoCuenta
		</span></td>						
		<td valign="top"><span>
TipoContrato
		</span></td>						
		<td valign="top"><span>
ClaveUnidadMonetaria
		</span></td>						
		<td valign="top"><span>
ValorActivoValuacion
		</span></td>						
		<td valign="top"><span>
NumeroPagos
		</span></td>						
		<td valign="top"><span>
FrecuenciaPagos
		</span></td>						
		<td valign="top"><span>
MontoPagar
		</span></td>						
		<td valign="top"><span>
FechaAperturaCuenta
		</span></td>						
		<td valign="top"><span>
FechaUltimoPago
		</span></td>						
		<td valign="top"><span>
FechaUltimaCompra
		</span></td>						
		<td valign="top"><span>
FechaCierreCuenta
		</span></td>						
		<td valign="top"><span>
FechaCorte
		</span></td>						
		<td valign="top"><span>
Garantia
		</span></td>						
		<td valign="top"><span>
CreditoMaximo
		</span></td>						
		<td valign="top"><span>
SaldoActual
		</span></td>						
		<td valign="top"><span>
LimiteCredito
		</span></td>						
		<td valign="top"><span>
SaldoVencido
		</span></td>						
		<td valign="top"><span>
NumeroPagosVencidos
		</span></td>						
		<td valign="top"><span>
PagoActual
		</span></td>						
		<td valign="top"><span>
HistoricoPagos
		</span></td>						
		<td valign="top"><span>
ClavePrevencion
		</span></td>						
		<td valign="top"><span>
TotalPagosReportados
		</span></td>						
		<td valign="top"><span>
ClaveAnteriorOtorgante
		</span></td>						
		<td valign="top"><span>
NombreAnteriorOtorgante
		</span></td>						
		<td valign="top"><span>
NumeroCuentaAnterior
		</span></td>						
		<td valign="top"><span>
TotalSaldosActuales
		</span></td>						
		<td valign="top"><span>
TotalSaldosVencidos
		</span></td>			
		<td valign="top"><span>
TotalElementosNombreReportados
		</span></td>						
		<td valign="top"><span>
TotalElementosDireccionReportados
		</span></td>						
		<td valign="top"><span>
TotalElementosEmpleoReportados
		</span></td>						
		<td valign="top"><span>
TotalElementosCuentaReportados
		</span></td>						
		<td valign="top"><span>
NombreOtorgante
		</span></td>						
		<td valign="top"><span>
DomicilioDevolucion
		</span></td>						
	</tr>

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

//$x_ClaveOtorgante = "'0003140049'";
$x_ClaveOtorgante = "=TEXTO(0003140049,\"0000000000\")";

$x_NombreOtorgante = "FINANCIERA CRECE";
$x_ClaveActualOtorgante = "=TEXTO(0003140049,\"0000000000\")";

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
		

		
		$x_fecha_vencimiento = $row["fecha_vencimiento"];
		$x_importe = $row["importe"];
		$x_interes = $row["interes"];
		$x_interes_moratorio = $row["interes_moratorio"];
		
		$x_total = $x_importe + $x_interes + $x_interes_moratorio;

		$x_limite_credito = $x_importe;
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
		if(empty($x_apellido_paterno)){
			$x_apellido_materno = "NO PROPORCIONADO";
		}		
		if(empty($x_apellido_materno)){
			$x_apellido_materno = "NO PROPORCIONADO";
		}
		$x_nombre = $datawrk["nombre_completo"];
		$x_fecha_nacimiento = $datawrk["fecha_nac"];	//falta
		
		$temptime = strtotime($x_fecha_nacimiento);
		$x_fecha_nacimiento = strftime('%Y%m%d',$temptime);

		$x_fecha_nacimiento = "";

		$x_rfc = $datawrk["rfc"];
		
		$x_rfc_4 = substr($x_rfc,0,4);

		if(strpos("0",$x_rfc_4) > 0){
			$x_rfc = "";	
		}
		if(strpos("1",$x_rfc_4) > 0){
			$x_rfc = "";	
		}
		if(strpos("2",$x_rfc_4) > 0){
			$x_rfc = "";	
		}
		if(strpos("3",$x_rfc_4) > 0){
			$x_rfc = "";	
		}
		if(strpos("4",$x_rfc_4) > 0){
			$x_rfc = "";	
		}
		if(strpos("5",$x_rfc_4) > 0){
			$x_rfc = "";	
		}
		if(strpos("6",$x_rfc_4) > 0){
			$x_rfc = "";	
		}
		if(strpos("7",$x_rfc_4) > 0){
			$x_rfc = "";	
		}
		if(strpos("8",$x_rfc_4) > 0){
			$x_rfc = "";	
		}
		if(strpos("9",$x_rfc_4) > 0){
			$x_rfc = "";	
		}

		
		
		//$x_rfc = $datawrk["rfc"];		
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
			$x_estado_civil = "";
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
			$x_codigo_postal = "=TEXTO($x_codigo_postal,\"00000\")";
			
			
			$x_fecha_residencia = $datawrk["fecha_residencia"]; //falta			

			$temptime = strtotime($x_fecha_residencia);
			$x_fecha_residencia = strftime('%Y%m%d',$temptime);
			
			$x_telefono = $datawrk["telefono"]; 	
			$x_telefono = str_replace("-","",$x_telefono);
			$x_telefono = str_replace(".","",$x_telefono);
			$x_telefono = str_replace("(","",$x_telefono);
			$x_telefono = str_replace(")","",$x_telefono);
			
			if(is_numeric($x_telefono)){
				$x_telefono = "=TEXTO($x_telefono,\"0\")";
			}else{
				$x_telefono = "";
			}
			
			/*
			if(!is_integer($x_telefono)){
				$x_telefono = "";
			}
			*/
			$x_residencia = $datawrk["vivienda_tipo_id"];
			if($x_residencia == 0){
				$x_residencia = 1;	
			}
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

		$x_direccion = trim($x_direccion);

		if(strpos($x_direccion," ") == 0 ){
			$x_direccion = $x_direccion . " SN";	
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
				case "1": // Get a record to display
					$x_frecuencia_pagos = "S";
					break;
				case "2": // Get a record to display
					$x_frecuencia_pagos = "C";
					break;
				case "3": // Get a record to display
					$x_frecuencia_pagos = "M";
					break;
				case "4": // Get a record to display
					$x_frecuencia_pagos = "Q";
					break;
			}
		


			$sSqlWrk = "SELECT max(fecha_pago) as ultimo_pago FROM vencimiento join recibo_vencimiento
			on recibo_vencimiento.vencimiento_id = vencimiento.vencimiento_id join recibo
			on recibo.recibo_id = recibo_vencimiento.recibo_id
			where recibo_status_id = 1 and vencimiento.credito_id = $x_credito_id ";
			//echo $sSqlWrk;
			$rswrk_cli = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk_cli);
			$x_fecha_ultimo_pago = $datawrk["ultimo_pago"];

			$temptime = strtotime($x_fecha_ultimo_pago);
			$x_fecha_ultimo_pago = strftime('%Y%m%d',$temptime);
			
			@phpmkr_free_result($rswrk_cli);
							

			$sSqlWrk = "SELECT max(importe) as credito_maximo FROM credito where cliente_num = '$x_cliente_num' and credito_status_id not in (3)";
			//echo $sSqlWrk;
			$rswrk_cli = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk_cli);
			$x_credito_maximo = $datawrk["credito_maximo"];
			@phpmkr_free_result($rswrk_cli);

			if(empty($x_credito_maximo)){
				$x_credito_maximo = 0;	
			}


			$sSqlWrk = "SELECT * FROM vencimiento where credito_id = $x_credito_id order by vencimiento_id";
			$rswrk_cli = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$x_tot_imp = 0;							
			$x_tot_imp_pagos = 0;				
			$x_tot_imp_pagos_pen = 0;								
			$x_tot_pagos_ven = 0;
			$x_tot_imp_pagos_ven = 0;			
			$x_pago_actual = 0;
			
			while($datawrk = phpmkr_fetch_array($rswrk_cli)){
				$x_tot_imp = $x_tot_imp  + $datawrk["total_venc"];
				if($datawrk["vencimiento_status_id"] == 1){
					$x_tot_imp_pagos_pen = $x_tot_imp_pagos_pen + $datawrk["total_venc"];			
				}
				if($datawrk["vencimiento_status_id"] == 3){
					$x_tot_pagos_ven = $x_tot_pagos_ven + 1;
					$x_tot_imp_pagos_ven = $x_tot_imp_pagos_ven + $datawrk["total_venc"];			
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
				if(FormatDateTime($datawrk["fecha_vencimiento"],7) < FormatDateTime($x_fecha_hasta,7) || FormatDateTime($datawrk["fecha_vencimiento"],7) == FormatDateTime($x_fecha_hasta,7)){
					$x_pago_actual++;
				}

				if(
				(FormatDateTime($datawrk["fecha_vencimiento"],7) > FormatDateTime($x_fecha_desde,7) || 
				FormatDateTime($datawrk["fecha_vencimiento"],7) == FormatDateTime($x_fecha_desde,7))
				&&
				(FormatDateTime($datawrk["fecha_vencimiento"],7) < FormatDateTime($x_fecha_hasta,7) || 
				FormatDateTime($datawrk["fecha_vencimiento"],7) == FormatDateTime($x_fecha_hasta,7))){
					$x_monto_pagar = $datawrk["total_venc"];	
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
			
			
			if(($x_monto_pagar < $x_saldo_actual || $x_monto_pagar == $x_saldo_actual) && $x_credito_maximo > 0 && (!empty($x_delegacion) || !empty($x_ciudad))){	
			
?>
	<!-- Table body -->
<tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>

<td align="left" valign="top"><span class="phpmaker">
<!--CC-->
<?php echo $x_ClaveOtorgante; ?>
</span></td>
<td align="left" valign="top"><span class="phpmaker">
<!--CC-->
<?php echo $x_NombreOtorgante; ?>
</span></td>
<td align="left" valign="top"><span class="phpmaker">
ARCHIVO
</span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo $x_fecha_extraccion; ?>
</span></td>
<td align="left" valign="top"><span class="phpmaker">
</span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo strtoupper(limpiatxt($x_apellido_paterno)); ?>
</span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo strtoupper(limpiatxt($x_apellido_materno)); ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
</span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo strtoupper(limpiatxt($x_nombre)); ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php //echo $x_fecha_nacimiento; ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php //echo strtoupper(limpiatxt($x_rfc)); ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php 
$x_curp = strtoupper(limpiatxt($x_curp)); 
if(strlen($x_curp) == 18){
	//echo $x_curp;
}
?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo "MX"; ?>
</span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo strtoupper(limpiatxt($x_residencia)); //tipo de vivienda?>
</span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo strtoupper(limpiatxt($x_licencia_conducir)); ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo $x_estado_civil; ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo $x_sexo; ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo strtoupper(limpiatxt($x_ife)); ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo $x_dependientes; ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
</span></td>
<td align="left" valign="top"><span class="phpmaker">
</span></td>
<td align="left" valign="top"><span class="phpmaker">
PF
</span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo substr(strtoupper(limpiatxt($x_direccion)),0,30); ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo strtoupper(limpiatxt($x_colonia)); ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo strtoupper(limpiatxt($x_delegacion)); ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo strtoupper(limpiatxt($x_ciudad)); ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo strtoupper($x_estado); ?>
</span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo $x_codigo_postal; ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php //echo $x_fecha_residencia; ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php //echo $x_telefono; ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php 
$x_domicilio_tipo = "C";
echo $x_domicilio_tipo; // N = Negocio   O = Domicilio del Otorgante   C = Casa   P = Apartado Postal   E = Empleo ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo $x_asentamiento_tipo_id; // tabla ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php // echo strtoupper(limpiatxt($x_empresa)); ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php // echo substr(strtoupper(limpiatxt($x_empresa_direccion)),0,30); ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php // echo strtoupper(limpiatxt($x_empresa_colonia)); ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php // echo strtoupper(limpiatxt($x_empresa_delegacion)); ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php // echo strtoupper(limpiatxt($x_empresa_ciudad)); ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php // echo strtoupper(limpiatxt($x_empresa_estado)); ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php // echo $x_empresa_codigo_postal; ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php //echo $x_empresa_telefono; ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php // echo $x_empresa_extension; ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php // echo $x_empresa_fax; ?>
</span></td>
<td align="left" valign="top"><span class="phpmaker"><?php // echo strtoupper(limpiatxt($x_empresa_puesto)); ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php // echo $x_empresa_fecha_contratacion; ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php /*
if($x_empresa_salario_mensual > 0){
	echo $x_empresa_clave_moneda; 
}
*/?></span></td>
<td align="left" valign="top"><span class="phpmaker"><?php // echo FormatNumber($x_empresa_salario_mensual,0,0,0,0); ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php // echo $x_empresa_ultima_fecha_empleo; ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php /* if($x_empresa_puesto != ""){echo $x_fecha_solicitud;} */ ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<!--CC-->
<?php echo $x_ClaveActualOtorgante; ?>
</span></td>
<td align="left" valign="top"><span class="phpmaker">
<!--CC-->
<?php echo $x_NombreOtorgante; ?>
</span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo $x_credito_num; ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo $x_tipo_responsabilidad; // tabla?>
</span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo $x_tipo_cuenta; // Tabla Pagos Fijos?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo $x_tipo_contrato; // tabla todos PP?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo $x_unidad_monetaria; // tabla todos MX?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo FormatNumber($x_garantia_valor,0,0,0,0); // valor garantia?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo $x_num_pagos; ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo $x_frecuencia_pagos; // forma de pagos?></span></td>
<td align="right" valign="top"><span class="phpmaker">
<?php 
if(!empty($x_fecha_finiquito_credito)){
	echo "0";		
}else{
	echo FormatNumber($x_monto_pagar,0,0,0,0); 
}
?>
</span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo $x_fecha_solicitud; ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo $x_fecha_ultimo_pago; ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo $x_fecha_solicitud; ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo $x_fecha_finiquito_credito; ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo $x_fecha_corte; ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php echo substr(strtoupper(limpiatxt($x_garantia_descripcion)),0,30); ?></span></td>
<td align="right" valign="top"><span class="phpmaker">
<?php echo FormatNumber($x_credito_maximo,0,0,0,0); // tabla?></span></td>
<td align="right" valign="top"><span class="phpmaker">
<?php 

if(!empty($x_fecha_finiquito_credito)){
	echo "0";		
}else{
	if(empty($x_saldo_actual) || $x_saldo_actual == 0){
		
		if($x_monto_pagar > 0){
			echo FormatNumber($x_monto_pagar,0,0,0,0); ;
		}else{
			echo "0";	
		}
	}else{
		echo FormatNumber($x_saldo_actual,0,0,0,0); 
	}
}
?></span></td>
<td align="right" valign="top"><span class="phpmaker">
<?php 
//soo se reporta para creditos revolventes
echo "0";
//echo FormatNumber($x_limite_credito,0,0,0,0); //importe credito 
?></span></td>
<td align="right" valign="top"><span class="phpmaker">
<?php 
if($x_saldo_vencido == 0){
	echo "0";
}else{
	echo FormatNumber($x_saldo_vencido,0,0,0,0); //cartera venc 	
}
/*
if($x_num_pagos_venc == 0){
	echo "0";
}else{
	echo FormatNumber($x_saldo_vencido,0,0,0,0); //cartera venc 
}
*/
?></span></td>
<td align="right" valign="top"><span class="phpmaker">
<?php //echo $x_num_pagos_venc; ?></span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php 
if($x_saldo_vencido == 0){
	$x_pago_actual = "&nbsp;V";
}else{
	if($x_pago_actual > 83){
		$x_pago_actual = "84";
	}else{
		$x_pago_actual = "=TEXTO($x_pago_actual,\"00\")";	
	}
}
echo $x_pago_actual;
?></span></td>
<td><span class="phpmaker">
<!-- CC -->

</span></td>
<td><span class="phpmaker">

</span></td>
<td><span class="phpmaker">

</span></td>
<td><span class="phpmaker">

</span></td>
<td><span class="phpmaker">

</span></td>
<td><span class="phpmaker">

</span></td>
<td align="right" valign="top"><span class="phpmaker">
<?php 
//if($nRecActual == $nTotalRecs){
	
	echo FormatNumber($x_tot_saldo_actual,0,0,0,0);
	
	
//} ?>
</span></td>
<td align="right" valign="top"><span class="phpmaker">
<?php 
//if($nRecActual == $nTotalRecs){
	echo FormatNumber($x_tot_saldo_vencido,0,0,0,0);
//} 
?>
</span></td>
<td align="right" valign="top"><span class="phpmaker">
<?php 
//if($nRecActual == $nTotalRecs){

	echo FormatNumber($x_tot_nombres_reportados,0,0,0,0);
	
//} 
?>
</span></td>
<td align="right" valign="top"><span class="phpmaker">
<?php 
//if($nRecActual == $nTotalRecs){
	
	echo FormatNumber($x_tot_domicilio_reportados,0,0,0,0);
	
//} 
?>
</span></td>
<td align="right" valign="top"><span class="phpmaker">
<?php 
//if($nRecActual == $nTotalRecs){
	
	echo FormatNumber($x_tot_empleo_reportados,0,0,0,0);
	
//} 
?>
</span></td>
<td align="right" valign="top"><span class="phpmaker">
<?php 
//if($nRecActual == $nTotalRecs){
	
	echo FormatNumber($x_tot_cuenta_reportados,0,0,0,0);
	
//} 
?>
</span></td>
<td align="left" valign="top"><span class="phpmaker">
<?php 
//if($nRecActual == $nTotalRecs){
	echo $x_NombreOtorgante;
//} 
?>
</span></td>
<td><span class="phpmaker">
jfoncerrada@financieracrea.com
</span></td>
</tr>
<?php
			}
	}
}
?>
</table>
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


return $x_txt;
 
}

?>
<?php if ($sExport <> "word" && $sExport <> "excel") { ?>
</body>
</html>
<?php } ?>


