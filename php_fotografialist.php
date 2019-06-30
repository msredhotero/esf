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
$x_fotografia_id = Null; 
$ox_fotografia_id = Null;
$x_solicitud_id = Null; 
$ox_solicitud_id = Null;
$x_titulo = Null; 
$ox_titulo = Null;
$x_descripcion = Null; 
$ox_descripcion = Null;
$x_mapa = Null; 
$ox_mapa = Null;
$fs_x_mapa = 0;
$fn_x_mapa = "";
$ct_x_mapa = "";
$w_x_mapa = 0;
$h_x_mapa = 0;
$a_x_mapa = "";
$x_foto = Null; 
$ox_foto = Null;
$fs_x_foto = 0;
$fn_x_foto = "";
$ct_x_foto = "";
$w_x_foto = 0;
$h_x_foto = 0;
$a_x_foto = "";
$x_pais_id = Null; 
$ox_pais_id = Null;
$x_estado_id = Null; 
$ox_estado_id = Null;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=fotos.xls');
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


$x_solicitud_id = $_GET["x_solicitud_id"];
if(empty($x_solicitud_id)){
	$x_solicitud_id = $_POST["x_solicitud_id"];
	if(empty($x_solicitud_id)){	
		echo "No se ha especificado la solicitud";
		exit();
	}
}

$sSqlWrk = "SELECT grupo_nombre FROM solicitud where solicitud_id = $x_solicitud_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	if($rowwrk["grupo_nombre"] != ""){
		$x_cliente = $rowwrk["grupo_nombre"];		
	}else{
		$sSqlWrk = "SELECT nombre_completo, apellido_paterno, apellido_materno FROM cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id where solicitud_cliente.solicitud_id = $x_solicitud_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
			$x_cliente = $rowwrk["nombre_completo"]." ".$rowwrk["apellido_paterno"]." ".$rowwrk["apellido_materno"];	
		}else{
			$x_cliente = "Cliente no Localizado.";
		}
		@phpmkr_free_result($rswrk);
	}
}else{
	$x_cliente = "La solicitud no fue localizada.";
	exit();
}
@phpmkr_free_result($rswrk);


	
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
	$_SESSION["fotografia_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["fotografia_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["fotografia_searchwhere"];
}

// Build SQL
$sSql = "SELECT * FROM `fotografia`";

// Load Default Filter
$sDefaultFilter = "";
$sGroupBy = "";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = "`solicitud_id` ASC,`titulo` ASC";

// Build WHERE condition
$sDbWhere = "(solicitud_id = $x_solicitud_id) AND ";
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>e - SF >  FINANCIERA CRECE - FOTOS </title>
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
<SCRIPT TYPE="text/javascript">
<!--
window.focus();
//-->
</SCRIPT>
</head>
<body>
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
var rowaltcolor = '#ECE9D8'; // row alternate color
var rowmovercolor = '#B5E2FF'; // row mouse over color
var rowselectedcolor = '#6699CC'; // row selected color
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
<div align="center">
<p align="center"><a href="javascript: window.close();">Cerrar Ventana</a></p>
<p><span class="phpmaker">FOTOGRAFIAS DEL CLIENTE:&nbsp;<strong><?php echo $x_cliente; ?></strong><br />
  <br />
</span></p>
<?php if ($sExport == "") { ?>
<form action="php_fotografialist.php">
</form>
<?php } ?>
<?php if ($sExport == "") { ?>
<br />
<table border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
		<td width="110"><div align="center"><span class="phpmaker"><a href="php_fotografiaadd.php?x_solicitud_id=<?php echo $x_solicitud_id; ?>" target="_blank">Agregar foto</a></span></div></td>
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
<form action="php_fotografialist.php" name="ewpagerform" id="ewpagerform">
</form>
<?php } ?>
<?php if ($nTotalRecs > 0)  { ?>
<form method="post" name="listado" action="php_fotografialist.php">
<input type="hidden" name="x_solicitud_id" value="<?php echo $x_solicitud_id; ?>" />

<table id="ewlistmain" class="ewTable">
	<!-- Table header -->
	<tr class="ewTableHeader">
<?php if ($sExport == "") { ?>
<td>&nbsp;</td>
<td><input type="checkbox" name="checkall" class="phpmaker" onclick="EW_selectKey(this);" /></td>
<?php } ?>
		<td valign="top"><span>
titulo
		</span></td>
		<td valign="top"><span>
Descripcion
		</span></td>
		<td valign="top">nombre de fotografia</td>
		<td valign="top"><span>
Fotografia
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
		$x_fotografia_id = $row["fotografia_id"];
		$x_solicitud_id = $row["solicitud_id"];
		$x_titulo = $row["titulo"];
		$x_descripcion = $row["descripcion"];
		$x_mapa = $row["mapa"];
		$x_foto = $row["foto"];
		$x_pais_id = $row["pais_id"];
		$x_estado_id = $row["estado_id"];
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>
<?php if ($sExport == "") { ?>
<td><span class="phpmaker"><a href="<?php if ($x_fotografia_id <> "") {echo "php_fotografiaedit.php?fotografia_id=" . urlencode($x_fotografia_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>" target='_blank'>Editar</a></span></td>
<td><span class="phpmaker"><input type="checkbox" name="key_d[]" value="<?php echo $x_fotografia_id; ?>" class="phpmaker" onclick='ew_clickmultidelete(this);'>
Eliminar</span></td>
<?php } ?>
		<!-- fotografia_id -->
		<!-- solicitud_id -->
		<!-- titulo -->
		<td><span>
<?php echo $x_titulo; ?>
</span></td>
		<!-- descripcion -->
		<td><span>
<?php echo $x_descripcion; ?>
</span></td>
		<td align="center" valign="middle"><?php echo $x_foto; ?></td>
		<!-- mapa -->
		<!-- foto -->
		<td align="center" valign="middle"><span>
<?php if ((!is_null($x_foto)) &&  $x_foto <> "") { ?>
<img src="<?php echo ewUploadPath(0) . $x_foto ?>" />
<?php } ?>
</span></td>
		<!-- pais_id -->
		<!-- estado_id -->
	</tr>
<?php
	}
}
?>
</table>
<?php if ($sExport == "") { ?>
<?php if ($nRecActual > 0) { ?>
<!---
<p><input type="button" name="btndelete" value="ELIMINAR SELECCIONADAS" onClick="if (!EW_selected(this)) alert('No ha seleccionados fotos'); else {this.form.action='php_fotografiadelete.php';this.form.encoding='application/x-www-form-urlencoded';this.form.submit();}">
--->
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
</div>
</body>
</html>
<?php } ?>
<?php

//-------------------------------------------------------------------------------
// Function BasicSearchSQL
// - Build WHERE clause for a keyword

function BasicSearchSQL($Keyword)
{
	$sKeyword = (!get_magic_quotes_gpc()) ? addslashes($Keyword) : $Keyword;
	$BasicSearchSQL = "";
	$BasicSearchSQL.= "`titulo` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`descripcion` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`mapa` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`foto` LIKE '%" . $sKeyword . "%' OR ";
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
	}else{
		$bCtrl = false;
	}

	// Check for an Order parameter
	if (strlen(@$_GET["order"]) > 0) {
		$sOrder = @$_GET["order"];

		// Field fotografia_id
		if ($sOrder == "fotografia_id") {
			$sSortField = "`fotografia_id`";
			$sLastSort = @$_SESSION["fotografia_x_fotografia_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["fotografia_x_fotografia_id_Sort"] = $sThisSort;
		}else{
			if (!($bCtrl) && @$_SESSION["fotografia_x_fotografia_id_Sort"] <> "") { $_SESSION["fotografia_x_fotografia_id_Sort"] = "" ; }
		}

		// Field solicitud_id
		if ($sOrder == "solicitud_id") {
			$sSortField = "`solicitud_id`";
			$sLastSort = @$_SESSION["fotografia_x_solicitud_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["fotografia_x_solicitud_id_Sort"] = $sThisSort;
		}else{
			if (!($bCtrl) && @$_SESSION["fotografia_x_solicitud_id_Sort"] <> "") { $_SESSION["fotografia_x_solicitud_id_Sort"] = "" ; }
		}

		// Field titulo
		if ($sOrder == "titulo") {
			$sSortField = "`titulo`";
			$sLastSort = @$_SESSION["fotografia_x_titulo_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["fotografia_x_titulo_Sort"] = $sThisSort;
		}else{
			if (!($bCtrl) && @$_SESSION["fotografia_x_titulo_Sort"] <> "") { $_SESSION["fotografia_x_titulo_Sort"] = "" ; }
		}

		// Field descripcion
		if ($sOrder == "descripcion") {
			$sSortField = "`descripcion`";
			$sLastSort = @$_SESSION["fotografia_x_descripcion_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["fotografia_x_descripcion_Sort"] = $sThisSort;
		}else{
			if (!($bCtrl) && @$_SESSION["fotografia_x_descripcion_Sort"] <> "") { $_SESSION["fotografia_x_descripcion_Sort"] = "" ; }
		}

		// Field mapa
		if ($sOrder == "mapa") {
			$sSortField = "`mapa`";
			$sLastSort = @$_SESSION["fotografia_x_mapa_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["fotografia_x_mapa_Sort"] = $sThisSort;
		}else{
			if (!($bCtrl) && @$_SESSION["fotografia_x_mapa_Sort"] <> "") { $_SESSION["fotografia_x_mapa_Sort"] = "" ; }
		}

		// Field foto
		if ($sOrder == "foto") {
			$sSortField = "`foto`";
			$sLastSort = @$_SESSION["fotografia_x_foto_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["fotografia_x_foto_Sort"] = $sThisSort;
		}else{
			if (!($bCtrl) && @$_SESSION["fotografia_x_foto_Sort"] <> "") { $_SESSION["fotografia_x_foto_Sort"] = "" ; }
		}

		// Field pais_id
		if ($sOrder == "pais_id") {
			$sSortField = "`pais_id`";
			$sLastSort = @$_SESSION["fotografia_x_pais_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["fotografia_x_pais_id_Sort"] = $sThisSort;
		}else{
			if (!($bCtrl) && @$_SESSION["fotografia_x_pais_id_Sort"] <> "") { $_SESSION["fotografia_x_pais_id_Sort"] = "" ; }
		}

		// Field estado_id
		if ($sOrder == "estado_id") {
			$sSortField = "`estado_id`";
			$sLastSort = @$_SESSION["fotografia_x_estado_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["fotografia_x_estado_id_Sort"] = $sThisSort;
		}else{
			if (!($bCtrl) && @$_SESSION["fotografia_x_estado_id_Sort"] <> "") { $_SESSION["fotografia_x_estado_id_Sort"] = "" ; }
		}
		if ($bCtrl) {
			$sOrderBy = @$_SESSION["fotografia_OrderBy"];
			$pos = strpos($sOrderBy, $sSortField . " " . $sLastSort);
			if ($pos === false) {
				if ($sOrderBy <> "") { $sOrderBy .= ", "; }
				$sOrderBy .= $sSortField . " " . $sThisSort;
			}else{
				$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
			}
			$_SESSION["fotografia_OrderBy"] = $sOrderBy;
		}else{
			$_SESSION["fotografia_OrderBy"] = $sSortField . " " . $sThisSort;
		}
		$_SESSION["fotografia_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["fotografia_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["fotografia_OrderBy"] = $sOrderBy;
		$_SESSION["fotografia_x_solicitud_id_Sort"] = "ASC";
		$_SESSION["fotografia_x_titulo_Sort"] = "ASC";
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
		$_SESSION["fotografia_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["fotografia_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["fotografia_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["fotografia_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["fotografia_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["fotografia_REC"] = $nStartRec;
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
			$_SESSION["fotografia_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["fotografia_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["fotografia_OrderBy"] = $sOrderBy;
			if (@$_SESSION["fotografia_x_fotografia_id_Sort"] <> "") { $_SESSION["fotografia_x_fotografia_id_Sort"] = ""; }
			if (@$_SESSION["fotografia_x_solicitud_id_Sort"] <> "") { $_SESSION["fotografia_x_solicitud_id_Sort"] = ""; }
			if (@$_SESSION["fotografia_x_titulo_Sort"] <> "") { $_SESSION["fotografia_x_titulo_Sort"] = ""; }
			if (@$_SESSION["fotografia_x_descripcion_Sort"] <> "") { $_SESSION["fotografia_x_descripcion_Sort"] = ""; }
			if (@$_SESSION["fotografia_x_mapa_Sort"] <> "") { $_SESSION["fotografia_x_mapa_Sort"] = ""; }
			if (@$_SESSION["fotografia_x_foto_Sort"] <> "") { $_SESSION["fotografia_x_foto_Sort"] = ""; }
			if (@$_SESSION["fotografia_x_pais_id_Sort"] <> "") { $_SESSION["fotografia_x_pais_id_Sort"] = ""; }
			if (@$_SESSION["fotografia_x_estado_id_Sort"] <> "") { $_SESSION["fotografia_x_estado_id_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["fotografia_REC"] = $nStartRec;
	}
}
?>
