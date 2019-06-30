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
$x_fondeo_empresa_id = Null;
$x_nombre = Null;
$x_entidad_id = Null;
$x_calle = Null;
$x_colonia = Null;
$x_ciudad = Null;
$x_codigo_postal = Null;
$x_lada = Null;
$x_telefono = Null;
$x_fax = Null;
$x_contacto = Null;
$x_contacto_email = Null;
$x_fondeo_empresa_dependiente_id = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
$sKey = @$_GET["key"];
if (($sKey == "") || (is_null($sKey))) { $sKey = @$_POST["key"]; }
if (!empty($sKey)) $sKey = (get_magic_quotes_gpc()) ? stripslashes($sKey) : $sKey;

// Get action
$sAction = @$_POST["a_edit"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";	// Display with input box
} else {

	// Get fields from form
	$x_fondeo_empresa_id = @$_POST["x_fondeo_empresa_id"];
	$x_nombre = @$_POST["x_nombre"];
	$x_entidad_id = @$_POST["x_entidad_id"];
	$x_calle = @$_POST["x_calle"];
	$x_colonia = @$_POST["x_colonia"];
	$x_ciudad = @$_POST["x_ciudad"];
	$x_codigo_postal = @$_POST["x_codigo_postal"];
	$x_lada = @$_POST["x_lada"];
	$x_telefono = @$_POST["x_telefono"];
	$x_fax = @$_POST["x_fax"];
	$x_contacto = @$_POST["x_contacto"];
	$x_contacto_email = @$_POST["x_contacto_email"];
	$x_fondeo_empresa_dependiente_id = @$_POST["x_fondeo_empresa_dependiente_id"];
}
if (($sKey == "") || ((is_null($sKey)))) {
	ob_end_clean();
	header("Location: php_fondeo_empresalist.php");
	exit();
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($sKey,$conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron los datos.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_fondeo_empresalist.php");
			exit();
		}
		break;
	case "U": // Update
		if (EditData($sKey,$conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "Los datos han sido actualizados.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_fondeo_empresalist.php");
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
	if (!EW_onError(EW_this, EW_this.x_nombre, "TEXT", "El nombre es requerido."))
		return false;
}
if (EW_this.x_entidad_id && !EW_hasValue(EW_this.x_entidad_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_entidad_id, "SELECT", "Le entidad es requerida."))
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
if (EW_this.x_ciudad && !EW_hasValue(EW_this.x_ciudad, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ciudad, "TEXT", "La ciudad es requerida."))
		return false;
}
if (EW_this.x_contacto && !EW_hasValue(EW_this.x_contacto, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_contacto, "TEXT", "El nombre del contacto es requerido."))
		return false;
}
if (EW_this.x_contacto_email && !EW_hasValue(EW_this.x_contacto_email, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_contacto_email, "TEXT", "El email es requerido o es incorrecto."))
		return false;
}
if (EW_this.x_contacto_email && !EW_checkemail(EW_this.x_contacto_email.value)) {
	if (!EW_onError(EW_this, EW_this.x_contacto_email, "TEXT", "El email es requerido o es incorrecto."))
		return false; 
}
return true;
}

//-->
</script>
<p><span class="phpmaker">Empresas de Fondeo<br><br>
<a href="php_fondeo_empresalist.php">Regresar al listado</a></span></p>
<form name="empresaedit" id="empresaedit" action="php_fondeo_empresaedit.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<input type="hidden" name="key" value="<?php echo htmlspecialchars($sKey); ?>">
<table class="ewTable">
	<tr>
		<td width="121" class="ewTableHeaderThin"><span>No</span></td>
		<td width="867" class="ewTableAltRow"><span>
<?php echo $x_fondeo_empresa_id; ?><input type="hidden" name="x_fondeo_empresa_id" value="<?php echo $x_fondeo_empresa_id; ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Nombre</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_nombre" id="x_nombre" size="30" maxlength="250" value="<?php echo htmlspecialchars(@$x_nombre) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Entidad</span></td>
		<td class="ewTableAltRow"><span>
<?php
$x_entidad_idList = "<select name=\"x_entidad_id\">";
$x_entidad_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT `entidad_id`, `nombre` FROM `entidad`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["entidad_id"] == @$x_entidad_id) {
			$x_entidad_idList .= "' selected";
		}
		$x_entidad_idList .= ">" . $datawrk["nombre"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_entidad_idList .= "</select>";
echo $x_entidad_idList;
?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Calle</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_calle" id="x_calle" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_calle) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Colonia</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_colonia" id="x_colonia" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_colonia) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Ciudad</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_ciudad" id="x_ciudad" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_ciudad) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Codigo postal</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_codigo_postal" id="x_codigo_postal" size="30" maxlength="10" value="<?php echo htmlspecialchars(@$x_codigo_postal) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Lada</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_lada" id="x_lada" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_lada) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Telefono</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_telefono" id="x_telefono" size="30" maxlength="20" value="<?php echo htmlspecialchars(@$x_telefono) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Fax</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_fax" id="x_fax" size="30" maxlength="20" value="<?php echo htmlspecialchars(@$x_fax) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Contacto</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_contacto" id="x_contacto" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_contacto) ?>">
</span></td>
	</tr>
	<tr>
	  <td class="ewTableHeaderThin"><span>Contacto email</span></td>
	  <td class="ewTableAltRow"><span>
  <input type="text" name="x_contacto_email" id="x_contacto_email" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_contacto_email) ?>">
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

function LoadData($sKey,$conn)
{
	$sKeyWrk = "" . addslashes($sKey) . "";
	$sSql = "SELECT * FROM `fondeo_empresa`";
	$sSql .= " WHERE `fondeo_empresa_id` = " . $sKeyWrk;
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
		$GLOBALS["x_fondeo_empresa_id"] = $row["fondeo_empresa_id"];
		$GLOBALS["x_nombre"] = $row["nombre"];
		$GLOBALS["x_entidad_id"] = $row["entidad_id"];
		$GLOBALS["x_calle"] = $row["calle"];
		$GLOBALS["x_colonia"] = $row["colonia"];
		$GLOBALS["x_ciudad"] = $row["ciudad"];
		$GLOBALS["x_codigo_postal"] = $row["codigo_postal"];
		$GLOBALS["x_lada"] = $row["lada"];
		$GLOBALS["x_telefono"] = $row["telefono"];
		$GLOBALS["x_fax"] = $row["fax"];
		$GLOBALS["x_contacto"] = $row["contacto"];
		$GLOBALS["x_contacto_email"] = $row["contacto_email"];
		$GLOBALS["x_fondeo_empresa_dependiente_id"] = $row["empresa_dependiente_id"];
	}
	phpmkr_free_result($rs);
	return $LoadData;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function EditData
// - Edit Data based on Key Value sKey
// - Variables used: field variables

function EditData($sKey,$conn)
{

	// Open record
	$sKeyWrk = "" . addslashes($sKey) . "";
	$sSql = "SELECT * FROM `fondeo_empresa`";
	$sSql .= " WHERE `fondeo_empresa_id` = " . $sKeyWrk;
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
		$EditData = false; // Update Failed
	}else{
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre"]) : $GLOBALS["x_nombre"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`nombre`"] = $theValue;
		$theValue = ($GLOBALS["x_entidad_id"] != "") ? intval($GLOBALS["x_entidad_id"]) : "NULL";
		$fieldList["`entidad_id`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle"]) : $GLOBALS["x_calle"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`calle`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia"]) : $GLOBALS["x_colonia"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`colonia`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ciudad"]) : $GLOBALS["x_ciudad"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ciudad`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_codigo_postal"]) : $GLOBALS["x_codigo_postal"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`codigo_postal`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_lada"]) : $GLOBALS["x_lada"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`lada`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono"]) : $GLOBALS["x_telefono"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`telefono`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_fax"]) : $GLOBALS["x_fax"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`fax`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_contacto"]) : $GLOBALS["x_contacto"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`contacto`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_contacto_email"]) : $GLOBALS["x_contacto_email"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`contacto_email`"] = $theValue;

		// update
		$sSql = "UPDATE `fondeo_empresa` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE `fondeo_empresa_id` =". $sKeyWrk;
		phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
		$EditData = true; // Update Successful
	}
	return $EditData;
}
?>
