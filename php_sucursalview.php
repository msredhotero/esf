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
$x_sucursal_id = Null;
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
$x_sucursal_dependiente_id = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
$sKey = @$_GET["key"];
if (($sKey == "") || ((is_null($sKey)))) {
	$sKey = @$_GET["key"]; 
}
if (($sKey == "") || ((is_null($sKey)))) {
	ob_end_clean(); 
	header("Location: php_sucursallist.php"); 
	exit();
}
if (!empty($sKey)) $sKey = (get_magic_quotes_gpc()) ? stripslashes($sKey) : $sKey;

// Get action
$sAction = @$_POST["a_view"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";	// Display with input box
}

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($sKey,$conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No Record Found for Key = " . $sKey;
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_sucursallist.php");
			exit();
		}
}
?>
<?php include ("header.php") ?>
<p><span class="phpmaker">Sucursales<br><br>
<a href="php_sucursallist.php">Regresar al listado</a>&nbsp;
<a href="<?php echo "php_sucursaledit.php?key=" . urlencode($sKey); ?>">Editar</a>&nbsp;
<a href="<?php echo "php_sucursaldelete.php?key=" . urlencode($sKey); ?>">Eliminar</a></span></p>
<p>
<form>
<table class="ewTable">
	<tr>
		<td width="119" class="ewTableHeaderThin"><span>No</span></td>
		<td width="869" class="ewTableAltRow"><span>
<?php echo $x_sucursal_id; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Nombre</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_nombre; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Entidad</span></td>
		<td class="ewTableAltRow"><span>
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
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Calle</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_calle; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Colonia</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_colonia; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Ciudad</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_ciudad; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Codigo postal</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_codigo_postal; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Lada</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_lada; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Telefono</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_telefono; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Fax</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_fax; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Contacto</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_contacto; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Contacto email</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_contacto_email; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Depende</span></td>
		<td class="ewTableAltRow"><span>
<?php
if ((!is_null($x_sucursal_dependiente_id)) && ($x_sucursal_dependiente_id <> "")) {
	$sSqlWrk = "SELECT *  FROM `sucursal`";
	$sTmp = $x_sucursal_dependiente_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE (`sucursal_id` = " . $sTmp . ")";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["nombre"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_sucursal_dependiente_id = $x_sucursal_dependiente_id; // Backup Original Value
$x_sucursal_dependiente_id = $sTmp;
?>
<?php echo $x_sucursal_dependiente_id; ?>
<?php $x_sucursal_dependiente_id = $ox_sucursal_dependiente_id; // Restore Original Value ?>
</span></td>
	</tr>
</table>
</form>
<p>
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
	$sSql = "SELECT * FROM `sucursal`";
	$sSql .= " WHERE `sucursal_id` = " . $sKeyWrk;
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
		$GLOBALS["x_sucursal_id"] = $row["sucursal_id"];
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
		$GLOBALS["x_sucursal_dependiente_id"] = $row["sucursal_dependiente_id"];
	}
	phpmkr_free_result($rs);
	return $LoadData;
}
?>
