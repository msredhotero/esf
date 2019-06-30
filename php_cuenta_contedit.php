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

// Load key from QueryString
$x_cuenta_cont_id = @$_GET["cuenta_cont_id"];

//if (!empty($x_cuenta_cont_id )) $x_cuenta_cont_id  = (get_magic_quotes_gpc()) ? stripslashes($x_cuenta_cont_id ) : $x_cuenta_cont_id ;
// Get action

$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I";	// Display with input box
} else {

	// Get fields from form
	$x_cuenta_cont_id = @$_POST["x_cuenta_cont_id"];
	$x_cuenta_cont_tipo_id = @$_POST["x_cuenta_cont_tipo_id"];
	$x_cuenta = @$_POST["x_cuenta"];
	$x_sub_cuenta = @$_POST["x_sub_cuenta"];
	$x_ssub_cuenta = @$_POST["x_ssub_cuenta"];
	$x_sssub_cuenta = @$_POST["x_sssub_cuenta"];
	$x_descricpion = @$_POST["x_descricpion"];
}

// Check if valid key
if (($x_cuenta_cont_id == "") || (is_null($x_cuenta_cont_id))) {
	ob_end_clean();
	header("Location: php_cuenta_contlist.php");
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
			header("Location: php_cuenta_contlist.php");
			exit();
		}
		break;
	case "U": // Update
		if (EditData($conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "Update Record Successful";
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
<p><span class="phpmaker">Edit TABLE: Contabilidad Catalogo de Cuentas<br><br><a href="php_cuenta_contlist.php">Back to List</a></span></p>
<form name="cuenta_contedit" id="cuenta_contedit" action="php_cuenta_contedit.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<table class="ewTable">
	<tr>
		<td class="ewTableHeader"><span>No</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_cuenta_cont_id; ?><input type="hidden" id="x_cuenta_cont_id" name="x_cuenta_cont_id" value="<?php echo htmlspecialchars(@$x_cuenta_cont_id); ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Tipo</span></td>
		<td class="ewTableAltRow"><span>
<?php
$x_cuenta_cont_tipo_idList = "<select name=\"x_cuenta_cont_tipo_id\">";
$x_cuenta_cont_tipo_idList .= "<option value=''>Please Select</option>";
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
		<td class="ewTableHeader"><span>Cuenta</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_cuenta" id="x_cuenta" size="5" maxlength="5" value="<?php echo htmlspecialchars(@$x_cuenta) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>S-cuenta</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_sub_cuenta" id="x_sub_cuenta" size="30" maxlength="10" value="<?php echo htmlspecialchars(@$x_sub_cuenta) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>SS-cuenta</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_ssub_cuenta" id="x_ssub_cuenta" size="30" maxlength="10" value="<?php echo htmlspecialchars(@$x_ssub_cuenta) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>SSS-cuenta</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_sssub_cuenta" id="x_sssub_cuenta" size="30" maxlength="10" value="<?php echo htmlspecialchars(@$x_sssub_cuenta) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Descricpion</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_descricpion" id="x_descricpion" size="30" maxlength="250" value="<?php echo htmlspecialchars(@$x_descricpion) ?>">
</span></td>
	</tr>
</table>
<p>
<input type="submit" name="Action" value="EDIT">
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
// Function EditData
// - Edit Data based on Key Value sKey
// - Variables used: field variables

function EditData($conn)
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
		$bEditData = false; // Update Failed
	}else{
		$theValue = ($GLOBALS["x_cuenta_cont_tipo_id"] != "") ? intval($GLOBALS["x_cuenta_cont_tipo_id"]) : "NULL";
		$fieldList["`cuenta_cont_tipo_id`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_cuenta"]) : $GLOBALS["x_cuenta"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`cuenta`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_sub_cuenta"]) : $GLOBALS["x_sub_cuenta"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`sub_cuenta`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ssub_cuenta"]) : $GLOBALS["x_ssub_cuenta"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ssub_cuenta`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_sssub_cuenta"]) : $GLOBALS["x_sssub_cuenta"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`sssub_cuenta`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_descricpion"]) : $GLOBALS["x_descricpion"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`descricpion`"] = $theValue;

		// update
		$sSql = "UPDATE `cuenta_cont` SET ";
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
