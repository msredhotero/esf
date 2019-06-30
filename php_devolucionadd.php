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
$x_factor = 60.00; 
$ox_factor = Null;
$x_importe = Null; 
$ox_importe = Null;
$x_devolucion_status_id = Null; 
$ox_devolucion_status_id = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
$x_devolucion_id = @$_GET["devolucion_id"];
if (empty($x_devolucion_id)) {
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
	$x_devolucion_id = @$_POST["x_devolucion_id"];
	$x_fecha_registro = @$_POST["x_fecha_registro"];
	$x_fecha_pago = @$_POST["x_fecha_pago"];
	$x_medio_pago_id = @$_POST["x_medio_pago_id"];
	$x_referencia = @$_POST["x_referencia"];
//	$x_factor = @$_POST["x_factor"];
	$x_importe = @$_POST["x_importe"];
	$x_devolucion_status_id = @$_POST["x_devolucion_status_id"];
	$x_credito_id = @$_POST["x_credito_id"];	
	$x_vencimientos_id = @$_POST["x_vencimientos_id"];		
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No records found";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_devolucionlist.php");
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "La devolucion ha sido registrada.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_devolucionlist.php");
			exit();
		}
		break;
	case "L": // Add
		$x_ref_loc = @$_POST["x_ref_loc"];	
		if(strlen($x_ref_loc) == ""){
			$_SESSION["ewmsg"] = "El folio no es valido, verifiquelo.";				
		}else{
			$sSql = "select credito.credito_id, cliente.nombre_completo 
			from credito join solicitud
			on solicitud.solicitud_id = credito.solicitud_id join solicitud_cliente
			on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente 
			on cliente.cliente_id = solicitud_cliente.cliente_id
			where solicitud.folio = '$x_ref_loc'";
			$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			if(phpmkr_num_rows($rswrk) > 0){
				$rowwrk = phpmkr_fetch_array($rswrk);
				$x_credito_id = $rowwrk["credito_id"];
				$x_nombre_completo = $rowwrk["nombre_completo"];	


				$sSql = "select * from vencimiento join credito
				on credito.credito_id = vencimiento.credito_id
				where credito.credito_id = $x_credito_id and credito.credito_status_id = 3 and vencimiento.vencimiento_status_id = 5 ";
				$rswrk2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
				if(phpmkr_num_rows($rswrk2) > 0){
					$x_vencimientos_id = "";				
					$x_total = 0;
					while ($rowwrk2 = phpmkr_fetch_array($rswrk2)){
						$sSql = "select count(*) as devpag from devolucion_vencimiento join devolucion
						on devolucion.devolucion_id = devolucion_vencimiento.devolucion_id
						where devolucion_vencimiento.vencimiento_id = ".$rowwrk2["vencimiento_id"]." and devolucion.devolucion_status_id <> 3 ";
						$rswrk3 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
						$rowwrk3 = phpmkr_fetch_array($rswrk3);
						if($rowwrk3["devpag"] == 0){
							$x_vencimientos_id .= $rowwrk2["vencimiento_id"].",";
							$x_referencia .= $rowwrk2["vencimiento_num"]." (". $rowwrk2["fecha_vencimiento"] . "), ";
							$x_total = $x_total + round($rowwrk2["interes"] * ($x_factor / 100));						
						}
						phpmkr_free_result($rswrk3);
					}
					if($x_total == 0){
						$x_credito_id = null;
						$x_vencimientos_id = null;
						$_SESSION["ewmsg"] = "El credito ya tiene registradas devoluciones por pagos anticipados.";													
					}else{
						$x_vencimientos_id = substr($x_vencimientos_id,0, strlen($x_vencimientos_id) - 1);
						$x_referencia = "Venc: ".substr($x_referencia,0, strlen($x_referencia) - 2);						
					}
				}else{
					$_SESSION["ewmsg"] = "El credito no tiene pagos anticipados, o no ha sido finiquitado.";								
				}
			}else{
				$_SESSION["ewmsg"] = "La referencia no fue localizada, verifiquela.";								
			}
			phpmkr_free_result($rswrk);
			phpmkr_free_result($rswrk2);			
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

function refloc(){
EW_this = document.devolucionadd;
validada = true;


	if (EW_this.x_ref_loc && !EW_hasValue(EW_this.x_ref_loc, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_ref_loc, "TEXT", "El Folio es requerido."))
		validada = false;
	}

	if(validada == true){
		EW_this.a_add.value = "L";
		EW_this.submit();
	}
}

function EW_checkMyForm(EW_this) {
EW_this = document.devolucionadd;
validada = true;

if (EW_this.x_fecha_registro && !EW_hasValue(EW_this.x_fecha_registro, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_registro, "TEXT", "La fecha de registro es requerida."))
		return false;
}
if (EW_this.x_fecha_registro && !EW_checkeurodate(EW_this.x_fecha_registro.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_registro, "TEXT", "La fecha de registro es requerida."))
		return false; 
}
/*
if (EW_this.x_fecha_pago && !EW_hasValue(EW_this.x_fecha_pago, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_pago, "TEXT", "La fecha de pago es requerida."))
		return false;
}
if (EW_this.x_fecha_pago && !EW_checkeurodate(EW_this.x_fecha_pago.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_pago, "TEXT", "La fecha de pago es requerida."))
		return false; 
}
if (EW_this.x_medio_pago_id && !EW_hasValue(EW_this.x_medio_pago_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_medio_pago_id, "SELECT", "El medio de pago es requerido."))
		return false;
}
*/
if (EW_this.x_referencia && !EW_hasValue(EW_this.x_referencia, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_referencia, "TEXT", "La referencia de pago es requerida."))
		return false;
}
if (EW_this.x_factor && !EW_hasValue(EW_this.x_factor, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_factor, "TEXT", "El factor de descuento es requerido."))
		return false;
}
if (EW_this.x_factor && !EW_checknumber(EW_this.x_factor.value)) {
	if (!EW_onError(EW_this, EW_this.x_factor, "TEXT", "El factor de descuento es requerido."))
		return false; 
}
if (EW_this.x_importe && !EW_hasValue(EW_this.x_importe, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_importe, "TEXT", "El importe de pago es requerido."))
		return false;
}
if (EW_this.x_importe && !EW_checknumber(EW_this.x_importe.value)) {
	if (!EW_onError(EW_this, EW_this.x_importe, "TEXT", "El importe de pago es requerido."))
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
<form name="devolucionadd" id="devolucionadd" action="php_devolucionadd.php" method="post" >
<p>
<input type="hidden" name="x_hoy" value="<?php echo $currdate; ?>">
<input type="hidden" name="x_credito_id" value="<?php echo $x_credito_id; ?>">
<input type="hidden" name="x_vencimientos_id" value="<?php echo $x_vencimientos_id; ?>">
<input type="hidden" name="x_hoy" value="<?php echo $currdate; ?>">
<input type="hidden" name="a_add" value="A">

<table class="phpmaker" >
<tr>
<td>Folio:
</td>
<td>
<input type="text" name="x_ref_loc" value="<?php echo $x_ref_loc;?>" size="20" maxlength="20" />
</td>
<td>
<input type="button" name="busca" value="Localizar" onclick="refloc()" />
</td>
</tr>
</table>


<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="ewmsg"><?php echo $_SESSION["ewmsg"] ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>

<br />
<br />

<?php if ((!is_null($x_credito_id)) || ($x_credito_id > 0)){?>

<table class="ewTable_small">
	<tr>
		<td width="107" class="ewTableHeaderThin"><span>Fecha de registro</span></td>
		<td width="681" class="ewTableAltRow"><span>
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
<!---	
	<tr>
		<td class="ewTableHeaderThin"><span>Fecha de pago</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_fecha_pago" id="x_fecha_pago" value="<?php //echo FormatDateTime(@$x_fecha_pago,7); ?>">
&nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_pago" alt="Calendario" style="cursor:pointer;cursor:hand;">
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
/*
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
*/
?>
</span></td>
	</tr>
-->	
	<tr>
		<td class="ewTableHeaderThin"><span>Referencia</span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_referencia" id="x_referencia" size="100" maxlength="150" value="<?php echo htmlspecialchars(@$x_referencia) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Descuento %</span></td>
		<td class="ewTableAltRow"><span>
		<?php echo "60.00"; ?>
<input type="hidden" name="x_factor" id="x_factor" value="60.00">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Importe</span></td>
		<td class="ewTableAltRow"><span>
<input style="text-align:right" type="text" name="x_importe" id="x_importe" size="12" maxlength="10" value="<?php echo FormatNumber(@$x_total,2,0,0,1) ?>" onkeydown="return noinput(this,event);">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Status</span></td>
		<td class="ewTableAltRow"><span>
<?php if (!(!is_null($x_devolucion_status_id)) || ($x_devolucion_status_id == "")) { $x_devolucion_status_id = 0;} // Set default value ?>
<?php
$x_devolucion_status_idList = "<select name=\"x_devolucion_status_id\">";
$x_devolucion_status_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT `devolucion_status_id`, `descripcion` FROM `devolucion_status` where `devolucion_status_id` = 1";
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
<input type="submit" name="Action" value="Agregar devolucion" onclick="EW_checkMyForm()">
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
		$GLOBALS["x_devolucion_status_id"] = $row["devolucion_status_id"];
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
	global $x_devolucion_id;
	$sSql = "SELECT * FROM `devolucion`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";

	// Check for duplicate key
	$bCheckKey = true;
	$sWhereChk = $sWhere;
	if ((@$x_devolucion_id == "") || (is_null($x_devolucion_id))) {
		$bCheckKey = false;
	} else {
		if ($sWhereChk <> "") { $sWhereChk .= " AND "; }
		$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_devolucion_id) : $x_devolucion_id;			
		$sWhereChk .= "(`devolucion_id` = " . addslashes($sTmp) . ")";
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

	// Field fecha_registro
	$theValue = ($GLOBALS["x_fecha_registro"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_registro"]) . "'" : "Null";
	$fieldList["`fecha_registro`"] = $theValue;

	// Field fecha_pago
	$theValue = ($GLOBALS["x_fecha_pago"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_pago"]) . "'" : "Null";
	$fieldList["`fecha_pago`"] = $theValue;

	// Field medio_pago_id
	$theValue = ($GLOBALS["x_medio_pago_id"] != "") ? intval($GLOBALS["x_medio_pago_id"]) : "0";
	$fieldList["`medio_pago_id`"] = $theValue;

	// Field referencia
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia"]) : $GLOBALS["x_referencia"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`referencia`"] = $theValue;

	// Field factor
	$theValue = ($GLOBALS["x_factor"] != "") ? " '" . doubleval($GLOBALS["x_factor"]) . "'" : "NULL";
	$fieldList["`factor`"] = $theValue;

	// Field importe
	$theValue = ($GLOBALS["x_importe"] != "") ? " '" . doubleval($GLOBALS["x_importe"]) . "'" : "NULL";
	$fieldList["`importe`"] = $theValue;

	// Field devolucion_status_id
	$theValue = ($GLOBALS["x_devolucion_status_id"] != "") ? intval($GLOBALS["x_devolucion_status_id"]) : "NULL";
	$fieldList["`devolucion_status_id`"] = $theValue;

	// insert into database
	$sSql = "INSERT INTO `devolucion` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$x_devolucion_id = mysql_insert_id();

	$sSql = "select vencimiento_id from vencimiento where vencimiento_id in(".$GLOBALS["x_vencimientos_id"].")";
	$rswrk2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	while ($rowwrk2 = phpmkr_fetch_array($rswrk2)){
		$x_venc_id = $rowwrk2 ["vencimiento_id"];
		$sSql = "insert into devolucion_vencimiento values(0,$x_devolucion_id,$x_venc_id)";			
		phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);	
	}
	phpmkr_free_result($rswrk2);
	return true;
}
?>
