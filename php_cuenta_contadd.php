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

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
$x_cuenta_cont_id = @$_GET["cuenta_cont_id"];
if (empty($x_cuenta_cont_id)) {
	$bCopy = false;
}

// Get action
$sAction = @$_POST["a_add"];
if (($sAction == "") || ((is_null($sAction)))) {
	if ($bCopy) {
		$sAction = "C"; // Copy record
	}else{
		$sAction = "I"; // Display blank record
	}
}else{

	// Get fields from form
	$x_cuenta_cont_id = @$_POST["x_cuenta_cont_id"];
	$x_cuenta_cont_tipo_id = @$_POST["x_cuenta_cont_tipo_id"];
	$x_cuenta = @$_POST["x_cuenta"];
	$x_sub_cuenta = @$_POST["x_sub_cuenta"];
	$x_ssub_cuenta = @$_POST["x_ssub_cuenta"];
	$x_sssub_cuenta = @$_POST["x_sssub_cuenta"];
	$x_descricpion = @$_POST["x_descricpion"];
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron los datos";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_cuenta_contlist.php");
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "Los datos han sido registrados";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_cuenta_contlist.php");
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
if (EW_this.x_cuenta_cont_tipo_id && !EW_hasValue(EW_this.x_cuenta_cont_tipo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_cuenta_cont_tipo_id, "SELECT", "El tipo de cuenta es requerido."))
		return false;
}
if (EW_this.x_cuenta && !EW_hasValue(EW_this.x_cuenta, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_cuenta, "TEXT", "La cuenta es requerida."))
		return false;
}
if (EW_this.x_descricpion && !EW_hasValue(EW_this.x_descricpion, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_descricpion, "TEXT", "La descripcion es requerida."))
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
<p><span class="phpmaker">Catalogo de Cuentas<br>
  <br>
    <a href="php_cuenta_contlist.php">Regresar a la lista</a></span></p>
<form name="cuenta_contadd" id="cuenta_contadd" action="php_cuenta_contadd.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_add" value="A">
<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION["ewmsg"] ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>
<table class="ewTable">
	<tr>
		<td width="101" class="ewTableHeaderThin"><span>Tipo</span></td>
		<td width="887" class="ewTableAltRow"><span>
<?php if (!(!is_null($x_cuenta_cont_tipo_id)) || ($x_cuenta_cont_tipo_id == "")) { $x_cuenta_cont_tipo_id = 0;} // Set default value ?>
<?php
$x_cuenta_cont_tipo_idList = "<select name=\"x_cuenta_cont_tipo_id\">";
$x_cuenta_cont_tipo_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT `cuenta_cont_tipo_id`, `desc_corta` FROM `cuenta_cont_tipo`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_cuenta_cont_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["cuenta_cont_tipo_id"] == @$x_cuenta_cont_tipo_id) {
			$x_cuenta_cont_tipo_idList .= "' selected";
		}
		$x_cuenta_cont_tipo_idList .= ">" . $datawrk["desc_corta"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_cuenta_cont_tipo_idList .= "</select>";
echo $x_cuenta_cont_tipo_idList;
?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Cuenta</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_cuenta" id="x_cuenta" size="5" maxlength="5" value="<?php echo htmlspecialchars(@$x_cuenta) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>S-cuenta</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_sub_cuenta" id="x_sub_cuenta" size="30" maxlength="10" value="<?php echo htmlspecialchars(@$x_sub_cuenta) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>SS-cuenta</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_ssub_cuenta" id="x_ssub_cuenta" size="30" maxlength="10" value="<?php echo htmlspecialchars(@$x_ssub_cuenta) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>SSS-cuenta</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_sssub_cuenta" id="x_sssub_cuenta" size="30" maxlength="10" value="<?php echo htmlspecialchars(@$x_sssub_cuenta) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Descricpion</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_descricpion" id="x_descricpion" size="30" maxlength="250" value="<?php echo htmlspecialchars(@$x_descricpion) ?>">
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
<?php

//-------------------------------------------------------------------------------
// Function AddData
// - Add Data
// - Variables used: field variables

function AddData($conn)
{
	global $x_cuenta_cont_id;
	$sSql = "SELECT * FROM `cuenta_cont`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";

	// Check for duplicate key
	$bCheckKey = true;
	$sWhereChk = $sWhere;
	if ((@$x_cuenta_cont_id == "") || (is_null($x_cuenta_cont_id))) {
		$bCheckKey = false;
	} else {
		if ($sWhereChk <> "") { $sWhereChk .= " AND "; }
		$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_cuenta_cont_id) : $x_cuenta_cont_id;			
		$sWhereChk .= "(`cuenta_cont_id` = " . addslashes($sTmp) . ")";
	}
	if ($bCheckKey) {
		$sSqlChk = $sSql . " WHERE " . $sWhereChk;
		$rsChk = phpmkr_query($sSqlChk, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSqlChk);
		if (phpmkr_num_rows($rsChk) > 0) {
			$_SESSION["ewmsg"] = "Duplicate value for primary key";
			phpmkr_free_result($rsChk);
			return false;
		}
		phpmkr_free_result($rsChk);
	}

	// Field cuenta_cont_tipo_id
	$theValue = ($GLOBALS["x_cuenta_cont_tipo_id"] != "") ? intval($GLOBALS["x_cuenta_cont_tipo_id"]) : "NULL";
	$fieldList["`cuenta_cont_tipo_id`"] = $theValue;

	// Field cuenta
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_cuenta"]) : $GLOBALS["x_cuenta"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`cuenta`"] = $theValue;

	// Field sub_cuenta
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_sub_cuenta"]) : $GLOBALS["x_sub_cuenta"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`sub_cuenta`"] = $theValue;

	// Field ssub_cuenta
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ssub_cuenta"]) : $GLOBALS["x_ssub_cuenta"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ssub_cuenta`"] = $theValue;

	// Field sssub_cuenta
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_sssub_cuenta"]) : $GLOBALS["x_sssub_cuenta"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`sssub_cuenta`"] = $theValue;

	// Field descricpion
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_descricpion"]) : $GLOBALS["x_descricpion"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`descricpion`"] = $theValue;

	// insert into database
	$sSql = "INSERT INTO `cuenta_cont` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	return true;
}
?>
