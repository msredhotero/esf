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
$filter['x_garantia_status_srch'] = '';

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

if((!empty($filter["x_nombre_srch"])) || (!empty($filter["x_apepat_srch"])) || (!empty($filter["x_apemat_srch"])) || (!empty($filter["x_clinum_srch"]))){
		$ssrch = "";
		if(!empty($filter["x_nombre_srch"])){
			$ssrch .= "(convenio_liquidacion.nombre_completo like '%".$filter["x_nombre_srch"]."%') AND ";
		}
		if(!empty($filter["x_apepat_srch"])){
			$ssrch .= "(convenio_liquidacion.nombre_completo like '%".$filter["x_apepat_srch"]."%') AND ";
		}
		if(!empty($filter["x_apemat_srch"])){
			$ssrch .= "(convenio_liquidacion.nombre_completo like '%".$filter["x_apemat_srch"]."%') AND ";
		}
		$ssrch_cli = $ssrch;
	
		$ssrch = substr($ssrch, 0, strlen($ssrch)-5);
		
		
		
	}else{
		$ssrch_cli = "";
	}
	
	// En Credito
	if( (!empty($filter["x_cresta_srch"])) || (!empty($filter["x_credito_tipo_id"])) ){
		$ssrch_cre = "";
		
		//si se selecciono algun tipo de credito
		if(!empty($filter["x_credito_tipo_id"])){
		//si se selecciono pero no es TODOS que tiene un valor de 100
				if(!empty($filter["x_credito_tipo_id"]) && ($filter["x_credito_tipo_id"] != "100")){
				$ssrch_cre .= "(credito.credito_tipo_id = ".$filter["x_credito_tipo_id"].") AND ";
						}	
		}
		
		
		
		if(!empty($filter["x_crenum_srch"])){
			///$ssrch_cre .= "(credito.credito_num+0 = ".$filter["x_crenum_srch"].") AND ";
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
	
		
		if(!empty($filter["x_garantia_status_srch"]) && ($filter["x_garantia_status_srch"] != "100")){
			$ssrch_cre .= "(convenio_liquidacion.status = ".$filter["x_garantia_status_srch"].") AND ";
		}
		


if(!empty($filter["x_sucursal_srch"])){
	echo "enra a filro sucursal";
		if((!empty($filter["x_sucursal_srch"])) && ($filter["x_sucursal_srch"] != "1000")){
			$ssrch_cre .= "(promotor.sucursal_id = ".$filter["x_sucursal_srch"].") AND ";
			//$ssrch_cre = substr($ssrch_cre, 0, strlen($ssrch_cre)-5);		
		}
	}
	
if(!empty($filter["x_crenum_srch"])){
			$ssrch_cre .= "(convenio_liquidacion.credito_num+0 = ".$filter["x_crenum_srch"].") AND ";
		}	



$sWhere = $ssrch_SUC.$ssrch_cli.$ssrch_cre.$sNPWhere;


// Build WHERE condition
$sDbWhere = "";
if ($sDbWhereMaster != "") {
	$sDbWhere .= "(" . $sDbWhereMaster . ") AND ";
}
if ($sSrchWhere != "") {
	$sDbWhere .= "(" . $sSrchWhere . ") AND ";
}
if (strlen($sDbWhere) > 5) {
	$sDbWhere = substr($sDbWhere, 0, strlen($sDbWhere)-5); // Trim rightmost AND
}

// Build SQL
 if ($_SESSION["php_project_esf_status_UserRolID"] == 12 || $_SESSION["php_project_esf_status_UserRolID"] == 17 ){
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
						#echo "entro en gerente<br>";					
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
	 
	 // seleccionamos todos los promotores que correspondan a esa sucursal
	 $sqlListaPromotores = "SELECT * FROM promotor  WHERE  sucursal_id = ". $_SESSION["php_project_esf_SucursalID"]."";
	# echo   $sqlListaPromotores."<br>";
	 $rsPromotores = phpmkr_query($sqlListaPromotores, $conn) or die ("Error al selccioar los promotores de la sucursal". phpmkr_error()."sql:".$sqlListaPromotores);
	 while($rowPromotores = phpmkr_fetch_array($rsPromotores)){
		 $x_promotores .= $rowPromotores["promotor_id"].", ";	 
		 }
		 $x_promotores = trim($x_promotores, ", ");
		# echo $filter["x_promo_srch"] ."<br>";
		 if((empty($filter["x_promo_srch"])) || ($filter["x_promo_srch"] == 1000)){
		 $sDbWhereEncargado = " (solicitud.promotor_id IN ($x_promotores)  ) AND ";
		# echo "ntra a la condicion....<br>";
		
		if(!empty($filter["x_cresta_srch"]) && $filter["x_cresta_srch"] != 100){
			 $sDbWhereEncargado = " and (solicitud.promotor_id IN ($x_promotores)  ) AND ";
			}
		if((!empty($filter["x_nombre_srch"])) || (!empty($filter["x_apepat_srch"])) || (!empty($filter["x_apemat_srch"])) || (!empty($filter["x_clinum_srch"]))){
			 $sDbWhereEncargado = " and (solicitud.promotor_id IN ($x_promotores)  ) AND ";
			}	
			
		 }
		 
		 if(!empty($filter["x_vendedor_srch"])){
		if((!empty($filter["x_vendedor_srch"])) && ($filter["x_vendedor_srch"] != "1000")){
			$ssrch_vend .= " and (solicitud.vendedor_id = ".$filter["x_vendedor_srch"].")  ";
			//$ssrch_cre .= "  (solicitud.vendedor_id = ".$filter["x_vendedor_srch"].")  AND  ";
			//$ssrch_cre = substr($ssrch_cre, 0, strlen($ssrch_cre)-5);		
		}
 }
 
 $sSql = "SELECT  convenio_liquidacion.*, promotor.nombre_completo as promotor,sucursal.nombre as suc from  convenio_liquidacion join promotor on promotor.promotor_id = convenio_liquidacion.usuario_id  join sucursal on promotor.sucursal_id = sucursal.sucursal_id  where ";
$sSql .= " (convenio_liquidacion.usuario_id IN ($x_promotores) ) order by promotor.sucursal_id,convenio_liquidacion.fecha_registro  asc";
		 
	 }else{


$sSql = "SELECT  pld_operacion_relevant.*, promotor.nombre_completo as promotor from  pld_operacion_inusual   ";

	 }

// Load Default Filter
$sDefaultFilter = "";
$sGroupBy = "";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = "";
$sWhere = "";
$sWhere = $ssrch_SUC.$ssrch_cli.$ssrch_cre.$sNPWhere;
if ($sDefaultFilter != "") {
	$sWhere .= "(" . $sDefaultFilter . ") AND ";
}
if ($sDbWhere != "") {
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
$sOrderBy = "";
SetUpSortOrder();
if ($sOrderBy != "") {
	$sSql .= " ORDER BY " . $sOrderBy;
}

//$sSql .= "  order by promotor.sucursal_id,convenio_liquidacion.fecha_registro  asc";
?>

<?php include ("header.php") ?>
<?php if ($sExport == "") { ?>
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
<p><span class="phpmaker">Operaciones Inusuales<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="php_operaciones_relevanteslist.php?export=excel">Export to Excel</a>
&nbsp;&nbsp;<a href="php_operaciones_relevanteslist.php?export=word">Export to Word</a>
<?php } ?>
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





<form action="php_operaciones_relevanteslist.php" name="ewpagerform" id="ewpagerform">
<table width="785" border="0" cellpadding="0" cellspacing="0">
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
	  <td>N&uacute;mero de Cr&eacute;dito</td>
	  <td>&nbsp;</td>
	  <td><span class="phpmaker">
	    <input name="x_crenum_srch" type="text" id="x_crenum_srch" value="<?php echo $filter["x_crenum_srch"]; ?>" size="20" />
	  </span></td>
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
	  <td><span class="phpmaker">
	    <input type="submit" name="Submit" value="Buscar &nbsp;(*)" />
	  </span></td>
	  <td>&nbsp;</td>
	  <td><span class="phpmaker"><a href="php_operaciones_inusuales.php?cmd=reset">Mostrar Todos</a>&nbsp;&nbsp; </span></td>
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
		<a href="php_operaciones_relevanteslist.php?start=<?php echo $PrevStart; ?>&<?php echo $_QS; ?>"><b>Anterior</b></a>
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
		<a href="php_operaciones_relevanteslist.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?></b></a>
					<?php }
					$x += $nDisplayRecs;
					$y += 1;
				} elseif (($x >= ($dx1-$nDisplayRecs*$nRecRange)) && ($x <= ($dx2+$nDisplayRecs*$nRecRange))) {
					if ($x+$nRecRange*$nDisplayRecs < $nTotalRecs) { ?>
		<a href="php_operaciones_relevanteslist.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?>-<?php echo ($y+$nRecRange-1);?></b></a>
					<?php } else {
						$ny=intval(($nTotalRecs-1)/$nDisplayRecs)+1;
							if ($ny == $y) { ?>
		<a href="php_operaciones_relevanteslist.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?></b></a>
							<?php } else { ?>
		<a href="php_operaciones_relevanteslist.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?>-<?php echo $ny; ?></b></a>
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
			  <a href="php_operaciones_relevanteslist.php?start=<?php echo $NextStart; ?>&<?php echo $_QS; ?>"><b>Siguiente</b></a>
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
		  <a href="php_operaciones_relevanteslist.php?order=<?php echo urlencode("convenio_liquidacion_id"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">ID<?php if (@$_SESSION["convenio_liquidacion_x_convenio_liquidacion_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["convenio_liquidacion_x_convenio_liquidacion_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
  <?php } ?>
		  </span></td>
        	<td valign="top"><span>
  <?php if ($sExport <> "") { ?>
        	  Promotor
  <?php }else{ ?>
        	  Promotor
  <?php } ?>
      	  </span></td>
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
   credito_num
  <?php } ?>
 </span></td>

 <td valign="top"><span>
<?php if ($sExport <> "") { ?>
Nobre completo
<?php }else{ ?>
	Nombre completo
<?php } ?>
		</span></td>
        <td valign="top"><span>
<?php if ($sExport <> "") { ?>
Monto Pago
<?php }else{ ?>
	Monto Pago<?php } ?>
		</span></td>
        <td valign="top"><span>
  <?php if ($sExport <> "") { ?>
          Monto Cr&eacute;dito
  <?php }else{ ?>
          Monto Cr&eacute;dito
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
		$sqlCredito = "SELECT  credito_id, credito_num, credito.importe as importe_credito, credito.solicitud_id, cliente.nombre_completo, cliente.apellido_paterno, cliente.apellido_materno FROM credito, solicitud_cliente, cliente WHERE credito_id = $credito_id and solicitud_cliente.solicitud_id = credito.solicitud_id  and cliente.cliente_id = solicitud_cliente.cliente_id order by solicitud_cliente.solicitud_cliente DESC";
		$rsCredito = phpmkr_query($sqlCredito,$conn)or die ("Error al seleccionar credito".phpmkr_error());
		$rowCredito = phpmkr_fetch_array($rsCredito);
		foreach($rowCredito as $nombre => $valor){
			$$nombre = $valor;
			//echo $nombre;
			}
			$x_cliente = $nombre_completo." ".$apellido_paterno." ".$apellido_materno;
		
		
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
    <?php if ($sExport == "") { ?>

<?php } ?>
		<!-- convenio_liquidacion_id -->
		<td><span>
  <?php echo $convenio_liquidacion_id; ?>
</span></td>
<td><span>
  <?php echo $promotor; ?>
</span></td>
<td><span>
  <?php echo $fecha_registro; ?>
</span></td>
<td><span>
  <?php echo $credito_num; ?>
</span></td>
<td><span>
<?php echo $cliente; ?>
</span></td>
<td><span>
<?php echo $importe; ?>
</span></td>
<td><span>
  <?php echo $importe_credito; ?>
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
