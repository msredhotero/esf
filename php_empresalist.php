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
if (@$_SESSION["php_project_esf_status"] <> "login") {
	header("Location:  login.php");
	exit();
}
?>
<?php

// Initialize common variables
$x_empresa_id = Null;
$x_nombre = Null;
$x_calle = Null;
$x_numero_exterior = Null;
$x_numero_interior = Null;
$x_colonia = Null;
$x_estado = Null;
$x_delegacion = Null;
$x_telefono = Null;
$x_fax = Null;
$x_otro = Null;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=empresa.xls');
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
	$_SESSION["empresa_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["empresa_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["empresa_searchwhere"];
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
$sSql = "SELECT * FROM `empresa`";

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
<?php if ($sExport == "") { ?>
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
}

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
<p><span class="phpmaker">EMPRESA
<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="php_empresalist.php?export=excel">Exportar a  Excel</a>
<?php } ?>
</span></p>
<?php if ($sExport == "") { ?>
<form action="php_empresalist.php">
<table width="465" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="350"><span class="phpmaker">
			<input type="text" name="psearch" size="20">
			<input type="Submit" name="Submit" value="Buscar &nbsp;(*)">&nbsp;&nbsp;
			<a href="php_empresalist.php?cmd=reset">Mostrar todo</a>&nbsp;&nbsp;
		</span></td>
	</tr>
	<tr><td><span class="phpmaker"><input type="radio" name="psearchtype" value="" checked>Frase Exacta&nbsp;&nbsp;<input type="radio" name="psearchtype" value="AND">Todas las palabras&nbsp;&nbsp;<input type="radio" name="psearchtype" value="OR">Cualquier palabra</span></td></tr>
</table>
</form><p>
<?php } ?>
<?php if ($sExport == "") { ?>
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><span class="phpmaker"><a href="php_empresaadd.php">Agregar una empresa nueva</a></span></td>
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
<form action="php_empresalist.php" name="ewpagerform" id="ewpagerform">
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
		<a href="php_empresalist.php?start=<?php echo $PrevStart; ?>"><b>Anterior</b></a>
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
		<a href="php_empresalist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
					<?php }
					$x += $nDisplayRecs;
					$y += 1;
				} elseif (($x >= ($dx1-$nDisplayRecs*$nRecRange)) && ($x <= ($dx2+$nDisplayRecs*$nRecRange))) {
					if ($x+$nRecRange*$nDisplayRecs < $nTotalRecs) { ?>
		<a href="php_empresalist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo ($y+$nRecRange-1);?></b></a>
					<?php } else {
						$ny=intval(($nTotalRecs-1)/$nDisplayRecs)+1;
							if ($ny == $y) { ?>
		<a href="php_empresalist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
							<?php } else { ?>
		<a href="php_empresalist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo $ny; ?></b></a>
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
		<a href="php_empresalist.php?start=<?php echo $NextStart; ?>"><b>Siguiente</b></a>
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
<?php } ?>
<form method="post">
<table class="ewTable">
<?php if ($nTotalRecs > 0) { ?>
	<!-- Table header -->
	<tr class="ewTableHeader">
<?php if ($sExport == "") { ?>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>


<?php } ?>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
empresa id
<?php }else{ ?>
	<a href="php_empresalist.php?order=<?php echo urlencode("empresa_id"); ?>" style="color: #FFFFFF;">empresa id<?php if (@$_SESSION["empresa_x_empresa_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["empresa_x_empresa_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
nombre
<?php }else{ ?>
	<a href="php_empresalist.php?order=<?php echo urlencode("nombre"); ?>" style="color: #FFFFFF;">nombre&nbsp;(*)<?php if (@$_SESSION["empresa_x_nombre_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["empresa_x_nombre_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
calle
<?php }else{ ?>
	<a href="php_empresalist.php?order=<?php echo urlencode("calle"); ?>" style="color: #FFFFFF;">calle&nbsp;(*)<?php if (@$_SESSION["empresa_x_calle_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["empresa_x_calle_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
numero exterior
<?php }else{ ?>
	<a href="php_empresalist.php?order=<?php echo urlencode("numero_exterior"); ?>" style="color: #FFFFFF;">numero exterior<?php if (@$_SESSION["empresa_x_numero_exterior_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["empresa_x_numero_exterior_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
numero interior
<?php }else{ ?>
	<a href="php_empresalist.php?order=<?php echo urlencode("numero_interior"); ?>" style="color: #FFFFFF;">numero interior&nbsp;(*)<?php if (@$_SESSION["empresa_x_numero_interior_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["empresa_x_numero_interior_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
colonia
<?php }else{ ?>
	<a href="php_empresalist.php?order=<?php echo urlencode("colonia"); ?>" style="color: #FFFFFF;">colonia&nbsp;(*)<?php if (@$_SESSION["empresa_x_colonia_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["empresa_x_colonia_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
estado
<?php }else{ ?>
	<a href="php_empresalist.php?order=<?php echo urlencode("estado"); ?>" style="color: #FFFFFF;">estado<?php if (@$_SESSION["empresa_x_estado_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["empresa_x_estado_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
delegacion
<?php }else{ ?>
	<a href="php_empresalist.php?order=<?php echo urlencode("delegacion"); ?>" style="color: #FFFFFF;">delegacion<?php if (@$_SESSION["empresa_x_delegacion_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["empresa_x_delegacion_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
telefono
<?php }else{ ?>
	<a href="php_empresalist.php?order=<?php echo urlencode("telefono"); ?>" style="color: #FFFFFF;">telefono&nbsp;(*)<?php if (@$_SESSION["empresa_x_telefono_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["empresa_x_telefono_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
fax
<?php }else{ ?>
	<a href="php_empresalist.php?order=<?php echo urlencode("fax"); ?>" style="color: #FFFFFF;">fax&nbsp;(*)<?php if (@$_SESSION["empresa_x_fax_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["empresa_x_fax_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
        <td><span>
        <?php if ($sExport <> "") { ?>
Representante
<?php }else{ ?>
        Representante
        <?php }?>
        </span>
        </td>
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
		$sKey = $row["empresa_id"];
		$x_empresa_id = $row["empresa_id"];
		$x_nombre = $row["nombre"];
		$x_calle = $row["calle"];
		$x_numero_exterior = $row["numero_exterior"];
		$x_numero_interior = $row["numero_interior"];
		$x_colonia = $row["colonia"];
		$x_estado = $row["estado"];
		$x_delegacion = $row["delegacion"];
		$x_telefono = $row["telefono"];
		$x_fax = $row["fax"];
		$x_otro = $row["otro"];
		
		$sqlRep = "SELECT * FROM representante_legal WHERE empresa_id  =  $x_empresa_id ";
		$responseRep = phpmkr_query($sqlRep, $conn) or die("error el buscar representante de empresa".phpmkr_error()."sql:".$sqlRep);
		$rowRep = phpmkr_fetch_array($responseRep);
		$x_representante =  $rowRep["nombre_completo"]." ".$rowRep["apellido_paterno "]." ".$rowRep["apellido_paterno"];
		phpmkr_free_result($responseRep);
		
		
		
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
<?php if ($sExport == "") { ?>
<td><span class="phpmaker"><a href="<?php if ((!is_null($sKey))) { echo "empresaview.php?key=" . urlencode($sKey); } else { echo "javascript:alert('Invalid Record! Key is null');";  } ?>">Ver</a></span></td>
<td><span class="phpmaker"><a href="<?php if ((!is_null($sKey))) { echo "php_empresaedit.php?empresa_id=" . urlencode($sKey); } else { echo "javascript:alert('Invalid Record! Key is null');"; } ?>">Editar</a></span></td>

<?php } ?>

<td><span>
<?php echo $x_status; ?>
</span></td>
		<!-- empresa_id -->
		<td><span>
<?php echo $x_empresa_id; ?>
</span></td>
		<!-- nombre -->
		<td><span>
<?php echo $x_nombre; ?>
</span></td>
		<!-- calle -->
		<td><span>
<?php echo $x_calle; ?>
</span></td>
		<!-- numero_exterior -->
		<td><span>
<?php echo $x_numero_exterior; ?>
</span></td>
		<!-- numero_interior -->
		<td><span>
<?php echo $x_numero_interior; ?>
</span></td>
		<!-- colonia -->
		<td><span>
<?php echo $x_colonia; ?>
</span></td>
		<!-- estado -->
		<td><span>
<?php echo $x_estado; ?>
</span></td>
		<!-- delegacion -->
		<td><span>
<?php echo $x_delegacion; ?>
</span></td>
		<!-- telefono -->
		<td><span>
<?php echo $x_telefono; ?>
</span></td>
		<!-- fax -->
		<td><span>
<?php echo $x_fax; ?>
</span></td>
	<!-- representante -->
		<td><span>
<?php echo $x_representante; 
$x_representante = "";?>
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
	$BasicSearchSQL.= "`nombre` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`calle` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`numero_interior` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`colonia` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`telefono` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`fax` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`otro` LIKE '%" . $sKeyword . "%' OR ";
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

		// Field empresa_id
		if ($sOrder == "empresa_id") {
			$sSortField = "`empresa_id`";
			$sLastSort = @$_SESSION["empresa_x_empresa_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["empresa_x_empresa_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["empresa_x_empresa_id_Sort"] <> "") { @$_SESSION["empresa_x_empresa_id_Sort"] = ""; }
		}

		// Field nombre
		if ($sOrder == "nombre") {
			$sSortField = "`nombre`";
			$sLastSort = @$_SESSION["empresa_x_nombre_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["empresa_x_nombre_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["empresa_x_nombre_Sort"] <> "") { @$_SESSION["empresa_x_nombre_Sort"] = ""; }
		}

		// Field calle
		if ($sOrder == "calle") {
			$sSortField = "`calle`";
			$sLastSort = @$_SESSION["empresa_x_calle_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["empresa_x_calle_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["empresa_x_calle_Sort"] <> "") { @$_SESSION["empresa_x_calle_Sort"] = ""; }
		}

		// Field numero_exterior
		if ($sOrder == "numero_exterior") {
			$sSortField = "`numero_exterior`";
			$sLastSort = @$_SESSION["empresa_x_numero_exterior_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["empresa_x_numero_exterior_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["empresa_x_numero_exterior_Sort"] <> "") { @$_SESSION["empresa_x_numero_exterior_Sort"] = ""; }
		}

		// Field numero_interior
		if ($sOrder == "numero_interior") {
			$sSortField = "`numero_interior`";
			$sLastSort = @$_SESSION["empresa_x_numero_interior_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["empresa_x_numero_interior_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["empresa_x_numero_interior_Sort"] <> "") { @$_SESSION["empresa_x_numero_interior_Sort"] = ""; }
		}

		// Field colonia
		if ($sOrder == "colonia") {
			$sSortField = "`colonia`";
			$sLastSort = @$_SESSION["empresa_x_colonia_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["empresa_x_colonia_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["empresa_x_colonia_Sort"] <> "") { @$_SESSION["empresa_x_colonia_Sort"] = ""; }
		}

		// Field estado
		if ($sOrder == "estado") {
			$sSortField = "`estado`";
			$sLastSort = @$_SESSION["empresa_x_estado_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["empresa_x_estado_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["empresa_x_estado_Sort"] <> "") { @$_SESSION["empresa_x_estado_Sort"] = ""; }
		}

		// Field delegacion
		if ($sOrder == "delegacion") {
			$sSortField = "`delegacion`";
			$sLastSort = @$_SESSION["empresa_x_delegacion_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["empresa_x_delegacion_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["empresa_x_delegacion_Sort"] <> "") { @$_SESSION["empresa_x_delegacion_Sort"] = ""; }
		}

		// Field telefono
		if ($sOrder == "telefono") {
			$sSortField = "`telefono`";
			$sLastSort = @$_SESSION["empresa_x_telefono_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["empresa_x_telefono_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["empresa_x_telefono_Sort"] <> "") { @$_SESSION["empresa_x_telefono_Sort"] = ""; }
		}

		// Field fax
		if ($sOrder == "fax") {
			$sSortField = "`fax`";
			$sLastSort = @$_SESSION["empresa_x_fax_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["empresa_x_fax_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["empresa_x_fax_Sort"] <> "") { @$_SESSION["empresa_x_fax_Sort"] = ""; }
		}
		$_SESSION["empresa_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["empresa_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["empresa_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["empresa_OrderBy"] = $sOrderBy;
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
		$_SESSION["empresa_REC"] = $nStartRec;
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
			$_SESSION["empresa_REC"] = $nStartRec;
		}
		else
		{
			$nStartRec = @$_SESSION["empresa_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["empresa_REC"] = $nStartRec;
			}
		}
	}
	else
	{
		$nStartRec = @$_SESSION["empresa_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["empresa_REC"] = $nStartRec;
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
			$_SESSION["empresa_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}
		elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["empresa_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["empresa_OrderBy"] = $sOrderBy;
			if (@$_SESSION["empresa_x_empresa_id_Sort"] <> "") { $_SESSION["empresa_x_empresa_id_Sort"] = ""; }
			if (@$_SESSION["empresa_x_nombre_Sort"] <> "") { $_SESSION["empresa_x_nombre_Sort"] = ""; }
			if (@$_SESSION["empresa_x_calle_Sort"] <> "") { $_SESSION["empresa_x_calle_Sort"] = ""; }
			if (@$_SESSION["empresa_x_numero_exterior_Sort"] <> "") { $_SESSION["empresa_x_numero_exterior_Sort"] = ""; }
			if (@$_SESSION["empresa_x_numero_interior_Sort"] <> "") { $_SESSION["empresa_x_numero_interior_Sort"] = ""; }
			if (@$_SESSION["empresa_x_colonia_Sort"] <> "") { $_SESSION["empresa_x_colonia_Sort"] = ""; }
			if (@$_SESSION["empresa_x_estado_Sort"] <> "") { $_SESSION["empresa_x_estado_Sort"] = ""; }
			if (@$_SESSION["empresa_x_delegacion_Sort"] <> "") { $_SESSION["empresa_x_delegacion_Sort"] = ""; }
			if (@$_SESSION["empresa_x_telefono_Sort"] <> "") { $_SESSION["empresa_x_telefono_Sort"] = ""; }
			if (@$_SESSION["empresa_x_fax_Sort"] <> "") { $_SESSION["empresa_x_fax_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["empresa_REC"] = $nStartRec;
	}
}
?>
