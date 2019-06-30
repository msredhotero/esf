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
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=vencimiento.xls');
}
if ($sExport == "word") {
	header('Content-Type: application/vnd.ms-word');
	header('Content-Disposition: attachment; filename=vencimiento.doc');
}
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

// Handle Reset Command
ResetCmd();

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

if($credito_id < 6431){
//	echo "credito_id <= 6431";
$sDbWhere = "(vencimiento.credito_id = $x_credito_id) AND  vencimiento.vencimiento_num < 10000 )   ";
}else{
	$sDbWhere = "(vencimiento.credito_id = $x_credito_id) AND  vencimiento.vencimiento_num < 10000 )   ";
	
	}
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
*/
	$sSql .= " ORDER BY vencimiento.vencimiento_num ";
/*}*/

#echo $sSql; // Uncomment to show SQL for debugging
?>

<?php


$sqlFP = 	"SELECT forma_pago_id, penalizacion penalizacion, garantia_liquida FROM credito WHERE credito_id =  $x_credito_id";
$rsFP = phpmkr_query($sqlFP, $conn) or die ("Error al selccioanl la forma de pago". phpmkr_error()."sql:".$sqlFP);
$rowFP = phpmkr_fetch_array($rsFP);
$x_forma_pago_id = $rowFP["forma_pago_id"];
$x_penalizacion = $rowFP["penalizacion"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Financiera CRECE</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<style type="text/css">td img {display: block;}</style>
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#FFFFFF">
<script type="text/javascript" src="ew.js"></script>
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
function EW_selectKey(elem) {
	var f = elem.form;	
	if (!f.elements["key_d[]"]) return;
	if (f.elements["key_d[]"][0]) {
		for (var i=0; i<f.elements["key_d[]"].length; i++)
			f.elements["key_d[]"][i].checked = elem.checked;	
	} else {
		f.elements["key_d[]"].checked = elem.checked;	
	}
	if (f.elements["checkall"])
	{
		if (f.elements["checkall"][0])
		{
			for (var i = 0; i<f.elements["checkall"].length; i++)
				f.elements["checkall"][i].checked = elem.checked;
		} else {
			f.elements["checkall"].checked = elem.checked;
		}
	}
	ew_clickall(elem);
}
function EW_selected(elem) {
	var f = elem.form;	
	if (!f.elements["key_d[]"]) return false;
	if (f.elements["key_d[]"][0]) {
		for (var i=0; i<f.elements["key_d[]"].length; i++)
			if (f.elements["key_d[]"][i].checked) return true;
	} else {
		return f.elements["key_d[]"].checked;
	}
	return false;
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
SetUpStartRec(); // Set Up Start Record Position
?>
<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="phpmaker" style="color: Red;"><?php echo $_SESSION["ewmsg"]; ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>
<?php if ($sExport == "") { ?>
<form action="php_vencimientolist.php" name="ewpagerform" id="ewpagerform">
</form>
<?php } ?>
<?php if ($nTotalRecs > 0)  { ?>
<form method="post" name="principal" action="php_vencimientolist.php">
<p>
  <input type="hidden" name="credito_id" value="<?php echo $x_credito_id; ?>"  />
</p>
<p>&nbsp;</p>
<table id="ewlistmain" class="ewTable" align="center">
	<!-- Table header -->
	<tr class="ewTableHeader">
<td><span>
&nbsp;
</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
No
<?php }else{ ?>
	<a href="php_vencimientolist.php?credito_id=<?php echo $x_credito_id; ?>&order=<?php echo urlencode("vencimiento_id"); ?>">No<?php if (@$_SESSION["vencimiento_x_vencimiento_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vencimiento_x_vencimiento_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Status
<?php }else{ ?>
	<a href="php_vencimientolist.php?credito_id=<?php echo $x_credito_id; ?>&order=<?php echo urlencode("vencimiento_status_id"); ?>">Status<?php if (@$_SESSION["vencimiento_x_vencimiento_status_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vencimiento_x_vencimiento_status_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Fecha
<?php }else{ ?>
	<a href="php_vencimientolist.php?credito_id=<?php echo $x_credito_id; ?>&order=<?php echo urlencode("fecha_vencimiento"); ?>">Fecha<?php if (@$_SESSION["vencimiento_x_fecha_vencimiento_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vencimiento_x_fecha_vencimiento_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
   
		<td valign="top"><span>
Fecha de Pago
		</span></td>
        
        <td valign="top"><span>
Fecha Remanente
		</span></td>				        
		<td valign="top"><span>
Ref. de Pago
		</span></td>				                
		<td valign="top"><span>
Capital		
	  </span></td>				
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Importe
<?php }else{ ?>
	<a href="php_vencimientolist.php?credito_id=<?php echo $x_credito_id; ?>&order=<?php echo urlencode("importe"); ?>">Importe<?php if (@$_SESSION["vencimiento_x_importe_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vencimiento_x_importe_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Inter&eacute;s
<?php }else{ ?>
	<a href="php_vencimientolist.php?credito_id=<?php echo $x_credito_id; ?>&order=<?php echo urlencode("interes"); ?>">Inter&eacute;s<?php if (@$_SESSION["vencimiento_x_interes_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vencimiento_x_interes_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top">IVA</td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Moratorio
<?php }else{ ?>
	<a href="php_vencimientolist.php?credito_id=<?php echo $x_credito_id; ?>&order=<?php echo urlencode("interes_moratorio"); ?>">Moratorios<?php if (@$_SESSION["vencimiento_x_interes_moratorio_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vencimiento_x_interes_moratorio_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top">IVA M.</td>
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
// elsaldo debe de ser el total de los vencimientos

// seleccionamos si tiene garantia liquida o no
$sqlGarantiaLiquida = "SELECT garantia_liquida, num_pagos,forma_pago_id FROM credito WHERE credito_id = $x_credito_id ";
$rsGarantiaLiquida = phpmkr_query($sqlGarantiaLiquida,$conn) or die ("Erro al seleccionar la garatia liquida del credito".phpmkr_error()."sql:".$sqlGarantiaLiquida);
$rowGarantiaLiquida = phpmkr_fetch_array($rsGarantiaLiquida);
$x_tiene_garantia_liquida = $rowGarantiaLiquida["garantia_liquida"];
$x_numero_de_pagos = $rowGarantiaLiquida["num_pagos"];
$x_forma_pago = $rowGarantiaLiquida["forma_pago_id"];
$x_numero_1 = 0;
$x_numero_2 = 0;
$x_numero_3 = 0;
if ($x_tiene_garantia_liquida == 1){
	// si tiene garantia liquda
	if($x_forma_pago == 1){
		//semanla
		if($x_numero_de_pagos == 40){
			$x_numero_1 = 40;
			$x_numero_2 = 39;
			$x_numero_3 = 38;
			}else{
				$x_numero_1 = 20;
				$x_numero_2 = 19;
				$x_numero_3 = 0;
				}
		}else if($x_forma_pago == 2){
			//catorcenal
			if($x_numero_de_pagos == 24){
				$x_numero_1 = 24;
				$x_numero_2 = 23;
				$x_numero_3 = 0;				
				}else{
					$x_numero_1 = 12;
					$x_numero_2 = 0;
					$x_numero_3 = 0;					
					}		
			
			}else if($x_forma_pago == 3){
				//mensual
				$x_numero_1 = 12;
				$x_numero_2 = 0;
				$x_numero_3 = 0;
				
				}else if($x_forma_pago == 4){
					//quincenal
					if($x_numero_de_pagos == 24){
				$x_numero_1 = 24;
				$x_numero_2 = 23;
				$x_numero_3 = 0;				
				}else{
					$x_numero_1 = 12;
					$x_numero_2 = 0;
					$x_numero_3 = 0;					
					}
					
					}
	
	}
$x_paga_vencimiento = 0;


$x_total = 0;
$x_total_pagos = 0;
$x_total_interes = 0;
$x_total_iva = 0;
$x_total_iva_mor = 0;
$x_total_moratorios = 0;
$x_total_total = 0;

$x_total_d = 0;
$x_total_pagos_d = 0;
$x_total_interes_d = 0;
$x_total_iva_d = 0;
$x_total_iva_mor_d = 0;
$x_total_moratorios_d = 0;
$x_total_total_d = 0;

$x_total_pagos_v = 0;
$x_total_interes_v = 0;
$x_total_iva_v = 0;
$x_total_iva_mor_v = 0;
$x_total_moratorios_v = 0;
$x_total_total_v = 0;


$x_total_a = 0;
$x_total_pagos_a = 0;
$x_total_interes_a = 0;
$x_total_iva_a = 0;
$x_total_iva_mor_a = 0;
$x_total_moratorios_a = 0;
$x_total_total_a = 0;

$nRecActual = 0;
while (($row = @phpmkr_fetch_array($rs)) && ($nRecCount < $nStopRec)) {
	$nRecCount = $nRecCount + 1;
	if ($nRecCount >= $nStartRec) {
		$nRecActual++;

		// Set row color
		$sItemRowClass = " class=\"ewTableRow\"";
		$sListTrJs = " onmouseover='ew_mouseover(this);' onmouseout='ew_mouseout(this);' onclick='ew_click(this);'";

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
		$x_fecha_remanente = $row["fecha_genera_remanente"];
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
		
		if(($x_vencimiento_status_id == 2) || ($x_vencimiento_status_id == 5)){
		
			$sSqlWrk = "SELECT fecha_pago, referencia_pago_2 FROM recibo join recibo_vencimiento on recibo_vencimiento.recibo_id = recibo.recibo_id where recibo_vencimiento.vencimiento_id = $x_vencimiento_id and recibo.recibo_status_id = 1";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$rowwrk = phpmkr_fetch_array($rswrk);

			$x_fecha_pago = $rowwrk["fecha_pago"];
			$x_referencia_pago2 = $rowwrk["referencia_pago_2"];			

			@phpmkr_free_result($rswrk);

			$x_total_pagos_a = $x_total_pagos_a + $x_importe;
			$x_total_interes_a = $x_total_interes_a + $x_interes;
			$x_total_iva_a = $x_total_iva_a + $x_iva;			
			$x_total_iva_mor_a = $x_total_iva_mor_a + $x_iva_mor;						
			$x_total_moratorios_a = $x_total_moratorios_a + $x_interes_moratorio;
			$x_total_total_a = $x_total_total_a + $x_total;
			

		}else{
			$x_fecha_pago  = "";
			$x_referencia_pago2 = "";						

			$x_total_pagos_d = $x_total_pagos_d + $x_importe;
			$x_total_interes_d = $x_total_interes_d + $x_interes;
			$x_total_iva_d = $x_total_iva_d + $x_iva;			
			$x_total_iva_mor_d = $x_total_iva_mor_d + $x_iva_mor;						
			$x_total_moratorios_d = $x_total_moratorios_d + $x_interes_moratorio;
			$x_total_total_d = $x_total_total_d + $x_total;
			
			if($x_vencimiento_status_id == 3){
				$x_total_pagos_v = $x_total_pagos_v + $x_importe;
				$x_total_interes_v = $x_total_interes_v + $x_interes;
				$x_total_iva_v = $x_total_iva_v + $x_iva;			
				$x_total_iva_mor_v = $x_total_iva_mor_v + $x_iva_mor;						
				$x_total_moratorios_v = $x_total_moratorios_v + $x_interes_moratorio;
				$x_total_total_v = $x_total_total_v + $x_total;
			}
			
		}
		

		
		$x_ref_loc = str_pad($x_vencimiento_id, 5, "0", STR_PAD_LEFT)."/".str_pad($x_vencimiento_num, 2, "0", STR_PAD_LEFT);
		
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>
<!--
<td><span class="phpmaker"><a href="<?php //if ($x_vencimiento_id <> "") {echo "php_reciboadd.php?vencimiento_id=" . urlencode($x_vencimiento_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>" target="_blank">Pagar</a></span></td>
--->
<td><span class="phpmaker">

<?php 

if(($_SESSION["php_project_esf_status_UserRolID"] == 1) || ($_SESSION["php_project_esf_status_UserRolID"] == 4)){
	if($x_vencimiento_status_id == 1 || $x_vencimiento_status_id == 3 || $x_vencimiento_status_id == 6 ){
		if($x_vencimiento_num != $x_numero_1 && $x_vencimiento_num != $x_numero_2  && $x_vencimiento_num != $x_numero_3 ){
		echo "<a href=php_pagoadd.php?credito_id=$x_credito_id&x_refloc=$x_ref_loc target=\"_self\">pagar</a>";
		}else{
			echo "<a  target=\"_self\">pagar</a>";
			}
	}else{
//		echo $x_ref_loc; 	
	}
}else{
//	echo $x_ref_loc; 
}

?>
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
		<td align="center"><span>
<?php echo FormatDateTime($x_fecha_pago,7); ?>
</span></td>
<td align="center"><span>
<?php  if($x_fecha_remanente != "0000-00-00"){ echo FormatDateTime($x_fecha_remanente,7);} ?>
</span></td>
		<td align="center"><span>
<?php echo $x_referencia_pago2; ?>
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
		<td align="right"><span>
<?php echo (is_numeric($x_iva)) ? FormatNumber($x_iva,2,0,0,1) : $x_iva; ?>
</span></td>
		<!-- interes_moratorio -->
		<td align="right"><span>
<?php echo (is_numeric($x_interes_moratorio)) ? FormatNumber($x_interes_moratorio,2,0,0,1) : $x_interes_moratorio; ?>
</span></td>
		<td align="right"><?php echo (is_numeric($x_iva_mor)) ? FormatNumber($x_iva_mor,2,0,0,1) : $x_iva_mor; ?></td>
		<td align="right"><span>
<?php echo (is_numeric($x_total)) ? FormatNumber($x_total,2,0,0,1) : $x_total; ?>
</span></td>
	</tr>
<?php
$x_saldo = $x_saldo - $x_importe;
	}
}
?>
    
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td align="right"><span>
</span></td>
<td><span>
</span></td>
<td align="center"><span>
</span></td>
<td align="center"><span>
</span></td>
<td align="right"><span>
</span></td>
<td align="right"><span>
<b>
<?php echo FormatNumber($x_total_pagos,2,0,0,1); ?></b>
</span></td>
<td align="right"><span>
<b>
<?php echo FormatNumber($x_total_interes,2,0,0,1); ?></b>
</span></td>
<td align="right"><span>
<b>
<?php echo FormatNumber($x_total_iva,2,0,0,1); ?></b>
</span></td>
<td align="right"><span>
<b>
<?php echo FormatNumber($x_total_moratorios,2,0,0,1); ?></b>
</span></td>
<td align="right"><b><?php echo FormatNumber($x_total_iva_mor,2,0,0,1); ?></b></td>
<td align="right"><span>
<b>
<?php echo FormatNumber($x_total_total,2,0,0,1); ?></b>
</span></td>
	</tr>


<tr>
<td colspan="7" align="right">

  <strong>SALDO DEUDOR:</strong></td>
<td align="right"><span>
</span></td>
<td align="right"><span>
<b>
<?php echo FormatNumber($x_total_pagos_d,2,0,0,1); ?></b>
</span></td>
<td align="right"><span>
<b>
<?php echo FormatNumber($x_total_interes_d,2,0,0,1); ?></b>
</span></td>
<td align="right"><span>
<b>
<?php echo FormatNumber($x_total_iva_d,2,0,0,1); ?></b>
</span></td>
<td align="right"><span>
<b>
<?php echo FormatNumber($x_total_moratorios_d,2,0,0,1); ?></b>
</span></td>
<td align="right"><b><?php echo FormatNumber($x_total_iva_mor_d,2,0,0,1); ?></b></td>
<td align="right"><span>
<b>
<?php echo FormatNumber($x_total_total_d,2,0,0,1); ?></b>
</span></td>
	</tr>
<tr>
  <td colspan="7" align="right"><strong>SALDO VENCIDO:</strong></td>
  <td align="right">&nbsp;</td>
  <td align="right"><b><?php echo FormatNumber($x_total_pagos_v,2,0,0,1); ?></b></td>
  <td align="right"><b><?php echo FormatNumber($x_total_interes_v,2,0,0,1); ?></b></td>
  <td align="right"><b><?php echo FormatNumber($x_total_iva_v,2,0,0,1); ?></b></td>
  <td align="right"><b><?php echo FormatNumber($x_total_moratorios_v,2,0,0,1); ?></b></td>
  <td align="right"><b><?php echo FormatNumber($x_total_iva_mor_v,2,0,0,1); ?></b></td>
  <td align="right"><b><?php echo FormatNumber($x_total_total_v,2,0,0,1); ?></b></td>
</tr>
<tr>
  <td colspan="7" align="right"><strong>TOTAL PAGADO:</strong></td>
  <td align="right">&nbsp;</td>
  <td align="right"><b><?php echo FormatNumber($x_total_pagos_a,2,0,0,1); ?></b></td>
  <td align="right"><b><?php echo FormatNumber($x_total_interes_a,2,0,0,1); ?></b></td>
  <td align="right"><b><?php echo FormatNumber($x_total_iva_a,2,0,0,1); ?></b></td>  
  <td align="right"><b><?php echo FormatNumber($x_total_moratorios_a,2,0,0,1); ?></b></td>
  <td align="right"><b><?php echo FormatNumber($x_total_iva_mor_a,2,0,0,1); ?></b></td>
  <td align="right"><b><?php echo FormatNumber($x_total_total_a,2,0,0,1); ?></b></td>
</tr>
</table>
<p>&nbsp;</p>


<br />
<?php if ($sExport == "") { ?>
  <?php if ($nRecActual > 0) { ?>
</p>
<p>
  <?php } ?>
  <?php } ?>
</p>
</form>
<?php } ?>
<?php

// Close recordset and connection
phpmkr_free_result($rs);
phpmkr_db_close($conn);
?>
<?php if ($sExport <> "word" && $sExport <> "excel") { ?>
</body>
</html>
<?php } ?>
<?php

//-------------------------------------------------------------------------------
// Function SetUpSortOrder
// - Set up Sort parameters based on Sort Links clicked
// - Variables setup: sOrderBy, Session("Table_OrderBy"), Session("Table_Field_Sort")

function SetUpSortOrder()
{
	global $sOrderBy;
	global $sDefaultOrderBy;

	// Check for an Order parameter
	if (strlen(@$_GET["order"]) > 0) {
		$sOrder = @$_GET["order"];

		// Field vencimiento_id
		if ($sOrder == "vencimiento_id") {
			$sSortField = "`vencimiento_id`";
			$sLastSort = @$_SESSION["vencimiento_x_vencimiento_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vencimiento_x_vencimiento_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vencimiento_x_vencimiento_id_Sort"] <> "") { @$_SESSION["vencimiento_x_vencimiento_id_Sort"] = ""; }
		}

		// Field credito_id
		if ($sOrder == "credito_id") {
			$sSortField = "`credito_id`";
			$sLastSort = @$_SESSION["vencimiento_x_credito_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vencimiento_x_credito_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vencimiento_x_credito_id_Sort"] <> "") { @$_SESSION["vencimiento_x_credito_id_Sort"] = ""; }
		}

		// Field vencimiento_status_id
		if ($sOrder == "vencimiento_status_id") {
			$sSortField = "`vencimiento_status_id`";
			$sLastSort = @$_SESSION["vencimiento_x_vencimiento_status_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vencimiento_x_vencimiento_status_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vencimiento_x_vencimiento_status_id_Sort"] <> "") { @$_SESSION["vencimiento_x_vencimiento_status_id_Sort"] = ""; }
		}

		// Field fecha_vencimiento
		if ($sOrder == "fecha_vencimiento") {
			$sSortField = "`fecha_vencimiento`";
			$sLastSort = @$_SESSION["vencimiento_x_fecha_vencimiento_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vencimiento_x_fecha_vencimiento_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vencimiento_x_fecha_vencimiento_Sort"] <> "") { @$_SESSION["vencimiento_x_fecha_vencimiento_Sort"] = ""; }
		}

		// Field importe
		if ($sOrder == "importe") {
			$sSortField = "`importe`";
			$sLastSort = @$_SESSION["vencimiento_x_importe_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vencimiento_x_importe_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vencimiento_x_importe_Sort"] <> "") { @$_SESSION["vencimiento_x_importe_Sort"] = ""; }
		}

		// Field interes
		if ($sOrder == "interes") {
			$sSortField = "`interes`";
			$sLastSort = @$_SESSION["vencimiento_x_interes_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vencimiento_x_interes_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vencimiento_x_interes_Sort"] <> "") { @$_SESSION["vencimiento_x_interes_Sort"] = ""; }
		}

		// Field interes_moratorio
		if ($sOrder == "interes_moratorio") {
			$sSortField = "`interes_moratorio`";
			$sLastSort = @$_SESSION["vencimiento_x_interes_moratorio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vencimiento_x_interes_moratorio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vencimiento_x_interes_moratorio_Sort"] <> "") { @$_SESSION["vencimiento_x_interes_moratorio_Sort"] = ""; }
		}
		$_SESSION["vencimiento_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["vencimiento_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["vencimiento_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
			$sSortField = "`vencimiento_id`";
			$sLastSort = @$_SESSION["vencimiento_x_vencimiento_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vencimiento_x_vencimiento_id_Sort"] = $sThisSort;
		
//		$_SESSION["vencimiento_OrderBy"] = $sOrderBy;
	}
}

//-------------------------------------------------------------------------------
// Function SetUpStartRec
//- Set up Starting Record parameters based on Pager Navigation
// - Variables setup: nStartRec

function SetUpStartRec()
{

	// Check for a START parameter
	global $nStartRec;
	global $nDisplayRecs;
	global $nTotalRecs;
	if (strlen(@$_GET["start"]) > 0) {
		$nStartRec = @$_GET["start"];
		$_SESSION["vencimiento_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["vencimiento_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["vencimiento_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["vencimiento_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["vencimiento_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["vencimiento_REC"] = $nStartRec;
		}
	}
}

//-------------------------------------------------------------------------------
// Function ResetCmd
// - Clear list page parameters
// - RESET: reset search parameters
// - RESETALL: reset search & master/detail parameters
// - RESETSORT: reset sort parameters

function ResetCmd()
{

	// Get Reset Cmd
	if (strlen(@$_GET["cmd"]) > 0) {
		$sCmd = @$_GET["cmd"];

		// Reset Search Criteria
		if (strtoupper($sCmd) == "RESET") {
			$sSrchWhere = "";
			$_SESSION["vencimiento_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["vencimiento_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["vencimiento_OrderBy"] = $sOrderBy;
			if (@$_SESSION["vencimiento_x_vencimiento_id_Sort"] <> "") { $_SESSION["vencimiento_x_vencimiento_id_Sort"] = ""; }
			if (@$_SESSION["vencimiento_x_credito_id_Sort"] <> "") { $_SESSION["vencimiento_x_credito_id_Sort"] = ""; }
			if (@$_SESSION["vencimiento_x_vencimiento_status_id_Sort"] <> "") { $_SESSION["vencimiento_x_vencimiento_status_id_Sort"] = ""; }
			if (@$_SESSION["vencimiento_x_fecha_vencimiento_Sort"] <> "") { $_SESSION["vencimiento_x_fecha_vencimiento_Sort"] = ""; }
			if (@$_SESSION["vencimiento_x_importe_Sort"] <> "") { $_SESSION["vencimiento_x_importe_Sort"] = ""; }
			if (@$_SESSION["vencimiento_x_interes_Sort"] <> "") { $_SESSION["vencimiento_x_interes_Sort"] = ""; }
			if (@$_SESSION["vencimiento_x_interes_moratorio_Sort"] <> "") { $_SESSION["vencimiento_x_interes_moratorio_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["vencimiento_REC"] = $nStartRec;
	}
}
?>


