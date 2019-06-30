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
$x_recibo_id = Null;
$x_fecha_registro = Null;
$x_fecha_pago = Null;
$x_importe = Null;
$x_medio_pago_id = Null;
$x_referencia_pago = Null;
$x_referencia_pago_2 = Null;
$x_recibo_status_id = Null;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=recibo.xls');
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
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

// Handle Reset Command
ResetCmd();

// Set Up Inline Edit Parameters
$sAction = "";
$sKey = "";
SetUpInlineEdit($conn);

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
	$_SESSION["recibo_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["recibo_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["recibo_searchwhere"];
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
$sSql = "SELECT * FROM `recibo`";

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
<script type="text/javascript">
<!--
function EW_checkMyForm(EW_this) {
if (EW_this.x_fecha_registro && !EW_checkeurodate(EW_this.x_fecha_registro.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_registro, "TEXT", "Incorrect date, format = dd/mm/yyyy - fecha registro"))
		return false; 
}
if (EW_this.x_fecha_pago && !EW_hasValue(EW_this.x_fecha_pago, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_pago, "TEXT", "Please enter required field - fecha pago"))
		return false;
}
if (EW_this.x_fecha_pago && !EW_checkeurodate(EW_this.x_fecha_pago.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_pago, "TEXT", "Incorrect date, format = dd/mm/yyyy - fecha pago"))
		return false; 
}
if (EW_this.x_importe && !EW_hasValue(EW_this.x_importe, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_importe, "TEXT", "Please enter required field - importe"))
		return false;
}
if (EW_this.x_importe && !EW_checknumber(EW_this.x_importe.value)) {
	if (!EW_onError(EW_this, EW_this.x_importe, "TEXT", "Incorrect floating point number - importe"))
		return false; 
}
if (EW_this.x_medio_pago_id && !EW_hasValue(EW_this.x_medio_pago_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_medio_pago_id, "SELECT", "Please enter required field - medio pago id"))
		return false;
}
if (EW_this.x_recibo_status_id && !EW_hasValue(EW_this.x_recibo_status_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_recibo_status_id, "SELECT", "Please enter required field - recibo status id"))
		return false;
}
return true;
}

//-->
</script>
<?php } ?>
<?php if ($sExport == "") { ?>
<script type="text/javascript" src="popcalendar.js"></script>
<!-- New popup calendar -->
<!--link rel="stylesheet" type="text/css" media="all" href="calendar/calendar-win2k-1.css" title="win2k-1" /-->
<!--script type="text/javascript" src="calendar/calendar.js"></script-->
<!--script type="text/javascript" src="calendar/calendar-en.js"></script-->
<!--script type="text/javascript" src="calendar/calendar-setup.js"></script-->
<?php } ?>
<?php

// Set up Record Set
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
$nTotalRecs = phpmkr_num_rows($rs);
if ($nDisplayRecs <= 0) { // Display All Records
	$nDisplayRecs = $nTotalRecs;
}
$nStartRec = 1;
SetUpStartRec(); // Set Up Start Record Position
?>
<p><span class="phpmaker">TABLE: recibo
<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="php_recibolist_mas.php?export=excel">Export to Excel</a>
<?php } ?>
</span></p>
<?php if ($sExport == "") { ?>
<form action="php_recibolist_mas.php">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><span class="phpmaker">
			<input type="text" name="psearch" size="20">
			<input type="Submit" name="Submit" value="Search &nbsp;(*)">&nbsp;&nbsp;
			<a href="php_recibolist_mas.php?cmd=reset">Show all</a>&nbsp;&nbsp;
		</span></td>
	</tr>
	<tr><td><span class="phpmaker"><input type="radio" name="psearchtype" value="" checked>Exact phrase&nbsp;&nbsp;<input type="radio" name="psearchtype" value="AND">All words&nbsp;&nbsp;<input type="radio" name="psearchtype" value="OR">Any word</span></td></tr>
</table>
</form>
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
<form action="php_recibolist_mas.php" name="ewpagerform" id="ewpagerform">
<table border="0" cellspacing="1" cellpadding="4" bgcolor="#CCCCCC">
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
		<a href="php_recibolist_mas.php?start=<?php echo $PrevStart; ?>"><b>Prev</b></a>
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
		<a href="php_recibolist_mas.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
					<?php }
					$x += $nDisplayRecs;
					$y += 1;
				} elseif (($x >= ($dx1-$nDisplayRecs*$nRecRange)) && ($x <= ($dx2+$nDisplayRecs*$nRecRange))) {
					if ($x+$nRecRange*$nDisplayRecs < $nTotalRecs) { ?>
		<a href="php_recibolist_mas.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo ($y+$nRecRange-1);?></b></a>
					<?php } else {
						$ny=intval(($nTotalRecs-1)/$nDisplayRecs)+1;
							if ($ny == $y) { ?>
		<a href="php_recibolist_mas.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
							<?php } else { ?>
		<a href="php_recibolist_mas.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo $ny; ?></b></a>
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
		<a href="php_recibolist_mas.php?start=<?php echo $NextStart; ?>"><b>Next</b></a>
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
<form name="recibolist" id="recibolist" action="php_recibolist_mas.php" method="post">
<table border="0" cellspacing="1" cellpadding="4" bgcolor="#CCCCCC">
<?php if ($nTotalRecs > 0) { ?>
	<!-- Table header -->
	<tr bgcolor="#666666">
<?php if ($sExport == "") { ?>
<td>&nbsp;</td>
<?php } ?>
		<td valign="top"><span class="phpmaker" style="color: #FFFFFF;">
<?php if ($sExport <> "") { ?>
recibo id
<?php }else{ ?>
	<a href="php_recibolist_mas.php?order=<?php echo urlencode("recibo_id"); ?>" style="color: #FFFFFF;">recibo id<?php if (@$_SESSION["recibo_x_recibo_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["recibo_x_recibo_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span class="phpmaker" style="color: #FFFFFF;">
<?php if ($sExport <> "") { ?>
fecha registro
<?php }else{ ?>
	<a href="php_recibolist_mas.php?order=<?php echo urlencode("fecha_registro"); ?>" style="color: #FFFFFF;">fecha registro<?php if (@$_SESSION["recibo_x_fecha_registro_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["recibo_x_fecha_registro_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span class="phpmaker" style="color: #FFFFFF;">
<?php if ($sExport <> "") { ?>
fecha pago
<?php }else{ ?>
	<a href="php_recibolist_mas.php?order=<?php echo urlencode("fecha_pago"); ?>" style="color: #FFFFFF;">fecha pago<?php if (@$_SESSION["recibo_x_fecha_pago_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["recibo_x_fecha_pago_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span class="phpmaker" style="color: #FFFFFF;">
<?php if ($sExport <> "") { ?>
importe
<?php }else{ ?>
	<a href="php_recibolist_mas.php?order=<?php echo urlencode("importe"); ?>" style="color: #FFFFFF;">importe<?php if (@$_SESSION["recibo_x_importe_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["recibo_x_importe_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span class="phpmaker" style="color: #FFFFFF;">
<?php if ($sExport <> "") { ?>
medio pago id
<?php }else{ ?>
	<a href="php_recibolist_mas.php?order=<?php echo urlencode("medio_pago_id"); ?>" style="color: #FFFFFF;">medio pago id<?php if (@$_SESSION["recibo_x_medio_pago_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["recibo_x_medio_pago_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span class="phpmaker" style="color: #FFFFFF;">
<?php if ($sExport <> "") { ?>
referencia pago
<?php }else{ ?>
	<a href="php_recibolist_mas.php?order=<?php echo urlencode("referencia_pago"); ?>" style="color: #FFFFFF;">referencia pago&nbsp;(*)<?php if (@$_SESSION["recibo_x_referencia_pago_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["recibo_x_referencia_pago_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span class="phpmaker" style="color: #FFFFFF;">
<?php if ($sExport <> "") { ?>
referencia pago 2
<?php }else{ ?>
	<a href="php_recibolist_mas.php?order=<?php echo urlencode("referencia_pago_2"); ?>" style="color: #FFFFFF;">referencia pago 2&nbsp;(*)<?php if (@$_SESSION["recibo_x_referencia_pago_2_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["recibo_x_referencia_pago_2_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span class="phpmaker" style="color: #FFFFFF;">
<?php if ($sExport <> "") { ?>
recibo status id
<?php }else{ ?>
	<a href="php_recibolist_mas.php?order=<?php echo urlencode("recibo_status_id"); ?>" style="color: #FFFFFF;">recibo status id<?php if (@$_SESSION["recibo_x_recibo_status_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["recibo_x_recibo_status_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
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
	$sItemRowClass = " bgcolor=\"#FFFFFF\"";

	// Display alternate color for rows
	if ($nRecCount % 2 <> 0) {
		$sItemRowClass = " bgcolor=\"#F5F5F5\"";
	}

		// Load Key for record
		$sKey = $row["recibo_id"];
		if (@$_SESSION["project1_InlineEdit_Key"] == $sKey) {
			$sItemRowClass = " bgcolor=\"#FFFF99\"";
		}
		$x_recibo_id = $row["recibo_id"];
		$x_fecha_registro = $row["fecha_registro"];
		$x_fecha_pago = $row["fecha_pago"];
		$x_importe = $row["importe"];
		$x_medio_pago_id = $row["medio_pago_id"];
		$x_referencia_pago = $row["referencia_pago"];
		$x_referencia_pago_2 = $row["referencia_pago_2"];
		$x_recibo_status_id = $row["recibo_status_id"];
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
<?php if ($sExport == "") { ?>
<td><span class="phpmaker">
<?php if (@$_SESSION["project1_InlineEdit_Key"] == $sKey) { ?>
<a href="" onClick="if (EW_checkMyForm(document.recibolist)) document.recibolist.submit();return false;">Update</a>&nbsp;<a href="php_recibolist_mas.php?a=cancel">Cancel</a>
<input type="hidden" name="key" value="<?php echo htmlspecialchars($sKey); ?>">
<input type="hidden" name="a_list" value="update">
<?php } else { ?>
<a href="<?php if ((!is_null($sKey))) { echo "php_recibolist_mas.php?a=edit&key=" . urlencode($sKey); } else { echo "javascript:alert('Invalid Record! Key is null');"; } ?>">Inline Edit</a>
<?php } ?>
</span></td>
<?php } ?>
		<!-- recibo_id -->
		<td><span class="phpmaker">
<?php if (@$_SESSION["project1_InlineEdit_Key"] == $sKey) { // Edit Record ?>
<?php echo $x_recibo_id; ?><input type="hidden" name="x_recibo_id" value="<?php echo $x_recibo_id; ?>">
<?php } else { ?>
<?php echo $x_recibo_id; ?>
<?php } ?>
</span></td>
		<!-- fecha_registro -->
		<td><span class="phpmaker">
<?php echo FormatDateTime($x_fecha_registro,7); ?>
</span></td>
		<!-- fecha_pago -->
		<td><span class="phpmaker">
<?php if (@$_SESSION["project1_InlineEdit_Key"] == $sKey) { // Edit Record ?>
<input type="text" name="x_fecha_pago" id="x_fecha_pago" value="<?php echo FormatDateTime(@$x_fecha_pago,7); ?>">
&nbsp;<input type="image" src="images/ew_calendar.gif" alt="Pick a Date" onClick="popUpCalendar(this, this.form.x_fecha_pago,'dd/mm/yyyy');return false;">
<?php } else { ?>
<?php echo FormatDateTime($x_fecha_pago,7); ?>
<?php } ?>
</span></td>
		<!-- importe -->
		<td><span class="phpmaker">
<?php if (@$_SESSION["project1_InlineEdit_Key"] == $sKey) { // Edit Record ?>
<input type="text" name="x_importe" id="x_importe" size="30" value="<?php echo htmlspecialchars(@$x_importe) ?>">
<?php } else { ?>
<?php echo $x_importe; ?>
<?php } ?>
</span></td>
		<!-- medio_pago_id -->
		<td><span class="phpmaker">
<?php if (@$_SESSION["project1_InlineEdit_Key"] == $sKey) { // Edit Record ?>
<?php
$x_medio_pago_idList = "<select name=\"x_medio_pago_id\">";
$x_medio_pago_idList .= "<option value=''>Please Select</option>";
$sSqlWrk = "SELECT `medio_pago_id`, `descripcion` FROM `medio_pago`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_medio_pago_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["medio_pago_id"] == @$x_medio_pago_id) {
			$x_medio_pago_idList .= "' selected";
		}
		$x_medio_pago_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_medio_pago_idList .= "</select>";
echo $x_medio_pago_idList;
?>
<?php } else { ?>
<?php
if ((!is_null($x_medio_pago_id)) && ($x_medio_pago_id <> "")) {
	$sSqlWrk = "SELECT *  FROM `medio_pago`";
	$sTmp = $x_medio_pago_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE (`medio_pago_id` = " . $sTmp . ")";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_medio_pago_id = $x_medio_pago_id; // Backup Original Value
$x_medio_pago_id = $sTmp;
?>
<?php echo $x_medio_pago_id; ?>
<?php $x_medio_pago_id = $ox_medio_pago_id; // Restore Original Value ?>
<?php } ?>
</span></td>
		<!-- referencia_pago -->
		<td><span class="phpmaker">
<?php if (@$_SESSION["project1_InlineEdit_Key"] == $sKey) { // Edit Record ?>
<input type="text" name="x_referencia_pago" id="x_referencia_pago" size="30" maxlength="50" value="<?php echo htmlspecialchars(@$x_referencia_pago) ?>">
<?php } else { ?>
<?php echo $x_referencia_pago; ?>
<?php } ?>
</span></td>
		<!-- referencia_pago_2 -->
		<td><span class="phpmaker">
<?php if (@$_SESSION["project1_InlineEdit_Key"] == $sKey) { // Edit Record ?>
<input type="text" name="x_referencia_pago_2" id="x_referencia_pago_2" size="30" maxlength="50" value="<?php echo htmlspecialchars(@$x_referencia_pago_2) ?>">
<?php } else { ?>
<?php echo $x_referencia_pago_2; ?>
<?php } ?>
</span></td>
		<!-- recibo_status_id -->
		<td><span class="phpmaker">
<?php if (@$_SESSION["project1_InlineEdit_Key"] == $sKey) { // Edit Record ?>
<?php
$x_recibo_status_idList = "<select name=\"x_recibo_status_id\">";
$x_recibo_status_idList .= "<option value=''>Please Select</option>";
$sSqlWrk = "SELECT `recibo_status_id`, `descripcion` FROM `recibo_status`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_recibo_status_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["recibo_status_id"] == @$x_recibo_status_id) {
			$x_recibo_status_idList .= "' selected";
		}
		$x_recibo_status_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_recibo_status_idList .= "</select>";
echo $x_recibo_status_idList;
?>
<?php } else { ?>
<?php
if ((!is_null($x_recibo_status_id)) && ($x_recibo_status_id <> "")) {
	$sSqlWrk = "SELECT *  FROM `recibo_status`";
	$sTmp = $x_recibo_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE (`recibo_status_id` = " . $sTmp . ")";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_recibo_status_id = $x_recibo_status_id; // Backup Original Value
$x_recibo_status_id = $sTmp;
?>
<?php echo $x_recibo_status_id; ?>
<?php $x_recibo_status_id = $ox_recibo_status_id; // Restore Original Value ?>
<?php } ?>
</span></td>
	</tr>
<?php
	}
}
?>
</table>
</form>
<?php if (strtoupper($sAction) == "EDIT") { ?>
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
// Function SetUpInlineEdit
// - Set up Inline Edit parameters based on querystring parameters a & key
// - Variables setup: sAction, sKey, Session("Proj_InlineEdit_Key")

function SetUpInlineEdit($conn)
{

	// Get the keys for master table
	if (strlen(@$_GET["a"]) > 0) {
		$sAction = @$_GET["a"];
		if (strtoupper($sAction) == "EDIT") { // Change to Inline Edit Mode
			if (strlen(@$_GET["key"]) > 0) {
				$sKey = @$_GET["key"];
				if (LoadData($sKey,$conn)) {
					$_SESSION["project1_InlineEdit_Key"] = $sKey; // Set up Inline Edit key
				}
			}
		}
		elseif (strtoupper($sAction) == "CANCEL")  // Switch out of Inline Edit Mode
		{
			$_SESSION["project1_InlineEdit_Key"] = ""; // Clear Inline Edit key
		}
	}
	else
	{
		$sAction = @$_POST["a_list"];
		$sKey = @$_POST["key"];
		if (strtoupper($sAction) == "UPDATE") { // Update Record

			// Get fields from form
			global $x_recibo_id;
			$x_recibo_id = @$_POST["x_recibo_id"];
			global $x_fecha_registro;
			$x_fecha_registro = @$_POST["x_fecha_registro"];
			global $x_fecha_pago;
			$x_fecha_pago = @$_POST["x_fecha_pago"];
			global $x_importe;
			$x_importe = @$_POST["x_importe"];
			global $x_medio_pago_id;
			$x_medio_pago_id = @$_POST["x_medio_pago_id"];
			global $x_referencia_pago;
			$x_referencia_pago = @$_POST["x_referencia_pago"];
			global $x_referencia_pago_2;
			$x_referencia_pago_2 = @$_POST["x_referencia_pago_2"];
			global $x_recibo_status_id;
			$x_recibo_status_id = @$_POST["x_recibo_status_id"];
			if ($sKey == @$_SESSION["project1_InlineEdit_Key"]) {
				if (InlineEditData($sKey,$conn)) {
					$_SESSION["ewmsg"] = "Update Record Successful for Key = " . $sKey;
					$_SESSION["project1_InlineEdit_Key"] = ""; // Clear Inline Edit key
				}
			}
		}
		else
		{
			$_SESSION["project1_InlineEdit_Key"] = ""; // Clear Inline Edit key
		}
	}
}

//-------------------------------------------------------------------------------
// Function BasicSearchSQL
// - Build WHERE clause for a keyword

function BasicSearchSQL($Keyword)
{
	$sKeyword = (!get_magic_quotes_gpc()) ? addslashes($Keyword) : $Keyword;
	$BasicSearchSQL = "";
	$BasicSearchSQL.= "`referencia_pago` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`referencia_pago_2` LIKE '%" . $sKeyword . "%' OR ";
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

		// Field recibo_id
		if ($sOrder == "recibo_id") {
			$sSortField = "`recibo_id`";
			$sLastSort = @$_SESSION["recibo_x_recibo_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["recibo_x_recibo_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["recibo_x_recibo_id_Sort"] <> "") { @$_SESSION["recibo_x_recibo_id_Sort"] = ""; }
		}

		// Field fecha_registro
		if ($sOrder == "fecha_registro") {
			$sSortField = "`fecha_registro`";
			$sLastSort = @$_SESSION["recibo_x_fecha_registro_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["recibo_x_fecha_registro_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["recibo_x_fecha_registro_Sort"] <> "") { @$_SESSION["recibo_x_fecha_registro_Sort"] = ""; }
		}

		// Field fecha_pago
		if ($sOrder == "fecha_pago") {
			$sSortField = "`fecha_pago`";
			$sLastSort = @$_SESSION["recibo_x_fecha_pago_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["recibo_x_fecha_pago_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["recibo_x_fecha_pago_Sort"] <> "") { @$_SESSION["recibo_x_fecha_pago_Sort"] = ""; }
		}

		// Field importe
		if ($sOrder == "importe") {
			$sSortField = "`importe`";
			$sLastSort = @$_SESSION["recibo_x_importe_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["recibo_x_importe_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["recibo_x_importe_Sort"] <> "") { @$_SESSION["recibo_x_importe_Sort"] = ""; }
		}

		// Field medio_pago_id
		if ($sOrder == "medio_pago_id") {
			$sSortField = "`medio_pago_id`";
			$sLastSort = @$_SESSION["recibo_x_medio_pago_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["recibo_x_medio_pago_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["recibo_x_medio_pago_id_Sort"] <> "") { @$_SESSION["recibo_x_medio_pago_id_Sort"] = ""; }
		}

		// Field referencia_pago
		if ($sOrder == "referencia_pago") {
			$sSortField = "`referencia_pago`";
			$sLastSort = @$_SESSION["recibo_x_referencia_pago_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["recibo_x_referencia_pago_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["recibo_x_referencia_pago_Sort"] <> "") { @$_SESSION["recibo_x_referencia_pago_Sort"] = ""; }
		}

		// Field referencia_pago_2
		if ($sOrder == "referencia_pago_2") {
			$sSortField = "`referencia_pago_2`";
			$sLastSort = @$_SESSION["recibo_x_referencia_pago_2_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["recibo_x_referencia_pago_2_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["recibo_x_referencia_pago_2_Sort"] <> "") { @$_SESSION["recibo_x_referencia_pago_2_Sort"] = ""; }
		}

		// Field recibo_status_id
		if ($sOrder == "recibo_status_id") {
			$sSortField = "`recibo_status_id`";
			$sLastSort = @$_SESSION["recibo_x_recibo_status_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["recibo_x_recibo_status_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["recibo_x_recibo_status_id_Sort"] <> "") { @$_SESSION["recibo_x_recibo_status_id_Sort"] = ""; }
		}
		$_SESSION["recibo_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["recibo_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["recibo_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["recibo_OrderBy"] = $sOrderBy;
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
		$_SESSION["recibo_REC"] = $nStartRec;
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
			$_SESSION["recibo_REC"] = $nStartRec;
		}
		else
		{
			$nStartRec = @$_SESSION["recibo_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["recibo_REC"] = $nStartRec;
			}
		}
	}
	else
	{
		$nStartRec = @$_SESSION["recibo_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["recibo_REC"] = $nStartRec;
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
			$_SESSION["recibo_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}
		elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["recibo_searchwhere"] = $sSrchWhere;
			$_SESSION["project1_InlineEdit_Key"] = ""; // Clear Inline Edit key

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["recibo_OrderBy"] = $sOrderBy;
			if (@$_SESSION["recibo_x_recibo_id_Sort"] <> "") { $_SESSION["recibo_x_recibo_id_Sort"] = ""; }
			if (@$_SESSION["recibo_x_fecha_registro_Sort"] <> "") { $_SESSION["recibo_x_fecha_registro_Sort"] = ""; }
			if (@$_SESSION["recibo_x_fecha_pago_Sort"] <> "") { $_SESSION["recibo_x_fecha_pago_Sort"] = ""; }
			if (@$_SESSION["recibo_x_importe_Sort"] <> "") { $_SESSION["recibo_x_importe_Sort"] = ""; }
			if (@$_SESSION["recibo_x_medio_pago_id_Sort"] <> "") { $_SESSION["recibo_x_medio_pago_id_Sort"] = ""; }
			if (@$_SESSION["recibo_x_referencia_pago_Sort"] <> "") { $_SESSION["recibo_x_referencia_pago_Sort"] = ""; }
			if (@$_SESSION["recibo_x_referencia_pago_2_Sort"] <> "") { $_SESSION["recibo_x_referencia_pago_2_Sort"] = ""; }
			if (@$_SESSION["recibo_x_recibo_status_id_Sort"] <> "") { $_SESSION["recibo_x_recibo_status_id_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["recibo_REC"] = $nStartRec;
	}
}
?>
<?php

//-------------------------------------------------------------------------------
// Function LoadData
// - Load Data based on Key Value sKey
// - Variables setup: field variables

function LoadData($sKey,$conn)
{
	$sKeyWrk = "" . addslashes($sKey) . "";
	$sSql = "SELECT * FROM `recibo`";
	$sSql .= " WHERE `recibo_id` = " . $sKeyWrk;
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sGroupBy <> "") {
		$sSql .= " GROUP BY " . $sGroupBy;
	}
	if ($sHaving <> "") {
		$sSql .= " HAVING " . $sHaving;
	}
	if ($sOrderBy <> "") {
		$sSql .= " ORDER BY " . $sOrderBy;
	}
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
	if (phpmkr_num_rows($rs) == 0) {
		$LoadData = false;
	}else{
		$LoadData = true;
		$row = phpmkr_fetch_array($rs);

		// Get the field contents
		$GLOBALS["x_recibo_id"] = $row["recibo_id"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
		$GLOBALS["x_fecha_pago"] = $row["fecha_pago"];
		$GLOBALS["x_importe"] = $row["importe"];
		$GLOBALS["x_medio_pago_id"] = $row["medio_pago_id"];
		$GLOBALS["x_referencia_pago"] = $row["referencia_pago"];
		$GLOBALS["x_referencia_pago_2"] = $row["referencia_pago_2"];
		$GLOBALS["x_recibo_status_id"] = $row["recibo_status_id"];
	}
	phpmkr_free_result($rs);
	return $LoadData;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function EditData
// - Edit Data based on Key Value sKey
// - Variables used: field variables

function InlineEditData($sKey,$conn)
{

	// Open record
	$sKeyWrk = "" . addslashes($sKey) . "";
	$sSql = "SELECT * FROM `recibo`";
	$sSql .= " WHERE `recibo_id` = " . $sKeyWrk;
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sGroupBy <> "") {
		$sSql .= " GROUP BY " . $sGroupBy;
	}
	if ($sHaving <> "") {
		$sSql .= " HAVING " . $sHaving;
	}
	if ($sOrderBy <> "") {
		$sSql .= " ORDER BY " . $sOrderBy;
	}
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
	if (phpmkr_num_rows($rs) == 0) {
		$InlineEditData = false; // Update Failed
	}else{
		$theValue = ($GLOBALS["x_fecha_pago"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_pago"]) . "'" : "NULL";
		$fieldList["`fecha_pago`"] = $theValue;
		$theValue = ($GLOBALS["x_importe"] != "") ? " '" . doubleval($GLOBALS["x_importe"]) . "'" : "NULL";
		$fieldList["`importe`"] = $theValue;
		$theValue = ($GLOBALS["x_medio_pago_id"] != "") ? intval($GLOBALS["x_medio_pago_id"]) : "NULL";
		$fieldList["`medio_pago_id`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_pago"]) : $GLOBALS["x_referencia_pago"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`referencia_pago`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_pago_2"]) : $GLOBALS["x_referencia_pago_2"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`referencia_pago_2`"] = $theValue;
		$theValue = ($GLOBALS["x_recibo_status_id"] != "") ? intval($GLOBALS["x_recibo_status_id"]) : "NULL";
		$fieldList["`recibo_status_id`"] = $theValue;

		// update
		$sSql = "UPDATE `recibo` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE `recibo_id` =". $sKeyWrk;
		phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
		$InlineEditData = true; // Update Successful
	}
	return $InlineEditData;
}
?>
