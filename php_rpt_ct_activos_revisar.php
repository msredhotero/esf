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

$x_fecha_desde = $_POST["x_fecha_desde"];
if(empty($x_fecha_desde)){
	$x_fecha_desde = $_GET["x_fecha_desde"];
}
$x_fecha_hasta = $_POST["x_fecha_hasta"];	
if(empty($x_fecha_hasta)){
	$x_fecha_hasta = $_GET["x_fecha_hasta"];	
}

if($x_fecha_desde == ""){
	$x_fecha_desde = $currdate;
	$x_fecha_hasta = $currdate;
	$x_fecha_desde_sql = ConvertDateToMysqlFormat($currdate);
	$x_fecha_hasta_sql = ConvertDateToMysqlFormat($currdate);
	
}else{
	$x_fecha_desde_sql = ConvertDateToMysqlFormat($x_fecha_desde);
	$x_fecha_hasta_sql = ConvertDateToMysqlFormat($x_fecha_hasta);
}

$temptime = strtotime($x_fecha_desde_sql);	
$x_fecha_desde_sql = strftime('%Y-%m-%d',$temptime);	

$temptime = strtotime($x_fecha_hasta_sql);	
$x_fecha_hasta_sql = strftime('%Y-%m-%d',$temptime);	

$x_empresa_id = "";
$x_fondeo_credito_id = "";
$x_sucursal_srch = "";
$x_promo_srch = "";
$x_cresta_srch = "";

$x_empresa_id = $_POST["x_empresa_id"];
$x_fondeo_credito_id = $_POST["x_fondeo_credito_id"];
$x_sucursal_srch = $_POST["x_sucursal_srch"];
$x_promo_srch = $_POST["x_promo_srch"];
$x_gestor_srch = $_POST["x_gestor_srch"];
$x_cresta_srch = $_POST["x_cresta_srch"];

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
<p><span class="phpmaker">REPORTE DE CARTERA TOTAL
<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="php_rpt_ct.php?export=excel&x_fecha_desde=<?php echo FormatDateTime(@$x_fecha_desde,7); ?>&x_fecha_hasta=<?php echo FormatDateTime(@$x_fecha_hasta,7); ?>">Exportar a Excel</a><?php } ?>
</span></p>

<?php if ($sExport == "") { ?>
<p align="left"><span class="phpmaker">
<form action="php_rpt_ct.php" name="filtro" id="filtro" method="post">
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
	  <td>Creditos:</td>
	  <td><?php
		$x_estado_civil_idList = "<select name=\"x_cresta_srch\" class=\"texto_normal\">";
		if ($_SESSION["x_credito_status_id_filtro"] == 0){
			$x_estado_civil_idList .= "<option value='' selected>TODOS</option>";
		}else{
			$x_estado_civil_idList .= "<option value='' >TODAS</option>";		
		}
		$sSqlWrk = "SELECT credito_status_id, descripcion FROM credito_status where credito_status_id not in (2,5,4)";
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
		?></td>
	  <td>Gestor</td>
	  <td><select name="x_gestor_srch" >
      <option value="0">Seleccionar</option>
      <option value="18" <?php if ($x_gestor_srch == 18){?> selected="selected"<?php }?>>Fernando Sanchez </option>
      <option value="1250"  <?php if ($x_gestor_srch == 1250){?> selected="selected"<?php }?> >Angelica Tabares </option>
      <option value="16"  <?php if ($x_gestor_srch == 16){?> selected="selected"<?php }?> >Monica Flores </option>
      <option value="3615" <?php if ($x_gestor_srch == 3615){?> selected="selected"<?php }?> >Marcela Lopez </option>
      <option value="3065" <?php if ($x_gestor_srch == 3065){?> selected="selected"<?php }?> >Josefina Ochoa </option>
      <option value="4561" <?php if ($x_gestor_srch == 4561 ){?> selected="selected"<?php }?> >Rodrigo Sanchez </option>
      <option value="4812" <?php if ($x_gestor_srch == 4842 ){?> selected="selected"<?php }?> >Victoria Garcia </option>
      <option value="4561" <?php if ($x_gestor_srch ==  4561){?> selected="selected"<?php }?> >Rodrigo Sanchez </option>
    </select></td>
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
		  Desde:
		  </span> </div>
         </td>
		<td width="104"><span>
		<input name="x_fecha_desde" type="text" id="x_fecha_desde" value="<?php echo FormatDateTime(@$x_fecha_desde,7); ?>" size="11">
		&nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_desde" alt="Pick a Date" style="cursor:pointer;cursor:hand;">
		<script type="text/javascript">
		Calendar.setup(
		{
		inputField : "x_fecha_desde", // ID of the input field
		ifFormat : "%d/%m/%Y", // the date format
		button : "cx_fecha_desde" // ID of the button
		}
		);
		</script>
		</span>		
        </td>
		<td width="88"><div align="right"><span class="phpmaker">
		  Hasta:
		  </span> </div>
         </td>
		<td width="117"><span>
		<input name="x_fecha_hasta" type="text" id="x_fecha_hasta" value="<?php echo FormatDateTime(@$x_fecha_hasta,7); ?>" size="11">
		&nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_hasta" alt="Pick a Date" style="cursor:pointer;cursor:hand;">
		<script type="text/javascript">
		Calendar.setup(
		{
		inputField : "x_fecha_hasta", // ID of the input field
		ifFormat : "%d/%m/%Y", // the date format
		button : "cx_fecha_hasta" // ID of the button
		}
		);
		</script>
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
		<td valign="top" rowspan="2"><span>
Fecha
		</span></td>
		<td valign="top"  rowspan="2"><span>
Otrogado
		</span></td>        
		<td valign="top"  rowspan="2"><span>
Pagado
		</span></td>        
		 
<td colspan="3" align="center">Devengados</td>

    <td valign="top"  rowspan="2"><span>
Cartera Total</span></td>           
	</tr>
     <tr>
    <td>Interes</td>
    <td>&nbsp;Moratorio</td>
    <td>Comision</td>
  </tr>
<?php
$nRecCount = 0;
$x_fecha_actual = $x_fecha_desde_sql;

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
if(!empty($x_cresta_srch) && $x_cresta_srch > 0){
	$x_filtros .= " AND credito.credito_status_id = $x_cresta_srch ";
}


#echo "filter gestor".$filter["x_gestor_srch"]."<br>";
if ((!empty($x_gestor_srch))){
#	echo "getor entra";
		#si el filtro de gestor esta lleno entonces se debe incluir solo los credito que son de ese gestor
		if($x_gestor_srch == 18){
			$sSqlGestor = "SELECT credito_id FROM gestor_credito  ";
		}else{
			
			$sSqlGestor = "SELECT credito_id FROM gestor_credito WHERE usuario_id = ".$x_gestor_srch ." ";
			}
		
	
$rsGestor = phpmkr_query($sSqlGestor, $conn) or die ("Error al seleccionar los credito que pertenecen al gestor". phpmkr_error()."sql :".$sSqlGestor);
while ($rowGestor = mysql_fetch_array($rsGestor)){
	$x_listado_creditos_gestor = $x_listado_creditos_gestor.$rowGestor["credito_id"].", ";
	}
	
		$x_listado_creditos_gestor = substr($x_listado_creditos_gestor, 0, strlen($x_listado_creditos_gestor)-2); 		
$x_filtros .= " AND ((credito.credito_id in ($x_listado_creditos_gestor)) ) ";		
		}


while($x_fecha_actual <= $x_fecha_hasta_sql){

	$nRecCount = $nRecCount + 1;

	// Set row color
	$sItemRowClass = " class=\"ewTableRow\"";
	$sListTrJs = "";

	// Display alternate color for rows
	if ($nRecCount % 2 <> 1) {
		$sItemRowClass = " class=\"ewTableAltRow\"";
	}


	// Build SQL
	$sSql = "SELECT sum(importe) as capital from credito join solicitud on solicitud.solicitud_id = credito.solicitud_id where credito.credito_status_id not in (2,5,4) and credito.fecha_otrogamiento <= '".$x_fecha_actual."'".$x_filtros;
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = @phpmkr_fetch_array($rs);
	$x_tot_otorgado = $row["capital"];
	phpmkr_free_result($rs);		
	
	echo "<br> SQL total otorgado ".$sSql;		

//	$sSql = "SELECT sum(importe) as pagado from recibo where fecha_pago <= '".$x_fecha_actual."'";
#echo $sSql;


	$sSql = "SELECT sum(vencimiento.importe) as pagado from vencimiento join recibo_vencimiento on recibo_vencimiento.vencimiento_id = vencimiento.vencimiento_id join recibo on recibo.recibo_id = recibo_vencimiento.recibo_id join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id where (vencimiento.vencimiento_status_id in(2,5)) AND (recibo.recibo_status_id = 1) AND (recibo.fecha_pago <= '".$x_fecha_actual."') AND (vencimiento.credito_id not in (select credito_id from credito where credito_status_id in (2,5,4))) ".$x_filtros;
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = @phpmkr_fetch_array($rs);
	$x_tot_pagado = $row["pagado"];
	phpmkr_free_result($rs);	
	//echo "<br> SQL total pagado ".$sSql;	
	
	$x_hoy_es = date("Y-m-d");
	
	$x_interes_devengado_condonacion_gral ="";
	$x_mora_devengado_condonacion_gral ="";
	$x_mora_com_devengado_condonacion_gral ="";
	
	$sSql = "SELECT sum(vencimiento.interes) as interes_devengado_no_pagado from vencimiento  join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id where (vencimiento.vencimiento_status_id in(1,3,6,7))   AND (vencimiento.credito_id not in (select credito_id from credito where credito_status_id in (2,5,4))) and vencimiento.fecha_vencimiento < \"$x_hoy_es\" ".$x_filtros;	
//	echo "<br> devengado no pgado".$sSql."<br>";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = @phpmkr_fetch_array($rs);
	$x_interes_devengado_no_pagado = $row["interes_devengado_no_pagado"];
	$sSql = "SELECT sum(vencimiento.interes_moratorio) as mora_devengado_no_pagado from vencimiento  join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id where (vencimiento.vencimiento_status_id in(1,3,6,7))   AND (vencimiento.credito_id not in (select credito_id from credito where credito_status_id in (2,5,4))) and vencimiento.fecha_vencimiento < \"$x_hoy_es\" and vencimiento.vencimiento_num < 3000 ".$x_filtros;	
	//echo "<br> devengado no pgado".$sSql."<br>";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = @phpmkr_fetch_array($rs);
	$x_mora_devengado_no_pagado = $row["mora_devengado_no_pagado"];
	
	$sSql = "SELECT sum(vencimiento.interes_moratorio) as mora_devengado_no_pagado_com from vencimiento  join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id where (vencimiento.vencimiento_status_id in(1,3,6,7))   AND (vencimiento.credito_id not in (select credito_id from credito where credito_status_id in (2,5,4))) and vencimiento.fecha_vencimiento < \"$x_hoy_es\" and vencimiento.vencimiento_num > 3000 ".$x_filtros;	
	//echo "<br> devengado no pgado".$sSql."<br>";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = @phpmkr_fetch_array($rs);
	$x_mora_devengado_no_pagado_com = $row["mora_devengado_no_pagado_com"];
	
	
	
	$sSql = "SELECT vencimiento.vencimiento_id, vencimiento.fecha_vencimiento as fecha_ven, credito.credito_id from vencimiento  join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id where (vencimiento.vencimiento_status_id in(8))   AND (vencimiento.credito_id not in (select credito_id from credito where credito_status_id in (2,5))) and vencimiento.fecha_vencimiento < \"$x_hoy_es\" ".$x_filtros;	
	//echo "<br> devengado no pgado".$sSql."<br>";
	$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	while($row2 = @phpmkr_fetch_array($rs2)){
	$x_interes_devengado_no_pagado = $row["interes_devengado_no_pagado"];
	$x_credito_id_c = $row2["credito_id"];
	$x_fecha_ven_c  = $row2["fecha_ven"];
	$x_vencimiento_id_c = $row2["vencimiento_id"];
	
	$sqlFechaCond = "SELECT fecha_registro FROM condonacion where credito_id = $x_credito_id_c and vencimiento_id = $x_vencimiento_id_c ";
	$rsFecha = phpmkr_query($sqlFechaCond,$conn) or die ("Error al seleccionar los vencimientos condeomands.".phpmkr_error()."sql:".$sqlFechaCond);
	$rowCond = phpmkr_fetch_array($rsFecha);
	$x_fecha_registro_codonacion = $rowCondonacion["fecha_registro"];
	
	
	
	if($x_fecha_registro_codonacion > $x_fecha_ven_c)
	// seleccionamos los datos del vencimiento
	$sSql = "SELECT  vencimiento.vencimiento_num, vencimiento.interes as interes_devengado_no_pagado , vencimiento.interes_moratorio as mora_devengado_no_pagado from vencimiento  where vencimiento_id = $x_vencimiento_id_c";	
	//echo "<br> devengado no pgado".$sSql."<br>";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = @phpmkr_fetch_array($rs);
	$x_interes_devengado_condondo = $row["interes_devengado_no_pagado"];
	$x_mora_devengado_condondo = $row["mora_devengado_no_pagado"];	
	echo "mora con".$x_mora_devengado_condondo;
	$x_venc_num = $row["vencimiento_num"];
	
	$x_interes_devengado_condonacion_gral += $x_interes_devengado_condondo;
	if($x_venc_num < 3000){
		$x_mora_devengado_condonacion_gral += $x_mora_devengado_condondo;
		}else{
			$x_mora_com_devengado_condonacion_gral += $x_mora_devengado_condondo;			
			}
	
	}
	echo "int con ".$x_interes_devengado_condonacion_gral."mora cond".$x_mora_devengado_condonacion_gral."com cond".$x_mora_com_devengado_condonacion_gral;
	
	$x_interes_devengado_no_pagado +=  $x_interes_devengado_condonacion_gral;
	$x_mora_devengado_no_pagado += $x_mora_devengado_condonacion_gral;
	$x_mora_devengado_no_pagado_com += $x_mora_com_devengado_condonacion_gral;
	
	
	

		echo "<br>".$interes_devengado_no_pagado;	

#echo "<BR>".$sSql."<BR>";
	$x_tot_cartera = $x_tot_otorgado - $x_tot_pagado +$interes_devengado_no_pagado + $x_mora_devengado_no_pagado + $x_mora_devengado_no_pagado_com;	
	
	$x_tot_intereses = 0;
	$x_tot_moratorios = 0;
	$x_tot_total_fecha = 0;
	

	?>
		<!-- Table body -->
		<tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>
			<td align="center"><span>
	<?php echo FormatDateTime($x_fecha_actual,7); ?>
	</span></td>
			<!-- importe -->
			<td align="right"><span>
	<?php echo FormatNumber($x_tot_otorgado,2,0,0,1); ?>
	</span></td>	
			<!-- importe -->
			<td align="right"><span>
	<?php echo FormatNumber($x_tot_pagado,2,0,0,1); ?>
	</span></td>	
			<!-- importe -->
			    <td align="right"><span>
	<?php echo FormatNumber($x_interes_devengado_no_pagado,2,0,0,1); ?>
	</span></td>
    <td align="right"><span>
	<?php echo FormatNumber($x_mora_devengado_no_pagado,2,0,0,1); ?>
	</span></td>
    <td align="right"><span>
	<?php echo FormatNumber($x_mora_devengado_no_pagado_com,2,0,0,1); ?>
	</span></td>	
    <td align="right"><span>
	<?php echo FormatNumber($x_tot_cartera,2,0,0,1); ?>
	</span></td>

    
		</tr>
	<?php

	$temptime = strtotime($x_fecha_actual);	
	//recorre 1 dia
	$temptime = DateAdd('w',1,$temptime);
	//fecha actual
	$x_fecha_actual = strftime('%Y-%m-%d',$temptime);	
	$x_fecha_actual = FormatDateTime($x_fecha_actual,7);
	$x_fecha_actual = ConvertDateToMysqlFormat($x_fecha_actual);
/*	
	echo "actual " . $x_fecha_actual."<br>";
	echo "hasta " . $x_fecha_hasta_sql."<br>";	
	echo "dif " . ($x_fecha_actual <= $x_fecha_hasta_sql) ."<br>";	
	
	if($nRecCount > 2){
	exit();
	}
*/

}


?>
</table>
</div>
</span>
</p>
<?php
// Close recordset and connection
phpmkr_db_close($conn);
?>
<?php if ($sExport <> "word" && $sExport <> "excel") { ?>
<?php include ("footer.php") ?>
<?php } ?>
