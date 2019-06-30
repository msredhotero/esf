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

// Initialize common variables
$x_recibo_qr_id = Null;
$x_promotor_id = Null;
$x_fecha = Null;
$x_hora = Null;
$x_cliente = Null;
$x_credito = Null;
$x_monto = Null;
$x_comentario = Null;
$x_folio = Null;
$x_confirma_cliente = Null;
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
	header("Locationphp_recibo_qrlist.php"); 
}
if (!empty($sKey)) $sKey = (get_magic_quotes_gpc()) ? stripslashes($sKey) : $sKey;

// Get action
$sAction = @$_POST["a_view"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";	// Display with input box
}

// Open connection to the database
$conn = phpmkr_db_connect(HOST,USER,PASS,DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($sKey,$conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No Record Found for Key = " . $sKey;
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location php_recibo_qrlist.php");
		}
}
?>
<?php include ("header.php") ?>
<p><span class="phpmaker">Recibo registrado<br><br>
<a href="php_recibo_qrlist.php">Regresar a la lista</a>&nbsp;&nbsp;
</span></p>
<p>
<form>
<table class="ewTable">
	<tr>
		<td width="114" class="ewTableHeader"><span>recibo qr id</span></td>
		<td width="874" class="ewTableAltRow"><span>
<?php echo $x_recibo_qr_id; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>promotor id</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_promotor_id; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>fecha</span></td>
		<td class="ewTableAltRow"><span>
<?php echo FormatDateTime($x_fecha,5); ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>hora</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_hora; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>cliente</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_cliente; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>credito</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_credito; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>monto</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_monto; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>comentario</span></td>
		<td class="ewTableAltRow"><span>
<?php echo str_replace(chr(10), "<br>", @$x_comentario); ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>folio</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_folio; ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>confirma cliente</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_confirma_cliente; ?>
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
	$sSql = "SELECT * FROM `recibo_qr`";
	$sSql .= " WHERE `recibo_qr_id` = " . $sKeyWrk;
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
	$rs = phpmkr_query($sSql,$conn);
	if (phpmkr_num_rows($rs) == 0) {
		$LoadData = false;
	}else{
		$LoadData = true;
		$row = phpmkr_fetch_array($rs);

		// Get the field contents
		$GLOBALS["x_recibo_qr_id"] = $row["recibo_qr_id"];
		$GLOBALS["x_promotor_id"] = $row["promotor_id"];
		$GLOBALS["x_fecha"] = $row["fecha"];
		$GLOBALS["x_hora"] = $row["hora"];
		$GLOBALS["x_cliente"] = $row["cliente"];
		$GLOBALS["x_credito"] = $row["credito"];
		$GLOBALS["x_monto"] = $row["monto"];
		$GLOBALS["x_comentario"] = $row["comentario"];
		$GLOBALS["x_folio"] = $row["folio"];
		$GLOBALS["x_confirma_cliente"] = $row["confirma_cliente"];
	}
	phpmkr_free_result($rs);
	return $LoadData;
}
?>
