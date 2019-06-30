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
$x_direccion_id = Null; 
$ox_direccion_id = Null;
$x_cliente_id = Null; 
$ox_cliente_id = Null;
$x_aval_id = Null; 
$ox_aval_id = Null;
$x_promotor_id = Null; 
$ox_promotor_id = Null;
$x_direccion_tipo_id = Null; 
$ox_direccion_tipo_id = Null;
$x_calle = Null; 
$ox_calle = Null;
$x_colonia = Null; 
$ox_colonia = Null;
$x_delegacion_id = Null; 
$ox_delegacion_id = Null;
$x_otra_delegacion = Null; 
$ox_otra_delegacion = Null;
$x_entidad = Null; 
$ox_entidad = Null;
$x_codigo_postal = Null; 
$ox_codigo_postal = Null;
$x_ubicacion = Null; 
$ox_ubicacion = Null;
$x_antiguedad = Null; 
$ox_antiguedad = Null;
$x_vivienda_tipo_id = Null; 
$ox_vivienda_tipo_id = Null;
$x_otro_tipo_vivienda = Null; 
$ox_otro_tipo_vivienda = Null;
$x_telefono = Null; 
$ox_telefono = Null;
$x_telefono_secundario = Null; 
$ox_telefono_secundario = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
$arRecKey = Null;

// Load Key Parameters
$sKey = "";
$bSingleDelete = true;
$x_direccion_id = @$_GET["direccion_id"];
if (!empty($x_direccion_id)) {
	if ($sKey <> "") { $sKey .= ","; }
	$sKey .= $x_direccion_id;
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
	header("Location: php_direccionlist.php");
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
	$sDbWhere .= "`direccion_id`=" . $sRecKey . " AND ";
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
			header("Location: php_direccionlist.php");
			exit();
		}
		break;
	case "D": // Delete
		if (DeleteData($sDbWhere,$conn)) {
			$_SESSION["ewmsg"] = "Delete Successful";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_direccionlist.php");
			exit();
		}
		break;
}
?>
<?php include ("header.php") ?>
<p><span class="phpmaker">Delete from TABLE: DIRECCIONES<br><br><a href="php_direccionlist.php">Back to List</a></span></p>
<form action="php_direcciondelete.php" method="post">
<p>
<input type="hidden" name="a_delete" value="D">
<?php $sKey = (get_magic_quotes_gpc()) ? stripslashes($sKey) : $sKey; ?>
<input type="hidden" name="key_d" value="<?php echo htmlspecialchars($sKey); ?>">
<table class="ewTable">
	<tr class="ewTableHeader">
		<td valign="top"><span>No</span></td>
		<td valign="top"><span>Cliente</span></td>
		<td valign="top"><span>Aval</span></td>
		<td valign="top"><span>Promotor</span></td>
		<td valign="top"><span>Tipo de dirección</span></td>
		<td valign="top"><span>Calle</span></td>
		<td valign="top"><span>colonia</span></td>
		<td valign="top"><span>Delegación</span></td>
		<td valign="top"><span>Otra delegación</span></td>
		<td valign="top"><span>Entidad</span></td>
		<td valign="top"><span>Codigo postal</span></td>
		<td valign="top"><span>Ubicación</span></td>
		<td valign="top"><span>Antiguedad</span></td>
		<td valign="top"><span>Tipo de vivienda</span></td>
		<td valign="top"><span>Otro tipo de vivienda</span></td>
		<td valign="top"><span>Teléfono</span></td>
		<td valign="top"><span>Teléfono secundario</span></td>
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
	$x_direccion_id = $sRecKey;
	if (LoadData($conn)) {
?>
	<tr<?php echo $sItemRowClass;?>>
		<td><span>
<?php echo $x_direccion_id; ?>
</span></td>
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
		<td><span>
<?php
if ((!is_null($x_aval_id)) && ($x_aval_id <> "")) {
	$sSqlWrk = "SELECT `nombre_completo` FROM `aval`";
	$sTmp = $x_aval_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `aval_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["nombre_completo"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_aval_id = $x_aval_id; // Backup Original Value
$x_aval_id = $sTmp;
?>
<?php echo $x_aval_id; ?>
<?php $x_aval_id = $ox_aval_id; // Restore Original Value ?>
</span></td>
		<td><span>
<?php
if ((!is_null($x_promotor_id)) && ($x_promotor_id <> "")) {
	$sSqlWrk = "SELECT `nombre_completo` FROM `promotor`";
	$sTmp = $x_promotor_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `promotor_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["nombre_completo"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_promotor_id = $x_promotor_id; // Backup Original Value
$x_promotor_id = $sTmp;
?>
<?php echo $x_promotor_id; ?>
<?php $x_promotor_id = $ox_promotor_id; // Restore Original Value ?>
</span></td>
		<td><span>
<?php
if ((!is_null($x_direccion_tipo_id)) && ($x_direccion_tipo_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `direccion_tipo`";
	$sTmp = $x_direccion_tipo_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `direccion_tipo_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_direccion_tipo_id = $x_direccion_tipo_id; // Backup Original Value
$x_direccion_tipo_id = $sTmp;
?>
<?php echo $x_direccion_tipo_id; ?>
<?php $x_direccion_tipo_id = $ox_direccion_tipo_id; // Restore Original Value ?>
</span></td>
		<td><span>
<?php echo $x_calle; ?>
</span></td>
		<td><span>
<?php echo $x_colonia; ?>
</span></td>
		<td><span>
<?php
if ((!is_null($x_delegacion_id)) && ($x_delegacion_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `delegacion`";
	$sTmp = $x_delegacion_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `delegacion_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_delegacion_id = $x_delegacion_id; // Backup Original Value
$x_delegacion_id = $sTmp;
?>
<?php echo $x_delegacion_id; ?>
<?php $x_delegacion_id = $ox_delegacion_id; // Restore Original Value ?>
</span></td>
		<td><span>
<?php echo $x_otra_delegacion; ?>
</span></td>
		<td><span>
<?php echo $x_entidad; ?>
</span></td>
		<td><span>
<?php echo $x_codigo_postal; ?>
</span></td>
		<td><span>
<?php echo $x_ubicacion; ?>
</span></td>
		<td><span>
<?php echo $x_antiguedad; ?>
</span></td>
		<td><span>
<?php
if ((!is_null($x_vivienda_tipo_id)) && ($x_vivienda_tipo_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `vivienda_tipo`";
	$sTmp = $x_vivienda_tipo_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `vivienda_tipo_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_vivienda_tipo_id = $x_vivienda_tipo_id; // Backup Original Value
$x_vivienda_tipo_id = $sTmp;
?>
<?php echo $x_vivienda_tipo_id; ?>
<?php $x_vivienda_tipo_id = $ox_vivienda_tipo_id; // Restore Original Value ?>
</span></td>
		<td><span>
<?php echo $x_otro_tipo_vivienda; ?>
</span></td>
		<td><span>
<?php echo $x_telefono; ?>
</span></td>
		<td><span>
<?php echo $x_telefono_secundario; ?>
</span></td>
	</tr>
<?php
	}
	$i += 1;
}
?>
</table>
<p>
<input type="submit" name="Action" value="CONFIRM DELETE">
</form>
<?php include ("footer.php") ?>
<?php

//-------------------------------------------------------------------------------
// Function LoadData
// - Load Data based on Key Value sKey
// - Variables setup: field variables

function LoadData($conn)
{
	global $x_direccion_id;
	$sSql = "SELECT * FROM `direccion`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_direccion_id) : $x_direccion_id;
	$sWhere .= "(`direccion_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_direccion_id"] = $row["direccion_id"];
		$GLOBALS["x_cliente_id"] = $row["cliente_id"];
		$GLOBALS["x_aval_id"] = $row["aval_id"];
		$GLOBALS["x_promotor_id"] = $row["promotor_id"];
		$GLOBALS["x_direccion_tipo_id"] = $row["direccion_tipo_id"];
		$GLOBALS["x_calle"] = $row["calle"];
		$GLOBALS["x_colonia"] = $row["colonia"];
		$GLOBALS["x_delegacion_id"] = $row["delegacion_id"];
		$GLOBALS["x_otra_delegacion"] = $row["otra_delegacion"];
		$GLOBALS["x_entidad"] = $row["entidad"];
		$GLOBALS["x_codigo_postal"] = $row["codigo_postal"];
		$GLOBALS["x_ubicacion"] = $row["ubicacion"];
		$GLOBALS["x_antiguedad"] = $row["antiguedad"];
		$GLOBALS["x_vivienda_tipo_id"] = $row["vivienda_tipo_id"];
		$GLOBALS["x_otro_tipo_vivienda"] = $row["otro_tipo_vivienda"];
		$GLOBALS["x_telefono"] = $row["telefono"];
		$GLOBALS["x_telefono_secundario"] = $row["telefono_secundario"];
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
	global $x_direccion_id;
	$sSql = "SELECT * FROM `direccion`";
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
	global $x_direccion_id;
	$sSql = "Delete FROM `direccion`";
	$sSql .= " WHERE " . $sqlKey;
	phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	return true;
}
?>
