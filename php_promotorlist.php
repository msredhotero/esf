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
$x_promotor_id = Null; 
$ox_promotor_id = Null;
$x_usuario_id = Null; 
$ox_usuario_id = Null;
$x_nombre_completo = Null; 
$ox_nombre_completo = Null;
$x_comision = Null; 
$ox_comision = Null;
$x_direccion_id = Null; 
$ox_direccion_id = Null;
$x_telefono_oficina = Null; 
$ox_telefono_oficina = Null;
$x_telefono_particular = Null; 
$ox_telefono_particular = Null;
$x_telefono_movil = Null; 
$ox_telefono_movil = Null;
$x_promotor_status_id = Null; 
$ox_promotor_status_id = Null;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=promotor.xls');
}
if ($sExport == "word") {
	header('Content-Type: application/vnd.ms-word');
	header('Content-Disposition: attachment; filename=promotor.doc');
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


$x_sucursal_id = $_POST["x_sucursal_id"];


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
	$_SESSION["promotor_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["promotor_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["promotor_searchwhere"];
}

// Build SQL
$sSql = "SELECT * FROM `promotor`";

// Load Default Filter
$sDefaultFilter = "";
$sGroupBy = "";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = "";

// Build WHERE condition
$sDbWhere = "";

if(!empty($x_sucursal_id)){
	$sDbWhere = "(sucursal_id = $x_sucursal_id) AND ";
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
$sWhere = "";
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

// Set Up Sorting Order
$sOrderBy = "";
SetUpSortOrder();
if ($sOrderBy != "") {
	$sSql .= " ORDER BY " . $sOrderBy;
}

//echo $sSql; // Uncomment to show SQL for debugging
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
<p><span class="phpmaker">PROMOTORES
<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="php_promotorlist.php?export=excel">Exportar a Excel</a> <?php } ?>
</span></p>
<?php if ($sExport == "") { ?>
<form action="php_promotorlist.php">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><span class="phpmaker">
			<input type="text" name="psearch" size="20">
			<input type="Submit" name="Submit" value="Buscar &nbsp;(*)">
			&nbsp;&nbsp; <a href="php_promotorlist.php?cmd=reset">Mostrar todos</a>&nbsp;&nbsp;
		</span></td>
	</tr>
	<tr><td><span class="phpmaker"><input type="radio" name="psearchtype" value="" checked>
	Frase Exacta&nbsp;
	<input type="radio" name="psearchtype" value="AND">
	Todas las palabras
	<input type="radio" name="psearchtype" value="OR">
	Cualquier palabra</span></td>
	</tr>
</table>
</form>
<?php } ?>
<?php if ($sExport == "") { ?>
<br /><br /><br />

<form name="filtros" action="php_promotorlist.php" method="post">

<table width="785" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="70"><strong>Sucursal:</strong></td>
    <td width="14">&nbsp;</td>
    <td width="701"><span class="phpmaker">
      <?php
$x_empresa_dependiente_idList = "<select name=\"x_sucursal_id\" class=\"texto_normal\">";
$x_empresa_dependiente_idList .= "<option value=''>Todas</option>";
$sSqlWrk = "SELECT `sucursal_id`, `nombre` FROM `sucursal`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_empresa_dependiente_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["sucursal_id"] == @$x_sucursal_id) {
			$x_empresa_dependiente_idList .= "' selected";
		}
		$x_empresa_dependiente_idList .= ">" . $datawrk["nombre"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_empresa_dependiente_idList .= "</select>";
echo $x_empresa_dependiente_idList;
?>
    </span></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <td><span class="phpmaker">
      <input type="submit" name="Submit2" value="Filtrar&nbsp;(*)" />
    </span></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
</table>
</form>







<table border="0" cellspacing="0" cellpadding="0">
  <tr>
		<td><span class="phpmaker"><a href="php_promotoradd.php">Agregar nuevo promotor</a></span></td>
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
<form action="php_promotorlist.php" name="ewpagerform" id="ewpagerform">
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
		if ($nStartRec == 1) {
			$isPrev = False;
		} else {
			$isPrev = True;
			$PrevStart = $nStartRec - $nDisplayRecs;
			if ($PrevStart < 1) { $PrevStart = 1; } ?>
		<a href="php_promotorlist.php?start=<?php echo $PrevStart; ?>"><b>Anterior</b></a>
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
		<a href="php_promotorlist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
					<?php }
					$x += $nDisplayRecs;
					$y += 1;
				} elseif (($x >= ($dx1-$nDisplayRecs*$nRecRange)) && ($x <= ($dx2+$nDisplayRecs*$nRecRange))) {
					if ($x+$nRecRange*$nDisplayRecs < $nTotalRecs) { ?>
		<a href="php_promotorlist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo ($y+$nRecRange-1);?></b></a>
					<?php } else {
						$ny=intval(($nTotalRecs-1)/$nDisplayRecs)+1;
							if ($ny == $y) { ?>
		<a href="php_promotorlist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
							<?php } else { ?>
		<a href="php_promotorlist.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo $ny; ?></b></a>
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
		<a href="php_promotorlist.php?start=<?php echo $NextStart; ?>"><b>Siguiente</b></a>
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
<td><input type="checkbox" name="checkall" class="phpmaker" onClick="EW_selectKey(this);"></td>
<?php } ?>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
No
<?php }else{ ?>
	<a href="php_promotorlist.php?order=<?php echo urlencode("promotor_id"); ?>">No<?php if (@$_SESSION["promotor_x_promotor_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["promotor_x_promotor_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top">Sucursal</td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Tipo
<?php }else{ ?>
	<a href="php_promotorlist.php?order=<?php echo urlencode("promotor_tipo_id"); ?>">Tipo<?php if (@$_SESSION["promotor_x_promotor_tipo_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["promotor_x_promotor_tipo_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Status
<?php }else{ ?>
	<a href="php_promotorlist.php?order=<?php echo urlencode("promotor_status_id"); ?>">Status<?php if (@$_SESSION["promotor_x_promotor_status_id_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["promotor_x_promotor_status_id_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>		
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Nombre completo
<?php }else{ ?>
	<a href="php_promotorlist.php?order=<?php echo urlencode("nombre_completo"); ?>">Nombre completo&nbsp;(*)<?php if (@$_SESSION["promotor_x_nombre_completo_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["promotor_x_nombre_completo_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Teléfono de oficina
<?php }else{ ?>
	<a href="php_promotorlist.php?order=<?php echo urlencode("telefono_oficina"); ?>">Teléfono de oficina&nbsp;(*)<?php if (@$_SESSION["promotor_x_telefono_oficina_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["promotor_x_telefono_oficina_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Teléfono particular
<?php }else{ ?>
	<a href="php_promotorlist.php?order=<?php echo urlencode("telefono_particular"); ?>">Teléfono particular&nbsp;(*)<?php if (@$_SESSION["promotor_x_telefono_particular_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["promotor_x_telefono_particular_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Teléfono móvil
<?php }else{ ?>
	<a href="php_promotorlist.php?order=<?php echo urlencode("telefono_movil"); ?>">Teléfono móvil&nbsp;(*)<?php if (@$_SESSION["promotor_x_telefono_movil_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["promotor_x_telefono_movil_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
comision
<?php }else{ ?>
	<a href="php_promotorlist.php?order=<?php echo urlencode("comision"); ?>">comision<?php if (@$_SESSION["promotor_x_comision_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["promotor_x_comision_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
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
		$x_sucursal_id = $row["sucursal_id"];
		$x_promotor_id = $row["promotor_id"];		
		$x_usuario_id = $row["usuario_id"];
		$x_nombre_completo = $row["nombre_completo"];
		$x_comision = $row["comision"];
		$x_direccion_id = $row["direccion_id"];
		$x_telefono_oficina = $row["telefono_oficina"];
		$x_telefono_particular = $row["telefono_particular"];
		$x_telefono_movil = $row["telefono_movil"];
		$x_promotor_tipo_id = $row["promotor_tipo_id"];
		$x_promotor_status_id = $row["promotor_status_id"];		
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>
<?php if ($sExport == "") { ?>
<td><span class="phpmaker"><a href="<?php if ($x_promotor_id <> "") {echo "php_promotoredit.php?promotor_id=" . urlencode($x_promotor_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Editar</a></span></td>
<td><span class="phpmaker"><input type="checkbox" name="key_d[]" value="<?php echo $x_promotor_id; ?>" class="phpmaker" onclick='ew_clickmultidelete(this);'>
  Eliminar</span></td>
<?php } ?>
		<!-- promotor_id -->
		<td><span>
<?php echo $x_promotor_id; ?>
</span></td>
		<td><?php
if ((!is_null($x_sucursal_id)) && ($x_sucursal_id <> "")) {
	$sSqlWrk = "SELECT `nombre` FROM `sucursal`";
	$sTmp = $x_sucursal_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `sucursal_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["nombre"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_promotor_tipo_id = $x_promotor_tipo_id; // Backup Original Value
$x_promotor_tipo_id = $sTmp;
?>
          <?php echo $x_promotor_tipo_id; ?>
          <?php $x_promotor_tipo_id = $ox_promotor_tipo_id; // Restore Original Value ?></td>
		<!-- promotor_status_id -->
		<td><span>
<?php
if ((!is_null($x_promotor_tipo_id)) && ($x_promotor_tipo_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `promotor_tipo`";
	$sTmp = $x_promotor_tipo_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `promotor_tipo_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_promotor_tipo_id = $x_promotor_tipo_id; // Backup Original Value
$x_promotor_tipo_id = $sTmp;
?>
<?php echo $x_promotor_tipo_id; ?>
<?php $x_promotor_tipo_id = $ox_promotor_tipo_id; // Restore Original Value ?>
</span></td>
		<!-- promotor_status_id -->
		<td><span>
<?php
if ((!is_null($x_promotor_status_id)) && ($x_promotor_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `promotor_status`";
	$sTmp = $x_promotor_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `promotor_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_promotor_status_id = $x_promotor_status_id; // Backup Original Value
$x_promotor_status_id = $sTmp;
?>
<?php echo $x_promotor_status_id; ?>
<?php $x_promotor_status_id = $ox_promotor_status_id; // Restore Original Value ?>
</span></td>
		<!-- nombre_completo -->
		<td><span>
<?php echo $x_nombre_completo; ?>
</span></td>
		<!-- telefono_oficina -->
		<td><span>
<?php echo $x_telefono_oficina; ?>
</span></td>
		<!-- telefono_particular -->
		<td><span>
<?php echo $x_telefono_particular; ?>
</span></td>
		<!-- telefono_movil -->
		<td><span>
<?php echo $x_telefono_movil; ?>
</span></td>
		<!-- comision -->
		<td><span>
<?php echo (is_numeric($x_comision)) ? FormatPercent($x_comision,2,0,0,0) : $x_comision; ?>
</span></td>
	</tr>
<?php
	}
}
?>
</table>
<?php if ($sExport == "") { ?>
<?php if ($nRecActual > 0) { ?>
<p><input type="button" name="btndelete" value="ELIMINAR SELECCIONADOS" onClick="if (!EW_selected(this)) alert('No records selected'); else {this.form.action='php_promotordelete.php';this.form.encoding='application/x-www-form-urlencoded';this.form.submit();}">
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
	$BasicSearchSQL.= "`nombre_completo` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`telefono_oficina` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`telefono_particular` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`telefono_movil` LIKE '%" . $sKeyword . "%' OR ";
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

		// Field promotor_id
		if ($sOrder == "promotor_id") {
			$sSortField = "`promotor_id`";
			$sLastSort = @$_SESSION["promotor_x_promotor_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["promotor_x_promotor_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["promotor_x_promotor_id_Sort"] <> "") { @$_SESSION["promotor_x_promotor_id_Sort"] = ""; }
		}

		// Field usuario_id
		if ($sOrder == "usuario_id") {
			$sSortField = "`usuario_id`";
			$sLastSort = @$_SESSION["promotor_x_usuario_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["promotor_x_usuario_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["promotor_x_usuario_id_Sort"] <> "") { @$_SESSION["promotor_x_usuario_id_Sort"] = ""; }
		}

		// Field nombre_completo
		if ($sOrder == "nombre_completo") {
			$sSortField = "`nombre_completo`";
			$sLastSort = @$_SESSION["promotor_x_nombre_completo_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["promotor_x_nombre_completo_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["promotor_x_nombre_completo_Sort"] <> "") { @$_SESSION["promotor_x_nombre_completo_Sort"] = ""; }
		}

		// Field comision
		if ($sOrder == "comision") {
			$sSortField = "`comision`";
			$sLastSort = @$_SESSION["promotor_x_comision_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["promotor_x_comision_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["promotor_x_comision_Sort"] <> "") { @$_SESSION["promotor_x_comision_Sort"] = ""; }
		}

		// Field direccion_id
		if ($sOrder == "direccion_id") {
			$sSortField = "`direccion_id`";
			$sLastSort = @$_SESSION["promotor_x_direccion_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["promotor_x_direccion_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["promotor_x_direccion_id_Sort"] <> "") { @$_SESSION["promotor_x_direccion_id_Sort"] = ""; }
		}

		// Field telefono_oficina
		if ($sOrder == "telefono_oficina") {
			$sSortField = "`telefono_oficina`";
			$sLastSort = @$_SESSION["promotor_x_telefono_oficina_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["promotor_x_telefono_oficina_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["promotor_x_telefono_oficina_Sort"] <> "") { @$_SESSION["promotor_x_telefono_oficina_Sort"] = ""; }
		}

		// Field telefono_particular
		if ($sOrder == "telefono_particular") {
			$sSortField = "`telefono_particular`";
			$sLastSort = @$_SESSION["promotor_x_telefono_particular_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["promotor_x_telefono_particular_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["promotor_x_telefono_particular_Sort"] <> "") { @$_SESSION["promotor_x_telefono_particular_Sort"] = ""; }
		}

		// Field telefono_movil
		if ($sOrder == "telefono_movil") {
			$sSortField = "`telefono_movil`";
			$sLastSort = @$_SESSION["promotor_x_telefono_movil_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["promotor_x_telefono_movil_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["promotor_x_telefono_movil_Sort"] <> "") { @$_SESSION["promotor_x_telefono_movil_Sort"] = ""; }
		}

		// Field promotor_tipo_id
		if ($sOrder == "promotor_tipo_id") {
			$sSortField = "`promotor_tipo_id`";
			$sLastSort = @$_SESSION["promotor_x_promotor_tipo_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["promotor_x_promotor_tipo_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["promotor_x_promotor_tipo_id_Sort"] <> "") { @$_SESSION["promotor_x_promotor_tipo_id_Sort"] = ""; }
		}

		// Field promotor_status_id
		if ($sOrder == "promotor_status_id") {
			$sSortField = "`promotor_status_id`";
			$sLastSort = @$_SESSION["promotor_x_promotor_status_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["promotor_x_promotor_status_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["promotor_x_promotor_status_id_Sort"] <> "") { @$_SESSION["promotor_x_promotor_status_id_Sort"] = ""; }
		}


		
		$_SESSION["promotor_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["promotor_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["promotor_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["promotor_OrderBy"] = $sOrderBy;
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
		$_SESSION["promotor_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["promotor_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["promotor_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["promotor_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["promotor_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["promotor_REC"] = $nStartRec;
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
			$_SESSION["promotor_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["promotor_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["promotor_OrderBy"] = $sOrderBy;
			if (@$_SESSION["promotor_x_promotor_id_Sort"] <> "") { $_SESSION["promotor_x_promotor_id_Sort"] = ""; }
			if (@$_SESSION["promotor_x_usuario_id_Sort"] <> "") { $_SESSION["promotor_x_usuario_id_Sort"] = ""; }
			if (@$_SESSION["promotor_x_nombre_completo_Sort"] <> "") { $_SESSION["promotor_x_nombre_completo_Sort"] = ""; }
			if (@$_SESSION["promotor_x_comision_Sort"] <> "") { $_SESSION["promotor_x_comision_Sort"] = ""; }
			if (@$_SESSION["promotor_x_direccion_id_Sort"] <> "") { $_SESSION["promotor_x_direccion_id_Sort"] = ""; }
			if (@$_SESSION["promotor_x_telefono_oficina_Sort"] <> "") { $_SESSION["promotor_x_telefono_oficina_Sort"] = ""; }
			if (@$_SESSION["promotor_x_telefono_particular_Sort"] <> "") { $_SESSION["promotor_x_telefono_particular_Sort"] = ""; }
			if (@$_SESSION["promotor_x_telefono_movil_Sort"] <> "") { $_SESSION["promotor_x_telefono_movil_Sort"] = ""; }
			if (@$_SESSION["promotor_x_promotor_tipo_id_Sort"] <> "") { $_SESSION["promotor_x_promotor_tipo_id_Sort"] = ""; }
			if (@$_SESSION["promotor_x_promotor_status_id_Sort"] <> "") { $_SESSION["promotor_x_promotor_status_id_Sort"] = ""; }			
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["promotor_REC"] = $nStartRec;
	}
}
?>
