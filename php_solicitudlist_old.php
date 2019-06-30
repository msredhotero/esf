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
$x_solicitud_id = Null; 
$ox_solicitud_id = Null;
$x_credito_tipo_id = Null; 
$ox_credito_tipo_id = Null;
$x_solicitud_status_id = Null; 
$ox_solicitud_status_id = Null;
$x_folio = Null; 
$ox_folio = Null;
$x_fecha_registro = Null; 
$ox_fecha_registro = Null;
$x_promotor_id = Null; 
$ox_promotor_id = Null;
$x_importe_solicitado = Null; 
$ox_importe_solicitado = Null;
$x_plazo = Null; 
$ox_plazo = Null;
$x_contrato = Null; 
$ox_contrato = Null;
$x_pagare = Null; 
$ox_pagare = Null;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=solicitud.xls');
}
if ($sExport == "word") {
	header('Content-Type: application/vnd.ms-word');
	header('Content-Disposition: attachment; filename=solicitud.doc');
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
$nDisplayRecs = 20;
$nRecRange = 10;

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

// Handle Reset Command
ResetCmd();

//$x_psearch = $_POST["psearch"];
$_SESSION["x_credito_tipo_id"] = $_POST["x_credito_tipo_id"];

if(empty($_SESSION["x_credito_tipo_id"])){	
if(!empty($_SESSION["x_credito_tipo_id_ant"])){
	$_SESSION["x_credito_tipo_id"] =  $_SESSION["x_credito_tipo_id_ant"];
	}else{
	//se pone el valor de 100 que equivale a todos
	$_SESSION["x_credito_tipo_id"] = 100;
	}
}
$_SESSION["x_credito_tipo_id_ant"] = $_SESSION["x_credito_tipo_id"];


$x_nombre_srch = $_POST["x_nombre_srch"];
$x_apepat_srch = $_POST["x_apepat_srch"];
$x_apemat_srch = $_POST["x_apemat_srch"];
$x_crenum_srch = $_POST["x_crenum_srch"];
$x_clinum_srch = $_POST["x_clinum_srch"];
$x_cresta_srch = $_POST["x_cresta_srch"];


$x_posteo = $x_nombre_srch.$x_apepat_srch.$x_apemat_srch.$x_crenum_srch.$x_clinum_srch.$x_cresta_srch;
// se compara con diferente de dos ya que se agragaron nuevos tipos de solicitudes
// valor anterior
//if($_SESSION["x_credito_tipo_id"] == 1){

if($_SESSION["x_credito_tipo_id"] != 2){

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
if(!empty($x_cresta_srch)){
	$_SESSION["x_cresta_srch"] = $x_cresta_srch;
}else{
	if(strlen($x_posteo) > 0){
		$_SESSION["x_cresta_srch"] = "";
	}
}

// EN clientes
if((!empty($_SESSION["x_nombre_srch"])) || (!empty($_SESSION["x_apepat_srch"])) || (!empty($_SESSION["x_apemat_srch"])) || (!empty($_SESSION["x_clinum_srch"]))){
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
    
	$ssrch = substr($ssrch, 0, strlen($ssrch)-5);
	
	$ssrch_sql = "select cliente.cliente_id from cliente where ".$ssrch;
	$rs_qry = phpmkr_query($ssrch_sql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$nTotalRecs = phpmkr_num_rows($rs_qry);
	if($nTotalRecs >0){
		while ($row_sqry = @phpmkr_fetch_array($rs_qry)) {
			$ssrch_cli .= $row_sqry[0].","; 			
		}
		if(strlen($ssrch_cli) > 0 ){
			$ssrch_cli = " cliente.cliente_id in (".substr($ssrch_cli, 0, strlen($ssrch_cli)-1).") AND ";	
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

	$ssrch .= "(credito.credito_num+0 = ".$_SESSION["x_crenum_srch"].") AND ";

	$ssrch = substr($ssrch, 0, strlen($ssrch)-5);	
	
	$ssrch_sql = "select credito.solicitud_id from credito where ".$ssrch;

	$rs_qry = phpmkr_query($ssrch_sql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $ssrch_sql);
	$nTotalRecs = phpmkr_num_rows($rs_qry);
	if($nTotalRecs >0){
		while ($row_sqry = @phpmkr_fetch_array($rs_qry)) {
			$ssrch_cre .= $row_sqry[0].","; 			
		}
		if(strlen($ssrch_cre) > 0 ){
			$ssrch_cre = " solicitud.solicitud_id in (".substr($ssrch_cre, 0, strlen($ssrch_cre)-1).") AND ";	
		}else{
			$ssrch_cre = "";
		}
	}else{
		$ssrch_cre = "";
	}
}else{
	$ssrch_cre = "";
}


if(!empty($_SESSION["x_cresta_srch"])){
	if(!empty($_SESSION["x_cresta_srch"]) && ($_SESSION["x_cresta_srch"] != "100")){
		$ssrch_sol .= "(solicitud.solicitud_status_id = ".$_SESSION["x_cresta_srch"].") AND ";
	}
	///ERROR/////////////////////////////////////////////////////
	//$ssrch_sol = substr($ssrch_sol, 0, strlen($ssrch_sol)-5);	
	////////////////////////////////////////////////////////////
}
	//si se selecciono algun tipo de credito
	if(!empty($_SESSION["x_credito_tipo_id"])){
	//si se selecciono pero no es TODOS que tiene el valor de 100
	if(!empty($_SESSION["x_credito_tipo_id"]) && ($_SESSION["x_credito_tipo_id"] != "100")){
		$ssrch_tipo_cred .= "(solicitud.credito_tipo_id = ".$_SESSION["x_credito_tipo_id"].") AND ";
	}
	}
	
	
	
//}



$sSql = "SELECT solicitud.*, cliente.cliente_num, cliente.nombre_completo, cliente.apellido_paterno, cliente.apellido_materno FROM solicitud join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id ";

}else{

$sSql = "SELECT solicitud.* FROM solicitud ";		

$_SESSION["x_nombre_srch"] = "";
$_SESSION["x_apepat_srch"] = "";
$_SESSION["x_apemat_srch"] = "";
$_SESSION["x_crenum_srch"] = "";
$_SESSION["x_clinum_srch"] = "";
$_SESSION["x_cresta_srch"] = "";

$ssrch_sol = "";
$ssrch_cli = "";
$ssrch_cre = "";

$ssrch_sol = "(solicitud.credito_tipo_id = 2) AND ";

}


/*
if(empty($_SESSION["x_credito_tipo_id_filtro"])){
	if($_POST["x_credito_tipo_id_filtro"]) {
		if($_POST["x_credito_tipo_id_filtro"] == 1){
			$sSql = "SELECT solicitud.*, cliente.nombre_completo, cliente.apellido_paterno, cliente.apellido_materno FROM solicitud join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id ";
		}else{
			$sSql = "SELECT solicitud.* FROM solicitud ";		
		}
		$_SESSION["x_credito_tipo_id_filtro"] = $_POST["x_credito_tipo_id_filtro"];
	}else{
		$_SESSION["x_credito_tipo_id_filtro"] = 1;
			$sSql = "SELECT solicitud.*, cliente.nombre_completo, cliente.apellido_paterno, cliente.apellido_materno FROM solicitud join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id ";
	}
}else{
	if($_POST["x_credito_tipo_id_filtro"]) {
		if($_POST["x_credito_tipo_id_filtro"] == 1) {	
			$sSql = "SELECT solicitud.*, cliente.nombre_completo, cliente.apellido_paterno, cliente.apellido_materno FROM solicitud join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id ";
		}else{
			$sSql = "SELECT solicitud.* FROM solicitud ";		
		}
		$_SESSION["x_credito_tipo_id_filtro"] = $_POST["x_credito_tipo_id_filtro"];
	}else{

		if($_SESSION["x_credito_tipo_id_filtro"] == 1){
			$sSql = "SELECT solicitud.*, cliente.nombre_completo, cliente.apellido_paterno, cliente.apellido_materno FROM solicitud join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id ";
		}else{
			$_SESSION["x_credito_tipo_id_filtro"] = 2;
			$sSql = "SELECT solicitud.* FROM solicitud ";				
		}
	}
}
*/


/*
$sSql = "SELECT solicitud.*, cliente.nombre_completo FROM solicitud join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id ";
*/

// Load Default Filter
$sDefaultFilter = "";
$sGroupBy = "";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = "";

// Build WHERE condition
if($_SESSION["php_project_esf_status_UserRolID"] == 7) {
	$sDbWhere = " (solicitud.promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"]. ") AND ";
}else{
	$sDbWhere = "";
}

/*
if(empty($_SESSION["x_solicitud_status_id_filtro"])){
	if($_POST["x_solicitud_status_id_filtro"]) {
		if($_POST["x_solicitud_status_id_filtro"] > 0 && $_POST["x_solicitud_status_id_filtro"] < 100 ) {	
			$sDbWhere = "(solicitud.solicitud_status_id = ".$_POST["x_solicitud_status_id_filtro"]. ") AND ";
		}
		$_SESSION["x_solicitud_status_id_filtro"] = $_POST["x_solicitud_status_id_filtro"];
	}else{
		$_SESSION["x_solicitud_status_id_filtro"] = 100;
	}
}else{
	if($_POST["x_solicitud_status_id_filtro"]) {
		if($_POST["x_solicitud_status_id_filtro"] > 0 && $_POST["x_solicitud_status_id_filtro"] < 100) {	
			$sDbWhere = "(solicitud.solicitud_status_id = ".$_POST["x_solicitud_status_id_filtro"]. ") AND ";
		}
		$_SESSION["x_solicitud_status_id_filtro"] = $_POST["x_solicitud_status_id_filtro"];
	}else{
		if($_SESSION["x_solicitud_status_id_filtro"] < 100){
			$sDbWhere = "(solicitud.solicitud_status_id = ".$_SESSION["x_solicitud_status_id_filtro"]. ") AND ";	
		}
		
	}
}

*/

/*
// Get Search Criteria for Basic Search
SetUpBasicSearch();


// Build Search Criteria
if ($sSrchAdvanced != "") {
	$sSrchWhere = $sSrchAdvanced; // Advanced Search
}
elseif ($sSrchBasic != "") {
	$sSrchWhere = $sSrchBasic; // Basic Search
}

// Save Search Criteria
if ($sSrchWhere != "") {
	$_SESSION["solicitud_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["solicitud_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["solicitud_searchwhere"];
}

*/


if ($sDbWhereDetail <> "") {
	$sDbWhere .= "(" . $sDbWhereDetail . ") AND ";
}
if ($sSrchWhere <> "") {
	$sDbWhere .= "(" . $sSrchWhere . ") AND ";
}
if (strlen($sDbWhere) > 5) {
	$sDbWhere = substr($sDbWhere, 0, strlen($sDbWhere)-5); // Trim rightmost AND
}
/*
if($_SESSION["x_credito_tipo_id_filtro"] == 1){
	$sWhere = "";
}else{
	$sWhere = "(solicitud.credito_tipo_id = 2) AND ";
}
*/


$sWhere = $ssrch_tipo_cred.$ssrch_sol.$ssrch_cli.$ssrch_cre;


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
if($_SESSION["x_credito_tipo_id_filtro"] == 1){
$sOrderBy = "";
SetUpSortOrder();
if ($sOrderBy != "") {
	$sSql .= " ORDER BY " . $sOrderBy;
}
}
*/

$sSql .= " ORDER BY solicitud.fecha_registro desc";

//echo $sSql; // Uncomment to show SQL for debugging
?>
<?php include ("header.php") ?>
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
<p><span class="phpmaker">SOLICITUDES
<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="php_solicitudlist.php?export=excel">Exportar a Excel</a>
<?php } ?>
</span></p>
<?php if ($sExport == "") { ?>
<form name="filtros" action="php_solicitudlist.php" method="post">
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
	  <td>Numero de Credito</td>
	  <td>&nbsp;</td>
	  <td><span class="phpmaker">
	    <input name="x_crenum_srch" type="text" id="x_crenum_srch" value="<?php echo $_SESSION["x_crenum_srch"]; ?>" size="20" />
	  </span></td>
	  <td>&nbsp;</td>
	  <td>Numero de Cliente </td>
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
	  <td>Status de la Solicitud</td>
	  <td>&nbsp;</td>
	  <td><span class="phpmaker">
	    <?php
		$x_estado_civil_idList = "<select name=\"x_cresta_srch\" class=\"texto_normal\">";
		if ($_SESSION["x_credito_status_id_filtro"] == 0){
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
				if ($datawrk["solicitud_status_id"] == $_SESSION["x_cresta_srch"]) {
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
	  <td>Tipo de Credito</td>
	  <td><!-- <input name="x_credito_tipo_id" type="radio" id="x_credito_tipo_id" value="1" <?php //if($_SESSION["x_credito_tipo_id"] == 1){ echo "checked='checked'"; }?> onclick="javascript: document.filtros.submit();" /> --></td>
	  <td>	    <!-- Personal --> <?php
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
				if ($datawrk["credito_tipo_id"] == @$_SESSION["x_credito_tipo_id"]) {
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
	  <td><!-- <input type="radio" name="x_credito_tipo_id" id="x_credito_tipo_id" value="2"  onclick="javascript: document.filtros.submit();"/ <?php //if($_SESSION["x_credito_tipo_id"] == 2){ echo "checked='checked'"; }?>> --></td>
	  <td><!-- Grupo --></td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>
	<tr>
	  <td><span class="phpmaker">
	    <input type="submit" name="Submit" value="Buscar &nbsp;(*)" />
	  </span></td>
	  <td>&nbsp;</td>
	  <td><span class="phpmaker"><a href="php_solicitudlist.php?cmd=reset">Mostrar Todos</a>&nbsp;&nbsp; </span></td>
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
<form action="php_solicitudlist.php" name="ewpagerform" id="ewpagerform">
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
		<a href="php_solicitudlist.php?start=<?php echo $PrevStart; ?>"><b>Anterior</b></a>
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
		<a href="php_solicitudlist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
					<?php }
					$x += $nDisplayRecs;
					$y += 1;
				} elseif (($x >= ($dx1-$nDisplayRecs*$nRecRange)) && ($x <= ($dx2+$nDisplayRecs*$nRecRange))) {
					if ($x+$nRecRange*$nDisplayRecs < $nTotalRecs) { ?>
		<a href="php_solicitudlist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo ($y+$nRecRange-1);?></b></a>
					<?php } else {
						$ny=intval(($nTotalRecs-1)/$nDisplayRecs)+1;
							if ($ny == $y) { ?>
		<a href="php_solicitudlist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
							<?php } else { ?>
		<a href="php_solicitudlist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo $ny; ?></b></a>
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
		<a href="php_solicitudlist.php?start=<?php echo $NextStart; ?>"><b>Siguiente</b></a>
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
<br />
<form name="creditotipo" method="post" action="php_solicitudlist.php">


</form>
&nbsp;
<form name="filtro" method="post" action="php_solicitudlist.php">
</form>

<?php } ?>
<?php if ($nTotalRecs > 0)  { ?>
<form method="post">
<table id="ewlistmain" class="ewTable">
	<!-- Table header -->
	<tr class="ewTableHeader">
<?php if ($sExport == "") { ?>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>

<td><input type="checkbox" name="checkall" class="phpmaker" onClick="EW_selectKey(this);"></td>
<?php } ?>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Credito Num
<?php }else{ ?>
Credito Num
<!---
	<a href="php_solicitudlist.php?order=<?php //echo urlencode("solicitud_id"); ?>">No<?php //if (@$_SESSION["solicitud_x_solicitud_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php //} elseif (@$_SESSION["solicitud_x_solicitud_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php //} ?></a>
-->    
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Status
<?php }else{ ?>
	<a href="php_solicitudlist.php?order=<?php echo urlencode("solicitud_status_id"); ?>">Status<?php if (@$_SESSION["solicitud_x_solicitud_status_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["solicitud_x_solicitud_status_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Tipo de Crédito
<?php }else{ ?>
	<a href="php_solicitudlist.php?order=<?php echo urlencode("credito_tipo_id"); ?>">Tipo de Crédito<?php if (@$_SESSION["solicitud_x_credito_tipo_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["solicitud_x_credito_tipo_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Fecha de registro
<?php }else{ ?>
	<a href="php_solicitudlist.php?order=<?php echo urlencode("fecha_registro"); ?>">Fecha de registro<?php if (@$_SESSION["solicitud_x_fecha_registro_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["solicitud_x_fecha_registro_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>        
Cliente Num.        
		</span></td>        
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Cliente
<?php }else{ ?>
	<a href="php_solicitudlist.php?order=<?php echo urlencode("cliente"); ?>">Cliente&nbsp;(*)<?php if (@$_SESSION["solicitud_x_cliente_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["solicitud_x_cliente_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Promotor
<?php }else{ ?>
	<a href="php_solicitudlist.php?order=<?php echo urlencode("promotor_id"); ?>">Promotor<?php if (@$_SESSION["solicitud_x_promotor_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["solicitud_x_promotor_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Importe solicitado
<?php }else{ ?>
	<a href="php_solicitudlist.php?order=<?php echo urlencode("importe_solicitado"); ?>">Importe solicitado<?php if (@$_SESSION["solicitud_x_importe_solicitado_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["solicitud_x_importe_solicitado_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Plazo
<?php }else{ ?>
	<a href="php_solicitudlist.php?order=<?php echo urlencode("plazo"); ?>">Plazo<?php if (@$_SESSION["solicitud_x_plazo_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["solicitud_x_plazo_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Contrato
<?php }else{ ?>
	<a href="php_solicitudlist.php?order=<?php echo urlencode("contrato"); ?>">Contrato<?php if (@$_SESSION["solicitud_x_contrato_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["solicitud_x_contrato_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Pagaré
<?php }else{ ?>
	<a href="php_solicitudlist.php?order=<?php echo urlencode("pagare"); ?>">Pagaré<?php if (@$_SESSION["solicitud_x_pagare_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["solicitud_x_pagare_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
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
		$x_solicitud_id = $row["solicitud_id"];
		$x_credito_tipo_id = $row["credito_tipo_id"];
		$x_solicitud_status_id = $row["solicitud_status_id"];
		$x_folio = $row["folio"];
		$x_fecha_registro = $row["fecha_registro"];
		$x_importe_solicitado = $row["importe_solicitado"];
		$x_plazo_id = $row["plazo_id"];
		$x_forma_pago= $row["forma_pago_id"];
		$x_contrato = $row["contrato"];
		$x_pagare = $row["pagare"];
		$x_promotor_id = $row["promotor_id"];
		$x_grupo_nombre = $row["grupo_nombre"];		
		$x_cliente_num = $row["cliente_num"];
		$x_formato_nuevo = $row["formato_nuevo"];
		
		$x_link_edit="";
		$x_link_view="";
		$x_link_print="";
		
		if($x_credito_tipo_id == 1){
			if($x_formato_nuevo == 0){
				$x_link_edit ="php_solicitudedit.php";
				$x_link_view = "php_solicitudview.php";
				$x_link_print = "php_solicitud_print.php";
				}else if($x_formato_nuevo == 1){
					$x_link_edit ="modulos/php_solicitudeditIndividual.php";
					$x_link_view = "modulos/php_solicitudviewIndividual.php";
					$x_link_print = "modulos/tipoCuenta/formatos/php_solicitudIndividualP_print.php";
					}
			}else if($x_credito_tipo_id == 2){
				if($x_formato_nuevo == 0){
					$x_link_edit ="php_solicitud_caedit.php";
					$x_link_view = "";
					$x_link_print = "";
					}else if($x_formato_nuevo == 1){
						$x_link_edit ="modulos/php_solicitudeditSolidario.php";
						$x_link_view = "";
						$x_link_print = "modulos/tipoCuenta/formatos/php_solicitudSolidario_print.php";
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
						}
		
		//el tipo de credito debe ser diferente a 2 puedes ser 1, 3 o 4 
		//if($x_credito_tipo_id == 1){
		if($x_credito_tipo_id != 2){
			$sSqlWrk = "SELECT cliente.nombre_completo, cliente.apellido_paterno, cliente.apellido_materno FROM solicitud join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id where solicitud.solicitud_id = $x_solicitud_id";
			$rswrk_cli = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$row_cli = @phpmkr_fetch_array($rswrk_cli);
			$x_cliente = $row_cli["nombre_completo"]." ".$row_cli["apellido_paterno"]." ".$row_cli["apellido_materno"];
			@phpmkr_free_result($rswrk_cli);
		}else{
			$x_cliente = "Asociados";
		}
		
		$sSqlWrk = "SELECT credito.credito_num FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id where solicitud.solicitud_id = $x_solicitud_id";
		$rswrk_cli = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$row_cli = @phpmkr_fetch_array($rswrk_cli);
		$x_credito_num = $row_cli["credito_num"];
		@phpmkr_free_result($rswrk_cli);

		
		
		
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>
<?php if ($sExport == "") { ?>

<?php if(($x_credito_tipo_id == 1) || ($x_credito_tipo_id == 3) || ($x_credito_tipo_id == 4)) {?>
<!--- FC -->
<td><span class="phpmaker"><a href="<?php if ($x_solicitud_id <> "") {echo "$x_link_view?solicitud_id=" . urlencode($x_solicitud_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Ver</a></span></td>
<?php if($x_solicitud_status_id != 6) {?>

<?php if($x_solicitud_status_id == 7) {?>
<td><span class="phpmaker">
&nbsp;
</span></td>
<?php }else{ ?>
<td>
<span class="phpmaker"><a href="<?php if ($x_solicitud_id <> "") {echo "$x_link_edit?solicitud_id=" . urlencode($x_solicitud_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>" target='_blank'>Editar</a></span>
</td>
<?php } ?>

<?php }else{ ?>
<?php if(($_SESSION["php_project_esf_status_UserRolID"] == 1) || ($_SESSION["php_project_esf_status_UserRolID"] == 2) || ($_SESSION["php_project_esf_status_UserRolID"] == 3)) {?>
<td><span class="phpmaker">
&nbsp;
</span></td>
<?php }else{ ?>
<td><span class="phpmaker">&nbsp;</span></td>
<?php } ?>
<?php } ?>
<td><span class="phpmaker"><a href="<?php if ($x_solicitud_id <> "") {echo "$x_link_print?solicitud_id=" . urlencode($x_solicitud_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>" target="_blank">Imprimir</a></span></td>
<?php if($x_solicitud_status_id == 6) {?>
<td><span class="phpmaker"><a href="<?php if ($x_solicitud_id <> "") { if($x_formato_nuevo == 0){echo "php_contrato_print.php?solicitud_id=" . urlencode($x_solicitud_id);}else if($x_formato_nuevo == 1){echo "php_contrato_print_v1.php?solicitud_id=" . urlencode($x_solicitud_id);} } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>" target="_blank">Contrato</a></span></td>
<td><span class="phpmaker"><a href="<?php if ($x_solicitud_id <> "") {if($x_formato_nuevo == 0){echo "php_pagare_print.php?solicitud_id=" . urlencode($x_solicitud_id);}else if($x_formato_nuevo == 1){echo "php_pagare_print_v1.php?solicitud_id=" . urlencode($x_solicitud_id);} } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>" target="_blank">Pagar&eacute;</a></span></td>
<?php }else{ ?>

<?php if(($x_solicitud_status_id == 3) && ($_SESSION["php_project_esf_status_UserRolID"] == 1)) {?>
<td><span class="phpmaker"><a href="<?php if ($x_solicitud_id <> "") {echo "php_creditoadd.php?x_solicitud_id=" . urlencode($x_solicitud_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>" target="_self">Otrogar Cr&eacute;dito</a></span></td>
<?php }else{ ?>
<td><span class="phpmaker">&nbsp;</span></td>
<?php } ?>
<td><span class="phpmaker">&nbsp;</span></td>
<?php } ?>

<?php }else{ ?>
<!--- FA -->

<td colspan="3" align="center" valign="middle"><span class="phpmaker">
<a href="<?php if ($x_solicitud_id <> "") {echo "$x_link_edit?solicitud_id=" . urlencode($x_solicitud_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Ver</a>
<?php if(($x_solicitud_status_id == 3) && ($_SESSION["php_project_esf_status_UserRolID"] == 1)) {?>
<br /><br />
<span class="phpmaker"><a href="<?php if ($x_solicitud_id <> "") {echo "php_creditoadd.php?x_solicitud_id=" . urlencode($x_solicitud_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>" target="_self">Otrogar Cr&eacute;dito</a></span>
<?php } ?>
</span></td>
<?php if($x_solicitud_status_id == 6 ) {?>
<td><span class="phpmaker"><a href="<?php if ($x_solicitud_id <> "") { if($x_formato_nuevo == 0){echo "php_contrato_print.php?solicitud_id=" . urlencode($x_solicitud_id);}else if($x_formato_nuevo == 1){echo "php_contrato_print_v1.php?solicitud_id=" . urlencode($x_solicitud_id);} } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>" target="_blank">Contrato</a></span></td>
<td><span class="phpmaker"><a href="<?php if ($x_solicitud_id <> "") {if($x_formato_nuevo == 0){echo "php_pagare_print.php?solicitud_id=" . urlencode($x_solicitud_id);}else if($x_formato_nuevo == 1){echo "php_pagare_print_v1.php?solicitud_id=" . urlencode($x_solicitud_id);} } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>" target="_blank">Pagar&eacute;</a></span></td>
<?php }else{?>
<td>&nbsp;</td>
<td>&nbsp;</td>
<?php }?>




<?php } ?>

<td><span class="phpmaker"><input type="checkbox" name="key_d[]" value="<?php echo $x_solicitud_id; ?>" class="phpmaker" onclick='ew_clickmultidelete(this);'>
Eliminar</span></td>
<?php } ?>
		<!-- solicitud_id -->
		<td align="right"><span>
<?php echo $x_credito_num; //$x_solicitud_id; ?>
</span></td>
		<!-- folio -->
		<!-- solicitud_status_id -->
		<td><span>
<?php
if ((!is_null($x_solicitud_status_id)) && ($x_solicitud_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `solicitud_status`";
	$sTmp = $x_solicitud_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `solicitud_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_solicitud_status_id = $x_solicitud_status_id; // Backup Original Value
$x_solicitud_status_id = $sTmp;
?>
<?php echo $x_solicitud_status_id; ?>
<?php $x_solicitud_status_id = $ox_solicitud_status_id; // Restore Original Value ?>
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
		<!-- fecha_registro -->
		<td align="center"><span>
<?php echo FormatDateTime($x_fecha_registro,7); ?>
</span></td>
		<td align="center"><span>
<?php 
if($x_credito_tipo_id == 1){
	echo $x_cliente_num; 
}
?>

</span></td>
<td align="left"><span>
<?php 
if($x_credito_tipo_id == 1){
	echo $x_cliente; 
}else{
	echo $x_grupo_nombre;
}


?>
</span></td>


		<!-- promotor_id -->
		<td><span>
<?php
if ((!is_null($x_promotor_id)) && ($x_promotor_id <> "")) {
	$sSqlWrk = "SELECT `nombre_completo` FROM `promotor`";
	$sTmp = $x_promotor_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `promotor_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["nombre_completo"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_promotor_id = $x_promotor_id; // Backup Original Value
$x_promotor_id = $sTmp;
?>
<?php echo $x_promotor_id; ?>
<?php $x_promotor_id = $ox_promotor_id; // Restore Original Value ?>
</span></td>
		<!-- importe_solicitado -->
		<td align="right"><span>
<?php echo (is_numeric($x_importe_solicitado)) ? FormatNumber($x_importe_solicitado,0,0,0,-2) : $x_importe_solicitado; ?>
</span></td>
		<!-- plazo -->
		<td><span>
<?php
if($x_formato_nuevo == 1){
if ((!is_null($x_plazo_id)) && ($x_plazo_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `forma_pago`";
	$sTmp = $x_forma_pago;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `forma_pago_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
		@phpmkr_free_result($rswrk);
	}
	
} else {
	$sTmp = "";
}

$ox_plazo_id = $x_plazo_id; // Backup Original Value
//$x_plazo_id = $sTmp;

//imprimimos numero de pagos y forma de pago
 echo $x_plazo_id." ".$sTmp;
} else if($x_formato_nuevo == 0){
	if ((!is_null($x_plazo_id)) && ($x_plazo_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `plazo`";
	$sTmp = $x_plazo_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `plazo_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}	
	echo $sTmp;	
	
	}?>


<?php $x_plazo_id = $ox_plazo_id; // Restore Original Value ?>
</span></td>
		<!-- contrato -->
		<td><span>
<?php
echo @$x_contrato;
?>
</span></td>
		<!-- pagare -->
		<td><span>
<?php
echo @$x_pagare;
?>
</span></td>
	</tr>
<?php
	}
}
?>
</table>
<?php if ($sExport == "") { ?>
<?php if ($nRecActual > 0) { ?>

<?php if($_SESSION["php_project_esf_status_UserRolID"] == 1) { ?>
<p><input type="button" name="btndelete" value="ELIMINAR SELECCIONADOS" onClick="if (!EW_selected(this)) alert('No records selected'); else {this.form.action='php_solicituddelete.php';this.form.encoding='application/x-www-form-urlencoded';this.form.submit();}">
<?php } ?>

</p>
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
	$BasicSearchSQL = "";
	if($_SESSION["x_credito_tipo_id_filtro"] == 1){
//		$BasicSearchSQL.= "solicitud.folio LIKE '%" . $sKeyword . "%' OR ";
		$BasicSearchSQL.= "cliente.nombre_completo LIKE '%" . $sKeyword . "%' OR ";	
	}else{
//		$BasicSearchSQL.= "solicitud.folio LIKE '%" . $sKeyword . "%' OR ";
		$BasicSearchSQL.= "solicitud.grupo_nombre LIKE '%" . $sKeyword . "%' OR ";	
	}
	
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
	$sSearchType = @$_POST["psearchtype"];
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

		// Field solicitud_id
		if ($sOrder == "solicitud_id") {
			$sSortField = "`solicitud_id`";
			$sLastSort = @$_SESSION["solicitud_x_solicitud_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["solicitud_x_solicitud_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["solicitud_x_solicitud_id_Sort"] <> "") { @$_SESSION["solicitud_x_solicitud_id_Sort"] = ""; }
		}

		// Field credito_tipo_id
		if ($sOrder == "credito_tipo_id") {
			$sSortField = "`credito_tipo_id`";
			$sLastSort = @$_SESSION["solicitud_x_credito_tipo_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["solicitud_x_credito_tipo_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["solicitud_x_credito_tipo_id_Sort"] <> "") { @$_SESSION["solicitud_x_credito_tipo_id_Sort"] = ""; }
		}

		// Field solicitud_status_id
		if ($sOrder == "solicitud_status_id") {
			$sSortField = "`solicitud_status_id`";
			$sLastSort = @$_SESSION["solicitud_x_solicitud_status_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["solicitud_x_solicitud_status_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["solicitud_x_solicitud_status_id_Sort"] <> "") { @$_SESSION["solicitud_x_solicitud_status_id_Sort"] = ""; }
		}

		// Field folio
		if ($sOrder == "folio") {
			$sSortField = "`folio`";
			$sLastSort = @$_SESSION["solicitud_x_folio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["solicitud_x_folio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["solicitud_x_folio_Sort"] <> "") { @$_SESSION["solicitud_x_folio_Sort"] = ""; }
		}

		// Field folio
		if ($sOrder == "cliente") {
			$sSortField = "`nombre_completo`";
			$sLastSort = @$_SESSION["solicitud_x_cliente_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["solicitud_x_cliente_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["solicitud_x_cliente_Sort"] <> "") { @$_SESSION["solicitud_x_cliente_Sort"] = ""; }
		}

		// Field fecha_registro
		if ($sOrder == "fecha_registro") {
			$sSortField = "`fecha_registro`";
			$sLastSort = @$_SESSION["solicitud_x_fecha_registro_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["solicitud_x_fecha_registro_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["solicitud_x_fecha_registro_Sort"] <> "") { @$_SESSION["solicitud_x_fecha_registro_Sort"] = ""; }
		}

		// Field promotor_id
		if ($sOrder == "promotor_id") {
			$sSortField = "`promotor_id`";
			$sLastSort = @$_SESSION["solicitud_x_promotor_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["solicitud_x_promotor_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["solicitud_x_promotor_id_Sort"] <> "") { @$_SESSION["solicitud_x_promotor_id_Sort"] = ""; }
		}

		// Field importe_solicitado
		if ($sOrder == "importe_solicitado") {
			$sSortField = "`importe_solicitado`";
			$sLastSort = @$_SESSION["solicitud_x_importe_solicitado_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["solicitud_x_importe_solicitado_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["solicitud_x_importe_solicitado_Sort"] <> "") { @$_SESSION["solicitud_x_importe_solicitado_Sort"] = ""; }
		}

		// Field plazo
		if ($sOrder == "plazo") {
			$sSortField = "`plazo`";
			$sLastSort = @$_SESSION["solicitud_x_plazo_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["solicitud_x_plazo_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["solicitud_x_plazo_Sort"] <> "") { @$_SESSION["solicitud_x_plazo_Sort"] = ""; }
		}

		// Field contrato
		if ($sOrder == "contrato") {
			$sSortField = "`contrato`";
			$sLastSort = @$_SESSION["solicitud_x_contrato_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["solicitud_x_contrato_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["solicitud_x_contrato_Sort"] <> "") { @$_SESSION["solicitud_x_contrato_Sort"] = ""; }
		}

		// Field pagare
		if ($sOrder == "pagare") {
			$sSortField = "`pagare`";
			$sLastSort = @$_SESSION["solicitud_x_pagare_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["solicitud_x_pagare_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["solicitud_x_pagare_Sort"] <> "") { @$_SESSION["solicitud_x_pagare_Sort"] = ""; }
		}
		$_SESSION["solicitud_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["solicitud_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["solicitud_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["solicitud_OrderBy"] = $sOrderBy;
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
		$_SESSION["solicitud_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["solicitud_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["solicitud_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["solicitud_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["solicitud_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["solicitud_REC"] = $nStartRec;
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
	$_SESSION["x_credito_tipo_id_filtro"] = "";
	$_SESSION["x_solicitud_status_id_filtro"]= "";

		// Reset Search Criteria
		if (strtoupper($sCmd) == "RESET") {
			$sSrchWhere = "";
			$_SESSION["solicitud_searchwhere"] = $sSrchWhere;
			$_SESSION["x_nombre_srch"] = "";
			$_SESSION["x_apepat_srch"] = "";
			$_SESSION["x_apemat_srch"] = "";
			$_SESSION["x_crenum_srch"] = "";
			$_SESSION["x_clinum_srch"] = "";
			$_SESSION["x_cresta_srch"] = "";


		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["solicitud_searchwhere"] = $sSrchWhere;
			$_SESSION["x_nombre_srch"] = "";
			$_SESSION["x_apepat_srch"] = "";
			$_SESSION["x_apemat_srch"] = "";
			$_SESSION["x_crenum_srch"] = "";
			$_SESSION["x_clinum_srch"] = "";
			$_SESSION["x_cresta_srch"] = "";

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["solicitud_OrderBy"] = $sOrderBy;
			if (@$_SESSION["solicitud_x_solicitud_id_Sort"] <> "") { $_SESSION["solicitud_x_solicitud_id_Sort"] = ""; }
			if (@$_SESSION["solicitud_x_credito_tipo_id_Sort"] <> "") { $_SESSION["solicitud_x_credito_tipo_id_Sort"] = ""; }
			if (@$_SESSION["solicitud_x_solicitud_status_id_Sort"] <> "") { $_SESSION["solicitud_x_solicitud_status_id_Sort"] = ""; }
			if (@$_SESSION["solicitud_x_folio_Sort"] <> "") { $_SESSION["solicitud_x_folio_Sort"] = ""; }
			if (@$_SESSION["solicitud_x_cliente_Sort"] <> "") { $_SESSION["solicitud_x_cliente_Sort"] = ""; }			
			if (@$_SESSION["solicitud_x_fecha_registro_Sort"] <> "") { $_SESSION["solicitud_x_fecha_registro_Sort"] = ""; }
			if (@$_SESSION["solicitud_x_promotor_id_Sort"] <> "") { $_SESSION["solicitud_x_promotor_id_Sort"] = ""; }
			if (@$_SESSION["solicitud_x_importe_solicitado_Sort"] <> "") { $_SESSION["solicitud_x_importe_solicitado_Sort"] = ""; }
			if (@$_SESSION["solicitud_x_plazo_Sort"] <> "") { $_SESSION["solicitud_x_plazo_Sort"] = ""; }
			if (@$_SESSION["solicitud_x_contrato_Sort"] <> "") { $_SESSION["solicitud_x_contrato_Sort"] = ""; }
			if (@$_SESSION["solicitud_x_pagare_Sort"] <> "") { $_SESSION["solicitud_x_pagare_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["solicitud_REC"] = $nStartRec;
	}
}
?>
