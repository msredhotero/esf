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
$x_direccion_id = Null; 
$ox_direccion_id = Null;
$x_cliente_id = Null; 
$ox_cliente_id = Null;
$x_aval_id = Null; 
$ox_aval_id = Null;
$x_promotor_id = Null; 
$ox_promotor_id = Null;
$x_direccion_tipo_id = Null; 
$ox_direccion_tipo_id = Null;
$x_calle = Null; 
$ox_calle = Null;
$x_colonia = Null; 
$ox_colonia = Null;
$x_delegacion_id = Null; 
$ox_delegacion_id = Null;
$x_otra_delegacion = Null; 
$ox_otra_delegacion = Null;
$x_entidad = Null; 
$ox_entidad = Null;
$x_codigo_postal = Null; 
$ox_codigo_postal = Null;
$x_ubicacion = Null; 
$ox_ubicacion = Null;
$x_antiguedad = Null; 
$ox_antiguedad = Null;
$x_vivienda_tipo_id = Null; 
$ox_vivienda_tipo_id = Null;
$x_otro_tipo_vivienda = Null; 
$ox_otro_tipo_vivienda = Null;
$x_telefono = Null; 
$ox_telefono = Null;
$x_telefono_secundario = Null; 
$ox_telefono_secundario = Null;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=direccion.xls');
}
if ($sExport == "word") {
	header('Content-Type: application/vnd.ms-word');
	header('Content-Disposition: attachment; filename=direccion.doc');
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
	$_SESSION["direccion_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["direccion_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["direccion_searchwhere"];
}

// Build SQL
$sSql = "SELECT * FROM `direccion`";

// Load Default Filter
$sDefaultFilter = "";
$sGroupBy = "";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = "";

// Build WHERE condition
$sDbWhere = "";
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
<p><span class="phpmaker">TABLE: DIRECCIONES
<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="php_direccionlist.php?export=excel">Export to Excel</a>
&nbsp;&nbsp;<a href="php_direccionlist.php?export=word">Export to Word</a>
<?php } ?>
</span></p>
<?php if ($sExport == "") { ?>
<form action="php_direccionlist.php">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><span class="phpmaker">
			<input type="text" name="psearch" size="20">
			<input type="Submit" name="Submit" value="Search &nbsp;(*)">&nbsp;&nbsp;
			<a href="php_direccionlist.php?cmd=reset">Show all</a>&nbsp;&nbsp;
		</span></td>
	</tr>
	<tr><td><span class="phpmaker"><input type="radio" name="psearchtype" value="" checked>Exact phrase&nbsp;&nbsp;<input type="radio" name="psearchtype" value="AND">All words&nbsp;&nbsp;<input type="radio" name="psearchtype" value="OR">Any word</span></td></tr>
</table>
</form>
<?php } ?>
<?php if ($sExport == "") { ?>
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><span class="phpmaker"><a href="php_direccionadd.php">Add</a></span></td>
	</tr>
</table>
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
<form action="php_direccionlist.php" name="ewpagerform" id="ewpagerform">
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
		<a href="php_direccionlist.php?start=<?php echo $PrevStart; ?>"><b>Prev</b></a>
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
		<a href="php_direccionlist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
					<?php }
					$x += $nDisplayRecs;
					$y += 1;
				} elseif (($x >= ($dx1-$nDisplayRecs*$nRecRange)) && ($x <= ($dx2+$nDisplayRecs*$nRecRange))) {
					if ($x+$nRecRange*$nDisplayRecs < $nTotalRecs) { ?>
		<a href="php_direccionlist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo ($y+$nRecRange-1);?></b></a>
					<?php } else {
						$ny=intval(($nTotalRecs-1)/$nDisplayRecs)+1;
							if ($ny == $y) { ?>
		<a href="php_direccionlist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
							<?php } else { ?>
		<a href="php_direccionlist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo $ny; ?></b></a>
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
		<a href="php_direccionlist.php?start=<?php echo $NextStart; ?>"><b>Next</b></a>
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
	Records <?php echo  $nStartRec;  ?> to <?php  echo $nStopRec; ?> of <?php echo  $nTotalRecs; ?>
<?php } else { ?>
	No records found
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
<td><input type="checkbox" name="checkall" class="phpmaker" onClick="EW_selectKey(this);"></td>
<?php } ?>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
No
<?php }else{ ?>
	<a href="php_direccionlist.php?order=<?php echo urlencode("direccion_id"); ?>">No<?php if (@$_SESSION["direccion_x_direccion_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["direccion_x_direccion_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Cliente
<?php }else{ ?>
	<a href="php_direccionlist.php?order=<?php echo urlencode("cliente_id"); ?>">Cliente<?php if (@$_SESSION["direccion_x_cliente_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["direccion_x_cliente_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Aval
<?php }else{ ?>
	<a href="php_direccionlist.php?order=<?php echo urlencode("aval_id"); ?>">Aval<?php if (@$_SESSION["direccion_x_aval_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["direccion_x_aval_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Promotor
<?php }else{ ?>
	<a href="php_direccionlist.php?order=<?php echo urlencode("promotor_id"); ?>">Promotor<?php if (@$_SESSION["direccion_x_promotor_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["direccion_x_promotor_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Tipo de dirección
<?php }else{ ?>
	<a href="php_direccionlist.php?order=<?php echo urlencode("direccion_tipo_id"); ?>">Tipo de dirección<?php if (@$_SESSION["direccion_x_direccion_tipo_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["direccion_x_direccion_tipo_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Calle
<?php }else{ ?>
	<a href="php_direccionlist.php?order=<?php echo urlencode("calle"); ?>">Calle&nbsp;(*)<?php if (@$_SESSION["direccion_x_calle_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["direccion_x_calle_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
colonia
<?php }else{ ?>
	<a href="php_direccionlist.php?order=<?php echo urlencode("colonia"); ?>">colonia&nbsp;(*)<?php if (@$_SESSION["direccion_x_colonia_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["direccion_x_colonia_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Delegación
<?php }else{ ?>
	<a href="php_direccionlist.php?order=<?php echo urlencode("delegacion_id"); ?>">Delegación<?php if (@$_SESSION["direccion_x_delegacion_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["direccion_x_delegacion_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Otra delegación
<?php }else{ ?>
	<a href="php_direccionlist.php?order=<?php echo urlencode("otra_delegacion"); ?>">Otra delegación&nbsp;(*)<?php if (@$_SESSION["direccion_x_otra_delegacion_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["direccion_x_otra_delegacion_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Entidad
<?php }else{ ?>
	<a href="php_direccionlist.php?order=<?php echo urlencode("entidad"); ?>">Entidad&nbsp;(*)<?php if (@$_SESSION["direccion_x_entidad_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["direccion_x_entidad_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Codigo postal
<?php }else{ ?>
	<a href="php_direccionlist.php?order=<?php echo urlencode("codigo_postal"); ?>">Codigo postal&nbsp;(*)<?php if (@$_SESSION["direccion_x_codigo_postal_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["direccion_x_codigo_postal_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Ubicación
<?php }else{ ?>
	<a href="php_direccionlist.php?order=<?php echo urlencode("ubicacion"); ?>">Ubicación&nbsp;(*)<?php if (@$_SESSION["direccion_x_ubicacion_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["direccion_x_ubicacion_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Antiguedad
<?php }else{ ?>
	<a href="php_direccionlist.php?order=<?php echo urlencode("antiguedad"); ?>">Antiguedad<?php if (@$_SESSION["direccion_x_antiguedad_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["direccion_x_antiguedad_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Tipo de vivienda
<?php }else{ ?>
	<a href="php_direccionlist.php?order=<?php echo urlencode("vivienda_tipo_id"); ?>">Tipo de vivienda<?php if (@$_SESSION["direccion_x_vivienda_tipo_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["direccion_x_vivienda_tipo_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Otro tipo de vivienda
<?php }else{ ?>
	<a href="php_direccionlist.php?order=<?php echo urlencode("otro_tipo_vivienda"); ?>">Otro tipo de vivienda&nbsp;(*)<?php if (@$_SESSION["direccion_x_otro_tipo_vivienda_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["direccion_x_otro_tipo_vivienda_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Teléfono
<?php }else{ ?>
	<a href="php_direccionlist.php?order=<?php echo urlencode("telefono"); ?>">Teléfono&nbsp;(*)<?php if (@$_SESSION["direccion_x_telefono_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["direccion_x_telefono_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Teléfono secundario
<?php }else{ ?>
	<a href="php_direccionlist.php?order=<?php echo urlencode("telefono_secundario"); ?>">Teléfono secundario&nbsp;(*)<?php if (@$_SESSION["direccion_x_telefono_secundario_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["direccion_x_telefono_secundario_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
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
		$x_direccion_id = $row["direccion_id"];
		$x_cliente_id = $row["cliente_id"];
		$x_aval_id = $row["aval_id"];
		$x_promotor_id = $row["promotor_id"];
		$x_direccion_tipo_id = $row["direccion_tipo_id"];
		$x_calle = $row["calle"];
		$x_colonia = $row["colonia"];
		$x_delegacion_id = $row["delegacion_id"];
		$x_otra_delegacion = $row["otra_delegacion"];
		$x_entidad = $row["entidad"];
		$x_codigo_postal = $row["codigo_postal"];
		$x_ubicacion = $row["ubicacion"];
		$x_antiguedad = $row["antiguedad"];
		$x_vivienda_tipo_id = $row["vivienda_tipo_id"];
		$x_otro_tipo_vivienda = $row["otro_tipo_vivienda"];
		$x_telefono = $row["telefono"];
		$x_telefono_secundario = $row["telefono_secundario"];
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>
<?php if ($sExport == "") { ?>
<td><span class="phpmaker"><a href="<?php if ($x_direccion_id <> "") {echo "php_direccionview.php?direccion_id=" . urlencode($x_direccion_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">View</a></span></td>
<td><span class="phpmaker"><a href="<?php if ($x_direccion_id <> "") {echo "php_direccionedit.php?direccion_id=" . urlencode($x_direccion_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Edit</a></span></td>
<td><span class="phpmaker"><a href="<?php if ($x_direccion_id <> "") {echo "php_direccionadd.php?direccion_id=" . urlencode($x_direccion_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Copy</a></span></td>
<td><span class="phpmaker"><input type="checkbox" name="key_d[]" value="<?php echo $x_direccion_id; ?>" class="phpmaker" onclick='ew_clickmultidelete(this);'>Delete</span></td>
<?php } ?>
		<!-- direccion_id -->
		<td><span>
<?php echo $x_direccion_id; ?>
</span></td>
		<!-- cliente_id -->
		<td><span>
<?php
if ((!is_null($x_cliente_id)) && ($x_cliente_id <> "")) {
	$sSqlWrk = "SELECT `nombre_completo` FROM `cliente`";
	$sTmp = $x_cliente_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `cliente_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["nombre_completo"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_cliente_id = $x_cliente_id; // Backup Original Value
$x_cliente_id = $sTmp;
?>
<?php echo $x_cliente_id; ?>
<?php $x_cliente_id = $ox_cliente_id; // Restore Original Value ?>
</span></td>
		<!-- aval_id -->
		<td><span>
<?php
if ((!is_null($x_aval_id)) && ($x_aval_id <> "")) {
	$sSqlWrk = "SELECT `nombre_completo` FROM `aval`";
	$sTmp = $x_aval_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `aval_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["nombre_completo"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_aval_id = $x_aval_id; // Backup Original Value
$x_aval_id = $sTmp;
?>
<?php echo $x_aval_id; ?>
<?php $x_aval_id = $ox_aval_id; // Restore Original Value ?>
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
		<!-- direccion_tipo_id -->
		<td><span>
<?php
if ((!is_null($x_direccion_tipo_id)) && ($x_direccion_tipo_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `direccion_tipo`";
	$sTmp = $x_direccion_tipo_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `direccion_tipo_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_direccion_tipo_id = $x_direccion_tipo_id; // Backup Original Value
$x_direccion_tipo_id = $sTmp;
?>
<?php echo $x_direccion_tipo_id; ?>
<?php $x_direccion_tipo_id = $ox_direccion_tipo_id; // Restore Original Value ?>
</span></td>
		<!-- calle -->
		<td><span>
<?php echo $x_calle; ?>
</span></td>
		<!-- colonia -->
		<td><span>
<?php echo $x_colonia; ?>
</span></td>
		<!-- delegacion_id -->
		<td><span>
<?php
if ((!is_null($x_delegacion_id)) && ($x_delegacion_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `delegacion`";
	$sTmp = $x_delegacion_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `delegacion_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_delegacion_id = $x_delegacion_id; // Backup Original Value
$x_delegacion_id = $sTmp;
?>
<?php echo $x_delegacion_id; ?>
<?php $x_delegacion_id = $ox_delegacion_id; // Restore Original Value ?>
</span></td>
		<!-- otra_delegacion -->
		<td><span>
<?php echo $x_otra_delegacion; ?>
</span></td>
		<!-- entidad -->
		<td><span>
<?php echo $x_entidad; ?>
</span></td>
		<!-- codigo_postal -->
		<td><span>
<?php echo $x_codigo_postal; ?>
</span></td>
		<!-- ubicacion -->
		<td><span>
<?php echo $x_ubicacion; ?>
</span></td>
		<!-- antiguedad -->
		<td><span>
<?php echo $x_antiguedad; ?>
</span></td>
		<!-- vivienda_tipo_id -->
		<td><span>
<?php
if ((!is_null($x_vivienda_tipo_id)) && ($x_vivienda_tipo_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `vivienda_tipo`";
	$sTmp = $x_vivienda_tipo_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `vivienda_tipo_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_vivienda_tipo_id = $x_vivienda_tipo_id; // Backup Original Value
$x_vivienda_tipo_id = $sTmp;
?>
<?php echo $x_vivienda_tipo_id; ?>
<?php $x_vivienda_tipo_id = $ox_vivienda_tipo_id; // Restore Original Value ?>
</span></td>
		<!-- otro_tipo_vivienda -->
		<td><span>
<?php echo $x_otro_tipo_vivienda; ?>
</span></td>
		<!-- telefono -->
		<td><span>
<?php echo $x_telefono; ?>
</span></td>
		<!-- telefono_secundario -->
		<td><span>
<?php echo $x_telefono_secundario; ?>
</span></td>
	</tr>
<?php
	}
}
?>
</table>
<?php if ($sExport == "") { ?>
<?php if ($nRecActual > 0) { ?>
<p><input type="button" name="btndelete" value="DELETE SELECTED" onClick="if (!EW_selected(this)) alert('No records selected'); else {this.form.action='php_direcciondelete.php';this.form.encoding='application/x-www-form-urlencoded';this.form.submit();}"></p>
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
	$BasicSearchSQL.= "`calle` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`colonia` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`otra_delegacion` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`entidad` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`codigo_postal` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`ubicacion` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`otro_tipo_vivienda` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`telefono` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`telefono_secundario` LIKE '%" . $sKeyword . "%' OR ";
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

		// Field direccion_id
		if ($sOrder == "direccion_id") {
			$sSortField = "`direccion_id`";
			$sLastSort = @$_SESSION["direccion_x_direccion_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["direccion_x_direccion_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["direccion_x_direccion_id_Sort"] <> "") { @$_SESSION["direccion_x_direccion_id_Sort"] = ""; }
		}

		// Field cliente_id
		if ($sOrder == "cliente_id") {
			$sSortField = "`cliente_id`";
			$sLastSort = @$_SESSION["direccion_x_cliente_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["direccion_x_cliente_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["direccion_x_cliente_id_Sort"] <> "") { @$_SESSION["direccion_x_cliente_id_Sort"] = ""; }
		}

		// Field aval_id
		if ($sOrder == "aval_id") {
			$sSortField = "`aval_id`";
			$sLastSort = @$_SESSION["direccion_x_aval_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["direccion_x_aval_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["direccion_x_aval_id_Sort"] <> "") { @$_SESSION["direccion_x_aval_id_Sort"] = ""; }
		}

		// Field promotor_id
		if ($sOrder == "promotor_id") {
			$sSortField = "`promotor_id`";
			$sLastSort = @$_SESSION["direccion_x_promotor_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["direccion_x_promotor_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["direccion_x_promotor_id_Sort"] <> "") { @$_SESSION["direccion_x_promotor_id_Sort"] = ""; }
		}

		// Field direccion_tipo_id
		if ($sOrder == "direccion_tipo_id") {
			$sSortField = "`direccion_tipo_id`";
			$sLastSort = @$_SESSION["direccion_x_direccion_tipo_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["direccion_x_direccion_tipo_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["direccion_x_direccion_tipo_id_Sort"] <> "") { @$_SESSION["direccion_x_direccion_tipo_id_Sort"] = ""; }
		}

		// Field calle
		if ($sOrder == "calle") {
			$sSortField = "`calle`";
			$sLastSort = @$_SESSION["direccion_x_calle_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["direccion_x_calle_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["direccion_x_calle_Sort"] <> "") { @$_SESSION["direccion_x_calle_Sort"] = ""; }
		}

		// Field colonia
		if ($sOrder == "colonia") {
			$sSortField = "`colonia`";
			$sLastSort = @$_SESSION["direccion_x_colonia_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["direccion_x_colonia_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["direccion_x_colonia_Sort"] <> "") { @$_SESSION["direccion_x_colonia_Sort"] = ""; }
		}

		// Field delegacion_id
		if ($sOrder == "delegacion_id") {
			$sSortField = "`delegacion_id`";
			$sLastSort = @$_SESSION["direccion_x_delegacion_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["direccion_x_delegacion_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["direccion_x_delegacion_id_Sort"] <> "") { @$_SESSION["direccion_x_delegacion_id_Sort"] = ""; }
		}

		// Field otra_delegacion
		if ($sOrder == "otra_delegacion") {
			$sSortField = "`otra_delegacion`";
			$sLastSort = @$_SESSION["direccion_x_otra_delegacion_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["direccion_x_otra_delegacion_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["direccion_x_otra_delegacion_Sort"] <> "") { @$_SESSION["direccion_x_otra_delegacion_Sort"] = ""; }
		}

		// Field entidad
		if ($sOrder == "entidad") {
			$sSortField = "`entidad`";
			$sLastSort = @$_SESSION["direccion_x_entidad_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["direccion_x_entidad_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["direccion_x_entidad_Sort"] <> "") { @$_SESSION["direccion_x_entidad_Sort"] = ""; }
		}

		// Field codigo_postal
		if ($sOrder == "codigo_postal") {
			$sSortField = "`codigo_postal`";
			$sLastSort = @$_SESSION["direccion_x_codigo_postal_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["direccion_x_codigo_postal_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["direccion_x_codigo_postal_Sort"] <> "") { @$_SESSION["direccion_x_codigo_postal_Sort"] = ""; }
		}

		// Field ubicacion
		if ($sOrder == "ubicacion") {
			$sSortField = "`ubicacion`";
			$sLastSort = @$_SESSION["direccion_x_ubicacion_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["direccion_x_ubicacion_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["direccion_x_ubicacion_Sort"] <> "") { @$_SESSION["direccion_x_ubicacion_Sort"] = ""; }
		}

		// Field antiguedad
		if ($sOrder == "antiguedad") {
			$sSortField = "`antiguedad`";
			$sLastSort = @$_SESSION["direccion_x_antiguedad_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["direccion_x_antiguedad_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["direccion_x_antiguedad_Sort"] <> "") { @$_SESSION["direccion_x_antiguedad_Sort"] = ""; }
		}

		// Field vivienda_tipo_id
		if ($sOrder == "vivienda_tipo_id") {
			$sSortField = "`vivienda_tipo_id`";
			$sLastSort = @$_SESSION["direccion_x_vivienda_tipo_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["direccion_x_vivienda_tipo_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["direccion_x_vivienda_tipo_id_Sort"] <> "") { @$_SESSION["direccion_x_vivienda_tipo_id_Sort"] = ""; }
		}

		// Field otro_tipo_vivienda
		if ($sOrder == "otro_tipo_vivienda") {
			$sSortField = "`otro_tipo_vivienda`";
			$sLastSort = @$_SESSION["direccion_x_otro_tipo_vivienda_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["direccion_x_otro_tipo_vivienda_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["direccion_x_otro_tipo_vivienda_Sort"] <> "") { @$_SESSION["direccion_x_otro_tipo_vivienda_Sort"] = ""; }
		}

		// Field telefono
		if ($sOrder == "telefono") {
			$sSortField = "`telefono`";
			$sLastSort = @$_SESSION["direccion_x_telefono_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["direccion_x_telefono_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["direccion_x_telefono_Sort"] <> "") { @$_SESSION["direccion_x_telefono_Sort"] = ""; }
		}

		// Field telefono_secundario
		if ($sOrder == "telefono_secundario") {
			$sSortField = "`telefono_secundario`";
			$sLastSort = @$_SESSION["direccion_x_telefono_secundario_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["direccion_x_telefono_secundario_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["direccion_x_telefono_secundario_Sort"] <> "") { @$_SESSION["direccion_x_telefono_secundario_Sort"] = ""; }
		}
		$_SESSION["direccion_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["direccion_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["direccion_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["direccion_OrderBy"] = $sOrderBy;
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
		$_SESSION["direccion_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["direccion_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["direccion_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["direccion_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["direccion_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["direccion_REC"] = $nStartRec;
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
			$_SESSION["direccion_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["direccion_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["direccion_OrderBy"] = $sOrderBy;
			if (@$_SESSION["direccion_x_direccion_id_Sort"] <> "") { $_SESSION["direccion_x_direccion_id_Sort"] = ""; }
			if (@$_SESSION["direccion_x_cliente_id_Sort"] <> "") { $_SESSION["direccion_x_cliente_id_Sort"] = ""; }
			if (@$_SESSION["direccion_x_aval_id_Sort"] <> "") { $_SESSION["direccion_x_aval_id_Sort"] = ""; }
			if (@$_SESSION["direccion_x_promotor_id_Sort"] <> "") { $_SESSION["direccion_x_promotor_id_Sort"] = ""; }
			if (@$_SESSION["direccion_x_direccion_tipo_id_Sort"] <> "") { $_SESSION["direccion_x_direccion_tipo_id_Sort"] = ""; }
			if (@$_SESSION["direccion_x_calle_Sort"] <> "") { $_SESSION["direccion_x_calle_Sort"] = ""; }
			if (@$_SESSION["direccion_x_colonia_Sort"] <> "") { $_SESSION["direccion_x_colonia_Sort"] = ""; }
			if (@$_SESSION["direccion_x_delegacion_id_Sort"] <> "") { $_SESSION["direccion_x_delegacion_id_Sort"] = ""; }
			if (@$_SESSION["direccion_x_otra_delegacion_Sort"] <> "") { $_SESSION["direccion_x_otra_delegacion_Sort"] = ""; }
			if (@$_SESSION["direccion_x_entidad_Sort"] <> "") { $_SESSION["direccion_x_entidad_Sort"] = ""; }
			if (@$_SESSION["direccion_x_codigo_postal_Sort"] <> "") { $_SESSION["direccion_x_codigo_postal_Sort"] = ""; }
			if (@$_SESSION["direccion_x_ubicacion_Sort"] <> "") { $_SESSION["direccion_x_ubicacion_Sort"] = ""; }
			if (@$_SESSION["direccion_x_antiguedad_Sort"] <> "") { $_SESSION["direccion_x_antiguedad_Sort"] = ""; }
			if (@$_SESSION["direccion_x_vivienda_tipo_id_Sort"] <> "") { $_SESSION["direccion_x_vivienda_tipo_id_Sort"] = ""; }
			if (@$_SESSION["direccion_x_otro_tipo_vivienda_Sort"] <> "") { $_SESSION["direccion_x_otro_tipo_vivienda_Sort"] = ""; }
			if (@$_SESSION["direccion_x_telefono_Sort"] <> "") { $_SESSION["direccion_x_telefono_Sort"] = ""; }
			if (@$_SESSION["direccion_x_telefono_secundario_Sort"] <> "") { $_SESSION["direccion_x_telefono_secundario_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["direccion_REC"] = $nStartRec;
	}
}
?>
