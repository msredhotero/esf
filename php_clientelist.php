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
$x_cliente_id = Null; 
$ox_cliente_id = Null;
$x_solicitud_id = Null; 
$ox_solicitud_id = Null;
$x_usuario_id = Null; 
$ox_usuario_id = Null;
$x_nombre_completo = Null; 
$ox_nombre_completo = Null;
$x_tipo_negocio = Null; 
$ox_tipo_negocio = Null;
$x_edad = Null; 
$ox_edad = Null;
$x_sexo = Null; 
$ox_sexo = Null;
$x_estado_civil_id = Null; 
$ox_estado_civil_id = Null;
$x_numero_hijos = Null; 
$ox_numero_hijos = Null;
$x_nombre_conyuge = Null; 
$ox_nombre_conyuge = Null;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=cliente.xls');
}
if ($sExport == "word") {
	header('Content-Type: application/vnd.ms-word');
	header('Content-Disposition: attachment; filename=cliente.doc');
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
	$_SESSION["cliente_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["cliente_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["cliente_searchwhere"];
}

// Build SQL
if($_SESSION["php_project_esf_status_UserRolID"] == 1) {
	$sSql = "SELECT cliente.* FROM cliente";
}
if($_SESSION["php_project_esf_status_UserRolID"] == 2) {
	$sSql = "SELECT cliente.* FROM cliente join solicitud_cliente 
	on solicitud_cliente.cliente_id = cliente.cliente_id join solicitud 
	on solicitud.solicitud_id = solicitud_cliente.solicitud_id";
}
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
	$sDbWhere = "(solicitud.promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"]. ") AND ";
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
<p><span class="phpmaker">CLIENTES
<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="php_clientelist.php?export=excel">Exportar a Excel</a>
&nbsp;&nbsp;<a href="php_clientelist.php?export=word">Exportar a Word</a>
<?php } ?>
</span></p>
<?php if ($sExport == "") { ?>
<form action="php_clientelist.php">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><span class="phpmaker">
			<input type="text" name="psearch" size="20">
			<input type="Submit" name="Submit" value="Buscar &nbsp;(*)">
			&nbsp;&nbsp;
			<a href="php_clientelist.php?cmd=reset">Mostrar Todos</a>&nbsp;&nbsp;
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
<!---
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><span class="phpmaker"><a href="php_clienteadd.php">Agregar nuevo Cliente</a></span></td>
	</tr>
</table>
-->
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
<form action="php_clientelist.php" name="ewpagerform" id="ewpagerform">
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
		<a href="php_clientelist.php?start=<?php echo $PrevStart; ?>"><b>Anterior</b></a>
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
		<a href="php_clientelist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
					<?php }
					$x += $nDisplayRecs;
					$y += 1;
				} elseif (($x >= ($dx1-$nDisplayRecs*$nRecRange)) && ($x <= ($dx2+$nDisplayRecs*$nRecRange))) {
					if ($x+$nRecRange*$nDisplayRecs < $nTotalRecs) { ?>
		<a href="php_clientelist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo ($y+$nRecRange-1);?></b></a>
					<?php } else {
						$ny=intval(($nTotalRecs-1)/$nDisplayRecs)+1;
							if ($ny == $y) { ?>
		<a href="php_clientelist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
							<?php } else { ?>
		<a href="php_clientelist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo $ny; ?></b></a>
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
		<a href="php_clientelist.php?start=<?php echo $NextStart; ?>"><b>Siguiente</b></a>
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
<td><input type="checkbox" name="checkall" class="phpmaker" onClick="EW_selectKey(this);"></td>
<?php } ?>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
No
<?php }else{ ?>
	<a href="php_clientelist.php?order=<?php echo urlencode("cliente_id"); ?>">No<?php if (@$_SESSION["cliente_x_cliente_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["cliente_x_cliente_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Nombre Completo
<?php }else{ ?>
	<a href="php_clientelist.php?order=<?php echo urlencode("nombre_completo"); ?>">Nombre Completo&nbsp;(*)<?php if (@$_SESSION["cliente_x_nombre_completo_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["cliente_x_nombre_completo_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Tipo de negocio
<?php }else{ ?>
	<a href="php_clientelist.php?order=<?php echo urlencode("tipo_negocio"); ?>">Tipo de negocio&nbsp;(*)<?php if (@$_SESSION["cliente_x_tipo_negocio_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["cliente_x_tipo_negocio_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Email
<?php }else{ ?>
Email
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
		$x_cliente_id = $row["cliente_id"];
		$x_solicitud_id = $row["solicitud_id"];
		$x_usuario_id = $row["usuario_id"];
		$x_nombre_completo = $row["nombre_completo"];
		$x_apellido_paterno = $row["apellido_paterno"];
		$x_apellido_materno = $row["apellido_materno"];		
		$x_tipo_negocio = $row["tipo_negocio"];
		$x_edad = $row["edad"];
		$x_sexo = $row["sexo"];
		$x_estado_civil_id = $row["estado_civil_id"];
		$x_numero_hijos = $row["numero_hijos"];
		$x_nombre_conyuge = $row["nombre_conyuge"];
		$x_email = $row["email"];		
		
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>
<?php if ($sExport == "") { ?>
<td><span class="phpmaker"><a href="<?php if ($x_cliente_id <> "") {echo "php_clienteview.php?cliente_id=" . urlencode($x_cliente_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Ver</a></span></td>
<td><span class="phpmaker"><a href="<?php if ($x_cliente_id <> "") {echo "php_clienteedit.php?cliente_id=" . urlencode($x_cliente_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Editar</a></span></td>
<td><span class="phpmaker"><input type="checkbox" name="key_d[]" value="<?php echo $x_cliente_id; ?>" class="phpmaker" onclick='ew_clickmultidelete(this);'>
Eliminar</span></td>
<?php } ?>
		<!-- cliente_id -->
		<td><span>
<?php echo $x_cliente_id; ?>
</span></td>
		<!-- nombre_completo -->
		<td><span>
<?php echo $x_nombre_completo." ".$x_apellido_pateron." ".$x_apellido_materno; ?>
</span></td>
		<!-- tipo_negocio -->
		<td><span>
<?php echo $x_tipo_negocio; ?>
</span></td>
		<!-- edad -->
		<td><span>
		<a href="mailto:<?php echo $x_email; ?>">
<?php echo $x_email; ?>
</a>
</span></td>
	</tr>
<?php
	}
}
?>
</table>
<?php if ($sExport == "") { ?>
<?php if ($nRecActual > 0) { ?>
<?php if($_SESSION["php_project_esf_status_UserRolID"] == 1) { ?>
<p><input type="button" name="btndelete" value="ELIMINAR SELECCIONADOS" onClick="if (!EW_selected(this)) alert('No records selected'); else {this.form.action='php_clientedelete.php';this.form.encoding='application/x-www-form-urlencoded';this.form.submit();}">
<?php } ?>
</p>
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
	$BasicSearchSQL.= "cliente.nombre_completo LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "cliente.tipo_negocio LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "cliente.nombre_conyuge LIKE '%" . $sKeyword . "%' OR ";
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

		// Field cliente_id
		if ($sOrder == "cliente_id") {
			$sSortField = "cliente.cliente_id";
			$sLastSort = @$_SESSION["cliente_x_cliente_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["cliente_x_cliente_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["cliente_x_cliente_id_Sort"] <> "") { @$_SESSION["cliente_x_cliente_id_Sort"] = ""; }
		}

		// Field solicitud_id
		if ($sOrder == "solicitud_id") {
			$sSortField = "solicitud.solicitud_id";
			$sLastSort = @$_SESSION["cliente_x_solicitud_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["cliente_x_solicitud_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["cliente_x_solicitud_id_Sort"] <> "") { @$_SESSION["cliente_x_solicitud_id_Sort"] = ""; }
		}

		// Field usuario_id
		if ($sOrder == "usuario_id") {
			$sSortField = "cliente.usuario_id";
			$sLastSort = @$_SESSION["cliente_x_usuario_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["cliente_x_usuario_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["cliente_x_usuario_id_Sort"] <> "") { @$_SESSION["cliente_x_usuario_id_Sort"] = ""; }
		}

		// Field nombre_completo
		if ($sOrder == "nombre_completo") {
			$sSortField = "cliente.nombre_completo";
			$sLastSort = @$_SESSION["cliente_x_nombre_completo_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["cliente_x_nombre_completo_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["cliente_x_nombre_completo_Sort"] <> "") { @$_SESSION["cliente_x_nombre_completo_Sort"] = ""; }
		}

		// Field tipo_negocio
		if ($sOrder == "tipo_negocio") {
			$sSortField = "cliente.tipo_negocio";
			$sLastSort = @$_SESSION["cliente_x_tipo_negocio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["cliente_x_tipo_negocio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["cliente_x_tipo_negocio_Sort"] <> "") { @$_SESSION["cliente_x_tipo_negocio_Sort"] = ""; }
		}

		// Field edad
		if ($sOrder == "edad") {
			$sSortField = "cliente.edad";
			$sLastSort = @$_SESSION["cliente_x_edad_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["cliente_x_edad_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["cliente_x_edad_Sort"] <> "") { @$_SESSION["cliente_x_edad_Sort"] = ""; }
		}

		// Field sexo
		if ($sOrder == "sexo") {
			$sSortField = "cliente.sexo";
			$sLastSort = @$_SESSION["cliente_x_sexo_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["cliente_x_sexo_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["cliente_x_sexo_Sort"] <> "") { @$_SESSION["cliente_x_sexo_Sort"] = ""; }
		}

		// Field estado_civil_id
		if ($sOrder == "estado_civil_id") {
			$sSortField = "cliente.estado_civil_id";
			$sLastSort = @$_SESSION["cliente_x_estado_civil_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["cliente_x_estado_civil_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["cliente_x_estado_civil_id_Sort"] <> "") { @$_SESSION["cliente_x_estado_civil_id_Sort"] = ""; }
		}

		// Field numero_hijos
		if ($sOrder == "numero_hijos") {
			$sSortField = "cliente.numero_hijos";
			$sLastSort = @$_SESSION["cliente_x_numero_hijos_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["cliente_x_numero_hijos_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["cliente_x_numero_hijos_Sort"] <> "") { @$_SESSION["cliente_x_numero_hijos_Sort"] = ""; }
		}

		// Field nombre_conyuge
		if ($sOrder == "nombre_conyuge") {
			$sSortField = "cliente.nombre_conyuge";
			$sLastSort = @$_SESSION["cliente_x_nombre_conyuge_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["cliente_x_nombre_conyuge_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["cliente_x_nombre_conyuge_Sort"] <> "") { @$_SESSION["cliente_x_nombre_conyuge_Sort"] = ""; }
		}
		$_SESSION["cliente_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["cliente_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["cliente_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["cliente_OrderBy"] = $sOrderBy;
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
		$_SESSION["cliente_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["cliente_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["cliente_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["cliente_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["cliente_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["cliente_REC"] = $nStartRec;
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
			$_SESSION["cliente_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["cliente_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["cliente_OrderBy"] = $sOrderBy;
			if (@$_SESSION["cliente_x_cliente_id_Sort"] <> "") { $_SESSION["cliente_x_cliente_id_Sort"] = ""; }
			if (@$_SESSION["cliente_x_solicitud_id_Sort"] <> "") { $_SESSION["cliente_x_solicitud_id_Sort"] = ""; }
			if (@$_SESSION["cliente_x_usuario_id_Sort"] <> "") { $_SESSION["cliente_x_usuario_id_Sort"] = ""; }
			if (@$_SESSION["cliente_x_nombre_completo_Sort"] <> "") { $_SESSION["cliente_x_nombre_completo_Sort"] = ""; }
			if (@$_SESSION["cliente_x_tipo_negocio_Sort"] <> "") { $_SESSION["cliente_x_tipo_negocio_Sort"] = ""; }
			if (@$_SESSION["cliente_x_edad_Sort"] <> "") { $_SESSION["cliente_x_edad_Sort"] = ""; }
			if (@$_SESSION["cliente_x_sexo_Sort"] <> "") { $_SESSION["cliente_x_sexo_Sort"] = ""; }
			if (@$_SESSION["cliente_x_estado_civil_id_Sort"] <> "") { $_SESSION["cliente_x_estado_civil_id_Sort"] = ""; }
			if (@$_SESSION["cliente_x_numero_hijos_Sort"] <> "") { $_SESSION["cliente_x_numero_hijos_Sort"] = ""; }
			if (@$_SESSION["cliente_x_nombre_conyuge_Sort"] <> "") { $_SESSION["cliente_x_nombre_conyuge_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["cliente_REC"] = $nStartRec;
	}
}
?>
