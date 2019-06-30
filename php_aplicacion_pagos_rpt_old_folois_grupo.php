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
ini_set("max_execution_time", "0")
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
	//echo "ENTRO A RECUALCULO *****";
		$sqlVenc1 = "SELECT * FROM vencimiento WHERE  credito_id = $x_credito_id AND (vencimiento_status_id = 3) ";
		$responseVen1 = phpmkr_query($sqlVenc1,$conn) or die ("Error al seleccionar el vencimeinto*". phpmkr_error()."sql : ".$sqlVenc1);
		//echo $sqlVenc1 ."<br>";
		//datos del  vencimiento
		$x_num_vencimientos1 = mysql_num_rows($responseVen1);
		//echo"numero de vencimeintos vencidos = ".$x_num_vencimientos1."<br>"; 
			$x_total_del_importe_vencimiento = 0;
			while ($rowVen1 = phpmkr_fetch_array($responseVen1)){
			//echo "entro al calculo de moratorios:-...<br>";
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
			$response = phpmkr_query($sqlVenNum, $conn) or die("error en numero de vencimiento".phpmkr_error()."sql:".$sqlVenNum);
			$rownpagos = phpmkr_fetch_array($response);  
			$x_numero_de_pagos_d =  $rownpagos["numero_de_pagos_d"];
			
			
			
			// si el numero de pagos es mayor a uno significa que ya se cobraron los moratoios o parte de ellos y ya no se deben de volver a recalcular los moratorios...
			// solo entra al ciclo de moratorios si solo existe un  pago con esta fecha.
			if($x_numero_de_pagos_d < 2){
			//RECALCULAMOS LOS MORATORIOS; ESTO SE HACE PORQUE AVECES LOS REPORTES LLEGAN TARDE Y YA SE GENERARON MORATORIOS, AUNQUE EL CLIENTE YA HAYA REALIZADA EL PAGO
			// SE RECALCULAN A ALA FECHA DE MOVIMIENTO
			if(empty($x_s_iva)){
				$x_s_iva = 0;
			}		
			if(is_null($x_s_interes_moratorio)){
				$x_s_interes_moratorio = 0;
			}
			 if($x_s_interes_moratorio > 0){
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
				 
				 //solo se hace un recalculo de moratorios si el campo moratorios es mayor de 0
			$x_dias_vencidos = datediff('d', $x_s_fecha_vencimiento, $x_fecha_movimiento, false);	
			
			//echo "dias venciados = ".$x_dias_vencidos."<br>";
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
				
				$x_tot_venc = $x_s_importe + $x_s_interes + $x_importe_mora + $x_s_iva + $x_iva_mor;		
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
	
	
	//echo "Entro a Aplica pagos <br>";
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
		
		
		//CALCULA SOBRANTE DE PAGO
		 $x_sobrante = 0;
		 $x_total_venc_por_pagar = 0;
		 
			
		if(($x_credito_id > 0) && ($x_numero_creditos_activos == 1)){
		$x_total_a_pagar_vencimientos = calculaTotalVencimientos($x_credito_id,$conn);
		//echo "deposito del cliente por  :".$x_importe."<br>";
		//echo "total apagar de los vencimientos = ".$x_total_a_pagar_vencimientos."<br>";
		if($x_total_a_pagar_vencimientos < $x_importe){
			
			// la persona pago de mas...este pago no se debe de aplicar...
			//verificamos los moratorios........
				
			$x_total_vencimientos_moratorios = calculaMoratorios($x_credito_id, $conn, $x_fecha_movimiento);
			//echo "total a pagar de los ven con moratorios recalculados".$x_total_vencimientos_moratorios."<br>";
			 if($x_total_vencimientos_moratorios < $x_importe){
				// no se aplica el pago
				 $x_sobrante = 1;
				
				}
			
			
			
			}
		
		
		}
		
		
		
		if( ($x_credito_id > 0) && ($x_numero_creditos_activos == 1) && ($x_sobrante == 0)){
			//echo "<br>credito id.". $x_credito_id." mayor de 0<br><br>";
			
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
			$x_s_importe = $rowVen["importe"];
			$x_s_interes = $rowVen["interes"];
			$x_s_interes_moratorio = $rowVen["interes_moratorio"];
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
			 //echo "fecha vencimeinto".$x_s_fecha_vencimiento."<br>";
			 //echo "fecha movimeinto ".$x_fecha_movimiento."<br>";
			 
			 
			 if($x_s_fecha_vencimiento > $x_fecha_movimiento ){
				//echo "fecha vencimeinto mayor a fecha del movimeinto";
			$x_dias_vencidos_m = datediff('d', $x_fecha_movimiento,$x_s_fecha_vencimiento, false);
			//echo "dias de diferencia = ".$x_dias_vencidos_m."<br>";
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
			
				if($x_dias_vencidos > $x_dias_gracia){
				//echo "dias vencidos mayor  dias de gracia<br>";
				//echo "dias vencidos :".$x_dias_vencidos."<br>";
			
				$x_importe_mora = $x_c_tasa_moratoria * $x_dias_vencidos;
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
					//echo "query ;".$sSql;
					
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
						//echo "<BR>entramos  caso 1 <br> el ".$x_importe."es igual a ".$x_s_importe_total."<br>";
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
					//echo "INSERTAMOS EN RECIBO_ENCIMEINTO:". mysql_insert_id()."-";
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
									//echo "aplico remanente....".$x_s_vencimiento_num.".... fecha ".$x_s_fecha_vencimiento." .....";
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
						//echo $sSql;
					
					
					
						// la fecha en que se pago el vencimeinto es la fecha que se utiliza en el campo fecha genera remanente...						
					//	$x_d_fecha_genera_remanente = ConvertDateToMysqlFormat($x_fecha_movimiento);
																						
					
						if($x_s_vencimiento_num > 1000){
								$sSql = "insert into vencimiento values(0,$x_s_credito_id, $x_s_vencimiento_num,$x_s_vencimiento_status_id, '$x_s_fecha_vencimiento', $x_d_importe, $x_d_interes, $x_d_interes_moratorio, $x_d_iva, $x_d_iva_mor, $x_d_importe_total, '$x_d_fecha_genera_remanente')";
							}else{																					
						$sSql = "insert into vencimiento values(0,$x_s_credito_id, $x_s_vencimiento_num,$x_s_vencimiento_status_id, '$x_s_fecha_vencimiento', $x_d_importe, $x_d_interes, $x_d_interes_moratorio, $x_d_iva, $x_d_iva_mor, $x_d_importe_total, 'NULL')";
							}
						$x_result = phpmkr_query($sSql, $conn);
						//echo $sSql;
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
					//echo "<br>salimos caso 2<br>---------------------------";
				}//cierra caso dos
				
				
				
				// =========== CASO 3  ================//
				// EL IMPORTE DEL PAGO ES MAYOR AL IMPORTE DEL VENCIMIENTO
				// SE MATA EL VENCIMEINTO ACTUAL Y SE REVIS EL VENCIMIENTO SIGUIENTE PARA PAGARLO O
				// ABONAR UNA CANTIDAD, SI NO SE PAGA COMPLETO, SE MATA EL VENCIMENTO POR LA CANTIDAD
				// Y SE GENERA UNO NUEVO, CON LA CANTIDAD FALTANTE 
				
				
				if(($x_importe > 0) && (doubleval($x_importe) > doubleval($x_s_importe_total))){
					//echo"<BR> caso tres <br> el importe del pago ".$x_importe. "es mayor que el vencimeinto" .$x_s_importe_total."<br>";
				$sSql = "update vencimiento set vencimiento_status_id = 2
				where vencimiento_id =  ".$x_s_vencimiento_id;
				$responseS = phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
				
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
			$x_recibo = 2;
			// salimos del if numero de vencimientos por pagar mayor a 0
			}else{
				//aun hay saldo del deposito, pero ya no hay vencimietos para liquidar
				$GLOBALS["x_pago_sobrante"] =  $GLOBALS["x_pago_sobrante"] + $x_importe;
				//echo "credito_id :".$x_credito_id."";
				$x_importe = 0;
				echo "<BR> ************************************************abajo si lo encontro *************************************************</BR>";
				}
				
		
				
				
				
				
				
				
				
				
				
				
				
			}// while importe > 0
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
					$sSqlUr = "UPDATE recibo SET referencia_pago_2  = \"0\" WHERE  recibo_id = $x_recibo_id ";
					phpmkr_query($sSqlUr,$conn) or die("Error al actulizar referencia de pago dos".phpmkr_error()."sql :".$sSqlUr);
					}
				}// if x_recibo_id > 0
			
			}else{
				
				
				$sqlStausc = "SELECT credito_status_id FROM credito WHERE `tarjeta_num` =  $x_ref_pago ";
				$reponseCreditost = phpmkr_query($sqlStausc, $conn)or die ("error el seleccionar el estaus del credito;".phpmkr_error()."sql: ".$sqlStausc);
				$rowad = phpmkr_fetch_array($reponseCreditost);
				$status =  $rowad["credito_status_id"];
				
				
				if ($x_sobrante == 1){
					
					$GLOBALS["x_importe_NO_aplicado"] =	$GLOBALS["x_importe_NO_aplicado"] + $x_importe;
						$GLOBALS["x_no_aplicados_reporte"] .= "<tr>
											  	  <td><font color=\"#FF0000\" style=\"font-size:11px;\">$x_ref_pago</font>&nbsp; &nbsp; &nbsp;</td><td>&nbsp;</td>
											  <td><font color=\"#FF0000\" style=\"font-size:11px;\" >El importe pagado es mayor que el importe de los vencimientos pendientes.</font></td>
											  </tr>";
						$GLOBALS["x_pagos_NO_aplicados"]++;
					
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
		$sSqlWrk = "SELECT count(*) as pendientes FROM vencimiento where credito_id = $x_credito_id and vencimiento_status_id in (1,3,6)";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$rowwrk = phpmkr_fetch_array($rswrk);
		$x_pendientes = $rowwrk["pendientes"];
		@phpmkr_free_result($rswrk);
		
		$sSqlWrk = "SELECT solicitud_id  FROM credito where credito_id = $x_credito_id";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$rowwrk = phpmkr_fetch_array($rswrk);
		$x_solicitud_id = $rowwrk["solicitud_id"];
		@phpmkr_free_result($rswrk);

	if($x_pendientes == 0){
		//echo "<br> pendientes igual que 0 <br>";
	//FINIQUITA CREDITO
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
		
		if($GLOBALS["x_folio_triple"] == 1){
			//pagos puntuales 3 folios	
			//echo "entro a folio triple 1";	  
		if(($fecha_actual > $fecha_inicio_sorteo)&&($fecha_actual < $fecha_limite_sorteo)){ 
		//fecha valida para generar los folios de la rifa
				//echo "entro a fecha del sorteo";
			//generamos la clave del folio
			$x_cont = 1;
			while($x_cont <= 3){
				//echo "entro a while 3<p>";
				
					$sSqlFOL = "SELECT count(*) as folios FROM folio_rifa ";
					$rswrkF = phpmkr_query($sSqlFOL,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlFOL);
					$rowwrkF = phpmkr_fetch_array($rswrkF);
					$x_numero_folios = $rowwrkF["folios"];
					@phpmkr_free_result($rswrkF);

				
				if($x_numero_folios < 2000){
					//echo "numero de folios menor de 2000";
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
				//echo "solcitud :".$x_solicitud_id."<br>";
				// Field clave
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_nombre_completo) : $x_nombre_completo; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`nombre_completo`"] = $theValue;			
				
				// Field visitas
				$theValue = ($x_folio_nuevo != "") ? intval($x_folio_nuevo) : "0";
				$fieldList["`folio`"] = $theValue;				
				//echo "$x_folio_nuevo".$x_folio_nuevo."<br>";
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
			//	echo "folio_triple = 0";
				
				if(($fecha_actual > $fecha_inicio_sorteo)&&($fecha_actual < $fecha_limite_sorteo)){ 
		//fecha valida para generar los folios de la rifa
		//echo("fecha dentro sorteo bien");
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
				//echo "solcitud :".$x_solicitud_id."<br>";
				// Field visitas
				// Field clave
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($x_nombre_completo) : $x_nombre_completo; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`nombre_completo`"] = $theValue;
				// Field visitas
				$theValue = ($x_folio_nuevo != "") ? intval($x_folio_nuevo) : "0";
				$fieldList["`folio`"] = $theValue;				
				//echo "folio :".$x_folio_nuevo."<br>";
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
			
			
				}
			$x_cont++;
			} // while 3
			
			
		}// fechas sorteo
				
				
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
		
		
		
		
		
		
			
		
	}// pendientes = 0
	
// COMENTARIOS DE CARTERA VENCIDA
	$sSqlWrk = "SELECT count(*) as vencidos FROM vencimiento where credito_id = $x_credito_id and vencimiento_status_id = 3";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$rowwrk = phpmkr_fetch_array($rswrk);
	$x_vencidos = $rowwrk["vencidos"];
	@phpmkr_free_result($rswrk);

	if($x_vencidos == 0){
	//Elimina comentarios
	/*
		$sSqlWrk = "delete from credito_comment where credito_id = $x_credito_id";
		phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	*/
	
	
	}
			
	
	
	
	
	
	
	

	
		
	}//creditoid >0
		
		}//SELECT * FROM masiva_pago_2
	
	return true;
	die();
	}//FIN APLICA PAGOS
	
	
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




