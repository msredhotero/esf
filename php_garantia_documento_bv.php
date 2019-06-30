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
<?php include ("db.php") ?>
<?php

// Get key
$x_garantia_id = @$_GET["garantia_id"];
if (($x_garantia_id == "") || (is_null($x_garantia_id))) { 
	ob_end_clean();
	header("Location: php_garantialist.php");
	exit();
}
$x_garantia_id = (get_magic_quotes_gpc()) ? stripslashes($x_garantia_id) : $x_garantia_id;
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$sSql = "SELECT * FROM `garantia`";
$sWhere = "";
$sGroupBy = "";
$sHaving = "";
$sOrderBy = "";
if ($sWhere <> "") { $sWhere .= " AND "; }
$sTmp = (!get_magic_quotes_gpc()) ? addslashes($x_garantia_id) : $x_garantia_id;
$sWhere .= "(`garantia_id` = " . $sTmp . ")";
$sSql .= " WHERE " . $sWhere;
if ($sGroupBy != "") {
	$sSql .= " GROUP BY " . $sGroupBy;
}
if ($sHaving != "") {
	$sSql .= " HAVING " . $sHaving;
}
if ($sOrderBy != "") {
	$sSql .= " ORDER BY " . $sOrderBy;
}
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
if (phpmkr_num_rows($rs) > 0) {
	$row = phpmkr_fetch_array($rs);
	if ($row["documento"]<> "") {
		header("Content-Disposition: attachment; filename=" . $row["documento"]);
	}
	echo $row["documento"];
}
phpmkr_free_result($rs);
phpmkr_db_close($conn);
?>
