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
$x_garantia_liquida_id = Null;
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
	header('Content-Disposition: attachment; filename=garantia_liquida.xls');
}
if ($sExport == "word") {
	header('Content-Type: application/vnd.ms-word');
	header('Content-Disposition: attachment; filename=garantia_liquida.doc');
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
$nDisplayRecs = 100;
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

if($_SESSION["php_project_esf_status_UserRolID"] == 12) {
		#sleccionamos los promotores de la sucursal
		$sqlSuc = "SELECT sucursal_id FROM responsable_sucursal	WHERE usuario_id =" .$_SESSION["php_project_esf_status_UserID"] ."";
		$rsScu = phpmkr_query($sqlSuc,$conn) or die ("Error al seleccionar la suc".phpmkr_error()."sql.".$sqlSuc);
		$rowSuc = phpmkr_fetch_array($rsScu);
		$x_sucursal_id =  $rowSuc["sucursal_id"];
		
		#echo "sucursal _id".$x_sucursal_id."<br>";
		$sqlPromotores = "SELECT solicitud.solicitud_id, credito.credito_id FROM solicitud JOIN credito ON credito.solicitud_id = solicitud.solicitud_id  join promotor ON promotor.promotor_id = solicitud.promotor_id WHERE promotor.sucursal_id = $x_sucursal_id";
		
		//echo "sql ".$sqlPromotores."<br>";
		$rsPromotor = phpmkr_query($sqlPromotores,$conn) or die("Error al seleccionar los promotores".phpmkr_error()."Sql:". $sqlPromotores);
		while($rowPromotor = phpmkr_fetch_array($rsPromotor)){
			$x_listado_promotores = $x_listado_promotores. $rowPromotor["credito_id"].", ";
			$x_credito_id = $rowPromotor["credito_id"];
			
			}
			$x_listado_promotores = trim($x_listado_promotores,", ");
		#	echo "listdo de credito".$x_listado_promotores ."<br>";
			if(strlen($x_listado_promotores)<4){
				$x_listado_promotores = 0;
				}
		
		#seleccionamos losrecibos que sean de los prmotores
		$sqlRecibos = "SELECT garantia_liquida_id FROM garantia_liquida WHERE credito_id in ($x_listado_promotores)";
		//echo "sql gar".$sqlRecibos."<br>";
		$rsRecibos = phpmkr_query($sqlRecibos,$conn) or die ("Error al selecciona los recibos de la sucursal". phpmkr_error()."sql:".$sqlRecibos);
		while($rowRecibos = phpmkr_fetch_array($rsRecibos)){
			$x_lista_recibos = $x_lista_recibos. $rowRecibos["garantia_liquida_id"].", ";
			
			}
			$x_lista_recibos = trim($x_lista_recibos,", ");
			
		//	echo "<br><br>lista de garantias".$x_lista_recibos."<br>";
		
		$ssrch_SUC = "   garantia_liquida.garantia_liquida_id  in ($x_lista_recibos) AND ";
		#echo "sql lista".$ssrch ."<br>";
	}

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
	
		
		if(!empty($filter["x_garantia_status_srch"]) && ($filter["x_garantia_status_srch"] != "100")){
			$ssrch_cre .= "(garantia_liquida.status = ".$filter["x_garantia_status_srch"].") AND ";
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
$sSql = "SELECT garantia_liquida.*, garantia_liquida_status.descripcion, credito.credito_num, cliente.nombre_completo, cliente.apellido_paterno, cliente.apellido_materno FROM garantia_liquida JOIN garantia_liquida_status ON garantia_liquida_status.garantia_liquida_status_id = garantia_liquida.status JOIN credito ON  credito.credito_id = garantia_liquida.credito_id JOIN solicitud ON solicitud.solicitud_id = credito.solicitud_id JOIN solicitud_cliente ON solicitud_cliente.solicitud_id = solicitud.solicitud_id JOIN  cliente ON cliente.cliente_id = solicitud_cliente.cliente_id";

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
?>

<?php include ("header.php") ?>
<?php if ($sExport == "") { ?>
<script type="text/javascript" src="ew.js"></script>
<script language="javascript" src="concilia_garantia.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<?php } ?>
<?php
#echo $sSql;
// Set up Record Set
$rs = phpmkr_query($sSql,$conn);
$nTotalRecs = phpmkr_num_rows($rs);
if ($nDisplayRecs <= 0) { // Display All Records
	$nDisplayRecs = $nTotalRecs;
}
$nStartRec = 1;
SetUpStartRec(); // Set Up Start Record Position
?>
<p><span class="phpmaker">Garant&iacute;a L&iacute;quida
  <?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="php_garantia_liquidalist.php?export=excel">Export to Excel</a>
&nbsp;&nbsp;<a href="php_garantia_liquidalist.php?export=word">Export to Word</a>
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





<form action="php_garantia_liquidalist.php" name="ewpagerform" id="ewpagerform">
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
	  <td>Status garantia</td>
	  <td>&nbsp;</td>
	  <td><?php
		$x_estado_civil_idList = "<select name=\"x_garantia_status_srch\" class=\"texto_normal\">";
		if ($filter["x_garantia_status_srch"] == 0){
			$x_estado_civil_idList .= "<option value='100' selected>TODAS</option>";
		}else{
			$x_estado_civil_idList .= "<option value='100' >TODAS</option>";		
		}
		$sSqlWrk = "SELECT `garantia_liquida_status_id`, `descripcion` FROM `garantia_liquida_status`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["garantia_liquida_status_id"] == $filter["x_garantia_status_srch"]) {
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
	  <td><span class="phpmaker">
	    <input type="submit" name="Submit" value="Buscar &nbsp;(*)" />
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
		<a href="php_garantia_liquidalist.php?start=<?php echo $PrevStart; ?>&<?php echo $_QS; ?>"><b>Anterior</b></a>
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
		<a href="php_garantia_liquidalist.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?></b></a>
					<?php }
					$x += $nDisplayRecs;
					$y += 1;
				} elseif (($x >= ($dx1-$nDisplayRecs*$nRecRange)) && ($x <= ($dx2+$nDisplayRecs*$nRecRange))) {
					if ($x+$nRecRange*$nDisplayRecs < $nTotalRecs) { ?>
		<a href="php_garantia_liquidalist.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?>-<?php echo ($y+$nRecRange-1);?></b></a>
					<?php } else {
						$ny=intval(($nTotalRecs-1)/$nDisplayRecs)+1;
							if ($ny == $y) { ?>
		<a href="php_garantia_liquidalist.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?></b></a>
							<?php } else { ?>
		<a href="php_garantia_liquidalist.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?>-<?php echo $ny; ?></b></a>
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
			  <a href="php_garantia_liquidalist.php?start=<?php echo $NextStart; ?>&<?php echo $_QS; ?>"><b>Siguiente</b></a>
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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<?php } ?>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
ID
<?php }else{ ?>
	<a href="php_garantia_liquidalist.php?order=<?php echo urlencode("garantia_liquida_id"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">ID<?php if (@$_SESSION["garantia_liquida_x_garantia_liquida_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["garantia_liquida_x_garantia_liquida_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
credito Num
<?php }else{ ?>
	<a href="php_garantia_liquidalist.php?order=<?php echo urlencode("credito_id"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">cr&eacute;dito Num<?php if (@$_SESSION["garantia_liquida_x_credito_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["garantia_liquida_x_credito_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
        <td>Cliente</td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
monto
<?php }else{ ?>
	<a href="php_garantia_liquidalist.php?order=<?php echo urlencode("monto"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">monto<?php if (@$_SESSION["garantia_liquida_x_monto_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["garantia_liquida_x_monto_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
status
<?php }else{ ?>
	<a href="php_garantia_liquidalist.php?order=<?php echo urlencode("status"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">status<?php if (@$_SESSION["garantia_liquida_x_status_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["garantia_liquida_x_status_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
fecha
<?php }else{ ?>
	<a href="php_garantia_liquidalist.php?order=<?php echo urlencode("fecha"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">fecha<?php if (@$_SESSION["garantia_liquida_x_fecha_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["garantia_liquida_x_fecha_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
fecha modificacion
<?php }else{ ?>
	<a href="php_garantia_liquidalist.php?order=<?php echo urlencode("fecha_modificacion"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">fecha modificaci&oacute;n<?php if (@$_SESSION["garantia_liquida_x_fecha_modificacion_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["garantia_liquida_x_fecha_modificacion_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
        <td>
        Concilia garant&iacute;a
        </td>

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
while (($row = @phpmkr_fetch_array($rs)) && ($nRecCount < $nStopRec)) {
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
		$sKey = $row["garantia_liquida_id"];
		$x_garantia_liquida_id = $row["garantia_liquida_id"];
		$x_credito_id = $row["credito_num"];
		$x_monto = $row["monto"];
		$x_status = $row["descripcion"];
		$x_fecha = $row["fecha"];
		$x_fecha_modificacion = $row["fecha_modificacion"];
		$x_nombre_completo = $row["nombre_completo"];
		$x_apellido_paterno = $row["apellido_paterno"];
		$x_apellido_materno = $row["apellido_materno"];
		$x_nombre_cliente = $x_nombre_completo." ". $x_apellido_paterno." ". $x_apellido_materno;
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
    <?php if ($sExport == "") { ?>
<td><span class="phpmaker"><a href="<?php if ((!is_null($sKey))) { echo "php_garantia_liquidaview.php?key=" . urlencode($sKey); } else { echo "javascript:alert('Invalid Record! Key is null');";  } ?>">Vier</a></span></td>
<td><span class="phpmaker"><a href="<?php if ((!is_null($sKey))) { echo "php_garantia_liquidaedit.php?key=" . urlencode($sKey); } else { echo "javascript:alert('Invalid Record! Key is null');"; } ?>">Editar</a></span></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<?php } ?>
		<!-- garantia_liquida_id -->
		<td><span>
<?php echo $x_garantia_liquida_id; ?>
</span></td>
		<!-- credito_id -->
		<td><span>
<?php echo $x_credito_id; ?>
</span></td>
<td><?php echo $x_nombre_cliente;?></td>
		<!-- monto -->
		<td><span>
<?php echo $x_monto; ?>
</span></td>
		<!-- status -->
		<td><span>
<?php echo $x_status; ?>
</span></td>
		<!-- fecha -->
		<td><span>
<?php echo FormatDateTime($x_fecha,5); ?>
</span></td>
		<!-- fecha_modificacion -->
		<td><span>
<?php echo FormatDateTime($x_fecha_modificacion,5); ?>
</span></td>
<td>
<?php if(($x_garantia_liquida_id> 0)){
	#echo $x_cocilia_cheque_id." ".$x_status_conciliado;
	$x_checked = "";
	if ($x_conciliada == 1){
	$x_checked = 'checked="checked"';
	#echo 1;
	}	
	$x_disable = "";
	if(($_SESSION["php_project_esf_status_UserRolID"] != 1) && ($_SESSION["php_project_esf_status_UserRolID"] != 4)  ){
	$x_disable = 'disabled="disabled"';
	}else{
		if (($_SESSION["php_project_esf_status_UserRolID"] == 4) && ($x_conciliada == 1)){
		$x_disable = 'disabled="disabled"';
		}
	} ?>
<span>
		<div id="capaGarantiaConciliada_<?php echo $x_garantia_liquida_id;?>"><input type="checkbox" name="x_concilia_garantia<?php echo $x_garantia_liquida_id; ?>" <?php echo $x_disable; ?> <?php echo $x_checked;?> onclick="ConciliaGarantia(<?php echo $x_garantia_liquida_id; ?>);"  /></div><?php } ?>

</td>
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

		// Field garantia_liquida_id
		if ($sOrder == "garantia_liquida_id") {
			$sSortField = "`garantia_liquida_id`";
			$sLastSort = @$_SESSION["garantia_liquida_x_garantia_liquida_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["garantia_liquida_x_garantia_liquida_id_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["garantia_liquida_x_garantia_liquida_id_Sort"] <> "") { $_SESSION["garantia_liquida_x_garantia_liquida_id_Sort"] = "" ; }
		}

		// Field credito_id
		if ($sOrder == "credito_id") {
			$sSortField = "`credito_id`";
			$sLastSort = @$_SESSION["garantia_liquida_x_credito_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["garantia_liquida_x_credito_id_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["garantia_liquida_x_credito_id_Sort"] <> "") { $_SESSION["garantia_liquida_x_credito_id_Sort"] = "" ; }
		}

		// Field monto
		if ($sOrder == "monto") {
			$sSortField = "`monto`";
			$sLastSort = @$_SESSION["garantia_liquida_x_monto_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["garantia_liquida_x_monto_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["garantia_liquida_x_monto_Sort"] <> "") { $_SESSION["garantia_liquida_x_monto_Sort"] = "" ; }
		}

		// Field status
		if ($sOrder == "status") {
			$sSortField = "`status`";
			$sLastSort = @$_SESSION["garantia_liquida_x_status_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["garantia_liquida_x_status_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["garantia_liquida_x_status_Sort"] <> "") { $_SESSION["garantia_liquida_x_status_Sort"] = "" ; }
		}

		// Field fecha
		if ($sOrder == "fecha") {
			$sSortField = "`fecha`";
			$sLastSort = @$_SESSION["garantia_liquida_x_fecha_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["garantia_liquida_x_fecha_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["garantia_liquida_x_fecha_Sort"] <> "") { $_SESSION["garantia_liquida_x_fecha_Sort"] = "" ; }
		}

		// Field fecha_modificacion
		if ($sOrder == "fecha_modificacion") {
			$sSortField = "`fecha_modificacion`";
			$sLastSort = @$_SESSION["garantia_liquida_x_fecha_modificacion_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["garantia_liquida_x_fecha_modificacion_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["garantia_liquida_x_fecha_modificacion_Sort"] <> "") { $_SESSION["garantia_liquida_x_fecha_modificacion_Sort"] = "" ; }
		}
		if ($bCtrl) {
			$sOrderBy = @$_SESSION["garantia_liquida_OrderBy"];
			$pos = strpos($sOrderBy, $sSortField . " " . $sLastSort);
			if ($pos === false) {
				if ($sOrderBy <> "") { $sOrderBy .= ", "; }
				$sOrderBy .= $sSortField . " " . $sThisSort;
			}else{
				$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
			}
			$_SESSION["garantia_liquida_OrderBy"] = $sOrderBy;
		}
		else
		{
			$_SESSION["garantia_liquida_OrderBy"] = $sSortField . " " . $sThisSort;
		}
		$_SESSION["garantia_liquida_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["garantia_liquida_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["garantia_liquida_OrderBy"] = $sOrderBy;
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
		$_SESSION["garantia_liquida_REC"] = $nStartRec;
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
			$_SESSION["garantia_liquida_REC"] = $nStartRec;
		}
		else
		{
			$nStartRec = @$_SESSION["garantia_liquida_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["garantia_liquida_REC"] = $nStartRec;
			}
		}
	}
	else
	{
		$nStartRec = @$_SESSION["garantia_liquida_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["garantia_liquida_REC"] = $nStartRec;
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
			$_SESSION["garantia_liquida_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}
		elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["garantia_liquida_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["garantia_liquida_OrderBy"] = $sOrderBy;
			if (@$_SESSION["garantia_liquida_x_garantia_liquida_id_Sort"] <> "") { $_SESSION["garantia_liquida_x_garantia_liquida_id_Sort"] = ""; }
			if (@$_SESSION["garantia_liquida_x_credito_id_Sort"] <> "") { $_SESSION["garantia_liquida_x_credito_id_Sort"] = ""; }
			if (@$_SESSION["garantia_liquida_x_monto_Sort"] <> "") { $_SESSION["garantia_liquida_x_monto_Sort"] = ""; }
			if (@$_SESSION["garantia_liquida_x_status_Sort"] <> "") { $_SESSION["garantia_liquida_x_status_Sort"] = ""; }
			if (@$_SESSION["garantia_liquida_x_fecha_Sort"] <> "") { $_SESSION["garantia_liquida_x_fecha_Sort"] = ""; }
			if (@$_SESSION["garantia_liquida_x_fecha_modificacion_Sort"] <> "") { $_SESSION["garantia_liquida_x_fecha_modificacion_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["garantia_liquida_REC"] = $nStartRec;
	}
}
?>
