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

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
$x_recibo_id = @$_GET["recibo_id"];
if (empty($x_recibo_id)) {
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
	$x_recibo_id = @$_POST["x_recibo_id"];
	$x_vencimiento_id = @$_POST["x_vencimiento_id"];
	$x_medio_pago_id = @$_POST["x_medio_pago_id"];
	$x_referencia_pago = @$_POST["x_referencia_pago"];
	$x_fecha_registro = @$_POST["x_fecha_registro"];	
	$x_fecha_pago = @$_POST["x_fecha_pago"];		
	$x_importe_venc = @$_POST["x_importe_venc"];
	$x_interes_venc = @$_POST["x_interes_venc"];
	$x_interes_moratorio = @$_POST["x_interes_moratorio"];	
	$x_importe = @$_POST["x_importe"];		
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No records found";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: php_recibolist.php");
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "El pago ha sido aplicado.";
			$x_ref_loc = Null;			
			$x_vencimiento_id = Null;
			$x_vencimiento_num = Null;
			$x_vencimiento_status_id = Null;
			$x_fecha_vencimiento = Null;
			$x_importe_venc = Null;
			$x_importe = Null;			
			$x_interes_venc = Null;
			$x_interes_moratorio = Null;
			$x_folio = Null;
			$x_nombre_completo = Null;
		}
		break;
	case "L": // Add
		$x_ref_loc = @$_POST["x_ref_loc"];	
		if(strlen($x_ref_loc) != 8){
			$_SESSION["ewmsg"] = "La referencia no es valida verifiquela.";		
			$x_vencimiento_id = Null;		
		}else{
			if(strpos($x_ref_loc,"/") == 0){
				$_SESSION["ewmsg"] = "La referencia no es valida verifiquela.";				
				$x_vencimiento_id = Null;				
			}else{
				$x_venc_id = substr($x_ref_loc,0,strpos($x_ref_loc,"/"));
				$x_venc_id = intval($x_venc_id);
				$sSql = "select vencimiento.*, solicitud.folio, solicitud.promotor_id, cliente.nombre_completo 
				from vencimiento join credito 
				on credito.credito_id = vencimiento.credito_id join solicitud
				on solicitud.solicitud_id = credito.solicitud_id join solicitud_cliente
				on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente 
				on cliente.cliente_id = solicitud_cliente.cliente_id
				where vencimiento.vencimiento_id = ".$x_venc_id;
				$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
				if(phpmkr_num_rows($rswrk) > 0){
					$rowwrk = phpmkr_fetch_array($rswrk);
					$x_val_promo = true;
					if(($rowwrk["vencimiento_status_id"] != 1) && ($rowwrk["vencimiento_status_id"] != 3)) {
						$_SESSION["ewmsg"] = "El vencimiento ya esta pagado o cancelado, verifique.";														
						$x_val_promo = false;						
						$x_vencimiento_id = Null;							
					}

					if($_SESSION["php_project_esf_status_UserRolID"] == 2) {
						if($_SESSION["php_project_esf_status_PromotorID"] != $rowwrk["promotor_id"]){
							$_SESSION["ewmsg"] = "La referencia no le corresponde, verifiquela.";														
							$x_val_promo = false;						
							$x_vencimiento_id = Null;							
						}
					}
					
					if($x_val_promo == true){
						$x_vencimiento_id = $rowwrk["vencimiento_id"];
						$x_vencimiento_num = $rowwrk["vencimiento_num"];				
						$x_vencimiento_status_id = $rowwrk["vencimiento_status_id"];								
						$x_fecha_vencimiento = $rowwrk["fecha_vencimiento"];
						$x_importe_venc = $rowwrk["importe"];
						$x_interes_venc = $rowwrk["interes"];									
						$x_interes_moratorio = $rowwrk["interes_moratorio"];																
						$x_folio = $rowwrk["folio"];															
						$x_nombre_completo = $rowwrk["nombre_completo"];																				
					}
				}else{
					$x_vencimiento_id = Null;	
					$_SESSION["ewmsg"] = "La referencia no fue localizada, verifiquela.";								
				}
				phpmkr_free_result($rswrk);
			}
		}
		break;		
}
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="utilerias/datefunc.js"></script>
<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<script type="text/javascript">
<!--

function actimp(){
EW_this = document.pagoadd;

	EW_this.x_importe.value = Number(EW_this.x_importe_venc.value) + Number(EW_this.x_interes_venc.value) + Number(EW_this.x_interes_moratorio.value);

}
function refloc(){
EW_this = document.pagoadd;
validada = true;


	if (EW_this.x_ref_loc && !EW_hasValue(EW_this.x_ref_loc, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_ref_loc, "TEXT", "La referencia es requerida."))
		validada = false;
	}

	if(validada == true){
		EW_this.a_add.value = "L";
		EW_this.submit();
	}
}


function EW_checkMyForm() {
EW_this = document.pagoadd;
validada = true;

if (validada && EW_this.x_medio_pago_id && !EW_hasValue(EW_this.x_medio_pago_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_medio_pago_id, "SELECT", "El medio de pago es requerido."))
		validada = false;
}
if (validada && EW_this.x_referencia_pago && !EW_hasValue(EW_this.x_referencia_pago, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_referencia_pago, "TEXT", "La referencia de pago es requerida."))
		validada = false;
}
if (validada && EW_this.x_fecha_pago && !EW_hasValue(EW_this.x_fecha_pago, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_pago, "TEXT", "La fecha de pago es requerida."))
		validada = false;
}
if (EW_this.x_fecha_pago && !EW_checkeurodate(EW_this.x_fecha_pago.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_pago, "TEXT", "La fecha de pago es incorrecta."))
		validada = false;
}

if(validada == true){
	if (!compareDates(EW_this.x_fecha_pago.value, EW_this.x_hoy.value)) {	
		if (!EW_onError(EW_this, EW_this.x_fecha_pago, "TEXT", "La fecha de pago no puede ser mayor a la fecha actual."))
			validada = false; 
	}
}

if(validada == true){
	if (!compareDates(EW_this.x_fecha_vencimiento.value, EW_this.x_hoy.value)) {	
		if (!EW_onError(EW_this, EW_this.x_fecha_pago, "TEXT", "Para realizar pagos anticipados vaya a la opcion correspondiente del menu de Pagos."))
			validada = false; 
	}
}

if(validada == true){
	EW_this.a_add.value = "A";
	EW_this.submit();
}

}

//-->
</script>
<!--script type="text/javascript" src="popcalendar.js"></script-->
<!-- New popup calendar -->
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/skins/aqua/theme.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-sp.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<p><span class="phpmaker">PAGOS<br>
    <br>
</span>
  <?php
if (@$_SESSION["ewmsg"] <> "") {
?>
</p>
  <p><span class="ewmsg"><?php echo $_SESSION["ewmsg"] ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>

<form name="pagoadd" id="pagoadd" action="php_pagoadd.php" method="post">
<input type="hidden" name="a_add" value="A">
<input type="hidden" name="x_hoy" value="<?php echo $currdate; ?>">
<input type="hidden" name="x_vencimiento_id" value="<?php echo $x_vencimiento_id; ?>">
<input type="hidden" name="x_fecha_vencimiento" value="<?php echo $x_fecha_vencimiento; ?>">
<input type="hidden" name="x_fecha_registro" value="<?php echo FormatDateTime(@$currdate,7); ?>">		

<table class="phpmaker" >
<tr>
<td>Referencia:
</td>
<td>
<input type="text" name="x_ref_loc" value="<?php echo $x_ref_loc;?>" size="20" maxlength="20" onKeyPress="return noenter(this,event)"/>
</td>
<td>
<input type="button" name="busca" value="Localizar" onclick="refloc()" />
</td>
</tr>
</table>

<br />
<br />

<?php if ((!is_null($x_vencimiento_id)) || ($x_vencimiento_id > 0)){?>

<table class="ewTable_small">
	<tr>
	  <td height="26" class="ewTableHeaderThin"><p>Folio</p>	    </td>
	  <td class="ewTableAltRow"><b>
	  <?php echo $x_folio; ?>
	  </b>	  </td>
	  </tr>
	<tr>
	  <td class="ewTableHeaderThin">Cliente</td>
	  <td class="ewTableAltRow"><?php echo $x_nombre_completo; ?>	  </td>
	  </tr>
	<tr>
		<td width="143" class="ewTableHeaderThin"><span>Vencimiento</span></td>
		<td width="845" class="ewTableAltRow"><span>
	  <?php echo $x_vencimiento_num; ?>	  		
		</span></td>
	</tr>
	<tr>
	  <td class="ewTableHeaderThin">Fecha Venc. </td>
	  <td class="ewTableAltRow"><?php echo FormatDateTime(@$x_fecha_vencimiento,7); ?></td>
	  </tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Medio de pago</span></td>
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
<input type="text" name="x_referencia_pago" id="x_referencia_pago" size="30" maxlength="50" value="<?php echo htmlspecialchars(@$x_ref_loc) ?>">
</span></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><span>Fecha de pago </span></td>
		<td class="ewTableAltRow"><span>
<input type="text" name="x_fecha_pago" id="x_fecha_pago" value="<?php echo FormatDateTime(@$currdate,7); ?>">
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
	  <td class="ewTableHeaderThin">Importe</td>
	  <td class="ewTableAltRow">
	  <span>
	  <input style="text-align:right" type="text" name="x_importe_venc" value="<?php echo FormatNumber(@$x_importe_venc,2,0,0,0); ?>" onblur="actimp()"  />
      </span>
	  </td>
	  </tr>
	<tr>
	  <td class="ewTableHeaderThin">interes</td>
	  <td class="ewTableAltRow">
	  <span>
	  <input style="text-align:right" type="text" name="x_interes_venc" value="<?php echo FormatNumber(@$x_interes_venc,2,0,0,0); ?>" onblur="actimp()" />	  
      </span>	  
	  </td>
	  </tr>
	<tr>
	  <td class="ewTableHeaderThin">moratorios</td>
	  <td class="ewTableAltRow">
	  <span>
	  <input style="text-align:right" type="text" name="x_interes_moratorio" value="<?php echo FormatNumber(@$x_interes_moratorio,2,0,0,0); ?>"  onblur="actimp()" />	  	  
      </span>	  	  
	  </td>
	  </tr>
	<tr>
		<td class="ewTableHeaderThin">Total a pagar </td>
		<td class="ewTableAltRow">
	    <input style="text-align:right" name="x_importe" type="text" id="x_importe" value="<?php echo FormatNumber(@$x_importe_venc + @$x_interes_venc + @$x_interes_moratorio,2,0,0,0); ?>" />		
		</td>
	</tr>
</table>
<p>
<?php if(($x_vencimiento_status_id == 2) || ($x_vencimiento_status_id == 4)){
	$sSqlWrk = "SELECT `descripcion` FROM `vencimiento_status`";
	$sTmp = $x_vencimiento_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `vencimiento_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}else{
		$sTmp = "INDEFINIDO";	
	}
	@phpmkr_free_result($rswrk);
	echo "<span class=\"phpmaker\"><font color=\"#FF0000\"><b>El vencimiento se encuentra: ".$sTmp."</b></font></span>";
}else{
?>

<input type="button"  name="Action" value="Aplicar Pago" onclick="EW_checkMyForm()">
<?php } ?>

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
		$GLOBALS["x_medio_pago_id"] = $row["medio_pago_id"];
		$GLOBALS["x_referencia_pago"] = $row["referencia_pago"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
		$GLOBALS["x_importe"] = $row["importe"];
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
	global $x_recibo_id;
	$sSql = "SELECT * FROM `recibo`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";

	// Check for duplicate key
	$bCheckKey = true;
	$sWhereChk = $sWhere;
	if ((@$x_recibo_id == "") || (is_null($x_recibo_id))) {
		$bCheckKey = false;
	} else {
		if ($sWhereChk <> "") { $sWhereChk .= " AND "; }
		$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_recibo_id) : $x_recibo_id;			
		$sWhereChk .= "(`recibo_id` = " . addslashes($sTmp) . ")";
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


	// Field recibo_status_id
	$fieldList["`recibo_status_id`"] = 1;


	// Field vencimiento_id
	$theValue = ($GLOBALS["x_vencimiento_id"] != "") ? intval($GLOBALS["x_vencimiento_id"]) : "NULL";
	$fieldList["`vencimiento_id`"] = $theValue;

	// Field medio_pago_id
	$theValue = ($GLOBALS["x_medio_pago_id"] != "") ? intval($GLOBALS["x_medio_pago_id"]) : "NULL";
	$fieldList["`medio_pago_id`"] = $theValue;

	// Field referencia_pago
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_pago"]) : $GLOBALS["x_referencia_pago"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`referencia_pago`"] = $theValue;

	// Field fecha_registro
	$theValue = ($GLOBALS["x_fecha_registro"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_registro"]) . "'" : "Null";
	$fieldList["`fecha_registro`"] = $theValue;

	// Field fecha_registro
	$theValue = ($GLOBALS["x_fecha_pago"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_pago"]) . "'" : "Null";
	$fieldList["`fecha_pago`"] = $theValue;

	// Field importe
	$theValue = ($GLOBALS["x_importe"] != "") ? " '" . doubleval($GLOBALS["x_importe"]) . "'" : "NULL";
	$fieldList["`importe`"] = $theValue;

	// insert into database
	$sSql = "INSERT INTO `recibo` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);

	$sSql = "update vencimiento set 
	vencimiento_status_id = 2,
	importe = ".$GLOBALS["x_importe_venc"].",
	interes = ".$GLOBALS["x_interes_venc"].",
	interes_moratorio = ".$GLOBALS["x_interes_moratorio"].",
	total_venc = ".$GLOBALS["x_importe"]." 
	where vencimiento_id =  ".$GLOBALS["x_vencimiento_id"];
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);


//VALIDA PAGOS FALTANTES	

	$sSqlWrk = "SELECT credito_id  FROM vencimiento where vencimiento_id = ".$GLOBALS["x_vencimiento_id"];
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$rowwrk = phpmkr_fetch_array($rswrk);
	$x_credito_id = $rowwrk["credito_id"];
	@phpmkr_free_result($rswrk);

	$sSqlWrk = "SELECT solicitud_id  FROM credito where credito_id = $x_credito_id";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$rowwrk = phpmkr_fetch_array($rswrk);
	$x_solicitud_id = $rowwrk["solicitud_id"];
	@phpmkr_free_result($rswrk);

	$sSqlWrk = "SELECT count(*) as pendientes FROM vencimiento where credito_id = $x_credito_id and vencimiento_status_id in (1,3)";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$rowwrk = phpmkr_fetch_array($rswrk);
	$x_pendientes = $rowwrk["pendientes"];
	@phpmkr_free_result($rswrk);

	if($x_pendientes == 0){
	//FINIQUITA CREDITO
		$sSqlWrk = "UPDATE credito set credito_status_id = 3 where credito_id = $x_credito_id";
		phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);

		$sSqlWrk = "UPDATE solicitud set solicitud_status_id = 7 where solicitud_id = $x_solicitud_id";
		phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);

	}
	
	return true;
}
?>

