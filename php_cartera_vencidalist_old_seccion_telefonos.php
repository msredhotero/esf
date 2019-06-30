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
$x_gestor_srch = $_POST["x_gestor_srch"];
 if( isset($_GET["x_gestor_srch"] )){
	 $_SESSION["x_gestor_srch"] = $_GET["x_gestor_srch"];
	// echo "gestor en get".$_SESSION["x_gestor_srch"];
	 }
if($_GET["x_dias_ini"]){
	$_SESSION["x_dias_ini"] = $_GET["x_dias_ini"];
	$_SESSION["x_dias_fin"] = $_GET["x_dias_fin"];		
	$_SESSION["x_gestor_srch"] = $_GET["x_gestor_srch"];	
	
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
		if($_SESSION["php_project_esf_status_UserRolID"] == 12){
			//echo "2";
			}else{
		$ssrch_cre = substr($ssrch_cre, 0, strlen($ssrch_cre)-5);	
			}
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




if(!empty($_POST["x_credito_tipo_id"])){
	$_SESSION["x_credito_tipo_id"] = $_POST["x_credito_tipo_id"];
}else{
	if(empty($_SESSION["x_credito_tipo_id"])){
		$_SESSION["x_credito_tipo_id"] = 1;
	}
}

$sWhere .= "(credito.credito_tipo_id = ".$_SESSION["x_credito_tipo_id"]. ") AND ";


if ($sWhere != ""){
	if (substr($sWhere, -5) == " AND ") {
		$sWhere = " AND ".substr($sWhere, 0, strlen($sWhere)-5);
	}else{
		$sWhere = " AND ".$sWhere;
	}
}

#echo "s where ".$sWhere;


// seleccionamos los datos de la scursal por si el rol con el que se logue ael usuario es de un responsable de sucursal.
 if ($_SESSION["php_project_esf_status_UserRolID"] == 12){
	 // el usuario es un encargado de suscursal
	 // se agrega una condicion al where para que en listado solo se vean los credito de la sucursal.
	 
	 // se selecciona a todos los promotores que pertenecen a la sucursal del  encargado
	 $sqlEncargadoSucursal = "SELECT * FROM encargado_sucursal WHERE encargado_sucursal_id = ". $_SESSION["php_project_esf_status_ResponsableID"]."";
	// $rsEncargadoSucursal = phpmkr_query($sqlEncargadoSucursal, $conn) or die ("Error al seleccionar los datos del encrgado de sucursal". phpmkr_error()."sql:".$sqlEncargadoSucursal);
	 //$rowEncargadoSucursal = phpmkr_fetch_array($rsEncargadoSucursal);
	 $x_sucursal_id = $rowEncargadoSucursal["sucursal_id"];
	 
		$x_suc_id = $_SESSION["php_project_esf_SucursalID"];
		
		
		if($_SESSION["php_project_esf_status_UserRolID"] == 12){
						#echo "entro en encargado";
					
						$sSqls = "select responsable_sucursal_id, sucursal_id from responsable_sucursal where usuario_id = ".$_SESSION["php_project_esf_status_UserID"];
					//	echo $sSql;
						$rs2 = phpmkr_query($sSqls,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
						if (phpmkr_num_rows($rs2) > 0) {
							$row2 = phpmkr_fetch_array($rs2);
							$_SESSION["php_project_esf_status_ResponsableID"] = $row2["responsable_sucursal_id"];	
							$_SESSION["php_project_esf_SucursalID"] = $row2["sucursal_id"];						
							$bValidPwd = true;
						}
		}
	 
	 // seleccionamos todos los promotores que correspondan a esa sucursal
	 $sqlListaPromotores = "SELECT * FROM promotor  WHERE  sucursal_id = ". $_SESSION["php_project_esf_SucursalID"]."";
	// echo   $sqlListaPromotores."<br>";
	 $rsPromotores = phpmkr_query($sqlListaPromotores, $conn) or die ("Error al selccioar los promotores de la sucursal". phpmkr_error()."sql:".$sqlListaPromotores);
	 while($rowPromotores = phpmkr_fetch_array($rsPromotores)){
		 $x_promotores .= $rowPromotores["promotor_id"].", ";	 
		 }
		 $x_promotores = trim($x_promotores, ", ");
		 if(empty($filter["x_promo_srch"]))
		 $sDbWhereEncargado = " (solicitud.promotor_id IN ($x_promotores)  )";
		 
	 }


// Build SQL
if($_SESSION["php_project_esf_status_UserRolID"] == 7) {
	$sSql = "select vencimiento.credito_id, credito.solicitud_id, solicitud.formato_nuevo as formato from vencimiento join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id where (credito.credito_status_id in (1,3)) AND (vencimiento.vencimiento_status_id = 3) AND (solicitud.promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"]. ") AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) >= ".$_SESSION["x_dias_ini"].") AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) <= ".$_SESSION["x_dias_fin"].$sWhere.") group by vencimiento.credito_id order by credito.credito_num+0";
}else{
	if($_SESSION["php_project_esf_status_UserRolID"] == 5) {
		$sSql = "select vencimiento.credito_id,solicitud.formato_nuevo as formato from vencimiento join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id where (credito.credito_status_id = 4) AND (vencimiento.vencimiento_status_id = 3) AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) >= ".$_SESSION["x_dias_ini"].") AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) <= ".$_SESSION["x_dias_fin"].$sWhere.")";
	}else{
		$sSql = "select vencimiento.credito_id,solicitud.formato_nuevo as formato, solicitud.promotor_id from vencimiento join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id where (credito.credito_status_id in (1,3,4)) AND (vencimiento.vencimiento_status_id = 3) AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) >= ".$_SESSION["x_dias_ini"].") AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) <= ".$_SESSION["x_dias_fin"].$sWhere.")";
		
		if($_SESSION["php_project_esf_status_UserRolID"] == 12){
		if(empty($_SESSION["x_promo_srch"])){
			$sSql .= " AND $sDbWhereEncargado ";
			}
		}
	}
//	echo "where ancargado".$sDbWhereEncargado."<br>";
	
// Build SQL
if($_SESSION["php_project_esf_status_UserRolID"] == 16) { //gestor de cobranza
$x_listado_creditos = "";
// seleccionamos todos los credito que pertenecen la getor de cobranza
$sqlCreditogestor =  "SELECT * FROM gestor_credito WHERE usuario_id =  ".$_SESSION["php_project_esf_status_UserID"]."";
$rsCreditogestor = phpmkr_query($sqlCreditogestor,$conn) or die ("Error al seleccionar los credito del gestor".phpmkr_error()."sql:".$sqlCreditogestor);
while($rowCreditogestor =  phpmkr_fetch_array($rsCreditogestor)){
	$x_listado_creditos =  $x_listado_creditos.$rowCreditogestor["credito_id"].", ";
	}
	$x_listado_creditos = trim($x_listado_creditos,", ");

	$sSql = "select credito.credito_id, vencimiento.credito_id, credito.solicitud_id, solicitud.formato_nuevo as formato from vencimiento join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id where (credito.credito_status_id in (1,3,4)) AND (vencimiento.vencimiento_status_id = 3) AND (credito.credito_id in($x_listado_creditos)) AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) >= ".$_SESSION["x_dias_ini"].") AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) <= ".$_SESSION["x_dias_fin"].$sWhere.")";
	
}

// Build SQL
if($_SESSION["php_project_esf_status_UserRolID"] == 17) { // gerente de sucursal
//seleccionamos todos los promotres de la sucursal
$sSqls = "select gerente_sucursal_id, sucursal_id from gerente_sucursal where usuario_id = ".$_SESSION["php_project_esf_status_UserID"];
						//echo $sSql;
						$rs2 = phpmkr_query($sSqls,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
						if (phpmkr_num_rows($rs2) > 0) {
							$row2 = phpmkr_fetch_array($rs2);
							$_SESSION["php_project_esf_status_GerenteID"] = $row2["gerente_sucursal_id"];	
							$_SESSION["php_project_esf_SucursalID"] = $row2["sucursal_id"];						
							$bValidPwd = true;
						}

 $sqlListaPromotores = "SELECT * FROM promotor  WHERE  sucursal_id = ". $_SESSION["php_project_esf_SucursalID"]."";
	// echo   $sqlListaPromotores."<br>";
	 $rsPromotores = phpmkr_query($sqlListaPromotores, $conn) or die ("Error al selccioar los promotores de la sucursal". phpmkr_error()."sql:".$sqlListaPromotores);
	 while($rowPromotores = phpmkr_fetch_array($rsPromotores)){
		 $x_promotores .= $rowPromotores["promotor_id"].", ";	 
		 }
		 $x_promotores = trim($x_promotores, ", ");
		 //if(empty($filter["x_promo_srch"]))
		// $sDbWhereEncargado = " (solicitud.promotor_id IN ($x_promotores)  )";

	$sSql = "select vencimiento.credito_id, credito.solicitud_id, solicitud.formato_nuevo as formato from vencimiento join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id where (credito.credito_status_id in (1,3,4)) AND (vencimiento.vencimiento_status_id = 3) AND (solicitud.promotor_id IN ($x_promotores)) AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) >= ".$_SESSION["x_dias_ini"].") AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) <= ".$_SESSION["x_dias_fin"].$sWhere.") ";
}	
	
	$sSql .= " group by vencimiento.credito_id order by credito.credito_num+0";
	//echo $sSql;
	
	 echo "<br><br><br>".$sSqlA;

}


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
&nbsp;&nbsp;<a href="php_cartera_vencidalist.php?export=excel&x_dias_ini=<?php echo $x_dias_ini; ?>&x_dias_fin=<?php echo $x_dias_fin; ?>&x_gestor_srch=<?php echo $x_gestor_srch; ?>">Exportar a Excel</a>
<?php } ?>
</span></p>

<?php if ($sExport == "") { ?>
<form action="php_cartera_vencidalist.php" name="filtro" id="filtro" method="post">
<table width="886" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="125"><span class="phpmaker">Tipo de Credito</span></td>
    <td width="11">&nbsp;</td>
    <td width="147" class="phpmaker"><input name="x_credito_tipo_id" type="radio" id="x_credito_tipo_id" value="1" <?php if($_SESSION["x_credito_tipo_id"] == 1){ echo "checked='checked'"; }?> onclick="javascript: document.filtro.submit();" />
Personal</td>
    <td width="11">&nbsp;</td>
    <td width="128"><?php
		$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);  
		$x_estado_civil_idList = "<select name=\"x_credito_tipo_id2\" >";
		//$x_estado_civil_idList .= "<option value=''>Seleccione</option>";
		$x_estado_civil_idList .= "<option value='100' selected>TODOS</option>";
		$sSqlWrk = "SELECT `credito_tipo_id`, `descripcion` FROM `credito_tipo` order by descripcion";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["credito_tipo_id"] == @$_SESSION["x_credito_tipo_id"]) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		
		?></td>
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
    <td class="phpmaker"><input type="radio" name="x_credito_tipo_id" id="x_credito_tipo_id" value="2"  onclick="javascript: document.filtro.submit();"/ <?php if($_SESSION["x_credito_tipo_id"] == 2){ echo "checked='checked'"; }?> />
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
    <td class="phpmaker">
      <?php
		$x_estado_civil_idList = "<select name=\"x_sucursal_srch\" class=\"texto_normal\">";
		if($_SESSION["php_project_esf_status_UserRolID"] == 7) {
			$sSqlWrk = "SELECT sucursal.sucursal_id, nombre FROM sucursal join promotor on promotor.sucursal_id = sucursal.sucursal_id Where promotor.promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"];
		}else if($_SESSION["php_project_esf_status_UserRolID"] == 12){
			$sSqlWrk = "SELECT sucursal_id, nombre FROM sucursal where sucursal.sucursal_id = ".$_SESSION["php_project_esf_SucursalID"];
			
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
      </td>
    <td>&nbsp;</td>
    <td class="phpmaker">Fondo:</td>
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
		}else if($_SESSION["php_project_esf_status_UserRolID"] == 12){
		$sSqlWrk = "SELECT promotor_id, nombre_completo FROM promotor Where sucursal_id = ".$_SESSION["php_project_esf_SucursalID"]."";
		$x_estado_civil_idList .= "<option value=\"1000\">TODOS</option>";
		
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
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="phpmaker">Gestor</td>
    <td>&nbsp;</td>
    <td>    
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
      </select></td> 
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
    <td><span class="phpmaker">
      <input type="submit" name="Submit" value="Buscar &nbsp;(*)" />
    </span></td>
    <td>&nbsp;</td>
    <td><span class="phpmaker"><a href="php_cartera_vencidalist.php?cmd=reset">Mostrar Todos</a>&nbsp;&nbsp; </span></td>
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
Ver Crédito
		</span></td>
		<td valign="top"><span>        
Credito Num.
</span></td>		
		<td valign="top"><span>        
Fondo
</span></td>		

		<td valign="top"><span>
Promotor
		</span></td>
        <td valign="top"><span>
Gestor
		</span></td>
        <td valign="top"><span>
Zona
		</span></td>
		<td valign="top"><span>
Cliente
		</span></td>
        <td valign="top"><span>
Aval
		</span></td>
        
        		<td valign="top"><span>
Municipio
		</span></td>
        		<td valign="top"><span>
Calle
		</span></td>
        		<td valign="top"><span>
Colonia
		</span></td>
        <td valign="top"><span>
Telefono contacto
		</span></td>
		<td valign="top"><span>
Num. de Pagos Vencidos
		</span></td>		
		<td valign="top"><span>
Max. num de Dias vencido
		</span></td>
		<td valign="top">Dias sin pagar</td>
		<td valign="top"><span>
  Capital
		Vencido</span></td>
		<td valign="top">Capital Deudor</td>
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
        <td valign="top"><span>
Imprimir carta
		</span></td>
	</tr>
<?php
$x_tot_pagos_venc = 0;
$x_max_dias_venc = 0;
$x_ttotal_importe = 0;
$x_ttotal_importe_d = 0;
$x_ttotal_interes = 0;
$x_ttotal_moratorios = 0;
$x_ttotal_total = 0;
 #sql
 #############################################
 #############################################
 #echo $sSql;
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
	$x_solicitud_id = $row["solicitud_id"];
	$x_formato = $row["formato"];
	
	
	if($x_formato == 1){
		// formato nuevo de la solicitud
		$x_link = "modulos/php_solicitudviewIndividual.php";
		$x_link_2 = "modulos/php_solicitudeditSolidario.php";
		}else{
			// formato viejo de la solicitud			
		$x_link = "php_solicitudedit.php";
		$x_link_2 = "php_solicitud_caedit.php";
			}


	$sqlZona = "SELECT solicitud_id FROM  credito WHERE credito_id =  $x_credito_id ";
	$rsZona = phpmkr_query($sqlZona,$conn) or die("Erro zona".phpmkr_error().$sqlZona);
	$rowZona =  phpmkr_fetch_array($rsZona);
	$x_solicitud_id =  $rowZona["solicitud_id"];
	$x_zona_id  = 0;
	$sqlZona = "SELECT zona_id FROM  solicitud WHERE solicitud_id =  $x_solicitud_id ";
	$rsZona = phpmkr_query($sqlZona,$conn) or die("Erro zona".phpmkr_error().$sqlZona);
	$rowZona =  phpmkr_fetch_array($rsZona);
	$x_zona_id =  $rowZona["zona_id"];
	@phpmkr_free_result($rsZona);
	$sqlCliente = "SELECT cliente_id FROM  solicitud_cliente WHERE solicitud_id = $x_solicitud_id ";
	$rscliente =  phpmkr_query($sqlCliente, $conn) or die("Error la selec liente".phpmkr_error());
	$rowCliente = phpmkr_fetch_array($rscliente);
	$x_cliente_id = $rowCliente["cliente_id"];
	$x_telefono_casa= "";
	$x_telefono_cel = "";
	$x_telefono_list= "";
	$sqlTelefono = "SELECT * FROM telefono where telefono_tipo_id = 1 and cliente_id = $x_cliente_id";
	$rsTelefono = phpmkr_query($sqlTelefono,$conn)or die("Error al seleccionar el tele");
	while($rowTelefono = phpmkr_fetch_array($rsTelefono)){
		$x_telefono_casa = $x_telefono_casa.$rowTelefono["numero"].", ";
		}
		
	//$x_telefono_casa = "CASA: ".$x_telefono_casa;
	$sqlTelefono = "SELECT * FROM telefono where telefono_tipo_id = 2 and cliente_id = $x_cliente_id";
	$rsTelefono = phpmkr_query($sqlTelefono,$conn)or die("Error al seleccionar el tele");
	while($rowTelefono = phpmkr_fetch_array($rsTelefono)){
		$x_telefono_cel = $x_telefono_cel.$rowTelefono["numero"].", ";
		}	
	//$x_telefono_casa = "CEL: ".$x_telefono_casa;
	if( strlen($x_telefono_casa)> 5){
		$x_telefono_casa = "CASA: ".$x_telefono_casa;
		}
		
	if( strlen($x_telefono_cel)> 5){
		$x_telefono_cel = "CEL: ".$x_telefono_cel;
		}
	$x_telefono_list = 	$x_telefono_casa." ".$x_telefono_cel;
	$sSqlWrk = "select credito.importe, credito.credito_num, credito.credito_tipo_id, solicitud.solicitud_id from credito join solicitud on solicitud.solicitud_id = credito.solicitud_id where credito.credito_id  = $x_credito_id";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$rowwrk = phpmkr_fetch_array($rswrk);
	$x_credito_num = $rowwrk["credito_num"];	
	$x_credito_importe = $rowwrk["importe"];		
	$x_credito_tipo_id = $rowwrk["credito_tipo_id"];	
	$x_solicitud_id = $rowwrk["solicitud_id"];	
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
	$sSqlWrk = "select sum(importe) as pagado from vencimiento where credito_id = $x_credito_id and vencimiento_status_id = 2 ";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$rowwrk = phpmkr_fetch_array($rswrk);
	$x_total_importe_pagado = $rowwrk["pagado"];	
	@phpmkr_free_result($rswrk);

	$x_total_importe_d = $x_credito_importe - $x_total_importe_pagado;
	
	
//dias desde su ultimo pago

	$sSqlWrk = "SELECT TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(recibo.fecha_pago) as dias_up FROM vencimiento join recibo_vencimiento on recibo_vencimiento.vencimiento_id = vencimiento.vencimiento_id join recibo on recibo.recibo_id = recibo_vencimiento.recibo_id where (vencimiento.credito_id = $x_credito_id) AND (vencimiento.vencimiento_status_id = 2) order by recibo.fecha_pago desc limit 1";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$rowwrk = phpmkr_fetch_array($rswrk);
$x_dias_ultimo_pago = $rowwrk["dias_up"];		
@phpmkr_free_result($rswrk);	


?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
		<!-- vencimiento_id -->
		<td align="center"><span>
<?php if($x_credito_tipo_id == 1){ ?> 

       
<a href="<?php echo $x_link ?>?solicitud_id=<?php echo $x_solicitud_id; ?>" target="_blank">Ver</a>
<?php }else{ ?>
<a href="<?php echo $x_link_2;?>?solicitud_id=<?php echo $x_solicitud_id; ?>" target="_blank">Ver</a>
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
<td>

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

<td><?php 
echo $x_zona_id;

?></td>
		<td align="left"><span>
<?php 

$x_aval = "";
		if($x_credito_tipo_id == 1){
			$sSqlWrk = "SELECT cliente.nombre_completo as cliente_nombre, cliente.apellido_paterno, cliente.apellido_materno FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id   Where credito.credito_id = $x_credito_id ";
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
<td>
<span>
<?php 
$x_aval = "";
$sql_dir = "";
$x_representante_cliente_id = "";
$x_representante  = "";
if($x_credito_tipo_id == 1 ||  $x_credito_tipo_id == 2  ){
	#echo "tipo c =". $x_credito_tipo_id;
			$sSqlWrka = "SELECT  aval.nombre_completo as aval_nombre, aval.apellido_paterno as aval_ap, aval.apellido_materno as aval_am FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join aval on aval.solicitud_id = solicitud.solicitud_id Where credito.credito_id = $x_credito_id ";
		
		if($x_credito_tipo_id == 2 ){
			//buscamos el nombre del representante del grupo y su cliente id. para despues buscar esa direccion 
			#echo "tipo dos";
			$sqlGrupo = " SELECT * FROM creditosolidario  join solicitud_grupo on solicitud_grupo.grupo_id = creditosolidario.creditoSolidario_id where solicitud_grupo.solicitud_id = $x_solicitud_id ";
			$responseGrupo = phpmkr_query($sqlGrupo,$conn) or die ("error al ejecutar query grupo".phpmkr_error()."sql: ".$sqlGrupo);
			$rowGrupo = phpmkr_fetch_array($responseGrupo);
			$x_creditoSolidario_id =  $rowGrupo["creditoSolidario_id"];
			$x_nombre_grupo = $rowGrupo["nombre_grupo"];
			
			$x_cont_g = 1;
			while($x_cont_g <= 10){
				
				$GLOBALS["x_integrante_$x_cont_g"] = $rowGrupo["integrante_$x_cont_g"];
				$GLOBALS["x_monto_$x_cont_g"] =$rowGrupo["monto_$x_cont_g"];
				$GLOBALS["x_rol_integrante_$x_cont_g"] = $rowGrupo["rol_integrante_$x_cont_g"]; 
				$GLOBALS["x_cliente_id_$x_cont_g"] = $rowGrupo["cliente_id_$x_cont_g"];
				
				//BUSCO AL REPRESENTANTE DEL GRUPO
				if($GLOBALS["x_rol_integrante_$x_cont_g"] == 1){
					$GLOBALS["$x_representate_grupo"] = $rowGrupo["integrante_$x_cont_g"];
					$x_representante_cliente_id =  $rowGrupo["cliente_id_$x_cont_g"];
					if(!empty($x_representante_cliente_id) and ($x_representante_cliente_id>0)){
						 $sql_dir = "AND direccion.cliente_id =  $x_representante_cliente_id ";
					}
					}
				
				$x_cont_g++;
				}
			#echo "rep id".$x_representante_cliente_id."<br>";
			$x_representante_cliente_id =$x_representante_cliente_id +0;
			if(!empty ($x_representante_cliente_id) && ($x_representante_cliente_id>0)){
			$sSqlWrkrep = "SELECT  cliente.nombre_completo as cliente_nombre, cliente.apellido_paterno as cliente_ap, cliente.apellido_materno as cliente_am FROM cliente where cliente_id =  $x_representante_cliente_id ";
			 $rswrkrep = phpmkr_query($sSqlWrkrep,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrkrep);
			 $rowwrkrep = phpmkr_fetch_array($rswrkrep);
			 $x_representante = $rowwrkrep["cliente_nombre"]." ". $rowwrkrep["cliente_ap"]." ".$rowwrkrep["cliente_am"];
			 echo "Rep :". $x_representante;
			}
			 
			
			}
		$x_aval = "";
		$rswrka = phpmkr_query($sSqlWrka,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrka);
		#echo "sql:".$sSqlWrka."<br>";
		if ($rswrka && $rowwrka = phpmkr_fetch_array($rswrka)) {
			
			$x_aval = $rowwrka["aval_nombre"]." ".$rowwrka["aval_ap"]." ".$rowwrka["aval_am"];
			if(!empty($x_representante)){
				$x_aval = "Representante :" .$x_representante." ";
				$x_representante = "";
				}
		}else{
			$x_aval = "";									
		}
		@phpmkr_free_result($rswrka);
		
}if (empty($x_aval) or $x_aval == ""){
	#echo "aval vacio";
	$sSqlWrka = "SELECT  datos_aval.nombre_completo as aval_nombre, datos_aval.apellido_paterno as aval_ap, datos_aval.apellido_materno as aval_am FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join datos_aval on datos_aval.solicitud_id = solicitud.solicitud_id Where credito.credito_id = $x_credito_id ";
	if($x_credito_tipo_id == 2 ){
			//buscamos el nombre del representante del grupo y su cliente id. para despues buscar esa direccion 
			#echo "tipo dos";
			$sqlGrupo = " SELECT * FROM creditosolidario  join solicitud_grupo on solicitud_grupo.grupo_id = creditosolidario.creditoSolidario_id where solicitud_grupo.solicitud_id = $x_solicitud_id ";
			$responseGrupo = phpmkr_query($sqlGrupo,$conn) or die ("error al ejecutar query grupo".phpmkr_error()."sql: ".$sqlGrupo);
			$rowGrupo = phpmkr_fetch_array($responseGrupo);
			$x_creditoSolidario_id =  $rowGrupo["creditoSolidario_id"];
			$x_nombre_grupo = $rowGrupo["nombre_grupo"];
			
			$x_cont_g = 1;
			while($x_cont_g <= 10){
				
				$GLOBALS["x_integrante_$x_cont_g"] = $rowGrupo["integrante_$x_cont_g"];
				$GLOBALS["x_monto_$x_cont_g"] =$rowGrupo["monto_$x_cont_g"];
				$GLOBALS["x_rol_integrante_$x_cont_g"] = $rowGrupo["rol_integrante_$x_cont_g"]; 
				$GLOBALS["x_cliente_id_$x_cont_g"] = $rowGrupo["cliente_id_$x_cont_g"];
				
				//BUSCO AL REPRESENTANTE DEL GRUPO
				if($GLOBALS["x_rol_integrante_$x_cont_g"] == 1){
					$GLOBALS["$x_representate_grupo"] = $rowGrupo["integrante_$x_cont_g"];
					$x_representante_cliente_id =  $rowGrupo["cliente_id_$x_cont_g"];
					if(!empty($x_representante_cliente_id) and ($x_representante_cliente_id>0)){
						 $sql_dir = "AND direccion.cliente_id =  $x_representante_cliente_id ";
					}
					}
				
				$x_cont_g++;
				}
				$x_representante_cliente_id =$x_representante_cliente_id +0;
			#echo "rep id".$x_representante_cliente_id."<br>";
			if(!empty ($x_representante_cliente_id) && ($x_representante_cliente_id>0)){
			$sSqlWrkrep = "SELECT  cliente.nombre_completo as cliente_nombre, cliente.apellido_paterno as cliente_ap, cliente.apellido_materno as cliente_am FROM cliente where cliente_id =  $x_representante_cliente_id ";
			 $rswrkrep = phpmkr_query($sSqlWrkrep,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrkrep);
			 $rowwrkrep = phpmkr_fetch_array($rswrkrep);
			 $x_representante = $rowwrkrep["cliente_nombre"]." ". $rowwrkrep["cliente_ap"]." ".$rowwrkrep["cliente_am"];
			 echo "Rep :". $x_representante;
			}
			 
			
			}
		$x_aval = "";
		$rswrka = phpmkr_query($sSqlWrka,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrka);
	#	echo "sql:".$sSqlWrka."<br>";
		if ($rswrka && $rowwrka = phpmkr_fetch_array($rswrka)) {
			
			$x_aval = $rowwrka["aval_nombre"]." ".$rowwrka["aval_ap"]." ".$rowwrka["aval_am"];
			if(!empty($x_representante)){
				$x_aval = "Representante :" .$x_representante." ";
				$x_representante = "";
				}
		}else{
			$x_aval = $rowwrka["aval_nombre"]." ".$rowwrka["aval_ap"]." ".$rowwrka["aval_am"];										
		}
		@phpmkr_free_result($rswrka);
	
	
	}
echo $x_aval; ?>
</span>
</td>
<td align="left"><span>
<?php 
$x_municipio  = "";
$x_calle = "";
$x_colonia = "";
$x_municipio_a  = "";
$x_calle_a = "";
$x_colonia_a = "";
$x_numero_ext = "";
$sSqlWrkd = "SELECT delegacion.descripcion as municipio, direccion.cliente_id, direccion.telefono, direccion.telefono_secundario,direccion.telefono_movil,direccion.telefono_movil_2, direccion.calle as calle, direccion.colonia as colonia, direccion.numero_exterior as no_ext,direccion.delegacion_id  FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id join direccion on direccion.cliente_id = cliente.cliente_id join delegacion on delegacion.delegacion_id = direccion.delegacion_id   Where credito.credito_id = $x_credito_id  and direccion.direccion_tipo_id = 1 ".$sql_dir. " ";

$rswrkd = phpmkr_query($sSqlWrkd,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrkd);
$rowwrkd = phpmkr_fetch_array($rswrkd);
$x_municipio = $rowwrkd["municipio"];
$x_calle = $rowwrkd["calle"];
$x_numero_ext = $rowwrkd["no_ext"];
$x_calle = $x_calle ." ".$x_numero_ext;
$x_colonia =  $rowwrkd["colonia"];
$x_telefono = $rowwrkd["telefono"];
$x_telefono_secundario = $rowwrkd["telefono_secundario"];
$x_telefono_movil = $rowwrkd["telefono_movil"];
$x_telefono_movil_2 = $rowwrkd["telefono_movil_2"];


if($x_formato == 1){
if((!empty($rowwrkd["telefono_movil"])) && (!is_null($rowwrkd["telefono_movil"]))){
	$x_numero_telefonico = "Cel :". $rowwrkd["telefono_movil"];	
	
							}else if((!empty($rowwrkd["telefono_movil_2"])) && (!is_null($rowwrkd["telefono_movil_2"]))){
								$x_numero_telefonico = "Cel 2:". $rowwrkd["telefono_movil_2"];
								
							}else if((!empty($rowwrkd["telefono"])) && (!is_null($rowwrkd["telefono"]))){
										$x_numero_telefonico = $rowwrkd["telefono"];				
								}else if((!empty($rowwrkd["telefono_secundario"])) && (!is_null($rowwrkd["telefono_secundario"]))){
									$x_numero_telefonico = $rowwrkd["telefono_secundario"];
									}									
									if (empty($x_numero_telefonico)){
							$x_numero_telefonico = "No hay telefono";
							}					
							

}else{

if((!empty($rowwrkd["telefono_secundario"])) && (!is_null($rowwrkd["telefono_secundario"]))){
	$x_numero_telefonico = "Cel 1".$rowwrkd["telefono_secundario"];
							}else if((!empty($rowwrkd["telefono_movil_2"])) && (!is_null($rowwrkd["telefono_movil_2"]))){
								$x_numero_telefonico = "Cel 2:". $rowwrkd["telefono_movil_2"];
							}else if((!empty($rowwrkd["telefono"])) && (!is_null($rowwrkd["telefono"]))){
								$x_numero_telefonico = "prim ".$rowwrkd["telefono"];							
								}else if((!empty($rowwrkd["telefono_movil"])) && (!is_null($rowwrkd["telefono_movil"]))){
									$x_numero_telefonico = "Sec :". $rowwrkd["telefono_movil"];	
									}									
									if (empty($x_numero_telefonico)){
							$x_numero_telefonico = "No hay telefono";
							}
							
}

@phpmkr_free_result($rswrkd);
$x_numero_ext_a = "";
$x_municipio_a = "";
$x_calle_a = "";
$x_colonia_a = "";
$sSqlWrkda = "SELECT delegacion.descripcion as municipio, direccion.cliente_id,direccion.telefono, direccion.telefono_secundario,direccion.telefono_movil,direccion.telefono_movil_2, direccion.calle as calle, direccion.colonia as colonia, direccion.numero_exterior as no_ext,direccion.delegacion_id  FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id join aval on aval.solicitud_id = solicitud.solicitud_id join   direccion on direccion.aval_id = aval.aval_id join delegacion on delegacion.delegacion_id = direccion.delegacion_id    Where credito.credito_id = $x_credito_id and direccion.direccion_tipo_id = 3 ";
$rswrkda = phpmkr_query($sSqlWrkda,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrkda);
$rowwrkda = phpmkr_fetch_array($rswrkda);
$x_municipio_a = $rowwrkda["municipio"];
$x_calle_a = $rowwrkda["calle"];
$x_numero_ext_a = $rowwrkda["no_ext"];
$x_calle_a = $x_calle_a ." ".$x_numero_ext_a;
$x_colonia_a =  $rowwrkda["colonia"];


if($x_formato == 1){
if((!empty($rowwrkd["telefono_movil"])) && (!is_null($rowwrkd["telefono_movil"]))){
	$x_numero_telefonicoa = "Cel :". $rowwrkd["telefono_movil"];	
	
							}else if((!empty($rowwrkd["telefono_movil_2"])) && (!is_null($rowwrkd["telefono_movil_2"]))){
								$x_numero_telefonicoa = "Cel 2:". $rowwrkd["telefono_movil_2"];
								
							}else if((!empty($rowwrkd["telefono"])) && (!is_null($rowwrkd["telefono"]))){
										$x_numero_telefonicoa = $rowwrkd["telefono"];				
								}else if((!empty($rowwrkd["telefono_secundario"])) && (!is_null($rowwrkd["telefono_secundario"]))){
									$x_numero_telefonicoa = $rowwrkd["telefono_secundario"];
									}									
									if (empty($x_numero_telefonico)){
							$x_numero_telefonicoa = "No hay telefono";
							}					
							

}else{

if((!empty($rowwrkd["telefono_secundario"])) && (!is_null($rowwrkd["telefono_secundario"]))){
	$x_numero_telefonicoa = "Cel 1".$rowwrkd["telefono_secundario"];
							}else if((!empty($rowwrkd["telefono_movil_2"])) && (!is_null($rowwrkd["telefono_movil_2"]))){
								$x_numero_telefonicoa = "Cel 2:". $rowwrkd["telefono_movil_2"];
							}else if((!empty($rowwrkd["telefono"])) && (!is_null($rowwrkd["telefono"]))){
								$x_numero_telefonicoa = "prim ".$rowwrkd["telefono"];							
								}else if((!empty($rowwrkd["telefono_movil"])) && (!is_null($rowwrkd["telefono_movil"]))){
									$x_numero_telefonicoa = "Sec :". $rowwrkd["telefono_movil"];	
									}									
									if (empty($x_numero_telefonico)){
							$x_numero_telefonicoa = "No hay telefono";
							}
							
}
/*if((!empty($rowwrkda["telefono"])) && (!is_null($rowwrkda["telefono"]))){
	$x_numero_telefonicoa = $rowwrkda["telefono"];
							}else if((!empty($rowwrkda["telefono_secundario"])) && (!is_null($rowwrkda["telefono_secundario"]))){
								$x_numero_telefonicoa = $rowwrkda["telefono_secundario"];
							}else if((!empty($rowwrkda["telefono_secundario"])) && (!is_null($rowwrkda["telefono_movil"]))){
								$x_numero_telefonicoa = "Cel :". $rowwrkda["telefono_movil"];							
								}else if((!empty($rowwrkda["telefono_secundario"])) && (!is_null($rowwrkda["ttelefono_movil_2"]))){
									$x_numero_telefonicoa = "Cel:". $rowwrkda["telefono_movil_2"];
									}									
									if (empty($x_numero_telefonico)){
							$x_numero_telefonicoa = "No hay telefono";
							}*/

if(empty($x_municipio_a)){
	// municipio esta vacio se busca el datos aval...
	$sSqlWrkda = "SELECT delegacion.descripcion as municipio,datos_aval.telefono_p, 
	datos_aval.telefono_c, datos_aval.calle as calle, datos_aval.colonia as colonia, datos_aval.delegacion  FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id join datos_aval on datos_aval.solicitud_id = solicitud.solicitud_id  join delegacion on delegacion.delegacion_id = datos_aval.delegacion    Where credito.credito_id = $x_credito_id ";
$rswrkda = phpmkr_query($sSqlWrkda,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrkda);
$rowwrkda = phpmkr_fetch_array($rswrkda);
$x_municipio_a = $rowwrkda["municipio"];
$x_calle_a = $rowwrkda["calle"];
$x_numero_ext_a = $rowwrkda["no_ext"];
$x_calle_a = $x_calle_a ." ".$x_numero_ext_a;
$x_colonia_a =  $rowwrkda["colonia"];
	
	}


@phpmkr_free_result($rswrkda);

echo  $x_municipio."<br>".$x_municipio_a; ?>
</span></td>
<td align="left"><span>
<?php echo $x_calle." Telefono : ".$x_numero_telefonico."<br>".$x_calle_a." Telefono : ".$x_numero_telefonicoa;
$x_numero_telefonico = "";
$x_numero_telefonicoa = "";
?>
</span></td>
<td align="left"><span>
<?php echo  $x_colonia."<br>".$x_colonia_a ;?>
</span></td>
<td align="left"><span>
<?php echo  $x_telefono_list ;?>
</span></td>
		<td align="right"><span>
<?php echo $x_contador; ?>
</span></td>
		<td align="right"><span>
<?php echo $x_dias_venc_ant; ?>
</span></td>
		<td align="right">
		<?php echo $x_dias_ultimo_pago; ?></td>
		<!-- importe -->
		<td align="right"><span>
<?php echo FormatNumber($x_total_importe,2,0,0,1) ?>
</span></td>
		<td align="right"><?php echo FormatNumber($x_total_importe_d,2,0,0,1) ?></td>
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
<td><span><a href="php_imprime_carta_menu.php?credito_id=<?php echo $x_credito_id;?>" target="_blank">Seleccionar carta </a></span></td>
	</tr>
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
		<td valign="top" colspan="10"><span>
<b>Totales</b>
		</span></td>
		<td valign="top"><span>

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
		<td align="right"></td>
		<td align="right"><span>
<?php echo FormatNumber($x_ttotal_importe_d,2,0,0,1) ?>        
		</span></td>
		<td align="right">
<?php echo FormatNumber($x_ttotal_interes,2,0,0,1) ?>        
        </td>
		<td align="right"><span>
<?php echo FormatNumber($x_ttotal_moratorios,2,0,0,1) ?>        
		</span></td>
		<td align="right"><span>
<?php echo FormatNumber($x_ttotal_total,2,0,0,1) ?>                
		</span></td>
		<td align="right"><span>
<?php echo FormatNumber($nRecCount,0,0,0,1) ?>                        
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
