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
$x_fecha_hasta = $_POST["x_fecha_hasta"];	

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
&nbsp;&nbsp;<a href="php_rpt_cv.php?export=excel">Exportar a Excel</a><?php } ?>
</span></p>
<p>


<?php if ($sExport == "") { ?>
<form action="php_rpt_cv.php" name="filtro" id="filtro" method="post">
<table width="660" class="phpmaker">
	<tr>
		<td width="55"><div align="right"><span class="phpmaker">
		  Desde:
		  </span> </div>
         </td>
		<td width="102"><span>
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
		<td width="41"><div align="right"><span class="phpmaker">
		  Hasta:
		  </span> </div>
         </td>
		<td width="96"><span>
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
		<td width="61"><span class="phpmaker">
		<input type="button"  value="Filtrar" name="Generar Reporte" onclick="filtrar();"  />
		</span>		
        </td>		
	</tr>
</table>
</form>
<?php } ?>


<table id="ewlistmain" class="ewTable">
	<!-- Table header -->
	<tr class="ewTableHeader">
		<td valign="top"><span>
Fecha
		</span></td>
		<td valign="top"><span>
Capital
		</span></td>        
		<td valign="top"><span>
Interés
		</span></td>
		<td valign="top"><span>
Interés moratorio
		</span></td>
		<td valign="top"><span>        
Total Fecha
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
	$sSql = "SELECT * from vencimiento where fecha_vencimiento <= '".$x_fecha_actual."' and vencimiento_status_id <> 4 ";

	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);

	$x_tot_importe = 0;
	$x_tot_intereses = 0;
	$x_tot_moratorios = 0;
	$x_tot_total_fecha = 0;
	while ($row = @phpmkr_fetch_array($rs)) {

		$x_vencimiento_id = $row["vencimiento_id"];
		$x_vencimiento_status_id = $row["vencimiento_status_id"];

		$x_cv = false;
		
		if(($x_vencimiento_status_id == 1) || ($x_vencimiento_status_id == 3)){
			$x_cv = true;
		}

		if(($x_vencimiento_status_id == 2) || ($x_vencimiento_status_id == 5)){
		/*
			$sSqlWrk = "SELECT recibo.fecha_pago FROM recibo_vencimiento join recibo on recibo.recibo_id = recibo_vencimiento.recibo_id where recibo_vencimiento.vencimiento_id = $x_vencimiento_id";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_fecha_pago = $datawrk["fecha_pago"];
			@phpmkr_free_result($rswrk);

			$temptime = strtotime($x_fecha_pago);	
			$x_fecha_pago = strftime('%Y-%m-%d',$temptime);	

			if($x_fecha_pago > $x_fecha_actual){
				$x_cv = true;			
			}
		*/
		}		
	
	
		if($x_cv == true){

			$x_importe = $row["importe"];
			$x_interes = $row["interes"];
			$x_interes_moratorio = $row["interes_moratorio"];
			$x_total_venc = $row["total_venc"];		
		
			$x_tot_importe = $x_tot_importe + $x_importe;
			$x_tot_intereses = $x_tot_intereses + $x_interes;
			$x_tot_moratorios = $x_tot_moratorios + $x_interes_moratorio;
			$x_tot_total_fecha = $x_tot_total_fecha + $x_total_venc;
		}
	
	}
	
	phpmkr_free_result($rs);			
	?>
		<!-- Table body -->
		<tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>
			<td align="center"><span>
	<?php echo FormatDateTime($x_fecha_actual,7); ?>
	</span></td>
			<!-- importe -->
			<td align="right"><span>
	<?php echo FormatNumber($x_tot_importe,2,0,0,1); ?>
	</span></td>
			<!-- interes -->
			<td align="right"><span>
	<?php echo FormatNumber($x_tot_intereses,2,0,0,1); ?>
	</span></td>
			<!-- interes_moratorio -->
			<td align="right"><span>
	<?php echo FormatNumber($x_tot_moratorios,2,0,0,1); ?>
	</span></td>
			<td align="right"><span>
	<?php echo FormatNumber($x_tot_total_fecha,2,0,0,1); ?>
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

<?php
// Close recordset and connection
phpmkr_db_close($conn);
?>
<?php if ($sExport <> "word" && $sExport <> "excel") { ?>
<?php include ("footer.php") ?>
<?php } ?>
