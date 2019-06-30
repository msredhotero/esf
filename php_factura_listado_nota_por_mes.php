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
	header('Content-Disposition: attachment; filename=reporte_FACTURAS_MES.xls');
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
 if ($sExport == "") {
$nDisplayRecs = 1000000000;
$nRecRange = 10;
 }else{
	 $nDisplayRecs  = 1000000000;
	 }


// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$filter = array();

$filter['x_credito_tipo_id'] = 100;
$filter['x_nombre_srch'] = '';
$filter['x_apepat_srch'] = '';
$filter['x_apemat_srch'] = '';
$filter['x_clinum_srch'] = '';
$filter['x_cresta_srch'] = '';
$filter['x_entidad_srch'] = '';
$filter['x_delegacion_srch'] = '';
$filter['x_credito_tipo_id'] = 100;
$filter['x_credito_num_filtro'] = '';
$filter['x_fecha_desde'] = '';
$filter['x_fecha_hasta'] = '';
$filter['x_medio_pago_id'] = '';
$filter['x_promo_srch'] = '';
$filter['x_gestor_srch'] = '';
$filter['x_sucursal_srch'] = '';
$filter['x_fecha_desde_2'] = '';
$filter['x_fecha_hasta_2'] = '';
$filter['x_gestor'] = '';


 if ($sExport != "") {
if(isset($_GET)) {	
	foreach($_GET as $key => $value) {
		
		if(isset($filter[$key]))
			 $filter[$key] = $value;
	}
	
}
 }

 if ($sExport == "") {
if(isset($_POST)) {	
	foreach($_POST as $key => $value) {
		if(isset($filter[$key])) $filter[$key] = $value;
	}	
	
	
}
 }

if(!function_exists('http_build_query')) {
    function http_build_query($data,$prefix=null,$sep='',$key='') {
        $ret    = array();
            foreach((array)$data as $k => $v) {
                $k    = urlencode($k);
                if(is_int($k) && $prefix != null) {
                    $k    = $prefix.$k;
                };
                if(!empty($key)) {
                    $k    = $key."[".$k."]";
                };

                if(is_array($v) || is_object($v)) {
                    array_push($ret,http_build_query($v,"",$sep,$k));
                }
                else {
                    array_push($ret,$k."=".urlencode($v));

                };
            };

        if(empty($sep)) {
            $sep = ini_get("arg_separator.output");
        };

        return    implode($sep, $ret);
    };
};


// Handle Reset Command
//ResetCmd();

// Build SQL
$sSql = "SELECT * FROM folio_nota_credito ";
// join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente  on cliente.cliente_id = solicitud_cliente.cliente_id  join promotor

	
	

// Load Default Filter
$sDefaultFilter = "";
$sGroupBy = "";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = "";

// Build WHERE condition




/*
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
}*/
	

if($filter["x_fecha_desde"] != "" || $filter["x_fecha_desde_2"] != ""){
if($filter["x_fecha_desde"] != ""){
	$sDbWhere .= "(fecha >= '".ConvertDateToMysqlFormat($filter["x_fecha_desde"])."') AND ";
	$sDbWhere .= "(fecha <= '".ConvertDateToMysqlFormat($filter["x_fecha_hasta"])."') AND ";	
}

if($filter["x_fecha_desde_2"] != ""){
	$sDbWhere .= "(fecha >= '".ConvertDateToMysqlFormat($filter["x_fecha_desde_2"])."') AND ";
	$sDbWhere .= "(fecha <= '".ConvertDateToMysqlFormat($filter["x_fecha_hasta_2"])."') AND ";	
}
}else{

	$x_fec = date("Y-m-d");
	$x_exp_f = explode("-",$x_fec);
	$x_mes = $x_exp_f["1"];
	$x_year = $x_exp_f["0"];
	$sDbWhere .= "  month(fecha) = $x_mes AND ";
	$sDbWhere .= "  year(fecha) = $x_year AND ";
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

// se agrega  al condicion del jeuridico

$sWhere =  $sWhere .$sDbWhere_juridico;

if ($sWhere != "") {
	$sSql .= " WHERE " . $sWhere;
}
if ($sGroupBy != "") {
	$sSql .= " GROUP BY " . $sGroupBy;
}
if ($sHaving != "") {
	$sSql .= " HAVING " . $sHaving;
}




#echo $sSql; // Uncomment to show SQL for debugging

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
<p><span class="phpmaker">REPORTE DE FACTURAS POR MES
<?php if ($sExport == "") { ?>

<?php if(($_SESSION["php_project_esf_status_UserRolID"] == 1) || ($_SESSION["php_project_esf_status_UserRolID"] == 4)){
?>
&nbsp;&nbsp;<a href="php_factura_listado_factura_por_mes.php?export=excel&x_credito_num_filtro=<?php echo $x_credito_num_filtro; ?>&x_fecha_desde=<?php echo ConvertDateToMysqlFormat(@$filter['x_fecha_desde']); ?>&x_fecha_hasta=<?php echo ConvertDateToMysqlFormat(@$filter['x_fecha_hasta']); ?>&x_gestor=<?php echo $filter["x_gestor"];?>&x_promo_srch=<?php echo $filter["x_promo_srch"];?>&x_sucursal_srch=<?php echo$filter['x_sucursal_srch']  ?>&x_fecha_desde_2=<?php echo ConvertDateToMysqlFormat(@$filter['x_fecha_desde_2']); ?>&x_fecha_hasta_2=<?php echo ConvertDateToMysqlFormat(@$filter['x_fecha_hasta_2']); ?>">Exportar a Excel</a><?php } ?><?php } ?>
</span></p>
<p>
<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="phpmaker" style="color: Red;"><?php echo $_SESSION["ewmsg"]; ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>

<?php if ($sExport == "") { ?>

<form action="php_factura_listado_factura_por_mes.php" name="filtro" id="filtro" method="post">
  <table width="785" border="0" cellpadding="0" cellspacing="0">
  <tr>
  <td><table width="895" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="133">&nbsp;</td>
      <td width="10">&nbsp;</td>
      <td width="174">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="100">&nbsp;</td>
      <td width="13">&nbsp;</td>
      <td width="160">&nbsp;</td>
      <td width="37">&nbsp;</td>
      <td width="115">&nbsp;</td>
      <td width="128">&nbsp;</td>
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
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><div align="right"><span class="phpmaker"> Pago desde: </span></div></td>
      <td>&nbsp;</td>
      <td><span>
        <input name="x_fecha_desde" type="text" id="x_fecha_desde" value="<?php echo FormatDateTime(@$filter['x_fecha_desde'],7); ?>" size="11" />
        &nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_desde" alt="Pick a Date" style="cursor:pointer;cursor:hand;" />
        <script type="text/javascript">
		Calendar.setup(
		{
		inputField : "x_fecha_desde", // ID of the input field
		ifFormat : "%d/%m/%Y", // the date format
		button : "cx_fecha_desde" // ID of the button
		}
		);
		</script>
      </span></td>
      <td>&nbsp;</td>
      <td><div align="right"><span class="phpmaker"> Pago hasta: </span></div></td>
      <td>&nbsp;</td>
      <td><span>
        <input name="x_fecha_hasta" type="text" id="x_fecha_hasta" value="<?php echo FormatDateTime(@$filter["x_fecha_hasta"],7); ?>" size="11" />
        &nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_hasta" alt="Pick a Date" style="cursor:pointer;cursor:hand;" />
        <script type="text/javascript">
		Calendar.setup(
		{
		inputField : "x_fecha_hasta", // ID of the input field
		ifFormat : "%d/%m/%Y", // the date format
		button : "cx_fecha_hasta" // ID of the button
		}
		);
		</script>
      </span></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><a href="php_factura_listado_factura_por_mes.php?cmd=reset">Mostrar Todos</a></td>
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
      <td></td>
      <td>&nbsp;</td>
    </tr>
    
      <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="button"  value="Filtrar" name="filtro2" onclick="filtrar();"  /></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td></td>
      <td>&nbsp;</td>
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
      <td></td>
      <td>&nbsp;</td>
    </tr>
  </table></td>
</tr>
</table>
</form>
<?php } ?>


<?php if ($sExport == "") { ?>
<form action="php_factura_listado_factura_por_mes.php" name="ewpagerform" id="ewpagerform">
<table class="ewTablePager">
	<tr>
		<td nowrap>
<span class="phpmaker">
<?php
$_QS = http_build_query($filter);
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
		<a href="php_factura_listado_factura_por_mes.php?start=<?php echo $PrevStart; ?>&<?php echo $_QS; ?>"><b>Anterior</b></a>
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
		<a href="php_factura_listado_factura_por_mes.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?></b></a>
					<?php }
					$x += $nDisplayRecs;
					$y += 1;
				} elseif (($x >= ($dx1-$nDisplayRecs*$nRecRange)) && ($x <= ($dx2+$nDisplayRecs*$nRecRange))) {
					if ($x+$nRecRange*$nDisplayRecs < $nTotalRecs) { ?>
		<a href="php_factura_listado_factura_por_mes.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?>-<?php echo ($y+$nRecRange-1);?></b></a>
					<?php } else {
						$ny=intval(($nTotalRecs-1)/$nDisplayRecs)+1;
							if ($ny == $y) { ?>
		<a href="php_factura_listado_factura_por_mes.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?></b></a>
							<?php } else { ?>
		<a href="php_factura_listado_factura_por_mes.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?>-<?php echo $ny; ?></b></a>
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
		<a href="php_factura_listado_factura_por_mes.php?start=<?php echo $NextStart; ?>&<?php echo $_QS; ?>"><b>Siguiente</b></a>
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
Fecha
<?php }else{ ?>
Fecha
<!---
	<a href="php_factura_listado_factura_por_mes.php?order=<?php //echo urlencode("credito_id"); ?>">Crédito num.<?php // if (@$_SESSION["vencimiento_x_credito_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php //} elseif (@$_SESSION["vencimiento_x_credito_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php// } ?></a>
-->    
<?php } ?>
		</span></td>
		<td valign="top"><span> Credito
</span></td>
        <td valign="top"><span>
Cliente
		</span></td>
        <td valign="top">Folio</td>
		<td valign="top">INTORD</td>     
        <td valign="top">INTMOR</td>    
		<td valign="top">COMCOB</td>
<td valign="top">&nbsp;</td>
		<td valign="top">Tasa</td>
		<td valign="top"><span>
		  Total
		  </span></td>
		<td valign="top">Total_IVA</td>
		        
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
$x_tot_importe = 0;
$x_tot_intereses = 0;
$x_tot_moratorios = 0;
$x_tot_total_pago = 0;

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
		$x_fecha = $row["fecha"];
		$x_folio = $row["numero"];
		$x_folio_id = $row["folio_nota_credito_id"];
		
		// seleccionamos los movimientos de ese folio		
		// consultar los ordinarios
		// consultar todos los morartorios
		// consultar todos las comisiones		
		$x_moratorios = 0;
		$x_comision = 0;
		$x_ordinarios = 0;
		$x_tasa = 0;
		$x_total = 0;
		$x_total_iva = 0;
		$sqlMovimientos = "SELECT monto, tasa FROM detalle_nota_credito WHERE folio_nota_credito_id = $x_folio_id and  descripcion = \"CONIOR\"";		
		$rsMovimiento = phpmkr_query($sqlMovimientos,$conn) or die ("Error la seeccionar los mivimientos del folio".phpmkr_error()."sql: ".$sqlMovimientos );
		$rowMovimientos = phpmkr_fetch_array($rsMovimiento);
		$x_ordinarios_condonados = $rowMovimientos["monto"];
		
		$x_tasa = $rowMovimientos["tasa"];		
		$sqlMovimientos = "SELECT monto, tasa FROM detalle_nota_credito WHERE folio_nota_credito_id = $x_folio_id and  descripcion = \"CONIMO\"";
		$rsMovimiento = phpmkr_query($sqlMovimientos,$conn) or die ("Error la seeccionar los mivimientos del folio".phpmkr_error()."sql: ".$sqlMovimientos );
		$rowMovimientos = phpmkr_fetch_array($rsMovimiento);
		$x_moratorios_condonados = $rowMovimientos["monto"];
				
		$sqlMovimientos = "SELECT monto, tasa FROM detalle_nota_credito WHERE folio_nota_credito_id = $x_folio_id and  descripcion = \"CONCCO\"";		
		$rsMovimiento = phpmkr_query($sqlMovimientos,$conn) or die ("Error la seeccionar los mivimientos del folio".phpmkr_error()."sql: ".$sqlMovimientos );
		$rowMovimientos = phpmkr_fetch_array($rsMovimiento);
		$x_comision_condonada = $rowMovimientos["monto"];
		
		$x_total =  $x_comision_condonada + $x_moratorios_condonados + $x_ordinarios_condonados;
		$x_total_iva = $x_total * ($x_tasa / 100);
		$x_total_gral = $x_total_gral + $x_total;  
		$x_total_gral_iva = $x_total_gral_iva + $x_total_iva;
		
		
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>
		<!-- credito_id -->
		<td align="center"><?php echo $x_fecha;?></td>
		<td align="center"><?php echo $x_credito_id;?></td>
        <td align="center"><?php echo $x_cliente ;?>&nbsp;</td>
         <td align="center"><?php echo $x_folio ;?>&nbsp;</td>
<td align="center"><?php echo FormatNumber($x_ordinarios_condonados,2,0,0,1);?></td>
		<td align="left"><?php echo  FormatNumber($x_moratorios_condonados,2,0,0,1);?></td>
<td align="left"><?php echo  FormatNumber($x_comision_condonada,2,0,0,1);?></td>

		<!-- fecha_vencimiento -->
		
<td align="center">&nbsp;</td>
		<td align="center" valign="middle"><?php echo $x_tasa;?></td>
		<!-- importe -->
		<!-- interes -->
		<td align="right"><?php echo FormatNumber($x_total,2,0,0,1);?></td>
		<td align="right"><?php echo FormatNumber($x_total_iva,2,0,0,1);?></td>
		<!-- interes_moratorio -->
		
	</tr>
<?php
	}
}
?>

	<tr>
		<td align="left" colspan="6"><span>
        <strong>
        Totales de la P&aacute;gina:&nbsp;        </strong>
</span></td>
		<td align="right">&nbsp;</td>
		<td align="right">&nbsp;</td>
		<td align="right">&nbsp;</td>
		<!-- importe -->		<!-- interes -->
		<!-- interes_moratorio -->
		<td align="right"><?php echo FormatNumber($x_total_gral,2,0,0,1); ?></td>
		<td align="right"><?php echo FormatNumber($x_total_gral_iva,2,0,0,1); ?></td>
	</tr>
</table>
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
			$sSortField = "`credito_num+0`";
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
			$sSortField = "`fecha_pago`";
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
			$sSrchWhere = "";
			$_SESSION["vencimiento_searchwhere"] = $sSrchWhere;
			$_SESSION["x_fecha_desde"] = "";
			$_SESSION["x_fecha_hasta"] = "";		
			$_SESSION["x_credito_num_filtro"] = "";	
			$_SESSION["x_medio_pago_id"] = 0;
			$_SESSION["x_promo_srch"] = 0;
			
		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["vencimiento_searchwhere"] = $sSrchWhere;
			$_SESSION["x_fecha_desde"] = "";
			$_SESSION["x_fecha_hasta"] = "";		
			$_SESSION["x_credito_num_filtro"] = "";	
			$_SESSION["x_medio_pago_id"] = 0;
			$_SESSION["x_promo_srch"] = 0;			

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
