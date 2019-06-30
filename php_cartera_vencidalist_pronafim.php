<?php session_start(); ?>
<?php ob_start(); ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Cache-Control: private");
header("Pragma: no-cache"); // HTTP/1.0 
$ewCurSec = 0; // Initialise

if (@$_SESSION["php_project_esf_status"] <> "login") {
	header("Location:  login.php");
	exit();
}

$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=carteravenc.xls');
}
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
include_once("utilerias/datefunc.php");


$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];
$currdate_fc = $currentdate["mon"]."/".$currentdate["year"];

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$x_fondeo_credito_id = $_POST["x_fondeo_credito_id"];

if(!empty($x_fondeo_credito_id)){
//	$x_fecha_corte = $_POST["x_fecha_corte"];
	$x_fecha_corte = $currdate;	
	$temptime = strtotime(ConvertDateToMysqlFormat($currdate));	
	$x_mes_actual = strftime('%m',$temptime);
	$x_year_actual = strftime('%Y',$temptime);
}

?>
<?php include ("header.php") ?>
<?php if ($sExport == "") { ?>
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/skins/aqua/theme.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-sp.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<script type="text/javascript" src="ew.js"></script>
<script src="lineafondeohint.js"></script>
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

	if(!EW_this.x_fondeo_credito_id){
		alert("Debe de indicar la linea de credito de un fondo especifico");
		validada = false;		
	}

	if (validada && EW_this.x_fondeo_credito_id && !EW_hasValue(EW_this.x_fondeo_credito_id, "SELECT" )) {
		if (!EW_onError(EW_this, EW_this.x_fondeo_credito_id, "SELECT", "Indique la linea de credito."))
			validada = false;
	}
/*
	if (validada && EW_this.x_fecha_corte && !EW_hasValue(EW_this.x_fecha_corte, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_fecha_corte, "TEXT", "La fecha de corte es requerida."))
			validada = false;
	}

	if (validada && EW_this.x_fecha_corte && !EW_checkeurodate(EW_this.x_fecha_corte.value)) {
		if (!EW_onError(EW_this, EW_this.x_fecha_corte, "TEXT", "La fecha de corte es incorrecta."))
			validada = false;
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
<p><span class="phpmaker">
Reporte Pronafim - ANEXO C<br /><br />
<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="php_cartera_vencidalist_pronafim.php?export=excel&x_dias_ini=<?php echo $x_dias_ini; ?>&x_dias_fin=<?php echo $x_dias_fin; ?>">Exportar a Excel</a>
<?php } ?>
</span></p>

<?php if ($sExport == "") { ?>
<form action="php_cartera_vencidalist_pronafim.php" name="filtro" id="filtro" method="post">
<table width="886" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="125">&nbsp;</td>
    <td width="11">&nbsp;</td>
    <td width="147">&nbsp;</td>
    <td width="11">&nbsp;</td>
    <td width="128">&nbsp;</td>
    <td width="11">&nbsp;</td>
    <td width="147">&nbsp;</td>
    <td width="10">&nbsp;</td>
    <td width="116">&nbsp;</td>
    <td width="11">&nbsp;</td>
    <td width="169">&nbsp;</td>
  </tr>
  <tr>
    <td class="phpmaker">Fondo:</td>
    <td>&nbsp;</td>
    <td><span class="phpmaker">
      <?php
$x_medio_pago_idList = "<select  name=\"x_empresa_id\" onchange=\"cargalineas(this,'txtlineas',0)\">";
$x_medio_pago_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT fondeo_empresa.fondeo_empresa_id, fondeo_empresa.nombre FROM fondeo_empresa order by fondeo_empresa.fondeo_empresa_id ";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_medio_pago_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["fondeo_empresa_id"] == $_SESSION["x_empresa_id"]) {
			$x_medio_pago_idList .= "' selected";
		}

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
?>
    </span></td>
    <td>&nbsp;</td>
    <td colspan="3">
<div id="txtlineas" style=" float: left;">

</div>    
    </td>
    <td>&nbsp;</td>
    <td colspan="3">

    </td>
    </tr>
  <tr>
    <td class="phpmaker">Fecha de Corte:</td>
    <td>&nbsp;</td>
    <td>
		<span>
        
        <?php echo FormatDateTime($currdate,7); ?>
        
        
	    </span>		    
    </td>
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
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><span class="phpmaker">
      <input type="button" name="Submit" value="Generar Reporte" onclick="filtrar()" />
    </span></td>
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
    <td>&nbsp;</td>
  </tr>
</table>
</form>
<?php } ?>



<?php
if(!empty($x_fondeo_credito_id)){

//credito fondos propios
$sSql = "
select fondeo_credito_id from fondeo_credito where fondeo_empresa_id = (select fondeo_empresa_id from fondeo_empresa order by fondeo_empresa_id limit 1)
";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
$row = @phpmkr_fetch_array($rs);
$x_fondeo_credito_id_propios = $row["fondeo_credito_id"];
phpmkr_free_result($rs);


//------------------ SALDOS FIN DE MES

//VENCIDOS PRONAFIM
$sSql = "
select sum(vencimiento.importe) as importe_venc from vencimiento join credito on credito.credito_id = vencimiento.credito_id join fondeo_colocacion on fondeo_colocacion.credito_id = credito.credito_id where credito.credito_status_id = 1 
and fondeo_colocacion.fondeo_credito_id = $x_fondeo_credito_id and vencimiento.vencimiento_status_id = 3 

and 

((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) > 30)


";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
$row = @phpmkr_fetch_array($rs);
$x_saldos_pronafim_ven = $row["importe_venc"];
phpmkr_free_result($rs);

//VENCIDOS PROPIOS
$sSql = "
select sum(vencimiento.importe) as importe_venc from vencimiento join credito on credito.credito_id = vencimiento.credito_id join fondeo_colocacion on fondeo_colocacion.credito_id = credito.credito_id where credito.credito_status_id = 1 
and fondeo_colocacion.fondeo_credito_id = $x_fondeo_credito_id_propios and vencimiento.vencimiento_status_id = 3

and 

((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) > 30)


";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
$row = @phpmkr_fetch_array($rs);
$x_saldos_propios_ven = $row["importe_venc"];
phpmkr_free_result($rs);

//VIGENTES PRONAFIM
$sSql = "
select sum(vencimiento.importe) as importe_vig from vencimiento join credito on credito.credito_id = vencimiento.credito_id join fondeo_colocacion on fondeo_colocacion.credito_id = credito.credito_id where credito.credito_status_id = 1 
and fondeo_colocacion.fondeo_credito_id = $x_fondeo_credito_id and 

(vencimiento.vencimiento_status_id = 1 or 

(
vencimiento.vencimiento_status_id = 3
and 
((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) <= 30)
)

)

";

$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
$row = @phpmkr_fetch_array($rs);
$x_saldos_pronafim_vig = $row["importe_vig"];
phpmkr_free_result($rs);

//VIEGENTES PROPIOS
$sSql = "
select sum(vencimiento.importe) as importe_vig from vencimiento join credito on credito.credito_id = vencimiento.credito_id join fondeo_colocacion on fondeo_colocacion.credito_id = credito.credito_id where credito.credito_status_id = 1 
and fondeo_colocacion.fondeo_credito_id = $x_fondeo_credito_id_propios and 


(vencimiento.vencimiento_status_id = 1 or 

(
vencimiento.vencimiento_status_id = 3
and 
((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) <= 30)
)

)

";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
$row = @phpmkr_fetch_array($rs);
$x_saldos_propios_vig = $row["importe_vig"];
phpmkr_free_result($rs);





//------------------ ACTIVOS VENCIDOS VIGENTES

//CREDITOS ACTIVOS PRONAFIM AQUI
$sSql = "
select count(credito.credito_id) as credito_act from credito join fondeo_colocacion on fondeo_colocacion.credito_id = credito.credito_id where credito.credito_status_id = 1 
and fondeo_colocacion.fondeo_credito_id = $x_fondeo_credito_id 
";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
$row = @phpmkr_fetch_array($rs);
$x_credito_act_pronafim = $row["credito_act"];
phpmkr_free_result($rs);

//CREDITOS VENCIDOS PRONAFIM AQUI
$sSql = "
select count(credito.credito_id) as credito_ven from credito join fondeo_colocacion on fondeo_colocacion.credito_id = credito.credito_id where credito.credito_status_id = 1 
and fondeo_colocacion.fondeo_credito_id = $x_fondeo_credito_id and credito.credito_id in (select credito_id from vencimiento where vencimiento_status_id = 3

and 

((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) > 30)

)




";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
$row = @phpmkr_fetch_array($rs);
$x_credito_ven_pronafim = $row["credito_ven"];
phpmkr_free_result($rs);

//CREDITOS VIGENTES PRONAFIM AQUI
$sSql = "
select count(credito.credito_id) as credito_vig from credito join fondeo_colocacion on fondeo_colocacion.credito_id = credito.credito_id where credito.credito_status_id = 1 
and fondeo_colocacion.fondeo_credito_id = $x_fondeo_credito_id and credito.credito_id not in (select credito_id from vencimiento where vencimiento_status_id = 3

and 

((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) <= 30)



)
";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
$row = @phpmkr_fetch_array($rs);
$x_credito_vig_pronafim = $row["credito_vig"];
phpmkr_free_result($rs);



//CREDITOS ACTIVOS PROPIOS AQUI
$sSql = "
select count(credito.credito_id) as credito_act from credito join fondeo_colocacion on fondeo_colocacion.credito_id = credito.credito_id where credito.credito_status_id = 1 
and fondeo_colocacion.fondeo_credito_id = $x_fondeo_credito_id_propios 
";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
$row = @phpmkr_fetch_array($rs);
$x_credito_act_propios = $row["credito_act"];
phpmkr_free_result($rs);

//CREDITOS VENCIDOS PROPIOS AQUI
$sSql = "
select count(credito.credito_id) as credito_ven from credito join fondeo_colocacion on fondeo_colocacion.credito_id = credito.credito_id where credito.credito_status_id = 1 
and fondeo_colocacion.fondeo_credito_id = $x_fondeo_credito_id_propios and credito.credito_id in (select credito_id from vencimiento where vencimiento_status_id = 3

and 

((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) > 30)

)
";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
$row = @phpmkr_fetch_array($rs);
$x_credito_ven_propios = $row["credito_ven"];
phpmkr_free_result($rs);

//CREDITOS VIGENTES PROPIOS AQUI
$sSql = "
select count(credito.credito_id) as credito_vig from credito join fondeo_colocacion on fondeo_colocacion.credito_id = credito.credito_id where credito.credito_status_id = 1 
and fondeo_colocacion.fondeo_credito_id = $x_fondeo_credito_id_propios and credito.credito_id not in (select credito_id from vencimiento where vencimiento_status_id = 3

and 

((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) <= 30)


)
";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
$row = @phpmkr_fetch_array($rs);
$x_credito_vig_propios = $row["credito_vig"];
phpmkr_free_result($rs);







//------------------ MOVIMEINTOS DEL MES
//mov mes pronafim
$sSql = "
select count(credito.credito_id) as credito_mes, sum(credito.importe) as importe from credito join fondeo_colocacion on fondeo_colocacion.credito_id = credito.credito_id where credito.credito_status_id = 1 
and fondeo_colocacion.fondeo_credito_id = $x_fondeo_credito_id and YEAR(credito.fecha_otrogamiento) = $x_year_actual and MONTH(credito.fecha_otrogamiento) = $x_mes_actual ";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
$row = @phpmkr_fetch_array($rs);
$x_credito_mes_pronafim = $row["credito_mes"];
$x_credito_mes_importe_pronafim = $row["importe"];
phpmkr_free_result($rs);

//mov mes pronafim
$sSql = "
select count(credito.credito_id) as credito_mes, sum(credito.importe) as importe from credito join fondeo_colocacion on fondeo_colocacion.credito_id = credito.credito_id where credito.credito_status_id = 1 
and fondeo_colocacion.fondeo_credito_id = $x_fondeo_credito_id_propios and YEAR(credito.fecha_otrogamiento) = $x_year_actual and MONTH(credito.fecha_otrogamiento) = $x_mes_actual ";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
$row = @phpmkr_fetch_array($rs);
$x_credito_mes_propios = $row["credito_mes"];
$x_credito_mes_importe_propios = $row["importe"];
phpmkr_free_result($rs);




$x_rango_ven_0 = 0;
$x_rango_vig_0 = 0;
	
//DESGLOCE CV PRONAFIM
$sSql = "
select credito.credito_id from credito join fondeo_colocacion on fondeo_colocacion.credito_id = credito.credito_id where credito.credito_status_id = 1 and fondeo_colocacion.fondeo_credito_id = $x_fondeo_credito_id order by credito.credito_id
";

$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);

while ($row = @phpmkr_fetch_array($rs)){

	$x_credito_id = $row["credito_id"];

	//saldo vencido
	$x_importe_vencido = 0;
	$sSql = "
select sum(importe) as saldo_vencido from vencimiento where credito_id = $x_credito_id
and vencimiento_status_id = 3
";
	$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
	$rowwrk = @phpmkr_fetch_array($rswrk);
	$x_importe_vencido = $rowwrk["saldo_vencido"];
	phpmkr_free_result($rswrk);	

	//saldo vigente
	$x_importe_vigente = 0;
	$sSql = "
select sum(importe) as saldo_vigente from vencimiento where credito_id = $x_credito_id
and vencimiento_status_id = 1
";
	$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
	$rowwrk = @phpmkr_fetch_array($rswrk);
	$x_importe_vigente = $rowwrk["saldo_vigente"];
	phpmkr_free_result($rswrk);	

	
	//localizar rango que le toca
	$sSql = "
select fecha_vencimiento from vencimiento where credito_id = $x_credito_id
and vencimiento_status_id = 3 order by vencimiento_id limit 1
";
	$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
	$rowwrk = @phpmkr_fetch_array($rswrk);
	$x_fecha_primer_vencido = $rowwrk["fecha_vencimiento"];
	phpmkr_free_result($rswrk);	



	if(!empty($x_fecha_primer_vencido)){
		$datefrom = ConvertDateToMysqlFormat($x_fecha_primer_vencido);		
		$dateto = ConvertDateToMysqlFormat($currdate);
		$x_dias = datediff('d', $datefrom, $dateto);
	}else{
		$x_dias = "X";
	}


	//suma en rango correspondiente
	switch ($x_dias)
	{
		case "X":
			$x_rango_ven_0 = $x_rango_ven_0 + $x_importe_vencido;
			$x_rango_vig_0 = $x_rango_vig_0 + $x_importe_vigente;			
			break;
		case ($x_dias >= 1 && $x_dias <= 7): 
			$x_rango_ven_1 = $x_rango_ven_1 + $x_importe_vencido;
			$x_rango_vig_1 = $x_rango_vig_1 + $x_importe_vigente;			
			break;
		case ($x_dias >= 8 && $x_dias <= 30): 
			$x_rango_ven_2 = $x_rango_ven_2 + $x_importe_vencido;
			$x_rango_vig_2 = $x_rango_vig_2 + $x_importe_vigente;			
			break;
		case ($x_dias >= 31 && $x_dias <= 60): 
			$x_rango_ven_3 = $x_rango_ven_3 + $x_importe_vencido;
			$x_rango_vig_3 = $x_rango_vig_3 + $x_importe_vigente;			
			break;
		case ($x_dias >= 61 && $x_dias <= 90): 
			$x_rango_ven_4 = $x_rango_ven_4 + $x_importe_vencido;
			$x_rango_vig_4 = $x_rango_vig_4 + $x_importe_vigente;			
			break;
		case ($x_dias >= 91 && $x_dias <= 120): 
			$x_rango_ven_5 = $x_rango_ven_5 + $x_importe_vencido;
			$x_rango_vig_5 = $x_rango_vig_5 + $x_importe_vigente;			
			break;
		case ($x_dias >= 121): 
			$x_rango_ven_6 = $x_rango_ven_6 + $x_importe_vencido;
			$x_rango_vig_6 = $x_rango_vig_6 + $x_importe_vigente;			
			break;
	}
}






$x_rango_ven_0_p = 0;
$x_rango_vig_0_p = 0;
	
//DESGLOCE CV PROPIOS
$sSql = "
select credito.credito_id from credito join fondeo_colocacion on fondeo_colocacion.credito_id = credito.credito_id where credito.credito_status_id = 1 and fondeo_colocacion.fondeo_credito_id = $x_fondeo_credito_id_propios order by credito.credito_id
";

$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);

while ($row = @phpmkr_fetch_array($rs)){

	$x_credito_id = $row["credito_id"];

	//saldo vencido
	$x_importe_vencido = 0;
	$sSql = "
select sum(importe) as saldo_vencido from vencimiento where credito_id = $x_credito_id
and vencimiento_status_id = 3
";
	$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
	$rowwrk = @phpmkr_fetch_array($rswrk);
	$x_importe_vencido = $rowwrk["saldo_vencido"];
	phpmkr_free_result($rswrk);	

	//saldo vigente
	$x_importe_vigente = 0;
	$sSql = "
select sum(importe) as saldo_vigente from vencimiento where credito_id = $x_credito_id
and vencimiento_status_id = 1
";
	$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
	$rowwrk = @phpmkr_fetch_array($rswrk);
	$x_importe_vigente = $rowwrk["saldo_vigente"];
	phpmkr_free_result($rswrk);	

	
	//localizar rango que le toca
	$sSql = "
select fecha_vencimiento from vencimiento where credito_id = $x_credito_id
and vencimiento_status_id = 3 order by vencimiento_id limit 1
";
	$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
	$rowwrk = @phpmkr_fetch_array($rswrk);
	$x_fecha_primer_vencido = $rowwrk["fecha_vencimiento"];
	phpmkr_free_result($rswrk);	


	if(!empty($x_fecha_primer_vencido)){
		$datefrom = ConvertDateToMysqlFormat($x_fecha_primer_vencido);		
		$dateto = ConvertDateToMysqlFormat($currdate);
		$x_dias = datediff('d', $datefrom, $dateto);
	}else{
		$x_dias = "X";	
	}

	//suma en rango correspondiente
	switch ($x_dias)
	{
		case "X":
			$x_rango_ven_0_p = $x_rango_ven_0_p + $x_importe_vencido;
			$x_rango_vig_0_p = $x_rango_vig_0_p + $x_importe_vigente;			
			break;
		case ($x_dias >= 1 && $x_dias <= 7): 
			$x_rango_ven_1_p = $x_rango_ven_1_p + $x_importe_vencido;
			$x_rango_vig_1_p = $x_rango_vig_1_p + $x_importe_vigente;			
			break;
		case ($x_dias >= 8 && $x_dias <= 30): 
			$x_rango_ven_2_p = $x_rango_ven_2_p + $x_importe_vencido;
			$x_rango_vig_2_p = $x_rango_vig_2_p + $x_importe_vigente;			
			break;
		case ($x_dias >= 31 && $x_dias <= 60): 
			$x_rango_ven_3_p = $x_rango_ven_3_p + $x_importe_vencido;
			$x_rango_vig_3_p = $x_rango_vig_3_p + $x_importe_vigente;			
			break;
		case ($x_dias >= 61 && $x_dias <= 90): 
			$x_rango_ven_4_p = $x_rango_ven_4_p + $x_importe_vencido;
			$x_rango_vig_4_p = $x_rango_vig_4_p + $x_importe_vigente;			
			break;
		case ($x_dias >= 91 && $x_dias <= 120): 
			$x_rango_ven_5_p = $x_rango_ven_5_p + $x_importe_vencido;
			$x_rango_vig_5_p = $x_rango_vig_5_p + $x_importe_vigente;			
			break;
		case ($x_dias >= 121): 
			$x_rango_ven_6_p = $x_rango_ven_6_p + $x_importe_vencido;
			$x_rango_vig_6_p = $x_rango_vig_6_p + $x_importe_vigente;			
			break;
	}
}

?>
<strong>SALDOS DE CARTERA Y CREDITOS</strong>
<table width="629">
  <!-- Table header -->
  <tr>
	  <td width="189" valign="top" class="ewTableHeaderThin">Saldos al final del mes</td>
	  <td width="251" align="center" valign="top" class="ewTableHeaderThin">Indiviual</td>
	  <td width="173" align="center" valign="top" class="ewTableHeaderThin">Grupal</td>
	  </tr>
	<tr>
	  <td valign="top" class="ewTableHeaderThin">Vencidos</td>
		<td align="center" valign="top" class="ewTableAltRow"><?php echo FormatNumber($x_saldos_pronafim_ven,2,0,0,1); ?></td>		
		<td align="center" valign="top" class="ewTableAltRow"><?php echo FormatNumber($x_saldo_vencido_30_gpo,2,0,0,1); ?></td>		
	</tr>
	<tr>
	  <td valign="top" class="ewTableHeaderThin">Vigentes</td>
		<td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_saldos_pronafim_vig,2,0,0,1); ?></span></td>		
		<td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_saldo_vigente_gpo,2,0,0,1); ?></span></td>		
	</tr>
	<tr>
	  <td valign="top" class="ewTableHeaderThin">Vencidos otros recursos</td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_saldos_propios_ven,2,0,0,1); ?></span></td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($o_saldo_vencido_30_gpo,2,0,0,1); ?></span></td>
	  </tr>
	<tr>
	  <td valign="top" class="ewTableHeaderThin">Vigentes otros recurso</td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_saldos_propios_vig,2,0,0,1); ?></span></td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($o_saldo_vigente_gpo,2,0,0,1); ?></span></td>
	  </tr>
    
</table>
<br />
<br />
<br />

<table width="629">
	<!-- Table header -->
	<tr>
	  <td width="189" valign="top" class="ewTableHeaderThin">Creditos</td>
	  <td width="251" align="center" valign="top" class="ewTableHeaderThin">Indiviual</td>
	  <td width="173" align="center" valign="top" class="ewTableHeaderThin">Grupal</td>
	  </tr>
	<tr>
	  <td valign="top" class="ewTableHeaderThin">Activos</td>
		<td align="center" valign="top" class="ewTableAltRow"><?php echo FormatNumber($x_credito_act_pronafim,0,0,0,1); ?></td>		
		<td align="center" valign="top" class="ewTableAltRow"><?php echo FormatNumber($x_activos_gpo,0,0,0,1); ?></td>		
	</tr>
	<tr>
	  <td valign="top" class="ewTableHeaderThin">Vencidos</td>
		<td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_credito_ven_pronafim,0,0,0,1); ?></span></td>		
		<td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_creditos_vencido_30_gpo,0,0,0,1); ?></span></td>		
	</tr>
	<tr>
	  <td valign="top" class="ewTableHeaderThin">Vigentes</td>
	  <td align="center" valign="top">
      <?php echo FormatNumber($x_credito_vig_pronafim,0,0,0,1); ?>      
      </td>
	  <td align="center" valign="top">
      <?php echo FormatNumber($x_creditos_vigente_gpo,0,0,0,1); ?>      
      </td>
	  </tr>
	<tr>
	  <td valign="top" class="ewTableHeaderThin">Activos otros recursos</td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_credito_act_propios,0,0,0,1); ?></span></td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($o_activos_gpo,0,0,0,1); ?></span></td>
	  </tr>
	<tr>
	  <td valign="top" class="ewTableHeaderThin">Vencidos otros recursos</td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_credito_ven_propios,0,0,0,1); ?></span></td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($o_creditos_vencido_30_gpo,0,0,0,1); ?></span></td>
	  </tr>
	<tr>
	  <td valign="top" class="ewTableHeaderThin">Vigentes otros recursos</td>
	  <td align="center" valign="top"><?php echo FormatNumber($x_credito_vig_propios,0,0,0,1); ?></td>
	  <td align="center" valign="top"><?php echo FormatNumber($o_creditos_vigente_gpo,0,0,0,1); ?></td>
	  </tr>
    
</table>

<br />
<br />
<br />
<strong>MOVIMIENTOS DEL MES</strong>
<table width="629">
  <!-- Table header -->
  <tr>
	  <td width="189" valign="top" class="ewTableHeaderThin">Creditos y montos colocados</td>
	  <td width="251" align="center" valign="top" class="ewTableHeaderThin">Indiviual</td>
	  <td width="173" align="center" valign="top" class="ewTableHeaderThin">Grupal</td>
	  </tr>
	<tr>
	  <td height="37" valign="top" class="ewTableHeaderThin">Total</td>
		<td align="center" valign="top" class="ewTableAltRow"><?php echo FormatNumber($x_credito_mes_pronafim,0,0,0,1); ?></td>		
		<td align="center" valign="top" class="ewTableAltRow"><?php echo FormatNumber($x_creditos_colocados_mes_gpo,0,0,0,1); ?></td>		
	</tr>
	<tr>
	  <td valign="top" class="ewTableHeaderThin">Monto</td>
		<td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_credito_mes_importe_pronafim,2,0,0,1); ?></span></td>		
		<td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_importe_colocado_mes_gpo,2,0,0,1); ?></span></td>		
	</tr>
	<tr>
	  <td valign="top" class="ewTableHeaderThin">Total OR</td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_credito_mes_propios,0,0,0,1); ?></span></td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($o_creditos_colocados_mes_gpo,0,0,0,1); ?></span></td>
	  </tr>
	<tr>
	  <td valign="top" class="ewTableHeaderThin">Monto OR</td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_credito_mes_importe_propios,2,0,0,1); ?></span></td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($o_importe_colocado_mes_gpo,2,0,0,1); ?></span></td>
	  </tr>
    
</table>
<br />
<br />
<strong><br />
CARTERA EN RIESGO</strong>
<table width="623">
  <!-- Table header -->
  <tr>
	  <td width="80" valign="top" class="ewTableHeaderThin">Dias / Saldos</td>
	  <td width="122" align="center" valign="top" class="ewTableHeaderThin">Vencidos</td>
	  <td width="125" align="center" valign="top" class="ewTableHeaderThin">Vigentes</td>
	  <td width="127" align="center" valign="top" class="ewTableHeaderThin">Vencidos OR</td>
	  <td width="145" align="center" valign="top" class="ewTableHeaderThin">Vigentes OR</td>
	  </tr>
	<tr>
	  <td align="center" valign="middle" class="ewTableHeaderThin">0</td>
		<td align="center" valign="top" class="ewTableAltRow"><?php echo FormatNumber($x_rango_ven_0,2,0,0,1); ?></td>
		<td align="center" valign="top" class="ewTableAltRow"><?php echo FormatNumber($x_rango_vig_0,2,0,0,1); ?></td>
		<td align="center" valign="top" class="ewTableAltRow"><?php echo FormatNumber($x_rango_ven_0_p,2,0,0,1); ?></td>		
		<td align="center" valign="top" class="ewTableAltRow"><?php echo FormatNumber($x_rango_vig_0_p,2,0,0,1); ?></td>		
	</tr>
	<tr>
	  <td align="center" valign="middle" class="ewTableHeaderThin">1 a 7</td>
		<td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_ven_1,2,0,0,1); ?></span></td>
		<td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_vig_1,2,0,0,1); ?></span></td>
		<td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_ven_1_p,2,0,0,1); ?></span></td>		
		<td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_vig_1_p,2,0,0,1); ?></span></td>		
	</tr>
	<tr>
	  <td align="center" valign="middle" class="ewTableHeaderThin">8 a 30</td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_ven_2,2,0,0,1); ?></span></td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_vig_2,2,0,0,1); ?></span></td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_ven_2_p,2,0,0,1); ?></span></td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_vig_2_p,2,0,0,1); ?></span></td>
	  </tr>
	<tr>
	  <td align="center" valign="middle" class="ewTableHeaderThin">31 a 60</td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_ven_3,2,0,0,1); ?></span></td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_vig_3,2,0,0,1); ?></span></td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_ven_3_p,2,0,0,1); ?></span></td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_vig_3_p,2,0,0,1); ?></span></td>
	  </tr>
	<tr>
	  <td align="center" valign="middle" class="ewTableHeaderThin">61 a 90</td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_ven_4,2,0,0,1); ?></span></td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_vig_4,2,0,0,1); ?></span></td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_ven_4_p,2,0,0,1); ?></span></td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_vig_4_p,2,0,0,1); ?></span></td>
	  </tr>
	<tr>
	  <td align="center" valign="middle" class="ewTableHeaderThin">91 a 120</td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_ven_5,2,0,0,1); ?></span></td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_vig_5,2,0,0,1); ?></span></td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_ven_5_p,2,0,0,1); ?></span></td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_vig_5_p,2,0,0,1); ?></span></td>
	  </tr>
	<tr>
	  <td align="center" valign="middle" class="ewTableHeaderThin">M&aacute;s de 120</td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_ven_6,2,0,0,1); ?></span></td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_vig_6,2,0,0,1); ?></span></td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_ven_6_p,2,0,0,1); ?></span></td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_vig_6_p,2,0,0,1); ?></span></td>
	  </tr>
    
</table>
<?php } ?>



<?php
// Close recordset and connection
phpmkr_free_result($rs);
phpmkr_db_close($conn);
?>
<?php if ($sExport <> "word" && $sExport <> "excel") { ?>
<?php include ("footer.php") ?>
<?php } ?>

