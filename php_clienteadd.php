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
$x_cliente_id = Null; 
$ox_cliente_id = Null;
$x_solicitud_id = Null; 
$ox_solicitud_id = Null;
$x_usuario_id = Null; 
$ox_usuario_id = Null;
$x_nombre_completo = Null; 
$ox_nombre_completo = Null;
$x_tipo_negocio = Null; 
$ox_tipo_negocio = Null;
$x_edad = Null; 
$ox_edad = Null;
$x_sexo = Null; 
$ox_sexo = Null;
$x_estado_civil_id = Null; 
$ox_estado_civil_id = Null;
$x_numero_hijos = Null; 
$ox_numero_hijos = Null;
$x_nombre_conyuge = Null; 
$ox_nombre_conyuge = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
$x_cliente_id = @$_GET["cliente_id"];
if (empty($x_cliente_id)) {
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
	$x_cliente_id = @$_POST["x_cliente_id"];
	$x_solicitud_id = @$_POST["x_solicitud_id"];
	$x_usuario_id = @$_POST["x_usuario_id"];
	$x_nombre_completo = @$_POST["x_nombre_completo"];
	$x_tipo_negocio = @$_POST["x_tipo_negocio"];
	$x_edad = @$_POST["x_edad"];
	$x_sexo = @$_POST["x_sexo"];
	$x_estado_civil_id = @$_POST["x_estado_civil_id"];
	$x_numero_hijos = @$_POST["x_numero_hijos"];
	$x_nombre_conyuge = @$_POST["x_nombre_conyuge"];
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No records found";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_clientelist.php");
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "Add New Record Successful";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_clientelist.php");
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
if (EW_this.x_solicitud_id && !EW_hasValue(EW_this.x_solicitud_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_solicitud_id, "SELECT", "La solciitud es requerida."))
		return false;
}
if (EW_this.x_usuario_id && !EW_hasValue(EW_this.x_usuario_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_usuario_id, "SELECT", "El usuario es requerido."))
		return false;
}
if (EW_this.x_nombre_completo && !EW_hasValue(EW_this.x_nombre_completo, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre_completo, "TEXT", "El nombre es requerido."))
		return false;
}
if (EW_this.x_tipo_negocio && !EW_hasValue(EW_this.x_tipo_negocio, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_tipo_negocio, "TEXT", "El tipo de negocio es requerido."))
		return false;
}
if (EW_this.x_edad && !EW_hasValue(EW_this.x_edad, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_edad, "TEXT", "La edad es requerida."))
		return false;
}
if (EW_this.x_edad && !EW_checkinteger(EW_this.x_edad.value)) {
	if (!EW_onError(EW_this, EW_this.x_edad, "TEXT", "La edad es requerida."))
		return false; 
}
if (EW_this.x_sexo && !EW_hasValue(EW_this.x_sexo, "RADIO" )) {
	if (!EW_onError(EW_this, EW_this.x_sexo, "RADIO", "Please enter required field - Sexo"))
		return false;
}
if (EW_this.x_estado_civil_id && !EW_hasValue(EW_this.x_estado_civil_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_estado_civil_id, "SELECT", "El estado civil es requerido."))
		return false;
}
if (EW_this.x_numero_hijos && !EW_checkinteger(EW_this.x_numero_hijos.value)) {
	if (!EW_onError(EW_this, EW_this.x_numero_hijos, "TEXT", "Incorrect integer - Numero de hijos"))
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
<p><span class="phpmaker">Add to TABLE: CLIENTES<br><br><a href="php_clientelist.php">Back to List</a></span></p>
<form name="clienteadd" id="clienteadd" action="php_clienteadd.php" method="post" onSubmit="return EW_checkMyForm(this);">
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
		<td class="ewTableHeader"><span>Solicitud</span></td>
		<td class="ewTableAltRow"><span>
<?php if (!(!is_null($x_solicitud_id)) || ($x_solicitud_id == "")) { $x_solicitud_id = 0;} // Set default value ?>
<?php
$x_solicitud_idList = "<select name=\"x_solicitud_id\">";
$x_solicitud_idList .= "<option value=''>Please Select</option>";
$sSqlWrk = "SELECT `solicitud_id`, `folio` FROM `solicitud`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_solicitud_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["solicitud_id"] == @$x_solicitud_id) {
			$x_solicitud_idList .= "' selected";
		}
		$x_solicitud_idList .= ">" . $datawrk["folio"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_solicitud_idList .= "</select>";
echo $x_solicitud_idList;
?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Usuario</span></td>
		<td class="ewTableAltRow"><span>
<?php if (!(!is_null($x_usuario_id)) || ($x_usuario_id == "")) { $x_usuario_id = 0;} // Set default value ?>
<?php
$x_usuario_idList = "<select name=\"x_usuario_id\">";
$x_usuario_idList .= "<option value=''>Please Select</option>";
$sSqlWrk = "SELECT `usuario_id`, `nombre` FROM `usuario`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_usuario_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["usuario_id"] == @$x_usuario_id) {
			$x_usuario_idList .= "' selected";
		}
		$x_usuario_idList .= ">" . $datawrk["nombre"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_usuario_idList .= "</select>";
echo $x_usuario_idList;
?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Nombre Completo</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_nombre_completo" id="x_nombre_completo" size="30" maxlength="250" value="<?php echo htmlspecialchars(@$x_nombre_completo) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Tipo de negocio</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_tipo_negocio" id="x_tipo_negocio" size="30" maxlength="250" value="<?php echo htmlspecialchars(@$x_tipo_negocio) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Edad</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_edad" id="x_edad" size="30" value="<?php echo htmlspecialchars(@$x_edad) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Sexo</span></td>
		<td class="ewTableAltRow"><span>
<input type="radio" name="x_sexo"<?php if (@$x_sexo == "1") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("1"); ?>">
<?php echo "M"; ?>
<?php echo EditOptionSeparator(0); ?>
<input type="radio" name="x_sexo"<?php if (@$x_sexo == "2") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("2"); ?>">
<?php echo "F"; ?>
<?php echo EditOptionSeparator(1); ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Estado Civil</span></td>
		<td class="ewTableAltRow"><span>
<?php if (!(!is_null($x_estado_civil_id)) || ($x_estado_civil_id == "")) { $x_estado_civil_id = 0;} // Set default value ?>
<?php
$x_estado_civil_idList = "<select name=\"x_estado_civil_id\">";
$x_estado_civil_idList .= "<option value=''>Please Select</option>";
$sSqlWrk = "SELECT `estado_civil_id`, `descripcion` FROM `estado_civil`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["estado_civil_id"] == @$x_estado_civil_id) {
			$x_estado_civil_idList .= "' selected";
		}
		$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_estado_civil_idList .= "</select>";
echo $x_estado_civil_idList;
?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Numero de hijos</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_numero_hijos" id="x_numero_hijos" size="30" value="<?php echo htmlspecialchars(@$x_numero_hijos) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Nombre del Conyuge</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_nombre_conyuge" id="x_nombre_conyuge" size="30" maxlength="250" value="<?php echo htmlspecialchars(@$x_nombre_conyuge) ?>">
</span></td>
	</tr>
</table>
<p>
<input type="submit" name="Action" value="ADD">
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
	global $x_cliente_id;
	$sSql = "SELECT * FROM `cliente`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_cliente_id) : $x_cliente_id;
	$sWhere .= "(`cliente_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_cliente_id"] = $row["cliente_id"];
		$GLOBALS["x_solicitud_id"] = $row["solicitud_id"];
		$GLOBALS["x_usuario_id"] = $row["usuario_id"];
		$GLOBALS["x_nombre_completo"] = $row["nombre_completo"];
		$GLOBALS["x_tipo_negocio"] = $row["tipo_negocio"];
		$GLOBALS["x_edad"] = $row["edad"];
		$GLOBALS["x_sexo"] = $row["sexo"];
		$GLOBALS["x_estado_civil_id"] = $row["estado_civil_id"];
		$GLOBALS["x_numero_hijos"] = $row["numero_hijos"];
		$GLOBALS["x_nombre_conyuge"] = $row["nombre_conyuge"];
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
	global $x_cliente_id;
	$sSql = "SELECT * FROM `cliente`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";

	// Check for duplicate key
	$bCheckKey = true;
	$sWhereChk = $sWhere;
	if ((@$x_cliente_id == "") || (is_null($x_cliente_id))) {
		$bCheckKey = false;
	} else {
		if ($sWhereChk <> "") { $sWhereChk .= " AND "; }
		$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_cliente_id) : $x_cliente_id;			
		$sWhereChk .= "(`cliente_id` = " . addslashes($sTmp) . ")";
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

	// Field solicitud_id
	$theValue = ($GLOBALS["x_solicitud_id"] != "") ? intval($GLOBALS["x_solicitud_id"]) : "NULL";
	$fieldList["`solicitud_id`"] = $theValue;

	// Field usuario_id
	$theValue = ($GLOBALS["x_usuario_id"] != "") ? intval($GLOBALS["x_usuario_id"]) : "NULL";
	$fieldList["`usuario_id`"] = $theValue;

	// Field nombre_completo
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_completo"]) : $GLOBALS["x_nombre_completo"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre_completo`"] = $theValue;

	// Field tipo_negocio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tipo_negocio"]) : $GLOBALS["x_tipo_negocio"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`tipo_negocio`"] = $theValue;

	// Field edad
	$theValue = ($GLOBALS["x_edad"] != "") ? intval($GLOBALS["x_edad"]) : "NULL";
	$fieldList["`edad`"] = $theValue;

	// Field sexo
	$theValue = ($GLOBALS["x_sexo"] != "") ? intval($GLOBALS["x_sexo"]) : "NULL";
	$fieldList["`sexo`"] = $theValue;

	// Field estado_civil_id
	$theValue = ($GLOBALS["x_estado_civil_id"] != "") ? intval($GLOBALS["x_estado_civil_id"]) : "NULL";
	$fieldList["`estado_civil_id`"] = $theValue;

	// Field numero_hijos
	$theValue = ($GLOBALS["x_numero_hijos"] != "") ? intval($GLOBALS["x_numero_hijos"]) : "NULL";
	$fieldList["`numero_hijos`"] = $theValue;

	// Field nombre_conyuge
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_conyuge"]) : $GLOBALS["x_nombre_conyuge"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre_conyuge`"] = $theValue;

	// insert into database
	$sSql = "INSERT INTO `cliente` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	return true;
}
?>
