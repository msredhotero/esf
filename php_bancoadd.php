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
$x_banco_id = Null;
$x_nombre = Null;
$x_cuenta = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Get action
$sAction = @$_POST["a_add"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sKey = @$_GET["key"];
	$sKey = (get_magic_quotes_gpc()) ? stripslashes($sKey) : $sKey;
	if ($sKey <> "") {
		$sAction = "C"; // Copy record
	}
	else
	{
		$sAction = "I"; // Display blank record
	}
}
else
{

	// Get fields from form
	$x_banco_id = @$_POST["x_banco_id"];
	$x_nombre = @$_POST["x_nombre"];
	$x_cuenta = @$_POST["x_cuenta"];
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($sKey,$conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No Record Found for Key = " . $sKey;
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_bancolist.php");
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "Los datos han sido registrados.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_bancolist.php");
			exit();
		}
		break;
}
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<script type="text/javascript">
<!--
function EW_checkMyForm(EW_this) {
if (EW_this.x_nombre && !EW_hasValue(EW_this.x_nombre, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre, "TEXT", "EL nombre es requerido."))
		return false;
}
if (EW_this.x_cuenta && !EW_hasValue(EW_this.x_cuenta, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_cuenta, "TEXT", "La cuenta es requerida."))
		return false;
}
return true;
}

//-->
</script>
<p><span class="phpmaker">CATALOGO DE BANCOS<br>
  <br>
    <a href="php_bancolist.php">Regresar al listado</a></span></p>
<form name="bancoadd" id="bancoadd" action="php_bancoadd.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_add" value="A">
<table class="ewTable">
	<tr>
		<td class="ewTableHeaderThin"><span>Nombre</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_nombre" id="x_nombre" size="60" maxlength="250" value="<?php echo htmlspecialchars(@$x_nombre) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Cuenta</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_cuenta" id="x_cuenta" size="30" maxlength="50" value="<?php echo htmlspecialchars(@$x_cuenta) ?>">
</span></td>
	</tr>
</table>
<p>
<input type="submit" name="Action" value="Agregar">
</form>
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
	$sSql = "SELECT * FROM `banco`";
	$sSql .= " WHERE `banco_id` = " . $sKeyWrk;
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
		$GLOBALS["x_banco_id"] = $row["banco_id"];
		$GLOBALS["x_nombre"] = $row["nombre"];
		$GLOBALS["x_cuenta"] = $row["cuenta"];
	}
	phpmkr_free_result($rs);
	return $LoadData;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function AddData
// - Add Data
// - Variables used: field variables

function AddData($conn)
{

	// Field nombre
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre"]) : $GLOBALS["x_nombre"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre`"] = $theValue;

	// Field cuenta
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_cuenta"]) : $GLOBALS["x_cuenta"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`cuenta`"] = $theValue;

	// insert into database
	$strsql = "INSERT INTO `banco` (";
	$strsql .= implode(",", array_keys($fieldList));
	$strsql .= ") VALUES (";
	$strsql .= implode(",", array_values($fieldList));
	$strsql .= ")";
	phpmkr_query($strsql, $conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $strsql);
	return true;
}
?>
