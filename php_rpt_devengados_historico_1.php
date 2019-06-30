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
	header('Content-Disposition: attachment; filename=reporte_devengado_historico_'.date("Y-m-d").'.xls');
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

$x_pagado_srch = $_POST["x_pagado_srch"];

//echo "pagado".$x_pagado_srch."<br>";
if(empty($x_pagado_srch)){
	$x_pagado_srch = $_GET["x_pagado_srch"];
}

$x_credito_status_srch = $_POST["x_credito_status_srch"];
if(empty($x_credito_status_srch)){
	$x_credito_status_srch = $_GET["x_credito_status_srch"];
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
&nbsp;&nbsp;<a href="php_rpt_devengados_historico.php?export=excel&x_mes=<?php echo $x_mes; ?>&x_year=<?php echo $x_year; ?>&x_empresa_id=<?php echo $x_empresa_id; ?>&x_fondeo_credito_id=<?php echo $x_fondeo_credito_id; ?>&x_sucursal_srch=<?php echo $x_sucursal_srch; ?>&x_promo_srch=<?php echo $x_promo_srch; ?>&x_pagado_srch=<?php echo $x_pagado_srch; ?>&x_credito_status_srch=<?php echo $x_credito_status_srch; ?>">Exportar a Excel</a><?php } ?>
</span></p>

<?php if ($sExport == "") { ?><span class="phpmaker">
<form action="php_rpt_devengados_historico.php" name="filtro" id="filtro" method="post">

<table width="785" border="0" cellpadding="0" cellspacing="0">
  <tr>
  <td><table width="895" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="133">Fondo:</td>
      <td width="10">&nbsp;</td>
      <td width="99"><?php
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
      <td width="10">&nbsp;</td>
      <td width="148"><div id="txtlineas" style=" float: left;">
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
      </div></td>
      <td width="10">&nbsp;</td>
      <td width="119">&nbsp;</td>
      <td colspan="3">&nbsp;</td>
      </tr>
    
   <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Pagado:</td>
      <td>&nbsp;</td>
      <td><select name="x_pagado_srch">
      <option value="1000">Seleccione</option>
      <option value="1">Pagado</option>
      <option value="2">NO Pagado</option>
      </select></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
     <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Status del credito</td>
      <td>&nbsp;</td>
      <td><span class="phpmaker">
        <?php
		$x_estado_civil_idList = "<select name=\"x_credito_status_srch\" class=\"texto_normal\">";
		if ($filter["x_credito_status_id_filtro"] == 0){
			$x_estado_civil_idList .= "<option value='1000' selected>TODAS</option>";
		}else{
			$x_estado_civil_idList .= "<option value='1000' >TODAS</option>";		
		}
		$sSqlWrk = "SELECT `credito_status_id`, `descripcion` FROM `credito_status` where credito_status_id in (1,3) ";
		#echo  $sSqlWrk;
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["credito_status_id"] == $x_cresta_srch) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?>
      </span></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><!-- <input type="radio" name="x_credito_tipo_id" id="x_credito_tipo_id" value="2"  onclick="javascript: document.filtros.submit();"/ <?php //if($_SESSION["x_credito_tipo_id"] == 2){ echo "checked='checked'"; }?>> --></td>
      <td><!-- Grupo --></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Promotor</td>
      <td>&nbsp;</td>
      <td><?php
		$x_estado_civil_idList = "<select name=\"x_promo_srch\" class=\"texto_normal\">";
		if($_SESSION["php_project_esf_status_UserRolID"] == 7) {
			$sSqlWrk = "SELECT promotor_id, nombre_completo FROM promotor Where promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"];
		}else{
			$sSqlWrk = "SELECT `promotor_id`, `nombre_completo` FROM `promotor`";	
			$x_estado_civil_idList .= "<option value=\"1000\">TODOS</option>";	
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
      <td><!--Gestor-->Sucursal</td>
      <td>&nbsp;</td>
      <td><?php
		$x_estado_civil_idList = "<select name=\"x_sucursal_srch\" class=\"texto_normal\">";
		if($_SESSION["php_project_esf_status_UserRolID"] == 7) {
			$sSqlWrk = "SELECT sucursal.sucursal_id, nombre FROM sucursal join promotor on promotor.sucursal_id = sucursal.sucursal_id Where promotor.promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"];
		}else{
			$sSqlWrk = "SELECT sucursal_id, nombre FROM sucursal ";	
			$x_estado_civil_idList .= "<option value=\"1000\">TODOS</option>";	
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
		?>        <!--<span class="ewTableAltRow"><select name="x_gestor_srch" >
      <option value="0">Seleccionar</option>
      <option value="18" <?php if ($filter["x_gestor_srch"]== 18){?> selected="selected"<?php }?>>Fernando Sanchez </option>
      <option value="1250"  <?php if ($filter["x_gestor_srch"]== 1250){?> selected="selected"<?php }?> >Angelica Tabares </option>
      <option value="16"  <?php if ($filter["x_gestor_srch"]== 16){?> selected="selected"<?php }?> >Monica Flores </option>
      <option value="3615" <?php if ($filter["x_gestor_srch"]== 3615){?> selected="selected"<?php }?> >Marcela Lopez </option>
      <option value="3065" <?php if ($filter["x_gestor_srch"]== 3065){?> selected="selected"<?php }?> >Josefina Ochoa </option>
        <option value="6658" <?php if ($filter["x_gestor_srch"]== 6658){?> selected="selected"<?php }?> >Miguel Angel </option>
      <option value="4812" <?php if ($filter["x_gestor_srch"]== 4842 ){?> selected="selected"<?php }?> >Victoria Garcia </option>      
      <option value="4561" <?php if ($filter["x_gestor_srch"]==  4561){?> selected="selected"<?php }?> >Rodrigo Sanchez </option>
      </select></span>--></td>
      <td>&nbsp;</td>
      <td><a href="php_rpt_pagos.php?cmd=reset"></a></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Mes</td>
      <td>&nbsp;</td>
      <td><input name="x_mes" type="text" id="x_mes" value="<?php echo @$x_mes; ?>" size="4" /></td>
      <td>&nbsp;</td>
      <td>A&ntilde;o</td>
      <td>&nbsp;</td>
      <td><input name="x_year" type="text" id="x_year" value="<?php echo @$x_year; ?>" size="6" /></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="button"  value="Generar Reporte" name="Generar Reporte" onclick="filtrar();"  /></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td></td>
      <td>&nbsp;</td>
    </tr>
    
      <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td></td>
      <td>&nbsp;</td>
    </tr>
      <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td></td>
      <td>&nbsp;</td>
    </tr>
  </table></td>
</tr>
</table>
</form>
</span></p>
<br />
<p align="center">
  <?php } ?>
</p>
<p align="left"><span class="phpmaker">
<div align="left">
<?php

?>
  <table id="ewlistmain" class="ewTable" align="left">
    <!-- Table header -->
    <tr class="ewTableHeader" >
		<td width="118" valign="top"><span>
  Mes/A&ntilde;o</span></td>
		<td width="175" valign="top">Fondo</td>
		<td width="175" valign="top">Sucursal</td>
		<td width="175" valign="top">Promotor</td>
        <td width="175" valign="top">Cliente</td>
        <td width="175" valign="top">Cr&eacute;dito num</td>
        <td width="175" valign="top">Status</td>
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
<td width="158" valign="top">Mora Comision</td>        
		<td width="192" valign="top"><span> IVA Mora Comision

<td width="192" valign="top"><span> Total
</span></td> 
<td width="192" valign="top"><span>Pagado 
</span></td>                
	</tr>
<?php

$x_filtros = "";
//filtros
if((!empty($x_empresa_id) && $x_empresa_id > 0) && (!empty($x_fondeo_credito_id) && $x_fondeo_credito_id > 0)){
	$x_filtros = " AND credito.credito_id in (select credito_id from fondeo_colocacion where fondeo_credito_id = $x_fondeo_credito_id) ";
}
if(!empty($x_sucursal_srch) && $x_sucursal_srch > 0 && $x_sucursal_srch != 1000){
	$x_filtros .= " AND solicitud.promotor_id in (select promotor_id from promotor where sucursal_id = $x_sucursal_srch) ";
}

if(!empty($x_credito_status_srch) && $x_credito_status_srch > 0 && $x_credito_status_srch != 1000){
	 $x_filtro_status = " AND credito.credito_status_id = $x_credito_status_srch  ";
}
//echo " <br>".$x_credito_status_srch."...";

if(!empty($x_promo_srch) && $x_promo_srch > 0 &&   $x_promo_srch != 1000){
	//echo "<br> AND solicitud.promotor_id = $x_promo_srch"; 
	$x_filtros .= " AND solicitud.promotor_id = $x_promo_srch ";
}
if(!empty($x_pagado_srch) && $x_pagado_srch != 1000){
	//echo "<br> pagado"; 
	$x_filtros .= " AND vencimiento_devengado_pagado.pagado_status_id = $x_pagado_srch ";
}

//echo $x_pagado_srch."---";

 if(empty($x_filtro_status)){
	  $x_filtro_status = " AND credito.credito_status_id in (1)";
	  }

if(!empty($x_filtros)){
 // echo "filtros llenos<br>";
 
$sSql = "select sum(vencimiento.importe) as capital, sum(interes) as interes, sum(interes_moratorio) as moratorios, sum(vencimiento.iva) as iva, sum(iva_mor) as ivmor from vencimiento join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id where year(vencimiento.fecha_vencimiento) = $x_year and month(vencimiento.fecha_vencimiento) = $x_mes".$x_filtros .$x_filtro_status;

$sSql = "select credito.penalizacion AS penalizacionsss, vencimiento_devengado_pagado.pagado_status_id, vencimiento_devengado_pagado.fecha as fecha_pago, credito.credito_id, credito.solicitud_id, credito.credito_num, credito.credito_status_id, sum(vencimiento_devengado.importe) as capital, sum(interes) as interes, sum(interes_moratorio) as moratorios, sum(vencimiento_devengado.iva) as iva, sum(iva_mor) as ivmor from vencimiento_devengado join credito on credito.credito_id = vencimiento_devengado.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id join vencimiento_devengado_pagado on vencimiento_devengado_pagado.vencimiento_id = vencimiento_devengado.vencimiento_id  where    year(vencimiento_devengado.fecha_vencimiento) = $x_year and month(vencimiento_devengado.fecha_vencimiento) = $x_mes".$x_filtros . $x_filtro_status. "GROUP BY credito.credito_id";

}else{
$sSql = "select sum(vencimiento.importe) as capital, sum(interes) as interes, sum(interes_moratorio) as moratorios, sum(iva) as iva, sum(iva_mor) as ivmor from vencimiento where year(fecha_vencimiento) = $x_year and month(fecha_vencimiento) = $x_mes".$x_filtros;

$sSql = "select credito.penalizacion AS penalizacion,  credito.credito_id, credito.solicitud_id, credito.credito_num, credito.credito_status_id,sum(vencimiento_devengado.importe) as capital, sum(vencimiento_devengado.interes) as interes, sum(vencimiento_devengado.interes_moratorio) as moratorios, sum(vencimiento_devengado.iva) as iva, sum(vencimiento_devengado.iva_mor) as ivmor from vencimiento_devengado, credito, vencimiento_devengado_pagado  where credito.credito_id = vencimiento_devengado.credito_id   and vencimiento_devengado_pagado.vencimiento_id = vencimiento_devengado.vencimiento_id  and  year(vencimiento_devengado.fecha_vencimiento) = $x_year and month(vencimiento_devengado.fecha_vencimiento) = $x_mes  ".$x_filtros. $x_filtro_status ." GROUP BY credito.credito_id ";

$sSql = "select credito.penalizacion AS penalizacion,vencimiento_devengado_pagado.pagado_status_id, vencimiento_devengado_pagado.fecha as fecha_pago,  credito.credito_id, credito.solicitud_id, credito.credito_num, credito.credito_status_id,sum(vencimiento_devengado.importe) as capital, sum(vencimiento_devengado.interes) as interes, sum(vencimiento_devengado.interes_moratorio) as moratorios, sum(vencimiento_devengado.iva) as iva, sum(vencimiento_devengado.iva_mor) as ivmor from vencimiento_devengado, credito, vencimiento_devengado_pagado  where credito.credito_id = vencimiento_devengado.credito_id   and vencimiento_devengado_pagado.vencimiento_id = vencimiento_devengado.vencimiento_id  and  year(vencimiento_devengado.fecha_vencimiento) = $x_year and month(vencimiento_devengado.fecha_vencimiento) = $x_mes  ".$x_filtros. $x_filtro_status ." ";

$sSql = "select credito.credito_id, credito.credito_status_id,vencimiento_devengado_pagado.pagado_status_id, vencimiento_devengado_pagado.fecha as fecha_pago,  sum(vencimiento_devengado.importe) as capital, sum(vencimiento_devengado.interes) as interes, sum(vencimiento_devengado.interes_moratorio) as moratorios, sum(vencimiento_devengado.iva) as iva, sum(vencimiento_devengado.iva_mor) as ivmor from vencimiento_devengado, vencimiento_devengado_pagado, credito  where vencimiento_devengado_pagado.vencimiento_id = vencimiento_devengado.vencimiento_id and vencimiento_devengado.credito_id = credito.credito_id  and  year(vencimiento_devengado.fecha_vencimiento) = $x_year and month(vencimiento_devengado.fecha_vencimiento) = $x_mes  ".$x_filtros. /*$x_filtro_status .*/" GROUP BY vencimiento_devengado.vencimiento_id";

$sSql = "select vencimiento.vencimiento_id, credito.penalizacion AS penalizacion, credito.credito_id, credito.solicitud_id, credito.credito_num, credito.credito_status_id,vencimiento.importe as capital, vencimiento.interes as interes, vencimiento.interes_moratorio as moratorios, vencimiento.iva as iva, vencimiento.iva_mor as ivmor from corte_credito as vencimiento, credito  where credito.credito_id = vencimiento.credito_id  and  year(vencimiento.fecha_vencimiento) = $x_year and month(vencimiento.fecha_vencimiento) = $x_mes  ".$x_filtros. $x_filtro_status ." GROUP BY vencimiento.vencimiento_id ";



}

echo "SQL: ....".$sSql;

//die();
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$SQL_CUENTA = $sSql;
$rs_CUENTA = phpmkr_query($SQL_CUENTA,$conn) ;
$datos = mysql_num_rows($rs_CUENTA);
if($datos <1)
echo " NO HAY DATOS REGISTRADOS PARA ESTE PERIODO<br> ";
while($row = @phpmkr_fetch_array($rs)){




$x_credito_id = $row["credito_id"];
$x_vencimiento_id = $row["vencimiento_id"];
$x_capital = $row["capital"];
$x_interes = $row["interes"];
$x_moratorios = $row["moratorios"];
$x_credito_num = $row["credito_num"];
$x_solicitud_id = $row["solicitud_id"];
$x_credito_id = $row["credito_id"];
$x_pagado_status_id = $row["pagado_status_id"];
$x_fecha_pago = $row["fecha_pago"];
$x_penalizacion = $row["penalizacion"];
$sqlFCEHAP = "SELECT vencimiento_devengado_pagado.pagado_status_id, vencimiento_devengado_pagado.fecha as fecha_pago FROM vencimiento_devengado_pagado WHERE vencimiento_devengado_pagado.vencimiento_id =  $x_vencimiento_id";
$rsFech = phpmkr_query($sqlFCEHAP,$conn) or die();
$rowFech = phpmkr_fetch_array($rsFech);
$x_pagado_status_id = $rowFech["pagado_status_id"];


//echo "--".$x_pagado_status_id;
if($x_pagado_status_id == 1){
	$x_pagado_status_id = "PAGADO";
	}else if($x_pagado_status_id == 2){
		$x_pagado_status_id = "NO PAGADO";
		}


//echo "<br>".$row["credito_num"].  $row["credito_status_id"];
$x_credito_status = $row["credito_status_id"];
$x_iva = $row["iva"];
$x_ivmor = $row["ivmor"];
if($x_penalizacion > 0){
	//calculmo nuevaente loas moratorios y los dividimos en los tre rubros
	
	// el capial sigue sieno capital
	//las penalizaciones siguen siendo moratorios...
	// pero la comison se separa de los moratorios...
	$x_moratorios = 0;
	$x_ivmor = 0;
	$sSqlMora = " SELECT sum(vencimiento_devengado.interes_moratorio) as moratorios, sum(vencimiento_devengado.iva_mor) as ivmor from vencimiento_devengado  where vencimiento_devengado.credito_id  = $x_credito_id and vencimiento_devengado.vencimiento_num < 3000 and  year(vencimiento_devengado.fecha_vencimiento) = $x_year and month(vencimiento_devengado.fecha_vencimiento) = $x_mes  ";
	$rsMora = phpmkr_query($sSqlMora,$conn) or die ("Error al seeccionar los moraorios de las penalizaciones".phpmkr_error().$sSqlMora);
	$rowMora = phpmkr_fetch_array($rsMora);
	$x_moratorios_pena = $rowMora["moratorios"];
	$x_ivmor_pena = $rowMora["ivmor"];
	
	$x_moratorios = $x_moratorios_pena;// = $rowMora["moratorios"];
	$x_ivmor =$x_ivmor_pena;
	
	$sSqlComi = "  SELECT sum(vencimiento_devengado.interes_moratorio) as moratorios, sum(vencimiento_devengado.iva_mor) as ivmor from vencimiento_devengado  where vencimiento_devengado.credito_id  = $x_credito_id and vencimiento_num > 3000 and  year(vencimiento_devengado.fecha_vencimiento) = $x_year and month(vencimiento_devengado.fecha_vencimiento) = $x_mes  ";
	$rsComi = phpmkr_query($sSqlComi,$conn) or die ("Error al seeccionar los moraorios de las penalizaciones".phpmkr_error().$sSqlComi);
	$rowComi = phpmkr_fetch_array($rsComi);
	$x_moratorios_comi = $rowComi["moratorios"];
	$x_ivmor_comi = $rowComi["ivmor"];
	
	
	
	}


$x_total_credito = $x_ivmor + $x_iva + $x_moratorios + $x_capital+ $x_ivmor_comi + $x_moratorios_comi + $x_interes;
$x_total_ivmor += $x_ivmor;
$x_total_ivmor_comi += $x_ivmor_comi;
$x_total_iva += $x_iva;
$x_total_moratorios += $x_moratorios;
$x_total_moratorios_comi += $x_moratorios_comi;
$x_total_capital +=  $x_capital;
$x_total_general += $x_total_credito;
$x_total_interes += $x_interes;

$sqlSol =  "select solicitud_id from credito where credito.credito_id =$x_credito_id  ";
$rssol = phpmkr_query($sqlSol,$conn) or die ("Error al seleccionar fodeo 122");
$rowSol = phpmkr_fetch_array($rssol);
$x_solicitud_id = $rowSol["solicitud_id"];
//datos del cliente 
$x_cliente = "";
$sqlFondo = "select * from cliente, solicitud_cliente where cliente.cliente_id= solicitud_cliente.cliente_id and solicitud_cliente .solicitud_id =$x_solicitud_id  ";
$rsFondeo = phpmkr_query($sqlFondo,$conn) or die ("Error al seleccionar fodeo 1");
$rowFondeo = phpmkr_fetch_array($rsFondeo);
$x_cliente = $rowFondeo["nombre_completo"]." ".$rowFondeo["apellido_paterno"]." ".$rowFondeo["apellido_materno"];

$sqlFondo = "select * from fondeo_colocacion where credito_id = $x_credito_id ";
$rsFondeo = phpmkr_query($sqlFondo,$conn) or die ("Error al seleccionar fodeo");
$rowFondeo = phpmkr_fetch_array($rsFondeo);
$x_fondeo = $rowFondeo["fondeo_credito_id"];
if($x_fondeo == 6){
	$x_fondeo_descripcion = "PROPIOS";
	}else if($x_fondeo ==  7){
		
		$x_fondeo_descripcion = "FIM";
		}else{
			$x_fondeo_descripcion = "otros";
			}
			
			$sSqlWrk = "SELECT `credito_status_id`, `descripcion` FROM `credito_status` where credito_status_id  = $x_credito_status ";
			#echo  $sSqlWrk;
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$rowD = phpmkr_fetch_array($rswrk,$conn);
			$x_credito_status_desc = $rowD["descripcion"];
		
			
$sSqlWrk = "SELECT promotor.promotor_id, promotor.nombre_completo, sucursal.nombre FROM promotor, solicitud, sucursal where solicitud.promotor_id= promotor.promotor_id and solicitud.solicitud_id = $x_solicitud_id and sucursal.sucursal_id = promotor.sucursal_id  ";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
				$x_promotor = $datawrk["nombre_completo"];	
				$x_sucursal	 = 	$datawrk["nombre"];

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
			}else{
				
				echo $x_fondeo_descripcion;}
			?>
            </td>
			<td align="left">
            <?php
			if(!empty($x_sucursal_srch) &&  $x_sucursal_srch != 1000){
				$sSqlWrk = "SELECT sucursal_id, nombre FROM sucursal where sucursal_id = $x_sucursal_srch";
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				echo $datawrk["nombre"];
				@phpmkr_free_result($rswrk);
			}else{
				echo $x_sucursal;
				}
            ?>
            </td>
			<td align="left">
            <?php
			if(!empty($x_promo_srch) && $x_promo_srch != 1000 ){
				$sSqlWrk = "SELECT promotor_id, nombre_completo FROM promotor where promotor_id = $x_promo_srch";
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				echo $datawrk["nombre_completo"];
				@phpmkr_free_result($rswrk);
			}else{
				echo $x_promotor;
				}
            ?>
            </td>
			<!-- importe -->
            <td><?php echo $x_cliente; ?></td>
            <td><?php echo $x_credito_num; ?></td>
            <td><?php echo $x_credito_status_desc; ?></td>
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
    <td align="right"><?php echo FormatNumber($x_moratorios_comi,2,0,0,1); ?></td>	
			<!-- importe -->
			<td align="right"><span>
	<?php echo FormatNumber($x_ivmor_comi,2,0,0,1); ?>
	</span></td>
    <td><?php echo FormatNumber($x_total_credito,2,0,0,1); ?></td>
    <td><?php echo $x_pagado_status_id; ?></td>
    
		</tr>
        <?php }// while sql ?>
        <tr>
        <td colspan="6"></td>
        <td align="right"><span>
	<?php if($datos >0) echo FormatNumber($x_total_capital,2,0,0,1); ?>
	</span></td>	
			<!-- importe -->
			<td align="right"><span>
	<?php if($datos >0) echo FormatNumber($x_total_interes,2,0,0,1); ?>
	</span></td>
			<td align="right"><?php if($datos >0) echo FormatNumber($x_total_iva,2,0,0,1); ?></td>
			<td align="right"><?php  if($datos >0)echo FormatNumber($x_total_moratorios,2,0,0,1); ?></td>	
			<!-- importe -->
			<td align="right"><span>
	<?php  if($datos >0) echo FormatNumber($x_total_ivmor,2,0,0,1); ?>
	</span></td>	
     <td align="right"><?php echo FormatNumber($x_total_moratorios_comi,2,0,0,1); ?></td>	
			<!-- importe -->
			<td align="right"><span>
	<?php echo FormatNumber($x_total_ivmor_comi,2,0,0,1); ?>
	</span></td>
    <td><?php  if($datos >0) echo FormatNumber($x_total_general,2,0,0,1); ?></td>
    <td>&nbsp;</td>
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
