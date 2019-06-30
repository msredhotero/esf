<?php set_time_limit(0); ?>
<?php session_start(); ?>
<?php ob_start(); ?> 

<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>

<?php

die();
#######################################################################################################################
#######################################################################################################################
#######################################################################################################################
###################              GENERA CORTE DE CUENTAS MENSUALMENTE                        ###########################
#######################################################################################################################
#######################################################################################################################

#!.- EL PROCESO SE EJECUTA EL ULTIMO DIA DEL MES
#2.- SOLO EN CREDITOS ACTIVOS
#3.- GUARDA UNA COPIA DEL CREDITO A COMO ESTABA EL ULTIMO DIA DEL MES
#4.- INSERTA EN LA TABLA CORTE
#5.- GUARDA LA COPIA DEL CREDITO EN LA TABLA CORTE_CREDITO
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$x_hoy = date("Y-m-d");

 
$mañana=  date("Y-m-d", strtotime("-1 day"));
echo "hoy es ".$x_hoy;
echo "<br>mañana es  ".$mañana;

//ultimo dia del mes 

$sqlultimo_dia_mes = " SELECT LAST_DAY('$x_hoy') AS ultimo_dia_mes ";
$rsUltimo_dia_mes = phpmkr_query($sqlultimo_dia_mes,$conn) or die ("Error la seleccionar el ultimo dia del mes".phpmkr_error()."sql: ".$sqlultimo_dia_mes);
$rowUltimo_dia_mes = phpmkr_fetch_array($rsUltimo_dia_mes);
$x_ultimo_dia_mes = $rowUltimo_dia_mes["ultimo_dia_mes"];
echo "<br>ultimo dia mes ".$x_ultimo_dia_mes;
if($x_hoy == $mañana){
	echo "<br>hoy es igual que mañana<br>";
	}else if($x_hoy < $mañana){
		echo "<br>hoy es menor que mañana";
		}else{
			echo "<br>Hoy es mayor que mañana";
			}
			
			
$sqlActivos = "SELECT credito_id FROM credito WHERE credito_status_id = 1 ";
$rsActivos = phpmkr_query($sqlActivos, $conn) or die ("Error la seleccionar los credito actyivos".phpmkr_error()."sql:".$sqlActivos);
while($rowActivos = phpmkr_fetch_array($rsActivos)){
	$x_credito_id = $rowActivos["credito_id"];
	
	
	// selecionamos el ultimo numero del corte
		$sqlNUmeroCorte = "SELECT max(numero_corte) as numero FROM corte WHERE credito_id =  $x_credito_id";
		$rsNumero_Corte = phpmkr_query($sqlNUmeroCorte,$conn) or die("Error al seelccionar el numero de corte".phpmkr_error()."sql:".$sqlNUmeroCorte);
		$rowNumero_corte = phpmkr_fetch_array($rsNumero_Corte);
		$x_numero_actual =  $rowNumero_corte["numero"] +0;
		$x_numero_sig = $x_numero_actual + 1;
		mysql_free_result($rsNumero_Corte);
		
		$x_today = date("Y-m-d");		
		#1.- se inserta en tabla corte
		$sqlInsertCorte = " INSERT INTO `corte` (`corte_id`, `credito_id`, `fecha`, `factura_id`, `numero_corte`) ";
		$sqlInsertCorte .= " VALUES (NULL, $x_credito_id, \"$x_today\", '0', $x_numero_sig)";
		$rsInsertCorte = phpmkr_query($sqlInsertCorte,$conn)or die ("Erro al insertar el corte".phpmkr_error()."sql:".$sqlInsert);
		mysql_free_result($rsInsertCorte);		
		$x_corte_id = mysql_insert_id();
	
	echo "inserta en corte ".$sqlInsertCorte."<br>";
	
	$sqlVencimiento = " SELECT * FROM vencimiento WHERE credito_id = $x_credito_id ";
	$rsVencimiento = phpmkr_query($sqlVencimiento,$conn)or die("Error al seleccionar los datos del credito-vencimientos".phpmkr_error()."sql;".$sqlVencimiento);
	while($rowVencimiento = mysql_fetch_assoc($rsVencimiento)){
		$x_vencimiento_id = $rowVencimiento["vencimiento_id"];
		$x_credito_id = $rowVencimiento["credito_id"];
		$x_vencimiento_num = $rowVencimiento["vencimiento_num"]+0;
		$x_vencimiento_status_id = $rowVencimiento["vencimiento_status_id"];
		$x_fecha_vencimiento = $rowVencimiento["fecha_vencimiento"];
		$x_importe = $rowVencimiento["importe"]+0;		
		$x_interes = $rowVencimiento["interes"]+0;
		$x_interes_moratorio = $rowVencimiento["interes_moratorio"]+0;
		$x_iva = $rowVencimiento["iva"]+0;
		$x_iva_mor = $rowVencimiento["iva_mor"]+0;
		$x_total_venc = $rowVencimiento["total_venc"]+0;
		
		
		$sqlInsert = " INSERT INTO `corte_credito` (`corte_credito_id`, `corte_id`, `vencimiento_id`, `credito_id`, `vencimiento_num`, `vencimiento_status_id`, `fecha_vencimiento`, `importe`, `interes`, `interes_moratorio`, `iva`, `iva_mor`, `total_venc`) ";
		$sqlInsert .= " VALUES (NULL, $x_corte_id, $x_vencimiento_id, $x_credito_id, $x_vencimiento_num, $x_vencimiento_status_id, \"$x_fecha_vencimiento\", $x_importe, $x_interes, $x_interes_moratorio, $x_iva, $x_iva_mor, $x_total_venc);";
		$rsInsert = phpmkr_query($sqlInsert,$conn) or die("Error al insertar en corte credito".phpmkr_error()."sql:".$sqlInsert);
		
		echo " corte credito ".$sqlInsert."<br>";
		
		}// para  cada registro encontrado se hace 	
	
	
	}

?>