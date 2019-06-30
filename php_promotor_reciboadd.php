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
$x_promotor_recibo_id = Null; 
$ox_promotor_recibo_id = Null;
$x_promotor_comision_id = Null; 
$ox_promotor_comision_id = Null;
$x_fecha_registro = Null; 
$ox_fecha_registro = Null;
$x_fecha_pago = Null; 
$ox_fecha_pago = Null;
$x_medio_pago_id = Null; 
$ox_medio_pago_id = Null;
$x_referencia_pago = Null; 
$ox_referencia_pago = Null;
$x_importe = Null; 
$ox_importe = Null;
$x_promotor_recibo_status_id = Null; 
$ox_promotor_recibo_status_id = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);


$x_promotor_comision_id = @$_GET["promotor_comision_id"];
if (empty($x_promotor_comision_id)) {
	$x_promotor_comision_id = @$_POST["x_promotor_comision_id"];
	if (empty($x_promotor_comision_id)) {
		echo "No se localizaron los datos de la comsiion.<br><br>";
		echo "<a href=\"php_promotor_comsiionlist.php\">Regresar a la lista</a></span></p>";
		exit();
	}
}

// Get action
$sAction = @$_POST["a_add"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I"; // Display blank record

		$sSql = "select promotor.nombre_completo, promotor_comision.comision_importe, promotor_comision.credito_id
		from promotor_comision join promotor
		on promotor.promotor_id = promotor_comision.promotor_id 
		where promotor_comision.promotor_comision_id = $x_promotor_comision_id";
		$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		if(phpmkr_num_rows($rswrk) > 0){
			$rowwrk = phpmkr_fetch_array($rswrk);
			$x_promotor = $rowwrk["nombre_completo"];
			$x_importe = $rowwrk["comision_importe"];
			$x_credito_id = $rowwrk["credito_id"];
			
			$sSql = "select credito_num	from credito where credito_id = $x_credito_id";
			$rswrk2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$rowwrk2 = phpmkr_fetch_array($rswrk2);
			$x_credito_num = $rowwrk2["credito_num"];
			phpmkr_free_result($rswrk2);			
		}else{
			$x_solicitud_id = 0;
			$_SESSION["ewmsg"] = "No fueron localizados los datos de la comision.";								
		}
		phpmkr_free_result($rswrk);
	
}else{

	// Get fields from form
	$x_promotor_recibo_id = @$_POST["x_promotor_recibo_id"];
//	$x_promotor_comision_id = @$_POST["x_promotor_comision_id"];
	$x_fecha_registro = @$_POST["x_fecha_registro"];
	$x_fecha_pago = @$_POST["x_fecha_pago"];
	$x_medio_pago_id = @$_POST["x_medio_pago_id"];
	$x_referencia_pago = @$_POST["x_referencia_pago"];
	$x_importe = @$_POST["x_importe"];
	$x_promotor_recibo_status_id = @$_POST["x_promotor_recibo_status_id"];
}

switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron lo datos";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_promotor_comisionlist.php");
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "El pago ha sido aplicado.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_promotor_comisionlist.php");
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
if (EW_this.x_promotor_comision_id && !EW_hasValue(EW_this.x_promotor_comision_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_promotor_comision_id, "SELECT", "El folio es requerido."))
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
if (EW_this.x_fecha_pago && !EW_hasValue(EW_this.x_fecha_pago, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_pago, "TEXT", "La fecha de pago es requerida."))
		return false;
}
if (EW_this.x_fecha_pago && !EW_checkeurodate(EW_this.x_fecha_pago.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_pago, "TEXT", "La fecha de pago es requerida."))
		return false; 
}
if (EW_this.x_medio_pago_id && !EW_hasValue(EW_this.x_medio_pago_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_medio_pago_id, "SELECT", "El Medio de pago es requerido."))
		return false;
}
if (EW_this.x_referencia_pago && !EW_hasValue(EW_this.x_referencia_pago, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_referencia_pago, "TEXT", "La referencia de pago es requerida."))
		return false;
}
if (EW_this.x_importe && !EW_hasValue(EW_this.x_importe, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_importe, "TEXT", "El importe es requerido."))
		return false;
}
if (EW_this.x_importe && !EW_checknumber(EW_this.x_importe.value)) {
	if (!EW_onError(EW_this, EW_this.x_importe, "TEXT", "El importe es requerido."))
		return false; 
}
if (EW_this.x_promotor_recibo_status_id && !EW_hasValue(EW_this.x_promotor_recibo_status_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_promotor_recibo_status_id, "SELECT", "El status del pago es requerido."))
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
<script type="text/javascript" src="jscalendar/lang/calendar-sp.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<p><span class="phpmaker">Pagos de Comision<br>
  <br>
    <a href="php_promotor_comisionlist.php">Regresar a la lista</a></span></p>
<form name="promotor_reciboadd" id="promotor_reciboadd" action="php_promotor_reciboadd.php" method="post" onSubmit="return EW_checkMyForm(this);">
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
<table class="ewTable_small">
	<tr>
		<td width="163" class="ewTableHeaderThin"><span>Comision No.</span></td>
		<td width="825" class="ewTableAltRow"><span>
<?php
echo $x_promotor_comision_id;
?>
<input type="hidden" name="x_promotor_comision_id" value="<?php echo $x_promotor_comision_id; ?>"  />
</span></td>
	</tr>
	<tr>
	  <td class="ewTableHeaderThin">Promotor</td>
	  <td class="ewTableAltRow">
	  <span>
	  <?php echo $x_promotor; ?>
	  </span>
	  </td>
	  </tr>
	<tr>
	  <td class="ewTableHeaderThin">Credito Num.</td>
	  <td class="ewTableAltRow">
	  <span>
	  <?php echo $x_credito_num; ?>
	  </span>
	  </td>
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
		<td class="ewTableHeaderThin"><span>Fecha de pago</span></td>
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
<?php if (!(!is_null($x_medio_pago_id)) || ($x_medio_pago_id == "")) { $x_medio_pago_id = 0;} // Set default value ?>
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
		<td class="ewTableHeaderThin"><span>Referencia de pago</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_referencia_pago" id="x_referencia_pago" size="30" maxlength="50" value="<?php echo htmlspecialchars(@$x_referencia_pago) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Importe</span></td>
		<td class="ewTableAltRow"><span>
		<?php echo FormatNumber(@$x_importe,2,0,0,1) ?>
		<input type="hidden" name="x_importe" value="<?php echo $x_importe; ?>"  />
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Status</span></td>
		<td class="ewTableAltRow"><span>
<?php if (!(!is_null($x_promotor_recibo_status_id)) || ($x_promotor_recibo_status_id == "")) { $x_promotor_recibo_status_id = 0;} // Set default value ?>
<?php
$x_promotor_recibo_status_idList = "<select name=\"x_promotor_recibo_status_id\">";
$x_promotor_recibo_status_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT `promotor_recibo_status_id`, `descripcion` FROM `promotor_recibo_status`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_promotor_recibo_status_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["promotor_recibo_status_id"] == @$x_promotor_recibo_status_id) {
			$x_promotor_recibo_status_idList .= "' selected";
		}
		$x_promotor_recibo_status_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_promotor_recibo_status_idList .= "</select>";
echo $x_promotor_recibo_status_idList;
?>
</span></td>
	</tr>
</table>
<p>
<input type="submit" name="Action" value="Aplicar Pago">
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
	global $x_promotor_recibo_id;
	$sSql = "SELECT * FROM `promotor_recibo`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_promotor_recibo_id) : $x_promotor_recibo_id;
	$sWhere .= "(`promotor_recibo_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_promotor_recibo_id"] = $row["promotor_recibo_id"];
		$GLOBALS["x_promotor_comision_id"] = $row["promotor_comision_id"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
		$GLOBALS["x_fecha_pago"] = $row["fecha_pago"];
		$GLOBALS["x_medio_pago_id"] = $row["medio_pago_id"];
		$GLOBALS["x_referencia_pago"] = $row["referencia_pago"];
		$GLOBALS["x_importe"] = $row["importe"];
		$GLOBALS["x_promotor_recibo_status_id"] = $row["promotor_recibo_status_id"];
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
	global $x_promotor_recibo_id;
	$sSql = "SELECT * FROM `promotor_recibo`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";

	// Check for duplicate key
	$bCheckKey = true;
	$sWhereChk = $sWhere;
	if ((@$x_promotor_recibo_id == "") || (is_null($x_promotor_recibo_id))) {
		$bCheckKey = false;
	} else {
		if ($sWhereChk <> "") { $sWhereChk .= " AND "; }
		$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_promotor_recibo_id) : $x_promotor_recibo_id;			
		$sWhereChk .= "(`promotor_recibo_id` = " . addslashes($sTmp) . ")";
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

	// Field promotor_comision_id
	$theValue = ($GLOBALS["x_promotor_comision_id"] != "") ? intval($GLOBALS["x_promotor_comision_id"]) : "NULL";
	$fieldList["`promotor_comision_id`"] = $theValue;

	// Field fecha_registro
	$theValue = ($GLOBALS["x_fecha_registro"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_registro"]) . "'" : "Null";
	$fieldList["`fecha_registro`"] = $theValue;

	// Field fecha_pago
	$theValue = ($GLOBALS["x_fecha_pago"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_pago"]) . "'" : "Null";
	$fieldList["`fecha_pago`"] = $theValue;

	// Field medio_pago_id
	$theValue = ($GLOBALS["x_medio_pago_id"] != "") ? intval($GLOBALS["x_medio_pago_id"]) : "NULL";
	$fieldList["`medio_pago_id`"] = $theValue;

	// Field referencia_pago
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_pago"]) : $GLOBALS["x_referencia_pago"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`referencia_pago`"] = $theValue;

	// Field importe
	$theValue = ($GLOBALS["x_importe"] != "") ? " '" . doubleval($GLOBALS["x_importe"]) . "'" : "NULL";
	$fieldList["`importe`"] = $theValue;

	// Field promotor_recibo_status_id
	$theValue = ($GLOBALS["x_promotor_recibo_status_id"] != "") ? intval($GLOBALS["x_promotor_recibo_status_id"]) : "NULL";
	$fieldList["`promotor_recibo_status_id`"] = $theValue;

	// insert into database
	$sSql = "INSERT INTO `promotor_recibo` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);

	$sSql = "update promotor_comision set promotor_comision_status_id = 2 where promotor_comision_id = ".$GLOBALS["x_promotor_comision_id"];
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	
	return true;
}
?>
