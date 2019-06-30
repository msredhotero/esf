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
$x_referencia_id = Null; 
$ox_referencia_id = Null;
$x_cliente_id = Null; 
$ox_cliente_id = Null;
$x_nombre_completo = Null; 
$ox_nombre_completo = Null;
$x_telefono = Null; 
$ox_telefono = Null;
$x_parentesco_tipo_id = Null; 
$ox_parentesco_tipo_id = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Get key
$x_referencia_id = @$_GET["referencia_id"];
if (($x_referencia_id == "") || ((is_null($x_referencia_id)))) {
	ob_end_clean(); 
	header("Location: php_referencialist.php"); 
	exit();
}

//$x_referencia_id = (get_magic_quotes_gpc()) ? stripslashes($x_referencia_id) : $x_referencia_id;
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
			header("Location: php_referencialist.php");
			exit();
		}
}
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<p><span class="phpmaker">View TABLE: REFERENCIAS<br><br>
<a href="php_referencialist.php">Back to List</a>&nbsp;
<a href="<?php if ($x_referencia_id <> "") {echo "php_referenciaedit.php?referencia_id=" . urlencode($x_referencia_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Edit</a>&nbsp;
<a href="<?php if ($x_referencia_id <> "") {echo "php_referenciaadd.php?referencia_id=" . urlencode($x_referencia_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Copy</a>&nbsp;
<a href="<?php if ($x_referencia_id <> "") {echo "php_referenciadelete.php?referencia_id=" . urlencode($x_referencia_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Delete</a>&nbsp;
</span></p>
<p>
<form>
<table class="ewTable">
	<tr>
		<td class="ewTableHeader"><span>No</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_referencia_id; ?>
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
		<td class="ewTableHeader"><span>Nombre completo</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_nombre_completo; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Teléfono</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_telefono; ?>
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
	global $x_referencia_id;
	$sSql = "SELECT * FROM `referencia`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_referencia_id) : $x_referencia_id;
	$sWhere .= "(`referencia_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_referencia_id"] = $row["referencia_id"];
		$GLOBALS["x_cliente_id"] = $row["cliente_id"];
		$GLOBALS["x_nombre_completo"] = $row["nombre_completo"];
		$GLOBALS["x_telefono"] = $row["telefono"];
		$GLOBALS["x_parentesco_tipo_id"] = $row["parentesco_tipo_id"];
	}
	phpmkr_free_result($rs);
	return $bLoadData;
}
?>
