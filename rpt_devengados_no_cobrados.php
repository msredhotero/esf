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
	header('Content-Disposition: attachment; filename=reporte_devengado_no_cobrados'.date("Y-m-d").'.xls');
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
<p><span class="phpmaker">REPORTE DEVENGADOS NO COBRADOS
<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<?php } ?>
<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="rpt_devengados_no_cobrados.php?export=excel&x_mes=<?php echo $x_mes; ?>&x_year=<?php echo $x_year; ?>&x_empresa_id=<?php echo $x_empresa_id; ?>&x_fondeo_credito_id=<?php echo $x_fondeo_credito_id; ?>&x_sucursal_srch=<?php echo $x_sucursal_srch; ?>&x_promo_srch=<?php echo $x_promo_srch; ?>&x_pagado_srch=<?php echo $x_pagado_srch; ?>&x_credito_status_srch=<?php echo $x_credito_status_srch; ?>">Exportar a Excel</a><?php } ?>
</span></p>
</span></p>

<?php if ($sExport == "") { ?>
<?php } ?>
<p align="left"><span class="phpmaker">
<div align="left">
  <table id="ewlistmain" class="ewTable" align="left">
    <!-- Table header -->
    <tr class="ewTableHeader" >
		
		<td width="175" valign="top">Sucursal</td>
		<td width="175" valign="top">Promotor</td>
        <td width="175" valign="top">Cliente</td>
        <td width="175" valign="top">Cr&eacute;dito num</td>
        <td width="175" valign="top">Status</td>
		<td width="175" valign="top"><span> Interes
</span></td>        
		<td width="196" valign="top"><span> Moratorios
</span></td>
		<td width="133" valign="top">Comision</td>
		<td width="192" valign="top"><span> Total
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

 if(empty($x_filtro_status)){
	  $x_filtro_status = " AND credito.credito_status_id in (1)";
	  }

if(!empty($x_filtros)){
 // echo "filtros llenos<br>";
 
$sSql = "select sum(vencimiento.importe) as capital, sum(interes) as interes, sum(interes_moratorio) as moratorios, sum(vencimiento.iva) as iva, sum(iva_mor) as ivmor from vencimiento join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id where year(vencimiento.fecha_vencimiento) = $x_year and month(vencimiento.fecha_vencimiento) = $x_mes".$x_filtros .$x_filtro_status;

$sSql = "select  credito.credito_id, credito.solicitud_id, credito.credito_num, credito.credito_status_id, sum(vencimiento.importe) as capital, sum(interes) as interes, sum(interes_moratorio) as moratorios, sum(vencimiento.iva) as iva, sum(iva_mor) as ivmor from vencimiento join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id where    year(vencimiento.fecha_vencimiento) = $x_year and month(vencimiento.fecha_vencimiento) = $x_mes".$x_filtros . $x_filtro_status. "GROUP BY credito.credito_id";

}else{
$sSql = "select sum(vencimiento.importe) as capital, sum(interes) as interes, sum(interes_moratorio) as moratorios, sum(iva) as iva, sum(iva_mor) as ivmor from vencimiento where year(fecha_vencimiento) = $x_year and month(fecha_vencimiento) = $x_mes".$x_filtros;

$sSql = "select credito.credito_id, credito.solicitud_id, credito.credito_num, credito.credito_status_id,sum(vencimiento.importe) as capital, sum(vencimiento.interes) as interes, sum(vencimiento.interes_moratorio) as moratorios, sum(vencimiento.iva) as iva, sum(vencimiento.iva_mor) as ivmor from vencimiento, credito  where credito.credito_id = vencimiento.credito_id  and  year(vencimiento.fecha_vencimiento) = $x_year and month(vencimiento.fecha_vencimiento) = $x_mes  ".$x_filtros. $x_filtro_status ." GROUP BY credito.credito_id ";
}

$x_hoy_es = date("Y-m-d");
$sSql = "SELECT sum(vencimiento.interes) as interes_devengado_no_pagado , credito.credito_id from vencimiento  join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id where (vencimiento.vencimiento_status_id in(1,3,6,7))   AND (vencimiento.credito_id not in (select credito_id from credito where credito_status_id in (2,5))) and vencimiento.fecha_vencimiento >= \"2014-03-01\" and vencimiento.fecha_vencimiento < \"$x_hoy_es\" ".$x_filtros."GROUP BY credito_id";



echo "SQL: ....".$sSql;
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
while($row = @phpmkr_fetch_array($rs)){

$x_capital = $row["capital"];
$interes_devengado_no_pagado = $row["interes_devengado_no_pagado"];
$x_moratorios = $row["moratorios"];

$x_solicitud_id = $row["solicitud_id"];
$x_credito_id = $row["credito_id"];

//echo "<br>".$row["credito_num"].  $row["credito_status_id"];
$x_credito_status = $row["credito_status_id"];
$x_iva = $row["iva"];
$x_ivmor = $row["ivmor"];
$x_total_credito = $x_ivmor + $x_iva + $x_moratorios + $x_capital;
$x_total_ivmor += $x_ivmor;
$x_total_interes_devengado_no_pagado += $interes_devengado_no_pagado;
$x_total_moratorios += $x_moratorios;
$x_total_com +=  $x_capital;
$x_total_general += $x_total_credito;

// datos del credito

$x_cliente = "";
$sqlFondo = "select * from credito where credito_id = $x_credito_id  ";
$rsFondeo = phpmkr_query($sqlFondo,$conn) or die ("Error al seleccionar fodeo");
$rowFondeo = phpmkr_fetch_array($rsFondeo);
$x_solicitud_id = $rowFondeo["solicitud_id"];
$x_credito_status =  $rowFondeo["credito_status_id"];
$x_credito_num = $rowFondeo["credito_num"];


$sSql1 = "SELECT sum(vencimiento.interes_moratorio) as mora_devengado_no_pagado from vencimiento  join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id where (vencimiento.vencimiento_status_id in(1,3,6,7))   AND (vencimiento.credito_id not in (select credito_id from credito where credito_status_id in (2,5))) and vencimiento.fecha_vencimiento >= \"2014-03-01\"  and vencimiento.fecha_vencimiento < \"$x_hoy_es\" and vencimiento.vencimiento_num < 3000  and credito.credito_id = $x_credito_id".$x_filtros;	
	#echo "<br> devengado no pgado".$sSql."<br>";
	$rs1 = phpmkr_query($sSql1,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row1 = @phpmkr_fetch_array($rs1);
	$x_mora_devengado_no_pagado = $row1["mora_devengado_no_pagado"];
	$x_total_mora_devengado_no_pagado += $x_mora_devengado_no_pagado;
	$sSql1 = "SELECT sum(vencimiento.interes_moratorio) as mora_devengado_no_pagado_com from vencimiento  join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id where (vencimiento.vencimiento_status_id in(1,3,6,7))   AND (vencimiento.credito_id not in (select credito_id from credito where credito_status_id in (2,5))) and vencimiento.fecha_vencimiento >= \"2014-03-01\" and vencimiento.fecha_vencimiento < \"$x_hoy_es\" and vencimiento.vencimiento_num > 3000 and credito.credito_id = $x_credito_id ".$x_filtros;	
	#echo "<br> devengado no pgado".$sSql."<br>";
	$rs1 = phpmkr_query($sSql1,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row1 = @phpmkr_fetch_array($rs1);
	$x_mora_devengado_no_pagado_com = $row1["mora_devengado_no_pagado_com"];
	$x_total_mora_devengado_no_pagado_com += $x_mora_devengado_no_pagado_com;
	
	$x_tota_gral += $x_mora_devengado_no_pagado_com + $x_mora_devengado_no_pagado + $interes_devengado_no_pagado;





//datos del cliente 
$x_cliente = "";
$sqlFondo = "select * from cliente, solicitud_cliente where cliente.cliente_id= solicitud_cliente.cliente_id and solicitud_cliente .solicitud_id =$x_solicitud_id  ";
$rsFondeo = phpmkr_query($sqlFondo,$conn) or die ("Error al seleccionar fodeo");
$rowFondeo = phpmkr_fetch_array($rsFondeo);
$x_cliente = $rowFondeo["nombre_completo"]." ".$rowFondeo["apellido_paterno"]." ".$rowFondeo["apellido_materno"];

			
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
			<td align="right"><?php echo FormatNumber($interes_devengado_no_pagado,2,0,0,1); ?></td>	
			<!-- importe -->
			<td align="right"><?php echo FormatNumber($x_mora_devengado_no_pagado,2,0,0,1); ?></td>
			<td align="right"><?php echo FormatNumber($x_mora_devengado_no_pagado,2,0,0,1); ?></td>
			<!-- importe -->
			<td><?php echo FormatNumber($interes_devengado_no_pagado + $x_mora_devengado_no_pagado +$x_mora_devengado_no_pagado_com,2,0,0,1); ?></td>
    
		</tr>
        <?php }// while sql ?>
        <tr>

        <td colspan="6"></td>
        <td align="right"><span>
	
	</span></td>	
			<!-- importe -->
			<td align="right"><?php echo FormatNumber($x_total_interes_devengado_no_pagado,2,0,0,1); ?></td>
			<td align="right"><?php echo FormatNumber($x_total_mora_devengado_no_pagado,2,0,0,1); ?></td>
			<td align="right"><?php echo FormatNumber($x_total_mora_devengado_no_pagado_com,2,0,0,1); ?></td>
            <td align="right"><?php echo FormatNumber($x_tota_gral,2,0,0,1); ?></td>	
			<!-- importe -->		  </tr>
        
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
