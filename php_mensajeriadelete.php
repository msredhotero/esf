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
$x_mensajeria_id = Null; 
$ox_mensajeria_id = Null;
$x_fecha_registro = Null; 
$ox_fecha_registro = Null;
$x_fecha_inicio = Null; 
$ox_fecha_inicio = Null;
$x_fecha_fin = Null; 
$ox_fecha_fin = Null;
$x_asunto = Null; 
$ox_asunto = Null;
$x_mensajeria_tipo_id = Null; 
$ox_mensajeria_tipo_id = Null;
$x_formato_docto_id = Null; 
$ox_formato_docto_id = Null;
$x_mensajeria_status_id = Null; 
$ox_mensajeria_status_id = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
$arRecKey = Null;

// Load Key Parameters
$sKey = "";
$bSingleDelete = true;
$x_mensajeria_id = @$_GET["mensajeria_id"];
if (!empty($x_mensajeria_id)) {
	if ($sKey <> "") { $sKey .= ","; }
	$sKey .= $x_mensajeria_id;
}else{
	$bSingleDelete = false;
}
if (!$bSingleDelete) {
	$sKey = @$_POST["key_d"];
}
if (!is_array($sKey)) {
	if (strlen($sKey) > 0) {	
		$arRecKey = split(",", $sKey);
	}
}else {
	$sKey = implode(",", $sKey);
	$arRecKey = split(",", $sKey);
}
if (count($arRecKey) <= 0) {
	ob_end_clean();
	header("Location: php_mensajerialist.php");
	exit();
}
$sKey = implode(",", $arRecKey);
$i = 0;
$sDbWhere = "";
while ($i < count($arRecKey)){
	$sDbWhere .= "(";

	// Remove spaces
	$sRecKey = trim($arRecKey[$i+0]);
	$sRecKey = (!get_magic_quotes_gpc()) ? addslashes($sRecKey) : $sRecKey ;

	// Build the SQL
	$sDbWhere .= "`mensajeria_id`=" . $sRecKey . " AND ";
	if (substr($sDbWhere, -5) == " AND ") { $sDbWhere = substr($sDbWhere, 0, strlen($sDbWhere)-5) . ") OR "; }
	$i += 1;
}
if (substr($sDbWhere, -4) == " OR ") { $sDbWhere = substr($sDbWhere, 0 , strlen($sDbWhere)-4); }

// Get action
$sAction = @$_POST["a_delete"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";		// Display with input box
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Display
		if (LoadRecordCount($sDbWhere,$conn) <= 0) {
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_mensajerialist.php");
			exit();
		}
		break;
	case "D": // Delete
		if (DeleteData($sDbWhere,$conn)) {
			$_SESSION["ewmsg"] = "Los datos han sido eliminados.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_mensajerialist.php");
			exit();
		}
		break;
}
?>
<?php include ("header.php") ?>
<p><span class="phpmaker">Mensajeria<br>
  <br>
    <a href="php_mensajerialist.php">Regresar a la lista</a></span></p>
<form action="php_mensajeriadelete.php" method="post">
<p>
<input type="hidden" name="a_delete" value="D">
<?php $sKey = (get_magic_quotes_gpc()) ? stripslashes($sKey) : $sKey; ?>
<input type="hidden" name="key_d" value="<?php echo htmlspecialchars($sKey); ?>">
<table class="ewTable">
	<tr class="ewTableHeader">
		<td valign="top"><span>No</span></td>
		<td valign="top"><span>Fecha de registro</span></td>
		<td valign="top"><span>Fecha de inicio</span></td>
		<td valign="top"><span>Fecha de fin</span></td>
		<td valign="top"><span>Asunto</span></td>
		<td valign="top"><span>Tipo de mensajeria</span></td>
		<td valign="top"><span>Formato</span></td>
		<td valign="top"><span>Status</span></td>
	</tr>
<?php
$nRecCount = 0;
$i = 0;
while ($i < count($arRecKey)) {
	$nRecCount++;

	// Set row color
	$sItemRowClass = " class=\"ewTableRow\"";

	// Display alternate color for rows
	if ($nRecCount % 2 <> 0) {
		$sItemRowClass = " class=\"ewTableAltRow\"";
	}
	$sRecKey = trim($arRecKey[$i+0]);
	$sRecKey = (get_magic_quotes_gpc()) ? stripslashes($sRecKey) : $sRecKey;
	$x_mensajeria_id = $sRecKey;
	if (LoadData($conn)) {
?>
	<tr<?php echo $sItemRowClass;?>>
		<td><span>
<?php echo $x_mensajeria_id; ?>
</span></td>
		<td><span>
<?php echo FormatDateTime($x_fecha_registro,7); ?>
</span></td>
		<td><span>
<?php echo FormatDateTime($x_fecha_inicio,7); ?>
</span></td>
		<td><span>
<?php echo FormatDateTime($x_fecha_fin,7); ?>
</span></td>
		<td><span>
<?php echo $x_asunto; ?>
</span></td>
		<td><span>
<?php
if ((!is_null($x_mensajeria_tipo_id)) && ($x_mensajeria_tipo_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `mensajeria_tipo`";
	$sTmp = $x_mensajeria_tipo_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `mensajeria_tipo_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_mensajeria_tipo_id = $x_mensajeria_tipo_id; // Backup Original Value
$x_mensajeria_tipo_id = $sTmp;
?>
<?php echo $x_mensajeria_tipo_id; ?>
<?php $x_mensajeria_tipo_id = $ox_mensajeria_tipo_id; // Restore Original Value ?>
</span></td>
		<td><span>
<?php
if ((!is_null($x_formato_docto_id)) && ($x_formato_docto_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `formato_docto`";
	$sTmp = $x_formato_docto_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `formato_docto_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_formato_docto_id = $x_formato_docto_id; // Backup Original Value
$x_formato_docto_id = $sTmp;
?>
<?php echo $x_formato_docto_id; ?>
<?php $x_formato_docto_id = $ox_formato_docto_id; // Restore Original Value ?>
</span></td>
		<td><span>
<?php
if ((!is_null($x_mensajeria_status_id)) && ($x_mensajeria_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `mensajeria_status`";
	$sTmp = $x_mensajeria_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `mensajeria_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_mensajeria_status_id = $x_mensajeria_status_id; // Backup Original Value
$x_mensajeria_status_id = $sTmp;
?>
<?php echo $x_mensajeria_status_id; ?>
<?php $x_mensajeria_status_id = $ox_mensajeria_status_id; // Restore Original Value ?>
</span></td>
	</tr>
<?php
	}
	$i += 1;
}
?>
</table>
<p>
<input type="submit" name="Action" value="CONFIRME LA ELIMINACION">
</form>
<?php include ("footer.php") ?>
<?php

//-------------------------------------------------------------------------------
// Function LoadData
// - Load Data based on Key Value sKey
// - Variables setup: field variables

function LoadData($conn)
{
	global $x_mensajeria_id;
	$sSql = "SELECT * FROM `mensajeria`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_mensajeria_id) : $x_mensajeria_id;
	$sWhere .= "(`mensajeria_id` = " . addslashes($sTmp) . ")";
	$sSql .= " WHERE " . $sWhere;
	if ($sGroupBy <> "") {
		$sSql .= " GROUP BY " . $sGroupBy;
	}
	if ($sHaving <> "") {
		$sSql .= " HAVING " . $sHaving;
	}
	if ($sOrderBy <> "") {
		$sSql .= " ORDER BY " . $sOrderBy;
	}
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	if (phpmkr_num_rows($rs) == 0) {
		$bLoadData = false;
	}else{
		$bLoadData = true;
		$row = phpmkr_fetch_array($rs);

		// Get the field contents
		$GLOBALS["x_mensajeria_id"] = $row["mensajeria_id"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
		$GLOBALS["x_fecha_inicio"] = $row["fecha_inicio"];
		$GLOBALS["x_fecha_fin"] = $row["fecha_fin"];
		$GLOBALS["x_asunto"] = $row["asunto"];
		$GLOBALS["x_mensajeria_tipo_id"] = $row["mensajeria_tipo_id"];
		$GLOBALS["x_formato_docto_id"] = $row["formato_docto_id"];
		$GLOBALS["x_mensajeria_status_id"] = $row["mensajeria_status_id"];
	}
	phpmkr_free_result($rs);
	return $bLoadData;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function LoadRecordCount
// - Load Record Count based on input sql criteria sqlKey

function LoadRecordCount($sqlKey,$conn)
{
	global $x_mensajeria_id;
	$sSql = "SELECT * FROM `mensajeria`";
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
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
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
	global $x_mensajeria_id;
	$sSql = "Delete FROM `mensajeria`";
	$sSql .= " WHERE " . $sqlKey;
	phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	return true;
}
?>
