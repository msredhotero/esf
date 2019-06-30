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


$x_solicitud_id = @$_GET["key"];
if (($x_solicitud_id == "") || ((is_null($x_solicitud_id)))) {
	$x_solicitud_id = @$_POST["solicitud_id"];
	if (($x_solicitud_id == "") || ((is_null($x_solicitud_id)))) {	
		echo "Solicitud no localizada.";
		exit();
	}
}

?>
<?php

// Initialize common variables
$x_solicitud_inciso_id = Null; 
$ox_solicitud_inciso_id = Null;
$x_fecha_registro = Null; 
$ox_fecha_registro = Null;
$x_cliente_id = Null; 
$ox_cliente_id = Null;
$x_ingreso_id = Null; 
$ox_ingreso_id = Null;
$x_garantia_id = Null; 
$ox_garantia_id = Null;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=solicitud_inciso.xls');
}
if ($sExport == "word") {
	header('Content-Type: application/vnd.ms-word');
	header('Content-Disposition: attachment; filename=solicitud_inciso.doc');
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

// Build SQL
$sSql = "SELECT * FROM solicitud_inciso";

// Load Default Filter
$sDefaultFilter = "";
$sGroupBy = "";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = "";

// Build WHERE condition
$sDbWhere = "(solicitud_inciso.solicitud_id = $x_solicitud_id) AND ";
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Financiera CRECE</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">td img {display: block;}</style>
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#FFFFFF">
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
<?php if ($sExport == "") { ?>
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><span class="phpmaker">
		<?php
		$sSqlWrk = "SELECT `folio` FROM `solicitud`";
		$sTmp = $x_solicitud_id;
		$sTmp = addslashes($sTmp);
		$sSqlWrk .= " WHERE `solicitud_id` = " . $sTmp . "";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
			echo "Folio: ".$rowwrk["folio"];
		}
		@phpmkr_free_result($rswrk);
		?>
		</span></td>
	</tr>
	<tr>
		<td><span class="phpmaker"><a href="php_solicitud_incisoadd.php?key=<?php echo $x_solicitud_id; ?>">Agregar Nuevo Asociado</a></span></td>
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
<?php if ($nTotalRecs > 0)  { ?>
<form method="post">
<table id="ewlistmain" class="ewTable_small">
	<!-- Table header -->
	<tr class="ewTableHeader">
<?php if ($sExport == "") { ?>
<td width="35">&nbsp;</td>
<td width="42">&nbsp;</td>
<td width="73"><input type="checkbox" name="checkall" class="phpmaker" onClick="EW_selectKey(this);"></td>
<?php } ?>
		<td width="75" valign="top"><span>
<?php if ($sExport <> "") { ?>
No
<?php }else{ ?>
	<a href="php_solicitud_incisolist.php?key=<?php echo $x_solicitud_id; ?>&order=<?php echo urlencode("solicitud_inciso_id"); ?>">No<?php if (@$_SESSION["solicitud_inciso_x_solicitud_inciso_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["solicitud_inciso_x_solicitud_inciso_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td width="121" valign="top"><span>
<?php if ($sExport <> "") { ?>
Fecha de registro
<?php }else{ ?>
	<a href="php_solicitud_incisolist.php?key=<?php echo $x_solicitud_id; ?>&order=<?php echo urlencode("fecha_registro"); ?>">Fecha de registro<?php if (@$_SESSION["solicitud_inciso_x_fecha_registro_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["solicitud_inciso_x_fecha_registro_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td width="426" valign="top"><span>
<?php if ($sExport <> "") { ?>
Socio
<?php }else{ ?>
	<a href="php_solicitud_incisolist.php?key=<?php echo $x_solicitud_id; ?>&order=<?php echo urlencode("cliente_id"); ?>">Socio<?php if (@$_SESSION["solicitud_inciso_x_cliente_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["solicitud_inciso_x_cliente_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
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
		$x_solicitud_inciso_id = $row["solicitud_inciso_id"];
		$x_solicitud_id = $row["solicitud_id"];
		$x_fecha_registro = $row["fecha_registro"];
		$x_cliente_id = $row["cliente_id"];
		$x_ingreso_id = $row["ingreso_id"];
		$x_garantia_id = $row["garantia_id"];
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>
<?php if ($sExport == "") { ?>
<td><span class="phpmaker"><a href="<?php if ($x_solicitud_inciso_id <> "") {echo "php_solicitud_incisoview.php?key=$x_solicitud_id&solicitud_inciso_id=" . urlencode($x_solicitud_inciso_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Ver</a></span></td>
<td><span class="phpmaker"><a href="<?php if ($x_solicitud_inciso_id <> "") {echo "php_solicitud_incisoedit.php?key=$x_solicitud_id&solicitud_inciso_id=" . urlencode($x_solicitud_inciso_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Editar</a></span></td>
<td><span class="phpmaker"><input type="checkbox" name="key_d[]" value="<?php echo $x_solicitud_inciso_id; ?>" class="phpmaker" onclick='ew_clickmultidelete(this);'> 
Eliminar
</span></td>
<?php } ?>
		<!-- solicitud_inciso_id -->
		<td align="center"><span>
<?php echo $x_solicitud_inciso_id; ?>
</span></td>
		<!-- fecha_registro -->
		<td><span>
<?php echo FormatDateTime($x_fecha_registro,7); ?>
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
	</tr>
<?php
	}
}
?>
</table>
<?php if ($sExport == "") { ?>
<?php if ($nRecActual > 0) { ?>
<p><input type="button" name="btndelete" value="ELIMINAR SELECCIONADOS" onClick="if (!EW_selected(this)) alert('No ha seleccionado asociados'); else {this.form.action='php_solicitud_incisodelete.php?key=<?php echo $x_solicitud_id; ?>';this.form.encoding='application/x-www-form-urlencoded';this.form.submit();}">
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
</body>
</html>
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

		// Field solicitud_inciso_id
		if ($sOrder == "solicitud_inciso_id") {
			$sSortField = "`solicitud_inciso_id`";
			$sLastSort = @$_SESSION["solicitud_inciso_x_solicitud_inciso_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["solicitud_inciso_x_solicitud_inciso_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["solicitud_inciso_x_solicitud_inciso_id_Sort"] <> "") { @$_SESSION["solicitud_inciso_x_solicitud_inciso_id_Sort"] = ""; }
		}

		// Field solicitud_id
		if ($sOrder == "solicitud_id") {
			$sSortField = "`solicitud_id`";
			$sLastSort = @$_SESSION["solicitud_inciso_x_solicitud_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["solicitud_inciso_x_solicitud_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["solicitud_inciso_x_solicitud_id_Sort"] <> "") { @$_SESSION["solicitud_inciso_x_solicitud_id_Sort"] = ""; }
		}

		// Field fecha_registro
		if ($sOrder == "fecha_registro") {
			$sSortField = "`fecha_registro`";
			$sLastSort = @$_SESSION["solicitud_inciso_x_fecha_registro_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["solicitud_inciso_x_fecha_registro_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["solicitud_inciso_x_fecha_registro_Sort"] <> "") { @$_SESSION["solicitud_inciso_x_fecha_registro_Sort"] = ""; }
		}

		// Field cliente_id
		if ($sOrder == "cliente_id") {
			$sSortField = "`cliente_id`";
			$sLastSort = @$_SESSION["solicitud_inciso_x_cliente_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["solicitud_inciso_x_cliente_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["solicitud_inciso_x_cliente_id_Sort"] <> "") { @$_SESSION["solicitud_inciso_x_cliente_id_Sort"] = ""; }
		}

		// Field ingreso_id
		if ($sOrder == "ingreso_id") {
			$sSortField = "`ingreso_id`";
			$sLastSort = @$_SESSION["solicitud_inciso_x_ingreso_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["solicitud_inciso_x_ingreso_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["solicitud_inciso_x_ingreso_id_Sort"] <> "") { @$_SESSION["solicitud_inciso_x_ingreso_id_Sort"] = ""; }
		}

		// Field garantia_id
		if ($sOrder == "garantia_id") {
			$sSortField = "`garantia_id`";
			$sLastSort = @$_SESSION["solicitud_inciso_x_garantia_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["solicitud_inciso_x_garantia_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["solicitud_inciso_x_garantia_id_Sort"] <> "") { @$_SESSION["solicitud_inciso_x_garantia_id_Sort"] = ""; }
		}
		$_SESSION["solicitud_inciso_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["solicitud_inciso_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["solicitud_inciso_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["solicitud_inciso_OrderBy"] = $sOrderBy;
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
		$_SESSION["solicitud_inciso_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["solicitud_inciso_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["solicitud_inciso_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["solicitud_inciso_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["solicitud_inciso_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["solicitud_inciso_REC"] = $nStartRec;
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
			$_SESSION["solicitud_inciso_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["solicitud_inciso_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["solicitud_inciso_OrderBy"] = $sOrderBy;
			if (@$_SESSION["solicitud_inciso_x_solicitud_inciso_id_Sort"] <> "") { $_SESSION["solicitud_inciso_x_solicitud_inciso_id_Sort"] = ""; }
			if (@$_SESSION["solicitud_inciso_x_solicitud_id_Sort"] <> "") { $_SESSION["solicitud_inciso_x_solicitud_id_Sort"] = ""; }
			if (@$_SESSION["solicitud_inciso_x_fecha_registro_Sort"] <> "") { $_SESSION["solicitud_inciso_x_fecha_registro_Sort"] = ""; }
			if (@$_SESSION["solicitud_inciso_x_cliente_id_Sort"] <> "") { $_SESSION["solicitud_inciso_x_cliente_id_Sort"] = ""; }
			if (@$_SESSION["solicitud_inciso_x_ingreso_id_Sort"] <> "") { $_SESSION["solicitud_inciso_x_ingreso_id_Sort"] = ""; }
			if (@$_SESSION["solicitud_inciso_x_garantia_id_Sort"] <> "") { $_SESSION["solicitud_inciso_x_garantia_id_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["solicitud_inciso_REC"] = $nStartRec;
	}
}
?>
