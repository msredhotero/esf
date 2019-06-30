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
if (@$_SESSION["php_project_esf_status"] <> "login") {
	header("Location:  login.php");
	exit();
}
?>
<?php

// Initialize common variables
$x_sucursal_id = Null;
$x_nombre = Null;
$x_entidad_id = Null;
$x_calle = Null;
$x_colonia = Null;
$x_ciudad = Null;
$x_codigo_postal = Null;
$x_lada = Null;
$x_telefono = Null;
$x_fax = Null;
$x_contacto = Null;
$x_contacto_email = Null;
$x_sucursal_dependiente_id = Null;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=documentos_solicitudes.xls');
}
if ($sExport == "word") {
	header('Content-Type: application/vnd.ms-word');
	header('Content-Disposition: attachment; filename=documentos_solicitudes.doc');
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
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

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
	$_SESSION["sucursal_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["sucursal_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["sucursal_searchwhere"];
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


$sqlDia =  "SELECT DAYNAME( CURDATE( ) ) as dia";
$rsDia = phpmkr_query($sqlDia,$conn)or die ("Error al seleccionar el dia".phpmkr_error()."sql;".$sqlDia);
$rowDia = phpmkr_fetch_array($rsDia); 
$x_dia = $rowDia["dia"];

$sqlDIA1 = "SELECT DATE_SUB(CURDATE(),INTERVAL 7 DAY) as fecha_inicio";
$rsDIA1 = phpmkr_query($sqlDIA1,$conn)or die("Error al selecionar dia 1".phpmkr_error().$sqlDIA1);
$rowDIA1 = phpmkr_fetch_array($rsDIA1);
$x_fecha_inicio = $rowDIA1["fecha_inicio"];

$sqlDIA1 = "SELECT DATE_SUB(CURDATE(),INTERVAL 1 DAY) as fecha_fin";
$rsDIA1 = phpmkr_query($sqlDIA1,$conn)or die("Error al selecionar dia 1".phpmkr_error().$sqlDIA1);
$rowDIA1 = phpmkr_fetch_array($rsDIA1);
$x_fecha_fin = $rowDIA1["fecha_fin"];

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
		 
// Build SQL
$sSql = "SELECT solicitud.*,sucursal.nombre as suc FROM `solicitud` join promotor on promotor.promotor_id = solicitud.promotor_id  join sucursal on promotor.sucursal_id = sucursal.sucursal_id where ";
$sSql .= "  solicitud.fecha_preanalisis >= \"$x_fecha_inicio\" and solicitud.fecha_preanalisis <= \"$x_fecha_fin\" and solicitud.promotor_id in ($x_promotores) order by promotor.sucursal_id,fecha_preanalisis  asc";
		 
	 }else{

// Build SQL
$sSql = "SELECT solicitud.*,sucursal.nombre as suc FROM `solicitud` join promotor on promotor.promotor_id = solicitud.promotor_id  join sucursal on promotor.sucursal_id = sucursal.sucursal_id where ";
$sSql .= "  solicitud.fecha_preanalisis >= \"$x_fecha_inicio\" and solicitud.fecha_preanalisis <= \"$x_fecha_fin\" order by promotor.sucursal_id,fecha_preanalisis  asc";
	 }
#echo $sSql;
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
<?php if ($sExport == "") { ?>
<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<?php } ?>
<?php if ($sExport == "") { ?>
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
}

//-->
</script>
<?php } ?>
<?php

// Set up Record Set
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
$nTotalRecs = phpmkr_num_rows($rs);
if ($nDisplayRecs <= 0) { // Display All Records
	$nDisplayRecs = $nTotalRecs;
}
$nStartRec = 1;
SetUpStartRec(); // Set Up Start Record Position
?>
<p><span class="phpmaker">Listado de solicitudes que pasaron a Pre-an&aacute;lisis 
<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="php_listado_documetos_solictudes.php?export=excel">Exportar a Excel</a>
&nbsp;&nbsp;
<?php } ?>
</span></p>
<?php if ($sExport == "") { ?>
<?php } ?>
<br />
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
<form action="php_sucursallist.php" name="ewpagerform" id="ewpagerform">
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
		<a href="php_sucursallist.php?start=<?php echo $PrevStart; ?>"><b>Anteriro</b></a>
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
		<a href="php_sucursallist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
					<?php }
					$x += $nDisplayRecs;
					$y += 1;
				} elseif (($x >= ($dx1-$nDisplayRecs*$nRecRange)) && ($x <= ($dx2+$nDisplayRecs*$nRecRange))) {
					if ($x+$nRecRange*$nDisplayRecs < $nTotalRecs) { ?>
		<a href="php_sucursallist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo ($y+$nRecRange-1);?></b></a>
					<?php } else {
						$ny=intval(($nTotalRecs-1)/$nDisplayRecs)+1;
							if ($ny == $y) { ?>
		<a href="php_sucursallist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
							<?php } else { ?>
		<a href="php_sucursallist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo $ny; ?></b></a>
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
		<a href="php_sucursallist.php?start=<?php echo $NextStart; ?>"><b>Siguiente</b></a>
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
<form method="post">
<table width="1049" class="ewTable">
<?php if ($nTotalRecs > 0) { ?>
	<!-- Table header -->
	<tr class="ewTableHeader">

<td width="39">ID</td>
<td width="115">sucursal</td>
		<td width="110" valign="top"><span>
  Fecha pre-an&aacute;lisis</span></td>
		<td width="238" valign="top"><span>
  Nombre
</span></td>
		<td width="188" valign="top">Aval</td>
		<td width="136" valign="top">Documentos Th</td>
		<td width="142" valign="top">Documentos Aval</td>
		
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
if($x_dia == "Friday" || $x_dia != "Friday"){
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
		$sKey = $row["sucursal_id"];
		$x_solicitud_id = $row["solicitud_id"];
		
		//sql cliente 
		$sqCliente = "SELECT * FROM cliente JOIN solicitud_cliente  on solicitud_cliente.cliente_id = cliente.cliente_id where solicitud_cliente.solicitud_id = $x_solicitud_id ";
		$rsC = phpmkr_query($sqCliente,$conn);
		$rosC = phpmkr_fetch_array($rsC);
		$x_cliente = "";
		$x_nombre = $rosC["nombre_completo"];
		$x_apellido_paterno = $rosC["apellido_paterno"];
		$x_apellido_materno = $rosC["apellido_materno"];
		$x_fecha_preanalisis = $rosC["fecha_preanalisis"];
		$x_cliente = $x_nombre." ".$x_apellido_paterno." ".$x_apellido_materno;
		echo "<br>".$x_cliente;
		$x_cliente_id = $rosC["cliente_id"];
		$x_aval = "";
		$sqlA = "SELEcT * FROM datos_aval WHERE solicitud_id = $x_solicitud_id";
		echo $sqlA;
		$rsA = phpmkr_query($sqlA,$conn)or die("erro aval".phpmkr_error().$sqlA);
		$rowA = phpmkr_fetch_array($rsA);
		$x_nombre_a = $rowA["nombre_completo"];
		$x_apellido_paterno_a = $rowA["apellido_paterno"];
		$x_apellido_materno_a = $rowA["apellido_materno"];
		$x_fecha_preanalisis = $row["fecha_preanalisis"];
		$x_aval = $x_nombre_a." ".$x_apellido_paterno_a." ".$x_apellido_materno_a;
		
		$x_sucursal_id = $row["sucursal_id"];
		$x_sucursal = $row["suc"];
		$x_ciudad = $row["ciudad"];
		$x_codigo_postal = $row["codigo_postal"];
		$x_lada = $row["lada"];
		$x_telefono = $row["telefono"];
		$x_fax = $row["fax"];
		$x_contacto = $row["contacto"];
		$x_contacto_email = $row["contacto_email"];
		$x_sucursal_dependiente_id = $row["sucursal_dependiente_id"];
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>

<td align="left"><?php echo $x_solicitud_id; ?></td>
<td align="left"><span>
<?php echo $x_sucursal; ?>
</span></td>
		<!-- sucursal_id -->
		<td align="left"><span>
<?php echo $x_fecha_preanalisis; ?>
</span></td>
		<!-- nombre -->
		<td align="left"><span>
<?php echo $x_cliente; ?>
</span></td>
		<!-- entidad_id -->
		<td align="left"><span>

<?php echo $x_aval; ?>
</span></td>
		<!-- calle -->
		<td><span>
<?php echo $x_calle; ?>
</span></td>
		<!-- colonia -->
		<td><span>
<?php echo $x_colonia; ?>
</span></td>
		<!-- ciudad -->
		
	</tr>
<?php
	}
}
}// el dia es viernes
?>
</table>
<?php if ($sExport == "") { ?>
<?php if ($nRecActual > 0) { ?>
<p>&nbsp;</p>
<?php } ?>
<?php } ?>
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
	$BasicSearchSQL.= "`nombre` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`calle` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`colonia` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`ciudad` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`codigo_postal` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`lada` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`telefono` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`fax` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`contacto` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`contacto_email` LIKE '%" . $sKeyword . "%' OR ";
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

		// Field sucursal_id
		if ($sOrder == "sucursal_id") {
			$sSortField = "`sucursal_id`";
			$sLastSort = @$_SESSION["sucursal_x_sucursal_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["sucursal_x_sucursal_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["sucursal_x_sucursal_id_Sort"] <> "") { @$_SESSION["sucursal_x_sucursal_id_Sort"] = ""; }
		}

		// Field nombre
		if ($sOrder == "nombre") {
			$sSortField = "`nombre`";
			$sLastSort = @$_SESSION["sucursal_x_nombre_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["sucursal_x_nombre_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["sucursal_x_nombre_Sort"] <> "") { @$_SESSION["sucursal_x_nombre_Sort"] = ""; }
		}

		// Field entidad_id
		if ($sOrder == "entidad_id") {
			$sSortField = "`entidad_id`";
			$sLastSort = @$_SESSION["sucursal_x_entidad_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["sucursal_x_entidad_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["sucursal_x_entidad_id_Sort"] <> "") { @$_SESSION["sucursal_x_entidad_id_Sort"] = ""; }
		}

		// Field calle
		if ($sOrder == "calle") {
			$sSortField = "`calle`";
			$sLastSort = @$_SESSION["sucursal_x_calle_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["sucursal_x_calle_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["sucursal_x_calle_Sort"] <> "") { @$_SESSION["sucursal_x_calle_Sort"] = ""; }
		}

		// Field colonia
		if ($sOrder == "colonia") {
			$sSortField = "`colonia`";
			$sLastSort = @$_SESSION["sucursal_x_colonia_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["sucursal_x_colonia_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["sucursal_x_colonia_Sort"] <> "") { @$_SESSION["sucursal_x_colonia_Sort"] = ""; }
		}

		// Field ciudad
		if ($sOrder == "ciudad") {
			$sSortField = "`ciudad`";
			$sLastSort = @$_SESSION["sucursal_x_ciudad_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["sucursal_x_ciudad_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["sucursal_x_ciudad_Sort"] <> "") { @$_SESSION["sucursal_x_ciudad_Sort"] = ""; }
		}

		// Field codigo_postal
		if ($sOrder == "codigo_postal") {
			$sSortField = "`codigo_postal`";
			$sLastSort = @$_SESSION["sucursal_x_codigo_postal_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["sucursal_x_codigo_postal_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["sucursal_x_codigo_postal_Sort"] <> "") { @$_SESSION["sucursal_x_codigo_postal_Sort"] = ""; }
		}

		// Field lada
		if ($sOrder == "lada") {
			$sSortField = "`lada`";
			$sLastSort = @$_SESSION["sucursal_x_lada_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["sucursal_x_lada_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["sucursal_x_lada_Sort"] <> "") { @$_SESSION["sucursal_x_lada_Sort"] = ""; }
		}

		// Field telefono
		if ($sOrder == "telefono") {
			$sSortField = "`telefono`";
			$sLastSort = @$_SESSION["sucursal_x_telefono_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["sucursal_x_telefono_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["sucursal_x_telefono_Sort"] <> "") { @$_SESSION["sucursal_x_telefono_Sort"] = ""; }
		}

		// Field fax
		if ($sOrder == "fax") {
			$sSortField = "`fax`";
			$sLastSort = @$_SESSION["sucursal_x_fax_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["sucursal_x_fax_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["sucursal_x_fax_Sort"] <> "") { @$_SESSION["sucursal_x_fax_Sort"] = ""; }
		}

		// Field contacto
		if ($sOrder == "contacto") {
			$sSortField = "`contacto`";
			$sLastSort = @$_SESSION["sucursal_x_contacto_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["sucursal_x_contacto_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["sucursal_x_contacto_Sort"] <> "") { @$_SESSION["sucursal_x_contacto_Sort"] = ""; }
		}

		// Field contacto_email
		if ($sOrder == "contacto_email") {
			$sSortField = "`contacto_email`";
			$sLastSort = @$_SESSION["sucursal_x_contacto_email_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["sucursal_x_contacto_email_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["sucursal_x_contacto_email_Sort"] <> "") { @$_SESSION["sucursal_x_contacto_email_Sort"] = ""; }
		}

		// Field sucursal_dependiente_id
		if ($sOrder == "sucursal_dependiente_id") {
			$sSortField = "`sucursal_dependiente_id`";
			$sLastSort = @$_SESSION["sucursal_x_sucursal_dependiente_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["sucursal_x_sucursal_dependiente_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["sucursal_x_sucursal_dependiente_id_Sort"] <> "") { @$_SESSION["sucursal_x_sucursal_dependiente_id_Sort"] = ""; }
		}
		$_SESSION["sucursal_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["sucursal_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["sucursal_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["sucursal_OrderBy"] = $sOrderBy;
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
		$_SESSION["sucursal_REC"] = $nStartRec;
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
			$_SESSION["sucursal_REC"] = $nStartRec;
		}
		else
		{
			$nStartRec = @$_SESSION["sucursal_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["sucursal_REC"] = $nStartRec;
			}
		}
	}
	else
	{
		$nStartRec = @$_SESSION["sucursal_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["sucursal_REC"] = $nStartRec;
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
			$_SESSION["sucursal_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}
		elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["sucursal_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["sucursal_OrderBy"] = $sOrderBy;
			if (@$_SESSION["sucursal_x_sucursal_id_Sort"] <> "") { $_SESSION["sucursal_x_sucursal_id_Sort"] = ""; }
			if (@$_SESSION["sucursal_x_nombre_Sort"] <> "") { $_SESSION["sucursal_x_nombre_Sort"] = ""; }
			if (@$_SESSION["sucursal_x_entidad_id_Sort"] <> "") { $_SESSION["sucursal_x_entidad_id_Sort"] = ""; }
			if (@$_SESSION["sucursal_x_calle_Sort"] <> "") { $_SESSION["sucursal_x_calle_Sort"] = ""; }
			if (@$_SESSION["sucursal_x_colonia_Sort"] <> "") { $_SESSION["sucursal_x_colonia_Sort"] = ""; }
			if (@$_SESSION["sucursal_x_ciudad_Sort"] <> "") { $_SESSION["sucursal_x_ciudad_Sort"] = ""; }
			if (@$_SESSION["sucursal_x_codigo_postal_Sort"] <> "") { $_SESSION["sucursal_x_codigo_postal_Sort"] = ""; }
			if (@$_SESSION["sucursal_x_lada_Sort"] <> "") { $_SESSION["sucursal_x_lada_Sort"] = ""; }
			if (@$_SESSION["sucursal_x_telefono_Sort"] <> "") { $_SESSION["sucursal_x_telefono_Sort"] = ""; }
			if (@$_SESSION["sucursal_x_fax_Sort"] <> "") { $_SESSION["sucursal_x_fax_Sort"] = ""; }
			if (@$_SESSION["sucursal_x_contacto_Sort"] <> "") { $_SESSION["sucursal_x_contacto_Sort"] = ""; }
			if (@$_SESSION["sucursal_x_contacto_email_Sort"] <> "") { $_SESSION["sucursal_x_contacto_email_Sort"] = ""; }
			if (@$_SESSION["sucursal_x_sucursal_dependiente_id_Sort"] <> "") { $_SESSION["sucursal_x_sucursal_dependiente_id_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["sucursal_REC"] = $nStartRec;
	}
}
?>
