<?php session_start(); ?>
<?php ob_start(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link  href="../crm/crm.css" rel="stylesheet" type="text/css" />
<link href="../crm/modulos/pagare_css.css" rel="stylesheet" type="text/css" media="print" />
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Cache-Control: private");
header("Pragma: no-cache"); // HTTP/1.0 
/*
if (@$_SESSION["php_project_esf_status"] <> "login") {
	header("Location:  login.php");
	exit();
}
*/
?>
<STYLE>
@media all {
   div.saltopagina{
      display: none;
   }
}
   
@media print{
   div.saltopagina{
      display:block;
      page-break-before:always;
   }
}
</STYLE> 
<?php
include ("db.php");
include ("phpmkrfn.php");
include("../crm/datefunc.php");

include("amount2txt.php");

$x_hoy = date("Y-m-d");

$x_promotor_id = $_GET["x_promo_srch"];
$x_fecha_carta = $_GET["fecha_carta"];
$x_destino_c = $_GET["dirigido"];
$x_edo_cuenta = $_GET["edo_cuenta"];
$x_credito_id = $_GET["credito_id"];
$x_imprimir_estado_cuenta = $x_edo_cuenta;

if($x_imprimir_estado_cuenta == 1){
	ob_end_clean();
	header("Location: php_credito_view_masivo.php?promotor_id=$x_promotor_id&destino=$x_destino_c");		
	}
//destino 1 = titular;
//destino 2 = aval;
$conn = phpmkr_db_connect(HOST, USER, PASS,DB, PORT);

//seleccionamos el usuario del promotor

########################################
//$x_promotor_id = 18;
//$x_hoy = "2013-03-05";
//$x_destino_c = 2;
#echo "destino".$x_destino_c."<br>";
########################################

$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	
$currdate = ConvertDateToMysqlFormat($currdate);
$currtime = $currentdate["hours"].":".$currentdate["minutes"].":".$currentdate["seconds"];	


// seleccionamos todos los casos que tengan para hoy una carta para imprimir
$sSqlGral = "SELECT * FROM credito ";
$sSqlGral .= " WHERE credito.credito_num in ( "; //listado de los creditos
$sSqlGral .= " 3599,3609,3752,
3869,3974,4195,4372,4574,4663,5010,5434,5531,5532,5564,5574,5578,5581,5589,5590,5591,5591,5593,5598,5599,5602,5603,5604,5642,5645,5646,5652,5653,5654,5655,5656,5658,5673,5674,5689,5697,5707,5711,5728,5736,5738,5739,5740,5760,5780,5823,5891,5944,5946,
6099,6394,6420,6434,6435,6451,6478,6505,6517,6597 ) ";

##se quito del query porque no straba las cartas



$rsGral = phpmkr_query($sSqlGral,$conn) or die ("error al selecciona las tareas de hoy".phpmkr_error()."sql:".$sSqlGral);
$x_registros = mysql_num_rows($rsGral);
#echo "registros  encontrados ".$x_registros."<br>";
while($rowGral = phpmkr_fetch_array($rsGral)){
	// JALAMOS LOS VALORE DE LA TAREA
	
	//se definene estos valores para que imprima la carta 1
	$x_orden = 4;
	$x_crm_tarea_tipo_id  = 8;
	$x_credito_id = $rowGral["credito_id"];
	#echo "tarea_status".$x_crm_tarea_status_id ."<br>";

	
	
	
	
	
	$sSql = "SELECT * FROM credito where credito_id = $x_credito_id";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_solicitud_id = $row["solicitud_id"];
	phpmkr_free_result($rs); //KUKI
	
	//AVAL
$sSql = "SELECT * FROM aval where solicitud_id = $x_solicitud_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_nombre_aval = $row["nombre_completo"]." ".$row["apellido_paterno"]." ".$row["apellido_materno"];
phpmkr_free_result($rs);


	
if(strlen ($x_nombre_aval)< 5 ){
	//buscamos el aval en las otras tablas donde puede estar si es que ya no esta en la tabal vieja
	#los datos del aval estan en la tabla datos_aval; por el cambio del formto en la solitud del aval	
	$sSql = " SELECT * FROM  datos_aval WHERE solicitud_id = $x_solicitud_id";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	
  $x_nombre_aval = $row["nombre_completo"]." ".$row["apellido_paterno"]." ".$row["apellido_materno"];
}
	
	// seleccionamos la fecha de otorgamiento del credito
	// los credito que no se otorgaron antes del 30 DE ABRIL DEL 2012
	// SE IMPRIMIRAN CON CARTA DE NUEVA ADMINISTRACION
	$x_fecha_otorgamiento = "";
	
	$SQLFECHAO = "SELECT fecha_otrogamiento FROM credito WHERE credito_id = $x_credito_id";
	$rsFcehao = phpmkr_query($SQLFECHAO,$conn)or die ("Error al seleccionar la fecha otor del cre". phpmkr_error()."sql:".$SQLFECHAO);
	$rowFechao = phpmkr_fetch_array($rsFcehao);
	$x_fecha_otorgamiento = $rowFechao["fecha_otrogamiento"];
	#echo "fecha_otorgamiento".$x_fecha_otorgamiento."<br>";
	
	
	$sqlCredito = "SELECT * FROM credito WHERE credito_id = $x_credito_id ";
	$rsCredito = phpmkr_query($sqlCredito,$conn)or die ("Error al seleccionar los datos del credito".phpmkr_error()."sql".$sqlCredito);
	$rowCredito = phpmkr_fetch_array($rsCredito);
	$x_credito_tipo_id = $rowCredito["credito_tipo_id"];
	$x_solicitud_id = $rowCredito["solicitud_id"];
	
#	echo "sol_id".$x_solicitud_id."<br>";
	if($x_credito_tipo_id == 2){
	#	echo "sol_id..".$x_solicitud_id."<br>";
		$x_lista_integrantes_id = "";
			// ES UN CREDITO SOLIDARIO
			//echo "load data solidario";
			$sqlGrupo = "SELECT * FROM creditosolidario WHERE  solicitud_id = $x_solicitud_id";
		#	echo "sql solidario".$sqlGrupo."<br>";
			$responseGrupo = phpmkr_query($sqlGrupo,$conn) or die ("error al ejecutar query grupo".phpmkr_error()."sql: ".$sqlGrupo);
			$rowGrupo = phpmkr_fetch_array($responseGrupo);
			$GLOBALS["x_creditoSolidario_id"] =  $rowGrupo["creditoSolidario_id"];
			$GLOBALS["x_nombre_grupo"] = $rowGrupo["nombre_grupo"];
			#echo $GLOBALS["x_nombre_grupo"]."..";
			
			
			$x_cont_g = 1;
			while($x_cont_g <= 10){
				
				$GLOBALS["x_integrante_$x_cont_g"] = $rowGrupo["integrante_$x_cont_g"];
				$GLOBLAS["x_nombre_integrante_$x_cont_g"] = $rowGrupo["integrante_$x_cont_g"];
				
				#echo "integrante".$rowGrupo["integrante_$x_cont_g"]."id ".$rowGrupo["cliente_id_$x_cont_g"]. "<br>";
				
				if(!empty($rowGrupo["cliente_id_$x_cont_g"]) && $rowGrupo["cliente_id_$x_cont_g"] +0 > 1 ){
					$x_lista_integrantes = $rowGrupo["integrante_$x_cont_g"].", ";
					$x_lista_integrantes_id = $x_lista_integrantes_id.$rowGrupo["cliente_id_$x_cont_g"].", ";					
					}
				//BUSCO AL REPRESENTANTE DEL GRUPO
				if($GLOBALS["x_rol_integrante_$x_cont_g"] == 1){
					$GLOBALS["$x_representate_grupo"] = $rowGrupo["integrante_$x_cont_g"];
					$GLOBALS["x_representante_cliente_id"] =  $rowGrupo["cliente_id_$x_cont_g"];
					}
								
				$x_cont_g++;
				}
			
			$x_lista_integrantes_id = trim($x_lista_integrantes_id, ", ");
			
			#echo "lista de integrantes".$x_lista_integrantes_id;
			$x_array_cliente_grupo  = explode(",",$x_lista_integrantes_id);
			foreach($x_array_cliente_grupo as $x_no_cliente){
				#Imprimimos le formato para cada integrante del grupo
			#	echo "entra al foreach";
					if(($x_orden == 4) and ($x_crm_tarea_tipo_id == 8)){
			// se trata de una carta 1
			//$x_formato = 6;// va dirgida al titular
			//nuevos formatos
			$x_formato = 54;
			
			
			if($x_destino_c == 2){
				//$x_formato = 7;
				// nuevos formatos
				$x_formato = 48;// va dirgida al aval
				
				
				#echo "formato".$x_formato;
				}
			//	echo "formato ".$x_formato."<br>";
				if ($x_formato == 54 || $x_formato == 55 ){
				$x_tabla1 = loadDataCartaAdmin($conn, $x_credito_id, $x_formato, $x_no_cliente);
				if($x_formato == 53){
				//	$x_tabla1 = "";
					}
					
				}else{
				$x_tabla1 = loadDataCarta1($conn, $x_credito_id, $x_formato, $x_no_cliente);
				if($x_formato == 48){
					//$x_tabla1 = "";
					}
				}
				//echo "formato".$x_formato."<br>";
			echo $x_tabla1;
			?>
			<div class="saltopagina"></div>			
			<?php
			
			$x_edo_cuenta = LoadDataEdoCuenta($conn,$x_credito_id);			
			echo $x_edo_cuenta;
			?>
			<div class="saltopagina"></div>			
			<?php
			
		
			
			}// if carat 1
				
				}

			
			phpmkr_free_result($rowGrupo);
			
			
		
		
		
	
	}else{
	//verificamos que tipo de tarea es, para saber si es carta y cual carta es	
	// carta 1 = orden --> 4 ; crm_tarea_tipo_id -->8
	// carta 2 = orden --> 8 ; crm_tarea_tipo_id -->9
	// carta 3 = orden --> 12 ; crm_tarea_tipo_id -->10
	// carta D = orden --> 20 ; crm_tarea_tipo_id -->12
	
	#echo "entra a else ind";
	##echo "tipo". $x_crm_tarea_tipo_id."<br>";
	
	if( ($x_orden == 4) || ($x_orden == 8) || ($x_orden == 12) || ($x_orden == 20) ){		
		//ENTRA PARA IMPRIMIR LAS CARTAS
		$x_cli = 0;
		if(($x_orden == 4) and ($x_crm_tarea_tipo_id == 8)){
			// se trata de una carta 1
			//$x_formato = 6;// va dirgida al titular
			//nuevos formatos
			$x_formato = 54;
			
			
			if($x_destino_c == 2){
				//$x_formato = 7;
				// nuevos formatos
				$x_formato = 48;// va dirgida al aval
				
				
				#echo "formato".$x_formato;
				}
				#echo "formato ".$x_formato."<br>";
				if ($x_formato == 54 || $x_formato == 55 ){
				$x_tabla1 = loadDataCartaAdmin($conn, $x_credito_id, $x_formato,$x_cli);
				}else{
				$x_tabla1 = loadDataCarta1($conn, $x_credito_id, $x_formato,$x_cli);
				}
			echo $x_tabla1;
			?>
			<div class="saltopagina"></div>			
			<?php
			
			$x_edo_cuenta = LoadDataEdoCuenta($conn,$x_credito_id);
	#echo "sale edocuenta<br>";
	echo $x_edo_cuenta;
			?>
			<div class="saltopagina"></div>			
			<?php
	
			
			
			if(strlen ($x_nombre_aval)> 5 ){
				//SI HAY AVAL IMPRIMIMOS LA CARTA DEL AVAL
				#echo "FROMATO ELTRO DE IF AVAL----".$x_formato." ---<br>".$x_nombre_aval."---";
				if ($x_formato == 54  ){
					$x_formato = 55;
				$x_tabla1 = loadDataCartaAdmin($conn, $x_credito_id, $x_formato,$x_cli);
				}else{
					$x_formato = 55;					
					$x_tabla1 = loadDataCarta1($conn, $x_credito_id, $x_formato,$x_cli);
				
			#echo "la tabla es :".$x_tabla1 ."<br><br>";	
				}
				echo $x_tabla1;
			?>
			<div class="saltopagina"></div>			
			<?php
			
				$x_edo_cuenta = LoadDataEdoCuenta($conn,$x_credito_id);
				echo $x_edo_cuenta;
#	echo "sale edocuenta<br>";
			?>
			<div class="saltopagina"></div>			
			<?php
				}
			
			
			
			}// if carat 1
			
		
			
		}
	
	}//else credito grupal
	
	if(($x_orden == 4) || ($x_orden == 8) || ($x_orden == 12) || ($x_orden == 20)){
	
	//imprimos el estado de cuenta
	//echo "entra edocuenta este estado de cuenta es para el TH<br>";
	
	}
//	}// si no esta cerrado el caso
	}// while principal




function LoadDataCartaAdmin($conn, $credito_id, $x_formato, $x_cliente_ida)
{
	//CREDITO
	#echo "entro carta admin credito_id = ".$credito_id;
	
	
	$x_credito_id = $credito_id;
	$sSql = "SELECT * FROM credito where credito_id = $x_credito_id";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_solicitud_id = $row["solicitud_id"];
	$x_tarjeta_numero = $row["tarjeta_num"];
	$x_fecha_otorgamiento = $row["fecha_otrogamiento"];
	phpmkr_free_result($rs);
	#echo "no entra";
	$sSql = "SELECT * FROM solicitud where solicitud_id = $x_solicitud_id";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	if (phpmkr_num_rows($rs) == 0) {
		$bLoadData = false;
	}else{
		#echo "entra";
		$bLoadData = true;
		$row = phpmkr_fetch_array($rs);

		$GLOBALS["x_solicitud_id"] = $row["solicitud_id"];
		$GLOBALS["x_credito_tipo_id"] = $row["credito_tipo_id"];
		$GLOBALS["x_solicitud_status_id"] = $row["solicitud_status_id"];
		$GLOBALS["x_folio"] = $row["folio"];
		$GLOBALS["x_fecha_registro"] = $row["fecha_registro"];
		$GLOBALS["x_promotor_id"] = $row["promotor_id"];
		$GLOBALS["x_importe_solicitado"] = $row["importe_solicitado"];
		$GLOBALS["x_plazo_id"] = $row["plazo_id"];
		$GLOBALS["x_forma_pago_id"] = $row["forma_pago_id"];		
		$GLOBALS["x_contrato"] = $row["contrato"];
		$GLOBALS["x_pagare"] = $row["pagare"];
		
		
		$sSql = "SELECT * FROM credito  WHERE solicitud_id = ".$GLOBALS["x_solicitud_id"]." ";
		$rs = phpmkr_query($sSql, $conn) or die ("Error al selccionar los datos del credito". phpmkr_error()."sql;".$sSql);
		$rowC = phpmkr_fetch_array($rs);
		$GLOBALS["x_penalizacion"] = $rowC["penalizacion"];
		$GLOBALS["x_garantia_liquida"] = $rowC["garantia_liquida"];
		$GLOBALS["x_credito_id"] = $rowC["credito_id"];
		
		
		//INTEGRANTES DEL GRUPO
		$x_soli_id =  $GLOBALS["x_solicitud_id"];
		if($GLOBALS["x_credito_tipo_id"] == 2){
			// ES UN CREDITO SOLIDARIO
			//echo "load data solidario";
			$sqlGrupo = "SELECT * FROM creditosolidario WHERE  solicitud_id = $x_soli_id";
			//echo "sql solidario".$sqlGrupo."<br>";+
			$responseGrupo = phpmkr_query($sqlGrupo,$conn) or die ("error al ejecutar query grupo".phpmkr_error()."sql: ".$sqlGrupo);
			$rowGrupo = phpmkr_fetch_array($responseGrupo);
			$GLOBALS["x_creditoSolidario_id"] =  $rowGrupo["creditoSolidario_id"];
			$x_nombre_grupo = "GRUPO ".$rowGrupo["nombre_grupo"];
			
			$x_cont_g = 1;
			while($x_cont_g <= 10){
				
				$GLOBALS["x_integrante_$x_cont_g"] = $rowGrupo["integrante_$x_cont_g"];
				//$x_monto_i =  $rowGrupo["monto_$x_cont_g"];
				//$GLOBALS["x_monto_$x_cont_g"] = number_format($x_monto_i);
				$GLOBALS["x_monto_$x_cont_g"] =  $rowGrupo["monto_$x_cont_g"];
				$GLOBALS["x_rol_integrante_$x_cont_g"] = $rowGrupo["rol_integrante_$x_cont_g"]; 
				$GLOBALS["x_cliente_id_$x_cont_g"] = $rowGrupo["cliente_id_$x_cont_g"];
				
				//BUSCO AL REPRESENTANTE DEL GRUPO
				if($GLOBALS["x_rol_integrante_$x_cont_g"] == 1){
					$GLOBALS["$x_representate_grupo"] = $rowGrupo["integrante_$x_cont_g"];
					$GLOBALS["x_representante_cliente_id"] =  $rowGrupo["cliente_id_$x_cont_g"];
					}
				
				$x_cont_g++;
				}
			
			
			
			phpmkr_free_result($rowGrupo);
			
			}



	//CREDITO
	//SELECIONO LOS DATIS DE CREDITO PARA HACER LA TABLA  CON LA TASA AL 60%


//CLIENTE

if($x_cliente_ida > 1){
	$sSql = "select cliente.* from cliente  where cliente_id = $x_cliente_ida ";	
	}else{

		$sSql = "select cliente.* from cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id where solicitud_cliente.solicitud_id = $x_solicitud_id";	
	}
		$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row2 = phpmkr_fetch_array($rs2);
		$GLOBALS["x_cliente_id"] = $row2["cliente_id"];
		$GLOBALS["x_usuario_id"] = $row2["usuario_id"];
		$x_nombre_completo = $row2["nombre_completo"];
		$x_apellido_paterno = $row2["apellido_paterno"];		
		$x_apellido_materno = $row2["apellido_materno"];				
		$GLOBALS["x_tipo_negocio"] = $row2["tipo_negocio"];
		$GLOBALS["x_edad"] = $row2["edad"];
		$x_sexo = $row2["sexo"];
		$GLOBALS["x_estado_civil_id"] = $row2["estado_civil_id"];
		$GLOBALS["x_numero_hijos"] = $row2["numero_hijos"];
		$GLOBALS["x_nombre_conyuge"] = $row2["nombre_conyuge"];
		$GLOBALS["x_email"] = $row2["email"];	
		
		$x_cliente =  $x_nombre_completo." ".$x_apellido_paterno." ".$x_apellido_materno;
		if($x_formato != 53){
		if($x_sexo == 1){
	$x_cliente = "ESTIMADO ".$x_cliente;
}else{
	$x_cliente = "ESTIMADA ".$x_cliente;
}	
		}
		
		
		


				
		



		$sSql = "select * from aval where solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rs5 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row5 = phpmkr_fetch_array($rs5);
		$GLOBALS["x_aval_id"] = $row5["aval_id"];
		$GLOBALS["x_nombre_completo_aval"] = $row5["nombre_completo"];
		$GLOBALS["x_apellido_paterno_aval"] = $row5["apellido_paterno"];
		$GLOBALS["x_apellido_materno_aval"] = $row5["apellido_materno"];										
		$GLOBALS["x_parentesco_tipo_id_aval"] = $row5["parentesco_tipo_id"];
		$GLOBALS["x_telefono3"] = $row5["telefono"];
		$GLOBALS["x_ingresos_mensuales"] = $row5["ingresos_mensuales"];
		$GLOBALS["x_ocupacion"] = $row5["ocupacion"];


		if($GLOBALS["x_aval_id"] != ""){
			$sSql = "select * from direccion where aval_id = ".$GLOBALS["x_aval_id"]." and direccion_tipo_id = 3";
			$rs6 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
			$row6 = phpmkr_fetch_array($rs6);
			$GLOBALS["x_direccion_id6"] = $row6["direccion_id"];
			$GLOBALS["x_calle3"] = $row6["calle"];
			$GLOBALS["x_colonia3"] = $row6["colonia"];
			$GLOBALS["x_delegacion_id3"] = $row6["delegacion_id"];
			$GLOBALS["x_otra_delegacion3"] = $row6["otra_delegacion"];
			$GLOBALS["x_entidad3"] = $row6["entidad"];
			$GLOBALS["x_codigo_postal3"] = $row6["codigo_postal"];
			$GLOBALS["x_ubicacion3"] = $row6["ubicacion"];
			$GLOBALS["x_antiguedad3"] = $row6["antiguedad"];
			$GLOBALS["x_vivienda_tipo_id2"] = $row6["vivienda_tipo_id"];
			$GLOBALS["x_otro_tipo_vivienda3"] = $row6["otro_tipo_vivienda"];

			$GLOBALS["x_telefono3"] = $row6["telefono"];
			$GLOBALS["x_telefono_secundario3"] = $row6["telefono_secundario"];
		}else{
			$GLOBALS["x_direccion_id3"] = "";
			$GLOBALS["x_calle3"] = "";
			$GLOBALS["x_colonia3"] = "";
			$GLOBALS["x_delegacion_id3"] = "";
			$GLOBALS["x_otra_delegacion3"] = "";
			$GLOBALS["x_entidad3"] = "";
			$GLOBALS["x_codigo_postal3"] = "";
			$GLOBALS["x_ubicacion3"] = "";
			$GLOBALS["x_antiguedad3"] = "";
			$GLOBALS["x_vivienda_tipo_id2"] = "";
			$GLOBALS["x_otro_tipo_vivienda3"] = "";
			$GLOBALS["x_telefono3"] = "";
			$GLOBALS["x_telefono_secundario3"] = "";
		}


		$sSql = "select * from garantia where solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rs7 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row7 = phpmkr_fetch_array($rs7);
		$GLOBALS["x_garantia_desc"] = $row7["descripcion"];
		$GLOBALS["x_garantia_valor"] = $row7["valor"];		

		$sSql = "select * from ingreso where solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rs8 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row8 = phpmkr_fetch_array($rs8);
		$GLOBALS["x_ingreso_id"] = $row8["ingreso_id"];
		$GLOBALS["x_ingresos_negocio"] = $row8["ingresos_negocio"];
		$GLOBALS["x_ingresos_familiar_1"] = $row8["ingresos_familiar_1"];
		$GLOBALS["x_parentesco_tipo_id_ing_1"] = $row8["parentesco_tipo_id"];
		$GLOBALS["x_ingresos_familiar_2"] = $row8["ingresos_familiar_2"];
		$GLOBALS["x_parentesco_tipo_id_ing_2"] = $row8["parentesco_tipo_id2"];
		$GLOBALS["x_otros_ingresos"] = $row8["otros_ingresos"];

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_nombre_completo_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_nombre_completo_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_nombre_completo_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_nombre_completo_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";

		$GLOBALS["x_referencia_id_1"] = "";
		$GLOBALS["x_nombre_completo_1"] = "";
		$GLOBALS["x_telefono_1"] = "";
		$GLOBALS["x_parentesco_tipo_id_ref_1"] = "";
	

		$x_count = 1;
		$sSql = "select * from referencia where solicitud_id = ".$GLOBALS["x_solicitud_id"]." order by referencia_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9 = phpmkr_fetch_array($rs9)){
			$GLOBALS["x_referencia_id_$x_count"] = $row9["referencia_id"];
			$GLOBALS["x_nombre_completo_ref_$x_count"] = $row9["nombre_completo"];
			$GLOBALS["x_telefono_ref_$x_count"] = $row9["telefono"];
			$GLOBALS["x_parentesco_tipo_id_ref_$x_count"] = $row9["parentesco_tipo_id"];
			$x_count++;
		}




		$x_contenido_id = 0;
		
		$sSql = "SELECT count(*) as gara FROM garantia where solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row = phpmkr_fetch_array($rs);
		$x_gara = $row["gara"];
		phpmkr_free_result($rs);

	/*	if($x_gara > 0){
			$x_contenido_id = 3;
		}else{
			$x_contenido_id = 12;
		}*/
		
		$sSql = "SELECT nombre_completo AS avales FROM datos_aval WHERE solicitud_id = ".$GLOBALS["x_solicitud_id"];
		$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row = phpmkr_fetch_array($rs);
		$x_aval = $row["avales"];
		if( strlen(trim($x_aval)) > 0  ){
			$x_aval = 1;
			}
		$x_valor_credito_tipo_id = $GLOBALS["x_credito_tipo_id"];
		
		
				
				//echo "contenido".$x_contenido_id;		
		$sSql = "select contenido from formato_docto where formato_docto_id = $x_formato";
		$rs10 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row10 = phpmkr_fetch_array($rs10);
		$x_contenido = $row10["contenido"];
		

		#seleccionamos los datos de los vencimeintos
		$currdate = date("Y-m-d");
		$x_total_gral = 0;
		$x_capital_ordinario = 0;
		$x_total_gral_sin_tope = 0;
		$x_total_sin_tope = 0;
		
		$sqlSaldoD = "SELECT SUM(total_venc) as total_p  FROM vencimiento WHERE credito_id = ". $GLOBALS["x_credito_id"]." and vencimiento_status_id in  (1,3,6)  ";
		$rsSaldoD = phpmkr_query($sqlSaldoD,$conn)or die("error sal ven".phpmkr_error()."sql :".$sqlSaldoD);
		$rowSaldoD = phpmkr_fetch_array($rsSaldoD);
		$_total_faltante = $rowSaldoD["total_p"];//total
		
		$sqlSaldoD = "SELECT SUM(interes_moratorio) as moratorio  FROM vencimiento WHERE credito_id = ". $GLOBALS["x_credito_id"]." and vencimiento_status_id in  (1,3,6)  ";
		$rsSaldoD = phpmkr_query($sqlSaldoD,$conn)or die("error sal ven".phpmkr_error()."sql :".$sqlSaldoD);
		$rowSaldoD = phpmkr_fetch_array($rsSaldoD);
		$_moratorio = $rowSaldoD["moratorio"];
		
		$sqlSaldoD = "SELECT SUM(iva_mor) as iva_moratorio  FROM vencimiento WHERE credito_id = ". $GLOBALS["x_credito_id"]." and vencimiento_status_id in  (1,3,6)  ";
		$rsSaldoD = phpmkr_query($sqlSaldoD,$conn)or die("error sal ven".phpmkr_error()."sql :".$sqlSaldoD);
		$rowSaldoD = phpmkr_fetch_array($rsSaldoD);
		$_iva_moratorio = $rowSaldoD["iva_moratorio"];
		
		$x_condonado = $_total_faltante -($_moratorio + $_iva_moratorio );
	#	echo "condonado".$x_condonado."<br>";
		
	
		
		
	#echo "total faltante ".$_total_faltante."<br>";	
		$SQLcRDITO = "SELECT * FROM credito WHERE credito_id = ". $GLOBALS["x_credito_id"]." ";
		$rscRDITO = phpmkr_query($SQLcRDITO,$conn) or die("error la seleccionar los datos del credito".phpmkr_error()."sql;".$SQLcRDITO);
		$rowscRDITO = phpmkr_fetch_array($rscRDITO);
		$x_tasa_moratoria = $rowscRDITO["tasa_moratoria"];
		
		$sqlVencimeintos = "SELECT * FROM vencimiento WHERE credito_id = ". $GLOBALS["x_credito_id"]." and vencimiento_status_id in  (1,3)  order by vencimiento_num  ";
		#echo "sql mayor ".$sqlVencimeintos."<br>";
		$x_total_dias_vencidos  = 0;
		$rsVencimeintos = phpmkr_query($sqlVencimeintos,$conn)or die ("Error al seleccionar los vencimientos". phpmkr_error()."sql:".$sqlVencimeintos);
		while($rowVencimientos = phpmkr_fetch_array($rsVencimeintos)){
			// seleccionamos el primer vancimeinto.
			// Y  vemos si esta vencido o o no
			$x_vencimiento_id = $rowVencimientos["vencimeinto_id"];
			$x_vencimiento_num = $rowVencimientos["vencimiento_num"];
			$x_vencimiento_status_id = $rowVencimientos["vencimiento_status_id"];//status vencimiento
			#echo "<br>venc staus----".$x_vencimiento_status_id;
			$x_fecha_vencimiento = $rowVencimientos["fecha_vencimiento"];// fecha de pago
			$x_importe = $rowVencimientos["importe"]; // importe	
			$x_interes = $rowVencimientos["interes"]; // interes
			$x_interes_moratorio = $rowVencimientos["interes_moratorio"]; //interes moratorio
			$x_iva = $rowVencimientos["iva"]; // iva
			$x_iva_mor = $rowVencimientos["iva_mor"];//  iva mor
			$x_total_venc = $rowVencimientos["total_venc"]; // total venc		
		
			
			$x_credito_id = $rowVencimientos["credito_id"];
		#	echo "<br>credito_id = ".$x_credito_id; 			
			$x_total_con_tope = $x_total_venc;			
			$x_c_ordi = $x_importe + $x_interes + $x_iva;	
					
			// status_vencimeito_id
			// 1 .- pendiente
			// 2.- pagado
			// 3.- vencido
			// 4.- cabcelado
			
			if(($x_vencimiento_status_id == 3)){
				// se recalculan los moratorios sin toparlos				
				#echo "<br>entra al if<br>";
				$sqlVenNum = "SELECT COUNT(*) AS numero_de_pagos FROM vencimiento WHERE  fecha_vencimiento = \"$x_fecha_vencimiento\" AND  credito_id =  $x_credito_id";
				$response = phpmkr_query($sqlVenNum, $conn) or die("error en numero de vencimiento".phpmkr_error()."sql:".$sqlVenNum);
				$rownpagos = phpmkr_fetch_array($response);  
				$x_numero_de_pagos =  $rownpagos["numero_de_pagos"];
				
				//if($x_numero_de_pagos < 2){
				//se hace el clculo de los moratorios				
				$x_dias_vencidos = datediff('d', $x_fecha_vencimiento, $currdate, false);					
					$x_dia = strtoupper(date('l',strtotime($x_fecha_vencimiento)));				
				
					#echo "<br>fecha  vencimeinto".$x_fecha_vencimiento;
	#echo "<br>fecha actual  ".$currdate;	
	
				
					$x_dias_gracia = 2;
					switch ($x_dia)
					{
						case "MONDAY": // Get a record to display
							$x_dias_gracia = 2;
							break;
						case "TUESDAY": // Get a record to display
							$x_dias_gracia = 2;
							break;
						case "WEDNESDAY": // Get a record to display
							$x_dias_gracia = 4;
							break;
						case "THURSDAY": // Get a record to display
							$x_dias_gracia = 4;
							break;
						case "FRIDAY": // Get a record to display
							$x_dias_gracia = 4;
							break;
						case "SATURDAY": // Get a record to display
							$x_dias_gracia = 3;
							break;
						case "SUNDAY": // Get a record to display
							$x_dias_gracia = 2;
							break;		
					}
	#echo "dias de gracia".$x_dias_gracia;
					#	echo "froma de pago =".$x_forma_pago_valor."<br>";
					#	echo "penalizacion ".$x_penalizacion."<br>";
						if(($x_dias_vencidos >= $x_dias_gracia) ){
							$x_importe_mora = 10 * $x_dias_vencidos;
							#echo "dias vencido".$x_dias_vencidos."<br>";							
							#echo "numero venc".$x_vencimiento_num."<br><br>";
							#echo "<br>importe mora".$x_importe_mora."<br>";
							if($x_iva_credito == 1){
								$x_iva_mor = round($x_importe_mora * .16);
								$x_iva_mor = 0;			
							}else{
								$x_iva_mor = 0;
							}
							
					//moratorios no majyores a 	2						
							if($x_credito_tipo_id == 2){			
								if($x_importe_mora > 0){
									$x_importe_mora = 250;
									}			
								}
								#echo "importe mora truncado".$x_importe_mora."<br>";
								
								if($x_vencimiento_num < 1000){
							$x_total_sin_tope = $x_importe + $x_interes + $x_importe_mora + $x_iva + $x_iva_mor;					
							#echo "total sin tope dentro del if". $x_total_sin_tope."-- <br>";
						#	echo " staus credito".$x_vencimiento_status_id ." <br>dias vencido  ".$x_dias_vencidos."<br>";
							$x_total_dias_vencidos = $x_total_dias_vencidos +$x_dias_vencidos;
							#echo "<br><br> total ".$x_total_dias_vencidos."<br>";
								}else{
									$x_total_sin_tope = $x_total_venc;
									
									}
							
						}// dias vencidos mayor o ogial a dias de gracia....
				
				
				//}// numero de pagos menor a 2
				
				
		#	echo "numero de dias ".$x_dias_vencidos." vencimiento num ".$x_vencimiento_num."total del vencime".$x_total_con_tope."<br>";				
				$x_total_gral_sin_tope = $x_total_gral_sin_tope +$x_total_sin_tope;
		#	echo "<br> sin tope  total".$x_total_gral_sin_tope."<br>";
			$x_capital_ordinario = $x_capital_ordinario +$x_c_ordi;
			$x_total_gral_con_tope = $x_total_gral_con_tope + $x_total_con_tope;
			#echo "<br> CON tope".$x_total_gral_con_tope;
		#	echo "<br> ordinario".$x_capital_ordinario;
		#		
				}//vencimeinto_vencido
		#	echo "no entra<br>";
			$x_total_con_tope = $x_total_venc;
			
		
			}//while
			
$x_nombre_aval = "";			
//AVAL
$sSql = "SELECT * FROM aval where solicitud_id = $x_solicitud_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
if($row["sexo"] == 1){
	$x_saludo = "Sr.";
}else{
	$x_saludo = "Sra.";	
}

//$x_aval = $row["nombre_completo"];
  $x_nombre_aval = $row["nombre_completo"]." ".$row["apellido_paterno"]." ".$row["apellido_materno"];
	$x_aval = $x_saludo." ". $x_nombre_aval;
	#echo "el aval es".$x_aval."<br>";						

phpmkr_free_result($rs);

if(strlen ($x_nombre_aval)< 5){
	//buscamos el aval en las otras tablas donde puede estar si es que ya no esta en la tabal vieja
	#los datos del aval estan en la tabla datos_aval; por el cambio del formto en la solitud del aval
	
	
	$sSql = " SELECT * FROM  datos_aval WHERE solicitud_id = $x_solicitud_id";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	if($row["sexo"] == 1){
		$x_saludo = "Sr.";
	}else{
		$x_saludo = "Sra.";	
	}
  $x_nombre_aval = $row["nombre_completo"]." ".$row["apellido_paterno"]." ".$row["apellido_materno"];
//$x_aval = $row["nombre_completo"];
	$x_aval = $x_saludo." ". $x_nombre_aval;	
	}			

$GLOBALS["x_total_gral_sin_tope"] = $x_total_gral_sin_tope ;
$GLOBALS["x_capital_ordinario"] = $x_capital_ordinario ;
$GLOBALS["x_total_gral_con_tope"] = $x_total_gral_con_tope ;


// carta cambio de administracion

//echo "x_saldo_total_credito".$x_saldo_total_credito."<br>";
//x_saldo_total_credito
#echo "--- DV".$x_total_dias_vencidos."<br>";
$x_total_gral_sin_tope = ($x_total_dias_vencidos * 10) + $x_condonado;
$x_total_gral_con_tope = "$".FormatNumber($_total_faltante,2,0,0,1);
$x_total_gral_sin_tope = "$".FormatNumber($x_total_gral_sin_tope,2,0,0,1);
$x_capital_ordinario = "$".FormatNumber($x_capital_ordinario,2,0,0,1);
$x_condonado = "$".FormatNumber($x_condonado,2,0,0,1);

$x_hoy = date("Y-m-d");

$sqlDV = "select DATE_ADD(\"$x_hoy\",INTERVAL 10 DAY) as dia_valido";
#echo "sql".$sqlDV." ";
$rsDV = phpmkr_query($sqlDV,$conn)or die("Erro al sle dia val".phpmkr_error());
$rowDV = phpmkr_fetch_array($rsDV);
$x_dia_valido = $rowDV["dia_valido"];
#echo "dia valido".$x_dia_valido."<br>";

$currdate = date("Y-m-d");

$x_fecha_actual  = FechaLetras_normal(strtotime(ConvertDateToMysqlFormat($currdate)),false);

$x_fecha_valida =  FechaLetras_normal(strtotime($x_dia_valido),false);
$x_contenido = str_replace("\$x_saldo_total_credito",$x_total_gral_con_tope,$x_contenido);
$x_contenido = str_replace("\$x_mora_sin_truncar",$x_total_gral_sin_tope,$x_contenido);
$x_contenido = str_replace("\$x_capital_ordinario_iva",$x_condonado,$x_contenido);
$x_contenido = str_replace("\$x_fecha_valida",$x_fecha_valida,$x_contenido);
$x_contenido = str_replace("\$x_fecha_actual",$x_fecha_actual,$x_contenido);
$x_contenido = str_replace("\$x_cliente",htmlentities($x_cliente),$x_contenido);
$x_contenido = str_replace("\$x_aval",htmlentities($x_aval),$x_contenido);
$x_contenido = str_replace("\$x_nombre_grupo",$x_nombre_grupo,$x_contenido);


	}
	phpmkr_free_result($rs);
	phpmkr_free_result($rs2);	
	phpmkr_free_result($rs3);		
	phpmkr_free_result($rs4);			
	phpmkr_free_result($rs5);				
	phpmkr_free_result($rs6);					
	phpmkr_free_result($rs7);						
	phpmkr_free_result($rs8);
	phpmkr_free_result($rs9);								
	phpmkr_free_result($rs10);	

#echo "nombre aval".$x_nombre_aval."<br>";
	
	if((strlen ($x_nombre_aval)< 5 )and ($x_formato == 48 || $x_formato == 53) ){
	$x_contenido ="";
	}		
	

	
									
	return $x_contenido;
}




function loadDataCarta1($conn, $credito_id, $x_formato, $x_cliente_ida){
	//CREDITO
	$x_credito_id = $credito_id;
	$sSql = "SELECT * FROM credito where credito_id = $x_credito_id";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_solicitud_id = $row["solicitud_id"];
	$x_tarjeta_numero = $row["tarjeta_num"];
	$x_fecha_otorgamiento = $row["fecha_otrogamiento"];
	phpmkr_free_result($rs);
	$x_fecha_otorgamiento = FechaLetras_normal(strtotime($x_fecha_otorgamiento),false);
	
	
	//INTEGRANTES DEL GRUPO
		$x_soli_id =  $x_solicitud_id;
		if($GLOBALS["x_credito_tipo_id"] == 2){
			// ES UN CREDITO SOLIDARIO
			//echo "load data solidario";
			$sqlGrupo = "SELECT * FROM creditosolidario WHERE  solicitud_id = $x_soli_id";
			//echo "sql solidario".$sqlGrupo."<br>";+
			$responseGrupo = phpmkr_query($sqlGrupo,$conn) or die ("error al ejecutar query grupo".phpmkr_error()."sql: ".$sqlGrupo);
			$rowGrupo = phpmkr_fetch_array($responseGrupo);
			$GLOBALS["x_creditoSolidario_id"] =  $rowGrupo["creditoSolidario_id"];
			$x_nombre_grupo = $rowGrupo["nombre_grupo"];
			
			$x_cont_g = 1;
			while($x_cont_g <= 10){
				
				$GLOBALS["x_integrante_$x_cont_g"] = $rowGrupo["integrante_$x_cont_g"];
				//$x_monto_i =  $rowGrupo["monto_$x_cont_g"];
				//$GLOBALS["x_monto_$x_cont_g"] = number_format($x_monto_i);
				$GLOBALS["x_monto_$x_cont_g"] =  $rowGrupo["monto_$x_cont_g"];
				$GLOBALS["x_rol_integrante_$x_cont_g"] = $rowGrupo["rol_integrante_$x_cont_g"]; 
				$GLOBALS["x_cliente_id_$x_cont_g"] = $rowGrupo["cliente_id_$x_cont_g"];
				
				//BUSCO AL REPRESENTANTE DEL GRUPO
				if($GLOBALS["x_rol_integrante_$x_cont_g"] == 1){
					$GLOBALS["$x_representate_grupo"] = $rowGrupo["integrante_$x_cont_g"];
					$GLOBALS["x_representante_cliente_id"] =  $rowGrupo["cliente_id_$x_cont_g"];
					}
				
				$x_cont_g++;
				}
			
			
			
			phpmkr_free_result($rowGrupo);
			
			}

//CLIENTE

if($x_cliente_ida > 1){
	$sSqlWrk = "SELECT cliente.usuario_id, cliente.sexo, cliente.nombre_completo as cliente_nombre, cliente.apellido_paterno, cliente.apellido_materno FROM cliente Where cliente_id = $x_cliente_ida";
	}else{


$sSqlWrk = "SELECT cliente.usuario_id, cliente.sexo, cliente.nombre_completo as cliente_nombre, cliente.apellido_paterno, cliente.apellido_materno FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id Where credito.credito_id = $x_credito_id ";
	}
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	if($rowwrk["sexo"] == 1){
		$x_saludo = "Sr.";
	}else{
		$x_saludo = "Sra.";	
	}
	$x_titular = $x_saludo." ".$rowwrk["cliente_nombre"]." ".$rowwrk["apellido_paterno"]." ".$rowwrk["apellido_materno"];	
	#echo "cliente ".$x_titular."<br>";					
	$x_usuario_id = $rowwrk["usuario_id"];
}else{
	$x_titular = "";					
	$x_usuario_id = "";						
}
@phpmkr_free_result($rswrk);

//



//AVAL
$sSql = "SELECT * FROM aval where solicitud_id = $x_solicitud_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
if($row["sexo"] == 1){
	$x_saludo = "Sr.";
}else{
	$x_saludo = "Sra.";	
}

//$x_aval = $row["nombre_completo"];
  $x_nombre_aval = $row["nombre_completo"]." ".$row["apellido_paterno"]." ".$row["apellido_materno"];
	$x_aval = $x_saludo." ". $x_nombre_aval;
	#echo "el aval es".$x_aval."<br>";						

phpmkr_free_result($rs);


	
if(strlen ($x_nombre_aval)< 5 ){
	//buscamos el aval en las otras tablas donde puede estar si es que ya no esta en la tabal vieja
	#los datos del aval estan en la tabla datos_aval; por el cambio del formto en la solitud del aval
	
	
	$sSql = " SELECT * FROM  datos_aval WHERE solicitud_id = $x_solicitud_id";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	if($row["sexo"] == 1){
		$x_saludo = "Sr.";
	}else{
		$x_saludo = "Sra.";	
	}
  $x_nombre_aval = $row["nombre_completo"]." ".$row["apellido_paterno"]." ".$row["apellido_materno"];
//$x_aval = $row["nombre_completo"];
	$x_aval = $x_saludo." ". $x_nombre_aval;	
	}
#echo "nombre aval ". $x_nombre_aval."<br>";



//fecha limite pago (actual + 8 dias
$temptime = strtotime(ConvertDateToMysqlFormat($currdate_original));	
$temptime = DateAdd('w',8,$temptime);
$fecha_act = strftime('%Y-%m-%d',$temptime);			
$x_dia = strftime('%A',$temptime);
//Validar domingos
if($x_dia == "SUNDAY"){
	$temptime = strtotime($fecha_act);
	$temptime = DateAdd('w',1,$temptime);
	$fecha_act = strftime('%Y-%m-%d',$temptime);
}
$fecha_act = FechaLetras_normal(strtotime(ConvertDateToMysqlFormat($fecha_act)),false);


//vencimiento
$sSqlWrk = "SELECT *, TO_DAYS('".ConvertDateToMysqlFormat($currdate_original)."') - TO_DAYS(vencimiento.fecha_vencimiento) as dias_venc FROM vencimiento where (credito_id = $x_credito_id) AND (vencimiento.vencimiento_status_id = 3)";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);


$x_total_importe = 0;
$x_total_interes = 0;
$x_total_moratorios = 0;
$x_total_total = 0;
$x_dias_venc_ant = 0;
$x_contador = 0;
$x_iva_mor = 0;
$x_iva = 0;
$x_total_2  = 0;
$x_total  = 0;

while($rowwrk = phpmkr_fetch_array($rswrk)) {
	$x_importe = $rowwrk["importe"];
	$x_interes = $rowwrk["interes"];
	$x_interes_moratorio = $rowwrk["interes_moratorio"];
	$x_dias_venc = $rowwrk["dias_venc"];	
	$x_iva_mor = $rowwrk["iva_mor"];
	$x_iva = $rowwrk["iva"];
	$x_total_venc = $rowwrk["total_venc"];
	
	
	if($x_dias_venc > $x_dias_venc_ant){
		$x_dias_venc_ant = $x_dias_venc;
		$x_fecha_venc = $rowwrk["fecha_vencimiento"];				
	}
	$x_total_importe = $x_total_importe + $x_importe;
	$x_total_interes = $x_total_interes + $x_interes;
	$x_total_moratorios = $x_total_moratorios + $x_interes_moratorio;
	$x_total_iva_mor = $x_total_iva_mor + $x_iva_mor;
	$x_total_iva = $x_total_iva + $x_iva;
	
	$x_total = $x_importe + $x_interes + $x_iva + $x_interes_moratorio + $x_iva_mor;
	
	$x_total_total = $x_total_total + $x_total ;
	$x_total_2 = $x_total_2 + $x_total_venc ;
	$x_contador++;
}
@phpmkr_free_result($rswrk);

//buscamos el vencimeito siguiente
$sqlVS = "SELECT * FROM vencimiento WHERE vencimiento.vencimiento_status_id = 1 and credito_id = $x_credito_id ORDER BY vencimiento.vencimiento_num ASC LIMIT 0,1 ";
$RSvs = phpmkr_query($sqlVS,$conn)or die ("Error al seleccionar el vencimeinto siguiente".phpmkr_error()."sql".$sqlVS);
$rowVS = phpmkr_fetch_array($RSvs);
$x_fecha_ven_si = $rowVS["fecha_vencimiento"];
$x_total_venci_sig =$rowVS["total_venc"];
$x_total_venci_sig_letras = covertirNumLetras($x_total_venci_sig);
$x_monto_vencimiento_siguiente = "$".FormatNumber($x_total_venci_sig,2,0,0,1);

$x_fecha_venc = FechaLetras_normal(strtotime(ConvertDateToMysqlFormat($x_fecha_venc)),false);
$x_fecha_vencimiento_siguiente = FechaLetras_normal(strtotime(ConvertDateToMysqlFormat($x_fecha_ven_si)),false);


$x_moratorio_letras = covertirNumLetras($x_total_moratorios);
$x_moratorios = "$".FormatNumber($x_total_moratorios,2,0,0,1)." (-$x_moratorio_letras-) ";

$x_total_letras = covertirNumLetras($x_total_total);
$x_total_pagar = "$".FormatNumber($x_total_total,2,0,0,1);

$x_saldo_vencido  = $x_total_pagar;
//$x_total_letras = covertirNumLetras($x_total_2);
//$x_total_pagar = "$".FormatNumber($x_total_2,2,0,0,1)." (-$x_total_letras-) ";


//pagos vigentes pendientes
$sSql = "SELECT count(*) as vig_pen FROM vencimiento where credito_id = $x_credito_id and vencimiento_status_id = 1";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_vigentes = $row["vig_pen"];
phpmkr_free_result($rs);

if($x_vigentes > 0){
	$x_parrafo_1 = "";
	$x_parrafo_2 = " para ponerse al corriente es de ".$x_total_pagar.".";	
}else{
	$x_parrafo_1 = "Le recordamos que el cr&eacute;dito se encuentra totalmente vencido. ";# desde el ".$fecha_act.".";
	$x_parrafo_2 = " para liquidar su deuda es de ".$x_total_pagar.".";		
}


//FORMATO
$sSql = "select contenido from formato_docto where formato_docto_id = $x_formato";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_contenido = $row["contenido"];
phpmkr_free_result($rs);

$currdate = date("Y-m-d");

$x_fecha_actual  = FechaLetras_normal(strtotime(ConvertDateToMysqlFormat($currdate)),false);
//$x_fecha_actual = FechaLetras_normal(strtotime(ConvertDateToMysqlFormat($x_fecha_hoy)),false);

$x_hoy = date("Y-m-d");
$sqlDV = "select DATE_ADD(\"$x_hoy\",INTERVAL 10 DAY) as dia_valido";
#echo "sql".$sqlDV." ";
$rsDV = phpmkr_query($sqlDV,$conn)or die("Erro al sle dia val".phpmkr_error());
$rowDV = phpmkr_fetch_array($rsDV);
$x_dia_valido = $rowDV["dia_valido"];
#echo "dia valido".$x_dia_valido."<br>";

$x_fecha_valida =  FechaLetras_normal(strtotime($x_dia_valido),false);

$x_contenido = str_replace("\$x_fecha_actual",$x_fecha_actual,$x_contenido);
$x_contenido = str_replace("\$x_aval",htmlentities($x_aval),$x_contenido);
$x_contenido = str_replace("\$x_titular",htmlentities($x_titular),$x_contenido);
$x_contenido = str_replace("\$x_fecha_otorgamiento",$x_fecha_otorgamiento,$x_contenido);
$x_contenido = str_replace("\$x_parrafo_1",$x_parrafo_1,$x_contenido);
$x_contenido = str_replace("\$x_parrafo_2",$x_parrafo_2,$x_contenido);
$x_contenido = str_replace("\$x_fecha_limite_pago",$fecha_act,$x_contenido);
$x_contenido = str_replace("\$x_moratorios",$x_moratorios,$x_contenido);
$x_contenido = str_replace("\$x_tarjeta_num",htmlentities($x_tarjeta_numero),$x_contenido);
$x_contenido = str_replace("\$x_saldo_vencido",$x_saldo_vencido,$x_contenido);
$x_contenido = str_replace("\$x_nombre_grupo",$x_nombre_grupo,$x_contenido);
$x_contenido = str_replace("\$x_monto_vencimiento_siguiente",$x_monto_vencimiento_siguiente,$x_contenido);
$x_contenido = str_replace("\$x_fecha_vencimiento_siguiente",$x_fecha_vencimiento_siguiente,$x_contenido);
//echo "formato".$x_formato ."<br>";

$x_contenido = str_replace("\$x_usuario",$x_usuario,$x_contenido);
$x_contenido = str_replace("\$x_clave",$x_clave,$x_contenido);
#echo "nombre aval el loas data ".$x_nombre_aval ."--------";
if(strlen($x_nombre_aval) < 5){
	
if(($x_formato == 48) || ($x_formato == 53) ){
	$x_contenido ="";
	}
	
	}	
	return $x_contenido;
	
	}
	
function loadDataCarta1Aval($conn, $credito_id, $x_formato){
	
	//CREDITO
	$x_credito_id = $credito_id;
	$sSql = "SELECT * FROM credito where credito_id = $x_credito_id";
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$row = phpmkr_fetch_array($rs);
	$x_solicitud_id = $row["solicitud_id"];
	$x_tarjeta_numero = $row["tarjeta_num"];
	$x_fecha_otorgamiento = $row["fecha_otrogamiento"];
	phpmkr_free_result($rs);
	$x_fecha_otorgamiento = FechaLetras_normal(strtotime($x_fecha_otorgamiento),false);

//CLIENTE
$sSqlWrk = "SELECT cliente.usuario_id, cliente.sexo, cliente.nombre_completo as cliente_nombre, cliente.apellido_paterno, cliente.apellido_materno FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id Where credito.credito_id = $x_credito_id ";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	if($rowwrk["sexo"] == 1){
		$x_saludo = "Sr.";
	}else{
		$x_saludo = "Sra.";	
	}
	$x_titular = $x_saludo." ".$rowwrk["cliente_nombre"]." ".$rowwrk["apellido_paterno"]." ".$rowwrk["apellido_materno"];						
	$x_usuario_id = $rowwrk["usuario_id"];
}else{
	$x_titular = "";					
	$x_usuario_id = "";						
}
@phpmkr_free_result($rswrk);

//AVAL
$sSql = "SELECT * FROM aval where solicitud_id = $x_solicitud_id";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
if($row["sexo"] == 1){
	$x_saludo = "Sr.";
}else{
	$x_saludo = "Sra.";	
}

//$x_aval = $row["nombre_completo"];

	$x_aval = $x_saludo." ".$row["nombre_completo"]." ".$row["apellido_paterno"]." ".$row["apellido_materno"];						

phpmkr_free_result($rs);




//fecha limite pago (actual + 8 dias
$temptime = strtotime(ConvertDateToMysqlFormat($currdate_original));	
$temptime = DateAdd('w',8,$temptime);
$fecha_act = strftime('%Y-%m-%d',$temptime);			
$x_dia = strftime('%A',$temptime);
//Validar domingos
if($x_dia == "SUNDAY"){
	$temptime = strtotime($fecha_act);
	$temptime = DateAdd('w',1,$temptime);
	$fecha_act = strftime('%Y-%m-%d',$temptime);
}
$fecha_act = FechaLetras_normal(strtotime(ConvertDateToMysqlFormat($fecha_act)),false);


//vencimiento
$sSqlWrk = "SELECT *, TO_DAYS('".ConvertDateToMysqlFormat($currdate_original)."') - TO_DAYS(vencimiento.fecha_vencimiento) as dias_venc FROM vencimiento where (credito_id = $x_credito_id) AND (vencimiento.vencimiento_status_id = 3)";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);


$x_total_importe = 0;
$x_total_interes = 0;
$x_total_moratorios = 0;
$x_total_total = 0;
$x_dias_venc_ant = 0;
$x_contador = 0;
$x_iva_mor = 0;
$x_iva = 0;
$x_total_2  = 0;
$x_total  = 0;

while($rowwrk = phpmkr_fetch_array($rswrk)) {
	$x_importe = $rowwrk["importe"];
	$x_interes = $rowwrk["interes"];
	$x_interes_moratorio = $rowwrk["interes_moratorio"];
	$x_dias_venc = $rowwrk["dias_venc"];	
	$x_iva_mor = $rowwrk["iva_mor"];
	$x_iva = $rowwrk["iva"];
	$x_total_venc = $rowwrk["total_venc"];
	
	
	if($x_dias_venc > $x_dias_venc_ant){
		$x_dias_venc_ant = $x_dias_venc;
		$x_fecha_venc = $rowwrk["fecha_vencimiento"];				
	}
	$x_total_importe = $x_total_importe + $x_importe;
	$x_total_interes = $x_total_interes + $x_interes;
	$x_total_moratorios = $x_total_moratorios + $x_interes_moratorio;
	$x_total_iva_mor = $x_total_iva_mor + $x_iva_mor;
	$x_total_iva = $x_total_iva + $x_iva;
	
	$x_total = $x_importe + $x_interes + $x_iva + $x_interes_moratorio + $x_iva_mor;
	
	$x_total_total = $x_total_total + $x_total ;
	$x_total_2 = $x_total_2 + $x_total_venc ;
	$x_contador++;
}
@phpmkr_free_result($rswrk);

$x_fecha_venc = FechaLetras_normal(strtotime(ConvertDateToMysqlFormat($x_fecha_venc)),false);;

$x_moratorio_letras = covertirNumLetras($x_total_moratorios);
$x_moratorios = "$".FormatNumber($x_total_moratorios,2,0,0,1)." (-$x_moratorio_letras-) ";

$x_total_letras = covertirNumLetras($x_total_total);
$x_total_pagar = "$".FormatNumber($x_total_total,2,0,0,1)." (-$x_total_letras-) ";


//$x_total_letras = covertirNumLetras($x_total_2);
//$x_total_pagar = "$".FormatNumber($x_total_2,2,0,0,1)." (-$x_total_letras-) ";


//pagos vigentes pendientes
$sSql = "SELECT count(*) as vig_pen FROM vencimiento where credito_id = $x_credito_id and vencimiento_status_id = 1";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_vigentes = $row["vig_pen"];
phpmkr_free_result($rs);

if($x_vigentes > 0){
	$x_parrafo_1 = "";
	$x_parrafo_2 = " para ponerse al corriente es de ".$x_total_pagar.".";	
}else{
	$x_parrafo_1 = "Le recordamos que el cr&eacute;dito se encuentra totalmente vencido. ";# desde el ".$fecha_act.".";
	$x_parrafo_2 = " para liquidar su deuda es de ".$x_total_pagar.".";		
}


//FORMATO
$sSql = "select contenido from formato_docto where formato_docto_id = $x_formato";
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
$x_contenido = $row["contenido"];
phpmkr_free_result($rs);

$x_contenido = str_replace("\$x_fecha_actual",$currdate,$x_contenido);
$x_contenido = str_replace("\$x_aval",htmlentities($x_aval),$x_contenido);
$x_contenido = str_replace("\$x_titular",htmlentities($x_titular),$x_contenido);
$x_contenido = str_replace("\$x_fecha_otorgamiento",$x_fecha_otorgamiento,$x_contenido);
$x_contenido = str_replace("\$x_parrafo_1",$x_parrafo_1,$x_contenido);
$x_contenido = str_replace("\$x_parrafo_2",$x_parrafo_2,$x_contenido);
$x_contenido = str_replace("\$x_fecha_limite_pago",$fecha_act,$x_contenido);
$x_contenido = str_replace("\$x_moratorios",$x_moratorios,$x_contenido);
$x_contenido = str_replace("\$x_tarjeta_num",htmlentities($x_tarjeta_numero),$x_contenido);

$x_contenido = str_replace("\$x_usuario",$x_usuario,$x_contenido);
$x_contenido = str_replace("\$x_clave",$x_clave,$x_contenido);
	
	return $x_contenido;
	
	
	}	
	
	
	
function telefonos($conn, $x_cliente_id){
	
	$x_c_id = $x_cliente_id;
	$x_count_2 = 1;
		$sSql = "select * from  telefono where cliente_id = $x_c_id  AND telefono_tipo_id = 1 order by telefono_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9 = phpmkr_fetch_array($rs9)){			
			$GLOBALS["x_telefono_casa_$x_count_2"] = $row9["numero"];
			$GLOBALS["x_comentario_casa_$x_count_2"] = $row9["comentario"];
			$GLOBALS["contador_telefono"] = $x_count_2;
			$x_count_2++;
			
		}
		
	//	echo "telefonos". $GLOBALS["x_telefono_casa_1"].$GLOBALS["x_telefono_casa_2"]; 



		$x_count_3 = 1;
		$sSql = "select * from  telefono where cliente_id = $x_c_id  AND telefono_tipo_id = 2 order by telefono_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9e = phpmkr_fetch_array($rs9)){			
			$GLOBALS["x_telefono_celular_$x_count_3"] = $row9e["numero"];
			$GLOBALS["x_comentario_celular_$x_count_3"] = $row9e["comentario"];
			$GLOBALS["x_compania_celular_$x_count_3"] = $row9e["compania_id"];	
			$GLOBALS["contador_celular"] = $x_count_3;
			$x_count_3++;
			
		}
		
			//echo "telefonos". $GLOBALS["x_telefono_celular_1"].$GLOBALS["x_telefono_celular_2"]; 
}

function cargaIntegrantes($conn, $x_sol_id){
	$x_soli_id = $x_sol_id;
	//echo "load data solidario";
			$sqlGrupo = "SELECT * FROM creditosolidario WHERE  solicitud_id = $x_soli_id";
			//echo "sql solidario".$sqlGrupo."<br>";
			$responseGrupo = phpmkr_query($sqlGrupo,$conn) or die ("error al ejecutar query grupo".phpmkr_error()."sql: ".$sqlGrupo);
			$rowGrupo = phpmkr_fetch_array($responseGrupo);
			$GLOBALS["x_creditoSolidario_id"] =  $rowGrupo["creditoSolidario_id"];
			$GLOBALS["x_nombre_grupo"] = $rowGrupo["nombre_grupo"];
			//echo $GLOBALS["x_nombre_grupo"]."..";
			$x_cont_g = 1;
			while($x_cont_g <= 10){
				
				$GLOBALS["x_integrante_$x_cont_g"] = $rowGrupo["integrante_$x_cont_g"];
				$GLOBLAS["x_nombre_integrante_$x_cont_g"] = $rowGrupo["integrante_$x_cont_g"];
				 
				//$x_monto_i =  $rowGrupo["monto_$x_cont_g"];
				//$GLOBALS["x_monto_$x_cont_g"] = number_format($x_monto_i);
				
				//echo "integrante".$rowGrupo["integrante_$x_cont_g"]."<br>";
				$GLOBALS["x_monto_$x_cont_g"] =  $rowGrupo["monto_$x_cont_g"];
				$GLOBALS["x_rol_integrante_$x_cont_g"] = $rowGrupo["rol_integrante_$x_cont_g"]; 
				$GLOBALS["x_cliente_id_$x_cont_g"] = $rowGrupo["cliente_id_$x_cont_g"];
				echo "cliente id".$GLOBALS["x_cliente_id_$x_cont_g"]." grupo <br>";
				$grupoId[]= $GLOBALS["x_cliente_id_$x_cont_g"];
				
				foreach( $grupoId as $valos){
					echo "arreglo".$valos;
					}
				//BUSCO AL REPRESENTANTE DEL GRUPO
				if($GLOBALS["x_rol_integrante_$x_cont_g"] == 1){
					$GLOBALS["$x_representate_grupo"] = $rowGrupo["integrante_$x_cont_g"];
					$GLOBALS["x_representante_cliente_id"] =  $rowGrupo["cliente_id_$x_cont_g"];
					}
					
					
					
					
				
				$x_cont_g++;
				}
			
			
			
			phpmkr_free_result($rowGrupo);
	
	
	
	
	}

function LoadDataEdoCuenta($conn, $x_credito_id ){


$x_solcre_id =$x_credito_id;
//CREDITO

$x_tabla_fina = "";
	$x_respuesta = "";
	$x_respuesta1 = "";
	$x_respuesta2 = "";
	$x_respuesta3 = "";
	$x_respuesta4 = "";
	$x_respuesta5 = "";
	$x_respuesta6 = "";
	$x_respuesta7 = "";
	$x_respuesta8 = "";
	$x_respuesta9 = "";
	$x_respuesta10 = "";
	$x_respuesta11 = "";
	$x_respuesta12 = "";
	$x_respuesta13 = "";
	$x_respuesta14 = "";
	$x_respuesta15 = "";
	$x_respuesta16 = "";
	$x_respuesta17 = "";
	$x_respuesta18 = "";
	$x_respuesta19 = "";
	$x_respuesta20 = "";
	$x_respuesta21 = "";
	$x_respuesta26 = "";

$x_respuesta = "";
$sSql = "select * from credito where credito_id = ".$x_solcre_id;
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row = phpmkr_fetch_array($rs);
	$GLOBALS["x_credito_id"] = $row["credito_id"];
	$x_credito_num = $row["credito_num"];		
	$x_cliente_num = $row["cliente_num"];				
	$x_credito_tipo_id = $row["credito_tipo_id"];
	$GLOBALS["x_credito_tipo_id"] = $row["credito_tipo_id"];
	$GLOBALS["x_solicitud_id"] = $row["solicitud_id"];
	$x_solicitud_id = $row["solicitud_id"];
	$x_credito_status_id = $row["credito_status_id"];
	$x_fecha_otrogamiento = $row["fecha_otrogamiento"];
	$x_importe = $row["importe"];
	$x_tasa = $row["tasa"];
	$x_plazo = $row["plazo_id"];
	$x_fecha_vencimiento = $row["fecha_vencimiento"];
	$x_tasa_moratoria = $row["tasa_moratoria"];
	$x_medio_pago_id= $row["medio_pago_id"];
	$x_banco_id = $row["banco_id"];		
	$x_referencia_pago = $row["referencia_pago"];
	$x_forma_pago_id = $row["forma_pago_id"];	
	$GLOBALS["x_forma_pago_id"] = $row["forma_pago_id"];	
	$x_num_pagos= $row["num_pagos"];				
	$x_tdp = $row["tarjeta_num"];		
	$GLOBALS["credito_tipo_id"] = $row["credito_tipo_id"];
	$credito_tipo_id = $row["credito_tipo_id"];
	if ($GLOBALS["credito_tipo_id"] == 1){
		$x_tipo_credito = " Individual";
		}else {
			$x_tipo_credito = " Solidario";
			}
	
	
					
// seleccionamos los comentarios..

$sSqlcomentarios = "SELECT * FROM `credito_comment` where credito_id = ".$GLOBALS["x_credito_id"]."";
$rsComentarios = phpmkr_query($sSqlcomentarios, $conn) or die ("Error al seleccionar la bitacora".phpmkr_error()."sql:".$sSqlcomentarios);
$rowComentarios = phpmkr_fetch_array($rsComentarios);
//$GLOBALS["x_credito_id"] = $row["credito_id"];
$x_comentario_int = "";
$x_comentario_ext = "";
$x_comentario_int = $rowComentarios["comentario_int"];
$x_comentario_ext = $rowComentarios["comentario_ext"];


//Cuenta
$sSql = "select cliente.* from cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id join solicitud on solicitud.solicitud_id = solicitud_cliente.solicitud_id 
where solicitud.solicitud_id = ".$GLOBALS["x_solicitud_id"];
$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row2 = phpmkr_fetch_array($rs2);
	$GLOBALS["x_cuenta"] = $row2["nombre_completo"]." ".$row2["apellido_paterno"]." ".$row2["apellido_materno"];
	$x_cuenta = $row2["nombre_completo"]." ".$row2["apellido_paterno"]." ".$row2["apellido_materno"];
	$x_c_id = $row2["cliente_id"];

// telefonos
/*if(!empty( $x_c_id)){
 telefonos($conn, $x_c_id);
}*/
$x_count_2 = 1;
if(!empty($x_c_id)){
		$sSql = "select * from  telefono where cliente_id = $x_c_id  AND telefono_tipo_id = 1 order by telefono_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9 = phpmkr_fetch_array($rs9)){			
			$GLOBALS["x_telefono_casa_$x_count_2"] = $row9["numero"];
			$x_telefono_casa[] =$row9["numero"];
			$GLOBALS["x_comentario_casa_$x_count_2"] = $row9["comentario"];
			$GLOBALS["contador_telefono"] = $x_count_2;
			$x_count_2++;
			
		}
}
		



		$x_count_3 = 1;
		if(!empty($x_c_id)){
		$sSql = "select * from  telefono where cliente_id = $x_c_id  AND telefono_tipo_id = 2 order by telefono_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9e = phpmkr_fetch_array($rs9)){			
			$GLOBALS["x_telefono_celular_$x_count_3"] = $row9e["numero"];
			$x_telefono_celular[] = $row9e["numero"];
			$GLOBALS["x_comentario_celular_$x_count_3"] = $row9e["comentario"];
			$GLOBALS["x_compania_celular_$x_count_3"] = $row9e["compania_id"];	
			$GLOBALS["contador_celular"] = $x_count_3;
			$x_count_3++;
			
		}
		}

// direcciones

if(!empty($x_c_id)){
#personal domicilio
$sqlD = "SELECT * FROM direccion WHERE cliente_id = $x_c_id and direccion_tipo_id = 1 order by direccion_id desc limit 1";		
		$sSql2 = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where cliente_id = $x_c_id and direccion_tipo_id = 1 order by direccion_id desc limit 1";
		$rs3 = phpmkr_query($sqlD,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sqlD);
		$row3 = phpmkr_fetch_array($rs3);
		$GLOBALS["x_direccion_id"] = $row3["direccion_id"];		
		$GLOBALS["x_calle_domicilio"] = $row3["calle"];
		$GLOBALS["x_colonia_domicilio"] = $row3["colonia"];
		$GLOBALS["x_delegacion_id"] = $row3["delegacion_id"];
		$GLOBALS["x_localidad_id"] = $row3["localidad_id"];
		$GLOBALS["x_propietario"] = $row3["propietario"];
		$GLOBALS["x_entidad_domicilio"] = $row3["entidad"];
		$GLOBALS["x_codigo_postal_domicilio"] = $row3["codigo_postal"];
		$GLOBALS["x_ubicacion_domicilio"] = $row3["ubicacion"];	
		$GLOBALS["x_numero_exterior"] = $row3["numero_exterior"];
		$x_telefono_casa[] = $row3["telefono"];
		$x_telefono_casa[] = $row3["telefono_secundario"];
		$x_telefono_celular[] = $row3["telefono_movil"];
		
		if (!empty($GLOBALS["x_delegacion_id"])){
		$sqlDel = "SELECT * FROM `delegacion` WHERE `delegacion_id` = ".$GLOBALS["x_delegacion_id"]." ";
		$rsDesl = phpmkr_query($sqlDel, $conn) or die ("Error al seleccionar los del".phpmkr_error()."sql:". $sqlDel);
		$rowDel = phpmkr_fetch_array($rsDesl);
		}
		$x_delegacion = $rowDel["descripcion"];
		
		$x_direccion_dom_th = "Calle : ".$GLOBALS["x_calle_domicilio"]." ". $GLOBALS["x_numero_exterior"].", colonia: ".$GLOBALS["x_colonia_domicilio"].", localidad :".$GLOBALS["x_localidad_id"].", delegacion: ".$x_delegacion.", C.P.: ".$GLOBALS["x_codigo_postal_domicilio"]; 
$x_delegacion = "";
		//falta telefono secundario
#direccion negocio
		$sqlD = "SELECT * FROM direccion WHERE cliente_id = $x_c_id and direccion_tipo_id = 2 order by direccion_id desc limit 1";	
		$sSql = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where cliente_id = $x_c_id  and direccion_tipo_id = 2 order by direccion_id desc limit 1";
		$rs4 = phpmkr_query($sqlD,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row4 = phpmkr_fetch_array($rs4);
		
			$GLOBALS["x_direccion_id2"] = $row4["direccion_id"];
			$GLOBALS["x_giro_negocio"] = $row4["giro_negocio"];
			$GLOBALS["x_calle_negocio"] = $row4["calle"];
			$GLOBALS["x_colonia_negocio"] = $row4["colonia"];
			$GLOBALS["x_entidad_negocio"] = $row4["entidad"];
			$GLOBALS["x_ubicacion_negocio"] = $row4["ubicacion"];
			$GLOBALS["x_codigo_postal_negocio"] = $row4["codigo_postal"];			
			$GLOBALS["x_delegacion_id2"] = $row4["delegacion_id"];
			$GLOBALS["x_localidad_id2"] = $row4["localidad_id"];
			$GLOBALS["x_numero_exterior2"] = $row4["numero_exterior"];
			$x_telefono_casa[] = $row3["telefono"];
			$x_telefono_casa[] = $row3["telefono_secundario"];
			$x_telefono_celular[] = $row3["telefono_movil"];
			
			
			 if(!empty($GLOBALS["x_delegacion_id2"])){
				$sqlDel = "SELECT * FROM `delegacion` WHERE `delegacion_id` = ".$GLOBALS["x_delegacion_id2"]." ";
		$rsDesl = phpmkr_query($sqlDel, $conn) or die ("Error al seleccionar los del".phpmkr_error()."sql:". $sqlDel);
		$rowDel = phpmkr_fetch_array($rsDesl);
			 }
		$x_delegacion1 = $rowDel["descripcion"];
				$x_direccion_neg_th = "Calle : ".$GLOBALS["x_calle_negocio"]." ".$GLOBALS["x_numero_exterior2"].", colonia: ".$GLOBALS["x_colonia_negocio"].", localidad :".$GLOBALS["x_localidad_id2"].", delegacion: ".$x_delegacion1.", C.P.: ".$GLOBALS["x_codigo_postal_negocio"]; 
				
		$x_delegacion= "";		
		
		
}

## direccion aval
$sSqlWrka = "SELECT * FROM datos_aval Where solicitud_id = ".$GLOBALS["x_solicitud_id"] ."  group by datos_aval_id";
	$rswrka = phpmkr_query($sSqlWrka,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrka);
	#echo $sSqlWrka;
	$rowwrka = phpmkr_fetch_array($rswrka);
	$x_aval = $rowwrka["aval"];
	$x_datos_aval_id = $rowwrka["datos_aval_id"];
	#echo $x_datos_aval_id."aid";
	@phpmkr_free_result($rswrk);
	    $GLOBALS["x_nombre_completo"] = $rowwrka["nombre_completo"];
		$GLOBALS["x_apellido_paterno"] = $rowwrka["apellido_paterno"];
		$GLOBALS["x_apellido_materno"] = $rowwrka["apellido_materno"];
		$GLOBALS["x_nombre_avala"]= " ". $GLOBALS["x_nombre_completo"] ." ".$GLOBALS["x_apellido_paterno"]." ".$GLOBALS["x_apellido_materno"]." ";
		$x_nombre_aval= " ". $GLOBALS["x_nombre_completo"] ." ".$GLOBALS["x_apellido_paterno"]." ".$GLOBALS["x_apellido_materno"]." ";
		$GLOBALS["x_rfc"] = $rowwrka["rfc"];
		$GLOBALS["x_curp"] = $rowwrka["curp"];
		$GLOBALS["x_calle"] = $rowwrka["calle"];
		$GLOBALS["x_colonia"] = $rowwrka["colonia"];
		$GLOBALS["x_entidad"] = $rowwrka["entidad"];
		$GLOBALS["x_codigo_postal"] = $rowwrka["codigo_postal"];
		$GLOBALS["x_delegacion"] = $rowwrka["delegacion"];
		$GLOBALS["x_vivienda_tipo"] = $rowwrka["vivienda_tipo"];
		$GLOBALS["x_telefono_p"] = $rowwrka["telefono_p"];
		$GLOBALS["x_telefono_c"] = $rowwrka["telefono_c"];
		$GLOBALS["x_telefono_o"] = $rowwrka["telefono_o"];
		$GLOBALS["x_antiguedad_v"] = $rowwrka["antiguedad_v"];
		$GLOBALS["x_tel_arrendatario_v"] = $rowwrka["tel_arrendatario_v"];
		$GLOBALS["x_renta_mensual_v"] = $rowwrka["renta_mensual_v"];
		$GLOBALS["x_calle_2"] = $rowwrka["calle_2"];
		$GLOBALS["x_colonia_2"] = $rowwrka["colonia_2"];
		$GLOBALS["x_entidad_2"] = $rowwrka["entidad_2"];
		$GLOBALS["x_codigo_postal_2"] = $rowwrka["codigo_postal_2"];
		$GLOBALS["x_delegacion_2"] = $rowwrka["delegacion_2"];
		
		if(!empty($GLOBALS["x_delegacion"])) {
		$sqlDel = "SELECT * FROM `delegacion` WHERE `delegacion_id` = ".$GLOBALS["x_delegacion"]." ";
		$rsDesl = phpmkr_query($sqlDel, $conn) or die ("Error al seleccionar los del".phpmkr_error()."sql:". $sqlDel);
		$rowDel = phpmkr_fetch_array($rsDesl);
		}
		$x_delegacion2 = $rowDel["descripcion"];
		
		$x_direccion_dom_av = "Calle : ".$GLOBALS["x_calle"].", colonia: ".$GLOBALS["x_colonia"].", delegacion: ".$x_delegacion2 ;
		$x_delegacion = "";
		if(!empty($GLOBALS["x_delegacion_2"])){
		$sqlDel = "SELECT * FROM `delegacion` WHERE `delegacion_id` = ".$GLOBALS["x_delegacion_2"]." ";
		$rsDesl = phpmkr_query($sqlDel, $conn) or die ("Error al seleccionar los del".phpmkr_error()."sql:". $sqlDel);
		$rowDel = phpmkr_fetch_array($rsDesl);
		}
		$x_delegacion3 = $rowDel["descripcion"]; 
		$x_direccion_neg_av = "Calle : ".$GLOBALS["x_calle_2"].", colonia: ".$GLOBALS["x_colonia_2"].", delegacion: ".$x_delegacion3; 
$x_delegacion = "";


if (empty($x_datos_aval_id)){
	// se buscan los datos del avla en la tabla anterior

	$sqlAval	 = "SELECT * FROM aval	WHERE solicitud_id = $x_solicitud_id ";
	$rsAval = phpmkr_query($sqlAval, $conn)  or die ("Error al seleccionar los adtos del aval de la tabla prima".phpmkr_error()."sql:".$sqlAval);
	$rowAval = phpmkr_fetch_array($rsAval);
	$x_aval_id = $rowAval["aval_id"];
	$x_nombre_avala =" ".$rowAval["nombre_completo"]. " ". $rowAval["apellido_paterno"]." ".$rowAval["apellido_materno"];
	
	//echo "nombre aval".$x_nombre_aval;
	if(!empty($x_aval_id)){
	//seleccionamos las direcciones de los avlaes de las tablas primarias
	$sqlD = "SELECT * FROM direccion WHERE aval_id = $x_aval_id and direccion_tipo_id = 3  order by direccion_id desc limit 1";	
	//echo "dir aval".$sqlD;	
		$sSql2 = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where aval_id = $x_aval_id and direccion_tipo_id = 1 order by direccion_id desc limit 1";
		$rs3 = phpmkr_query($sqlD,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sqlD);
		$row3 = phpmkr_fetch_array($rs3);
		$GLOBALS["x_direccion_id"] = $row3["direccion_id"];		
		$GLOBALS["x_calle_domicilio"] = $row3["calle"];
		$GLOBALS["x_colonia_domicilio"] = $row3["colonia"];
		$GLOBALS["x_delegacion_id"] = $row3["delegacion_id"];
		$GLOBALS["x_localidad_id"] = $row3["localidad_id"];
		$GLOBALS["x_propietario"] = $row3["propietario"];
		$GLOBALS["x_entidad_domicilio"] = $row3["entidad"];
		$GLOBALS["x_codigo_postal_domicilio"] = $row3["codigo_postal"];
		$GLOBALS["x_ubicacion_domicilio"] = $row3["ubicacion"];	
		$GLOBALS["x_numero_exterior"] = $row3["numero_exterior"];
		$x_telefono_casa_aval[] = $row3["telefono"];
		$x_telefono_casa_aval[] = $row3["telefono_secundario"];
		$x_telefono_casa_aval[] = $row3["telefono_movil"];
		
		//echo "aval".$row3["telefono"]." ".$row3["telefono_secundario"].$row3["telefono_movil"];
		
		
		if (!empty($GLOBALS["x_delegacion_id"])){
		$sqlDel = "SELECT * FROM `delegacion` WHERE `delegacion_id` = ".$GLOBALS["x_delegacion_id"]." ";
		$rsDesl = phpmkr_query($sqlDel, $conn) or die ("Error al seleccionar los del".phpmkr_error()."sql:". $sqlDel);
		$rowDel = phpmkr_fetch_array($rsDesl);
		}
		$x_delegacion = $rowDel["descripcion"];
		
		$x_direccion_dom_av = "Calle : ".$GLOBALS["x_calle_domicilio"]." ". $GLOBALS["x_numero_exterior"].", colonia: ".$GLOBALS["x_colonia_domicilio"].", localidad :".$GLOBALS["x_localidad_id"].", delegacion: ".$x_delegacion.", C.P.: ".$GLOBALS["x_codigo_postal_domicilio"]; 
$x_delegacion = "";
		//falta telefono secundario
		
	}
	
	}
//promotor

$sSql = "select promotor.nombre_completo from promotor join solicitud on solicitud.promotor_id = promotor.promotor_id where solicitud.solicitud_id = ".$GLOBALS["x_solicitud_id"];
$rs3 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row3 = phpmkr_fetch_array($rs3);
$x_promotor = $row3["nombre_completo"];


//Pagos Vencidos
$sSql = "select count(*) as vencidos from vencimiento
where credito_id = ".$GLOBALS["x_credito_id"]." and vencimiento_status_id = 3 ";
$rs4 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row4 = phpmkr_fetch_array($rs4);
	$GLOBALS["x_pagos_vencidos"] = $row4["vencidos"];
	
	
	
	
	//INTEGRANTES DEL GRUPO
		$x_soli_id =  $GLOBALS["x_solicitud_id"];
		if($GLOBALS["x_credito_tipo_id"] == 2){
		//	cargaIntegrantes($conn, $x_soli_id);
			// ES UN CREDITO SOLIDARIO
			
			$sqlGrupo = "SELECT * FROM creditosolidario WHERE  solicitud_id = $x_soli_id";
			//echo "sql solidario".$sqlGrupo."<br>";
			$responseGrupo = phpmkr_query($sqlGrupo,$conn) or die ("error al ejecutar query grupo".phpmkr_error()."sql: ".$sqlGrupo);
			$rowGrupo = phpmkr_fetch_array($responseGrupo);
			$GLOBALS["x_creditoSolidario_id"] =  $rowGrupo["creditoSolidario_id"];
			$GLOBALS["x_nombre_grupo"] = $rowGrupo["nombre_grupo"];
			//echo $GLOBALS["x_nombre_grupo"]."..";
			$x_cont_g = 1;
			while($x_cont_g <= 10){
				
				$GLOBALS["x_integrante_$x_cont_g"] = $rowGrupo["integrante_$x_cont_g"];
				$GLOBLAS["x_nombre_integrante_$x_cont_g"] = $rowGrupo["integrante_$x_cont_g"];
				 
				//$x_monto_i =  $rowGrupo["monto_$x_cont_g"];
				//$GLOBALS["x_monto_$x_cont_g"] = number_format($x_monto_i);
				
				//echo "integrante".$rowGrupo["integrante_$x_cont_g"]."<br>";
				$GLOBALS["x_monto_$x_cont_g"] =  $rowGrupo["monto_$x_cont_g"];
				$GLOBALS["x_rol_integrante_$x_cont_g"] = $rowGrupo["rol_integrante_$x_cont_g"]; 
				$GLOBALS["x_cliente_id_$x_cont_g"] = $rowGrupo["cliente_id_$x_cont_g"];
				//echo "cliente id".$GLOBALS["x_cliente_id_$x_cont_g"]." grupo <br>";
				$grupoId[]= $GLOBALS["x_cliente_id_$x_cont_g"];
				
				
				//BUSCO AL REPRESENTANTE DEL GRUPO
				if($GLOBALS["x_rol_integrante_$x_cont_g"] == 1){
					$GLOBALS["$x_representate_grupo"] = $rowGrupo["integrante_$x_cont_g"];
					$GLOBALS["x_representante_cliente_id"] =  $rowGrupo["cliente_id_$x_cont_g"];
					}
					
					
					
					
				
				$x_cont_g++;
				}
			
			
			
			
			
			
			}


phpmkr_free_result($rs);
phpmkr_free_result($rs2);
phpmkr_free_result($rs3);
phpmkr_free_result($rs4);


$x_respuesta = '
<table align="center" width="700" border="0" cellspacing="0" cellpadding="0">
<tr>
<td>
  <img src="images/logo_arbol_completo.gif" width="115" height="93" />
  </td></tr>
</table>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="0" class="link_oculto">
<tr >
  <td colspan="5" class="txt_negro_medio" style=" border-bottom: solid 1px #C00; border-top: solid 1px #C00; padding-bottom: 5px; padding-top: 5px;" bgcolor="#ffffff">
  </td>
  </tr></table>
  
  <table align="center" width="700" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td class="txt_negro_medio" >

  </td>
  <td colspan="4" class="txt_datos_azul" align="center">Credito'.$x_tipo_credito.'</td>
</tr>
<tr>
  <td class="txt_negro_medio">Cuenta</td>
  <td colspan="4" class="txt_datos_azul"> '.$x_cuenta.'</td>
  </tr>
<tr>
  <td class="txt_negro_medio">Promotor:</td>
  <td colspan="4" class="txt_datos_azul">'. $x_promotor.'</td>
  </tr>
<tr>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
</tr>
<tr>
<td width="132" class="txt_negro_medio">Credito No.
</td>
<td width="108" class="txt_datos_azul">'.$x_credito_num.'</td>
<td width="41">&nbsp;</td>
<td width="106" class="txt_negro_medio">Status</td>
<td width="313" class="txt_datos_azul">';
  
if ((!is_null($x_credito_status_id)) && ($x_credito_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `credito_status`";
	$sTmp = $x_credito_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk11 .= " WHERE `credito_status_id` = " . $sTmp . "";
	$rswrk11 = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk11 && $rowwrk11 = phpmkr_fetch_array($rswrk11)) {
		$sTmp = $rowwrk11["descripcion"];
	}
	@phpmkr_free_result($rswrk11);
} else {
	$sTmp = "";
}
$ox_credito_status_id = $x_credito_status_id; // Backup Original Value
$x_credito_status_id = $sTmp;

  $x_respuesta .= " $x_credito_status_id";
   $x_credito_status_id = $ox_credito_status_id; // Restore Original Value 
  
  
   $x_respuesta .= "</td></tr>";
  
$x_respuesta .=  '<tr>
  <td class="txt_negro_medio"> Tarjeta Num.</td>
  <td class="txt_datos_azul">'.$x_tdp.'</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">Otorgado</td>
  <td class="txt_datos_azul">'. FormatDateTime($x_fecha_otrogamiento,7).'</td>
</tr>';
$x_respuesta .= '<tr>
  <td class="txt_negro_medio">Vencimiento</td>
  <td class="txt_datos_azul">'. FormatDateTime($x_fecha_vencimiento,7).'</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">Importe</td>
  <td class="txt_datos_azul">'.FormatNumber($x_importe,0,0,0,-2).'</td>
</tr>';
$x_respuesta .= '<tr>
  <td class="txt_negro_medio">Tasa</td>
  <td class="txt_datos_azul">'. FormatPercent(($x_tasa / 100),2,0,0,0).'</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">Num. pagos</td>
  <td class="txt_datos_azul">'.$x_num_pagos.'</td>
</tr>';
$x_respuesta .= '<tr>
  <td class="txt_negro_medio">Tasa Moratorios</td>
  <td class="txt_datos_azul">'.FormatPercent(($x_tasa_moratoria / 100),2,0,0,0).'</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">Forma de pago</td>
  <td class="txt_datos_azul">';
    
		$sSqlWrk = "SELECT descripcion FROM forma_pago where forma_pago_id = ".$GLOBALS["x_forma_pago_id"]."";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrk = phpmkr_fetch_array($rswrk);
	$x_respuesta .= '' .$datawrk["descripcion"].'';
		@phpmkr_free_result($rswrk);
		
  $x_respuesta .=  '</td>
</tr>';
$x_respuesta .= '<tr>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
</tr>
</table>';
 if( $x_credito_tipo_id == 2){
	//$x_respuesta .= "CHULMIS";
	 	//imprimimos la tabla de los integrantes
	//echo $x_tabla_integrantes;
	

	//$x_no_integrante = 1;
//while($x_no_integrante <= 10){
	 foreach($grupoId as $x_cliente_idG){
	
		$x_id_int =  $x_cliente_idG;
	//	echo "CLIENTE ID".$x_id_int."<BR>";

	if(!empty($x_id_int) && $x_id_int >0 ){
		//Buscamos las direcciones y los datos del clientre
		
		
		//Nombre
		//Cuenta
$sSql = "select cliente.* from cliente where cliente_id = $x_id_int ";
//echo $sSql;
$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row2 = phpmkr_fetch_array($rs2);
	$x_cuenta_g = $row2["nombre_completo"]." ".$row2["apellido_paterno"]." ".$row2["apellido_materno"];
		//echo $GLOBALS["x_cuenta"];
		unset($x_telefono_casa);
		unset($x_telefono_celular);
		if(!empty($x_id_int)){
		telefonos($conn,$x_id_int);
		}
		
		$x_respuesta .= '<table align="center" width="700" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td align="center" class="txt_negro_medio" style=" border-bottom: solid 1px #000">DATOS DEL CLIENTE:   '.$x_cuenta_g.'</td>
</tr>';
$x_count_2 = 1;
		$sSql = "select * from  telefono where cliente_id = $x_id_int  AND telefono_tipo_id = 1 order by telefono_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9 = phpmkr_fetch_array($rs9)){			
			$GLOBALS["x_telefono_casa_$x_count_2"] = $row9["numero"];
			$x_telefono_casa[] =$row9["numero"];
			$GLOBALS["x_comentario_casa_$x_count_2"] = $row9["comentario"];
			$GLOBALS["contador_telefono"] = $x_count_2;
			$x_count_2++;			
		}
		
		



		$x_count_3 = 1;
		$sSql = "select * from  telefono where cliente_id = $x_id_int  AND telefono_tipo_id = 2 order by telefono_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9e = phpmkr_fetch_array($rs9)){			
			$GLOBALS["x_telefono_celular_$x_count_3"] = $row9e["numero"];
			$x_telefono_celular[] =$row9e["numero"];
			$GLOBALS["x_comentario_celular_$x_count_3"] = $row9e["comentario"];
			$GLOBALS["x_compania_celular_$x_count_3"] = $row9e["compania_id"];	
			$GLOBALS["contador_celular"] = $x_count_3;
			$x_count_3++;
			
		}		
		
		#personal domicilio
$sqlD = "SELECT * FROM direccion WHERE cliente_id = $x_id_int and direccion_tipo_id = 1 order by direccion_id desc limit 1";		
		$sSql2 = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where cliente_id = $x_c_id and direccion_tipo_id = 1 order by direccion_id desc limit 1";
		$rs3 = phpmkr_query($sqlD,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sqlD);
		$row3 = phpmkr_fetch_array($rs3);
		$GLOBALS["x_direccion_id"] = $row3["direccion_id"];		
		$GLOBALS["x_calle_domicilio"] = $row3["calle"];
		$GLOBALS["x_colonia_domicilio"] = $row3["colonia"];
		$GLOBALS["x_delegacion_id"] = $row3["delegacion_id"];
		$GLOBALS["x_localidad_id"] = $row3["localidad_id"];
		$GLOBALS["x_propietario"] = $row3["propietario"];
		$GLOBALS["x_entidad_domicilio"] = $row3["entidad"];
		$GLOBALS["x_codigo_postal_domicilio"] = $row3["codigo_postal"];
		$GLOBALS["x_ubicacion_domicilio"] = $row3["ubicacion"];	
		$GLOBALS["x_numero_exterior"] = $row3["numero_exterior"];
		$GLOBALS["x_telefono"] = $row3["telefono"];
		$GLOBALS["x_telefono_secundario"] = $row3["telefono_secundario"];
		$GLOBALS["x_telefono_movil"] = $row3["telefono_movil"];
		$x_telefono_casa[] = $row3["telefono"];
		$x_telefono_casa[] = $row3["telefono_secundario"];
		$x_telefono_celular[] = $row3["telefono_movil"];
		
		if (!empty($GLOBALS["x_delegacion_id"])){
		$sqlDel = "SELECT * FROM `delegacion` WHERE `delegacion_id` = ".$GLOBALS["x_delegacion_id"]." ";
		$rsDesl = phpmkr_query($sqlDel, $conn) or die ("Error al seleccionar los del".phpmkr_error()."sql:". $sqlDel);
		$rowDel = phpmkr_fetch_array($rsDesl);
		}
		$x_delegacion = $rowDel["descripcion"];
		
		$x_direccion_dom_th_g = "Calle : ".$GLOBALS["x_calle_domicilio"]." ". $GLOBALS["x_numero_exterior"].", colonia: ".$GLOBALS["x_colonia_domicilio"].", localidad :".$GLOBALS["x_localidad_id"].", delegacion: ".$x_delegacion.", C.P.: ".$GLOBALS["x_codigo_postal_domicilio"]; 
$x_delegacion = "";

//echo $GLOBALS["x_direccion_dom_th"] ;

		//falta telefono secundario
#direccion negocio
		$sqlD = "SELECT * FROM direccion WHERE cliente_id = $x_id_int and direccion_tipo_id = 2 order by direccion_id desc limit 1";	
		$sSql = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where cliente_id = $x_c_id  and direccion_tipo_id = 2 order by direccion_id desc limit 1";
		$rs4 = phpmkr_query($sqlD,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row4 = phpmkr_fetch_array($rs4);
		
			$GLOBALS["x_direccion_id2"] = $row4["direccion_id"];
			$GLOBALS["x_giro_negocio"] = $row4["giro_negocio"];
			$GLOBALS["x_calle_negocio"] = $row4["calle"];
			$GLOBALS["x_colonia_negocio"] = $row4["colonia"];
			$GLOBALS["x_entidad_negocio"] = $row4["entidad"];
			$GLOBALS["x_ubicacion_negocio"] = $row4["ubicacion"];
			$GLOBALS["x_codigo_postal_negocio"] = $row4["codigo_postal"];			
			$GLOBALS["x_delegacion_id2"] = $row4["delegacion_id"];
			$GLOBALS["x_localidad_id2"] = $row4["localidad_id"];
			$GLOBALS["x_numero_exterior2"] = $row4["numero_exterior"];
			$x_telefono_casa[] = $row3["telefono"];
		$x_telefono_casa[] = $row3["telefono_secundario"];
		$x_telefono_celular[] = $row3["telefono_movil"];
			
			
			 if(!empty($GLOBALS["x_delegacion_id2"])){
				$sqlDel = "SELECT * FROM `delegacion` WHERE `delegacion_id` = ".$GLOBALS["x_delegacion_id2"]." ";
		$rsDesl = phpmkr_query($sqlDel, $conn) or die ("Error al seleccionar los del".phpmkr_error()."sql:". $sqlDel);
		$rowDel = phpmkr_fetch_array($rsDesl);
			 }
		$x_delegacion1 = $rowDel["descripcion"];
				$x_direccion_neg_th_g = "Calle : ".$GLOBALS["x_calle_negocio"]." ".$GLOBALS["x_numero_exterior2"].", colonia: ".$GLOBALS["x_colonia_negocio"].", localidad :".$GLOBALS["x_localidad_id2"].", delegacion: ".$x_delegacion1.", C.P.: ".$GLOBALS["x_codigo_postal_negocio"]; 
				
		$x_delegacion= "";		
		//echo $GLOBALS["x_direccion_neg_th"];
		

		
	$x_lis_tel = "";	
	if(!empty($x_telefono_casa)){
foreach($x_telefono_casa as $telefonos_casa){
	$x_lis_tel = $x_lis_tel.$telefonos_casa .", ";
	}
	}
	$x_lis_cel = "";
	if(!empty($x_telefono_celeular)){
foreach($x_telefono_celeular as $telefonos_cel){
	$x_lis_cel = $x_lis_cel.$telefonos_cel .", ";
	}	
	}
	
	if(!empty($x_telefono_casa_aval)){
		foreach( $x_telefono_casa_aval as $telefonos_aval){
			$x_lis_tel_aval = $x_lis_tel_aval. $telefonos_aval. " "; 
			
			}
		
		}

$x_respuesta .='<tr>
  <td  class="txt_negro_medio">TH DOM:'. $x_direccion_dom_th_g.'</td>
</tr>
<tr>
  <td  class="txt_negro_medio">TH NEG:'. $x_direccion_neg_th_g.'</td>
</tr>
<tr>';
$x_respuesta .= '<td  class="txt_negro_medio">Telefonos:  " Casa: "'.$x_lis_tel.' " Cel: "'.$x_lis_cel.'</td>
</tr>
<tr>
  <td  class="txt_negro_medio">&nbsp;</td>
</tr>
</table>';


	}

}
	
	
	
	
	
	
	}else{
    
$x_respuesta7=' <table align="center" width="700" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td colspan="5" align="center" class="txt_negro_medio" style=" border-bottom: solid 1px #000">Datos de Cliente</td>
</tr>
<tr>
  <td colspan="5"  class="txt_negro_medio">TH DOM:'.$x_direccion_dom_th.'</td>
</tr>
<tr>
  <td colspan="5"  class="txt_negro_medio">TH NEG: '.$x_direccion_neg_th.'</td>
</tr>
<tr>';


if(!empty($x_telefono_casa)){
foreach($x_telefono_casa as $telefonos_casa){
	$x_lis_tel = $x_lis_tel.$telefonos_casa .", ";
	}
}
if(!empty($x_telefono_celular)){
foreach($x_telefono_celular as $telefonos_cel){
	$x_lis_cel = $x_lis_cel.$telefonos_cel .", ";
	}
}
if(!empty($x_telefono_casa_aval)){
		foreach( $x_telefono_casa_aval as $telefonos_aval){
			$x_lis_tel_aval = $x_lis_tel_aval. $telefonos_aval. " "; 
			
			}
		
		}

$x_respuesta8='<td colspan="5"  class="txt_negro_medio">Telefonos:  Casa: '.$x_lis_tel. ' Cel: '.$x_lis_cel.'</td>
</tr>
<tr>
  <td colspan="5"  class="txt_negro_medio">&nbsp;</td>
</tr>
<tr>
  <td colspan="5" align="center" class="txt_negro_medio" style=" border-bottom: solid 1px #000">Datos de Aval</td>
</tr>
<tr>
  <td colspan="5" class="txt_negro_medio">Aval :'. $x_nombre_avala.'</td>
</tr>
<tr>
  <td colspan="5"  class="txt_negro_medio">AV DOM: '.$x_direccion_dom_av.'</td>
</tr>
<tr>
  <td colspan="5"  class="txt_negro_medio">AV NEG:'. $x_direccion_neg_av.'</td>
</tr>
<tr>';



$x_respuesta9=' <td colspan="5"  class="txt_negro_medio">Telefonos: Casa: '.$x_lis_tel_aval. ' Cel: '.$x_telefono_c.'</td>
</tr>
<tr>
  <td colspan="5"  class="txt_negro_medio">&nbsp;</td>
</tr>
</table>';
}
$x_respuesta10= '<table width="700" align="center">
<tr>
  <td colspan="5" align="center" class="txt_negro_medio" style=" border-bottom: solid 1px #000">Estado de Cliente</td>
</tr>
<tr>
  <td colspan="5" align="center" class="txt_negro_medio">&nbsp;</td>
</tr>
<tr>
  <td colspan="5" class="txt_negro_medio">';
  

	$sSqlvencimeinto = "SELECT * FROM vencimiento WHERE (vencimiento.credito_id = $x_solcre_id)  and vencimiento.vencimiento_num < 2000 ORDER BY vencimiento.vencimiento_num+0";  
	//and vencimiento.vencimiento_num < 2000
	//echo $sSqlvencimeinto;
	$rsVencimiento = phpmkr_query($sSqlvencimeinto,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSqlvencimiento);
  
  
  
$x_respuesta11='<table width="700"  align="center" cellpadding="0" cellspacing="0" border="0"> 
    
    <tr class="ewTableHeader">
      <td width="2%"> &nbsp; </td>
      <td width="5%" align="center" valign="middle" class="txt_negro_medio">No</td>
      <td width="9%" align="center" valign="middle" class="txt_negro_medio"> Status </td>
      <td width="8%" align="center" valign="middle" class="txt_negro_medio"> Fecha </td>
      <td width="14%" align="center" valign="middle" class="txt_negro_medio"> Fecha de Pago </td>
      <td width="10%" align="center" valign="middle" class="txt_negro_medio"> Capital </td>
      <td width="9%" align="center" valign="middle" class="txt_negro_medio"> Importe </td>
      <td width="9%" align="center" valign="middle" class="txt_negro_medio"> Inter&eacute;s </td>
      <td width="12%" align="center" valign="middle" class="txt_negro_medio">IVA</td>
      <td width="12%" align="center" valign="middle" class="txt_negro_medio"> Moratorios </td>
      <td width="7%" align="center" valign="middle" class="txt_negro_medio">IVA M.</td>
      <td width="7%" align="center" valign="middle" class="txt_negro_medio"> Total </td>
    </tr>';
    


$sSqlWrk = "SELECT importe FROM credito where credito_id = $x_solcre_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
$x_saldo = $datawrk["importe"];
@phpmkr_free_result($rswrk);

$x_total = 0;
$x_total_pagos = 0;
$x_total_interes = 0;
$x_total_iva = 0;
$x_total_moratorios = 0;
$x_total_iva_moratorios = 0;
$x_total_total = 0;

$x_total_d = 0;
$x_total_pagos_d = 0;
$x_total_interes_d = 0;
$x_total_iva_d = 0;
$x_total_moratorios_d = 0;
$x_total_iva_moratorios_d = 0;
$x_total_total_d = 0;

//NUEVO
$x_total_pagos_v = 0;
$x_total_interes_v = 0;
$x_total_iva_v = 0;
$x_total_iva_mor_v = 0;
$x_total_moratorios_v = 0;
$x_total_total_v = 0;


$x_total_a = 0;
$x_total_pagos_a = 0;
$x_total_interes_a = 0;
$x_total_iva_a = 0;
$x_total_moratorios_a = 0;
$x_total_iva_moratorios_a = 0;
$x_total_total_a = 0;


while ($rowVencimeinto = @phpmkr_fetch_array($rsVencimiento)) {

		$x_vencimiento_id = $rowVencimeinto["vencimiento_id"];
		$x_vencimiento_num = $rowVencimeinto["vencimiento_num"];		
		$x_vencimiento_status_id = $rowVencimeinto["vencimiento_status_id"];
		
		$x_fecha_vencimiento = $rowVencimeinto["fecha_vencimiento"];
		$x_importe = $rowVencimeinto["importe"];
		$x_interes = $rowVencimeinto["interes"];
		$x_iva = $rowVencimeinto["iva"];		
		$x_iva_mor = $rowVencimeinto["iva_mor"];				
		if(empty($x_iva)){
			$x_iva = 0;
		}
		if(empty($x_iva_mor)){
			$x_iva_mor = 0;
		}
		
		$x_interes_moratorio = $rowVencimeinto["interes_moratorio"];
		
		$x_total = $x_importe + $x_interes + $x_iva + $x_interes_moratorio + $x_iva_mor;

		$x_total_pagos = $x_total_pagos + $x_importe;
		$x_total_interes = $x_total_interes + $x_interes;
		$x_total_iva = $x_total_iva + $x_iva;		
		$x_total_moratorios = $x_total_moratorios + $x_interes_moratorio;
		$x_total_iva_moratorios = $x_total_iva_moratorios + $x_interes_iva_moratorio;		
		$x_total_total = $x_total_total + $x_total;
		
		if(($x_vencimiento_status_id == 2) || ($x_vencimiento_status_id == 5)){
		
			$sSqlWrk = "SELECT fecha_pago, referencia_pago_2 FROM recibo join recibo_vencimiento on recibo_vencimiento.recibo_id = recibo.recibo_id where recibo_vencimiento.vencimiento_id = $x_vencimiento_id and recibo.recibo_status_id = 1";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$rowwrk = phpmkr_fetch_array($rswrk);
			$x_fecha_pago = $rowwrk["fecha_pago"];
			$x_referencia_pago2 = $rowwrk["referencia_pago_2"];			

			@phpmkr_free_result($rswrk);

			$x_total_pagos_a = $x_total_pagos_a + $x_importe;
			$x_total_interes_a = $x_total_interes_a + $x_interes;
			$x_total_iva_a = $x_total_iva_a + $x_iva;			
			$x_total_moratorios_a = $x_total_moratorios_a + $x_interes_moratorio;
			$x_total_iva_moratorios_a = $x_total_iva_moratorios_a + $x_interes_iva_moratorio;			
			$x_total_total_a = $x_total_total_a + $x_total;

		}else{
			$x_fecha_pago  = "";
			$x_referencia_pago2 = "";						

			$x_total_pagos_d = $x_total_pagos_d + $x_importe;
			$x_total_interes_d = $x_total_interes_d + $x_interes;
			$x_total_iva_d = $x_total_iva_d + $x_iva;			
			$x_total_moratorios_d = $x_total_moratorios_d + $x_interes_moratorio;
			$x_total_iva_moratorios_d = $x_total_iva_moratorios_d + $x_interes_iva_moratorio;			
			$x_total_total_d = $x_total_total_d + $x_total;
			
			//NUEVO
			
			if($x_vencimiento_status_id == 3){
				$x_total_pagos_v = $x_total_pagos_v + $x_importe;
				$x_total_interes_v = $x_total_interes_v + $x_interes;
				$x_total_iva_v = $x_total_iva_v + $x_iva;			
				$x_total_iva_mor_v = $x_total_iva_mor_v + $x_iva_mor;						
				$x_total_moratorios_v = $x_total_moratorios_v + $x_interes_moratorio;
				$x_total_total_v = $x_total_total_v + $x_total;
			}
			
			
			
			
			
			
		}
		
		$x_ref_loc = str_pad($x_vencimiento_id, 5, "0", STR_PAD_LEFT)."/".str_pad($x_vencimiento_num, 2, "0", STR_PAD_LEFT);
		

// $x_respuesta11.= '<tr'. $sItemRowClass.$sListTrJs .'>';
    $x_respuesta11.= '<tr class="ewTableHeader">';
      
$x_respuesta11.= '<td> </td>

     
     
      <td align="right"> <span class="txt_datos_azul">'. $x_vencimiento_num.' </span></td>
      
     
      <td align="center">
        <span class="txt_datos_azul">';
        
if ((!is_null($x_vencimiento_status_id)) && ($x_vencimiento_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `vencimiento_status`";
	$sTmp = $x_vencimiento_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `vencimiento_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_vencimiento_status_id = $x_vencimiento_status_id; // Backup Original Value
$x_vencimiento_status_id = $sTmp;

        $x_respuesta11 .= $x_vencimiento_status_id;         
         $x_vencimiento_status_id = $ox_vencimiento_status_id; // Restore Original Value ?     
       $x_respuesta11 .=' </span></td>
      
      <td align="center"> <span class="txt_datos_azul">'. FormatDateTime($x_fecha_vencimiento,7).'</span></td>
      <td align="center"> <span class="txt_datos_azul">'. FormatDateTime($x_fecha_pago,7).'</span></td>
      <td align="right"> <span class="txt_datos_azul">'.  FormatNumber($x_saldo,2,0,0,1).'</span></td>
      
      <td align="right"> <span class="txt_datos_azul">'. FormatNumber($x_importe,2,0,0,1).' </span></td>
     
      <td align="right"> <span class="txt_datos_azul">'.FormatNumber($x_interes,2,0,0,1).'</span></td>
      <td align="right"><span class="txt_datos_azul">'.FormatNumber($x_iva,2,0,0,1).'</span></td>
      
      <td align="right"> <span class="txt_datos_azul">'. FormatNumber($x_interes_moratorio,2,0,0,1).'</span></td>
      <td align="right"><span class="txt_datos_azul">'. FormatNumber($x_iva_moratorios,2,0,0,1).'</span></td>
      <td align="right"> <span class="txt_datos_azul">'. FormatNumber($x_total,2,0,0,1).'</span></td>
    </tr>';
    
$x_saldo = $x_saldo - $x_importe;
}

$x_respuesta16='<tr>
      <td >&nbsp;</td>
      <td >&nbsp;</td>
      <td >&nbsp;</td>
      <td ><span>&nbsp;</span></td>
      <td ><span>&nbsp;</span></td>
      <td ><span>&nbsp;</span></td>
      <td ></td>
      <td ></td>
      <td ></td>
      <td ></td>
      <td ></td>
      <td ></td>
    </tr>
    <tr>
      <td style=" border-top: solid 1px #000">&nbsp;</td>
      <td style=" border-top: solid 1px #000">&nbsp;</td>
      <td style=" border-top: solid 1px #000">&nbsp;</td>
      <td style=" border-top: solid 1px #000" align="right"><span>&nbsp;</span></td>
      <td style=" border-top: solid 1px #000"><span>&nbsp;</span></td>
      <td style=" border-top: solid 1px #000" align="right"><span>&nbsp;</span></td>
      <td style=" border-top: solid 1px #000" align="right"> <span class="txt_datos_azul"><b> '. FormatNumber($x_total_pagos,2,0,0,1).'</b></span></td>
      <td style=" border-top: solid 1px #000" align="right"> <span class="txt_datos_azul"><b>'.FormatNumber($x_total_interes,2,0,0,1).'</b></span></td>
      <td align="right" class="txt_datos_azul" style=" border-top: solid 1px #000"><b>'. FormatNumber($x_total_iva,2,0,0,1).'</b></td>
      <td style=" border-top: solid 1px #000" align="right"> <span class="txt_datos_azul"><b>'. FormatNumber($x_total_moratorios,2,0,0,1).'</b></span></td>
      <td align="right" class="txt_datos_azul" style=" border-top: solid 1px #000"><b>'.FormatNumber($x_total_iva_moratorios,2,0,0,1).'</b></td>
      <td style=" border-top: solid 1px #000" align="right"> <span class="txt_datos_azul"><b>'.FormatNumber($x_total_total,2,0,0,1).'</b></span></td>
    </tr>
    
    <tr >
      <td  colspan="5" align="right" class="txt_negro_medio">SALDO DEUDOR:</td>
      <td  align="right"><span>&nbsp;</span></td>
      <td  align="right"> <span class="txt_datos_azul"><b> '.FormatNumber($x_total_pagos_d,2,0,0,1).'</b></span></td>
      <td align="right"> <span class="txt_datos_azul"><b> '. FormatNumber($x_total_interes_d,2,0,0,1).'</b></span></td>
      <td  align="right" class="txt_datos_azul"><b>'. FormatNumber($x_total_iva_d,2,0,0,1).'</b></td>
      <td  align="right"> <span class="txt_datos_azul"><b> '.FormatNumber($x_total_moratorios_d,2,0,0,1).'</b></span></td>
      <td  align="right" class="txt_datos_azul"><b>'.FormatNumber($x_total_iva_moratorios_d,2,0,0,1).'</b></td>
      <td  align="right"> <span class="txt_datos_azul"><b>'.FormatNumber($x_total_total_d,2,0,0,1).'</b></span></td>
    </tr>
    <tr>
  <td colspan="5" align="right" class="txt_negro_medio">SALDO VENCIDO:</td>
  <td align="right">&nbsp;</td>
  <td align="right"><span class="txt_datos_azul"><b>'.FormatNumber($x_total_pagos_v,2,0,0,1).'</b></span></td>
  <td align="right"><span class="txt_datos_azul"><b>'.FormatNumber($x_total_interes_v,2,0,0,1).'</b></span></td>
  <td align="right"><span class="txt_datos_azul"><b>'.FormatNumber($x_total_iva_v,2,0,0,1).'</b></span></td>
  <td align="right"><span class="txt_datos_azul"><b>'.FormatNumber($x_total_moratorios_v,2,0,0,1).'</b></span></td>
  <td align="right"><span class="txt_datos_azul"><b>'.FormatNumber($x_total_iva_mor_v,2,0,0,1).'</b></span></td>
  <td align="right"><span class="txt_datos_azul"><b>'.FormatNumber($x_total_total_v,2,0,0,1).'</b></span></td>
</tr>
    <tr>
      <td colspan="5" align="right" class="txt_negro_medio">TOTAL PAGADO:</td>
      <td  align="right">&nbsp;</td>
      <td align="right" class="txt_datos_azul"><b>'.FormatNumber($x_total_pagos_a,2,0,0,1).'</b></td>
      <td align="right" class="txt_datos_azul"><b>'.FormatNumber($x_total_interes_a,2,0,0,1).'</b></td>
      <td align="right" class="txt_datos_azul"><b>'.FormatNumber($x_total_iva_a,2,0,0,1).'</b></td>
      <td align="right" class="txt_datos_azul"><b>'.FormatNumber($x_total_moratorios_a,2,0,0,1).'</b></td>
      <td align="right" class="txt_datos_azul"><b>'.FormatNumber($x_total_iva_moratorios_a,2,0,0,1).'</b></td>
      <td align="right" class="txt_datos_azul"><b>'.FormatNumber($x_total_total_a,2,0,0,1).'</b></td>
    </tr>
  </table></td>
  </tr>
  <tr><td colspan="5">&nbsp;</td></tr>
  <tr><td colspan="5">&nbsp;</td></tr>
  <tr>
    <td colspan="5">
  <table width="700"  align="center" cellpadding="0" cellspacing="0" border="0"> 
  
   ';
    /*<tr class="ewTableHeader">
      <td width="2%"><span> &nbsp; </span></td>
      <td width="5%" align="center" valign="middle" class="txt_negro_medio"><span> No </span></td>
      <td width="9%" align="center" valign="middle" class="txt_negro_medio"> Status </td>
      <td width="8%" align="center" valign="middle" class="txt_negro_medio"> Fecha </td>
      <td width="14%" align="center" valign="middle" class="txt_negro_medio"> Fecha de Pago </td>
      <td width="10%" align="center" valign="middle" class="txt_negro_medio"> Capital </td>
      <td width="9%" align="center" valign="middle" class="txt_negro_medio"> Importe </td>
      <td width="9%" align="center" valign="middle" class="txt_negro_medio"> Inter&eacute;s </td>
      <td width="12%" align="center" valign="middle" class="txt_negro_medio">IVA</td>
      <td width="12%" align="center" valign="middle" class="txt_negro_medio"> Moratorios </td>
      <td width="7%" align="center" valign="middle" class="txt_negro_medio">IVA M.</td>
      <td width="7%" align="center" valign="middle" class="txt_negro_medio"> Total </td>
    </tr>*/
    


$sSqlWrk = "SELECT importe FROM credito where credito_id = $x_solcre_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrk = phpmkr_fetch_array($rswrk);
$x_saldo = $datawrk["importe"];
@phpmkr_free_result($rswrk);

$x_total = 0;
$x_total_pagos = 0;
$x_total_interes = 0;
$x_total_iva = 0;
$x_total_moratorios = 0;
$x_total_iva_moratorios = 0;
$x_total_total = 0;

$x_total_d = 0;
$x_total_pagos_d = 0;
$x_total_interes_d = 0;

$x_total_iva_d = 0;
$x_total_moratorios_d = 0;
$x_total_iva_moratorios_d = 0;
$x_total_total_d = 0;

//NUEVO
$x_total_pagos_v = 0;
$x_total_interes_v = 0;
$x_total_iva_v = 0;
$x_total_iva_mor_v = 0;
$x_total_moratorios_v = 0;
$x_total_total_v = 0;


$x_total_a = 0;
$x_total_pagos_a = 0;
$x_total_interes_a = 0;
$x_total_iva_a = 0;
$x_total_moratorios_a = 0;
$x_total_iva_moratorios_a = 0;
$x_total_total_a = 0;

$sqlPenalizaciones = "SELECT * FROM vencimiento WHERE (vencimiento.credito_id = $x_credito_id) AND (vencimiento.vencimiento_num > 2000)  ";
$rs = phpmkr_query($sqlPenalizaciones, $conn) or die ("Error".phpmkr_error()."sql".$sqlPenalizaciones);

while ($row = @phpmkr_fetch_array($rs)) {

		$x_vencimiento_id = $row["vencimiento_id"];
		$x_vencimiento_num = $row["vencimiento_num"];		
		$x_vencimiento_status_id = $row["vencimiento_status_id"];
		
		$x_fecha_vencimiento = $row["fecha_vencimiento"];
		$x_importe = $row["importe"];
		$x_interes = $row["interes"];
		$x_iva = $row["iva"];		
		$x_iva_mor = $row["iva_mor"];				
		if(empty($x_iva)){
			$x_iva = 0;
		}
		if(empty($x_iva_mor)){
			$x_iva_mor = 0;
		}
		
		$x_interes_moratorio = $row["interes_moratorio"];
		
		$x_total = $x_importe + $x_interes + $x_iva + $x_interes_moratorio + $x_iva_mor;

		$x_total_pagos = $x_total_pagos + $x_importe;
		$x_total_interes = $x_total_interes + $x_interes;
		$x_total_iva = $x_total_iva + $x_iva;		
		$x_total_moratorios = $x_total_moratorios + $x_interes_moratorio;
		$x_total_iva_moratorios = $x_total_iva_moratorios + $x_iva_mor;		
		$x_total_total = $x_total_total + $x_total;
		
		if(($x_vencimiento_status_id == 2) || ($x_vencimiento_status_id == 5)){
		
			$sSqlWrk = "SELECT fecha_pago, referencia_pago_2 FROM recibo join recibo_vencimiento on recibo_vencimiento.recibo_id = recibo.recibo_id where recibo_vencimiento.vencimiento_id = $x_vencimiento_id and recibo.recibo_status_id = 1";
			$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$rowwrk = phpmkr_fetch_array($rswrk);
			$x_fecha_pago = $rowwrk["fecha_pago"];
			$x_referencia_pago2 = $rowwrk["referencia_pago_2"];			

			@phpmkr_free_result($rswrk);

			$x_total_pagos_a = $x_total_pagos_a + $x_importe;
			$x_total_interes_a = $x_total_interes_a + $x_interes;
			$x_total_iva_a = $x_total_iva_a + $x_iva;			
			$x_total_moratorios_a = $x_total_moratorios_a + $x_interes_moratorio;
			$x_total_iva_moratorios_a = $x_total_iva_moratorios_a + $x_interes_iva_moratorio;			
			$x_total_total_a = $x_total_total_a + $x_total;

		}else{
			$x_fecha_pago  = "";
			$x_referencia_pago2 = "";						

			$x_total_pagos_d = $x_total_pagos_d + $x_importe;
			$x_total_interes_d = $x_total_interes_d + $x_interes;
			$x_total_iva_d = $x_total_iva_d + $x_iva;			
			$x_total_moratorios_d = $x_total_moratorios_d + $x_interes_moratorio;
			$x_total_iva_moratorios_d = $x_total_iva_moratorios_d + $x_interes_iva_moratorio;			
			$x_total_total_d = $x_total_total_d + $x_total;
			
			//NUEVO
			
			if($x_vencimiento_status_id == 3){
				$x_total_pagos_v = $x_total_pagos_v + $x_importe;
				$x_total_interes_v = $x_total_interes_v + $x_interes;
				$x_total_iva_v = $x_total_iva_v + $x_iva;			
				$x_total_iva_mor_v = $x_total_iva_mor_v + $x_iva_mor;						
				$x_total_moratorios_v = $x_total_moratorios_v + $x_interes_moratorio;
				$x_total_total_v = $x_total_total_v + $x_total;
			}
			
			
			
			
			
			
		}
		
		$x_ref_loc = str_pad($x_vencimiento_id, 5, "0", STR_PAD_LEFT)."/".str_pad($x_vencimiento_num, 2, "0", STR_PAD_LEFT);
		

    
 $x_respuesta17= '  <tr'.$sItemRowClass .$sListTrJs.'>';

      $x_respuesta18= '<td></td>
    
      <td align="right"> <span class="txt_datos_azul">'.$x_vencimiento_num.'</span></td>
    
      <td align="center">
        <span class="txt_datos_azul">';
     
if ((!is_null($x_vencimiento_status_id)) && ($x_vencimiento_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `vencimiento_status`";
	$sTmp = $x_vencimiento_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `vencimiento_status_id` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_vencimiento_status_id = $x_vencimiento_status_id; // Backup Original Value
$x_vencimiento_status_id = $sTmp;

         $x_respuesta19= $x_vencimiento_status_id;      
         $x_vencimiento_status_id = $ox_vencimiento_status_id; // Restore Original Value     
       $x_respuesta20= ' </span></td>
      
      <td align="center"> <span class="txt_datos_azul">'.FormatDateTime($x_fecha_vencimiento,7).'</span></td>
      <td align="center"> <span class="txt_datos_azul">'.FormatDateTime($x_fecha_pago,7).'</span></td>
      <td align="right"> <span class="txt_datos_azul">'.(is_numeric($x_saldo)) ? FormatNumber($x_saldo,2,0,0,1) : $x_saldo.'</span></td>
     
      <td align="right"> <span class="txt_datos_azul">'.(is_numeric($x_importe)) ? FormatNumber($x_importe,2,0,0,1) : $x_importe.'</span></td>
      
      <td align="right"> <span class="txt_datos_azul">'.(is_numeric($x_interes)) ? FormatNumber($x_interes,2,0,0,1) : $x_interes.'</span></td>
      <td align="right"><span class="txt_datos_azul">'.(is_numeric($x_iva)) ? FormatNumber($x_iva,2,0,0,1) : $x_iva.'</span></td>
     
      <td align="right"> <span class="txt_datos_azul">'.(is_numeric($x_interes_moratorio)) ? FormatNumber($x_interes_moratorio,2,0,0,1) : $x_interes_moratorio.'</span></td>
      <td align="right"><span class="txt_datos_azul">'.(is_numeric($x_iva_mor)) ? FormatNumber($x_iva_mor,2,0,0,1) : $x_iva.'</span></td>
      <td align="right"> <span class="txt_datos_azul">'.(is_numeric($x_total)) ? FormatNumber($x_total,2,0,0,1) : $x_total.'</span></td>
    </tr>';
    
$x_saldo = $x_saldo - $x_importe;
}

    $x_respuesta21=' <tr>
      <td >&nbsp;</td>
      <td >&nbsp;</td>
      <td >&nbsp;</td>
      <td ><span>&nbsp;</span></td>
      <td ><span>&nbsp;</span></td>
      <td ><span>&nbsp;</span></td>
      <td ></td>
      <td ></td>
      <td ></td>
      <td ></td>
      <td ></td>
      <td ></td>
    </tr>
 
  </table>
  
  
  </td></tr>
     <table width="700" align="center">
<tr>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
</tr>
<tr>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
</tr>
<tr>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
</tr>
<tr>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
</tr>
 
<tr>
  <td colspan="5" class="txt_negro_medio"><h4>Solicitamos su pago a la brevedad, para mayores informes comuniquese a nuestra linea de atencion a clientes en el D.F al 51350259 en el interior de la republica a la linea sin consto 018008376133.</h4></td>
</tr>
</table>';

$x_credito_id = $x_solcre_id;
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

$sqlSaldo = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and  fecha_vencimiento <= '$x_fecha_fin_mes' and vencimiento_status_id in (1,3,6,7) ";
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
$sqlSaldo2 = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and  fecha_vencimiento > '$x_fecha_fin_mes' and vencimiento_status_id in (1,3,6) ";
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
$sqlSaldo3 = "SELECT * FROM vencimiento WHERE credito_id = $x_credito_id and  fecha_vencimiento > '$x_fecha_fin_mes' and vencimiento_status_id in (7) ";
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


# seleccionamos la forma de pago 
$sqlFormaPago = "SELECT forma_pago_id FROM credito WHERE credito_id = $x_credito_id ";
$rsFormaPago = phpmkr_query($sqlFormaPago, $conn) or die ("Error al seleccionar".phpmkr_error()."sql:".$sqlFormaPago);
$rowFormaPago = phpmkr_fetch_array($rsFormaPago);
$x_forma_pago_id = $rowFormaPago["forma_pago_id"];


$x_respuesta22= '<br style="page-break-before:always" clear="all"> 
<table align="center" width="700" border="0"  id="ewlistmain" class="ewTable" style="font-family:Verdana, Geneva, sans-serif; font-size:12px; border-bottom-color:#CCCCCC;text-align:justify" >
<tr>
<td><strong> Comentarios Internos</strong> Credito No.<strong>'. $x_credito_num.'</strong></td>

<td>  </td>

</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>'. $x_comentario_int.'</td>
<td></td>
</tr>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td><strong> Comentarios Externos</strong></td>
<td></td>
</tr>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>'.$x_comentario_ext.'</td>';
$x_respuesta23= '<td></td>

</tr>
</table>';
 if ($x_forma_pago_id == 3){ 
 $x_respuesta24= '<table   align="center" width="700" border="0" id="ewlistmain" class="ewTable" style="font-family:Verdana, Geneva, sans-serif; font-size:12px; border-bottom-color:#CCCCCC">
  <tr>
    <td colspan="3"><strong>LIQUIDACI&Oacute;N DE CR&Eacute;DITO</strong></td>
    </tr>
  <tr>
    <td width="493" >Fecha de vigencia</td>
    <td colspan="2">'. FormatDateTime($x_fecha_de_vigencia,7).'</td>
    </tr>
  <tr>
    <td>Monto para liquidar</td>
    <td colspan="2"><strong>'.FormatNumber($x_saldo_a_liquidar,2,0,0,1).'</strong></td>
    </tr>
     <tr>
    <td>Detalle</td>
    <td width="374" >Vencimientos</td>
    <td width="119" >Saldo</td>
  </tr>
  <tr>
    <td>Vencimientos pendientes con interes</td>
    <td>'. $x_lista_con_interes.'</td>
    <td  align="right">'. FormatNumber($x_total_liquida_con_interes,2,0,0,1).'</td>
  </tr>
  <tr>
    <td>Vencimientos pendientes sin interes</td>
    <td>'. $x_lista_sin_interes.'</td>
    <td align="right">'. FormatNumber($x_total_liquida_sin_interes,2,0,0,1).'</td>
  </tr>
  <tr>
    <td>Moratorios</td>
    <td>'.$x_lista_penalizaciones.'</td>
    <td align="right">'. FormatNumber($x_total_penalizaciones,2,0,0,1).'</td>
  </tr>
  <tr>
    <td colspan="2" align="right">Total: </td>
    <td align="right"><strong>'. FormatNumber($x_saldo_a_liquidar,2,0,0,1).'</strong></td>
  </tr>
</table>';

 }
 $x_respuesta26='<br style="page-break-before:always" clear="all">';

//echo "respuesta dos -----".$x_respuesta2;
//echo "respuesta tres -----".$x_respuesta3;
//echo "respuesta cuatros -----".$x_respuesta4;

//return $bLoadData;
$x_tabla_fina = $x_respuesta. $x_respuesta1.$x_respuesta2.$x_respuesta3.$x_respuesta4.$x_respuesta5.$x_respuesta6.$x_respuesta7.$x_respuesta8.$x_respuesta9.$x_respuesta10.$x_respuesta11.$x_respuesta12.$x_respuesta13.$x_respuesta14.$x_respuesta15.$x_respuesta16.$x_respuesta17.$x_respuesta18.$x_respuesta19.$x_respuesta20.$x_respuesta21.$x_respuesta26;

// se quitaron los comentarios en lo qu se busca como truncarlos  $x_respuesta22.$x_respuesta23.$x_respuesta24.
return $x_tabla_fina ;
}	
	
