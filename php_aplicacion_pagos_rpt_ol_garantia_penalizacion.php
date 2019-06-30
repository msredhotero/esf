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
ini_set("max_execution_time", "0");
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
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=pagos_masivos_no_aplicados.xls');
}
?>


<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php include_once("utilerias/datefunc.php");?>
<?php

$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

$x_fecha_pago = $_GET["x_fecha_pago"];	
$x_carga_id = $_GET["x_carga_id"];
$x_procesado = $_GET["x_procesado"];	

 
$x_banco_id = $_GET["x_banco_id"];
$x_medio_pago_id = $_GET["x_medio_pago_id"]; 
$x_referencia_pago_1 = $_GET["x_referencia_pago_1"];

//echo "fecha :".$x_fecha_pago."<br>";
//echo "banco_id :".$x_banco_id."<br>";
//echo "medio de pago :".$x_medio_pago_id."<br>";
if ($sExport <> "excel") {
if(!LoadPagosNoAplicados($conn)){
	echo "Error no se respaldaron los pagos no aplicados:";
	die();
	
	}else{
		//echo "Total de pagos Pendientes =".$x_pagos_pendientes."-";
		}
		
		
if($_SESSION["aplica_pagos"] == 1){		
if(!AplicaPagos($conn)){
	echo "Error al aplicar los pagos mavivos....";
	die();
}else{
		//echo "Reporte de pagos aplicados:";
		}
		
$_SESSION["aplica_pagos"] = 0;		
}
if(!AddReport($conn)){
	echo "No se guardo el reporte de la transaccion";
	die();
	}
	
}

?>
<?php include ("header.php") ?>
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
<p><span class="phpmaker">
<?php if ($sExport == "") { ?>
Aplicaci&oacute;n Masiva de Pagos (Resultado - <?php echo FormatDateTime($currdate,7);?>)
<?php }else{ ?>
Aplicaci&oacute;n Masiva de Pagos (Resultado - <?php echo FormatDateTime($currdate,7);?>)
<?php } ?>
</span></p>
<p><span class="phpmaker"><?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="php_aplicacion_pagos_rpt.php?export=excel">Exportar a Excel</a>
<?php } ?></span></p>
<?php if ($sExport == "") { ?>
<p>
<?php } ?>
<?php
$sqlSe = "SELECT * FROM reporte_pago_masivo ORDER BY reporte_pago_masivo_id desc ";


$responseQ = phpmkr_query($sqlSe, $conn) or die("error al leer los datos de la tabla reporte".phpmkr_error()."sql:".$sqlSe);
$row = phpmkr_fetch_array($responseQ);
$x_pagos_aplicados = $row["pagos_aplicados"];
$x_importe_aplicado  = $row["pagos_aplicados_importe"];
$x_pagos_NO_aplicados  = $row["pagos_no_palicados"];
$x_importe_NO_aplicado = $row["pagos_no_aplicados_importe"];
$x_pagos_aplicados_correctos = $row["pagos_correctos"];
$x_pagos_aplicados_diferente  = $row["pagos_diferentes"];
$x_pagos_pendientes  = $row["pagos_pendientes"];
$x_no_aplicados_reporte  = $row["detalle_pago_no_aplicado"];

phpmkr_free_result($responseQ);

$t_pa = $x_pagos_aplicados;
//echo "tpa:".$t_pa_1."--";
$t_paM = $x_importe_aplicado;
//echo "tpna".$t_paM_1."";
$t_pna = $x_pagos_NO_aplicados;
$t_pnaM = $x_importe_NO_aplicado;
$t_pac = $x_pagos_aplicados_correctos;
$t_pad = $x_pagos_aplicados_diferente;
$t_pp = $x_pagos_pendientes;
$t_na_r = $x_no_aplicados_reporte;
?>




<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="phpmaker" style="color: Red;"><?php echo $_SESSION["ewmsg"]; ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>



<table width="866" class="phpmaker">

	<tr>
	  <td width="164">&nbsp;</td>
	  <td width="12">&nbsp;</td>
	  <td width="190">&nbsp;</td>
	  <td width="431">&nbsp;</td>
	  </tr>
	<tr>
	  <td bgcolor="#F7F7E6" valign="middle"><?php if ($sExport <> "") { ?>Total de pagos aplicados: <?php }else {?> Total de pagos aplicados:<?php }?></td>
	  <td bgcolor="#F7F7E6"></td>
	  <td bgcolor="#F7F7E6"><?php if ($sExport <> "") { ?><?php echo $t_pa ?><?php }else {?><?php echo ($x_pagos_aplicados_correctos + $x_pagos_aplicados_diferente)?><?php }?></td>
	  <td bgcolor="#F7F7E6"><?php if ($sExport <> "") { ?>Por un monto de:<?php }else {?>Por un monto de:<?php }?> <strong><?php if ($sExport <> "") { ?><?php echo $t_paM ?><?php }else {?><?php echo $x_importe_aplicado ?><?php }?></strong></td>
    </tr>
    <tr>
	  <td bgcolor="#F7F7E6" valign="middle"><?php if ($sExport <> "") { ?>Total de pagos correctos:<?php }else {?>Total de pagos correctos:<?php }?></td>
	  <td bgcolor="#F7F7E6"></td>
	  <td bgcolor="#F7F7E6"><?php if ($sExport <> "") { ?><?php echo $t_pac ?><?php }else {?><?php echo $x_pagos_aplicados_correctos ?><?php }?></td>
	  <td bgcolor="#F7F7E6">&nbsp;</td>
    </tr>
    <tr>
	  <td bgcolor="#F7F7E6" valign="middle"><?php if ($sExport <> "") { ?>Total de pagos diferente:<?php }else {?>Total de pagos diferente:<?php }?></td>
	  <td bgcolor="#F7F7E6"></td>
	  <td bgcolor="#F7F7E6"><?php if ($sExport <> "") { ?><?php echo $t_pad ?><?php }else {?><?php echo $x_pagos_aplicados_diferente ?><?php }?></td>
	  <td bgcolor="#F7F7E6">&nbsp;</td>
    </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td></td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
    </tr>
    <tr>
	  <td bgcolor="#F7F7E6" valign="middle"><?php if ($sExport <> "") { ?>Total de pagos PENDIENTES:<?php }else {?>Total de pagos PENDIENTES:<?php }?></td>
	  <td bgcolor="#F7F7E6"></td>
	  <td bgcolor="#F7F7E6"><?php if ($sExport <> "") { ?><?php echo $t_pp ?><?php }else {?><?php echo $x_pagos_pendientes ?><?php }?></td>
	  <td bgcolor="#F7F7E6">&nbsp;</td>
    </tr>
    <tr>
	  <td>&nbsp;</td>
	  <td></td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
    </tr>
	<tr>
	  <td bgcolor="#F7F7E6" valign="middle"><?php if ($sExport <> "") { ?>Total de pagos <strong>NO</strong> aplicados:<?php }else {?>Total de pagos <strong>NO</strong> aplicados:<?php }?></td>
	  <td bgcolor="#F7F7E6"></td>
	  <td bgcolor="#F7F7E6"><?php if ($sExport <> "") { ?><?php echo $t_pna ?><?php }else {?><?php echo $x_pagos_NO_aplicados ?><?php }?></td>
	  <td bgcolor="#F7F7E6"><?php if ($sExport <> "") { ?>Por un monto de: <strong><?php echo $t_pnaM ?><?php }else {?><?php echo $x_importe_NO_aplicado ?><?php }?></strong></td>
    </tr>
	<tr>
	  <td><?php if ($sExport <> "") { ?>REFERENCIA<?php }?></td>
	  <td></td>
	  <td><?php if ($sExport <> "") { ?>CAUSA<?php } else {?>REFERENCIA<?php }?></td>
	  <td><?php if ($sExport == "") { ?>CAUSA<?php }?></td>
	  </tr>
     
	<tr>
	  <td>&nbsp;</td>
	  <td></td>
	  <td colspan="2"><?php if ($sExport <> "") { ?><?php echo $t_na_r ?><?php }else {?><?php echo "<table>". $x_no_aplicados_reporte. "</table>" ?><?php }?></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td></td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>
	</table>



<?php

// Close recordset and connection
phpmkr_db_close($conn);
?>
<?php if ($sExport <> "word" && $sExport <> "excel") { ?>
<?php include ("footer.php") ?>
<?php } ?>
<?php



function LoadPagosNoAplicados($conn){
	$x_pagos_no_aplicados = true;
	$sql = "SELECT * FROM masiva_pago_2  WHERE no_aplicar_pago = 1";
	$response = phpmkr_query($sql,$conn);
	
	if(!$response){
		$x_pagos_no_aplicados = false;
		echo phpmkr_error() . '<br>SQL: ' . $sSql;
		}
		$x_pagos_pendientes = 0;
	while ($row = phpmkr_fetch_array($response)){
			$x_carga_folio_id = $row["carga_folio_id"];
			$x_fecha_carga = $row["fecha_carga"];			
			$x_aplicacion_status_id = $row["aplicacion_status_id"];
			$x_ref_pago = $row["ref_pago"];
			$x_nombre_cliente = $row["nombre_cliente"];
			$x_numero_cliente = $row["numero_cliente"];
			$x_importe = doubleval($row["importe"]);
			$x_fecha_movimiento = $row["fecha_movimiento"];
			$x_nombre_archivo = $row["nombre_archivo"];
			$GLOBALS["x_nombre_archivo"]=  $row["nombre_archivo"];
			$x_sucursal_id = $row["sucursal_id"];
			$x_uploaded_file_id = $row["uploaded_file_id"];
			$x_no_aplicar_pago = $row["no_aplicar_pago"];
			
			
			
			$sqlPP = "INSERT INTO masiva_pago_pendiente";
			$sqlPP .= " VALUES(0,$x_carga_folio_id ,\"$x_fecha_carga\",$x_aplicacion_status_id,\"$x_ref_pago\",\"$x_nombre_cliente\",$x_numero_cliente,$x_importe,\"$x_fecha_movimiento\",";
			$sqlPP .= "\"$x_nombre_archivo\",$x_sucursal_id,$x_uploaded_file_id,$x_no_aplicar_pago)";
			$resposeA = phpmkr_query($sqlPP ,$conn);
			if(!$resposeA ){
				$x_pagos_no_aplicados = false;
				echo phpmkr_error() . '<br>SQL: ' . $sqlPP;
				}
			
		
		
		$x_pagos_pendientes ++;
		}// fin while
	$GLOBALS["x_pagos_pendientes"] = $x_pagos_pendientes; 
	return $x_pagos_no_aplicados;
	}// fin LoadPagosNoAplicados


function calculaTotalVencimientos( $credito_id, $conn){
	$sqlVnec = "SELECT SUM(total_venc) AS importe_vencim  FROM vencimiento WHERE credito_id = $credito_id AND (vencimiento_status_id = 1 || vencimiento_status_id = 3 || vencimiento_status_id = 6)";
	$responseV = phpmkr_query($sqlVnec ,$conn) or die ("Error al seleccionar la sumade los vencimientos pendientes o venciedos".phpmkr_error()."sql: ".$sqlVnec);
	
	$rowv = phpmkr_fetch_array($responseV);
	$total_vencimientos = $rowv["importe_vencim"];
	
	return $total_vencimientos;
	}
	
	
function calculaMoratorios($x_credito_id, $conn,$x_fecha_movimiento){
	echo "ENTRO A RECUALCULO *****<br>";
		$sqlVenc1 = "SELECT * FROM vencimiento WHERE  credito_id = $x_credito_id AND (vencimiento_status_id = 3) ";
		$responseVen1 = phpmkr_query($sqlVenc1,$conn) or die ("Error al seleccionar el vencimeinto*". phpmkr_error()."sql : ".$sqlVenc1);
		#echo $sqlVenc1 ."<br>";
		//datos del  vencimiento
		#echo "fecha del movimeinto ".$x_fecha_movimiento."<br>";
		#echo "credito_id ".$x_credito_id."<br>";
		$x_num_vencimientos1 = mysql_num_rows($responseVen1);
		//echo"numero de vencimeintos vencidos = ".$x_num_vencimientos1."<br>"; 
			$x_total_del_importe_vencimiento = 0;
			while ($rowVen1 = phpmkr_fetch_array($responseVen1)){
		#	echo "entro al calculo de moratorios:-...<br>";
			$x_s_vencimiento_id = $rowVen1["vencimiento_id"];
			$x_s_credito_id = $rowVen1["credito_id"];
			//echo "<bR>vencimeinto-----".$x_s_vencimiento_id."---<br>";
			$x_s_vencimiento_num = $rowVen1["vencimiento_num"];
			$x_s_vencimiento_status_id = $rowVen1["vencimiento_status_id"];
			$x_s_fecha_vencimiento = $rowVen1["fecha_vencimiento"];
			$x_s_importe = $rowVen1["importe"];
			$x_s_interes = $rowVen1["interes"];
			$x_s_interes_moratorio = $rowVen1["interes_moratorio"];
			$x_s_iva = $rowVen1["iva"];
			$x_s_iva_mor = $rowVen1["iva_mor"];
			$x_s_total_venc = $rowVen1["total_venc"];
			
			//echo "Selecciono vencimiento".$x_s_vencimiento_num."<br>";
			echo "fecha_vencimiento".$x_s_fecha_vencimiento."<br>";
			$x_forma_pago_id = 0;
			$x_fecha_vencimiento_credito = 0;
			$sSql1 = "SELECT forma_pago_id, fecha_vencimiento FROM credito where credito_id = ".$x_s_credito_id."";
			$rs1 = phpmkr_query($sSql1,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql1);
			$row1 = phpmkr_fetch_array($rs1);
			$x_forma_pago_id = $row1["forma_pago_id"];
			$x_fecha_vencimiento_credito = $row1["fecha_vencimiento"];
			phpmkr_free_result($rs1);
			
			

			//echo "Selecciono forma de pago".$x_forma_pago_id."<br> y fecha de vencimeinto del credito".$x_fecha_vencimiento_credito."<br>";
			$x_forma_pago = 0;
			$sSql = "SELECT valor FROM forma_pago where forma_pago_id = ".$x_forma_pago_id;
			$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$row = phpmkr_fetch_array($rs);
			$x_forma_pago = $row["valor"];
			phpmkr_free_result($rs);
					
			
			
			
			$sqlVenNum = "SELECT COUNT(*) AS numero_de_pagos_d FROM vencimiento WHERE  fecha_vencimiento =  \"$x_s_fecha_vencimiento\" AND  credito_id =  $x_s_credito_id";
		#	echo "sql no venc".$sqlVenNum."<br>";
			$response = phpmkr_query($sqlVenNum, $conn) or die("error en numero de vencimiento".phpmkr_error()."sql:".$sqlVenNum);
			$rownpagos = phpmkr_fetch_array($response);  
			$x_numero_de_pagos_d =  $rownpagos["numero_de_pagos_d"];
		#	echo "numero de pagos".$x_numero_de_pagos_d."<br>";
			
			
			// si el numero de pagos es mayor a uno significa que ya se cobraron los moratoios o parte de ellos y ya no se deben de volver a recalcular los moratorios...
			// solo entra al ciclo de moratorios si solo existe un  pago con esta fecha.
			if($x_numero_de_pagos_d < 2){
			//RECALCULAMOS LOS MORATORIOS; ESTO SE HACE PORQUE AVECES LOS REPORTES LLEGAN TARDE Y YA SE GENERARON MORATORIOS, AUNQUE EL CLIENTE YA HAYA REALIZADA EL PAGO
		#	echo "=entra al calculo de los moratorios=<br>";
			// SE RECALCULAN A ALA FECHA DE MOVIMIENTO
			if(empty($x_s_iva)){
				$x_s_iva = 0;
			}		
			if(is_null($x_s_interes_moratorio)){
				$x_s_interes_moratorio = 0;
			}
			 if($x_s_interes_moratorio == 0){
			#	 echo "interes moratorio es mayor de 0 se vuelven a calcular los moratorios"; 
			// echo "recalculamos los moratorios <br>";
				 $sqlC = "SELECT  credito.credito_status_id, credito.credito_tipo_id, credito.importe as importe_credito, credito.tasa_moratoria, credito.credito_num+0 as crednum, credito.tasa,credito.iva as iva_credito";
				 $sqlC .= " FROM credito WHERE credito_id = $x_s_credito_id";
				 $responseC = phpmkr_query( $sqlC, $conn) or die ("Error al seleccionar los datos del credito ". phpmkr_error()."sql : ". $sqlC);
				 $rowC = phpmkr_fetch_array($responseC);
				 $x_c_credito_status_id = $rowC["credito_status_id"];
				 $x_c_credito_tipo_id = $rowC["credito_tipo_id"];
				 $x_c_importe = $rowC["importe"];
				 $x_c_tasa_moratoria = $rowC["tasa_moratoria"];
				 $x_c_crednum = $rowC["crednum"];
				 $x_c_tasa = $rowC["tasa"];	
				 $x_c_iva_credito = $rowC["iva_credito"];	
				 $x_c_penalizacion = $rowC["penalizacion"];
				 
				 
				 
				// echo "tasa mor". $x_c_tasa_moratoria."<br>";
				
				 phpmkr_free_result($responseC);
				 
				 //solo se hace un recalculo de moratorios si el campo moratorios es mayor de 0
			$x_dias_vencidos = datediff('d', $x_s_fecha_vencimiento, $x_fecha_movimiento, false);	
		#	echo "fecha de vencimeinto...".$x_s_fecha_vencimiento."<br>";
		#	echo "dias vencidos ... ".$x_dias_vencidos."<br>";
			//echo "dias vencids ".$x_dias_vencidos."";
			$x_dia = strtoupper(date('l',strtotime($x_s_fecha_vencimiento)));


			$x_dias_gracia = 0;
			switch ($x_dia)
				{
					case "MONDAY": // Get a record to display
						$x_dias_gracia = 0;
						break;
					case "TUESDAY": // Get a record to display
						$x_dias_gracia = 0;
						break;
					case "WEDNESDAY": // Get a record to display
						$x_dias_gracia = 0;
						break;
					case "THURSDAY": // Get a record to display
						$x_dias_gracia = 0;
						break;
					case "FRIDAY": // Get a record to display
						$x_dias_gracia = 0;
						break;
					case "SATURDAY": // Get a record to display
						$x_dias_gracia = 0;
						break;
					case "SUNDAY": // Get a record to display
						$x_dias_gracia = 0;
						break;		
				}
			
				if($x_dias_vencidos > $x_dias_gracia){
				//	echo "dias vencidos mayor  dias de gracia<br>";
						$x_importe_mora = $x_c_tasa_moratoria * $x_dias_vencidos;
						
						//echo "importe de mortaorios = ".$x_importe_mora ."<br>";
				if($x_c_iva_credito == 1){
					//			$x_iva_mor = round($x_importe_mora * .15);
					$x_iva_mor = 0;			
				}else{
					$x_iva_mor = 0;
				}
			
			
				if($x_c_credito_tipo_id == 2){			
					if($x_importe_mora > 0){
						$x_importe_mora = 250;
					}			
				}else{		
					if($x_importe_mora > (($x_s_interes + $x_s_iva) * 2)){				
					$x_importe_mora = ($x_s_interes + $x_s_iva) * 2;
						
					}
				}
		
				/*echo "importe de moratorios despues de la validacion =".$x_importe_mora."<br>";
				echo "importe  =".$x_s_importe."<br>";
				echo "interes =".$x_s_interes."<br>";
				echo "iva =".$x_s_iva."<br>";
				echo "iva moratorios =".$x_iva_mor."<br>";*/
				if( $x_c_penalizacion > 0){
					$x_tot_venc = $x_s_importe + $x_s_interes + $x_s_iva ;	
					}else{
						$x_tot_venc = $x_s_importe + $x_s_interes + $x_importe_mora + $x_s_iva + $x_iva_mor;	
					}
				//echo "total del vencimeinto con los moratorios recalculados = ". $x_tot_venc  ."<br>";	
				if($x_credito_num > 809){
			 
				
				$x_total_del_importe_vencimiento = $x_total_del_importe_vencimiento + $x_tot_venc;
				//echo "suma = ".$x_total_del_importe_vencimiento."<br>";
				//echo "<br>update moratorios:<br>";
				// echo $sSqlWrk."<br>";
				}else{
			
				$x_total_del_importe_vencimiento = $x_total_del_importe_vencimiento + $x_tot_venc;
				//echo  $sSqlWrk."<br>";
				}
				phpmkr_query($sSqlWrk,$conn);
				//echo "actulizamos los moaratorios en la tabla<br>";
				// se actulizan los valores que fueron modificados en el registro
				$x_s_interes_moratorio = $x_importe_mora;
				$x_s_iva_mor =  $x_iva_mor;
				$x_s_total_venc =  $x_tot_venc;
				}// dias vencidos
				}//if interes moratorio > 0
	
			}
			}// fin whilw
	
	
	if($x_num_vencimientos1 == 0){
	    
	$sqlVnec = "SELECT SUM(total_venc) AS importe_vencim  FROM vencimiento WHERE credito_id = $x_credito_id AND (vencimiento_status_id = 1 || vencimiento_status_id = 6)";
	$responseV = phpmkr_query($sqlVnec ,$conn) or die ("Error al seleccionar la sumade los vencimientos pendientes o venciedos".phpmkr_error()."sql: ".$sqlVnec);
	
	$rowv = phpmkr_fetch_array($responseV);
	$x_total_del_importe_vencimiento = $rowv["importe_vencim"];
		
		
		}else if($x_num_vencimientos1 > 0){
			//se recalcularon los moratorios y a esa cantidad se le agregan los demas saldo en estatus 1 o 6
			$sqlVnec = "SELECT SUM(total_venc) AS importe_vencim  FROM vencimiento WHERE credito_id = $x_credito_id AND (vencimiento_status_id = 1 || vencimiento_status_id = 6)";
			$responseV = phpmkr_query($sqlVnec ,$conn) or die ("Error al seleccionar la sumade los vencimientos pendientes o venciedos".phpmkr_error()."sql: ".$sqlVnec);
	
		$rowv = phpmkr_fetch_array($responseV);
		$x_total_del_importe_vencimiento = $x_total_del_importe_vencimiento + $rowv["importe_vencim"];			
			//echo "total del vencimeinto conrecalculo mas los otros importes".$x_total_del_importe_vencimiento."<br>";
			}
	return $x_total_del_importe_vencimiento;
	
	
	}
	
	
	


function AplicaPagos($conn){
	
	
	#echo "Entro a Aplica pagos <br>";
	// selecciona los registros en masiva pago dos...si hay referencias iguales las agrupa y suma el importe
	$sql = "SELECT carga_folio_id, fecha_carga, aplicacion_status_id, ref_pago, nombre_cliente, numero_cliente, SUM(importe) AS importe_total, fecha_movimiento,";
	
	$sql .= " nombre_archivo, sucursal_id, uploaded_file_id, no_aplicar_pago  FROM masiva_pago_2 GROUP BY ref_pago HAVING no_aplicar_pago = 0";
	//echo "sql: del pagos". $sql ."<br>";
	$response = phpmkr_query($sql,$conn);
	$num_reg = mysql_num_rows($response );
	//echo "<br>numero d registros =".$num_reg."<br>";
	$GLOBALS["x_total_de_registros"] = 0;
	$GLOBALS["x_pagos_aplicados"] = 0;
	$GLOBALS["x_pagos_aplicados_correctos"] = 0;
	$GLOBALS["x_pagos_aplicados_diferente"] = 0;
	$GLOBALS["x_pagos_NO_aplicados"] = 0;
	$GLOBALS["x_importe_aplicado"]= 0;
	$GLOBALS["x_importe_NO_aplicado"]= 0;
	
	// credito_id y solicitud_id... los guardamos...
	
	$x_id_credi = 0;
	$x_id_soli = 0;
	
	
	if(!$response){
		//echo phpkmr_error()."sql : ".$sql ;		
		}
	while($row = phpmkr_fetch_array($response)){
		//echo "Entro al while de masiva 2<br>";
		
		$x_carga_folio_id = $row["carga_folio_id"];
		$x_fecha_carga = $row["fecha_carga"];
		$x_aplicacion_status_id = $row["aplicacion_status_id"];
		$x_ref_pago = $row["ref_pago"];
		$x_nombre_cliente = $row["nombre_cliente"];
		$x_numero_cliente = $row["numero_cliente"];
		$x_importe = $row["importe_total"];
		$x_importe_temporal =  $row["importe_total"];
		$x_fecha_movimiento = $row["fecha_movimiento"];
		$x_nombre_archivo = $row["nombre_archivo"];
		$x_sucursal_id = $row["sucursal_id"];
		$x_uploaded_file_id = $row["uploaded_file_id"];
		$x_no_aplicar_pago = $row["no_aplicar_pago"];
		 $x_ultimo_vencimiento_nn = 0;
		 $x_c_garantia_liquida = 0;
		 $x_c_penalizacion = 0;
		 $x_c_forma_pago = 0;
		 $x_credito_liquidado = 0;
		//echo "LA referenci de pago es:".$x_ref_pago."<br>";
		//echo "El numero de cliente en el archivo es :".$x_numero_cliente."<br>";
		
		//variables
		$x_numero_creditos_activos = 0;
		$x_credito_id = 0;
		
		
		
		$sqlRef = "SELECT `credito_id` , `credito_num`  FROM `credito`  WHERE `tarjeta_num` =  $x_ref_pago  AND (`credito_status_id` = 1 || `credito_status_id` = 4 || `credito_status_id` = 5)";
		$responseRef = phpmkr_query($sqlRef,$conn)or die("Error al buscar la referencia".phpmkr_error()."sql : ".$sqlRef );
		//echo  $sqlRef."<br>";
		$x_numero_creditos_activos  = mysql_num_rows($responseRef);
		//echo "numero de creditos activos es igual a".$x_numero_creditos_activos."-";
		$rowRef = phpmkr_fetch_array($responseRef); 
		$x_credito_id = $rowRef["credito_id"];
		$x_credito_num = $rowRef["credito_num"];
		//echo "<br>El numero de credito por tarjeta es:". $x_credito_id."<br>";
		
		phpmkr_free_result($responseRef);
		
			
		
		$sqlCli = "SELECT `credito_id` , `credito_num`, `solicitud_id`  FROM credito WHERE credito.solicitud_id IN ";
		$sqlCli .= "(SELECT solicitud.solicitud_id FROM solicitud JOIN solicitud_cliente ON(solicitud_cliente.solicitud_id = solicitud.solicitud_id) ";
		$sqlCli .= "JOIN cliente ON (cliente.cliente_id = solicitud_cliente.cliente_id) WHERE cliente.cliente_id IN ";
		$sqlCli .= "(SELECT `cliente_id` FROM `cliente` WHERE `cliente_num` = $x_numero_cliente)) order by `credito_id` desc";
		
		$reponseCli = phpmkr_query($sqlCli,$conn) or die("Error al buscar cliente". phpmkr_error()."sql : ".$sqlCli);
		$rowCli = phpmkr_fetch_array($reponseCli);
		$x_credito_id_2 = $rowCli["credito_id"];		
		$x_credito_num_2 = $rowCli["credito_num"];
		//echo "<br>El numero de credito por numero de cliente es:". $x_credito_id_2."<br>";
		phpmkr_free_result($reponseCli);
		
		
		
		################################################################################################################
		###############################  LIQUIDACION  DE CREDITO POR ANTICIPADO  #######################################
		################################################################################################################
		# INSERTAMOS EL PAGO PARA LIQUIDAR EL CREDITO
		# SI EL PAGPO ES MENSUAL Y  EL CREDITO TIENE GARANTIA LIQUIDA Y TIENE MOSNTO DE PENALIZACION ENTONCES SE PUEDE LIQUIDAR EL PAGO EN SU TOTALIDAD
		# BUSCAMOS EL MONTO PARA LIQUIDAR
		# SI EL MOSNTO PARA LIQUIDAR CONINCIDE CON EL MONTO PAGADO ENTONCES:
		# SE ACTULAIZAN LOS VENCIMIENTOS PARA QUITARLES LOS INTERESES, PAGARLOS Y SE LIQUIDA EL CREDITO 
		
		
		
		if($x_credito_id >0){ 
		$sqlCondiconesCredito = " SELECT forma_pago_id, garantia_liquida, penalizacion, solicitud_id FROM credito WHERE credito_id = $x_credito_id ";
		$rsCondicionesCredito = phpmkr_query($sqlCondiconesCredito, $conn) or die ("Error al seleccionar las condiciones de credito". phpmkr_error()."sql:". $sqlCondiconesCredito);
		$rowCondicionesCredito = phpmkr_fetch_array($rsCondicionesCredito);
		$x_c_forma_pago = $rowCondicionesCredito["forma_pago_id"];
		#echo "forma de pago".$x_c_forma_pago."<br>";
		$x_c_garantia_liquida = $rowCondicionesCredito["garantia_liquida"];
		$x_c_penalizacion = $rowCondicionesCredito["penalizacion"];
		$x_c_solicitud_id = $rowCondicionesCredito["solicitud_id"];
		$x_credito_liquidado =0;
		}
		# SIE EL CREDITO ES MESUAL Y ES APLICA PARA LIQUIDACION ANTICIPADA; TIENE QUE TENER GARANTIA LIQUIDA Y PENALIZACION
		# ESTA VALDACION APLICARA TODOS LOS CREDITOS SEMANALES, CATORCENALES, QUINCENALES Y MENSAUAES QUE TENGAN EL CAMPO DE PENALIZACION        # LLENO
		if (!empty($x_c_penalizacion) && $x_c_penalizacion > 0  ){
			
			$x_monto_para_liquidar = montoParaLiquidar($x_credito_id, $x_fecha_movimiento,$conn);
			$x_saldo_a_favor = $x_importe - $x_monto_para_liquidar;
			
			
			if (($x_credito_id > 0) && ($x_numero_creditos_activos == 1) &&($x_importe >= $x_monto_para_liquidar)){
			#si el importe del pago es mayor que el monto para liquidar, se procede.
			$x_saldo_sobrante_liquidado = $x_importe - $x_monto_para_liquidar;
			 echo "monto para liquidar = ".$x_monto_para_liquidar."<br>";
			 #echo "importe-- ".$x_importe."<br>";	
			# echo "SALDO a favor".$x_saldo_a_favor."<br>";		
			#1.- PAGAMOS LOS VENCIMIENTOS CON INTERESES.
			#2.- PAGAMOS LOS VENCIMIENTOS SIN INTERESES.
			#3.- PAGAMOS LAS PENALIZACIONES Y/O  COMISION POR CABRANZA.
			#4.- LIQUIDAMOS EL CREDITO
			#5.- CAMBIAMOS EL STATUS DE LA GARATIA LIQUIDA A = 3; EN DEUDA 
			#6.- INSERTAMOS EN RECIBO
			#7.- INSERTAMOS EN RECIBO_VENCIMIENTO
			#8.- CAMBIAMOS ALGUNA CONDION PARA QUE YA NO ENTRE EN LAS SIGUIENTES VALIDACIONES DEL PAGO.		
			#9.- SI PAGO DE MAS SE DEBE AGREGAR EL MONTO SOBRANTE AL MONTO DE LA GARANTIA LIQUIDA	
			
			$x_fecha_de_pago = ConvertDateToMysqlFormat($x_fecha_movimiento) ;
			$sqlLastDay =" SELECT LAST_DAY('$x_fecha_de_pago') AS fin_mes ";
			$rsLastDay = phpmkr_query($sqlLastDay, $conn) or die ("Error al seleccionar el ultimo dia de mes".phpmkr_error()."SQL:".$sqlLastDay);
			$rowLastDay = phpmkr_fetch_array($rsLastDay);
			$x_fecha_fin_mes = $rowLastDay["fin_mes"];
			
			
			
			$sqlVencidos = "SELECT vencimiento_num FROM vencimiento WHERE credito_id = $x_credito_id AND vencimiento_status_id = 3 ORDER BY vencimiento_num DESC LIMIT 0,1 ";
			$rsVencidos = phpmkr_query($sqlVencidos,$conn) or die ("Error al seeleccionar el ultimo encimiento vencido".phpmkr_error()."sql:". $sqlVencidos);
			$rowVencidos = phpmkr_fetch_array($rsVencidos);
			$x_no_referencia_ven = $rowVencidos["vencimiento_num"];
				
				
			if(!empty($x_no_referencia_ven)){
			// si existe uno o mas vencimientos vencido se toma el ultimo como numero de referencia
			// y buscamos el que le sigue que estar pendiente y se quedara cmo numero de referencia
			$sqlPendiente = "SELECT vencimiento_num FROM vencimiento WHERE credito_id = $x_credito_id AND vencimiento_status_id = 1 AND vencimiento_num > $x_no_referencia_ven  ORDER BY vencimiento_num ASC LIMIT 0,1 ";
			$rsPendiente = phpmkr_query($sqlPendiente,$conn) or die ("Error al seeleccionar el ultimo encimiento vencido".phpmkr_error()."sql:". $sqlPendiente);
			$rowPendiente = phpmkr_fetch_array($rsPendiente);
			$x_no_referencia_ven = $rowPendiente["vencimiento_num"];	
			}else{
				//si numeor de referencia esta vencido entonces se toma como numero de referenia el primer vencimiento pendiente
				$sqlPendiente = "SELECT vencimiento_num FROM vencimiento WHERE credito_id = $x_credito_id AND vencimiento_status_id = 1 ORDER BY vencimiento_num ASC LIMIT 0,1 ";
				$rsPendiente = phpmkr_query($sqlPendiente,$conn) or die ("Error al seeleccionar el ultimo encimiento vencido".phpmkr_error()."sql:". $sqlPendiente);
				$rowPendiente = phpmkr_fetch_array($rsPendiente);
				$x_no_referencia_ven = $rowPendiente["vencimiento_num"];			
			}
 
		#echo "numero de referencia ven". $x_no_referencia_ven."<br>";	
				

			
			$x_lista_vencimientos_interes= "";
			#1
		#	$sqlSaldoLA = "SELECT vencimiento_id FROM vencimiento WHERE credito_id = $x_credito_id and fecha_vencimiento <= '$x_fecha_fin_mes' and vencimiento_status_id in (1,3,6)";
			$sqlSaldoLA = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and  vencimiento_num <= $x_no_referencia_ven and vencimiento_status_id in (1,3,6,7) order by  vencimiento_num asc";
			$rsSaldoLA = phpmkr_query($sqlSaldoLA, $conn) or die ("error en saldos". phpmkr_error()."sql:".$sqlSaldoLA);
			$x_total_liquida_con_interes = 0;
			$x_lista_con_interes = "";
			while ($rowSaldoLA = phpmkr_fetch_array($rsSaldoLA)){
			#PAGAMOS EL VENCIMIENTO, CON TODO Y SUS INTERESES..
			$x_venc_id_interes = $rowSaldoLA["vencimiento_id"];
			$x_lista_vencimientos_interes .= $x_venc_id_interes. ", ";			
			$sqlPagaVencimiento = "UPDATE vencimiento SET vencimiento_status_id = 2 WHERE vencimiento_id = $x_venc_id_interes ";
			$rsPagaVencimiento = phpmkr_query($sqlPagaVencimiento, $conn) or die ("Error al actulaizar el vencimeinto con interes". phpmkr_error()."sql:". $sqlPagaVencimiento);
			}
			#$x_lista_con_interes = trim($x_lista_con_interes,", ");
			
			#2
			#$sqlSaldoLA = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and fecha_vencimiento >= '$x_fecha_fin_mes' and vencimiento_status_id in (1,3,6)";
			$sqlSaldoLA  = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and   vencimiento_num > $x_no_referencia_ven and vencimiento_num < 2000 and vencimiento_status_id in (1,3,6) order by  vencimiento_num asc";
			$rsSaldoLA = phpmkr_query($sqlSaldoLA, $conn) or die ("error en saldos". phpmkr_error()."sql:".$sqlSaldoLA);
			$x_total_liquida_con_interes = 0;
			$x_lista_con_interes = "";
			while ($rowSaldoLA = phpmkr_fetch_array($rsSaldoLA)){
			#PAGAMOS EL VENCIMIENTO, CON TODO Y SUS INTERESES..
			$x_venc_id_interes = $rowSaldoLA["vencimiento_id"];
			$x_lista_vencimientos_interes .= $x_venc_id_interes. ", ";		
			$x_interes_vecimiento = $rowSaldoLA["interes"];
			$x_iva_venicimiento_la = $rowSaldoLA["iva"];
			$x_total_vencimiento_la = $rowSaldoLA["total_venc"];
			$x_total_vencimiento_la =  $x_total_vencimiento_la - $x_iva_venicimiento_la - $x_interes_vecimiento;
			$sqlPagaVencimiento = "UPDATE vencimiento SET vencimiento_status_id = 2, interes = 0, iva = 0, total_venc = $x_total_vencimiento_la ";
			$sqlPagaVencimiento .= " WHERE vencimiento_id = $x_venc_id_interes ";
			$rsPagaVencimiento = phpmkr_query($sqlPagaVencimiento, $conn) or die ("Error al actulaizar el vencimeinto con interes". phpmkr_error()."sql:". $sqlPagaVencimiento);
			}
			#$x_lista_con_interes = trim($x_lista_con_interes,", ");
			
			#3
			#$sqlSaldoLA = "SELECT vencimiento_id FROM vencimiento WHERE credito_id = $x_credito_id and fecha_vencimiento >= '$x_fecha_fin_mes' and vencimiento_status_id in (7,8)";
			$sqlSaldoLA = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and   vencimiento_num > $x_no_referencia_ven and vencimiento_num >= 2000 and vencimiento_status_id in (1,3,6) order by  vencimiento_num asc";			
			$rsSaldoLA = phpmkr_query($sqlSaldoLA, $conn) or die ("error en saldos". phpmkr_error()."sql:".$sqlSaldoLA);
			$x_total_liquida_con_interes = 0;
			$x_lista_con_interes = "";
			while ($rowSaldoLA = phpmkr_fetch_array($rsSaldoLA)){
			#PAGAMOS EL VENCIMIENTO, CON TODO Y SUS INTERESES..
			$x_venc_id_interes = $rowSaldoLA["vencimiento_id"];
			$x_lista_vencimientos_interes .= $x_venc_id_interes. ", ";			
			$sqlPagaVencimiento = "UPDATE vencimiento SET vencimiento_status_id = 2 WHERE vencimiento_id = $x_venc_id_interes ";
			$rsPagaVencimiento = phpmkr_query($sqlPagaVencimiento, $conn) or die ("Error al actulaizar el vencimeinto con interes". phpmkr_error()."sql:". $sqlPagaVencimiento);
			}
			
			
			$x_lista_vencimientos_interes = trim($x_lista_vencimientos_interes,", ");
			
			#4
			$SqlLiquidaCredito = "UPDATE credito SET credito_status_id = 3 WHERE credito_id = $x_credito_id ";
			$rsLiquidaCredito = phpmkr_query($SqlLiquidaCredito, $conn) or die ("Error al actualizar el credito". phpmkr_error()."sql:". $SqlLiquidaCredito);
			
			$SqlLiquidaCredito = "UPDATE solicitud SET solicitud_status_id = 7 WHERE solicitud_id = $x_c_solicitud_id ";
			$rsLiquidaCredito = phpmkr_query($SqlLiquidaCredito, $conn) or die ("Error al actualizar el credito". phpmkr_error()."sql:". $SqlLiquidaCredito);
			
			#5 
			$SqlLiquidaCredito = "UPDATE garantia_liquida SET status = 3 WHERE credito_id = $x_credito_id ";
			$rsLiquidaCredito = phpmkr_query($SqlLiquidaCredito, $conn) or die ("Error al actualizar el credito". phpmkr_error()."sql:". $SqlLiquidaCredito);
			
			
			
			//#6 INSERTA EN RECIBO
			
					$fieldList = NULL;
					// Field recibo_status_id
					$fieldList["`recibo_status_id`"] = 1;
											
					$theValue = ($GLOBALS["x_banco_id"] != "") ? intval($GLOBALS["x_banco_id"]) : "0";
					$fieldList["`banco_id`"] = $theValue;
					
					// Field medio_pago_id
					$theValue = ($GLOBALS["x_medio_pago_id"] != "") ? intval($GLOBALS["x_medio_pago_id"]) : "NULL";
					$fieldList["`medio_pago_id`"] = $theValue;
				
					// Field referencia_pago
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_pago_1"]) : $GLOBALS["x_referencia_pago_1"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`referencia_pago`"] = $theValue;
				
					// Field referencia_pago
					$x_referencia_pago21 = "LIQUIDADO ANTICIPADO";
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_referencia_pago21) : $x_referencia_pago21; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`referencia_pago_2`"] = $theValue;
				
					// Field fecha_registro
					$theValue = ($GLOBALS["x_fecha_pago"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_pago"]) . "'" : "Null";
					$fieldList["`fecha_registro`"] = $theValue;
				
					// Field fecha_registro
					$theValue = ($x_fecha_movimiento != "") ? " '" . ConvertDateToMysqlFormat($x_fecha_movimiento) . "'" : "Null";
					$fieldList["`fecha_pago`"] = $theValue;
				
					// Field importe
					$theValue = ($x_importe != "") ? " '" . doubleval($x_importe) . "'" : "NULL";
					$fieldList["`importe`"] = $theValue;
				
					// insert into database
					$sSql = "INSERT INTO `recibo` (";
					$sSql .= implode(",", array_keys($fieldList));
					$sSql .= ") VALUES (";
					$sSql .= implode(",", array_values($fieldList));
					$sSql .= ")";
					phpmkr_query($sSql, $conn) or die("Failed to execute query....: " . phpmkr_error() . '<br>SQL: ' . $sSql);
					
					$x_recibo_id = mysql_insert_id();
					
					
					#7
					
					# echo "lista de vencimeintos".$x_vencimientos_pagados_li."<br>";
					$x_vencimientos_pagados_li = explode(", ",$x_lista_vencimientos_interes);
					foreach($x_vencimientos_pagados_li as $ven_id){
						// por cada valor que esta en la lista se inserta en recibo_vencimiento;
						 $insertRecibo = "insert into recibo_vencimiento values(0,".$ven_id .",$x_recibo_id)";
						 $x_result = phpmkr_query($insertRecibo, $conn);
					#	echo "inserta en recibo". $insertRecibo."<br>";
						}
					
					
			# CAMBIAR ALGUNA CONDCION PARA QUE YA NO ENTRE EN LOS SIGUIENTES IF
			#	if($x_credito_liquidado == 1){
			# el credito ya se liquido ya no debe de enstra en la siguiente condicion
			#cambiamos la variable $x_sobrante a  1 para que no se cumpla la condicion y no entre
			#$x_sobrante = 1; // esto esta presente abajo...						
			#}
			
			
			#9 
			$x_fecha_de_pago_exc = ConvertDateToMysqlFormat($x_fecha_movimiento) ;
			if($x_saldo_a_favor > 0){
				// el cliente tiene saldo a favor...
				# se agrega el monto a  garantia liquida
				$sqlInsertGarantia = "INSERT INTO garantia_liquida (`garantia_liquida_id`, `credito_id`, `monto`, `status`, `fecha`, `fecha_modificacion`)";
				$sqlInsertGarantia .= " VALUES (NULL, $x_credito_id, $x_saldo_a_favor, '6', \"$x_fecha_de_pago_exc\", NULL)";
				$rsInsertaGarantia = phpmkr_query($sqlInsertGarantia, $conn) or die ("Error al insertar en garantia ".phpmkr_error()."sql:".$sqlInsertGarantia);
				#echo "inserta en garantia liquida".$sqlInsertGarantia."<br>";
				}
			
			
			$x_credito_liquidado = 1;
			}// IF CREDITO_ID > 0, REDITO_ACTIVO= 1, IMPORTE >= MONTO PARA LIQUIDAR			
			}// IF CREDITO LIQUIDABLE POR ANTICIPADO
		
		
		
		
		//CALCULA SOBRANTE DE PAGO
		 $x_sobrante = 0;
		 $x_total_venc_por_pagar = 0;
		 
			
		if(($x_credito_id > 0) && ($x_numero_creditos_activos == 1)){
			 $x_ultimo_vencimiento_nn = 0;
			
			
		#primero validamos la parte de la garantia
		
				// si sobrante es == 0
		if($x_c_penalizacion > 0 && $x_c_garantia_liquida > 0 && $x_credito_liquidado != 1){
			
			#1.- contamos cuantos vencimeintos hay pendientes
			#2.- cambiamos el status del ultimo pagoa para que no se pagues y entren primero las penalizaciones y las comisiones
			#3.- terminando todo el proceso de pago volvemos a cambiar el status del pago 12... y si ya no hay pagos pendiestes ni vencidoe netonces pagamos el pago 12 con la garantia y cambiamos el status de la garantia a utulizado para liquidar credito. 
			 $sqlNumeroPagosPendientes = "SELECT count(*) AS total_penalizaciones FROM vencimiento WHERE  credito_id = $x_credito_id AND vencimiento_status_id in (1,3) AND  vencimiento_num > 2000";
			 $rsNumeroPagosPendientes = phpmkr_query($sqlNumeroPagosPendientes, $conn) or die ("Error al seleccionar los vencimeintos pendientes".phpmkr_error."sql:".$sqlNumeroPagosPendientes);
			 $rowNumeroPagosPendientes = phpmkr_fetch_array($rsNumeroPagosPendientes);
			 $x_total_penalizaciones = $rowNumeroPagosPendientes["total_penalizaciones"];
			 if ( $x_total_penalizaciones > 0 ){
				 # entonces cambiamos le status de la vencimeinto numero penultimos para que primero pague las penalizaciones y la comsion y despues pague   el ultimo vencimeintos.
				 #seleccionamos el penultimo vencimiento
				 $sqlUltimoVencimiento = "SELECT vencimiento_num FROM  vencimiento WHERE  credito_id = $x_credito_id AND vencimiento_status_id in (1,3) ";
				 $sqlUltimoVencimiento .= " AND  vencimiento_num < 2000 ORDER BY vencimiento_num DESC LIMIT 0,1 ";
				 $rsUltimoVencimiento = phpmkr_query($sqlUltimoVencimiento, $conn) or die ("Error al seleccionar el ultimo vencimeinto".phpmkr_error()."sql :". $sqlUltimoVencimiento);
				 $rowUltimoVencimiento = phpmkr_fetch_array( $rsUltimoVencimiento);
				 $x_ultimo_vencimiento_nn  = $rowUltimoVencimiento["vencimiento_num"];
				 $x_ultomo_vencimeinto_nn_menos_1 = $x_ultimo_vencimiento_nn - 1;
				 
				 
				 #forma pago 
				 #1 semenala
				 #2 catorcenal
				 #3 mensual
				 #4 quincenal
				 if ($x_c_forma_pago == 1){
					 // el credito es semanal y la garantia liquida corresponde a lo de dos pagos asi que debemos cambair el status a dos pagso para que no entren al ciclo de pago
					 
					  $sqlUpdateUltimoVencmeinto = "UPDATE vencimiento SET  vencimiento_status_id  = 9 WHERE credito_id = $x_credito_id AND vencimiento_num IN ($x_ultimo_vencimiento_nn, $x_ultomo_vencimeinto_nn_menos_1) ";
				#	  echo "dobel vencimeinto cambio status".$sqlUpdateUltimoVencmeinto."<br>";
					 }else{
						 // solo se cambia el status al ultimo vencimiento
					       $sqlUpdateUltimoVencmeinto = "UPDATE vencimiento SET  vencimiento_status_id  = 9 WHERE credito_id = $x_credito_id AND vencimiento_num = $x_ultimo_vencimiento_nn ";
						 }
				 #cambiamos el status del vencimeinto para que no se pague   
				  #$sqlUpdateUltimoVencmeinto = "UPDATE vencimiento SET  vencimiento_status_id  = 9 WHERE credito_id = $x_credito_id AND vencimiento_num = $x_ultimo_vencimiento_nn";
				  $rsUpdateUltimoVencmeinto = phpmkr_query($sqlUpdateUltimoVencmeinto, $conn) or die ("Erroe al actulizar el vencimeito ultimo para no ser tomado en ecuenta 1". phpmkr_error()."sql :".$sqlUltimoVencimiento); 
				  
				  
				 }
			
			}
		
		
		
			
			
		$x_total_a_pagar_vencimientos = calculaTotalVencimientos($x_credito_id,$conn);
		#echo "deposito del cliente por  :".$x_importe."<br>";
		#echo "total apagar de los vencimientos = ".$x_total_a_pagar_vencimientos."<br>";
		if($x_total_a_pagar_vencimientos < $x_importe){			
			// la persona pago de mas...este pago no se debe de aplicar...
			//verificamos los moratorios........
			
			#si el credito tiene penalizacion entonces nno se recalculan los moratorios porque ya no existen
			#el credito tiene penalizaciones cuano el campo es mayor de 0
			
			if($x_c_penalizacion < 1){				
			$x_total_vencimientos_moratorios = calculaMoratorios($x_credito_id, $conn, $x_fecha_movimiento);
			#echo "total a pagar de los ven con moratorios recalculados".$x_total_vencimientos_moratorios."<br>";
			 if($x_total_vencimientos_moratorios < $x_importe){
				// no se aplica el pago
				 $x_sobrante = 1;				
				}
			}else{
				$x_sobrante = 1;				
				}		
			}
		
		
		}
		
		

		
		if($x_credito_liquidado == 1){
			# el credito ya se liquido ya no debe de enstra en la siguiente condicion
			#cambiamos la variable $x_sobrante a  1 para que no se cumpla la condicion y no entre
			$x_sobrante = 1;
			
			}
		
		if( ($x_credito_id > 0) && ($x_numero_creditos_activos == 1) && ($x_sobrante == 0)){
			
			echo "<br>credito id.". $x_credito_id." mayor de 0<br><br>";
			
			$GLOBALS["x_importe_aplicado"] = $GLOBALS["x_importe_aplicado"] + $x_importe;
			
			 if($x_credito_id == $x_credito_id_2){
				 $GLOBALS["x_pagos_aplicados_correctos"]++;
				 }else{
					 $GLOBALS["x_pagos_aplicados_diferente"]++;
					 }
					 
			//echo "LA REFERENCIA Y EL NUMERO DE CLIENTE COINCIDE";
			// SE APLICA EL PAGO
			
			$x_importe_tem = $x_importe;
			$x_recibo = 1;
			$x_s_vencimiento_num = 0;
			$x_s_vencimiento_status_id = 0;
			$x_s_fecha_vencimiento = "";
			$x_s_importe = 0;
			$x_s_interes = 0;
			$x_s_interes_moratorio = 0;
			$x_s_iva = 0;
			$x_s_iva_mor = 0;
			$x_s_total_venc = 0;
			
			while($x_importe > 0){
				
			// seleccionamos el siguente vencimiento a pagar.
			$sqlVenc = "SELECT * FROM vencimiento WHERE  credito_id = $x_credito_id AND (vencimiento_status_id = 1 OR vencimiento_status_id = 3 OR vencimiento_status_id = 6 ) ORDER BY `fecha_vencimiento` LIMIT 1";
			$responseVen = phpmkr_query($sqlVenc,$conn) or die ("Error al seleccionar el vencimeinto". phpmkr_error()."sql : ".$sqlVenc);
			//echo $sqlVenc ."<br>";
			//datos del  vencimiento
			$x_num_vencimientos = mysql_num_rows($responseVen);
			
			// si aun hay vencimientos por pagar
			if( $x_num_vencimientos > 0){
				// se hace todo el proceso de  pago.........
			$num_regis = phpmkr_num_rows($responseVen);
			$x_s_interes_moratorio = 0;
			$rowVen = phpmkr_fetch_array($responseVen);
			
			$x_s_vencimiento_id = $rowVen["vencimiento_id"];
			$x_s_credito_id = $rowVen["credito_id"];
			//echo "<bR>vencimeinto-----".$x_s_vencimiento_id."---<br>";
			$x_s_vencimiento_num = $rowVen["vencimiento_num"];
			$x_s_vencimiento_status_id = $rowVen["vencimiento_status_id"];
			$x_s_fecha_vencimiento = $rowVen["fecha_vencimiento"];
			echo "FECHA VENCIMEINTO".$x_s_fecha_vencimiento."<BR>";
			$x_s_importe = $rowVen["importe"];
			$x_s_interes = $rowVen["interes"];
			$x_s_interes_moratorio = $rowVen["interes_moratorio"];
			echo "INTERES MORATORIO".$x_s_interes_moratorio."<BR>";
			$x_s_iva = $rowVen["iva"];
			$x_s_iva_mor = $rowVen["iva_mor"];
			$x_s_total_venc = $rowVen["total_venc"];
			//echo "credito_id :".$x_s_credito_id."----";
			//echo "Selecciono vencimiento".$x_s_vencimiento_num."<br>";
			
			$x_forma_pago_id = 0;
			$x_fecha_vencimiento_credito = 0;
			$sSql = "SELECT forma_pago_id, fecha_vencimiento FROM credito where credito_id = ".$x_s_credito_id."";
			$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$row = phpmkr_fetch_array($rs);
			$x_forma_pago_id = $row["forma_pago_id"];
			$x_fecha_vencimiento_credito = $row["fecha_vencimiento"];
			phpmkr_free_result($rs);		
			
			// fecha vencimeito para  remanente
			$x_fecha_vencimiento_remanente = "";

			$sqlFVR = "SELECT  fecha_vencimiento FROM vencimiento WHERE credito_id = ".$x_s_credito_id." ORDER BY vencimiento_num DESC LIMIT 1";
			$responseFVR = phpmkr_query($sqlFVR, $conn) or die ("error al seleccionar la fecha para remanente". phpmkr_error()."sql: ".$sqlFVR);
			$rowfvr = phpmkr_fetch_array($responseFVR);
			$x_fecha_vencimiento_remanente = $rowfvr["fecha_vencimiento"];
			
			phpmkr_free_result($responseFVR);
					
			//echo "Selecciono forma de pago".$x_forma_pago_id."<br> y fecha de vencimeinto del credito".$x_fecha_vencimiento_credito."<br>";
			$x_forma_pago = 0;
			$sSql = "SELECT valor FROM forma_pago where forma_pago_id = ".$x_forma_pago_id;
			$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$row = phpmkr_fetch_array($rs);
			$x_forma_pago = $row["valor"];
			phpmkr_free_result($rs);
					
			
			
			
			// si existe un vencimeinto con la misma fecha ya no se hace el recalculo de los moratorios..porque los moratosios ya se calcularon una vez y ya no es necesario volverlos a calcular	            //posterirormente
			
			$x_numero_de_pagos = 0;	
			$sqlVenNum = "SELECT COUNT(*) AS numero_de_pagos FROM vencimiento WHERE  fecha_vencimiento =  \"$x_fecha_vencimiento\" AND  credito_id =  $x_credito_id";
			$responseNPP = phpmkr_query($sqlVenNum, $conn) or die("error en numero de vencimiento".phpmkr_error()."sql:".$sqlVenNum);
			$rownpagos = phpmkr_fetch_array($responseNPP);  
			$x_numero_de_pagos =  $rownpagos["numero_de_pagos"];
			
			
			//RECALCULAMOS LOS MORATORIOS; ESTO SE HACE PORQUE AVECES LOS REPORTES LLEGAN TARDE Y YA SE GENERARON MORATORIOS, AUNQUE EL CLIENTE YA HAYA REALIZADA EL PAGO
			// SE RECALCULAN A ALA FECHA DE MOVIMIENTO
			if(empty($x_s_iva)){
				$x_s_iva = 0;
			}		
			if(is_null($x_s_interes_moratorio)){
				$x_s_interes_moratorio = 0;
			}
			 if($x_s_interes_moratorio > 0 || $x_s_interes_moratorio == 0 ){
		// echo "recalculamos los moratorios <br>";
				 $sqlC = "SELECT  credito.credito_status_id, credito.credito_tipo_id, credito.importe as importe_credito, credito.tasa_moratoria, credito.credito_num+0 as crednum, credito.tasa,credito.iva as iva_credito";
				 $sqlC .= " FROM credito WHERE credito_id = $x_s_credito_id";
				 $responseC = phpmkr_query( $sqlC, $conn) or die ("Error al seleccionar los datos del credito ". phpmkr_error()."sql : ". $sqlC);
				 $rowC = phpmkr_fetch_array($responseC);
				 $x_c_credito_status_id = $rowC["credito_status_id"];
				 $x_c_credito_tipo_id = $rowC["credito_tipo_id"];
				 $x_c_importe = $rowC["importe"];
				 $x_c_tasa_moratoria = $rowC["tasa_moratoria"];
				 $x_c_crednum = $rowC["crednum"];
				 $x_c_tasa = $rowC["tasa"];	
				 $x_c_iva_credito = $rowC["iva_credito"];	
				 
				 
				// echo "tasa mor". $x_c_tasa_moratoria."<br>";
				
				 phpmkr_free_result($responseC);
				 
				 $x_dias_vencidos = 0;	 
				
		
			
			$x_dias_vencidos = 0;	 
				 //solo se hace un recalculo de moratorios si el campo moratorios es mayor de 0
			$x_dias_vencidos = datediff('d', $x_s_fecha_vencimiento, $x_fecha_movimiento, false);	
			 echo "fecha VENCIMEINTO".$x_s_fecha_vencimiento."<br>";
			 echo "fecha MOVIMEINTO".$x_fecha_movimiento."<br>";
			 echo "DIAS VENCIDOS".$x_dias_vencidos."<BR>";
			 
			 
			 if($x_s_fecha_vencimiento > $x_fecha_movimiento ){
				//echo "fecha vencimeinto mayor a fecha del movimeinto";
			$x_dias_vencidos_m = datediff('d', $x_fecha_movimiento,$x_s_fecha_vencimiento, false);
			#echo "DIAS DIFERENCIA = ".$x_dias_vencidos_m."<br>";
			}else{
				$x_dias_vencidos_m = 0;// para quese  no se cumpla la condicion menor a 7 y se va directo a remanente
				
				}
			//echo "dias vencids ".$x_dias_vencidos."";
			$x_dia = strtoupper(date('l',strtotime($x_s_fecha_vencimiento)));


			$x_dias_gracia = 0;
			switch ($x_dia)
				{
					case "MONDAY": // Get a record to display
						$x_dias_gracia = 0;
						break;
					case "TUESDAY": // Get a record to display
						$x_dias_gracia = 0;
						break;
					case "WEDNESDAY": // Get a record to display
						$x_dias_gracia = 0;
						break;
					case "THURSDAY": // Get a record to display
						$x_dias_gracia = 0;
						break;
					case "FRIDAY": // Get a record to display
						$x_dias_gracia = 0;
						break;
					case "SATURDAY": // Get a record to display
						$x_dias_gracia = 0;
						break;
					case "SUNDAY": // Get a record to display
						$x_dias_gracia = 0;
						break;		
				}
			
			
				// si el credito tiene el campo de penalizacion lleno ; signifca que es de los creditos nuevos y que no se debe de volver a recalcular los moratorios
				if( $x_c_penalizacion < 1 )	{
					// esto sera cieryo solo en los creito viejitos, los creditos nuevo traeran lleno el campo penalizacion y no entraran aqui
					
					
				if($x_dias_vencidos > $x_dias_gracia){
				echo "dias vencidos mayor  dias de gracia<br>";
				echo "dias vencidos :".$x_dias_vencidos."<br>";
			
				$x_importe_mora = $x_c_tasa_moratoria * $x_dias_vencidos;
				echo "================================importe de los moratorios en la funcion principal es :".$x_importe_mora."<br>";
				if($x_c_iva_credito == 1){
								$x_iva_mor = round($x_importe_mora * .15);
					$x_iva_mor = 0;			
				}else{
					$x_iva_mor = 0;
				}
			
			
					//echo "moratorios 1:".$x_importe_mora."<br>";
				if($x_c_credito_tipo_id == 2){			
					if($x_importe_mora > 0){
						$x_importe_mora = 250;
					}			
				}else{		
					if($x_importe_mora > (($x_s_interes + $x_s_iva) * 2)){						
							$x_importe_mora = (($x_s_interes + $x_s_iva) * 2);			
					}
				}
		        
				//echo "moratorios2 :".$x_importe_mora."<br>";
				
				$x_tot_venc = $x_s_importe + $x_s_interes + $x_importe_mora + $x_s_iva + $x_iva_mor;		
				
				if($x_numero_de_pagos < 2){
					//si solo hay unvencimiento con esa fecha se actualiza los moratorios
					
				if($x_credito_num > 809){
			    $sSqlWrk = "update vencimiento set vencimiento_status_id = 3, interes_moratorio = $x_importe_mora, iva_mor = $x_iva_mor, total_venc = $x_tot_venc where vencimiento_id = $x_s_vencimiento_id ";
				//echo "<br>update moratorios:<br>";
				 //echo $sSqlWrk."<br>";
				}else{
				$sSqlWrk = "update vencimiento set vencimiento_status_id = 3 where vencimiento_id = $x_s_vencimiento_id ";
				//echo  $sSqlWrk."<br>";
				}
				phpmkr_query($sSqlWrk,$conn);
				
				}// fin valida vencimeintos anteriores con la misma fecha
				
				
				
				//echo "actulizamos los moaratorios en la tabla<br>";
				// se actulizan los valores que fueron modificados en el registro
				$x_s_interes_moratorio = $x_importe_mora;
				$x_s_iva_mor =  $x_iva_mor;
				$x_s_total_venc =  $x_tot_venc;
				}// dias vencidos
				
				}// if penalizacion menor de 1
				}//if interes moratorio > 0
				
			
				
				// =========== APLICAR PAGO ============
				
				// =========== CASO 1  ================
				// EL IMPORTE DEL PAGO ES IGUAL AL IMPORTE DEL VENCIMIENTO
				
				// =========== CASO 2  ================
				// EL IMPORTE DEL PAGO ES MENOR AL IMPORTE DEL VENCIMIENTO
				// EN ESTE CASO SE  MATA EL VENCIMIENTO POR LA CANTIDAD PAGADA
				// SI LA CANTIDAD FALTANTE ES MENOR A $100 SE VA A REMANENTE
				// SI LA CANTIDAD FALTANTE ES MAYOR A  $100 SE GENERA UN NUEVO VENCIMIENTO, CON LA MISMA FECHA POR LA CATIDAD RESTANTE
				
				// =========== CASO 3  ================
				// EL IMPORTE DEL PAGO ES MAYOR AL IMPORTE DEL VENCIMIENTO
				// SE MATA EL VENCIMEINTO ACTUAL Y SE REVIS EL VENCIMIENTO SIGUIENTE PARA PAGARLO O
				// ABONAR UNA CANTIDAD, SI NO SE PAGA COMPLETO, SE MATA EL VENCIMENTO POR LA CANTIDAD
				// Y SE GENERA UNO NUEVO, CON LA CANTIDAD FALTANTE 
				
				
				if($x_recibo == 1){
					$fieldList = NULL;
					// Field recibo_status_id
					$fieldList["`recibo_status_id`"] = 1;
											
					$theValue = ($GLOBALS["x_banco_id"] != "") ? intval($GLOBALS["x_banco_id"]) : "0";
					$fieldList["`banco_id`"] = $theValue;
					
					// Field medio_pago_id
					$theValue = ($GLOBALS["x_medio_pago_id"] != "") ? intval($GLOBALS["x_medio_pago_id"]) : "NULL";
					$fieldList["`medio_pago_id`"] = $theValue;
				
					// Field referencia_pago
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_pago_1"]) : $GLOBALS["x_referencia_pago_1"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`referencia_pago`"] = $theValue;
				
					// Field referencia_pago
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_referencia_pago2) : $x_referencia_pago2; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`referencia_pago_2`"] = $theValue;
				
					// Field fecha_registro
					$theValue = ($GLOBALS["x_fecha_pago"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_pago"]) . "'" : "Null";
					$fieldList["`fecha_registro`"] = $theValue;
				
					// Field fecha_registro
					$theValue = ($x_fecha_movimiento != "") ? " '" . ConvertDateToMysqlFormat($x_fecha_movimiento) . "'" : "Null";
					$fieldList["`fecha_pago`"] = $theValue;
				
					// Field importe
					$theValue = ($x_importe != "") ? " '" . doubleval($x_importe) . "'" : "NULL";
					$fieldList["`importe`"] = $theValue;
				
					// insert into database
					$sSql = "INSERT INTO `recibo` (";
					$sSql .= implode(",", array_keys($fieldList));
					$sSql .= ") VALUES (";
					$sSql .= implode(",", array_values($fieldList));
					$sSql .= ")";
					phpmkr_query($sSql, $conn) or die("Failed to execute query....: " . phpmkr_error() . '<br>SQL: ' . $sSql);
					
					$x_recibo_id = mysql_insert_id();
					echo "query ;".$sSql;
					
					//echo "insertamos en recibo $x_recibo_id *******<br>";
				}
				
				
					if(empty($x_s_iva)){
						$x_s_iva = 0;
					}
					if(empty($x_s_iva_mor)){
						$x_s_iva_mor = 0;
					}

				
					$x_s_importe_total = $x_s_importe + $x_s_interes + $x_s_interes_moratorio + $x_s_iva + $x_s_iva_mor;
				
					$x_importe = FormatNumber($x_importe,2,0,0,0);
					$x_s_importe_total = FormatNumber($x_s_importe_total,2,0,0,0);
					
					// =========== CASO 1  ================//
					// EL IMPORTE DEL PAGO ES IGUAL AL IMPORTE DEL VENCIMIENTO
					//echo "<br>ANTES DE ENTRAR AL CASO EL IMPORTE ES DE ".$x_importe."........";
					if(doubleval($x_importe) == doubleval($x_s_importe_total)){
						echo "<BR>entramos  caso 1 <br> el ".$x_importe."es igual a ".$x_s_importe_total."<br>";
					$sSql = "update vencimiento set 
					vencimiento_status_id = 2 ";
					//importe = ".$x_s_importe.",
					//interes = ".$x_s_interes.",
					//iva = ".$x_s_iva.",	
					//iva_mor = ".$$x_s_iva_mor.",		
					//interes_moratorio = ".$x_interes_moratorio.",
					//total_venc = ".$x_importe." 
					$sSql .= " where vencimiento_id =  ".$x_s_vencimiento_id;
					$responseUsV = phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
					echo "update ven..pagado".$sSql."<br>";
					
					if($responseUsV){
						$GLOBALS["x_pagos_aplicados"] ++;						
						}else{
							$GLOBALS["x_pagos_NO_aplicados"] ++;						
							}
					phpmkr_free_result($responseUsV);
					
					//actualizamos el valor de importe
					$x_importe_fin = 0;
					
					$sSql = "insert into recibo_vencimiento values(0,".$x_s_vencimiento_id .",$x_recibo_id)";
					$x_result = phpmkr_query($sSql, $conn);
					echo "INSERTAMOS EN RECIBO_ENCIMEINTO:". $sSql."-";
					if(!$x_result){
						echo phpmkr_error() . '<br>SQL: ' . $sSql;
						phpmkr_query('rollback;', $conn);	 
						exit();
					}	
					//echo "<br> salimos caso 1_________________________________<br>";
					}

				
					// =========== CASO 2  ================//
					// EL IMPORTE DEL PAGO ES MENOR AL IMPORTE DEL VENCIMIENTO
					// EN ESTE CASO SE  MATA EL VENCIMIENTO POR LA CANTIDAD PAGADA
					// SI LA CANTIDAD FALTANTE ES MENOR A $100 SE VA A REMANENTE
					// SI LA CANTIDAD FALTANTE ES MAYOR A  $100 SE GENERA UN NUEVO VENCIMIENTO, CON LA MISMA FECHA POR LA CATIDAD RESTANTE
					//REMANENTE
					/*
					Si faltan menos de 100 pesos se genrea vencimiento con status remanente y con numero de vencimiento igual pero con una r la fecha de pago sera un periodo extra eal fin del la fecha de vencimiento del credito.
					*/
										
					if( (doubleval($x_importe) > 0) && (doubleval($x_importe) < doubleval($x_s_importe_total))){	
					
						//moratorios
						if($x_importe > ($x_s_interes_moratorio + $x_s_iva_mor) || $x_importe == ($x_s_interes_moratorio + $x_s_iva_mor)){
					
					
							$x_p_interes_moratorio = $x_s_interes_moratorio;
							$x_p_iva_mor = $x_s_iva_mor;
							
							$x_importe = $x_importe - ($x_s_interes_moratorio + $x_s_iva_mor);
							
							//interes ord
							if($x_importe > ($x_s_interes + $x_s_iva) || $x_importe == ($x_s_interes + $x_s_iva)){
					
						
								$x_p_interes = $x_s_interes;
								$x_p_iva = $x_s_iva;
					
								$x_importe = $x_importe - ($x_s_interes + $x_s_iva);			
								
								$x_p_importe = $x_importe;		
								$x_d_importe = $x_s_importe - $x_p_importe;
								$x_d_interes = 0;
								$x_d_iva = 0;
								$x_d_interes_moratorio = 0;
								$x_d_iva_mor = 0;
								$x_d_importe_total = $x_d_importe + $x_d_interes + $x_d_iva + $x_d_interes_moratorio + $x_d_iva_mor;
								
								//remanente < 100
								
								//echo "dias vencidos....".$x_dias_vencidos_m." ";
								if((intval($x_d_importe) < 100) && (intval($x_dias_vencidos_m) < 7)){
									//echo "dias vencidos menor que 7 y resto menor que $100";
									//echo "fecha vencieinto ultimo =".$x_fecha_vencimiento_remanente.".....";
									$x_s_vencimiento_status_id = 6;
									$x_s_fecha_vencimiento = DateAdd('w',$x_forma_pago,strtotime($x_fecha_vencimiento_remanente));
									$x_s_fecha_vencimiento = strftime('%Y-%m-%d',$x_s_fecha_vencimiento);			
									$x_s_vencimiento_num = intval($x_s_vencimiento_num) + 1000;
									$x_d_fecha_genera_remanente = ConvertDateToMysqlFormat($x_fecha_movimiento);
									echo "aplico remanente....".$x_s_vencimiento_num.".... fecha ".$x_s_fecha_vencimiento." .....";
								}
								
								if($x_c_penalizacion > 0){
									// si penalizacion es mayor de 0 entonces los remitentes se deben generar con la fecha del ultimo vencimiento
									// seleccionamos los datos del ultimo vencimeinto.
									
									$sqlUltimovv = "SELECT fecha_vencimiento FROM  vencimiento WHERE credito_id = $x_credito_id  order by fecha_vencimiento DESC LIMIT 0,1 ";
									$rsUltimovv = phpmkr_query($sqlUltimovv,$conn) or die("Erroe al selccioanr la ultima fecha del vencimeinto".phpmkr_error()."sql:".$sqlUltimovv);
									$rowUltimovv = phpmkr_fetch_array($rsUltimovv);
									$x_fecha_gen_new = $rowUltimovv["fecha_vencimiento"];	
									$x_d_fecha_genera_remanente = $x_fecha_gen_new;
									echo "FECHA GENRA REMANENTE ++++".$x_d_fecha_genera_remanente."<br>";								
									}
								
							}else{
					
								if(!is_null($x_s_iva) && !empty($x_s_iva) && intval($x_s_iva) > 0){
									$x_p_interes = $x_importe / 1.16;
									$x_p_iva = $x_p_interes * .16;
								}else{
									$x_p_interes = $x_importe;
									$x_p_iva = 0;
								}
								
								$x_p_importe = 0;		
								$x_d_importe = $x_s_importe - $x_p_importe;
								$x_d_interes = $x_s_interes - $x_p_interes;
								$x_d_iva = $x_s_iva - $x_p_iva;
								$x_d_interes_moratorio = 0;
								$x_d_iva_mor = 0;
								$x_d_importe_total = $x_d_importe + $x_d_interes + $x_d_iva + $x_d_interes_moratorio + $x_d_iva_mor;
								
							}
						}else{
					
							if(!is_null($x_s_iva_mor) && !empty($x_s_iva_mor) && intval($x_s_iva_mor) > 0){
								$x_p_interes_moratorio = $x_importe / 1.16;
								$x_p_iva_mor = $x_p_interes_moratorio * .16;
							}else{
								$x_p_interes_moratorio = $x_importe;
								$x_p_iva_mor = 0;
							}
							$x_p_interes = 0;
							$x_p_iva = 0;
							$x_p_importe = 0;		
							
							$x_d_importe = $x_s_importe - $x_p_importe;
							$x_d_interes = $x_s_interes - $x_p_interes;
							$x_d_iva = $x_s_iva - $x_p_iva;
							$x_d_interes_moratorio = $x_s_interes_moratorio - $x_p_interes_moratorio;
							$x_d_iva_mor = $x_s_iva_mor - $x_p_iva_mor;
							$x_d_importe_total = $x_d_importe + $x_d_interes + $x_d_iva + $x_d_interes_moratorio + $x_d_iva_mor;
							
						}
						$x_p_importe_total = $x_p_importe + $x_p_interes + $x_p_iva + $x_p_iva_mor + $x_p_interes_moratorio;
					
						$sSql = "update vencimiento set 
						vencimiento_status_id = 2,
						importe = $x_p_importe,
						interes = $x_p_interes,
						iva = $x_p_iva,	
						iva_mor = $x_p_iva_mor,		
						interes_moratorio = $x_p_interes_moratorio,
						total_venc = ".$x_p_importe_total." 
						where vencimiento_id =  ".$x_s_vencimiento_id."";
						phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
						echo "<br> caso 2 update vencimeinto".$sSql;
					
					
					
						// la fecha en que se pago el vencimeinto es la fecha que se utiliza en el campo fecha genera remanente...						
					//	$x_d_fecha_genera_remanente = ConvertDateToMysqlFormat($x_fecha_movimiento);
																						
					
						if($x_s_vencimiento_num > 1000){
								$sSql = "insert into vencimiento values(0,$x_s_credito_id, $x_s_vencimiento_num,$x_s_vencimiento_status_id, '$x_s_fecha_vencimiento', $x_d_importe, $x_d_interes, $x_d_interes_moratorio, $x_d_iva, $x_d_iva_mor, $x_d_importe_total, '$x_d_fecha_genera_remanente')";
							}else{																					
						$sSql = "insert into vencimiento values(0,$x_s_credito_id, $x_s_vencimiento_num,$x_s_vencimiento_status_id, '$x_s_fecha_vencimiento', $x_d_importe, $x_d_interes, $x_d_interes_moratorio, $x_d_iva, $x_d_iva_mor, $x_d_importe_total, 'NULL')";
							}
						$x_result = phpmkr_query($sSql, $conn);
						echo "<br> inseertamos el resto ".$sSql;
						if(!$x_result){
							echo phpmkr_error() . '<br>SQL: ' . $sSql;
							phpmkr_query('rollback;', $conn);	 
							exit();
						}
							//echo $sSql;			
					
					//actulaizamos el valor de importe
					$x_importe_fin = 0;		
					
					
																						
					$sSql = "insert into recibo_vencimiento values(0,".$x_s_vencimiento_id .",$x_recibo_id)";
					$x_result = phpmkr_query($sSql, $conn);
					if(!$x_result){
						echo phpmkr_error() . '<br>SQL: ' . $sSql;
						phpmkr_query('rollback;', $conn);	 
						exit();
					}	
					//echo "INSERTAMOS EN RECIBO_vENCIMEINTO:". mysql_insert_id()."-";
					echo "<br>salimos caso 2<br>---------------------------";
				}//cierra caso dos
				
				
				
				// =========== CASO 3  ================//
				// EL IMPORTE DEL PAGO ES MAYOR AL IMPORTE DEL VENCIMIENTO
				// SE MATA EL VENCIMEINTO ACTUAL Y SE REVIS EL VENCIMIENTO SIGUIENTE PARA PAGARLO O
				// ABONAR UNA CANTIDAD, SI NO SE PAGA COMPLETO, SE MATA EL VENCIMENTO POR LA CANTIDAD
				// Y SE GENERA UNO NUEVO, CON LA CANTIDAD FALTANTE 
				
				
				if(($x_importe > 0) && (doubleval($x_importe) > doubleval($x_s_importe_total))){
					echo"<BR> caso tres <br> el importe del pago ".$x_importe. "es mayor que el vencimeinto" .$x_s_importe_total."<br>";
				$sSql = "update vencimiento set vencimiento_status_id = 2
				where vencimiento_id =  ".$x_s_vencimiento_id;
				$responseS = phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
				echo "<br> update ven".$sSq."<br>";
				if($responseS){
						$GLOBALS["x_pagos_aplicados"] ++;						
						}else{
							$GLOBALS["x_pagos_NO_aplicados"] ++;							
							}
						
				$x_importe_fin = $x_importe - $x_s_importe_total;
				
				
				$sSql = "insert into recibo_vencimiento values(0,".$x_s_vencimiento_id .",$x_recibo_id)";
					$x_result = phpmkr_query($sSql, $conn);
					if(!$x_result){
						echo phpmkr_error() . '<br>SQL: ' . $sSql;
						phpmkr_query('rollback;', $conn);	 
						exit();
					}	
				
			//echo "INSERTAMOS EN RECIBO_ENCIMEINTO:". mysql_insert_id()."-";
				//echo "<br>Salimos caso 3<br>_______________________________";
				}
				
				
				
				
			$x_importe = $x_importe_fin;
			echo "<br>---------credito ".$x_credito_id."importe al final del los casos ".$x_importe."---------";
			$x_recibo = 2;
			// salimos del if numero de vencimientos por pagar mayor a 0
			}else{
				//aun hay saldo del deposito, pero ya no hay vencimietos para liquidar
				$GLOBALS["x_pago_sobrante"] =  $GLOBALS["x_pago_sobrante"] + $x_importe;
				//echo "credito_id :".$x_credito_id."";
				$x_importe = 0;
				echo "<BR> ************************************************abajo si lo encontro $x_credito_id*************************************************</BR>";
				}
				
		
				
				
				
				
				
				
				
				
				
				
				
			}// while importe > 0
			
			
			if($x_c_penalizacion > 0 && $x_c_garantia_liquida > 0){
			#aqui regresamos el status del ultimo vencimiento a pendiente si esque lo teniamos en status 9
			#echo "DENTRO DE LA ULTIMA VALIDACION<BR>";
			#
			$sqlUpdateUltimoVencmeinto = "UPDATE vencimiento SET  vencimiento_status_id  = 1 WHERE credito_id = $x_credito_id AND ";
			$sqlUpdateUltimoVencmeinto .= " vencimiento_num = $x_ultimo_vencimiento_nn AND vencimiento_status_id  = 9 ";
			#	  $rsUpdateUltimoVencmeinto = phpmkr_query($sqlUpdateUltimoVencmeinto, $conn) or die ("Erroe al actulizar el vencimeito ultimo para no ser tomado en ecuenta ". phpmkr_error()."sql :".$sqlUltimoVencimiento); 
				  
			//VERIFICAMOS SI YA NO TIENE NUNGUNA COMISION O NINGUNA PENALIZACION PENDIENTE POR PAGAR
			// Y SI TIENE GARANTIA LIQUIDA SI ES ASI
			// LIQUIDAMOS EL CREDITO	  
			
			$SQLpp = "SELECT count(*) as pagos_pendietes FROM vencimiento where vencimiento_status_id in (1,3,9) and credito_id = $x_credito_id ";
			$RSPP = phpmkr_query($SQLpp,$conn) or die ("Error al seleccionar los vencimeintos faltantes del credito". phpmkr_error()."sql:".$SQLpp);
			$ROWpp = phpmkr_fetch_array($RSPP);
			$x_numero_vencimientos_pendientes = $ROWpp["pagos_pendietes"];
			
			//seleccionamos el importe de la garantia
			$SQLpp = "SELECT monto FROM garantia_liquida where credito_id = $x_credito_id ";
			$RSPP = phpmkr_query($SQLpp,$conn) or die ("Error al seleccionar los vencimeintos faltantes del credito". phpmkr_error()."sql:".$SQLpp);
			$ROWpp = phpmkr_fetch_array($RSPP);
			$x_monto_garantia = $ROWpp["monto"];
			
			/************************************************
			******** aqui garatia liquida doble o simple  zulma ***
			************************************************/	
			$x_vencimientos_pendintes_por_cambio = 0;
			
			
			if($x_c_forma_pago == 1){
				$x_vencimientos_pendintes_por_cambio = 2;
				}else{
					$x_vencimientos_pendintes_por_cambio = 1;
					}		
			#echo "VENCIMIENTOS PENDIENTES POR CAMBIO".$x_vencimientos_pendintes_por_cambio."<BR>";
			if(($x_numero_vencimientos_pendientes == $x_vencimientos_pendintes_por_cambio) && ($x_c_garantia_liquida > 0)){
				echo "entra al if de venc.pendientes por cambio<br>";
				// 1.- generamos el recibo
				// 2.- pagamos el (los) vencimiento(s)
				// 3.- generamos recibo_vencimeinto
				// 4.- cambiamos status de la garantia
				// 5.- el recibo en la referencia dira garantia
				
				// seleccionamos los vencimientos que tienen estatus de 9 y que corresponde al credito
				$sqlV9 = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id AND vencimiento_status_id IN (1,9) "; 
				$rsV9 = phpmkr_query($sqlV9,$conn)or die("Error al seleccionar los V9".phpmkr_error()."sql:".$sqlV9);
				$x_lista_v9_n = " ";
				$x_lista_v9_id = " ";
				while($rowV9 = phpmkr_fetch_array($rsV9)){
					$x_v9n = $rowV9["vencimiento_num"];
					$x_v9id = $rowV9["vencimiento_id"];
					$x_lista_v9_n = $x_lista_v9_n.$x_v9n.", ";
					$x_lista_v9_id = $x_lista_v9_id.$x_v9id.", ";
					}
					$x_lista_v9_n =  trim($x_lista_v9_n,", ");
					$x_lista_v9_ne = "SE PAGO CON GARANTIA, ".$x_lista_v9_n;
					$x_referencia_pago2 = $x_lista_v9_ne;
				    $x_lista_v9_id = trim($x_lista_v9_id,", ");
					
					echo "LSITA V9 ID".$x_lista_v9_id."<BR>";
					
					$fieldList = NULL;
					// Field recibo_status_id
					$fieldList["`recibo_status_id`"] = 1;
											
					$theValue = ($GLOBALS["x_banco_id"] != "") ? intval($GLOBALS["x_banco_id"]) : "0";
					$fieldList["`banco_id`"] = $theValue;
					
					// Field medio_pago_id
					$theValue = ($GLOBALS["x_medio_pago_id"] != "") ? intval($GLOBALS["x_medio_pago_id"]) : "NULL";
					$fieldList["`medio_pago_id`"] = $theValue;
				
					// Field referencia_pago
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_pago_1"]) : $GLOBALS["x_referencia_pago_1"]; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`referencia_pago`"] = $theValue;
				
					// Field referencia_pago
					$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_referencia_pago2) : $x_referencia_pago2; 
					$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
					$fieldList["`referencia_pago_2`"] = $theValue;
				
					// Field fecha_registro
					$theValue = ($GLOBALS["x_fecha_pago"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_pago"]) . "'" : "Null";
					$fieldList["`fecha_registro`"] = $theValue;
				
					// Field fecha_registro
					$theValue = ($x_fecha_movimiento != "") ? " '" . ConvertDateToMysqlFormat($x_fecha_movimiento) . "'" : "Null";
					$fieldList["`fecha_pago`"] = $theValue;
				
					// Field importe
					$theValue = ($x_monto_garantia != "") ? " '" . doubleval($x_monto_garantia) . "'" : "NULL";
					$fieldList["`importe`"] = $theValue;
				
					// insert into database
					$sSql = "INSERT INTO `recibo` (";
					$sSql .= implode(",", array_keys($fieldList));
					$sSql .= ") VALUES (";
					$sSql .= implode(",", array_values($fieldList));
					$sSql .= ")";
					phpmkr_query($sSql, $conn) or die("Failed to execute query....: " . phpmkr_error() . '<br>SQL: ' . $sSql);
				#	echo "INSERTA EN RECIBO".$sSql."<BR>";
					$x_recibo_id_v9 = mysql_insert_id();
				
				$x_lista_v9_id_a = explode(", ",$x_lista_v9_id);
				    //insertamos en recibo vecimeinto							
					foreach($x_lista_v9_id_a as $v9id){
						// por cada elemento en la lista, pagamos el vencimiento e insertamos en vencimiento_recibo						
						$sSql = "insert into recibo_vencimiento values(0,".$v9id.",$x_recibo_id_v9)";
						$x_result = phpmkr_query($sSql, $conn);
						echo "INSERTAMOS EN RECIBO_ENCIMEINTO: ii". mysql_insert_id()."-";
						echo "INSERTAMOS EN REC_VEN".$sSql."<BR>";
						if(!$x_result){
							echo phpmkr_error() . '<br>SQL: ' . $sSql;
							phpmkr_query('rollback;', $conn);	 
							exit();
						}	
						
						//actualizamos el status del vencimiento a pagado
						$sqlUV9 = "UPDATE vencimiento SET vencimiento_status_id = 2 WHERE vencimiento_id = $v9id and vencimiento_status_id in (1,9)";
						$x_result = phpmkr_query($sqlUV9,$conn);
						if(!$x_result){
							echo phpmkr_error() . '<br>SQL: ' . $sqlUV9;
							phpmkr_query('rollback;', $conn);	 
							exit();
						}	
						echo "ACTR VENCI".$sqlUV9."<BR>";
						}	
						
						//actualizamos el status de la garantia
						$updag = "UPDATE  garantia_liquida SET status = 2 WHERE  credito_id = $x_credito_id and status= 1";
						$x_result = phpmkr_query($updag,$conn);
						if(!$x_result){
							echo phpmkr_error() . '<br>SQL: ' . $updag;
							phpmkr_query('rollback;', $conn);	 
							exit();
						}	
						
			
				
				$GLOBALS["x_no_aplicados_reporte"] .= "<tr>
											  <td><font color=\"#2ba739\" style=\"font-size:11px;\">$x_ref_pago</font>&nbsp; &nbsp; &nbsp;</td><td>&nbsp;</td>
											  <td><font color=\"#2ba739\" style=\"font-size:11px;\" >Se APLICO el monto de la garantia liquida al ultimo pago</font></td>
											  </tr>";
				
				
				}
			}
			
			
			
			
			 ////echo"Salimos del while";
			
				//numeros de los vencimientos pagados con el mismo recibo
				//------------------------------------------------------//	
				if($x_recibo_id > 0){
				$sqlnv = "SELECT `vencimiento_num` FROM `vencimiento` WHERE vencimiento_id";
				$sqlnv .= " IN (SELECT vencimiento_id FROM `recibo_vencimiento` where recibo_id = $x_recibo_id )";
				$responsenv = phpmkr_query($sqlnv, $conn) or die ("Error al seleccinar los vencimiento pagados con el mismo recivo");
				$x_numero_vencimientos = "";
				
				while($rownvs = phpmkr_fetch_array($responsenv)){
					$x_numero_vencimientos .= $rownvs["vencimiento_num"].", ";
					}
				$x_numero_vencimientos = rtrim($x_numero_vencimientos,", ");
				
				$x_numero_pagos = phpmkr_num_rows($responsenv);
				if($x_numero_pagos > 1){
				$x_ref_pagos = "Pago vencimiento ".$x_numero_vencimientos;
				
				
				$sSqlUr = "UPDATE recibo SET referencia_pago_2  = \"$x_ref_pagos\" WHERE  recibo_id = $x_recibo_id ";
				phpmkr_query($sSqlUr,$conn) or die("Error al actulizar referencia de pago dos".phpmkr_error()."sql :".$sSqlUr);
				}else{
					$sSqlUr = "UPDATE recibo SET referencia_pago_2  = \"Pag mas. 0\" WHERE  recibo_id = $x_recibo_id ";
					phpmkr_query($sSqlUr,$conn) or die("Error al actulizar referencia de pago dos".phpmkr_error()."sql :".$sSqlUr);
					}
				}// if x_recibo_id > 0
				
				// aqui se insertan el codigo para el envio de mensajes por pago efectuado
				
				// se busca cuanto se pago
				// cuantos vemcientos son en total
				// cual es que se pago
				// la fecha en que se pago
				// cua le s el vencimeinto que se debe de pagar 
				
				//insertamos el codigo para el mensaje que se ha recibido el pago  del cliente
	################################################################################################################## 
	#####################################################  SMS  ######################################################
	################################################################################################################## 		
	// mandamos el mensaje de pago efectuado
	
			$sqlClientel = " SELECT cliente_id FROM solicitud_cliente JOIN solicitud ON solicitud.solicitud_id = solicitud_cliente.solicitud_id JOIN ";
			$sqlClientel .= " credito ON credito.solicitud_id = solicitud.solicitud_id WHERE credito_id = $x_credito_id ";
			$rsClientel = phpmkr_query($sqlClientel, $conn) or die ("Error al seleccionar el cliente _id". phpmkr_error(). "sql:". $sqlClientel);
			$rowClientel = phpmkr_fetch_array($rsClientel);
			$x_cliente_idl = 	$rowClientel["cliente_id"];
			
				if (!empty($x_cliente_idl)){
		 $x_no_celular  = "";
		 $sqlCelular = "SELECT numero FROM telefono WHERE cliente_id = $x_cliente_idl  AND telefono_tipo_id = 2 ORDER BY `telefono_id` DESC ";
		 $rsCelular = phpmkr_query($sqlCelular, $conn) or die ("Error al seleccioanr el numero de celuar". phpmkr_error()."sql:".$sqlCelular);
		 $rowCelular = phpmkr_fetch_array($rsCelular);
		 $x_no_celular = $rowCelular["numero"];
		 $x_compania = $rowCelular["compania_id"];
		 
		// $x_no_celular = "5540663927";
		 
		 //SALDO
		$sql_saldo =  "SELECT SUM(total_venc)  as saldo FROM vencimiento WHERE credito_id = $x_credito_id  and vencimiento.vencimiento_status_id in (1,3,6)";
		$rs_saldo = phpmkr_query($sql_saldo, $conn) or die ("Error al seleccionar el saldo  en pago efectuado". phpmkr_error()."sql :". $sql_saldo);
		$row_saldo = phpmkr_fetch_array($rs_saldo);
		$x_saldo_por_pagar = FormatNumber($row_saldo["saldo"],2,0,0,0);		
		 
		 
		 // ULTIMO VENCIMIENTO
		$sql_ult_vec = "SELECT  num_pagos FROM credito WHERE credito_id = $x_credito_id";
		$rs_ult_ven = phpmkr_query($sql_ult_vec, $conn) or die ("Error al seleccionar ultimo paga en apago efectuado pagos". phpmkr_error()."sql:". $sql_ult_vec);
		$row_ult_ven = phpmkr_fetch_array($rs_ult_ven);
		$x_no_ultimo_vencimiento = $row_ult_ven["num_pagos"];
		
		
		//IMPORTE PAGADO
		$sql_ult_vec = "SELECT  importe AS pagado FROM recibo WHERE recibo_id = $x_recibo_id ";
		$rs_ult_ven = phpmkr_query($sql_ult_vec, $conn) or die ("Error al seleccionar ultimo paga en apago efectuado pagos". phpmkr_error()."sql:". $sql_ult_vec);
		$row_ult_ven = phpmkr_fetch_array($rs_ult_ven);
		$x_pagado = $row_ult_ven["pagado"];
		
		 
		 
		 // ya tenemos el numero de celular  y los datos del mensaje, ahora hacemos el envio del mensaje de texto al celular
		 if(!empty($x_no_celular) &&  $x_no_celular != "5555555555"){
			// $x_pagado  =  $GLOBALS["x_importe"];
			 $x_fecha_de_pago = $GLOBALS["x_fecha_pago"];
			 $x_vencimiento_num = $x_ref_pagos;
			 $x_fecha = ConvertDateToMysqlFormat($GLOBALS["x_fecha_pago"]);
						#si tenemos el numero de celular guardado entonces enviamos el mensaje
						$x_mensaje = "FINANCIERA CREA: PAGO EFECTUADO $x_pagado el $x_fecha_de_pago  ";
						$x_mensaje .= "PAGO $x_vencimiento_num DE $x_no_ultimo_vencimiento  SALDO $x_saldo_por_pagar" ;							
						echo "MENSAJE :". $x_mensaje."<BR>";										
						//Varios destinatarios
						$para  = 'sms@financieracrea.com'; // atención a la coma
						//subject
						$titulo = $x_no_celular;						
						//$cabeceras = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras = 'From: zortiz@createc.mx';
						//Mail it						
						mail($para, $titulo, $x_mensaje, $cabeceras);	
						$x_contador_3 ++;
						
						$tiposms = "8";
						$chip = "";
						#cambiamos a chip 2						
						//insertaSmsTabla($conn, , $x_credito_id_liquidado, $tiposms, $chip, $x_mensaje, $titulo, $x_compania);
						$sqlInsert = "INSERT INTO `msm` (`sms_id`, `cliente_id`, `credito_id`, `sms_tipo`, `texto`, `fecha`, `chip`, `celular`) ";
						$sqlInsert .= " VALUES (NULL, $x_cliente_idl, $x_credito_id, $tiposms, '$x_mensaje ', '$x_fecha', '$x_chip', '$x_no_celular');";
						$result = $rsInsert = phpmkr_query($sqlInsert, $conn) or die ("Error al inserta en sms tabla". phpmkr_error()."sql :". $sqlInsert);
	 
						
						
						
			}			
		}		
				
				
				
				
				
				
				
				
				
				
				
				
			
			}else{
				
				
				$sqlStausc = "SELECT credito_status_id FROM credito WHERE `tarjeta_num` =  $x_ref_pago ";
				$reponseCreditost = phpmkr_query($sqlStausc, $conn)or die ("error el seleccionar el estaus del credito;".phpmkr_error()."sql: ".$sqlStausc);
				$rowad = phpmkr_fetch_array($reponseCreditost);
				$status =  $rowad["credito_status_id"];
				
				
				if ($x_sobrante == 1){
					if($x_credito_liquidado == 1){						
						$GLOBALS["x_no_aplicados_reporte"] .= "<tr>
											  <td><font color=\"#2d41fb\" style=\"font-size:11px;\">$x_ref_pago</font>&nbsp; &nbsp; &nbsp;</td><td>&nbsp;</td>
											  <td><font color=\"#2d41fb\" style=\"font-size:11px;\" >El credito fue liquidado por anticipado. Se debe regresar el monto de la garantia</font></td>
											  </tr>";
						
						}else{
					
					$GLOBALS["x_importe_NO_aplicado"] =	$GLOBALS["x_importe_NO_aplicado"] + $x_importe;
						$GLOBALS["x_no_aplicados_reporte"] .= "<tr>
											  	  <td><font color=\"#FF0000\" style=\"font-size:11px;\">$x_ref_pago</font>&nbsp; &nbsp; &nbsp;</td><td>&nbsp;</td>
											  <td><font color=\"#FF0000\" style=\"font-size:11px;\" >El importe pagado es mayor que el importe de los vencimientos pendientes.</font></td>
											  </tr>";
						$GLOBALS["x_pagos_NO_aplicados"]++;
						}
					
					} else if($x_numero_creditos_activos > 1){
						
						$GLOBALS["x_importe_NO_aplicado"] =	$GLOBALS["x_importe_NO_aplicado"] + $x_importe;
						$GLOBALS["x_no_aplicados_reporte"] .= "<tr>
											  	  <td><font color=\"#FF0000\" style=\"font-size:11px;\">$x_ref_pago</font>&nbsp; &nbsp; &nbsp;</td><td>&nbsp;</td>
											  <td><font color=\"#FF0000\" style=\"font-size:11px;\" >El n&uacute;mero de referencia tiene asociado m&aacute;s de 1 cr&eacute;dito activo.</font></td>
											  </tr>";
						$GLOBALS["x_pagos_NO_aplicados"]++;
						
						}else{				
						if($status == 3){
							// credito liuidado
								$GLOBALS["x_importe_NO_aplicado"]= 	$GLOBALS["x_importe_NO_aplicado"] + $x_importe;
						
								$GLOBALS["x_no_aplicados_reporte"] .= "<tr>
														  <td><font color=\"#FF0000\" style=\"font-size:11px;\">$x_ref_pago</font>&nbsp; &nbsp; &nbsp;</td><td>&nbsp;</td>
													  <td><font color=\"#FF0000\" style=\"font-size:11px;\" >Cr&eacute;dito liquidado. </font></td>
													  </tr>";
								
								$GLOBALS["x_pagos_NO_aplicados"]++;
							
							}else if($status == 2){
									$GLOBALS["x_importe_NO_aplicado"]= 	$GLOBALS["x_importe_NO_aplicado"] + $x_importe;
						
									$GLOBALS["x_no_aplicados_reporte"] .= "<tr>
														  <td><font color=\"#FF0000\" style=\"font-size:11px;\">$x_ref_pago</font>&nbsp; &nbsp; &nbsp;</td><td>&nbsp;</td>
													  <td><font color=\"#FF0000\" style=\"font-size:11px;\" >Cr&eacute;dito  cancelado. </font></td>
													  </tr>";
								
									$GLOBALS["x_pagos_NO_aplicados"]++;						
								
								}else{
									$GLOBALS["x_importe_NO_aplicado"]= 	$GLOBALS["x_importe_NO_aplicado"] + $x_importe;
						
									$GLOBALS["x_no_aplicados_reporte"] .= "<tr>
														  <td><font color=\"#FF0000\" style=\"font-size:11px;\">$x_ref_pago</font>&nbsp; &nbsp; &nbsp;</td><td>&nbsp;</td>
													  <td><font color=\"#FF0000\" style=\"font-size:11px;\" >Cr&eacute;dito no localizado. </font></td>
													  </tr>";
								
									$GLOBALS["x_pagos_NO_aplicados"]++;					
									
									
									}
			
						}//fin else
				}// if credito_id > 0
		
		
		
				
		$GLOBALS["x_total_de_registros"]++;
		
		
		
		
		
		
		

	/*$sSqlWrk = "SELECT credito_id  FROM vencimiento where vencimiento_id = ".$x_s_vencimiento_id."" ;
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query...." . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$rowwrk = phpmkr_fetch_array($rswrk);
	$x_credito_id = $rowwrk["credito_id"];
	@phpmkr_free_result($rswrk);*/
	
	if($x_credito_id > 0){
		////echo "<br> entramos a pendientes<br>";

	
	
		//************************************* ULTIMO PAGO ******************************************//
		//************************************* CASOS  CRM  *****************************************//
		//************************************* FOLIOS RIFA *****************************************//
		//VALIDA PAGOS FALTANTES	
		$sSqlWrk = "SELECT count(*) as pendientes FROM vencimiento where credito_id = $x_credito_id and vencimiento_status_id in (1,3,6,9)";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$rowwrk = phpmkr_fetch_array($rswrk);
		$x_pendientes = $rowwrk["pendientes"];
		@phpmkr_free_result($rswrk);
		
		$sSqlWrk = "SELECT solicitud_id, credito_tipo_id  FROM credito where credito_id = $x_credito_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$rowwrk = phpmkr_fetch_array($rswrk);
		$x_solicitud_id = $rowwrk["solicitud_id"];
		$x_tipo_credito = $rowwrk["credito_tipo_id"];
		@phpmkr_free_result($rswrk);

	if($x_pendientes == 0){
		//echo "<br> pendientes igual que 0 <br>";
	//FINIQUITA CREDITO
		// selecciona status anterior			
			$sqlFVR2 = "SELECT credito_status_id FROM credito WHERE credito_id = $x_credito_id";
			$responseFVR2 = phpmkr_query($sqlFVR2, $conn) or die ("error al seleccionar el credito_id". phpmkr_error()."sql: ".$sqlFVR2);
			$rowfvr2 = phpmkr_fetch_array($responseFVR2);
			$x_credito_status_id = $rowfvr2["credito_status_id"];
					
	
		$sSqlWrk = "UPDATE credito set credito_status_id = 3 where credito_id = $x_credito_id";
		phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);

		$sSqlWrk = "UPDATE solicitud set solicitud_status_id = 7 where solicitud_id = $x_solicitud_id";
		phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		
		$GLOBALS["x_folio_triple"] = 1;
		//seleccionam22os los vencimientos
		$sqlVencimientos = "SELECT * FROM vencimiento where credito_id = $x_credito_id";
		$responseVen = phpmkr_query($sqlVencimientos,$conn)  or die("Error en select vencimientos".phpmkr_error()."sql:".$sqlVencimientos);
		
		$x_vencimiento_id = "";
		$x_fecha_vencimiento = "";
		while($rowVencimientos = phpmkr_fetch_array($responseVen)){
			//echo "<br>entro a vencimeinto <P><br>";
			//echo "<p>";
			// mientras hay vencimientos
			$x_vencimiento_id = $rowVencimientos["vencimiento_id"];
			$x_fecha_vencimiento_act = $rowVencimientos["fecha_vencimiento"];
			$GLOBALS["x_fecha_vencimiento_ACT"] = $rowVencimientos["fecha_vencimiento"];
			
			$sqlRecibo_Vencimientos = "SELECT * FROM recibo_vencimiento  where vencimiento_id = $x_vencimiento_id";
			$responseREC_VEN = phpmkr_query($sqlRecibo_Vencimientos,$conn)  or die("Error en selectrecibo_vencimientos".phpmkr_error()."sql:".$sqlRecibo_Vencimientos);
			while($rowRec_ven = phpmkr_fetch_array($responseREC_VEN)){
				
				//echo "<br> entro a ven_recibo del vencimeinto numero".$x_vencimiento_id." <br>";
				$x_recibo_vencimiento_id = $rowRec_ven["recibo_vencimiento_id"];
				$x_recibo_id =  $rowRec_ven["recibo_id"];
				
				
				$sqlRecibo = "SELECT * FROM recibo  where recibo_id = $x_recibo_id";
				$responseRec = phpmkr_query($sqlRecibo ,$conn)  or die("Error en select recibo".phpmkr_error()."sql:".$sqlRecibo );
				
				while($rowRecibos = phpmkr_fetch_array($responseRec)){
					//echo "<br>entro a recibo del recibo numero".$x_recibo_id."<p>";
					$x_recibo_id = $rowRecibos["recibo_id"];
					$x_fecha_pago = $rowRecibos["fecha_pago"];
					
					$fecha_del_vencimiento = $GLOBALS["x_fecha_vencimiento_ACT"];
					/*$fecha_del_vencimiento = strtotime($fecha_del_vencimiento);   
					$fecha_del_pago = '"'.$x_fecha_pago.'"'
					$fecha_del_pago = strtotime($fecha_del_pago);*/
					
					//echo "fecha_vencimiento".$fecha_del_vencimiento."<p>";				
					//echo "fecha_pago".$x_fecha_pago."<p>";
					//echo "fecha_del_vencimiento == fecha_del_pago".$fecha_del_vencimiento == $fecha_del_pago."";
					//echo "fecha_del_pago < fecha_del_vencimiento".$fecha_del_pago < $fecha_del_vencimiento."-";
					if(($fecha_del_vencimiento == $x_fecha_pago)||($x_fecha_pago < $fecha_del_vencimiento)){
						//echo("fecha vencimiento es igual a fecha pago o es menor la fecha de vencimiento");
						if($GLOBALS["x_folio_triple"] == 1){
						$GLOBALS["x_folio_triple"] = 1;	
						
						
						//echo "folio triple es =".$GLOBALS["x_folio_triple"]."-<p>"; 
						}
					}else{
						
						//echo "fecha pago es mayo a fecha vencimeinto";
							$GLOBALS["x_folio_triple"] = 0;
								//echo "folio triple es =".$GLOBALS["x_folio_triple"]."-<p>"; 
							}
					
					
					
					}//while recibos
					phpmkr_free_result($responseRec);
				
				}	// while recibo vencimeinto
				phpmkr_free_result($responseREC_VEN);
		
													
		}// fin while vencimientos
		 phpmkr_free_result($responseVen);
		 
		 // compara fechas
		 
		
		 
		
		//$fecha_actual = strtotime(date("d-m-Y H:i:00",time())); 
		//$fecha_inicio_sorteo = strtotime("2011-04-01");
		//$fecha_limite_sorteo = strtotime("2011-12-04");
		
		$fecha_actual = strtotime(date("Y-m-d ",time())); 
		$fecha_inicio_sorteo = strtotime("2011-04-01");
		$fecha_limite_sorteo = strtotime("2011-12-10");
		//$fecha_limite_sorteo = strtotime("04-04-2011 21:00:00");
		
		
		
		if($x_tipo_credito != 2){
		if($GLOBALS["x_folio_triple"] == 1){
			//pagos puntuales 3 folios	
		#	echo "entro a folio triple 1 en credito individual";	  
		if(($fecha_actual > $fecha_inicio_sorteo)&&($fecha_actual < $fecha_limite_sorteo)){ 
		//fecha valida para generar los folios de la rifa
			#	echo "entro a fecha del sorteo";
			//generamos la clave del folio
			$x_cont = 1;
			while($x_cont <= 3){
			#	echo "entro a while 3<p>";
				
					$sSqlFOL = "SELECT count(*) as folios FROM folio_rifa ";
					$rswrkF = phpmkr_query($sSqlFOL,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlFOL);
					$rowwrkF = phpmkr_fetch_array($rswrkF);
					$x_numero_folios = $rowwrkF["folios"];
					@phpmkr_free_result($rswrkF);

				
				if($x_numero_folios < 2000){
				#	echo "numero de folios menor de 2000";
			//alimentamos el generador de aleatorios
			//mt_srand((double)microtime()*1000000);			
			//mt_srand (microtime()*1000000);
			//generamos un número aleatorio
			//$numero_aleatorio = mt_rand(1,2000);
		// SE CAMBIA LA FORMA DE GENERAR EL FOLIO A HORA SERA CONSECUTIVO
		
		$sqlFolio = "SELECT MAX(folio) as ultimo_folio FROM folio_rifa";
		$responseFolio = phpmkr_query($sqlFolio,$conn)or die("error en folio max".phpmkr_error()."sql: ".$sqlFolio);
		$rowFolio = phpmkr_fetch_array($responseFolio);
		$x_ultimo_folio =  $rowFolio["ultimo_folio"];
		$x_folio_nuevo = intval($x_ultimo_folio)+ 1;
			/*
			$GLOBALS["rowNA"] = 1;
			while($GLOBALS["rowNA"] == 1){
				// el numero ya existe se genera otro
				mt_srand (microtime()*1000000);
				$numero_aleatorio = mt_rand(1,2000);
					
				echo "folio es :".$numero_aleatorio."<p>";
				$sqlNA ="SELECT count(*) as folio_repetido FROM folio_rifa WHERE folio = $numero_aleatorio";
				$responseNA = phpmkr_query($sqlNA, $conn) or die ("Error en select folio");
				$rowNA = phpmkr_fetch_array($responseNA);
				$x_folio_repetido = $rowNA["folio_repetido"];
				echo "folio repatido =".$x_folio_repetido."-<p>";
				if($x_folio_repetido == 0){
					$GLOBALS["rowNA"] = 2;					
					}		
			
			echo $numero_aleatorio. "<p>";
			
			
			}*/// fin while $rowNA= 1
			
			
			//insertar en la tabla folio_rifa
				$fieldList = NULL;	
				// Field visitas
				$theValue = ($x_solicitud_id != "") ? intval($x_solicitud_id) : "0";
				$fieldList["`solicitud_id`"] = $theValue;		
				
				// Field clave
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_nombre_completo) : $x_nombre_completo; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`nombre_completo`"] = $theValue;			
			
				// Field visitas
				$theValue = ($x_folio_nuevo != "") ? intval($x_folio_nuevo) : "0";
				$fieldList["`folio`"] = $theValue;				
				
				// insert into database
				$sSql = "INSERT INTO `folio_rifa` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
				
				$x_result = phpmkr_query($sSql, $conn);
			#	echo $sSql."<br>";
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
			
				}//fin numero de folios
				
				
				$x_cont++;
			} // while 3
			
			
		}// fechas sorteo	
				
				
			
				
			
			}else if($GLOBALS["x_folio_triple"] == 0){
				// pagos no puntales  1 folio
			#	echo "folio_triple = 0";
				
				if(($fecha_actual > $fecha_inicio_sorteo)&&($fecha_actual < $fecha_limite_sorteo)){ 
		//fecha valida para generar los folios de la rifa
	#	echo("fecha dentro sorteo bien");
			//generamos la clave del folio
			$x_cont = 1;
			while($x_cont <= 1){
				
				$sSqlFOL = "SELECT count(*) as folios FROM folio_rifa ";
				$rswrkF = phpmkr_query($sSqlFOL,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlFOL);
				$rowwrkF = phpmkr_fetch_array($rswrkF);
				$x_numero_folios = $rowwrkF["folios"];
				@phpmkr_free_result($rswrkF);				
				
				if($x_numero_folios < 2000 ){
			//alimentamos el generador de aleatorios
			//mt_srand((double)microtime()*1000000);			
			//mt_srand (microtime()*1000000);
			//generamos un número aleatorio
			//$numero_aleatorio = mt_rand(1,2000); 
			
			/*$rowNA = 1;
			while($rowNA == 1){
				// el numero ya existe se genera otro
				mt_srand (microtime()*1000000);
				$numero_aleatorio = mt_rand(1,2000);				
				$sqlNA ="SELECT count(*) as folio_repetido FROM folio_rifa WHERE folio = $numero_aleatorio";
				$responseNA = phpmkr_query($sqlNA, $conn) or die ("Error en insert folio");
				$rowNA = phpmkr_fetch_array($responseNA);
				$x_folio_repetido = $rowNA["folio_repetido"];
				if($x_folio_repetido == 0){
					$rowNA = 2;
					
					}		
			
			echo $numero_aleatorio. "<p>";
			
			
			}*/// fin while $rowNA= 1
			
			//folios consecutivos
		$sqlFolio = "SELECT MAX(folio) as ultimo_folio FROM folio_rifa";
		$responseFolio = phpmkr_query($sqlFolio,$conn)or die("error en folio max".phpmkr_error()."sql: ".$sqlFolio);
		$rowFolio = phpmkr_fetch_array($responseFolio);
		$x_ultimo_folio =  $rowFolio["ultimo_folio"];
		$x_folio_nuevo = intval($x_ultimo_folio)+ 1;
			
			
			
			//insertar en la tabla folio_rifa
				$fieldList = NULL;	
				// Field visitas
				$theValue = ($x_solicitud_id != "") ? intval($x_solicitud_id) : "0";
				$fieldList["`solicitud_id`"] = $theValue;
				// Field visitas
				// Field clave
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_nombre_completo) : $x_nombre_completo; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`nombre_completo`"] = $theValue;
				// Field visitas
				$theValue = ($x_folio_nuevo != "") ? intval($x_folio_nuevo) : "0";
				$fieldList["`folio`"] = $theValue;				
				
				// insert into database
				$sSql = "INSERT INTO `folio_rifa` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
				$x_result = phpmkr_query($sSql, $conn);
			
				
			#	echo $sSql."<br>";
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
			
			
				}
			$x_cont++;
			} // while 3
			
			
		}// fechas sorteo
				
				
				}
		} else if($x_tipo_credito == 2) {
			//FOLIOS PARA CREDITOS SOLIDARIOS
		#	echo "tipo de credito grupal<br>";
			// SELECCIONAMOS EL ID DEL GRUPO
			$sqlgid = "SELECT * FROM  creditosolidario  WHERE creditoSolidario_id IN(SELECT grupo_id FROM solicitud_grupo WHERE  solicitud_id = $x_solicitud_id)";
			$responsegid = phpmkr_query($sqlgid, $conn) or die("error al seleccionar los datos del gruopo".phpmkr_error()."sql;".$sqlgid);
			$rowGrupo = phpmkr_fetch_array($responsegid);
		#	echo  $sqlgid."<br>";
			$GLOBALS["x_creditoSolidario_id"] =  $rowGrupo["creditoSolidario_id"];
			$GLOBALS["x_nombre_grupo"] = $rowGrupo["nombre_grupo"];
			
			$x_cont_gid = 1;
			while($x_cont_gid <= 10){	
		#	echo "entro al while de folio para cada integrante <br> integrante ".$x_cont_gid."<br>";
				
				$x_cliente_id_grupo_actual = $rowGrupo["cliente_id_$x_cont_gid"];
		#		echo "cliente id".$x_cliente_id_grupo_actual."<br>";
			
			
			if(!empty($x_cliente_id_grupo_actual) && is_numeric($x_cliente_id_grupo_actual)){
			
		
			
			if($GLOBALS["x_folio_triple"] == 1){
			//pagos puntuales 3 folios	
		#	echo "entro a folio triple 1";	  
		if(($fecha_actual > $fecha_inicio_sorteo)&&($fecha_actual < $fecha_limite_sorteo)){ 
		//fecha valida para generar los folios de la rifa
				echo "entro a fecha del sorteo";
			//generamos la clave del folio
			$x_cont = 1;
			while($x_cont <= 3){
				echo "entro a while 3<p>";
				
					$sSqlFOL = "SELECT count(*) as folios FROM folio_rifa ";
					$rswrkF = phpmkr_query($sSqlFOL,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlFOL);
					$rowwrkF = phpmkr_fetch_array($rswrkF);
					$x_numero_folios = $rowwrkF["folios"];
					@phpmkr_free_result($rswrkF);

				
				if($x_numero_folios < 2000){
					echo "numero de folios menor de 2000";
			//alimentamos el generador de aleatorios
			//mt_srand((double)microtime()*1000000);			
			//mt_srand (microtime()*1000000);
			//generamos un número aleatorio
			//$numero_aleatorio = mt_rand(1,2000);
		// SE CAMBIA LA FORMA DE GENERAR EL FOLIO A HORA SERA CONSECUTIVO
		
		$sqlFolio = "SELECT MAX(folio) as ultimo_folio FROM folio_rifa";
		$responseFolio = phpmkr_query($sqlFolio,$conn)or die("error en folio max".phpmkr_error()."sql: ".$sqlFolio);
		$rowFolio = phpmkr_fetch_array($responseFolio);
		$x_ultimo_folio =  $rowFolio["ultimo_folio"];
		$x_folio_nuevo = intval($x_ultimo_folio)+ 1;
			/*
			$GLOBALS["rowNA"] = 1;
			while($GLOBALS["rowNA"] == 1){
				// el numero ya existe se genera otro
				mt_srand (microtime()*1000000);
				$numero_aleatorio = mt_rand(1,2000);
					
				echo "folio es :".$numero_aleatorio."<p>";
				$sqlNA ="SELECT count(*) as folio_repetido FROM folio_rifa WHERE folio = $numero_aleatorio";
				$responseNA = phpmkr_query($sqlNA, $conn) or die ("Error en select folio");
				$rowNA = phpmkr_fetch_array($responseNA);
				$x_folio_repetido = $rowNA["folio_repetido"];
				echo "folio repatido =".$x_folio_repetido."-<p>";
				if($x_folio_repetido == 0){
					$GLOBALS["rowNA"] = 2;					
					}		
			
			echo $numero_aleatorio. "<p>";
			
			
			}*/// fin while $rowNA= 1
			
			
			//insertar en la tabla folio_rifa
				$fieldList = NULL;	
				// Field solcitud_id
				$theValue = ($x_solicitud_id != "") ? intval($x_solicitud_id) : "0";
				$fieldList["`solicitud_id`"] = $theValue;
				
				// Field cliente_id
				$theValue = ($x_cliente_id_grupo_actual != "") ? intval($x_cliente_id_grupo_actual) : "0";
				$fieldList["`cliente_id`"] = $theValue;		
				
				
				// Field clave
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_nombre_completo) : $x_nombre_completo; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`nombre_completo`"] = $theValue;			
			
				// Field visitas
				$theValue = ($x_folio_nuevo != "") ? intval($x_folio_nuevo) : "0";
				$fieldList["`folio`"] = $theValue;				
				
				// insert into database
				$sSql = "INSERT INTO `folio_rifa` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
				$x_result = phpmkr_query($sSql, $conn);
				
							
				
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
			
				}//fin numero de folios
				
				
				$x_cont++;
			} // while 3
			
			
		}// fechas sorteo	
				
				
			
				
			
			}else if($GLOBALS["x_folio_triple"] == 0){
				// pagos no puntales  1 folio
			#	echo "folio_triple = 0";
				
				if(($fecha_actual > $fecha_inicio_sorteo)&&($fecha_actual < $fecha_limite_sorteo)){ 
		//fecha valida para generar los folios de la rifa
	#	echo("fecha dentro sorteo bien");
			//generamos la clave del folio
			$x_cont = 1;
			while($x_cont <= 1){
				
				$sSqlFOL = "SELECT count(*) as folios FROM folio_rifa ";
				$rswrkF = phpmkr_query($sSqlFOL,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlFOL);
				$rowwrkF = phpmkr_fetch_array($rswrkF);
				$x_numero_folios = $rowwrkF["folios"];
				@phpmkr_free_result($rswrkF);				
				
				if($x_numero_folios < 2000 ){
			
			
			//folios consecutivos
		$sqlFolio = "SELECT MAX(folio) as ultimo_folio FROM folio_rifa";
		$responseFolio = phpmkr_query($sqlFolio,$conn)or die("error en folio max".phpmkr_error()."sql: ".$sqlFolio);
		$rowFolio = phpmkr_fetch_array($responseFolio);
		$x_ultimo_folio =  $rowFolio["ultimo_folio"];
		$x_folio_nuevo = intval($x_ultimo_folio)+ 1;
			
			
			
			//insertar en la tabla folio_rifa
				$fieldList = NULL;	
				// Field visitas
				$theValue = ($x_solicitud_id != "") ? intval($x_solicitud_id) : "0";
				$fieldList["`solicitud_id`"] = $theValue;
				// Field cliente_id
				$theValue = ($x_cliente_id_grupo_actual != "") ? intval($x_cliente_id_grupo_actual) : "0";
				$fieldList["`cliente_id`"] = $theValue;		
				// Field clave
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_nombre_completo) : $x_nombre_completo; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`nombre_completo`"] = $theValue;
				// Field visitas
				$theValue = ($x_folio_nuevo != "") ? intval($x_folio_nuevo) : "0";
				$fieldList["`folio`"] = $theValue;				
				
				// insert into database
				$sSql = "INSERT INTO `folio_rifa` (";
				$sSql .= implode(",", array_keys($fieldList));
				$sSql .= ") VALUES (";
				$sSql .= implode(",", array_values($fieldList));
				$sSql .= ")";
				$x_result = phpmkr_query($sSql, $conn);
			
		#		echo  $sSql ."folio 1  <br>";
				
				if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
				}
			
			
				}
			$x_cont++;
			} // while 3
			
			
		} // fechas sorteo
				
				
				}				
			
			}// nuerico no vacio
			
			
			$x_cont_gid ++;
			}
				
			
				
				
				
				
				
				
			
			
			
			}
		
		
		
		
		
		
		// GENERA CASO CRM PARA RENOVACION (RENOVACION POR LIQUIDACION)
		// valida que no haya ya un caso abierto para este credito
		
		$currentdate = getdate(time());		
		$currdate =$currentdate["year"]."-".$currentdate["mon"]."-".$currentdate["mday"];
		$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];	
		//echo "currdate".$currdate;
		$currdate = ConvertDateToMysqlFormat($currdate);
		$sSqlWrk = "
		SELECT count(*) as caso_abierto
		FROM 
			crm_caso join crm_tarea
			on crm_tarea.crm_caso_id = crm_caso.crm_caso_id
		WHERE 
			crm_caso.crm_caso_tipo_id = 2
			AND crm_caso.crm_caso_status_id = 1
			AND crm_caso.credito_id = $x_credito_id
			AND crm_tarea.orden = 3
		";
		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
		$x_caso_abierto = $datawrk["caso_abierto"];		
		@phpmkr_free_result($rswrk);
		// si no existe un caso abierto, se abre uno.
		if($x_caso_abierto == 0){
			
			$sSqlWrk = "
			SELECT *
			FROM 
				crm_playlist
			WHERE 
				crm_playlist.crm_caso_tipo_id = 2
				AND crm_playlist.orden = 3
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_crm_playlist_id = $datawrk["crm_playlist_id"];
			$x_prioridad_id = $datawrk["prioridad_id"];	
			$x_asunto = $datawrk["asunto"];				
			//RENOVACION POR LIQUIDACION
			$x_asunto = $x_asunto." por liquidacion";
			$x_descripcion = $datawrk["descripcion"];		
			$x_tarea_tipo_id = $datawrk["tarea_fuente"];		
			$x_orden = $datawrk["orden"];	
			$x_dias_espera = $datawrk["dias_espera"];		
			$x_destino_id = $datawrk["destino_id"];					
			@phpmkr_free_result($rswrk);
		
/*		
			//Fecha Vencimiento
			$temptime = strtotime($currdate);	
			$temptime = DateAdd('w',$x_dias_espera,$temptime);
			$fecha_venc = strftime('%Y-%m-%d',$temptime);			
			$x_dia = strftime('%A',$temptime);
			if($x_dia == "SUNDAY"){
				$temptime = strtotime($fecha_venc);
				$temptime = DateAdd('w',1,$temptime);
				$fecha_venc = strftime('%Y-%m-%d',$temptime);
			}
			$temptime = strtotime($fecha_venc);
*/		
		
		
			$x_origen = 1;
			$x_bitacora = "Seguimiento - (".FormatDateTime($currdate,7)." - $currtime)";
		
			$x_bitacora .= "\n";
			$x_bitacora .= "$x_asunto - $x_descripcion ";	
	
	
			$sSqlWrk = "
			SELECT cliente.cliente_id
			FROM 
				cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id join solicitud on solicitud.solicitud_id = solicitud_cliente.solicitud_id join credito on credito.solicitud_id = solicitud.solicitud_id
			WHERE 
				credito.credito_id = $x_credito_id
			LIMIT 1
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_cliente_id = $datawrk["cliente_id"];
			@phpmkr_free_result($rswrk);
	
	
		
			$sSqlWrk = "
			SELECT usuario_id
			FROM 
				usuario
			WHERE 
				usuario.usuario_rol_id = $x_destino_id
			LIMIT 1
			";
			
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$datawrk = phpmkr_fetch_array($rswrk);
			$x_usuario_id = $datawrk["usuario_id"];
			@phpmkr_free_result($rswrk);
			
		
			$sSql = "INSERT INTO crm_caso values (0,2,1,1,$x_cliente_id,'".$currdate."',$x_origen,$x_usuario_id,'$x_bitacora','".$currdate."',NULL,$x_credito_id)";
		
			$x_result = phpmkr_query($sSql, $conn);
			$x_crm_caso_id = mysql_insert_id();	
	

	
			if($x_crm_caso_id > 0){
	
				$sSql = "INSERT INTO crm_tarea values (0,$x_crm_caso_id, $x_orden, $x_tarea_tipo_id, $x_prioridad_id,'".$currdate."', '$currtime','$currdate',NULL,NULL,NULL, 1, 1, 2, $x_usuario_id, NULL,NULL, '$x_asunto','$x_descripcion',1)";
			
				$x_result = phpmkr_query($sSql, $conn);
		
				$sSqlWrk = "
				SELECT 
					comentario_int
				FROM 
					credito_comment
				WHERE 
					credito_id = ".$x_credito_id."
				LIMIT 1
				";
				
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
				$datawrk = phpmkr_fetch_array($rswrk);
				$x_comment_ant = $datawrk["comentario_int"];
				@phpmkr_free_result($rswrk);
		
		
				if(empty($x_comment_ant)){
					$sSql = "insert into credito_comment values(0, $x_credito_id, '$x_bitacora', NULL)";
				}else{
					$x_bitacora = $x_comment_ant . "\n\n------------------------------\n" . $x_bitacora;
					$sSql = "UPDATE credito_comment set comentario_int = '$x_bitacora' where credito_id = $x_credito_id";
				}
		
				phpmkr_query($sSql, $conn);
			}
			
		
		}
		
		
					//insertamos en la tabla de liquidacion de credito
					$x_fecha_liquida = date("Y-m-d");
					$hora = getdate(time());
					$x_hora = $hora["hours"] . ":" . $hora["minutes"] . ":" . $hora["seconds"]; 
				
					
					//insertamos en la tabla de liquidacion
					$sqlLiquida = "INSERT INTO credito_liquidado (`credito_liquidado_id` ,`credito_id` ,`solicitud_id` ,";
					$sqlLiquida .= "`fecha` ,`hora` ,`status` ,`x` ,`xx` )";
					$sqlLiquida .= " VALUES (NULL , $x_credito_id, $x_solicitud_id, '$x_fecha_liquida', '$x_hora', '$x_credito_status_id', '1', '1');";
					$RSC = phpmkr_query($sqlLiquida, $conn) or die("error". phpmkr_error().$sqlLiquida) ;
		
		
		
			
		
	}// pendientes = 0
	
	
//	 si no hay vencimeniento vencidos cerramos las tareas del crm y el caso tambien.

	$sSqlWrk = "SELECT count(*) as vencidos FROM vencimiento where credito_id = $x_credito_id and vencimiento_status_id = 3";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$rowwrk = phpmkr_fetch_array($rswrk);
	$x_vencidos = $rowwrk["vencidos"];
	@phpmkr_free_result($rswrk);

	if($x_vencidos == 0){
	//Elimina tareas y casos del crm...
	
	$sSqlWrkCCC = "
	SELECT count(*) as caso_abierto,`crm_caso_id` 
	FROM 
		crm_caso
	WHERE 
		crm_caso.crm_caso_tipo_id = 3
		AND crm_caso.crm_caso_status_id = 1
		AND crm_caso.credito_id = $x_credito_id
	";
	
	$rswrkFFF = phpmkr_query($sSqlWrkCCC,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrkCCC);
	$datawrkFFF = phpmkr_fetch_array($rswrkFFF);
	$x_caso_abierto = $datawrkFFF["caso_abierto"];		
	$crm_caso_id_v = $datawrkFFF["crm_caso_id"];

	
	if($x_caso_abierto > 0 && !empty($crm_caso_id_v)){
		// SI HAY UN CASO ABIERTO, CERRAMOS TODAS LA TEREAS GENERADAS APARA ESE CASO		
		$SQLTAREAS  ="UPDATE crm_tarea SET crm_tarea_status_id = 4 WHERE  crm_caso_id  = $crm_caso_id_v ";
		$rswrkTAREAS = phpmkr_query($SQLTAREAS ,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $SQLTAREAS );
		
		$sSqlWrkcasos = "UPDATE crm_caso   SET crm_caso.crm_caso_status_id = 2 WHERE  
		crm_caso.crm_caso_tipo_id = 3
		AND crm_caso.crm_caso_status_id = 1
		AND crm_caso.credito_id = $x_credito_id";
		$rswrk = phpmkr_query($sSqlWrkcasos,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrkcasos);
		
		}
	
	
	
	}// vencimientos vencidos
			
	
	
	
	
	
	
	

	
		
	}//creditoid >0 // termian el while superior
		
		
		
		
		
		
		
		}//SELECT * FROM masiva_pago_2
		
			$sqlUpdateUltimoVencmeinto = "UPDATE vencimiento SET  vencimiento_status_id  = 1 WHERE  vencimiento_status_id  = 9 ";
			$rsUpdateUltimoVencmeinto = phpmkr_query($sqlUpdateUltimoVencmeinto, $conn) or die ("Erroe al actulizar el vencimeito ultimo para no ser tomado en ecuenta2 ". phpmkr_error()."sql :".$sqlUltimoVencimiento); 
	
	return true;
	die();
	}//FIN APLICA PAGOS
	
	
	
	
	
	
	
	function montoParaLiquidar($x_credito_id, $x_fecha_movimiento, $conn){
		$x_monto_para_liquidar = 0;
		// seleccionamos el ultimo dia del mes en que se hizo el movimiento
		$x_fecha_de_pago = ConvertDateToMysqlFormat($x_fecha_movimiento) ;
		$sqlLastDay =" SELECT LAST_DAY('$x_fecha_de_pago') AS fin_mes ";
		$rsLastDay = phpmkr_query($sqlLastDay, $conn) or die ("Error al seleccionar el ultimo dia de mes".phpmkr_error()."SQL:".$sqlLastDay);
		$rowLastDay = phpmkr_fetch_array($rsLastDay);
		$x_fecha_fin_mes = $rowLastDay["fin_mes"];
		// cambiamos las condiciones para que puedan ser aplicadas a todos los tipo de pagos, mesual,semanal,quincenal, catorcenal.	
		// tengo que copiar todas la condiones del vencimiento list
		
		
		$sqlVencidos = "SELECT vencimiento_num FROM vencimiento WHERE credito_id = $x_credito_id AND vencimiento_status_id = 3 ORDER BY vencimiento_num DESC LIMIT 0,1 ";
$rsVencidos = phpmkr_query($sqlVencidos,$conn) or die ("Error al seeleccionar el ultimo encimiento vencido".phpmkr_error()."sql:". $sqlVencidos);
$rowVencidos = phpmkr_fetch_array($rsVencidos);
$x_no_referencia_ven = $rowVencidos["vencimiento_num"];
if(!empty($x_no_referencia_ven)){
	// si existe uno o mas vencimientos vencido se toma el ultimo como numero de referencia
	// y buscamos el que le sigue que estar pendiente y se quedara cmo numero de referencia
	$sqlPendiente = "SELECT vencimiento_num FROM vencimiento WHERE credito_id = $x_credito_id AND vencimiento_status_id = 1 AND vencimiento_num > $x_no_referencia_ven  ORDER BY vencimiento_num ASC LIMIT 0,1 ";
	$rsPendiente = phpmkr_query($sqlPendiente,$conn) or die ("Error al seeleccionar el ultimo encimiento vencido".phpmkr_error()."sql:". $sqlPendiente);
	$rowPendiente = phpmkr_fetch_array($rsPendiente);
	$x_no_referencia_ven = $rowPendiente["vencimiento_num"];	
	}else{
		//si numeor de referencia esta vencido entonces se toma como numero de referenia el primer vencimiento pendiente
			$sqlPendiente = "SELECT vencimiento_num FROM vencimiento WHERE credito_id = $x_credito_id AND vencimiento_status_id = 1 ORDER BY vencimiento_num ASC LIMIT 0,1 ";
	$rsPendiente = phpmkr_query($sqlPendiente,$conn) or die ("Error al seeleccionar el ultimo encimiento vencido".phpmkr_error()."sql:". $sqlPendiente);
	$rowPendiente = phpmkr_fetch_array($rsPendiente);
	$x_no_referencia_ven = $rowPendiente["vencimiento_num"];			
		}
 
		#echo "numero de referencia ven". $x_no_referencia_ven."<br>";
		if(!empty($x_no_referencia_ven)) {
		#$sqlSaldo = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and  fecha_vencimiento <= '$x_fecha_fin_mes' and vencimiento_status_id in (1,3,6) ";
		$sqlSaldo = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and  vencimiento_num <= $x_no_referencia_ven and vencimiento_status_id in (1,3,6,7) order by  vencimiento_num asc";
		$rsSaldo = phpmkr_query($sqlSaldo, $conn) or die ("error en saldos". phpmkr_error()."sql:".$sqlSaldo);
		$x_total_liquida_con_interes = 0;
		$x_lista_con_interes = "";
		while ($rowSaldo = phpmkr_fetch_array($rsSaldo)){
			#echo "entra";
			#echo $rowSaldo["vencimiento_id"]."<br>";
			$x_total_liquida_con_interes = $x_total_liquida_con_interes + $rowSaldo["total_venc"];
			$x_lista_con_interes = $x_lista_con_interes.$rowSaldo["vencimiento_num"].", ";
		}
		$x_lista_con_interes = trim($x_lista_con_interes,", ");
		#echo "total liquida con intetres ".$x_total_liquida_con_interes."<br>";
		
	
		#$sqlSaldo2 = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and  fecha_vencimiento > '$x_fecha_fin_mes' and vencimiento_status_id in (1,3,6) ";
		$sqlSaldo2 = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and   vencimiento_num > $x_no_referencia_ven and vencimiento_num < 2000 and vencimiento_status_id in (1,3,6) order by  vencimiento_num asc";
		$rsSaldo2 = phpmkr_query($sqlSaldo2, $conn) or die ("error en saldos". phpmkr_error()."sql:".$sqlSaldo2);
		$x_total_liquida_sin_interes = 0;
		$x_total_interes_i = 0; 
		$x_total_iva_i = 0;
		$x_lista_sin_interes ="";
		while ($rowSaldo2 = phpmkr_fetch_array($rsSaldo2)){
			#echo "entra";
			#echo $rowSaldo["vencimiento_id"]."<br>";
			$x_total_liquida_sin_interes = $x_total_liquida_sin_interes + $rowSaldo2["total_venc"];
			$x_total_interes_i = $x_total_interes_i + $rowSaldo2["interes"];
			$x_total_iva_i = $x_total_iva_i + $rowSaldo2["iva"];
			$x_lista_sin_interes = $x_lista_sin_interes.$rowSaldo2["vencimiento_num"].", ";
		}	
		$x_lista_sin_interes = trim($x_lista_sin_interes,", ");	
	#	echo "total liquida sin intetres".$x_total_liquida_sin_interes."<br>";
		#$sqlSaldo3 = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and  fecha_vencimiento > '$x_fecha_fin_mes' and vencimiento_status_id in (7,8) ";
		$sqlSaldo3 = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and   vencimiento_num > $x_no_referencia_ven and vencimiento_num >= 2000 and vencimiento_status_id in (1,3,6) order by  vencimiento_num asc";
		$rsSaldo3 = phpmkr_query($sqlSaldo3, $conn) or die ("error en saldos". phpmkr_error()."sql:".$sqlSaldo3);
		$x_total_penalizaciones = 0;
		$x_lista_penalizaciones = "";
		while ($rowSaldo3 = phpmkr_fetch_array($rsSaldo3)){
			#echo "entra";
			#echo $rowSaldo["vencimiento_id"]."<br>";
			$x_total_penalizaciones = $x_total_penalizaciones + $rowSaldo3["total_venc"];	
			$x_lista_penalizaciones = $x_lista_penalizaciones.$rowSaldo3["vencimiento_num"].", ";
		}	
		$x_lista_penalizaciones = trim($x_lista_penalizaciones, ", ");
	#	echo "total penalizaciones".$x_lista_penalizaciones."<br>";
		#echo "total_liqui con interes".$x_total_liquida_con_interes."<br>";
		#echo "total_pendiente".$x_total_liquida_sin_interes."<br>";
		#echo "total interes pendiente". $x_total_interes_i."<br>";
		#echo "total_iva_pendiente".$x_total_iva_i."<br>";

		$x_total_liquida_sin_interes = $x_total_liquida_sin_interes -$x_total_interes_i -$x_total_iva_i;
	#	echo "total_pendiente".$x_total_liquida_sin_interes."<br>";
		$x_fecha_de_vigencia = $x_fecha_fin_mes;
		$x_saldo_a_liquidar = $x_total_liquida_con_interes +  $x_total_liquida_sin_interes+ $x_total_penalizaciones;
		
		$x_monto_para_liquidar = $x_saldo_a_liquidar;		
		}
#		echo "monto_para liquidar en la funcion =".$x_monto_para_liquidar."<br>";
		return $x_monto_para_liquidar;
		
		}
	function AddReport($conn){
		
		$pa = $GLOBALS["x_pagos_aplicados_correctos"] + $GLOBALS["x_pagos_aplicados_diferente"];
		$pna =$GLOBALS["x_pagos_NO_aplicados"];
		$pai =$GLOBALS["x_importe_aplicado"];
		$pnai= $GLOBALS["x_importe_NO_aplicado"];
		$pc =$GLOBALS["x_pagos_aplicados_correctos"];
		$pd =$GLOBALS["x_pagos_aplicados_diferente"];	
		$pp =$GLOBALS["x_pagos_pendientes"];
		$dpna =$GLOBALS["x_no_aplicados_reporte"];
		
		
		
		$SqlI = "INSERT INTO `reporte_pago_masivo` (
			`reporte_pago_masivo_id` ,
			`fecha` ,
			`file` ,
			`pagos_aplicados` ,
			`pagos_aplicados_importe` ,
			`pagos_no_palicados` ,
			`pagos_no_aplicados_importe` ,
			`pagos_correctos` ,
			`pagos_diferentes` ,
			`pagos_pendientes` ,
			`detalle_pago_no_aplicado` 
			)
			VALUES (
			NULL , '".ConvertDateToMysqlFormat($GLOBALS["x_fecha_pago"]) ."', '".$GLOBALS["x_nombre_archivo"]."', '$pa', '$pai', '$pna', '$pnai', '$pc', '$pd', '$pp', '$dpna');";
		

		$response = phpmkr_query($SqlI,$conn) or die("error al insert :".phpmkr_error()."sql:".$SqlI);
		
		if($response ){
			$x_res = true;
			}
			
			return $x_res;
		}

?>




