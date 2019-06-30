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
$x_recibo_id = Null; 
$ox_recibo_id = Null;
$x_vencimiento_id = Null; 
$ox_vencimiento_id = Null;
$x_medio_pago_id = Null; 
$ox_medio_pago_id = Null;
$x_referencia_pago = Null; 
$ox_referencia_pago = Null;
$x_fecha_registro = Null; 
$ox_fecha_registro = Null;
$x_importe = Null; 
$ox_importe = Null;


$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	

?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString


$x_credito_id = @$_GET["credito_id"];
if (empty($x_credito_id)) {
	$x_credito_id = @$_POST["x_credito_id"];
}
$x_ref_loc = @$_GET["x_refloc"];
if (empty($x_ref_loc)) {
	$x_ref_loc = @$_POST["x_refloc"];
	if (empty($x_ref_loc)) {

		/*echo "<div align='center' class='phpmaker'>
		<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\" color=\"#CC3300\">
		No se selecciono un recibo para su aplicacion.<br><br>";
		echo "<a href=\"php_vencimientolist.php?credito_id=$x_credito_id\">Cerrar Ventana</a>
		</font>
		</div>";
		exit();*/
	}

}

// Get action
$sAction = @$_POST["a_add"];

echo "action ".$sAction."<br>";
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "L"; // Display blank record

}else{

	// Get fields from form
	$x_recibo_id = @$_POST["x_recibo_id"];
	$x_vencimiento_id = @$_POST["x_vencimiento_id"];
	$x_banco_id = @$_POST["x_banco_id"];		
	$x_medio_pago_id = @$_POST["x_medio_pago_id"];
	$x_referencia_pago = @$_POST["x_referencia_pago"];
	$x_referencia_pago2 = @$_POST["x_referencia_pago2"];	
	$x_fecha_registro = @$_POST["x_fecha_registro"];	
	$x_fecha_pago = @$_POST["x_fecha_pago"];		
	$x_importe_venc = @$_POST["x_importe_venc"];
	$x_interes_venc = @$_POST["x_interes_venc"];
	$x_iva_venc = @$_POST["x_iva_venc"];	
	$x_iva_mor_venc = @$_POST["x_iva_mor_venc"];		
	$x_interes_moratorio = @$_POST["x_interes_moratorio"];	
	$x_importe = @$_POST["x_importe"];		
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$x_ref_loc = Null;			
			$x_ref_loc2 = Null;						
			$x_vencimiento_id = Null;
			$x_vencimiento_num = Null;
			$x_vencimiento_status_id = Null;
			$x_fecha_vencimiento = Null;
			$x_importe_venc = Null;
			$x_importe = Null;			
			$x_interes_venc = Null;
			$x_iva_venc = Null;			
			$x_iva_mor_venc = Null;						
			$x_interes_moratorio = Null;
			$x_folio = Null;
			$x_nombre_completo = Null;

/*
			echo "
			<script type=\"text/javascript\">
			<!--
			window.opener.document.principal.submit();
			//-->
			</script>";
*/			


		//	header("Location:  php_vencimientolist.php?credito_id=$x_credito_id");
/*			
			echo "<div align='center' class='phpmaker'>
			<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\" color=\"#CC3300\">
			El pago ha sido aplicado.<br><br>";
			echo "<a href=\"php_vencimientolist.php?credito_id=$x_credito_id\">Cerrar Ventana</a>
			</font>
			</div>";
*/			
			//exit();
			
			
		}
		break;
	case "L": // Add
//		$x_ref_loc = @$_POST["x_ref_loc"];	
		if(strlen($x_ref_loc) == 0){
			echo "<div align='center' class='phpmaker'>
			<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\" color=\"#CC3300\">
			La referencia no es valida verifiquela.<br><br>";
			echo "<a href=\"php_vencimientolist.php?credito_id=$x_credito_id\">Cerrar Ventana</a>
			</font>
			</div>";
		//	exit();
		
		}else{
			if(strpos($x_ref_loc,"/") == 0){
				echo "<div align='center' class='phpmaker'>
				<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\" color=\"#CC3300\">
				La referencia no es valida verifiquela.<br><br>";
				echo "<a href=\"php_vencimientolist.php?credito_id=$x_credito_id\">Cerrar Ventana</a>
				</font>
				</div>";
				//exit();
			}else{
				$x_venc_id = substr($x_ref_loc,0,strpos($x_ref_loc,"/"));
				$x_venc_id = intval($x_venc_id);
				
				//tipo de credito
				$sSql = "select credito.credito_tipo_id from credito join vencimiento
				on vencimiento.credito_id = credito.credito_id where vencimiento.vencimiento_id = $x_venc_id";				
				$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
				$rowwrk = phpmkr_fetch_array($rswrk);
				$x_credito_tipo_id = $rowwrk["credito_tipo_id"];				
				phpmkr_free_result($rswrk2);
				
				
				if($x_credito_tipo_id == 1){
				$sSql = "select vencimiento.*, solicitud.folio, solicitud.promotor_id, cliente.nombre_completo, credito.credito_id, credito.credito_num, credito.cliente_num 
				from vencimiento join credito 
				on credito.credito_id = vencimiento.credito_id join solicitud
				on solicitud.solicitud_id = credito.solicitud_id join solicitud_cliente
				on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente 
				on cliente.cliente_id = solicitud_cliente.cliente_id
				where vencimiento.vencimiento_id = ".$x_venc_id;
				
					
				$x_today = date("Y-m-d");
				$x_f_p = explode("-",$x_today);
				$x_dia = $x_f_p[2];
				$x_mes = $x_f_p[1];
				$x_anio = $x_f_p[0];
				#echo "dia ".$x_dia."mes ".$x_mes."año ".$x_anio."<br>";
				
				// seleccionamos el ultimo dia del mes que corresponde a la fecha actual
				$x_ultimo_dia_mes_f = strftime("%d", mktime(0, 0, 0, $x_mes+1, 0, $x_anio));
				#echo "yultimo dia de mes". $x_ultimo_dia_mes_f."<br>";
				
				$x_fecha_inicio_mes = $x_anio."-".$x_mes."-01";
				$x_fecha_fin_mes = $x_anio."-".$x_mes."-".$x_ultimo_dia_mes_f; 
				#echo "incio mes".$x_fecha_inicio_mes."<br>";
				#echo "fin mes".$x_fecha_fin_mes."<br>";
				
				
				
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
				
				
				$sqlSaldo = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and  vencimiento_num <= $x_no_referencia_ven and vencimiento_status_id in (1,3,6,7) ";
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
				$sqlSaldo2 = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and   vencimiento_num > $x_no_referencia_ven and vencimiento_num < 2000 and vencimiento_status_id in (1,3,6) order by  vencimiento_num asc ";
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
				$sqlSaldo3 = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and   vencimiento_num > $x_no_referencia_ven and vencimiento_num >= 2000 and vencimiento_status_id in (1,3,6) order by  vencimiento_num asc ";
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
				#echo "total_liqui con interes".$x_total_liquida_con_interes."<br>";
				#echo "total_pendiente".$x_total_liquida_sin_interes."<br>";
				#echo "total interes pendiente". $x_total_interes_i."<br>";
				#echo "total_iva_pendiente".$x_total_iva_i."<br>";
				
				$x_total_liquida_sin_interes = $x_total_liquida_sin_interes -$x_total_interes_i -$x_total_iva_i;
				#echo "total_pendiente".$x_total_liquida_sin_interes."<br>";
				$x_fecha_de_vigencia = $x_fecha_fin_mes;
				$x_saldo_a_liquidar = $x_total_liquida_con_interes +  $x_total_liquida_sin_interes+ $x_total_penalizaciones;
				?><p>
				
				<?php 
				# seleccionamos la forma de pago 
				$sqlFormaPago = "SELECT forma_pago_id FROM credito WHERE credito_id = $x_credito_id ";
				$rsFormaPago = phpmkr_query($sqlFormaPago, $conn) or die ("Error al seleccionar".phpmkr_error()."sql:".$sqlFormaPago);
				$rowFormaPago = phpmkr_fetch_array($rsFormaPago);
				$x_forma_pago_id = $rowFormaPago["forma_pago_id"];


				
				
				}else{
					$sSql = "select vencimiento.*, solicitud.folio, solicitud.promotor_id, solicitud.grupo_nombre as nombre_completo, credito.credito_id, credito.credito_num, credito.cliente_num 
					from vencimiento join credito 
					on credito.credito_id = vencimiento.credito_id join solicitud
					on solicitud.solicitud_id = credito.solicitud_id 
					where vencimiento.vencimiento_id = ".$x_venc_id;
					
					$x_today = date("Y-m-d");
					$x_f_p = explode("-",$x_today);
					$x_dia = $x_f_p[2];
					$x_mes = $x_f_p[1];
					$x_anio = $x_f_p[0];
					#echo "dia ".$x_dia."mes ".$x_mes."año ".$x_anio."<br>";
					
					// seleccionamos el ultimo dia del mes que corresponde a la fecha actual
					$x_ultimo_dia_mes_f = strftime("%d", mktime(0, 0, 0, $x_mes+1, 0, $x_anio));
					#echo "yultimo dia de mes". $x_ultimo_dia_mes_f."<br>";
					
					$x_fecha_inicio_mes = $x_anio."-".$x_mes."-01";
					$x_fecha_fin_mes = $x_anio."-".$x_mes."-".$x_ultimo_dia_mes_f; 
					#echo "incio mes".$x_fecha_inicio_mes."<br>";
					#echo "fin mes".$x_fecha_fin_mes."<br>";
					
					
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
					
					$sqlSaldo = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and  vencimiento_num <= $x_no_referencia_ven and vencimiento_status_id in (1,3,6,7) ";
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
				$sqlSaldo2 = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and   vencimiento_num > $x_no_referencia_ven and vencimiento_num < 2000 and vencimiento_status_id in (1,3,6) order by  vencimiento_num asc ";
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
				#echo "total_liqui con interes".$x_total_liquida_con_interes."<br>";
				#echo "total_pendiente".$x_total_liquida_sin_interes."<br>";
				#echo "total interes pendiente". $x_total_interes_i."<br>";
				#echo "total_iva_pendiente".$x_total_iva_i."<br>";
				
				$x_total_liquida_sin_interes = $x_total_liquida_sin_interes -$x_total_interes_i -$x_total_iva_i;
				#echo "total_pendiente".$x_total_liquida_sin_interes."<br>";
				$x_fecha_de_vigencia = $x_fecha_fin_mes;
				$x_saldo_a_liquidar = $x_total_liquida_con_interes +  $x_total_liquida_sin_interes+ $x_total_penalizaciones;
				?><p>
				
				<?php 
				# seleccionamos la forma de pago 
				$sqlFormaPago = "SELECT forma_pago_id FROM credito WHERE credito_id = $x_credito_id ";
				$rsFormaPago = phpmkr_query($sqlFormaPago, $conn) or die ("Error al seleccionar".phpmkr_error()."sql:".$sqlFormaPago);
				$rowFormaPago = phpmkr_fetch_array($rsFormaPago);
				$x_forma_pago_id = $rowFormaPago["forma_pago_id"];
					
					
					
					
					
					
					?><p>
					
					<?php 
					# seleccionamos la forma de pago 
					$sqlFormaPago = "SELECT forma_pago_id FROM credito WHERE credito_id = $x_credito_id ";
					$rsFormaPago = phpmkr_query($sqlFormaPago, $conn) or die ("Error al seleccionar".phpmkr_error()."sql:".$sqlFormaPago);
					$rowFormaPago = phpmkr_fetch_array($rsFormaPago);
					$x_forma_pago_id = $rowFormaPago["forma_pago_id"];


					
					
					
				}
				$rswrk = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
				if(phpmkr_num_rows($rswrk) > 0){
					$rowwrk = phpmkr_fetch_array($rswrk);
					$x_val_promo = true;
					if(($rowwrk["vencimiento_status_id"] != 1) && ($rowwrk["vencimiento_status_id"] != 3) && ($rowwrk["vencimiento_status_id"] != 6) && ($rowwrk["vencimiento_status_id"] != 8)) {
						/*echo "<div align='center' class='phpmaker'>
						<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\" color=\"#CC3300\">
						El vencimiento ya esta pagado o cancelado, verifique.<br><br>";
						echo "<a href=\"php_vencimientolist.php?credito_id=$x_credito_id\">Cerrar Ventana</a>
						</font>
						</div>";
						exit();*/
																	
					}

					$x_vencimiento_id = $rowwrk["vencimiento_id"];
					$x_vencimiento_num = $rowwrk["vencimiento_num"];				
					$x_credito_id = $rowwrk["credito_id"];				
					$x_credito_num = $rowwrk["credito_num"];									
					$x_cliente_num = $rowwrk["cliente_num"];														
					$x_vencimiento_status_id = $rowwrk["vencimiento_status_id"];								
					$x_fecha_vencimiento = $rowwrk["fecha_vencimiento"];
					$x_importe_venc = $rowwrk["importe"];
					$x_interes_venc = $rowwrk["interes"];									
					$x_iva_venc = $rowwrk["iva"];														
					$x_iva_mor_venc = $rowwrk["iva_mor"];																			
					if(empty($x_iva_venc)){
						$x_iva_venc = 0;
					}
					if(empty($x_iva_mor_venc)){
						$x_iva_mor_venc = 0;
					}
					
					$x_interes_moratorio = $rowwrk["interes_moratorio"];																
					$x_folio = $rowwrk["folio"];															
					$x_nombre_completo = $rowwrk["nombre_completo"];		


					//VALIDA QUE 	EL PAGO NO SEA SALETADO
					
				$sSql = "select count(*) as salteado from vencimiento where credito_id = $x_credito_id and vencimiento_num < $x_vencimiento_num and vencimiento_status_id in (3)";
				$rswrk2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
					$rowwrk2 = phpmkr_fetch_array($rswrk2);
					$x_salteado = $rowwrk2["salteado"];
					phpmkr_free_result($rswrk2);
					if($x_salteado > 0){
						/*echo "<div align='center' class='phpmaker'>
						<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\" color=\"#CC3300\">
						El credito tiene pagos anteriores vencidos, no se pueden aplicar pagos salteado.<br><br>";
						echo "<a href=\"php_vencimientolist.php?credito_id=$x_credito_id\">Cerrar Ventana</a>
						</font>
						</div>";
						exit();*/
					}
				}else{
					echo "<div align='center' class='phpmaker'>
					<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\" color=\"#CC3300\">
					La referencia no fue localizada, verifiquela.<br><br>";
					echo "<a href=\"php_vencimientolist.php?credito_id=$x_credito_id\">Cerrar Ventana</a>
					</font>
					</div>";
					//exit();
				}
				phpmkr_free_result($rswrk);
			}
		}
		break;		
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>e - SF >  FINANCIERA CRECE - PAGOS </title>
<link href="php_project_esf.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script type="text/javascript" language="JavaScript1.2" src="menu/stlib.js"></script>
<SCRIPT TYPE="text/javascript">
<!--
window.focus();
//-->
</SCRIPT>
</head>
<body>

<script type="text/javascript" src="utilerias/datefunc.js"></script>
<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<script type="text/javascript">
<!--

function actimp(){
EW_this = document.pagoadd;

	EW_this.x_importe.value = Number(EW_this.x_importe_venc.value) + Number(EW_this.x_interes_venc.value) + Number(EW_this.x_interes_moratorio.value) + Number(EW_this.x_iva_venc.value) + Number(EW_this.x_iva_mor_venc.value);

}

function EW_checkMyForm() {
EW_this = document.pagoadd;
validada = true;


//Solo importes iguales o menores

x_importe_para_liquidar = document.getElementById("x_importe_para_liquidar").value;




if(Number(EW_this.x_importe.value) > Number(x_importe_para_liquidar)){
	if (!EW_onError(EW_this, EW_this.x_importe, "TEXT", "El importe de pago no puede ser mayor al importe del saldo para liquidar. " + x_importe_para_liquidar))
		validada = false;
}
if(Number(EW_this.x_importe.value) < Number(x_importe_para_liquidar)){
	if (!EW_onError(EW_this, EW_this.x_importe, "TEXT", "El importe de pago no puede ser menor al importe del saldo para liquidar. " + x_importe_para_liquidar))
		validada = false;
}

if (validada == true && EW_this.x_banco_id && !EW_hasValue(EW_this.x_banco_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_banco_id, "SELECT", "EL banco y cuenta son requeridos."))
		validada = false;
}

if (validada && EW_this.x_medio_pago_id && !EW_hasValue(EW_this.x_medio_pago_id, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_medio_pago_id, "SELECT", "El medio de pago es requerido."))
		validada = false;
}
if (validada && EW_this.x_referencia_pago && !EW_hasValue(EW_this.x_referencia_pago, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_referencia_pago, "TEXT", "La referencia de pago 1 es requerida."))
		validada = false;
}
if (validada && EW_this.x_referencia_pago2 && !EW_hasValue(EW_this.x_referencia_pago2, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_referencia_pago2, "TEXT", "La referencia de pago 2 es requerida."))
		validada = false;
}
if (validada && EW_this.x_fecha_pago && !EW_hasValue(EW_this.x_fecha_pago, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_pago, "TEXT", "La fecha de pago es requerida."))
		validada = false;
}
if (EW_this.x_fecha_pago && !EW_checkeurodate(EW_this.x_fecha_pago.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_pago, "TEXT", "La fecha de pago es incorrecta."))
		validada = false;
}

if(validada == true){
	if (!compareDates(EW_this.x_fecha_pago.value, EW_this.x_hoy.value)) {	
		if (!EW_onError(EW_this, EW_this.x_fecha_pago, "TEXT", "La fecha de pago no puede ser mayor a la fecha actual."))
			validada = false; 
	}
}

if(validada == true){
	if (!compareDates(EW_this.x_fecha_vencimiento.value, EW_this.x_hoy.value)) {	
		if (!EW_onError(EW_this, EW_this.x_fecha_pago, "TEXT", "Para realizar pagos anticipados vaya a la opcion correspondiente del menu de Pagos."))
			validada = false; 
	}
}

if(validada == true){
	EW_this.a_add.value = "A";
	EW_this.submit();
}

}

//-->
</script>
<!--script type="text/javascript" src="popcalendar.js"></script-->
<!-- New popup calendar -->
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/skins/aqua/theme.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-es.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<p align="center"><span class="phpmaker"><strong><h3><center>LIQUIDAR CR&Eacute;DITO</center></h3></strong><br>
<br>
<a href="php_vencimientolist.php?credito_id=<?php echo "$x_credito_id"; ?>">Cerrar ventana</a>
<br />
<br />
    
</span>
  <?php
if (@$_SESSION["ewmsg"] <> "") {
?>
</p>
  <p><span class="ewmsg"><?php echo $_SESSION["ewmsg"] ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>

<form name="pagoadd" id="pagoadd" action="" method="post" >
<input type="hidden" name="a_add" value="A">
<input type="hidden" name="x_refloc" value="<?php echo $x_ref_loc; ?>">
<input type="hidden" name="x_hoy" value="<?php echo $currdate; ?>">
<input type="hidden" name="x_vencimiento_id" value="<?php echo $x_vencimiento_id; ?>">
<input type="hidden" name="x_fecha_vencimiento" value="<?php echo $x_fecha_vencimiento; ?>">
<input type="hidden" name="x_fecha_registro" value="<?php echo FormatDateTime(@$currdate,7); ?>">		
<input type="hidden" name="x_credito_id" value="<?php echo $x_credito_id; ?>"  />
<input type="hidden" name="x_importe_para_liquidar" id="x_importe_para_liquidar" value="<?php echo $x_saldo_a_liquidar;?>" />

<table width="500" align="center" class="ewTable_small">
	<tr>
	  <td height="50" class="ewTableHeaderThin"><div align="left">Cr&eacute;dito No.</div></td>
	  <td class="ewTableAltRow">
	    <div align="left"><?php echo $x_credito_num; ?>        </div></td>
    </tr>
	<tr>
	  <td class="ewTableHeaderThin"><div align="left">Cliente No.</div></td>
	  <td class="ewTableAltRow"><div align="left"><?php echo $x_cliente_num; ?>	  </div></td>
	  </tr>
	<tr>
		<td width="199" class="ewTableHeaderThin"><div align="left"><span>Vencimiento</span></div></td>
	  <td width="589" class="ewTableAltRow"><div align="left"><span>
	    <?php echo $x_vencimiento_num; ?>	  		
	    </span></div></td>
	</tr>
	<tr>
	  <td class="ewTableHeaderThin"><div align="left">Fecha Venc. </div></td>
	  <td class="ewTableAltRow"><div align="left">
      <?php echo FormatDateTime(@$x_fecha_vencimiento,7); ?></div></td>
	  </tr>
	<tr>
	  <td class="ewTableHeaderThin">Banco</td>
	  <td class="ewTableAltRow"><?php if (!(!is_null($x_banco_id)) || ($x_banco_id == "")) { $x_banco_id = 0;} // Set default value ?>
        <?php
$x_medio_pago_idList = "<select name=\"x_banco_id\">";
$x_medio_pago_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT `banco_id`, `nombre`, `cuenta` FROM `banco`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_medio_pago_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["banco_id"] == @$x_banco_id) {
			$x_medio_pago_idList .= "' selected";
		}
		$x_medio_pago_idList .= ">" . $datawrk["nombre"] . " - " . $datawrk["cuenta"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_medio_pago_idList .= "</select>";
echo $x_medio_pago_idList;
?></td>
    </tr>
	<tr>
		<td class="ewTableHeaderThin"><div align="left"><span>Medio de pago</span></div></td>
		<td class="ewTableAltRow"><div align="left"><span>
  <?php if (!(!is_null($x_medio_pago_id)) || ($x_medio_pago_id == "")) { $x_medio_pago_id = 0;} // Set default value ?>
  <?php
$x_medio_pago_idList = "<select name=\"x_medio_pago_id\">";
$x_medio_pago_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT `medio_pago_id`, `descripcion` FROM `medio_pago`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_medio_pago_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["medio_pago_id"] == @$x_medio_pago_id) {
			$x_medio_pago_idList .= "' selected";
		}
		$x_medio_pago_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_medio_pago_idList .= "</select>";
echo $x_medio_pago_idList;
?>
        </span></div></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><div align="left"><span>Referencia de pago</span> 1</div></td>
		<td class="ewTableAltRow"><div align="left"><span>
          <input type="text" name="x_referencia_pago" id="x_referencia_pago" size="30" maxlength="50" value="<?php echo htmlspecialchars(@$x_ref_loc) ?>">
        </span></div></td>
	</tr>
	<tr>
	  <td class="ewTableHeaderThin"><div align="left"><span>Referencia de pago</span> 2</div>	    </td>
	  <td class="ewTableAltRow"><div align="left"><span>
        <input type="text" name="x_referencia_pago2" id="x_referencia_pago2" size="30" maxlength="50" value="<?php echo htmlspecialchars(@$x_referencia_pago2) ?>" />
      </span></div></td>
    </tr>
	<tr>
		<td class="ewTableHeaderThin"><div align="left"><span>Fecha de pago </span></div></td>
		<td class="ewTableAltRow"><div align="left"><span>
  <input type="text" name="x_fecha_pago" id="x_fecha_pago" value="<?php echo FormatDateTime(@$x_fecha_vencimiento,7); ?>">
  &nbsp;<img src="images/ew_calendar.gif" id="cx_fecha_pago" alt="Calendario" style="cursor:pointer;cursor:hand;">
  <script type="text/javascript">
Calendar.setup(
{
inputField : "x_fecha_pago", // ID of the input field
ifFormat : "%d/%m/%Y", // the date format
button : "cx_fecha_pago" // ID of the button
}
);
</script>
        </span></div></td>
	</tr>
	<tr>
		<td class="ewTableHeaderThin"><div align="left">Total a pagar </div></td>
		<td class="ewTableAltRow">
	      <div align="left">
	        <input style="text-align:right" name="x_importe" type="text" id="x_importe" value="<?php echo FormatNumber(@$x_saldo_a_liquidar ,2,0,0,0); ?>"   />		
          </div></td>
	</tr>
</table>
<p>
<?php if(($x_vencimiento_status_id == 2) || ($x_vencimiento_status_id == 4)){
	$sSqlWrk = "SELECT `descripcion` FROM `vencimiento_status`";
	$sTmp = $x_vencimiento_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `vencimiento_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}else{
		$sTmp = "INDEFINIDO";	
	}
	@phpmkr_free_result($rswrk);
	//echo "<span class=\"phpmaker\"><font color=\"#FF0000\"><b>El vencimiento se encuentra: ".$sTmp."</b></font></span>";
	?>
    <p align="right">
	<input type="button"  name="Action" value="Liquidar Credito" onClick="EW_checkMyForm()">
    </p>
	<?php
}else{
?>
<p align="center">
<input type="button"  name="Action" value="Liquidar Credito" onClick="EW_checkMyForm()">
</p>

<?php } ?>
</form>

<?php if (@$sExport == "") { ?>
		<p>&nbsp;</p>
	</td>
</tr>
</table>
<?php } ?>
</body>
</html>

<?php
phpmkr_db_close($conn);
?>
<?php

//-------------------------------------------------------------------------------
// Function LoadData
// - Load Data based on Key Value sKey
// - Variables setup: field variables

function LoadData($conn)
{
	global $x_recibo_id;
	$sSql = "SELECT * FROM `recibo`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_recibo_id) : $x_recibo_id;
	$sWhere .= "(`recibo_id` = " . addslashes($sTmp) . ")";
	$sSql .= " WHERE " . $sWhere;
	if ($sGroupBy <> "") {
		$sSql .= " GROUP BY " . $sGroupBy;
	}
	if ($sHaving <> "") {
		$sSql .= " HAVING " . $sHaving;
	}
	if ($sOrderBy <> "") {
		$sSql .= " ORDER BY " . $sOrderBy;
	}
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	if (phpmkr_num_rows($rs) == 0) {
		$bLoadData = false;
	}else{
		$bLoadData = true;
		$row = phpmkr_fetch_array($rs);

		// Get the field contents
		$GLOBALS["x_recibo_id"] = $row["recibo_id"];
		$GLOBALS["x_vencimiento_id"] = $row["vencimiento_id"];
		$GLOBALS["x_medio_pago_id"] = $row["medio_pago_id"];
		$GLOBALS["x_referencia_pago"] = $row["referencia_pago"];
		$GLOBALS["x_referencia_pago2"] = $row["referencia_pago_2"];		
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
		$GLOBALS["x_importe"] = $row["importe"];
	}
	phpmkr_free_result($rs);
	return $bLoadData;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function AddData
// - Add Data
// - Variables used: field variables

function AddData($conn)
{

	global $x_recibo_id;
	$sSql = "SELECT * FROM `recibo`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	

	// Check for duplicate key
	$bCheckKey = true;
	$sWhereChk = $sWhere;
	if ((@$x_recibo_id == "") || (is_null($x_recibo_id))) {
		$bCheckKey = false;
	} else {
		if ($sWhereChk <> "") { $sWhereChk .= " AND "; }
		$sTmp =  (get_magic_quotes_gpc()) ? stripslashes($x_recibo_id) : $x_recibo_id;			
		$sWhereChk .= "(`recibo_id` = " . addslashes($sTmp) . ")";
	}
	if ($bCheckKey) {
		$sSqlChk = $sSql . " WHERE " . $sWhereChk;
		$rsChk = phpmkr_query($sSqlChk, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSqlChk);
		if (phpmkr_num_rows($rsChk) > 0) {
			$_SESSION["ewmsg"] = "Duplicate value for primary key";
			phpmkr_free_result($rsChk);
			return false;
		}
		phpmkr_free_result($rsChk);
	}


	// Field recibo_status_id
	$fieldList["`recibo_status_id`"] = 1;


	// Field vencimiento_id
	/*
	$theValue = ($GLOBALS["x_vencimiento_id"] != "") ? intval($GLOBALS["x_vencimiento_id"]) : "NULL";
	$fieldList["`vencimiento_id`"] = $theValue;
	*/

	$theValue = ($GLOBALS["x_banco_id"] != "") ? intval($GLOBALS["x_banco_id"]) : "0";
	$fieldList["`banco_id`"] = $theValue;
	
	// Field medio_pago_id
	$theValue = ($GLOBALS["x_medio_pago_id"] != "") ? intval($GLOBALS["x_medio_pago_id"]) : "NULL";
	$fieldList["`medio_pago_id`"] = $theValue;

	// Field referencia_pago
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_pago"]) : $GLOBALS["x_referencia_pago"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`referencia_pago`"] = $theValue;

	// Field referencia_pago
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_pago2"]) : $GLOBALS["x_referencia_pago2"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`referencia_pago_2`"] = $theValue;

	// Field fecha_registro
	$theValue = ($GLOBALS["x_fecha_registro"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_registro"]) . "'" : "Null";
	$fieldList["`fecha_registro`"] = $theValue;

	// Field fecha_registro
	$theValue = ($GLOBALS["x_fecha_pago"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_pago"]) . "'" : "Null";
	$fieldList["`fecha_pago`"] = $theValue;

	// Field importe
	$theValue = ($GLOBALS["x_importe"] != "") ? " '" . doubleval($GLOBALS["x_importe"]) . "'" : "NULL";
	$fieldList["`importe`"] = $theValue;

	// insert into database
	$sSql = "INSERT INTO `recibo` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	$x_result = phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$x_recibo_id = mysql_insert_id();



#$sSql = "insert into recibo_vencimiento values(0,".$GLOBALS["x_vencimiento_id"].",$x_recibo_id)";
#$x_result = phpmkr_query($sSql, $conn);
if(!$x_result){
	echo phpmkr_error() . '<br>SQL: ' . $sSql;
	phpmkr_query('rollback;', $conn);	 
	exit();
}

// ya se inserto en recibo ahora debemos insertar en recibo vencimeinto por cada vencimiento que se haya pago y debemos actualizar los vencimeintos en  los que no se pago el interes.

$x_credito_id = $GLOBALS["x_credito_id"];

$sqlSol_id = "select solicitud_id FROM credito WHERE credito_id = $x_credito_id ";
$rsSol_id = phpmkr_query($sqlSol_id,$conn) or die ("error al seleccionar la solcitud id". phpmkr_error()."sql:". $sqlSol_id);
$rowSol_id = phpmkr_fetch_array($rsSol_id);

$x_c_solicitud_id = $rowSol_id["solicitud_id"];

$x_fecha_de_pago = ConvertDateToMysqlFormat($GLOBALS["x_fecha_pago"]) ;
			$sqlLastDay =" SELECT LAST_DAY('$x_fecha_de_pago') AS fin_mes ";
			$rsLastDay = phpmkr_query($sqlLastDay, $conn) or die ("Error al seleccionar el ultimo dia de mes".phpmkr_error()."SQL:".$sqlLastDay);
			$rowLastDay = phpmkr_fetch_array($rsLastDay);
			$x_fecha_fin_mes = $rowLastDay["fin_mes"];
			
			//CAMBIAMOS LA VALIDACION PARA QUE SEA POR STATUS DE VENCIMEINTO
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
			
			
			
			
			
			
			
			
			
			$x_lista_vencimientos_interes= "";
			#1
		#$sqlSaldoLA = "SELECT vencimiento_id FROM vencimiento WHERE credito_id = $x_credito_id and fecha_vencimiento <= '$x_fecha_fin_mes' and vencimiento_status_id in (1,3,6)";
		
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
			#echo "lista ven int".$x_lista_vencimientos_interes."<br>";
			
			#4
			$SqlLiquidaCredito = "UPDATE credito SET credito_status_id = 3 WHERE credito_id = $x_credito_id ";
			$rsLiquidaCredito = phpmkr_query($SqlLiquidaCredito, $conn) or die ("Error al actualizar el credito". phpmkr_error()."sql:". $SqlLiquidaCredito);
			 
			$SqlLiquidaCredito = "UPDATE solicitud SET solicitud_status_id = 7 WHERE solicitud_id = $x_c_solicitud_id ";
			$rsLiquidaCredito = phpmkr_query($SqlLiquidaCredito, $conn) or die ("Error al actualizar el credito". phpmkr_error()."sql:". $SqlLiquidaCredito);
			
			#5 
			$SqlLiquidaCredito = "UPDATE garantia_liquida SET status = 3 WHERE credito_id = $x_credito_id ";
			$rsLiquidaCredito = phpmkr_query($SqlLiquidaCredito, $conn) or die ("Error al actualizar el credito". phpmkr_error()."sql:". $SqlLiquidaCredito);
			
			#7
			$x_vencimientos_pagados_li = explode(", ",$x_lista_vencimientos_interes);
			foreach($x_vencimientos_pagados_li as $ven_id){
					// por cada valor que esta en la lista se inserta en recibo_vencimiento;
					 $insertRecibo = "insert into recibo_vencimiento values(0,$ven_id,$x_recibo_id)";
					 $x_result = phpmkr_query($insertRecibo, $conn);
				#	echo $sSql;
					}
				






	return true;
}
?>

