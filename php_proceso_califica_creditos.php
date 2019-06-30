<?php set_time_limit(0); ?>
<?php session_start(); ?>
<?php ob_start(); ?> 
<?php
// Initialize common variables
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php include ("utilerias/datefunc.php") ?>


<?php

// IP compartido
$msg = "COMPARTIDO=>" .$_SERVER['HTTP_CLIENT_IP'] ." PROXY ==>".$_SERVER['HTTP_X_FORWARDED_FOR']."===>"." ACCESO".$_SERVER['REMOTE_ADDR'];

$today = date("Y-m-d");
$from = "test@financieracrea.com";
    $to = "zuoran_17@hotmail.com";
    $subject = "Checking PHP mail";
    $message = "PHP mail works just fine ".$today."<BR>".$msg." PROCESO_CALIFICA_CREDITOS.PHP";
    $headers = "From:" . $from;
    mail($to,$subject,$message, $headers);
#Este proceso califica la manera en que fue pagado el credito
#tomando los sigueites criterios de tipo de cliente

# a) los creditos que se pagarn en la fecha que correspondi o antes
# b)  su mayor atraso es menor a 11 dias
# c)  su mayor atraso es  mayor a 10 dias y menor a 31
# d) su mayor atraso es mayor a 30 dias y/o genero comision de cobranza

# los creditos que estan en (4,5) cbrnza externa o incobrable x defaul son tipo D


$x_today =  date("Y-m-d");
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

//seleccionmos todo los credito en cobranza o incobrables

//$x_today  = ("Y-m-d", mktime(0, 0, 0,date("m"),date("d")-2, date("Y")));

$sqlD = " SELECT DATE_SUB(\"$x_today\", INTERVAL 2 DAY) as dia";
$RS = phpmkr_query($sqlD,$conn) or die("error al seleccionar fecha".phpmkr_error().$sqlD);
$rowD = phpmkr_fetch_array($RS);
$x_today = $rowD["dia"];


echo "echo".$x_today;
//die();
//seleccionmos todo los credito en cobranza o incobrables
$sqlActivo = "SELECT credito_id FROM credito WHERE credito_status_id = 1";
echo $sqlActivo."<br><br>";
$rsActivo = phpmkr_query($sqlActivo,$conn) or die ("Erro al inserta en tipo de ciente".phpmkr_error().$sqlActivo);
while($rowActivo = phpmkr_fetch_array($rsActivo)){
	$x_credito_id = $rowActivo["credito_id"];
	$x_tipo_cliente = "";
	$x_mayor_numero_dias_vencido = 0;
	echo "credito _id ".$x_credito_id;
	// seleciconamos los vencimientos del credito
	$sqlVencimiento  = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id  and fecha_vencimiento <  \"$x_today\" ";
	$rsVencimiento =  phpmkr_query($sqlVencimiento,$conn) or die("Error al seleccionar ven".phpmkr_error().$sqlVencimiento);
	while($rowVencimiento =  phpmkr_fetch_array($rsVencimiento)){
	$x_fecha_vencimiento = $rowVencimiento["fecha_vencimiento"]; 
	$x_status_vencimiento = $rowVencimiento["vencimiento_status_id"]; 
	$x_vencimiento_id = $rowVencimiento["vencimiento_id"];
	
	echo  "-".$x_status_vencimiento."<br>"; 
	echo "fecha venciemito ".$x_fecha_vencimiento."<br>";
	// si el vencimiento ya esta pagado se cuentas los dias que se tardo en pagar
	// SI EL VENCIMEINTO ESTA VENCID SE CUENTAS CUANTOS DIAS VENCIDOS TIENE
	// SI LOS DIAS VENDIDOS O DE PAGO EXCEDEN LOS 31 AHI SE TERMINA LA COMRACION DE LOS VENCIMEINTOS 
	
	if($x_mayor_numero_dias_vencido < 31){
	if($x_status_vencimiento == 3){
		
		$sqlDias = "SELECT DATEDIFF(\"$x_today\",\"$x_fecha_vencimiento\") as dias_transcurridos";
		$rsDiasTrans = phpmkr_query($sqlDias,$conn)or die ("Error al contar los dias".phpmkr_error().$sqlDias);
		$rowDiasTrans = phpmkr_fetch_array($rsDiasTrans);
		$x_dias_vencido = $rowDiasTrans["dias_transcurridos"];
		echo "dias vencido ".$x_dias_vencido."<br>";
		if($x_dias_vencido > $x_mayor_numero_dias_vencido){
			 $x_mayor_numero_dias_vencido = $x_dias_vencido;
		}
		}else if($x_status_vencimiento == 2){
			//el vencimiento fue pagado, buscmos la fecha de pago para comprobar cuanto se tardaron en pagarlo
			
			$sqlFechaPago = "SELECT fecha_pago FROM recibo, recibo_vencimiento WHERE recibo_vencimiento.recibo_id = recibo.recibo_id and recibo_vencimiento.vencimiento_id = $x_vencimiento_id and recibo_status_id=1  ";
			$rsFechaPago =  phpmkr_query($sqlFechaPago,$conn) or die("Error al seleccionar fecha pago".phpmkr_error().$sqlFechaPago);
			$rowFechaPago = phpmkr_fetch_array($rsFechaPago);
			$x_fecha_pago_vencimiento = $rowFechaPago["fecha_pago"];
			echo "fecha pago venc".$x_fecha_pago_vencimiento."<br>";
			$sqlDias = "SELECT DATEDIFF(\"$x_fecha_pago_vencimiento\",\"$x_fecha_vencimiento\") as dias_transcurridos";
			$rsDiasTrans = phpmkr_query($sqlDias,$conn)or die ("Error al contar los dias".phpmkr_error().$sqlDias);
			$rowDiasTrans = phpmkr_fetch_array($rsDiasTrans);
			$x_dias_vencido = $rowDiasTrans["dias_transcurridos"];
			echo "dias vencido ".$x_dias_vencido."<br>";
			if($x_dias_vencido > $x_mayor_numero_dias_vencido){
			 	$x_mayor_numero_dias_vencido = $x_dias_vencido;
			}
			
			
			}else if($x_status_vencimiento == 1){
				$x_dias_vencido = 0;
			
				}
		
		
			echo "encuantrauno mayor a 3o dias deberia dejar de comparar";
			//$rowVencimiento= array();			
			}
	
	}// while vencimiento
	
	if($x_mayor_numero_dias_vencido <= 0){
		$x_tipo_cliente = 'A';
		}else if($x_mayor_numero_dias_vencido > 0 && $x_mayor_numero_dias_vencido <= 10 ){
			$x_tipo_cliente = 'B';
			}else if($x_mayor_numero_dias_vencido > 10 && $x_mayor_numero_dias_vencido <=30 ){
				$x_tipo_cliente = 'C';
				}else if($x_mayor_numero_dias_vencido > 30){
					$x_tipo_cliente = 'D';
					}
	
	$sqlInsertTipoCliente = "INSERT INTO `tipo_cliente` (`tipo_cliente_id`, `credito_id`, `tipo_cliente`) VALUES (NULL, $x_credito_id, \"$x_tipo_cliente\")";
	$rsInsert = phpmkr_query($sqlInsertTipoCliente,$conn) or die(phpmkr_error."sql : ".$sqlInsertTipoCliente);
	echo $sqlInsertTipoCliente." <br><br>";
	
	
}
?>