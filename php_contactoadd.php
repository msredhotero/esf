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
$x_contacto_id = Null; 
$ox_contacto_id = Null;
$x_nombre = Null; 
$ox_nombre = Null;
$x_email = Null; 
$ox_email = Null;
$x_comentario = Null; 
$ox_comentario = Null;
$x_fecha_registro = Null; 
$ox_fecha_registro = Null;
$x_telefono = Null; 
$ox_telefono = Null;
$x_contacto_status_id = Null; 
$ox_contacto_status_id = Null;
$x_promotor_id = Null; 
$ox_promotor_id = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
$x_contacto_id = @$_GET["contacto_id"];
if (empty($x_contacto_id)) {
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
	$x_contacto_id = @$_POST["x_contacto_id"];
	$x_nombre = @$_POST["x_nombre"];
	$x_email = @$_POST["x_email"];
//	$x_comentario = @$_POST["x_comentario"];
	$x_comentario = stripslashes($_POST['inpContent']);
	$x_fecha_registro = @$_POST["x_fecha_registro"];
	$x_telefono = @$_POST["x_telefono"];
	$x_contacto_status_id = @$_POST["x_contacto_status_id"];
	$x_promotor_id = @$_POST["x_promotor_id"];
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron los datos";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_contactolist.php");
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "El contacto ha sido registrdo.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_contactolist.php");
			exit();
		}
		break;
}
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<script language=JavaScript src='editor/scripts/language/spanish/editor_lang.js'></script>
<?php
//Check user's Browser
if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE"))
    echo "<script language=JavaScript src='editor/scripts/editor.js'></script>";
else
    echo "<script language=JavaScript src='editor/scripts/moz/editor.js'></script>";
?>

<pre id="idTemporary" name="idTemporary" style="display:none">
<?
if(isset($_POST["inpContent"]))
    {
    $sContent=stripslashes($_POST['inpContent']);//remove slashes (/)
    echo htmlentities($sContent);
	}else{
		if($x_comentario != ""){
			echo $x_comentario;
		}
	}
?>
</pre>

<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<script type="text/javascript">
<!--
function EW_checkMyForm() {

EW_this = document.contactoadd;
validada = true;

if (EW_this.x_nombre && !EW_hasValue(EW_this.x_nombre, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre, "TEXT", "El nombre es requerido."))
	validada = false;
}
if (validada && EW_this.x_email && !EW_hasValue(EW_this.x_email, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_email, "TEXT", "El Email es requerido."))
	validada = false;
}
if (validada && EW_this.x_email && !EW_checkemail(EW_this.x_email.value)) {
	if (!EW_onError(EW_this, EW_this.x_email, "TEXT", "El Email es invalido."))
	validada = false;
}
/*
if (EW_this.x_comentario && !EW_hasValue(EW_this.x_comentario, "TEXTAREA" )) {
	if (!EW_onError(EW_this, EW_this.x_comentario, "TEXTAREA", "El comentario es requerido."))
		return false;
}
*/
if (validada && EW_this.x_fecha_registro && !EW_hasValue(EW_this.x_fecha_registro, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_registro, "TEXT", "La fecha de registro es requerida."))
	validada = false;
}
if (validada && EW_this.x_fecha_registro && !EW_checkeurodate(EW_this.x_fecha_registro.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_registro, "TEXT", "La fecha de registro es requerida."))
	validada = false;
}
if (validada && EW_this.x_contacto_status_id && !EW_hasValue(EW_this.x_contacto_status_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_contacto_status_id, "SELECT", "El status es requerido."))
	validada = false;
}
if (validada && EW_this.x_promotor_id && !EW_hasValue(EW_this.x_promotor_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_promotor_id, "SELECT", "El promotor es requerido."))
	validada = false;
}

EW_this.elements.inpContent.value = oEdit1.getHTMLBody();

if(validada && EW_this.elements.inpContent.value == ""){
	if (!EW_onError(EW_this, EW_this.x_comentario, "TEXTAREA", "El comentario es requerido."))
		validada = false;
}
if(validada == true){
	EW_this.submit();
}

}

//-->
</script>
<!--script type="text/javascript" src="popcalendar.js"></script-->
<!-- New popup calendar -->
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/skins/aqua/theme.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<p><span class="phpmaker">CONTACTOS<br>
  <br>
    <a href="php_contactolist.php">Regresar a la lista</a></span></p>
<form name="contactoadd" id="contactoadd" action="php_contactoadd.php" method="post">
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
		<td width="132" class="ewTableHeader"><span>Nombre</span></td>
		<td width="856" class="ewTableAltRow"><span>
<input type="text" name="x_nombre" id="x_nombre" size="80" maxlength="250" value="<?php echo htmlspecialchars(@$x_nombre) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Email</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_email" id="x_email" size="50" maxlength="150" value="<?php echo htmlspecialchars(@$x_email) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Comentario</span></td>
		<td class="ewTableAltRow"><span>
    <div align="left">
      <script>
		var oEdit1 = new InnovaEditor("oEdit1");

		//STEP 2: Asset Manager Localization: Add querystring lang=english/danish/dutch...
        oEdit1.cmdAssetManager="modalDialogShow('editor/assetmanager/assetmanager.php?lang=spanish',10,10)";//Use "relative to root" path
		oEdit1.btnFlash=false;//Show 'Insert Flash' button
		oEdit1.btnMedia=false;//Show 'Insert Media' button
		oEdit1.btnBookmark=false;
		oEdit1.btnImage=true;
		oEdit1.btnForm=false;
		oEdit1.RENDER(document.getElementById("idTemporary").innerHTML);
	</script>
      <input type="hidden" name="inpContent"  id="inpContent" />
    </div>		
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Fecha de registro</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_fecha_registro" id="x_fecha_registro" value="<?php echo FormatDateTime(@$x_fecha_registro,7); ?>">
&nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_registro" alt="Calendario" style="cursor:pointer;cursor:hand;">
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
		<td class="ewTableHeader"><span>Telefono</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_telefono" id="x_telefono" size="30" maxlength="50" value="<?php echo htmlspecialchars(@$x_telefono) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Status</span></td>
		<td class="ewTableAltRow"><span>
<?php if (!(!is_null($x_contacto_status_id)) || ($x_contacto_status_id == "")) { $x_contacto_status_id = 0;} // Set default value ?>
<?php
$x_contacto_status_idList = "<select name=\"x_contacto_status_id\">";
$x_contacto_status_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT `contacto_status_id`, `descripcion` FROM `contacto_status`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_contacto_status_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["contacto_status_id"] == @$x_contacto_status_id) {
			$x_contacto_status_idList .= "' selected";
		}
		$x_contacto_status_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_contacto_status_idList .= "</select>";
echo $x_contacto_status_idList;
?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeader"><span>Promotor</span></td>
		<td class="ewTableAltRow"><span>
<?php if (!(!is_null($x_promotor_id)) || ($x_promotor_id == "")) { $x_promotor_id = 0;} // Set default value ?>
<?php
$x_promotor_idList = "<select name=\"x_promotor_id\">";
$x_promotor_idList .= "<option value=''>Seleccione</option>";
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
</table>
<p>
<input class="boton_medium" type="button" name="Action" value="AGREGAR" onClick="EW_checkMyForm();">
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
	global $x_contacto_id;
	$sSql = "SELECT * FROM `contacto`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_contacto_id) : $x_contacto_id;
	$sWhere .= "(`contacto_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_contacto_id"] = $row["contacto_id"];
		$GLOBALS["x_nombre"] = $row["nombre"];
		$GLOBALS["x_email"] = $row["email"];
		$GLOBALS["x_comentario"] = $row["comentario"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
		$GLOBALS["x_telefono"] = $row["telefono"];
		$GLOBALS["x_contacto_status_id"] = $row["contacto_status_id"];
		$GLOBALS["x_promotor_id"] = $row["promotor_id"];
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
	global $x_contacto_id;
	$sSql = "SELECT * FROM `contacto`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";

	// Check for duplicate key
	$bCheckKey = true;
	$sWhereChk = $sWhere;
	if ((@$x_contacto_id == "") || (is_null($x_contacto_id))) {
		$bCheckKey = false;
	} else {
		if ($sWhereChk <> "") { $sWhereChk .= " AND "; }
		$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_contacto_id) : $x_contacto_id;			
		$sWhereChk .= "(`contacto_id` = " . addslashes($sTmp) . ")";
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

	// Field nombre
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre"]) : $GLOBALS["x_nombre"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre`"] = $theValue;

	// Field email
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_email"]) : $GLOBALS["x_email"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`email`"] = $theValue;

	// Field comentario
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_comentario"]) : $GLOBALS["x_comentario"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`comentario`"] = $theValue;

	// Field fecha_registro
	$theValue = ($GLOBALS["x_fecha_registro"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_registro"]) . "'" : date("D, d M Y H:i:s");
	$fieldList["`fecha_registro`"] = $theValue;

	// Field telefono
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono"]) : $GLOBALS["x_telefono"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono`"] = $theValue;

	// Field contacto_status_id
	$theValue = ($GLOBALS["x_contacto_status_id"] != "") ? intval($GLOBALS["x_contacto_status_id"]) : "NULL";
	$fieldList["`contacto_status_id`"] = $theValue;

	// Field promotor_id
	$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
	$fieldList["`promotor_id`"] = $theValue;

	// insert into database
	$sSql = "INSERT INTO `contacto` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	return true;
}
?>
