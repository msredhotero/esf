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
	header('Content-Disposition: attachment; filename=cuadro_cartera.xls');
}
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
include_once("utilerias/datefunc.php");
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
$x_gestor_srch = $_POST["x_gestor_srch"];
$x_empresa_id = $_POST["x_empresa_id"];
$x_sucursal_srch = $_POST["x_sucursal_srch"];
$x_cresta_srch = $_POST["x_cresta_srch"];
$x_credito_status = $_POST["x_credito_status"];
if(empty($x_gestor_srch)) {
	$x_gestor_srch = $_GET["x_gestor_srch"];	
}
if(empty($x_promo_srch)) {
	$x_promo_srch = $_GET["x_promo_srch"];	
}
if(empty($x_sucursal_srch)) {
	$x_sucursal_srch = $_GET["x_sucursal_srch"];	
}

if(empty($x_credito_status)) {
	$x_credito_status = $_GET["x_credito_status"];	
}


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
if(!empty($x_cresta_srch)){
	$_SESSION["x_cresta_srch"] = $x_cresta_srch;
}else{
	if(strlen($x_posteo) > 0){
		$_SESSION["x_cresta_srch"] = "";
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
	if((!empty($_SESSION["x_crenum_srch"])) || (!empty($_SESSION["x_cresta_srch"]))){
		$ssrch_cre = "";
		if(!empty($_SESSION["x_crenum_srch"])){
			$ssrch_cre .= "(credito.credito_num+0 = ".$_SESSION["x_crenum_srch"].") AND ";
		}
		if(!empty($_SESSION["x_cresta_srch"]) && ($_SESSION["x_cresta_srch"] != "100")){
			$ssrch_cre .= "(credito.credito_status_id = ".$_SESSION["x_cresta_srch"].") AND ";
		}
		if(strlen($ssrch_cre) > 0 ){
//			$ssrch_cre = substr($ssrch_cre, 0, strlen($ssrch_cre)-5);	
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


#echo "filter gestor".$filter["x_gestor_srch"]."<br>";
if ((!empty($x_gestor_srch))){
	//echo "getor entra";
		#si el filtro de gestor esta lleno entonces se debe incluir solo los credito que son de ese gestor
		if($x_gestor_srch == 18){
			$sSqlGestor = "SELECT credito_id FROM gestor_credito  ";
		}else{
			
			$sSqlGestor = "SELECT credito_id FROM gestor_credito WHERE usuario_id = ".$x_gestor_srch ." ";
			}
		
	
$rsGestor = phpmkr_query($sSqlGestor, $conn) or die ("Error al seleccionar los credito que pertenecen al gestor". phpmkr_error()."sql :".$sSqlGestor);
while ($rowGestor = mysql_fetch_array($rsGestor)){
	$x_listado_creditos_gestor = $x_listado_creditos_gestor.$rowGestor["credito_id"].", ";
	}
	
		$x_listado_creditos_gestor = substr($x_listado_creditos_gestor, 0, strlen($x_listado_creditos_gestor)-2); 		
$sCreW .= "((credito.credito_id in ($x_listado_creditos_gestor)) ) AND ";		
		}

$sWhere = $ssrch_cli.$ssrch_cre.$sCreW;




if(!empty($_POST["x_credito_tipo_id"]) && $_POST["x_credito_tipo_id"] < 1000){
	$_SESSION["x_credito_tipo_id"] = $_POST["x_credito_tipo_id"];
	$sWhere .= " (credito.credito_tipo_id = ".$_SESSION["x_credito_tipo_id"]. ") AND ";	
}

if ($sWhere != ""){
	if (substr($sWhere, -5) == " AND ") {
		$sWhere = " AND ".substr($sWhere, 0, strlen($sWhere)-5);
	}else{
		$sWhere = " AND ".$sWhere;
	}
}

// Build SQL
if($_SESSION["php_project_esf_status_UserRolID"] == 7) {
	$sSql = "select vencimiento.credito_id from vencimiento join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id where (credito.credito_status_id in (1,3)) AND (vencimiento.vencimiento_status_id = 3) AND (solicitud.promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"]. ") AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) >= ".$_SESSION["x_dias_ini"].") AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) <= ".$_SESSION["x_dias_fin"].$sWhere.") group by vencimiento.credito_id order by credito.credito_num+0";
}else{
	if($_SESSION["php_project_esf_status_UserRolID"] == 5) {
		$sSql = "select vencimiento.credito_id from vencimiento join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id where (credito.credito_status_id = 4) AND (vencimiento.vencimiento_status_id = 3) AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) >= ".$_SESSION["x_dias_ini"].") AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) <= ".$_SESSION["x_dias_fin"].$sWhere.")";
	}else{
		$sSql = "select vencimiento.credito_id from vencimiento join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id where (credito.credito_status_id not in (2)) ".$sWhere;
	}
	
	$sSql .= "  group by vencimiento.credito_id  having credito_id < 10 order by credito.credito_num+0";

}


//echo $sSql; // Uncomment to show SQL for debugging
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
CARTERA CAPITAL</span></p>
<p><span class="phpmaker"><br />
  <br />
  <?php if ($sExport == "") { ?>
  
  <?php  if(($_SESSION["php_project_esf_status_UserRolID"] == 1) || ($_SESSION["php_project_esf_status_UserRolID"] == 4)){
?>
  &nbsp;&nbsp;<a href="php_cuadro_cartera.php?export=excel&x_credito_status=<?php echo $x_credito_status; ?>&x_sucursal_srch=<?php echo $_SESSION["x_sucursal_srch"]; ?>&x_promo_srch=<?php echo $_SESSION["x_promo_srch"];?>&x_gestor_srch=<?php echo $x_gestor_srch;?>">Exportar a Excel</a>
  <?php } ?>
    <?php } ?>
</span></p>

<?php if ($sExport == "") { ?>
<form action="php_cuadro_cartera.php" name="filtro" id="filtro" method="post">
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
    <td>&nbsp;Status credito</td>
    <td>&nbsp;</td>
    <td><select name="x_credito_status">
      <option value="1" <?php if($x_credito_status == 1){?> selected="selected" <?php }?>>Activo</option>
      <option value="4" <?php if($x_credito_status == 4){?> selected="selected" <?php }?>>Cobranza Externa</option>
    </select></td>
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
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
			if(!empty($_SESSION["x_sucursal_srch"]) && ($_SESSION["x_sucursal_srch"] != "1000")){
				$sSqlWrk = "SELECT `promotor_id`, `nombre_completo` FROM `promotor`  WHERE promotor.sucursal_id = ".$_SESSION["x_sucursal_srch"]." ";
				}	
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
    <td>Gestor</td>
    <td>&nbsp;</td>
    <td><!--<select name="x_gestor_srch" >
      <option value="0">Seleccionar</option>
      <option value="18" <?php if ($x_gestor_srch == 18){?> selected="selected"<?php }?>>Fernando Sanchez </option>
      <option value="1250"  <?php if ($x_gestor_srch == 1250){?> selected="selected"<?php }?> >Angelica Tabares </option>
      <option value="16"  <?php if ($x_gestor_srch == 16){?> selected="selected"<?php }?> >Monica Flores </option>
      <option value="3615" <?php if ($x_gestor_srch == 3615){?> selected="selected"<?php }?> >Marcela Lopez </option>
      <option value="3065" <?php if ($x_gestor_srch == 3065){?> selected="selected"<?php }?> >Josefina Ochoa </option>
      <option value="4561" <?php if ($x_gestor_srch == 4561 ){?> selected="selected"<?php }?> >Rodrigo Sanchez </option>
      <option value="4812" <?php if ($x_gestor_srch == 4842 ){?> selected="selected"<?php }?> >Victoria Garcia </option>
      <option value="4561" <?php if ($x_gestor_srch ==  4561){?> selected="selected"<?php }?> >Rodrigo Sanchez </option>
    </select>-->
    <select name="x_gestor_srch" >  
    <option value="0">Seleccionar</option>    
      <option value="7184" <?php if ($x_gestor_srch == 7184){?> selected="selected"<?php }?>>Angelica Tabares</option>
      <option value="7182" <?php if ($x_gestor_srch == 7182){?> selected="selected"<?php }?>>Marcela Lopez</option>
      <option value="7183" <?php if ($x_gestor_srch == 7183){?> selected="selected"<?php }?>>Josefina Ochoa</option>
      <option value="7187" <?php if ($x_gestor_srch == 7187){?> selected="selected"<?php }?>>Victoria Garcia</option>      
      <option value="7180" <?php if ($x_gestor_srch == 7180){?> selected="selected"<?php }?>>Rodrigo Sanchez</option>
      <option value="7179" <?php if ($x_gestor_srch == 7179){?> selected="selected"<?php }?>>Mauricio Trejo</option>
      <option value="7181" <?php if ($x_gestor_srch == 7181){?> selected="selected"<?php }?>>Javier Foncerrada</option>
      <option value="7188" <?php if ($x_gestor_srch == 7188){?> selected="selected"<?php }?>>Cesar Olvera</option>
      </select>
    
    
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
      <input type="submit" name="Submit" value="Buscar &nbsp;(*)" />
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

if(empty($x_credito_status)){
	$x_credito_status = 1;
	}
if(!empty($_SESSION["x_sucursal_srch"])){
	if(!empty($_SESSION["x_sucursal_srch"]) && ($_SESSION["x_sucursal_srch"] != "1000")){
		$ssrch_cre .= "(promotor.sucursal_id = ".$_SESSION["x_sucursal_srch"].") AND ";
		$ssrch_cre = substr($ssrch_cre, 0, strlen($ssrch_cre)-5);		
	}
}

$x_rango_ven_0 = 0;
$x_rango_vig_0 = 0;
$sSql = "
select credito.credito_id from credito where credito_status_id in ($x_credito_status) and credito_id not in (15) and credito_id not in(select credito_id from credito where credito_num  in(5955, 6247, 6331, 6458, 6461, 6496, 6503, 6510, 6559, 6577, 6603, 6608, 6632, 6638, 6639, 6661,6185,2710, 6490, 6499, 6500, 6504, 6570, 6585, 6592,6433, 6448, 6488, 6501, 6509, 6593, 6594,6545,6248, 6332, 6484,2518, 3522, 4174, 4378, 5245, 5360, 5480, 5511, 5765, 5902, 5906, 5983, 6007, 6110, 6177, 6251, 6274, 6292, 6302, 6354, 6369,1888, 5819, 6260, 6363, 6493, 6502, 6518, 6534, 6536, 6538, 6551, 6553, 6564, 6572, 6606, 6610, 6627, 6628, 6631, 6641, 6646, 6649, 6660,6359, 6629,6511,6558, 6576,2098, 3978, 4141, 4354, 4485, 4748, 5161, 5481, 6163, 6208, 6245, 6301, 6416, 6432,6426, 6479, 6481, 6513, 6520, 6521, 6533, 6539, 6540, 6546, 6560, 6561, 6562, 6563, 6569, 6575, 6578, 6581, 6582, 6583, 6586, 6590, 6602, 6609, 6618, 6619, 6620, 6621, 6623, 6624, 6625, 6635, 6640, 6642, 6643, 6653, 6658,6588, 6589, 6617, 6654, 6659,6474, 6480, 6486, 6522, 6550, 6552, 6600, 6626, 6633, 6645, 6657,6373, 6399, 6403, 6446, 6587,6543, 6549,6494,1219, 2000, 2309, 2378, 4219, 4556, 4670, 4876, 4878, 5187, 5218, 5223, 5384, 5544, 6275, 6440,5624, 6101, 6380, 6393, 6411, 6412, 6419, 6515, 6531, 6607,6218, 6514, 6526,6052, 6525,6214,6022, 6081, 6148, 6465, 6489, 6495,3718, 4043, 4095, 4183, 4634, 5533, 5557, 5583, 5584, 5592, 5597, 5606, 5639, 5675, 5677, 5759, 5778, 5832, 5852, 5854, 5889, 5927, 5929, 5930, 5934, 5959, 6004, 6028, 6046, 6071, 6130, 6180, 6199,5749, 6031, 6544, 6557, 6591,6547, 6556, 6616,3221,6395, 6450,4051, 4612, 5473, 5668, 5690, 5743, 5810, 5958, 5978, 6080, 6093, 6097, 6107, 6134, 6295,6507, 6519, 6541, 6542, 6548, 6554, 6555, 6565, 6566, 6568, 6573, 6574, 6579, 6580, 6584, 6598, 6599, 6601, 6604, 6605, 6612, 6613, 6614, 6615, 6622, 6634, 6637, 6644, 6648, 6650, 6651, 6652, 6655,6413, 6424, 6527, 6528, 6567, 6630,6400, 6477, 6516, 6530, 6532, 6571, 6595, 6611,6390, 6529,6421, 6431, 6447, 6462, 6508, 6535,6379, 6389, 6396, 6414, 6471) and credito.credito_status_id = 1 ) order by credito.credito_id 
";
#select credito.credito_id from credito where credito_status_id in ($x_credito_status) and credito_id not in (15) and credito.credito_id  in (select credito_id from gestor_credito where usuario_id not in (7206,7180,7181,7182,7183,7184,7186,7187,7188,7203))  order by credito.credito_id 

if(!empty($x_promo_srch) && ($x_promo_srch != 1000) ){
	$sSql = "select credito.credito_id from credito join solicitud on credito.solicitud_id = solicitud.solicitud_id where credito_status_id in ($x_credito_status) and credito_id not in (15) and solicitud.promotor_id = $x_promo_srch  order by credito.credito_id";
	}	
	
if (!empty($x_gestor_srch) && ($x_gestor_srch != 1000)){
	// seleccionamos a todos los promotores de la sucuersal
	
		if ((!empty($x_gestor_srch) && ($x_gestor_srch != 1000)) && (empty($x_promo_srch) || ($x_promo_srch == 1000))){
	$sSql = "select credito.credito_id from credito join gestor_credito on credito.credito_id = gestor_credito.credito_id where credito_status_id in ($x_credito_status) and credito.credito_id not in (15) and gestor_credito.usuario_id = $x_gestor_srch  order by credito.credito_id";
		}
		
		if(!empty($x_promo_srch) && ($x_promo_srch != 1000) ){
		$sSql = "select credito.credito_id from credito join solicitud on credito.solicitud_id = solicitud.solicitud_id join gestor_credito on credito.credito_id = gestor_credito.credito_id where credito_status_id in ($x_credito_status) and credito.credito_id not in (15) and gestor_credito.usuario_id = $x_gestor_srch and solicitud.promotor_id = $x_promo_srch  order by credito.credito_id";	
			
		}
		
	}	
if (!empty($x_sucursal_srch) && ($x_sucursal_srch < 1000)){
	//echo ".................. nn..............";
	$list_promotores= "";
	// seleccionamos a todos los promotores de la sucuersal
	$SqlPromotor = "SELECT * FROM promotor where sucursal_id = $x_sucursal_srch "; 
	$rsPromotor =  phpmkr_query($SqlPromotor,$conn)or die("Error al seleccionar todos los promotores de la suc".phpmkr_error()."sql;".$SqlPromotor);
	while($rowPro = phpmkr_fetch_array($rsPromotor)){
		$list_promotores = $list_promotores.$rowPro["promotor_id"].", ";
		}
		
	$list_promotores = trim($list_promotores,", ");
		
	$sSql = "select credito.credito_id from credito join solicitud on credito.solicitud_id = solicitud.solicitud_id where credito_status_id in ($x_credito_status) and credito_id not in (15) and solicitud.promotor_id in ($list_promotores)  order by credito.credito_id";
	}	
	
//DESGLOCE CV PRONAFIM
//$sSql = "select credito.credito_id from credito where credito_status_id in (1) and credito_id not in (15)  order by credito.credito_id ";
echo "<br><br><br>".$sSql."<br>";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);

while ($row = @phpmkr_fetch_array($rs)){

	$x_credito_id = $row["credito_id"];
	
	$sqlNOC = "SELECT credito_num FROM credito WHERE credito_id = $x_credito_id ";
	$rsNOC = phpmkr_query($sqlNOC,$conn)or die("Error al seleccionar le numero de credito".phpmkr_error()."sql:".$sqlNOC);
	$rowNOC = phpmkr_fetch_array($rsNOC);
	$x_credito_num = $rowNOC["credito_num"];

	//saldo vencido
	$x_importe_vencido = 0;
	$sSql = "
select sum(importe) as saldo_vencido from vencimiento where credito_id = $x_credito_id
and vencimiento_status_id in (3)
";

	$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
	$rowwrk = @phpmkr_fetch_array($rswrk);
	$x_importe_vencido = $rowwrk["saldo_vencido"];
	phpmkr_free_result($rswrk);	
	if($x_credito_num == 1015 || $x_credito_num  == 2433){
		echo "crdito_ num ".$x_credito_num." vencido ".$x_importe_vencido."<br>";
		}

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
if($x_credito_num == 1015 || $x_credito_num  == 2433){
		echo "crdito_ num ".$x_credito_num." vigente ".$x_importe_vigente."<br>";
		}
$x_lisc_1 = 	$x_lisc_1.$x_credito_id.", ";
	//localizar rango que le toca
	$sSql = "select fecha_vencimiento from vencimiento where credito_id = $x_credito_id and vencimiento_status_id = 3 order by vencimiento_num, vencimiento_id limit 1
";
//echo "sql fecha".$sSql;

	$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
	$rowwrk = @phpmkr_fetch_array($rswrk);
	$x_fecha_primer_vencido = $rowwrk["fecha_vencimiento"];
	phpmkr_free_result($rswrk);	
//echo "fecha primer vencido".$x_fecha_primer_vencido;

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
			$x_numero_credito_vig_0	= 	$x_numero_credito_vig_0+1;		
			$x_numero_credito_vig_0_lis	= 	$x_numero_credito_vig_0_lis.$x_credito_num.", ";	
			break;
		case ($x_dias >= 1 && $x_dias <= 7): 
			$x_rango_ven_1 = $x_rango_ven_1 + $x_importe_vencido;
			$x_rango_vig_1 = $x_rango_vig_1 + $x_importe_vigente;
			$x_numero_credito_vig_1	= 	$x_numero_credito_vig_1+1;	
			$x_numero_credito_vig_1_lis	= 	$x_numero_credito_vig_1_lis.$x_credito_num.", ";	
			break;
		case ($x_dias >= 8 && $x_dias <= 30): 
			$x_rango_ven_2 = $x_rango_ven_2 + $x_importe_vencido;
			$x_rango_vig_2 = $x_rango_vig_2 + $x_importe_vigente;
			$x_numero_credito_vig_2	= 	$x_numero_credito_vig_2+1;	
			$x_numero_credito_vig_2_lis	= 	$x_numero_credito_vig_2_lis.$x_credito_num.", ";			
			break;
		case ($x_dias >= 31 && $x_dias <= 60): 
			$x_rango_ven_3 = $x_rango_ven_3 + $x_importe_vencido;
			$x_rango_vig_3 = $x_rango_vig_3 + $x_importe_vigente;	
			$x_numero_credito_vig_3	= 	$x_numero_credito_vig_3+1;
			$x_numero_credito_vig_3_lis	= 	$x_numero_credito_vig_3_lis.$x_credito_num.", ";			
			break;
		case ($x_dias >= 61 && $x_dias <= 90): 
			$x_rango_ven_4 = $x_rango_ven_4 + $x_importe_vencido;
			$x_rango_vig_4 = $x_rango_vig_4 + $x_importe_vigente;	
			$x_numero_credito_vig_4	= 	$x_numero_credito_vig_4+1;	
			$x_numero_credito_vig_4_lis	= 	$x_numero_credito_vig_4_lis.$x_credito_num.", ";		
			break;
		case ($x_dias >= 91 && $x_dias <= 120): 
			$x_rango_ven_5 = $x_rango_ven_5 + $x_importe_vencido;
			$x_rango_vig_5 = $x_rango_vig_5 + $x_importe_vigente;
			$x_numero_credito_vig_5	= 	$x_numero_credito_vig_5+1;
			$x_numero_credito_vig_5_lis	= 	$x_numero_credito_vig_5_lis.$x_credito_num.", ";				
			break;
		case ($x_dias >= 121): 
			$x_rango_ven_6 = $x_rango_ven_6 + $x_importe_vencido;
			$x_rango_vig_6 = $x_rango_vig_6 + $x_importe_vigente;	
			$x_numero_credito_vig_6	= 	$x_numero_credito_vig_6+1;	
			$x_numero_credito_vig_6_lis	= 	$x_numero_credito_vig_6_lis.$x_credito_num.", ";		
			break;
	}
}






$x_rango_ven_0_p = 0;
$x_rango_vig_0_p = 0;





//DESGLOCE CV PROPIOS



$sSql = "
select credito.credito_id from credito where credito_status_id in (1,4) order by credito.credito_id
";

$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);

while ($row = @phpmkr_fetch_array($rs)){

	$x_credito_id = $row["credito_id"];

	//saldo vencido
	$x_importe_vencido = 0;
	$sSql = "select sum(importe) as saldo_vencido from vencimiento where credito_id = $x_credito_id
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
and vencimiento_status_id in (1,6,7,9);
";
	$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
	$rowwrk = @phpmkr_fetch_array($rswrk);
	$x_importe_vigente = $rowwrk["saldo_vigente"];
	phpmkr_free_result($rswrk);	
$x_lisc_2 = 	$x_lisc_2.$x_credito_id.", ";
	
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

$x_total_0 = $x_rango_ven_0 + $x_rango_vig_0;
$x_total_1 = $x_rango_ven_1 + $x_rango_vig_1;
$x_total_2 = $x_rango_ven_2 + $x_rango_vig_2;
$x_total_3 = $x_rango_ven_3 + $x_rango_vig_3;
$x_total_4 = $x_rango_ven_4 + $x_rango_vig_4;
$x_total_5 = $x_rango_ven_5 + $x_rango_vig_5;
$x_total_6 = $x_rango_ven_6 + $x_rango_vig_6;

$x_total_vencido = $x_rango_ven_0 +$x_rango_ven_1+$x_rango_ven_2+$x_rango_ven_3+$x_rango_ven_4+$x_rango_ven_5+$x_rango_ven_6;
$x_total_vigente = $x_rango_vig_0 +$x_rango_vig_1 +$x_rango_vig_2+ $x_rango_vig_3+$x_rango_vig_4+$x_rango_vig_5+$x_rango_vig_6;
$x_total_total = $x_total_vencido + $x_total_vigente;
$x_total_numero_credito = $x_numero_credito_vig_0 +$x_numero_credito_vig_1+$x_numero_credito_vig_2+$x_numero_credito_vig_3+$x_numero_credito_vig_4+$x_numero_credito_vig_5+$x_numero_credito_vig_6;
?>

<br />
<strong><br />
CARTERA EN RIESGO</strong>
<table width="674">
  <!-- Table header -->
  <tr>
	  <td width="146" valign="top" class="ewTableHeaderThin">Dias / Saldos</td>
	  <td width="108" align="center" valign="top" class="ewTableHeaderThin">Vencidos</td>
	  <td width="127" align="center" valign="top" class="ewTableHeaderThin">Vigentes</td>
      <td width="136" align="center" valign="top" class="ewTableHeaderThin">Total</td>
      <td width="123" align="center" valign="top" class="ewTableHeaderThin">N&uacute;mero de cr&eacute;ditos</td>
       </tr>
	<tr>
	  <td align="center" valign="middle" class="ewTableHeaderThin">0</td>
		<td align="center" valign="top" ><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_ven_0,2,0,0,1); ?></span></td>
		<td align="center" valign="top" ><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_vig_0,2,0,0,1); ?></span></td>
        <td width="136" align="center" valign="top"  ><span class="ewTableAltRow"><?php echo FormatNumber($x_total_0,2,0,0,1);?></span></td>
        <td width="123" align="center" valign="top" ><span class="ewTableAltRow"><?php echo $x_numero_credito_vig_0;?></span></td>
      </tr>
	<tr>
	  <td align="center" valign="middle" class="ewTableHeaderThin">1 a 7</td>
		<td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_ven_1,2,0,0,1); ?></span></td>
		<td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_vig_1,2,0,0,1); ?></span></td>
       <td width="136" align="center" valign="top" ><span class="ewTableAltRow"><?php echo FormatNumber($x_total_1,2,0,0,1);?></span></td>
        <td width="123" align="center" valign="top" ><span class="ewTableAltRow"><?php echo $x_numero_credito_vig_1;?></span></td>
        </tr>
	<tr>
	  <td align="center" valign="middle" class="ewTableHeaderThin">8 a 30</td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_ven_2,2,0,0,1); ?></span></td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_vig_2,2,0,0,1); ?></span></td>
      <td width="136" align="center" valign="top" ><span class="ewTableAltRow"><?php echo FormatNumber($x_total_2,2,0,0,1);?></span></td>
        <td width="123" align="center" valign="top" ><span class="ewTableAltRow"><?php echo $x_numero_credito_vig_2;?></span></td>
        </tr>
	<tr>
	  <td align="center" valign="middle" class="ewTableHeaderThin">31 a 60</td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_ven_3,2,0,0,1); ?></span></td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_vig_3,2,0,0,1); ?></span></td>
      <td width="136" align="center" valign="top" ><span class="ewTableAltRow"><?php echo FormatNumber($x_total_3,2,0,0,1);?></span></td>
        <td width="123" align="center" valign="top" ><span class="ewTableAltRow"><?php echo $x_numero_credito_vig_3;?></span></td>
        </tr>
	<tr>
	  <td align="center" valign="middle" class="ewTableHeaderThin">61 a 90</td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_ven_4,2,0,0,1); ?></span></td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_vig_4,2,0,0,1); ?></span></td>
      <td width="136" align="center" valign="top" ><span class="ewTableAltRow"><?php echo FormatNumber($x_total_4,2,0,0,1);?></span></td>
        <td width="123" align="center" valign="top" ><span class="ewTableAltRow"><?php echo $x_numero_credito_vig_4;?></span></td>
        </tr>
	<tr>
	  <td align="center" valign="middle" class="ewTableHeaderThin">91 a 120</td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_ven_5,2,0,0,1); ?></span></td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_vig_5,2,0,0,1); ?></span></td>
      <td width="136" align="center" valign="top" ><span class="ewTableAltRow"><?php echo FormatNumber($x_total_5,2,0,0,1);?></span></td>
        <td width="123" align="center" valign="top" ><span class="ewTableAltRow"><?php echo $x_numero_credito_vig_5;?></span></td>
        </tr>
	<tr>
	  <td align="center" valign="middle" class="ewTableHeaderThin">M&aacute;s de 120</td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_ven_6,2,0,0,1); ?></span></td>
	  <td align="center" valign="top"><span class="ewTableAltRow"><?php echo FormatNumber($x_rango_vig_6,2,0,0,1); ?></span></td>
      <td width="136" align="center" valign="top" ><span class="ewTableAltRow"><?php echo FormatNumber($x_total_6,2,0,0,1);?></span></td>
        <td width="123" align="center" valign="top" ><span class="ewTableAltRow"><?php echo $x_numero_credito_vig_6;?></span></td>
        </tr>
         <tr>
	  <td align="center" valign="middle" class="ewTableHeaderThin">Total</td>
		<td align="center" valign="top" ><span class="ewTableAltRow"><?php echo FormatNumber($x_total_vencido,2,0,0,1); ?></span></td>
		<td align="center" valign="top" ><span class="ewTableAltRow"><?php echo FormatNumber($x_total_vigente,2,0,0,1); ?></span></td>
        <td width="136" align="center" valign="top"  ><span class="ewTableAltRow"><?php echo FormatNumber($x_total_total,2,0,0,1);?></span></td>
        <td width="123" align="center" valign="top" ><span class="ewTableAltRow"><?php echo $x_total_numero_credito;?></span></td>
      </tr>
    
</table>

<table width="1314">
  <!-- Table header -->
  <tr>
	  <td width="146" valign="top" class="ewTableHeaderThin">Dias / Saldos</td>
	  <td width="646" align="center" valign="top" class="ewTableHeaderThin">Listado n&uacute;mero de cr&eacute;dito</td>
	  
     
	  </tr>
	<tr>
	  <td align="center" valign="middle" class="ewTableHeaderThin">0</td>
		<td align="center" valign="middle" style="font-size:7px"><?php echo $x_numero_credito_vig_0_lis; ?></td>
      	
	</tr>
	<tr>
	  <td align="center" valign="middle" class="ewTableHeaderThin">1 a 7</td>
		<td align="center" valign="middle" style="font-size:7px"><?php echo $x_numero_credito_vig_1_lis; ?></td>
				
	</tr>
	<tr>
	  <td align="center" valign="middle" class="ewTableHeaderThin">8 a 30</td>
	  <td align="center" valign="middle" style="font-size:7px"><?php echo $x_numero_credito_vig_2_lis; ?></td>
	 
	  </tr>
	<tr>
	  <td align="center" valign="middle" class="ewTableHeaderThin">31 a 60</td>
	  <td align="center" valign="middle" style="font-size:7px"><?php echo $x_numero_credito_vig_3_lis; ?></td>

	  </tr>
	<tr>
	  <td align="center" valign="middle" class="ewTableHeaderThin">61 a 90</td>
	  <td align="center" valign="middle" style="font-size:7px"><?php echo $x_numero_credito_vig_4_lis; ?></td>
	  
	  </tr>
	<tr>
	  <td align="center" valign="middle" class="ewTableHeaderThin">91 a 120</td>
	  <td align="center" valign="middle" style="font-size:7px"><?php echo $x_numero_credito_vig_5_lis; ?></td>
	 
	  </tr>
	<tr>
	  <td align="center" valign="middle" class="ewTableHeaderThin">M&aacute;s de 120</td>
	  <td align="center" valign="middle" style="font-size:7px"><?php echo $x_numero_credito_vig_6_lis; ?></td>

	  </tr>
    
</table>

<?php
//echo "lista 1".$x_lisc_1."<br><br>";
//echo "lista 2".$x_lisc_2."<br><br>";
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
