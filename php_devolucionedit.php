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
$x_devolucion_id = Null; 
$ox_devolucion_id = Null;
$x_fecha_registro = Null; 
$ox_fecha_registro = Null;
$x_fecha_pago = Null; 
$ox_fecha_pago = Null;
$x_medio_pago_id = Null; 
$ox_medio_pago_id = Null;
$x_referencia = Null; 
$ox_referencia = Null;
$x_factor = Null; 
$ox_factor = Null;
$x_importe = Null; 
$ox_importe = Null;
$x_devolucion_status_id = Null; 
$ox_devolucion_status_id = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Load key from QueryString
$x_devolucion_id = @$_GET["devolucion_id"];
$x_devolucion_status_id = @$_GET["tm"];

//if (!empty($x_devolucion_id )) $x_devolucion_id  = (get_magic_quotes_gpc()) ? stripslashes($x_devolucion_id ) : $x_devolucion_id ;
// Get action

$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I";	// Display with input box
} else {

	// Get fields from form
	$x_devolucion_id = @$_POST["x_devolucion_id"];
	$x_fecha_registro = @$_POST["x_fecha_registro"];
	$x_fecha_pago = @$_POST["x_fecha_pago"];
	$x_medio_pago_id = @$_POST["x_medio_pago_id"];
	$x_referencia = @$_POST["x_referencia"];
	$x_factor = @$_POST["x_factor"];
	$x_importe = @$_POST["x_importe"];
	$x_devolucion_status_id = @$_POST["x_devolucion_status_id"];
}

// Check if valid key
if (($x_devolucion_id == "") || (is_null($x_devolucion_id))) {
	ob_end_clean();
	header("Location: php_devolucionlist.php");
	exit();
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron los datos";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_devolucionlist.php");
			exit();
		}
		break;
	case "U": // Update
		if (EditData($conn)) { // Update Record based on key
			if($x_devolucion_status_id  == 2){
				$_SESSION["ewmsg"] = "La devolucion ha sido pagada.";
			}else{
				$_SESSION["ewmsg"] = "La devolucion ha sido cancelada.";			
			}
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_devolucionlist.php");
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
if (EW_this.x_fecha_pago && !EW_hasValue(EW_this.x_fecha_pago, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_pago, "TEXT", "La fecha de movimiento es requerida."))
		return false;
}
if (EW_this.x_fecha_pago && !EW_checkeurodate(EW_this.x_fecha_pago.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_pago, "TEXT", "La fecha de movimiento es incorrecta."))
		return false; 
}
if (EW_this.x_medio_pago_id && !EW_hasValue(EW_this.x_medio_pago_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_medio_pago_id, "SELECT", "El medio de pago es requerido."))
		return false;
}
if (EW_this.x_referencia && !EW_hasValue(EW_this.x_referencia, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_referencia, "TEXT", "La referencia de pago es requerida."))
		return false;
}
if (EW_this.x_devolucion_status_id && !EW_hasValue(EW_this.x_devolucion_status_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_devolucion_status_id, "SELECT", "El status de la devolucion es requerido."))
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
<p><span class="phpmaker">Devoluciones<br>
  <br>
    <a href="php_devolucionlist.php">Regresar a la lista</a></span></p>
<form name="devolucionedit" id="devolucionedit" action="php_devolucionedit.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<table class="ewTable_small">
	<tr>
		<td width="140" class="ewTableHeaderThin"><span>No</span></td>
		<td width="648" class="ewTableAltRow"><span>
<?php echo $x_devolucion_id; ?>
<input type="hidden" id="x_devolucion_id" name="x_devolucion_id" value="<?php echo htmlspecialchars(@$x_devolucion_id); ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Fecha de registro</span></td>
		<td class="ewTableAltRow"><span>
		<?php echo FormatDateTime(@$x_fecha_registro,7); ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Fecha de <?php if($x_devolucion_status_id == 2){ echo "pago";}else{ echo "cancelacion";}?></span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_fecha_pago" id="x_fecha_pago" value="<?php echo FormatDateTime(@$x_fecha_pago,7); ?>">
&nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_pago" alt="Pick a Date" style="cursor:pointer;cursor:hand;">
<script type="text/javascript">
Calendar.setup(
{
inputField : "x_fecha_pago", // ID of the input field
ifFormat : "%d/%m/%Y", // the date format
button : "cx_fecha_pago" // ID of the button
}
);
</script>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Medio de Pago</span></td>
		<td class="ewTableAltRow"><span>
<?php
$x_medio_pago_idList = "<select name=\"x_medio_pago_id\">";
$x_medio_pago_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT `medio_pago_id`, `descripcion` FROM `medio_pago`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_medio_pago_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["medio_pago_id"] == @$x_medio_pago_id) {
			$x_medio_pago_idList .= "' selected";
		}
		$x_medio_pago_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_medio_pago_idList .= "</select>";
echo $x_medio_pago_idList;
?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Referencia de Pago</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_referencia" id="x_referencia" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_referencia) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Descuento</span></td>
		<td class="ewTableAltRow"><span>
		<?php echo FormatNumber(@$x_factor,2,0,0,0) ?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Importe</span></td>
		<td class="ewTableAltRow"><span>
		<?php echo FormatNumber(@$x_importe,2,0,0,1) ?>		
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Status</span></td>
		<td class="ewTableAltRow"><span>
<?php
$x_devolucion_status_idList = "<select name=\"x_devolucion_status_id\">";
$x_devolucion_status_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT devolucion_status_id, descripcion FROM devolucion_status where devolucion_status_id = $x_devolucion_status_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_devolucion_status_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["devolucion_status_id"] == @$x_devolucion_status_id) {
			$x_devolucion_status_idList .= "' selected";
		}
		$x_devolucion_status_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_devolucion_status_idList .= "</select>";
echo $x_devolucion_status_idList;
?>
</span></td>
	</tr>
</table>
<p>
<input type="submit" name="Action" value="<?php if($x_devolucion_status_id == 2){echo "Aplicar Pago";}else{echo "Cancelar";}?>">
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
	global $x_devolucion_id;
	$sSql = "SELECT * FROM `devolucion`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_devolucion_id) : $x_devolucion_id;
	$sWhere .= "(`devolucion_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_devolucion_id"] = $row["devolucion_id"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
		$GLOBALS["x_fecha_pago"] = $row["fecha_pago"];
		$GLOBALS["x_medio_pago_id"] = $row["medio_pago_id"];
		$GLOBALS["x_referencia"] = $row["referencia"];
		$GLOBALS["x_factor"] = $row["factor"];
		$GLOBALS["x_importe"] = $row["importe"];
//		$GLOBALS["x_devolucion_status_id"] = $row["devolucion_status_id"];
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
	global $x_devolucion_id;
	$sSql = "SELECT * FROM `devolucion`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_devolucion_id) : $x_devolucion_id;	
	$sWhere .= "(`devolucion_id` = " . addslashes($sTmp) . ")";
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
		$theValue = ($GLOBALS["x_fecha_pago"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_pago"]) . "'" : "Null";
		$fieldList["`fecha_pago`"] = $theValue;
		$theValue = ($GLOBALS["x_medio_pago_id"] != "") ? intval($GLOBALS["x_medio_pago_id"]) : "NULL";
		$fieldList["`medio_pago_id`"] = $theValue;
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia"]) : $GLOBALS["x_referencia"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`referencia`"] = $theValue;
		$theValue = ($GLOBALS["x_devolucion_status_id"] != "") ? intval($GLOBALS["x_devolucion_status_id"]) : "NULL";
		$fieldList["`devolucion_status_id`"] = $theValue;

		// update
		$sSql = "UPDATE `devolucion` SET ";
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
