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
$nDisplayRecs = 1000;
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
$filter['x_credito_tipo_id'] = 100;
$filter['x_periodo_srch'] = '';

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
	if((!empty($filter["x_crenum_srch"])) || (!empty($filter["x_cresta_srch"])) || (!empty($filter["x_credito_tipo_id"])) || (!empty($filter["x_crenum_srch"])) ){
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
		
		 if ((!empty($filter["x_periodo_srch"]))){
		  // se esta buscando algun periodo en especifico
		  $sqlCVP = "SELECT credito_id, fecha_pago, fecha  FROM  credito_vencido_periodo";
		  $rsCVP = phpmkr_query($sqlCVP, $conn) or die("Error cvp list". phpmkr_error()."sql:".$sqlCVP);
		   $x_c_list = "";
		 while( $rowCVP = phpmkr_fetch_array($rsCVP)){
			 $x_fecha = $rowCVP["fecha"];
			 $x_fecha_pago = $rowCVP["fecha_pago"];		
			 $x_c_id = $rowCVP["credito_id"]; 
			
			$sql_fecha = "SELECT DATEDIFF('$x_fecha_pago','$x_fecha') AS dias_vencido;";
			$rs_fecha = phpmkr_query($sql_fecha, $conn) or die ("Error al seleccionar  la fecha". phpmkr_error(). "sql:". $sql_fecha);
			$row_fecha = phpmkr_fetch_array($rs_fecha);
			$x_dias_vencido = $row_fecha["dias_vencido"];
			 
			 if(($filter["x_periodo_srch"] == 20) and ($x_dias_vencido >0 && $x_dias_vencido <=20) ){
				 echo $x_c_id."-";
				 $x_c_list .= $x_c_id.", ";
				 }else if(($filter["x_periodo_srch"] == 30) and ($x_dias_vencido >20 && $x_dias_vencido <=30)){
					 $x_c_list .= $x_c_id.", ";					 
					 }else if(($filter["x_periodo_srch"] == 60) and ($x_dias_vencido >30 && $x_dias_vencido <=60)){
						  $x_c_list .= $x_c_id.", ";
						 }else if(($filter["x_periodo_srch"] == 90) and ($x_dias_vencido >60 && $x_dias_vencido <=90)){
							  $x_c_list .= $x_c_id.", ";
							 }else if(($filter["x_periodo_srch"] == 120) and ($x_dias_vencido >90 && $x_dias_vencido <=120)){
								  $x_c_list .= $x_c_id.", ";
								 }else if(($filter["x_periodo_srch"] == 180) and ($x_dias_vencido >120 && $x_dias_vencido <=180)){
									  $x_c_list .= $x_c_id.", ";
									 }else if(($filter["x_periodo_srch"] == 200) and ($x_dias_vencido >180)){
										  $x_c_list .= $x_c_id.", ";
										 }
			 
			 } 
			 $x_c_list = substr($x_c_list, 0, strlen($x_c_list)-2);
			 if(!empty($x_c_list)){
			  $ssrch_cre .= " (credito.credito_id in ($x_c_list) ) AND ";	
			 }else{
				 // si la lista esta vacia entonces no hay datos que mostrar
				 $ssrch_cre .= " (credito.credito_id in (0) ) AND ";
				 }
			 
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
	// los periodos
	  if ((!empty($filter["x_periodo_srch"]))){
		  // se esta buscando algun periodo en especifico
		  $sqlCVP = "SELECT credito_id, fecha_pago, fecha  FROM  credito_vencido_periodo";
		  $rsCVP = phpmkr_query($sqlCVP, $conn) or die("Error cvp list". phpmkr_error()."sql:".$sqlCVP);
		   $x_c_list = "";
		 while( $rowCVP = phpmkr_fetch_array($rsCVP)){
			 $x_fecha = $rowCVP["fecha"];
			 $x_fecha_pago = $rowCVP["fecha_pago"];		
			 $x_c_id = $rowCVP["credito_id"]; 
			
			$sql_fecha = "SELECT DATEDIFF('$x_fecha_pago','$x_fecha') AS dias_vencido;";
			$rs_fecha = phpmkr_query($sql_fecha, $conn) or die ("Error al seleccionar  la fecha". phpmkr_error(). "sql:". $sql_fecha);
			$row_fecha = phpmkr_fetch_array($rs_fecha);
			$x_dias_vencido = $row_fecha["dias_vencido"];
			 
			 if(($filter["x_periodo_srch"] == 20) and ($x_dias_vencido >0 && $x_dias_vencido <=20) ){
				 echo $x_c_id."-";
				 $x_c_list .= $x_c_id.", ";
				 }else if(($filter["x_periodo_srch"] == 30) and ($x_dias_vencido >20 && $x_dias_vencido <=30)){
					 $x_c_list .= $x_c_id.", ";					 
					 }else if(($filter["x_periodo_srch"] == 60) and ($x_dias_vencido >30 && $x_dias_vencido <=60)){
						  $x_c_list .= $x_c_id.", ";
						 }else if(($filter["x_periodo_srch"] == 90) and ($x_dias_vencido >60 && $x_dias_vencido <=90)){
							  $x_c_list .= $x_c_id.", ";
							 }else if(($filter["x_periodo_srch"] == 120) and ($x_dias_vencido >90 && $x_dias_vencido <=120)){
								  $x_c_list .= $x_c_id.", ";
								 }else if(($filter["x_periodo_srch"] == 180) and ($x_dias_vencido >120 && $x_dias_vencido <=180)){
									  $x_c_list .= $x_c_id.", ";
									 }else if(($filter["x_periodo_srch"] == 200) and ($x_dias_vencido >180)){
										  $x_c_list .= $x_c_id.", ";
										 }
			 
			 } 
			 
			 $x_c_list = substr($x_c_list, 0, strlen($x_c_list)-2);
			 //$ssrch_cre .= "(credito.credito_id in ($x_c_list) ) AND ";	
			 $ssrch_cre = substr( $ssrch_cre, 0, strlen($ssrch_cre)-5);
		  echo "<br>lista de creditos". $x_c_list ;
		  $sqlV = "SELECT *
FROM `vencimiento`
WHERE `credito_id` =6204
AND `vencimiento_status_id` =3
ORDER BY `vencimiento`.`vencimiento_num` ASC
LIMIT 0 , 1";
		  
		  
		  }
	

	$x_join = "";
	if($_SESSION["php_project_esf_status_UserRolID"] == 11){
		// solo se toma en cuenta los credito de finafim
		$x_join = " JOIN fondeo_colocacion ON fondeo_colocacion.credito_id = credito.credito_id";		
		}
	if($_SESSION["php_project_esf_status_UserRolID"] == 7) {
		$sSql = "SELECT credito.*, credito_vencido_periodo.fecha_pago AS fpv, credito_vencido_periodo.fecha as fev  FROM  credito_vencido_periodo JOIN  credito ON credito.credito_id =  credito_vencido_periodo.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id $x_join";
	}else{
		$sSql = "SELECT credito.*, credito_vencido_periodo.fecha_pago AS fpv, credito_vencido_periodo.fecha as fev FROM credito_vencido_periodo JOIN  credito ON credito.credito_id =  credito_vencido_periodo.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id $x_join";
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
	
	
	
	
	
	$x_join = "";
	if($_SESSION["php_project_esf_status_UserRolID"] == 11){
		// solo se toma en cuenta los credito de finafim
		$x_join = " JOIN fondeo_colocacion ON fondeo_colocacion.credito_id = credito.credito_id";
		
		}
	
	if($_SESSION["php_project_esf_status_UserRolID"] == 7) {
		$sSql = "SELECT credito.*,credito_vencido_periodo.fecha_pago AS fpv, credito_vencido_periodo.fecha as fev  FROM  credito_vencido_periodo JOINcredito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id  $x_join ";
	}else{
		$sSql = "SELECT credito.*,credito_vencido_periodo.fecha_pago AS fpv, credito_vencido_periodo.fecha as fev  FROM  credito_vencido_periodo JOIN credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id  $x_join ";
	
//	$sSql = "SELECT credito.* FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id ";
	}

	
	
	
	
}




// Load Default Filter
$sDefaultFilter = "";
$sGroupBy = "";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = " credito.credito_num+0 desc ";


// filtor para el tipo de rol de externos


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

// Build WHERE condition
if($filter["php_project_esf_status_UserRolID"] == 7) {
	$sDbWhere = "(solicitud.promotor_id = ".$filter["php_project_esf_status_PromotorID"]. ") AND ";
}else{
	if(!empty($filter["x_promo_srch"]) && $filter["x_promo_srch"] != 1000){
		$filter["x_promo_srch"] = $filter["x_promo_srch"];
		$sDbWhere = "(solicitud.promotor_id = ".$filter["x_promo_srch"]. ") AND ";		
	}else{
		$filter["x_promo_srch"] = "";		
		$sDbWhere = "";		
	}
}



$sWhere = $ssrch_cli.$ssrch_cre.$sNPWhere;

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
	$sSql .= " WHERE credito.credito_status_id not in (2) AND " . $sWhere;
}else{
	$sSql .= " WHERE  credito.credito_status_id not in (2)";
	}

	$sSql .= " GROUP BY  credito.credito_id ";

if ($sHaving != "") {
	$sSql .= " HAVING " . $sHaving;
}

// Set Up Sorting Order
$sOrderBy = "";
SetUpSortOrder();
if ($sOrderBy != "") {
	$sSql .= " ORDER BY " . $sOrderBy;
}

echo $sSql; // Uncomment to show SQL for debugging
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

// Set up Record Set
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$nTotalRecs = phpmkr_num_rows($rs);
if ($nDisplayRecs <= 0) { // Display All Records
	$nDisplayRecs = $nTotalRecs;
}
$nStartRec = 1;
SetUpStartRec(); // Set Up Start Record Position
?>
<p><span class="phpmaker">CREDITOS VENCIDOS POR PERIODO
<?php if ($sExport == "") { ?>
&nbsp;
<?php } ?>
</span></p>
<?php if ($sExport == "") { ?>
<form action="" name="filtros" method="post">
<table width="785" border="0" cellpadding="0" cellspacing="0">
	<tr>
	  <td>Tipo de Credito</td>
	  <td>&nbsp;</td>
	  <td> <?php
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
	  <td>
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
		$sSqlWrk = "SELECT `credito_status_id`, `descripcion` FROM `credito_status` WHERE credito_status_id not in (2)";
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
	  <td><span class="phpmaker">
	    <?php
$x_medio_pago_idList = "<select  name=\"x_empresa_id\" onchange=\"cargalineas(this,'txtlineas',0)\">";
$x_medio_pago_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT fondeo_empresa.fondeo_empresa_id, fondeo_empresa.nombre FROM fondeo_empresa order by fondeo_empresa.fondeo_empresa_id ";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		
	
		
		$x_medio_pago_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["fondeo_empresa_id"] == $filter["x_empresa_id"]) {
			$x_medio_pago_idList .= "' selected";
		}


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
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>Periodo </td>
	  <td valign="middle"><select name="x_periodo_srch">
       <option value="">Seleccione</option>
      <option value="20">de 0 a 20 dias</option>
      <option value="30">de 21 a 30 dias</option>
      <option value="60">de 31 a 60 dias</option>
      <option value="90">de 61 a 90 dias</option>
      <option value="120">de 91 a 120 dias</option>
      <option value="180">de 121 a 180 dias</option>
      <option value="200">de  mas de 180 dias.</option> 
      
      
      </select></td>
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
	  <td><span class="phpmaker">
	    <input type="submit" name="Submit" value="Buscar &nbsp;(*)" />
	  </span></td>
	  <td>&nbsp;</td>
	  <td><span class="phpmaker"><a href="php_credito_vencido_list_new_periodo.php?cmd=reset">Mostrar Todos</a>&nbsp;&nbsp; </span></td>
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
<form action="php_credito_vencido_list_new_periodo.php" name="ewpagerform" id="ewpagerform">
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
		<a href="php_credito_vencido_list_new_periodo.php?start=<?php echo $PrevStart; ?>&<?php echo $_QS; ?>"><b>Anterior</b></a>
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
		<a href="php_credito_vencido_list_new_periodo.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?></b></a>
					<?php }
					$x += $nDisplayRecs;
					$y += 1;
				} elseif (($x >= ($dx1-$nDisplayRecs*$nRecRange)) && ($x <= ($dx2+$nDisplayRecs*$nRecRange))) {
					if ($x+$nRecRange*$nDisplayRecs < $nTotalRecs) { ?>
		<a href="php_credito_vencido_list_new_periodo.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?>-<?php echo ($y+$nRecRange-1);?></b></a>
					<?php } else {
						$ny=intval(($nTotalRecs-1)/$nDisplayRecs)+1;
							if ($ny == $y) { ?>
		<a href="php_credito_vencido_list_new_periodo.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?></b></a>
							<?php } else { ?>
		<a href="php_credito_vencido_list_new_periodo.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?>-<?php echo $ny; ?></b></a>
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
		<a href="php_credito_vencido_list_new_periodo.php?start=<?php echo $NextStart; ?>&<?php echo $_QS; ?>"><b>Siguiente</b></a>
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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<!--<td>&nbsp;</td>-->
<?php } ?>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Cr&eacute;dito No
<?php }else{ ?>
	<a href="php_credito_vencido_list_new_periodo.php?order=<?php echo urlencode("credito_id"); ?>">Cr&eacute;dito No<?php if (@$_SESSION["credito_x_credito_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["credito_x_credito_id_Sort"] == "ASC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
Fondo
		</span></td>        
		<td valign="top"><span>
Promotor
		</span></td>        
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Cliente No
<?php }else{ ?>
	<a href="php_credito_vencido_list_new_periodo.php?order=<?php echo urlencode("cliente_id"); ?>">Cliente No<?php if (@$_SESSION["credito_x_cliente_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["credito_x_cliente_id_Sort"] == "ASC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
        
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Cliente
<?php }else{ ?>
Cliente
<!---
	<a href="php_credito_vencido_list_new_periodo.php?order=<?php //echo urlencode("cliente"); ?>">Cliente<?php //if (@$_SESSION["credito_x_cliente_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php //} elseif (@$_SESSION["credito_x_cliente_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php //} ?></a>
-->    
<?php } ?>
		</span></td>		
		<td valign="top"><span> fecha vencido      
</span></td>
<td valign="top"><span> fecha pagado      
</span></td>
<td valign="top"><span> dias vencido     
</span></td>		        
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Tipo
<?php }else{ ?>
	<a href="php_credito_vencido_list_new_periodo.php?order=<?php echo urlencode("credito_tipo_id"); ?>">Tipo<?php if (@$_SESSION["credito_x_credito_tipo_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["credito_x_credito_tipo_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Status
<?php }else{ ?>
	<a href="php_credito_vencido_list_new_periodo.php?order=<?php echo urlencode("credito_status_id"); ?>">Status<?php if (@$_SESSION["credito_x_credito_status_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["credito_x_credito_status_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Fecha de otrogamiento
<?php }else{ ?>
	<a href="php_credito_vencido_list_new_periodo.php?order=<?php echo urlencode("fecha_otrogamiento"); ?>">Fecha de otrogamiento<?php if (@$_SESSION["credito_x_fecha_otrogamiento_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["credito_x_fecha_otrogamiento_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
<td valign="top">&nbsp;</td>

		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Importe
<?php }else{ ?>
	<a href="php_credito_vencido_list_new_periodo.php?order=<?php echo urlencode("importe"); ?>">Importe<?php if (@$_SESSION["credito_x_importe_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["credito_x_importe_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
Forma de Pago		
		</span></td>				
		<td valign="top"><span>
Numero de Pagos
		</span></td>				
		<td valign="top">&nbsp;</td>
		<td valign="top">&nbsp;</td>

		<td valign="top"><span>
Comentarios
		</span></td>
        <td valign="top"><span>
        <?php if ($sExport <> "") { ?>
Fecha credito a cobranza externa
<?php }else{ ?>
Fecha credito a cobranza externa
<?php } ?>
		</span></td>
        <td valign="top"><span>
 <?php if ($sExport <> "") { ?>
Fecha credito a incobrable
<?php }else{ ?>
Fecha credito a incobrable
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
		$x_fecha_entra_vencido = $row["fev"];
		$x_fecha_paga_vencido = $row["fpv"];
		$today = date("Y-m-d");
		$x_dias_vencido = "";
		
		if(empty($x_fecha_paga_vencido) || is_null($x_fecha_paga_vencido)){
			$sql_fecha = "SELECT DATEDIFF('$today','$x_fecha_entra_vencido') AS dias_vencido;";
			$rs_fecha = phpmkr_query($sql_fecha, $conn) or die ("Error al seleccionar  la fecha". phpmkr_error(). "sql:". $sql_fecha);
			$row_fecha = phpmkr_fetch_array($rs_fecha);
			$x_dias_vencido = $row_fecha["dias_vencido"];
			#echo "dias vencido uando es empty".$sql_fecha. $x_dias_vencido."<br>";
		}else{
			$sql_fecha = "SELECT DATEDIFF('$x_fecha_paga_vencido','$x_fecha_entra_vencido') AS dias_vencido;";
			$rs_fecha = phpmkr_query($sql_fecha, $conn) or die ("Error al seleccionar  la fecha". phpmkr_error(). "sql:". $sql_fecha);
			$row_fecha = phpmkr_fetch_array($rs_fecha);
			$x_dias_vencido = $row_fecha["dias_vencido"];
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
<td><span class="phpmaker"><a href="<?php if ($x_solicitud_id <> "") {echo "$x_link_print?solicitud_id=" . urlencode($x_solicitud_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>" target='_blank'>Solicitud</a></span></td>
<?php }else{ ?>
<td><span class="phpmaker">
<a href="<?php if ($x_solicitud_id <> "") {echo "$x_link_print?solicitud_id=" . urlencode($x_solicitud_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>" target='_blank'>Solicitud</a>
</span></td>
<?php } ?>


<td><span class="phpmaker"><a href="<?php if ($x_credito_id <> "") {echo "php_creditoview.php?credito_id=" . urlencode($x_credito_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Ver</a>

</span></td>
<!--Solo admin, gestor de credito o contabilidad pueden aplicar pagos-->
<?php if (($_SESSION["php_project_esf_status_UserRolID"] == 1) || ($_SESSION["php_project_esf_status_UserRolID"] == 4) ||   ($_SESSION["php_project_esf_status_UserRolID"] == 2)) { ?>
<td><span class="phpmaker"><a href="<?php if ($x_credito_id <> "") {echo "php_creditoedit.php?credito_id=" . urlencode($x_credito_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>"></a></span></td>
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
if ($x_fondo == "FONDOS PROPIOS"){
	$x_fondo = "FP";
	}else{
		$x_fondo = "FIM";
		}
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

		<!-- solicitud_id -->
		<td><span>
        <?php echo $x_cliente_num; ?>
</span></td>
		<td align="left"><span>
<?php echo htmlentities($x_cliente); ?>
</span></td>
<td><span><?php echo FormatDateTime($x_fecha_entra_vencido,7);?></span></td>
<td><span><?php echo FormatDateTime($x_fecha_paga_vencido,7);?></span></td>
<td><span><?php echo $x_dias_vencido;?></span></td>
		
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
<td><span>
<?php echo FormatDateTime($x_fecha_alta,7); ?>
<?php $x_fecha_alta= "" ;?>
</span></td>
<!-- conciliar cheques -->

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


		<td align="center">&nbsp;</td>

<!-- fecha_otrogamiento -->
		<td><span>
<?php echo FormatDateTime($x_fecha_cobranza_externa,7);
$x_fecha_cobranza_externa = "";
?>
</span></td>

<!-- fecha_otrogamiento -->
		<td><span>
<?php echo FormatDateTime($x_fecha_incobrable,7);
$x_fecha_incobrable = "";
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
