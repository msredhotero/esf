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

//if (!empty($x_promotor_recibo_id )) $x_promotor_recibo_id  = (get_magic_quotes_gpc()) ? stripslashes($x_promotor_recibo_id ) : $x_promotor_recibo_id ;
// Get action

$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I"; // Display blank record

		$sSql = "select promotor.nombre_completo, promotor_comision.comision_importe, solicitud.folio,
		promotor_recibo.promotor_recibo_id
		from promotor_comision join promotor_recibo
		on promotor_recibo.promotor_comision_id = promotor_comision.promotor_comision_id join promotor
		on promotor.promotor_id = promotor_comision.promotor_id join solicitud
		on solicitud.promotor_id = promotor.promotor_id
		where promotor_comision.promotor_comision_id = $x_promotor_comision_id";
		$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		if(phpmkr_num_rows($rswrk) > 0){
			$rowwrk = phpmkr_fetch_array($rswrk);
			$x_promotor = $rowwrk["nombre_completo"];
			$x_importe = $rowwrk["comision_importe"];
			$x_folio = $rowwrk["folio"];
			$x_promotor_recibo_id = $rowwrk["promotor_recibo_id"];			
		}else{
			$x_solicitud_id = 0;
			$_SESSION["ewmsg"] = "No fueron localizados los datos de la comision.";								
		}
		phpmkr_free_result($rswrk);

} else {

	// Get fields from form
	$x_promotor_recibo_id = @$_POST["x_promotor_recibo_id"];
//	$x_promotor_comision_id = @$_POST["x_promotor_comision_id"];
	$x_fecha_registro = @$_POST["x_fecha_registro"];
	$x_fecha_pago = @$_POST["x_fecha_pago"];
	$x_promotor_recibo_status_id = 2;
}

// Check if valid key
if (($x_promotor_recibo_id == "") || (is_null($x_promotor_recibo_id))) {
	ob_end_clean();
	header("Location: php_promotor_recibolist.php");
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
			$_SESSION["ewmsg"] = "El pago ha sido cancelado.";
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
if (EW_this.x_fecha_pago && !EW_hasValue(EW_this.x_fecha_pago, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_pago, "TEXT", "La fecha de cancelacion es requerida."))
		return false;
}
if (EW_this.x_fecha_pago && !EW_checkeurodate(EW_this.x_fecha_pago.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_pago, "TEXT", "La fecha de cancelacion es requerida."))
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
<p><span class="phpmaker">Cancelaci&oacute;n de Pagos de Comision<br>
    <br>
    <a href="php_promotor_comisionlist.php">Regresar a la lista</a></span></p>
<form name="promotor_reciboedit" id="promotor_reciboedit" action="php_promotor_reciboedit.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<input type="hidden" name="x_promotor_recibo_id" value="<?php echo $x_promotor_recibo_id; ?>">
<input type="hidden" name="x_fecha_registro" value="<?php echo $currdate; ?>">

<table class="ewTable_small">
  <tr>
    <td width="163" class="ewTableHeaderThin"><span>Comision No.</span></td>
    <td width="825" class="ewTableAltRow"><span>
      <?php
echo $x_promotor_comision_id;
?>
      <input type="hidden" name="x_promotor_comision_id" value="<?php echo $x_promotor_comision_id; ?>">
    </span></td>
  </tr>
  <tr>
    <td class="ewTableHeaderThin">Promotor</td>
    <td class="ewTableAltRow"><span> <?php echo $x_promotor; ?> </span> </td>
  </tr>
  <tr>
    <td class="ewTableHeaderThin">Folio</td>
    <td class="ewTableAltRow"><span> <?php echo $x_folio; ?> </span> </td>
  </tr>
  
  <tr>
    <td class="ewTableHeaderThin"><span>Fecha de cancelci&oacute;on</span></td>
    <td class="ewTableAltRow"><span>
      <input type="text" name="x_fecha_pago" id="x_fecha_pago" value="<?php echo FormatDateTime(@$x_fecha_pago,7); ?>" />
      &nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_pago" alt="Pick a Date" style="cursor:pointer;cursor:hand;" />
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
// Function EditData
// - Edit Data based on Key Value sKey
// - Variables used: field variables

function EditData($conn)
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
		$bEditData = false; // Update Failed
	}else{
		$theValue = ($GLOBALS["x_fecha_registro"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_registro"]) . "'" : "Null";
		$fieldList["`fecha_registro`"] = $theValue;
		$theValue = ($GLOBALS["x_fecha_pago"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_pago"]) . "'" : "Null";
		$fieldList["`fecha_pago`"] = $theValue;
		$theValue = ($GLOBALS["x_promotor_recibo_status_id"] != "") ? intval($GLOBALS["x_promotor_recibo_status_id"]) : "NULL";
		$fieldList["`promotor_recibo_status_id`"] = $theValue;

		// update
		$sSql = "UPDATE `promotor_recibo` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE " . $sWhere;
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);


		$sSql = "update promotor_comision set promotor_comision_status_id = 3 where promotor_comision_id = ".$GLOBALS["x_promotor_comision_id"];
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		
		$bEditData = true; // Update Successful
	}
	return $bEditData;
}
?>
