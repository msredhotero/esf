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

// Load key from QueryString
$x_promotor_comision_id = @$_GET["promotor_comision_id"];

//if (!empty($x_promotor_comision_id )) $x_promotor_comision_id  = (get_magic_quotes_gpc()) ? stripslashes($x_promotor_comision_id ) : $x_promotor_comision_id ;
// Get action

$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I";	// Display with input box
} else {

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

// Check if valid key
if (($x_promotor_comision_id == "") || (is_null($x_promotor_comision_id))) {
	ob_end_clean();
	header("Location: php_promotor_comisionlist.php");
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
			header("Location: php_promotor_comisionlist.php");
			exit();
		}
		break;
	case "U": // Update
		if (EditData($conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "La comision ha sido cancelada.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_promotor_comisionlist.php");
			exit();
		}
		break;
	case "C": // Update
		$sSql = "select credito.importe 
		from solicitud join credito
		on credito.solicitud_id = solicitud.solicitud_id
		where solicitud.solicitud_id = $x_solicitud_id";
		$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		if(phpmkr_num_rows($rswrk) > 0){
			$rowwrk = phpmkr_fetch_array($rswrk);
			$x_importe = $rowwrk["importe"];
			$x_comision_importe = $x_importe * ($x_comision / 100);
		}else{
			$x_solicitud_id = 0;
			$_SESSION["ewmsg"] = "No fue localizado el credito de la solicitud.";
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
function calculacomision(){
EW_this = document.promotor_comisionedit;
validada = true;

if (EW_this.x_comision && !EW_hasValue(EW_this.x_comision, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_comision, "TEXT", "El porcentaje de comision es requerida."))
		validada = false;
}
if (EW_this.x_comision && !EW_checknumber(EW_this.x_comision.value)) {
	if (!EW_onError(EW_this, EW_this.x_comision, "TEXT", "El porcentaje de comision es incorrecto."))
		validada = false;
}

if(validada == true){
	EW_this.a_edit.value = "C";
	EW_this.submit();
}

}



function EW_checkMyForm(EW_this) {
if (EW_this.x_comision && !EW_hasValue(EW_this.x_comision, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_comision, "TEXT", "La comision es requerida."))
		return false;
}
if (EW_this.x_comision && !EW_checknumber(EW_this.x_comision.value)) {
	if (!EW_onError(EW_this, EW_this.x_comision, "TEXT", "La comision es requerida."))
		return false; 
}
if (EW_this.x_comision_importe && !EW_hasValue(EW_this.x_comision_importe, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_comision_importe, "TEXT", "El importe es requrido."))
		return false;
}
if (EW_this.x_comision_importe && !EW_checknumber(EW_this.x_comision_importe.value)) {
	if (!EW_onError(EW_this, EW_this.x_comision_importe, "TEXT", "El importe es requrido."))
		return false; 
}
if (EW_this.x_referencia && !EW_hasValue(EW_this.x_referencia, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_referencia, "TEXT", "La referencia es requerida."))
		return false;
}
if (EW_this.x_promotor_comision_status_id && !EW_hasValue(EW_this.x_promotor_comision_status_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_promotor_comision_status_id, "SELECT", "El status es requerido."))
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
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-1.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<p><span class="phpmaker">Cancelar Comision<br>
    <br>
    <a href="php_promotor_comisionlist.php">Regresar a la lista</a></span></p>
<form name="promotor_comisionedit" id="promotor_comisionedit" action="php_promotor_comisioncancelar.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<input type="hidden" name="x_solicitud_id" value="<?php echo $x_solicitud_id; ?>"  />
<input type="hidden" name="x_promotor_id" value="<?php echo $x_promotor_id; ?>"  />
<input type="hidden" name="x_fecha_registro" value="<?php echo $x_fecha_registro; ?>"  />
<table class="ewTable_small">
	<tr>
		<td width="115" class="ewTableHeaderThin"><span>No</span></td>
		<td width="673" class="ewTableAltRow"><span>
<?php echo $x_promotor_comision_id; ?>
<input type="hidden" id="x_promotor_comision_id" name="x_promotor_comision_id" value="<?php echo htmlspecialchars(@$x_promotor_comision_id); ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Promotor</span></td>
		<td class="ewTableAltRow"><span>
<?php
$sSqlWrk = "SELECT promotor_id, nombre_completo FROM promotor where promotor_id = $x_promotor_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
echo $datawrk["nombre_completo"];
@phpmkr_free_result($rswrk);
?>
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Credito No.</span></td>
		<td class="ewTableAltRow"><span>
<?php
$sSqlWrk = "
SELECT credito_num FROM credito where credito_id = $x_credito_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
echo $datawrk["credito_num"];
@phpmkr_free_result($rswrk);
?>
</span></td>
	</tr>
	
	<tr>
		<td class="ewTableHeaderThin"><span>Fecha de registro</span></td>
		<td class="ewTableAltRow"><span>
<?php echo FormatDateTime(@$x_fecha_registro,7); ?>		
</span></td>
	</tr>
	
	<tr>
		<td class="ewTableHeaderThin"><span>Importe</span></td>
		<td class="ewTableAltRow"><span>
		<?php echo FormatNumber(@$x_comision_importe,2,0,0,1) ?>		
<input name="x_comision_importe" type="hidden" id="x_comision_importe" value="<?php echo FormatNumber(@$x_comision_importe,2,0,0,1) ?>" >
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Referencia</span></td>
		<td class="ewTableAltRow"><span>
<?php echo htmlspecialchars(@$x_referencia) ?>		
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Status</span></td>
		<td class="ewTableAltRow"><span>
<?php
$sSqlWrk = "SELECT promotor_comision_status_id, descripcion FROM promotor_comision_status where promotor_comision_status_id <> 2";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
echo $datawrk["descripcion"];
@phpmkr_free_result($rswrk);
?>
</span></td>
	</tr>
</table>
<p>
<input type="submit" name="Action" value="CANCELAR">
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
		$GLOBALS["x_credito_id"] = $row["credito_id"];
		$GLOBALS["x_vencimiento_id"] = $row["vencimiento_id"];		
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
// Function EditData
// - Edit Data based on Key Value sKey
// - Variables used: field variables

function EditData($conn)
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
		$bEditData = false; // Update Failed
	}else{
		$fieldList["`promotor_comision_status_id`"] = 3;

		// update
		$sSql = "UPDATE `promotor_comision` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE " . $sWhere;
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		
		
		$sSql = "UPDATE promotor_recibo SET promotor_recibo_status_id = 2 where promotor_comision_id = $x_promotor_comision_id";
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		
		
		
		$bEditData = true; // Update Successful
	}
	return $bEditData;
}
?>
