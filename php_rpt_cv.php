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
	header('Content-Disposition: attachment; filename=reporte_cv.xls');
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
<p><span class="phpmaker">REPORTE DE CARTERA VIGENTE
<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="php_rpt_cv.php?export=excel&x_fecha_desde=<?php echo FormatDateTime(@$x_fecha_desde,7); ?>&x_fecha_hasta=<?php echo FormatDateTime(@$x_fecha_hasta,7); ?>">Exportar a Excel</a><?php } ?>
</span></p>

<?php if ($sExport == "") { ?>
<p><span class="phpmaker">
<form action="php_rpt_cv.php" name="filtro" id="filtro" method="post">
<table width="660" align="left" class="phpmaker">
	<tr>
	  <td>Fondo</td>
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
		if ($datawrk["x_empresa_id"] == @$x_empresa_id) {
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

		if(strtoupper($datawrk["nombre"]) == "FONDOS PROPIOS"){
			$x_medio_pago_idList .= ">" . $datawrk["nombre"] . "</option>";
		}else{
			$x_medio_pago_idList .= ">" . $datawrk["nombre"] . "</option>";
		}



		
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
				if ($datawrk["sucursal_id"] == $_SESSION["x_sucursal_srch"]) {
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
	  <td>&nbsp;</td>
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
<br /><br /><br /><br /><br /><br /><br />
<p><span class="phpmaker">
<table id="ewlistmain" class="ewTable" align="left">
	<!-- Table header -->
	<tr class="ewTableHeaderThin" >
		<td valign="top"><span>
Fecha
		</span></td>
		<td valign="top"><span>
Otrogado
		</span></td>        
		<td valign="top"><span>
Pagado
		</span></td>        
		<td valign="top"><span>
Vencido
		</span></td>                
		<td valign="top"><span>
Cartera Vigente
		</span></td>                
	</tr>
<?php
$nRecCount = 0;
$x_fecha_actual = $x_fecha_desde_sql;

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
	
	$sSql = "SELECT sum(importe) as capital from vencimiento where credito_id in (select credito_id from credito where credito_status_id not in (2,5) and fecha_otrogamiento <= '".$x_fecha_actual."')";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = @phpmkr_fetch_array($rs);
	$x_tot_otorgado = $row["capital"];
	phpmkr_free_result($rs);				



	$sSql = "SELECT sum(vencimiento.importe) as vencido from vencimiento where 
(vencimiento.credito_id not in (select credito_id from credito where credito_status_id in (2,5)))	
AND (vencimiento.vencimiento_status_id in(3)) AND (vencimiento.fecha_vencimiento <= '".$x_fecha_actual."')";

	$sSql = "SELECT sum(vencimiento.importe) as vencido from vencimiento where credito_id in (select credito_id from credito where credito_status_id not in (2,5) and fecha_otrogamiento <= '".$x_fecha_actual."') and vencimiento_status_id = 3";



	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = @phpmkr_fetch_array($rs);
	$x_tot_vencido = $row["vencido"];
	phpmkr_free_result($rs);				


	$sSql = "SELECT sum(vencimiento.importe) as pagado from vencimiento where (vencimiento.vencimiento_status_id in(2,5)) AND (vencimiento.credito_id not in (select credito_id from credito where credito_status_id in (2,5)))";
	
	$sSql = "SELECT sum(vencimiento.importe) as pagado from vencimiento where credito_id in (select credito_id from credito where credito_status_id not in (2,5) and fecha_otrogamiento <= '".$x_fecha_actual."') and vencimiento_status_id in (2,5)";

	
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = @phpmkr_fetch_array($rs);
	$x_tot_pagado = $row["pagado"];
	phpmkr_free_result($rs);				


	
	$x_tot_cartera = ($x_tot_otorgado - ($x_tot_pagado + $x_tot_vencido));	
	
	
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
			<td align="right"><span>
	<?php echo FormatNumber($x_tot_vencido,2,0,0,1); ?>
	</span></td>	    
			<!-- importe -->
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
</span></p>
<?php
// Close recordset and connection
phpmkr_db_close($conn);
?>
<?php if ($sExport <> "word" && $sExport <> "excel") { ?>
<?php include ("footer.php") ?>
<?php } ?>
