<?php session_start(); ?>
<?php ob_start(); ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Pragma: no-cache"); // HTTP/1.0 
?>
<?php
$ewCurSec = 0; // Initialise

// User levels
define("ewAllowAdd", 1, true);
define("ewAllowDelete", 2, true);
define("ewAllowEdit", 4, true);
define("ewAllowView", 8, true);
define("ewAllowList", 8, true);
define("ewAllowReport", 8, true);
define("ewAllowSearch", 8, true);																														
define("ewAllowAdmin", 16, true);						
?>
<?php

$x_datos_aval_id = $_GET["datos_aval_id"];


$sKey = $_GET["datos_aval_id"];

// Initialize common variables
$x_datos_aval_id = Null;
$x_cliente_id = Null;
$x_nombre_completo = Null;
$x_apellido_paterno = Null;
$x_apellido_materno = Null;
$x_rfc = Null;
$x_curp = Null;
$x_fecha_nacimiento = Null;
$x_sexo = Null;
$x_integrantes_familia = Null;
$x_dependientes = Null;
$x_correo_electronico = Null;
$x_nombre_conyuge = Null;
$x_calle = Null;
$x_colonia = Null;
$x_entidad = Null;
$x_codigo_postal = Null;
$x_delegacion = Null;
$x_vivienda_tipo = Null;
$x_telefono_p = Null;
$x_telefono_c = Null;
$x_telefono_o = Null;
$x_antiguedad_v = Null;
$x_tel_arrendatario_v = Null;
$x_renta_mensual_v = Null;
$x_calle_2 = Null;
$x_colonia_2 = Null;
$x_entidad_2 = Null;
$x_codigo_postal_2 = Null;
$x_delegacion_2 = Null;
$x_vivienda_tipo_2 = Null;
$x_telefono_p_2 = Null;
$x_telefono_c_2 = Null;
$x_telefono_o_2 = Null;
$x_antiguedad_n = Null;
$x_tel_arrendatario_n = Null;
$x_garantia_desc = Null;
$x_garantia_valor = Null;
$x_renta_mesual_n = Null;
$x_referencia_1 = Null;
$x_referencia_2 = Null;
$x_referencia_3 = Null;
$x_referencia_4 = Null;
$x_telefono_ref_1 = Null;
$x_telefono_ref_2 = Null;
$x_telefono_ref_3 = Null;
$x_telefono_ref_4 = Null;
$x_relacion_1 = Null;
$x_relacion_2 = Null;
$x_relacion_3 = Null;
$x_relacion_4 = Null;
$x_ing_fam_negocio = Null;
$x_ing_fam_otro_th = Null;
$x_ing_fam_1 = Null;
$x_ing_fam_2 = Null;
$x_ing_fam_deuda_1 = Null;
$x_ing_fam_deuda_2 = Null;
$x_ing_fam_total = Null;
$x_ing_fam_cuales_1 = Null;
$x_ing_fam_cuales_2 = Null;
$x_ing_fam_cuales_13 = Null;
$x_ing_fam_cuales_4 = Null;
$x_ing_fam_cuales_5 = Null;
$x_flujos_neg_ventas = Null;
$x_flujos_neg_proveedor_1 = Null;
$x_flujos_neg_proveedor_2 = Null;
$x_flujos_neg_proveedor_3 = Null;
$x_flujos_neg_proveedor_4 = Null;
$x_flujos_neg_gasto_1 = Null;
$x_flujos_neg_gasto_2 = Null;
$x_flujos_neg_gasto_3 = Null;
$x_flujos_neg_cual_1 = Null;
$x_flujos_neg_cual_2 = Null;
$x_flujos_neg_cual_3 = Null;
$x_flujos_neg_cual_4 = Null;
$x_flujos_neg_cual_5 = Null;
$x_flujos_neg_cual_6 = Null;
$x_flujos_neg_cual_7 = Null;
$x_ingreso_negocio = Null;
$x_inv_neg_fija_conc_1 = Null;
$x_inv_neg_fija_conc_2 = Null;
$x_inv_neg_fija_conc_3 = Null;
$x_inv_neg_fija_conc_4 = Null;
$x_inv_neg_fija_valor_1 = Null;
$x_inv_neg_fija_valor_2 = Null;
$x_inv_neg_fija_valor_3 = Null;
$x_inv_neg_fija_valor_4 = Null;
$x_inv_neg_total_fija = Null;
$x_inv_neg_var_conc_1 = Null;
$x_inv_neg_var_conc_2 = Null;
$x_inv_neg_var_conc_3 = Null;
$x_inv_neg_var_conc_4 = Null;
$x_inv_neg_var_valor_1 = Null;
$x_inv_neg_var_valor_2 = Null;
$x_inv_neg_var_valor_3 = Null;
$x_inv_neg_var_valor_4 = Null;
$x_inv_neg_total_var = Null;
$x_inv_neg_activos_totales = Null;
?>
<?php include("../../../db.php");?>

<?php include ("../../../phpmkrfn.php") ?>
<?php
$conn = phpmkr_db_connect(HOST, USER, PASS,DB,PORT);
// Get action
$sAction = @$_POST["a_add"];
if (($sAction == "") || ((is_null($sAction)))) {
	
		$sAction = "I"; // Display blank record
	}

else
{

	// Get fields from form
	$x_datos_aval_id = @$_POST["x_datos_aval_id"];
	$x_cliente_id = @$_POST["x_cliente_id"];
	$x_solicitud_id = @$_POST["x_solicitud_id"];
	$x_nombre_completo = @$_POST["x_nombre_completo"];
	$x_apellido_paterno = @$_POST["x_apellido_paterno"];
	$x_apellido_materno = @$_POST["x_apellido_materno"];
	$x_rfc = @$_POST["x_rfc"];
	$x_curp = @$_POST["x_curp"];
	$x_fecha_nacimiento = @$_POST["x_fecha_nacimiento"];
	$x_sexo = @$_POST["x_sexo"];
	$x_integrantes_familia = @$_POST["x_integrantes_familia"];
	$x_dependientes = @$_POST["x_dependientes"];
	$x_correo_electronico = @$_POST["x_correo_electronico"];
	$x_nombre_conyuge = @$_POST["x_nombre_conyuge"];
	$x_calle = @$_POST["x_calle"];
	$x_colonia = @$_POST["x_colonia"];
	$x_entidad = @$_POST["x_entidad"];
	$x_codigo_postal = @$_POST["x_codigo_postal"];
	$x_delegacion = @$_POST["x_delegacion"];
	$x_vivienda_tipo = @$_POST["x_vivienda_tipo"];
	$x_telefono_p = @$_POST["x_telefono_p"];
	$x_telefono_c = @$_POST["x_telefono_c"];
	$x_telefono_o = @$_POST["x_telefono_o"];
	$x_antiguedad_v = @$_POST["x_antiguedad_v"];
	$x_tel_arrendatario_v = @$_POST["x_tel_arrendatario_v"];
	$x_renta_mensual_v = @$_POST["x_renta_mensual_v"];
	$x_calle_2 = @$_POST["x_calle_2"];
	$x_colonia_2 = @$_POST["x_colonia_2"];
	$x_entidad_2 = @$_POST["x_entidad_2"];
	$x_codigo_postal_2 = @$_POST["x_codigo_postal_2"];
	$x_delegacion_2 = @$_POST["x_delegacion_2"];
	$x_vivienda_tipo_2 = @$_POST["x_vivienda_tipo_2"];
	$x_telefono_p_2 = @$_POST["x_telefono_p_2"];
	$x_telefono_c_2 = @$_POST["x_telefono_c_2"];
	$x_telefono_o_2 = @$_POST["x_telefono_o_2"];
	$x_antiguedad_n = @$_POST["x_antiguedad_n"];
	$x_tel_arrendatario_n = @$_POST["x_tel_arrendatario_n"];
	$x_garantia_desc = @$_POST["x_garantia_desc"];
	$x_garantia_valor = @$_POST["x_garantia_valor"];
	$x_renta_mesual_n = @$_POST["x_renta_mesual_n"];
	$x_referencia_1 = @$_POST["x_referencia_1"];
	$x_referencia_2 = @$_POST["x_referencia_2"];
	$x_referencia_3 = @$_POST["x_referencia_3"];
	$x_referencia_4 = @$_POST["x_referencia_4"];
	$x_telefono_ref_1 = @$_POST["x_telefono_ref_1"];
	$x_telefono_ref_2 = @$_POST["x_telefono_ref_2"];
	$x_telefono_ref_3 = @$_POST["x_telefono_ref_3"];
	$x_telefono_ref_4 = @$_POST["x_telefono_ref_4"];
	$x_relacion_1 = @$_POST["x_relacion_1"];
	$x_relacion_2 = @$_POST["x_relacion_2"];
	$x_relacion_3 = @$_POST["x_relacion_3"];
	$x_relacion_4 = @$_POST["x_relacion_4"];
	$x_ing_fam_negocio = @$_POST["x_ing_fam_negocio"];
	$x_ing_fam_otro_th = @$_POST["x_ing_fam_otro_th"];
	$x_ing_fam_1 = @$_POST["x_ing_fam_1"];
	$x_ing_fam_2 = @$_POST["x_ing_fam_2"];
	$x_ing_fam_deuda_1 = @$_POST["x_ing_fam_deuda_1"];
	$x_ing_fam_deuda_2 = @$_POST["x_ing_fam_deuda_2"];
	$x_ing_fam_total = @$_POST["x_ing_fam_total"];
	$x_ing_fam_cuales_1 = @$_POST["x_ing_fam_cuales_1"];
	$x_ing_fam_cuales_2 = @$_POST["x_ing_fam_cuales_2"];
	$x_ing_fam_cuales_13 = @$_POST["x_ing_fam_cuales_13"];
	$x_ing_fam_cuales_4 = @$_POST["x_ing_fam_cuales_4"];
	$x_ing_fam_cuales_5 = @$_POST["x_ing_fam_cuales_5"];
	$x_flujos_neg_ventas = @$_POST["x_flujos_neg_ventas"];
	$x_flujos_neg_proveedor_1 = @$_POST["x_flujos_neg_proveedor_1"];
	$x_flujos_neg_proveedor_2 = @$_POST["x_flujos_neg_proveedor_2"];
	$x_flujos_neg_proveedor_3 = @$_POST["x_flujos_neg_proveedor_3"];
	$x_flujos_neg_proveedor_4 = @$_POST["x_flujos_neg_proveedor_4"];
	$x_flujos_neg_gasto_1 = @$_POST["x_flujos_neg_gasto_1"];
	$x_flujos_neg_gasto_2 = @$_POST["x_flujos_neg_gasto_2"];
	$x_flujos_neg_gasto_3 = @$_POST["x_flujos_neg_gasto_3"];
	$x_flujos_neg_cual_1 = @$_POST["x_flujos_neg_cual_1"];
	$x_flujos_neg_cual_2 = @$_POST["x_flujos_neg_cual_2"];
	$x_flujos_neg_cual_3 = @$_POST["x_flujos_neg_cual_3"];
	$x_flujos_neg_cual_4 = @$_POST["x_flujos_neg_cual_4"];
	$x_flujos_neg_cual_5 = @$_POST["x_flujos_neg_cual_5"];
	$x_flujos_neg_cual_6 = @$_POST["x_flujos_neg_cual_6"];
	$x_flujos_neg_cual_7 = @$_POST["x_flujos_neg_cual_7"];
	$x_ingreso_negocio = @$_POST["x_ingreso_negocio"];
	$x_inv_neg_fija_conc_1 = @$_POST["x_inv_neg_fija_conc_1"];
	$x_inv_neg_fija_conc_2 = @$_POST["x_inv_neg_fija_conc_2"];
	$x_inv_neg_fija_conc_3 = @$_POST["x_inv_neg_fija_conc_3"];
	$x_inv_neg_fija_conc_4 = @$_POST["x_inv_neg_fija_conc_4"];
	$x_inv_neg_fija_valor_1 = @$_POST["x_inv_neg_fija_valor_1"];
	$x_inv_neg_fija_valor_2 = @$_POST["x_inv_neg_fija_valor_2"];
	$x_inv_neg_fija_valor_3 = @$_POST["x_inv_neg_fija_valor_3"];
	$x_inv_neg_fija_valor_4 = @$_POST["x_inv_neg_fija_valor_4"];
	$x_inv_neg_total_fija = @$_POST["x_inv_neg_total_fija"];
	$x_inv_neg_var_conc_1 = @$_POST["x_inv_neg_var_conc_1"];
	$x_inv_neg_var_conc_2 = @$_POST["x_inv_neg_var_conc_2"];
	$x_inv_neg_var_conc_3 = @$_POST["x_inv_neg_var_conc_3"];
	$x_inv_neg_var_conc_4 = @$_POST["x_inv_neg_var_conc_4"];
	$x_inv_neg_var_valor_1 = @$_POST["x_inv_neg_var_valor_1"];
	$x_inv_neg_var_valor_2 = @$_POST["x_inv_neg_var_valor_2"];
	$x_inv_neg_var_valor_3 = @$_POST["x_inv_neg_var_valor_3"];
	$x_inv_neg_var_valor_4 = @$_POST["x_inv_neg_var_valor_4"];
	$x_inv_neg_total_var = @$_POST["x_inv_neg_total_var"];
	$x_inv_neg_activos_totales = @$_POST["x_inv_neg_activos_totales"];
	
	$x_giro_negocio = @$_POST["x_giro_negocio"];
	$x_ubicacion = @$_POST["x_ubicacion"];
	$x_ubicacion_2 = @$_POST["x_ubicacion_2"];
	
	//comentrios
	$x_comentario_promotor = @$_POST["x_comentario_promotor"];
	$x_comentario_comite = @$_POST["x_comentario_comite"];
}

switch ($sAction)
{


case "I": // Get a record to display
		
		
		if (LoadData($sKey,$conn)) { // Load Record based on key
			
			phpmkr_db_close($conn);
			//ob_end_clean();
			//header("Location: datos_avallist.php");
		}
		break;
	case "A": // Add
	
	
		if (EditData($conn)) { // Add New Record
			$x_mensaje = "<p align='center'>Los datos del cliente han sido registrados.</p>";
			$x_mensaje .= "
			<script type='text/javascript'>
			<!--
				window.opener.document.frm_visor.submit();
			//-->
			</script>";
			
			
			
		}
		break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />

<link href="../../../php_project_esf.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>

<script language="javascript">
function cargaEventos(){
	
	
	
	
	//alert("Credito para Adquisicion de Maquinaria")
	 //ACTUALIZA EL CONTENIDO DEL FORMATO ADQUISICION DE MAQUINARIA
	 //0document.getElementById("siguiente").onclick = muestraOculta;
	//document.getElementById("anterior").onclick = muestraOculta;
	//document.getElementById("enviar").onclick = EW_checkMyForm;
	//document.getElementById("seEnvioFormulario").onclick = ocultaMens;
	
	document.getElementById("x_inv_neg_var_valor_1").onchange = totalVarible;
	document.getElementById("x_inv_neg_var_valor_2").onchange = totalVarible;
	document.getElementById("x_inv_neg_var_valor_3").onchange = totalVarible;
	document.getElementById("x_inv_neg_var_valor_4").onchange = totalVarible;
	document.getElementById("x_inv_neg_fija_valor_1").onchange= totalFija;
	document.getElementById("x_inv_neg_fija_valor_2").onchange = totalFija;
	document.getElementById("x_inv_neg_fija_valor_3").onchange = totalFija;
	document.getElementById("x_inv_neg_fija_valor_4").onchange = totalFija;
	document.getElementById("x_ing_fam_negocio").onchange = ingresoFamiliar;
	document.getElementById("x_ing_fam_otro_th").onchange = ingresoFamiliar;
	document.getElementById("x_ing_fam_1").onchange = ingresoFamiliar;
	document.getElementById("x_ing_fam_2").onchange = ingresoFamiliar;
	document.getElementById("x_ing_fam_deuda_1").onchange = ingresoFamiliar;
	document.getElementById("x_ing_fam_deuda_2").onchange = ingresoFamiliar;
	document.getElementById("x_flujos_neg_ventas").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_proveedor_1").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_proveedor_2").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_proveedor_3").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_proveedor_4").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_gasto_1").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_gasto_2").onchange = flujosDeNegocio;
	document.getElementById("x_flujos_neg_gasto_3").onchange = flujosDeNegocio;
	
	 
	
	
function muestraOculta(){
 	var id = this.id; 
 	if(id =="siguiente"){
		document.getElementById("paginaUno").style.display ="none";
		document.getElementById("paginaDos").style.display ="block";
		document.getElementById("siguiente").style.display="none";
		document.getElementById("anterior").style.display="block";	
		} else{
			document.getElementById("paginaUno").style.display ="block";
			document.getElementById("paginaDos").style.display ="none";
			document.getElementById("anterior").style.display="none";
			document.getElementById("siguiente").style.display="block";
			}
 }//function muestraOculta 
	 
		function totalVarible (){
			t1 = document.getElementById("x_inv_neg_var_valor_1").value;
			t2 = document.getElementById("x_inv_neg_var_valor_2").value;
			t3 = document.getElementById("x_inv_neg_var_valor_3").value;
			t4 = document.getElementById("x_inv_neg_var_valor_4").value;
			total = 0;
			
			if(t1 == '')
				n1 = 0;
				else
				n1 = parseFloat(document.getElementById("x_inv_neg_var_valor_1").value);
				
			if(t2 == '')
				n2 = 0;
				else
				n2 = parseFloat(document.getElementById("x_inv_neg_var_valor_2").value);
				
			if(t3 == '')
				n3 = 0;
				else
				n3 = parseFloat(document.getElementById("x_inv_neg_var_valor_3").value);
				
			if(t4 == '')
				n4 = 0;
				else
				n4 = parseFloat(document.getElementById("x_inv_neg_var_valor_4").value);
			
			suma = n1 + n2 + n3 + n4;
			
			tempT = document.getElementById("x_inv_neg_total_fija").value;
			
			if(tempT == '')
				nt = 0;
				else
				nt = parseFloat(document.getElementById("x_inv_neg_total_fija").value);
			
			
			document.getElementById("x_inv_neg_total_var").value = suma;
			document.getElementById("x_inv_neg_activos_totales").value = (suma + nt);
			
			}
	 
	     
	 
	function totalFija (){
			t1 = document.getElementById("x_inv_neg_fija_valor_1").value;
			t2 = document.getElementById("x_inv_neg_fija_valor_2").value;
			t3 = document.getElementById("x_inv_neg_fija_valor_3").value;
			t4 = document.getElementById("x_inv_neg_fija_valor_4").value;
			total = 0;
			
			if(t1 == '')
				n1 = 0;
				else
				n1 = parseFloat(document.getElementById("x_inv_neg_fija_valor_1").value);
				
			if(t2 == '')
				n2 = 0;
				else
				n2 = parseFloat(document.getElementById("x_inv_neg_fija_valor_2").value);
				
			if(t3 == '')
				n3 = 0;
				else
				n3 = parseFloat(document.getElementById("x_inv_neg_fija_valor_3").value);
				
			if(t4 == '')
				n4 = 0;
				else
				n4 = parseFloat(document.getElementById("x_inv_neg_fija_valor_4").value);
			
			suma = n1 + n2 + n3 + n4;
			
			tempT = document.getElementById("x_inv_neg_total_var").value;
			
			if(tempT == '')
				nt = 0;
				else
				nt = parseFloat(document.getElementById("x_inv_neg_total_var").value);
			
			
			document.getElementById("x_inv_neg_total_fija").value = suma;
			document.getElementById("x_inv_neg_activos_totales").value = (suma + nt);
			
			}	
			
			function ingresoFamiliar(){
				t1 = document.getElementById("x_ing_fam_negocio").value;
				t2 = document.getElementById("x_ing_fam_otro_th").value;
				t3 = document.getElementById("x_ing_fam_1").value;
				t4 = document.getElementById("x_ing_fam_2").value;
				t5 = document.getElementById("x_ing_fam_deuda_1").value;
				t6 = document.getElementById("x_ing_fam_deuda_2").value;
				
				if(t1 == '')
				n1 = 0;
				else
				n1 = parseFloat(document.getElementById("x_ing_fam_negocio").value);
				
				if(t2 == '')
				n2 = 0;
				else
				n2 = parseFloat(document.getElementById("x_ing_fam_otro_th").value);
				
				
				if(t3 == '')
				n3 = 0;
				else
				n3 = parseFloat(document.getElementById("x_ing_fam_1").value);
				
				if(t4 == '')
				n4 = 0;
				else
				n4 = parseFloat(document.getElementById("x_ing_fam_2").value);
				
				if(t5 == '')
				n5 = 0;
				else
				n5 = parseFloat(document.getElementById("x_ing_fam_deuda_1").value);
				
				
				if(t6 == '')
				n6 = 0;
				else
				n6 = parseFloat(document.getElementById("x_ing_fam_deuda_2").value);
				
				total =(n1 + n2 +n3 +n4)-(n5 + n6);	
				
				
				document.getElementById("x_ing_fam_total").value = total;			
				
				}
				
				function flujosDeNegocio (){
					
					t1 = document.getElementById("x_flujos_neg_ventas").value;
					t2 = document.getElementById("x_flujos_neg_proveedor_1").value;
					t3 = document.getElementById("x_flujos_neg_proveedor_2").value;
					t4 = document.getElementById("x_flujos_neg_proveedor_3").value;
					t5 = document.getElementById("x_flujos_neg_proveedor_4").value;
					t6 = document.getElementById("x_flujos_neg_gasto_1").value;
					t7 = document.getElementById("x_flujos_neg_gasto_2").value;
					t8 = document.getElementById("x_flujos_neg_gasto_3").value;
					total = 0;
					
					if(t1 == '')
					n1 = 0;
					else
					n1 = parseFloat(document.getElementById("x_flujos_neg_ventas").value);
					
					if(t2 == '')
					n2 = 0;
					else
					n2 = parseFloat(document.getElementById("x_flujos_neg_proveedor_1").value);
					
					if(t3 == '')
					n3 = 0;
					else
					n3 = parseFloat(document.getElementById("x_flujos_neg_proveedor_2").value);
					
					if(t4 == '')
					n4 = 0;
					else
					n4 = parseFloat(document.getElementById("x_flujos_neg_proveedor_3").value);
					
					if(t5 == '')
					n5 = 0;
					else
					n5 = parseFloat(document.getElementById("x_flujos_neg_proveedor_4").value);
					
					if(t6 == '')
					n6 = 0;
					else
					n6 = parseFloat(document.getElementById("x_flujos_neg_gasto_1").value);
					
					if(t7 == '')
					n7 = 0;
					else
					n7 = parseFloat(document.getElementById("x_flujos_neg_gasto_2").value);
					
					if(t8 == '')
					n8 = 0;
					else
					n8 = parseFloat(document.getElementById("x_flujos_neg_gasto_3").value);
					
					
					total = ((n1)-(n2 + n3 + n4 + n5 +n6 +n7 +n8));					
					
					 document.getElementById("x_ingreso_negocio").value = total;
					 }
	
	

vact = document.solicitudedit.x_actividad_id.value;

	if(vact == 1){
		document.getElementById("actividad1").className = "TG_visible";
		document.getElementById("actividad2").className = "TG_hidden";
		document.getElementById("actividad3").className = "TG_hidden";				
	}
	if(vact == 2){
		document.getElementById("actividad1").className = "TG_hidden";
		document.getElementById("actividad2").className = "TG_visible";
		document.getElementById("actividad3").className = "TG_hidden";				
	}
	if(vact == 3){
		document.getElementById("actividad1").className = "TG_hidden";
		document.getElementById("actividad2").className = "TG_hidden";
		document.getElementById("actividad3").className = "TG_visible";				
	}

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	}// fin carga eventos

</script>
<script type="text/javascript">
<!--
function EW_checkMyForm(EW_this) {
if (EW_this.x_cliente_id && !EW_hasValue(EW_this.x_cliente_id, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_cliente_id, "TEXT", "Please enter required field - cliente id"))
		return false;
}
if (EW_this.x_cliente_id && !EW_checkinteger(EW_this.x_cliente_id.value)) {
	if (!EW_onError(EW_this, EW_this.x_cliente_id, "TEXT", "Incorrect integer - cliente id"))
		return false; 
}
if (EW_this.x_nombre_completo && !EW_hasValue(EW_this.x_nombre_completo, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_nombre_completo, "TEXT", "Please enter required field - nombre completo"))
		return false;
}
if (EW_this.x_apellido_paterno && !EW_hasValue(EW_this.x_apellido_paterno, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_apellido_paterno, "TEXT", "Please enter required field - apellido paterno"))
		return false;
}
if (EW_this.x_fecha_nacimiento && !EW_hasValue(EW_this.x_fecha_nacimiento, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_fecha_nacimiento, "TEXT", "Please enter required field - fecha nacimiento"))
		return false;
}
/*
if (EW_this.x_fecha_nacimiento && !EW_checkdate(EW_this.x_fecha_nacimiento.value)) {
	if (!EW_onError(EW_this, EW_this.x_fecha_nacimiento, "TEXT", "Incorrect date, format = yyyy/mm/dd - fecha nacimiento"))
		return false; 
}*/
if (EW_this.x_sexo && !EW_hasValue(EW_this.x_sexo, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_sexo, "TEXT", "Please enter required field - sexo"))
		return false;
}
if (EW_this.x_integrantes_familia && !EW_checkinteger(EW_this.x_integrantes_familia.value)) {
	if (!EW_onError(EW_this, EW_this.x_integrantes_familia, "TEXT", "Incorrect integer - integrantes familia"))
		return false; 
}
if (EW_this.x_dependientes && !EW_checkinteger(EW_this.x_dependientes.value)) {
	if (!EW_onError(EW_this, EW_this.x_dependientes, "TEXT", "Incorrect integer - dependientes"))
		return false; 
}
if (EW_this.x_calle && !EW_hasValue(EW_this.x_calle, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_calle, "TEXT", "Please enter required field - calle"))
		return false;
}
if (EW_this.x_colonia && !EW_hasValue(EW_this.x_colonia, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_colonia, "TEXT", "Please enter required field - colonia"))
		return false;
}
if (EW_this.x_entidad && !EW_hasValue(EW_this.x_entidad, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_entidad, "TEXT", "Please enter required field - entidad"))
		return false;
}
if (EW_this.x_entidad && !EW_checkinteger(EW_this.x_entidad.value)) {
	if (!EW_onError(EW_this, EW_this.x_entidad, "TEXT", "Incorrect integer - entidad"))
		return false; 
}
if (EW_this.x_codigo_postal && !EW_checkinteger(EW_this.x_codigo_postal.value)) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal, "TEXT", "Incorrect integer - codigo postal"))
		return false; 
}
if (EW_this.x_delegacion && !EW_checkinteger(EW_this.x_delegacion.value)) {
	if (!EW_onError(EW_this, EW_this.x_delegacion, "TEXT", "Incorrect integer - delegacion"))
		return false; 
}
if (EW_this.x_vivienda_tipo && !EW_checkinteger(EW_this.x_vivienda_tipo.value)) {
	if (!EW_onError(EW_this, EW_this.x_vivienda_tipo, "TEXT", "Incorrect integer - vivienda tipo"))
		return false; 
}
if (EW_this.x_telefono_p && !EW_hasValue(EW_this.x_telefono_p, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_telefono_p, "TEXT", "Please enter required field - telefono p"))
		return false;
}
if (EW_this.x_antiguedad_v && !EW_checkinteger(EW_this.x_antiguedad_v.value)) {
	if (!EW_onError(EW_this, EW_this.x_antiguedad_v, "TEXT", "Incorrect integer - antiguedad v"))
		return false; 
}
if (EW_this.x_renta_mensual_v && !EW_checknumber(EW_this.x_renta_mensual_v.value)) {
	if (!EW_onError(EW_this, EW_this.x_renta_mensual_v, "TEXT", "Incorrect floating point number - renta mensual v"))
		return false; 
}
if (EW_this.x_entidad_2 && !EW_checkinteger(EW_this.x_entidad_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_entidad_2, "TEXT", "Incorrect integer - entidad 2"))
		return false; 
}
if (EW_this.x_codigo_postal_2 && !EW_checkinteger(EW_this.x_codigo_postal_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_codigo_postal_2, "TEXT", "Incorrect integer - codigo postal 2"))
		return false; 
}
if (EW_this.x_delegacion_2 && !EW_checkinteger(EW_this.x_delegacion_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_delegacion_2, "TEXT", "Incorrect integer - delegacion 2"))
		return false; 
}
if (EW_this.x_vivienda_tipo_2 && !EW_checkinteger(EW_this.x_vivienda_tipo_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_vivienda_tipo_2, "TEXT", "Incorrect integer - vivienda tipo 2"))
		return false; 
}
if (EW_this.x_antiguedad_n && !EW_checkinteger(EW_this.x_antiguedad_n.value)) {
	if (!EW_onError(EW_this, EW_this.x_antiguedad_n, "TEXT", "Incorrect integer - antiguedad n"))
		return false; 
}
if (EW_this.x_garantia_valor && !EW_checknumber(EW_this.x_garantia_valor.value)) {
	if (!EW_onError(EW_this, EW_this.x_garantia_valor, "TEXT", "Incorrect floating point number - garantia valor"))
		return false; 
}
if (EW_this.x_renta_mesual_n && !EW_checknumber(EW_this.x_renta_mesual_n.value)) {
	if (!EW_onError(EW_this, EW_this.x_renta_mesual_n, "TEXT", "Incorrect floating point number - renta mesual n"))
		return false; 
}
if (EW_this.x_relacion_1 && !EW_checkinteger(EW_this.x_relacion_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_relacion_1, "TEXT", "Incorrect integer - relacion 1"))
		return false; 
}
if (EW_this.x_relacion_2 && !EW_checkinteger(EW_this.x_relacion_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_relacion_2, "TEXT", "Incorrect integer - relacion 2"))
		return false; 
}
if (EW_this.x_relacion_3 && !EW_checkinteger(EW_this.x_relacion_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_relacion_3, "TEXT", "Incorrect integer - relacion 3"))
		return false; 
}
if (EW_this.x_relacion_4 && !EW_checkinteger(EW_this.x_relacion_4.value)) {
	if (!EW_onError(EW_this, EW_this.x_relacion_4, "TEXT", "Incorrect integer - relacion 4"))
		return false; 
}
if (EW_this.x_ing_fam_negocio && !EW_checknumber(EW_this.x_ing_fam_negocio.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_negocio, "TEXT", "Incorrect floating point number - ing fam negocio"))
		return false; 
}
if (EW_this.x_ing_fam_otro_th && !EW_checknumber(EW_this.x_ing_fam_otro_th.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_otro_th, "TEXT", "Incorrect floating point number - ing fam otro th"))
		return false; 
}
if (EW_this.x_ing_fam_1 && !EW_checknumber(EW_this.x_ing_fam_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_1, "TEXT", "Incorrect floating point number - ing fam 1"))
		return false; 
}
if (EW_this.x_ing_fam_2 && !EW_checknumber(EW_this.x_ing_fam_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_2, "TEXT", "Incorrect floating point number - ing fam 2"))
		return false; 
}
if (EW_this.x_ing_fam_deuda_1 && !EW_checknumber(EW_this.x_ing_fam_deuda_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_1, "TEXT", "Incorrect floating point number - ing fam deuda 1"))
		return false; 
}
if (EW_this.x_ing_fam_deuda_2 && !EW_checknumber(EW_this.x_ing_fam_deuda_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_deuda_2, "TEXT", "Incorrect floating point number - ing fam deuda 2"))
		return false; 
}
if (EW_this.x_ing_fam_total && !EW_checknumber(EW_this.x_ing_fam_total.value)) {
	if (!EW_onError(EW_this, EW_this.x_ing_fam_total, "TEXT", "Incorrect floating point number - ing fam total"))
		return false; 
}
if (EW_this.x_flujos_neg_ventas && !EW_checknumber(EW_this.x_flujos_neg_ventas.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_ventas, "TEXT", "Incorrect floating point number - flujos neg ventas"))
		return false; 
}
if (EW_this.x_flujos_neg_proveedor_1 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_1, "TEXT", "Incorrect floating point number - flujos neg proveedor 1"))
		return false; 
}
if (EW_this.x_flujos_neg_proveedor_2 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_2, "TEXT", "Incorrect floating point number - flujos neg proveedor 2"))
		return false; 
}
if (EW_this.x_flujos_neg_proveedor_3 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_3, "TEXT", "Incorrect floating point number - flujos neg proveedor 3"))
		return false; 
}
if (EW_this.x_flujos_neg_proveedor_4 && !EW_checknumber(EW_this.x_flujos_neg_proveedor_4.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_proveedor_4, "TEXT", "Incorrect floating point number - flujos neg proveedor 4"))
		return false; 
}
if (EW_this.x_flujos_neg_gasto_1 && !EW_checknumber(EW_this.x_flujos_neg_gasto_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_1, "TEXT", "Incorrect floating point number - flujos neg gasto 1"))
		return false; 
}
if (EW_this.x_flujos_neg_gasto_2 && !EW_checknumber(EW_this.x_flujos_neg_gasto_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_2, "TEXT", "Incorrect floating point number - flujos neg gasto 2"))
		return false; 
}
if (EW_this.x_flujos_neg_gasto_3 && !EW_checknumber(EW_this.x_flujos_neg_gasto_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_flujos_neg_gasto_3, "TEXT", "Incorrect floating point number - flujos neg gasto 3"))
		return false; 
}
if (EW_this.x_ingreso_negocio && !EW_checknumber(EW_this.x_ingreso_negocio.value)) {
	if (!EW_onError(EW_this, EW_this.x_ingreso_negocio, "TEXT", "Incorrect floating point number - ingreso negocio"))
		return false; 
}
if (EW_this.x_inv_neg_fija_valor_1 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_1, "TEXT", "Incorrect floating point number - inv neg fija valor 1"))
		return false; 
}
if (EW_this.x_inv_neg_fija_valor_2 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_2, "TEXT", "Incorrect floating point number - inv neg fija valor 2"))
		return false; 
}
if (EW_this.x_inv_neg_fija_valor_3 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_3, "TEXT", "Incorrect floating point number - inv neg fija valor 3"))
		return false; 
}
if (EW_this.x_inv_neg_fija_valor_4 && !EW_checknumber(EW_this.x_inv_neg_fija_valor_4.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_fija_valor_4, "TEXT", "Incorrect floating point number - inv neg fija valor 4"))
		return false; 
}
if (EW_this.x_inv_neg_total_fija && !EW_checknumber(EW_this.x_inv_neg_total_fija.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_fija, "TEXT", "Incorrect floating point number - inv neg total fija"))
		return false; 
}
if (EW_this.x_inv_neg_var_valor_1 && !EW_checknumber(EW_this.x_inv_neg_var_valor_1.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_1, "TEXT", "Incorrect floating point number - inv neg var valor 1"))
		return false; 
}
if (EW_this.x_inv_neg_var_valor_2 && !EW_checknumber(EW_this.x_inv_neg_var_valor_2.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_2, "TEXT", "Incorrect floating point number - inv neg var valor 2"))
		return false; 
}
if (EW_this.x_inv_neg_var_valor_3 && !EW_checknumber(EW_this.x_inv_neg_var_valor_3.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_3, "TEXT", "Incorrect floating point number - inv neg var valor 3"))
		return false; 
}
if (EW_this.x_inv_neg_var_valor_4 && !EW_checknumber(EW_this.x_inv_neg_var_valor_4.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_var_valor_4, "TEXT", "Incorrect floating point number - inv neg var valor 4"))
		return false; 
}
if (EW_this.x_inv_neg_total_var && !EW_checknumber(EW_this.x_inv_neg_total_var.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_total_var, "TEXT", "Incorrect floating point number - inv neg total var"))
		return false; 
}
if (EW_this.x_inv_neg_activos_totales && !EW_checknumber(EW_this.x_inv_neg_activos_totales.value)) {
	if (!EW_onError(EW_this, EW_this.x_inv_neg_activos_totales, "TEXT", "Incorrect floating point number - inv neg activos totales"))
		return false; 
}
return true;
}

//-->
</script>
<script type="text/javascript" src="popcalendar.js"></script>
<script type="text/ecmascript" src="paisedohint.js"></script>
<title>Documento sin título</title>
<link rel="stylesheet" href="../../../crm.css" type="text/css" />

<style >
#contenedor {
	width:60%;
	margin:auto;
	 
	}

</style>
</head>
<body onload="cargaEventos();">
<!-- New popup calendar -->
<link rel="stylesheet" type="text/css" media="all" href="../../jscalendar/skins/aqua/theme.css" title="win2k-1" />
<script type="text/javascript" src="../../jscalendar/calendar.js"></script>

<script type="text/ecmascript" src="../../jscalendar/lang/calendar-es.js"></script>
<script type="text/javascript" src="../../jscalendar/calendar-setup.js"></script>
<?php 
$conn = phpmkr_db_connect(HOST, USER, PASS,DB,PORT);
echo $x_mensaje;
?>
<center><span class="phpmaker">Datos del Aval<br>
  <br>
    <a href="javascript: window.close();">Cerrar ventana</a></span></p></center>
<p>&nbsp;</p>
<form name="datos_avaladd" id="datos_avaladd" action="formato_avaledit.php" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_add" value="A">
<input  type="hidden" name="x_cliente_id" value="1" />
<input type="hidden" name="x_datos_aval_id" id="x_datos_aval_id" value="<?php echo($x_datos_aval_id);?>" />

<?php
if (@$_SESSION["mensajeAlta"] <> "") {
?>
<div id="seEnvioFormulario" style="display:block"><table width="90%" class="error_box"><tr ><td align="center"><?php echo($_SESSION["mensajeAlta"]); ?></td></tr></table> </div>
      
    <?php
	$_SESSION["mensajeAlta"] = ""; // Clear message
}else{
?>
     <div id="seEnvioFormulario" style="display:none"> </div>
    <?php }
?>  

<div id="contenedor">


<table width="100%" id="pagina1">
<tr><td><input type="hidden" name="datos" id="datos" value="enviados" /></td></tr>
<tr>
  <td><table width="91%">
    <tr>
      <td colspan="11" id="tableHead"></td>
    </tr>
    <tr>
      <td colspan="11" id="tableHead"></td>
    </tr>
    <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td  colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold"> Datos Personales del Aval</td>
    </tr>
     <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td width="223" >Titular</td>
      <td colspan="8" align="center"><table width="98%">
        <tr>
          <td><input type="text" name="x_nombre_completo" id="x_nombre_completo"  maxlength="250" size="35" value="<?php echo htmlentities($x_nombre_completo); ?>" /></td>
          <td><input type="text" name="x_apellido_paterno" id="x_apellido_paterno" maxlength="250" size="35" value="<?php echo htmlentities($x_apellido_paterno); ?>"  /></td>
          <td><input type="text" name="x_apellido_materno" id="x_apellido_materno" maxlength="250" size="35" value="<?php echo htmlentities($x_apellido_materno); ?>"  /></td>
          </tr>
        <tr>
          <td>Nombre</td>
          <td>Apellido Paterno</td>
          <td>Apellido Materno</td>
          </tr>
        </table></td>
    </tr>
      <tr>
        <td>RFC</td>
        <td colspan="4"><input type="text" name="x_rfc" id="x_rfc"  maxlength="30" size="25" value="<?php echo htmlentities($x_rfc); ?>" /></td>
        <td width="172">CURP</td>
        <td colspan="5"><input type="text" name="x_curp" id="x_curp"  maxlength="30" size="25" value="<?php echo htmlentities($x_curp); ?>" /></td>
      </tr>
    <tr>
      <td>Fecha Nacimiento</td>
      <td colspan="2"><span class="texto_normal">
              <input name="x_fecha_nacimiento" type="text" id="x_fecha_nacimiento" value="<?php echo FormatDateTime(@$x_fecha_nacimiento,7); ?>" size="25"  /> 
              &nbsp;<img src="../../../images/ew_calendar.gif" id="cx_fecha_nacimiento" onMouseOver="javascript: Calendar.setup(
            { 
            inputField : 'x_fecha_nacimiento', 
           ifFormat : '%d/%m/%Y', 
            button : 'cx_fecha_nacimiento' 
            }
            );" style="cursor:pointer;cursor:hand;" /></span></td>
      <td width="105">Sexo</td>
      <td width="103"><label>
        <select name="x_sexo" id="x_sexo">
        <option value="1" <?php if($x_sexo == 1){echo("SELECTED");} ?>>Masculino</option> 
		<option value="2" <?php if($x_sexo == 2){echo("SELECTED");} ?>>Femenino</option>
        </select>
      </label></td>
      <td>Integrantes Familia</td>
      <td colspan="5"><input type="text" name="x_integrantes_familia" id="x_integrantes_familia"  maxlength="30" size="25" onKeyPress="return solonumeros(this,event)" value="<?php echo htmlentities($x_integrantes_familia);?> "/></td>
    </tr>
    <tr>
      <td>Dependientes</td>
      <td colspan="2"><input type="text" name="x_dependientes" id="x_dependientes"  maxlength="30" size="30" onKeyPress="return solonumeros(this,event)" value="<?php echo htmlentities($x_dependientes);?> "/></td>
      <td colspan="2">Correo Electronico</td>
      <td colspan="6"><span class="phpmaker">
        <input type="text" name="x_correo_electronico" id="x_correo_electronico"  value="<?php echo htmlentities($x_correo_electronico); ?>"  />
      </span></td>
    </tr>
    <tr>
      <td>Esposo(a)</td>
      <td colspan="10"><input type="text" name="x_nombre_conyuge" id="x_nombre_conyuge"  maxlength="250" size="100" value="<?php echo htmlentities($x_nombre_conyuge); ?>" /></td>
    </tr>
    <tr>
      <td colspan="11" id="tableHead"></td>
    </tr>
    <tr>
      <td colspan="11" id="tableHead"></td>
    </tr>
    <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Domicilio Particular</td>
    </tr>
      <tr>
      <td colspan="11" id="tableHead"></td>
    </tr>
    <tr>
      <td colspan="11" id="tableHead"></td>
    </tr>
    <tr>
      <td colspan="11" id="tableHead"></td>
    </tr>
    <tr>
      <td>Calle</td>
      <td colspan="10"><input type="text" name="x_calle" id="x_calle"  maxlength="100" size="100" value="<?php echo htmlentities($x_calle); ?>" /></td>
    </tr>
    <tr>
      <td>Colonia</td>
      <td colspan="4"><input type="text" name="x_colonia" id="x_colonia"  maxlength="100" size="50" value="<?php echo htmlentities($x_colonia); ?>" /></td>
      <td>C&oacute;digo Postal</td>
      <td colspan="5"><input type="text" name="x_codigo_postal" id="x_codigo_postal"  maxlength="10" size="20" onKeyPress="return solonumeros(this,event)" value="<?php echo htmlentities($x_codigo_postal); ?>" /></td>
    </tr>
    <tr>
      <td>Entidad</td>
      <td colspan="4"><!-- <input type="text" name="x_entidad_domicilio" id="x_entidad_domicilio"  maxlength="250" size="50"/> -->
      <?php
		$x_entidad_idList = "<select name=\"x_entidad\" id=\"x_entidad\" class=\"texto_normal\" onchange=\"showHint(this,'txtHint1', 'x_delegacion')\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `entidad_id`, `nombre` FROM `entidad`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["entidad_id"] == @$x_entidad) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?>
      </td>
      <td><div align="left"><span class="texto_normal">
              
        </span><span class="texto_normal">
        <div id="txtHint1" class="texto_normal">
        Del/Mun:
        <?php
	if($x_entidad > 0) {
		$x_delegacion_idList = "<select name=\"x_delegacion\" class=\"texto_normal\">";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where entidad_id = $x_entidad";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["delegacion_id"] == @$x_delegacion) {
					$x_delegacion_idList .= "' selected";
				}
				$x_delegacion_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_delegacion_idList .= "</select>";
		echo $x_delegacion_idList;
		}
		?>
        </div>
        </span></div></td>
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
      <td>Ubicacion</td>
      <td colspan="10"><input type="text" name="x_ubicacion" id="x_ubicacion"  maxlength="250" size="100" value="<?php echo htmlentities($x_ubicacion); ?>" /></td>
    </tr>
    <tr>
      <td>Tipo Vivienda</td>
      <td colspan="4">
      <?php
		$x_vivienda_tipo_idList = "<select name=\"x_vivienda_tipo\" id=\"x_vivienda_tipo\"  class=\"texto_normal\" onchange=\"viviendatipo('1')\">";
		$x_vivienda_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `vivienda_tipo_id`, `descripcion` FROM `vivienda_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_vivienda_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["vivienda_tipo_id"] == @$x_vivienda_tipo) {
					$x_vivienda_tipo_idList .= "' selected";
				}
				$x_vivienda_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_vivienda_tipo_idList .= "</select>";
		echo $x_vivienda_tipo_idList;
		?>
      
      </td>
      <td>Antiguedad</td>
      <td colspan="5"><input type="text" name="x_antiguedad_v" id="x_antiguedad_v"  maxlength="10" size="20" value="<?php echo htmlentities($x_antiguedad_v); ?>" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="4">&nbsp;</td>
      <td>Tel Arrendatario</td>
      <td colspan="5"><input type="text" name="x_tel_arrendatario_v" id="x_tel_arrendatario_v"  maxlength="20" size="20" value="<?php echo htmlentities($x_tel_arrendatario_v); ?>" /></td>
    </tr>
    <tr>
      <td height="24">Tel Domicilio</td>
      <td width="133"><input type="text" name="x_telefono_p" id="x_telefono_p"  maxlength="50" size="25" value="<?php echo htmlentities($x_telefono_p); ?>" /></td>
      <td width="76">Otro Tel</td>
      <td colspan="2"><input type="text" name="x_telefono_o" id="x_telefono_o"  maxlength="50" size="25" value="<?php echo htmlentities($x_telefono_o); ?>" /></td>
      <td>Renta Mensual</td>
      <td colspan="5"><input type="text" name="x_renta_mensual_v" id="x_renta_mensual_v"  maxlength="25" size="20" onKeyPress="return solonumeros(this,event)" value="<?php echo number_format(@$x_renta_mensual_v, 0, '.', '') ?>" /></td>
    </tr>
    <tr>
      <td>Celular</td>
      <td><input type="text" name="x_telefono_c" id="x_telefono_c"  maxlength="50" size="25" value="<?php echo htmlentities($x_telefono_c); ?>" /></td>
      <td>&nbsp;</td>
      <td colspan="2"></td>
      <td colspan="6">&nbsp;</td>
    </tr>
      <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Datos del Negocio </td>
    </tr>
     <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td>Giro Negocio</td>
      <td colspan="10"><input type="text" name="x_giro_negocio" id="x_giro_negocio"  maxlength="250" size="100" value="<?php echo htmlentities($x_giro_negocio); ?>" /></td>
    </tr>
    <tr>
      <td>Calle</td>
      <td colspan="10"><input type="text" name="x_calle_2" id="x_calle_2"  maxlength="250" size="100" value="<?php echo htmlentities($x_calle_2); ?>" /></td>
    </tr>
    <tr>
      <td>Colonia</td>
      <td colspan="4"><input type="text" name="x_colonia_2" id="x_colonia_2"  maxlength="250" size="70" value="<?php echo htmlentities($x_colonia_2); ?>" /></td>
      <td><p>C&oacute;digo Postal</p></td>
      <td colspan="5"><input type="text" name="x_codigo_postal_2" id="x_codigo_postal_2"  maxlength="25" size="20" onKeyPress="return solonumeros(this,event)" value="<?php echo htmlentities($x_codigo_postal_2); ?>"/></td>
    </tr>
    <tr>
      <td>Entidad</td>
      <td colspan="4"><!-- <input type="text" name="x_entidad_negocio" id="x_entidad_negocio"  maxlength="250" size="70"/> -->
      <?php
		$x_entidad_idList2 = "<select name=\"x_entidad_2\" id=\"x_entidad_2\" class=\"texto_normal\" onchange=\"showHint(this,'txtHint2', 'x_delegacion_2')\" >";
		$x_entidad_idList2 .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `entidad_id`, `nombre` FROM `entidad`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList2 .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["entidad_id"] == @$x_entidad_2) {
					$x_entidad_idList2 .= "' selected";
				}
				$x_entidad_idList2 .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList2 .= "</select>";
		echo $x_entidad_idList2;
		?>
      
      </td>
      <td><div align="left"><span class="texto_normal">
              
        </span><span class="texto_normal">
        <div id="txtHint2" class="texto_normal">
        Del/Mun:
        <?php
		if($x_entidad_2 > 0) {
		$x_delegacion_idList = "<select name=\"x_delegacion_2\" class=\"texto_normal\">";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where entidad_id = $x_entidad";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["delegacion_id"] == @$x_delegacion_2) {
					$x_delegacion_idList .= "' selected";
				}
				$x_delegacion_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_delegacion_idList .= "</select>";
		echo $x_delegacion_idList;
		}
		?>
        </div>
        </span></div></td>
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
      <td>Ubicacion</td>
      <td colspan="10"><input type="text" name="x_ubicacion_2" id="x_ubicacion_2"  maxlength="250" size="100" value="<?php echo htmlentities($x_ubicacion_2); ?>" /></td>
    </tr>
    <tr>
      <td>Tipo Local</td>
      <td colspan="4">
      <?php
		$x_vivienda_tipo_idList = "<select name=\"x_vivienda_tipo_2\" id=\"x_vivienda_tipo_2\"  class=\"texto_normal\" onchange=\"viviendatipo('1')\">";
		$x_vivienda_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `vivienda_tipo_id`, `descripcion` FROM `vivienda_tipo`";
		$rswrk = @phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" .phpmkr_error(). ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_vivienda_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["vivienda_tipo_id"] == @$x_vivienda_tipo_2) {
					$x_vivienda_tipo_idList .= "' selected";
				}
				$x_vivienda_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_vivienda_tipo_idList .= "</select>";
		echo $x_vivienda_tipo_idList;
		?>
      
      
      </td>
      <td>Antiguedad Negocio</td>
      <td colspan="5"><input type="text" name="x_antiguedad_n" id="x_antiguedad_n"  maxlength="10" size="20" value="<?php echo htmlentities($x_antiguedad_n); ?>"/></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="4">&nbsp;</td>
      <td>Tel. Arrendatario</td>
      <td colspan="5"><input type="text" name="x_tel_arrendatario_n" id="x_tel_arrendatario_n"  maxlength="20" size="20" value="<?php echo htmlentities($x_tel_arrendatario_n); ?>" /></td>
    </tr>
    <tr>
      <td>Tel Negocio</td>
      <td colspan="4"><input type="text" name="x_telefono_p_2" id="x_telefono_p_2"  maxlength="50" size="25" value="<?php echo htmlentities($x_telefono_p_2); ?>" /></td>
      <td>Renta Mensual</td>
      <td colspan="5"><input type="text" name="x_renta_mesual_n" id="renta_mesual_n"  maxlength="50" size="20" onKeyPress="return solonumeros(this,event)" value="<?php echo number_format(@$x_renta_mesual_n, 0, '.', '') ?>" /></td>
    </tr>
      <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Garantias</td>
    </tr>
     <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td colspan="11"><center><textarea cols="80" rows="4" id="x_garantia_desc" name="x_garantia_desc" ><?php echo htmlentities($x_garantia_desc); ?></textarea></center></td>
    </tr>
    <tr>
      <td colspan="11"><table width="80%">
        <tr>
          <td>Valor</td>
          <td><input type="text" name="x_garantia_valor" id="x_garantia_valor" onKeyPress="return solonumeros(this,event)" value="<?php echo number_format(@$x_garantia_valor, 0, '.', '') ?>"  /></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="11">&nbsp;</td>
    </tr></table>
    <table width="90%">
      <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
  <tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Referencias Comerciales</td>
    </tr>
     <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td height="36" colspan="2"><table width="300"><tr>
        <td width="48">1.-</td>
        <td width="276"><input type="text" name="x_referencia_1" id="x_referencia_com_1"  maxlength="250" size="40" value="<?php echo htmlentities($x_referencia_1); ?>" /></td></tr></table></td>
      <td colspan="2"><table width="160" height="29"><tr>
        <td width="36">Tel</td>
        <td width="137"><input type="text" name="x_telefono_ref_1" id="x_telefono_ref_1"  maxlength="20" size="30" value="<?php echo htmlentities($x_telefono_ref_1); ?>" /></td></tr></table></td>
      <td width="107">Parentesco</td>
      <td colspan="6">
      <?php
		$x_parentesco_tipo_idList = "<select name=\"x_relacion_1\" id=\"x_relacion_1\" class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_relacion_1) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?>
      
      
      
      
      
       </td>
      </tr>
    <tr>
      <td colspan="2"><table width="300"><tr>
        <td width="48">2.-</td>
        <td width="277"><input type="text" name="x_referencia_2" id="x_referencia_2"  maxlength="250" size="40" value="<?php echo htmlentities($x_referencia_2); ?>" /></td></tr></table></td>
      <td colspan="2"><table width="160"><tr>
        <td width="36">Tel</td>
        <td width="137"><input type="text" name="x_telefono_ref_2" id="x_telefono_ref_2"  maxlength="20" size="30" value="<?php echo htmlentities($x_telefono_ref_2); ?>" /></td></tr></table></td>
      <td>Parentesco</td>
      <td colspan="6">
       <?php
		$x_parentesco_tipo_idList = "<select name=\"x_relacion_2\" id=\"x_relacion_2\" class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_relacion_2) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?>  
      
      </td>
      </tr>
    <tr>
      <td colspan="2"><table width="300"><tr>
        <td width="48">3.-</td>
        <td width="276"><input type="text" name="x_referencia_3" id="x_referencia_3"  maxlength="250" size="40" value="<?php echo htmlentities($x_referencia_3); ?>" /></td></tr></table></td>
      <td colspan="2"><table width="183"><tr>
        <td width="34">Tel</td>
        <td width="137"><input type="text" name="x_telefono_ref_3" id="x_telefono_ref_3"  maxlength="20" size="30" value="<?php echo htmlentities($x_telefono_ref_3); ?>" /></td></tr></table></td>
      <td>Parentesco</td>
      <td colspan="6">
      <?php
		$x_parentesco_tipo_idList = "<select name=\"x_relacion_3\" id=\"x_relacion_3\" class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_relacion_3) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?>
      
      
      
      </td>
      </tr>
    <tr>
      <td colspan="2"><table width="300"><tr>
        <td width="48">4.-</td>
        <td width="276"><input type="text" name="x_referencia_4" id="x_referencia_4"  maxlength="250" size="40" value="<?php echo htmlentities($x_referencia_4); ?>" /></td></tr></table></td>
     <td colspan="2"><table width="160"><tr>
        <td width="35">Tel</td>
        <td width="137"><input type="text" name="x_telefono_ref_4" id="x_telefono_ref_4"  maxlength="20" size="30" value="<?php echo htmlentities($x_telefono_ref_4); ?>" /></td></tr></table></td>
      <td>Parentesco</td>
      <td colspan="6">
      <?php
		$x_parentesco_tipo_idList = "<select name=\"x_relacion_4\" id=\"x_relacion_4\" class=\"texto_normal\">";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {

				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_relacion_4) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?>
      
      
      
      
      
      </td>
      </tr>
    <tr>
      <td width="201">&nbsp;</td>
      <td width="108">&nbsp;</td>
      <td width="159">&nbsp;</td>
      <td width="69">&nbsp;</td>
      <td>&nbsp;</td>
      <td width="51">&nbsp;</td>
      <td width="140">&nbsp;</td>
      <td width="3">&nbsp;</td>
      <td width="3">&nbsp;</td>
      <td width="3">&nbsp;</td>
      <td width="48">&nbsp;</td>
    </tr>
    
</table>
    
    
    
<table width="90%" id="tablePag" style="display:none"><tr><td><div id="paginacion">

<div  align="right"><a href="#" id="siguiente">Siguiente</a></div>

</div></td></tr></table></tr>
  </table>
  
<!--pagina uno -->


<p>&nbsp;</p>

<div id="paginaDos" style="display:block">
<!-- paginaDos-->
<table width="100%" id="pagina2" style="display:block">
<tr>
  <td height="748"><table width="90%" >
    
    <tr>
      <td colspan="22"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Ingresos Familiares</td>
    </tr>
     <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td width="147">Ingreso del Negocio</td>
      <td colspan="4"><input type="text" name="x_ing_fam_negocio" id="x_ing_fam_negocio"  maxlength="30" size="35" onKeyPress="return solonumeros(this,event)" value="<?php echo number_format(@$x_ing_fam_negocio, 0, '.', '') ?>" /></td>
      <td colspan="2">&nbsp;</td>
      <td width="76">&nbsp;</td>
      <td width="52">&nbsp;</td>
      <td width="69">&nbsp;</td>
      <td width="56">&nbsp;</td>
      <td width="127">&nbsp;</td>
      <td width="23" colspan="10">&nbsp;</td>
    </tr>
    <tr>
      <td width="147">Otros Ingresos TH</td>
      <td colspan="4"><input type="text" name="x_ing_fam_otro_th" id="x_ing_fam_otro_th"  maxlength="30" size="35"onKeyPress="return solonumeros(this,event)" value="<?php echo number_format(@$x_ing_fam_otro_th, 0, '.', '') ?>" /></td>
      <td colspan="2">Cu&aacute;l</td>
      <td colspan="4"><input type="text" name="x_ing_fam_cuales_1" id="x_ing_fam_cuales_1"  maxlength="250" size="35" value="<?php echo htmlentities($x_ing_fam_cuales_1); ?>" /></td>
      <td width="127">&nbsp;</td>
      <td colspan="10">&nbsp;</td>
    </tr>
     <tr>
      <td width="147">Ingreso Familiar 1</td>
      <td colspan="4"><input type="text" name="x_ing_fam_1" id="x_ing_fam_1"  maxlength="30" size="35" onKeyPress="return solonumeros(this,event)" value="<?php echo number_format(@$x_ing_fam_1, 0, '.', '') ?>" /></td>
      <td colspan="2">Cu&aacute;l</td>
      <td colspan="4"><input type="text" name="x_ing_fam_cuales_2" id="x_ing_fam_cuales_2"  maxlength="250" size="35" value="<?php echo htmlentities($x_ing_fam_cuales_2); ?>" /></td>
      <td width="127">&nbsp;</td>
      <td colspan="10">&nbsp;</td>
    </tr>
     <tr>
      <td width="147">Ingreso Familiar 2</td>
      <td colspan="4"><input type="text" name="x_ing_fam_2" id="x_ing_fam_2"  maxlength="30" size="35" onKeyPress="return solonumeros(this,event)" value="<?php echo number_format(@$x_ing_fam_2, 0, '.', '') ?>" /></td>
      <td colspan="2">Cu&aacute;l</td>
      <td colspan="4"><input type="text" name="x_ing_fam_cuales_13" id="x_ing_fam_cuales_13"  maxlength="250" size="35" value="<?php echo htmlentities($x_ing_fam_cuales_13); ?>" /></td>
      <td width="127">&nbsp;</td>
      <td colspan="10">&nbsp;</td>
    </tr>
     <tr>
      <td width="147">Deuda 1</td>
      <td colspan="4"><input type="text" name="x_ing_fam_deuda_1" id="x_ing_fam_deuda_1"  maxlength="30" size="35" onKeyPress="return solonumeros(this,event)" value="<?php echo number_format(@$x_ing_fam_deuda_1, 0, '.', '') ?>" /></td>
      <td colspan="2">Cu&aacute;l</td>
      <td colspan="4"><input type="text" name="x_ing_fam_cuales_4" id="x_ing_fam_cuales_4"  maxlength="250" size="35" value="<?php echo htmlentities($x_ing_fam_cuales_4); ?>" /></td>
      <td width="127">&nbsp;</td>
      <td colspan="10">&nbsp;</td>
    </tr>
     <tr>
      <td width="147">Deuda 2</td>
      <td colspan="4"><input type="text" name="x_ing_fam_deuda_2" id="x_ing_fam_deuda_2"  maxlength="30" size="35" onKeyPress="return solonumeros(this,event)" value="<?php echo number_format(@$x_ing_fam_deuda_2, 0, '.', '') ?>" /></td>
      <td colspan="2">Cu&aacute;l</td>
      <td colspan="4"><input type="text" name="x_ing_fam_cuales_5" id="x_ing_fam_cuales_5"  maxlength="250" size="35" value="<?php echo htmlentities($x_ing_fam_cuales_5); ?>" /></td>
      <td width="127">&nbsp;</td>
      <td colspan="10">&nbsp;</td>
    </tr>
     <tr>
      <td width="147">Total</td>
      <td colspan="4"><input type="text" name="x_ing_fam_total" id="x_ing_fam_total"  maxlength="30" size="35" onKeyPress="return solonumeros(this,event)" value="<?php echo number_format(@$x_ing_fam_total, 0, '.', '') ?>" /></td>
      <td colspan="2">&nbsp;</td>
      <td width="76">&nbsp;</td>
      <td width="52">&nbsp;</td>
      <td width="69">&nbsp;</td>
      <td width="56">&nbsp;</td>
      <td width="127">&nbsp;</td>
      <td colspan="10">&nbsp;</td>
    </tr>
     <tr>
      <td width="147">&nbsp;</td>
      <td width="71">&nbsp;</td>
      <td width="60">&nbsp;</td>
      <td width="72">&nbsp;</td>
      <td width="65">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td width="76">&nbsp;</td>
      <td width="52">&nbsp;</td>
      <td width="69">&nbsp;</td>
      <td width="56">&nbsp;</td>
      <td width="127">&nbsp;</td>
      <td colspan="10">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="22" id="tableHead"><p></td>
    </tr>
    
    <tr>
      <td colspan="22"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Flujos de Negocio</td>
         
    </tr>
     <tr>
      <td colspan="22" id="tableHead"><p></td>
    </tr>
    <tr>
      <td width="147">Ventas</td>
      <td colspan="4"><input type="text" name="x_flujos_neg_ventas" id="x_flujos_neg_ventas"  maxlength="30" size="35" onKeyPress="return solonumeros(this,event)" value="<?php echo number_format(@$x_flujos_neg_ventas, 0, '.', '') ?>" /></td>
      <td colspan="2">&nbsp;</td>
      <td width="76">&nbsp;</td>
      <td width="52">&nbsp;</td>
      <td width="69">&nbsp;</td>
      <td width="56">&nbsp;</td>
      <td width="127">&nbsp;</td>
      <td width="23" colspan="10">&nbsp;</td>
    </tr>
    <tr>
      <td width="147">Proveedor 1</td>
      <td colspan="4"><input type="text" name="x_flujos_neg_proveedor_1" id="x_flujos_neg_proveedor_1"  maxlength="250" size="35" onKeyPress="return solonumeros(this,event)" value="<?php echo number_format(@$x_flujos_neg_proveedor_1, 0, '.', '') ?>" /></td>
      <td colspan="2">Cu&aacute;l</td>
      <td colspan="4"><input type="text" name="x_flujos_neg_cual_1" id="x_flujos_neg_cual_1"  maxlength="100" size="35" value="<?php echo htmlentities($x_flujos_neg_cual_1); ?>" /></td>
      <td width="127">&nbsp;</td>
      <td colspan="10">&nbsp;</td>
    </tr>
     <tr>
      <td width="147">Proveedor 2</td>
      <td colspan="4"><input type="text" name="x_flujos_neg_proveedor_2" id="x_flujos_neg_proveedor_2"  maxlength="250" size="35" onKeyPress="return solonumeros(this,event)" value="<?php echo number_format(@$x_flujos_neg_proveedor_2, 0, '.', '') ?>" /></td>
      <td colspan="2">Cu&aacute;l</td>
      <td colspan="4"><input type="text" name="x_flujos_neg_cual_2" id="x_flujos_neg_cual_2"  maxlength="100" size="35" value="<?php echo htmlentities($x_flujos_neg_cual_2); ?>" /></td>
      <td width="127">&nbsp;</td>
      <td colspan="10">&nbsp;</td>
    </tr>
     <tr>
      <td width="147">Proveedor 3</td>
      <td colspan="4"><input type="text" name="x_flujos_neg_proveedor_3" id="x_flujos_neg_proveedor_3"  maxlength="250" size="35" onKeyPress="return solonumeros(this,event)" value="<?php echo number_format(@$x_flujos_neg_proveedor_3, 0, '.', '') ?>" /></td>
      <td colspan="2">Cu&aacute;l</td>
      <td colspan="4"><input type="text" name="x_flujos_neg_cual_3" id="x_flujos_neg_cual_3"  maxlength="100" size="35" value="<?php echo htmlentities($x_flujos_neg_cual_3); ?>" /></td>
      <td width="127">&nbsp;</td>
      <td colspan="10">&nbsp;</td>
    </tr>
     <tr>
      <td width="147">Proveedor 4</td>
      <td colspan="4"><input type="text" name="x_flujos_neg_proveedor_4" id="x_flujos_neg_proveedor_4"  maxlength="250" size="35" onKeyPress="return solonumeros(this,event)" value="<?php echo number_format(@$x_flujos_neg_proveedor_4, 0, '.', '') ?>"/></td>
      <td colspan="2">Cu&aacute;l</td>
      <td colspan="4"><input type="text" name="x_flujos_neg_cual_4" id="x_flujos_neg_cual_4"  maxlength="100" size="35" value="<?php echo htmlentities($x_flujos_neg_cual_4); ?>" /></td>
      <td width="127">&nbsp;</td>
      <td colspan="10">&nbsp;</td>
    </tr>
     <tr>
      <td width="147">Gasto 1</td>
      <td colspan="4"><input type="text" name="x_flujos_neg_gasto_1" id="x_flujos_neg_gasto_1"  maxlength="250" size="35" onKeyPress="return solonumeros(this,event)" value="<?php echo number_format(@$x_flujos_neg_gasto_1, 0, '.', '') ?>" /></td>
      <td colspan="2">Cu&aacute;l</td>
      <td colspan="4"><input type="text" name="x_flujos_neg_cual_5" id="x_flujos_neg_cual_5"  maxlength="100" size="35" value="<?php echo htmlentities($x_flujos_neg_cual_5); ?>" /></td>
      <td width="127">&nbsp;</td>
      <td colspan="10">&nbsp;</td>
    </tr>
     <tr>
      <td width="147">Gasto 2</td>
      <td colspan="4"><input type="text" name="x_flujos_neg_gasto_2" id="x_flujos_neg_gasto_2"  maxlength="250" size="35" onKeyPress="return solonumeros(this,event)" value="<?php echo number_format(@$x_flujos_neg_gasto_2, 0, '.', '') ?>" /></td>
      <td colspan="2">Cu&aacute;l</td>
      <td colspan="4"><input type="text" name="x_flujos_neg_cual_6" id="x_flujos_neg_cual_6"  maxlength="100" size="35" value="<?php echo htmlentities($x_flujos_neg_cual_6); ?>" /></td>
      <td width="127">&nbsp;</td>
      <td colspan="10">&nbsp;</td>
    </tr>
    <tr>
      <td width="147">Gasto 3</td>
      <td colspan="4"><input type="text" name="x_flujos_neg_gasto_3" id="x_flujos_neg_gasto_3"  maxlength="250" size="35" onKeyPress="return solonumeros(this,event)" value="<?php echo number_format(@$x_flujos_neg_gasto_3, 0, '.', '') ?>" /></td>
      <td colspan="2">Cu&aacute;l</td>
      <td colspan="4"><input type="text" name="x_flujos_neg_cual_7" id="x_flujos_neg_cual_7"  maxlength="100" size="35" value="<?php echo htmlentities($x_flujos_neg_cual_7); ?>" /></td>
      <td width="127">&nbsp;</td>
      <td colspan="10">&nbsp;</td>
    </tr>
    <tr>
      <td width="147">Ingreso Negocio</td>
      <td colspan="4"><input type="text" name="x_ingreso_negocio" id="x_ingreso_negocio"  maxlength="250" size="35" onKeyPress="return solonumeros(this,event)" value="<?php echo number_format(@$x_ingreso_negocio, 0, '.', '') ?>" /></td>
      <td colspan="2">&nbsp;</td>
      <td colspan="4">&nbsp;</td>
      <td width="127">&nbsp;</td>
      <td colspan="10">&nbsp;</td>
    </tr></table>
    
    
    
    
    
    <table width="90%"><tr>
    <tr>
      <td colspan="19" id="tableHead"><p></td>
    </tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Inversion del Negocio</td>
    </tr>
     <tr>
      <td colspan="19" id="tableHead"><p></td>
    </tr>
    <tr>
      <td height="30" colspan="5"><center>FIJA</center></td>
      <td colspan="2">&nbsp;</td>
      <td colspan="2"><center>VARIABLE</center></td>
      <td width="21" colspan="10">&nbsp;</td>
    </tr>
    <tr>
      <td width="226">Concepto</td>
      <td colspan="4">Valor</td>
      <td colspan="2">&nbsp;</td>
      <td width="267">Concepto</td>
      <td width="171" colspan="-2">Valor</td>
      <td colspan="10">&nbsp;</td>
    </tr>
     <tr>
      <td width="226"><input type="text" name="x_inv_neg_fija_conc_1" id="x_inv_neg_fija_conc_1"  maxlength="250" size="35" value="<?php echo htmlentities($x_inv_neg_fija_conc_1); ?>" /></td>
      <td colspan="4"><input type="text" name="x_inv_neg_fija_valor_1" id="x_inv_neg_fija_valor_1"  maxlength="250" size="25" onKeyPress="return solonumeros(this,event)" value="<?php echo number_format(@$x_inv_neg_fija_valor_1, 0, '.', '') ?>" /></td>
      <td colspan="2">&nbsp;</td>
      <td><input type="text" name="x_inv_neg_var_conc_1" id="x_inv_neg_var_conc_1"  maxlength="250" size="35" value="<?php echo htmlentities($x_inv_neg_var_conc_1); ?>" /></td>
      <td width="171" colspan="-2"><input type="text" name="x_inv_neg_var_valor_1" id="x_inv_neg_var_valor_1"  maxlength="250" size="25" onKeyPress="return solonumeros(this,event)" value="<?php echo number_format(@$x_inv_neg_var_valor_1, 0, '.', '') ?>" /></td>
      <td colspan="10">&nbsp;</td>
    </tr>
     <tr>
      <td width="226"><input type="text" name="x_inv_neg_fija_conc_2" id="x_inv_neg_fija_conc_2"  maxlength="250" size="35" value="<?php echo htmlentities($x_inv_neg_fija_conc_2); ?>" /></td>
      <td colspan="4"><input type="text" name="x_inv_neg_fija_valor_2" id="x_inv_neg_fija_valor_2"  maxlength="250" size="25" onKeyPress="return solonumeros(this,event)" value="<?php echo number_format(@$x_inv_neg_fija_valor_2, 0, '.', '') ?>" /></td>
      <td colspan="2">&nbsp;</td>
      <td><input type="text" name="x_inv_neg_var_conc_2" id="x_inv_neg_var_conc_2"  maxlength="250" size="35" value="<?php echo htmlentities($x_inv_neg_var_conc_2); ?>" /></td>
      <td width="171" colspan="-2"><input type="text" name="x_inv_neg_var_valor_2" id="x_inv_neg_var_valor_2"  maxlength="250" size="25" onKeyPress="return solonumeros(this,event)" value="<?php echo number_format(@$x_inv_neg_var_valor_2, 0, '.', '') ?>" /></td>
      <td colspan="10">&nbsp;</td>
    </tr>
     <tr>
      <td width="226"><input type="text" name="x_inv_neg_fija_conc_3" id="x_inv_neg_fija_conc_3"  maxlength="250" size="35" value="<?php echo htmlentities($x_inv_neg_fija_conc_3); ?>" /></td>
      <td colspan="4"><input type="text" name="x_inv_neg_fija_valor_3" id="x_inv_neg_fija_valor_3"  maxlength="250" size="25" onKeyPress="return solonumeros(this,event)" value="<?php echo number_format(@$x_inv_neg_fija_valor_3, 0, '.', '') ?>" /></td>
      <td colspan="2">&nbsp;</td>
      <td><input type="text" name="x_inv_neg_var_conc_3" id="x_inv_neg_var_conc_3"  maxlength="250" size="35" value="<?php echo htmlentities($x_inv_neg_var_conc_3);?>"/></td>
      <td width="171" colspan="-2"><input type="text" name="x_inv_neg_var_valor_3" id="x_inv_neg_var_valor_3"  maxlength="250" size="25" onKeyPress="return solonumeros(this,event)" value="<?php echo number_format(@$x_inv_neg_var_valor_3, 0, '.', '') ?>" /></td>
      <td colspan="10">&nbsp;</td>
    </tr>
     <tr>
      <td width="226"><input type="text" name="x_inv_neg_fija_conc_4" id="x_inv_neg_fija_conc_4"  maxlength="250" size="35" value="<?php echo htmlentities($x_inv_neg_fija_conc_4); ?>" /></td>
      <td colspan="4"><input type="text" name="x_inv_neg_fija_valor_4" id="x_inv_neg_fija_valor_4"  maxlength="250" size="25" onkeypress="return solonumeros(this,event)" value="<?php echo number_format(@$x_inv_neg_fija_valor_4, 0, '.', '') ?>" /></td>
      <td colspan="2">&nbsp;</td>
      <td><input type="text" name="x_inv_neg_var_conc_4" id="x_inv_neg_var_conc_4"  maxlength="250" size="35" value="<?php echo htmlentities($x_inv_neg_var_conc_4); ?>" /></td>
      <td width="171" colspan="-2"><input type="text" name="x_inv_neg_var_valor_4" id="x_inv_neg_var_valor_4"  maxlength="250" size="25" onKeyPress="return solonumeros(this,event)" value="<?php echo number_format(@$x_inv_neg_var_valor_4, 0, '.', '') ?>" /></td>
      <td colspan="10">&nbsp;</td>
    </tr>
     <tr>
      <td width="226" align="right">Total</td>
      <td colspan="4"><input type="text" name="x_inv_neg_total_fija" id="x_inv_neg_total_fija"  maxlength="250" size="25" onKeyPress="return solonumeros(this,event)" value="<?php echo number_format(@$x_inv_neg_total_fija, 0, '.', '') ?>" /></td>
      <td colspan="2">&nbsp;</td>
      <td align="right">Total</td>
      <td width="171" colspan="-2"><input type="text" name="x_inv_neg_total_var" id="x_inv_neg_total_var"  maxlength="250" size="25" onKeyPress="return solonumeros(this,event)" value="<?php echo number_format(@$x_inv_neg_total_var, 0, '.', '') ?>" /></td>
      <td colspan="10">&nbsp;</td>
    </tr>
     <tr>
      <td width="226">&nbsp;</td>
      <td colspan="4">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td align="right"></td>
      <td width="171" colspan="-2"></td>
      <td colspan="10">&nbsp;</td>
    </tr>
    <tr>
      <td width="226">&nbsp;</td>
      <td colspan="4">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td align="right">ACTIVOS TOTALES:</td>
      <td width="171" colspan="-2"><input type="text" name="x_inv_neg_activos_totales" id="x_inv_neg_activos_totales"  maxlength="250" size="25" onKeyPress="return solonumeros(this,event)" value="<?php echo number_format(@$x_inv_neg_activos_totales, 0, '.', '') ?>" /></td>
      <td colspan="10">&nbsp;</td>
      <tr>
      <td colspan="19" id="tableHead"><p></td>
    </tr>
     <tr>
    <td colspan="11" bgcolor="#FFE6E6"><div align="center"><span class="texto_normal_bold">Comentarios del Promotor</span></div></td>
    </tr>
  <tr>

    <td colspan="11">&nbsp;</td>
  </tr>
  <tr>
   
    <td  colspan="11" align="left" valign="top">
	  <div align="center">
	    <textarea name="x_comentario_promotor" cols="60" rows="5"><?php echo htmlentities($x_comentario_promotor); ?></textarea>
	      </div></td>
  
  </tr>
  <tr>
    <td colspan="11">&nbsp;</td>
    
  </tr>
  <tr>
    <td colspan="11" bgcolor="#FFE6E6"><div align="center"><span class="texto_normal_bold">Comentarios del Comite de Cr&eacute;dito </span></div></td>
    </tr>
  <tr>
    <td colspan="11">&nbsp;</td>
   
  </tr>
  <tr>
   
    <td  colspan="11" align="left" valign="top">
	  <div align="center">
	    <textarea name="x_comentario_comite" cols="60" rows="5"><?php echo htmlentities($x_comentario_comite); ?></textarea>
	      </div></td>
    
  </tr>
    
</table>
</div>
<input type="submit" name="Guardar" value="Editar">
</form>

</body>
</html>

<?php
/*phpmkr_db_close($conn);*/
?>
<?php

//-------------------------------------------------------------------------------
// Function LoadData
// - Load Data based on Key Value sKey
// - Variables setup: field variables

function LoadData($sKey,$conn)
{
	$sKeyWrk = "" . addslashes($sKey) . "";
	
	
	$x_datos_aval_id = $_GET["datos_aval_id"];
	global $x_datos_aval_id;
	$sSql = "SELECT * FROM `datos_aval`";
	$sSql .= " WHERE `datos_aval_id` = ".$sKey ;
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	
	if ($sGroupBy <> "") {
		$sSql .= " GROUP BY " . $sGroupBy;
	}
	if ($sHaving <> "") {
		$sSql .= " HAVING " . $sHaving;
	}
	if ($sOrderBy <> "") {
		$sSql .= " ORDER BY " . $sOrderBy;
	}
	$rs = phpmkr_query($sSql,$conn);
	if (phpmkr_num_rows($rs) == 0) {
		$LoadData = false;
	}else{
		$LoadData = true;
		$row = phpmkr_fetch_array($rs);

		// Get the field contents
		$GLOBALS["x_datos_aval_id"] = $row["datos_aval_id"];
		$GLOBALS["x_cliente_id"] = $row["cliente_id"];
		$GLOBALS["x_solicitud_id"] = $row["solicitud_id"];
		$GLOBALS["x_nombre_completo"] = $row["nombre_completo"];
		$GLOBALS["x_apellido_paterno"] = $row["apellido_paterno"];
		$GLOBALS["x_apellido_materno"] = $row["apellido_materno"];
		$GLOBALS["x_rfc"] = $row["rfc"];
		$GLOBALS["x_curp"] = $row["curp"];
		$GLOBALS["x_fecha_nacimiento"] = $row["fecha_nacimiento"];
		$GLOBALS["x_sexo"] = $row["sexo"];
		$GLOBALS["x_integrantes_familia"] = $row["integrantes_familia"];
		$GLOBALS["x_dependientes"] = $row["dependientes"];
		$GLOBALS["x_correo_electronico"] = $row["correo_electronico"];
		$GLOBALS["x_nombre_conyuge"] = $row["nombre_conyuge"];
		$GLOBALS["x_calle"] = $row["calle"];
		$GLOBALS["x_colonia"] = $row["colonia"];
		$GLOBALS["x_entidad"] = $row["entidad"];
		$GLOBALS["x_codigo_postal"] = $row["codigo_postal"];
		$GLOBALS["x_delegacion"] = $row["delegacion"];
		$GLOBALS["x_vivienda_tipo"] = $row["vivienda_tipo"];
		$GLOBALS["x_telefono_p"] = $row["telefono_p"];
		$GLOBALS["x_telefono_c"] = $row["telefono_c"];
		$GLOBALS["x_telefono_o"] = $row["telefono_o"];
		$GLOBALS["x_antiguedad_v"] = $row["antiguedad_v"];
		$GLOBALS["x_tel_arrendatario_v"] = $row["tel_arrendatario_v"];
		$GLOBALS["x_renta_mensual_v"] = $row["renta_mensual_v"];
		$GLOBALS["x_calle_2"] = $row["calle_2"];
		$GLOBALS["x_colonia_2"] = $row["colonia_2"];
		$GLOBALS["x_entidad_2"] = $row["entidad_2"];
		$GLOBALS["x_codigo_postal_2"] = $row["codigo_postal_2"];
		$GLOBALS["x_delegacion_2"] = $row["delegacion_2"];
		$GLOBALS["x_vivienda_tipo_2"] = $row["vivienda_tipo_2"];
		$GLOBALS["x_telefono_p_2"] = $row["telefono_p_2"];
		$GLOBALS["x_telefono_c_2"] = $row["telefono_c_2"];
		$GLOBALS["x_telefono_o_2"] = $row["telefono_o_2"];
		$GLOBALS["x_antiguedad_n"] = $row["antiguedad_n"];
		$GLOBALS["x_tel_arrendatario_n"] = $row["tel_arrendatario_n"];
		$GLOBALS["x_garantia_desc"] = $row["garantia_desc"];
		$GLOBALS["x_garantia_valor"] = $row["garantia_valor"];
		$GLOBALS["x_renta_mesual_n"] = $row["renta_mesual_n"];
		$GLOBALS["x_referencia_1"] = $row["referencia_1"];
		$GLOBALS["x_referencia_2"] = $row["referencia_2"];
		$GLOBALS["x_referencia_3"] = $row["referencia_3"];
		$GLOBALS["x_referencia_4"] = $row["referencia_4"];
		$GLOBALS["x_telefono_ref_1"] = $row["telefono_ref_1"];
		$GLOBALS["x_telefono_ref_2"] = $row["telefono_ref_2"];
		$GLOBALS["x_telefono_ref_3"] = $row["telefono_ref_3"];
		$GLOBALS["x_telefono_ref_4"] = $row["telefono_ref_4"];
		$GLOBALS["x_relacion_1"] = $row["relacion_1"];
		$GLOBALS["x_relacion_2"] = $row["relacion_2"];
		$GLOBALS["x_relacion_3"] = $row["relacion_3"];
		$GLOBALS["x_relacion_4"] = $row["relacion_4"];
		$GLOBALS["x_ing_fam_negocio"] = $row["ing_fam_negocio"];
		$GLOBALS["x_ing_fam_otro_th"] = $row["ing_fam_otro_th"];
		$GLOBALS["x_ing_fam_1"] = $row["ing_fam_1"];
		$GLOBALS["x_ing_fam_2"] = $row["ing_fam_2"];
		$GLOBALS["x_ing_fam_deuda_1"] = $row["ing_fam_deuda_1"];
		$GLOBALS["x_ing_fam_deuda_2"] = $row["ing_fam_deuda_2"];
		$GLOBALS["x_ing_fam_total"] = $row["ing_fam_total"];
		$GLOBALS["x_ing_fam_cuales_1"] = $row["ing_fam_cuales_1"];
		$GLOBALS["x_ing_fam_cuales_2"] = $row["ing_fam_cuales_2"];
		$GLOBALS["x_ing_fam_cuales_13"] = $row["ing_fam_cuales_13"];
		$GLOBALS["x_ing_fam_cuales_4"] = $row["ing_fam_cuales_4"];
		$GLOBALS["x_ing_fam_cuales_5"] = $row["ing_fam_cuales_5"];
		$GLOBALS["x_flujos_neg_ventas"] = $row["flujos_neg_ventas"];
		$GLOBALS["x_flujos_neg_proveedor_1"] = $row["flujos_neg_proveedor_1"];
		$GLOBALS["x_flujos_neg_proveedor_2"] = $row["flujos_neg_proveedor_2"];
		$GLOBALS["x_flujos_neg_proveedor_3"] = $row["flujos_neg_proveedor_3"];
		$GLOBALS["x_flujos_neg_proveedor_4"] = $row["flujos_neg_proveedor_4"];
		$GLOBALS["x_flujos_neg_gasto_1"] = $row["flujos_neg_gasto_1"];
		$GLOBALS["x_flujos_neg_gasto_2"] = $row["flujos_neg_gasto_2"];
		$GLOBALS["x_flujos_neg_gasto_3"] = $row["flujos_neg_gasto_3"];
		$GLOBALS["x_flujos_neg_cual_1"] = $row["flujos_neg_cual_1"];
		$GLOBALS["x_flujos_neg_cual_2"] = $row["flujos_neg_cual_2"];
		$GLOBALS["x_flujos_neg_cual_3"] = $row["flujos_neg_cual_3"];
		$GLOBALS["x_flujos_neg_cual_4"] = $row["flujos_neg_cual_4"];
		$GLOBALS["x_flujos_neg_cual_5"] = $row["flujos_neg_cual_5"];
		$GLOBALS["x_flujos_neg_cual_6"] = $row["flujos_neg_cual_6"];
		$GLOBALS["x_flujos_neg_cual_7"] = $row["flujos_neg_cual_7"];
		$GLOBALS["x_ingreso_negocio"] = $row["ingreso_negocio"];
		$GLOBALS["x_inv_neg_fija_conc_1"] = $row["inv_neg_fija_conc_1"];
		$GLOBALS["x_inv_neg_fija_conc_2"] = $row["inv_neg_fija_conc_2"];
		$GLOBALS["x_inv_neg_fija_conc_3"] = $row["inv_neg_fija_conc_3"];
		$GLOBALS["x_inv_neg_fija_conc_4"] = $row["inv_neg_fija_conc_4"];
		$GLOBALS["x_inv_neg_fija_valor_1"] = $row["inv_neg_fija_valor_1"];
		$GLOBALS["x_inv_neg_fija_valor_2"] = $row["inv_neg_fija_valor_2"];
		$GLOBALS["x_inv_neg_fija_valor_3"] = $row["inv_neg_fija_valor_3"];
		$GLOBALS["x_inv_neg_fija_valor_4"] = $row["inv_neg_fija_valor_4"];
		$GLOBALS["x_inv_neg_total_fija"] = $row["inv_neg_total_fija"];
		$GLOBALS["x_inv_neg_var_conc_1"] = $row["inv_neg_var_conc_1"];
		$GLOBALS["x_inv_neg_var_conc_2"] = $row["inv_neg_var_conc_2"];
		$GLOBALS["x_inv_neg_var_conc_3"] = $row["inv_neg_var_conc_3"];
		$GLOBALS["x_inv_neg_var_conc_4"] = $row["inv_neg_var_conc_4"];
		$GLOBALS["x_inv_neg_var_valor_1"] = $row["inv_neg_var_valor_1"];
		$GLOBALS["x_inv_neg_var_valor_2"] = $row["inv_neg_var_valor_2"];
		$GLOBALS["x_inv_neg_var_valor_3"] = $row["inv_neg_var_valor_3"];
		$GLOBALS["x_inv_neg_var_valor_4"] = $row["inv_neg_var_valor_4"];
		$GLOBALS["x_inv_neg_total_var"] = $row["inv_neg_total_var"];
		$GLOBALS["x_inv_neg_activos_totales"] = $row["inv_neg_activos_totales"];
		$GLOBALS["x_giro_negocio"] = $row["giro_negocio"];
		$GLOBALS["x_ubicacion"] = $row["ubicacion"];
		$GLOBALS["x_ubicacion_2"] = $row["ubicacion_2"];
		$GLOBALS["x_comentario_promotor"] = $row["comentario_promotor"];
		$GLOBALS["x_comentario_comite"] = $row["comentario_comite"];
	}
	phpmkr_free_result($rs);
	
	
	return $LoadData;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function AddData
// - Add Data
// - Variables used: field variables

function EditData($conn)
{

	// Update Record
	global $x_datos_aval_id;
	$sSql = "SELECT * FROM `datos_aval`";
	$sSql .= " WHERE 0 = 1";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sGroupBy <> "") {
		$sSql .= " GROUP BY " . $sGroupBy;
	}
	if ($sHaving <> "") {
		$sSql .= " HAVING " . $sHaving;
	}
	if ($sOrderBy <> "") {
		$sSql .= " ORDER BY " . $sOrderBy;
	}



	$fieldList = NULL;
	// Field cliente_id
	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
	//$fieldList["`cliente_id`"] = $theValue;

	$theValue = ($GLOBALS["x_cliente_id"] != "") ? intval($GLOBALS["x_cliente_id"]) : "NULL";
	//$fieldList["`solictud_id`"] = $theValue;
	
	// Field nombre_completo
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_completo"]) : $GLOBALS["x_nombre_completo"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre_completo`"] = $theValue;

	// Field apellido_paterno
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_paterno"]) : $GLOBALS["x_apellido_paterno"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`apellido_paterno`"] = $theValue;

	// Field apellido_materno
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_materno"]) : $GLOBALS["x_apellido_materno"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`apellido_materno`"] = $theValue;

	// Field rfc
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_rfc"]) : $GLOBALS["x_rfc"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`rfc`"] = $theValue;

	// Field curp
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_curp"]) : $GLOBALS["x_curp"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`curp`"] = $theValue;

	// Field fecha_nacimiento
	$theValue = ($GLOBALS["x_fecha_nacimiento"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_nacimiento"]) . "'" : "NULL";
	$fieldList["`fecha_nacimiento`"] = $theValue;

	// Field sexo
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_sexo"]) : $GLOBALS["x_sexo"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`sexo`"] = $theValue;

	// Field integrantes_familia
	$theValue = ($GLOBALS["x_integrantes_familia"] != "") ? intval($GLOBALS["x_integrantes_familia"]) : "NULL";
	$fieldList["`integrantes_familia`"] = $theValue;

	// Field dependientes
	$theValue = ($GLOBALS["x_dependientes"] != "") ? intval($GLOBALS["x_dependientes"]) : "NULL";
	$fieldList["`dependientes`"] = $theValue;

	// Field correo_electronico
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_correo_electronico"]) : $GLOBALS["x_correo_electronico"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`correo_electronico`"] = $theValue;

	// Field nombre_conyuge
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_conyuge"]) : $GLOBALS["x_nombre_conyuge"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre_conyuge`"] = $theValue;

	// Field calle
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle"]) : $GLOBALS["x_calle"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`calle`"] = $theValue;

	// Field colonia
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia"]) : $GLOBALS["x_colonia"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`colonia`"] = $theValue;

	// Field entidad
	$theValue = ($GLOBALS["x_entidad"] != "") ? intval($GLOBALS["x_entidad"]) : "NULL";
	$fieldList["`entidad`"] = $theValue;

	// Field codigo_postal
	$theValue = ($GLOBALS["x_codigo_postal"] != "") ? intval($GLOBALS["x_codigo_postal"]) : "NULL";
	$fieldList["`codigo_postal`"] = $theValue;

	// Field delegacion
	$theValue = ($GLOBALS["x_delegacion"] != "") ? intval($GLOBALS["x_delegacion"]) : "NULL";
	$fieldList["`delegacion`"] = $theValue;

	// Field vivienda_tipo
	$theValue = ($GLOBALS["x_vivienda_tipo"] != "") ? intval($GLOBALS["x_vivienda_tipo"]) : "NULL";
	$fieldList["`vivienda_tipo`"] = $theValue;

	// Field telefono_p
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_p"]) : $GLOBALS["x_telefono_p"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono_p`"] = $theValue;

	// Field telefono_c
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_c"]) : $GLOBALS["x_telefono_c"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono_c`"] = $theValue;

	// Field telefono_o
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_o"]) : $GLOBALS["x_telefono_o"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono_o`"] = $theValue;

	// Field antiguedad_v
	$theValue = ($GLOBALS["x_antiguedad_v"] != "") ? intval($GLOBALS["x_antiguedad_v"]) : "NULL";
	$fieldList["`antiguedad_v`"] = $theValue;

	// Field tel_arrendatario_v
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_arrendatario_v"]) : $GLOBALS["x_tel_arrendatario_v"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`tel_arrendatario_v`"] = $theValue;

	// Field renta_mensual_v
	$theValue = ($GLOBALS["x_renta_mensual_v"] != "") ? " '" . doubleval($GLOBALS["x_renta_mensual_v"]) . "'" : "NULL";
	$fieldList["`renta_mensual_v`"] = $theValue;

	// Field calle_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_calle_2"]) : $GLOBALS["x_calle_2"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`calle_2`"] = $theValue;

	// Field colonia_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_colonia_2"]) : $GLOBALS["x_colonia_2"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`colonia_2`"] = $theValue;

	// Field entidad_2
	$theValue = ($GLOBALS["x_entidad_2"] != "") ? intval($GLOBALS["x_entidad_2"]) : "NULL";
	$fieldList["`entidad_2`"] = $theValue;

	// Field codigo_postal_2
	$theValue = ($GLOBALS["x_codigo_postal_2"] != "") ? intval($GLOBALS["x_codigo_postal_2"]) : "NULL";
	$fieldList["`codigo_postal_2`"] = $theValue;

	// Field delegacion_2
	$theValue = ($GLOBALS["x_delegacion_2"] != "") ? intval($GLOBALS["x_delegacion_2"]) : "NULL";
	$fieldList["`delegacion_2`"] = $theValue;

	// Field vivienda_tipo_2
	$theValue = ($GLOBALS["x_vivienda_tipo_2"] != "") ? intval($GLOBALS["x_vivienda_tipo_2"]) : "NULL";
	$fieldList["`vivienda_tipo_2`"] = $theValue;

	// Field telefono_p_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_p_2"]) : $GLOBALS["x_telefono_p_2"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono_p_2`"] = $theValue;

	// Field telefono_c_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_c_2"]) : $GLOBALS["x_telefono_c_2"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono_c_2`"] = $theValue;

	// Field telefono_o_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_o_2"]) : $GLOBALS["x_telefono_o_2"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono_o_2`"] = $theValue;

	// Field antiguedad_n
	$theValue = ($GLOBALS["x_antiguedad_n"] != "") ? intval($GLOBALS["x_antiguedad_n"]) : "NULL";
	$fieldList["`antiguedad_n`"] = $theValue;

	// Field tel_arrendatario_n
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tel_arrendatario_n"]) : $GLOBALS["x_tel_arrendatario_n"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`tel_arrendatario_n`"] = $theValue;

	// Field garantia_desc
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_garantia_desc"]) : $GLOBALS["x_garantia_desc"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`garantia_desc`"] = $theValue;

	// Field garantia_valor
	$theValue = ($GLOBALS["x_garantia_valor"] != "") ? " '" . doubleval($GLOBALS["x_garantia_valor"]) . "'" : "NULL";
	$fieldList["`garantia_valor`"] = $theValue;

	// Field renta_mesual_n
	$theValue = ($GLOBALS["x_renta_mesual_n"] != "") ? " '" . doubleval($GLOBALS["x_renta_mesual_n"]) . "'" : "NULL";
	$fieldList["`renta_mesual_n`"] = $theValue;

	// Field referencia_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_1"]) : $GLOBALS["x_referencia_1"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`referencia_1`"] = $theValue;

	// Field referencia_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_2"]) : $GLOBALS["x_referencia_2"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`referencia_2`"] = $theValue;

	// Field referencia_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_3"]) : $GLOBALS["x_referencia_3"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`referencia_3`"] = $theValue;

	// Field referencia_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_referencia_4"]) : $GLOBALS["x_referencia_4"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`referencia_4`"] = $theValue;

	// Field telefono_ref_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_ref_1"]) : $GLOBALS["x_telefono_ref_1"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono_ref_1`"] = $theValue;

	// Field telefono_ref_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_ref_2"]) : $GLOBALS["x_telefono_ref_2"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono_ref_2`"] = $theValue;

	// Field telefono_ref_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_ref_3"]) : $GLOBALS["x_telefono_ref_3"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono_ref_3`"] = $theValue;

	// Field telefono_ref_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_ref_4"]) : $GLOBALS["x_telefono_ref_4"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`telefono_ref_4`"] = $theValue;

	// Field relacion_1
	$theValue = ($GLOBALS["x_relacion_1"] != "") ? intval($GLOBALS["x_relacion_1"]) : "NULL";
	$fieldList["`relacion_1`"] = $theValue;

	// Field relacion_2
	$theValue = ($GLOBALS["x_relacion_2"] != "") ? intval($GLOBALS["x_relacion_2"]) : "NULL";
	$fieldList["`relacion_2`"] = $theValue;

	// Field relacion_3
	$theValue = ($GLOBALS["x_relacion_3"] != "") ? intval($GLOBALS["x_relacion_3"]) : "NULL";
	$fieldList["`relacion_3`"] = $theValue;

	// Field relacion_4
	$theValue = ($GLOBALS["x_relacion_4"] != "") ? intval($GLOBALS["x_relacion_4"]) : "NULL";
	$fieldList["`relacion_4`"] = $theValue;

	// Field ing_fam_negocio
	$theValue = ($GLOBALS["x_ing_fam_negocio"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_negocio"]) . "'" : "NULL";
	$fieldList["`ing_fam_negocio`"] = $theValue;

	// Field ing_fam_otro_th
	$theValue = ($GLOBALS["x_ing_fam_otro_th"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_otro_th"]) . "'" : "NULL";
	$fieldList["`ing_fam_otro_th`"] = $theValue;

	// Field ing_fam_1
	$theValue = ($GLOBALS["x_ing_fam_1"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_1"]) . "'" : "NULL";
	$fieldList["`ing_fam_1`"] = $theValue;

	// Field ing_fam_2
	$theValue = ($GLOBALS["x_ing_fam_2"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_2"]) . "'" : "NULL";
	$fieldList["`ing_fam_2`"] = $theValue;

	// Field ing_fam_deuda_1
	$theValue = ($GLOBALS["x_ing_fam_deuda_1"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_deuda_1"]) . "'" : "NULL";
	$fieldList["`ing_fam_deuda_1`"] = $theValue;

	// Field ing_fam_deuda_2
	$theValue = ($GLOBALS["x_ing_fam_deuda_2"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_deuda_2"]) . "'" : "NULL";
	$fieldList["`ing_fam_deuda_2`"] = $theValue;

	// Field ing_fam_total
	$theValue = ($GLOBALS["x_ing_fam_total"] != "") ? " '" . doubleval($GLOBALS["x_ing_fam_total"]) . "'" : "NULL";
	$fieldList["`ing_fam_total`"] = $theValue;

	// Field ing_fam_cuales_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_1"]) : $GLOBALS["x_ing_fam_cuales_1"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ing_fam_cuales_1`"] = $theValue;

	// Field ing_fam_cuales_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_2"]) : $GLOBALS["x_ing_fam_cuales_2"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ing_fam_cuales_2`"] = $theValue;

	// Field ing_fam_cuales_13
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_13"]) : $GLOBALS["x_ing_fam_cuales_13"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ing_fam_cuales_13`"] = $theValue;

	// Field ing_fam_cuales_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_4"]) : $GLOBALS["x_ing_fam_cuales_4"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ing_fam_cuales_4`"] = $theValue;

	// Field ing_fam_cuales_5
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ing_fam_cuales_5"]) : $GLOBALS["x_ing_fam_cuales_5"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ing_fam_cuales_5`"] = $theValue;

	// Field flujos_neg_ventas
	$theValue = ($GLOBALS["x_flujos_neg_ventas"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_ventas"]) . "'" : "NULL";
	$fieldList["`flujos_neg_ventas`"] = $theValue;

	// Field flujos_neg_proveedor_1
	$theValue = ($GLOBALS["x_flujos_neg_proveedor_1"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_proveedor_1"]) . "'" : "NULL";
	$fieldList["`flujos_neg_proveedor_1`"] = $theValue;

	// Field flujos_neg_proveedor_2
	$theValue = ($GLOBALS["x_flujos_neg_proveedor_2"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_proveedor_2"]) . "'" : "NULL";
	$fieldList["`flujos_neg_proveedor_2`"] = $theValue;

	// Field flujos_neg_proveedor_3
	$theValue = ($GLOBALS["x_flujos_neg_proveedor_3"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_proveedor_3"]) . "'" : "NULL";
	$fieldList["`flujos_neg_proveedor_3`"] = $theValue;

	// Field flujos_neg_proveedor_4
	$theValue = ($GLOBALS["x_flujos_neg_proveedor_4"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_proveedor_4"]) . "'" : "NULL";
	$fieldList["`flujos_neg_proveedor_4`"] = $theValue;

	// Field flujos_neg_gasto_1
	$theValue = ($GLOBALS["x_flujos_neg_gasto_1"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_gasto_1"]) . "'" : "NULL";
	$fieldList["`flujos_neg_gasto_1`"] = $theValue;

	// Field flujos_neg_gasto_2
	$theValue = ($GLOBALS["x_flujos_neg_gasto_2"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_gasto_2"]) . "'" : "NULL";
	$fieldList["`flujos_neg_gasto_2`"] = $theValue;

	// Field flujos_neg_gasto_3
	$theValue = ($GLOBALS["x_flujos_neg_gasto_3"] != "") ? " '" . doubleval($GLOBALS["x_flujos_neg_gasto_3"]) . "'" : "NULL";
	$fieldList["`flujos_neg_gasto_3`"] = $theValue;

	// Field flujos_neg_cual_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_1"]) : $GLOBALS["x_flujos_neg_cual_1"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_1`"] = $theValue;

	// Field flujos_neg_cual_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_2"]) : $GLOBALS["x_flujos_neg_cual_2"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_2`"] = $theValue;

	// Field flujos_neg_cual_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_3"]) : $GLOBALS["x_flujos_neg_cual_3"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_3`"] = $theValue;

	// Field flujos_neg_cual_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_4"]) : $GLOBALS["x_flujos_neg_cual_4"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_4`"] = $theValue;

	// Field flujos_neg_cual_5
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_5"]) : $GLOBALS["x_flujos_neg_cual_5"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_5`"] = $theValue;

	// Field flujos_neg_cual_6
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_6"]) : $GLOBALS["x_flujos_neg_cual_6"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_6`"] = $theValue;

	// Field flujos_neg_cual_7
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_flujos_neg_cual_7"]) : $GLOBALS["x_flujos_neg_cual_7"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`flujos_neg_cual_7`"] = $theValue;

	// Field ingreso_negocio
	$theValue = ($GLOBALS["x_ingreso_negocio"] != "") ? " '" . doubleval($GLOBALS["x_ingreso_negocio"]) . "'" : "NULL";
	$fieldList["`ingreso_negocio`"] = $theValue;

	// Field inv_neg_fija_conc_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_fija_conc_1"]) : $GLOBALS["x_inv_neg_fija_conc_1"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_fija_conc_1`"] = $theValue;

	// Field inv_neg_fija_conc_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_fija_conc_2"]) : $GLOBALS["x_inv_neg_fija_conc_2"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_fija_conc_2`"] = $theValue;

	// Field inv_neg_fija_conc_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_fija_conc_3"]) : $GLOBALS["x_inv_neg_fija_conc_3"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_fija_conc_3`"] = $theValue;

	// Field inv_neg_fija_conc_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_fija_conc_4"]) : $GLOBALS["x_inv_neg_fija_conc_4"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_fija_conc_4`"] = $theValue;

	// Field inv_neg_fija_valor_1
	$theValue = ($GLOBALS["x_inv_neg_fija_valor_1"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_fija_valor_1"]) . "'" : "NULL";
	$fieldList["`inv_neg_fija_valor_1`"] = $theValue;

	// Field inv_neg_fija_valor_2
	$theValue = ($GLOBALS["x_inv_neg_fija_valor_2"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_fija_valor_2"]) . "'" : "NULL";
	$fieldList["`inv_neg_fija_valor_2`"] = $theValue;

	// Field inv_neg_fija_valor_3
	$theValue = ($GLOBALS["x_inv_neg_fija_valor_3"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_fija_valor_3"]) . "'" : "NULL";
	$fieldList["`inv_neg_fija_valor_3`"] = $theValue;

	// Field inv_neg_fija_valor_4
	$theValue = ($GLOBALS["x_inv_neg_fija_valor_4"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_fija_valor_4"]) . "'" : "NULL";
	$fieldList["`inv_neg_fija_valor_4`"] = $theValue;

	// Field inv_neg_total_fija
	$theValue = ($GLOBALS["x_inv_neg_total_fija"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_total_fija"]) . "'" : "NULL";
	$fieldList["`inv_neg_total_fija`"] = $theValue;

	// Field inv_neg_var_conc_1
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_var_conc_1"]) : $GLOBALS["x_inv_neg_var_conc_1"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_var_conc_1`"] = $theValue;

	// Field inv_neg_var_conc_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_var_conc_2"]) : $GLOBALS["x_inv_neg_var_conc_2"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_var_conc_2`"] = $theValue;

	// Field inv_neg_var_conc_3
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_var_conc_3"]) : $GLOBALS["x_inv_neg_var_conc_3"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_var_conc_3`"] = $theValue;

	// Field inv_neg_var_conc_4
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_inv_neg_var_conc_4"]) : $GLOBALS["x_inv_neg_var_conc_4"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`inv_neg_var_conc_4`"] = $theValue;

	// Field inv_neg_var_valor_1
	$theValue = ($GLOBALS["x_inv_neg_var_valor_1"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_var_valor_1"]) . "'" : "NULL";
	$fieldList["`inv_neg_var_valor_1`"] = $theValue;

	// Field inv_neg_var_valor_2
	$theValue = ($GLOBALS["x_inv_neg_var_valor_2"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_var_valor_2"]) . "'" : "NULL";
	$fieldList["`inv_neg_var_valor_2`"] = $theValue;

	// Field inv_neg_var_valor_3
	$theValue = ($GLOBALS["x_inv_neg_var_valor_3"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_var_valor_3"]) . "'" : "NULL";
	$fieldList["`inv_neg_var_valor_3`"] = $theValue;

	// Field inv_neg_var_valor_4
	$theValue = ($GLOBALS["x_inv_neg_var_valor_4"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_var_valor_4"]) . "'" : "NULL";
	$fieldList["`inv_neg_var_valor_4`"] = $theValue;

	// Field inv_neg_total_var
	$theValue = ($GLOBALS["x_inv_neg_total_var"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_total_var"]) . "'" : "NULL";
	$fieldList["`inv_neg_total_var`"] = $theValue;

	// Field inv_neg_activos_totales
	$theValue = ($GLOBALS["x_inv_neg_activos_totales"] != "") ? " '" . doubleval($GLOBALS["x_inv_neg_activos_totales"]) . "'" : "NULL";
	$fieldList["`inv_neg_activos_totales`"] = $theValue;
	
	// Field giro
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_giro_negocio"]) : $GLOBALS["x_giro_negocio"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`giro_negocio`"] = $theValue;
	
	// Field ubicacion
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion"]) : $GLOBALS["x_ubicacion"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ubicacion`"] = $theValue;
	
	// Field ubicacion_2
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion_2"]) : $GLOBALS["x_ubicacion_2"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ubicacion_2`"] = $theValue;
	
	//agregados por requeriemiento	
		//coiemntarios se garegaron por requerimiento
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_comentario_promotor"]) : $GLOBALS["x_comentario_promotor"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`comentario_promotor`"] = $theValue;
	
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_comentario_comite"]) : $GLOBALS["x_comentario_comite"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`comentario_comite`"] = $theValue;
	

	
		$sSql = "UPDATE `datos_aval` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE datos_aval_id = ".$GLOBALS["x_datos_aval_id"].""; 
		$x_result = phpmkr_query($sSql,$conn) or die("ERROR FATAL...".phpmkr_error()."SQL STATEMENT".$sSql);
			if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					exit();
		}
	
	
	
	
	
	
	
	return true;
}
?>
