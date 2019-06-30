<?php session_start(); ?>
<?php ob_start(); ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Cache-Control: private");
header("Pragma: no-cache"); // HTTP/1.0 


set_time_limit(300);

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
	header('Content-Disposition: attachment; filename=reporte_tpct.xls');
}
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php include("utilerias/datefunc.php") ?>
<?php include ("header.php") ?>
<?php

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

$x_fecha_desde = $currdate;
$x_fecha_hasta = $currdate;
$x_fecha_desde_sql = ConvertDateToMysqlFormat($currdate);
$x_fecha_hasta_sql = ConvertDateToMysqlFormat($currdate);
	

$temptime = strtotime($x_fecha_desde_sql);	
$x_fecha_desde_sql = strftime('%Y-%m-%d',$temptime);	

$temptime = strtotime($x_fecha_hasta_sql);	
$x_fecha_hasta_sql = strftime('%Y-%m-%d',$temptime);	


$x_empresa_id = $_POST["x_empresa_id"];
$x_fondeo_credito_id = $_POST["x_fondeo_credito_id"];
$x_sucursal_srch = $_POST["x_sucursal_srch"];
$x_credito_tipo_srch = $_POST["x_credito_tipo_srch"];




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
<p><span class="phpmaker">REPORTE DE TASA PROMEDIO DE CARTERA TOTAL
<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="php_rpt_tpct.php?export=excel&x_fecha_desde=<?php echo FormatDateTime(@$x_fecha_desde,7); ?>&x_fecha_hasta=<?php echo FormatDateTime(@$x_fecha_hasta,7); ?>">Exportar a Excel</a><?php } ?>
</span></p>

<?php if ($sExport == "") { ?>
<p><span class="phpmaker">
<form action="php_rpt_tpct.php" name="filtro" id="filtro" method="post">
<table width="660" align="left" class="phpmaker">
	<tr>
	  <td>Fondo</td>
	  <td><?php
$x_medio_pago_idList = "<select  name=\"x_empresa_id\" onchange=\"cargalineas(this,'txtlineas',0)\">";
$x_medio_pago_idList .= "<option value='1000'>TODOS</option>";
$sSqlWrk = "SELECT fondeo_empresa.fondeo_empresa_id, fondeo_empresa.nombre FROM fondeo_empresa order by fondeo_empresa.fondeo_empresa_id ";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		
		$x_medio_pago_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["x_empresa_id"] == $x_empresa_id) {
			$x_medio_pago_idList .= "' selected";
		}
/*

		if($x_fondeo_saldo > 0){
			$x_medio_pago_idList .= ">" . $datawrk["nombre"] . " Credito No.: " . $datawrk["credito_num"] . " Saldo: " . FormatNumber($x_fondeo_saldo,0,0,0,1) . "</option>";
		}else{
			if(strtoupper($datawrk["nombre"]) == "FONDOS PROPIOS"){
				$x_medio_pago_idList .= ">" . $datawrk["nombre"] . "</option>";
			}
		}

*/

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
		?></td>
	  <td>&nbsp;</td>
	  <td colspan="2">

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
		<td width="96">Tipo de Credito</td>
		<td width="107"><?php
		$x_estado_civil_idList = "<select name=\"x_credito_tipo_srch\" class=\"texto_normal\">";
		$sSqlWrk = "SELECT credito_tipo_id, descripcion FROM credito_tipo ";	
		$x_estado_civil_idList .= "<option value=\"1000\">TODOS</option>";	
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["credito_tipo_id"] == $x_credito_tipo_srch) {
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
		<td width="74">&nbsp;</td>
		<td width="104">&nbsp;</td>		
		<td width="255"><span class="phpmaker">
		<input type="button"  value="Generar Reporte" name="Generar Reporte" onclick="filtrar();"  />
		</span>		
        </td>		
	</tr>
</table>
</form>
</span></p>
<br />
<br />
<?php } ?>
<br /><br /><br />
<p><span class="phpmaker">
<?php

$sSql = "truncate table tpcv_tmp";
phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);

$nRecCount = 0;
$x_fecha_actual = $x_fecha_desde_sql;

while($x_fecha_actual <= $x_fecha_hasta_sql){

// Cartera Vigente
	
	$sSql = "SELECT sum(importe) as capital from vencimiento where credito_id in (select credito_id from credito where credito_status_id not in (2,5) and fecha_otrogamiento <= '".$x_fecha_actual."') ";
	
	if(!empty($x_fondeo_credito_id) && $x_fondeo_credito_id > 0 && $x_fondeo_credito_id != 1000){
		$sSql .= " AND credito_id in (select credito_id from fondeo_colocacion where fondeo_credito_id = $x_fondeo_credito_id) ";		
	}
	if(!empty($x_sucursal_srch) && $x_sucursal_srch > 0 && $x_sucursal_srch != 1000){
		$sSql .= " AND credito_id in (select credito_id from credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id where promotor.sucursal_id = $x_sucursal_srch) ";		
	}
	if(!empty($x_credito_tipo_srch) && $x_credito_tipo_srch > 0 && $x_credito_tipo_srch != 1000){
		$sSql .= " AND vencimiento.credito_id in (select credito_id from credito where credito_tipo_id = $x_credito_tipo_srch) ";		
	}
	
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = @phpmkr_fetch_array($rs);
	$x_tot_otorgado = $row["capital"];
	phpmkr_free_result($rs);				


	$sSql = "SELECT sum(vencimiento.importe) as pagado from vencimiento where (vencimiento.vencimiento_status_id in(2,5)) AND (vencimiento.credito_id not in (select credito_id from credito where credito_status_id in (2,5)))";
	
	if(!empty($x_fondeo_credito_id) && $x_fondeo_credito_id > 0 && $x_fondeo_credito_id != 1000){
		$sSql .= " AND vencimiento.credito_id in (select credito_id from fondeo_colocacion where fondeo_credito_id = $x_fondeo_credito_id) ";		
	}
	if(!empty($x_sucursal_srch) && $x_sucursal_srch > 0 && $x_sucursal_srch != 1000){
		$sSql .= " AND vencimiento.credito_id in (select credito_id from credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id where promotor.sucursal_id = $x_sucursal_srch) ";		
	}
	if(!empty($x_credito_tipo_srch) && $x_credito_tipo_srch > 0 && $x_credito_tipo_srch != 1000){
		$sSql .= " AND vencimiento.credito_id in (select credito_id from credito where credito_tipo_id = $x_credito_tipo_srch) ";		
	}
	
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = @phpmkr_fetch_array($rs);
	$x_tot_pagado = $row["pagado"];
	phpmkr_free_result($rs);				


//quitar creditos incobrables y cancelados de pagos

	$x_tot_cartera = 0;	
	$x_tot_cartera = $x_tot_otorgado - $x_tot_pagado;	
	

//Saldos Creditos Semanales
	$sSql = "SELECT credito_id, tasa, forma_pago_id, num_pagos from credito where credito_status_id not in (2,5) and fecha_otrogamiento <= '".$x_fecha_actual."'";
	
	if(!empty($x_fondeo_credito_id) && $x_fondeo_credito_id > 0 && $x_fondeo_credito_id != 1000){
		$sSql .= " AND credito_id in (select credito_id from fondeo_colocacion where fondeo_credito_id = $x_fondeo_credito_id) ";		
	}
	if(!empty($x_sucursal_srch) && $x_sucursal_srch > 0 &&$x_sucursal_srch != 1000){
		$sSql .= " AND credito_id in (select credito_id from credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id where promotor.sucursal_id = $x_sucursal_srch) ";		
	}
	if(!empty($x_credito_tipo_srch) && $x_credito_tipo_srch > 0 && $x_credito_tipo_srch != 1000){
		$sSql .= " AND credito.credito_tipo_id = $x_credito_tipo_srch ";		
	}
	
//	echo $sSql;
	
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$nRecCount = 0;
	$x_hombres = 0;
	$x_mujeres = 0;
	$x_creditos = 0;
	while($row = @phpmkr_fetch_array($rs)){
		$x_credito_id = $row["credito_id"];
		$x_tasa = $row["tasa"];				
		$x_forma_pago_id = $row["forma_pago_id"];		
		$x_num_pagos = $row["num_pagos"];				
	
		$sSql = "SELECT sum(importe) as capital from vencimiento where credito_id = $x_credito_id";
		$rs1 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row1 = @phpmkr_fetch_array($rs1);
		$x_tot_otorgado = $row1["capital"];
		phpmkr_free_result($rs1);				


		$sSql = "SELECT sum(vencimiento.importe) as pagado FROM vencimiento 
	WHERE vencimiento.credito_id = $x_credito_id AND vencimiento.vencimiento_status_id in(2,5)";
		$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row2 = @phpmkr_fetch_array($rs2);
		$x_tot_pagado = $row2["pagado"];
		phpmkr_free_result($rs2);				


		$sSql = "SELECT fecha_vencimiento FROM vencimiento 
	WHERE vencimiento.credito_id = $x_credito_id order by fecha_vencimiento desc limit 1";
		$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row2 = @phpmkr_fetch_array($rs2);
		$x_fecha_vencimiento = $row2["fecha_vencimiento"];
		phpmkr_free_result($rs2);				

		$x_dias_neg = 0;
		$x_dias = 0;
		$x_dias = datediff('d', $x_fecha_actual, $x_fecha_vencimiento);
		$x_dias_neg = $x_dias;
		if($x_dias < 0){
			$x_dias = 0;	
		}

		$x_saldo = 0;
		$x_saldo = $x_tot_otorgado - $x_tot_pagado;
		
		
		$x_tasa_anual = 0;
		$x_fac_pp = 0;
		switch ($x_forma_pago_id)
		{
			case "1": // semanal
				$x_tasa_anual = $x_tasa * 52;
				break;	
			case "2": // catorcenal
				$x_tasa_anual = $x_tasa * 26;
				break;	
			case "3": // mensual
				$x_tasa_anual = $x_tasa * 24;
				break;	
			case "4": // quincenal
				$x_tasa_anual = $x_tasa * 13;
				break;	
		}
		
		$x_tpcv = 0;
		$x_tpcv = (($x_saldo / $x_tot_cartera) * ($x_tasa_anual / 100));
		
		$x_pp = 0;
		$x_ppneg = 0;
		$x_pp = (($x_saldo / $x_tot_cartera) * $x_dias);		
		$x_ppneg = (($x_saldo / $x_tot_cartera) * $x_dias_neg);				

		$sSql = "insert into tpcv_tmp values(0,'$x_fecha_actual', $x_tot_cartera, $x_credito_id, $x_tpcv, $x_pp, $x_ppneg)";
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		
		
		//sexo y edad
		$sSql = "SELECT sexo, fecha_nac FROM cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id join solicitud on solicitud.solicitud_id = solicitud_cliente.solicitud_id join credito on credito.solicitud_id = solicitud.solicitud_id where credito.credito_id = $x_credito_id";
		$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row2 = @phpmkr_fetch_array($rs2);
		$x_sexo = $row2["sexo"];
		$x_fecha_nac = $row2["fecha_nac"];		
		phpmkr_free_result($rs2);				
		
		$x_edad = datediff('yyyy', $x_fecha_actual, $x_fecha_nac);	
		if($x_edad < 18 || $x_edad > 90){
			$x_edad = 0;	
		}
		$x_edad_promedio = $x_edad_promedio + $x_edad;
		
		if($x_sexo == 1){
			$x_hombres++;	
		}else{
			$x_mujeres++;			
		}
		
		if($x_edad > 0){
			$x_creditos++;
		}
		
	}
	phpmkr_free_result($rs);	
	
	if($x_edad_promedio > 0){
		$x_edad_promedio = $x_edad_promedio / $x_creditos;
	}else{
		$x_edad_promedio = 0;
	}
	
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

<br /><br /><br /><br />
<table id="ewlistmain" class="ewTable" align="left">
	<!-- Table header -->
	<tr class="ewTableHeaderThin" >
		<td valign="top"><span>
Fecha
		</span></td>
		<td valign="top">Fondo</td>
		<td valign="top">Sucursal</td>
		<td valign="top">Tipo</td>
		<td valign="top">
Cartera Total 
		</td>
		<td valign="top">Hombres</td>
		<td valign="top">Mujeres</td>
		<td valign="top">Edad p</td>
		<td valign="top">Plazo Promedio neg</td>
		<td valign="top">Plazo Promedio</td>        
		<td valign="top"><span>
Tasa promedio
		</span></td>        
	</tr>

<?php
$nRecCount = 0;
$sSql = "SELECT fecha_tpcv, cv, sum(tpcv) as tpcv_total, sum(pp) as pp_total, sum(ppn) as ppn_total  from tpcv_tmp group by fecha_tpcv order by fecha_tpcv ";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$nRecCount = 0;
while($row = @phpmkr_fetch_array($rs)){

	$nRecCount = $nRecCount + 1;

	// Set row color
	$sItemRowClass = " class=\"ewTableRow\"";
	$sListTrJs = "";

	// Display alternate color for rows
	if ($nRecCount % 2 <> 1) {
		$sItemRowClass = " class=\"ewTableAltRow\"";
	}

	$x_fecha = $row["fecha_tpcv"];
	$x_cv = $row["cv"];	
	$x_tpcv = $row["tpcv_total"];	
	$x_pp = $row["pp_total"];		
	$x_ppn = $row["ppn_total"];			


?>
    
		<!-- Table body -->
		<tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>
			<td align="center"><span>
	<?php echo FormatDateTime($x_fecha,7); ?>
	</span></td>
		  <td align="left"><?php
			
			if(!empty($x_fondeo_credito_id) && $x_fondeo_credito_id > 0){		
				$sSqlWrk = "SELECT nombre FROM fondeo_empresa where fondeo_empresa_id = $x_empresa_id";	
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				echo $datawrk["nombre"];
				@phpmkr_free_result($rswrk);
			}
			
		?></td>
			<td align="left"><?php
			
			if(!empty($x_sucursal_srch) && $x_sucursal_srch > 0){		
				$sSqlWrk = "SELECT sucursal_id, nombre FROM sucursal where sucursal_id = $x_sucursal_srch";	
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				echo $datawrk["nombre"];
				@phpmkr_free_result($rswrk);
			}
			
		?></td>
			<td align="left"><?php
			
			if(!empty($x_credito_tipo_srch) && $x_credito_tipo_srch > 0){
				$sSqlWrk = "SELECT credito_tipo_id, descripcion FROM credito_tipo where credito_tipo_id = $x_credito_tipo_srch";					
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				echo $datawrk["descripcion"];
				@phpmkr_free_result($rswrk);
			}
			
		?></td>
			<!-- importe -->
			<td align="right"><?php echo FormatNumber($x_cv,2,0,0,1); ?></td>
			<td align="right"><?php echo FormatNumber($x_hombres,0,0,0,1); ?></td>
			<td align="right"><?php echo FormatNumber($x_mujeres,0,0,0,1); ?></td>
			<td align="right"><?php echo FormatNumber($x_edad_promedio,2,0,0,0); ?></td>
			<td align="right"><?php echo FormatNumber($x_ppn,2,0,0,1); ?></td>
			<td align="right"><?php echo FormatNumber($x_pp,2,0,0,1); ?></td>	
      <!-- importe -->
			<td align="right"><span>
	<?php echo FormatNumber($x_tpcv,2,0,0,1); ?>
	</span></td>	
			<!-- importe -->
		</tr>
<?php
}
?>
</table>
</span></p>
<?php
phpmkr_free_result($rs);				
// Close recordset and connection
phpmkr_db_close($conn);
?>
<?php if ($sExport <> "word" && $sExport <> "excel") { ?>
<?php include ("footer.php") ?>
<?php } ?>
