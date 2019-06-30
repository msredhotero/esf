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
	header('Content-Disposition: attachment; filename=reporte_devengado_'.date("Y-m-d").'.xls');
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


$filter = array();

#print_r($_POST);
$filter['x_credito_tipo_id'] = 100;
$filter['x_nombre_srch'] = '';
$filter['x_apepat_srch'] = '';
$filter['x_apemat_srch'] = '';
$filter['x_clinum_srch'] = '';
$filter['x_cresta_srch'] = '';
$filter['x_cresta_srch_2'] = '';
$filter['x_entidad_srch'] = '';
$filter['x_delegacion_srch'] = '';
$filter['x_credito_tipo_id'] = 100;
$filter['x_credito_num_filtro'] = '';
$filter['x_fecha_desde'] = '';
$filter['x_fecha_hasta'] = '';
$filter['x_medio_pago_id'] = '';
$filter['x_promo_srch'] = '';
$filter['x_gestor_srch'] = '';
$filter['x_sucursal_srch'] = '';
$filter['x_fecha_desde_2'] = '';
$filter['x_fecha_hasta_2'] = '';
$filter['x_gestor'] = '';

if(isset($_GET)) {	
	foreach($_GET as $key => $value) {
		
		if(isset($filter[$key]))
			 $filter[$key] = $value;
	}
	
}


if(isset($_POST)) {	
	foreach($_POST as $key => $value) {
		if(isset($filter[$key])) $filter[$key] = $value;
	}	
	
	
}


if(!function_exists('http_build_query')) {
    function http_build_query($data,$prefix=null,$sep='',$key='') {
        $ret    = array();
            foreach((array)$data as $k => $v) {
                $k    = urlencode($k);
                if(is_int($k) && $prefix != null) {
                    $k    = $prefix.$k;
                };
                if(!empty($key)) {
                    $k    = $key."[".$k."]";
                };

                if(is_array($v) || is_object($v)) {
                    array_push($ret,http_build_query($v,"",$sep,$k));
                }
                else {
                    array_push($ret,$k."=".urlencode($v));
                };
            };

        if(empty($sep)) {
            $sep = ini_get("arg_separator.output");
        };

        return    implode($sep, $ret);
    };
};




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
<p><span class="phpmaker">REPORTE DE INTERESES DEVENGADOS
<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="php_rpt_devengados.php?export=excel&x_mes=<?php echo $x_mes; ?>&x_year=<?php echo $x_year; ?>&x_empresa_id=<?php echo $x_empresa_id; ?>&x_fondeo_credito_id=<?php echo $x_fondeo_credito_id; ?>&x_sucursal_srch=<?php echo $x_sucursal_srch; ?>&x_promo_srch=<?php echo $x_promo_srch; ?>&x_pagado_srch=<?php echo $x_pagado_srch; ?>&x_credito_status_srch=<?php echo $x_credito_status_srch; ?>">Exportar a Excel</a> <?php } ?>
</span></p>

<?php if ($sExport == "") { ?><span class="phpmaker">
<form action="php_rpt_devengados.php" name="filtro" id="filtro" method="post">
<table width="785" border="0" cellpadding="0" cellspacing="0" style="visibility:hidden">
  <tr>
  <td><table width="895" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="133">Fondo:</td>
      <td width="10">&nbsp;</td>
      <td width="99"><?php
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
		
		if ($datawrk["fondeo_empresa_id"] == $x_empresa_id) {
			$x_medio_pago_idList .= "' selected";
		}

		$x_medio_pago_idList .= ">" . $datawrk["nombre"] . "</option>";
		
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_medio_pago_idList .= "</select>";
echo $x_medio_pago_idList;
?></td>
      <td width="10">&nbsp;</td>
      <td width="148"><div id="txtlineas" style=" float: left;">
        <?php
if(((!empty($x_empresa_id) && $x_empresa_id > 0)) && (!empty($x_fondeo_credito_id) && $x_fondeo_credito_id > 0)){

$x_delegacion_idList = "<select name=\"x_fondeo_credito_id\" >";
$x_delegacion_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT fondeo_credito_id, credito_num, importe FROM fondeo_credito where fondeo_empresa_id = $x_empresa_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["fondeo_credito_id"] == @$x_fondeo_credito_id) {
			$x_delegacion_idList .= "' selected";
		}
		$x_delegacion_idList .= "> Num: " . $datawrk["credito_num"] . " Importe: " . FormatNumber($datawrk["importe"],2,0,0,1) . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_delegacion_idList .= "</select>";
echo "Linea C.: ".$x_delegacion_idList;
	
	
}
?>
      </div></td>
      <td width="10">&nbsp;</td>
      <td width="119">&nbsp;</td>
      <td colspan="3">&nbsp;</td>
      </tr>
    
  
    <tr>
      <td>Status del credito</td>
      <td>&nbsp;</td>
      <td><span class="phpmaker">
        <?php
		$x_estado_civil_idList = "<select name=\"x_credito_status_srch\" class=\"texto_normal\">";
		if ($filter["x_credito_status_id_filtro"] == 0){
			$x_estado_civil_idList .= "<option value='1000' selected>TODAS</option>";
		}else{
			$x_estado_civil_idList .= "<option value='1000' >TODAS</option>";		
		}
		$sSqlWrk = "SELECT `credito_status_id`, `descripcion` FROM `credito_status` where credito_status_id in (1,3) ";
		#echo  $sSqlWrk;
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["credito_status_id"] == $x_cresta_srch) {
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
      <td></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    
    <tr>
      <td>Promotor</td>
      <td>&nbsp;</td>
      <td><?php
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
				if ($datawrk["promotor_id"] == $x_promo_srch) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["nombre_completo"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?></td>
      <td>&nbsp;</td>
      <td><!--Gestor-->Sucursal</td>
      <td>&nbsp;</td>
      <td><?php
		$x_estado_civil_idList = "<select name=\"x_sucursal_srch\" class=\"texto_normal\">";
		if($_SESSION["php_project_esf_status_UserRolID"] == 7) {
			$sSqlWrk = "SELECT sucursal.sucursal_id, nombre FROM sucursal join promotor on promotor.sucursal_id = sucursal.sucursal_id Where promotor.promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"];
		}else{
			$sSqlWrk = "SELECT sucursal_id, nombre FROM sucursal ";	
			$x_estado_civil_idList .= "<option value=\"1000\">TODOS</option>";	
		}		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["sucursal_id"] == $x_sucursal_srch) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["nombre"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?>        <!--<span class="ewTableAltRow"><select name="x_gestor_srch" >
      <option value="0">Seleccionar</option>
      <option value="18" <?php if ($filter["x_gestor_srch"]== 18){?> selected="selected"<?php }?>>Fernando Sanchez </option>
      <option value="1250"  <?php if ($filter["x_gestor_srch"]== 1250){?> selected="selected"<?php }?> >Angelica Tabares </option>
      <option value="16"  <?php if ($filter["x_gestor_srch"]== 16){?> selected="selected"<?php }?> >Monica Flores </option>
      <option value="3615" <?php if ($filter["x_gestor_srch"]== 3615){?> selected="selected"<?php }?> >Marcela Lopez </option>
      <option value="3065" <?php if ($filter["x_gestor_srch"]== 3065){?> selected="selected"<?php }?> >Josefina Ochoa </option>
        <option value="6658" <?php if ($filter["x_gestor_srch"]== 6658){?> selected="selected"<?php }?> >Miguel Angel </option>
      <option value="4812" <?php if ($filter["x_gestor_srch"]== 4842 ){?> selected="selected"<?php }?> >Victoria Garcia </option>      
      <option value="4561" <?php if ($filter["x_gestor_srch"]==  4561){?> selected="selected"<?php }?> >Rodrigo Sanchez </option>
      </select></span>--></td>
      <td>&nbsp;</td>
      <td><a href="php_rpt_pagos.php?cmd=reset"></a></td>
      <td>&nbsp;</td>
    </tr>
    
    
    
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="button"  value="Generar Reporte" name="Generar Reporte" onclick="filtrar();"  /></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td></td>
      <td>&nbsp;</td>
    </tr>
    
       </table></td>
</tr>
</table>
</form>
<form action="" name="filtro" id="filtro" method="post">
<input   type="hidden" name="x_paginacion" value="<?php echo $x_paginacion?>"  id="x_paginacion"/>
<p>
  <table width="785" border="0" cellpadding="0" cellspacing="0">
  <tr>
  <td><table width="895" border="0" cellpadding="0" cellspacing="0" >
    <tr>
      <td width="133">Tipo de Cr&eacute;dito</td>
      <td width="10">&nbsp;</td>
      <td width="99"><?php
		$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);  
		$x_estado_civil_idList = "<select name=\"x_credito_tipo_id\"  >";
		//$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$x_estado_civil_idList .= "<option value='100' selected>TODOS</option>";
		$sSqlWrk = "SELECT `credito_tipo_id`, `descripcion` FROM `credito_tipo` order by descripcion";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["credito_tipo_id"] == @$filter["x_credito_tipo_id"]) {
					$x_estado_civil_idList .= "'selected'";
				}
				$x_estado_civil_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		
		?></td>
      <td width="10">&nbsp;</td>
      <td width="148">&nbsp;</td>
      <td width="10">&nbsp;</td>
      <td width="119">&nbsp;</td>
      <td width="123">&nbsp;</td>
      <td width="115">&nbsp;</td>
      <td width="128">&nbsp;</td>
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
      <td><span class="phpmaker">Nombre</span></td>
      <td>&nbsp;</td>
      <td><span class="phpmaker">
        <input name="x_nombre_srch" type="text" id="x_nombre_srch" value="<?php echo $filter["x_nombre_srch"]; ?>" size="20" />
      </span></td>
      <td>&nbsp;</td>
      <td><span class="phpmaker">Apellido Paterno</span></td>
      <td>&nbsp;</td>
      <td><span class="phpmaker">
        <input name="x_apepat_srch" type="text" id="x_apepat_srch" value="<?php echo $filter["x_apepat_srch"]; ?>" size="20" />
      </span></td>
      <td>&nbsp;</td>
      <td><span class="phpmaker">Apellido Materno </span></td>
      <td><span class="phpmaker">&nbsp;&nbsp;
        <input name="x_apemat_srch" type="text" id="x_apemat_srch" value="<?php echo $filter["x_apemat_srch"]; ?>" size="20" />
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
      <td>Cr&eacute;dito num:</td>
      <td>&nbsp;</td>
      <td><input name="x_credito_num_filtro" type="text" id="x_credito_num_filtro" value="<?php echo $filter["x_credito_num_filtro"]; ?>" size="10" maxlength="10" /></td>
      <td>&nbsp;</td>
      <td>N&uacute;mero de Cliente </td>
      <td>&nbsp;</td>
      <td><span class="phpmaker">
        <input name="x_clinum_srch" type="text" id="x_clinum_srch" value="<?php echo $filter["x_clinum_srch"]; ?>" size="20" />
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
      <td>Sucursal</td>
      <td>&nbsp;</td>
      <td><?php
		$x_estado_civil_idList = "<select name=\"x_sucursal_srch\" class=\"texto_normal\">";
		if($_SESSION["php_project_esf_status_UserRolID"] == 7) {
			$sSqlWrk = "SELECT sucursal.sucursal_id, nombre FROM sucursal join promotor on promotor.sucursal_id = sucursal.sucursal_id Where promotor.promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"];
		}else{
			$sSqlWrk = "SELECT sucursal_id, nombre FROM sucursal ";	
			$x_estado_civil_idList .= "<option value=\"1000\">TODOS</option>";	
		}		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["sucursal_id"] == $filter["x_sucursal_srch"]) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["nombre"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="19">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><!-- Grupo --></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Staus del Cr&eacute;dito</td>
      <td>&nbsp;</td>
      <td><?php
		$x_estado_civil_idList = "<select name=\"x_cresta_srch_2\" class=\"texto_normal\">";
		if ($filter["x_credito_status_id_filtro_2"] == 0){
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
				if ($datawrk["credito_status_id"] == $filter["x_cresta_srch_2"]) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><!-- Grupo --></td>
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
      <td><!-- Grupo --></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    
    <tr>
      <td>Promotor</td>
      <td>&nbsp;</td>
      <td><?php
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
				if ($datawrk["promotor_id"] == $filter["x_promo_srch"]) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["nombre_completo"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?></td>
      <td>&nbsp;</td>
      <td><!--Gestor--></td>
      <td>&nbsp;</td>
      <td><!--<span class="ewTableAltRow"><select name="x_gestor_srch" >
      <option value="0">Seleccionar</option>
      <option value="18" <?php if ($filter["x_gestor_srch"]== 18){?> selected="selected"<?php }?>>Fernando Sanchez </option>
      <option value="1250"  <?php if ($filter["x_gestor_srch"]== 1250){?> selected="selected"<?php }?> >Angelica Tabares </option>
      <option value="16"  <?php if ($filter["x_gestor_srch"]== 16){?> selected="selected"<?php }?> >Monica Flores </option>
      <option value="3615" <?php if ($filter["x_gestor_srch"]== 3615){?> selected="selected"<?php }?> >Marcela Lopez </option>
      <option value="3065" <?php if ($filter["x_gestor_srch"]== 3065){?> selected="selected"<?php }?> >Josefina Ochoa </option>
        <option value="6658" <?php if ($filter["x_gestor_srch"]== 6658){?> selected="selected"<?php }?> >Miguel Angel </option>
      <option value="4812" <?php if ($filter["x_gestor_srch"]== 4842 ){?> selected="selected"<?php }?> >Victoria Garcia </option>      
      <option value="4561" <?php if ($filter["x_gestor_srch"]==  4561){?> selected="selected"<?php }?> >Rodrigo Sanchez </option>
      </select></span>--></td>
      <td>&nbsp;</td>
      <td><a href="php_rpt_pagos.php?cmd=reset"></a></td>
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
    <tr >
      <td><div align="right"><span class="phpmaker"> vencimiento desde: </span></div></td>
      <td>&nbsp;</td>
      <td><span>
        <input name="x_fecha_desde" type="text" id="x_fecha_desde" value="<?php echo FormatDateTime(@$filter['x_fecha_desde'],7); ?>" size="11" />
        &nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_desde" alt="Pick a Date" style="cursor:pointer;cursor:hand;" />
        <script type="text/javascript">
		Calendar.setup(
		{
		inputField : "x_fecha_desde", // ID of the input field
		ifFormat : "%d/%m/%Y", // the date format
		button : "cx_fecha_desde" // ID of the button
		}
		);
		</script>
      </span></td>
      <td>&nbsp;</td>
      <td><div align="right"> vencimiento<span class="phpmaker"> hasta: </span></div></td>
      <td>&nbsp;</td>
      <td><span>
        <input name="x_fecha_hasta" type="text" id="x_fecha_hasta" value="<?php echo FormatDateTime(@$filter["x_fecha_hasta"],7); ?>" size="11" />
        &nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_hasta" alt="Pick a Date" style="cursor:pointer;cursor:hand;" />
        <script type="text/javascript">
		Calendar.setup(
		{
		inputField : "x_fecha_hasta", // ID of the input field
		ifFormat : "%d/%m/%Y", // the date format
		button : "cx_fecha_hasta" // ID of the button
		}
		);
		</script>
      </span></td>
      <td>&nbsp;</td>
      <td><input type="Submit"  id="Submit" value="Filtrar" name="filtro2"  /></td>
      <td><a href="php_condonacionlist.php?cmd=reset">Mostrar Todos</a></td>
    </tr>
    <tr>
      <td><div align="right"></div></td>
      <td>&nbsp;</td>
      <td><span>&nbsp; 
		</span>	</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><span>&nbsp; 
		</span></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Mes</td>
      <td>&nbsp;</td>
      <td><input name="x_mes" type="text" id="x_mes" value="<?php echo @$x_mes; ?>" size="4" /></td>
      <td>&nbsp;</td>
      <td>A&ntilde;o</td>
      <td>&nbsp;</td>
      <td><input name="x_year" type="text" id="x_year" value="<?php echo @$x_year; ?>" size="6" /></td>
      <td>&nbsp;</td>
      <td></td>
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
      <td></td>
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
      <td></td>
      <td>&nbsp;</td>
    </tr>
  </table></td>
</tr>
</table>
</form>




</span></p>
<br />
<p align="center">
  <?php } ?>
</p>
<p align="left"><span class="phpmaker">
<div align="left">
  <table id="ewlistmain" class="ewTable" align="left">
    <!-- Table header -->
    <tr class="ewTableHeader" >
		<td width="118" valign="top"><span>
  Mes/A&ntilde;o</span></td>
		<td width="175" valign="top">Fondo</td>
		<td width="175" valign="top">Sucursal</td>
		<td width="175" valign="top">Promotor</td>
        <td width="175" valign="top">Cliente</td>
        <td width="175" valign="top">Cr&eacute;dito num</td>
        <td width="175" valign="top">Status</td>
		<td width="175" valign="top"><span>
Capital
		</span></td>        
		<td width="196" valign="top"><span>
Interes
		</span></td>
		<td width="133" valign="top">IVA</td>
		<td width="158" valign="top">Moratorios</td>        
		<td width="192" valign="top"><span> IVA Moratorios
</span></td>
<td width="158" valign="top">Mora Comision</td>        
		<td width="192" valign="top"><span> IVA Mora Comision
</span></td>


<td width="192" valign="top"><span> Total
</span></td>                
<td width="192" valign="top"><span>Pagado 
</span></td>   
<td width="192" valign="top"><span>Fecha de pago 
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
	  
$x_fecha_calculada = $x_year."-".$x_mes."-01";
//busacmos el ultimo dia del mes seleccionado para el reporte	  
$sqlultimo_dia_mes = " SELECT LAST_DAY('$x_fecha_calculada') AS ultimo_dia_mes ";
$rsUltimo_dia_mes = phpmkr_query($sqlultimo_dia_mes,$conn) or die ("Error la seleccionar el ultimo dia del mes".phpmkr_error()."sql: ".$sqlultimo_dia_mes);
$rowUltimo_dia_mes = phpmkr_fetch_array($rsUltimo_dia_mes);
$x_ultimo_dia_mes = $rowUltimo_dia_mes["ultimo_dia_mes"]; 

//////////////////////////////////////////////////////////////NUEVAS CONDICIONES PARA LOS FILTRO NUEVOS //////////////

if($filter["x_fecha_desde"] != ""){
	$sDbWhere .= "(vencimiento.fecha_vencimiento >= '".ConvertDateToMysqlFormat($filter["x_fecha_desde"])."') AND ";
	$sDbWhere .= "(vencimiento.fecha_vencimiento <= '".ConvertDateToMysqlFormat($filter["x_fecha_hasta"])."') AND ";	
	$x_usar_fecha_mes = 0;
}else{
	$x_usar_fecha_mes = 1;
	}

if($filter["x_credito_num_filtro"] != ""){
	$sDbWhere .= "(credito.credito_num+0 = ".$filter["x_credito_num_filtro"].") AND ";
}


 if (!empty($filter["x_sucursal_srch"])   || !empty($filter["x_promo_srch"])){
							#echo "entra---";																																																																																																													   // se unen los dos queries..
		if((($filter["x_sucursal_srch"] != "1000") && (!empty($filter["x_sucursal_srch"])) )&& ((!empty($filter["x_promo_srch"])&&($filter["x_promo_srch"] != "1000"  )))){
			$sDbWhere .= "(promotor.sucursal_id = ".$filter["x_sucursal_srch"].") AND (promotor.promotor_id = ".$filter["x_promo_srch"].") AND ";
			
			}									
															  
																																																																																																																				    else if(!empty($filter["x_sucursal_srch"]) ){
		if((!empty($filter["x_sucursal_srch"])) && ($filter["x_sucursal_srch"] != "1000")){
			$sDbWhere .= "(promotor.sucursal_id = ".$filter["x_sucursal_srch"].") AND ";
				//echo "<br>entra al if suc";	
		}
	}else if(!empty($filter["x_promo_srch"]) ){
		if((!empty($filter["x_promo_srch"])) && ($filter["x_promo_srch"] != "1000")){
			$sDbWhere .= "(solicitud.promotor_id = ".$filter["x_promo_srch"].") AND ";
				
		}
	}
 }
 
 
 $x_promo_srch = $filter["x_promo_srch"];
if(!empty($filter["x_promo_srch"])){
	if($filter["x_promo_srch"] < 1000){
		}else{
		$filter["x_promo_srch"] = "";
	}
}

if(!empty($filter["x_promo_srch"])){
	$sDbWhere .= "(solicitud.promotor_id = ".$filter["x_promo_srch"].") AND ";
}

// EN clientes
if((!empty($filter["x_nombre_srch"])) || (!empty($filter["x_apepat_srch"])) || (!empty($filter["x_apemat_srch"])) || (!empty($filter["x_clinum_srch"]))){
	$ssrch = "";
	if(!empty($filter["x_nombre_srch"])){
		$ssrch .= "(cliente.nombre_completo like '%".$filter["x_nombre_srch"]."%') AND ";
	}
	if(!empty($filter["x_apepat_srch"])){
		$ssrch .= "(cliente.apellido_paterno like '%".$filter["x_apepat_srch"]."%') AND ";
	}
	if(!empty($filter["x_apemat_srch"])){
		$ssrch .= "(cliente.apellido_materno like '%".$filter["x_apemat_srch"]."%') AND ";
	}
	if(!empty($filter["x_clinum_srch"])){
		$ssrch .= "(cliente.cliente_num+0 = ".$filter["x_clinum_srch"].") AND ";
	}
    
	$ssrch = substr($ssrch, 0, strlen($ssrch)-5);
	 //echo "serc cliente ".$ssrch."<br>";
	$ssrch_sql = "select cliente.cliente_id from cliente where ".$ssrch;
	$rs_qry = phpmkr_query($ssrch_sql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$nTotalRecs = phpmkr_num_rows($rs_qry);
	
	if($nTotalRecs >0){
		while ($row_sqry = @phpmkr_fetch_array($rs_qry)) {
			$ssrch_cli .= $row_sqry[0].","; 			
		}
		if(strlen($ssrch_cli) > 0 ){
			$sDbWhere .= " cliente.cliente_id in (".substr($ssrch_cli, 0, strlen($ssrch_cli)-1).") AND ";	
			
			
		}else{
			$ssrch_cli = "";
		}
	}else{
		$ssrch_cli = "";
	}
}else{
	$ssrch_cli = "";
}


if(!empty($filter["x_cresta_srch"])){
	if(!empty($filter["x_cresta_srch"]) && ($filter["x_cresta_srch"] != "100")){
		$sDbWhere .= "(solicitud.solicitud_status_id = ".$filter["x_cresta_srch"].") AND ";
	}
	
}
if(!empty($filter["x_cresta_srch_2"])){
	if(!empty($filter["x_cresta_srch_2"]) && ($filter["x_cresta_srch_2"] != "100")){
		$sDbWhere .= "(credito.credito_status_id = ".$filter["x_cresta_srch_2"].") AND ";
	}
	
}

//si se selecciono algun tipo de credito
	if(!empty($filter["x_credito_tipo_id"])){
	//si se selecciono pero no es TODOS que tiene el valor de 100
	if(!empty($filter["x_credito_tipo_id"]) && ($filter["x_credito_tipo_id"] != "100")){
		$sDbWhere .= "(solicitud.credito_tipo_id = ".$filter["x_credito_tipo_id"].") AND ";
	}
}



if (substr($sDbWhere, -5) == " AND ") {
	$sDbWhere = substr($sDbWhere, 0, strlen($sDbWhere)-5);
}
if(!empty($sDbWhere)){
$sDbWhere = " AND ".$sDbWhere;
}

if(!empty($x_filtros)){
 // echo "filtros llenos<br>";
 
 
 
$sSql = "select sum(vencimiento.importe) as capital, sum(interes) as interes, sum(interes_moratorio) as moratorios, sum(vencimiento.iva) as iva, sum(iva_mor) as ivmor from vencimiento join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id where year(vencimiento.fecha_vencimiento) = $x_year and month(vencimiento.fecha_vencimiento) = $x_mes".$x_filtros .$x_filtro_status;

$sSql = "select  credito.penalizacion AS penalizacion, credito.credito_id, credito.solicitud_id, credito.credito_num, credito.credito_status_id, sum(vencimiento.importe) as capital, sum(interes) as interes, sum(interes_moratorio) as moratorios, sum(vencimiento.iva) as iva, sum(iva_mor) as ivmor from vencimiento join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id where    year(vencimiento.fecha_vencimiento) = $x_year and month(vencimiento.fecha_vencimiento) = $x_mes".$x_filtros . $x_filtro_status.  $sDbWhere. "GROUP BY credito.credito_id";

}else{
$sSql = "select sum(vencimiento.importe) as capital, sum(interes) as interes, sum(interes_moratorio) as moratorios, sum(iva) as iva, sum(iva_mor) as ivmor from vencimiento where year(fecha_vencimiento) = $x_year and month(fecha_vencimiento) = $x_mes".$x_filtros;

$sSql = "select vencimiento.vencimiento_status_id, vencimiento.vencimiento_id,credito.penalizacion AS penalizacion, credito.credito_id, credito.solicitud_id, credito.credito_num, credito.credito_status_id, vencimiento.importe  as capital, vencimiento.interes as interes, vencimiento.interes_moratorio as moratorios, vencimiento.iva as iva, vencimiento.iva_mor as ivmor from vencimiento, credito  where credito.credito_id = vencimiento.credito_id  and  year(vencimiento.fecha_vencimiento) = $x_year and month(vencimiento.fecha_vencimiento) = $x_mes  ".$x_filtros. $x_filtro_status ." GROUP BY vencimiento.vencimiento_id ";


######################################## NUEVO QUERY PARA NEVOS FILTROS ##########################################

$sSql = "select vencimiento.vencimiento_status_id, vencimiento.vencimiento_id,credito.penalizacion AS penalizacion, credito.credito_id, credito.solicitud_id, credito.credito_num, credito.credito_status_id, vencimiento.importe  as capital, vencimiento.interes as interes, vencimiento.interes_moratorio as moratorios, vencimiento.iva as iva, vencimiento.iva_mor as ivmor from vencimiento, credito ,solicitud, solicitud_cliente, cliente, promotor  where credito.credito_id = vencimiento.credito_id and solicitud.solicitud_id = credito.solicitud_id andsolicitud_cliente.solicitud_id = solicitud.solicitud_id and solicitud_cliente.cliente_id = cliente.cliente_id and    and promotor.promotor_id = solicitud.promotor_id  year(vencimiento.fecha_vencimiento) = $x_year and month(vencimiento.fecha_vencimiento) = $x_mes  ".$x_filtros. $x_filtro_status ." GROUP BY vencimiento.vencimiento_id ";

//join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on solicitud_cliente.cliente_id = cliente.cliente_id  join promotor on promotor.promotor_id = solicitud.promotor_id
}
if($x_usar_fecha_mes== 1){
$sSql = "select vencimiento.fecha_vencimiento, vencimiento.vencimiento_status_id, vencimiento.vencimiento_id,credito.penalizacion AS penalizacion, credito.credito_id, credito.solicitud_id, credito.credito_num, credito.credito_status_id, vencimiento.importe  as capital, vencimiento.interes as interes, vencimiento.interes_moratorio as moratorios, vencimiento.iva as iva, vencimiento.iva_mor as ivmor from vencimiento, credito ,solicitud, solicitud_cliente, cliente, promotor  where credito.credito_id = vencimiento.credito_id and solicitud.solicitud_id = credito.solicitud_id and solicitud_cliente.solicitud_id = solicitud.solicitud_id and solicitud_cliente.cliente_id = cliente.cliente_id and  promotor.promotor_id = solicitud.promotor_id  and year(vencimiento.fecha_vencimiento) = $x_year and month(vencimiento.fecha_vencimiento) = $x_mes  ".$x_filtros. $x_filtro_status .$sDbWhere." GROUP BY vencimiento.vencimiento_id ";
}else{
	$sSql = "select vencimiento.fecha_vencimiento, vencimiento.vencimiento_status_id, vencimiento.vencimiento_id,credito.penalizacion AS penalizacion, credito.credito_id, credito.solicitud_id, credito.credito_num, credito.credito_status_id, vencimiento.importe  as capital, vencimiento.interes as interes, vencimiento.interes_moratorio as moratorios, vencimiento.iva as iva, vencimiento.iva_mor as ivmor from vencimiento, credito ,solicitud, solicitud_cliente, cliente, promotor  where credito.credito_id = vencimiento.credito_id and solicitud.solicitud_id = credito.solicitud_id and solicitud_cliente.solicitud_id = solicitud.solicitud_id and solicitud_cliente.cliente_id = cliente.cliente_id and  promotor.promotor_id = solicitud.promotor_id   ".$x_filtros. $x_filtro_status .$sDbWhere." GROUP BY vencimiento.vencimiento_id ";
	}

echo "SQL: ....".$sSql;
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
while($row = @phpmkr_fetch_array($rs)){
// si el credito tiene penalizaciones entonces, se vuelve a calcular los moratorios pra saber si son penalizaciones o si son comision por cobranza 
$x_capital = $row["capital"];
$x_fecha_vencimineto = $row["fecha_vencimiento"];
$x_interes = $row["interes"];
$x_moratorios = $row["moratorios"];
$x_credito_num = $row["credito_num"];
$x_solicitud_id = $row["solicitud_id"];
$x_credito_id = $row["credito_id"];
$x_penalizacion = $row["penalizacion"];
$x_vencimiento_id = $row["vencimiento_id"];
$x_vencimiento_status_id =  $row["vencimiento_status_id"];
//echo "<br>".$row["credito_num"].  $row["credito_status_id"];
$x_credito_status = $row["credito_status_id"];
$x_iva = $row["iva"];
$x_ivmor = $row["ivmor"];
//echo $x_vencimiento_status_id.",";

 $x_pagado_status = "NO PAGADO";
if($x_vencimiento_status_id == 2){
	$x_pagado_status = "";
	#echo "<br>";
	$sqlFechaPago = "SELECT fecha_pago FROM recibo WHERE recibo_status_id = 1 and recibo_id in (SELECT recibo_id FROM recibo_vencimiento WHERE vencimiento_id = $x_vencimiento_id  ) limit 0,1";
	$rsFechaPago = phpmkr_query($sqlFechaPago,$conn)or die("Error l seleccionar estaus venciiento".phpmkr_error().$sqlFechaPago);
	$rowFechaPago = phpmkr_fetch_array($rsFechaPago);
	$x_fecha_pago_venc = $rowFechaPago["fecha_pago"];
	
$sqlDias = "SELECT TO_DAYS('$x_fecha_pago_venc')AS PAGO_VENC , TO_DAYS('$x_ultimo_dia_mes') AS FECHA_CORTE ";
$rsDias =phpmkr_query($sqlDias,$conn) or die("Error l seleccionar los dias");
$rowDias =phpmkr_fetch_array($rsDias);
#echo  $sqlDias."<br>";
$x_dias_pago = $rowDias["PAGO_VENC"];
$x_dias_corte = $rowDias["FECHA_CORTE"];
//echo "  dias corte $x_dias_corte "."??"." dias pago  $x_dias_pago";
if( $x_dias_pago <= $x_dias_corte ){
	$x_pagado_status = "PAGADO";
	#echo "se pago antes del corte";
}else{
	$x_pagado_status = "NO PAGADO";
	$x_fecha_pago_venc = "";
	}
	
	
	}

if($x_penalizacion > 0){
	//calculmo nuevaente loas moratorios y los dividimos en los tre rubros
	
	// el capial sigue sieno capital
	//las penalizaciones siguen siendo moratorios...
	// pero la comison se separa de los moratorios...
	$x_moratorios = 0;
	$x_ivmor = 0;
	$sSqlMora = " SELECT vencimiento.interes_moratorio as moratorios, vencimiento.iva_mor as ivmor from vencimiento  where vencimiento.credito_id  = $x_credito_id and vencimiento_num < 3000 and  year(vencimiento.fecha_vencimiento) = $x_year and month(vencimiento.fecha_vencimiento) = $x_mes  and vencimiento.vencimiento_id = $x_vencimiento_id ";
	$rsMora = phpmkr_query($sSqlMora,$conn) or die ("Error al seeccionar los moraorios de las penalizaciones".phpmkr_error().$sSqlMora);
	$rowMora = phpmkr_fetch_array($rsMora);
	$x_moratorios_pena = $rowMora["moratorios"];
	$x_ivmor_pena = $rowMora["ivmor"];
	
	$x_moratorios = $x_moratorios_pena;// = $rowMora["moratorios"];
	$x_ivmor =$x_ivmor_pena;
	
	$sSqlComi = "  SELECT vencimiento.interes_moratorio as moratorios, vencimiento.iva_mor as ivmor from vencimiento  where vencimiento.credito_id  = $x_credito_id and vencimiento_num > 3000 and  year(vencimiento.fecha_vencimiento) = $x_year and month(vencimiento.fecha_vencimiento) = $x_mes  and vencimiento.vencimiento_id = $x_vencimiento_id";
	$rsComi = phpmkr_query($sSqlComi,$conn) or die ("Error al seeccionar los moraorios de las penalizaciones".phpmkr_error().$sSqlComi);
	$rowComi = phpmkr_fetch_array($rsComi);
	$x_moratorios_comi = $rowComi["moratorios"];
	$x_ivmor_comi = $rowComi["ivmor"];
	
	}




$x_total_credito = $x_interes+ $x_ivmor + $x_iva + $x_moratorios + $x_capital+ $x_ivmor_comi + $x_moratorios_comi ;
$x_total_ivmor += $x_ivmor;
$x_total_ivmor_comi += $x_ivmor_comi;
$x_total_iva += $x_iva;
$x_total_moratorios += $x_moratorios;
$x_total_moratorios_comi += $x_moratorios_comi;
$x_total_capital +=  $x_capital;
$x_total_general += $x_total_credito;
$x_total_interes += $x_interes;







//datos del cliente 
$x_cliente = "";
$sqlFondo = "select * from cliente, solicitud_cliente where cliente.cliente_id= solicitud_cliente.cliente_id and solicitud_cliente .solicitud_id =$x_solicitud_id  ";
$rsFondeo = phpmkr_query($sqlFondo,$conn) or die ("Error al seleccionar fodeo");
$rowFondeo = phpmkr_fetch_array($rsFondeo);
$x_cliente = $rowFondeo["nombre_completo"]." ".$rowFondeo["apellido_paterno"]." ".$rowFondeo["apellido_materno"];

$sqlFondo = "select * from fondeo_colocacion where credito_id = $x_credito_id ";
$rsFondeo = phpmkr_query($sqlFondo,$conn) or die ("Error al seleccionar fodeo");
$rowFondeo = phpmkr_fetch_array($rsFondeo);
$x_fondeo = $rowFondeo["fondeo_credito_id"];
if($x_fondeo == 6){
	$x_fondeo_descripcion = "PROPIOS";
	}else if($x_fondeo ==  7){
		
		$x_fondeo_descripcion = "FIM";
		}else{
			$x_fondeo_descripcion = "otros";
			}
			
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
			<td align="center"><span>
	<?php //echo// $x_mes."/".$x_year;?>
      <?php echo FormatDateTime($x_fecha_vencimineto,7)  ; ?>
	</span></td>
			<td align="left">
            <?php
			if(!empty($x_empresa_id)){
				$x_empresa_nombre = "";
            	$sSqlWrk = "SELECT fondeo_empresa.fondeo_empresa_id, fondeo_empresa.nombre FROM fondeo_empresa where fondeo_empresa_id = $x_empresa_id";
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_empresa_nombre = htmlspecialchars($datawrk["fondeo_empresa.nombre"]);
				@phpmkr_free_result($rswrk);
				
				$x_credito_numero = "";
				if(!empty($x_fondeo_credito_id)){
					$sSqlWrk = "SELECT fondeo_credito_id, credito_num, importe FROM fondeo_credito where fondeo_empresa_id = $x_empresa_id";
					$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
					$datawrk = phpmkr_fetch_array($rswrk);
					$x_credito_numero = $datawrk["credito_num"];
					@phpmkr_free_result($rswrk);
				}

				echo $x_empresa_nombre."/".$x_credito_numero;
			}else{
				
				echo $x_fondeo_descripcion;}
			?>
            </td>
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
			<td align="right"><span>
	<?php echo FormatNumber($x_capital,2,0,0,1); ?>
	</span></td>	
			<!-- importe -->
			<td align="right"><span>
	<?php echo FormatNumber($x_interes,2,0,0,1); ?>
	</span></td>
			<td align="right"><?php echo FormatNumber($x_iva,2,0,0,1); ?></td>
			<td align="right"><?php echo FormatNumber($x_moratorios,2,0,0,1); ?></td>	
			<!-- importe -->
			<td align="right"><span>
	<?php echo FormatNumber($x_ivmor,2,0,0,1); ?>
	</span></td>
    <td align="right"><?php echo FormatNumber($x_moratorios_comi,2,0,0,1); ?></td>	
			<!-- importe -->
			<td align="right"><span>
	<?php echo FormatNumber($x_ivmor_comi,2,0,0,1); ?>
	</span></td>	
    
    <td><?php echo FormatNumber($x_total_credito,2,0,0,1); ?></td>
    <td><?php echo $x_pagado_status; ?></td>
    <td><?php echo $x_fecha_pago_venc; ?></td>
    <?php $x_fecha_pago_venc = "";?>
		</tr>
        <?php }// while sql ?>
        <tr>
        <td colspan="7"></td>
        <td align="right"><span>
	<?php echo FormatNumber($x_total_capital,2,0,0,1); ?>
	</span></td>	
			<!-- importe -->
			<td align="right"><span>
	<?php echo FormatNumber($x_total_interes,2,0,0,1); ?>
	</span></td>
			<td align="right"><?php echo FormatNumber($x_total_iva,2,0,0,1); ?></td>
			<td align="right"><?php echo FormatNumber($x_total_moratorios,2,0,0,1); ?></td>	
			<!-- importe -->
			<td align="right"><span>
	<?php echo FormatNumber($x_total_ivmor,2,0,0,1); ?>
	</span></td>
    
    <td align="right"><?php echo FormatNumber($x_total_moratorios_comi,2,0,0,1); ?></td>	
			<!-- importe -->
			<td align="right"><span>
	<?php echo FormatNumber($x_total_ivmor_comi,2,0,0,1); ?>
	</span></td>
    	
    
    <td><?php echo FormatNumber($x_total_general,2,0,0,1); ?></td>
        </tr>
        
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
