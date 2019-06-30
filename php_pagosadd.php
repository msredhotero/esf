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
$x_msg_err = "";

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
	if (empty($x_credito_id)) {

		echo "<div align='center' class='phpmaker'>
		<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\" color=\"#CC3300\">
		No se selecciono el credito.<br><br>";
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
	$x_medio_pago_id = @$_POST["x_medio_pago_id"];
	$x_referencia_pago = @$_POST["x_referencia_pago"];
	$x_fecha_registro = @$_POST["x_fecha_registro"];	
	$x_fecha_pago = @$_POST["x_fecha_pago"];		
	$x_importe_pago = @$_POST["x_importe_pago"];
	$x_importe_sel = @$_POST["x_importe_sel"];	
	$x_num_vencs = $_POST["x_num_vencs"];
	
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "A": // Add

		//Valida orden seguido de los venc sel
		$validados = true;
		$x_contador = 0;
		$x_venc_num = 0;
		$x_msg_err = "";
		while(($x_contador <= $x_num_vencs) && ($x_msg_err == "")){
			$x_contador++;

			eval("\$x_sel = isset(\$_POST['x_venc_id_".$x_contador."']) ? 1 : 0 ;");
			
			if($x_sel == 1){
				eval("\$x_vencimiento_id = \$_POST['x_venc_id_".$x_contador."'];");
				$sSql = "select vencimiento_num	from vencimiento where vencimiento_id = $x_vencimiento_id order by vencimiento_num";
				$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
				$rowwrk = phpmkr_fetch_array($rswrk);
				$x_v_n = $rowwrk["vencimiento_num"];	
				phpmkr_free_result($rswrk);

				echo "venc_num: ".$x_v_n." ant = ".$x_venc_num."<br>";
/*
				if($x_venc_num == 0){
					$x_venc_num = $x_v_n;
				}else{
					if($x_v_n > ($x_venc_num + 1)){
						$validados = false;					
					}
				}
*/
				if($x_venc_num > 0){
					if($x_v_n > ($x_venc_num + 1)){
						$validados = false;					
					}
				}
				
				$x_venc_num = $x_v_n;					
				
				if($validados == false){
					$x_msg_err =  "Los vencimientos seleccionados deben de ser continuos, NO se pueden aplicar vencmientos saltados, Verifique.";
				}
			}
		}				
		
		if($x_msg_err == ""){

			$x_fecha_pago = "'".ConvertDateToMysqlFormat($x_fecha_pago)."'";
			$x_fecha_registro = "'".ConvertDateToMysqlFormat($x_fecha_registro)."'";			
			$x_saldo = $x_importe_pago;
			$x_genera_recibo = true;
			$x_contador = 0;
			while($x_contador <= $x_num_vencs){
				$x_contador++;

				eval("\$x_sel = isset(\$_POST['x_venc_id_".$x_contador."']) ? 1 : 0 ;");
				
				if($x_sel == 1){
					eval("\$x_vencimiento_id = \$_POST['x_venc_id_".$x_contador."'];");				
					
					$sSql = "select total_venc from vencimiento where vencimiento_id = $x_vencimiento_id";
					$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
					$rowwrk = phpmkr_fetch_array($rswrk);
					$x_total_venc = $rowwrk["total_venc"];	
					phpmkr_free_result($rswrk);
					
					$sSql = "update vencimiento set vencimiento_status_id = 2 where vencimiento_id = $x_vencimiento_id";
					phpmkr_query($sSql,$conn) or die("Failed to execute query a: ");
					
					if($x_genera_recibo == true){
						$sSql = "insert into recibo values(0,$x_fecha_registro,$x_fecha_pago,$x_importe_pago,$x_medio_pago_id,'$x_referencia_pago',1)";
						phpmkr_query($sSql,$conn) or die("Failed to execute query: ");
						$x_recibo_id = mysql_insert_id();

						$sSql = "insert into recibo_vencimiento values(0,$x_vencimiento_id,$x_recibo_id)";
						phpmkr_query($sSql,$conn) or die("Failed to execute query1: ");						
						$x_genera_recibo = false;
					}else{
						$sSql = "insert into recibo_vencimiento values(0,$x_vencimiento_id,$x_recibo_id)";
						phpmkr_query($sSql,$conn) or die("Failed to execute query2: ");											
					}

					$x_saldo = $x_saldo - $x_total_venc;
				}
			}

			// Aplicar pagos anticipados (si ya no hay pagos pendientes mata el credito y genera una devolucion
			if($x_saldo > 0){

				$sSql = "select * from vencimiento where credito_id = $x_credito_id and vencimiento_status_id = 1 order by fecha_vencimiento desc";
				$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			
				while (($x_saldo > 0) && ($rowwrk = phpmkr_fetch_array($rswrk))){
					$x_s_vencimiento_id = $rowwrk["vencimiento_id"];
					$x_s_vencimiento_num = $rowwrk["vencimiento_num"];		
					$x_s_credito_id = $rowwrk["credito_id"];
					$x_s_vencimiento_status_id = $rowwrk["vencimiento_status_id"];
					$x_s_fecha_vencimiento = $rowwrk["fecha_vencimiento"];
					$x_s_importe = $rowwrk["importe"];
					$x_s_interes = $rowwrk["interes"];
					$x_s_interes_moratorio = $rowwrk["interes_moratorio"];
					$x_s_importe_total = $rowwrk["total_venc"];	
			
					if($x_saldo == $x_s_importe_total){
						$sSql = "update vencimiento set vencimiento_status_id = 5
						where vencimiento_id =  ".$x_s_vencimiento_id;
						phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
						$x_saldo = 0;
						
						$sSql = "insert into recibo_vencimiento values(0,$x_s_vencimiento_id,$x_recibo_id)";
						phpmkr_query($sSql, $conn) or die("Failed to execute query4: " . phpmkr_error() . '<br>SQL: ' . $sSql);;
						
					}else{
					
						if($x_saldo < $x_s_importe_total){
/*						
							//FALTA prorratera saldo entre capital e intereses conforme a la tasa del credito.
							$sSql = "select tasa from credito where credito_id = $x_credito_id";
							$rswrk2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
							$rowwrk2 = phpmkr_fetch_array($rswrk2);
							$x_tasa = $rowwrk2["tasa"];	
							phpmkr_free_result($rswrk2);
*/							

							$x_por1 = (($x_s_importe * 100) / $x_s_importe_total);
							$x_d_importe = ($x_saldo * ($x_por1 / 100));

							$x_por2 = (($x_s_interes * 100) / $x_s_importe_total);
							$x_d_interes = ($x_saldo * ($x_por2 / 100));

				
							$sSql = "update vencimiento set 
							vencimiento_status_id = 2,
							importe = ".$x_d_importe.",
							interes = ".$x_d_interes.",
							total_venc = ".$x_saldo." 
							where vencimiento_id =  ".$x_s_vencimiento_id;
							phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
				
							
							$sSql = "insert into recibo_vencimiento values(0,$x_s_vencimiento_id,$x_recibo_id)";
							$x_result = phpmkr_query($sSql, $conn) or die("Failed to execute query5: " . phpmkr_error() . '<br>SQL: ' . $sSql);;
							if(!$x_result){
								echo phpmkr_error() . '<br>SQL: ' . $sSql;
								phpmkr_query('rollback;', $conn);	 
								exit();
							}

				
							$x_d_importe = $x_s_importe - $x_d_importe;
							$x_d_interes = $x_s_interes - $x_d_interes;
							$x_saldo = $x_s_importe_total - $x_saldo;	
						
							$sSql = "insert into vencimiento values(0,$x_credito_id, $x_s_vencimiento_num,1, '$x_s_fecha_vencimiento', $x_d_importe, $x_d_interes, 0, $x_saldo)";
							$x_result = phpmkr_query($sSql, $conn);
							if(!$x_result){
								echo phpmkr_error() . '<br>SQL: ' . $sSql;
								phpmkr_query('rollback;', $conn);	 
								exit();
							}
							$x_saldo = 0;
						}else{
							if($x_saldo > $x_s_importe_total){
					
								$x_saldo = $x_saldo - $x_s_importe_total;	
					
								$sSql = "update vencimiento set vencimiento_status_id = 5
								where vencimiento_id =  ".$x_s_vencimiento_id;
								phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
								
								
								$sSql = "insert into recibo_vencimiento values(0,$x_s_vencimiento_id,$x_recibo_id)";
phpmkr_query($sSql, $conn) or die("Failed to execute query6: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			
							
							}
						}
					}
					
			
				}
				phpmkr_free_result($rowwrk);
			}


			//VALIDA SI EL CREDIT YA SE FINIQUITO
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
			header("Location:  php_vencimientolist.php?credito_id=$x_credito_id");
			exit();
		}

		break;
	case "L": // Add

		//tipo de credito
		$sSql = "select credito.credito_tipo_id from credito where credito.credito_id = $x_credito_id";				
		$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$rowwrk = phpmkr_fetch_array($rswrk);
		$x_credito_tipo_id = $rowwrk["credito_tipo_id"];				
		phpmkr_free_result($rswrk2);
		
		
		if($x_credito_tipo_id == 1){	
	
			$sSql = "select solicitud.folio, solicitud.promotor_id, cliente.nombre_completo, credito.credito_num, credito.cliente_num, credito.importe, vencimiento.fecha_vencimiento
			from vencimiento join credito 
			on credito.credito_id = vencimiento.credito_id join solicitud
			on solicitud.solicitud_id = credito.solicitud_id join solicitud_cliente
			on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente 
			on cliente.cliente_id = solicitud_cliente.cliente_id
			where credito.credito_id = $x_credito_id and vencimiento_status_id in (1,3)";
		}else{
			$sSql = "select solicitud.folio, solicitud.promotor_id, solicitud.grupo_nombre as  nombre_completo, credito.credito_num, credito.cliente_num, credito.importe, vencimiento.fecha_vencimiento
			from vencimiento join credito 
			on credito.credito_id = vencimiento.credito_id join solicitud
			on solicitud.solicitud_id = credito.solicitud_id 
			where credito.credito_id = $x_credito_id and vencimiento_status_id in (1,3)";
		}
		
		$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		if(phpmkr_num_rows($rswrk) > 0){
			$rowwrk = phpmkr_fetch_array($rswrk);

			$x_credito_num = $rowwrk["credito_num"];				
			$x_cliente_num = $rowwrk["cliente_num"];														
			$x_importe_credito = $rowwrk["importe"];
			$x_folio = $rowwrk["folio"];															
			$x_nombre_completo = $rowwrk["nombre_completo"];																				
			$x_fecha_vencimiento = $rowwrk["fecha_vencimiento"];																							

		}else{
			echo "<div align='center' class='phpmaker'>
			<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\" color=\"#CC3300\">
			No se localizaron pagos pendientes.<br><br>";
			echo "<a href=\"php_vencimientolist.php?credito_id=$x_credito_id\">Cerrar Ventana</a>
			</font>
			</div>";
			exit();
		}
		phpmkr_free_result($rswrk);
		break;		
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>e - SF >  FINANCIERA CRECE - PAGOS </title>
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="JavaScript1.2" src="menu/stlib.js"></script>
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

function actualizapago(vencid){
	EW_this = document.pagoadd;
	imp = eval("Number(document.pagoadd.x_importe_" + vencid + ".value)");	
	if(eval("document.pagoadd.x_venc_id_" + vencid + ".checked") == true){
		x_tpago = Number(EW_this.x_importe_sel_dum.value) + Number(imp);	
	}else{
		x_tpago = Number(EW_this.x_importe_sel_dum.value) - Number(imp);		
	}

	EW_this.x_importe_sel_dum.value = x_tpago.toFixed(2);
	EW_this.x_importe_sel.value = x_tpago.toFixed(2);	
}

function EW_checkMyForm() {
EW_this = document.pagoadd;
validada = true;


//captura valor del usuario
//prompt()

if(Number(EW_this.x_importe_pago.value) == 0){
	if (!EW_onError(EW_this, EW_this.x_importe_pago, "TEXT", "El importe de pago es requerido."))
		validada = false;
}

if (validada == true){
	if(Number(EW_this.x_importe_sel.value) == 0){
		if (!EW_onError(EW_this, EW_this.x_importe_pago, "TEXT", "No se han seleccionado vencimientos a pagar."))
			validada = false;
	}
}

if (validada == true){
if(Number(EW_this.x_importe_sel.value) > Number(EW_this.x_importe_pago.value)){
	alert("El total seleccionado es MAYOR al importe de pago, verifique.");
	validada = false;
}

if(Number(EW_this.x_importe_sel.value) < Number(EW_this.x_importe_pago.value)){
	x = confirm("El total seleccionado es MENOR al pago, desea aplicar el sobrante a pagos anticipados.");
	if(x == false){
		validada = false;	
	}
}
}

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
<div align="center">
<a href="php_vencimientolist.php?credito_id=<?php echo $x_credito_id; ?>">Cancelar y Cerrar ventana</a>
<br />
<br />
<?php
if($x_msg_err != ""){
echo $x_msg_err,"<br><br>";
}
?>
</div>
<form name="pagoadd" id="pagoadd" action="php_pagosadd.php" method="post">
<input type="hidden" name="a_add" value="A">
<input type="hidden" name="x_credito_id" value="<?php echo $x_credito_id; ?>">
<input type="hidden" name="x_hoy" value="<?php echo $currdate; ?>">
<input type="hidden" name="x_fecha_registro" value="<?php echo FormatDateTime(@$currdate,7); ?>">		

<table width="500" align="center" class="ewTable_small">
	
	<tr>
		<td width="200" class="ewTableHeaderThin"><div align="left"><span>Medio de pago</span></div></td>
	  <td width="300" class="ewTableAltRow"><div align="left"><span>
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
		<td class="ewTableHeaderThin"><div align="left"><span>Referencia de pago</span></div></td>
		<td class="ewTableAltRow"><div align="left"><span>
          <input type="text" name="x_referencia_pago" id="x_referencia_pago" size="30" maxlength="50" value="<?php echo htmlspecialchars(@$x_ref_loc) ?>">
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
	  <td class="ewTableHeaderThin"><div align="left">Importe del pago</div></td>
	  <td class="ewTableAltRow">
	    <div align="left"><span>
          <input style="text-align:right" type="text" name="x_importe_pago" value="<?php echo FormatNumber(@$x_importe_pago,2,0,0,0); ?>" onKeyPress="return solonumeros(this,event,true)"  />
        </span>        </div></td>
    </tr>
	
	<tr>
		<td class="ewTableHeaderThin"><div align="left">Total selsccionado a pagar</div></td>
		<td class="ewTableAltRow">
	      <div align="left">
          
	        <input style="text-align:right" name="x_importe_sel_dum" type="text" id="x_importe_sel_dum" value="0" onKeyPress="return noenter(this,event)"  />		
            <input type="hidden" name="x_importe_sel" value="0"  />
          </div></td>
	</tr>
	<tr>
	  <td class="ewTableAltRow">&nbsp;</td>
	  <td class="ewTableAltRow"><input type="button"  name="Action" value="Aplicar Pago" onclick="EW_checkMyForm()" /></td>
    </tr>
    
	<tr>
	  <td colspan="2" class="ewTableAltRow">&nbsp;</td>
    </tr>
	<tr>
	  <td colspan="2" class="ewTableHeaderThin"> <div align="center">Vencimientos</div></td>
    </tr>
	<tr>
	  <td colspan="2" class="ewTableAltRow">
      <!-- Listado de vencimientos -->
      
      
      <table class="ewTable" align="center">
      <tr class="ewTableHeaderThin">
      	<td>Sel.        </td>
      	<td>No.        </td>
      	<td>Status        </td>
      	<td>Fec. Venc.        </td>
      	<td>Saldo        </td>
      	<td>Importe        </td>
      	<td>interes        </td>
      	<td>Moratorios        </td>
      	<td>Total        </td>        
	  </tr>
      <?php
$sSql = "SELECT * FROM vencimiento where credito_id = $x_credito_id and vencimiento_status_id in (1,3) order by vencimiento.vencimiento_num";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);     

$x_saldo = $x_importe_credito;
$x_total = 0;
$x_total_pagos = 0;
$x_total_interes = 0;
$x_total_moratorios = 0;
$x_total_total = 0;
$nRecActual = 0;
$nRecCount = 0;
while ($row = @phpmkr_fetch_array($rs)) {
	$nRecCount = $nRecCount + 1;

		// Set row color
		$sItemRowClass = " class=\"ewTableRow\"";
		$sListTrJs = " ";

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
		
		$x_total = $x_importe + $x_interes + $x_interes_moratorio;

		$x_total_pagos = $x_total_pagos + $x_importe;
		$x_total_interes = $x_total_interes + $x_interes;
		$x_total_moratorios = $x_total_moratorios + $x_interes_moratorio;
		$x_total_total = $x_total_total + $x_total;
		
		$x_ref_loc = str_pad($x_vencimiento_id, 5, "0", STR_PAD_LEFT)."/".str_pad($x_vencimiento_num, 2, "0", STR_PAD_LEFT);
		
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>
<!--
<td><span class="phpmaker"><a href="<?php //if ($x_vencimiento_id <> "") {echo "php_reciboadd.php?vencimiento_id=" . urlencode($x_vencimiento_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>" target="_blank">Pagar</a></span></td>
--->
<td><span class="phpmaker">
<input type="checkbox" name="x_venc_id_<?php echo $nRecCount; ?>" value="<?php echo $x_vencimiento_id; ?>" onclick="actualizapago(<?php echo $nRecCount; ?>)"  />
</span></td>
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
<input type="hidden" name="x_importe_<?php echo $nRecCount; ?>" value="<?php echo $x_total; ?>"  />
</span></td>
	</tr>
<?php
$x_saldo = $x_saldo - $x_importe;
}
?>
<input type="hidden" name="x_num_vencs" value="<?php echo $nRecCount; ?>"  />
 </table>      </td>
    </tr>
</table>
<p>

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
$x_s_interes_moratorio = $rowwrk["interes_moratorio"];
$x_s_importe_total = $rowwrk["total_venc"];	
phpmkr_free_result($rowwrk);
	
if($GLOBALS["x_importe"] == $x_s_importe_total){
	$sSql = "update vencimiento set 
	vencimiento_status_id = 2,
	importe = ".$GLOBALS["x_importe_venc"].",
	interes = ".$GLOBALS["x_interes_venc"].",
	interes_moratorio = ".$GLOBALS["x_interes_moratorio"].",
	total_venc = ".$GLOBALS["x_importe"]." 
	where vencimiento_id =  ".$GLOBALS["x_vencimiento_id"];
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
}

if($GLOBALS["x_importe"] < $x_s_importe_total){
	$sSql = "update vencimiento set 
	vencimiento_status_id = 2,
	importe = ".$GLOBALS["x_importe_venc"].",
	interes = ".$GLOBALS["x_interes_venc"].",
	interes_moratorio = ".$GLOBALS["x_interes_moratorio"].",
	total_venc = ".$GLOBALS["x_importe"]." 
	where vencimiento_id =  ".$GLOBALS["x_vencimiento_id"];
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);

	$x_d_importe = $x_s_importe - $GLOBALS["x_importe_venc"];
	$x_d_interes = $x_s_interes - $GLOBALS["x_interes_venc"];
	$x_d_interes_moratorio = $x_s_interes_moratorio - $GLOBALS["x_interes_moratorio"];
	$x_saldo = $x_d_importe + $x_d_interes + $x_d_interes_moratorio;	

	
	$sSql = "insert into vencimiento values(0,$x_s_credito_id, $x_s_vencimiento_num,1, '$x_s_fecha_vencimiento', $x_d_importe, $x_d_interes, $x_d_interes_moratorio, $x_saldo)";
	$x_result = phpmkr_query($sSql, $conn);
	if(!$x_result){
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
		phpmkr_query('rollback;', $conn);	 
		exit();
	}
}


if($GLOBALS["x_importe"] > $x_s_importe_total){
	$sSql = "update vencimiento set vencimiento_status_id = 2
	where vencimiento_id =  ".$GLOBALS["x_vencimiento_id"];
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);

	$x_d_importe = $GLOBALS["x_importe_venc"] - $x_s_importe ;
	$x_d_interes = $GLOBALS["x_interes_venc"] - $x_s_interes;
	$x_d_interes_moratorio = $GLOBALS["x_interes_moratorio"] - $x_s_interes_moratorio;
	$x_saldo = $GLOBALS["x_importe"] - $x_s_importe_total;	


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


