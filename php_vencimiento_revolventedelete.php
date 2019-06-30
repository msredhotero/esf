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


$x_credito_id = @$_GET["credito_id"];
if (empty($x_credito_id)) {
	$x_credito_id = @$_POST["x_credito_id"];
	if (empty($x_credito_id)) {	
		echo "No se locaizo el credito.";	
		exit();
	}
}

$x_vencimiento_id = @$_GET["vencimiento_id"];
if (empty($x_vencimiento_id)) {
	$x_vencimiento_id = @$_POST["x_vencimiento_id"];
	if (empty($x_vencimiento_id)) {	
		echo "No se locaizo el vencimiento.";	
		exit();
	}
}

// Get action
$sAction = @$_POST["a_delete"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";		// Display with input box
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "X": // Display
		if (LoadRecordCount($sDbWhere,$conn) <= 0) {
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_vencimiento_revolventelist.php");
			exit();
		}
		break;
	case "D": // Delete
		if (DeleteData($x_vencimiento_id,$conn)) {
			$_SESSION["ewmsg"] = "El vencimiento ha sido eliminado.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_restructura_revolvente.php?credito_id=$x_credito_id");
			exit();
		}
		break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Financiera CRECE</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">td img {display: block;}</style>
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#FFFFFF">
<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>

<p><span class="phpmaker">ELIMINANDO VENCIMIENTO<br><br><a href="php_restructura_revolvente.php?credito_id=<?php echo $x_credito_id; ?>">Cancelar</a></span></p>
<?php

	$sSql = "SELECT vencimiento.*, credito.credito_num, vencimiento_status.descripcion FROM vencimiento join credito on credito.credito_id = vencimiento.credito_id join vencimiento_status on vencimiento_status.vencimiento_status_id = vencimiento.vencimiento_status_id where vencimiento.vencimiento_id = $x_vencimiento_id";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	if (phpmkr_num_rows($rs) == 0) {
		$bLoadData = false;
	}else{
		$bLoadData = true;
		$row = phpmkr_fetch_array($rs);

		// Get the field contents
		$x_vencimiento_num = $row["vencimiento_num"];
		$x_credito_num = $row["credito_num"];		
		$x_vencimiento_status = $row["descripcion"];
		$x_fecha_vencimiento = $row["fecha_vencimiento"];
		$x_importe = $row["importe"];
		$x_interes = $row["interes"];
		$x_interes_moratorio = $row["interes_moratorio"];
		$x_total_venc = $row["total_venc"];		
	}
	phpmkr_free_result($rs);

?>


<?php if($bLoadData == true){ ?>
<form action="" method="post">
<p>
<input type="hidden" name="a_delete" value="D">
<input type="hidden" name="x_credito_id" value="<?php echo $x_credito_id; ?>">
<input type="hidden" name="x_vencimiento_id" value="<?php echo $x_vencimiento_id; ?>">
<table class="ewTable">
	<tr class="ewTableHeader">
		<td valign="top"><span>No</span></td>
		<td valign="top"><span>Crédito</span></td>
		<td valign="top"><span>Status</span></td>
		<td valign="top"><span>Fecha de vencimiento</span></td>
		<td valign="top"><span>Importe</span></td>
		<td valign="top"><span>Interés</span></td>
		<td valign="top"><span>Interés moratorio</span></td>
		<td valign="top"><span>Total</span></td>        
	</tr>

	<tr>
		<td align="center"><span>
<?php echo $x_vencimiento_num; ?>
</span></td>
		<td align="center"><span>
<?php echo $x_credito_num; ?>
</span></td>
		<td align="center"><span>
<?php echo $x_vencimiento_status; ?>        
</span></td>
		<td align="center"><span>
<?php echo FormatDateTime($x_fecha_vencimiento,7); ?>
</span></td>
		<td align="right"><span>
<?php echo FormatNumber($x_importe,2,0,0,1); ?>
</span></td>
		<td align="right"><span>
<?php echo FormatNumber($x_interes,2,0,0,1); ?>
</span></td>
		<td align="right"><span>
<?php echo FormatNumber($x_interes_moratorio,2,0,0,1); ?>
</span></td>
		<td align="right"><span>
<?php echo FormatNumber($x_total_venc,2,0,0,1); ?>
</span></td>
	</tr>
</table>
<p>
<input type="submit" name="Action" value="CONFIRME">
</form>
<?php }else{ ?>
<div align="center"><span><font color="#FF0000"><b>EL VENCIMIENTO NO FUE LOCALIZADO.</b></font></span></div>
<?php } ?>
</body>
</html>

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
<?php

//-------------------------------------------------------------------------------
// Function LoadRecordCount
// - Load Record Count based on input sql criteria sqlKey

function LoadRecordCount($sqlKey,$conn)
{
	global $x_vencimiento_id;
	$sSql = "SELECT * FROM `vencimiento`";
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
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
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
	$sSql = "Delete FROM `vencimiento`";
	$sSql .= " WHERE vencimiento_id = " . $sqlKey;
	phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	return true;
}
?>
