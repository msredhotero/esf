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
$x_fondeo_empresa_id = Null;
$x_nombre = Null;
$x_entidad_id = Null;
$x_calle = Null;
$x_colonia = Null;
$x_ciudad = Null;
$x_codigo_postal = Null;
$x_lada = Null;
$x_telefono = Null;
$x_fax = Null;
$x_contacto = Null;
$x_contacto_email = Null;
$x_fondeo_empresa_dependiente_id = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Load Key Parameters
$sKey = @$_GET["key"];
if (($sKey == "") || ((is_null($sKey)))) {
	$sKey = @$_POST["key_d"];
}
$sDbWhere = "";
if (!is_array($sKey)) {
	$arRecKey = split(",",$sKey);
}else {
	$arRecKey = $sKey;
}

// Multiple delete records
if (count($arRecKey) <= 0) {
	ob_end_clean();
	header("Location: php_fondeo_empresalist.php");
	exit();
}
foreach ($arRecKey as $sRecKey)
{
	$sRecKey = (get_magic_quotes_gpc()) ? $sRecKey : addslashes($sRecKey);

	// Remove spaces
	$sRecKey = trim($sRecKey);

	// Build the SQL
	$sDbWhere .= "(`fondeo_empresa_id`=" . $sRecKey . ") OR ";
}
if (substr($sDbWhere, -4) == " OR ") { $sDbWhere = substr($sDbWhere, 0, strlen($sDbWhere)-4); }

// Get action
$sAction = @$_POST["a_delete"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";	// Display with input box
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Display
		if (LoadRecordCount($sDbWhere,$conn) <= 0) {
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_fondeo_empresalist.php");
			exit();
		}
		break;
	case "D": // Delete
		if (DeleteData($sDbWhere,$conn)) {
			$ewmsg = (get_magic_quotes_gpc()) ? stripslashes(implode(",", array_values($sKey))) : implode(",", array_values($sKey));
			$_SESSION["ewmsg"] = "Los datos han sido eliminados.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_fondeo_empresalist.php");
			exit();
		}
		break;
}
?>
<?php include ("header.php") ?>
<p><span class="phpmaker">Empresas de Fondeo<br><br>
<a href="php_fondeo_empresalist.php">Regresar al listado</a></span></p>
<form action="php_fondeo_empresadelete.php" method="post">
<p>
<input type="hidden" name="a_delete" value="D">
<?php
	foreach ($arRecKey as $sid) {
	$sid = (get_magic_quotes_gpc()) ? stripslashes($sid) : $sid;
?>
		<input type="hidden" name="key_d[]" value="<?php echo  htmlspecialchars($sid); ?>">
<?php
	}
?>
<table class="ewTable">
	<tr class="ewTableHeader">
		<td valign="top"><span>No</span></td>
		<td valign="top"><span>Nombre</span></td>
		<td valign="top"><span>Entidad</span></td>
		<td valign="top"><span>Calle</span></td>
		<td valign="top"><span>Colonia</span></td>
		<td valign="top"><span>Ciudad</span></td>
		<td valign="top"><span>Codigo postal</span></td>
		<td valign="top"><span>Lada</span></td>
		<td valign="top"><span>Telefono</span></td>
		<td valign="top"><span>Fax</span></td>
		<td valign="top"><span>Contacto</span></td>
		<td valign="top"><span>Contacto email</span></td>
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
<?php echo $x_fondeo_empresa_id; ?>
</span></td>
		<td><span>
<?php echo $x_nombre; ?>
</span></td>
		<td><span>
<?php
if ((!is_null($x_entidad_id)) && ($x_entidad_id <> "")) {
	$sSqlWrk = "SELECT *  FROM `entidad`";
	$sTmp = $x_entidad_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE (`entidad_id` = " . $sTmp . ")";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["nombre"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_entidad_id = $x_entidad_id; // Backup Original Value
$x_entidad_id = $sTmp;
?>
<?php echo $x_entidad_id; ?>
<?php $x_entidad_id = $ox_entidad_id; // Restore Original Value ?>
</span></td>
		<td><span>
<?php echo $x_calle; ?>
</span></td>
		<td><span>
<?php echo $x_colonia; ?>
</span></td>
		<td><span>
<?php echo $x_ciudad; ?>
</span></td>
		<td><span>
<?php echo $x_codigo_postal; ?>
</span></td>
		<td><span>
<?php echo $x_lada; ?>
</span></td>
		<td><span>
<?php echo $x_telefono; ?>
</span></td>
		<td><span>
<?php echo $x_fax; ?>
</span></td>
		<td><span>
<?php echo $x_contacto; ?>
</span></td>
		<td><span>
  <?php echo $x_contacto_email; ?>
</span></td>
		</tr>
<?php
	}
}
?>
</table>
<p>
<input type="submit" name="Action" value="CONFIRME">
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
	$sKeyWrk = "" . addslashes($sKey) . "";
	$sSql = "SELECT * FROM `fondeo_empresa`";
	$sSql .= " WHERE `fondeo_empresa_id` = " . $sKeyWrk;
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
		$GLOBALS["x_fondeo_empresa_id"] = $row["fondeo_empresa_id"];
		$GLOBALS["x_nombre"] = $row["nombre"];
		$GLOBALS["x_entidad_id"] = $row["entidad_id"];
		$GLOBALS["x_calle"] = $row["calle"];
		$GLOBALS["x_colonia"] = $row["colonia"];
		$GLOBALS["x_ciudad"] = $row["ciudad"];
		$GLOBALS["x_codigo_postal"] = $row["codigo_postal"];
		$GLOBALS["x_lada"] = $row["lada"];
		$GLOBALS["x_telefono"] = $row["telefono"];
		$GLOBALS["x_fax"] = $row["fax"];
		$GLOBALS["x_contacto"] = $row["contacto"];
		$GLOBALS["x_contacto_email"] = $row["contacto_email"];
		$GLOBALS["x_fondeo_empresa_dependiente_id"] = $row["empresa_dependiente_id"];
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
	$sSql = "SELECT * FROM `fondeo_empresa`";
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
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
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
	$sSql = "Delete FROM `fondeo_empresa`";
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
	phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
	return true;
}
?>
