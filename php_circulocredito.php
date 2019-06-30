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

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

// Handle Reset Command
ResetCmd();

// Build SQL
$sSql = "SELECT * FROM `vencimiento`";

// Load Default Filter
$sDefaultFilter = "";
$sGroupBy = "";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = "";

// Build WHERE condition
$x_credito_id = @$_GET["credito_id"];
if (($x_credito_id == "") || ((is_null($x_credito_id)))) {
	$x_credito_id = @$_POST["credito_id"];
	if (($x_credito_id == "") || ((is_null($x_credito_id)))) {	
		echo "Credito no localizado.";
		exit();
	}
}
$sDbWhere = "(vencimiento.credito_id = $x_credito_id) AND ";

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
SetUpSortOrder();
if ($sOrderBy != "") {
	$sSql .= " ORDER BY vencimiento.vencimiento_num ";
}

//echo $sSql; // Uncomment to show SQL for debugging
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Financiera CRECE</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">td img {display: block;}</style>
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#FFFFFF">
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
<script type="text/javascript">
<!--
var EW_HTMLArea;

//-->
</script>
<script type="text/javascript">
<!--
function EW_selectKey(elem) {
	var f = elem.form;	
	if (!f.elements["key_d[]"]) return;
	if (f.elements["key_d[]"][0]) {
		for (var i=0; i<f.elements["key_d[]"].length; i++)
			f.elements["key_d[]"][i].checked = elem.checked;	
	} else {
		f.elements["key_d[]"].checked = elem.checked;	
	}
	if (f.elements["checkall"])
	{
		if (f.elements["checkall"][0])
		{
			for (var i = 0; i<f.elements["checkall"].length; i++)
				f.elements["checkall"][i].checked = elem.checked;
		} else {
			f.elements["checkall"].checked = elem.checked;
		}
	}
	ew_clickall(elem);
}
function EW_selected(elem) {
	var f = elem.form;	
	if (!f.elements["key_d[]"]) return false;
	if (f.elements["key_d[]"][0]) {
		for (var i=0; i<f.elements["key_d[]"].length; i++)
			if (f.elements["key_d[]"][i].checked) return true;
	} else {
		return f.elements["key_d[]"].checked;
	}
	return false;
}

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
SetUpStartRec(); // Set Up Start Record Position
?>

<?php if ($sExport == "") { ?>
<form action="php_circulocredito.php" name="ewpagerform" id="ewpagerform">
</form>
<?php } ?>
<?php if ($nTotalRecs > 0)  { ?>
<form method="post" name="principal" action="php_circulocredito.php">
<input type="hidden" name="credito_id" value="<?php echo $x_credito_id; ?>"  />
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


$sSqlWrk = "SELECT importe FROM credito where credito_id = $x_credito_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
$x_saldo = $datawrk["importe"];
@phpmkr_free_result($rswrk);

$x_total = 0;
$x_total_pagos = 0;
$x_total_interes = 0;
$x_total_moratorios = 0;
$x_total_total = 0;
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

		$x_total_pagos = $x_total_pagos + $x_importe;
		$x_total_interes = $x_total_interes + $x_interes;
		$x_total_moratorios = $x_total_moratorios + $x_interes_moratorio;
		$x_total_total = $x_total_total + $x_total;
		
		$x_ref_loc = str_pad($x_vencimiento_id, 5, "0", STR_PAD_LEFT)."/".str_pad($x_vencimiento_num, 2, "0", STR_PAD_LEFT);
		
?>
	<!-- Table body -->
<tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>

<td><span class="phpmaker">
<!--CC-->
&nbsp;
</span></td>
<td><span class="phpmaker">
<!--CC-->
&nbsp;
</span></td>
<td><span class="phpmaker">
ARCHIVO
&nbsp;
</span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $currdate; ?>
</span></td>
<td><span class="phpmaker">
&nbsp;</span></td>
<td><span class="phpmaker">
&nbsp;<?php echo $x_apellido_paterno; ?>
</span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_apellido_materno; ?></span></td>
<td><span class="phpmaker">
&nbsp;
</span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_nombre; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_fecha_nacimiento; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_rfc; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_curp; ?></span></td>
<td><span class="phpmaker">
&nbsp;<?php echo $x_nacionalidad; ?>
</span></td>
<td><span class="phpmaker">
&nbsp;<?php echo $x_residencia; //tipo de vivienda?>
</span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_licencia_conducir; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_estado_civil; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_sexo; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_ife; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_dependientes; ?></span></td>
<td><span class="phpmaker">
&nbsp;</span></td>
<td><span class="phpmaker">
&nbsp;
</span></td>
<td><span class="phpmaker">
&nbsp; PF
</span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_direccion; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_colonia; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_delegacion; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_ciudad; ?></span></td>
<td><span class="phpmaker">
&nbsp;<?php echo $x_estado; ?>
</span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_codigo_postal; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_fecha_residencia; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_telefono; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_domicilio_tipo; // N = Negocio   O = Domicilio del Otorgante   C = Casa   P = Apartado Postal   E = Empleo ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_tipo_asentamiento; // tabla ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_empresa; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_empresa_direccion; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_empresa_colonia; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_empresa_delegacion; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_empresa_ciudad; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_empresa_estado; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_empresa_codigo_postal; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_empresa_telefono; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_empresa_telefono_ext; ?></span></td>
<td><span class="phpmaker">
<?php echo $x_empresa_fax; ?>&nbsp;
</span></td>
<td><span class="phpmaker">
&nbsp;
</span><span class="phpmaker"><?php echo $x_empresa_puesto; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_empsa_fecha_contratacion; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_empresa_tipo_moneda; ?></span></td>
<td><span class="phpmaker">
&nbsp;
</span><span class="phpmaker"><?php echo $x_empresa_puesto; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_empresa_ultima_fecha_empleo; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_empresa_fecha_verificacion; ?></span></td>
<td><span class="phpmaker">
<!--CC-->
&nbsp;
</span></td>
<td><span class="phpmaker">
<!--CC-->
&nbsp;
</span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_numero_credito; ?></span></td>
<td><span class="phpmaker">
&nbsp;<?php echo $x_tipo_responsabilidad; // tabla?>
</span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_tipo_cuenta; // Tabla Pagos Fijos?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_tipo_contrato; // tabla todos PP?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_unidad_monetaria; // tabla todos MX?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_valor_activo_valuacion; // valor garantia?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_num_pagos; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_frecuencia_pagos; // forma de pagos?></span></td>
<td><span class="phpmaker">
&nbsp;<?php echo $x_importe_credito; ?>
</span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_fecha_solicitud; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_fecha_ultimo_pago; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_fecha_credito; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_fecha_finiquito_credito; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_fecha_vencimeinto; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_garantia; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_credito_maximo; // tabla?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_saldo_actual; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_limite_credito; //importe credito ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_saldo_vencido; //cartera venc ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_num_pagos_venc; ?></span></td>
<td><span class="phpmaker">
&nbsp;
<?php echo $x_importe_venc; ?></span></td>
<td><span class="phpmaker">
<!-- CC -->
&nbsp;
</span></td>
<td><span class="phpmaker">
&nbsp;
</span></td>
<td><span class="phpmaker">
&nbsp;
</span></td>
<td><span class="phpmaker">
&nbsp;
</span></td>
<td><span class="phpmaker">
&nbsp;
</span></td>
<td><span class="phpmaker">
&nbsp;
</span></td>
<td><span class="phpmaker">
&nbsp;
</span></td>
<td><span class="phpmaker">
&nbsp;
</span></td>
<td><span class="phpmaker">
&nbsp;
</span></td>
<td><span class="phpmaker">
&nbsp;
</span></td>
<td><span class="phpmaker">
&nbsp;
</span></td>
<td><span class="phpmaker">
&nbsp;
</span></td>
<td><span class="phpmaker">
&nbsp;
</span></td>
<td><span class="phpmaker">
&nbsp;
</span></td>
</tr>
<?php
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
<?php } ?>
<?php

// Close recordset and connection
phpmkr_free_result($rs);
phpmkr_db_close($conn);
?>
<?php if ($sExport <> "word" && $sExport <> "excel") { ?>
</body>
</html>
<?php } ?>
<?php

//-------------------------------------------------------------------------------
// Function SetUpSortOrder
// - Set up Sort parameters based on Sort Links clicked
// - Variables setup: sOrderBy, Session("Table_OrderBy"), Session("Table_Field_Sort")

function SetUpSortOrder()
{
	global $sOrderBy;
	global $sDefaultOrderBy;

	// Check for an Order parameter
	if (strlen(@$_GET["order"]) > 0) {
		$sOrder = @$_GET["order"];

		// Field vencimiento_id
		if ($sOrder == "vencimiento_id") {
			$sSortField = "`vencimiento_id`";
			$sLastSort = @$_SESSION["vencimiento_x_vencimiento_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vencimiento_x_vencimiento_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vencimiento_x_vencimiento_id_Sort"] <> "") { @$_SESSION["vencimiento_x_vencimiento_id_Sort"] = ""; }
		}

		// Field credito_id
		if ($sOrder == "credito_id") {
			$sSortField = "`credito_id`";
			$sLastSort = @$_SESSION["vencimiento_x_credito_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vencimiento_x_credito_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vencimiento_x_credito_id_Sort"] <> "") { @$_SESSION["vencimiento_x_credito_id_Sort"] = ""; }
		}

		// Field vencimiento_status_id
		if ($sOrder == "vencimiento_status_id") {
			$sSortField = "`vencimiento_status_id`";
			$sLastSort = @$_SESSION["vencimiento_x_vencimiento_status_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vencimiento_x_vencimiento_status_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vencimiento_x_vencimiento_status_id_Sort"] <> "") { @$_SESSION["vencimiento_x_vencimiento_status_id_Sort"] = ""; }
		}

		// Field fecha_vencimiento
		if ($sOrder == "fecha_vencimiento") {
			$sSortField = "`fecha_vencimiento`";
			$sLastSort = @$_SESSION["vencimiento_x_fecha_vencimiento_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vencimiento_x_fecha_vencimiento_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vencimiento_x_fecha_vencimiento_Sort"] <> "") { @$_SESSION["vencimiento_x_fecha_vencimiento_Sort"] = ""; }
		}

		// Field importe
		if ($sOrder == "importe") {
			$sSortField = "`importe`";
			$sLastSort = @$_SESSION["vencimiento_x_importe_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vencimiento_x_importe_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vencimiento_x_importe_Sort"] <> "") { @$_SESSION["vencimiento_x_importe_Sort"] = ""; }
		}

		// Field interes
		if ($sOrder == "interes") {
			$sSortField = "`interes`";
			$sLastSort = @$_SESSION["vencimiento_x_interes_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vencimiento_x_interes_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vencimiento_x_interes_Sort"] <> "") { @$_SESSION["vencimiento_x_interes_Sort"] = ""; }
		}

		// Field interes_moratorio
		if ($sOrder == "interes_moratorio") {
			$sSortField = "`interes_moratorio`";
			$sLastSort = @$_SESSION["vencimiento_x_interes_moratorio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vencimiento_x_interes_moratorio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vencimiento_x_interes_moratorio_Sort"] <> "") { @$_SESSION["vencimiento_x_interes_moratorio_Sort"] = ""; }
		}
		$_SESSION["vencimiento_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["vencimiento_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["vencimiento_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
			$sSortField = "`vencimiento_id`";
			$sLastSort = @$_SESSION["vencimiento_x_vencimiento_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vencimiento_x_vencimiento_id_Sort"] = $sThisSort;
		
//		$_SESSION["vencimiento_OrderBy"] = $sOrderBy;
	}
}

//-------------------------------------------------------------------------------
// Function SetUpStartRec
//- Set up Starting Record parameters based on Pager Navigation
// - Variables setup: nStartRec

function SetUpStartRec()
{

	// Check for a START parameter
	global $nStartRec;
	global $nDisplayRecs;
	global $nTotalRecs;
	if (strlen(@$_GET["start"]) > 0) {
		$nStartRec = @$_GET["start"];
		$_SESSION["vencimiento_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["vencimiento_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["vencimiento_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["vencimiento_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["vencimiento_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["vencimiento_REC"] = $nStartRec;
		}
	}
}

//-------------------------------------------------------------------------------
// Function ResetCmd
// - Clear list page parameters
// - RESET: reset search parameters
// - RESETALL: reset search & master/detail parameters
// - RESETSORT: reset sort parameters

function ResetCmd()
{

	// Get Reset Cmd
	if (strlen(@$_GET["cmd"]) > 0) {
		$sCmd = @$_GET["cmd"];

		// Reset Search Criteria
		if (strtoupper($sCmd) == "RESET") {
			$sSrchWhere = "";
			$_SESSION["vencimiento_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["vencimiento_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["vencimiento_OrderBy"] = $sOrderBy;
			if (@$_SESSION["vencimiento_x_vencimiento_id_Sort"] <> "") { $_SESSION["vencimiento_x_vencimiento_id_Sort"] = ""; }
			if (@$_SESSION["vencimiento_x_credito_id_Sort"] <> "") { $_SESSION["vencimiento_x_credito_id_Sort"] = ""; }
			if (@$_SESSION["vencimiento_x_vencimiento_status_id_Sort"] <> "") { $_SESSION["vencimiento_x_vencimiento_status_id_Sort"] = ""; }
			if (@$_SESSION["vencimiento_x_fecha_vencimiento_Sort"] <> "") { $_SESSION["vencimiento_x_fecha_vencimiento_Sort"] = ""; }
			if (@$_SESSION["vencimiento_x_importe_Sort"] <> "") { $_SESSION["vencimiento_x_importe_Sort"] = ""; }
			if (@$_SESSION["vencimiento_x_interes_Sort"] <> "") { $_SESSION["vencimiento_x_interes_Sort"] = ""; }
			if (@$_SESSION["vencimiento_x_interes_moratorio_Sort"] <> "") { $_SESSION["vencimiento_x_interes_moratorio_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["vencimiento_REC"] = $nStartRec;
	}
}
?>


