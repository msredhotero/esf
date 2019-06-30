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
					if(($rowwrk["vencimiento_status_id"] != 1) && ($rowwrk["vencimiento_status_id"] != 3) && ($rowwrk["vencimiento_status_id"] != 6) && ($rowwrk["vencimiento_status_id"] != 8)) {
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

// seleccionamos los datos del credito

$sSqlA = "SELECT forma_pago_id, garantia_liquida, penalizacion,num_pagos ,fecha_vencimiento FROM credito where credito_id = ".$x_s_credito_id."";
			$rsA = phpmkr_query($sSqlA,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSqlA);
			$rowA = phpmkr_fetch_array($rsA);
			$x_forma_pago_id = $rowA["forma_pago_id"];
			$x_c_forma_pago = $rowA["forma_pago_id"];			
			$x_garantia_liquida = $rowA["garantia_liquida"];
			$x_penalizacion = $rowA["penalizacion"];
			$x_num_pagos = $rowA["num_pagos"];
			
			$x_fecha_vencimiento_credito = $row1["fecha_vencimiento"];
			phpmkr_free_result($rs1);



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

#el importe solo puede ser igual o menor que el monto del vencimiento
// =========== CASO 2  ================//
					// EL IMPORTE DEL PAGO ES MENOR AL IMPORTE DEL VENCIMIENTO
					// EN ESTE CASO SE  MATA EL VENCIMIENTO POR LA CANTIDAD PAGADA
					// SI LA CANTIDAD FALTANTE ES MENOR A $100 SE VA A REMANENTE
					// SI LA CANTIDAD FALTANTE ES MAYOR A  $100 SE GENERA UN NUEVO VENCIMIENTO, CON LA MISMA FECHA POR LA CATIDAD RESTANTE
					//REMANENTE
					/*
					Si faltan menos de 100 pesos se genrea vencimiento con status remanente y con numero de vencimiento igual pero con una r la fecha de pago sera un periodo extra eal fin del la fecha de vencimiento del credito.
					*/




if($GLOBALS["x_importe"] < $x_s_importe_total){
	//credito_id
			
			$sqlFVR = "SELECT credito_id FROM vencimiento WHERE vencimiento_id =  ".$GLOBALS["x_vencimiento_id"]." ";
			$responseFVR = phpmkr_query($sqlFVR, $conn) or die ("error al seleccionar el credito_id". phpmkr_error()."sql: ".$sqlFVR);
			$rowfvr = phpmkr_fetch_array($responseFVR);
			$x_credito_id = $rowfvr["credito_id"];
			
		
	
			// fecha vencimeito para  remanente
			$x_fecha_vencimiento_remanente = "";
			$sqlFVR = "SELECT  fecha_vencimiento FROM vencimiento WHERE credito_id = ".$x_credito_id." ORDER BY vencimiento_num DESC LIMIT 1";
			$responseFVR = phpmkr_query($sqlFVR, $conn) or die ("error al seleccionar la fecha para remanente". phpmkr_error()."sql: ".$sqlFVR);
			$rowfvr = phpmkr_fetch_array($responseFVR);
			$x_fecha_vencimiento_remanente = $rowfvr["fecha_vencimiento"];
	
			$x_forma_pago_id = 0;
			$x_fecha_vencimiento_credito = 0;
			$sSql1 = "SELECT forma_pago_id, garantia_liquida, penalizacion,  fecha_vencimiento FROM credito where credito_id = ".$x_credito_id."";
			$rs1 = phpmkr_query($sSql1,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql1);
			$row1 = phpmkr_fetch_array($rs1);
			$x_forma_pago_id = $row1["forma_pago_id"];
			$x_c_forma_pago = $row1["forma_pago_id"];			
			$x_garantia_liquida = $row1["garantia_liquida"];
			$x_penalizacion = $row1["penalizacion"];
			
			$x_fecha_vencimiento_credito = $row1["fecha_vencimiento"];
			phpmkr_free_result($rs1);
			
			

			//echo "Selecciono forma de pago".$x_forma_pago_id."<br> y fecha de vencimeinto del credito".$x_fecha_vencimiento_credito."<br>";
			$x_forma_pago = 0;
			$sSql = "SELECT valor FROM forma_pago where forma_pago_id = ".$x_forma_pago_id;
			$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$row = phpmkr_fetch_array($rs);
			$x_forma_pago = $row["valor"];
			phpmkr_free_result($rs);
					
			
			
			
			$sqlVenNum = "SELECT COUNT(*) AS numero_de_pagos_d FROM vencimiento WHERE  fecha_vencimiento =  \"$x_s_fecha_vencimiento\" AND  credito_id =  $x_s_credito_id";
			$response = phpmkr_query($sqlVenNum, $conn) or die("error en numero de vencimiento".phpmkr_error()."sql:".$sqlVenNum);
			$rownpagos = phpmkr_fetch_array($response);  
			$x_numero_de_pagos_d =  $rownpagos["numero_de_pagos_d"];
	
	
			
$x_importe = $GLOBALS["x_importe"];
$x_s_interes_moratorio = $GLOBALS["x_interes_moratorio"];
$x_s_iva_mor = $GLOBALS["x_iva_mor_venc"];
$x_s_interes = $GLOBALS["x_interes_venc"];
$x_s_iva = $GLOBALS["x_iva_venc"];
 
 

if($x_importe > ($x_s_interes_moratorio + $x_s_iva_mor) || $x_importe == ($x_s_interes_moratorio + $x_s_iva_mor)){
 include_once("utilerias/datefunc.php");
		 
							$x_p_interes_moratorio = $x_s_interes_moratorio;
							$x_p_iva_mor = $x_s_iva_mor;
							
							$x_importe = $x_importe - ($x_s_interes_moratorio + $x_s_iva_mor);
							
							//interes ord
							if($x_importe > ($x_s_interes + $x_s_iva) || $x_importe == ($x_s_interes + $x_s_iva)){
					
						
								$x_p_interes = $x_s_interes;
								$x_p_iva = $x_s_iva;
					
								$x_importe = $x_importe - ($x_s_interes + $x_s_iva);			
								
								$x_p_importe = $x_importe;		
								$x_d_importe = $x_s_importe - $x_p_importe;
								$x_d_interes = 0;
								$x_d_iva = 0;
								$x_d_interes_moratorio = 0;
								$x_d_iva_mor = 0;
								$x_d_importe_total = $x_d_importe + $x_d_interes + $x_d_iva + $x_d_interes_moratorio + $x_d_iva_mor;
								
								//remanente < 100
								
								//echo "dias vencidos....".$x_dias_vencidos_m." ";
								if((intval($x_d_importe) < 100) && (intval($x_dias_vencidos_m) < 7)){
									//echo "dias vencidos menor que 7 y resto menor que $100";
									//echo "fecha vencieinto ultimo =".$x_fecha_vencimiento_remanente.".....";
									$x_s_vencimiento_status_id = 6;
									$x_s_fecha_vencimiento = DateAdd('w',$x_forma_pago,strtotime($x_fecha_vencimiento_remanente));
									$x_s_fecha_vencimiento = strftime('%Y-%m-%d',$x_s_fecha_vencimiento);			
									$x_s_vencimiento_num = intval($x_s_vencimiento_num) + 1000;
									$x_d_fecha_genera_remanente = ConvertDateToMysqlFormat($x_fecha_movimiento);
									//echo "aplico remanente....".$x_s_vencimiento_num.".... fecha ".$x_s_fecha_vencimiento." .....";
								}
								
							}else{
					
								if(!is_null($x_s_iva) && !empty($x_s_iva) && intval($x_s_iva) > 0){
									$x_p_interes = $x_importe / 1.16;
									$x_p_iva = $x_p_interes * .16;
								}else{
									$x_p_interes = $x_importe;
									$x_p_iva = 0;
								}
								
								$x_p_importe = 0;		
								$x_d_importe = $x_s_importe - $x_p_importe;
								$x_d_interes = $x_s_interes - $x_p_interes;
								$x_d_iva = $x_s_iva - $x_p_iva;
								$x_d_interes_moratorio = 0;
								$x_d_iva_mor = 0;
								$x_d_importe_total = $x_d_importe + $x_d_interes + $x_d_iva + $x_d_interes_moratorio + $x_d_iva_mor;
								
							}
						}else{
					
							if(!is_null($x_s_iva_mor) && !empty($x_s_iva_mor) && intval($x_s_iva_mor) > 0){
								$x_p_interes_moratorio = $x_importe / 1.16;
								$x_p_iva_mor = $x_p_interes_moratorio * .16;
							}else{
								$x_p_interes_moratorio = $x_importe;
								$x_p_iva_mor = 0;
							}
							$x_p_interes = 0;
							$x_p_iva = 0;
							$x_p_importe = 0;		
							
							$x_d_importe = $x_s_importe - $x_p_importe;
							$x_d_interes = $x_s_interes - $x_p_interes;
							$x_d_iva = $x_s_iva - $x_p_iva;
							$x_d_interes_moratorio = $x_s_interes_moratorio - $x_p_interes_moratorio;
							$x_d_iva_mor = $x_s_iva_mor - $x_p_iva_mor;
							$x_d_importe_total = $x_d_importe + $x_d_interes + $x_d_iva + $x_d_interes_moratorio + $x_d_iva_mor;
							
						}
						$x_p_importe_total = $x_p_importe + $x_p_interes + $x_p_iva + $x_p_iva_mor + $x_p_interes_moratorio;
					
						$sSql = "update vencimiento set 
						vencimiento_status_id = 2,
						importe = $x_p_importe,
						interes = $x_p_interes,
						iva = $x_p_iva,	
						iva_mor = $x_p_iva_mor,		
						interes_moratorio = $x_p_interes_moratorio,
						total_venc = ".$x_p_importe_total." 
						where vencimiento_id =  ".$x_s_vencimiento_id."";
						phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
						//echo $sSql;
					
					
					
						// la fecha en que se pago el vencimeinto es la fecha que se utiliza en el campo fecha genera remanente...						
					//	$x_d_fecha_genera_remanente = ConvertDateToMysqlFormat($x_fecha_movimiento);
																						
					
						if($x_s_vencimiento_num > 1000){
								$sSql = "insert into vencimiento values(0,$x_s_credito_id, $x_s_vencimiento_num,$x_s_vencimiento_status_id, '$x_s_fecha_vencimiento', $x_d_importe, $x_d_interes, $x_d_interes_moratorio, $x_d_iva, $x_d_iva_mor, $x_d_importe_total, '$x_d_fecha_genera_remanente')";
							}else{																					
						$sSql = "insert into vencimiento values(0,$x_s_credito_id, $x_s_vencimiento_num,$x_s_vencimiento_status_id, '$x_s_fecha_vencimiento', $x_d_importe, $x_d_interes, $x_d_interes_moratorio, $x_d_iva, $x_d_iva_mor, $x_d_importe_total, 'NULL')";
							}
						$x_result = phpmkr_query($sSql, $conn);
						//echo $sSql;
						if(!$x_result){
							echo phpmkr_error() . '<br>SQL: ' . $sSql;
							phpmkr_query('rollback;', $conn);	 
							exit();
						}
							//echo $sSql;			
					
					//actulaizamos el valor de importe
					$x_importe_fin = 0;		
					
					
																						
					//$sSql = "insert into recibo_vencimiento values(0,".$x_s_vencimiento_id .",$x_recibo_id)";
					//$x_result = phpmkr_query($sSql, $conn);
					//if(!$x_result){
						//echo phpmkr_error() . '<br>SQL: ' . $sSql;
						#phpmkr_query('rollback;', $conn);	 
						#exit();
					#}	


				
				
					
				




} # fin del caso dos



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

	$sSqlWrk = "SELECT credito_id FROM vencimiento where vencimiento_id = ".$GLOBALS["x_vencimiento_id"];
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$rowwrk = phpmkr_fetch_array($rswrk);
	$x_credito_id = $rowwrk["credito_id"];
	
	@phpmkr_free_result($rswrk);
	
	
	# VERIFICAMOS SI SOLO LE FALTA UN PAGAO, EL ULTIMO Y SI TIENE GARANTIA LIQUIDA Y EL CAMPO DE PENALIZACVIONES LLENO 
	# ENTONCES PAGAMOS EL ULTIMO VENCIMEINTO
	
	
	
	if(!empty($x_garantia_liquida) && $x_garantia_liquida > 0){
		//penalizacion de be estar lleno
		if(!empty($x_penalizacion) && $x_penalizacion > 0){
			
		$x_vencimientos_pendintes_por_cambio = 0;	
	if($x_c_forma_pago == 1){
				if($x_num_pagos == 40){
					$x_vencimientos_pendintes_por_cambio = 3;
					}else{		
						$x_vencimientos_pendintes_por_cambio = 2;
					}
				}else{
					if((($x_c_forma_pago == 1) || ($x_c_forma_pago == 4)) && ($x_num_pagos == 24)){
							$x_vencimientos_pendintes_por_cambio = 2;					
						}else{
							$x_vencimientos_pendintes_por_cambio = 1;
						}
				}
			
			
			$sSqlWrk = "SELECT count(*) as pendientes FROM vencimiento where credito_id = $x_credito_id and vencimiento_status_id in (1,3,6)";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$rowwrk = phpmkr_fetch_array($rswrk);
			$x_pendientes = $rowwrk["pendientes"];
			@phpmkr_free_result($rswrk);
			echo "pendiantes".$x_pendientes."pend nece".$x_vencimientos_pendintes_por_cambio."<br>";
			
			
			if($x_pendientes == $x_vencimientos_pendintes_por_cambio){
				// si solo le falta un pago y es el ultimo se paga con la garantia liquida
				//seleccionamos el importe de la garantia
			$SQLpp = "SELECT monto FROM garantia_liquida where credito_id = $x_credito_id ";
			$RSPP = phpmkr_query($SQLpp,$conn) or die ("Error al seleccionar los vencimeintos faltantes del credito". phpmkr_error()."sql:".$SQLpp);
			$ROWpp = phpmkr_fetch_array($RSPP);
			$x_monto_garantia = $ROWpp["monto"];
			
			
			// seleccionamos los vencimientos que tienen estatus de 9 y que corresponde al credito
				$sqlV9 = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id AND vencimiento_status_id IN (1,9) "; 
				$rsV9 = phpmkr_query($sqlV9,$conn)or die("Error al seleccionar los V9".phpmkr_error()."sql:".$sqlV9);
				$x_lista_v9_n = " ";
				$x_lista_v9_id = " ";
				
				while($rowV9 = phpmkr_fetch_array($rsV9)){
					$x_v9n = $rowV9["vencimiento_num"];
					$x_v9id = $rowV9["vencimiento_id"];
					$x_lista_v9_n = $x_lista_v9_n.$x_v9n.", ";
					$x_lista_v9_id = $x_lista_v9_id.$x_v9id.", ";
					}
					$x_lista_v9_n =  trim($x_lista_v9_n,", ");
					$x_lista_v9_ne = "SE PAGO CON GARANTIA, ".$x_lista_v9_n;
					$x_referencia_pago2 = $x_lista_v9_ne;
				    $x_lista_v9_id = trim($x_lista_v9_id,", ");
					
					echo "LSITA V9 ID".$x_lista_v9_id."<BR>";
					
				$fieldList = NULL;
					// Field recibo_status_id
					$fieldList["`recibo_status_id`"] = 1;
											
					$theValue = ($GLOBALS["x_banco_id"] != "") ? intval($GLOBALS["x_banco_id"]) : "0";
					$fieldList["`banco_id`"] = $theValue;
					
					// Field medio_pago_id
					$theValue = ($GLOBALS["x_medio_pago_id"] != "") ? intval($GLOBALS["x_medio_pago_id"]) : "NULL";
					$fieldList["`medio_pago_id`"] = $theValue;
				
					// Field referencia_pago
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_pago_1"]) : $GLOBALS["x_referencia_pago_1"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`referencia_pago`"] = $theValue;
				
					// Field referencia_pago
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_referencia_pago2) : $x_referencia_pago2; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`referencia_pago_2`"] = $theValue;
				
					// Field fecha_registro
					$theValue = ($GLOBALS["x_fecha_pago"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_pago"]) . "'" : "Null";
					$fieldList["`fecha_registro`"] = $theValue;
				
					// Field fecha_registro
					$theValue = ($x_fecha_movimiento != "") ? " '" . ConvertDateToMysqlFormat($x_fecha_movimiento) . "'" : "Null";
					$fieldList["`fecha_pago`"] = $theValue;
				
					// Field importe
					$theValue = ($x_monto_garantia != "") ? " '" . doubleval($x_monto_garantia) . "'" : "NULL";
					$fieldList["`importe`"] = $theValue;
				
					// insert into database
					$sSql = "INSERT INTO `recibo` (";
					$sSql .= implode(",", array_keys($fieldList));
					$sSql .= ") VALUES (";
					$sSql .= implode(",", array_values($fieldList));
					$sSql .= ")";
					phpmkr_query($sSql, $conn) or die("Failed to execute query....: " . phpmkr_error() . '<br>SQL: ' . $sSql);
				#	echo "INSERTA EN RECIBO".$sSql."<BR>";
					$x_recibo_id_v9 = mysql_insert_id();
				
				$x_lista_v9_id_a = explode(", ",$x_lista_v9_id);
				    //insertamos en recibo vecimeinto							
					foreach($x_lista_v9_id_a as $v9id){
						// por cada elemento en la lista, pagamos el vencimiento e insertamos en vencimiento_recibo						
						$sSql = "insert into recibo_vencimiento values(0,".$v9id.",$x_recibo_id_v9)";
						$x_result = phpmkr_query($sSql, $conn);
						echo "INSERTAMOS EN RECIBO_ENCIMEINTO: ii". mysql_insert_id()."-";
						echo "INSERTAMOS EN REC_VEN".$sSql."<BR>";
						if(!$x_result){
							echo phpmkr_error() . '<br>SQL: ' . $sSql;
							//phpmkr_query('rollback;', $conn);	 
							exit();
						}	
						
						//actualizamos el status del vencimiento a pagado
						$sqlUV9 = "UPDATE vencimiento SET vencimiento_status_id = 2 WHERE vencimiento_id = $v9id and vencimiento_status_id in (1,9)";
						$x_result = phpmkr_query($sqlUV9,$conn);
						if(!$x_result){
							echo phpmkr_error() . '<br>SQL: ' . $sqlUV9;
							//phpmkr_query('rollback;', $conn);	 
							exit();
						}	
						
						}	
			
						//actualizamos el status de la garantia
						$updag = "UPDATE  garantia_liquida SET status = 2 WHERE  credito_id = $x_credito_id and status= 1";
						$x_result = phpmkr_query($updag,$conn);
						if(!$x_result){
							echo phpmkr_error() . '<br>SQL: ' . $updag;
							//phpmkr_query('rollback;', $conn);	 
							exit();
						}	
			
			
				
				
				
				}// epndientes igual a pendientes por garantia
			
			
			}// penalizacion mayor a 0
		
		
		}// garantia mayor a 0

	$sSqlWrk = "SELECT solicitud_id, credito_tipo_id  FROM credito where credito_id = $x_credito_id";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$rowwrk = phpmkr_fetch_array($rswrk);
	$x_solicitud_id = $rowwrk["solicitud_id"];
	$x_tipo_credito = $rowwrk["credito_tipo_id"];
	@phpmkr_free_result($rswrk);

	$sSqlWrk = "SELECT count(*) as pendientes FROM vencimiento where credito_id = $x_credito_id and vencimiento_status_id in (1,3,6)";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$rowwrk = phpmkr_fetch_array($rswrk);
	$x_pendientes = $rowwrk["pendientes"];
	@phpmkr_free_result($rswrk);

	if($x_pendientes == 0){
			//FINIQUITA CREDITO	
			// selecciona status anterior			
			$sqlFVR2 = "SELECT credito_status_id FROM credito WHERE credito_id = $x_credito_id";
			$responseFVR2 = phpmkr_query($sqlFVR2, $conn) or die ("error al seleccionar el credito_id". phpmkr_error()."sql: ".$sqlFVR2);
			$rowfvr2 = phpmkr_fetch_array($responseFVR2);
			$x_credito_status_id = $rowfvr2["credito_status_id"];
					
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
		
		
		//FOLIOS PARA CREDITOS PERSONALES Y GRUPALES
		
		if($x_tipo_credito != 2){
		if($GLOBALS["x_folio_triple"] == 1){
			//pagos puntuales 3 folios	
			echo "entro a folio triple 1 en credito individual";	  
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
			//generamos un n�mero aleatorio
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
				echo $sSql."<br>";
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
			//generamos un n�mero aleatorio
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
			
				
				echo $sSql."<br>";
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
		} else if($x_tipo_credito == 2) {
			//FOLIOS PARA CREDITOS SOLIDARIOS
			echo "tipo de credito grupal<br>";
			// SELECCIONAMOS EL ID DEL GRUPO
			$sqlgid = "SELECT * FROM  creditosolidario  WHERE creditoSolidario_id IN(SELECT grupo_id FROM solicitud_grupo WHERE  solicitud_id = $x_solicitud_id)";
			$responsegid = phpmkr_query($sqlgid, $conn) or die("error al seleccionar los datos del gruopo".phpmkr_error()."sql;".$sqlgid);
			$rowGrupo = phpmkr_fetch_array($responsegid);
			echo  $sqlgid."<br>";
			$GLOBALS["x_creditoSolidario_id"] =  $rowGrupo["creditoSolidario_id"];
			$GLOBALS["x_nombre_grupo"] = $rowGrupo["nombre_grupo"];
			
			$x_cont_gid = 1;
			while($x_cont_gid <= 10){	
			echo "entro al while de folio para cada integrante <br> integrante ".$x_cont_gid."<br>";
				
				$x_cliente_id_grupo_actual = $rowGrupo["cliente_id_$x_cont_gid"];
				echo "cliente id".$x_cliente_id_grupo_actual."<br>";
			
			
			if(!empty($x_cliente_id_grupo_actual) && is_numeric($x_cliente_id_grupo_actual)){
			
		
			
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
			//generamos un n�mero aleatorio
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
				// Field solcitud_id
				$theValue = ($x_solicitud_id != "") ? intval($x_solicitud_id) : "0";
				$fieldList["`solicitud_id`"] = $theValue;
				
				// Field cliente_id
				$theValue = ($x_cliente_id_grupo_actual != "") ? intval($x_cliente_id_grupo_actual) : "0";
				$fieldList["`cliente_id`"] = $theValue;		
				
				
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
				// Field cliente_id
				$theValue = ($x_cliente_id_grupo_actual != "") ? intval($x_cliente_id_grupo_actual) : "0";
				$fieldList["`cliente_id`"] = $theValue;		
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
			
				echo  $sSql ."folio 1  <br>";
				
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
			
			
				}
			$x_cont++;
			} // while 3
			
			
		} // fechas sorteo
				
				
				}				
			
			}// nuerico no vacio
			
			
			$x_cont_gid ++;
			}
				
			
				
				
				
				
				
				
			
			
			
			}
		
		
		
		//verificamos si el credito es de los nuevos y sis tiene garantia liquida y penalizacion 
		
		# si el campo penalizacion esta lleno entonces el redito es de los nueves
		# si tiene garantia liquida entonces se liquida el credito si solo le faltara un vencimeinto pendiente
		
		
		
		//FOLIOS PARA CREDITOS SOLIDARIOS
		
		
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
		
		
		//insertamos en la tabla de liquidacion de credito
					$x_fecha_liquida = date("Y-m-d");
					$hora = getdate(time());
					$x_hora = $hora["hours"] . ":" . $hora["minutes"] . ":" . $hora["seconds"]; 
		
			//insertamos en la tabla de liquidacion
					$sqlLiquida = "INSERT INTO credito_liquidado (`credito_liquidado_id` ,`credito_id` ,`solicitud_id` ,";
					$sqlLiquida .= "`fecha` ,`hora` ,`status` ,`x` ,`xx` )";
					$sqlLiquida .= " VALUES (NULL , $x_credito_id, $x_solicitud_id, '$x_fecha_liquida', '$x_hora', '$x_credito_status_id', '1', '1');";
					$RSC = phpmkr_query($sqlLiquida, $conn) or die("error". phpmkr_error().$sqlLiquida) ;
		
			
		
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
	
	
	//insertamos el codigo para el mensaje que se ha recibido el pago  del cliente
	################################################################################################################## 
	#####################################################  SMS  ######################################################
	################################################################################################################## 		
	// mandamos el mensaje de pago efectuado
	
			$sqlClientel = " SELECT cliente_id FROM solicitud_cliente JOIN solicitud ON solicitud.solicitud_id = solicitud_cliente.solicitud_id JOIN ";
			$sqlClientel .= " credito ON credito.solicitud_id = solicitud.solicitud_id WHERE credito_id = $x_credito_id ";
			$rsClientel = phpmkr_query($sqlClientel, $conn) or die ("Error al seleccionar el cliente _id". phpmkr_error(). "sql:". $sqlClientel);
			$rowClientel = phpmkr_fetch_array($rsClientel);
			$x_cliente_idl = 	$rowClientel["cliente_id"];
			
				if (!empty($x_cliente_idl)){
		 $x_no_celular  = "";
		 $sqlCelular = "SELECT numero FROM telefono WHERE cliente_id = $x_cliente_idl  AND telefono_tipo_id = 2 ORDER BY `telefono_id` DESC ";
		 $rsCelular = phpmkr_query($sqlCelular, $conn) or die ("Error al seleccioanr el numero de celuar". phpmkr_error()."sql:".$sqlCelular);
		 $rowCelular = phpmkr_fetch_array($rsCelular);
		 $x_no_celular = $rowCelular["numero"];
		 $x_compania = $rowCelular["compania_id"];
		 
		// $x_no_celular = "5540663927";
		 
		 //SALDO
		$sql_saldo =  "SELECT SUM(total_venc)  as saldo FROM vencimiento WHERE credito_id = $x_credito_id  and vencimiento.vencimiento_status_id in (1,3,6)";
		$rs_saldo = phpmkr_query($sql_saldo, $conn) or die ("Error al seleccionar el saldo  en pago efectuado". phpmkr_error()."sql :". $sql_saldo);
		$row_saldo = phpmkr_fetch_array($rs_saldo);
		$x_saldo_por_pagar = FormatNumber($row_saldo["saldo"],2,0,0,0);		
		 
		 
		 // ULTIMO VENCIMIENTO
		$sql_ult_vec = "SELECT  num_pagos FROM credito WHERE credito_id = $x_credito_id";
		$rs_ult_ven = phpmkr_query($sql_ult_vec, $conn) or die ("Error al seleccionar ultimo paga en apago efectuado pagos". phpmkr_error()."sql:". $sql_ult_vec);
		$row_ult_ven = phpmkr_fetch_array($rs_ult_ven);
		$x_no_ultimo_vencimiento = $row_ult_ven["num_pagos"];
		
		
		//VENCIMEINTO PAGADO
		$sql_ult_vec = "SELECT  vencimiento_num FROM vencimiento WHERE vencimiento_id = ".$GLOBALS["x_vencimiento_id"]." ";
		$rs_ult_ven = phpmkr_query($sql_ult_vec, $conn) or die ("Error al seleccionar ultimo paga en apago efectuado pagos". phpmkr_error()."sql:". $sql_ult_vec);
		$row_ult_ven = phpmkr_fetch_array($rs_ult_ven);
		$x_no_vencimiento_num = $row_ult_ven["vencimiento_num"];
		
		 
		 
		 // ya tenemos el numero de celular  y los datos del mensaje, ahora hacemos el envio del mensaje de texto al celular
		 if(!empty($x_no_celular) &&  $x_no_celular != "5555555555"){
			 $x_pagado  =  $GLOBALS["x_importe"];
			 $x_fecha_de_pago = $GLOBALS["x_fecha_pago"];
			 $x_vencimiento_num = $GLOBALS["x_vencimiento_id"];
			 $x_fecha_de_pago = ConvertDateToMysqlFormat($GLOBALS["x_fecha_pago"]);
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
						$x_mensaje = "FINANCIERA CREA: PAGO EFECTUADO $x_pagado el $x_fecha_de_pago  ";
						$x_mensaje .= "PAGO $x_no_vencimiento_num DE $x_no_ultimo_vencimiento  SALDO $x_saldo_por_pagar <br>" ;							
						echo "MENSAJE :". $x_mensaje."<BR>";										
						//Varios destinatarios
						$para  = 'sms@financieracrea.com'; // atenci�n a la coma
						//subject
						$titulo = $x_no_celular;						
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						//Mail it						
						 mail($para, $titulo, $x_mensaje, $cabeceras);	
						$x_contador_3 ++;
						
						$tiposms = "8";
						$chip = "";
						#cambiamos a chip 2						
						//insertaSmsTabla($conn, , $x_credito_id_liquidado, $tiposms, $chip, $x_mensaje, $titulo, $x_compania);
						$sqlInsert = "INSERT INTO `msm` (`sms_id`, `cliente_id`, `credito_id`, `sms_tipo`, `texto`, `fecha`, `chip`, `celular`) ";
						$sqlInsert .= " VALUES (NULL, $x_cliente_idl, $x_credito_id, $tiposms, '$x_mensaje ', '$x_fecha_de_pago', '$x_chip', '$x_no_celular');";
						$result = $rsInsert = phpmkr_query($sqlInsert, $conn) or die ("Error al inserta en sms tabla". phpmkr_error()."sql :". $sqlInsert);
	 
						
						
						
			}			
		}		
			
	
	
	
	
	
	
	
	
	
	
	
	
	
	


	return true;
}
?>

