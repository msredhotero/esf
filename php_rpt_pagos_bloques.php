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
	header('Content-Disposition: attachment; filename=reporte_pagos_agrupados'.date("Y-m-d").'.xls');
}
if ($sExport == "word") {
	header('Content-Type: application/vnd.ms-word');
	header('Content-Disposition: attachment; filename=reporte_pagos.doc');
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
 if ($sExport == "") {
$nDisplayRecs = 500;
$nRecRange = 10;
 }else{
	 #if (empty($_GET["start"]))
	 $_GET["start"] = 1;
	 $nDisplayRecs  = 1000000000; 
	 
	}


// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
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
$filter['x_monto_agrupado_id'] = '';

if($_SESSION["php_project_esf_status_UserRolID"] == 16){
	// SI EL PERFIL  CORRESPONDE CON EL DESPACHO EXTERNO SOLO SE MOSTRARAN LOS CREDITOS Y SOLICITUDES QUE ESTEN EL LA LISTA DE  LA TABLA CREDITO_EN_EXTERNO
	$sqlLista = "SELECT credito_id FROM  gestor_credito where usuario_id = ".$_SESSION["php_project_esf_status_UserID"]." ";
	$rsLista = phpmkr_query($sqlLista, $conn) or die ("Erroe al seleccionar la lista de los que estan en credito_externio". phpmkr_error()."sql :". $sqlLista);
	$x_lis_externo = "";
	while($rowLista = phpmkr_fetch_array($rsLista)){
		$x_lis_externo .= $rowLista["credito_id"]. ", ";		
		}
	$x_lis_externo = trim($x_lis_externo, ", ");
	$sNPWhere2 = " and (credito.credito_id IN ($x_lis_externo) )  ";
	
	
	}

 #if ($sExport != "") {
if(isset($_GET)) {	
	foreach($_GET as $key => $value) {
		
		if(isset($filter[$key]))
			 $filter[$key] = $value;
	}
	
}
 //}

 #if ($sExport == "") {
if(isset($_POST)) {	
	foreach($_POST as $key => $value) {
		if(isset($filter[$key])) $filter[$key] = $value;
	}	
	
	
}
 #}

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


// Handle Reset Command
//ResetCmd();

// Build SQL
$sSql = "SELECT  SUM(vencimiento.total_venc) AS total_venc, credito.credito_id, credito.solicitud_id, credito.credito_status_id, credito.credito_num, vencimiento.vencimiento_num, SUM(vencimiento.importe) as impvenc,SUM(vencimiento.interes) AS interes,SUM(vencimiento.iva) AS iva, SUM(vencimiento.interes_moratorio) AS interes_moratorio, SUM(vencimiento.iva_mor) AS iva_mor , vencimiento.total_venc as tv, recibo.*, solicitud.solicitud_id,  solicitud.promotor_id, solicitud_cliente.cliente_id, cliente.cliente_id
FROM vencimiento join credito 
on credito.credito_id = vencimiento.credito_id join recibo_vencimiento
on recibo_vencimiento.vencimiento_id = vencimiento.vencimiento_id join recibo
on recibo.recibo_id = recibo_vencimiento.recibo_id join solicitud on solicitud.solicitud_id = credito.solicitud_id  join promotor on promotor.promotor_id = solicitud.promotor_id join solicitud_cliente ON solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id";




	
	

// Load Default Filter
$sDefaultFilter = "";
$sGroupBy = "";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = "";

// Build WHERE condition

$sDbWhere = "(vencimiento.vencimiento_status_id in (2,5)) AND (recibo.recibo_status_id = 1) AND ";


/*
if($_POST["x_fecha_desde"]){
	$_SESSION["x_fecha_desde"] = $_POST["x_fecha_desde"];
	$_SESSION["x_fecha_hasta"] = $_POST["x_fecha_hasta"];		
}else{
	if(empty($_SESSION["x_fecha_desde"])){
		$_SESSION["x_fecha_desde"] = $currdate;
		$_SESSION["x_fecha_hasta"] = $currdate;		
	}
}

if($_POST["x_credito_num_filtro"]){
	$_SESSION["x_credito_num_filtro"] = $_POST["x_credito_num_filtro"];

}else{
	if(empty($_SESSION["x_credito_num_filtro"])){
		$_SESSION["x_credito_num_filtro"] = "";
	}
}*/
	
if($filter["x_medio_pago_id"]){	
	$sDbWhere .= "(recibo.medio_pago_id = ".$filter["x_medio_pago_id"].") AND ";
}else{
	if(empty($filter["x_medio_pago_id"]) || $filter["x_medio_pago_id"] == ""){
		$filter["x_medio_pago_id"] = 0;
	}
}

if($filter["x_fecha_desde"] != ""){
	$sDbWhere .= "(recibo.fecha_pago >= '".ConvertDateToMysqlFormat($filter["x_fecha_desde"])."') AND ";
	$sDbWhere .= "(recibo.fecha_pago <= '".ConvertDateToMysqlFormat($filter["x_fecha_hasta"])."') AND ";	
}

if($filter["x_fecha_desde_2"] != ""){
	$sDbWhere .= "(recibo.fecha_registro >= '".ConvertDateToMysqlFormat($filter["x_fecha_desde_2"])."') AND ";
	$sDbWhere .= "(recibo.fecha_registro <= '".ConvertDateToMysqlFormat($filter["x_fecha_hasta_2"])."') AND ";	
}

if($filter["x_credito_num_filtro"] != ""){
	$sDbWhere .= "(credito.credito_num+0 = ".$filter["x_credito_num_filtro"].") AND ";
}


if($_SESSION["php_project_esf_status_UserRolID"] == 12){		//echo "entro en encargado";
						$sSqls = "select responsable_sucursal_id, sucursal_id from responsable_sucursal where usuario_id = ".$_SESSION["php_project_esf_status_UserID"];
						//echo $sSql;
						$rs2 = phpmkr_query($sSqls,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
						if (phpmkr_num_rows($rs2) > 0) {
							$row2 = phpmkr_fetch_array($rs2);
							$_SESSION["php_project_esf_status_ResponsableID"] = $row2["responsable_sucursal_id"];	
							$_SESSION["php_project_esf_SucursalID"] = $row2["sucursal_id"];						
							$bValidPwd = true;
						}
		}
if(!empty($_SESSION["php_project_esf_SucursalID"])){
$filter["x_sucursal_srch"] = $_SESSION["php_project_esf_SucursalID"] ;
}

#echo "<br> sucr ".$filter["x_sucursal_srch"] ."<br>";
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
	 #echo "serc cliente ".$ssrch."<br>";
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





//echo "filter gestor".$filter["x_gestor_srch"]."<br>";
if ((!empty($filter["x_gestor_srch"]))){
	echo "getor entra";
		#si el filtro de gestor esta lleno entonces se debe incluir solo los credito que son de ese gestor
		if($filter["x_gestor_srch"] == 18){
			$sSqlGestor = "SELECT credito_id FROM gestor_credito  ";
		}else{
			
			$sSqlGestor = "SELECT credito_id FROM gestor_credito WHERE usuario_id = ".$filter["x_gestor_srch"] ." ";
			}
		
	
$rsGestor = phpmkr_query($sSqlGestor, $conn) or die ("Error al seleccionar los credito que pertenecen al gestor". phpmkr_error()."sql :".$sSqlGestor);
while ($rowGestor = mysql_fetch_array($rsGestor)){
	$x_listado_creditos_gestor = $x_listado_creditos_gestor.$rowGestor["credito_id"].", ";
	}
	
		$x_listado_creditos_gestor = substr($x_listado_creditos_gestor, 0, strlen($x_listado_creditos_gestor)-2); 		
$sDbWhere .= "((credito.credito_id in ($x_listado_creditos_gestor)) ) AND ";		
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

// se agrega  al condicion del jeuridico
$sHaving = '';
$x_monto_agrupado_id = 200000;
$sGroupBy = " credito.credito_id ";
if($filter["x_monto_agrupado_id"]){
$sHaving = " total_venc >= ".$filter["x_monto_agrupado_id"] ;
}

$x_total_TC = 0;
#echo "****<br>".$filter["x_monto_agrupado_id"];
if($filter["x_monto_agrupado_id"] =='USD'){
	
	#seleccionamos el ultimo velor del tipo de cambio
	$sqlTipoCambio = "SELECT * FROM tipo_cambio ORDER BY tipo_cambio_id DESC";
	
	#echo "<br>***************".$sqlTipoCambio;
	$rswrkTC = phpmkr_query($sqlTipoCambio,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sqlTipoCambio);
		$x_monto_tipo_cambio = 0;
			while ($datawrkTC = phpmkr_fetch_array($rswrkTC)) {
				#echo $datawrkTC["monto"];
				$x_monto_tipo_cambio = $datawrkTC["monto"];
				}
			$x_total_TC = $x_monto_tipo_cambio * 500;
			
			$sHaving = " total_venc >= ".$x_total_TC ;
	}
 
$sWhere =  $sWhere .$sDbWhere_juridico.$sNPWhere2;

if ($sWhere != "") {
	$sSql .= " AND  " . $sWhere;
}
if ($sGroupBy != "") {
	$sSql .= " GROUP BY " . $sGroupBy;
}
if ($sHaving != "") {
	$sSql .= " HAVING " . $sHaving . " "; ;
}

// Set Up Sorting Order
$sOrderBy = "";
//SetUpSortOrder();
if ($sOrderBy != "") {
//	$sSql .= " ORDER BY " . $sOrderBy;
	$sSql .= " ORDER BY recibo.fecha_pago ";
}
//$sSql .= " ORDER BY credito.credito_num+0, vencimiento.vencimiento_num ";
$sSql .= " ORDER BY recibo.fecha_pago desc";
#echo "<br>".$sSql; // Uncomment to show SQL for debugging

?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<?php if ($sExport == "") { ?>
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/skins/aqua/theme.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-es.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<script type="text/javascript" src="ew.js"></script>
<?php } ?>



<script type="text/javascript" src="scripts/jquery-1.4.js"></script>
    <script type="text/javascript" src="scripts/jquery-ui-1.8.custom.min.js"></script>
    <script type="text/javascript" src="scripts/jquery.themeswitcher.js"></script>
    
    
    <script>
  
//Cuando el DOM est� descargado por completo:
$(document).ready(function(){	

	$("#Submit").click(function(){
		$("#x_paginacion").val(1);
		
	});
	
	});
	
	
	

   </script> 
    
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
if($_POST["x_paginacion"] == 1){
	$_GET["start"] = 1;
	}

SetUpStartRec(); // Set Up Start Record Position
?>
<p><span class="phpmaker">REPORTE DE PAGOS POR MONTOS AGRUPADOS
<?php if ($sExport == "") { ?>

<?php if(($_SESSION["php_project_esf_status_UserRolID"] == 1) || ($_SESSION["php_project_esf_status_UserRolID"] == 4) || ($_SESSION["php_project_esf_status_UserRolID"] == 20)){
?>
&nbsp;&nbsp;<a href="php_rpt_pagos_bloques.php?export=excel&x_credito_num_filtro=<?php echo $x_credito_num_filtro; ?>&x_cresta_srch_2=<?php echo $filter["x_cresta_srch_2"];?>&x_cresta_srch=<?php echo $filter["x_cresta_srch"] ;?>&x_fecha_desde=<?php echo ConvertDateToMysqlFormat(@$filter['x_fecha_desde']); ?>&x_fecha_hasta=<?php echo ConvertDateToMysqlFormat(@$filter['x_fecha_hasta']); ?>&x_gestor=<?php echo $filter["x_gestor"];?>&x_promo_srch=<?php echo $filter["x_promo_srch"];?>&x_sucursal_srch=<?php echo$filter['x_sucursal_srch']  ?>&x_fecha_desde_2=<?php echo ConvertDateToMysqlFormat(@$filter['x_fecha_desde_2']); ?>&x_fecha_hasta_2=<?php echo ConvertDateToMysqlFormat(@$filter['x_fecha_hasta_2']); ?>">Exportar a Excel</a><?php } ?><?php } ?>
</span></p>
<p>
<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="phpmaker" style="color: Red;"><?php echo $_SESSION["ewmsg"]; ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>

<?php if ($sExport == "") { ?>

<form action="php_rpt_pagos_bloques.php" name="filtro" id="filtro" method="post">
<input   type="hidden" name="x_paginacion" value="<?php echo $x_paginacion?>"  id="x_paginacion"/>
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
      <td width="148">Monto agrupado:</td>
      <td width="10">&nbsp;</td>
      <td width="119"><?php
		$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);  
		$x_estado_civil_idList = "<select name=\"x_monto_agrupado_id\" id=\"x_monto_agrupado_id\" >";
		//$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		
		$x_estado_civil_idList .= "<option value='500000' selected>$500,000</option>";
		$x_estado_civil_idList .= "<option value='300000' selected>$300,000</option>";
		$x_estado_civil_idList .= "<option value='200000' selected>$200,000</option>";
		$x_estado_civil_idList .= "<option value='100000' selected>$100,000</option>";
		$x_estado_civil_idList .= "<option value='50000' selected>$50,000</option>";
		$x_estado_civil_idList .= "<option value='20000' selected>$20,000</option>";
		$x_estado_civil_idList .= "<option value='10000' selected>$10,000</option>";
		$x_estado_civil_idList .= "<option value='USD' selected> USD 500</option>";
		$x_estado_civil_idList .= "<option value='1' selected>TODOS</option>";	
		
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		
		?></td>
      <td width="123"><?php if($x_total_TC>0){ echo "$".number_format($x_total_TC,"2",".",",");}?></td>
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
      <td>Status de la Solicitud</td>
      <td>&nbsp;</td>
      <td><span class="phpmaker">
        <?php
		$x_estado_civil_idList = "<select name=\"x_cresta_srch\" class=\"texto_normal\">";
		if ($filter["x_credito_status_id_filtro"] == 0){
			$x_estado_civil_idList .= "<option value='100' selected>TODAS</option>";
		}else{
			$x_estado_civil_idList .= "<option value='100' >TODAS</option>";		
		}
		$sSqlWrk = "SELECT `solicitud_status_id`, `descripcion` FROM `solicitud_status`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["solicitud_status_id"] == $filter["x_cresta_srch"]) {
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
      <td>Sucursal</td>
      <td></td>
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
      <td>Medio de Pago:</td>
      <td><?php
$x_medio_pago_idList = "<select name=\"x_medio_pago_id\">";
$x_medio_pago_idList .= "<option value=''>Seleccione</option>";
$x_medio_pago_idList .= "<option value='0'";
if (@$filter["x_medio_pago_id"] == 0) {
	$x_medio_pago_idList .= " selected";
}
$x_medio_pago_idList .=">Todos</option>";
$sSqlWrk = "SELECT medio_pago_id, descripcion FROM medio_pago";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_medio_pago_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["medio_pago_id"] == @$filter["x_medio_pago_id"]) {
			$x_medio_pago_idList .= " selected";
		}
		$x_medio_pago_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_medio_pago_idList .= "</select>";
echo $x_medio_pago_idList;
?></td>
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
      <td><a href="php_rpt_pagos_bloques.php?cmd=reset"></a></td>
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
      <td><div align="right"><span class="phpmaker"> Pago desde: </span></div></td>
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
      <td><div align="right"><span class="phpmaker"> Pago hasta: </span></div></td>
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
      <td><a href="php_rpt_pagos_bloques.php?cmd=reset">Mostrar Todos</a></td>
    </tr>
    <tr>
      <td><div align="right"><span class="phpmaker">Registro desde</span></div></td>
      <td>&nbsp;</td>
      <td><span>
		<input name="x_fecha_desde_2" type="text" id="x_fecha_desde_2" value="<?php echo FormatDateTime(@$filter["x_fecha_desde_2"],7); ?>" size="11">
		&nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_desde_2" alt="Pick a Date" style="cursor:pointer;cursor:hand;">
		<script type="text/javascript">
		Calendar.setup(
		{
		inputField : "x_fecha_desde_2", // ID of the input field
		ifFormat : "%d/%m/%Y", // the date format
		button : "cx_fecha_desde_2" // ID of the button
		}
		);
		</script>
		</span>	</td>
      <td>&nbsp;</td>
      <td><div align="right"><span class="phpmaker">Regritro hasta</span></div></td>
      <td>&nbsp;</td>
      <td><span>
		<input name="x_fecha_hasta_2" type="text" id="x_fecha_hasta_2" value="<?php echo FormatDateTime(@$filter["x_fecha_hasta_2"],7); ?>" size="11">
		&nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_hasta_2" alt="Pick a Date" style="cursor:pointer;cursor:hand;">
		<script type="text/javascript">
		Calendar.setup(
		{
		inputField : "x_fecha_hasta_2", // ID of the input field
		ifFormat : "%d/%m/%Y", // the date format
		button : "cx_fecha_hasta_2" // ID of the button
		}
		);
		</script>
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
      <td>Gestor cobranza</td>
      <td>&nbsp;</td>
      <td><span><!--<select name="x_gestor" > 
      <option value="" >Seleccione </option>     
      <option value="1250" <?php if ($filter["x_gestor"] == 1250){?> selected="selected" <?php }?> >Angelica Tabares </option>
      <option value="16"  <?php if ($filter["x_gestor"] == 16){?> selected="selected" <?php }?>>Monica Flores </option>
      <option value="3615"  <?php if ($filter["x_gestor"] == 615){?> selected="selected" <?php }?> >Marcela Lopez </option>
      <option value="3065"  <?php if ($filter["x_gestor"] == 3065){?> selected="selected" <?php }?> >Josefina Ochoa </option>      
      <option value="4812"  <?php if ($filter["x_gestor"] == 4812){?> selected="selected" <?php }?> >Victoria Garcia </option>      
      <option value="4561"   <?php if ($filter["x_gestor"] == 4561){?> selected="selected" <?php }?>>Rodrigo Sanchez </option>
      <option value="6937"   <?php if ($filter["x_gestor"] == 6937){?> selected="selected" <?php }?>>Mauricio Trejo </option>
      <option value="1"   <?php if ($filter["x_gestor"] == 1){?> selected="selected" <?php }?>>Javier Foncerrada </option>
      <option value="6891"  <?php if ($filter["x_gestor"] == 6891){?> selected="selected" <?php }?>> Cesar Olvera </option>
      </select>-->
      
      <select name="x_gestor" >  
    <option value="0">Seleccionar</option>    
      <option value="7184" <?php if ($x_gestor == 7184){?> selected="selected"<?php }?>>Angelica Tabares</option>
      <option value="7182" <?php if ($x_gestor == 7182){?> selected="selected"<?php }?>>Marcela Lopez</option>
      <option value="7183" <?php if ($x_gestor == 7183){?> selected="selected"<?php }?>>Josefina Ochoa</option>
      <option value="7187" <?php if ($x_gestor == 7187){?> selected="selected"<?php }?>>Victoria Garcia</option>      
      <option value="7180" <?php if ($x_gestor == 7180){?> selected="selected"<?php }?>>Rodrigo Sanchez</option>
      <option value="7179" <?php if ($x_gestor == 7179){?> selected="selected"<?php }?>>Mauricio Trejo</option>
      <option value="7181" <?php if ($x_gestor == 7181){?> selected="selected"<?php }?>>Javier Foncerrada</option>
      <option value="7188" <?php if ($x_gestor == 7188){?> selected="selected"<?php }?>>Cesar Olvera</option>
      </select>
      
      
      
      </span>&nbsp;</td>
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
<?php } ?>


<?php if ($sExport == "") { ?>
<form action="php_rpt_pagos_bloques.php" name="ewpagerform" id="ewpagerform">
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
		if ($nStartRec == 1) {
			$isPrev = False;
		} else {
			$isPrev = True;
			$PrevStart = $nStartRec - $nDisplayRecs;
			if ($PrevStart < 1) { $PrevStart = 1; } ?>
		<a href="php_rpt_pagos_bloques.php?start=<?php echo $PrevStart; ?>&<?php echo $_QS; ?>"><b>Anterior</b></a>
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
		<a href="php_rpt_pagos_bloques.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?></b></a>
					<?php }
					$x += $nDisplayRecs;
					$y += 1;
				} elseif (($x >= ($dx1-$nDisplayRecs*$nRecRange)) && ($x <= ($dx2+$nDisplayRecs*$nRecRange))) {
					if ($x+$nRecRange*$nDisplayRecs < $nTotalRecs) { ?>
		<a href="php_rpt_pagos_bloques.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?>-<?php echo ($y+$nRecRange-1);?></b></a>
					<?php } else {
						$ny=intval(($nTotalRecs-1)/$nDisplayRecs)+1;
							if ($ny == $y) { ?>
		<a href="php_rpt_pagos_bloques.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?></b></a>
							<?php } else { ?>
		<a href="php_rpt_pagos_bloques.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?>-<?php echo $ny; ?></b></a>
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
		<a href="php_rpt_pagos_bloques.php?start=<?php echo $NextStart; ?>&<?php echo $_QS; ?>"><b>Siguiente</b></a>
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
	Registros <?php echo  $nStartRec;  ?> al <?php  echo $nStopRec; ?> de <?php echo  $nTotalRecs; ?>
<?php } else { ?>
	No hay datos
<?php }?>
</span>
		</td>
	</tr>
</table>
</form>
<?php } ?>
<?php if ($nTotalRecs > 0)  { ?>
<form method="post">
<table id="ewlistmain" class="ewTable">
	<!-- Table header -->
	<tr class="ewTableHeader">
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Cr�dito num.
<?php }else{ ?>
Cr�dito num.
<!---
	<a href="php_rpt_pagos_bloques.php?order=<?php //echo urlencode("credito_id"); ?>">Cr�dito num.<?php // if (@$_SESSION["vencimiento_x_credito_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php //} elseif (@$_SESSION["vencimiento_x_credito_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php// } ?></a>
-->    
<?php } ?>
		</span></td>
        <td valign="top"><span>Cliente</span></td>
		<td valign="top"><span>
Venc. No
		</span></td>
        <td valign="top"><span>Status</span></td>
        <td valign="top">Status Anterior</td>  
		<td valign="top">Promotor</td>     
        <td valign="top">Gestor</td>    
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Fecha de pago
<?php }else{ ?>
Fecha de pago

<?php } ?>

		</span></td>
<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Fecha de registro
<?php }else{ ?>
Fecha de registro
<?php } ?>

		</span></td>
		<td valign="top">Banco - Cta.</td>
		<td valign="top">Medio de Pago</td>
		<td valign="top">Ref1</td>
		<td valign="top">Ref2</td>
		<td valign="top"><span>
Importe
		</span></td>
		<td valign="top"><span>
Inter�s
		</span></td>
		<td valign="top">IVA</td>
		<td valign="top"><span>
		  Inter�s moratorio
		  </span></td>
		<td valign="top"><span>        
		  Total de Pago        
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
$nRecActual = 0;
$x_tot_importe = 0;
$x_tot_intereses = 0;
$x_tot_moratorios = 0;
$x_tot_total_pago = 0;

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
		$x_credito_id = "";
		$x_credito_num = $row["credito_num"];
		$x_credito_id = $row["credito_id"];
		$x_solicitud_id = $row["solicitud_id"];
		$x_status = 0;$x_credito_status= "";
		$x_status = "";
		
		//datos del cliente 
$x_cliente = "";
$sqlFondoa = "select * from cliente, solicitud_cliente, credito where cliente.cliente_id= solicitud_cliente.cliente_id and solicitud_cliente.solicitud_id = credito.solicitud_id and credito.credito_id = $x_credito_id ";
$rsFondeoa = phpmkr_query($sqlFondoa,$conn) or die ("Error al seleccionar fodeo");
$rowFondeoa = phpmkr_fetch_array($rsFondeoa);
$x_cliente = $rowFondeoa["nombre_completo"]." ".$rowFondeoa["apellido_paterno"]." ".$rowFondeoa["apellido_materno"];
		
		//echo "credito_id".$x_credito_id."-";
		$sqlStatus = "select credito_status_id from credito where credito_id = $x_credito_id ";
		$rsStatus = phpmkr_query($sqlStatus,$conn)or die("Error al seleccionar el estatus del credito".phpmkr_error().$sqlStatus);
		$rowStatus = phpmkr_fetch_array($rsStatus);
		$x_status = $rowStatus["credito_status_id"];
		if($x_status == 1){
			$x_credito_status = "Activo";
			}else if($x_status == 4){
				$x_credito_status = "Cobranza Externa";
				}else if($x_status == 3){
					$x_credito_status = "Liquidado";
					}else if($x_status == 5){
						$x_credito_status = "Incobrable";
						}
						
						
					if($x_status == 3){	
		$sqlStatus = "select status from credito_liquidado where credito_id = $x_credito_id ";
		$rsStatus = phpmkr_query($sqlStatus,$conn)or die("Error al seleccionar el estatus del credito".phpmkr_error().$sqlStatus);
		$rowStatus = phpmkr_fetch_array($rsStatus);
		$x_status_li = $rowStatus["status"];
		if($x_status_li == 1){
			$x_status = "Activo";
			}else if($x_status_li == 4){
				$x_status = "Cobranza Externa";
				}else if($x_status_li == 3){
					$x_status = "Liquidado";
					}else if($x_status_li == 5){
						$x_status = "Incobrable";
						}	
						
					}else{
						$x_status = "";
						}
		$x_vencimiento_num = $row["vencimiento_num"];
		$x_fecha_pago = $row["fecha_pago"];
		$x_fecha_registro = $row["fecha_registro"];
		$x_importe = $row["impvenc"];
		$x_interes = $row["interes"];
		$x_iva = $row["iva"];		
		$x_interes_moratorio = $row["interes_moratorio"];
		$x_iva_moratorio = $row["iva_mor"];		
		$x_total_venc = $row["total_venc"];		
		
		
		$x_medio_pago_id = $row["medio_pago_id"];
		$x_banco_id = $row["banco_id"];		
		$x_referencia_pago = $row["referencia_pago"];
		$x_referencia_pago_2 = $row["referencia_pago_2"];		


		$x_solicitud_id = $row["solicitud_id"];
		$x_gestor = "";

		$x_tot_importe = $x_tot_importe + $x_importe;
		$x_tot_intereses = $x_tot_intereses + $x_interes;
		$x_tot_iva = $x_tot_iva + $x_iva;		
		$x_tot_moratorios = $x_tot_moratorios + $x_interes_moratorio;
		$x_tot_iva_moratorios = $x_tot_iva_moratorios + $x_iva_moratorio;		
		$x_tot_total_pago = $x_tot_total_pago + $x_total_venc;
		
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>
		<!-- credito_id -->
		<td align="center"><span>
<?php echo $x_credito_num; ?>
</span></td>
<td align="center"><span>
<?php echo $x_cliente; ?>
</span></td>

		<td align="center"><span>
<?php echo $x_vencimiento_num; ?>
</span></td>
<td align="center"><span>
<?php echo $x_credito_status; ?>
</span></td>
<td align="center"><span><strong>
<?php echo $x_status; ?></strong>
</span></td>
		<td align="left"><?php
$sSqlWrk = "SELECT promotor.nombre_completo
FROM solicitud join promotor
on promotor.promotor_id = solicitud.promotor_id
where solicitud.solicitud_id = $x_solicitud_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	$sTmp = $rowwrk["nombre_completo"];
}else{
	$sTmp = "Datos no localizados";
}
echo $sTmp;
@phpmkr_free_result($rswrk);
?></td>
<td align="left">
<?php 
$x_gestor = "";
$x_gestor_id = "";
		$sSqlWrk = "SELECT gestor_credito.usuario_id, usuario.nombre FROM usuario  join gestor_credito on gestor_credito.usuario_id = usuario.usuario_id Where credito_id = $x_credito_id ";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
			$x_gestor = $rowwrk["nombre"];	
			$x_gestor_id = 	$rowwrk["usuario_id"];						
		}else{
			$x_gestor = "";										
		}
		@phpmkr_free_result($rswrk);

echo $x_gestor;

//echo $x_credito_id;
//echo "sql  = ".	$sSqlWrk."-";

?></td>

		<!-- fecha_vencimiento -->
		<td align="center"><span>
<?php echo FormatDateTime($x_fecha_pago,7); ?>
</span></td>
<td align="center"><span>
<?php echo FormatDateTime($x_fecha_registro,7); ?>
</span></td>
		<td align="center" valign="middle"><span><?php
if ((!is_null($x_banco_id)) && ($x_banco_id <> "")) {
	$sSqlWrk = "SELECT nombre, cuenta FROM banco";
	$sTmp = $x_banco_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE banco_id = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		echo $rowwrk["nombre"] . " - " . $rowwrk["cuenta"];
	}
	@phpmkr_free_result($rswrk);
} 
?></span></td>
		<td align="center" valign="middle"><span><?php
if ((!is_null($x_medio_pago_id)) && ($x_medio_pago_id <> "")) {
	$sSqlWrk = "SELECT descripcion FROM medio_pago where medio_pago_id = $x_medio_pago_id";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_medio_pago_id = $x_medio_pago_id; // Backup Original Value
$x_medio_pago_id = $sTmp;
?>
          <?php echo $x_medio_pago_id; ?>
          <?php $x_medio_pago_id = $ox_medio_pago_id; // Restore Original Value ?>
          </span>
          </td>
		<td align="center" valign="middle"><span><?php echo $x_referencia_pago; ?></span></td>
		<td align="center" valign="middle"><span><?php echo $x_referencia_pago_2; ?></span></td>
		<!-- importe -->
		<td align="right"><span>
<?php echo FormatNumber($x_importe,2,0,0,1); ?>
</span></td>
		<!-- interes -->
		<td align="right"><span>
<?php echo FormatNumber($x_interes,2,0,0,1); ?>
</span></td>
		<td align="right"><?php echo FormatNumber($x_iva,2,0,0,1); ?></td>
		<!-- interes_moratorio -->
		<td align="right"><span>
  <?php echo FormatNumber($x_interes_moratorio,2,0,0,1); ?>
</span></td>
		<td align="right"><span>
  <?php echo FormatNumber($x_total_venc,2,0,0,1); ?>
</span></td>
	</tr>
<?php
	}
}
?>

	<tr>
		<td align="left" colspan="9"><span>
        <strong>
        Totales de la P&aacute;gina:&nbsp;        </strong>
</span></td>
		<td align="right">&nbsp;</td>
		<td align="right">&nbsp;</td>
		<td align="right">&nbsp;</td>
		<td align="right">&nbsp;</td>
		<!-- importe -->
		<td align="right"><span>
        <strong>
<?php echo FormatNumber($x_tot_importe,2,0,0,1); ?></strong>
</span></td>
		<!-- interes -->
		<td align="right"><span>
        <strong>
<?php echo FormatNumber($x_tot_intereses,2,0,0,1); ?></strong>
</span></td>
		<td align="right"><strong><?php echo FormatNumber($x_tot_iva,2,0,0,1); ?></strong></td>
		<!-- interes_moratorio -->
		<td align="right"><span>
		  <strong>
  <?php echo FormatNumber($x_tot_moratorios,2,0,0,1); ?></strong>
</span></td>
		<td align="right"><span><strong>
  <?php echo FormatNumber($x_tot_total_pago,2,0,0,1); ?>
		  </strong>
</span></td>
	</tr>
</table>
</form>
<?php } ?>
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
			$sSortField = "`credito_num+0`";
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
			$sSortField = "`fecha_pago`";
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
		$_SESSION["vencimiento_OrderBy"] = $sOrderBy;
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
			$_SESSION["x_fecha_desde"] = "";
			$_SESSION["x_fecha_hasta"] = "";		
			$_SESSION["x_credito_num_filtro"] = "";	
			$_SESSION["x_medio_pago_id"] = 0;
			$_SESSION["x_promo_srch"] = 0;
			
		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["vencimiento_searchwhere"] = $sSrchWhere;
			$_SESSION["x_fecha_desde"] = "";
			$_SESSION["x_fecha_hasta"] = "";		
			$_SESSION["x_credito_num_filtro"] = "";	
			$_SESSION["x_medio_pago_id"] = 0;
			$_SESSION["x_promo_srch"] = 0;			

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
