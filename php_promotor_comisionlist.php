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
$x_promotor_comision_id = Null; 
$ox_promotor_comision_id = Null;
$x_promotor_id = Null; 
$ox_promotor_id = Null;
$x_solicitud_id = Null; 
$ox_solicitud_id = Null;
$x_fecha_registro = Null; 
$ox_fecha_registro = Null;
$x_comision = Null; 
$ox_comision = Null;
$x_comision_importe = Null; 
$ox_comision_importe = Null;
$x_referencia = Null; 
$ox_referencia = Null;
$x_promotor_comision_status_id = Null; 
$ox_promotor_comision_status_id = Null;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=promotor_comision.xls');
}
if ($sExport == "word") {
	header('Content-Type: application/vnd.ms-word');
	header('Content-Disposition: attachment; filename=promotor_comision.doc');
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
$nDisplayRecs = 20;
$nRecRange = 10;

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

// Handle Reset Command
ResetCmd();

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
	$_SESSION["promotor_comision_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["promotor_comision_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["promotor_comision_searchwhere"];
}

// Build SQL

	$sSql = "SELECT * FROM promotor_comision";

// Load Default Filter
$sDefaultFilter = "";
$sGroupBy = "";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = "";

// Build WHERE condition
if($_SESSION["php_project_esf_status_UserRolID"] == 1) {
	$sDbWhere = "";
}
if($_SESSION["php_project_esf_status_UserRolID"] == 2) {
	$sDbWhere = "(promotor_comision.promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"].") AND ";
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
<p><span class="phpmaker">Comisiones
<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="php_promotor_comisionlist.php?export=excel">Exportar a Excel</a><?php } ?>
</span></p>
<?php if ($sExport == "") { ?>
<form action="php_promotor_comisionlist.php">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><span class="phpmaker">
			<input type="text" name="psearch" size="20">
			<input type="Submit" name="Submit" value="Buscar &nbsp;(*)">
			&nbsp;&nbsp; <a href="php_promotor_comisionlist.php?cmd=reset">Mostrar todas</a>&nbsp;&nbsp;
		</span></td>
	</tr>
	<tr><td><span class="phpmaker"><input type="radio" name="psearchtype" value="" checked>
	Frase Exacta&nbsp;&nbsp;
	<input type="radio" name="psearchtype" value="AND">
	Todas las palabras
	<input type="radio" name="psearchtype" value="OR">
	Cualquier palabra</span></td>
	</tr>
</table>
</form>
<?php } ?>
<?php if ($sExport == "") { ?>
<?php if($_SESSION["php_project_esf_status_UserRolID"] == 1) { ?>
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><span class="phpmaker"><a href="php_promotor_comisionadd.php">Agregar nueva comision</a></span></td>
	</tr>
</table>
<p>
<?php } ?>
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
<form action="php_promotor_comisionlist.php" name="ewpagerform" id="ewpagerform">
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
		<a href="php_promotor_comisionlist.php?start=<?php echo $PrevStart; ?>"><b>Anterior</b></a>
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
		<a href="php_promotor_comisionlist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
					<?php }
					$x += $nDisplayRecs;
					$y += 1;
				} elseif (($x >= ($dx1-$nDisplayRecs*$nRecRange)) && ($x <= ($dx2+$nDisplayRecs*$nRecRange))) {
					if ($x+$nRecRange*$nDisplayRecs < $nTotalRecs) { ?>
		<a href="php_promotor_comisionlist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo ($y+$nRecRange-1);?></b></a>
					<?php } else {
						$ny=intval(($nTotalRecs-1)/$nDisplayRecs)+1;
							if ($ny == $y) { ?>
		<a href="php_promotor_comisionlist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
							<?php } else { ?>
		<a href="php_promotor_comisionlist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo $ny; ?></b></a>
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
		<a href="php_promotor_comisionlist.php?start=<?php echo $NextStart; ?>"><b>Siguiente</b></a>
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
	<a href="php_promotor_comisionlist.php?order=<?php echo urlencode("promotor_comision_id"); ?>">No<?php if (@$_SESSION["promotor_comision_x_promotor_comision_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["promotor_comision_x_promotor_comision_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Promotor
<?php }else{ ?>
	<a href="php_promotor_comisionlist.php?order=<?php echo urlencode("promotor_id"); ?>">Promotor<?php if (@$_SESSION["promotor_comision_x_promotor_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["promotor_comision_x_promotor_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Credito No.
<?php }else{ ?>
	<a href="php_promotor_comisionlist.php?order=<?php echo urlencode("credito_id"); ?>">Credito No.<?php if (@$_SESSION["promotor_comision_x_credito_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["promotor_comision_x_credito_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Fecha de registro
<?php }else{ ?>
	<a href="php_promotor_comisionlist.php?order=<?php echo urlencode("fecha_registro"); ?>">Fecha de registro<?php if (@$_SESSION["promotor_comision_x_fecha_registro_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["promotor_comision_x_fecha_registro_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Importe
<?php }else{ ?>
	<a href="php_promotor_comisionlist.php?order=<?php echo urlencode("comision_importe"); ?>">Importe<?php if (@$_SESSION["promotor_comision_x_comision_importe_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["promotor_comision_x_comision_importe_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Referencia
<?php }else{ ?>
	<a href="php_promotor_comisionlist.php?order=<?php echo urlencode("referencia"); ?>">Referencia&nbsp;(*)<?php if (@$_SESSION["promotor_comision_x_referencia_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["promotor_comision_x_referencia_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Status
<?php }else{ ?>
	<a href="php_promotor_comisionlist.php?order=<?php echo urlencode("promotor_comision_status_id"); ?>">Status<?php if (@$_SESSION["promotor_comision_x_promotor_comision_status_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["promotor_comision_x_promotor_comision_status_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
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
		$x_promotor_comision_id = $row["promotor_comision_id"];
		$x_promotor_id = $row["promotor_id"];
		$x_credito_id = $row["credito_id"];
		$x_vencimiento_id = $row["vencimiento_id"];		
		$x_fecha_registro = $row["fecha_registro"];
		$x_comision = $row["comision"];
		$x_comision_importe = $row["comision_importe"];
		$x_referencia = $row["referencia"];
		$x_promotor_comision_status_id = $row["promotor_comision_status_id"];
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>
<?php if ($sExport == "") { ?>

<?php if($_SESSION["php_project_esf_status_UserRolID"] == 1) { ?>
<?php if ($x_promotor_comision_status_id == 1) { ?>
<td><span class="phpmaker"><a href="<?php if ($x_promotor_comision_id <> "") {echo "php_promotor_comisionedit.php?promotor_comision_id=" . urlencode($x_promotor_comision_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Modificar</a></span></td>
<td><span class="phpmaker"><a href="<?php if ($x_promotor_comision_id <> "") {echo "php_promotor_comisioncancelar.php?promotor_comision_id=" . urlencode($x_promotor_comision_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Cancelar</a></span></td>
<td><span class="phpmaker"><a href="<?php if ($x_promotor_comision_id <> "") {echo "php_promotor_reciboadd.php?promotor_comision_id=" . urlencode($x_promotor_comision_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Pagar</a></span></td>
<?php } ?>
<?php if ($x_promotor_comision_status_id == 2) { ?>
<td><span class="phpmaker"></span></td>
<td><span class="phpmaker"><a href="<?php if ($x_promotor_comision_id <> "") {echo "php_promotor_recibo_print.php?promotor_comision_id=" . urlencode($x_promotor_comision_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>" target="_blank">Recibo</a></span></td>
<td><span class="phpmaker"><a href="<?php if ($x_promotor_comision_id <> "") {echo "php_promotor_comisioncancelar.php?promotor_comision_id=" . urlencode($x_promotor_comision_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Cancelar</a></span></td>
<?php } ?>
<?php if ($x_promotor_comision_status_id == 3) { ?>
<td><span class="phpmaker">&nbsp;</span></td>
<td><span class="phpmaker">&nbsp;</span></td>
<td><span class="phpmaker">&nbsp;</span></td>
<?php } ?>

<?php }else{ ?>

<td><span class="phpmaker">&nbsp;</span></td>
<td><span class="phpmaker">&nbsp;</span></td>

<?php } ?>

<?php } ?>
		<!-- promotor_comision_id -->
		<td><span>
<?php echo $x_promotor_comision_id; ?>
</span></td>
		<!-- promotor_id -->
		<td><span>
<?php
if ((!is_null($x_promotor_id)) && ($x_promotor_id <> "")) {
	$sSqlWrk = "SELECT `nombre_completo` FROM `promotor`";
	$sTmp = $x_promotor_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `promotor_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["nombre_completo"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_promotor_id = $x_promotor_id; // Backup Original Value
$x_promotor_id = $sTmp;
?>
<?php echo $x_promotor_id; ?>
<?php $x_promotor_id = $ox_promotor_id; // Restore Original Value ?>
</span></td>
		<!-- solicitud_id -->
		<td align="center"><span>
<?php
if ((!is_null($x_credito_id)) && ($x_credito_id <> "")) {
	$sSqlWrk = "SELECT `credito_num` FROM `credito`";
	$sTmp = $x_credito_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `credito_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["credito_num"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_credito_id = $x_credito_id; // Backup Original Value
$x_credito_id = $sTmp;
?>
<?php echo $x_credito_id; ?>
<?php $x_credito_id = $ox_credito_id; // Restore Original Value ?>
</span></td>

		<!-- fecha_registro -->
		<td align="center"><span>
<?php echo FormatDateTime($x_fecha_registro,7); ?>
</span></td>
		<!-- comision -->
		<td align="right"><span>
<?php echo (is_numeric($x_comision_importe)) ? FormatNumber($x_comision_importe,2,0,0,-2) : $x_comision_importe; ?>
</span></td>
		<!-- referencia -->
		<td align="left"><span>
<?php echo $x_referencia; ?>
</span></td>
		<!-- promotor_comision_status_id -->
		<td align="left"><span>
<?php
if ((!is_null($x_promotor_comision_status_id)) && ($x_promotor_comision_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `promotor_comision_status`";
	$sTmp = $x_promotor_comision_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `promotor_comision_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_promotor_comision_status_id = $x_promotor_comision_status_id; // Backup Original Value
$x_promotor_comision_status_id = $sTmp;
?>
<?php echo $x_promotor_comision_status_id; ?>
<?php $x_promotor_comision_status_id = $ox_promotor_comision_status_id; // Restore Original Value ?>
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
	$BasicSearchSQL.= "`referencia` LIKE '%" . $sKeyword . "%' OR ";
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

		// Field promotor_comision_id
		if ($sOrder == "promotor_comision_id") {
			$sSortField = "`promotor_comision_id`";
			$sLastSort = @$_SESSION["promotor_comision_x_promotor_comision_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["promotor_comision_x_promotor_comision_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["promotor_comision_x_promotor_comision_id_Sort"] <> "") { @$_SESSION["promotor_comision_x_promotor_comision_id_Sort"] = ""; }
		}

		// Field promotor_id
		if ($sOrder == "promotor_id") {
			$sSortField = "`promotor_id`";
			$sLastSort = @$_SESSION["promotor_comision_x_promotor_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["promotor_comision_x_promotor_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["promotor_comision_x_promotor_id_Sort"] <> "") { @$_SESSION["promotor_comision_x_promotor_id_Sort"] = ""; }
		}

		// Field solicitud_id
		if ($sOrder == "credito_id") {
			$sSortField = "`credito_id`";
			$sLastSort = @$_SESSION["promotor_comision_x_credito_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["promotor_comision_x_credito_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["promotor_comision_x_credito_id_Sort"] <> "") { @$_SESSION["promotor_comision_x_credito_id_Sort"] = ""; }
		}

		// Field fecha_registro
		if ($sOrder == "fecha_registro") {
			$sSortField = "`fecha_registro`";
			$sLastSort = @$_SESSION["promotor_comision_x_fecha_registro_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["promotor_comision_x_fecha_registro_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["promotor_comision_x_fecha_registro_Sort"] <> "") { @$_SESSION["promotor_comision_x_fecha_registro_Sort"] = ""; }
		}

		// Field comision
		if ($sOrder == "comision") {
			$sSortField = "`comision`";
			$sLastSort = @$_SESSION["promotor_comision_x_comision_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["promotor_comision_x_comision_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["promotor_comision_x_comision_Sort"] <> "") { @$_SESSION["promotor_comision_x_comision_Sort"] = ""; }
		}

		// Field comision_importe
		if ($sOrder == "comision_importe") {
			$sSortField = "`comision_importe`";
			$sLastSort = @$_SESSION["promotor_comision_x_comision_importe_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["promotor_comision_x_comision_importe_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["promotor_comision_x_comision_importe_Sort"] <> "") { @$_SESSION["promotor_comision_x_comision_importe_Sort"] = ""; }
		}

		// Field referencia
		if ($sOrder == "referencia") {
			$sSortField = "`referencia`";
			$sLastSort = @$_SESSION["promotor_comision_x_referencia_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["promotor_comision_x_referencia_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["promotor_comision_x_referencia_Sort"] <> "") { @$_SESSION["promotor_comision_x_referencia_Sort"] = ""; }
		}

		// Field promotor_comision_status_id
		if ($sOrder == "promotor_comision_status_id") {
			$sSortField = "`promotor_comision_status_id`";
			$sLastSort = @$_SESSION["promotor_comision_x_promotor_comision_status_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["promotor_comision_x_promotor_comision_status_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["promotor_comision_x_promotor_comision_status_id_Sort"] <> "") { @$_SESSION["promotor_comision_x_promotor_comision_status_id_Sort"] = ""; }
		}
		$_SESSION["promotor_comision_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["promotor_comision_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["promotor_comision_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["promotor_comision_OrderBy"] = $sOrderBy;
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
		$_SESSION["promotor_comision_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["promotor_comision_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["promotor_comision_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["promotor_comision_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["promotor_comision_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["promotor_comision_REC"] = $nStartRec;
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
			$_SESSION["promotor_comision_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["promotor_comision_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["promotor_comision_OrderBy"] = $sOrderBy;
			if (@$_SESSION["promotor_comision_x_promotor_comision_id_Sort"] <> "") { $_SESSION["promotor_comision_x_promotor_comision_id_Sort"] = ""; }
			if (@$_SESSION["promotor_comision_x_promotor_id_Sort"] <> "") { $_SESSION["promotor_comision_x_promotor_id_Sort"] = ""; }
			if (@$_SESSION["promotor_comision_x_credito_id_Sort"] <> "") { $_SESSION["promotor_comision_x_credito_id_Sort"] = ""; }
			if (@$_SESSION["promotor_comision_x_fecha_registro_Sort"] <> "") { $_SESSION["promotor_comision_x_fecha_registro_Sort"] = ""; }
			if (@$_SESSION["promotor_comision_x_comision_Sort"] <> "") { $_SESSION["promotor_comision_x_comision_Sort"] = ""; }
			if (@$_SESSION["promotor_comision_x_comision_importe_Sort"] <> "") { $_SESSION["promotor_comision_x_comision_importe_Sort"] = ""; }
			if (@$_SESSION["promotor_comision_x_referencia_Sort"] <> "") { $_SESSION["promotor_comision_x_referencia_Sort"] = ""; }
			if (@$_SESSION["promotor_comision_x_promotor_comision_status_id_Sort"] <> "") { $_SESSION["promotor_comision_x_promotor_comision_status_id_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["promotor_comision_REC"] = $nStartRec;
	}
}
?>
