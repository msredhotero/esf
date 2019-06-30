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
$x_ent_num = Null;
$x_sdn_name = Null;
$x_sdn_type = Null;
$x_program = Null;
$x_title = Null;
$x_call_sign = Null;
$x_vess_type = Null;
$x_tonnage = Null;
$x_grt = Null;
$x_vess_flag = Null;
$x_vess_owner = Null;
$x_remarks = Null;

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
	$_SESSION["csv_sdn_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["csv_sdn_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["csv_sdn_searchwhere"];
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
$sSql = "SELECT * FROM `csv_sdn`";

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
<p><span class="phpmaker">LISTA OFAC</span></p>
<form action="php_ofaclist.php">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="337"><span class="phpmaker">
			<input type="text" name="psearch" size="20">
			<input type="Submit" name="Submit" value="Buscar(*)">
			&nbsp;&nbsp;
			<a href="php_ofaclist.php?cmd=reset">Mostrar todos</a>&nbsp;&nbsp;
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
<table border="0" cellspacing="0" cellpadding="0">
	
    <tr>
		<td><span class="phpmaker"><a href="php_reporte_cnbv_carga_lista_ofac1.php">Importar registros a la lista</a></span></td>
	</tr>
</table>
<form action="php_ofaclist.php" name="ewpagerform" id="ewpagerform">
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
		<a href="php_ofaclist.php?start=<?php echo $PrevStart; ?>"><b>Anterior</b></a>
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
		<a href="php_ofaclist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
					<?php }
					$x += $nDisplayRecs;
					$y += 1;
				} elseif (($x >= ($dx1-$nDisplayRecs*$nRecRange)) && ($x <= ($dx2+$nDisplayRecs*$nRecRange))) {
					if ($x+$nRecRange*$nDisplayRecs < $nTotalRecs) { ?>
		<a href="php_ofaclist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo ($y+$nRecRange-1);?></b></a>
					<?php } else {
						$ny=intval(($nTotalRecs-1)/$nDisplayRecs)+1;
							if ($ny == $y) { ?>
		<a href="php_ofaclist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
							<?php } else { ?>
		<a href="php_ofaclist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo $ny; ?></b></a>
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
		<a href="php_ofaclist.php?start=<?php echo $NextStart; ?>"><b>Siguiente</b></a>
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
    <td> </td>
    <td> </td>
		<td valign="top"><span>
	<a href="php_ofaclist.php?order=<?php echo urlencode("ent_num"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">ent_num<?php if (@$_SESSION["csv_sdn_x_ent_num_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["csv_sdn_x_ent_num_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="php_ofaclist.php?order=<?php echo urlencode("sdn_name"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">Nombre<?php if (@$_SESSION["csv_sdn_x_sdn_name_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["csv_sdn_x_sdn_name_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="php_ofaclist.php?order=<?php echo urlencode("sdn_type"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">sdn_type<?php if (@$_SESSION["csv_sdn_x_sdn_type_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["csv_sdn_x_sdn_type_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
        
        
		<td valign="top"><span>
	<a href="php_ofaclist.php?order=<?php echo urlencode("program"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">program<?php if (@$_SESSION["csv_sdn_x_program_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["csv_sdn_x_program_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="php_ofaclist.php?order=<?php echo urlencode("title"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">title<?php if (@$_SESSION["csv_sdn_x_title_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["csv_sdn_x_title_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="php_ofaclist.php?order=<?php echo urlencode("call_sign"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">call_sign<?php if (@$_SESSION["csv_sdn_x_call_sign_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["csv_sdn_x_call_sign_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
		<td valign="top"><span>
	<a href="php_ofaclist.php?order=<?php echo urlencode("vess_type"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">vess_type <?php if (@$_SESSION["csv_sdn_x_vess_type_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["csv_sdn_x_vess_type_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
        
        <td valign="top"><span>
	<a href="php_ofaclist.php?order=<?php echo urlencode("tonnage"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">tonnage <?php if (@$_SESSION["csv_sdn_x_tonnage_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["csv_sdn_x_tonnage_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
        <td valign="top"><span>
	<a href="php_ofaclist.php?order=<?php echo urlencode("grt"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">grt <?php if (@$_SESSION["csv_sdn_x_grt_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["csv_sdn_x_grt_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
        <td valign="top"><span>
	<a href="php_ofaclist.php?order=<?php echo urlencode("vess_flag"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">vess_flag <?php if (@$_SESSION["csv_sdn_x_vess_flag_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["csv_sdn_x_vess_flag_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
        <td valign="top"><span>
	<a href="php_ofaclist.php?order=<?php echo urlencode("vess_owner"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">vess_owner <?php if (@$_SESSION["csv_sdn_x_vess_owner_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["csv_sdn_x_vess_owner_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
        <td valign="top"><span>
	<a href="php_ofaclist.php?order=<?php echo urlencode("remarks"); ?>" style="color: #FFFFFF;" onMouseDown="ewsort(event, this.href);">remarks <?php if (@$_SESSION["csv_sdn_x_remarks_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["csv_sdn_x_remarks_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></td>
        
        <td valign="top"><span>
	Direcci&oacute;n
		</span></td>
        
        <td valign="top" width="260"><span>
	ALT NAME
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
		$sKey = $row["ent_num"];
		$x_ent_num = $row["ent_num"];
		$x_sdn_name = $row["sdn_name"];
		$x_sdn_type = $row["sdn_type"];
		$x_program = $row["program"];
		$x_title = $row["title"];
		$x_call_sign = $row["call_sign"];
		$x_vess_type = $row["vess_type"];
		$x_tonnage = $row["tonnage"];
		$x_grt = $row["grt"];
		$x_vess_flag = $row["vess_flag"];
		$x_vess_owner = $row["vess_owner"];
		$x_remarks = $row["remarks"];
		$x_direccion = '';
		$x_adicionales = '';
		
		
		$sqlDireccion =  "SELECT * FROM csv_add  WHERE ent_num = ".$sKey ." ";		
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
		
		$sqlDireccion =  "SELECT * FROM csv_alt  WHERE ent_num = ".$sKey ." ";		
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
		<!-- csv_sdn_id -->
        
     <td><span class="phpmaker"><a href="<?php if ((!is_null($sKey))) { echo "php_ofacAdlist.php?Keyword=" . urlencode($sKey); } else { echo "javascript:alert('Invalid Record! Key is null');";  } ?>" target="new">Detalle direcci&oacute;n</a></span></td>
<td><span class="phpmaker"><a href="<?php if ((!is_null($sKey))) { echo "php_ofacAltlist.php?Keyword=" . urlencode($sKey); } else { echo "javascript:alert('Invalid Record! Key is null');"; } ?>" target="new">Detalle alternos</a></span></td>   
        
        
        
        
        
        
        
        
		<td><span>
<?php echo $x_ent_num; ?>
</span></td>
		<!-- sucursal_id -->
		<td><span>
<?php echo $x_sdn_name; ?>
</span></td>
		
        <td><span>
<?php echo $x_sdn_type; ?>
</span></td>
<td><span>
<?php echo $x_program; ?>
</span></td>
		

<td><?php echo $x_title;?></td>
		<!-- promotor_id -->
		
		<!-- telefono_movil -->
		<td><span>
<?php echo $x_call_sign; ?>
</span></td>
		<!-- telefono_fijo -->
		<td><span>
<?php echo $x_vess_type; ?>
</span></td>
		<!-- fecha_registro -->


<td><span>
<?php echo $x_tonnage; ?>
</span></td>


<td><span>
<?php echo $x_grt; ?>
</span></td>


<td><span>
<?php  echo $x_vess_flag; ?>
</span></td>


<td><span>
<?php echo $x_vess_owner; ?>
</span></td>


<td><span>
<?php echo $x_remarks; ?>
</span></td>

<td><span>
<?php echo $x_direccion; ?>
</span></td>

<td><span>
<?php echo $x_adicionales; ?>
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
<?php include ("footer.php") ?>
<?php

//-------------------------------------------------------------------------------
// Function BasicSearchSQL
// - Build WHERE clause for a keyword

function BasicSearchSQL($Keyword)
{
	$sKeyword = (!get_magic_quotes_gpc()) ? addslashes($Keyword) : $Keyword;
	$BasicSearchSQL = "";
	$BasicSearchSQL.= "`sdn_name` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`sdn_type` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`program` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`title` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`call_sign` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`vess_type` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`tonnage` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`grt` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`vess_flag` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`vess_owner` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`remarks` LIKE '%" . $sKeyword . "%' OR ";
	
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

		// Field ent_num
		if ($sOrder == "ent_num") {
			$sSortField = "`ent_num`";
			$sLastSort = @$_SESSION["csv_sdn_x_ent_num_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["csv_sdn_x_ent_num_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["csv_sdn_x_ent_num_Sort"] <> "") { $_SESSION["csv_sdn_x_ent_num_Sort"] = "" ; }
		}
		
		// Field ent_num
		if ($sOrder == "sdn_name") {
			$sSortField = "`sdn_name`";
			$sLastSort = @$_SESSION["csv_sdn_x_sdn_name_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["csv_sdn_x_sdn_name_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["csv_sdn_x_sdn_name_Sort"] <> "") { $_SESSION["csv_sdn_x_sdn_name_Sort"] = "" ; }
		}


// Field sdn_type
		if ($sOrder == "sdn_type") {
			$sSortField = "`sdn_type`";
			$sLastSort = @$_SESSION["csv_sdn_x_sdn_type_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["csv_sdn_x_sdn_type_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["csv_sdn_x_sdn_type_Sort"] <> "") { $_SESSION["csv_sdn_x_sdn_type_Sort"] = "" ; }
		}
		
		// Field program
		if ($sOrder == "program") {
			$sSortField = "`program`";
			$sLastSort = @$_SESSION["csv_sdn_x_program_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["csv_sdn_x_program_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["csv_sdn_x_program_Sort"] <> "") { $_SESSION["csv_sdn_x_program_Sort"] = "" ; }
		}
		
		
		// Field title
		if ($sOrder == "title") {
			$sSortField = "`title`";
			$sLastSort = @$_SESSION["csv_sdn_x_title_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["csv_sdn_x_title_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["csv_sdn_x_title_Sort"] <> "") { $_SESSION["csv_sdn_x_title_Sort"] = "" ; }
		}
		
		// Field call_sign
		if ($sOrder == "call_sign") {
			$sSortField = "`call_sign`";
			$sLastSort = @$_SESSION["csv_sdn_x_call_sign_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["csv_sdn_x_call_sign_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["csv_sdn_x_call_sign_Sort"] <> "") { $_SESSION["csv_sdn_x_call_sign_Sort"] = "" ; }
		}
		
		// Field vess_type
		if ($sOrder == "vess_type") {
			$sSortField = "`vess_type`";
			$sLastSort = @$_SESSION["csv_sdn_x_vess_type_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["csv_sdn_x_vess_type_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["csv_sdn_x_vess_type_Sort"] <> "") { $_SESSION["csv_sdn_x_vess_type_Sort"] = "" ; }
		}
		
		// Field tonnage
		if ($sOrder == "tonnage") {
			$sSortField = "`tonnage`";
			$sLastSort = @$_SESSION["csv_sdn_x_tonnage_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["csv_sdn_x_tonnage_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["csv_sdn_x_tonnage_Sort"] <> "") { $_SESSION["csv_sdn_x_tonnage_Sort"] = "" ; }
		}
		
		// Field grt
		if ($sOrder == "grt") {
			$sSortField = "`grt`";
			$sLastSort = @$_SESSION["csv_sdn_x_grt_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["csv_sdn_x_grt_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["csv_sdn_x_grt_Sort"] <> "") { $_SESSION["csv_sdn_x_grt_Sort"] = "" ; }
		}
		
		
		// Field vess_flag
		if ($sOrder == "vess_flag") {
			$sSortField = "`vess_flag`";
			$sLastSort = @$_SESSION["csv_sdn_x_vess_flag_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["csv_sdn_x_vess_flag_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["csv_sdn_x_vess_flag_Sort"] <> "") { $_SESSION["csv_sdn_x_vess_flag_Sort"] = "" ; }
		}
		
		// Field vess_owner
		if ($sOrder == "vess_owner") {
			$sSortField = "`vess_owner`";
			$sLastSort = @$_SESSION["csv_sdn_x_vess_owner_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["csv_sdn_x_vess_owner_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["csv_sdn_x_vess_owner_Sort"] <> "") { $_SESSION["csv_sdn_x_vess_owner_Sort"] = "" ; }
		}
		
		// Field remarks
		if ($sOrder == "remarks") {
			$sSortField = "`remarks`";
			$sLastSort = @$_SESSION["csv_sdn_x_remarks_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else { $sThisSort = "ASC"; }
			$_SESSION["csv_sdn_x_remarks_Sort"] = $sThisSort;
		}
		else
		{
			if (!($bCtrl) && @$_SESSION["csv_sdn_x_remarks_Sort"] <> "") { $_SESSION["csv_sdn_x_remarks_Sort"] = "" ; }
		}
		
		if ($bCtrl) {
			$sOrderBy = @$_SESSION["csv_sdn_OrderBy"];
			$pos = strpos($sOrderBy, $sSortField . " " . $sLastSort);
			if ($pos === false) {
				if ($sOrderBy <> "") { $sOrderBy .= ", "; }
				$sOrderBy .= $sSortField . " " . $sThisSort;
			}else{
				$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
			}
			$_SESSION["csv_sdn_OrderBy"] = $sOrderBy;
		}
		else
		{
			$_SESSION["csv_sdn_OrderBy"] = $sSortField . " " . $sThisSort;
		}
		$_SESSION["csv_sdn_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["csv_sdn_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["csv_sdn_OrderBy"] = $sOrderBy;
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
		$_SESSION["csv_sdn_REC"] = $nStartRec;
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
			$_SESSION["csv_sdn_REC"] = $nStartRec;
		}
		else
		{
			$nStartRec = @$_SESSION["csv_sdn_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["csv_sdn_REC"] = $nStartRec;
			}
		}
	}
	else
	{
		$nStartRec = @$_SESSION["csv_sdn_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["csv_sdn_REC"] = $nStartRec;
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
			$_SESSION["csv_sdn_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}
		elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["csv_sdn_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["csv_sdn_OrderBy"] = $sOrderBy;
			if (@$_SESSION["csv_sdn_x_ent_num_Sort"] <> "") { $_SESSION["csv_sdn_x_ent_num_Sort"] = ""; }
			if (@$_SESSION["csv_sdn_x_sdn_name_Sort"] <> "") { $_SESSION["csv_sdn_x_sdn_name_Sort"] = ""; }
			if (@$_SESSION["csv_sdn_x_sdn_type_Sort"] <> "") { $_SESSION["csv_sdn_x_sdn_type_Sort"] = ""; }
			if (@$_SESSION["csv_sdn_x_program_Sort"] <> "") { $_SESSION["csv_sdn_x_program_Sort"] = ""; }
			if (@$_SESSION["csv_sdn_x_title_Sort"] <> "") { $_SESSION["csv_sdn_x_title_Sort"] = ""; }
			if (@$_SESSION["csv_sdn_x_call_sign_Sort"] <> "") { $_SESSION["csv_sdn_x_call_sign_Sort"] = ""; }
			if (@$_SESSION["csv_sdn_x_vess_type_Sort"] <> "") { $_SESSION["csv_sdn_x_vess_type_Sort"] = ""; }
			if (@$_SESSION["csv_sdn_x_tonnage_Sort"] <> "") { $_SESSION["csv_sdn_x_tonnage_Sort"] = ""; }
			if (@$_SESSION["csv_sdn_x_grt_Sort"] <> "") { $_SESSION["csv_sdn_x_grt_Sort"] = ""; }
			if (@$_SESSION["csv_sdn_x_vess_flag_Sort"] <> "") { $_SESSION["csv_sdn_x_vess_flag_Sort"] = ""; }
			if (@$_SESSION["csv_sdn_x_vess_owner_Sort"] <> "") { $_SESSION["csv_sdn_x_vess_owner_Sort"] = ""; }
			if (@$_SESSION["csv_sdn_x_remarks_Sort"] <> "") { $_SESSION["csv_sdn_x_remarks_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["csv_sdn_REC"] = $nStartRec;
	}
}
?>
