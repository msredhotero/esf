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
$x_id_csv_lista_negra_cnbv = Null;
$x_nombre_completo = Null;
$x_rfc = Null;
$x_fecha_reporte = Null;
$x_monto = Null;
$x_entidad_reporta = Null;
$x_notas = Null;


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
$nDisplayRecs = 200;
$nRecRange = 10;

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS,DB, PORT);

// Handle Reset Command
ResetCmd();

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
	$_SESSION["csv_lista_negra_cnbv_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["csv_lista_negra_cnbv_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["csv_lista_negra_cnbv_searchwhere"];
}

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
$sSql = "SELECT * FROM `csv_lista_negra_cnbv`";

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
<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
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
<p><span class="phpmaker">LISTA NEGRA DE LA CNBV</span></p>
<form action="php_cnbvlist.php">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="337"><span class="phpmaker">
			<input type="text" name="psearch" size="20">
			<input type="Submit" name="Submit" value="Buscar(*)">
			&nbsp;&nbsp;
			<a href="php_cnbvlist.php?cmd=reset">Mostrar todos</a>&nbsp;&nbsp;
		</span></td>
	</tr>
	<tr><td><span class="phpmaker"><input type="radio" name="psearchtype" value="" checked>
	Frase exacta&nbsp;&nbsp;
	<input type="radio" name="psearchtype" value="AND">
	Todas las palabras&nbsp;&nbsp;
	<input type="radio" name="psearchtype" value="OR">
	Cualquier palabra</span></td></tr>
</table>
</form>
<p>&nbsp;</p>

<p>
<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="phpmaker" style="color: Red;"><?php echo $_SESSION["ewmsg"]; ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>
<p>&nbsp;</p>
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><span class="phpmaker"><a href="php_lista_cnbvadd.php">Agregar nuevo registro</a></span></td>
	</tr>
    <tr>
		<td><span class="phpmaker"><a href="php_reporte_cnbv_carga_lista_cnbv.php">Importar registros a la lista</a></span></td>
	</tr>
</table>
<p>
<form action="php_cnbvlist.php" name="ewpagerform" id="ewpagerform">
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
		<a href="php_cnbvlist.php?start=<?php echo $PrevStart; ?>"><b>Anterior</b></a>
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
		<a href="php_cnbvlist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
					<?php }
					$x += $nDisplayRecs;
					$y += 1;
				} elseif (($x >= ($dx1-$nDisplayRecs*$nRecRange)) && ($x <= ($dx2+$nDisplayRecs*$nRecRange))) {
					if ($x+$nRecRange*$nDisplayRecs < $nTotalRecs) { ?>
		<a href="php_cnbvlist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo ($y+$nRecRange-1);?></b></a>
					<?php } else {
						$ny=intval(($nTotalRecs-1)/$nDisplayRecs)+1;
							if ($ny == $y) { ?>
		<a href="php_cnbvlist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
							<?php } else { ?>
		<a href="php_cnbvlist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo $ny; ?></b></a>
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
		<a href="php_cnbvlist.php?start=<?php echo $NextStart; ?>"><b>Siguiente</b></a>
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
<form method="post">
<table width="1259" class="ewTable">
<?php if ($nTotalRecs > 0) { ?>
	<!-- Table header -->
	<tr class="ewTableHeader">
    <td>Editar</td>
   
		<td valign="top"><span>
	<a href="php_cnbvlist.php?order=<?php echo urlencode("id_csv_lista_negra_cnbv"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">id_csv_lista_negra_cnbv<?php if (@$_SESSION["csv_lista_negra_cnbv_x_id_csv_lista_negra_cnbv_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["csv_lista_negra_cnbv_x_id_csv_lista_negra_cnbv_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="php_cnbvlist.php?order=<?php echo urlencode("nombre_completo"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">Nombre completo<?php if (@$_SESSION["csv_lista_negra_cnbv_x_nombre_completo_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["csv_lista_negra_cnbv_x_nombre_completo_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="php_cnbvlist.php?order=<?php echo urlencode("rfc"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">RFC<?php if (@$_SESSION["csv_lista_negra_cnbv_x_rfc_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["csv_lista_negra_cnbv_x_rfc_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
        
        
		<td valign="top"><span>
	<a href="php_cnbvlist.php?order=<?php echo urlencode("fecha_reporte"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">Fecha reporte<?php if (@$_SESSION["csv_lista_negra_cnbv_x_fecha_reporte_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["csv_lista_negra_cnbv_x_fecha_reporte_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="php_cnbvlist.php?order=<?php echo urlencode("monto"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">Monto<?php if (@$_SESSION["csv_lista_negra_cnbv_x_monto_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["csv_lista_negra_cnbv_x_monto_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="php_cnbvlist.php?order=<?php echo urlencode("entidad_reporta"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">Entidad que reporta<?php if (@$_SESSION["csv_lista_negra_cnbv_x_entidad_reporta_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["csv_lista_negra_cnbv_x_entidad_reporta_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="php_cnbvlist.php?order=<?php echo urlencode("notas"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">Notas <?php if (@$_SESSION["csv_lista_negra_cnbv_x_notas_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["csv_lista_negra_cnbv_x_notas_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
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
		$sKey = $row["id_csv_lista_negra_cnbv"];
		$x_id_csv_lista_negra_cnbv = $row["id_csv_lista_negra_cnbv"];
		$x_nombre_completo = $row["nombre_completo"];
		$x_rfc = $row["rfc"];
		$x_fecha_reporte = $row["fecha_reporte"];
		$x_monto = $row["monto"];
		$x_entidad_reporta = $row["entidad_reporta"];
		$x_notas = $row["notas"];
		$x_tonnage = $row["tonnage"];
		$x_grt = $row["grt"];
		$x_vess_flag = $row["vess_flag"];
		$x_vess_owner = $row["vess_owner"];
		$x_remarks = $row["remarks"];
		$x_direccion = '';
		$x_adicionales = '';
		
		
		$sqlDireccion =  "SELECT * FROM csv_add  WHERE id_csv_lista_negra_cnbv = ".$sKey ." ";		
		$rswrkD = phpmkr_query($sqlDireccion,$conn);
		if ($rswrkD) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrkD)) {
				$x_adress = ($datawrk["address"] =='-0- ')?'':$datawrk["address"];
				$x_city = ($datawrk["city_estate"] =='-0- ')?'':$datawrk["city_estate"];
				$x_country = ($datawrk["country"] =='-0- ')?'':$datawrk["country"];
				
					$x_direccion =  "ADDRESS: ".$x_adress."<br>" ;
					$x_direccion .=  " CITY/ESTATE: ".$x_city ."<br>";
					$x_direccion .= "COUNTRY: ".$x_country;
					
				
			}
		}
		
		$sqlDireccion =  "SELECT * FROM csv_alt  WHERE id_csv_lista_negra_cnbv = ".$sKey ." ";		
		$rswrkD = phpmkr_query($sqlDireccion,$conn);
		if ($rswrkD) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrkD)) {
				$x_adress = ($datawrk["alt_type"] =='-0-')?'':$datawrk["alt_type"];
				$x_city = ($datawrk["alt_name"] =='-0- ')?'':$datawrk["alt_name"];
					
					$x_adicionales .=  "TYPE: ".$x_adress." ALT_NAME: ". $x_city ." <br>" ;
					
					
					
				
			}
		}

		
		
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
		<!-- csv_lista_negra_cnbv_id -->
        
        
        
        
        
        
        
        
     
<td><span class="phpmaker"><a href="<?php if ($x_id_csv_lista_negra_cnbv <> "") {echo "php_lista_cnbvedit.php?id_csv_lista_negra_cnbv=".urlencode($x_id_csv_lista_negra_cnbv).""; } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>" target='_blank'>Editar</a></span></td>  
        
		<td><span>
<?php echo $x_id_csv_lista_negra_cnbv; ?>
</span></td>
		<!-- sucursal_id -->
		<td><span>
<?php echo $x_nombre_completo; ?>
</span></td>
		
        <td><span>
<?php echo $x_rfc; ?>
</span></td>
<td><span>
<?php echo $x_fecha_reporte; ?>
</span></td>
		

<td><?php echo $x_monto;?></td>
		<!-- promotor_id -->
		
		<!-- telefono_movil -->
		<td><span>
<?php echo $x_entidad_reporta; ?>
</span></td>
		<!-- telefono_fijo -->
		<td><span>
<?php echo $x_notas; ?>
</span></td>
		<!-- fecha_registro -->


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
<?php include ("footer.php") ?>
<?php

//-------------------------------------------------------------------------------
// Function BasicSearchSQL
// - Build WHERE clause for a keyword

function BasicSearchSQL($Keyword)
{
	$sKeyword = (!get_magic_quotes_gpc()) ? addslashes($Keyword) : $Keyword;
	$BasicSearchSQL = "";
	$BasicSearchSQL.= "`nombre_completo` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`rfc` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`fecha_reporte` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`monto` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`entidad_reporta` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`notas` LIKE '%" . $sKeyword . "%' OR ";
	
	
	
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

		// Field id_csv_lista_negra_cnbv
		if ($sOrder == "id_csv_lista_negra_cnbv") {
			$sSortField = "`id_csv_lista_negra_cnbv`";
			$sLastSort = @$_SESSION["csv_lista_negra_cnbv_x_id_csv_lista_negra_cnbv_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["csv_lista_negra_cnbv_x_id_csv_lista_negra_cnbv_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["csv_lista_negra_cnbv_x_id_csv_lista_negra_cnbv_Sort"] <> "") { $_SESSION["csv_lista_negra_cnbv_x_id_csv_lista_negra_cnbv_Sort"] = "" ; }
		}
		
		// Field id_csv_lista_negra_cnbv
		if ($sOrder == "nombre_completo") {
			$sSortField = "`nombre_completo`";
			$sLastSort = @$_SESSION["csv_lista_negra_cnbv_x_nombre_completo_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["csv_lista_negra_cnbv_x_nombre_completo_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["csv_lista_negra_cnbv_x_nombre_completo_Sort"] <> "") { $_SESSION["csv_lista_negra_cnbv_x_nombre_completo_Sort"] = "" ; }
		}


// Field rfc
		if ($sOrder == "rfc") {
			$sSortField = "`rfc`";
			$sLastSort = @$_SESSION["csv_lista_negra_cnbv_x_rfc_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["csv_lista_negra_cnbv_x_rfc_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["csv_lista_negra_cnbv_x_rfc_Sort"] <> "") { $_SESSION["csv_lista_negra_cnbv_x_rfc_Sort"] = "" ; }
		}
		
		// Field fecha_reporte
		if ($sOrder == "fecha_reporte") {
			$sSortField = "`fecha_reporte`";
			$sLastSort = @$_SESSION["csv_lista_negra_cnbv_x_fecha_reporte_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["csv_lista_negra_cnbv_x_fecha_reporte_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["csv_lista_negra_cnbv_x_fecha_reporte_Sort"] <> "") { $_SESSION["csv_lista_negra_cnbv_x_fecha_reporte_Sort"] = "" ; }
		}
		
		
		// Field monto
		if ($sOrder == "monto") {
			$sSortField = "`monto`";
			$sLastSort = @$_SESSION["csv_lista_negra_cnbv_x_monto_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["csv_lista_negra_cnbv_x_monto_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["csv_lista_negra_cnbv_x_monto_Sort"] <> "") { $_SESSION["csv_lista_negra_cnbv_x_monto_Sort"] = "" ; }
		}
		
		// Field entidad_reporta
		if ($sOrder == "entidad_reporta") {
			$sSortField = "`entidad_reporta`";
			$sLastSort = @$_SESSION["csv_lista_negra_cnbv_x_entidad_reporta_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["csv_lista_negra_cnbv_x_entidad_reporta_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["csv_lista_negra_cnbv_x_entidad_reporta_Sort"] <> "") { $_SESSION["csv_lista_negra_cnbv_x_entidad_reporta_Sort"] = "" ; }
		}
		
		// Field notas
		if ($sOrder == "notas") {
			$sSortField = "`notas`";
			$sLastSort = @$_SESSION["csv_lista_negra_cnbv_x_notas_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["csv_lista_negra_cnbv_x_notas_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["csv_lista_negra_cnbv_x_notas_Sort"] <> "") { $_SESSION["csv_lista_negra_cnbv_x_notas_Sort"] = "" ; }
		}
		
	
		
	
		
		
	
		
		
		
		
		if ($bCtrl) {
			$sOrderBy = @$_SESSION["csv_lista_negra_cnbv_OrderBy"];
			$pos = strpos($sOrderBy, $sSortField . " " . $sLastSort);
			if ($pos === false) {
				if ($sOrderBy <> "") { $sOrderBy .= ", "; }
				$sOrderBy .= $sSortField . " " . $sThisSort;
			}else{
				$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
			}
			$_SESSION["csv_lista_negra_cnbv_OrderBy"] = $sOrderBy;
		}
		else
		{
			$_SESSION["csv_lista_negra_cnbv_OrderBy"] = $sSortField . " " . $sThisSort;
		}
		$_SESSION["csv_lista_negra_cnbv_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["csv_lista_negra_cnbv_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["csv_lista_negra_cnbv_OrderBy"] = $sOrderBy;
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
		$_SESSION["csv_lista_negra_cnbv_REC"] = $nStartRec;
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
			$_SESSION["csv_lista_negra_cnbv_REC"] = $nStartRec;
		}
		else
		{
			$nStartRec = @$_SESSION["csv_lista_negra_cnbv_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["csv_lista_negra_cnbv_REC"] = $nStartRec;
			}
		}
	}
	else
	{
		$nStartRec = @$_SESSION["csv_lista_negra_cnbv_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["csv_lista_negra_cnbv_REC"] = $nStartRec;
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
			$_SESSION["csv_lista_negra_cnbv_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}
		elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["csv_lista_negra_cnbv_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["csv_lista_negra_cnbv_OrderBy"] = $sOrderBy;
			if (@$_SESSION["csv_lista_negra_cnbv_x_id_csv_lista_negra_cnbv_Sort"] <> "") { $_SESSION["csv_lista_negra_cnbv_x_id_csv_lista_negra_cnbv_Sort"] = ""; }
			if (@$_SESSION["csv_lista_negra_cnbv_x_nombre_completo_Sort"] <> "") { $_SESSION["csv_lista_negra_cnbv_x_nombre_completo_Sort"] = ""; }
			if (@$_SESSION["csv_lista_negra_cnbv_x_rfc_Sort"] <> "") { $_SESSION["csv_lista_negra_cnbv_x_rfc_Sort"] = ""; }
			if (@$_SESSION["csv_lista_negra_cnbv_x_fecha_reporte_Sort"] <> "") { $_SESSION["csv_lista_negra_cnbv_x_fecha_reporte_Sort"] = ""; }
			if (@$_SESSION["csv_lista_negra_cnbv_x_monto_Sort"] <> "") { $_SESSION["csv_lista_negra_cnbv_x_monto_Sort"] = ""; }
			if (@$_SESSION["csv_lista_negra_cnbv_x_entidad_reporta_Sort"] <> "") { $_SESSION["csv_lista_negra_cnbv_x_entidad_reporta_Sort"] = ""; }
			if (@$_SESSION["csv_lista_negra_cnbv_x_notas_Sort"] <> "") { $_SESSION["csv_lista_negra_cnbv_x_notas_Sort"] = ""; }
			if (@$_SESSION["csv_lista_negra_cnbv_x_tonnage_Sort"] <> "") { $_SESSION["csv_lista_negra_cnbv_x_tonnage_Sort"] = ""; }
			
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["csv_lista_negra_cnbv_REC"] = $nStartRec;
	}
}
?>
