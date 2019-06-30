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
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=cobranza.xls');
}
if ($sExport == "word") {
	header('Content-Type: application/vnd.ms-word');
	header('Content-Disposition: attachment; filename=cobranza.doc');
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


$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

// Handle Reset Command
ResetCmd();

// Build SQL
if($_SESSION["php_project_esf_status_UserRolID"] == 7) {
	$sSql = "SELECT credito.credito_num, credito.credito_tipo_id, solicitud.solicitud_id, credito.credito_id FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id ";
}else{
	$sSql = "SELECT credito.credito_num, credito.credito_tipo_id, solicitud.solicitud_id, credito.credito_id FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id ";
}

// Load Default Filter
$sDefaultFilter = "";
$sGroupBy = "";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = "";



if($_POST["x_fecha_desde"]){
	$_SESSION["x_fecha_desde"] = $_POST["x_fecha_desde"];
	$_SESSION["x_fecha_hasta"] = $_POST["x_fecha_hasta"];		
}else{
	if(empty($_SESSION["x_fecha_desde"])){
		$_SESSION["x_fecha_desde"] = $currdate;
		$_SESSION["x_fecha_hasta"] = $currdate;		
	}
}

$x_promo_srch = $_POST["x_promo_srch"];
if(!empty($x_promo_srch)){
	if($x_promo_srch < 1000){
		$_SESSION["x_promo_srch"] = $x_promo_srch;
	}else{
		$_SESSION["x_promo_srch"] = "";
	}
}


$x_sucursal_srch = $_POST["x_sucursal_srch"];
if(!empty($x_sucursal_srch)){
	if($x_sucursal_srch < 1000){
		$_SESSION["x_sucursal_srch"] = $x_sucursal_srch;
	}else{
		$_SESSION["x_sucursal_srch"] = "";
	}
}


$x_empresa_id = $_POST["x_empresa_id"];
if(!empty($x_empresa_id)){
	$_SESSION["x_empresa_id"] = $x_empresa_id;
}else{
	if(strlen($x_posteo) > 0){
		$_SESSION["x_empresa_id"] = "";
	}
}

if(!empty($_POST["x_credito_tipo_id"])){
	$_SESSION["x_credito_tipo_id"] = $_POST["x_credito_tipo_id"];
}else{
	if(empty($_SESSION["x_credito_tipo_id"])){
		$_SESSION["x_credito_tipo_id"] = 1;
	}
}


$sDbWhere = "(credito.credito_status_id = 1) AND ";

$sDbWhere .= "(credito.credito_id in (SELECT distinct(credito_id) from vencimiento where vencimiento_status_id = 3)) AND ";


$sDbWhere .= "(credito.credito_tipo_id = ".$_SESSION["x_credito_tipo_id"].") AND ";

// Build WHERE condition
if($_SESSION["php_project_esf_status_UserRolID"] == 7) {
	$sDbWhere .= "(solicitud.promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"]. ") AND ";
}else{
//	$sDbWhere .= "(credito.credito_status_id = 1) AND";
}

if(!empty($_SESSION["x_promo_srch"])){
	$sDbWhere .= "(solicitud.promotor_id = ".$_SESSION["x_promo_srch"].") AND ";
}


if(!empty($_SESSION["x_sucursal_srch"])){
	if(!empty($_SESSION["x_sucursal_srch"]) && ($_SESSION["x_sucursal_srch"] != "1000")){
		$sDbWhere .= "(promotor.sucursal_id = ".$_SESSION["x_sucursal_srch"].") AND ";

	}
}


if(!empty($_SESSION["x_empresa_id"])){
	if(!empty($_SESSION["x_empresa_id"]) && ($_SESSION["x_empresa_id"] != "999999999")){
		$sDbWhere .= "(credito.credito_id in (select credito_id from  fondeo_colocacion join fondeo_credito on fondeo_credito.fondeo_credito_id = fondeo_colocacion.fondeo_credito_id join fondeo_empresa on fondeo_empresa.fondeo_empresa_id = fondeo_credito.fondeo_empresa_id where fondeo_empresa.fondeo_empresa_id = ".$_SESSION["x_empresa_id"].")) AND ";
	}
}


if ($sDbWhereDetail <> "") {
	$sDbWhere .= "(" . $sDbWhereDetail . ") AND ";
}
if ($sSrchWhere <> "") {
	$sDbWhere .= "(" . $sSrchWhere . ") AND ";
}
if (strlen($sDbWhere) > 5) {
	$sDbWhere = substr($sDbWhere, 0, strlen($sDbWhere)-5); // Trim rightmost AND
}
/*
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
*/
$sSql .= " WHERE " . $sDbWhere;
$sSql .= " ORDER BY credito.credito_id ASC ";	

//echo $sSql; // Uncomment to show SQL for debugging
?>
<?php include ("header.php") ?>
<?php if ($sExport == "") { ?>
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/skins/aqua/theme.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-es.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<script type="text/javascript" src="ew.js"></script>
<?php } ?>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<?php if ($sExport == "") { ?>
<script type="text/javascript" src="utilerias/datefunc.js"></script>
<?php } ?>
<script type="text/javascript">
<!--
function filtrar(){
EW_this = document.filtro;
validada = true;

	if (validada && EW_this.x_fecha_desde && !EW_hasValue(EW_this.x_fecha_desde, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_fecha_desde, "TEXT", "La fecha desde es requerida."))
			validada = false;
	}

	if (validada && EW_this.x_fecha_hasta && !EW_hasValue(EW_this.x_fecha_hasta, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_fecha_hasta, "TEXT", "La fecha hasta es requerida."))
			validada = false;
	}

	if(validada == true){
		if (!compareDates(EW_this.x_fecha_desde.value, EW_this.x_fecha_hasta.value)) {	
			if (!EW_onError(EW_this, EW_this.x_fecha_desde, "TEXT", "La fecha Desde no puede ser menor a la fecha hasta."))
				validada = false; 
		}
	}

	if(validada == true){
		EW_this.submit();
	}
}
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
<p><span class="phpmaker">
<?php if ($sExport == "") { ?>
CAPITAL EN RIESGO
<?php }else{ ?>
<b>
CAPITAL EN RIESGO (Peri&oacute;do del: <?php echo FormatDateTime($_SESSION["x_fecha_desde"],7); ?> al: <?php echo FormatDateTime($_SESSION["x_fecha_hasta"],7); ?>)
</b>
<?php } ?>
<?php if ($sExport == "") { ?>
<?php if($_SESSION["x_fecha_desde"] != ""){ ?>
&nbsp;&nbsp;<a href="php_rpt_capital_riesgo.php?export=excel&x_fecha_desde=<?php echo $x_fecha_desde; ?>&x_fecha_hasta=<?php echo $x_fecha_hasta; ?>">Exportar a Excel</a>
<?php }else{ ?>
&nbsp;&nbsp;<a href="php_rpt_capital_riesgo.php?export=excel">Exportar a Excel</a>
<?php } ?>
<?php } ?>
</span></p>
<?php if ($sExport == "") { ?>
<p>
<?php } ?>
<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="phpmaker" style="color: Red;"><?php echo $_SESSION["ewmsg"]; ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>

<?php if ($sExport == "") { ?>

<form action="php_rpt_capital_riesgo.php" name="filtro" id="filtro" method="post">
<table class="phpmaker">
	<tr>
	  <td>Tipo de Credito</td>
	  <td><input name="x_credito_tipo_id" type="radio" id="x_credito_tipo_id" value="1" <?php if($_SESSION["x_credito_tipo_id"] == 1){ echo "checked='checked'"; }?> onclick="javascript: document.filtro.submit();" />
Personal</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td><input type="radio" name="x_credito_tipo_id" id="x_credito_tipo_id2" value="2"  onclick="javascript: document.filtro.submit();"/ <?php if($_SESSION["x_credito_tipo_id"] == 2){ echo "checked='checked'"; }?> />
Grupo </td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>
      
      
      <!---
      
	<tr>
	  <td><span class="phpmaker">
	    Desde:
	    </span>
	    </td>
	  <td><span>
	    <input type="text" name="x_fecha_desde" id="x_fecha_desde" value="<?php echo FormatDateTime(@$_SESSION["x_fecha_desde"],7); ?>">
	    &nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_desde" alt="Pick a Date" style="cursor:pointer;cursor:hand;">
	    <script type="text/javascript">
		Calendar.setup(
		{
		inputField : "x_fecha_desde", // ID of the input field
		ifFormat : "%d/%m/%Y", // the date format
		button : "cx_fecha_desde" // ID of the button
		}
		);
		</script>
	    </span>		
	    </td>
	  <td><span class="phpmaker">
	    Hasta:
	    </span>
	    </td>
	  <td><span>
	    <input type="text" name="x_fecha_hasta" id="x_fecha_hasta" value="<?php echo FormatDateTime(@$_SESSION["x_fecha_hasta"],7); ?>">
	    &nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_hasta" alt="Pick a Date" style="cursor:pointer;cursor:hand;">
	    <script type="text/javascript">
		Calendar.setup(
		{
		inputField : "x_fecha_hasta", // ID of the input field
		ifFormat : "%d/%m/%Y", // the date format
		button : "cx_fecha_hasta" // ID of the button
		}
		);
		</script>
	    </span>		
	    </td>		
	  <td>&nbsp;</td>		
	  <td>&nbsp;</td>		
	  </tr>
      
      --->
      
	<tr>
	  <td>Fondo:</td>
	  <td><?php
$x_empresa_dependiente_idList = "<select name=\"x_empresa_id\" class=\"texto_normal\">";
$x_empresa_dependiente_idList .= "<option value=''>Seleccione</option>";

$sSqlWrk = "SELECT fondeo_credito_id, credito_num, fondeo_credito.fondeo_empresa_id,  fondeo_empresa.nombre, fondeo_credito.importe FROM fondeo_credito join fondeo_empresa on fondeo_empresa.fondeo_empresa_id = fondeo_credito.fondeo_empresa_id order by fondeo_credito.fondeo_empresa_id ";

$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_empresa_dependiente_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["fondeo_empresa_id"] == @$x_empresa_id) {
			$x_empresa_dependiente_idList .= "' selected";
		}
		
		if(strtoupper($datawrk["nombre"]) == "FONDOS PROPIOS"){
			$x_empresa_dependiente_idList .= ">" . $datawrk["nombre"] . "</option>";
		}else{
			$x_empresa_dependiente_idList .= ">" . $datawrk["nombre"] . " Credito No.: " . $datawrk["credito_num"] . "</option>";
		}
		
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_empresa_dependiente_idList .= "</select>";
echo $x_empresa_dependiente_idList;
?></td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>
	<tr>
	  <td>Promotor:</td>
	  <td><?php
		$x_estado_civil_idList = "<select name=\"x_promo_srch\" class=\"texto_normal\">";
		if($_SESSION["php_project_esf_status_UserRolID"] == 7) {
			$sSqlWrk = "SELECT promotor_id, nombre_completo FROM promotor Where promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"];
		}else{
			$sSqlWrk = "SELECT `promotor_id`, `nombre_completo` FROM `promotor`";	
			$x_estado_civil_idList .= "<option value=\"1000\">TODOS</option>";	
		}		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["promotor_id"] == $_SESSION["x_promo_srch"]) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["nombre_completo"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?></td>
	  <td>Sucursal:</td>
	  <td><?php
		$x_estado_civil_idList = "<select name=\"x_sucursal_srch\" class=\"texto_normal\">";
		if($_SESSION["php_project_esf_status_UserRolID"] == 7) {
			$sSqlWrk = "SELECT sucursal_id, nombre FROM sucursal join promotor on promotor.sucursal_id = sucursal.sucursal_id Where promotor.promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"];
		}else{
			$sSqlWrk = "SELECT sucursal_id, nombre FROM sucursal ";	
			$x_estado_civil_idList .= "<option value=\"1000\">TODOS</option>";	
		}		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["sucursal_id"] == $_SESSION["x_sucursal_srch"]) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["nombre"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?></td>
	  <td><input type="button"  value="Filtrar" name="filtro" onclick="filtrar();"  /></td>
	  <td><a href="php_rpt_capital_riesgo.php?cmd=reset">Mostrar Todos</a></td>
	  </tr>
</table>


<?php } ?>


<?php if ($sExport == "") { ?>
<form action="php_rpt_capital_riesgo.php" name="ewpagerform" id="ewpagerform">
<table class="ewTablePager">
	<tr>
		<td nowrap>
<span class="phpmaker">
<?php

// Display page numbers
if ($nTotalRecs > 0) {
	$rsEof = ($nTotalRecs < ($nStartRec + $nDisplayRecs));
	if ($nTotalRecs > $nDisplayRecs) {

		// Find out if there should be Backward or Forward Buttons on the TABLE.
		if ($nStartRec == 1) {
			$isPrev = False;
		} else {
			$isPrev = True;
			$PrevStart = $nStartRec - $nDisplayRecs;
			if ($PrevStart < 1) { $PrevStart = 1; } ?>
		<a href="php_rpt_capital_riesgo.php?start=<?php echo $PrevStart; ?>"><b>Anterior</b></a>
		<?php
		}
		if ($isPrev || (!$rsEof)) {
			$x = 1;
			$y = 1;
			$dx1 = intval(($nStartRec-1)/($nDisplayRecs*$nRecRange))*$nDisplayRecs*$nRecRange+1;
			$dy1 = intval(($nStartRec-1)/($nDisplayRecs*$nRecRange))*$nRecRange+1;
			if (($dx1+$nDisplayRecs*$nRecRange-1) > $nTotalRecs) {
				$dx2 = intval($nTotalRecs/$nDisplayRecs)*$nDisplayRecs+1;
				$dy2 = intval($nTotalRecs/$nDisplayRecs)+1;
			} else {
				$dx2 = $dx1+$nDisplayRecs*$nRecRange-1;
				$dy2 = $dy1+$nRecRange-1;
			}
			while ($x <= $nTotalRecs) {
				if (($x >= $dx1) && ($x <= $dx2)) {
					if ($nStartRec == $x) { ?>
		<b><?php echo $y; ?></b>
					<?php } else { ?>
		<a href="php_rpt_capital_riesgo.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
					<?php }
					$x += $nDisplayRecs;
					$y += 1;
				} elseif (($x >= ($dx1-$nDisplayRecs*$nRecRange)) && ($x <= ($dx2+$nDisplayRecs*$nRecRange))) {
					if ($x+$nRecRange*$nDisplayRecs < $nTotalRecs) { ?>
		<a href="php_rpt_capital_riesgo.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo ($y+$nRecRange-1);?></b></a>
					<?php } else {
						$ny=intval(($nTotalRecs-1)/$nDisplayRecs)+1;
							if ($ny == $y) { ?>
		<a href="php_rpt_capital_riesgo.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
							<?php } else { ?>
		<a href="php_rpt_capital_riesgo.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo $ny; ?></b></a>
							<?php }
					}
					$x += $nRecRange*$nDisplayRecs;
					$y += $nRecRange;
				} else {
					$x += $nRecRange*$nDisplayRecs;
					$y += $nRecRange;
				}
			}
		}

		// Next link
		if (!$rsEof) {
			$NextStart = $nStartRec + $nDisplayRecs;
			$isMore = True;  ?>
		<a href="php_rpt_capital_riesgo.php?start=<?php echo $NextStart; ?>"><b>Siguiente</b></a>
		<?php } else {
			$isMore = False;
		} ?>
		<br>
<?php	}
	if ($nStartRec > $nTotalRecs) { $nStartRec = $nTotalRecs; }
	$nStopRec = $nStartRec + $nDisplayRecs - 1;
	$nRecCount = $nTotalRecs - 1;
	if ($rsEof) { $nRecCount = $nTotalRecs; }
	if ($nStopRec > $nRecCount) { $nStopRec = $nRecCount; } ?>
	Registros <?php echo  $nStartRec;  ?> al <?php  echo $nStopRec; ?> de <?php echo  $nTotalRecs; ?>
<?php } else { ?>
	No hay datos
<?php }?>
</span>
		</td>
	</tr>
</table>
</form>
<?php } ?>
<?php if ($nTotalRecs > 0)  { ?>
<form method="post">
<table id="ewlistmain" class="ewTable">
	<!-- Table header -->
	<tr class="ewTableHeader">
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
<b>Crédito Num.</b>
<?php }else{ ?>
Crédito Num.
<?php } ?>
		</span></td>

		<td valign="top">
		  Fondo        
		  </td>        
        
		<td valign="top"><span>
  <?php if ($sExport <> "") { ?>
  <b>Promotor</b>
  <?php }else{ ?>
		  Promotor
  <?php } ?>
		  </span></td>				
<td><span>
  <?php if ($sExport <> "") { ?>
  <b>Cliente</b>
  <?php }else{ ?>
  Cliente
  <?php } ?>
</span></td>
<td valign="top"><span>
  <?php if ($sExport <> "") { ?>
  <b>Importe</b>
  <?php }else{ ?>
  Importe
  <?php } ?>
</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
<b>Interés</b>
<?php }else{ ?>
Interés
<?php } ?>
		</span></td>
		<td valign="top">IVA</td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
<b>Moratorios</b>
<?php }else{ ?>
Moratorios
<?php } ?>
		</span></td>
		<td>IVA M.</td>
<td><span>
<?php if ($sExport <> "") { ?>
<b>Total</b>
<?php }else{ ?>
Total
<?php } ?>
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
$nRecActual = 0;

$x_total_pago = 0;
$x_total_importe = 0;
$x_total_interes = 0;
$x_total_iva = 0;
$x_total_iva_mor = 0;
$x_total_moratorios = 0;
$x_total_total = 0;

while (($row = @phpmkr_fetch_array($rs)) && ($nRecCount < $nStopRec)) {
	$nRecCount = $nRecCount + 1;
	
	if ($nRecCount >= $nStartRec) {
		$nRecActual++;

		// Set row color
		$sItemRowClass = " class=\"ewTableRow\"";
		$sListTrJs = " onmouseover='ew_mouseover(this);' onmouseout='ew_mouseout(this);' onclick='ew_click(this);'";

		// Display alternate color for rows
		if ($nRecCount % 2 <> 1) {
			$sItemRowClass = " class=\"ewTableAltRow\"";
		}

	$x_credito_id = $row["credito_id"];
	$x_credito_tipo_id = $row["credito_tipo_id"];		
	$x_credito_num = $row["credito_num"];		
	$x_solicitud_id = $row["solicitud_id"];		


	

	$sSqlWrk = "SELECT vencimiento.credito_id, sum(vencimiento.importe) as importe, sum(vencimiento.interes) as interes, sum(vencimiento.iva) as iva, sum(vencimiento.interes_moratorio) as interes_moratorio, sum(vencimiento.iva_mor) as iva_mor, sum(vencimiento.total_venc) as total_venc FROM vencimiento where credito_id = $x_credito_id and vencimiento_status_id in (1,3) ";
/*
	if($_SESSION["x_fecha_desde"] != ""){
		$sSqlWrk .= " AND (vencimiento.fecha_vencimiento >= '".ConvertDateToMysqlFormat($_SESSION["x_fecha_desde"])."') AND  (vencimiento.fecha_vencimiento <= '".ConvertDateToMysqlFormat($_SESSION["x_fecha_hasta"])."') ";	
	}
*/
	$sSqlWrk .= " GROUP BY vencimiento.credito_id ";

	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$datawrk = phpmkr_fetch_array($rswrk);

	$x_importe = $datawrk["importe"];
	$x_interes = $datawrk["interes"];
	$x_interes_moratorio = $datawrk["interes_moratorio"];
	$x_iva = $datawrk["iva"];		
	$x_iva_mor = $datawrk["iva_mor"];				

	@phpmkr_free_result($rswrk);



	if(empty($x_iva)){
		$x_iva = 0;
	}
	if(empty($x_iva_mor)){
		$x_iva_mor = 0;
	}


	$x_total_pago = $x_importe + $x_interes + $x_interes_moratorio + $x_iva + $x_iva_mor;
	$x_total_importe = $x_total_importe + $x_importe;
	$x_total_interes = $x_total_interes + $x_interes;
	$x_total_iva = $x_total_iva + $x_iva;		
	$x_total_moratorios = $x_total_moratorios + $x_interes_moratorio;
	$x_total_iva_mor = $x_total_iva_mor + $x_iva_mor;				
	$x_total_total = $x_total_total + $x_total_pago;




?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>
		<!-- vencimiento_id -->
		<td align="right"><span>
<?php echo $x_credito_num; ?>
</span></td>

		<td align="left"><span>
<?php 


$sSqlWrk = "select fondeo_empresa.nombre from  fondeo_colocacion join fondeo_credito on fondeo_credito.fondeo_credito_id = fondeo_colocacion.fondeo_credito_id join fondeo_empresa on fondeo_empresa.fondeo_empresa_id = fondeo_credito.fondeo_empresa_id where fondeo_colocacion.credito_id = $x_credito_id ";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	$x_fondo = $rowwrk["nombre"];								
}else{
	$x_fondo = "Propio";										
}
@phpmkr_free_result($rswrk);

echo $x_fondo; 
?>
</span></td>


		<!-- credito_id -->
		<td align="left"><span>
  <?php
$sSqlWrk = "SELECT promotor.nombre_completo
FROM solicitud join promotor
on promotor.promotor_id = solicitud.promotor_id
where solicitud.solicitud_id = $x_solicitud_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	$sTmp = $rowwrk["nombre_completo"];
}else{
	$sTmp = "Datos no localizados";
}
echo $sTmp;
@phpmkr_free_result($rswrk);
?>
</span></td>
		<td align="left"><span>
  <?php 
		if($x_credito_tipo_id == 1){
			$sSqlWrk = "SELECT cliente.nombre_completo as cliente_nombre, cliente.apellido_paterno, cliente.apellido_materno FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id Where credito.credito_id = $x_credito_id ";
		}else{
			$sSqlWrk = "SELECT solicitud.grupo_nombre as cliente_nombre FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id Where credito.credito_id = $x_credito_id ";
		}

		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
			$x_cliente = $rowwrk["cliente_nombre"]." ".$rowwrk["apellido_paterno"]." ".$rowwrk["apellido_materno"];								
		}else{
			$x_cliente = "";										
		}
		@phpmkr_free_result($rswrk);
		echo $x_cliente;
?>
</span></td>
		<!-- vencimiento_status_id -->
		<!-- fecha_vencimiento -->
		<!-- importe -->
		<td align="right"><span>
<?php echo (is_numeric($x_importe)) ? FormatNumber($x_importe,2,0,0,1) : $x_importe; ?>
</span></td>
		<!-- interes -->
		<td align="right"><span>
<?php echo (is_numeric($x_interes)) ? FormatNumber($x_interes,2,0,0,1) : $x_interes; ?>
</span></td>
		<td align="right"><?php echo (is_numeric($x_iva)) ? FormatNumber($x_iva,2,0,0,1) : $x_iva; ?></td>
		<!-- interes_moratorio -->
		<td align="right"><span>
<?php echo (is_numeric($x_interes_moratorio)) ? FormatNumber($x_interes_moratorio,2,0,0,1) : $x_interes_moratorio; ?>
</span></td>
		<td align="right"><?php echo (is_numeric($x_iva_mor)) ? FormatNumber($x_iva_mor,2,0,0,1) : $x_iva_mor; ?></td>
		<td align="right"><span>
<?php echo (is_numeric($x_total_pago)) ? FormatNumber($x_total_pago,2,0,0,1) : $x_total_pago; ?>
</span></td>
	</tr>
    
<?php

	}
	
}
?>

<tr>
<td></td>
<td></td>

<td></td>
<td></td>
<td align="right"><span>
  <b>
    <?php echo FormatNumber($x_total_importe,2,0,0,1); ?>
    </b>
</span></td>
<td align="right"><span>
<b>
<?php echo FormatNumber($x_total_interes,2,0,0,1); ?>
</b>
</span></td>

<td align="right"><span>
<b>
<?php echo FormatNumber($x_total_iva,2,0,0,1); ?>
</b>
</span></td>

<td align="right"><span>
<b>
<?php echo FormatNumber($x_total_moratorios,2,0,0,1); ?>
</b>
</span></td>

<td align="right"><span>
<b>
<?php echo FormatNumber($x_total_iva_mor,2,0,0,1); ?>
</b>
</span></td>
<td align="right"><span>
<b>
<?php echo FormatNumber($x_total_total,2,0,0,1); ?>
</b>
</span></td>
</tr>


</table>
<?php if ($sExport == "") { ?>
<?php if ($nRecActual > 0) { ?>
<?php } ?>
<?php } ?>
</form>
<?php } ?>
<?php

// Close recordset and connection
phpmkr_free_result($rs);
phpmkr_db_close($conn);
?>
<?php if ($sExport <> "word" && $sExport <> "excel") { ?>
<?php include ("footer.php") ?>
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
		$_SESSION["vencimiento_OrderBy"] = $sOrderBy;
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
			$_SESSION["x_fecha_desde"] = "";
			$_SESSION["x_fecha_hasta"] = "";		
			$_SESSION["x_promo_srch"] = 0;	
			$_SESSION["x_empresa_id"] = "";		
			$_SESSION["x_credito_tipo_id"] = "";			
			$sSrchWhere = "";
			$_SESSION["vencimiento_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$_SESSION["x_fecha_desde"] = "";
			$_SESSION["x_fecha_hasta"] = "";		
			$_SESSION["x_promo_srch"] = 0;		
			$_SESSION["x_empresa_id"] = "";		
			$_SESSION["x_credito_tipo_id"] = "";			
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
