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
$x_inciso_referencia = Null; 
$ox_inciso_referencia = Null;
$x_solicitud_inciso_id = Null; 
$ox_solicitud_inciso_id = Null;
$x_referencia_id = Null; 
$ox_referencia_id = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Get key
$x_inciso_referencia = @$_GET["inciso_referencia"];
if (($x_inciso_referencia == "") || ((is_null($x_inciso_referencia)))) {
	ob_end_clean(); 
	header("Location: php_inciso_referencialist.php"); 
	exit();
}

//$x_inciso_referencia = (get_magic_quotes_gpc()) ? stripslashes($x_inciso_referencia) : $x_inciso_referencia;
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
			header("Location: php_inciso_referencialist.php");
			exit();
		}
}
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<p><span class="phpmaker">View TABLE: inciso referencia<br><br>
<a href="php_inciso_referencialist.php">Back to List</a>&nbsp;
<a href="<?php if ($x_inciso_referencia <> "") {echo "php_inciso_referenciaedit.php?inciso_referencia=" . urlencode($x_inciso_referencia); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Edit</a>&nbsp;
<a href="<?php if ($x_inciso_referencia <> "") {echo "php_inciso_referenciaadd.php?inciso_referencia=" . urlencode($x_inciso_referencia); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Copy</a>&nbsp;
<a href="<?php if ($x_inciso_referencia <> "") {echo "php_inciso_referenciadelete.php?inciso_referencia=" . urlencode($x_inciso_referencia); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Delete</a>&nbsp;
</span></p>
<p>
<form>
<table class="ewTable">
	<tr>
		<td class="ewTableHeader"><span>No</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_inciso_referencia; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Inciso</span></td>
		<td class="ewTableAltRow"><span>
<?php
if ((!is_null($x_solicitud_inciso_id)) && ($x_solicitud_inciso_id <> "")) {
	$sSqlWrk = "SELECT `solicitud_inciso_id` FROM `solicitud_inciso`";
	$sTmp = $x_solicitud_inciso_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `solicitud_inciso_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["solicitud_inciso_id"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_solicitud_inciso_id = $x_solicitud_inciso_id; // Backup Original Value
$x_solicitud_inciso_id = $sTmp;
?>
<?php echo $x_solicitud_inciso_id; ?>
<?php $x_solicitud_inciso_id = $ox_solicitud_inciso_id; // Restore Original Value ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Referencia</span></td>
		<td class="ewTableAltRow"><span>
<?php
if ((!is_null($x_referencia_id)) && ($x_referencia_id <> "")) {
	$sSqlWrk = "SELECT `referencia_id` FROM `referencia`";
	$sTmp = $x_referencia_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `referencia_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["referencia_id"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_referencia_id = $x_referencia_id; // Backup Original Value
$x_referencia_id = $sTmp;
?>
<?php echo $x_referencia_id; ?>
<?php $x_referencia_id = $ox_referencia_id; // Restore Original Value ?>
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
	global $x_inciso_referencia;
	$sSql = "SELECT * FROM `inciso_referencia`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_inciso_referencia) : $x_inciso_referencia;
	$sWhere .= "(`inciso_referencia` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_inciso_referencia"] = $row["inciso_referencia"];
		$GLOBALS["x_solicitud_inciso_id"] = $row["solicitud_inciso_id"];
		$GLOBALS["x_referencia_id"] = $row["referencia_id"];
	}
	phpmkr_free_result($rs);
	return $bLoadData;
}
?>
