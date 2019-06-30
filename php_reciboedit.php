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
$x_recibo_id = Null; 
$ox_recibo_id = Null;
$x_vencimiento_id = Null; 
$ox_vencimiento_id = Null;
$x_medio_pago_id = Null; 
$ox_medio_pago_id = Null;
$x_referencia_pago = Null; 
$ox_referencia_pago = Null;
$x_fecha_registro = Null; 
$ox_fecha_registro = Null;
$x_importe = Null; 
$ox_importe = Null;
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// Load key from QueryString
$x_recibo_id = @$_GET["recibo_id"];

// Get key
$x_recibo_id = @$_GET["recibo_id"];
if (($x_recibo_id == "") || ((is_null($x_recibo_id)))) {
	$x_recibo_id = @$_POST["x_recibo_id"];
	if (($x_recibo_id == "") || ((is_null($x_recibo_id)))) {
		ob_end_clean(); 
		header("Location: php_recibolist.php"); 
		exit();
	}
}


//if (!empty($x_recibo_id )) $x_recibo_id  = (get_magic_quotes_gpc()) ? stripslashes($x_recibo_id ) : $x_recibo_id ;
// Get action

$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I";	// Display with input box
} else {

	// Get fields from form
	$x_recibo_id = @$_POST["x_recibo_id"];
	$x_banco_id = @$_POST["x_banco_id"];
	$x_medio_pago_id = @$_POST["x_medio_pago_id"];	
	$x_referencia_pago = @$_POST["x_referencia_pago"];
	$x_fecha_pago = @$_POST["x_fecha_pago"];
	$x_importe = @$_POST["x_importe"];
}

// Check if valid key
if (($x_recibo_id == "") || (is_null($x_recibo_id))) {
	ob_end_clean();
	header("Location: php_recibolist.php");
	exit();
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No records found";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_recibolist.php");
			exit();
		}
		break;
	case "U": // Update
		if (EditData($conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "El pago ha sido actualizado";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_recibolist.php");
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
if (EW_this.x_medio_pago_id && !EW_hasValue(EW_this.x_medio_pago_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_medio_pago_id, "SELECT", "El medio de pago es requerido."))
		return false;
}
if (EW_this.x_fecha_pago && !EW_hasValue(EW_this.x_fecha_pago, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_pago, "TEXT", "La fecha de pago es requerida."))
		return false;
}
if (EW_this.x_fecha_pago && !EW_checkeurodate(EW_this.x_fecha_pago.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_pago, "TEXT", "La fecha de pago es incorrecta."))
		return false; 
}
if (EW_this.x_importe && !EW_hasValue(EW_this.x_importe, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_importe, "TEXT", "El importe es requerido."))
		return false;
}
if (EW_this.x_importe && !EW_checknumber(EW_this.x_importe.value)) {
	if (!EW_onError(EW_this, EW_this.x_importe, "TEXT", "El importe es incorrecto."))
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
<p><span class="phpmaker">PAGOS<br><br>
<a href="php_recibolist.php">Regresar a la lista</a></span></p>
<form name="reciboedit" id="reciboedit" action="php_reciboedit.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<input type="hidden" name="x_recibo_id" value="<?php echo $x_recibo_id; ?>">
<table class="ewTable_small">
  <tr>
    <td width="145" class="ewTableHeaderThin">Cr&eacute;dito No.</td>
    <td width="643" class="ewTableAltRow"><?php
$sSqlWrk = "SELECT credito.credito_num, credito.cliente_num FROM recibo join recibo_vencimiento 
on recibo.recibo_id = recibo_vencimiento.recibo_id join vencimiento 
on vencimiento.vencimiento_id = recibo_vencimiento.vencimiento_id join credito
on credito.credito_id = vencimiento.credito_id
where recibo.recibo_id = $x_recibo_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	$x_credito_num = $rowwrk["credito_num"];
	$x_cliente_num = $rowwrk["cliente_num"];	
}
@phpmkr_free_result($rswrk);

echo $x_credito_num; 
?>    </td>
  </tr>
  <tr>
    <td class="ewTableHeaderThin">Cliente No.</td>
    <td class="ewTableAltRow"><?php
	  echo $x_cliente_num;
	  ?>    </td>
  </tr>
  
  <tr>
    <td class="ewTableHeaderThin">Banco - Cta</td>
    <td class="ewTableAltRow"><?php if (!(!is_null($x_banco_id)) || ($x_banco_id == "")) { $x_banco_id = 0;} // Set default value ?>
      <?php
$x_medio_pago_idList = "<select name=\"x_banco_id\">";
$x_medio_pago_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT `banco_id`, `nombre`, `cuenta` FROM `banco`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_medio_pago_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["banco_id"] == @$x_banco_id) {
			$x_medio_pago_idList .= "' selected";
		}
		$x_medio_pago_idList .= ">" . $datawrk["nombre"] . " - " . $datawrk["cuenta"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_medio_pago_idList .= "</select>";
echo $x_medio_pago_idList;
?></td>
  </tr>
  <tr>
    <td class="ewTableHeaderThin"><span>Medio de pago</span></td>
    <td class="ewTableAltRow"><?php
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
?></td>
  </tr>
  <tr>
    <td class="ewTableHeaderThin"><span>Referencia de pago</span></td>
    <td class="ewTableAltRow"><input type="text" name="x_referencia_pago" id="x_referencia_pago" size="30" maxlength="50" value="<?php echo htmlspecialchars(@$x_referencia_pago) ?>" /></td>
  </tr>
  <tr>
    <td class="ewTableHeaderThin"><span>Fecha de Pago</span></td>
    <td class="ewTableAltRow">
<span>
<input type="text" name="x_fecha_pago" id="x_fecha_pago" value="<?php echo FormatDateTime(@$x_fecha_pago,7); ?>">
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
</span>    </td>
  </tr>
  <tr>
    <td class="ewTableHeaderThin"><span>Importe</span></td>
    <td class="ewTableAltRow"><input type="text" name="x_importe" id="x_importe" size="30" value="<?php echo htmlspecialchars(@$x_importe) ?>" /></td>
  </tr>
  <tr>
    <td colspan="2" class="ewTableHeaderThin"><div align="center">Vencimientos</div></td>
  </tr>
  <tr>
    <td colspan="2" class="ewTableRow"><table id="ewlistmain" class="ewTable" align="center">
      <!-- Table header -->
      <tr class="ewTableHeader">
        <td valign="top"><span> No </span></td>
        <td valign="top"><span> Status </span></td>
        <td valign="top"><span> Fecha </span></td>
        <td valign="top"><span> Importe </span></td>
        <td valign="top"><span> Inter&eacute;s </span></td>
        <td valign="top"><span> Moratorio </span></td>
        <td valign="top"><span> Total </span></td>
      </tr>
      <?php


$sSqlWrk = "SELECT vencimiento.* FROM vencimiento join recibo_vencimiento
on recibo_vencimiento.vencimiento_id = vencimiento.vencimiento_id 
where recibo_vencimiento.recibo_id = $x_recibo_id";
$rswrkm = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);

$x_total = 0;
$x_total_interes = 0;
$x_total_moratorios = 0;
$x_total_total = 0;

while ($row = @phpmkr_fetch_array($rswrkm)) {

		$x_vencimiento_id = $row["vencimiento_id"];
		$x_vencimiento_num = $row["vencimiento_num"];		
		$x_vencimiento_status_id = $row["vencimiento_status_id"];
		$x_fecha_vencimiento = $row["fecha_vencimiento"];
		$x_importe = $row["importe"];
		$x_interes = $row["interes"];
		$x_interes_moratorio = $row["interes_moratorio"];
		
		$x_total = $x_importe + $x_interes + $x_interes_moratorio;
		$x_total_interes = $x_total_interes + $x_interes;
		$x_total_moratorios = $x_total_moratorios + $x_interes_moratorio;
		$x_total_total = $x_total_total + $x_total;
	
?>
      <!-- Table body -->
      <tr>
        <!-- vencimiento_id -->
        <td align="right"><span> <?php echo $x_vencimiento_num; ?> </span></td>
        <!-- credito_id -->
        <!-- vencimiento_status_id -->
        <td><span>
          <?php
if ((!is_null($x_vencimiento_status_id)) && ($x_vencimiento_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `vencimiento_status`";
	$sTmp = $x_vencimiento_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `vencimiento_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_vencimiento_status_id = $x_vencimiento_status_id; // Backup Original Value
$x_vencimiento_status_id = $sTmp;
?>
          <?php echo $x_vencimiento_status_id; ?>
          <?php $x_vencimiento_status_id = $ox_vencimiento_status_id; // Restore Original Value ?>
        </span></td>
        <!-- fecha_vencimiento -->
        <td align="center"><span> <?php echo FormatDateTime($x_fecha_vencimiento,7); ?> </span></td>
        <!-- importe -->
        <td align="right"><span> <?php echo (is_numeric($x_importe)) ? FormatNumber($x_importe,2,0,0,1) : $x_importe; ?> </span></td>
        <!-- interes -->
        <td align="right"><span> <?php echo (is_numeric($x_interes)) ? FormatNumber($x_interes,2,0,0,1) : $x_interes; ?> </span></td>
        <!-- interes_moratorio -->
        <td align="right"><span> <?php echo (is_numeric($x_interes_moratorio)) ? FormatNumber($x_interes_moratorio,2,0,0,1) : $x_interes_moratorio; ?> </span></td>
        <td align="right"><span> <?php echo (is_numeric($x_total)) ? FormatNumber($x_total,2,0,0,1) : $x_total; ?> </span></td>
      </tr>
      <?php
	}

?>
      <tr>
        <td align="right"><span> </span></td>
        <td><span> </span></td>
        <td align="center"><span> </span></td>
        <td align="right"><span> <b> <?php echo FormatNumber($x_total_pagos,2,0,0,1); ?> </b> </span></td>
        <td align="right"><span> <b> <?php echo FormatNumber($x_total_interes,2,0,0,1); ?> </b> </span></td>
        <td align="right"><span> <b> <?php echo FormatNumber($x_total_moratorios,2,0,0,1); ?> </b> </span></td>
        <td align="right"><span> <b> <?php echo FormatNumber($x_total_total,2,0,0,1); ?> </b> </span></td>
      </tr>
    </table></td>
  </tr>
</table>
<p>
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

function LoadData($conn)
{
	global $x_recibo_id;
	$sSql = "SELECT * FROM `recibo`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_recibo_id) : $x_recibo_id;
	$sWhere .= "(`recibo_id` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_recibo_id"] = $row["recibo_id"];
		$GLOBALS["x_vencimiento_id"] = $row["vencimiento_id"];
		$GLOBALS["x_banco_id"] = $row["banco_id"];
		$GLOBALS["x_medio_pago_id"] = $row["medio_pago_id"];		
		$GLOBALS["x_referencia_pago"] = $row["referencia_pago"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
		$GLOBALS["x_fecha_pago"] = $row["fecha_pago"];		
		$GLOBALS["x_importe"] = $row["importe"];
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
	global $x_recibo_id;
	$sSql = "SELECT * FROM `recibo`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_recibo_id) : $x_recibo_id;	
	$sWhere .= "(`recibo_id` = " . addslashes($sTmp) . ")";
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
		$theValue = ($GLOBALS["x_banco_id"] != "") ? intval($GLOBALS["x_banco_id"]) : "0";
		$fieldList["`banco_id`"] = $theValue;
		$theValue = ($GLOBALS["x_medio_pago_id"] != "") ? intval($GLOBALS["x_medio_pago_id"]) : "NULL";
		$fieldList["`medio_pago_id`"] = $theValue;
		
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_pago"]) : $GLOBALS["x_referencia_pago"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`referencia_pago`"] = $theValue;
		$theValue = ($GLOBALS["x_fecha_pago"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_pago"]) . "'" : "Null";
		$fieldList["`fecha_pago`"] = $theValue;
		$theValue = ($GLOBALS["x_importe"] != "") ? " '" . doubleval($GLOBALS["x_importe"]) . "'" : "NULL";
		$fieldList["`importe`"] = $theValue;

		// update
		$sSql = "UPDATE `recibo` SET ";
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
