<?php session_start(); ?>
<?php ob_start(); ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Pragma: no-cache"); // HTTP/1.0 
?>
<?php
$ewCurSec = 0; // Initialise

// User levels
define("ewAllowAdd", 1, true);
define("ewAllowDelete", 2, true);
define("ewAllowEdit", 4, true);
define("ewAllowView", 8, true);
define("ewAllowList", 8, true);
define("ewAllowReport", 8, true);
define("ewAllowSearch", 8, true);																														
define("ewAllowAdmin", 16, true);						
?>
<?php

// Initialize common variables
$x_masiva_pago_pendiente_id = Null;
$x_carga_folio_id = Null;
$x_fecha_carga = Null;
$x_aplicacion_status_id = Null;
$x_ref_pago = Null;
$x_nombre_cliente = Null;
$x_numero_cliente = Null;
$x_importe = Null;
$x_fecha_movimiento = Null;
$x_nombre_archivo = Null;
$x_sucursal_id = Null;
$x_uploaded_file_id = Null;
$x_no_aplicar_pago = Null;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=masiva_pago_pendiente.xls');
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
$sSrchBasic = "";
$sSrchWhere = "";
$sDbWhere = "";
$sDefaultOrderBy = "";
$sDefaultFilter = "";
$sWhere = "";
$sGroupBy = "";
$sHaving = "";
$sOrderBy = "";
$sSqlMaster = "";
$nDisplayRecs = 20;
$nRecRange = 10;

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS,DB, PORT);

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
	$_SESSION["masiva_pago_pendiente_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["masiva_pago_pendiente_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["masiva_pago_pendiente_searchwhere"];
}

// Build WHERE condition
$sDbWhere = "";
if ($sDbWhereMaster != "") {
	$sDbWhere .= "(" . $sDbWhereMaster . ") AND ";
}
if ($sSrchWhere != "") {
	$sDbWhere .= "(" . $sSrchWhere . ") AND ";
}
if (strlen($sDbWhere) > 5) {
	$sDbWhere = substr($sDbWhere, 0, strlen($sDbWhere)-5); // Trim rightmost AND
}

// Build SQL
$sSql = "SELECT * FROM `masiva_pago_pendiente`";

// Load Default Filter
$sDefaultFilter = "";
$sGroupBy = "";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = "";
$sWhere = "";
if ($sDefaultFilter != "") {
	$sWhere .= "(" . $sDefaultFilter . ") AND ";
}
if ($sDbWhere != "") {
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
?>
<?php include ("header.php") ?>
<?php if ($sExport == "") { ?>
<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<?php } ?>
<?php

// Set up Record Set
$rs = phpmkr_query($sSql,$conn);
$nTotalRecs = phpmkr_num_rows($rs);
if ($nDisplayRecs <= 0) { // Display All Records
	$nDisplayRecs = $nTotalRecs;
}
$nStartRec = 1;
SetUpStartRec(); // Set Up Start Record Position
?>
<p><span class="phpmaker">PAGOS PENDIENTES
<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="php_masiva_pago_pendientelist.php?export=excel">Exportar a Excel</a>
<?php } ?>
</span></p>
<?php if ($sExport == "") { ?>
<form action="php_masiva_pago_pendientelist.php">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="404"><span class="phpmaker">
			<input type="text" name="psearch" size="20">
			<input type="Submit" name="Submit" value="Buscar &nbsp;(*)">&nbsp;&nbsp;
			<a href="php_masiva_pago_pendientelist.php?cmd=reset">Mostrar todos</a>&nbsp;&nbsp;
		</span></td>
	</tr>
	<tr><td><span class="phpmaker"><input type="radio" name="psearchtype" value="" checked>Frase exacta&nbsp;&nbsp;<input type="radio" name="psearchtype" value="AND">Todas las palabras&nbsp;&nbsp;<input type="radio" name="psearchtype" value="OR">Cualquier palabra</span></td></tr>
</table>
</form>
<?php } ?>
<?php if ($sExport == "") { ?>
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><span class="phpmaker"></span></td>
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
<form action="masiva_pago_pendientelist.php" name="ewpagerform" id="ewpagerform">
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
		if 	($nStartRec == 1) {
			$isPrev = False;
		} else {
			$isPrev = True;
			$PrevStart = $nStartRec - $nDisplayRecs;
			if ($PrevStart < 1) { $PrevStart = 1; } ?>
		<a href="php_masiva_pago_pendientelist.php?start=<?php echo $PrevStart; ?>"><b>Anterior</b></a>
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
		<a href="php_masiva_pago_pendientelist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
					<?php }
					$x += $nDisplayRecs;
					$y += 1;
				} elseif (($x >= ($dx1-$nDisplayRecs*$nRecRange)) && ($x <= ($dx2+$nDisplayRecs*$nRecRange))) {
					if ($x+$nRecRange*$nDisplayRecs < $nTotalRecs) { ?>
		<a href="php_masiva_pago_pendientelist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo ($y+$nRecRange-1);?></b></a>
					<?php } else {
						$ny=intval(($nTotalRecs-1)/$nDisplayRecs)+1;
							if ($ny == $y) { ?>
		<a href="php_masiva_pago_pendientelist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
							<?php } else { ?>
		<a href="php_masiva_pago_pendientelist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo $ny; ?></b></a>
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
		<a href="php_masiva_pago_pendientelist.php?start=<?php echo $NextStart; ?>"><b>Siguiente</b></a>
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
	Regristros <?php echo  $nStartRec;  ?> a <?php  echo $nStopRec; ?> de <?php echo  $nTotalRecs; ?>
<?php } else { ?>
	No hay datos
<?php }?>
</span>
		</td>
	</tr>
</table>
</form>
<?php } ?>
<form method="post">
<table class="ewTable">
<?php if ($nTotalRecs > 0) { ?>
	<!-- Table header -->
	<tr class="ewTableHeader">
<?php if ($sExport == "") { ?>

<td>&nbsp;</td>
<?php } ?>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
masiva pago pendiente id
<?php }else{ ?>
	<a href="php_masiva_pago_pendientelist.php?order=<?php echo urlencode("masiva_pago_pendiente_id"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">masiva pago pendiente id<?php if (@$_SESSION["masiva_pago_pendiente_x_masiva_pago_pendiente_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["masiva_pago_pendiente_x_masiva_pago_pendiente_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
carga folio id
<?php }else{ ?>
	<a href="php_masiva_pago_pendientelist.php?order=<?php echo urlencode("carga_folio_id"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">carga folio id<?php if (@$_SESSION["masiva_pago_pendiente_x_carga_folio_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["masiva_pago_pendiente_x_carga_folio_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
fecha carga
<?php }else{ ?>
	<a href="php_masiva_pago_pendientelist.php?order=<?php echo urlencode("fecha_carga"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">fecha carga<?php if (@$_SESSION["masiva_pago_pendiente_x_fecha_carga_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["masiva_pago_pendiente_x_fecha_carga_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
ref pago
<?php }else{ ?>
	<a href="php_masiva_pago_pendientelist.php?order=<?php echo urlencode("ref_pago"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">ref pago&nbsp;(*)<?php if (@$_SESSION["masiva_pago_pendiente_x_ref_pago_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["masiva_pago_pendiente_x_ref_pago_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
nombre cliente
<?php }else{ ?>
	<a href="php_masiva_pago_pendientelist.php?order=<?php echo urlencode("nombre_cliente"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">nombre cliente&nbsp;(*)<?php if (@$_SESSION["masiva_pago_pendiente_x_nombre_cliente_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["masiva_pago_pendiente_x_nombre_cliente_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
numero cliente
<?php }else{ ?>
	<a href="php_masiva_pago_pendientelist.php?order=<?php echo urlencode("numero_cliente"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">numero cliente<?php if (@$_SESSION["masiva_pago_pendiente_x_numero_cliente_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["masiva_pago_pendiente_x_numero_cliente_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
importe
<?php }else{ ?>
	<a href="php_masiva_pago_pendientelist.php?order=<?php echo urlencode("importe"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">importe<?php if (@$_SESSION["masiva_pago_pendiente_x_importe_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["masiva_pago_pendiente_x_importe_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
fecha movimiento
<?php }else{ ?>
	<a href="php_masiva_pago_pendientelist.php?order=<?php echo urlencode("fecha_movimiento"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">fecha movimiento<?php if (@$_SESSION["masiva_pago_pendiente_x_fecha_movimiento_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["masiva_pago_pendiente_x_fecha_movimiento_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
nombre archivo
<?php }else{ ?>
	<a href="php_masiva_pago_pendientelist.php?order=<?php echo urlencode("nombre_archivo"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">nombre archivo&nbsp;(*)<?php if (@$_SESSION["masiva_pago_pendiente_x_nombre_archivo_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["masiva_pago_pendiente_x_nombre_archivo_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
sucursal id
<?php }else{ ?>
	<a href="php_masiva_pago_pendientelist.php?order=<?php echo urlencode("sucursal_id"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">sucursal id<?php if (@$_SESSION["masiva_pago_pendiente_x_sucursal_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["masiva_pago_pendiente_x_sucursal_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
	</tr>
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
$nRecActual = 0;
while (($row = @phpmkr_fetch_array($rs)) && ($nRecCount < $nStopRec)) {
	$nRecCount = $nRecCount + 1;
	if ($nRecCount >= $nStartRec) {
		$nRecActual = $nRecActual + 1;

	// Set row color
	$sItemRowClass = " class=\"ewTableRow\"";

	// Display alternate color for rows
	if ($nRecCount % 2 <> 0) {
		$sItemRowClass = " class=\"ewTableAltRow\"";
	}

		// Load Key for record
		$sKey = $row["masiva_pago_pendiente_id"];
		$x_masiva_pago_pendiente_id = $row["masiva_pago_pendiente_id"];
		$x_carga_folio_id = $row["carga_folio_id"];
		$x_fecha_carga = $row["fecha_carga"];
		$x_aplicacion_status_id = $row["aplicacion_status_id"];
		$x_ref_pago = $row["ref_pago"];
		$x_nombre_cliente = $row["nombre_cliente"];
		$x_numero_cliente = $row["numero_cliente"];
		$x_importe = $row["importe"];
		$x_fecha_movimiento = $row["fecha_movimiento"];
		$x_nombre_archivo = $row["nombre_archivo"];
		$x_sucursal_id = $row["sucursal_id"];
		$x_uploaded_file_id = $row["uploaded_file_id"];
		$x_no_aplicar_pago = $row["no_aplicar_pago"];
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
<?php if ($sExport == "") { ?>

<td><span class="phpmaker"><a href="<?php if ((!is_null($sKey))) { echo "php_masiva_pago_pendientedelete.php?key=" . urlencode($sKey); } else { echo "javascript:alert('Invalid Record! Key is null');"; }  ?>">Eliminar</a></span></td>
<?php } ?>
		<!-- masiva_pago_pendiente_id -->
		<td><span>
<?php echo $x_masiva_pago_pendiente_id; ?>
</span></td>
		<!-- carga_folio_id -->
		<td><span>
<?php echo $x_carga_folio_id; ?>
</span></td>
		<!-- fecha_carga -->
		<td><span>
<?php echo FormatDateTime($x_fecha_carga,5); ?>
</span></td>
		<!-- ref_pago -->
		<td><span>
<?php echo $x_ref_pago; ?>
</span></td>
		<!-- nombre_cliente -->
		<td><span>
<?php echo $x_nombre_cliente; ?>
</span></td>
		<!-- numero_cliente -->
		<td><span>
<?php echo $x_numero_cliente; ?>
</span></td>
		<!-- importe -->
		<td><span>
<?php echo $x_importe; ?>
</span></td>
		<!-- fecha_movimiento -->
		<td><span>
<?php echo FormatDateTime($x_fecha_movimiento,5); ?>
</span></td>
		<!-- nombre_archivo -->
		<td><span>
<?php echo $x_nombre_archivo; ?>
</span></td>
		<!-- sucursal_id -->
		<td><span>
<?php echo $x_sucursal_id; ?>
</span></td>
	</tr>
<?php
	}
}
?>
</table>
</form>
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
	$BasicSearchSQL.= "`ref_pago` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`nombre_cliente` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`nombre_archivo` LIKE '%" . $sKeyword . "%' OR ";
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

	// Check for Ctrl pressed
	if (strlen(@$_GET["ctrl"]) > 0) {
		$bCtrl = true;
	}
	else
	{
		$bCtrl = false;
	}

	// Check for an Order parameter
	if (strlen(@$_GET["order"]) > 0) {
		$sOrder = @$_GET["order"];

		// Field masiva_pago_pendiente_id
		if ($sOrder == "masiva_pago_pendiente_id") {
			$sSortField = "`masiva_pago_pendiente_id`";
			$sLastSort = @$_SESSION["masiva_pago_pendiente_x_masiva_pago_pendiente_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["masiva_pago_pendiente_x_masiva_pago_pendiente_id_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["masiva_pago_pendiente_x_masiva_pago_pendiente_id_Sort"] <> "") { $_SESSION["masiva_pago_pendiente_x_masiva_pago_pendiente_id_Sort"] = "" ; }
		}

		// Field carga_folio_id
		if ($sOrder == "carga_folio_id") {
			$sSortField = "`carga_folio_id`";
			$sLastSort = @$_SESSION["masiva_pago_pendiente_x_carga_folio_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["masiva_pago_pendiente_x_carga_folio_id_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["masiva_pago_pendiente_x_carga_folio_id_Sort"] <> "") { $_SESSION["masiva_pago_pendiente_x_carga_folio_id_Sort"] = "" ; }
		}

		// Field fecha_carga
		if ($sOrder == "fecha_carga") {
			$sSortField = "`fecha_carga`";
			$sLastSort = @$_SESSION["masiva_pago_pendiente_x_fecha_carga_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["masiva_pago_pendiente_x_fecha_carga_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["masiva_pago_pendiente_x_fecha_carga_Sort"] <> "") { $_SESSION["masiva_pago_pendiente_x_fecha_carga_Sort"] = "" ; }
		}

		// Field ref_pago
		if ($sOrder == "ref_pago") {
			$sSortField = "`ref_pago`";
			$sLastSort = @$_SESSION["masiva_pago_pendiente_x_ref_pago_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["masiva_pago_pendiente_x_ref_pago_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["masiva_pago_pendiente_x_ref_pago_Sort"] <> "") { $_SESSION["masiva_pago_pendiente_x_ref_pago_Sort"] = "" ; }
		}

		// Field nombre_cliente
		if ($sOrder == "nombre_cliente") {
			$sSortField = "`nombre_cliente`";
			$sLastSort = @$_SESSION["masiva_pago_pendiente_x_nombre_cliente_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["masiva_pago_pendiente_x_nombre_cliente_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["masiva_pago_pendiente_x_nombre_cliente_Sort"] <> "") { $_SESSION["masiva_pago_pendiente_x_nombre_cliente_Sort"] = "" ; }
		}

		// Field numero_cliente
		if ($sOrder == "numero_cliente") {
			$sSortField = "`numero_cliente`";
			$sLastSort = @$_SESSION["masiva_pago_pendiente_x_numero_cliente_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["masiva_pago_pendiente_x_numero_cliente_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["masiva_pago_pendiente_x_numero_cliente_Sort"] <> "") { $_SESSION["masiva_pago_pendiente_x_numero_cliente_Sort"] = "" ; }
		}

		// Field importe
		if ($sOrder == "importe") {
			$sSortField = "`importe`";
			$sLastSort = @$_SESSION["masiva_pago_pendiente_x_importe_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["masiva_pago_pendiente_x_importe_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["masiva_pago_pendiente_x_importe_Sort"] <> "") { $_SESSION["masiva_pago_pendiente_x_importe_Sort"] = "" ; }
		}

		// Field fecha_movimiento
		if ($sOrder == "fecha_movimiento") {
			$sSortField = "`fecha_movimiento`";
			$sLastSort = @$_SESSION["masiva_pago_pendiente_x_fecha_movimiento_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["masiva_pago_pendiente_x_fecha_movimiento_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["masiva_pago_pendiente_x_fecha_movimiento_Sort"] <> "") { $_SESSION["masiva_pago_pendiente_x_fecha_movimiento_Sort"] = "" ; }
		}

		// Field nombre_archivo
		if ($sOrder == "nombre_archivo") {
			$sSortField = "`nombre_archivo`";
			$sLastSort = @$_SESSION["masiva_pago_pendiente_x_nombre_archivo_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["masiva_pago_pendiente_x_nombre_archivo_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["masiva_pago_pendiente_x_nombre_archivo_Sort"] <> "") { $_SESSION["masiva_pago_pendiente_x_nombre_archivo_Sort"] = "" ; }
		}

		// Field sucursal_id
		if ($sOrder == "sucursal_id") {
			$sSortField = "`sucursal_id`";
			$sLastSort = @$_SESSION["masiva_pago_pendiente_x_sucursal_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["masiva_pago_pendiente_x_sucursal_id_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["masiva_pago_pendiente_x_sucursal_id_Sort"] <> "") { $_SESSION["masiva_pago_pendiente_x_sucursal_id_Sort"] = "" ; }
		}
		if ($bCtrl) {
			$sOrderBy = @$_SESSION["masiva_pago_pendiente_OrderBy"];
			$pos = strpos($sOrderBy, $sSortField . " " . $sLastSort);
			if ($pos === false) {
				if ($sOrderBy <> "") { $sOrderBy .= ", "; }
				$sOrderBy .= $sSortField . " " . $sThisSort;
			}else{
				$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
			}
			$_SESSION["masiva_pago_pendiente_OrderBy"] = $sOrderBy;
		}
		else
		{
			$_SESSION["masiva_pago_pendiente_OrderBy"] = $sSortField . " " . $sThisSort;
		}
		$_SESSION["masiva_pago_pendiente_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["masiva_pago_pendiente_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["masiva_pago_pendiente_OrderBy"] = $sOrderBy;
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
		$_SESSION["masiva_pago_pendiente_REC"] = $nStartRec;
	}
	elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}
			elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["masiva_pago_pendiente_REC"] = $nStartRec;
		}
		else
		{
			$nStartRec = @$_SESSION["masiva_pago_pendiente_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["masiva_pago_pendiente_REC"] = $nStartRec;
			}
		}
	}
	else
	{
		$nStartRec = @$_SESSION["masiva_pago_pendiente_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["masiva_pago_pendiente_REC"] = $nStartRec;
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
			$_SESSION["masiva_pago_pendiente_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}
		elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["masiva_pago_pendiente_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["masiva_pago_pendiente_OrderBy"] = $sOrderBy;
			if (@$_SESSION["masiva_pago_pendiente_x_masiva_pago_pendiente_id_Sort"] <> "") { $_SESSION["masiva_pago_pendiente_x_masiva_pago_pendiente_id_Sort"] = ""; }
			if (@$_SESSION["masiva_pago_pendiente_x_carga_folio_id_Sort"] <> "") { $_SESSION["masiva_pago_pendiente_x_carga_folio_id_Sort"] = ""; }
			if (@$_SESSION["masiva_pago_pendiente_x_fecha_carga_Sort"] <> "") { $_SESSION["masiva_pago_pendiente_x_fecha_carga_Sort"] = ""; }
			if (@$_SESSION["masiva_pago_pendiente_x_ref_pago_Sort"] <> "") { $_SESSION["masiva_pago_pendiente_x_ref_pago_Sort"] = ""; }
			if (@$_SESSION["masiva_pago_pendiente_x_nombre_cliente_Sort"] <> "") { $_SESSION["masiva_pago_pendiente_x_nombre_cliente_Sort"] = ""; }
			if (@$_SESSION["masiva_pago_pendiente_x_numero_cliente_Sort"] <> "") { $_SESSION["masiva_pago_pendiente_x_numero_cliente_Sort"] = ""; }
			if (@$_SESSION["masiva_pago_pendiente_x_importe_Sort"] <> "") { $_SESSION["masiva_pago_pendiente_x_importe_Sort"] = ""; }
			if (@$_SESSION["masiva_pago_pendiente_x_fecha_movimiento_Sort"] <> "") { $_SESSION["masiva_pago_pendiente_x_fecha_movimiento_Sort"] = ""; }
			if (@$_SESSION["masiva_pago_pendiente_x_nombre_archivo_Sort"] <> "") { $_SESSION["masiva_pago_pendiente_x_nombre_archivo_Sort"] = ""; }
			if (@$_SESSION["masiva_pago_pendiente_x_sucursal_id_Sort"] <> "") { $_SESSION["masiva_pago_pendiente_x_sucursal_id_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["masiva_pago_pendiente_REC"] = $nStartRec;
	}
}
?>
