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
$x_cliente_id = Null; 
$ox_cliente_id = Null;
$x_solicitud_id = Null; 
$ox_solicitud_id = Null;
$x_usuario_id = Null; 
$ox_usuario_id = Null;
$x_nombre_completo = Null; 
$ox_nombre_completo = Null;
$x_tipo_negocio = Null; 
$ox_tipo_negocio = Null;
$x_edad = Null; 
$ox_edad = Null;
$x_sexo = Null; 
$ox_sexo = Null;
$x_estado_civil_id = Null; 
$ox_estado_civil_id = Null;
$x_numero_hijos = Null; 
$ox_numero_hijos = Null;
$x_nombre_conyuge = Null; 
$ox_nombre_conyuge = Null;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=cliente_finafim.xls');
}
if ($sExport == "word") {
	header('Content-Type: application/vnd.ms-word');
	header('Content-Disposition: attachment; filename=cliente_finafim.doc');
}
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php



$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];

if($_POST["x_fecha_corte"]){
	$x_fecha_corte = $_POST["x_fecha_corte"];
}else{
	if($_GET["x_fecha_corte"]){
		$x_fecha_corte = $_GET["x_fecha_corte"];
	}else{	
		$x_fecha_corte = $currdate;	
	}
}

$temptime = strtotime(ConvertDateToMysqlFormat($x_fecha_corte));	
$x_mes_actual = intval(strftime('%m',$temptime));
$x_year_actual = strftime('%Y',$temptime);


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
$nDisplayRecs = 2000;
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
	$_SESSION["cliente_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["cliente_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["cliente_searchwhere"];
}

// Build SQL
$sSql = "SELECT cliente.* FROM cliente join solicitud_cliente 
on solicitud_cliente.cliente_id = cliente.cliente_id join solicitud 
on solicitud.solicitud_id = solicitud_cliente.solicitud_id join credito on credito.solicitud_id = solicitud.solicitud_id join fondeo_colocacion on fondeo_colocacion.credito_id = credito.credito_id where fondeo_colocacion.fondeo_credito_id in (7,8) and month(credito.fecha_otrogamiento) = $x_mes_actual and year(credito.fecha_otrogamiento) = $x_year_actual order by credito.credito_id ";

/* todos
$sSql = "SELECT cliente.* FROM cliente join solicitud_cliente 
on solicitud_cliente.cliente_id = cliente.cliente_id join solicitud 
on solicitud.solicitud_id = solicitud_cliente.solicitud_id join credito on credito.solicitud_id = solicitud.solicitud_id join fondeo_colocacion on fondeo_colocacion.credito_id = credito.credito_id where fondeo_colocacion.fondeo_credito_id in (7,8) order by credito.credito_id ";
*/

// Load Default Filter
$sDefaultFilter = "";
$sGroupBy = "";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = "";

// Build WHERE condition

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

<?php if ($sExport == "") { ?>

<link rel="stylesheet" type="text/css" media="all" href="jscalendar/skins/aqua/theme.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-sp.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>

<?php } ?>

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
<p><span class="phpmaker">CLIENTES
<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="php_clientelist_finafim.php?export=excel&x_fecha_corte=<?php echo $x_fecha_corte; ?>">Exportar a Excel</a>
&nbsp;&nbsp;<a href="php_clientelist_finafim.php?export=word">Exportar a Word</a>
<?php } ?>
</span></p>
<?php if ($sExport == "") { ?>
<form action="php_clientelist_finafim.php" method="post">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
	  <td>Fecha de Corte:</td>
	  <td>
<span>
	    <input type="text" name="x_fecha_corte" id="x_fecha_corte" value="<?php echo FormatDateTime($x_fecha_corte,7); ?>">
	    &nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_corte" alt="Calendario" style="cursor:pointer;cursor:hand;">
	    <script type="text/javascript">
		Calendar.setup(
		{
		inputField : "x_fecha_corte", // ID of the input field
		ifFormat : "%d/%m/%Y", // the date format
		button : "cx_fecha_corte" // ID of the button
		}
		);
		</script>
	    </span>	      
      </td>
      <td>&nbsp;&nbsp;<input type="submit" name="filtro" value="Generar" /></td>
	  </tr>
	</table>
</form>
<?php } ?>
<?php if ($sExport == "") { ?>
<!---
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><span class="phpmaker"><a href="php_clienteadd.php">Agregar nuevo Cliente</a></span></td>
	</tr>
</table>
-->
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
<form action="php_clientelist_finafim.php" name="ewpagerform" id="ewpagerform">
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
		<a href="php_clientelist_finafim.php?start=<?php echo $PrevStart; ?>"><b>Anterior</b></a>
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
		<a href="php_clientelist_finafim.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
					<?php }
					$x += $nDisplayRecs;
					$y += 1;
				} elseif (($x >= ($dx1-$nDisplayRecs*$nRecRange)) && ($x <= ($dx2+$nDisplayRecs*$nRecRange))) {
					if ($x+$nRecRange*$nDisplayRecs < $nTotalRecs) { ?>
		<a href="php_clientelist_finafim.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo ($y+$nRecRange-1);?></b></a>
					<?php } else {
						$ny=intval(($nTotalRecs-1)/$nDisplayRecs)+1;
							if ($ny == $y) { ?>
		<a href="php_clientelist_finafim.php?start=<?php echo $x; ?>"><b><?php echo $y; ?></b></a>
							<?php } else { ?>
		<a href="php_clientelist_finafim.php?start=<?php echo $x; ?>"><b><?php echo $y; ?>-<?php echo $ny; ?></b></a>
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
		<a href="php_clientelist_finafim.php?start=<?php echo $NextStart; ?>"><b>Siguiente</b></a>
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
	  <td valign="top">ORG_ID</td>
		<td valign="top"><span> ACRED_ID
</span></td>
		<td valign="top">CURP</td>
		<td valign="top">IFE</td>
		<td valign="top"><span> PRIMER_AP
</span></td>
		<td valign="top">SEGUNDO_AP</td>
		<td valign="top">NOMBRE</td>
		<td valign="top">FECHA_NAC</td>
		<td valign="top">SEXO</td>
		<td valign="top">TEL</td>
		<td valign="top">CVE_EDO_CIVIL</td>
		<td valign="top">EDO_RES</td>
		<td valign="top">MUNICIPIO</td>
		<td valign="top">LOCALIDAD</td>
		<td valign="top">CALLE</td>
		<td valign="top">NUMERO_EXTERIOR</td>
		<td valign="top">NUMERO_INTERIOR</td>
		<td valign="top">COLONIA</td>
		<td valign="top">CP</td>
		<td valign="top">METODOLOGIA</td>
		<td valign="top">NOM_GRUPO</td>
		<td valign="top">ESTUDIOS</td>
		<td valign="top">ACTIVIDAD</td>
		<td valign="top">FECHA_INICIO_ACT_PRODUCTIVA</td>
		<td valign="top">UBICACION_NEGOCIO</td>
		<td valign="top">PERSONAS_TRABAJANDO</td>
		<td valign="top">INGRESO_SEMANAL</td>
		<td valign="top">ROL_EN_HOGAR</td>
		<td valign="top">SUCURSAL</td>
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
		$x_cliente_id = $row["cliente_id"];
		$x_cliente_num = $row["cliente_num"];						
		$x_nombre_completo = $row["nombre_completo"];
		$x_apellido_paterno = $row["apellido_paterno"];
		$x_apellido_materno = $row["apellido_materno"];		
		$x_sexo = $row["sexo"];
		$x_estado_civil_id = $row["estado_civil_id"];
		$x_email = $row["email"];		
		$x_fecha_nac = $row["fecha_nac"];	
		$x_fecha_ini_act_prod = $row["fecha_ini_act_prod"];			
		$x_rol_hogar_id = $row["rol_hogar_id"];			


		$sSqlWrk = "SELECT * FROM negocio Where cliente_id = $x_cliente_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_tipo_inmueble_id = $datawrk["tipo_inmueble_id"];				
		$x_personas_trabajando = $datawrk["personas_trabajando"];				
		@phpmkr_free_result($rswrk);


		

		if(empty($x_ubicacion_negocio_id)){
			$x_ubicacion_negocio = "1";
		}
		if(empty($x_personas_trabajando)){
			$x_personas_trabajando = "0";
		}
		
				
		$sSqlWrk = "SELECT * FROM direccion Where direccion_tipo_id = 1 and cliente_id = $x_cliente_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_telefono = $datawrk["telefono"];
		$x_estado = $datawrk["entidad"];		
		$x_delegacion_id = $datawrk["delegacion_id"];				
		$x_num_ext = $datawrk["numero_exterior"];				
		$x_calle = $datawrk["calle"];						
		$x_colonia = $datawrk["colonia"];				
		$x_codigo_postal = $datawrk["codigo_postal"];				
		@phpmkr_free_result($rswrk);

		if($x_sexo == 1){ //hombre H
			$x_sexo = "2";
		}else{ //mujer M
			$x_sexo = "1";			
		}

$x_estado = "";
$x_delegacion = "";
		
		if(!empty($x_delegacion_id)){

			$sSqlWrk = "SELECT entidad_id, cve_municipio FROM delegacion Where delegacion_id = $x_delegacion_id";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_entidad_id = $datawrk["entidad_id"];
			$x_delegacion = $datawrk["cve_municipio"];			
			@phpmkr_free_result($rswrk);
			
			
			switch ($x_entidad_id)
			{
				case "1": 
					$x_estado = "DF";
					break;
				case "2": 
					$x_estado = "MEX";
					break;
				case "3": 
					$x_estado = "PUE";
					break;
				case "6": 
					$x_estado = "HGO";
					break;
				case "7": 
					$x_estado = "COL";
					break;

				default:
					$x_estado = "SIN EDO";
					break;
			}

		}else{
			$x_estado = "SIN EDO";
		}

	
		if(!empty($x_estado_civil_id)){
			switch ($x_estado_civil_id)
			{
				case 1: // soltero
					$x_estado_civil_id = "1";
					break;
				case 2: // casado 
					$x_estado_civil_id = "2";
					break;
				case 3: // union libre 
					$x_estado_civil_id = "5";
					break;
				case 4: // divorciado
					$x_estado_civil_id = "4";
					break;
				case 6: // viudo
					$x_estado_civil_id = "3";
					break;
				case 7: // separado
					$x_estado_civil_id = "6";
					break;
				default: // soltero / soltero
					$x_estado_civil_id = "7";
			}
		}else{
			$x_estado_civil_id = "7";
		}


		$sSqlWrk = "SELECT max(solicitud_id) as sol_cli FROM solicitud_cliente Where cliente_id = $x_cliente_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_solicitud_id = $datawrk["sol_cli"];
		@phpmkr_free_result($rswrk);

		
		if(!empty($x_solicitud_id)){
			
			$sSqlWrk = "SELECT * FROM ingreso Where solicitud_id = $x_solicitud_id";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_ingreso_semanal = $datawrk["ingresos_negocio"];				
			@phpmkr_free_result($rswrk);
			
			if(empty($x_ingreso_semanal) || $x_ingreso_semanal == 0){
				$x_ingreso_semanal = 1;	
			}

		}else{
			$x_ingreso_semanal = 1;
		}

		
		
?>
	<!-- Table body -->
	<tr>
	  <td>146</td>
		<!-- cliente_id -->
		<td><span>
<?php echo $x_cliente_num; ?>
</span></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<!-- nombre_completo -->
		<td><span>
<?php echo $x_apellido_paterno; ?>
</span></td>
		<td><?php echo $x_apellido_materno; ?></td>
		<td><?php echo $x_nombre_completo; ?></td>
		<td><?php 
		if(!empty($x_fecha_nac) && $x_fecha_nac != "0000-00-00"){
		echo FormatDateTime($x_fecha_nac,7); 
		}
		?></td>
		<td align="center"><?php echo $x_sexo; ?></td>
		<td><?php echo $x_telefono; ?></td>
		<td><?php echo $x_estado_civil_id; ?></td>
		<td><?php echo $x_estado; ?></td>
		<td><?php echo "=TEXTO(".$x_delegacion.",\"00000\")"; ?></td>
		<td>&nbsp;</td>
		<!-- tipo_negocio -->
		<td><?php echo $x_calle; ?></td>
		<td><?php echo $x_num_ext; ?></td>
		<td>&nbsp;</td>
		<td><?php echo $x_colonia; ?></td>
		<td><?php echo $x_codigo_postal; ?></td>
		<td>I</td>
		<td><?php //echo "0"; ?></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><?php 
		if(!empty($x_fecha_ini_act_prod) && $x_fecha_ini_act_prod != "0000-00-00"){
		echo FormatDateTime($x_fecha_ini_act_prod,7); 
		}
		?></td>
		<td><?php echo $x_tipo_inmueble_id; ?></td>
		<td><?php echo $x_personas_trabajando; ?></td>
		<td><?php echo $x_ingreso_semanal; ?></td>
		<td><?php echo $x_rol_hogar_id; ?></td>
		<!-- edad -->
		<td><?php echo "MATRIZ"; ?></td>
	</tr>
<?php
	}
}
?>
</table>
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
	$BasicSearchSQL.= "cliente.nombre_completo LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "cliente.tipo_negocio LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "cliente.nombre_conyuge LIKE '%" . $sKeyword . "%' OR ";
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

		// Field cliente_id
		if ($sOrder == "cliente_id") {
			$sSortField = "cliente.cliente_id";
			$sLastSort = @$_SESSION["cliente_x_cliente_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["cliente_x_cliente_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["cliente_x_cliente_id_Sort"] <> "") { @$_SESSION["cliente_x_cliente_id_Sort"] = ""; }
		}

		// Field solicitud_id
		if ($sOrder == "solicitud_id") {
			$sSortField = "solicitud.solicitud_id";
			$sLastSort = @$_SESSION["cliente_x_solicitud_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["cliente_x_solicitud_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["cliente_x_solicitud_id_Sort"] <> "") { @$_SESSION["cliente_x_solicitud_id_Sort"] = ""; }
		}

		// Field usuario_id
		if ($sOrder == "usuario_id") {
			$sSortField = "cliente.usuario_id";
			$sLastSort = @$_SESSION["cliente_x_usuario_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["cliente_x_usuario_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["cliente_x_usuario_id_Sort"] <> "") { @$_SESSION["cliente_x_usuario_id_Sort"] = ""; }
		}

		// Field nombre_completo
		if ($sOrder == "nombre_completo") {
			$sSortField = "cliente.nombre_completo";
			$sLastSort = @$_SESSION["cliente_x_nombre_completo_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["cliente_x_nombre_completo_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["cliente_x_nombre_completo_Sort"] <> "") { @$_SESSION["cliente_x_nombre_completo_Sort"] = ""; }
		}

		// Field tipo_negocio
		if ($sOrder == "tipo_negocio") {
			$sSortField = "cliente.tipo_negocio";
			$sLastSort = @$_SESSION["cliente_x_tipo_negocio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["cliente_x_tipo_negocio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["cliente_x_tipo_negocio_Sort"] <> "") { @$_SESSION["cliente_x_tipo_negocio_Sort"] = ""; }
		}

		// Field edad
		if ($sOrder == "edad") {
			$sSortField = "cliente.edad";
			$sLastSort = @$_SESSION["cliente_x_edad_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["cliente_x_edad_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["cliente_x_edad_Sort"] <> "") { @$_SESSION["cliente_x_edad_Sort"] = ""; }
		}

		// Field sexo
		if ($sOrder == "sexo") {
			$sSortField = "cliente.sexo";
			$sLastSort = @$_SESSION["cliente_x_sexo_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["cliente_x_sexo_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["cliente_x_sexo_Sort"] <> "") { @$_SESSION["cliente_x_sexo_Sort"] = ""; }
		}

		// Field estado_civil_id
		if ($sOrder == "estado_civil_id") {
			$sSortField = "cliente.estado_civil_id";
			$sLastSort = @$_SESSION["cliente_x_estado_civil_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["cliente_x_estado_civil_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["cliente_x_estado_civil_id_Sort"] <> "") { @$_SESSION["cliente_x_estado_civil_id_Sort"] = ""; }
		}

		// Field numero_hijos
		if ($sOrder == "numero_hijos") {
			$sSortField = "cliente.numero_hijos";
			$sLastSort = @$_SESSION["cliente_x_numero_hijos_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["cliente_x_numero_hijos_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["cliente_x_numero_hijos_Sort"] <> "") { @$_SESSION["cliente_x_numero_hijos_Sort"] = ""; }
		}

		// Field nombre_conyuge
		if ($sOrder == "nombre_conyuge") {
			$sSortField = "cliente.nombre_conyuge";
			$sLastSort = @$_SESSION["cliente_x_nombre_conyuge_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["cliente_x_nombre_conyuge_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["cliente_x_nombre_conyuge_Sort"] <> "") { @$_SESSION["cliente_x_nombre_conyuge_Sort"] = ""; }
		}
		$_SESSION["cliente_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["cliente_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["cliente_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["cliente_OrderBy"] = $sOrderBy;
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
		$_SESSION["cliente_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["cliente_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["cliente_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["cliente_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["cliente_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["cliente_REC"] = $nStartRec;
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
			$_SESSION["cliente_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["cliente_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["cliente_OrderBy"] = $sOrderBy;
			if (@$_SESSION["cliente_x_cliente_id_Sort"] <> "") { $_SESSION["cliente_x_cliente_id_Sort"] = ""; }
			if (@$_SESSION["cliente_x_solicitud_id_Sort"] <> "") { $_SESSION["cliente_x_solicitud_id_Sort"] = ""; }
			if (@$_SESSION["cliente_x_usuario_id_Sort"] <> "") { $_SESSION["cliente_x_usuario_id_Sort"] = ""; }
			if (@$_SESSION["cliente_x_nombre_completo_Sort"] <> "") { $_SESSION["cliente_x_nombre_completo_Sort"] = ""; }
			if (@$_SESSION["cliente_x_tipo_negocio_Sort"] <> "") { $_SESSION["cliente_x_tipo_negocio_Sort"] = ""; }
			if (@$_SESSION["cliente_x_edad_Sort"] <> "") { $_SESSION["cliente_x_edad_Sort"] = ""; }
			if (@$_SESSION["cliente_x_sexo_Sort"] <> "") { $_SESSION["cliente_x_sexo_Sort"] = ""; }
			if (@$_SESSION["cliente_x_estado_civil_id_Sort"] <> "") { $_SESSION["cliente_x_estado_civil_id_Sort"] = ""; }
			if (@$_SESSION["cliente_x_numero_hijos_Sort"] <> "") { $_SESSION["cliente_x_numero_hijos_Sort"] = ""; }
			if (@$_SESSION["cliente_x_nombre_conyuge_Sort"] <> "") { $_SESSION["cliente_x_nombre_conyuge_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["cliente_REC"] = $nStartRec;
	}
}
?>
