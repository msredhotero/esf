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
$x_credito_tipo_id = Null; 
$ox_credito_tipo_id = Null;
$x_descripcion = Null; 
$ox_descripcion = Null;
$x_monto_minimo = Null; 
$ox_monto_minimo = Null;
$x_monto_maximo = Null; 
$ox_monto_maximo = Null;
$x_tasa = Null; 
$ox_tasa = Null;
$x_plazo = Null; 
$ox_plazo = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Load key from QueryString
$x_credito_tipo_id = @$_GET["credito_tipo_id"];

//if (!empty($x_credito_tipo_id )) $x_credito_tipo_id  = (get_magic_quotes_gpc()) ? stripslashes($x_credito_tipo_id ) : $x_credito_tipo_id ;
// Get action

$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I";	// Display with input box
} else {

	// Get fields from form
	$x_credito_tipo_id = @$_POST["x_credito_tipo_id"];
	$x_descripcion = @$_POST["x_descripcion"];
	$x_monto_minimo = @$_POST["x_monto_minimo"];
	$x_monto_maximo = @$_POST["x_monto_maximo"];
	$x_tasa = @$_POST["x_tasa"];
	$x_plazo = @$_POST["x_plazo"];
}

// Check if valid key
if (($x_credito_tipo_id == "") || (is_null($x_credito_tipo_id))) {
	ob_end_clean();
	header("Location: php_credito_tipolist.php");
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
			header("Location: php_credito_tipolist.php");
			exit();
		}
		break;
	case "U": // Update
		if (EditData($conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "Update Record Successful";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_credito_tipolist.php");
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
	if (!EW_onError(EW_this, EW_this.x_descripcion, "TEXT", "La descripción es requerida."))
		return false;
}
if (EW_this.x_monto_minimo && !EW_hasValue(EW_this.x_monto_minimo, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_monto_minimo, "TEXT", "EL importe del monto mínimo es requerido."))
		return false;
}
if (EW_this.x_monto_minimo && !EW_checknumber(EW_this.x_monto_minimo.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_minimo, "TEXT", "EL importe del monto mínimo es requerido."))
		return false; 
}
if (EW_this.x_monto_maximo && !EW_hasValue(EW_this.x_monto_maximo, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_monto_maximo, "TEXT", "El importe del monto máximo es requerido."))
		return false;
}
if (EW_this.x_monto_maximo && !EW_checknumber(EW_this.x_monto_maximo.value)) {
	if (!EW_onError(EW_this, EW_this.x_monto_maximo, "TEXT", "El importe del monto máximo es requerido."))
		return false; 
}
if (EW_this.x_tasa && !EW_hasValue(EW_this.x_tasa, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tasa, "TEXT", "La tasa es requerida."))
		return false;
}
if (EW_this.x_tasa && !EW_checknumber(EW_this.x_tasa.value)) {
	if (!EW_onError(EW_this, EW_this.x_tasa, "TEXT", "La tasa es requerida."))
		return false; 
}
if (EW_this.x_plazo && !EW_hasValue(EW_this.x_plazo, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_plazo, "TEXT", "El plazo (meses) es requerido."))
		return false;
}
if (EW_this.x_plazo && !EW_checkinteger(EW_this.x_plazo.value)) {
	if (!EW_onError(EW_this, EW_this.x_plazo, "TEXT", "El plazo (meses) es requerido."))
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
<p><span class="phpmaker">Edit TABLE: PORTAFOLIO<br><br><a href="php_credito_tipolist.php">Back to List</a></span></p>
<form name="credito_tipoedit" id="credito_tipoedit" action="php_credito_tipoedit.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<table class="ewTable">
	<tr>
		<td class="ewTableHeader"><span>No</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_credito_tipo_id; ?><input type="hidden" id="x_credito_tipo_id" name="x_credito_tipo_id" value="<?php echo htmlspecialchars(@$x_credito_tipo_id); ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Descripción</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_descripcion" id="x_descripcion" size="30" maxlength="100" value="<?php echo htmlspecialchars(@$x_descripcion) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Monto mínimo</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_monto_minimo" id="x_monto_minimo" size="30" value="<?php echo htmlspecialchars(@$x_monto_minimo) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Monto máximo</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_monto_maximo" id="x_monto_maximo" size="30" value="<?php echo htmlspecialchars(@$x_monto_maximo) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Tasa</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_tasa" id="x_tasa" size="30" value="<?php echo htmlspecialchars(@$x_tasa) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Plazo (meses)</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_plazo" id="x_plazo" size="30" value="<?php echo htmlspecialchars(@$x_plazo) ?>">
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
	global $x_credito_tipo_id;
	$sSql = "SELECT * FROM `credito_tipo`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_credito_tipo_id) : $x_credito_tipo_id;
	$sWhere .= "(`credito_tipo_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_credito_tipo_id"] = $row["credito_tipo_id"];
		$GLOBALS["x_descripcion"] = $row["descripcion"];
		$GLOBALS["x_monto_minimo"] = $row["monto_minimo"];
		$GLOBALS["x_monto_maximo"] = $row["monto_maximo"];
		$GLOBALS["x_tasa"] = $row["tasa"];
		$GLOBALS["x_plazo"] = $row["plazo"];
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
	global $x_credito_tipo_id;
	$sSql = "SELECT * FROM `credito_tipo`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_credito_tipo_id) : $x_credito_tipo_id;	
	$sWhere .= "(`credito_tipo_id` = " . addslashes($sTmp) . ")";
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
		$theValue = ($GLOBALS["x_monto_minimo"] != "") ? " '" . doubleval($GLOBALS["x_monto_minimo"]) . "'" : "NULL";
		$fieldList["`monto_minimo`"] = $theValue;
		$theValue = ($GLOBALS["x_monto_maximo"] != "") ? " '" . doubleval($GLOBALS["x_monto_maximo"]) . "'" : "NULL";
		$fieldList["`monto_maximo`"] = $theValue;
		$theValue = ($GLOBALS["x_tasa"] != "") ? " '" . doubleval($GLOBALS["x_tasa"]) . "'" : "NULL";
		$fieldList["`tasa`"] = $theValue;
		$theValue = ($GLOBALS["x_plazo"] != "") ? intval($GLOBALS["x_plazo"]) : "NULL";
		$fieldList["`plazo`"] = $theValue;

		// update
		$sSql = "UPDATE `credito_tipo` SET ";
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
