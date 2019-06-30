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
	$x_credito_id = @$_POST["x_credito_id"];	
	$x_nombre_completo = @$_POST["x_nombre_completo"];
	$x_medio_pago_id = @$_POST["x_medio_pago_id"];
	$x_referencia_pago = @$_POST["x_referencia_pago"];
	$x_fecha_registro = @$_POST["x_fecha_registro"];	
	$x_fecha_pago = @$_POST["x_fecha_pago"];		
	$x_importe = @$_POST["x_importe"];
	$x_num_pagos = @$_POST["x_num_pagos"];	
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
			header("Location: php_recibolist.php");
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "Los pagos ha sido aplicados.";
			$x_credito_id = null;	
			$x_nombre_completo = "";
			$x_medio_pago_id = "";
			$x_referencia_pago = "";
			$x_fecha_registro = "";
			$x_fecha_pago = "";	
			$x_importe = "";
			$x_num_pagos = "";	
			$x_vencimientos_id = "";		
		}
		break;
	case "L": // Add
		$x_ref_loc = @$_POST["x_ref_loc"];	
			
		$sSql = "select solicitud.promotor_id, solicitud.folio, cliente.nombre_completo, credito.credito_id 
		from solicitud join credito 
		on solicitud.solicitud_id = credito.solicitud_id join solicitud_cliente
		on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente 
		on cliente.cliente_id = solicitud_cliente.cliente_id
		where credito.credito_num = '$x_ref_loc'";;
		$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		if(phpmkr_num_rows($rswrk) > 0){
			$rowwrk = phpmkr_fetch_array($rswrk);
			$x_credito_id = $rowwrk["credito_id"];
			$x_promotor_id = $rowwrk["promotor_id"];			
			$x_folio = $rowwrk["folio"];															
			$x_nombre_completo = $rowwrk["nombre_completo"];																				
			phpmkr_free_result($rswrk);


			//VENC VENCIDOS
			$sSql = "select count(*) as vencidos
			from vencimiento 
			where credito_id = $x_credito_id and vencimiento_status_id in (3)";;
			$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$rowwrk = phpmkr_fetch_array($rswrk);
			$x_vencidos = $rowwrk["vencidos"];																				
			phpmkr_free_result($rswrk);
			
			$x_val_promo = true;
			
			if($_SESSION["php_project_esf_status_UserRolID"] == 2) {
				if($_SESSION["php_project_esf_status_PromotorID"] != $x_promotor_id){
					$_SESSION["ewmsg"] = "El credito no le corresponde, verifiquelo.";														
					$x_val_promo = false;						
					$x_credito_id = null;
				}
			}
			
			if($x_val_promo == true){
				if($x_vencidos > 0){
					$x_credito_id = null;
					$_SESSION["ewmsg"] = "El credito tiene pagos vencidos, no esposible anticipar pagos.";								
				}else{
					//VENC PEND
					$sSql = "select count(*) as pendientes
					from vencimiento 
					where credito_id = $x_credito_id and vencimiento_status_id in (1)";;
					$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
					if(phpmkr_num_rows($rswrk) > 0){
						$rowwrk = phpmkr_fetch_array($rswrk);
						$x_pendientes = $rowwrk["pendientes"];																				
					}else{
						$x_credito_id = null;				
						$_SESSION["ewmsg"] = "No hay vencimientos pendientes de pago.";								
					}
					phpmkr_free_result($rswrk);
				}
			}
			
			
			
		}else{
			$x_credito_id = null;
			$_SESSION["ewmsg"] = "El credito no fue localizado, verifique.";								
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

function refloc(){
EW_this = document.pagoadd;
validada = true;


	if (EW_this.x_ref_loc && !EW_hasValue(EW_this.x_ref_loc, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_ref_loc, "TEXT", "El folio es requerido."))
		validada = false;
	}

	if(validada == true){
		EW_this.a_add.value = "L";
		EW_this.submit();
	}
}

function muestravenc(){
EW_this = document.pagoadd;
validada = true;

	if (EW_this.x_num_pagos && !EW_hasValue(EW_this.x_num_pagos, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_num_pagos, "SELECT", "Indique el numero de pagos a realizar."))
			validada = false;
	}

	if(validada == true){
		EW_this.a_add.value = "V";
		EW_this.submit();
	}

}

function actualizapago(venc){
EW_this = document.pagoadd;

	if(venc.checked == true){
		EW_this.x_total_pago_dummy.value = Number(EW_this.x_total_pago_dummy.value) + Number(venc.value);
	}else{
		EW_this.x_total_pago_dummy.value = Number(EW_this.x_total_pago_dummy.value) - Number(venc.value);
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
/*
if(validada == true){
	if (!compareDates(EW_this.x_fecha_vencimiento.value, EW_this.x_hoy.value)) {	
		if (!EW_onError(EW_this, EW_this.x_fecha_registro, "TEXT", "Para realizar pagos anticipados vaya a la opcion correspondiente del menu de Pagos."))
			validada = false; 
	}
}
*/
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
<p><span class="phpmaker">PAGOS ANTICIPADOS <br>
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

<form name="pagoadd" id="pagoadd" action="php_pago_anticipadoadd.php" method="post">
<input type="hidden" name="a_add" value="A">
<input type="hidden" name="x_hoy" value="<?php echo $currdate; ?>">
<input type="hidden" name="x_credito_id" value="<?php echo $x_credito_id; ?>">
<input type="hidden" name="x_nombre_completo" value="<?php echo $x_nombre_completo; ?>">
<input type="hidden" name="x_pendientes" value="<?php echo $x_pendientes; ?>">
<input type="hidden" name="x_fecha_registro" id="x_fecha_registro" value="<?php echo FormatDateTime(@$currdate,7); ?>">

<table class="phpmaker" >
<tr>
<td>Cr&eacute;dito N&uacute;mero:</td>
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

<?php if ((!is_null($x_credito_id)) || ($x_credito_id > 0)){?>

<table class="ewTable_small">
	<tr>
	  <td width="143" height="26" class="ewTableHeaderThin"><p>Cliente</p>	    </td>
	  <td width="845" class="ewTableAltRow"><b>
	  <?php echo $x_nombre_completo; ?>
	  </b>	  </td>
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
<input type="text" name="x_referencia_pago" id="x_referencia_pago" size="30" maxlength="50" value="PAGO ANTICIPADO">
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
	  <td class="ewTableHeaderThin">Numero de Venc. </td>
	  <td class="ewTableAltRow"><span>
		<?php
		$x_medio_pago_idList = "<select name=\"x_num_pagos\" onchange=\"muestravenc()\">";
		$x_medio_pago_idList .= "<option value=''>Seleccione</option>";
		$x_contador = 1;
		while ($x_contador <= $x_pendientes) {
			$x_medio_pago_idList .= "<option value=\"" . $x_contador . "\"";
			if ($x_contador == @$x_num_pagos) {
				$x_medio_pago_idList .= "' selected";
			}
			$x_medio_pago_idList .= ">" . $x_contador . "</option>";
			$x_contador++;
		}
		$x_medio_pago_idList .= "</select>";
		echo $x_medio_pago_idList;
		?>
	  </span>
	  </td>
	  </tr>
</table>
<p>



<?php if($x_num_pagos > 0 ){ ?>

<!-- VENCIMEITOS --->
<table id="ewlistmain" class="ewTable" align="center">
	<tr class="ewTableHeader">
		<td valign="top"><span>
No
		</span></td>
		<td valign="top"><span>
Status
		</span></td>
		<td valign="top"><span>
Fecha
		</span></td>
		<td valign="top"><span>
Saldo		
		</span></td>				
		<td valign="top"><span>
Importe
		</span></td>
		<td valign="top"><span>
Interés
		</span></td>
		<td valign="top"><span>
Moratorio
		</span></td>
		<td valign="top"><span>
Total		
		</span></td>						
	</tr>

<?php
$sSqlWrk = "SELECT importe FROM credito where credito_id = $x_credito_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
$x_saldo = $datawrk["importe"];
@phpmkr_free_result($rswrk);


$sSql = "SELECT * FROM vencimiento WHERE (vencimiento.credito_id = $x_credito_id) and  (vencimiento.vencimiento_status_id = 1) order by vencimiento_num+0 DESC";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$x_total = 0;
$x_total_pagos = 0;
$x_total_interes = 0;
$x_total_moratorios = 0;
$x_total_total = 0;
$x_vencimientos_id = "";
$nRecActual = 0;
$nRecCount = 0;
while (($row = @phpmkr_fetch_array($rs)) && ($nRecCount < $x_num_pagos)) {
	$nRecCount = $nRecCount + 1;
	if ($nRecCount >= $nStartRec) {
		$nRecActual++;

		// Set row color
		$sItemRowClass = " class=\"ewTableRow\"";
		$sListTrJs = " onmouseover='ew_mouseover(this);' onmouseout='ew_mouseout(this);' onclick='ew_click(this);'";

		// Display alternate color for rows
		if ($nRecCount % 2 <> 1) {
			$sItemRowClass = " class=\"ewTableAltRow\"";
		}
		$x_vencimiento_id = $row["vencimiento_id"];
		$x_vencimiento_num = $row["vencimiento_num"];		
		$x_credito_id = $row["credito_id"];
		$x_vencimiento_status_id = $row["vencimiento_status_id"];
		$x_fecha_vencimiento = $row["fecha_vencimiento"];
		$x_importe = $row["importe"];
		$x_interes = $row["interes"];
		$x_interes_moratorio = $row["interes_moratorio"];


		//DESCUENTO
		/*
		$x_interes = round(($x_interes - ($x_interes * .66)));
		$x_interes = 145;
		$x_interes = round($x_interes,-1);		
		*/
		
		//comision promotor sobre interes o capital total o parcial segun el tipo de promotor
		//porcentaje de descuento pago anticipado 66% al final 
		
		//redondeo de centavos
		
		
		$x_total = $x_importe + $x_interes + $x_interes_moratorio;

		$x_total_pagos = $x_total_pagos + $x_importe;
		$x_total_interes = $x_total_interes + $x_interes;
		$x_total_moratorios = $x_total_moratorios + $x_interes_moratorio;
		$x_total_total = $x_total_total + $x_total;
		
		$x_vencimientos_id .= $x_vencimiento_id.",";
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>
<!--
<td><span class="phpmaker"><a href="<?php //if ($x_vencimiento_id <> "") {echo "php_reciboadd.php?vencimiento_id=" . urlencode($x_vencimiento_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>" target="_blank">Pagar</a></span></td>
--->
		<!-- vencimiento_id -->
		<td align="right"><span>
<?php echo $x_vencimiento_num; ?>
</span></td>
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
		<td align="center"><span>
<?php echo FormatDateTime($x_fecha_vencimiento,7); ?>
</span></td>
		<td align="right"><span>
<?php echo (is_numeric($x_saldo)) ? FormatNumber($x_saldo,2,0,0,1) : $x_saldo; ?>
</span></td>
		<!-- importe -->
		<td align="right"><span>
<?php echo (is_numeric($x_importe)) ? FormatNumber($x_importe,2,0,0,1) : $x_importe; ?>
</span></td>
		<!-- interes -->
		<td align="right"><span>
<?php echo (is_numeric($x_interes)) ? FormatNumber($x_interes,2,0,0,1) : $x_interes; ?>
</span></td>
		<!-- interes_moratorio -->
		<td align="right"><span>
<?php echo (is_numeric($x_interes_moratorio)) ? FormatNumber($x_interes_moratorio,2,0,0,1) : $x_interes_moratorio; ?>
</span></td>
		<td align="right"><span>
<?php echo (is_numeric($x_total)) ? FormatNumber($x_total,2,0,0,1) : $x_total; ?>
</span></td>
	</tr>
<?php
$x_saldo = $x_saldo - $x_importe;
	}
}

$x_vencimientos_id = substr($x_vencimientos_id,0,strlen($x_vencimientos_id)-1);

?>
<tr>
<td></td>
<td><span>
</span></td>
<td align="center"><span>
</span></td>
<td align="right"><span>
</span></td>
<td align="right"><span>
<b>
<?php echo FormatNumber($x_total_pagos,2,0,0,1); ?>
</b>
</span></td>
<td align="right"><span>
<b>
<?php echo FormatNumber($x_total_interes,2,0,0,1); ?>
</b>
</span></td>
<td align="right"><span>
<b>
<?php echo FormatNumber($x_total_moratorios,2,0,0,1); ?>
</b>
</span></td>
<td align="right"><span>
<b>
<?php echo FormatNumber($x_total_total,2,0,0,1); ?>
</b>
</span></td>
	</tr>
</table>
<br />
<br />
<span class="phpmaker">
<b>
TOTAL A PAGAR:&nbsp;<?php echo FormatNumber($x_total_total,2,0,0,1); ?>
</b>
</span>
<br />
<br />
<input type="hidden" name="x_vencimientos_id" value="<?php echo $x_vencimientos_id; ?>"  />
<input type="button"  name="Action" value="Aplicar Pagos" onclick="EW_checkMyForm()">

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

		// Field recibo_status_id
		$fieldList["`recibo_status_id`"] = 1;
	
/*	
		// Field vencimiento_id
//		$theValue = ($GLOBALS["x_vencimiento_id"] != "") ? intval($GLOBALS["x_vencimiento_id"]) : "NULL";
		$fieldList["`vencimiento_id`"] = $x_vencimiento_id;
*/
	
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
//		$theValue = ($GLOBALS["x_importe"] != "") ? " '" . doubleval($GLOBALS["x_importe"]) . "'" : "NULL";
		$fieldList["`importe`"] = 0;
	
		// insert into database
		$sSql = "INSERT INTO `recibo` (";
		$sSql .= implode(",", array_keys($fieldList));
		$sSql .= ") VALUES (";
		$sSql .= implode(",", array_values($fieldList));
		$sSql .= ")";
		phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$x_recibo_id = mysql_insert_id();



	$sSql = "SELECT * FROM vencimiento WHERE vencimiento_id in (".$GLOBALS["x_vencimientos_id"].")";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$x_total_pago = 0;
	while ($row = @phpmkr_fetch_array($rs)) {
		$x_vencimiento_id = $row["vencimiento_id"];
		$x_importe = $row["importe"];
		$x_interes = $row["interes"];
		$x_interes_moratorio = $row["interes_moratorio"];
		$x_total = $x_importe + $x_interes + $x_interes_moratorio;
		$x_total_pago = $x_total_pago + $x_total;
		
		$sSql = "update vencimiento set vencimiento_status_id = 5 where vencimiento_id =  ".$x_vencimiento_id;
		phpmkr_query($sSql, $conn);


		$sSql = "insert into recibo_vencimiento values(0,$x_vencimiento_id,$x_recibo_id)";
		$x_result = phpmkr_query($sSql, $conn);
		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}

	}
	phpmkr_free_result($rs);


	$sSql = "update recibo set importe = $x_total_pago where recibo_id =  ".$x_recibo_id;
	phpmkr_query($sSql, $conn);


//VALIDA PAGOS FALTANTES	

	$sSqlWrk = "SELECT credito_id  FROM vencimiento where vencimiento_id = ".$x_vencimiento_id;
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

