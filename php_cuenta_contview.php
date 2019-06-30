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
$x_cuenta_cont_id = Null; 
$ox_cuenta_cont_id = Null;
$x_cuenta_cont_tipo_id = Null; 
$ox_cuenta_cont_tipo_id = Null;
$x_cuenta = Null; 
$ox_cuenta = Null;
$x_sub_cuenta = Null; 
$ox_sub_cuenta = Null;
$x_ssub_cuenta = Null; 
$ox_ssub_cuenta = Null;
$x_sssub_cuenta = Null; 
$ox_sssub_cuenta = Null;
$x_descricpion = Null; 
$ox_descricpion = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Get key
$x_cuenta_cont_id = @$_GET["cuenta_cont_id"];
if (($x_cuenta_cont_id == "") || ((is_null($x_cuenta_cont_id)))) {
	ob_end_clean(); 
	header("Location: php_cuenta_contlist.php"); 
	exit();
}

//$x_cuenta_cont_id = (get_magic_quotes_gpc()) ? stripslashes($x_cuenta_cont_id) : $x_cuenta_cont_id;
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
			header("Location: php_cuenta_contlist.php");
			exit();
		}
}
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<p><span class="phpmaker">View TABLE: Contabilidad Catalogo de Cuentas<br><br>
<a href="php_cuenta_contlist.php">Back to List</a>&nbsp;
<a href="<?php if ($x_cuenta_cont_id <> "") {echo "php_cuenta_contedit.php?cuenta_cont_id=" . urlencode($x_cuenta_cont_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Edit</a>&nbsp;
<a href="<?php if ($x_cuenta_cont_id <> "") {echo "php_cuenta_contadd.php?cuenta_cont_id=" . urlencode($x_cuenta_cont_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Copy</a>&nbsp;
<a href="<?php if ($x_cuenta_cont_id <> "") {echo "php_cuenta_contdelete.php?cuenta_cont_id=" . urlencode($x_cuenta_cont_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Delete</a>&nbsp;
</span></p>
<p>
<form>
<table class="ewTable">
	<tr>
		<td class="ewTableHeader"><span>No</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_cuenta_cont_id; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Tipo</span></td>
		<td class="ewTableAltRow"><span>
<?php
if ((!is_null($x_cuenta_cont_tipo_id)) && ($x_cuenta_cont_tipo_id <> "")) {
	$sSqlWrk = "SELECT `desc_corta` FROM `cuenta_cont_tipo`";
	$sTmp = $x_cuenta_cont_tipo_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `cuenta_cont_tipo_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["desc_corta"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_cuenta_cont_tipo_id = $x_cuenta_cont_tipo_id; // Backup Original Value
$x_cuenta_cont_tipo_id = $sTmp;
?>
<?php echo $x_cuenta_cont_tipo_id; ?>
<?php $x_cuenta_cont_tipo_id = $ox_cuenta_cont_tipo_id; // Restore Original Value ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Cuenta</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_cuenta; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>S-cuenta</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_sub_cuenta; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>SS-cuenta</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_ssub_cuenta; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>SSS-cuenta</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_sssub_cuenta; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Descricpion</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_descricpion; ?>
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
	global $x_cuenta_cont_id;
	$sSql = "SELECT * FROM `cuenta_cont`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_cuenta_cont_id) : $x_cuenta_cont_id;
	$sWhere .= "(`cuenta_cont_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_cuenta_cont_id"] = $row["cuenta_cont_id"];
		$GLOBALS["x_cuenta_cont_tipo_id"] = $row["cuenta_cont_tipo_id"];
		$GLOBALS["x_cuenta"] = $row["cuenta"];
		$GLOBALS["x_sub_cuenta"] = $row["sub_cuenta"];
		$GLOBALS["x_ssub_cuenta"] = $row["ssub_cuenta"];
		$GLOBALS["x_sssub_cuenta"] = $row["sssub_cuenta"];
		$GLOBALS["x_descricpion"] = $row["descricpion"];
	}
	phpmkr_free_result($rs);
	return $bLoadData;
}
?>
