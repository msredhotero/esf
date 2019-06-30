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

$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

if($_POST["x_dias_ini"]){
	$_SESSION["x_dias_ini"] = $_POST["x_dias_ini"];
	$_SESSION["x_dias_fin"] = $_POST["x_dias_fin"];		
}else{
	if(empty($_SESSION["x_dias_ini"])){
		$_SESSION["x_dias_ini"] = 1;
		$_SESSION["x_dias_fin"] = 999999999;		
	}
}

$x_nombre_srch = $_POST["x_nombre_srch"];
$x_apepat_srch = $_POST["x_apepat_srch"];
$x_apemat_srch = $_POST["x_apemat_srch"];
$x_crenum_srch = $_POST["x_crenum_srch"];
$x_clinum_srch = $_POST["x_clinum_srch"];
$x_promo_srch = $_POST["x_promo_srch"];

ResetCmd();

$x_posteo = $x_nombre_srch.$x_apepat_srch.$x_apemat_srch.$x_crenum_srch.$x_clinum_srch.$x_promo_srch;

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

$sWhere = $ssrch_cli.$ssrch_cre;
if ($sWhere != ""){
	if (substr($sWhere, -5) == " AND ") {
		$sWhere = " AND ".substr($sWhere, 0, strlen($sWhere)-5);
	}else{
		$sWhere = " AND ".$sWhere;
	}
}

// Build SQL
$sSql = "select vencimiento.credito_id from vencimiento join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id where (credito.credito_status_id = 4) AND (vencimiento.vencimiento_status_id = 3) AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) >= ".$_SESSION["x_dias_ini"].") AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) <= ".$_SESSION["x_dias_fin"].")".$sWhere;

	
$sSql .= " group by vencimiento.credito_id order by credito.credito_num+0";




//echo $sSql; // Uncomment to show SQL for debugging
?>
<?php include ("header.php") ?>
<?php if ($sExport == "") { ?>
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/skins/aqua/theme.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-sp.js"></script>
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

	if (validada && EW_this.x_dias_ini && !EW_hasValue(EW_this.x_dias_ini, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_dias_ini, "TEXT", "Indique el numero de dias de inicio."))
			validada = false;
	}
	if (validada == true && EW_this.x_dias_ini && !EW_checkinteger(EW_this.x_dias_ini.value)) {
		if (!EW_onError(EW_this, EW_this.x_dias_ini, "TEXT", "El numero de dias de inicio es incorrecto, por favor verifiquelo."))
			validada = false;
	}

	if (validada && EW_this.x_dias_fin && !EW_hasValue(EW_this.x_dias_fin, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_dias_fin, "TEXT", "Indique el numero de dias de fin."))
			validada = false;
	}
	if (validada == true && EW_this.x_dias_fin && !EW_checkinteger(EW_this.x_dias_fin.value)) {
		if (!EW_onError(EW_this, EW_this.x_dias_fin, "TEXT", "El numero de dias de fin es incorrecto, por favor verifiquelo."))
			validada = false;
	}


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
CARTERA VENCIDA (de <?php echo $_SESSION["x_dias_ini"]; ?> a <?php echo $_SESSION["x_dias_fin"]; ?> d&iacute;as de vencimiento)
<br /><br />
<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="php_cartera_vencidalist.php?export=excel&x_dias_ini=<?php echo $x_dias_ini; ?>&x_dias_fin=<?php echo $x_dias_fin; ?>">Exportar a Excel</a>
<?php } ?>
</span></p>

<?php if ($sExport == "") { ?>
<form action="php_cartera_vencidalist2.php" name="filtro" id="filtro" method="post">
  <table width="785" border="0" cellpadding="0" cellspacing="0">
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
      <td><span class="phpmaker">Dias Inicio:</span></td>
      <td>&nbsp;</td>
      <td><input name="x_dias_ini" type="text" id="x_dias_ini" onkeypress="return solonumeros(this,event)" value="<?php echo @$_SESSION["x_dias_ini"]; ?>" size="10" maxlength="10" /></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><span class="phpmaker">Dias Fin:</span></td>
      <td>&nbsp;</td>
      <td><input name="x_dias_fin" type="text" id="x_dias_fin" onkeypress="return solonumeros(this,event)" value="<?php echo @$_SESSION["x_dias_fin"]; ?>" size="10" maxlength="10" /></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><span class="phpmaker">
        <input type="submit" name="Submit" value="Buscar &nbsp;(*)" />
      </span></td>
      <td>&nbsp;</td>
      <td><span class="phpmaker"><a href="php_cartera_vencidalist2.php?cmd=reset">Mostrar Todos</a>&nbsp;&nbsp; </span></td>
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
Ver Crédito
		</span></td>
		<td valign="top"><span>        
Credito Num.
</span></td>		
		<td valign="top"><span>
Promotor
		</span></td>
		<td valign="top"><span>
Cliente
		</span></td>
		<td valign="top"><span>
Num. de Pagos Vencidos
		</span></td>		
		<td valign="top"><span>
Max. num de Dias vencido
		</span></td>
		<td valign="top"><span>
Total de Capital
		</span></td>
		<td valign="top"><span>
Total de Intereses
		</span></td>
		<td valign="top"><span>
Total de Moratorios
		</span></td>
		<td valign="top"><span>
Total Vencido
		</span></td>
		<td valign="top"><span>
Comentarios
		</span></td>
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


	$sSqlWrk = "select credito.credito_num, credito.credito_tipo_id, solicitud.solicitud_id from credito join solicitud on solicitud.solicitud_id = credito.solicitud_id where credito.credito_id  = $x_credito_id";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$rowwrk = phpmkr_fetch_array($rswrk);
	$x_credito_num = $rowwrk["credito_num"];	
	$x_credito_tipo_id = $rowwrk["credito_tipo_id"];	
	$x_solicitud_id = $rowwrk["solicitud_id"];	
	@phpmkr_free_result($rswrk);
		
	$sSqlWrk = "SELECT *, TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento) as dias_venc FROM vencimiento where (credito_id = $x_credito_id) AND (vencimiento.vencimiento_status_id = 3) AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) >= ".$_SESSION["x_dias_ini"].") AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) <= ".$_SESSION["x_dias_fin"].")	";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	
	
	$x_total_importe = 0;
	$x_total_interes = 0;
	$x_total_moratorios = 0;
	$x_total_total = 0;
	$x_dias_venc_ant = 0;
	$x_contador = 0;
	
	while($rowwrk = phpmkr_fetch_array($rswrk)) {
		$x_importe = $rowwrk["importe"];
		$x_interes = $rowwrk["interes"];
		$x_interes_moratorio = $rowwrk["interes_moratorio"];
		$x_dias_venc = $rowwrk["dias_venc"];		

		if($x_dias_venc > $x_dias_venc_ant){
			$x_dias_venc_ant = $x_dias_venc;
		}
		$x_total_importe = $x_total_importe + $x_importe;
		$x_total_interes = $x_total_interes + $x_interes;
		$x_total_moratorios = $x_total_moratorios + $x_interes_moratorio;
		$x_total_total = $x_total_total + ($x_importe + $x_interes + $x_interes_moratorio);
		$x_contador++;
	}
	@phpmkr_free_result($rswrk);
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
		<!-- vencimiento_id -->
		<td align="center"><span>
<?php if($x_credito_tipo_id == 1){ ?>        
<a href="php_solicitudview.php?solicitud_id=<?php echo $x_solicitud_id; ?>" target="_blank">Ver</a>
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
		<td align="right"><span>
<?php echo $x_contador; ?>
</span></td>
		<td align="right"><span>
<?php echo $x_dias_venc_ant; ?>
</span></td>
		<!-- importe -->
		<td align="right"><span>
<?php echo FormatNumber($x_total_importe,2,0,0,1) ?>
</span></td>
		<!-- interes -->
		<td align="right"><span>
<?php echo FormatNumber($x_total_interes,2,0,0,1) ?>        
</span></td>
		<!-- interes_moratorio -->
		<td align="right"><span>   
<?php echo FormatNumber($x_total_moratorios,2,0,0,1) ?>        
</span></td>
		<td align="right"><span>
<?php echo FormatNumber($x_total_total,2,0,0,1) ?>                
</span></td>
		<td align="center"><span>   
    <iframe name="comentarios" src="php_comentarios_visor.php?key=<?php echo $x_credito_id;?>" scrolling="no" style="margin-left:0px; width:100px; height:30px; margin-top:-5px" frameborder="0" allowtransparency="true" id="contenido"></iframe>
</span></td>
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
		<td valign="top" colspan="4"><span>
<b>Totales</b>
		</span></td>
		<td valign="top"><span>

		</span></td>		
		<td valign="top"><span>

		</span></td>		
		<td valign="top"><span>

		</span></td>
		<td align="right"><span>
<?php echo FormatNumber($x_ttotal_importe,2,0,0,1) ?>
		</span></td>
		<td align="right"><span>
<?php echo FormatNumber($x_ttotal_interes,2,0,0,1) ?>        
		</span></td>
		<td align="right"><span>
<?php echo FormatNumber($x_ttotal_moratorios,2,0,0,1) ?>        
		</span></td>
		<td align="right"><span>
<?php echo FormatNumber($x_ttotal_total,2,0,0,1) ?>                
		</span></td>
		<td align="right"><span>

		</span></td>
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

			

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["x_nombre_srch"] = "";
			$_SESSION["x_apepat_srch"] = "";
			$_SESSION["x_apemat_srch"] = "";
			$_SESSION["x_crenum_srch"] = "";
			$_SESSION["x_clinum_srch"] = "";
			$_SESSION["x_promo_srch"] = "";

		// Reset Sort Criteria
		}

		// Reset Start Position (Reset Command)
	}
}

?>
