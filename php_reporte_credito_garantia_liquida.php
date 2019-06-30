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
$x_constancia_descuento_id = Null;
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
	header('Content-Disposition: attachment; filename=constancia_descuento.xls');
}
if ($sExport == "word") {
	header('Content-Type: application/vnd.ms-word');
	header('Content-Disposition: attachment; filename=constancia_descuento.doc');
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
$nDisplayRecs = 1000;
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
$filter['x_tipo_id'] = 100;
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
$x_vacio = 0;

	if((!empty($filter["x_nombre_srch"])) || (!empty($filter["x_apepat_srch"])) || (!empty($filter["x_apemat_srch"])) || (!empty($filter["x_clinum_srch"])) ){
		$ssrch = "";
		if(!empty($filter["x_nombre_srch"])){
			$ssrch .= "(cliente like '%".$filter["x_nombre_srch"]."%') AND ";
		}
		if(!empty($filter["x_apepat_srch"])){
			$ssrch .= "(cliente like '%".$filter["x_apepat_srch"]."%') AND ";
		}
		if(!empty($filter["x_apemat_srch"])){
			$ssrch .= "(cliente '%".$filter["x_apemat_srch"]."%') AND ";
		}
		
#	echo $ssrch;
	
		
		 if(!empty($filter["x_tipo_id"]) && $filter["x_tipo_id"] != 100){
		 
		$ssrch .= "(tipo_constancia_id = ".$filter["x_tipo_id"].") AND "; 
	 }
			
	$ssrch = substr($ssrch, 0, strlen($ssrch)-5);	
	$x_vacio = 1;
	}else{
		$ssrch = "";
	}
	 if(!empty($filter["x_tipo_id"]) && $filter["x_tipo_id"] != 100 && $x_vacio == 0){
		 
		$ssrch .= "(tipo_constancia_id = ".$filter["x_tipo_id"].") AND "; 
	 }
	#echo "segundo ".$ssrch;
	// En Credito
$sWhere = $ssrch_SUC.$ssrch_cli.$ssrch_cre.$sNPWhere.$ssrch;


// aqui en la oficina............
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
 if ($_SESSION["php_project_esf_status_UserRolID"] == 12 || $_SESSION["php_project_esf_status_UserRolID"] == 17 || $_SESSION["php_project_esf_status_UserRolID"] == 18 ){
	 
 


		 $sSql = "SELECT * FROM  constancia_descuento  ";
	 }else{




$sSql = "SELECT * FROM  constancia_descuento ";
	 }



$sSql = " SELECT credito.*, garantia_liquida.status  as gar_estatus, garantia_liquida.monto as gar_monto FROM credito join garantia_liquida on garantia_liquida.credito_id = credito.credito_id and credito.garantia_liquida = 1 and credito.	credito_status_id in (1,4) ";




// Load Default Filter
$sDefaultFilter = "";
$sGroupBy = "";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = "";
$sWhere = "";


$sWhere = $ssrch_SUC.$ssrch_cli.$ssrch_cre.$sNPWhere.$ssrch;
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

#echo "<br> ".$sSql;
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
$rs = phpmkr_query($sSql,$conn);
$nTotalRecs = phpmkr_num_rows($rs);
if ($nDisplayRecs <= 0) { // Display All Records
	$nDisplayRecs = $nTotalRecs;
}
$nStartRec = 1;
SetUpStartRec(); // Set Up Start Record Position
?>
<p><span class="phpmaker">
  <?php if ($sExport == "") { ?>
&nbsp;&nbsp;
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





<form action="php_constancias_descuentolist.php" name="ewpagerform" id="ewpagerform">
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
		<a href="php_constancias_descuentolist.php?start=<?php echo $PrevStart; ?>&<?php echo $_QS; ?>"><b>Anterior</b></a>
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
		<a href="php_constancias_descuentolist.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?></b></a>
					<?php }
					$x += $nDisplayRecs;
					$y += 1;
				} elseif (($x >= ($dx1-$nDisplayRecs*$nRecRange)) && ($x <= ($dx2+$nDisplayRecs*$nRecRange))) {
					if ($x+$nRecRange*$nDisplayRecs < $nTotalRecs) { ?>
		<a href="php_constancias_descuentolist.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?>-<?php echo ($y+$nRecRange-1);?></b></a>
					<?php } else {
						$ny=intval(($nTotalRecs-1)/$nDisplayRecs)+1;
							if ($ny == $y) { ?>
		<a href="php_constancias_descuentolist.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?></b></a>
							<?php } else { ?>
		<a href="php_constancias_descuentolist.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?>-<?php echo $ny; ?></b></a>
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
			  <a href="php_constancias_descuentolist.php?start=<?php echo $NextStart; ?>&<?php echo $_QS; ?>"><b>Siguiente</b></a>
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
	<a href="php_constancias_descuentolist.php?order=<?php echo urlencode("constancia_descuento_id"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">ID<?php if (@$_SESSION["constancia_descuento_x_constancia_descuento_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["constancia_descuento_x_constancia_descuento_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
        	<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Folio
<?php }else{ ?>Num credito<?php } ?>
		</span></td>
        <td>Otorgamiento</td>
<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Vendedor
<?php }else{ ?>
NO vencimiento
<?php } ?>
		</span></td>
       <td valign="top"><span>
  <?php if ($sExport <> "") { ?>
         Fecha registro
  <?php }else{ ?>
  <?php } ?>
       </span></td>
 <td valign="top"><span>
  <?php if ($sExport <> "") { ?>
   Nobre completo
  <?php }else{ ?>
   PAgadoso
  <?php } ?>
 </span></td>
        <td valign="top"><span>
  <?php if ($sExport <> "") { ?>
          Monto  
  <?php }else{ ?>
          Monto
  <?php } ?>
        </span></td>
        <td valign="top"><span>
          <?php if ($sExport <> "") { ?>
          Observaciones
          <?php }else{ ?>
          Status garantia
          <?php } ?>
        </span></td>
         <td valign="top"><span>
          <?php if ($sExport <> "") { ?>
         Pendientes
          <?php }else{ ?>
        Pendientes
          <?php } ?>
        </span></td>
         <td valign="top"><span>
          <?php if ($sExport <> "") { ?>
          Penalizaciones
          <?php }else{ ?>
          Penalizaciones
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
		$sKey = $row["constancia_descuento_id"];
		
		foreach($row as $nombre => $valor){
			$$nombre = $valor;
			//echo $nombre;
			}
			
$x_pagados= 0;
$sqlPagados = "SELECT * from vencimiento where credito_id = $credito_id and vencimiento_status_id = 2  group BY vencimiento_num ";
$rsPagados = phpmkr_query($sqlPagados,$conn)or die ("Error al seleccionar los vencimientos".phpmkr_error()."sql :".$sqlPagados);
//$rowPagados = phpmkr_fetch_array($rsPagados);
//echo $sqlPagados;
$x_pagados =  mysql_num_rows($rsPagados);
$sqlPagados = "SELECT count(*) as pendientes from vencimiento where credito_id = $credito_id and vencimiento_status_id in (1,3)  and vencimiento_num < 1000; ";
$rsPagados = phpmkr_query($sqlPagados,$conn)or die ("Error al seleccionar los vencimientos".phpmkr_error()."sql :".$sqlPagados);
$rowPagados = phpmkr_fetch_array($rsPagados);	
$x_pendientes = $rowPagados["pendientes"];

$sqlPagados = "SELECT count(*) as pendientes from vencimiento where credito_id = $credito_id and vencimiento_status_id in (1,3)  and vencimiento_num > 2000; ";
$rsPagados = phpmkr_query($sqlPagados,$conn)or die ("Error al seleccionar los vencimientos".phpmkr_error()."sql :".$sqlPagados);
$rowPagados = phpmkr_fetch_array($rsPagados);	
$x_penalizaciones_pendientes = $rowPagados["pendientes"];

$sqlFormaPago = "SELECT * FROM forma_pago where forma_pago_id = $forma_pago_id ";
$rsFormaPago = phpmkr_query($sqlFormaPago,$conn) or die("Error al seleccionar la forma de pago".phpmkr_error()."sql:".$sqlFormaPago);
$rowFormaPago = phpmkr_fetch_array($rsFormaPago);
$x_forma_pago = $rowFormaPago["descripcion"];	

$sqlFormaPago = "SELECT * FROM garantia_liquida_status where garantia_liquida_status_id = $gar_estatus ";
$rsFormaPago = phpmkr_query($sqlFormaPago,$conn) or die("Error al seleccionar la forma de pago".phpmkr_error()."sql:".$sqlFormaPago);
$rowFormaPago = phpmkr_fetch_array($rsFormaPago);
$x_garantia = $rowFormaPago["descripcion"];	


$x_imprime = 0;
 switch($forma_pago_id){
	 	case 1:
		if($x_penalizaciones_pendientes <= 3 && $x_pendientes < 3){
			$x_imprime = 1;			
			}	 
		break;
		case 2:
		if($x_penalizaciones_pendientes <= 2 && $x_pendientes < 2){
			$x_imprime = 1;			
			}	 
		break;
		case 3:
		if($x_penalizaciones_pendientes <= 2 && $x_pendientes < 2){
			$x_imprime = 1;			
			}	 
		break;
		case 4:
		if($x_penalizaciones_pendientes <= 2 && $x_pendientes < 2){
			$x_imprime = 1;			
			}	 
		break;
			
	 
	 }	
		
#echo "imprime ". $x_imprime." pendientes ".$x_pendientes." penalizaciones ".$x_penalizaciones_pendientes."forma pago _id ".$forma_pago_id." ";		
?>
	<!-- Table body -->
    
  <?php if ($x_imprime == 1){?>  
  
	<tr<?php echo $sItemRowClass; ?>>
    <?php if ($sExport == "") { ?>

<?php } ?>
		<!-- constancia_descuento_id -->
		<td><span>
<?php echo  $credito_id; ?>
</span></td>
<td><span>
<?php echo $credito_num." "; ?>
<a href="php_creditoedit.php?credito_id=<?php echo $credito_id;?>" target="_blank">Ver</a>
</span></td>
<td><span>
<?php echo $fecha_otrogamiento; ?>
</span></td>
<td><span>
<?php echo  $num_pagos; ?>
</span></td>

<td><span>
  <?php echo $x_forma_pago; ?>
</span></td>
<td><span>
  <?php echo $x_pagados ?>
</span></td>
<td><span>
  <?php echo $gar_monto; ?>
</span></td>
<td><span>
  <?php echo $x_garantia; ?>
</span></td>
<td><span>
  <?php echo $x_pendientes; ?>
</span></td>
<td><span>
  <?php echo $x_penalizaciones_pendientes; ?>
</span></td>

</tr>
<?php
  }// imprime es 1;
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

		// Field constancia_descuento_id
		if ($sOrder == "constancia_descuento_id") {
			$sSortField = "`constancia_descuento_id`";
			$sLastSort = @$_SESSION["constancia_descuento_x_constancia_descuento_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["constancia_descuento_x_constancia_descuento_id_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["constancia_descuento_x_constancia_descuento_id_Sort"] <> "") { $_SESSION["constancia_descuento_x_constancia_descuento_id_Sort"] = "" ; }
		}

		// Field credito_id
		if ($sOrder == "credito_id") {
			$sSortField = "`credito_id`";
			$sLastSort = @$_SESSION["constancia_descuento_x_credito_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["constancia_descuento_x_credito_id_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["constancia_descuento_x_credito_id_Sort"] <> "") { $_SESSION["constancia_descuento_x_credito_id_Sort"] = "" ; }
		}

		// Field monto
		if ($sOrder == "monto") {
			$sSortField = "`monto`";
			$sLastSort = @$_SESSION["constancia_descuento_x_monto_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["constancia_descuento_x_monto_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["constancia_descuento_x_monto_Sort"] <> "") { $_SESSION["constancia_descuento_x_monto_Sort"] = "" ; }
		}

		// Field status
		if ($sOrder == "status") {
			$sSortField = "`status`";
			$sLastSort = @$_SESSION["constancia_descuento_x_status_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["constancia_descuento_x_status_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["constancia_descuento_x_status_Sort"] <> "") { $_SESSION["constancia_descuento_x_status_Sort"] = "" ; }
		}

		// Field fecha
		if ($sOrder == "fecha") {
			$sSortField = "`fecha`";
			$sLastSort = @$_SESSION["constancia_descuento_x_fecha_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["constancia_descuento_x_fecha_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["constancia_descuento_x_fecha_Sort"] <> "") { $_SESSION["constancia_descuento_x_fecha_Sort"] = "" ; }
		}

		// Field fecha_modificacion
		if ($sOrder == "fecha_modificacion") {
			$sSortField = "`fecha_modificacion`";
			$sLastSort = @$_SESSION["constancia_descuento_x_fecha_modificacion_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["constancia_descuento_x_fecha_modificacion_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["constancia_descuento_x_fecha_modificacion_Sort"] <> "") { $_SESSION["constancia_descuento_x_fecha_modificacion_Sort"] = "" ; }
		}
		if ($bCtrl) {
			$sOrderBy = @$_SESSION["constancia_descuento_OrderBy"];
			$pos = strpos($sOrderBy, $sSortField . " " . $sLastSort);
			if ($pos === false) {
				if ($sOrderBy <> "") { $sOrderBy .= ", "; }
				$sOrderBy .= $sSortField . " " . $sThisSort;
			}else{
				$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
			}
			$_SESSION["constancia_descuento_OrderBy"] = $sOrderBy;
		}
		else
		{
			$_SESSION["constancia_descuento_OrderBy"] = $sSortField . " " . $sThisSort;
		}
		$_SESSION["constancia_descuento_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["constancia_descuento_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["constancia_descuento_OrderBy"] = $sOrderBy;
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
		$_SESSION["constancia_descuento_REC"] = $nStartRec;
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
			$_SESSION["constancia_descuento_REC"] = $nStartRec;
		}
		else
		{
			$nStartRec = @$_SESSION["constancia_descuento_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["constancia_descuento_REC"] = $nStartRec;
			}
		}
	}
	else
	{
		$nStartRec = @$_SESSION["constancia_descuento_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["constancia_descuento_REC"] = $nStartRec;
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
			$_SESSION["constancia_descuento_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}
		elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["constancia_descuento_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["constancia_descuento_OrderBy"] = $sOrderBy;
			if (@$_SESSION["constancia_descuento_x_constancia_descuento_id_Sort"] <> "") { $_SESSION["constancia_descuento_x_constancia_descuento_id_Sort"] = ""; }
			if (@$_SESSION["constancia_descuento_x_credito_id_Sort"] <> "") { $_SESSION["constancia_descuento_x_credito_id_Sort"] = ""; }
			if (@$_SESSION["constancia_descuento_x_monto_Sort"] <> "") { $_SESSION["constancia_descuento_x_monto_Sort"] = ""; }
			if (@$_SESSION["constancia_descuento_x_status_Sort"] <> "") { $_SESSION["constancia_descuento_x_status_Sort"] = ""; }
			if (@$_SESSION["constancia_descuento_x_fecha_Sort"] <> "") { $_SESSION["constancia_descuento_x_fecha_Sort"] = ""; }
			if (@$_SESSION["constancia_descuento_x_fecha_modificacion_Sort"] <> "") { $_SESSION["constancia_descuento_x_fecha_modificacion_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["constancia_descuento_REC"] = $nStartRec;
	}
}
?>

