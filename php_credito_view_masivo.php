<?php session_start(); ?>
<?php ob_start(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../crm.css" rel="stylesheet" type="text/css" />
<link href="pagare_css.css" rel="stylesheet" type="text/css" media="print" />
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

$x_credito_id = $_GET["key"];
$x_crm_caso_id = $_GET["key2"];
$x_link = $_GET["key3"];


// seleccionamos todos los casos que sorrespondan ala sucursal del responsable
$sSql = "SELECT * FROM crm_caso join crm_tarea on crm_tarea.crm_caso_id = crm_caso.crm_caso_id ";
$conn = phpmkr_db_connect(HOST, USER, PASS,DB, PORT);

//echo "user rol".$_SESSION["php_project_esf_status_UserRolID"] ."<br>";

if($_SESSION["php_project_esf_status_UserRolID"] == 12){
			// el usuario tiene un rol de RESPONSABLE DE SUCURSAL			
			//1.- seleccionamos la sucursal del  reponsable de sucursal
			//2.- PRIMERO SELECCIONAMOS A TODOS LOS PROMOTORES QUE CORRESPONDEN A LA SUCURSAL
			//3.- SELECCIONAMOS LOS CASOS QUE CORRESPONDEN A DICHOS PROMOTORES
			
		//	echo "usuario rol 12";
			//1.-
			$sqlSucursal = "SELECT sucursal_id FROM responsable_sucursal  WHERE usuario_id = ".$_SESSION["php_project_esf_status_UserID"]." ";
			$rsSucursal = phpmkr_query($sqlSucursal, $conn) or die("Error al seleccionar los datos de la sucursal vf".phpmkr_error()."sql:".$sqlSucursal);
			$rowSucursal = phpmkr_fetch_array($rsSucursal);
			$x_sucursal_id = $rowSucursal["sucursal_id"];
			//echo "sql suc".$sqlSucursal."<br>";
			
			// seleccionamos todos los credito que pertenecen a aesa sucursal
			$x_listado_creditos = "";
			$sql_credito = "Select credito.credito_id, promotor.* from credito JOIN  solicitud on solicitud.solicitud_id =  credito.solicitud_id join promotor ON promotor.promotor_id = solicitud.promotor_id WHERE promotor.sucursal_id = $x_sucursal_id";
			$rs_credito = phpmkr_query($sql_credito, $conn)or die ("Error al selccionar los credito de la sucursal". phpmkr_error()."sql".$sql_credito);
			while($row_credito = phpmkr_fetch_array($rs_credito)){
				$x_listado_creditos .= $row_credito["credito_id"].", ";
				
				}
			$x_listado_creditos = trim($x_listado_creditos,", ");
			
			//2.-			
			$sqlPromotores = "SELECT usuario_id from promotor WHERE sucursal_id = $x_sucursal_id";
			$rsPromotores = phpmkr_query($sqlPromotores, $conn) or die("Error al seleccionar los promotres". phpmkr_error()."sql:".$sqlPromotores);
			while($rowPromotores = phpmkr_fetch_array($rsPromotores)){
				$x_lista_promotores .= $rowPromotores["promotor_id"].", ";
				$x_lista_usuarios .= $rowPromotores["usuario_id"].", ";
				}
				
			//echo "promotores ".$sqlPromotores."<br>";	
			//echo "lista".$x_lista_usuarios."<br>";
				
			$x_lista_promotores = trim($x_lista_promotores, ", ");
			$x_lista_usuarios = trim($x_lista_usuarios, ", ");
			
			// sacamos el usuario id de los promotores
			if(!empty($x_lista_usuarios)){
			//$sDbWhere .= "crm_tarea.destino IN (".$x_lista_usuarios .") AND ";	
			$sDbWhere .= "crm_caso.credito_id IN (".$x_listado_creditos .") AND ";	
			}else{
				$sDbWhere .= "(crm_caso.destino = ".$_SESSION["crm_UserID"].") AND ";
				}
			
			
			
			}else{
			$sDbWhere .= "(crm_tarea.destino = ".$_SESSION["crm_UserID"].") AND ";	
			
			}
$sDbWhere .= "(crm_tarea.crm_tarea_status_id in (1,2)) ";				

if($sDbWhere != ""){
	$sSql .= " WHERE ".$sDbWhere .$gestor_where;
}

$rss = phpmkr_query($sSql, $conn) or die ("Error al seleccionar los casos de la tareas del CRM".phpmkr_error()."sql:".$sSql);
//echo "sSql ".$sSql;

/*if(!empty($x_credito_id)){
	$conn = phpmkr_db_connect(HOST, USER, PASS,DB);
	LoadData($conn);
}else{
	echo "No se ha espcificadoel Numero de Credito";
	exit();
}

if(empty($x_link)){
	$x_link = 0;
}
*/
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=edocta.xls');
}else{
	$sExport = "";
}

?>

<?php


 ?>
<br />
<br />
<br />
<br />
<div align="left" style=" padding-left: 10px" class="txt_datos_azul"></div>
<br /><br />
<?php
$x_contador = 0;
echo "num_row ".mysql_num_rows($rss);
?>
<?php while($rowa = phpmkr_fetch_array($rss)){ 
echo "entra 1<br>";
$x_contador ++;
echo "contador".$x_contador."<br>";
//caso
	$sKey = $rowa["crm_caso_id"];
	$x_crm_caso_id = $rowa["crm_caso_id"];
	echo "x_crm_caso_id".$x_crm_caso_id;
	$x_crm_caso_tipo_id = $rowa["crm_caso_tipo_id"];
	$x_crm_caso_status_id = $rowa["crm_caso_status_id"];
	$x_crm_caso_prioridad_id = $rowa["crm_caso_prioridad_id"];
	$x_cuenta_id = $rowa["cuenta_id"];
	$x_fecha_registro = $rowa["fecha_registro"];
	$x_origen = $rowa["origen"];
	$x_responsable = $rowa["responsable"];
	$x_bitacora = $rowa["bitacora"];
	$x_fecha_status = $rowa["fecha_status"];
	$x_solicitud_id = $rowa["solicitud_id"];	
	$x_credito_id = $rowa["credito_id"];	
	echo "credito id".$x_credito_id;	

	//tarea
	$x_crm_tarea_id = $rowa["crm_tarea_id"];
	$x_crm_tarea_tipo_id = $rowa["crm_tarea_tipo_id"];
	$x_crm_tarea_prioridad_id = $rowa["crm_tarea_prioridad_id"];
	$x_fecha_registro = $rowa["fecha_registro"];
	$x_hora_registro = $rowa["hora_registro"];
	$x_fecha_ejecuta = $rowa["fecha_ejecuta"];
	$x_hora_ejecuta = $rowa["hora_ejecuta"];
	$x_fecha_recordatorio = $rowa["fecha_recordatorio"];
	$x_hora_recordatorio = $rowa["hora_recordatorio"];
	$x_origen_rol = $rowa["origen_rol"];
	$x_destino_rol = $rowa["destino_rol"];
	$x_origen = $rowa["origen"];
	$x_destino = $rowa["destino"];
	$x_observaciones = $rowa["observaciones"];
	$x_crm_tarea_status_id = $rowa["crm_tarea_status_id"];			
	$x_fecha_status = $rowa["fecha_status"];
	$x_asunto = $rowa["asunto"];
	$x_descripcion = $rowa["descripcion"];
	
	//seleccionamos es status del credito si ya esta liquidado entonces no se imprime nada
	$sqlLiquidado = "SELECT credito_status_id FROM credito 	WHERE credito_id = $x_credito_id";
	$rsStatus = phpmkr_query($sqlLiquidado, $conn) or die ("Error al seleccionar el estatus del credito".phpmkr_error()."sql:".$sqlLiquidado);
	$rowStatus = phpmkr_fetch_array($rsStatus);
	$x_status_credito = $rowStatus["credito_status_id"];
	echo "satus del credito".$x_status_credito."<br>";
	if(($x_status_credito != 2 )&& ($x_status_credito != 3 )){
	
	
	
	// cargamos los datos generales del credito
	
	if(LoadData($conn, $x_credito_id )){
		echo "salgo de load data";
		}
		echo "<br>pasa load data";
	
	echo "<br>cuenta".$x_cuenta;
  echo "<br>credito".$x_credito_num;
   echo "<br>status".$x_credito_status_id;

?>

<table align="center" width="700" border="0" cellspacing="0" cellpadding="0">
<tr>
<td>
 
  <img src="images/logo.gif" width="115" height="93" />
  </td></tr>
</table>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="0" >
<tr >
<td colspan="5" class="txt_negro_medio" style=" border-bottom: solid 1px #C00; border-top: solid 1px #C00; padding-bottom: 5px; padding-top: 5px;" bgcolor="#ffffff">

  <?php echo "logo";?>
 
  <table align="center" width="700" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td class="txt_negro_medio" >&nbsp;</td>
  <td colspan="4" class="txt_datos_azul" align="center">Credito <?php echo $x_tipo_credito; ?></td>
</tr>
<?php echo "logo";?>
<tr>
  <td class="txt_negro_medio">Cuenta</td>
  <td colspan="4" class="txt_datos_azul"><?php echo $x_cuenta; ?></td>
  </tr>
  <?php echo "logo";?>
<tr>
  <td class="txt_negro_medio">Promotor:</td>
  <td colspan="4" class="txt_datos_azul"><?php echo $x_promotor; ?></td>
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
<td width="108" class="txt_datos_azul"><?php echo $x_credito_num; ?></td>
<td width="41">&nbsp;</td>
<td width="106" class="txt_negro_medio">Status</td>
<td width="313" class="txt_datos_azul">
  <?php
if ((!is_null($x_credito_status_id)) && ($x_credito_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `credito_status`";
	$sTmp = $x_credito_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `credito_status_id` = " . $sTmp . "";
	$rswrkd = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrkd && $rowwrkd = phpmkr_fetch_array($rswrkd)) {
		$sTmp = $rowwrkd["descripcion"];
	}
	@phpmkr_free_result($rswrkd);
} else {
	$sTmp = "";
}
$ox_credito_status_id = $x_credito_status_id; // Backup Original Value
$x_credito_status_id = $sTmp;
?>
  <?php echo $x_credito_status_id; ?>
  <?php $x_credito_status_id = $ox_credito_status_id; // Restore Original Value ?>
</td>
</tr>
<tr>
  <td class="txt_negro_medio">Tarjeta Num.</td>
  <td class="txt_datos_azul"><?php echo $x_tdp; ?></td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">Otorgado</td>
  <td class="txt_datos_azul"><?php echo FormatDateTime($x_fecha_otrogamiento,7); ?></td>
</tr>
<tr>
  <td class="txt_negro_medio">Vencimiento</td>
  <td class="txt_datos_azul"><?php echo FormatDateTime($x_fecha_vencimiento,7); ?></td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">Importe</td>
  <td class="txt_datos_azul"><?php echo (is_numeric($x_importe)) ? FormatNumber($x_importe,0,0,0,-2) : $x_importe; ?></td>
</tr>
<tr>
  <td class="txt_negro_medio">Tasa</td>
  <td class="txt_datos_azul"><?php echo (is_numeric($x_tasa)) ? FormatPercent(($x_tasa / 100),2,0,0,0) : ($x_tasa / 100); ?></td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">Num. pagos</td>
  <td class="txt_datos_azul"><?php echo $x_num_pagos; ?></td>
</tr>
<tr>
  <td class="txt_negro_medio">Tasa Moratorios</td>
  <td class="txt_datos_azul"><?php echo (is_numeric($x_tasa_moratoria)) ? FormatPercent(($x_tasa_moratoria / 100),2,0,0,0) : ($x_tasa_moratoria / 100); ?></td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">Forma de pago</td>
  <td class="txt_datos_azul">
    <?php 
		$sSqlWrk = "SELECT descripcion FROM forma_pago where forma_pago_id = $x_forma_pago_id";
		$rswrkd1 = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		$datawrkd1 = phpmkr_fetch_array($rswrkd1);
		echo $datawrkd1["descripcion"];
		@phpmkr_free_result($rswrkd1);
		?>
  </td>
</tr>
<tr>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
  <td>&nbsp;</td>
  <td class="txt_negro_medio">&nbsp;</td>
  <td class="txt_datos_azul">&nbsp;</td>
</tr>
</table>
<?php if( $x_credito_tipo_id == 2){
	//imprimimos la tabla de los integrantes
	echo $x_tabla_integrantes;
	$x_no_integrante = 1;
while($x_no_integrante <= 10){
	
	$x_nombre_int = "x_nombre_integrante_$x_no_integrante";
	$x_nombre_int = $$x_nombre_int;
	$x_id_int = "";


	//integrante_id
	$x_id_int = "x_cliente_id_$x_no_integrante";
	$x_id_int = $$x_id_int ;
	
	
	
	//echo "CLIENTE ID ++++".$x_id_int."<BR>";
	$x_id_int = $x_id_int +0;
	if(!empty($x_id_int) && $x_id_int >0 ){
		//Buscamos las direcciones y los datos del clientre
		
		
		//Nombre
		//Cuenta
$sSql = "select cliente.* from cliente where cliente_id = $x_id_int ";
$rs2a = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row2a = phpmkr_fetch_array($rs2a);
	$GLOBALS["x_cuenta"] = $row2a["nombre_completo"]." ".$row2a["apellido_paterno"]." ".$rowa2["apellido_materno"];
		//echo $GLOBALS["x_cuenta"];
		
		
$x_count_2 = 1;
		$sSql = "select * from  telefono where cliente_id = $x_id_int  AND telefono_tipo_id = 1 order by telefono_id";
		$rs9a = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9a = phpmkr_fetch_array($rs9a)){			
			$GLOBALS["x_telefono_casa_$x_count_2"] = $row9a["numero"];
			$GLOBALS["x_comentario_casa_$x_count_2"] = $row9a["comentario"];
			$GLOBALS["contador_telefono"] = $x_count_2;
			$x_count_2++;			
		}
		
		



		$x_count_3 = 1;
		$sSql = "select * from  telefono where cliente_id = $x_id_int  AND telefono_tipo_id = 2 order by telefono_id";
		$rs9e = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9e = phpmkr_fetch_array($rs9e)){			
			$GLOBALS["x_telefono_celular_$x_count_3"] = $row9e["numero"];
			$GLOBALS["x_comentario_celular_$x_count_3"] = $row9e["comentario"];
			$GLOBALS["x_compania_celular_$x_count_3"] = $row9e["compania_id"];	
			$GLOBALS["contador_celular"] = $x_count_3;
			$x_count_3++;
			
		}		
		
		#personal domicilio
$sqlD = "SELECT * FROM direccion WHERE cliente_id = $x_id_int and direccion_tipo_id = 1 order by direccion_id desc limit 1";		
		$sSql2 = "select * from direccion join delegacion
		on delegacion.delegacion_id = direccion.delegacion_id where cliente_id = $x_c_id and direccion_tipo_id = 1 order by direccion_id desc limit 1";
		$rs3a = phpmkr_query($sqlD,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sqlD);
		$row3a = phpmkr_fetch_array($rs3a);
		$GLOBALS["x_direccion_id"] = $row3a["direccion_id"];		
		$GLOBALS["x_calle_domicilio"] = $row3a["calle"];
		$GLOBALS["x_colonia_domicilio"] = $row3a["colonia"];
		$GLOBALS["x_delegacion_id"] = $row3a["delegacion_id"];
		$GLOBALS["x_localidad_id"] = $row3a["localidad_id"];
		$GLOBALS["x_propietario"] = $row3a["propietario"];
		$GLOBALS["x_entidad_domicilio"] = $row3a["entidad"];
		$GLOBALS["x_codigo_postal_domicilio"] = $row3a["codigo_postal"];
		$GLOBALS["x_ubicacion_domicilio"] = $row3a["ubicacion"];	
		$GLOBALS["x_numero_exterior"] = $row3a["numero_exterior"];
		$GLOBALS["x_telefono"] = $row3a["telefono"];
		$GLOBALS["x_telefono_secundario"] = $row3a["telefono_secundario"];
		$GLOBALS["x_telefono_movil"] = $row3a["telefono_movil"];
		
		if (!empty($GLOBALS["x_delegacion_id"])){
		$sqlDel = "SELECT * FROM `delegacion` WHERE `delegacion_id` = ".$GLOBALS["x_delegacion_id"]." ";
		$rsDesla = phpmkr_query($sqlDel, $conn) or die ("Error al seleccionar los del".phpmkr_error()."sql:". $sqlDel);
		$rowDela = phpmkr_fetch_array($rsDesla);
		}
		$x_delegacion = $rowDela["descripcion"];
		
		$GLOBALS["x_direccion_dom_th"] = "Calle : ".$GLOBALS["x_calle_domicilio"]." ". $GLOBALS["x_numero_exterior"].", colonia: ".$GLOBALS["x_colonia_domicilio"].", localidad :".$GLOBALS["x_localidad_id"].", delegacion: ".$x_delegacion.", C.P.: ".$GLOBALS["x_codigo_postal_domicilio"]; 
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
			echo "calle negocio ".$x_calle_negocio."<br>";
			
			
			 if(!empty($GLOBALS["x_delegacion_id2"])){
				$sqlDel = "SELECT * FROM `delegacion` WHERE `delegacion_id` = ".$GLOBALS["x_delegacion_id2"]." ";
		$rsDesl = phpmkr_query($sqlDel, $conn) or die ("Error al seleccionar los del".phpmkr_error()."sql:". $sqlDel);
		$rowDel = phpmkr_fetch_array($rsDesl);
			 }
		$x_delegacion1 = $rowDel["descripcion"];
				$GLOBALS["x_direccion_neg_th"] = "Calle : ".$GLOBALS["x_calle_negocio"]." ".$GLOBALS["x_numero_exterior2"].", colonia: ".$GLOBALS["x_colonia_negocio"].", localidad :".$GLOBALS["x_localidad_id2"].", delegacion: ".$x_delegacion1.", C.P.: ".$GLOBALS["x_codigo_postal_negocio"]; 
				
		$x_delegacion= "";		
		//echo $GLOBALS["x_direccion_neg_th"];
		
		
		
		

		
		$x_telefonos = "";
if (!empty($x_telefono_casa_1)){
	$x_telefonos .= $x_telefono_casa_1.", ";
	}
if (!empty($x_telefono_casa_2)){
	$x_telefonos .= $x_telefono_casa_2.", ";
	}
if (!empty($x_telefono_casa_3)){
	$x_telefonos .= $x_telefono_casa_3.", ";
	}
if (!empty($x_telefono_casa_4)){
	$x_telefonos .= $x_telefono_casa_4.", ";
	}
if (!empty($x_telefono_casa_5)){
	$x_telefonos .= $x_telefono_casa_5.", ";
	}
if (!empty($x_telefono_casa_6)){
	$x_telefonos .= $x_telefono_casa_6.", ";
	}
$x_telefonos = $x_telefono. " ". $x_telefono_secundario; 	
$x_celulares = ""; 
if (!empty($x_telefono_celular_1)){
	$x_celulares .= $x_telefono_celular_1.", ";
	}	
if (!empty($x_telefono_celular_2)){
	$x_celulares .= $x_telefono_celular_2.", ";
	}
if (!empty($x_telefono_celular_3)){
	$x_celulares .= $x_telefono_celular_3.", ";
	}
if (!empty($x_telefono_celular_4)){
	$x_celulares .= $x_telefono_celular_4.", ";
	}
if (!empty($x_telefono_celular_5)){
	$x_celulares .= $x_telefono_celular_5.", ";
	}		
				
$x_celulares = $x_telefono_movil;

$x_tabla = '<table align="center" width="700" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td align="center" class="txt_negro_medio" style=" border-bottom: solid 1px #000">DATOS DEL CLIENTE:   '.$x_cuenta.'</td>
</tr>
<tr>
  <td  class="txt_negro_medio">TH DOM:'. $x_direccion_dom_th.'</td>
</tr>
<tr>
  <td  class="txt_negro_medio">TH NEG:'. $x_direccion_neg_th.'</td>
</tr>
<tr>';
 $x_tabla .= '<td  class="txt_negro_medio">Telefonos:  " Casa: "'.$x_telefonos.' " Cel: "'.$x_celulares.'</td>
</tr>
<tr>
  <td  class="txt_negro_medio">&nbsp;</td>
</tr>
</table>';

echo  $x_tabla;
	}
$x_no_integrante++;
}
	
	
	
	
	
	
	}else{?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td colspan="5" align="center" class="txt_negro_medio" style=" border-bottom: solid 1px #000">Datos de Cliente</td>
</tr>
<tr>
  <td colspan="5"  class="txt_negro_medio">TH DOM: <?php echo $x_direccion_dom_th; ?></td>
</tr>
<tr>
  <td colspan="5"  class="txt_negro_medio">TH NEG: <?php echo $x_direccion_neg_th ?></td>
</tr>
<tr>
<?php
$x_telefonos = "";
if (!empty($x_telefono_casa_1)){
	$x_telefonos .= $x_telefono_casa_1.", ";
	}
if (!empty($x_telefono_casa_2)){
	$x_telefonos .= $x_telefono_casa_2.", ";
	}
if (!empty($x_telefono_casa_3)){
	$x_telefonos .= $x_telefono_casa_3.", ";
	}
if (!empty($x_telefono_casa_4)){
	$x_telefonos .= $x_telefono_casa_4.", ";
	}
if (!empty($x_telefono_casa_5)){
	$x_telefonos .= $x_telefono_casa_5.", ";
	}
if (!empty($x_telefono_casa_6)){
	$x_telefonos .= $x_telefono_casa_6.", ";
	}
$x_telefonos = trim($x_telefonos, ", ");	
$x_celulares = ""; 
if (!empty($x_telefono_celular_1)){
	$x_celulares .= $x_telefono_celular_1.", ";
	}	
if (!empty($x_telefono_celular_2)){
	$x_celulares .= $x_telefono_celular_2.", ";
	}
if (!empty($x_telefono_celular_3)){
	$x_celulares .= $x_telefono_celular_3.", ";
	}
if (!empty($x_telefono_celular_4)){
	$x_celulares .= $x_telefono_celular_4.", ";
	}
if (!empty($x_telefono_celular_5)){
	$x_celulares .= $x_telefono_celular_5.", ";
	}				
$x_celulares = trim($x_celulares, ", ");
?>
  <td colspan="5"  class="txt_negro_medio">Telefonos: <?php echo " Casa: ".$x_telefonos. " Cel: ".$x_celulares;  ?></td>
</tr>
<tr>
  <td colspan="5"  class="txt_negro_medio">&nbsp;</td>
</tr>
<tr>
  <td colspan="5" align="center" class="txt_negro_medio" style=" border-bottom: solid 1px #000">Datos de Aval</td>
</tr>
<tr>
  <td colspan="5" class="txt_negro_medio">Aval :<?php echo $x_nombre_aval; ?></td>
</tr>
<tr>
  <td colspan="5"  class="txt_negro_medio">AV DOM: <?php echo $x_direccion_dom_av; ?></td>
</tr>
<tr>
  <td colspan="5"  class="txt_negro_medio">AV NEG: <?php echo $x_direccion_neg_av;?></td>
</tr>
<tr>
<?php
$x_telefono_aval = $x_telefono_p.", ".$x_telefono_o;
$x_telefono_aval =  trim($x_telefono_aval, ", ");
?>
  <td colspan="5"  class="txt_negro_medio">Telefonos: <?php  echo " Casa: ".$x_telefono_aval. " Cel: ".$x_telefono_c. "";?></td>
</tr>
<tr>
  <td colspan="5"  class="txt_negro_medio">&nbsp;</td>
</tr>
</table>
<?php }?>
<table width="700" align="center">
<tr>
  <td colspan="5" align="center" class="txt_negro_medio" style=" border-bottom: solid 1px #000">Estado de Cliente</td>
</tr>
<tr>
  <td colspan="5" align="center" class="txt_negro_medio">&nbsp;</td>
</tr>
<tr>
  <td colspan="5" class="txt_negro_medio">
  
<?php
	$sSqlv = "SELECT * FROM vencimiento WHERE (vencimiento.credito_id = $x_credito_id)  and vencimiento.vencimiento_num < 2000 ORDER BY vencimiento.vencimiento_num+0";  
	$rsv = phpmkr_query($sSqlv,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSqlv);
  
  
?>  
  <table width="700"  align="center" cellpadding="0" cellspacing="0" border="0"> 
    <!-- Table header -->
    <tr class="ewTableHeader">
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
    </tr>
    <?php


$sSqlWrk = "SELECT importe FROM credito where credito_id = $x_credito_id";
$rswrkimp = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrkimp = phpmkr_fetch_array($rswrkimp);
$x_saldo = $datawrkimp["importe"];
@phpmkr_free_result($rswrkimp);

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


while ($rowv = @phpmkr_fetch_array($rsv)) {

		$x_vencimiento_id = $rowv["vencimiento_id"];
		$x_vencimiento_num = $rowv["vencimiento_num"];		
		$x_vencimiento_status_id = $rowv["vencimiento_status_id"];
		
		$x_fecha_vencimiento = $rowv["fecha_vencimiento"];
		$x_importe = $rowv["importe"];
		$x_interes = $rowv["interes"];
		$x_iva = $rowv["iva"];		
		$x_iva_mor = $rowv["iva_mor"];				
		if(empty($x_iva)){
			$x_iva = 0;
		}
		if(empty($x_iva_mor)){
			$x_iva_mor = 0;
		}
		
		$x_interes_moratorio = $rowv["interes_moratorio"];
		
		$x_total = $x_importe + $x_interes + $x_iva + $x_interes_moratorio + $x_iva_mor;

		$x_total_pagos = $x_total_pagos + $x_importe;
		$x_total_interes = $x_total_interes + $x_interes;
		$x_total_iva = $x_total_iva + $x_iva;		
		$x_total_moratorios = $x_total_moratorios + $x_interes_moratorio;
		$x_total_iva_moratorios = $x_total_iva_moratorios + $x_interes_iva_moratorio;		
		$x_total_total = $x_total_total + $x_total;
		
		if(($x_vencimiento_status_id == 2) || ($x_vencimiento_status_id == 5)){
		
			$sSqlWrk = "SELECT fecha_pago, referencia_pago_2 FROM recibo join recibo_vencimiento on recibo_vencimiento.recibo_id = recibo.recibo_id where recibo_vencimiento.vencimiento_id = $x_vencimiento_id and recibo.recibo_status_id = 1";
			$rswrkppp = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$rowwrkppp = phpmkr_fetch_array($rswrkppp);
			$x_fecha_pago = $rowwrkppp["fecha_pago"];
			$x_referencia_pago2 = $rowwrkppp["referencia_pago_2"];			

			@phpmkr_free_result($rswrkppp);

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
		
?>
    <!-- Table body -->
    <tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>
      <!--
<td><span class="phpmaker"><a href="<?php //if ($x_vencimiento_id <> "") {echo "php_reciboadd.php?vencimiento_id=" . urlencode($x_vencimiento_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>" target="_blank">Pagar</a></span></td>
--->
      <td></td>
      <!-- vencimiento_id -->
      <td align="right"> <span class="txt_datos_azul"><?php echo $x_vencimiento_num; ?> </span></td>
      <!-- credito_id -->
      <!-- vencimiento_status_id -->
      <td align="center">
        <span class="txt_datos_azul">
        <?php
if ((!is_null($x_vencimiento_status_id)) && ($x_vencimiento_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `vencimiento_status`";
	$sTmp = $x_vencimiento_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `vencimiento_status_id` = " . $sTmp . "";
	$rswrkv = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrkv && $rowwrk = phpmkr_fetch_array($rswrkv)) {
		$sTmp = $rowwrkv["descripcion"];
	}
	@phpmkr_free_result($rswrkv);
} else {
	$sTmp = "";
}
$ox_vencimiento_status_id = $x_vencimiento_status_id; // Backup Original Value
$x_vencimiento_status_id = $sTmp;
?>
        <?php echo $x_vencimiento_status_id; ?>        
        <?php $x_vencimiento_status_id = $ox_vencimiento_status_id; // Restore Original Value ?>      
        </span></td>
      <!-- fecha_vencimiento -->
      <td align="center"> <span class="txt_datos_azul"><?php echo FormatDateTime($x_fecha_vencimiento,7); ?> </span></td>
      <td align="center"> <span class="txt_datos_azul"><?php echo FormatDateTime($x_fecha_pago,7); ?> </span></td>
      <td align="right"> <span class="txt_datos_azul"><?php echo (is_numeric($x_saldo)) ? FormatNumber($x_saldo,2,0,0,1) : $x_saldo; ?> </span></td>
      <!-- importe -->
      <td align="right"> <span class="txt_datos_azul"><?php echo (is_numeric($x_importe)) ? FormatNumber($x_importe,2,0,0,1) : $x_importe; ?> </span></td>
      <!-- interes -->
      <td align="right"> <span class="txt_datos_azul"><?php echo (is_numeric($x_interes)) ? FormatNumber($x_interes,2,0,0,1) : $x_interes; ?> </span></td>
      <td align="right"><span class="txt_datos_azul"><?php echo (is_numeric($x_iva)) ? FormatNumber($x_iva,2,0,0,1) : $x_iva; ?></span></td>
      <!-- interes_moratorio -->
      <td align="right"> <span class="txt_datos_azul"><?php echo (is_numeric($x_interes_moratorio)) ? FormatNumber($x_interes_moratorio,2,0,0,1) : $x_interes_moratorio; ?> </span></td>
      <td align="right"><span class="txt_datos_azul"><?php echo (is_numeric($x_iva)) ? FormatNumber($x_iva_moratorios,2,0,0,1) : $x_iva; ?></span></td>
      <td align="right"> <span class="txt_datos_azul"><?php echo (is_numeric($x_total)) ? FormatNumber($x_total,2,0,0,1) : $x_total; ?> </span></td>
    </tr>
    <?php
$x_saldo = $x_saldo - $x_importe;
}
?>
    <tr>
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
      <td style=" border-top: solid 1px #000" align="right"> <span class="txt_datos_azul"><b> <?php echo FormatNumber($x_total_pagos,2,0,0,1); ?></b></span></td>
      <td style=" border-top: solid 1px #000" align="right"> <span class="txt_datos_azul"><b> <?php echo FormatNumber($x_total_interes,2,0,0,1); ?></b></span></td>
      <td align="right" class="txt_datos_azul" style=" border-top: solid 1px #000"><b><?php echo FormatNumber($x_total_iva,2,0,0,1); ?></b></td>
      <td style=" border-top: solid 1px #000" align="right"> <span class="txt_datos_azul"><b> <?php echo FormatNumber($x_total_moratorios,2,0,0,1); ?></b></span></td>
      <td align="right" class="txt_datos_azul" style=" border-top: solid 1px #000"><b><?php echo FormatNumber($x_total_iva_moratorios,2,0,0,1); ?></b></td>
      <td style=" border-top: solid 1px #000" align="right"> <span class="txt_datos_azul"><b> <?php echo FormatNumber($x_total_total,2,0,0,1); ?></b></span></td>
    </tr>
    
    <tr >
      <td  colspan="5" align="right" class="txt_negro_medio">SALDO DEUDOR:</td>
      <td  align="right"><span>&nbsp;</span></td>
      <td  align="right"> <span class="txt_datos_azul"><b> <?php echo FormatNumber($x_total_pagos_d,2,0,0,1); ?></b></span></td>
      <td align="right"> <span class="txt_datos_azul"><b> <?php echo FormatNumber($x_total_interes_d,2,0,0,1); ?></b></span></td>
      <td  align="right" class="txt_datos_azul"><b><?php echo FormatNumber($x_total_iva_d,2,0,0,1); ?></b></td>
      <td  align="right"> <span class="txt_datos_azul"><b> <?php echo FormatNumber($x_total_moratorios_d,2,0,0,1); ?></b></span></td>
      <td  align="right" class="txt_datos_azul"><b><?php echo FormatNumber($x_total_iva_moratorios_d,2,0,0,1); ?></b></td>
      <td  align="right"> <span class="txt_datos_azul"><b> <?php echo FormatNumber($x_total_total_d,2,0,0,1); ?></b></span></td>
    </tr>
    <tr>
  <td colspan="5" align="right" class="txt_negro_medio">SALDO VENCIDO:</td>
  <td align="right">&nbsp;</td>
  <td align="right"><span class="txt_datos_azul"><b><?php echo FormatNumber($x_total_pagos_v,2,0,0,1); ?></b></span></td>
  <td align="right"><span class="txt_datos_azul"><b><?php echo FormatNumber($x_total_interes_v,2,0,0,1); ?></b></span></td>
  <td align="right"><span class="txt_datos_azul"><b><?php echo FormatNumber($x_total_iva_v,2,0,0,1); ?></b></span></td>
  <td align="right"><span class="txt_datos_azul"><b><?php echo FormatNumber($x_total_moratorios_v,2,0,0,1); ?></b></span></td>
  <td align="right"><span class="txt_datos_azul"><b><?php echo FormatNumber($x_total_iva_mor_v,2,0,0,1); ?></b></span></td>
  <td align="right"><span class="txt_datos_azul"><b><?php echo FormatNumber($x_total_total_v,2,0,0,1); ?></b></span></td>
</tr>
    <tr>
      <td colspan="5" align="right" class="txt_negro_medio">TOTAL PAGADO:</td>
      <td  align="right">&nbsp;</td>
      <td align="right" class="txt_datos_azul"><b><?php echo FormatNumber($x_total_pagos_a,2,0,0,1); ?></b></td>
      <td align="right" class="txt_datos_azul"><b><?php echo FormatNumber($x_total_interes_a,2,0,0,1); ?></b></td>
      <td align="right" class="txt_datos_azul"><b><?php echo FormatNumber($x_total_iva_a,2,0,0,1); ?></b></td>
      <td align="right" class="txt_datos_azul"><b><?php echo FormatNumber($x_total_moratorios_a,2,0,0,1); ?></b></td>
      <td align="right" class="txt_datos_azul"><b><?php echo FormatNumber($x_total_iva_moratorios_a,2,0,0,1); ?></b></td>
      <td align="right" class="txt_datos_azul"><b><?php echo FormatNumber($x_total_total_a,2,0,0,1); ?></b></td>
    </tr>
  </table></td>
  </tr>
  <tr><td colspan="5">&nbsp;</td></tr>
  <tr><td colspan="5">&nbsp;</td></tr>
  <tr>
    <td colspan="5">
  <table width="700"  align="center" cellpadding="0" cellspacing="0" border="0"> 
    <!-- Table header -->
    <tr class="ewTableHeader">
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
    </tr>
    <?php


$sSqlWrk = "SELECT importe FROM credito where credito_id = $x_credito_id";
$rswrki = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$datawrki = phpmkr_fetch_array($rswrki);
$x_saldo = $datawrki["importe"];
@phpmkr_free_result($rswrki);

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
$rspp = phpmkr_query($sqlPenalizaciones, $conn) or die ("Error".phpmkr_error()."sql".$sqlPenalizaciones);

while ($rowpp = @phpmkr_fetch_array($rspp)) {

		$x_vencimiento_id = $rowpp["vencimiento_id"];
		$x_vencimiento_num = $rowpp["vencimiento_num"];		
		$x_vencimiento_status_id = $rowpp["vencimiento_status_id"];
		
		$x_fecha_vencimiento = $rowpp["fecha_vencimiento"];
		$x_importe = $rowpp["importe"];
		$x_interes = $rowpp["interes"];
		$x_iva = $rowpp["iva"];		
		$x_iva_mor = $rowpp["iva_mor"];				
		if(empty($x_iva)){
			$x_iva = 0;
		}
		if(empty($x_iva_mor)){
			$x_iva_mor = 0;
		}
		
		$x_interes_moratorio = $rowpp["interes_moratorio"];
		
		$x_total = $x_importe + $x_interes + $x_iva + $x_interes_moratorio + $x_iva_mor;

		$x_total_pagos = $x_total_pagos + $x_importe;
		$x_total_interes = $x_total_interes + $x_interes;
		$x_total_iva = $x_total_iva + $x_iva;		
		$x_total_moratorios = $x_total_moratorios + $x_interes_moratorio;
		$x_total_iva_moratorios = $x_total_iva_moratorios + $x_iva_mor;		
		$x_total_total = $x_total_total + $x_total;
		
		if(($x_vencimiento_status_id == 2) || ($x_vencimiento_status_id == 5)){
		
			$sSqlWrk = "SELECT fecha_pago, referencia_pago_2 FROM recibo join recibo_vencimiento on recibo_vencimiento.recibo_id = recibo.recibo_id where recibo_vencimiento.vencimiento_id = $x_vencimiento_id and recibo.recibo_status_id = 1";
			$rswrkf = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
			$rowwrkf = phpmkr_fetch_array($rswrkf);
			$x_fecha_pago = $rowwrkf["fecha_pago"];
			$x_referencia_pago2 = $rowwrkf["referencia_pago_2"];			

			@phpmkr_free_result($rswrkf);

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
		
?>
    <!-- Table body -->
    <tr<?php echo $sItemRowClass; ?><?php echo $sListTrJs; ?>>
      <!--
<td><span class="phpmaker"><a href="<?php //if ($x_vencimiento_id <> "") {echo "php_reciboadd.php?vencimiento_id=" . urlencode($x_vencimiento_id); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>" target="_blank">Pagar</a></span></td>
--->
      <td></td>
      <!-- vencimiento_id -->
      <td align="right"> <span class="txt_datos_azul"><?php echo $x_vencimiento_num; ?> </span></td>
      <!-- credito_id -->
      <!-- vencimiento_status_id -->
      <td align="center">
        <span class="txt_datos_azul">
        <?php
if ((!is_null($x_vencimiento_status_id)) && ($x_vencimiento_status_id <> "")) {
	$sSqlWrk = "SELECT `descripcion` FROM `vencimiento_status`";
	$sTmp = $x_vencimiento_status_id;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `vencimiento_status_id` = " . $sTmp . "";
	$rswrks = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrks && $rowwrks = phpmkr_fetch_array($rswrks)) {
		$sTmp = $rowwrks["descripcion"];
	}
	@phpmkr_free_result($rswrks);
} else {
	$sTmp = "";
}
$ox_vencimiento_status_id = $x_vencimiento_status_id; // Backup Original Value
$x_vencimiento_status_id = $sTmp;
?>
        <?php echo $x_vencimiento_status_id; ?>        
        <?php $x_vencimiento_status_id = $ox_vencimiento_status_id; // Restore Original Value ?>      
        </span></td>
      <!-- fecha_vencimiento -->
      <td align="center"> <span class="txt_datos_azul"><?php echo FormatDateTime($x_fecha_vencimiento,7); ?> </span></td>
      <td align="center"> <span class="txt_datos_azul"><?php echo FormatDateTime($x_fecha_pago,7); ?> </span></td>
      <td align="right"> <span class="txt_datos_azul"><?php echo (is_numeric($x_saldo)) ? FormatNumber($x_saldo,2,0,0,1) : $x_saldo; ?> </span></td>
      <!-- importe -->
      <td align="right"> <span class="txt_datos_azul"><?php echo (is_numeric($x_importe)) ? FormatNumber($x_importe,2,0,0,1) : $x_importe; ?> </span></td>
      <!-- interes -->
      <td align="right"> <span class="txt_datos_azul"><?php echo (is_numeric($x_interes)) ? FormatNumber($x_interes,2,0,0,1) : $x_interes; ?> </span></td>
      <td align="right"><span class="txt_datos_azul"><?php echo (is_numeric($x_iva)) ? FormatNumber($x_iva,2,0,0,1) : $x_iva; ?></span></td>
      <!-- interes_moratorio -->
      <td align="right"> <span class="txt_datos_azul"><?php echo (is_numeric($x_interes_moratorio)) ? FormatNumber($x_interes_moratorio,2,0,0,1) : $x_interes_moratorio; ?> </span></td>
      <td align="right"><span class="txt_datos_azul"><?php echo (is_numeric($x_iva_mor)) ? FormatNumber($x_iva_mor,2,0,0,1) : $x_iva; ?></span></td>
      <td align="right"> <span class="txt_datos_azul"><?php echo (is_numeric($x_total)) ? FormatNumber($x_total,2,0,0,1) : $x_total; ?> </span></td>
    </tr>
    <?php
$x_saldo = $x_saldo - $x_importe;
}
?>
    <tr>
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
      <td style=" border-top: solid 1px #000" align="right"> <span class="txt_datos_azul"><b> <?php echo FormatNumber($x_total_pagos,2,0,0,1); ?></b></span></td>
      <td style=" border-top: solid 1px #000" align="right"> <span class="txt_datos_azul"><b> <?php echo FormatNumber($x_total_interes,2,0,0,1); ?></b></span></td>
      <td align="right" class="txt_datos_azul" style=" border-top: solid 1px #000"><b><?php echo FormatNumber($x_total_iva,2,0,0,1); ?></b></td>
      <td style=" border-top: solid 1px #000" align="right"> <span class="txt_datos_azul"><b> <?php echo FormatNumber($x_total_moratorios,2,0,0,1); ?></b></span></td>
      <td align="right" class="txt_datos_azul" style=" border-top: solid 1px #000"><b><?php echo FormatNumber($x_total_iva_moratorios,2,0,0,1); ?></b></td>
      <td style=" border-top: solid 1px #000" align="right"> <span class="txt_datos_azul"><b> <?php echo FormatNumber($x_total_total,2,0,0,1); ?></b></span></td>
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
  <td colspan="5" class="txt_negro_medio"><h5>Solicitamos su pago a la brevedad, para mayores informes comuniquese a nuestra linea de atencion a clientes en el D.F al 51350259 en el interior de la republica a la linea sin consto 018008376133.</h5></td>
</tr>
</table>


<br style="page-break-before:always" clear="all"> 
<table align="center" width="700" border="0"  id="ewlistmain" class="ewTable" style="font-family:Verdana, Geneva, sans-serif; font-size:12px; border-bottom-color:#CCCCCC;text-align:justify" >
<tr>
<td><strong> Comentarios Internos</strong> Credito No.<strong><?php echo $x_credito_num; ?></strong></td>

<td>  </td>

</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td><?php echo $x_comentario_int;?></td>
<td></td>
</tr>

<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td><strong> Comentarios Externos</strong></td>
<td></td>
</tr>

<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td><?php echo $x_comentario_ext;?></td>
<td></td>

</tr>
</table>
<?php

} // fin del if?>
<?php }?>


<?php
//phpmkr_db_close($conn);
?>
<?php

//-------------------------------------------------------------------------------
// Function LoadData
// - Load Data based on Key Value sKey
// - Variables setup: field variables

function LoadData($conn, $x_credito_id )
{
	 $x_credito_id = $x_credito_id;		


//CREDITO
echo "entro a loas data";

$sSqlI = "select * from credito where credito_id = ".$x_credito_id;
$rsI = phpmkr_query($sSqlI,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSqlI);
$rowI = phpmkr_fetch_array($rsI);
	$GLOBALS["x_credito_id"] = $rowI["credito_id"];
	$GLOBALS["x_credito_num"] = $rowI["credito_num"];		
	$GLOBALS["x_cliente_num"] = $rowI["cliente_num"];				
	$GLOBALS["x_credito_tipo_id"] = $rowI["credito_tipo_id"];
	$GLOBALS["x_solicitud_id"] = $rowI["solicitud_id"];
	$GLOBALS["x_credito_status_id"] = $rowI["credito_status_id"];
	$GLOBALS["x_fecha_otrogamiento"] = $rowI["fecha_otrogamiento"];
	$GLOBALS["x_importe"] = $rowI["importe"];
	$GLOBALS["x_tasa"] = $rowI["tasa"];
	$GLOBALS["x_plazo"] = $rowI["plazo_id"];
	$GLOBALS["x_fecha_vencimiento"] = $rowI["fecha_vencimiento"];
	$GLOBALS["x_tasa_moratoria"] = $rowI["tasa_moratoria"];
	$GLOBALS["x_medio_pago_id"] = $rowI["medio_pago_id"];
	$GLOBALS["x_banco_id"] = $rowI["banco_id"];		
	$GLOBALS["x_referencia_pago"] = $rowI["referencia_pago"];
	$GLOBALS["x_forma_pago_id"] = $rowI["forma_pago_id"];		
	$GLOBALS["x_num_pagos"] = $rowI["num_pagos"];				
	$GLOBALS["x_tdp"] = $rowI["tarjeta_num"];		
	$GLOBALS["credito_tipo_id"] = $rowI["credito_tipo_id"];
	if ($GLOBALS["credito_tipo_id"] == 1){
		$GLOBALS["x_tipo_credito"] = "Individual";
		}else {
			$GLOBALS["x_tipo_credito"] = "Solidario";
			}
	
	
					
// seleccionamos los comentarios..

$sSqlcomentarios = "SELECT * FROM `credito_comment` where credito_id = ".$GLOBALS["x_credito_id"]."";
$rsComentarios = phpmkr_query($sSqlcomentarios, $conn) or die ("Error al seleccionar la bitacora".phpmkr_error()."sql:".$sSqlcomentarios);
$rowComentarios = phpmkr_fetch_array($rsComentarios);
//$GLOBALS["x_credito_id"] = $row["credito_id"];
$GLOBALS["x_comentario_int"] = $rowComentarios["comentario_int"];
$GLOBALS["x_comentario_ext"] = $rowComentarios["comentario_ext"];


if($GLOBALS["credito_tipo_id"] == 1){

//Cuenta
$sSql = "select cliente.* from cliente join solicitud_cliente on solicitud_cliente.cliente_id = cliente.cliente_id join solicitud on solicitud.solicitud_id = solicitud_cliente.solicitud_id 
where solicitud.solicitud_id = ".$GLOBALS["x_solicitud_id"];
echo "sql".$sSql."<br>";
$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row2 = phpmkr_fetch_array($rs2);
	$GLOBALS["x_cuenta"] = $row2["nombre_completo"]." ".$row2["apellido_paterno"]." ".$row2["apellido_materno"];
	$x_c_id = $row2["cliente_id"];
}
// telefonos

$x_count_2 = 1;
if($x_c_id > 0){
		$sSql = "select * from  telefono where cliente_id = $x_c_id  AND telefono_tipo_id = 1 order by telefono_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9 = phpmkr_fetch_array($rs9)){			
			$GLOBALS["x_telefono_casa_$x_count_2"] = $row9["numero"];
			$GLOBALS["x_comentario_casa_$x_count_2"] = $row9["comentario"];
			$GLOBALS["contador_telefono"] = $x_count_2;
			$x_count_2++;
			
		}

		



		$x_count_3 = 1;
		
		$sSql = "select * from  telefono where cliente_id = $x_c_id  AND telefono_tipo_id = 2 order by telefono_id";
		$rs9ea = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9ea = phpmkr_fetch_array($rs9ea)){			
			$GLOBALS["x_telefono_celular_$x_count_3"] = $row9ea["numero"];
			$GLOBALS["x_comentario_celular_$x_count_3"] = $row9ea["comentario"];
			$GLOBALS["x_compania_celular_$x_count_3"] = $row9ea["compania_id"];	
			$GLOBALS["contador_celular"] = $x_count_3;
			$x_count_3++;
			
		}
	

// direcciones


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
		
		
		if (!empty($GLOBALS["x_delegacion_id"])){
		$sqlDel = "SELECT * FROM `delegacion` WHERE `delegacion_id` = ".$GLOBALS["x_delegacion_id"]." ";
		$rsDesl = phpmkr_query($sqlDel, $conn) or die ("Error al seleccionar los del".phpmkr_error()."sql:". $sqlDel);
		$rowDel = phpmkr_fetch_array($rsDesl);
		}
		$x_delegacion = $rowDel["descripcion"];
		
		$GLOBALS["x_direccion_dom_th"] = "Calle : ".$GLOBALS["x_calle_domicilio"]." ". $GLOBALS["x_numero_exterior"].", colonia: ".$GLOBALS["x_colonia_domicilio"].", localidad :".$GLOBALS["x_localidad_id"].", delegacion: ".$x_delegacion.", C.P.: ".$GLOBALS["x_codigo_postal_domicilio"]; 
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
			
			
			 if(!empty($GLOBALS["x_delegacion_id2"])){
				$sqlDel = "SELECT * FROM `delegacion` WHERE `delegacion_id` = ".$GLOBALS["x_delegacion_id2"]." ";
		$rsDesl2 = phpmkr_query($sqlDel, $conn) or die ("Error al seleccionar los del".phpmkr_error()."sql:". $sqlDel);
		$rowDel2 = phpmkr_fetch_array($rsDesl2);
			 }
		$x_delegacion1 = $rowDel2["descripcion"];
				$GLOBALS["x_direccion_neg_th"] = "Calle : ".$GLOBALS["x_calle_negocio"]." ".$GLOBALS["x_numero_exterior2"].", colonia: ".$GLOBALS["x_colonia_negocio"].", localidad :".$GLOBALS["x_localidad_id2"].", delegacion: ".$x_delegacion1.", C.P.: ".$GLOBALS["x_codigo_postal_negocio"]; 
				
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
	@phpmkr_free_result($rswrka);
	    $GLOBALS["x_nombre_completo"] = $rowwrka["nombre_completo"];
		$GLOBALS["x_apellido_paterno"] = $rowwrka["apellido_paterno"];
		$GLOBALS["x_apellido_materno"] = $rowwrka["apellido_materno"];
		$GLOBALS["x_nombre_aval"]= " ". $GLOBALS["x_nombre_completo"] ." ".$GLOBALS["x_apellido_paterno"]." ".$GLOBALS["x_apellido_materno"]." ";
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
		
		$GLOBALS["x_direccion_dom_av"] = "Calle : ".$GLOBALS["x_calle"].", colonia: ".$GLOBALS["x_colonia"].", delegacion: ".$x_delegacion2 ;
		$x_delegacion = "";
		if(!empty($GLOBALS["x_delegacion_2"])){
		$sqlDel = "SELECT * FROM `delegacion` WHERE `delegacion_id` = ".$GLOBALS["x_delegacion_2"]." ";
		$rsDesl = phpmkr_query($sqlDel, $conn) or die ("Error al seleccionar los del".phpmkr_error()."sql:". $sqlDel);
		$rowDel = phpmkr_fetch_array($rsDesl);
		}
		$x_delegacion3 = $rowDel["descripcion"]; 
		$GLOBALS["x_direccion_neg_av"] = "Calle : ".$GLOBALS["x_calle_2"].", colonia: ".$GLOBALS["x_colonia_2"].", delegacion: ".$x_delegacion3; 
$x_delegacion = "";

//promotor

$sSql = "select promotor.nombre_completo from promotor join solicitud on solicitud.promotor_id = promotor.promotor_id where solicitud.solicitud_id = ".$GLOBALS["x_solicitud_id"];
$rs3P = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row3P = phpmkr_fetch_array($rs3P);
$GLOBALS["x_promotor"] = $row3P["nombre_completo"];


//Pagos Vencidos
$sSql = "select count(*) as vencidos from vencimiento
where credito_id = ".$GLOBALS["x_credito_id"]." and vencimiento_status_id = 3 ";
$rs4P = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$row4P = phpmkr_fetch_array($rs4P);
	$GLOBALS["x_pagos_vencidos"] = $row4P["vencidos"];
	
	
	
	
	//INTEGRANTES DEL GRUPO
		$x_soli_id =  $GLOBALS["x_solicitud_id"];
		if($GLOBALS["x_credito_tipo_id"] == 2){
			// ES UN CREDITO SOLIDARIO
			//echo "load data solidario";
			$sqlGrupo = "SELECT * FROM creditosolidario WHERE  solicitud_id = $x_soli_id";
			//echo "sql solidario".$sqlGrupo."<br>";
			$responseGrupo = phpmkr_query($sqlGrupo,$conn) or die ("error al ejecutar query grupo".phpmkr_error()."sql: ".$sqlGrupo);
			$rowGrupo = phpmkr_fetch_array($responseGrupo);
			$GLOBALS["x_creditoSolidario_id"] =  $rowGrupo["creditoSolidario_id"];
			$GLOBALS["x_nombre_grupo"] = $rowGrupo["nombre_grupo"];
			//echo $GLOBALS["x_nombre_grupo"]."..";
			$x_cont_gs = 1;
			while($x_cont_gs <= 10){
				
				$GLOBALS["x_integrante_$x_cont_gs"] = $rowGrupo["integrante_$x_cont_gs"];
				$GLOBLAS["x_nombre_integrante_$x_cont_gs"] = $rowGrupo["integrante_$x_cont_gs"];
				
				//$x_monto_i =  $rowGrupo["monto_$x_cont_g"];
				//$GLOBALS["x_monto_$x_cont_g"] = number_format($x_monto_i);
				
				//echo "integrante".$rowGrupo["integrante_$x_cont_g"]."<br>";
				$GLOBALS["x_monto_$x_cont_gs"] =  $rowGrupo["monto_$x_cont_gs"];
				$GLOBALS["x_rol_integrante_$x_cont_gs"] = $rowGrupo["rol_integrante_$x_cont_gs"]; 
				$GLOBALS["x_cliente_id_$x_cont_gs"] = $rowGrupo["cliente_id_$x_cont_gs"];
				
				//BUSCO AL REPRESENTANTE DEL GRUPO
				if($GLOBALS["x_rol_integrante_$x_cont_gs"] == 1){
					$GLOBALS["$x_representate_grupo"] = $rowGrupo["integrante_$x_cont_gs"];
					$GLOBALS["x_representante_cliente_id"] =  $rowGrupo["cliente_id_$x_cont_gs"];
					}
					
					
					
					
				
				$x_cont_gs++;
				}
			
			
			
			//phpmkr_free_result($rowGrupo);
			
			}


//phpmkr_free_result($rs);
phpmkr_free_result($rs2);
phpmkr_free_result($rs3);
phpmkr_free_result($rs4);

return $bLoadData;
}
?>

