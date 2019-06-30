<?php set_time_limit(0); ?>
<?php session_start(); ?>
<?php ob_start(); ?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php include ("utilerias/datefunc.php") ?>
<?php
$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];
$currmonth = $currentdate["mon"];	
$curryear = $currentdate["year"];

$x_fecha_actual = ConvertDateToMysqlFormat($currdate);

$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);


//mes
$x_mes_ini = 9;
$x_year_ini = 2011;

while(($x_mes_ini < ($currmonth + 1)) && ($x_year_ini < ($curryear + 1))){

	//sucursal
	$sSql = "select * from sucursal order by sucursal_id";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
	while ($row = phpmkr_fetch_array($rs)) {
		$x_sucursal_id = $row["sucursal_id"];
		
		$sSql = "truncate table cosecha_tmp";
		phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
		
		//Fondo
		$sSqlwrk = "select * from fondeo_credito join fondeo_empresa on fondeo_empresa.fondeo_empresa_id = fondeo_credito.fondeo_empresa_id order by fondeo_credito.fondeo_empresa_id, fondeo_credito.fondeo_credito_id";
		$rswrk = phpmkr_query($sSqlwrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		while ($rowwrk = phpmkr_fetch_array($rswrk)) {
			$x_fondeo_credito_id = $rowwrk["fondeo_credito_id"];
			$x_empresa_nombre = $rowwrk["nombre"];			
			
			//tipo de credito
			$sSqlwrk2 = "select * from credito_tipo order by descripcion";
			$rswrk2 = phpmkr_query($sSqlwrk2,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlwrk2);
			while ($rowwrk2 = phpmkr_fetch_array($rswrk2)) {
				$x_credito_tipo_id = $rowwrk2["credito_tipo_id"];
				$x_descripcion = $rowwrk2["descripcion"];			


				//promotor
				$sSqlwrk2p = "select * from promotor where sucursal_id = $x_sucursal_id order by nombre_completo ";
				$rswrk2p = phpmkr_query($sSqlwrk2p,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlwrk2);
				while ($rowwrk2p = phpmkr_fetch_array($rswrk2p)) {
					$x_promotor_id = $rowwrk2p["promotor_id"];
					$x_nombre_completo = $rowwrk2p["nombre_completo"];			

	
	
					// Cartera Total
					$sSqlwrk3 = "SELECT sum(importe) as capital from vencimiento where credito_id in (select credito_id from credito where credito_status_id not in (2,5) AND month(fecha_otrogamiento) = $x_mes_ini AND year(fecha_otrogamiento) = $x_year_ini) 
					
	AND credito_id in (select credito_id from credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id where promotor.sucursal_id = $x_sucursal_id and promotor.promotor_id = $x_promotor_id ) 				
	
	AND credito_id in (select credito_id from fondeo_colocacion where fondeo_credito_id = $x_fondeo_credito_id)
	
	AND vencimiento.credito_id in (select credito_id from credito where credito_tipo_id = $x_credito_tipo_id) 
					";
					
					$rswrk3 = phpmkr_query($sSqlwrk3,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSqlwrk3);
					$rowwrk3 = @phpmkr_fetch_array($rswrk3);
					$x_tot_otorgado = 0;
					$x_tot_otorgado = $rowwrk3["capital"];
					if(empty($x_tot_otorgado)){
						$x_tot_otorgado = 0;	
					}
					phpmkr_free_result($rswrk3);				
				
				
					$sSqlwrk3 = "SELECT sum(vencimiento.importe) as pagado from vencimiento where (vencimiento.vencimiento_status_id in(2,5)) AND (vencimiento.credito_id not in (select credito_id from credito where credito_status_id in (2,5)))
	
	AND (vencimiento.credito_id in (select credito_id from credito where month(fecha_otrogamiento) = $x_mes_ini AND year(fecha_otrogamiento) = $x_year_ini))
	
	AND vencimiento.credito_id in (select credito_id from credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id where promotor.sucursal_id = $x_sucursal_id and promotor.promotor_id = $x_promotor_id) 				
	
	AND vencimiento.credito_id in (select credito_id from fondeo_colocacion where fondeo_credito_id = $x_fondeo_credito_id)
	
	AND vencimiento.credito_id in (select credito_id from credito where credito_tipo_id = $x_credito_tipo_id)
					";
					
					$rswrk3 = phpmkr_query($sSqlwrk3,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSqlwrk3);
					$rowwrk3 = @phpmkr_fetch_array($rswrk3);
					$x_tot_pagado = 0;
					$x_tot_pagado = $rowwrk3["pagado"];
					if(empty($x_tot_pagado)){
						$x_tot_pagado = 0;	
					}
					phpmkr_free_result($rswrk3);				
				
	
					//cartera total = otorgado - pagado
					$x_tot_cartera_total = 0;	
					$x_tot_cartera_total = $x_tot_otorgado - $x_tot_pagado;	
	
	
	
					// Cartera Vencida
					$sSqlwrk3 = "SELECT sum(importe) as capital from vencimiento where credito_id in (select credito_id from credito where credito_status_id not in (2,5) AND month(fecha_otrogamiento) = $x_mes_ini AND year(fecha_otrogamiento) = $x_year_ini) AND vencimiento.vencimiento_status_id = 3 
					
	AND credito_id in (select credito_id from credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id where promotor.sucursal_id = $x_sucursal_id and promotor.promotor_id = $x_promotor_id) 				
	
	AND credito_id in (select credito_id from fondeo_colocacion where fondeo_credito_id = $x_fondeo_credito_id)
	
	AND vencimiento.credito_id in (select credito_id from credito where credito_tipo_id = $x_credito_tipo_id) 
					";
					
					$rswrk3 = phpmkr_query($sSqlwrk3,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSqlwrk3);
					$rowwrk3 = @phpmkr_fetch_array($rswrk3);
					$x_tot_cartera_vencida = 0;
					$x_tot_cartera_vencida = $rowwrk3["capital"];
					if(empty($x_tot_cartera_vencida)){
						$x_tot_cartera_vencida = 0;	
					}
					phpmkr_free_result($rswrk3);	
					
	
	
					// Cartera Vigente
					$x_tot_cartera_vigente = 0;
					$x_tot_cartera_vigente = ($x_tot_otorgado - ($x_tot_pagado + $x_tot_cartera_vencida));				
	
	
				
					//Creditos 
					$sSqlwrk3 = "SELECT credito_id, tasa, forma_pago_id, num_pagos from credito where credito_status_id not in (2,5) and month(fecha_otrogamiento) = $x_mes_ini and year(fecha_otrogamiento) = $x_year_ini
	
	AND credito_id in (select credito_id from credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id where promotor.sucursal_id = $x_sucursal_id and promotor.promotor_id = $x_promotor_id)				
	
	AND credito_id in (select credito_id from fondeo_colocacion where fondeo_credito_id = $x_fondeo_credito_id)
	
	AND credito.credito_tipo_id = $x_credito_tipo_id
					 ";
					
	//				echo $sSqlwrk3."<br>";
					
					$rswrk3 = phpmkr_query($sSqlwrk3,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSqlwrk3);
					
	//				echo "nl ".phpmkr_num_rows($rswrk3)."<br><br>";
					$nRecCount = 0;
					$x_hombres = 0;
					$x_mujeres = 0;
					$x_creditos = 0;
					
					
					//Numero de creditos				
					$x_numero_creditos = 0;
					$x_numero_creditos = phpmkr_num_rows($rswrk3);
					
					//Monto promedio
					$x_monto_promedio = 0;
					if($x_tot_otorgado > 0 && $x_numero_creditos > 0){
						$x_monto_promedio = $x_tot_otorgado / $x_numero_creditos;
					}
					
					while($rowwrk3 = @phpmkr_fetch_array($rswrk3)){
						$x_credito_id = $rowwrk3["credito_id"];
						$x_tasa = $rowwrk3["tasa"];				
						$x_forma_pago_id = $rowwrk3["forma_pago_id"];		
						$x_num_pagos = $rowwrk3["num_pagos"];				
					
						$sSqlwrk4 = "SELECT sum(importe) as capital from vencimiento where credito_id = $x_credito_id";
						$rswrk4 = phpmkr_query($sSqlwrk4,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSqlwrk4);
						$rowwrk4 = @phpmkr_fetch_array($rswrk4);
						$x_tot_otorgado2 = $rowwrk4["capital"];
						phpmkr_free_result($rswrk4);				
				
				
						$sSqlwrk4 = "SELECT sum(vencimiento.importe) as pagado FROM vencimiento 
					WHERE vencimiento.credito_id = $x_credito_id AND vencimiento.vencimiento_status_id in(2,5)";
						$rswrk4 = phpmkr_query($sSqlwrk4,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSqlwrk4);
						$rowwrk4 = @phpmkr_fetch_array($rswrk4);
						$x_tot_pagado2 = $rowwrk4["pagado"];
						phpmkr_free_result($rswrk4);				
				
				
						$sSqlwrk4 = "SELECT fecha_vencimiento FROM vencimiento 
					WHERE vencimiento.credito_id = $x_credito_id order by fecha_vencimiento desc limit 1";
						$rswrk4 = phpmkr_query($sSqlwrk4,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSqlwrk4);
						$rowwrk4 = @phpmkr_fetch_array($rswrk4);
						$x_fecha_vencimiento = $rowwrk4["fecha_vencimiento"];
						phpmkr_free_result($rswrk4);				
				
						$x_dias_neg = 0;
						$x_dias = 0;
						$x_dias = datediff('d', $x_fecha_actual, $x_fecha_vencimiento);
						$x_dias_neg = $x_dias;
						if($x_dias < 0){
							$x_dias = 0;	
						}
				
						$x_saldo = 0;
						$x_saldo = $x_tot_otorgado2 - $x_tot_pagado2;
						
						
						$x_tasa_anual = 0;
						$x_fac_pp = 0;
						switch ($x_forma_pago_id)
						{
							case "1": // semanal
								$x_tasa_anual = $x_tasa * 52;
								break;	
							case "2": // catorcenal
								$x_tasa_anual = $x_tasa * 26;
								break;	
							case "3": // mensual
								$x_tasa_anual = $x_tasa * 24;
								break;	
							case "4": // quincenal
								$x_tasa_anual = $x_tasa * 13;
								break;	
						}
						
						$x_tpcv = 0;
						$x_tpcv = (($x_saldo / $x_tot_cartera_vigente) * ($x_tasa_anual / 100));
						
						$x_pp = 0;
						$x_ppneg = 0;
						$x_pp = (($x_saldo / $x_tot_cartera_vigente) * $x_dias);		
						$x_ppneg = (($x_saldo / $x_tot_cartera_vigente) * $x_dias_neg);				
				
						$sSql = "insert into cosecha_tmp values(0,'$x_fecha_actual', $x_tot_cartera_vigente, $x_credito_id, $x_tpcv, $x_pp, $x_ppneg)";
						phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
						
						
						//sexo y edad
						$sSqlwrk4 = "SELECT sexo, fecha_nac FROM cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id join solicitud on solicitud.solicitud_id = solicitud_cliente.solicitud_id join credito on credito.solicitud_id = solicitud.solicitud_id where credito.credito_id = $x_credito_id";
						$rswrk4 = phpmkr_query($sSqlwrk4,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSqlwrk4);
						$rowwrk4 = @phpmkr_fetch_array($rswrk4);
						$x_sexo = $rowwrk4["sexo"];
						$x_fecha_nac = $rowwrk4["fecha_nac"];		
						phpmkr_free_result($rswrk4);				
						
						$x_edad = datediff('yyyy', $x_fecha_actual, $x_fecha_nac);	
						if($x_edad < 18 || $x_edad > 90){
							$x_edad = 0;	
						}
						$x_edad_promedio = $x_edad_promedio + $x_edad;
						
						if($x_sexo == 1){
							$x_hombres++;	
						}else{
							$x_mujeres++;			
						}
						
						if($x_edad > 0){
							$x_creditos++;
						}
						
					}
					phpmkr_free_result($rs3);	
					
					if(empty($x_hombres)){
						$x_hombres = 0;	
					}
					if(empty($x_mujeres)){
						$x_mujeres = 0;	
					}
					
					if($x_edad_promedio > 0 && $x_creditos > 0){
						$x_edad_promedio = $x_edad_promedio / $x_creditos;
					}else{
						$x_edad_promedio = 0;
					}
				
					$sSql = "insert into cosecha SELECT 0, $x_year_ini, $x_mes_ini, $x_sucursal_id, $x_fondeo_credito_id, $x_credito_tipo_id, '".ConvertDateToMysqlFormat($currdate)."', $x_tot_cartera_total, cv, $x_tot_cartera_vencida, $x_tot_otorgado, $x_numero_creditos, $x_monto_promedio, sum(tpcv) as tpcv_total, sum(pp) as pp_total, sum(ppneg) as ppn_total, $x_edad_promedio as edad_promedio, $x_hombres, $x_mujeres, $x_promotor_id from cosecha_tmp group by fecha_ctmp  ";
					phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
					
					$sSql = "delete from cosecha_tmp where 0=0"; 
					phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);

				
				}
				@phpmkr_free_result($rswrk2p);
				
			}
			@phpmkr_free_result($rswrk2);
				
		}
		@phpmkr_free_result($rswrk);
		
	}
	@phpmkr_free_result($rs);

	$x_mes_ini++;
	if($x_mes_ini == 13){
		$x_mes_ini = 1;
		$x_year_ini++;
	}
}

phpmkr_db_close($conn);

echo "Fin de Proceso";

?>
