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
$x_responsable_sucursal_id = Null;
$x_sucursal_id = Null;
$x_usuario_id = Null;
$x_responsable_sucursal_status_id = Null;
$x_nombre_completo = Null;
$x_email = Null;
$x_telefono_sucursal = Null;
$x_telefono_movil = Null;
$x_telefono_casa = Null;
$x_new_field0 = Null;
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
	$_SESSION["responsable_sucursal_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["responsable_sucursal_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["responsable_sucursal_searchwhere"];
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
$sSql = "SELECT * FROM `responsable_sucursal`";

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
<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
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
<p><span class="phpmaker">RESPONSABLES DE SUCURSAL
</span></p>
<form action="php_responsable_sucursallist.php">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><span class="phpmaker">
			<input type="text" name="psearch" size="20">
			<input type="Submit" name="Submit" value="Buscar">
			&nbsp;&nbsp;
			<a href="php_responsable_sucursallist.php?cmd=reset">Mostrar todos</a>&nbsp;&nbsp;
		</span></td>
	</tr>
	<tr><td><span class="phpmaker"><input type="radio" name="psearchtype" value="" checked>
	Frase exacta&nbsp;&nbsp;
	<input type="radio" name="psearchtype" value="AND">
	Todas las palabras
<input type="radio" name="psearchtype" value="OR">
Cualquier palabra</span></td></tr>
</table>
</form>
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="185"><p class="phpmaker">&nbsp;</p>
		  <p class="phpmaker"><a href="php_responsable_sucursaladd.php">Agregar responsable de sucursal</a></p></td>
	</tr>
</table>
<p>
<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="phpmaker" style="color: Red;"><?php echo $_SESSION["ewmsg"]; ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>
<form action="php_responsable_sucursallist.php" name="ewpagerform" id="ewpagerform">
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
		<a href="php_responsable_sucursallist.php?start=<?php echo $PrevStart; ?>"><b>Anterior</b></a>
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
		<a href="php_responsable_sucursallist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
					<?php }
					$x += $nDisplayRecs;
					$y += 1;
				} elseif (($x >= ($dx1-$nDisplayRecs*$nRecRange)) && ($x <= ($dx2+$nDisplayRecs*$nRecRange))) {
					if ($x+$nRecRange*$nDisplayRecs < $nTotalRecs) { ?>
		<a href="php_responsable_sucursallist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo ($y+$nRecRange-1);?></b></a>
					<?php } else {
						$ny=intval(($nTotalRecs-1)/$nDisplayRecs)+1;
							if ($ny == $y) { ?>
		<a href="php_responsable_sucursallist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
							<?php } else { ?>
		<a href="php_responsable_sucursallist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo $ny; ?></b></a>
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
		<a href="php_responsable_sucursallist.php?start=<?php echo $NextStart; ?>"><b>Siguiente</b></a>
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
	Registros <?php echo  $nStartRec;  ?> a <?php  echo $nStopRec; ?> de <?php echo  $nTotalRecs; ?>
<?php } else { ?>
	No hay datos
<?php }?>
</span>
		</td>
	</tr>
</table>
</form>
<form method="post">
<table class="ewTable">
<?php if ($nTotalRecs > 0) { ?>
	<!-- Table header -->
	<tr class="ewTableHeader">
    <td> </td>
    <td> </td>
		<td valign="top"><span>
	<a href="php_responsable_sucursallist.php?order=<?php echo urlencode("responsable_sucursal_id"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">responsable sucursal id<?php if (@$_SESSION["responsable_sucursal_x_responsable_sucursal_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["responsable_sucursal_x_responsable_sucursal_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="php_responsable_sucursallist.php?order=<?php echo urlencode("sucursal_id"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">sucursal id<?php if (@$_SESSION["responsable_sucursal_x_sucursal_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["responsable_sucursal_x_sucursal_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="php_responsable_sucursallist.php?order=<?php echo urlencode("usuario_id"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">usuario id<?php if (@$_SESSION["responsable_sucursal_x_usuario_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["responsable_sucursal_x_usuario_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="php_responsable_sucursallist.php?order=<?php echo urlencode("responsable_sucursal_status_id"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">responsable sucursal status id<?php if (@$_SESSION["responsable_sucursal_x_responsable_sucursal_status_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["responsable_sucursal_x_responsable_sucursal_status_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="php_responsable_sucursallist.php?order=<?php echo urlencode("email"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">email&nbsp;(*)<?php if (@$_SESSION["responsable_sucursal_x_email_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["responsable_sucursal_x_email_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="php_responsable_sucursallist.php?order=<?php echo urlencode("telefono_sucursal"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">telefono sucursal&nbsp;(*)<?php if (@$_SESSION["responsable_sucursal_x_telefono_sucursal_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["responsable_sucursal_x_telefono_sucursal_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="php_responsable_sucursallist.php?order=<?php echo urlencode("telefono_movil"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">telefono movil&nbsp;(*)<?php if (@$_SESSION["responsable_sucursal_x_telefono_movil_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["responsable_sucursal_x_telefono_movil_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="php_responsable_sucursallist.php?order=<?php echo urlencode("telefono_casa"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">telefono casa&nbsp;(*)<?php if (@$_SESSION["responsable_sucursal_x_telefono_casa_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["responsable_sucursal_x_telefono_casa_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top">&nbsp;</td>

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
		$sKey = $row["responsable_sucursal_id"];
		$x_responsable_sucursal_id = $row["responsable_sucursal_id"];
		$x_sucursal_id = $row["sucursal_id"];
		$x_usuario_id = $row["usuario_id"];
		$x_responsable_sucursal_status_id = $row["responsable_sucursal_status_id"];
		$x_nombre_completo = $row["nombre_completo"];
		$x_email = $row["email"];
		$x_telefono_sucursal = $row["telefono_sucursal"];
		$x_telefono_movil = $row["telefono_movil"];
		$x_telefono_casa = $row["telefono_casa"];
		$x_new_field0 = $row["new_field0"];
		$x_sucursal = 0;
		
		
$x_vendedor_status_idList = "<select name=\"x_sucursal_id\">";
$x_vendedor_status_idList .= "<option value=''>Please Select</option>";
$sSqlWrk = "SELECT `sucursal_id`, `nombre` FROM `sucursal`";
$rswrk = phpmkr_query($sSqlWrk,$conn);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_vendedor_status_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["sucursal_id"] == @$x_sucursal_id) {
			$x_sucursal = $datawrk["nombre"] ;
			$x_vendedor_status_idList .= "' selected";
		}
		$x_vendedor_status_idList .= ">" . $datawrk["nombre"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_vendedor_status_idList .= "</select>";


		
		
		
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
		<!-- responsable_sucursal_id -->
        
        <td><span class="phpmaker"><a href="<?php if ((!is_null($sKey))) { echo "php_responsable_sucursalview.php?key=" . urlencode($sKey); } else { echo "javascript:alert('Invalid Record! Key is null');";  } ?>">Ver</a></span></td>
<td><span class="phpmaker"><a href="<?php if ((!is_null($sKey))) { echo "php_responsable_sucursaledit.php?key=" . urlencode($sKey); } else { echo "javascript:alert('Invalid Record! Key is null');"; } ?>">Editar</a></span></td>
		<td><span>
<?php echo $x_responsable_sucursal_id; ?>
</span></td>
		<!-- sucursal_id -->
		<td><span>
<?php echo $x_sucursal; ?>
</span></td>
		<!-- usuario_id -->
		<td><span>
<?php echo $x_usuario_id; ?>
</span></td>
		<!-- responsable_sucursal_status_id -->
		<td><span>
<?php
if ((!is_null($x_responsable_sucursal_status_id)) && ($x_responsable_sucursal_status_id <> "")) {
	$sSqlWrk = "SELECT * FROM `responsable_sucursal_status`";
	$sTmp = $x_responsable_sucursal_status_id;
	$sTmp = (!get_magic_quotes_gpc()) ? addslashes($sTmp) : $sTmp;
	$sSqlWrk .= " WHERE `responsable_sucursal_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_responsable_sucursal_status_id = $x_responsable_sucursal_status_id; // Backup Original Value
$x_responsable_sucursal_status_id = $sTmp;
?>
<?php echo $x_responsable_sucursal_status_id; ?>
<?php $x_responsable_sucursal_status_id = $ox_responsable_sucursal_status_id; // Restore Original Value ?>
</span></td>
		<!-- email -->
		<td><span>
<?php echo $x_email; ?>
</span></td>
		<!-- telefono_sucursal -->
		<td><span>
<?php echo $x_telefono_sucursal; ?>
</span></td>
		<!-- telefono_movil -->
		<td><span>
<?php echo $x_telefono_movil; ?>
</span></td>
		<!-- telefono_casa -->
		<td><span>
<?php echo $x_telefono_casa; ?>
</span></td>
		<!-- new_field0 -->
		<td>&nbsp;</td>


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
<?php include ("footer.php") ?>
<?php

//-------------------------------------------------------------------------------
// Function BasicSearchSQL
// - Build WHERE clause for a keyword

function BasicSearchSQL($Keyword)
{
	$sKeyword = (!get_magic_quotes_gpc()) ? addslashes($Keyword) : $Keyword;
	$BasicSearchSQL = "";
	$BasicSearchSQL.= "`nombre_completo` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`email` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`telefono_sucursal` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`telefono_movil` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`telefono_casa` LIKE '%" . $sKeyword . "%' OR ";
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

		// Field responsable_sucursal_id
		if ($sOrder == "responsable_sucursal_id") {
			$sSortField = "`responsable_sucursal_id`";
			$sLastSort = @$_SESSION["responsable_sucursal_x_responsable_sucursal_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["responsable_sucursal_x_responsable_sucursal_id_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["responsable_sucursal_x_responsable_sucursal_id_Sort"] <> "") { $_SESSION["responsable_sucursal_x_responsable_sucursal_id_Sort"] = "" ; }
		}

		// Field sucursal_id
		if ($sOrder == "sucursal_id") {
			$sSortField = "`sucursal_id`";
			$sLastSort = @$_SESSION["responsable_sucursal_x_sucursal_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["responsable_sucursal_x_sucursal_id_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["responsable_sucursal_x_sucursal_id_Sort"] <> "") { $_SESSION["responsable_sucursal_x_sucursal_id_Sort"] = "" ; }
		}

		// Field usuario_id
		if ($sOrder == "usuario_id") {
			$sSortField = "`usuario_id`";
			$sLastSort = @$_SESSION["responsable_sucursal_x_usuario_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["responsable_sucursal_x_usuario_id_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["responsable_sucursal_x_usuario_id_Sort"] <> "") { $_SESSION["responsable_sucursal_x_usuario_id_Sort"] = "" ; }
		}

		// Field responsable_sucursal_status_id
		if ($sOrder == "responsable_sucursal_status_id") {
			$sSortField = "`responsable_sucursal_status_id`";
			$sLastSort = @$_SESSION["responsable_sucursal_x_responsable_sucursal_status_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["responsable_sucursal_x_responsable_sucursal_status_id_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["responsable_sucursal_x_responsable_sucursal_status_id_Sort"] <> "") { $_SESSION["responsable_sucursal_x_responsable_sucursal_status_id_Sort"] = "" ; }
		}

		// Field email
		if ($sOrder == "email") {
			$sSortField = "`email`";
			$sLastSort = @$_SESSION["responsable_sucursal_x_email_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["responsable_sucursal_x_email_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["responsable_sucursal_x_email_Sort"] <> "") { $_SESSION["responsable_sucursal_x_email_Sort"] = "" ; }
		}

		// Field telefono_sucursal
		if ($sOrder == "telefono_sucursal") {
			$sSortField = "`telefono_sucursal`";
			$sLastSort = @$_SESSION["responsable_sucursal_x_telefono_sucursal_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["responsable_sucursal_x_telefono_sucursal_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["responsable_sucursal_x_telefono_sucursal_Sort"] <> "") { $_SESSION["responsable_sucursal_x_telefono_sucursal_Sort"] = "" ; }
		}

		// Field telefono_movil
		if ($sOrder == "telefono_movil") {
			$sSortField = "`telefono_movil`";
			$sLastSort = @$_SESSION["responsable_sucursal_x_telefono_movil_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["responsable_sucursal_x_telefono_movil_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["responsable_sucursal_x_telefono_movil_Sort"] <> "") { $_SESSION["responsable_sucursal_x_telefono_movil_Sort"] = "" ; }
		}

		// Field telefono_casa
		if ($sOrder == "telefono_casa") {
			$sSortField = "`telefono_casa`";
			$sLastSort = @$_SESSION["responsable_sucursal_x_telefono_casa_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["responsable_sucursal_x_telefono_casa_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["responsable_sucursal_x_telefono_casa_Sort"] <> "") { $_SESSION["responsable_sucursal_x_telefono_casa_Sort"] = "" ; }
		}

		// Field new_field0
		if ($sOrder == "new_field0") {
			$sSortField = "`new_field0`";
			$sLastSort = @$_SESSION["responsable_sucursal_x_new_field0_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["responsable_sucursal_x_new_field0_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["responsable_sucursal_x_new_field0_Sort"] <> "") { $_SESSION["responsable_sucursal_x_new_field0_Sort"] = "" ; }
		}
		if ($bCtrl) {
			$sOrderBy = @$_SESSION["responsable_sucursal_OrderBy"];
			$pos = strpos($sOrderBy, $sSortField . " " . $sLastSort);
			if ($pos === false) {
				if ($sOrderBy <> "") { $sOrderBy .= ", "; }
				$sOrderBy .= $sSortField . " " . $sThisSort;
			}else{
				$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
			}
			$_SESSION["responsable_sucursal_OrderBy"] = $sOrderBy;
		}
		else
		{
			$_SESSION["responsable_sucursal_OrderBy"] = $sSortField . " " . $sThisSort;
		}
		$_SESSION["responsable_sucursal_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["responsable_sucursal_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["responsable_sucursal_OrderBy"] = $sOrderBy;
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
		$_SESSION["responsable_sucursal_REC"] = $nStartRec;
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
			$_SESSION["responsable_sucursal_REC"] = $nStartRec;
		}
		else
		{
			$nStartRec = @$_SESSION["responsable_sucursal_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["responsable_sucursal_REC"] = $nStartRec;
			}
		}
	}
	else
	{
		$nStartRec = @$_SESSION["responsable_sucursal_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["responsable_sucursal_REC"] = $nStartRec;
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
			$_SESSION["responsable_sucursal_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}
		elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["responsable_sucursal_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["responsable_sucursal_OrderBy"] = $sOrderBy;
			if (@$_SESSION["responsable_sucursal_x_responsable_sucursal_id_Sort"] <> "") { $_SESSION["responsable_sucursal_x_responsable_sucursal_id_Sort"] = ""; }
			if (@$_SESSION["responsable_sucursal_x_sucursal_id_Sort"] <> "") { $_SESSION["responsable_sucursal_x_sucursal_id_Sort"] = ""; }
			if (@$_SESSION["responsable_sucursal_x_usuario_id_Sort"] <> "") { $_SESSION["responsable_sucursal_x_usuario_id_Sort"] = ""; }
			if (@$_SESSION["responsable_sucursal_x_responsable_sucursal_status_id_Sort"] <> "") { $_SESSION["responsable_sucursal_x_responsable_sucursal_status_id_Sort"] = ""; }
			if (@$_SESSION["responsable_sucursal_x_email_Sort"] <> "") { $_SESSION["responsable_sucursal_x_email_Sort"] = ""; }
			if (@$_SESSION["responsable_sucursal_x_telefono_sucursal_Sort"] <> "") { $_SESSION["responsable_sucursal_x_telefono_sucursal_Sort"] = ""; }
			if (@$_SESSION["responsable_sucursal_x_telefono_movil_Sort"] <> "") { $_SESSION["responsable_sucursal_x_telefono_movil_Sort"] = ""; }
			if (@$_SESSION["responsable_sucursal_x_telefono_casa_Sort"] <> "") { $_SESSION["responsable_sucursal_x_telefono_casa_Sort"] = ""; }
			if (@$_SESSION["responsable_sucursal_x_new_field0_Sort"] <> "") { $_SESSION["responsable_sucursal_x_new_field0_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["responsable_sucursal_REC"] = $nStartRec;
	}
}
?>
