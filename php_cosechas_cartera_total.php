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



	//sucursal
	$sSql = "select * from sucursal order by sucursal_id";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSql);
	while ($row = phpmkr_fetch_array($rs)) {
		$x_sucursal_id = $row["sucursal_id"];
		
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
				$sSqlwrk2p = "select * from promotor where sucursal_id = $x_sucursal_id and promotor_id in (select distinct(promotor_id) as promotor_id from solicitud join credito on credito.solicitud_id = solicitud.solicitud_id where credito.credito_status_id not in (2,5)) order by nombre_completo ";
				
				$sSqlwrk2p = "select * from promotor where sucursal_id = $x_sucursal_id order by nombre_completo ";
				
				
				
				$rswrk2p = phpmkr_query($sSqlwrk2p,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlwrk2);
				while ($rowwrk2p = phpmkr_fetch_array($rswrk2p)) {
					$x_promotor_id = $rowwrk2p["promotor_id"];
					$x_nombre_completo = $rowwrk2p["nombre_completo"];			

	
	
					// Cartera Total
					$sSqlwrk3 = "SELECT sum(importe) as capital from credito where credito_status_id not in (2,5) AND credito.fecha_otrogamiento <= '$x_fecha_actual' 
					
	AND credito_id in (select credito_id from credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id where promotor.sucursal_id = $x_sucursal_id and promotor.promotor_id = $x_promotor_id ) 				
	
	AND credito_id in (select credito_id from fondeo_colocacion where fondeo_credito_id = $x_fondeo_credito_id)
	
	AND credito_tipo_id = $x_credito_tipo_id 
					";
					
					
					$sSqlwrk3 = "SELECT sum(importe) as capital from credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id join fondeo_colocacion on fondeo_colocacion.credito_id = credito.credito_id
					
					where credito_status_id not in (2,5) AND credito.fecha_otrogamiento <= '$x_fecha_actual' and promotor.sucursal_id = $x_sucursal_id and promotor.promotor_id = $x_promotor_id and fondeo_credito_id = $x_fondeo_credito_id
	
	AND credito.credito_tipo_id = $x_credito_tipo_id 
					";
					



					
					
					$rswrk3 = phpmkr_query($sSqlwrk3,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSqlwrk3);
					$rowwrk3 = @phpmkr_fetch_array($rswrk3);
					$x_tot_otorgado = 0;
					$x_tot_otorgado = $rowwrk3["capital"];
					if(empty($x_tot_otorgado)){
						$x_tot_otorgado = 0;	
					}
					phpmkr_free_result($rswrk3);				
				
				
					$sSqlwrk3 = "SELECT sum(vencimiento.importe) as pagado from vencimiento join recibo_vencimiento on recibo_vencimiento.vencimiento_id = vencimiento.vencimiento_id join recibo on recibo.recibo_id = recibo_vencimiento.recibo_id where (vencimiento.vencimiento_status_id in(2,5)) AND (vencimiento.credito_id not in (select credito_id from credito where credito_status_id in (2,5))) AND (recibo.recibo_status_id = 1) AND (recibo.fecha_pago <= '".$x_fecha_actual."')
	
		
	AND vencimiento.credito_id in (select credito_id from credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id where promotor.sucursal_id = $x_sucursal_id and promotor.promotor_id = $x_promotor_id) 				
	
	AND vencimiento.credito_id in (select credito_id from fondeo_colocacion where fondeo_credito_id = $x_fondeo_credito_id)
	
	AND vencimiento.credito_id in (select credito_id from credito where credito_tipo_id = $x_credito_tipo_id)
					";
					
					


					$sSqlwrk3 = "SELECT sum(vencimiento.importe) as pagado from vencimiento join recibo_vencimiento on recibo_vencimiento.vencimiento_id = vencimiento.vencimiento_id join recibo on recibo.recibo_id = recibo_vencimiento.recibo_id join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id join fondeo_colocacion on fondeo_colocacion.credito_id = credito.credito_id 
					
					where (vencimiento.vencimiento_status_id in(2,5)) AND (credito.credito_status_id not in (2,5)) AND (recibo.recibo_status_id = 1) AND (recibo.fecha_pago <= '".$x_fecha_actual."') and (promotor.sucursal_id = $x_sucursal_id and promotor.promotor_id = $x_promotor_id) and (fondeo_colocacion.fondeo_credito_id = $x_fondeo_credito_id) and (credito.credito_tipo_id = $x_credito_tipo_id)
	
		
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
	
	
/*	
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

*/

	

					$sSql = "insert into cosecha_cartera_total values(0, $curryear, $currmonth, $x_sucursal_id, $x_fondeo_credito_id, $x_credito_tipo_id, $x_promotor_id, '".ConvertDateToMysqlFormat($currdate)."', $x_tot_otorgado, $x_tot_pagado, $x_tot_cartera_total) ";
					phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);

				
				}
				@phpmkr_free_result($rswrk2p);
				
			}
			@phpmkr_free_result($rswrk2);
				
		}
		@phpmkr_free_result($rswrk);
		
	}
	@phpmkr_free_result($rs);




phpmkr_db_close($conn);

echo "Fin de Proceso";

?>
