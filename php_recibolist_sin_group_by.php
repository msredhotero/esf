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
$x_recibo_id = Null; 
$ox_recibo_id = Null;
$x_vencimiento_id = Null; 
$ox_vencimiento_id = Null;
$x_medio_pago_id = Null; 
$ox_medio_pago_id = Null;
$x_referencia_pago = Null; 
$ox_referencia_pago = Null;
$x_fecha_registro = Null; 
$ox_fecha_registro = Null;
$x_importe = Null; 
$ox_importe = Null;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=recibo.xls');
}
if ($sExport == "word") {
	header('Content-Type: application/vnd.ms-word');
	header('Content-Disposition: attachment; filename=recibo.doc');
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
$nDisplayRecs = 100;
$nRecRange = 10;

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

// Handle Reset Command
ResetCmd();


// Build SQL
if($_SESSION["php_project_esf_status_UserRolID"] == 7) {
	$sSql = "SELECT recibo.* FROM recibo join recibo_vencimiento
	on recibo_vencimiento.recibo_id = recibo.recibo_id join vencimiento
	on vencimiento.vencimiento_id = recibo_vencmimiento.vencimimento_id join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id ";
}else{
	$sSql = "SELECT recibo.*, credito.credito_num, credito.cliente_num FROM recibo join recibo_vencimiento 
on recibo.recibo_id = recibo_vencimiento.recibo_id join vencimiento 
on vencimiento.vencimiento_id = recibo_vencimiento.vencimiento_id join credito
on credito.credito_id = vencimiento.credito_id ";
}


// Get Search Criteria for Basic Search
SetUpBasicSearch();

// Build Search Criteria
if ($sSrchAdvanced != "") {
	$sSrchWhere = $sSrchAdvanced; // Advanced Search
}
elseif ($sSrchBasic != "") {
	$sSrchWhere = $sSrchBasic; // Basic Search
}

// Save Search Criteria
if ($sSrchWhere != "") {
	$_SESSION["recibo_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["recibo_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["recibo_searchwhere"];
}

// Build SQL


//	$sSql = "SELECT * FROM recibo ";


// Load Default Filter
$sDefaultFilter = "";
$sGroupBy = "";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = "";

// Build WHERE condition

if($_SESSION["php_project_esf_status_UserRolID"] == 7) {
	$sDbWhere = "(solicitud.promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"]. ") AND ";
}

if($_POST["x_fecha_desde"]){
	$_SESSION["x_fecha_desde"] = $_POST["x_fecha_desde"];
	$_SESSION["x_fecha_hasta"] = $_POST["x_fecha_hasta"];		
}else{
	if(empty($_SESSION["x_fecha_desde"])){
		$_SESSION["x_fecha_desde"] = $currdate;
		$_SESSION["x_fecha_hasta"] = $currdate;		
	}
}

if($_POST["x_credito_num_filtro"]){
	$_SESSION["x_credito_num_filtro"] = $_POST["x_credito_num_filtro"];

}else{
	if(empty($_SESSION["x_credito_num_filtro"])){
		$_SESSION["x_credito_num_filtro"] = "";
	}
}
	
if($_POST["x_recibo_status_id"]){
	$_SESSION["x_recibo_status_id"] = $_POST["x_recibo_status_id"];

}else{
	if(empty($_SESSION["x_recibo_status_id"])){
		$_SESSION["x_recibo_status_id"] = 0;
	}
}

if($_SESSION["x_fecha_desde"] != ""){
	$sDbWhere .= "(recibo.fecha_pago >= '".ConvertDateToMysqlFormat($_SESSION["x_fecha_desde"])."') AND ";
	$sDbWhere .= "(recibo.fecha_pago <= '".ConvertDateToMysqlFormat($_SESSION["x_fecha_hasta"])."') AND ";	
}

if($_SESSION["x_credito_num_filtro"] != ""){
	$sDbWhere .= "(credito.credito_num = '".$_SESSION["x_credito_num_filtro"]."') AND ";
}

if($_SESSION["x_recibo_status_id"] > 0){
	$sDbWhere .= "(recibo.recibo_status_id = ".$_SESSION["x_recibo_status_id"].") AND ";
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

//$sSql .= " GROUP BY recibo.fecha_pago desc ";
	
// Set Up Sorting Order
$sOrderBy = "";
SetUpSortOrder();
if ($sOrderBy != "") {
	$sSql .= " ORDER BY " . $sOrderBy;
}

//echo $sSql; // Uncomment to show SQL for debugging
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>
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
<?php if ($sExport == "") { ?>
<script type="text/javascript" src="utilerias/datefunc.js"></script>
<?php } ?>

<script type="text/javascript">
<!--
function filtrar(){
EW_this = document.filtro;
validada = true;
/*
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
*/
	if(validada == true){
		EW_this.submit();
	}
}
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
<p><span class="phpmaker">PAGOS
<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="php_recibolist.php?export=excel">Exportar a Excel</a><?php } ?>
</span></p>
<?php if ($sExport == "") { ?>
<form action="php_recibolist.php">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><span class="phpmaker">
			<input type="text" name="psearch" size="20">
			<input type="Submit" name="Submit" value="Buscar &nbsp;(*)">
			&nbsp;&nbsp;
			<a href="php_recibolist.php?cmd=reset">Mostrar todos</a>&nbsp;&nbsp;
		</span></td>
	</tr>
	<tr><td><span class="phpmaker"><input type="radio" name="psearchtype" value="" checked>
	Frase Exacta&nbsp;&nbsp;
	<input type="radio" name="psearchtype" value="AND">
	Todas las palabras&nbsp;
	<input type="radio" name="psearchtype" value="OR">
	cualquier palabra</span></td>
	</tr>
</table>
</form>
<?php } ?>
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

<form action="php_recibolist.php" name="filtro" id="filtro" method="post">
<table width="660" class="phpmaker">
	<tr>
	  <td width="79">credito num:</td>
	  <td width="54"><input name="x_credito_num_filtro" type="text" id="x_credito_num_filtro" value="<?php echo $_SESSION["x_credito_num_filtro"]; ?>" size="10" maxlength="10" /></td>
		<td width="55"><div align="right"><span class="phpmaker">
		  Desde:
		  </span> </div></td>
		<td width="102"><span>
		<input name="x_fecha_desde" type="text" id="x_fecha_desde" value="<?php echo FormatDateTime(@$_SESSION["x_fecha_desde"],7); ?>" size="11">
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
		</span>		</td>
		<td width="41"><div align="right"><span class="phpmaker">
		  Hasta:
		  </span> </div></td>
		<td width="96"><span>
		<input name="x_fecha_hasta" type="text" id="x_fecha_hasta" value="<?php echo FormatDateTime(@$_SESSION["x_fecha_hasta"],7); ?>" size="11">
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
		</span>		</td>		
		<td width="61">&nbsp;</td>		
		<td width="136">&nbsp;</td>		
	</tr>
	<tr>
	  <td>Status:</td>
	  <td colspan="3"><span class="ewTableAltRow">
	    <?php
$x_medio_pago_idList = "<select name=\"x_recibo_status_id\">";
$x_medio_pago_idList .= "<option value=''>Seleccione</option>";
$x_medio_pago_idList .= "<option value='0'>Todos</option>";
$sSqlWrk = "SELECT recibo_status_id, descripcion FROM recibo_status";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_medio_pago_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["recibo_status_id"] == @$_SESSION["x_recibo_status_id"]) {
			$x_medio_pago_idList .= "' selected";
		}
		$x_medio_pago_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_medio_pago_idList .= "</select>";
echo $x_medio_pago_idList;
?>
	  </span></td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td><input type="button"  value="Filtrar" name="filtro" onclick="filtrar();"  /></td>
	  <td><a href="php_recibolist.php?cmd=reset">Mostrar Todos</a></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>
</table>


<?php } ?>


<?php if ($sExport == "") { ?>
<form action="php_recibolist.php" name="ewpagerform" id="ewpagerform">
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
		<a href="php_recibolist.php?start=<?php echo $PrevStart; ?>"><b>Anterior</b></a>
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
		<a href="php_recibolist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
					<?php }
					$x += $nDisplayRecs;
					$y += 1;
				} elseif (($x >= ($dx1-$nDisplayRecs*$nRecRange)) && ($x <= ($dx2+$nDisplayRecs*$nRecRange))) {
					if ($x+$nRecRange*$nDisplayRecs < $nTotalRecs) { ?>
		<a href="php_recibolist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo ($y+$nRecRange-1);?></b></a>
					<?php } else {
						$ny=intval(($nTotalRecs-1)/$nDisplayRecs)+1;
							if ($ny == $y) { ?>
		<a href="php_recibolist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
							<?php } else { ?>
		<a href="php_recibolist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo $ny; ?></b></a>
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
		<a href="php_recibolist.php?start=<?php echo $NextStart; ?>"><b>Siguiente</b></a>
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
<?php if ($sExport == "") { ?>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<?php } ?>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
No
<?php }else{ ?>
	<a href="php_recibolist.php?order=<?php echo urlencode("recibo_id"); ?>">No<?php if (@$_SESSION["recibo_x_recibo_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["recibo_x_recibo_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Status
<?php }else{ ?>
	<a href="php_recibolist.php?order=<?php echo urlencode("recibo_status_id"); ?>">Status<?php if (@$_SESSION["recibo_x_recibo_status_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["recibo_x_recibo_status_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Cr&eacute;dito No.
<?php }else{ ?>
	<a href="php_recibolist.php?order=<?php echo urlencode("credito_num"); ?>">Cr&eacute;dito No.<?php if (@$_SESSION["recibo_x_credito_num_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["recibo_x_credito_num_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>		
		<td valign="top"><span>        
        Venc. Num.
		</span></td>		        
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Cliente No.
<?php }else{ ?>
	<a href="php_recibolist.php?order=<?php echo urlencode("cliente_num"); ?>">Cliente No.<?php if (@$_SESSION["recibo_x_cliente_num_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["recibo_x_cliente_num_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
        

		</span></td>		
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Fecha de registro
<?php }else{ ?>
	<a href="php_recibolist.php?order=<?php echo urlencode("fecha_registro"); ?>">Fecha de registro<?php if (@$_SESSION["recibo_x_fecha_registro_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["recibo_x_fecha_registro_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>		
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Fecha de Pago
<?php }else{ ?>
	<a href="php_recibolist.php?order=<?php echo urlencode("fecha_registro"); ?>">Fecha de Pago<?php if (@$_SESSION["recibo_x_fecha_registro_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["recibo_x_fecha_registro_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>				
		<td valign="top">Banco - Cta.</td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Medio de pago
<?php }else{ ?>
	<a href="php_recibolist.php?order=<?php echo urlencode("medio_pago_id"); ?>">Medio de pago<?php if (@$_SESSION["recibo_x_medio_pago_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["recibo_x_medio_pago_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Referencia de pago
<?php }else{ ?>
	<a href="php_recibolist.php?order=<?php echo urlencode("referencia_pago"); ?>">Referencia de pago<?php if (@$_SESSION["recibo_x_referencia_pago_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["recibo_x_referencia_pago_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
Referencia de pago 2        
		</span></td>                
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Importe
<?php }else{ ?>
	<a href="php_recibolist.php?order=<?php echo urlencode("importe"); ?>">Importe<?php if (@$_SESSION["recibo_x_importe_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["recibo_x_importe_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
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
		$x_recibo_id = $row["recibo_id"];
		$x_recibo_status_id = $row["recibo_status_id"];		
/*		
		$x_folio = $row["folio"];		
		$x_vencimiento_id = $row["vencimiento_id"];
		$x_vencimiento_num = $row["vencimiento_num"];		
*/		
		$x_medio_pago_id = $row["medio_pago_id"];
		$x_banco_id = $row["banco_id"];		
		$x_referencia_pago = $row["referencia_pago"];
		$x_referencia_pago_2 = $row["referencia_pago_2"];		
		$x_fecha_registro = $row["fecha_registro"];
		$x_fecha_pago = $row["fecha_pago"];		
		$x_importe = $row["importe"];
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>
<?php if ($sExport == "") { ?>
<?php if ($x_recibo_status_id == 1) { ?>
<td><span class="phpmaker">
<a href="<?php if ($x_recibo_id <> "") {echo "php_reciboview.php?recibo_id=" . urlencode($x_recibo_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Ver</a>
</span></td>

<td><span class="phpmaker">
<a href="<?php if ($x_recibo_id <> "") {echo "php_reciboedit.php?recibo_id=" . urlencode($x_recibo_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Editar</a>
</span>
</td>
<?php if($_SESSION["php_project_esf_status_UserRolID"] == 1) { ?>
<td><span class="phpmaker"><a href="<?php if ($x_recibo_id <> "") {echo "php_recibo_cancelar.php?recibo_id=" . urlencode($x_recibo_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Cancelar</a></span></td>
<?php }else{ ?>
<td><span class="phpmaker">&nbsp;</span></td>
<?php } ?>
<?php }else{ ?>
<td>
<span class="phpmaker"><a href="<?php if ($x_recibo_id <> "") {echo "php_reciboview.php?recibo_id=" . urlencode($x_recibo_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Ver</a></span>
</span></td>
<td><span class="phpmaker">&nbsp;</span></td>
<td><span class="phpmaker">&nbsp;</span></td>
<?php } ?>
<?php } ?>
		<td><span>
<?php echo $x_recibo_id; ?>
</span></td>
		<!-- medio_pago_id -->
		<td align="center"><span>
<?php
if ((!is_null($x_recibo_status_id)) && ($x_recibo_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `recibo_status`";
	$sTmp = $x_recibo_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `recibo_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_recibo_status_id = $x_recibo_status_id; // Backup Original Value
$x_recibo_status_id = $sTmp;
?>
<?php echo $x_recibo_status_id; ?>
<?php $x_recibo_status_id = $ox_recibo_status_id; // Restore Original Value ?>
</span></td>
		<!-- recibo_id -->
		<td align="center"><span>
<?php 

$sSqlWrk = "SELECT credito.credito_num, credito.cliente_num FROM recibo join recibo_vencimiento 
on recibo.recibo_id = recibo_vencimiento.recibo_id join vencimiento 
on vencimiento.vencimiento_id = recibo_vencimiento.vencimiento_id join credito
on credito.credito_id = vencimiento.credito_id
where recibo.recibo_id = $x_recibo_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	$x_credito_num = $rowwrk["credito_num"];
	$x_cliente_num = $rowwrk["cliente_num"];	
}
@phpmkr_free_result($rswrk);

echo $x_credito_num; 

?>
</span></td>
		<td align="center"><span>
<?php 

$sSqlWrk = "SELECT vencimiento.vencimiento_num FROM recibo join recibo_vencimiento 
on recibo.recibo_id = recibo_vencimiento.recibo_id join vencimiento 
on vencimiento.vencimiento_id = recibo_vencimiento.vencimiento_id 
where recibo.recibo_id = $x_recibo_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$x_vencs_num = "";
while($rowwrk = phpmkr_fetch_array($rswrk)){
	$x_vencs_num .= $rowwrk["vencimiento_num"] . "," ;
}
if($x_vencs_num != ""){
	$x_vencs_num = substr($x_vencs_num, 0, strlen($x_vencs_num)-1);
}
@phpmkr_free_result($rswrk);
echo $x_vencs_num; 
?>
</span></td>

		<!-- vencimiento_id -->
		<td align="center"><span>
<?php

echo $x_cliente_num; 

?>
        
</span></td>                
		<!-- fecha_registro -->
		<td align="center"><span>
<?php echo FormatDateTime($x_fecha_registro,7); ?>
</span></td>
		<!-- fecha_registro -->
		<td align="center"><span>
<?php echo FormatDateTime($x_fecha_pago,7); ?>
</span></td>



		<td><span>
<?php
if ((!is_null($x_banco_id)) && ($x_banco_id <> "")) {
	$sSqlWrk = "SELECT nombre, cuenta FROM banco";
	$sTmp = $x_banco_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE banco_id = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		echo $rowwrk["nombre"] . " - " . $rowwrk["cuenta"];
	}
	@phpmkr_free_result($rswrk);
} 
?>
</span></td>









		<!-- medio_pago_id -->
		<td><span>
<?php
if ((!is_null($x_medio_pago_id)) && ($x_medio_pago_id <> "")) {
	$sSqlWrk = "SELECT descripcion FROM medio_pago where medio_pago_id = $x_medio_pago_id";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_medio_pago_id = $x_medio_pago_id; // Backup Original Value
$x_medio_pago_id = $sTmp;
?>
<?php echo $x_medio_pago_id; ?>
<?php $x_medio_pago_id = $ox_medio_pago_id; // Restore Original Value ?>
</span></td>
		<!-- referencia_pago -->
		<td><span>
<?php echo $x_referencia_pago; ?>
</span></td>
		<td><span>
<?php echo $x_referencia_pago_2; ?>
</span></td>
		<!-- importe -->
		<td align="right"><span>
<?php echo (is_numeric($x_importe)) ? FormatNumber($x_importe,2,0,0,1) : $x_importe; ?>
</span></td>
	</tr>
<?php
	}
}
?>
</table>
<?php if ($sExport == "") { ?>
<?php if ($nRecActual > 0) { ?>
<p>&nbsp;</p>
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
// Function BasicSearchSQL
// - Build WHERE clause for a keyword

function BasicSearchSQL($Keyword)
{
	$sKeyword = (!get_magic_quotes_gpc()) ? addslashes($Keyword) : $Keyword;
	$BasicSearchSQL = "";
	$BasicSearchSQL.= "recibo.referencia_pago LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "solicitud.folio LIKE '%" . $sKeyword . "%' OR ";	
	if (substr($BasicSearchSQL, -4) == " OR ") { $BasicSearchSQL = substr($BasicSearchSQL, 0, strlen($BasicSearchSQL)-4); }
	return $BasicSearchSQL;
}

//-------------------------------------------------------------------------------
// Function SetUpBasicSearch
// - Set up Basic Search parameter based on form elements pSearch & pSearchType
// - Variables setup: sSrchBasic

function SetUpBasicSearch()
{
	global $sSrchBasic;
	$sSearch = (!get_magic_quotes_gpc()) ? addslashes(@$_GET["psearch"]) : @$_GET["psearch"];
	$sSearchType = @$_GET["psearchtype"];
	if ($sSearch <> "") {
		if ($sSearchType <> "") {
			while (strpos($sSearch, "  ") != false) {
				$sSearch = str_replace("  ", " ",$sSearch);
			}
			$arKeyword = split(" ", trim($sSearch));
			foreach ($arKeyword as $sKeyword)
			{
				$sSrchBasic .= "(" . BasicSearchSQL($sKeyword) . ") " . $sSearchType . " ";
			}
		}
		else
		{
			$sSrchBasic = BasicSearchSQL($sSearch);
		}
	}
	if (substr($sSrchBasic, -4) == " OR ") { $sSrchBasic = substr($sSrchBasic, 0, strlen($sSrchBasic)-4); }
	if (substr($sSrchBasic, -5) == " AND ") { $sSrchBasic = substr($sSrchBasic, 0, strlen($sSrchBasic)-5); }
}

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

		// Field recibo_id
		if ($sOrder == "recibo_id") {
			$sSortField = "`recibo_id`";
			$sLastSort = @$_SESSION["recibo_x_recibo_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["recibo_x_recibo_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["recibo_x_recibo_id_Sort"] <> "") { @$_SESSION["recibo_x_recibo_id_Sort"] = ""; }
		}

		// Field recibo_id
		if ($sOrder == "recibo_status_id") {
			$sSortField = "`recibo_status_id`";
			$sLastSort = @$_SESSION["recibo_x_recibo_status_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["recibo_x_recibo_status_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["recibo_x_recibo_status_id_Sort"] <> "") { @$_SESSION["recibo_x_recibo_status_id_Sort"] = ""; }
		}

		// Field recibo_id
		if ($sOrder == "credito_num") {
			$sSortField = "credito_num+0";
			$sLastSort = @$_SESSION["recibo_x_credito_num_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["recibo_x_credito_num_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["recibo_x_credito_num_Sort"] <> "") { @$_SESSION["recibo_x_credito_num_Sort"] = ""; }
		}

		// Field recibo_id
		if ($sOrder == "cliente_num") {
			$sSortField = "cliente_num+0";
			$sLastSort = @$_SESSION["recibo_x_cliente_num_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["recibo_x_cliente_num_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["recibo_x_cliente_num_Sort"] <> "") { @$_SESSION["recibo_x_cliente_num_Sort"] = ""; }
		}




		// Field folio
		if ($sOrder == "folio") {
			$sSortField = "`folio`";
			$sLastSort = @$_SESSION["recibo_x_folio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["recibo_x_folio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["recibo_x_folio_Sort"] <> "") { @$_SESSION["recibo_x_folio_Sort"] = ""; }
		}

		// Field vencimiento_id
		if ($sOrder == "vencimiento_id") {
			$sSortField = "`vencimiento_id`";
			$sLastSort = @$_SESSION["recibo_x_vencimiento_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["recibo_x_vencimiento_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["recibo_x_vencimiento_id_Sort"] <> "") { @$_SESSION["recibo_x_vencimiento_id_Sort"] = ""; }
		}

		// Field medio_pago_id
		if ($sOrder == "medio_pago_id") {
			$sSortField = "`medio_pago_id`";
			$sLastSort = @$_SESSION["recibo_x_medio_pago_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["recibo_x_medio_pago_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["recibo_x_medio_pago_id_Sort"] <> "") { @$_SESSION["recibo_x_medio_pago_id_Sort"] = ""; }
		}

		// Field referencia_pago
		if ($sOrder == "referencia_pago") {
			$sSortField = "`referencia_pago`";
			$sLastSort = @$_SESSION["recibo_x_referencia_pago_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["recibo_x_referencia_pago_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["recibo_x_referencia_pago_Sort"] <> "") { @$_SESSION["recibo_x_referencia_pago_Sort"] = ""; }
		}

		// Field fecha_registro
		if ($sOrder == "fecha_registro") {
			$sSortField = "`fecha_registro`";
			$sLastSort = @$_SESSION["recibo_x_fecha_registro_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["recibo_x_fecha_registro_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["recibo_x_fecha_registro_Sort"] <> "") { @$_SESSION["recibo_x_fecha_registro_Sort"] = ""; }
		}

		// Field importe
		if ($sOrder == "importe") {
			$sSortField = "`importe`";
			$sLastSort = @$_SESSION["recibo_x_importe_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["recibo_x_importe_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["recibo_x_importe_Sort"] <> "") { @$_SESSION["recibo_x_importe_Sort"] = ""; }
		}
		$_SESSION["recibo_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["recibo_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["recibo_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["recibo_OrderBy"] = $sOrderBy;
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
		$_SESSION["recibo_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["recibo_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["recibo_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["recibo_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["recibo_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["recibo_REC"] = $nStartRec;
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
			$_SESSION["x_fecha_desde"] = "";
			$_SESSION["x_fecha_hasta"] = "";		
			$_SESSION["x_credito_num_filtro"] = "";	
			$_SESSION["x_recibo_status_id"] = 0;
			
			$_SESSION["recibo_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["x_fecha_desde"] = "";
			$_SESSION["x_fecha_hasta"] = "";		
			$_SESSION["x_credito_num_filtro"] = "";	
			$_SESSION["x_recibo_status_id"] = 0;
			
			$_SESSION["recibo_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["recibo_OrderBy"] = $sOrderBy;
			if (@$_SESSION["recibo_x_recibo_id_Sort"] <> "") { $_SESSION["recibo_x_recibo_id_Sort"] = ""; }
			if (@$_SESSION["recibo_x_recibo_status_id_Sort"] <> "") { $_SESSION["recibo_x_recibo_status_id_Sort"] = ""; }
			if (@$_SESSION["recibo_x_credito_num_Sort"] <> "") { $_SESSION["recibo_x_credito_num_Sort"] = ""; }
			if (@$_SESSION["recibo_x_cliente_num_Sort"] <> "") { $_SESSION["recibo_x_cliente_num_Sort"] = ""; }
			
			if (@$_SESSION["recibo_x_folio_Sort"] <> "") { $_SESSION["recibo_x_folio_Sort"] = ""; }			
			if (@$_SESSION["recibo_x_vencimiento_id_Sort"] <> "") { $_SESSION["recibo_x_vencimiento_id_Sort"] = ""; }
			if (@$_SESSION["recibo_x_medio_pago_id_Sort"] <> "") { $_SESSION["recibo_x_medio_pago_id_Sort"] = ""; }
			if (@$_SESSION["recibo_x_referencia_pago_Sort"] <> "") { $_SESSION["recibo_x_referencia_pago_Sort"] = ""; }
			if (@$_SESSION["recibo_x_fecha_registro_Sort"] <> "") { $_SESSION["recibo_x_fecha_registro_Sort"] = ""; }
			if (@$_SESSION["recibo_x_importe_Sort"] <> "") { $_SESSION["recibo_x_importe_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["recibo_REC"] = $nStartRec;
	}
}
?>
