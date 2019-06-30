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
?>
<?php
if (@$_SESSION["php_project_esf_status"] <> "login") {
	header("Location:  login.php");
	exit();
}

$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=reporte_ct.xls');
}
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php include("utilerias/datefunc.php") ?>
<?php include ("header.php") ?>
<?php

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

$x_mes = $_POST["x_mes"];
if(empty($x_mes)){
	$x_mes = $_GET["x_mes"];
}
$x_year = $_POST["x_year"];	
if(empty($x_year)){
	$x_year = $_GET["x_year"];	
}

if(empty($x_mes) || $x_mes == ""){
	$x_mes = intval($currentdate["mon"]);
}
if(empty($x_year) || $x_year == ""){
	$x_year = intval($currentdate["year"]);
}

$x_empresa_id = "";
$x_fondeo_credito_id = "";
$x_sucursal_srch = "";
$x_promo_srch = "";

$x_empresa_id = $_POST["x_empresa_id"];
if(empty($x_empresa_id)){
	$x_empresa_id = $_GET["x_empresa_id"];
}

$x_fondeo_credito_id = $_POST["x_fondeo_credito_id"];
if(empty($x_fondeo_credito_id)){
	$x_fondeo_credito_id = $_GET["x_fondeo_credito_id"];
}

$x_sucursal_srch = $_POST["x_sucursal_srch"];
if(empty($x_sucursal_srch)){
	$x_sucursal_srch = $_GET["x_sucursal_srch"];
}

$x_promo_srch = $_POST["x_promo_srch"];
if(empty($x_promo_srch)){
	$x_promo_srch = $_GET["x_promo_srch"];
}

?>

<script type="text/javascript" src="ew.js"></script>
<script src="lineafondeohint.js"></script>
<?php if ($sExport == "") { ?>
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/skins/aqua/theme.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-es.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<script type="text/javascript" src="ew.js"></script>
<?php } ?>

<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<?php if ($sExport == "") { ?>
<script type="text/javascript" src="utilerias/datefunc.js"></script>
<?php } ?>

<script type="text/javascript">
<!--
function filtrar(){
EW_this = document.filtro;
validada = true;
/*
	if (validada && EW_this.x_fecha_desde && !EW_hasValue(EW_this.x_fecha_desde, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_fecha_desde, "TEXT", "La fecha desde es requerida."))
			validada = false;
	}

	if (validada && EW_this.x_fecha_hasta && !EW_hasValue(EW_this.x_fecha_hasta, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_fecha_hasta, "TEXT", "La fecha hasta es requerida."))
			validada = false;
	}

	if(validada == true){
		if (!compareDates(EW_this.x_fecha_desde.value, EW_this.x_fecha_hasta.value)) {	
			if (!EW_onError(EW_this, EW_this.x_fecha_desde, "TEXT", "La fecha Desde no puede ser menor a la fecha hasta."))
				validada = false; 
		}
	}
*/
	if(validada == true){
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
<p><span class="phpmaker">REPORTE DE INTERESES DEVENGADOS
<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="php_rpt_devengados.php?export=excel&x_mes=<?php echo $x_mes; ?>&x_year=<?php echo $x_year; ?>&x_empresa_id=<?php echo $x_empresa_id; ?>&x_fondeo_credito_id=<?php echo $x_fondeo_credito_id; ?>&x_sucursal_srch=<?php echo $x_sucursal_srch; ?>&x_promo_srch=<?php echo $x_promo_srch; ?>">Exportar a Excel</a><?php } ?>
</span></p>

<?php if ($sExport == "") { ?>
<p align="left"><span class="phpmaker">
<form action="php_rpt_devengados.php" name="filtro" id="filtro" method="post">
<table width="660" align="left" class="phpmaker">
	<tr>
	  <td>Fondo:</td>
	  <td><?php
$x_medio_pago_idList = "<select  name=\"x_empresa_id\" onchange=\"cargalineas(this,'txtlineas',0)\">";
$x_medio_pago_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT fondeo_empresa.fondeo_empresa_id, fondeo_empresa.nombre FROM fondeo_empresa order by fondeo_empresa.fondeo_empresa_id ";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		
/*
		$sSqlWrk2 = "SELECT sum(importe) as otorgado FROM credito where credito_id in (select credito_id from fondeo_colocacion join fondeo_credito on fondeo_credito.fondeo_credito_id = fondeo_colocacion.fondeo_credito_id where fondeo_credito.fondeo_credito_id = ".$datawrk["fondeo_credito_id"].") and credito.credito_status_id in (1, 3,4,5)";
		$rswrk2 = phpmkr_query($sSqlWrk2,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk2);
		$datawrk2 = phpmkr_fetch_array($rswrk2);
		$x_fondeo_saldo = $datawrk["importe"] - $datawrk2["otorgado"];
		@phpmkr_free_result($rswrk2);
*/		
		
		$x_medio_pago_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		
		if ($datawrk["fondeo_empresa_id"] == $x_empresa_id) {
			$x_medio_pago_idList .= "' selected";
		}

		$x_medio_pago_idList .= ">" . $datawrk["nombre"] . "</option>";
		
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_medio_pago_idList .= "</select>";
echo $x_medio_pago_idList;
?></td>
	  <td>&nbsp;</td>
	  <td colspan="2">
<div id="txtlineas" style=" float: left;">
<?php
if(((!empty($x_empresa_id) && $x_empresa_id > 0)) && (!empty($x_fondeo_credito_id) && $x_fondeo_credito_id > 0)){

$x_delegacion_idList = "<select name=\"x_fondeo_credito_id\" >";
$x_delegacion_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT fondeo_credito_id, credito_num, importe FROM fondeo_credito where fondeo_empresa_id = $x_empresa_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["fondeo_credito_id"] == @$x_fondeo_credito_id) {
			$x_delegacion_idList .= "' selected";
		}
		$x_delegacion_idList .= "> Num: " . $datawrk["credito_num"] . " Importe: " . FormatNumber($datawrk["importe"],2,0,0,1) . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_delegacion_idList .= "</select>";
echo "Linea C.: ".$x_delegacion_idList;
	
	
}
?>
</div>
      </td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>
	<tr>
	  <td>Sucursal</td>
	  <td><?php
		$x_estado_civil_idList = "<select name=\"x_sucursal_srch\" class=\"texto_normal\">";
		if($_SESSION["php_project_esf_status_UserRolID"] == 7) {
			$sSqlWrk = "SELECT sucursal_id, nombre FROM sucursal join promotor on promotor.sucursal_id = sucursal.sucursal_id Where promotor.promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"];
		}else{
			$sSqlWrk = "SELECT sucursal_id, nombre FROM sucursal ";	
			$x_estado_civil_idList .= "<option value=\"\">TODAS</option>";	
		}		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["sucursal_id"] == $x_sucursal_srch) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["nombre"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?></td>
	  <td>Promotor:</td>
	  <td><?php
		$x_estado_civil_idList = "<select name=\"x_promo_srch\" class=\"texto_normal\">";
		if($_SESSION["php_project_esf_status_UserRolID"] == 7) {
			$sSqlWrk = "SELECT promotor_id, nombre_completo FROM promotor Where promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"];
		}else{
			$sSqlWrk = "SELECT `promotor_id`, `nombre_completo` FROM `promotor`";	
			$x_estado_civil_idList .= "<option value=\"\">TODOS</option>";	
		}		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["promotor_id"] == $x_promo_srch) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["nombre_completo"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?></td>
	  <td>&nbsp;</td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>
	<tr>
	  <td width="72"><div align="right"><span class="phpmaker">
	    Mes:
	    </span> </div>
	    </td>
	  <td width="104">
         <span>
	    <input name="x_mes" type="text" id="x_mes" value="<?php echo @$x_mes; ?>" size="4">
	    </span>		
	    </td>
	  <td width="88"><div align="right"><span class="phpmaker">
	    Año:
	    </span> </div>
	    </td>
	  <td width="117">
        <span>
	    <input name="x_year" type="text" id="x_year" value="<?php echo @$x_year; ?>" size="6">
	    </span>		
	    </td>		
	  <td width="255">&nbsp;</td>		
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td><input type="button"  value="Generar Reporte" name="Generar Reporte" onclick="filtrar();"  /></td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>
</table>
</form>
</span></p>
<br />
<p align="center">&nbsp;</p>
<br />
<?php } ?>
<br /><br /><br /><br /><br /><br />
<p align="left"><span class="phpmaker">
<div align="left">
<table id="ewlistmain" class="ewTable" align="left">
	<!-- Table header -->
	<tr class="ewTableHeaderThin" >
		<td width="118" valign="top"><span>
  Mes/Año</span></td>
		<td width="175" valign="top">Fondo</td>
		<td width="175" valign="top">Sucursal</td>
		<td width="175" valign="top">Promotor</td>
		<td width="175" valign="top"><span>
Capital
		</span></td>        
		<td width="196" valign="top"><span>
Interes
		</span></td>
		<td width="133" valign="top">IVA</td>
		<td width="158" valign="top">Moratorios</td>        
		<td width="192" valign="top"><span> IVA Moratorios
</span></td>                
	</tr>
<?php

$x_filtros = "";
//filtros
if((!empty($x_empresa_id) && $x_empresa_id > 0) && (!empty($x_fondeo_credito_id) && $x_fondeo_credito_id > 0)){
	$x_filtros = " AND credito.credito_id in (select credito_id from fondeo_colocacion where fondeo_credito_id = $x_fondeo_credito_id) ";
}
if(!empty($x_sucursal_srch) && $x_sucursal_srch > 0){
	$x_filtros .= " AND solicitud.promotor_id in (select promotor_id from promotor where sucursal_id = $x_sucursal_srch) ";
}
if(!empty($x_promo_srch) && $x_promo_srch > 0){
	$x_filtros .= " AND solicitud.promotor_id = $x_promo_srch ";
}

if(!empty($x_filtros)){
$sSql = "select sum(vencimiento.importe) as capital, sum(interes) as interes, sum(interes_moratorio) as moratorios, sum(vencimiento.iva) as iva, sum(iva_mor) as ivmor from vencimiento join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id where year(vencimiento.fecha_vencimiento) = $x_year and month(vencimiento.fecha_vencimiento) = $x_mes".$x_filtros;
}else{
$sSql = "select sum(importe) as capital, sum(interes) as interes, sum(interes_moratorio) as moratorios, sum(iva) as iva, sum(iva_mor) as ivmor from vencimiento where year(fecha_vencimiento) = $x_year and month(fecha_vencimiento) = $x_mes".$x_filtros;
}


$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = @phpmkr_fetch_array($rs);

$x_capital = $row["capital"];
$x_interes = $row["interes"];
$x_moratorios = $row["moratorios"];
$x_iva = $row["iva"];
$x_ivmor = $row["ivmor"];
?>
		<!-- Table body -->
		<tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>
			<td align="center"><span>
	<?php echo $x_mes."/".$x_year; ?>
	</span></td>
			<td align="left">
            <?php
			if(!empty($x_empresa_id)){
				$x_empresa_nombre = "";
            	$sSqlWrk = "SELECT fondeo_empresa.fondeo_empresa_id, fondeo_empresa.nombre FROM fondeo_empresa where fondeo_empresa_id = $x_empresa_id";
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_empresa_nombre = htmlspecialchars($datawrk["fondeo_empresa.nombre"]);
				@phpmkr_free_result($rswrk);
				
				$x_credito_numero = "";
				if(!empty($x_fondeo_credito_id)){
					$sSqlWrk = "SELECT fondeo_credito_id, credito_num, importe FROM fondeo_credito where fondeo_empresa_id = $x_empresa_id";
					$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
					$datawrk = phpmkr_fetch_array($rswrk);
					$x_credito_numero = $datawrk["credito_num"];
					@phpmkr_free_result($rswrk);
				}

				echo $x_empresa_nombre."/".$x_credito_numero;
			}
			?>
            </td>
			<td align="left">
            <?php
			if(!empty($x_sucursal_srch)){
				$sSqlWrk = "SELECT sucursal_id, nombre FROM sucursal where sucursal_id = $x_sucursal_srch";
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				echo $datawrk["nombre"];
				@phpmkr_free_result($rswrk);
			}
            ?>
            </td>
			<td align="left">
            <?php
			if(!empty($x_promo_srch)){
				$sSqlWrk = "SELECT promotor_id, nombre_completo FROM promotor where promotor_id = $x_promo_srch";
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				echo $datawrk["nombre_completo"];
				@phpmkr_free_result($rswrk);
			}
            ?>
            </td>
			<!-- importe -->
			<td align="right"><span>
	<?php echo FormatNumber($x_capital,2,0,0,1); ?>
	</span></td>	
			<!-- importe -->
			<td align="right"><span>
	<?php echo FormatNumber($x_interes,2,0,0,1); ?>
	</span></td>
			<td align="right"><?php echo FormatNumber($x_iva,2,0,0,1); ?></td>
			<td align="right"><?php echo FormatNumber($x_moratorios,2,0,0,1); ?></td>	
			<!-- importe -->
			<td align="right"><span>
	<?php echo FormatNumber($x_ivmor,2,0,0,1); ?>
	</span></td>	
    
		</tr>
</table>
</div>
</span>
</p>
<?php
// Close recordset and connection
@phpmkr_free_result($rs);
phpmkr_db_close($conn);
?>
<?php if ($sExport <> "word" && $sExport <> "excel") { ?>
<?php include ("footer.php") ?>
<?php } ?>
