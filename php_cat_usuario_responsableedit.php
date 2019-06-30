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
?>
<?php
if (@$_SESSION["php_project_esf_status"] <> "login") {
	header("Location:  login.php");
	exit();
}
?>
<?php

// Initialize common variables
$x_responsable_id = Null; 
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

$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
$currdate_base = $currentdate["year"]."-".$currentdate["mon"]."-".$currentdate["mday"];	

?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
$x_usuario_id = @$_GET["usuario_id"];
if (empty($x_usuario_id)) {
	$bCopy = false;
}

$x_responsable_id = @$_GET["x_responsable_id"];
if (empty($x_responsable_id)) {
	$x_responsable_id = @$_POST["x_responsable_id"];
}else{

	$sSql = "select usuario_id from responsable_sucursal where responsable_sucursal_id = $x_responsable_id";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_usuario_id = $row["usuario_id"];
	phpmkr_free_result($rs);

	$sSql = "select * from usuario where usuario_id = $x_usuario_id";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
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
	phpmkr_free_result($rs);

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

		if (EditData($conn, $x_responsable_id)) { // Add New Record
			echo "
			<script type=\"text/javascript\">
			<!--
			window.opener.document.responsable_sucursaledit.a_edit.value='N';			
			window.opener.document.responsable_sucursaledit.submit();
			window.close();
			//-->
			</script>";

//			echo "<div align=\"center\" class=\"phpmaker\"><strong><a href=\"javascript:window.close()\">Cuenta actualizada, Cerrar Ventana</a></strong></div>";			
			
		}else{
			$_msg = "No fue posible registrar los datos.";		
		}
		break;
}
?>
<html>
<head>
<title>Usuarios</title>
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
<SCRIPT TYPE="text/javascript">
<!--
window.focus();
//-->
</SCRIPT>
</head>
<body>
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
if (EW_this.x_fecha_visita && !EW_checkeurodate(EW_this.x_fecha_visita.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_visita, "TEXT", "Incorrect date, format = dd/mm/yyyy - Ultima visita"))
		return false; 
}
if (EW_this.x_visitas && !EW_checkinteger(EW_this.x_visitas.value)) {
	if (!EW_onError(EW_this, EW_this.x_visitas, "TEXT", "Incorrect integer - No. de visitas"))
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
<!-- New popup calendar -->
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/skins/aqua/theme.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-es.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<form name="usuarioadd" id="usuarioadd" action="php_cat_usuario_responsableedit.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_add" value="A">
<input type="hidden" name="x_responsable_id" value="<?php echo $x_responsable_id; ?>" >
<input type="hidden" name="x_usuario_id" value="<?php echo $x_usuario_id; ?>" >
<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION["ewmsg"] ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>

<p>
<div align="center" class="phpmaker"><strong><u>Usuarios</u></strong></div>
<br>
<div align="center" class="phpmaker"><strong><a href="javascript:window.close()">Cerrar Ventana</a></strong></div>
</p>
<div align="left" class="phpmaker"></div>
<table class="phpmaker" width="400">
	<tr>
		<td class="ewTableHeaderThin"><span>Perimisos</span></td>
		<td class="ewTableAltRow"><span>
<?php if (!(!is_null($x_usuario_rol_id)) || ($x_usuario_rol_id == "")) { $x_usuario_rol_id = 0;} // Set default value ?>
<?php
$x_usuario_rol_idList = "<select name=\"x_usuario_rol_id\">";
$x_usuario_rol_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT `usuario_rol_id`, `descripcion` FROM `usuario_rol` where descripcion = 'Responsable de sucursal'";
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
<input type="text" name="x_nombre" id="x_nombre" size="30" maxlength="250" value="<?php echo htmlspecialchars(@$x_nombre) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Fecha de registro</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_fecha_registro" id="x_fecha_registro" value="<?php echo FormatDateTime($currdate,7); ?>">
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
<input type="text" name="x_fecha_caduca" id="x_fecha_caduca" value="<?php echo FormatDateTime($currdate,7); ?>">
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
<input type="text" name="x_email" id="x_email" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_email) ?>">
</span></td>
	</tr>
	<tr>
	  <td colspan="2" class="ewTableRow">&nbsp;</td>
    </tr>
	<tr>
	  <td colspan="2" class="ewTableRow"><div align="center">
	    <input type="submit" name="Action" value="Modificar">
	  </div></td>
    </tr>
</table>

<p>

</form>
</body>
</html>
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
// Function EditData
// - Edit Data based on Key Value sKey
// - Variables used: field variables

function EditData($conn)
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
		$bEditData = false; // Update Failed
	}else{
		$theValue = ($GLOBALS["x_usuario_rol_id"] != "") ? intval($GLOBALS["x_usuario_rol_id"]) : "NULL";
		$fieldList["`usuario_rol_id`"] = $theValue;
		$theValue = ($GLOBALS["x_usuario_status_id"] != "") ? intval($GLOBALS["x_usuario_status_id"]) : "NULL";
		$fieldList["`usuario_status_id`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_usuario"]) : $GLOBALS["x_usuario"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`usuario`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_clave"]) : $GLOBALS["x_clave"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`clave`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre"]) : $GLOBALS["x_nombre"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`nombre`"] = $theValue;
		$theValue = ($GLOBALS["x_fecha_registro"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_registro"]) . "'" : "Null";
		$fieldList["`fecha_registro`"] = $theValue;
		$theValue = ($GLOBALS["x_fecha_caduca"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_caduca"]) . "'" : "Null";
		$fieldList["`fecha_caduca`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_email"]) : $GLOBALS["x_email"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`email`"] = $theValue;

		// update
		$sSql = "UPDATE `usuario` SET ";
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

