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

// Initialize common variables
$x_credito_id = Null; 
$ox_credito_id = Null;
$x_credito_tipo_id = Null; 
$ox_credito_tipo_id = Null;
$x_solicitud_id = Null; 
$ox_solicitud_id = Null;
$x_credito_status_id = Null; 
$ox_credito_status_id = Null;
$x_fecha_otrogamiento = Null; 
$ox_fecha_otrogamiento = Null;
$x_importe = Null; 
$ox_importe = Null;
$x_tasa = Null; 
$ox_tasa = Null;
$x_plazo = Null; 
$ox_plazo = Null;
$x_fecha_vencimiento = Null; 
$ox_fecha_vencimiento = Null;
$x_tasa_moratoria = Null; 
$ox_tasa_moratoria = Null;
$x_medio_pago_id = Null; 
$ox_medio_pago_id = Null;
$x_referencia_pago = Null; 
$ox_referencia_pago = Null;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=credito.xls');
}
if ($sExport == "word") {
	header('Content-Type: application/vnd.ms-word');
	header('Content-Disposition: attachment; filename=credito.doc');
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
$nDisplayRecs = 25;
$nRecRange = 10;

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

// Handle Reset Command
ResetCmd();












//diferente de dos para que entre ene todos los casos tipo 1, 3,4
if(($filter["x_credito_tipo_id"] != 2)  ){
 //echo "entra a diferente de dos";
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
	if((!empty($filter["x_crenum_srch"])) || (!empty($filter["x_cresta_srch"])) || (!empty($filter["x_credito_tipo_id"])) ){
		$ssrch_cre = "";
		
		//si se selecciono algun tipo de credito
		if(!empty($filter["x_credito_tipo_id"])){
		//si se selecciono pero no es TODOS que tiene un valor de 100
				if(!empty($filter["x_credito_tipo_id"]) && ($filter["x_credito_tipo_id"] != "100")){
				$ssrch_cre .= "(credito.credito_tipo_id = ".$filter["x_credito_tipo_id"].") AND ";
						}	
		}
		
		
		
		if(!empty($filter["x_crenum_srch"])){
			$ssrch_cre .= "(credito.credito_num+0 = ".$filter["x_crenum_srch"].") AND ";
		}
		if(!empty($filter["x_cresta_srch"]) && ($filter["x_cresta_srch"] != "100")){
			$ssrch_cre .= "(credito.credito_status_id = ".$filter["x_cresta_srch"].") AND ";
		}
		if(strlen($ssrch_cre) > 0 ){
			//$ssrch_cre = substr($ssrch_cre, 0, strlen($ssrch_cre)-5);	
		}else{
			$ssrch_cre = "";
		}
	}else{
		$ssrch_cre = "";
	}
	
	if(!empty($filter["x_empresa_id"])){}


/*
SELECT credito.* FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id WHERE 

(credito.credito_id in 
 
(select credito_id from fondeo_colocacion join fondeo_credito on fondeo_credito.fondeo_credito_id = fondeo_colocacion.fondeo_credito_id join fondeo_empresa on fondeo_empresa.fondeo_empresa_id = fondeo_credito.fondeo_empresa_id where (fondeo_empresa.fondeo_empresa_id = 7) AND (fondeo_credito.fondeo_credito_id = 7)
																																																																					 
																																																																					 )
																																																																											
																																																																											ORDER BY credito.credito_num+0 desc
																																																																																																																			 */
																																																																																																																			  
																																																																																																																			  if ((!empty($filter["x_sucursal_srch"])) && (!empty($filter["x_promo_srch"]))){
																																																																																																																			   // se unen los dos queries..
		if(($filter["x_sucursal_srch"] != "1000") && ($filter["x_promo_srch"] != "1000"  ) ){
			$ssrch_cre .= "(promotor.sucursal_id = ".$filter["x_sucursal_srch"].") AND (promotor.promotor_id = ".$filter["x_promo_srch"].") AND ";
			$ssrch_cre = substr($ssrch_cre, 0, strlen($ssrch_cre)-5);
			}												
															  
																																																																																																																				   else if((!empty($filter["x_sucursal_srch"]))){
		if((!empty($filter["x_sucursal_srch"])) && ($filter["x_sucursal_srch"] != "1000")){
			$ssrch_cre .= "(promotor.sucursal_id = ".$filter["x_sucursal_srch"].") AND ";
			$ssrch_cre = substr($ssrch_cre, 0, strlen($ssrch_cre)-5);		
		}
	}else if(!empty($filter["x_promo_srch"])){
		if((!empty($filter["x_promo_srch"])) && ($filter["x_promo_srch"] != "1000")){
			$ssrch_cre .= "(promotor.promotor_id = ".$filter["x_promo_srch"].") AND ";
			$ssrch_cre = substr($ssrch_cre, 0, strlen($ssrch_cre)-5);		
		}
	}	
 }
	
	
	if ((!empty($filter["x_gestor_srch"]))){
		#si el filtro de gestor esta lleno entonces se debe incluir solo los credito que son de ese gestor
		if($filter["x_gestor_srch"] == 18){
			$sSqlGestor = "SELECT credito_id FROM gestor_credito  ";
		}else{
			
			$sSqlGestor = "SELECT credito_id FROM gestor_credito WHERE usuario_id = ".$filter["x_gestor_srch"]." ";
			}
		
	
$rsGestor = phpmkr_query($sSqlGestor, $conn) or die ("Error al seleccionar los credito que pertenecen al gestor". phpmkr_error()."sql :".$sSqlGestor);
while ($rowGestor = mysql_fetch_array($rsGestor)){
	$x_listado_creditos_gestor = $x_listado_creditos_gestor.$rowGestor["credito_id"].", ";
	}
	
		$x_listado_creditos_gestor = substr($x_listado_creditos_gestor, 0, strlen($x_listado_creditos_gestor)-2); 		
$sCreW .= "((credito.credito_id in ($x_listado_creditos_gestor)) ) AND ";		
		}
	
	/*
	// Get Search Criteria for Basic Search
	//SetUpBasicSearch();
	
	// Build Search Criteria
	
	if ($sSrchAdvanced != "") {
		$sSrchWhere = $sSrchAdvanced; // Advanced Search
	}
	elseif ($sSrchBasic != "") {
		$sSrchWhere = $sSrchBasic; // Basic Search
	}
	
	
	// Save Search Criteria
	if ($sSrchWhere != "") {
		$_SESSION["credito_searchwhere"] = $sSrchWhere;
	
		// Reset start record counter (new search)
		$nStartRec = 1;
		$_SESSION["credito_REC"] = $nStartRec;
	}
	else
	{
		$sSrchWhere = @$_SESSION["credito_searchwhere"];
	}
	*/
	
	// filtros para roles
	


	
	
	
	$x_join = "";
	if($_SESSION["php_project_esf_status_UserRolID"] == 11){
		// solo se toma en cuenta los credito de finafim
		$x_join = " JOIN fondeo_colocacion ON fondeo_colocacion.credito_id = credito.credito_id";
		
		}
	
	
	if($_SESSION["php_project_esf_status_UserRolID"] == 7) {
		$sSql = "SELECT credito.* FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id $x_join";
	}else{
		$sSql = "SELECT credito.* FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id $x_join";
	
//	$sSql = "SELECT credito.* FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id ";
	}


	
}else{
//grupos
 //echo "entra a asociados";

	// EN clientes
	if((!empty($filter["x_nombre_srch"])) || (!empty($filter["x_apepat_srch"])) || (!empty($filter["x_apemat_srch"])) || (!empty($filter["x_clinum_srch"]))){
	
		$ssrch = "";
		if(!empty($filter["x_clinum_srch"])){
			$ssrch .= "(cliente.cliente_num+0 = ".$filter["x_clinum_srch"].") AND ";
		}
		
		if(!empty($filter["x_nombre_srch"])){
			$ssrch .= "(solicitud.grupo_nombre like '%".$filter["x_nombre_srch"]."%') AND ";
		}
		if(!empty($filter["x_apepat_srch"])){
			$ssrch .= "(solicitud.grupo_nombre like '%".$filter["x_apepat_srch"]."%') AND ";
		}
		if(!empty($filter["x_apemat_srch"])){
			$ssrch .= "(solicitud.grupo_nombre like '%".$filter["x_apemat_srch"]."%') AND ";
		}
	
		$ssrch = substr($ssrch, 0, strlen($ssrch)-5);
		
		$ssrch_sql = "select solicitud.solicitud_id from solicitud where ".$ssrch;
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
	
	
	
if ((!empty($filter["x_gestor_srch"]))){
		#si el filtro de gestor esta lleno entonces se debe incluir solo los credito que son de ese gestor
		if($filter["x_gestor_srch"] == 18){
			$sSqlGestor = "SELECT credito_id FROM gestor_credito  ";
		}else{
			
			$sSqlGestor = "SELECT credito_id FROM gestor_credito WHERE usuario_id = ".$filter["x_gestor_srch"]." ";
			}
		
	
$rsGestor = phpmkr_query($sSqlGestor, $conn) or die ("Error al seleccionar los credito que pertenecen al gestor". phpmkr_error()."sql :".$sSqlGestor);
while ($rowGestor = mysql_fetch_array($rsGestor)){
	$x_listado_creditos_gestor = $x_listado_creditos_gestor.$rowGestor["credito_id"].", ";
	}
	
		$x_listado_creditos_gestor = substr($x_listado_creditos_gestor, 0, strlen($x_listado_creditos_gestor)-2); 		
$sCreW .= "((credito.credito_id in ($x_listado_creditos_gestor)) ) AND ";		
		}	
	
	
	
	// En Credito
	
	$ssrch_cre .= "(credito.credito_tipo_id = 2m) AND ";
	
	if((!empty($filter["x_crenum_srch"])) || (!empty($filter["x_cresta_srch"])) || (!empty($filter["x_crenum_srch"]) )){
	//echo "entra al credito";
		$ssrch_cre = "";
		if(!empty($filter["x_crenum_srch"])){
			$ssrch_cre .= "(credito.credito_num+0 = ".$filter["x_crenum_srch"].") AND ";
		}
		if(!empty($filter["x_cresta_srch"]) && ($filter["x_cresta_srch"] != "100")){
			$ssrch_cre .= "(credito.credito_status_id = ".$filter["x_cresta_srch"].") AND ";
		}
			$ssrch_cre .= "(credito.credito_tipo_id = 2) AND "; 
		if(strlen($ssrch_cre) > 0 ){
			//$ssrch_cre = substr($ssrch_cre, 0, strlen($ssrch_cre)-5);	
		}else{
			$ssrch_cre = "";
		}
	}else{
		$ssrch_cre = "";
	}
	
	if(!empty($filter["x_empresa_id"])){
		if(!empty($filter["x_empresa_id"]) && ($filter["x_empresa_id"] != "999999999")){
			$ssrch_cre .= "(credito.credito_id in (select credito_id from  fondeo_colocacion join fondeo_credito on fondeo_credito.fondeo_credito_id = fondeo_colocacion.fondeo_credito_id join fondeo_empresa on fondeo_empresa.fondeo_empresa_id = fondeo_credito.fondeo_empresa_id where fondeo_empresa.fondeo_empresa_id = ".$filter["x_empresa_id"].")) AND ";

			if(!empty($filter["x_fondeo_credito_id"])){
				$ssrch_cre .= "(fondeo_credito.fondeo_credito_id = ".$filter["x_fondeo_credito_id"].") AND ";
			}

			$ssrch_cre = substr($ssrch_cre, 0, strlen($ssrch_cre)-5);		
		}
		
	}
	
	
	
	
	
	if(!empty($filter["x_sucursal_srch"])){
		if((!empty($filter["x_sucursal_srch"])) && ($filter["x_sucursal_srch"] != "1000")){
			$ssrch_cre .= "(promotor.sucursal_id = ".$filter["x_sucursal_srch"].") AND ";
			$ssrch_cre = substr($ssrch_cre, 0, strlen($ssrch_cre)-5);		
		}
	}
	
	
	if ((!empty($filter["x_gestor_srch"]))){
		#si el filtro de gestor esta lleno entonces se debe incluir solo los credito que son de ese gestor
		
		$sSqlGestor = "SELECT credito_id FROM gestor_credito WHERE usuario_id = ".$filter["x_gestor_srch"]." ";
$rsGestor = phpmkr_query($sSqlGestor, $conn) or die ("Error al seleccionar los credito que pertenecen al gestor". phpmkr_error()."sql :".$sSqlGestor);
while ($rowGestor = mysql_fetch_array($rsGestor)){
	$x_listado_creditos_gestor = $x_listado_creditos_gestor.$rowGestor["credito_id"].", ";
	}
	
		$x_listado_creditos_gestor = substr($x_listado_creditos_gestor, 0, strlen($x_listado_creditos_gestor)-2); 		
$sCreW .= "((credito.credito_id in ($x_listado_creditos_gestor)) ) AND ";		
		}
	

	
	
	$x_join = "";
	if($_SESSION["php_project_esf_status_UserRolID"] == 11){
		// solo se toma en cuenta los credito de finafim
		$x_join = " JOIN fondeo_colocacion ON fondeo_colocacion.credito_id = credito.credito_id";		
		}
	
	if($_SESSION["php_project_esf_status_UserRolID"] == 7) {
		$sSql = "SELECT credito.* FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id  $x_join ";
	}else{
		$sSql = "SELECT credito.* FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id  $x_join ";

	}	
	
}





// Load Default Filter
$sDefaultFilter = "";
$sGroupBy = "";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = " credito.cliente_num+0 desc ";


// filtor para el tipo de rol de externos

	// contamos sus creditos activos
		$sqlActivos = "SELECT COUNT( * ) as total_activos , cliente_num FROM  `credito` WHERE  `credito_status_id` =1 GROUP BY  `cliente_num` ";
		$rsAcytivos = phpmkr_query($sqlActivos,$conn)or die ("Error al seleccionar los activos".phpmkr_error()."sql:".$sqlActivos);
		while($rowActivos = phpmkr_fetch_array($rsAcytivos)){
		$x_activos = $rowActivos["total_activos"];
		$x_cliente = $rowActivos["cliente_num"];
		//echo "-".$x_activos;
		if($x_activos > 1){
			$x_lista_cliente = $x_lista_cliente.$x_cliente.", ";
			}
		}
	 $x_lista_cliente = trim($x_lista_cliente,", ");
		 
$sSql = "SELECT credito.* FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id $x_join where cliente_num in ($x_lista_cliente) and credito_status_id = 1  ";




$sWhere = $ssrch_cli.$ssrch_cre.$sNPWhere.$sCreW.$sDbWhereEncargado.$ssrch_vend ;



if ($sDbWhereDetail <> "") {
	$sDbWhere .= "(" . $sDbWhereDetail . ") AND ";
}
if ($sSrchWhere <> "") {
	$sDbWhere .= "(" . $sSrchWhere . ") AND ";
}
if (strlen($sDbWhere) > 5) {
	$sDbWhere = substr($sDbWhere, 0, strlen($sDbWhere)-5); // Trim rightmost AND
}

if ($sDefaultFilter <> "") {
	$sWhere .= "(" . $sDefaultFilter . ") AND ";
}
if ($sDbWhere <> "") {
	if ($sWhere <> "") {	
		$sWhere .= " AND (" . $sDbWhere . ") AND ";
	}else{
		$sWhere .= " (" . $sDbWhere . ") AND ";		
	}
}
if (substr($sWhere, -5) == " AND ") {
	$sWhere = substr($sWhere, 0, strlen($sWhere)-5);
}
if ($sWhere != "") {
	$sSql .= " WHERE " . $sWhere;
}else{
	
	
	
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

#################################
#################################
#echo $sSql; // Uncomment to show SQL for debugging

?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<script src="lineafondeohint.js"></script>

<script language="javascript" src="concilia_cheque.js"></script>
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


//echo "QUERY  ".$sSql;
// Set up Record Set
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$nTotalRecs = phpmkr_num_rows($rs);
if ($nDisplayRecs <= 0) { // Display All Records
	$nDisplayRecs = $nTotalRecs;
}
$nStartRec = 1;
SetUpStartRec(); // Set Up Start Record Position
?>
<p><span class="phpmaker">CREDITOS
ACTIVOS DOBLES <?php if ($sExport == "") { ?>


<?php if(($_SESSION["php_project_esf_status_UserRolID"] == 1) || ($_SESSION["php_project_esf_status_UserRolID"] == 4)){
?>
&nbsp;&nbsp;<a href="php_listo_credito_activo_doble.php?export=excel">Exportar a Excel</a>
&nbsp;&nbsp;<a href="php_listo_credito_activo_doble.php?export=word">Exportar a Word</a>
<?php } ?>
<?php } ?>
</span></p>
<?php if ($sExport == "") { ?>
<form action="php_listo_credito_activo_doble.php" name="filtros" method="post">
</form>
<?php } ?>
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
<form action="php_listo_credito_activo_doble.php" name="ewpagerform" id="ewpagerform">
<table class="ewTablePager">
	<tr>
		<td nowrap>
<span class="phpmaker">
<?php


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
		<a href="php_listo_credito_activo_doble.php?start=<?php echo $PrevStart; ?>&<?php echo $_QS; ?>"><b>Anterior</b></a>
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
		<a href="php_listo_credito_activo_doble.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?></b></a>
					<?php }
					$x += $nDisplayRecs;
					$y += 1;
				} elseif (($x >= ($dx1-$nDisplayRecs*$nRecRange)) && ($x <= ($dx2+$nDisplayRecs*$nRecRange))) {
					if ($x+$nRecRange*$nDisplayRecs < $nTotalRecs) { ?>
		<a href="php_listo_credito_activo_doble.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?>-<?php echo ($y+$nRecRange-1);?></b></a>
					<?php } else {
						$ny=intval(($nTotalRecs-1)/$nDisplayRecs)+1;
							if ($ny == $y) { ?>
		<a href="php_listo_credito_activo_doble.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?></b></a>
							<?php } else { ?>
		<a href="php_listo_credito_activo_doble.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?>-<?php echo $ny; ?></b></a>
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
		<a href="php_listo_credito_activo_doble.php?start=<?php echo $NextStart; ?>&<?php echo $_QS; ?>"><b>Siguiente</b></a>
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
<?php if ($sExport == "") { ?>

<!--<td>&nbsp;</td>-->
<?php } ?>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Cr&eacute;dito No
<?php }else{ ?>
	<a href="php_listo_credito_activo_doble.php?order=<?php echo urlencode("credito_id"); ?>">Cr&eacute;dito No<?php if (@$_SESSION["credito_x_credito_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["credito_x_credito_id_Sort"] == "ASC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		        
		<td valign="top"><span>
Promotor
		</span></td> 
        <td valign="top"><span>
Gestor
		</span></td>        
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Cliente No
<?php }else{ ?>
	<a href="php_listo_credito_activo_doble.php?order=<?php echo urlencode("cliente_id"); ?>">Cliente No<?php if (@$_SESSION["credito_x_cliente_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["credito_x_cliente_id_Sort"] == "ASC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
        
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Cliente
<?php }else{ ?>
Cliente
<!---
	<a href="php_listo_credito_activo_doble.php?order=<?php //echo urlencode("cliente"); ?>">Cliente<?php //if (@$_SESSION["credito_x_cliente_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php //} elseif (@$_SESSION["credito_x_cliente_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php //} ?></a>
-->    
<?php } ?>
		</span></td>		
				        
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Tipo
<?php }else{ ?>
	<a href="php_listo_credito_activo_doble.php?order=<?php echo urlencode("credito_tipo_id"); ?>">Tipo<?php if (@$_SESSION["credito_x_credito_tipo_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["credito_x_credito_tipo_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Status
<?php }else{ ?>
	<a href="php_listo_credito_activo_doble.php?order=<?php echo urlencode("credito_status_id"); ?>">Status<?php if (@$_SESSION["credito_x_credito_status_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["credito_x_credito_status_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Fecha de otorgamiento
<?php }else{ ?>
	<a href="php_listo_credito_activo_doble.php?order=<?php echo urlencode("fecha_otrogamiento"); ?>">Fecha de otrogamiento<?php if (@$_SESSION["credito_x_fecha_otrogamiento_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["credito_x_fecha_otrogamiento_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>

		<td valign="top"><span>
  <?php if ($sExport <> "") { ?>
		  Importe
  <?php }else{ ?>
		  <a href="php_listo_credito_activo_doble.php?order=<?php echo urlencode("importe"); ?>">Importe<?php if (@$_SESSION["credito_x_importe_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["credito_x_importe_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
  <?php } ?>
		  </span></td>
		<?php if (($_SESSION["php_project_esf_status_UserRolID"] ==3 ) ){
	?>
	<?php
	}?>    
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
		
	
		
		$x_credito_id = $row["credito_id"];
		$x_credito_num = $row["credito_num"];		
		$x_cliente_num = $row["cliente_num"];				
		$x_credito_tipo_id = $row["credito_tipo_id"];
		$x_solicitud_id = $row["solicitud_id"];
		$x_credito_status_id = $row["credito_status_id"];
		$x_fecha_otrogamiento = $row["fecha_otrogamiento"];
		$x_importe = $row["importe"];
		$x_tasa = $row["tasa"];
		$x_iva = $row["iva"];		
		$x_plazo = $row["plazo_id"];
		$x_fecha_vencimiento = $row["fecha_vencimiento"];
		$x_tasa_moratoria = $row["tasa_moratoria"];
		$x_medio_pago_id = $row["medio_pago_id"];
		$x_referencia_pago = $row["referencia_pago"];
		$x_forma_pago_id = $row["forma_pago_id"];		
		$x_num_pagos = $row["num_pagos"];				
		$x_tdp = $row["tarjeta_num"];
		$x_fecha_cobranza_externa =  $row["fecha_cobranza_externa"];
		$x_fecha_incobrable = $row["fecha_incobrable"];
		
			// contamos sus creditos activos
		$sqlActivos = "SELECT * from credito WHERE  `credito_status_id` = 1 and credito_id = $x_credito_id ";
		$rsAcytivos = phpmkr_query($sqlActivos,$conn)or die ("Error al seleccionar los activos".phpmkr_error()."sql:".$sqlActivos);
		$rowActivos = phpmkr_fetch_array($rsAcytivos);
		$x_activos = $rowActivos["total_activos"];
	//	echo $sqlActivos;
		//while($rowActivos = phpmkr_fetch_array($rsAcytivos)){
	//	if($x_activos > 1){
		$x_credito_id = $rowActivos["credito_id"];
		$x_credito_num =$rowActivos["credito_num"];	
		//echo "num".	$x_credito_num;
		$x_cliente_num = $rowActivos["cliente_num"];				
		$x_credito_tipo_id = $rowActivos["credito_tipo_id"];
		$x_solicitud_id = $rowActivos["solicitud_id"];
		$x_credito_status_id = $rowActivos["credito_status_id"];
		$x_fecha_otrogamiento = $rowActivos["fecha_otrogamiento"];
		$x_importe = $rowActivos["importe"];
		$x_tasa = $rowActivos["tasa"];
		$x_iva = $rowActivos["iva"];		
		$x_plazo = $rowActivos["plazo_id"];
		$x_fecha_vencimiento = $rowActivos["fecha_vencimiento"];
		$x_tasa_moratoria = $rowActivos["tasa_moratoria"];
		$x_medio_pago_id = $rowActivos["medio_pago_id"];
		$x_referencia_pago = $rowActivos["referencia_pago"];
		$x_forma_pago_id = $rowActivos["forma_pago_id"];		
		$x_num_pagos = $rowActivos["num_pagos"];				
		$x_tdp = $rowActivos["tarjeta_num"];
		$x_fecha_cobranza_externa =  $rowActivos["fecha_cobranza_externa"];
		$x_fecha_incobrable = $rowActivos["fecha_incobrable"];
		
		$x_cocilia_cheque_id = 0;
		$x_status_conciliado = 0;
		$sqlConciliaCheque = "SELECT cheque_conciliado_id, status FROM cheque_conciliado WHERE credito_id = $x_credito_id";
		$rsConciliaCheque = phpmkr_query($sqlConciliaCheque, $conn) or die ("erroe al seleccionar la conciliacion de cheque". phpmkr_error()."sql:". $sqlConciliaCheque);
		$rowConcilia = phpmkr_fetch_array($rsConciliaCheque);
		$x_cocilia_cheque_id = $rowConcilia["cheque_conciliado_id"];
		$x_status_conciliado = $rowConcilia["status"];
		
		$sqlFormato = "SELECT formato_nuevo, fecha_otorga_credito FROM  solicitud WHERE solicitud_id = $x_solicitud_id ";
		$responseFormato = phpmkr_query($sqlFormato,$conn) or die("error en formato".phpmkr_error."sql".$sqlFormato);
		$rowFormato = phpmkr_fetch_array($responseFormato);
		$x_formato_nuevo = $rowFormato["formato_nuevo"];
		$x_fecha_alta = $rowFormato["fecha_otorga_credito"];
		phpmkr_free_result($rowFormato);	
		$x_link_edit="";
		$x_link_view="";
		$x_link_print="";
		
		$x_link_edit_credito = "";
		$x_link_view_credito = "";
		$x_fecha_visita_supervision= "";
		$sqlFS = "SELECT fecha_visita_supervision FROM solicitud WHERE solicitud_id = $x_solicitud_id ";
		$rsFS = phpmkr_query($sqlFS, $conn) or die ("Error al buscar la fceha de supervison del cliente".phpmkr_error()."sql:".$sqlFS);
		$rowFS = phpmkr_fetch_array($rsFS);
		$x_fecha_visita_supervision = $rowFS["fecha_visita_supervision"];
		if (($x_fecha_visita_supervision  == "1999-11-30") || ($x_fecha_visita_supervision  == "0000-00-00")) {
			$x_fecha_visita_supervision = "";
			}else{
				if(is_null($x_fecha_visita_supervision)){
					$x_fecha_visita_supervision = "";
					}
				$x_fecha_visita_supervision = "SUPERVISIÓN ".$x_fecha_visita_supervision;
				}
		if (strlen($x_fecha_visita_supervision)< 15){
			$x_fecha_visita_supervision = "";
			}		
		
		if($x_credito_tipo_id == 1){
			if($x_formato_nuevo == 0){
				$x_link_edit ="php_solicitudedit.php";
				$x_link_view = "php_solicitudview.php";
				$x_link_print = "php_solicitud_print.php";
				}else if($x_formato_nuevo == 1){
					$x_link_edit ="modulos/php_solicitudeditIndividual.php";
					$x_link_view = "";
					//$x_link_print = "modulos/tipoCuenta/formatos/php_solicitudIndividualP_print.php";
					$x_link_print = "modulos/php_solicitudeditIndividual.php";
					}
			}else if($x_credito_tipo_id == 2){
				if($x_formato_nuevo == 0){
					$x_link_edit ="";
					$x_link_view = "";
					$x_link_print = "php_solicitud_caedit.php";
					}else if($x_formato_nuevo == 1){
						$x_link_edit ="modulos/php_solicitudeditSolidario.php";
						$x_link_view = "";
						//$x_link_print = "modulos/tipoCuenta/formatos/php_solicitudSolidario_print.php";
						$x_link_print = "modulos/php_solicitudeditSolidario.php";
						}
				}else if($x_credito_tipo_id == 3){
					 if($x_formato_nuevo == 1){
							$x_link_edit ="modulos/php_solicitudeditMaquinaria.php";
							$x_link_view = "";
							$x_link_print = "modulos/tipoCuenta/formatos/php_solicitudMaquinaria_print.php";
							}
					}else if($x_credito_tipo_id == 4){
						 if($x_formato_nuevo == 1){
								$x_link_edit ="modulos/php_solicitudeditPYME.php";
								$x_link_view = "";
								$x_link_print = "modulos/tipoCuenta/formatos/php_solicitudPYME_print.php";
								}
						}else if($x_credito_tipo_id == 5){
								$x_link_edit ="modulos/php_solicitudeditIndividual.php";
								$x_link_view = "";				
								$x_link_print = "modulos/php_solicitudeditIndividual.php";					
							
							}
					if($x_credito_tipo_id == 5){
						//es un credito revolvente; se manda aotra pagina
						$x_link_edit_credito = "php_credito_revolventeedit.php";
						$x_link_view_credito = "php_credito_revolventeview.php";
						}	else{
							// se va ala normal
							$x_link_edit_credito = "php_creditoedit.php";
							$x_link_view_credito = "php_creditoview.php";
							}	
		
		
		
		
		
		

		if($x_credito_tipo_id != 2){
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

		
		
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>
<?php if ($sExport == "") { ?>

<?php if($x_credito_tipo_id != 2) {?>

<?php } ?>
<!--<?php if ((($x_credito_status_id == 1) || ($x_credito_status_id == 4)) && ($_SESSION["php_project_esf_status_UserRolID"] == 1)) { ?>
<td><span class="phpmaker"><a href="<?php if ($x_credito_id <> "") {echo "php_creditoelimina.php?credito_id=" . urlencode($x_credito_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Eliminar</a></span></td>
<?php }else{ ?>
<td><span class="phpmaker"></span></td>
<?php } ?>-->

<?php } ?>
		<!-- credito_id -->
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
		$x_gestor = "";
		$sSqlWrk = "SELECT promotor.nombre_completo FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id join gestor_credito on gestor_credito.credito_id = credito.credito_id Where credito.credito_id = $x_credito_id ";
		$sSqlWrk = "SELECT gestor_credito.usuario_id, usuario.nombre FROM usuario  join gestor_credito on gestor_credito.usuario_id = usuario.usuario_id Where credito_id = $x_credito_id ";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
			$x_gestor = $rowwrk["nombre"];								
		}else{
			$x_gestor = "";										
		}
		@phpmkr_free_result($rswrk);

echo $x_gestor; 

?>

</span></td>

		<!-- solicitud_id -->
		<td><span>
        <?php echo $x_cliente_num; ?>
</span></td>
		<td align="left"><span>
<?php echo htmlentities($x_cliente); ?>
</span></td>
		
        

		<!-- credito_tipo_id -->
		<td><span>
<?php
if ((!is_null($x_credito_tipo_id)) && ($x_credito_tipo_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `credito_tipo`";
	$sTmp = $x_credito_tipo_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `credito_tipo_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
		if ($sTmp  == "PERSONAL"){
			$sTmp  = "P";
			}else if($sTmp  == "SOLIDARIO") {
				$sTmp = "S";
				}
		
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_credito_tipo_id = $x_credito_tipo_id; // Backup Original Value
$x_credito_tipo_id = $sTmp;
?>
<?php echo $x_credito_tipo_id; ?>
<?php $x_credito_tipo_id = $ox_credito_tipo_id; // Restore Original Value ?>
</span></td>
		<!-- credito_status_id -->
		<td><span>
<?php
if ((!is_null($x_credito_status_id)) && ($x_credito_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `credito_status`";
	$sTmp = $x_credito_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `credito_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_credito_status_id = $x_credito_status_id; // Backup Original Value
$x_credito_status_id = $sTmp;
?>
<?php echo $x_credito_status_id; ?>
<?php $x_credito_status_id = $ox_credito_status_id; // Restore Original Value ?>
</span></td>
		<!-- fecha_otrogamiento -->
		<td><span>
<?php echo FormatDateTime($x_fecha_otrogamiento,7); ?>



</span></td>

		<!-- importe -->
		<td align="right"><span>
<?php echo (is_numeric($x_importe)) ? FormatNumber($x_importe,2,0,0,1) : $x_importe; ?>
</span></td>
		<!-- tasa -->
		<!-- tasa_moratoria -->
		<!-- plazo -->		<!-- fecha_vencimiento -->
		<!-- medio_pago_id -->		<!-- referencia_pago -->
		<!-- fecha_otrogamiento -->
		<!-- fecha_otrogamiento -->
		<?php if (($_SESSION["php_project_esf_status_UserRolID"] ==3 ) ){
	?>
	<?php
	}?>
</tr>
<?php
	//}
}

}
?>

</table>
<?php if ($sExport == "") { ?>
<?php if ($nRecActual > 0) { ?>
<p>
<?php } ?>
<?php } ?>
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
// Function BasicSearchSQL
// - Build WHERE clause for a keyword

function BasicSearchSQL($Keyword)
{

$sKeyword = (!get_magic_quotes_gpc()) ? addslashes($Keyword) : $Keyword;
	
$x_entero = intval($sKeyword);

	$BasicSearchSQL = "";
	if($x_entero > 0){

	$BasicSearchSQL.= "credito.credito_num LIKE '%" . $sKeyword . "%' OR ";
	
	}else{
/*
	$BasicSearchSQL.= "cliente.nombre_completo LIKE '%" . $sKeyword . "%' OR ";	
	$BasicSearchSQL.= "cliente.apellido_paterno LIKE '%" . $sKeyword . "%' OR ";	
	$BasicSearchSQL.= "cliente.apellido_materno LIKE '%" . $sKeyword . "%' OR ";	
*/	
	}
//	$BasicSearchSQL.= "cliente.nombre_completo LIKE '%" . $sKeyword . "%' OR ";	
	if (substr($BasicSearchSQL, -4) == " OR ") { $BasicSearchSQL = substr($BasicSearchSQL, 0, strlen($BasicSearchSQL)-4); }
	return $BasicSearchSQL;
}

//-------------------------------------------------------------------------------
// Function SetUpBasicSearch
// - Set up Basic Search parameter based on form elements pSearch & pSearchType
// - Variables setup: sSrchBasic

function SetUpBasicSearch()
{
	global $sSrchBasic;
	$sSearch = (!get_magic_quotes_gpc()) ? addslashes(@$_POST["psearch"]) : @$_POST["psearch"];
	$sSearchType = @$_GET["psearchtype"];
	if ($sSearch <> "") {
		if ($sSearchType <> "") {
			while (strpos($sSearch, "  ") != false) {
				$sSearch = str_replace("  ", " ",$sSearch);
			}
			$arKeyword = split(" ", trim($sSearch));
			foreach ($arKeyword as $sKeyword)
			{
				$sSrchBasic .= "(" . BasicSearchSQL($sKeyword) . ") " . $sSearchType . " ";
			}
		}
		else
		{
			$sSrchBasic = BasicSearchSQL($sSearch);
		}
	}
	if (substr($sSrchBasic, -4) == " OR ") { $sSrchBasic = substr($sSrchBasic, 0, strlen($sSrchBasic)-4); }
	if (substr($sSrchBasic, -5) == " AND ") { $sSrchBasic = substr($sSrchBasic, 0, strlen($sSrchBasic)-5); }
}

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

		// Field credito_id
		if ($sOrder == "credito_id") {
			$sSortField = "credito.credito_num+0";
			$sLastSort = @$_SESSION["credito_x_credito_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "ASC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["credito_x_credito_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["credito_x_credito_id_Sort"] <> "") { @$_SESSION["credito_x_credito_id_Sort"] = ""; }
		}

		// Field credito_id
		if ($sOrder == "cliente_id") {
			$sSortField = "credito.cliente_num+0";
			$sLastSort = @$_SESSION["credito_x_cliente_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "ASC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["credito_x_cliente_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["credito_x_cliente_id_Sort"] <> "") { @$_SESSION["credito_x_cliente_id_Sort"] = ""; }
		}

		// Field credito_tipo_id
		if ($sOrder == "credito_tipo_id") {
			$sSortField = "credito.credito_tipo_id";
			$sLastSort = @$_SESSION["credito_x_credito_tipo_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["credito_x_credito_tipo_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["credito_x_credito_tipo_id_Sort"] <> "") { @$_SESSION["credito_x_credito_tipo_id_Sort"] = ""; }
		}

		// Field credito_tipo_id
		if ($sOrder == "cliente") {
			$sSortField = "cliente.nombre_completo";
			$sLastSort = @$_SESSION["credito_x_cliente_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["credito_x_cliente_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["credito_x_cliente_Sort"] <> "") { @$_SESSION["credito_x_cliente_Sort"] = ""; }
		}

		// Field solicitud_id
		if ($sOrder == "solicitud_id") {
			$sSortField = "credito.solicitud_id";
			$sLastSort = @$_SESSION["credito_x_solicitud_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["credito_x_solicitud_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["credito_x_solicitud_id_Sort"] <> "") { @$_SESSION["credito_x_solicitud_id_Sort"] = ""; }
		}

		// Field credito_status_id
		if ($sOrder == "credito_status_id") {
			$sSortField = "credito.credito_status_id";
			$sLastSort = @$_SESSION["credito_x_credito_status_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["credito_x_credito_status_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["credito_x_credito_status_id_Sort"] <> "") { @$_SESSION["credito_x_credito_status_id_Sort"] = ""; }
		}

		// Field fecha_otrogamiento
		if ($sOrder == "fecha_otrogamiento") {
			$sSortField = "credito.fecha_otrogamiento";
			$sLastSort = @$_SESSION["credito_x_fecha_otrogamiento_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["credito_x_fecha_otrogamiento_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["credito_x_fecha_otrogamiento_Sort"] <> "") { @$_SESSION["credito_x_fecha_otrogamiento_Sort"] = ""; }
		}

		// Field importe
		if ($sOrder == "importe") {
			$sSortField = "credito.importe";
			$sLastSort = @$_SESSION["credito_x_importe_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["credito_x_importe_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["credito_x_importe_Sort"] <> "") { @$_SESSION["credito_x_importe_Sort"] = ""; }
		}

		// Field tasa
		if ($sOrder == "tasa") {
			$sSortField = "credito.tasa";
			$sLastSort = @$_SESSION["credito_x_tasa_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["credito_x_tasa_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["credito_x_tasa_Sort"] <> "") { @$_SESSION["credito_x_tasa_Sort"] = ""; }
		}

		// Field plazo
		if ($sOrder == "plazo") {
			$sSortField = "credito.plazo";
			$sLastSort = @$_SESSION["credito_x_plazo_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["credito_x_plazo_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["credito_x_plazo_Sort"] <> "") { @$_SESSION["credito_x_plazo_Sort"] = ""; }
		}

		// Field fecha_vencimiento
		if ($sOrder == "fecha_vencimiento") {
			$sSortField = "credito.fecha_vencimiento";
			$sLastSort = @$_SESSION["credito_x_fecha_vencimiento_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["credito_x_fecha_vencimiento_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["credito_x_fecha_vencimiento_Sort"] <> "") { @$_SESSION["credito_x_fecha_vencimiento_Sort"] = ""; }
		}

		// Field tasa_moratoria
		if ($sOrder == "tasa_moratoria") {
			$sSortField = "credito.tasa_moratoria";
			$sLastSort = @$_SESSION["credito_x_tasa_moratoria_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["credito_x_tasa_moratoria_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["credito_x_tasa_moratoria_Sort"] <> "") { @$_SESSION["credito_x_tasa_moratoria_Sort"] = ""; }
		}

		// Field medio_pago_id
		if ($sOrder == "medio_pago_id") {
			$sSortField = "credito.medio_pago_id";
			$sLastSort = @$_SESSION["credito_x_medio_pago_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["credito_x_medio_pago_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["credito_x_medio_pago_id_Sort"] <> "") { @$_SESSION["credito_x_medio_pago_id_Sort"] = ""; }
		}

		// Field referencia_pago
		if ($sOrder == "referencia_pago") {
			$sSortField = "credito..referencia_pago";
			$sLastSort = @$_SESSION["credito_x_referencia_pago_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["credito_x_referencia_pago_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["credito_x_referencia_pago_Sort"] <> "") { @$_SESSION["credito_x_referencia_pago_Sort"] = ""; }
		}
		$_SESSION["credito_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["credito_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["credito_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["credito_OrderBy"] = $sOrderBy;
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
		$_SESSION["credito_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["credito_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["credito_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["credito_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["credito_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["credito_REC"] = $nStartRec;
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
			$_SESSION["credito_searchwhere"] = $sSrchWhere;

			$_SESSION["x_nombre_srch"] = "";
			$_SESSION["x_apepat_srch"] = "";
			$_SESSION["x_apemat_srch"] = "";
			$_SESSION["x_crenum_srch"] = "";
			$_SESSION["x_clinum_srch"] = "";
			$_SESSION["x_cresta_srch"] = "";
			$_SESSION["x_promo_srch"] = "";
			$_SESSION["x_empresa_id"] = "";
			$_SESSION["x_fondeo_credito_id"] = "";			
			$_SESSION["x_credito_tipo_id"] = "";			
			

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["credito_searchwhere"] = $sSrchWhere;
			$_SESSION["x_nombre_srch"] = "";
			$_SESSION["x_apepat_srch"] = "";
			$_SESSION["x_apemat_srch"] = "";
			$_SESSION["x_crenum_srch"] = "";
			$_SESSION["x_clinum_srch"] = "";
			$_SESSION["x_cresta_srch"] = "";
			$_SESSION["x_promo_srch"] = "";
			$_SESSION["x_empresa_id"] = "";			
			$_SESSION["x_fondeo_credito_id"] = "";			
			$_SESSION["x_credito_tipo_id"] = "";						
			
		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["credito_OrderBy"] = $sOrderBy;
			if (@$_SESSION["credito_x_credito_id_Sort"] <> "") { $_SESSION["credito_x_credito_id_Sort"] = ""; }
			if (@$_SESSION["credito_x_cliente_id_Sort"] <> "") { $_SESSION["credito_x_cliente_id_Sort"] = ""; }
			
			if (@$_SESSION["credito_x_credito_tipo_id_Sort"] <> "") { $_SESSION["credito_x_credito_tipo_id_Sort"] = ""; }
			if (@$_SESSION["credito_x_solicitud_id_Sort"] <> "") { $_SESSION["credito_x_solicitud_id_Sort"] = ""; }
			if (@$_SESSION["credito_x_credito_status_id_Sort"] <> "") { $_SESSION["credito_x_credito_status_id_Sort"] = ""; }
			if (@$_SESSION["credito_x_fecha_otrogamiento_Sort"] <> "") { $_SESSION["credito_x_fecha_otrogamiento_Sort"] = ""; }
			if (@$_SESSION["credito_x_importe_Sort"] <> "") { $_SESSION["credito_x_importe_Sort"] = ""; }
			if (@$_SESSION["credito_x_tasa_Sort"] <> "") { $_SESSION["credito_x_tasa_Sort"] = ""; }
			if (@$_SESSION["credito_x_plazo_Sort"] <> "") { $_SESSION["credito_x_plazo_Sort"] = ""; }
			if (@$_SESSION["credito_x_fecha_vencimiento_Sort"] <> "") { $_SESSION["credito_x_fecha_vencimiento_Sort"] = ""; }
			if (@$_SESSION["credito_x_tasa_moratoria_Sort"] <> "") { $_SESSION["credito_x_tasa_moratoria_Sort"] = ""; }
			if (@$_SESSION["credito_x_medio_pago_id_Sort"] <> "") { $_SESSION["credito_x_medio_pago_id_Sort"] = ""; }
			if (@$_SESSION["credito_x_referencia_pago_Sort"] <> "") { $_SESSION["credito_x_referencia_pago_Sort"] = ""; }
			if (@$_SESSION["credito_x_cliente_Sort"] <> "") { $_SESSION["credito_x_cliente_Sort"] = ""; }			
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["credito_REC"] = $nStartRec;
	}
}
?>
