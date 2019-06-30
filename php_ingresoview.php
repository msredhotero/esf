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
$x_ingreso_id = Null; 
$ox_ingreso_id = Null;
$x_cliente_id = Null; 
$ox_cliente_id = Null;
$x_ingresos_negocio = Null; 
$ox_ingresos_negocio = Null;
$x_ingresos_familiar_1 = Null; 
$ox_ingresos_familiar_1 = Null;
$x_parentesco_tipo_id = Null; 
$ox_parentesco_tipo_id = Null;
$x_ingresos_familiar_2 = Null; 
$ox_ingresos_familiar_2 = Null;
$x_parentesco_tipo_id2 = Null; 
$ox_parentesco_tipo_id2 = Null;
$x_otros_ingresos = Null; 
$ox_otros_ingresos = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Get key
$x_ingreso_id = @$_GET["ingreso_id"];
if (($x_ingreso_id == "") || ((is_null($x_ingreso_id)))) {
	ob_end_clean(); 
	header("Location: php_ingresolist.php"); 
	exit();
}

//$x_ingreso_id = (get_magic_quotes_gpc()) ? stripslashes($x_ingreso_id) : $x_ingreso_id;
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
			header("Location: php_ingresolist.php");
			exit();
		}
}
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<p><span class="phpmaker">View TABLE: ingreso<br><br>
<a href="php_ingresolist.php">Back to List</a>&nbsp;
<a href="<?php if ($x_ingreso_id <> "") {echo "php_ingresoedit.php?ingreso_id=" . urlencode($x_ingreso_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Edit</a>&nbsp;
<a href="<?php if ($x_ingreso_id <> "") {echo "php_ingresoadd.php?ingreso_id=" . urlencode($x_ingreso_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Copy</a>&nbsp;
<a href="<?php if ($x_ingreso_id <> "") {echo "php_ingresodelete.php?ingreso_id=" . urlencode($x_ingreso_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Delete</a>&nbsp;
</span></p>
<p>
<form>
<table class="ewTable">
	<tr>
		<td class="ewTableHeader"><span>No</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_ingreso_id; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Cliente</span></td>
		<td class="ewTableAltRow"><span>
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
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Ingresos del negocio</span></td>
		<td class="ewTableAltRow"><span>
<?php echo (is_numeric($x_ingresos_negocio)) ? FormatNumber($x_ingresos_negocio,0,0,0,-2) : $x_ingresos_negocio; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Ingresos familiares 1</span></td>
		<td class="ewTableAltRow"><span>
<?php echo (is_numeric($x_ingresos_familiar_1)) ? FormatNumber($x_ingresos_familiar_1,0,0,0,-2) : $x_ingresos_familiar_1; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Parentesco</span></td>
		<td class="ewTableAltRow"><span>
<?php
if ((!is_null($x_parentesco_tipo_id)) && ($x_parentesco_tipo_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `parentesco_tipo`";
	$sTmp = $x_parentesco_tipo_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `parentesco_tipo_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_parentesco_tipo_id = $x_parentesco_tipo_id; // Backup Original Value
$x_parentesco_tipo_id = $sTmp;
?>
<?php echo $x_parentesco_tipo_id; ?>
<?php $x_parentesco_tipo_id = $ox_parentesco_tipo_id; // Restore Original Value ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Ingresos familiares 2</span></td>
		<td class="ewTableAltRow"><span>
<?php echo (is_numeric($x_ingresos_familiar_2)) ? FormatNumber($x_ingresos_familiar_2,0,0,0,-2) : $x_ingresos_familiar_2; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Parentesco</span></td>
		<td class="ewTableAltRow"><span>
<?php
if ((!is_null($x_parentesco_tipo_id2)) && ($x_parentesco_tipo_id2 <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `parentesco_tipo`";
	$sTmp = $x_parentesco_tipo_id2;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `parentesco_tipo_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_parentesco_tipo_id2 = $x_parentesco_tipo_id2; // Backup Original Value
$x_parentesco_tipo_id2 = $sTmp;
?>
<?php echo $x_parentesco_tipo_id2; ?>
<?php $x_parentesco_tipo_id2 = $ox_parentesco_tipo_id2; // Restore Original Value ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Otros ingresos</span></td>
		<td class="ewTableAltRow"><span>
<?php echo (is_numeric($x_otros_ingresos)) ? FormatNumber($x_otros_ingresos,0,0,0,-2) : $x_otros_ingresos; ?>
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
	global $x_ingreso_id;
	$sSql = "SELECT * FROM `ingreso`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_ingreso_id) : $x_ingreso_id;
	$sWhere .= "(`ingreso_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_ingreso_id"] = $row["ingreso_id"];
		$GLOBALS["x_cliente_id"] = $row["cliente_id"];
		$GLOBALS["x_ingresos_negocio"] = $row["ingresos_negocio"];
		$GLOBALS["x_ingresos_familiar_1"] = $row["ingresos_familiar_1"];
		$GLOBALS["x_parentesco_tipo_id"] = $row["parentesco_tipo_id"];
		$GLOBALS["x_ingresos_familiar_2"] = $row["ingresos_familiar_2"];
		$GLOBALS["x_parentesco_tipo_id2"] = $row["parentesco_tipo_id2"];
		$GLOBALS["x_otros_ingresos"] = $row["otros_ingresos"];
	}
	phpmkr_free_result($rs);
	return $bLoadData;
}
?>
