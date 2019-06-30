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






// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

$x_hoy = date("Y-m-d");
//$x_hoy = "2014-01-31";

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


$sSql = " SELECT * FROM condonacion WHERE fecha_registro  >= \"$x_primer_dia_mes\" and fecha_registro <= \"$x_ultimo_dia_mes\" and status_id = 1 GROUP BY credito_id ";


echo $sSql; // Uncomment to show SQL for debugging
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
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$nTotalRecs = phpmkr_num_rows($rs);
if ($nDisplayRecs <= 0) { // Display All Records
	$nDisplayRecs = $nTotalRecs;
}
$nStartRec = 1;
?>


<?php if ($nTotalRecs > 0)  { ?>
<form method="post" name="principal" action="">
<input type="hidden" name="credito_id" value="<?php echo $x_credito_id; ?>"  />

<?php if ($sExport == "") { ?>
<a href="php_factura_notas_credito.php?export=excel">Exportar a Excel</a>
<br /><br />
<?php } ?>


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

		


#$x_ultimo_folio = 0;
$nRecActual = 0;

$file=fopen("LISTADO_NOTAS_zulma_$x_todayy.txt","w+") or die("Problemas");
$x_contador = 1;
//echo "LISTADO_FACTURAS_$x_todayy.txt<br>";
while (($rowCreditosFactura = @phpmkr_fetch_array($rs)) && ($nRecCount < $nStopRec)) {

	$nRecCount = $nRecCount + 1;
	if ($nRecCount >= $nStartRec) {
		$nRecActual++;	
	echo "contador = ".$x_contador."<br>";	
	$x_credito_id = $rowCreditosFactura["credito_id"];
	echo "credito_id = ".$x_credito_id."<br>";
	// seleccionamos los vencimiens de cada credito que corresponden al mes a facturar	
	// SELECCIOAMOS TODAS LAS CONDONACIONES DEL CREDITO	
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
		
		// penalizaciones		
	$sqlVencimientosMes = "SELECT * FROM vencimiento JOIN condonacion on condonacion.vencimiento_id = vencimiento.vencimiento_id WHERE vencimiento.credito_id = $x_credito_id  and vencimiento_num > 2000 and vencimiento_num < 3000  and vencimiento.vencimiento_status_id in( 8,1,3) and condonacion.facturado = 0 order by vencimiento.vencimiento_id ";
	$rsVencimientosMes = phpmkr_query($sqlVencimientosMes,$conn) or die ("Error la seleccionar los vencimitos del mes".phpmkr_error()."sql :".$sqlVencimientosMes);
	
	
	
	echo "select 1".$sqlVencimientosMes."<br>";
	// aqui selecciono las comiones por cobranza
	$x_interes_moratorio_penalizacion = 0;
	while($rowVencimientosMes = phpmkr_fetch_array($rsVencimientosMes)){
		$x_vencimiento_id = $rowVencimientosMes["vencimiento_id"];		
		$x_interes_moratorio_penalizacion = $rowVencimientosMes["interes_moratorio"];		
		$x_iva_mor_penalizacion = $rowVencimientosMes["iva_mor"];	
		
		$x_total_penalizacion = $x_total_penalizacion +  $x_interes_moratorio_penalizacion;
	}
		
		
	//comision por cobraza	
	$sqlVencimientosMes = "SELECT * FROM vencimiento JOIN condonacion on condonacion.vencimiento_id = vencimiento.vencimiento_id WHERE vencimiento.credito_id = $x_credito_id = $x_credito_id  and vencimiento_num > 3000 and  vencimiento.vencimiento_status_id in ( 8,1,3,2) and condonacion.facturado = 0 order by vencimiento.vencimiento_id ";
	$rsVencimientosMes = phpmkr_query($sqlVencimientosMes,$conn) or die ("Error la seleccionar los vencimitos del mes".phpmkr_error()."sql :".$sqlVencimientosMes);
	
	// aqui selecciono las comiones por cobranza
	$x_interes_moratorio_penalizacion = 0;
	while($rowVencimientosMes = phpmkr_fetch_array($rsVencimientosMes)){
		$x_vencimiento_id = $rowVencimientosMes["vencimiento_id"];		
		$x_interes_moratorio_comision = $rowVencimientosMes["interes_moratorio"];		
		$x_iva_mor_comision = $rowVencimientosMes["iva_mor"];		
		$x_total_comision = $x_total_comision +  $x_interes_moratorio_penalizacion;
	}	
		
		 $x_Intereses_Ordinarions = "INTORD";	
		 $x_Intereses_Moratorios = "INTMOR";
		 $x_Comisión_Cobranza = "COMCOB";
		 $x_Comison_Gestoria = "COMGTO";
		 $x_codonacion_ordinario = "CONIOR"; // ordinario no es posible condonar
		 $x_Condonacion_mora = "CONIMO"; // penalizaciones
		 $x_Condonacion_comision = "CONCCO"; // comision por cobranza
		 $x_condonacion_gestoria = "CONCGT";
		if(($x_total_penalizacion > 0) || ($x_total_comision > 0)){			
		// si hay monto para facturar
		// se busca el folio siguiente			
		//seleccionamos el ultim folio que se dio 
		$sqlFolio = "SELECT MAX(numero) AS ultimo FROM  folio_nota_credito ";
		$rsFolio = phpmkr_query($sqlFolio,$conn)or die("Error al seleccionar las fact".phpmkr_error().$sqlFolio);
		$rowFolio = phpmkr_fetch_array($rsFolio);
		$x_ultimo_folio = $rowFolio["ultimo"];
		$x_folio_siguiente_nota = $x_ultimo_folio + 1;
		$x_ultimo_folio = $x_folio_siguiente_nota;
		
		$x_fecha_folio = date("Y-m-d");
		// insertamos registro en folio_factura
		$sqlinsertFolio = "INSERT INTO `folio_nota_credito` (`folio_nota_credito_id`, `numero`,`credito_id`, `fecha`) ";
		$sqlinsertFolio .=" VALUES (NULL, $x_folio_siguiente_nota, $x_credito_id, \"$x_fecha_folio\") " ;
		$rsInsertFolio = phpmkr_query($sqlinsertFolio,$conn) or die("error al insertar en folio".phpmkr_error()."sql ".$sqlinsertFolio);
		$x_folio_nota_credito_id = mysql_insert_id();	
			// aqui se llena la primer fila
			
		echo 	$sqlinsertFolio."<br>";			
		#$x_identificador_registro = "F";
		$x_identificador_registro = "N";
		$x_fecha_factura = $x_anio.$x_mes.$x_dia_fin;
		#$x_serie_docto = "FA";
		$x_serie_docto = "NC";
		#$x_serie_docto ="=CONCATENAR(\"$x_serie_docto\",REPETIR(\" \",6-LARGO(\"$x_serie_docto\")))";
		$x_serie_docto = str_pad($x_serie_docto, 6, " ");
		#$x_folio = "=CONCATENAR(REPETIR(\" \",4-LARGO($x_folio_siguiente_factura)),$x_folio_siguiente_factura)";
		$x_folio = str_pad($x_folio_siguiente_nota, 4, " ",STR_PAD_LEFT);
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
		
		
		echo "total penalizacion ".$x_total_penalizacion." total ".$x_total_comision."<br>";
		
		if($x_total_penalizacion > 0){
			
			echo "entra en penalizacion ";
			// se agrega un renglo para este registro
			$x_identificador_registro = "D";
			//$x_clave_registro = "=CONCATENAR(\"$x_Intereses_Moratorios\",REPETIR(\" \",6-LARGO(\"$x_Intereses_Moratorios\")))";
			$x_Intereses_Ordinarions;
			$x_cantidad = "01";
			//$x_cantidad = "=CONCATENAR(\"$x_cantidad\",REPETIR(\" \",2-LARGO(\"$x_cantidad\")))";
			$x_importe = round($x_total_penalizacion, 2);
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
			$x_clave_registro = str_pad($x_Condonacion_mora, 6, " ");
			fputs($file,$x_identificador_registro);
			fputs($file,$x_clave_registro);
			fputs($file,$x_cantidad);
			fputs($file,$x_importe);
			fputs($file,$x_tasa_iva);					
	  		fputs($file,"\r\n");
			
			$SQLdETALLE = "INSERT INTO `detalle_nota_credito` (`detalle_nota_credito_id`, `folio_nota_credito_id`, `credito_id`, `tasa`, `monto`, `descripcion`) ";
			$SQLdETALLE .= "  VALUES (NULL, $x_folio_nota_credito_id, $x_credito_id, $x_tasa_iva, $x_importe, \"$x_Condonacion_mora\") ";
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
			
		if($x_total_comision > 0){
			
			echo "entra en comison <br>";
			// se agrega un renglo para este registro
			$x_identificador_registro = "D";
			//$x_clave_registro = "=CONCATENAR(\"$x_Comisión_Cobranza\",REPETIR(\" \",6-LARGO(\"$x_Comisión_Cobranza\")))";
 $x_Intereses_Ordinarions;
			$x_cantidad = "01";
			//$x_cantidad = "=CONCATENAR(\"$x_cantidad\",REPETIR(\" \",2-LARGO(\"$x_cantidad\")))";
			$x_importe = round($x_total_comision, 2);
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
			$x_clave_registro = str_pad($x_Condonacion_comision, 6, " ");
			fputs($file,$x_identificador_registro);
			fputs($file,$x_clave_registro);
			fputs($file,$x_cantidad);
			fputs($file,$x_importe);
			fputs($file,$x_tasa_iva);
					
	  		fputs($file,"\r\n");
			
			$SQLdETALLE = "INSERT INTO `detalle_nota_credito` (`detalle_nota_credito_id`, `folio_nota_credito_id`, `credito_id`, `tasa`, `monto`, `descripcion`) ";
			$SQLdETALLE .= "  VALUES (NULL, $x_folio_nota_credito, $x_credito_id, $x_tasa_iva, $x_importe, \"$x_Condonacion_comision\") ";
			$rsDetalle = phpmkr_query($SQLdETALLE,$conn)or die("Error al insertar detalle factura".pgpmkr_error()."sql:".$SQLdETALLE);
			echo "nota dos".$SQLdETALLE."<br>";
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
<a href="php_factura_electronica_descarga_factura.php?nombre_fichero=<?php echo "LISTADO_NOTAS_$x_todayy.txt"; ?>"  target="_blank"> ARCHIVO DEL MES </a>
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
