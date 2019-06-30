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
$x_direccion_id = Null; 
$ox_direccion_id = Null;
$x_cliente_id = Null; 
$ox_cliente_id = Null;
$x_aval_id = Null; 
$ox_aval_id = Null;
$x_promotor_id = Null; 
$ox_promotor_id = Null;
$x_direccion_tipo_id = Null; 
$ox_direccion_tipo_id = Null;
$x_calle = Null; 
$ox_calle = Null;
$x_colonia = Null; 
$ox_colonia = Null;
$x_delegacion_id = Null; 
$ox_delegacion_id = Null;
$x_otra_delegacion = Null; 
$ox_otra_delegacion = Null;
$x_entidad = Null; 
$ox_entidad = Null;
$x_codigo_postal = Null; 
$ox_codigo_postal = Null;
$x_ubicacion = Null; 
$ox_ubicacion = Null;
$x_antiguedad = Null; 
$ox_antiguedad = Null;
$x_vivienda_tipo_id = Null; 
$ox_vivienda_tipo_id = Null;
$x_otro_tipo_vivienda = Null; 
$ox_otro_tipo_vivienda = Null;
$x_telefono = Null; 
$ox_telefono = Null;
$x_telefono_secundario = Null; 
$ox_telefono_secundario = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Load key from QueryString
$x_direccion_id = @$_GET["direccion_id"];

//if (!empty($x_direccion_id )) $x_direccion_id  = (get_magic_quotes_gpc()) ? stripslashes($x_direccion_id ) : $x_direccion_id ;
// Get action

$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I";	// Display with input box
} else {

	// Get fields from form
	$x_direccion_id = @$_POST["x_direccion_id"];
	$x_cliente_id = @$_POST["x_cliente_id"];
	$x_aval_id = @$_POST["x_aval_id"];
	$x_promotor_id = @$_POST["x_promotor_id"];
	$x_direccion_tipo_id = @$_POST["x_direccion_tipo_id"];
	$x_calle = @$_POST["x_calle"];
	$x_colonia = @$_POST["x_colonia"];
	$x_delegacion_id = @$_POST["x_delegacion_id"];
	$x_otra_delegacion = @$_POST["x_otra_delegacion"];
	$x_entidad = @$_POST["x_entidad"];
	$x_codigo_postal = @$_POST["x_codigo_postal"];
	$x_ubicacion = @$_POST["x_ubicacion"];
	$x_antiguedad = @$_POST["x_antiguedad"];
	$x_vivienda_tipo_id = @$_POST["x_vivienda_tipo_id"];
	$x_otro_tipo_vivienda = @$_POST["x_otro_tipo_vivienda"];
	$x_telefono = @$_POST["x_telefono"];
	$x_telefono_secundario = @$_POST["x_telefono_secundario"];
}

// Check if valid key
if (($x_direccion_id == "") || (is_null($x_direccion_id))) {
	ob_end_clean();
	header("Location: php_direccionlist.php");
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
			header("Location: php_direccionlist.php");
			exit();
		}
		break;
	case "U": // Update
		if (EditData($conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "Update Record Successful";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_direccionlist.php");
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
if (EW_this.x_cliente_id && !EW_hasValue(EW_this.x_cliente_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_cliente_id, "SELECT", "El cliente es requerido."))
		return false;
}
if (EW_this.x_aval_id && !EW_hasValue(EW_this.x_aval_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_aval_id, "SELECT", "El aval es requerido."))
		return false;
}
if (EW_this.x_promotor_id && !EW_hasValue(EW_this.x_promotor_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_promotor_id, "SELECT", "El promotor es requerido."))
		return false;
}
if (EW_this.x_direccion_tipo_id && !EW_hasValue(EW_this.x_direccion_tipo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_direccion_tipo_id, "SELECT", "El tipo de dirección es requerido."))
		return false;
}
if (EW_this.x_calle && !EW_hasValue(EW_this.x_calle, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_calle, "TEXT", "La calle es requerida."))
		return false;
}
if (EW_this.x_colonia && !EW_hasValue(EW_this.x_colonia, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_colonia, "TEXT", "La colonia es requerida."))
		return false;
}
if (EW_this.x_delegacion_id && !EW_hasValue(EW_this.x_delegacion_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_delegacion_id, "SELECT", "La delegación es requerida."))
		return false;
}
if (EW_this.x_entidad && !EW_hasValue(EW_this.x_entidad, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_entidad, "TEXT", "La entidad es requerida."))
		return false;
}
if (EW_this.x_codigo_postal && !EW_hasValue(EW_this.x_codigo_postal, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal, "TEXT", "El Código Postal es requerido."))
		return false;
}
if (EW_this.x_ubicacion && !EW_hasValue(EW_this.x_ubicacion, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ubicacion, "TEXT", "La Ubicación es requerida."))
		return false;
}
if (EW_this.x_antiguedad && !EW_hasValue(EW_this.x_antiguedad, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_antiguedad, "TEXT", "La Antiguedad es requerida."))
		return false;
}
if (EW_this.x_antiguedad && !EW_checkinteger(EW_this.x_antiguedad.value)) {
	if (!EW_onError(EW_this, EW_this.x_antiguedad, "TEXT", "La Antiguedad es requerida."))
		return false; 
}
if (EW_this.x_vivienda_tipo_id && !EW_hasValue(EW_this.x_vivienda_tipo_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_vivienda_tipo_id, "SELECT", "El tipo de vivienda es requerido."))
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
<p><span class="phpmaker">Edit TABLE: DIRECCIONES<br><br><a href="php_direccionlist.php">Back to List</a></span></p>
<form name="direccionedit" id="direccionedit" action="php_direccionedit.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<table class="ewTable">
	<tr>
		<td class="ewTableHeader"><span>No</span></td>
		<td class="ewTableAltRow"><span>
<?php echo $x_direccion_id; ?><input type="hidden" id="x_direccion_id" name="x_direccion_id" value="<?php echo htmlspecialchars(@$x_direccion_id); ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Cliente</span></td>
		<td class="ewTableAltRow"><span>
<?php
$x_cliente_idList = "<select name=\"x_cliente_id\">";
$x_cliente_idList .= "<option value=''>Please Select</option>";
$sSqlWrk = "SELECT `cliente_id`, `nombre_completo` FROM `cliente`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_cliente_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["cliente_id"] == @$x_cliente_id) {
			$x_cliente_idList .= "' selected";
		}
		$x_cliente_idList .= ">" . $datawrk["nombre_completo"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_cliente_idList .= "</select>";
echo $x_cliente_idList;
?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Aval</span></td>
		<td class="ewTableAltRow"><span>
<?php
$x_aval_idList = "<select name=\"x_aval_id\">";
$x_aval_idList .= "<option value=''>Please Select</option>";
$sSqlWrk = "SELECT `aval_id`, `nombre_completo` FROM `aval`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_aval_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["aval_id"] == @$x_aval_id) {
			$x_aval_idList .= "' selected";
		}
		$x_aval_idList .= ">" . $datawrk["nombre_completo"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_aval_idList .= "</select>";
echo $x_aval_idList;
?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Promotor</span></td>
		<td class="ewTableAltRow"><span>
<?php
$x_promotor_idList = "<select name=\"x_promotor_id\">";
$x_promotor_idList .= "<option value=''>Please Select</option>";
$sSqlWrk = "SELECT `promotor_id`, `nombre_completo` FROM `promotor`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_promotor_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["promotor_id"] == @$x_promotor_id) {
			$x_promotor_idList .= "' selected";
		}
		$x_promotor_idList .= ">" . $datawrk["nombre_completo"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_promotor_idList .= "</select>";
echo $x_promotor_idList;
?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Tipo de dirección</span></td>
		<td class="ewTableAltRow"><span>
<?php
$x_direccion_tipo_idList = "<select name=\"x_direccion_tipo_id\">";
$x_direccion_tipo_idList .= "<option value=''>Please Select</option>";
$sSqlWrk = "SELECT `direccion_tipo_id`, `descripcion` FROM `direccion_tipo`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_direccion_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["direccion_tipo_id"] == @$x_direccion_tipo_id) {
			$x_direccion_tipo_idList .= "' selected";
		}
		$x_direccion_tipo_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_direccion_tipo_idList .= "</select>";
echo $x_direccion_tipo_idList;
?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Calle</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_calle" id="x_calle" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_calle) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>colonia</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_colonia" id="x_colonia" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_colonia) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Delegación</span></td>
		<td class="ewTableAltRow"><span>
<?php
$x_delegacion_idList = "<select name=\"x_delegacion_id\">";
$x_delegacion_idList .= "<option value=''>Please Select</option>";
$sSqlWrk = "SELECT `delegacion_id`, `descripcion` FROM `delegacion`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["delegacion_id"] == @$x_delegacion_id) {
			$x_delegacion_idList .= "' selected";
		}
		$x_delegacion_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_delegacion_idList .= "</select>";
echo $x_delegacion_idList;
?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Otra delegación</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_otra_delegacion" id="x_otra_delegacion" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_otra_delegacion) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Entidad</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_entidad" id="x_entidad" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_entidad) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Codigo postal</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_codigo_postal" id="x_codigo_postal" size="30" maxlength="10" value="<?php echo htmlspecialchars(@$x_codigo_postal) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Ubicación</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_ubicacion" id="x_ubicacion" size="30" maxlength="250" value="<?php echo htmlspecialchars(@$x_ubicacion) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Antiguedad</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_antiguedad" id="x_antiguedad" size="30" value="<?php echo htmlspecialchars(@$x_antiguedad) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Tipo de vivienda</span></td>
		<td class="ewTableAltRow"><span>
<?php
$x_vivienda_tipo_idList = "<select name=\"x_vivienda_tipo_id\">";
$x_vivienda_tipo_idList .= "<option value=''>Please Select</option>";
$sSqlWrk = "SELECT `vivienda_tipo_id`, `descripcion` FROM `vivienda_tipo`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_vivienda_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["vivienda_tipo_id"] == @$x_vivienda_tipo_id) {
			$x_vivienda_tipo_idList .= "' selected";
		}
		$x_vivienda_tipo_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_vivienda_tipo_idList .= "</select>";
echo $x_vivienda_tipo_idList;
?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Otro tipo de vivienda</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_otro_tipo_vivienda" id="x_otro_tipo_vivienda" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_otro_tipo_vivienda) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Teléfono</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_telefono" id="x_telefono" size="30" maxlength="50" value="<?php echo htmlspecialchars(@$x_telefono) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Teléfono secundario</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_telefono_secundario" id="x_telefono_secundario" size="30" maxlength="50" value="<?php echo htmlspecialchars(@$x_telefono_secundario) ?>">
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
	global $x_direccion_id;
	$sSql = "SELECT * FROM `direccion`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_direccion_id) : $x_direccion_id;
	$sWhere .= "(`direccion_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_direccion_id"] = $row["direccion_id"];
		$GLOBALS["x_cliente_id"] = $row["cliente_id"];
		$GLOBALS["x_aval_id"] = $row["aval_id"];
		$GLOBALS["x_promotor_id"] = $row["promotor_id"];
		$GLOBALS["x_direccion_tipo_id"] = $row["direccion_tipo_id"];
		$GLOBALS["x_calle"] = $row["calle"];
		$GLOBALS["x_colonia"] = $row["colonia"];
		$GLOBALS["x_delegacion_id"] = $row["delegacion_id"];
		$GLOBALS["x_otra_delegacion"] = $row["otra_delegacion"];
		$GLOBALS["x_entidad"] = $row["entidad"];
		$GLOBALS["x_codigo_postal"] = $row["codigo_postal"];
		$GLOBALS["x_ubicacion"] = $row["ubicacion"];
		$GLOBALS["x_antiguedad"] = $row["antiguedad"];
		$GLOBALS["x_vivienda_tipo_id"] = $row["vivienda_tipo_id"];
		$GLOBALS["x_otro_tipo_vivienda"] = $row["otro_tipo_vivienda"];
		$GLOBALS["x_telefono"] = $row["telefono"];
		$GLOBALS["x_telefono_secundario"] = $row["telefono_secundario"];
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
	global $x_direccion_id;
	$sSql = "SELECT * FROM `direccion`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_direccion_id) : $x_direccion_id;	
	$sWhere .= "(`direccion_id` = " . addslashes($sTmp) . ")";
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
		$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
		$fieldList["`cliente_id`"] = $theValue;
		$theValue = ($GLOBALS["x_aval_id"] != "") ? intval($GLOBALS["x_aval_id"]) : "NULL";
		$fieldList["`aval_id`"] = $theValue;
		$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
		$fieldList["`promotor_id`"] = $theValue;
		$theValue = ($GLOBALS["x_direccion_tipo_id"] != "") ? intval($GLOBALS["x_direccion_tipo_id"]) : "NULL";
		$fieldList["`direccion_tipo_id`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle"]) : $GLOBALS["x_calle"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`calle`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia"]) : $GLOBALS["x_colonia"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`colonia`"] = $theValue;
		$theValue = ($GLOBALS["x_delegacion_id"] != "") ? intval($GLOBALS["x_delegacion_id"]) : "NULL";
		$fieldList["`delegacion_id`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otra_delegacion"]) : $GLOBALS["x_otra_delegacion"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`otra_delegacion`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_entidad"]) : $GLOBALS["x_entidad"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`entidad`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_codigo_postal"]) : $GLOBALS["x_codigo_postal"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`codigo_postal`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion"]) : $GLOBALS["x_ubicacion"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ubicacion`"] = $theValue;
		$theValue = ($GLOBALS["x_antiguedad"] != "") ? intval($GLOBALS["x_antiguedad"]) : "NULL";
		$fieldList["`antiguedad`"] = $theValue;
		$theValue = ($GLOBALS["x_vivienda_tipo_id"] != "") ? intval($GLOBALS["x_vivienda_tipo_id"]) : "NULL";
		$fieldList["`vivienda_tipo_id`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_otro_tipo_vivienda"]) : $GLOBALS["x_otro_tipo_vivienda"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`otro_tipo_vivienda`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono"]) : $GLOBALS["x_telefono"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_secundario"]) : $GLOBALS["x_telefono_secundario"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono_secundario`"] = $theValue;

		// update
		$sSql = "UPDATE `direccion` SET ";
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
