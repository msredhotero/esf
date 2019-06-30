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
$x_recibo_qr_id = Null;
$x_promotor_id = Null;
$x_fecha = Null;
$x_hora = Null;
$x_cliente = Null;
$x_credito = Null;
$x_monto = Null;
$x_comentario = Null;
$x_folio = Null;
$x_confirma_cliente = Null;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=recibo_qr.xls');
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


$_SESSION["aplica_pagos"] =1;
#echo "sesion ".$_SESSION["aplica_pagos"]."<br>";

// Handle Reset Command
ResetCmd();
$filter = array();

$filter['x_credito_tipo_id'] = 100;

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

#echo "numero".$filter["x_crenum_srch"]."<br>";
#echo "cliente".$filter['x_clinum_srch']."<br>";

	$ssrch= "";
		if(!empty($filter["x_clinum_srch"])){
			$ssrch .= "(recibo_qr.cliente_num+0 = ".$filter["x_clinum_srch"].") AND ";
			//echo "campo lleno";
		}
	
		
		
	
		
		
		if(!empty($filter["x_crenum_srch"])){
			$ssrch .= "(recibo_qr.credito+0 = ".$filter["x_crenum_srch"].") AND ";
			//echo "campo 2 lleno<br>";
		}
		
	
	  
																																																																																																																			   if(!empty($filter["x_promo_srch"])){
		if((!empty($filter["x_promo_srch"])) && ($filter["x_promo_srch"] != "1000")){
			$ssrch .= "(recibo_qr.promotor_id = ".$filter["x_promo_srch"].") AND ";
			
		}
	}	
	
	
	
	
	
	if($_SESSION["php_project_esf_status_UserRolID"] == 12) {
		#sleccionamos los promotores de la sucursal
		$sqlSuc = "SELECT sucursal_id FROM responsable_sucursal	WHERE usuario_id =" .$_SESSION["php_project_esf_status_UserID"] ."";
		$rsScu = phpmkr_query($sqlSuc,$conn) or die ("Error al seleccionar la suc".phpmkr_error()."sql.".$sqlSuc);
		$rowSuc = phpmkr_fetch_array($rsScu);
		$x_sucursal_id =  $rowSuc["sucursal_id"];
		#echo  $sqlSuc."<br>";
		
		$sqlPromotores = "SELECT promotor_id FROM promotor WHERE promotor.sucursal_id = $x_sucursal_id";
		$rsPromotor = phpmkr_query($sqlPromotores,$conn) or die("Error al seleccionar los promotores".phpmkr_error()."Sql:". $sqlPromotores);
		while($rowPromotor = phpmkr_fetch_array($rsPromotor)){
			$x_listado_promotores = $x_listado_promotores. $rowPromotor["promotor_id"].", ";
			
			}
			$x_listado_promotores = trim($x_listado_promotores,", ");
		#echo "lisatdo promotores ".$x_listado_promotores."<br>";
		#seleccionamos losrecibos que sean de los prmotores
		$sqlRecibos = "SELECT recibo_qr_id FROM recibo_qr WHERE promotor_id in ($x_listado_promotores)";
		$rsRecibos = phpmkr_query($sqlRecibos,$conn) or die ("Error al selecciona los recibos de la sucursal". phpmkr_error()."sql:".$sqlRecibos);
		while($rowRecibos = phpmkr_fetch_array($rsRecibos)){
			$x_lista_recibos = $x_lista_recibos. $rowRecibos["recibo_qr_id"].", ";
			#echo $x_lista_recibos."<br>";
			}
			$x_lista_recibos = trim($x_lista_recibos,", ");
		
		$ssrch = " recibo_qr.recibo_qr_id in ($x_lista_recibos) AND ";
		#echo "sql lista".$ssrch ."<br>";
	}
	
	if(!empty($ssrch)){		
		$ssrch = substr($ssrch, 0, strlen($ssrch)-5);
		}


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
	$_SESSION["recibo_qr_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["recibo_qr_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["recibo_qr_searchwhere"];
}

// Build WHERE condition
$sDbWhere = $ssrch;
//echo "where ".$sDbWhere."<br>";
if ($sDbWhereMaster != "") {
	$sDbWhere .= "(" . $sDbWhereMaster . ") AND ";
}
if ($sSrchWhere != "") {
	$sDbWhere .= "(" . $sSrchWhere . ") AND ";
}


// Build SQL
$sSql = "SELECT * FROM `recibo_qr`";

// Load Default Filter
$sDefaultFilter = "";
$sGroupBy = "";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = "";
$sWhere = "";
if ($sDefaultFilter != "") {
	$sWhere .= "(" . $sDefaultFilter . ") AND ";
}
if ($sDbWhere != "") {
	$sWhere .= "(" . $sDbWhere . ") AND ";
}

if ($sDbWhere != "") {
	$sSql .= " WHERE " . $sDbWhere;
}
if ($sGroupBy != "") {
	$sSql .= " GROUP BY " . $sGroupBy;
}
if ($sHaving != "") {
	$sSql .= " HAVING " . $sHaving;
}

#echo " main sql ".$sSql ."<br>";
// Set Up Sorting Order
$sOrderBy = " recibo_qr_id DESC ";
SetUpSortOrder();
if ($sOrderBy != "") {
	$sSql .= " ORDER BY " . $sOrderBy;
}
?>
<?php include ("header.php") ?>
<?php if ($sExport == "") { ?>
<script type="text/javascript" src="ew.js"></script>
<script language="javascript" src="concilia_pago.js"></script>
<script language="javascript" src="concilia_recibo.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<?php } ?>
<?php

// Set up Record Set
$rs = phpmkr_query($sSql,$conn);
$nTotalRecs = phpmkr_num_rows($rs);
if ($nDisplayRecs <= 0) { // Display All Records
	$nDisplayRecs = $nTotalRecs;
}
$nStartRec = 1;
SetUpStartRec(); // Set Up Start Record Position
?>
<p><span class="phpmaker">Listado de Recibos QR registrados
<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="php_recibo_qrlist.php?export=excel">Export a Excel</a>
<?php } ?>
</span></p>
<?php if ($sExport == "") { ?>
<form action="php_recibo_qrlist.php" method="post">
<table width="879" border="0" cellpadding="0" cellspacing="0">
	<tr>
	  <td width="135" height="24">&nbsp;</td>
	  <td width="12">&nbsp;</td>
	  <td width="117">&nbsp;</td>
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

$sqlSucursal = "SELECT sucursal_id FROM responsable_sucursal WHERE usuario_id = ".$_SESSION["php_project_esf_status_UserID"]." ";
$rsSuc = phpmkr_query($sqlSucursal,$conn);
$rowSuc = phpmkr_fetch_array($rsSuc);
$x_sucursal_id =$rowSuc["sucursal_id"];
$_SESSION["php_project_esf_SucursalID"] = $x_sucursal_id;
		$x_estado_civil_idList = "<select name=\"x_sucursal_srch\" class=\"texto_normal\">";
		if($_SESSION["php_project_esf_status_UserRolID"] == 7) {
			$sSqlWrk = "SELECT sucursal.sucursal_id, nombre FROM sucursal join promotor on promotor.sucursal_id = sucursal.sucursal_id Where promotor.promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"];
		}else if($_SESSION["php_project_esf_status_UserRolID"] == 12){
			$sSqlWrk = "SELECT sucursal_id, nombre FROM sucursal Where sucursal.sucursal_id = ".$x_sucursal_id ;
			
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
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td colspan="3">&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td valign="middle">&nbsp;</td>
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
	  <td><span class="phpmaker"><a href="php_.php?cmd=reset">Mostrar Todos</a>&nbsp;&nbsp; </span></td>
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
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>
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
<form action="php_recibo_qrlist.php" name="ewpagerform" id="ewpagerform">
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
		if 	($nStartRec == 1) {
			$isPrev = False;
		} else {
			$isPrev = True;
			$PrevStart = $nStartRec - $nDisplayRecs;
			if ($PrevStart < 1) { $PrevStart = 1; } ?>
		<a href="php_recibo_qrlist.php?start=<?php echo $PrevStart; ?>"><b>Anterior</b></a>
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
		<a href="php_recibo_qrlist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
					<?php }
					$x += $nDisplayRecs;
					$y += 1;
				} elseif (($x >= ($dx1-$nDisplayRecs*$nRecRange)) && ($x <= ($dx2+$nDisplayRecs*$nRecRange))) {
					if ($x+$nRecRange*$nDisplayRecs < $nTotalRecs) { ?>
		<a href="php_recibo_qrlist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo ($y+$nRecRange-1);?></b></a>
					<?php } else {
						$ny=intval(($nTotalRecs-1)/$nDisplayRecs)+1;
							if ($ny == $y) { ?>
		<a href="php_recibo_qrlist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
							<?php } else { ?>
		<a href="php_recibo_qrlist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo $ny; ?></b></a>
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
		<a href="php_recibo_qrlist.php?start=<?php echo $NextStart; ?>"><b>Siguiente</b></a>
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
<table width="1026" class="ewTable">
<?php if ($nTotalRecs > 0) { ?>
	<!-- Table header -->
	<tr class="ewTableHeader">
    <?php if ($sExport == "") { ?>
<td width="26">&nbsp;</td>

<?php } ?>
		<td width="104" valign="top"><span>
<?php if ($sExport <> "") { ?>
recibo qr id
<?php }else{ ?>
	<a href="php_recibo_qrlist.php?order=<?php echo urlencode("recibo_qr_id"); ?>" style="color: #FFFFFF;">recibo qr id<?php if (@$_SESSION["recibo_qr_x_recibo_qr_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["recibo_qr_x_recibo_qr_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td width="105" valign="top"><span>
<?php if ($sExport <> "") { ?>
promotor id
<?php }else{ ?>
	<a href="php_recibo_qrlist.php?order=<?php echo urlencode("promotor_id"); ?>" style="color: #FFFFFF;">promotor id<?php if (@$_SESSION["recibo_qr_x_promotor_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["recibo_qr_x_promotor_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td width="119" valign="top"><span>
<?php if ($sExport <> "") { ?>
fecha
<?php }else{ ?>
	<a href="php_recibo_qrlist.php?order=<?php echo urlencode("fecha"); ?>" style="color: #FFFFFF;">fecha<?php if (@$_SESSION["recibo_qr_x_fecha_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["recibo_qr_x_fecha_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td width="90" valign="top"><span>
<?php if ($sExport <> "") { ?>
hora
<?php }else{ ?>
	<a href="php_recibo_qrlist.php?order=<?php echo urlencode("hora"); ?>" style="color: #FFFFFF;">hora&nbsp;(*)<?php if (@$_SESSION["recibo_qr_x_hora_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["recibo_qr_x_hora_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td width="93" valign="top"><span>
<?php if ($sExport <> "") { ?>
credito
<?php }else{ ?>
	<a href="php_recibo_qrlist.php?order=<?php echo urlencode("credito"); ?>" style="color: #FFFFFF;">credito&nbsp;(*)<?php if (@$_SESSION["recibo_qr_x_credito_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["recibo_qr_x_credito_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td width="124" valign="top"><span>
<?php if ($sExport <> "") { ?>
monto
<?php }else{ ?>
	<a href="php_recibo_qrlist.php?order=<?php echo urlencode("monto"); ?>" style="color: #FFFFFF;">monto<?php if (@$_SESSION["recibo_qr_x_monto_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["recibo_qr_x_monto_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td width="111" valign="top"><span>
<?php if ($sExport <> "") { ?>
folio
<?php }else{ ?>
	<a href="php_recibo_qrlist.php?order=<?php echo urlencode("folio"); ?>" style="color: #FFFFFF;">folio<?php if (@$_SESSION["recibo_qr_x_folio_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["recibo_qr_x_folio_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td width="134" valign="top"><span>
<?php if ($sExport <> "") { ?>
confirma cliente
<?php }else{ ?>
	<a href="php_recibo_qrlist.php?order=<?php echo urlencode("confirma_cliente"); ?>" style="color: #FFFFFF;">confirma cliente<?php if (@$_SESSION["recibo_qr_x_confirma_cliente_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["recibo_qr_x_confirma_cliente_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
        <td>Comentario</td>
         <td>Ver edo.Cuenta </td>
        <td width="76">
        Pagar 
        </td>
        <td>Pagoen aclaracion </td>
        <td>Bitacora Resp Suc</td>
        <td>Recibo archivado</td>

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
		$sKey = $row["recibo_qr_id"];
		$x_recibo_qr_id = $row["recibo_qr_id"];
		$x_promotor_id = $row["promotor_id"];
		$x_fecha = $row["fecha"];
		$x_hora = $row["hora"];
		$x_cliente_nombre = $row["cliente"];
		$x_credito = $row["credito"];
		$x_cliente = $row["cliente_num"];
		$x_monto = $row["monto"];
		$x_comentario = $row["comentario"];
		$x_folio = $row["folio"];
		$x_confirma_cliente = $row["confirma_cliente"];
		$x_pago_aplicado = $row["pago_aplicado"];
		//echo "pago a".$x_pago_aplicado;
		$x_nombre_promotor = "";
		$x_confirmacion = "";
		$x_link_aplica_pago = "";
		$x_nombre_cliente = "";
		
		$x_recibo_conflicto = $row["conflicto"];
$x_archivado = $row["archivado"];
//echo "archivado ".$x_archivado."-";
		$sqlPromo = "SELECT nombre_completo FROM promotor WHERE promotor_id = $x_promotor_id";
		$rsPromo = phpmkr_query($sqlPromo, $conn) or die("Error al seleccionar el promotor". phpmkr_error()."sql:".$sqlPromo);
		$rowPromo = phpmkr_fetch_array($rsPromo);
		$x_nombre_promotor = $rowPromo["nombre_completo"];
		if($x_confirma_cliente == 1){
			$x_confirmacion = "Si";
			}
			
		$sqlPromo = "SELECT nombre_completo FROM promotor WHERE promotor_id = $x_promotor_id";
		$rsPromo = phpmkr_query($sqlPromo, $conn) or die("Error al seleccionar el promotor". phpmkr_error()."sql:".$sqlPromo);
		$rowPromo = phpmkr_fetch_array($rsPromo);
		$x_nombre_promotor = $rowPromo["nombre_completo"];
		if($x_confirma_cliente == 1){
			$x_confirmacion = "Si";
			}	
		$x_link_edo_cuenta = "";	
		// buscamos los datos delc redito y del cliente para ver sis e pueden aplicar los pagos desde el lsiado o si se debera aplicar manualmente
		//echo "credito ".$x_credito ." cliene ".$x_cliente." ";
		if((!empty($x_credito)) and (!empty($x_cliente))){
			// si los dos campos estan llenos, se sigue le proceso
			//echo "entra al amyor";
			//echo "credito ".$x_credito ." cliene ".$x_cliente." ";
			// seleccionamos todos los creditos que tengan el mismo numeroy esten activos
			$sqlCre = "SELECT * FROM credito WHERE 	credito_num = $x_credito and credito_status_id IN (1,4,5 ) ";
			$rsCre = phpmkr_query($sqlCre,$conn) or die ("Error al seleccionar los datos del credito". phpmkr_error()."sql:".$sqlCre);
			$rowCre = phpmkr_fetch_array($rsCre);
			$x_credito_id = $rowCre["credito_id"];
			$x_numero_credito_activos = mysql_num_rows($rsCre);	 
			//echo " numero de credito activos".$x_numero_credito_activos."- ";
			$sqlCliente = "SELECT * FROM cliente  WHERE cliente.cliente_num = $x_cliente";
			//echo "sql :".$sqlCliente;
			$rsCliente = phpmkr_query($sqlCliente,$conn) or die ("Error al seleccionar los datos del cliente".phpmkr_error()."sql:".$sqlCliente);
			$rowCliente = phpmkr_fetch_array($rsCliente);	
			$x_cliente_id = $rowCliente["cliente_id"];
			$x_numero_clientes = mysql_num_rows($rsCliente);
			//echo "numero de clientes".$x_numero_clientes."- ";
			$x_nombre_cliente = $rowCliente["nombre_completo"]." ".$rowCliente["apellido_paterno"]." ".$rowCliente["apellido_materno"];
			$x_link_edo_cuenta = "php_creditoview.php?credito_id=$x_credito_id";
			
			// solo debe de exirtir un cliente y un credito, si hay mas no procede el pago
			if($x_numero_credito_activos== 1 and $x_numero_clientes==1){
				// si hay 1 y solo 1 concidencias entra.
				// seleccionamos los datos de la tarjeta de pagos del creditpo
			//	echo "entra ala segunbdo if ";
				$sqlTarjeta = " SELECT * FROM credito WHERE credito_id = $x_credito_id ";
				$rstarjeta = phpmkr_query($sqlTarjeta,$conn) or die ("Error al selccionar le numero de tarjeta". phpmkr_error()."sql:".$sqlTarjeta);
				$rowTarjeta = phpmkr_fetch_array($rstarjeta);
				$x_numero_tarjeta = $rowTarjeta["tarjeta_num"];
				
				if(!empty($x_numero_tarjeta)){
				// buscamos si esa tarjeta se encuantra en algun otro credito
				$sqlTrajetaDuplicada = "SELECT * FROM credito WHERE tarjeta_num = $x_numero_tarjeta  and credito.credito_status_id in (1,4,5) and credito_id != $x_credito_id"; 
				//echo "sql tarjeta dupliacada".$sqlTrajetaDuplicada."<br>";
				$rsTrajetaDuplicada = phpmkr_query($sqlTrajetaDuplicada,$conn) or die("Error al seleccionar los datos de la tarjeta duplicada". phpmkr_error()."SQL:".$sqlTrajetaDuplicada);
				$rowTarjetaDuplicada = phpmkr_fetch_array($rsTrajetaDuplicada);
				$x_credito_id_tarjeta_duplicada = $rowTarjetaDuplicada["credito_id"];
				
				// si no encontro ninguna tarjeta duplicada, se hace el proceso
				if(empty($x_credito_id_tarjeta_duplicada)){
					// si no encontro ningun otro credito con esa tarjeta, seguimos el proceso.					
					// creamos el link para enviar al proceso
					//echo "dentro-- pago aplicado".$x_pago_aplicado."--";
					if($x_pago_aplicado !=1){
					//	echo "link ";
					$x_link_aplica_pago = "php_aplicacion_pagos_reciboqr.php?x_credito_id=$x_credito_id&x_tarjeta=$x_numero_tarjeta&x_cliente_id=$x_cliente&x_folio=$x_folio&x_monto=$x_monto&x_fecha=$x_fecha&x_qr_id=$x_recibo_qr_id";}else{
						$x_link_aplica_pago = "Pago aplicado";
						}
						//echo $x_link_aplica_pago;
					
				}// no hay tarjeta duplicacda								
				
				
				}// numero de tarjeta lleno		
				
				}// solo hay un cliente y un credito
			
			}	// el campo cliente id y el campo credito-id estan llenos
			
			
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
    <?php if ($sExport == "") { ?>
<td><span class="phpmaker"><a href="<?php if ((!is_null($sKey))) { echo "php_recibo_qrview.php?key=" . urlencode($sKey); } else { echo "javascript:alert('Invalid Record! Key is null');";  } ?>">Ver</a></span></td>

<?php } ?>
		<!-- recibo_qr_id -->
		<td><span>
<?php echo $x_recibo_qr_id; ?>
</span></td>
		<!-- promotor_id -->
		<td><span>
<?php echo $x_nombre_promotor; ?>
</span></td>
		<!-- fecha -->
		<td><span>
<?php echo FormatDateTime($x_fecha,5); ?>
</span></td>
		<!-- hora -->
		<td><span>
<?php echo $x_hora; ?>
</span></td>
		<!-- credito -->
		<td><span>
<?php echo $x_credito; ?>
</span></td>
		<!-- monto -->
		<td><span>
<?php echo $x_monto; ?>
</span></td>
		<!-- folio -->
		<td><span>
<?php echo $x_folio; ?>
</span></td>
		<!-- confirma_cliente -->
		<td><span>
<?php echo $x_confirmacion; ?>
</span></td>
<td><?php echo $x_comentario;?></td>
<td><?php if(($_SESSION["php_project_esf_status_UserRolID"] == 4) || (($_SESSION["php_project_esf_status_UserRolID"] == 1))){ ?>
		<a href="<?php echo $x_link_edo_cuenta;?>">Edo.Cuenta</a><?php } ?></td>
		<td>
		<?php if(($_SESSION["php_project_esf_status_UserRolID"] == 4) || (($_SESSION["php_project_esf_status_UserRolID"] == 1))){  if($x_pago_aplicado== 1){?> Pago Aplicado<?php }else{?>
		<?php if(!empty($x_link_aplica_pago)){?><span><a href="<?php echo $x_link_aplica_pago;?>">Aplicar pago</a><?php } }}else{
			if($x_pago_aplicado== 1){?> Pago Aplicado <?php }
			
			}?>

</span></td>
<td>
<?php if(($x_recibo_qr_id> 0)){
	#echo $x_cocilia_cheque_id." ".$x_status_conciliado;
	$x_checked = "";
	if ($x_recibo_conflicto == 1){
	$x_checked = 'checked="checked"';
	#echo 1;
	}	
	$x_disable = "";
	if(($_SESSION["php_project_esf_status_UserRolID"] != 1) && ($_SESSION["php_project_esf_status_UserRolID"] != 4)){
	$x_disable = 'disabled="disabled"';
	}else{
		if (($_SESSION["php_project_esf_status_UserRolID"] == 4) && ($x_conciliada == 1)){
		$x_disable = 'disabled="disabled"';
		}
	} 
	
	
	?>
<span>
		<div id="capaReciboConficto_<?php echo $x_recibo_qr_id;?>"><input type="checkbox" name="x_recibo_conflicto<?php echo $x_recibo_qr_id; ?>" <?php echo $x_disable; ?> <?php echo $x_checked;?> onclick="ReciboConflicto(<?php echo $x_recibo_qr_id; ?>);"  /></div><?php } ?>




</td>
<td><span>   
    <iframe name="comentarios" src="php_recibo_qr_coment_visor.php?x_recibo_qr_id=<?php echo $x_recibo_qr_id;?>" scrolling="no" style="margin-left:0px; width:100px; height:30px; margin-top:-5px" frameborder="0" allowtransparency="true" id="contenido"></iframe>
</span></td>
<td>
<?php if(($x_recibo_qr_id> 0)){
	#echo $x_cocilia_cheque_id." ".$x_status_conciliado;
	$x_checked = "";
	$x_disable = "";
	if ($x_archivado >0){
	$x_checked = 'checked="checked"';
	$x_disable = 'disabled="disabled"';
	#echo 1;
	}	
	
	if(($_SESSION["php_project_esf_status_UserRolID"] != 1) && ($_SESSION["php_project_esf_status_UserRolID"] != 12)){
	$x_disable = 'disabled="disabled"';
	}else{
		if (($_SESSION["php_project_esf_status_UserRolID"] == 12) && ($x_conciliada == 1)){
		$x_disable = 'disabled="disabled"';
		}
	} ?>
<span>
		<div id="capaReciboArchivado_<?php echo $x_recibo_qr_id;?>"><input type="checkbox" name="x_archiva_recibo<?php echo $x_recibo_qr_id; ?>" <?php echo $x_disable; ?> <?php echo $x_checked;?> onclick="ArchivaRecibo(<?php echo $x_recibo_qr_id; ?>);"  /></div><?php } ?>

</td>
<td></td>

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
// Function BasicSearchSQL
// - Build WHERE clause for a keyword

function BasicSearchSQL($Keyword)
{
	$sKeyword = (!get_magic_quotes_gpc()) ? addslashes($Keyword) : $Keyword;
	$BasicSearchSQL = "";
	$BasicSearchSQL.= "`hora` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`cliente` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`credito` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`comentario` LIKE '%" . $sKeyword . "%' OR ";
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
	$sSearch = (!get_magic_quotes_gpc()) ? addslashes(@$_GET["psearch"]) : @$_GET["psearch"];
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

		// Field recibo_qr_id
		if ($sOrder == "recibo_qr_id") {
			$sSortField = "`recibo_qr_id`";
			$sLastSort = @$_SESSION["recibo_qr_x_recibo_qr_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["recibo_qr_x_recibo_qr_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["recibo_qr_x_recibo_qr_id_Sort"] <> "") { @$_SESSION["recibo_qr_x_recibo_qr_id_Sort"] = ""; }
		}

		// Field promotor_id
		if ($sOrder == "promotor_id") {
			$sSortField = "`promotor_id`";
			$sLastSort = @$_SESSION["recibo_qr_x_promotor_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["recibo_qr_x_promotor_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["recibo_qr_x_promotor_id_Sort"] <> "") { @$_SESSION["recibo_qr_x_promotor_id_Sort"] = ""; }
		}

		// Field fecha
		if ($sOrder == "fecha") {
			$sSortField = "`fecha`";
			$sLastSort = @$_SESSION["recibo_qr_x_fecha_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["recibo_qr_x_fecha_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["recibo_qr_x_fecha_Sort"] <> "") { @$_SESSION["recibo_qr_x_fecha_Sort"] = ""; }
		}

		// Field hora
		if ($sOrder == "hora") {
			$sSortField = "`hora`";
			$sLastSort = @$_SESSION["recibo_qr_x_hora_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["recibo_qr_x_hora_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["recibo_qr_x_hora_Sort"] <> "") { @$_SESSION["recibo_qr_x_hora_Sort"] = ""; }
		}

		// Field credito
		if ($sOrder == "credito") {
			$sSortField = "`credito`";
			$sLastSort = @$_SESSION["recibo_qr_x_credito_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["recibo_qr_x_credito_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["recibo_qr_x_credito_Sort"] <> "") { @$_SESSION["recibo_qr_x_credito_Sort"] = ""; }
		}

		// Field monto
		if ($sOrder == "monto") {
			$sSortField = "`monto`";
			$sLastSort = @$_SESSION["recibo_qr_x_monto_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["recibo_qr_x_monto_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["recibo_qr_x_monto_Sort"] <> "") { @$_SESSION["recibo_qr_x_monto_Sort"] = ""; }
		}

		// Field folio
		if ($sOrder == "folio") {
			$sSortField = "`folio`";
			$sLastSort = @$_SESSION["recibo_qr_x_folio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["recibo_qr_x_folio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["recibo_qr_x_folio_Sort"] <> "") { @$_SESSION["recibo_qr_x_folio_Sort"] = ""; }
		}

		// Field confirma_cliente
		if ($sOrder == "confirma_cliente") {
			$sSortField = "`confirma_cliente`";
			$sLastSort = @$_SESSION["recibo_qr_x_confirma_cliente_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["recibo_qr_x_confirma_cliente_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["recibo_qr_x_confirma_cliente_Sort"] <> "") { @$_SESSION["recibo_qr_x_confirma_cliente_Sort"] = ""; }
		}
		$_SESSION["recibo_qr_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["recibo_qr_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["recibo_qr_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["recibo_qr_OrderBy"] = $sOrderBy;
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
		$_SESSION["recibo_qr_REC"] = $nStartRec;
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
			$_SESSION["recibo_qr_REC"] = $nStartRec;
		}
		else
		{
			$nStartRec = @$_SESSION["recibo_qr_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["recibo_qr_REC"] = $nStartRec;
			}
		}
	}
	else
	{
		$nStartRec = @$_SESSION["recibo_qr_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["recibo_qr_REC"] = $nStartRec;
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
			$_SESSION["recibo_qr_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}
		elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["recibo_qr_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["recibo_qr_OrderBy"] = $sOrderBy;
			if (@$_SESSION["recibo_qr_x_recibo_qr_id_Sort"] <> "") { $_SESSION["recibo_qr_x_recibo_qr_id_Sort"] = ""; }
			if (@$_SESSION["recibo_qr_x_promotor_id_Sort"] <> "") { $_SESSION["recibo_qr_x_promotor_id_Sort"] = ""; }
			if (@$_SESSION["recibo_qr_x_fecha_Sort"] <> "") { $_SESSION["recibo_qr_x_fecha_Sort"] = ""; }
			if (@$_SESSION["recibo_qr_x_hora_Sort"] <> "") { $_SESSION["recibo_qr_x_hora_Sort"] = ""; }
			if (@$_SESSION["recibo_qr_x_credito_Sort"] <> "") { $_SESSION["recibo_qr_x_credito_Sort"] = ""; }
			if (@$_SESSION["recibo_qr_x_monto_Sort"] <> "") { $_SESSION["recibo_qr_x_monto_Sort"] = ""; }
			if (@$_SESSION["recibo_qr_x_folio_Sort"] <> "") { $_SESSION["recibo_qr_x_folio_Sort"] = ""; }
			if (@$_SESSION["recibo_qr_x_confirma_cliente_Sort"] <> "") { $_SESSION["recibo_qr_x_confirma_cliente_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["recibo_qr_REC"] = $nStartRec;
	}
}
?>
