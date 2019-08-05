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
$x_creditoSolidario_id = Null;
$x_nombre_grupo = Null;
$x_promotor = Null;
$x_representante_sugerido = Null;
$x_tesorero = Null;
$x_numero_integrantes = Null;
$x_integrante_1 = Null;
$x_monto_1 = Null;
$x_integrante_2 = Null;
$x_monto_2 = Null;
$x_integrante_3 = Null;
$x_monto_3 = Null;
$x_integrante_4 = Null;
$x_monto_4 = Null;
$x_integrante_5 = Null;
$x_monto_5 = Null;
$x_integrante_6 = Null;
$x_monto_6 = Null;
$x_integrante_7 = Null;
$x_monto_7 = Null;
$x_integrante_8 = Null;
$x_monto_8 = Null;
$x_integrante_9 = Null;
$x_monto_9 = Null;
$x_integrante_10 = Null;
$x_monto_10 = Null;
$x_monto_total = Null;
$x_fecha_registro = Null;
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
$conn = phpmkr_db_connect(HOST, USER, PASS,DB);

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
	$HTTP_SESSION_VARS["creditosolidario_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$HTTP_SESSION_VARS["creditosolidario_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$HTTP_SESSION_VARS["creditosolidario_searchwhere"];
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
$sSql = "SELECT * FROM `creditosolidario`";

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
<p><span class="phpmaker">TABLE: creditosolidario
</span></p>
<form action="creditosolidariolist.php">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><span class="phpmaker">
			<input type="text" name="psearch" size="20">
			<input type="Submit" name="Submit" value="Search &nbsp;(*)">&nbsp;&nbsp;
			<a href="creditosolidariolist.php?cmd=reset">Show all</a>&nbsp;&nbsp;
		</span></td>
	</tr>
	<tr><td><span class="phpmaker"><input type="radio" name="psearchtype" value="" checked>Exact phrase&nbsp;&nbsp;<input type="radio" name="psearchtype" value="AND">All words&nbsp;&nbsp;<input type="radio" name="psearchtype" value="OR">Any word</span></td></tr>
</table>
</form>
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><span class="phpmaker"><a href="creditosolidarioadd.php">Add</a></span></td>
	</tr>
</table>
<p>
<?php
if (@$HTTP_SESSION_VARS["ewmsg"] <> "") {
?>
<p><span class="phpmaker" style="color: Red;"><?php echo $HTTP_SESSION_VARS["ewmsg"]; ?></span></p>
<?php
	$HTTP_SESSION_VARS["ewmsg"] = ""; // Clear message
}
?>
<form method="post">
<table class="ewTable">
<?php if ($nTotalRecs > 0) { ?>
	<!-- Table header -->
	<tr class="ewTableHeader">
		<td valign="top"><span>
	<a href="creditosolidariolist.php?order=<?php echo urlencode("creditoSolidario_id"); ?>" style="color: #FFFFFF;">credito Solidario id<?php if (@$HTTP_SESSION_VARS["creditosolidario_x_creditoSolidario_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["creditosolidario_x_creditoSolidario_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="creditosolidariolist.php?order=<?php echo urlencode("nombre_grupo"); ?>" style="color: #FFFFFF;">nombre grupo&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["creditosolidario_x_nombre_grupo_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["creditosolidario_x_nombre_grupo_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="creditosolidariolist.php?order=<?php echo urlencode("promotor"); ?>" style="color: #FFFFFF;">promotor&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["creditosolidario_x_promotor_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["creditosolidario_x_promotor_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="creditosolidariolist.php?order=<?php echo urlencode("representante_sugerido"); ?>" style="color: #FFFFFF;">representante sugerido&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["creditosolidario_x_representante_sugerido_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["creditosolidario_x_representante_sugerido_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="creditosolidariolist.php?order=<?php echo urlencode("tesorero"); ?>" style="color: #FFFFFF;">tesorero&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["creditosolidario_x_tesorero_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["creditosolidario_x_tesorero_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="creditosolidariolist.php?order=<?php echo urlencode("numero_integrantes"); ?>" style="color: #FFFFFF;">numero integrantes<?php if (@$HTTP_SESSION_VARS["creditosolidario_x_numero_integrantes_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["creditosolidario_x_numero_integrantes_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="creditosolidariolist.php?order=<?php echo urlencode("integrante_1"); ?>" style="color: #FFFFFF;">integrante 1&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_1_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_1_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="creditosolidariolist.php?order=<?php echo urlencode("monto_1"); ?>" style="color: #FFFFFF;">monto 1<?php if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_1_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["creditosolidario_x_monto_1_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="creditosolidariolist.php?order=<?php echo urlencode("integrante_2"); ?>" style="color: #FFFFFF;">integrante 2&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_2_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_2_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="creditosolidariolist.php?order=<?php echo urlencode("monto_2"); ?>" style="color: #FFFFFF;">monto 2<?php if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_2_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["creditosolidario_x_monto_2_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="creditosolidariolist.php?order=<?php echo urlencode("integrante_3"); ?>" style="color: #FFFFFF;">integrante 3&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_3_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_3_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="creditosolidariolist.php?order=<?php echo urlencode("monto_3"); ?>" style="color: #FFFFFF;">monto 3<?php if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_3_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["creditosolidario_x_monto_3_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="creditosolidariolist.php?order=<?php echo urlencode("integrante_4"); ?>" style="color: #FFFFFF;">integrante 4&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_4_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_4_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="creditosolidariolist.php?order=<?php echo urlencode("monto_4"); ?>" style="color: #FFFFFF;">monto 4<?php if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_4_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["creditosolidario_x_monto_4_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="creditosolidariolist.php?order=<?php echo urlencode("integrante_5"); ?>" style="color: #FFFFFF;">integrante 5&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_5_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_5_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="creditosolidariolist.php?order=<?php echo urlencode("monto_5"); ?>" style="color: #FFFFFF;">monto 5<?php if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_5_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["creditosolidario_x_monto_5_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="creditosolidariolist.php?order=<?php echo urlencode("integrante_6"); ?>" style="color: #FFFFFF;">integrante 6&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_6_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_6_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="creditosolidariolist.php?order=<?php echo urlencode("monto_6"); ?>" style="color: #FFFFFF;">monto 6<?php if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_6_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["creditosolidario_x_monto_6_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="creditosolidariolist.php?order=<?php echo urlencode("integrante_7"); ?>" style="color: #FFFFFF;">integrante 7&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_7_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_7_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="creditosolidariolist.php?order=<?php echo urlencode("monto_7"); ?>" style="color: #FFFFFF;">monto 7<?php if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_7_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["creditosolidario_x_monto_7_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="creditosolidariolist.php?order=<?php echo urlencode("integrante_8"); ?>" style="color: #FFFFFF;">integrante 8&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_8_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_8_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="creditosolidariolist.php?order=<?php echo urlencode("monto_8"); ?>" style="color: #FFFFFF;">monto 8<?php if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_8_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["creditosolidario_x_monto_8_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="creditosolidariolist.php?order=<?php echo urlencode("integrante_9"); ?>" style="color: #FFFFFF;">integrante 9&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_9_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_9_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="creditosolidariolist.php?order=<?php echo urlencode("monto_9"); ?>" style="color: #FFFFFF;">monto 9<?php if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_9_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["creditosolidario_x_monto_9_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="creditosolidariolist.php?order=<?php echo urlencode("integrante_10"); ?>" style="color: #FFFFFF;">integrante 10&nbsp;(*)<?php if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_10_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_10_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="creditosolidariolist.php?order=<?php echo urlencode("monto_10"); ?>" style="color: #FFFFFF;">monto 10<?php if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_10_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["creditosolidario_x_monto_10_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="creditosolidariolist.php?order=<?php echo urlencode("monto_total"); ?>" style="color: #FFFFFF;">monto total<?php if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_total_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["creditosolidario_x_monto_total_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="creditosolidariolist.php?order=<?php echo urlencode("fecha_registro"); ?>" style="color: #FFFFFF;">fecha registro<?php if (@$HTTP_SESSION_VARS["creditosolidario_x_fecha_registro_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$HTTP_SESSION_VARS["creditosolidario_x_fecha_registro_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
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
		$sKey = $row["creditoSolidario_id"];
		$x_creditoSolidario_id = $row["creditoSolidario_id"];
		$x_nombre_grupo = $row["nombre_grupo"];
		$x_promotor = $row["promotor"];
		$x_representante_sugerido = $row["representante_sugerido"];
		$x_tesorero = $row["tesorero"];
		$x_numero_integrantes = $row["numero_integrantes"];
		$x_integrante_1 = $row["integrante_1"];
		$x_monto_1 = $row["monto_1"];
		$x_integrante_2 = $row["integrante_2"];
		$x_monto_2 = $row["monto_2"];
		$x_integrante_3 = $row["integrante_3"];
		$x_monto_3 = $row["monto_3"];
		$x_integrante_4 = $row["integrante_4"];
		$x_monto_4 = $row["monto_4"];
		$x_integrante_5 = $row["integrante_5"];
		$x_monto_5 = $row["monto_5"];
		$x_integrante_6 = $row["integrante_6"];
		$x_monto_6 = $row["monto_6"];
		$x_integrante_7 = $row["integrante_7"];
		$x_monto_7 = $row["monto_7"];
		$x_integrante_8 = $row["integrante_8"];
		$x_monto_8 = $row["monto_8"];
		$x_integrante_9 = $row["integrante_9"];
		$x_monto_9 = $row["monto_9"];
		$x_integrante_10 = $row["integrante_10"];
		$x_monto_10 = $row["monto_10"];
		$x_monto_total = $row["monto_total"];
		$x_fecha_registro = $row["fecha_registro"];
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
		<!-- creditoSolidario_id -->
		<td><span>
<?php echo $x_creditoSolidario_id; ?>
</span></td>
		<!-- nombre_grupo -->
		<td><span>
<?php echo $x_nombre_grupo; ?>
</span></td>
		<!-- promotor -->
		<td><span>
<?php echo $x_promotor; ?>
</span></td>
		<!-- representante_sugerido -->
		<td><span>
<?php echo $x_representante_sugerido; ?>
</span></td>
		<!-- tesorero -->
		<td><span>
<?php echo $x_tesorero; ?>
</span></td>
		<!-- numero_integrantes -->
		<td><span>
<?php echo $x_numero_integrantes; ?>
</span></td>
		<!-- integrante_1 -->
		<td><span>
<?php echo $x_integrante_1; ?>
</span></td>
		<!-- monto_1 -->
		<td><span>
<?php echo $x_monto_1; ?>
</span></td>
		<!-- integrante_2 -->
		<td><span>
<?php echo $x_integrante_2; ?>
</span></td>
		<!-- monto_2 -->
		<td><span>
<?php echo $x_monto_2; ?>
</span></td>
		<!-- integrante_3 -->
		<td><span>
<?php echo $x_integrante_3; ?>
</span></td>
		<!-- monto_3 -->
		<td><span>
<?php echo $x_monto_3; ?>
</span></td>
		<!-- integrante_4 -->
		<td><span>
<?php echo $x_integrante_4; ?>
</span></td>
		<!-- monto_4 -->
		<td><span>
<?php echo $x_monto_4; ?>
</span></td>
		<!-- integrante_5 -->
		<td><span>
<?php echo $x_integrante_5; ?>
</span></td>
		<!-- monto_5 -->
		<td><span>
<?php echo $x_monto_5; ?>
</span></td>
		<!-- integrante_6 -->
		<td><span>
<?php echo $x_integrante_6; ?>
</span></td>
		<!-- monto_6 -->
		<td><span>
<?php echo $x_monto_6; ?>
</span></td>
		<!-- integrante_7 -->
		<td><span>
<?php echo $x_integrante_7; ?>
</span></td>
		<!-- monto_7 -->
		<td><span>
<?php echo $x_monto_7; ?>
</span></td>
		<!-- integrante_8 -->
		<td><span>
<?php echo $x_integrante_8; ?>
</span></td>
		<!-- monto_8 -->
		<td><span>
<?php echo $x_monto_8; ?>
</span></td>
		<!-- integrante_9 -->
		<td><span>
<?php echo $x_integrante_9; ?>
</span></td>
		<!-- monto_9 -->
		<td><span>
<?php echo $x_monto_9; ?>
</span></td>
		<!-- integrante_10 -->
		<td><span>
<?php echo $x_integrante_10; ?>
</span></td>
		<!-- monto_10 -->
		<td><span>
<?php echo $x_monto_10; ?>
</span></td>
		<!-- monto_total -->
		<td><span>
<?php echo $x_monto_total; ?>
</span></td>
		<!-- fecha_registro -->
		<td><span>
<?php echo FormatDateTime($x_fecha_registro,5); ?>
</span></td>
<td><span class="phpmaker"><a href="<?php if (($sKey != NULL)) { echo "creditosolidarioview.php?key=" . urlencode($sKey); } else { echo "javascript:alert('Invalid Record! Key is null');";  } ?>">View</a></span></td>
<td><span class="phpmaker"><a href="<?php if (($sKey != NULL)) { echo "creditosolidarioedit.php?key=" . urlencode($sKey); } else { echo "javascript:alert('Invalid Record! Key is null');"; } ?>">Edit</a></span></td>
<td><span class="phpmaker"><a href="<?php if (($sKey != NULL)) { echo "creditosolidarioadd.php?key=" . urlencode($sKey); } else { echo "javascript:alert('Invalid Record! Key is null');"; } ?>">Copy</a></span></td>
<td><span class="phpmaker"><a href="<?php if (($sKey != NULL)) { echo "creditosolidariodelete.php?key=" . urlencode($sKey); } else { echo "javascript:alert('Invalid Record! Key is null');"; }  ?>">Delete</a></span></td>
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
<form action="creditosolidariolist.php" name="ewpagerform" id="ewpagerform">
<table class="ewTablePager">
	<tr>
		<td nowrap>
<?php
if ($nTotalRecs > 0) {
	$rsEof = ($nTotalRecs < ($nStartRec + $nDisplayRecs));
	$PrevStart = $nStartRec - $nDisplayRecs;
	if ($PrevStart < 1) { $PrevStart = 1; }
	$NextStart = $nStartRec + $nDisplayRecs;
	if ($NextStart > $nTotalRecs) { $NextStart = $nStartRec ; }
	$LastStart = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
	?>
	<table border="0" cellspacing="0" cellpadding="0"><tr><td><span class="phpmaker">Page&nbsp;</span></td>
<!--first page button-->
	<?php if ($nStartRec == 1) { ?>
	<td><img src="images/firstdisab.gif" alt="First" width="16" height="16" border="0"></td>
	<?php } else { ?>
	<td><a href="creditosolidariolist.php?start=1"><img src="images/first.gif" alt="First" width="16" height="16" border="0"></a></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($PrevStart == $nStartRec) { ?>
	<td><img src="images/prevdisab.gif" alt="Previous" width="16" height="16" border="0"></td>
	<?php } else { ?>
	<td><a href="creditosolidariolist.php?start=<?php echo $PrevStart; ?>"><img src="images/prev.gif" alt="Previous" width="16" height="16" border="0"></a></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="pageno" value="<?php echo intval(($nStartRec-1)/$nDisplayRecs+1); ?>" size="4"></td>
<!--next page button-->
	<?php if ($NextStart == $nStartRec) { ?>
	<td><img src="images/nextdisab.gif" alt="Next" width="16" height="16" border="0"></td>
	<?php } else { ?>
	<td><a href="creditosolidariolist.php?start=<?php echo $NextStart; ?>"><img src="images/next.gif" alt="Next" width="16" height="16" border="0"></a></td>
	<?php  } ?>
<!--last page button-->
	<?php if ($LastStart == $nStartRec) { ?>
	<td><img src="images/lastdisab.gif" alt="Last" width="16" height="16" border="0"></td>
	<?php } else { ?>
	<td><a href="creditosolidariolist.php?start=<?php echo $LastStart; ?>"><img src="images/last.gif" alt="Last" width="16" height="16" border="0"></a></td>
	<?php } ?>
	<td><span class="phpmaker">&nbsp;of <?php echo intval(($nTotalRecs-1)/$nDisplayRecs+1);?></span></td>
	</tr></table>
	<?php if ($nStartRec > $nTotalRecs) { $nStartRec = $nTotalRecs; }
	$nStopRec = $nStartRec + $nDisplayRecs - 1;
	$nRecCount = $nTotalRecs - 1;
	if ($rsEof) { $nRecCount = $nTotalRecs; }
	if ($nStopRec > $nRecCount) { $nStopRec = $nRecCount; } ?>
	<span class="phpmaker">Records <?php echo $nStartRec; ?> to <?php echo $nStopRec; ?> of <?php echo $nTotalRecs; ?></span>
<?php } else { ?>
	<span class="phpmaker">No records found</span>
<?php } ?>
		</td>
	</tr>
</table>
</form>
<?php include ("footer.php") ?>
<?php

//-------------------------------------------------------------------------------
// Function BasicSearchSQL
// - Build WHERE clause for a keyword

function BasicSearchSQL($Keyword)
{
	$sKeyword = (!get_magic_quotes_gpc()) ? addslashes($Keyword) : $Keyword;
	$BasicSearchSQL = "";
	$BasicSearchSQL.= "`nombre_grupo` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`promotor` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`representante_sugerido` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`tesorero` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`integrante_1` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`integrante_2` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`integrante_3` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`integrante_4` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`integrante_5` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`integrante_6` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`integrante_7` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`integrante_8` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`integrante_9` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`integrante_10` LIKE '%" . $sKeyword . "%' OR ";
	if (substr($BasicSearchSQL, -4) == " OR ") { $BasicSearchSQL = substr($BasicSearchSQL, 0, strlen($BasicSearchSQL)-4); }
	return $BasicSearchSQL;
}

//-------------------------------------------------------------------------------
// Function SetUpBasicSearch
// - Set up Basic Search parameter based on form elements pSearch & pSearchType
// - Variables setup: sSrchBasic

function SetUpBasicSearch()
{
	global $HTTP_GET_VARS;
	global $sSrchBasic;
	$sSearch = (!get_magic_quotes_gpc()) ? addslashes(@$HTTP_GET_VARS["psearch"]) : @$HTTP_GET_VARS["psearch"];
	$sSearchType = @$HTTP_GET_VARS["psearchtype"];
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
	global $HTTP_SESSION_VARS;
	global $HTTP_GET_VARS;
	global $sOrderBy;
	global $sDefaultOrderBy;

	// Check for an Order parameter
	if (strlen(@$HTTP_GET_VARS["order"]) > 0) {
		$sOrder = @$HTTP_GET_VARS["order"];

		// Field creditoSolidario_id
		if ($sOrder == "creditoSolidario_id") {
			$sSortField = "`creditoSolidario_id`";
			$sLastSort = @$HTTP_SESSION_VARS["creditosolidario_x_creditoSolidario_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["creditosolidario_x_creditoSolidario_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["creditosolidario_x_creditoSolidario_id_Sort"] <> "") { @$HTTP_SESSION_VARS["creditosolidario_x_creditoSolidario_id_Sort"] = ""; }
		}

		// Field nombre_grupo
		if ($sOrder == "nombre_grupo") {
			$sSortField = "`nombre_grupo`";
			$sLastSort = @$HTTP_SESSION_VARS["creditosolidario_x_nombre_grupo_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["creditosolidario_x_nombre_grupo_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["creditosolidario_x_nombre_grupo_Sort"] <> "") { @$HTTP_SESSION_VARS["creditosolidario_x_nombre_grupo_Sort"] = ""; }
		}

		// Field promotor
		if ($sOrder == "promotor") {
			$sSortField = "`promotor`";
			$sLastSort = @$HTTP_SESSION_VARS["creditosolidario_x_promotor_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["creditosolidario_x_promotor_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["creditosolidario_x_promotor_Sort"] <> "") { @$HTTP_SESSION_VARS["creditosolidario_x_promotor_Sort"] = ""; }
		}

		// Field representante_sugerido
		if ($sOrder == "representante_sugerido") {
			$sSortField = "`representante_sugerido`";
			$sLastSort = @$HTTP_SESSION_VARS["creditosolidario_x_representante_sugerido_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["creditosolidario_x_representante_sugerido_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["creditosolidario_x_representante_sugerido_Sort"] <> "") { @$HTTP_SESSION_VARS["creditosolidario_x_representante_sugerido_Sort"] = ""; }
		}

		// Field tesorero
		if ($sOrder == "tesorero") {
			$sSortField = "`tesorero`";
			$sLastSort = @$HTTP_SESSION_VARS["creditosolidario_x_tesorero_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["creditosolidario_x_tesorero_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["creditosolidario_x_tesorero_Sort"] <> "") { @$HTTP_SESSION_VARS["creditosolidario_x_tesorero_Sort"] = ""; }
		}

		// Field numero_integrantes
		if ($sOrder == "numero_integrantes") {
			$sSortField = "`numero_integrantes`";
			$sLastSort = @$HTTP_SESSION_VARS["creditosolidario_x_numero_integrantes_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["creditosolidario_x_numero_integrantes_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["creditosolidario_x_numero_integrantes_Sort"] <> "") { @$HTTP_SESSION_VARS["creditosolidario_x_numero_integrantes_Sort"] = ""; }
		}

		// Field integrante_1
		if ($sOrder == "integrante_1") {
			$sSortField = "`integrante_1`";
			$sLastSort = @$HTTP_SESSION_VARS["creditosolidario_x_integrante_1_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["creditosolidario_x_integrante_1_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_1_Sort"] <> "") { @$HTTP_SESSION_VARS["creditosolidario_x_integrante_1_Sort"] = ""; }
		}

		// Field monto_1
		if ($sOrder == "monto_1") {
			$sSortField = "`monto_1`";
			$sLastSort = @$HTTP_SESSION_VARS["creditosolidario_x_monto_1_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["creditosolidario_x_monto_1_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_1_Sort"] <> "") { @$HTTP_SESSION_VARS["creditosolidario_x_monto_1_Sort"] = ""; }
		}

		// Field integrante_2
		if ($sOrder == "integrante_2") {
			$sSortField = "`integrante_2`";
			$sLastSort = @$HTTP_SESSION_VARS["creditosolidario_x_integrante_2_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["creditosolidario_x_integrante_2_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_2_Sort"] <> "") { @$HTTP_SESSION_VARS["creditosolidario_x_integrante_2_Sort"] = ""; }
		}

		// Field monto_2
		if ($sOrder == "monto_2") {
			$sSortField = "`monto_2`";
			$sLastSort = @$HTTP_SESSION_VARS["creditosolidario_x_monto_2_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["creditosolidario_x_monto_2_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_2_Sort"] <> "") { @$HTTP_SESSION_VARS["creditosolidario_x_monto_2_Sort"] = ""; }
		}

		// Field integrante_3
		if ($sOrder == "integrante_3") {
			$sSortField = "`integrante_3`";
			$sLastSort = @$HTTP_SESSION_VARS["creditosolidario_x_integrante_3_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["creditosolidario_x_integrante_3_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_3_Sort"] <> "") { @$HTTP_SESSION_VARS["creditosolidario_x_integrante_3_Sort"] = ""; }
		}

		// Field monto_3
		if ($sOrder == "monto_3") {
			$sSortField = "`monto_3`";
			$sLastSort = @$HTTP_SESSION_VARS["creditosolidario_x_monto_3_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["creditosolidario_x_monto_3_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_3_Sort"] <> "") { @$HTTP_SESSION_VARS["creditosolidario_x_monto_3_Sort"] = ""; }
		}

		// Field integrante_4
		if ($sOrder == "integrante_4") {
			$sSortField = "`integrante_4`";
			$sLastSort = @$HTTP_SESSION_VARS["creditosolidario_x_integrante_4_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["creditosolidario_x_integrante_4_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_4_Sort"] <> "") { @$HTTP_SESSION_VARS["creditosolidario_x_integrante_4_Sort"] = ""; }
		}

		// Field monto_4
		if ($sOrder == "monto_4") {
			$sSortField = "`monto_4`";
			$sLastSort = @$HTTP_SESSION_VARS["creditosolidario_x_monto_4_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["creditosolidario_x_monto_4_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_4_Sort"] <> "") { @$HTTP_SESSION_VARS["creditosolidario_x_monto_4_Sort"] = ""; }
		}

		// Field integrante_5
		if ($sOrder == "integrante_5") {
			$sSortField = "`integrante_5`";
			$sLastSort = @$HTTP_SESSION_VARS["creditosolidario_x_integrante_5_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["creditosolidario_x_integrante_5_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_5_Sort"] <> "") { @$HTTP_SESSION_VARS["creditosolidario_x_integrante_5_Sort"] = ""; }
		}

		// Field monto_5
		if ($sOrder == "monto_5") {
			$sSortField = "`monto_5`";
			$sLastSort = @$HTTP_SESSION_VARS["creditosolidario_x_monto_5_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["creditosolidario_x_monto_5_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_5_Sort"] <> "") { @$HTTP_SESSION_VARS["creditosolidario_x_monto_5_Sort"] = ""; }
		}

		// Field integrante_6
		if ($sOrder == "integrante_6") {
			$sSortField = "`integrante_6`";
			$sLastSort = @$HTTP_SESSION_VARS["creditosolidario_x_integrante_6_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["creditosolidario_x_integrante_6_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_6_Sort"] <> "") { @$HTTP_SESSION_VARS["creditosolidario_x_integrante_6_Sort"] = ""; }
		}

		// Field monto_6
		if ($sOrder == "monto_6") {
			$sSortField = "`monto_6`";
			$sLastSort = @$HTTP_SESSION_VARS["creditosolidario_x_monto_6_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["creditosolidario_x_monto_6_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_6_Sort"] <> "") { @$HTTP_SESSION_VARS["creditosolidario_x_monto_6_Sort"] = ""; }
		}

		// Field integrante_7
		if ($sOrder == "integrante_7") {
			$sSortField = "`integrante_7`";
			$sLastSort = @$HTTP_SESSION_VARS["creditosolidario_x_integrante_7_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["creditosolidario_x_integrante_7_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_7_Sort"] <> "") { @$HTTP_SESSION_VARS["creditosolidario_x_integrante_7_Sort"] = ""; }
		}

		// Field monto_7
		if ($sOrder == "monto_7") {
			$sSortField = "`monto_7`";
			$sLastSort = @$HTTP_SESSION_VARS["creditosolidario_x_monto_7_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["creditosolidario_x_monto_7_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_7_Sort"] <> "") { @$HTTP_SESSION_VARS["creditosolidario_x_monto_7_Sort"] = ""; }
		}

		// Field integrante_8
		if ($sOrder == "integrante_8") {
			$sSortField = "`integrante_8`";
			$sLastSort = @$HTTP_SESSION_VARS["creditosolidario_x_integrante_8_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["creditosolidario_x_integrante_8_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_8_Sort"] <> "") { @$HTTP_SESSION_VARS["creditosolidario_x_integrante_8_Sort"] = ""; }
		}

		// Field monto_8
		if ($sOrder == "monto_8") {
			$sSortField = "`monto_8`";
			$sLastSort = @$HTTP_SESSION_VARS["creditosolidario_x_monto_8_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["creditosolidario_x_monto_8_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_8_Sort"] <> "") { @$HTTP_SESSION_VARS["creditosolidario_x_monto_8_Sort"] = ""; }
		}

		// Field integrante_9
		if ($sOrder == "integrante_9") {
			$sSortField = "`integrante_9`";
			$sLastSort = @$HTTP_SESSION_VARS["creditosolidario_x_integrante_9_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["creditosolidario_x_integrante_9_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_9_Sort"] <> "") { @$HTTP_SESSION_VARS["creditosolidario_x_integrante_9_Sort"] = ""; }
		}

		// Field monto_9
		if ($sOrder == "monto_9") {
			$sSortField = "`monto_9`";
			$sLastSort = @$HTTP_SESSION_VARS["creditosolidario_x_monto_9_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["creditosolidario_x_monto_9_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_9_Sort"] <> "") { @$HTTP_SESSION_VARS["creditosolidario_x_monto_9_Sort"] = ""; }
		}

		// Field integrante_10
		if ($sOrder == "integrante_10") {
			$sSortField = "`integrante_10`";
			$sLastSort = @$HTTP_SESSION_VARS["creditosolidario_x_integrante_10_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["creditosolidario_x_integrante_10_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_10_Sort"] <> "") { @$HTTP_SESSION_VARS["creditosolidario_x_integrante_10_Sort"] = ""; }
		}

		// Field monto_10
		if ($sOrder == "monto_10") {
			$sSortField = "`monto_10`";
			$sLastSort = @$HTTP_SESSION_VARS["creditosolidario_x_monto_10_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["creditosolidario_x_monto_10_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_10_Sort"] <> "") { @$HTTP_SESSION_VARS["creditosolidario_x_monto_10_Sort"] = ""; }
		}

		// Field monto_total
		if ($sOrder == "monto_total") {
			$sSortField = "`monto_total`";
			$sLastSort = @$HTTP_SESSION_VARS["creditosolidario_x_monto_total_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["creditosolidario_x_monto_total_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_total_Sort"] <> "") { @$HTTP_SESSION_VARS["creditosolidario_x_monto_total_Sort"] = ""; }
		}

		// Field fecha_registro
		if ($sOrder == "fecha_registro") {
			$sSortField = "`fecha_registro`";
			$sLastSort = @$HTTP_SESSION_VARS["creditosolidario_x_fecha_registro_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$HTTP_SESSION_VARS["creditosolidario_x_fecha_registro_Sort"] = $sThisSort;
		}
		else
		{
			if (@$HTTP_SESSION_VARS["creditosolidario_x_fecha_registro_Sort"] <> "") { @$HTTP_SESSION_VARS["creditosolidario_x_fecha_registro_Sort"] = ""; }
		}
		$HTTP_SESSION_VARS["creditosolidario_OrderBy"] = $sSortField . " " . $sThisSort;
		$HTTP_SESSION_VARS["creditosolidario_REC"] = 1;
	}
	$sOrderBy = @$HTTP_SESSION_VARS["creditosolidario_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$HTTP_SESSION_VARS["creditosolidario_OrderBy"] = $sOrderBy;
	}
}

//-------------------------------------------------------------------------------
// Function SetUpStartRec
//- Set up Starting Record parameters based on Pager Navigation
// - Variables setup: nStartRec

function SetUpStartRec()
{

	// Check for a START parameter
	global $HTTP_SESSION_VARS;
	global $HTTP_GET_VARS;
	global $nStartRec;
	global $nDisplayRecs;
	global $nTotalRecs;
	if (strlen(@$HTTP_GET_VARS["start"]) > 0) {
		$nStartRec = @$HTTP_GET_VARS["start"];
		$HTTP_SESSION_VARS["creditosolidario_REC"] = $nStartRec;
	}
	elseif (strlen(@$HTTP_GET_VARS["pageno"]) > 0) {
		$nPageNo = @$HTTP_GET_VARS["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}
			elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$HTTP_SESSION_VARS["creditosolidario_REC"] = $nStartRec;
		}
		else
		{
			$nStartRec = @$HTTP_SESSION_VARS["creditosolidario_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$HTTP_SESSION_VARS["creditosolidario_REC"] = $nStartRec;
			}
		}
	}
	else
	{
		$nStartRec = @$HTTP_SESSION_VARS["creditosolidario_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$HTTP_SESSION_VARS["creditosolidario_REC"] = $nStartRec;
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
		global $HTTP_SESSION_VARS;
		global $HTTP_GET_VARS;

	// Get Reset Cmd
	if (strlen(@$HTTP_GET_VARS["cmd"]) > 0) {
		$sCmd = @$HTTP_GET_VARS["cmd"];

		// Reset Search Criteria
		if (strtoupper($sCmd) == "RESET") {
			$sSrchWhere = "";
			$HTTP_SESSION_VARS["creditosolidario_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}
		elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$HTTP_SESSION_VARS["creditosolidario_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$HTTP_SESSION_VARS["creditosolidario_OrderBy"] = $sOrderBy;
			if (@$HTTP_SESSION_VARS["creditosolidario_x_creditoSolidario_id_Sort"] <> "") { $HTTP_SESSION_VARS["creditosolidario_x_creditoSolidario_id_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["creditosolidario_x_nombre_grupo_Sort"] <> "") { $HTTP_SESSION_VARS["creditosolidario_x_nombre_grupo_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["creditosolidario_x_promotor_Sort"] <> "") { $HTTP_SESSION_VARS["creditosolidario_x_promotor_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["creditosolidario_x_representante_sugerido_Sort"] <> "") { $HTTP_SESSION_VARS["creditosolidario_x_representante_sugerido_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["creditosolidario_x_tesorero_Sort"] <> "") { $HTTP_SESSION_VARS["creditosolidario_x_tesorero_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["creditosolidario_x_numero_integrantes_Sort"] <> "") { $HTTP_SESSION_VARS["creditosolidario_x_numero_integrantes_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_1_Sort"] <> "") { $HTTP_SESSION_VARS["creditosolidario_x_integrante_1_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_1_Sort"] <> "") { $HTTP_SESSION_VARS["creditosolidario_x_monto_1_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_2_Sort"] <> "") { $HTTP_SESSION_VARS["creditosolidario_x_integrante_2_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_2_Sort"] <> "") { $HTTP_SESSION_VARS["creditosolidario_x_monto_2_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_3_Sort"] <> "") { $HTTP_SESSION_VARS["creditosolidario_x_integrante_3_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_3_Sort"] <> "") { $HTTP_SESSION_VARS["creditosolidario_x_monto_3_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_4_Sort"] <> "") { $HTTP_SESSION_VARS["creditosolidario_x_integrante_4_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_4_Sort"] <> "") { $HTTP_SESSION_VARS["creditosolidario_x_monto_4_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_5_Sort"] <> "") { $HTTP_SESSION_VARS["creditosolidario_x_integrante_5_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_5_Sort"] <> "") { $HTTP_SESSION_VARS["creditosolidario_x_monto_5_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_6_Sort"] <> "") { $HTTP_SESSION_VARS["creditosolidario_x_integrante_6_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_6_Sort"] <> "") { $HTTP_SESSION_VARS["creditosolidario_x_monto_6_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_7_Sort"] <> "") { $HTTP_SESSION_VARS["creditosolidario_x_integrante_7_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_7_Sort"] <> "") { $HTTP_SESSION_VARS["creditosolidario_x_monto_7_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_8_Sort"] <> "") { $HTTP_SESSION_VARS["creditosolidario_x_integrante_8_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_8_Sort"] <> "") { $HTTP_SESSION_VARS["creditosolidario_x_monto_8_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_9_Sort"] <> "") { $HTTP_SESSION_VARS["creditosolidario_x_integrante_9_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_9_Sort"] <> "") { $HTTP_SESSION_VARS["creditosolidario_x_monto_9_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["creditosolidario_x_integrante_10_Sort"] <> "") { $HTTP_SESSION_VARS["creditosolidario_x_integrante_10_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_10_Sort"] <> "") { $HTTP_SESSION_VARS["creditosolidario_x_monto_10_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["creditosolidario_x_monto_total_Sort"] <> "") { $HTTP_SESSION_VARS["creditosolidario_x_monto_total_Sort"] = ""; }
			if (@$HTTP_SESSION_VARS["creditosolidario_x_fecha_registro_Sort"] <> "") { $HTTP_SESSION_VARS["creditosolidario_x_fecha_registro_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$HTTP_SESSION_VARS["creditosolidario_REC"] = $nStartRec;
	}
}
?>
