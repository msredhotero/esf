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
<?php include ("utilerias/datefunc.php") ?>
<?php

$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
$currdate = $currentdate["year"]."-".$currentdate["mon"]."/".$currentdate["mday"];	
// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);



$x_nombre_srch = $_POST["x_nombre_srch"];
$x_apepat_srch = $_POST["x_apepat_srch"];
$x_apemat_srch = $_POST["x_apemat_srch"];
$x_crenum_srch = $_POST["x_crenum_srch"];
$x_clinum_srch = $_POST["x_clinum_srch"];
$x_promo_srch = $_POST["x_promo_srch"];
$x_empresa_id = $_POST["x_empresa_id"];
$x_sucursal_srch = $_POST["x_sucursal_srch"];


ResetCmd();

$x_posteo = $x_nombre_srch.$x_apepat_srch.$x_apemat_srch.$x_crenum_srch.$x_clinum_srch.$x_promo_srch.$x_sucursal_srch;

if(!empty($x_nombre_srch)){
	$_SESSION["x_nombre_srch"] = $x_nombre_srch;
}else{
	if(strlen($x_posteo) > 0){
		$_SESSION["x_nombre_srch"] = "";
	}
}
if(!empty($x_apepat_srch)){
	$_SESSION["x_apepat_srch"] = $x_apepat_srch;
}else{
	if(strlen($x_posteo) > 0){
		$_SESSION["x_apepat_srch"] = "";
	}
}
if(!empty($x_apemat_srch)){
	$_SESSION["x_apemat_srch"] = $x_apemat_srch;
}else{
	if(strlen($x_posteo) > 0){
		$_SESSION["x_apemat_srch"] = "";
	}
}
if(!empty($x_crenum_srch)){
	$_SESSION["x_crenum_srch"] = $x_crenum_srch;
}else{
	if(strlen($x_posteo) > 0){
		$_SESSION["x_crenum_srch"] = "";
	}
}
if(!empty($x_clinum_srch)){
	$_SESSION["x_clinum_srch"] = $x_clinum_srch;
}else{
	if(strlen($x_posteo) > 0){
		$_SESSION["x_clinum_srch"] = "";
	}
}
if(!empty($x_promo_srch)){
	if($x_promo_srch < 1000){
		$_SESSION["x_promo_srch"] = $x_promo_srch;
	}else{
		$_SESSION["x_promo_srch"] = "";
	}
}else{
	if(strlen($x_posteo) > 0){
		$_SESSION["x_promo_srch"] = "";
	}
}

// EN clientes
if((!empty($_SESSION["x_nombre_srch"])) || (!empty($_SESSION["x_apepat_srch"])) || (!empty($_SESSION["x_apemat_srch"])) || (!empty($_SESSION["x_clinum_srch"])) || (!empty($_SESSION["x_promo_srch"]))){
	$ssrch = "";
	if(!empty($_SESSION["x_nombre_srch"])){
		$ssrch .= "(cliente.nombre_completo like '%".$_SESSION["x_nombre_srch"]."%') AND ";
	}
	if(!empty($_SESSION["x_apepat_srch"])){
		$ssrch .= "(cliente.apellido_paterno like '%".$_SESSION["x_apepat_srch"]."%') AND ";
	}
	if(!empty($_SESSION["x_apemat_srch"])){
		$ssrch .= "(cliente.apellido_materno like '%".$_SESSION["x_apemat_srch"]."%') AND ";
	}
	if(!empty($_SESSION["x_clinum_srch"])){
		$ssrch .= "(cliente.cliente_num+0 = ".$_SESSION["x_clinum_srch"].") AND ";
	}
	if(!empty($_SESSION["x_promo_srch"])){
		$ssrch .= "(solicitud.promotor_id = ".$_SESSION["x_promo_srch"].") AND ";
	}

	$ssrch = substr($ssrch, 0, strlen($ssrch)-5);
	
	$ssrch_sql = "select solicitud.solicitud_id from solicitud join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id where ".$ssrch;
	$rs_qry = phpmkr_query($ssrch_sql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$nTotalRecs = phpmkr_num_rows($rs_qry);
	if($nTotalRecs >0){
		while ($row_sqry = @phpmkr_fetch_array($rs_qry)) {
			$ssrch_cli .= $row_sqry[0].","; 			
		}
		if(strlen($ssrch_cli) > 0 ){
			$ssrch_cli = " credito.solicitud_id in (".substr($ssrch_cli, 0, strlen($ssrch_cli)-1).") AND ";	
		}else{
			$ssrch_cli = "";
		}
	}else{
		$ssrch_cli = "";
	}
}else{
	$ssrch_cli = "";
}

// En Credito
if(!empty($_SESSION["x_crenum_srch"])){
	$ssrch_cre = "";
	if(!empty($_SESSION["x_crenum_srch"])){
		$ssrch_cre .= "(credito.credito_num+0 = ".$_SESSION["x_crenum_srch"].") AND ";
	}
	if(strlen($ssrch_cre) > 0 ){
		$ssrch_cre = substr($ssrch_cre, 0, strlen($ssrch_cre)-5);	
	}else{
		$ssrch_cre = "";
	}
}else{
	$ssrch_cre = "";
}





if(!empty($x_sucursal_srch)){
	$_SESSION["x_sucursal_srch"] = $x_sucursal_srch;
}else{
	if(strlen($x_posteo) > 0){
		$_SESSION["x_sucursal_srch"] = "";
	}
}




if(!empty($_SESSION["x_sucursal_srch"])){
	if(!empty($_SESSION["x_sucursal_srch"]) && ($_SESSION["x_sucursal_srch"] != "1000")){
		$ssrch_cre .= "(promotor.sucursal_id = ".$_SESSION["x_sucursal_srch"].") AND ";
		$ssrch_cre = substr($ssrch_cre, 0, strlen($ssrch_cre)-5);		
	}
}


if(!empty($x_empresa_id)){
	$_SESSION["x_empresa_id"] = $x_empresa_id;
}else{
	if(strlen($x_posteo) > 0){
		$_SESSION["x_empresa_id"] = "";
	}
}


if(!empty($_SESSION["x_empresa_id"])){
	if(!empty($_SESSION["x_empresa_id"]) && ($_SESSION["x_empresa_id"] != "999999999")){
		$ssrch_cre .= "(credito.credito_id in (select credito_id from  fondeo_colocacion join fondeo_credito on fondeo_credito.fondeo_credito_id = fondeo_colocacion.fondeo_credito_id join fondeo_empresa on fondeo_empresa.fondeo_empresa_id = fondeo_credito.fondeo_empresa_id where fondeo_empresa.fondeo_empresa_id = ".$_SESSION["x_empresa_id"].")) AND ";
		
		if(!empty($_SESSION["x_fondeo_credito_id"])){
			$ssrch_cre .= "(fondeo_credito.fondeo_credito_id = ".$_SESSION["x_fondeo_credito_id"].") AND ";
		}
		
		
		
	}
//	$ssrch_cre = substr($ssrch_cre, 0, strlen($ssrch_cre)-5);		
}


$sWhere = $ssrch_cli.$ssrch_cre;




if(!empty($_POST["x_credito_tipo_id"])){
	$_SESSION["x_credito_tipo_id"] = $_POST["x_credito_tipo_id"];
}else{
	if(empty($_SESSION["x_credito_tipo_id"])){
		$_SESSION["x_credito_tipo_id"] = 1;
	}
}

$sWhere .= " (credito.credito_tipo_id = ".$_SESSION["x_credito_tipo_id"]. ") AND ";


if ($sWhere != ""){
	if (substr($sWhere, -5) == " AND ") {
		$sWhere = " AND ".substr($sWhere, 0, strlen($sWhere)-5);
	}else{
		$sWhere = " AND ".$sWhere;
	}
}

// Build SQL todos los creditos activos y liquidados

$sSql = "select credito_id from credito where (credito.credito_status_id in (1)) AND (credito.credito_id in (select distinct(credito_id) from vencimiento where vencimiento_status_id = 3))  $sWhere ";


//$sSql; // Uncomment to show SQL for debugging
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
<?php
// Set up Record Set
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
?>
<p><span class="phpmaker">
ESTADISTICAS DE CARTERA <br /><br />
<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="php_cartera_estadisticas.php?export=excel">Exportar a Excel</a>
<?php } ?>
</span></p>

<?php if ($sExport == "") { ?>
<form action="php_cartera_estadisticas.php" name="filtro" id="filtro" method="post">
<table width="886" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="125"><span class="phpmaker">Tipo de Credito</span></td>
    <td width="11">&nbsp;</td>
    <td width="147"><input name="x_credito_tipo_id" type="radio" id="x_credito_tipo_id" value="1" <?php if($_SESSION["x_credito_tipo_id"] == 1){ echo "checked='checked'"; }?> onclick="javascript: document.filtro.submit();" />
Personal</td>
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type="radio" name="x_credito_tipo_id" id="x_credito_tipo_id2" value="2"  onclick="javascript: document.filtro.submit();"/ <?php if($_SESSION["x_credito_tipo_id"] == 2){ echo "checked='checked'"; }?> />
Grupo</td>
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
    <td><span class="phpmaker">Nombre</span></td>
    <td>&nbsp;</td>
    <td><span class="phpmaker">
      <input name="x_nombre_srch" type="text" id="x_nombre_srch" value="<?php echo $_SESSION["x_nombre_srch"]; ?>" size="20" />
    </span></td>
    <td>&nbsp;</td>
    <td><span class="phpmaker">Apellido Paterno</span></td>
    <td>&nbsp;</td>
    <td><span class="phpmaker">
      <input name="x_apepat_srch" type="text" id="x_apepat_srch" value="<?php echo $_SESSION["x_apepat_srch"]; ?>" size="20" />
    </span></td>
    <td>&nbsp;</td>
    <td><span class="phpmaker">Apellido Materno </span></td>
    <td>&nbsp;</td>
    <td><span class="phpmaker">&nbsp;&nbsp;
          <input name="x_apemat_srch" type="text" id="x_apemat_srch" value="<?php echo $_SESSION["x_apemat_srch"]; ?>" size="20" />
    </span></td>
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
    <td class="phpmaker">Numero de Credito</td>
    <td>&nbsp;</td>
    <td><span class="phpmaker">
      <input name="x_crenum_srch" type="text" id="x_crenum_srch" value="<?php echo $_SESSION["x_crenum_srch"]; ?>" size="20" />
    </span></td>
    <td>&nbsp;</td>
    <td class="phpmaker">Numero de Cliente </td>
    <td>&nbsp;</td>
    <td><span class="phpmaker">
      <input name="x_clinum_srch" type="text" id="x_clinum_srch" value="<?php echo $_SESSION["x_clinum_srch"]; ?>" size="20" />
    </span></td>
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
    <td class="phpmaker">Sucursal</td>
    <td>&nbsp;</td>
    <td><span class="phpmaker">
      <?php
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
		?>
      </span></td>
    <td>&nbsp;</td>
    <td>Fondo:</td>
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
		
/*
		$sSqlWrk2 = "SELECT sum(importe) as otorgado FROM credito where credito_id in (select credito_id from fondeo_colocacion join fondeo_credito on fondeo_credito.fondeo_credito_id = fondeo_colocacion.fondeo_credito_id where fondeo_credito.fondeo_credito_id = ".$datawrk["fondeo_credito_id"].") and credito.credito_status_id in (1, 3,4,5)";
		$rswrk2 = phpmkr_query($sSqlWrk2,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk2);
		$datawrk2 = phpmkr_fetch_array($rswrk2);
		$x_fondeo_saldo = $datawrk["importe"] - $datawrk2["otorgado"];
		@phpmkr_free_result($rswrk2);
*/		
		
		$x_medio_pago_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["fondeo_empresa_id"] == $_SESSION["x_empresa_id"]) {
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
?>
    </span></td>
    <td>&nbsp;</td>
    <td colspan="3">
<div id="txtlineas" style=" float: left;">

</div>
    </td>
    </tr>
  <tr>
    <td class="phpmaker">&nbsp;</td>
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
    <td class="phpmaker">Promotor</td>
    <td>&nbsp;</td>
    <td><span class="phpmaker">
      <?php
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
				if ($datawrk["promotor_id"] == $_SESSION["x_promo_srch"]) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["nombre_completo"] . "</option>";
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
      <input type="submit" name="Submit" value="Buscar &nbsp;(*)" />
    </span></td>
    <td>&nbsp;</td>
    <td><span class="phpmaker"><a href="php_cartera_estadisticas.php?cmd=reset">Mostrar Todos</a>&nbsp;&nbsp; </span></td>
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

<table id="ewlistmain" class="ewTable">
	<!-- Table header -->
	<tr class="ewTableHeader">
		<td valign="top"><span>
Ver Solicitud
		</span></td>
		<td valign="top"><span>
Ver Cr�dito
		</span></td>
		<td valign="top"><span>        
Credito Num.
</span></td>
		<td valign="top">Fecha de Otorgamiento</td>		
		<td valign="top"><span>        
Fondo
</span></td>		

		<td valign="top"><span>
Promotor
		</span></td>
		<td valign="top"><span>
		  Cliente
		  </span></td>
		<td valign="top">Plazo</td>
		<td valign="top">Forma de pago</td>
		<td valign="top"><span>
		  Total de Capital
		  </span></td>
		<td valign="top"><span>
Total de Intereses
		</span></td>
		<td valign="top"><span>
Total de Moratorios
		</span></td>
		<td valign="top">Total </td>
		<td valign="top">Periocidad de pago Promedio</td>
		<td valign="top">Promedio de Pago</td>
		<td valign="top">Dias de atraso promedio</td>
		<td valign="top">Dias sin pagar</td>
		<td valign="top">Cada cuantos d. paga</td>
		</tr>
<?php
$x_tot_pagos_venc = 0;
$x_max_dias_venc = 0;
$x_ttotal_importe = 0;
$x_ttotal_interes = 0;
$x_ttotal_moratorios = 0;
$x_ttotal_total = 0;

while ($row = @phpmkr_fetch_array($rs)){
	$nRecCount = $nRecCount + 1;
	$nRecActual++;

	// Set row color
	$sItemRowClass = " class=\"ewTableRow\"";

	// Display alternate color for rows
	if ($nRecCount % 2 <> 1) {
		$sItemRowClass = " class=\"ewTableAltRow\"";
	}

	$x_credito_id = $row["credito_id"];


	$sSqlWrk = "select credito.plazo_id, credito.forma_pago_id, credito.fecha_otrogamiento, credito.credito_num, credito.credito_tipo_id, solicitud.solicitud_id from credito join solicitud on solicitud.solicitud_id = credito.solicitud_id where credito.credito_id  = $x_credito_id";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$rowwrk = phpmkr_fetch_array($rswrk);
	$x_credito_num = $rowwrk["credito_num"];	
	$x_credito_tipo_id = $rowwrk["credito_tipo_id"];	
	$x_solicitud_id = $rowwrk["solicitud_id"];	
	$x_plazo_id = $rowwrk["plazo_id"];		
	$x_forma_pago_id = $rowwrk["forma_pago_id"];			
	$x_fecha_otrogamiento = $rowwrk["fecha_otrogamiento"];	
	@phpmkr_free_result($rswrk);


	$sSqlWrk = "select sum(importe) as tot_cap, sum(interes) as tot_int, sum(interes_moratorio) as tot_int_mor, sum(total_venc) as tot_venc  from vencimiento where credito_id  = $x_credito_id";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$rowwrk = phpmkr_fetch_array($rswrk);
	$x_capital = $rowwrk["tot_cap"];	
	$x_interes = $rowwrk["tot_int"];	
	$x_moratorio = $rowwrk["tot_int_mor"];	
	$x_total_credito = $rowwrk["tot_venc"];		
	@phpmkr_free_result($rswrk);

		
	
//dias desde su ultimo pago
/*
	$sSqlWrk = "SELECT TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(recibo.fecha_pago) as dias_up FROM vencimiento join recibo_vencimiento on recibo_vencimiento.vencimiento_id = vencimiento.vencimiento_id join recibo on recibo.recibo_id = recibo_vencimiento.recibo_id where (vencimiento.credito_id = $x_credito_id) AND (vencimiento.vencimiento_status_id = 2) order by recibo.fecha_pago desc limit 1";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$rowwrk = phpmkr_fetch_array($rswrk);
$x_dias_ultimo_pago = $rowwrk["dias_up"];		
@phpmkr_free_result($rswrk);	
*/	

//periocidad  de pago


/*
ASGURAR NO FECHAS DE PAGO DUPLICADAS
SOLO CONSIDERA LA PRIMERA


CONSOLIDAR PAGOS DE MISMA FECHA PARA EL REPORTE DE PAGOS Y LAS ESTADISTICAS PARA OBTENER BIEN LOS PROMEDIOS
*/



	$sSqlWrk = "SELECT distinct(fecha_pago) as fecha_pago, recibo.importe as importe_pago, vencimiento.fecha_vencimiento FROM vencimiento join recibo_vencimiento on recibo_vencimiento.vencimiento_id = vencimiento.vencimiento_id join recibo on recibo.recibo_id = recibo_vencimiento.recibo_id where (vencimiento.credito_id = $x_credito_id) AND (vencimiento.vencimiento_status_id = 2) order by recibo.fecha_pago";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);


$currentdate = getdate(time());
$currdate = $currentdate["year"]."-".$currentdate["mon"]."-".$currentdate["mday"];	

$x_num_pagos = 0;
$x_total_dias_pago = 0;
$x_fecha_ultimo_pago = $x_fecha_otrogamiento;
$x_importe_pago = 0;
$x_dias_atraso = 0;
while($rowwrk = phpmkr_fetch_array($rswrk)){
	$x_fecha_pago = $rowwrk["fecha_pago"];		
	$x_fecha_vencimiento = $rowwrk["fecha_vencimiento"];			
	$x_importe_pago = $x_importe_pago + $rowwrk["importe_pago"];			
	$x_dias_pago = datediff("d", $x_fecha_ultimo_pago, $x_fecha_pago);
//	echo "fecha_pago: $x_fecha_pago fec_ult $x_fecha_ultimo_pago x_dias_pago ".$x_dias_pago."<br>";
	$x_total_dias_pago = $x_total_dias_pago + $x_dias_pago;
	
	if($x_fecha_pago != $x_fecha_vencimiento){
		
		$x_calc_dia = datediff("d", $x_fecha_vencimiento, $x_fecha_pago);	
/*		
		if(intval($x_calc_dia) < 0){
			$x_calc_dia = 0;
		}
*/
//		$x_dias_atraso = $x_dias_atraso + datediff("d", $x_fecha_vencimiento, $x_fecha_pago);		
		$x_dias_atraso = $x_dias_atraso + $x_calc_dia;				
	}
	
	$x_num_pagos++;
	$x_fecha_ultimo_pago = $x_fecha_pago;
}
if(!empty($x_total_dias_pago) && !empty($x_num_pagos)){
	$x_perioricidad_pago = $x_total_dias_pago / $x_num_pagos; 
}else{
	$x_perioricidad_pago = 0;
}

if(!empty($x_importe_pago) && !empty($x_num_pagos)){
	$x_promedio_pago = $x_importe_pago / $x_num_pagos; 
}else{
	$x_promedio_pago = 0;
}
if(!empty($x_dias_atraso) && !empty($x_num_pagos)){
	$x_atraso_promedio = $x_dias_atraso / $x_num_pagos; 
}else{
	$x_atraso_promedio = 0;
}

$x_dias_sin_pagar = datediff("d", $x_fecha_ultimo_pago, $currdate);

@phpmkr_free_result($rswrk);	
//echo "$x_perioricidad_pago<br><br>";

//promedio de pago






/*
	$sSqlWrk = "SELECT TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(recibo.fecha_pago) as dias_up FROM vencimiento join recibo_vencimiento on recibo_vencimiento.vencimiento_id = vencimiento.vencimiento_id join recibo on recibo.recibo_id = recibo_vencimiento.recibo_id where (vencimiento.credito_id = $x_credito_id) AND (vencimiento.vencimiento_status_id = 2) order by recibo.fecha_pago desc limit 1";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$rowwrk = phpmkr_fetch_array($rswrk);
$x_dias_ultimo_pago = $rowwrk["dias_up"];		
@phpmkr_free_result($rswrk);	
*/	

//dias de atraso promedio
/*
	$sSqlWrk = "SELECT TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(recibo.fecha_pago) as dias_up FROM vencimiento join recibo_vencimiento on recibo_vencimiento.vencimiento_id = vencimiento.vencimiento_id join recibo on recibo.recibo_id = recibo_vencimiento.recibo_id where (vencimiento.credito_id = $x_credito_id) AND (vencimiento.vencimiento_status_id = 2) order by recibo.fecha_pago desc limit 1";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$rowwrk = phpmkr_fetch_array($rswrk);
$x_dias_ultimo_pago = $rowwrk["dias_up"];		
@phpmkr_free_result($rswrk);	
*/	

//dias sin pagar
/*
	$sSqlWrk = "SELECT TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(recibo.fecha_pago) as dias_up FROM vencimiento join recibo_vencimiento on recibo_vencimiento.vencimiento_id = vencimiento.vencimiento_id join recibo on recibo.recibo_id = recibo_vencimiento.recibo_id where (vencimiento.credito_id = $x_credito_id) AND (vencimiento.vencimiento_status_id = 2) order by recibo.fecha_pago desc limit 1";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$rowwrk = phpmkr_fetch_array($rswrk);
$x_dias_ultimo_pago = $rowwrk["dias_up"];		
@phpmkr_free_result($rswrk);	
*/	

//cada cuantos dias paga
/*
	$sSqlWrk = "SELECT TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(recibo.fecha_pago) as dias_up FROM vencimiento join recibo_vencimiento on recibo_vencimiento.vencimiento_id = vencimiento.vencimiento_id join recibo on recibo.recibo_id = recibo_vencimiento.recibo_id where (vencimiento.credito_id = $x_credito_id) AND (vencimiento.vencimiento_status_id = 2) order by recibo.fecha_pago desc limit 1";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$rowwrk = phpmkr_fetch_array($rswrk);
$x_dias_ultimo_pago = $rowwrk["dias_up"];		
@phpmkr_free_result($rswrk);	
*/	


?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
		<!-- vencimiento_id -->
		<td align="center"><span>
<?php if($x_credito_tipo_id == 1){ ?>        
<a href="php_solicitudedit.php?solicitud_id=<?php echo $x_solicitud_id; ?>" target="_blank">Ver</a>
<?php }else{ ?>
<a href="php_solicitud_caedit.php?solicitud_id=<?php echo $x_solicitud_id; ?>" target="_blank">Ver</a>
<?php } ?>
</span></td>
		<td align="center"><span>
<?php if($_SESSION["php_project_esf_status_UserRolID"] == 1) { ?>
<a href="php_creditoedit.php?credito_id=<?php echo $x_credito_id; ?>" target="_blank">
<?php }else{ ?>
<a href="php_creditoview.php?credito_id=<?php echo $x_credito_id; ?>" target="_blank">
<?php } ?>
Ver</a>
</span></td>
        
		<td align="right"><span>
<?php echo $x_credito_num; ?>
</span></td>
		<td align="center"><?php echo FormatDateTime($x_fecha_otrogamiento,7); ?></td>

		<td align="left"><span>

<?php 

$sSqlWrk = "select fondeo_empresa.nombre from  fondeo_colocacion join fondeo_credito on fondeo_credito.fondeo_credito_id = fondeo_colocacion.fondeo_credito_id join fondeo_empresa on fondeo_empresa.fondeo_empresa_id = fondeo_credito.fondeo_empresa_id where fondeo_colocacion.credito_id = $x_credito_id ";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	$x_fondo = $rowwrk["nombre"];								
}else{
	$x_fondo = "Propio";										
}
@phpmkr_free_result($rswrk);

echo $x_fondo; 
?>
		</span></td>

		<td align="left"><span>
<?php 
		$sSqlWrk = "SELECT promotor.nombre_completo FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id Where credito.credito_id = $x_credito_id ";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
			$x_promotor = $rowwrk["nombre_completo"];								
		}else{
			$x_promotor = "";										
		}
		@phpmkr_free_result($rswrk);

echo $x_promotor; 

?>
</span></td>
		<td align="left"><span>
		  <?php 
		if($x_credito_tipo_id == 1){
			$sSqlWrk = "SELECT cliente.nombre_completo as cliente_nombre, cliente.apellido_paterno, cliente.apellido_materno FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id Where credito.credito_id = $x_credito_id ";
		}else{
			$sSqlWrk = "SELECT solicitud.grupo_nombre as cliente_nombre FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id Where credito.credito_id = $x_credito_id ";
		}

		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
			$x_cliente = $rowwrk["cliente_nombre"]." ".$rowwrk["apellido_paterno"]." ".$rowwrk["apellido_materno"];								
		}else{
			$x_cliente = "";										
		}
		@phpmkr_free_result($rswrk);
		echo $x_cliente;
?>
</span></td>
		<td align="left">
<?php 
if(!empty($x_plazo_id)){
$sSqlWrk = "select descripcion from plazo where plazo_id = $x_plazo_id ";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	$x_plazo = $rowwrk["descripcion"];								
}else{
	$x_plazo = "";										
}
@phpmkr_free_result($rswrk);

echo $x_plazo; 
}
?>
		</td>
		<td align="left">
<?php 
if(!empty($x_forma_pago_id)){
$sSqlWrk = "select descripcion from forma_pago where forma_pago_id = $x_forma_pago_id ";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	$x_forma_pago = $rowwrk["descripcion"];								
}else{
	$x_forma_pago = "";										
}
@phpmkr_free_result($rswrk);

echo $x_forma_pago; 
}
?>
        
        </td>
		<!-- importe -->
		<td align="right"><span>
<?php echo FormatNumber($x_capital,2,0,0,1) ?>
</span></td>
		<!-- interes -->
		<td align="right"><span>
<?php echo FormatNumber($x_interes,2,0,0,1) ?>        
</span></td>
		<!-- interes_moratorio -->
		<td align="right"><span>   
<?php echo FormatNumber($x_moratorio,2,0,0,1) ?>        
</span></td>
		<td align="right"><?php echo FormatNumber($x_total_credito,2,0,0,1) ?></td>
		<td align="right"><?php echo FormatNumber($x_perioricidad_pago,2,0,0,1) ?></td>
		<td align="right"><?php echo FormatNumber($x_promedio_pago,2,0,0,1) ?></td>
		<td align="right"><?php echo FormatNumber($x_atraso_promedio,2,0,0,1) ?></td>
		<td align="right"><?php 
		echo FormatNumber($x_dias_sin_pagar,0,0,0,1) 
		
		
		?></td>
		<td align="right"><?php echo FormatNumber($x_total_total,2,0,0,1) ?></td>
		</tr>
<?php

	$x_tot_pagos_venc = $x_tot_pagos_venc + $x_contador;
	if($x_max_dias_venc < $x_dias_venc_ant){
		$x_max_dias_venc = $x_dias_venc_ant;
	}
	$x_ttotal_importe = $x_ttotal_importe + $x_total_importe;
	$x_ttotal_interes = $x_ttotal_interes + $x_total_interes;
	$x_ttotal_moratorios = $x_ttotal_moratorios + $x_total_moratorios;
	$x_ttotal_total = $x_ttotal_total + $x_total_total;
}
?>
<tr>
		<td valign="top" colspan="5"><span>
<b>Totales</b>
		</span></td>
		<td valign="top"><span>

		</span></td>		
		<td valign="top"><span>
		  
		  </span></td>
		<td align="right">&nbsp;</td>
		<td align="right">&nbsp;</td>		
		<td align="right"><span>
  <?php echo FormatNumber($x_ttotal_interes,2,0,0,1) ?>        
		  </span></td>
		<td align="right"><span>
<?php echo FormatNumber($x_ttotal_moratorios,2,0,0,1) ?>        
		</span></td>
		<td align="right"><span>
<?php echo FormatNumber($x_ttotal_total,2,0,0,1) ?>                
		</span></td>
		<td align="right"><?php echo FormatNumber($nRecCount,0,0,0,1) ?></td>
		<td align="right"><?php echo FormatNumber($nRecCount,0,0,0,1) ?></td>
		<td align="right"><?php echo FormatNumber($nRecCount,0,0,0,1) ?></td>
		<td align="right"><?php echo FormatNumber($nRecCount,0,0,0,1) ?></td>
		<td align="right"><?php echo FormatNumber($nRecCount,0,0,0,1) ?></td>
		<td align="right"><?php echo FormatNumber($nRecCount,0,0,0,1) ?></td>
	</tr>
</table>
<?php
// Close recordset and connection
phpmkr_free_result($rs);
phpmkr_db_close($conn);
?>
<?php if ($sExport <> "word" && $sExport <> "excel") { ?>
<?php include ("footer.php") ?>
<?php } ?>
<?php

function ResetCmd()
{

	// Get Reset Cmd
	if (strlen(@$_GET["cmd"]) > 0) {
		$sCmd = @$_GET["cmd"];

		// Reset Search Criteria
		if (strtoupper($sCmd) == "RESET") {
			$sSrchWhere = "";

			$_SESSION["x_nombre_srch"] = "";
			$_SESSION["x_apepat_srch"] = "";
			$_SESSION["x_apemat_srch"] = "";
			$_SESSION["x_crenum_srch"] = "";
			$_SESSION["x_clinum_srch"] = "";
			$_SESSION["x_promo_srch"] = "";
			$_SESSION["x_empresa_id"] = "";		
			$_SESSION["x_fondeo_credito_id"] = "";			
			$_SESSION["x_credito_tipo_id"] = "";				

			

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["x_nombre_srch"] = "";
			$_SESSION["x_apepat_srch"] = "";
			$_SESSION["x_apemat_srch"] = "";
			$_SESSION["x_crenum_srch"] = "";
			$_SESSION["x_clinum_srch"] = "";
			$_SESSION["x_promo_srch"] = "";
			$_SESSION["x_empresa_id"] = "";		
			$_SESSION["x_fondeo_credito_id"] = "";			
			$_SESSION["x_credito_tipo_id"] = "";				

		// Reset Sort Criteria
		}

		// Reset Start Position (Reset Command)
	}
}

?>
