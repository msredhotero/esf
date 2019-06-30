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
$x_forma_pago_id = Null; 
$ox_forma_pago_id = Null;
$x_descripcion = Null; 
$ox_descripcion = Null;
$x_valor = Null; 
$ox_valor = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Load key from QueryString
$x_forma_pago_id = @$_GET["forma_pago_id"];

//if (!empty($x_forma_pago_id )) $x_forma_pago_id  = (get_magic_quotes_gpc()) ? stripslashes($x_forma_pago_id ) : $x_forma_pago_id ;
// Get action

$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I";	// Display with input box
} else {

	// Get fields from form
	$x_forma_pago_id = @$_POST["x_forma_pago_id"];
	$x_descripcion = @$_POST["x_descripcion"];
	$x_valor = @$_POST["x_valor"];
}

// Check if valid key
if (($x_forma_pago_id == "") || (is_null($x_forma_pago_id))) {
	ob_end_clean();
	header("Location: php_forma_pagolist.php");
	exit();
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No records found";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_forma_pagolist.php");
			exit();
		}
		break;
	case "U": // Update
		if (EditData($conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "Los datos han sido actualizados";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_forma_pagolist.php");
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
if (EW_this.x_descripcion && !EW_hasValue(EW_this.x_descripcion, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_descripcion, "TEXT", "La descripcion es requerida."))
		return false;
}
if (EW_this.x_valor && !EW_hasValue(EW_this.x_valor, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_valor, "TEXT", "El valor en dias es requerido."))
		return false;
}
if (EW_this.x_valor && !EW_checkinteger(EW_this.x_valor.value)) {
	if (!EW_onError(EW_this, EW_this.x_valor, "TEXT", "El valor en dias es incorrecto."))
		return false; 
}
return true;
}

//-->
</script>
<script type="text/javascript">
<!--
var EW_HTMLArea;

//-->
</script>
<p><span class="phpmaker">Formas de pago<br>
    <br>
    <a href="php_forma_pagolist.php">Regresar a la lista</a></span></p>
<form name="forma_pagoedit" id="forma_pagoedit" action="php_forma_pagoedit.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<table class="ewTable">
	<tr>
		<td width="154" class="ewTableHeader"><span>No</span></td>
		<td width="834" class="ewTableAltRow"><span>
<?php echo $x_forma_pago_id; ?>
<input type="hidden" id="x_forma_pago_id" name="x_forma_pago_id" value="<?php echo htmlspecialchars(@$x_forma_pago_id); ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Descripcion</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_descripcion" id="x_descripcion" size="40" maxlength="50" value="<?php echo htmlspecialchars(@$x_descripcion) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Num. Dias</span></td>
		<td class="ewTableAltRow"><span>
<input name="x_valor" type="text" id="x_valor" value="<?php echo htmlspecialchars(@$x_valor) ?>" size="5" maxlength="5">
</span></td>
	</tr>
</table>
<p>
<input type="submit" name="Action" value="EDITAR">
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

function LoadData($conn)
{
	global $x_forma_pago_id;
	$sSql = "SELECT * FROM `forma_pago`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_forma_pago_id) : $x_forma_pago_id;
	$sWhere .= "(`forma_pago_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_forma_pago_id"] = $row["forma_pago_id"];
		$GLOBALS["x_descripcion"] = $row["descripcion"];
		$GLOBALS["x_valor"] = $row["valor"];
	}
	phpmkr_free_result($rs);
	return $bLoadData;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function EditData
// - Edit Data based on Key Value sKey
// - Variables used: field variables

function EditData($conn)
{
	global $x_forma_pago_id;
	$sSql = "SELECT * FROM `forma_pago`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_forma_pago_id) : $x_forma_pago_id;	
	$sWhere .= "(`forma_pago_id` = " . addslashes($sTmp) . ")";
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
		$bEditData = false; // Update Failed
	}else{
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_descripcion"]) : $GLOBALS["x_descripcion"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`descripcion`"] = $theValue;
		$theValue = ($GLOBALS["x_valor"] != "") ? intval($GLOBALS["x_valor"]) : "NULL";
		$fieldList["`valor`"] = $theValue;

		// update
		$sSql = "UPDATE `forma_pago` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE " . $sWhere;
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$bEditData = true; // Update Successful
	}
	return $bEditData;
}
?>
