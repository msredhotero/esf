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
<p><span class="phpmaker">
  CARTERA CAPITAL</span></p>
<p>Diferencia encontrada entre reporte de cartera total y reporte de cuadros de cartera el d&iacute;a jueves 07/11/2013</p>
<p>&nbsp;</p>
<p>$28,650.34</p>
<p>Se guarda registro para futuras referencias.</p>
<p>&nbsp;</p>
<?php include ("footer.php") ?>

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
