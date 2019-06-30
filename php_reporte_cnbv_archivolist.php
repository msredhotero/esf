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
$filter['x_credito_tipo_id'] = 100;
$filter['x_empresa_srch'] = 100;
$_SESSION["solicitud_REC"] = 0;

$filter['x_alerta_tipo_id'] = 100;


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

//$x_credito_tipo_id = 100;

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







$x_posteo = $x_nombre_srch.$x_apepat_srch.$x_apemat_srch.$x_crenum_srch.$x_clinum_srch.$x_cresta_srch;
//$x_posteo_2 = $x_entidad_srch.$x_delegacion_srch;

if($filter["x_credito_tipo_id"] > 0){}else{


if(!empty($filter["x_alerta_tipo_id"])){
	//si se selecciono pero no es TODOS
	if(!empty($filter["x_alerta_tipo_id"]) && ($filter["x_alerta_tipo_id"] != "100")){
		$ssrch_tipo_cred .= "(alerta_pld.tipo_alerta_id = ".$filter["x_alerta_tipo_id"].") AND ";
	}
	
	
	}

$sSql = "SELECT * FROM reporte_cnv_archivos ";
 // echo $sSql."<br>";


$filter["x_nombre_srch"] = "";
$filter["x_apepat_srch"] = "";
$filter["x_apemat_srch"] = "";
$filter["x_crenum_srch"] = "";
$filter["x_clinum_srch"] = "";
$filter["x_cresta_srch"] = "";
$filter["x_empresa_srch"] = "";
$filter["start"] = 1;


$ssrch_sol = "";
$ssrch_cli = "";
$ssrch_cre = "";

//$ssrch_sol = "(solicitud.credito_tipo_id = 2) AND ";

}




// Load Default Filter
$sDefaultFilter = "";
$sGroupBy = "";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = "";

// Build WHERE condition
if($_SESSION["php_project_esf_status_UserRolID"] == 7) {
	$sDbWhere = "(solicitud.promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"]. ") AND ";
}else{
	$sDbWhere = "";
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



$sWhere = $ssrch_tipo_cred.$ssrch_sol.$ssrch_cli.$ssrch_cre.$srch_gar;


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

#$sSql = "SELECT * FROM reporte_cnv_archivos group by archivo ";
$sSql = "SELECT * FROM reporte_cnv_archivos  ";

$sSql .= " ORDER BY reporte_cnbv_id DESC";
//echo "sql:".$sSql."<br>";

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
<script src="paisedohint.js"></script> 
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
<p><span class="phpmaker">Listado archivos CNBV
<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="php_reporte_cnbvlist.php">	Listado reporte CNBV</a>

<div class="content-box-gray">
<br /><b>NOTA:</b> Para cada archivo que se env&iacute;e a la CNBV se deber&aacute; adjuntar el acuse de recibo electr&oacute;nico que envia la CNBV</div>
 
<?php } ?>
</span></p>
<?php if ($sExport == "") { 

?>

<form name="filtros" action="php_alerta_pld_list.php" method="post">
<input type="hidden" name="tipo_credito" value="" />
<input type="hidden" name="status" value="" />
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
<form action="php_alerta_pld_list.php?x_tipo_credito=7" name="ewpagerform" id="ewpagerform">
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
		<a href="php_alerta_pld_list.php?start=<?php echo $PrevStart; ?>&<?php echo $_QS; ?>"><b>Anterior</b></a>
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
		<a href="php_alerta_pld_list.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?></b></a>
					<?php }
					$x += $nDisplayRecs;
					$y += 1;
				} elseif (($x >= ($dx1-$nDisplayRecs*$nRecRange)) && ($x <= ($dx2+$nDisplayRecs*$nRecRange))) {
					if ($x+$nRecRange*$nDisplayRecs < $nTotalRecs) { ?>
		<a href="php_alerta_pld_list.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?>-<?php echo ($y+$nRecRange-1);?></b></a>
					<?php } else {
						$ny=intval(($nTotalRecs-1)/$nDisplayRecs)+1;
							if ($ny == $y) { ?>
		<a href="php_alerta_pld_list.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?></b></a>
							<?php } else { ?>
		<a href="php_alerta_pld_list.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?>-<?php echo $ny; ?></b></a>
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
		<a href="php_alerta_pld_list.php?start=<?php echo $NextStart; ?>&<?php echo $_QS; ?>"><b>Siguiente</b></a>
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
<form name="creditotipo" method="post" action="php_alerta_pld_list.php">


</form>
&nbsp;
<form name="filtro" method="post" action="php_alerta_pld_list.php">
</form>

<?php } ?>
<?php if ($nTotalRecs > 0)  { ?>
<form method="post">
<table id="ewlistmain" class="ewTable">
	<!-- Table header -->
	<tr class="ewTableHeader">
<?php if ($sExport == "") { ?>
<td colspan="2">Archivo</td>


<?php } ?>
		<td>Nombre</td>
         
        <td>Fecha</td>
         <td>Registros</td>
         <td>Acuse de recibo emitido por CNBV</td>
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
		//print_r($row);
		$x_reporte_cnbv_id = $row["reporte_cnbv_id"];
		$x_archivo_name = $row["archivo"];
		$x_archivo = $row["archivo"];
		$x_fecha_creacion = $row["fecha_creacion"];
		$x_no_registros = $row["no_registros"];
		$x_archivo_cnbv =  $row["archivo_cnbv"];
		$x_carpeta = $row["carpeta"];
		
		
		//echo "<br>".$x_credito_tipo_id ;
		
		if($x_carpeta){
			$x_archivo1 = "reportes_cnbv/$x_carpeta/".$x_archivo."";
			$x_archivo_cnbv_lin1 = "php_reporte_cnbv_descarga_ficheros.php?archivo=".$x_carpeta."/".$x_archivo."";
			}else{
				$x_archivo1 = "reportes_cnbv/".$x_archivo."";
				$x_archivo_cnbv_lin1 = "php_reporte_cnbv_descarga_ficheros.php?archivo=".$x_archivo."";
				}
		
		
		
		$x_archivo_cnbv_link = "php_reporte_cnbv_descarga_ficheros.php?archivo=".$x_archivo_cnbv."";
		
		
		
		
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>
<?php if ($sExport == "") { ?>

<td><a href="<?php echo $x_archivo1;?>" target="_blank">Ver archivo </a></span></td>
<td><a href="<?php echo $x_archivo_cnbv_lin1;?>" target="_blank">Descargar</a></span></td>

<?php } ?>
<td><?php echo $x_archivo_name; ?></td>
<td><?php echo $x_fecha_creacion; ?></td>
<td><?php echo $x_no_registros; ?></td>
<td>

<?php
if(empty($x_archivo_cnbv)){?>
	<a href="php_subir_archivo_cnbv.php?id=<?php echo $x_reporte_cnbv_id;?>".>Subir archivo de la CNBV</a>
    <?php
}else{
echo "<a href='reportes_cnbv/cnbv/".$x_archivo_cnbv."'target='_blank'>".$x_archivo_cnbv."</a>";


	

}?></td>
	
    	<!-- solicitud_id -->		<!-- folio -->
		<!-- solicitud_status_id -->		<!-- credito_tipo_id -->		<!-- fecha_registro -->
		<!-- promotor_id -->		<!-- importe_solicitado -->		<!-- plazo -->		<!-- contrato -->		<!-- pagare --></tr>
<?php
	}
}
?>
</table>
<?php if ($sExport == "") { ?>
<?php if ($nRecActual > 0) { ?>

<?php if($_SESSION["php_project_esf_status_UserRolID"] == 1) { ?>
<p>
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
	if($filter["x_credito_tipo_id_filtro"] == 1){
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
	$filter["x_credito_tipo_id_filtro"] = "";
	$filter["x_solicitud_status_id_filtro"]= "";

		// Reset Search Criteria
		if (strtoupper($sCmd) == "RESET") {
			$sSrchWhere = "";
			$_SESSION["solicitud_searchwhere"] = $sSrchWhere;
			$filter["x_nombre_srch"] = "";
			$filter["x_apepat_srch"] = "";
			$filter["x_apemat_srch"] = "";
			$filter["x_crenum_srch"] = "";
			$filter["x_clinum_srch"] = "";
			$filter["x_cresta_srch"] = "";


		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["solicitud_searchwhere"] = $sSrchWhere;
			$filter["x_nombre_srch"] = "";
			$filter["x_apepat_srch"] = "";
			$filter["x_apemat_srch"] = "";
			$filter["x_crenum_srch"] = "";
			$filter["x_clinum_srch"] = "";
			$filter["x_cresta_srch"] = "";

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
<!--or_id_Sort"] <> "") { $_SESSION["solicitud_x_promotor_id_Sort"] = ""; }
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
-->