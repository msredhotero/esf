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

	
	// ubicacion ...googlemaps
	$x_latlong  = $_POST["x_latlong"];	
	// ubicacion google maps, negocio
	$x_latlong2 = $_POST["x_latlong2"];	
	// mapa negocuio lleno o vacio 
	$x_hidden_mapa_negocio = $_POST["x_hidden_mapa_negocio"];
	
	$x_google_maps_id = @$_POST["x_google_maps_id"];
	$x_google_maps_neg_id = @$_POST["x_google_maps_neg_id"];
	
	
	// nuevos campos agregados
	$x_tipo_inmueble_id = $_POST["x_tipo_inmueble_id"];// ya
	$x_entidad_nacimiento = $_POST["x_entidad_nacimiento"];
	$x_rol_hogar_id = $_POST["x_rol_hogar_id"];	
	$x_fecha_ini_act_prod = $_POST["x_fecha_ini_act_prod"];
	
	
	$x_estudio_id = $_POST["x_estudio_id"];
	$x_ppe = $_POST["x_ppe"];
	$x_ingreso_semanal = $_POST["x_ingreso_semanal"];
	
	//direccion
	$x_localidad_id = $_POST["x_localidad_id"]; // ya
	$x_ubicacion = @$_POST["x_ubicacion"]; // ya
	$x_localidad_id2 = $_POST["x_localidad_id2"]; // ya
	$x_ubicacion_2 = @$_POST["x_ubicacion_2"];// ya
	$x_numero_exterior = $_POST["x_numero_exterior"]; // ya
	$x_numero_exterior2 = $_POST["x_numero_exterior2"]; // ya
	$x_tel_arrendatario_v = $_POST["x_tel_arrendatario_v"]; // ya
	
	
	//SE VA A LA TABLA GARANTIA
	 $x_tipo_garantia = $_POST["x_tipo_garantia"];
	 $x_modelo_garantia = $_POST["x_modelo_garantia"];
	 $x_garantia_valor_factura = $_POST["x_garantia_valor_factura"];
	
	
	
	//personas politicamente expuestas
	$x_parentesco_ppe = $_POST["x_parentesco_ppe"];
	$x_nombre_ppe = $_POST["x_nombre_ppe"];
	$x_apellido_paterno_ppe = $_POST["x_apellido_paterno_ppe"];
	$x_apellido_materno_ppe = $_POST["x_apellido_materno_ppe"];
	
	
	// negocio
	//en negocio
	
	$x_giro_negocio_id = $_POST["x_giro_negocio_id"];
	$x_atiende_titular = $_POST["x_atiende_titular"];
	$x_personas_trabajando = $_POST["x_personas_trabajando"];
	$x_destino_credito_id = @$_POST["x_destino_credito_id"];
	
	//GASTOS
	$x_renta_mensula_domicilio = @$_POST["x_renta_mensual_v"];  // se guaradara en el gasto	
	$x_renta_mensula_domicilio2 = @$_POST["x_renta_mensual_n"]; //no existe en la tabla domicilio
	
	



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
<link href="http://code.google.com/apis/maps/documentation/javascript/examples/default.css" rel="stylesheet" type="text/css" /> 
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script language="javascript" src="tipoCuenta/formatos/js/carga_telefonos.js"></script>
<script src="../../mapsNegocio.js"></script>

<script type="text/javascript" src="ew.js"></script>

<script language="javascript" src="js/carga_telefonos.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>

<script type="text/javascript"> 
  var geocoder;
  var map;  
  var markersArray = [];
 
  
  function initialize() {
    geocoder = new google.maps.Geocoder();
	// lo localizamos en la cuidad de mexico
	
	var lat = document.getElementById("x_latitud").value;
    var long = document.getElementById("x_longitud").value;
	if(lat == 0 && long == 0){
		lat = 19.3393632;
		long = -99.19202559999997;
		}
    var latlng = new google.maps.LatLng(lat,long);
    var myOptions = {
      zoom: 12,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);	
	addMarker(latlng);
	document.getElementById("x_latlong").value = latlng;
  }
  
  function addMarker(location) {
	  
    marker = new google.maps.Marker({
      position: location,
      map: map
	  
    });
    markersArray.push(marker);
  }
 
  function codeAddress() {
	  deleteOverlays();
    var address = document.getElementById("address").value;
    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        map.setCenter(results[0].geometry.location);
        var marker = new google.maps.Marker({
            map: map, 
            position: results[0].geometry.location
			
        });
		//creamos un array con los markers
		markersArray.push(marker);
		//asignamos el valor de latidu y longitud a la variable hidden x_ubicacion
		document.getElementById("x_latlong").value = results[0].geometry.location;
		
		
      } else {
        alert("Geocode was not successful for the following reason: " + status);
      }
    });
  }
  
  // eliminamos los maracadores
  function deleteOverlays() {
    if (markersArray) {
      for (i in markersArray) {
        markersArray[i].setMap(null);
      }
      markersArray.length = 0;
    }
  }
  
  
 
	  
function mostrarOriginal(){
	  deleteOverlays();
	  var lat = document.getElementById("x_latitud").value;
      var long = document.getElementById("x_longitud").value;
	  var latlng = new google.maps.LatLng(lat,long);
	  
	  
	  var myOptions = {
      zoom: 12,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	addMarker(latlng)
   
	document.getElementById("x_latlong").value = latlng;
	 }
   </script> 
   <script type="text/javascript"> 
  var geocoder;
  var map;  
  var markersArray2 = [];
 
  
  function initialize2() {
    geocoder2 = new google.maps.Geocoder();
	// lo localizamos en la cuidad de mexico
	
	var lat2 = document.getElementById("x_latitud2").value;
    var long2 = document.getElementById("x_longitud2").value;
	if(lat2 == 0 && long2 == 0){
		lat2 = 19.3393632;
		long2 = -99.19202559999997;
		}
    var latlng2 = new google.maps.LatLng(lat2,long2);
    var myOptions2 = {
      zoom: 12,
      center: latlng2,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map2 = new google.maps.Map(document.getElementById("map_canvas2"), myOptions2);	
	addMarker2(latlng2);
	document.getElementById("x_latlong2").value = latlng2;
  }
  
  function addMarker2(location) {
	  
    marker2 = new google.maps.Marker({
      position: location,
      map: map2
	  
    });
    markersArray2.push(marker2);
  }
 
  function codeAddress2() {
	  deleteOverlays2();
    var address2 = document.getElementById("address2").value;
    geocoder2.geocode( { 'address': address2}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        map2.setCenter(results[0].geometry.location);
        var marker2 = new google.maps.Marker({
            map: map2, 
            position: results[0].geometry.location
			
        });
		//creamos un array con los markers
		markersArray2.push(marker2);
		//asignamos el valor de latidu y longitud a la variable hidden x_ubicacion
		document.getElementById("x_latlong2").value = results[0].geometry.location;
		
		
      } else {
        alert("Geocode was not successful for the following reason: " + status);
      }
    });
  }
  
  // eliminamos los maracadores
  function deleteOverlays2() {
    if (markersArray2) {
      for (i in markersArray2) {
        markersArray2[i].setMap(null);
      }
      markersArray2.length = 0;
    }
  }
  
  
 
	  
function mostrarOriginal(){
	  deleteOverlays();
	  var lat = document.getElementById("x_latitud").value;
      var long = document.getElementById("x_longitud").value;
	  var latlng = new google.maps.LatLng(lat,long);
	  
	  
	  var myOptions = {
      zoom: 12,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	addMarker(latlng)
   
	document.getElementById("x_latlong").value = latlng;
	 }
	 
	 
	 
 function quitaMapaNegocio(){
	 // borramos el espacio desiganado a ael mapa de  negocio
	 document.getElementById("mapaNegocio").innerHTML="";
	 document.getElementById("x_hidden_mapa_negocio").value = 0;
	 }	
	 
function cargaMapaNegocio(){
	document.getElementById("x_hidden_mapa_negocio").value = 1;
	cargaMapa();
		setTimeout("initialize2()",1000);
	}	 	 
   </script> 




<script language="javascript">
function cargaEventos(){
	
	initialize();
	
	
	if(document.getElementById("x_hidden_mapa_negocio").value == 1){
			initialize2();
			
			//si los mapas son diferentes cargamos el mapa dos.
		}else{
			// quito el ma´pa que esta pintado
			quitaMapaNegocio();
			}

	
	
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
	
	
function actualizaReferencia(tipo){
	
	this.tipo = tipo;
	
	if(tipo == 1){
		
		var nuevoValor = parseInt(document.getElementById("contador_telefono").value);
		nuevoValor = nuevoValor + 1;
		document.getElementById("contador_telefono").value = nuevoValor;
		cargaCampoTelefono();
		}else if( tipo == 2){
			var nuevoValor = parseInt(document.getElementById("contador_celular").value);
			nuevoValor = nuevoValor + 1;
			document.getElementById("contador_celular").value = nuevoValor;
			cargaCampoCelular();
			}
	
	
	
	}
	
function cargaCampoTelefono(){
	var id = parseInt(document.getElementById("contador_telefono").value);
	process_tel(id, 1);
	
	}
	
function cargaCampoCelular(){
	var id = document.getElementById("contador_celular").value;
	process_tel(id, 2);
	
	}		

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
<form name="datos_avaladd" id="datos_avaladd" action="" method="post" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_add" value="A">
<input  type="hidden" name="x_cliente_id" value="1" />
<input type="hidden" name="x_datos_aval_id" id="x_datos_aval_id" value="<?php echo($x_datos_aval_id);?>" />
<input type="hidden" name="x_google_maps_id" id="x_google_maps_id" value="<?php  echo $x_google_maps_id; ?>" />
<input type="hidden" name="x_google_maps_neg_id" id="x_google_maps_neg_id" value="<?php  echo $x_google_maps_id; ?>" />

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

<!--pagina uno -->

<tr>
  <td>&nbsp;</td></tr>
<tr>
<td>

<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
      <td colspan="11"  align="left" valign="top"class="texto_normal_bold"><div></div></td>
    </tr>
     <tr>
      <td colspan="11"  align="left" valign="top"class="texto_normal_bold">&nbsp;</td>
    </tr>
  <tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Datos Personales Aval</td>
    </tr>

     <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td width="98" class="texto_normal" >Titular</td>
      <td colspan="8" align="center"><table width="98%">
        <tr>
          <td width="33%">
          <input type="text" name="x_nombre_completo" id="x_nombre_completo" value="<?php echo htmlentities($x_nombre)?>" maxlength="250" size="35" <?php echo $x_readonly;?>  />
          </td>
          <td width="34%"><input type="text" name="x_apellido_paterno" id="x_apellido_parterno" value="<?php echo htmlentities(@$x_apellido_paterno) ?>" maxlength="250" size="35" <?php echo $x_readonly;?> /></td>
          <td width="33%"><input type="text" name="x_apellido_materno" id="x_apellido_materno" value="<?php echo htmlentities(@$x_apellido_materno) ?>" maxlength="250" size="35" <?php echo $x_readonly;?> /></td>
          </tr>
        <tr>
          <td class="texto_normal"> Nombre</td>
          <td>Apellido Paterno</td>
          <td>Apellido Materno</td>
          </tr>
        </table></td>
    </tr>
     <tr>
      <td>Fecha Nac</td>
      <td colspan="2"><span class="texto_normal">
              <input name="x_fecha_nacimiento" type="text" <?php echo $x_readonly;?> id="x_fecha_nacimiento" value="<?php echo FormatDateTime(@$x_fecha_nacimiento,7); ?>" size="25" onChange="generaCurpRfc(this,'txtHintcurp', 'txtHintrfc');"  />
              &nbsp;<img src="../images/ew_calendar.gif" id="cx_fecha_nacimiento" onClick="javascript: Calendar.setup(
            {
            inputField : 'x_fecha_nacimiento', 
            ifFormat : '%d/%m/%Y',
            button : 'cx_fecha_nacimiento' 
            }
            );" style="cursor:pointer;cursor:hand;" />
              </span></td>
      <td width="66">Sexo</td>
      <td width="110"><label>
        <select name="x_sexo" id="x_sexo" <?php echo $x_readonly2;?> onChange="generaCurpRfc(this,'txtHintcurp', 'txtHintrfc');">
        <option value="1" <?php if($x_sexo == 1){echo("SELECTED");} ?> >Masculino</option> 
		<option value="2" <?php if($x_sexo == 2){echo("SELECTED");} ?>>Femenino</option>
        </select>
      </label></td>
       <td width="317">Lugar de nacimiento</td>
      <td colspan="5"><?php
		$x_entidad_idList = "<select name=\"x_entidad_nacimiento\" $x_readonly2 id=\"x_entidad_nacimiento\" onchange=\"generaCurpRfc(this,'txtHintcurp', 'txtHintrfc')\" >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `entidad_nacimiento_id`, `descripcion`, cve_entidad FROM `entidad_nacimiento`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["entidad_nacimiento_id"] == @$x_entidad_nacimiento ) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?></td>
    </tr>
    <tr>
      <td>RFC</td>
      <td colspan="2"><div id="txtHintrfc"><input type="text" name="x_rfc" id="x_rfc" size="20" maxlength="150" value="<?php echo htmlentities(@$x_rfc) ?>" <?php echo $x_readonly;?> /></div></td>
      <td width="66">CURP</td>
      <td width="110"><div id="txtHintcurp"><input type="text" name="x_curp" id="x_curp" size="20" maxlength="150" value="<?php echo htmlentities(@$x_curp) ?>"  <?php echo $x_readonly;?>/></div></td>
      <td>Correo Electronico</td>
      <td colspan="5"><input type="text" name="x_correo_electronico" id="x_correo_electronico" value="<?php echo htmlspecialchars(@$x_correo_electronico) ?>"  maxlength="100" size="35" <?php echo $x_readonly;?>/></td>
    </tr>
    
    <tr>
      <td>Integrantes Familia</td>
      <td colspan="2"><input type="text" name="x_integrantes_familia" id="x_integrantes_familia" value="<?php echo htmlspecialchars(@$x_integrantes_familia) ?>" maxlength="30" size="25"  onkeypress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
      <td colspan="2">Dependientes</td>
      <td colspan="6"><input type="text" name="x_dependientes" id="x_dependientes" value="<?php echo htmlspecialchars(@$x_dependientes) ?>"  maxlength="30" size="30"  onkeypress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
    </tr>
    <tr>
      <td height="25">Esposo(a)</td>
      <td colspan="10"><input type="text" name="x_esposa" id="x_esposa"  value="<?php echo htmlspecialchars(@$x_esposa) ?>" maxlength="250" size="100" <?php echo $x_readonly;?>/></td>
    </tr>
    <tr>
      <td>Escolaridad</td>
      <td colspan="2"><?php
		$x_entidad_idList = "<select name=\"x_estudio_id\"  $x_readonly2 id=\"x_estudio_id\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `estudio_id`, `descripcion` FROM `estudios`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["estudio_id"] == @$x_estudio_id) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?></td>
      <td colspan="2">Rol en el hogar</td>
      <td colspan="6"><?php
		$x_entidad_idList = "<select name=\"x_rol_hogar_id\" id=\"x_rol_hogar_id\"   $x_readonly2 >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `rol_hogar_id`, `descripcion` FROM `rol_hogar`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["rol_hogar_id"] == @$x_rol_hogar_id) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?></td>
    </tr>
    <tr>
      <td>Fecha inicio de actividad productiva</td>
      <td colspan="2"><input type="text" name="x_fecha_ini_act_prod" id="x_fecha_ini_act_prod" value="<?php echo FormatDateTime(@$x_fecha_ini_act_prod,7);?>"   maxlength="100" size="30" <?php echo $x_readonly;?>/>
      &nbsp;<img src="../images/ew_calendar.gif" id="cxfecha_ini_act_prod" onclick="javascript: Calendar.setup(
            {
            inputField : 'x_fecha_ini_act_prod', 
           ifFormat : '%d/%m/%Y', 
            button : 'cxfecha_ini_act_prod' 
            }
            );" style="cursor:pointer;cursor:hand;" /></td>
      <td colspan="2">Destino Credito</td>
      <td colspan="6"><?php
		$x_entidad_idList = "<select name=\"x_destino_credito_id\"  $x_readonly2 id=\"x_destino_credito_id\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `destino_credito_id`, `descripcion` FROM `destino_credito`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["destino_credito_id"] == @$x_destino_credito_id) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?></td>
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
      <td colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Personas Pol&iacute;ticamente Expuestas</td>
    </tr>
     <tr>
      <td colspan="10">&nbsp;</td>
      </tr>
    <tr>
      <td colspan="4">Usted o alg&uacute;n familiar  cuenta con cargo pol&iacute;tico, dentro o fuera del territorio M&eacute;xicano? </td>
      <td><select name="x_ppe" id="x_ppe" <?php echo $x_readonly2;?> >
        <option value="0">Seleccione</option>
        <option value="1" <?php  if($x_ppe == 1) {?> selected="selected" <?php }?> >Si</option>
        <option value="2" <?php  if($x_ppe == 2) {?> selected="selected" <?php }?>>No</option>
      </select></td>
      <td colspan="6"><table width="341"  border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="52">Relaci&oacute;n</td>
          <td width="147" ><?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_ppe\"  $x_readonly2 id=\"x_parentesco_ppe\" >";
		$x_parentesco_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `parentesco_tipo_id`, `descripcion` FROM `parentesco_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_parentesco_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["parentesco_tipo_id"] == @$x_parentesco_ppe) {
					$x_parentesco_tipo_idList .= "' selected";
				}
				$x_parentesco_tipo_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_parentesco_tipo_idList .= "</select>";
		echo $x_parentesco_tipo_idList;
		?></td>
        </tr>
      </table></td>
      </tr>
    <tr>
      <td colspan="11"  align="left"><table width="71%">
        <tr>
          <td width="26%"><input type="text" name="x_nombre_ppe" id="x_nombre_ppe"  value="<?php  echo $x_nombre_ppe; ?>" maxlength="250" size="35" <?php echo $x_readonly;?>/></td>
          <td width="25%"><input type="text" name="x_apellido_paterno_ppe"  value="<?php  echo $x_apellido_paterno_ppe; ?>" id="x_apellido_parterno_ppe" maxlength="250" size="35" <?php echo $x_readonly;?> /></td>
          <td width="49%"><input type="text" name="x_apellido_materno_ppe" value="<?php  echo $x_apellido_materno_ppe; ?>" id="x_apellido_materno_ppe" maxlength="250" size="35"  <?php echo $x_readonly;?> /></td>
          </tr>
        <tr>
          <td>Nombre</td>
          <td>Apellido Paterno</td>
          <td>Apellido Materno</td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    
    <tr>
      <td colspan="11" align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold"> Domicilio </td>
    </tr>
      <tr>
      <td colspan="11" id="tableHead"></td>
    </tr>
    <tr>
      <td colspan="11" id="tableHead"></td>
    </tr>
    <tr>
      <td colspan="11" id="tableHead"><input  type="hidden"  name="x_hidden_mapa_negocio" id="x_hidden_mapa_negocio" value="<?php echo $x_hidden_mapa_negocio;?>" />&nbsp;</td>
    </tr>
    <tr>
      <td>Calle</td>
      <td colspan="4"><input type="text" name="x_calle" id="x_calle" value="<?php echo htmlentities($x_calle);?>"  maxlength="100" size="60" <?php echo $x_readonly;?>/></td>
      <td colspan="5">&nbsp;N&uacute;mero exterior&nbsp;&nbsp;<input type="text" name="x_numero_exterior" id="x_numero_exterior" value="<?php echo ($x_numero_exterior);?>"  maxlength="20" size="20" <?php echo $x_readonly;?>/></td>
    </tr>
    <tr>
      <td>Colonia</td>
      <td colspan="4"><input type="text" name="x_colonia" id="x_colonia"  value="<?php echo htmlspecialchars(@$x_colonia) ?>" maxlength="100" size="50" <?php echo $x_readonly;?>/></td>
      <td>C&oacute;digo Postal </td>
      <td colspan="5"><input type="text" name="x_codigo_postal" id="x_codigo_postal" value="<?php echo htmlspecialchars(@$x_codigo_postal) ?>"  maxlength="10" size="20"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
    </tr>
    <tr>
      <td>Entidad</td>
      <td colspan="4"><?php
		$x_entidad_idList = "<select name=\"x_entidad\" id=\"x_entidad\" $x_readonly2 onchange=\"showHint(this,'txtHint1', 'x_delegacion_id')\" >";
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
      <td colspan="6"><div align="left"><span class="texto_normal">
        
        </span><span class="texto_normal">
          <div id="txtHint1" class="texto_normal">
            Del/Mun:
            <?php
		if($x_entidad > 0) {
		$x_delegacion_idList = "<select name=\"x_delegacion_id\"  echo $x_readonly2>";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where entidad_id = $x_entidad";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["delegacion_id"] == @$x_delegacion_id) {
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
    </tr>
    <tr>
      <td>Localidad</td>
      <td colspan="4"><div id="txtHint3" class="texto_normal">
        <?php  
		
		if(!empty($x_localidad_id)){
$x_delegacion_idList = "<select name=\"x_localidad_id\" $x_readonly2 >";
$x_delegacion_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT localidad_id, descripcion FROM localidad where localidad_id = $x_localidad_id";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["localidad_id"] == @$x_localidad_id) {
			$x_delegacion_idList .= "' selected";
		}
		$x_delegacion_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);

$x_delegacion_idList .= "</select>";
		}
echo $x_delegacion_idList;
      ?>
      </div></td>
      <td>Ubicacion</td>
      <td colspan="5">
      <strong>
      <input type="text" name="x_ubicacion" id="x_ubicacion" value="<?php echo htmlspecialchars(@$x_ubicacion) ?>"  maxlength="250" size="35" <?php echo $x_readonly;?>/>
      </strong></td>
    </tr>
    <tr>
      <td>Tipo Vivienda</td>
      <td colspan="4">
      <?php
		$x_vivienda_tipo_idList = "<select name=\"x_tipo_vivienda\" $x_readonly2 id=\"x_tipo_vivienda\"  class=\"texto_normal\" onchange=\"viviendatipo('1')\">";
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
      <td>Antiguedad (a&ntilde;os)</td>
      <td colspan="5"><input type="text" name="x_antiguedad_v" id="x_antiguedad_v"  value="<?php echo htmlspecialchars(@$x_antiguedad_v) ?>" maxlength="10" size="20" <?php echo $x_readonly;?> /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="4">&nbsp;</td>
      <td>Tel. Arrendatario</td>
      <td colspan="5"><input type="text" name="x_tel_arrendatario_v" id="x_tel_arrendatario_v"  value="<?php echo htmlspecialchars(@$x_tel_arrendatario_v) ?>" maxlength="20" size="20" <?php echo $x_readonly;?> /></td>
    </tr>
    <tr>
      <td height="24"><?php if(!empty($x_telefono_domicilio)){?>Tel Domicilio<?php }?></td>
      <td width="144"><?php if(!empty($x_telefono_domicilio)){?><input type="text" name="x_telefono_domicilio" id="x_telefono_domicilio"  value="<?php echo htmlspecialchars(@$x_telefono_domicilio) ?>" maxlength="50" size="25" <?php echo $x_readonly;?>/><?php } ?></td>
      <td width="87"><?php if(!empty( $x_otro_tel_domicilio_1)){?>Otro Tel<?php }?></td>
      <td colspan="2"><?php if(!empty($x_otro_tel_domicilio_1 )){?><input type="text" name="x_otro_tel_domicilio_1" id="x_otro_tel_domicilio_1"  value="<?php echo htmlspecialchars(@$x_otro_tel_domicilio_1) ?>" maxlength="50" size="25" <?php echo $x_readonly;?>/><?php }?></td>
      <td>Renta Mensual</td>
      <td colspan="5"><input type="text" name="x_renta_mensual_v" id="x_renta_mensual_v" value="<?php echo number_format(@$x_renta_mensual_v, 0, '.', '') ?>" maxlength="25" size="20"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
    </tr>
    <tr>
      <td><?php if(!empty($x_celular)){?>Celular<?php }?></td>
      <td><?php if(!empty($x_celular)){?><input type="text" name="x_celular" id="x_celular" value="<?php echo $x_celular;?>"  maxlength="10" size="25" <?php echo $x_readonly;?>/><?php } ?></td>
      <td><?php if(!empty($x_compania_celular_id)){?>Compa&ntilde;ia <?php }?></td>
      <td colspan="2"><?php if(!empty($x_compania_celular_id)){?><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_id\" id=\"x_compania_celular_id\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_id) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?><?php }?></td>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td><?php if(!empty($x_otro_telefono_domicilio_2)){?>Otro Celular<?php }?></td>
      <td><?php if(!empty($x_otro_telefono_domicilio_2)){?><input type="text" name="x_otro_telefono_domicilio_2" id="x_otro_telefono_domicilio_2"  value="<?php echo $x_otro_telefono_domicilio_2;?>" maxlength="50" size="25" <?php echo $x_readonly;?>/><?php }?></td>
      <td><?php if(!empty($x_compania_celular_id_2)){?>Compa&ntilde;ia<?php }?></td>
      <td colspan="2"><?php if(!empty($x_compania_celular_id_2)){?><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_id_2\" id=\"x_compania_celular_id_2\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_id_2) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?><?php }?></td>
      <td colspan="6"><div id="x_guarda_direccion_a"><input type="button" value="Guardar direccion"  onclick="GuardaDireccionAntigua(<?php echo $x_cliente_id;?>)"/></div></td>
    </tr>
      <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
     <tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Telefonos nueva seccion </td>
    </tr>
    
    <tr>
      <td colspan="11" id="tableHead"><table width="100%" border="0">
  <tr>
    <td colspan="4"  bgcolor="#CCFFCC">Telefonos fijos <?php if($contador_telefono < 1){$contador_telefono = 1;}?> 
      <input type="hidden" name="contador_telefono"  id="contador_telefono" value="<?php echo $contador_telefono ?>"/> </td>
    <td width="58%" colspan="6"  bgcolor="#87CEEB">Celulares<?php if($contador_celular < 1) {$contador_celular = 1;}?> <input type="hidden" name="contador_celular" id="contador_celular" value="<?php echo $contador_celular;?>" /></td>
    </tr> </table>
    <table width="100%" border="0">
  <tr>
    <td width="41%"><table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_1" type="text" value="<?php echo $x_telefono_casa_1 ?>" <?php echo $x_readonly;?> size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td>
        <select name="x_comentario_casa_1">
        <option value="">Seleccione</option>
         <option value="Domicilio" <?php if ($x_comentario_casa_1 == "Domicilio"){ ?> selected="selected" <?php }?>>Domicilio</option>
         <option value="Negocio" <?php if ($x_comentario_casa_1 == "Negocio"){ ?> selected="selected" <?php }?>>Negocio</option>
        </select>
        <!--<input name="x_comentario_casa_1" type="text" value="<?php echo $x_comentario_casa_1; ?>" size="30" maxlength="250" />--></td>
      </tr>
    </table></td>
    <td width="59%"><table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_1" type="text" size="13" maxlength="250" value="<?php echo $x_telefono_celular_1 ?>" <?php echo $x_readonly;?>  onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%">
		<?php
		$x_entidad_idList = "<select name=\"x_compania_celular_1\" id=\"x_compania_celular_1\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_1) {
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
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_1" value="<?php echo $x_comentario_celular_1;?>"  size="30" maxlength="250" /></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td> <div id="x_telefono_casa_2">
    <?php if($x_telefono_casa_2){ ?>
    <table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_2" type="text" value="<?php echo $x_telefono_casa_2;?>" <?php echo $x_readonly;?> size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td>
        <select name="x_comentario_casa_2">
        <option value="">Seleccione</option>
         <option value="Domicilio" <?php if ($x_comentario_casa_2 == "Domicilio"){ ?> selected="selected" <?php }?>>Domicilio</option>
         <option value="Negocio" <?php if ($x_comentario_casa_2 == "Negocio"){ ?> selected="selected" <?php }?>>Negocio</option>
        </select>
        <!--<input name="x_comentario_casa_2'" type="text" value="<?php echo $x_comentario_casa_2;?>" size="30" maxlength="250" />--></td>
      </tr>
    </table>
    <?php }?>  
    </div></td>
    <td><div id="telefono_celular_2">
    <?php if(!empty($x_telefono_celular_2)) {?>
    <table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_2"  value="<?php echo $x_telefono_celular_2; ?>" type="text" size="13" maxlength="250" <?php echo $x_readonly;?>  onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%"><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_2\" id=\"x_compania_celular_2\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {

			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_2) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?> </td>
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_2"  value="<?php echo $x_comentario_celular_2;?>"size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?>    
    
    </div></td>
  </tr>
  <tr>
    <td> <div id="x_telefono_casa_3">
      <?php if($x_telefono_casa_3){ ?>
    <table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_3" type="text" value="<?php $x_telefono_casa_3;?>"  <?php echo $x_readonly;?> size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td>
        <select name="x_comentario_casa_3">
        <option value="">Seleccione</option>
         <option value="Domicilio" <?php if ($x_comentario_casa_3 == "Domicilio"){ ?> selected="selected" <?php }?>>Domicilio</option>
         <option value="Negocio" <?php if ($x_comentario_casa_3 == "Negocio"){ ?> selected="selected" <?php }?>>Negocio</option>
        </select>
        <!--<input name="x_comentario_casa_3'" type="text" value="<?php x_comentario_casa_3;?>" size="30" maxlength="250" />--></td>
      </tr>
    </table>
    <?php }?> 
    
    </div></td>
    <td><div id="telefono_celular_3">
    <?php if(!empty($x_telefono_celular_3)) {?>
    <table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_3"  value="<?php echo $x_telefono_celular_3; ?>"type="text" size="13" maxlength="250" <?php echo $x_readonly;?> onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%"><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_3\" id=\"x_compania_celular_3\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_3) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?> </td>
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_3"  value="<?php echo $x_comentario_celular_3;?>"size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?>    
    
    </div></td>
  </tr>
  <tr>
    <td> <div id="x_telefono_casa_4">
      <?php if($x_telefono_casa_4){ ?>
    <table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_4" type="text" value="<?php $x_telefono_casa_4;?>" <?php echo $x_readonly;?> size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td>
        <select name="x_comentario_casa_4">
        <option value="">Seleccione</option>
         <option value="Domicilio" <?php if ($x_comentario_casa_4 == "Domicilio"){ ?> selected="selected" <?php }?>>Domicilio</option>
         <option value="Negocio" <?php if ($x_comentario_casa_4 == "Negocio"){ ?> selected="selected" <?php }?>>Negocio</option>
        </select>
        <!--<input name="x_comentario_casa_4'" type="text" value="<?php x_comentario_casa_4;?>" size="30" maxlength="250" />--></td>
      </tr>
    </table>
    <?php }?> 
    </div></td>
    <td><div id="telefono_celular_4">
    <?php if(!empty($x_telefono_celular_4)) {?>
    <table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_4"  value="<?php echo $x_telefono_celular_4; ?>"type="text" size="13" maxlength="250" <?php echo $x_readonly;?> onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%"><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_4\" id=\"x_compania_celular_4\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_4) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?> </td>
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_4"  value="<?php echo $x_comentario_celular_4;?>"size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?>    
    </div></td>
  </tr><tr>
    <td> <div id="x_telefono_casa_5">
      <?php if($x_telefono_casa_5){ ?>
    <table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_5" type="text" value="<?php $x_telefono_casa_5;?>" <?php echo $x_readonly;?> size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td>
        <select name="x_comentario_casa_5">
        <option value="">Seleccione</option>
         <option value="Domicilio" <?php if ($x_comentario_casa_5 == "Domicilio"){ ?> selected="selected" <?php }?>>Domicilio</option>
         <option value="Negocio" <?php if ($x_comentario_casa_5 == "Negocio"){ ?> selected="selected" <?php }?>>Negocio</option>
        </select>
        <!--<input name="x_comentario_casa_5'" type="text" value="<?php x_comentario_casa_5;?>" size="30" maxlength="250" />--></td>
      </tr>
    </table>
    <?php }?> 
    </div></td>
    <td><div id="telefono_celular_5">
    <?php if(!empty($x_telefono_celular_5)) {?>
    <table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_5"  value="<?php echo $x_telefono_celular_5; ?>"type="text" size="13" maxlength="250" <?php echo $x_readonly;?>  onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%"><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_5\" id=\"x_compania_celular5\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_5) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?> </td>
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_5"  value="<?php echo $x_comentario_celular_5;?>"size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?>    
    </div></td>
  </tr><tr>
    <td> <div id="x_telefono_casa_6">
      <?php if($x_telefono_casa_6){ ?>
    <table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_6" type="text" value="<?php $x_telefono_casa_6;?>" <?php echo $x_readonly;?> size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td>
        <select name="x_comentario_casa_6">
        <option value="">Seleccione</option>
         <option value="Domicilio" <?php if ($x_comentario_casa_6 == "Domicilio"){ ?> selected="selected" <?php }?>>Domicilio</option>
         <option value="Negocio" <?php if ($x_comentario_casa_6 == "Negocio"){ ?> selected="selected" <?php }?>>Negocio</option>
        </select>
        <!--<input name="x_comentario_casa_6'" type="text" value="<?php x_comentario_casa_6;?>" size="30" maxlength="250" />--></td>
      </tr>
    </table>
    <?php }?> 
    </div></td>
    <td><div id="telefono_celular_6">
    <?php if(!empty($x_telefono_celular_6)) {?>
    <table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_6"  value="<?php echo $x_telefono_celular_6; ?>"type="text" size="13" maxlength="250" <?php echo $x_readonly;?>  onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%"><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_6\" id=\"x_compania_celular_6\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_6) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?> </td>
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_6"  value="<?php echo $x_comentario_celular_6;?>"size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?>    
    </div></td>
  </tr><tr>
    <td> <div id="x_telefono_casa_7">
      <?php if($x_telefono_casa_7){ ?>
    <table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_7" type="text" value="<?php $x_telefono_casa_7;?>" <?php echo $x_readonly;?> size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td>
        <select name="x_comentario_casa_7">
        <option value="">Seleccione</option>
         <option value="Domicilio" <?php if ($x_comentario_casa_7 == "Domicilio"){ ?> selected="selected" <?php }?>>Domicilio</option>
         <option value="Negocio" <?php if ($x_comentario_casa_7 == "Negocio"){ ?> selected="selected" <?php }?>>Negocio</option>
        </select>
        <!--<input name="x_comentario_casa_7'" type="text" value="<?php x_comentario_casa_7;?>" size="30" maxlength="250" />--></td>
      </tr>
    </table>
    <?php }?> 
    </div></td>
    <td><div id="telefono_celular_7">
    <?php if(!empty($x_telefono_celular_7)) {?>
    <table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_7"  value="<?php echo $x_telefono_celular_7; ?>"type="text" size="13" maxlength="250" <?php echo $x_readonly;?> onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%"><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_7\" id=\"x_compania_celular_7\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_7) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?> </td>
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_7"  value="<?php echo $x_comentario_celular_7;?>"size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?>    
    </div></td>
  </tr><tr>
    <td> <div id="x_telefono_casa_8">
      <?php if($x_telefono_casa_8){ ?>
    <table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_8" type="text" value="<?php $x_telefono_casa_8;?>" size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td>
        <select name="x_comentario_casa_8">
        <option value="">Seleccione</option>
         <option value="Domicilio" <?php if ($x_comentario_casa_8 == "Domicilio"){ ?> selected="selected" <?php }?>>Domicilio</option>
         <option value="Negocio" <?php if ($x_comentario_casa_8 == "Negocio"){ ?> selected="selected" <?php }?>>Negocio</option>
        </select>
        <!--<input name="x_comentario_casa_8'" type="text" value="<?php x_comentario_casa_8;?>" size="30" maxlength="250" />--></td>
      </tr>
    </table>
    <?php }?> 
    </div></td>
    <td><div id="telefono_celular_8">
    <?php if(!empty($x_telefono_celular_8)) {?>
    <table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_8"  value="<?php echo $x_telefono_celular_8; ?>"type="text" size="13" maxlength="250" <?php echo $x_readonly;?> onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%"><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_8\" id=\"x_compania_celular_8\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_8) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?> </td>
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_8"  value="<?php echo $x_comentario_celular_8;?>"size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?>    
    </div></td>
  </tr><tr>
    <td> <div id="x_telefono_casa_9">
      <?php if($x_telefono_casa_9){ ?>
    <table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_9" type="text" value="<?php $x_telefono_casa_9;?>" <?php echo $x_readonly;?> size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td>
        <select name="x_comentario_casa_9">
        <option value="">Seleccione</option>
         <option value="Domicilio" <?php if ($x_comentario_casa_9 == "Domicilio"){ ?> selected="selected" <?php }?>>Domicilio</option>
         <option value="Negocio" <?php if ($x_comentario_casa_9 == "Negocio"){ ?> selected="selected" <?php }?>>Negocio</option>
        </select>
        <!--<input name="x_comentario_casa_9'" type="text" value="<?php x_comentario_casa_9;?>" size="30" maxlength="250" />--></td>
      </tr>
    </table>
    <?php }?> 
    </div></td>
    <td><div id="telefono_celular_9">
    <?php if(!empty($x_telefono_celular_9)) {?>
    <table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_9"  value="<?php echo $x_telefono_celular_9; ?>"type="text"  size="13" maxlength="250" <?php echo $x_readonly;?>  onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%"><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_9\" id=\"x_compania_celular_9\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_9) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?> </td>
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_9"  value="<?php echo $x_comentario_celular_9;?>"size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?>    
    </div></td>
  </tr><tr>
    <td> <div id="x_telefono_casa_10">
      <?php if($x_telefono_casa_10){ ?>
    <table width="100%" border="0">
      <tr>
        <td>Telefono</td>
        <td><input name="x_telefono_casa_10" type="text" value="<?php $x_telefono_casa_10;?>"  <?php echo $x_readonly;?> size="13" maxlength="15" onKeyPress="return solonumeros(this,event)"/></td>
        <td>Comentario</td>
        <td>
        <select name="x_comentario_casa_10">
        <option value="">Seleccione</option>
         <option value="Domicilio" <?php if ($x_comentario_casa_10 == "Domicilio"){ ?> selected="selected" <?php }?>>Domicilio</option>
         <option value="Negocio" <?php if ($x_comentario_casa_10 == "Negocio"){ ?> selected="selected" <?php }?>>Negocio</option>
        </select>
        <!--<input name="x_comentario_casa_10'" type="text" value="<?php x_comentario_casa_10;?>" size="30" maxlength="250" />--></td>
      </tr>
    </table>
    <?php }?> 
    </div></td>
    <td><div id="telefono_celular_10">
    <?php if(!empty($x_telefono_celular_10)) {?>
    <table width="100%" border="0">
      <tr>
        <td width="11%">Celular</td>
        <td width="14%"><input name="x_telefono_celular_10"  value="<?php echo $x_telefono_celular_10; ?>"type="text" <?php echo $x_readonly;?> size="13" maxlength="250"  onKeyPress="return solonumeros(this,event)" /></td>
        <td width="15%">Compa&ntilde;ia</td>
        <td width="2%"><?php
		$x_entidad_idList = "<select name=\"x_compania_celular_10\" id=\"x_compania_celular_10\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `compania_celular_id`, `nombre` FROM compania_celular`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["compania_celular_id"] == @$x_compania_celular_10) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["nombre"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?> </td>
        <td width="21%">Comentario</td>
        <td width="37%"><input type="text" name="x_comentario_celular_10"  value="<?php echo $x_comentario_celular_10;?>"size="30" maxlength="250" /></td>
      </tr>
    </table>
    <?php }?>    
    </div></td>
  </tr><tr>
    <td> <div id="x_telefono_casa_11">
    </div></td>
    <td><div id="telefono_celular_11">
    </div></td>
  </tr>
  
  <tr>
    <td align="right"><input type="button" value="Agregar telefono casa" onClick="actualizaReferencia(1);" /></td>
    <td align="right"><input type="button" value="Agregar celular" onClick="actualizaReferencia(2);" /></td>
  </tr>
</table>

</td>
    </tr>   
    
    
    
    
    
    <tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Datos del Negocio</td>
    </tr>
     <tr>
      <td colspan="11" ><strong>Mismos datos que el domicilio <input type="checkbox" name="x_mismos_datos_domiclio" <?php echo $x_checked;?>  onchange="llenaDatosNegocio();" /></strong></td>
    </tr>
    <tr>
      <td>Giro Negocio</td>
      <td colspan="4"> <?php
		$x_entidad_idList = "<select name=\"x_giro_negocio_id\" $x_readonly2 id=\"x_giro_negocio_id\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `giro_negocio_id`, `descripcion` FROM `giro_negocio`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["giro_negocio_id"] == @$x_giro_negocio_id) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?></td>
      <td><p>Tipo Negocio</p></td>
      <td colspan="5"> <?php
		$x_entidad_idList = "<select name=\"x_tipo_inmueble_id\" $x_readonly2 id=\"x_tipo_inmueble_id\"  >";
		$x_entidad_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `tipo_inmueble_id`, `descripcion` FROM `tipo_inmueble`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_entidad_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["tipo_inmueble_id"] == @$x_tipo_inmueble_id) {
					$x_entidad_idList .= "' selected";
				}
				$x_entidad_idList .= ">" . htmlentities($datawrk["descripcion"]) . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_entidad_idList .= "</select>";
		echo $x_entidad_idList;
		?></td>
    </tr>
    <tr>
      <td>Atiende Titular</td>
      <td colspan="4"><select name="x_atiende_titular" <?php echo $x_readonly2;?>>
      <option>Seleccione</option>
		<option value="si" <?php if($x_atiende_titular == "si"){?> selected="selected" <?php } ?> >Si</option>
        <option value="no" <?php if($x_atiende_titular == "no"){?> selected="selected" <?php } ?>>No</option>
      </select></td>
      <td><p>No.Personas trabajando</p></td>
      <td colspan="5"><input type="text" name="x_personas_trabajando" value="<?php echo($x_personas_trabajando);?>" maxlength="20" size="10" <?php echo $x_readonly;?>/></td>
    </tr>
    <tr>
      <td>Calle</td>
      <td colspan="4"><input type="text" name="x_calle_2" id="x_calle_2" value="<?php echo $x_calle_2; ?>"  maxlength="250" size="60" <?php echo $x_readonly;?>/></td>
      <td colspan="5">&nbsp;N&uacute;mero exterior&nbsp;&nbsp;<input type="text" name="x_numero_exterior_2" id="x_numero_exterior_2" value="<?php echo ($x_numero_exterior_2);?>"  maxlength="20" size="20" <?php echo $x_readonly;?>/></td>
    </tr>
    <tr>
      <td>Colonia</td>
      <td colspan="4"><input type="text" name="x_colonia_2" id="x_colonia_2"  value="<?php echo htmlspecialchars(@$x_colonia_2) ?>" maxlength="250" size="70" <?php echo $x_readonly;?>/></td>
      <td><p>C&oacute;digo Postal</p></td>
      <td colspan="5"> <input type="text" name="x_codigo_postal_2" id="x_codigo_postal_2" value="<?php echo htmlspecialchars(@$x_codigo_postal_2)?>"  maxlength="25" size="30"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
    </tr>
    <tr>
      <td>Entidad</td>
      <td colspan="4"><?php
		$x_entidad_idList2 = "<select name=\"x_entidad_2\" id=\"x_entidad_2\" $x_readonly2  onchange=\"showHint(this,'txtHint2', 'x_delegacion_id2')\" >";
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
      <td colspan="6"><div align="left"><span class="texto_normal">
        <input type="hidden" name="x_delegacion_id_temp" value="" />
        
        </span><span class="texto_normal">
          <div id="txtHint2" class="texto_normal">        
            Del/Mun:        
            <?php
		if($x_entidad_2 > 0 ){
		$x_delegacion_idList = "<select name=\"x_delegacion_id2\" $x_readonly2 >";
		$x_delegacion_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT delegacion_id, descripcion FROM delegacion where entidad_id = $x_entidad_2";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["delegacion_id"] == @$x_delegacion_id2) {
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
    </tr>
    <tr>
      <td>Localidad</td>
      <td colspan="4"><div id="txtHint4" class="texto_normal">
      <?php  
	  if(!empty($x_localidad_id2)){
$x_delegacion_idList = "<select name=\"x_localidad_id2\" $x_readonly2  >";
$x_delegacion_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT localidad_id, descripcion FROM localidad where delegacion_id = $x_delegacion_id2";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_delegacion_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["localidad_id"] == @$x_localidad_id2) {
			$x_delegacion_idList .= "' selected";
		}
		$x_delegacion_idList .= ">" . $datawrk["descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);

$x_delegacion_idList .= "</select>";
	  }
echo $x_delegacion_idList;
      ?></div></td>
      <td>Ubicacion</td>
      <td colspan="5">
      <strong></strong>
     
             
      <input type="text" name="x_ubicacion_2" id="x_ubicacion_2" value="<?php echo htmlspecialchars(@$x_ubicacion_2) ?>" maxlength="250" size="35" <?php echo $x_readonly;?>/></td>
    </tr>
    <tr>
      <td>Tipo Local</td>
      <td colspan="4">
      <?php
		$x_vivienda_tipo_idList = "<select name=\"x_tipo_local_negocio\" id=\"x_tipo_local_negocio\" $x_readonly2   onchange=\"viviendatipo('1')\">";
		$x_vivienda_tipo_idList .= "<option value=''>Seleccione</option>";
		$sSqlWrk = "SELECT `vivienda_tipo_id`, `descripcion` FROM `vivienda_tipo`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_vivienda_tipo_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["vivienda_tipo_id"] == @$x_tipo_local_negocio) {
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
      <td>Antiguedad (a&ntilde;os)</td>
      <td colspan="5"><input type="text" name="x_antiguedad_negocio" id="x_antiguedad_negocio"  value="<?php echo htmlspecialchars(@$x_antiguedad_n) ?>" maxlength="10" size="20" <?php echo $x_readonly;?>/></td>
    </tr>
    <tr>
      <td>Ingreso semanal</td>
      <td colspan="4"><input type="text" name="x_ingreso_semanal" id="x_ingreso_semanal" value="<?php echo $x_ingreso_semanal;?>"  maxlength="50" size="20" onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
      <td>Tel. Arrendatario</td>
      <td colspan="5"><input type="text" name="x_tel_arrendatario_negocio" id="x_tel_arrendatario_negocio"  value="<?php echo htmlspecialchars(@$x_tel_arrendatario_n) ?>" maxlength="20" size="20" <?php echo $x_readonly;?>/></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="4">&nbsp;</td>
      <td>Renta Mensual</td>
      <td colspan="5"><input type="text" name="x_renta_mensual_n" id="x_renta_mensual_n"  value="<?php echo number_format(@$x_renta_mensual_n, 0, '.', '') ?>" maxlength="50" size="20"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
    </tr>
      <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Garantia</td>
    </tr>
     <tr>
      <td colspan="11"  align="left"><table width="90%" height="26" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="171" >Tipo de garantia</td>
          <td width="444" ><input type="text" name="x_tipo_garantia"  value="<?php echo $x_tipo_garantia;?>" maxlength="500" size="60" <?php echo $x_readonly;?>/></td>
          <td width="172" >Modelo, A&ntilde;o</td>
          <td width="276"><input type="text" name="x_modelo_garantia" value="<?php echo $x_modelo_garantia; ?>"maxlength="500" size="60"  <?php echo $x_readonly;?>/></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="11"><center><textarea cols="80" rows="4" id="x_garantia_desc" name="x_garantia_desc" <?php echo $x_readonly;?>><?php echo @$x_garantia_desc; ?></textarea></center></td>
    </tr>
    <tr>
      <td colspan="11">&nbsp;</td>
  </tr>
  
  <tr>
      <td colspan="11"><table width="80%">
        <tr>
          <td>Valor comercial</td>
          <td><input type="text" name="x_garantia_valor" id="x_garantia_valor" value="<?php  echo number_format($x_garantia_valor, 0, '.', '');?>" onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?> /></td>
          <td>Valor factura</td>
          <td><input type="text" name="x_garantia_valor_factura" id="x_garantia_valor_factura"  value="<?php  echo number_format($x_garantia_valor_factura, 0, '.', '');?>" onKeyPress="return solonumeros(this,event)"  <?php echo $x_readonly;?>/></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="11"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
      <td colspan="11"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Referencias Comerciales</td>
    </tr>
     <tr>
      <td colspan="11" id="tableHead"><p></td>
    </tr>
    <tr>
      <td height="36" colspan="2"><table width="250"><tr>
        <td width="48">1.-</td>
        <td width="276"><input type="text" name="x_referencia_1" id="x_referencia_1" value="<?php echo htmlspecialchars(@$x_referencia_1) ?>"  maxlength="250" size="60" <?php echo $x_readonly;?>/></td></tr></table></td>
      <td colspan="2"><table width="160" height="29"><tr>
        <td width="19">Tel</td>
        <td width="129"><input type="text" name="x_telefono_ref_1" id="x_telefono_ref_11"  value="<?php echo htmlspecialchars(@$x_telefono_ref_1) ?>" maxlength="20" size="30" <?php echo $x_readonly;?>/></td></tr></table></td>
      <td width="80">Parentesco</td>
      <td colspan="6">
         <?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_ref_1\" $x_readonly2 id=\"x_parentesco_ref_1\" >";
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
      <td colspan="2"><table width="250"><tr>
        <td width="48">2.-</td>
        <td width="277"><input type="text" name="x_referencia_2" id="x_referencia_2"  value="<?php echo htmlspecialchars(@$x_referencia_2) ?>" maxlength="250" size="60" <?php echo $x_readonly;?>/></td></tr></table></td>
      <td colspan="2"><table width="160"><tr>
        <td width="19">Tel</td>
        <td width="129"><input type="text" name="x_telefono_ref_2" id="x_telefono_ref_2"  value="<?php echo htmlspecialchars(@$x_telefono_ref_2) ?>" maxlength="20" size="30" <?php echo $x_readonly;?>/></td></tr></table></td>
      <td>Parentesco</td>
      <td colspan="6">
      <?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_ref_2\" id=\"x_parentesco_ref_2\"  $x_readonly2 >";
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
      <td colspan="2"><table width="250"><tr>
        <td width="48">3.-</td>
      <td width="276"><input type="text" name="x_referencia_3" id="x_referencia_3"  value="<?php echo htmlspecialchars(@$x_referencia_3) ?>" maxlength="250" size="60" <?php echo $x_readonly;?>/></td></tr></table></td>
      <td colspan="2"><table width="183"><tr>
        <td width="19">Tel</td>
        <td width="152"><input type="text" name="x_telefono_ref_3" id="x_telefono_ref_3"  value="<?php echo htmlspecialchars(@$x_telefono_ref_3) ?>"  maxlength="20" size="30" <?php echo $x_readonly;?>/></td></tr></table></td>
      <td>Parentesco</td>
      <td colspan="6">
      <?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_ref_3\" id=\"x_parentesco_ref_3\"  $x_readonly2 >";
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
      <td colspan="2"><table width="250"><tr>
        <td width="48">4.-</td>
      <td width="276"><input type="text" name="x_referencia_4" id="x_referencia_4"  value="<?php echo htmlspecialchars(@$x_referencia_4) ?>" maxlength="250" size="60" <?php echo $x_readonly;?>/></td></tr></table></td>
     <td colspan="2"><table width="160"><tr>
        <td width="17">Tel</td>
        <td width="131"><input type="text" name="x_telefono_ref_4" id="x_telefono_ref_4"  value="<?php echo htmlspecialchars(@$x_telefono_ref_4) ?>" maxlength="20" size="30" <?php echo $x_readonly;?>/></td></tr></table></td>
      <td>Parentesco</td>
      <td colspan="6">
      <?php
		$x_parentesco_tipo_idList = "<select name=\"x_parentesco_ref_4\" id=\"x_parentesco_ref_4\" $x_readonly2 >";
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
      <td width="187">&nbsp;</td>
      <td width="72">&nbsp;</td>
      <td width="169">&nbsp;</td>
      <td width="61">&nbsp;</td>
      <td>&nbsp;</td>
      <td width="24">&nbsp;</td>
      <td width="68">&nbsp;</td>
      <td width="4">&nbsp;</td>
      <td width="4">&nbsp;</td>
      <td width="4">&nbsp;</td>
      <td width="27">&nbsp;</td>
    </tr>
    
</table></td>
  </tr>
  <?php  if ($x_fecha_registro <= "2012-07-02") {
	  // si la fecha de registro es mayor al 28/06/2012 entonces las solcitudes ya se gaurdaron con los nuevos campos de ingresos
	  // y al editar se debe mostrar los campos
	  
	  ?>
  <tr>
 
    <td colspan="11">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="22"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Ingresos Familiares</td>
      </tr>
      <tr>
        <td colspan="11" id="tableHead2"><p></p></td>
      </tr>
      <tr>
        <td width="153">Ingreso del Negocio</td>
        <td colspan="4"><input type="text" name="x_ing_fam_negocio" id="x_ing_fam_negocio"  value="<?php echo number_format(@$x_ing_fam_negocio, 0, '.', '') ?>" maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
        <td colspan="2">&nbsp;</td>
        <td width="154">&nbsp;</td>
        <td width="20">&nbsp;</td>
        <td width="53">&nbsp;</td>
        <td width="52">&nbsp;</td>
        <td width="47">&nbsp;</td>
        <td width="13" colspan="10">&nbsp;</td>
      </tr>
      <tr>

        <td width="153">Otros Ingresos TH</td>
        <td colspan="4"><input type="text" name="x_ing_fam_otro_th" id="x_ing_fam_otro_th" value="<?php echo number_format(@$x_ing_fam_otro_th, 0, '.', '') ?>"  maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_ing_fam_cuales_1" id="x_ing_fam_cuales_1"   value="<?php echo htmlspecialchars(@$x_ing_fam_cuales_1) ?>" maxlength="250" size="35" <?php echo $x_readonly;?>/></td>
        <td width="47">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="153">Ingreso Familiar 1</td>
        <td colspan="4"><input type="text" name="x_ing_fam_1" id="x_ing_fam_1"   value="<?php echo number_format(@$x_ing_fam_1, 0, '.', '') ?>" maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_ing_fam_cuales_2" id="x_ing_fam_cuales_2" value="<?php echo htmlspecialchars(@$x_ing_fam_cuales_2) ?>" maxlength="250" size="35" <?php echo $x_readonly;?>/></td>
        <td width="47">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="153">Ingreso Familiar  2</td>
        <td colspan="4"><input type="text" name="x_ing_fam_2" id="x_ing_fam_2"  value="<?php echo number_format(@$x_ing_fam_2, 0, '.', '') ?>" maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_ing_fam_cuales_13" id="x_ing_fam_cuales_13"  value="<?php echo htmlspecialchars(@$x_ing_fam_cuales_13) ?>" maxlength="250" size="35" <?php echo $x_readonly;?>/></td>
        <td width="47">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="153">Deuda 1</td>
        <td colspan="4"><input type="text" name="x_ing_fam_deuda_1" id="x_ing_fam_deuda_1"  value="<?php echo number_format(@$x_ing_fam_deuda_1, 0, '.', '') ?>" maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_ing_fam_cuales_4" id="x_ing_fam_cuales_4"  value="<?php echo htmlspecialchars(@$x_ing_fam_cuales_4) ?>" maxlength="250" size="35" <?php echo $x_readonly;?>/></td>
        <td width="47">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="153">Deuda 2</td>
        <td colspan="4"><input type="text" name="x_ing_fam_deuda_2" id="x_ing_fam_deuda_2"  value="<?php echo number_format(@$x_ing_fam_deuda_2, 0, '.', '') ?>" maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?> /></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_ing_fam_cuales_5" id="x_ing_fam_cuales_5" value="<?php echo htmlspecialchars(@$x_ing_fam_cuales_5) ?>"  maxlength="250" size="35" <?php echo $x_readonly;?> /></td>
        <td width="47">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="153">Total</td>
        <td colspan="4"><input type="text" name="x_ing_fam_total" id="x_ing_fam_total"  value="<?php echo number_format(@$x_ing_fam_total, 0, '.', '') ?>" maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?> /></td>
        <td colspan="2">&nbsp;</td>
        <td width="154">&nbsp;</td>
        <td width="20">&nbsp;</td>
        <td width="53">&nbsp;</td>
        <td width="52">&nbsp;</td>
        <td width="47">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="153">&nbsp;</td>
        <td width="42">&nbsp;</td>
        <td width="35">&nbsp;</td>
        <td width="43">&nbsp;</td>
        <td width="64">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
        <td width="154">&nbsp;</td>
        <td width="20">&nbsp;</td>
        <td width="53">&nbsp;</td>
        <td width="52">&nbsp;</td>
        <td width="47">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="22" id="tableHead2"><p></p></td>
      </tr>
      <tr>
        <td colspan="22"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Flujos del Negocio</td>
      </tr>
      <tr>
        <td colspan="22" id="tableHead2"><p></p></td>
      </tr>
      <tr>
        <td width="153">Ventas</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_ventas" id="x_flujos_neg_ventas" value="<?php echo number_format(@$x_flujos_neg_ventas, 0, '.', '') ?>"  maxlength="30" size="35"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?> /></td>
        <td colspan="2">&nbsp;</td>
        <td colspan="5">&nbsp;</td>
        <td width="13" colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="153">Proveedor 1</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_proveedor_1" id="x_flujos_neg_proveedor_1" value="<?php echo number_format(@$x_flujos_neg_proveedor_1, 0, '.', '') ?>"  maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?> /></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_cual_1" id="x_flujos_neg_cual_1"  value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_1) ?>" maxlength="100" size="35" <?php echo $x_readonly;?> /></td>
        <td width="47">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="153">Proveedor 2</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_proveedor_2" id="x_flujos_neg_proveedor_2"  value="<?php echo number_format(@$x_flujos_neg_proveedor_2, 0, '.', '') ?>" maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?> /></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_cual_2" id="x_flujos_neg_cual_2" value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_2) ?>"  maxlength="100" size="35" <?php echo $x_readonly;?> /></td>
        <td width="47">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="153">Proveedor 3</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_proveedor_3" id="x_flujos_neg_proveedor_3"  value="<?php echo number_format(@$x_flujos_neg_proveedor_3, 0, '.', '') ?>" maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?> /></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_cual_3" id="x_flujos_neg_cual_3"  value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_3) ?>" maxlength="100" size="35" <?php echo $x_readonly;?> /></td>
        <td width="47">&nbsp;</td>
        <td colspan="10">&nbsp;</td>

      </tr>
      <tr>
        <td width="153">Proveedor 4</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_proveedor_4" id="x_flujos_neg_proveedor_4"  value="<?php echo number_format(@$x_flujos_neg_proveedor_4, 0, '.', '') ?>" maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?> /></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_cual_4" id="x_flujos_neg_cual_4"  value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_4) ?>" maxlength="100" size="35" <?php echo $x_readonly;?> /></td>
        <td width="47">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="153">Gasto 1</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_gasto_1" id="x_flujos_neg_gasto_1" value="<?php echo number_format(@$x_flujos_neg_gasto_1, 0, '.', '') ?>"  maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?> /></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_cual_5" id="x_flujos_neg_cual_5"  value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_5) ?>" maxlength="100" size="35" <?php echo $x_readonly;?> /></td>
        <td width="47">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="153">Gasto 2</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_gasto_2" id="x_flujos_neg_gasto_2"  value="<?php echo number_format(@$x_flujos_neg_gasto_2, 0, '.', '') ?>" maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_cual_6" id="x_flujos_neg_cual_6"  value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_6) ?>" maxlength="100" size="35" <?php echo $x_readonly;?>/></td>
        <td width="47">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="153">Gasto 3</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_gasto_3" id="x_flujos_neg_gasto_3"  value="<?php echo number_format(@$x_flujos_neg_gasto_3, 0, '.', '') ?>" maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
        <td colspan="2">Cu&aacute;l</td>
        <td colspan="4"><input type="text" name="x_flujos_neg_cual_7" id="x_flujos_neg_cual_7"  value="<?php echo htmlspecialchars(@$x_flujos_neg_cual_7) ?>" maxlength="100" size="35" <?php echo $x_readonly;?>/></td>
        <td width="47">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
      <tr>
        <td width="153">Ingreso Negocio</td>
        <td colspan="4"><input type="text" name="x_ingreso_negocio" id="x_ingreso_negocio"  value="<?php echo number_format(@$x_ingreso_negocio, 0, '.', '') ?>" maxlength="250" size="35"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
        <td colspan="2">&nbsp;</td>
        <td colspan="4">&nbsp;</td>
        <td width="47">&nbsp;</td>
        <td colspan="10">&nbsp;</td>
      </tr>
 
        <tr>
          <td colspan="19" id="tableHead2"><p></p></td>
        </tr>
            <td colspan="19"  align="center" valign="top" bgcolor="#FFE6E6" class="texto_normal_bold">Inversion del Negocio</td>
        </tr>
        <tr>
          <td colspan="19" id="tableHead2"><p></p></td>
        </tr>
        <tr>
          <td height="30" colspan="5"><center>
            FIJA
          </center></td>
          <td colspan="2">&nbsp;</td>
          <td colspan="12"><center>
            VARIABLE
          </center></td>
          </tr>
        <tr>
          <td width="153">Concepto</td>
          <td colspan="4">Valor</td>
          <td colspan="2">&nbsp;</td>
          <td width="154">Concepto</td>
          <td colspan="11">Valor</td>
          </tr>
        <tr>
          <td width="153"><input type="text" name="x_inv_neg_fija_conc_1" id="x_inv_neg_fija_conc_1" value="<?php echo htmlspecialchars(@$x_inv_neg_fija_conc_1) ?>"  maxlength="250" size="35" <?php echo $x_readonly;?> /></td>
          <td colspan="4"><input type="text" name="x_inv_neg_fija_valor_1" id="x_inv_neg_fija_valor_1"  value="<?php echo number_format(@$x_inv_neg_fija_valor_1, 0, '.', '') ?>"  maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?> /></td>
          <td colspan="2">&nbsp;</td>
          <td><input type="text" name="x_inv_neg_var_conc_1" id="x_inv_neg_var_conc_1"  value="<?php echo htmlspecialchars(@$x_inv_neg_var_conc_1) ?>" maxlength="250" size="35" <?php echo $x_readonly;?> /></td>
          <td colspan="11"><input type="text" name="x_inv_neg_var_valor_1" value="<?php echo number_format(@$x_inv_neg_var_valor_1, 0, '.', '') ?>" id="x_inv_neg_var_valor_1"  maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?> /></td>
          </tr>
        <tr>
          <td width="153"><input type="text" name="x_inv_neg_fija_conc_2" id="x_inv_neg_fija_conc_2"  value="<?php echo htmlspecialchars(@$x_inv_neg_fija_conc_2) ?>" maxlength="250" size="35" <?php echo $x_readonly;?>/></td>
          <td colspan="4"><input type="text" name="x_inv_neg_fija_valor_2" id="x_inv_neg_fija_valor_2" value="<?php echo number_format(@$x_inv_neg_fija_valor_2, 0, '.', '') ?>"  maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?> /></td>
          <td colspan="2">&nbsp;</td>
          <td><input type="text" name="x_inv_neg_var_conc_2" id="x_inv_neg_var_conc_2"  value="<?php echo htmlspecialchars(@$x_inv_neg_var_conc_2) ?>" maxlength="250" size="35" <?php echo $x_readonly;?>/></td>
          <td colspan="11"><input type="text" name="x_inv_neg_var_valor_2"  value="<?php echo number_format(@$x_inv_neg_var_valor_2, 0, '.', '') ?>" id="x_inv_neg_var_valor_2"  maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
          </tr>
        <tr>
          <td width="153"><input type="text" name="x_inv_neg_fija_conc_3" id="x_inv_neg_fija_conc_3"   value="<?php echo htmlspecialchars(@$x_inv_neg_fija_conc_3) ?>" maxlength="250" size="35" <?php echo $x_readonly;?>/></td>
          <td colspan="4"><input type="text" name="x_inv_neg_fija_valor_3" id="x_inv_neg_fija_valor_3"  value="<?php echo number_format(@$x_inv_neg_fija_valor_3, 0, '.', '') ?>" maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
          <td colspan="2">&nbsp;</td>
          <td><input type="text" name="x_inv_neg_var_conc_3" id="x_inv_neg_var_conc_3"  value="<?php echo htmlspecialchars(@$x_inv_neg_var_conc_3) ?>" maxlength="250" size="35" <?php echo $x_readonly;?>/></td>
          <td colspan="11"><input type="text" name="x_inv_neg_var_valor_3" id="x_inv_neg_var_valor_3"  value="<?php echo number_format(@$x_inv_neg_var_valor_3, 0, '.', '') ?>" maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
          </tr>
        <tr>
          <td width="153"><input type="text" name="x_inv_neg_fija_conc_4" id="x_inv_neg_fija_conc_4"   value="<?php echo htmlspecialchars(@$x_inv_neg_fija_conc_4) ?>" maxlength="250" size="35" <?php echo $x_readonly;?>/></td>
          <td colspan="4"><input type="text" name="x_inv_neg_fija_valor_4" id="x_inv_neg_fija_valor_4" value="<?php echo number_format(@$x_inv_neg_fija_valor_4, 0, '.', '') ?>" maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
          <td colspan="2">&nbsp;</td>
          <td><input type="text" name="x_inv_neg_var_conc_4" id="x_inv_neg_var_conc_4" value="<?php echo htmlspecialchars(@$x_inv_neg_var_conc_4) ?>"  maxlength="250" size="35" <?php echo $x_readonly;?>/></td>
          <td colspan="11"><input type="text" name="x_inv_neg_var_valor_4" id="x_inv_neg_var_valor_4"  value="<?php echo number_format(@$x_inv_neg_var_valor_4, 0, '.', '') ?>" maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
          </tr>
        <tr>
          <td width="153" align="right">Total</td>
          <td colspan="4"><input type="text" name="x_inv_neg_total_fija" id="x_inv_neg_total_fija"  value="<?php echo number_format(@$x_inv_neg_total_fija, 0, '.', '') ?>" maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
          <td colspan="2">&nbsp;</td>
          <td align="right">Total:</td>
          <td colspan="11"><input type="text" name="x_inv_neg_total_var" id="x_inv_neg_total_var"   value="<?php echo number_format(@$x_inv_neg_total_var, 0, '.', '') ?>"maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
          </tr>
        <tr>
          <td width="153">&nbsp;</td>
          <td colspan="4">&nbsp;</td>
          <td colspan="2">&nbsp;</td>
          <td align="right"></td>
          <td colspan="11"></td>
          </tr>
        <tr>
          <td width="153">&nbsp;</td>
          <td colspan="4">&nbsp;</td>
          <td colspan="2">&nbsp;</td>
          <td align="right">ACTIVOS TOTALES:</td>
          <td colspan="11"><input type="text" name="x_inv_neg_activos_totales" id="x_inv_neg_activos_totales" value="<?php echo number_format(@$x_inv_neg_activos_totales, 0, '.', '') ?>" maxlength="250" size="25"  onKeyPress="return solonumeros(this,event)" <?php echo $x_readonly;?>/></td>
          </tr>
        <tr>
          <td colspan="19" id="tableHead2"><p>sdad</p></td>
        </tr>
       
      </table>
  
  
  
  
  </td></tr><?php } ?>
    <tr>
      <td colspan="11">&nbsp;</td>
  </tr>
  
    <tr>
      <td colspan="11">
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
       <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" bgcolor="#FFE6E6"><div align="center"><span class="texto_normal_bold">Comentarios del Promotor</span></div></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="left" valign="top">
	  <div align="center">
	    <textarea name="x_comentario_promotor" cols="60" rows="5" <?php echo $x_readonly;?>><?php echo $x_comentario_promotor; ?></textarea>
	      </div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" bgcolor="#FFE6E6"><div align="center"><span class="texto_normal_bold">Comentarios del Comite de Cr&eacute;dito </span></div></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="left" valign="top">
	  <div align="center">
	    <textarea name="x_comentario_comite" cols="60" rows="5" <?php echo $x_readonly;?>><?php echo $x_comentario_comite; ?></textarea>
	      </div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td colspan="3" bgcolor="#FFE6E6"><div align="center">Direcci&oacute;n en mapa domicilio</div></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="hidden" name="x_latitud" id="x_latitud" value="<?php echo $x_latitud+0;?>" />
    	<input type="hidden" name="x_longitud" id="x_longitud" value="<?php echo $x_longitud+0; ?>" />
    
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div> 
   Direcci&oacute;n : <input id="address" type="textbox" value="" size="60" <?php echo $x_readonly;?> >
    <input type="button" value="Buscar" onclick="codeAddress();"  />    
    <input onClick="mostrarOriginal();" type=button value="Mostrar original" />
    <input onClick="deleteOverlays();" type=button value="Limpiar"/>
    </div> </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><center><div id="map_canvas" style="top:30px;width:700px; height:400px"></div></center>
      <p>
        <input type="hidden" name="x_latlong" id="x_latlong" />
      </p>
      <p>&nbsp;</p>
      <p>&nbsp;</p></td>
    <td>&nbsp;</td>
  </tr>
  
   <tr><td colspan="3"><div id="mapaNegocio"><table width="100%">
   <tr>
    <td colspan="3" bgcolor="#FFE6E6"><div align="center">Direcci&oacute;n en mapa negocio</div></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="hidden" name="x_latitud2" id="x_latitud2" value="<?php echo $x_latitud2+0;?>" />
    	<input type="hidden" name="x_longitud2" id="x_longitud2" value="<?php echo $x_longitud2+0; ?>" />
    
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div> 
   Direcci&oacute;n : <input id="address2" type="textbox" value="" size="60" <?php echo $x_readonly;?> >
    <input type="button" value="Buscar" onclick="codeAddress2();" />    
    <input onClick="mostrarOriginal2();" type=button value="Mostrar original"/>
    <input onClick="deleteOverlays2();" type=button value="Limpiar"/>
    </div> </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><center><div id="map_canvas2" style="top:30px;width:700px; height:400px"></div></center>
      <p>
        <input type="hidden" name="x_latlong2" id="x_latlong2" />
      </p>
      <p>&nbsp;</p>
      <p>&nbsp;</p></td>
    <td>&nbsp;</td>
  </tr></table></div></td></tr>
      
      
      
      
      </table>
      
      
      </td>
  </tr>
  
    <tr>
      <td colspan="11"></td>
  </tr>
  
    <tr>
      <td colspan="11"></td>
  </tr>
  
    <tr>
      <td colspan="11"></td>
  </tr>
  
    <tr>
      <td colspan="11"></td>
  </tr>
  
  </table>




</td>


</tr>


<div id="paginaDos" style="display:block">
<!-- paginaDos-->
<table width="100%" id="pagina2" style="display:block">
<tr>
  <td height="748">
  </div>
<input type="submit" name="Guardar" value="Editar" <?php echo $x_readonly2;?>>
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
		$GLOBALS["x_esposa"] = $row["nombre_conyuge"];
	//nuevos campos
		
		$GLOBALS["x_fecha_ini_act_prod"] = $row["fecha_ini_act_prod"];
		$GLOBALS["x_rol_hogar_id"] = $row["rol_hogar_id"];
		$GLOBALS["x_entidad_nacimiento"] = $row["entidad_nacimiento_id"];	
		$GLOBALS["x_estudio_id"] = $row["escolaridad_id"];
		$GLOBALS["x_ppe"] = $row["ppe"];
		$GLOBALS["x_ingreso_semanal"] = $row["ingreso_semanal"];
		$GLOBALS["x_numero_exterior"] = $row["numero_exterior"];
		$GLOBALS["x_numero_exterior_2"] = $row["numero_exterior_2"];
		
		$GLOBALS["x_tipo_garantia"] = $row["tipo_garantia"];
		$GLOBALS["x_modelo_garantia"] = $row["modelo"];
		$GLOBALS["x_garantia_valor_factura"] = $row["valor_factura"];
		
			//CLIENTE		
		$sSql = "select * from ppe_aval where aval_id = ".$GLOBALS["x_datos_aval_id"]." ";
		$rs2 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row2 = phpmkr_fetch_array($rs2);
		$GLOBALS["x_ppe_id"] = $row2["ppe_id"];		
		$GLOBALS["x_parentesco_ppe"] = $row2["relacion_id"];
		$GLOBALS["x_nombre_ppe"] = $row2["nombre"];
		
		$GLOBALS["x_apellido_paterno_ppe"] = $row2["a_paterno"];		
		$GLOBALS["x_apellido_materno_ppe"] = $row2["a_materno"];	
		
		
		$sSql = "select * from negocio_aval where aval_id = ".$GLOBALS["x_datos_aval_id"];
		$rsn5 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$rown5 = phpmkr_fetch_array($rsn5);
		$GLOBALS["x_negocio_id"] = $rown5["negocio_id"];
		$GLOBALS["x_giro_negocio_id"] = $rown5["giro_negocio_id"];
		$GLOBALS["x_tipo_inmueble_id"] = $rown5["tipo_inmueble_id"];
		$GLOBALS["x_atiende_titular"] = $rown5["atiende_titular"];
		$GLOBALS["x_personas_trabajando"] = $rown5["personas_trabajando"];
		$GLOBALS["x_destino_credito_id"] = $rown5["destino_credito_id"];
		
		
		
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
		$GLOBALS["x_renta_mensual_n"] = $row["renta_mesual_n"];
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
		$GLOBALS["x_ing_fam_cuales_13"] = $row["x_ing_fam_cuales_13"];
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
		$GLOBALS["x_codigo_postal_2"] = $row["codigo_postal_2"];
		
		
		$GLOBALS["x_delegacion_id"] = $row["delegacion"];
		$GLOBALS["x_delegacion_id2"] = $row["delegacion_2"];
		$GLOBALS["x_localidad_id"] = $row["localidad_id"];
		$GLOBALS["x_localidad_id2"] = $row["localidad_id2"];
		
		$x_count_2 = 1;
		$sSql = "select * from  telefono where aval_id = ".$GLOBALS["x_datos_aval_id"]."  AND telefono_tipo_id = 1 order by telefono_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9 = phpmkr_fetch_array($rs9)){			
			$GLOBALS["x_telefono_casa_$x_count_2"] = $row9["numero"];
			$GLOBALS["x_comentario_casa_$x_count_2"] = $row9["comentario"];
			$GLOBALS["contador_telefono"] = $x_count_2;
			$x_count_2++;
			
		}
		
		



		$x_count_3 = 1;
		$sSql = "select * from  telefono where aval_id = ".$GLOBALS["x_datos_aval_id"]."  AND telefono_tipo_id = 2 order by telefono_id";
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		while ($row9e = phpmkr_fetch_array($rs9)){			
			$GLOBALS["x_telefono_celular_$x_count_3"] = $row9e["numero"];
			$GLOBALS["x_comentario_celular_$x_count_3"] = $row9e["comentario"];
			$GLOBALS["x_compania_celular_$x_count_3"] = $row9e["compania_id"];	
			$GLOBALS["contador_celular"] = $x_count_3;
			$x_count_3++;
			
		}
		
		
		
	//GOOGLEMAPS
		$sSql = "select  * from  google_maps_aval  credito where aval_id = ".$GLOBALS["x_datos_aval_id"];
		$rs8 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row8 = phpmkr_fetch_array($rs8);
		$x_latlong = $row8["latlong"];
		
		$x_coordenadas = explode(",", $x_latlong);
		$GLOBALS["x_google_maps_id"] = $row8["google_maps_id"];
		//echo"google_id".$GLOBALS["x_google_maps_id"];
		$GLOBALS["x_latitud"] = trim($x_coordenadas[0], "("); 
		$GLOBALS["x_longitud"] = trim($x_coordenadas[1],")") ;
		//$GLOBALS["x_latlong"] = $row8["latlong"];
		
		
		//GOOGLEMAPS negocio
		$sSql = "select  * from  google_maps_neg_aval  credito where aval_id = ".$GLOBALS["x_datos_aval_id"];
		$rs9 = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$row9 = phpmkr_fetch_array($rs9);
		$x_latlong2 = $row9["latlong"];
		
		$x_coordenadas2 = explode(",", $x_latlong2);
		$GLOBALS["x_google_maps_neg_id"] = $row9["google_maps_neg_id"];
		//echo"google_id".$GLOBALS["x_google_maps_id"];
		$GLOBALS["x_latitud2"] = trim($x_coordenadas2[0], "("); 
		$GLOBALS["x_longitud2"] = trim($x_coordenadas2[1],")") ;
		//$GLOBALS["x_latlong"] = $row8["latlong"];

if($x_coordenadas2 == $x_coordenadas){
	$GLOBALS["x_checked"]= 'checked="checked"';
	$GLOBALS["x_hidden_mapa_negocio"] = 0;
	// el mapa es el mismo 
	
	}	
	
		$sqlSol = "SELECT solicitud_status_id FROM solicitud WHERE solicitud_id = ".$GLOBALS["x_solicitud_id"]."";
		$rsSol = phpmkr_query($sqlSol, $conn) or die ("Error al seleccionar el status de la solcitud". phpmkr_error()."sql:".$sqlSol);
		$rowSol = phpmkr_fetch_array($rsSol);
		$GLOBALS["x_solicitud_status_id"] = $rowSol["solicitud_status_id"];
		
			
		if($GLOBALS["x_solicitud_status_id"] == 3 || $GLOBALS["x_solicitud_status_id"] == 5 || $GLOBALS["x_solicitud_status_id"] == 6 || $GLOBALS["x_solicitud_status_id"] == 7 || $GLOBALS["x_solicitud_status_id"]  == 8 ){
			$GLOBALS["x_readonly"] = 'readonly = "readonly"';
			$GLOBALS["x_readonly2"] = 'disabled="disabled"';
			}
		
		
		
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
	
	// campos agregados a datos para igualar la solicitud a  la del cliente
	##############################
	##############################
	// Field tipo_negocio
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tipo_negocio"]) : $GLOBALS["x_tipo_negocio"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`tipo_negocio`"] = $theValue;


	// Field edad
	$theValue = ($GLOBALS["x_edad"] != "") ? intval($GLOBALS["x_edad"]) : "NULL";
	$fieldList["`edad`"] = $theValue;	

	// Field estado_civil_id
	$theValue = ($GLOBALS["x_estado_civil_id"] != "") ? intval($GLOBALS["x_estado_civil_id"]) : "0";
	$fieldList["`estado_civil_id`"] = $theValue;

	

	$theValue = ($GLOBALS["x_nacionalidad_id"] != "") ? intval($GLOBALS["x_nacionalidad_id"]) : "0";
	$fieldList["`nacionalidad_id`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_empresa"]) : $GLOBALS["x_empresa"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`empresa`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_puesto"]) : $GLOBALS["x_puesto"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`puesto`"] = $theValue;

	// Field fecha_registro
	$theValue = ($GLOBALS["x_fecha_contratacion"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_contratacion"]) . "'" : "Null";
	$fieldList["`fecha_contratacion`"] = $theValue;

	$theValue = ($GLOBALS["x_salario_mensual"] != "") ? " '" . doubleval($GLOBALS["x_salario_mensual"]) . "'" : "Null";
	$fieldList["`salario_mensual`"] = $theValue;


	
	// Field promotor_id
	$theValue = ($GLOBALS["x_rol_hogar_id"] != "") ? intval($GLOBALS["x_rol_hogar_id"]) : "NULL";
	$fieldList["`rol_hogar_id`"] = $theValue;
	// Field fecha_inicio_act
	$theValue = ($GLOBALS["x_fecha_ini_act_prod"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_fecha_ini_act_prod"]) . "'" : "Null";
	$fieldList["`fecha_ini_act_prod`"] = $theValue;
	
	// entidad nacimiento
	$theValue = ($GLOBALS["x_entidad_nacimiento"] != "") ? intval($GLOBALS["x_entidad_nacimiento"]) : "0";
	$fieldList["`entidad_nacimiento_id`"] = $theValue;
	
	// eacolaridad
	$theValue = ($GLOBALS["x_estudio_id"] != "") ? intval($GLOBALS["x_estudio_id"]) : "0";
	$fieldList["`escolaridad_id`"] = $theValue;
	
	// eacolaridad
	$theValue = ($GLOBALS["x_estudio_id"] != "") ? intval($GLOBALS["x_estudio_id"]) : "0";
	$fieldList["`escolaridad_id`"] = $theValue;

	// eacolaridad
	$theValue = ($GLOBALS["x_ppe"] != "") ? intval($GLOBALS["x_ppe"]) : "0";
	$fieldList["`ppe`"] = $theValue;
	
	$theValue = ($GLOBALS["x_ingreso_semanal"] != "") ? " '" . doubleval($GLOBALS["x_ingreso_semanal"]) . "'" : "Null";
	$fieldList["`ingreso_semanal`"] = $theValue;
	
	##############################
	##############################
//terminan campos de tabla de cliente.	
	
	
	
	

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
	
	////campos nuevos

		// Field delegacion_id
	$theValue = ($GLOBALS["x_localidad_id"] != "") ? intval($GLOBALS["x_localidad_id"]) : "0";
	$fieldList["`localidad_id`"] = $theValue;

	// Field ubicacion
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion"]) : $GLOBALS["x_ubicacion"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ubicacion`"] = $theValue;

	
	
	

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
	
	
	#################
		// Field delegacion_id
	$theValue = ($GLOBALS["x_localidad_id2"] != "") ? intval($GLOBALS["x_localidad_id2"]) : "0";
	$fieldList["`localidad_id2`"] = $theValue;

	// Field ubicacion
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_ubicacion2"]) : $GLOBALS["x_ubicacion2"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ubicacion_id2`"] = $theValue;

	#################

	// Field garantia_desc
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_garantia_desc"]) : $GLOBALS["x_garantia_desc"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`garantia_desc`"] = $theValue;

	// Field garantia_valor
	$theValue = ($GLOBALS["x_garantia_valor"] != "") ? " '" . doubleval($GLOBALS["x_garantia_valor"]) . "'" : "NULL";
	$fieldList["`garantia_valor`"] = $theValue;
	
	
		// campos nuevos que se agragaron 
	
	// Field valor factura
	$theValue = ($GLOBALS["x_garantia_valor_factura"] != "") ? " '" . doubleval($GLOBALS["x_garantia_valor_factura"]) . "'" : "Null";
	$fieldList["`valor_factura`"] = $theValue;
		
		// Field descripcion
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_tipo_garantia"]) : $GLOBALS["x_tipo_garantia"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`tipo_garantia`"] = $theValue;
			// Field descripcion
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_modelo_garantia"]) : $GLOBALS["x_modelo_garantia"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`modelo`"] = $theValue;
	
	// terminan los campos de garantia

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
	
	
	// CAMPOS AGREGADOS PARA INGRESOS
	
	
	//FIEL GRIO NEGOCIO
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
	
	
	
	//PERSONAS POLITICAMENTE EXPUESTAS
	$fieldList = NULL;
	$fieldList["`aval_id`"] = $x_datos_aval_id;
	
	//RELACION
	$theValue = ($GLOBALS["x_parentesco_ppe"] != "") ? intval($GLOBALS["x_parentesco_ppe"]) : "0";
	$fieldList["`relacion_id`"] = $theValue;
	
	
	// Field nombre_completo
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_nombre_ppe"]) : $GLOBALS["x_nombre_ppe"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`nombre`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_paterno_ppe"]) : $GLOBALS["x_apellido_paterno_ppe"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`a_paterno`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_apellido_materno_ppe"]) : $GLOBALS["x_apellido_materno_ppe"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`a_materno`"] = $theValue;
	
	
	
		$sSql = "UPDATE `ppe_aval` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE aval_id = ".$GLOBALS["x_datos_aval_id"].""; 
		$x_result = phpmkr_query($sSql,$conn) or die("ERROR FATAL...".phpmkr_error()."SQL STATEMENT".$sSql);
			if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					exit();
		}
	
	
	//NEGOCIO
	$fieldList = NULL;
	$fieldList["`aval_id`"] = $x_datos_aval_id;
		// Field antigudad
	$theValue = ($GLOBALS["x_giro_negocio_id"] != "") ? intval($GLOBALS["x_giro_negocio_id"]) : "0";
	$fieldList["`giro_negocio_id`"] = $theValue;
	
		// Field antiguedad
	$theValue = ($GLOBALS["x_tipo_inmueble_id"] != "") ? intval($GLOBALS["x_tipo_inmueble_id"]) : "0";
	$fieldList["`tipo_inmueble_id`"] = $theValue;
	
		// Field antiguedad
	$theValue = ($GLOBALS["x_personas_trabajando"] != "") ? intval($GLOBALS["x_personas_trabajando"]) : "0";
	$fieldList["`personas_trabajando`"] = $theValue;

	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_atiende_titular"]) : $GLOBALS["x_atiende_titular"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`atiende_titular`"] = $theValue;
	
	// Field antiguedad
	$theValue = ($GLOBALS["x_destino_credito_id"] != "") ? intval($GLOBALS["x_destino_credito_id"]) : "0";
	$fieldList["`destino_credito_id`"] = $theValue;
	


	

	phpmkr_query($sSql, $conn) or die ("Error al insertar en  negocio".phpmkr_error()."sql:".$sSql);
	$sSql = "UPDATE `negocio_aval` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE aval_id = ".$GLOBALS["x_datos_aval_id"].""; 
		$x_result = phpmkr_query($sSql,$conn) or die("ERROR FATAL...".phpmkr_error()."SQL STATEMENT".$sSql);
			if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					exit();
		}
		
		
	
	
	
	if( empty($GLOBALS["x_google_maps_id"])  ){
			
			$fieldList = NULL;			
			$fieldList["`cliente_id`"] = $GLOBALS["x_cliente_id"];
	
				//	$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";  google_maps	
				$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_latlong"]) : $GLOBALS["x_latlong"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`latlong`"] = $theValue;	
		
		
	   // insert into database
	$strsql = "INSERT INTO `google_maps` (";
	$strsql .= implode(",", array_keys($fieldList));
	$strsql .= ") VALUES (";
	$strsql .= implode(",", array_values($fieldList));
	$strsql .= ")";
	if(empty($x_readonly)){
	phpmkr_query($strsql, $conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $strsql);
	}
	
			
			
			
			}else{
	$fieldList = NULL;
	
	//	$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";  google_maps	
	$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_latlong"]) : $GLOBALS["x_latlong"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`latlong`"] = $theValue;	
		
		
	   $sSql = "UPDATE `google_maps` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE cliente_id = ".$GLOBALS["x_cliente_id"]."";
		if(empty($x_readonly)){
		$x_result = phpmkr_query($sSql,$conn) or die("ERROR FATAL...".phpmkr_error()."SQL STATEMENT".$sSql);
		
			if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
		}	}	
		

			}//else google_maps_id
			
			
			
			
			
			
	if( empty($GLOBALS["x_google_maps_neg_id"])  ){
			
			$fieldList = NULL;			
			$fieldList["`cliente_id`"] = $GLOBALS["x_cliente_id"];
			
		if($GLOBALS["x_hidden_mapa_negocio"] == 0){
		// si el valor es 0 significa que  no hya mapa de negocion entonces se inserta la misma direccion en ambos mapas
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_latlong"]) : $GLOBALS["x_latlong"];
		}else if($GLOBALS["x_hidden_mapa_negocio"] == 1){
			// significa que si hay map de negocio entonces se debe poner las dos direcciones difeerenctes 
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_latlong2"]) : $GLOBALS["x_latlong2"];
			
			}
	
				//	$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";  google_maps	
				//$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_latlong2"]) : $GLOBALS["x_latlong2"]; 
				$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
				$fieldList["`latlong`"] = $theValue;	
		
		
	   // insert into database
	$strsql = "INSERT INTO `google_maps_neg` (";
	$strsql .= implode(",", array_keys($fieldList));
	$strsql .= ") VALUES (";
	$strsql .= implode(",", array_values($fieldList));
	$strsql .= ")";
	if(empty($x_readonly)){
	phpmkr_query($strsql, $conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $strsql);
	}
	
			
			
			
			}else{
	$fieldList = NULL;
	
	
	
			if($GLOBALS["x_hidden_mapa_negocio"] == 0){
		// si el valor es 0 significa que  no hya mapa de negocion entonces se inserta la misma direccion en ambos mapas
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_latlong"]) : $GLOBALS["x_latlong"];
		}else if($GLOBALS["x_hidden_mapa_negocio"] == 1){
			// significa que si hay map de negocio entonces se debe poner las dos direcciones difeerenctes 
			$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_latlong2"]) : $GLOBALS["x_latlong2"];
			
			}
	
	//	$theValue = ($x_cliente_id != "") ? intval($x_cliente_id) : "NULL";  google_maps	
	//$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_latlong2"]) : $GLOBALS["x_latlong2"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`latlong`"] = $theValue;	
		
		
	   $sSql = "UPDATE `google_maps_neg` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE cliente_id = ".$GLOBALS["x_cliente_id"]."";
		if(empty($x_readonly)){
		$x_result = phpmkr_query($sSql,$conn) or die("ERROR FATAL...".phpmkr_error()."SQL STATEMENT".$sSql);
		
			if(!$x_result){
					echo phpmkr_error() . '<br>SQL: ' . $sSql;
					phpmkr_query('rollback;', $conn);	 
					exit();
		}}		
		

			}//else google_maps_id		
	
	
	
	
	
	
		
		//Telefonos nueva seccion	
	// telefonos nueva seccion 
	$contador_telefono =  $_POST["contador_telefono"];
	$contador_celular =   $_POST["contador_celular"];
	
	$x_telefono_casa_1 =  $_POST["x_telefono_casa_1"];
	$x_telefono_casa_2 =  $_POST["x_telefono_casa_2"];
	$x_telefono_casa_3 =  $_POST["x_telefono_casa_3"];
	$x_telefono_casa_4 =  $_POST["x_telefono_casa_4"];
	$x_telefono_casa_5 =  $_POST["x_telefono_casa_5"];
	$x_telefono_casa_6 =  $_POST["x_telefono_casa_6"];
	$x_telefono_casa_7 =  $_POST["x_telefono_casa_7"];
	$x_telefono_casa_8 =  $_POST["x_telefono_casa_8"];
	$x_telefono_casa_9 =  $_POST["x_telefono_casa_9"];
	$x_telefono_casa_10 =  $_POST["x_telefono_casa_10"];
	$x_comentario_casa_1 = $_POST["x_comentario_casa_1"];
	$x_comentario_casa_2 = $_POST["x_comentario_casa_2"];
	$x_comentario_casa_3 = $_POST["x_comentario_casa_3"];
	$x_comentario_casa_4 = $_POST["x_comentario_casa_4"];
	$x_comentario_casa_5 = $_POST["x_comentario_casa_5"];
	$x_comentario_casa_6 = $_POST["x_comentario_casa_6"];
	$x_comentario_casa_7 = $_POST["x_comentario_casa_7"];
	$x_comentario_casa_8 = $_POST["x_comentario_casa_8"];
	$x_comentario_casa_9 = $_POST["x_comentario_casa_9"];
	$x_comentario_casa_10 = $_POST["x_comentario_casa_10"];
	
	$x_telefono_celular_1 =  $_POST["x_telefono_celular_1"];
	$x_telefono_celular_2 =  $_POST["x_telefono_celular_2"];
	$x_telefono_celular_3 =  $_POST["x_telefono_celular_3"];
	$x_telefono_celular_4 =  $_POST["x_telefono_celular_4"];
	$x_telefono_celular_5 =  $_POST["x_telefono_celular_5"];
	$x_telefono_celular_6 =  $_POST["x_telefono_celular_6"];
	$x_telefono_celular_7 =  $_POST["x_telefono_celular_7"];
	$x_telefono_celular_8 =  $_POST["x_telefono_celular_8"];
	$x_telefono_celular_9 =  $_POST["x_telefono_celular_9"];
	$x_telefono_celular_10 =  $_POST["x_telefono_celular_10"];
	$x_comentario_celular_1 = $_POST["x_comentario_celular_1"];
	$x_comentario_celular_2 = $_POST["x_comentario_celular_2"];
	$x_comentario_celular_3 = $_POST["x_comentario_celular_3"];
	$x_comentario_celular_4 = $_POST["x_comentario_celular_4"];
	$x_comentario_celular_5 = $_POST["x_comentario_celular_5"];
	$x_comentario_celular_6 = $_POST["x_comentario_celular_6"];
	$x_comentario_celular_7 = $_POST["x_comentario_celular_7"];
	$x_comentario_celular_8 = $_POST["x_comentario_celular_8"];
	$x_comentario_celular_9 = $_POST["x_comentario_celular_9"];
	$x_comentario_celular_10 = $_POST["x_comentario_celular_10"];
	
	$x_compania_celular_1 =  $_POST["x_compania_celular_1"];
	$x_compania_celular_2 =  $_POST["x_compania_celular_2"];
	$x_compania_celular_3 =  $_POST["x_compania_celular_3"];
	$x_compania_celular_4 =  $_POST["x_compania_celular_4"];
	$x_compania_celular_5 =  $_POST["x_compania_celular_5"];
	$x_compania_celular_6 =  $_POST["x_compania_celular_6"];
	$x_compania_celular_7 =  $_POST["x_compania_celular_7"];
	$x_compania_celular_8 =  $_POST["x_compania_celular_8"];
	$x_compania_celular_9 =  $_POST["x_compania_celular_9"];
	$x_compania_celular_10 =  $_POST["x_compania_celular_10"];
	
	
	
	// terminan los camposd e telefono
	



	//TELEFONOS

		$sSql = " delete from telefono WHERE aval_id = " . $GLOBALS["x_datos_aval_id"];
		$x_result = phpmkr_query($sSql,$conn);

		if(!$x_result){
			echo phpmkr_error() . '<br>SQL: ' . $sSql;
			phpmkr_query('rollback;', $conn);	 
			exit();
		}
		
		$x_count_telefonos_casa = 1 ;
		echo   $GLOBALS["contador_telefono"];
	while($x_count_telefonos_casa  <=  $GLOBALS["contador_telefono"]){
		
		$x_aux_num = "x_telefono_casa_$x_count_telefonos_casa";
		$x_aux_num = $$x_aux_num;
		
		$x_aux_coment = "x_comentario_casa_$x_count_telefonos_casa";
		$x_aux_coment = $$x_aux_coment;
		
		if($GLOBALS["x_telefono_casa_$x_count_telefonos_casa"] != ""){		
		
		$fieldList = NULL;
		
		$fieldList["`cliente_id`"] = $GLOBALS["x_cliente_id"];
		$fieldList["`telefono_tipo_id`"] = 1; // uno es telefono fijo	
		
		// Field numero
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_casa_$x_count_telefonos_casa"]) : $GLOBALS["x_telefono_casa_$x_count_telefonos_casa"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`numero`"] = $theValue;
		
		// Field comentario
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_comentario_casa_$x_count_telefonos_casa"]) : $GLOBALS["x_comentario_casa_$x_count_telefonos_casa"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`comentario`"] = $theValue;
		
		
		// insert into database
			$sSql = "INSERT INTO `telefono` (";
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
		
		$x_count_telefonos_casa ++;
		}
	
	
		$x_count_telefonos_cel = 1 ;
	while($x_count_telefonos_cel  <=  $GLOBALS["contador_celular"]){
		
		$x_aux_cel = "x_telefono_celular_$x_count_telefonos_cel";
		$x_aux_cel = $$x_aux_cel;
		
		$x_aux_coment = "x_comentario_celular_$x_count_telefonos_cel";
		$x_aux_coment = $$x_aux_coment;
		
		$x_aux_comp = "x_compania_celular_$x_count_telefonos_cel";
		$x_aux_comp = $$x_aux_comp;
		
		if($GLOBALS["x_telefono_celular_$x_count_telefonos_cel"] != ""){		
		
		$fieldList = NULL;
		
		$fieldList["`cliente_id`"] = $GLOBALS["x_cliente_id"];
		$fieldList["`telefono_tipo_id`"] = 2; // uno es telefono fijo	
		
		// Field numero
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["x_telefono_celular_$x_count_telefonos_cel"]) : $GLOBALS["x_telefono_celular_$x_count_telefonos_cel"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`numero`"] = $theValue;
		
		// Field comentario
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($GLOBALS["comentario_celular_$x_count_telefonos_cel"]) : $GLOBALS["comentario_celular_$x_count_telefonos_cel"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`comentario`"] = $theValue;
		
		// Field acomania cel
		$theValue = ($GLOBALS["x_compania_celular_$x_count_$x_count_telefonos_cel"] != "") ? intval($GLOBALS["x_compania_celular_$x_count_telefonos_cel"]) : "0";
		$fieldList["`compania_id`"] = $theValue;
		
		
		// insert into database
			$sSql = "INSERT INTO `telefono` (";
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
		
		$x_count_telefonos_cel ++;
		}	
		
		
			
	
	
	return true;
}
?>
