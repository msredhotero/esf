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
$x_inciso_referencia = Null; 
$ox_inciso_referencia = Null;
$x_solicitud_inciso_id = Null; 
$ox_solicitud_inciso_id = Null;
$x_referencia_id = Null; 
$ox_referencia_id = Null;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=inciso_referencia.xls');
}
if ($sExport == "word") {
	header('Content-Type: application/vnd.ms-word');
	header('Content-Disposition: attachment; filename=inciso_referencia.doc');
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
$sSql = "SELECT * FROM `inciso_referencia`";

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
<p><span class="phpmaker">TABLE: inciso referencia
<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="php_inciso_referencialist.php?export=excel">Export to Excel</a>
&nbsp;&nbsp;<a href="php_inciso_referencialist.php?export=word">Export to Word</a>
<?php } ?>
</span></p>
<?php if ($sExport == "") { ?>
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><span class="phpmaker"><a href="php_inciso_referenciaadd.php">Add</a></span></td>
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
<form action="php_inciso_referencialist.php" name="ewpagerform" id="ewpagerform">
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
		<a href="php_inciso_referencialist.php?start=<?php echo $PrevStart; ?>"><b>Prev</b></a>
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
		<a href="php_inciso_referencialist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
					<?php }
					$x += $nDisplayRecs;
					$y += 1;
				} elseif (($x >= ($dx1-$nDisplayRecs*$nRecRange)) && ($x <= ($dx2+$nDisplayRecs*$nRecRange))) {
					if ($x+$nRecRange*$nDisplayRecs < $nTotalRecs) { ?>
		<a href="php_inciso_referencialist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo ($y+$nRecRange-1);?></b></a>
					<?php } else {
						$ny=intval(($nTotalRecs-1)/$nDisplayRecs)+1;
							if ($ny == $y) { ?>
		<a href="php_inciso_referencialist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
							<?php } else { ?>
		<a href="php_inciso_referencialist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo $ny; ?></b></a>
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
		<a href="php_inciso_referencialist.php?start=<?php echo $NextStart; ?>"><b>Next</b></a>
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
	<a href="php_inciso_referencialist.php?order=<?php echo urlencode("inciso_referencia"); ?>">No<?php if (@$_SESSION["inciso_referencia_x_inciso_referencia_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["inciso_referencia_x_inciso_referencia_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Inciso
<?php }else{ ?>
	<a href="php_inciso_referencialist.php?order=<?php echo urlencode("solicitud_inciso_id"); ?>">Inciso<?php if (@$_SESSION["inciso_referencia_x_solicitud_inciso_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["inciso_referencia_x_solicitud_inciso_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Referencia
<?php }else{ ?>
	<a href="php_inciso_referencialist.php?order=<?php echo urlencode("referencia_id"); ?>">Referencia<?php if (@$_SESSION["inciso_referencia_x_referencia_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["inciso_referencia_x_referencia_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
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
		$x_inciso_referencia = $row["inciso_referencia"];
		$x_solicitud_inciso_id = $row["solicitud_inciso_id"];
		$x_referencia_id = $row["referencia_id"];
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>
<?php if ($sExport == "") { ?>
<td><span class="phpmaker"><a href="<?php if ($x_inciso_referencia <> "") {echo "php_inciso_referenciaview.php?inciso_referencia=" . urlencode($x_inciso_referencia); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">View</a></span></td>
<td><span class="phpmaker"><a href="<?php if ($x_inciso_referencia <> "") {echo "php_inciso_referenciaedit.php?inciso_referencia=" . urlencode($x_inciso_referencia); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Edit</a></span></td>
<td><span class="phpmaker"><a href="<?php if ($x_inciso_referencia <> "") {echo "php_inciso_referenciaadd.php?inciso_referencia=" . urlencode($x_inciso_referencia); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Copy</a></span></td>
<td><span class="phpmaker"><input type="checkbox" name="key_d[]" value="<?php echo $x_inciso_referencia; ?>" class="phpmaker" onclick='ew_clickmultidelete(this);'>Delete</span></td>
<?php } ?>
		<!-- inciso_referencia -->
		<td><span>
<?php echo $x_inciso_referencia; ?>
</span></td>
		<!-- solicitud_inciso_id -->
		<td><span>
<?php
if ((!is_null($x_solicitud_inciso_id)) && ($x_solicitud_inciso_id <> "")) {
	$sSqlWrk = "SELECT `solicitud_inciso_id` FROM `solicitud_inciso`";
	$sTmp = $x_solicitud_inciso_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `solicitud_inciso_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["solicitud_inciso_id"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_solicitud_inciso_id = $x_solicitud_inciso_id; // Backup Original Value
$x_solicitud_inciso_id = $sTmp;
?>
<?php echo $x_solicitud_inciso_id; ?>
<?php $x_solicitud_inciso_id = $ox_solicitud_inciso_id; // Restore Original Value ?>
</span></td>
		<!-- referencia_id -->
		<td><span>
<?php
if ((!is_null($x_referencia_id)) && ($x_referencia_id <> "")) {
	$sSqlWrk = "SELECT `referencia_id` FROM `referencia`";
	$sTmp = $x_referencia_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `referencia_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["referencia_id"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_referencia_id = $x_referencia_id; // Backup Original Value
$x_referencia_id = $sTmp;
?>
<?php echo $x_referencia_id; ?>
<?php $x_referencia_id = $ox_referencia_id; // Restore Original Value ?>
</span></td>
	</tr>
<?php
	}
}
?>
</table>
<?php if ($sExport == "") { ?>
<?php if ($nRecActual > 0) { ?>
<p><input type="button" name="btndelete" value="DELETE SELECTED" onClick="if (!EW_selected(this)) alert('No records selected'); else {this.form.action='php_inciso_referenciadelete.php';this.form.encoding='application/x-www-form-urlencoded';this.form.submit();}"></p>
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

		// Field inciso_referencia
		if ($sOrder == "inciso_referencia") {
			$sSortField = "`inciso_referencia`";
			$sLastSort = @$_SESSION["inciso_referencia_x_inciso_referencia_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["inciso_referencia_x_inciso_referencia_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["inciso_referencia_x_inciso_referencia_Sort"] <> "") { @$_SESSION["inciso_referencia_x_inciso_referencia_Sort"] = ""; }
		}

		// Field solicitud_inciso_id
		if ($sOrder == "solicitud_inciso_id") {
			$sSortField = "`solicitud_inciso_id`";
			$sLastSort = @$_SESSION["inciso_referencia_x_solicitud_inciso_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["inciso_referencia_x_solicitud_inciso_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["inciso_referencia_x_solicitud_inciso_id_Sort"] <> "") { @$_SESSION["inciso_referencia_x_solicitud_inciso_id_Sort"] = ""; }
		}

		// Field referencia_id
		if ($sOrder == "referencia_id") {
			$sSortField = "`referencia_id`";
			$sLastSort = @$_SESSION["inciso_referencia_x_referencia_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["inciso_referencia_x_referencia_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["inciso_referencia_x_referencia_id_Sort"] <> "") { @$_SESSION["inciso_referencia_x_referencia_id_Sort"] = ""; }
		}
		$_SESSION["inciso_referencia_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["inciso_referencia_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["inciso_referencia_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["inciso_referencia_OrderBy"] = $sOrderBy;
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
		$_SESSION["inciso_referencia_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["inciso_referencia_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["inciso_referencia_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["inciso_referencia_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["inciso_referencia_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["inciso_referencia_REC"] = $nStartRec;
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
			$_SESSION["inciso_referencia_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["inciso_referencia_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["inciso_referencia_OrderBy"] = $sOrderBy;
			if (@$_SESSION["inciso_referencia_x_inciso_referencia_Sort"] <> "") { $_SESSION["inciso_referencia_x_inciso_referencia_Sort"] = ""; }
			if (@$_SESSION["inciso_referencia_x_solicitud_inciso_id_Sort"] <> "") { $_SESSION["inciso_referencia_x_solicitud_inciso_id_Sort"] = ""; }
			if (@$_SESSION["inciso_referencia_x_referencia_id_Sort"] <> "") { $_SESSION["inciso_referencia_x_referencia_id_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["inciso_referencia_REC"] = $nStartRec;
	}
}
?>
