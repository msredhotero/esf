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
/*if (@$_SESSION["php_project_esf_status"] <> "login") {
	header("Location:  login.php");
	exit();
}*/
?>
<?php

// Initialize common variables
$x_vencimiento_id = Null; 
$ox_vencimiento_id = Null;
$x_credito_id = Null; 
$ox_credito_id = Null;
$x_vencimiento_status_id = Null; 
$ox_vencimiento_status_id = Null;
$x_fecha_vencimiento = Null; 
$ox_fecha_vencimiento = Null;
$x_importe = Null; 
$ox_importe = Null;
$x_interes = Null; 
$ox_interes = Null;
$x_interes_moratorio = Null; 
$ox_interes_moratorio = Null;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=lisatdo_facturas_mes.xls');
}
if ($sExport == "word") {
	header('Content-Type: application/vnd.ms-word');
	header('Content-Disposition: attachment; filename=listado_facturas_mes.doc');
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
 if ($sExport == "") {
$nDisplayRecs = 500;
$nRecRange = 10;
 }else{
	 $nDisplayRecs  = 1000000000;
	 }


// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$filter = array();

$filter['x_credito_tipo_id'] = 100;
$filter['x_nombre_srch'] = '';
$filter['x_apepat_srch'] = '';
$filter['x_apemat_srch'] = '';
$filter['x_clinum_srch'] = '';
$filter['x_cresta_srch'] = '';
$filter['x_entidad_srch'] = '';
$filter['x_delegacion_srch'] = '';
$filter['x_credito_tipo_id'] = 100;
$filter['x_credito_num_filtro'] = '';
$filter['x_fecha_desde'] = '';
$filter['x_fecha_hasta'] = '';
$filter['x_medio_pago_id'] = '';
$filter['x_promo_srch'] = '';
$filter['x_gestor_srch'] = '';
$filter['x_sucursal_srch'] = '';
$filter['x_fecha_desde_2'] = '';
$filter['x_fecha_hasta_2'] = '';
$filter['x_gestor'] = '';


 if ($sExport != "") {
if(isset($_GET)) {	
	foreach($_GET as $key => $value) {
		
		if(isset($filter[$key]))
			 $filter[$key] = $value;
	}
	
}
 }

 if ($sExport == "") {
if(isset($_POST)) {	
	foreach($_POST as $key => $value) {
		if(isset($filter[$key])) $filter[$key] = $value;
	}	
	
	
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


// Handle Reset Command
//ResetCmd();

$sqlFecha = " SELECT fecha FROM estado_cuenta order by estado_cuenta_id DESC limit 0,1 ";
$rsFecha = phpmkr_query($sqlFecha,$conn) or die("Error al seleccionar la fecha de las facturas". phpmkr_error()."sql:".$sqlFecha);
$rowFecha = phpmkr_fetch_array($rsFecha);
$x_fecha_facturas = $rowFecha["fecha"];

$SQLfACTAURAS = "SELECT * FROM estado_cuenta  WHERE fecha = \"$x_fecha_facturas\" ";
$rs = phpmkr_query($SQLfACTAURAS,$conn) or die ("Error al seleccionar los datos de las  fcturas".phpmkr_error()."sql:".$SQLfACTAURAS);





	
	

// Load Default Filter
$sDefaultFilter = "";
$sGroupBy = "";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = "";


	







?>
<?php include ("header.php") ?>
<script type="text/javascript" src="ew.js"></script>
<?php if ($sExport == "") { ?>
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/skins/aqua/theme.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-es.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<script type="text/javascript" src="ew.js"></script>
<?php } ?>

<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<?php if ($sExport == "") { ?>
<script type="text/javascript" src="utilerias/datefunc.js"></script>
<?php } ?>

<script type="text/javascript">
<!--
function filtrar(){
EW_this = document.filtro;
validada = true;

	if(validada == true){
		EW_this.submit();
	}
}
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
//$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$nTotalRecs = phpmkr_num_rows($rs);
if ($nDisplayRecs <= 0) { // Display All Records
	$nDisplayRecs = $nTotalRecs;
}
$nStartRec = 1;
SetUpStartRec(); // Set Up Start Record Position
?>
<p><span class="phpmaker">LISTADO DE FACTURAS DEL MES
<?php if ($sExport == "") { ?>

<?php if(($_SESSION["php_project_esf_status_UserRolID"] != 1) || ($_SESSION["php_project_esf_status_UserRolID"] != 4)){
?>
&nbsp;&nbsp;<a href="php_factura_electronica_listado_exportar.php?export=excel">Exportar a Excel</a><?php } ?><?php } ?>
</span></p>
<p>
<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="phpmaker" style="color: Red;"><?php echo $_SESSION["ewmsg"]; ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>

<?php if ($sExport == "") { ?>

<form action="php_rpt_pagos.php" name="filtro" id="filtro" method="post">
  <table width="785" border="0" cellpadding="0" cellspacing="0">
  <tr>
  <td>&nbsp;</td>
</tr>
</table>
</form>
<?php } ?>


<?php if ($sExport == "") { ?>
<form action="php_rpt_pagos.php" name="ewpagerform" id="ewpagerform">
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
		<a href="php_rpt_pagos.php?start=<?php echo $PrevStart; ?>&<?php echo $_QS; ?>"><b>Anterior</b></a>
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
		<a href="php_rpt_pagos.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?></b></a>
					<?php }
					$x += $nDisplayRecs;
					$y += 1;
				} elseif (($x >= ($dx1-$nDisplayRecs*$nRecRange)) && ($x <= ($dx2+$nDisplayRecs*$nRecRange))) {
					if ($x+$nRecRange*$nDisplayRecs < $nTotalRecs) { ?>
		<a href="php_rpt_pagos.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?>-<?php echo ($y+$nRecRange-1);?></b></a>
					<?php } else {
						$ny=intval(($nTotalRecs-1)/$nDisplayRecs)+1;
							if ($ny == $y) { ?>
		<a href="php_rpt_pagos.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?></b></a>
							<?php } else { ?>
		<a href="php_rpt_pagos.php?start=<?php echo $x; ?>&<?php echo $_QS; ?>"><b><?php echo $y; ?>-<?php echo $ny; ?></b></a>
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
		<a href="php_rpt_pagos.php?start=<?php echo $NextStart; ?>&<?php echo $_QS; ?>"><b>Siguiente</b></a>
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
		<td width="119" valign="top">Serie</td>
		<td width="100" valign="top">folio</td>
        <td width="50" valign="top">fecha</td>
		<td width="50" valign="top">No. Aprob</td>     
        <td width="50" valign="top">A&ntilde;o Aprob</td>    
		<td width="50" valign="top">Tipo Comprobante</td>
<td width="50" valign="top">Forma Pago</td>
		<td width="50" valign="top">Sub Total</td>
		<td width="50" valign="top">Total</td>
		<td width="50" valign="top">RFC CREA</td>
		<td width="50" valign="top">Nombre</td>
		<td width="50" valign="top">Calle</td>
		<td width="50" valign="top">Numero Ext</td>
		<td width="50" valign="top">Numero Int</td>
		<td width="50" valign="top">Colonia</td>
		<td width="63" valign="top">Delegacion</td> 
        <td width="50" valign="top">Estado</td>
		<td width="50" valign="top">Pais</td>
		<td width="50" valign="top">C.P</td>
        <td width="50" valign="top">RFC CLIENTE</td>
		<td width="50" valign="top">Nombre</td>
		<td width="50" valign="top">Calle</td>
		<td width="50" valign="top">Numero Ext</td>
		<td width="50" valign="top">Numero Int</td>
		<td width="50" valign="top">Colonia</td>
		<td width="63" valign="top">Delegacion</td> 
        <td width="50" valign="top">Estado</td>
		<td width="50" valign="top">Pais</td>
		<td width="50" valign="top">C.P</td>
        <td width="50" valign="top">Dias Periodo</td>
        <td width="50" valign="top">Saldo Promedio</td>
        <td width="50" valign="top">Saldo Final</td>
        <td width="50" valign="top">Tipo cuenta</td>
		       
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
$x_tot_importe = 0;
$x_tot_intereses = 0;
$x_tot_moratorios = 0;
$x_tot_total_pago = 0;

while (($row = @phpmkr_fetch_array($rs)) && ($nRecCount < $nStopRec)) {
	$nRecCount = $nRecCount + 1;
	if ($nRecCount >= $nStartRec) {
		$nRecActual++;

		// Set row color
		
		// aqui todos los ueries anidados
		
		 $rowFacturas = $row ;
		
	$x_estado_cuenta_id = $rowFacturas["estado_cuenta_id"];
	$x_credito_id = $rowFacturas["credito_id"];
	$x_fecha = $rowFacturas["fecha"];
	$x_numero = $rowFacturas["numero"];
	$x_dias_periodo = $rowFacturas["dias_periodo"];
	$x_saldo_final = $rowFacturas["saldo_final"];
	$x_tipo_cuenta = $rowFacturas["tipo_cuenta"];
	$x_saldo_promedio = $rowFacturas["saldo_promedio"];
	
	
	// buscamos los datos del credito
	$sqlCredito =  "SELECT * FROM credito WHERE credito_id  = $x_credito_id "; 
	$rsCredito = phpmkr_query($sqlCredito, $conn)or die ("Erro en credito");
	$rowCredito = phpmkr_fetch_array($rsCredito);
/*	foreach($rowCredito as $campo => $valor){
		$$campo = $valor;
		echo $campo."<br>";
		}*/	
	$x_credito_tipo_id = $rowCredito["credito_tipo_id"];
	$x_solicitud_id = $rowCredito["solicitud_id"];
	$x_credito_status_id = $rowCredito["credito_status_id"];
	$x_credito_num = $rowCredito["credito_num"];
	$x_fecha_otrogamiento = $rowCredito["fecha_otrogamiento"];
	$x_importe = $rowCredito["importe"];
	$x_tasa = $rowCredito["tasa"];
	$x_plazo_id = $rowCredito["plazo_id"];
	$x_fecha_vencimiento = $rowCredito["fecha_vencimiento"];	
	$x_tasa_moratoria = $rowCredito["tasa_moratoria"];
	$x_cliente_num = $rowCredito["cliente_num"];
	$x_iva = $rowCredito["iva"];	
	
	
	$sqlCliente =  "SELECT * FROM cliente, solicitud_cliente WHERE solicitud_cliente.cliente_id = cliente.cliente_id and solicitud_cliente.solicitud_id = $x_solicitud_id ";
	$rsCliente =  phpmkr_query($sqlCliente, $conn) or die("Error al seleccionar cliente".phpmkr_error().$sqlCliente);
	$rowCliente = phpmkr_fetch_array($rsCliente);
	    /*foreach($rowCliente as $campo => $valor){
		$$campo = $valor;
		echo $campo."<br>";		
		}*/
		
		$x_cliente_id =  $rowCliente["cliente_id"];
		$x_nombre_completo =  $rowCliente["nombre_completo"];
		$x_rfc =  $rowCliente["rfc"];
		$x_apellido_paterno =  $rowCliente["apellido_paterno"];
		$x_apellido_materno =  $rowCliente["apellido_materno"];
		$x_nombre_cliente = $x_nombre_completo." ".$x_apellido_paterno ." ".$x_apellido_materno;
		
		if($x_iva == 1 ){
		// si es 1 tiene RFC GENERICO
		$x_rfc = "XAXX010101000";		
		}else{
			// tiene RFC PROPIO
			$x_rfc  = $rowCliente["rfc"];		
			}// iva  == 1
			
		if(empty($x_rfc)){			
			$x_rfc = "XAXX010101000";			
			}		
		
		if(!empty($x_cliente_id)){
	// seleccionamos los datos de la direccion del cliente 
	$sqlDireccion = "SELECT * FROM direccion  WHERE cliente_id = $x_cliente_id  and direccion_tipo_id = 1";	
	$rsDireccion = phpmkr_query($sqlDireccion, $conn) or die ("Error al seleccionar los datos de la direccion ".phpmkr_error().$sqlDireccion);
	$rowDireccion = phpmkr_fetch_array($rsDireccion);
		}
	
	#echo $sqlDireccion;
	/*foreach($rowDireccion as $campo => $valor){
		$$campo = $valor;
		echo $campo. " -- ". $valor."<br>";
		}*/
		
		$x_calle =  $rowDireccion["calle"];
		$x_colonia =  $rowDireccion["colonia"];
		$x_delegacion_id =  $rowDireccion["delegacion_id"];
		$x_codigo_postal =  $rowDireccion["codigo_postal"];
		$x_no_exterior =  $rowDireccion["numero_exterior"];
		$x_entidad =  $rowDireccion["entidad"];
		if($x_no_exterior == 0){
			$x_no_exterior = "";
			}
	
		
	$x_delagacion_descripcion = "";	
	if(!empty($x_delegacion_id)){
	$sqlDelegacion = "SELECT * FROM delegacion WHERE delegacion_id = $x_delegacion_id ";
	#echo  $sqlDelegacion;
	$rsDelagacion = phpmkr_query($sqlDelegacion,$conn) or die("Error al selecionar la delagacion".phpmkr_error().$sqlDelegacion);
	$rowDelagacion =  phpmkr_fetch_array($rsDelagacion);	
	$x_delagacion_descripcion =  $rowDelagacion["descripcion"];
	$x_entidad_id = $rowDelagacion["entidad_id"];
	}
	$x_entidad_descripcion = "";
	if(!empty($x_entidad_id)){
	$sqlDelegacion = "SELECT * FROM entidad  where  entidad_id = $x_entidad_id";
	$rsDelagacion = phpmkr_query($sqlDelegacion,$conn) or die("Error al selecionar la delagacion".phpmkr_error().$sqlDelegacion);
	$rowDelagacion =  phpmkr_fetch_array($rsDelagacion);	
	$x_entidad_descripcion =  $rowDelagacion["nombre"];
		
		}
	
	
	
	
	$sqlCertificado = "SELECT * FROM factura_certificado  WHERE factura_certificado_id = 1";
	$rsCertificado = phpmkr_query($sqlCertificado, $conn) or die ("Error al seleccionar los datos de los pagos en la genracion del xml". phpmkr_error()."Query:".$sqlCertificado);
	$rowCertificado = phpmkr_fetch_array($rsCertificado);
	$x_rfc_contribuyente = $rowCertificado["rfc_contribuyente"];
	$x_numero_aprobacion = $rowCertificado["numero_aprobacion"];
	$x_anio_aprobacion = $rowCertificado["anio_aprobacion"];
	$x_serie_certificado = $rowCertificado["serie_certificado"];
	$x_serie = $rowCertificado["serie"];
	$x_fecha_transaccion = $rowCertificado["fecha_transaccion"];
	$x_hora_transaccion = $rowCertificado["hora_transaccion"];
	$x_hora_transaccion = $rowCertificado["hora_transaccion"];
	$x_hora_transaccion = $rowCertificado["hora_transaccion"];
	$x_hora_transaccion = $rowCertificado["hora_transaccion"];
	$x_fecha_T = $x_fecha_transaccion."T".$x_hora_transaccion;
	$x_fecha_R = $x_fecha_transaccion." ".$x_hora_transaccion;
	
	mysql_free_result($rsCertificado);
	$x_version = "2.0";
	$x_serie = "";
	$x_folio = "";
	$x_hora_transaccion = "";
	$x_sello = "";
	$x_numero_aprobacion = "";
	$x_anio_aprobacion = "";
	$x_forma_pago = "PAGO EN UNA SOLA EXHIBICION"; // este campo va lleno
	$x_sub_total_XML = "";
	$x_total_XML = "";
	$x_tipo_comprobante = "ingreso";
	$rfc_crea = $x_rfc_contribuyente;
	$x_nombre_crea = "Microfinanciera Crece, S.A. de C.V., SOFOM, E.N.R.";
	$x_calle_crea = "AVENIDA REVOLUCION";
	$x_no_exterior_crea = "1909";
	$x_no_interior_crea = "PISO 7";
	$x_municipio_crea = "SAN ANGEL";
	$x_delagacion_crea = "ALVARO OBREGON";
	$x_entidad_crea = "DISTRITO FEDERAL";
	$x_pais_crea = "MEXICO";
	$x_cp_crea = "01000";
		
		
			


		
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>
		<!-- credito_id -->
		<td align="center"><?php echo $x_serie; ?></td>
		<td align="center"><?php echo $x_folio; ?></td>
<td align="center"><?php echo $x_hora_transaccion ?></td>
		<td align="left"><?php echo $x_numero_aprobacion; ?></td>
<td align="left">
<?php echo $x_anio_aprobacion;?></td>

		<!-- fecha_vencimiento -->
		<td align="center"><?php echo $x_tipo_comprobante; ?></td>
<td align="center"><?php echo $x_forma_pago; ?></td>
		<td align="center" valign="middle"><?php echo $x_sub_total_XML; ?></td>
		<td align="center" valign="middle"><?php echo $x_total_XML; ?></td>
        
        
		<td align="center" valign="middle"><?php echo $rfc_crea; ?></td>
		<td align="center" valign="middle"><?php echo $x_nombre_crea; ?></td>		
		<td align="right"><?php echo $x_calle_crea; ?></td>	
		<td align="right"><?php echo $x_no_exterior_crea; ?></td>
		<td align="right"><?php echo $x_no_interior_crea; ?></td>		
		<td align="right"><?php echo $x_municipio_crea; ?></td>
		<td align="right"><?php echo $x_delagacion_crea ?></td>
        <td align="right"><?php echo $x_entidad_crea; ?></td>
		<td align="right"><?php echo $x_pais_crea ?></td>
        <td align="right"><?php echo $x_cp_crea; ?></td>
        
        <td align="center" valign="middle"><?php echo $x_rfc; ?></td>
		<td align="center" valign="middle"><?php echo $x_nombre_cliente; ?></td>		
		<td align="right"><?php echo $x_calle; ?></td>	
		<td align="right"><?php echo $x_no_exterior; ?></td>
		<td align="right"><?php echo $x_no_interior; ?></td>		
		<td align="right"><?php echo $x_colonia ?></td>
		<td align="right"><?php echo $x_delagacion_descripcion ?></td>
        <td align="right"><?php echo $x_entidad_descripcion; ?></td>
		<td align="right"><?php echo $x_pais_crea ?></td>
        <td align="right"><?php echo $x_codigo_postal; ?></td>
        
        <td align="right"><?php echo $x_dias_periodo; ?></td>
        <td align="right"><?php echo FormatNumber($x_saldo_promedio,0,0,0,-2) ; ?></td>
        <td align="right"><?php echo $x_saldo_final; ?></td>
        <td align="right"><?php echo $x_tipo_cuenta; ?></td>
		
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

		// Field vencimiento_id
		if ($sOrder == "vencimiento_id") {
			$sSortField = "`vencimiento_id`";
			$sLastSort = @$_SESSION["vencimiento_x_vencimiento_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vencimiento_x_vencimiento_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vencimiento_x_vencimiento_id_Sort"] <> "") { @$_SESSION["vencimiento_x_vencimiento_id_Sort"] = ""; }
		}

		// Field credito_id
		if ($sOrder == "credito_id") {
			$sSortField = "`credito_num+0`";
			$sLastSort = @$_SESSION["vencimiento_x_credito_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vencimiento_x_credito_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vencimiento_x_credito_id_Sort"] <> "") { @$_SESSION["vencimiento_x_credito_id_Sort"] = ""; }
		}

		// Field vencimiento_status_id
		if ($sOrder == "vencimiento_status_id") {
			$sSortField = "`vencimiento_status_id`";
			$sLastSort = @$_SESSION["vencimiento_x_vencimiento_status_id_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vencimiento_x_vencimiento_status_id_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vencimiento_x_vencimiento_status_id_Sort"] <> "") { @$_SESSION["vencimiento_x_vencimiento_status_id_Sort"] = ""; }
		}

		// Field fecha_vencimiento
		if ($sOrder == "fecha_vencimiento") {
			$sSortField = "`fecha_pago`";
			$sLastSort = @$_SESSION["vencimiento_x_fecha_vencimiento_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vencimiento_x_fecha_vencimiento_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vencimiento_x_fecha_vencimiento_Sort"] <> "") { @$_SESSION["vencimiento_x_fecha_vencimiento_Sort"] = ""; }
		}

		// Field importe
		if ($sOrder == "importe") {
			$sSortField = "`importe`";
			$sLastSort = @$_SESSION["vencimiento_x_importe_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vencimiento_x_importe_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vencimiento_x_importe_Sort"] <> "") { @$_SESSION["vencimiento_x_importe_Sort"] = ""; }
		}

		// Field interes
		if ($sOrder == "interes") {
			$sSortField = "`interes`";
			$sLastSort = @$_SESSION["vencimiento_x_interes_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vencimiento_x_interes_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vencimiento_x_interes_Sort"] <> "") { @$_SESSION["vencimiento_x_interes_Sort"] = ""; }
		}

		// Field interes_moratorio
		if ($sOrder == "interes_moratorio") {
			$sSortField = "`interes_moratorio`";
			$sLastSort = @$_SESSION["vencimiento_x_interes_moratorio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vencimiento_x_interes_moratorio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vencimiento_x_interes_moratorio_Sort"] <> "") { @$_SESSION["vencimiento_x_interes_moratorio_Sort"] = ""; }
		}
		$_SESSION["vencimiento_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["vencimiento_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["vencimiento_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["vencimiento_OrderBy"] = $sOrderBy;
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
		$_SESSION["vencimiento_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["vencimiento_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["vencimiento_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["vencimiento_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["vencimiento_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["vencimiento_REC"] = $nStartRec;
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
			$_SESSION["vencimiento_searchwhere"] = $sSrchWhere;
			$_SESSION["x_fecha_desde"] = "";
			$_SESSION["x_fecha_hasta"] = "";		
			$_SESSION["x_credito_num_filtro"] = "";	
			$_SESSION["x_medio_pago_id"] = 0;
			$_SESSION["x_promo_srch"] = 0;
			
		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["vencimiento_searchwhere"] = $sSrchWhere;
			$_SESSION["x_fecha_desde"] = "";
			$_SESSION["x_fecha_hasta"] = "";		
			$_SESSION["x_credito_num_filtro"] = "";	
			$_SESSION["x_medio_pago_id"] = 0;
			$_SESSION["x_promo_srch"] = 0;			

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["vencimiento_OrderBy"] = $sOrderBy;
			if (@$_SESSION["vencimiento_x_vencimiento_id_Sort"] <> "") { $_SESSION["vencimiento_x_vencimiento_id_Sort"] = ""; }
			if (@$_SESSION["vencimiento_x_credito_id_Sort"] <> "") { $_SESSION["vencimiento_x_credito_id_Sort"] = ""; }
			if (@$_SESSION["vencimiento_x_vencimiento_status_id_Sort"] <> "") { $_SESSION["vencimiento_x_vencimiento_status_id_Sort"] = ""; }
			if (@$_SESSION["vencimiento_x_fecha_vencimiento_Sort"] <> "") { $_SESSION["vencimiento_x_fecha_vencimiento_Sort"] = ""; }
			if (@$_SESSION["vencimiento_x_importe_Sort"] <> "") { $_SESSION["vencimiento_x_importe_Sort"] = ""; }
			if (@$_SESSION["vencimiento_x_interes_Sort"] <> "") { $_SESSION["vencimiento_x_interes_Sort"] = ""; }
			if (@$_SESSION["vencimiento_x_interes_moratorio_Sort"] <> "") { $_SESSION["vencimiento_x_interes_moratorio_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["vencimiento_REC"] = $nStartRec;
	}
}
?>
