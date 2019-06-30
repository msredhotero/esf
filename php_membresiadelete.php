<?php session_start(); ?>
<?php ob_start(); ?>
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
$x_membresia_id = Null;
$x_fecha_registro = Null;
$x_monto = Null;
$x_status = Null;
$x_sucursal_id = Null;
$x_fecha_expiracion = Null;
$x_nombre = Null;
$x_apellido_paterno = Null;
$x_apellido_materno = Null;
$x_numero_cliente = Null;
$x_calle = Null;
$x_numero = Null;
$x_colonia = Null;
$x_estado_id = Null;
$x_delegacion_id = Null;
$x_fecha_nacimiento = Null;
$x_promotor_id = Null;
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
	header("Location: php_membresialist.php");
}
	$sKey = (get_magic_quotes_gpc()) ? $sKey : addslashes($sKey);
$sDbWhere .= "`membresia_id`=" . trim($sKey) . "";

// Get action
$sAction = @$_POST["a_delete"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";	// Display with input box
}
$conn = phpmkr_db_connect(HOST, USER, PASS,DB);
switch ($sAction)
{
	case "I": // Display
		if (LoadRecordCount($sDbWhere,$conn) <= 0) {
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_membresialist.php");
		}
		break;
	case "D": // Delete
		if (DeleteData($sDbWhere,$conn)) {
			$_SESSION["ewmsg"] = "Delete Successful For Key = " . stripslashes($sKey);
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_membresialist.php");
		}
		break;
}
?>
<?php include ("header.php") ?>
<p><span class="phpmaker">Delete from TABLE: membresia<br><br><a href="php_membresialist.php">Back to List</a></span></p>
<form action="php_membresiadelete.php" method="post">
<p>
<input type="hidden" name="a_delete" value="D">
<?php $sKey = (get_magic_quotes_gpc()) ? stripslashes($sKey) : $sKey; ?>
<input type="hidden" name="key_d" value="<?php echo  htmlspecialchars($sKey); ?>">
<table class="ewTable">
	<tr class="ewTableHeader">
		<td valign="top"><span>membresia id</span></td>
		<td valign="top"><span>fecha registro</span></td>
		<td valign="top"><span>monto</span></td>
		<td valign="top"><span>status</span></td>
		<td valign="top"><span>sucursal id</span></td>
		<td valign="top"><span>fecha expiracion</span></td>
		<td valign="top"><span>numero cliente</span></td>
		<td valign="top"><span>estado id</span></td>
		<td valign="top"><span>delegacion id</span></td>
		<td valign="top"><span>fecha nacimiento</span></td>
		<td valign="top"><span>promotor id</span></td>
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
<?php echo $x_membresia_id; ?>
</span></td>
		<td><span>
<?php echo FormatDateTime($x_fecha_registro,5); ?>
</span></td>
		<td><span>
<?php echo $x_monto; ?>
</span></td>
		<td><span>
<?php echo $x_status; ?>
</span></td>
		<td><span>
<?php echo $x_sucursal_id; ?>
</span></td>
		<td><span>
<?php echo FormatDateTime($x_fecha_expiracion,5); ?>
</span></td>
		<td><span>
<?php echo $x_numero_cliente; ?>
</span></td>
		<td><span>
<?php echo $x_estado_id; ?>
</span></td>
		<td><span>
<?php echo $x_delegacion_id; ?>
</span></td>
		<td><span>
<?php echo FormatDateTime($x_fecha_nacimiento,5); ?>
</span></td>
		<td><span>
<?php echo $x_promotor_id; ?>
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
	$sKeyWrk = "" . addslashes($sKey) . "";
	$sSql = "SELECT * FROM `membresia`";
	$sSql .= " WHERE `membresia_id` = " . $sKeyWrk;
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
		$GLOBALS["x_membresia_id"] = $row["membresia_id"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
		$GLOBALS["x_monto"] = $row["monto"];
		$GLOBALS["x_status"] = $row["status"];
		$GLOBALS["x_sucursal_id"] = $row["sucursal_id"];
		$GLOBALS["x_fecha_expiracion"] = $row["fecha_expiracion"];
		$GLOBALS["x_nombre"] = $row["nombre"];
		$GLOBALS["x_apellido_paterno"] = $row["apellido_paterno"];
		$GLOBALS["x_apellido_materno"] = $row["apellido_materno"];
		$GLOBALS["x_numero_cliente"] = $row["numero_cliente"];
		$GLOBALS["x_calle"] = $row["calle"];
		$GLOBALS["x_numero"] = $row["numero"];
		$GLOBALS["x_colonia"] = $row["colonia"];
		$GLOBALS["x_estado_id"] = $row["estado_id"];
		$GLOBALS["x_delegacion_id"] = $row["delegacion_id"];
		$GLOBALS["x_fecha_nacimiento"] = $row["fecha_nacimiento"];
		$GLOBALS["x_promotor_id"] = $row["promotor_id"];
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
	$sSql = "SELECT * FROM `membresia`";
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
	$sSql = "Delete FROM `membresia`";
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
