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

//die();
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

$currentdate = getdate(time());
$currdate = $currentdate["year"].$currentdate["mon"].$currentdate["mday"];	
$currdate_venc = $currentdate["year"]."-".$currentdate["mon"]."-".$currentdate["mday"];	

$temptime = strtotime($currdate_venc);
$x_fecha_extraccion = strftime('%Y%m%d',$temptime);
$x_fecha_corte = $x_fecha_extraccion;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=lista_factura_mes.xls');
}
if ($sExport == "word") {
	header('Content-Type: application/vnd.ms-word');
	header('Content-Disposition: attachment; filename=lista_factura_mes.doc');
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
$nDisplayRecs = 500;
$nRecRange = 10;





// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

$x_hoy = date("Y-m-d");
$x_hoy = "2014-02-27";

$sqlLastDay = "SELECT LAST_DAY (\"$x_hoy\") AS ultimo_dia_mes ";
$rsLastDay = phpmkr_query($sqlLastDay,$conn) or die ("Error en dia".phpmkr_error().$sqlLastDay);
$rowLastDay =  phpmkr_fetch_array($rsLastDay);
$x_ultimo_dia_mes = $rowLastDay["ultimo_dia_mes"];


$x_fecha_mes = explode("-",$x_ultimo_dia_mes);
$x_anio = $x_fecha_mes[0];
$x_mes = $x_fecha_mes[1];
$x_dia = "01";
$x_dia_fin = $x_fecha_mes[2];
$x_primer_dia_mes = $x_anio."-".$x_mes."-".$x_dia;


// seleccionamos todos los creditos con pagos para el mes de fecturacion
// que esten activos o que esten liquidados
$x_todayy = date("Y-m-d");

$sSql = "SELECT credito.credito_id  FROM vencimiento, credito  WHERE credito.credito_id = vencimiento.credito_id and vencimiento.fecha_vencimiento >= \"$x_primer_dia_mes\"  and vencimiento.fecha_vencimiento <= \"$x_ultimo_dia_mes\" and credito.credito_id not in(1489)  and (credito.credito_status_id = 1 || credito.credito_status_id = 3) GROUP BY credito_id ";

echo $sSql."<br>";
#die();


// Load Default Order
$sDefaultOrderBy = "";

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

if ($sOrderBy != "") {
	//$sSql .= " ORDER BY vencimiento.credito_id,vencimiento.fecha_vencimiento ";
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
<?php

// Set up Record Set
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$nTotalRecs = phpmkr_num_rows($rs);
if ($nDisplayRecs <= 0) { // Display All Records
	$nDisplayRecs = $nTotalRecs;
}
$nStartRec = 1;

?>
<?php if ($sExport <> "word" && $sExport <> "excel") { ?>
<a href="php_circulocredito_list.php">Regresar</a><br /><br />
<?php } ?>

<?php if ($nTotalRecs > 0)  { ?>
<form method="post" name="principal" action="">
<input type="hidden" name="credito_id" value="<?php echo $x_credito_id; ?>"  />

<?php if ($sExport == "") { ?>
<a href="php_factura_electronica_y_notas_credito.php?export=excel">Exportar a Excel</a>
<br /><br />
<?php } ?>

<span class="phpmaker">LISTA DE FACTURAS <?php echo $x_todayy; ?></span>
<br />
<p>&nbsp;</p>
<span class="phpmaker" style="color:#F00"> Para descargar el archivo</span> <span class="phpmaker" style="color:#F00"> <?php echo "LISTADO_FACTURAS_$x_todayy.txt";?> </span><span class="phpmaker" style="color:#F00">  use la liga al final de la tabla.</span><br />
<p>&nbsp;</p>

<a href="php_factura_electronica_lista_clientes.php?nombre_fichero=<?php echo "LISTADO_FACTURAS_$x_todayy.txt"; ?>"  target="_blank"> PARA IR A LA LISTA DE CLIENTES PULSE AQUI </a>
<p>&nbsp;</p>

<table id="ewlistmain" class="ewTable" align="center">
	<!-- Table header -->
	

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

$x_total = 0;
$x_total_pagos = 0;
$x_total_interes = 0;
$x_total_moratorios = 0;
$x_total_total = 0;

$x_tot_saldo_actual = 0;
$x_tot_saldo_vencido = 0;			
$x_tot_nombres_reportados = 0;
$x_tot_domicilio_reportados = 0;			
$x_tot_empleo_reportados = 0;			
$x_tot_cuenta_reportados = 0;			


#$x_ultimo_folio = 0;
$nRecActual = 0;

$file=fopen("facturas_notas_clientes_txt/LISTADO_FACTURAS_$x_todayy.txt","w+") or die("Problemas");

//echo "LISTADO_FACTURAS_$x_todayy.txt<br>";
while (($rowCreditosFactura = @phpmkr_fetch_array($rs)) && ($nRecCount < $nStopRec)) {
	$nRecCount = $nRecCount + 1;
	if ($nRecCount >= $nStartRec) {
		$nRecActual++;

		
		
	$x_credito_id = $rowCreditosFactura["credito_id"];
	//echo "credito_id = ".$x_credito_id."<br>";
	// seleccionamos los vencimiens de cada credito que corresponden al mes a facturar
	
	
	/// PRIMER RENGLON DEL CASO
		
		
		$x_cliente_id = 0;
		$SQLsOLICITU = "SELECT solicitud_id, iva FROM credito WHERE credito_id = $x_credito_id ";
		$rsSolictud = phpmkr_query($SQLsOLICITU,$conn)or die("Erro al seleccionar sol".phpmkr_error().$SQLsOLICITU);
		$rowSolicitud = phpmkr_fetch_array($rsSolictud);
		$x_solicitud_id = $rowSolicitud["solicitud_id"];
		$x_iva_credito = $rowSolicitud["iva"];
		
		$sqlCliente = "SELECT cliente_id from  solicitud_cliente WHERE solicitud_id = $x_solicitud_id ";
		$rsCliente = phpmkr_query($sqlCliente,$conn)or die("Error al seleccionar el cliente".phpmkr_error().$sqlCliente);
		$RowCliente = phpmkr_fetch_array($rsCliente);
		$x_cliente_id = $RowCliente["cliente_id"];
		
		
		
	$x_total_ordinario = 0;
	$x_total_iva = 0;
	$x_total_mora = 0;
	$x_total_iva_mor = 0;
	$sqlVencimientosMes = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and fecha_vencimiento >= \"$x_primer_dia_mes\"  and fecha_vencimiento <= \"$x_ultimo_dia_mes\"  and vencimiento_num < 3000 order by vencimiento_id ";
	$rsVencimientosMes = phpmkr_query($sqlVencimientosMes,$conn) or die ("Error la seleccionar los vencimitos del mes".phpmkr_error()."sql :".$sqlVencimientosMes);
	
	
	
	while($rowVencimientosMes = phpmkr_fetch_array($rsVencimientosMes)){
		$x_vencimiento_id = $rowVencimientosMes["vencimiento_id"];
		$x_vencimiento_num = $rowVencimientosMes["vencimiento_num"];
		$x_importe = $rowVencimientosMes["importe"];
		$x_interes = $rowVencimientosMes["interes"];
		$x_interes_moratorio = $rowVencimientosMes["interes_moratorio"];
		$x_iva = $rowVencimientosMes["iva"];
		$x_iva_mor = $rowVencimientosMes["iva_mor"];
		$x_total_venc = $rowVencimientosMes["total_venc"];
		
		$x_total_ordinario = $x_total_ordinario + $x_interes; 
		$x_total_iva = $x_total_iva + $x_iva ;
		$x_total_mora = $x_total_mora + $x_interes_moratorio;
		$x_total_iva_mor = $x_total_iva_mor + $x_iva_mor;
		
		
		
		}	
	$sqlVencimientosMes = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and  fecha_vencimiento >= \"$x_primer_dia_mes\"  and fecha_vencimiento <= \"$x_ultimo_dia_mes\"  and vencimiento_num > 3000 order by vencimiento_id ";
	$rsVencimientosMes = phpmkr_query($sqlVencimientosMes,$conn) or die ("Error la seleccionar los vencimitos del mes".phpmkr_error()."sql :".$sqlVencimientosMes);
	
	// aqui selecciono las comiones por cobranza
	$x_interes_moratorio_penalizacion = 0;
	while($rowVencimientosMes = phpmkr_fetch_array($rsVencimientosMes)){
		$x_vencimiento_id = $rowVencimientosMes["vencimiento_id"];		
		$x_interes_moratorio_penalizacion = $rowVencimientosMes["interes_moratorio"];		
		$x_iva_mor_penalizacion = $rowVencimientosMes["iva_mor"];	
	}
		
		
		 $x_Intereses_Ordinarions = "INTORD";	
		 $x_Intereses_Moratorios = "INTMOR";
		 $x_Comisión_Cobranza = "COMCOB";
		 $x_Comison_Gestoria = "COMGTO";
		 $x_codonacion_ordinario = "CONIOR";
		 $x_Condonacion_mora = "CONIMO";
		 $x_Condonacion_comision = "CONCCO";
		 $x_condonacion_gestoria = "CONCGT";
		
		// aqui se genera un renglon por concepto
		
		// se grupa los totales en un solo registro


		#echo "<br>folio factura = ".$x_folio_siguiente_factura;
		#echo "<br>credti id" .$x_credito_id;
		
		
		if(($x_total_ordinario > 0) || ($x_total_mora > 0) || ($x_interes_moratorio_penalizacion > 0)){
			
		// si hay monto para facturar
		// se busca el folio siguiente
			
		//seleccionamos el ultim folio que se dio 
		$sqlFolio = "SELECT MAX(numero) AS ultimo FROM  folio_factura ";
		$rsFolio = phpmkr_query($sqlFolio,$conn)or die("Error al seleccionar las fact".phpmkr_error().$sqlFolio);
		$rowFolio = phpmkr_fetch_array($rsFolio);
		$x_ultimo_folio = $rowFolio["ultimo"];
		$x_folio_siguiente_factura = $x_ultimo_folio + 1;
		$x_ultimo_folio = $x_folio_siguiente_factura;
		
		$x_fecha_folio = date("Y-m-d");
		// insertamos registro en folio_factura
		$sqlinsertFolio = "INSERT INTO `folio_factura` (`folio_factura`, `numero`,`credito_id`, `fecha`) ";
		$sqlinsertFolio .=" VALUES (NULL, $x_folio_siguiente_factura, $x_credito_id, \"$x_fecha_folio\") " ;
		$rsInsertFolio = phpmkr_query($sqlinsertFolio,$conn) or die("error al insertar en folio".phpmkr_error()."sql ".$sqlinsertFolio);
			
			// aqui se llena la primer fila
			
		$x_identificador_registro = "F";
		$x_fecha_factura = $x_anio.$x_mes.$x_dia_fin;
		$x_serie_docto = "FA";
		#$x_serie_docto ="=CONCATENAR(\"$x_serie_docto\",REPETIR(\" \",6-LARGO(\"$x_serie_docto\")))";
		$x_serie_docto = str_pad($x_serie_docto, 6, " ");
		#$x_folio = "=CONCATENAR(REPETIR(\" \",4-LARGO($x_folio_siguiente_factura)),$x_folio_siguiente_factura)";
		$x_folio = str_pad($x_folio_siguiente_factura, 4, " ",STR_PAD_LEFT);
		#$x_clave_cliente = "=CONCATENAR(REPETIR(\" \",5-LARGO($x_cliente_id)),$x_cliente_id)";
		$x_clave_cliente = str_pad($x_cliente_id, 5, " ",STR_PAD_LEFT);
		#$x_id_credito = "=CONCATENAR(REPETIR(\" \",5-LARGO($x_credito_id)),$x_credito_id,)";
		$x_id_credito = str_pad($x_credito_id, 5, " ",STR_PAD_LEFT);
		
		
		
		fputs($file,$x_identificador_registro);
		fputs($file,$x_fecha_factura);
		fputs($file,$x_serie_docto);
		fputs($file,$x_folio);
		fputs($file,$x_clave_cliente);
		fputs($file,$x_id_credito);		
  		fputs($file,"\r\n");
		
		?>
        <tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>

        <td  valign="top">
        <!--CC-->
        <?php echo $x_identificador_registro; ?>
        </td>
        <td  valign="top">
          <!--CC-->
          <?php echo $x_fecha_factura; ?>
        </td>
        <td  valign="top">
          <!--CC-->
          <?php echo $x_serie_docto; ?>
        </td>
        <td   valign="top">
          <!--CC-->
          <?php echo $x_folio; ?>
        </td>
        <td  valign="top">
          <!--CC-->
          <?php echo $x_clave_cliente ?>
        </td>
        <td  valign="top">
          <!--CC-->
          <?php echo $x_id_credito ?>
        </td>
        </tr>
        <?php
		
		
		if($x_total_ordinario > 0){
			// se agrega un renglo para este registro
			$x_identificador_registro = "D";
			
			//$x_clave_registro = "=CONCATENAR(\"$x_Intereses_Ordinarions\",REPETIR(\" \",6-LARGO(\"$x_Intereses_Ordinarions\")))";
 //$x_Intereses_Ordinarions;
 			//$x_clave_registro = str_pad($x_Intereses_Ordinarions, 6, " ");
			$x_cantidad = "01";
			//$x_cantidad = "=CONCATENAR(\"$x_cantidad\",REPETIR(\" \",2-LARGO(\"$x_cantidad\")))";
			$x_importe = round($x_total_ordinario, 2);
			//$x_importe = "=TEXTO($x_importe,\"$00000,00\")";
			$x_tem_imp = $x_importe;
			$x_eplo_impo = explode(".",$x_tem_imp);
			$x_cent = $x_eplo_impo[1];
			if(empty($x_cent)){
				$x_importe = $x_importe.".00"; 
				$x_cent = ".00";
				}
			if(strlen($x_cent) < 2){
				$x_importe = $x_importe."0";
				}	
				
			//$x_importe = "=TEXTO($x_importe,\"0.00\")";	
			//$x_importe = "=CONCATENAR(REPETIR(\" \",8-LARGO(TEXTO($x_importe,\"0.00\"))),TEXTO($x_importe,\"0.00\"))";
			//$x_importe = str_pad($x_importe, 8, " ",STR_PAD_LEFT);
			if($x_iva_credito == 1){
			$x_tasa_iva = "16.00";
			}else{
			$x_tasa_iva = "00.00";	
				}
			//$x_tasa_iva = "=CONCATENAR(\"$x_tasa_iva\",REPETIR(\" \",5-LARGO(\"$x_tasa_iva\")))";	
			$x_tasa_iva = str_pad($x_tasa_iva, 5, " ",STR_PAD_LEFT);
			$x_importe = str_pad($x_importe, 8, " ",STR_PAD_LEFT);
			$x_clave_registro = str_pad($x_Intereses_Ordinarions, 6, " ");
			fputs($file,$x_identificador_registro);
			fputs($file,$x_clave_registro);
			fputs($file,$x_cantidad);
			fputs($file,$x_importe);
			fputs($file,$x_tasa_iva);					
	  		fputs($file,"\r\n");
			
			$SQLdETALLE = "INSERT INTO `detalle_factura` (`detalle_factura_id`, `folio_factura`, `credito_id`, `tasa`, `monto`, `descripcion`) ";
			$SQLdETALLE .= "  VALUES (NULL, $x_folio_siguiente_factura, $x_credito_id, $x_tasa_iva, $x_importe, \"$x_Intereses_Ordinarions\") ";
			$rsDetalle = phpmkr_query($SQLdETALLE,$conn)or die("Error al insertar detalle factura".phpmkr_error()."sql:".$SQLdETALLE);
			
			?>
            <tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>

        <td  valign="top">
        <!--CC-->
        <?php echo $x_identificador_registro; ?>
        </td>
        <td  valign="top">
          <!--CC-->
          <?php echo $x_clave_registro; ?>
        </td>
        <td  valign="top">
          <!--CC-->
          <?php echo $x_cantidad; ?>
        </td>
        <td  valign="top">
          <!--CC-->
          <?php echo $x_importe; ?>
        </td>
        <td  valign="top">
          <!--CC-->
          <?php echo $x_tasa_iva ?>
        </td>
        <td  valign="top">
          <!--CC-->
          <?php echo $x_clave_cliente ?>
        </td>
        </tr>
            <?php
			
			}
			
		if($x_total_mora > 0){
			// se agrega un renglo para este registro
			$x_identificador_registro = "D";
			//$x_clave_registro = "=CONCATENAR(\"$x_Intereses_Moratorios\",REPETIR(\" \",6-LARGO(\"$x_Intereses_Moratorios\")))";
 $x_Intereses_Ordinarions;
			$x_cantidad = "01";
			//$x_cantidad = "=CONCATENAR(\"$x_cantidad\",REPETIR(\" \",2-LARGO(\"$x_cantidad\")))";
			$x_importe = round($x_total_mora, 2);
			$x_tem_imp = $x_importe;
			$x_eplo_impo = explode(".",$x_tem_imp);
			$x_cent = $x_eplo_impo[1];
			if(empty($x_cent)){
				$x_importe = $x_importe.".00"; 
				$x_cent = ".00";
				}
			if(strlen($x_cent) < 2){
				$x_importe = $x_importe."0";
				}
			//$x_importe = "=CONCATENAR(REPETIR(\" \",8-LARGO($x_importe)),$x_importe)";
			//$x_importe = "=TEXTO($x_importe,\"$00000,00\")";
			//$x_importe = "=CONCATENAR(REPETIR(\" \",8-LARGO(TEXTO($x_importe,\"0.00\"))),TEXTO($x_importe,\"0.00\"))";
			if($x_iva_credito == 1){
			$x_tasa_iva = "16.00";
			}else{
			$x_tasa_iva = "00.00";	
				}
			//$x_tasa_iva = "=CONCATENAR(\"$x_tasa_iva\",REPETIR(\" \",5-LARGO(\"$x_tasa_iva\")))";
			
			$x_tasa_iva = str_pad($x_tasa_iva, 5, " ",STR_PAD_LEFT);
			$x_importe = str_pad($x_importe, 8, " ",STR_PAD_LEFT);
			$x_clave_registro = str_pad($x_Intereses_Moratorios, 6, " ");
			fputs($file,$x_identificador_registro);
			fputs($file,$x_clave_registro);
			fputs($file,$x_cantidad);
			fputs($file,$x_importe);
			fputs($file,$x_tasa_iva);					
	  		fputs($file,"\r\n");
			
			$SQLdETALLE = "INSERT INTO `detalle_factura` (`detalle_factura_id`, `folio_factura`, `credito_id`, `tasa`, `monto`, `descripcion`) ";
			$SQLdETALLE .= "  VALUES (NULL, $x_folio_siguiente_factura, $x_credito_id, $x_tasa_iva, $x_importe, \"$x_Intereses_Moratorios\") ";
			$rsDetalle = phpmkr_query($SQLdETALLE,$conn)or die("Error al insertar detalle factura".phpmkr_error()."sql:".$SQLdETALLE);
			
			?>
            <tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>

        <td valign="top">
        <!--CC-->
        <?php echo $x_identificador_registro; ?>
        </td>
        <td  valign="top">
          <!--CC-->
          <?php echo $x_clave_registro; ?>
        </td>
        <td  valign="top">
          <!--CC-->
          <?php echo $x_cantidad; ?>
        </td>
        <td  valign="top">
          <!--CC-->
          <?php echo $x_importe; ?>
        </td>
        <td  valign="top">
          <!--CC-->
          <?php echo $x_tasa_iva ?>
        </td>
        <td  valign="top">
          <!--CC-->
          <?php echo $x_clave_cliente ?>
        </td>
        </tr>
            <?php
			
			}	
			
		if($x_interes_moratorio_penalizacion > 0){
			// se agrega un renglo para este registro
			$x_identificador_registro = "D";
			//$x_clave_registro = "=CONCATENAR(\"$x_Comisión_Cobranza\",REPETIR(\" \",6-LARGO(\"$x_Comisión_Cobranza\")))";
 $x_Intereses_Ordinarions;
			$x_cantidad = "01";
			//$x_cantidad = "=CONCATENAR(\"$x_cantidad\",REPETIR(\" \",2-LARGO(\"$x_cantidad\")))";
			$x_importe = round($x_interes_moratorio_penalizacion, 2);
			$x_tem_imp = $x_importe;
			$x_eplo_impo = explode(".",$x_tem_imp);
			$x_cent = $x_eplo_impo[1];
			if(empty($x_cent)){
				$x_importe = $x_importe.".00"; 
				$x_cent = ".00";
				}
			if(strlen($x_cent) < 2){
				$x_importe = $x_importe."0";
				}
			//$x_importe = "=CONCATENAR(REPETIR(\" \",8-LARGO($x_importe)),$x_importe)";
			//$x_importe = "=TEXTO($x_importe,\"$00000,00\")";
			//$x_importe = "=CONCATENAR(REPETIR(\" \",8-LARGO(TEXTO($x_importe,\"0.00\"))),TEXTO($x_importe,\"0.00\"))";
			if($x_iva_credito == 1){
			$x_tasa_iva = "16.00";
			}else{
			$x_tasa_iva = "00.00";	
				}
				
			//$x_tasa_iva = "=CONCATENAR(\"$x_tasa_iva\",REPETIR(\" \",5-LARGO(\"$x_tasa_iva\")))";
			
			$x_tasa_iva = str_pad($x_tasa_iva, 5, " ",STR_PAD_LEFT);
			$x_importe = str_pad($x_importe, 8, " ",STR_PAD_LEFT);
			$x_clave_registro = str_pad($x_Comisión_Cobranza, 6, " ");
			fputs($file,$x_identificador_registro);
			fputs($file,$x_clave_registro);
			fputs($file,$x_cantidad);
			fputs($file,$x_importe);
			fputs($file,$x_tasa_iva);
			fputs($file,"\r\n");
			$SQLdETALLE = "INSERT INTO `detalle_factura` (`detalle_factura_id`, `folio_factura`, `credito_id`, `tasa`, `monto`, `descripcion`) ";
			$SQLdETALLE .= "  VALUES (NULL, $x_folio_siguiente_factura, $x_credito_id, $x_tasa_iva, $x_importe, \"$x_Comisión_Cobranza\") ";
			$rsDetalle = phpmkr_query($SQLdETALLE,$conn)or die("Error al insertar detalle factura".phpmkr_error()."sql:".$SQLdETALLE);
			
			
			?>
            <tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>

        <td  valign="top">
        <!--CC-->
        <?php echo $x_identificador_registro; ?>
        </td>
        <td  valign="top">
          <!--CC-->
          <?php echo $x_clave_registro; ?>
        </td>
        <td  valign="top">
          <!--CC-->
          <?php echo $x_cantidad; ?>
        </td>
        <td  valign="top">
          <!--CC-->
          <?php echo $x_importe; ?>
        </td>
        <td  valign="top">
          <!--CC-->
          <?php echo $x_tasa_iva ?>
        </td>
        <td valign="top">
          <!--CC-->
          <?php echo $x_clave_cliente ?>
        </td>
        </tr>
            <?php
			
			}	
		
		}
		
		
		
		
		
		
		// Set row color
		$sItemRowClass = " class=\"ewTableRow\"";
		$sListTrJs = " ";

		// Display alternate color for rows
		if ($nRecCount % 2 <> 1) {
			$sItemRowClass = " class=\"ewTableAltRow\"";
		}
			
			
			
			
?>

<?php
			
	}
}
?>
</table>

<?php


?>
<?php if ($sExport == "") { ?>
<?php if ($nRecActual > 0) { ?>
<p>
  <?php } ?>
  <?php } ?>
</p>
</form>
<?php }else{ ?>
No hay datos que mostrar.
<?php } ?>
<?php



$sqlArchivo = "INSERT INTO `factura_archivo_txt` (`factura_archivo_txt_id`, `fecha`, `nombre`, `tipo_archivo`) VALUES (NULL, \"$x_todayy\", \"LISTADO_FACTURAS_$x_todayy.txt\", '2');";
$rsArchivo = phpmkr_query($sqlArchivo,$conn)or die ("Error al insertar los archivos".phpmkr_error()."sql:".$sqlArchivo);
// Close recordset and connection
phpmkr_free_result($rs);
phpmkr_db_close($conn);


function limpiatxt($x_txt){

$x_txt = str_replace("."," ",$x_txt);
$x_txt = str_replace(","," ",$x_txt);
$x_txt = str_replace("/"," ",$x_txt);
$x_txt = str_replace("#"," ",$x_txt);
$x_txt = str_replace("&"," ",$x_txt);
$x_txt = str_replace("$"," ",$x_txt);
$x_txt = str_replace(":"," ",$x_txt);
$x_txt = str_replace("("," ",$x_txt);
$x_txt = str_replace(")"," ",$x_txt);
$x_txt = str_replace("-"," ",$x_txt);
$x_txt = str_replace("á","a",$x_txt);
$x_txt = str_replace("é","e",$x_txt);
$x_txt = str_replace("í","i",$x_txt);
$x_txt = str_replace("ó","o",$x_txt);
$x_txt = str_replace("ú","u",$x_txt);
$x_txt = str_replace("Á","A",$x_txt);
$x_txt = str_replace("É","E",$x_txt);
$x_txt = str_replace("Í","I",$x_txt);
$x_txt = str_replace("Ó","O",$x_txt);
$x_txt = str_replace("Ú","U",$x_txt);


$x_txt = str_replace("Ã¡","a",$x_txt);
$x_txt = str_replace("Ã©","e",$x_txt);
$x_txt = str_replace("Ã­","i",$x_txt);
$x_txt = str_replace("Ã³","o",$x_txt);
$x_txt = str_replace("Ãº","u",$x_txt);
$x_txt = str_replace("Ã±","ñ",$x_txt);


return $x_txt;
 
}

fclose($file);
?>
<?php if ($sExport <> "word" && $sExport <> "excel") { ?>
</body>
</html>
<?php } ?>
