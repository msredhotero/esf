<?php set_time_limit(0); ?>
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
$x_vencimiento_id = Null; 
$ox_vencimiento_id = Null;
$x_credito_id = Null; 
$ox_credito_id = Null;
$x_vencimiento_status_id = Null; 
$ox_vencimiento_status_id = Null;
$x_fecha_vencimiento = Null; 
$ox_fecha_vencimiento = Null;
$x_importe = Null; 
$ox_importe = Null;
$x_interes = Null; 
$ox_interes = Null;
$x_interes_moratorio = Null; 
$ox_interes_moratorio = Null;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
$nStartRec = 0;
$nStopRec = 0;
$nTotalRecs = 0;
$nRecCount = 0;
$nRecActual = 0;
$sKeyMaster = "";
$sDbWhereMaster = "";
$sSrchAdvanced = "";
$sDbWhereDetail = "";
$sSrchBasic = "";
$sSrchWhere = "";
$sDbWhere = "";
$sDefaultOrderBy = "";
$sDefaultFilter = "";
$sWhere = "";
$sGroupBy = "";
$sHaving = "";
$sOrderBy = "";
$sSqlMasterBase = "";
$sSqlMaster = "";
$sListTrJs = "";
$bEditRow = "";
$nEditRowCnt = "";
$sDeleteConfirmMsg = "";
$nDisplayRecs = 500;
$nRecRange = 10;

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

if($_POST["x_rec"] == "r"){

	$x_credito_id = $_POST["credito_id"];
	$x_num_venc = $_POST["x_num_venc"];
	$x_contador = 1;
	while ($x_contador <= $x_num_venc){
		eval("\$x_editable = \$_POST['x_editable_".$x_contador."'];");	
		if($x_editable == 1){
			eval("\$x_vencimiento_id = \$_POST['x_vencimiento_id_".$x_contador."'];");			
			eval("\$x_importe = \$_POST['x_importe_".$x_contador."'];");						
			eval("\$x_interes = \$_POST['x_interes_".$x_contador."'];");									
			eval("\$x_iva = \$_POST['x_iva_".$x_contador."'];");												
			eval("\$x_iva_mor = \$_POST['x_iva_mor_".$x_contador."'];");															
			eval("\$x_interes_moratorio = \$_POST['x_interes_moratorio_".$x_contador."'];");												
			eval("\$x_fecha_vencimiento = \$_POST['x_fecha_vencimiento_".$x_contador."'];");															
			eval("\$x_total = \$_POST['x_total_".$x_contador."'];");									

			$x_importe = ($x_importe != "") ? doubleval($x_importe) : "0";
			$x_interes = ($x_interes != "") ? doubleval($x_interes) : "0";
			$x_iva = ($x_iva != "") ? doubleval($x_iva) : "0";			
			$x_iva_mor = ($x_iva_mor != "") ? doubleval($x_iva_mor) : "0";						
			$x_interes_moratorio = ($x_interes_moratorio != "") ? doubleval($x_interes_moratorio) : "0";
			$x_total = ($x_total != "") ? doubleval($x_total) : "0";									

			$x_fecha_vencimiento = ($x_fecha_vencimiento != "") ? "'".ConvertDateToMysqlFormat($x_fecha_vencimiento)."'" : "NULL";
			
			$sSql = "UPDATE vencimiento SET  importe = $x_importe, interes = $x_interes, iva = $x_iva,  interes_moratorio = $x_interes_moratorio, iva_mor = $x_iva_mor, vencimiento_status_id = 1, fecha_vencimiento = $x_fecha_vencimiento, total_venc = $x_total where vencimiento_id = $x_vencimiento_id";
//			phpmkr_query($sSql,$conn) or die("Se ha presentado un inconveniente tecnico.");

		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);			
		}
		$x_contador++;
	}
	
	header("Location:  php_vencimiento_revolventelist.php?credito_id=$x_credito_id");
	

}


// Handle Reset Command

// Build SQL
$sSql = "SELECT * FROM `vencimiento`";

// Load Default Filter
$sDefaultFilter = "";
$sGroupBy = "";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = "";

// Build WHERE condition
$x_credito_id = @$_GET["credito_id"];
if (($x_credito_id == "") || ((is_null($x_credito_id)))) {
	$x_credito_id = @$_POST["credito_id"];
	if (($x_credito_id == "") || ((is_null($x_credito_id)))) {	
		echo "Credito no localizado.";
		exit();
	}
}
$sDbWhere = "(vencimiento.credito_id = $x_credito_id) AND ";

if ($sDbWhereDetail <> "") {
	$sDbWhere .= "(" . $sDbWhereDetail . ") AND ";
}
if ($sSrchWhere <> "") {
	$sDbWhere .= "(" . $sSrchWhere . ") AND ";
}
if (strlen($sDbWhere) > 5) {
	$sDbWhere = substr($sDbWhere, 0, strlen($sDbWhere)-5); // Trim rightmost AND
}
$sWhere = "";
if ($sDefaultFilter <> "") {
	$sWhere .= "(" . $sDefaultFilter . ") AND ";
}
if ($sDbWhere <> "") {
	$sWhere .= "(" . $sDbWhere . ") AND ";
}
if (substr($sWhere, -5) == " AND ") {
	$sWhere = substr($sWhere, 0, strlen($sWhere)-5);
}
if ($sWhere != "") {
	$sSql .= " WHERE " . $sWhere;
}
if ($sGroupBy != "") {
	$sSql .= " GROUP BY " . $sGroupBy;
}
if ($sHaving != "") {
	$sSql .= " HAVING " . $sHaving;
}

// Set Up Sorting Order
/*
$sOrderBy = "";
SetUpSortOrder();
if ($sOrderBy != "") {
	$sSql .= " ORDER BY " . $sOrderBy;
}
*/

$sSql .= " ORDER BY vencimiento.vencimiento_num+0, vencimiento.vencimiento_id ";
	
//echo $sSql; // Uncomment to show SQL for debugging
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Financiera CRECE</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">td img {display: block;}</style>
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#FFFFFF">
<script type="text/javascript" src="NumberFormats.js"></script>
<script type="text/javascript" src="ew.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/skins/aqua/theme.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-es.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<script type="text/javascript">
<!--
var firstrowoffset = 1; // first data row start at
var tablename = 'ewlistmain'; // table name
var lastrowoffset = 0; // footer row
var usecss = true; // use css
var rowclass = 'ewTableRow'; // row class
var rowaltclass = 'ewTableAltRow'; // row alternate class
var rowmoverclass = 'ewTableHighlightRow'; // row mouse over class
var rowselectedclass = 'ewTableSelectRow'; // row selected class
var roweditclass = 'ewTableEditRow'; // row edit class
var rowcolor = '#FFFFFF'; // row color
var rowaltcolor = '#F5F5F5'; // row alternate color
var rowmovercolor = '#FFCCFF'; // row mouse over color
var rowselectedcolor = '#CCFFFF'; // row selected color
var roweditcolor = '#FFFF99'; // row edit color

//-->
</script>
<script type="text/javascript">
<!--
var EW_HTMLArea;

//-->
</script>
<script type="text/javascript">
<!--
function recalimp(){
EW_this = document.principal;

	v_num_venc = Number(EW_this.x_num_venc.value);

	tot_imp = 0;
	tot_intn = 0;
	tot_intm = 0;		
	tot_iva = 0;		
	tot_ivam = 0;			



    for(i=1; i <= v_num_venc; i = i + 1){		
        imp = eval("Number(document.principal.x_importe_" + i + ".value)");
        int_nor = eval("Number(document.principal.x_interes_" + i + ".value)");
        int_mor = eval("Number(document.principal.x_interes_moratorio_" + i + ".value)");				
        iva = eval("Number(document.principal.x_iva_" + i + ".value)");						
        ivam = eval("Number(document.principal.x_iva_mor_" + i + ".value)");								
		tot_line = Number(imp) + Number(int_nor) + Number(int_mor) + Number(iva) + Number(ivam);
		
//		x = addCommas(tot_line);
		
		eval("Number(document.principal.x_total_" + i + ".value = " + tot_line.toFixed(2) + ")");
		
        tot_imp = Number(tot_imp) + Number(imp);
		tot_intn = Number(tot_intn) + Number(int_nor);
		tot_intm = Number(tot_intm) + Number(int_mor);
		tot_iva = Number(tot_iva) + Number(iva);
		tot_ivam = Number(tot_ivam) + Number(ivam);		
    }





//	x = addCommas(tot_imp);
	EW_this.x_total_pagos.value = tot_imp.toFixed(2);
	
//	x = addCommas(tot_intn);
	EW_this.x_total_interes.value = tot_intn.toFixed(2);
	
//	x = addCommas(tot_intm);
	EW_this.x_total_moratorios.value = tot_intm.toFixed(2);

	EW_this.x_total_iva.value = tot_iva.toFixed(2);
	
	EW_this.x_total_iva_mor.value = tot_ivam.toFixed(2);	


	tot_tot = Number(tot_imp) + Number(tot_intn) + Number(tot_intm) + Number(tot_iva) + Number(tot_ivam);

//	x = addCommas(tot_tot);
	EW_this.x_total_total.value = tot_tot.toFixed(2);

	
}

//-->
</script>
<?php

// Set up Record Set
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$nTotalRecs = phpmkr_num_rows($rs);
if ($nDisplayRecs <= 0) { // Display All Records
	$nDisplayRecs = $nTotalRecs;
}
$nStartRec = 1;
?>
<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="phpmaker" style="color: Red;"><?php echo $_SESSION["ewmsg"]; ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>
<?php if ($nTotalRecs > 0)  { ?>
<form method="post" name="principal" action="php_restructura_revolvente.php">
<input type="hidden" name="credito_id" value="<?php echo $x_credito_id; ?>"  />
<input type="hidden" name="x_rec" value="r"  />

<table id="funciones" class="ewTable" align="center">
<tr>
<td>
<input type="button" name="cancelar" value="Cancelar" onclick="javascript: window.location = 'php_vencimiento_revolventelist.php?credito_id=<?php echo $x_credito_id; ?>';"  /></td>
<td>
<input type="button" name="aplicar" value="Aplicar restructura" onclick="javascript: document.principal.submit();" /></td>
</tr>
<tr>
  <td><a href="php_vencimiento_revolventeadd.php?credito_id=<?php echo $x_credito_id; ?>" target="_self">Agregar Vencmiento</a></td>
  <td>&nbsp;</td>
</tr>
</table>
<br />

<table id="ewlistmain" class="ewTable" align="center">
	<!-- Table header -->
	<tr class="ewTableHeaderThin" >
		<td valign="top"><span>
No
		</span></td>
		<td valign="top"><span>
Status
		</span></td>
		<td valign="top"><span>
&nbsp;
		</span></td>        
		<td valign="top"><span>
Fecha
		</span></td>
		<td valign="top"><span>
Importe
		</span></td>
		<td valign="top"><span>
Interés
		</span></td>
		<td valign="top">IVA</td>
		<td valign="top"><span>
Moratorio
		</span></td>
		<td valign="top">IVA Moratorios</td>
		<td valign="top"><span>
Total		
		</span></td>						
	</tr>
<?php

// Avoid starting record > total records
if ($nStartRec > $nTotalRecs) {
	$nStartRec = $nTotalRecs;
}

// Set the last record to display
$nStopRec = $nStartRec + $nDisplayRecs - 1;

// Move to first record directly for performance reason
$nRecCount = $nStartRec - 1;
if (phpmkr_num_rows($rs) > 0) {
	phpmkr_data_seek($rs, $nStartRec -1);
}


$sSqlWrk = "SELECT importe FROM credito where credito_id = $x_credito_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
$x_saldo = $datawrk["importe"];
@phpmkr_free_result($rswrk);

$x_total = 0;
$x_total_pagos = 0;
$x_total_interes = 0;
$x_total_iva = 0;
$x_total_iva_mor = 0;
$x_total_moratorios = 0;
$x_total_total = 0;
$nRecActual = 0;
$x_num_venc = 0;
$x_contador = 1;
while (($row = @phpmkr_fetch_array($rs)) && ($nRecCount < $nStopRec)) {
	$nRecCount = $nRecCount + 1;
	if ($nRecCount >= $nStartRec) {
		$nRecActual++;

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
		$x_iva = $row["iva"];		
		$x_iva_mor = $row["iva_mor"];				
		if(empty($x_iva)){
			$x_iva = 0;
		}
		if(empty($x_iva_mor)){
			$x_iva_mor = 0;
		}
		
		$x_interes_moratorio = $row["interes_moratorio"];
		
		$x_total = $x_importe + $x_interes + $x_interes_moratorio + $x_iva + $x_iva_mor;

		$x_total_pagos = $x_total_pagos + $x_importe;
		$x_total_interes = $x_total_interes + $x_interes;
		$x_total_iva = $x_total_iva + $x_iva;		
		$x_total_iva_mor = $x_total_iva_mor + $x_iva_mor;				
		$x_total_moratorios = $x_total_moratorios + $x_interes_moratorio;
		$x_total_total = $x_total_total + $x_total;
		
		$x_ref_loc = str_pad($x_vencimiento_id, 5, "0", STR_PAD_LEFT)."/".str_pad($x_vencimiento_num, 2, "0", STR_PAD_LEFT);
		
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>
		<!-- vencimiento_id -->
		<td align="right"><span>
<?php echo $x_vencimiento_num; ?>
<?php
if($x_vencimiento_status_id == 1 || $x_vencimiento_status_id == 3){
	$x_editable = 1;	
}else{
	$x_editable = 0;
}
?>
<input type="hidden" name="x_editable_<?php echo $x_contador; ?>" value="<?php echo $x_editable; ?>"  />

<input type="hidden" name="x_vencimiento_id_<?php echo $x_contador; ?>" value="<?php echo $x_vencimiento_id; ?>"  />
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

		<td align="left"><span>
		<?php 
        if($x_vencimiento_status_id == 1 || $x_vencimiento_status_id == 3 || $x_vencimiento_status_id == 6){
        ?>
        <a href="php_vencimiento_revolventedelete.php?credito_id=<?php echo $x_credito_id; ?>&vencimiento_id=<?php echo $x_vencimiento_id; ?>" target="_self">Eliminar</a>
		<?php 
		}
        ?>
        
        </span>
        </td>
        
		<!-- fecha_vencimiento -->
		<td align="left">
<?php 
if($x_vencimiento_status_id == 1 || $x_vencimiento_status_id == 3 || $x_vencimiento_status_id == 6){
?>
<span>
  <input type="text" size="12" maxlength="12" name="x_fecha_vencimiento_<?php echo $x_contador; ?>" id="x_fecha_vencimiento_<?php echo $x_contador; ?>" value="<?php echo FormatDateTime(@$x_fecha_vencimiento,7); ?>">&nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_vencimiento_<?php echo $x_contador; ?>" alt="Calendario" style="cursor:pointer;cursor:hand;">
  <script type="text/javascript">
Calendar.setup(
{
inputField : "x_fecha_vencimiento_<?php echo $x_contador; ?>", // ID of the input field
ifFormat : "%d/%m/%Y", // the date format
button : "cx_fecha_vencimiento_<?php echo $x_contador; ?>" // ID of the button
}
);
</script>
        </span>
<?php }else{ ?>
<?php echo FormatDateTime(@$x_fecha_vencimiento,7); ?>
<?php } ?>        
</td>
		<!-- importe -->
		<td align="right"><span>
<?php 
if($x_vencimiento_status_id == 1 || $x_vencimiento_status_id == 3 || $x_vencimiento_status_id == 6){
?>
<input type="text" style="text-align:right" name="x_importe_<?php echo $x_contador; ?>" value="<?php echo FormatNumber($x_importe,2,0,0,0); ?>" maxlength="10" size="10" onKeyPress="return solonumeros(this,event,true)" onblur="recalimp()"/>
<?
}else{
	echo (is_numeric($x_importe)) ? FormatNumber($x_importe,2,0,0,0) : $x_importe; 
	echo "<input type='hidden' name='x_importe_$x_contador' value='$x_importe' />";
}
?>

</span></td>
		<!-- interes -->
		<td align="right"><span>
<?php 
if($x_vencimiento_status_id == 1 || $x_vencimiento_status_id == 3 || $x_vencimiento_status_id == 6){
?>
<input type="text" style="text-align:right" name="x_interes_<?php echo $x_contador; ?>" value="<?php echo FormatNumber($x_interes,2,0,0,0); ?>" maxlength="10" size="10" onKeyPress="return solonumeros(this,event,true)" onblur="recalimp()"/>
<?
}else{
	echo (is_numeric($x_interes)) ? FormatNumber($x_interes,2,0,0,0) : $x_interes; 
	echo "<input type='hidden' name='x_interes_$x_contador' value='$x_interes' />";	
}
?>
</span></td>
		<td align="right">
<?php 
if($x_vencimiento_status_id == 1 || $x_vencimiento_status_id == 3 || $x_vencimiento_status_id == 6){
?>
<input type="text" style="text-align:right" name="x_iva_<?php echo $x_contador; ?>" value="<?php echo FormatNumber($x_iva,2,0,0,0); ?>" maxlength="10" size="10" onKeyPress="return solonumeros(this,event,true)" onblur="recalimp()"/>
<?
}else{
	echo (is_numeric($x_iva)) ? FormatNumber($x_iva,2,0,0,0) : $x_iva; 
	echo "<input type='hidden' name='x_iva_$x_contador' value='$x_iva' />";	
}
?>        
        </td>
		<!-- interes_moratorio -->
		<td align="right"><span>
<?php 
if($x_vencimiento_status_id == 1 || $x_vencimiento_status_id == 3 || $x_vencimiento_status_id == 6){
?>
<input type="text" style="text-align:right" name="x_interes_moratorio_<?php echo $x_contador; ?>" value="<?php echo FormatNumber($x_interes_moratorio,2,0,0,0); ?>" maxlength="10" size="10" onKeyPress="return solonumeros(this,event,true)" onblur="recalimp()"/>
<?
}else{
	echo (is_numeric($x_interes_moratorio)) ? FormatNumber($x_interes_moratorio,2,0,0,0) : $x_interes_moratorio; 
	echo "<input type='hidden' name='x_interes_moratorio_$x_contador' value='$x_interes_moratorio' />";		
}
?>
</span></td>
		<td align="right"><?php 
if($x_vencimiento_status_id == 1 || $x_vencimiento_status_id == 3 || $x_vencimiento_status_id == 6){
?>
          <input type="text" style="text-align:right" name="x_iva_mor_<?php echo $x_contador; ?>" value="<?php echo FormatNumber($x_iva_mor,2,0,0,0); ?>" maxlength="10" size="10" onkeypress="return solonumeros(this,event,true)" onblur="recalimp()"/>
        <?
}else{
	echo (is_numeric($x_iva_mor)) ? FormatNumber($x_iva_mor,2,0,0,0) : $x_iva_mor; 
	echo "<input type='hidden' name='x_iva_mor_$x_contador' value='$x_iva_mor' />";	
}
?></td>
		<td align="right"><span>
<?php 
if($x_vencimiento_status_id == 1 || $x_vencimiento_status_id == 3 || $x_vencimiento_status_id == 6){
?>
<input type="text" style="text-align:right" name="x_total_<?php echo $x_contador; ?>" value="<?php echo FormatNumber($x_total,2,0,0,0); ?>" maxlength="10" size="10" onKeyPress="return noenter(this,event)"/>
<?
}else{
	echo (is_numeric($x_total)) ? FormatNumber($x_total,2,0,0,0) : $x_total; 
	echo "<input type='hidden' name='x_total_$x_contador' value='$x_total' />";		
}
?>
</span></td>
	</tr>
<?php
$x_saldo = $x_saldo - $x_importe;
$x_num_venc++;
$x_contador++;
	}
}
?>
<tr>
<td align="right" colspan="4"><span>
<b>Totales:</b>
</span></td>
<td align="right"><span>
<b>
<input style="text-align:right" type="text" name="x_total_pagos" value="
<?php echo FormatNumber($x_total_pagos,2,0,0,1); ?>
" onKeyPress="return noenter(this,event)" size="15" />
</b>
</span></td>
<td align="right"><span>
<b>
<input style="text-align:right" type="text" name="x_total_interes" value="
<?php echo FormatNumber($x_total_interes,2,0,0,0); ?>
" onKeyPress="return noenter(this,event)" size="15"/>
</b>
</span></td>
<td align="right">
<b>
<input style="text-align:right" type="text" name="x_total_iva" value="
<?php echo FormatNumber($x_total_iva,2,0,0,0); ?>
" onKeyPress="return noenter(this,event)" size="15"/>
</b>
</td>
<td align="right"><span>
<b>
<input style="text-align:right" type="text" name="x_total_moratorios" value="
<?php echo FormatNumber($x_total_moratorios,2,0,0,0); ?>
" onKeyPress="return noenter(this,event)" size="15"/>
</b>
</span></td>
<td align="right"><b>
  <input name="x_total_iva_mor" type="text" id="x_total_iva_mor" style="text-align:right" onkeypress="return noenter(this,event)" value="
<?php echo FormatNumber($x_total_iva_mor,2,0,0,0); ?>
" size="15"/>
</b></td>
<td align="right"><span>
<b>
<input style="text-align:right" type="text" name="x_total_total" value="
<?php echo FormatNumber($x_total_total,2,0,0,0); ?>
" onKeyPress="return noenter(this,event)" size="15"/>
</b>
</span></td>
	</tr>
</table>
<?php if ($sExport == "") { ?>
<?php if ($nRecActual > 0) { ?>
<p>
  <?php } ?>
  <?php } ?>
</p>
<input type="hidden" name="x_num_venc" value="<?php echo $x_num_venc; ?>"  />
</form>
<?php } ?>
<?php

// Close recordset and connection
phpmkr_free_result($rs);
phpmkr_db_close($conn);
?>
</body>
</html>
