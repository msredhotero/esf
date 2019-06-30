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
$x_credito_comment_id = Null;
$x_credito_id = Null;
$x_comentario_int = Null;
$x_comentario_ext = Null;
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
	header("Location: php_credito_commentlist.php"); 
	exit();
}
if (!empty($sKey)) $sKey = (get_magic_quotes_gpc()) ? stripslashes($sKey) : $sKey;

// Get action
$sAction = @$_POST["a_view"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";	// Display with input box
}

// Open connection to the database
$conn = phpmkr_db_connect(HOST,USER,PASS,DB);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($sKey,$conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No Record Found for Key = " . $sKey;
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_credito_commentlist.php");
			exit();
		}
}
?>
<?php include ("header.php") ?>
<p><span class="phpmaker">View TABLE: Comentarios<br><br>
<a href="php_credito_commentlist.php">Back to List</a>&nbsp;
<a href="<?php echo "php_credito_commentedit.php?key=" . urlencode($sKey); ?>">Edit</a>&nbsp;
</span></p>
<p>
<form>
<table class="ewTable">
	<tr>
		<td class="ewTableHeader"><span>No</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_credito_comment_id; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Credito Num</span></td>
		<td class="ewTableAltRow"><span>
<?php
if ((!is_null($x_credito_id)) && ($x_credito_id <> "")) {
	$sSqlWrk = "SELECT *  FROM `credito`";
	$sTmp = $x_credito_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE (`credito_id` = " . $sTmp . ")";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["num_pagos"];
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
		<td class="ewTableHeader"><span>Comentario Interno</span></td>
		<td class="ewTableAltRow"><span>
<?php echo str_replace(chr(10), "<br>", @$x_comentario_int); ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Comentario Externo</span></td>
		<td class="ewTableAltRow"><span>
<?php echo str_replace(chr(10), "<br>", @$x_comentario_ext); ?>
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
	$sSql = "SELECT * FROM `credito_comment`";
	$sSql .= " WHERE `credito_comment_id` = " . $sKeyWrk;
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
		$GLOBALS["x_credito_comment_id"] = $row["credito_comment_id"];
		$GLOBALS["x_credito_id"] = $row["credito_id"];
		$GLOBALS["x_comentario_int"] = $row["comentario_int"];
		$GLOBALS["x_comentario_ext"] = $row["comentario_ext"];
	}
	phpmkr_free_result($rs);
	return $LoadData;
}
?>
