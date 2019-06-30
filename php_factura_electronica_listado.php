<?php set_time_limit(0); ?>
<?php session_start(); ?>
<?php ob_start(); ?> 
<?php
include ("db.php");
include("phpmkrfn.php");
include("fcaturacion_electronica_xml.php");
include("facturacion_electronica_cadena_original.php");
include("fcaturacion_electronica_pdf.php");
include("amount2txt.php");
include("factura_xml.php");
include_once('fpdf.php'); //para crear el pdf
include_once('numero_a_letra.php'); // funcion para convertir el total a letra


#######################################################################################################################
#######################################################################################################################
#######################################################################################################################
###################              GENERA LISTADO DE FACTURA MENSUALMENTE                     ###########################
#######################################################################################################################
#######################################################################################################################

// 1.-el proceso se corre el primer dia del mes en la madrugada 
// 2.-la fecha de inicio debe ser el dia uno del mes anterios 
// 3.-la fecha fin debe ser el ultimo dia del mes aterior
//ejemplo hoy es 01/feb/2014
// fecha incio 01/01/2014 fecha fin  31/01/2014

// 4.- se eleccionan los mosntos de los vencimeinto devengados del mes que acaba de terminar 
// la factura sale por los montos que se devengaron, no por los montos que se pagaron..
// que pasa cuando se reporta un monto por ejemplo de una comision y despues se condona la comision?
// el monto ya se reporto, pero al final la comision(o penalizacion) se elimino del estado de cuenta porque se condono
// 4.1 solo aplica para credito activos
// 4.2 Seleccionamos los datos del cliente
// 4.3 intereses devengados




$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$x_fecha =  date("Y-m-d"); //
$x_fecha = "2014-02-01";

$x_mes_pasao = date("Y-m-d", mktime(0,0,0, date("m")-1, date("d"), date("Y")));
$x_fecha_inicio = 


$mañana        = date("Y-m-d", mktime( 0,0,0, date("m")  , date("d")+1, date("Y")));
$mes_anterior  = date("Y-m-d", mktime(0, 0, 0, date("m")-1, date("d"),   date("Y")));
$año_siguiente = date("Y-m-d",  mktime(0, 0, 0, date("m"),   date("d"),   date("Y")+1));
echo "maana ".$mañana ."<br> mes anterior ".$mes_anterior. "<br> anio sig ".$año_siguiente."<br>";


$sqld = "SELECT DATE_SUB('$x_fecha', INTERVAL 1 MONTH )AS mes_pasado";
$rsd = phpmkr_query($sqld, $conn) or die ("Error al seleccionar el ultimo dia del mes :".phpmkr_error($sqld). "Query :".$sqld);
$rowd =phpmkr_fetch_array($rsd);
$x_fecha_mes_pasado = $rowd["mes_pasado"];
#echo "mes pasdo".$x_ultimo_dia_mes."<br>";
$sqld = "SELECT LAST_DAY('$x_fecha_mes_pasado') AS ultimo_dia";
$rsd = phpmkr_query($sqld, $conn) or die ("Error al seleccionar el ultimo dia del mes :".phpmkr_error($sqld). "Query :".$sqld);
$rowd =phpmkr_fetch_array($rsd);
$x_ultimo_dia_mes = $rowd["ultimo_dia"];
$x_p = explode("-",$x_ultimo_dia_mes);
$x_dia = $x_p["2"];
$x_mes = $x_p["1"];
$x_anio = $x_p["0"];
#echo "ultimo dia".$x_dia."<br>";
$x_primer_dia_mes = "01";
$x_fecha_inicio = $x_anio."-".$x_mes."-".$x_primer_dia_mes;
$x_fecha_fin = $x_anio."-".$x_mes."-".$x_dia;

echo "incio".$x_fecha_inicio."<br>";
echo "fin".$x_fecha_fin."<br>";

//$x_fecha_fin  = "2013-12-31";

$sqlCreditoFActuraMes = "SELECT credito_id FROM vencimiento WHERE fecha_vencimiento >= \"$x_fecha_inicio\" and fecha_vencimiento <= \"$x_fecha_fin\" group by credito_id ";
	$rsCreditoFacturaMes = phpmkr_query($sqlCreditoFActuraMes,$conn) or die("error en creditos facturados".phpmkr_error());
	while($rowCreditoFacturaMes = phpmkr_fetch_array($rsCreditoFacturaMes)){
		$x_lista_credito_factura_mes = $x_lista_credito_factura_mes .$rowCreditoFacturaMes["credito_id"].", ";
		}
		
		$x_lista_credito_factura_mes = trim($x_lista_credito_factura_mes,", ");
//4.1

$sqlCreditoActivo =  "SELECT credito_id, iva, tasa FROM credito WHERE credito_status_id = 1  and credito_id not in (15, 1064,2499 ) and credito_id in ($x_lista_credito_factura_mes)"; 
$rsCreditoActivo = phpmkr_query($sqlCreditoActivo, $conn) or die ("Error al seleccionar los credito activos".phpmkr_error()."sql:".$sqlCreditoActivo);
while ($rowCReditoActivo = phpmkr_fetch_array($rsCreditoActivo)){
	$x_credito_id = $rowCReditoActivo["credito_id"];
	$x_iva = $rowCReditoActivo["iva"];	
	$x_tasa = $rowCReditoActivo["tasa"];
	
	
	
	
	
	// si el iva  == 2 significa que no se le cobro iva; means nos dia su RFC
	echo "<br> credito_ id lista ".$x_lista_credito_factura_mes."";
	
	
	



	
				
		$sqlNumeroEdo = "SELECT MAX(numero) as ultimo_estado FROM estado_cuenta WHERE credito_id = $x_credito_id ";
		$rsNumeroEdo  = phpmkr_query($sqlNumeroEdo,$conn) or die ("Error al seleccionar los estados de cuenta ".phpmkr_error()."sql:".$sqlNumeroEdo);
		$rowNumeroEdo = phpmkr_fetch_array($rsNumeroEdo);
		$x_numero_estado_cuenta =  $rowNumeroEdo["ultimo_estado"];		
		// se guarda el numero maximo del estado de cuenta
		
		
		// se guarda la variable del estado de cuenta para agragarlo al archivo XML
		$x_numero_actual = $x_numero_estado_cuenta + 1;
		
		$x_el_estado_de_cuenta = "SELECT * FROM estado_cuenta WHERE credito_id = $x_credito_id  order by estado_cuenta_id DESC LIMIT 0,1 ";
		$rsEstadoCuenta = phpmkr_query($x_el_estado_de_cuenta,$conn)or die ("Error al seleccionar el esatdo de cuenta".phpmkr_error()."sql:".$x_el_estado_de_cuenta);
		$rowEstadoCuenta = phpmkr_fetch_array($rsEstadoCuenta);
		$x_saldo_final = $rowEstadoCuenta["saldo_final"];
		
		if($x_saldo_final <1 ){
			// no hay saldo final registrado; se calcula el saldo final
			//
			
			
			}else{
				
					#saldo promed		
		// el saldo promedio se calcula segun las formulas 		
		// el capital presta do  mas los intereses del mes actual menos los pagos realizados por eñl cliente en el periodo corresponiente
		// en el siguestado de cuenta el acapital presta solo corresponde al el mosnto del prestamo menos el saldo final.
		// ultimo dia del mes = 
		
		$sqlUltimoDiaMes = " SELECT LAST_DAY('2014-01-05') AS  ultimo_dia_mes ";
		$rsUltimoDiaMes = phpmkr_query($sqlUltimoDiaMes, $conn) or die ("Error al seleccionar el ultomo dia del mes".phpmkr_error()."sql;".$sqlUltimoDiaMes);
		$rowUltimoDiaMes = phpmkr_fetch_array($rsUltimoDiaMes);
		$x_dias_periodo = $rowUltimoDiaMes["ultimo_dia_mes"]; 
		$x_dias = explode("-",$x_dias_periodo);
		$x_dias_periodo = $x_dias[2];
		
		// hacemos el calculo del sldo promedio
		
		
		$sqlVencimientosPagados = "SELECT SUM(total_venc) as total_pagado FROM vencimiento WHERE credito_id = $x_credito_id and vencimiento_status_id = 2 and fecha_vencimiento <= \"$x_fecha_fin\" and fecha_vencimiento >= \"$x_fecha_inicio\" ";
	$rsVencimientosPagados =  phpmkr_query($sqlVencimientosPagados, $conn)or die ("Error al seleccionar los pagados".phpmkr_error());
	$rowVencimientosPagados = phpmkr_fetch_array($rsVencimientosPagados);
	$x_total_pagado =  $rowVencimientosPagados["total_pagado"];
	echo "<BR>Total pagado  ".$x_total_pagado;
	
	$sqlVenicmientosInteresesDevengados = "SELECT SUM(interes) AS interes_devengado FROM vencimiento WHERE credito_id = $x_credito_id and fecha_vencimiento <= \"$x_fecha_fin\" and fecha_vencimiento >= \"$x_fecha_inicio\"";
	$rsVencimientosInteresesDevengados = phpmkr_query($sqlVenicmientosInteresesDevengados,$conn)or die ("Error al seleciconar devengados ".phpmkr_error()."sql:".$sqlVenicmientosInteresesDevengados);
	$rowVencimientosInteresesDevengados = phpmkr_fetch_array($rsVencimientosInteresesDevengados);
	$x_intereses_devengados = $rowVencimientosInteresesDevengados["interes_devengado"];
	
	echo "INTERESES DEVENGADOS ".$x_intereses_devengados."<BR>";
	
	$sqlVenicmientosIvaDevengados = " SELECT SUM(iva) AS iva_devengado FROM vencimiento WHERE credito_id = $x_credito_id and fecha_vencimiento <= \"$x_fecha_fin\" and fecha_vencimiento >= \"$x_fecha_inicio\"";
	$rsVencimientosIvaDevengados = phpmkr_query($sqlVenicmientosIvaDevengados,$conn)or die ("Error al seleciconar devengados ".phpmkr_error()."sql:".$sqlVenicmientosIvaDevengados);
	$rowVencimientosIvaDevengados = phpmkr_fetch_array($rsVencimientosIvaDevengados);
	$x_iva_devengados = $rowVencimientosIvaDevengados["iva_devengado"];
	echo "iva  ". $x_iva_devengados."<br>";
	
	$sqlVenicmientosMoraDevengados = " SELECT SUM(interes_moratorio) AS interes_moratorio_devengado FROM vencimiento WHERE credito_id = $x_credito_id and fecha_vencimiento <= \"$x_fecha_fin\" and fecha_vencimiento >= \"$x_fecha_inicio\"";
	$rsVencimientosMoraDevengados = phpmkr_query($sqlVenicmientosMoraDevengados,$conn)or die ("Error al seleciconar devengados ".phpmkr_error()."sql:".$sqlVenicmientosMoraDevengados);
	$rowVencimientosMoraDevengados = phpmkr_fetch_array($rsVencimientosMoraDevengados);
	$x_interes_moratorio_devengado = $rowVencimientosMoraDevengados["interes_moratorio_devengado"];
	
	echo "mora ".$x_interes_moratorio_devengado."<br>";
	
	
	$sqlVenicmientosIvaMoraDevengados = " SELECT SUM(iva_mor) AS iva_moratorio_devengado FROM vencimiento WHERE credito_id = $x_credito_id and fecha_vencimiento <= \"$x_fecha_fin\" and fecha_vencimiento >= \"$x_fecha_inicio\"";
	$rsVencimientosIvaMoraDevengados = phpmkr_query($sqlVenicmientosIvaMoraDevengados,$conn)or die ("Error al seleciconar devengados ".phpmkr_error()."sql:".$sqlVenicmientosIvaMoraDevengados);
	$rowVencimientosIvaMoraDevengados = phpmkr_fetch_array($rsVencimientosIvaMoraDevengados);
	$x_iva_moratorio_devengado = $rowVencimientosIvaMoraDevengados["iva_moratorio_devengado"];
	 echo "mora iva ".$x_iva_moratorio_devengado;
	
	$x_saldo_final = ($x_saldo_final + ($x_intereses_devengados + $x_iva_devengados + $x_interes_moratorio_devengado + $x_iva_moratorio_devengado)) - $x_total_pagado;
	$x_saldo_promedio =  $x_saldo_final / $x_dias_periodo;	
	$x_tipo_cuenta = 1;	
	$x_tasa_bruta = $x_tasa;
		
					
		// insertamos el tabla de estad de cuenta...
		$sqlInsertaEstadoCuenta = "INSERT INTO `estado_cuenta` (`estado_cuenta_id`, `credito_id`, `fecha`, `numero`, `saldo_promedio`, `tasa_bruta`, `dias_periodo`, ";
		$sqlInsertaEstadoCuenta .= "  `saldo_final`, `tipo_cuenta` ) ";		
		$sqlInsertaEstadoCuenta .= " VALUES (NULL, $x_credito_id, \"$x_fecha\", $x_numero_actual, $x_saldo_promedio, $x_tasa_bruta, $x_dias_periodo, $x_saldo_final , $x_tipo_cuenta) ";
		
		echo "<br>".$sqlInsertaEstadoCuenta."<br>";
		$rsInsertaEstadoCuenta = phpmkr_query($sqlInsertaEstadoCuenta, $conn) or die ("Erro al seleccionar el estado de cuenta".phpmkr_error()."sql:".$sqlInsertaEstadoCuenta);
		 		
		$x_estado_cuenta = mysql_insert_id();
				
				}
		
		#$x_saldo_promedio = principal + interesese correspondientes al periodo - pagos realizados
		
		//SELECCIONAMOS EL PRINCIPAL
		
		$SQLmONTOoTORGADO = "SELECT * FROM credito WHERE credito_id = $x_credito_id ";
		$rsMontoOtorgado = phpmkr_query($SQLmONTOoTORGADO, $conn) or die ("Error al seleccionar el monto".phpmkr_error().$SQLmONTOoTORGADO);
		
		
		
	
		
		
		
		// se relaciona el estado de cuenta con el  la factura
		$sqlInserta = "INSERT INTO `factura_cfd` (`factura_cfd_id`, `factura_id`, `archivo`, `fecha`) ";
		$sqlInserta .= " VALUES (NULL, $x_estado_cuenta, '2', '2');   ";
		//$rsConsulta = phpmkr_query($sqlInserta,$conn) or die ("Error al seleccionar los modulos ".phpmkr_error()."sql:".$sqlInserta);
		//$rowConsulta = phpmkr_fetch_array($rs);
		
		
		
		
		// se relaciona el estado de cuenta con el  la factura
		$sqlInserta = "INSERT INTO `financ13_esf`.`factura_pdf` (`factura_pdf_id`, `factura_id`, `archivo`, `fecha`) ";
		$sqlInserta .= " VALUES (NULL, $x_estado_cuenta, '2', '2')";
		//$rsConsulta = phpmkr_query($sqlInserta,$conn) or die ("Error al seleccionar los modulos ".phpmkr_error()."sql:".$sqlInserta);
		//$rowConsulta = phpmkr_fetch_array($rs);
		
		
	
	}





