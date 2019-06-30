<?php session_start(); ?>
<?php ob_start(); ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Cache-Control: private");
header("Pragma: no-cache"); // HTTP/1.0 

if (@$_SESSION["php_project_esf_status"] <> "login") {
	header("Location:  login.php");
	exit();
}
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php 
include ("header.php");
if ($_POST["x_fecha_desde"] != "") {
	$x_fecha_desde = $_POST["x_fecha_desde"];
	$x_fecha_hasta = $_POST["x_fecha_hasta"];	
//	header("Location:  php_circulocredito_rpt.php?key1=$x_fecha_desde&key2=$x_fecha_hasta");
	header("Location:  php_burocredito_rpt.php?key1=$x_fecha_desde&key2=$x_fecha_hasta");	
	exit();
}else{
	$x_fecha_desde = $currdate;
	$x_fecha_hasta = $currdate;	
}
?>
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
<script type="text/javascript" src="utilerias/datefunc.js"></script>
<script type="text/javascript">
<!--
function generar(){
EW_this = document.filtro;
validada = true;

	if (validada && EW_this.x_fecha_desde && !EW_hasValue(EW_this.x_fecha_desde, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_fecha_desde, "TEXT", "La primera fecha es requerida."))
			validada = false;
	}

	if (validada && EW_this.x_fecha_hasta && !EW_hasValue(EW_this.x_fecha_hasta, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_fecha_hasta, "TEXT", "La segunda fecha es requerida."))
			validada = false;
	}

	if(validada == true){
		if (!compareDates(EW_this.x_fecha_desde.value, EW_this.x_fecha_hasta.value)) {	
			if (!EW_onError(EW_this, EW_this.x_fecha_desde, "TEXT", "La segunda fecha no puede ser menor a la primera fecha."))
				validada = false; 
		}
	}

	if(validada == true){
		EW_this.submit();
	}
}
//-->
</script>

<form action="php_burocredito_list.php" name="filtro" id="filtro" method="post">
<table width="587">
	<tr>
	  <td colspan="6" class="ewTableHeaderThin">GENERACION DEL REPORTE PARA BURO DE CREDITO</td>
	  </tr>
	<tr>
	  <td width="102" height="18">&nbsp;</td>
	  <td width="154">&nbsp;</td>
	  <td width="40">&nbsp;</td>
	  <td width="152">&nbsp;</td>
	  <td width="78">&nbsp;</td>
	  <td width="33">&nbsp;</td>
	  </tr>
	<tr>
		<td><span class="phpmaker">
		Semana del:
		</span>		</td>
		<td><span>
		<input type="text" name="x_fecha_desde" id="x_fecha_desde" value="<?php echo FormatDateTime(@$x_fecha_desde,7); ?>">
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
		</span>		</td>
		<td><span class="phpmaker">al:
		</span>		</td>
		<td><span>
		<input type="text" name="x_fecha_hasta" id="x_fecha_hasta" value="<?php echo FormatDateTime(@$x_fecha_hasta,7); ?>">
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
		</span>		</td>		
		<td><span class="phpmaker">
		<input type="button"  value="GENERAR" name="filtro" onclick="generar();"  />
		</span>		</td>		
		<td>&nbsp;</td>		
	</tr>
</table>
</form>

<?php

// Close recordset and connection
?>
<?php include ("footer.php") ?>

