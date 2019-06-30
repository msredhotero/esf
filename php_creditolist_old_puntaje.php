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



$filter = array();



$filter['x_credito_tipo_id'] = 100;

$filter['x_nombre_srch'] = '';

$filter['x_apepat_srch'] = '';

$filter['x_apemat_srch'] = '';

$filter['x_crenum_srch'] = '';

$filter['x_clinum_srch'] = '';

$filter['x_cresta_srch'] = '';

$filter['x_entidad_srch'] = '';

$filter['x_delegacion_srch'] = '';

$filter['x_sucursal_srch'] = '';

$filter['x_promo_srch'] = ''; 

$filter['x_vendedor_srch'] = ''; 

$filter['x_credito_tipo_id'] = 100;



$filter['x_gestor_srch'] = ''; 



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











$x_posteo = $x_nombre_srch.$x_apepat_srch.$x_apemat_srch.$x_crenum_srch.$x_clinum_srch.$x_cresta_srch.$x_sucursal_srch.$x_empresa_id.$x_fondeo_credito_id;









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

	

	if(!empty($filter["x_empresa_id"])){

		if(!empty($filter["x_empresa_id"]) && ($filter["x_empresa_id"] != "999999999")){

			

			

			if(!empty($filter["x_fondeo_credito_id"])){

				$ssrch_cre .= "(credito.credito_id in (select credito_id from  fondeo_colocacion join fondeo_credito on fondeo_credito.fondeo_credito_id = fondeo_colocacion.fondeo_credito_id join fondeo_empresa on fondeo_empresa.fondeo_empresa_id = fondeo_credito.fondeo_empresa_id where (fondeo_empresa.fondeo_empresa_id = ".$filter["x_empresa_id"].") AND (fondeo_credito.fondeo_credito_id = ".$filter["x_fondeo_credito_id"].")))) AND";

			}else{

				$ssrch_cre .= "(credito.credito_id in (select credito_id from  fondeo_colocacion join fondeo_credito on fondeo_credito.fondeo_credito_id = fondeo_colocacion.fondeo_credito_id join fondeo_empresa on fondeo_empresa.fondeo_empresa_id = fondeo_credito.fondeo_empresa_id where (fondeo_empresa.fondeo_empresa_id = ".$filter["x_empresa_id"]."))) AND ";

			}

			

			$ssrch_cre = substr($ssrch_cre, 0, strlen($ssrch_cre)-5);		

		}

		

	}





/*

SELECT credito.* FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id WHERE 



(credito.credito_id in 

 

(select credito_id from fondeo_colocacion join fondeo_credito on fondeo_credito.fondeo_credito_id = fondeo_colocacion.fondeo_credito_id join fondeo_empresa on fondeo_empresa.fondeo_empresa_id = fondeo_credito.fondeo_empresa_id where (fondeo_empresa.fondeo_empresa_id = 7) AND (fondeo_credito.fondeo_credito_id = 7)

																																																																					 

																																																																					 )

																																																																											

																																																																											ORDER BY credito.credito_num+0 desc

																																																																																																																			 */

																																																																																																																			  

																																																																																																																			  if ((!empty($filter["x_sucursal_srch"])) && (!empty($filter["x_promo_srch"]))){																																																																																																																			   // se unen los dos queries..

		if(($filter["x_sucursal_srch"] != "1000") && ($filter["x_promo_srch"] != "1000"  ) ){

			#echo "los dos";

			$ssrch_cre .= "(promotor.sucursal_id = ".$filter["x_sucursal_srch"].") AND (promotor.promotor_id = ".$filter["x_promo_srch"].") AND ";

			$ssrch_cre = substr($ssrch_cre, 0, strlen($ssrch_cre)-5);

			}																						  

																																																																																																																				   else if((!empty($filter["x_sucursal_srch"]))){

																																																																																																																					   #echo "entr solo en sucursal";

		if((!empty($filter["x_sucursal_srch"])) && ($filter["x_sucursal_srch"] != "1000")){

			$ssrch_cre .= "(promotor.sucursal_id = ".$filter["x_sucursal_srch"].") AND ";

			$ssrch_cre = substr($ssrch_cre, 0, strlen($ssrch_cre)-5);		

		}

	}else if(!empty($filter["x_promo_srch"])){

	#	echo "entra solo en promotor";

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

		#echo "ROL DE PROMOTOR".$_SESSION["php_project_esf_status_PromotorID"];

		$sDbWhereP .= " (solicitud.promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"]. ") AND ";

		#echo $sSql;

	}else{

		$sSql = "SELECT credito.* FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id $x_join";

	

//	$sSql = "SELECT credito.* FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id ";

	}

	//echo $sSql ."<br><br>";

	



	

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

	

	$ssrch_cre .= "(credito.credito_tipo_id = 2) AND ";

	

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

	#echo "ROL DE PROMOTOR".$_SESSION["php_project_esf_status_PromotorID"];

		$sSql = "SELECT credito.* FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id  $x_join ";

		$sDbWhereP .= " (solicitud.promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"]. ") AND ";

	}else{

		$sSql = "SELECT credito.* FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id  $x_join ";

	



	}



	//echo $sSql ."<br><br>";

	

	

	

}









// Load Default Filter

$sDefaultFilter = "";

$sGroupBy = "";

$sHaving = "";



// Load Default Order

$sDefaultOrderBy = " credito.credito_num+0 desc ";





// filtor para el tipo de rol de externos





// seleccionamos los datos de la scursal por si el rol con el que se logue ael usuario es de un responsable de sucursal.

 if ($_SESSION["php_project_esf_status_UserRolID"] == 12 || $_SESSION["php_project_esf_status_UserRolID"] == 17 || $_SESSION["php_project_esf_status_UserRolID"] == 18){

	 // el usuario es un encargado de suscursal o un gerente de sucursal deben poder ver todos los credito que pertenecen a su sucursal

	 // se agrega una condicion al where para que en listado solo se vean los credito de la sucursal.

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

		

		if($_SESSION["php_project_esf_status_UserRolID"] == 17){

						//echo "entro en encargado";					

						$sSqls = "select gerente_sucursal_id, sucursal_id from gerente_sucursal where usuario_id = ".$_SESSION["php_project_esf_status_UserID"];

						//echo $sSql;

						$rs2 = phpmkr_query($sSqls,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);

						if (phpmkr_num_rows($rs2) > 0) {

							$row2 = phpmkr_fetch_array($rs2);

							$_SESSION["php_project_esf_status_GerenteID"] = $row2["gerente_sucursal_id"];	

							$_SESSION["php_project_esf_SucursalID"] = $row2["sucursal_id"];						

							$bValidPwd = true;

						}

						

		}	

		

		if($_SESSION["php_project_esf_status_UserRolID"] == 18){

						//echo "entro en encargado";					

						$sSqls = "select  sucursal_id from gerente_fashion_price where usuario_id = ".$_SESSION["php_project_esf_status_UserID"];

						//echo $sSql;

						$rs2 = phpmkr_query($sSqls,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);

						if (phpmkr_num_rows($rs2) > 0) {

							$row2 = phpmkr_fetch_array($rs2);

							$_SESSION["php_project_esf_status_GerenteID"] = $row2["gerente_sucursal_id"];	

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

		 $sDbWhereEncargado = " (solicitud.promotor_id IN ($x_promotores)  ) AND ";

		 

		  if($_SESSION["php_project_esf_status_UserRolID"] == 18){

		 $sDbWhereEncargado .=  " credito.credito_tipo_id = 5 AND ";

		  

		  }

		  if(!empty($filter["x_vendedor_srch"])){

			 // echo "filtro vendedor".$filter["x_vendedor_srch"]."<br>";

		if((!empty($filter["x_vendedor_srch"])) && ($filter["x_vendedor_srch"] != "1000")){

			$ssrch_vend .= " and (solicitud.vendedor_id = ".$filter["x_vendedor_srch"].")  ";

			//$ssrch_cre .= "  (solicitud.vendedor_id = ".$filter["x_vendedor_srch"].")  AND  ";

			//$ssrch_cre = substr($ssrch_cre, 0, strlen($ssrch_cre)-5);		

		}

		  }

		 

	 }else{

		  if(!empty($filter["x_vendedor_srch"])){

		if((!empty($filter["x_vendedor_srch"])) && ($filter["x_vendedor_srch"] != "1000")){

			//$ssrch_vend .= " and (solicitud.vendedor_id = ".$filter["x_vendedor_srch"].")  ";

			$ssrch_cre .= "  (solicitud.vendedor_id = ".$filter["x_vendedor_srch"].")  AND  ";

			$ssrch_cre = substr($ssrch_cre, 0, strlen($ssrch_cre)-5);		

		}

		  }

	 }

		 

		 







if($_SESSION["php_project_esf_status_UserRolID"] == 10) {

	// SI EL PERFIL  CORRESPONDE CON EL DESPACHO EXTERNO SOLO SE MOSTRARAN LOS CREDITOS Y SOLICITUDES QUE ESTEN EL LA LISTA DE  LA TABLA CREDITO_EN_EXTERNO

	$sqlLista = "SELECT credito_id FROM credito_en_externo ";

	$rsLista = phpmkr_query($sqlLista, $conn) or die ("Erroe al seleccionar la lista de los que estan en credito_externio". phpmkr_error()."sql :". $sqlLista);

	$x_lis_externo = "";

	while($rowLista = phpmkr_fetch_array($rsLista)){

		$x_lis_externo .= $rowLista["credito_id"]. ", ";		

		}

	$x_lis_externo = trim($x_lis_externo, ", ");

	$sNPWhere = "(credito.credito_id IN ($x_lis_externo) ) AND ";

	}

	

	// si  el usuario es un gestor de cobranza

	

if($_SESSION["php_project_esf_status_UserRolID"] == 16) {

	// SI EL PERFIL  CORRESPONDE CON EL DESPACHO EXTERNO SOLO SE MOSTRARAN LOS CREDITOS Y SOLICITUDES QUE ESTEN EL LA LISTA DE  LA TABLA CREDITO_EN_EXTERNO

	$sqlLista = "SELECT credito_id FROM  gestor_credito where usuario_id = ".$_SESSION["php_project_esf_status_UserID"]." ";

	$rsLista = phpmkr_query($sqlLista, $conn) or die ("Erroe al seleccionar la lista de los que estan en credito_externio". phpmkr_error()."sql :". $sqlLista);

	$x_lis_externo = "";

	while($rowLista = phpmkr_fetch_array($rsLista)){

		$x_lis_externo .= $rowLista["credito_id"]. ", ";		

		}

	$x_lis_externo = trim($x_lis_externo, ", ");

	$sNPWhere = "(credito.credito_id IN ($x_lis_externo) ) AND ";

	}	

#echo "$sNPWhere".$sNPWhere;

// Build WHERE condition

if($filter["php_project_esf_status_UserRolID"] == 7) {

	$sDbWhere = "(solicitud.promotor_id = ".$filter["php_project_esf_status_PromotorID"]. ") AND ";

}else{

	if(!empty($filter["x_promo_srch"]) && $filter["x_promo_srch"] != 1000){

		$filter["x_promo_srch"] = $filter["x_promo_srch"];

		$sDbWhere = "(solicitud.promotor_id = ".$filter["x_promo_srch"]. ") AND ";	

		$sDbWhereP = "";	

	}else{

		$filter["x_promo_srch"] = "";		

		$sDbWhere = "";		

	}

}



//echo "<br>ddd".$sDbWhereP."<br>";



$sWhere = $ssrch_cli.$ssrch_cre.$sNPWhere.$sCreW.$sDbWhereEncargado.$ssrch_vend.$sDbWhereP;



if($_SESSION["php_project_esf_status_UserRolID"] == 11){

if(!empty($sWhere)){

	$sWhere .= " fondeo_colocacion.fondeo_credito_id = 7 ";

	}else {

		$sWhere = "fondeo_colocacion.fondeo_credito_id = 7 ";

		}

		

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



<script type="text/javascript" src="scripts/jquery-1.4.js"></script>

    <script type="text/javascript" src="scripts/jquery-ui-1.8.custom.min.js"></script>

    <script type="text/javascript" src="scripts/jquery.themeswitcher.js"></script>

   <script>

  

//Cuando el DOM esté descargado por completo:

$(document).ready(function(){	

	$(".cambia_fondo").click(function(event) {

		var capa_credito_id = this.id;		

		var myarr = capa_credito_id.split(".");

		var id = myarr[1];

		var capa = myarr[0];

		

		//alert("el fondeo del credito se cambiara"+ capa_credito_id);

		//alert("capa"+capa);

		//alert("id"+id);

                    $("#capa_fondo_"+id).load('cambia_credito_fondo.php?x_credito_id='+id);

                });

	

	

	$("#Submit").click(function(){

		$("#x_paginacion").val(1);

		

	});

	

	});

	

	

	



   </script> 



<?php





//echo "QUERY  ".$sSql;

// Set up Record Set
//echo $sSql;
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

<p><span class="phpmaker">CREDITOS

<?php if ($sExport == "") { ?>





<?php if(($_SESSION["php_project_esf_status_UserRolID"] == 1) || ($_SESSION["php_project_esf_status_UserRolID"] == 4)){

?>

&nbsp;&nbsp;<a href="php_creditolist.php?export=excel">Exportar a Excel</a>

&nbsp;&nbsp;<a href="php_creditolist.php?export=word">Exportar a Word</a>

<?php } ?>

<?php } ?>

</span></p>

<?php if ($sExport == "") { ?>

<form action="php_creditolist.php" name="filtros" method="post">

<input type="hidden" name="x_paginacion" value="<?php echo $x_paginacion?>"  id="x_paginacion"/>

<table width="879" border="0" cellpadding="0" cellspacing="0">

	<tr>

	  <td width="135">Tipo de Credito</td>

	  <td width="12">&nbsp;</td>

	  <td width="117"><!-- <input name="x_credito_tipo_id" type="radio" id="x_credito_tipo_id" value="1" <?php if($filter["x_credito_tipo_id"] == 1){ echo "checked='checked'"; }?> onclick="javascript: document.filtros.submit();" /> -->

<!-- Personal --> <?php

		$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);  

		$x_estado_civil_idList = "<select name=\"x_credito_tipo_id\" >";

		//$x_estado_civil_idList .= "<option value=''>Seleccione</option>";

		$x_estado_civil_idList .= "<option value='100' selected>TODOS</option>";

		$sSqlWrk = "SELECT `credito_tipo_id`, `descripcion` FROM `credito_tipo` order by descripcion";

		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);

		if ($rswrk) {

			$rowcntwrk = 0;

			while ($datawrk = phpmkr_fetch_array($rswrk)) {

				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";

				if ($datawrk["credito_tipo_id"] == @$filter["x_credito_tipo_id"]) {

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

	  <td width="12">&nbsp;</td>

	  <td width="139">&nbsp;</td>

	  <td width="12">&nbsp;</td>

	  <td width="108">&nbsp;</td>

	  <td width="12">&nbsp;</td>

	  <td width="158">&nbsp;</td>

	  <td width="154">&nbsp;</td>

	  </tr>

	<tr>

	  <td>&nbsp;</td>

	  <td>&nbsp;</td>

	  <td><!-- <input type="radio" name="x_credito_tipo_id" id="x_credito_tipo_id2" value="2"  onclick="javascript: document.filtros.submit();"/ <?php if($_SESSION["x_credito_tipo_id"] == 2){ echo "checked='checked'"; }?> /> -->

<!-- Grupo --></td>

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

	  <td>Numero de Credito</td>

	  <td>&nbsp;</td>

	  <td><span class="phpmaker">

	    <input name="x_crenum_srch" type="text" id="x_crenum_srch" value="<?php echo $filter["x_crenum_srch"]; ?>" size="20" />

	  </span></td>

	  <td>&nbsp;</td>

	  <td>Numero de Cliente </td>

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

	  <td>Status del Cr&eacute;dito</td>

	  <td>&nbsp;</td>

	  <td><span class="phpmaker">

	    <?php

		$x_estado_civil_idList = "<select name=\"x_cresta_srch\" class=\"texto_normal\">";

		if ($filter["x_credito_status_id_filtro"] == 0){

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

				if ($datawrk["credito_status_id"] == $filter["x_cresta_srch"]) {

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

	  <td>&nbsp;</td>

	  <td><span class="phpmaker">

<?php

		$x_estado_civil_idList = "<select name=\"x_sucursal_srch\" class=\"texto_normal\">";

		if($_SESSION["php_project_esf_status_UserRolID"] == 7) {

			$sSqlWrk = "SELECT sucursal.sucursal_id, nombre FROM sucursal join promotor on promotor.sucursal_id = sucursal.sucursal_id Where promotor.promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"];

		}else if($_SESSION["php_project_esf_status_UserRolID"] == 12 || $_SESSION["php_project_esf_status_UserRolID"] == 17){

			$sSqlWrk = "SELECT sucursal_id, nombre FROM sucursal Where sucursal.sucursal_id = ".$_SESSION["php_project_esf_SucursalID"];

			

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

		?>      

      

      

      </span></td>

	  <td>&nbsp;</td>

	  <td>Promotor</td>

	  <td valign="middle"><span class="phpmaker">

	    <?php

		$x_estado_civil_idList = "<select name=\"x_promo_srch\" class=\"texto_normal\">";

		if($_SESSION["php_project_esf_status_UserRolID"] == 7) {

			$sqlp = "SELECT supervisor FROM promotor WHERE promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"]."";

			$rss = phpmkr_query($sqlp, $conn) or die ("error al selecciona el campo de supervisor tabla promotores". phpmkr_error()."sql :".$sqlp);

			$rows = phpmkr_fetch_array($rss);

			$x_supervisor = $rows["supervisor"];

			phpmkr_free_result($rss);

			if($x_supervisor == 1){

				//seleccionamos todos los promotores que supervisa;

				$sqlp = "SELECT promotor_id FROM promotor WHERE supervisor_id = ".$_SESSION["php_project_esf_status_PromotorID"]."";

			$rss = phpmkr_query($sqlp, $conn) or die ("error al selecciona el campo de supervisor tabla promotores". phpmkr_error()."sql :".$sqlp);

			while($rows = phpmkr_fetch_array($rss)){

			$x_promotores .= $rows["promotor_id"].", ";

			}

				$x_promotores = trim($x_promotores, ", ");

				

				$sSqlWrk = "SELECT promotor_id, nombre_completo FROM promotor Where promotor_id IN ($x_promotores)";

				

				}else{

			$sSqlWrk = "SELECT promotor_id, nombre_completo FROM promotor Where promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"];

				}

		}else if($_SESSION["php_project_esf_status_UserRolID"] == 12 || $_SESSION["php_project_esf_status_UserRolID"] == 17){

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

		?>

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

	  <td>Fondo:</td>

	  <td>&nbsp;</td>

	  <td colspan="3"><span class="phpmaker">

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

		if ($datawrk["fondeo_empresa_id"] == $filter["x_empresa_id"]) {

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

	    </span>

	    <div id="txtlineas" style=" float: left;">

	      

	      </div>	    </td>

	  <td>&nbsp;</td>

	  <td>&nbsp;</td>

	  <td>&nbsp;</td>

	  <td>Vendedor</td>

	  <td valign="middle"><?php

		$x_estado_civil_idList = "<select name=\"x_vendedor_srch\" class=\"texto_normal\">";

		 if($_SESSION["php_project_esf_status_UserRolID"] == 12){

		$sSqlWrk = "SELECT vendedor_id, nombre_completo FROM vendedor Where sucursal_id = ".$_SESSION["php_project_esf_SucursalID"]."";

		$x_estado_civil_idList .= "<option value=\"1000\">TODOS</option>";

		

		}else{

			$sSqlWrk = "SELECT `vendedor_id`, `nombre_completo` FROM `vendedor`";	

			$x_estado_civil_idList .= "<option value=\"1000\">TODOS</option>";	

		}		

		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);

		if ($rswrk) {

			$rowcntwrk = 0;

			while ($datawrk = phpmkr_fetch_array($rswrk)) {

				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";

				if ($datawrk["vendedor_id"] == $filter["x_vendedor_srch"]) {  

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

	  <td>Gestor de credito</td>

	  <td><!--<select name="x_gestor_srch" >

	    <option value="0">Seleccionar</option>

	    <option value="18" <?php if ($filter["x_gestor_srch"]== 18){?> selected="selected"<?php }?>>Fernando Sanchez </option>

	    <option value="1250"  <?php if ($filter["x_gestor_srch"]== 1250){?> selected="selected"<?php }?> >Angelica Tabares </option>

	    <option value="16"  <?php if ($filter["x_gestor_srch"]== 16){?> selected="selected"<?php }?> >Monica Flores </option>

	    <option value="3615" <?php if ($filter["x_gestor_srch"]== 3615){?> selected="selected"<?php }?> >Marcela Lopez </option>

	    <option value="3065" <?php if ($filter["x_gestor_srch"]== 3065){?> selected="selected"<?php }?> >Josefina Ochoa </option>

	    <option value="4561" <?php if ($filter["x_gestor_srch"]== 4561 ){?> selected="selected"<?php }?> >Rodrigo Sanchez </option>

	    <option value="4812" <?php if ($filter["x_gestor_srch"]== 4842 ){?> selected="selected"<?php }?> >Victoria Garcia </option>

	    <option value="4561" <?php if ($filter["x_gestor_srch"]==  4561){?> selected="selected"<?php }?> >Rodrigo Sanchez </option>

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

	  </tr>

	<tr>

	  <td><span class="phpmaker">

	    <input type="submit" name="Submit" value="Buscar &nbsp;(*)" id="Submit" />

	  </span></td>

	  <td>&nbsp;</td>

	  <td><span class="phpmaker"><a href="php_creditolist.php?cmd=reset">Mostrar Todos</a>&nbsp;&nbsp; </span></td>

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

<form action="php_creditolist.php" name="ewpagerform" id="ewpagerform">

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

		<a href="php_creditolist.php?start=<?php echo $PrevStart; ?>&<?php echo $_QS; ?>"><b>Anterior</b></a>

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

		<a href="php_creditolist.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?></b></a>

					<?php }

					$x += $nDisplayRecs;

					$y += 1;

				} elseif (($x >= ($dx1-$nDisplayRecs*$nRecRange)) && ($x <= ($dx2+$nDisplayRecs*$nRecRange))) {

					if ($x+$nRecRange*$nDisplayRecs < $nTotalRecs) { ?>

		<a href="php_creditolist.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?>-<?php echo ($y+$nRecRange-1);?></b></a>

					<?php } else {

						$ny=intval(($nTotalRecs-1)/$nDisplayRecs)+1;

							if ($ny == $y) { ?>

		<a href="php_creditolist.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?></b></a>

							<?php } else { ?>

		<a href="php_creditolist.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?>-<?php echo $ny; ?></b></a>

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

		<a href="php_creditolist.php?start=<?php echo $NextStart; ?>&<?php echo $_QS; ?>"><b>Siguiente</b></a>

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

<td>Estado de Cuenta</td>

<td>&nbsp;</td>

<td>&nbsp;</td>

<td>&nbsp;</td>

<!--<td>&nbsp;</td>-->

<?php } ?>

		<td valign="top"><span>

<?php if ($sExport <> "") { ?>

Crédito No

<?php }else{ ?>

	<a href="php_creditolist.php?order=<?php echo urlencode("credito_id"); ?>">Crédito No<?php if (@$_SESSION["credito_x_credito_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["credito_x_credito_id_Sort"] == "ASC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>

<?php } ?>

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

        <td>Tipo Cliente</td>

        <td>Perfil Transaccional</td>      

		<td valign="top"><span>

<?php if ($sExport <> "") { ?>

Cliente No

<?php }else{ ?>

	<a href="php_creditolist.php?order=<?php echo urlencode("cliente_id"); ?>">Cliente No<?php if (@$_SESSION["credito_x_cliente_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["credito_x_cliente_id_Sort"] == "ASC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>

<?php } ?>

		</span></td>

        

		<td valign="top"><span>

<?php if ($sExport <> "") { ?>

Cliente

<?php }else{ ?>

Cliente

<!---

	<a href="php_creditolist.php?order=<?php //echo urlencode("cliente"); ?>">Cliente<?php //if (@$_SESSION["credito_x_cliente_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php //} elseif (@$_SESSION["credito_x_cliente_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php //} ?></a>

-->    

<?php } ?>

		</span></td>

<td valign="top"><span>

<?php if ($sExport <> "") { ?>

PLD

<?php }else{ ?>

PLD   

<?php } ?>

		</span></td>        

        		

		<td valign="top"><span>        

Tarjeta Num.

		</span></td>		        

		<td valign="top"><span>

<?php if ($sExport <> "") { ?>

Tipo

<?php }else{ ?>

	<a href="php_creditolist.php?order=<?php echo urlencode("credito_tipo_id"); ?>">Tipo<?php if (@$_SESSION["credito_x_credito_tipo_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["credito_x_credito_tipo_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>

<?php } ?>

		</span></td>

		<td valign="top"><span>

<?php if ($sExport <> "") { ?>

Status

<?php }else{ ?>

	<a href="php_creditolist.php?order=<?php echo urlencode("credito_status_id"); ?>">Status<?php if (@$_SESSION["credito_x_credito_status_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["credito_x_credito_status_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>

<?php } ?>

		</span></td>

		<td valign="top"><span>

<?php if ($sExport <> "") { ?>

Fecha de otrogamiento

<?php }else{ ?>

	<a href="php_creditolist.php?order=<?php echo urlencode("fecha_otrogamiento"); ?>">Fecha de otrogamiento<?php if (@$_SESSION["credito_x_fecha_otrogamiento_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["credito_x_fecha_otrogamiento_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>

<?php } ?>

		</span></td>



<td valign="top"><span>

Conciliar	cheque	

		</span></td>

		<td valign="top"><span>

<?php if ($sExport <> "") { ?>

Importe

<?php }else{ ?>

	<a href="php_creditolist.php?order=<?php echo urlencode("importe"); ?>">Importe<?php if (@$_SESSION["credito_x_importe_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["credito_x_importe_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>

<?php } ?>

		</span></td>

		<td valign="top"><span>

Forma de Pago		

		</span></td>				

		<td valign="top"><span>

Numero de Pagos

		</span></td>				

		<td valign="top"><span>

<?php if ($sExport <> "") { ?>

Medio de pago

<?php }else{ ?>

	<a href="php_creditolist.php?order=<?php echo urlencode("medio_pago_id"); ?>">Medio de pago<?php if (@$_SESSION["credito_x_medio_pago_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["credito_x_medio_pago_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>

<?php } ?>

		</span></td>

		<td valign="top"><span>

<?php if ($sExport <> "") { ?>

Referencia de pago

<?php }else{ ?>

	<a href="php_creditolist.php?order=<?php echo urlencode("referencia_pago"); ?>">Referencia de pago&nbsp;(*)<?php if (@$_SESSION["credito_x_referencia_pago_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["credito_x_referencia_pago_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>

<?php } ?>

		</span></td>



		<td valign="top"><span>

Comentarios

		</span></td>
        <td valign="top"><span>

Reporte Interno Preocupante

		</span></td>
        <td valign="top"><span>

Reporte Inusal

		</span></td>


        



<?php if (($_SESSION["php_project_esf_status_UserRolID"] ==3 ) ){

	?>

	<td>Demanda con exhorto</td>

    <td>Demanda sin exhorto</td>

	

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

		

		$x_tipo_cliente = "";

		$sqlTipoCliente = "SELECT tipo_cliente FROM tipo_cliente WHERE credito_id = $x_credito_id ";
		$rsTipociente = phpmkr_query($sqlTipoCliente,$conn) or die("Error tipo cliente".$sqlTipoCliente);
		$rowTipoCliente =  phpmkr_fetch_array($rsTipociente);
		$x_tipo_cliente = $rowTipoCliente["tipo_cliente"];
			
		$sqlTipoCliente = "SELECT cliente_id FROM solicitud_cliente WHERE solicitud_id = $x_solicitud_id ";
		$rsTipociente = phpmkr_query($sqlTipoCliente,$conn) or die("Error tipo cliente".$sqlTipoCliente);
		$rowTipoCliente =  phpmkr_fetch_array($rsTipociente);
		$x_cliente_id = $rowTipoCliente["cliente_id"];	

		

		$x_perfil_trans="";
		$sqlTipoCliente = "SELECT * FROM perfil_transaccional WHERE credito_id = $x_credito_id ";
		$rsTipociente = phpmkr_query($sqlTipoCliente,$conn) or die("Error tipo cliente".$sqlTipoCliente);
		$rowTipoCliente =  phpmkr_fetch_array($rsTipociente);
		$x_importe_promedio = $rowTipoCliente["importe_promedio"];	
		$x_dias_promedio = $rowTipoCliente["dias_promedio"];	
		$x_total_creditos = $rowTipoCliente["numero_creditos"];	

		//echo $x_solicitud_id."solicitud ";

		if($x_total_creditos>0){

		$x_imp_pro = $x_importe_promedio/$x_total_creditos;

		$x_d_prom =  $x_dias_promedio/$x_total_creditos;

		$x_perfil_trans = "Importe Prom ".$x_imp_pro." <br> dias prom ".$x_d_prom;

		}

		

		

		if($x_fecha_otrogamiento > "2013-07-03"){

		

		// buscamos valore en cmite de credito

		$sqlComteC = "SELECT pld FROM comite_credito_solicitud WHERE solicitud_id = $x_solicitud_id ";

		$rsPLD = phpmkr_query($sqlComteC,$conn)or die ("Error al seleccionar pld".phpmkr_error().$sqlComteC);

		$rowPLD = phpmkr_fetch_array($rsPLD);

		

		$x_pld = $rowPLD["pld"];

		if($x_pld== 1){

			$x_pld = "ALTO";

			}else { // else if($x_pld==2){

				$x_pld = "BAJO";

				}//else{

					//$x_pld ="";

					//}

					

		}

					if($x_solicitud_id == 10070){

			//echo $sqlComteC." ..." .$x_pld;

			}

		

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
					
					if($x_solicitud_id > 10453){
						$x_link_print = "modulos/php_solicitudeditIndividualNomina.php";
						
						}

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

<?php if(($_SESSION["php_project_esf_status_UserRolID"] == 1) || ($_SESSION["php_project_esf_status_UserRolID"] == 2) || ($_SESSION["php_project_esf_status_UserRolID"] == 4) ){?>

<td><span><a href="php_imprime_estado_cuenta.php?credito_id=<?php echo $x_credito_id;?>" target="_blank"><center> Imprimir</center> </a></span></td><?php } else{?>

<td>&nbsp;</td><?php } ?>



<?php if($x_credito_tipo_id != 2) {?>

<td><span class="phpmaker"><a href="<?php if ($x_solicitud_id <> "") {echo "$x_link_print?solicitud_id=" . urlencode($x_solicitud_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>" target='_blank'>Solicitud</a></span></td>

<?php }else{ ?>

<td><span class="phpmaker">

<a href="<?php if ($x_solicitud_id <> "") {echo "$x_link_print?solicitud_id=" . urlencode($x_solicitud_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>" target='_blank'>Solicitud</a>

</span></td>

<?php } ?>





<td><span class="phpmaker"><a href="<?php if ($x_credito_id <> "") {echo "$x_link_view_credito?credito_id=" . urlencode($x_credito_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Ver</a>



</span></td>

<?php //echo "tipo de credito". $x_credito_tipo_id."usuario id ".$_SESSION["php_project_esf_status_UserRolID"]."<br>";?>

<!--Solo admin, gestor de credito o contabilidad pueden aplicar pagos-->

<?php if (($_SESSION["php_project_esf_status_UserRolID"] == 1) || ($_SESSION["php_project_esf_status_UserRolID"] == 4) ||  ($_SESSION["php_project_esf_status_UserRolID"] == 2) || (($_SESSION["php_project_esf_status_UserRolID"] == 12) && ($x_credito_tipo_id == 5))){ ?>



<td><span class="phpmaker"><a href="<?php if ($x_credito_id <> "") {echo "$x_link_edit_credito?credito_id=" . urlencode($x_credito_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Editar</a></span></td>

<?php }else{ ?>

<td><span class="phpmaker"></span></td>

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



		<td align="center">

        <?php if(($_SESSION["php_project_esf_status_UserRolID"] == 1)){ ?>

        <span id="capa_fondo_<?php echo $x_credito_id;?>">

<?php 





$sSqlWrk = "select fondeo_empresa.nombre from  fondeo_colocacion join fondeo_credito on fondeo_credito.fondeo_credito_id = fondeo_colocacion.fondeo_credito_id join fondeo_empresa on fondeo_empresa.fondeo_empresa_id = fondeo_credito.fondeo_empresa_id where fondeo_colocacion.credito_id = $x_credito_id ";

$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);

if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {

	$x_fondo = $rowwrk["nombre"];								

}else{

	$x_fondo = "Propio";										

}

@phpmkr_free_result($rswrk);

if ($x_fondo == "FONDOS PROPIOS"){

	$x_fondo = "FP";

	}else{

		$x_fondo = "FIM";

		}

echo $x_fondo."\n"; 

?>

</span><span  style=" color:#FF6633;" class="cambia_fondo"  id="cambia_fondo.<?php echo $x_credito_id;?>" >CAMBIAR</span>



<?php }?>

</td>



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

			$x_user_gest = $rowwrk["usuario_id"];							

		}else{

			$x_gestor = "";										

		}

		@phpmkr_free_result($rswrk);



if($x_user_gest  == 6824 || $x_user_gest == 6823 || $x_user_gest == 6822 || $x_user_gest == 6812 || $x_user_gest == 6837 ){

	$x_gestor = $x_gestor ."(RESP SUC)";

	}

echo $x_gestor; 



?>



</span></td>



		<!-- solicitud_id -->

        <td><?php echo $x_tipo_cliente;?></td>

        <td><?php echo $x_perfil_trans;?></td>

		<td><span>

        <?php echo $x_cliente_num; ?>

</span></td>

		<td align="left"><span>

<?php echo htmlentities($x_cliente); ?>

</span></td>

<td align="left"><span>

<?php echo htmlentities($x_pld); ?>

</span></td>

		<td align="right"><span>

<?php echo htmlentities($x_tdp); ?>

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

<?php echo FormatDateTime($x_fecha_otrogamiento,7)."\n".$x_fecha_visita_supervision; ?>







</span></td>



<!-- conciliar cheques -->

<td align="right">

<?php if(($x_cocilia_cheque_id > 0)){

	#echo $x_cocilia_cheque_id." ".$x_status_conciliado;

	$x_checked = "";

	if ($x_status_conciliado == 1){

	$x_checked = 'checked="checked"';

	#echo 1;

	}	

	$x_disable = "";

	if(($_SESSION["php_project_esf_status_UserRolID"] != 1) && ($_SESSION["php_project_esf_status_UserRolID"] != 4)){

	$x_disable = 'disabled="disabled"';

	}else{

		if (($_SESSION["php_project_esf_status_UserRolID"] == 4) && ($x_status_conciliado == 1)){

		$x_disable = 'disabled="disabled"';

		}

	} ?>

<span>

		<div id="capaChequeConciliado_<?php echo $x_credito_id;?>"><input type="checkbox" name="x_concilia_cheque<?php echo $x_credito_id; ?>" <?php echo $x_disable; ?> <?php echo $x_checked;?> onclick="ConciliaCheque(<?php echo $x_credito_id; ?>);"  /></div><?php } ?></td>

		<!-- importe -->

		<td align="right"><span>

<?php echo (is_numeric($x_importe)) ? FormatNumber($x_importe,2,0,0,1) : $x_importe; ?>

</span></td>

		<!-- tasa -->

		<!-- tasa_moratoria -->

		<!-- plazo -->

		<td><span class="ewTableAltRow">

		  <?php 

		$sSqlWrk = "SELECT descripcion FROM forma_pago where forma_pago_id = $x_forma_pago_id";

		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);

		$datawrk = phpmkr_fetch_array($rswrk);

		echo $datawrk["descripcion"];

		@phpmkr_free_result($rswrk);

		?>

		</span></td>

		<!-- fecha_vencimiento -->



		<td align="right"><span>

		<?php echo $x_num_pagos; ?>

		</span></td>



		<!-- medio_pago_id -->

		<td><span>

<?php

if ((!is_null($x_medio_pago_id)) && ($x_medio_pago_id <> "")) {

	$sSqlWrk = "SELECT `descripcion` FROM `medio_pago`";

	$sTmp = $x_medio_pago_id;

	$sTmp = addslashes($sTmp);

	$sSqlWrk .= " WHERE `medio_pago_id` = " . $sTmp . "";

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

</span></td>

		<!-- referencia_pago -->

		<td align="right"><span>

<?php echo $x_referencia_pago; ?>

</span></td>





		<td align="center"><span>   

    <iframe name="comentarios" src="php_comentarios_visor.php?key=<?php echo $x_credito_id;?>" scrolling="no" style="margin-left:0px; width:100px; height:30px; margin-top:-5px" frameborder="0" allowtransparency="true" id="contenido"></iframe>

</span></td>



<!-- fecha_otrogamiento -->

		<!--<td><span>

<?php //echo FormatDateTime($x_fecha_cobranza_externa,7);

$x_fecha_cobranza_externa = "";

?>

</span></td>



<!-- fecha_otrogamiento -->

		<!--  <td><span>

<?php //echo FormatDateTime($x_fecha_incobrable,7);

$x_fecha_incobrable = "";

?>

</span></td>-->


<td><span class="phpmaker"><a href="<?php if ($x_solicitud_id <> "") {echo "php_reporte_cnbv_add.php?solicitud_id=".urlencode($x_solicitud_id)."&cliente_id=".urlencode($x_cliente_id)."&tipo=3"; } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>" target='_blank'>Generar reporte</a></span></td>
<td><span class="phpmaker"><a href="<?php if ($x_solicitud_id <> "") {echo "php_reporte_cnbv_add.php?solicitud_id=".urlencode($x_solicitud_id)."&cliente_id=".urlencode($x_cliente_id)."&tipo=2"; } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>" target='_blank'>Generar reporte</a></span></td>


<?php if (($_SESSION["php_project_esf_status_UserRolID"] ==3 ) ){

	?>

	<td><span class="phpmaker"><a href="<?php if ($x_solicitud_id <> "") {echo "php_demanda_exhorto_print.php?solicitud_id=" . urlencode($x_solicitud_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>" target='_blank'>Con exhorto</a></span></td>

    <td><span class="phpmaker"><a href="<?php if ($x_solicitud_id <> "") {echo "php_demanda_print.php?solicitud_id=" . urlencode($x_solicitud_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>" target='_blank'>Sin exhorto</a></span></td>

	

	<?php

	}?>





	</tr>

<?php

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

