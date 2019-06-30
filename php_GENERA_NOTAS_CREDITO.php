<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php
// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$x_hoy = date("Y-m-d");
$x_hoy = "2015-03-31";
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
$x_todayy = "2015-03-31";

//$sSql = "SELECT credito.credito_id  FROM vencimiento, credito  WHERE credito.credito_id = vencimiento.credito_id and vencimiento.fecha_vencimiento >= \"$x_primer_dia_mes\"  and vencimiento.fecha_vencimiento <= \"$x_ultimo_dia_mes\" and credito.credito_id not in(1489)  and (credito.credito_status_id = 1 || credito.credito_status_id = 3) GROUP BY credito_id ";

//NOTAS DE CREDITO
#status_id = 1 = entra  como nota de credito



//if($x_hoy == $x_ultimo_dia_mes){
$file=fopen("facturas_notas_clientes_txt/LISTADO_NOTAS_$x_todayy.txt","w+") or die("Problemas");
$sSql = " SELECT * FROM condonacion WHERE fecha_registro  >= \"$x_primer_dia_mes\" and fecha_registro <= \"$x_ultimo_dia_mes\" and status_id = 1 GROUP BY credito_id ";
$rs = phpmkr_query($sSql,$conn) or die ("Erro al seleciconar los vencimientos de notas de credito".phpmkr_error()."sql :".$sSql);
while (($rowCreditosFactura = @phpmkr_fetch_array($rs))) {
	
		
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
	$sqlVencimientosMes = "SELECT SUM(interes_moratorio) as mora1 FROM vencimiento JOIN condonacion on condonacion.vencimiento_id = vencimiento.vencimiento_id WHERE vencimiento.credito_id = $x_credito_id  and vencimiento_num > 2000 and vencimiento_num < 3000  and vencimiento.vencimiento_status_id in( 8,1,3) and condonacion.facturado = 0 order by vencimiento.vencimiento_id ";
	$rsVencimientosMes = phpmkr_query($sqlVencimientosMes,$conn) or die ("Error la seleccionar los vencimitos del mes".phpmkr_error()."sql :".$sqlVencimientosMes);
	
	
	$rowVencimientosMes = phpmkr_fetch_array($rsVencimientosMes);
	echo "select 1".$sqlVencimientosMes."<br>";
	// aqui selecciono las comiones por cobranza
	$x_interes_moratorio_penalizacion = 0;				
	$x_interes_moratorio_penalizacion = $rowVencimientosMes["mora1"];	
	$x_total_penalizacion =  $x_interes_moratorio_penalizacion;
	
		
		
	//comision por cobraza	
	$sqlVencimientosMes = "SELECT SUM(interes_moratorio) as mora2 FROM vencimiento JOIN condonacion on condonacion.vencimiento_id = vencimiento.vencimiento_id WHERE vencimiento.credito_id = $x_credito_id = $x_credito_id  and vencimiento_num > 3000 and  vencimiento.vencimiento_status_id in ( 8,1,3,2) and condonacion.facturado = 0 order by vencimiento.vencimiento_id ";
	$rsVencimientosMes = phpmkr_query($sqlVencimientosMes,$conn) or die ("Error la seleccionar los vencimitos del mes".phpmkr_error()."sql :".$sqlVencimientosMes);
	
	// aqui selecciono las comiones por cobranza
	$rowVencimientosMes = phpmkr_fetch_array($rsVencimientosMes);
	$x_interes_moratorio_penalizacion = 0;		
	$x_interes_moratorio_comision = $rowVencimientosMes["mora2"];				
	$x_total_comision = $x_interes_moratorio_comision;
		
		
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
		 $SQLiMPIDEdOBLE = 
		
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
			
			
			}	
		
		}
		

			
	}



$sqlArchivo = "INSERT INTO `factura_archivo_txt` (`factura_archivo_txt_id`, `fecha`, `nombre`, `tipo_archivo`) VALUES (NULL, \"$x_hoy\", \"LISTADO_NOTAS_$x_todayy.txt\", '3');";
$rsArchivo = phpmkr_query($sqlArchivo,$conn)or die ("Error al insertar los archivos".phpmkr_error()."sql:".$sqlArchivo);


// Close recordset and connection
phpmkr_free_result($rs);
phpmkr_db_close($conn);
fclose($file);
//}// hoy es el ultimo de mes el proceso se ejecuta para generar la lista de notas de credito del mes que paso 
?>