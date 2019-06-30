<?php session_start(); ?>
<?php ob_start(); ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Pragma: no-cache"); // HTTP/1.0 
?>
<?php
$ewCurSec = 0; // Initialise

// User levels
define("ewAllowAdd", 1, true);
define("ewAllowDelete", 2, true);
define("ewAllowEdit", 4, true);
define("ewAllowView", 8, true);
define("ewAllowList", 8, true);
define("ewAllowReport", 8, true);
define("ewAllowSearch", 8, true);																														
define("ewAllowAdmin", 16, true);						
?>
<?php

// Initialize common variables
$x_convenio_liquidacion_id = Null;
$x_credito_id = Null;
$x_monto = Null;
$x_status = Null;
$x_fecha = Null;
$x_fecha_modificacion = Null;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=convenio_liquidacion.xls');
}
if ($sExport == "word") {
	header('Content-Type: application/vnd.ms-word');
	header('Content-Disposition: attachment; filename=convenio_liquidacion.doc');
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
$sSrchBasic = "";
$sSrchWhere = "";
$sDbWhere = "";
$sDefaultOrderBy = "";
$sDefaultFilter = "";
$sWhere = "";
$sGroupBy = "";
$sHaving = "";
$sOrderBy = "";
$sSqlMaster = "";
$nDisplayRecs = 20;
$nRecRange = 10;

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS,DB, PORT);

// Handle Reset Command
ResetCmd();

/*$filter = array();


$filter['x_crenum_srch'] = '';*/



$filter = array();

#print_r($_POST);
$filter['x_credito_tipo_id'] = 100;
$filter['x_tipo_srch'] = 1000;
$filter['x_nombre_srch'] = '';
$filter['x_apepat_srch'] = '';
$filter['x_apemat_srch'] = '';
$filter['x_clinum_srch'] = '';
$filter['x_cresta_srch'] = '';
$filter['x_cresta_srch_2'] = '';
$filter['x_entidad_srch'] = '';
$filter['x_delegacion_srch'] = '';
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


#echo "rol id".$_SESSION["php_project_esf_status_UserRolID"]."<br>";



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

if(!empty($filter["x_tipo_srch"])){
	//echo "enra a filro sucursal";
		if((!empty($filter["x_tipo_srch"])) && ($filter["x_tipo_srch"] != "1000")){
			$ssrch_cre .= "(id_tipo_mensaje = ".$filter["x_tipo_srch"].") AND ";
			//$ssrch_cre = substr($ssrch_cre, 0, strlen($ssrch_cre)-5);		
		}
	}
	
if(!empty($filter["x_crenum_srch"])){
			$ssrch_cre .= "( credito_num+0 = ".$filter["x_crenum_srch"].") AND ";
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
		echo " f(!empty(".$filter["x_credito_tipo_id"].") && (".$filter["x_credito_tipo_id"]." != \"100\"))"; 
	}
}

if($filter["x_fecha_desde"] != ""){
	$sDbWhere .= "(sms_enviados.fecha_registro >= '".ConvertDateToMysqlFormat($filter["x_fecha_desde"])."') AND ";
	$sDbWhere .= "(sms_enviados.fecha_registro <= '".ConvertDateToMysqlFormat($filter["x_fecha_hasta"])."') AND ";	
}

//$sWhere =  $sDbWhere.$sWhere .$sDbWhere_juridico.$sNPWhere2;
$sSql = "SELECT cliente.cliente_id, promotor.promotor_id,promotor.sucursal_id,solicitud.solicitud_id, sms_enviados.*, credito.credito_id, credito.credito_num, credito.solicitud_id, credito.credito_status_id FROM sms_enviados  join credito on credito.credito_num = sms_enviados.no_credito join solicitud on solicitud.solicitud_id = credito.solicitud_id  join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on solicitud_cliente.cliente_id = cliente.cliente_id  join promotor on promotor.promotor_id = solicitud.promotor_id"	;
// Load Default Filter
$sDefaultFilter = "";
$sGroupBy = "";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = "";


$sWhere =  $sDbWhere .$sDbWhere_juridico.$sNPWhere2.$ssrch_cre;
if ($sDefaultFilter != "") {
	$sWhere .= "(" . $sDefaultFilter . ") AND ";
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
$sOrderBy = "";
SetUpSortOrder();
if ($sOrderBy != "") {
	$sSql .= " ORDER BY " . $sOrderBy;
}

$sSql .= "  order by id_sms_enviado desc ";

?>

<?php include ("header.php") ?>
<?php if ($sExport == "") { ?>



<link rel="stylesheet" type="text/css" media="all" href="jscalendar/skins/aqua/theme.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-es.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<script type="text/javascript" src="ew.js"></script>

<script language="javascript" src="concilia_conevio_liquidacion.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<?php } ?>
<?php
#echo $sSql;
// Set up Record Set
$rs = phpmkr_query($sSql,$conn)or die(phpmkr_error().$sSql);
$nTotalRecs = phpmkr_num_rows($rs);
if ($nDisplayRecs <= 0) { // Display All Records

	$nDisplayRecs = $nTotalRecs;
}
$nStartRec = 1;
SetUpStartRec(); // Set Up Start Record Position
?>
<p><span class="phpmaker">Mensajes enviados desde el d&iacute;a 07 de Junio del 2014
  <?php if ($sExport == "") { ?><?php } ?>
</span></p>
<?php if ($sExport == "") { ?>
<p>
  <?php } ?>
  <?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="phpmaker" style="color: Red;"><?php echo $_SESSION["ewmsg"]; ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>
<?php if ($sExport == "") { ?>





<!--<form action="" name="ewpagerform" id="ewpagerform">-->


<form action="" name="filtro" id="filtro" method="post">
<input   type="hidden" name="x_paginacion" value="<?php echo $x_paginacion?>"  id="x_paginacion"/>
<p>
  <table width="785" border="0" cellpadding="0" cellspacing="0">
  <tr>
  <td><table width="895" border="0" cellpadding="0" cellspacing="0">
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
      <td>Tipo de Mensaje</td>
      <td>&nbsp;</td>
      <td><?php
		$x_estado_civil_idList = "<select name=\"x_tipo_srch\" class=\"texto_normal\">";
			$sSqlWrk = "SELECT sms_tipo_id, descripcion FROM sms_tipo ";
			$x_estado_civil_idList .= "<option value=\"1000\">TODOS</option>";	
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["sms_tipo_id"] == $filter["x_tipo_srch"]) {
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
    <tr>
      <td><div align="right"><span class="phpmaker">  desde: </span></div></td>
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
      <td><div align="right"><span class="phpmaker"> hasta: </span></div></td>
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



<table class="ewTablePager">
	<tr>
		<td nowrap>
<span class="phpmaker">
<?php


$_QS = http_build_query($filter);
// Display page numbers
if ($nTotalRecs > 0) {
	$rsEof = ($nTotalRecs < ($nStartRec + $nDisplayRecs));
	if ($nTotalRecs > $nDisplayRecs) {

		// Find out if there should be Backward or Forward Buttons on the TABLE.
		if 	($nStartRec == 1) {
			$isPrev = False;
		} else {
			$isPrev = True;
			$PrevStart = $nStartRec - $nDisplayRecs;
			if ($PrevStart < 1) { $PrevStart = 1; } ?>
		<a href="php_smslist.php?start=<?php echo $PrevStart; ?>&<?php echo $_QS; ?>"><b>Anterior</b></a>
		<?php
		}
		if ($isPrev || (!$rsEof)) {
			$x = 1;
			$y = 1;
			$dx1 = intval(($nStartRec-1)/($nDisplayRecs*$nRecRange))*$nDisplayRecs*$nRecRange+1;
			$dy1 = intval(($nStartRec-1)/($nDisplayRecs*$nRecRange))*$nRecRange+1;
			if (($dx1+$nDisplayRecs*$nRecRange-1) > $nTotalRecs) {
				$dx2 = intval($nTotalRecs/$nDisplayRecs)*$nDisplayRecs+1;
				$dy2 = intval($nTotalRecs/$nDisplayRecs)+1;
			} else {
				$dx2 = $dx1+$nDisplayRecs*$nRecRange-1;
				$dy2 = $dy1+$nRecRange-1;
			}
			while ($x <= $nTotalRecs) {
				if (($x >= $dx1) && ($x <= $dx2)) {
					if ($nStartRec == $x) { ?>
		<b><?php echo $y; ?></b>
					<?php } else { ?>
		<a href="php_smslist.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?></b></a>
					<?php }
					$x += $nDisplayRecs;
					$y += 1;
				} elseif (($x >= ($dx1-$nDisplayRecs*$nRecRange)) && ($x <= ($dx2+$nDisplayRecs*$nRecRange))) {
					if ($x+$nRecRange*$nDisplayRecs < $nTotalRecs) { ?>
		<a href="php_smslist.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?>-<?php echo ($y+$nRecRange-1);?></b></a>
					<?php } else {
						$ny=intval(($nTotalRecs-1)/$nDisplayRecs)+1;
							if ($ny == $y) { ?>
		<a href="php_smslist.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?></b></a>
							<?php } else { ?>
		<a href="php_smslist.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?>-<?php echo $ny; ?></b></a>
							<?php }
					}
					$x += $nRecRange*$nDisplayRecs;
					$y += $nRecRange;
				} else {
					$x += $nRecRange*$nDisplayRecs;
					$y += $nRecRange;
				}
			}
		}

		// Next link
		if (!$rsEof) {
			$NextStart = $nStartRec + $nDisplayRecs;
			$isMore = True;  ?>
			  <a href="php_smslist.php?start=<?php echo $NextStart; ?>&<?php echo $_QS; ?>"><b>Siguiente</b></a>
		<?php } else {
			$isMore = False;
		} ?>
		<br>
<?php	}
	if ($nStartRec > $nTotalRecs) { $nStartRec = $nTotalRecs; }
	$nStopRec = $nStartRec + $nDisplayRecs - 1;
	$nRecCount = $nTotalRecs - 1;
	if ($rsEof) { $nRecCount = $nTotalRecs; }
	if ($nStopRec > $nRecCount) { $nStopRec = $nRecCount; } ?>
	Registros <?php echo  $nStartRec;  ?> a <?php  echo $nStopRec; ?> de <?php echo  $nTotalRecs; ?>
<?php } else { ?>
	No hay datos
<?php }?>
</span>
		</td>
	</tr>
</table>
</form>
<?php } ?>
<form method="post">
<table class="ewTable">
<?php if ($nTotalRecs > 0) { ?>
	<!-- Table header -->
	<tr class="ewTableHeader">
    <?php if ($sExport == "") { ?>

<?php } ?>
		<td valign="top"><span>
  <?php if ($sExport <> "") { ?>
		  ID
  <?php }else{ ?>
		  <a href="php_smslist.php?order=<?php echo urlencode("convenio_liquidacion_id"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">ID<?php if (@$_SESSION["convenio_liquidacion_x_convenio_liquidacion_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["convenio_liquidacion_x_convenio_liquidacion_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
  <?php } ?>
		  </span></td>
          <td>Sucursal</td>
          <td>Promotor</td>
          <td>Cliente</td>
        	<td valign="top"><span>
  <?php if ($sExport <> "") { ?>
        	  Fecha registro
  <?php }else{ ?>
        	  Fecha registro
  <?php } ?>
      	  </span></td>
          
 <td valign="top"><span>
  <?php if ($sExport <> "") { ?>
   Credito num
  <?php }else{ ?>
   Cr&eacute;dito num
  <?php } ?>
 </span></td>
 <td>Status</td>
<td valign="top"><span>
  <?php if ($sExport <> "") { ?>
  TIPO<?php }else{ ?> TIPO
  <?php } ?>
</span></td>
        <td valign="top"><span>
<?php if ($sExport <> "") { ?>
 Texto
 <?php }else{ ?>Texto<?php } ?>
		</span></td>
        <td valign="top"><span>
<?php if ($sExport <> "") { ?>
Celular<?php }else{ ?>Celular<?php } ?>
		</span></td>
        <td valign="top"><span>
<?php if ($sExport <> "") { ?> Destino<?php }else{ ?> Destino<?php } ?>
		</span></td>
        <td valign="top"><span>
  <?php if ($sExport <> "") { ?>
          Forma envio
  <?php }else{ ?>
          Forma envio
  <?php } ?>
        </span></td>
  </tr>
<?php } ?>
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
$nRecActual = 0;
while (($row = @mysql_fetch_assoc($rs)) && ($nRecCount < $nStopRec)) {
	$nRecCount = $nRecCount + 1;
	if ($nRecCount >= $nStartRec) {
		$nRecActual = $nRecActual + 1;

	// Set row color
	$sItemRowClass = " class=\"ewTableRow\"";

	// Display alternate color for rows
	if ($nRecCount % 2 <> 0) {
		$sItemRowClass = " class=\"ewTableAltRow\"";
	}

		// Load Key for record
		$sKey = $row["convenio_liquidacion_id"];
		
		foreach($row as $nombre => $valor){
			$$nombre = $valor;
			//echo $nombre;
			}
		$x_tipo = "";	
		if($id_tipo_mensaje>0){
		$SQLtIPO = "SELECT  descripcion FROM sms_tipo WHERE sms_tipo_id = $id_tipo_mensaje";
		$rsDescri = phpmkr_query($SQLtIPO,$conn) or die("Erro al seleccionar".phpmkr_error());
		$rowDesc = phpmkr_fetch_array($rsDescri);
		$x_tipo = 	$rowDesc["descripcion"];
		}
		if($credito_status_id>0){
		$SQLtIPO = "SELECT credito_status.descripcion as credito_status FROM  credito_status WHERE credito_status.credito_status_id   = $credito_status_id";
		$rsDescri = phpmkr_query($SQLtIPO,$conn) or die("Erro al seleccionar".phpmkr_error()."sql. ".$SQLtIPO);
		$rowDesc = phpmkr_fetch_array($rsDescri);
		//$x_credito_num =$rowDesc["credito_num"];
		$x_credito_status =$rowDesc["credito_status"];
		}
		
		// seleccionamos promotor y sucursal
		if($sucursal_id>0){
		$SQLtIPO = "SELECT  sucursal.nombre as sucursal, promotor.nombre_completo as promotor  FROM  promotor , sucursal WHERE  sucursal.sucursal_id = $sucursal_id and promotor.promotor_id = $promotor_id and promotor.sucursal_id =  sucursal.sucursal_id";
		$rsDescri = phpmkr_query($SQLtIPO,$conn) or die("Erro al seleccionar".phpmkr_error());
		$rowDesc = phpmkr_fetch_array($rsDescri);
		$x_sucursal =$rowDesc["sucursal"];
		$x_promotor =$rowDesc["promotor"];
		}
		// seleccionmos datos del cleinte		
		if($cliente_id>0){
		$SQLtIPO = "SELECT   cliente.nombre_completo, cliente.apellido_paterno, cliente.apellido_materno FROM cliente  WHERE cliente_id = $cliente_id  ";
		$rsDescri = phpmkr_query($SQLtIPO,$conn) or die("Erro al seleccionar".phpmkr_error());
		$rowDesc = phpmkr_fetch_array($rsDescri);
		$x_cliente =$rowDesc["nombre_completo"]." ".$rowDesc["apellido_paterno"]." ".$rowDesc["apellido_materno"];
		}
		
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
    <?php if ($sExport == "") { ?>

<?php } ?>
		<!-- convenio_liquidacion_id -->
		<td><span>
  <?php echo $id_sms_enviado; ?>
</span></td>
<td><span>
  <?php echo $x_sucursal; ?>
</span></td><td><span>
  <?php echo $x_promotor; ?>
</span></td><td><span>
  <?php echo $x_cliente; ?>
</span></td>
<td><span>
  <?php echo $fecha_registro; ?>
</span></td>
<td><span>
  <?php echo $no_credito; ?>
</span></td>
<td><?php echo $x_credito_status;?></td>
<td><span>
  <?php echo $x_tipo; ?>
</span></td>
<td><span>
<?php echo $contenido; ?>
</span></td>
<td><span>
<?php echo $no_celular; ?>
</span></td>
<td><span>
<?php if( $destino == 1) echo "CLIENTE";if( $destino == 2) echo "AVAL"; ?>
</span></td>
<td><span>
<?php if( $tipo_envio == 1) echo "POR PROCESO";if( $destino == 2) echo "POR ADMINISTRACION"; ?>
</span></td>

</tr>
<?php
	}
}
?>
</table>
</form>
<?php

// Close recordset and connection
phpmkr_free_result($rs);
phpmkr_db_close($conn);
?>
<?php if ($sExport <> "word" && $sExport <> "excel") { ?>
<?php include ("footer.php") ?>
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

	// Check for Ctrl pressed
	if (strlen(@$_GET["ctrl"]) > 0) {
		$bCtrl = true;
	}
	else
	{
		$bCtrl = false;
	}

	// Check for an Order parameter
	if (strlen(@$_GET["order"]) > 0) {
		$sOrder = @$_GET["order"];

		// Field convenio_liquidacion_id
		if ($sOrder == "convenio_liquidacion_id") {
			$sSortField = "`convenio_liquidacion_id`";
			$sLastSort = @$_SESSION["convenio_liquidacion_x_convenio_liquidacion_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["convenio_liquidacion_x_convenio_liquidacion_id_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["convenio_liquidacion_x_convenio_liquidacion_id_Sort"] <> "") { $_SESSION["convenio_liquidacion_x_convenio_liquidacion_id_Sort"] = "" ; }
		}

		// Field credito_id
		if ($sOrder == "credito_id") {
			$sSortField = "`credito_id`";
			$sLastSort = @$_SESSION["convenio_liquidacion_x_credito_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["convenio_liquidacion_x_credito_id_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["convenio_liquidacion_x_credito_id_Sort"] <> "") { $_SESSION["convenio_liquidacion_x_credito_id_Sort"] = "" ; }
		}

		// Field monto
		if ($sOrder == "monto") {
			$sSortField = "`monto`";
			$sLastSort = @$_SESSION["convenio_liquidacion_x_monto_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["convenio_liquidacion_x_monto_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["convenio_liquidacion_x_monto_Sort"] <> "") { $_SESSION["convenio_liquidacion_x_monto_Sort"] = "" ; }
		}

		// Field status
		if ($sOrder == "status") {
			$sSortField = "`status`";
			$sLastSort = @$_SESSION["convenio_liquidacion_x_status_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["convenio_liquidacion_x_status_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["convenio_liquidacion_x_status_Sort"] <> "") { $_SESSION["convenio_liquidacion_x_status_Sort"] = "" ; }
		}

		// Field fecha
		if ($sOrder == "fecha") {
			$sSortField = "`fecha`";
			$sLastSort = @$_SESSION["convenio_liquidacion_x_fecha_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["convenio_liquidacion_x_fecha_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["convenio_liquidacion_x_fecha_Sort"] <> "") { $_SESSION["convenio_liquidacion_x_fecha_Sort"] = "" ; }
		}

		// Field fecha_modificacion
		if ($sOrder == "fecha_modificacion") {
			$sSortField = "`fecha_modificacion`";
			$sLastSort = @$_SESSION["convenio_liquidacion_x_fecha_modificacion_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["convenio_liquidacion_x_fecha_modificacion_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["convenio_liquidacion_x_fecha_modificacion_Sort"] <> "") { $_SESSION["convenio_liquidacion_x_fecha_modificacion_Sort"] = "" ; }
		}
		if ($bCtrl) {
			$sOrderBy = @$_SESSION["convenio_liquidacion_OrderBy"];
			$pos = strpos($sOrderBy, $sSortField . " " . $sLastSort);
			if ($pos === false) {
				if ($sOrderBy <> "") { $sOrderBy .= ", "; }
				$sOrderBy .= $sSortField . " " . $sThisSort;
			}else{
				$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
			}
			$_SESSION["convenio_liquidacion_OrderBy"] = $sOrderBy;
		}
		else
		{
			$_SESSION["convenio_liquidacion_OrderBy"] = $sSortField . " " . $sThisSort;
		}
		$_SESSION["convenio_liquidacion_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["convenio_liquidacion_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["convenio_liquidacion_OrderBy"] = $sOrderBy;
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
		$_SESSION["convenio_liquidacion_REC"] = $nStartRec;
	}
	elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}
			elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["convenio_liquidacion_REC"] = $nStartRec;
		}
		else
		{
			$nStartRec = @$_SESSION["convenio_liquidacion_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["convenio_liquidacion_REC"] = $nStartRec;
			}
		}
	}
	else
	{
		$nStartRec = @$_SESSION["convenio_liquidacion_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["convenio_liquidacion_REC"] = $nStartRec;
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
			$_SESSION["convenio_liquidacion_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}
		elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["convenio_liquidacion_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["convenio_liquidacion_OrderBy"] = $sOrderBy;
			if (@$_SESSION["convenio_liquidacion_x_convenio_liquidacion_id_Sort"] <> "") { $_SESSION["convenio_liquidacion_x_convenio_liquidacion_id_Sort"] = ""; }
			if (@$_SESSION["convenio_liquidacion_x_credito_id_Sort"] <> "") { $_SESSION["convenio_liquidacion_x_credito_id_Sort"] = ""; }
			if (@$_SESSION["convenio_liquidacion_x_monto_Sort"] <> "") { $_SESSION["convenio_liquidacion_x_monto_Sort"] = ""; }
			if (@$_SESSION["convenio_liquidacion_x_status_Sort"] <> "") { $_SESSION["convenio_liquidacion_x_status_Sort"] = ""; }
			if (@$_SESSION["convenio_liquidacion_x_fecha_Sort"] <> "") { $_SESSION["convenio_liquidacion_x_fecha_Sort"] = ""; }
			if (@$_SESSION["convenio_liquidacion_x_fecha_modificacion_Sort"] <> "") { $_SESSION["convenio_liquidacion_x_fecha_modificacion_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["convenio_liquidacion_REC"] = $nStartRec;
	}
}
?>
