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
$x_vencimiento_id = Null; 
$ox_vencimiento_id = Null;
$x_credito_id = Null; 
$ox_credito_id = Null;
$x_vencimiento_status_id = Null; 
$ox_vencimiento_status_id = Null;
$x_fecha_vencimiento = Null; 
$ox_fecha_vencimiento = Null;
$x_importe = Null; 
$ox_importe = Null;
$x_interes = Null; 
$ox_interes = Null;
$x_interes_moratorio = Null; 
$ox_interes_moratorio = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Get key
$x_vencimiento_id = @$_GET["vencimiento_id"];
if (($x_vencimiento_id == "") || ((is_null($x_vencimiento_id)))) {
	ob_end_clean(); 
	header("Location: php_vencimientolist.php"); 
	exit();
}

//$x_vencimiento_id = (get_magic_quotes_gpc()) ? stripslashes($x_vencimiento_id) : $x_vencimiento_id;
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
			header("Location: php_vencimientolist.php");
			exit();
		}
}
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<p><span class="phpmaker">View TABLE: VENCIMIENTOS<br><br>
<a href="php_vencimientolist.php">Back to List</a>&nbsp;
<a href="<?php if ($x_vencimiento_id <> "") {echo "php_vencimientoedit.php?vencimiento_id=" . urlencode($x_vencimiento_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Edit</a>&nbsp;
<a href="<?php if ($x_vencimiento_id <> "") {echo "php_vencimientoadd.php?vencimiento_id=" . urlencode($x_vencimiento_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Copy</a>&nbsp;
<a href="<?php if ($x_vencimiento_id <> "") {echo "php_vencimientodelete.php?vencimiento_id=" . urlencode($x_vencimiento_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Delete</a>&nbsp;
</span></p>
<p>
<form>
<table class="ewTable">
	<tr>
		<td class="ewTableHeader"><span>No</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_vencimiento_id; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Crédito</span></td>
		<td class="ewTableAltRow"><span>
<?php
if ((!is_null($x_credito_id)) && ($x_credito_id <> "")) {
	$sSqlWrk = "SELECT `credito_id` FROM `credito`";
	$sTmp = $x_credito_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `credito_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["credito_id"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_credito_id = $x_credito_id; // Backup Original Value
$x_credito_id = $sTmp;
?>
<?php echo $x_credito_id; ?>
<?php $x_credito_id = $ox_credito_id; // Restore Original Value ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Status</span></td>
		<td class="ewTableAltRow"><span>
<?php
if ((!is_null($x_vencimiento_status_id)) && ($x_vencimiento_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `vencimiento_status`";
	$sTmp = $x_vencimiento_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `vencimiento_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_vencimiento_status_id = $x_vencimiento_status_id; // Backup Original Value
$x_vencimiento_status_id = $sTmp;
?>
<?php echo $x_vencimiento_status_id; ?>
<?php $x_vencimiento_status_id = $ox_vencimiento_status_id; // Restore Original Value ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Fecha de vencimiento</span></td>
		<td class="ewTableAltRow"><span>
<?php echo FormatDateTime($x_fecha_vencimiento,7); ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Importe</span></td>
		<td class="ewTableAltRow"><span>
<?php echo (is_numeric($x_importe)) ? FormatNumber($x_importe,0,0,0,-2) : $x_importe; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Interés</span></td>
		<td class="ewTableAltRow"><span>
<?php echo (is_numeric($x_interes)) ? FormatNumber($x_interes,0,-2,0,-2) : $x_interes; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Interés moratorio</span></td>
		<td class="ewTableAltRow"><span>
<?php echo (is_numeric($x_interes_moratorio)) ? FormatNumber($x_interes_moratorio,0,0,0,-2) : $x_interes_moratorio; ?>
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
	global $x_vencimiento_id;
	$sSql = "SELECT * FROM `vencimiento`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_vencimiento_id) : $x_vencimiento_id;
	$sWhere .= "(`vencimiento_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_vencimiento_id"] = $row["vencimiento_id"];
		$GLOBALS["x_credito_id"] = $row["credito_id"];
		$GLOBALS["x_vencimiento_status_id"] = $row["vencimiento_status_id"];
		$GLOBALS["x_fecha_vencimiento"] = $row["fecha_vencimiento"];
		$GLOBALS["x_importe"] = $row["importe"];
		$GLOBALS["x_interes"] = $row["interes"];
		$GLOBALS["x_interes_moratorio"] = $row["interes_moratorio"];
	}
	phpmkr_free_result($rs);
	return $bLoadData;
}
?>
