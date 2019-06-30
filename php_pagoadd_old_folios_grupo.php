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


$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	

?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString


$x_credito_id = @$_GET["credito_id"];
if (empty($x_credito_id)) {
	$x_credito_id = @$_POST["x_credito_id"];
}
$x_ref_loc = @$_GET["x_refloc"];
if (empty($x_ref_loc)) {
	$x_ref_loc = @$_POST["x_refloc"];
	if (empty($x_ref_loc)) {

		echo "<div align='center' class='phpmaker'>
		<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\" color=\"#CC3300\">
		No se selecciono un recibo para su aplicacion.<br><br>";
		echo "<a href=\"php_vencimientolist.php?credito_id=$x_credito_id\">Cerrar Ventana</a>
		</font>
		</div>";
		exit();
	}

}

// Get action
$sAction = @$_POST["a_add"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "L"; // Display blank record

}else{

	// Get fields from form
	$x_recibo_id = @$_POST["x_recibo_id"];
	$x_vencimiento_id = @$_POST["x_vencimiento_id"];
	$x_banco_id = @$_POST["x_banco_id"];		
	$x_medio_pago_id = @$_POST["x_medio_pago_id"];
	$x_referencia_pago = @$_POST["x_referencia_pago"];
	$x_referencia_pago2 = @$_POST["x_referencia_pago2"];	
	$x_fecha_registro = @$_POST["x_fecha_registro"];	
	$x_fecha_pago = @$_POST["x_fecha_pago"];		
	$x_importe_venc = @$_POST["x_importe_venc"];
	$x_interes_venc = @$_POST["x_interes_venc"];
	$x_iva_venc = @$_POST["x_iva_venc"];	
	$x_iva_mor_venc = @$_POST["x_iva_mor_venc"];		
	$x_interes_moratorio = @$_POST["x_interes_moratorio"];	
	$x_importe = @$_POST["x_importe"];		
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$x_ref_loc = Null;			
			$x_ref_loc2 = Null;						
			$x_vencimiento_id = Null;
			$x_vencimiento_num = Null;
			$x_vencimiento_status_id = Null;
			$x_fecha_vencimiento = Null;
			$x_importe_venc = Null;
			$x_importe = Null;			
			$x_interes_venc = Null;
			$x_iva_venc = Null;			
			$x_iva_mor_venc = Null;						
			$x_interes_moratorio = Null;
			$x_folio = Null;
			$x_nombre_completo = Null;

/*
			echo "
			<script type=\"text/javascript\">
			<!--
			window.opener.document.principal.submit();
			//-->
			</script>";
*/			


			header("Location:  php_vencimientolist.php?credito_id=$x_credito_id");
/*			
			echo "<div align='center' class='phpmaker'>
			<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\" color=\"#CC3300\">
			El pago ha sido aplicado.<br><br>";
			echo "<a href=\"php_vencimientolist.php?credito_id=$x_credito_id\">Cerrar Ventana</a>
			</font>
			</div>";
*/			
			exit();
			
			
		}
		break;
	case "L": // Add
//		$x_ref_loc = @$_POST["x_ref_loc"];	
		if(strlen($x_ref_loc) == 0){
			echo "<div align='center' class='phpmaker'>
			<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\" color=\"#CC3300\">
			La referencia no es valida verifiquela.<br><br>";
			echo "<a href=\"php_vencimientolist.php?credito_id=$x_credito_id\">Cerrar Ventana</a>
			</font>
			</div>";
			exit();
		
		}else{
			if(strpos($x_ref_loc,"/") == 0){
				echo "<div align='center' class='phpmaker'>
				<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\" color=\"#CC3300\">
				La referencia no es valida verifiquela.<br><br>";
				echo "<a href=\"php_vencimientolist.php?credito_id=$x_credito_id\">Cerrar Ventana</a>
				</font>
				</div>";
				exit();
			}else{
				$x_venc_id = substr($x_ref_loc,0,strpos($x_ref_loc,"/"));
				$x_venc_id = intval($x_venc_id);
				
				//tipo de credito
				$sSql = "select credito.credito_tipo_id from credito join vencimiento
				on vencimiento.credito_id = credito.credito_id where vencimiento.vencimiento_id = $x_venc_id";				
				$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
				$rowwrk = phpmkr_fetch_array($rswrk);
				$x_credito_tipo_id = $rowwrk["credito_tipo_id"];				
				phpmkr_free_result($rswrk2);
				
				
				if($x_credito_tipo_id == 1){
				$sSql = "select vencimiento.*, solicitud.folio, solicitud.promotor_id, cliente.nombre_completo, credito.credito_id, credito.credito_num, credito.cliente_num 
				from vencimiento join credito 
				on credito.credito_id = vencimiento.credito_id join solicitud
				on solicitud.solicitud_id = credito.solicitud_id join solicitud_cliente
				on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente 
				on cliente.cliente_id = solicitud_cliente.cliente_id
				where vencimiento.vencimiento_id = ".$x_venc_id;
				}else{
					$sSql = "select vencimiento.*, solicitud.folio, solicitud.promotor_id, solicitud.grupo_nombre as nombre_completo, credito.credito_id, credito.credito_num, credito.cliente_num 
					from vencimiento join credito 
					on credito.credito_id = vencimiento.credito_id join solicitud
					on solicitud.solicitud_id = credito.solicitud_id 
					where vencimiento.vencimiento_id = ".$x_venc_id;
				}
				$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
				if(phpmkr_num_rows($rswrk) > 0){
					$rowwrk = phpmkr_fetch_array($rswrk);
					$x_val_promo = true;
					if(($rowwrk["vencimiento_status_id"] != 1) && ($rowwrk["vencimiento_status_id"] != 3) && ($rowwrk["vencimiento_status_id"] != 6)) {
						echo "<div align='center' class='phpmaker'>
						<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\" color=\"#CC3300\">
						El vencimiento ya esta pagado o cancelado, verifique.<br><br>";
						echo "<a href=\"php_vencimientolist.php?credito_id=$x_credito_id\">Cerrar Ventana</a>
						</font>
						</div>";
						exit();
																	
					}

					$x_vencimiento_id = $rowwrk["vencimiento_id"];
					$x_vencimiento_num = $rowwrk["vencimiento_num"];				
					$x_credito_id = $rowwrk["credito_id"];				
					$x_credito_num = $rowwrk["credito_num"];									
					$x_cliente_num = $rowwrk["cliente_num"];														
					$x_vencimiento_status_id = $rowwrk["vencimiento_status_id"];								
					$x_fecha_vencimiento = $rowwrk["fecha_vencimiento"];
					$x_importe_venc = $rowwrk["importe"];
					$x_interes_venc = $rowwrk["interes"];									
					$x_iva_venc = $rowwrk["iva"];														
					$x_iva_mor_venc = $rowwrk["iva_mor"];																			
					if(empty($x_iva_venc)){
						$x_iva_venc = 0;
					}
					if(empty($x_iva_mor_venc)){
						$x_iva_mor_venc = 0;
					}
					
					$x_interes_moratorio = $rowwrk["interes_moratorio"];																
					$x_folio = $rowwrk["folio"];															
					$x_nombre_completo = $rowwrk["nombre_completo"];		


					//VALIDA QUE 	EL PAGO NO SEA SALETADO
					
				$sSql = "select count(*) as salteado from vencimiento where credito_id = $x_credito_id and vencimiento_num < $x_vencimiento_num and vencimiento_status_id in (3)";
				$rswrk2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
					$rowwrk2 = phpmkr_fetch_array($rswrk2);
					$x_salteado = $rowwrk2["salteado"];
					phpmkr_free_result($rswrk2);
					if($x_salteado > 0){
						echo "<div align='center' class='phpmaker'>
						<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\" color=\"#CC3300\">
						El credito tiene pagos anteriores vencidos, no se pueden aplicar pagos salteado.<br><br>";
						echo "<a href=\"php_vencimientolist.php?credito_id=$x_credito_id\">Cerrar Ventana</a>
						</font>
						</div>";
						exit();
					}
				}else{
					echo "<div align='center' class='phpmaker'>
					<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\" color=\"#CC3300\">
					La referencia no fue localizada, verifiquela.<br><br>";
					echo "<a href=\"php_vencimientolist.php?credito_id=$x_credito_id\">Cerrar Ventana</a>
					</font>
					</div>";
					exit();
				}
				phpmkr_free_result($rswrk);
			}
		}
		break;		
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>e - SF >  FINANCIERA CRECE - PAGOS </title>
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="JavaScript1.2" src="menu/stlib.js"></script>
<SCRIPT TYPE="text/javascript">
<!--
window.focus();
//-->
</SCRIPT>
</head>
<body>

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

	EW_this.x_importe.value = Number(EW_this.x_importe_venc.value) + Number(EW_this.x_interes_venc.value) + Number(EW_this.x_interes_moratorio.value) + Number(EW_this.x_iva_venc.value) + Number(EW_this.x_iva_mor_venc.value);

}

function EW_checkMyForm() {
EW_this = document.pagoadd;
validada = true;


//Solo importes iguales o menores

x_imp_venc = Number(EW_this.x_importe_venc.value) + Number(EW_this.x_interes_venc.value) + Number(EW_this.x_interes_moratorio.value) + Number(EW_this.x_iva_venc.value) + Number(EW_this.x_iva_mor_venc.value);


x_imp_venc = Math.round(x_imp_venc*100)/100;

if(Number(EW_this.x_importe.value) > Number(x_imp_venc)){
	if (!EW_onError(EW_this, EW_this.x_importe, "TEXT", "El importe de pago no puede ser mayor al importe del vencimiento. " + x_imp_venc))
		validada = false;
}

if (validada == true && EW_this.x_banco_id && !EW_hasValue(EW_this.x_banco_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_banco_id, "SELECT", "EL banco y cuenta son requeridos."))
		validada = false;
}

if (validada && EW_this.x_medio_pago_id && !EW_hasValue(EW_this.x_medio_pago_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_medio_pago_id, "SELECT", "El medio de pago es requerido."))
		validada = false;
}
if (validada && EW_this.x_referencia_pago && !EW_hasValue(EW_this.x_referencia_pago, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_referencia_pago, "TEXT", "La referencia de pago 1 es requerida."))
		validada = false;
}
if (validada && EW_this.x_referencia_pago2 && !EW_hasValue(EW_this.x_referencia_pago2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_referencia_pago2, "TEXT", "La referencia de pago 2 es requerida."))
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
<script type="text/javascript" src="jscalendar/lang/calendar-es.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<p align="center"><span class="phpmaker">PAGOS
<br>
<br>
<a href="php_vencimientolist.php?credito_id=<?php echo "$x_credito_id"; ?>">Cerrar ventana</a>
<br />
<br />
    
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
<input type="hidden" name="x_refloc" value="<?php echo $x_ref_loc; ?>">
<input type="hidden" name="x_hoy" value="<?php echo $currdate; ?>">
<input type="hidden" name="x_vencimiento_id" value="<?php echo $x_vencimiento_id; ?>">
<input type="hidden" name="x_fecha_vencimiento" value="<?php echo $x_fecha_vencimiento; ?>">
<input type="hidden" name="x_fecha_registro" value="<?php echo FormatDateTime(@$currdate,7); ?>">		
<input type="hidden" name="x_credito_id" value="<?php echo $x_credito_id; ?>"  />

<table width="500" align="center" class="ewTable_small">
	<tr>
	  <td height="50" class="ewTableHeaderThin"><div align="left">Cr&eacute;dito No.</div></td>
	  <td class="ewTableAltRow">
	    <div align="left"><?php echo $x_credito_num; ?>        </div></td>
    </tr>
	<tr>
	  <td class="ewTableHeaderThin"><div align="left">Cliente No.</div></td>
	  <td class="ewTableAltRow"><div align="left"><?php echo $x_cliente_num; ?>	  </div></td>
	  </tr>
	<tr>
		<td width="199" class="ewTableHeaderThin"><div align="left"><span>Vencimiento</span></div></td>
	  <td width="589" class="ewTableAltRow"><div align="left"><span>
	    <?php echo $x_vencimiento_num; ?>	  		
	    </span></div></td>
	</tr>
	<tr>
	  <td class="ewTableHeaderThin"><div align="left">Fecha Venc. </div></td>
	  <td class="ewTableAltRow"><div align="left">
      <?php echo FormatDateTime(@$x_fecha_vencimiento,7); ?></div></td>
	  </tr>
	<tr>
	  <td class="ewTableHeaderThin">Banco</td>
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
		<td class="ewTableHeaderThin"><div align="left"><span>Medio de pago</span></div></td>
		<td class="ewTableAltRow"><div align="left"><span>
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
        </span></div></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><div align="left"><span>Referencia de pago</span> 1</div></td>
		<td class="ewTableAltRow"><div align="left"><span>
          <input type="text" name="x_referencia_pago" id="x_referencia_pago" size="30" maxlength="50" value="<?php echo htmlspecialchars(@$x_ref_loc) ?>">
        </span></div></td>
	</tr>
	<tr>
	  <td class="ewTableHeaderThin"><div align="left"><span>Referencia de pago</span> 2</div>	    </td>
	  <td class="ewTableAltRow"><div align="left"><span>
        <input type="text" name="x_referencia_pago2" id="x_referencia_pago2" size="30" maxlength="50" value="<?php echo htmlspecialchars(@$x_referencia_pago2) ?>" />
      </span></div></td>
    </tr>
	<tr>
		<td class="ewTableHeaderThin"><div align="left"><span>Fecha de pago </span></div></td>
		<td class="ewTableAltRow"><div align="left"><span>
  <input type="text" name="x_fecha_pago" id="x_fecha_pago" value="<?php echo FormatDateTime(@$x_fecha_vencimiento,7); ?>">
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
        </span></div></td>
	</tr>
	<tr>
	  <td class="ewTableHeaderThin"><div align="left">Importe</div></td>
	  <td class="ewTableAltRow">
	    <div align="left"><span>
          <input style="text-align:right" type="text" name="x_importe_venc" value="<?php echo FormatNumber(@$x_importe_venc,2,0,0,0); ?>" onKeyPress="return noenter(this,event)"/>
        </span>        </div></td>
    </tr>
	<tr>
	  <td class="ewTableHeaderThin"><div align="left">interes</div></td>
	  <td class="ewTableAltRow">
	    <div align="left"><span>
          <input style="text-align:right" type="text" name="x_interes_venc" value="<?php echo FormatNumber(@$x_interes_venc,2,0,0,0); ?>" onKeyPress="return noenter(this,event)" />	  
        </span>        </div></td>
    </tr>
	<tr>
	  <td class="ewTableHeaderThin">IVA</td>
	  <td class="ewTableAltRow"><div align="left"><span>
	    <input style="text-align:right" type="text" name="x_iva_venc" value="<?php echo FormatNumber(@$x_iva_venc,2,0,0,0); ?>" onkeypress="return noenter(this,event)" />
      </span></div></td>
    </tr>
	<tr>
	  <td class="ewTableHeaderThin"><div align="left">moratorios</div></td>
	  <td class="ewTableAltRow">
	    <div align="left"><span>
          <input style="text-align:right" type="text" name="x_interes_moratorio" value="<?php echo FormatNumber(@$x_interes_moratorio,2,0,0,0); ?>"  onKeyPress="return noenter(this,event)" />	  	  
        </span>        </div></td>
    </tr>
	<tr>
	  <td class="ewTableHeaderThin">IVA Moratorios</td>
	  <td class="ewTableAltRow"><div align="left"><span>
	    <input name="x_iva_mor_venc" type="text" id="x_iva_mor_venc" style="text-align:right" onkeypress="return noenter(this,event)" value="<?php echo FormatNumber(@$x_iva_mor_venc,2,0,0,0); ?>" />
      </span></div></td>
    </tr>
	<tr>
		<td class="ewTableHeaderThin"><div align="left">Total a pagar </div></td>
		<td class="ewTableAltRow">
	      <div align="left">
	        <input style="text-align:right" name="x_importe" type="text" id="x_importe" value="<?php echo FormatNumber(@$x_importe_venc + @$x_interes_venc + @$x_interes_moratorio + @$x_iva_venc + @$x_iva_mor_venc ,2,0,0,0); ?>"   />		
          </div></td>
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
<p align="center">
<input type="button"  name="Action" value="Aplicar Pago" onclick="EW_checkMyForm()">
</p>

<?php } ?>
</form>

<?php if (@$sExport == "") { ?>
		<p>&nbsp;</p>
	</td>
</tr>
</table>
<?php } ?>
</body>
</html>

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
		$GLOBALS["x_referencia_pago2"] = $row["referencia_pago_2"];		
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
	/*
	$theValue = ($GLOBALS["x_vencimiento_id"] != "") ? intval($GLOBALS["x_vencimiento_id"]) : "NULL";
	$fieldList["`vencimiento_id`"] = $theValue;
	*/

	$theValue = ($GLOBALS["x_banco_id"] != "") ? intval($GLOBALS["x_banco_id"]) : "0";
	$fieldList["`banco_id`"] = $theValue;
	
	// Field medio_pago_id
	$theValue = ($GLOBALS["x_medio_pago_id"] != "") ? intval($GLOBALS["x_medio_pago_id"]) : "NULL";
	$fieldList["`medio_pago_id`"] = $theValue;

	// Field referencia_pago
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_pago"]) : $GLOBALS["x_referencia_pago"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`referencia_pago`"] = $theValue;

	// Field referencia_pago
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_pago2"]) : $GLOBALS["x_referencia_pago2"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`referencia_pago_2`"] = $theValue;

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
	$x_recibo_id = mysql_insert_id();



$sSql = "insert into recibo_vencimiento values(0,".$GLOBALS["x_vencimiento_id"].",$x_recibo_id)";
$x_result = phpmkr_query($sSql, $conn);
if(!$x_result){
	echo phpmkr_error() . '<br>SQL: ' . $sSql;
	phpmkr_query('rollback;', $conn);	 
	exit();
}

//

$sSql = "select * from vencimiento where vencimiento_id =  ".$GLOBALS["x_vencimiento_id"];
$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$rowwrk = phpmkr_fetch_array($rswrk);
$x_s_vencimiento_id = $rowwrk["vencimiento_id"];
$x_s_vencimiento_num = $rowwrk["vencimiento_num"];		
$x_s_credito_id = $rowwrk["credito_id"];
$x_s_vencimiento_status_id = $rowwrk["vencimiento_status_id"];
$x_s_fecha_vencimiento = $rowwrk["fecha_vencimiento"];
$x_s_importe = $rowwrk["importe"];
$x_s_interes = $rowwrk["interes"];
$x_s_iva = $rowwrk["iva"];
$x_s_iva_mor = $rowwrk["iva_mor"];
$x_s_interes_moratorio = $rowwrk["interes_moratorio"];
$x_s_importe_total = $rowwrk["total_venc"];	
phpmkr_free_result($rowwrk);


if(empty($x_s_iva)){
	$x_s_iva = 0;
}
if(empty($x_s_iva_mor)){
	$x_s_iva_mor = 0;
}

$x_s_importe_total = $x_s_importe + $x_s_interes + $x_s_interes_moratorio + $x_s_iva + $x_s_iva_mor;

$GLOBALS["x_importe"] = FormatNumber($GLOBALS["x_importe"],2,0,0,0);
$x_s_importe_total = FormatNumber($x_s_importe_total,2,0,0,0);

if($GLOBALS["x_importe"] == $x_s_importe_total){
	$sSql = "update vencimiento set 
	vencimiento_status_id = 2,
	importe = ".$GLOBALS["x_importe_venc"].",
	interes = ".$GLOBALS["x_interes_venc"].",
	iva = ".$GLOBALS["x_iva_venc"].",	
	iva_mor = ".$GLOBALS["x_iva_mor_venc"].",		
	interes_moratorio = ".$GLOBALS["x_interes_moratorio"].",
	total_venc = ".$GLOBALS["x_importe"]." 
	where vencimiento_id =  ".$GLOBALS["x_vencimiento_id"];
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
}

if($GLOBALS["x_importe"] < $x_s_importe_total){


	$x_por1 = (($x_s_importe * 100) / $x_s_importe_total);
	$x_d_importe = ($GLOBALS["x_importe"] * ($x_por1 / 100));

	$x_por2 = (($x_s_interes * 100) / $x_s_importe_total);
	$x_d_interes = ($GLOBALS["x_importe"] * ($x_por2 / 100));

	if($x_s_interes_moratorio > 0){
		$x_por3 = (($x_s_interes_moratorio * 100) / $x_s_importe_total);
		$x_d_interes_moratorio = ($GLOBALS["x_importe"] * ($x_por3 / 100));
	}else{
		$x_d_interes_moratorio = 0;
	}

	if($x_s_iva > 0){
		$x_por5 = (($x_s_iva * 100) / $x_s_importe_total);
		$x_d_iva = ($GLOBALS["x_importe"] * ($x_por5 / 100));
	}else{
		$x_d_iva = 0;
	}

	if($x_s_iva_mor > 0){
		$x_por6 = (($x_s_iva_mor * 100) / $x_s_importe_total);
		$x_d_iva_mor = ($GLOBALS["x_importe"] * ($x_por6 / 100));
	}else{
		$x_d_iva_mor = 0;
	}

	$sSql = "update vencimiento set 
	vencimiento_status_id = 2,
	importe = $x_d_importe,
	interes = $x_d_interes,
	iva = $x_d_iva,	
	iva_mor = $x_d_iva_mor,		
	interes_moratorio = $x_d_interes_moratorio,
	total_venc = ".$GLOBALS["x_importe"]." 
	where vencimiento_id =  ".$GLOBALS["x_vencimiento_id"];
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);

	$x_d_importe = $x_s_importe - $x_d_importe;
	$x_d_interes = $x_s_interes - $x_d_interes;
	$x_d_iva = $x_s_iva - $x_d_iva;	
	$x_d_iva_mor = $x_s_iva_mor - $x_d_iva_mor;		
	$x_d_interes_moratorio = $x_s_interes_moratorio - $x_d_interes_moratorio;
	$x_d_importe_total = $x_d_importe + $x_d_interes + $x_d_interes_moratorio + $x_d_iva + $x_d_iva_mor;	

	
	$sSql = "insert into vencimiento values(0,$x_s_credito_id, $x_s_vencimiento_num,1, '$x_s_fecha_vencimiento', $x_d_importe, $x_d_interes, $x_d_interes_moratorio, $x_d_iva, $x_d_iva_mor, $x_d_importe_total,'NULL')";
	$x_result = phpmkr_query($sSql, $conn);
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
		phpmkr_query('rollback;', $conn);	 
		exit();
	}
}

/*
if($GLOBALS["x_importe"] > $x_s_importe_total){
	$sSql = "update vencimiento set vencimiento_status_id = 2
	where vencimiento_id =  ".$GLOBALS["x_vencimiento_id"];
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);

	$x_d_importe = $GLOBALS["x_importe_venc"] - $x_s_importe ;
	$x_d_interes = $GLOBALS["x_interes_venc"] - $x_s_interes;
	$x_d_interes_moratorio = $GLOBALS["x_interes_moratorio"] - $x_s_interes_moratorio;
	$x_d_importe_total = $GLOBALS["x_importe"] - $x_s_importe_total;	


	$sSql = "select * from vencimiento where credito_id = $x_s_credito_id and vencimiento_status_id = 1 order by fecha_vencimiento, vencimiento_id";
	$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	

	while (($x_d_importe_total > 0) && ($rowwrk = phpmkr_fetch_array($rswrk))){
		$x_s_vencimiento_id = $rowwrk["vencimiento_id"];
		$x_s_vencimiento_num = $rowwrk["vencimiento_num"];		
		$x_s_credito_id = $rowwrk["credito_id"];
		$x_s_vencimiento_status_id = $rowwrk["vencimiento_status_id"];
		$x_s_fecha_vencimiento = $rowwrk["fecha_vencimiento"];
		$x_s_importe = $rowwrk["importe"];
		$x_s_interes = $rowwrk["interes"];
		$x_s_interes_moratorio = $rowwrk["interes_moratorio"];
		$x_s_importe_total = $rowwrk["total_venc"];	

		if($x_d_importe_total == $x_s_importe_total){
			$sSql = "update vencimiento set vencimiento_status_id = 2
			where vencimiento_id =  ".$x_s_vencimiento_id;
			phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$x_d_importe_total = 0;
			
			$sSql = "insert into recibo_vencimiento values(0,$x_s_vencimiento_id,$x_recibo_id)";
			$x_result = phpmkr_query($sSql, $conn);
			if(!$x_result){
				echo phpmkr_error() . '<br>SQL: ' . $sSql;
				phpmkr_query('rollback;', $conn);	 
				exit();
			}

			
		}else{
		
			if($x_d_importe_total < $x_s_importe_total){
	
				$sSql = "update vencimiento set 
				vencimiento_status_id = 2,
				importe = ".$x_d_importe.",
				interes = ".$x_d_interes.",
				interes_moratorio = ".$x_d_interes_moratorio.",
				total_venc = ".$x_d_importe_total." 
				where vencimiento_id =  ".$x_s_vencimiento_id;
				phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	
				
				$sSql = "insert into recibo_vencimiento values(0,$x_s_vencimiento_id,$x_recibo_id)";
				$x_result = phpmkr_query($sSql, $conn);
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
	
				$x_d_importe = $x_s_importe - $x_d_importe;
				$x_d_interes = $x_s_interes - $x_d_interes;
				$x_d_interes_moratorio = $x_s_interes_moratorio - $x_d_interes_moratorio;
				$x_d_importe_total = $x_s_importe_total - $x_d_importe_total;	
			
				$sSql = "insert into vencimiento values(0,$x_s_credito_id, $x_s_vencimiento_num,1, '$x_s_fecha_vencimiento', $x_d_importe, $x_d_interes, $x_d_interes_moratorio, $x_d_importe_total)";
				$x_result = phpmkr_query($sSql, $conn);
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
				$x_d_importe_total = 0;
			}else{
				if($x_d_importe_total > $x_s_importe_total){
		
					$x_d_importe = $x_d_importe - $x_s_importe ;
					$x_d_interes = $x_d_interes - $x_s_interes;
					$x_d_interes_moratorio = $x_d_interes_moratorio - $x_s_interes_moratorio;
					$x_d_importe_total = $x_d_importe_total - $x_s_importe_total;	
		
					$sSql = "update vencimiento set vencimiento_status_id = 2
					where vencimiento_id =  ".$x_s_vencimiento_id;
					phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
					
					
					$sSql = "insert into recibo_vencimiento values(0,$x_s_vencimiento_id,$x_recibo_id)";
					$x_result = phpmkr_query($sSql, $conn);
					if(!$x_result){
						echo phpmkr_error() . '<br>SQL: ' . $sSql;
						phpmkr_query('rollback;', $conn);	 
						exit();
					}

				
				}
			}
		}
		

	}
	phpmkr_free_result($rowwrk);
	
}

*/

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

	$sSqlWrk = "SELECT count(*) as pendientes FROM vencimiento where credito_id = $x_credito_id and vencimiento_status_id in (1,3,6)";
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
		
		$GLOBALS["x_folio_triple"] = 1;
		//seleccionam22os los vencimientos
		$sqlVencimientos = "SELECT * FROM vencimiento where credito_id = $x_credito_id";
		$responseVen = phpmkr_query($sqlVencimientos,$conn)  or die("Error en select vencimientos".phpmkr_error()."sql:".$sqlVencimientos);
		
		$x_vencimiento_id = "";
		$x_fecha_vencimiento = "";
		while($rowVencimientos = phpmkr_fetch_array($responseVen)){
			echo "entro a vencimeinto <P>";
			echo "<p>";
			// mientras hay vencimientos
			$x_vencimiento_id = $rowVencimientos["vencimiento_id"];
			$x_fecha_vencimiento_act = $rowVencimientos["fecha_vencimiento"];
			$GLOBALS["x_fecha_vencimiento_ACT"] = $rowVencimientos["fecha_vencimiento"];
			
			$sqlRecibo_Vencimientos = "SELECT * FROM recibo_vencimiento  where vencimiento_id = $x_vencimiento_id";
			$responseREC_VEN = phpmkr_query($sqlRecibo_Vencimientos,$conn)  or die("Error en selectrecibo_vencimientos".phpmkr_error()."sql:".$sqlRecibo_Vencimientos);
			while($rowRec_ven = phpmkr_fetch_array($responseREC_VEN)){
				
				echo "entro a ven_recibo";
				$x_recibo_vencimiento_id = $rowRec_ven["recibo_vencimiento_id"];
				$x_recibo_id =  $rowRec_ven["recibo_id"];
				
				
				$sqlRecibo = "SELECT * FROM recibo  where recibo_id = $x_recibo_id";
				$responseRec = phpmkr_query($sqlRecibo ,$conn)  or die("Error en select recibo".phpmkr_error()."sql:".$sqlRecibo );
				
				while($rowRecibos = phpmkr_fetch_array($responseRec)){
					echo "entro a recibo<p>";
					$x_recibo_id = $rowRecibos["recibo_id"];
					$x_fecha_pago = $rowRecibos["fecha_pago"];
					
					$fecha_del_vencimiento = $GLOBALS["x_fecha_vencimiento_ACT"];
					/*$fecha_del_vencimiento = strtotime($fecha_del_vencimiento);   
					$fecha_del_pago = '"'.$x_fecha_pago.'"'
					$fecha_del_pago = strtotime($fecha_del_pago);*/
					
					echo "fecha_vencimiento".$fecha_del_vencimiento."<p>";				
					echo "fecha_pago".$x_fecha_pago."<p>";
					echo "fecha_del_vencimiento == fecha_del_pago".$fecha_del_vencimiento == $fecha_del_pago."";
					echo "fecha_del_pago < fecha_del_vencimiento".$fecha_del_pago < $fecha_del_vencimiento."-";
					if(($fecha_del_vencimiento == $x_fecha_pago)||($x_fecha_pago < $fecha_del_vencimiento)){
						echo("fecha vencimiento es igual a fecha pago o es menor la fecha de vencimiento");
						if($GLOBALS["x_folio_triple"] == 1){
						$GLOBALS["x_folio_triple"] = 1;	
						
						
						echo "folio triple es =".$GLOBALS["x_folio_triple"]."-<p>"; 
						}
					}else{
						
						echo "fecha pago es mayo a fecha vencimeinto";
							$GLOBALS["x_folio_triple"] = 0;
								echo "folio triple es =".$GLOBALS["x_folio_triple"]."-<p>"; 
							}
					
					
					
					}//while recibos
					phpmkr_free_result($responseRec);
				
				}	// while recibo vencimeinto
				phpmkr_free_result($responseREC_VEN);
		
													
		}// fin while vencimientos
		 phpmkr_free_result($responseVen);
		 
		 // compara fechas
		 
		
		 
		
		//$fecha_actual = strtotime(date("d-m-Y H:i:00",time())); 
		//$fecha_inicio_sorteo = strtotime("2011-04-01");
		//$fecha_limite_sorteo = strtotime("2011-12-04");
		
		$fecha_actual = strtotime(date("Y-m-d ",time())); 
		$fecha_inicio_sorteo = strtotime("2011-04-01");
		$fecha_limite_sorteo = strtotime("2011-12-10");
		//$fecha_limite_sorteo = strtotime("04-04-2011 21:00:00");
		
		if($GLOBALS["x_folio_triple"] == 1){
			//pagos puntuales 3 folios	
			echo "entro a folio triple 1";	  
		if(($fecha_actual > $fecha_inicio_sorteo)&&($fecha_actual < $fecha_limite_sorteo)){ 
		//fecha valida para generar los folios de la rifa
				echo "entro a fecha del sorteo";
			//generamos la clave del folio
			$x_cont = 1;
			while($x_cont <= 3){
				echo "entro a while 3<p>";
				
					$sSqlFOL = "SELECT count(*) as folios FROM folio_rifa ";
					$rswrkF = phpmkr_query($sSqlFOL,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlFOL);
					$rowwrkF = phpmkr_fetch_array($rswrkF);
					$x_numero_folios = $rowwrkF["folios"];
					@phpmkr_free_result($rswrkF);

				
				if($x_numero_folios < 2000){
					echo "numero de folios menor de 2000";
			//alimentamos el generador de aleatorios
			//mt_srand((double)microtime()*1000000);			
			//mt_srand (microtime()*1000000);
			//generamos un número aleatorio
			//$numero_aleatorio = mt_rand(1,2000);
		// SE CAMBIA LA FORMA DE GENERAR EL FOLIO A HORA SERA CONSECUTIVO
		
		$sqlFolio = "SELECT MAX(folio) as ultimo_folio FROM folio_rifa";
		$responseFolio = phpmkr_query($sqlFolio,$conn)or die("error en folio max".phpmkr_error()."sql: ".$sqlFolio);
		$rowFolio = phpmkr_fetch_array($responseFolio);
		$x_ultimo_folio =  $rowFolio["ultimo_folio"];
		$x_folio_nuevo = intval($x_ultimo_folio)+ 1;
			/*
			$GLOBALS["rowNA"] = 1;
			while($GLOBALS["rowNA"] == 1){
				// el numero ya existe se genera otro
				mt_srand (microtime()*1000000);
				$numero_aleatorio = mt_rand(1,2000);
					
				echo "folio es :".$numero_aleatorio."<p>";
				$sqlNA ="SELECT count(*) as folio_repetido FROM folio_rifa WHERE folio = $numero_aleatorio";
				$responseNA = phpmkr_query($sqlNA, $conn) or die ("Error en select folio");
				$rowNA = phpmkr_fetch_array($responseNA);
				$x_folio_repetido = $rowNA["folio_repetido"];
				echo "folio repatido =".$x_folio_repetido."-<p>";
				if($x_folio_repetido == 0){
					$GLOBALS["rowNA"] = 2;					
					}		
			
			echo $numero_aleatorio. "<p>";
			
			
			}*/// fin while $rowNA= 1
			
			
			//insertar en la tabla folio_rifa
				$fieldList = NULL;	
				// Field visitas
				$theValue = ($x_solicitud_id != "") ? intval($x_solicitud_id) : "0";
				$fieldList["`solicitud_id`"] = $theValue;		
				
				// Field clave
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_nombre_completo) : $x_nombre_completo; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`nombre_completo`"] = $theValue;			
			
				// Field visitas
				$theValue = ($x_folio_nuevo != "") ? intval($x_folio_nuevo) : "0";
				$fieldList["`folio`"] = $theValue;				
				
				// insert into database
				$sSql = "INSERT INTO `folio_rifa` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
				$x_result = phpmkr_query($sSql, $conn);
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
			
				}//fin numero de folios
				
				
				$x_cont++;
			} // while 3
			
			
		}// fechas sorteo	
				
				
			
				
			
			}else if($GLOBALS["x_folio_triple"] == 0){
				// pagos no puntales  1 folio
				echo "folio_triple = 0";
				
				if(($fecha_actual > $fecha_inicio_sorteo)&&($fecha_actual < $fecha_limite_sorteo)){ 
		//fecha valida para generar los folios de la rifa
		echo("fecha dentro sorteo bien");
			//generamos la clave del folio
			$x_cont = 1;
			while($x_cont <= 1){
				
				$sSqlFOL = "SELECT count(*) as folios FROM folio_rifa ";
				$rswrkF = phpmkr_query($sSqlFOL,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlFOL);
				$rowwrkF = phpmkr_fetch_array($rswrkF);
				$x_numero_folios = $rowwrkF["folios"];
				@phpmkr_free_result($rswrkF);				
				
				if($x_numero_folios < 2000 ){
			//alimentamos el generador de aleatorios
			//mt_srand((double)microtime()*1000000);			
			//mt_srand (microtime()*1000000);
			//generamos un número aleatorio
			//$numero_aleatorio = mt_rand(1,2000); 
			
			/*$rowNA = 1;
			while($rowNA == 1){
				// el numero ya existe se genera otro
				mt_srand (microtime()*1000000);
				$numero_aleatorio = mt_rand(1,2000);				
				$sqlNA ="SELECT count(*) as folio_repetido FROM folio_rifa WHERE folio = $numero_aleatorio";
				$responseNA = phpmkr_query($sqlNA, $conn) or die ("Error en insert folio");
				$rowNA = phpmkr_fetch_array($responseNA);
				$x_folio_repetido = $rowNA["folio_repetido"];
				if($x_folio_repetido == 0){
					$rowNA = 2;
					
					}		
			
			echo $numero_aleatorio. "<p>";
			
			
			}*/// fin while $rowNA= 1
			
			//folios consecutivos
		$sqlFolio = "SELECT MAX(folio) as ultimo_folio FROM folio_rifa";
		$responseFolio = phpmkr_query($sqlFolio,$conn)or die("error en folio max".phpmkr_error()."sql: ".$sqlFolio);
		$rowFolio = phpmkr_fetch_array($responseFolio);
		$x_ultimo_folio =  $rowFolio["ultimo_folio"];
		$x_folio_nuevo = intval($x_ultimo_folio)+ 1;
			
			
			
			//insertar en la tabla folio_rifa
				$fieldList = NULL;	
				// Field visitas
				$theValue = ($x_solicitud_id != "") ? intval($x_solicitud_id) : "0";
				$fieldList["`solicitud_id`"] = $theValue;
				// Field visitas
				// Field clave
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_nombre_completo) : $x_nombre_completo; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`nombre_completo`"] = $theValue;
				// Field visitas
				$theValue = ($x_folio_nuevo != "") ? intval($x_folio_nuevo) : "0";
				$fieldList["`folio`"] = $theValue;				
				
				// insert into database
				$sSql = "INSERT INTO `folio_rifa` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
				$x_result = phpmkr_query($sSql, $conn);
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
			
			
				}
			$x_cont++;
			} // while 3
			
			
		}// fechas sorteo
				
				
				}
		
		
		
		// GENERA CASO CRM PARA RENOVACION (RENOVACION POR LIQUIDACION)
		// valida que no haya ya un caso abierto para este credito
		
		$currentdate = getdate(time());		
		$currdate =$currentdate["year"]."-".$currentdate["mon"]."-".$currentdate["mday"];
		$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];	
		echo "currdate".$currdate;
		$currdate = ConvertDateToMysqlFormat($currdate);
		$sSqlWrk = "
		SELECT count(*) as caso_abierto
		FROM 
			crm_caso join crm_tarea
			on crm_tarea.crm_caso_id = crm_caso.crm_caso_id
		WHERE 
			crm_caso.crm_caso_tipo_id = 2
			AND crm_caso.crm_caso_status_id = 1
			AND crm_caso.credito_id = $x_credito_id
			AND crm_tarea.orden = 3
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_caso_abierto = $datawrk["caso_abierto"];		
		@phpmkr_free_result($rswrk);
		// si no existe un caso abierto, se abre uno.
		if($x_caso_abierto == 0){
			
			$sSqlWrk = "
			SELECT *
			FROM 
				crm_playlist
			WHERE 
				crm_playlist.crm_caso_tipo_id = 2
				AND crm_playlist.orden = 3
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_crm_playlist_id = $datawrk["crm_playlist_id"];
			$x_prioridad_id = $datawrk["prioridad_id"];	
			$x_asunto = $datawrk["asunto"];				
			//RENOVACION POR LIQUIDACION
			$x_asunto = $x_asunto." por liquidacion";
			$x_descripcion = $datawrk["descripcion"];		
			$x_tarea_tipo_id = $datawrk["tarea_fuente"];		
			$x_orden = $datawrk["orden"];	
			$x_dias_espera = $datawrk["dias_espera"];		
			$x_destino_id = $datawrk["destino_id"];					
			@phpmkr_free_result($rswrk);
		
/*		
			//Fecha Vencimiento
			$temptime = strtotime($currdate);	
			$temptime = DateAdd('w',$x_dias_espera,$temptime);
			$fecha_venc = strftime('%Y-%m-%d',$temptime);			
			$x_dia = strftime('%A',$temptime);
			if($x_dia == "SUNDAY"){
				$temptime = strtotime($fecha_venc);
				$temptime = DateAdd('w',1,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);
			}
			$temptime = strtotime($fecha_venc);
*/		
		
		
			$x_origen = 1;
			$x_bitacora = "Seguimiento - (".FormatDateTime($currdate,7)." - $currtime)";
		
			$x_bitacora .= "\n";
			$x_bitacora .= "$x_asunto - $x_descripcion ";	
	
	
			$sSqlWrk = "
			SELECT cliente.cliente_id
			FROM 
				cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id join solicitud on solicitud.solicitud_id = solicitud_cliente.solicitud_id join credito on credito.solicitud_id = solicitud.solicitud_id
			WHERE 
				credito.credito_id = $x_credito_id
			LIMIT 1
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_cliente_id = $datawrk["cliente_id"];
			@phpmkr_free_result($rswrk);
	
	
		
			$sSqlWrk = "
			SELECT usuario_id
			FROM 
				usuario
			WHERE 
				usuario.usuario_rol_id = $x_destino_id
			LIMIT 1
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_usuario_id = $datawrk["usuario_id"];
			@phpmkr_free_result($rswrk);
			
		
			$sSql = "INSERT INTO crm_caso values (0,2,1,1,$x_cliente_id,'".$currdate."',$x_origen,$x_usuario_id,'$x_bitacora','".$currdate."',NULL,$x_credito_id)";
		
			$x_result = phpmkr_query($sSql, $conn);
			$x_crm_caso_id = mysql_insert_id();	
	

	
			if($x_crm_caso_id > 0){
	
				$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$currdate',NULL,NULL,NULL, 1, 1, 2, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
			
				$x_result = phpmkr_query($sSql, $conn);
		
				$sSqlWrk = "
				SELECT 
					comentario_int
				FROM 
					credito_comment
				WHERE 
					credito_id = ".$x_credito_id."
				LIMIT 1
				";
				
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_comment_ant = $datawrk["comentario_int"];
				@phpmkr_free_result($rswrk);
		
		
				if(empty($x_comment_ant)){
					$sSql = "insert into credito_comment values(0, $x_credito_id, '$x_bitacora', NULL)";
				}else{
					$x_bitacora = $x_comment_ant . "\n\n------------------------------\n" . $x_bitacora;
					$sSql = "UPDATE credito_comment set comentario_int = '$x_bitacora' where credito_id = $x_credito_id";
				}
		
				phpmkr_query($sSql, $conn);
			}
			
		
		}
		
		
		
		
		
		
			
		
	}// pendientes = 0
	
// COMENTARIOS DE CARTERA VENCIDA
	$sSqlWrk = "SELECT count(*) as vencidos FROM vencimiento where credito_id = $x_credito_id and vencimiento_status_id = 3";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$rowwrk = phpmkr_fetch_array($rswrk);
	$x_vencidos = $rowwrk["vencidos"];
	@phpmkr_free_result($rswrk);

	if($x_vencidos == 0){
	//Elimina comentarios
	/*
		$sSqlWrk = "delete from credito_comment where credito_id = $x_credito_id";
		phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	*/
	}


	return true;
}
?>

