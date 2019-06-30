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
$x_masiva_pago_pendiente_id = Null;
$x_carga_folio_id = Null;
$x_fecha_carga = Null;
$x_aplicacion_status_id = Null;
$x_ref_pago = Null;
$x_nombre_cliente = Null;
$x_numero_cliente = Null;
$x_importe = Null;
$x_fecha_movimiento = Null;
$x_nombre_archivo = Null;
$x_sucursal_id = Null;
$x_uploaded_file_id = Null;
$x_no_aplicar_pago = Null;
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
$arRecKey = split(",",$sKey);

// Single delete record
if (($sKey == "") || ((is_null($sKey)))) {
	ob_end_clean();
	header("Location: php_masiva_pago_pendientelist.php");
}
	$sKey = (get_magic_quotes_gpc()) ? $sKey : addslashes($sKey);
$sDbWhere .= "`masiva_pago_pendiente_id`=" . trim($sKey) . "";

// Get action
$sAction = @$_POST["a_delete"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";	// Display with input box
}
$conn = phpmkr_db_connect(HOST, USER, PASS,DB, PORT);
switch ($sAction)
{
	case "I": // Display
		if (LoadRecordCount($sDbWhere,$conn) <= 0) {
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_masiva_pago_pendientelist.php");
		}
		break;
	case "D": // Delete
		if (DeleteData($sDbWhere,$conn)) {
			$_SESSION["ewmsg"] = "Registro eliminado = " . stripslashes($sKey);
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_masiva_pago_pendientelist.php");
		}
		break;
}
?>
<?php include ("header.php") ?>
<p><span class="phpmaker">Eliminar Pago Pendiente<br>
  <br><a href="php_masiva_pago_pendientelist.php">Regresar a la lista</a></span></p>
<form action="php_masiva_pago_pendientedelete.php" method="post">
<p>
<input type="hidden" name="a_delete" value="D">
<?php $sKey = (get_magic_quotes_gpc()) ? stripslashes($sKey) : $sKey; ?>
<input type="hidden" name="key_d" value="<?php echo  htmlspecialchars($sKey); ?>">
<table class="ewTable">
	<tr class="ewTableHeader">
		<td valign="top"><span>masiva pago pendiente id</span></td>
		<td valign="top"><span>carga folio id</span></td>
		<td valign="top"><span>fecha carga</span></td>
		<td valign="top"><span>ref pago</span></td>
		<td valign="top"><span>nombre cliente</span></td>
		<td valign="top"><span>numero cliente</span></td>
		<td valign="top"><span>importe</span></td>
		<td valign="top"><span>fecha movimiento</span></td>
		<td valign="top"><span>nombre archivo</span></td>
		<td valign="top"><span>sucursal id</span></td>
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
<?php echo $x_masiva_pago_pendiente_id; ?>
</span></td>
		<td><span>
<?php echo $x_carga_folio_id; ?>
</span></td>
		<td><span>
<?php echo FormatDateTime($x_fecha_carga,5); ?>
</span></td>
		<td><span>
<?php echo $x_ref_pago; ?>
</span></td>
		<td><span>
<?php echo $x_nombre_cliente; ?>
</span></td>
		<td><span>
<?php echo $x_numero_cliente; ?>
</span></td>
		<td><span>
<?php echo $x_importe; ?>
</span></td>
		<td><span>
<?php echo FormatDateTime($x_fecha_movimiento,5); ?>
</span></td>
		<td><span>
<?php echo $x_nombre_archivo; ?>
</span></td>
		<td><span>
<?php echo $x_sucursal_id; ?>
</span></td>
	</tr>
<?php
	}
}
?>
</table>
<p>
<input type="submit" name="Action" value="Eliminar">
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
	$sSql = "SELECT * FROM `masiva_pago_pendiente`";
	$sSql .= " WHERE `masiva_pago_pendiente_id` = " . $sKeyWrk;
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
		$GLOBALS["x_masiva_pago_pendiente_id"] = $row["masiva_pago_pendiente_id"];
		$GLOBALS["x_carga_folio_id"] = $row["carga_folio_id"];
		$GLOBALS["x_fecha_carga"] = $row["fecha_carga"];
		$GLOBALS["x_aplicacion_status_id"] = $row["aplicacion_status_id"];
		$GLOBALS["x_ref_pago"] = $row["ref_pago"];
		$GLOBALS["x_nombre_cliente"] = $row["nombre_cliente"];
		$GLOBALS["x_numero_cliente"] = $row["numero_cliente"];
		$GLOBALS["x_importe"] = $row["importe"];
		$GLOBALS["x_fecha_movimiento"] = $row["fecha_movimiento"];
		$GLOBALS["x_nombre_archivo"] = $row["nombre_archivo"];
		$GLOBALS["x_sucursal_id"] = $row["sucursal_id"];
		$GLOBALS["x_uploaded_file_id"] = $row["uploaded_file_id"];
		$GLOBALS["x_no_aplicar_pago"] = $row["no_aplicar_pago"];
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
	$sSql = "SELECT * FROM `masiva_pago_pendiente`";
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
	$sSql = "Delete FROM `masiva_pago_pendiente`";
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
