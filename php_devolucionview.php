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
$x_devolucion_id = Null; 
$ox_devolucion_id = Null;
$x_fecha_registro = Null; 
$ox_fecha_registro = Null;
$x_fecha_pago = Null; 
$ox_fecha_pago = Null;
$x_medio_pago_id = Null; 
$ox_medio_pago_id = Null;
$x_referencia = Null; 
$ox_referencia = Null;
$x_factor = Null; 
$ox_factor = Null;
$x_importe = Null; 
$ox_importe = Null;
$x_devolucion_status_id = Null; 
$ox_devolucion_status_id = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Get key
$x_devolucion_id = @$_GET["devolucion_id"];
if (($x_devolucion_id == "") || ((is_null($x_devolucion_id)))) {
	ob_end_clean(); 
	header("Location: php_devolucionlist.php"); 
	exit();
}

//$x_devolucion_id = (get_magic_quotes_gpc()) ? stripslashes($x_devolucion_id) : $x_devolucion_id;
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
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No records found";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_devolucionlist.php");
			exit();
		}
}
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<p><span class="phpmaker">View TABLE: Devoluciones<br><br>
<a href="php_devolucionlist.php">Back to List</a>&nbsp;
<a href="<?php if ($x_devolucion_id <> "") {echo "php_devolucionedit.php?devolucion_id=" . urlencode($x_devolucion_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Edit</a>&nbsp;
<a href="<?php if ($x_devolucion_id <> "") {echo "php_devolucionadd.php?devolucion_id=" . urlencode($x_devolucion_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Copy</a>&nbsp;
<a href="<?php if ($x_devolucion_id <> "") {echo "php_devoluciondelete.php?devolucion_id=" . urlencode($x_devolucion_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Delete</a>&nbsp;
</span></p>
<p>
<form>
<table class="ewTable">
	<tr>
		<td class="ewTableHeader"><span>No</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_devolucion_id; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Fecha de registro</span></td>
		<td class="ewTableAltRow"><span>
<?php echo FormatDateTime($x_fecha_registro,7); ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Fecha de pago</span></td>
		<td class="ewTableAltRow"><span>
<?php echo FormatDateTime($x_fecha_pago,7); ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Medio de Pago</span></td>
		<td class="ewTableAltRow"><span>
<?php
if ((!is_null($x_medio_pago_id)) && ($x_medio_pago_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `medio_pago`";
	$sTmp = $x_medio_pago_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `medio_pago_id` = " . $sTmp . "";
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
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Referencia de Pago</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_referencia; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Descuento</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_factor; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Importe</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_importe; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Status</span></td>
		<td class="ewTableAltRow"><span>
<?php
if ((!is_null($x_devolucion_status_id)) && ($x_devolucion_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `devolucion_status`";
	$sTmp = $x_devolucion_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `devolucion_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_devolucion_status_id = $x_devolucion_status_id; // Backup Original Value
$x_devolucion_status_id = $sTmp;
?>
<?php echo $x_devolucion_status_id; ?>
<?php $x_devolucion_status_id = $ox_devolucion_status_id; // Restore Original Value ?>
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

function LoadData($conn)
{
	global $x_devolucion_id;
	$sSql = "SELECT * FROM `devolucion`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_devolucion_id) : $x_devolucion_id;
	$sWhere .= "(`devolucion_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_devolucion_id"] = $row["devolucion_id"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
		$GLOBALS["x_fecha_pago"] = $row["fecha_pago"];
		$GLOBALS["x_medio_pago_id"] = $row["medio_pago_id"];
		$GLOBALS["x_referencia"] = $row["referencia"];
		$GLOBALS["x_factor"] = $row["factor"];
		$GLOBALS["x_importe"] = $row["importe"];
		$GLOBALS["x_devolucion_status_id"] = $row["devolucion_status_id"];
	}
	phpmkr_free_result($rs);
	return $bLoadData;
}
?>
