<?php session_start(); ?>
<?php ob_start(); ?>
<?php set_time_limit(0); ?>
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
	header('Content-Disposition: attachment; filename=carterasaldoscap.xls');
}
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];

$limit = 100;

if (isset($_GET['paginaActual'])) {
	$paginaActual = $_GET['paginaActual'];
	$desdePaginador = $limit * $paginaActual;
} else {
	$paginaActual = 0;
	$desdePaginador = 0;
}

$hastaPaginador = 100;



// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);


if(isset($_POST["x_dias_ini"])){
	$_SESSION["x_dias_ini"] = $_POST["x_dias_ini"];
	$_SESSION["x_dias_fin"] = $_POST["x_dias_fin"];
}else{
	if(empty($_SESSION["x_dias_ini"])){
		$_SESSION["x_dias_ini"] = 1;
		$_SESSION["x_dias_fin"] = 999999999;
	}
}
$x_nombre_srch = @$_POST["x_nombre_srch"];
$x_apepat_srch = @$_POST["x_apepat_srch"];
$x_apemat_srch = @$_POST["x_apemat_srch"];
$x_crenum_srch = @$_POST["x_crenum_srch"];
$x_clinum_srch = @$_POST["x_clinum_srch"];
$x_promo_srch = @$_POST["x_promo_srch"];
$x_gestor_srch = @$_POST["x_gestor_srch"];
$x_empresa_id = @$_POST["x_empresa_id"];
$x_sucursal_srch = @$_POST["x_sucursal_srch"];
$x_cresta_srch = @$_POST["x_cresta_srch"];

$sCreW = '';

if(empty($x_gestor_srch)) {
	$x_gestor_srch = @$_GET["x_gestor_srch"];
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
if((!empty($_SESSION["x_nombre_srch"])) || (!empty($_SESSION["x_apepat_srch"])) || (!empty($_SESSION["x_apemat_srch"])) || (!empty($_SESSION["x_clinum_srch"])) || (!empty($_SESSION["x_promo_srch"]))) {
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

	$ssrch_sql = "select solicitud.solicitud_id from solicitud join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id where ".$ssrch." limit 30"; //marcos limit 30


	//die(var_dump($ssrch_sql));
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
} else {
	$ssrch_cli = "";
}

// En Credito
	if((!empty($_SESSION["x_crenum_srch"])) || (!empty($_SESSION["x_cresta_srch"]))) {
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
	} else {
		$ssrch_cre = "";
	}



if(!empty($x_sucursal_srch)){
	$_SESSION["x_sucursal_srch"] = $x_sucursal_srch;
} else {
	if(strlen($x_posteo) > 0){
		$_SESSION["x_sucursal_srch"] = "";
	}
}




if(!empty($_SESSION["x_sucursal_srch"])) {
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
if ((!empty($x_gestor_srch))) {
	echo "getor entra";
	#si el filtro de gestor esta lleno entonces se debe incluir solo los credito que son de ese gestor
	if($x_gestor_srch == 18) {
		$sSqlGestor = "SELECT credito_id FROM gestor_credito limit 30 "; //marcos limit 30
	} else {
		$sSqlGestor = "SELECT credito_id FROM gestor_credito WHERE usuario_id = ".$x_gestor_srch ." limit 30 "; //marcos limit 30
	}


	$rsGestor = phpmkr_query($sSqlGestor, $conn) or die ("Error al seleccionar los credito que pertenecen al gestor". phpmkr_error()."sql :".$sSqlGestor);

	while ($rowGestor = mysql_fetch_array($rsGestor)){
		$x_listado_creditos_gestor = $x_listado_creditos_gestor.$rowGestor["credito_id"].", ";
	}

	$x_listado_creditos_gestor = substr($x_listado_creditos_gestor, 0, strlen($x_listado_creditos_gestor)-2);

	$sCreW .= "((credito.credito_id in ($x_listado_creditos_gestor)) ) AND ";
}

$sWhere = $ssrch_cli.$ssrch_cre.$sCreW;




if(!empty($_POST["x_credito_tipo_id"]) && $_POST["x_credito_tipo_id"] < 1000) {
	$_SESSION["x_credito_tipo_id"] = $_POST["x_credito_tipo_id"];
	$sWhere .= " (credito.credito_tipo_id = ".$_SESSION["x_credito_tipo_id"]. ") AND ";
}




if ($sWhere != "") {
	if (substr($sWhere, -5) == " AND ") {
		$sWhere = " AND ".substr($sWhere, 0, strlen($sWhere)-5);
	}else{
		$sWhere = " AND ".$sWhere;
	}
}

$sSqlLimit = '';
// Build SQL
if($_SESSION["php_project_esf_status_UserRolID"] == 7) {
	$sSql = "select vencimiento.credito_id from vencimiento join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id where (credito.credito_status_id in (1,3)) AND (vencimiento.vencimiento_status_id = 3) AND (solicitud.promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"]. ") AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) >= ".$_SESSION["x_dias_ini"].") AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) <= ".$_SESSION["x_dias_fin"].$sWhere.") group by vencimiento.credito_id order by credito.credito_num+0 ";
} else {
	if($_SESSION["php_project_esf_status_UserRolID"] == 5) {
		$sSql = "select vencimiento.credito_id from vencimiento join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id where (credito.credito_status_id = 4) AND (vencimiento.vencimiento_status_id = 3) AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) >= ".$_SESSION["x_dias_ini"].") AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) <= ".$_SESSION["x_dias_fin"].$sWhere.")";
	} else {

		$sSql = "select vencimiento.credito_id from vencimiento join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id where(credito.credito_status_id not in (2))  ".$sWhere;
		//$sSql = "select vencimiento.credito_id from vencimiento join credito on credito.credito_id = vencimiento.credito_id  where(credito.credito_status_id not in (2))  ".$sWhere;
	}

	$sSql .= " group by vencimiento.credito_id order by credito.credito_num+0 "; //marcos limit 30

}

$sSqlLimit = $sSql."  limit ".$desdePaginador.",".$hastaPaginador;


//echo $sSql; // Uncomment to show SQL for debugging


//die(var_dump($sSql));


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
$rs = phpmkr_query($sSqlLimit,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
?>
<p><span class="phpmaker">
CARTERA CAPITAL</span></p>
<p><span class="phpmaker"><br />
  <br />
  <?php if ($sExport == "") { ?>

  <?php  if(($_SESSION["php_project_esf_status_UserRolID"] == 1) || ($_SESSION["php_project_esf_status_UserRolID"] == 4) || ($_SESSION["php_project_esf_status_UserRolID"] == 2)){
?>
  &nbsp;&nbsp;<a href="php_cartera_saldos_capital.php?export=excel&x_dias_ini=<?php echo $x_dias_ini; ?>&x_dias_fin=<?php echo $x_dias_fin; ?>&x_gestor_srch=<?php echo $x_gestor_srch;?>">Exportar a Excel</a>
  <?php } ?>
    <?php } ?>
</span></p>

<?php if ($sExport == "") { ?>
<form action="php_cartera_saldos_capital.php" name="filtro" id="filtro" method="post">
<table width="886" border="0" cellpadding="0" cellspacing="0">
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
    <td width="125"><span class="phpmaker">Tipo de Credito</span></td>
    <td width="11">&nbsp;</td>
    <td width="147"><span class="phpmaker">
      <?php
		$x_estado_civil_idList = "<select name=\"x_credito_tipo_id\" class=\"texto_normal\">";
		$sSqlWrk = "SELECT `credito_tipo_id`, `descripcion` FROM `credito_tipo`";
		if($_SESSION["x_credito_tipo_id"] = 1000){
			$x_estado_civil_idList .= "<option value=\"1000\" selected>TODOS</option>";
		}else{
			$x_estado_civil_idList .= "<option value=\"1000\">TODOS</option>";
		}
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if($_SESSION["x_credito_tipo_id"] < 1000){
				if ($datawrk["credito_tipo_id"] == $_SESSION["x_credito_tipo_id"]) {
					$x_estado_civil_idList .= "' selected";
				}
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?>
    </span>


	</td>
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
    <td></td>
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
      <input name="x_nombre_srch" type="text" id="x_nombre_srch" value="<?php echo @$_SESSION["x_nombre_srch"]; ?>" size="20" />
    </span></td>
    <td>&nbsp;</td>
    <td><span class="phpmaker">Apellido Paterno</span></td>
    <td>&nbsp;</td>
    <td><span class="phpmaker">
      <input name="x_apepat_srch" type="text" id="x_apepat_srch" value="<?php echo @$_SESSION["x_apepat_srch"]; ?>" size="20" />
    </span></td>
    <td>&nbsp;</td>
    <td><span class="phpmaker">Apellido Materno </span></td>
    <td>&nbsp;</td>
    <td><span class="phpmaker">&nbsp;&nbsp;
          <input name="x_apemat_srch" type="text" id="x_apemat_srch" value="<?php echo @$_SESSION["x_apemat_srch"]; ?>" size="20" />
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
      <input name="x_crenum_srch" type="text" id="x_crenum_srch" value="<?php echo @$_SESSION["x_crenum_srch"]; ?>" size="20" />
    </span></td>
    <td>&nbsp;</td>
    <td class="phpmaker">Numero de Cliente </td>
    <td>&nbsp;</td>
    <td><span class="phpmaker">
      <input name="x_clinum_srch" type="text" id="x_clinum_srch" value="<?php echo @$_SESSION["x_clinum_srch"]; ?>" size="20" />
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
				if ($datawrk["sucursal_id"] == @$_SESSION["x_sucursal_srch"]) {
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
		if ($datawrk["fondeo_empresa_id"] == @$_SESSION["x_empresa_id"]) {
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
				if ($datawrk["promotor_id"] == @$_SESSION["x_promo_srch"]) {
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
    <td><span class="phpmaker">Dias Fin:</span></td>
    <td>&nbsp;</td>
    <td><input name="x_dias_fin" type="text" id="x_dias_fin" onkeypress="return solonumeros(this,event)" value="<?php echo @$_SESSION["x_dias_fin"]; ?>" size="10" maxlength="10" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Status:</td>
    <td>&nbsp;</td>
    <td><span class="phpmaker">
      <?php
		$x_estado_civil_idList = "<select name=\"x_cresta_srch\" class=\"texto_normal\">";
		if (@$_SESSION["x_credito_status_id_filtro"] == 0){
			$x_estado_civil_idList .= "<option value='100' selected>TODAS</option>";
		}else{
			$x_estado_civil_idList .= "<option value='100' >TODAS</option>";
		}
		$sSqlWrk = "SELECT `credito_status_id`, `descripcion` FROM `credito_status`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["credito_status_id"] == $_SESSION["x_cresta_srch"]) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
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
    <td><span class="phpmaker"><a href="php_cartera_saldos_capital.php?cmd=reset">Mostrar Todos</a>&nbsp;&nbsp; </span></td>
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

<?php

$resTotal = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);

$totalRegistrosPaginador = mysql_num_rows($resTotal);
$cantidadPaginas = round($totalRegistrosPaginador / $limit, 0, PHP_ROUND_HALF_UP);

//die(var_dump($cantidadPaginas));
$activeClass = '';
$cadPaginador = '';

for ($i=0; $i < $cantidadPaginas; $i++) {
	// code...
	if ($paginaActual == $i) {
		$activeClass = 'color:#F00;';
	} else {
		$activeClass = '';
	}
	$cadPaginador .= '<a href="php_cartera_saldos_capital.php?paginaActual='.$i.'" style="margin-bottom:5px; padding:15px 8px;'.$activeClass.'">'.($i + 1).'</a>';

	if ((($i % 30) == 0) && $i <> 0) {
		$cadPaginador .= '<br><br>';
	}
}

echo $cadPaginador;


?>

</form>
<?php } ?>

<table id="ewlistmain" class="ewTable">
	<!-- Table header -->
	<tr class="ewTableHeader">
		<td>Sucursal</td>
        <td valign="top"><span>
		  Credito Num.
</span></td>
<td valign="top"><span>
		  Cliente Num.
</span></td>
       <td>Tipo Cliente</td>
		<td valign="top">Status</td>
		<td valign="top"><span>
Fondo
</span></td>
		<td valign="top">Tipo credito</td>

		<td valign="top"><span>
Promotor
		</span></td>
        <td valign="top"><span>
Gestor
		</span></td>
		<td valign="top"><span>
		  Cliente
		  </span></td>

         <td valign="top"><span>
		  Paterno
		  </span></td>

         <td valign="top"><span>
		 Materno
		  </span></td>
		<td valign="top">Importe</td>
		<td valign="top">Tasa</td>
        <td valign="top">Periodicidad</td>
		<td valign="top">Fecha otorgamiento</td>
		<td valign="top">Fecha vencimiento</td>
		<td valign="top">Capital pagado</td>
		<td valign="top">Interes pagado</td>
		<td valign="top">Moratorio pagado</td>
		<td valign="top"><span>
		  Max. num de Dias vencido
		  </span></td>
		<td valign="top">Dias sin pagar</td>
		<td valign="top"><span>
  Capital
		Vencido</span></td>
		<td valign="top">Capital Deudor</td>
		</tr>
<?php
$x_tot_pagos_venc = 0;
$x_max_dias_venc = 0;
$x_ttotal_importe = 0;
$x_ttotal_importe_d = 0;
$x_ttotal_interes = 0;
$x_ttotal_moratorios = 0;
$x_ttotal_total = 0;
$nRecCount = 0;
$nRecActual = 0;
$x_total_capital_pagado = 0;
$x_ttotal_importe_sp = 0;

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
	$x_periodicidad= "";


	$x_tipo_cliente = "";
	$sqlTipoCliente = "SELECT tipo_cliente FROM tipo_cliente WHERE credito_id = $x_credito_id ";
	$rsTipociente = phpmkr_query($sqlTipoCliente,$conn) or die("Error tipo cliente".$sqlTipoCliente);
	$rowTipoCliente =  phpmkr_fetch_array($rsTipociente);
	$x_tipo_cliente = $rowTipoCliente["tipo_cliente"];

	//peridicinada de pago
	$sqlP = "SELECT credito.forma_pago_id,  forma_pago.descripcion FROM credito JOIN forma_pago ON forma_pago.forma_pago_id = credito.forma_pago_id WHERE credito_id = $x_credito_id";
	$rsP = phpmkr_query($sqlP, $conn) or die ("Error al selaccionar la forma de pago". phpmkr_error());
	$rowP = phpmkr_fetch_array($rsP);
	$x_periodicidad = $rowP["descripcion"];
	@phpmkr_free_result($rsP);

	$sSqlWrk = "select credito.fecha_otrogamiento, credito.fecha_vencimiento, credito.tasa, credito.credito_status_id, credito.importe, credito.credito_num, credito.credito_tipo_id, solicitud.solicitud_id from credito join solicitud on solicitud.solicitud_id = credito.solicitud_id where credito.credito_id  = $x_credito_id";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$rowwrk = phpmkr_fetch_array($rswrk);
	$x_credito_num = $rowwrk["credito_num"];
	$x_credito_importe = $rowwrk["importe"];
	$x_credito_tipo_id = $rowwrk["credito_tipo_id"];
	$x_credito_status_id = $rowwrk["credito_status_id"];
	$x_solicitud_id = $rowwrk["solicitud_id"];
	$x_fecha_otrogamiento = $rowwrk["fecha_otrogamiento"];
	$x_fecha_vencimiento = $rowwrk["fecha_vencimiento"];
	$x_tasa = $rowwrk["tasa"];
	@phpmkr_free_result($rswrk);

	$sSqlWrk = "SELECT *, TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento) as dias_venc FROM vencimiento where (credito_id = $x_credito_id) AND (vencimiento.vencimiento_status_id = 3) AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) >= ".$_SESSION["x_dias_ini"].") AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) <= ".$_SESSION["x_dias_fin"].")
order by vencimiento_num+0
	";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);


	$x_total_importe = 0;
	$x_total_importe_d = 0;
	$x_total_interes = 0;
	$x_total_moratorios = 0;
	$x_total_total = 0;
	$x_dias_venc_ant = 0;
	$x_dias_venc_ultimo_pago = 0;
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

	//saldo deudor capital
	//determinar pagado capital
	$sSqlWrk = "select sum(importe) as pagado from vencimiento where credito_id = $x_credito_id and vencimiento_status_id in( 2,5) ";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$rowwrk = phpmkr_fetch_array($rswrk);
	$x_total_importe_pagado = $rowwrk["pagado"];
	@phpmkr_free_result($rswrk);

	$x_total_importe_d = $x_credito_importe - $x_total_importe_pagado;


//dias desde su ultimo pago

	$sSqlWrk = "SELECT TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(recibo.fecha_pago) as dias_up FROM vencimiento join recibo_vencimiento on recibo_vencimiento.vencimiento_id = vencimiento.vencimiento_id join recibo on recibo.recibo_id = recibo_vencimiento.recibo_id where (vencimiento.credito_id = $x_credito_id) AND (vencimiento.vencimiento_status_id in( 2)) order by recibo.fecha_pago desc limit 1";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$rowwrk = phpmkr_fetch_array($rswrk);
$x_dias_ultimo_pago = $rowwrk["dias_up"];
@phpmkr_free_result($rswrk);



//capital pagado
	$sSqlWrk = "SELECT sum(importe) as cap_pag, sum(interes) as int_pag, sum(interes_moratorio) as mor_pag FROM vencimiento where (vencimiento.credito_id = $x_credito_id) AND (vencimiento.vencimiento_status_id IN (2,5))";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$rowwrk = phpmkr_fetch_array($rswrk);
$x_capital_pagado = $rowwrk["cap_pag"];
$x_interes_pagado = $rowwrk["int_pag"];
$x_mora_pagado = $rowwrk["mor_pag"];
$x_total_capital_pagado += $x_capital_pagado;
@phpmkr_free_result($rswrk);


#el query de la cartera total es el siguiente
// SELECT sum(vencimiento.importe) as pagado from vencimiento join recibo_vencimiento on recibo_vencimiento.vencimiento_id = vencimiento.vencimiento_id join recibo on recibo.recibo_id = recibo_vencimiento.recibo_id join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id where (vencimiento.vencimiento_status_id in(2,5)) AND (recibo.recibo_status_id = 1) AND (vencimiento.credito_id not in (select credito_id from credito where credito_status_id in (2,5))) AND credito.credito_status_id = 3

# SE UTLIZO ESTE QUERY PARA PODER COMARAR CARTER ATOTAL CONTRA REPORTE DE SALDOS Y ENCNTARAR LA DIFERENCIA DE DICHOS REPORTES

// nuevo query
//$sSqlw = "SELECT sum(vencimiento.importe) as cap_pag,sum(interes) as int_pag, sum(interes_moratorio) as mor_pag from vencimiento join recibo_vencimiento on recibo_vencimiento.vencimiento_id = vencimiento.vencimiento_id join recibo on recibo.recibo_id = recibo_vencimiento.recibo_id join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id where (vencimiento.vencimiento_status_id in(2,5)) AND (recibo.recibo_status_id = 1)  and(vencimiento.credito_id=$x_credito_id) ".$x_filtros;
//echo $sSqlw;
	//$rsw = phpmkr_query($sSqlw,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSqlw);
	//$rowwrk = @phpmkr_fetch_array($rsw);
	//$x_capital_pagado = $rowwrk["cap_pag"];
	//$x_interes_pagado = $rowwrk["int_pag"];
	//$x_mora_pagado = $rowwrk["mor_pag"];
    //$x_total_capital_pagado += $x_capital_pagado;

?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
		<!-- vencimiento_id -->
        <td>
		<?php
		/* aqui */

		 $sSqlWrk = "SELECT promotor.nombre_completo , promotor.promotor_id,  promotor.sucursal_id  FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id  Where credito.credito_id = $x_credito_id ";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
			$x_promotor_nombre = $rowwrk["nombre_completo"];
			$x_sucursal_id =  $rowwrk["sucursal_id"];
	}else{
			$x_promotor_nombre = "";
			$x_sucursal_id =  $rowwrk["sucursal_id"];
		}
		@phpmkr_free_result($rswrk);

$x_sucursal = "";

if($x_sucursal_id>0){
		$sSqlWrk = "SELECT nombre FROM  sucursal Where sucursal_id = $x_sucursal_id ";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {

			$x_sucursal =  $rowwrk["nombre"];
		}else{

			$x_sucursal =  $rowwrk["nombre"];
		}
}
		@phpmkr_free_result($rswrk);

echo $x_sucursal;
?>
</td>

		<td align="right"><span>
  <?php echo $x_credito_num; ?>
</span></td>
<td align="left"><?php
$sSqlWrk = "SELECT cliente.cliente_num as num_cli FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id Where credito.credito_id = $x_credito_id ";
//$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
//if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	//echo $rowwrk["num_cli"];
//}
//@phpmkr_free_result($rswrk);
?></td>
   <td><?php echo $x_tipo_cliente?></td>
		<td align="left"><?php
if($x_credito_status_id>0){
$sSqlWrk = "select descripcion from credito_status where credito_status_id = $x_credito_status_id ";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	echo $rowwrk["descripcion"];
}
}
@phpmkr_free_result($rswrk);
?></td>

		<td align="left"><span>

<?php

$sSqlWrk = "select
		fondeo_empresa.nombre
	from  	fondeo_colocacion
	inner
	join 		fondeo_credito
	on 		fondeo_credito.fondeo_credito_id = fondeo_colocacion.fondeo_credito_id
	inner
	join 		fondeo_empresa
	on 		fondeo_empresa.fondeo_empresa_id = fondeo_credito.fondeo_empresa_id
	where 	fondeo_colocacion.credito_id = $x_credito_id ";
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
		<td align="left"><?php
		if($x_credito_tipo_id>0){
		$sSqlWrk = "SELECT descripcion FROM credito_tipo Where credito_tipo_id = $x_credito_tipo_id ";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
			$x_promotor = $rowwrk["descripcion"];
		}else{
			$x_promotor = "";
		}
		@phpmkr_free_result($rswrk);

echo $x_promotor;
		}

?></td>

		<td align="left"><span>
<?php

echo $x_promotor_nombre;

?>
</span></td>

	<td align="left"><span>
<?php
		$sSqlWrk = "SELECT promotor.nombre_completo FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id join gestor_credito on gestor_credito.credito_id = credito.credito_id Where credito.credito_id = $x_credito_id ";
		$sSqlWrk = "SELECT gestor_credito.usuario_id, usuario.nombre FROM usuario  join gestor_credito on gestor_credito.usuario_id = usuario.usuario_id Where credito_id = $x_credito_id ";
		//$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		//if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		//	$x_gestor = $rowwrk["nombre"];
		//}else{
		//	$x_gestor = "";
		//}
		//@phpmkr_free_result($rswrk);

//echo $x_gestor;

?>
</span></td>
		<td align="left"><span>
  <?php
  $x_cliente = "";
  $x_paterno = "";
  $x_materno = "";
		if($x_credito_tipo_id == 1){
			$sSqlWrk = "SELECT cliente.nombre_completo as cliente_nombre, cliente.apellido_paterno, cliente.apellido_materno FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id Where credito.credito_id = $x_credito_id ";
		}else{
			$sSqlWrk = "SELECT solicitud.grupo_nombre as cliente_nombre,'' as apellido_paterno, '' as apellido_materno FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id Where credito.credito_id = $x_credito_id ";
		}

		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
			$x_cliente = $rowwrk["cliente_nombre"];
			$x_paterno = $rowwrk["apellido_paterno"];
			$x_materno = $rowwrk["apellido_materno"];
		}else{
			$x_cliente = "";
			@phpmkr_free_result($rswrk);
		}
		@phpmkr_free_result($rswrk);
		echo $x_cliente;
?>
</span></td>
<td align="left"><span>
  <?php
		if($x_credito_tipo_id == 1){
			echo $x_paterno;
		}
?>
</span></td>
<td align="left"><span>
  <?php
		if($x_credito_tipo_id == 1){
			echo $x_materno;
		}
?>
</span></td>
		<td align="right"><?php echo $x_credito_importe; ?></td>
		<td align="center"><?php echo $x_tasa; ?></td>
        <td align="center"><?php echo $x_periodicidad; ?></td>
		<td align="center"><?php echo FormatDateTime($x_fecha_otrogamiento,7); ?></td>
		<td align="center"><?php echo FormatDateTime($x_fecha_vencimiento,7); ?></td>
		<td align="right"><?php echo FormatNumber($x_capital_pagado,2,0,0,1) ?></td>
		<td align="right"><?php echo FormatNumber($x_interes_pagado,2,0,0,1) ?></td>
		<td align="right"><?php echo FormatNumber($x_mora_pagado,2,0,0,1) ?></td>
		<td align="right"><span>
  <?php echo $x_dias_venc_ant; ?>
</span></td>
		<td align="right"><?php echo $x_dias_ultimo_pago; ?></td>
		<!-- importe -->
		<td align="right"><span>
<?php echo FormatNumber($x_total_importe,2,0,0,1) ?>
</span></td>
		<td align="right"><?php echo FormatNumber($x_total_importe_d,2,0,0,1) ?></td>
		<!-- interes -->
		<!-- interes_moratorio -->		</tr>
<?php

	$x_tot_pagos_venc = $x_tot_pagos_venc + $x_contador;
	if($x_max_dias_venc < $x_dias_venc_ant){
		$x_max_dias_venc = $x_dias_venc_ant;
	}
	$x_ttotal_importe = $x_ttotal_importe + $x_total_importe;
 	$x_ttotal_importe_d = $x_ttotal_importe_d + $x_total_importe_d;
	$x_ttotal_interes = $x_ttotal_interes + $x_total_interes;
	$x_ttotal_moratorios = $x_ttotal_moratorios + $x_total_moratorios;
	$x_ttotal_total = $x_ttotal_total + $x_total_total;
}
?>
<tr>
		<td valign="top" colspan="3"><span>
<b>Totales</b>
		</span></td>
		<td valign="top">&nbsp;</td>
		<td valign="top"><span>

		</span></td>
		<td valign="top"><span>

		  </span></td>
		<td align="right">&nbsp;</td>
		<td align="right">&nbsp;</td>
		<td align="right">&nbsp;</td>
		<td align="right">&nbsp;</td>
		<td align="right">&nbsp;</td>
		<td align="right">&nbsp;</td>
		<td align="right"><?php echo FormatNumber($x_ttotal_importe,2,0,0,1) ?></td>
		<td align="right">&nbsp;</td>
		<td align="right"><?php echo FormatNumber($x_ttotal_importe_sp,2,0,0,1) ?></td>
		<td align="right"></td>
		<td align="right"><?php echo FormatNumber($x_ttotal_interes,2,0,0,1) ?></td>
        <td align="right"><?php echo FormatNumber($x_total_capital_pagado,2,0,0,1) ?>...</td>
		<td align="right">&nbsp;</td>
		<td align="right">&nbsp;</td>
		<td align="right">&nbsp;</td>
        <td align="right">&nbsp;</td>
		<td align="right">&nbsp;</td>
		<td align="right"><span>
			<?php echo FormatNumber($x_ttotal_importe_d,2,0,0,1) ?>
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
