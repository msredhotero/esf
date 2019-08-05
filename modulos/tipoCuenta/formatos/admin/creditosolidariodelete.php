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

// Load Key Parameters
$sKey = @$HTTP_GET_VARS["key"];
if (($sKey == "") || (($sKey == NULL))) {
	$sKey = @$HTTP_POST_VARS["key_d"];
}
$sDbWhere = "";
$arRecKey = split(",",$sKey);

// Single delete record
if (($sKey == "") || (($sKey == NULL))) {
	ob_end_clean();
	header("Location: creditosolidariolist.php");
}
	$sKey = (get_magic_quotes_gpc()) ? $sKey : addslashes($sKey);
$sDbWhere .= "`creditoSolidario_id`=" . trim($sKey) . "";

// Get action
$sAction = @$HTTP_POST_VARS["a_delete"];
if (($sAction == "") || (($sAction == NULL))) {
	$sAction = "I";	// Display with input box
}
$conn = phpmkr_db_connect(HOST, USER, PASS,DB);
switch ($sAction)
{
	case "I": // Display
		if (LoadRecordCount($sDbWhere,$conn) <= 0) {
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: creditosolidariolist.php");
		}
		break;
	case "D": // Delete
		if (DeleteData($sDbWhere,$conn)) {
			$HTTP_SESSION_VARS["ewmsg"] = "Delete Successful For Key = " . stripslashes($sKey);
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: creditosolidariolist.php");
		}
		break;
}
?>
<?php include ("header.php") ?>
<p><span class="phpmaker">Delete from TABLE: creditosolidario<br><br><a href="creditosolidariolist.php">Back to List</a></span></p>
<form action="creditosolidariodelete.php" method="post">
<p>
<input type="hidden" name="a_delete" value="D">
<?php $sKey = (get_magic_quotes_gpc()) ? stripslashes($sKey) : $sKey; ?>
<input type="hidden" name="key_d" value="<?php echo  htmlspecialchars($sKey); ?>">
<table class="ewTable">
	<tr class="ewTableHeader">
		<td valign="top"><span>credito Solidario id</span></td>
		<td valign="top"><span>nombre grupo</span></td>
		<td valign="top"><span>promotor</span></td>
		<td valign="top"><span>representante sugerido</span></td>
		<td valign="top"><span>tesorero</span></td>
		<td valign="top"><span>numero integrantes</span></td>
		<td valign="top"><span>integrante 1</span></td>
		<td valign="top"><span>monto 1</span></td>
		<td valign="top"><span>integrante 2</span></td>
		<td valign="top"><span>monto 2</span></td>
		<td valign="top"><span>integrante 3</span></td>
		<td valign="top"><span>monto 3</span></td>
		<td valign="top"><span>integrante 4</span></td>
		<td valign="top"><span>monto 4</span></td>
		<td valign="top"><span>integrante 5</span></td>
		<td valign="top"><span>monto 5</span></td>
		<td valign="top"><span>integrante 6</span></td>
		<td valign="top"><span>monto 6</span></td>
		<td valign="top"><span>integrante 7</span></td>
		<td valign="top"><span>monto 7</span></td>
		<td valign="top"><span>integrante 8</span></td>
		<td valign="top"><span>monto 8</span></td>
		<td valign="top"><span>integrante 9</span></td>
		<td valign="top"><span>monto 9</span></td>
		<td valign="top"><span>integrante 10</span></td>
		<td valign="top"><span>monto 10</span></td>
		<td valign="top"><span>monto total</span></td>
		<td valign="top"><span>fecha registro</span></td>
	</tr>
<?php
$nRecCount = 0;
foreach ($arRecKey as $sRecKey) {
	$sRecKey = trim($sRecKey);
	$sRecKey = (get_magic_quotes_gpc()) ? stripslashes($sRecKey) : $sRecKey;
	$nRecCount = $nRecCount + 1;

	// Set row color
	$sItemRowClass = " class=\"ewTableRow\"";

	// Display alternate color for rows
	if ($nRecCount % 2 <> 0) {
		$sItemRowClass = " class=\"ewTableAltRow\"";
	}
	if (LoadData($sRecKey,$conn)) {
?>
	<tr<?php echo $sItemRowClass;?>>
		<td><span>
<?php echo $x_creditoSolidario_id; ?>
</span></td>
		<td><span>
<?php echo $x_nombre_grupo; ?>
</span></td>
		<td><span>
<?php echo $x_promotor; ?>
</span></td>
		<td><span>
<?php echo $x_representante_sugerido; ?>
</span></td>
		<td><span>
<?php echo $x_tesorero; ?>
</span></td>
		<td><span>
<?php echo $x_numero_integrantes; ?>
</span></td>
		<td><span>
<?php echo $x_integrante_1; ?>
</span></td>
		<td><span>
<?php echo $x_monto_1; ?>
</span></td>
		<td><span>
<?php echo $x_integrante_2; ?>
</span></td>
		<td><span>
<?php echo $x_monto_2; ?>
</span></td>
		<td><span>
<?php echo $x_integrante_3; ?>
</span></td>
		<td><span>
<?php echo $x_monto_3; ?>
</span></td>
		<td><span>
<?php echo $x_integrante_4; ?>
</span></td>
		<td><span>
<?php echo $x_monto_4; ?>
</span></td>
		<td><span>
<?php echo $x_integrante_5; ?>
</span></td>
		<td><span>
<?php echo $x_monto_5; ?>
</span></td>
		<td><span>
<?php echo $x_integrante_6; ?>
</span></td>
		<td><span>
<?php echo $x_monto_6; ?>
</span></td>
		<td><span>
<?php echo $x_integrante_7; ?>
</span></td>
		<td><span>
<?php echo $x_monto_7; ?>
</span></td>
		<td><span>
<?php echo $x_integrante_8; ?>
</span></td>
		<td><span>
<?php echo $x_monto_8; ?>
</span></td>
		<td><span>
<?php echo $x_integrante_9; ?>
</span></td>
		<td><span>
<?php echo $x_monto_9; ?>
</span></td>
		<td><span>
<?php echo $x_integrante_10; ?>
</span></td>
		<td><span>
<?php echo $x_monto_10; ?>
</span></td>
		<td><span>
<?php echo $x_monto_total; ?>
</span></td>
		<td><span>
<?php echo FormatDateTime($x_fecha_registro,5); ?>
</span></td>
	</tr>
<?php
	}
}
?>
</table>
<p>
<input type="submit" name="Action" value="CONFIRM DELETE">
</form>
<?php include ("footer.php") ?>
<?php
phpmkr_db_close($conn);
?>
<?php

//-------------------------------------------------------------------------------
// Function LoadData
// - Load Data based on Key Value sKey
// - Variables setup: field variables

function LoadData($sKey,$conn)
{
	global $HTTP_SESSION_VARS;
	$sKeyWrk = "" . addslashes($sKey) . "";
	$sSql = "SELECT * FROM `creditosolidario`";
	$sSql .= " WHERE `creditoSolidario_id` = " . $sKeyWrk;
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
	$rs = phpmkr_query($sSql,$conn);
	if (phpmkr_num_rows($rs) == 0) {
		$LoadData = false;
	}else{
		$LoadData = true;
		$row = phpmkr_fetch_array($rs);

		// Get the field contents
		$GLOBALS["x_creditoSolidario_id"] = $row["creditoSolidario_id"];
		$GLOBALS["x_nombre_grupo"] = $row["nombre_grupo"];
		$GLOBALS["x_promotor"] = $row["promotor"];
		$GLOBALS["x_representante_sugerido"] = $row["representante_sugerido"];
		$GLOBALS["x_tesorero"] = $row["tesorero"];
		$GLOBALS["x_numero_integrantes"] = $row["numero_integrantes"];
		$GLOBALS["x_integrante_1"] = $row["integrante_1"];
		$GLOBALS["x_monto_1"] = $row["monto_1"];
		$GLOBALS["x_integrante_2"] = $row["integrante_2"];
		$GLOBALS["x_monto_2"] = $row["monto_2"];
		$GLOBALS["x_integrante_3"] = $row["integrante_3"];
		$GLOBALS["x_monto_3"] = $row["monto_3"];
		$GLOBALS["x_integrante_4"] = $row["integrante_4"];
		$GLOBALS["x_monto_4"] = $row["monto_4"];
		$GLOBALS["x_integrante_5"] = $row["integrante_5"];
		$GLOBALS["x_monto_5"] = $row["monto_5"];
		$GLOBALS["x_integrante_6"] = $row["integrante_6"];
		$GLOBALS["x_monto_6"] = $row["monto_6"];
		$GLOBALS["x_integrante_7"] = $row["integrante_7"];
		$GLOBALS["x_monto_7"] = $row["monto_7"];
		$GLOBALS["x_integrante_8"] = $row["integrante_8"];
		$GLOBALS["x_monto_8"] = $row["monto_8"];
		$GLOBALS["x_integrante_9"] = $row["integrante_9"];
		$GLOBALS["x_monto_9"] = $row["monto_9"];
		$GLOBALS["x_integrante_10"] = $row["integrante_10"];
		$GLOBALS["x_monto_10"] = $row["monto_10"];
		$GLOBALS["x_monto_total"] = $row["monto_total"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
	}
	phpmkr_free_result($rs);
	return $LoadData;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function LoadRecordCount
// - Load Record Count based on input sql criteria sqlKey

function LoadRecordCount($sqlKey,$conn)
{
	global $HTTP_SESSION_VARS;
	$sSql = "SELECT * FROM `creditosolidario`";
	$sSql .= " WHERE " . $sqlKey;
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
	$rs = phpmkr_query($sSql,$conn);
	return phpmkr_num_rows($rs);
	phpmkr_free_result($rs);
}
?>
<?php

//-------------------------------------------------------------------------------
// Function DeleteData
// - Delete Records based on input sql criteria sqlKey

function DeleteData($sqlKey,$conn)
{
	global $HTTP_SESSION_VARS;
	$sSql = "Delete FROM `creditosolidario`";
	$sSql .= " WHERE " . $sqlKey;
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
	phpmkr_query($sSql,$conn);
	return true;
}
?>
