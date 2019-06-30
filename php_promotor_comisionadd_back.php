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
$x_promotor_comision_id = Null; 
$ox_promotor_comision_id = Null;
$x_promotor_id = Null; 
$ox_promotor_id = Null;
$x_solicitud_id = Null; 
$ox_solicitud_id = Null;
$x_fecha_registro = Null; 
$ox_fecha_registro = Null;
$x_comision = Null; 
$ox_comision = Null;
$x_comision_importe = Null; 
$ox_comision_importe = Null;
$x_referencia = Null; 
$ox_referencia = Null;
$x_promotor_comision_status_id = Null; 
$ox_promotor_comision_status_id = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
$x_promotor_comision_id = @$_GET["promotor_comision_id"];
if (empty($x_promotor_comision_id)) {
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
	$x_promotor_comision_id = @$_POST["x_promotor_comision_id"];
	$x_promotor_id = @$_POST["x_promotor_id"];
	$x_solicitud_id = @$_POST["x_solicitud_id"];
	$x_vencimiento_id = @$_POST["x_vencimiento_id"];	
	$x_fecha_registro = @$_POST["x_fecha_registro"];
	$x_comision = @$_POST["x_comision"];
	$x_comision_importe = @$_POST["x_comision_importe"];
	$x_referencia = @$_POST["x_referencia"];
	$x_promotor_comision_status_id = @$_POST["x_promotor_comision_status_id"];
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No se localizaron los datos";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_promotor_comisionlist.php");
			exit();
		}
		break;
	case "A": // Add
		//VALIDA PAGO 
		$sSql = "select count(*) as pagada
		from promotor_comision
		where 
		promotor_comision.promotor_id = $x_promotor_id and 
		promotor_comision.solicitud_id = $x_solicitud_id and 
		promotor_comision.vencimiento_id = $x_vencimiento_id and
		promotor_comision.promotor_comision_status_id in (1,2)
		";
		$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		phpmkr_num_rows($rswrk);
		$rowwrk = phpmkr_fetch_array($rswrk);
		$x_pagada = $rowwrk["pagada"];			
		phpmkr_free_result($rswrk);		
		
		if($x_pagada > 0){
			$_SESSION["ewmsg"] = "La comision ya se encontraba pagada, si desea modificarla primero cancele la exsistente.";						
		}else{
			if (AddData($conn)) { // Add New Record
				$_SESSION["ewmsg"] = "Los datos han sido registrados.";
				phpmkr_db_close($conn);
				ob_end_clean();
				header("Location: php_promotor_comisionlist.php");
				exit();
			}
		}
		break;
	case "N": // Add
		$x_solicitud_id = "";
		$x_vencimiento_id = "";
		break;		
	case "L": // Add
		$sSql = "select promotor.promotor_tipo_id, promotor.comision, credito.credito_id, credito.credito_status_id, credito.importe 
		from promotor join solicitud
		on solicitud.promotor_id = promotor.promotor_id join credito
		on credito.solicitud_id = solicitud.solicitud_id
		where promotor.promotor_id = $x_promotor_id and solicitud.solicitud_id = $x_solicitud_id";
		$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		if(phpmkr_num_rows($rswrk) > 0){
			$rowwrk = phpmkr_fetch_array($rswrk);
			$x_promotor_tipo_id = $rowwrk["promotor_tipo_id"];			
			$x_credito_id = $rowwrk["credito_id"];						
			$x_credito_status_id = $rowwrk["credito_status_id"];									
			$x_comision = $rowwrk["comision"];
			$x_importe = $rowwrk["importe"];
			$x_comision_importe = $x_importe * ($x_comision / 100);
		}else{
			$x_solicitud_id = 0;
			$_SESSION["ewmsg"] = "No fue localizado el credito de la solicitud seleccionada.";								
		}
		phpmkr_free_result($rswrk);

		break;		
	case "V": // Add
		$sSql = "select * from vencimiento where vencimiento_id = $x_vencimiento_id";
		$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		if(phpmkr_num_rows($rswrk) > 0){
			$rowwrk = phpmkr_fetch_array($rswrk);
			$x_importe = $rowwrk["importe"];
			$x_comision_importe = $x_importe * ($x_comision / 100);
		}else{
			$x_vencimiento_id = 0;
			$_SESSION["ewmsg"] = "No fue localizado el vecncimiento del credito seleccionado.";								
		}
		phpmkr_free_result($rswrk);

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
function promoloc(){

EW_this = document.promotorcomisionadd;
validada = true;


if (EW_this.x_promotor_id && !EW_hasValue(EW_this.x_promotor_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_promotor_id, "SELECT", "El promotor es requerido."))
		validada = false;
}

if(validada == true){
	EW_this.a_add.value = "N";
	EW_this.submit();
}

}


function solicitudloc(){

EW_this = document.promotorcomisionadd;
validada = true;


if (EW_this.x_solicitud_id && !EW_hasValue(EW_this.x_solicitud_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_solicitud_id, "SELECT", "El folio es requerido."))
		validada = false;
}

if(validada == true){
	EW_this.a_add.value = "L";
	EW_this.submit();
}

}


function vencimientoloc(){
EW_this = document.promotorcomisionadd;
validada = true;


if (EW_this.x_vencimiento_id && !EW_hasValue(EW_this.x_vencimiento_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_vencimiento_id, "SELECT", "El vencimiento es requerido."))
		validada = false;
}

if(validada == true){
	EW_this.a_add.value = "V";
	EW_this.submit();
}

}


function EW_checkMyForm() {

EW_this = document.promotorcomisionadd;
validada = true;

if (EW_this.x_promotor_id && !EW_hasValue(EW_this.x_promotor_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_promotor_id, "SELECT", "El promotor es requerido."))
		validada = false;
}
if (validada == true && EW_this.x_solicitud_id && !EW_hasValue(EW_this.x_solicitud_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_solicitud_id, "SELECT", "El folio es requerido."))
		validada = false;
}
if (validada == true && EW_this.x_vencimiento_id && !EW_hasValue(EW_this.x_vencimiento_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_vencimiento_id, "SELECT", "El vencimiento es requerido."))
		validada = false;
}
if (validada == true && EW_this.x_fecha_registro && !EW_hasValue(EW_this.x_fecha_registro, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_registro, "TEXT", "La fecha de registro es requerida."))
		validada = false;
}
if (validada == true && EW_this.x_fecha_registro && !EW_checkeurodate(EW_this.x_fecha_registro.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_registro, "TEXT", "La fecha de registro es requerida."))
		validada = false;
}
if (validada == true && EW_this.x_comision && !EW_hasValue(EW_this.x_comision, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_comision, "TEXT", "La comision es requerida."))
		validada = false;
}
if (validada == true && EW_this.x_comision && !EW_checknumber(EW_this.x_comision.value)) {
	if (!EW_onError(EW_this, EW_this.x_comision, "TEXT", "La comision es requerida."))
		validada = false;
}
if (validada == true && EW_this.x_comision_importe && !EW_hasValue(EW_this.x_comision_importe, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_comision_importe, "TEXT", "El importe es requrido."))
		validada = false;
}
if (validada == true && EW_this.x_comision_importe && !EW_checknumber(EW_this.x_comision_importe.value)) {
	if (!EW_onError(EW_this, EW_this.x_comision_importe, "TEXT", "El importe es requrido."))
		validada = false;
}
if (validada == true && EW_this.x_referencia && !EW_hasValue(EW_this.x_referencia, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_referencia, "TEXT", "La referencia es requerida."))
		validada = false;
}
if (validada == true && EW_this.x_promotor_comision_status_id && !EW_hasValue(EW_this.x_promotor_comision_status_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_promotor_comision_status_id, "SELECT", "El status es requerido."))
		validada = false;
}

if(validada == true){
	EW_this.a_add.value = "A";
	EW_this.submit();
}

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
<p><span class="phpmaker">Comisiones<br>
  <br>
    <a href="php_promotor_comisionlist.php">Regresar a la lista</a></span></p>
<form name="promotorcomisionadd" id="promotorcomisionadd" action="php_promotor_comisionadd.php" method="post" >
<p>
<input type="hidden" name="a_add" value="A">
<input type="hidden" name="x_importe" value="<?php echo $x_importe; ?>"  />
<input type="hidden" name="x_credito_id" value="<?php echo $x_credito_id; ?>"  />

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
		<td width="129" class="ewTableHeaderThin"><span>Promotor</span></td>
		<td width="659" class="ewTableAltRow"><span>
<?php if (!(!is_null($x_promotor_id)) || ($x_promotor_id == "")) { $x_promotor_id = 0;} // Set default value ?>
<?php
$x_promotor_idList = "<select name=\"x_promotor_id\" onchange=\"promoloc()\">";
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



<?php if ($x_promotor_id > 0){ // Set default value ?>
	
	<tr>
		<td class="ewTableHeaderThin"><span>Cr&eacute;dito No.</span></td>
		<td class="ewTableAltRow"><span>
<?php 
if (!(!is_null($x_solicitud_id)) || ($x_solicitud_id == "")) { $x_solicitud_id = 0;} // Set default value 
$x_solicitud_idList = "<select name=\"x_solicitud_id\" onchange=\"solicitudloc()\">";
$x_solicitud_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT solicitud_id, folio FROM solicitud where promotor_id = $x_promotor_id and solicitud.solicitud_status_id = 6";
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


<?php if ($x_solicitud_id > 0){ // Set default value ?>
	
	<tr>
	  <td class="ewTableHeaderThin">Vencimiento</td>
	  <td class="ewTableAltRow">
<?php 
if ($x_promotor_tipo_id == 1){
	if($x_credito_status_id <> "3"){
		echo "
		<select name=\"x_vencimiento_id\">
		<option value=''>EL credito aun no ha sido liquidado</option>
		</select>
		";
	}else{
		echo "
		<select name=\"x_vencimiento_id\">
		<option value='0'>Todos</option>
		</select>
		";
	}
}else{
	$x_solicitud_idList = "<select name=\"x_vencimiento_id\" onchange=\"vencimientoloc()\">";
	$x_solicitud_idList .= "<option value=''>Seleccione</option>";
	$sSqlWrk = "SELECT vencimiento_id, vencimiento_num FROM vencimiento where credito_id = $x_credito_id and vencimiento_status_id = 2";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk) {
		$rowcntwrk = 0;
		while ($datawrk = phpmkr_fetch_array($rswrk)) {
			$x_solicitud_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
			if ($datawrk["vencimiento_id"] == @$x_vencimiento_id) {
				$x_solicitud_idList .= "' selected";
			}
			$x_solicitud_idList .= ">" . $datawrk["vencimiento_num"] . "</option>";
			$rowcntwrk++;
		}
	}
	@phpmkr_free_result($rswrk);
	$x_solicitud_idList .= "</select>";
	echo $x_solicitud_idList;
}	
?></td>
	  </tr>
	<tr>
	  <td class="ewTableHeaderThin">Importe</td>
	  <td class="ewTableAltRow">
	  <?php echo FormatNumber($x_importe,2,0,0,1); ?>
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
		<td class="ewTableHeaderThin"><span>Comision %</span></td>
		<td class="ewTableAltRow"><span>
<input name="x_comision" type="text" id="x_comision" value="<?php echo FormatNumber(@$x_comision,2,0,0,0) ?>" size="6" maxlength="5">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin">Comision $ </td>
		<td class="ewTableAltRow"><span>
<input style="text-align:right" name="x_comision_importe" type="text" id="x_comision_importe" value="<?php echo FormatNumber(@$x_comision_importe,2,0,0,1) ?>" size="12" maxlength="10">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Referencia</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_referencia" id="x_referencia" size="30" maxlength="150" value="<?php echo htmlspecialchars(@$x_referencia) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Status</span></td>
		<td class="ewTableAltRow"><span>
<?php if (!(!is_null($x_promotor_comision_status_id)) || ($x_promotor_comision_status_id == "")) { $x_promotor_comision_status_id = 0;} // Set default value ?>
<?php
$x_promotor_comision_status_idList = "<select name=\"x_promotor_comision_status_id\">";
$x_promotor_comision_status_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT promotor_comision_status_id, descripcion FROM promotor_comision_status where promotor_comision_status_id = 1";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_promotor_comision_status_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["promotor_comision_status_id"] == 1) {
			$x_promotor_comision_status_idList .= "' selected";
		}
		$x_promotor_comision_status_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_promotor_comision_status_idList .= "</select>";
echo $x_promotor_comision_status_idList;
?>
</span></td>
	</tr>

<?php } ?>
<?php } ?>
</table>
<p>
<?php if ($x_promotor_id > 0 && $x_solicitud_id > 0){ // Set default value ?>
<input type="button"name="Action" value="Agregar" onclick="EW_checkMyForm()">
<?php } ?>
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
	global $x_promotor_comision_id;
	$sSql = "SELECT * FROM `promotor_comision`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_promotor_comision_id) : $x_promotor_comision_id;
	$sWhere .= "(`promotor_comision_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_promotor_comision_id"] = $row["promotor_comision_id"];
		$GLOBALS["x_promotor_id"] = $row["promotor_id"];
		$GLOBALS["x_solicitud_id"] = $row["solicitud_id"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
		$GLOBALS["x_comision"] = $row["comision"];
		$GLOBALS["x_comision_importe"] = $row["comision_importe"];
		$GLOBALS["x_referencia"] = $row["referencia"];
		$GLOBALS["x_promotor_comision_status_id"] = $row["promotor_comision_status_id"];
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
	global $x_promotor_comision_id;
	$sSql = "SELECT * FROM `promotor_comision`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";

	// Check for duplicate key
	$bCheckKey = true;
	$sWhereChk = $sWhere;
	if ((@$x_promotor_comision_id == "") || (is_null($x_promotor_comision_id))) {
		$bCheckKey = false;
	} else {
		if ($sWhereChk <> "") { $sWhereChk .= " AND "; }
		$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_promotor_comision_id) : $x_promotor_comision_id;			
		$sWhereChk .= "(`promotor_comision_id` = " . addslashes($sTmp) . ")";
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

	// Field promotor_id
	$theValue = ($GLOBALS["x_promotor_id"] != "") ? intval($GLOBALS["x_promotor_id"]) : "NULL";
	$fieldList["`promotor_id`"] = $theValue;

	// Field solicitud_id
	$theValue = ($GLOBALS["x_solicitud_id"] != "") ? intval($GLOBALS["x_solicitud_id"]) : "NULL";
	$fieldList["`solicitud_id`"] = $theValue;

	// Field solicitud_id
	$theValue = ($GLOBALS["x_vencimiento_id"] != "") ? intval($GLOBALS["x_vencimiento_id"]) : "0";
	$fieldList["`vencimiento_id`"] = $theValue;

	// Field fecha_registro
	$theValue = ($GLOBALS["x_fecha_registro"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_registro"]) . "'" : "Null";
	$fieldList["`fecha_registro`"] = $theValue;

	// Field comision
	$theValue = ($GLOBALS["x_comision"] != "") ? " '" . doubleval($GLOBALS["x_comision"]) . "'" : "NULL";
	$fieldList["`comision`"] = $theValue;

	// Field comision_importe
	$theValue = ($GLOBALS["x_comision_importe"] != "") ? " '" . doubleval($GLOBALS["x_comision_importe"]) . "'" : "NULL";
	$fieldList["`comision_importe`"] = $theValue;

	// Field referencia
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia"]) : $GLOBALS["x_referencia"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`referencia`"] = $theValue;

	// Field promotor_comision_status_id
	$theValue = ($GLOBALS["x_promotor_comision_status_id"] != "") ? intval($GLOBALS["x_promotor_comision_status_id"]) : "NULL";
	$fieldList["`promotor_comision_status_id`"] = $theValue;

	// insert into database
	$sSql = "INSERT INTO `promotor_comision` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	return true;
}
?>
