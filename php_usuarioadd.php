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
$x_usuario_id = Null; 
$ox_usuario_id = Null;
$x_usuario_rol_id = Null; 
$ox_usuario_rol_id = Null;
$x_usuario_status_id = Null; 
$ox_usuario_status_id = Null;
$x_usuario = Null; 
$ox_usuario = Null;
$x_clave = Null; 
$ox_clave = Null;
$x_nombre = Null; 
$ox_nombre = Null;
$x_fecha_registro = Null; 
$ox_fecha_registro = Null;
$x_fecha_caduca = Null; 
$ox_fecha_caduca = Null;
$x_fecha_visita = Null; 
$ox_fecha_visita = Null;
$x_visitas = Null; 
$ox_visitas = Null;
$x_email = Null; 
$ox_email = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
$x_usuario_id = @$_GET["usuario_id"];
if (empty($x_usuario_id)) {
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
	$x_usuario_id = @$_POST["x_usuario_id"];
	$x_usuario_rol_id = @$_POST["x_usuario_rol_id"];
	$x_usuario_status_id = @$_POST["x_usuario_status_id"];
	$x_usuario = @$_POST["x_usuario"];
	$x_clave = @$_POST["x_clave"];
	$x_nombre = @$_POST["x_nombre"];
	$x_fecha_registro = @$_POST["x_fecha_registro"];
	$x_fecha_caduca = @$_POST["x_fecha_caduca"];
	$x_fecha_visita = @$_POST["x_fecha_visita"];
	$x_visitas = @$_POST["x_visitas"];
	$x_email = @$_POST["x_email"];
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No records found";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_usuariolist.php");
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "Los datos han sido registrados";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_usuariolist.php");
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
if (EW_this.x_usuario_rol_id && !EW_hasValue(EW_this.x_usuario_rol_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_usuario_rol_id, "SELECT", "Los permisos de lusuario son requeridos."))
		return false;
}
if (EW_this.x_usuario_status_id && !EW_hasValue(EW_this.x_usuario_status_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_usuario_status_id, "SELECT", "El status es requerido."))
		return false;
}
if (EW_this.x_usuario && !EW_hasValue(EW_this.x_usuario, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_usuario, "TEXT", "La cuenta es requerida."))
		return false;
}
if (EW_this.x_clave && !EW_hasValue(EW_this.x_clave, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_clave, "TEXT", "La clave es requerida."))
		return false;
}
if (EW_this.x_nombre && !EW_hasValue(EW_this.x_nombre, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre, "TEXT", "El nombre es requerido."))
		return false;
}
if (EW_this.x_fecha_registro && !EW_hasValue(EW_this.x_fecha_registro, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_registro, "TEXT", "La fecha de registro es requerida."))
		return false;
}
if (EW_this.x_fecha_registro && !EW_checkeurodate(EW_this.x_fecha_registro.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_registro, "TEXT", "La fecha de registro es requerida."))
		return false; 
}
if (EW_this.x_fecha_caduca && !EW_hasValue(EW_this.x_fecha_caduca, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_caduca, "TEXT", "La fecha de caducidad es requerida."))
		return false;
}
if (EW_this.x_fecha_caduca && !EW_checkeurodate(EW_this.x_fecha_caduca.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_caduca, "TEXT", "La fecha de caducidad es requerida."))
		return false; 
}
if (EW_this.x_email && !EW_hasValue(EW_this.x_email, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_email, "TEXT", "El email es requerido."))
		return false;
}
if (EW_this.x_email && !EW_checkemail(EW_this.x_email.value)) {
	if (!EW_onError(EW_this, EW_this.x_email, "TEXT", "El email es requerido."))
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
<!--script type="text/javascript" src="popcalendar.js"></script-->
<!-- New popup calendar -->
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/skins/aqua/theme.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-es.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<p><span class="phpmaker">USUARIOS<br>
  <br>
    <a href="php_usuariolist.php">Regresar a la lista</a></span></p>
<form name="usuarioadd" id="usuarioadd" action="php_usuarioadd.php" method="post" onSubmit="return EW_checkMyForm(this);">
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
<table width="399" >
	<tr>
		<td width="129" class="ewTableHeaderThin"><span>Perimisos</span></td>
		<td width="258" class="ewTableAltRow"><span>
<?php if (!(!is_null($x_usuario_rol_id)) || ($x_usuario_rol_id == "")) { $x_usuario_rol_id = 0;} // Set default value ?>
<?php
$x_usuario_rol_idList = "<select name=\"x_usuario_rol_id\">";
$x_usuario_rol_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT `usuario_rol_id`, `descripcion` FROM `usuario_rol`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_usuario_rol_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["usuario_rol_id"] == @$x_usuario_rol_id) {
			$x_usuario_rol_idList .= "' selected";
		}
		$x_usuario_rol_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_usuario_rol_idList .= "</select>";
echo $x_usuario_rol_idList;
?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Status</span></td>
		<td class="ewTableAltRow"><span>
<?php if (!(!is_null($x_usuario_status_id)) || ($x_usuario_status_id == "")) { $x_usuario_status_id = 0;} // Set default value ?>
<?php
$x_usuario_status_idList = "<select name=\"x_usuario_status_id\">";
$x_usuario_status_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT `usuario_status_id`, `descripcion` FROM `usuario_status`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_usuario_status_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["usuario_status_id"] == @$x_usuario_status_id) {
			$x_usuario_status_idList .= "' selected";
		}
		$x_usuario_status_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_usuario_status_idList .= "</select>";
echo $x_usuario_status_idList;
?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Cuenta</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_usuario" id="x_usuario" size="10" maxlength="10" value="<?php echo htmlspecialchars(@$x_usuario) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Clave</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_clave" id="x_clave" size="10" maxlength="10" value="<?php echo htmlspecialchars(@$x_clave) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Nombre</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_nombre" id="x_nombre" size="50" maxlength="250" value="<?php echo htmlspecialchars(@$x_nombre) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Fecha de registro</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_fecha_registro" id="x_fecha_registro" value="<?php echo FormatDateTime(@$x_fecha_registro,7); ?>">
&nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_registro" alt="Pick a Date" style="cursor:pointer;cursor:hand;">
<script type="text/javascript">
Calendar.setup(
{
inputField : "x_fecha_registro", // ID of the input field
ifFormat : "%d/%m/%Y", // the date format
button : "cx_fecha_registro" // ID of the button
}
);
</script>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Fecha de caducidad</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_fecha_caduca" id="x_fecha_caduca" value="<?php echo FormatDateTime(@$x_fecha_caduca,7); ?>">
&nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_caduca" alt="Pick a Date" style="cursor:pointer;cursor:hand;">
<script type="text/javascript">
Calendar.setup(
{
inputField : "x_fecha_caduca", // ID of the input field
ifFormat : "%d/%m/%Y", // the date format
button : "cx_fecha_caduca" // ID of the button
}
);
</script>
</span></td>
	</tr>
	
	<tr>
		<td class="ewTableHeaderThin"><span>Email</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_email" id="x_email" size="50" maxlength="150" value="<?php echo htmlspecialchars(@$x_email) ?>">
</span></td>
	</tr>
</table>
<p>
<input type="submit" name="Action" value="Registrar">
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
	global $x_usuario_id;
	$sSql = "SELECT * FROM `usuario`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_usuario_id) : $x_usuario_id;
	$sWhere .= "(`usuario_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_usuario_id"] = $row["usuario_id"];
		$GLOBALS["x_usuario_rol_id"] = $row["usuario_rol_id"];
		$GLOBALS["x_usuario_status_id"] = $row["usuario_status_id"];
		$GLOBALS["x_usuario"] = $row["usuario"];
		$GLOBALS["x_clave"] = $row["clave"];
		$GLOBALS["x_nombre"] = $row["nombre"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
		$GLOBALS["x_fecha_caduca"] = $row["fecha_caduca"];
		$GLOBALS["x_fecha_visita"] = $row["fecha_visita"];
		$GLOBALS["x_visitas"] = $row["visitas"];
		$GLOBALS["x_email"] = $row["email"];
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
	global $x_usuario_id;
	$sSql = "SELECT * FROM `usuario`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";

	// Check for duplicate key
	$bCheckKey = true;
	$sWhereChk = $sWhere;
	if ((@$x_usuario_id == "") || (is_null($x_usuario_id))) {
		$bCheckKey = false;
	} else {
		if ($sWhereChk <> "") { $sWhereChk .= " AND "; }
		$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_usuario_id) : $x_usuario_id;			
		$sWhereChk .= "(`usuario_id` = " . addslashes($sTmp) . ")";
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

	// Field usuario_rol_id
	$theValue = ($GLOBALS["x_usuario_rol_id"] != "") ? intval($GLOBALS["x_usuario_rol_id"]) : "NULL";
	$fieldList["`usuario_rol_id`"] = $theValue;

	// Field usuario_status_id
	$theValue = ($GLOBALS["x_usuario_status_id"] != "") ? intval($GLOBALS["x_usuario_status_id"]) : "NULL";
	$fieldList["`usuario_status_id`"] = $theValue;

	// Field usuario
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_usuario"]) : $GLOBALS["x_usuario"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`usuario`"] = $theValue;

	// Field clave
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_clave"]) : $GLOBALS["x_clave"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`clave`"] = $theValue;

	// Field nombre
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre"]) : $GLOBALS["x_nombre"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre`"] = $theValue;

	// Field fecha_registro
	$theValue = ($GLOBALS["x_fecha_registro"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_registro"]) . "'" : "Null";
	$fieldList["`fecha_registro`"] = $theValue;

	// Field fecha_caduca
	$theValue = ($GLOBALS["x_fecha_caduca"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_caduca"]) . "'" : "Null";
	$fieldList["`fecha_caduca`"] = $theValue;

	// Field fecha_visita
	$theValue = ($GLOBALS["x_fecha_visita"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_visita"]) . "'" : "Null";
	$fieldList["`fecha_visita`"] = $theValue;

	// Field visitas
	$theValue = ($GLOBALS["x_visitas"] != "") ? intval($GLOBALS["x_visitas"]) : "NULL";
	$fieldList["`visitas`"] = $theValue;

	// Field email
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_email"]) : $GLOBALS["x_email"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`email`"] = $theValue;

	// insert into database
	$sSql = "INSERT INTO `usuario` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	return true;
}
?>
